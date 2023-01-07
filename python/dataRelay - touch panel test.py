#!/usr/bin/env python3

##############################################################################
#
# sudo apt-get install python3-pip
# sudo pip install cymysql
#
##############################################################################

import serial
import cymysql
import os
from time import sleep
from datetime import datetime
from datetime import timedelta 
import RPi.GPIO as GPIO

servername = "localhost"
username = "{username}"
password = "{userpass}"
dbname = "poolboy"

GPIO.setmode(GPIO.BCM)
GPIO.setwarnings(False)

# Global PIN setup
outputpins = []
conn = cymysql.connect(servername, username, password, dbname)
curs = conn.cursor()
sql="SELECT RelayID, RelayGPIOpin, RelayDescription FROM View_ActiveRelays"
curs.execute(sql)
relayset = curs.fetchall()
for relay in relayset:
    outputpins.append(int(relay[1]))
curs.close()
conn.close()

# Global Variables
probescantime = datetime.now()
duckloop = 5
duckiter = 5

#Set our GPIO pins to outputs and set them to off.
for i in outputpins:
    GPIO.setup(i, GPIO.OUT)
    GPIO.output(i, True)
    GPIO.output(i, False)
    GPIO.output(i, True)

touchscreen = 0
touchscreen_name = ''

# trying to find a touchscreen.
def find_touchscreen():
    try:
        ser = serial.Serial('dev/ttyACM0', 9600, timeout=1)
        ser.reset_input_buffer()
        if ser.in_waiting > 0:
            line = ser.readline().decode('utf-8').rstrip()
            print(line)
            if line == 'POOLBOY TOUCH STARTED':
                touchscreen = 1
                touchscreen_name = 'dev/ttyACM0'
    except:
        touchscreen = 0

# Read the manual setting from the database
def read_current_data(relay):
    conn = cymysql.connect(servername, username, password, dbname)
    curs = conn.cursor()
    relay_value = 0
    relay_id = 0
    timer_type = ''

    sql = "SELECT M.RelayState, M.RelayID, M.TimerType \
        FROM MainTimer M \
        INNER JOIN View_ActiveRelays A ON M.RelayID = A.RelayID \
        WHERE M.TimerDay = dayofweek(now()) \
            AND M.TimerStart <= now() \
            AND M.TimerEnd > now() \
            AND A.RelayGPIOpin = " + str(relay) + " \
        ORDER BY M.TimerType DESC LIMIT 1"
    curs.execute(sql)
    records = curs.fetchall()
    for row in records:
        relay_value = row[0]
        relay_id = row[1]
        timer_type = row[2]
    
    if timer_type != 'silence':
        sql = "SELECT COUNT(*) FROM Cooldown C \
            INNER JOIN View_ActiveRelays A ON C.RelayID = A.RelayID \
            WHERE A.RelayGPIOpin = " + str(relay)
        curs.execute(sql)
        records = curs.fetchall()
        for row in records:
            if row[0] > 0:
                relay_value = 1
        
    curs.close()
    conn.close()
    return relay_value

# read any commands received from the touchscreen and call appropriate stored proc
def read_touchscreen():
    print('reading screen')

# list current combos for sending to touchpad.
def update_touchscreen():
    conn = cymysql.connect(servername, username, password, dbname)
    curs = conn.cursor()
    combo_id = 0
    combo_name = ''

    sql = "SELECT ComboID \
            FROM MainTimer \
            WHERE TimerDay = dayofweek(now()) \
            AND TimerStart <= now() \
            AND TimerEnd > now() \
            AND TimerType <> 'silence' \
            ORDER BY TimerType DESC \
            LIMIT 1"
    curs.execute(sql)
    records = curs.fetchall()
    for row in records:
        combo_active = row[0]

    sql = "SELECT C.ComboID, C.ComboName \
        FROM ComboMain M \
        WHERE C.OverrideDisplay = 1 \
        ORDER BY C.ComboID"
    curs.execute(sql)
    records = curs.fetchall()
    for row in records:
        combo_id = row[0]
        combo_name = row[1]
        # build output string to send to arduino in format "C:(id):(active):(name)"
        if combo_id == combo_active:
            combo_command = 'C:' + combo_id + ':1:' + combo_name + '\n'
        else:
            combo_command = 'C:' + combo_id + ':0:' + combo_name + '\n'

        ser = serial.Serial(touchscreen_name, 9600, timeout=1)
        ser.reset_input_buffer()
        ser.write(combo_command.encode('itf-8'))

    curs.close()
    conn.close()

def scan_timers():
    conn = cymysql.connect(servername, username, password, dbname)
    curs = conn.cursor()

    args = [0]
    result_args = curs.callproc('clearOverride', args)

    curs.close()
    conn.commit()
    conn.close()
    return

def scan_temp_probes():
    conn = cymysql.connect(servername, username, password, dbname)
    curs = conn.cursor()
    try:
        for i in os.listdir('/sys/bus/w1/devices'):
            if i != 'w1_bus_master1':
                # print "Found probe: %s" % i 
                location = '/sys/bus/w1/devices/' + i + '/w1_slave'
                try:
                    tfile = open(location)
                    text = tfile.read()
                    tfile.close()
                    secondline = text.split("\n")[1]
                    temperaturedata = secondline.split(" ")[9]
                    temperature = float(temperaturedata[2:])
                    celsius = temperature / 1000

                    args = [i,celsius]
                    result_args = curs.callproc('writeTemperature', args)
                except:
                    # do nothing
                    j = 0
    except:
        # do nothing
        j = 0

    curs.close()
    conn.commit()
    conn.close()
    return

# Main Program
if __name__ == '__main__':
    while True:  # Repeat the code indefinitely
        for i in outputpins:
            relayState = read_current_data(i)  # get the current setting from the database for the current pin.

            # Set GPIO output
            GPIO.setup(i, GPIO.OUT)
            if relayState == None:
                GPIO.output(i, True)
            else:
                if relayState == 1:
                    GPIO.output(i, False)
                else:
                    GPIO.output(i, True)

    # Scan overrides for expired records and clean them out
        scan_timers()

    # Check duckdns - placed loop here for instant call instead of waiting a minute.
        if duckiter >= duckloop:
            duckiter = 0
            os.system("/home/pi/PoolBoy/duckdns/duck.sh")

    # Attach Touchscreen if necessary
        if touchscreen == 0:
            find_touchscreen()
        else:
            read_touchscreen()
            update_touchscreen()

    # Scan temp probes and store data, but only once a minute max
        if datetime.now() > probescantime + timedelta(seconds=60):
            scan_temp_probes()
            probescantime = datetime.now()

            duckiter += 1

        sleep(1)

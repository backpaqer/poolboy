#!/usr/bin/env python

##############################################################################
#
# sudo apt-get install python3-pip
# sudo pip install cymysql
#
##############################################################################

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

# Scan temp probes and store data, but only once a minute max
    if datetime.now() > probescantime + timedelta(seconds=60):
        scan_temp_probes()
        probescantime = datetime.now()

        duckiter += 1

    sleep(1)

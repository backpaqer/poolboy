# PoolBoy
## Pool automation.
Controls up to 16 relays in configured combinations or individually using a **Raspberry Pi**.
It supports 3 levels of priority; 
- a silence overlay that prevents noisy equipment running during law-enforced quiet times in suburban settings, 
- a normal programming schedule for filtering, chemical treating, spas etc and 
- an override mode that takes over control whether system is idle or in normal schedule mode for a default amount of time (default is 3 hours) after which it will return to whatever program is running (if at all) but doesn't override the silence overlay (eg running the spa mode will get shut down by the silence mode if they collide). 

There is one exception to the silence override, if silence shuts down a running event that includes heating, the heating cooldown timer will kick in when the heater is turned off and the pump will run for the predetermined cooldown time and then stop.

## Structure
There is a single python module that reads the database to determine current state and set relays accordingly. If temp sensors are connected, it reads the data from the sensors and stores these to the database for plotting/historical purposes.
The web front-end of the app is PHP/Javascript/CSS 

## Database
Uses a MariaDB (mysql) database for data storage.

The key idea in this app is "combos". So the table ComboMain holds the main definition for any combo with all of it's sub-items in the sub-table ComboItem. Each ComboMain may have many ComboItems that either turn a relay on, off or do-nothing. Do-nothing does exactly that - nothing. On or Off may do something depending on any competition with other currently active "Timer" items.

Everything is a "Timer" here and therefore you will find everything in the MainTimer table. All Timer items work on a 7 day week (Sun-Sat), very basic. Though I may add in a month modifier that, when present, restricts those programs to those specific months. This would be useful for "normal" filtration programs where winter modes usually only need a couple of hours of circulation vs summer modes that need 8 or more hours of circulation, particularly pools with salt cells.

I've gone heavy with procs here to dumb down the front-end code, but this may be a case of over-engineering. But it's working fine at the moment and allows for easy unit testing within the database without having to line up the web pages etc...agile y'know.

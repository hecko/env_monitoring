#!/usr/bin/env python

# Example for RC timing reading for Raspberry Pi
# Must be used with GPIO 0.3.1a or later - earlier verions
# are not fast enough!

# phoocell is connected directly to RPI via GPIO port 18 through
# a 1uF polarized capacitor as RPI does not have ADC converter

import RPi.GPIO as GPIO, time, os
import lib.utils as utils

DEBUG = 1
GPIO.setmode(GPIO.BCM)

def RCtime (RCpin):
    reading = 0
    GPIO.setup(RCpin, GPIO.OUT)
    GPIO.output(RCpin, GPIO.LOW)
    time.sleep(0.1)

    GPIO.setup(RCpin, GPIO.IN)
    # This takes about 1 millisecond per loop cycle
    while (GPIO.input(RCpin) == GPIO.LOW):
        reading += 1
    return reading

val = RCtime(18) # Read RC timing using pin #18

utils.send_to_cloud("light", val)

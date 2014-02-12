#!/usr/bin/env python
# sudo pip install pyserial

# 0 - anemometer - reset counter
# 1 - anemometer
# 2 - light sensor
# 4 - wind vane
# 5 - DHT temp and humidity sensor
# 6 - BMP180 barometric pressure sensor (+ amb. temp)

import serial
import time
import re
from math import *

import lib.utils as utils

serial_dev = '/dev/ttyACM0'
#serial_dev = '/dev/tty.usbserial-A9007KLg'

ser = serial.Serial(serial_dev, 9600)

print serial_dev + " initialized. Getting data..."
time.sleep(3)

ser.write("2")
print ser.readline()

ser.write("0")
ser.write("1")
print ser.readline()

ser.write("4")
print ser.readline()

ser.write("5")
print ser.readline()

ser.write("6")
print ser.readline()

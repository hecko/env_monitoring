#!/usr/bin/env python

# 0 - test
# 1 - anemometer
# 2 - light sensor
# 3 - temp

import serial
import time
import re

import lib.utils as utils

ser = serial.Serial('/dev/ttyACM0', 9600)
time.sleep(3)

ser.write("2")
out = ser.readline()
light = re.split(':|\\n', out)[2]
utils.send_to_cloud("hacklab", "light", light)

sleep = 5
ser.write("0")
time.sleep(sleep)
ser.write("1")
out = ser.readline()
count = re.split(':|\\n', out)[2]
freq = float(count) / float(sleep) / 2.0
utils.send_to_cloud("hacklab", "wind_freq", freq)

ser.write("3")
out = ser.readline()
light = re.split(':|\\n', out)[2]
utils.send_to_cloud("hacklab", "temp", light)

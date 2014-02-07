#!/usr/bin/env python

# 0 - anemometer - reset counter
# 1 - anemometer
# 2 - light sensor
# 3 - temp
# 4 - wind vane

import serial
import time
import re
from math import *

import lib.utils as utils

ser = serial.Serial('/dev/ttyACM0', 9600)
time.sleep(3)

ser.write("2")
out = ser.readline()
light = int(re.split(':|\\n', out)[2])
utils.send_to_cloud("hacklab", "light", light)

sleep = 5
ser.write("0")
time.sleep(sleep)
ser.write("1")
out = ser.readline()
count = re.split(':|\\n', out)[2]
freq = float(count) / float(sleep) / 2.0
mps = float(freq) / 0.777  # 0.777Hz per m/s nominal - from vectorinstruments web site
utils.send_to_cloud("hacklab", "wind_speed", mps)

ser.write("3")
out = ser.readline()
light = float(re.split(':|\\n', out)[2])
utils.send_to_cloud("hacklab", "temp", light)

ser.write("4")
out = ser.readline()
wv = re.split(':|\\n', out)[2]
wv = int(wv)%360
utils.send_to_cloud("hacklab", "wind_direction", wv)

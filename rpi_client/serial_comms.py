#!/usr/bin/env python
# sudo pip install pyserial

# 0 - anemometer - reset counter
# 1 - anemometer
# 2 - light sensor
# 3 - temp (ds18b20)
# 4 - wind vane
# 5 - DHT temp and humidity sensor

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
out = ser.readline()
light = 100.0 / 1023.0 * float(re.split(':|\\n', out)[2])
utils.send_to_cloud("hacklab", "light", light)

sleep = 12
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
temp = float(re.split(':|\\n', out)[2])
utils.send_to_cloud("hacklab", "ds_temp", temp)

ser.write("4")
out = ser.readline()
wv = re.split(':|\\n', out)[2]
wv = int(wv)%360
utils.send_to_cloud("hacklab", "wind_direction", wv)

ser.write("5")
out   = ser.readline()
temp  = float(re.split(':|\\n', out)[2])
humid = float(re.split(':|\\n', out)[4])
utils.send_to_cloud("hacklab", "dht_temp", temp)
utils.send_to_cloud("hacklab", "humidity", humid)
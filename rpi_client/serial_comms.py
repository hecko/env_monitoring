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
import subprocess
import sys
from tendo import singleton
import argparse

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

parser = argparse.ArgumentParser(description='Requests data from Arduino via serial interface and sends the data to remote server.')

parser.add_argument('-s', '--send',    action="store_true", help='Send to server, otherwise just test reading the sensors.')
parser.add_argument('-v', '--verbose', action="store_true", help='Print out more info.')

utils.args = parser.parse_args()

#serial_dev = '/dev/ttyACM0'
#serial_dev = '/dev/tty.usbserial-A9007KLg'
serial_dev = '/dev/ttyAMA0'

ser = serial.Serial(serial_dev, 9600)

utils.info(serial_dev + " initialized. Getting data...")
time.sleep(3)

ser.write("2")
out = ser.readline()
light = 100.0 / 1023.0 * float(re.split(':|\\n', out)[2])
utils.info(light)
utils.send_to_cloud("light", light)

sleep = 12
ser.write("0")
time.sleep(sleep)
ser.write("1")
out = ser.readline()
count = re.split(':|\\n', out)[2]
freq = float(count) / float(sleep) / 2.0
mps = float(freq) / 0.777  # 0.777Hz per m/s nominal - from vectorinstruments web site
utils.send_to_cloud("wind_speed", mps)

ser.write("4")
out = ser.readline()
wv = re.split(':|\\n', out)[2]
utils.send_to_cloud("wind_direction", wv)

ser.write("5")
out   = ser.readline()
temp  = float(re.split(':|\\n', out)[2])
humid = float(re.split(':|\\n', out)[4])
utils.send_to_cloud("temp", temp)
utils.send_to_cloud("humidity", humid)

ser.write("6")
out = ser.readline()
pressure = float(re.split(':|\\n', out)[2])
utils.send_to_cloud("pressure", pressure)

utils.info("Done.")

#!/usr/bin/env python

#sudo pip install pyserial

import time
import lib.utils as utils
import serial
import subprocess
import re
from tendo import singleton
import argparse

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

parser = argparse.ArgumentParser(description='Outputs everything that comes via serial line.'
             + 'and writes into a log file in /tmp directory')

parser.add_argument('-v', '--verbose', action="store_true", help='Print out more info.')

utils.args = parser.parse_args()

serial_dev = '/dev/ttyACM0'

ser = serial.Serial(serial_dev, 9600)

utils.info(serial_dev + " initialized. Getting data...")
time.sleep(3)

filename = "env_" + str(time.time()) + ".log"
myFile = open('/tmp/' + filename, 'a')
print "Writing log into /tmp/" + filename

while True:
    out = ser.readline().decode('utf-8')
    m = re.match( r"[\w\%\.]+", out)
    if not (hasattr(m, "group")):
        continue 
    out = m.group(0)
    if (len(out) >= 2):
        val = out[:-1]
        if out[-1] == "C":
            txt = "temperature," + str(time.time()) + ",C," + val
            print txt
            myFile.write(txt + "\n")
        elif out[-1] == "%":
            txt = "humidity," + str(time.time()) + ",%," + val
            print txt
            myFile.write(txt + "\n")
        elif out[-1] == "G":
            txt = "impact," + str(time.time()) + ",G," + val
            print txt
            myFile.write(txt + "\n")
        else:
            print "unknown," + str(time.time()) + ",," + val

myFile.close()
ser.close()

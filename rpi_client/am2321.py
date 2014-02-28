#!/usr/bin/python

#this script relies on am2321 temperature sensor to be connected to
#raspberry pi using 1Wire interface
#
#script is intended to be run fron cron every 5 or so minutes
#

import lib.utils as utils
import subprocess
import sys
from tendo import singleton
import argparse

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

parser = argparse.ArgumentParser(description='Gets humidity and temperature data from AM2321 and sends to server.')

parser.add_argument('-s', '--send',    action="store_true", help='Send to server, otherwise just test reading the sensors.')
parser.add_argument('-v', '--verbose', action="store_true", help='Print out more info.')

args = parser.parse_args()


def read_sensor(pin):
    out = {}
    text = subprocess.check_output(["../external/Adafruit-Raspberry-Pi-Python-Code/Adafruit_DHT_Driver/Adafruit_DHT", "2302", str(pin)])

    # split the two lines
    lines = text.split("\n")

    # make sure the crc is valid
    if len(lines) == 4:
        out['temp'] = float(lines[2].split()[2])
        out['humi'] = float(lines[2].split()[6])
        return out
    else:
        sys.exit("unable to read sensor data")

now = read_sensor(4) # pin to which the sensor is connected to

if args.verbose:
    print now

if args.send:
    utils.send_to_cloud("temp", now['temp'])
    utils.send_to_cloud("humidity", now['humi'])

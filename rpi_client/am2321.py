#!/usr/bin/python

#this script relies on am2321 temperature sensor to be connected to
#raspberry pi using 1Wire interface
#
#script is intended to be run fron cron every 5 or so minutes
#

import lib.utils as utils
import subprocess

def read_sensor(pin):
    text = subprocess.check_output(["./Adafruit_DHT_Driver/Adafruit_DHT", "2302", str(pin)])

    # split the two lines
    lines = text.split("\n")

    print lines
    return

    # make sure the crc is valid
    if lines[0].find("YES") > 0:
        # get the 9th (10th) chunk of text and lose the t= bit
        temp = float((lines[1].split(" ")[9])[2:])
        # add a decimal point
        temp /= 1000
        return temp

now = read_sensor(4)

#utils.send_to_cloud("temp_hum", now[temp])
#utils.send_to_cloud("humi_hum", now[humi])

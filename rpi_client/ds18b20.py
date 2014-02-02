#!/usr/bin/python

#this script relies on ds18b20 temperature sensor to be connected to
#raspberry pi using 1Wire interface
#
#script is intended to be run fron cron every 5 or so monites
#
#to configure make sure onewire serial matches your sensor serial
#in the read_temperature() param

import lib.utils as utils

def read_temperature(onewire):
    file  = '/sys/bus/w1/devices/' + onewire + '/w1_slave'
    tfile = open(file)
    text  = tfile.read()
    tfile.close()

    # split the two lines
    lines = text.split("\n")

    # make sure the crc is valid
    if lines[0].find("YES") > 0:
        # get the 9th (10th) chunk of text and lose the t= bit
        temp = float((lines[1].split(" ")[9])[2:])
        # add a decimal point
        temp /= 1000
        return temp

#current_temp = read_temperature('28-0000019d3e23')
onewire = '28-000001e3f96c';
current_temp = read_temperature(onewire)
utils.send_to_cloud("temp", current_temp)

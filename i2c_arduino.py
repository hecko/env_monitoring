#!/usr/bin/python

#this script is used to read i2c slave information from arduino
#it is defigned to read two bytes of info - in this case number
#of revolutions from anemometer and calculate that to frequency
#
#make sure address variable matchies the one on arduino

import smbus
import time
import lib.utils as utils

sleep = 12
address = 0x04

bus = smbus.SMBus(1)

def readData():
    data1 = bus.read_word_data(address,0)
    return data1

print "Sampling delay: " + str(sleep) + " seconds."

readData() #this also resets the counter on arduino
time.sleep(sleep)
count = readData()

freq = float(count) / float(sleep) / 2.0
utils.send_to_cloud("wind_freq", freq)

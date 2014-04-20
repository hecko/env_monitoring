#!/usr/bin/env python

import argparse
import lib.utils as utils
import sys
import re
from tendo import singleton

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

parser = argparse.ArgumentParser(description='Gets temperature data from iBeacon and send to server.' +
  'Invoke like this:' +
  'nohup hcitool lescan --duplicates > /dev/null &&' +
  'hcidump -x -R -Y | ./beacon_scan_to_cube_cx.py -v')

parser.add_argument('-s', '--send',    action="store_true", help='Send to server, otherwise just test reading the sensors.')
parser.add_argument('-v', '--verbose', action="store_true", help='Print out more info.')

utils.args = parser.parse_args()

def parse(var):
  full = "".join(var)
  full = full.replace("\n", '')
  full = re.sub(' +',' ',full)
  full = full.rstrip()
  utils.info(full)
  print len(full)
  minor = int(full[-11:-6].replace(' ', ''), 16)
  temp  = minor / 10.0
  utils.info("Minor: " + str(minor))
  utils.send_to_cloud("beacon", temp)

repeat = 1

while(repeat == 1):
  data = [0,0,0]
  data[0] = sys.stdin.readline()
  if data[0][0] == '>':
    data[1] = sys.stdin.readline()
    data[2] = sys.stdin.readline()
    if len("".join(data)) == 144:
      parse(data)
      repeat = 0

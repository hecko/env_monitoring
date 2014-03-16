#!/usr/bin/python

import time
import lib.utils as utils
import subprocess
import sys
from tendo import singleton
import argparse

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

parser = argparse.ArgumentParser(description='Sends heartbeat to server.')

parser.add_argument('-s', '--send',    action="store_true", help='Send to server, otherwise just test reading the sensors.')
parser.add_argument('-v', '--verbose', action="store_true", help='Print out more info.')

utils.args = parser.parse_args()

utils.send_to_cloud("beat", time.time())

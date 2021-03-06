#!/usr/bin/python

from tendo import singleton
import gps, os, time

import lib.utils as utils

import argparse

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

parser = argparse.ArgumentParser(description='Gets gps location data from local gpsd server and sends them to remote server.')

parser.add_argument('-s', '--send',    action="store_true", help='Send to server, otherwise just test reading the sensors.')
parser.add_argument('-v', '--verbose', action="store_true", help='Print out more info.')

utils.args = parser.parse_args()

# Listen on port 2947 (gpsd) of localhost
session = gps.gps('localhost', 2947)
session.stream(gps.WATCH_ENABLE | gps.WATCH_NEWSTYLE)

utils.info("Starting gps poller")

while True:
    try:
        utils.info("Starting try...")
        report = session.next()
        utils.info(report);
        if report['class'] == 'TPV':
            utils.info(report)
            if hasattr(report, 'time'):
                error = 0.0
                if hasattr(report, 'epx'):
                    error = (report.epx + report.epy) / 2.0
                location = str(report.lat) + ',' + str(report.lon) + ',' + str(error)
                utils.send_to_cloud("location", location)
                time.sleep(60) # sleep 60 seconds after reporting location
        time.sleep(3); # only try to get data every THIS seconds if unsuccessfull
    except KeyError:
        pass
    except KeyboardInterrupt:
        quit()
    except StopIteration:
        session = None
        utils.info("GPSD has terminated")

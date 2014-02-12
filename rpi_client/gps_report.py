#!/usr/bin/python

from tendo import singleton
import gps, os, time

import lib.utils as utils

me = singleton.SingleInstance() # will sys.exit(-1) if other instance is running

# Listen on port 2947 (gpsd) of localhost
session = gps.gps('localhost', 2947)
session.stream(gps.WATCH_ENABLE | gps.WATCH_NEWSTYLE)

while True:
    try:
        report = session.next()
        # Wait for a 'TPV' report and display the current time
        # To see all report data, uncomment the line below
        if report['class'] == 'TPV':
            print report
            if hasattr(report, 'time'):
                error = 0.0
                if hasattr(report, 'epx'):
                    error = (report.epx + report.epy) / 2.0
                location = str(report.lat) + ',' + str(report.lon) + ',' + str(error)
                utils.send_to_cloud("hacklab", "location", location)
                time.sleep(60) # sleep 60 seconds after reporting location
    except KeyError:
        pass
    except KeyboardInterrupt:
        quit()
    except StopIteration:
        session = None
        print "GPSD has terminated"

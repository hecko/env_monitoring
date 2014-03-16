#!/usr/bin/python

import time
import urllib2
import json
import ConfigParser
import io


def send_to_cloud(what, value):
    config = ConfigParser.ConfigParser()
    config.read('config.txt')
    url   = config.get("rpi", "server_put")
    token = config.get("rpi", "token")
    data = { 'token': token,
             'data': [ {
                      'timestamp': int(time.time()),
                      'key':       what,
                      'val':       value,
                     } ]
           }
    print data
    print "Sending to " + url
    req = urllib2.Request(url)
    req.add_header('Content-Type', 'application/json')
    response = urllib2.urlopen(req, json.dumps(data))
    print response.info()
    print response.getcode()

def cpu_serial():
    file = '/proc/cpuinfo'
    tfile = open(file)
    text = tfile.read()
    tfile.close()
    lines = text.split("\n")
    # get the 9th (10th) chunk of text and lose the t= bit
    serial = (lines[11].split(":")[1])[2:]
    print str(serial)
    return str(serial)

version = "0.1"

#!/usr/bin/python

import time
import urllib2
import json

def send_to_cloud(token, what, value):
    print "Sending this: " + str(what) + ", value: " + str(value)
    data = { 'token': token,
             'data': [ {
                      'timestamp': int(time.time()),
                      'key':       what,
                      'val':       value,
                     } ]
           }
    print data
    req = urllib2.Request('http://188.226.135.53:3000/put/')
    req.add_header('Content-Type', 'application/json')
    response = urllib2.urlopen(req, json.dumps(data))

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

#!/usr/bin/python

import time
import urllib2
import json

def send_to_cloud(token, what, value):
    url  = 'http://188.226.135.53:3000/put/'
    url  = 'http://lists.blava.net/~maco/env_monitoring/php_server/put.php'
    url  = 'http://knotsup.ibored.com.au/put.php'
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

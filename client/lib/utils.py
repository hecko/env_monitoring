#!/usr/bin/python

import time
import urllib2
import json

def send_to_cloud(what, value):
    data = {}
    data['time']   = int(time.time())
    data['key']    = what
    data['val']    = value
    data['serial'] = cpu_serial()
    print data
    req = urllib2.Request('http://lists.blava.net/~maco/json/in.php')
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
    return serial

version = "0.1"

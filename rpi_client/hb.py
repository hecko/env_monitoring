#!/usr/bin/python

import time
import lib.utils as utils

utils.send_to_cloud("hacklab", "beat", time.time())

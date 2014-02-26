#!/usr/bin/python

import time
import lib.utils as utils

utils.send_to_cloud("beat", time.time())

#!/usr/bin/env python

import cgitb
cgitb.enable()
import json
from datetime import datetime
import os

date_time = datetime.now().strftime("%m/%d/%Y, %H, %M, %S")
IP = os.environ["REMOTE_ADDR"]

info = { "IP Address" : IP, "today" : date_time, }

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>Hello Json Python </title>')
print('</head>')
print('<body>')
print('<h1> Hello, Python!</h1>')
print(json.dumps(info))
print('</body>')
print('</html>')

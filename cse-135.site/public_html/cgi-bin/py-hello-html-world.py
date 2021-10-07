#!/usr/bin/env python

import cgitb
cgitb.enable()

import datetime
import os

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>Hello Python</title>')
print('</head>')
print('<body>')
print('<h1> Hello, Python!</h1>')
print('The current date and time is:')
print(datetime.datetime.now())
print('<br>')
print('IP Address: ')
print (os.environ["REMOTE_ADDR"])
print('</br>')
print('</body>')
print('</html>')




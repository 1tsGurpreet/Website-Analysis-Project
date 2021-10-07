#!/usr/bin/env python

import cgitb
cgitb.enable()


import sys, os

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>POST Message Body</title>')
print('</head>')
print('<body>')
print('<h1>POST Message Body</h1>')
print('<b> Message Body: </b>')
print(sys.stdin.read())
print('</body>')
print('</html>')




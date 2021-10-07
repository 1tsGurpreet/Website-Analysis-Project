#!/usr/bin/env python

import cgitb
cgitb.enable()

import sys, os
from urlparse import urlparse, parse_qs

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>General Request Echo</title>')
print('</head>')
print('<body>')
print('<h1> General Request Echo</h1>')
print('<b>Protocol: </b>')
print(os.environ['SERVER_PROTOCOL'])
print('<br>')
print('<b>Method:</b>')
print(os.environ['REQUEST_METHOD'])
print('</br>')
print('<b>Query String: </b>')
query_string = os.environ['QUERY_STRING']
print(query_string)
print('<br>')
print('<b>Query Arguments: </b>')
print(parse_qs(query_string))
print('<br>')
print('<b> Message Body: </b>')
print(sys.stdin.read())
print('</br>')
print('</body>')
print('</html>')




#!/usr/bin/env python

import cgitb
cgitb.enable()

import sys, os
from urlparse import urlparse, parse_qs

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>GET Request Echo</title>')
print('</head>')
print('<body>')
print('<h1>Get Request Echo</h1>')
print('<b>Query String: </b>')
query_string = os.environ['QUERY_STRING']
print(query_string)
print('<br>')
print('<b>Query Arguments: </b>')
print(parse_qs(query_string))
print('</body>')
print('</html>')




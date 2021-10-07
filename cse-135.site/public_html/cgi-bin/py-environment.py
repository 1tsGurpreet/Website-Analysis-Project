#!/usr/bin/python
import sys, os
 
print ("Content-Type: text/html\n\n")
print('<html>')
print('<head>')
print('<title>Environment Variables</title>')
print('</head>')
print('<body>')
print('<h1> Environment Variables</h1>')
for name, value in os.environ.items():
         print "<b>%s\t</b>= %s <br/>" % (name, value)
print('</body>')
print('</html>')




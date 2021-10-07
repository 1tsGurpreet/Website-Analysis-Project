#!/usr/bin/env python
import Cookie
import os

cook = Cookie.SimpleCookie()
cook.load(os.environ.get('HTTP_COOKIE'))
name = cook['Username'].value

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>Python Sessions Page 2</title>')
print('</head>')
print('<body>')
print('<h1>Python Sessions Page 2</h1>')
print('<b> Cookie: </b>')
print(name)

print('<br></br>')
print("<a href=\"/cgi-bin/py-session-1.py\">Session Page 1</a><br/>")
print("<a href=\"/hw2/py-cgiform.html\">Python CGI Form</a><br />")
print("<a href=\"/cgi-bin/py-destroy-session.py\">Destroy Session</a><br />")


print('</body>')
print('</html>')    




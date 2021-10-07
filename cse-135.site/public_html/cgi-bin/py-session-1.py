#!/usr/bin/env python
import Cookie
import cgi, cgitb
import os

cook = Cookie.SimpleCookie()

form = cgi.FieldStorage()
name = form.getvalue('usrname')

if (name == "" or name ==  None):
    cook.load(os.environ.get('HTTP_COOKIE'))
    name = cook['Username'].value
else:
    cook['Username'] = name
    print(cook)

print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>Python Sessions Page 1</title>')
print('</head>')
print('<body>')
print('<h1>Python Sessions Page 1</h1>')
print('<b> Cookie: </b>')
print(name)
print('<br></br>')
print("<a href=\"/cgi-bin/py-session-2.py\">Session Page 2</a><br/>")
print("<a href=\"/hw2/py-cgiform.html\">Python CGI Form</a><br />")
print("<a href=\"/cgi-bin/py-destroy-session.py\">Destroy Session</a><br />")


print('</body>')
print('</html>')    




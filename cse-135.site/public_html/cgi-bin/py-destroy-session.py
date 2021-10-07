#!/usr/bin/env python

import Cookie

cook = Cookie.SimpleCookie()
cook['Username'] = "None"

print(cook)
print("Content-type: text/html\r\n\r\n")
print('<html>')
print('<head>')
print('<title>Session Destroyed</title>')
print('</head>')
print('<body>')
print('<h1> Session Destroyed</h1>')
print('<a href="/">Home</a><br>')
print('</body>')
print('</html>')




#!/usr/bin/ruby

require 'cgi'
require 'stringio'


cgi = CGI.new


print cgi.header
print "<html>"
print "<head>"
print "<title>POST echo</title>"
print "</head>"
print "<body>"
print "<h1>POST echo</h1>"
print "<b>Message Body: </b>"
print "<br></br>"
for i in cgi.keys do
    print i 
    print(" = ")
    print cgi[i]
    print("<br></br>")
end
print "</body>"
print "</html>"

#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new
time1 = Time.now

print cgi.header
print "<html>"
print "<head>"
print "<title>Hello Ruby</title>"
print "</head>"
print "<body>"
print "<h1>Hello Ruby!</h1>"
print "Current Time: " + time1.inspect
print "<br></br>"
print "IP Address: " 
print(ENV['REMOTE_ADDR'])
print "</body>"
print "</html>"

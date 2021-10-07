#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new

data = ENV.to_a


print cgi.header
print "<html>"
print "<head>"
print "<title>Environment Variables</title>"
print "</head>"
print "<body>"
print "<h1>Environment Variables</h1>"
data.each { |x| puts "<br> #{x} </br>" }
print "</body>"
print "</html>"

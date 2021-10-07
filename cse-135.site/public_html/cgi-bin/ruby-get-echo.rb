#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new



print cgi.header
print "<html>"
print "<head>"
print "<title>Query String</title>"
print "</head>"
print "<body>"
print "<h1>Query String</h1>"
print "<b> Query String: </b>"
print(ENV["QUERY_STRING"])
print "<br></br>"
print "<b> Parsed Query: </b>"  
print(CGI::parse(ENV["QUERY_STRING"]))
print "</body>"
print "</html>"

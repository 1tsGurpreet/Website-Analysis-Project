#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new

print cgi.header
print "<html>"
print "<head>"
print "<title>General Request Echo</title>"
print "</head>"
print "<body>"
print "<h1>General Request Echo</h1>"
print "<b> Protocol: </b>"
print(ENV["SERVER_PROTOCOL"])
print "<br></br>"
print "<b> Method: </b>"
print(ENV["REQUEST_METHOD"])
print "<br></br>"
print "<b> Query String: </b>"
print(ENV["QUERY_STRING"])
print "<br></br>"
print "<b> Parsed Query: </b>"  
print(CGI::parse(ENV["QUERY_STRING"]))
print "<br></br>"
print "<b> Message Body: </b>"  
for i in cgi.keys do
    print i 
    print(" = ")
    print cgi[i]
    print("<br></br>")
end
print "</body>"
print "</html>"

#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new

c = CGI::Cookie.new('name' => 'ruby-cookie',
                    'value' => '',
                    'expires' => Time.now)


print cgi.header('cookie' => [c], 'type' => 'text/html')
print "<html>"
print "<head>"
print "<title>Session Destroyed</title>"
print "</head>"
print "<body>"
print "<h1>Destroy Session</h1>"
print "Cookie erased! "
print '<br>' 
print("<a href=\"/hw2/ruby-cgiform.html\">Ruby CGI Form</a><br />")
print("<a href=\"/\">Home</a><br />")
print "</body>"
print "</html>"

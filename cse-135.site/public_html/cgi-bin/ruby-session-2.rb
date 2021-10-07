#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new
cookies = cgi.cookies()

#print cgi.header('cookie' => [c], 'type' => 'text/html')
print cgi.header
print "<html>"
print "<head>"
print "<title>Session Page 2</title>"
print "</head>"
print "<body>"
print "<h1>Session Page 2</h1>"
print "Cookie: "
if cookies.key?('ruby-cookie')
    print cookies['ruby-cookie'].value
else
    print 'cookie not found!'
end
print '<br>' 
print("<a href=\"/cgi-bin/ruby-session-1.rb\">Session Page 1</a><br/>")
print("<a href=\"/hw2/ruby-cgiform.html\">Ruby CGI Form</a><br />")
print("<a href=\"/cgi-bin/ruby-destroy-session.rb\">Destroy Session</a><br />")
print("<a href=\"/\">Home</a><br />")
print "</body>"
print "</html>"

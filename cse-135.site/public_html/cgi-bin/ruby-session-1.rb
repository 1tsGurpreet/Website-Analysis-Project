#!/usr/bin/ruby

require 'cgi'

cgi = CGI.new
params = cgi.params
if not params.key?('usrname')
    params['usrname'] = ''
end

c = CGI::Cookie.new('name' => 'ruby-cookie',
                    'value' => params['usrname'],
                    'expires' => Time.now + 60 * 60 * 24)

if (ENV['REQUEST_METHOD'] != 'POST' && cgi.cookies.key?('ruby-cookie') && cgi.cookies()['ruby-cookie'].value != "")
    c = cgi.cookies()['ruby-cookie']
end

print cgi.header('cookie' => [c], 'type' => 'text/html')
print "<html>"
print "<head>"
print "<title>Session Page 1</title>"
print "</head>"
print "<body>"
print "<h1>Session Page 1</h1>"
print "Cookie: "
print c.value
print '<br>' 
print("<a href=\"/cgi-bin/ruby-session-2.rb\">Session Page 2</a><br/>")
print("<a href=\"/hw2/ruby-cgiform.html\">Ruby CGI Form</a><br />")
print("<a href=\"/cgi-bin/ruby-destroy-session.rb\">Destroy Session</a><br />")
print("<a href=\"/\">Home</a><br />")
print "</body>"
print "</html>"

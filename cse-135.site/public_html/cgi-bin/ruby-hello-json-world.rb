#!/usr/bin/ruby

require 'cgi'
require 'json'

cgi = CGI.new
time1 = Time.now
my_object = { :Time => time1, :IP => ENV['REMOTE_ADDR'] }

print cgi.header
print('<html>')
print('<head>')
print('<title>Hello Json Ruby </title>')
print('</head>')
print('<body>')
print('<h1> Hello, Ruby!</h1>')
print(JSON.pretty_generate(my_object))
print('</body>')
print('</html>')
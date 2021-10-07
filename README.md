# Website Data Analysis Project

## Available Scripts 

To collect data for the main website (cse-135.site)

Go under the cse-135.site/public_html/rest-api directory and run:

`php -S localhost:3000 -t api`

To manage data for the report (reporting.cse-135.site) 

go under the reporting.cse-135.site/public_html/rest-api directory and run:

`php -S localhost:3030 -t api`

This will allow you to look at the data of the user and if granted admin rights you can manage the users through a friendly user interface

## reporting.cse-135.site

Through an authentication form, this website will redirect you to a dashboard and report of the data collected from our main website. This includes static information, performance, and user activity such as mouse position, mouse clicks, keystrokes etc.

## cse-135.site

This is a simple website that explores HTTP properties in different scripting languages. Its main use is for our collector.js to collect data from the users and be displayed and analyzed in reporting.cse-135.site



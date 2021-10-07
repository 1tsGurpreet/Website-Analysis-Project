<%@ page import java.time.format.DateTimeFormatter;
         import java.time.LocalDateTime; 
%> 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hello JSP</title>
</head>
<body>
    <h1>Hello, JSP</h1>
    <strong>Date: </strong>
    <%
      DateTimeFormatter time = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");  
      LocalDateTime now = LocalDateTime.now();  
      out.println(time.format(now));
    %>
    <br>
    <strong>Your IP: </strong>
    <% out.println(request.getRemoteAddr()); %>
</body>
</html>

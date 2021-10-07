import java.time.format.DateTimeFormatter;
import java.time.LocalDateTime; 
import javax.servlet.http.HttpServletRequest;
import java.io.*;
import javax.servlet.*;
import javax.servlet.http.*;

public class javaHelloHtmlWorld
{
        public static void main(String[] args){
        DateTimeFormatter time = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");  
        LocalDateTime now = LocalDateTime.now();  
                
        HttpServletRequest request = (HttpServletRequest) req;
        String ip = request.getRemoteAddr();
                
        System.out.println(ip);
      

        System.out.println("Cache-Control: no-cache");
        System.out.println("Content-type: text/html");
        System.out.printf("\n");
        System.out.printf("\n");
        System.out.println("<html><head><title>Hello CGI World</title></head>");
        System.out.println("<body><h1>Hello, Java</h1>");
        System.out.printf("The current date and time is: ");
        System.out.println(time.format(now));
        System.out.println("<br>");
        System.out.printf("IP Address: ");
        //System.out.println(getClientIp());        
        System.out.println("</br>");
        System.out.println("</body>");
        System.out.println("</html>");
       }
        

}


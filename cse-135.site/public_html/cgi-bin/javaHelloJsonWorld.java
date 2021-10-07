import java.time.format.DateTimeFormatter;
import java.time.LocalDateTime; 
import java.util.*;
import org.json.simple.JSONObject;

public class javaHelloJsonWorld
{
        public static void main(String[] args) {
        DateTimeFormatter time = DateTimeFormatter.ofPattern("yyyy/MM/dd HH:mm:ss");  
        LocalDateTime now = LocalDateTime.now();  
                
        JSONObject obj = new JSONObject();

      obj.put("name", "foo");
      obj.put("num", new Integer(100));
      obj.put("balance", new Double(1000.21));
      obj.put("is_vip", new Boolean(true));

      System.out.print(obj);
      

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

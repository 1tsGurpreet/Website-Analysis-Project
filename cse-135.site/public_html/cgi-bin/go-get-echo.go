package main
import "fmt"
import "net/http/cgi"
import "net/url"

func main() {
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>GET Echo</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    fmt.Print("<h1>GET Echo</h1>")

    req, err := cgi.Request()
    if err == nil {
        m, err := url.ParseQuery(req.URL.RawQuery)
        fmt.Print("<p><strong>Query String:  </strong>", req.URL.RawQuery, "</p>")
        fmt.Print("<strong>Parameters: </strong><ul>")
        if err == nil {
            for k, v := range m{
                fmt.Print("<li><strong>", k, ": </strong>", v, "</li>")
            }
        }
        fmt.Print("</ul>")
    }
    fmt.Print("</body>")
    fmt.Print("</html>")
}

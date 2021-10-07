package main
import "fmt"
import "net/http/cgi"
import "net/url"

func main() {
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>General Echo</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    fmt.Print("<h1>General Echo</h1>")

    req, err := cgi.Request()
    if err == nil {
        fmt.Print("<strong>Protocol: </strong>", req.Proto, "<br>")
        fmt.Print("<strong>Method: </strong>", req.Method, "<br>")

        m, err := url.ParseQuery(req.URL.RawQuery)
        fmt.Print("<p><strong>Query String:  </strong>", req.URL.RawQuery, "</p>")
        fmt.Print("<strong>Query Parameters: </strong><ul>")
        if err == nil {
            for k, v := range m{
                fmt.Print("<li><strong>", k, ": </strong>", v, "</li>")
            }
        }
        fmt.Print("</ul>")

        req.ParseForm()
        fmt.Print("<strong>Body Parameters: </strong><ul>")
        if err == nil {
            for k, v := range req.PostForm{
                fmt.Print("<li><strong>", k, ": </strong>", v, "</li>")
            }
        }
        fmt.Print("</ul>")
    }
    fmt.Print("</body>")
    fmt.Print("</html>")
}

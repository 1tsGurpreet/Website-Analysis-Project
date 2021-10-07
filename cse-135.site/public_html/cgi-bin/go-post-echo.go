package main
import "fmt"
import "net/http/cgi"

func main() {
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>POST Echo</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    fmt.Print("<h1>POST Echo</h1>")

    req, err := cgi.Request()
    if err == nil {
        req.ParseForm()
        fmt.Print("<strong>Parameters: </strong><ul>")
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

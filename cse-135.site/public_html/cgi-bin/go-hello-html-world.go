package main
import "fmt"
import "net/http/cgi"
import "time"

func main() {
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>Hello, Go!</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    now := time.Now()
    fmt.Print("<h1>Team WacDonalds was here - Hello, Go!</h1>")
    fmt.Print("<p><strong>Date/Time: </strong>", now.String(), "</p>")

    req, err := cgi.Request()
    if err == nil {
        fmt.Print("<p><strong>Your IP Address: </strong>", req.RemoteAddr, "</p>")
    }
    fmt.Print("</body>")
    fmt.Print("</html>")
}

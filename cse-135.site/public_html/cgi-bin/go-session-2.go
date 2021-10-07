package main
import "fmt"
import "net/http/cgi"

func main() {
    req, _ := cgi.Request()
    cook, err := req.Cookie("golang-cookie")

    fmt.Println("Set-Cookie:", cook.String())
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>Session Page 2</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    fmt.Print("<h1>GO Session Page 2</h1>")
    if err != nil {
        fmt.Print("Uh-oh, the cookie must have broken. We can't find your name.<br>")
    } else {
        fmt.Print("<strong>Name: </strong>", (*cook).Value, "<br>")
    }
    fmt.Print("<a href=\"/cgi-bin/go-session-1.cgi\">Session Page 1</a><br/>")
    fmt.Print("<a href=\"/hw2/go-cgiform.html\">Go CGI Form</a><br />")
    fmt.Print("<a href=\"/cgi-bin/go-destroy-session.cgi\">Destroy Session</a><br />")
    fmt.Print("<a href=\"/\">Home</a><br />")
    fmt.Print("</body>")
    fmt.Print("</html>")
}

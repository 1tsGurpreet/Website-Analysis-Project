package main
import "fmt"
import "net/http/cgi"
import "net/http"
import "time"

func main() {
    req, _ := cgi.Request()
    cook, err := req.Cookie("golang-cookie")
    if req.Method == "POST" || err != nil {
        req.ParseForm()
        name := ""
        if val, ok := req.Form["name"]; ok {
            name = val[0]
        }
        cook = &http.Cookie{ Name: "golang-cookie",
                            Value: name,
                            Expires: time.Now().Add(time.Hour * 7),
                            HttpOnly: true,
                            Unparsed: []string{"name=" + name}}
    }
    fmt.Println("Set-Cookie:", cook.String())
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>Session Page 1</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    fmt.Print("<h1>GO Session Page 1</h1>")
    fmt.Print("<strong>Name: </strong>", (*cook).Value, "<br>")

    fmt.Print("<a href=\"/cgi-bin/go-session-2.cgi\">Session Page 2</a><br/>")
    fmt.Print("<a href=\"/hw2/go-cgiform.html\">Go CGI Form</a><br />")
    fmt.Print("<a href=\"/cgi-bin/go-destroy-session.cgi\">Destroy Session</a><br />")
    fmt.Print("<a href=\"/\">Home</a><br />")
    fmt.Print("</body>")
    fmt.Print("</html>")
}

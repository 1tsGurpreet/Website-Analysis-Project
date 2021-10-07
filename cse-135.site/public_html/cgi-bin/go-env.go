package main
import "fmt"
import "os"
import "strings"

func main() {
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>Environment Variables</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    fmt.Print("<h1>Environment Variables</h1>")

    fmt.Print("<ul>")
    env := os.Environ()
    for _, e := range env {
        pair := strings.SplitN(e, "=", 2)
        fmt.Print("<li><strong>", pair[0], ": </strong>", pair[1], "</li>")
    }
    fmt.Print("</ul>")
    fmt.Print("</body>")
    fmt.Print("</html>")
}

package main
import "fmt"
import "net/http/cgi"
import "time"
import "encoding/json"

type Info struct{
    Time string `json:"time"`
    IP string `json:"ip"`
}

func main() {
    fmt.Println("Cache-Control: no-cache")
    fmt.Println("Content-type: text/html\n")
    fmt.Print("<html><head>")
    fmt.Print("<title>Hello, Go!</title>")
    fmt.Print("</head>")
    fmt.Print("<body>")
    now := time.Now()
    fmt.Print("<h2>Hello, Go!</h2>")
    info := Info{Time: now.String(), IP: "0.0.0.0"}
    req, err := cgi.Request()
    if err == nil {
        info = Info{Time: now.String(), IP: req.RemoteAddr}
    }
    info_json, err := json.Marshal(info)
    if err != nil{
        fmt.Println(err)
    } else{
        fmt.Println(string(info_json))
    }
    fmt.Print("</body>")
    fmt.Print("</html>")
}

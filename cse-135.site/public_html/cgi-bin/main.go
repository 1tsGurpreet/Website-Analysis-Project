#!/usr/lib/go-1.10/bin run

package main

import	"fmt"


func main() {
    fmt.Println("Content-type: text/html\r\n\r\n")
    fmt.Println("<html>")
    fmt.Println("<head>")
    fmt.Println("<title>Hello Python</title>")
    fmt.Println("</head>")
    fmt.Println("<body>")
    fmt.Println("<h1> Hello, Python!</h1>")
    //fmt.Println(strconv.Atoi(params["REMOTE_PORT"]))
    fmt.Println("</body>")
    fmt.Println("</html>")
}

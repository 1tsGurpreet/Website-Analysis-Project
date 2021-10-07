<?php
require "../start.php";
use Src\Users;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST,PUT,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode( '/', $uri );
// $uri[1] = rest-api, $uri[2] = apir, $uri[3] = endpoint, $uri[4] = id number

// the post id is, of course, optional and must be a number
$requestMethod = $_SERVER["REQUEST_METHOD"];

$postId = null;
if (isset($uri[4]) && $uri[4] != '') {
    $postId = $uri[4];
}

if ($requestMethod == 'POST' && $postId != null){
  header("HTTP/1.1 404 Not Found");
  exit();
}

if ( ($requestMethod == 'PUT' || $requestMethod == 'DELETE') && $postId == null ){
  header("HTTP/1.1 404 Not Found");
  exit();
}

// endpoints starting with `/post` or `/posts` for GET shows all posts
// everything else results in a 404 Not Found
if ($uri[3] == 'users') {
  $controller = new Users($dbConnection, $requestMethod, $postId);
//  if ($requestMethod == 'POST')
//    $controller2 = new StaticInfo($DOdbConnection, $requestMethod, $postId);
} else {
  header("HTTP/1.1 404 Not Found");
  exit();
}

// pass the request method and post ID to the Post and process the HTTP request:
$controller->processRequest();
if (isset($controller2))
  $controller2->processRequest();


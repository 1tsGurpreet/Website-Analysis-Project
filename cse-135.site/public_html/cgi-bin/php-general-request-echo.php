<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>General Echo</title>
</head>
<body>
    <h1>General Echo</h1>
    <?php
      echo "<strong>Request type: </strong>";
      echo $_SERVER['REQUEST_METHOD'] . "<br>";
      echo "<strong>Protocol: </strong>";
      echo $_SERVER['SERVER_PROTOCOL'] . "<br>";
      echo "<strong>Query: </strong>";
      if (array_key_exists("QUERY_STRING", $_SERVER)){
        echo "<ul>";
        $q = array();
        parse_str($_SERVER['QUERY_STRING'], $q);
        foreach ((array) $q as $k => $v) {
          echo "<li><strong>" . $k .":</strong> " . $v ."</li>";
        }
        echo "</ul>";
      }

      echo "<strong>Request Contents: </strong><br>";
      if(empty($_POST)){
        parse_str(file_get_contents('php://input'), $_POST);
      }
      echo "<ul>";
      foreach ((array) $_POST as $k => $v) {
        echo "<li><strong>" . $k .":</strong> " . $v ."</li>";
      }
	  echo "</ul>";
     ?>
    
</body>
</html>

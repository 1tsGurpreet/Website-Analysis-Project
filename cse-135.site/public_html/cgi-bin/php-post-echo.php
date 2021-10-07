<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>POST Echo</title>
</head>
<body>
    <h1>POST Echo</h1>
    <?php
      #echo $_SERVER['REQUEST_METHOD'] . "<br>";
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

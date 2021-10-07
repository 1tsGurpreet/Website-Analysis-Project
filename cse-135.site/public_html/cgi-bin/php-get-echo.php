<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>GET Echo</title>
</head>
<body>
    <h1>GET Echo</h1>
    <?php
     echo "<strong>Query String: </strong>";
     if (array_key_exists("QUERY_STRING", $_SERVER)){
         echo  $_SERVER['QUERY_STRING'] ;
     }
     echo "<br>";
     echo "<ul>";
      foreach ((array) $_GET as $k => $v) {
        echo "<li><strong>" . $k .":</strong> " . $v ."</li>";
       }
	  echo "</ul>";
     ?>
    
</body>
</html>

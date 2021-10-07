<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Environment variables</title>
</head>
<body>
    <h1>Environment Variables</h1>
    <?php
	  echo "<h2>Environment Variables</h2>";
	  
	  foreach ((array) getenv() as $k => $v) {
        echo "<strong>" . $k .":</strong> " . $v ."<br>";
      }
      
	  echo "<br><h2>Server Variables</h2>";

	  foreach ($_SERVER as $k => $v) {
        echo "<strong>" . $k .":</strong> " . $v ."<br>";
      }
     ?>
    
</body>
</html>

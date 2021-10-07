<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Hello PHP</title>
</head>
<body>
    <h1>Hello, PHP</h1>
    <?php
	  $hello->today = date("Y-m-d H:i:s");
	  $hello->ip = $_SERVER['REMOTE_ADDR'];
	  $hello_JSON = json_encode($hello);
      
      echo $hello_JSON;
     ?>
    
</body>
</html>
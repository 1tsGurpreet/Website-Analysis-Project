<?php
  if (session_status() !== PHP_SESSION_ACTIVE){
    session_start();
  }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Session Destroyed</title>
</head>
<body>
    <h1>Session Destroyed</h1>
    <?php
      session_unset();
      session_destroy();
      echo '<a href="/">Home</a><br>';
     ?>
    
</body>
</html>

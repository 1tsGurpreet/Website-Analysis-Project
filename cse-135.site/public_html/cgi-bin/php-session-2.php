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
    <title>Session Page 2</title>
</head>
<body>
    <h1>Session Page 2</h1>
    <?php
      echo "<strong>Name: </strong>" . $_SESSION['name'] . "<br>";
      echo "<strong>ID: </strong>" . session_id() . "<br>";
      echo '<a href="php-session-1.php?' . session_id() . '">Page 1</a><br>';
      echo '<a href="php-session-destroy.php?' . session_id() . '">Destroy Session</a><br>';
      echo '<a href="/">Home</a><br>';
     ?>
    
</body>
</html>

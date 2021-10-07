<?php
  if (session_status() !== PHP_SESSION_ACTIVE){
    session_start();
    if(! empty($_POST)){
      #parse_str(file_get_contents('php://input'), $_POST);
      $_SESSION['name'] = $_POST['name'];
    }
  }
 ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Session Page 1</title>
</head>
<body>
    <h1>Session Page 1</h1>
    <?php
      echo "<strong>Name: </strong>" . $_SESSION['name'] . "<br>";
      echo '<a href="php-session-2.php?' . session_id() . '">Page 2</a><br>';
      echo '<a href="php-session-destroy.php?' . session_id() . '">Destroy Session</a><br>';
      echo '<a href="/">Home</a><br>';
      session_write_close();
     ?>
    
</body>
</html>


<?php
    include("config.php");
    include('session.php');
    if ($rights_session == 0) { // Start the condition >
      header("Location: index.php");
    }
?>

    

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <link rel="stylesheet" type="text/css" href="/users.css">
  <title>CRUD ENPOINT</title>
  <!--Script Reference[1]-->
  <script src="https://cdn.zinggrid.com/zinggrid.min.js" defer></script>
  <script src="/users.js" ></script>
</head>
<body>
<nav>

<h1>Users</h1>
<h1 id='logout' class='nav-right'><a href = "logout.php">Sign Out</a></h1>
<h1 id ='home_page' class='nav-right'><a href = "index.php">Homepage</a></h1>
<h1><a href = "/clickLoad.php" class='nav-right'>Click Details</a></h1> 
<h1><a href = "/loadTime.php" class='nav-right'>Load Details</a></h1> 
</nav>
  <!--Grid Component Placement[2]-->
  <zing-grid editor-controls="all" pager>
    <zg-data>
      <zg-param name="src" value="https://reporting.cse-135.site/php_api/users">
    </zg-data>
  </zing-grid>
</body>
</html>

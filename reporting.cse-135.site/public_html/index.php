<?php
   include('session.php');
?>
<html> 
  <head>
    <title>Dashboard</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="apple-touch-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="/dashboard.css">
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script src="https://cdn.zinggrid.com/zinggrid.min.js" defer></script>
    <script src="/dashboard.js"></script>
  </head>
   
<body>
  <nav>

    <h1><a href = '/'>Welcome <?php echo $login_session;?></a></h1>
    <h1 id='logout' class='nav-right'><a href = "logout.php">Sign Out</a></h1>
    <?php 
      if ($rights_session == 1) {
        ?> <h1 id ='user_management' class='nav-right'><a href = "users.php">User Management</a></h1><?php
      } ?>
    <h1><a href = "/clickLoad.php" class='nav-right'>Click Details</a></h1> 
    <h1><a href = "/loadTime.php" class='nav-right'>Load Details</a></h1> 
  </nav>

  <section id='dash-summary'>
    <h2>Summary</h2>
    <article id='txt-summary'>
      <h3>At a Glance</h3>
      <ul>
        <li>There have been <strong id='week-visit'>---</strong> visits in the last 7 days</li>
        <li>In that time, the average load time was <strong id='week-load'>---</strong></li>
        <li>In that time, the average clicks per session time was <strong id='week-click'>---</strong></li>
        <li>In that time, the average session length was <strong id='week-session'>---</strong></li>
        <li>There have been <strong id='past-visit'>---</strong> visits earlier than 7 days ago</li>
        <li>There have been <strong id='undated-visit'>---</strong> undated visits</li>
        <li>In total, the average load time is <strong id='total-load'>---</strong></li>
        <li>In total, the average session length is <strong id='total-session'>---</strong></li>
        <li>In total, the average clicks per session is <strong id='total-click'>---</strong></li>
      </ul>
    </article>

    <div id="pie-chart">
    </div>
  </section>

  <section class='user-summary'>
    <h2>What your users look like</h2>
    <article id='xxx-conn-chart-wrapper'>
    <div id="conn-chart">
    </div>
    </article>

    <article id='xxx-screen-chart-wrapper'>
    <div id="screen-chart">
    </div>
    </article>

    <article id='xxx-lang-chart-wrapper'>
    <div id="lang-chart">
    </div>
    </article>
  </section>

  <div id="bar-chart" class="dataviz-container">
    <h2>Access Patterns (Dated Data Only)</h2>
  </div> 

  <div id="grid" class="dataviz-container">
  <zing-grid
    caption="Access Logs (with dateTime Data)"
    search
    sort
    pager
    page-size='25'
    data='' id='static-grid'>
  </zing-grid>
  </div> 

  <!--div id="scatter" class="dataviz-container">
    <h1>3-series line chart</h1>
  </div-->
</body>
   
</html>

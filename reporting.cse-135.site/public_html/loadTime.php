<?php
   include('session.php');
?>
<html> 
  <head>
    <title>Load Details</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="apple-touch-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="/dashboard.css">
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script src="/loadTime.js"></script> 
  </head>
   
<body>
    <nav>
    <h1><a href = '/'>Welcome <?php echo $login_session;?></a></h1>
    <h1 id='logout' class='nav-right'><a href = "logout.php">Sign Out</a></h1>
    <?php
      if ($rights_session == 1) {
        ?> <h1 id ='user_management' class='nav-right'><a href = "users.php">User Management</a></h1><?php
      } ?>
    <h1><a href = "/metricname.php" class='nav-right'>Click Details</a></h1>
    <h1><a href = "/loadTime.php" class='nav-right'>Load Details</a></h1>
  </nav>

  <section id='dash-summary'>
    <h2>Load Details</h2>
    <article id='txt-summary'>
      <h3>At a Glance</h3>
      <ul>
        <li>There have been <strong id='week-visit'>---</strong> visits in the last 7 days</li>
        <li>In that time, the average load time was <strong id='week-load'>---</strong></li>
        <li>There have been <strong id='month-visit'>---</strong> visits in the last 30 days</li>
        <li>In that time, the average load time was <strong id='month-load'>---</strong></li>
        <li>There have been <strong id='past-visit'>---</strong> visits earlier than 30 days ago</li>
        <li>There have been <strong id='undated-visit'>---</strong> undated visits</li>
        <li>In total, there have been <strong id='total-visit'>---</strong> visits</li>
        <li>In total, the average load time is <strong id='total-load'>---</strong></li>
      </ul>
    </article>

    <div id="pie-chart">
    </div>
  </section>

  <section class='user-summary'>
    <h2>What recent load times look like</h2>
    <article id='xxx-week-pie-chart-wrapper'>
    <div id="week-pie-chart">
    </div>
    </article>

    <article id='xxx-month-pie-chart-wrapper'>
    <div id="month-pie-chart">
    </div>
    </article>
  </section>

  <section class='bar-summary' style='margin-top:5em;'>
    <h2>Where load time is spent</h2>
    <article>
    <div id="week-percent-chart" class='hbar-chart'>
    </div>
    </article>

    <article>
    <div id="month-percent-chart" class='hbar-chart'>
    </div>
    </article>
  </section>

  <section class='user-summary'>
    <h2>Notes</h2>
    <article class='notes-wrapper' style='padding-top:0;'>
      <h3>What this is intended to answer:</h3>
      <ul class='notes'>
        <li>How long does it take to load?</li>
        <li>If there were changes in the last 7 days, then has the load time improved or worsened?</li>
        <li>Where is the load time spent?</li>
        <li>Does that differ depending on how long it takes to load? For example, might the longer load times be caused by a slow response or slow HTML parsing?</li>
        <li>Where should you target in order to improve load time the most?</li>
      </ul>
    </article>

    <article class='note-wrapper' style='padding-top:0;'>
      <h3>Words of caution:</h3>
      <ul class='notes'>
        <li>HTML may start parsing before the response finishes, the section marked as 'HTML parsing' is the time when only HTML parsing is happening.</li>
        <li>The load time may be divided into finer granularities, but that data was not recorded.</li>
      </ul>
    </article>
  </section>
</body>
   
</html>

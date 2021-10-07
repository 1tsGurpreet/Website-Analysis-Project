<?php
include('session.php');
?>
<html>
  <head>
    <meta charset="utf-8">
    <title>Report</title>
    <link href="favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <link rel="apple-touch-icon" href="favicon.ico">
    <link rel="stylesheet" type="text/css" href="/clickLoad.css">
    <link rel="stylesheet" type="text/css" href="/dashboard.css">
    <script src="https://cdn.zingchart.com/zingchart.min.js"></script>
    <script src="/clickLoad.js"></script>
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
    <h2>Click Details</h2>
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
  </section>

<header id="three">
	<h1>Click page x and y scatter chart</h1>
</header>

<div id='myChart'></div>
  <script>
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "b55b025e438fa8a98e32482b5f768ff5"];
    var myConfig4 = {
      "type": "scatter",
      "series": [{
          "values": [
            [454, 603],
            [438, 602],
            [194, 593],
            [76, 602],
            [467, 702],
            [616, 727],
            [616, 727],
            [616, 727],
	    [700, 677],
	    [269, 297],
	    [269, 297],
	    [269, 297],
	    [745, 785],
	    [745, 785],
	    [705, 689],
	    [399, 499],
	    [475, 652],
	    [573, 683],
	    [664, 671],
          ]
        }
      ]
    };
 
    zingchart.render({
      id: 'myChart',
      data: myConfig4,
      height: 400,
      width: "100%"
    });
  </script>

<header id="two">
	<h1>Click screen x and y scatter chart<h1>
</header>

<div id='screen'></div>
  <script>
    ZC.LICENSE = ["569d52cefae586f634c54f86dc99e6a9", "b55b025e438fa8a98e32482b5f768ff5"];
    var myConfig4 = {
      "type": "scatter",
      "series": [{
          "values": [
            [3014, 706],
            [2998, 705],
            [2754, 696],
            [2636, 705],
            [3027, 805],
            [3176, 830],
            [3176, 8300],
            [3176, 830],
            [2829, 400],
	    [716, 415],
	    [476, 282],
	    [487, 257],
	    [765, 271],
	    [811, 301],
          ]
        },
      ]
    };

    zingchart.render({
      id: 'screen',
      data: myConfig4,
      height: 400,
      width: "100%"
    });
  </script>

<section class='user-summary'>
    <h2>Notes</h2>
    <article class='notes-wrapper' style='padding-top:0;'>
      <h3>What this is intended to answer:</h3>
      <ul class='notes'>
        <li>how many user visit our web??</li>
        <li>Is the web popular?</li>
	<li>What position did the user usually click?</li>
	<li>Is the user not activity in the web?</li>
      </ul>
    </article>

  </section>


</body>

</html>


<?php
   include('config.php');
   session_start();
   
   $user_check = $_SESSION['login_user'];
   
   $ses_sql = mysqli_query($link,"select username from users where username = '$user_check' ");
   
   $row = mysqli_fetch_array($ses_sql,MYSQLI_ASSOC);
   
   $login_session = $row['username'];

   $rights_check = $_SESSION['rights'];
   
   $ses_sql1 = mysqli_query($link,"select rights from users where rights = '$rights_check'");
   
   $row1 = mysqli_fetch_array($ses_sql1,MYSQLI_ASSOC);
   
   $rights_session = $row1['rights'];
   
   if(!isset($_SESSION['login_user'])){
      header("location:login.php");
      die();
   }
?>

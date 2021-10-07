<?php
   include("config.php");
   session_start();
   
   if($_SERVER["REQUEST_METHOD"] == "POST") {
      // username and password sent from form 
      
      $myusername = mysqli_real_escape_string($link,$_POST['username']);
      $mypassword = mysqli_real_escape_string($link,$_POST['password']); 
      
      $sql1 = "SELECT salt FROM users WHERE username = '$myusername' or email = '$myusername'";
      $result1 = mysqli_query($link, $sql1);
      $row1 = mysqli_fetch_array($result1,MYSQLI_ASSOC);
      $salt = $row1['salt'];

      $sql2 = "SELECT passcode FROM users WHERE username = '$myusername' or email = '$myusername'";
      $result2 = mysqli_query($link, $sql2);
      $row2 = mysqli_fetch_array($result2,MYSQLI_ASSOC);
      $passcode = $row2['passcode'];

      $string_to_be_hashed = $mypassword . $salt;

      $hashedPassword = hash('sha256', $string_to_be_hashed);

      $sql3 = "SELECT username FROM users WHERE username = '$myusername' or email = '$myusername'";
      $result3 = mysqli_query($link, $sql3);
      $row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC);
      $user = $row3['username'];

      $sql4 = "SELECT rights FROM users WHERE username = '$myusername' or email = '$myusername'";
      $result4 = mysqli_query($link, $sql4);
      $row4 = mysqli_fetch_array($result4,MYSQLI_ASSOC);
      $rights = $row4['rights'];


      
      // $sql3 = "SELECT id FROM users WHERE username = '$myusername' and passcode = '$mypassword'";
      // $result3 = mysqli_query($link,$sql3);
      
      // $row3 = mysqli_fetch_array($result3,MYSQLI_ASSOC);
      // $id = $row3['id'];
      
      // $count = mysqli_num_rows($result3);
      
      // If result matched $myusername and $mypassword, table row must be 1 row
		
      if($hashedPassword == $passcode) {
         $_SESSION['login_user'] = $user;
         $_SESSION['rights'] = $rights;
      
         header("Location: index.php");
      }else {
         $error = "Your Login Name or Password is invalid";
      }
   }
?>
<html>
   
   <head>
      <title>Login Page</title>
      
      <style type = "text/css">
         body {
            font-family:Arial, Helvetica, sans-serif;
            font-size:14px;
         }
         label {
            font-weight:bold;
            width:100px;
            font-size:14px;
         }
         .box {
            border:#666666 solid 1px;
         }
      </style>
      
   </head>
   
   <body bgcolor = "#FFFFFF">
	
      <div align = "center">
         <div style = "width:300px; border: solid 1px #333333; " align = "left">
            <div style = "background-color:#333333; color:#FFFFFF; padding:3px;"><b>Login</b></div>
				
            <div style = "margin:30px">
               
               <form action = "" method = "post">
                  <label>User/email  :</label><input type = "text" name = "username" class = "box"/><br /><br />
                  <label>Password  :</label><input type = "password" name = "password" class = "box" /><br/><br />
                  <input type = "submit" value = " Submit "/><br />
               </form>
               
               <div style = "font-size:11px; color:#cc0000; margin-top:10px"><?php echo $error; ?></div>
					
            </div>
				
         </div>
			
      </div>   

   </body>
</html>
<?php
session_start();
$nosession=0;
$login='login.php';
require_once('connect.php');
if(isset($_SESSION['user_email']))
   $login='login.php';
else if(isset($_SESSION['emp_id']))
   $login='admin.php';
else
{
   $nosession=1;
}
?>
<html>
   <head>
      <title>Logout</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>
   <body>
      <div class="centerFlex">
         <?php
            if($nosession==1)
            {
               echo 'Not Logged In<div><a href="'.$login.'" role="button" class="btn btn-primary">Login Now</a></div>';
            }
            else
            {
               session_unset();
               session_destroy();
               echo '<strong class="text-success">Logged out successfully!</strong><br><a class="btn btn-primary" href="'.$login.'" role="button">Login Back In</a>';
            }
         ?>
      </div>
   </body>
</html>

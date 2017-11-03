<?php
$emailexists = 1;
$needforecho = 0;
$needforlogin=0;
require_once('connect.php');
session_start();
if(isset($_SESSION['emp_id']))
{
   $department=$_SESSION['department'];
   if(strcmp("Managing",$department)==0)
   {
      header("Location:".get_base_url()."manager.php");
      die();
   }
   else
   {
      header("Location:".get_base_url()."employee.php");
      die();
   }
}

if(isset($_SESSION['user_email']))
{
   $stype=$_SESSION['stype'];
   if (strcmp($stype,'buyer')==0) {
      header("Location:".get_base_url()."welcomeb.php");
      die();
   } else {
      header("Location:".get_base_url()."welcomes.php");
      die();
   }
}
if(isset($_SESSION['unlog']))
{
   if($_SESSION['unlog']==1)
   {
      $_SESSION['unlog']=0;
      $needforlogin=1;
   }
}


if (isset($_POST['login'])) {
    $missingdata = array();
    if (empty($_POST['email'])) {
        $missingdata[] = 'email';
    } else {
        $email = mysqli_real_escape_string($dbc, $_POST['email']);
    }
    if (empty($_POST['pass'])) {
        $missingdata[] = 'pass';
    } else {
        $pass     = mysqli_real_escape_string($dbc, $_POST['pass']);
        $passhash = hash('sha256', $pass);
    }
    if (empty($missingdata))
    {
        $checksql = "SELECT email,password FROM users WHERE email='$email' AND password='$passhash'";
        $response = @mysqli_query($dbc, $checksql);
        if (mysqli_num_rows($response) == 0) {
            $emailexists = 0;
        } else
        {
            $_SESSION['user_email'] = $email;
            $query= "SELECT store_name,store_type,s_id FROM users WHERE email='$email'";
            $response= @mysqli_query($dbc, $query);
            $row= mysqli_fetch_array($response);
            $_SESSION['stype']=$row['store_type'];
            $_SESSION['s_id']=$row['s_id'];
            $_SESSION['store_name']=$row['store_name'];
            if (strcmp($row['store_type'], 'buyer')==0) {
                header("Location:".get_base_url()."welcomeb.php");
                die();
            } else {
                header("Location:".get_base_url()."welcomes.php");
                die();
            }
        }
    }
    else
    {
        $needforecho = 1;
    }
}
?>
<html>
   <head>
      <title>Login</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>
   <body>
      <h1 class="text-center">Login</h1>
      <div class="centerFlex">
         <form class="form-horizontal frm" method="post" action="login.php" autocomplete="on">
            <div>
               <?php
                  if ($needforecho == 1)
                      echo '<strong>All Fields are Mandatory!</strong>';
                  else if($needforlogin==1)
                      echo '<strong class="text-danger">Login First</strong>';
               ?>
            </div>
            <div class="form-group">
               <label class="control-label">Email-Id:</label>
               <span class="input-group">
               <span class="input-group-addon">@</span>
               <input type="email" class="form-control" name="email" placeholder="Enter Email-Id">
               </span>
            </div>
            <div class="form-group">
               <label class="control-label">Password:</label>
               <input type="password"  class="form-control" name="pass" placeholder="Enter Password">
            </div>
            <?php
               if ($emailexists == 0)
                  echo '<div><strong><em>Invalid Email Id or Password!</em></strong></div>';
            ?>
            <div><a href="register.php">Or Register Now!</a></div>
            <div><a href="forgotpass.php">Forgot Password?</a></div>

            <div ><input class="btn btn-primary" type="submit" value="Login" name="login"></div>
         </form>
      </div>
   </body>
</html>

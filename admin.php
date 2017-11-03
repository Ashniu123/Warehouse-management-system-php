<?php
require_once('connect.php');
$unauth=0;
$invalidlogin=0;
$needforlogin=0;
session_start();
if(isset($_SESSION['user_email'])){
   $stype=$_SESSION['stype'];
   if(strcmp($stype,'buyer')==0||strcmp($stype,'seller')==0)
   {
      $unauth=1;
   }
}
$needforecho=0;
if(isset($_SESSION['unlog']))
{
   if($_SESSION['unlog']==1)
   {
      $_SESSION['unlog']=0;
      $needforlogin=1;
   }
}
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
if (isset($_POST['login']))
{
    $missingdata = array();
    if (empty($_POST['emp_id'])) {
        $missingdata[] = 'emp_id';
    } else {
        $emp_id = mysqli_real_escape_string($dbc, $_POST['emp_id']);
    }
    if (empty($_POST['pass'])) {
        $missingdata[] = 'pass';
    } else {
        $pass     = mysqli_real_escape_string($dbc, $_POST['pass']);
        $passhash = hash('sha256', $pass);
    }
    if(!empty($missingdata))
    {
       $needforecho=1;
    }
    else
    {
       $query = "SELECT emp_id,password,department FROM employee WHERE emp_id='$emp_id' AND password='$passhash'";
       $response = @mysqli_query($dbc,$query);
       if (mysqli_num_rows($response) == 0)
       {
          $invalidlogin=1;
       }
       else
       {
          $row=mysqli_fetch_array($response);
          $_SESSION['emp_id']=$emp_id;
          $_SESSION['department']=$row['department'];
          if(strcmp($row['department'],'Managing')==0)
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
    }

}

?>
<html>
   <head>
      <title>Employee Login</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css"/>
   </head>
   <body>
   <h1 class="text-center">Admin Login</h1>
   <div class="centerFlex">
      <form class="form-horizontal frm" method="post" action="admin.php">
         <div>
            <?php
               if ($needforecho == 1)
                   echo '<strong>All Fields are Mandatory!</strong>';
               if($invalidlogin==1)
                  echo '<strong>Invalid Id or Password. Please try again!</strong>';
               if($needforlogin==1)
                  echo '<strong>Login First</strong>';
            ?>
         </div>
         <div class="form-group">
            <label class="control-label">Employee-Id:</label>
            <input type="text" autocomplete="off" class="form-control" name="emp_id" placeholder="Enter Employee-Id">
         </div>
         <div class="form-group">
            <label class="control-label">Password:</label>
            <input type="password"  class="form-control" name="pass" placeholder="Enter Password">
         </div>
         <div><a href="forgotpass.php">Forgot Password?</a></div>
         <div ><input class="btn btn-primary" type="submit" value="Login" name="login"></div>
      </form>
   </div>
   </body>
</html>

<?php
   session_start();
   if(isset($_SESSION['user_email']))
   {
       $stype=$_SESSION['stype'];
       if(strcmp($stype,'buyer')==0)
       {
           header("Location:".get_base_url()."welcomeb.php");
           die();
       } else {
           header("Location:".get_base_url()."welcomes.php");
           die();
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

?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome to Fresh Food Warehouse</title>
    <!-- Bootstrap -->
    <link href="bootstrap.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet" type="text/css">
</head>

<body>
    <h1 class="text-center">Welcome to Fresh Food Warehouse</h1>
    <address class="text-center">
      <strong>Fresh Food Warehouse</strong>
      <br>Ispat Bhavan, Lodhi Road
      <br>New Delhi - 110003<br>
      <abbr title="Phone">P:</abbr> 987798777
    </address>
    <div class="centerFlex text-center">
        <form class="frm">
            <div>
                <button class="btn btn-lg btn-primary" type="submit" formaction="login.php" value="login">Login</button>
            </div>
            <div>
                <button class="btn btn-lg btn-primary" type="submit" formaction="register.php" value="register">Register</button>
            </div>
        </form>
    </div>
</body>

</html>

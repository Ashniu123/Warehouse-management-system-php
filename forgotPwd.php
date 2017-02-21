<?php require_once('connect.php');
   session_start();
   if(isset($_SESSION['user_email']))
   {
      $stype=$_SESSION['stype'];
      if (strcmp($stype,'buyer')==0) {
         header("Location: http://localhost/Warehouse/welcomeb.php");
         die();
      } else {
         header("Location: http://localhost/Warehouse/welcomes.php");
         die();
      }
   }
   if(isset($_POST['submit']))
   {
      if(empty($_POST['email']))
      {
         $missingdata[]='email';
      }
      else {
         $email=mysqli_real_escape_string($dbc,$_POST['email']);
      }
      if(empty($missingdata))
      {
         $checksql="SELECT email FROM users WHERE email='$email'";
         $response=@mysqli_query($dbc,$checksql);
         if(mysqli_num_rows($response)==0)
         {
            echo '<strong>Email does not Exist!</strong>';
         }
         else {
            // require('reset-mail.php');
         }
      }
      else {
            echo '<strong>All fields are compulsory!</strong>';
      }
   }
?>
<html>
   <head>
      <title>Forgot Password!</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <script src='https://www.google.com/recaptcha/api.js'></script>
   </head>
   <body>
      <div class="centerFlex">
      <form class="frm" method="post" formaction="forgotPwd.php">
         <div>
            <label class="form-group" class="control-label">Email-Id:</label>
            <span class="input-group">
               <span class="input-group-addon">@</span>
               <input type="email" class="form-control" name="email">
            </span>
         </div>
         <div class="center-block">
            <!-- TODO:Make it work -->
         <div class="g-recaptcha" data-sitekey="6LdvYAgUAAAAAO0_5dIsxwAAp_FhYAk_ckv4LlLm"></div>
         </div>
         <div>
            <br><input class="btn btn-primary" type="submit" value="Submit" name="submit">
         </div>

      </form>
   </div>
   </body>
</html>

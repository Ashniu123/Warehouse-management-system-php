<?php 
   require_once('connect.php');
   $mailSent=false;
   session_start();
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
   if(isset($_POST['submit']))
   {
      if(empty($_POST['email']))
      {
         $missingdata[]='email';
      }
      else {
         $email=mysqli_real_escape_string($dbc,$_POST['email']);
      }
      if(empty($_POST['g-recaptcha-response'])){
            $missingdata[]='g-captcha';
      }
      else {
            $secret_key = "6LdvYAgUAAAAACqH4buVCsWHZQibIz95brbjEmQm";
            $url="http://www.google.com/recaptcha/api/siteverify";
            $payload = array('secret'=>$secret_key,'response' => $_POST['g-recaptcha-response']);
            $result=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=".$payload['secret']."&response=".$payload['response']."&remoteip=".$_SERVER['REMOTE_ADDR']);
            $result = json_decode($result);
            if ($result->success == false) { 
                  echo "<strong>reCaptcha Verification failed!</strong>"; 
            } 
            else {
                  echo "<strong>reCaptcha Verification successful!</strong>"; 
                  if(empty($missingdata))
                  {
                     $checksql="SELECT email,store_name FROM users WHERE email='$email'";
                     $response=@mysqli_query($dbc,$checksql);
                     if(mysqli_num_rows($response)==0)
                     {
                        echo '<strong>Email does not Exist!</strong>';
                     }
                     else {
                        include 'reset-mail.php';
                        $row = mysqli_fetch_array($response);
                        $userMail = $row['email'];
                        $userName = $row['store_name'];
                        $mailSent = sendMailToUser($userMail,$userName);
                     }
                  }
                  else {
                     echo '<strong>All fields are compulsory!</strong>';
                  }
            }
            
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
      <?php
      if($mailSent==true)
      {     
            echo "<h4 class='text-success'>Email Successfully Sent!</h4>";
            die();
      }
      ?>
      <div class="centerFlex">
      <form class="frm" method="post" formaction="verify.php">
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

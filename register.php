<?php
   require_once('connect.php');
   $userexists=0;
   $needforecho=0;
   $passunmatch=0;
   $success=0;
   if(isset($_POST['submit']))
   {
      $missingdata=array();
      if(empty($_POST['email']))
      {
         $missingdata[]='email';
      }
      else {
         $email=mysqli_real_escape_string($dbc,$_POST['email']);
      }
      if(empty($_POST['store_name']))
      {
         $missingdata[]='store_name';
      }
      else {
         $store_name=mysqli_real_escape_string($dbc,$_POST['store_name']);
      }
      if(empty($_POST['stype']))
      {
         $missingdata[]='stype';
      }
      else {
         $stype=mysqli_real_escape_string($dbc,$_POST['stype']);
      }
      if(empty($_POST['store_address']))
      {
         $missingdata[]='store_address';
      }
      else {
         $store_address=mysqli_real_escape_string($dbc,$_POST['store_address']);
      }
      if(empty($_POST['pass']))
      {
         $missingdata[]='pass';
      }
      else {
         $pass=mysqli_real_escape_string($dbc,$_POST['pass']);
         $passhash=hash('sha256',$pass);
      }
      if(empty($_POST['cpass']))
      {
         $missingdata[]='cpass';
      }
      else {
         $cpass=mysqli_real_escape_string($dbc,$_POST['cpass']);
      }

      if(empty($missingdata))
      {
         if(strcmp($pass,$cpass)==0)
         {
            $checksql="SELECT email FROM users WHERE email='$email'";
            $response=@mysqli_query($dbc,$checksql);
            if(mysqli_num_rows($response)==0)
            {
               $query="INSERT INTO users(email,store_name,store_type,address,password) VALUES('$email','$store_name','$stype','$store_address','$passhash')";
               mysqli_query($dbc,$query);
               if(mysqli_affected_rows($dbc))
               {
                  $success=1;
                  include 'send-email.php';
                  sendMailToUser($email,$store_name);
               }
               else
               {
                  echo mysqli_error($dbc);
               }
            }
            else
            {
               $userexists=1;
            }
         }
         else
         {
            $passunmatch=1;
         }
      }
      else {
         $needforecho=1;
      }
   }
?>
<html>
   <head>
      <title>Registration</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>
   <body>
      <h1 class="text-center">Registration</h1>
      <div class="centerFlex">
         <form class="form-horizontal frm" method="post" formaction="register.php">
            <div>
               <?php
               if($needforecho==1)
               {
                  echo "<strong>All Fields are Mandatory!</strong>";
               }
               else if($passunmatch==1)
               {
                  echo '<strong>Passwords Do not Match!</strong>';
               }
                ?>
            </div>
            <div class="form-group">
               <label class="control-label">Email-Id<span class="imp">*</span>:</label>
               <span class="input-group">
               <span class="input-group-addon">@</span>
               <input type="email" class="form-control" name="email" placeholder="Enter Email-Id">
            </span>
               <?php
                  if($userexists==1)
                     echo '<strong><em>*User Already Exists!*</em></strong>';
               ?>
            </div>
            <div class="form-group">
               <label class="control-label">Store Name<span class="imp">*</span>:</label>
               <input type="text" name="store_name" class="form-control" placeholder="Enter Store Name">
            </div>
            <div class="form-group">
               <label class="control-label">Store Address<span class="imp">*</span>:</label>
               <textarea name="store_address" class="form-control" rows="2" placeholder="Store Address"></textarea>
            </div>
            <div class="form-group ">
               <label class="checkbox-inline"><strong>You Are a<span class="imp">*</span></strong>:</label>
               <label class="checkbox-inline">
               <input type="checkbox" name="stype" value="seller">Seller
            </label>
            <label class="checkbox-inline">
            <input type="checkbox" name="stype" value="buyer">Buyer
         </label>
            </div>
            <div class="form-group">
               <label class="control-label">Password<span class="imp">*</span>:</label>
               <input type="password" class="form-control" name="pass" placeholder="Enter Password">
            </div>
            <div  class="form-group">
               <label class="control-label">Confirm Password<span class="imp">*</span>:</label>
               <input type="password" name="cpass" class="form-control" placeholder="Confirm Password">
            </div>
            <div>All Fields marked with <span class="imp">*</span> are Mandatory!</div>
               <div>
                  <?php
                     if($success==1)
                     {
                        echo '<strong>User Successfully Created!</strong><br><a class="btn btn-success active" href="login.php" role="button">Login Now!</a>';
                     }
                     else {
                        echo '<input class="btn btn-primary" type="submit" value="Submit" name="submit">';
                     }
                   ?>
               </div>
               <div><a href="login.php">Already Registered? Login Now!</a></div>
         </form>
      </div>
   </body>
</html>

<?php
   require_once('connect.php');
   $needforecho=0;
   $passunmatch=0;
   $unauth=0;
   $success=0;
   if(isset($_SESSION['user_email'])){
      $stype=$_SESSION['stype'];
      if(strcmp($stype,'buyer')==0||strcmp($stype,'seller')==0)
      {
         $unauth=1;
      }
   }
   if(isset($_SESSION['emp_id']))
   {
      $department=$_SESSION['department'];
      if(strcmp("Managing",$department)!=0)
      {
         $unauth=1;
      }
   }
   function random_str($length)
   {
      $keys = array_merge(range(0,9), range('A', 'Z'));
      $key = "";
      for($i=0; $i < $length; $i++) {
           $key .= $keys[mt_rand(0, count($keys) - 1)];
      }
      return $key;
   }//to generate a random string to put into order_id column
   if(isset($_POST['submit']))
   {
      $missingdata=array();
      if(empty($_POST['emp_name']))
      {
         $missingdata[]='emp_name';
      }
      else {
         $emp_name=mysqli_real_escape_string($dbc,$_POST['emp_name']);
      }
      $missingdata=array();
      if(empty($_POST['email']))
      {
         $missingdata[]='email';
      }
      else {
         $email=mysqli_real_escape_string($dbc,$_POST['email']);
      }
      if(empty($_POST['contact']))
      {
         $missingdata[]='contact';
      }
      else {
         $contact=$_POST['contact'];
      }
      if(empty($_POST['address']))
      {
         $missingdata[]='address';
      }
      else {
         $address=mysqli_real_escape_string($dbc,$_POST['address']);
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
            $emp_id=random_str(10);
            $checksql="SELECT emp_id FROM employee WHERE emp_id='$emp_id'";
            $response=@mysqli_query($dbc,$checksql);
            if(mysqli_num_rows($response)>0)
            {
               while($row=mysqli_fetch_array($response))
               {
                  if(strcmp($emp_id,$row['emp_id'])==0)
                     $emp_id=random_str(10);
               }
            }
               $query="INSERT INTO employee(emp_id,emp_name,email,contact,address,password) VALUES('$emp_id','$emp_name','$email',$contact,'$address','$passhash')";
               mysqli_query($dbc,$query);
               if(mysqli_affected_rows($dbc))
               {
                  $success=1;
               }
               else
               {
                  echo mysqli_error($dbc);
               }
            }
         else
         {
            $passunmatch=1;
         }
      }
      else
      {
         $needforecho=1;
      }
   }
?>
<html>
   <head>
      <title>Employee Registration</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>
   <body>
      <span class="rightFlex" id="log-out">
         <a href="logout.php" role="button" class="btn btn-warning">Logout</a>
         <span>
            <?php
               if($unauth==1)
               {
                  echo '<h4><strong class="text-danger">Unauthorized Access</strong></h4>';
                  // TODO:Allow access from index page or to the correct page
                  $unauth=0;
                  die();
               }
            ?>
         </span>
      </span>
      <span class="leftFlex" id="go-back">
      <a href="manager.php" role="button" class="btn btn-success">Back</a>
      </span>
      <div class="centerFlex">
         <form class="form-horizontal frm" autocomplete="off" method="post" formaction="empregister.php">
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
               <label class="control-label">Employee Name<span class="imp">*</span>:</label>
               <input type="text" class="form-control" name="emp_name" placeholder="Enter Name">
            </div>
            <div class="form-group">
               <label class="control-label">Email-Id<span class="imp">*</span>:</label>
               <span class="input-group">
               <span class="input-group-addon">@</span>
               <input type="email" class="form-control" name="email" placeholder="Enter Email-Id">
            </span>
            </div>
            <div class="form-group">
               <label class="control-label">Contact No:<span class="imp">*</span>:</label>
               <input type="tel" minlength=10 maxlength=10 name="contact" pattern="^[0-9]*$" class="form-control" placeholder="Enter Contact Number">
            </div>
            <div class="form-group">
               <label class="control-label">Address<span class="imp">*</span>:</label>
               <textarea name="address" class="form-control" rows="2" placeholder="Enter Address"></textarea>
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
               <input class="btn btn-primary" type="submit" name="submit">
               <div>
                  <?php
                     if($success==1)
                     {
                        echo '<strong>Success!</strong><br><span>Employee Id:'.$emp_id.'</span>';
                     }
                   ?>
               </div>
         </form>
      </div>
   </body>
</html>

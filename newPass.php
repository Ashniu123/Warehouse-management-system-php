<?php
$needforecho=0;
$fakemail=0;
$unauth=0;
$passunmatch=0;
$samepassword=0;
require_once('connect.php');
if (isset($_GET['email'])) {
    $email=$_GET['email'];
} else {
    $unauth=1;
}
if (isset($_POST['proceed'])) {
    $missingdata = array();
    if (empty($_POST['email'])) {
        $missingdata[] = 'email';
    } else {
        $email = $_POST['email'];
        $unauth=0;
    }
    if (empty($_POST['pass'])) {
        $missingdata[] = 'pass';
    } else {
        $pass     = mysqli_real_escape_string($dbc, $_POST['pass']);
        $passhash = hash('sha256', $pass);
    }
    if (empty($_POST['cpass'])) {
        $missingdata[] = 'cpass';
    } else {
        $cpass     = mysqli_real_escape_string($dbc, $_POST['cpass']);
    }
    if (empty($missingdata)) {
        if (strcmp($pass, $cpass)==0) {
             $checksql="SELECT password FROM users WHERE email='$email'";
             $response=@mysqli_query($dbc, $checksql);
            if (mysqli_num_rows($response)>0) {
                $row=mysqli_fetch_array($response);
                if (strcmp($row['password'], $passhash)==0) {
                    $samepassword=1;
                } else {
                    $query="INSERT INTO users(password) VALUES('$passhash') WHERE email=$email";
                    mysqli_query($dbc, $query);
                    if (mysqli_affected_rows($dbc)) {
                        $success=1;
                    } else {
                        echo mysqli_error($dbc);
                    }
                }
            } else {
                $fakemail=1;
            }
        } else {
             $passunmatch=1;
        }
    } else {
        $needforecho=1;
    }
}

?>
 <html>
   <head>
      <title>Set New Password</title>
      <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
      <link rel="stylesheet" type="text/css" href="styles.css">
      <meta name="viewport" content="width=device-width, initial-scale=1">
   </head>
   <body>
      <div>
            <?php
            if ($unauth==1) {
                echo '<h4><strong class="text-danger">Unauthorized Access</strong></h4>';
               // TODO:Allow access from index page or to the correct page
                $unauth=0;
                die();
            }
            else if($success==1){
                echo "<h4 class='text-success'><strong>Password Successfully Reset</strong></h4>";
                echo "<a class='btn btn-primary' href='login.php'>Login Now!</a>";
                die();
            }
            ?>
      </div>
      <div class="centerFlex">
         <form class="form-horizontal frm" method="post" action="newPass.php">
            <div>
                <?php
                if ($needforecho==1) {
                    echo '<strong>All Fields are Mandatory</strong>';
                } elseif ($passunmatch==1) {
                    echo '<strong>Passwords Do not Match!</strong>';
                }
                if ($fakemail==1) {
                    echo '<strong>Email-Id does not exist!</strong>';
                }
                ?>
            </div>
            <div class="form-group">
               <label class="control-label">Email-Id:</label>
                <?php echo '<input class="form-control" type="text" name="email" value="'.$email.'" readonly="readonly"/>'?>
               </span>
            </div>
            <div class="form-group">
               <label class="control-label">New Password:</label>
               <input type="password"  class="form-control" name="pass" placeholder="Enter New Password">
            </div>
            <div class="form-group">
               <label class="control-label">Confirm New Password:</label>
               <input type="password"  class="form-control" name="cpass" placeholder="Confirm New Password">
            </div>
            <div>
               <input class="btn btn-default" type="submit" value="Proceed" name="proceed">
            </div>
         </form>
      </div>
   </body>
 </html>

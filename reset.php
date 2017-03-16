<?php
	include 'connect.php';

	if(isset($_GET['email']))
	{
		$email = $_GET['email'];
	}
	if(isset($_POST['password']) && isset($_POST['repassword']))
	{
		$password = $_POST['password'];
		$repassword = $_POST['repassword'];

		if($password=="" || $repassword=="")
		{
			echo "<script>alert(\"Please Enter Both The Fields\");</script>";
		}
		elseif ($password!=$repassword)
		{
			echo "<script>alert(\"Passwords do not match!\");</script>";
		}
		else
		{

			$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
			$salt = base64_encode($salt);
			$salt = str_replace('+', '.', $salt);
			$flag = 0;
			$hash = crypt($password, '$2y$10$'.$salt.'$');
			$query = "UPDATE login SET \"Password\"='$hash' where \"Email\"='$email'";
			$success=pg_query($db,$query);
			if ($success)
			{
				echo "<script>alert(\"Password Changed Successfully\");</script>";
				echo "<script>window.close();</script>";
			}
			else
			{
				echo "<script>alert(\"Error Changing Password\");</script>";
				echo "<script>window.close();</script>";
			}


		}
	}

?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
	 <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

      <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
<link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">

</head>
<body>
<br>
<br>
<div class="container">
  <a href="#" data-target="#pwdModal" data-toggle="modal">Click here to reset password</a>
</div>

<!--modal-->
<div id="pwdModal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
  <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
          <h1 class="text-center">Reset Password</h1>
      </div>
      <div class="modal-body">
          <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">

                          <p>No worries we will help you!</p>
                            <div class="panel-body">
                                <fieldset>
                                <form action="" method="post">
                                    <div class="form-group">
                                        <input class="form-control input-lg" placeholder="New Password" name="password" type="password">
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control input-lg" placeholder="Re-enter Password" name="repassword" type="password">
                                    </div>
                                    <input class="btn btn-lg btn-primary btn-block" value="Submit" type="submit">
                                    </form>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
      <div class="modal-footer">
          <div class="col-md-12">
          <button class="btn" data-dismiss="modal" aria-hidden="true">Cancel</button>
		  </div>
      </div>
  </div>
  </div>
</div>

</body>
</html>

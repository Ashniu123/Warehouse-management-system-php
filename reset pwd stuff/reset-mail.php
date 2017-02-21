<?php

require 'PHPMailer-master/class.phpmailer.php';
require 'PHPMailer-master/class.smtp.php';
//include 'PHPMailer-master/PHPMailerAutoload.php';

$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = 'smtp';
$mail->Host = 'smtp.gmail.com'; // "ssl://smtp.gmail.com" didn't worked
//$mail->Port = 465;
//$mail->SMTPSecure = 'ssl';
//echo !extension_loaded('openssl')?"Not Available":"Available";
// or try these settings (worked on XAMPP and WAMP):
$mail->Port = 587;
$mail->SMTPSecure = 'tls';
$mail->SMTPDebug=3;
$mail->SMTPAuth=true;
$mail->Username = "atharvapatil1996@gmail.com";
$mail->Password = "Atharva123!";

$mail->IsHTML(true); // if you are going to send HTML formatted emails
$mail->SingleTo = true; // if you want to send a same email to multiple users. multiple emails will be sent one-by-one.

$mail->From = "atharvapatil1996@gmail.com";
$mail->FromName = "SquareBook";

$mail->addAddress("atharvapatil1996@gmail.com","Atharva");

$mail->Subject = "Password-Reset";
$mail->Body='Click on the below link to reset password<br><a href="localhost/Warehouse/reset.php?email='.$reset.'">reset.php?email='.$reset.'</a>';

if(!$mail->Send())
	echo "Email was not sent <br />PHPMailer Error: " . $mail->ErrorInfo;
else
	echo "<script>alert(\"Email has been sent\"); </script>";
?>

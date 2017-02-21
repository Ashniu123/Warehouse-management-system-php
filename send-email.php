<?php
require 'PHPMailer-master/class.phpmailer.php';
$mail = new PHPMailer();
$mail->IsSMTP();
$mail->Mailer = 'smtp';
$mail->SMTPAuth = true;
$mail->Host = 'smtp.gmail.com'; // "ssl://smtp.gmail.com" didn't worked
$mail->Port = 465;
$mail->SMTPSecure = 'ssl';
// or try these settings (worked on XAMPP and WAMP):
// $mail->Port = 587;
// $mail->SMTPSecure = 'tls';


$mail->Username = "atharvapatil1996@gmail.com";
$mail->Password = "Atharva123!";

$mail->IsHTML(true); // if you are going to send HTML formatted emails
$mail->SingleTo = true; // if you want to send a same email to multiple users. multiple emails will be sent one-by-one.

$mail->From = "justashniu@gmail.com";
$mail->FromName = "Nisheet";

$mail->addAddress("atharvapatil1996@gmail.com","User 1");
$mail->addAddress("nisheet1.sinvhal@gmail.com","User 2");

$mail->Subject = "New Registration";
$mail->Body='Greetings '.$store_name.'!<br>Welcome to Fresh Food Warehouse<br>
<a href="localhost/Warehouse/login.php>Login now!</a>';

if(!$mail->Send())
	echo "Email was not sent <br />PHPMailer Error: " . $mail->ErrorInfo;
else
	echo "Email has been sent";
?>

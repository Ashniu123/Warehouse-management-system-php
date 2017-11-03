<?php

function sendMailToUser($userMail,$userName){
	include("PHPMailer-master/class.phpmailer.php");
	
	$account="blahblah@hotmail.com"; //your email (currently configured for hotmail)
	$password="Password"; // Your password

	$mail = new PHPMailer();
	$mail->IsSMTP();
	$mail->CharSet = 'UTF-8';
	$mail->Host = "smtp-mail.outlook.com";
	$mail->SMTPAuth= true;
	$mail->Port = 587;
	$mail->Username= $account;
	$mail->Password= $password;
	$mail->SMTPSecure = 'tls';
	$mail->From = $account;
	$mail->FromName= "Warehouse Staff";
	$mail->isHTML(true);
    $mail->addAddress($userMail);//Recipient

    $mail->Subject = "Password-Reset for Warehouse";
    $mail->Body='<br/>Greetings '.$userName.'!<br/>Welcome to Fresh Food Warehouse.<br/> <a href="'.get_base_url().'login.php">Login Now!</a><br/>';

    if(!$mail->send()){
		echo "Mailer Error:\n" . $mail->ErrorInfo;
		return false;
	}else{
		return true;
	}
}
?>

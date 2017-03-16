<?php
include('connect.php');
if(isset($_GET['email']) && isset($_GET['code']))
{
	$email=$_GET['email'];
	$code=$_GET['code'];
	
$select=pg_query("select * from login where \"Email\"='$email' and \"Code\"='$code'");

if(pg_num_rows($select)==1)
	{

		$insert_user=pg_query($db,"update login set \"Flag\" = 1 where \"Email\" = '$email' AND \"Code\" = '$code'");
		if($insert_user)
		{
			echo "<script>alert(\"User Verified Successfully\");</script>";
		}
		else
		{
			echo "<script>alert(\"Error verifying user\");</script>";
		}
	}
}

?>

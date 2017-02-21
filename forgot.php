<html>
<head>
<title>Forgot Password</title>
</head>
<body>
<?php
include('connect.php');
 error_reporting('E_ALL ^ E_NOTICE');
 if(isset($_POST['submit']))
 {
  $name=$_POST['email'];
  
  $q=pg_query("select * from login where \"Email\" ='".$name."'") or die(pg_last_error());
  $n=pg_num_rows($q);
  if($n>0)
  {
      require 'reset-mail.php';  
      echo $n;
    
  }
  else
  {
   
    $er='User not registered';
   
  }
 }
?>

<h1 align="center">Forgot Password?</h1>
<div class="contact">


     <div id="er"><?php echo $er?></div>
     
     <div class="container">
            <form method="POST" action="#" role="form" class="fform" >
           
            <br>
            
		        <div class="form-group">
		            <label for="email"> <font color="red">*</font>UserName</label><br>
		            <input type="text" class="form-group" placeholder="Enter Your UserName" name="email" id="email">
		        </div>
		       
		       <div class="form-group">
		         <input type="submit" name="submit" id="submit" value="Submit">
		            </div>
		      
        </form> 
</div>


<script type="text/javascript">
$(document).ready(function() {
$('#submit').click(function() {
var name=document.getElementById('email').value;

if(name=='')
{
 $('#er').html('Enter your Email-ID');
 return false;
}

});
});
</script>
</body>

</html>
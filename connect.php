<?php
$server="localhost";
$username="root";
$password="";
$db="warehouse";

$dbc=new mysqli($server,$username,$password,$db);

if($dbc){
   echo "connection established<br>";
}
else {
   die("<br>connection failed: ".$dbc->connect_error.'<br>');
}
?>

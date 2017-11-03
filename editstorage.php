<?php
require_once('connect.php');
$success=0;
$unauth=0;
$needforlogin=0;
session_start();
if(isset($_SESSION['user_email'])){
   $stype=$_SESSION['stype'];
   if(strcmp($stype,'buyer')==0||strcmp($stype,'seller')==0)
   {
      $unauth=1;
   }
}
$needforecho=0;
$department="";
if(isset($_SESSION['emp_id']))
{
   $emp_id=$_SESSION['emp_id'];
   $department=$_SESSION['department'];
}
else
{
   $_SESSION['unlog']=1;
   header("Location:".get_base_url()."admin.php");
   die();
}
if(isset($_POST['update']))
{
   $missinglist=array();
   if (empty($_POST['section'])) {
      $missingdata[] = 'section';
   } else {
      $section = $_POST['section'];
   }
   if(empty($_POST['check_list']))
   {
      $missingdata[]='check_list';
   }
   else {
      $check_list=$_POST['check_list'];
   }
   if(empty($missingdata))
   {
      foreach ($check_list as $id)
      {
         foreach($section[$id] as $sect)
         {
            $query="UPDATE food SET section='$sect' WHERE foodId='$id'";
            $response=@mysqli_query($dbc,$query);
            if(mysqli_affected_rows($dbc))
            {
               $success=1;
            }
            else {
               $success=-1;
            }
         }
      }//end of outer foreach
   }
   else {
      $needforecho=1;
   }
}
?>
<html>
<head>
   <title>Welcome Manager!</title>
   <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" href="styles.css"/>
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
               $unauth=0;
               die();
            }
         ?>
      </span>
   </span>
   <span class="leftFlex" id="go-back">
      <?php
      if(strcmp($department,"Managing")==0)
         echo '<a href="manager.php" role="button" class="btn btn-success">Back</a>';
      else {
         echo '<a href="employee.php" role="button" class="btn btn-success">Back</a>';
      }
      ?>
   </span>
   <h1 class="centerFlex">Storage:</h1><hr>
      <div class="centerFlex ">
         <form class="frm-wel" method="post">
            <div>
               <?php
               if($success==1)
               {
                  echo '<strong>Updated Successfully!</strong>';
               }
               if($needforecho==1)
               {
                  echo '<strong>Please check some box before Proceeding!</strong>';
               }
               $query="SELECT foodId,food_name,section,store_name,addedon,expiry_date,qty,price,sold FROM food,users WHERE users.s_id=food.s_id";
               $response=@mysqli_query($dbc,$query);
               if(mysqli_num_rows($response)==0)
               {
                  echo '<span class="text-center">Storage is Empty!</span>';
               }
               else
               {
                  echo '<table cellspacing="5" class="table table-striped tble" cellpadding="10">
                  <tr>
                  <th class="text-center">Food Id</th>
                  <th class="text-center">Food Name</th>
                  <th class="text-center">Section</th>
                  <th class="text-center">Seller</th>
                  <th class="text-center">Added On</th>
                  <th class="text-center">Expiry Date</th>
                  <th class="text-center">Price/Item</th>
                  <th class="text-center">Quantity Available</th>
                  <th class="text-center">Quantity Sold</th>
                  <th class="text-center">Select</th>
                  </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['foodId'].'</td>
                     <td class="text-center">'.$row['food_name'].'</td><td>
                     <select name="section['.$row['foodId'].'][]" class="form-control"><option>'.$row['section'].'
                     </option><option>Dairy</option><option>Frozen</option><option>Meat</option></select>
                     </td><td class="text-center">'.$row['store_name'].'</td>
                     <td class="text-center">'.$row['addedon'].'</td>
                     <td class="text-center">'.$row['expiry_date'].'</td>
                     <td class="text-center">'.$row['price'].'</td>
                     <td class="text-center">'.$row['qty'].'</td>
                     <td class="text-center">'.$row['sold'].'</td>
                     <td class="text-center">
                     <label class="checkbox-inline"><input type="checkbox" name="check_list['.$row['foodId'].']" value="'.$row['foodId'].'"></label>
                     </td>
                     </tr>';
                  }//end of while
                  echo '</table>';
               }
               ?>
            </div>
         <div class="centerFlex"><input class="btn btn-danger" formaction="editstorage.php" type="submit" value="Update" name="update"></div>
      </form>
   </div>
</body>
</html>

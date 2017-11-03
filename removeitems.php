<?php
require_once('connect.php');
session_start();
if(isset($_SESSION['user_email']) && isset($_SESSION['s_id']))
{
   $email=$_SESSION['user_email'];
   $stype=$_SESSION['stype'];
   $s_id=$_SESSION['s_id'];
   $store_name=$_SESSION['store_name'];
}
else
{
   $_SESSION['unlog']=1;
   header("Location:".get_base_url()."login.php");
   die();
}
$unauth=0;
$success=0;
$needforecho=0;
if(strcmp($stype,'buyer')==0)
{
   $unauth=1;
}
if(isset($_POST['submit']))
{
   if(empty($_POST['check_list']))
   {
      $needforecho=1;
   }
   else
   {
      foreach ($_POST['check_list'] as $item)
      {
         $query="DELETE FROM food WHERE foodId='$item'";
         $response=@mysqli_query($dbc,$query);
         if(mysqli_affected_rows($dbc))
         {
            $success=1;
         }
         else
         {
            $success=-1;
            echo mysqli_error($dbc);
         }
      }//end of for
   }
}
?>

<html>
   <head>
      <title>Remove Items</title>
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
                  // TODO:Allow access from index page or to the correct page
                  $unauth=0;
                  die();
               }
            ?>
         </span>
      </span>
      <span class="leftFlex" id="go-back">
      <a href="welcomes.php" role="button" class="btn btn-success">Back</a>
      </span>
      <h1 class="text-center">Fresh Food Warehouse</h1>
      <h2 class="text-center"><?php echo $store_name;?></h2>
      <h4 class="text-center">Your Items:</h4>
      <div class="centerFlex">
            <div class="container centerFlex">
               <form method="post" class="frm-wel" action="removeitems.php">
                  <div>
                     <?php
                        if($success==1)
                        {
                           echo '<strong>Item(s) Successfully Deleted</strong>';
                        }
                        else if($success==-1)
                        {
                           echo '<strong class="text-danger">Item(s) was not Deleted. Please try again!</strong>';
                        }
                        if($needforecho==1)
                        {
                           echo '<strong>Please check some box before proceeding!</strong>';
                        }
                     ?>
                  </div>
               <div>
                  <?php
               $query="SELECT foodId,food_name,addedon,qty,price,sold,expiry_date FROM food WHERE s_id=$s_id";
               $response=@mysqli_query($dbc,$query);
               $num_rows=mysqli_num_rows($response);
               if($num_rows>0){
                  echo '<table cellspacing="5" class="table table-striped centerFlex tble" cellpadding="10">
                  <tr><th class="text-center">FoodId</th>
                  <th class="text-center">Food Name</th>
                  <th class="text-center">Quantity Available</th>
                  <th class="text-center">Price/Item</th>
                  <th class="text-center">Quantity Sold</th>
                  <th class="text-center">Added On</th>
                  <th class="text-center">Expiry Date</th>
                  <th class="text-center">Select</th>
                  </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['foodId'].'</td>
                     <td class="text-center">'.$row['food_name'].'</td>
                     <td class="text-center">'.$row['qty'].'</td>
                     <td class="text-center">'.$row['price'].'</td>
                     <td class="text-center">'.$row['sold'].'</td>
                     <td class="text-center">'.$row['addedon'].'</td>
                     <td class="text-center">'.$row['expiry_date'].'</td>
                     <td class="text-center">
                     <label class="checkbox-inline"><input type="checkbox" name="check_list['.$row['foodId'].']" value="'.$row['foodId'].'"></label>
                     </td>
                     </tr>';
                  }
                  echo '</table>';
               }
               else
               {
                  echo '<div class="text-center">No Items are being Sold!</div>';
               }
               ?>
            </div>
               <div class="text-center col-md-12">
                  <button type="submit" name="submit" class="btn btn-danger">Remove</button>
               </div>
            </form>
            </div>
         </form>
      </div>
   </body>
</html>

<?php
require_once('connect.php');

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
if(isset($_SESSION['emp_id']))
{
   $emp_id=$_SESSION['emp_id'];
   $department=$_SESSION['department'];
   if(strcmp("Managing",$department)==0)
   {
      header("Location: http://localhost/Warehouse-management-system-php/manager.php");
      die();
   }
}
else
{
   $_SESSION['unlog']=1;
   header("Location: http://localhost/Warehouse-management-system-php/admin.php");
   die();
}
$query="SELECT emp_name FROM employee WHERE emp_id='$emp_id'";
$response=@mysqli_query($dbc,$query);
$emp_name="";
if(mysqli_num_rows($response))
{
   $row=mysqli_fetch_array($response);
   $emp_name=$row['emp_name'];
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
   <div id="log-out">
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
   </div>
   <h1 class="centerFlex"><?php echo "Welcome $emp_name"; ?></h1><hr>
   <h4 class="centerFlex">Orders:</h4>
      <div class="centerFlex ">
         <form class="frm-wel">
            <div>
               <?php
               $query="SELECT order_id,store_name,orders.foodId,food.section,food.price,orders.qty,orders.addedon,delivery_date,status,address FROM food,users,orders WHERE users.s_id=orders.s_id AND food.foodId = orders.foodId ORDER BY delivery_date";
               $response=@mysqli_query($dbc,$query);
               if(mysqli_num_rows($response)==0)
               {
                  echo '<span class="text-center">No Orders Currently!</span>';
               }
               else
               {
                  echo '<table cellspacing="5" class="table table-striped tble" cellpadding="10">
                  <tr>
                  <th class="text-center">Order Id</th>
                  <th class="text-center">Store Name</th>
                  <th class="text-center">foodId</th>
                  <th class="text-center">Section</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Order Date</th>
                  <th class="text-center">Delivery Date</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Delivery Address</th>
                  </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['order_id'].'</td>
                     <td class="text-center">'.$row['store_name'].'</td>
                     <td class="text-center">'.$row['foodId'].'</td>
                     <td class="text-center">'.$row['section'].'</td>
                     <td class="text-center">'.$row['qty'].'*'.$row['price'].'='.$row['qty']*$row['price'].'</td>
                     <td class="text-center">'.$row['addedon'].'</td>
                     <td class="text-center">'.$row['delivery_date'].'</td>
                     <td class="text-center">'.$row['status'].'</td>
                     <td class="text-center">'.$row['address'].'</td>
                     </tr>';
                  }//end of while
                  echo '</table>';
               }
               ?>
            </div>
         </form>
   </div>
   <div class="centerFlex">
      <div>
         <form>
            <button class="btn btn-Info" type="submit" formaction="editorder.php" value="editorder">Edit Order</button>
         </form>
      </div>
   </div>
   <h4 class="centerFlex">Storage:</h4>
      <div class="centerFlex ">
         <form class="frm-wel">
            <div>
               <?php
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
                  </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['foodId'].'</td>
                     <td class="text-center">'.$row['food_name'].'</td>
                     <td class="text-center">'.$row['section'].'</td>
                     <td class="text-center">'.$row['store_name'].'</td>
                     <td class="text-center">'.$row['addedon'].'</td>
                     <td class="text-center">'.$row['expiry_date'].'</td>
                     <td class="text-center">'.$row['price'].'</td>
                     <td class="text-center">'.$row['qty'].'</td>
                     <td class="text-center">'.$row['sold'].'</td>
                     </tr>';
                  }//end of while
                  echo '</table>';
               }
               ?>
            </div>
         </form>
   </div>
   <div class="centerFlex">
      <div>
         <form>
            <button class="btn btn-Info" type="submit" formaction="editstorage.php" value="editstorage">Edit Storage</button>
         </form>
      </div>
   </div>
</body>
</html>

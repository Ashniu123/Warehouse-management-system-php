<?php
   require_once('connect.php');
   $unauth=1;
   session_start();
    if(isset($_SESSION['user_email'])){
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
   if(strcmp($stype,'buyer')==0)
   {
      $unauth=0;
   }
   $clear=0;
   if(isset($_POST['clear']))
   {
      $query="SELECT status FROM orders WHERE s_id=$s_id";
      $response=@mysqli_query($dbc,$query);
      if(mysqli_num_rows($response)>0)
      {
         while($row=mysqli_fetch_array($response))
         {
            $update="UPDATE orders SET status='deleted' WHERE status='delivered' AND s_id=$s_id";
            $update_status=@mysqli_query($dbc,$update);
            if(mysqli_affected_rows($dbc))
            {
               $clear=1;
            }
            else
            {
               $clear=-1;
            }
         }
      }
   }
?>
<!-- TODO:Recent Orders, New order->checkout.php -->
<html>
   <head>
      <title>Welcome to Fresh Food Warehouse!</title>
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
                  // TODO:Allow access from index page or to the correct page
                  $unauth=0;
                  die();
               }
            ?>
         </span>
      </div>
      <h1 class="text-center">Welcome to Fresh Food Warehouse</h1>
      <h2 class="text-center"><?php echo $store_name;?></h2>
      <h4 class="text-center">Recent Orders:</h4>
      <div class="centerFlex">
      <form class="frm-wel">
         <div>
            <?php
               if($clear==1)
               {
                  echo "<strong>Delivered Orders Cleared Successfully!</strong>";
               }
               else if($clear==-1)
               {
                  echo "<strong>No Orders to Clear!</strong>";
               }
            ?>
         </div>
         <div>
            <?php
            //Check if items are available or not
            $query="SELECT order_id,orders.foodId,orders.qty,orders.addedon,delivery_date,food_name,price,status FROM orders,food WHERE orders.s_id='$s_id' AND food.foodId=orders.foodId AND NOT status='deleted' ORDER BY delivery_date";
            $response=@mysqli_query($dbc,$query);
            if(mysqli_num_rows($response))
            {
               echo '<table cellspacing="5" class="table table-striped tble" cellpadding="10">
               <tr>
               <th class="text-center">Order Id</th>
               <th class="text-center">Food Id</th>
               <th class="text-center">Food Name</th>
               <th class="text-center">Quantity</th>
               <th class="text-center">Price/Item</th>
               <th class="text-center">Total Cost</th>
               <th class="text-center">Order Date</th>
               <th class="text-center">Expected Delivery Date</th>
               <th class="text-center">Status</th>
               </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['order_id'].'</td>
                     <td class="text-center">'.$row['foodId'].'</td>
                     <td class="text-center">'.$row['food_name'].'</td>
                     <td class="text-center">'.$row['qty'].'</td>
                     <td class="text-center">'.$row['price'].'</td>
                     <td class="text-center">'.$row['price']*$row['qty'].'</td>
                     <td class="text-center">'.$row['addedon'].'</td>
                     <td class="text-center">'.$row['delivery_date'].'</td>
                     <td class="text-center">'.$row['status'].'</td>
                     </tr>';
                  }
               echo '</table>';
            }
            else {
               echo '<div class="text-center">No Recent Orders</div>';
            }
            ?>
         </div>
      </form>
   </div>
   <div class="centerFlex">
      <form>
         <div>
         <button class="btn btn-Info" type="submit" formaction="neworder.php" value="neworder">New Order</button>
         </div>
      </form>
   </div>
   <div class="centerFlex">
      <form method="post">
         <div>
         <button class="btn btn-default" type="submit" name="clear" formaction="welcomeb.php" value="clear">Remove delivered items</button>
         </div>
      </form>
   </div>
   <div class="centerFlex">
      <form>
         <div>
         <button class="btn btn-danger" type="submit" name="remove" formaction="deleteorder.php" value="delete">Delete Order</button>
         </div>
      </form>
   </div>
   </body>
</html>

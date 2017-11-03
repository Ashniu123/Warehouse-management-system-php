<?php
   require_once('connect.php');
   $unauth=0;
   $qty_reset=0;
   $needforecho=0;
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
   if(strcmp($stype,'buyer')==0)
   {
      $unauth=1;
   }
   if(isset($_POST['reset_sold']))
   {
      $query="SELECT foodId FROM food WHERE s_id=$s_id";
      $response=mysqli_query($dbc,$query);
      if(mysqli_num_rows($response))
      {
         while($row=mysqli_fetch_array($response))
         {
            $update="UPDATE food SET sold=0 WHERE foodId='".$row['foodId']."'";
            mysqli_query($dbc,$update);
            if(mysqli_affected_rows($dbc))
            {
               $qty_reset=1;
            }
            else {
               $qty_reset=-1;
            }
         }//end of while
      }
      else {
         $needforecho=1;
      }
   }//end of isset if
?>
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
                  $unauth=0;
                  die();
               }
            ?>
         </span>
      </div>
      <h1 class="text-center">Fresh Food Warehouse</h1>
      <h2 class="text-center"><?php echo $store_name;?></h2>
      <h4 class="text-center">Your Items:</h4>
      <div class="centerFlex">
         <div class="container centerFlex">
            <form class="frm-wel">
            <div>
               <?php
               if($qty_reset==1)
               {
                  echo '<strong>Sold Items Reset to 0!</strong>';
               }
               if($needforecho==1)
               {
                  echo '<strong>Nothing to Reset!</strong>';
               }
               $query="SELECT foodId,food_name,expiry_date FROM food WHERE s_id=$s_id";
               $response=@mysqli_query($dbc,$query);
               if(mysqli_num_rows($response)>0)
               {
                  $current_date=date("Y-m-d");
                  while($row=mysqli_fetch_array($response))
                  {
                     if(strtotime($row['expiry_date'])<=strtotime($current_date))
                     {
                        $food=$row['foodId'];
                        $query="DELETE FROM food WHERE foodId='$food'";
                        $delete=@mysqli_query($dbc,$query);
                        if(mysqli_affected_rows($dbc))
                           echo '<span>The following Item(s) have been removed from the Warehouse due to their expiry:<br>'.$row['foodId'].' '.$row['food_name'].'</span><br>';
                     }
                  }
               }
            ?>
         </div>
            <?php
            $query="SELECT foodId,food_name,addedon,qty,price,sold,expiry_date FROM food WHERE s_id=$s_id";
            $response=@mysqli_query($dbc,$query);

            if(mysqli_num_rows($response)>0)
            {
               echo '<table cellspacing="5" class="table table-striped tble" cellpadding="10">
               <tr><th class="text-center">FoodId</th>
               <th class="text-center">Food Name</th>
               <th class="text-center">Quantity Available</th>
               <th class="text-center">Price/Item</th>
               <th class="text-center">Quantity Sold</th>
               <th class="text-center">Added On</th>
               <th class="text-center">Expiry Date</th>
               </tr>';
               while($row=mysqli_fetch_array($response))
               {
                  echo '<tr><td class="text-center">'.
                  $row['foodId'].'</td><td class="text-center">'.
                  $row['food_name'].'</td><td class="text-center">'.
                  $row['qty'].'</td><td class="text-center">'.
                  $row['price'].'</td><td class="text-center">'.
                  $row['sold'].'</td><td class="text-center">'.$row['addedon'].'</td><td class="text-center">'.
                  $row['expiry_date'].'</td></tr>';
               }
               echo '</table>';
            }
            else
            {
               echo '<div class="text-center">No Items are being Sold!</div>';
            }
            ?>
         </form>
         </div>
   </div>
   <div class="centerFlex">
      <form>
         <div>
            <button class="btn btn-Info" type="submit" formaction="additems.php" value="additems">Add Items</button>
         </div>
      </form>
   </div>
   <div class="centerFlex">
      <form>
         <div>
            <button class="btn btn-danger" type="submit" formaction="removeitems.php" value="removeitems">Remove Items</button>
         </div>
      </form>
   </div>
   <div class="centerFlex">
      <form method="post">
         <div>
         <button class="btn btn-default" type="submit" formaction="welcomes.php" value="reset_sold" name="reset_sold">Reset Quantity Sold</button>
         </div>
      </form>
   </div>
   </body>
</html>

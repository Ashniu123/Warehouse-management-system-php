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
function random_str($length)
{
   $keys = array_merge(range(0,9), range('A', 'Z'));
   $key = "";
   for($i=0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
   }
   return $key;
}//to generate a random string to put into order_id column
$unauth=1;
$success=0;
$itemexists=0;
$needforecho=0;
$badexpiry=0;
if(strcmp($stype,'buyer')==0)
{
   $unauth=0;
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
         $item=mysqli_real_escape_string($dbc,$item);
         foreach ($_POST['qty_list'][$item] as $qty)
         {
            $order_id=random_str(5);
            $query="SELECT order_id FROM orders";
            $response=@mysqli_query($dbc,$query);
            if(mysqli_num_rows($response)>0)
            {
               while($row=mysqli_fetch_array($response))
               {
                  if(strcmp($order_id,$row['order_id'])!=0)
                     $order_id=random_str(5);
               }
            }
            $random_number=rand(3,7);
            $current_date=date("Y-m-d");
            $delivery_date=date("Y-m-d",strtotime($current_date)+86400*$random_number);
            $query="INSERT INTO orders(order_id,s_id,foodId,qty,addedon,delivery_date) VALUES('$order_id',$s_id,'$item',$qty,CURDATE(),'$delivery_date')";
            mysqli_query($dbc,$query);
            if(mysqli_affected_rows($dbc))
            {
               $query="UPDATE food SET qty=qty-$qty,sold=sold+$qty WHERE foodId='$item'";
               mysqli_query($dbc,$query);
               if(mysqli_affected_rows($dbc))
               {
                  $success=1;
               }
               else
               {
                  $success=-1;
                  echo mysqli_error($dbc);
               }
            }
            else
            {
               $success=-1;
               echo mysqli_error($dbc);
            }

         }//inner for
      }//outer for
   }//else
}//isset if
?>
<html>
   <head>
      <title>New Order</title>
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
      <a href="welcomeb.php" role="button" class="btn btn-success">Back</a>
      </span>
      <h1 class="text-center">Fresh Food Warehouse</h1>
      <h2 class="text-center"><?php echo $store_name;?></h2>
      <h4 class="text-center">Items in Warehouse:</h4>
      <div class="centerFlex">
            <div class="container centerFlex">
               <form method="post" class="frm-wel" action="neworder.php">
                  <div>
                     <?php
                        if($success==1)
                        {
                           echo '<strong>Item(s) Successfully Added</strong>';
                        }
                        else if($success==-1)
                        {
                           echo '<strong class="text-danger">Item(s) was not Added. Please try again!</strong>';
                        }
                        if($needforecho==1)
                        {
                           echo '<strong>Please check some box before proceeding!</strong>';
                        }
                     ?>
                  </div>
               <div>
                  <?php
               $query="SELECT foodId,food_name,qty,price,expiry_date FROM food";
               $response=@mysqli_query($dbc,$query);
               $num_rows=mysqli_num_rows($response);
               if($num_rows>0){
                  echo '<table cellspacing="5" class="table table-striped centerFlex tble" cellpadding="10">
                  <tr><th class="text-center">FoodId</th>
                  <th class="text-center">Food Name</th>
                  <th class="text-center">Quantity Available</th>
                  <th class="text-center">Price/Item</th>
                  <th class="text-center">Expiry Date</th>
                  <th class="text-center">Select</th>
                  </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['foodId'].'</td>
                     <td class="text-center">'.$row['food_name'].'</td>
                     <td class="text-center">';
                     if($row['qty']>0)
                     {
                        echo '<input type="number" min="0" max="'.$row['qty'].'" name="qty_list['.$row['foodId'].'][]" placeholder="Qty">/'.$row['qty'];
                     }
                     else
                     {
                        echo '<input type="number" min="0" max="'.$row['qty'].'" name="qty_list['.$row['foodId'].'][]" placeholder="Qty" disabled>/'.$row['qty'];
                     }
                     echo '</td>
                     <td class="text-center">'.$row['price'].'</td>
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
                  <button type="submit" name="submit" class="btn btn-danger">Add</button>
               </div>
            </form>
            </div>
         </form>
      </div>
   </body>
</html>

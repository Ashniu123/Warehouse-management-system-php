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
   header("Location: ".get_base_url()."admin.php");
   die();
}
if(isset($_POST['update']))
{
   $missinglist=array();
   if (empty($_POST['status'])) {
      $missingdata[] = 'status';
   } else {
      $status = $_POST['status'];
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
         foreach($status[$id] as $stat)
         {
            $query="UPDATE orders SET status='$stat' WHERE order_id='$id'";
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
      $needoforecho=1;
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
   <h1 class="centerFlex">Edit Order(s)</h1><hr>
   <h4 class="centerFlex">Orders:</h4>
      <div class="centerFlex ">
         <form class="frm-wel" method="post">
            <div>
               <?php
               if($needforecho==1)
               {
                  echo '<strong>Nothing Updated</strong>';
               }
               if($success==1)
               {
                  echo '<strong>Status Updated</strong>';
               }
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
                  <th class="text-center">Food Id</th>
                  <th class="text-center">Section</th>
                  <th class="text-center">Total</th>
                  <th class="text-center">Order Date</th>
                  <th class="text-center">Delivery Date</th>
                  <th class="text-center">Status</th>
                  <th class="text-center">Delivery Address</th>
                  <th class="text-center">Select</th>
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
                     <td class="text-center">'.$row['delivery_date'].'</td><td>';
                     if(strcmp($row['status'],"Undelivered")==0)
                     {
                        echo '<select name="status['.$row['order_id'].'][]" class="form-control"><option>'.$row['status'].'
                        </option><option>delivered</option><option>deleted</option></select>';
                     }
                     else if(strcmp($row['status'],"delivered")==0)
                     {
                        echo '<select name="status['.$row['order_id'].'][]" class="form-control"><option>'.$row['status'].'
                        </option><option>Undelivered</option><option>deleted</option></select>';
                     }
                     else if(strcmp($row['status'],"deleted")==0)
                     {
                        echo '<select name="status['.$row['order_id'].'][]" class="form-control"><option>'.$row['status'].'
                        </option><option>Undelivered</option><option>delivered</option></select>';
                     }
                     echo '</td><td class="text-center">'.$row['address'].'</td>
                     <td class="text-center">
                     <label class="checkbox-inline"><input type="checkbox" name="check_list['.$row['order_id'].']" value="'.$row['order_id'].'"></label>
                     </td>
                     </tr>';
                  }//end of while
                  echo '</table>';
               }
               ?>
            </div>
         <div class="centerFlex"><input class="btn btn-danger" type="submit" value="Update" name="update"></div>
      </form>
   </div>
</body>
</html>

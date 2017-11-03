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
   function random_str($length)
   {
    $keys = array_merge(range(0,9), range('A', 'Z'));
    $key = "";
    for($i=0; $i < $length; $i++) {
        $key .= $keys[mt_rand(0, count($keys) - 1)];
    }
    return $key;
   }//to generate a random string to put into foodId column
   $success=0;
   $itemexists=0;
   $needforecho=0;
   $badexpiry=0;
   if(strcmp($stype,'buyer')==0)
   {
      $unauth=1;
   }
   if(isset($_POST['submit']))
   {
      $missingdata=array();
      if(empty($_POST['item_name']))
      {
         $missingdata[]='item_name';
      }
      else {
         $item_name=mysqli_real_escape_string($dbc,trim($_POST['item_name']));
      }
      if(empty($_POST['qty']))
      {
         $missingdata[]='qty';
      }
      else {
         $qty=$_POST['qty'];
      }
      if(empty($_POST['price']))
      {
         $missingdata[]='price';
      }
      else {
         $price=$_POST['price'];
      }
      if(empty($_POST['expiry']))
      {
         $missingdata[]='expiry_date';
      }
      else {
         $expiry=$_POST['expiry'];
         $current_date=date("Y-m-d");
         echo strtotime($expiry).'\n'.strtotime($current_date);
         if((strtotime($current_date) - strtotime($expiry)) > 0)
            $badexpiry=1;
      }
      if($badexpiry==0)
      {
         if(empty($missingdata))
         {
            $checksql="SELECT food_name,foodId,qty,addedon,expiry_date,price FROM food WHERE food_name='$item_name' AND s_id=$s_id";
            $response=@mysqli_query($dbc,$checksql);
            $foodId=random_str(5);
            $query="SELECT foodId from food";
            $foodIdcheck=@mysqli_query($dbc,$query);
            if(mysqli_num_rows($foodIdcheck)>0)//To get a unique foodId
            {
               while($row=mysqli_fetch_array($foodIdcheck))
               {
                  if(strcmp($row['foodId'],$foodId)!=0)
                     $foodId=random_str(5);
               }
            }
            if(mysqli_num_rows($response)==0)
            {
               $query="INSERT INTO food(foodId,food_name,s_id,addedon,expiry_date,qty,price) VALUES('$foodId','$item_name','$s_id',CURDATE(),'$expiry',$qty,$price)";
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
            }//end of case where num_rows=0
            else if(mysqli_num_rows($response))
            {
               $row=mysqli_fetch_array($response);
               $old_expiry=$row['expiry_date'];
               if(strtotime($row['addedon'])==strtotime(date("Y-m-d")))
               {
                  $qty=$qty+$row['qty'];
                  $foodId=$row['foodId'];
                  if(strtotime($old_expiry)==strtotime($expiry))
                  {
                     if($row['price']==$price)
                        $query="UPDATE food SET qty=$qty WHERE foodId='$foodId'";
                     else
                     {
                        $query="UPDATE food SET qty=$qty,price=$price WHERE foodId='$foodId'";
                     }
                  }//if expiry dates are same
                  else
                  {
                     $query="INSERT INTO food(foodId,food_name,s_id,addedon,expiry_date,qty,price) VALUES('$foodId','$item_name','$s_id',CURDATE(),'$expiry',$qty,$price)";
                  }
               }//end of case when added on time is same
               else
               {
                  $query="INSERT INTO food(foodId,food_name,s_id,addedon,expiry_date,qty,price) VALUES('$foodId','$item_name','$s_id',CURDATE(),'$expiry',$qty,$price)";
               }//when date is different
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
         }
         else
         {
            $needforecho=1;
         }
      }
   }

?>
<html>
   <head>
      <title>Add Items!</title>
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
      <h4 class="text-center">Add Your Items:</h4>
      <div class="centerFlex">
      <form class="frm-wel container" method="post" action="additems.php">
         <div>
            <?php
               if($success==1)
               {
                  echo '<strong>Item Successfully Added</strong>';
               }
               else if($success==-1)
               {
                  echo '<strong class="text-danger">Item was not Added. Please try again!</strong>';
               }
               if($needforecho==1)
               {
                  echo '<strong>All Fields are Mandatory!</strong>';
               }
               if($badexpiry==1)
               {
                  echo '<strong>Re-check expiry date!</strong>';
               }
            ?>
         </div>
         <div class="form-group col-md-12">
          <label class="control-label">Item Name:</label>
          <input type="text" class="form-control" name="item_name" value="CompanyName_ItemName_Wt">
         </div>
         <div class="form-group col-md-4">
          <label>Quantity</label>
          <input type="number" min="1" class="form-control" name="qty" placeholder="Quantity">
         </div>
         <div class="form-group col-md-4">
            <label class="control-label">Price:</label>
           <span class="input-group">
           <span class="input-group-addon">₹</span>
           <input type="number" step="0.01" min="0.00" class="form-control" name="price" placeholder="Enter Price">
        </span>
         </div>
         <div class="form-group col-md-4">
          <label>Expiry Date</label>
          <input type="date" class="form-control" name="expiry">
         </div>
         <div class="text-center col-md-12">
         <div>All Fields are Mandatory!</div>
         <button type="submit" name="submit" class="btn btn-danger">Add</button>
         </div>
      </form>
      </div>
   </body>
</html>

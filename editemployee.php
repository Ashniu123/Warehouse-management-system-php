<?php
require_once('connect.php');
$needforecho=0;
$unauth=0;
$success=0;
if(isset($_SESSION['user_email'])){
   $stype=$_SESSION['stype'];
   if(strcmp($stype,'buyer')==0||strcmp($stype,'seller')==0)
   {
      $unauth=1;
   }
}
if(isset($_SESSION['emp_id']))
{
   $department=$_SESSION['department'];
   if(strcmp("Managing",$department)!=0)
   {
      $unauth=1;
   }
}
if(isset($_POST['update']))
{
   $missinglist=array();
   if (empty($_POST['department'])) {
      $missingdata[] = 'department';
   } else {
      $department = $_POST['department'];
   }
   if (empty($_POST['salary'])) {
      $missingdata[] = 'salary';
   } else {
      $salary=$_POST['salary'];
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
         foreach($department[$id] as $dep)
         {
            foreach($salary[$id] as $sal)
            {
               $query="UPDATE employee SET department='$dep',salary=$sal WHERE emp_id='$id'";
               $response=@mysqli_query($dbc,$query);
               if(mysqli_affected_rows($dbc))
               {
                  $success=1;
               }
               else {
                  $success=-1;
               }
            }
         }//end of middle foreach
      }//end of outer foreach
   }
   else {
      $needoforecho=1;
   }
}
?>
<html>
<head>
   <title>Edit Employee</title>
   <link rel="stylesheet" type="text/css" href="bootstrap.min.css"/>
   <link rel="stylesheet" type="text/css" href="styles.css">
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
   <a href="manager.php" role="button" class="btn btn-success">Back</a>
   </span>
   <h4 class="centerFlex">Employee Info:</h4>
   <form method="post" action="editemployee.php">
      <div class="centerFlex">
         <div class="frm-wel">
            <?php
               if($needforecho==1)
               {
                  echo '<span>Fill all details!</span>';
               }
               if($success==1)
               {
                  echo '<em>Details Successfully Updated!</em>';
               }
               $query="SELECT emp_id,emp_name,email,department,salary,contact,address FROM employee WHERE NOT emp_id='THEMANAGER'";
               $response=@mysqli_query($dbc,$query);
               if(mysqli_num_rows($response)==0)
               {
                  echo '<span class="text-center">No Employees Currently!</span>';
               }
               else
               {
                  echo '<table cellspacing="5" class="table table-striped tble" cellpadding="10">
                  <tr>
                  <th class="text-center">Id</th>
                  <th class="text-center">Name</th>
                  <th class="text-center">Email-Id</th>
                  <th class="text-center">Department</th>
                  <th class="text-center">Salary</th>
                  <th class="text-center">Contact</th>
                  <th class="text-center col-md-3">Address</th>
                  <th class=text-center>Select</th>
                  </tr>';
                  while($row=mysqli_fetch_array($response))
                  {
                     echo '<tr>
                     <td class="text-center">'.$row['emp_id'].'</td>
                     <td class="text-center">'.$row['emp_name'].'</td>
                     <td class="text-center">'.$row['email'].'</td>
                     <td class="text-center col-md-2">';
                     echo '<select name="department['.$row['emp_id'].'][]" class="form-control"><option>'.$row['department'].'
                     <option>Food</option><option>Transport</option></select></td>
                     <td class="text-center col-md-2">
                    <span class="input-group">
                    <span class="input-group-addon">₹</span>
                    <input type="number" step="100" min="0.00" class="form-control" value="'.$row['salary'].'"
                     name="salary['.$row['emp_id'].'][]" placeholder="Salary">
                    </span></td>
                     <td class="text-center">'.$row['contact'].'</td>
                     <td class="text-center">'.$row['address'].'</td>
                     <td class="text-center">
                     <label class="checkbox-inline"><input type="checkbox" name="check_list['.$row['emp_id'].']" value="'.$row['emp_id'].'"></label>
                     </td>
                     </tr>';
                  }//end of while
                  echo '</table>';
               }
            ?>
         </div>
      </div>
      <div class="centerFlex"><input class="btn btn-danger" type="submit" value="Update" name="update"></div>
   </form>
</body>
</html>

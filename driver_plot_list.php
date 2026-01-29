<?php 
 include("includes/includes.inc.php"); 
  $msg = false;
  if(isset($_GET['del']) && $_GET['del'] ==true){
	$msg = true;   
  }
 $Query = mysql_query( "SELECT id,plotname,status FROM cab_driver_plot WHERE company_id =".$_SESSION['company_id']." ORDER BY id DESC");
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Driver Plot List</title>
</head>
<body>
 <?php 
   if($msg){
	  echo 'Plot Data has been deleted Succesfully';  
   }
 
 ?>
  <div style="border:1px solid #ccc; border-radius:5px;">
    <a href="driver_plot.php" title="edit">Add Plot Locations</a>
    <table width="584">
     <tr bgcolor="#D4A406">
     	<th width="218" height="26">Name</th>
        <th width="181">Status</th>
        <th width="154">Options</th>
     </tr> 
      <?php
		if(mysql_num_rows($Query) > 0){
			while( $row = mysql_fetch_assoc( $Query ) ){?>	
			<tr align="center">
                <td><?php echo $row['plotname']?></td>
                <td><?php echo $row['status']?></td>
                <td>
                    <a href="driver_plot_edit.php?id=<?php echo $row['id']?>" title="edit">Edit</a>
                    <a href="driver_plot.php?id=<?php echo $row['id']?>" title="Delete">Delete</a>
                </td>
			<td width="11"></th> 
		<?php }?> 
       <?php }
	   		else{
			echo '<span style="color:red;">Data Not Available</span>';
		  }?>
     </table>
   </div>



</body>
</html>

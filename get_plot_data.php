<?php 
include("includes/includes.inc.php");
	$queryExi = mysql_query("SELECT id,plotname,plotlanlat,status,plotcolor FROM `cab_driver_plot` WHERE company_id=".$_SESSION['company_id']."");
	if(mysql_num_rows($queryExi) > 0){
		while($row  = mysql_fetch_assoc($queryExi)){
			$record[] = $row;
		}
	  
	   echo json_encode($record); // display all coordinaties now.	
	}
  
?>
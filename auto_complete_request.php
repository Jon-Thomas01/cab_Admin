<?php 
//include("includes/includes.inc.php");
/*$username = "dbicabitpc";
$password = "High9high!@";
$hostname = "dbicabitpc.db.10270975.hostedresource.com"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
  $selected = mysql_select_db("dbicabitpc",$dbhandle) 
  or die("Could not select examples");*/
  
  
/*$username = "dbicabitpc";
$password = "High9high!@";
$hostname = "localhost"; */

//connection to the database
/*$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
  $selected = mysql_select_db("dbicabitpc",$dbhandle) 
  or die("Could not select examples");*/
  
$username = "dbicabitpc";
$password = "High9high!@";
$hostname = "localhost"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password) 
  or die("Unable to connect to MySQL");
  $selected = mysql_select_db("dbicabitpc",$dbhandle) 
  or die("Could not select examples");

// $tableName ="usman_post_address1"; 
$tableName ="usman_post_address1"; 
  
  

if($_GET['action'] == 'from'){
	 $keyword = str_replace(' ', '', $_GET['val']);
	  if(!empty($keyword)){ 
	   $sqlQ = "select postcode from ".$tableName."  WHERE  
		 	REPLACE( postcode, ' ', '') LIKE '%".$keyword."%' GROUP BY postcode LIMIT 0,200";
			$queryExi = mysql_query($sqlQ);
				if(mysql_num_rows($queryExi) > 0){
				//echo '<ul>'; // IN('213','4234','213','4234','213','4234','213','4234');
					while($row  = mysql_fetch_array($queryExi)){
						 // $total = getCount($row['postcode']);
						// $img='';
						  //if(getCount($row['postcode']) > 0){
							 $img ='<img src="images/arrow.png" style="float:right;margin-top: 5px;">';  
						  //}
						
					echo '<li  style="cursor:pointer;"  onclick="getAddressFrom(\''.str_replace("'", "\'", $row['postcode']).'\')">'.$row['postcode'].' '.$img.'</li>';
				}
				//echo '</ul>';
			
			}
	   }
 }

function getCount($postCode){
	  $queryExi = mysql_query('SELECT count(id) AS total FROM '.$tableName.' WHERE postcode="'.$postCode.'"');
	  if(mysql_num_rows($queryExi) > 0){
		  $row =  mysql_fetch_array($queryExi); 
		  return $row['total'];
	  }
     
}
 if($_GET['action'] == 'postCode_from'){
	 $keyword =  $_GET['postCode_from'];
	  if(!empty($keyword)){ 
	   $sqlQ = "select full_address from ".$tableName."  WHERE  
		 	postcode = '".$keyword."' LIMIT 0,100";
			$queryExi = mysql_query($sqlQ);
				if(mysql_num_rows($queryExi) > 0){
					while($row  = mysql_fetch_array($queryExi)){
						$full_address = str_replace(', London', '', $row['full_address']).',London, UK';
						echo '<li  style="cursor:pointer;" onclick="assign_item_from(\''.str_replace("'", "\'", $row['full_address']).'\')">'.$full_address.'</li>';
				}
			}
	   }
  }




/***************************************************************/
if($_GET['action'] == 'to'){
	 $keyword = str_replace(' ', '', $_GET['val']);
	  if(!empty($keyword)){ 
	   $sqlQ = "select postcode from ".$tableName."  WHERE  
		 	REPLACE( postcode, ' ', '') LIKE '%".$keyword."%' GROUP BY postcode LIMIT 0,200";
			$queryExi = mysql_query($sqlQ);
				if(mysql_num_rows($queryExi) > 0){
				//echo '<ul>';
					while($row  = mysql_fetch_array($queryExi)){
						 // $total = getCount($row['postcode']);
						// $img='';
						  //if(getCount($row['postcode']) > 0){
							 $img ='<img src="images/arrow.png" style="float:right;margin-top: 5px;">';  
						  //}
						
					echo '<li  style="cursor:pointer;"  onclick="getAddressTo(\''.str_replace("'", "\'", $row['postcode']).'\')">'.$row['postcode'].' '.$img.'</li>';
				}
				//echo '</ul>';
			
			}
	   }
 }

 if($_GET['action'] == 'postCode_to'){
	 $keyword =  $_GET['postCode_to'];
	  if(!empty($keyword)){ 
	   $sqlQ = "select full_address from ".$tableName."  WHERE  
		 	postcode = '".$keyword."' LIMIT 0,100";
			$queryExi = mysql_query($sqlQ);
				if(mysql_num_rows($queryExi) > 0){
					while($row  = mysql_fetch_array($queryExi)){
						$full_address = str_replace(', London', '', $row['full_address']).',London, UK';
						echo '<li  style="cursor:pointer;" onclick="assign_item_to(\''.str_replace("'", "\'", $row['full_address']).'\')">'.$full_address.'</li>';
				}
			}
	   }
  }



?>
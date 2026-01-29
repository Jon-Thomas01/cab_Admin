<?php 
include("includes/includes.inc.php");
//$username = "dbicabitpc";
//$password = "High9high!@";
//$hostname = "dbicabitpc.db.10270975.hostedresource.com"; 
//connection to the database
//$dbhandle = mysql_connect($hostname, $username, $password) 
//or die("Unable to connect to MySQL");
//$selected = mysql_select_db("dbicabitpc",$dbhandle) 
 // or die("Could not select examples");


if($_GET['action'] == 'from'){
	 $keyword  = $_GET['val'];
	if(!empty($keyword)){
		 $queryExi = mysql_query("select full_address from faizan_uk_postcode_address1  WHERE  full_address LIKE '%$keyword%' GROUP BY full_address LIMIT 0,100");
	   	if(mysql_num_rows($queryExi) > 0){
			echo '<ul>';
				while($row  = mysql_fetch_array($queryExi)){
				  echo '<li onclick="set_item_from(\''.str_replace("'", "\'", $row['full_address']).'\')" style="cursor:pointer;">'.$row['full_address'].'</li>';
				}
		    echo '</ul>';
		 }
	}
}


if($_GET['action'] == 'to'){
	 $keyword = $_GET['val'];
	if(!empty($keyword)){
		
		$queryExi = mysql_query("select full_address from faizan_uk_postcode_address1  WHERE  full_address LIKE '%$keyword%' GROUP BY full_address LIMIT 0,100");
	  	
		if(mysql_num_rows($queryExi) > 0){
			echo '<ul>';
			 while($row  = mysql_fetch_array($queryExi)){
			  echo '<li onclick="set_item_to(\''.str_replace("'", "\'", $row['full_address']).'\')" style="cursor:pointer;">'.$row['full_address'].'</li>';
			 }
		 	echo '</ul>';  
		}
	}
}



  if($_GET['action'] == 'getDistance'){
	if($_GET['from']!='' && $_GET['to']!='') {
		$postcode1 = str_replace(" ","+",str_replace(", ","+",$_GET['from']));
		$postcode2 = str_replace(" ","+",str_replace(", ","+",$_GET['to']));
		$result = array();
		$strResult = 0;
		$url = "http://maps.googleapis.com/maps/api/distancematrix/json?origins=$postcode1,UK&destinations=$postcode2,UK&mode=driving&language=en-EN&sensor=false";
		$data = @file_get_contents($url);
		$result = json_decode($data, true);
		$distance = $result['rows'][0]['elements'][0]['distance']['text'];
		$duration = $result['rows'][0]['elements'][0]['duration']['text'];
		if($distance!='' && $duration!=''){
			$strResult =  $distance.",".$duration; 
		}
		echo $strResult;
	}
	/*$_SESSION['distance'] = $result['rows'][0]['elements'][0]['distance']['text']; 
	$_SESSION['duration'] = $result['rows'][0]['elements'][0]['duration']['text'];
	$meters  = $result['rows'][0]['elements'][0]['distance']['value'];
	$_SESSION['durationAll'] = $_SESSION['duration'];
	$result = $meters/1609.344;  //convert to miles
	$_SESSION['total_distance'] =  round($result,1);
	$_SESSION['total_duration'] = $_SESSION['durationAll'];*/
}



if($_GET['action'] == 'saveuseraccount'){
	 $name = $_GET['name'];  
	 $email = $_GET['email'];
	 $phone = $_GET['phone'];
	 $address = $_GET['address'];
	 $company_id = $_SESSION['company_id'];
	 if(!empty($name)){ 
			$queryExi = mysql_query("INSERT INTO cab_account_user (name,email,phone,address,company_id,status)
			VALUES('".$name."','".$email."','".$phone."','".$address."','".$company_id."','1')");
			if($queryExi !=''){
				$lastInsertedId = mysql_insert_id();
				$Query = "SELECT id,name FROM cab_account_user WHERE company_id ='".$company_id."' and id='".$lastInsertedId."' ";
				$queryStr = mysql_query($Query);
				$row = mysql_fetch_array( $queryStr) ;
				echo '<option value="'.$row['id'].'">'.$row['name'].' </option>';
				}else{
					echo 0;
			    }
		}
	}




if($_GET['action'] == 'getMeetGreet'){
	 $cart_order_id = $_GET['cart_order_id'];
	if(!empty($cart_order_id)){
		
		$queryExi = mysql_query("SELECT name,detail FROM `cab_meetgreet` WHERE cart_order_id=".$cart_order_id);
	  	$result  = '';
		if(mysql_num_rows($queryExi) > 0){
			$row = mysql_fetch_array( $queryExi) ;
			$result .='<table>';
				if($row['name'] <> ""){
					$result .='<tr><th>Name:</th><td>'.$row['name'].'</td></tr>';
				}
				if($row['detail'] <> ""){
					$result .= '<tr><th>Detail:</th><td>'.$row['detail'].'</td></tr>';
				}
			$result .= '</table>'; 
		}
		echo $result;
		
	}
}

 if($_REQUEST['action'] == 'get_driver'){
	 $id = $_REQUEST['id'];
	if($id <> ""){
		$base_id = 'base_id='.$id.'  AND ';
		if($id==0){
			$base_id='';	
		}
		$queryExi = mysql_query("SELECT id,driver_no FROM `cab_fleet` WHERE ".$base_id."  company_id=".$_SESSION['company_id']."");
	  	$result  = '';
		if(mysql_num_rows($queryExi) > 0){
			$i=1;
			while($row = mysql_fetch_array( $queryExi)) {
				if($row['driver_no'] <> ""){
				$c='';
				if($i==1){
					$c='checked="checked"';
				}
				$result .='<div  class="radio_wrap">
				<span class="fl">'.$row['driver_no'].' :</span><input type="radio" class="fl" name="driver_no" value="'.$row['driver_no'].'"  '.$c.'>
				</div>';
				}
			$i++;
			}
		 echo $result;
		}else{
		  echo '<span style="color:red;">Data not available.!</span>';		
		}
	}else{
	   echo '<span style="color:red;">Data not available.!</span>';	
	}
 }
//multiple_days
if(isset($_POST['action'])  && $_POST['action']== 'rota_drivers'){
 	$idle_date = date("Y-m-d", strtotime($_POST['idle_date'])); 
	$from_time = $_POST['from_time'];
	$driver_no = $_POST['driver_no'];
	$to_time = $_POST['to_time'];
	$is_reguler = 0;
	if(isset($_POST['is_reguler']) && $_POST['is_reguler'] <>""){
	 $is_reguler = 1;	
	}
	
	
	$company_id = $_SESSION['company_id'];
	if($is_reguler ==1){
		$rota_dates_schedule = $_POST['rota_dates_schedule'];
		$rota_dates_schedule = ltrim($rota_dates_schedule,'||');
		$aRota_dates_schedule= explode("||",$rota_dates_schedule);
		
		for($i =0; $i<sizeof($aRota_dates_schedule);$i++){
			//$dateScehedule = date("Y-m-d", strtotime($aRota_dates_schedule[$i]));
			$dateScehedule = $aRota_dates_schedule[$i];
			//echo "date is ".$dateScehedule.'</br>';
			$queryExi = mysql_query("INSERT INTO cab_rota (`company_id`, `driver_no`, `idle_date`, `from_time`, `to_time`)
			VALUES('".$company_id."','".$driver_no."','".$dateScehedule."','".$from_time."','".$to_time."')");
		}
		
		echo "</br>";
		echo "<pre>";
			print_r($aRota_dates_schedule);
		echo "</pre>";
		die();
	}else{
	  
	 
	$result = false;
	$queryExi = mysql_query("INSERT INTO cab_rota (`company_id`, `driver_no`, `idle_date`, `from_time`, `to_time`)
	VALUES('".$company_id."','".$driver_no."','".$idle_date."','".$from_time."','".$to_time."')");
	if($queryExi !=''){
		$result = true;	
		
		
		
		
	}
	echo $result;
	}
	
	//date("d-m-Y", strtotime($row['idle_date']))
	
	
	/*$company_id = $_SESSION['company_id'];
	$result = false;
	$queryExi = mysql_query("INSERT INTO cab_rota (`company_id`, `driver_no`, `idle_date`, `from_time`, `to_time`)
	VALUES('".$company_id."','".$driver_no."','".$idle_date."','".$from_time."','".$to_time."')");
	if($queryExi !=''){
		$result = true;
		/*$lastInsertedId = mysql_insert_id(); // get latest id
		$Query = "SELECT * FROM cab_rota WHERE company_id ='".$company_id."' and id='".$lastInsertedId."' ";
		$queryStr = mysql_query($Query);
		$row = mysql_fetch_array( $queryStr) ;
		$result .='
				<div id="content_row" class="fl">
					<div class="box w1 bgcolor1 fl m2">'.$row['driver_no'].'</div>
					<div class="box w2 bgcolor1 fl ">'.$row['idle_date'].'</div>
					<div class="box w2 bgcolor1 fl ">'.$row['from_time'].'</div>
					<div class="box w2 bgcolor1 fl ">'.$row['to_time'].'</div>
					<div class="box w3 bgcolor1 fl ">
						<a href="javascript:void(0);">
							<img src="images/close_icon.png" alt="close" title="delete">
						</a>
					</div>
               </div>';
	 }*/
	




if($_REQUEST['action'] == 'delete_rota'){
	 $id = $_REQUEST['id'];
	if($id <> ""){
		$result =false;
		$queryExi = mysql_query("DELETE FROM cab_rota WHERE id=".$id." ");
		if($queryExi){
		  $result = true;	
		}
	  	echo $result;
	}
 }





?>
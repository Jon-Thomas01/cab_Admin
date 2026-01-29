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
	$keyword = str_replace(' ', '', $_GET['val']);
	if(!empty($keyword)){ 
		 $sqlQ = "select full_address from faizan_uk_postcode_address1  WHERE  
		 	REPLACE( full_address, ' ', '') LIKE '%".$keyword."%' GROUP BY full_address LIMIT 0,100";
		 $queryExi = mysql_query($sqlQ);
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
	 $keyword = str_replace(' ', '', $_GET['val']);
	if(!empty($keyword)){
		
		$sqlQ = "select full_address from faizan_uk_postcode_address1  WHERE  
		 	REPLACE( full_address, ' ', '') LIKE '%".$keyword."%' GROUP BY full_address LIMIT 0,100";
	  	 $queryExi = mysql_query($sqlQ);
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
		$distance = $result['rows'][0]['elements'][0]['distance']['text'];  // distance in meter.
		$duration = $result['rows'][0]['elements'][0]['duration']['text'];
		
		if($distance!='' && $duration!=''){
			// conversion in miles
			$result = $result['rows'][0]['elements'][0]['distance']['value']/1609.344; 	
			$total_miles = round($result,1);
			// conversion in miles
			$strResult =  $distance.",".$duration.",".$total_miles; 
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
	if($cart_order_id!='undefined'){ 
		
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
		// check for enage drivers
		$query = "SELECT driver_no FROM `cab_order_sum` WHERE pick_date = CURDATE() AND driver_no!='' AND company_id = '".$_SESSION['company_id']."' AND deleted=0"; 
		$Query1 = mysql_query( $query);	
		
		while($row = mysql_fetch_assoc( $Query1 )){
			$driver_No[]  =  $row['driver_no'];
		}	
		
		$currentDate  =  date('Y-m-d');
		$queryExi = mysql_query("SELECT id,driver_no,driver_pco_exp_date FROM `cab_fleet` WHERE ".$base_id."  company_id=".$_SESSION['company_id']."");
		$result  = '';
		if(mysql_num_rows($queryExi) > 0){
			$i=1;
			while($row = mysql_fetch_array( $queryExi)) {
				if($row['driver_no'] <> ""){
				$c='';
				if($i==1){
					$c='checked="checked"';
				}
				
				$border="";
				$title = '';
				
			
			if(count($driver_No) > 0){
			   
			   if(!in_array($row['driver_no'],$driver_No)){	
				if(strtotime($row['driver_pco_exp_date']) < strtotime($currentDate)){
					$border ='style="border:1px solid red;"';
					$title = 'This Driver PCO has Expired.';
					
				}else
				if(strtotime($row['driver_pco_exp_date']) > strtotime($currentDate)){ 
					   $remainingDay = checkRemaingDays($row['driver_pco_exp_date']);
					//if($remainingDay >=30){
						$border ='style="border:1px solid blue;"';
						$title = 'This Driver PCO will Expire in, '.$remainingDay.' Days';
						$daysLeft ='<span style="color:red;font-size: 9px;">Expire in '.'<b>'.$remainingDay.'</b> Days</span>';	
					//}
				}
				
				$result .='<div  class="radio_wrap" '.$border.' title="'.$title.'">
					<span class="fl">'.$row['driver_no'].' :</span><input type="radio" class="fl" name="driver_no" value="'.$row['driver_no'].'"  '.$c.'>
					</div>';
				}
			   
			   	
			}else{
			
			 if(strtotime($row['driver_pco_exp_date']) < strtotime($currentDate)){
					$border ='style="border:1px solid red;"';
					$title = 'This Driver PCO has Expired.';
					
				}else
				if(strtotime($row['driver_pco_exp_date']) > strtotime($currentDate)){ 
					   $remainingDay = checkRemaingDays($row['driver_pco_exp_date']);
					//if($remainingDay >=30){
						$border ='style="border:1px solid blue;"';
						$title = 'This Driver PCO will Expire in, '.$remainingDay.' Days';
						$daysLeft ='<span style="color:red;font-size: 9px;">Expire in '.'<b>'.$remainingDay.'</b> Days</span>';	
					//}
				}
				
				$result .='<div  class="radio_wrap" '.$border.' title="'.$title.'">
					<span class="fl">'.$row['driver_no'].' :</span><input type="radio" class="fl" name="driver_no" value="'.$row['driver_no'].'"  '.$c.'>
					</div>';
				}
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
 
/****************************************************************************/
   
   if($_REQUEST['action'] == 'get_report_driver'){
	 $id = $_REQUEST['id'];
	if($id <> ""){
		$base_id = 'base_id='.$id.'  AND ';
		if($id==0){
			$base_id='';	
		}
		$selctName = $_REQUEST['selctName'];
		$currentDate  =  date('Y-m-d');
		$queryExi = mysql_query("SELECT id,driver_no FROM `cab_fleet` WHERE ".$base_id."  company_id=".$_SESSION['company_id']."");
	  	$result  = '';
		if(mysql_num_rows($queryExi) > 0){
			$i=1; 
			$result .= '<span class="arr"></span>';
			$result .= '<select  class="" name="'.$selctName.'" class="tab_show" >'; 
	$result .= '<option  value="" style="padding: 5px 10px; color: #b5b5b5;  text-transform: capitalize; background:#fff; font-size:11px;">Select Driver No</option>';
		while($row = mysql_fetch_array( $queryExi)) {
				if($row['driver_no'] <> ""){
					$result .= '<option value="'.$row['driver_no'].'">'.$row['driver_no'].'</option>';
				}
			 $i++;
			}
			$result .= '</select>';
		  echo $result;
		}else{
		  echo '<span style="color:red;">Drivers not available for this bases.!</span>';		
		}
	}else{
	   echo '<span style="color:red;">Drivers not available for this bases.!</span>';
	}
 }
   
 
 /***************************************************************************/	 
	 
	 
	
 
//multiple_days
if(isset($_POST['action'])  && $_POST['action']== 'rota_drivers'){
	
	$idle_date  = $_POST['idle_date']; // date comes in correct format
	$aDates =explode("-",$idle_date);
	$d = $aDates[0];
	$selectedmonth = $aDates[1];
	$selectedyear = $aDates[2];
	
	$idle_date = date("Y-m-d", strtotime($idle_date));
	$from_time = $_POST['from_time'];
	$driver_no = $_POST['driver_no'];
	$to_time = $_POST['to_time'];
	$is_reguler = 0;
	if(isset($_POST['is_reguler']) && $_POST['is_reguler'] <> ""){
		$is_reguler = 1;	
	}	
    	$company_id = $_SESSION['company_id'];
	 	$dayCounter = 1;
		if($is_reguler ==1){
			$rota_dates_schedule = $_POST['rota_dates_schedule'];
			$rota_dates_schedule = ltrim($rota_dates_schedule,'||');
			$aRota_dates_schedule= explode("||",$rota_dates_schedule);
			
			if($aRota_dates_schedule[0] == ''){
				$result = false;
				$queryExi = mysql_query("INSERT INTO cab_rota (`company_id`, `driver_no`, `idle_date`, `from_time`, `to_time`, `rota_month`, `rota_year`)
				VALUES('".$company_id."','".$driver_no."','".$idle_date."','".$from_time."','".$to_time."','".$selectedmonth."','".$selectedyear."')");
				if($queryExi !=''){
					$result = true;	
				}
				echo $result;   
				exit;
			}
			for($i =0; $i<sizeof($aRota_dates_schedule);$i++){
				$dateScehedule = $aRota_dates_schedule[$i];
				$aStrDates = explode("-",$dateScehedule);
				$year = $aStrDates[0];
				$month = $aStrDates[1];
				$day  = $aStrDates[2];
				$check = false;
				if($selectedmonth=='01' and ($day >=25 and $day <=37)){
					$month = '02';
					if($day == 25){
						$month = '01';
					}else
					if($day == 26){
						$month = '01';
					}else
					if($day == 24){
						$month = '01';
					}else
					if($day == 27){
						$month = '01';
					}else
					if($day == 28){
						$month = '01';
					}else
					if($day == 29){
						$month = '01';
					}else
					if($day == 30){
						$month = '01';
					}else
					if($day == 31){
					    $month = '01';
					}else
					if($day == 32){
						$day = '01';
					}else
					if($day == 33){
						$day = '02';
					}else
					if($day == 34){
						$day = '03';
					}else
					if($day == 35){
						$day = '04';
					}else
					if($day == 36){
						$day = '05';
					}else
					if($day == 37){
						$day = '06';
					}
					$check = true; 
				}else
				 if($selectedmonth=='02' and ($day >=24 and $day <=35)){
					$month = '03';
					if($day == 24){
						//$day = '01';
						$month = '02';
					}else
					if($day == 25){
						//$day = '01';
						$month = '02';
					}else
					if($day == 26){
						//$day = '01';
						$month = '02';
					}else
					if($day == 27){
						//$day = '01';
						$month = '02';
					}else
					if($day == 28){
						$month = '02';
					}else
					if($day == 29){
						$month = '02';
					}else
					if($day == 30){
						$day = '01';
					}else
					if($day == 31){
						$day = '02';
					}else
					if($day == 32){
						$day = '03';
					}else
					if($day == 33){
						$day = '04';
					}else
					if($day == 34){
						$day = '05';
					}else
					if($day == 35){
						$day = '06';
					}
				 	$check = true; 
				}else
				if($selectedmonth=='03' and ($day >=25 and $day <=37)){	
					$month = '04';
					if($day == 25){
						$month = '03';
					}else
					if($day == 26){
						$month = '03';
					}else
					if($day == 24){
						$month = '03';
					}else
					if($day == 27){
						$month = '03';
					}else
					if($day == 28){
						$month = '03';
					}else
					if($day == 29){
						$month = '03';
					}else
					if($day == 30){
						$month = '03';
					}else
					if($day == 31){
					    $month = '03';
					}else
					if($day == 32){
						$day = '01';
					}else
					if($day == 33){
						$day = '02';
					}else
					if($day == 34){
						$day = '03';
					}else
					if($day == 35){
						$day = '04';
					}else
					if($day == 36){
						$day = '05';
					}else
					if($day == 37){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='04' and ($day >=24 and $day <=36)){	
					$month = '05';
					if($day == 24){
						$month = '04';
					}else
					if($day == 25){
						$month = '04';
					}else
					if($day == 26){
						$month = '04';
					}else
					if($day == 24){
						$month = '04';
					}else
					if($day == 27){
						$month = '04';
					}else
					if($day == 28){
						$month = '04';
					}else
					if($day == 29){
						$month = '04';
					}else
					if($day == 30){
						$month = '04';
					}else
					if($day == 31){
					    $day = '01';
					}else
					if($day == 32){
						$day = '02';
					}else
					if($day == 33){
						$day = '03';
					}else
					if($day == 34){
						$day = '04';
					}else
					if($day == 35){
						$day = '05';
					}else
					if($day == 36){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='05' and ($day >=25 and $day <=37)){			
					$month = '06';
					if($day == 25){
						$month = '05';
					}else
					if($day == 26){
						$month = '05';
					}else
					if($day == 24){
						$month = '05';
					}else
					if($day == 27){
						$month = '05';
					}else
					if($day == 28){
						$month = '05';
					}else
					if($day == 29){
						$month = '05';
					}else
					if($day == 30){
						$month = '05';
					}else
					if($day == 31){
					    $month = '05';
					}else
					if($day == 32){
						$day = '01';
					}else
					if($day == 33){
						$day = '02';
					}else
					if($day == 34){
						$day = '03';
					}else
					if($day == 35){
						$day = '04';
					}else
					if($day == 36){
						$day = '05';
					}else
					if($day == 37){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='06' and ($day >=24 and $day <=36)){		
					$month = '07';
					if($day == 24){
						$month = '06';
					}else
					if($day == 25){
						$month = '06';
					}else
					if($day == 26){
						$month = '06';
					}else
					if($day == 24){
						$month = '06';
					}else
					if($day == 27){
						$month = '06';
					}else
					if($day == 28){
						$month = '06';
					}else
					if($day == 29){
						$month = '06';
					}else
					if($day == 30){
						$month = '06';
					}else
					if($day == 31){
					    $day = '01';
					}else
					if($day == 32){
						$day = '02';
					}else
					if($day == 33){
						$day = '03';
					}else
					if($day == 34){
						$day = '04';
					}else
					if($day == 35){
						$day = '05';
					}else
					if($day == 36){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='07' and ($day >=25 and $day <=37)){		
					$month = '08';
					if($day == 25){
						$month = '07';
					}else
					if($day == 26){
						$month = '07';
					}else
					if($day == 24){
						$month = '07';
					}else
					if($day == 27){
						$month = '07';
					}else
					if($day == 28){
						$month = '07';
					}else
					if($day == 29){
						$month = '07';
					}else
					if($day == 30){
						$month = '07';
					}else
					if($day == 31){
					    $month = '07';
					}else
					if($day == 32){
						$day = '01';
					}else
					if($day == 33){
						$day = '02';
					}else
					if($day == 34){
						$day = '03';
					}else
					if($day == 35){
						$day = '04';
					}else
					if($day == 36){
						$day = '05';
					}else
					if($day == 37){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='08' and ($day >=25 and $day <=37)){		
					$month = '09';
					if($day == 25){
						$month = '08';
					}else
					if($day == 26){
						$month = '08';
					}else
					if($day == 24){
						$month = '08';
					}else
					if($day == 27){
						$month = '08';
					}else
					if($day == 28){
						$month = '08';
					}else
					if($day == 29){
						$month = '08';
					}else
					if($day == 30){
						$month = '08';
					}else
					if($day == 31){
					    $month = '08';
					}else
					if($day == 32){
						$day = '01';
					}else
					if($day == 33){
						$day = '02';
					}else
					if($day == 34){
						$day = '03';
					}else
					if($day == 35){
						$day = '04';
					}else
					if($day == 36){
						$day = '05';
					}else
					if($day == 37){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='09' and ($day >=24 and $day <=36)){		
					$month = '10';
					if($day == 24){
						$month = '09';
					}else
					if($day == 25){
						$month = '09';
					}else
					if($day == 26){
						$month = '09';
					}else
					if($day == 24){
						$month = '09';
					}else
					if($day == 27){
						$month = '09';
					}else
					if($day == 28){
						$month = '09';
					}else
					if($day == 29){
						$month = '09';
					}else
					if($day == 30){
						$month = '09';
					}else
					if($day == 31){
					    $day = '01';
					}else
					if($day == 32){
						$day = '02';
					}else
					if($day == 33){
						$day = '03';
					}else
					if($day == 34){
						$day = '04';
					}else
					if($day == 35){
						$day = '05';
					}else
					if($day == 36){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='10' and ($day >=25 and $day <=37)){		
					$month = '11';
					if($day == 25){
						$month = '10';
					}else
					if($day == 26){
						$month = '10';
					}else
					if($day == 24){
						$month = '10';
					}else
					if($day == 27){
						$month = '10';
					}else
					if($day == 28){
						$month = '10';
					}else
					if($day == 29){
						$month = '10';
					}else
					if($day == 30){
						$month = '10';
					}else
					if($day == 31){
					    $month = '10';
					}else
					if($day == 32){
						$day = '01';
					}else
					if($day == 33){
						$day = '02';
					}else
					if($day == 34){
						$day = '03';
					}else
					if($day == 35){
						$day = '04';
					}else
					if($day == 36){
						$day = '05';
					}else
					if($day == 37){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='11' and ($day >=24 and $day <=36)){		
					$month = '12';
					if($day == 24){
						$month = '11';
					}else
					if($day == 25){
						$month = '11';
					}else
					if($day == 26){
						$month = '11';
					}else
					if($day == 24){
						$month = '11';
					}else
					if($day == 27){
						$month = '11';
					}else
					if($day == 28){
						$month = '11';
					}else
					if($day == 29){
						$month = '11';
					}else
					if($day == 30){
						$month = '11';
					}else
					if($day == 31){
					    $day = '01';
					}else
					if($day == 32){
						$day = '02';
					}else
					if($day == 33){
						$day = '03';
					}else
					if($day == 34){
						$day = '04';
					}else
					if($day == 35){
						$day = '05';
					}else
					if($day == 36){
						$day = '06';
					}
					$check = true; 
				}else
				if($selectedmonth=='12' and ($day >=25 and $day <=37)){		
					$month = '01';
					if($day == 25){
						$month = '12';
					}else
					if($day == 26){
						$month = '12';
					}else
					if($day == 24){
						$month = '12';
					}else
					if($day == 27){
						$month = '12';
					}else
					if($day == 28){
						$month = '12';
					}else
					if($day == 29){
						$month = '12';
					}else
					if($day == 30){
						$month = '12';
					}else
					if($day == 31){
					    $month = '12';
					}else
					if($day == 32){
						$day = '01';
						$year = '2017';
					}else
					if($day == 33){
						$day = '02';
						$year = '2017';
					}else
					if($day == 34){
						$day = '03';
						$year = '2017';
					}else
					if($day == 35){
						$day = '04';
						$year = '2017';
					}else
					if($day == 36){
						$day = '05';
						$year = '2017';
					}else
					if($day == 37){
						$day = '06';
						$year = '2017';
					}
					$check = true; 
				}
				if($check ==true){
					$dateScehedule = $year.'-'.$month.'-'.$day;
				}
				$result = false;
				$queryExi = mysql_query("INSERT INTO cab_rota (`company_id`, `driver_no`, `idle_date`, `from_time`, `to_time`,`rota_month`, `rota_year`)
				VALUES('".$company_id."','".$driver_no."','".$dateScehedule."','".$from_time."','".$to_time."','".$month."','".$year."')");
				if($queryExi !=''){
					$result = true;	
				}
			 }
		}else{
			$result = false;
			$queryExi = mysql_query("INSERT INTO cab_rota (`company_id`, `driver_no`, `idle_date`, `from_time`, `to_time`,`rota_month`, `rota_year`)
			VALUES('".$company_id."','".$driver_no."','".$idle_date."','".$from_time."','".$to_time."','".$selectedmonth."','".$selectedyear."')");
			if($queryExi !=''){
				$result = true;	
			}
	  }
	  
	 echo $result; 
 }

 
// delete rota
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
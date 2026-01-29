<?php
include("includes/includes.inc.php");
/*require_once('classes/booking.php');
$all_booking  = new booking();*/
//$lang = getLang("includes".CC_DS."content".CC_DS."jobListing.inc.php");




//=========================================================
//    load  sub cat
//=========================================================

if($_REQUEST['act'] == "add_to_fav")
{
	//echo 'sammar'; exit;
	
	if(isset($_SESSION['user_id']))
	{
		$check = "Select * from cab_favourite where user_id = ".$_SESSION['user_id']." and therip_id = ".$_REQUEST['prop_id'];
		$result = $db->numrows($check);
		if($result >0)
		{
			echo '<p style="color:red;">Already saved</p>';
		}
		else
		{
			//$record["user_id"] = $db->mySQLSafe($_SESSION['user_id']);
			$record["therip_id"] = $db->mySQLSafe($_REQUEST['prop_id']);
			$record["user_id"] = $db->mySQLSafe($_SESSION['user_id']);
			$record["timestamp"] = $db->mySQLSafe(time());
			
			$insert = $db->insert($glob['dbprefix']."cab_favourite", $record);
			if($insert){echo '<p style="color:green;" >Successfully added</p>';}
			else{echo '<p style="color:red;">Failed to add</p>';}

		}
       }
	   
	 else if(isset($_SESSION['visitor_id']))
	{
		$check = "Select * from cab_favourite where user_id = ".$_SESSION['visitor_id']." and therip_id = ".$_REQUEST['prop_id'];
		$result = $db->numrows($check);
		if($result >0)
		{
			echo '<p style="color:red;">Already saved</p>';
		}
		else
		{
			//$record["user_id"] = $db->mySQLSafe($_SESSION['user_id']);
			$record["therip_id"] = $db->mySQLSafe($_REQUEST['prop_id']);
			$record["user_id"] = $db->mySQLSafe($_SESSION['visitor_id']);
			$record["timestamp"] = $db->mySQLSafe(time());
			
			$insert = $db->insert($glob['dbprefix']."cab_favourite", $record);
			if($insert){echo '<p style="color:green;" >Successfully added</p>';}
			else{echo '<p style="color:red;">Failed to add</p>';}

		}
       }  
	
	else
	{
		echo '<p style="color:red;">Please Sign In to save.</p>';
	}
	
	
	//echo $_POST['pid'];
	
}

    
	// check next hours jobs
	
	if($_GET['Action']=="checkNextHoursJobs")
	{
	  $result='';
	  if(isset($_GET['company_id']) && $_GET['company_id'] <> ""){
		$company_id  = $_GET['company_id'];	
			
		// 1 
		$date2=date('Y-m-d');
		$nowtime = date("Y-m-d H:i:s");
		$where1=' company_id='.$db->mySQLSafe($company_id).'   AND CONCAT_WS(" ", `pick_date`, `pick_time`) < "'.$nowtime.'" and pick_date = '.$db->mySQLSafe($date2);
		$data1 = getBookingData_aggregate($where1);
		
		// 2
		$dateplus2 = date('Y-m-d H:i:s', strtotime($nowtime . ' + 15 minute'));
$where2='  company_id='.$db->mySQLSafe($company_id).'  AND CONCAT_WS(" ", `pick_date`, `pick_time`) < "'.$dateplus2.'"    AND CONCAT_WS(" ", `pick_date`, `pick_time`) > "'.$nowtime.'" and pick_date = '.$db->mySQLSafe($date2);
		$data2 = getBookingData_aggregate($where2);
		
		
		
		// 3
		$dateplus3 = date('Y-m-d H:i:s', strtotime($nowtime . ' + 255 minute'));
		$where3='  company_id='.$db->mySQLSafe($company_id).'  AND CONCAT_WS(" ", `pick_date`, `pick_time`) < "'.$dateplus3.'"    AND CONCAT_WS(" ", `pick_date`, `pick_time`) > "'.$nowtime.'" and pick_date = '.$db->mySQLSafe($date2);
		$data3 = getBookingData_aggregate($where3);
		
		
		// 4
		$dateplus = date('Y-m-d H:i:s', strtotime($nowtime . ' + 240 minute'));
		$where4='  company_id='.$db->mySQLSafe($company_id).'  AND CONCAT_WS(" ", `pick_date`, `pick_time`) < "'.$dateplus.'"    AND CONCAT_WS(" ", `pick_date`, `pick_time`) > "'.$nowtime.'" and pick_date = '.$db->mySQLSafe($date2);
		$data4 = getBookingData_aggregate($where4);
		
		    $result=0;
			//if(is_array($data1) && $data1!=0){
				//$result = true;	
			//}else
			if(is_array($data2) && $data2!=0){
				$result = true;	
			}else
			if(is_array($data3) && $data3!=0){
				$result = true;	
			}else
			if(is_array($data4) && $data4!=0){
				$result = true;	
			}
			echo $result;
		
		}
	}
	
	/*****************Aggregate FUNCTION***********************/
	 
	 function getBookingData_aggregate($where){
		global $db;
		if($where != ''){
			$cond=' WHERE '.$where;
		}else{
			$cond='';
		}
		
	 	$query = 'SELECT * FROM `cab_order_sum` '.$cond.'  order by time DESC ';
		$results = $db->select($query);
		if($results == TRUE){
			return $results; 
		}else{
			return 0;
		}
	}
	
	
	
	/***************************************************/
	
	
	
	

	if($_GET['Action']=="checkNewEntery")
	{
		
		if(isset($_GET['company_id']) && $_GET['company_id'] <> ""){
			$company_id = $_GET['company_id']; 
		    $query = "SELECT cart_order_id FROM `cab_order_sum`
			 WHERE company_id='".$company_id."' 
			 AND latest_order =1  AND booking_status=0 
			 AND posted_by > -1  
			 order by time desc";
			 
		 	$results = $db->select($query);
			$data = false;
			if(count($results) > 0){
				$data = $results[0]['cart_order_id'];
			}
			echo  $data;
		}
	}

   
   if($_GET['Action']=="checkOrderCompletion")
	{
		
		if(isset($_GET['company_id']) && $_GET['company_id'] <> ""){
			 $company_id = $_GET['company_id']; 
		     $date  = date('Y-m-d'); // today
		 	$query = "SELECT cart_order_id,passenger_id  FROM `cab_order_sum`
			 WHERE company_id='".$company_id."' AND  pick_date ='".$date."'  AND booking_status=3"; // check booking status
			 $results = $db->select($query);
			 $data = false;
			if($results[0]['cart_order_id'] > 0){
					if($results[0]['order_type']==0){ // front booking 
					$aPassengerIds  = array();
						for($k=0;$k<sizeof($results);$k++){
							$aFPassengerIds[]  = $results[$k]['passenger_id'];    
						}   
					
					}
					if($results[0]['order_type']==1){ // backend booking 
					$aPassengerIds  = array();
						for($k=0;$k<sizeof($results);$k++){
							$aBPassengerIds[]  = $results[$k]['passenger_id'];    
						}   
					}
			   	
			   /* echo '<pre>';
				 print_r($aFPassengerIds);
				echo "</pre>";
				echo '<pre>';
				 print_r($aBPassengerIds);
				echo "</pre>";*/
				
			}
			//echo  $data;
		}
	}

   /* function getUserEmail($userId){
	   
	   if($userId <> ''){
	   		$query = "SELECT email FROM `cab_users` WHERE id IN()"; // check booking status
			$results = $db->select($query);
			$data = false;
			if(count($results) > 0){
				//$data = $results[0]['cart_order_id'];
				
				for($k=0;$k<sizeof($results);$k++){
				    
				  	
				
				}
				
				echo "<pre>";
				print_r($results);
				echo "</pre>";
				test();
			}
		}
	}*/
   
   
   
    
	
	
   
   









 if($_GET['Action']=="orderSeen")
	{
		
		if(isset($_GET['cart_id']) && $_GET['cart_id'] <> ""){
			$cart_id = $_GET['cart_id']; 
			$return = false;
			if($cart_id <> ""){
			 //$strQuery = "UPDATE `cab_order_sum` SET latest_order=0 WHERE cart_order_id='".$cart_id."'";
			 $strQuery = "UPDATE `cab_order_sum` SET latest_order=0 WHERE cart_order_id='".$cart_id."'";					
				if(mysql_query($strQuery)){
					$return = true;
				}
			}
			
		   echo $return;
		
		}
	}
   if($_GET['Action']=="turn_status")
	{
		
		if(isset($_GET['booking_id']) && $_GET['booking_id'] <> ""){
			$booking_id = $_GET['booking_id']; 
			$status = $_GET['status']; 
			if($status <> ""){
				$return = false;
			   // $strQuery = "UPDATE `cab_order_sum` SET booking_status='".$status."',time='".time()."' WHERE cart_order_id='".$booking_id."'";	
				$strQuery = "UPDATE `cab_order_sum` SET booking_status='".$status."',time='".time()."' WHERE cart_order_id='".$booking_id."'";					
				if(mysql_query($strQuery)){
					$return = true;
				}
				echo $return;
			}
		}
	}






if($_REQUEST['Action']=="load_subCate")
{

	 $str = "";
	 if($_REQUEST['catId'] == "")
	 {
	 	//echo "select cat_id, cat_name from cab_category where  cat_father_id = '".$catId."' order by cat_name asc" ;
		//exit();
		 $str.="<option value = ''>Select Sub Type</option>";
		 echo $str;
	 }
	 else
	 {
	 
			 $catId = $_REQUEST['catId'];
			if($catId > 0)
			{
			
				$sub_cate = $db->select("select cat_id, cat_name from cab_category where  cat_father_id = '".$catId."' order by cat_name asc");
			}
			$arrlen = count($sub_cate);
			$str="<option value = ''>Select Sub Type </option>";
			if($sub_cate)
			{
				for($i=0;$i<$arrlen;$i++)
				{		 
					  $cateID = $sub_cate[$i]['cat_id'];
					  $catName = $sub_cate[$i]['cat_name'];
					  $str.="<option value ='$cateID'>$catName</option>";
				}
				echo $str; 
			}
			else
			{
				echo "<option value = ''>Not Available</option>";
			}	
	 }
	 
}






//=========================================================
//    load cities
//=========================================================

if($_REQUEST['Action']=="load_city")
{

	 $str = "";
	// echo $_REQUEST['stateId']; exit;
	 //echo $_REQUEST['stateId']; exit;
	 if($_REQUEST['stateId'] == "")
	 {
	 	//echo "select Id, name from mps_cities where  state = '".$_REQUEST['stateId']."' order by name asc" ;
		//exit();
		 $str.="<option value = ''>Select City</option>";
		 echo $str;
	 }
	 else
	 {
	 
			 $stateId = $_REQUEST['stateId'];
			if($stateId > 0)
			{
			
				echo $cities = $db->select("select Id, name from cab_cities where  stateId = '".$stateId."' order by name asc");
			}
			$arrlen = count($cities);
			$str="<option value = ''>Select City</option>";
			if($cities)
			{
				for($i=0;$i<$arrlen;$i++)
				{		 
					  $stateID = $cities[$i]['Id'];
					  $stateName = $cities[$i]['name'];
					  $str.="<option value ='$stateID'>$stateName</option>";
				}
				echo $str; 
			}
			else
			{
				echo "<option value = ''>Not Available</option>";
			}	
	 }
	 
}




if($_REQUEST['Action']=="load_city_office")
{

	 $str = "";
	//echo $_REQUEST['stateId']; exit;
	 //echo $_REQUEST['stateId']; exit;
	 if($_REQUEST['stateId'] == "")
	 {
	 	//echo "select Id, name from mps_cities where  state = '".$_REQUEST['stateId']."' order by name asc" ;
		//exit();
		 $str.="<option value = ''>Select City</option>";
		 echo $str;
	 }
	 else
	 {
	 
			 $stateId = $_REQUEST['stateId'];
			if($stateId > 0)
			{
			
				echo $cities = $db->select("select Id, name from cab_cities where  stateId = '".$stateId."' order by name asc");
			}
			$arrlen = count($cities);
			$str="<option value = ''>Select City</option>";
			if($cities)
			{
				for($i=0;$i<$arrlen;$i++)
				{		 
					  $stateID = $cities[$i]['Id'];
					  $stateName = $cities[$i]['name'];
					  $str.="<option value ='$stateID'>$stateName</option>";
				}
				echo $str; 
			}
			else
			{
				echo "<option value = ''>Not Available</option>";
			}	
	 }
	 
}



//=========================================================
//    load states
//=========================================================

if($_REQUEST['Action']=="load_state")
{

	 $str = "";
	 if($_REQUEST['countryId'] == "")
	 {
		 $str.="<option value = ''>Select State </option>";
		 echo $str;
	 }
	 else
	 {
	 
			 $countryId = $_REQUEST['countryId'];
			if($countryId > 0)
			{
			//echo "select Id, name from cab_state where status = '1' and country_id = '".$countryId."' order by name asc" ;
				$cities = $db->select("select id, name from cab_iso_counties where  countryId = '".$countryId."' order by name asc");
			}
			$arrlen = count($cities);
			//$str .="<option value = '' >Select State</option>";
			if($cities)
			{
				
				for($i=0;$i<$arrlen;$i++)
				{		 
					 if($i==0){
						 $str .="<option value = '' >Select State</option>";
						 }
					  $stateID = $cities[$i]['id'];
					  $stateName = $cities[$i]['name'];
					  $str.="<option value ='$stateID'>$stateName</option>";
				}
				
				echo $str; 
			}
			else
			{
				echo "<option value = ''>Not Available</option>";
			}	
	 }
	 
}


//=========================================================
//    load Therapist
//=========================================================

if($_REQUEST['Action']=="load_therapist")
{

	 $str = "";
	 if($_REQUEST['name'] == "")
	 {
		 $str.="<option value = ''>Select Client </option>";
		 echo $str;
	 }
	 else
	 {
	 
			 $countryId = $_REQUEST['name'];
			if($countryId > 0)
			{
			//echo "select Id, name from cab_state where status = '1' and country_id = '".$countryId."' order by name asc" ;
				$cities = $db->select("select * from cab_contact where  therip_id = '".$countryId."'");
			}
			$arrlen = count($cities);
			$str="<option value = ''>Select Client</option>";
			if($cities)
			{
				for($i=0;$i<$arrlen;$i++)
				{		 
					  
					  $data = array($cities[$i]['id'],$cities[$i]['name'],$cities[$i]['email'], $cities[$i]['phone'],$cities[$i]['treatment_interest'],$cities[$i]['state'],$cities[$i]['city']);
					    
					  $html ="<option value ='$stateID'>$stateName</option>";
					  
					  
					 $html .= '{"sel":"'.$data.'",
					 "email"'.$data[2].'",
					"phone"'.$data[3].'",
					"treatment_interest"'.$data[4].'",
					"state"'.$data[5].'",
					"city"'.$data[6].'" 
					}'; 
				}
				echo $html; 
				
				
			}
			else
			{
				echo "<option value = ''>Not Available</option>";
			}	
	 }
	 
}
if($_REQUEST['Action']=="check_duplicate_user")
	{	
		
		$selectDel	 = $db->select("SELECT email FROM  cab_users where email='".$_REQUEST['email']."'");
		
		if($selectDel==true){
			//echo $html='<p style="color:#FF0000;">Email Already exists</p>'; 
			echo $html='Email Already exists'; 
			}else{}
	}

if($_REQUEST['Action']=="create_subscriber")
	{	
		
		$selectDel	 = $db->select("SELECT email FROM  cab_subscriber where email='".$_REQUEST['email']."'");
		
		if($selectDel==true){
			
			echo $html='You are already subscribe'; 
			}else{
				$record["email"] = $db->mySQLSafe($_REQUEST['email']);
			    $record["status"] = $db->mySQLSafe(1);
			$insert = $db->insert($glob['dbprefix']."cab_subscriber", $record);
				if($insert){
					echo $html='You are subscribed'; 
					}
				
				}
				
		
			
	}
	if($_REQUEST['Action']=="check_duplicate_user2")
	{	
		$selectDel	 = $db->select("SELECT id,email FROM  cab_users where email='".$_REQUEST['email']."'");
		
		if($selectDel==true){
			
			$query1 = "SELECT * FROM cab_todays_chat WHERE slot_id=".$db->mySQLSafe($_SESSION['slotid'])." and user_id=".$db->mySQLSafe($selectDel[0]['id']) ;
    $results1 = $db->select($query1);
	if($results1 == true ){
		
		echo $html='1'; 
		
		}else{
			
			$_SESSION['user_id']=$selectDel[0]['id'];
			
			echo $html='2'; 
			
		}
			}else{}
		
		
		
			
	}
	
	
	
	if($_REQUEST['Action']=="change_person"){
		$_SESSION['stay']['tguest']=$_REQUEST['total'];
		
		}
		if($_REQUEST['Action']=="load_booking"){
		
       $query = "SELECT id,`cab_id`, `room_id`, `user_id`, `amount_payable`,child, `totalstay`, `trooms`, `tguest`, `checkin`, `checkout`, `any_q`, `booking_status`, `status`,`datecreated` FROM ".$glob['dbprefix']."cab_booking WHERE id =".$db->mySQLSafe($_REQUEST['book_id'])." AND user_id=".$db->mySQLSafe($_SESSION['user_id']);
$results = $db->select($query);
	   if($results){
	   $html ='<h2 style="margin-left:100px;">Booking Information</h2>';
	   $html .='<div class="row manging_booking_tbl" >
      
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Hotel Name:</div>
       <div class="col-md-9">'.getHotelName($results[0]['cab_id']).'</div>
       </div>';
	   $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Hotel Address:</div>
       <div class="col-md-9">'.getHotelAddress($results[0]['cab_id']).'</div>
       </div>';
	   
	   $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Room:</div>
       <div class="col-md-9">'.getRoomName($results[0]['room_id'],$results[0]['cab_id']).'</div>
       </div>';
	   
	   $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Check In:</div>
       <div class="col-md-9">'.$results[0]['checkin'].'</div>
       </div>';
	   
	   $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Check Out:</div>
       <div class="col-md-9">'.$results[0]['checkout'].'</div>
       </div>';
	   
	   $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">No Of Guest:</div>
       <div class="col-md-9">'.$results[0]['tguest'].'</div>
       </div>';
	   $html .='<div class="row manging_booking_tbl" >
      
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">No Of Rooms:</div>
       <div class="col-md-9">'.$results[0]['trooms'].'</div>
       </div>';
	   $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Children:</div>
       <div class="col-md-9">'.$results[0]['child'].'</div>
       </div>';
	   
	   
	    $html .='<div class="row manging_booking_tbl" >
       
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Amount Payable:</div>
       <div class="col-md-9">Rs. '.$results[0]['amount_payable'].'</div>
       </div>';
	   if($results[0]['booking_status']=='1'){
				$selected='Pending';
				}
            if($results[0]['booking_status']=='2'){
				$selected='In Progress';
				}
            if($results[0]['booking_status']=='3'){
				$selected='Confirmed';
				}
	    $html .='<div class="row manging_booking_tbl" >
      
       <div class="col-md-3 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">Booking Status:</div>
       <div class="col-md-9">'.$selected.'</div>
       </div>';
	   
	   
	   }else{
		   $html .='<div class="row manging_booking_tbl" >
      
       <div class="col-md-12 manging_booking_tbl_bor" style="font-size:14px; font-weight:bold;">No Result Found</div>
       
       </div>';
		   
		   
		   }
		   
		   echo $html;
		
		}
		
		
		
		
	if($_REQUEST['Action']=="change_room"){
		$_SESSION['stay']['trooms']=$_REQUEST['total'];
		
		}	
	
	if($_REQUEST['Action']=="change_status"){
	
	
		$record['booking_status']=$db->mySQLSafe($_REQUEST['name']);
		$booking_id=$_REQUEST['booking_id'];
		$where="id =".$db->mySQLSafe($booking_id);
		$update	= $db->update("cab_booking", $record, $where);
	
	
	
	if($update == TRUE){
	$selectDel	 = $db->select("SELECT id,user_id,booking_status,cab_id,checkin,checkout FROM `cab_booking` WHERE `id`=".$db->mySQLSafe($_REQUEST['name']));
		
		if($selectDel==true){ 
		
		     //*******************email with booking detail****************
			     $email = getUserEmail($selectDel[0]['user_id']);
				
				 $name=getUserName($selectDel[0]['user_id']);
			     
				 
				
				 $message3  = "<img src='".$glob['storeURL']."images/logo3.png' alt='' /><br /><br />";
				 $message3 .= "Dear ".$_POST['first_name']." ".$_POST['last_name']." thank you for your hotel booking with us<br /><br />";
				 $message3 .= "Your Booking Detail. <br/><br />";
				 $message3 .= "Booking ID: ".$booking_id ."  <br/><br />";
				 $message3 .= "Hotel: ".getHotelName($selectDel[0]['cab_id'])."  <br/><br />";
				 $message3 .= "Address:".getHotelAddress($selectDel[0]['cab_id'])." <br/><br />";
				 
				 $message3 .= "Check In:".$selectDel[0]['checkin']." <br/><br />";
				 $message3 .= "Check Out:".$selectDel[0]['checkout']." <br/><br />";
				 
				 if($selectDel[0]['booking_status'] == 1){
				 $message3 .= "Your Booking status is pending. <br/><br />";
				 
				 }else if($selectDel[0]['booking_status'] == 2){
					 $message3 .= "Your Booking status in progress. <br/><br />";
					 }else if($selectDel[0]['booking_status'] == 2){
						 
						$message3 .= "Congratulations! Your Booking is confirmed now. <br/><br />"; 
						 }
				 
				 
				 $message3 .= "<br/>Kind regards,<br/><br/>hotel.com";
				 $headers3  = 'MIME-Version: 1.0' . "\r\n";
				 $headers3 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				 $headers3 .= 'From:'.$config['masterEmail'] . "\n";
	             $mail = @mail($email,"Hotel Reservation status at hotel.com",$message3,$headers3);	
		
			 
			if($selectDel[0]['booking_status']=='1'){
				$selected1='selected';
				}else{
					$selected1;
					}
            if($selectDel[0]['booking_status']=='2'){
				$selected2='selected';
				}else{
					$selected2;
					}
            if($selectDel[0]['booking_status']=='3'){
				$selected3='selected';
				}else{
					$selected3;
					}

			$html='
			          <option '.$selected1.'  value="1">Pending</option>
                      <option '.$selected2.' value="2">Inprogress</option>
                      <option '.$selected2.' value="3">confirmed</option>';
    		echo $html;
    
		}
	   }
	}

  
   if(isset($_POST['action']) && $_POST['action']=='getPreviousjourneyByPhoneNo')	{
//ob_start();
	$phoneNo =   $_POST['phoneNo'];
	$data ='';
	if($phoneNo <> ''){
	
		$sqlQuery =" SELECT * FROM cab_order_sum WHERE passenger_phone ='".$phoneNo."' AND deleted =0" ;
		$queryExi =mysql_query($sqlQuery);
		
		$data  .= '<tr bgcolor="#333333" align="center" style="border-right:1px solid white; color:#fff;">';
			$data  .= '<td width="5%" class="style" colspan="9"><h3>Previous Journey Detail</h3></td>';
		$data  .= '</tr>';	
		
		$data  .= '<table width="100%" cellpadding="4">';
		$data  .= '<tbody>';
				$data  .= '<tr bgcolor="#333333" align="center" style="border-right:1px solid white; color:#fff;">';
					$data  .= '<td width="5%" class="style">Order No</td>';
					$data  .= '<td width="8%" class="style">Phone No</td>';
					$data  .= '<td width="4%" class="style">Amount</td>';
					$data  .= '<td width="4%" class="style">payment</td>';
					$data  .= '<td width="11%" class="style">pickup</td>';
					$data  .= '<td width="11%" class="style">Drop</td>';
					$data  .= '<td width="6%" class="style">Pickup Date</td>';
					$data  .= '<td width="6%" class="style">Pickup Time</td>';
					$data  .= '<td width="5%" class="style"> ';
						$data  .= 'Persons';
					$data  .= '</td>';
					$data  .= '<td width="5%" class="style">';
					$data  .= 'Luggage';
					$data  .= '</td>';
					$data  .= '<td width="6%" class="style">Vehicle</td>';
					$data  .= '<td width="6%" class="style">Driver #</td>';
						
					$data  .= '<td width="7%" class="style">Status</td>';
				$data  .= '</tr>';
				
			$data  .= '</tbody>';
			$data  .= '</table>';
		$data  .= '<table width="100%" cellpadding="4">';
    	 $data  .= '<tbody>';
			
		
			if(mysql_num_rows($queryExi) > 0){
				$counter  =1 ;
				while($row  = mysql_fetch_assoc($queryExi)){
					if($counter%2==0){
					  	$bg = '#EAEAEA';
					}else{
					 	$bg = '';	
					}
					
					if(isset($row['payment_type']) && $row['payment_type'] == 1){
						$p_img ='<img height="12" src="'.$glob['storeURL'].'/images/glyphicons_267_credit_card.png" />';
					}else
					if(isset($row['payment_type']) && $row['payment_type'] == 2){
						$p_img ='<img height="12" src="'.$glob['storeURL'].'/images/glyphicons_228_gbp.png" />';
					}else
					if(isset($row['payment_type']) && $row['payment_type'] == 3){ 
						$p_img ='<img height="12" src="'.$glob['storeURL'].'/images/images.png" />';
					}else
					if(isset($row['payment_type']) && $row['payment_type'] == 4){ 
						$p_img ='<img  src="'.$glob['storeURL'].'/images/account_user.png" width="20" height="20"  title="user account"/>';
					}
					
					$data  .= '<tr bgcolor="'.$bg.'" align="center" style="border-right:1px solid white; color:#000;">';
						$data  .= '<td width="5%" class="style">'.$row['cart_order_id'].'</td>';
						$data  .= '<td width="5%" class="style">'.$row['passenger_phone'].'</td>';
						$data  .= '<td width="4%" class="style">&pound;'. $row['ordertotal'].'</td>';
						$data  .= '<td width="4%" class="style"> '.$p_img.'</td>';
							$data  .= '<td width="11%" class="style"> '.$row['postfrom'].'</td>';
							$data  .= '<td width="11%" class="style"> '.$row['postto'].'</td>';
						$data  .= '<td width="6%" class="style"> '.date('d-m-Y',strtotime($row['pick_date'])).'</td>';
						$data  .= '<td width="6%" class="style"> '.$row['pick_time'].'</td>';
						$data  .= '<td width="5%" class="style"> ';
						$data  .= ' '.$row['how_many'].''; 
						$data  .= '</td>';
							$data  .= '<td width="5%" class="style">';
						$data  .= ' '.$row['luggage'].'';
						$data  .= '</td>';
						$data  .= '<td width="6%" class="style"> '.getVehicle($row['how_many'],$row['luggage']).'</td>';
						$data  .= '<td width="6%" class="style"> '.$row['driver_no'].'</td>';
						$data  .= '<td width="7%" class="style"> '.getSts($row['booking_status']).'</td>';
					$data  .= ' </tr>';
				$counter++;
				}
			}else
			{
				$data  .= '<tr bgcolor="#fff" align="center" style="border-right:1px solid white; color:#000;">';
					$data  .= '<td width="5%" class="style" colspan="9">Not Found any booking against this Phone No.</td>';
				$data  .= '</tr>';		
			}
	    $data  .= '</tbody>';
    $data  .= '</table>';		
    }
  
   echo $data;	
	
}

  
  function getSts($id){
	//echo $id;
   if($id==0){
	 $type = '
		<span  style="background-color: #009ee5;
		border-radius: 8px;
		color: #fff;
		font-family: arial;
		font-size: 12px;
		font-weight: bold;
		padding: 5px 10px;
		display:inline-block;
		width:82px"  > Pending</span>';	
	} else if($id==1){  
		 $type = '<span style="background-color: #1c4c89;
		border-radius: 8px;
		color: #fff;
		font-family: arial;
		font-size: 12px;
		font-weight: bold;
		padding: 5px 10px;
		display:inline-block;
		width:82px" >Confirmed</span>';	
	} else if($id==2){
	 $type = '<span style="background-color: #126d93;
		border-radius: 8px;
		color: #fff;
		font-family: arial;
		font-size: 12px;
		font-weight: bold;
		padding: 5px 10px;
		display:inline-block;
		width:82px">Cancel</span>';		
	}
	 else if($id==3){
	 $type = '
		<span  style="background-color: #3d4144;
		border-radius: 8px;
		color: #fff;
		font-family: arial;
		font-size: 12px;
		font-weight: bold;
		padding: 5px 10px;
		display:inline-block;
		width:82px"  >Completed</span>';		
	}else if($id==4){
	 $type = '
		<span  style="background-color: #3d4144;
		border-radius: 8px;
		color: #fff;
		font-family: arial;
		font-size: 12px;
		font-weight: bold;
		padding: 5px 10px;
		display:inline-block;
		width:82px"  >Hand back</span>';		
	}
  return $type;

}
  
  
  
  
  








// khan
 if(isset($_POST['action']) && $_POST['action']=='get_previous_booking')	{
	$refernceId =   $_POST['refernceId'];
	$sqlQuery = "SELECT COS .* , CU.first_name AS booking_username,
	CU.email AS booking_email,
	CU.mobile AS booking_phoneno,
	CU.phone_alt AS phone_alt   
	FROM `cab_order_sum` AS COS
	INNER JOIN cab_users AS CU ON COS.passenger_id = CU.id
	WHERE  COS.cart_order_id='".$refernceId."'";
	
	 $queryExi =mysql_query($sqlQuery);
	 $previousBooking = array();
	 $data  = 0;
	 if(mysql_num_rows($queryExi) > 0){
		$row = mysql_fetch_assoc($queryExi);
		
		if($row['postfrom'] !=''){ 
			$previousBooking['postfrom'] =  'from_id_onload'.'='.$row['postfrom']; //0
		}
		if($row['postto'] !=''){ 
			$previousBooking['postto']=     'to_id_onload'.'='.$row['postto']; // 1
		}
		if($row['payment_type'] !=''){ 
			$previousBooking['payment_type'] = 'payment_type'.'='. $row['payment_type']; // 2
		}
		if($row['ordertotal'] !=''){ 
			$previousBooking['ordertotal']  =  'booking_price'.'='.$row['ordertotal']; //3
		}
		if($row['how_many'] !=''){ 
			$previousBooking['how_many'] =  'how_many'.'='.$row['how_many']; // 4
		}
		if($row['luggage'] !=''){ 
			$previousBooking['luggage'] =  'luggage'.'='.$row['luggage']; // 5
		}
		if($row['flight_no'] !=''){ 
			$previousBooking['flight_no']=  'flight_no'.'='.$row['flight_no']; // 6
		}
		if($row['is_child_trolley'] !=''){ 
			$previousBooking['is_child_trolley']=  'is_child_trolley'.'='.$row['is_child_trolley']; // 7
		}
		if($row['is_disable_trolley'] !=''){ 
			$previousBooking['is_disable_trolley']=  'is_disable_trolley'.'='.$row['is_disable_trolley']; // 8
		}
		if($row['pick_date'] !=''){ 
			$previousBooking['pick_date']=  'pickup_date'.'='.$row['pick_date']; // 9
		}
		if($row['pick_time'] !=''){ 
			$previousBooking['pick_time']=  'timepicker_start'.'='.$row['pick_time']; // 10
		}
		if($row['extra_comments'] !=''){ 
			$previousBooking['extra_information'] =  'extar_notes'.'='.$row['extra_comments']; // 11
		}
		
		if($row['booking_username'] !=''){  //booking_phoneno
			$previousBooking['booking_username']=  'booking_username'.'='.$row['booking_username']; // 13
		}
		if($row['booking_email'] !=''){ 
			$previousBooking['booking_email']= 'booking_email'.'='. $row['booking_email'];  // 14
		}
		if($row['booking_phoneno'] !=''){ 
			$previousBooking['booking_phoneno']=  'booking_phoneno'.'='.$row['booking_phoneno'];  // 15
		}
		if($row['vehicle'] !=''){ 
			$previousBooking['vehicle']=  'vehicle_select'.'='.$row['vehicle']; // 16
		}
		if($row['driver_no'] !=''){ 
			$previousBooking['driver_no']=  'driver_no'.'='.$row['driver_no']; // 16
		}
		/****New Added***/
		if($row['commission'] !=''){ 
			$previousBooking['commission'] =  'booking_commission'.'='.$row['commission']; // 16
		}
		if($row['waiting_time'] !=''){ 
			$previousBooking['waiting_time'] =  'waiting_time'.'='.$row['waiting_time']; // 16
		}
		if($row['parking_price'] !=''){ 
			$previousBooking['parking_price'] =  'booking_parking'.'='.$row['parking_price']; // 16
		}
		if($row['phone_alt'] !=''){ 
			$previousBooking['phone_alt'] =  'booking_phoneno_alt'.'='.$row['phone_alt']; // 16
		}
		if($row['is_received'] !=''){ 
			$previousBooking['is_received'] =  'booking_paymnet_received'.'='.$row['is_received']; // 16
		}
		
		
		
		
		/****New Added***/
		
		
		
		$strPreviousBooking  = implode("|",$previousBooking);
		$data =  $strPreviousBooking;
	 }
		echo $data; 
	 
   }
	
// calculate price	
 if(isset($_POST['action']) && $_POST['action']=='calculate_price')	{
  
    $pick_date = date('Y-m-d', strtotime($_POST['pick_date']));
	$pick_time = $_POST['pick_time'];
	$total_distance = $_POST['total_distance'];
	$total_duration = $_POST['total_duration'];
	$how_many = $_POST['how_many'];
	$luggage = $_POST['luggage'];
	
	$from_id = $_POST['from_id']; // source 
	$to_id = $_POST['to_id'];   // destination
	// section of first part of post code
	$aFrom_id = explode(" ",$_POST['from_postcode']);
	$aTo_id = explode(" ",$_POST['to_postcode']);
	$postcode1  =  $aFrom_id[0];  // first part of from post code
	$postcode2  =  $aTo_id[0];   // // first part of to post code
	// section of first part of post code
	$type  = 0; // type 
	//$vehical_type = getVehicleType($how_many,$luggage);
	$vehical_type = $_POST['vehicle_type'];
	
	
	
	
  $query= "SELECT cab_default_price.*
FROM cab_default_price ,cab_company
WHERE cab_company.status=1 and cab_company.featured=1 and cab_default_price.company_id = ".$_SESSION['company_id']." and company_id NOT IN (SELECT company_id FROM cab_freez WHERE `end_date` = '".$pick_date."' AND '".$pick_time."' between start_time and end_time) and all_freez =0 GROUP BY cab_default_price.company_id";

   $query_listing =mysql_query($query); 
   $ii = 0;
	
	while($row=mysql_fetch_array($query_listing)) {
	$res = getASAP($postcode1,$row['company_id'],$pick_time,$pick_date,$vehical_type);
		if($res == 1){	
			$price_return = getPricesAll($case,$row['company_id'],$postcode1,$postcode2,$postcode3,$total_distance,$how_many,$luggage);
		 
		//for retrurn case
		if($pick_date>0 and $pick_time >0 and $_SESSION['return_date']>0 and $_SESSION['return_time']>0){	
			$picktime = $pick_time.':'.$_SESSION['pick_hr'];	
			$returntime = $_SESSION['return_time'].':'.$_SESSION['return_hr'];	
			$price_time = getPricesTime($price_return,$row['company_id'],$pick_date,$picktime,$_SESSION['return_date'],$returntime);	
		}
			
			if($type==1){
			
				$cab_value  = getExecId($row['company_id']);
				$cab_charge_type  = getExecPayType($row['company_id']);
				$price = $price_return+$price_time;
				
				if($cab_charge_type==0){
					$exe_price = $price+($price*($cab_value/100));
				} else{
					$exe_price = $price*$cab_value;
				}
				
					if($cab_value>0){
						$price = $price_time+$exe_price;
						$price = round($price,2);
						
						$surcharge = getSurcharge(trim($from_id),trim($to_id),$row['company_id']); 
						$price = getPriceIncreaseDecreaseByCompany($row['company_id'],$pick_date,$pick_time,$price)+$surcharge;
						$results['full'][$i]['company_id']    			=  $row['company_id'];
						$results['full'][$i]['company_name'] 			=  getCompanyName_new($row['company_id']);
						$results['full'][$i]['price'] 					=  $price;
						$results['full'][$i]['postfrom'] 				=  $postcode1;
						$results['full'][$i]['postto'] 					=  $postcode2;
						$results['full'][$i]['total_distance'] 			=  $total_distance;
						$results['full'][$i]['total_duration'] 			=  $total_duration;
						$i++;
					} 
			
			} else {
				
				$price = $price_return+$price_time;
				$price = round($price,2);
				$surcharge = getSurcharge(trim($from_id),trim($to_id),$row['company_id']); 
				$price = getPriceIncreaseDecreaseByCompany($row['company_id'],$pick_date,$pick_time,$price)+$surcharge;
				$results['full'][$i]['company_id']    			=  $row['company_id'];
				$results['full'][$i]['company_name'] 			=  getCompanyName_new($row['company_id']);
				$results['full'][$i]['price'] 					=  $price;
				$results['full'][$i]['postfrom'] 				=  $postcode1;
				$results['full'][$i]['postto'] 					=  $postcode2;
				$results['full'][$i]['total_distance'] 			=  $total_distance;
				$results['full'][$i]['total_duration'] 			=  $total_duration;
            $i++;
			
			}
		}
   }
   
    $data = $results['full'];
	$total =  count($results['full']);
	$list = array_sort($data, 'price', SORT_ASC);
	$list = array_values($list);
	$least =  reset($list);
	echo $least_price 		= $least['price'];
    //$company_id 		= $least['company_id'];
	//$_SESSION['participant']=$list;
	//$list = super_unique($list,'company_id');
	//$total =  sizeof($list);
	
	 // super unique
	function super_unique($array,$key){
		$temp_array = array();
		foreach ($array as &$v) {
			if (!isset($temp_array[$v[$key]]))
				$temp_array[$v[$key]] =& $v;
		}
		$array = array_values($temp_array);
		return $array;
	} 
	  
  
   
 }
	
	
	
	
	
	
	
  //new functions import from root include / function.inc.php
  /********************Utility Functions*********************************/
  

	 
     function getASAP($postcode,$company_id,$pick_time,$pick_date,$vehicle_type){
	$db = new db();
	$date=explode('/',$pick_date);  // mm 0/dd 1 /yy 2 convert to 
	$pick_date=$date[2].'-'.$date[0].'-'.$date[1]; // yy-mm-dd
$query="SELECT id, company_id, post_code, job_p_hr".$vehicle_type.", lead_time".$vehicle_type." FROM cab_asap WHERE company_id='".$company_id."' AND post_code='".$postcode."' AND status=1";
	$result = $db->select($query);
	if($result== TRUE){
            $job="job_p_hr".$vehicle_type;  
			$lead="lead_time".$vehicle_type;
            $job_p_hr = $result[0][$job];
            $lead_time = $result[0][$lead];
		    $time_to_work	= addMinuitsTo24Hours($pick_time,$lead_time);

	$where =' company_id='.$db->mySQLSafe($company_id).' and pick_date='.$db->mySQLSafe($pick_date). ' 
	and '.$db->mySQLSafe($pick_time).' BETWEEN pick_time AND  '.$db->mySQLSafe($time_to_work).' AND vehicle='.$db->mySQLSafe($vehicle_type);
	$query2 = 'SELECT COUNT(cart_order_id) as total FROM `cab_order_sum` where '.$where;
	$resultData = $db->select($query2);
	if($resultData == TRUE){
		if($resultData[0]['total'] >= $job_p_hr){
		//can not do job
			return '2';
		}else {
		//can  do job	  
			return '1';
		}
	}else{
		//can  do job
			return '1';
		}
}else{
	//can do job
	return '1';
	}
}
	 
	// get all prices 
	 function getPricesAll($case,$company_id,$postcode1,$postcode2,$postcode3,$miles,$passenger,$suitcas){
		global $db,$glob;
		
		$pickup_fee = $db->select("SELECT pickup_fee FROM ".$glob['dbprefix']."cab_default_price WHERE company_id = ".$db->mySQLSafe($company_id)." LIMIT 1");
		$pickup_fee = $pickup_fee[0]['pickup_fee'];
		$max_mile =  getMaxMile($company_id);
		
		
		$cab_default_price = $db->select("SELECT inc_miles FROM ".$glob['dbprefix']."cab_default_price 
											WHERE company_id=".$company_id ." LIMIT 1");
											
		$inc_miles = $cab_default_price[0]['inc_miles'];
		
		$exrea_miles=$db->select("SELECT SUM(milage) as ext_miles FROM ".$glob['dbprefix']."cab_ext_mil_cost 
		WHERE company_id=".$company_id);
		$total_exta_miles= $miles-($exrea_miles[0]['ext_miles']+$inc_miles);
		
		
		if($inc_miles<$miles){
		
		$cost = 0;
		$miles = $miles - $inc_miles;
		
		$price_milage_price = $db->select("SELECT milage,cost_per_mile FROM ".$glob['dbprefix']."cab_ext_mil_cost 
		WHERE company_id=".$company_id);
		
		for($i=0;$i<count($price_milage_price);$i++){
		
		$miles1 = $miles-$price_milage_price[$i]['milage'];	
		if($miles1>0){
		$miles = $miles1;
		$cost+= $price_milage_price[$i]['milage']*$price_milage_price[$i]['cost_per_mile'];
		}else {
		
		$cost+= $miles*$price_milage_price[$i]['cost_per_mile'];
		break;
			}
			
			if($miles == $total_exta_miles  ){
				
				$cost+=$total_exta_miles*$price_milage_price[$i]['cost_per_mile'];
				
				}
			}
		$vehical_rate = getVehicleType($passenger,$suitcas);
		$vehicle_rate = 'vehicle_rate'.$vehical_rate;
		$rate_type = 'rate_type'.$vehical_rate;
		
		$getVehicalRate = $db->select("select $vehicle_rate , $rate_type from cab_vehicle_price where company_id = ".$db->mySQLSafe($company_id).' LIMIT 0,1');
		//print_r($getVehicalRate);
		if($case!=1){
		if($getVehicalRate[0]["$rate_type"]==0){
			//echo 'reached';
			if($getVehicalRate[0]["$vehicle_rate"]==0){
			$allcost = ($cost+$pickup_fee);
			} else{
			$getVehicalRate[0]["$vehicle_rate"]/100;
			//echo '<br />';	
			$allcost = ($cost+$pickup_fee)+(($cost+$pickup_fee)*$getVehicalRate[0]["$vehicle_rate"]/100);	
			}
		
		} else {
			
		
			if($getVehicalRate[0]["$vehicle_rate"]==0){
			$allcost = ($cost+$pickup_fee);
			} else{
			$allcost = ($cost+$pickup_fee+$getVehicalRate[0]["$vehicle_rate"]);	
				}
		}
		}else{
		
		$allcost =  $pickup_fee+$cost;
		
		}
		}
		else{
		
		
		//echo 'start else case if distance is less than exclusive miles';	
		$vehical_rate = getVehicleType($passenger,$suitcas);
		$vehicle_rate = 'vehicle_rate'.$vehical_rate;
		$rate_type = 'rate_type'.$vehical_rate;
		$getVehicalRate = $db->select("select $vehicle_rate , $rate_type from cab_vehicle_price where company_id = ".$db->mySQLSafe($company_id).' LIMIT 0,1');
		//print_r($getVehicalRate);
		if($case!=1){
		
		if($getVehicalRate[0]["$rate_type"]==0){
		     
			if($getVehicalRate[0]["$vehicle_rate"]==0){
				$allcost = ($cost+$pickup_fee);
			} else{
				$allcost = ($cost+$pickup_fee)+(($cost+$pickup_fee)*($getVehicalRate[0]["$vehicle_rate"]/100));	
			}
		} else {
			if($getVehicalRate[0]["$vehicle_rate"]==0){
				$allcost = ($cost+$pickup_fee);
			} else{
				$allcost = ($cost+$pickup_fee)+(($cost+$pickup_fee)*($getVehicalRate[0]["$vehicle_rate"]/100));	
			}
		}
		}else{
		$allcost =  $pickup_fee+$cost;
		
		}
		/* end else case if distance is less than exclusive miles*/	
		
			
		//echo $allcost =  $pickup_fee;
		}
		
		return round($allcost,2);


	 }	
	 
	 
	 
	  // price time
	  function getPricesTime($price_return,$company_id,$pickDate,$pickTime,$returnDate,$returnTime){
		global $db,$glob;
		
	$time_fee = $db->select("SELECT * FROM ".$glob['dbprefix']."cab_other_settings WHERE company_id = ".$db->mySQLSafe($company_id)." and pickup_time!='' and charge and other_charges LIMIT 1");
	
	$pickTime = Convertdateformat($pickDate).' '.$pickTime.'00';
	$returnTime = Convertdateformat($returnDate).' '.$returnTime.'00';
	$returnTime = strtotime($returnTime);
	$pickTime = strtotime($pickTime);
	$minutes = round(abs($returnTime - $pickTime) / 60,2);
	$cab_time = $time_fee[0]['pickup_time'];
		if($minutes<=$cab_time){
			return ($price_return*($time_fee[0]['charge']/100));
		} else {
		return ($price_return*($time_fee[0]['other_charges']/100));	
		}
		
	
	
}
	  
	   
	 
	 
	 
	function getExecId($id){
		$sql = "select exec_car from cab_other_settings where exec_car!='' and company_id ='".$id."'";
		$rs = mysql_query($sql); print mysql_error();
		if($row=mysql_fetch_array($rs))
		return $row['exec_car'];
	}

	function getExecPayType($id){
		$sql = "select executive_rate_option from cab_other_settings where exec_car!='' and company_id ='".$id."'";
		$rs = mysql_query($sql); print mysql_error();
		if($row=mysql_fetch_array($rs))
		return $row['executive_rate_option'];
	}
	 
	function getSurcharge($source,$destination,$company_id){
	$db = new db();
	$source_post_code=getPostCodeNew2($source);
	$des_post_code=getPostCodeNew2($destination);

$surchargeS='';
 $query9="SELECT printable_name,id FROM  cab_stations WHERE  image_name='".$source_post_code."'";
  $station = $db->select($query9);

if($station == TRUE){


 $query1="SELECT cab_cab_stations.surcharge FROM `cab_cab_stations`  WHERE  cab_cab_stations.status =1 AND company_id ='".$company_id."' 
 AND station='".$station['0']['id']."'" ;

$customerData = $db->select($query1);
if($customerData == true){
	
	$surcharge1=$customerData[0]['surcharge'];
	}
}
 $query10="SELECT printable_name,id FROM  cab_airport WHERE  image_name='".$source_post_code."'";

  $airport = $db->select($query10);

if($airport == TRUE){
 $query2="SELECT cab_cab_airport.surcharge FROM cab_cab_airport  WHERE  cab_cab_airport.status =1  AND company_id ='".$company_id."' AND airport='".$airport['0']['id']."'" ;
$result = $db->select($query2);

if($result == true){
	
	$surcharge2=$result[0]['surcharge'];
	}
	
}
 $surchargeS=$surcharge1+$surcharge2;



$query9="SELECT printable_name,id FROM  cab_stations WHERE  image_name='".$des_post_code."'";
$station = $db->select($query9);

if($station == TRUE){
$query1="SELECT cab_cab_stations.surcharge FROM `cab_cab_stations`  WHERE  cab_cab_stations.status =1 AND company_id ='".$company_id."' 
 AND station='".$station['0']['id']."'" ;

$customerData = $db->select($query1);
if($customerData == true){
	
	$surcharge3=$customerData[0]['surcharge'];
	}
}
 $query10="SELECT printable_name,id FROM  cab_airport WHERE  image_name='".$des_post_code."'"; 

  $airport = $db->select($query10);

if($airport == TRUE){
  $query2="SELECT cab_cab_airport.surcharge FROM cab_cab_airport  WHERE  cab_cab_airport.status =1  AND company_id ='".$company_id."' AND airport='".$airport['0']['id']."'" ;


$result = $db->select($query2);

if($result == true){
	
	$surcharge4=$result[0]['surcharge'];
	}
	
}
$surchargeS=$surcharge3+$surcharge4;


return $finalSurcharge=round($surchargeS+$surchargeD);

	
	} 
	 
	
	function getPriceIncreaseDecreaseByCompany($company_id,$pickup_date,$pickup_time,$price){            
			   global $db,$glob;
			   
			   $date=explode('/',$pickup_date);
			   $pickup_date=$date[2].'-'.$date[0].'-'.$date[1];
			   
			$query1 = "SELECT id, company_id, variation_type, value, incdec FROM cab_price_varient WHERE ".$db->mySQLSafe($pickup_date)."  BETWEEN start_date AND end_date AND  ".$db->mySQLSafe($pickup_time)." BETWEEN start_time AND end_time AND company_id=".$db->mySQLSafe($company_id)." AND status= 1 limit 0,1";
			
			
			$data = $db->select($query1);
			
			  if($data == TRUE)
				{
				  
				  
					if($data[0]['variation_type'] == 0)
					  {
						$amount=($price*$data[0]['value'])/100;
					  }else
					  {
						$amount=$data[0]['value'];
					  } 
					  
					 
					  
					  
					if($data[0]['incdec'] == 0)
					  {
						 
						return $fare=round($price-$amount);
						 
					  
					  }else
					  {
						return $fare=round($price+$amount);
					  }
			   }else
			   {
				  $query="SELECT `company_id`, `price_variation`, `price_varient_type` FROM `cab_default_price` WHERE `company_id`=".$db->mySQLSafe($company_id);
				  $result = $db->select($query);
						
					  
					if($result[0]['price_variation'] !=0)
					  {
						$amount=($price*$result[0]['price_variation'])/100;
						if($result[0]['price_varient_type'] == 0)
						  {
								return $fare=round($price-$amount);
						  }else
						  {
								return $fare=round($price+$amount);
						  }
			
					  }else
					  {
						return $fare=round($price);
					  }
					 
					 
			  }
	}
	
	function getCompanyName_new($companyId) {
		global $db,$glob;
		$cmpName = $db->select("select company_name from cab_company where Id = ".$db->mySQLSafe($companyId));
		return ($cmpName == TRUE) ? ucfirst($cmpName[0]['company_name']) : false;
	}	 
	 	
	function Convertdateformat($date){
		return $date = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
	}
 
	 
	function getVehicleType($passenger,$suitcas){
				$total = $passenger+$suitcas;
				if($total <= 6){
					return 1;
				}else if($total == 7){
					return 2;				  
				}else if($total > 7 and $total <=10){
					return 3;
				}else if($total > 7 and $total <=13){
					return 4;
				}else{
					return 4;
				}
		}

function getMaxMile($companyId) {
		global $db,$glob;
		$cmpName = $db->select("select milage , cost_per_mile from cab_ext_mil_cost where company_id = ".$db->mySQLSafe($companyId).' ORDER by id DESC LIMIT 0,1');
		return ($cmpName == TRUE) ? $cmpName[0]['cost_per_mile'] : false;
	}


function getPostCodeNew2($addrss){
		$prepAddr = str_replace(' ','+',$addrss);
		$geocode=file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false');
		$output= json_decode($geocode);
		/*echo '<pre>';
		print_r($output);
		*/
  
        $size= sizeof($output->results[0]->address_components)-1;
		$post_code = $output->results[0]->address_components[$size]->long_name;
		//$finelpost = preg_replace('/\s+/', '', $post_code);
		//$finelpost = explode(' ', $post_code);
		return $post_code;
	}			 
	 
	
	    // khan
	    if(isset($_REQUEST['action']) && $_REQUEST['action']=='get_coordinates'){
		   $coordinates ='London,Uk'.'|'.'51.5073509'.'|'.'-0.1277583';
		   if($_REQUEST['action'] <> ""){
				$location =  urlencode($_POST['location']);
				$result = array();
				$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$location."&language=en&sensor=false";
				$data = @file_get_contents($url);
				$result = json_decode($data, true);
				$inforString  = "";
				$formattedAddress = $result['results'][0]['formatted_address'];
				$lattitude = $result['results'][0]['geometry']['location']['lat'];
				$longitude = $result['results'][0]['geometry']['location']['lng'];
				if($formattedAddress <> ""){
				 $inforString .= $formattedAddress.'|';	
				}
				if($lattitude <> ""){
				  $inforString .= $lattitude.'|';	
				}
				if($longitude <> ""){
				  $inforString .= $longitude.'|';	
				}
				$coordinates =  $inforString;
		   }
		   
		   echo $coordinates;
		   
	}
	// save them for further use
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='save_coordinates'){
		$result  = 0;
		$record["driver_no"] = $db->mySQLSafe($_POST['driver_no']);
		$record["color"] = $db->mySQLSafe($_POST['fill_color']);
		$record["lattitude"] = $db->mySQLSafe($_POST['lattitude']);
		$record["longitude"] = $db->mySQLSafe($_POST['longitude']);
		$record["location"] = $db->mySQLSafe($_POST['formated_address']); 
		$record["company_id"] = $db->mySQLSafe($_SESSION['company_id']);
		$insert = $db->insert($glob['dbprefix']."cab_plotting", $record);
		if($insert){
			$result = true;	
		}	
		echo $result;
	}
	
	// update them
	if(isset($_REQUEST['action']) && $_REQUEST['action']=='update_coordinates'){
		$result  = 0;
		$record['driver_no']=$db->mySQLSafe($_POST['driver_no']);
		$record["color"] = $db->mySQLSafe($_POST['fill_color']);
		$record["status"] = $db->mySQLSafe($_POST['status']);
		$where="id =".$db->mySQLSafe($_POST['hidden_id']);
		$update	= $db->update("cab_plotting", $record, $where);

		if($update){
			$result = true;	  // hidden_id
		}	
		echo $result;
	}
	
	//update_coordinates
	
	
 /********************Utility Functions*********************************/
?>
      
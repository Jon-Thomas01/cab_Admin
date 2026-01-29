<?php
include("includes/includes.inc.php");

//echo date('Y-m-d H:i:s');


 $selectDel	 = $db->select("SELECT driver_no FROM  cab_fleet where company_id=".$db->mySQLSafe($_SESSION['company_id']));
		$driver_15=array();
	$driver_30=array();
	$driver_45=array();
	$driver_60=array();
	$driver15='';
	$driver30='';
	$driver45='';
	$driver60='';
		
for($i=0; $i<sizeof($selectDel); $i++){

 $driver_no = $selectDel[$i]['driver_no'];
 

$pick=explode(' ',date('Y-m-d H:i:s'));
$pick_date=$pick[0];
$pick_time=$pick[1];
$where ='driver_no ='.$db->mySQLSafe($driver_no). ' and company_id='.$db->mySQLSafe($_SESSION['company_id']).' and pick_date='.$db->mySQLSafe($pick_date). ' 
and pick_time < '.$db->mySQLSafe($pick_time).' LIMIT 0,1';


  $query = 'SELECT * FROM `cab_order_sum` where '.$where;

            $daata = $db->select($query); 
    
	if($daata == TRUE){
	$working_time =addTime($daata[0]['pick_time'],$daata[0]['total_duration']);			
	
	
	if( strtotime($daata[0]['pick_time']) > strtotime($working_time)){
		
		//echo '1';
		
		}else{
			$a++;
			$driver[]=$driver_no;
			
			
			
			
			
			}		
	}else{
		 //----------------------------------15 Min-------------------------------------------
	
	 $query="SELECT driver_id FROM `cab_drivers_attendence` where TIMEDIFF(NOW(),`entry_date`) < '00:16:00' and company_id=".$db->mySQLSafe($_SESSION['company_id'])." AND driver_id=".$db->mySQLSafe($driver_no);
	$select15Min	 = $db->select($query); 
	for($f=0; $f<sizeof($select15Min); $f++){
		
		$driver_15[]=$select15Min[$f]['driver_id'];
		$driver15++;
		}
	//------------------------------------30 min-----------------------------------------------	
	$query="SELECT driver_id FROM `cab_drivers_attendence` where TIMEDIFF(NOW(),`entry_date`) Between '00:16:00' and '00:31:00' and company_id=".$db->mySQLSafe($_SESSION['company_id'])." AND driver_id=".$db->mySQLSafe($driver_no);
	$select30Min	 = $db->select($query); 
	for($j=0; $j<sizeof($select30Min); $j++){
		
		$driver_30[]=$select30Min[$j]['driver_id'];
		$driver30++;
		}
		//------------------------------------48 min-----------------------------------------------	
	$query="SELECT driver_id FROM `cab_drivers_attendence` where TIMEDIFF(NOW(),`entry_date`) Between '00:31:00' and '00:46:00' and company_id=".$db->mySQLSafe($_SESSION['company_id'])." AND driver_id=".$db->mySQLSafe($driver_no);
	$select45Min	 = $db->select($query); 
	for($j=0; $j<sizeof($select45Min); $j++){
		
		$driver_45[]=$select45Min[$j]['driver_id'];
		$driver45++;
		}
	//------------------------------------60 min-----------------------------------------------	

$query="SELECT driver_id FROM `cab_drivers_attendence` where TIMEDIFF(NOW(),`entry_date`) Between '00:46:00' and '00:59:59' and company_id=".$db->mySQLSafe($_SESSION['company_id'])." AND driver_id=".$db->mySQLSafe($driver_no);
	$select60Min	 = $db->select($query); 
	for($k=0; $k<sizeof($select60Min); $k++){
		
		$driver_60[]=$select60Min[$k]['driver_id'];
		$driver60++;
		}
//***************************************************************************************************	
	
		 
		}
}
	echo $a;	


?>

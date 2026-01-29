<?php
include("includes/includes.inc.php");
include("dompdf_config.inc.php");
include("classes/class.phpmailer.php");
include("classes/setting_classes/setting.php");
include("classes/booking.php");
include("classes/user.php");

$otherdata = new setting($db);
$all_booking = new booking($db);
$user = new user($db);	




ob_start();
ob_get_contents();
ini_set("memory_limit", "100M");

//$_GET['id']=10021342;

$bir=filter_input(INPUT_GET, 'bid');
$report_fromdate=filter_input(INPUT_GET, 'report_fromdate');
$report_todate=filter_input(INPUT_GET, 'report_todate');
$booking_status=filter_input(INPUT_GET, 'booking_status');	
$driver=filter_input(INPUT_GET, 'driver');	

	if($bir){
		
		
		
	$condition='';
	$condition2='';
	if($report_fromdate and $report_todate ){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$date2=date('Y-m-d', strtotime($report_todate));	
		$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		$condition2 .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date1)) .'---'.  date("D M d  Y",strtotime($date2));
		$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
	}else if($report_fromdate and !$report_todate){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		$condition2 .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		//$display_date= date("D M d  Y",strtotime($date1));
		$display_date= date("D M d  Y",strtotime($date1));
	}else if (!$report_fromdate and $report_todate){
		$date2=date('Y-m-d', strtotime($report_todate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		$condition2 .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date2));
		$display_date= date("D M d  Y",strtotime($date2));	
	}
	
	if($driver){
		$condition .=' AND driver_no='.$db->mySQLSafe($driver)."  AND company_id ='".$_SESSION['company_id']."'";
		$condition2 .=' AND driver_no='.$db->mySQLSafe($driver)."  AND canceled_by ='".$_SESSION['company_id']."'";
		//$whr=' OR (driver_no='.$db->mySQLSafe($driver)."  AND canceled_by ='".$_SESSION['company_id']."')";
		}
		
		//************************************Pending jobs****************************************
		
		
		
		 $query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=0";
		
		$pending = $db->select($query1);
		
		
		if($pending[0]['toal_income'] >0){
			 $TOTAL_PENDING_JOBS=$pending[0]['total_jobs'];
			
			 $PENDING_AMOUNT= $pending[0]['toal_income']+$pending[0]['diffrence'];
			 
			 
				
		 }
		
		//************************************Confirm jobs****************************************
		
		
		
		 $query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=1";
		
		$confirm = $db->select($query2);
		
		
		if($confirm[0]['toal_income'] >0){
			 $TOTAL_CONFIRM_JOBS=$confirm[0]['total_jobs'];
			
			 $CONFIRM_AMOUNT= $confirm[0]['toal_income']+$confirm [0]['diffrence'];
			 
			 
				
		 }
		
		
		
		//************************************Complete jobs****************************************
		
		
		
		 $query4="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=3";
		
		$completed = $db->select($query4);
		
		
		if($completed[0]['toal_income'] >0){
			 $TOTAL_COMPLETED_JOBS=$completed[0]['total_jobs'];
			
			 $COMPLETED_AMOUNT= $completed[0]['toal_income']+$completed[0]['diffrence'];
			 
			 
				
		 }
		
		
		
		
		
		
		
		
		$TOTAL_JOBS=$TOTAL_PENDING_JOBS+$TOTAL_CONFIRM_JOBS+$TOTAL_CANCELLED_JOBS+$TOTAL_COMPLETED_JOBS;
		
		
		
		
		
		
		
		
		
  
  
		
		
		}
	
	

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>sdfgsfgsdfgsdfgsdfgsdf</title>
<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">

</head>
<body >

<?php
$html ='<table width="600" >
<tr >
<td style="color:#333; line-height:21px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:14px; padding-top:10px;" >
<b>'.$config["storeAddress"].'</b><br />

Tel : '.$config["storeContact"].'<br />
Email : '.$config["masterEmail"].'
</td>
<td ><img src="images/logo.png" />
</td>
</tr>
<tr style="height:55px;">
<td style="width:250px;height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tr>
<td style=" width:125px; font-weight:bolder; color:#333; border-radius:5px; height:40px; text-align:center; font-size:20px; font-weight:900px;">Report:</td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; text-align:center; color:#333;">Page 1 </td>
</tr>
</table>
</td>
</td>
</tr>
<tr style="height:100px;">
<td style="width:250px; border:1px solid #cecece; height:152px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px; line-height:22px; color:#7a7a7a; margin-left:5px;">
'.$user->getCompanyAddress($_SESSION['company_id']).'<br />
</td>
<td style="width:260px; margin-left:10px; border:1px solid #c1ced9;; height:160px; float:left; font-family:Arial, Helvetica, sans-serif;  font-size:13px; ">
<table>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Driver Number</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">'.$driver.'</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Driver Name</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#333; height:35px; text-align:center; font-size:16px;">'.ucfirst($user->getDriverName($driver,$_SESSION['company_id'])).'</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Report Date</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">'.date("d/m/Y").'</td>
</tr>

<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Period</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">'.$display_date.'</td>
</tr>
</table>
</td>
</td>
</tr>
<tr style="height:65px; margin-top:10px;">
<td style="width:250px;height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">

</td>
</td>
</tr>
<tr  >
<td   colspan="2">';
$html.='</td>
</tr>
</table>';

 $html.='<table width="550" border="1">
 
 
   
 
  <tr style="height:60px; padding-left:10px; background-color:#A3A3A3;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td align="center" colspan="2">Report Detail</td>
    
  </tr>';


$html.=' <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Total Jobs</td>
    <td align="center">'.round($TOTAL_JOBS).' Jobs</td>
  </tr>
  
  <tr  style="height:60px; padding-left:10px;background-color:#A3A3A3; font-family:Arial, Helvetica, sans-serif; font-size:16px;">
<td align="center" colspan="2">&nbsp;&nbsp;Pending Jobs</td>
     </tr>
  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Pending Jobs Total</td>
    <td align="center">'.round($TOTAL_PENDING_JOBS).' Jobs</td>
  </tr>
 
  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Pending Jobs Amount</td>
    <td align="center">&pound; '.round($PENDING_AMOUNT).'</td>
  </tr>';
 
 
 $html.='<tr  style="height:60px; padding-left:10px;background-color:#A3A3A3; font-family:Arial, Helvetica, sans-serif; font-size:16px;">
<td align="center" colspan="2">&nbsp;&nbsp;Confirm Jobs</td>
     </tr>
  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Confirm Jobs Total</td>
    <td align="center"> '.round($TOTAL_CONFIRM_JOBS).' Jobs</td>
  </tr>
 
  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Confirm Jobs Amount</td>
    <td align="center">&pound; '.round($CONFIRM_AMOUNT).'</td>
  </tr>';
  
 
  
  $html.='<tr  style="height:60px; padding-left:10px;background-color:#A3A3A3; font-family:Arial, Helvetica, sans-serif; font-size:16px;">
<td align="center" colspan="2">&nbsp;&nbsp;Completed Jobs</td>
     </tr>
  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Completed Jobs Total</td>
    <td align="center">'.round($TOTAL_COMPLETED_JOBS).' Jobs</td>
  </tr>
 
  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Completed Jobs Amount</td>
    <td align="center">&pound; '.round($COMPLETED_AMOUNT).'</td>
  </tr>';







$html.='</table>';
 

$dompdf = new DOMPDF();
	$dompdf->load_html($html); 
	$dompdf->render(); 
	$output = $dompdf->output();
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	
	$file_to_save = "uploads/pdf/icabit_$exec_time.pdf";
    file_put_contents($file_to_save, $output);
	
	
	
	
	
	
	
	//$dompdf->stream("icabit_$exec_time.pdf");// save pdf file.

/******************************************send email ***************************/
$fileLocation2 =$file_to_save;

$email_from = $config['masterEmail'];
$email_subject = "Here is your Report from icbit.com ";
$email_message ='Here is your Report from icbit.com about your activities ';
$to_user=getCompanyEmail($_SESSION['company_id']);
$mail = new PHPMailer();
$mail->From       = $email_from;

$mail->FromName   = "icabit.com";
$mail->Subject    = $email_subject;
$mail->IsHTML(true);  // set email format to HTML 
$mail->Body = $email_message;
$mail->AddAttachment($fileLocation2);

$mail->AddAddress($to_user, '');

$mail->Send();

unlink($file_to_save);
header("location:".$_SERVER['HTTP_REFERER']);
exit;
?>


</body>



</html>
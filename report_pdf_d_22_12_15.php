<?php
include("includes/includes.inc.php");
include("dompdf_config.inc.php");

include("classes/setting_classes/setting.php");
include("classes/booking.php");
include("classes/user.php");

$otherdata = new setting($db);
$all_booking = new booking($db);
$user = new user($db);	




ob_start();
ob_get_contents();
ini_set("memory_limit", "200M");

//$_GET['id']=10021342;

$bir=filter_input(INPUT_GET, 'bid');
$report_fromdate=filter_input(INPUT_GET, 'report_fromdate');
$report_todate=filter_input(INPUT_GET, 'report_todate');
$booking_status=filter_input(INPUT_GET, 'booking_status');	

	if($bir){
		$condition='';
		if($report_fromdate and $report_todate ){
			$date1=date('Y-m-d', strtotime($report_fromdate));
			$date2=date('Y-m-d', strtotime($report_todate));	
			$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
			$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
		}else if($report_fromdate and !$report_todate){
			$date1=date('Y-m-d', strtotime($report_fromdate));
			$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
			
			$display_date= date("D M d  Y",strtotime($date1));
		}else if (!$report_fromdate and $report_todate){
			$date2=date('Y-m-d', strtotime($report_todate));
			$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
			$display_date= date("D M d  Y",strtotime($date2));
		}
	 
		/*if($booking_status == 2){
			$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income FROM cab_order_sum
			WHERE 1=1 ".$condition." AND booking_status ='".$booking_status."' AND canceled_by ='".$_SESSION['company_id']."'";
			
			$query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
			WHERE 1=1 ".$condition." AND booking_status ='3' AND canceled_by ='".$_SESSION['company_id']."'";
			$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' and booking_status ='3' AND company_id ='".$_SESSION['company_id']."'";
			$results2 = $db->select($query2); 
		}else{		
			date("D M d  Y",strtotime($getdata[$i]['end_date']));
			$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
			FROM `cab_order_sum`
			WHERE 1=1  ".$condition."
			AND `booking_status` ='".$booking_status."'
			AND company_id ='".$_SESSION['company_id']."'";
			$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
		}*/

		//*****************************************************************
		$credit_vat=0.03;
		//************************************total jobs****************************************
		$query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition." AND `booking_status` ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
		
		$results = $db->select($query1);
		$deper=$results[0]['toal_income'];
		if($results[0]['toal_income'] >0){
			$total_income_gross= $results[0]['toal_income']+$results[0]['diffrence'];
			
			
		}
		//*************************************cancel jobs******************************************
		$query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND canceled_by ='".$_SESSION['company_id']."'";
		$results2 = $db->select($query2); 
		if($results2[0]['toal_price'] >0 ){
			$canceled_amount=$results2[0]['toal_price']-$results2[0]['myprice'];
		}
		
		//*********************************************credit cards**********************************************************
		$query3="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition." AND `booking_status` ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."' and payment_type=1";
		$results3 = $db->select($query3);
		
		if($results3[0]['toal_income'] >0){
		$TOALJOBS=$results3[0]['total_jobs'];
		$total_card_gross= $results3[0]['toal_income']+$results3[0]['diffrence'];
		$card_vat_total = round(($total_card_gross*$credit_vat)/$TOALJOBS,2);
		$cancelation;
		
		$TOALJOBS = $results3[0]['total_jobs'];
		$total_card_charges = round($results3[0]['toal_income']+$results3[0]['diffrence'],2); 
		//card charges*********
		$total_card_gross = round((($results3[0]['toal_income']+$results3[0]['diffrence'])*$credit_vat)/$TOALJOBS,2);
		$card_vat_total = round(($total_card_gross*$config['vat_amount'])/100,2);
		/*************/
		} 
		
		$commission= round((($total_income_gross-$canceled_amount)*$config['commission'])/100,2);
		$vatoncommision= round(($commission*$config['vat_amount'])/100,2);
		
		
		
		$totalamountgros=$commission;
		$totalvat=$vatoncommision+$card_vat_total;
		$GRAND_TOTAL=$totalamountgros + $totalvat+$total_card_gross;
		
	}
	
	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Pdf File</title>
        <link rel="stylesheet" href="css/style.css">
        <link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">
    </head>
<body>

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
<td style=" width:125px; font-weight:bolder; color:#333; border-radius:5px; height:40px; text-align:center; font-size:20px; font-weight:900px;">INVOICE:</td>
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
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Invoice No</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">'.$user->mkOrderNo().'</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Invoice Date</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">'.date("d/m/Y").'</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Order No</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#333; height:35px; text-align:center; font-size:16px;"></td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Account Ref</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">000</td>
</tr>
</table>
</td>
</td>
</tr>
<tr style="height:65px; margin-top:10px;">
<td style="width:250px;height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tr>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; margin-bottom:20px; text-align:center; color:#707070; font-size:16px; font-weight:900px;">VAT Reg No:</td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; text-align:center; margin-bottom:20px; color:#7a7a7a;">'.$config['vat_number'].' </td>
</tr>
</table>
</td>
</td>
</tr>
<tr>
<td colspan="2">

<table width="500" style=" color:#fff; margin-left:5px;margin-right:5px; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
	<tr style="background-color:#a3a3a3;">
		<td style="width:50px;text-align:center; font-weight:900;">Quantity</td>
		<td style="width:200px;text-align:center; font-weight:900;">Description</td>
		<td style="width:50px;text-align:center; font-weight:900;">Unit Price</td>
		<td style="width:50px;text-align:center; font-weight:900;">Disc Amt</td>
		<td style="width:50px;text-align:center; font-weight:900;">Net Amt</td>
		<td style="width:50px;text-align:center; font-weight:900;">VAT %</td>
		<td style="width:50px; text-align:center;font-weight:900;">VAT</td>
	</tr>
	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td style="width:50px; text-align:center; color:#6e767d;">'.$total_jobs.'</td>
		<td style="width:200px; text-align:center; color:#6e767d;">Total gross - '.$display_date.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$total_income_gross.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$config['vat_amount'].'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
	</tr>
	<tr style="height:60px; padding-left:10px; ">
		<td style="width:50px; text-align:center; color:#6e767d;">'.$total_cancel_jobs.'</td>
		<td style="width:200px; text-align:center; color:#6e767d;">Total Cancellation - 15th to 30th November 201</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$canceled_amount.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$config['vat_amount'].'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
	</tr>
	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:200px; text-align:center; color:#6e767d;">'.$config['commission'].' Commission - '.$display_date.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$commission.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$commission.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$config['vat_amount'].'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$vatoncommision.'</td>
	</tr>
	<tr style="height:60px; padding-left:10px; ">
		<td style="width:50px; text-align:center; color:#6e767d;">'.$TOALJOBS.'</td>
		<td style="width:200px; text-align:center; color:#6e767d;">Credit Card Payment - '.$display_date.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$total_card_charges.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
	</tr>
	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td style="width:50px; text-align:center; color:#6e767d;">'.$TOALJOBS.'</td>
		<td style="width:200px; text-align:center; color:#6e767d;">Credit Card Charges - '.$display_date.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$total_card_gross.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$total_card_gross.'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$config['vat_amount'].'</td>
		<td style="width:50px; text-align:center; color:#6e767d;">'.$card_vat_total.'</td>
	</tr>

	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td colspan="7"  >&nbsp;</td>
	</tr>
	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td colspan="5" style="width:120px;color:black;">Total Net Amount</td>
		<td style="width:120px;color:black;text-align:center;">&nbsp;</td>
		<td style="width:120px;color:black;text-align:center;">&pound;&nbsp;'.$totalamountgros.'</td>
	</tr>
	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td colspan="5" style="width:120px; color:black;">Total Tax Amount</td>
		<td style="width:120px;color:black;text-align:center;">&nbsp;</td>
		<td style="width:120px;color:black;text-align:center;">&pound;&nbsp;'.$totalvat.'</td>
	</tr>
	<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
		<td colspan="5" style="width:120px;color:black;">Invoice Total</td>
		<td style="width:120px;color:black;text-align:center;">&nbsp;</td>
		<td style="width:120px;color:black;text-align:center;">&pound;&nbsp;'.$GRAND_TOTAL.'</td>
	</tr>
</table>
</td>
</tr>
</table>';
  
   

	$dompdf = new DOMPDF();
	$dompdf->load_html($html); 
	$dompdf->render(); 
	$dompdf->output();
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	$dompdf->stream("icabit_$exec_time.pdf");// save pdf file.
?>


</body>



</html>
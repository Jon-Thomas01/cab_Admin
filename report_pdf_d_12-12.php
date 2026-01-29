<?php
include("includes/includes.inc.php");
include("dompdf_config.inc.php");

include("classes/setting_classes/setting.php");
include("classes/booking.php");
include("classes/user.php");

ob_start();
ob_get_contents();
ini_set("memory_limit", "100M");



$otherdata = new setting($db);
$all_booking = new booking($db);
$user = new user($db);	


//$_GET['id']=10021342;

$bir=filter_input(INPUT_GET, 'bid');
$report_fromdate=filter_input(INPUT_GET, 'report_fromdate');
$report_todate=filter_input(INPUT_GET, 'report_todate');
$booking_status=filter_input(INPUT_GET, 'booking_status');	

$credit_vat=0.03;
if($bir){
	/*$condition='';
	if($report_fromdate and $report_todate ){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$date2=date('Y-m-d', strtotime($report_todate));	
		$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date1)) .'---'.  date("D M d  Y",strtotime($date2));
		
		//15th to 30th November 201
		
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
	
	
	 $query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition." AND `booking_status` ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
	
	    $results = $db->select($query1);
	
	    if($results[0]['toal_income'] >0){
		
		$total_income_gross= $results[0]['toal_income']+$results[0]['diffrence'];
	
	    $commission= round(($total_income_gross*$config['commission'])/100,2);
		
		$vat= round(($commission*$config['vat_amount'])/100,2);
		
		
		
		
		}
	   
	   $query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND canceled_by ='".$_SESSION['company_id']."'";
		$results2 = $db->select($query2); 
	    
		if($results2[0]['toal_price'] >0 ){
	     $canceled_amount=$results2[0]['toal_price']-$results2[0]['myprice'];
	     
		 
		 
		 
		}
	    
		
		$query3="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition." AND `booking_status` ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."' and payment_type=1";
		
		$results3 = $db->select($query3);
	
	    if($results3[0]['toal_income'] >0){
		
		 $total_card_gross= $results3[0]['toal_income']+$results3[0]['diffrence'];
	
	    
		
		 $card_vat_total= round(($total_card_gross*$credit_vat)/100,2);
		
		
		$cancelation;
		
		} 
		
		
		
		$totalamountgros=$commission;
		$totalvat=$vat+$card_vat_total;
		$GRAND_TOTAL=$totalamountgros+$totalvat;*/
		
	   
	/*if($booking_status == 2){
		$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='".$booking_status."' AND canceled_by ='".$_SESSION['company_id']."'";
	
		
		
		
		
		
		
		
		
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' and booking_status ='3' AND company_id ='".$_SESSION['company_id']."'";
	
	
	
	
	
	}	else{		
		date("D M d  Y",strtotime($getdata[$i]['end_date']));
		
		
		$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum`
		WHERE 1=1  ".$condition."
		AND `booking_status` ='".$booking_status."'
		AND company_id ='".$_SESSION['company_id']."'";
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
	
	}*/
    /*$results = $db->select($query);
 	$where=$qur;
	$daata= $all_booking->getBookingDataReport($where); */
}
//SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income, SUM(new_price-original_price)as diffrence FROM `cab_order_sum` WHERE 1=1 AND `booking_status` ='1' AND company_id ='2'


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

  	


$html='<table  style="width:560px;  border:1px solid #cecece; margin:0px auto;">
<tr style="height:150px;">
<td style="width:255px; height:150px; color:#333; line-height:21px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:14px; padding-top:10px;">
<b>Minicabster Limited</b><br />
<b>c/o Illuminate Agency</b><br />
<b>Henry Wood House</b><br />
<b>2 Riding House Street</b><br />
<b>London</b><br />
W1W 7FA<br />
Tel : 0203 475 6656<br />
Email : accounts@minicabster.co.uk
</td>
<td style="width:180px; height:150px; float:left; margin-top:10px;"><img src="images/logo.png" />
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
<td style="width:250px; border:1px solid #cecece; height:152px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px; line-height:22px; margin-left:5px;">
SM Chauffuers<br />
Wellesley House, 1st Floor Suites<br />
102 Cranbrook Road<br />
Ilford<br />
Essex<br />
IG1 4NH<br />
</td>
<td style="width:260px; margin-left:10px; border:1px solid #cecece;; height:160px; float:left; font-family:Arial, Helvetica, sans-serif;  font-size:13px; ">
<table>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#4d4a4f; color:#fff; height:35px; text-align:center; font-size:14px;">Invoice No</td>
<td style=" width:125px; font-weight:bolder; background-color:#e4e4e4; color:#333; height:35px; text-align:center; font-size:16px;">47746</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#4d4a4f; color:#fff; height:35px; text-align:center; font-size:14px;">Invoice Date</td>
<td style=" width:125px; font-weight:bolder; background-color:#e4e4e4; color:#333; height:35px; text-align:center; font-size:16px;">30/11/2015</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#4d4a4f; color:#fff; height:35px; text-align:center; font-size:14px;">Order No</td>
<td style=" width:125px; font-weight:bolder; background-color:#e4e4e4; color:#333; height:35px; text-align:center; font-size:16px;"></td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#4d4a4f; color:#fff; height:35px; text-align:center; font-size:14px;">Account Ref</td>
<td style=" width:125px; font-weight:bolder; background-color:#e4e4e4; color:#333; height:35px; text-align:center; font-size:16px;">644</td>
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
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; margin-bottom:20px; text-align:center; color:#333; font-size:20px; font-weight:900px;">VAT Reg No:</td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; text-align:center; margin-bottom:20px; color:#333;">127723219 </td>
</tr>
</table>
</td>
</td>
</tr>
<tr style="height:40px;">
<td>
<table style="width:540px; background-color:#4d4a4f; color:#fff; margin-left:5px; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tr>
<td style="width:50px; font-weight:900;">Quantity</td>
<td style="width:120px; font-weight:900;">Description</td>
<td style="width:50px; font-weight:900;">Unit Price</td>
<td style="width:50px; font-weight:900;">Disc Amt</td>
<td style="width:50px; font-weight:900;">Net Amt</td>
<td style="width:50px; font-weight:900;">VAT %</td>
<td style="width:50px; font-weight:900;">VAT</td>
</tr>
</table>

<table style="width:540px; margin-left:5px; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; font-size:13px; ">

<tr style="height:60px; padding-left:10px; background-color:#f1f1f1;">
<td style="width:50px; text-align:center;">0.00</td>
<td style="width:250px; text-align:center;">Total gross - 15th to 30th November 201</td>
<td style="width:120px; text-align:center;">731.00</td>
<td style="width:120px; text-align:center;">0.00</td>
<td style="width:120px; text-align:center;">0.00</td>
<td style="width:120px; text-align:center;">20.00</td>
<td style="width:120px; text-align:center;">0.00</td>
</tr>


<tr style="height:60px; padding-left:10px; ">
<td style="width:250px; text-align:center; ">0.00</td>
<td style="width:120px; text-align:center; ">Total gross - 15th to 30th November 201</td>
<td style="width:50px; text-align:center; ">731.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:50px; text-align:center; ">20.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
</tr>


<tr style="height:60px; padding-left:10px; background-color:#f1f1f1;">
<td style="width:250px; text-align:center; ">0.00</td>
<td style="width:120px; text-align:center; ">Total gross - 15th to 30th November 201</td>
<td style="width:50px; text-align:center; ">731.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:50px; text-align:center; ">20.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
</tr>


<tr style="height:60px;">
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:250px; text-align:center; ">Total gross - 15th to 30th November 201</td>
<td style="width:50px; text-align:center; ">731.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
<td style="width:50px; text-align:center; ">20.00</td>
<td style="width:50px; text-align:center; ">0.00</td>
</tr>
</table>
</td>
</tr>
<tr style="height:40px;">
<td></td>
</tr>
<tr style="height:2px;  background-color:#333;">
<td></td>
</tr>
<tr style="height:30px;">
<td style="width:250px; height:20px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:20px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tr>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
</tr>
</table>
</td>
</td>
</tr>
<tr style="height:200px;">
<td style="width:250px; border:1px solid gray; height:120px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; border:1px solid gray; height:170px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; padding-right:5px; font-size:13px; padding-top:10px;">
<table style="width:250px; height:35px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tr>
<td></td>
</tr>
</table>
<table style="width:240px; color:#000; border-bottom:1px solid gray; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tr>
<td style="width:250px; font-weight:900;">Total Net Amount</td>
<td style="width:120px; font-weight:900;">£</td>
<td style="width:50px; font-weight:900;">52.32</td>
</tr>
</table>
<table style="width:240px; color:#000; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tr>
<td style="width:250px; font-weight:900;">Total Net Amount</td>
<td style="width:120px; font-weight:900;">£</td>
<td style="width:50px; font-weight:900;">52.32</td>
</tr>
</table>
<table style="width:240px; background-color:#333; color:#fff; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;"><tr>
<td style="width:250px; font-weight:900;">Total Net Amount</td>
<td style="width:120px; font-weight:900;">£</td>
<td style="width:50px; font-weight:900;">52.32</td>
</tr>
</table>


</td>
</td>
</tr>
<tr style="height:30px;">
<td style="width:250px;height:10px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:10px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tr>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
</tr>
</table>
</td>
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
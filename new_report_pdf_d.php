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

$bir=filter_input(INPUT_GET, 'bid');
$report_fromdate=filter_input(INPUT_GET, 'report_fromdate');
$report_todate=filter_input(INPUT_GET, 'report_todate');
$booking_status=filter_input(INPUT_GET, 'booking_status');	


/*********extra inputs***********/
$invoice_account_user = filter_input(INPUT_GET, 'invoice_account_user');
$invoice_discount_price = filter_input(INPUT_GET, 'invoice_discount_price');
$invoice_driver_no = filter_input(INPUT_GET, 'invoice_driver_no');
$invoice_payment_type = filter_input(INPUT_GET, 'invoice_payment_type');
$invoice_refernce_no = filter_input(INPUT_GET, 'invoice_refernce_no');
/*******extra inputs**********/



	if($bir){
		
	$condition='';
	if($report_fromdate and $report_todate ){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$date2=date('Y-m-d', strtotime($report_todate));	
		$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date1)) .'---'.  date("D M d  Y",strtotime($date2));
		$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
	}else if($report_fromdate and !$report_todate){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		//$display_date= date("D M d  Y",strtotime($date1));
		$display_date= date("D M d  Y",strtotime($date1));
	}else if (!$report_fromdate and $report_todate){
		$date2=date('Y-m-d', strtotime($report_todate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date2));
		$display_date= date("D M d  Y",strtotime($date2));	
	}
	
	if($invoice_discount_price <> ""){
		$condition  .= ' AND  discount = ' .$invoice_discount_price;
	}
	
	
	if($invoice_account_user <> ""){
		$condition  .= ' AND  cab_account_id = ' .$invoice_account_user;
	}
	if($invoice_driver_no <> ""){
		$condition  .= ' AND  driver_no = ' .$invoice_driver_no;
	}
	if($invoice_payment_type <> ""){
		$condition  .= ' AND  payment_type = ' .$invoice_payment_type;
	}
	
	if($invoice_refernce_no <> ""){
		$condition  .= ' AND  cart_order_id = ' .$invoice_refernce_no;
	} 
	
	if($booking_status == 2){
		
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' and booking_status ='3' AND company_id ='".$_SESSION['company_id']."'";
	}
	else if($booking_status == 7){
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' and booking_status ='2' AND canceled_by='".$_SESSION['company_id']."'";
	}
		else{		
		date("D M d  Y",strtotime($getdata[$i]['end_date']));
		$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum`
		WHERE 1=1  ".$condition."
		AND `booking_status` ='".$booking_status."'
		AND company_id ='".$_SESSION['company_id']."'";
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
	}
   
 	$where=$qur;
	
	if($booking_status == 2){
		$where="1=1 ".$condition." AND booking_status ='3' AND handback='1' AND canceled_by ='".$_SESSION['company_id']."'";
		}else if($booking_status == 7){
			$where="1=1 ".$condition." AND booking_status ='2' AND handback='0' AND canceled_by ='".$_SESSION['company_id']."'";
		}else{
			$where;
		}
	
	
    if($booking_status == 6 and $booking_status !=2){
		
		$book_status ;
		$where=" 1=1 ".$condition." AND company_id ='".$_SESSION['company_id']."'";
		}else{
			$book_status =" AND booking_status='".$booking_status."' ";
		}


		$data= $all_booking->getBookingDataReport($where); 
		$dataCount = count($data);

		//*****************************************************************
		$credit_vat=0.03;
		$fixed_panility_charges=2;
		//************************************total jobs by cash No Cancel****************************************
		
		
		
		 $query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition.$book_status." AND company_id ='".$_SESSION['company_id']."' and payment_type=2 ";
		
		$cash = $db->select($query1);
		
		
		if($cash[0]['toal_income'] >0){
			 $TOTAL_JOBS_CASH=$cash[0]['total_jobs'];
			
			 $TOTAL_INCOME_CASH= $cash[0]['toal_income'];
			 
			 $TOTAL_DIFFRENCE_CASH= $cash[0]['diffrence'];
				
		 }
		
		
		//*********************************************credit cards**********************************************************
		$query3="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition.$book_status." AND company_id ='".$_SESSION['company_id']."' and payment_type=1";
		$results3 = $db->select($query3);
		
		if($results3[0]['toal_income'] >0){
		$TOAL_JOBS_CARD=$results3[0]['total_jobs'];
		
		$TOTAL_INCOME_CARD= $results3[0]['toal_income'];
			 
		$TOTAL_DIFFRENCE_CARD= $results3[0]['diffrence'];
		
		$CARD_CHARGES_TOTAL = round(($TOTAL_INCOME_CARD*$credit_vat)/$TOAL_JOBS_CARD);
		
		
		
		$card_vat_total = round(($CARD_CHARGES_TOTAL*$config['vat_amount'])/100,2);
		
		
		
		/*************/
		} 
		
		
		
		
		
		
		
		
		//*************************************Handback jobs by me******************************************
		$query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND handback='1' AND canceled_by ='".$_SESSION['company_id']."'";
		$results2 = $db->select($query2); 
			
			if($results2[0]['toal_price'] >0 ){
			
			     $TOTAL_HANDBACK_JOBS=$results2[0]['total_jobs'];
			
			     $TOTAL_NEW_AMOUNT=$results2[0]['toal_price'];
			     $TOTAL_AMOUNT=$results2[0]['myprice'];
				 $TOTAL_HANDBACK_AMOUNT=$results2[0]['toal_price']-$results2[0]['myprice'];
				 $TOTAL_FIXED_ON_CANCEL=$fixed_panility_charges*$TOTAL_HANDBACK_JOBS;
			
			}
		
		
		
		
		
		
		
		$commission= round((($total_income_gross-$canceled_amount)*$config['commission'])/100,2);
		$vatoncommision= round(($commission*$config['vat_amount'])/100,2);
		$totalamountgros=$commission;
		$totalvat=$vatoncommision+$card_vat_total;
		$GRAND_TOTAL=$totalamountgros + $totalvat+$total_card_gross;
		
		
		
		$TOTAL_CASH=$TOTAL_INCOME_CASH;
		$TOTAL_CARD=$TOTAL_INCOME_CARD;
		$CASH_PAYED_BY_ICABIT_HANDBACK=$TOTAL_DIFFRENCE_CASH+$TOTAL_DIFFRENCE_CARD;
		$TOTAL_VALUE_ICABIT=$TOTAL_CASH+$TOTAL_CARD+$CASH_PAYED_BY_ICABIT_HANDBACK;
		
		$COMMISSION=round((($TOTAL_CASH+$TOTAL_CARD+$CASH_PAYED_BY_ICABIT_HANDBACK)*$config['commission'])/100,2);
		$VAT_ON_COMMISSION=round(($COMMISSION*$config['vat_amount'])/100,2);
		$FIXED_PANELTY=$TOTAL_FIXED_ON_CANCEL;
		$BOOKING_SWITCH_COST=$TOTAL_HANDBACK_AMOUNT;
		$TOTAL_CARD_CHARGES=$CARD_CHARGES_TOTAL;
        $TOTAL_VAT_ON_CARD=$card_vat_total;
		$CHARGEABLE_EXPENSE=$COMMISSION+$FIXED_PANELTY+$BOOKING_SWITCH_COST+$TOTAL_CARD_CHARGES+$TOTAL_VAT_ON_CARD;
		$FINAL_VALUE=$TOTAL_CARD-$CHARGEABLE_EXPENSE;
  
  
  
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
<tr  >
<td   colspan="2">';




	 
	
       
      
       
        
          
           
       


$html.='</td>
</tr>
</table>';
   if($booking_status==2){
	 
	     
 $html .='<div>
 <table width="200" >
  <tr height="30">
    <td colspan="2">Report Parameters&nbsp;</td>
    
  </tr>
  
  <tr height="30" >
    <td>Cab Office&nbsp; </td>
    <td>&nbsp;'.ucfirst($user->getCompanyName($_SESSION['company_id'])).'</td>
  </tr>
  <tr height="30" bgcolor="#E5E5E5">
    <td>Version&nbsp; </td>
    <td>&nbsp;1.1</td>
  </tr>
  <tr height="30">
    <td>Period&nbsp;</td>
    <td>&nbsp;'.$display_date.'</td>
  </tr>
   <tr height="30" bgcolor="#E5E5E5">
    <td >Job Type&nbsp;</td>
    <td>&nbsp;Handback</td>
  </tr>
</table>
<div style="height:20px;"></div>
 <table width="510" border="1" >
            <thead>
              <tr>
              <th>Sr. No</div></th>
                <th >Booking#</div></th>
                <th>Cancel Date</div></th>
                <th>Your Fare</th>
                <th>New Price</th>
                <th >Diffrence</th>
                <th>Penelity</th>
                <th>Total Amount</th>
                
                
                               
                
              </tr>
            </thead>
            
            <tbody>';
            
            $qury="SELECT cart_order_id, new_price , ordertotal,cancel_date FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND handback='1' AND canceled_by ='".$_SESSION['company_id']."'";
		$record = $db->select($qury); 
			
			$TOTAL=0;
			
			for($k='0'; $k<sizeof($record); $k++){ 
			
			$serial2 = $k + 1;
			
			$diffrence=($record[$k]['new_price']-$record[$k]['ordertotal']);
			$sum=($record[$k]['new_price']-$record[$k]['ordertotal'])+$fixed_panility_charges;
			
			
            
            
            
            $html .='<tr height="50">
            <td>'.$serial2.'</td>
                <td align="center">'.$record[$k]['cart_order_id'].'</td>
                <td align="center">'.date("m-d-Y", strtotime($record[$k]['cancel_date'])).'</td>
                <td>&pound;&nbsp;'.round($record[$k]['ordertotal']).'</td>
                <td>&pound;&nbsp;'.round($record[$k]['new_price']).'</td>
                <td ><div>&pound;&nbsp;'.round($diffrence).'</td>
                <td>&pound;&nbsp;'.round($fixed_panility_charges).'</td>
                <td>&pound;&nbsp;'.round($sum).'</td>
            
            </tr>';
            
           
			
			$TOTAL=$TOTAL+$sum;
			}
            $html .='<tr>
            <td height="50" align="right" colspan="8"><div style="padding-left:100px">&nbsp;<strong>Total Amount:&nbsp;&nbsp; &pound;&nbsp;'.round($TOTAL).'</strong>&nbsp;</div></td>
                            
            </tr>
            </tbody>
            </table>






 </div>';
 
  }
  else  if($booking_status==7){
	 
	     
 $html .='<div>
 <table width="200" >
  <tr height="30">
    <td colspan="2">Report Parameters&nbsp;</td>
    
  </tr>
  
  <tr height="30" >
    <td>Cab Office&nbsp; </td>
    <td>&nbsp;'.ucfirst($user->getCompanyName($_SESSION['company_id'])).'</td>
  </tr>
  <tr height="30" bgcolor="#E5E5E5">
    <td>Version&nbsp; </td>
    <td>&nbsp;1.1</td>
  </tr>
  <tr height="30">
    <td>Period&nbsp;</td>
    <td>&nbsp;'.$display_date.'</td>
  </tr>
   <tr height="30" bgcolor="#E5E5E5">
    <td >Job Type&nbsp;</td>
    <td>&nbsp;Cancel</td>
  </tr>
</table>
<div style="height:20px;"></div>
 <table width="510" border="1" >
            <thead>
              <tr>
              <th>Sr. No</div></th>
                <th >Booking#</div></th>
                <th>Cancel Date</div></th>
                <th>Your Fare</th>
                <th>New Price</th>
                
                <th>Total Amount</th>
                
                
                               
                
              </tr>
            </thead>
            
            <tbody>';
            
            $qury="SELECT cart_order_id, new_price , ordertotal,cancel_date FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='2' AND handback='0' AND canceled_by ='".$_SESSION['company_id']."'";
		$record = $db->select($qury); 
			
			$TOTAL=0;
			
			for($k='0'; $k<sizeof($record); $k++){ 
			
			$serial2 = $k + 1;
			
		//	$diffrence=($record[$k]['new_price']-$record[$k]['ordertotal']);
			$sum=$record[$k]['ordertotal'];
			
			
            
            
            
            $html .='<tr height="50">
            <td>'.$serial2.'</td>
                <td align="center">'.$record[$k]['cart_order_id'].'</td>
                <td align="center">'.date("m-d-Y", strtotime($record[$k]['cancel_date'])).'</td>
                <td>&pound;&nbsp;'.round($record[$k]['ordertotal']).'</td>
                <td>&pound;&nbsp;'.round($record[$k]['new_price']).'</td>
                
                <td>&pound;&nbsp;'.round($sum).'</td>
            
            </tr>';
            
           
			
			$TOTAL=$TOTAL+$sum;
			}
            $html .='<tr>
            <td height="50" align="right" colspan="8"><div style="padding-left:100px">&nbsp;<strong>Total Amount:&nbsp;&nbsp; &pound;&nbsp;'.round($TOTAL).'</strong>&nbsp;</div></td>
                            
            </tr>
            </tbody>
            </table>






 </div>';
 
  }else{
 
 $html.='<table width="550" border="1">
  <tr style="height:60px; padding-left:10px; background-color:#A3A3A3;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <th scope="col">&nbsp;&nbsp;Description&nbsp;&nbsp;</th>
    <th scope="col">&nbsp;&nbsp;Amount&nbsp;</th>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px; background-color:#E5E5E5;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Total value of jobs Paid through C.card</td>
    <td align="center">&pound; '. round($TOTAL_CARD).'</td>
  </tr>';
 $html.=' <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
<td>&nbsp;&nbsp;Total value of jobs Paid through cash</td>
    <td align="center">&pound; '. round($TOTAL_CASH).'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px; background-color:#E5E5E5;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Total cash paid by Icabit against cancelled jobs</td>
    <td align="center">&pound; '. round($CASH_PAYED_BY_ICABIT_HANDBACK).'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px;background-color:#A3A3A3; font-family:Arial, Helvetica, sans-serif; font-size:17px;">
    <td>&nbsp;&nbsp;Total value of jobs booked with Icabit;</td>
    <td align="center">&pound; '. round($TOTAL_VALUE_ICABIT).'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px; background-color:#E5E5E5;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Total chargeable commission '. $config['commission'].'%</td>
    <td align="center">&pound; '. $COMMISSION.'</td>
  </tr>';
  
$html.='  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Fixed panelty charges receievable<br />&nbsp;&nbsp;(No. of jobs X Rate)</td>
    <td align="center">&pound; '. $FIXED_PANELTY .'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px; background-color:#E5E5E5;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Booking switch cost<br />&nbsp;&nbsp;(Revised fare-Booking fair)</td>
    <td align="center">&nbsp;&pound; '. $BOOKING_SWITCH_COST.'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Credit card caharges<br  />&nbsp;&nbsp;50 p/Transaction</td>
    <td align="center">&pound; '. round($TOTAL_CARD_CHARGES).'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px;background-color:#E5E5E5; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;VAT on credit card charges</td>
    <td align="center">'. round($TOTAL_VAT_ON_CARD).'</td>
  </tr>';
$html.='  <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;</td>
    <td align="center">&pound; '. round($CHARGEABLE_EXPENSE).'</td>
  </tr>';
  
$html.='  <tr style="height:60px; padding-left:10px;background-color:#E5E5E5; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Credit card amount payable</td>
    <td align="center">&pound; ('. round($TOTAL_CARD).')</td>
  </tr>';
 $html.=' <tr style="height:60px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Chargeable expense</td>
    <td align="center">&pound; '. round($CHARGEABLE_EXPENSE).'</td>
  </tr>';
  
   
      if($FINAL_VALUE > 0){
		  
		  $text= 'Payable';
		  $FINAL_VALUE;
		  
		  
		  }else{
			  $text= 'Receivable';
			  $Test=str_replace('-','',$FINAL_VALUE);
			  
			  $FINAL_VALUE=$Test;
			  }
	
  
  
  
  $html.='<tr style="height:60px; padding-left:10px; background-color:#A3A3A3;font-family:Arial, Helvetica, sans-serif; font-size:17px;">
    <td>&nbsp;&nbsp;'. $text.'</td>
    <td align="center">&pound;('. round($FINAL_VALUE).')</td>
  </tr>
</table>';
 
    

       
 }
  

	$dompdf = new DOMPDF();
	$dompdf->load_html($html); 
	$dompdf->render(); 
	$dompdf->output();
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	$dompdf->stream("icabit_$exec_time.pdf");
	
	
	
	// save pdf file.
?>


</body>



</html>
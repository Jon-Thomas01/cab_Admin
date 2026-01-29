<?php 
include("includes/includes.inc.php");
include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db);
$user = new user($db);
	$input = INPUT_GET;
//if(isset($_GET['get']) && $_GET['get']==true){
	//$input  = INPUT_GET;	
//}
	$bir=filter_input($input, 'bid');
	$report_fromdate = filter_input(INPUT_GET, 'report_fromdate');
	$report_todate = filter_input(INPUT_GET, 'report_todate');
	$booking_status = filter_input(INPUT_GET, 'booking_status');	

	/*********extra inputs***********/
	$invoice_account_user = filter_input(INPUT_GET, 'invoice_account_user');
	$invoice_discount_price = filter_input(INPUT_GET, 'invoice_discount_price'); // uncomment for discount purpose
	
	$invoice_driver_no = filter_input(INPUT_GET, 'invoice_driver_no');
	$invoice_payment_type = filter_input(INPUT_GET, 'invoice_payment_type');
	$invoice_refernce_no = filter_input(INPUT_GET, 'invoice_refernce_no');
	/*******extra inputs**********/

    $aVal['invoice_discount_price'] = $_REQUEST['invoice_discount_price']; 
	$aVal['invoice_account_user'] = $_REQUEST['invoice_account_user']; 
	$aVal['invoice_driver_no'] = $_REQUEST['invoice_driver_no']; 
	$aVal['invoice_payment_type'] = $_REQUEST['invoice_payment_type']; 
	$aVal['invoice_refernce_no'] = $_REQUEST['invoice_refernce_no']; 
	$queryStr = queryString($aVal);
  
  if($bir){
	$condition='';
	if($report_fromdate and $report_todate ){
		$date1= date('Y-m-d', strtotime($report_fromdate)); 
		$date2= date('Y-m-d', strtotime($report_todate));	
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
			$qur =" 1=1 ".$condition." AND booking_status ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
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
		$credit_vat= 0.03;
		$fixed_panility_charges = 2;
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
   
   function queryString($aVal,$defaulVal=''){
	  $queryStr = '';
	  if($defaulVal <> ""){
	  	$queryStr  = $defaulVal;
	  }
	  foreach($aVal as $key=>$value){
		 $queryStr  .= '&'.$key.'='.$value;
	  }
	 return $queryStr; 
  }
   
   
   
   
?>
<style>
#tb_mainView {
    width: 100%;
	
}
.admin_register_form input, .admin_register_form select {
    margin: inherit;
    width: 290px;
	border: 2px solid #c9c9c9;
    display: block;
    height: 40px;
    padding: 5px 10px;
}
.btnpickup {
    background: #f7c51f none repeat scroll 0 0;
    border: medium none;
    border-radius: 8px;
    color: #000;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 20px;
    height: 40px;
    padding-left: 15px;
    width: 80%;
	float:right;
}
</style>
<link href="reports_scripts/css/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="<?php echo $glob['storeURL']; ?>css/bootstrap.min.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">

<div style="height:10px; width:100%;"></div>
<div id="search_bookings" class="admin_register_form">
   <!--<form method="post" action="">
   <input type="hidden" name="bid" id="bid" value="bid" />
  
   
    <div style="" class="col-md-3">
    <input type="text" name="report_fromdate" id="report_fromdate" placeholder="From Date" value="<?php echo  $report_fromdate;?>"  /> 
    
    </div>
    <div style="" class="col-md-3">
    <input type="text" name="report_todate" id="report_todate" placeholder="To Date" value="<?php echo $report_todate?>"  />
    
    </div>
     <div style="" class="col-md-1">
			<input type="hidden" name="booking_status" id="booking_status" value="6" />
            
    </div>
    
        <div style="" class="col-md-3">
            <button class=" btn-block">Generate Report</button>
        </div>
    
   
    <div class="clearfix"></div>
    </form>-->
    <div class="clearfix"></div>
        <div style="margin-top: 30px; margin-bottom:30px;"> 
 <?php if($bir){
	 if($booking_status==2){
	 ?>    
 <div>
 <table width="200" >
  <tr height="30">
    <td colspan="2">Report Parameters&nbsp;</td>
    
  </tr>
  
  <tr height="30" >
    <td>Cab Office&nbsp; </td>
    <td>&nbsp;<?php echo ucfirst($user->getCompanyName($_SESSION['company_id']));?></td>
  </tr>
  <tr height="30" bgcolor="#E5E5E5">
    <td>Version&nbsp; </td>
    <td>&nbsp;1.1</td>
  </tr>
  <tr height="30">
    <td>Period&nbsp;</td>
    <td>&nbsp;<?php echo $display_date;?></td>
  </tr>
   <tr height="30" bgcolor="#E5E5E5">
    <td >Job Type&nbsp;</td>
    <td>&nbsp;Handback</td>
  </tr>
</table>
<div style="height:20px;"></div>
 <table id="tb_mainView" class="table table-striped table-hover table-bordered align-center tb-v-align-middle">
            <thead>
              <tr>
              <th><div style="width:40px">&nbsp;Sr. No&nbsp;</div></th>
                <th ><div style="width:100px;">&nbsp;Booking Number&nbsp;</div></th>
                <th><div style="width:102px">&nbsp;Cancelation Date&nbsp;</div></th>
                <th><div>Your Fare</div></th>
                <th><div>Alternate Fare</div></th>
                <th style="width:90px;"><div>Diffrence</div></th>
                <th><div>Fixed Penelity</div></th>
                <th><div>Total Amount</div></th>
              </tr>
            </thead>
            <tbody>
            <?php
            
			$qury="SELECT cart_order_id, new_price , ordertotal,cancel_date FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND handback='1' AND canceled_by ='".$_SESSION['company_id']."'";
		$record = $db->select($qury); 
			
			$TOTAL=0;
			for($k='0'; $k<sizeof($record); $k++){ 
			$serial2 = $k + 1;
			$diffrence=($record[$k]['new_price']-$record[$k]['ordertotal']);
			$sum=($record[$k]['new_price']-$record[$k]['ordertotal'])+$fixed_panility_charges;
			
			?>
                <tr height="50">
                    <td><div style="width:30px">&nbsp;<?php echo $serial2;?>&nbsp;</div></td>
                    <td align="center"><div style="width:90px">&nbsp;<?php echo $record[$k]['cart_order_id']; ?>&nbsp;</div></td>
                    <td align="center"><div style="width:90px">&nbsp;<?php echo $record[$k]['cancel_date']; ?>&nbsp;</div></td>
                    <td><div>&pound;&nbsp;<?php echo round($record[$k]['ordertotal']); ?></div></td>
                    <td><div>&pound;&nbsp;<?php echo round($record[$k]['new_price']); ?></div></td>
                    <td style="width:90px;"><div>&pound;&nbsp;<?php echo round($diffrence); ?></div></td>
                    <td><div>&pound;&nbsp;<?php echo round($fixed_panility_charges);?></div></td>
                    <td><div>&pound;&nbsp;<?php echo  round($sum);?></div></td>
                </tr>
            <?php 
				$TOTAL=$TOTAL+$sum;
				
			}?>
            <tr>
            <td height="50" align="right" colspan="8"><div style="padding-left:1050px">&nbsp;<strong>Total Amount:&nbsp;&nbsp; &pound;&nbsp;<?php echo round($TOTAL);?></strong>&nbsp;</div></td>
                            
            </tr>
            </tbody>
            </table>

</div>
 
 <?php }
 
 else if($booking_status==7){
	 
	 ?>    
 <div>
 <table width="200" >
  <tr height="30">
    <td colspan="2">Report Parameters&nbsp;</td>
    
  </tr>
  
  <tr height="30" >
    <td>Cab Office&nbsp; </td>
    <td>&nbsp;<?php echo ucfirst($user->getCompanyName($_SESSION['company_id']));?></td>
  </tr>
  <tr height="30" bgcolor="#E5E5E5">
    <td>Version&nbsp; </td>
    <td>&nbsp;1.1</td>
  </tr>
  <tr height="30">
    <td>Period&nbsp;</td>
    <td>&nbsp;<?php echo $display_date;?></td>
  </tr>
   <tr height="30" bgcolor="#E5E5E5">
    <td >Job Type&nbsp;</td>
    <td>&nbsp;Cancel</td>
  </tr>
</table>
<div style="height:20px;"></div>
 <table id="tb_mainView" class="table table-striped table-hover table-bordered align-center tb-v-align-middle">
            <thead>
              <tr>
              <th><div style="width:40px">&nbsp;Sr. No&nbsp;</div></th>
                <th ><div style="width:100px;">&nbsp;Booking Number&nbsp;</div></th>
                <th><div style="width:102px">&nbsp;Cancelation Date&nbsp;</div></th>
                <th><div>Your Fare</div></th>
                <th><div>Alternate Fare</div></th>
                <th><div>Total Amount</div></th>
              </tr>
            </thead>
          <tbody>
            <?php
            $qury="SELECT cart_order_id, new_price , ordertotal,cancel_date FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='2' AND handback='0' AND canceled_by ='".$_SESSION['company_id']."'";
		$record = $db->select($qury); 
			
			$TOTAL=0;
			
			for($k='0'; $k<sizeof($record); $k++){ 
				$serial2 = $k + 1;
				$sum=$record[$k]['ordertotal'];
			
			?>
           <tr height="50">
            <td><div style="width:30px">&nbsp;<?php echo $serial2;?>&nbsp;</div></td>
                <td align="center"><div style="width:90px">&nbsp;<?php echo $record[$k]['cart_order_id']; ?>&nbsp;</div></td>
                <td align="center"><div style="width:90px">&nbsp;<?php echo date("m-d-Y", strtotime($record[$k]['cancel_date'])); ?>&nbsp;</div></td>
                <td><div>&pound;&nbsp;<?php echo round($record[$k]['ordertotal']); ?></div></td>
                <td><div>&pound;&nbsp;<?php echo round($record[$k]['new_price']); ?></div></td>
                <td><div>&pound;&nbsp;<?php echo  round($sum);?></div></td>
            </tr>
           
		   <?php 
			$TOTAL=$TOTAL+$sum;
			}?>
            <tr>
            <td height="50" align="right" colspan="8"><div style="padding-left:1050px">&nbsp;<strong>Total Amount:&nbsp;&nbsp; &pound;&nbsp;<?php echo round($TOTAL);?></strong>&nbsp;</div></td>
                            
            </tr>
            </tbody>
            </table>






 </div>
 
 <?php }
 
 
 else{?>
 <!--*******************************************-->

 <table width="960px" border="1" style="margin:0 auto;">
  <tr style=" padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <th scope="col" style="background:#00F; font-size:18px; color: #fff; padding:10px 20px;">&nbsp;&nbsp;Description&nbsp;&nbsp;</th>
    <th scope="col" style="background:#00F; font-size:18px; color: #fff; padding: 10px 20px; text-align:center;">&nbsp;&nbsp;Amount&nbsp;</th>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Total value of jobs paid through credit card</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($TOTAL_CARD); ?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
<td style="padding: 10px 30px;">&nbsp;&nbsp;Total value of jobs paid through cash</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($TOTAL_CASH); ?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Total cash paid by Icabit against handback jobs</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($CASH_PAYED_BY_ICABIT_HANDBACK);?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Total value of jobs booked with Icabit</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($TOTAL_VALUE_ICABIT); ?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Total chargeable commission <?php echo $config['commission']?>%</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo $COMMISSION; ?></td>
  </tr>
  
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Fixed panelty charges receievable<br />&nbsp;&nbsp;(No. of jobs X Rate)</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo $FIXED_PANELTY ?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Booking switch cost<br />&nbsp;&nbsp;(Revised fare-Booking fair)</td>
    <td align="center" style="padding: 10px 30px;">&nbsp;&pound; <?php echo $BOOKING_SWITCH_COST;?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Credit card caharges<br  />&nbsp;&nbsp;50 p/Transaction</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($TOTAL_CARD_CHARGES); ?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;VAT on credit card charges</td>
    <td align="center" style="padding: 10px 30px;"><?php echo round($TOTAL_VAT_ON_CARD);?></td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($CHARGEABLE_EXPENSE);  ?></td>
  </tr>
  
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Credit card amount payable</td>
    <td align="center" style="padding: 10px 30px;">&pound; (<?php echo round($TOTAL_CARD); ?>)</td>
  </tr>
  <tr style="padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:14px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;Chargeable expense</td>
    <td align="center" style="padding: 10px 30px;">&pound; <?php echo round($CHARGEABLE_EXPENSE) ?></td>
  </tr>
  
    <?PHP
		if($FINAL_VALUE > 0){
			$text= 'Payable';
			$FINAL_VALUE;
		}else{
			$text= 'Receivable';
			$Test=str_replace('-','',$FINAL_VALUE);
			$FINAL_VALUE=$Test;
		}
	?>
  
  
  
  <tr style=" padding-left:10px;font-family:Arial, Helvetica, sans-serif; font-size:17px;">
    <td style="padding: 10px 30px;">&nbsp;&nbsp;<?php echo $text; ?></td>
    <td align="center" style="padding: 10px 30px;">&pound;(<?php echo round($FINAL_VALUE); ?>)</td>
  </tr>
</table>
 <div style="height:20px;"></div> 
<!--********************************************-->
<?php }?>
       
      
       
        
          <!--table main-->
          <?php if($dataCount > 0){?>
              <table id="tb_mainView"  class="table table-striped table-hover table-bordered align-center tb-v-align-middle">
                <tr align="center" style="height:70px; background-color:#fff;">
                <td colspan="2" style="overflow: hidden; padding: 20px;">
               <div align="center">
              &nbsp;&nbsp;&nbsp;&nbsp;  <a href="new_report_pdf_d.php?bid=bid&report_fromdate=<?php echo $date1;?>&report_todate=<?php echo $date2; ?>&booking_status=<?php echo $booking_status; ?><?php echo $queryStr;?>" style="background:#F00; padding:10px; border-radius:6px; color:#fff;  font-weight:normal; font-family:Arial,Helvetica,sans-serif; text-transform:capitalize;">
        <span style="margin-right:5px;">
        
        </span>
        print/ PDF</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="new_report_pdf_e.php?bid=bid&report_fromdate=<?php echo $date1;?>&report_todate=<?php echo $date2; ?>&booking_status=<?php echo $booking_status; ?><?php echo $queryStr;?>"
    		style="background:#09F; padding:10px; border-radius:6px; color:#fff;  font-weight:normal; font-family:Arial,Helvetica,sans-serif; text-transform:capitalize;">
    <span style="margin-right: 4px;"></span>
        Send Email</a>
        &nbsp;&nbsp;&nbsp;&nbsp;
       		<a href="javascript:void(0)" onclick="window.close()" style="background:#333; padding:10px; border-radius:6px; color:#fff; font-weight:normal; font-family:Arial,Helvetica,sans-serif; text-transform:capitalize;">
    	   	 <span style="margin-right: 4px;"></span>
       	   	 Close
            </a>
        &nbsp;&nbsp;&nbsp;&nbsp; 
       <?php 
			if($booking_status==2){
        		$excel='excel_report_handback.php';
        	}elseif($booking_status==7){
        		$excel='excel_report_cancel.php';
        	}else{
        		$excel='excel_report_all.php';
        	} 
		?>
           <!--<a href="<?php echo $excel;?>?bid=bid&report_fromdate=<?php echo $date1;?>&report_todate=<?php echo $date2; ?>&booking_status=<?php echo $booking_status; ?>" style="background-color: #ef2d27;
        border-radius: 50px;
        color: #fff;
        font-family: arial;
        font-size: 15px;
        font-weight: bold;
        height: auto;
        padding: 12px 20px;
        width: 229px;" target="_blank">
        <span style="margin-right:5px;"><img src="<?php echo $glob['storeURL']; ?>/images/download_icon.png"></span>
        Download Excel</a>-->
       </div>
        </td>
     </tr>
              </table>
          <?php } ?> 
      <?php }else{?>
          <div style="text-align:center; height:500px;">No Data Found</div>
      <?php } ?>
        </div>
      </div>
      <div id="future" class="tab-pane fade in active">
        <div style="margin-top: 10px;"> 
          <!--table id="tb_mainView" class="table table-striped table-hover table-bordered table-striped align-center tb-v-align-middle"-->
         <?php
         
			
			$sm=0;
			$all=0;
			if($data){
		 ?>
         
          <table id="tb_mainView" class="table table-striped table-hover table-bordered align-center tb-v-align-middle">
            <thead>
              <tr>
              <th style="vertical-align:middle;"><div style="width:60px; font-family:Arial, Helvetica, sans-serif; font-weight:normal; text-align:center;">&nbsp;Sr. No&nbsp;</div></th>
                <th style="vertical-align:middle;"><div style="width:60px; font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">&nbsp;Booking Number&nbsp;</div></th>
                <th style="vertical-align:middle;"><div style="width:60px; font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">&nbsp;Company&nbsp;</div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">User</div></th>
                <th style="vertical-align:middle;"><div style=" font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Amount</div></th>
                <th style="width:90px; font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center; vertical-align: middle;"><div>Full Price</div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Pay</div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Pickup</div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Drop</div></th>
                <th style="width:120px; vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center; vertical-align: middle;">Pickup Date</div></th>
                <th style="width:120px; vertical-align: middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Pickup Time</div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;"><i data-original-title="pax" class="icon icon-user" data-toggle="tooltip" title=""></i></div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;"><i data-original-title="bags" class="icon icon-briefcase" data-toggle="tooltip" title=""></i></div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Vehicle</div></th>
                <th  style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Driver Number</div></th>
                <th style="vertical-align:middle;"><div style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center;">Status</div></th>
              </tr>
            </thead>
            <tbody>
            <?php
            
			$k = 0;
			for($i=0; $i<sizeof($data); $i++){
				$serial = $i + 1;
			?>
            <tr style="font-family:Arial, Helvetica, sans-serif; font-weight:normal;text-align:center; font-size:13px; text-align:center; vertical-align: middle;">
                <?php
				if($data[$i]['posted_by'] !='' ){
					$link="&posted_by=f7";
				}
				?>
            <td><?php echo $serial; ?></td>
                <td><div style="width:60px"><?php echo $data[$i]['cart_order_id']; ?></div></td>
                 <td><div><?php echo ucfirst($user->getCompanyName($data[$i]['company_id'])) ; ?><br />
				<?php 
				if($data[$i]['posted_by'] == '0'){ echo 'Icabit Admin';}else
				 {echo  ucfirst($user->getCompanyName($data[$i]['posted_by'])) ;} ?>
				<?php if(getCompanyPhone($data[$i]['posted_by']) !=''){echo  '<br />'.getCompanyPhone($data[$i]['posted_by']);}?></div></td>
                
                
                <td><div><?php echo $user->getPessengerName($data[$i]['passenger_id']) ; ?></div></td>
                <?php if($data[$i]['new_price'] > 0){ $canceled_price ='<i class="fa fa-gbp"></i>'.$data[$i]['ordertotal'];}else{ $canceled_price='';} ?>
                <td ><div align="center"> <?php echo $canceled_price; ?></div></td>
                <?php if($data[$i]['new_price'] > 0){
					$sm = $data[$i]['new_price'];
				 $price= '<i class="fa fa-gbp"></i>'.$data[$i]['new_price'] ;}
				 else{ 
				 $price= '<i class="fa fa-gbp"></i>'.$data[$i]['ordertotal']; 
				  $sm  = $data[$i]['ordertotal'];
				 }?>
                
                <td><div align="center"> <?php echo $price; ?></div></td>
                <td ><div align="center"><i class="fa <?php getPaymentType($data[$i]['payment_type'])?>"></i> </div></td>
                <td style="padding: 10px 30px;"><div><?php echo $data[$i]['postfrom']; ?></div></td>
                <td style="padding: 10px 30px;"><div><?php echo $data[$i]['postto']; ?></div></td>
                
                <td ><div align="center"><?php echo date("m-d-Y", strtotime($data[$i]['pick_date'])); ?></div></td>
                <td ><div align="center"> <?php 
				 $time=explode(':',$data[$i]['pick_time']);
				
				echo $time[0].':'.$time[1]; ?></div></td>
                <td><div align="center"><?php echo $data[$i]['how_many']; ?></div></td>
                <td><div align="center"><?php echo $data[$i]['luggage']; ?></div></td>
                <td><div align="center"><?php echo getVehicle($data[$i]['how_many'],$data[$i]['luggage']); ?></div></td>
                
                <td>
                <div align="center"><?php 
				if(isset($data[$i]['driver_no']) && $data[$i]['driver_no'] == 0){ echo "N/A";}else{  echo $data[$i]['driver_no']; } ?></div></td>
               	<td>
                <?php echo getStatus($data[$i]['booking_status']); ?>
                </td>
              
               
              </tr>
            <?php  $all = $all + $sm; $k++;}
			}else{?>
                
            <?php }?>
             
              <tr id="tr_seperatorLast" class="hide">
                <td colspan="19"></td>
              </tr>
            </tbody>
          </table>
          <p style="width:300px; margin: 0 auto 20px;"><strong> Total Jobs:</strong> <?php echo $k;?></br><strong>Total Amount:</strong><?php echo ' <i class="fa fa-gbp"></i> '.$all;?></p>
        </div>
      </div>
   <!-- <script src="reports_scripts/js/jquery-1.10.2.js"></script>
    <script src="reports_scripts/js/jquery-ui.js"></script>
    <script src="reports_scripts/js/custom_script.js"></script>-->
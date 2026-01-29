<?php 
include("includes/includes.inc.php");
include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db);
$user = new user($db);
$report_fromdate=filter_input(INPUT_GET, 'report_fromdate'); 
$report_todate=filter_input(INPUT_GET, 'report_todate');
//$booking_status=filter_input(INPUT_POST, 'booking_status');	

	if(isset($_GET['driver'])){
		$driver=filter_input(INPUT_GET, 'driver');	
	}
	
	if(isset($_POST['driver'])){
		$driver=filter_input(INPUT_POST, 'driver');	
	}
	
	if(isset($_GET['bid'])){
		$bir=filter_input(INPUT_GET, 'bid');	
	}
	
	if(isset($_POST['bid'])){
		$bir=filter_input(INPUT_POST, 'bid');	
	}

 if($bir){
	$condition='';
	$condition2='';
	if($report_fromdate and $report_todate ){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$date2=date('Y-m-d', strtotime($report_todate));	
		$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		$condition2 .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		//$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
		$display_date = date("d",strtotime($date1)).','.date("M Y",strtotime($date1)).'  <strong>to</strong>  '.date("d",strtotime($date2)).','.date("M Y",strtotime($date2));
		
	}else if($report_fromdate and !$report_todate){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		$condition2 .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		$display_date= date("D M d  Y",strtotime($date1));
	}else if (!$report_fromdate and $report_todate){
		$date2=date('Y-m-d', strtotime($report_todate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		$condition2 .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		$display_date= date("D M d  Y",strtotime($date2));	
	}
	
		if($driver){
			$condition .=' AND driver_no='.$db->mySQLSafe($driver)."  AND company_id ='".$_SESSION['company_id']."'";
		}
	
		//************************************Pending jobs****************************************
	    $query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=0";
		$pending = $db->select($query1);
		if($pending[0]['toal_income'] >0){
			$TOTAL_PENDING_JOBS=$pending[0]['total_jobs'];
			$PENDING_AMOUNT = $pending[0]['toal_income']+$pending[0]['diffrence'];
		}
		
		//************************************Confirm jobs****************************************
		$query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=1";
		$confirm = $db->select($query2);
		if($confirm[0]['toal_income'] >0){
			$TOTAL_CONFIRM_JOBS=$confirm[0]['total_jobs'];
			$CONFIRM_AMOUNT = $confirm[0]['toal_income']+$confirm [0]['diffrence'];
		}
		//************************************Complete jobs****************************************
		$query4="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=3";
		$completed = $db->select($query4);
		if($completed[0]['toal_income'] >0){
			$TOTAL_COMPLETED_JOBS=$completed[0]['total_jobs'];
			$COMPLETED_AMOUNT = $completed[0]['toal_income']+$completed[0]['diffrence'];
		}
			
		$TOTAL_JOBS=$TOTAL_PENDING_JOBS+$TOTAL_CONFIRM_JOBS+$TOTAL_CANCELLED_JOBS+$TOTAL_COMPLETED_JOBS;
		$where='1=1 '.$condition;
		$data= $all_booking->getBookingDataReport($where); 
	    $dataCount = count($data);
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

<!--<link href="reports_scripts/css/jquery-ui.css" rel="stylesheet">
<link rel="stylesheet" href="<?php //echo $glob['storeURL']; ?>css/bootstrap.min.css">
<link href="<?php //echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">-->


<div style="height:10px; width:100%;"></div>
<div id="search_bookings" class="admin_register_form">
  
 <div class="clearfix"></div>
    <div style="margin-top: 30px; margin-bottom:30px;"> 
 	<?php if($bir){ ?> 
 <div>
 <div style="height:20px;"></div>
 </div>
 
 <!--*******************************************-->

 <table width="900px" border="1" style="margin:0 auto; border-collapse: collapse;">
 <tr style="height:50px; padding-left:10px; background-color:#0099CC; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:16px;
 font-weight:bold;">
    <td colspan="2" align="center" >&nbsp;&nbsp;Driver's Report&nbsp;&nbsp;</td> 
      </tr>
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Driver Number</td>
    <td align="center"> <?php echo $driver; ?> </td>
  </tr>
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Driver Name</td>
    <td align="center"> 
		<?php 
			echo ucfirst($user->getDriverName($driver,$_SESSION['company_id'])); ?> </td>
  </tr>
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Report Date</td>
    <td align="center"> <?php echo date('Y-m-d'); ?> </td>
  </tr>
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Report Period</td>
    <td align="center"> <?php echo $display_date; ?></td>
  </tr>
  
  <tr style="height:50px; padding-left:10px; background-color:#0099CC; color: #fff; font-family:Arial, Helvetica, sans-serif; font-size:16px; font-weight:bold;">
    	<td colspan="2" align="center" >&nbsp;&nbsp;Report Detail&nbsp;&nbsp;</td>
  </tr>
  
  
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Total Jobs</td>
    <td align="center"> <?php echo round($TOTAL_JOBS); ?> Jobs</td>
  </tr>
  
  <tr  style="height:50px; padding-left:10px;background-color:#0099CC; color:#fff; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px;">
		<td align="center" colspan="2">&nbsp;&nbsp;Pending Jobs</td>
  </tr>
  
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Pending Jobs Total</td>
    <td align="center"><?php echo round($TOTAL_PENDING_JOBS);?> Jobs</td>
  </tr>
 
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Pending Jobs Amount</td>
    <td align="center">&pound; <?php echo round($PENDING_AMOUNT);?></td>
  </tr>
 <!---->
 
 <tr  style="height:50px; padding-left:10px;background-color:#0099CC; color:#fff; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px;">
<td align="center" colspan="2">&nbsp;&nbsp;Confirm Jobs</td>
     </tr>
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Confirm Jobs Total</td>
    <td align="center"> <?php echo round($TOTAL_CONFIRM_JOBS);?> Jobs</td>
  </tr>
 
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Confirm Jobs Amount</td>
    <td align="center">&pound; <?php echo round($CONFIRM_AMOUNT);?></td>
  </tr>
  <!---->
 
  
  <tr  style="height:50px; padding-left:10px;background-color:#0099CC; color:#fff; font-weight:bold; font-family:Arial, Helvetica, sans-serif; font-size:16px;">
<td align="center" colspan="2">&nbsp;&nbsp;Completed Jobs</td>
     </tr>
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Completed Jobs Total</td>
    <td align="center"> <?php echo round($TOTAL_COMPLETED_JOBS);?> Jobs</td>
  </tr>
 
  <tr style="height:40px; padding-left:10px; font-family:Arial, Helvetica, sans-serif; font-size:13px;">
    <td>&nbsp;&nbsp;Completed Jobs Amount</td>
    <td align="center">&pound; <?php echo round($COMPLETED_AMOUNT);?></td>
  </tr>
 
 </table>
 
     <div style="height:20px;"></div> 

        <!--table main-->
          <?php if($TOTAL_JOBS > 0){?>
              <table id="tb_mainView"  class="table table-striped table-hover table-bordered align-center tb-v-align-middle">
                <tr align="center" style="height:70px; background-color:#fff;">
                <td colspan="2">
               <div align="center">
              &nbsp;&nbsp;&nbsp;&nbsp;  <a href="report_driver_pdf_d.php?bid=bid&report_fromdate=<?php echo $date1;?>&report_todate=<?php echo $date2; ?>&driver=<?php echo $driver; ?>" style="background:#F00; color:#fff; font-size:14px; font-weight:bold; text-align: center; padding:16px 20px; border-radius:8px; font-family:Arial, Helvetica, sans-serif; text-decoration:none;">
        <span style="margin-right:5px; display:none;"><img src="<?php echo $glob['storeURL']; ?>/images/download_icon.png"></span>
        Download PDF</a>&nbsp;&nbsp;&nbsp;&nbsp;
                <a href="report_driver_pdf_e.php?bid=bid&report_fromdate=<?php echo $date1;?>&report_todate=<?php echo $date2; ?>&driver=<?php echo $driver; ?>" style="background:#0099cc; color:#fff; font-size:14px; font-weight:bold; text-align: center; padding:16px 20px; border-radius:8px; font-family:Arial, Helvetica, sans-serif; text-decoration:none;">
    
    <span style="margin-right: 4px;"></span>
        Email PDF</a>
        	&nbsp;&nbsp;&nbsp;&nbsp;
        <a href="driver_exl.php?bid=bid&report_fromdate=<?php echo $date1;?>&report_todate=<?php echo $date2; ?>&driver=<?php echo $driver; ?>"  target="_blank" style="background:#333; color:#fff; font-size:14px; font-weight:bold; text-align: center; padding:16px 20px; border-radius:8px; font-family:Arial, Helvetica, sans-serif; text-decoration:none;">
        <span style="margin-right:5px;"></span>
        	Download Excel
        </a>
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
         	if($data){
		 ?>
         <table id="tb_mainView" class="table table-striped table-hover table-bordered align-center tb-v-align-middle" style="border-collapse: collapse; border:1px solid #000;">
            <thead>
              <tr>
              <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div style="width:60px">&nbsp;Sr. No&nbsp;</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div style="width:60px">&nbsp;Booking Number&nbsp;</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div style="width:60px">&nbsp;Company&nbsp;</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>User</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Amount</div></th>
                <th style="border-right:1px solid #000; width:90px; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Full Price</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Pay</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Pickup</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Drop</div></th>
                <th  style="border-right:1px solid #000; width:90px; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Pickup Date</div></th>
                <th style="border-right:1px solid #000; width:120px; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Pickup Time</div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div><i data-original-title="pax" class="icon icon-user" data-toggle="tooltip" title=""></i></div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div><i data-original-title="bags" class="icon icon-briefcase" data-toggle="tooltip" title=""></i></div></th>
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Vehicle</div></th>
                <!--<th><div>Assign Driver </div></th>-->
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;" ><div>Driver Number</div></th>
                
                <!--<th><div>Cancel Job</div></th>-->
                <th style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><div>Status</div></th>
                
                               
                
              </tr>
            </thead>
            <tbody>
            <?php
            for($i=0; $i<sizeof($data); $i++){
				$serial = $i + 1;
			?>
            <tr >
                <?php
                if($data[$i]['posted_by'] !='' ){
					$link="&posted_by=f7";
				}
				
				?>
            <td style="border-right:1px solid #000;text-align:center; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px;"><?php echo $serial; ?></td>
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div style="width:60px"><?php echo $data[$i]['cart_order_id']; ?></div></td>
                 <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div><?php echo ucfirst($user->getCompanyName($data[$i]['company_id'])) ; ?><br />
				<?php 
				if($data[$i]['posted_by'] == '0'){ echo 'Icabit Admin';}else
				 {echo  ucfirst($user->getCompanyName($data[$i]['posted_by'])) ;} ?>
				<?php if(getCompanyPhone($data[$i]['posted_by']) !=''){echo  '<br />'.getCompanyPhone($data[$i]['posted_by']);}?></div></td>
                
                
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div><?php echo $user->getPessengerName($data[$i]['passenger_id']) ; ?></div></td>
                <?php if($data[$i]['new_price'] > 0){ $canceled_price ='<i class="fa fa-gbp"></i>'.$data[$i]['ordertotal'];}else{ $canceled_price='';} ?>
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;" ><div align="center"> <?php echo $canceled_price; ?></div></td>
                <?php if($data[$i]['new_price'] > 0){ $price= '<i class="fa fa-gbp"></i>'.$data[$i]['new_price'] ;} else{ $price= '<i class="fa fa-gbp"></i>'.$data[$i]['ordertotal']; }?>
                
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"> <?php echo $price; ?></div></td>
                <td  style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"><i class="fa <?php getPaymentType($data[$i]['payment_type'])?>"></i> </div></td>
                <td style="padding:10px 30px; border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div><?php echo $data[$i]['postfrom']; ?></div></td>
                <td  style="padding:10px 30px; border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div><?php echo $data[$i]['postto']; ?></div></td>
                
                <td  style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"><?php echo $data[$i]['pick_date']; ?></div></td>
                <td  style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"> <?php 
				 $time=explode(':',$data[$i]['pick_time']);
				
				echo $time[0].':'.$time[1]; ?></div></td>
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"><?php echo $data[$i]['how_many']; ?></div></td>
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"><?php echo $data[$i]['luggage']; ?></div></td>
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;"><div align="center"><?php echo getVehicle($data[$i]['how_many'],$data[$i]['luggage']); ?></div></td>
               
               <!--<td><a class="group3" href="driver_detail.php?id=<?php echo $data[$i]['cart_order_id'];?>&url=<?php echo $_GET['parm'].'/'.$_GET['page_name'];?>"><span  style="background-color: #f59600; border-radius: 50px; color: #fff; font-family: arial; font-size: 12px; font-weight: bold; padding: 5px 10px; display:inline-block;width:82px;"  >Assign</span></a></td>-->
                
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;">
                  <div align="center">
				   <?php 
					 //echo getDriverName($data[$i]['driver_no']);
					 if(isset($data[$i]['driver_no']) && $data[$i]['driver_no'] == 0){ echo "N/A";}else{  echo $data[$i]['driver_no']; } ?></div>
                </td>
                
                <td style="border-right:1px solid #000; border-bottom:1px solid #000; font-family:Arial, Helvetica, sans-serif; font-size:13px; text-align:center;">
                 <?php echo getStatus($data[$i]['booking_status']);?>
               </td>
              </tr>
            <?php }
			}else{?>
           <?php }?>
            <tr id="tr_seperatorLast" class="hide">
             <td colspan="19"></td>
            </tr>
           </tbody>
         </table>
        </div>
      </div>
      
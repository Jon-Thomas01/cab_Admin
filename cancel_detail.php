<?php
include("includes/includes.inc.php");

include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db);
$user= new user($db);	
$daata= $all_booking->getBookingCancelDetail($_GET['id']);

	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo  ucfirst($user->getCompanyName($_SESSION['company_id']));?></title>
<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>--> 
<script src="<?php echo $glob['storeURL']; ?>js/jquery.min.js"></script>
<style>
.tbl{}
.tbl td{ padding-left:20px; font-size:10px; color:#333;}

</style>
</head>

<body>
<form action="controller/user_controller.php" method="post" style="text-align:center; padding-left:15px; padding-top:15px; padding-right:15px;">
<input type="hidden" name="action" id="action" value="change_status" /> 
<input type="hidden" name="booking_id" id="booking_id" value="<?php echo $_GET['id']; ?>" /> 
<input type="hidden" name="return_url" id="return_url" value="<?php echo $_GET['url']; ?>" /> 
<input type="hidden" name="company_id" id="company_id" value="<?php echo $_SESSION['company_id']; ?>" /> 
<input type="hidden" name="original_price" id="original_price" value="<?php echo $daata[0]['ordertotal']; ?>" />
<table border="1" class="tbl table table-striped table-hover table-bordered align-left tb-v-align-middle"  >
            <thead>
              <tr style="background-color:#DFDFDF">
                <th colspan="2"> <div>Booking details</div>
                 </th>
              </tr>
            </thead>
            <tbody>
           
              <tr>
                <td ><strong>Booking</strong></td>
                <td ><?php echo $daata[0]['cart_order_id']; ?></td>
              </tr>
              <!--<tr>
                <td >Company</td>
                <td ><?php echo $user->getCompanyName($daata[0]['company_id']) ; if($daata[$i]['posted_by'] == '0'){ echo 'Icabit Admin';}else
				 {echo  ucfirst($user->getCompanyName($daata[$i]['posted_by'])) ;} ?></td>
              </tr>-->
              <!--<tr>
                <td >Company Mobile</td>
                <td ><?php echo $user->getCompanyPhone($daata[0]['company_id']) ; ?><?php echo  getCompanyPhone($daata[0]['posted_by'])?></td>
              </tr>-->
              <tr>
                <td align="left"><strong>User Name</strong></td>
                <td id="td_user"><?php echo $user->getPessengerName($daata[0]['passenger_id']) ; ?></td>
              </tr>
              <tr>
                <td align="left"><strong>User Mobile</strong></td>
                <td id="td_user"><?php echo $user->getUserPhone($daata[0]['passenger_id']) ; ?></td>
              </tr>
             <?php if($daata[0]['new_price'] > 0){ $canceled_price ='<i class="fa fa-gbp"></i> '.$daata[0]['ordertotal'];}else{ $canceled_price='';} ?>
              <?php if($daata[0]['new_price'] > 0){ $price= '<i class="fa fa-gbp"></i> '.$daata[0]['new_price'] ;} else{ $price= '<i class="fa fa-gbp"></i>'.$daata[0]['ordertotal']; }?>
              
              <tr>
                <td id="td_label_amount"><strong>Amount</strong></td>
                <td id="td_amount"><?php echo $daata[0]['ordertotal'] ?></td>
              </tr>
              
             
              <tr id="tr_total_amount">
                <td id="td_label_total_amount"><strong>Full value</strong></td>
                 
                
                <td id="td_total_amount"> <?php echo $daata[0]['ordertotal'] ?></td>
              </tr>
              
              <tr id="tr_booking_fee" style="display:none">
                <td id="td_label_booking_fee">Booking fee</td>
                <td id="td_booking_fee"><?php echo $daata[0]['']; ?></td>
              </tr>
              <tr id="tr_admin_fee" style="display:none">
                <td id="td_label_admin_fee">Admin fee</td>
                <td id="td_admin_fee"><?php echo $daata[0]['']; ?></td>
              </tr>
              <tr id="tr_binfo" style="display:none">
                <td id="td_label_binfo"><strong>Booking info</strong></td>
                <td id="td_binfo"><?php echo $daata[0]['']; ?></td>
              </tr>
              <tr>
                <td><strong>Pay</strong></td>
                <td id="td_pay_type"><?php  if(isset($daata[0]['payment_type']) && $daata[0]['payment_type'] == 1){?>
                <img height="12" src="<?php echo $glob['storeURL']; ?>/images/glyphicons_267_credit_card.png" />
				<?php 
				}if(isset($daata[0]['payment_type']) && $daata[0]['payment_type'] == 2){?>
                <img height="12" src="<?php echo $glob['storeURL']; ?>/images/glyphicons_228_gbp.png" />
				<?php }if(isset($daata[0]['payment_type']) && $daata[0]['payment_type'] == 3){ ?>
                
                <img height="12" src="<?php echo $glob['storeURL']; ?>/images/images.png" />
                <?php  }?></td>
              </tr>
              <tr>
                <td><strong>Pickup Time</strong></td>
                <td id="td_pickupat"><?php echo str_replace(':00','',$daata[0]['pick_time']); ?></td>
              </tr>
              
              <tr>
                <td><strong>Pickup Date</strong></td>
                <td id="td_pickupat"><?php echo date("m-d-Y", strtotime($daata[0]['pick_date'])); ?></td>
              </tr>
              <tr>
                <td><strong>Pickup</strong></td>
                <td id="td_pickup_address"><?php echo $daata[0]['postfrom']; ?></td>
              </tr>
              <tr>
                <td><strong>Drop</strong></td>
                <td id="td_dropoff_address"><?php echo $daata[0]['postto']; ?></td>
              </tr>
              <!--<tr>
                <td>Additional stop</td>
                <td id="td_via_address"><?php echo $daata[0]['']; ?></td>
              </tr>-->
              <!--<tr>
                <td><strong>Pax</strong></td>
                <td id="td_pax"><?php echo $daata[0]['how_many']; ?></td>
              </tr>-->
              <!--<tr>
                <td><strong>Bags</strong></td>
                <td id="td_bags"><?php echo $daata[0]['luggage']; ?></td>
              </tr>-->
              <!--<tr>
                <td>Comments</td>
                <td id="td_comments"><?php echo $daata[0]['extra_comments']; ?></td>
              </tr>-->
              <!--<tr>
                <td>Extra</td>
                <td id="td_extras"><?php echo $daata[0]['']; ?></td>
              </tr>-->
              <!--<tr>
                <td>Vehicle</td>
                <td id="td_vehicle"><?php echo getVehicle($daata[0]['how_many'],$daata[0]['luggage']); ?></td>
              </tr>-->
              <!--<tr>
                <td>Driver no.</td>
                <td id="td_driver_no"><?php echo $daata[0]['driver_no']; ?></td>
              </tr>
              
              <tr>
                <td>Driver Number</td>
                <td id="td_driver_no"><?php echo $daata[0]['driver_no']; ?></td>
              </tr>-->
              <tr>
                <td><strong>Action/status</strong></td>
                <td id="td_action_status" class="align-center"><?php echo getStatus(2); ?></td>
              </tr>
             <?php 
				$disable = 'disabled="disabled"';
				if($daata[0]['booking_status'] != 2 and $daata[0]['company_id'] == $_SESSION['company_id'] ){ 
					$disable = '';
				}
			   ?>
             
              <tr>
                <td><strong>Driver</strong></td>
                <td id="td_driver_no">
                
                <?php 
				$driver_no = $daata[0]['driver_no'];
					$driver=$user->getDriverNo($_SESSION['company_id']);
				
				if(isset($driver_no) && $driver_no !='0'){
					echo $driver_no = $daata[0]['driver_no'];
				}else{
				?>
                    <select id="driver_number" name="driver_number" class="selectpicker" style="width: 200px; margin-bottom: 1px;" <?php echo $disable;?>>
                        	<option  value="">Add Driver</option>
                        <?php 
							for($i=0; $i<sizeof($driver); $i++){
							if($daata[0]['driver_no']== $driver[$i]['driver_no']){
								$selected= 'selected="selected"';
							}else{
								$selected='';
							}
                        ?>
                        	<option <?php echo $selected?>  value="<?php echo  $driver[$i]['driver_no'];?>"><?php echo $driver[$i]['name'];?></option>
                    <?php } ?>                    
                    </select>
                
                 <?php } ?> 
                </td>
              </tr>
              <tr>
                <td><strong>change Status</strong></td>
                <td id="td_action_status" class="align-center"> <?php $status=array('Pending','Confirmed','Canceled','Completed');?>
                    <select onchange="changeStatus(this.value)"  <?php echo $disable;?> id="booking_status" name="booking_status" class="selectpicker" style="width: 200px; margin-bottom: 1px;">
                     
                     
                    
                
                
                
                
                <option selected="selected"   value="2">Canceled</option>
                     
                     
                                      
                       </select>
                    </td>
              </tr>
              <tr id="handbackdiv" style="display:none">
                <td><strong>Handback Reasons</strong></td>
                <td id="td_action_status" class="align-center"> <?php $handback=array('Pessenger no show','Double booking','Customer canceled');?>
                    <select  <?php echo $disable;?> id="handback" name="handback" class="selectpicker" style="width: 200px; margin-bottom: 1px;">
                     <?php 
						for($i=0; $i<sizeof($handback); $i++){
							if($daata[0]['handback'] == $i){
								$selected4= 'selected="selected"';
							}else{
								$selected4='';
							}
						?>
                      	<option <?php echo $selected4;?>  value="<?php echo  $i;?>"><?php echo  $handback[$i]?></option>
                      <?php } ?>                    
                       </select>
                    </td>
              </tr>
              <tr>
                <td>Late Notification</td>
                <td style="text-align: center;"><br>
                  <div> Your cab is expected to be </div>
                  <div style="margin-top: 9px;">
                  <?php $time=array('2','5','10','20','30');?>
                    <select id="minuits" name="minuits" class="selectpicker" style="display: block; margin: 0 auto; width: 200px;" <?php echo $disable;?>>
                      <option value="">Select</option>
                      <?php for($i=0; $i<sizeof($time); $i++){
						  
						  if($daata[0]['minuits']== $time[$i]){
							 $selected2= 'selected="selected"';
							  }else{
								 $selected2='';
								  }
						  
						  ?>
                      
                      <option <?php echo $selected2;?> value="<?php echo  $time[$i]?>"><?php echo  $time[$i]?></option>
                      <?php } ?>                    
                       </select>
                    Minutes
                    Late </div>
                  <div style="margin-top: 4px; margin-bottom: 18px;"> Due To
                  <?php $due=array('weather','traffic','accident/broken down','reallocation')?>
                    <select id="due_to" name="due_to" class="selectpicker" style="display: block; margin: 0 auto; width: 200px;" <?php echo $disable;?>>
                     <option value="">Select</option>
                      <?php for($i=0; $i<sizeof($due); $i++){
						  
							if($daata[0]['due_to']== $due[$i]){
								$selected3= 'selected="selected"';
							}else{
								$selected3='';
							}
						  
						  ?>
                      
                      <option <?php echo $selected3;?>  value="<?php echo  $due[$i]?>"><?php echo  $due[$i]?></option>
                      <?php } ?>  
                    </select>
                  </div>
                 </td>
              </tr>
              <tr>
                <td>&nbsp;</td>
                <td  class="align-center">
                	<button type="submit" name="sub" value="Submit" class="btn btn-primary" <?php echo $disable;?>>Submit</button>
               
                </td>
              </tr>
              <?php //}?>
            </tbody>
          </table>
</form>          
</body>
</html>
<script>
function changeStatus(id){
			
			if(id == 2){
               alert("If you cancel this job you will not retrive it again!");			
			    $('#handbackdiv').show();
			}	}
</script>
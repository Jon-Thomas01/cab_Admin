<?php
include("includes/includes.inc.php");

include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db);
$user= new user($db);	
 $posted_by=filter_input(INPUT_GET, 'posted_by');

if($posted_by){
	 $daata= $all_booking->getBookingDataSingleOther($_GET['id']);
	}else{
		$daata= $all_booking->getBookingDataSingle($_GET['id']);
		
		}

	


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Assign Driver</title>
<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<style>
.tbl{}
.tbl td{ padding-left:20px;}
#cboxLoadedContent{
	width:100% !important;
	height:100% !important;	
}
#cboxContent{
	width:100% !important;
	height:100% !important;	
}

</style>
</head>

<body>
<form action="controller/user_controller.php" method="post" style="text-align:center; padding-left:15px; padding-top:15px; padding-right:15px;">
<input type="hidden" name="action" id="action" value="change_status" /> 
<input type="hidden" name="booking_id" id="booking_id" value="<?php echo $_GET['id']; ?>" /> 
<input type="hidden" name="return_url" id="return_url" value="<?php echo $_GET['url']; ?>" /> 
<input type="hidden" name="company_id" id="company_id" value="<?php echo $_SESSION['company_id']; ?>" /> 
<input type="hidden" name="original_price" id="original_price" value="<?php echo $daata[0]['ordertotal']; ?>" />
<table border="1" class="tbl table table-striped table-hover table-bordered align-center tb-v-align-middle"  >
            <thead>
              <tr style="background-color:#DFDFDF">
                <th colspan="2"> <div>Assign Driver</div>
                 </th>
              </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" >&nbsp;</td>
                
              </tr>
              
             <?php if($daata[0]['booking_status'] != 2 and $daata[0]['company_id'] == $_SESSION['company_id'] ){?>
             
              <tr>
                <td>Driver</td>
                <td id="td_driver_no">
                
                <?php 
				$driver=$user->getDriverNo($_SESSION['company_id']);
				
				
				?>
                <select id="driver_number" name="driver_number" class="selectpicker" style="width: 200px; margin-bottom: 1px;">
                      <option  value="">Add Driver</option>
                      <?php 
					  
					  for($i=0; $i<sizeof($driver); $i++){
						  
						  if($daata[0]['driver_no']== $driver[$i]['driver_no']){
							 $selected= 'selected="selected"';
							  }else{
								  $selected='';
								  }
						  
						  ?>
                      
                      <option <?php echo $selected?>  value="<?php echo  $driver[$i]['driver_no'];?>"><?php echo $driver[$i]['name'].' ('.$driver[$i]['driver_no'].')';?></option>
                      <?php } ?>                    
                       </select></td>
              </tr>
              <tr>
                <td>Change Status</td>
                <td id="td_action_status" class="align-center"> <?php $status=array('Pending','Confirmed','Canceled','Completed');?>
                    
                     <select onchange="changeStatus(this.value)"  id="booking_status" name="booking_status" class="selectpicker" style="width: 200px; margin-bottom: 1px;">
                     
                      <?php for($i=0; $i<sizeof($status); $i++){
						  if($daata[0]['booking_status'] == $i){
							 $selected1= 'selected="selected"';
							  }else{
								 $selected1='';
								  }
						  
						  
						  ?>
                      
                      <option <?php echo $selected1;?>  value="<?php echo  $i;?>"><?php echo  $status[$i]?></option>
                      <?php } ?>                    
                       
                                         
                       </select></td>
              </tr>
              <tr>
                <td>Late Notification</td>
                <td style="text-align: center;"><br>
                  <div> Your cab is expected to be </div>
                  <div style="margin-top: 9px;">
                  <?php $time=array('2','5','10','20','30');?>
                    <select id="minuits" name="minuits" class="selectpicker" style="display: block; margin: 0 auto; width: 200px;">
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
                  <div style="margin-top: 4px; margin-bottom: 18px;"> due to
                  <?php $due=array('weather','traffic','accident/broken down','reallocation')?>
                    <select id="due_to" name="due_to" class="selectpicker" style="display: block; margin: 0 auto; width: 200px;">
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
                	<button type="submit" name="sub" value="Submit" class="btn btn-primary">Submit</button>
               
                </td>
              </tr>
              <?php }?>
            </tbody>
          </table>
</form>          
</body>
</html>
<script>
function changeStatus(id){
			
			if(id == 2){
               alert("If you cancel this job you will not retrive it again!");			
			
			}	}
</script>
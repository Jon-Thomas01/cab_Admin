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
<title><?php echo  ucfirst($user->getCompanyName($_SESSION['company_id']));?></title>
<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/bootstrap.min.css">
<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>--> 
<script src="<?php echo $glob['storeURL']; ?>js/jquery.min.js"></script>
<style>
.tbl{}
.tbl td{ padding-left:20px; font-size:10px; color:#000;}

</style>
</head>

<body>
<form action="controller/user_controller.php" method="post" style="text-align:center; padding-left:15px; padding-top:15px; padding-right:15px;">
<input type="hidden" name="action" id="action" value="change_status" /> 
<input type="hidden" name="booking_id" id="booking_id" value="<?php echo $_GET['id']; ?>" /> 
<input type="hidden" name="return_url" id="return_url" value="<?php echo $_GET['url']; ?>" /> 
<input type="hidden" name="company_id" id="company_id" value="<?php echo $_SESSION['company_id']; ?>" /> 
<input type="hidden" name="original_price" id="original_price" value="<?php echo $daata[0]['ordertotal']; ?>" />

<input type="hidden" name="booking_status" id="booking_status" value="2" />



<table border="1" class="tbl table table-striped table-hover table-bordered align-center tb-v-align-middle"  >
            <thead>
              <tr style="background-color:#DFDFDF">
                <th colspan="2"> <div>Handback</div>
                 </th>
              </tr>
            </thead>
            <tbody>
           
           
              <tr >
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

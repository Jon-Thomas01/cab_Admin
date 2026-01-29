<?php
include("includes/includes.inc.php");
include("dompdf_config.inc.php");
include("classes/booking.php");
include("classes/user.php");

ob_start();
ob_get_contents();
ini_set("memory_limit", "16M");

//$_GET['id']=10021342;

$all_booking = new booking($db);
$user= new user($db);	

$daata= $all_booking->getBookingDataSingleCanceled($_GET['id']);
$i=0; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $user->getCompanyName($daata[$i]['company_id']); ?></title>
<!--<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">-->

</head>
<body>
<div align="center" style="text-align:center; margin:20px;">&pound;</div>
<?php 

$html = '

<table  style="width:300px;  background-color:#ecb500; margin:0px auto; padding:10px;">
<tr>
<td style="width:370px; min-height:150px; float:left;"><img src="images/logo.png" /></td>
<td style="width:230px; min-height:150px; float:right;">
<table width="200" border="0">
  <tr>
    <td><img src="images/address.png" /></td>
    <td><div style="width:190px;   font-family:Arial, Helvetica, sans-serif; font-size:13px;">Minibustransports, 353 High Road Leyton Esse, xE10 5NA. A division of SM Chauffeurs Ltd, </div></td>
  </tr>
  <tr>
    <td><img src="images/phone.png" /></td>
    <td><div style="width:190px;   font-family:Arial, Helvetica, sans-serif; font-size:13px; margin-top:8px;"> 02034755518</div>
</td>
  </tr>
  <tr>
    <td><img src="images/email.png" /></td>
    <td><div style="width:190px;   font-family:Arial, Helvetica, sans-serif; font-size:13px; margin-top:8px;">support@minibustransports.com </div></td>
  </tr>
</table>
</td>
</tr>
<tr>
<td colspan="2" style="width:300px; max-height:auto; min-height:50px; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:30px; text-align:center; padding-top:15px; float:left; background-color:#38393f;">'.$user->getCompanyName($daata[0]['company_id']).' </td>
</tr>
<tr><td  colspan="2" style="height:10px; width:300px;"></td></tr>
<tr>
<td colspan="2" style="width:300px; font-family:Arial, Helvetica, sans-serif; font-size:20px; font-weight:bold;">Booking details </td>
</tr>
<tr><td colspan="2" style="height:10px; width:300px;"></td></tr>
<tr >
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Booking</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['cart_order_id'].' </td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Company </td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$user->getCompanyName($daata[0]['company_id']).' </td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Company Mobile </td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$user->getCompanyPhone($daata[0]['company_id']).' '.getCompanyPhone($daata[0]['posted_by']).' </td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">User name</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$user->getPessengerName($daata[0]['passenger_id']).'  </td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">User/mobile</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$user->getUserPhone($daata[0]['passenger_id']).' </td>
</tr>';

if($daata[$i]['new_price'] > 0){ $canceled_price ='&pound;'.$daata[$i]['ordertotal'];}else{ $canceled_price='';}
$html .='<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Canceled Price </td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$canceled_price.' </td>
</tr>';
if($daata[$i]['new_price'] > 0){ $price= $daata[$i]['new_price'] ;} else{ $price= $daata[$i]['ordertotal']; }
$html .='<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Total amount</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">&pound;'.$price.'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Pay  </td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.getPaymentType($daata[0]['payment_type']).'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Pickup time  </td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.str_replace(':00','',$daata[0]['pick_time']).' '.$daata[0]['pick_date'].' </td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Pickup</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['postfrom'].'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Drop</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['postto'].'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Pax</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['how_many'].' </td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Bags</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['luggage'].'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Comments</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['extra_comments'].'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Extra</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;"></td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Vehicle</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.getVehicle($daata[0]['how_many'],$daata[0]['luggage']).'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Driver no.</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.$daata[0]['driver_no'].'</td>
</tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Action/status</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">';


if($daata[0]['booking_status']==0){
	$html .= "Pending";	
	} else if($daata[0]['booking_status']==1){  
	$html .= "Confirmed";	
	} else if($daata[0]['booking_status']==2){
	$html .= "Canceled";		
	}
	 else if($daata[0]['booking_status']==3){
	$html .= "Completed";		
	}


$html .='</td></tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">Driver</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:13px; font-family:Arial, Helvetica, sans-serif;">'.getDriverName($daata[0]['driver_no']).'</td>
</tr>

<tr><td colspan="2" style="height:10px; width:300px;"></td></tr>
<tr><td colspan="2" style="height:1px; background-color:#000; width:300px;"></td></tr>
<tr><td colspan="2" style="height:10px; width:300px;"></td></tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:20px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;">Amount :'.$price.'</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:20px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;"></td>
</tr>
<tr><td colspan="2" style="height:5px; width:300px;"></td></tr>
<tr>
<td style="width:150px; max-height:auto; min-height:25px; float:left; font-size:20px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;">Grand Total :'.$price.'</td>
<td style="width:450px; max-height:auto; min-height:25px; float:left; font-size:20px; font-weight:bold; font-family:Arial, Helvetica, sans-serif;"></td>
</tr>
<tr><td colspan="2" style="height:30px; width:300px;"></td></tr>
<tr>
<td colspan="2" style="width:300px; max-height:auto; min-height:30px; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:center; padding-top:15px; float:left; background-color:#38393f;">© 2015-icabit.com. All rights reserved.</td>
</tr>
</table>

';

	$dompdf = new DOMPDF();
	$dompdf->load_html($html);
	//echo $dompdf->load_html($html); exit; 
	$dompdf->render(); 
	
	$dompdf->output();
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	$dompdf->stream("icabit_$exec_time.pdf");
?>
</body>
       
</html>
<?php
include("includes/includes.inc.php");
include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db);
$user= new user($db);	
$daata= $all_booking->getBookingDataSingleCanceled($_GET['id']);
$i=0; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">
<style>
.tbl{ }
.tbl td{ padding-left:20px;}

</style>
</head>

<body>

		<table border="1" class="tbl table table-striped table-hover table-bordered align-center tb-v-align-middle"  >
            <thead>
              <tr style="background-color:#DFDFDF">
                <th colspan="2"> <div>Booking details</div>
                 </th>
              </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="2" >&nbsp;</td>
                
              </tr>
              <tr>
                <td >Booking</td>
                <td ><?php echo $daata[$i]['cart_order_id']; ?></td>
              </tr>
              <tr>
                <td >Company</td>
                <td ><?php echo $user->getCompanyName($daata[$i]['company_id']) ; ?></td>
              </tr>
              <tr>
                <td >Company Mobile</td>
                <td ><?php echo $user->getCompanyPhone($daata[$i]['company_id']) ; ?><?php echo  getCompanyPhone($daata[$i]['posted_by'])?></td>
              </tr>
              <tr>
                <td align="left">User name</td>
                <td id="td_user"><?php echo $user->getPessengerName($daata[$i]['passenger_id']) ; ?></td>
              </tr>
              <tr>
                <td align="left">User/mobile</td>
                <td id="td_user"><?php echo $user->getUserPhone($daata[$i]['passenger_id']) ; ?></td>
              </tr>
              <tr id="tr_full_value">
                <td id="td_label_full_value">Full value</td>
                <td id="td_full_value"><i class="fa fa-gbp"> <?php echo $daata[$i]['ordertotal']; ?></td>
              </tr>
              <tr id="tr_total_amount">
                <td id="td_label_total_amount">Total amount</td>
                <td id="td_total_amount"><i class="fa fa-gbp"> <?php echo $daata[$i]['ordertotal']; ?></td>
              </tr>
              <tr>
                <td id="td_label_amount">Amount</td>
                <td id="td_amount"><i class="fa fa-gbp"> <?php echo $daata[$i]['new_price']; ?></td>
              </tr>
              <tr id="tr_booking_fee" style="display:none">
                <td id="td_label_booking_fee">Booking fee</td>
                <td id="td_booking_fee"><?php echo $daata[$i]['']; ?></td>
              </tr>
              <tr id="tr_admin_fee" style="display:none">
                <td id="td_label_admin_fee">Admin fee</td>
                <td id="td_admin_fee"><?php echo $daata[$i]['']; ?></td>
              </tr>
              <tr id="tr_binfo" style="display:none">
                <td id="td_label_binfo">Booking info</td>
                <td id="td_binfo"><?php echo $daata[$i]['']; ?></td>
              </tr>
              <tr>
                <td>Pay</td>
                <td id="td_pay_type"><i class="fa <?php getPaymentType($daata[$i]['payment_type'])?>"></td>
              </tr>
              <tr>
                <td>Pickup time</td>
                <td id="td_pickupat"><?php echo str_replace(':00','',$daata[$i]['pick_time']); ?> <?php echo $daata[$i]['pick_date']; ?></td>
              </tr>
              <tr>
                <td>Pickup</td>
                <td id="td_pickup_address"><?php echo $daata[$i]['postfrom']; ?></td>
              </tr>
              <tr>
                <td>Drop</td>
                <td id="td_dropoff_address"><?php echo $daata[$i]['postto']; ?></td>
              </tr>
              <!--<tr>
                <td>Additional stop</td>
                <td id="td_via_address"><?php echo $daata[$i]['']; ?></td>
              </tr>-->
              <tr>
                <td>Pax</td>
                <td id="td_pax"><?php echo $daata[$i]['how_many']; ?></td>
              </tr>
              <tr>
                <td>Bags</td>
                <td id="td_bags"><?php echo $daata[$i]['luggage']; ?></td>
              </tr>
              <tr>
                <td>Comments</td>
                <td id="td_comments"><?php echo $daata[$i]['extra_comments']; ?></td>
              </tr>
              <tr>
                <td>Extra</td>
                <td id="td_extras"><?php echo $daata[$i]['']; ?></td>
              </tr>
              <tr>
                <td>Vehicle</td>
                <td id="td_vehicle"><?php echo getVehicle($daata[$i]['how_many'],$daata[$i]['luggage']); ?></td>
              </tr>
              <tr>
                <td>Driver no.</td>
                <td id="td_driver_no"><?php echo $daata[$i]['driver_no']; ?></td>
              </tr>
              <tr>
                <td>Action/status</td>
                <td id="td_action_status" class="align-center"><?php echo getStatus($daata[0]['booking_status']); ?></td>
              </tr>
              
            </tbody>
          
</body>
</html>
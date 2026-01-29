<?php
include("includes/includes.inc.php");

include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db); 
$user= new user($db);	
$where=' booking_id='.$db->mySQLSafe($_GET['id']).' and  `quote_company` ='.$db->mySQLSafe($_SESSION['company_id']).' AND `winning_company` !='.$db->mySQLSafe($_SESSION['company_id']);
$daata= $all_booking->getLostBookingSingleData($where);
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
.tbl{}
.tbl td{ padding-left:20px;}

</style>
</head>

<body>

<table width="400" height="588" border="1" class="tbl table table-striped table-hover table-bordered align-center tb-v-align-middle"  >
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
                <td ><?php echo $daata[$i]['booking_id']; ?></td>
              </tr>
              <tr>
                <td >Your Quote</td>
                <td ><?php echo $daata[$i]['company_price'] ; ?></td>
              </tr>
              <tr>
                <td >Winning Quote</td>
                <td ><?php echo $daata[$i]['winning_quote'];?></td>
              </tr>
               <tr>
                <td>Pick up</td>
                <td id="td_pickup_address"><?php echo $daata[$i]['pick_up']; ?></td>
              </tr>
              <tr>
                <td>Drop</td>
                <td id="td_dropoff_address"><?php echo $daata[$i]['drop']; ?></td>
              </tr>
              
             
              <tr>
                <td>Pickup time</td>
                <td id="td_pickupat"><?php echo $daata[$i]['pick_time']; ?></td>
              </tr>
              <tr>
                <td>Return</td>
                <td id="td_pickup_address"><?php if($daata[$i]['return'] !='0000-00-00'){echo $daata[$i]['return'];} ?></td>
              </tr>
             
              <!--<tr>
                <td>Additional stop</td>
                <td id="td_via_address"><?php echo $daata[$i]['']; ?></td>
              </tr>-->
              <tr>
                <td>Passengers</td>
                <td id="td_pax"><?php echo $daata[$i]['person']; ?></td>
              </tr>
              <tr>
                <td>Bags</td>
                <td id="td_bags"><?php echo $daata[$i]['luggage']; ?></td>
              </tr>
             <!-- <tr>
                <td>Comments</td>
                <td id="td_comments"><?php echo $daata[$i]['extra_comments']; ?></td>
              </tr>
              <tr>
                <td>Extra</td>
                <td id="td_extras"><?php echo $daata[$i]['']; ?></td>
              </tr>-->
              <tr>
                <td>Vehicle</td>
                <td id="td_vehicle"><?php echo $daata[$i]['vehicle']; ?></td>
              </tr>
             
             
             
            </tbody>
          </table>
         
</body>
</html>

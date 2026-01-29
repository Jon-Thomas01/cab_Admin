<?php
include("includes/includes.inc.php");

include("classes/booking.php");
include("classes/user.php");

$all_booking = new booking($db);
$user= new user($db);	

$daata= $all_booking->getBookingDataSingle($_GET['id']);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="css/style.css">
<link href="<?php echo $glob['storeURL']; ?>css/font-awesome.min.css" rel="stylesheet">
<style>
.tbl{width: 600px;height:600px;}
.tbl td{ padding-left:20px;}
#tb_mainView {
    width: 100%;
}
</style>
</head>

<body>

<?php 

?>
<div id="future" class="tab-pane fade in active">
        <div style="margin-top: 10px;"> 
          <!--table id="tb_mainView" class="table table-striped table-hover table-bordered table-striped align-center tb-v-align-middle"-->
          <table id="tb_mainView" class="table table-striped table-hover table-bordered align-center tb-v-align-middle">
           
            <thead>
            <tr >
             <td colspan="10"><?php echo getDriverName2($_GET['id']); ?></td>
             </tr>
              <tr>
                
                <th><div>User</div></th>
                <th><div>Amount</div></th>
                
                <th><div>Pay</div></th>
                <th><div>Pickup</div></th>
                <th><div>Drop</div></th>
                
                <th style="width:120px;"><div>Pickup time</div></th>
                <th><div><i data-original-title="pax" class="icon icon-user" data-toggle="tooltip" title=""></i></div></th>
                <th><div><i data-original-title="bags" class="icon icon-briefcase" data-toggle="tooltip" title=""></i></div></th>
                <th><div>Extra</div></th>
                <th><div>Vehicle</div></th>
               
               
                
              </tr>
            </thead>
            <tbody>
            
            <?php
            
			$rowsPerPage =40;
			$where=' company_id='.$db->mySQLSafe($_SESSION['company_id']).' AND  driver_no= '.$db->mySQLSafe($_GET['id']);
			$page         = (is_numeric($_GET['page'])) ? $_GET['page'] : 0;
			
			
			
           // $numrows=$all_booking->getTotalCount($where);
           
           // $pagination   = paginateUrlReWriting($numrows, $rowsPerPage, $page, "page");
			//$daata= $all_booking->getBookingData($where,$page,$rowsPerPage);
			$daata= $all_booking->getBookingDataDriver($where); 
			
			if($daata){
			for($i=0; $i<sizeof($daata); $i++){
			?>
            <tr id="data<?php echo $daata[$i]['cart_order_id']; ?>">
               
                <td><div><?php echo $user->getPessengerName($daata[$i]['passenger_id']) ; ?></div></td>
                <td><div><i class="fa fa-gbp"></i> <?php echo $daata[$i]['ordertotal']; ?></div></td>
               
                <td><div><i class="fa <?php getPaymentType($daata[$i]['payment_type'])?>"></i> </div></td>
                <td style="padding: 0 30px;"><div><?php echo $daata[$i]['postfrom']; ?></div></td>
                <td style="padding: 0 30px;"><div><?php echo $daata[$i]['postto']; ?></div></td>
                
                <td><div><?php echo $daata[$i]['pick_date']; ?>, <?php echo str_replace(':00','',$daata[$i]['pick_time']); ?></div></td>
                <td><div><?php echo $daata[$i]['how_many']; ?></div></td>
                <td><div><?php echo $daata[$i]['luggage']; ?></div></td>
                <td style="padding: 0 30px;"><div><?php echo $daata[$i]['']; ?></div></td>
                <td><div><?php echo getVehicle($daata[$i]['how_many'],$daata[$i]['luggage']); ?></div></td>
               
              </tr>
              
            <?php }}else{?>
            
             
              <tr>
                <td colspan="10" class="align-center"><h4>No Result</h4></td>
              </tr>
              <tr class="item-pager">
              <td colspan="10"><div class="pagination pull-right" style="margin: 10px 0 0; width: 360px;">
                  
                  <span style="margin-top: 8px; float: left; margin-right: 8px; color: #838383;">&nbsp;</span>
                  <ul>
                    <?php //echo $pagination;?>
                  </ul>
                </div></td>
            </tr>
              <?php }?>
              <tr id="tr_seperatorLast" class="hide">
                <td colspan="10"></td>
              </tr>
            </tbody>
          </table>
          
        </div>
      </div>
   </body>
</html>

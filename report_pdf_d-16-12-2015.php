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

//$_GET['id']=10021342;

$bir=filter_input(INPUT_GET, 'bid');
$report_fromdate=filter_input(INPUT_GET, 'report_fromdate');
$report_todate=filter_input(INPUT_GET, 'report_todate');
$booking_status=filter_input(INPUT_GET, 'booking_status');	

	if($bir){
		$condition='';
		if($report_fromdate and $report_todate ){
			$date1=date('Y-m-d', strtotime($report_fromdate));
			$date2=date('Y-m-d', strtotime($report_todate));	
			$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
			$display_date= date("D M d  Y",strtotime($date1)) .'---'.  date("D M d  Y",strtotime($date2));
		}else if($report_fromdate and !$report_todate){
			$date1=date('Y-m-d', strtotime($report_fromdate));
			$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
			$display_date= date("D M d  Y",strtotime($date1));
		}else if (!$report_fromdate and $report_todate){
			$date2=date('Y-m-d', strtotime($report_todate));
			$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
			$display_date= date("D M d  Y",strtotime($date2));	
		}
	 
		if($booking_status == 2){
			$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income FROM cab_order_sum
			WHERE 1=1 ".$condition." AND booking_status ='".$booking_status."' AND canceled_by ='".$_SESSION['company_id']."'";
			
			$query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
			WHERE 1=1 ".$condition." AND booking_status ='3' AND canceled_by ='".$_SESSION['company_id']."'";
			$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' and booking_status ='3' AND company_id ='".$_SESSION['company_id']."'";
			$results2 = $db->select($query2); 
		}else{		
			date("D M d  Y",strtotime($getdata[$i]['end_date']));
			$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
			FROM `cab_order_sum`
			WHERE 1=1  ".$condition."
			AND `booking_status` ='".$booking_status."'
			AND company_id ='".$_SESSION['company_id']."'";
			$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
		}


		 $results = $db->select($query);
		 $where=$qur;
		 $daata= $all_booking->getBookingDataReport($where); 
	}
	
	echo "<pre>";
	 print_r($daata);
	echo "</pre>";
	//die();
	echo "</br>";
//SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income, SUM(new_price-original_price)as diffrence FROM `cab_order_sum` WHERE 1=1 AND `booking_status` ='1' AND company_id ='2'


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
/*$html='
<table style="background-color:#ecb500; margin:0px auto; padding:10px;"  width="500" >
<tr>
<td style="width:370px; min-height:150px; float:left;"><img src="images/logo.png" /></td>
<td style="width:230px; min-height:150px; float:right;">
<table width="200" border="0">
  <tr>
    <td><img src="images/address.png" /></td>
    <td><div style="width:190px;font-family:Arial, Helvetica, sans-serif; font-size:13px;">'.$config["storeAddress"].'</div></td>
  </tr>
  <tr>
    <td><img src="images/phone.png" /></td>
    <td><div style="width:190px;   font-family:Arial, Helvetica, sans-serif; font-size:13px; margin-top:8px;">'.$config["storeContact"].'</div>
</td>
  </tr>
  <tr>
    <td><img src="images/email.png" /></td>
    <td><div style="width:190px;   font-family:Arial, Helvetica, sans-serif; font-size:13px; margin-top:8px;">'.$config["masterEmail"].'</div></td>
  </tr>
</table>
</td>
</tr>
       <tr><td colspan="2">&nbsp;</td></tr>    
            <tr>
            <td colspan="2" align="center" style="width:300px; max-height:auto; min-height:50px; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:30px; text-align:center; padding-top:5px; float:left; background-color:#38393f;"><span style="font-size:18px;">'.ucfirst($otherdata->getCompanyName($_SESSION['company_id'])).' Report</span></td>
            </tr>
			<tr><td colspan="2">&nbsp;</td></tr>
			';
          
             if($results == TRUE){ 
			 
			 
			 if($booking_status==0){
			 $type = "Pending";	
			} else if($booking_status==1){  
			 $type = "Confirmed";	
			} else if($booking_status==2){
			 $type = "Canceled";		
			}
			 else if($booking_status==3){
			 $type = "Completed";		
			}
			 
			 
			 
            $html .='<tr align="center">
				<td>Generated On</td>
				<td align="left">'. date("D M d  Y").'</td>
				</tr>
				<tr align="center">
				<td>Report Period </td>
				<td align="left">'. $display_date.'</td>
				</tr>
				<tr align="center"> 
				<td>Jobs Status</td>
				<td align="left">'. $type.'</td>
				</tr>
				<tr align="center"> 
				<td>Total Jobs</td>
				<td align="left">'. $results[0]['total_jobs'].'</td>
            </tr>';
            if($booking_status == 2){ 
			$html .=' 
			<tr>
				<td>Total Lose</td>
				<td align="left">'. round($results[0]['toal_income'],2).'</td>
			</tr>';
            
           if($results2[0]['total_jobs'] > 0){
            $html .=' 
			<tr>
				<td>Fine for Canceled Job</td>
				<td align="left">'.round(($results2[0]['toal_price']-$results2[0]['myprice']),2).'</td>
            </tr>';
			}
			}else{
            $html .='
			<tr align="center">
				<td >Total Ravenue</td>
				<td align="left">'. round($results[0]['toal_income']+($results[0]['diffrence']),2).'</td>
            </tr>';
			}
			$html .=' 
			<tr align="center">
            	<td colspan="2">&nbsp;</td>
            </tr>';
           }else{
          $html .='
		  	<tr align="center">
            	<td colspan="2">No Data Found</td>
            </tr>';
           }
          $html .='<tr align="center">
            <td colspan="2">';
			
			$html .='<div id="future" class="tab-pane fade in active">
       <div style="margin-top: 10px;"> ';
   if($daata){
	$html .='<table  style="background-color:#ecb500; margin:0px auto; padding:10px;"  id="tb_mainView" >
            <thead>
              <tr>
              <th><div>&nbsp;Sr. No&nbsp;</div></th>
				<th><div >&nbsp;Booking&nbsp;</div></th>
				<th><div>User</div></th>
				<th ><div>Price</div></th>
				<th><div>Pickup</div></th>
				<th><div>Drop</div></th>
				<th ><div>Time</div></th>
				<th><div>Vehicle</div></th>
              </tr>
            </thead>
            <tbody>';
      
	  for($i=0; $i<sizeof($daata); $i++){
		  $serial = $i + 1;
		  
				$html .='<tr id="data'.$daata[$i]['cart_order_id'].'">
				<td>'. $serial.'</td>';
				$html .='<td><div style="width:60px">'.$daata[$i]['cart_order_id'].'</div></td>';
				$html .='<td><div>'.$user->getPessengerName($daata[$i]['passenger_id']).'</div></td>';
				if($daata[$i]['new_price'] > 0){ $price= '<i class="fa fa-gbp"></i>'.$daata[$i]['new_price'] ;
				} else{ 
					$price= '<i class="fa fa-gbp"></i>'.$daata[$i]['ordertotal'].'';
				}
				
				$html .='<td><div>'. $price.'</div></td>';
				$html .='<td style="padding: 0 30px;"><div>'. $daata[$i]['postfrom'].'</div></td>';
				$html .='<td style="padding: 0 30px;"><div>'. $daata[$i]['postto'].'</div></td>';
				$html .='<td><div>'.$daata[$i]['pick_date'].', '. str_replace(':00','',$daata[$i]['pick_time']).'</div></td>';
				$html .='<td><div>'.getVehicle($daata[$i]['how_many'],$daata[$i]['luggage']).'</div></td>';
				$html .=' </tr>';
             }
			 
			
			 
			}else{
             $html .='<tr>
                	<td colspan="16" class="align-center"><h4>No Result</h4></td>
                </tr>';
             }
              
           $html .='<tr id="tr_seperatorLast" class="hide">
                <td colspan="18"></td>
              </tr>
            </tbody>
          </table>
          
        </div>
  </div></td>
            </tr>
			
			
			<tr>
		<td colspan="2" style="width:300px; max-height:auto; min-height:30px; color:#fff; font-family:Arial, Helvetica, sans-serif; font-size:12px; text-align:center; padding-top:15px; float:left; background-color:#38393f;">© 2015-icabit.com. All rights reserved.</td>
		</tr>
	 </table>

';*/
 
   
   $html .='<table style="width:560px;  border:1px solid #cecece; margin:0px auto;">
<tbody><tr style="height:150px;">
<td style="width:255px; height:150px; color:#333; line-height:21px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:14px; padding-top:10px;">
<b>Minicabster Limited</b><br>
<b>c/o Illuminate Agency</b><br>
<b>Henry Wood House</b><br>
<b>2 Riding House Street</b><br>
<b>London</b><br>
W1W 7FA<br>
Tel : 0203 475 6656<br>
Email : accounts@minicabster.co.uk
</td>
<td style="width:180px; height:150px; float:left; margin-top:10px;"><img src="images/logo.png">
</td>
</tr>
<tr style="height:55px;">
<td style="width:250px;height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tbody><tr>
<td style=" width:125px; font-weight:bolder; color:#333; border-radius:5px; height:40px; text-align:center; font-size:20px; font-weight:900px;">INVOICE:</td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; text-align:center; color:#333;">Page 1 </td>
</tr>
</tbody></table>
</td>

</tr>
<tr style="height:100px;">
<td style="width:250px; border:1px solid #cecece; height:152px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px; line-height:22px; color:#7a7a7a; margin-left:5px;">
SM Chauffuers<br>
Wellesley House, 1st Floor Suites<br>
102 Cranbrook Road<br>
Ilford<br>
Essex<br>
IG1 4NH<br>
</td>
<td style="width:260px; margin-left:10px; border:1px solid #c1ced9;; height:160px; float:left; font-family:Arial, Helvetica, sans-serif;  font-size:13px; ">
<table>
<tbody><tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Invoice No</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">47746</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Invoice Date</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">30/11/2015</td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Order No</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#333; height:35px; text-align:center; font-size:16px;"></td>
</tr>
<tr>
<td style=" width:125px; font-weight:bolder; background-color:#a3a3a3; color:#fff; height:35px; text-align:center; font-size:14px;">Account Ref</td>
<td style=" width:125px; font-weight:bolder; background-color:#f5f5f5; color:#7a7a7a; height:35px; text-align:center; font-size:12px;">644</td>
</tr>
</tbody></table>
</td>

</tr>
<tr style="height:65px; margin-top:10px;">
<td style="width:250px;height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:30px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tbody><tr>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; margin-bottom:20px; text-align:center; color:#707070; font-size:16px; font-weight:900px;">VAT Reg No:</td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:40px; text-align:center; margin-bottom:20px; color:#7a7a7a;">127723219 </td>
</tr>
</tbody></table>
</td>

</tr>
<tr style="height:40px;">
<td>
<table style="width:540px; background-color:#a3a3a3; color:#fff; margin-left:5px; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tbody><tr>
<td style="width:50px; font-weight:900;">Quantity</td>
<td style="width:120px; font-weight:900;">Description</td>
<td style="width:50px; font-weight:900;">Unit Price</td>
<td style="width:50px; font-weight:900;">Disc Amt</td>
<td style="width:50px; font-weight:900;">Net Amt</td>
<td style="width:50px; font-weight:900;">VAT %</td>
<td style="width:50px; font-weight:900;">VAT</td>
</tr>
</tbody></table>

<table style="width:540px; margin-left:5px; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; font-size:13px; ">

<tbody><tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:250px; text-align:center; color:#6e767d;">Total gross - 15th to 30th November 201</td>
<td style="width:120px; text-align:center; color:#6e767d;">731.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">20.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">0.00</td>
</tr>


<tr style="height:60px; padding-left:10px; ">
<td style="width:120px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">Total gross - 15th to 30th November 201</td>
<td style="width:50px; text-align:center; color:#6e767d;">731.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">20.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
</tr>


<tr style="height:60px; padding-left:10px; background-color:#f5f5f5;">
<td style="width:120px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">Total gross - 15th to 30th November 201</td>
<td style="width:50px; text-align:center; color:#6e767d;">731.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">20.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
</tr>


<tr style="height:60px;">
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:120px; text-align:center; color:#6e767d;">Total gross - 15th to 30th November 201</td>
<td style="width:50px; text-align:center; color:#6e767d;">731.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">20.00</td>
<td style="width:50px; text-align:center; color:#6e767d;">0.00</td>
</tr>
</tbody></table>
</td>
</tr>
<tr style="height:40px;">
<td></td>
</tr>
<tr>
<td style="border-top:1px solid #cecece;"></td>
</tr>
<tr style="height:30px;">
<td style="width:250px; height:20px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:20px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tbody><tr>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
</tr>
</tbody></table>
</td>

</tr>
<tr style="height:200px;">
<td style="width:250px; border:1px solid gray; margin-left:6px; height:130px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:10px; border:1px solid gray; height:130px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; padding-right:5px; font-size:13px; padding-top:10px;">

<table style="width:240px; color:#000; border-bottom:1px solid #7a7a7a; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tbody><tr>
<td style="width:250px; font-weight:900; color:#7a7a7a;">Total Net Amount</td>
<td style="width:120px; font-weight:900; color:#7a7a7a;">£</td>
<td style="width:50px; font-weight:900; color:#7a7a7a;">52.32</td>
</tr>
</tbody></table>
<table style="width:240px; color:#000; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;">
<tbody><tr>
<td style="width:250px; font-weight:900; color:#7a7a7a;">Total Net Amount</td>
<td style="width:120px; font-weight:900; color:#7a7a7a;">£</td>
<td style="width:50px; font-weight:900; color:#7a7a7a;">52.32</td>
</tr>
</tbody></table>
<table style="width:240px; background-color:#a3a3a3; color:#fff; height:40px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px;"><tbody><tr>
<td style="width:250px; font-weight:900;">Total Net Amount</td>
<td style="width:120px; font-weight:900;">£</td>
<td style="width:50px; font-weight:900;">52.32</td>
</tr>
</tbody></table>


</td>

</tr>
<tr style="height:30px;">
<td style="width:250px;height:10px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:10px; font-size:13px; padding-top:10px;">
</td>
<td style="width:250px; margin-left:20px; height:10px; border-radius:5px; float:left; font-family:Arial, Helvetica, sans-serif; padding-left:5px; padding-right:5px; font-size:13px; padding-top:10px;">
<table>
<tbody><tr>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
<td style=" width:125px; font-weight:bolder; border-radius:5px; height:10px; text-align:center;"></td>
</tr>
</tbody></table>
</td>

</tr>
</tbody>
</table>';
   
   

	$dompdf = new DOMPDF();
	$dompdf->load_html($html); 
	$dompdf->render(); 
	$dompdf->output();
	$time_post = microtime(true);
	$exec_time = $time_post - $time_pre;
	$dompdf->stream("icabit_$exec_time.pdf");// save pdf file.
?>


</body>



</html>
<?php
/**
 * PHPExcel
 *
 * Copyright (C) 2006 - 2014 PHPExcel
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @category   PHPExcel
 * @package    PHPExcel
 * @copyright  Copyright (c) 2006 - 2014 PHPExcel (http://www.codeplex.com/PHPExcel)
 * @license    http://www.gnu.org/licenses/old-licenses/lgpl-2.1.txt	LGPL
 * @version    1.8.0, 2014-03-02
 */

/** Error reporting */
date_default_timezone_set('Europe/London');
define('EOL',(PHP_SAPI == 'cli') ? PHP_EOL : '<br />');
date_default_timezone_set('Europe/London');


include("includes/includes.inc.php");
include("dompdf_config.inc.php");
include("classes/setting_classes/setting.php");
include("classes/booking.php");
include("classes/user.php");

$otherdata = new setting($db);
$all_booking = new booking($db);
$user = new user($db);	



require_once dirname(__FILE__) . '/classes/excel/PHPExcel.php';
$objPHPExcel = new PHPExcel();
$credit_vat= 0.03;


$companyName  = $user->getCompanyName($_SESSION['company_id']);
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
		//$display_date= date("D M d  Y",strtotime($date1)) .'---'.  date("D M d  Y",strtotime($date2));
		$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
	}else if($report_fromdate and !$report_todate){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		//$display_date= date("D M d  Y",strtotime($date1));
		$display_date= date("D M d  Y",strtotime($date1));
	}else if (!$report_fromdate and $report_todate){
		$date2=date('Y-m-d', strtotime($report_todate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date2));
		$display_date= date("D M d  Y",strtotime($date2));	
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
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
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
		$credit_vat=0.03;
		$fixed_panility_charges=2;
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
        
		
   
   $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
   	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()
	       
			->setCellValue('C2', 'Cab Office')
			->setCellValue('D2', ucfirst($companyName))
			->setCellValue('C3', "Version")
			->setCellValue('D3', "1.1")
			->setCellValue('C4', "Period")
			->setCellValue('D4', $display_date)
			->setCellValue('C5', "Job type")
			->setCellValue('D5', "Cancel");
			
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setWidth(100);
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A10', " Sr. No ")
									->setCellValue('B10', "Booking Number")
									->setCellValue('C10', "Cancelation Date  ")
									->setCellValue('D10', "Your Fare")
									->setCellValue('E10', "Alternate Fare")
									
									->setCellValue('F10', "Total Amount");
									
	 $i=11;
     $qury="SELECT cart_order_id, new_price , ordertotal,cancel_date FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='2' AND handback='0' AND canceled_by ='".$_SESSION['company_id']."'";
		$record = $db->select($qury); 
			
			$TOTAL=0;
			
			for($k='0'; $k<sizeof($record); $k++){ 
			
			$serial2 = $k + 1;
			
			//$diffrence=($record[$k]['new_price']-$record[$k]['ordertotal']);
			$sum=$record[$k]['ordertotal'];
			 	
				
				
				
				
				$objPHPExcel->getActiveSheet()
				->setCellValue('A' . $i, $serial2)
				->setCellValue('B' . $i, $record[$k]['cart_order_id'])
				->setCellValue('C' . $i, date("m-d-Y", strtotime($record[$k]['cancel_date'])))
				->setCellValue('D' . $i, round($record[$k]['ordertotal']))
				->setCellValue('E' . $i, round($record[$k]['new_price']))
				
				->setCellValue('F' . $i, round($sum));
				
				
				$objPHPExcel->setActiveSheetIndex(0);
				
				/*if($status == 1){	
					$sharedStyle1 = new PHPExcel_Style();
					$sharedStyle1->applyFromArray(
					
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'=> array('argb' => $color1)),'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
					   )
					));
					$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "N$i:N$i");
				}else{
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color2)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "N$i:N$i");	
				
				
				}*/
				
			$i++;	
		$TOTAL=$TOTAL+$sum;
		
		}
		$nextline=$i+1;
		$objPHPExcel->getActiveSheet()->setCellValue('F'.$nextline, " Total Amount: ".round($TOTAL));
		
		$nextline=$i+10;
		//******************************************************************
		$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setWidth(100);
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A'.$nextline, " Sr. No ")
									->setCellValue('B'.$nextline, "Booking Number")
									->setCellValue('C'.$nextline, "Company ")
									->setCellValue('D'.$nextline, "User")
									->setCellValue('E'.$nextline, "Amount")
									->setCellValue('F'.$nextline, "Price")
									->setCellValue('G'.$nextline, "Pickup")
									->setCellValue('H'.$nextline, "Drop")
									->setCellValue('I'.$nextline, "Pickup Date")
									->setCellValue('J'.$nextline, "Pickup Time")
									->setCellValue('K'.$nextline, "Persons")
									->setCellValue('L'.$nextline, "Bags")
									->setCellValue('M'.$nextline, "Vehicle")
									->setCellValue('N'.$nextline, "Status");
	 $i=$nextline+1;
	 
	 
     for($j=0; $j < sizeof($data); $j++){
			 	$serial = $j + 1;	
				if($data[$j]['new_price'] > 0){ 
					$price = $data[$j]['new_price'] ;
				} else{ 
					$price= $data[$j]['ordertotal'];
				}
				
				$status = $data[$j]['booking_status'];
				if($status == 1){
					$color1 = 'ed214a';
					$statusText = 'confirmed';
				}else if($status == 2){
					$color2 = '97f415';
					$statusText = 'canceled';
				}else if($status == 3){
					$color2 = 'ed214a';
					$statusText = 'Completed';
					
					}
				
				if($data[$j]['new_price'] > 0){
					$canceled_price =$data[$j]['ordertotal'];
				}else{ 
					$canceled_price='';
				}
				
				if($data[$j]['new_price'] > 0){ 
					$price= $data[$j]['new_price']; 
				}else{
					$price= $data[$j]['ordertotal']; }
				
				$time = explode(':',$data[$j]['pick_time']);
				$objPHPExcel->getActiveSheet()
				->setCellValue('A' . $i, $serial)
				->setCellValue('B' . $i, $data[$j]['cart_order_id'])
				->setCellValue('C' . $i, ucfirst($user->getCompanyName($data[$j]['company_id'])))
				->setCellValue('D' . $i, $user->getPessengerName($data[$j]['passenger_id']))
				->setCellValue('E' . $i, $canceled_price)
				->setCellValue('F' . $i, $price)
				->setCellValue('G' . $i, $data[$j]['postfrom'])
				->setCellValue('H' . $i, $data[$j]['postto'])
				->setCellValue('I' . $i, date("m-d-Y", strtotime($data[$j]['pick_date'])))
				->setCellValue('J' . $i, $time[0].':'.$time[1])
				->setCellValue('K' . $i, $data[$j]['how_many'])
				->setCellValue('L' . $i, $data[$j]['luggage'])
				->setCellValue('M' . $i, getVehicle($data[$j]['how_many'],$data[$j]['luggage']))
				->setCellValue('N' . $i, $statusText);
				
				$objPHPExcel->setActiveSheetIndex(0);
				
				if($status == 1){	
					$sharedStyle1 = new PHPExcel_Style();
					$sharedStyle1->applyFromArray(
					
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'=> array('argb' => $color1)),'borders' => array(
						'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'		=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM)
					   )
					));
					$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "N$i:N$i");
				}else{
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color2)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "N$i:N$i");	
				}
				
			$i++;	
		}
		
		
		
		
		//******************************************************************
		
			$objPHPExcel->getActiveSheet()->getStyle('A'.$nextline.':N'.$nextline.'')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle('A10:H10')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$nextline.':N'.$nextline.'')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			
			$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setAutoSize(true);
		
			$objPHPExcel->getActiveSheet()->getStyle('A2:B2')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle('A16:A16')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			$sharedStyle3 = new PHPExcel_Style();
			$sharedStyle4 = new PHPExcel_Style();
			$sharedStyle5 = new PHPExcel_Style();
			$sharedStyle6 = new PHPExcel_Style();
			$sharedStyle7 = new PHPExcel_Style();
			$sharedStyle8 = new PHPExcel_Style();
			$sharedStyle3->applyFromArray(array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,'color'	=> array('argb' => 'E32D40')),));
			$sharedStyle4->applyFromArray(array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,'color'	=> array('argb' => '8248DB')),));
			$sharedStyle5->applyFromArray(array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,'color'	=> array('argb' => '3A79BD')),));
			$sharedStyle8->applyFromArray(array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,'color'	=> array('argb' => '070808')),));
			
				$sharedStyle6->applyFromArray(
					array('fill' 	=> array(
					'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
					'color'		=> array('argb' => 'F5F50C'),
					'bold'      => true
					),
				));
				
				$sharedStyle7->applyFromArray(
					array('fill' 	=> array(
					'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
					'color'		=> array('argb' => 'F5F50C')
					),
				'borders' => array(
					'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THICK),
					'bold'      => true
					),
				));
				
				
				$sharedStyle8->applyFromArray(
					array('fill' 	=> array(
					'type'		=> PHPExcel_Style_Fill::FILL_SOLID,
					'color'		=> array('argb' => '8248DB') 
					),
				'borders' => array(
					'bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'left'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'right'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'top'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
					'bold'      => true
					),
				));
				
					
			/*$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "B6:D6");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "B7:B7");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "D6:B7");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "D7:B7");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle5, "B18:B18");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle5, "D18:D18");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "B21:D22");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle6, "B26:D26");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle7, "B27:D27");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle8, "G33:G33");	*/
			
			
			$report=str_replace(' ','_',strtolower($companyName));
			
			$callStartTime = $report.'_'.$date1.'_to_'.$date2.'_';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(str_replace('excel_report_cancel.php', ''.$callStartTime.'excel_report_cancel.xlsx', __FILE__));
		?>
    
      		<script>window.location="<?php echo $callStartTime;?>excel_report_cancel.xlsx";</script> 
 	 







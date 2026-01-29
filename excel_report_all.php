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
		$query="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='".$booking_status."' AND canceled_by ='".$_SESSION['company_id']."'";
	
		$query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND canceled_by ='".$_SESSION['company_id']."'";
		$results2 = $db->select($query2); 
		$qur=" 1=1 ".$condition." AND booking_status ='".$booking_status."' and booking_status ='3' AND company_id ='".$_SESSION['company_id']."'";
	}	else{		
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
		$FIXED_PANELTY = $TOTAL_FIXED_ON_CANCEL;
		$BOOKING_SWITCH_COST=$TOTAL_HANDBACK_AMOUNT;
		$TOTAL_CARD_CHARGES=$CARD_CHARGES_TOTAL;
        $TOTAL_VAT_ON_CARD=$card_vat_total;
		$CHARGEABLE_EXPENSE=$COMMISSION+$FIXED_PANELTY+$BOOKING_SWITCH_COST+$TOTAL_CARD_CHARGES+$TOTAL_VAT_ON_CARD;
		$FINAL_VALUE=$TOTAL_CARD-$CHARGEABLE_EXPENSE;
  
  
  
		
		}
        
		$otherAdjustment =0;
		$valueOfJobCompleted = $total_income_gross - ($canceled_amount  + $otherAdjustment); 
		$commissionCalculated =  ($valueOfJobCompleted * $config['commission'])/100;
		$vatOnCommesioned  = ($commissionCalculated * $config['vat_amount'])/100;
		
		
		// Total Value of Jobs Booked with iCabit
		$totalValueOfJobsBooked = '';
		
		// minus Value of Cancelled Jobs
		$minusValueOfCancelledJobs = '';
		
		// minus Other Adjustments (see detail below)
		$minusOtherAdjustment = '';
		
		// Value of Completed Jobs with iCabit
		$valueOfCompletedJobs='';
		
		//Commission on Completed Jobs
		$commissionOnCompletedJobs ='';
		
		// VAT on Commission
		$vatOnCommission ='';
		
		// Credit Card Charges (50p per credit card transaction)
		$creditCardCharges_ = '';
		// VAT on Credit Card charges
		$vatOnCreditCardCharges ='';
		
		// Total Invoice for this Period (owed to iCabit)
		$totalInvoiceForThisPeriod = '';
		
		// Credit card charges (paid by customers)
		$creditCardChargesPaidByCustomers ='';
		
		// Booking fees (paid by customers)
		$bookingFeesPaidByCustomers ='';
		// Invoice for this period (as above)
		$invoiceForThisPeriod='';
		
		// plus/minus Outstanding balance on your account
		$invoiceForThisPeriod = '';
		
		// TOTAL YOU OWE TO MINICABSTER 
		$totalYouOweToiCabit = '';
		
		//TOTAL MINICABSTER OWES YOU
		$totalIcabitOwesYou  ='';
		$minusOutstanding ='';
		$bookingFeesByCustomer ='';
		$valueOfVoucherAcecpeted ='';
		
		$invoiceForThisPeriod = $card_vat_total + $total_card_gross + $vatOnCommesioned + $commissionCalculated;
		$totalcabsowns =  $total_card_charges + $valueOfVoucherAcecpeted + $total_card_gross + $bookingFeesByCustomer + $invoiceForThisPeriod  + $minusOutstanding; 
		
		
		if($FINAL_VALUE > 0){
		  
		  $text= 'Payable';
		  $FINAL_VALUE;
		  
		  
		  }else{
			  $text= 'Receivable';
			  $Test=str_replace('-','',$FINAL_VALUE);
			  
			  $FINAL_VALUE=$Test;
			  }
		
		
   
   $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
							 ->setLastModifiedBy("Maarten Balliauw")
							 ->setTitle("Office 2007 XLSX Test Document")
							 ->setSubject("Office 2007 XLSX Test Document")
							 ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("Test result file");
   	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A2', " Calculation of what you owe iCabit ")
			->setCellValue('C2', ucfirst($companyName))
			->setCellValue('B5', "Total value of jobs Paid through C.card")
			->setCellValue('B6', "Total value of jobs Paid through cash")
			->setCellValue('B7', "Total cash paid by Icabit against cancelled jobs")
			->setCellValue('B8', "Total value of jobs booked with Icabit")
			->setCellValue('B10', "Total chargeable commission ".$config['commission']."%")
			->setCellValue('B11', "Fixed panelty charges receievable")
			->setCellValue('B12', "Booking switch cost")
			->setCellValue('B13', "Credit card caharges")
			->setCellValue('B14', "VAT on credit card charges")
			->setCellValue('A16', "")
			->setCellValue('A17', "Credit card amount payable")
			->setCellValue('B18', "Chargeable expense")
			->setCellValue('B19', $text)
			
			->setCellValue('D5', "£ ".round($TOTAL_CARD))
			->setCellValue('D6', "£ ".round($TOTAL_CASH))
			->setCellValue('D7', "£ ".round($CASH_PAYED_BY_ICABIT_HANDBACK))
			->setCellValue('D8', "£ ".round($TOTAL_VALUE_ICABIT))
			->setCellValue('D10', "£ ".round($COMMISSION))
			->setCellValue('D11', "£ ". round($FIXED_PANELTY))
			->setCellValue('D12', "£ ". round($BOOKING_SWITCH_COST))
			->setCellValue('D13', "£ " .round($TOTAL_CARD_CHARGES))
			->setCellValue('D14', "£ " .round($TOTAL_VAT_ON_CARD) )
			
			->setCellValue('D16', "£ ".round($CHARGEABLE_EXPENSE))
			->setCellValue('D17', "£ ".round($TOTAL_CARD))
			->setCellValue('D18', "£ ".round($CHARGEABLE_EXPENSE))
			->setCellValue('D19', "£ ".round($FINAL_VALUE));
		   
		   
		   
		   
		   
	
	$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setAutoSize(true);
	$objPHPExcel->getActiveSheet()->getColumnDimension('A2')->setWidth(100);
	$objPHPExcel->setActiveSheetIndex(0);
	$objPHPExcel->getActiveSheet()->setCellValue('A34', " Sr. No ")
									->setCellValue('B34', "Booking Number")
									->setCellValue('C34', "Company ")
									->setCellValue('D34', "User")
									->setCellValue('E34', "Amount")
									->setCellValue('F34', "Price")
									->setCellValue('G34', "Pickup")
									->setCellValue('H34', "Drop")
									->setCellValue('I34', "Pickup Date")
									->setCellValue('J34', "Pickup Time")
									->setCellValue('K34', "Persons")
									->setCellValue('L34', "Bags")
									->setCellValue('M34', "Vehicle")
									->setCellValue('N34', "Status");
	 $i=35;
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
					$color3 = 'CCCC00';
					$statusText = 'completed';
				}else if($status == 0){
					
					$color0 = '000000';
					$statusText = 'pending';
					
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
				}else if($status == 2){
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color2)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "N$i:N$i");	
				}
				else if($status == 3){
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color3)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "N$i:N$i");	
				}
				else if($status == 0){
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color0)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "N$i:N$i");	
				}
				
			$i++;	
		}
		
			$objPHPExcel->getActiveSheet()->getStyle('A34:N34')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle('A34:N34')->applyFromArray(
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
				
					
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "B6:D6");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "B7:B7");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle3, "D6:B7");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "D7:B7");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle5, "B18:B18");	
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle5, "D18:D18");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle4, "B21:D22");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle6, "B26:D26");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle7, "B27:D27");
			$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle8, "G33:G33");	
			
			
			$report=str_replace(' ','_',strtolower($companyName));
			$callStartTime = $report.'_'.$date1.'_to_'.$date2.'_';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(str_replace('excel_report_all.php', ''.$callStartTime.'excel_report_all.xlsx', __FILE__));
		?>
    
      		<script>window.location="<?php echo $callStartTime;?>excel_report_all.xlsx";</script> 
 	 







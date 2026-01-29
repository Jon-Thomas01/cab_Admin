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
		 $data= $all_booking->getBookingDataReport($where); 
		 
		 
	##########################################Calculation Sum ########################################## 
		    
	if($report_fromdate and $report_todate ){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$date2=date('Y-m-d', strtotime($report_todate));	
		$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		$otherAdjustment = 0;//$config['other_adjustment']; echo "</br>";
		$creditCardCharges = $config['credit_card_charges'];
		
		//die();
		$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
		}else if($report_fromdate and !$report_todate){
			$date1=date('Y-m-d', strtotime($report_fromdate));
			$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
			$display_date= date("D M d  Y",strtotime($date1));
		}else if (!$report_fromdate and $report_todate){
			$date2=date('Y-m-d', strtotime($report_todate));
			$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
			$display_date= date("D M d  Y",strtotime($date2));	
		}
		
		$query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition." AND `booking_status` ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."'";
		$results = $db->select($query1);
		
		$deper=$results[0]['toal_income'];
		if($results[0]['toal_income'] > 0){
			$results[0]['total_jobs'];
			$total_income_gross= $results[0]['toal_income']+$results[0]['diffrence']; 
			$commission = round(($total_income_gross*$config['commission'])/100,2); 
			$vat = round(($commission*$config['vat_amount'])/100,2); 
		}
		
	   $query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `new_price` ) AS toal_price , SUM(ordertotal) myprice FROM cab_order_sum
		WHERE 1=1 ".$condition." AND booking_status ='3' AND canceled_by ='".$_SESSION['company_id']."' AND company_id ='".$_SESSION['company_id']."' "; // new added
		$results2 = $db->select($query2); 
		if($results2[0]['toal_price'] >0 ){
			$canceled_amount = $results2[0]['toal_price']-$results2[0]['myprice'];
		}
		
		$query3="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition." AND `booking_status` ='".$booking_status."' AND company_id ='".$_SESSION['company_id']."' and payment_type=1";
		$results3 = $db->select($query3);
		$paymentViaCreditCard = $results3[0]['total_jobs'];
		// jobs via credit_card =  total_jobs
	
		
		if($results3[0]['toal_income'] >0){
		$total_card_gross = $results3[0]['toal_income']+$results3[0]['diffrence']; 
		$card_vat_total = round(($total_card_gross*$credit_vat)/100,2); 
		//$cancelation; 
		
		$TOALJOBS = $results3[0]['total_jobs'];
		$total_card_charges = round($results3[0]['toal_income']+$results3[0]['diffrence'],2); 
		
		/*$total_card_gross = round(($results3[0]['toal_income']+$results3[0]['diffrence'])/$TOALJOBS,2);
		$card_vat_total = round(($total_card_gross*$credit_vat)/100,2);*/
		
		
		//card charges*********
		$total_card_gross = round((($results3[0]['toal_income']+$results3[0]['diffrence'])*$credit_vat)/$TOALJOBS,2);
		$card_vat_total = round(($total_card_gross*$config['vat_amount'])/100,2);
		/*************/
		//$cancelation;
		
		
		
		} 
		
		$totalamountgros = $commission; 
		$totalvat=$vat + $card_vat_total;  
		$GRAND_TOTAL = $totalamountgros + $totalvat;
			
		##########################################Calculation Sum ########################################## 	
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
			->setCellValue('B5', "Total Value of Jobs Booked with iCabit")
			->setCellValue('B6', "minus Value of Cancelled Jobs")
			->setCellValue('B7', "minus Other Adjustments (see detail below)")
			->setCellValue('B8', "Value of Completed Jobs with iCabit")
			->setCellValue('B10', "Commission on Completed Jobs")
			->setCellValue('B11', "VAT on Commission")
			->setCellValue('B12', "Credit Card Charges (50p per credit card transaction)")
			->setCellValue('B13', "VAT on Credit Card charges")
			->setCellValue('B14', "Total Invoice for this Period (owed to iCabit)")
			->setCellValue('A16', "Calculation of what iCabit is paying you (where we have collected credit card payments on your behalf)")
			->setCellValue('B18', "Credit Card Payments taken by iCabit")
			->setCellValue('B19', "Value of Vouchers accepted")
			->setCellValue('B21', "Credit card charges (paid by customers)")
			->setCellValue('B22', "Booking fees (paid by customers)")
			->setCellValue('B23', "Invoice for this period (as above)")
			->setCellValue('B24', "plus/minus Outstanding balance on your account")
			->setCellValue('C10', $config['commission'].'%')
			->setCellValue('C11', $config['vat_amount'].'%')
			->setCellValue('C13', $credit_vat.'%')
			->setCellValue('D5', "£ ".round($total_income_gross,2))
			->setCellValue('D6', "£ ".$canceled_amount)
			->setCellValue('D7', "£ ". $otherAdjustment)
			->setCellValue('D8', "£ ".round($valueOfJobCompleted,2))
			->setCellValue('D10', "£ ".round($commissionCalculated,2))
			->setCellValue('D11', '£ '. round($vatOnCommesioned,2))
			->setCellValue('D12', '£ '. $total_card_gross)
			->setCellValue('D13', "£ " .$card_vat_total)
			->setCellValue('D14', "£ " . round($invoiceForThisPeriod,0) )
			->setCellValue('F10', "booking exchange credit deducted from commission")
			->setCellValue('F12', "credit card transactions")
			->setCellValue('E12', $paymentViaCreditCard)
			->setCellValue('B26', "TOTAL YOU OWE TO iCabit ")
			->setCellValue('B27', "TOTAL iCabit OWES YOU")
			->setCellValue('B29', "If iCabit owes you, this will be paid to your account around 7 days after the end of the transfer period.")
			->setCellValue('B31', "If you owe iCabit, please make payment to our account as per your invoice.")
		    ->setCellValue('F27', "NOTE: Parcel Deliveries Amount Payable.")
			->setCellValue('D18', "-£ ".$total_card_charges)
			->setCellValue('D19', "-£  ".$valueOfVoucherAcecpeted)
			->setCellValue('D21', '£ '.$total_card_gross)
			->setCellValue('D22', "£ ".$bookingFeesByCustomer)
			->setCellValue('D23', "£ ". round($invoiceForThisPeriod,2))
			->setCellValue('D24', "£ ".$minusOutstanding)
			->setCellValue('D26', "-") 
			->setCellValue('D27', '£ '.round($totalcabsowns,2))  
			->setCellValue('G33', "paid by customers");
	
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
				}else
				if($status == 2){
					$color2 = '97f415';
					$statusText = 'canceled';
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
			
			$callStartTime = 'iCabit_'.$date1.'_to_'.$date2.'_';
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
			$objWriter->save(str_replace('excel_report.php', ''.$callStartTime.'excel_report.xlsx', __FILE__));
		?>
    
      		<script>window.location="<?php echo $callStartTime;?>excel_report.xlsx";</script> 
 	 







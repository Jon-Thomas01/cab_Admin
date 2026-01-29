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
$driver=filter_input(INPUT_GET, 'driver');	

	if($bir){
		
	$condition='';
	$condition2='';
	if($report_fromdate and $report_todate ){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$date2=date('Y-m-d', strtotime($report_todate));	
		$condition .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		$condition2 .=' AND `pick_date` BETWEEN '.$db->mySQLSafe($date1).' AND '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date1)) .'---'.  date("D M d  Y",strtotime($date2));
		$display_date=date("d",strtotime($date1)).'th to '.date("d",strtotime($date2)).'th '.date("M Y",strtotime($date2));
	}else if($report_fromdate and !$report_todate){
		$date1=date('Y-m-d', strtotime($report_fromdate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		$condition2 .=' AND `pick_date`= '.$db->mySQLSafe($date1);
		//$display_date= date("D M d  Y",strtotime($date1));
		$display_date= date("D M d  Y",strtotime($date1));
	}else if (!$report_fromdate and $report_todate){
		$date2=date('Y-m-d', strtotime($report_todate));
		$condition .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		$condition2 .=' AND `pick_date`= '.$db->mySQLSafe($date2);
		//$display_date= date("D M d  Y",strtotime($date2));
		$display_date= date("D M d  Y",strtotime($date2));	
	}
	
	if($driver){
		$condition .=' AND driver_no='.$db->mySQLSafe($driver)."  AND company_id ='".$_SESSION['company_id']."'";
		}
		
		//************************************Pending jobs****************************************
		
		
		
		 $query1="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=0";
		
		$pending = $db->select($query1);
		
		
		if($pending[0]['toal_income'] >0){
			 $TOTAL_PENDING_JOBS=$pending[0]['total_jobs'];
			
			 $PENDING_AMOUNT= $pending[0]['toal_income']+$pending[0]['diffrence'];
			 
			 
				
		 }
		
		//************************************Confirm jobs****************************************
		
		
		
		 $query2="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=1";
		
		$confirm = $db->select($query2);
		
		
		if($confirm[0]['toal_income'] >0){
			 $TOTAL_CONFIRM_JOBS=$confirm[0]['total_jobs'];
			
			 $CONFIRM_AMOUNT= $confirm[0]['toal_income']+$confirm [0]['diffrence'];
			 
			 
				
		 }
		
		
		//************************************Complete jobs****************************************
		
		
		
		 $query4="SELECT COUNT( `cart_order_id` ) AS total_jobs, SUM( `ordertotal` ) AS toal_income ,SUM( new_price - original_price ) AS diffrence
		FROM `cab_order_sum` WHERE 1=1  ".$condition. " AND booking_status=3";
		
		$completed = $db->select($query4);
		
		
		if($completed[0]['toal_income'] >0){
			 $TOTAL_COMPLETED_JOBS=$completed[0]['total_jobs'];
			
			 $COMPLETED_AMOUNT= $completed[0]['toal_income']+$completed[0]['diffrence'];
			 
			 
				
		 }
		
		
		
		
		
		
		
		
		$TOTAL_JOBS=$TOTAL_PENDING_JOBS+$TOTAL_CONFIRM_JOBS+$TOTAL_CANCELLED_JOBS+$TOTAL_COMPLETED_JOBS;
		
		
		$where='1=1 '.$condition;
		
		
		
		$data= $all_booking->getBookingDataReport($where); 
	    $dataCount = count($data);
		
		
  
  
		
		
		}
        
		
		
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
	$objPHPExcel->getActiveSheet()->setCellValue('A2', " Drivers activities ")
			->setCellValue('C2', ucfirst($companyName))
			->setCellValue('B5', "Driver Number")
			->setCellValue('B6', "Driver's Name")
			->setCellValue('B7', "Report Date")
			->setCellValue('B8', "Report Period")
			->setCellValue('B10', "Total Jobs")
			->setCellValue('B11', "Total Pending Jobs")
			->setCellValue('B12', "Total Pending Jobs Amount")
			->setCellValue('B13', "Total Confirm Jobs")
			->setCellValue('B14', "Total Confirm Jobs Amount")
			->setCellValue('B16', "Total Completed Jobs")
			->setCellValue('B17', "Total Complete Jobs Amount")
			
			
			->setCellValue('D5', $driver)
			->setCellValue('D6', ucfirst($user->getDriverName($driver,$_SESSION['company_id'])))
			->setCellValue('D7', date('Y-m-d'))
			->setCellValue('D8', $display_date)
			->setCellValue('D10', round($TOTAL_JOBS))
			->setCellValue('D11', round($TOTAL_PENDING_JOBS))
			->setCellValue('D12', "£ ". round($PENDING_AMOUNT))
			->setCellValue('D13', round($TOTAL_CONFIRM_JOBS))
			->setCellValue('D14', "£ " .round($CONFIRM_AMOUNT) )
			
			->setCellValue('D16', round($TOTAL_COMPLETED_JOBS))
			->setCellValue('D17', "£ ".round($COMPLETED_AMOUNT));
			
		   
		   
		   
		   
		   
	
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
									->setCellValue('N34', "Driver Number")
									->setCellValue('O34', "Status");
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
				->setCellValue('I' . $i, $data[$j]['pick_date'])
				->setCellValue('J' . $i, $time[0].':'.$time[1])
				->setCellValue('K' . $i, $data[$j]['how_many'])
				->setCellValue('L' . $i, $data[$j]['luggage'])
				->setCellValue('M' . $i, getVehicle($data[$j]['how_many'],$data[$j]['luggage']))
				->setCellValue('N' . $i, $data[$j]['driver_no'])
				->setCellValue('O' . $i, $statusText);
				
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
					$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle1, "O$i:O$i");
				}else if($status == 2){
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color2)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "O$i:O$i");	
				}
				else if($status == 3){
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color3)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "O$i:O$i");	
				}
				else if($status == 0){
					
				$sharedStyle2 = new PHPExcel_Style();
				$sharedStyle2->applyFromArray(
					array('font' 	=> array('type'		=> PHPExcel_Style_Fill::FILL_SOLID,
						'color'	=> array('argb' => $color0)),
						'borders' => array('bottom'	=> array('style' => PHPExcel_Style_Border::BORDER_THIN),
						'right'	=> array('style' => PHPExcel_Style_Border::BORDER_MEDIUM))
					));
				$objPHPExcel->getActiveSheet()->setSharedStyle($sharedStyle2, "O$i:O$i");	
				}
				
			$i++;	
		}
		
			$objPHPExcel->getActiveSheet()->getStyle('A34:O34')->applyFromArray(
				array(
					'font'    => array(
					'bold'      => true
				)
				)
			);
			
			$objPHPExcel->getActiveSheet()->getStyle('A34:O34')->applyFromArray(
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
			$objWriter->save(str_replace('driver_exl.php', ''.$callStartTime.'driver_exl.xlsx', __FILE__));
		?>
    
      		<script>window.location="<?php echo $callStartTime;?>driver_exl.xlsx";</script> 
 	 







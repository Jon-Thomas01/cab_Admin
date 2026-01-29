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

$otherdata = new setting($db);
$all_booking = new booking($db);
$currentDate  = date('Y-m-d');
require_once dirname(__FILE__) . '/classes/excel/PHPExcel.php';
$objPHPExcel = new PHPExcel();
echo 'Wait a moment Please,while we create file for you....';

$where='';
if(isset($_GET['data']) && $_GET['data']<> ""){
	
	$where = ' company_id ='.$_SESSION['company_id'];
	if(isset($_GET["idle_date"])  && $_GET["idle_date"]<> ""){
		$filterdate = date("Y-m-d", strtotime($_GET['idle_date']));
		$where .='  AND idle_date=' ."'".$filterdate."'";  // filterdate
	}
	if(isset($_GET["driver_no"]) && $_GET["driver_no"]<> ""){
		$diver_filter = filter_var($_GET["driver_no"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		$where .=' AND driver_no='.$diver_filter;
	}
	if(isset($_GET["rota_year"]) && $_GET["rota_year"]<> ""){
		$filter_year = filter_var($_GET["rota_year"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		$where .='  AND rota_year =' ."'".$filter_year."'";  // filterdate
	}
	if(isset($_GET["rota_month"]) && $_GET["rota_month"]<> ""){
		$filter_month = filter_var($_GET["rota_month"], FILTER_SANITIZE_NUMBER_INT, FILTER_FLAG_STRIP_HIGH); 
		$where .='  AND rota_month =' ."'".$filter_month."'";  // filterdate
		
	}
	if(isset($_GET["idle_date_last_seven"]) && $_GET["idle_date_last_seven"] <> ""){
		$last7DaysDate = date('Y-m-d', strtotime('-7 days'));
		$where .=' AND idle_date BETWEEN "'.$last7DaysDate.'" AND "'.$currentDate.'"';  // filterdate 
	}
	
	if(isset($_GET["idle_date_next_seven"]) && $_GET["idle_date_next_seven"] <> ""){	
		$next7DaysDate = date('Y-m-d', strtotime('+7 days'));
		$where .=' AND idle_date BETWEEN "'.$currentDate.'" AND "'.$next7DaysDate.'"';  // filterdate 
	}
	
}


		  $objPHPExcel->getActiveSheet()->setCellValue('A1', " Sr. No ")
			->setCellValue('B1', "DRIVER")
			->setCellValue('C1', "DATE")
			->setCellValue('D1', "FROM TIME")
			->setCellValue('E1', "TO TIME");
			
			$data= $all_booking->getRotaData($where); 
			$i=2;
			for($j=0; $j < sizeof($data); $j++){
			$serial = $j + 1;	
			$objPHPExcel->getActiveSheet()
				->setCellValue('A' . $i, $serial)
				->setCellValue('B' . $i, $data[$j]['driver_no'])
				->setCellValue('C' . $i, $data[$j]['idle_date'])
				->setCellValue('D' . $i, $data[$j]['from_time'])
				->setCellValue('E' . $i, $data[$j]['to_time']);
			$objPHPExcel->setActiveSheetIndex(0);
			$i++;	
		 }
		$callStartTime = 'iCabit_'.time();
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save(str_replace('excel_report_rota.php', ''.$callStartTime.'excel_report_rota.xlsx', __FILE__));
	?>
    <script>window.location="<?php echo $callStartTime;?>excel_report_rota.xlsx";</script> 
 	 







<?php 
include("includes/includes.inc.php");
$xml='<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<geonames style="MEDIUM">
<totalResultsCount>15</totalResultsCount>';
	$queryHotel = "SELECT full_address FROM cab_postcode_address WHERE STATUS =1 AND full_address LIKE '%a%' LIMIT 0,10";
	$hotelData = $db->select($queryHotel);
		for($i=0; $i<sizeof($hotelData); $i++){
			$xml .='<geoname><pocode>'.$hotelData[$i]['full_address'].'</pocode></geoname>';
		}
	$xml .='</geonames>';
	$xml; 
$fp = fopen('full_address.xml', 'w');
fwrite($fp, $xml);
fclose($fp);

?>
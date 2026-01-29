<?php 
include("includes/includes.inc.php");

$xml='<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<geonames style="MEDIUM">
<totalResultsCount>1000</totalResultsCount>';





$queryHotel = "SELECT `full_address` FROM `cab_postcode_address`";


$hotelData = $db->select($queryHotel);
 for($i=0; $i<sizeof($hotelData); $i++){



$xml .='<geoname>
<pocode>'.$hotelData[$i]['full_address'].'</pocode>

</geoname>
';
 }

$xml .='</geonames>';

 $xml; 

$fp = fopen('pocode_address.xml', 'w');
fwrite($fp, $xml);
fclose($fp);

?>
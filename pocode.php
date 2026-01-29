<?php 
include("includes/includes.inc.php");

$xml='<?xml version="1.0" encoding="UTF-8" standalone="no"?>
<geonames style="MEDIUM">
<totalResultsCount>1000</totalResultsCount>';





$queryHotel = "SELECT `pocode` FROM `cab_cab_pocode`";


$hotelData = $db->select($queryHotel);
 for($i=0; $i<sizeof($hotelData); $i++){



$xml .='<geoname>
<pocode>'.$hotelData[$i]['pocode'].'</pocode>

</geoname>
';
 }

$xml .='</geonames>';

 $xml; 

$fp = fopen('pocode.xml', 'w');
fwrite($fp, $xml);
fclose($fp);

?>
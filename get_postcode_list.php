<?php
include("includes/includes.inc.php");


$q = strtolower($_POST["keyword"]);
if (!$q) return;



 $queryA = "SELECT `pocode` FROM `cab_cab_pocode`  where pocode LIKE '$q%'  ";
    $resultsA = $db->select($queryA); 

for( $i=0; $i<count($resultsA); $i++ ){
	     $pocode=$resultsA[$i]['pocode'];
		 $pocode2="'".$pocode."'";
		 		 echo '<li onclick="set_item_to('.$pocode2.')"  style="width:60px;cursor:pointer; text-align:left">'.$pocode.'</li>';
	    }



?><!--<p><font color="#000000">recognize </font></p>-->

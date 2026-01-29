<?php include("includes/includes.inc.php");?>

<?php 
/*$keyword 	= $_POST['keyword'];
$keywordTo 	= $_POST['keywordTo'];

if(!empty($keyword)){
$locations = $db->select( "select full_address from cab_postcode_address 
	WHERE status = 1
	AND full_address like '%$keyword%'
");
	if(!empty($locations)){
		foreach ($locations as $rs) {
			echo '<li onclick="set_item(\''.str_replace("'", "\'", $rs['full_address']).'\')">'.$rs['full_address'].'</li>';
		}
	}
}
if(!empty($keywordTo)){
$locations = $db->select( "select full_address from cab_postcode_address 
	WHERE status = 1
	AND full_address like '%$keywordTo%'
");
	if(!empty($locations)){
		foreach ($locations as $rs) {
			echo '<li onclick="set_item_to(\''.str_replace("'", "\'", $rs['full_address']).'\')">'.$rs['full_address'].'</li>';
		}
	}
}*/


$keyword 		= $_POST['keyword'];
$keywordTo 		= $_POST['keywordTo'];
$keywordVia 	= $_POST['keywordVia'];

if(!empty($keyword)){
$locations = $db->select( "select full_address from cab_postcode_address 
	WHERE status = 1
	AND full_address like '%$keyword%' LIMIT 100
");

	if(!empty($locations)){
		foreach ($locations as $rs) {
			echo '<li onclick="set_item(\''.str_replace("'", "\'", $rs['full_address']).'\')">'.$rs['full_address'].'</li>';
		}
	}
}
if(!empty($keywordTo)){
$locations = $db->select( "select full_address from cab_postcode_address 
	WHERE status = 1
	AND full_address like '%$keywordTo%' LIMIT 100
");
	if(!empty($locations)){
		foreach ($locations as $rs) {
			echo '<li onclick="set_item_to(\''.str_replace("'", "\'", $rs['full_address']).'\')">'.$rs['full_address'].'</li>';
		}
	}
}

if(!empty($keywordVia)){
	$locations = $db->select( "select full_address from cab_postcode_address 
		WHERE status = 1
		AND full_address like '%$keywordVia%' LIMIT 100
	");
	
	if(!empty($locations)){
		foreach ($locations as $rs) {
			echo '<li onclick="set_item_Via(\''.str_replace("'", "\'", $rs['full_address']).'\')">'.$rs['full_address'].'</li>';
		}
	}
}



?>
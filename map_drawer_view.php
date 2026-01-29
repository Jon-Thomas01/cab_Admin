<?php
include("includes/includes.inc.php");
if(!isset($_SESSION['company_id'])){
	header("location:".$glob['storeURL']);
	exit;
} 
	if(isset($_GET['id']) && $_GET['action']=='view'){
		$id = filter_input(INPUT_GET, 'id');
		$Query = mysql_query( "SELECT *  FROM cab_plotting WHERE  id=".$id." AND company_id =".$_SESSION['company_id']);
		$row = mysql_fetch_assoc( $Query );
		$id =  $row['id'];
		$color = $row['color'];
		$lattitude = $row['lattitude'];
		$longitude = $row['longitude'];
		$driver_no = $row['driver_no'];
		$status = $row['status'];
		
	}else
	if(isset($_GET['id']) && $_GET['action']=='delete'){
		$id = filter_input(INPUT_GET, 'id');
		$Query = mysql_query( "DELETE FROM cab_plotting WHERE  id=".$id." AND company_id =".$_SESSION['company_id']);
		if($Query){
		  $_SESSION['loc_delete'] = 1;
		}else
		  $_SESSION['loc_delete']  = 0;
		echo '<script type="text/javascript">window.location = "'.$glob['storeURL'].'/dashboard2/draw_list"</script>';
		
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
var points =new google.maps.LatLng(<?php echo $lattitude;?> ,<?php  echo $longitude;?>); 
   
function initialize()
{
	var mapProp = {
		center:points,
		zoom:10,
		mapTypeId:google.maps.MapTypeId.ROADMAP
	};
	
	var map = new google.maps.Map(document.getElementById("mapContainer"),mapProp);
 	var drawCirlces = new google.maps.Circle({
		center:points,
		radius:4828.03,
		strokeColor:"#000",
		strokeOpacity:0.8,
		strokeWeight:2,
		fillColor:"#<?php echo $color;?>",
		fillOpacity:0.4,
	});
	
	// generate marker for location
	var marker = new google.maps.Marker({
		position: points,
		map: map,
		title: '<?php echo $location;?>'
	});
    drawCirlces.setMap(map);
}
   google.maps.event.addDomListener(window, 'load', initialize);
<?php //}?>
</script>
	<style>
		.holder{
			top:8px;
			left: 12%;
			content:"";
			z-index:999;
			position: absolute;
		}
		.back{
			background:#0099cc;
		}
		.back,
		.abs_save{
			border:0;
			color: #fff;
			margin: 0 5px;
			padding: 8px 20px;
			border-radius: 5px;
			background: #0077cc;
			display: inline-block;
			vertical-align: middle;
			cursor: pointer;
			text-decoration:none;
		}
		#fill_color{
			border:0;
			border-radius: 5px;
			width: 80px;
			height: 28px;
			margin: 2px 0 0;
			padding: 5px 10px;
		}
		.abs_select{
			height:36px;
			border: 1px solid #ccc;
			padding: 5px 10px;
		}
	</style>
</head>
  <body style="margin: 0;">
  <div class="holder">
	<a class="back" href="<?php echo $glob['storeURL']; ?>/dashboard2/draw_list">Back to list</a>
	<input class="jscolor abs_color" value="ab2567" id="fill_color" name=""  readonly>
	<input   type="hidden" value="<?php echo $id;?>" id="hidden_id" name="hidden_id"  >
	<select class="abs_select"  name="driver_no" id="driver_no">
		<option value="">All</option>
			<?php
        $Query = mysql_query( "SELECT id,name,driver_no FROM cab_fleet WHERE company_id =".$_SESSION['company_id']);
			while( $row = mysql_fetch_assoc( $Query ) ){?>
		<option value="<?php echo $row['driver_no'];?>" <?php if(isset($driver_no) && $driver_no==$row['driver_no']) echo 'selected="selected"';?>><?php echo  $row['name'];?> </option> 
		<?php } ?>
	</select>
    
	<select class="abs_select"  name="status" id="status">
		<option value="">All</option>
		<option value="1" <?php if(isset($status) && $status=='1') echo 'selected="selected"';?> ><?php echo 'active';?> </option> 
		<option value="0" <?php if(isset($status) && $status=='0') echo 'selected="selected"';?>><?php echo  'Inactive';?> </option> 
	</select>
	<input type="button" id="btn_update"  value="Update"  class="abs_save" >
</div>
        
        
  <input type="hidden" id="base_url" value="<?php echo $glob['storeURL']; ?>"> 
 
	 <div id="mapContainer" style="width:1490px;height:800px;"></div>
     
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
      <script type="text/javascript" src="<?php echo $glob['storeURL']; ?>js/map_script.js"></script> 
      <script src="<?php echo $glob['storeURL']; ?>reports_scripts/js/jscolor.js"></script> 
  </body>
</html>

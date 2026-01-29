<?php
include("includes/includes.inc.php");
if(!isset($_SESSION['company_id'])){
	header("location:".$glob['storeURL']);
	exit;
}
   $finalResult ='';
	if(isset($_POST['btn_save'])){
	     $finalResult  =  checkValidity($_POST);
		if($finalResult ==1){
			 $_SESSION['loc_exist'] = 1;
		}else {
			if( $_FILES["markerImage"]["name"] <> ""){
				$listImagePath='images/marker/';
				$file_name = $_FILES["markerImage"]["name"];
				if(!file_exists($listImagePath.$file_name)){
					move_uploaded_file($file_tmp = $_FILES["markerImage"]["tmp_name"],$listImagePath.$file_name);
				}else{
					$ext = pathinfo($file_name,PATHINFO_EXTENSION);
					$filename = basename($file_name,$ext);
					$renameFileName  =   time().'.'.$ext; 
					$file_name = $renameFileName; 
					move_uploaded_file($file_tmp = $_FILES["markerImage"]["tmp_name"],$listImagePath.$renameFileName);
				}
			}
				if(save_coordinates($_POST,$file_name)==true){
					$_SESSION['loc_save'] =1;
					header("location:".$glob['storeURL'].'dashboard2/draw_list');
			 }
	}
 }
?>



<!DOCTYPE html>
<html><head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Cab admin/circle route</title>
    <style>
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
			background:#ccc;
		}
		#map {
		height: 100%;
		}
		.controls {
			margin-top: 10px;
			border: 1px solid transparent;
			border-radius: 2px 0 0 2px;
			box-sizing: border-box;
			-moz-box-sizing: border-box;
			height: 32px;
			outline: none;
			box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
		}
		
		#pac-input {
			background-color: #fff;
			font-family: Roboto;
			font-size: 15px;
			font-weight: 300;
			margin-left: 12px;
			padding: 0 11px 0 13px;
			text-overflow: ellipsis;
			width: 300px;
		}
		
		#pac-input:focus {
		 border-color: #4d90fe;
		}
		
		.pac-container {
		 font-family: Roboto;
		}
		
		#type-selector {
			color: #fff;
			background-color: #4d90fe;
			padding: 5px 11px 0px 11px;
		}
		
		#type-selector label {
			font-family: Roboto;
			font-size: 13px;
			font-weight: 300;
		}
		#target {
		 width: 345px;
		}
		.abs,
		.btnSubmit{
			border:0;
			color: #fff;
			margin: 0 5px;
			padding: 8px 20px;
			border-radius: 5px;
			background: #0077cc;
			display: inline-block;
			vertical-align: middle;
			cursor: pointer;
		}
		.abs_save{left: 679px;position: absolute;top: 17px;z-index: 10000;}
		.abs_select{
			border:1px solid #666;
			width:140px;
			height: 34px;
			display:inline-block;
			vertical-align: middle;
			margin: 0 5px;
		}
		.abs_color{
			border:1px solid #000;
			height:30px;
			width: 80px;
			margin:0 5px;
			padding: 0 10px;
			border-radius: 6px;
			display: inline-block;
			vertical-align: middle;
		}
		#div_on_before_draw{
			overflow: hidden;
			text-align: center;
			width: 226px;
			margin: 0 auto;
			left:0;
			top:10px;
			right:24%;
			position: absolute;
			content:"";
			z-index:9999;
		}
		#back_to_application{
			border:0;
			color: #fff;
			margin: 0 5px;
			display: inline-block;
			vertical-align: middle;
			margin: 0 5px;
			padding: 8px 20px;
			background:#0077cc;
			border-radius: 5px;
			cursor:pointer;
		}
		#div_on_after_draw{
			top:16px;
			left:12%;
			content:"";
			z-index:999;
			position: absolute;
		}
		
    </style>
    
   
  </head>
  <body>
       
        <form id="uploadForm" action="" method="post"   enctype="multipart/form-data">
            <input id="pac-input" class="controls" type="text" placeholder="Enter location to Search..." >
            <div id="div_on_before_draw">
                <input type="button" id="draw-circle"  value="Draw Circle" style="" class="abs">
				
               
                <input type="button" class=" " value="Cancel" id="back_to_application" name="">
				
            </div>
            
            <input type="hidden" id="longitude"   name="longitude" value="">
            <input type="hidden" id="lattitude" name="lattitude" value="">
            <input type="hidden" id="formated_address" name="formated_address" value="">  
            <input type="hidden" id="hidden_fill_color" name="hidden_fill_color" value="">   
            <input type="hidden" id="base_url" value="<?php echo $glob['storeURL']; ?>"> 
            <input type="hidden" id="company_id" name="company_id" value="<?php echo $_SESSION['company_id']; ?>">  
            
            <div id="div_on_after_draw" style="display:none;">
             <input class="jscolor abs_color" value="ab2567" id="fill_color" name=""  readonly>
            <select class="abs_select"  name="driver_no" id="driver_no">
                <option value="">All</option>
                <?php
                $Query = mysql_query( "SELECT id,name,driver_no FROM cab_fleet WHERE company_id =".$_SESSION['company_id']);
                while( $row = mysql_fetch_assoc( $Query ) ){?>
                <option value="<?php echo $row['driver_no'];?>"><?php echo  $row['name'];?> </option> 
                <?php } ?>
            </select>
            
            
            <input name="markerImage"  type="file" class="inputFile" />
             <input type="submit" id="btn_save"  name="btn_save" value="Save Location" class="btnSubmit" />
            <input type="button" id="btn_back"  value="Back to search"   class="abs">
            
           
        </div>    
        
        </form>         
        <?php 
			if($_SESSION['loc_exist']==1){  
			 	echo 'This Driver <strong style="color:red;">'.$_POST['driver_no'].'</strong> already assign to Location ';
			}else
				if( $_SESSION['img_empty'] == 9){
					echo '<strong style="color:red;">Marker icon should be selected.</strong>';	
				}
			$_SESSION['loc_exist']  ='';
			$_SESSION['img_empty']  ='';	
		 ?> 
    <div id="map"></div>
    
    <script>
    function initAutocomplete() {
        var map = new google.maps.Map(document.getElementById('map'), {
          center: {lat: 51.5073509, lng: -0.1277583},
          zoom: 10,
          mapTypeId: google.maps.MapTypeId.ROADMAP
        });
        // Create the search box and link it to the UI element.
        var input = document.getElementById('pac-input');
        var searchBox = new google.maps.places.SearchBox(input);
        map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', function() {
          searchBox.setBounds(map.getBounds());
        });
         
        var markers = [];
        // Listen for the event fired when the user selects a prediction and retrieve
        // more details for that place.
        searchBox.addListener('places_changed', function() {
          var places = searchBox.getPlaces();

          if (places.length == 0) {
            return;
          }

          // Clear out the old markers.
          markers.forEach(function(marker) {
            marker.setMap(null);
          });
          markers = [];

          // For each place, get the icon, name and location.
          var bounds = new google.maps.LatLngBounds();
          places.forEach(function(place) {
            var icon = {
              url: place.icon,
              size: new google.maps.Size(71, 71),
              origin: new google.maps.Point(0, 0),
              anchor: new google.maps.Point(17, 34),
              scaledSize: new google.maps.Size(25, 25)
            };

            // Create a marker for each place.
            markers.push(new google.maps.Marker({
              map: map,
              icon: icon,
              title: place.name,
              position: place.geometry.location
            }));

            if (place.geometry.viewport) {
              // Only geocodes have viewport.
              bounds.union(place.geometry.viewport);
            } else {
              bounds.extend(place.geometry.location);
            }
          });
          map.fitBounds(bounds);
        });
      }

    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMlJVG3wytWVuxz_NsLvIp1BPrEXWU7Ng&libraries=places&callback=initAutocomplete"
         async defer></script>
         <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
         <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
       
      <script type="text/javascript" src="<?php echo $glob['storeURL']; ?>js/map_script.js"></script> 
      <script src="<?php echo $glob['storeURL']; ?>reports_scripts/js/jscolor.js"></script>  
     
  
      
  </body>
</html>
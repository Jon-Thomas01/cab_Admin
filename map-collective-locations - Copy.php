<?php 
include("includes/includes.inc.php");
if(!isset($_SESSION['company_id'])){
	header("location:".$glob['storeURL']);
	exit;
} 
		$Query = mysql_query( "SELECT lattitude,longitude,location,marker_icon,color  FROM cab_plotting WHERE   company_id =".$_SESSION['company_id']." AND status=1" );
		$str  = '';
		if(mysql_num_rows($Query) > 0){
			$i =1;
			while($row = mysql_fetch_assoc( $Query )){
			$str .='location_'.$i.': {
						center: {lat: '.$row['lattitude'].', lng: '.$row['longitude'].'},
						filled_color: "'.$row['color'].'",
						location_title: "'.$row['location'].'",
						icon: "'.$row['marker_icon'].'",
				   },'; 
			$i++;
			}
	  } 
	  $str= rtrim($str,',');
 ?>


<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Map Collective Locations</title>
    <style>
		html, body {
			height: 100%;
			margin: 0;
			padding: 0;
		}
		#map {
			height: 100%;
		}
    </style>
  </head>
  <body>
    <div id="map"></div>
    <script>
      
	  var citymap = {<?php echo $str;?>};
	  function initMap() {
		var map = new google.maps.Map(document.getElementById('map'), {
			zoom: 8, 
			center: {lat:51.5073509, lng:  -0.1277583},
			mapTypeId: google.maps.MapTypeId.ROADMAP
		});
		
		  for (var city in citymap) {
				var cityCircle = new google.maps.Circle({
				strokeColor: '#000',
				strokeOpacity: 0.8,
				strokeWeight: 2,
				fillColor: '#'+citymap[city].filled_color, 
				fillOpacity: 0.35,
				map: map,
				center: citymap[city].center,
				//radius: Math.sqrt(citymap[city].population) * 100
				radius: 4828.03
			});
		
			// generate markers
			var marker = new google.maps.Marker({ 
				position: citymap[city].center,
				map: map,
				icon:'<?php echo $glob['storeURL'].'images/marker/'?>'+citymap[city].icon,
				title: citymap[city].location_title
			});
		 }
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAMlJVG3wytWVuxz_NsLvIp1BPrEXWU7Ng&callback=initMap">
    </script>
  </body>
</html>
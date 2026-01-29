<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
var longitude = window.parent.document.getElementById("longitude").value;
var lattitude = window.parent.document.getElementById("lattitude").value;
var formated_address = window.parent.document.getElementById("formated_address").value;
var fill_color = window.parent.document.getElementById("hidden_fill_color").value;


var points =new google.maps.LatLng(lattitude , longitude);  
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
		fillColor:"#"+fill_color,
		fillOpacity:0.4,
	});
	
	
	// generate marker for location
	var marker = new google.maps.Marker({
		position: points,
		map: map,
		title: formated_address
	});
    drawCirlces.setMap(map);
}
   google.maps.event.addDomListener(window, 'load', initialize);

</script>
</head>
  <body>
	 <div id="mapContainer" style="width:1490px;height:800px;"></div>
  </body>
</html>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>
<body>
<div id="map" style="width:453px; height:244px;"></div>
<script>
function initMap() {
	var directionsService = new google.maps.DirectionsService;
	var directionsDisplay = new google.maps.DirectionsRenderer;
	var map = new google.maps.Map(document.getElementById("map"), {
		zoom: 7,
		center: {lat: 51.5000, lng: 0.1167}
	});
	directionsDisplay.setMap(map);
	calculateAndDisplayRoute(directionsService, directionsDisplay);
}

function calculateAndDisplayRoute(directionsService, directionsDisplay) {
	directionsService.route({
	origin: window.parent.document.getElementById("origen").value,
    destination:window.parent.document.getElementById("destination").value,
    travelMode: google.maps.TravelMode.DRIVING
  }, function(response, status) {
    if (status === google.maps.DirectionsStatus.OK) {
      directionsDisplay.setDirections(response);
    } else {
     
    }
  });
}
 </script>
    <script src="https://maps.googleapis.com/maps/api/js?callback=initMap"  async defer></script>
</body>
</html>

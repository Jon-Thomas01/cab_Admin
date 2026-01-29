<?php include("includes/includes.inc.php"); 
   // delete
    if(isset($_GET['id']) && $_GET['id']<>""){
		$queryExi = mysql_query("DELETE FROM `cab_driver_plot` WHERE id='".$_GET['id']."'");
		if($queryExi !=''){
			header("location:driver_plot_list.php?del=".true);
		}
	}
	// save
	if(isset($_REQUEST['action']) && $_REQUEST['action'] =='save_data'){
		$queryExi = mysql_query("INSERT INTO `cab_driver_plot` ( `plotname`, `plotcolor`, `plotlanlat`, `status`, `company_id`)
		VALUES('".$_POST['plotname']."','".$_POST['plotcolor']."','".$_POST['plotlanlat']."','".$_POST['status']."','".$_SESSION['company_id']."')");
		if($queryExi !=''){
			header("location:driver_plot_list.php");
		}
	 }
?>
<!DOCTYPE html>
<html class="no-js" lang="en">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Driver Plot</title>
</head>
<body>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script> 
<style>
	#map_canvas img {
		max-width: none;
	} 
	#map_canvas {
		height: 50%;
		margin: 0px;
		padding: 0px;
		width: 100%;
	}
	
	#panel {
		position: absolute;
		left: 60%;
		margin-left: -280px;
		z-index: 5;
		padding: 5px;
	}
</style>

<script type="text/javascript" src="http://code.jquery.com/jquery-1.8.1.js"></script> 
<script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script> 
<script type="text/javascript" src="js/jquery-ui-timepicker-addon.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0-beta.1/themes/base/jquery-ui.css">
<script type="text/javascript" src="js/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="css/jquery.fancybox.css?v=2.1.5" media="screen" />
	 
     <div id="panel" style="margin-left:-270px;width:700px;margin-top:5px;">
      <a href="driver_plot_list.php" title="Back to list" style="width:90px;font-size:15px;color:red; font-weight:bold;" >Back to list</a>
       &nbsp; 
          <a href="javascript:void(0);" 
          title="Save Data" style="width:90px;font-size:15px;color:red; font-weight:bold;" id="btnshowMap" onclick="showresult();">Save Data</a> 
          &nbsp;
          <a href="javascript:void(0);" title="Draw Shape" style="width:100px;font-size:15px;color:blue;font-weight:bold;" 
           id="btnshowMap" onclick="initial();">Draw Shape</a>
          <input id="address1" type="text" size="50" value="" />
    </div>
	<div style="border:0px solid #CC99FF;font-size:15px;">
  <div style="width:80%;float:left;">
    <div id="map_canvas" style="height:550px;border:0px solid #CC99FF;"></div>
  </div>
  
  <div id="formdiv" style="width:20%;float:right; background:#EAC135;">
    <h2>Save Plot</h2>
    <form action="#" method="post">
      <input type="hidden" name="action" id="action" value="save_data" >
      <!--<fieldset>-->
        <div class="editor-label">
          <label for="Name">Name</label>
        </div>
        <div class="editor-field">
            <input class="text-box single-line" id="plotname" name="plotname" type="text" value="" />
            <span class="field-validation-valid" data-valmsg-for="Name" data-valmsg-replace="true"></span>
        </div>
        
        <div class="editor-label">
          <label for="plotlanlat">Plot Area</label>
        </div>
        <div class="editor-label">
          <textarea id="plotlanlat" name="plotlanlat" cols="20" rows="5"></textarea>
          <span class="field-validation-valid" data-valmsg-for="plotlanlat" data-valmsg-replace="true">
          </span>
        </div>
         
         <div class="editor-label">
          <label for="min_Fare">COLOR</label>
        </div>
        
        <div class="editor-field">
          <input class="text-box single-line" data-val="true" data-val-number="The field min_Fare must be a number." data-val-required="The min_Fare field is required." id="plotcolor" name="plotcolor" type="text" value="#000000" />
          <span class="field-validation-valid" data-valmsg-for="min_Fare" data-valmsg-replace="true"></span> </div>
        <div class="editor-label">
          <label for="milefare">Status</label>
        </div>
        
        
        <div class="editor-field">
          <select name="status" id="status" >
            <option value="Active" selected>enable</option>
            <option value="Inactive">Disable</option>
          </select>
        </div>
        
        <p align="center"> 
          <input type="submit" value="Save" name="save_plot_data" style="width:150px;font-size:12px;" />
        </p>
        <a href="driver_plot_list.php">Cancel and Back to list</a>
        
      <!--</fieldset>-->
    </form>
  </div>
</div>
 <div> 

</div>
<section class="scripts"> 
  <script type="text/javascript">
  		var currencyvalue ='1';
		/*var datav ='(3.141472007651462, 101.70529615180658)^(3.1483868222251132, 101.72920072333977)^(3.169185086614547, 101.73405088203117)^(3.1620989622788493, 101.71066129462884)^';*/
		var datav ='';
		var circle;
		var location1;
		var map;
		var bermudaTriangle;
		var formdiv = document.getElementById("formdiv");
	
		var n = datav.split("^");
	Initialize();
	
    
	function Initialize() {
 		
		formdiv.style.visibility = "hidden";
        google.maps.visualRefresh = true;
        var Liverpool = new google.maps.LatLng(53.408841, -2.981397);
		var mapOptions = {
            center: new google.maps.LatLng(51.333789, -0.267620),
            zoom: 9,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
		var triangleCoords = [
			new google.maps.LatLng(25.774252, -80.190262),
			new google.maps.LatLng(18.466465, -66.118292),
			new google.maps.LatLng(32.321384, -64.75737)
      	];
		var vert = new Array();
        var tcor = "";
        var l = n.length;
		for (var i = 0; i < l - 1; i++) {
			var g = n[i];
            var p = g.replace("(", "");
            p = p.replace(")", "");
            var k = p.split(",");
			vert[i] = new google.maps.LatLng(k[0], k[1]);
		}
		
		bermudaTriangle = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            editable: true,
            draggable: true
        });
		
		

        bermudaTriangle.setMap(map);
		
		var countrycode = '';
		if(currencyvalue == 1 ){
			countrycode = 'GB' ;
		}
		else if(currencyvalue == 0){
			 countrycode = 'US' ;
		}else{
			countrycode = 'GB' ;
		}
		
        var aoptions = {
            componentRestrictions: { country: countrycode }
        };


        var input = (document.getElementById('address1'));

        var autocomplete = new google.maps.places.Autocomplete(input, aoptions);

        autocomplete.bindTo('bounds', map);


        var marker = new google.maps.Marker({
            map: map
        });

        google.maps.event.addListener(autocomplete, 'place_changed', function () {

            marker.setVisible(false);
            input.className = '';
            var place = autocomplete.getPlace();
            if (!place.geometry) {
                input.className = 'notfound';
                return;
            }

            if (place.geometry.viewport) {
                map.fitBounds(place.geometry.viewport);
            } else {
                map.setCenter(place.geometry.location);
                map.setZoom(17);  // Why 17? Because it looks good.
            }
            marker.setIcon(({
                url: place.icon,
                size: new google.maps.Size(71, 71),
                origin: new google.maps.Point(0, 0),
                anchor: new google.maps.Point(17, 34),
                scaledSize: new google.maps.Size(35, 35)
            }));
            marker.setPosition(place.geometry.location);
            marker.setVisible(true);

            var address = '';
            if (place.address_components) {
                address = [
                    (place.address_components[0] && place.address_components[0].short_name || ''),
                    (place.address_components[1] && place.address_components[1].short_name || ''),
                    (place.address_components[2] && place.address_components[2].short_name || '')
                  ].join(' ');
            }
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
            infowindow.open(map, marker);
        });
        // Sets a listener on a radio button to change the filter type on Places
        // Autocomplete.

		if(datav!=''){
		map.setZoom(17);
        map.setCenter(vert[0]);
		}
		GetAreas();
		
        google.maps.event.addListener(bermudaTriangle, 'mousedown', function () {
            showresult();
        });
		
		
    }
 
	
	
	 function GetAreas() {
		$.ajax({
            url: "<?php echo $glob['storeURL']; ?>get_plot_data.php",
            type: 'GET',
            dataType: 'json',
			success: function (data) {
				$.each(data, function (i, item)
				{	
					ShowPoly(item);
                });
            }

        });
    }

  

    function ShowPoly(item) {

		var vert = new Array();
        var tcor = "";
        datav = item.plotlanlat;
        n = datav.split("^");
		var l = n.length;
		usercolor = item.plotcolor;
		
        for (var i = 0; i < l - 1; i++) {

            var g = n[i];
            var p = g.replace("(", "");
            p = p.replace(")", "");
            var k = p.split(",");
			vert[i] = new google.maps.LatLng(k[0], k[1]);
		}

        bTriangle = new google.maps.Polygon({
            paths: vert,
            strokeColor: usercolor,
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#000000',
            fillOpacity: 0.35
//            editable: true,
//            draggable: true
        });

        bTriangle.setMap(map);
		map.setZoom(10);


    }
	
    function initial() {
        geocoder = new google.maps.Geocoder();
        address1 = document.getElementById("address1").value;
		
        if (geocoder) {
            geocoder.geocode({ 'address': address1 }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    location1 = results[0].geometry.location;
					showMap();
                } else {
                    alert("Geocode was not successful for the following reason: " + status);
				}
            });

        }

    }



    function showMap() {
		var triangleCoords = [
		new google.maps.LatLng(location1.lat(), location1.lng()),
		new google.maps.LatLng(location1.lat() + .01, location1.lng() + .01),
		new google.maps.LatLng(location1.lat() + .02, location1.lng() + .02)

  ];

        // Construct the polygon.
        bermudaTriangle = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            editable: true,
            draggable: true
        });

        bermudaTriangle.setMap(map);
        var foo = document.getElementById("btnshowMap");
        foo.disabled = true;
    }
    function showresult() {
        formdiv.style.visibility = "visible";
        var cs = "";
        var content = [];
        var vertices = bermudaTriangle.getPath();
        var len = vertices.getLength();
        content = vertices.getArray();
		for (var i = 0; i < len; i++) {
            cs += content[i]+"^";
        }
      	document.getElementById("plotlanlat").value = cs;
	  }
       </script> 
</section>
</body>
</html>
<script>
   
$("#conf").click(function() {
				var commentContainer = $(this).parent();
				var id = $(this).attr("id");
				var string ='configuredriverplotlist.php';
				$.fancybox.open({
						href : string,
						'content':$("#element").html(),
						'width':'1200',
						'height':'620',
						'autoDimensions':false,
						'type':'iframe',
						'autoSize':false,
						 padding : 1,
						 helpers : {
							thumbs : {
							afterclose : function(){
								window.location.reload();
								}
							}
						},
					});
					
			});
			
</script>
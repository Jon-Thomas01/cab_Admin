<?php 
 include("includes/includes.inc.php");
 if(isset($_GET['id']) && $_GET['id'] <> ""){
  
	$queryExi = mysql_query("SELECT id,plotname,plotlanlat,status,plotcolor FROM `cab_driver_plot` WHERE 
	company_id=".$_SESSION['company_id']." AND id=".$_GET['id']);
	if(mysql_num_rows($queryExi) > 0){
		$row  = mysql_fetch_assoc($queryExi);
	}
  }
  
	if(isset($_REQUEST['action']) && $_REQUEST['action'] =='update_data'){
		/*$queryExi = mysql_query("INSERT INTO `cab_driver_plot` ( `plotname`, `plotcolor`, `plotlanlat`, `status`, `company_id`)
		VALUES('".$_POST['plotname']."','".$_POST['plotcolor']."','".$_POST['plotlanlat']."','".$_POST['status']."','".$_SESSION['company_id']."')");*/
		 $queryStr = "UPDATE `cab_driver_plot` SET plotname='".$_POST['plotname']."',plotcolor='".$_POST['plotcolor']."',
		plotlanlat='".$_POST['plotlanlat']."',status='".$_POST['status']."' WHERE id='".$_POST['id']."'";
		$queryExi = mysql_query($queryStr);
		    if($queryExi !=''){
				header("location:driver_plot_list.php");
			}
	}
  
  
  
 ?>
<!DOCTYPE html>
<html class="no-js" lang="en">

<script type="text/javascript" src="js/jquery-1.7.1.js"></script>
<body>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script> 

<!-- This css is to ensure that the google map contols (zoom bar etc) show and size correctly. -->
<style>
    #map_canvas img {
        max-width: none;
    }
</style>

<!-- This css is to give a nice big popup "info window" when a marker is clicked on the map -->
<style>
    /*#map_canvas {
        height: 50%;
        margin: 0px;
        padding: 0px;
        width: 100%;
    }
    #mcanvas {
        margin: 0;
        padding: 0;
        height: 60%;
        margin-top: 40;
    }
    #maincontent {
        margin: 0;
        padding: 0;
        height: 60%;
        margin-top: 40;
    }
    #panel {
        position: absolute;
        top: 70px;
        left: 60%;
        margin-left: -280px;
        z-index: 5;
        background-color: #fff;
        padding: 5px;
        border: 1px solid #999;
    }*/
	
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

<div id="panel" style="margin-left:-270px;width:400px;margin-top:5px;"> 
  <!--  <input id="address1" type="text" size="50" value="High Wycombe HP12 4HJ, United Kingdom" />-->
  <input id="address1" type="hidden" size="50" value="" />
<!--  <input type="hidden" value="CREATE AREA" id="btnshowMap" onclick="initial();" class="button lightgray" style="width:100px;font-size:9px;"  />
  <input type="button" value="SAVE AREA" onclick="showresult();" class="button lightgray" style="width:90px;font-size:9px;" />-->
  
   <!-- <a href="javascript:void(0);" style="width:90px;font-size:15px;color:red;"  class="button lightgray" id="btnshowMap" onclick="showresult();">Save Data</a> -->
          &nbsp;
         <!-- <a href="javascript:void(0);" style="width:100px;font-size:15px;color:blue;"  id="btnshowMap" onclick="initial();">Create Draw</a>-->
  
  
</div>
<div style="border:0px solid #CC99FF;font-size:15px;" >
  <div style="width:80%;float:left; margin-right:30px;">
    <div id="map_canvas" style="height:550px;border:0px solid #CC99FF;"></div>
  </div>
  <div id="formdiv" style="width: 183px;float:right; margin-left:0px; background:#EAC135;">
    <form action="#" method="post">
      <input type="hidden" name="action" id="action" value="update_data" >
      <input type="hidden" name="id" id="id"  value="<?php if(isset($row['id'])) echo $row['id'];?>" >
       <div class="editor-label">
          <label for="Name">NAME</label>
        </div>
        <div class="editor-field">
          <input class="text-box single-line" id="plotname" name="plotname" type="text" value="<?php if(isset($row['plotname'])) echo $row['plotname'];?>" />
          <span class="field-validation-valid" data-valmsg-for="Name" data-valmsg-replace="true"></span> </div>
        <div class="editor-label" style="display:none">
          <label for="plotlanlat" >AREA</label>
        </div>
        <div class="editor-label" style="display:none">
          <textarea id="plotlanlat" name="plotlanlat" cols="20" rows="5"><?php if(isset($row['plotlanlat'])) echo $row['plotlanlat'];?></textarea>
          <span class="field-validation-valid" data-valmsg-for="plotlanlat" data-valmsg-replace="true"></span> </div>
		<div class="editor-label">
          <label for="min_Fare">COLOR</label>
        </div>
        <div class="editor-field">
          <input class="text-box single-line" data-val="true" data-val-number="The field min_Fare must be a number." data-val-required="The min_Fare field is required." id="plotcolor" name="plotcolor" type="text" value="<?php if(isset($row['plotcolor'])) echo $row['plotcolor'];?>" />
          <span class="field-validation-valid" data-valmsg-for="min_Fare" data-valmsg-replace="true"></span> </div>
        <div class="editor-label">
          <label for="status">STATUS</label>
        </div>
        <div class="editor-field">
        <select name="status" id="status"   >
            <option value="0">SELECT STATUS</option>
            <option value="Active" <?php if(isset($row['status']) && $row['status']=='Active') echo 'selected="selected"';?>>Active</option>
            <option value="Inactive"  <?php if(isset($row['status']) && $row['status']=='Inactive') echo 'selected="selected"';?> >Inactive</option>   
        </select>
        </div>
        <p align="center">
          <input type="submit" value="Save Changes" style="width:150px;font-size:12px;" class="button blue" />
        </p>
        <a href="driver_plot_list.php">Back to list</a> 
    </form>
  </div>
</div>
<div id="result"></div>
<section class="scripts"> 
  <script type="text/javascript">
  
  var datav ='<?php if(isset($row['plotlanlat'])) echo $row['plotlanlat'];?>';
    var circle;
    var location1;
    var map;
    var bermudaTriangle;
    var infoWindow ;
   // document.getElementById("result").innerHTML = datav;
    var n = datav.split("^");
   
        Initialize();
    	GetAreas();

    function Initialize() {
        // Google has tweaked their interface somewhat - this tells the api to use that new UI
        google.maps.visualRefresh = true;
             var mapOptions = {
            center: new google.maps.LatLng(51.333789, -0.267620),
            zoom: 9,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        };

        // This makes the div with id "map_canvas" a google map
        map = new google.maps.Map(document.getElementById("map_canvas"), mapOptions);
        infoWindow = new google.maps.InfoWindow();
        var vert = new Array();

        var tcor = "";
        var l = n.length;

        for (var i = 0; i < l - 1; i++) {
			var g = n[i];
            var p = g.replace("(", "");
            p = p.replace(")", "");
            var k = p.split(",");
			vert[i] = new google.maps.LatLng(k[0],k[1]);
	    }
 		bermudaTriangle = new google.maps.Polygon({
            paths: vert,
            strokeColor: '#FF0000',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: '#FF0000',
            fillOpacity: 0.35,
            editable: true,
            draggable: true
        });

        bermudaTriangle.setMap(map);
		map.setZoom(17);
        map.setCenter(vert[0]);
        GetAreas();
        google.maps.event.addListener(bermudaTriangle, 'mouseout', function () {
			showresult();
        });
    }

    //click event
    function initial() {
        geocoder = new google.maps.Geocoder();
        address1 = document.getElementById("address1").value;

        if (geocoder) {
            geocoder.geocode({ 'address': address1 }, function (results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    //location of first address (latitude + longitude)
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

       bermudaTriangle = new google.maps.Polygon({
            paths: triangleCoords,
            strokeColor: '<?php if(isset($row['plotcolor'])) {echo $row['plotcolor'];}else{echo '#FF0000';}?>',
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

        var cs = "";
        var content = [];
        var vertices = bermudaTriangle.getPath();
        // Iterate over the vertices.
        //   cs =  vertices.getAt(0)  + vertices.getAt(1);
        var len = vertices.getLength();
        content = vertices.getArray();

        for (var i = 0; i < len; i++) {
            cs += content[i] + "^";
        }


        //  document.getElementById("Result").innerHTML = cs + "len=" + len + "content=" + content[0];

        document.getElementById("Area_Lat").value = cs;


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
        //   vert[0] = new google.maps.LatLng(51.336036, -0.2673819999999978);
        //   vert[1] = new google.maps.LatLng(51.33037886466881, -0.23540934375000688);
        //    vert[2] = new google.maps.LatLng(51.356036, -0.24738200000001598);



        var tcor = "";
        datav = item.plotlanlat;
        n = datav.split("^");
        var l = n.length;

        for (var i = 0; i < l - 1; i++) {

            var g = n[i];
            var p = g.replace("(", "");
            p = p.replace(")", "");
            var k = p.split(",");

            vert[i] = new google.maps.LatLng(k[0], k[1]);


        }

        bTriangle = new google.maps.Polygon({
            paths: vert,
            strokeColor: '<?php if(isset($row['plotcolor'])) {echo $row['plotcolor'];}else{echo '#FF0000';}?>',
            strokeOpacity: 0.8,
            strokeWeight: 3,
            fillColor: 'Blue',
            fillOpacity: 0.35
//            editable: true,
//            draggable: true
        });

        bTriangle.setMap(map);


        //berudaTriangle.setPaths(tcor);
        map.setZoom(10);


    }
	    function showresult() {

 document.getElementById("plotlanlat").value='';
        formdiv.style.visibility = "visible";
        var cs = "";
        var content = [];
        var vertices = bermudaTriangle.getPath();
        // Iterate over the vertices.
        //   cs =  vertices.getAt(0)  + vertices.getAt(1);
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
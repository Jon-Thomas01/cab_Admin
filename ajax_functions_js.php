<script type="text/javascript">

function loadcities(stateid)
	{
		//alert(stateid);
		jQuery('#city').hide();
		var loader = "<img src='<?php echo $glob['storeURL']; ?>images/progress.gif' width='25' >";
		jQuery('#loader_city').html(loader);
		jQuery.post("<?php echo $glob['storeURL']; ?>ajax_functions.php",{Action:'load_city',stateId:stateid}, function(data){
		  jQuery('#loader_city').html("");
		  jQuery('#city').show();		
		  jQuery('#city').html(data);
		  //document.getElementById('city').selectedIndex = 0;
	});
	
	

}


function loadofficecities(stateid)
	{
		//alert(stateid);
		jQuery('#office_city').hide();
		var loader = "<img src='<?php echo $glob['storeURL']; ?>images/progress.gif' width='25' >";
		jQuery('#loader_city_office').html(loader);
		jQuery.post("<?php echo $glob['storeURL']; ?>ajax_functions.php",{Action:'load_city_office',stateId:stateid}, function(data){
		  jQuery('#loader_city_office').html("");
		  jQuery('#office_city').show();		
		  jQuery('#office_city').html(data);
		  //document.getElementById('city').selectedIndex = 0;
	});
	
	

}
// ===============   COPY THIS ====================

function loadstate(countryid)
{
    //alert(countryid);
	jQuery('#state').hide();
	var loader = "<img  src='<?php echo $glob['storeURL']; ?>/images/progress.gif' width='25' height='25' >";
	jQuery('#loader_state').html(loader);
	jQuery.post("<?php echo $glob['storeURL']; ?>/ajax_functions.php",{Action:'load_state',countryId:countryid}, function(data){
	jQuery('#loader_state').html("");
	jQuery('#state').show();		
	jQuery('#state').html(data);
	//alert(data);
	//document.getElementById('city').selectedIndex = 0;
	});
}






function loadcitiesAjax(countryid)
{
    //alert(countryid);
	jQuery('#state').hide();
	var loader = "<img src='<?php echo $glob['storeURL']; ?>/images/progress.gif' width='25' >";
	jQuery('#loader_state').html(loader);
	jQuery.post("<?php echo $glob['storeURL']; ?>/ajax_functions.php",{Action:'load_state',countryId:countryid}, function(data){
	jQuery('#loader_state').html("");
	jQuery('#state').show();		
	jQuery('#state').html(data);
	//alert(data);
	//document.getElementById('city').selectedIndex = 0;
	});
}

//=========================   LOAD  SUB CAT=======================


function loadSubcat(catID)
{
//alert(countryid);
	jQuery('#subCate').hide();
	var loader = "<img src='<?php echo $glob['storeURL']; ?>/images/lightbox-ico-loading.gif' width='25' >";
	jQuery('#loader_subCate').html(loader);
	jQuery.post("<?php echo $glob['storeURL']; ?>/ajax_functions.php",{Action:'load_subCate',catId:catID}, function(data){
	jQuery('#loader_subCate').html("");
	jQuery('#subCate').show();		
	jQuery('#subCate').html(data);
	//alert(data);
	//document.getElementById('city').selectedIndex = 0;
	});
}



// ===============   COPY THIS ====================


//==============================booking status=======================


//=========================   LOAD  SUB CAT=======================
function checkOther(id){
	 if($('#chk'+id).is(':checked')){
		 $('#dics'+id).focus();
		 }else{
			 $('#dics'+id).val('');
			 }
	}
function checkOther2(id){
	 if($('#chkpol'+id).is(':checked')){
		 $('#dicspol'+id).focus();
		 }else{
			 $('#dicspol'+id).val('');
			 }
	}
function checkOther3(id){
	 if($('#chkinfo'+id).is(':checked')){
		 $('#dicsinfo'+id).focus();
		 }else{
			 $('#dicsinfo'+id).val('');
			 }
	}
</script>





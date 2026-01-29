<?php include("includes/includes.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Rota Driver</title>
<link rel="stylesheet" type="text/css" href="<?php echo $glob['storeURL']; ?>css_new/rota.css">
 <script type="text/javascript"  src="<?php echo $glob['storeURL']; ?>js/jquery.js"></script>
<link href="http://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel="stylesheet">
<script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
 
</head>
<body>

   <div id="rota_contentainer">  
     <div class="header"> 
       <div class="header_left fl">
          
            <label>SORT VALUE</label>
            <select name="filter_val" id="filter_val" class="w4">
                <option value="">SELECT VALUE</option>
                <option value="1">Month</option>
                <option value="2">Last Week</option>
                <option value="3">Next Week</option>
            </select>
            <select name="diver_filter" id="diver_filter" class="w5">
                <option value="">SELECT OPERATOR DRIVER</option>
                <?php
                $Query = mysql_query( "SELECT id,name,driver_no FROM cab_fleet WHERE company_id =".$_SESSION['company_id']);
                while( $row = mysql_fetch_assoc( $Query ) ){?>
                    <option value="<?php echo $row['driver_no'];?>" 
                    <?php if(isset($_POST['filter_driver']) && $_POST['filter_driver']== $row['driver_no']){?> selected="selected" <?php }?>>
                    	<?php echo   $row['driver_no'].'--'.$row['name'];?>
                    </option>
                <?php } ?>
            </select>
       </div>
        <div class="header_right fr">
           
          
           
           <div id="advance_filter"  style="display:none; float: left;">
             <label class="fl">DATE</label>
                <select name="filter_year" id="filter_year" class="w6">
                    <option value="">Year</option>
                    <?php for($i=2014;$i<=2016;$i++){?>
                        <option value="<?php echo $i;?>"><?php echo $i;?></option>
                    <?php }?>
                </select>
               
                <select name="filter_month" id="filter_month" class="w7">
                    <option value="">Month</option>
                    <?php 
                        $month  = array("JANUARY","FEBRUARY","MARCH","APRIL","MAY","JUNE","JULY","AUGUEST","SEPTEMBER","OCTOBER","NOVEMBER","DECEMBER");
                        $j=1;
						for($i=0;$i< sizeof($month);$i++){
						 if($j < 10){
						  $j = '0'.$j;
						 }	
						?>
                        <option value="<?php echo $j;?>"><?php echo $month[$i];?></option>
                    <?php 
					$j++;
					}?>
                </select>
            
           </div> 
             
             <div id="initial_filter"> 
              <label class="fl">DATE</label>
            	<input type="text" id="filter_date" name="filter_date" value="" class="fl">
             </div>
            <input type="button" id="btn_reset" value="RESET" class="btn_reset fr">
          
       </div>
      </div> 
      <div class="rota_content fl"> 
          <div class="rota_content_left fl m1"> 
           <div id="population_area" class="fl">
              <div class="box w1 bgcolor fl m2 ">DRIVER</div>
              <div class="box w2 bgcolor fl ">DATE</div>
              <div class="box w2 bgcolor fl ">FROM DATE</div>
              <div class="box w2 bgcolor fl ">TO TIME</div>
              <div class="box w3 bgcolor fl ">ACTION</div>
            </div>
            <div class="loading-div"><img src="<?php echo $glob['storeURL']; ?>images/transpaerent_loader.gif" ></div>
              <div id="population_content_area" class="fl">
                	<img src="<?php echo $glob['storeURL']; ?>images/transpaerent_loader.gif" id="driver_loader">
              </div>
            </div>
          <div class="rota_content_right fr"> 
               <form id="f1" action="get_locations.php" method="POST"> 
               <div class="cover"></div> 
                <label>DRIVER</label>
                <select name="driver_no" id="driver_no" class="fr" required="required">
            	<option value="">Select Driver</option>
                <?php
                $Query = mysql_query( "SELECT id,name,driver_no FROM cab_fleet WHERE company_id =".$_SESSION['company_id']);
                while( $row = mysql_fetch_assoc( $Query ) ){?>
                    <option value="<?php echo $row['driver_no'];?>" 
                    <?php if(isset($_POST['filter_driver']) && $_POST['filter_driver']== $row['driver_no']){?> selected="selected" <?php }?>>
                    	<?php echo  $row['name'];?>
                    </option>
                <?php } ?>
            </select>
            
            
            <div class="clear">&nbsp;</div>
            <input type="hidden" id="action" name="action" value="rota_drivers">
             <label>DATE</label>
            <input type="text" id="idle_date" name="idle_date" value="" class="fr"  required="required" >
            <div class="clear">&nbsp;</div>
             <label>FROM TIME</label>
            <input type="text" id="from_time" name="from_time" value="" class="fr"  placeholder="hh:mm:ss" required="required" >
            <div class="clear">&nbsp;</div>
             <label>END TIME</label>
            <input type="text" id="to_time" name="to_time" value="" class="fr"   placeholder="hh:mm:ss" required="required" >
            
            <div class="clear">&nbsp;</div>
             <label>IS REGULER</label>
            <input type="checkbox" name="is_reguler" value="" class=""   placeholder="hh:mm:ss" id="is_reguler" />
             
            	<div id="dispaly_days" style="display:none;"></div>
            	<input type="button" id="btn" value="ADD" class="btn_add fl" >
            	<input type="submit" id="btn" value="SAVE" class="btn_save fl" style="display:none;">
            	<input type="button" id="btn_close" value="CLOSE" class="btn_close fl">
        </form>
        </div>
          
          <div class="rota_content_bottom fl "> 
            <input type="button" id="btn" value="IMPORT" class="btn_import fr">
         </div>
      
      </div>
   </div>
	<script type="text/javascript"  src="<?php echo $glob['storeURL']; ?>js/script.js"></script>
</body>
</html>

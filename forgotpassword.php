<?php 
include("includes/includes.inc.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Dashboard-Tabs</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="css/bootstrap.min.css">
<link href="css/font-awesome.min.css" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<!--Header-Area-->
<div id="header" class="clearfix">
   <?php include('includes/main_header.php');?>
</div>
<!--/Header-Area-->

<div class="spacer40"></div>

<div class="clearfix"></div>

<!--Content-Area-->
<div class="content_area content_area_login">
  <div style="top:30%" class="container">
   	<div  id="content_login">
    	<div id="login">
        <div align="center"><?php echo $_SESSION['msg4'];?></div>
        
<div class="col-lg-2" style="margin-right:10px;"><img src="<?php echo $glob['storeURL'];?>images/login_img.png" /></div>

        <div class="col-lg-2 login_heading icabit_heading" >i<span class="yellow_color">Cab</span>it</div>

        	<div class="clearfix"></div>
                <hr class="login_hr" />
        	<form id="login_form" method="post" action="controller/user_controller.php">
            <input type="hidden" name="action" id="action" value="forgotpassword" /> 
            	<div class="spacer40"></div>
                <label>Your Email</label>
                <input name="email" type="text" value="">
                <div class="clearfix spacer20"></div>
                
                <input class="login_btn" type="submit" value="ENTER">
            </form>
        </div>
    </div>
    
    
    <div class="clearfix"></div>
  </div>
</div>

<!--/Content-Area-->
<div class="clearfix"></div>

<!--Footer-->

<div id="footer" class="clearfix">
  <center>
    <span>© 2015 <a href="http://icabit.com/" target="_blank">iCabit</a> Ltd - All Rights Reserved</span>
  </center>
</div>

<!--/Footer--> 

<!--Script--> 

<!--<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script> -->
<script src="<?php echo $glob['storeURL']; ?>js/jquery.min.js"></script>
<script src="js/bootstrap.min.js"></script>
</body>
</html>
<?php unset($_SESSION['msg4']);?>
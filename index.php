<?php 
include("includes/includes.inc.php");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Company Dashboard</title>
<link rel="icon" href="<?php echo $glob['storeURL']; ?>images/favicon.ico" type="image/x-icon" />
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
 <div align="center">
 <?php if(isset($_SESSION['msg']) && $_SESSION['msg'] != ''){ ?>
 <h3 style="color:red; font-weight:bold; font-family:Arial, Helvetica, sans-serif"><?php echo $_SESSION['msg'];?></h3>
 <?php } ?>
 </div>
<!--Content-Area-->
<div class="content_area content_area_login">
  <div style="top:30%" class="container">
   	<div  id="content_login">
    	<div id="login">
       

		<div class="col-lg-2" style="margin-right:10px; margin-left:35px;"><img src="<?php echo $glob['storeURL'];?>images/logo_btn_2x.png" /></div>



        	<div class="clearfix"></div>
            <hr class="login_hr" />        
            
        	<div class="clearfix spacer30"></div>

        	<form id="login_form" method="post" action="controller/user_controller.php">
            <input type="hidden" class="login_field" name="action" id="action" value="company_login" /> 

                <div class="clearfix spacer20"></div>
                <div class="clearfix spacer10"></div>
                <label>Username</label>
                <input name="email" type="text" value="">
                <div class="clearfix spacer20"></div>
                 <div class="clearfix spacer10"></div>
                 <label>Password</label>
                <input name="password" type="password" value="">
                 <div class="clearfix spacer20"></div>
                 <div class="clearfix spacer10"></div>
                 <div class="clearfix spacer10"></div>
                 
                <input class="login_btn" type="submit" value="SUBMIT">
                
            </form>
            <div class="clearfix"></div>
            <div class="forgetpass" >
            <a href="<?php echo $glob['storeURL']; ?>/forgotpassword.php"><span class="yellow_color">Forgot Password</span></a></div>
            
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
<?php unset($_SESSION['msg']);?>
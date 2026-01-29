<?php
ob_start();
include("includes/includes.inc.php");

session_destroy();
unset($_SESSION['user_id']);
$_SESSION['user_id']='';



  

header ("Location: ".$glob['storeURL']);
//$facebook->destroySession();
?>


<?php
include("includes/includes.inc.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<header></header>
<section>
<?php
$page_name=$_GET['page_name'].'.php';

if($_GET['parm']=='dashboard'){
include("dashboard/$page_name");
}else if($_GET['parm']=='settings'){
	include("settings/$page_name");
	}
?>
</section>
<footer></footer>
</body>
</html>
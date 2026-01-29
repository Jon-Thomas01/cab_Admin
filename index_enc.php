<?php
$debugTime['start'] = microtime();
//header("X-Haiku: Haikus are easy, but sometimes they don't make sense. Refrigerator");
//header("X-GLaDOS: You just keep on trying, til you run out of cake.");
require_once ("includes" . CC_DS . "functions.inc.php");
require_once ("classes" . CC_DS . "db" . CC_DS . "db.php");
$db = new db();
require_once ("classes" . CC_DS . "cache" . CC_DS . "cache.php");
$config = fetchdbconfig("config");
if ($_REQUEST['_g'] !== "rm")
{
		require_once ("classes" . CC_DS . "session" . CC_DS . "cc_session.php");
		$cc_session = new session();
		$lang = getlang("common.inc.php");
}
switch ($_REQUEST['_g'] == "sw")
{
		case "sw":
				require_once ("includes" . CC_DS . "global" . CC_DS . "switch.inc.php");
				break;
		default:
				//require_once ("index.php");
}
if (true)
{
		$debug = "<div style='margin-top: 15px; font-family: Courier New, Courier, mono; border: 1px dashed #666; padding: 10px; color: #000; background: #FFF'>";
		$debug .= "<strong>\$_POST Variables:</strong><br />" . cc_print_array($_POST) . "<hr size=1 />";
		$debug .= "<strong>\$_GET Variables:</strong><br />" . cc_print_array($_GET) . "<hr size=1 />";
		$debug .= "<strong>\$_COOKIE Variables:</strong><br />" . cc_print_array($_COOKIE) . "<hr size=1  />";
		$debug .= "<strong>\$cc_session->ccUserData  Variables:</strong><br />" . cc_print_array($cc_session->ccUserData) . "<hr size=1  />";
		$debug .= "<strong>MySQL Queries (" . count($db->queryArray) . "):</strong><br />" . cc_print_array($db->queryArray);
		$debug .= "</div>";
}
?>

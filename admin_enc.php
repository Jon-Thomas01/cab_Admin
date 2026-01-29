<?php
print_r($ccAdminData);

require ("classes" . CC_DS . "db" . CC_DS . "db.php");
$db = new db();
require ("classes" . CC_DS . "cache" . CC_DS . "cache.php");
$config = fetchdbconfig("config");
if (detectssl())
{
		$GLOBALS['storeURL'] = $config['storeURL_SSL'];
		$GLOBALS['rootRel'] = $config['rootRel_SSL'];
}
else
{
		$GLOBALS['storeURL'] = $glob['storeURL'];
		$GLOBALS['rootRel'] = $glob['rootRel'];
}
$lang = getlang("admin" . CC_DS . "admin_common.inc.php");
include_once ("classes" . CC_DS . "session" . CC_DS . "cc_admin_session.php");
$admin_session = new admin_session();
if (!in_array($_GET['_g'], array("logout", "login", "requestPass")))
{
		$ccAdminData = $admin_session->get_session_data();
}
if (isset($_GET['_g']))
{
		if (!($_GET['_g'] == "modules") && !empty($_GET['module']) && substr($_GET['_g'], 0, 7) == "modules")
		{
				if ($_GET['_g'] == "modules" && !empty($_GET['module']))
				{
						$moduleData = explode("/", $_GET['module']);
						$module = $moduleData[0];
						$moduleType = $moduleData[0];
						$moduleName = $moduleData[1];
						$moduleScript = isset($moduleData[2]) ? $moduleData[2] : "index";
				}
				$moduleFile = CC_ROOT_DIR . CC_DS . "modules" . CC_DS . $module . CC_DS . $moduleName . CC_DS . CC_DS . "admin" . CC_DS . $moduleScript . ".inc.php";
				require_once (file_exists($moduleFile) ? $moduleFile : CC_ROOT_DIR . CC_DS . $glob['adminFolder'] . CC_DS . "modules" . CC_DS . "index.inc.php");
		}
		else
		{
				if ($_GET['_g'] == "modules" && !empty($_GET['module']))
				{
						$moduleData = explode("/", $_GET['module']);
						$module = $moduleData[0];
						$moduleType = $moduleData[0];
						$moduleName = $moduleData[1];
						$moduleScript = isset($moduleData[2]) ? $moduleData[2] : "index";
						$moduleFile = CC_ROOT_DIR . CC_DS . "modules" . CC_DS . $module . CC_DS . $moduleName . CC_DS . CC_DS . "admin" . CC_DS . $moduleScript . ".inc.php";
						require_once (file_exists($moduleFile) ? $moduleFile : CC_ROOT_DIR . CC_DS . $glob['adminFolder'] . CC_DS . "modules" . CC_DS . "index.inc.php");
				}
				else
				{
						require_once (mkpath($_GET['_g']));
				}
		}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//else if(!$_GET['_g'] && $_GET['admin']!=1){
//		require_once ($glob['adminFolder'] . CC_DS . "sources" . CC_DS . "login.inc.php");
//	}
else 
{
	require_once ($glob['adminFolder'] . CC_DS . "sources" . CC_DS . "home" . CC_DS . "index.inc.php");
}
if (!isset($skipFooter))
{
		require_once ($glob['adminFolder'] . CC_DS . "includes" . CC_DS . "footer.inc.php");
}
$db->close();
?>
<?
include_once('../../config.inc.php');
include_once(COMMON_DIR.'mysql.inc.php');
include_once('render.php');

$current_stable = '005';

//Routes you to the correct API Version
if(isset($_REQUEST['api']) && is_numeric($_REQUEST['api'])){
	$api_v = $_REQUEST['api'];
} else {
	$api_v = $current_stable;
}
if(file_exists('api/' . $api_v . '.php')){
	require_once('api/' . $api_v . '.php');
} else {
	require_once('api/' . $current_stable . '.php');
}




?>
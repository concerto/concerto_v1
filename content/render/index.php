<?
include_once('../../config.inc.php');
include_once('render.php');

render($_REQUEST['type'], $_REQUEST['file'], $_REQUEST['width'], $_REQUEST['height']);

?>
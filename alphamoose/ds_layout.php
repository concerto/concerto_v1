<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title><?php echo join(' - ',array(
		'Concerto Interface v0.3 (dev)',
		$pageTitle));?></title>
<link rel="stylesheet" type="text/css" href="<? echo ADMIN_BASE_URL 
?>/css/admin_new.css" />
<link rel="stylesheet" type="text/css" href="<? echo ADMIN_BASE_URL 
?>/css/menu_tabs.css" />

<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<? 
echo $admin_base_url ?>/js/pngfix.js"></script>
<![endif]-->

<?php //renderHeadExtras() ?>
</head>


<body>
  <div id="header">
    <div id="header_padding">
      <? include("includes/menu_tabs.php"); ?>
    </div>
  </div>

<div id="content_header">
  <h2><?php echo join(' :: ',$breadcrumbs)?></h2>
  <h1><?php echo $pageTitle;?></h1>
</div>

<div id="maincontent">
<?php renderMessages() ?>
<?php $this->render();//renderAction() ?>
</div>

<!-- BEGIN Sidebar -->
<? include("includes/left_menu.php"); ?>
<!-- END Sidebar -->


</body>
</html>

<?php
function renderMessage($type, $msg)
{
	switch($type)
	{
		case "error": $col='red'; break;
		case "warn": $col='yellow'; break;
		case "stat": $col='green'; break;
		case "info": default: $col='#069';$text='white'; break;
	}
	return '<div style="width:100%;background-color:'.$col.';color:'.$text.'"><p style="padding:3px">'.
		$msg."</p></div>\n";
}
?>

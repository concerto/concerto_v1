<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo join(' - ',array('Concerto Interface v0.8 (dev)',
		$this->getTitle()));?></title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<? echo ADMIN_BASE_URL ?>/css/admin_new.css" />
<link rel="stylesheet" type="text/css" href="<? echo ADMIN_BASE_URL ?>/css/menu_tabs.css" />

<!--[if lt IE 7.]>
<script defer type="text/javascript" src="<? 
echo $admin_base_url ?>/js/pngfix.js"></script>
<![endif]-->

<link rel="stylesheet" type="text/css" href="<? echo ADMIN_BASE_URL ?>/ajaxtabs/ajaxtabs.css" />
<script type="text/javascript" src="<? echo ADMIN_BASE_URL ?>/ajaxtabs/ajaxtabs.js"></script>

<?php //renderHeadExtras() ?>
</head>

<body>
  <div id="header">
    <div id="header_padding">
      <? include("includes/menu_tabs.php"); ?>
    </div>
  </div>

<div id="content_header">
  <h1><?php echo $this->getTitle();?></h1>
  <h2><?php echo join(' :: ',$this->breadcrumbs)?></h2>
</div>

<div id="maincontent">
<?php renderMessages() ?>
<?php $this->render();//renderAction() ?>
</div>

<!-- BEGIN Sidebar -->
<? include("includes/left_menu.php"); ?>
<!-- END Sidebar -->

<div id="footer_gutter">&nbsp;</div>
<div id="footer">
  <div id="footer_padding">
    <p>Copyright &copy; 2008 Student Senate Web Technologies Group.</p>
  </div>
</div>

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

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo join(' - ',array('Concerto Interface v'.CONCERTO_VERSION, $this->getTitle()));?></title>
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/admin_new.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/menu_tabs.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.datepicker.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.lightbox.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.tablesort.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.tabs.css" />

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ieonly.css" />
<![endif]-->

<!--[if lt IE 7.]>
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ie6.css" />
<script defer type="text/javascript" src="<?=ADMIN_BASE_URL?>js/pngfix.js"></script>
<![endif]-->

<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/jquery.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.datepicker.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.lightbox.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.tablesort.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.tabs.js"></script>

<meta name="application-name" content="Concerto"/>
<meta name="description" content="RPI Digital Signage for Everyone."/>
<meta name="application-url" content="http://signage.rpi.edu"/>
<link rel="icon" href="<?=ADMIN_BASE_URL?>images/concerto_32x32.png" sizes="32x32"/>
<link rel="icon" href="<?=ADMIN_BASE_URL?>images/concerto_48x48.png" sizes="48x48"/>

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

<?php //renderHeadExtras() ?>
</head>

<body>
  <div id="header">
    <div id="header_padding">
      <? include("includes/menu_tabs.php"); ?>
    </div>
  </div>

<div id="content_header">
  <h1><?=$this->getTitle()?></h1>
  <h2><?=$this->getCrumbs()?></h2>
</div>

<div id="maincontent">
<?php renderMessages() ?>
<?php $this->render();//renderAction() ?>
<div style="clear:both;"></div>
</div>

<!-- BEGIN Sidebar -->
<? include("includes/left_menu.php"); ?>
<!-- END Sidebar -->

<div id="footer_gutter">&nbsp;</div>
<div id="footer">
  <div id="footer_padding">
    <p>Copyright &copy; 2008 Rensselaer Polytechnic Institute (Student Senate Web Technologies Group)</p>
    <p><a href="<?= ADMIN_URL ?>/pages/show/docs/">Support Center</a> | <a href="http://myrpi.org/webtech/list_posts">Web Tech Blog</a> | Contact Support: <a href="mailto:concerto@union.rpi.edu">concerto@union.rpi.edu</a></p>
  </div>
</div>
<?php if(defined('GA_TRACKING')) { ?>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("<?= GA_TRACKING ?>");
pageTracker._trackPageview();
</script>
<?php } ?>
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
   return '<div class="alertmess ' . $type . '"><p>'.
      $msg."</p></div>\n";
}
?>

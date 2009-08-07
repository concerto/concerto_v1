<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
 *
 * This program is free software; you can redistribute it and/or modify it 
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author: brian $
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision: 548 $
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title><?php echo join(' - ',array('Concerto Panel', $this->getTitle()));?></title>
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/admin_new.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/fp_new.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.lightbox.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.tablesort.css" />
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ui.jquery.css" />

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ieonly.css" />
<script language="javascript" type="text/javascript" src="<?=ADMIN_BASE_URL?>js/excanvas.pack.js"></script>
<![endif]-->

<!--[if lt IE 7.]>
<link rel="stylesheet" type="text/css" href="<?=ADMIN_BASE_URL?>css/ie6.css" />
<script defer type="text/javascript" src="<?=ADMIN_BASE_URL?>js/pngfix.js"></script>
<![endif]-->

<!--<script type="text/javascript" src="<?//=ADMIN_BASE_URL?>js/jquery.js"></script>-->
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/jquery-1.3.2.min.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.lightbox.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.tablesort.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/ui.jquery.js"></script>
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/fpnew.js"></script>
<meta name="application-name" content="Concerto"/>
<meta name="description" content="RPI Digital Signage for Everyone."/>
<meta name="application-url" content="http://<?=$_SERVER['SERVER_NAME'] . ADMIN_URL?>"/>
<link rel="icon" href="<?=ADMIN_BASE_URL?>images/concerto_32x32.png" sizes="32x32"/>
<link rel="icon" href="<?=ADMIN_BASE_URL?>images/concerto_48x48.png" sizes="48x48"/>

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

<?php //renderHeadExtras() ?>
</head>

<body>

<div id="header"<?php if (!isLoggedIn()) { ?> class="header-login"<?php } ?>>
  <div id="header_padding">
    <? include("includes/menu_tabs.php"); ?>
  </div>
</div>

<div id="main">
	<div id="fp_stripe">
		<div id="fp_stripe_padding">
			<h1><a href="<?= ADMIN_URL ?>/wall/">View More Live Content</a></h1>
			<h2>on the Concerto Wall</h2>
			<h3><a href="#">Get the Screensaver</a> | <a href="<?= ADMIN_URL ?>/pages/show/docs/">Visit the Support Center</a></h3>
		</div>
	</div>
	<div id="fp_display"><img src="<?= ADMIN_BASE_URL ?>/images/fp/fp_display.png" alt="" /></div>
	<div id="screen_overlay">
		<div id="scr-graphics" align="center"><img class="fp-exposed" src=""></div>
		<div id="scr-timedate"><?=date('D m/d h:i a')?></div>
		<div id="scr-text"></div>
		<div id="scr-ticker">&nbsp;</div>
	</div>
	<div id="maincontent" class="fp_maincontent">
		<?php renderMessages() ?>
		<?php $this->render();//renderAction() ?>
		<div style="clear:both;"></div>
	</div>
</div>

<!-- BEGIN Sidebar -->
<? include("includes/left_menu.php"); ?>
<!-- END Sidebar -->

<div id="footer_gutter">&nbsp;</div>
<div id="footer">
  <div id="footer_padding">
    <p>Copyright &copy; 2009 Rensselaer Polytechnic Institute (Student Senate Web Technologies Group)</p>
    <p><a href="<?= ADMIN_URL ?>/pages/show/docs/">Support Center</a> | <a href="http://webtech.union.rpi.edu">Web Tech Group</a> | Contact Support: <a href="mailto:<?= SYSTEM_EMAIL ?>"><?= SYSTEM_EMAIL ?></a></p>
  </div>
</div>
<?php if(defined('GA_TRACKING') && GA_TRACKING) { ?>
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

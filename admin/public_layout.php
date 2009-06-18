<?php
/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technologies Group)
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
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Concerto - <?= $this->getTitle() ?></title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?= ADMIN_BASE_URL ?>css/public.css" />
<script src="<?=ADMIN_BASE_URL?>js/jquery-1.2.6.min.js" type="text/javascript"></script>
<script src="<?=ADMIN_BASE_URL?>js/jquery.localscroll-1.2.6-min.js" type="text/javascript"></script>
<script src="<?=ADMIN_BASE_URL?>js/jquery.scrollTo-1.4.0-min.js" type="text/javascript"></script>
<script src="<?=ADMIN_BASE_URL?>js/jquery.serialScroll-1.2.1-min.js" type="text/javascript"></script>
<script src="<?=ADMIN_BASE_URL?>js/ui.slider.js" type="text/javascript"></script>
<script src="<?=ADMIN_BASE_URL?>js/jquery.dimensions.js" type="text/javascript"></script>  
<script src="<?=ADMIN_BASE_URL?>js/jquery.accordion.min.js" type="text/javascript"></script>
<script type="text/javascript">
<!--
$(function () {
  $('UL.drawers').accordion({
    // the drawer handle
    header: 'H2.drawer-handle',
    
    // our selected class
    selectedClass: 'open',
    
    // match the Apple slide out effect
    event: 'mouseover'
  });
});
-->
</script>
</head>

<body>
<div id="wrap">
  <div id="header"><div id="header_inset"><div id="header_padding">
    <div style="float:left;"><a href="<?=ADMIN_URL.'/'.$this->controller.'/'.$this->action.'/'.$this->category['path']?>"><img src="<?= ADMIN_BASE_URL ?>images/public/logo.png" alt="" /></a></div>
    <div style="float:right;">
      <ul class="menu">
<?php
if(is_array($this->menu_links))
     foreach($this->menu_links as $ar)
         echo "<li><a href=\"".ADMIN_URL.'/'.$this->controller.'/'.$this->action."/$ar[url]\">$ar[name]</a></li>";
?>
      </ul>
    </div>
    <div style="clear:both; height:10px;"></div>
  </div></div></div>
  <div id="main">
    <!-- main content begins here -->
      <?php renderMessages() ?>
      <?php $this->render(); ?>   
    <!-- main content ends here -->
    <div style="clear:both;"></div>
    
  </div>
  
  <div id="footer">
  	<div id="footer_inset">
  		<div id="footer_padding">
  			<div style="float:left; width:48%; text-align:left;"><h1><b>Copyright &copy; 2008 RPI Web Tech Group.</b>  All rights reserved.</h1></div>
  			<div style="float:right; width:48%; text-align:right;"><a href="http://myrpi.org/webtech"><img border="0" src="<?= ADMIN_BASE_URL ?>images/wtg_logo.gif" alt="" /></a></div>
  		</div>
  	</div>
  </div>
  <div style="clear:both;"></div>
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

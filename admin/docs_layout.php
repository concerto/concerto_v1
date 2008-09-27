<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Concerto Support - <?=$this->getTitle()?></title>

<link rel="stylesheet" type="text/css" href="<?= ADMIN_BASE_URL ?>css/docs.css" />
<script type="text/javascript" src="<?=ADMIN_BASE_URL?>js/jquery-1.2.6.min.js"></script>

<meta name="application-name" content="Concerto Support"/>
<meta name="description" content="One-Stop Support for the RPI Digital Signage Network."/>
<meta name="application-url" content="http://signage.rpi.edu/admin/pages/show/docs/"/>
<link rel="icon" href="<?=ADMIN_BASE_URL?>images/concerto_32x32.png" sizes="32x32"/>
<link rel="icon" href="<?=ADMIN_BASE_URL?>images/concerto_48x48.png" sizes="48x48"/>

<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

</head>

<body>

<div id="left_pane"><div id="left_pane_padding">
  <!-- BEGIN Sidebar -->
  <br /><center><a href="<?=ADMIN_URL.'/'.$this->controller.'/'.$this->action.'/'.$this->category['path']?>">
  <img src="<?= ADMIN_BASE_URL ?>images/conc_support.gif" alt="Concerto Support" style="" border="0" />
  </a></center>
  <h1><a href="<?= ADMIN_URL ?>">Control Panel</a></h1>
  <div id="toc">
    <ol>
<?php
if(is_array($this->menu_links))
     foreach($this->menu_links as $ar)
        echo "<li><a href=\"".ADMIN_URL.'/'.$this->controller.'/'.$this->action."/$ar[url]\">$ar[name]</a></li>";
?>
    </ol>
  </div>
  <!-- END Sidebar -->
  <div style="clear:both;"></div>
</div></div>

<div id="right_pane">
  <div id="header">
    <div id="header_padding">
    </div>
  </div>

  <div id="content_header">
    <h1><?=$this->getTitle()?></h1>
    <h2><?=$this->getSubtitle()?></h2>
  </div>

  <div id="maincontent">
  <!-- main content begins here -->
<?php renderMessages() ?>
<?php $this->render(); ?>

  <!-- main content ends here -->

<p><a href="#">Return to Top</a></p>
  </div>
</div>

  <div id="footer_gutter">&nbsp;</div>
  <div id="footer">
    <div id="footer_padding">
      <p>Copyright &copy; 2008 Rensselaer Polytechnic Institute (Student Senate Web Technologies Group)</p>
      <p><a href="<?= ADMIN_URL ?>">Control Panel</a> | <a href="http://myrpi.org/webtech/list_posts">Web Tech Blog</a> | Contact Support: <a href="mailto:concerto@union.rpi.edu">concerto@union.rpi.edu</a></p>
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

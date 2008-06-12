<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" 
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Concerto - <?= $this->getTitle() ?></title>
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
<link rel="stylesheet" type="text/css" href="<?= ADMIN_BASE_URL ?>css/public.css" />

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
    <div style="clear:both; height:10px;"></div>
  </div>
  <div id="footer"><div id="footer_shadow"></div><div id="footer_padding">
    <h1>Copyright &copy; 2008 Rensselaer Polytechnic Institute (Student Senate Web Technologies Group)</h1>
  </div></div>
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

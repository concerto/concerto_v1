<a href="<?=ADMIN_URL.'/content/new'?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Submit Content</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<h2>All active content in the system is shown.  Click on a title for details.</h2>
<?php
if(is_array($this->contents) && count($this->contents>1))
{
foreach(array_keys($this->contents) as $field)
     $urls[]='<a href="#'.$field.'">'.$field.'</a>';
?>
<p>Jump to: <?=join(" | ", $urls)?>
</p>
<?php
} else {
	echo "<p>There is no active content in the system.</p>";
}
if(is_array($this->contents))
foreach($this->contents as $field=>$contents)
{
   echo "<br /><br /><h1><span class=\"emph\"><a name=\"$field\" ></a>$field</span>&nbsp;&nbsp;&nbsp;<a href=\"#\">top</a></h1>";
?>
<table class="edit_win" cellpadding="0" cellspacing="0">
<?php
   $notfirst=0; //style for first row
   foreach($contents as $content) {
      $submitter = new User($content->user_id);
      if(preg_match('/image/',$content->mime_type)) {
        $has_imagecol=1;

?>
  <!-- NEW COLLAPSED LIST CODE BEGINS HERE -->
  <tr class="minlist">
    <td colspan="2">
      <table class="minedit" cellpadding="0" cellspacing="0" width="100%"><tr>
      <td valign="middle" width="65">
<?php
if ($has_imagecol) { 
?>
      <img src="<?= ADMIN_URL?>/content/image/<?= $content->id ?>?width=50&height=38" />
<?php } ?>
      </td><td><span class="mintitle"><span class="emph"><a href="<?= ADMIN_URL?>/content/show/<?= $content->id ?>"><?= $content->name ?></a></span> <b><?= date("m/j/y",strtotime($content->start_time)) ?> - <?= date("m/j/y",strtotime($content->end_time)) ?></b></span></td></tr></table>
    </td>
  </tr>
  <!-- NEW COLLAPSED LIST CODE ENDS HERE -->

  <tr>
    <td<? if (!$notfirst) echo ' class="firstrow"'; ?>>
    <a href="<?= ADMIN_URL?>/content/show/<?= $content->id ?>"> 
    <img src="<?= ADMIN_URL?>/content/image/<?= $content->id ?>?width=200&height=150" />
    </a>
    </td>
<?php
      }
?>

    <td class="edit_col<? if (!$notfirst) {$notfirst =1;  echo ' firstrow';} ?>"
        <?if(!$has_imagecol) echo "colspan=2";?>>
      <a href="<?= ADMIN_URL?>/content/show/<? echo $content->id ?>">
       <h1><a href="<?= ADMIN_URL?>/content/show/<? echo $content->id ?>"><?=$content->name?></a></h1>
       <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;">
<?php
          if($content->mime_type == "text/plain")
             echo "$content->content<br/>\n";
?>
       <?=date("m/j/Y",strtotime($content->start_time))?> - <?=date("m/j/Y",strtotime($content->end_time))?></span>
       (# Weeks)
       <h2>Submitted by <strong><a href="<?=ADMIN_URL.'/users/show/'.$submitter->username?>"><?=$submitter->name?></a></strong></h2>
      </a>
    </td>
  </tr>

<?php
   }
?>
</table>
<?php
}
?>

<?php if ($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/feeds/edit/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a> 
<a href="<?=ADMIN_URL.'/feeds/delete/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php } ?>
</p>
      <h3>Group: <span class="emph"><a href="<?=ADMIN_URL.'/groups/show/'.$this->group->id?>"><?= $this->group->name ?></a></span></h3>
<?php
if($this->canEdit) {
?>
      <h3>Moderation status: <span class="emph">
<?php
	echo '<a href="'.ADMIN_URL.'/feeds/moderate/'.$this->feed->id.'">'.$this->waiting.'</a>';
?>
      </span></h3>
<?php
}
?>
     <h3>Active and Future Content</h3>

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
	echo "<p><em>This feed has no approved active or future content.</em></p>";
}
if(is_array($this->contents))
foreach($this->contents as $field=>$contents)
{
   echo "<a name=\"$field\"></a><h1>$field</h1>";
?>
<table class="edit_win" cellpadding="6" cellspacing="0">
<?php
   $notfirst=0; //style for first row
   foreach($contents as $content) {
      $submitter = new User($content->user_id);
?>
  <tr>
<?php
      if(preg_match('/image/',$content->mime_type)) {
        $has_imagecol=1;
?>
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
       <h2><img src='$stat' style='border:0px;' border='0' alt='' /></h2>
       <h1><a href="<?= ADMIN_URL?>/content/show/<? echo $content->id ?>"><?=$content->name?></a></h1>
       <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;">
<?php
          if($content->mime_type == "text/plain")
             echo "$content->content<br/>\n";
?>
       <?=date("m/j/Y",strtotime($content->start_time))?> - <?=date("m/j/Y",strtotime($content->end_time))?></span>
       <h2>Submitted by <strong><a href="<?=ADMIN_URL.'/users/show/'.$submitter->username?>"><?=$submitter->name?></a></strong></h2>
<? if ($this->canEdit) { ?>
       <h2><a href="<?=ADMIN_URL?>/feeds/deny/<?=$this->feed->id.'/'.$content->id?>" title="Unapprove this content">Remove from feed</a></h2>
<? } ?>
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

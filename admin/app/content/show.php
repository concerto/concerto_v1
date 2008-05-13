<?php if ($this->canEdit) {?>
<!--<a href="<?=ADMIN_URL.'/content/edit/'.$this->content->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Item</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a> -->
<a href="<?=ADMIN_URL.'/content/remove/'.$this->content->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Item</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php } ?>
</p>
<?php
if(preg_match('/image/',$this->content->mime_type)) {
?>
<a href="<?=ADMIN_URL.'/content/image/'.$this->content->id?>">
<img src="<?=ADMIN_URL.'/content/image/'.$this->content->id?>?width=400&height=300" alt="Content Image" style="float:left; border:1px solid #aaa; margin-right:10px" />
</a>
<?php
} else {
?>
<div style="padding:30px; top:50px; right:50px; bottom:50px; width:400px; background:url(<?=ADMIN_BASE_URL?>/images/lightblue_bg.gif); border:1px solid #aaa">
<?= $this->content->content ?>
</div>
<?php
}
?>

<h3>Run dates: <span class="emph"><?=date("m/j/Y H:i",strtotime($this->content->start_time))?> - <?=date("m/j/Y H:i",strtotime($this->content->end_time))?></span></h3>

<h3>Submitted By: <span class="emph"><a href="<?=ADMIN_URL.'/users/show/'.$this->submitter->username?>"><?=$this->submitter->name?></a></span></h3>

<br clear="left">
<?php

if(is_array($this->act_feeds)) {
?>
<h3>Feeds this appears on:</h3>
<ul>
<?php
    foreach ($this->act_feeds as $feed)
       echo '<li><a href="'.ADMIN_URL.'/feeds/show/'.$feed['feed']->id.'">'.$feed['feed']->name.'</a></li>'; 
}
?>
</ul>

<?php
if(is_array($this->wait_feeds))
{
?>
<h3>Awaiting approval on:</h3>
<ul>
<?php
   foreach ($this->wait_feeds as $feed)
      echo '<li><a href="'.ADMIN_URL.'/feeds/show/'.$feed['feed']->id.'">'.$feed['feed']->name.'</a></li>';
}
?>
</ul>

<?php
if(is_array($this->denied_feeds))
{
?>
<h3>Rejected From:</h3>
<ul>
<?php
   foreach ($this->denied_feeds as $feed)
      echo '<li><a href="'.ADMIN_URL.'/feeds/show/'.$feed['feed']->id.'">'.$feed['feed']->name.'</a></li>';
}
?>
</ul>

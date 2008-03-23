<?php if ($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/feeds/edit/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a> 
<a href="<?=ADMIN_URL.'/feeds/delete/'.$this->feed->id ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Delete Feed</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
<?php } ?>
</p>
      <h3>Group: <span class="emph"><a href="<?=ADMIN_URL.'/groups/show/'.$this->group->id?>"><?= $this->group->name ?></a></span></h3><br />
      <h3>Active and Future Content</h3>
      <p>
<?php
	if($this->canEdit) echo '<a href="'.ADMIN_URL.'/feeds/moderate/'.$this->feed->id.'">'.$this->waiting.'</a>';
	else echo $this->waiting;
?>
      </p>
      <table class="edit_win" cellpadding="6" cellspacing="0">
<?php 
if(is_array($this->contents)) {
foreach ($this->contents as $content) { 
$cont = new Content($content[content_id]);
?>
        <tr>
          <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
          <a href="<?= ADMIN_URL?>/content/show/<?= $cont->id ?>">
          <h1><?= $cont->name ?></h1>
          </a>
        </tr>
<?php }
}?> 
      </table>

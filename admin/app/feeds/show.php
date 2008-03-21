<p><a href="<?echo ADMIN_URL ?>/feeds">Back to Feeds Listing</a>
<?php if ($this->canEdit) {?>
 | <a href="<?echo ADMIN_URL ?>/feeds/edit/<?echo $this->feed->id ?>">Edit Feed</a>
 | <a href="<?echo ADMIN_URL ?>/feeds/destroy/<?echo $this->feed->id ?>">Delete Feed</a>
<?php } ?>
</p>
      <h3>Location:</h3>
      <p><? echo $this->screen->location?></p>
      <h3>Group:</h3>
      <p><a href="<?=ADMIN_URL.'/groups/show/'.$this->group->id?>">
         <?=$this->group->name?>
      </a></p>
      <h3>Active and Future Content</h3>
      <table class="edit_win" cellpadding="6" cellspacing="0">
<?php foreach ($this->feed->content_list("1") as $content) { 
$cont = new Content($content[content_id]);
?>
        <tr>
          <td<? if (!$notfirst) {$notfirst =1;  echo ' class="firstrow"';} ?>>
          <a href="<?= ADMIN_URL?>/content/show/<? echo $cont->id ?>">
          <h1><?= $cont->name ?></h1>
          </a>
        </tr>
<?php }?> 
      </table>

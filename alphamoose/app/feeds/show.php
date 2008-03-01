<p><a href="<?echo ADMIN_URL ?>/feeds">Back to Feeds Listing</a>
<?php if ($this->canEdit) {?>
 | <a href="<?echo ADMIN_URL ?>/feeds/edit/<?echo $this->id ?>">Edit Feed</a>
<?php } ?>
</p>
      <h3>Location:</h3>
      <p><? echo $this->screen->location?></p>
      <h3>Screen:</h3>
      <p><?php echo $this->screen->width.' x '.$this->screen->height.' ('.$ratio; 
?>)</p>
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


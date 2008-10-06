<?php
if (is_array($this->notifications)) {
foreach($this->notifications as $notification) {
?>
   <p class="<?= $notification->type ?>_<?= $notification->msg ?>"><?= $notification->text ?>
   <span class="datesub"><?= date('M j', $newsfeed->timestamp) ?></span></p>
<?php
}
}
?>

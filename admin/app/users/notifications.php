<?php
if (is_array($this->notifications)) {
foreach($this->notifications as $notification) {
?>
  	<p class="<?= $notification->type ?>_<?= $notification->msg ?>"><?= $notification->text ?><span class="datesub"><?= date('M j', $notification->timestamp) ?></span>
        <?php
          if($notification->has_extra){
            echo '<br/><span class="newsfeed_reason">'.$notification->additional.'</span>';
          }
        ?>
   </p>
<?php
}
}
?>

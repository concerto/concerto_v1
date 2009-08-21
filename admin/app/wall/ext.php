<? if (($this->feed->type == 3) || ($this->content->get_moderation_status($this->feed) != 1)){ ?>
  Invalid request.
<? } else { ?>
<img src="<?= ADMIN_URL ?>/content/image/<?= $this->content->id ?>?width=590&height=460" alt="<?= $this->content->name ?>" />
<h2>from the <b><?= $this->feed->name ?></b> feed</h2>
<? } ?>
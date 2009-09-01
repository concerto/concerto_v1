<? if (($this->feed->type == 3) || ($this->content->get_moderation_status($this->feed) != 1)){ ?>
  Invalid request.
<? } else { ?>
<a href="<?= ADMIN_URL ?>/content/show/<?= $this->content->id ?>" target="_blank"><img src="<?= ADMIN_URL ?>/content/image/<?= $this->content->id ?>?width=590&height=460" alt="<?= $this->content->name ?>" /></a>
<div id="overlay_details">
	<div style="float:left; width:24%;">
		<h2><span class="overlay_start"><?= date('M',strtotime($this->content->start_time)) ?> <span class="overlay_date"><?= date('j',strtotime($this->content->start_time)) ?></span></span><span class="overlay_to"> to </span><span class="overlay_end"><?= date('M',strtotime($this->content->end_time)) ?> <span class="overlay_date"><?= date('j',strtotime($this->content->end_time)) ?></span></span></h2>
	</div>
	<div style="float:right; width:74%;">
		<h1><span>Feed:</span> <?= $this->feed->name ?></h1>
		<h1><span>By:</span> <?= $this->submitter->name ?></h1>
	</div>
</div>
    
<? } ?>
<? if (($this->feed->type == 3) || ($this->content->get_moderation_status($this->feed) != 1)){ ?>
  Invalid request.
<? } else { ?>
<div id="overlay_graphic" style="text-align: center;">
  <a href="<?= ADMIN_URL ?>/content/show/<?= $this->content->id ?>" target="_blank"><img src="<?= ADMIN_URL ?>/content/image/<?= $this->content->id ?>?width=590&height=460" alt="<?= $this->content->name ?>"/></a>
</div>
<div id="overlay_details">
<?
  /*For non-AJAX requests we'll center the text
    We also have to encode things a bit differently 
    for AJAX, like Marc's last name
  */
  if(isset($_REQUEST['ajax'])){
    $width_left = 24;
    $width_right = 74;
    $submitter = utf8_encode($this->submitter->name);
  } else {
    $width_left = 49;
    $width_right = 49;
    $submitter = $this->submitter->name;
  }
?>
	<div style="float:left; width:<?= $width_left ?>%;">
		<h2><span class="overlay_start"><?= date('M',strtotime($this->content->start_time)) ?> <span class="overlay_date"><?= date('j',strtotime($this->content->start_time)) ?></span></span><span class="overlay_to"> to </span><span class="overlay_end"><?= date('M',strtotime($this->content->end_time)) ?> <span class="overlay_date"><?= date('j',strtotime($this->content->end_time)) ?></span></span></h2>
	</div>
	<div style="float:right; width:<?= $width_right ?>%;">
		<h1><span>Feed:</span> <?= $this->feed->name ?></h1>
		<h1><span>By:</span> <?= htmlspecialchars($submitter) ?></h1>
	</div>
</div>
<? if(!isset($_REQUEST['ajax'])){ ?>
<div id="bottomstrip">
        <div id="bottomstrip-padding">
                <a href="<?= ADMIN_URL ?>/wall/feedgrid/<?= $this->feed->id ?>">&lt;&lt; Back to the <?= $this->feed->name ?> Feed</a>
        </div>
</div>
<? } ?>

<? } ?>

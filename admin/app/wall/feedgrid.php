<div id="feedgrid">
<? if(strlen($this->feed->name) <= 0 || $this->feed->type == 3) { ?>
  Invalid request.
<? } else { ?>
<div id="wallthumbs">
  <? foreach ($this->contents as $content) { ?>
    <div class="UIWall_thumb">
      <a class="overlayTrigger" href="<?= ADMIN_URL ?>/wall/ext/<?= $this->feed->id ?>/<?= $content->id ?>" rel="#oz">
        <div class="UIWall_wrapper">
          <img class="UIWall_image" src="<?= ADMIN_URL ?>/content/image/<?= $content->id ?>?width=200&height=150" alt="<?= $content->name ?>" />
        </div>
      </a>
    </div>
  <? } ?>
</div>
<? } ?>
</div>
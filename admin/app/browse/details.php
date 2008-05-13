<table>
<tr>
<td class="preview <? if(preg_match('/text/',$this->content->mime_type)) { echo " text_bg"; } ?>" style="width:250px">
<? if(preg_match('/image/',$this->content->mime_type)) { ?>
    <a id="i-preview" href="<?= ADMIN_URL ?>/content/image/<?= $this->content->id ?>"><img src="<?= ADMIN_URL ?>/content/image/<?= $this->content->id ?>?width=250&amp;height=200" alt="" /></a>
<? } elseif(preg_match('/text/',$this->content->mime_type)) { ?>
    <span class="emph"><?= $this->content->content ?></span>
<? } ?>
</td>
<td>
    <h1><a href="<?= ADMIN_URL ?>/content/show/<?= $this->content->id ?>"><?= $this->content->name ?></a></h1>
    <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;"><?= date('M j, Y',strtotime($this->content->start_time)) ?> - <?= date('M j, Y',strtotime($this->content->end_time)) ?></span> <? if($this->week_range > 1) echo "({$this->week_range} Weeks)" ?>
    <h2>Display duration: <span class="emph"><img src="<?= ADMIN_BASE_URL ?>/images/stopwatch.gif" alt="Duration" /> <?=$this->content->duration/1000?> seconds</span></h2>
    <h2>Submitted by <strong><a href="<?= ADMIN_URL ?>/users/show/<?= $this->submitter->id ?>"><?= $this->submitter->name ?></a></strong></h2>
</td>
</tr>
</table>

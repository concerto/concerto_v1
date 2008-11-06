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
    <h1><a href="<?= ADMIN_URL ?>/content/show/<?= $this->content->id ?>"><?= $this->content->name ?></a>
<?php
        if($this->feed->user_priv($_SESSION['user'], 'moderate')) {
           if($this->status==0) {
              echo '&nbsp;&nbsp;&nbsp;<small><a title="Approve Content for Feed" href="'.ADMIN_URL.'/moderate/confirm/approve/'.$this->feed->id.'/'.$this->content->id.'"><img src="'. ADMIN_BASE_URL . '/images/icon_approve.gif" border="0" alt=""  /> <span style="color:green !important; font-size:0.8em;">Approve</span></a></small>';
           } elseif ($this->status==1) {
              echo '&nbsp;&nbsp;&nbsp;<small><a title="Remove Content from Feed" href="'.ADMIN_URL.'/moderate/confirm/deny/'.$this->feed->id.'/'.$this->content->id.'"><img src="'. ADMIN_BASE_URL . '/images/icon_disapprove.gif" border="0" alt="" /> <span style="color:red !important; font-size:0.8em;">Disapprove</span></a></small>';
           }
        }
?>  </h1>
    <span style="font-size:1.5em;font-weight:bold;color:#333;margin-bottom:12px;"><?= date('M j, Y',strtotime($this->content->start_time)) ?> - <?= date('M j, Y',strtotime($this->content->end_time)) ?></span> <? if($this->week_range > 1) echo "({$this->week_range} Weeks)" ?>
    <h2>Display duration: <span class="emph"><img src="<?= ADMIN_BASE_URL ?>/images/stopwatch.gif" alt="Duration" /> <?=$this->dur_str?></span></h2>
    <h2>Submitted by <strong><a href="<?= ADMIN_URL ?>/users/show/<?= $this->submitter->id ?>"><?= $this->submitter->name ?></a></strong></h2>
<? if($this->moderator->id != false) { ?>
    <h2>Moderated by <strong><a href="<?= ADMIN_URL ?>/users/show/<?= $this->moderator->id ?>"><?= $this->moderator->name ?></a></strong></h2>
<? } ?>
</td>
</tr>
</table>

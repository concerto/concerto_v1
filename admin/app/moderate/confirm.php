<form action="<?=ADMIN_URL?>/moderate/post" method="post">
<input type="hidden" name="feed_id" value="<?=$this->feed->id?>" />
<input type="hidden" name="content_id" value="<?=$this->content->id?>" />
<input type="hidden" name="action" value="<?=$this->args[1]?>" />
<? if($this->args[1]=="approve") { ?>
<h1>Duration:</h1>
<p><span class="mod_confirm" title="Duration (seconds)"><input type="text" name="duration" value="<?=$this->content->get_duration($this->feed)/1000?>" size="2" /></span></p>
<? } else { ?>
<h1>Reason for Rejection:</h1>
<p>
<select name="information">
<?
$choices = array("Your content is not applicable to my feed.",
                 "Your content is too hard to read.",
                 "Your content is redundant.",
                 "Your content is inappropriate.");
foreach($choices as $choice) {
?>
<option value="<?= $choice ?>"><?= $choice ?></option>
<? } ?>
</select>
</p>
<? } ?>
<h1>Additional Message to Send to Submitter:</h1>
<p><textarea name="notification" rows="3" cols="30"></textarea></p>
<? if($this->args[2] != "ajax") { ?>
<input type="submit" value="Submit" />
<? } else { ?>
<input type="hidden" name="ajax" value="1" />
<? } ?>
</form>

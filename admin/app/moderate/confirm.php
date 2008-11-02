<form action="<?=ADMIN_URL?>/moderate/post" method="post">
<input type="hidden" name="feed_id" value="<?=$this->feed->id?>" />
<input type="hidden" name="content_id" value="<?=$this->content->id?>" />
<input type="hidden" name="action" value="<?=$this->args[1]?>" />
<? if($this->args[1]=="approve") { ?>
<div class="ui-dialog-text">Duration</div>
<p><span class="mod_confirm" title="Duration (seconds)"><input type="text" name="duration" value="<?=$this->content->get_duration($this->feed)/1000?>" size="2" /></span></p>
<? } else { ?>
<div class="ui-dialog-text">Reason for Shittiness</div>
<p>
<select name="information">
<option value="Frest Milk">Fresh Milk</option>
<option value="Old Cheese">Old Cheese</option>
<option value="Hot Bread">Hot Bread</option>
</select>
</p>
<? } ?>
<i>Additional Information</i>
<p><textarea name="notification" rows="3" cols="30"></textarea></p>
<? if($this->args[2] != "ajax") { ?>
<input type="submit" value="Submit" />
<? } else { ?>
<input type="hidden" name="ajax" value="1" />
<? } ?>
</form>

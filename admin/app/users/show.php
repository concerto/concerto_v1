
<?php if ($this->canEdit) {?>
<a href="<?=ADMIN_URL.'/users/edit/'.$this->user->username ?>"><span class="buttonsel"><div class="buttonleft"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_left.gif" border="0" alt="" /></div><div class="buttonmid"><div class="buttonmid_padding">Edit Profile</div></div><div class="buttonright"><img src="<?= ADMIN_BASE_URL ?>/images/buttonsel_right.gif" border="0" alt="" /></div></span></a><div style="clear:both;height:12px;"></div>
</a>
<?php } ?>
</p>
<h3>Username: <span class="emph"><? echo $this->user->username?></span></h3>
<h3>Groups:</h3>
<ul>
<?php
  if(count($this->groups)<1)
     echo '<li><em>none</em></li>';
  foreach($this->groups as $group)
     echo '<li>'.$group.'</li>';
?>
</ul>
<?php if($this->canEdit) {?>
<h3>Contact: <span class="emph"><a href="mailto:<?php echo $this->user->email?>"><?php echo $this->user->email?></a></h3>
<? } ?>
<br />

<h3>Submissions</h3>

<?php
if(is_array($this->contents) && count($this->contents>1))
{
foreach(array_keys($this->contents) as $field)
  $urls[]='<a href="#'.$field.'">'.$field.'</a>'; ?>
	<p><em>Only content approved on one or more feeds is shown.</em></p>
	<p>Jump to: <?=join(" | ", $urls)?></p>
	<?php
} else {
	echo "<p><em>This user has had no content approved on one or more feeds.</em></p>";
}
if(is_array($this->contents))
foreach($this->contents as $field=>$contents)
{
	echo "<br /><br />";
?>
	<?php echo "<a name=\"$field\"></a><h1>$field</h1>"; ?>
	<table class="content_listing" cellpadding="6" cellspacing="0">
			<thead>
					<tr>
							<th>Preview</th>
							<th class="driver">Name</th>
							<th>Start Time</th>
							<th>End Time</th>
					</tr>
			</thead>
			<tbody>
	<?php
	if($contents){
			foreach($contents as $content) {
					$submitter = new User($content->user_id); ?>
					<tr id="c<?= $content->id ?>" class="listitem listitem_none">
							<td class="listh_icon"><?php
								if(preg_match('/image/',$content->mime_type)) {
									echo "<a href=\"http://signage.rpi.edu/admin/content/show/$content->id\"><img class=\"icon_border\" src=\"".ADMIN_URL."/content/image/$content->id?width=50&amp;height=37\" alt=\"Icon\" /></a>";
								} elseif(preg_match('/text/',$content->mime_type)) {
									echo "<img src=\"".ADMIN_BASE_URL."images/icon_text.gif\" alt=\"Icon\" />";
								} else {
									echo "&nbsp;";
								} ?></td>
							<td class="listtitle">
									<a href="http://signage.rpi.edu/admin/content/show/<?= $content->id ?>"><?= htmlspecialchars($content->name) ?></a>
							</td>
							<td><?=date("m/j/Y",strtotime($content->start_time))?></td>
							<td><?=date("m/j/Y",strtotime($content->end_time))?></td>
					</tr>
	<?php
			}
	} else {
	?>
					<tr><td colspan="4">This user has had no content approved on one or more feeds.</td></tr>
	<?php
	}
	?>
			</tbody>
	</table>

<?php
}
?>




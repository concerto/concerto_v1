<img src="<?=ADMIN_URL?>/templates/preview/<?=$this->template['id']?>" style=" border: 1px solid #aaa; display:inline;margin:25px;float:left" />
       <div style=" height:260px; top:0px; float:left; margin-left:30px;">
   <p style="width:280px; bottom:150px; padding:30px; margin-top:50px; background:url(../images/lightblue_bg.gif);
border:1px solid #aaa">Your screen is divided up into several areas, each of which can display different types of content.
   Use these controls to select feeds (categories of content, i.e. <i>Student Union</i>, which contains student clubs' content) to place in
each area, and how often to display each.</p>
   </div>
<br clear=left />

   <div>
   <form method="POST" action="<?=ADMIN_URL?>/screens/subscribe/<?=$this->screen->id?>">
     <ul class="subscriptions">
<?php
$fields_list=$this->screen->list_fields();
if(is_array($fields_list)){
foreach($fields_list as $field) {
?>

<li id ="field_<?=$field->id?>"><h2><span class="emph"><? echo $field->name ?></span> (Field)</h2>
<ul>

<?php
$positions = $field->list_positions();
if(is_array($positions)) {
   foreach($positions as $pos) {
		$feed = new Feed($pos->feed_id);	
		$value = $pos->weight;

      echo '<li id="pos_'.$field->id.'_'.$feed->id.'"><select name="content[freq]['.$field->id.']['.$feed->id.']">';
      echo '<option value="0"'.($value<=0?' selected':'').'>Never</option>';
      echo '<option value=".33"'.($value<=.33&&$value>0?' selected':'').'>Sometimes</option>';
      echo '<option value=".66"'.($value<=.66&&$value>.33?' selected':'').'>Moderately</option>';
      echo '<option value="1.00"'.($value>.66?' selected':'').'>Very Often</option>';
      echo '</select>';

?>
        display content from <a href="<?=ADMIN_URL.'/feeds/show/'.$feed->id?>">
           <?=$feed->name?></a>
           (<a href="#" onclick="removePos(<?=$field->id.','.$feed->id.',\''.$feed->name?>'); return false;">
            remove</a> )
           </li>
<?php
   }
} else echo "<li>(no current subscriptions)</li>";
      ?>


</ul>

	<p>
	  Add a feed to this field: 
	  <select id="add_<?=$field->id?>">
     <option value="" SELECTED></option>
     <?php
       foreach($field->avail_feeds() as $feed) {
          echo "<option value=\"$feed->id\">$feed->name</option>";
       }
     ?>
	  </select>
	  <input type="submit" onclick="addPos(<?=$field->id.',\''.ADMIN_URL.'/feeds/show/'?>'); return false;" value="Add" />
	</p>

</li>

<?php
}
}
?>
</ul>
   <input type="submit" value="Submit" />
   </form>


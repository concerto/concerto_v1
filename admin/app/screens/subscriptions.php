<img src="<?=ADMIN_URL?>/templates/preview/<?=$this->template['id']?>" style=" border: 1px solid #aaa; display:inline;margin:25px;float:left" />
       <div style=" height:260px; top:0px; float:left; margin-left:30px;">
   <p style="width:280px; bottom:150px; padding:30px; margin-top:50px; background:url(../images/lightblue_bg.gif);
border:1px solid #aaa">Your screen is divided up into several areas, called <b>fields</b>, each of which can display different types of content.
   Use these controls to select feeds (categories of content, i.e. <i>Student Union</i>, which contains student clubs' content) to place in
each field, and how often to display each.</p>
   </div>
<br clear=left />

   <div>
   <form method="POST" action="<?=ADMIN_URL?>/screens/subscribe/<?=$this->screen->id?>">

<?php
$fields_list=$this->screen->list_fields();
if(is_array($fields_list)){
foreach($fields_list as $field) {
?>

<div class="roundcont">
  <div class="roundtop"><img src="<? echo ADMIN_BASE_URL ?>/images/wc_tl.gif" alt="" width="6" height="6" class="corner topleft" style="display: none" /></div>
  <div class="roundcont_main">
    <h1><span class="emph"><? echo $field->name ?></span> (Field)</h1>
    
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

  </div>
  <div class="roundbottom"><img src="<? echo ADMIN_BASE_URL ?>/images/wc_bl.gif" alt="" width="6" height="6" class="corner botleft" style="display: none" /></div>
</div>


<?php
}
}
?>

   <input type="submit" value="Submit" />
   </form>


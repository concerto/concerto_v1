<!-- Beginning Screen Form -->
<?php
   //assuming $this->screen is null or the screen we want to edit
   $screen = $this->screen;
   if($screen->width/$screen->height==(16/9)) 
   {
      $scrimg="screen_169.png";
      $ratio ="16:9";
   }else{
      $scrimg="screen_43.png";
      $ratio ="4:3";
   }
?>

	<h3>General Screen Settings</h3>
   <p>All properties can be modified later.</p>
	<div style="float:left">
     <img src="../images/<?echo $scrimg?>" alt="" />
	</div>
	<div style="clear:none">
     <table style="clear:none" class='edit_win' cellpadding='6' cellspacing='0'>
       <tr>
	<td class='firstrow'><h5>Screen Name</h5></td>
	<td class='edit_col firstrow'>
	<input type="text" id="title" name="title" value="<?php echo $screen[name]?>"></td>
       </tr>
       <tr>
        <td><h5>Screen Description</h5></td>
        <td>
        <input type="text" id="title" name="desc" value="<?php echo $screen[desc]?>"></td>
       </tr>
       <tr>
	<td><h5>Layout Design</h5></td>
	<td><select name="layout">
	 <option value="1">Blue Swoosh</option>
	</select></td>
       </tr>
     </table>
     </div>
	<br clear="all" />
	<h3>Choose Content Sources</h3>

        <div style="width:100%">
	<img src="../images/swoosh_thumb.jpg"  style="float:left; border: 1px solid #aaa; display:inline" 
usemap="lmap" 
/>
<map id="lmap" name="lmap">

<area shape="rect" alt="Left Side Area (2)" title="Left Side Area (2)" coords="5,63,154,238" href="#area2" 
target="_self" 
onMouseOver="javascript:document.getElementById('arearow2').style.backgroundColor='#CDF'"
onMouseOut="javascript:document.getElementById('arearow2').style.backgroundColor=''" />
<area shape="rect" alt="Graphics Area (3)" title="Graphics Area (3)" coords="159,64,392,237" href="#area3" 
target="_self"
onMouseOver="javascript:document.getElementById('arearow3').style.backgroundColor='#CDF'"
onMouseOut="javascript:document.getElementById('arearow3').style.backgroundColor=''" />
<area shape="rect" alt="Ticker Area (1)" title="Ticker Area (1)" coords="231,5,397,39" href="#area1" target="_self" 
onMouseOver="javascript:document.getElementById('arearow1').style.backgroundColor='#CDF'" 
onMouseOut="javascript:document.getElementById('arearow1').style.backgroundColor=''" />

<area shape="default" nohref alt="" />
</map>


       <div style=" height:260px; top:0px; float:left; margin-left:30px;">
	<p style="width:280px; bottom:150px; padding:30px; top:50px; background:url(../images/lightblue_bg.gif); 
border:1px solid #aaa">Your screen is divided up into several areas, each of which can hold a specific type of 
content.
	Use these controls to chose various feeds (think of a feed as a category) of content to place in 
each area.</p>
	</div>	
<br clear=left />
     <table class="edit_win" cellpadding='6' cellspacing='0'>
        <tr id="arearow1">
	  <td class='firstrow'><h5><a name="area1" />Area 1</h5></td>
          <td class='firstrow edit_col'>Draw ticker content from:
<?php
   $query = "SELECT feed.id, feed.name FROM feed WHERE feed.id >= 0";
   $feeds = mysql_query($query);        
   while($feed = mysql_fetch_assoc($feeds))
   {
      echo '<br /><input type="checkbox" name="feed[1][]" value="'.$feed[id].'" checked />';
      echo '<label>'.$feed[name].'</label>';
   }
?>
          </td>
	</tr>
	<tr id="arearow2">
	  <td><h5><a name="area2" />Area 2</h5></td>
          <td>Draw left-side content from:
		<br /><input type="checkbox" name="feed[2][]" value="cal" checked />
		<label>Institute Events Calendar</label>
          </td>
	</tr>
	<tr id="arearow3">
	  <td><h5><a name="area3" />Area 3</h5></td>
          <td>Draw graphics content from:
<?php
   $query = "SELECT feed.id, feed.name FROM feed WHERE feed.id >= 0";
   $feeds = mysql_query($query);        
	while($feed = mysql_fetch_assoc($feeds))
	{
		$checked="";
		$query = "SELECT feed_screen.screen_id FROM feed_screen WHERE feed_screen.feed_id=".$feed[id].' AND 
feed_screen.screen_id = '.$id.' ';
      $res  = mysql_query($query);
		if($item=mysql_fetch_assoc($res))
		{
			$checked="checked";
		}
		echo '<br /><input type="checkbox" name="feed[3][]" value="'.$feed[id].'" '.$checked.'/>';
		echo '<label>'.$feed[name].'</label>'."\n";
	}
?>
          </td>
	</tr>
	<tr>
	  <td>
          <input type="hidden" name="screen_id" value="<?php echo $id?>" />
          <input value="Save Changes" type="submit" name="submit" />
        </td>
	<td />
       </tr>
     </table>
   </form>



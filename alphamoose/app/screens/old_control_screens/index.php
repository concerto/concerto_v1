<? include("../includes/pageheader.php"); ?>
</head>
<body>
  <div id="header">
    <div id="header_padding">
      <? include("../includes/menu_tabs.php"); ?>
    </div>
  </div>
  <div id="content_header">
    <h2><a href="/">Public Interface</a> :: <a href="../index.php">Admin Home</a></h2>
    <h1>Control Screens</h1>
  </div>
  <div id="maincontent">
<?php
	if($_POST['submit'])
	{
		processScreenEdit();
	}
	elseif(isset($_REQUEST['edit_id']))
	{
		showScreenForm($_REQUEST['edit_id']);	
	}
	else
	{
		printScreens();
	}
?>

  </div>
  <div style="clear:both; height:25px;" />
  <!-- BEGIN Sidebar -->
  <? include("../includes/left_menu.php"); ?>
  <!-- END Sidebar -->
</body>
</html>

<?php

function processScreenEdit()
{
        $id=$_POST['screen_id'];
	$query = 'UPDATE screen SET name=\''.$_POST[title].'\', `desc`=\''.$_POST[desc].'\' WHERE id='.$id;
	$res=mysql_query($query);	
$query="SELECT feed_screen.id AS fsid, feed.id AS fid FROM feed LEFT JOIN feed_screen ON screen_id = $id AND feed_id = 
feed.id WHERE 
feed.id >=0";
$res=mysql_query($query);
	while($row=mysql_fetch_assoc($res)) {
		if(array_search($row['fid'], $_POST['feed'][3])!==FALSE && $row['fsid']==NULL)
		{ 
			$query = "INSERT INTO feed_screen (`id`, `feed_id`, `screen_id`, `curcon`, `is_live`) 
VALUES (NULL, '".$row['fid']."', '$id', '0', '1');";
			$r2=mysql_query($query);
		}else if (array_search($row['fid'], $_POST['feed'][3])===FALSE && $row['fsid']!=NULL){
			$query = "DELETE FROM feed_screen WHERE feed_id = ".$row['fid']." AND screen_id=$id";
			$r2=mysql_query($query);
		}
	}	

	echo '</pre><h2>Screen successfully updated!</h2>';
	printScreens();
}

function showScreenForm($id)
{
        $query = "SELECT *
                FROM screen
                WHERE id = '$id'";
        $res = mysql_query($query);
        $screen = mysql_fetch_assoc($res);
?>
   <h1>Editing Screen <?php echo $screen[name]?></h1>
        <?php
                if($screen[width]/$screen[height]==(16/9)){
                        $scrimg="screen_169.png";
                        $ratio ="16:9";
                }else{
                        $scrimg="screen_43.png";
                        $ratio ="4:3";
                }
        ?>

   <form action="#" method="POST">
	<h3>General Screen Settings</h3>
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
<?php
}

function printScreens()
{
?>
    <h2>Click on a screen to change its configuration settings.</h2>
<?php
       $query = "SELECT * FROM screen;";
        $res = mysql_query($query);
        while($row = mysql_fetch_assoc($res))
	{
?>
    <a href="?edit_id=<?echo  $row['id']?>">
    <div class="screenfloat">
      <div class="screenfloat_padding">
	<?php
		if($row[width]/$row[height]==(16/9)){
			$scrimg="screen_169.png";
			$ratio ="16:9";
		}else{
			$scrimg="screen_43.png";
			$ratio ="4:3";
		}
	?>
        <img src="../images/<?echo $scrimg?>" alt="" /><br /><br />
        <h1><? echo $row['name']?></h1>
        <h2><? echo $row['desc']?></h2>
        <h3><?php echo $row[width].' x '.$row[height].' ('.$ratio; ?>)</h3>
	<?php if($row['online']==1) { ?>
        <span style="color:green;font-size:1.3em;font-weight:bold;">Online</span>
	<?php } else { ?>
        <span style="color:red;font-size:1.3em;font-weight:bold;">Offline</span>
	<?php } ?>
      </div>
    </div>
    </a>
<?php
	}
}
?>



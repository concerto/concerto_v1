<?
//Content Rendering API
//Version 0.05
//Notes: You shouldn't be touching this file directly.  You should be calling through the render/index.php handler and passing the version 005

//Default Values
$select = 'feed';
$select_id = '';
$type = 'all';
$format = 'raw';
$width = '';
$height = '';
$props = '';
$count = '1';
//End default values

//Define acceptable values
$select_av = array('content', 'feed', 'user');
$type_av = array('graphic', 'text', 'html', 'all');
$format_av = array('raw','html','rss');
//End Acceptable values

//Grab and check user values
if(in_array($_REQUEST['select'],$select_av)){
	$select = $_REQUEST['select'];
}
if(($select == 'feed' && is_numeric($_REQUEST['select_id'])) ||
 ($select == 'user' && is_string($_REQUEST['select_id'])) ||
 ($select == 'content' && is_numeric($_REQUEST['select_id']))){
	$select_id = escape($_REQUEST['select_id']);
} else {
	return false;
}
if(in_array($_REQUEST['type'], $type_av)){
	$type = $_REQUEST['type'];
	$props .="&type=$type";
}
if(in_array($_REQUEST['format'], $format_av)){
	$format = $_REQUEST['format'];
}
if(is_numeric($_REQUEST['width']) && is_numeric($_REQUEST['height'])){
	$width = $_REQUEST['width'];
	$height = $_REQUEST['height'];
	$props .= "&width=$width&height=$height";
}
if($_REQUEST['count'] == 'all' || is_numeric($_REQUEST['count'])){
	$count = $_REQUEST['count'];
}
//End user value code

//Generate the SQL
$sql_base = 'SELECT content.id, content.name, content.content, content.mime_type, content.submitted, feed_content.feed_id, user.username
FROM `content`
LEFT JOIN feed_content ON content.id = feed_content.content_id
LEFT JOIN user ON content.user_id = user.id
WHERE content.start_time < NOW() AND content.end_time > NOW()
AND feed_content.moderation_flag = 1';  //This base sql enforces some core rules that we should not lift such as moderation and live content only
if($select == 'feed'){
	$sql_base .= " AND feed_content.feed_id = $select_id";
}elseif($select == 'user'){
	$sql_base .= " AND user.username = '$select_id'";
}elseif($select == 'content'){
	$sql_base .= " AND content.id = $select_id";
	$count = 1;
}
if($type == 'graphic'){
	$sql_base .= ' AND content.type_id = 3';
}elseif($type == 'text'){
	$sql_base .= ' AND content.type_id = 2';
}elseif($type == 'html'){
	$sql_base .= ' AND content.type_id = 1';
}elseif($type == 'all'){
  $sql_base .= '';
}
//Don't forget the ordering
$sql_base .= ' ORDER BY id';
//End ordering
if(is_numeric($count)){
	$sql_base .= " LIMIT $count";
}
//End SQL generation

//Run and process the query
$res = sql_query($sql_base);
if($res){
	$i=0;
	while($row = sql_row_keyed($res,$i)){
		$data[$i] = $row;
		$i++;
	}
}
//End processing the query

//Verify we got content
if(!isset($data) || count($data) <= 0){
	return false;
}
//End verification

//Now to start generating some display
if($format == 'raw'){
	$content = $data[0]; //For images we only generate the first one
	if(false===strpos($content['mime_type'],'image')){
			echo $content['content'];
	} else {
		render('image', $content['content'], $width, $height);
	}
}elseif($format == 'html'){
	foreach($data as $i=>$content){
		echo '<div id="concerto_' . $i . '">';
		if($type =='graphic'){
			echo '<img src="index.php?select_id=' . $content['id'] . '&select=content' . $props . '" alt="' . $content['name'] . '" />';
		} else {
			echo $content['content'];
		}
		echo '</div>' . "\n";
	}
}elseif($format == 'rss'){
	header("Content-type: text/xml");
	echo '<?xml version="1.0"?>
<rss version="2.0">
   <channel>
      <title>Concerto API RSS</title>
      <link>http://' . $_SERVER['SERVER_NAME'] .'</link>
      <description>RSS Feed from Concerto API</description>
      <language>en-us</language>
      <pubDate>' . rssdate("now") . '</pubDate>
      <generator>Concerto API 0.05</generator>
      <webMaster>' . SYSTEM_EMAIL . '</webMaster>
';
	foreach($data as $i=>$content){
		$link = 'http://' . $_SERVER['SERVER_NAME'] .  $_SERVER['SCRIPT_NAME'] . '?select_id=' . $content['id'] . '&select=content' . $props;
		$link = htmlspecialchars($link);
		echo '      <item>
         <title>' . htmlspecialchars($content['name']) . '</title>
         <link>' . $link . '</link>
         <pubDate>' . rssdate($content['submitted']) . '</pubDate>
         <author>' . $content['username'] . '</author>
         <guid>' . $content['id'] . '</guid>
      </item>
';
	}
	echo '   </channel>
</rss>';
}

//Function to generate RSS friendly date.  Complies with some RFC
function rssdate($date){
  $newdate = strtotime($date);
  
 //returns the date ready for the rss feed
 $date = date('D, d M Y H:i:s T',$newdate);;
 return $date;
}
?>

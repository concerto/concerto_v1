<?php
  include_once('JSON.php');
  $json = new JSON;

//Array of feed id's to draw content from
$feed_id = $_GET['feedid'];
//Number of total content desired
$count = 80;

$mergedata = array();

$jsondata = file_get_contents('http://concerto.rpi.edu/content/render/?select_id=' . $feed_id . '&format=json&count=' . $count . '&orderby=rand&type=graphics&width=200&height=150');
$feeddata = $json->unserialize($jsondata);
if(is_array($feeddata)){
	$mergedata = array_merge($mergedata, $feeddata);
}

//Shuffle up the merged array
shuffle($mergedata);

//If its too large, shrink it down
if(count($mergedata) > $count){
  $mergedata = array_slice($mergedata,0,$count);
}
//Print it out
$jsonfeeds = $json->serialize($mergedata);
print_r($jsonfeeds);
?>

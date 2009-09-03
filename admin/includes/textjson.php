<?php
  include_once('JSON.php');
  $json = new JSON;

//Array of feed id's to draw content from
$feeds = array(8,11);
//Number of total content desired
$count = 80;

$mergedata = array();
//Loop through and pull out items, then merge them into a big array
foreach($feeds as $f_id){
  $jsondata = file_get_contents('http://signage.union.rpi.edu/content/render/?select_id=' . $f_id . '&format=json&count=' . $count . '&orderby=rand&type=text');
  $feeddata = $json->unserialize($jsondata);
  if(is_array($feeddata)){
    $mergedata = array_merge($mergedata, $feeddata);
  }
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

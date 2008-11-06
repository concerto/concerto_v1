<div>
<?
$dat = $_REQUEST;
$feed = new Feed($dat['feed_id']);
if(!$feed->set){
    echo "Error previewing content on that feed.";
} else {
    $dynamic = $feed->dyn;
    $start=$dat['start_date'].' '.$dat['start_time_hr'].':'.$dat['start_time_min'].' '.$dat['start_time_ampm'];
    $end=$dat['end_date'].' '.$dat['end_time_hr'].':'.$dat['end_time_min'].' '.$dat['end_time_ampm'];
    $preview = $dynamic->preview($_REQUEST['name'], 0, $_REQUEST['content'], $start, $end, date('Y-m-d H:i:s'));
    if(!$preview){
        echo "Preview generation failed.";
    }else{
        echo $preview;
    }
}
?>
</div>

<h2>Click on the feed to start moderating.</h2>
<?php
if(isset($this->feeds)){
    foreach($this->feeds as $feed_id => $feed){
        echo "<h3>{$feed->name}</h3>\n"; 
        if($this->count[$feed->id]==0){
            echo "No items awaiting moderation";
        } else {
            echo "<a href=\"".ADMIN_URL."/moderate/feed/{$feed->id}\">{$this->count[$feed->id]} items awaiting moderation</a>\n";
        }
    }
} else {
    echo "No feeds are awaiting moderation.";
}
?>
<br /><br /><br /><br /><br />
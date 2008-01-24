<h2>Click on a screen for more information.</h2>
<?php
foreach($sess[screens] as $screen){
?>
<a href="show/<? echo $screen->mac_address ?>"><? echo $screen->name;?></a>

<br />
<?php
}
?>

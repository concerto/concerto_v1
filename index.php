<?php
if(isset($_GET['mac'])) {
//header('Location: http://signage.union.rpi.edu/screen?'.$_SERVER["QUERY_STRING"]);
header('Location: http://ds.rpitv.org/tom?'.$_SERVER["QUERY_STRING"]);
} else {
?>
<head>
<title>Concerto</title>
</head>
<body style="background-color:#069; height:100%">
<div style="text-align:center;position:relative; top:25%;">
<img src="admin/images/conc_bluebg.gif">
<p style="color:white; font-family:sans-serif; font-style:italic; font-size:0.85em">coming soon</p>
</div>
</body>
<?}?>

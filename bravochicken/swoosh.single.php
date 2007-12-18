<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Digital Signage</title>
<link href="swoosh.css" media="all" rel="stylesheet" type="text/css" />
<script src="jquery.js" type="text/javascript"></script>
<script src="signage.single.js" type="text/javascript"></script>
<script type="text/javascript"><!--
$(document).ready(function(){init(1)});//--></script>
</head>
<body>
<div id="container">
	<div id="top_frame">
		<div id="datetime">
			<div id="datetime_padding">
				<div id="datetime_left">
					<div id="datetime_left_padding">
						<img src="images/datetime_bullet.png" alt="" />
					</div>
				</div>
				<div id="datetime_right">
					<div id="datetime_right_padding">
						<h1>Tuesday</h1>
						<h2>Late</h2>
					</div>
				</div>
			</div>
		</div>
		<div id="ticker">
			<div id="ticker_frame">
				<div id="ticker_padding">
					<!-- dynamic stuff goes here -->
				</div>
			</div>
		</div>
		<div style="clear:both;" />
	</div>
	<div id="top_gutter">
		<div id="swoosh_bottom">
			<img src="images/sp.gif" width="1" height="1" alt="" />
		</div> 
		<div style="width:60%;">
			<img src="images/sp.gif" width="1" height="1" alt="" />
		</div>
	</div>
	<div id="mainpane">
		<div id="mainpane_padding">
			<div id="tp1_cal">
				<div id="tp1_cal_padding">
					<!-- dynamic stuff goes here -->
				</div>
			</div>
			<div id="tp1_graphic">
				<!-- dynamic stuff goes here -->
			</div>
			<div style="clear:both;" />
		</div>
	</div>
</div>
</body>
</html>

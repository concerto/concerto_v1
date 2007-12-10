<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Digital Signage</title>
<link href="new.css" media="all" rel="stylesheet" type="text/css" />
<script src="jquery.js" type="text/javascript"></script>
<script type="text/javascript"><!--
var mac = 1;

$(document).ready(init);

function init(){
	$.getJSON("fields.php", function(json) {
		var screen = json['screen'];
		$.each(json['fields'], get);
	});
}

function get(i, n){
	var div = $("<div style='position: absolute; z-index: 1'></div>");
	$.getJSON("content.php", {id: i}, function(json) {
		div.append(json['content']);
		$(n+":first div:first").click();
		div.hide()
			.appendTo($(n+":first"))
			.fadeIn('slow')
			.animate({opacity: 1.0}, json['duration'], function() { get(i , n)})
			.click(function(){
				$(this).fadeOut('slow', function() {
					$(this).remove();
				});
			});
	});
}

//--></script>

</head>

<body style='margin: 0; padding: 0; height: 100%; width: 100%;'>
<div id="wrap">

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
             <h1 style="color:#FFF; margin:0; padding:0px;"><?= $date ?></h1>
             <h2 style="font-family:LCDMono, sans-serif; font-size:2.3em; font-weight:bold; letter-spacing:0.1em; color:#FFF; margin:0; padding:0px;"><?= $time ?></h2>
           </div>
         </div>
       </div>
    </div>
    <div id="ticker">
      <div id="ticker_padding" style="padding:1% 25px 1% 240px;">
        <div id="outer">
          <div id="middle"><h1 style="font-size:2.7em; letter-spacing:0.09em; color:#FFF; margin:0; padding:0px;"><!-- dynamic stuff goes here --></h1></div>
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

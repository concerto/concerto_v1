<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Digital Signage</title>
<script src="jquery.js" type="text/javascript"></script>
<script type="text/javascript"><!--
var mac = 1;

$(document).ready(init);

function init(){
	$.getJSON("fields.php", {'mac': mac}, function(json) {
		var screen = json['screen'];
		$.each(json['fields'], get);
	});
}

function get(i, n, prevdiv){
	var div = $("<div style='position: absolute; z-index: 1'></div>");
	$.getJSON('content.php', {'id': i}, function(json) {
		if(prevdiv != undefined) prevdiv.fadeOut('slow', function(){$(this).remove();});
		if(json['mime-type'].match(/text/)) {
			div.append(json['content']);
		} else if(json['mime-type'].match(/image/)) {
			$('<img>').attr('src', json['content']).attr('alt','').appendTo(div);
		} else {
			div.append("Unknown MIME Type");
		}
		div.hide()
			.appendTo($(n + ':first'))
			.fadeIn('slow')
			.animate({opacity: 1.0}, json['duration'], function(){/*get(i , n, div);*/});
	});
}

//--></script>

</head>

<body style='margin: 0; padding: 0; height: 100%; width: 100%;'>
<div id='container' style='height: height: 100%; width: 100%;'>
<div id="body" style="background:#FFF url(images/bg_banner.png) top center repeat-x; color:#000; font-family:Calibri, Arial, sans-serif; font-size:small; margin:0; padding:0; min-width:880px; text-align:left;">
  <div id="top_frame" style="border-top:solid 10px #000033; width:100%; ">
    <div id="datetime" style="background:#000033; float:left; height:117px; width:40%;">
      <div id="datetime_padding" style="background:#000033; padding:0 0 0 10px;">
        <div id="datetime_left" style="background:#000033; float:left; width:25%;"><div id="datetime_left_padding" style="background:#000033; padding:0 12% 0 12%;"><img src="images/datetime_union.png" alt="" /></div></div>
          <div id="datetime_right" style="float:right; font-size:1.7em; width:75%;">
            <div id="datetime_right_padding" style="padding:0 0 0 8%;">
              <h1 style="color:#FFF; margin:0; padding:0px;"><?= $date ?></h1>
              <h2 style="font-family:LCDMono, sans-serif; font-size:2.3em; font-weight:bold; letter-spacing:0.1em; color:#FFF; margin:0; padding:0px;"><?=$time?></h2>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="ticker" style="background:#336699 url(images/swoosh_top.gif) center left no-repeat; float:right; height:117px; width:60%;">
      <div id="ticker_padding" style="padding:1% 25px 1% 240px;">
        <h1 style="font-size:2.7em; letter-spacing:0.09em; color:#FFF; margin:0; padding:0px;"></h1>
      </div>
    </div>
    <div style="clear:both;" />
  </div>
  <div id="top_gutter">
    <div id="swoosh_bottom" style="background:#000033 url(images/swoosh_bottom.gif) center right no-repeat; float:left; height:20px; width:40%;"><img src="images/sp.gif" width="1" height="1" alt="" /></div> 
    <div style="width:60%;"><img src="images/sp.gif" width="1" height="1" alt="" /></div>
  </div>
  <div id="mainpane" style="background:url(images/mp_bg.gif) bottom left no-repeat; clear:both; width:100%;">
    <div id="mainpane_padding" style="padding:35px 25px 35px 35px;">
      <div id="tp1_cal" style="float:left; width:38%;">
        <div id="tp1_cal_padding" style="padding-right:10%;">
        </div>
      </div>
      <div id="tp1_graphic" style="background:gray; border:solid 5px #cccccc; float:right; width:61%;">
      </div>
      <div style="clear:both;" />
    </div>
  </div>
</div>
</div>
</body>
</html>

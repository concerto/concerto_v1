var ticker = [];
var tickercurrent = 0;
var text = [];
var textcurrent = 0;
var graphic = [];
var graphiccurrent = 0;
var width = 255;
var height = 190;


$(document).ready(function(){
	$.getJSON("frontpage/miniscreen", {cache: false}, function(json) {
		$.each(json['ticker'], function(offset) {
			ticker.push(this);
		});
		$.each(json['text'], function(offset) {
			text.push(this);
		});
		$.each(json['graphics'], function(offset) {
			graphic.push(this);
			});
		changetext();
		changegraphic();
		changeticker();
	});

/*
	$.getJSON("frontpage/miniscreen", {cache: false}, function(json) {
		$.each(json['text'], function(offset) {
			text.push(this);
		});
		changetext();
	});


	$.getJSON("frontpage/mini", {cache: false}, function(json) {
		$.each(json, function(offset) {
			graphic.push(this.content);
		});
		changegraphic();
	});

});
*/
function changegraphic() {	
	
	var imgSrc = graphic[graphiccurrent].toString() +"?" + "height=" + height + "&width=" + width;
	$(".fp-exposed").attr('src',"").fadeOut("slow", function(){
		$(".fp-exposed").attr('border',1).attr('src',imgSrc).fadeIn("slow");
		if(++graphiccurrent >= graphic.length){
			graphiccurrent = 0;
		}
		setTimeout(changegraphic, 13000);
	}).attr('border',0);
}

function changetime(){
			$("#scr-timedate").html(currenttime());
}

function zeropad(number){
  if(number < 10){ return "0" + number; }
  return number;
}
function currenttime(){
  var currentDate = new Date();
  var day = zeropad(currentDate.getDate());
  var month = zeropad(currentDate.getMonth());
  var hours = currentDate.getHours();
  if(hours > 12){
    post = "PM";
    hours = hours - 12;
  } else if(hours == 12) {
    post = "PM";
  } else {
    post = "AM";
  }
  hours = zeropad(hours);
  var minutes = zeropad(currentDate.getMinutes());
  var days=["Sun","Mon","Tue","Wed","Thu","Fri","Sat","Sun"]  
  return days[currentDate.getDay()] + " " + month + "/" + day + " " + hours + ":" + minutes + " " +  post;
}


function changetext(){
	if(textcurrent >= text.length){
		textcurrent = 0;
	}
 	tex = text[textcurrent].toString();
	textcurrent++;
	$("#scr-text").html(tex);
	setTimeout(changetext,15000);
}
function changeticker(){
	if(tickercurrent >= ticker.length){
		changetime();
		tickercurrent = 0;
	}
	//tick = ticker;
	tick = ticker[tickercurrent].toString();
	
	tickercurrent++;
	$("#scr-ticker").html(tick);
	setTimeout(changeticker,8000);
}
});

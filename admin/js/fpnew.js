$(document).ready(function(){
	var ticker = [];
	var tickercurrent = 0;
	var text = [];
	var textcurrent = 0;
	var graphic = [];
	var graphiccurrent = 0;
	var width = 255;
	var height = 190;

function wait(){}

function changegraphic() {	
	
	var imgSrc = graphic[graphiccurrent] + "&width=" + width + "&height=" + height;
	$(".fp-exposed").attr('src',"").attr('border',0).fadeOut("slow", function(){
	$(".fp-exposed").attr('src',imgSrc).attr('border',1).fadeIn("slow");
	
		if(++graphiccurrent >= graphic.length)
			{
		   graphiccurrent = 0;
			}
	setTimeout(changegraphic, 13000);
	});
}

function changetime()
{
	$.ajax({
		url: "../includes/time.php",
		cache: false,
		success: function(html){
			$("#scr-timedate").html(html);
		}
	});
}

function changetext()
{
	if(textcurrent >= text.length)
	{
		textcurrent = 0;
	}
 	tex = text[textcurrent];
	textcurrent++;
	$("#scr-text").html(tex);
setTimeout(changetext,15000);

}
function changeticker()
{

	if(tickercurrent >= ticker.length)
	{

		changetime();
		tickercurrent = 0;
	}

	tick = ticker[tickercurrent];
	if(tick.length > 98)
	{
	tick = tick.substring(0,100)
	tick = tick + "...";
	}
	tickercurrent++;
$("#scr-ticker").html(tick);
setTimeout(changeticker,8000);
}


	$.getJSON("../includes/tickerjson.php", {cache: false}, function(json) {
		$.each(json, function(offset) {
			ticker.push(this.content);
		});
		changeticker();
	});


	$.getJSON("../includes/textjson.php", {cache: false}, function(json) {
		$.each(json, function(offset) {
			text.push(this.content);
		});
		changetext();
	});


	$.getJSON("../includes/feedjson.php", {cache: false}, function(json) {
		$.each(json, function(offset) {
			graphic.push(this.content);
		});
		changegraphic();
	});

});

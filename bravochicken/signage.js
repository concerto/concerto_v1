jQuery.fn.extend({
	fadeGlobal: function(duration, prevdiv, callback){
		//if there a previous div, we need to fade it out
		if(prevdiv != undefined)
			prevdiv.fadeOut("slow", function(){$(this).remove();});

		//hides the div, adds it to the DOM, fades in and recursively calls load function
		return $(this).fadeIn("slow").animate({opacity: 1.0}, duration/*, callback*/);
	},
	
	fitBox: function(){
		if($(this).css("position") != "absolute" || $(this).parent().css("display") != "block") return;
		var size = $(this).css("font-size").replace("px","");
		while($(this).height() < $(this).parent().height() && size < 50) {
			$(this).css("font-size", ++size);
		}
		while($(this).height() > $(this).parent().height() && size > 5) {
			$(this).css("font-size", --size);
		}
		return $(this);
	},
});

function init(height, mac){
	$("#container").height(height);
	//AJAX JSON Request to get information about this screen
	$.ajax({type: "GET",
			url: "fields.php",
			data: {"mac": mac},
			success: function(json){
					//for each field, start a new load function
					$.each(json["fields"], function(id, field){
						load(json["screen"], id, field);
					});
				},
			error: function(){
				//try again in 1 second
				setTimeout(init, 1000);
			},
			dataType: "json"
	});
}

function load(screen, id, field, prevdiv){
	$.ajax({type: "GET",
			url: "content.php",
			data: {"screen": screen, "id": id},
			success: function(json){
				//create the absolute position div
				var div = $("<div>").css({"position": "absolute", "overflow": "hidden"}).hide().appendTo($(field + ":first"));
				
				//based on the mime-type of the content, handle it accordingly
				if(json["mime-type"].match(/text/)) {
					div.append(json["content"]).fitBox().fadeGlobal(json["duration"], prevdiv, function(){
						load(screen, id, field, div);
					});
				} else if(json["mime-type"].match(/image/)) {
					var img = new Image();
					img.src = json["content"];
					img.onload = function(){
						var imgTag = $("<img>").attr({"src": json["content"],
													  "alt": ""
						}).appendTo(div);
						div.height(div.parent().height()).width(div.parent().width());
						var ratio = img.width / img.height;
						if(ratio < 1)
							imgTag.css({"position": "relative",
									    "height": div.height(),
									    "left": (div.width() - div.height() * ratio) / 2
							});
						else
							imgTag.css({"position": "relative",
									    "width": div.width(),
									    "top": (div.height() - div.width() / ratio) / 2
							});
						div.fadeGlobal(json["duration"], prevdiv, function(){
							load(screen, id, field, div);
						});
					};
					img.error = function(){
						load(screen, id, field);
					}
				} else {
					div.append("Unknown MIME Type").fadeGlobal(json["duration"], prevdiv, function(){
						load(screen, id, field, div);
					});
				}
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					load(screen, id , field, prevdiv);
				}, 1000);
			},
			dataType: "json"
	});
}


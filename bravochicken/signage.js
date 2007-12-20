jQuery.fn.extend({
	fadeGlobal: function(duration, callback){
		$(this).siblings().each(function(){
			$(this).fadeOut("slow", function(){
				$(this).remove();
			});
		});
		//fades the new div in waits the duration and calls the callback function
		$(this).fadeIn("slow", function(){
			setTimeout(callback, duration);
		});
		return $(this);
	},
	
	fitBox: function(){
		//make sure the this div is absolute and the parent is block and relative
		if($(this).css("position") != "absolute" || $(this).parent().css("position") != "relative" || $(this).parent().css("display") != "block") return;
		//define high and low bound for the font sizing
		var high = 50;
		var low = 5;
		//while the difference is larger than a constant pixelage
		while(high - low > 3){
			//find the middle point of the font size
			var middle = parseInt((high + low) / 2);
			$(this).css("font-size", middle);
			//if the DOM is still too big then set the middle point as the high bound
			if($(this).height() > $(this).parent().height())
				high = middle;
			//otherwise the low bound
			else
				low = middle;
		}
		//center the div
		$(this).css("top", ($(this).parent().height() - $(this).height()) / 2);
		//return current div
		return $(this);
	},
});

function init(mac){
	//ajax json request to get information about this screen
	$.ajax({type: "GET",
			url: "content.php",
			data: {"mac": mac},
			success: function(json){
				load(json["screen"], json["template"], json["attr"], json["fields"]);
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					init(mac);
				}, 1000);
			},
			timeout: 5000,
			dataType: "json"
	});
}

function load(screenId, template, attr, fields){
	if(attr != undefined){
		if(attr["height"] != undefined)
			$("#container").height(attr["height"]);
		if(attr["stylesheet"] != undefined){
			$("head link").remove();
			$("head").append($("<link>").attr({"href": attr["stylesheet"],
												"media": "all",
												"rel": "stylesheet",
												"type": "text/css"}));
		}
	}
	$.ajax({type: "GET",
			url: template,
			success: function(html){
				$("#container").html(html);
				$.each(fields, function(id, field){
					fetch(screenId, id, field);
				});
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					load(screenId, template, attr, fields);
				}, 1000);
			},
			cache: false,
			timeout: 5000,
			dataType: "html"
	});
}

function fetch(screenId, fieldId, field){
	//ajax json request to get each field's content
	$.ajax({type: "GET",
			url: "content.php",
			data: {"screen_id": screenId, "field_id": fieldId},
			success: function(json){
				//create the absolute position div, hides it, and adds it to the DOM
				var div = $("<div>").css({"position": "absolute", "overflow": "hidden"}).hide().appendTo($(field + ":first"));
				//based on the mime-type of the content, handle it accordingly
				if(json["mime_type"].match(/text/)){
					//add the content, fit the content in the Box, and fade it in
					div.append(json["content"]).fitBox().fadeGlobal(json["duration"], function(){
						fetch(screenId, fieldId, field);
					});
				} else if(json["mime_type"].match(/image/)){
					//load the image to cache
					var img = new Image();
					//set onload event handler
					img.onload = function(){
						//create the image tag and add it to the DOM
						var imgTag = $("<img>").attr({"src": json["content"],
													  "alt": ""
						}).appendTo(div);
						//set the created div to parent's dimensions
						div.height(div.parent().height()).width(div.parent().width());
						//get the ratio of width to height
						var ratio = img.width / img.height;
						//if height is larger
						if(ratio < 1)
							//set height to driving dimension
							imgTag.css({"position": "relative",
									    "height": div.height(),
									    "left": (div.width() - div.height() * ratio) / 2
							});
						//if width is larger
						else
							//set width to driving dimension
							imgTag.css({"position": "relative",
									    "width": div.width(),
									    "top": (div.height() - div.width() / ratio) / 2
							});
						//fade in the div
						div.fadeGlobal(json["duration"], function(){
							fetch(screenId, fieldId, field, div);
						});
					};
					//if error then try again with another image
					img.error = function(){
						div.remove();
						fetch(screenId, fieldId, field);
					}
					img.src = json["content"];
				} else {
					//unknown MIME type
					div.append("Unknown MIME Type").fadeGlobal(json["duration"], function(){
						fetch(screenId, fieldId, field);
					});
				}
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					fetch(screenId, fieldId , field);
				}, 1000);
			},
			timeout: 5000,
			dataType: "json"
	});
}


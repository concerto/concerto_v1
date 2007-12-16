jQuery.fn.extend({
	fadeGlobal: function(duration, prevdiv, callback){
		//if there a previous div, we need to fade it out
		if(prevdiv != undefined)
			prevdiv.fadeOut("slow", function(){$(this).remove();});
			//prevdiv.remove();
		//fades the new div in waits the duration and calls the callback function
		$(this).fadeIn("slow", function() {
			//setTimeout(callback, duration);
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
		while(high - low > 3) {
			//find the middle point of the font size
			var middle = parseInt((high + low) / 2)
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
				//set the main container to a specific height
				if(json["height"] != undefined) $("#container").height(json["height"]);
				//for each field, start a new load function
				$.each(json["fields"], function(id, field){
					load(json["screen"], id, field);
				});
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					init(height, mac);
				}, 1000);
			},
			timeout: 5000,
			dataType: "json"
	});
}

function load(screenId, fieldId, field, prevdiv){
	//ajax json request to get each field's content
	$.ajax({type: "GET",
			url: "content.php",
			data: {"screen_id": screenId, "field_id": fieldId},
			success: function(json){
				//create the absolute position div, hides it, and adds it to the DOM
				var div = $("<div>").css({"position": "absolute", "overflow": "hidden"}).hide().appendTo($(field + ":first"));
				//based on the mime-type of the content, handle it accordingly
				if(json["mime_type"].match(/text/)) {
					//add the content, fit the content in the Box, and fade it in
					div.append(json["content"]).fitBox().fadeGlobal(json["duration"], prevdiv, function(){
						load(screenId, fieldId, field, div);
					});
				} else if(json["mime_type"].match(/image/)) {
					//load the image to cache
					var img = new Image();
					img.src = json["content"];
					//wait for it to load
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
						div.fadeGlobal(json["duration"], prevdiv, function(){
							load(screenId, fieldId, field, div);
						});
					};
					//if error then try again with another image
					img.error = function(){
						load(screenId, fieldId, field);
					}
				} else {
					//unknown MIME type
					div.append("Unknown MIME Type").fadeGlobal(json["duration"], prevdiv, function(){
						load(screenId, fieldId, field, div);
					});
				}
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					load(screenId, fieldId , field, prevdiv);
				}, 1000);
			},
			timeout: 5000,
			dataType: "json"
	});
}


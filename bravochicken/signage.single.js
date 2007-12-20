jQuery.fn.extend({
	fadeGlobal: function(){
		$(this).siblings().each(function(){
			$(this).fadeOut("slow", function(){
				$(this).remove();
			});
		});
		return $(this).fadeIn("slow");
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
			url: "content.single.php",
			data: {"mac": mac},
			success: function(json){
				load(json["screen"], json["template"], json["attr"]);
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

function load(screenId, template, attr){
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
				fetch(screenId);
			},
			error: function(){
				//try again in 1 second
				setTimeout(function(){
					load(screenId, template, attr);
				}, 1000);
			},
			cache: false,
			timeout: 5000,
			dataType: "html"
	});
}

function fetch(screenId){
	//ajax json request to get each field's content
	$.ajax({type: "GET",
			url: "content.single.php",
			data: {"screen_id": screenId},
			success: function(json){
				if(json != null){
					$.each(json, function(field, data){
						//create the absolute position div, hides it, and adds it to the DOM
						var div = $("<div>").css({"position": "absolute", "overflow": "hidden"}).hide().appendTo($(field + ":last"));
						//based on the mime-type of the content, handle it accordingly
						if(data["mime_type"].match(/text/)){
							//add the content, fit the content in the Box, and fade it in
							div.append(data["content"]).fitBox().fadeGlobal();
						} else if(data["mime_type"].match(/image/)){
							//load the image to cache
							var img = new Image();
							img.src = data["content"];
							//set onload event handler
							img.onload = function(){
								//create the image tag and add it to the DOM
								var imgTag = $("<img>").attr({"src": data["content"],
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
								div.fadeGlobal();
							};
							//if error then try again with another image
							img.error = function(){
								div.remove();
							}
						} else {
							//unknown MIME type
							div.append("Unknown MIME Type").fadeGlobal();
						}
					});
				}
			},
			timeout: 5000,
			dataType: "json"
	});
	setTimeout(function(){
		fetch(screenId);
	}, 1000);
}


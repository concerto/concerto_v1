jQuery.fn.extend({
	//function that takes the current jQuery object and fades it in deleting the previous div
	fadeGlobal: function(prevdiv){
	    //if the previous div exists then fade it out
	    if(prevdiv != undefined){
            prevdiv.animate({opacity: 0.0}, "slow", "swing", function(){
	            $(this).remove();
            });
	    }
		
		//fades the new div in waits the duration and calls the callback function
		$(this).animate({opacity: 1.0}, "slow", "swing");
		return $(this);
	},
	
	//function to fit text perfectly in the specified box
	fitBox: function(height, top){
		//define high and low bound for the font sizing
		var high = 128;
		var low = 8;
		//while the difference is larger than a constant pixelage
		while(high - low > 2){
			//find the middle point of the font size
			var middle = parseInt((high + low) / 2);
			$(this).css("font-size", middle);
			//if the DOM is still too big then set the middle point as the high bound
			if($(this).height() > height)
				high = middle;
			//otherwise the low bound
			else
				low = middle;
		}
		$(this).css("font-size", parseInt((high + low) / 2));
		//center the div
		$(this).css("top", top + (height - $(this).height()) / 2);
		//return current div
		return $(this);
	}
});

//the screen id of the current screen such that we don't have to query it every single time
//WARNING: SET THIS VARIABLE OR THE SCRIPT WILL START SHITTING BRICKS!!!...or not do anything.
var screenId;
//the current template ID
var templateId;
//the fields in the template, they will be defined later on...I hope
var fields = [];
//the current field that updates the script
var currentField;

//This is where it starts.  The function makes sure that a template exists and starts loading content
function start(){
    if(screenId == undefined || templateId == undefined || !fields.length){
        //No template is currently loaded
        initScreen();
    } else {
        //change a different field
        if(++currentField >= fields.length)
            currentField = 0;
        //...every 1.5 seconds
        setTimeout(fetchContent, 1500);
    }
}

function initScreen(){
    //ajax json request to get information about this screen
    $.ajax({type: "GET",
		    url: "content.php",
		    data: {"id": screenId},
		    success: function(json){
		        if(json != null){
			        var imgSrc = "template.php?id=" + screenId;
			        //load the image to cache
			        var img = new Image();
			        //set onload event handler
			        img.onload = function(){
                        $(document.body).empty();
			            fields.length = 0;
			            $("<img>")
			                .attr({"src": imgSrc,
					               "alt": ""
			                })
			                .css({"position": "absolute", 
				                  "left": "0",
				                  "top": "0"
			                })
			                .appendTo($(document.body));
			            var width = img.width;
			            var height = img.height;
			            $.each(json["fields"], function(fieldId, field){
			                field["id"] = fieldId;
			                field["timeout"] = 0;
			                field["prevdiv"] = undefined;
			                field["left"] *= width;
			                field["top"] *= height;
			                field["width"] *= width;
			                field["height"] *= height;
			                fields.push(field);
			            });
			            templateId = json["screen"]["template_id"];
			            currentField = 0;
			            start();
			        };
			        img.src = imgSrc;
			    } else {
			        start();
			    }
		    },
		    error: start,
		    timeout: 5000,
		    dataType: "json"
    });
}

function fetchContent(){
    var field = fields[currentField];
    var time = (new Date()).getTime();
    
    if(field && field["timeout"] < time)
	    //ajax json request to get each field's content
	    $.ajax({type: "GET",
			    url: "content.php",
			    data: {"screen_id": screenId, "field_id": field["id"]},
			    success: function(json){
				    if(!json || !json["mime_type"] || !json["duration"]){
				        field["timeout"] = time + 3000;
				    //based on the mime-type of the content, handle it accordingly
				    } else if(json["mime_type"].match(/text/)){
					    //add the content, fit the content in the Box, and fade it in
					    var div = $("<div>")
					        .attr("style", field["style"])
					        .css({"position": "absolute",
					              "overflow": "hidden",
					              "opacity": "0.0",
					              "left": field["left"],
					              "top": field["top"],
					              "width": field["width"]
					        })
					        .appendTo(document.body)
					        .append(json["content"])
					        .fitBox(field["height"], field["top"])
					        .fadeGlobal(field["prevdiv"]);
					    field["prevdiv"] = div;
					    field["timeout"] = time + parseInt(json["duration"]);
				        if(json["template_id"] != templateId) templateId = undefined;
				    } else if(json["mime_type"].match(/image/)){
					    var imgSrc = "image.php?file=" + escape(json["content"]) + "&width=" + field["width"] + "&height=" + field["height"];
					    //load the image to cache
					    var img = new Image();
					    //set onload event handler
					    img.onload = function(){
						    //create the image tag and add it to the DOM
						    var div = $("<img>")
						        .attr({"style": field["style"], "src": imgSrc, "alt": ""})
						        .css({"position": "absolute", 
        					          "opacity": "0.0",
								      "left": field["left"] + (field["width"] - img.width) / 2,
								      "top": field["top"] + (field["height"] - img.height) / 2
						        })
						        .appendTo(document.body)
						        .fadeGlobal(field["prevdiv"]);
					        field["prevdiv"] = div;
					        field["timeout"] = time + parseInt(json["duration"]);
				            if(json["template_id"] != templateId) templateId = undefined;
					    };
					    //if error then try again with another image
					    img.error = start;
					    img.src = imgSrc;
				    } else {
					    //unknown MIME type
					    var div = $("<div>")
					        .attr({"style": field["style"], "src": imgSrc, "alt": ""})
					        .css({"position": "absolute",
					              "overflow": "hidden",
					              "opacity": "0.0",
					              "left": field["left"],
					              "top": field["top"],
					              "width": field["width"]
					        })
					        .appendTo(document.body)
					        .append("Unknown MIME Type")
					        .fadeGlobal(field["prevdiv"]);
				        field["prevdiv"] = div;
				        field["timeout"] = time + 3000;
				        if(json["template_id"] != templateId) templateId = undefined;
				    }
				    start();
			    },
			    error: start,
			    timeout: 3000,
			    dataType: "json"
	    });
    else
        start();
}

$(document).ready(start);


(function($) {
    $.extend({
        signage: function(screenId){
            //the current template checksum, if this changes then the template and fields have changed
            var checksum;
            //the fields in the template, they will be defined later on...I hope
            var fields = [];
            //the current field that updates the script
            var currentField;
            
            //This is where it starts. The function makes sure that a template exists and starts loading content
            function start(){
                if(screenId == undefined || checksum == undefined || !fields.length){
                    //No template is currently loaded
                    checkScreen(true);
                } else {
                    //if we still in range of the fields then update each one
                    if(currentField < fields.length){
                        //...every 1.5 seconds
                        setTimeout(fetchContent, 1000);
                    } else {
                        //if not then check if we need a template update and go back to field 0
                        currentField = 0;
                        checkScreen();
                    }
                }
            }

            function checkScreen(force){
                //ajax json request to get information about this screen
                $.ajax({type: "GET",
                        url: "content.php",
                        data: {"id": screenId},
                        success: function(json){
                            //if the json does not exist or the checksum does not check up then reload the template
                            if(json != null && (force || json["checksum"] != checksum)){
                                var imgSrc = "template.php?id=" + screenId + "&time=" + (new Date()).getTime();
                                //load the image to cache
                                var img = new Image();
                                //set onload event handler
                                img.onload = function(){
                                    //empty the body...NONE WILL SURVIVE!!! HAHAHAHAHA
                                    $(document.body).empty();
                                    //emtpy the fields...LOL
                                    fields.length = 0;
                                    //create the background image
                                    $("<img>")
                                        .attr({"src": imgSrc,
                                               "alt": ""
                                        })
                                        .css({"position": "absolute", 
                                              "left": "0",
                                              "top": "0"
                                        })
                                        .appendTo($(document.body));
                                    //set properties of each field
                                    $.each(json["fields"], function(fieldId, field){
                                        field["id"] = fieldId;
                                        field["timeout"] = 0;
                                        field["prevdiv"] = undefined;
                                        fields.push(field);
                                    });
                                    checksum = json["checksum"];
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
                //update next field
                var field = fields[currentField++];
                var time = (new Date()).getTime();

                if(field && field["timeout"] < time)
                    //ajax json request to get each field's content
                    $.ajax({type: "GET",
                            url: "content.php",
                            data: {"screen_id": screenId, "field_id": field["id"]},
                            success: function(json){
                                //if not mime_type is set or duration then just wait
                                if(json && json["mime_type"] && json["duration"]){
                                    //based on the mime-type of the content, handle it accordingly
                                    if(json["mime_type"].match(/text/)){
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
                                            .fadeGlobal(field["prevdiv"], function(){
            					                field["prevdiv"] = div;
            					                start();
                                            });
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
                                                .fadeGlobal(field["prevdiv"], function(){
                                                    field["prevdiv"] = div;
                                                    start();
                                                });
                                        };
                                        //if error then try again with another image
                                        img.error = start;
                                        img.src = imgSrc;
                                    } else {
                                        start();
                                    }
                                    field["timeout"] = time + parseInt(json["duration"]);
                                } else {
                                    field["timeout"] = time + 3000;
                                    start();
                                }
                            },
                            error: start,
                            timeout: 3000,
                            dataType: "json"
                    });
                else
                    start();
            }
            
            start();
        }
	});
	
	
	$.fn.extend({
        //function that takes the current jQuery object and fades it in deleting the previous div
        fadeGlobal: function(prevdiv, callback){
            //if the previous div exists then fade it out
            if(prevdiv != undefined){
                prevdiv.animate({opacity: 0.0}, "slow", "swing", function(){
                    $(this).remove();
                });
            }
	
	        //fades the new div in waits the duration and calls the callback function
	        $(this).animate({opacity: 1.0}, "slow", "swing", callback);
	        return $(this);
        },
        
        //function to fit text perfectly in the specified box
        fitBox: function(height, top){
            //define high and low bound for the font sizing
            var high = 64;
            var low = 0;
            //while the difference is larger than a constant pixelage
            while(high - low > 1){
                //find the middle point of the font size
                var middle = (high + low) / 2;
                $(this).css("font-size", middle);
                //if the DOM is still too big then set the middle point as the high bound
                if($(this).height() > height)
                    high = middle;
                //otherwise the low bound
                else
                    low = middle;
            }

            //FUCKING UGLY...TAKE THIS SHIT OUT FOR GODS SAKE!!!
            var font = parseInt((high + low) / 2);
            while($(this).height() > height){
                $(this).css("font-size", --font);
            }
            //center the div
            $(this).css("top", top + (height - $(this).height()) / 2);
            //return current div
            return $(this);
        }
	});
})(jQuery);

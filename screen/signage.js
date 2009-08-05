/**
 * This file was developed as part of the Concerto digital signage project
 * at RPI.
 *
 * Copyright (C) 2009 Rensselaer Polytechnic Institute
 * (Student Senate Web Technolgies Group)
 *
 * This program is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option)
 * any later version.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.  You should have received a copy
 * of the GNU General Public License along with this program.
 *
 * @package      Concerto
 * @author       Web Technologies Group, $Author$
 * @copyright    Rensselaer Polytechnic Institute
 * @license      GPLv2, see www.gnu.org/licenses/gpl-2.0.html
 * @version      $Revision$
 */


(function($) {
    $.extend({
        signage: function(screenId){
            //the current template checksum, if this changes then the template and fields have changed
            var checksum;
            //the fields in the template, they will be defined later on...I hope
            var fields = [];
            var fieldToControl = 0;
            //the current field that updates the script
            var currentField;

            var paused = 0;
            var pauseTimeout;
            
            var maxContentToKeep = 10;

            function keyup_handler(e) {
                console.log("keystroke: %d", e.which);

                clearTimeout(pauseTimeout);
                pauseTimeout = setTimeout(run, 30000);

                if (e.which == 65 || e.which == 97) {
                    if (!paused) {
                        pause( );
                    }
                    console.log("Going to previous content");
                    previous_content( );
                } else if (e.which == 68 || e.which == 100) {
                    if (!paused) {
                        console.log("Pausing");
                        pause( );
                    } else {
                        console.log("Resuming");
                        run( );
                    }
                } else if (e.which == 71 || e.which == 103) {
                    if (!paused) {
                        pause( );
                    }
                    console.log("Going to next content");
                    next_content( );
                }
                    
            }

            // One-time initialization goes in here...
            function initialize( ) {
                // start everything up
                start( );
                // hook some key presses
                $(document).keyup(keyup_handler);
                // make sure we clean up old stale divs
                garbage_collect( );
            }
            
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
                $.ajax({type: "POST",
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
                                    var i = 0;
                                    $.each(json["fields"], function(fieldId, field){
                                        field["id"] = fieldId;
                                        field["timeout"] = 0;
                                        field["prevdiv"] = undefined;
                                        field["prevdivs"] = [];
                                        
                                        console.log("field %d:", i);
                                        //console.dir(field);

                                        if (field["width"] > 700) {
                                            console.log("Using field %d\n", i);
                                            fieldToControl = i;
                                        }

                                        fields.push(field);
                                        i++;
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

            function pause() {
                var field = fields[fieldToControl];

                // pause the screen
                paused = 1;
                field["current_index"] = field["prevdivs"].length - 1;
            }

            function run() {
                if (paused) {
                    paused = 0;
                    clearTimeout(pauseTimeout);
                }
            }

            function previous_content() {
                var field = fields[fieldToControl];
                var divs = field["prevdivs"];
                var pos = field["current_index"];


                //console.dir(field);
                //console.dir(divs);

                if (pos == 0) {
                    return; // can't go back further
                }

                var old_div = divs[pos];
                var new_div = divs[pos-1];

                //console.log("old_div:");
                //console.dirxml(old_div);
                //console.log("new_div:");
                //console.dirxml(new_div);

                new_div.fadeGlobal(old_div, function() {
                        field["prevdiv"] = new_div;
                    }
                );

                field["current_index"] = pos - 1;

            }
            

            function next_content() {
                var field = fields[fieldToControl];
                var divs = field["prevdivs"];
                var pos = field["current_index"];

                if (pos == divs.length - 1) {
                    return; // can't go forward further
                }

                var old_div = divs[pos];
                var new_div = divs[pos+1];

                new_div.fadeGlobal(old_div, function() {
                        field["prevdiv"] = new_div;
                    }
                );

                field["current_index"] = pos + 1;
            }

            function garbage_collect() {
                if (!paused) {
                    console.log("Let the purging commence!");
                    $.each(fields, function(fieldId, field) { 
                            while (field["prevdivs"].length > maxContentToKeep) {
                                var div = field["prevdivs"].shift();
                                console.log("Purging an old div from field %d", fieldId);
                                div.remove();
                            }
                        }
                    );
                }

                // repeat the garbage collection every 30 sec
                setTimeout(garbage_collect, 30000);
            }

            function fetchContent(){
                console.log("Fetching content...");
                // check if we're paused
                if (paused && currentField == fieldToControl) {
                    console.log("but we're paused!");
                    currentField++;
                    start( );
                    return;
                }
                //update next field
                var field = fields[currentField++];
                var time = (new Date()).getTime();

                if(field && field["timeout"] < time)
                    //ajax json request to get each field's content
                    $.ajax({type: "POST",
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
                                                                field["prevdivs"].push(div)
            					                start();
                                            });
                                    } else if(json["mime_type"].match(/image/)){
                                        var imgSrc = "image.php?file=" + escape(json["content"]) + "&width=" + field["width"] + "&height=" + field["height"];
                                        //load the image to cache
                                        var img = new Image();
                                        //set onload event handler
                                        img.onload = function(){
                                                console.log("Something looks like an image in field %d", currentField - 1);
                                                fieldToControl = currentField - 1;
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
                                                    field["prevdivs"].push(div)
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
            
            initialize();
        }
	});
	
	
	$.fn.extend({
        //function that takes the current jQuery object and fades it in deleting the previous div
        fadeGlobal: function(prevdiv, callback){
            //if the previous div exists then fade it out
            if(prevdiv != undefined){
                prevdiv.animate({opacity: 0.0}, "normal", "swing", function(){
                    //$(this).remove();
                });
            }
	
	        //fades the new div in waits the duration and calls the callback function
	        $(this).animate({opacity: 1.0}, "normal", "swing", callback);
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

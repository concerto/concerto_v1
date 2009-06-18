$(document).ready(function(){
    var content = [];
    var current = 0;
    var paused = 0;
    var width = 652;
    var height = 324;
    var thumbWidth = 200;
    var thumbHeight = 100;
    
    $.getJSON("../includes/feedjson.php", {cache: false}, function(json) {
        $.each(json, function(offset) {
            content.push(this.content);
            var imgSrc = this.content + "&width=" + thumbWidth + "&height=" + thumbHeight;
            var img = new Image();
            img.onload = function(){
                var a = $("<a>").attr("href","#").click(function(e){
                        e.preventDefault();
                        current = offset;
                        toggle(true);
                        rotate();
                        }).append($("<img>").attr("src", imgSrc).css("margin-top", (thumbHeight - img.height) / 2));
                $("<div>").addClass("fp-thumbs-div").append(a).hide().appendTo("#fpth-left").fadeIn();
            };
            img.src = imgSrc;
        });
        setTimeout(slideshow, 8000);
    });
    
});

// make sure the $ is pointing to JQuery and not some other library
    (function($){
        // add a new method to JQuery

        $.fn.equalHeight = function() {
           // find the tallest height in the collection
           // that was passed in (.column)
            tallest = 0;
            this.each(function(){
                thisHeight = $(this).height();
                if( thisHeight > tallest)
                    tallest = thisHeight;
            });

            // set each items height to use the tallest value found
            this.each(function(){
                $(this).height(tallest);
            });
        }

        
        
    })(jQuery);

//Fullheight sidebar
//Initial load of page
$(document).ready(sizeContent);

//Every resize of window
$(window).resize(sizeContent);

//Dynamically assign height
function sizeContent() {
    var newHeight = $("html").height() - $("#header").height() - $("#secondary_bar").height() + "px";
    $("#sidebar, #main").css("min-height", newHeight);
}
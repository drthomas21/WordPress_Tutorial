(function($){
    if($(window).height() > $("body").height()) {
        $("footer").style({
            "position":"absolute",
            "bottom":0
        });
    }
});

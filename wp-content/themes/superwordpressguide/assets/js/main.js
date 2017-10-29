(function($){
    if($(window).height() > $("body").height()) {
        $("footer").css({
            "position":"absolute",
            "bottom":0
        });
    }
})(jQuery);

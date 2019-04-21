(function($){
    function swapElements() {
        var content1 = $(".main-content .main-content-1");
        var content2 = $(".main-content .main-content-2");

        if($(window).width() <= 1024) {
            content1.insertAfter(content2);
        } else {
            content2.insertAfter(content1);
        }
    }

    $(window).resize(function() {
        swapElements();
    });

    $(document).ready(function() {
        swapElements();
    });
})(jQuery);

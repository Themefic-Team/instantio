(function($) {
    'use strict';
	
	jQuery(document).ready(function(){

        $( ".ins-scroll-to" ).parent().css( {"padding": "0", "margin": "0", "visibility": "hidden"} );

        $(".admin-scroll").click(function(e) {

            e.preventDefault();

            var secID = $(this).attr("data-id");
            console.log(secID);

            $('html, body').animate({
                scrollTop: $('#'+secID).offset().top
            }, 1000);
        });

    });

})(jQuery);
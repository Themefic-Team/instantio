(function($) {
    'use strict';

    jQuery(document).ready(function() {
		
		/*
		 * Nav Toggler
		 */
        $(document).on('click', '#ins-toggle-button, .added_to_cart', function(e) {
            e.preventDefault();

            var targetClass = $('.ins-toggle-button, .added_to_cart');

            if (targetClass.hasClass('open')) {
                targetClass.removeClass('open');
                $('.ins-container').removeClass('panel-open');
				$('html').removeClass('ins-panel-open');
            } else {
                targetClass.addClass('open');
                $('.ins-container').addClass('panel-open');
				$('html').addClass('ins-panel-open');
            }

        });

        //Collapse Nav if click on body
        $('html').on('click', function (e) {
            if (!$('#ins-toggle-button, .added_to_cart, .add_to_cart_button, .single_add_to_cart_button').is(e.target) && $('#ins-toggle-button, .added_to_cart, .add_to_cart_button, .single_add_to_cart_button').has(e.target).length === 0 && !$('.ins-inner').is(e.target) && $('.ins-inner').has(e.target).length === 0) {
                $('#ins-toggle-button, .added_to_cart').removeClass('open');
                $('.ins-container').removeClass('panel-open');
				$('html').removeClass('ins-panel-open');
            }
        });
		
		//Collapse Nav if click on cross
        $(document).on('click', '#ins-close', function(e) {
			var targetClass = $('#ins-toggle-button, .added_to_cart');
            targetClass.removeClass('open');
                $('.ins-container').removeClass('panel-open');
				$('html').removeClass('ins-panel-open');
        }); 		

        /*
        *  Auto open panel when add to cart item
        */
        if (autotogpanel == 'true') {
            $(document.body).on('added_to_cart', function() {
                setTimeout(function(){
                    if ( wiCartTotal > 0 ) {
                        $('.ins-toggle-button').addClass( 'open' );
                        $('.ins-container').addClass( 'panel-open' );
                        $('html').addClass('ins-panel-open');
                    } else {
                        $('.ins-toggle-button').removeClass( 'hascart' );
                        $('.ins-container').removeClass( 'hascart' );
                    }
                    // Update Cart on added to card
                jQuery('[name="update_cart"]').trigger( 'click' );
                },300);
            });
        }

    });

})(jQuery);
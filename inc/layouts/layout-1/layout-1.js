// Toggle Panel
(function($) {
    'use strict';

    jQuery(document).ready(function() {

        //Nav Toggler
        $(document).on(cartButton, '#ins-toggle-button, .added_to_cart', function(e) {
            e.preventDefault();

            var targetClass = $('#ins-toggle-button, .added_to_cart');

            if (targetClass.hasClass('open')) {
                targetClass.removeClass('open');
                $('.ins-lay1-container').removeClass('panel-open');
                $('html').removeClass('ins-panel-open');
            } else {
                targetClass.addClass('open');
                $('.ins-lay1-container').addClass('panel-open');
                $('html').addClass('ins-panel-open');
            }

        });

        //Collapse Nav if click on body
        $('html').on(cartButton, function (e) {
            if (!$('#ins-toggle-button, .added_to_cart, .add_to_cart_button').is(e.target) && $('#ins-toggle-button, .added_to_cart, .add_to_cart_button').has(e.target).length === 0 && !$('.ins-inner').is(e.target) && $('.ins-inner').has(e.target).length === 0) {
                $('#ins-toggle-button, .added_to_cart').removeClass('open');
                $('.ins-lay1-container').removeClass('panel-open');
                $('html').removeClass('ins-panel-open');
            }
        });

    });

})(jQuery);
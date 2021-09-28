(function($) {
    'use strict';

    jQuery(document).ready(function() {
						
		/*
		 *  Update cart total JS
		 */
        $(document.body).on('updated_cart_totals', function() {
            jQuery('.woocommerce-cart-form:not(:last)').remove();
            jQuery('.cart_totals:not(:last)').remove();
        });
		
		// Prevent view cart / checkout button link to work
		// Instead it will open popup
		$(document).on('click', '.added_to_cart', function(e) {
            e.preventDefault();
			$.fancybox.open( $('#ins3-popup'));
        });

    });

})(jQuery);
	

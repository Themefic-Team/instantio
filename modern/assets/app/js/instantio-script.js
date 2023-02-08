;(function ($) {
    'use strict';
    $ ( document ).ready(function() { 
        $(".ins-checkout-close").on('click', function(e) {
            e.preventDefault();
            $(".ins-checkout-layout-3.slide").removeClass('active');
            $(".ins-checkout-overlay").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('fadeIn');   
        });
        $(".ins-click-to-show").on('click', function(e) {
            e.preventDefault(); 
            $(".ins-checkout-layout-3.slide").toggleClass('active');
            $(".ins-checkout-overlay").toggleClass('active');
            $(".ins-checkout-popup").toggleClass('active');
            $(".ins-checkout-popup").toggleClass('fadeIn'); 
        });
        $(".ins-checkout-overlay").on('click', function(e) {
            e.preventDefault(); 
            $(".ins-checkout-layout-3.slide").removeClass('active');
            $(".ins-checkout-overlay").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('fadeIn');  
        });

        // Instantio Multistep Checkout
        $(".ins-step-btn").on('click', function(e) {
            e.preventDefault();
            $(".ins-step-btn").removeClass('active');
            $(this).addClass('active');
            var $this = $(this);
            var step = $this.data('step');
            $(".ins-single-step").removeClass('active');
            $('.'+step).addClass('active'); 
        });

    });
})(jQuery);

;(function ($) { 
    'use strict';
    $ ( document ).ready(function() { 
        $(document).on("click", ".ins-checkout-close", function(e){  
            e.preventDefault();
            $(".ins-checkout-layout-3.slide").removeClass('active');
            $(".ins-checkout-overlay").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('fadeIn');   
        });
        $(document).on("click", ".ins-click-to-show", function(e){  
            e.preventDefault(); 
            $(".ins-checkout-layout-3.slide").toggleClass('active');
            $(".ins-checkout-overlay").toggleClass('active');
            $(".ins-checkout-popup").toggleClass('active');
            $(".ins-checkout-popup").toggleClass('fadeIn'); 
        });
        $(document).on("click", ".ins-checkout-overlay", function(e){  
            e.preventDefault(); 
            $(".ins-checkout-layout-3.slide").removeClass('active');
            $(".ins-checkout-overlay").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('active'); 
            $(".ins-checkout-popup").removeClass('fadeIn');  
        });

        // Instantio Multistep Checkout
        $(document).on("click", ".ins-step-btn", function(e){  
            e.preventDefault();
            $(".ins-step-btn").removeClass('active');
            $(this).addClass('active');
            var $this = $(this);
            var step = $this.data('step');
            $(".ins-single-step").removeClass('active');
            $('.'+step).addClass('active'); 
        });

    });
    $( document.body ).on( 'added_to_cart', function(){ 
         $.ajax({
            url: ins_params.ajax_url, 
            type: 'POST',
            data: {
                id: '1',
                action: 'ins_ajax_cart_reload',
            },
            success: function( response ) { 
                console.log(response);
                $('.ins-checkout-layout').html('');
                $('.ins-checkout-layout').append(response);
                $('.ins-checkout-layout-3').addClass('active');
                $('.ins-checkout-overlay').addClass('active');
                $(".ins-checkout-popup").toggleClass('active');
                $(".ins-checkout-popup").toggleClass('fadeIn'); 

            }

        });
    });

    // Ins Qty plus Minus Script
    $(document).on("click", ".ins-cart-minus", function(e){ 
        e.preventDefault();
        let minus = $(this).closest('.ins-cart-qty-wrap').find('.quantity input[type="number"].qty');
       
        let qty = minus.val(); 
        if(qty  >= 1){
            minus.val(qty-1)
        }  
        $( '.ins-checkout-layout button[name="update_cart"]' ).trigger( "click" );
    });
    
    $(document).on("click", ".ins-cart-plus", function(e){ 
        e.preventDefault();
        let plus = $(this).closest('.ins-cart-qty-wrap').find('.quantity input[type="number"].qty');
        let qty = plus.val(); 
        plus.val(parseInt(qty)+1) 
        $( '.ins-checkout-layout button[name="update_cart"]' ).trigger( "click" );
    });

    // Ins Cart Item Remove
    $(document).on("click", ".ins-cart-item-remove", function(e){
        e.preventDefault();  
            let id = $(this).find('a.remove').data('product_id'); 
            $.ajax({
                url: ins_params.ajax_url, 
                type: 'POST',
                data: {
                    id: id,
                    action: 'ins_ajax_cart_item_remove',
                },
                beforeSend: function (response) {
                    $('.loader-container').addClass("active");
                },
                complete: function (response) { 
                    $('.loader-container').removeClass("active");
                },
                success: function( response ) {  
                    $('.ins-checkout-layout').html('');
                    $('.ins-checkout-layout').html(response.cart_data);
                }

            });
    });

    // empty cart
    $(document).on("click", ".ins-empty-cart", function(e){
        e.preventDefault();  

        $.ajax({
            url: ins_params.ajax_url, 
            type: 'POST',
            data: {
                action: 'ins_ajax_empty_cart',
            },
            beforeSend: function (response) {
                $('.loader-container').addClass("active");
            },
            complete: function (response) { 
                $('.loader-container').removeClass("active");
            },
            success: function( response ) {  
                $('.ins-checkout-layout').html('');
                $('.ins-checkout-layout').html(response.cart_data);
            }

        });
    });


    // Update Cart
    // empty cart
    $(document).on("click", '.ins-checkout-layout button[name="update_cart"], .ins-checkout-layout button[name="apply_coupon"]', function(e){
        e.preventDefault();   
        var $this = $(this),
            $form = $this.closest('form'),
            cart_item_keys = [],
            product_ids = [],
            quantities = [];
            coupon_code = $form.find('input[name="coupon_code"]').val();
        
        $form.find('.cart_item').each(function() {
            var $cart_item = $(this),
                cart_item_key = $cart_item.data('cart-item-key'),
                product_id = $cart_item.data('product-id'),
                quantity = $cart_item.find('.quantity input.qty').val();

            cart_item_keys.push(cart_item_key);
            product_ids.push(product_id);
            quantities.push(quantity);
        });  
        $.ajax({
            url: ins_params.ajax_url,
            type: 'post',
            data: {
                action: 'ins_ajax_update_cart',
                cart_item_keys: cart_item_keys,
                product_ids: product_ids,
                quantities: quantities,
                coupon_code: coupon_code
            },
            beforeSend: function (response) {
                $('.loader-container').addClass("active");
            },
            complete: function (response) { 
                $('.loader-container').removeClass("active");
            },
            success: function ( response ) { 
                $('.ins-checkout-layout').html('');
                $('.ins-checkout-layout').html(response.cart_data);
            }
        });
        return false;
    });

    // Remove Coupon
    $(document).on("click", ".ins-checkout-layout .woocommerce-remove-coupon", function(e){
        e.preventDefault();  
            let coupon = $(this).data('coupon'); 
            $.ajax({
                url: ins_params.ajax_url, 
                type: 'post',
                data: {
                    coupon: coupon,
                    action: 'ins_ajax_remove_coupon',
                },
                beforeSend: function (response) {
                    $('.loader-container').addClass("active");
                },
                complete: function (response) { 
                    $('.loader-container').removeClass("active");
                },
                success: function( response ) {  
                    $('.ins-checkout-layout').html('');
                    $('.ins-checkout-layout').html(response.cart_data);
                }

            }); 
    });


})(jQuery);

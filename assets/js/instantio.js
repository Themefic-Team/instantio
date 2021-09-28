(function($) {
	'use strict';
	jQuery(document).ready(function() {
		
		/* 
		 * Ajax Quick View
		 */
		if (noquickview == 'no') {
			// Add Quick View Panel DIV to body
			$(document.body).append('<div class="ins-quick-view"></div>');
			// Close Quick View Panel
			$(document).on('click', '.ins-quick-view .close', function(e) {
					$(this).parent().fadeOut(300);
				})
				// Variable Product Quick View Ajax on Click
			$(document).on('click', '.product_type_variable', function(e) {
				e.preventDefault();
				var $this = $(this),
					cartPos = $this.offset(),
					product_id = $this.data('product_id');
				$('.ins-quick-view').css({
					'top': parseInt(cartPos.top) + parseInt(45) + 'px',
					'left': cartPos.left + 'px'
				})
				$.ajax({
					type: 'post',
					url: instantio_ajax_params.wi_ajax_url,
					data: {
						action: 'wi_variable_product_quick_view',
						security: instantio_ajax_params.wi_ajax_nonce,
						product_id: product_id,
					},
					beforeSend: function(data) {
						$this.addClass('loading');
						$('.ins-quick-view').block();
					},
					success: function(data) {
						$this.removeClass('loading');
						$('.ins-quick-view').fadeIn(300).html(data).prepend('<span class="close"></span>');
					},
					error: function(data) {
						console.log(data);
					},
				});
			});
		}
		/* 
		 * Add To Cart Fly Effect
		 */
		if (cartFlyanim == 'on') {
			if(cartFlyicon == 'toggler') {
				$(document).on('click', '.add_to_cart_button:not(.product_type_variable), .single_add_to_cart_button:not(.disabled)', function() {
					$('body').append('<div id="ins-cart-fly">' + instantio_ajax_params.cart_icon + '</div>');
					var endPos = $("#ins-toggle-button").offset();
					var startPos = $(this).offset();
					$('#ins-cart-fly').css({
						'top': startPos.top + 'px',
						'left': startPos.left + 'px'
					}).animate({
						opacity: 1,
						top: endPos.top,
						left: endPos.left
					}, 1500, function() {
						$('.cartboom').addClass('cart_boom');
						setTimeout(function() {
							$('.cartboom').removeClass('cart_boom');
						}, 2200);
						$(this).css({
							'opacity': '0',
							'z-index': '0'
						});
						$(this).detach();
					});
				});
			}

			if(cartFlyicon == 'thumb') {
				// Archive Product
				$(document).on('click', '.add_to_cart_button:not(.product_type_variable)', function() {
					var productThumb = $(this).parents('.has-post-thumbnail').find('.woocommerce-LoopProduct-link').find('img').attr('src');
					var startPos = $(this).parents('.has-post-thumbnail').find('.woocommerce-LoopProduct-link').find('img').offset();
					var productThumbwidth = $(this).parents('.has-post-thumbnail').find('.woocommerce-LoopProduct-link').find('img').width();

					$('body').append('<div id="ins-cart-fly"><img src="' + productThumb + '"></div>');
					var endPos = $("#ins-toggle-button").offset();
					$('#ins-cart-fly').css({
						'top': startPos.top + 'px',
						'left': startPos.left + 'px',
						'width': productThumbwidth + 'px',
					}).animate({
						opacity: 1,
						top: endPos.top,
						left: endPos.left,
						'width': '50px',
						'height': 'auto',
					}, 1500, 'linear', function() {
						$('.cartboom').addClass('cart_boom');
						setTimeout(function() {
							$('.cartboom').removeClass('cart_boom');
						}, 2200);
						$(this).css({
							'opacity': '0',
							'z-index': '0'
						});
						$(this).detach();
					});
				});

				// Single & quick view product
				$(document).on('click', '.single_add_to_cart_button:not(.disabled)', function() {
					if($(this).parents('.has-post-thumbnail').find('.woocommerce-product-gallery__image').find('img').attr('src')) {
						var productThumb = $(this).parents('.has-post-thumbnail').find('.woocommerce-product-gallery__image').find('img').attr('src');
						var startPos = $(this).parents('.has-post-thumbnail').find('.woocommerce-product-gallery__image').find('img').offset();
						var productThumbwidth = $(this).parents('.has-post-thumbnail').find('.woocommerce-product-gallery__image').width();
					} else {
						var productId = $(this).parents('.woocommerce-variation-add-to-cart').find('input[name="add-to-cart"]').attr("value");
						var productThumb = $(this).parents().find('.post-'+productId).find('.woocommerce-LoopProduct-link').find('img').attr('src');
						var startPos = $(this).parents().find('.post-'+productId).find('.woocommerce-LoopProduct-link').find('img').offset();
						var productThumbwidth = $(this).parents().find('.post-'+productId).find('.woocommerce-LoopProduct-link').find('img').width();
					}
					$('body').append('<div id="ins-cart-fly"><img src="' + productThumb + '"></div>');
					var endPos = $("#ins-toggle-button").offset();
					$('#ins-cart-fly').css({
						'top': startPos.top + 'px',
						'left': startPos.left + 'px',
						'width': productThumbwidth + 'px',
					}).animate({
						opacity: 1,
						top: endPos.top,
						left: endPos.left,
						'width': '50px',
						'height': 'auto',
					}, 1500, 'linear', function() {
						$('.cartboom').addClass('cart_boom');
						setTimeout(function() {
							$('.cartboom').removeClass('cart_boom');
						}, 2200);
						$(this).css({
							'opacity': '0',
							'z-index': '0'
						});
						$(this).detach();
					});
				});

			}
		}
		/*
		 * Single Product Ajax Cart
		 */
		if (noajaxaddtocart == 'no') {
			$(document).on('click', '.single_add_to_cart_button:not(.disabled)', function(e) {
				e.preventDefault();
				var thisbutton = $(this),
					cart_form = thisbutton.closest('form.cart'),
					id = thisbutton.val(),
					product_qty = cart_form.find('input[name=quantity]').val() || 1,
					product_id = cart_form.find('input[name=product_id]').val() || id,
					variation_id = cart_form.find('input[name=variation_id]').val() || 0;
				var data = {
					action: 'wi_single_ajax_add_to_cart',
					product_id: product_id,
					product_sku: '',
					quantity: product_qty,
					variation_id: variation_id,
				};
				$(document.body).trigger('adding_to_cart', [thisbutton, data]);
				$.ajax({
					type: 'post',
					url: instantio_ajax_params.wi_ajax_url,
					data: data,
					beforeSend: function(response) {
						thisbutton.removeClass('added').addClass('loading');
					},
					complete: function(response) {
						thisbutton.addClass('added').removeClass('loading');
					},
					success: function(response) {
						if(response.error & response.product_url) {
							window.location = response.product_url;
							return;
						} else {
							$(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, thisbutton]);
						}
					},
				});
				return false;
			});
		}

	});
})(jQuery);
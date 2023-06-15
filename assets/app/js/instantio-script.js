(function ($) {
	"use strict";
	$(document).ready(function () {
		$(document).on("click", ".ins-checkout-close", function (e) {
			e.preventDefault();
			// gsap.reverse();
			$(".ins-checkout-layout-3.slide").removeClass("active");
			$(".ins-checkout-overlay").removeClass("active");
			$(".ins-checkout-popup").removeClass("active");
			$(".ins-checkout-layout").removeClass("active");
			$(".ins-checkout-popup").removeClass("fadeIn");
			$(".ins-click-to-show.popupcart").removeClass("hide");
		});
		$(document).on("click", ".ins-click-to-show.sidecart", function (e) {
			e.preventDefault();
			$(".ins-checkout-layout-3.slide").toggleClass("active");
			$(".ins-checkout-overlay").toggleClass("active");
			ins_owl_carousel();
		});

		$(document).on("click", ".ins-click-to-show.popupcart", function (e) {
			e.preventDefault();
			$(".ins-checkout-overlay").toggleClass("active");
			$(".ins-checkout-popup").toggleClass("active");
			$(".ins-checkout-layout").toggleClass("active");
			$(".ins-checkout-popup").toggleClass("fadeIn");
			ins_owl_carousel();

			ins_cart_animation("cart");
			$(this).toggleClass("hide");
		});

		$(document).on("click", ".ins-checkout-overlay", function (e) {
			e.preventDefault();
			$(".ins-checkout-layout-3.slide").removeClass("active");
			$(".ins-checkout-overlay").removeClass("active");
			$(".ins-checkout-popup").removeClass("active");
			$(".ins-checkout-layout").removeClass("active");
			$(".ins-checkout-popup").removeClass("fadeIn");
			$(".ins-click-to-show.popupcart").removeClass("hide");
		});

		// Instantio Multistep Checkout
		$(document).on("click", ".ins-step-btn", function (e) {
			e.preventDefault();
			$(".ins-step-btn").removeClass("active");
			$(this).addClass("active");
			var $this = $(this);
			var step = $this.data("step");
			$(".ins-single-step").removeClass("active");
			$("." + step).addClass("active");
		});

		// Hide toggle button if empty cart
		hide_toggle_btn();

		/*
		 * Ajax Quick View
		 */
		// alert(noquickview);
		if (noquickview == false) {
			// Add Quick View Panel DIV to body
			$(document.body).append('<div class="ins-quick-view"></div>');
			// Close Quick View Panel
			$(document).on("click", ".ins-quick-view .close", function (e) {
				$(this).parent().fadeOut(300);
			});
			// Variable Product Quick View Ajax on Click
			$(document).on("click", ".product_type_variable", function (e) {
				e.preventDefault();

				var $this = $(this),
					cartPos = $this.offset(),
					product_id = $this.data("product_id");

				$(".ins-quick-view").css({
					top: parseInt(cartPos.top) + parseInt(45) + "px",
					left: cartPos.left + "px",
				});

				if ($this.hasClass("ins-sell-add-to-cart")) {
					cartPos = $this.closest(".ins-single-product-sell").offset();
					$(".ins-quick-view").css({
						top: parseInt(cartPos.bottom) + "px",
						left: cartPos.left + "px",
					});
				}
				$.ajax({
					type: "post",
					url: ins_params.ajax_url,
					data: {
						action: "ins_variable_product_quick_view",
						security: ins_params.ins_ajax_nonce,
						product_id: product_id,
					},
					beforeSend: function (data) {
						$this.addClass("loading");
						$(".ins-quick-view").block();
					},
					success: function (data) {
						$this.removeClass("loading");
						$(".ins-quick-view")
							.fadeIn(300)
							.html(data)
							.prepend('<span class="close"></span>');
					},
					error: function (data) {
						console.log(data);
					},
				});
			});
		}
	});

	// Hide Toggle Button
	function hide_toggle_btn() {
		if (hide_toggler == true) {
			var cart_item_count = $(".ins-checkout-layout").find(
				".ins-single-cart-item"
			).length;
			if (cart_item_count == 0) {
				$(".ins-toggle-btn").css({ visibility: "hidden", opacity: "0" });
				$(".ins-checkout-layout-3").removeClass("active");
				$(".ins-checkout-overlay").removeClass("active");
				$(".ins-checkout-popup").removeClass("active");
			}else{
				$(".ins-toggle-btn").css({ visibility: "visible", opacity: "1" });
			}
		}
	}

	// Ajax Add To Cart
	$(document.body).on("added_to_cart", function () {
		var thisbutton = $(this);
		$.ajax({
			url: ins_params.ajax_url,
			type: "POST",
			data: {
				id: "1",
				action: "ins_ajax_cart_reload",
			},
			beforeSend: function (response) {
				thisbutton.removeClass("added").addClass("loading");
			},
			complete: function (response) {
				thisbutton.addClass("added").removeClass("loading");
			},
			success: function (response) {
				$(".ins-quick-view").hide();  
				$("#ins_cart_totals").html(response.data.ins_cart_count)
				// $("#ins_cart_totals").append(response.data.ins_cart_count);
				$(".ins-checkout-layout .ins-content").removeClass("hide");
				$(".ins-checkout-layout .ins-content").addClass("show");
				$(".ins-checkout-layout .ins-cart-empty").addClass("hide");
				$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
				$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

				// $(".ins-checkout-layout").append(response); 
			

				if (auto_open_toggle == true) {
					$(".ins-checkout-layout-3").addClass("active");
					$(".ins-checkout-overlay").addClass("active");
					$(".ins-checkout-popup").addClass("active");
					$(".ins-checkout-popup").addClass("fadeIn");
				}
				ins_owl_carousel();
				hide_toggle_btn();
				
			},
		});
	});

	// Ajax Single Page Add To Cart
	$(document).on("click", ".single_add_to_cart_button", function (e) {
		if (disable_ajax_add_cart == true) {
			return;
		}
		e.preventDefault();
		var thisbutton = $(this),
			cart_form = thisbutton.closest("form.cart"),
			id = thisbutton.val(),
			product_id = cart_form.find("input[name=product_id]").val() || id,
			product_qty = cart_form.find("input[name=quantity]").val() || 1,
			variation_id = cart_form.find("input[name=variation_id]").val() || 0;
		$.ajax({
			url: ins_params.ajax_url,
			type: "POST",
			data: {
				action: "ins_ajax_cart_single",
				product_id: product_id,
				quantity: product_qty,
				variation_id: variation_id,
			},
			beforeSend: function (response) {
				thisbutton.removeClass("added").addClass("loading");
			},
			complete: function (response) {
				ins_cart_icon_animation();
				thisbutton.addClass("added").removeClass("loading");
			},
			success: function (response) {
				$(".ins-quick-view").hide(); 
				$("#ins_cart_totals").html(response.data.ins_cart_count)
				$(".ins-checkout-layout .ins-content").removeClass("hide");
				$(".ins-checkout-layout .ins-content").addClass("show");
				$(".ins-checkout-layout .ins-cart-empty").addClass("hide");
				$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
				$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

				ins_owl_carousel();
 
				

				if (auto_open_toggle == true) {
					$(".ins-checkout-layout-3").addClass("active");
					$(".ins-checkout-overlay").addClass("active");
					$(".ins-checkout-popup").addClass("active");
					$(".ins-checkout-popup").addClass("fadeIn");
				}
				$(".ins-quick-view").hide();
				// go back to cart page
				$('.ins-single-step').removeClass('done');
				$('.ins-single-step').removeClass('active');
				$('.ins-single-step.step-1').addClass('done');
				$('.ins-single-step.step-1').addClass('active');
				$('.ins-content').find('.ins-cart-inner').hide();
				$('.ins-content').find('.ins-cart-inner').removeClass('active'); 
				$('.ins-content').find('.step-1').show();
				$('.ins-content').find('.step-1').addClass('active');
			},
		});
	});

	// Add To Cart Flying Animation
	$(document).on("click", ".add_to_cart_button", function () {
		if (cart_fly_anim == false) {
			ins_cart_icon_animation();
			return;
		}
		if ($(this).hasClass("product_type_variable")) {
			return;
		}
		
		var productThumb = $(this).closest(".product").find("img").attr("src");
		var startPos = $(this).closest(".product").find("img").offset();
		var productThumbwidth = $(this).closest(".product").find("img").width();
		var endPos = $(".ins-toggle-btn").offset();
		if (cart_fly_icon != "" && cart_fly_icon != true) {
			var productThumbtag =
				'<span class="ins-cart-fly-icon">' + cart_fly_icon + "</span>";
		} else {
			productThumbtag = '<img src="' + productThumb + '">';
		}

		$("body").append('<div id="ins-cart-fly">' + productThumbtag + "</div>");
		
		$("#ins-cart-fly")
			.css({
				top: startPos.top + "px",
				left: startPos.left + "px",
				width: productThumbwidth + "px",
			})
			.animate(
				{
					opacity: 1,
					top: endPos.top,
					left: endPos.left,
					width: "50px",
					height: "auto",
				},
				1500,
				"linear",
				function () {
					$(".cartboom").addClass("cart_boom");
					setTimeout(function () {
						$(".cartboom").removeClass("cart_boom");
					}, 2200);
					$(this).css({
						opacity: "0",
						"z-index": "0",
					});
					$(this).detach();

					ins_cart_icon_animation();
				}
			);
	});

	$(document).on("click", ".single_add_to_cart_button", function () {
		if (cart_fly_anim == false) {
			return;
		}
		var productThumb = $(this)
			.closest(".product")
			.find(".woocommerce-product-gallery__wrapper")
			.find("img");
		var productThumb_src = productThumb.attr("src");
		var productThumbwidth = productThumb.width();
		var startPos = productThumb.offset();
		var endPos = $(".ins-toggle-btn").offset();
		if (cart_fly_icon != "" && cart_fly_icon != true) {
			productThumb =
				'<span class="ins-cart-fly-icon">' + cart_fly_icon + "</span>";
		} else {
			productThumb = '<img src="' + productThumb_src + '">';
		}
		$("body").append('<div id="ins-cart-fly">' + productThumb + "</div>");

		$("#ins-cart-fly")
			.css({
				top: startPos.top + "px",
				left: startPos.left + "px",
				width: productThumbwidth + "px",
			})
			.animate(
				{
					opacity: 1,
					top: endPos.top,
					left: endPos.left,
					width: "50px",
					height: "auto",
				},
				1500,
				"linear",
				function () {
					$(".cartboom").addClass("cart_boom");
					setTimeout(function () {
						$(".cartboom").removeClass("cart_boom");
					}, 2200);
					$(this).css({
						opacity: "0",
						"z-index": "0",
					});
					$(this).detach();

					ins_cart_icon_animation();
				}
			);
	});

	// Ins Qty plus Minus Script
	$(document).on("click", ".ins-cart-minus", function (e) {
		e.preventDefault();
		let minus = $(this)
			.closest(".ins-cart-qty-wrap")
			.find('.quantity input[type="number"].qty');

		let qty = minus.val();
		if (qty >= 1) {
			minus.val(qty - 1);
		}
		$('.ins-checkout-layout button[name="update_cart"]').trigger("click");
	});

	$(document).on("click", ".ins-cart-plus", function (e) {
		e.preventDefault();
		let plus = $(this)
			.closest(".ins-cart-qty-wrap")
			.find('.quantity input[type="number"].qty');
		let qty = plus.val();
		plus.val(parseInt(qty) + 1);
		$('.ins-checkout-layout button[name="update_cart"]').trigger("click");
	});

	// Ins Cart Item Quantity Change
	$(document).on(
		"change",
		".ins-cart-item-quantity .quantity .qty",
		function (e) {
			e.preventDefault();
			$('.ins-checkout-layout button[name="update_cart"]').trigger("click");
		}
	);

	// Ins Cart Item Remove
	$(document).on("click", ".ins-cart-item-remove", function (e) {
		e.preventDefault();
		let animate_remove = $(this).closest(".ins-single-cart-item");
		let product_id = $(this).find("a.remove").data("product_id");
		let variation_id = $(this).find("a.remove").data("variation_id");
		$.ajax({
			url: ins_params.ajax_url,
			type: "POST",
			data: {
				product_id: product_id,
				variation_id: variation_id,
				action: "ins_ajax_cart_item_remove",
			},
			beforeSend: function (response) {
				$(".loader-container").addClass("active");
			},
			complete: function (response) {
				$(".loader-container").removeClass("active");
			},
			success: function (response) {
				gsap.to(animate_remove, {
					opacity: 0,
					x: -100,
					duration: 0.2,
					delay: 0.2,
					ease: "fadeIn",
				});
				setTimeout(function () {
					// $(".ins-checkout-layout").html("");
					// $(".ins-checkout-layout").append(response.cart_data);
					$("#ins_cart_totals").html(response.data.ins_cart_count)
					if(response.data.display == "show"){ 
						// alert("show");
						$(".ins-checkout-layout .ins-content").removeClass("hide");
					}
					if(response.data.hide_empty == "show"){ 
						$(".ins-checkout-layout .ins-cart-empty").removeClass("hide");;
					}
					$(".ins-checkout-layout .ins-content").addClass(response.data.display);
					$(".ins-checkout-layout .ins-cart-empty").addClass(response.data.hide_empty);
					$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
					$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

					ins_owl_carousel();
					// Hide toggle button if empty cart
					hide_toggle_btn();
				}, 400);
			},
		});
	});

	// empty cart
	$(document).on("click", ".ins-empty-cart", function (e) {
		e.preventDefault();

		$.ajax({
			url: ins_params.ajax_url,
			type: "POST",
			data: {
				action: "ins_ajax_empty_cart",
			},
			beforeSend: function (response) {
				$(".loader-container").addClass("active");
			},
			complete: function (response) {
				$(".loader-container").removeClass("active");
			},
			success: function (response) {
				// $(".ins-checkout-layout").html("");
				// $(".ins-checkout-layout").html(response.cart_data);
				$("#ins_cart_totals").html(response.data.ins_cart_count)
				$(".ins-checkout-layout .ins-content").removeClass("show");
				$(".ins-checkout-layout .ins-content").addClass("hide");
				$(".ins-checkout-layout .ins-cart-empty").removeClass("hide");
				$(".ins-checkout-layout .ins-cart-empty").addClass("show");
				$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
				$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);
				// Hide toggle button if empty cart
				hide_toggle_btn();
			},
		});
	});

	// Update Cart
	// empty cart
	$(document).on(
		"click",
		'.ins-checkout-layout button[name="update_cart"], .ins-checkout-layout button[name="apply_coupon"]',
		function (e) {
			e.preventDefault();
			var $this = $(this),
				$form = $this.closest("form"),
				cart_item_keys = [],
				product_ids = [],
				quantities = [],
				coupon_code = $form.find('input[name="coupon_code"]').val();

			$form.find(".cart_item").each(function () {
				var $cart_item = $(this),
					cart_item_key = $cart_item.data("cart-item-key"),
					product_id = $cart_item.data("product-id"),
					quantity = $cart_item.find(".quantity input.qty").val();

				cart_item_keys.push(cart_item_key);
				product_ids.push(product_id);
				quantities.push(quantity);
			});
			$.ajax({
				url: ins_params.ajax_url,
				type: "post",
				data: {
					action: "ins_ajax_update_cart",
					cart_item_keys: cart_item_keys,
					product_ids: product_ids,
					quantities: quantities,
					coupon_code: coupon_code,
				},
				beforeSend: function (response) {
					$(".loader-container").addClass("active");
				},
				complete: function (response) {
					$(".loader-container").removeClass("active");
				},
				success: function (response) { 
					// $(".ins-checkout-layout").html("");
					// $(".ins-checkout-layout").append(response.cart_data);
					$("#ins_cart_totals").html(response.data.ins_cart_count)
					if(response.data.display == "show"){ 
						// alert("show");
						$(".ins-checkout-layout .ins-content").removeClass("hide");
					}
					if(response.data.hide_empty == "show"){ 
						$(".ins-checkout-layout .ins-cart-empty").removeClass("hide");;
					}
					$(".ins-checkout-layout .ins-content").addClass(response.data.display);
					$(".ins-checkout-layout .ins-cart-empty").addClass(response.data.hide_empty);
					$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
					$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);
					ins_owl_carousel();
					// Hide toggle button if empty cart
					hide_toggle_btn();
				},
			});
			return false;
		}
	);

	// Remove Coupon
	$(document).on(
		"click",
		".ins-checkout-layout .woocommerce-remove-coupon",
		function (e) {
			e.preventDefault();
			let coupon = $(this).data("coupon");
			$.ajax({
				url: ins_params.ajax_url,
				type: "post",
				data: {
					coupon: coupon,
					action: "ins_ajax_remove_coupon",
				},
				beforeSend: function (response) {
					$(".loader-container").addClass("active");
				},
				complete: function (response) {
					$(".loader-container").removeClass("active");
				},
				success: function (response) {
					$(".ins-checkout-layout").html("");
					$(".ins-checkout-layout").html(response.cart_data);
				},
			});
		}
	);

	function ins_owl_carousel() {
		if ($(".ins-product-sell-carousel").length > 0) {
			$(".ins-product-sell-carousel").owlCarousel("destroy");
			$(".ins-product-sell-carousel").owlCarousel({
				// loop:true,
				margin: 10,
				nav: true,
				dots: false,
				responsive: {
					0: {
						items: 1,
					},
					600: {
						items: 1,
					},
					1000: {
						items: 2,
					},
				},
			});
		}
	}
	function ins_cart_icon_animation() {
		$(".ins-toggle-btn").addClass("ins-icon-animation-one");
		setTimeout(function () {
			$(".ins-toggle-btn").removeClass("ins-icon-animation-one");
		}, 1000);
	}

	// Cart Animation Gsap
	function ins_cart_animation($step = "cart") { 
		if ($step == "cart" && $('.ins_animate_one').length > 0) {
			gsap.from(".ins_animate_one .ins-checkout-header", {
				opacity: 0,
				y: -100,
				duration: 0.2,
				delay: 0.2,
				ease: "fadeIn",
			});
			gsap.from(".ins_animate_one .ins-checkout-steps", {
				opacity: 0,
				x: -100,
				duration: 0.2,
				delay: 0.2,
				ease: "fadeIn",
			});
			// gsap.from(".ins-cart-btns", {opacity: 0, y: 100, duration: 0.4, delay: 0.4, ease: "slideIn" });
			gsap.from(".ins_animate_one .step-1 .ins-cart-content-wrap, .step-1 .ins-up-sells", {
				opacity: 0,
				y: -100,
				duration: 0.2,
				delay: 0.4,
				ease: "fadeIn",
			});
			gsap.from(".ins_animate_one .step-1 .ins-cart-footer-content, .step-1  .ins-cart-btns", {
				opacity: 0,
				x: 100,
				duration: 0.2,
				delay: 0.6,
				ease: "fadeIn",
			});
		}
	}
})(jQuery);

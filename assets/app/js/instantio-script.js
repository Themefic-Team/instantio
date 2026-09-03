(function ($) {
	"use strict";

	/**
	 * Animate Instantio elements without requiring a third-party animation runtime.
	 * Large translations become a short fade when reduced motion is requested.
	 *
	 * @param {string|Element|JQuery} targets Elements or selector to animate.
	 * @param {Object} options Animation options.
	 */
	window.instantioAnimateFrom = function (targets, options) {
		var settings = $.extend({ x: 0, y: 0, duration: 200, delay: 0, reverse: false }, options || {});
		var reducedMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;
		var elements = typeof targets === "string" ? document.querySelectorAll(targets) : $(targets).toArray();

		Array.prototype.forEach.call(elements, function (element) {
			if (!element || typeof element.animate !== "function") {
				return;
			}

			var offset = reducedMotion ? "translate3d(0, 0, 0)" : "translate3d(" + settings.x + "px, " + settings.y + "px, 0)";
			var visible = { opacity: 1, transform: "translate3d(0, 0, 0)" };
			var hidden = { opacity: 0, transform: offset };

			element.animate(settings.reverse ? [visible, hidden] : [hidden, visible], {
				duration: reducedMotion ? 150 : settings.duration,
				delay: reducedMotion ? 0 : settings.delay,
				easing: "ease-out",
				fill: "backwards"
			});
		});
	};

	//Single Layout
	function single_step_order_review_callback() {
		// Check if instantio_layout_step is false, then exit the function
		if (!instantio_layout_step) {
			return;
		}

		// Ins existing code for single_step_order_review_callback() goes here
		$('.ins-cart-inner.shipping input').each(function () {
			var value = $(this).val();
			if (value != '') {
				$(this).closest('p.form-row').find('label').addClass('active');
			} else {
				$(this).closest('p.form-row').find('label').removeClass('active');
			}
		});

		$.ajax({
			url: instantio_params.ajax_url,
			type: "POST",
			data: {
				action: "instantio_update_order_review_callback"
			},
			success: function (response) {
				$('.ins-cart-inner.payment .ins-contact-wrap').html('');
				$('.ins-cart-inner.payment .ins-contact-wrap').append(response.data.ins_contact);

				$('.ins-cart-inner.payment .ins-card-cross-sell').html('');
				$('.ins-cart-inner.payment .ins-card-cross-sell').append(response.data.cross_sells);

				$('.ins-cart-inner.payment .ins-shipping-wrap').html('');
				$('.ins-cart-inner.payment .ins-shipping-wrap').append(response.data.ins_shiiping);

				// $(".ins-checkout-layout .ins-checkout-shipping .ins-cart-content-wrap").html("");
				// $(".ins-checkout-layout .ins-checkout-shipping .ins-cart-content-wrap").append(response.data.ins_shipping_additional);

				if (response.data.cross_sells != '' && response.data.cross_sells != null) {
					$('.ins-cart-inner.payment .ins-card-cross-sell').addClass('active');
				} else {
					$('.ins-cart-inner.payment .ins-card-cross-sell').removeClass('active');
				}
			},
		});
	}


	/**
	 * Check if a node is blocked for processing.
	 *
	 * @param {JQuery Object} $node
	 * @return {bool} True if the DOM Element is UI Blocked, false if not.
	 */
	var is_blocked = function ($node) {
		return $node.is('.processing') || $node.parents('.processing').length;
	};

	//Pre Loader Block a node visually for processing.
	var block = function ($node) {
		if (!is_blocked($node)) {
			$node.addClass('processing').block({
				message: null,
				overlayCSS: {
					background: '#fff',
					opacity: 0.6
				}
			});
		}
	};

	var update_cart_totals_div = function (html_str) {
		$('.ins-cart-collaterals .cart_totals').replaceWith(html_str);
		$(document.body).trigger('updated_cart_totals');
	};


	var get_url = function (endpoint) {
		return instantio_params.wc_ajax_url.toString().replace(
			'%%endpoint%%',
			endpoint
		);
	};

	/**
	 * Unblock a node after processing is complete.
	 *
	 * @param {JQuery Object} $node
	 */
	var unblock = function ($node) {
		$node.removeClass('processing').unblock();
	};

	$(document).ready(function () {
		var insCartTrigger = null;

		function insCartIsOpen() {
			return $(".ins-checkout-layout-3").hasClass("active") || $(".ins-checkout-popup").hasClass("active");
		}

		function insFocusCart() {
			var closeButton = $("#instantio-cart-panel .ins-checkout-close:visible").first();
			(closeButton.length ? closeButton : $("#instantio-cart-panel")).trigger("focus");
		}

		function insCloseCart() {
			$(".ins-checkout-layout-3.slide").removeClass("active");
			$(".ins-checkout-overlay").removeClass("active");
			$(".ins-checkout-popup").removeClass("active fadeIn");
			$(".ins-checkout-layout").removeClass("active");
			$(".ins-click-to-show.popupcart").removeClass("hide");
			$(".ins-click-to-show").attr("aria-expanded", "false");
			if (insCartTrigger && document.contains(insCartTrigger)) {
				insCartTrigger.focus();
			}
		}

		// instantio Close Button
		$(document).on("click", ".ins-checkout-close", function (e) {
			e.preventDefault();
			insCloseCart();
		});
		// instantio Clikc to Show Cart Slide
		$(document).on("click", ".ins-click-to-show.sidecart", function (e) {
			e.preventDefault();
			insCartTrigger = this;
			$(".ins-checkout-layout-3.slide").toggleClass("active");
			$(".ins-checkout-overlay").toggleClass("active");
			$(this).attr("aria-expanded", $(".ins-checkout-layout-3.slide").hasClass("active") ? "true" : "false");
			if (insCartIsOpen()) {
				setTimeout(insFocusCart, 0);
			}
			ins_owl_carousel();
		});

		// instantio Clikc to Show Cart popup
		$(document).on("click", ".ins-click-to-show.popupcart", function (e) {
			e.preventDefault();
			insCartTrigger = this;
			$(".ins-checkout-overlay").toggleClass("active");
			$(".ins-checkout-popup").toggleClass("active");
			$(".ins-checkout-layout").toggleClass("active");
			$(".ins-checkout-popup").toggleClass("fadeIn");
			ins_owl_carousel();

			ins_cart_animation("cart");
			$(this).toggleClass("hide");
			$(this).attr("aria-expanded", $(".ins-checkout-popup").hasClass("active") ? "true" : "false");
			if (insCartIsOpen()) {
				setTimeout(insFocusCart, 0);
			}
		});

		// instantio Overlay
		$(document).on("click", ".ins-checkout-overlay", function (e) {
			e.preventDefault();
			insCloseCart();
		});

		$(document).on("keydown", function (e) {
			if (!insCartIsOpen()) {
				return;
			}
			if (e.key === "Escape") {
				e.preventDefault();
				insCloseCart();
				return;
			}
			if (e.key !== "Tab") {
				return;
			}
			var focusable = $("#instantio-cart-panel").find('a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])').filter(":visible");
			if (!focusable.length) {
				e.preventDefault();
				$("#instantio-cart-panel").trigger("focus");
				return;
			}
			var first = focusable.first()[0];
			var last = focusable.last()[0];
			if (e.shiftKey && document.activeElement === first) {
				e.preventDefault();
				last.focus();
			} else if (!e.shiftKey && document.activeElement === last) {
				e.preventDefault();
				first.focus();
			}
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


		// ins content-height
		if ($('.ins-checkout-steps').length == 0) {
			// alert('Please add checkout steps');
			// $('.ins-content').attr('style', 'height: auto !important;');
		}

		/*
		 * Ajax Quick View
		 */
		// alert(instantio_no_quick_view);
		if (instantio_no_quick_view == false) {
			// Add Quick View Panel DIV to body

			// Close Quick View Panel
			$(document).on("click", ".ins-quick-view .close", function (e) {
				$(this).parent().fadeOut(300);
			});

			// Variable Product Quick View Ajax on Click
			$(document).on("click", ".product_type_variable", function (e) {
				e.preventDefault();


				// Close Quick View Panel
				$(document).on("click", ".ins-quick-view .close", function (e) {
					$(this).parent().fadeOut(300);
				});

				// Add Quick View Panel DIV to body
				$(this).closest('.product').append('<div class="ins-quick-view"></div>');
				$(this).closest('.product').css('position', 'relative');
				// Close Quick View Panel
				$(document).on("click", ".ins-quick-view .close", function (e) {
					$(this).parent().fadeOut(300);
				});

				var $this = $(this),
					cartPos = $this.offset(),
					product_id = $this.data("product_id");

				if ($this.hasClass("ins-sell-add-to-cart")) {
					$(document.body).append('<div class="ins-quick-view"></div>');
					cartPos = $this.closest(".ins-single-product-sell").offset();

					$(".ins-quick-view").attr("style", "top: " + parseInt(cartPos.top) + "px !important; left: " + cartPos.left + "px !important;");
				} else {
					// Add Quick View Panel DIV to body
					$('.ins-quick-view').remove();
					$(this).closest('.product').append('<div class="ins-quick-view"></div>');
					$(this).closest('.product').css('position', 'relative');
				}
				$.ajax({
					type: "post",
					url: instantio_params.ajax_url,
					data: {
						action: "instantio_variable_product_quick_view",
						security: instantio_params.instantio_ajax_nonce,
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
		if (instantio_hide_toggler == true) {
			var cart_item_count = $("#ins_cart_totals").html();
			if (cart_item_count == 0) {
				$(".ins-toggle-btn").css({ visibility: "hidden", opacity: "0" });
				$(".ins-mobile-bar").css({ display: 'none' });
				$(".ins-checkout-layout-3").removeClass("active");
				$(".ins-checkout-overlay").removeClass("active");
				$(".ins-checkout-popup").removeClass("active");
			} else {
				$(".ins-toggle-btn").css({ visibility: "visible", opacity: "1" });
				if ($(window).width() <= 576) {
					$(".ins-mobile-bar").css({
						visibility: "visible",
						opacity: "1"
					});
				}

			}
		}
		}

		// Ajax Add To Cart
		var ins_cart_refresh_timeout;
		var ins_cart_last_local_update = 0;

		function ins_refresh_cart_after_add() {
			var thisbutton = $(document.body);
		$.ajax({
			url: instantio_params.ajax_url,
			type: "POST",
			data: {
				id: "1",
				nonce: instantio_params.instantio_ajax_nonce,
				action: "instantio_ajax_cart_reload",
			},
			beforeSend: function (response) {
				thisbutton.removeClass("added").addClass("loading");
			},
			complete: function (response) {
				thisbutton.addClass("added").removeClass("loading");
			},
				success: function (response) {
					if (!response || !response.success || !response.data) {
						return;
					}

					$(".ins-quick-view").hide();
				$("#ins_cart_totals").html(response.data.ins_cart_count);
				$("#ins_cart_mobile_totals").html(response.data.ins_cart_count);
				// $("#ins_cart_totals").append(response.data.ins_cart_count);
				$(".ins-checkout-layout .ins-content").removeClass("hide");
				$(".ins-checkout-layout .ins-content").addClass("ins-show");
				$(".ins-single-layout-wrap .ins_single_layout_checkout_area").removeClass("hide");
				$(".ins-checkout-layout .ins-cart-empty").addClass("hide");
				$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
				$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

				$(".ins-checkout-layout .ins-checkout-shipping .ins-cart-content-wrap").html("");
				$(".ins-checkout-layout .ins-checkout-shipping .ins-cart-content-wrap").append(response.data.ins_shipping_additional);

				if (instantio_auto_open_toggle == true) {
					$(".ins-checkout-layout-3").addClass("active");
					$(".ins-checkout-overlay").addClass("active");
					$(".ins-checkout-popup").addClass("active");
					$(".ins-checkout-popup").addClass("fadeIn");
				}

				ins_owl_carousel();
				hide_toggle_btn();
				$(".loader-container").addClass("active");
				setTimeout(function () {
					$(".loader-container").removeClass("active");
					// go back to cart page
					$('.ins-single-step').removeClass('done');
					$('.ins-single-step').removeClass('active');
					$('.ins-single-step.step-1').addClass('done');
					$('.ins-single-step.step-1').addClass('active');
					$('.ins-content').find('.ins-cart-inner').hide();
					$('.ins-content').find('.ins-cart-inner').removeClass('active');
					$('.ins-content').find('.step-1').show();
					$('.ins-content').find('.step-1').addClass('active');
				}, 1000);
				single_step_order_review_callback();
				$('.ins-checkout-layout button[name="update_cart"]').trigger("click");
			},
			});
		}

		function ins_schedule_cart_refresh() {
			if (Date.now() - ins_cart_last_local_update < 500) {
				return;
			}

			clearTimeout(ins_cart_refresh_timeout);
			ins_cart_refresh_timeout = setTimeout(ins_refresh_cart_after_add, 100);
		}

		$(document.body).on("added_to_cart", ins_schedule_cart_refresh);

		if (document.body && document.body.addEventListener) {
			document.body.addEventListener("wc-blocks_added_to_cart", ins_schedule_cart_refresh);
		}

		// Ajax Single Page Add To Cart
	$(document).on("click", ".single_add_to_cart_button", function (e) {
		if (instantio_disable_ajax_add_cart == true) {
			return;
		}
		e.preventDefault();
		var thisbutton = $(this),
			cart_form = thisbutton.closest("form.cart"),
			id = thisbutton.val(),
			product_id = cart_form.find("input[name=product_id]").val() || cart_form.find("input[name=add-to-cart]").val() || id,
			product_qty = cart_form.find("input[name=quantity]").val() || 1,
			variation_id = cart_form.find("input[name=variation_id]").val() || 0,
			asnp_wepb_items = cart_form.find("input[name=asnp_wepb_items]").val() || '';

		if (cart_form.find("input[name=variation_id]").length > 0) {
			if (variation_id == '' || variation_id == 0) {
				return;
			}
		}

		var grouped_data = {};

		cart_form.find('input[name^="quantity["]').each(function () {
			var name = $(this).attr('name'); // quantity[123]
			var matches = name.match(/\[(\d+)\]/);

			if (matches) {
				var child_id = matches[1];
				var qty = $(this).val();

				if (qty > 0) {
					grouped_data[child_id] = qty;
				}
			}
		});

		$.ajax({
			url: instantio_params.ajax_url,
			type: "POST",
			data: {
				action: "instantio_ajax_cart_single",
				nonce: instantio_params.instantio_ajax_nonce,
				product_id: product_id,
				quantity: Object.keys(grouped_data).length ? grouped_data : product_qty,
				variation_id: variation_id,
				asnp_wepb_items: asnp_wepb_items
			},
			beforeSend: function (response) {
				thisbutton.removeClass("added").addClass("loading");

			},
			complete: function (response) {
				ins_cart_icon_animation();
				thisbutton.addClass("added").removeClass("loading");
			},
			success: function (response) {
				if (!response || !response.success || !response.data) {
					return;
				}

				$(".ins-quick-view").hide();
				$("#ins_cart_totals").html(response.data.ins_cart_count);
				$("#ins_cart_mobile_totals").html(response.data.ins_cart_count);
				$(".ins-checkout-layout .ins-content").removeClass("hide");
				$(".ins-single-layout-wrap .ins_single_layout_checkout_area").removeClass("hide");
				$(".ins-checkout-layout .ins-content").addClass("ins-show");
				$(".ins-checkout-layout .ins-cart-empty").addClass("hide");
				$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
				$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

				ins_owl_carousel();

				if (instantio_auto_open_toggle == true) {
					$(".ins-checkout-layout-3").addClass("active");
					$(".ins-checkout-overlay").addClass("active");
					$(".ins-checkout-popup").addClass("active");
					$(".ins-checkout-popup").addClass("fadeIn");
				}
				$(".ins-quick-view").hide();
				// go back to cart page
				$(".loader-container").addClass("active");

				setTimeout(function () {
					$(".loader-container").removeClass("active");
					// go back to cart page
					$('.ins-single-step').removeClass('done');
					$('.ins-single-step').removeClass('active');
					$('.ins-single-step.step-1').addClass('done');
					$('.ins-single-step.step-1').addClass('active');
					$('.ins-content').find('.ins-cart-inner').hide();
					$('.ins-content').find('.ins-cart-inner').removeClass('active');
					$('.ins-content').find('.step-1').show();
					$('.ins-content').find('.step-1').addClass('active');
				}, 1000);

				single_step_order_review_callback();
				$('.ins-checkout-layout button[name="update_cart"]').trigger("click");

				ins_cart_last_local_update = Date.now();
				$(document.body).trigger("added_to_cart", [
					response.data.fragments || {},
					response.data.cart_hash || "",
					thisbutton,
				]);
			},
		});

	});

	// Add To Cart Flying Animation
	$(document).on("click", ".add_to_cart_button", function () {

		var currentScreenSize = $(window).width() < 577;

		if (currentScreenSize == true) {
			return;
		}

		if (instantio_cart_fly_anim == false) {
			ins_cart_icon_animation();
			return;
		}

		if ($(this).hasClass("product_type_variable")) {
			return;
		}

		var productContainer = $(this).closest(".product, .ins-single-product-sell");
		var productImage = productContainer.find("img").first();
		var productThumb = productImage.attr("src");
		var startPos = productImage.offset();
		var productThumbwidth = productImage.width();
		var endPos = $(".ins-toggle-btn").offset();
		if (!productImage.length || !startPos || !endPos) {
			ins_cart_icon_animation();
			return;
		}
		if (instantio_cart_fly_icon != "" && instantio_cart_fly_icon != true) {
			var productThumbtag =
				'<span class="ins-cart-fly-icon">' + instantio_cart_fly_icon + "</span>";
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

		if (instantio_cart_fly_anim == false) {
			return;
		}

		var thisbutton = $(this);
		var cart_form = thisbutton.closest("form.cart");
		var variation_id = cart_form.find("input[name=variation_id]").val() || 0;

		if (cart_form.find("input[name=variation_id]").length > 0) {
			if (variation_id == '' || variation_id == 0) {
				return;
			}
		}

		if (instantio_cart_fly_anim == false) {
			return;
		}
		var productThumb = $(this)
			.closest(".product")
			.find(".woocommerce-product-gallery__wrapper")
			.find("img");
		if (productThumb.length == 0) {
			var productThumb = $(this)
				.closest(".product")
				.find("a")
				.find("img");
		}
		if (typeof productThumb == 'undefined' || productThumb.length == 0) {
			return false;
		}
		var productThumb_src = productThumb.attr("src");
		var productThumbwidth = productThumb.width();
		var startPos = productThumb.offset();
		var endPos = $(".ins-toggle-btn").offset();
		if (instantio_cart_fly_icon != "" && instantio_cart_fly_icon != true) {
			productThumb =
				'<span class="ins-cart-fly-icon">' + instantio_cart_fly_icon + "</span>";
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
		single_step_order_review_callback();
	});

	$(document).on("click", ".ins-cart-plus", function (e) {
		e.preventDefault();
		let plus = $(this)
			.closest(".ins-cart-qty-wrap")
			.find('.quantity input[type="number"].qty');
		let qty = plus.val();
		plus.val(parseInt(qty) + 1);
		$('.ins-checkout-layout button[name="update_cart"]').trigger("click");
		single_step_order_review_callback();
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
			url: instantio_params.ajax_url,
			type: "POST",
			data: {
				nonce: instantio_params.instantio_ajax_nonce,
				product_id: product_id,
				variation_id: variation_id,
				action: "instantio_ajax_cart_item_remove",
			},
			beforeSend: function (response) {
				$(".loader-container").addClass("active");
			},
			complete: function (response) {
				$(".loader-container").removeClass("active");
			},
			success: function (response) {
				window.instantioAnimateFrom(animate_remove, { x: -100, duration: 200, delay: 200, reverse: true });
				single_step_order_review_callback();
				setTimeout(function () {
					$("#ins_cart_totals").html(response.data.ins_cart_count);
					$("#ins_cart_mobile_totals").html(response.data.ins_cart_count);
					$("#ins-mobile-cart-total-amount").html(response.data.cart_total);
					if (response.data.display == "ins-show") {
						// alert("show");
						$(".ins-checkout-layout .ins-content").removeClass("hide");
						$(".ins-single-layout-wrap .ins_single_layout_checkout_area").removeClass("hide");
					}
					if (response.data.hide_empty == "ins-show") {
						$(".ins-checkout-layout .ins-cart-empty").removeClass("hide");
						$(".ins-single-layout-wrap .ins_single_layout_checkout_area").addClass("hide");
					}
					$(".ins-checkout-layout .ins-content").addClass(response.data.display);
					$(".ins-checkout-layout .ins-cart-empty").addClass(response.data.hide_empty);
					$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
					$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

					ins_owl_carousel();
					// Hide toggle button if empty cart
					hide_toggle_btn();
				}, 400);

				// Progress bar hide if cart is empty 
				if (response.data.ins_cart_count === 0) {
					$(".ins-checkout-steps").addClass("hide");
				} else {
					$(".ins-checkout-steps").removeClass("hide");
				}
			},
		});
	});

	// empty cart
	$(document).on("click", ".ins-empty-cart", function (e) {
		e.preventDefault();

		$.ajax({
			url: instantio_params.ajax_url,
			type: "POST",
			data: {
				nonce: instantio_params.instantio_ajax_nonce,
				action: "instantio_ajax_empty_cart",
			},
			beforeSend: function (response) {
				$(".loader-container").addClass("active");
			},
			complete: function (response) {
				$(".loader-container").removeClass("active");
			},
			success: function (response) {
				$("#ins_cart_totals").html(response.data.ins_cart_count);
				$("#ins_cart_mobile_totals").html(response.data.ins_cart_count);
				$("#ins-mobile-cart-total-amount").html(response.data.cart_total);
				$(".ins-checkout-layout .ins-content").removeClass("ins-show");
				$(".ins-checkout-layout .ins-content").addClass("hide");
				$(".ins-single-layout-wrap .ins_single_layout_checkout_area").addClass("hide");
				$(".ins-checkout-layout .ins-cart-empty").removeClass("hide");
				$(".ins-checkout-layout .ins-cart-empty").addClass("ins-show");
				$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
				$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);
				single_step_order_review_callback();
				// Hide toggle button if empty cart
				hide_toggle_btn();
				// Progress bar hide if cart is empty 
				if (response.data.ins_cart_count === 0) {
					$(".ins-checkout-steps").addClass("hide");
				} else {
					$(".ins-checkout-steps").removeClass("hide");
				}
			},
		});
	});

	// Update Cart 
	$(document).on(
		"click",
		'.ins-checkout-layout button[name="update_cart"], .ins-checkout-layout button[name="apply_coupon"]',
		function (e) {
			$('.ins-cart-inner.shipping input').each(function () {
				var value = $(this).val();
				if (value != '') {
					$(this).closest('p.form-row').find('label').addClass('active');
				} else {
					$(this).closest('p.form-row').find('label').removeClass('active');
				}
			});

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
				url: instantio_params.ajax_url,
				type: "post",
				data: {
					nonce: instantio_params.instantio_ajax_nonce,
					action: "instantio_ajax_update_cart",
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
					// console.log(response);
					// console.log(response.data);
					$("#ins_cart_totals").html(response.data.ins_cart_count);
					$("#ins_cart_mobile_totals").html(response.data.ins_cart_count);
					$("#ins-mobile-cart-total-amount").html(response.data.cart_total);

					// Progress bar hide if cart is empty 
					if (response.data.ins_cart_count === 0) {
						$(".ins-checkout-steps").addClass("hide");
					} else {
						$(".ins-checkout-steps").removeClass("hide");
					}

					if (response.data.display == "ins-show") {
						$(".ins-checkout-layout .ins-content").removeClass("hide");
						single_step_order_review_callback();
						$(".ins-single-layout-wrap .ins_single_layout_checkout_area").removeClass("hide");
					}
					if (response.data.hide_empty == "ins-show") {
						$(".ins-checkout-layout .ins-cart-empty").removeClass("hide");
						$(".ins-single-layout-wrap .ins_single_layout_checkout_area").addClass("hide");
					}
					$(".ins-checkout-layout .ins-content").addClass(response.data.display);
					$(".ins-checkout-layout .ins-cart-empty").addClass(response.data.hide_empty);
					$(".ins-checkout-layout .ins-cart-inner.step-1").html("");
					$(".ins-checkout-layout .ins-cart-inner.step-1").append(response.data.data);

					$(".ins-checkout-layout .ins-checkout-shipping .ins-cart-content-wrap").html("");
					$(".ins-checkout-layout .ins-checkout-shipping .ins-cart-content-wrap").append(response.data.ins_shipping_additional);

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
				url: instantio_params.ajax_url,
				type: "post",
				data: {
					nonce: instantio_params.instantio_ajax_nonce,
					coupon: coupon,
					action: "instantio_ajax_remove_coupon",
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

	// Payment Method Change
	$(document).on("click", '.ins-cart-collaterals #shipping_method input', function (e) {

		var shipping_methods = {};

		// eslint-disable-next-line max-len
		$('select.shipping_method, :input[name^=shipping_method][type=radio]:checked, :input[name^=shipping_method][type=hidden]').each(function () {
			shipping_methods[$(this).data('index')] = $(this).val();
		});

		block($('div.cart_totals'));

		var data = {
			security: instantio_params.update_shipping_method_nonce,
			shipping_method: shipping_methods
		};

		// AJAX call to the WooCommerce update_shipping_method action
		$.ajax({
			type: 'post',
			url: get_url('update_shipping_method'),
			data: data,
			dataType: 'html',
			success: function (response) {
				update_cart_totals_div(response);
			},
			complete: function (res) {
				unblock($('div.cart_totals'));
				// $(document.body).trigger('updated_shipping_method');
				$('.ins-cart-collaterals .cart_totals .woocommerce-shipping-totals .woocommerce-shipping-destination').css({
					display: 'none'
				});

				$('.ins-cart-collaterals .cart_totals .woocommerce-shipping-totals .woocommerce-shipping-calculator').css({
					display: 'none'
				});
			}
		});

	});

	// Up sell Carousel
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

	// toggle button Animation
	function ins_cart_icon_animation() {
		$(".ins-toggle-btn").addClass("ins-icon-animation-one");
		setTimeout(function () {
			$(".ins-toggle-btn").removeClass("ins-icon-animation-one");
		}, 1000);
	}

	// Cart entrance animation.
	function ins_cart_animation($step = "cart") {
		if ($step == "cart" && $('.ins_animate_one').length > 0) {
			window.instantioAnimateFrom(".ins_animate_one .ins-checkout-header", { y: -100, delay: 200 });
			window.instantioAnimateFrom(".ins_animate_one .ins-checkout-steps", { x: -100, delay: 200 });
			window.instantioAnimateFrom(".ins_animate_one .step-1 .ins-cart-content-wrap, .step-1 .ins-up-sells", { y: -100, delay: 400 });
			window.instantioAnimateFrom(".ins_animate_one .step-1 .ins-cart-footer-content, .step-1 .ins-cart-btns", { x: 100, delay: 600 });
		}
	}
})(jQuery);

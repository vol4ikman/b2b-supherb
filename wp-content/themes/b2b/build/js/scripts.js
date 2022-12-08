// Close minicart sidebar
jQuery(document).on("click", ".minicart-close button", function (e) {
	e.preventDefault();
	jQuery("body").removeClass("minicart-opened");
});

// User account mobile - contacts edit popup
jQuery(document).on("click", ".three-dots", function (e) {
	e.preventDefault();
	jQuery(this)
		.parents(".action-buttons-trigger")
		.find(".actions-popup")
		.toggleClass("active");
});

jQuery(document).on("click", ".toggle-mobile-menu", function (e) {
	e.preventDefault();
	jQuery("body").addClass("active-mobile-menu");
});
jQuery(document).on("click", ".close-mobile-menu", function (e) {
	e.preventDefault();
	jQuery("body").removeClass("active-mobile-menu");
});

jQuery(document).on("click", ".trigger-mobile-filters", function (e) {
	e.preventDefault();
	jQuery("body").addClass("category-filters-opened");
});
// Close mobile menu filters
jQuery(document).on("click", ".close-mobile-filter", function (e) {
	e.preventDefault();
	jQuery("body").removeClass("category-filters-opened");
});

jQuery(document).on("click", ".trigger-order-row", function (e) {
	e.preventDefault();
	var this_button = jQuery(this);
	var this_visible = jQuery(this).parents(".visible-row");

	this_button.toggleClass("active");
	this_visible.next("tr.hidden-row").toggleClass("active");
});

jQuery(".single_variation_wrap").on(
	"show_variation",
	function (event, variation) {
		if (jQuery("body").hasClass("post-type-archive-product")) {
			console.log(variation);
			var variation_price_html = jQuery(event.currentTarget)
				.find(".woocommerce-variation-price")
				.html();
			jQuery(event.currentTarget)
				.parents(".product-block-item")
				.find(".product-price-wrapper")
				.html(variation_price_html);
			jQuery(event.currentTarget)
				.parents(".product-block-item")
				.find(".product-price-wrapper")
				.fadeTo(100, 1);

			// update variation id on add to favorite product button
			if (variation.variation_id) {
				jQuery(event.currentTarget)
					.parents(".product-block-item")
					.find(".add-to-my-products")
					.attr("data-item-id", variation.variation_id);
			}
		}

		if (jQuery("body").hasClass("single-product")) {
			console.log("single product variation show");
			console.log(variation);
			var attribute_name = jQuery("table.variations select").attr(
				"data-attribute_name"
			);
			var price_html = variation.price_html;
			if (price_html) {
				jQuery(".b2b-single-product-price").html(price_html);
			} else {
				jQuery(".b2b-single-product-price").html("");
			}
		}

		if (jQuery("body").hasClass("quick-view-opened")) {
			var attribute_name = jQuery(
				".quick-view-product-inner table.variations select"
			).attr("data-attribute_name");
			var price_html = variation.price_html;
			console.log("after quick view opened");
			console.log(attribute_name);
			if (price_html) {
				jQuery(
					".quick-view-product-inner .b2b-single-product-price"
				).html(price_html);
			} else {
				jQuery(
					".quick-view-product-inner .b2b-single-product-price"
				).html("");
			}
		}
	}
);

jQuery("form.variations_form").on("show_variation", function (event, data) {
	//console.log( data.variation_id );      // The variation Id  <===  <===
	//
	// console.log( data.attributes );        // The variation attributes
	// console.log( data.availability_html ); // The formatted stock status
	// console.log( data.dimensions );        // The dimensions data
	// console.log( data.dimensions_html );   // The formatted dimensions
	// console.log( data.display_price );     // The raw price (float)
	// console.log( data.display_regular_price ); // The raw regular price (float)
	// console.log( data.image );             // The image data
	// console.log( data.image_id );          // The image ID (int)
	// console.log( data.is_downloadable );   // Is downloadable (boolean)
	// console.log( data.is_in_stock );       // Is in stock (boolean)
	// console.log( data.is_purchasable );    // Is purchasable (boolean)
	// console.log( data.is_sold_individually ); // Is sold individually (yes or no)
	// console.log( data.is_virtual );        // Is vistual (boolean)
	// console.log( data.max_qty );           // Max quantity (int)
	// console.log( data.min_qty );           // Min quantity (int)
	// console.log( data.price_html );        // Formatted displayed price
	// console.log( data.sku );               // The variation SKU
	// console.log( data.variation_description ); // The variation description
	// console.log( data.variation_is_active );   // Is variation active (boolean)
	// console.log( data.variation_is_visible );  // Is variation visible (boolean)
	// console.log( data.weight );            // The weight (float)
	// console.log( data.weight_html );       // The formatted weight
});

jQuery(document).on(
	"change",
	".quick-view-product-inner .variations_form table.variations select",
	function () {
		console.log("quick-view variation changed");
		var variations = JSON.parse(
			jQuery(this).parents("form.cart").attr("data-product_variations")
		);
		var attr_name = jQuery(
			".quick-view-product-inner .variations_form table.variations select"
		).attr("data-attribute_name");
		var price_html = "";
		if (variations) {
			console.log(attr_name);
			jQuery.each(variations, function (key, item) {
				console.log(item);
				if (
					item.attributes[attr_name] ===
					jQuery(
						".quick-view-product-inner .variations_form table.variations select"
					).val()
				) {
					price_html = item.price_html;
				}
			});
		}

		if (price_html) {
			jQuery(".quick-view-product-inner .b2b-single-product-price").html(
				price_html
			);
		} else {
			jQuery(".quick-view-product-inner .b2b-single-product-price").html(
				""
			);
		}
	}
);

jQuery(document).on("click", ".quantity-trigger", function (e) {
	e.preventDefault();
	console.log("quantity-trigger clicked");
	var wc_quantity = jQuery(this)
		.parents(".quantity")
		.find("input.input-text.qty");
	var b2b_quantity = jQuery(this)
		.parents(".b2b-quantity-inner")
		.find('input[name="b2b-quantity"]');

	var current_quantity = parseInt(b2b_quantity.val());
	if (jQuery(this).hasClass("plus")) {
		b2b_quantity.val(current_quantity + 1);
		wc_quantity.val(current_quantity + 1);
	}
	if (jQuery(this).hasClass("minus")) {
		if (parseInt(b2b_quantity.val()) - 1 >= 1) {
			b2b_quantity.val(current_quantity - 1);
			wc_quantity.val(current_quantity - 1);
		}
	}
	wc_quantity.trigger("change");
	if (jQuery("body").hasClass("page-template-tpl-cart")) {
		jQuery('button[name="update_cart"]').click();
	}
});

jQuery(document).on("change", 'input[name="b2b-quantity"]', function () {
	var this_quantity = jQuery(this).val();
	var parent_wrap = jQuery(this).parents("div.quantity");
	parent_wrap.find("input.input-text.qty.text").val(this_quantity);
});

// Open floating form
jQuery(document).on("click", ".floating-contact-form-trigger", function (e) {
	e.preventDefault();
	jQuery(".floating-contact-form-shortcode").addClass("active");
});
// Close floating form
jQuery(document).on("click", ".close-floating-form", function (e) {
	e.preventDefault();
	jQuery(".floating-contact-form-shortcode").removeClass("active");
});

// Contrast A11Y
jQuery(document).on("click", ".account-button.contrast", function (e) {
	e.preventDefault();
	jQuery("body").toggleClass("a11y-contrast");
});

jQuery(document).on("change", 'input[name="select_all"]', function () {
	if (jQuery(this).is(":checked")) {
		jQuery('input[name="pr_to_add_to_cart[]"]').each(function () {
			jQuery(this).prop("checked", true);
			jQuery(this).trigger("change");
		});
	} else {
		jQuery('input[name="pr_to_add_to_cart[]"]').each(function () {
			jQuery(this).prop("checked", false);
			jQuery(this).trigger("change");
		});
	}
});

// Coupon input.
jQuery(document).on("change", 'input[name="b2b-coupon-code"]', function (e) {
	e.preventDefault();
	jQuery('input[name="coupon_code"]').val(jQuery(this).val());
});
// Coupon submit.
jQuery(document).on("click", ".checkout-coupon-submit button", function (e) {
	e.preventDefault();
	if (jQuery('input[name="b2b-coupon-code"]').val()) {
		jQuery("form.checkout_coupon").trigger("submit");
		setTimeout(function () {
			if (jQuery(".woocommerce-error li").length) {
				jQuery(".checkout-coupon-message").html(
					'<span class="message">' +
						jQuery(".woocommerce-error li").html() +
						"</span>"
				);
			}
		}, 800);
	} else {
		alert("יש להזין קוד קופון");
	}
});

jQuery(document).on("change", 'input[name="pr_to_add_to_cart[]"]', function () {
	var products_to_add = [];
	jQuery('input[name="pr_to_add_to_cart[]"]').each(function () {
		if (jQuery(this).is(":checked")) {
			products_to_add.push(jQuery(this).val());
		}
	});
	console.log(products_to_add);
	if (products_to_add.length) {
		jQuery(".add-to-cart-selected").addClass("active");
	} else {
		jQuery(".add-to-cart-selected").removeClass("active");
	}
});

// Copy URL.
jQuery(document).on("click", ".copy-url", function (e) {
	e.preventDefault();
	console.log("copy-url clicked");
	var _this = jQuery(this);
	var url = document.location.href;
	var message = "";
	navigator.clipboard.writeText(url).then(
		function () {
			console.log("Copied!");
			message = "הקישור הועתק";
			alert(message);
			// if( ! jQuery('.copy-message').length ){
			// 	_this.append('<span class="copy-message">'+message+'</span>');
			// 	setTimeout( function(){
			// 		jQuery('.copy-message').fadeOut( 250, function(){
			// 			jQuery(this).remove();
			// 		});
			// 	}, 5000);
			// }
		},
		function () {
			console.log("Copy error");
		}
	);
});

jQuery(document).ready(function () {
	jQuery(".replacement-products").on("click", function (e) {
		e.preventDefault();
		let scroll_to_target = jQuery(".single-product .related.products");
		if (scroll_to_target.length) {
			jQuery("html,body").animate(
				{
					scrollTop: scroll_to_target.offset().top,
				},
				"slow"
			);
		}
	});

	if (jQuery(window).width() < 961) {
		jQuery(".MyAccount-mobile-navigation select").on(
			"change",
			function (e) {
				var target_url = jQuery(this).val();
				if (target_url) {
					window.location.href = target_url;
				}
			}
		);
	}

	if (jQuery("body").hasClass("single-product")) {
		if (jQuery(".product").hasClass("product-type-simple")) {
			console.log("is simple product");
			var price_html = jQuery("p.price").html();
			jQuery(".b2b-single-product-price").html(price_html);
		}
	}

	load_category_price_range();

	// Open minicart sidebar
	jQuery(".cart-button").on("click", function (e) {
		e.preventDefault();
		jQuery("body").addClass("minicart-opened");
	});

	if (jQuery(".banners-slider-inner").length) {
		jQuery(".banners-slider-inner").on("init", function (event, slick) {
			console.log("initialized");
			console.log(slick);
			var total_dots = slick.$dots[0].childElementCount;
			jQuery(slick.$slider[0]).addClass("total-" + total_dots);
			//total_slides = slick
		});
		jQuery(".banners-slider-inner").slick({
			rtl: true,
			infinite: false,
			slidesToShow: 1,
			slidesToScroll: 1,
			dots: true,
		});
	}

	if (jQuery(".solgar-supherb-tabs").length) {
		init_solgar_supherb_tabs();
	}

	jQuery(".inline-popup").magnificPopup({
		type: "inline",
		preloader: false,
	});

	// accessible contact form 7 focus validation
	// list of contact form 7 DOM events: https://contactform7.com/dom-events/
	var cf7_form = jQuery(".wpcf7");
	cf7_form.on("wpcf7invalid ", function (event) {
		jQuery(this).find(".wpcf7-not-valid").first().focus();
	});

	// accessibility handle browser zoom level
	jQuery(window).resize(function () {
		var browserZoomLevel = Math.round(window.devicePixelRatio * 100);
		//console.log(browserZoomLevel);
		if (browserZoomLevel < 401) {
			jQuery("body").removeClass(
				"zoom-level-90 zoom-level-100 zoom-level-110 zoom-level-120 zoom-level-130 zoom-level-140 zoom-level-150 zoom-level-160 zoom-level-170 zoom-level-180 zoom-level-190 zoom-level-200 zoom-level-210 zoom-level-220 zoom-level-230 zoom-level-240 zoom-level-250 zoom-level-260 zoom-level-270 zoom-level-280 zoom-level-290 zoom-level-300 zoom-level-310 zoom-level-320 zoom-level-330 zoom-level-340 zoom-level-350 zoom-level-360 zoom-level-370 zoom-level-380 zoom-level-390 zoom-level-400"
			);
			jQuery("body").addClass("zoom-level-" + browserZoomLevel);
		}
	});

	jQuery(".trigger-user-menu").click(function (e) {
		e.preventDefault();
		jQuery(this).parents("li").find("ul.user-submenu").slideToggle();
	});

	jQuery('input[type="tel"]').each(function () {
		jQuery(this).on("change", function () {
			var input_val = jQuery(this).val();
			if (!validatePhoneNumber(input_val)) {
				jQuery(this).addClass("error");
				if (!jQuery(this).parents("label .error-desc").length) {
					jQuery(this)
						.parents("label")
						.append(
							'<span class="error-desc">' +
								jQuery(this).attr("placeholder") +
								" לא תקין</span>"
						);
				}
			} else {
				jQuery(this).removeClass("error");
				jQuery(this).parents("label").find(".error-desc").remove();
			}
		});
	});

	jQuery('.login-sidebar input[type="email"]').each(function () {
		jQuery(this).on("change", function () {
			console.log("change email");
			var input_val = jQuery(this).val();
			if (!validateEmail(input_val)) {
				jQuery(this).addClass("error");
				if (!jQuery(this).parents("label .error-desc").length) {
					jQuery(this)
						.parents("label")
						.append(
							'<span class="error-desc">' +
								jQuery(this).attr("placeholder") +
								" לא תקין</span>"
						);
				}
			} else {
				jQuery(this).removeClass("error");
				jQuery(this).parents("label").find(".error-desc").remove();
			}
		});
	});
});

jQuery(window).on("load", function () {
	googleTranslateElementInit();
});

function load_category_price_range() {
	if (jQuery("body").hasClass("tax-product_cat")) {
		var handle = jQuery("#custom-handle .custom-handle-inner span");
		var max_price = jQuery('input[name="max_price"]');
		jQuery("#slider-range-max").slider({
			range: "max",
			min: parseInt(jQuery("#slider-range-max").attr("data-min")),
			max: parseInt(jQuery("#slider-range-max").attr("data-max")),
			value: parseInt(jQuery("#slider-range-max").attr("data-max")),
			create: function () {
				handle.text(jQuery(this).slider("value"));
				max_price.val(jQuery(this).slider("value"));
			},
			slide: function (event, ui) {
				handle.text(ui.value);
				max_price.val(ui.value);
			},
			stop: function (event, ui) {
				console.log("slide stop");
				var form = jQuery(".product-sidebar-filter").serialize();
				jQuery.blockUI({
					overlayCSS: { backgroundColor: "#A1D45D", cursor: "wait" },
					message: "המתן...",
					css: {
						padding: 20,
						margin: 0,
						width: "30%",
						top: "40%",
						left: "35%",
						textAlign: "center",
						color: "#35563C",
						border: "3px solid 35563C",
						backgroundColor: "#fff",
						cursor: "wait",
					},
				});
				filter_sidebar_products(form);
			},
		});
		jQuery("#amount").val(jQuery("#slider-range-max").slider("value"));
	}
}

function init_solgar_supherb_tabs() {
	jQuery(".solgar-supherb-tabs a").on("click", function (e) {
		e.preventDefault();
		var target = jQuery(this).attr("href");

		jQuery(".tab-inner").removeClass("active");
		jQuery(".solgar-supherb-tabs a").removeClass("active");

		jQuery(this).addClass("active");
		jQuery(".tabs-contents " + target).addClass("active");
	});
}

function validateEmail(email) {
	var re = /\S+@\S+\.\S+/;
	return re.test(email);
}

function validatePhoneNumber(input_str) {
	var re = /^\(?(\d{3})\)?[- ]?(\d{3})[- ]?(\d{4})$/;
	return re.test(input_str);
}

function googleTranslateElementInit() {
	new google.translate.TranslateElement(
		{
			pageLanguage: "he",
		},
		"google_translate_element"
	);

	jQuery(".language-switcher-inner").on("click", function (e) {
		e.preventDefault();
		jQuery(".language-switcher-select").toggleClass("active");
	});
	jQuery(".language-selector > div").on("click", function (e) {
		e.preventDefault();
		var chosen_lang = jQuery(this).attr("data-value");
		jQuery(".goog-te-combo").val(chosen_lang).trigger("change");
		triggerHtmlEvent(jQuery(".goog-te-combo")[0], "change");
		jQuery(".language-switcher-select").removeClass("active");
		if (chosen_lang === "iw") {
			chosen_lang = "HE";
		}
		jQuery(".language-switcher-inner .current-lang").html(
			chosen_lang.toUpperCase()
		);
	});
}

function triggerHtmlEvent(element, eventName) {
	var event;
	if (document.createEvent) {
		event = document.createEvent("HTMLEvents");
		event.initEvent(eventName, true, true);
		element.dispatchEvent(event);
	} else {
		event = document.createEventObject();
		event.eventType = eventName;
		element.fireEvent("on" + event.eventType, event);
	}
}

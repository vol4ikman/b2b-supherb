jQuery(document).ready(function(){
    console.log('ajax');
    jQuery('.minicart-clear button').click(function(e){
        e.preventDefault();
        if( confirm("לנקות עגלת קניות?") == true ) {
            console.log('clear');
            b2b_clear_cart();
        } else {
            console.log('cancel');
        }
    });
});

jQuery(document).on('change', '.sidebar-widget-item input[type="checkbox"]', function(e){
    e.preventDefault();
    jQuery('form.product-sidebar-filter').trigger('submit');
})

jQuery(document).on('click', '.close-quick-view', function(e){
    e.preventDefault();
    jQuery('.quick-view-popup-holder').removeClass('active').html('');
});

jQuery('select.category-order-by').on('change', function(){
    jQuery('input[name="category-order-by"]').val( jQuery('select.category-order-by').val());
    var form = jQuery('form.product-sidebar-filter').serialize();
    jQuery.blockUI({
        overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
        message: 'המתן...',
        css: {
            padding:        20,
            margin:         0,
            width:          '30%',
            top:            '40%',
            left:           '35%',
            textAlign:      'center',
            color:          '#35563C',
            border:         '3px solid 35563C',
            backgroundColor:'#fff',
            cursor:         'wait'
        },
    });
    filter_sidebar_products(form);
});

jQuery(document).on('submit', 'form.product-sidebar-filter', function(e){
    e.preventDefault();
    jQuery('input[name="category-order-by"]').val( jQuery('select.category-order-by').val());
    var form = jQuery(this).serialize();
    jQuery.blockUI({
        overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
        message: 'המתן...',
        css: {
            padding:        20,
            margin:         0,
            width:          '30%',
            top:            '40%',
            left:           '35%',
            textAlign:      'center',
            color:          '#35563C',
            border:         '3px solid 35563C',
            backgroundColor:'#fff',
            cursor:         'wait'
        },
    });
    filter_sidebar_products(form);
});

jQuery(document).on('click', '.add-to-my-products', function(e){
    e.preventDefault();
    var item_id = jQuery(this).attr('data-item-id');
    if( typeof item_id !='undefined' && item_id ){
        console.log(item_id);
        add_product_to_user_favorites(item_id);
    } else {
        alert('יש לבחור סוג מוצר.');
    }
});

jQuery('.quick-view').on('click', function(e){
    e.preventDefault();
    var item_id = jQuery(this).attr('data-item');
    load_product_quick_view(item_id);
});

jQuery('.remove-product').on('click', function(e){
    e.preventDefault();
    var item_id = jQuery(this).attr('data-id');
    if( typeof item_id !='undefined' && item_id ){
        jQuery.blockUI({
            overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
            message: 'המתן...',
            css: {
                padding:        20,
                margin:         0,
                width:          '30%',
                top:            '40%',
                left:           '35%',
                textAlign:      'center',
                color:          '#35563C',
                border:         '3px solid 35563C',
                backgroundColor:'#fff',
                cursor:         'wait'
            },
        });
        remove_product_from_favorites(item_id);
    } else {
        alert('יש לבחור סוג מוצר.');
    }
});

jQuery('#update_profile_request').on('submit', function(e){
    e.preventDefault();
    jQuery(this).find('button[type="submit"]').prop('disabled', true );
    var form = jQuery(this).serialize();
    update_profile_request(form);
});

jQuery('#create_new_contact_form').on('submit', function(e){
    e.preventDefault();
    jQuery(this).find('button[type="submit"]').prop('disabled', true );
    var form = jQuery(this).serialize();
    create_new_contact(form);
});

jQuery(document).on('click', '.reg-step-1.active button.next-step', function(e){
    e.preventDefault();
    var errors = [];
    var inputs = jQuery('.reg-step-1.active input, .reg-step-1.active select');

    inputs.each( function(key, item){
        var input_value = jQuery(item).val();
        if( ! input_value ) {
            jQuery(item).addClass('error');
            errors.push(true);
        }
    });

    console.log(errors);

    if( ! errors.length ) {
        jQuery('.reg-step-1').removeClass('active');
        jQuery('.reg-step-2').addClass('active');
    }
});

jQuery(document).on('click', '.reg-step-2.active button[type="submit"]', function(e){
    e.preventDefault();
    var errors = [];
    var inputs = jQuery('.reg-step-2.active input');

    inputs.each( function(key, item){
        var input_value = jQuery(item).val();
        if( ! input_value ) {
            jQuery(item).addClass('error');
            errors.push(true);
        }
    });

    console.log(errors);

    if( ! errors.length ) {
        var form = jQuery('.reg-form').serialize();
        jQuery.blockUI({
            overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
            message: 'שולח...',
            css: {
                padding:        20,
                margin:         0,
                width:          '30%',
                top:            '40%',
                left:           '35%',
                textAlign:      'center',
                color:          '#35563C',
                border:         '3px solid 35563C',
                backgroundColor:'#fff',
                cursor:         'wait'
            },
        });
        submit_registration_form( form );

    }
});

function b2b_clear_cart(){
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action  : "b2b_clear_cart",
        },
        success: function(response) {
            jQuery( document.body ).trigger( 'wc_fragments_refreshed' );
            jQuery('.b2b-minicart-wrapper').html(response.html);
        }
    });
}

function filter_sidebar_products(form){
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action  : "filter_sidebar_products",
            form : form
        },
        success: function(response) {
            console.log(response);
            jQuery('.section-category-products').html(response.html);
            jQuery.unblockUI();
        }
    });
}

function remove_product_from_favorites(item_id){
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action  : "remove_product_from_favorites",
            item_id : item_id
        },
        success: function(response) {
            if( ! response.error ){
                if( response.item_id ) {
                    jQuery('.user-products-table tr[data-pid="'+response.item_id+'"]').remove();
                }
            }
            jQuery.unblockUI();
        }
    });
}

function add_product_to_user_favorites(item_id){
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action  : "add_product_to_user_favorites",
            item_id : item_id
        },
        success: function(response) {
            console.log(response);
            if( ! response.error ) {
                alert(response.message);
            }
        }
    });
}

function create_new_contact( form ) {
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action: "create_new_contact",
            form : form
        },
        success: function(response) {
            if( ! response.error ) {
                jQuery('#create-new-contact').find('button[type="submit"]').prop('disabled', false );
                jQuery('#create-new-contact').find('.ajax-response').html('<span class="success">'+response.message+'</span>');
                jQuery('#create-new-contact input').each( function(){
                    jQuery(this).val('');
                });
                jQuery('.contact-list-table tbody').html(response.html);
            } else {
                jQuery('#create-new-contact').find('.ajax-response').html('<span class="error">שגיאה בשליחת טופס. יש לסנות עוד פעם.</span>');
            }
        }
    });
}

function update_profile_request(form){
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action: "update_profile_request",
            form : form
        },
        success: function(response) {
            if( ! response.error ) {
                jQuery('#update_profile_request').find('button[type="submit"]').prop('disabled', false );
                jQuery('#update_profile_request').find('.ajax-response').html('<span class="success">'+response.message+'</span>');
                jQuery('#update_profile_request input').each( function(){
                    jQuery(this).val('');
                })
            } else {
                jQuery('#').find('.ajax-response').html('<span class="error">שגיאה בשליחת טופס. יש לסנות עוד פעם.</span>');
            }
        }
    });
}

function submit_registration_form( form ) {
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action: "submit_registration_form",
            form : form
        },
        success: function(response) {
            console.log(response);
            if( ! response.error ) {
                jQuery('.reg-step-2').removeClass('active');
                jQuery('.reg-step-3').addClass('active');
                jQuery('.form-wrapper').addClass('step-3');
            } else {
                jQuery('.reg-form .ajax-response').html('<span class="error">'+response.message+'</span>');
            }
            // unblock
            jQuery.unblockUI();
        }
    })
}

jQuery('form.step1-form').on('submit', function(e){
    e.preventDefault();
    var form = jQuery(this).serialize();
    jQuery.blockUI({
        overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
        message: 'שולח...',
        css: {
            padding:        20,
            margin:         0,
            width:          '30%',
            top:            '40%',
            left:           '35%',
            textAlign:      'center',
            color:          '#35563C',
            border:         '3px solid 35563C',
            backgroundColor:'#fff',
            cursor:         'wait'
        },
    });
    submit_step1_form(form);
});

jQuery(document).on('submit', 'form.step2-form', function(e){
    e.preventDefault();
    var form = jQuery(this).serialize();
    jQuery.blockUI({
        overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
        message: 'מתחבר...',
        css: {
            padding:        20,
            margin:         0,
            width:          '30%',
            top:            '40%',
            left:           '35%',
            textAlign:      'center',
            color:          '#35563C',
            border:         '3px solid 35563C',
            backgroundColor:'#fff',
            cursor:         'wait'
        },
    });
    submit_step2_form(form);
});

function submit_step1_form( form ) {
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action: "submit_step1_form",
            form : form
        },
        success: function(response) {
            console.log(response);

            if( response.error ) {
                jQuery('#login-screen-wrapper .ajax-response').html('<span class="error">'+response.message+'</span>');
            } else {
                jQuery('#login-screen-wrapper').html(response.step2);
            }
            // unblock
            jQuery.unblockUI();
        }
    })
}

function submit_step2_form( form ) {
    jQuery.ajax({
        type : "post",
        dataType : "json",
        url : site_settings.ajaxurl,
        data : {
            action: "submit_step2_form",
            form : form
        },
        success: function(response) {
            console.log(response);

            if( response.error ) {
                jQuery('#login-screen-wrapper .ajax-response').html('<span class="error">'+response.message+'</span>');
            } else {
                // on success - redirect to shop page
                window.location.href = response.redirect;
            }
            // unblock
            jQuery.unblockUI();
        }
    });
}

function load_product_quick_view(item_id){

    jQuery.blockUI({
        overlayCSS: { backgroundColor: '#A1D45D', cursor: 'wait' },
        message: 'טוען...',
        css: {
            padding:        20,
            margin:         0,
            width:          '30%',
            top:            '40%',
            left:           '35%',
            textAlign:      'center',
            color:          '#35563C',
            border:         '3px solid 35563C',
            backgroundColor:'#fff',
            cursor:         'wait'
        },
    });

    jQuery.ajax({
        type     : "post",
        dataType : "json",
        url      : site_settings.ajaxurl,
        data : {
            action  : "load_product_quick_view",
            item_id : item_id
        },
        success: function(response) {
            if( response.html ) {
                jQuery('body').addClass('quick-view-opened');
                jQuery('.quick-view-popup-holder').html(response.html);

                jQuery('<a href="'+response.product_url+'" class="quick-go-to-product">לעמוד המוצר</a>').insertAfter('.quick-view-product-inner .variations_form');
                jQuery('.quick-view-popup-holder').addClass('active');

                var variations = JSON.parse( jQuery('.quick-view-product-inner div.product form.cart').attr('data-product_variations') );
                var attr_name = jQuery('.quick-view-product-inner .variations_form table.variations select').attr('data-attribute_name');
                var price_html = '';
                if( variations ) {
                    console.log(attr_name);
                    jQuery.each( variations, function(key, item){
                        console.log(item);
                        if( item.attributes[attr_name] === jQuery('.quick-view-product-inner .variations_form table.variations select').val() ) {
                            price_html = item.price_html;
                        }
                    });
                }

                if( price_html ){
        			jQuery('.quick-view-product-inner .b2b-single-product-price').html(price_html);
        		} else {
        			jQuery('.quick-view-product-inner .b2b-single-product-price').html('');
        		}

                if( response.product_share ){
                    jQuery('.quick-view-product-inner .user-favorites-product').append(response.product_share);
                }

            }

            // unblock
            jQuery.unblockUI();
        }
    });
}

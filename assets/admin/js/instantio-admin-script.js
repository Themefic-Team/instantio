(function ($) {

    jQuery(document).ready(function () {

        $(".ins-scroll-to").parent().css({ "padding": "0", "margin": "0", "visibility": "hidden" });

        $(".admin-scroll").click(function (e) {
            e.preventDefault();
            var secID = $(this).attr("data-id");
            console.log(secID);

            $('html, body').animate({
                scrollTop: $('#' + secID).offset().top
            }, 1000);
        });

        /**
         * Ajax install WooCommerce
         * 
         * @since 1.0
         */
        // $(document).on('click', '.tf-install', function (e) {
        //     e.preventDefault();

        //     var current = $(this);
        //     var plugin_slug = current.attr("data-plugin-slug");

        //     current.addClass('updating-message').text('Installing...');
        //     console.log('click');

        //     var data = {
        //         action: 'ins_ajax_install_plugin',
        //         _ajax_nonce: ins_admin_params.ins_nonce,
        //         slug: plugin_slug,
        //     };

        //     console.log(data);

        //     jQuery.post(ins_admin_params.ajax_url, data, function (response) {
        //         console.log(response);
        //         //console.log(response.data.activateUrl);
        //         current.removeClass('updating-message');
        //         current.addClass('updated-message').text('Installed!');
        //         current.attr("href", response.data.activateUrl);
        //     })
        //         .fail(function () {
        //             current.removeClass('updating-message').text('Failed!');
        //         })
        //         .always(function () {
        //             current.removeClass('install-now updated-message').addClass('activate-now button-primary').text('Activating...');
        //             current.unbind(e);
        //             current[0].click();
        //         });
        // });

        /**
         * Ajax install WooCommerce
         * 
         * @since 3.0
         */
        $(document).on('click', '.ins_wooinstall', function (e) {
            e.preventDefault();

            var current = $(this);
            var plugin_slug = current.attr("data-plugin-slug");

            current.addClass('updating-message').text('Installing...');

            var data = {
                action: 'ins_ajax_install_woocommerce',
                _ajax_nonce: ins_admin_params.ins_nonce,
                slug: plugin_slug,
            };

            jQuery.post(ins_admin_params.ajax_url, data, function (response) {
                // console.log(response);
                // console.log(response.data.activateUrl);
                current.removeClass('updating-message');
                current.addClass('updated-message').text('Installed!');
                current.attr("href", response.data.activateUrl);
            })
                .fail(function () {
                    current.removeClass('updating-message').text('Failed!');
                })
                .always(function () {
                    current.removeClass('install-now updated-message').addClass('activate-now button-primary').text('Activating...');
                    current.unbind(e);
                    current[0].click();
                });
        });

        /**
         * Pro Feature button link
         */
        $(document).on('click', '.ins-pro', function (e) {
            window.open('https://themefic.com/instantio/go/upgrade');
        });

        $(document).on('click', '.ins-csf-pro', function (e) {
            window.open('https://themefic.com/instantio/go/upgrade');
        });

    });

})(jQuery);

jQuery(document).ready(function($) {
    $('.plugin-button').not('.pro').on('click', function(e) {
        e.preventDefault();

        let button = $(this);
        let action = button.data('action');
        let pluginSlug = button.data('plugin');
        let pluginFileName = button.data('plugin_filename');

        if (!action || !pluginSlug) return;

        let loader = button.find('.loader');
        let originalText = button.clone().children().remove().end().text().trim();

        if (action === 'install') {
            button.contents().first().replaceWith('Installing..');
        } else if (action === 'activate') {
            button.contents().first().replaceWith('Activating..');
        }

        button.addClass('loading').prop('disabled', true);
        loader.show();

        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'ins_themefic_manage_plugin',
                security: ins_admin_data.themefic_nonce,
                plugin_slug: pluginSlug,
                plugin_filename: pluginFileName,
                plugin_action: action
            },
            success: function(response) {
                button.removeClass('loading').prop('disabled', false);
                loader.hide();

                if (response.success) {
                    if (action === 'install') {
                        button.contents().first().replaceWith('Activate');
                        button.data('action', 'activate').removeClass('install').addClass('activate');
                    } else if (action === 'activate') {
                        button.replaceWith('<span class="plugin-button plugin-status active">Activated</span>');
                    }
                } else {
                    button.contents().first().replaceWith(originalText);
                    alert('Error: ' + response.data);
                }
            },
            error: function() {
                button.contents().first().replaceWith(originalText).removeClass('loading').prop('disabled', false);
                loader.hide();
                alert('An error occurred. Please try again.');
            }
        });
    });
});
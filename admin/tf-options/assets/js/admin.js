
(function ($) {

    $(document).ready(function () {



        // Create an instance of Notyf
        const notyf = new Notyf({
            ripple: true,
            dismissable: true,
            duration: 3000,
            position: {
                x: 'right',
                y: 'bottom',
            },
        });

        /**
         * Delete old review fields
         * @author kabir, fida
         */
        $(document).on('click', '.tf-del-old-review-fields', function (e) {
            e.preventDefault();
            var $this = $(this);
            var data = {
                action: 'tf_delete_old_review_fields',
                deleteAll: $(this).data('delete-all')
            };

            $.ajax({
                type: 'post',
                url: tf_admin_params.ajax_url,
                data: data,
                beforeSend: function (data) {
                    notyf.success(tf_admin_params.deleting_old_review_fields);
                },
                success: function (data) {
                    notyf.success(data.data);
                },
                error: function (data) {
                    notyf.error(data.data);
                },

            });

        });

        /**
         * Ajax install
         * 
         * @since 1.0
         */
        $(document).on('click', '.tf-install', function (e) {
            e.preventDefault();

            var current = $(this);
            var plugin_slug = current.attr("data-plugin-slug");

            current.addClass('updating-message').text(tf_admin_params.installing);

            var data = {
                action: 'tf_ajax_install_plugin',
                _ajax_nonce: tf_admin_params.tf_nonce,
                slug: plugin_slug,
            };

            jQuery.post(tf_admin_params.ajax_url, data, function (response) {
                current.removeClass('updating-message');
                current.addClass('updated-message').text(tf_admin_params.installed);
                current.attr("href", response.data.activateUrl);
            })
                .fail(function () {
                    current.removeClass('updating-message').text(tf_admin_params.install_failed);
                })
                .always(function () {
                    current.removeClass('install-now updated-message').addClass('activate-now button-primary').text(tf_admin_params.activating);
                    current.unbind(e);
                    current[0].click();
                });
        });

        /**
         * Pro Feature button link
         */
        $(document).on('click', '.tf-pro', function (e) {
            e.preventDefault();
            window.open('https://themefic.com/instantio/');
        });

        $(window).on('load', function () {
            $('.tf-field-disable').find('input, select, textarea, button, div, span').attr('disabled', 'disabled');
        });

        $(document).on('click', '.tf-field-pro', function (e) {
            e.preventDefault();
            window.open('https://themefic.com/instantio/');
        });

        //documentation link open in new tab
        $('.tf-go-docs').parent().attr('target', '_blank');

    });

})(jQuery);
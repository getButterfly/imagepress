jQuery(document).ready(function() {
    if (jQuery('.ip-color-picker')) {
        jQuery(".ip-color-picker").wpColorPicker();
    }

    if (jQuery('.ajax_trash')) {
        jQuery('.ajax_trash').click(e => {
            e.preventDefault();

            let data = {
                action: 'ajax_trash_action',
                odvm_post: jQuery(this).attr('data-post'),
            };

            jQuery.post(ajax_var.ajaxurl, data, function (response) {
                // Success (response)
            });

            fade_vote = jQuery(this).attr('data-post');
            jQuery('#notification-' + fade_vote).fadeOut('slow');
        });
    }
});

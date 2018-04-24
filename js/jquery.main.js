/**
 * ImagePress Javascript functions
 *
 * @copyright 2014-2018 Ciprian Popescu
 */

/* global jQuery, ipAjaxVar, swal, Masonry */

/*eslint quotes: ["error", "single"]*/
/*eslint-env es6*/

function addMoreFiles() {
    var newElement = jQuery('#fileuploads').clone().prop({
        class: 'ip-more'
    });
    newElement.find('input, textarea, select').val('').end().insertBefore('#endOfForm');
}



jQuery.fn.extend({
    greedyScroll: function(sensitivity) {
        return this.each(function() {
            jQuery(this).bind('mousewheel DOMMouseScroll', function(evt) {
               var delta;
               if (evt.originalEvent) {
                  delta = -evt.originalEvent.wheelDelta || evt.originalEvent.detail;
               }
               if (delta !== null) {
                  evt.preventDefault();
                  if (evt.type === 'DOMMouseScroll') {
                     delta = delta * (sensitivity ? sensitivity : 20);
                  }
                  return jQuery(this).scrollTop(delta + jQuery(this).scrollTop());
               }
            });
        });
    }
});

function bytesToSize(bytes) {
    var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'],
        i = parseInt(Math.floor(Math.log(bytes) / Math.log(1024)), 10);

    if (bytes === 0) {
        return 'n/a';
    }

    if (i === 0) {
        return bytes + ' ' + sizes[i];
    }

    return (bytes / Math.pow(1024, i)).toFixed(1) + ' ' + sizes[i];
}

jQuery(document).ready(function() {
    jQuery('#imagepress_image_file_bulk').change(function () {
        var filename = jQuery('#imagepress_image_file_bulk').val();
        jQuery('.file-upload').addClass('active');
        jQuery('#noFile').text(filename.replace("C:\\fakepath\\", ""));
    });

    jQuery('.poster-container img').click(function(){
        jQuery(this).toggleClass('ip-more-target');
    });

    /* like action */
    jQuery('body').on('click', '.imagepress-like', function(e){
        e.preventDefault();
        e.stopImmediatePropagation();
        var like = jQuery(this);
        var pid = like.data('post_id');
        like.html('<svg class="lnr lnr-heart"><use xlink:href="#lnr-heart"></use></svg> <svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg>');
        jQuery.ajax({
            type: 'post',
            url: ipAjaxVar.ajaxurl,
            data: 'action=imagepress-like&nonce=' + ipAjaxVar.nonce + '&imagepress_like=&post_id=' + pid,
            success: function(count){
                if(count.indexOf('already') !== -1) {
                    var lecount = count.replace('already', '');
                    if(lecount === '0') {
                        lecount = ipAjaxVar.likelabel;
                    }
                    like.removeClass('liked');
                    like.html('<svg class="lnr lnr-heart"><use xlink:href="#lnr-heart"></use></svg> ' + lecount);
                }
                else {
                    count = ipAjaxVar.unlikelabel;
                    like.addClass('liked');
                    like.html('<svg class="lnr lnr-heart"><use xlink:href="#lnr-heart"></use></svg> ' + count);
                }
            }
        });
        return false;
    });



    /*
     * Drag & Drop Uploader
     */
    if (jQuery('#dropContainer').length) {
        document.getElementById('dropContainer').ondragover = document.getElementById('dropContainer').ondragenter = function(evt) {
            evt.preventDefault();
        };

        document.getElementById('dropContainer').ondrop = function(evt) {
            document.getElementById('imagepress_image_file').files = evt.dataTransfer.files;
            // Display selected image
            // document.getElementById("dropContainer").innerHTML = document.getElementById('imagepress_image_file').files[0].name;

            evt.preventDefault();
        };
    }



    var fileInput = jQuery('#imagepress_image_file');
    var maxSize = fileInput.data('max-size');
    jQuery('#imagepress_image_file').change(function () {
        if (fileInput.get(0).files.length){
            var fileSize = fileInput.get(0).files[0].size; // in bytes
            if (fileSize > maxSize) {
                jQuery('#imagepress-errors').append('<p>Warning: File size is too big (' + bytesToSize(fileSize) + ')!</p>');
                jQuery('#imagepress_submit').attr('disabled', true);

                return false;
            } else {
                jQuery('#imagepress-errors').html('');
                jQuery('#imagepress_submit').removeAttr('disabled');
            }
        } else {
            return false;
        }
    });

    jQuery('#imagepress_upload_image_form').submit(function() {
        jQuery('#imagepress-errors').html('');
        jQuery('#imagepress_submit').prop('disabled', true);
        jQuery('#imagepress_submit').css('opacity', '0.5');
        jQuery('#ipload').html('<svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg> Uploading...');
    });
    /* end upload */

    /* ip_editor() related actions */
    jQuery(document).on('click', '#ip-editor-open', function(e){
        jQuery('.ip-editor').slideToggle('fast');
        e.preventDefault();
    });

    jQuery(document).on('click', '#ip-editor-delete-image', function (e) {
        var id = jQuery(this).data('image-id'),
            redirect = jQuery(this).data('redirect');

        swal({
            title: '',
            text: ipAjaxVar.swal_confirm_operation,
            showCancelButton: true,
            confirmButtonText: ipAjaxVar.swal_confirm_button,
            cancelButtonText: ipAjaxVar.swal_cancel_button,
        }).then(function(response) {
            if (response.value) {
                jQuery.ajax({
                    type: 'post',
                    url: ipAjaxVar.ajaxurl,
                    data: {
                        action: 'ip_delete_post',
                        id: id,
                    },
                    success: function(result) {
                        if (result === 'success') {
                            window.location = redirect;
                        }
                    },
                });
            }
        });

        e.preventDefault();
        return false;
    });

    jQuery(document).on('click', '.featured-post', function(e){
        if(confirm('Set this image as main image?')) {
            jQuery(this).parent().parent().css('border', '3px solid #ffffff');

            var pid = jQuery(this).data('pid');
            var id = jQuery(this).data('id');
            var nonce = jQuery(this).data('nonce');
            jQuery.ajax({
                type: 'post',
                url: ipAjaxVar.ajaxurl,
                data: {
                    action: 'ip_featured_post',
                    nonce: nonce,
                    pid: pid,
                    id: id
                },
                success: function(result) {
                    if(result == 'success') {
                        jQuery('.ip-notice').fadeIn();
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });

    // notifications
    jQuery('.notifications-container .notification-item.unread').click(function(){
        var id = jQuery(this).data('id');
        jQuery.ajax({
            type: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'notification_read',
                id: id
            }
        });
    });

    /* mark all as read */
    jQuery('.ip_notification_mark').click(function(e){
        e.preventDefault();
        var userid = jQuery(this).data('userid');
        jQuery.ajax({
            type: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'notification_read_all',
                userid: userid
            }
        });

        jQuery('.notifications-bell').html('<svg class="lnr lnr-alarm"><use xlink:href="#lnr-alarm"></use></svg><sup>0</sup>');
    });

    jQuery('.notifications-container .notifications-inner').greedyScroll(25);
    jQuery('.notifications-container').hide();
    jQuery('.notifications-bell').click(function(e){
        jQuery('.notifications-container').toggle();
        e.preventDefault();
    });
    jQuery('.notifications-container').mouseleave(function(e){
        jQuery('.notifications-container').fadeOut('fast');
        e.preventDefault();
    });










    /* profile specific functions */
    jQuery('.ip-tab .ip-tabs').addClass('active').find('> li:eq(0)').addClass('current');
    jQuery('.ip-tab .ip-tabs li a:not(.imagepress-button)').click(function(g) {
        var tab = jQuery(this).closest('.ip-tab'),
            index = jQuery(this).closest('li').index();

        tab.find('.ip-tabs > li').removeClass('current');
        jQuery(this).closest('li').addClass('current');

        tab.find('.tab_content').find('.ip-tabs-item').not('.ip-tabs-item:eq(' + index + ')').hide();
        tab.find('.tab_content').find('.ip-tabs-item:eq(' + index + ')').show();

        g.preventDefault();
    });

    jQuery('.imagepress-follow a').on('click', function(e) {
        e.preventDefault();
        var $this = jQuery(this);
        if(ipAjaxVar.logged_in != 'undefined' && ipAjaxVar.logged_in != 'true') {
            alert(ipAjaxVar.login_required);
            return;
        }

        var data = {
            action: $this.hasClass('follow') ? 'follow' : 'unfollow',
            user_id: $this.data('user-id'),
            follow_id: $this.data('follow-id'),
            nonce: ipAjaxVar.nonce
        };

        jQuery('img.pwuf-ajax').show();

        jQuery.post(ipAjaxVar.ajaxurl, data, function(response) {
            if(response == 'success') {
                jQuery('.imagepress-follow a').toggle();
            }
            else {
                alert(ipAjaxVar.processing_error);
            }

            jQuery('img.pwuf-ajax').hide();
        });
    });



    /* collections */
    jQuery(document).on('click', '.changeCollection', function(e){
        jQuery(this).parent().parent().next('.collection_details_edit').toggleClass('active');
        e.preventDefault();
    });
    jQuery(document).on('click', '.closeCollectionEdit', function(e){
        jQuery(this).parent().toggleClass('active');
        e.preventDefault();
    });
    jQuery('.toggleModal').on('click', function(e){
        jQuery('.ip-modal').toggleClass('active');
        e.preventDefault();
    });
    jQuery('.toggleFrontEndModal').on('click', function(e){
        jQuery('.frontEndModal').toggleClass('active');
        e.preventDefault();
    });
    jQuery('.toggleFrontEndModal .close').on('click', function(e){
        jQuery('.frontEndModal').toggleClass('active');
        e.preventDefault();
    });

    jQuery('.addCollection').click(function(e){
        jQuery('.addCollection').val('Creating...');
        jQuery('.collection-progress').fadeIn();
        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'addCollection',
                collection_author_id: jQuery('#collection_author_id').val(),
                collection_title: jQuery('#collection_title').val(),
                collection_status: jQuery('#collection_status').val()
            }
        }).done(function() {
            jQuery('.addCollection').val('Create another collection');
            jQuery('.collection-progress').hide();
            jQuery('.showme').fadeIn();
        });

        e.preventDefault();
    });

    jQuery(document).on('click', '.deleteCollection', function(e){
        jQuery('body').find('deleteCollection').hide();
        var ipc = jQuery(this).data('collection-id');
        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'deleteCollection',
                collection_id: ipc,
            }
        }).done(function() {
            jQuery('.ipc' + ipc).fadeOut();
            jQuery('.ip-loadingCollections').fadeOut();
        });

        e.preventDefault();
    });
    jQuery(document).on('click', '.deleteCollectionImage', function(e){
        var ipc = jQuery(this).data('image-id');
        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'deleteCollectionImage',
                image_id: ipc,
            }
        }).done(function() {
            jQuery('.ip_box_' + ipc).fadeOut();
            jQuery('.ip-loadingCollections').fadeOut();
        });

        e.preventDefault();
    });

    jQuery(document).on('click', '.saveCollection', function(e){
        var ipc = jQuery(this).data('collection-id');
        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'editCollectionTitle',
                collection_id: ipc,
                collection_title: jQuery('.ct' + ipc).val(),
            }
        }).done(function() {
            jQuery('.collection_details_edit').removeClass('active');
            jQuery('.imagepress-collections').trigger('click');
        });

        e.preventDefault();
    });
    jQuery(document).on('change', '.collection-status', function(e){
        var ipc = jQuery(this).data('collection-id');

        var option = this.options[this.selectedIndex];

        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'editCollectionStatus',
                collection_id: ipc,
                collection_status: jQuery(option).val()
            }
        }).done(function() {
            jQuery('.cde' + ipc).fadeOut('fast');
        });

        e.preventDefault();
    });

    jQuery('.ip-modal .close').click(function(e){
        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'ip_collections_display',
            }
        }).done(function(msg) {
            jQuery('.collections-display').html(msg);
        });

        e.preventDefault();
    });
    jQuery('.imagepress-collections').click(function(e){
        jQuery('.ip-loadingCollections').show();
        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                action: 'ip_collections_display',
            }
        }).done(function(msg) {
            jQuery('.collections-display').html(msg);
            jQuery('.ip-loadingCollections').fadeOut();
        });

        e.preventDefault();
    });

    jQuery(document).on('click', '.editCollection', function(e){
        var ipc = jQuery(this).data('collection-id');
        jQuery('.ip-loadingCollectionImages').show();

        jQuery.ajax({
            method: 'post',
            url: ipAjaxVar.ajaxurl,
            data: {
                collection_id: ipc,
                action: 'ip_collection_display',
            }
        }).done(function(msg) {
            jQuery('.collections-display').html(msg);
            jQuery('.ip-loadingCollectionImages').fadeOut();
        });

        e.preventDefault();
    });
    /* end collections */

    // Sortable images inside profile editor
    var fixHelperModified = function(e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function(index) {
            jQuery(this).width($originals.eq(index).width());
        });
        return $helper;
    };

    if (jQuery('.editor-image-manager').length) {
        jQuery('.editor-image-manager').sortable({
            helper: fixHelperModified,
            handle: '.editor-image-handle',
            opacity: 0.75,
            update: function() {
                var image_order = jQuery(this).sortable('serialize') + '&action=imagepress_list_update_order';

                jQuery.post(ipAjaxVar.ajaxurl, image_order, function() {
                    swal({
                        toast: true,
                        position: 'top-end',
                        title: '',
                        html: '<svg class="lnr lnr-checkmark-circle"><use xlink:href="#lnr-checkmark-circle"></use></svg>',
                        showConfirmButton: false,
                        timer: 3000,
                    });
                });
            }
        }).enableSelection();
    }

    jQuery(document).on('click', '.editor-image-delete', function (e) {
        var id = jQuery(this).data('image-id');

        swal({
            title: '',
            text: ipAjaxVar.swal_confirm_operation,
            showCancelButton: true,
            confirmButtonText: ipAjaxVar.swal_confirm_button,
            cancelButtonText: ipAjaxVar.swal_cancel_button,
        }).then(function(response) {
            if (response.value) {
                jQuery(this).parent().parent().fadeOut();

                jQuery.ajax({
                    type: 'post',
                    url: ipAjaxVar.ajaxurl,
                    data: {
                        action: 'ip_delete_post',
                        id: id,
                    },
                    success: function(result) {
                        if (result === 'success') {
                            jQuery('#listItem_' + id).fadeOut(function(){
                                jQuery('#listItem_' + id).remove();
                            });
                        }
                    },
                });
            }
        });

        e.preventDefault();
        return false;
    });

    jQuery('.editableImage').click(function () {
        jQuery(this).addClass('editableImageActive');
    });
    jQuery('.editableImage').blur(function () {
        jQuery(this).removeClass('editableImageActive');
    });

    jQuery('.editableImage').keypress(function (e) {
        if (e.keyCode == 10 || e.keyCode == 13) {
            e.preventDefault();

            var id = jQuery(this).data('image-id');
            var title = jQuery(this).val();

            jQuery('.editableImageStatus_' + id).show().html('<svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg>');

            jQuery.ajax({
                type: 'post',
                url: ipAjaxVar.ajaxurl,
                data: {
                    action: 'ip_update_post_title',
                    title: title,
                    id: id,
                },
                success: function(result) {
                    if(result == 'success') {
                        jQuery('#listImage_' + id).removeClass('editableImageActive');
                        jQuery('.editableImageStatus_' + id).show().html('<svg class="lnr lnr-checkmark-circle"><use xlink:href="#lnr-checkmark-circle"></use></svg>');
                    }
                }
            });
        }
    });



    /*
     * Cinnamon Login
     *
     * Allow AJAX processing of login, registration and password reset forms
     */
    jQuery('#regform').on('submit', function(e){
        e.preventDefault();

		jQuery('#regform p.message').remove();
        jQuery('#regform h2').after('<p class="message notice">' + ipAjaxVar.registrationloadingmessage + '</p>');

        jQuery.ajax({
            type: 'GET',
            dataType: 'json',
            url: ipAjaxVar.ajaxurl,
            data: jQuery('#regform').serialize() + '&action=cinnamon_process_registration',
            success: function(results) {
                if(results.registered === true) {
                    jQuery('#regform p.message').removeClass('notice').addClass('success').text(results.message).show();
                } else {
                    jQuery('#regform p.message').removeClass('notice').addClass('error').html(results.message).show();
                }
            }
        });
    });

	jQuery('#pswform').on('submit', function(e){
        e.preventDefault();

        jQuery('#pswform p.message').remove();
        jQuery('#pswform h2').after('<p class="message notice">' + ipAjaxVar.loadingmessage + '</p>');

        jQuery.ajax({
            type: 'GET',
            dataType: 'json',
            url: ipAjaxVar.ajaxurl,
            data: {
                'action': 'cinnamon_process_psw_recovery', // Calls our wp_ajax_nopriv_ajaxlogin
                'username': jQuery('#pswform #forgot_login').val(),
                'forgotten': jQuery('#pswform input[name="forgotten"]').val(),
                'security': jQuery('#pswform #security').val()
            },
            success: function(results) {
                if(results.reset === true) {
                    jQuery('#pswform p.message').removeClass('notice').addClass('success').text(results.message).show();
                } else {
                    jQuery('#pswform p.message').removeClass('notice').addClass('error').html(results.message).show();
                }
            }
        });
    });
    /*
     * End Cinnamon Login
     */



    if (jQuery('.ip-uploader').length) {
        var userUploads = jQuery('.ip-uploader').data('user-uploads'),
            uploadLimit = jQuery('.ip-uploader').data('upload-limit'),
            globalUploadLimitMessage = ipAjaxVar.ip_global_upload_limit_message;

        if (!isNaN(uploadLimit) && userUploads >= uploadLimit) {
            jQuery('<div>' + globalUploadLimitMessage + ' (' + userUploads + '/' + uploadLimit + ')</div>').insertBefore('.ip-uploader');
            jQuery('.ip-uploader').remove();
        }
    }
});






























function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return typeof sParameterName[1] === 'undefined' ? true : sParameterName[1];
        }
    }
}

jQuery(document).ready(function() {
    if (jQuery('#ip-sorter-primary').length) {
        var request_uri = window.location.search,
            sorterDropdown = document.getElementById('sorter'),
            rangerDropdown = document.getElementById('ranger'),
            taxxxerDropdown = document.getElementById('taxxxer'),
            queryElement = document.getElementById('q'),
            queryLocation;

        // Check URI parameters, select default values, and redirect based on user selection
        if (getUrlParameter('sort') !== null) {
            sorterDropdown.value = request_uri;
        } else {
            sorterDropdown.selectedIndex = 0;
        }

        if (getUrlParameter('range') !== null) {
            rangerDropdown.value = request_uri;
        } else {
            rangerDropdown.selectedIndex = 0;
        }

        if (getUrlParameter('t') !== null) {
            taxxxerDropdown.value = request_uri;
        } else {
            taxxxerDropdown.selectedIndex = 0;
        }

        // Check if dropdown has changed on page load
        sorterDropdown.onchange = function () {
            document.getElementById('ip-sorter-loader').innerHTML = '<svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg>';
            window.location.href = sorterDropdown.value;
        };
        rangerDropdown.onchange = function () {
            document.getElementById('ip-sorter-loader').innerHTML = '<svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg>';
            window.location.href = rangerDropdown.value;
        };
        taxxxerDropdown.onchange = function () {
            document.getElementById('ip-sorter-loader').innerHTML = '<svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg>';
            window.location.href = taxxxerDropdown.value;
        };

        queryElement.onkeypress = function (e) {
            var event = e || window.event;
            var charCode = event.which || event.keyCode;

            if (charCode === '13') {
                // Enter key pressed

                queryLocation = window.location.search.replace(/(q=)[^\&]+/, '$1' + queryElement.value);
                window.location = queryLocation;

                return false;
            }
        };
    }
});

// ImagePress Grid UI
jQuery(window).load(function() {
    var gridUi = ipAjaxVar.grid_ui,
        currentDiv;

    if (gridUi === 'masonry') {
        if (jQuery('#ip-boxes').length) {
            var container = document.querySelector('#ip-boxes');
            var msnry = new Masonry(container, {
                itemSelector: '.ip_box ',
                columnWidth: '.ip_box',
                gutter: 0,
            });
        }
    } else if (gridUi === 'default') {
        var equalHeight = function(container) {
            var currentTallest = 0,
                currentRowStart = 0,
                rowDivs = new Array(),
                $el,
                topPosition = 0;

            jQuery(container).each(function() {
                $el = jQuery(this);
                jQuery($el).height('auto');
                topPosition = $el.position().top;

                if (currentRowStart != topPosition) {
                    for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                        rowDivs[currentDiv].height(currentTallest);
                    }
                    rowDivs.length = 0; // empty the array
                    currentRowStart = topPosition;
                    currentTallest = $el.height();
                    rowDivs.push($el);
                } else {
                    rowDivs.push($el);
                    currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
                }

                for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
                    rowDivs[currentDiv].height(currentTallest);
                }
            });
        };

        // Create equal height image containers // onload
        if (jQuery('.list .ip_box').length) {
            equalHeight('.list .ip_box');
        }
        // Create equal height image containers // onload
        if (jQuery('.ip-box-container-default .ip_box').length) {
            equalHeight('.ip-box-container-default .ip_box');
        }
    }
});



/*
 * Infinite lazy loading for Profile page
 */
jQuery(document).ready(function () {
    // Check if profile container exists
    if (jQuery('.profile-hub-container').length) {
        var sizeTotal = jQuery('#ip-boxes .ip_box').length,
            sizePerRow = jQuery('.ip-profile').data('ipw'),
            sizePerPage = ipAjaxVar.imagesperpage;

        if (sizeTotal === 0) {
            jQuery('#ipProfileShowMore').hide();
        }

        /*
         * Loop through first X visible images and lazy load them
         */
        jQuery('#ip-boxes .ip_box:lt(' + sizePerPage + ')').show();

        jQuery(document).on('click', '#ipProfileShowMore', function() {
            sizePerRow = (sizePerRow + sizePerPage <= sizeTotal) ? sizePerRow + sizePerPage : sizeTotal;
            jQuery('#ip-boxes .ip_box:lt(' + sizePerRow + ')').show();

            if (sizePerRow === sizeTotal) {
                jQuery('#ipProfileShowMore').hide();
            }
        });
    }
});



document.addEventListener && document.addEventListener('DOMContentLoaded', function () {
    var a, f = {},
        b, d, g, e = !1,
        h = document.getElementsByTagName('use'),
        c;
    XMLHttpRequest && (e = new XMLHttpRequest, e = 'withCredentials' in e ? XMLHttpRequest : XDomainRequest ? XDomainRequest : !1);
    if (e)
        for (g = function() {
                var a = document.body,
                    b = document.createElement('x');
                b.innerHTML = c.responseText;
                a.insertBefore(b.firstChild, a.firstChild)
            }, d = 0; d < h.length; d += 1) b = h[d].getAttribute('xlink:href').split('#'), a = b[0], b = b[1], a.length || !b || document.getElementById(b) ||
            (a = ipAjaxVar.ip_url + '/img/svgdefs.svg'), a.length && (f[a] = f[a] || new e, c = f[a], c.onload || (c.onload = g, c.open('GET', a), c.send()))
}, !1);

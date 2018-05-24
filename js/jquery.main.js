/**
 * ImagePress Javascript functions
 *
 * @copyright 2014-2018 Ciprian Popescu
 */

/* global jQuery, ipAjaxVar, Masonry */

/*eslint quotes: ["error", "single"]*/
/*eslint-env es6*/
/*eslint-env browser*/

function roar(e,r,t){"use strict";if("object"!=typeof t&&(t={}),window.roarAlert)window.roarAlert.cancel&&(window.roarAlert.cancelElement.style=""),window.roarAlert.confirm&&(window.roarAlert.confirmElement.style=""),document.body.classList.add("roar-open"),window.roarAlert.element.style.display="block",a=window.roarAlert;else{var a={element:null,cancelElement:null,confirmElement:null};a.element=document.querySelector(".roar-alert")}if(a.cancel=void 0!==t.cancel?t.cancel:!1,a.cancelText=void 0!==t.cancelText?t.cancelText:"Cancel",a.cancelCallBack=function(e){return document.body.classList.remove("roar-open"),window.roarAlert.element.style.display="none","function"==typeof t.cancelCallBack&&t.cancelCallBack(e),!0},document.querySelector(".roar-alert-mask")&&document.querySelector(".roar-alert-mask").addEventListener("click",function(e){return document.body.classList.remove("roar-open"),window.roarAlert.element.style.display="none","function"==typeof t.cancelCallBack&&t.cancelCallBack(e),!0}),a.message=r,a.title=e,a.confirm=void 0!==t.confirm?t.confirm:!0,a.confirmText=void 0!==t.confirmText?t.confirmText:"Confirm",a.confirmCallBack=function(e){return document.body.classList.remove("roar-open"),window.roarAlert.element.style.display="none","function"==typeof t.confirmCallBack&&t.confirmCallBack(e),!0},!a.element){a.html='<div class="roar-alert" id="roar-alert" role="alertdialog"><div class="roar-alert-mask"></div><div class="roar-alert-message-body" role="alert" aria-relevant="all"><div class="roar-alert-message-tbf roar-alert-message-title">'+a.title+'</div><div class="roar-alert-message-tbf roar-alert-message-content">'+a.message+'</div><div class="roar-alert-message-tbf roar-alert-message-button">',a.cancel,a.html+='<a href="javascript:;" class="roar-alert-message-tbf roar-alert-message-button-cancel">'+a.cancelText+"</a>",a.confirm,a.html+='<a href="javascript:;" class="roar-alert-message-tbf roar-alert-message-button-confirm">'+a.confirmText+"</a>",a.html+="</div></div></div>";var l=document.createElement("div");l.id="roar-alert-wrap",l.innerHTML=a.html,document.body.appendChild(l),a.element=document.querySelector(".roar-alert"),a.cancelElement=document.querySelector(".roar-alert-message-button-cancel"),a.cancel?document.querySelector(".roar-alert-message-button-cancel").style.display="block":document.querySelector(".roar-alert-message-button-cancel").style.display="none",a.confirmElement=document.querySelector(".roar-alert-message-button-confirm"),a.confirm?document.querySelector(".roar-alert-message-button-confirm").style.display="block":document.querySelector(".roar-alert-message-button-confirm").style.display="none",a.cancelElement.onclick=a.cancelCallBack,a.confirmElement.onclick=a.confirmCallBack,window.roarAlert=a}document.querySelector(".roar-alert-message-title").innerHTML="",document.querySelector(".roar-alert-message-content").innerHTML="",document.querySelector(".roar-alert-message-button-cancel").innerHTML=a.cancelText,document.querySelector(".roar-alert-message-button-confirm").innerHTML=a.confirmText,a.cancelElement=document.querySelector(".roar-alert-message-button-cancel"),a.cancel?document.querySelector(".roar-alert-message-button-cancel").style.display="block":document.querySelector(".roar-alert-message-button-cancel").style.display="none",a.confirmElement=document.querySelector(".roar-alert-message-button-confirm"),a.confirm?document.querySelector(".roar-alert-message-button-confirm").style.display="block":document.querySelector(".roar-alert-message-button-confirm").style.display="none",a.cancelElement.onclick=a.cancelCallBack,a.confirmElement.onclick=a.confirmCallBack,a.title=a.title||"",a.message=a.message||"",document.querySelector(".roar-alert-message-title").innerHTML=a.title,document.querySelector(".roar-alert-message-content").innerHTML=a.message,window.roarAlert=a}



document.addEventListener('DOMContentLoaded', function (event) {
    /**
     * Record Like action for custom post type
     */
    if (document.querySelector('.imagepress-like')) {
        document.querySelector('.imagepress-like').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var likeLabel,
                request = new XMLHttpRequest(),
                like = this,
                pid = like.dataset.post_id;

            like.innerHTML = '<svg class="lnr lnr-heart"><use xlink:href="#lnr-heart"></use></svg> <svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg>';

            request.open('POST', ipAjaxVar.ajaxurl, true);
            request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
            request.onload = function () {
                if (this.status >= 200 && this.status < 400) {
                    if (this.response.indexOf('already') !== -1) {
                        if (this.response.replace('already', '') === '0') {
                            likeLabel = ipAjaxVar.likelabel;
                        }
                        like.classList.remove('liked');
                        like.innerHTML = '<svg class="lnr lnr-heart"><use xlink:href="#lnr-heart"></use></svg> ' + likeLabel;
                    } else {
                        likeLabel = ipAjaxVar.unlikelabel;
                        like.classList.add('liked');
                        like.innerHTML = '<svg class="lnr lnr-heart"><use xlink:href="#lnr-heart"></use></svg> ' + likeLabel;
                    }
                } else {
                    // Response error
                }
            };
            request.onerror = function() {
                // Connection error
            };
            request.send('action=imagepress-like&nonce=' + ipAjaxVar.nonce + '&imagepress_like=&post_id=' + pid);

            return false;
        });
    }



    /*
     * Drag & Drop Uploader
     */
    if (document.getElementById('dropContainer')) {
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



    if (document.getElementById('imagepress_upload_image_form')) {
        document.getElementById('imagepress_upload_image_form').addEventListener('submit', function () {
            document.getElementById('imagepress_submit').disabled = true;
            document.getElementById('imagepress_submit').style.setProperty('opacity', '0.5');
            document.getElementById('ipload').innerHTML = '<svg class="lnr lnr-sync"><use xlink:href="#lnr-sync"></use></svg> Uploading...';
        });
    }
    /* end upload */

    /* ip_editor() related actions */
    if (document.querySelector('.ip-editor')) {
        var container = document.querySelector('.ip-editor');

        document.getElementById('ip-editor-open').addEventListener('click', function (event) {
            event.preventDefault();

            if (!container.classList.contains('active')) {
                container.classList.add('active');
                container.style.height = 'auto';

                var height = container.clientHeight + 'px';

                container.style.height = '0px';

                setTimeout(function () {
                    container.style.height = height;
                }, 0);
            } else {
                container.style.height = '0px';

                container.addEventListener('transitionend', function () {
                    container.classList.remove('active');
                }, {
                    once: true
                });
            }
        });

        jQuery(document).on('click', '#ip-editor-delete-image', function (e) {
            var id = this.dataset.imageId,
                redirect = this.dataset.redirect,
                options = {
                    cancel: true,
                    cancelText: ipAjaxVar.swal_cancel_button,
                    cancelCallBack: function () {
                        // Do nothing
                    },
                    confirm: true,
                    confirmText: ipAjaxVar.swal_confirm_button,
                    confirmCallBack: function () {
                        var request = new XMLHttpRequest();

                        request.open('POST', ipAjaxVar.ajaxurl, true);
                        request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                        request.onload = function () {
                            if (this.status >= 200 && this.status < 400) {
                                window.location = redirect;
                            } else {
                                // Response error
                            }
                        };
                        request.onerror = function() {
                            // Connection error
                        };
                        request.send('action=ip_delete_post&nonce=' + ipAjaxVar.nonce + '&id=' + id);
                    }
                };

            roar('', ipAjaxVar.swal_confirm_operation, options);

            e.preventDefault();
            return false;
        });
    }

    jQuery(document).on('click', '.featured-post', function (e) {
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
                success: function (result) {
                    if (result === 'success') {
                        jQuery('.ip-notice').fadeIn();
                    }
                }
            });
        }
        e.preventDefault();
        return false;
    });

    // notifications
    jQuery('.notifications-container .notification-item.unread').click(function () {
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

    jQuery('.imagepress-follow a').on('click', function (e) {
        e.preventDefault();
        var $this = jQuery(this);
        if (ipAjaxVar.logged_in != 'undefined' && ipAjaxVar.logged_in != 'true') {
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

        jQuery.post(ipAjaxVar.ajaxurl, data, function (response) {
            if (response === 'success') {
                jQuery('.imagepress-follow a').toggle();
            } else {
                alert(ipAjaxVar.processing_error);
            }

            jQuery('img.pwuf-ajax').hide();
        });
    });



    /* collections */
    jQuery(document).on('click', '.changeCollection', function (e) {
        jQuery(this).parent().parent().next('.collection_details_edit').toggleClass('active');
        e.preventDefault();
    });
    jQuery(document).on('click', '.closeCollectionEdit', function (e) {
        jQuery(this).parent().toggleClass('active');
        e.preventDefault();
    });
    jQuery('.toggleModal').on('click', function (e) {
        jQuery('.ip-modal').toggleClass('active');
        e.preventDefault();
    });
    jQuery('.toggleFrontEndModal').on('click', function (e) {
        jQuery('.frontEndModal').toggleClass('active');
        e.preventDefault();
    });
    jQuery('.toggleFrontEndModal .close').on('click', function (e) {
        jQuery('.frontEndModal').toggleClass('active');
        e.preventDefault();
    });

    jQuery('.addCollection').click(function (e) {
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
                    // Done. Do nothing.
                });
            }
        }).enableSelection();
    }

    jQuery(document).on('click', '.editor-image-delete', function (e) {
        var id = jQuery(this).data('image-id'),
            options = {
                cancel: true,
                cancelText: ipAjaxVar.swal_cancel_button,
                cancelCallBack: function () {
                    // Do nothing
                },
                confirm: true,
                confirmText: ipAjaxVar.swal_confirm_button,
                confirmCallBack: function () {

                    var request = new XMLHttpRequest();

                    request.open('POST', ipAjaxVar.ajaxurl, true);
                    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
                    request.onload = function () {
                        if (this.status >= 200 && this.status < 400) {
                            jQuery('#listItem_' + id).fadeOut(function(){
                                jQuery('#listItem_' + id).remove();
                            });
                        } else {
                            // Response error
                        }
                    };
                    request.onerror = function() {
                        // Connection error
                    };
                    request.send('action=ip_delete_post&nonce=' + ipAjaxVar.nonce + '&id=' + id);
                }
            };

        roar('', ipAjaxVar.swal_confirm_operation, options);

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
        if (e.keyCode === 10 || e.keyCode === 13) {
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
                success: function (result) {
                    if (result === 'success') {
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
    jQuery('#regform').on('submit', function (e) {
        e.preventDefault();

		jQuery('#regform p.message').remove();
        jQuery('#regform h2').after('<p class="message notice">' + ipAjaxVar.registrationloadingmessage + '</p>');

        jQuery.ajax({
            type: 'GET',
            dataType: 'json',
            url: ipAjaxVar.ajaxurl,
            data: jQuery('#regform').serialize() + '&action=cinnamon_process_registration',
            success: function (results) {
                if (results.registered === true) {
                    jQuery('#regform p.message').removeClass('notice').addClass('success').text(results.message).show();
                } else {
                    jQuery('#regform p.message').removeClass('notice').addClass('error').html(results.message).show();
                }
            }
        });
    });

	jQuery('#pswform').on('submit', function (e) {
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

document.addEventListener('DOMContentLoaded', function() {
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

    // ImagePress Grid UI
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

                if (currentRowStart !== topPosition) {
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

    /*
     * Infinite lazy loading for Profile page
     */
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

    // Load SVG in body
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

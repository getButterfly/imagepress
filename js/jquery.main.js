/* global window, document, console, ipAjaxVar, Sortable */
/* eslint quotes: ["error", "single"] */
/* eslint-env browser */
/* jslint-env browser */



function do_confirm(imageId) {
    document.getElementById('aep_prompt').innerHTML = ipAjaxVar.swal_confirm_operation;
    document.getElementById('aep_ww').style.display = '';

    document.querySelector('.aep_ok').dataset.imageId = imageId;
}

function do_click(m) {
    // Hide confirm modal
    document.getElementById('aep_ww').style.display = 'none';

    if (!m) {
        // false
    } else {
        // true
        var id = document.querySelector('.aep_ok').dataset.imageId;

        xhrRequest('ip_delete_post', '&id=' + id, function (caller) {
            document.getElementById('listItem_' + id).remove();
        });

        return false;
    }
}

if (document.querySelector('.aep_ok')) {
    document.querySelector('.aep_ok').addEventListener('click', function () {
        do_click(true);
    });
    document.querySelector('.aep_cancel').addEventListener('click', function () {
        do_click(false);
    });
}



function xhrRequest(action, arguments, onSuccess) {
    var request = new XMLHttpRequest();

    request.open('POST', ipAjaxVar.ajaxurl, true);
    request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
    request.onload = function (response) {
        if (this.status >= 200 && this.status < 400) {
            // Response success
            onSuccess(this);
        } else {
            // Response error
        }
    };
    request.onerror = function() {
        // Connection error
    };
    request.send('action=' + action + '&nonce=' + ipAjaxVar.nonce + arguments);
}



/**
 * ImagePress Javascript functions
 */
document.addEventListener('DOMContentLoaded', function () {
    /**
     * Record Like action for custom post type
     */
    if (document.querySelector('.imagepress-like')) {
        document.querySelector('.imagepress-like').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var likeLabel,
                like = this,
                pid = like.dataset.post_id;

            like.innerHTML = '<i class="fas fa-heart"></i> <i class="fas fa-cog fa-spin"></i>';

            xhrRequest('imagepress-like', '&imagepress_like=&post_id=' + pid, function (caller) {
                if (caller.response.indexOf('already') !== -1) {
                    if (caller.response.replace('already', '') === '0') {
                        likeLabel = ipAjaxVar.likelabel;
                    }
                    like.classList.remove('liked');
                    like.innerHTML = '<i class="fas fa-heart"></i> ' + likeLabel;
                } else {
                    likeLabel = ipAjaxVar.unlikelabel;
                    like.classList.add('liked');
                    like.innerHTML = '<i class="far fa-heart"></i> ' + likeLabel;
                }
            });

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
            document.getElementById('ipload').innerHTML = '<i class="fas fa-cog fa-spin"></i> Uploading...';
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

        if (document.querySelector('.ip-delete-post')) {
            var elements = document.querySelectorAll('.ip-delete-post');

            for (var i = 0; i < elements.length; i++) {
                elements[i].addEventListener('click', function (e) {
                    e.preventDefault();

                    var id = this.dataset.imageId;

                    xhrRequest('ip_delete_post', '&id=' + id, function (caller) {
                        document.querySelector('.ip-additional-' + id).remove();
                    });
                });
            }
        }
    }



    if (document.querySelector('ul.tabs')) {
        var tabLinks = document.querySelectorAll('ul.tabs li a');

        for (var i = 0; i < tabLinks.length; i++) {
            tabLinks[i].onclick = function () {
                var target = this.getAttribute('href').replace('#', '');
                var sections = document.querySelectorAll('div.tab-content');

                for (var j=0; j < sections.length; j++) {
                    sections[j].style.display = 'none';
                }

                document.getElementById(target).style.display = 'block';

                for (var k=0; k < tabLinks.length; k++) {
                    tabLinks[k].removeAttribute('class');
                }

                this.setAttribute('class', 'is-active');

                return false;
            }
        };
    }


    /* profile specific functions */
    if (document.querySelector('.imagepress-follow a')) {
        document.querySelector('.imagepress-follow a').addEventListener('click', function (e) {
            e.preventDefault();

            var $this = jQuery(this);

            if (typeof ipAjaxVar.logged_in !== 'undefined' && ipAjaxVar.logged_in !== 'true') {
                alert(ipAjaxVar.login_required);

                return;
            }

            var action = $this.hasClass('follow') ? 'follow' : 'unfollow';

            document.querySelector('img.pwuf-ajax').style.display = 'inline-block';

            xhrRequest(action, '&user_id=' + $this.data('user-id') + '&follow_id=' + $this.data('follow-id'), function (caller) {
                jQuery('.imagepress-follow a').toggle();
                document.querySelector('img.pwuf-ajax').style.display = 'none';
            });
        });
    }



    /* collections */
    document.querySelector('body').addEventListener('click', function(event) {
        if (event.target.className.indexOf('changeCollection') !== -1) {
            document.querySelector('.cde' + event.target.dataset.collectionId).classList.toggle('active');

            event.preventDefault();
        }
        if (event.target.className.indexOf('closeCollectionEdit') !== -1) {
            document.querySelector('.cde' + event.target.dataset.collectionId).classList.toggle('active');

            event.preventDefault();
        }
    });

    if (document.querySelector('.toggleModal')) {
        document.querySelector('.toggleModal').addEventListener('click', function(e) {
            document.querySelector('.ip-modal').classList.toggle('active');

            e.preventDefault();
        });
    }

    if (document.querySelector('.toggleFrontEndModal')) {
        document.querySelector('.toggleFrontEndModal').addEventListener('click', function(e) {
            document.querySelector('.frontEndModal').classList.toggle('active');

            e.preventDefault();
        });
        document.querySelector('.toggleFrontEndModal .close').addEventListener('click', function(e) {
            document.querySelector('.frontEndModal').classList.toggle('active');

            e.preventDefault();
        });
    }

    if (document.querySelector('.addCollection')) {
        document.querySelector('.addCollection').addEventListener('click', function(e) {
            document.querySelector('.addCollection').value = 'Creating...';
            jQuery('.collection-progress').fadeIn();

            xhrRequest('addCollection', '&collection_author_id=' + jQuery('#collection_author_id').val() + '&collection_title=' + jQuery('#collection_title').val() + '&collection_status=' + jQuery('#collection_status').val(), function (caller) {
                document.querySelector('.addCollection').value = 'Create another collection';
                jQuery('.collection-progress').hide();
                jQuery('.showme').fadeIn();
            });

            e.preventDefault();
        });
    }

    jQuery(document).on('click', '.deleteCollection', function(e){
        jQuery('body').find('deleteCollection').hide();

        var ipc = jQuery(this).data('collection-id');

        xhrRequest('deleteCollection', '&collection_id=' + ipc, function (caller) {
            jQuery('.ipc' + ipc).fadeOut();
            jQuery('.ip-loadingCollections').fadeOut();
        });

        e.preventDefault();
    });

    jQuery(document).on('click', '.saveCollection', function(e){
        var ipc = jQuery(this).data('collection-id');

        xhrRequest('editCollectionTitle', '&collection_id=' + ipc + '&collection_title=' + jQuery('.ct' + ipc).val(), function (caller) {
            jQuery('.collection_details_edit').removeClass('active');
            jQuery('.imagepress-collections').trigger('click');
        });

        e.preventDefault();
    });
    jQuery(document).on('change', '.collection-status', function (e) {
        var ipc = jQuery(this).data('collection-id');

        var option = this.options[this.selectedIndex];

        xhrRequest('editCollectionStatus', '&collection_id=' + ipc + '&collection_status=' + jQuery(option).val(), function (caller) {
            document.querySelector('.cde' + ipc).style.display = 'none';
        });

        e.preventDefault();
    });

    if (document.querySelector('.ip-modal .close')) {
        document.querySelector('.ip-modal .close').addEventListener('click', function (e) {
            xhrRequest('ip_collections_display', '', function (caller) {
                document.querySelector('.collections-display').innerHTML = caller.response;
            });

            e.preventDefault();
        });
    }

    if (document.querySelector('.imagepress-collections')) {
        document.querySelector('.imagepress-collections').addEventListener('click', function (e) {
            document.querySelector('.ip-loadingCollections').style.display = 'inline-block';

            xhrRequest('ip_collections_display', '', function (caller) {
                document.querySelector('.collections-display').innerHTML = caller.response;
                document.querySelector('.ip-loadingCollections').style.display = 'none';
            });

            e.preventDefault();
        });
    }
    /* end collections */

    // Sortable images inside profile editor
    if (document.querySelector('.editor-image-manager')) {
        var list = document.querySelector('.editor-image-manager');
        var sortable = Sortable.create(list, {
            sort: true,
            onUpdate: function () {
                var order = sortable.toArray();

                xhrRequest('imagepress_list_update_order', '&order=' + order, function (caller) {
                    // Sort success
                });
            },
        });
    }

    if (document.querySelector('.editableImage')) {
        var editableImageTriggers = document.querySelectorAll('.editableImage');

        [].forEach.call(editableImageTriggers, function (el) {
            el.addEventListener('keydown', function (e) {
                if (e.keyCode === 10 || e.keyCode === 13) {
                    e.preventDefault();

                    var id = this.dataset.imageId;
                    var title = this.value;

                    document.querySelector('.editableImageStatus_' + id).innerHTML = '<i class="fas fa-cog fa-spin"></i>';
                    document.querySelector('.editableImageStatus_' + id).style.display = 'inline-block';

                    xhrRequest('ip_update_post_title', '&title=' + title + '&id=' + id, function (caller) {
                        document.querySelector('.editableImageStatus_' + id).innerHTML = '<i class="fas fa-check"></i>';
                        document.querySelector('.editableImageStatus_' + id).style.display = 'inline-block';
                    });
                }
            });
        });
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
    if (document.getElementById('ip-sorter-primary')) {
        var requestUri = window.location.search,
            sorterDropdown = document.getElementById('sorter'),
            rangerDropdown = document.getElementById('ranger'),
            taxxxerDropdown = document.getElementById('taxxxer'),
            queryElement = document.getElementById('q'),
            queryLocation;

        // Check URI parameters, select default values, and redirect based on user selection
        if (getUrlParameter('sort') !== null) {
            sorterDropdown.value = requestUri;
        } else {
            sorterDropdown.selectedIndex = 0;
        }

        if (getUrlParameter('range') !== null) {
            rangerDropdown.value = requestUri;
        } else {
            rangerDropdown.selectedIndex = 0;
        }

        if (getUrlParameter('t') !== null) {
            taxxxerDropdown.value = requestUri;
        } else {
            taxxxerDropdown.selectedIndex = 0;
        }

        // Check if dropdown has changed on page load
        sorterDropdown.onchange = function () {
            document.getElementById('ip-sorter-loader').innerHTML = '<i class="fas fa-cog fa-spin"></i>';
            window.location.href = sorterDropdown.value;
        };
        rangerDropdown.onchange = function () {
            document.getElementById('ip-sorter-loader').innerHTML = '<i class="fas fa-cog fa-spin"></i>';
            window.location.href = rangerDropdown.value;
        };
        taxxxerDropdown.onchange = function () {
            document.getElementById('ip-sorter-loader').innerHTML = '<i class="fas fa-cog fa-spin"></i>';
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
}, !1);

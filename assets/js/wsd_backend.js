'use strict';

(function($) {
    var timeout = null;
    var debug = false;

    $(document).ready(function() {
        debug && console.log('wcsd 0.3.3');
        wsd_options();
    });

    $(document).on('change', 'select[name="_wsd_product_shipping_type"]', function() {
        wsd_options();
    });


    function wsd_options() {
        debug && console.log('wsd_options');

        if ($('select[name="_wsd_product_shipping_type"]').val() == 'date') {
            $('input[name="_wsd_product_shipping_datetime"]').parent().show();
            $('input[name="_wsd_product_shipping_datetime"]').attr('required','true');
            $('input[name="_wsd_product_shipping_delay"]').removeAttr('required');
            $('input[name="_wsd_product_shipping_delay"]').parent().hide();
        } else {
            $('input[name="_wsd_product_shipping_datetime"]').removeAttr('required');
            $('input[name="_wsd_product_shipping_datetime"]').parent().hide();
            $('input[name="_wsd_product_shipping_delay"]').parent().show();
            $('input[name="_wsd_product_shipping_delay"]').attr('required','true');
        }
    }

    /*
    $(document).on('click touch', '#div', function() {
        if ($(this).is(':checked')) {

        } else {

        }
    });

    // search input
    $(document).on('keyup', '#div', function() {
        if ($('#div').val() != '') {
            $('#div_loading').show();

            if (timeout != null) {
                clearTimeout(timeout);
            }

            timeout = setTimeout(wsd_ajax_get_data, 300);

            return false;
        }
    });


    // actions on search result items
    $(document).on('click touch', '#wsd_results li', function() {
        $(this).children('span.remove').html('×');
        $('#wsd_selected ul').append($(this));
        $('#wsd_results').hide();
        $('#wsd_keyword').val('');
        wsd_get_ids();
        wsd_arrange();

        return false;
    });

    // change qty of each item
    $(document).on('keyup change click', '#wsd_selected input', function() {
        wsd_get_ids();

        return false;
    });

    // actions on selected items
    $(document).on('click touch', '#wsd_selected span.remove', function() {
        $(this).parent().remove();
        wsd_get_ids();

        return false;
    });

    // hide search result box if click outside
    $(document).on('click touch', function(e) {
        if ($(e.target).closest($('#wsd_results')).length == 0) {
            $('#wsd_results').hide();
        }
    });

    $(document).on('wsdDragEndEvent', function() {
        wsd_get_ids();
    });

    function wsd_settings() {
        // hide search result box by default
        $('#wsd_results').hide();
        $('#wsd_loading').hide();

        // show or hide limit
        if ($('#wsd_custom_qty').is(':checked')) {
            $('.wsd_tr_show_if_custom_qty').show();
            $('.wsd_tr_hide_if_custom_qty').hide();
        } else {
            $('.wsd_tr_show_if_custom_qty').hide();
            $('.wsd_tr_hide_if_custom_qty').show();
        }
    }



    function wsd_arrange() {
        $('#wsd_selected li').arrangeable({
            dragEndEvent: 'wsdDragEndEvent',
            dragSelector: '.move',
        });
    }

    function wsd_get_ids() {
        var wsd_ids = new Array();

        $('#wsd_selected li').each(function() {
            if (!$(this).hasClass('wsd_default')) {
                wsd_ids.push($(this).attr('data-id') + '/' +
                    $(this).find('.price input').val() + '/' +
                    $(this).find('.qty input').val());
            }
        });

        if (wsd_ids.length > 0) {
            $('#wsd_ids').val(wsd_ids.join(','));
        } else {
            $('#wsd_ids').val('');
        }
    }

    function wsd_ajax_get_data() {
        // ajax search product
        timeout = null;

        var data = {
            action: 'wsd_get_search_results',
            wsd_keyword: $('#wsd_keyword').val(),
            wsd_id: $('#wsd_id').val(),
            wsd_ids: $('#wsd_ids').val(),
        };

        $.post(ajaxurl, data, function(response) {
            $('#wsd_results').show();
            $('#wsd_results').html(response);
            $('#wsd_loading').hide();
        });
    }
     */
})(jQuery);
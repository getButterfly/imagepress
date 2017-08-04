jQuery(document).ready(function() {
    jQuery('.ip-color-picker').wpColorPicker();

    var range_ipw = jQuery('.input-range-ipw'),
        value_ipw = jQuery('.range-value-ipw'),
        range_ipp = jQuery('.input-range-ipp'),
        value_ipp = jQuery('.range-value-ipp');

    value_ipw.html(range_ipw.attr('value'));
    value_ipp.html(range_ipp.attr('value'));

    range_ipw.on('input', function() {
        range_ipw.attr('value', this.value);
        value_ipw.html(this.value);
    });
    range_ipp.on('input', function() {
        range_ipp.attr('value', this.value);
        value_ipp.html(this.value);
    });
});

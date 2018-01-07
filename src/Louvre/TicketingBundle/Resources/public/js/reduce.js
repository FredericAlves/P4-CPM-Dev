$(function ($) {

    var $reduceCheckBox = $('.reduceCheckBox');



    $reduceCheckBox.change(function () {
        if ($reduceCheckBox.is(':checked')) {
            $('#reduceAlert').show();
        } else {
            $('#reduceAlert').hide();
        }
    })
});


$(document).ready(function() {
    $('.mask-price').mask('000.000.000', {reverse: true});
    $("#f-transfer").validate({
        rules: {
            amount: {
                required: true
            }
        },
        messages: {
            amount: {
                required: 'Nhập số tiền cần chuyển'
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
});
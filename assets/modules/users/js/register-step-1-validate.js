$(document).ready(function() {

    jQuery.validator.addMethod("phoneCheck", function() {
        var isSuccess = false;
        var phone = $('#f-register-step-1-phone').val();
        var strURL = base_url + 'users/check_phone_availablity';
        var data = { 'phone': phone, 'ajax': 1 };
        $.ajax({
            url: strURL,
            type: 'POST',
            async: false,
            data: data,
            success: function(response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    isSuccess = true;
                }
            }
        });
        return isSuccess;
    }, 'Số điện thoại này đã có người sử dụng');

    $("#f-register-step-1").validate({
        rules: {
            phone: {
                required: true,
                number: true,
                minlength: 10,
                maxlength: 10,
                phoneCheck: true
            }
        },
        messages: {
            phone: {
                required: 'Nhập số điện thoại'
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
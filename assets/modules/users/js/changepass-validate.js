$(document).ready(function() {
    jQuery.validator.addMethod("currentpasswordCheck", function() {
        var isSuccess = false;
        var current_password = $('#id-current-password').val();
        var strURL = base_url + 'users/check_current_password_availablity';
        var data = {'current_password': current_password, 'ajax': 1};
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
    }, 'Mật khẩu hiện tại không đúng');

    $("#f-changepass").validate({
        rules: {
            current_password: {
                required: true,
                minlength: 6,
                maxlength: 32,
                currentpasswordCheck: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 32
            },
            password_confirm: {
                required: true,
                minlength: 6,
                maxlength: 32,
                equalTo: "#password"
            }
        },
        messages: {
            current_password: {
                required: 'Nhập mật khẩu hiện tại'
            },
            password: {
                required: 'Nhập mật khẩu mới'
            },
            password_confirm: {
                required: 'Nhập xác nhận mật khẩu mới',
                equalTo: 'Xác nhận mật khẩu mới chưa đúng'
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
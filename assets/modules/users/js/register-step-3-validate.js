$(document).ready(function() {

    jQuery.validator.addMethod("usernameCheck", function() {
        var isSuccess = false;
        var username = $('#f-register-username').val();
        var strURL = base_url + 'users/check_username_availablity';
        var data = { 'username': username, 'ajax': 1 };
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
    }, 'Tên đăng nhập này đã có người sử dụng');



    jQuery.validator.addMethod("useremailCheck", function() {
        var isSuccess = false;
        var email = $('#f-register-email').val();
        var strURL = base_url + 'users/check_email_availablity';
        var data = { 'email': email, 'ajax': 1 };
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
    }, 'Email này đã có người sử dụng');

    $("#f-register").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            username: {
                required: true,
                minlength: 6,
                maxlength: 20,
                usernameCheck: true
            },
            email: {
                required: true,
                email: true,
                useremailCheck: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 60
            },
            password_confirm: {
                required: true
            }
        },
        messages: {
            full_name: {
                required: 'Nhập họ tên'
            },
            username: {
                required: 'Nhập tên đăng nhập'
            },
            email: {
                required: 'Nhập email',
                email: 'Nhập đúng định dạng email'
            },
            password: {
                required: 'Nhập mật khẩu'
            },
            password_confirm: {
                required: 'Nhập mật khẩu xác nhận'
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
$(document).ready(function() {
    jQuery.validator.addMethod("usernameFormat", function() {
        var isSuccess = false;
        var username = $('#f-register-username').val();
        var letter = /^([a-zA-Z0-9_\.])+$/;
        if (username.match(letter)){
            isSuccess = true;
        }
        return isSuccess;
    }, 'Tên đăng nhập phải viết liền không dấu (bao gồm chữ cái, chữ số, dấu gạch dưới và dấu chấm)');

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

    jQuery.validator.addMethod("emailFormat", function() {
        var isSuccess = false;
        var email = $('#f-register-email').val();
        var letter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        if (email.match(letter)){
            isSuccess = true;
        }
        return isSuccess;
    }, 'Email không hợp lệ');

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

    jQuery.validator.addMethod("identityNumberCardCheck", function() {
        var isSuccess = false;
        var identity_number_card = $('#identity_number_card').val();
        var strURL = base_url + 'users/check_identity_number_card_availablity';
        var data = { 'identity_number_card': identity_number_card, 'ajax': 1 };
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
    }, 'Số chứng minh thư này đã có người sử dụng');

    jQuery.validator.addMethod("phoneFormat", function() {
        var isSuccess = false;
        var phone = $('#f-register-phone').val();
        var letter = /((09|03|07|08|05)+([0-9]{8})\b)/g;
        if (phone.match(letter)){
            isSuccess = true;
        }
        return isSuccess;
    }, 'Số điện thoại không hợp lệ');

    jQuery.validator.addMethod("phoneCheck", function() {
        var isSuccess = false;
        var phone = $('#f-register-phone').val();
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

    $("#f-register").validate({
        rules: {
            full_name: {
                required: true,
                maxlength: 40
            },
            username: {
                required: true,
                minlength: 6,
                maxlength: 20,
                usernameFormat: true,
                usernameCheck: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 60
            },
            password_confirm: {
                required: true,
                minlength: 6,
                maxlength: 60,
                equalTo: "#f-register-password"
            },
            email: {
                required: true,
                emailFormat: true,
                useremailCheck: true
            },
            phone: {
                required: true,
                phoneFormat: true,
                phoneCheck: true
            },
            address: {
                required: true
            },
            identity_number_card: {
                required: true,
                identityNumberCardCheck: true
            }
        },
        messages: {
            full_name: {
                required: 'Nhập họ tên'
            },
            username: {
                required: 'Nhập tên đăng nhập'
            },
            password: {
                required: 'Nhập mật khẩu'
            },
            password_confirm: {
                required: 'Nhập mật khẩu xác nhận'
            },
            email: {
                required: 'Nhập email',
                email: 'Nhập đúng định dạng email'
            },
            phone: {
                required: 'Nhập số điện thoại'
            },
            address: {
                required: 'Nhập địa chỉ'
            },
            identity_number_card: {
                required: 'Nhập số chứng minh thư'
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
function closeAllTriggerClick(){
    $('#closeall').trigger('click');
}
$(document).on('click', '.btn-trigger-forget-password', function() {
    $('#pop-forget-password').trigger('click');
});
$(document).on('click', '.btn-trigger-login', function() {
    $('#pop-sign-in').trigger('click');
});
$(document).on('submit', '#f-login', function() {
    var f_prefix = '#f-login-';

    var f_username = $(f_prefix + 'username');
    var username = f_username.val();
    if ($.trim(username) == '') {
        alert('Vui lòng nhập tên đăng nhập!');
        f_username.focus();
        return false;
    }

    var f_password = $(f_prefix + 'password');
    var password = f_password.val();
    if ($.trim(password) == '') {
        alert('Vui lòng nhập mật khẩu!');
        f_password.focus();
        return false;
    }

    $.ajax({
        url: base_url + 'login-ajax',
        data: {
            'username': username,
            'password': password
        },
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            alert(response.message);
            if (response.status === 'success') {
                $('#f-login')[0].reset();
                location.reload();
            }
        },
        error: function(e) {
            console.log('Error processing your request: ' + e.responseText);
        }
    });
    return false;
});

$(document).on('submit', '#f-register', function() {
    var f_prefix = '#f-register-';

    var f_full_name = $(f_prefix + 'full-name');
    var full_name = f_full_name.val();
    if ($.trim(full_name) == '') {
        alert('Vui lòng nhập họ tên!');
        f_full_name.focus();
        return false;
    }

    var f_username = $(f_prefix + 'username');
    var username = f_username.val();
    if ($.trim(username) == '') {
        alert('Vui lòng nhập tên đăng nhập!');
        f_username.focus();
        return false;
    }

    var f_password = $(f_prefix + 'password');
    var password = f_password.val();
    if ($.trim(password) == '') {
        alert('Vui lòng nhập mật khẩu!');
        f_password.focus();
        return false;
    }

    var f_re_password = $(f_prefix + 're-password');
    var re_password = f_re_password.val();
    if ($.trim(re_password) == '') {
        alert('Vui lòng nhập mật khẩu xác nhận!');
        f_re_password.focus();
        return false;
    }

    var f_email = $(f_prefix + 'email');
    var email = f_email.val();
    if ($.trim(email) == '') {
        alert('Vui lòng nhập email!');
        f_email.focus();
        return false;
    }

    var f_phone = $(f_prefix + 'phone');
    var phone = f_phone.val();
    if ($.trim(phone) == '') {
        alert('Vui lòng nhập số điện thoại!');
        f_phone.focus();
        return false;
    }

    var f_ref = $(f_prefix + 'ref');
    var ref = f_ref.val();

    $.ajax({
        url: base_url + 'register-ajax',
        data: {
            'full_name': full_name,
            'username': username,
            'password': password,
            're_password': re_password,
            'email': email,
            'phone': phone,
            'ref': ref
        },
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            alert(response.message);
            if (response.status === 'success') {
                $('#f-register')[0].reset();
                $('#pop-sign-in').trigger('click');
            }
        },
        error: function(e) {
            console.log('Error processing your request: ' + e.responseText);
        }
    });
    return false;
});

$(document).on('submit', '#f-forget-password', function() {
    var f_prefix = '#f-forget-password-';

    var f_email = $(f_prefix + 'email');
    var email = f_email.val();
    if ($.trim(email) == '') {
        alert('Vui lòng nhập email!');
        f_email.focus();
        return false;
    }

    $.ajax({
        url: base_url + 'forget-password-ajax',
        data: {
            'email': email
        },
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log(response);
            alert(response.message);
            if (response.status === 'success') {
                $('#f-forget-password')[0].reset();
                closeAllTriggerClick();
            }
        },
        error: function(e) {
            console.log('Error processing your request: ' + e.responseText);
        }
    });
    return false;
});

$(document).ready(function() {
    jQuery.validator.addMethod("useremailCheck", function() {
        var isSuccess = false;
        var email = $('#id_email').val();
        var strURL = base_url + 'users/check_email_availablity';
        var data = {'email': email, 'ajax': 1};
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
    
    $("#f-editinfo").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            email: {
                required: true,
                email: true,
                useremailCheck: true
            }
        },
        messages: {
            full_name: {
                required: 'Nhập họ tên'
            },
            email: {
                required: 'Nhập email',
                email: 'Nhập đúng định dạng email'
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
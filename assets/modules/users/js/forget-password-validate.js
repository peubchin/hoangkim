$(document).ready(function() {
    $("#f-register").validate({
        rules: {
            email: {
                required: true,
                email: true
            },
            captcha: {
                required: true
            }
        },
        messages: {
            email: {
                required: 'Nhập email',
                email: 'Nhập đúng định dạng email'
            },
            captcha: {
                required: 'Nhập mã xác nhận'
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
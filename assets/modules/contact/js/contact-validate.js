jQuery.each(jQuery('textarea[data-autoresize]'), function() {
    var offset = this.offsetHeight - this.clientHeight;

    var resizeTextarea = function(el) {
        jQuery(el).css('height', 'auto').css('height', el.scrollHeight + offset);
    };
    jQuery(this).on('keyup input', function() {
        resizeTextarea(this);
    }).removeAttr('data-autoresize');
});

$(document).ready(function() {
    $("#f_contact").validate({
        rules: {
            yourname: {
                required: true,
                minlength: 6,
                maxlength: 20
            },
            email: {
                required: true,
                email: true
            },
            subject: {
                required: true,
                minlength: 6,
                maxlength: 100
            },
            message: {
                required: true,
                minlength: 6
            },
            captcha: {
                required: true
            }
        },
        messages: {
            yourname: {
                required: 'Nhập họ tên'
            },
            email: {
                required: 'Nhập email',
                email: 'Nhập đúng định dạng email'
            },
            subject: {
                required: 'Nhập tiêu đề'
            },
            message: {
                required: 'Nhập nội dung'
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
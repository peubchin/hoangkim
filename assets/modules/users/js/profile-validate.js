$(document).on("click", ".browse", function() {
    var file = $(this).parents().find(".file");
    file.trigger("click");
});
$('#photo').change(function(e) {
    var fileName = e.target.files[0].name;
    $("#file").val(fileName);

    var reader = new FileReader();
    reader.onload = function(e) {
        document.getElementById("preview").src = e.target.result;
    };
    reader.readAsDataURL(this.files[0]);
});
$(document).ready(function() {
    var clipboard = new Clipboard('.btn-copy');
    $("#f-profile").validate({
        rules: {
            full_name: {
                required: true,
                minlength: 6,
                maxlength: 40
            },
            address: {
                required: true
            },
            phone: {
                required: true
            }
        },
        messages: {
            full_name: {
                required: 'Nhập họ tên'
            },
            address: {
                required: 'Nhập địa chỉ'
            },
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
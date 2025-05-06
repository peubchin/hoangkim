$(document).ready(function() {
    $(".chosen-select-enable-search").chosen({ search_contains: true });
    $("#f-content").validate({
        rules: {
            referred_by: {
                required: true
            }
        },
        messages: {
            referred_by: {
                required: 'Chọn người giới thiệu'
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
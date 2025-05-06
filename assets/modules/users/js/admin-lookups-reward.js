$(document).ready(function() {
    $(".chosen-select-enable-search").chosen({ search_contains: true });
    $("#f-content").validate({
        rules: {
            user_id: {
                required: true
            },
            month: {
                required: true
            }
        },
        messages: {
            user_id: {
                required: 'Chọn thành viên'
            },
            month: {
                required: 'Chọn tháng xem'
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

    $('#datePickerMonth').datepicker({
        minViewMode: 1,
        format: 'mm-yyyy',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        showOn: 'focus',
        language: 'vi',
        autoclose: true,
        startDate: "04-2023",
        endDate: today()
    });
});
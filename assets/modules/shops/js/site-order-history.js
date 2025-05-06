$(document).ready(function() {
    $('#datePickerFromday').datepicker({
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        showOn: 'focus',
        language: 'vi',
        autoclose: true,
        startDate: "01-01-1970"
    });

    $('#datePickerToday').datepicker({
        format: 'dd-mm-yyyy',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        showOn: 'focus',
        language: 'vi',
        autoclose: true,
        startDate: "01-01-1970"
    });
});

$(document).on('click', '.btn-status-cancel', function(e) {
    $(this).closest('.btn-group').find('button').trigger('click');
    if (!confirm("Bạn có thật sự muốn hủy đơn hàng này?")) {
        return false;
    }
    /*
    var id = $(this).attr('data-id');
    //alert(id); return false;
    var el = $(this).closest('td');
    var data = {
        'id': id,
        'status': -1
    };
    $.ajax({
        url: base_url + 'admin/commission/change-status-ajax',
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            $("#notify").html(response.message);
            if (response.status === 'success') {
                el.html(response.content);
            }
        },
        error: function(e) {
            console.log('Error processing your request: ' + e.responseText);
        }
    });
    return false;
    */
});
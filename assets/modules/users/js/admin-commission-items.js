$(document).ready(function() {
    $('form[name=main]').submit(function(event) {
        var action = $('select[name="action"]').val();
        if (action === 'delete') {
            if (confirm("Bạn có thật sự muốn xóa các giao dịch này? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị xóa. Bạn sẽ không thể phục hồi lại chúng sau này!")) {
                return true;
            }
            return false;
        }
    });

    $('.btn-delete-confirm').on('click', function(e, confirmed) {
        var link = $(this).attr('href');
        if (!confirmed) {
            e.preventDefault();
            BootstrapDialog.confirm({
                title: 'XÁC NHẬN THÔNG TIN',
                message: 'Bạn có thật sự muốn xóa giao dịch này? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị xóa. Bạn sẽ không thể phục hồi lại chúng sau này!',
                type: BootstrapDialog.TYPE_DANGER,
                closable: true,
                draggable: true,
                btnCancelLabel: 'Hủy bỏ',
                btnOKLabel: 'Đồng ý',
                btnOKClass: 'btn-primary',
                callback: function(result) {
                    if (result) {
                        window.location.href = link;
                    }
                }
            });
        }
    });

    $('.btn-status-confirm').on('click', function(e) {
        if (!confirm("Bạn có thật sự muốn xác nhận yêu cầu này?")) {
            return false;
        }
        var id = $(this).attr('data-id');
        var el = $(this).closest('td');
        var data = {
            'id': id,
            'status': 1
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
    });

    $('.btn-status-cancel').on('click', function(e) {
        if (!confirm("Bạn có thật sự muốn hủy yêu cầu này?")) {
            return false;
        }
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
    });

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

    $('#datePicker').datepicker({
        format: 'dd/mm/yyyy',
        changeMonth: true,
        changeYear: true,
        showOtherMonths: true,
        showOn: 'focus',
        language: 'vi',
        autoclose: true,
        startDate: "01/01/1970"
    });
});
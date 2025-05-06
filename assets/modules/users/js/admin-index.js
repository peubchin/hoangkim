$(document).ready(function() {
    $('.confirm_bootstrap').on('click', function(e, confirmed) {
        var link = $(this).attr('href');
        if (!confirmed) {
            e.preventDefault();
            BootstrapDialog.confirm({
                title: 'XÁC NHẬN THÔNG TIN',
                message: 'Bạn có thật sự muốn xóa tài khoản này? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị xóa. Bạn sẽ không thể phục hồi lại chúng sau này!',
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

    $(".change_status").on('ifChanged', function() {
        var userid = $(this).val();
        var strURL = base_url + 'admin/users/active';
        if ($(this).is(':checked')) { // check select status
            var form_data = {'userid': userid, 'active': 1};
        } else {
            var form_data = {'userid': userid, 'active': 0};
        }

        $.ajax({
            type: "POST",
            url: strURL,
            data: form_data,
            success: function(html) {
                $("#notify").html(html);
            }
        });

        return false;
    });

    var obj_fields = {
        // 'is-bestseller': {field: "is_bestseller", massage_success: "Đã bật hiển thị sản phẩm bán chạy!", massage_warning: "Đã tắt hiển thị sản phẩm bán chạy!"},
        'is-wholesale': {field: "is_wholesale", massage_success: "Đã bật hiển thị tài khoản sỉ!", massage_warning: "Đã tắt hiển thị tài khoản sỉ!"}
    };
    Object.keys(obj_fields).forEach(function (key) {
        $(".change-" + key).on('ifChanged', function () {
            var id = $(this).val();
            var value = 0;
            var strURL = base_url + 'admin/users/change-field';
            if ($(this).is(':checked')) {
                value = 1;
            }
            var form_data = obj_fields[key];
            form_data.id = id;
            form_data.value = value;

            $.ajax({
                type: "POST",
                url: strURL,
                data: form_data,
                success: function (html) {
                    $("#notify").html(html);
                }
            });

            return false;
        });
    });
});

$(document).on('click', '.btn-generate-qr-code', function() {
    var id = parseInt($(this).attr('data-id'));
    var el = $(this).closest('td');
    var data = {
        'id': id
    };
    $.ajax({
        url: base_url + 'admin/users/generate-qr-code-ajax',
        data: data,
        type: 'POST',
        dataType: 'json',
        success: function(response) {
            // console.log(response);
            // $("#notify").html(response.message);
            alert(response.message);
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
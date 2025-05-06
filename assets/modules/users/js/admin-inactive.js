$(document).ready(function() {
    $('.btn-confirm-lock-all').on('click', function(e, confirmed) {
        var link = $(this).attr('href');
        if (!confirmed) {
            e.preventDefault();
            BootstrapDialog.confirm({
                title: 'XÁC NHẬN THÔNG TIN',
                message: 'Bạn có thật sự muốn khóa các tài khoản này? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị ảnh hưởng. Bạn sẽ không thể phục hồi lại chúng sau này!',
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
});
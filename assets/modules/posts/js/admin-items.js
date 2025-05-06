//<![CDATA[
$(document).ready(function() {
    $('.delete_bootbox').on('click', function(e, confirmed) {
        var link = $(this).attr('href');
        if (!confirmed) {
            e.preventDefault();
            BootstrapDialog.confirm({
                title: 'XÁC NHẬN THÔNG TIN',
                message: 'Bạn có thật sự muốn xóa bài viết này? Nếu đồng ý, tất cả dữ liệu liên quan sẽ bị xóa. Bạn sẽ không thể phục hồi lại chúng sau này!',
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

    var obj_fields = {
		'inhome': {field: "inhome", massage_success: "Đã bật hiển thị bài viết ở trang chủ!", massage_warning: "Đã tắt hiển thị bài viết ở trang chủ!"},
		'is-news': {field: "is_news", massage_success: "Đã bật hiển thị bài viết là tin tức!", massage_warning: "Đã tắt hiển thị bài viết là tin tức!"},
    'is-featured': {field: "is_featured", massage_success: "Đã bật hiển thị bài viết nổi bật!", massage_warning: "Đã tắt hiển thị bài viết nổi bật!"},
    'is-new': {field: "is_new", massage_success: "Đã bật hiển thị bài viết mới nhất!", massage_warning: "Đã tắt hiển thị bài viết mới nhất!"}
    };
    Object.keys(obj_fields).forEach(function (key) {
        $(".change-" + key).on('ifChanged', function () {
            var id = $(this).val();
            var value = 0;
            var strURL = base_url + 'admin/posts/change-field';
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
//]]>

$(document).ready(function () {
    jQuery.validator.addMethod("aliasCheck", function () {
        var isSuccess = false;
        var alias = $('#alias').val();

        var id = 0;
        if ($('#id').length) {
            id = $('#id').val();
        }

        var strURL = base_url + 'admin/shops/cat/check_alias_availablity';
        var data = {'alias': alias, 'id': id, 'ajax': 1};
        $.ajax({
            url: strURL,
            type: 'POST',
            async: false,
            data: data,
            success: function (response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    isSuccess = true;
                }
            }
        });

        return isSuccess;
    }, 'Liên kết tĩnh này đã sử dụng');

    jQuery.validator.addMethod("alias_enCheck", function () {
        var isSuccess = false;
        var alias_en = $('#alias_en').val();

        var id = 0;
        if ($('#id').length) {
            id = $('#id').val();
        }

        var strURL = base_url + 'admin/shops/cat/check_alias_en_availablity';
        var data = {'alias_en': alias_en, 'id': id, 'ajax': 1};
        $.ajax({
            url: strURL,
            type: 'POST',
            async: false,
            data: data,
            success: function (response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    isSuccess = true;
                }
            }
        });

        return isSuccess;
    }, 'Liên kết tĩnh tiếng Anh này đã sử dụng');

    $("#f-cat").validate({
        rules: {
            name: {
                required: true
            },
            name_en: {
                required: true
            },
            alias: {
                required: true,
                aliasCheck: true
            },
            alias_en: {
                required: true,
                alias_enCheck: true
            }
        },
        messages: {
            name: {
                required: 'Nhập tên loại sản phẩm'
            },
            name_en: {
                required: 'Nhập tên loại sản phẩm tiếng Anh'
            },
            alias: {
                required: 'Nhập liên kết tĩnh'
            },
            alias_en: {
                required: 'Nhập liên kết tĩnh tiếng Anh'
            }
        },
        highlight: function (element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function (element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function (error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });

    $("#name").on('keyup blur', function () {
        var title = $(this).val();
        $("#alias").val(get_slug(title));

        return false;
    });

    $("#name_en").on('keyup blur', function () {
        var title = $(this).val();
        $("#alias_en").val(get_slug(title));

        return false;
    });
});
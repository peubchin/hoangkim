$(document).ready(function () {
    jQuery.validator.addMethod("aliasCheck", function () {
        var isSuccess = false;
        var alias = $('#alias').val();
        
        var id = 0;
        if ($('#id').length) {
            id = $('#id').val();
        }

        var strURL = base_url + 'admin/posts/cat/check_alias_availablity';
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

    $("#f-cat").validate({
        rules: {
            name: {
                required: true
            },
            alias: {
                required: true,
                aliasCheck: true
            }
        },
        messages: {
            name: {
                required: 'Nhập tên chủ đề'
            },
            alias: {
                required: 'Nhập liên kết tĩnh'
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
});
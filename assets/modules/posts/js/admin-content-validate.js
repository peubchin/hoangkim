var tags = document.getElementById("Tags").value;
res = tags.split(",");
jQuery(".tm-input").tagsManager({
    prefilled: res,
    CapitalizeFirstLetter: false,
    AjaxPush: null,
    AjaxPushAllTags: null,
    AjaxPushParameters: null,
    delimiters: [9, 13, 44],
    backspace: [8],
    blinkBGColor_1: '#FFFF9C',
    blinkBGColor_2: '#CDE69C',
    hiddenTagListName: 'hidden-tags',
    hiddenTagListId: null,
    deleteTagsOnBackspace: true, //false: không cho xóa tag

    tagsContainer: null,
    tagCloseIcon: '×',
    tagClass: '',
    validator: null,
    onlyTagList: false

});
$(document).ready(function () {
    $("#f-content").validate({
        rules: {
            title: {
                required: true
            },
            alias: {
                required: true
            }
        },
        messages: {
            title: {
                required: 'Nhập tên bài viết'
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

    $("#title").on('keyup blur', function () {
        var title = $(this).val();
        $("#alias").val(get_slug(title));

        return false;
    });
});
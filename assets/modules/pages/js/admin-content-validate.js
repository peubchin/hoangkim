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

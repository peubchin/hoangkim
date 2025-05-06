$(function () {
    $(".chosen").chosen();
});
$(document).on('click', '.chosen-toggle', function () {
    $(".chosen").chosen();
    $(this).closest('.input-group').find('option').each(function () {
        if ($(this).val() != 0) {
            $(this).attr('selected', 'selected');
        }
    });
    $(this).closest('.input-group').find('select.chosen').trigger('chosen:updated');
});
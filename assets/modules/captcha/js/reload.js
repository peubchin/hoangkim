//<![CDATA[
$(document).ready(function() {
    $('#reload_captcha').on("click", function() {
        var strURL = base_url + 'captcha/refresh';
        var data = {'ajax': 1};
        $.ajax({
            url: strURL,
            type: 'POST',
            async: false,
            data: data,
            success: function(response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    $('#captcha').html('');
                    $('#captcha').html(getData.message);
                }
            }
        });
        return false;
    });
});
//]]>
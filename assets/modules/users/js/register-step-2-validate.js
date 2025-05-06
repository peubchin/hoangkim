function runTimer(){
    if($('#countdown').length){
        var downloadTimer = setInterval(function() {
            $('#countdown').html(timeleft + 's');
            timeleft -= 1;
            if (timeleft <= 0) {
                clearInterval(downloadTimer);
                $("#action-OTP").html('<button class="btn btn-info btn-new-OTP" type="button">Lấy mã</button>');
            }
        }, 1000);
    }
}
$(document).ready(function() {
    runTimer();
    $("#f-register-step-2").validate({
        rules: {
            OTP: {
                required: true,
                number: true,
                minlength: 6,
                maxlength: 6
            }
        },
        messages: {
            OTP: {
                required: 'Nhập OTP'
            }
        },
        highlight: function(element) {
            $(element).closest('.form-group').addClass('has-error');
        },
        unhighlight: function(element) {
            $(element).closest('.form-group').removeClass('has-error');
        },
        errorElement: 'span',
        errorClass: 'help-block',
        errorPlacement: function(error, element) {
            if (element.parent('.input-group').length) {
                error.insertAfter(element.parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
});

$(document).on('click', '.btn-new-OTP', function() {
    var reloadTimer = false;
    $.ajax({
        url: base_url + "users/get-new-OTP-ajax",
        type: 'POST',
        cache: false,
        async: false,
        data: {'phone': phone},
        dataType: 'json',
        success: function(response) {
            //console.log(response);
            alert(response.message);
            if (response.status === 'success') {
                $('#action-OTP').html(response.content);
                reloadTimer = true;
                timeleft = timeLimited;
            }
        },
        error: function(e) {
            console.log('Lỗi: ' + e.responseText);
        }
    });
    if(reloadTimer){
        runTimer();
    }
    return false;
});
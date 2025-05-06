function debounce(func, wait) {
    var timeout;

    return function() {
        var context = this,
            args = arguments;

        var executeFunction = function() {
            func.apply(context, args);
        };

        clearTimeout(timeout);
        timeout = setTimeout(executeFunction, wait);
    };
}

$(document).ready(function() {
    $('.mask-price').mask('000.000.000', {reverse: true});
    $("#f-withdrawal").validate({
        rules: {
            amount: {
                required: true
            }
        },
        messages: {
            amount: {
                required: 'Nhập số tiền cần rút'
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

function numberFormat(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.");
}

function numberFormatDecode(num) {
    return num.toString().replace(/\./g, "");
}

function setTotal(){
    var run = debounce(function () {
        var percent = 10;
        var amount = parseFloat(numberFormatDecode($('#amount').val()));
        if(isNaN(amount)){
            amount = 0;
        }
        if(amount > 0){
            var total = Math.round(Math.abs(amount * (1 + percent/100)));
            $('#total strong').html(numberFormat(total));
            $('#total').removeClass('d-none');
        }else{
            $('#total strong').html('');
            $('#total').addClass('d-none');
        }
    }, 1000);
    run();
}

$(document).on('keydown blur', '#amount', function() {
    setTotal();
});
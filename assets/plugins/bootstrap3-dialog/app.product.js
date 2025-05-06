function displayDialog(message, type) {
    var dialogMessage = new BootstrapDialog();
    dialogMessage.setTitle('Thông báo');
    dialogMessage.setMessage(message);
    if (type === 'success') {
        dialogMessage.setType(BootstrapDialog.TYPE_INFO);
    } else if (type === 'warning') {
        dialogMessage.setType(BootstrapDialog.TYPE_WARNING);
    } else {
        dialogMessage.setType(BootstrapDialog.TYPE_DANGER);
    }
    dialogMessage.open();
    setTimeout(function () {
        dialogMessage.close();
    }, 3600);
}
$(document).ready(function () {
    var link = base_url + 'shops' + '/';
    $("#increase").on('click', function () {
        var qty = $("#qty").val();
        if (!isNaN(qty)) {
            qty++;
            $("#qty").val(qty);
        }
        return false;
    });

    $("#reduced").on('click', function () {
        var qty = $("#qty").val();
        if (!isNaN(qty) && qty > 1) {
            qty--;
            $("#qty").val(qty);
        }
        return false;
    });

    $("form#product_addtocart_form").submit(function () {
        var id = $(this).find('input[name=product_id]').val();
        var qty = $(this).find('input[name=qty]').val();
        console.log(qty);

        $.post(link + "cart/add_cart_item", {product_id: id, qty: qty, ajax: '1'},
        function (data) {
            if (data === 'true') {
                $.get(link + "cart/show_cart_page", function (cart) {
                    $(".mini-cart").html(cart);
                });
                displayDialog('Sản phẩm này đã được mua!', 'success');
            } else {
                alert("Sản phẩm này không tồn tại!");
            }
        });

        return false;
    });


    $(".link-wishlist").on('click', function () {
        var str_id = $(this).attr('id');
        var arr = str_id.split("_");
        var product_id = arr[arr.length - 1];
        var strURL = base_url + 'shops/like';
        var data = {'product_id': product_id, 'ajax': 1};
        $.ajax({
            url: strURL,
            type: 'POST',
            cache: false,
            data: data,
            success: function (response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    displayDialog(getData.message, 'success');
                } else if (getData.status === 'warning') {
                    displayDialog(getData.message, 'warning');
                } else {
                    displayDialog(getData.message, '');
                }
            },
            error: function () {
                displayDialog('Có lỗi xảy ra', '');
            }
        });
        return false;
    });
    
    $(".link-compare").on('click', function () {
        var str_id = $(this).attr('id');
        var arr = str_id.split("_");
        var product_id = arr[arr.length - 1];
        var strURL = base_url + 'compare/add/' + product_id;
        var data = {'ajax': 1};
        $.ajax({
            url: strURL,
            type: 'POST',
            cache: false,
            data: data,
            success: function (response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    displayDialog(getData.message, 'success');
                } else if (getData.status === 'warning') {
                    displayDialog(getData.message, 'warning');
                } else {
                    displayDialog(getData.message, '');
                }
            },
            error: function () {
                displayDialog('Có lỗi xảy ra', '');
            }
        });
        return false;
    });
    
    $(".link-compare-remove").on('click', function () {
        var str_id = $(this).attr('id');
        var arr = str_id.split("_");
        var product_id = arr[arr.length - 1];
        var strURL = base_url + 'compare/delete/' + product_id;
        var data = {'ajax': 1};
        $.ajax({
            url: strURL,
            type: 'POST',
            cache: false,
            data: data,
            success: function (response) {
                var getData = $.parseJSON(response);
                if (getData.status === 'success') {
                    displayDialog(getData.message, 'success');
                } else if (getData.status === 'warning') {
                    displayDialog(getData.message, 'warning');
                } else {
                    displayDialog(getData.message, '');
                }
            },
            error: function () {
                displayDialog('Có lỗi xảy ra', '');
            }
        });
        return false;
    });


    $(".cart-button form.add-to-cart").on('click', function () {
        var id = $(this).find('input[name=product_id]').val();

        var link = base_url + 'shops' + '/';
        //var qty = $("#qty_" + id).val();

        $.post(link + "cart/add_cart_item", {product_id: id, qty: 1, ajax: '1'},
        function (data) {
            if (data === 'true') {
                $.get(link + "cart/show_cart_page", function (cart) {
                    $(".mini-cart").html(cart);
                });
                displayDialog('Sản phẩm này đã được mua!', 'success');
            } else {
                displayDialog("Product does't exist!", '');
            }
        });

        return false;
    });
});
/*
$(document).on('click', '.btn-quickview', function () {
    var link = $(this).attr('href');
    $.ajax({
        type: "POST",
        dataType: "json",
        url: link,
        data: {is_ajax: 1},
        success: function (response) {
            if (response.status == 'success') {
                $(".modal-body").html('');
                $(".modal-body").html(response.content);
                $('#myModal').modal('show');
            } else {
                $('#myModal').modal('hide');
            }
        }
    });
    return false;
});
*/
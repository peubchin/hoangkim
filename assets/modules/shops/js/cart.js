$(document).on('click', ".btn-mini-cart-up, .btn-mini-cart-down", function (e) {
	$(this).closest('li').find('.mini-cart-qty').trigger('blur');
});

$(document).on('blur', ".mini-cart-qty", function (e) {
	var qty = parseInt($(this).val());
	var rowid = $(this).attr('data-rowid');
	if(qty <= 0){
		return false;
	}
	$(this).closest('li').addClass('my-active');
	
	var data = {
		'rowid': rowid,
		'qty': qty
	};
	//console.log(data);
	//return false;
	$.ajax({
		url: base_url + 'gio-hang-cap-nhat-ajax',
		data: data,
		type: 'POST',
		dataType: 'json',
		success: function (response) {
			if (response.status === 'success') {
				//$('.container-mini-cart li.my-active').find('.cart-subtotal').html(response.content.item.subtotal + ' ₫');
				$('#mini-cart-total').html(response.content.total + ' ₫');
			}
			$('.container-mini-cart li').removeClass('my-active');
		},
		error: function (e) {
			console.log('Error processing your request: ' + e.responseText);
		}
	});
	return false;
});

$(document).on('click', ".mini-cart-remove-item", function (e) {
	e.preventDefault();
	if(!confirm("Bạn có muốn xóa sản phẩm này khỏi giỏ hàng?")){
		return false;
	}
	var rowid = $(this).attr('data-rowid');
	$(this).closest('li').addClass('my-active');
	
	var data = {
		'rowid': rowid
	};
	$.ajax({
		url: base_url + 'gio-hang-xoa-san-pham-ajax',
		data: data,
		type: 'POST',
		dataType: 'json',
		success: function (response) {
			if (response.status === 'success') {
				$('.container-mini-cart li.my-active').remove();
				$('#mini-cart-count').html('(' + response.content.count + ') Sản phẩm');
				$('#mini-cart-total').html(response.content.total + ' ₫');
				if(parseInt(response.content.total) == 0){
					$.get(base_url + "shops/cart/show_cart_page", function (cart) {
						$(".mini-cart").html(cart);
						$('.after-btn-cart').bootstrapNumber({
							upClass: "default btn-mini-cart-up",
							downClass: "default btn-mini-cart-down",
							center: true
						});
					});
				}
			}
		},
		error: function (e) {
			console.log('Error processing your request: ' + e.responseText);
		}
	});
	return false;
});

$(document).ready(function() {
	$('.after-btn-cart').bootstrapNumber({
		upClass: "default btn-mini-cart-up",
		downClass: "default btn-mini-cart-down",
		center: true
	});
    $("#f-add-to-cart").on('submit', function () {
        var $this = $(this);
        var id = $this.find('input[name=product_id]').val();
        var qty = $this.find('input[name=qty]').val();

        var link = base_url + 'shops' + '/';
		$.post(link + "cart/add_cart_item", {product_id: id, qty: qty, ajax: '1'},
        function (data) {
            if (data === 'true') {
                $.get(link + "cart/show_cart_page", function (cart) {
                    $(".mini-cart").html(cart);
					$('.after-btn-cart').bootstrapNumber({
						upClass: "default btn-mini-cart-up",
						downClass: "default btn-mini-cart-down",
						center: true
					});
                });
				window.location.href = base_url + 'thanh-toan.html';//content.redirect;
                //alert('Sản phẩm này đã được thêm vào giỏ hàng!');
                //return false;
            } else {
                alert("Sản phẩm này không tồn tại!");
            }
        });

        return false;
    });
	$(".btn-buy-now").on('click', function() {
        var $this = $(this).closest("form#f-add-to-cart");
        var id = $this.find('input[name=product_id]').val();
        var qty = $this.find('input[name=qty]').val();

        var link = base_url + 'shops' + '/';
        $.post(link + "cart/add_cart_item", {'product_id': id, 'qty': qty, 'ajax': '1'},
            function(data) {
                if (data === 'true') {
                    $.get(link + "cart/show_cart_page", function(cart) {
                        $(".mini-cart").html(cart);
                        $('.after-btn-cart').bootstrapNumber({
                            upClass: "default btn-mini-cart-up",
                            downClass: "default btn-mini-cart-down",
                            center: true
                        });
                    });
                    alert('Sản phẩm này đã được thêm vào giỏ hàng!');
                    //$(location).attr('href', base_url + 'gio-hang.html');
                    return false;
                } else {
                    alert("Sản phẩm này không tồn tại!");
                }
            });

        return false;
    });
});

$(document).ready(function() {
    $('#filter-by').on('change', function() {
        var param = 'filter';
        var this_value = $(this).val();
        var query_string_value = getQuerystring(param);
        var query_string_character = '?';

        if (document.location.search.length) {
            query_string_character = '&';
        }

        if (query_string_value === '') {
            window.location.href = document.URL + query_string_character + param + '=' + this_value;
        } else {
            window.location.href = updateQueryStringParameter(document.URL, param, this_value);
        }
    });

    $('#per-page-by').on('change', function() {
        var param = 'per_page';
        var this_value = $(this).val();
        var query_string_value = getQuerystring(param);
        var query_string_character = '?';

        if (document.location.search.length) {
            query_string_character = '&';
        }

        if (query_string_value === '') {
            window.location.href = document.URL + query_string_character + param + '=' + this_value;
        } else {
            window.location.href = updateQueryStringParameter(document.URL, param, this_value);
        }
    });
});

function getQuerystring(key, default_) {
    if (default_ == null)
        default_ = "";
    key = key.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + key + "=([^&#]*)");
    var qs = regex.exec(window.location.href);
    if (qs == null)
        return default_;
    else
        return qs[1];
}

function updateQueryStringParameter(uri, key, value) {
    var re = new RegExp("([?|&])" + key + "=.*?(&|#|$)", "i");
    if (uri.match(re)) {
        return uri.replace(re, '$1' + key + "=" + value + '$2');
    } else {
        var hash = '';
        var separator = uri.indexOf('?') !== -1 ? "&" : "?";
        if (uri.indexOf('#') !== -1) {
            hash = uri.replace(/.*#/, '#');
            uri = uri.replace(/#.*/, '');
        }
        return uri + separator + key + "=" + value + hash;
    }
}
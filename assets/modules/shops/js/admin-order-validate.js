tblAppendGrid = 'tblAppendGrid';
products_selected = [];
products_options = [];
rowIndexProcessing = 0;
$(function() {
    $('#' + tblAppendGrid).appendGrid({
        caption: 'HÓA ĐƠN BÁN HÀNG',
        initRows: 1,
        columns: [{
                name: 'product_id',
                display: 'Sản phẩm',
                type: 'custom',
                customBuilder: function(parent, idPrefix, name, uniqueIndex) {
                    var ctrlId = idPrefix + '_' + name + '_' + uniqueIndex;
                    var ctrl = document.createElement('select');
                    $.each(products, function(key, val) {
                        ctrl.add(createOption(key, val));
                    });
                    $(ctrl).attr({
                        'id': ctrlId,
                        'name': ctrlId,
                        'style': 'width: 500px;',
                        'class': 'select_on_change'
                    }).appendTo(parent).chosen({ enable_split_word_search: true });

                    return ctrl;
                },
                customGetter: function(idPrefix, name, uniqueIndex) {
                    return $('#' + idPrefix + '_' + name + '_' + uniqueIndex).chosen().val();
                },
                customSetter: function(idPrefix, name, uniqueIndex, value) {
                    $('#' + idPrefix + '_' + name + '_' + uniqueIndex).val(value).trigger('chosen:updated');
                }
            },
            {
                name: 'quantity',
                display: 'Số lượng',
                displayCss: { 'text-align': 'center', 'width': '80px' },
                type: 'text',
                ctrlAttr: { maxlength: 50, min: 1, class: 'quantity-auto-focus'},
                ctrlCss: { 'text-align': 'right' },
                value: 1,
                onChange: function(evt, rowIndex) {
                    changeValueRowIndex(rowIndex);
                }
            },
            /*{
                name: 'unit_price',
                display: 'Đơn giá',
                displayCss: { 'text-align': 'center', 'width': '120px' },
                type: 'hidden',
                ctrlAttr: { maxlength: 100, readonly: 'readonly', class: 'mask-number'},
                ctrlCss: { 'text-align': 'right' },
                value: 0
            },*/
            {
                name: 'promotion_price',
                display: 'Đơn giá',
                displayCss: { 'text-align': 'center', 'width': '120px' },
                type: 'text',
                ctrlAttr: { maxlength: 100, readonly: 'readonly', class: 'mask-number'},
                ctrlCss: { 'text-align': 'right' },
                value: 0
            },
            {
                name: 'VAT',
                display: 'VAT (%)',
                displayCss: { 'text-align': 'center', 'width': '50px' },
                type: 'text',
                ctrlAttr: { readonly: 'readonly'},
                ctrlCss: { 'text-align': 'right' },
                value: 0
            },
            {
                name: 'VAT_value',
                display: 'Thuế GTGT',
                displayCss: { 'text-align': 'center', 'width': '120px' },
                type: 'text',
                ctrlAttr: { readonly: 'readonly', class: 'mask-number'},
                ctrlCss: { 'text-align': 'right' },
                value: 0
            },
            {
                name: 'monetized',
                display: 'Thành tiền',
                displayCss: { 'text-align': 'center', 'color': '#ff0000' },
                type: 'text',
                ctrlAttr: { maxlength: 100, readonly: 'readonly', class: 'mask-number' },
                ctrlCss: { 'text-align': 'right' },
                value: 0
            },
            {
                name: 'unit_price',
                type: 'hidden',
                value: 0
            },
            {
                name: 'cost_price',
                type: 'hidden',
                value: 0
            },
            {
                name: 'product_price',
                type: 'hidden',
                value: 0
            }
        ],
        initData: product_init
    });

    if ($('#id').length) {
        openLastChosen();
    } else {
        var customer_id = $('#customer_id').val();
        if (customer_id == 0) {
            alert('Vui lòng chọn khách hàng!');
            openCustomerChosen();
            return false;
        }else{
            openLastChosen();
        }
    }
});


function createOption(value, text) {
    var option = document.createElement('option');
    option.text = text;
    option.value = value;
    return option;
}

function load_customer_info(id = 0) {
    if (id == 0) {
        return false;
    }
    var strURL = base_url + 'users/ajax_get';
    var data = {
        'id': id
    };
    $.ajax({
        url: strURL,
        type: 'POST',
        cache: false,
        data: data,
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                $('#address').text(response.content.address);
                $('#phone').text(response.content.phone);
                $('#email').text(response.content.email);
                $('.info-customer').removeClass('hide');
            } else {
                $('.info-customer').addClass('hide');
            }
        },
        error: function(e) {
            console.log('Lỗi: ' + e.responseText);
        }
    });
    return false;
}

$(document).ready(function() {
    $('.mask-number').mask('000,000,000', { reverse: true });
    setTotal();
    var customer_id = $('#customer_id').val();
    load_customer_info(customer_id);
    $('#f-content').on('submit', function() {
        var customer_id = $('#customer_id').val();
        if (customer_id == 0) {
            alert('Vui lòng chọn khách hàng!');
            openCustomerChosen();
            return false;
        }
        removeEmptyRows();

        var count = $('#' + tblAppendGrid).appendGrid('getRowCount');
        if(count == 0){
            alert('Vui lòng nhập sản phẩm cho đơn hàng');
            appendRow();
            return false;
        }
        var data = $(document.forms[0]).serialize() + '&' + $.param({ 'count': count });
        // console.log(data); return false;
        $('#processing-modal').modal('show');
        $.ajax({
            url:  base_url + 'admin/orders/content-ajax',
            type: 'POST',
            cache: false,
            data: data,
            dataType: 'json',
            success: function(response) {
                $('#processing-modal').modal('hide');
                alert(response.message);
                if (response.status == 'success') {
                    window.location.href = response.content.data;
                }
            },
            error: function(e) {
                $('#processing-modal').modal('hide');
                console.log('Lỗi: ' + e.responseText);
            }
        });

        return false;
    });

    $(".chosen-select").chosen({ enable_split_word_search: false });
    $(".chosen-select-enable-search").chosen({ search_contains: true });
});

$(document).on('change', '.select_on_change', function() {
    var attr_id = $(this).attr('id');
    var attr_arr = attr_id.split("_");
    var uniqueIndex = attr_arr[attr_arr.length - 1];
    var rowIndex = $('#' + tblAppendGrid).appendGrid('getRowIndex', uniqueIndex);
    //console.log('rowIndex: ' + rowIndex);
    var el_parent = $(this).closest('tr');
    var id = $(this).val();
    if (id == 0) {
        return false;
    }

    var customer_id = $('#customer_id').val();
    if (customer_id == 0) {
        alert('Vui lòng chọn khách hàng!');
        openCustomerChosen();
        return false;
    }

    var price_type = $('#' + tblAppendGrid).appendGrid('getCtrlValue', 'price_type', rowIndex);
    var quantity = parseInt($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'quantity', rowIndex));
    var data = {
        'id': id,
        'price_type': price_type
    };
    $.ajax({
        url: base_url + 'products/ajax_get',
        type: 'POST',
        cache: false,
        data: data,
        dataType: 'json',
        success: function(response) {
            console.log(response);
            var content = response.content;
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'unit_price', rowIndex, numberFormat(content.price));
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'product_price', rowIndex, numberFormat(content.promotion_price));
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'promotion_price', rowIndex, numberFormat(content.price_before_tax));
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'cost_price', rowIndex, numberFormat(content.product_cost_price));
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'VAT', rowIndex, content.VAT_percent);
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'VAT_value', rowIndex, numberFormat(content.VAT_value));
            $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'monetized', rowIndex, numberFormat(quantity * parseFloat(content.promotion_price)));
            changeValidateRow(rowIndex);
            setTotal();
            appendRow();
            el_parent.find('.quantity-auto-focus').focus();
        },
        error: function(e) {
            console.log('Lỗi: ' + e.responseText);
        }
    });
    return false;
});

$(document).on('keypress', '.quantity-auto-focus', function(event){
    var keycode = event.keyCode ? event.keyCode : event.which;
    if(keycode == '13'){
        openLastChosen();
        event.stopPropagation();
        return false;
    }else if (!(keycode == 46 || keycode == 8 || keycode == 37 || keycode == 39 || (keycode >= 48 && keycode <= 57))) {
        event.preventDefault();
    }
});

function removeA(arr) {
    var what, a = arguments, L = a.length, ax;
    while (L > 1 && arr.length) {
        what = a[--L];
        while ((ax= arr.indexOf(what)) !== -1) {
            arr.splice(ax, 1);
        }
    }
    return arr;
}

function changeValidateRow(rowIndex) {
    let product_id_current = parseInt($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'product_id', rowIndex));

    var checkErrors = -1;
    var count = $('#' + tblAppendGrid).appendGrid('getRowCount');
    for (var z = 0; z < count; z++) {
        let product_id = parseInt($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'product_id', z));
        if(product_id === product_id_current){
            checkErrors++;
        }
    }
    if(checkErrors > 0){
        alert('Sản phẩm này đã tồn tại. Vui lòng chọn sản phẩm khác!');
        removeRow(rowIndex);
    }
}

function changeValueRowIndex(rowIndex) {
    var quantity = parseInt($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'quantity', rowIndex));
    var promotion_price = parseFloat(numberFormatDecode($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'promotion_price', rowIndex)));
    var product_price = parseFloat(numberFormatDecode($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'product_price', rowIndex)));
    var monetized_before_tax = quantity * promotion_price;
    var monetized = quantity * product_price;
    $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'VAT_value', rowIndex, numberFormat(monetized - monetized_before_tax));
    $('#' + tblAppendGrid).appendGrid('setCtrlValue', 'monetized', rowIndex, numberFormat(monetized));
    setTotal();
}

function openLastChosen() {
    $('.select_on_change').last().chosen().trigger('chosen:open');
}

function openCustomerChosen() {
    $('#customer_id').chosen().trigger('chosen:open');
}

function removeEmptyRows() {
    var count = $('#' + tblAppendGrid).appendGrid('getRowCount');
    for (var i = 0; i < count; i++) {
        if ($('#' + tblAppendGrid).appendGrid('getCtrlValue', 'product_id', i) == 0) {
            $('#' + tblAppendGrid).appendGrid('removeRow', i);
        }
    }
}

function removeRow(rowIndex) {
    $('#' + tblAppendGrid).appendGrid('removeRow', rowIndex);
}

function appendRow() {
    removeEmptyRows();
    $('#' + tblAppendGrid).appendGrid('appendRow', 1);
    openLastChosen();
}

function setTotal() {
    var total_number = 0;
    var total_VAT = 0;
    var data = $('#' + tblAppendGrid).appendGrid('getAllValue');
    for (var i = 0; i < data.length; i++) {
        var monetized = parseFloat(numberFormatDecode(data[i].monetized));
        var VAT_value = parseFloat(numberFormatDecode(data[i].VAT_value));
        if (isNaN(monetized)) {
            monetized = 0;
        }
        if (isNaN(VAT_value)) {
            VAT_value = 0;
        }
        total_number += monetized;
        total_VAT += VAT_value;
    }
    if (total_number > 0) {
        var order_discount_percent = order_discount = order_monetized = 0;

        /*if(total_number >= 4000000){
            order_discount_percent = 10;
            console.log('Giảm giá: ' + order_discount_percent);
        }else if(total_number >= 2000000){
            order_discount_percent = 5;
            console.log('Giảm giá: ' + order_discount_percent);
        }*/
        order_discount = total_number * order_discount_percent / 100;
        order_monetized = total_number - order_discount;
        $('#total_VAT').text(numberFormat(total_VAT));
        $('#total_number').text(numberFormat(total_number));
        $('#total_discount').text((order_discount > 0 ? '-' : '') + numberFormat(order_discount));
        $('#total_discount_percent').text(order_discount_percent + '%');
        $('#total_monetized').text(numberFormat(order_monetized));

        var timeout;
        var processing = false;
        timeout = setTimeout(function() {
            if (!processing) {
                var total_monetized_text = $.trim(docso(order_monetized));
                total_monetized_text = capitalizeFirstLetter(total_monetized_text);
                $('#total_monetized_text').text(total_monetized_text);
            }
        }, 300);
        return false;
    }
}

function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}

mangso = ['không', 'một', 'hai', 'ba', 'bốn', 'năm', 'sáu', 'bảy', 'tám', 'chín'];

function dochangchuc(so, daydu) {
    var chuoi = "";
    chuc = Math.floor(so / 10);
    donvi = so % 10;
    if (chuc > 1) {
        chuoi = " " + mangso[chuc] + " mươi";
        if (donvi == 1) {
            chuoi += " mốt";
        }
    } else if (chuc == 1) {
        chuoi = " mười";
        if (donvi == 1) {
            chuoi += " một";
        }
    } else if (daydu && donvi > 0) {
        chuoi = " lẻ";
    }
    if (donvi == 5 && chuc >= 1) {
        chuoi += " lăm";
    } else if (donvi > 1 || (donvi == 1 && chuc == 0)) {
        chuoi += " " + mangso[donvi];
    }
    return chuoi;
}

function docblock(so, daydu) {
    var chuoi = "";
    tram = Math.floor(so / 100);
    so = so % 100;
    if (daydu || tram > 0) {
        chuoi = " " + mangso[tram] + " trăm";
        chuoi += dochangchuc(so, true);
    } else {
        chuoi = dochangchuc(so, false);
    }
    return chuoi;
}

function dochangtrieu(so, daydu) {
    var chuoi = "";
    trieu = Math.floor(so / 1000000);
    so = so % 1000000;
    if (trieu > 0) {
        chuoi = docblock(trieu, daydu) + " triệu";
        daydu = true;
    }
    nghin = Math.floor(so / 1000);
    so = so % 1000;
    if (nghin > 0) {
        chuoi += docblock(nghin, daydu) + " nghìn";
        daydu = true;
    }
    if (so > 0) {
        chuoi += docblock(so, daydu);
    }
    return chuoi;
}

function docso(so) {
    if (so == 0)
        return mangso[0];
    var chuoi = "",
        hauto = "";
    do {
        ty = so % 1000000000;
        so = Math.floor(so / 1000000000);
        if (so > 0) {
            chuoi = dochangtrieu(ty, true) + hauto + chuoi;
        } else {
            chuoi = dochangtrieu(ty, false) + hauto + chuoi;
        }
        hauto = " tỷ";
    } while (so > 0);
    return chuoi + ' đồng';
}

function numberFormat(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,");
}

function numberFormatDecode(num) {
    return num.toString().replace(/,/g, "");
}

$(document).on('change', '#customer_id', function() {
    var id = $(this).val();
    load_customer_info(id);
});
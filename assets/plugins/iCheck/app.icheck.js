$(document).ready(function() {
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
        checkboxClass: 'icheckbox_minimal-blue',
        radioClass: 'iradio_minimal-blue'
    });

    //Flat red color scheme for iCheck
    $('input[type="checkbox"].flat-blue, input[type="radio"].flat-blue').iCheck({
        checkboxClass: 'icheckbox_flat-blue',
        radioClass: 'iradio_flat-blue'
    });
	
	
	var checkAll = $('input[type="checkbox"].check-all');
    var checkboxes = $('input[type="checkbox"].check');
    
    checkAll.on('ifChecked ifUnchecked', function(event) {        
        if (event.type == 'ifChecked') {
            checkboxes.iCheck('check');
        } else {
            checkboxes.iCheck('uncheck');
        }
    });
    
    checkboxes.on('ifChanged', function(event){
        if(checkboxes.filter(':checked').length == checkboxes.length) {
            checkAll.prop('checked', 'checked');
        } else {
            checkAll.removeProp('checked');
        }
        checkAll.iCheck('update');
    });

    set_link_active();
});

function set_link_active() {
    var pathname = window.location.pathname;
    var pathname = pathname.split('/');
    var curent_link = '';

    for (var i = 2; i < pathname.length; i++) {
        if (!isNaN(pathname[i])) {//isNaN(param) neu khong phai so thì tre ve true
            pathname.splice(i, 1); //xoa phan tu tai vi tri i
        }
    }

    for (var i = 2; i < pathname.length; i++) {
        curent_link += pathname[i];
        if (i !== (pathname.length - 1)) {
            curent_link += '/';
        }
    }

    curent_link = base_url + curent_link;

    $('ul.sidebar-menu a').each(function() {
        var test = $(this).attr('href');
        if (test === curent_link) {
            $(this).parent().addClass('active');
            //$(this).closest('li').children('a').removeClass('active');
        }
    });
}
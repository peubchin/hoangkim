<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

$config['facebook'] = array(
	'app_id' => '124350198151002',
	'app_secret' => '5aa3c4ceea848e3a9bc9bec1d41525a0',
);
$config['site_per_page'] = array(
	'posts' => 12,
);
$config['admin_list'] = array(
	'item' => 10,
	'total' => 100,
);
$config['num_links'] = 9;
/*
$config['sms'] = array(
	'api_key' => 'F26C86EDB6D67F6E847FDF5C2A3698',
	'secret_key' => '9463FD47461066669D2C6F4C62FF92',
	'expired' => 120,
);
*/
$config['toolbar'] = array(
	'full' => array(
		array('Source', 'Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'),
		array('Bold', 'Italic', 'Underline', 'Strike', '-', 'Subscript', 'Superscript'),
		array('NumberedList', 'BulletedList', '-', 'Outdent', 'Indent'),
		array('JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'),
		array('Link', 'Unlink'),
		array('Image', 'Table', 'HorizontalRule'),
		array('Styles', 'Format', 'Font', 'FontSize'),
		array('TextColor', 'BGColor'),
	),
	'mini' => array(
		array('Bold', 'Italic', 'Underline', 'Strike'),
		array('Link', 'Unlink'),
		array('Styles', 'Format', 'Font', 'FontSize'),
		array('TextColor', 'BGColor'),
	),
);
$config['transaction_status'] = array(
	'0' => 'Chưa thanh toán',
	'1' => 'Đang thanh toán, chờ phản hồi',
	'4' => 'Đã thanh toán, tiền đã nhận',
);
$config['orders_transaction_status'] = array(
	'-1' => 'Đã huỷ',
	'0' => 'Chờ thanh toán',
	'1' => 'Đã thanh toán',
);
$config['orders_transaction_status_label'] = array(
	'-1' => 'danger',
	'0' => 'warning',
	'1' => 'success',
);
$config['per_page'] = 9;
$config['gender'] = array(
	'default' => 'N',
	'data' => array(
		"N" => "N/A",
		"M" => "Nam",
		"F" => "Nữ",
	),
);
$config['oauth_provider'] = array(
	'system' => 'Hệ thống',
	'google' => 'Google',
	'facebook' => 'Facebook',
);
$config['delivery_method'] = array(
	'1' => 'Giao tận nhà',
	'2' => 'Khách nhận hàng tại công ty',
);
/*
$config['forms_of_payment'] = array(
'1' => 'Thanh toán tiền mặt',
'2' => 'Chuyển khoản',
);
 */
$config['forms_of_payment'] = array(
	// 'cod' => array(
	// 	'title' => 'Thanh toán tiền mặt khi nhận hàng',
	// 	'info' => 'Thanh toán trực tiếp cho người vận chuyển, ngay sau khi nhận được hàng',
	// ),
	'bacs' => array(
		'title' => 'Chuyển khoản ngân hàng',
		'info' => 'Số tài khoản: 19135781619012 <br>TRƯƠNG HUỲNH NGỌC ANH <br>
			    Ngân hàng: Techcombank - Chi nhánh HCM <br> Nội dung: Mã Đơn hàng.... hoặc username'
	),
	// 'cheque' => array(
	// 	'title' => 'Thanh toán tại cửa hàng',
	// 	'info' => 'Địa chỉ: 145/3E Tô ký, P.Tân Chánh Hiệp, Q.12',
	// ),
);
$config['email_type'] = array(
	'post' => 'Tin tức',
	'page' => 'Trang tĩnh',
	'contact' => 'Liên hệ',
);
$config['images_modules'] = array(
	'slideshow' => 'Slide show',
	'advertise_right_top' => 'Quảng cáo dưới slider',
	'advertise_sidebar' => 'Quảng cáo cột trái',
	'advertise_bottom' => 'Quảng cáo bottom',
	'bannerbottom' => 'Banner Bottom',
	'partner' => 'Đối tác',
	'service' => 'Dịch vụ',
	'advertise' => 'Quảng cáo',
	'footer_introdue' => 'Giới thiệu Footer',
	'login_introdue' => 'Giới thiệu Đăng nhập / Đăng ký',
);
$config['info_modules'] = array(
	'welcome' => 'Welcome',
	'companyname' => 'Tên công ty',
	'noprice_infomation' => 'Thông tin khi không nhập giá sản phẩm',
	'address_center' => 'Văn phòng đại diện',
	'service_footer' => 'Giải pháp / Dịch vụ',
	'copyright' => 'Copyright',
	'contact' => 'Liên hệ',
	'address' => 'Địa chỉ',
	'certify' => 'Chứng nhận',
	'partner' => 'Đối tác',
	'hotline_dvkh' => 'DVKH Hotline',
	'email_dvkh' => 'DVKH Email',
	'skype_dvkh' => 'DVKH Zalo',
	'hotline_scroll' => 'Hotline Theo Web',
	'link_video' => 'Link video hướng dẫn',
	'content_main' => 'Nội dung trang chủ(dưới slide)',
	'result_order' => 'Nội dung thanh toán',
);
$config['menu_modules'] = array(
	'Main' => 'Menu chính',
	'Bottom' => 'Menu chính sách',
	'Left' => 'Menu chăm sóc khách hàng',
	'Right' => 'Menu tuyển dụng',
);
$config['search_price'] = array(
    '0-1000000' => 'Dưới 1 triệu',
    '1000000-2000000' => 'Từ 1 - 2 triệu',
    '2000000-3000000' => 'Từ 2 - 3 triệu',
    '3000000-4000000' => 'Từ 3 - 4 triệu',
    '4000000-5000000' => 'Từ 4 - 5 triệu',
    '5000000-10000000' => 'Từ 5 - 10 triệu',
    '10000000-0' => 'Trên 10 triệu'
);
$config['stock_status'] = array(
    'instock' => 'Còn hàng',
    'outofstock' => 'Hết hàng',
    'onbackorder' => 'Chờ hàng'
);
$config['stock_status_display'] = array(
    'instock' => '<span class="instock">Còn hàng</span>',
    'outofstock' => '<span class="outofstock">Hết hàng</span>',
    'onbackorder' => '<span class="onbackorder">Chờ hàng</span>'
);
$config['role'] = array(
	//'MEMBER' => 'Thành viên',
    'AGENCY' => 'Đại lý',
    'SUPPORT' => 'Hoàng Kim hỗ trợ',
    'HOTLINE' => 'Hoàng Kim hotline',
    'ACCOUNTANT' => 'Kế toán',
    'ADMIN' => 'Admin',
);
$config['role_label'] = array(
	//'MEMBER' => 'default',
    'AGENCY' => 'primary',
    'SUPPORT' => 'info',
    'HOTLINE' => 'info',
    'ACCOUNTANT' => 'warning',
    'ADMIN' => 'danger',
);
$config['role_group'] = array(
	//'MEMBER' => 'default',
    'AGENCY' => 3,
    'SUPPORT' => 8,
    'HOTLINE' => 9,
    'ACCOUNTANT' => 5,
    'ADMIN' => 6,
);
$config['role_access_admin'] = array(
	'ADMIN',
    'SUPPORT',
    'HOTLINE',
    'ACCOUNTANT',
);
$config['users_modules_commission'] = array(
	'BUY' => 'Mua hàng',
	'SUB_BUY' => 'Nhận phần trăm từ cấp dưới',
	'SUB_BUY_ROOT' => 'Nhận phần trăm trực tiếp từ cấp dưới',
	'PAY_IN' => 'Nạp tiền vào tài khoản',
	// 'BUY_SYSTEM' => 'Trả tiền cho hệ thống',
	'SELL' => 'Bán hàng',
	'WITHDRAWAL' => 'Rút tiền',
	'WITHDRAWAL_FEE' => 'Thuế thu nhập cá nhân',
	'REVENUE_BONUS' => 'Thưởng doanh số',
	'WITHDRAWAL_BONUS' => 'Rút tiền thưởng',
	'WITHDRAWAL_BONUS_FEE' => 'Thuế thu nhập cá nhân rút tiền thưởng',
	// 'SYSTEM' => 'Nhận phần trăm từ người dùng mua sản phẩm',
	// 'TRANSFER' => 'Chuyển tiền cho người dùng khác',
	// 'TRANSFERED' => 'Nhận tiền cho người dùng khác chuyển',
);
$config['users_modules_commission_label'] = array(
	'BUY' => 'danger',
	'SUB_BUY' => 'success',
	'SUB_BUY_ROOT' => 'success',
	'PAY_IN' => 'primary',
	// 'BUY_SYSTEM' => 'warning',
	'SELL' => 'info',
	'WITHDRAWAL' => 'danger',
	'WITHDRAWAL_FEE' => 'danger',
	'REVENUE_BONUS' => 'success',
	'WITHDRAWAL_BONUS' => 'danger',
	'WITHDRAWAL_BONUS_FEE' => 'danger',
	// 'SYSTEM' => 'default',
	// 'TRANSFER' => 'danger',
	// 'TRANSFERED' => 'primary',
);
$config['users_modules_commission_status'] = array(
	'-1' => 'Đã hủy yêu cầu',
	'0' => 'Đã yêu cầu',
	'1' => 'Khả dụng',
);
$config['users_modules_commission_status_label'] = array(
	'-1' => 'danger',
	'0' => 'warning',
	'1' => 'success',
);
$config['order_status'] = array(
	'-1' => 'Đã hủy',
	'0' => 'Chờ thanh toán',
	'1' => 'Đã thanh toán',
);
$config['report_type'] = array(
    'YEAR' => 'Năm',
    'QUARTER' => 'Quý',
    'MONTH' => 'Tháng',
    'DAY' => 'Ngày',
);

$config['report_type_quarter'] = array(
    '1' => 'Quý 1',
    '2' => 'Quý 2',
    '3' => 'Quý 3',
    '4' => 'Quý 4',
);

$config['report_type_month'] = array(
    '1' => '01',
    '2' => '02',
    '3' => '03',
    '4' => '04',
    '5' => '05',
    '6' => '06',
    '7' => '07',
    '8' => '08',
    '9' => '09',
    '10' => '10',
    '11' => '11',
    '12' => '12',
);
$config['shops_VAT'] = array(
	'DEFAULT' => 'Không',
	'VAT8' => '8%',
	'VAT10' => '10%',
);
$config['shops_VAT_value'] = array(
	'DEFAULT' => 0,
	'VAT8' => 8,
	'VAT10' => 10,
);
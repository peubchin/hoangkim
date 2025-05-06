<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

if (!function_exists('debug_arr')) {
    function debug_arr($arr, $bool = false){
        echo "<pre>";
        print_r($arr);
        echo "<pre>";
        if($bool){
            die;
        }
    }
}

if(!function_exists('pre')){
    function pre($data, $bool = false){
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        if($bool){
            die;
        }
    }
}

if(!function_exists('get_CI')){
    function get_CI(){
        $CI =& get_instance();
        
        return $CI;
    }
}

if (!function_exists('get_segment')) {
    function get_segment($segment = 1) {
        $CI = get_CI();

        return ($CI->uri->segment($segment) == '') ? '' : $CI->uri->segment($segment);
    }
}

if (!function_exists('generate_qr_code')) {
    function generate_qr_code($qr_code = ''){
        $response = array(
            'status' => 'warning',
            'content' => NULL,
            'message' => 'Kiểm tra thông tin nhập'
        );

        if(trim($qr_code) != ''){
            $CI =& get_instance();

            $CI->load->library('ciqrcode');
            $file_name = $qr_code . '.png';

            $params = array();
            $params['data'] = site_url('dang-ky') . '?ref=' . $qr_code;
            $params['level'] = 'L';
            $params['size'] = 3;
            $params['savename'] = get_module_path('users_qr') . $file_name;

            if($CI->ciqrcode->generate($params)){
                $response = array(
                    'status' => 'success',
                    'content' => $params['savename'],
                    'message' => 'Tạo mã QR Code thành công!'
                );
            } else {
                $response = array(
                    'status' => 'error',
                    'content' => NULL,
                    'message' => 'Có lỗi xảy ra! Vui lòng kiểm tra lại!'
                );
            }
        }

        return $response;
    }
}

if (!function_exists('get_config_value')) {
    function get_config_value($config_name = '') {
        $CI = &get_instance();
        $row = $CI->M_configs->get($config_name);
        return isset($row['config_value']) ? $row['config_value'] : '';
    }
}

if (!function_exists('gets_config_value')) {
    function gets_config_value($config_names = null) {
        $CI =& get_instance();
        return $CI->M_configs->gets($config_names);
    }
}

if (!function_exists('get_price_before_tax')) {
    function get_price_before_tax($price = 0, $VAT = 0, $quantity = 1) {
        $total = $price;
        if($price > 0 && $VAT > 0){
            $total = ($price / (1 + $VAT / 100)) * $quantity;
        }

        return $total;
    }
}

if (!function_exists('get_money_VND')) {
    function get_money_VND($total = 0) {
        return formatRice($total / 1000) . 'k';
    }
}

if (!function_exists('get_VAT_percent_product')) {
    function get_VAT_percent_product($VAT_product = 'DEFAULT') {
        $CI =& get_instance();
        return (float) display_value_array($CI->config->item('shops_VAT_value'), $VAT_product);
    }
}

if (!function_exists('get_VAT_product')) {
    function get_VAT_product($price = 0, $VAT_product = 'DEFAULT', $quantity = 1) {
        $total = 0;
        $VAT = get_VAT_percent_product($VAT_product);
        //var_dump($VAT);
        if($price > 0 && $VAT > 0){
            $total = ($price / (1 + $VAT / 100)) * $quantity;
        }

        return $total;
    }
}

if (!function_exists('send_sms')) {

    function send_sms($phone = '', $content = '', $options = NULL){
		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Cấu hình không hợp lệ';
		if(trim($phone) == '' || trim($content) == ''){
			return json_encode($message);
		}
        $ci = &get_instance();
		$sms_api_key = $ci->config->item('api_key', 'sms');
		$sms_secret_key = $ci->config->item('secret_key', 'sms');
        $sms_brandname = isset($options['brandname']) ? $options['brandname'] : $ci->config->item('brandname', 'sms');
        $sms_type = isset($options['type']) ? (int)$options['type'] : 2;
        $ch = curl_init();
		$SampleXml = "<RQST>"
						. "<APIKEY>". $sms_api_key ."</APIKEY>"
						. "<SECRETKEY>". $sms_secret_key ."</SECRETKEY>"
						. "<ISFLASH>0</ISFLASH>"
						. "<SMSTYPE>" . $sms_type . "</SMSTYPE>"
						. "<CONTENT>". $content ."</CONTENT>"
						//. "<BRANDNAME>QCAO_ONLINE</BRANDNAME>"
                        . "<BRANDNAME>" . $sms_brandname . "</BRANDNAME>"
						. "<CONTACTS>"
						. "<CUSTOMER>"
						. "<PHONE>". $phone ."</PHONE>"
						. "</CUSTOMER>"
						. "</CONTACTS>"
					. "</RQST>";
		curl_setopt($ch, CURLOPT_URL,            "http://api.esms.vn/MainService.svc/xml/SendMultipleMessage_V4/");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST,           1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,     $SampleXml);
		curl_setopt($ch, CURLOPT_HTTPHEADER,     array('Content-Type: text/plain'));

		$result = curl_exec($ch);
		$response = simplexml_load_string($result);
		if ($response === FALSE) {
	    	$message['status'] = 'danger';
			$message['content'] = $data;
			$message['message'] = 'Lỗi: ' . curl_error($ch);
		} elseif($response->CodeResult == 100) {
			$message['status'] = 'success';
			$message['content'] = $response;
			$message['message'] = 'Gửi tin nhắn thành công';
		} else {
			$message['status'] = 'danger';
			$message['content'] = $response;
			$message['message'] = 'Gửi tin nhắn không thành công';
		}
	    curl_close($ch);
	    return json_encode($message);
	}

}

if (!function_exists('get_start_date')) {

    function get_start_date($str_date) {
        $date = parse_date($str_date) . " 00:00:00";

        return strtotime($date);
    }

}

if (!function_exists('get_end_date')) {

    function get_end_date($str_date) {
        $date = parse_date($str_date) . " 23:59:59";

        return strtotime($date);
    }

}

if (!function_exists('get_current_date')) {

    function get_current_date($str_date) {
        $date = parse_date($str_date) . " " . date('H:i:s');
        return strtotime($date);
    }

}

if (!function_exists('convert_str_to_date')) {

    function convert_str_to_date($str = '', $separator = '/') {
        $timestamp = time();
        $dates = explode($separator, $str);
        if(isset($dates[0]) && isset($dates[1]) && isset($dates[2])){
            $year = $dates[2];
            if($year < 100){
                $year += 2000;
            }
            $timestamp = strtotime($year . "-" . $dates[1] . "-" . $dates[0]);
        }

        return $timestamp;
    }

}

if (!function_exists('parse_date')) {

    function parse_date($str_date, $separator = '-') {
        $dates = explode($separator, $str_date);
        $str_date = $dates[2] . "-" . $dates[1] . "-" . $dates[0];

        return $str_date;
    }

}

if (!function_exists('get_info_withdrawal_user')) {
    function get_info_withdrawal_user($user_id = 0) {
        $data = null;
        $ci = &get_instance();
        $user = $ci->M_users->get_info($user_id);
        if(is_array($user) && !empty($user)){
            $data = array(
                'phone' => isset($user['phone']) ? $user['phone'] : '',
                'account_holder' => isset($user['account_holder']) ? $user['account_holder'] : '',
                'account_number' => isset($user['account_number']) ? $user['account_number'] : '',
                'bank_name' => isset($user['bank_name']) ? $user['bank_name'] : '',
                'bank_branch' => isset($user['branch_bank']) ? $user['branch_bank'] : '',
            );
        }
        return $data;
    }
}

if (!function_exists('get_commission_user')) {
    function get_commission_user($user_id = 0, $not_in_id = 0, $options = array()) {
        $ci = &get_instance();

        $pay_in_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('PAY_IN')
        );

        $withdrawal_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('WITHDRAWAL'),
            'not_in_id' => $not_in_id,
        );

        $withdrawal_fee_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('WITHDRAWAL_FEE'),
            'not_in_id' => $not_in_id,
        );

        $total_commission_buy_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('SUB_BUY', 'SUB_BUY_ROOT')
        );

        if(isset($options['date_start'])){
            $start_date_start = get_start_date($options['date_start']);
            $pay_in_args['start_date_start'] = $start_date_start;
            $withdrawal_args['start_date_start'] = $start_date_start;
            $withdrawal_fee_args['start_date_start'] = $start_date_start;
            $total_commission_buy_args['start_date_start'] = $start_date_start;
        }

        $pay_in = abs($ci->M_users_commission->get_total($pay_in_args));

        $withdrawal = abs($ci->M_users_commission->get_total($withdrawal_args));
        $withdrawal_fee = abs($ci->M_users_commission->get_total($withdrawal_fee_args));

        /*
        $transfer = abs($ci->M_users_commission->get_total(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('TRANSFER')
        )));

        $transfered = abs($ci->M_users_commission->get_total(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('TRANSFERED')
        )));
        */

        $total_commission_buy = abs($ci->M_users_commission->get_total($total_commission_buy_args));

        /*
        $total_buy = abs($ci->M_users_commission->get_total(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY')
        )));

        $total_buy_credit_card = abs($ci->M_users_commission->get_total(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY'),
            'payment' => 'CREDIT_CARD',
        )));

        $total_buy_system = abs($this->M_users_commission->_get_total_value(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY_SYSTEM')
        )));
        */

        // $balance = $pay_in + $total_commission_buy + $transfered - $total_buy_system - $withdrawal - $transfer;
        $balance = $pay_in + $total_commission_buy - $withdrawal - $withdrawal_fee;

        return array(
            'total_pay_in' => $pay_in,
            'total_withdrawal' => $withdrawal,
            'total_withdrawal_fee' => $withdrawal_fee,
            'total_commission_buy' => $total_commission_buy,
            // 'total_buy' => $total_buy,
            // 'total_buy_credit_card' => $total_buy_credit_card,
            // 'total_buy_system' => $total_buy_system,
            'balance' => $balance,
        );
    }
}

if (!function_exists('get_balance_user')) {
    function get_balance_user($user_id = 0, $not_in_id = 0, $options = array()) {
        $data = get_commission_user($user_id, $not_in_id, $options);
        return isset($data['balance']) ? $data['balance'] : 0;
    }
}

if (!function_exists('get_total_commission_user')) {
    function get_total_commission_user($user_id = 0) {
        $ci = &get_instance();

        // $date = '01-08-2021';
        // $date = '25-10-2021';
        // $start_date_start = get_start_date($date);

        $buy = abs($ci->M_users_commission->get_total(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('SUB_BUY_ROOT', 'SUB_BUY'),
            // 'start_date_start' => $start_date_start
        )));

        return $buy;
    }
}

if (!function_exists('get_buy_user')) {
    function get_buy_user($user_id = 0) {
        $ci = &get_instance();

        // $date = '01-08-2021';
        $date = '25-10-2021';
        $start_date_start = get_start_date($date);

        $buy = abs($ci->M_users_commission->get_total(array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY'),
            'start_date_start' => $start_date_start
        )));

        return $buy;
    }
}

if (!function_exists('get_accumulated_user')) {
    function get_accumulated_user($user_id = 0, $date = '') {
        $ci = &get_instance();

        //$date = '25-10-2021';

        $args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY')
        );

        if(trim($date) != ''){
            $args['start_date_start'] = get_start_date($date);
        }

        return abs($ci->M_users_commission->get_accumulated($args));
    }
}

if (!function_exists('get_accumulated_user_id')) {
    function get_accumulated_user_id($user_id = 0, $options = array()) {
        $ci = &get_instance();

        $args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY')
        );

        if(isset($options['start_date_start'])){
            $args['start_date_start'] = $options['start_date_start'];
        }

        if(isset($options['start_date_end'])){
            $args['start_date_end'] = $options['start_date_end'];
        }

        return abs($ci->M_users_commission->get_accumulated($args));
    }
}

if (!function_exists('get_accumulated_commission_user_id')) {
    function get_accumulated_commission_user_id($user_id = 0, $options = array()) {
        $ci = &get_instance();

        $args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('BUY')
        );

        if(isset($options['start_date_start'])){
            $args['start_date_start'] = $options['start_date_start'];
        }

        if(isset($options['start_date_end'])){
            $args['start_date_end'] = $options['start_date_end'];
        }

        return abs($ci->M_users_commission->get_value_real($args));
    }
}

if (!function_exists('get_current_revenue_bonus_user')) {
    function get_current_revenue_bonus_user($user_id = 0, $not_in_id = 0) {
        $ci = &get_instance();

        $revenue_bonus_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('REVENUE_BONUS')
        );

        $withdrawal_bonus_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('WITHDRAWAL_BONUS'),
            'not_in_id' => $not_in_id,
        );

        $withdrawal_bonus_fee_args = array(
            'user_id' => $user_id,
            'status' => 1,
            'in_action' => array('WITHDRAWAL_BONUS_FEE'),
            'not_in_id' => $not_in_id,
        );

        $total_revenue_bonus = abs($ci->M_users_commission->get_total($revenue_bonus_args));
        $withdrawal_bonus = abs($ci->M_users_commission->get_total($withdrawal_bonus_args));
        $withdrawal_bonus_fee = abs($ci->M_users_commission->get_total($withdrawal_bonus_fee_args));

        return $total_revenue_bonus - ($withdrawal_bonus + $withdrawal_bonus_fee);
    }
}

if (!function_exists('show_treetable_users')) {

    function show_treetable_users($users, $parent_id = 0, $stt = 0, $total = 0){
        $cate_child = array();
        foreach ($users as $key => $item){
            if ($item['referred_by'] == $parent_id){
                $cate_child[] = $item;
                unset($users[$key]);
            }
        }
        if ($cate_child){
            $stt++;
            if ($stt == 11){
                return;
            }
            $parent = '';
            foreach ($cate_child as $key => $item){
                // $revenue = $item['revenue'];
                // $commission = $item['commission'];
                // $total += $commission;
                $root = $item['userid'];
                $revenue = formatRice(get_buy_user($root));
                // $revenue = formatRice(get_total_commission_user($root));
                // $revenue = formatRice(get_accumulated_user($root));
                if ($stt > 1){
                    $parent = $item['referred_by'];
                }
                echo '<tr>
                        <td>
                            <div class="tt" data-tt-id="' . $root . '" data-tt-parent="' . $parent . '">' . $item['full_name'] . ' - ' . $item['username'] . ' - ' . $revenue . '</div>
                        </td>
                    </tr>';
                show_treetable_users($users, $item['userid'], $stt, $total);
            }
        }
    }

}

if (!function_exists('get_parent_level')) {

    function get_parent_level($users, $user_id = 0, $stt = 0){
        $subs_parent = array();
        foreach ($users as $key => $item){
            if ($item['userid'] == $user_id){
                $parent_id = $item['referred_by'];
                foreach ($users as $user){
                    if ($user['userid'] == $parent_id){
                        $stt++;
                        if ($stt == 12){
                            return;
                        }
                        $subs_parent = get_parent_level($users, $parent_id, $stt);
                        $subs_parent[] = $parent_id;
                    }
                }
            }
        }
        return $subs_parent;
    }

}

if (!function_exists('get_data_parent_level')) {

    function get_data_parent_level($users, $user_id = 0){
        $data_parent = array(
            'root' => 0,
            'subs' => 0
        );
        $data = get_parent_level($users, $user_id);
        if(is_array($data) && !empty($data)){
            $root_id = end($data);
            array_pop($data);
            arsort($data);
            $data = array_values($data);
            $data_parent = array(
                'root' => $root_id,
                'subs' => $data
            );
        }
        return $data_parent;
    }

}

if (!function_exists('current_full_url')) {
    function current_full_url() {
        $CI = &get_instance();
        $url = $CI->config->site_url($CI->uri->uri_string());
        return $_SERVER['QUERY_STRING'] ? $url . '?' . $_SERVER['QUERY_STRING'] : $url;
    }
}

if (!function_exists('get_first_element')) {
    function get_first_element($data = '') {
        $str = $data;
        if (is_array($data) && !empty($data)) {
            $str = reset($data);
        }
        return $str;
    }
}

/*
 * Importion: This is change page url admin
 */
if (!function_exists('get_admin_url')) {

    function get_admin_url($module_slug = '') {
        $html = '';
        $ci = & get_instance();
        $base_url = $ci->config->item('base_url');
        $html .= $base_url . 'admin';
        if (trim($module_slug) != '') {
            $html .= '/' . $module_slug;
        }

        return $html;
    }

}

if (!function_exists('add_css')) {

    function add_css($names = array()) {
        $html = '';
        $data = array();
        if (is_array($names) && !empty($names)) {
            foreach ($names as $value) {
                $data[] = '<link href="' . get_asset('css_path') . $value . '.css" type="text/css" rel="stylesheet" />';
            }
        }

        if (is_array($data) && !empty($data)) {
            $html = implode("\n\t\t", $data);
        }

        return $html;
    }

}

if (!function_exists('add_js')) {

    function add_js($names = array()) {
        $html = '';
        $data = array();
        if (is_array($names) && !empty($names)) {
            foreach ($names as $value) {
                $data[] = '<script type="text/javascript" src="' . get_asset('js_path') . $value . '.js"></script>';
            }
        }

        if (is_array($data) && !empty($data)) {
            $html = implode("\n\t\t", $data);
        }

        return $html;
    }

}

if (!function_exists('create_folder')) {

    function create_folder($path_folder = 'uploads/', $create_index_file = true) {
        if (!is_dir($path_folder)) {
            mkdir('./' . $path_folder, 0777, TRUE);
            if ($create_index_file) {
                $index_file = 'index.html';
                copy_file('uploads/' . $index_file, $path_folder . '/' . $index_file);
            }
        }
    }

}

if (!function_exists('copy_file')) {

    function copy_file($from_file, $to_file, $delete = false) {
        $file = FCPATH . $from_file;
        $newfile = FCPATH . $to_file;
        copy($file, $newfile);
        if ($delete) {
            @unlink($file);
        }
    }

}

if (!function_exists('active_link')) {

    function activate_menu($controller = '') {
        $CI = get_instance();
        $class = $CI->router->fetch_class(); //tra ve lop chua fuction hien tai
        return ($class == $controller) ? 'active' : '';
    }

}

if (!function_exists('is_home')) {

    function is_home() {
        $ci = & get_instance();
        if ($ci->uri->uri_string() == '') {
            return true;
        }

        return false;
    }

}

if (!function_exists('get_asset')) {

    function get_asset($folder = '') {
        $html = '';
        $ci = & get_instance();
        $base_url = $ci->config->item('base_url');
        $html .= $base_url;
        if (trim($folder) != '') {
            $html .= $ci->config->item($folder);
        }

        return $html;
    }

}

if (!function_exists('get_view_page')) {

    function get_view_page($view_page = '') {
        $data = array(
            'page_grid' => 'Lưới',
            'page_list' => 'Danh sách',
        );
        $html = '';
        if (isset($data[$view_page])) {
            $html .= $data[$view_page];
        }

        return $html;
    }

}

if (!function_exists('validate_file_exists')) {

    function validate_file_exists($file = '') {
        $bool = true;

        if (is_dir($file) || !file_exists(FCPATH . $file)) {
            $bool = false;
        }

        return $bool;
    }

}

if (!function_exists('get_image')) {

    function get_image($path_image = '', $path_default_image = 'uploads/no_image.png') {
        $html = $path_image;

        if (is_dir($path_image) || !file_exists(FCPATH . $path_image)) {
            $html = $path_default_image;
        }

        return base_url($html);
    }

}

if (!function_exists('get_option_per_page')) {

    function get_option_per_page($option_selected = '') {
        $ci = &get_instance();
        $html = '';
        $data = range($ci->config->item('item', 'admin_list'), $ci->config->item('total', 'admin_list'), $ci->config->item('item', 'admin_list'));
        foreach ($data as $value) {
            $selected = '';
            if ($option_selected == $value) {
                $selected = ' selected="selected"';
            }
            $html .= "<option value='$value' $selected>" . $value . "</option>";
        }

        return $html;
    }

}

if (!function_exists('get_option_select')) {

    function get_option_select($data, $option_selected = '') {
        $html = '';
        foreach ($data as $key => $value) {
            $selected = '';
            if (strcmp($option_selected, $key) === 0){
                $selected = ' selected="selected"';
            }
            $html .= "<option value='$key' $selected>" . $value . "</option>";
        }

        return $html;
    }

}

if (!function_exists('display_value_array')) {

    function display_value_array($data, $key = '') {
        $html = '';
        if (isset($data[$key])) {
            $html = $data[$key];
        }

        return $html;
    }

}

if (!function_exists('get_file_name_uploads_path')) {

    function get_file_name_uploads_path($path) {
        $ext = end(explode("/", $path));
        return $ext;
    }

}

if (!function_exists('get_module_path')) {

    function get_module_path($module_name = '') {
        $html = '';
        $ci = & get_instance();
        $modules_path = $ci->config->item('modules_path');
        if (trim($module_name) != '' && isset($modules_path[$module_name])) {
            $html = $modules_path[$module_name];
        }

        return $html;
    }

}

if (!function_exists('get_shops_thumbnais_default_size')) {

    function get_shops_thumbnais_default_size() {
        $html = '';
        $ci = & get_instance();
        $shops_thumbnais_sizes = $ci->config->item('shops_thumbnais_sizes');

        if (is_array($shops_thumbnais_sizes)) {
            $keys = array_keys($shops_thumbnais_sizes);
            if (isset($keys[0])) {
                $html = $keys[0];
            }
        }

        return $html;
    }

}

if (!function_exists('get_shops_thumbnais_sizes')) {

    function get_shops_thumbnais_sizes($key = '185x181') {
        $array = NULL;
        $ci = & get_instance();
        $shops_thumbnais_sizes = $ci->config->item('shops_thumbnais_sizes');
        if (trim($key) != '' && isset($shops_thumbnais_sizes[$key])) {
            $array = $shops_thumbnais_sizes[$key];
        }

        return $array;
    }

}

if (!function_exists('get_posts_thumbnais_sizes')) {

    function get_posts_thumbnais_sizes($key = '185x181') {
        $array = NULL;
        $ci = & get_instance();
        $posts_thumbnais_sizes = $ci->config->item('posts_thumbnais_sizes');
        if (trim($key) != '' && isset($posts_thumbnais_sizes[$key])) {
            $array = $posts_thumbnais_sizes[$key];
        }

        return $array;
    }

}

if (!function_exists('display_label')) {

    function display_label($content = '', $lable_type = 'success', $bootstrap_version = 3) {
        $html = '';
        if($bootstrap_version == 4){
            $html = "<span class='badge badge-$lable_type'>$content</span>";
        }else{
            $html = "<span class='label label-$lable_type'>$content</span>";
        }

        return $html;
    }

}

if (!function_exists('get_option_gender')) {

    function get_option_gender($option_selected = '') {
        $html = '';
        $ci = & get_instance();
        $ci->config->load('params');
        $genders = $ci->config->item('gender');
        foreach ($genders['data'] as $key => $value) {
            $selected = '';
            if ($option_selected == $key) {
                $selected = ' selected="selected"';
            }
            $html .= "<option value='$key' $selected>" . $value . "</option>";
        }

        return $html;
    }

}

if (!function_exists('get_gender')) {

    function get_gender($gender_key = '') {
        $html = '';
        $ci = & get_instance();
        $ci->config->load('params');
        $genders = $ci->config->item('gender');
        if (isset($genders['data'][$gender_key])) {
            $html .= $genders['data'][$gender_key];
        }

        return $html;
    }

}

if (!function_exists('number_format_en')) {
    /*
     * format number n000 to n,000.00
     */

    function number_format_en($number, $decimals = 2) {
        return number_format($number, $decimals, '.', ',');
    }

}

if (!function_exists('number_format_vi')) {
    /*
     * format number n000 to n.000,00
     */

    function number_format_vi($number, $decimals = 2) {
        return number_format($number, $decimals, ',', '.');
    }

}

if (!function_exists('number_format_normal')) {
    /*
     * format number n000 to n.000.00
     */

    function number_format_normal($number, $decimals = 2) {
        return number_format($number, $decimals, '.', '.');
    }

}

if (!function_exists('format_m_d_Y_strtotime')) {
    /*
     * convert date format m-d-Y or m/d/Y to Y-m-d
     */

    function format_m_d_Y_strtotime($str, $separator = '-') {
        $dates = explode($separator, $str);
        return $dates[2] . '-' . $dates[0] . '-' . $dates[1];
    }

}

if (!function_exists('format_d_m_Y_strtotime')) {
    /*
     * convert date format d-m-Y or d/m/Y to Y-m-d
     */

    function format_d_m_Y_strtotime($str, $separator = '-') {
        $dates = explode($separator, $str);
        return $dates[2] . '-' . $dates[1] . '-' . $dates[0];
    }

}

if (!function_exists('get_tag')) {

    function get_tag($tag, $xml) {
        preg_match_all('/<' . $tag . '>(.*)<\/' . $tag . '>$/imU', $xml, $match);
        return $match[1];
    }

}

if (!function_exists('get_rand_string')) {

    function get_rand_string($length = 11) {
        $str = '';
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);
        for ($i = 0; $i < $length; $i++) {
            $str .= $chars[rand(0, $size - 1)];
        }
        return $str;
    }

}

if (!function_exists('is_bot')) {

    function is_bot() {
        /* This function will check whether the visitor is a search engine robot */

        $botlist = array("Teoma", "alexa", "froogle", "Gigabot", "inktomi",
            "looksmart", "URL_Spider_SQL", "Firefly", "NationalDirectory",
            "Ask Jeeves", "TECNOSEEK", "InfoSeek", "WebFindBot", "girafabot",
            "crawler", "www.galaxy.com", "Googlebot", "Scooter", "Slurp",
            "msnbot", "appie", "FAST", "WebBug", "Spade", "ZyBorg", "rabaz",
            "Baiduspider", "Feedfetcher-Google", "TechnoratiSnoop", "Rankivabot",
            "Mediapartners-Google", "Sogou web spider", "WebAlta Crawler", "TweetmemeBot",
            "Butterfly", "Twitturls", "Me.dium", "Twiceler");

        foreach ($botlist as $bot) {
            if (strpos($_SERVER['HTTP_USER_AGENT'], $bot) !== false)
                return true; // Is a bot
        }

        return false; // Not a bot
    }

}

if (!function_exists('str_remove_unicode_space')) {

    function str_remove_unicode_space($str = '', $removeSpace = false) {
        $result = "";

//Loại bỏ dấu tiếng việt
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

//Xóa khoảng trắng
        if ($removeSpace == true) {
            $arr = explode(" ", $str);
            foreach ($arr as $k => $v) {
                $result .= $v;
            }
        } else {
            $result = $str;
        }

        return $result;
    }

}


if (!function_exists('str_standardize')) {

    function str_standardize($str = '') {
        $str = trim($str); // xóa tất cả các khoảng trắng còn thừa ở đầu và cuối chuỗi
        $str = preg_replace('/\s(?=\s)/', '', $str); // Thay thế nhiều khoảng trắng liên tiếp nhau trong chuỗi = 1 khoảng trắng duy nhất
        $str = preg_replace('/[\n\r\t]/', ' ', $str); // Thay thế những kí tự đặc biệt: xuống dòng, tab = khoảng trắng

        return $str;
    }

}

if (!function_exists('replace_special_url')) {

    function replace_special_url($str = '') {
        $pattern = '/[^\w\d\s]/';
//$str = str_remove_unicode_space($str);
        $str = preg_replace($pattern, "-", $str);
        $str = str_replace(" ", "-", $str);
        $str = preg_replace('/\-(?=\-)/', '', $str);

        return $str;
    }

}

if (!function_exists('show_alert_success')) {

    function show_alert_success($str = '') {
        $html = '';
        if (trim($str) != '') {
            $html .= '
              <div class="alert alert-dismissable alert-success">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <strong>' . $str . '</strong>
              </div>';
        }
        return $html;
    }

}

if (!function_exists('show_alert_danger')) {

    function show_alert_danger($str = '') {
        $html = '';
        if (trim($str) != '') {
            $html .= '
              <div class="alert alert-dismissable alert-danger">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <strong>' . $str . '</strong>
              </div>';
        }
        return $html;
    }

}

if (!function_exists('show_alert_warning')) {

    function show_alert_warning($str = '') {
        $html = '';
        if (trim($str) != '') {
            $html .= '
              <div class="alert alert-dismissable alert-warning">
                <button data-dismiss="alert" class="close" type="button">×</button>
                <h4>' . $str . '</h4>
              </div>';
        }
        return $html;
    }

}

if (!function_exists('show_badge')) {

    function show_badge($str = '', $type = 'light') {
        $html = '';
        if (trim($str) != '') {
            $html .= '<span class="badge badge-' . $type . '">' . $str . '</span>';
        }
        return $html;
    }

}

if (!function_exists('display_date')) {

    function display_date($timestamp = 0, $full = FALSE) {
        $html = '';
        if($full){
            $html .= date('H:i:s d/m/Y', $timestamp);
        }else{
            $html .= date('H:i d/m/Y', $timestamp);
        }

        return $html;
    }

}

if (!function_exists('get_day_of_week_vi')) {

    function get_day_of_week_vi($strtotime = 0) {
        $day = date('w', $strtotime);
        switch ($day) {
            case 0:
                $thu = "Chủ nhật";
                break;
            case 1:
                $thu = "Thứ hai";
                break;
            case 2:
                $thu = "Thứ ba";
                break;
            case 3:
                $thu = "Thứ tư";
                break;
            case 4:
                $thu = "Thứ năm";
                break;
            case 5:
                $thu = "Thứ sáu";
                break;
            case 6:
                $thu = "Thứ bảy";
                break;
            default: $thu = "";
                break;
        }
        return $thu;
    }

}

if (!function_exists('php_truncate')) {

    function php_truncate($text, $length) {
        $length = abs((int) $length);
        if (mb_strlen($text, 'UTF-8') > $length) {
            $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
        }
        return($text);
    }

}

if (!function_exists('formatRiceVND')) {

    function formatRiceVND($price = 0) {
        $symbol = ' VND';
        $symbol_thousand = '.';
        $decimal_place = 0;
        $number = number_format($price, $decimal_place, '', $symbol_thousand);
        return $number . $symbol;
    }

}

if (!function_exists('formatRice')) {

    function formatRice($price = 0) {
        $symbol_thousand = '.';
        $decimal_place = 0;
        $number = number_format($price, $decimal_place, '', $symbol_thousand);
        return $number;
    }

}

if (!function_exists('alias')) {

    function alias($str = '', $removeSpace = false) {
        $result = "";

//Loại bỏ dấu tiếng việt
        $unicode = array(
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ặ|ằ|ẳ|ẵ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ',
            'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị',
            'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự',
            'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ặ|Ằ|Ẳ|Ẵ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ',
            'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị',
            'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự',
            'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        );
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }

//Xóa khoảng trắng
        if ($removeSpace == true) {
            $arr = explode(" ", $str);
            foreach ($arr as $k => $v)
                $result .= $v;
        } else {
            $result = $str;
        }

        $pattern = '/[^\w\d\s]/';
        $str = $result;
        $str = preg_replace($pattern, "-", $str);
        $result = str_replace(" ", "-", $str);

//$str = str_only_character($str);
//return $str;


        $character = '-';
        $str = $result;
        $result = '';

        $arr_Temps = explode($character, $str);
        foreach ($arr_Temps as $key => $value) {
            if ($value == '') {
                unset($arr_Temps[$key]);
            }
        }

        $end = count($arr_Temps) - 1;
        $i = 0;

        foreach ($arr_Temps as $key => $value) {
            if ($value != '') {
                $result .= $value;
                if ($i != $end) {
                    $result .= $character;
                }
            }
            $i++;
        }

        return $result;
    }

}

if (!function_exists('get_product_discounts')) {

    function get_product_discounts($product_price = 0, $product_sales_price = 0) {
        return ($product_sales_price > 0 ? $product_sales_price : $product_price);
    }

}

if (!function_exists('get_promotion_price')) {

	function get_promotion_price($product_price = 0, $product_promotion_price = 0) {
		if($product_promotion_price > 0 && $product_promotion_price < $product_price){
			$price = $product_promotion_price;
		}else{
			$price = $product_price;
		}
		return $price;
	}

}

if (!function_exists('get_promotion_price_F0')) {

    function get_promotion_price_F0($product_price = 0, $F0 = 0) {
        return $product_price - $F0;
    }

}

if (!function_exists('convert_to_lowercase')) {
	function convert_to_lowercase($word = '') {
		return mb_strtolower($word, 'UTF-8');
	}
}

if (!function_exists('convert_to_uppercase')) {
	function convert_to_uppercase($word = '') {
		return mb_strtoupper($word, 'UTF-8');
	}
}

if (!function_exists('display_option_select')) {

    function display_option_select($data, $option_value = 'id', $option_name = 'name', $option_selected = 0) {
        $html = '';

        if (is_array($data) && !empty($data)) {
            foreach ($data as $value) {
                $selected = '';
                if ($value[$option_value] == $option_selected) {
                    $selected = ' selected="selected"';
                }
                $html .= "\n";
                $html .= '<option' . $selected . ' value="' . $value[$option_value] . '"' . '>' . $value[$option_name] . '</option>';
            }
        }

        return $html;
    }

}

function parse_id_cart($str_id = '', $all = false) {
    $str = explode('_', $str_id);
    if ($all) {
        $data = array();
        $data['product_id'] = isset($str[0]) ? (int) $str[0] : 0;
        $data['unit_id'] = isset($str[1]) ? (int) $str[1] : 0;
    } else {
        $data = 0;
        if (isset($str[0])) {
            $data = (int) $str[0];
        }
    }

    return $data;
}

if (!function_exists('get_current_user_logged_in')) {
    function get_current_user_logged_in(){
        $user = NULL;
        $CI = get_CI();
        
        if ($CI->session->has_userdata('logged_in_by')) {
            $session_data = $CI->session->userdata('logged_in_by');
        }elseif ($CI->session->has_userdata('logged_in')) {
            $session_data = $CI->session->userdata('logged_in');
        }
        if(isset($session_data['userid']) && $session_data['userid'] != 0){
            $user = $CI->M_users->get($session_data['userid']);
        }

        return $user;
    }
}

if (!function_exists('get_current_user_id')) {
    function get_current_user_id(){
        $user_id = 0;
        $user = get_current_user_logged_in();
        if(isset($user['userid'])){
            $user_id = (int) $user['userid'];
        }
        return $user_id;
    }
}

if (!function_exists('get_current_user_role')) {
    function get_current_user_role(){
        $user_role = '';
        $user = get_current_user_logged_in();
        if(isset($user['role'])){
            $user_role = $user['role'];
        }
        return $user_role;
    }
}

if (!function_exists('check_current_user_is_wholesale')) {
    function check_current_user_is_wholesale(){
        $bool = FALSE;
        $user = get_current_user_logged_in();
        if(isset($user['is_wholesale']) && $user['is_wholesale'] == 1){
            $bool = TRUE;
        }
        return $bool;
    }
}
/* End of file master_helper.php */
/* Location: ./application/helpers/master_helper.php */
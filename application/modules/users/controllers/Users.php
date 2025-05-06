<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Users extends Layout {

	public $_path = '';
	private $_allowed_field = array('active', 'is_wholesale');

	function __construct() {
		parent::__construct();
		$this->_data['breadcrumbs_module_name'] = 'Tài khoản';
		$this->_path = get_module_path('users');
	}

	function admin_change_field_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$post = $this->input->post();
		if (!empty($post)) {
			$value = $this->input->post('value');
			$id = $this->input->post('id');
			$field = $this->input->post('field');
			$massage_success = $this->input->post('massage_success');
			$massage_warning = $this->input->post('massage_warning');
			$data = array(
				$field => $value,
			);
			if (!in_array($field, $this->_allowed_field)) {
				$notify_type = 'danger';
				$notify_content = 'Trường này không tồn tại!';
			} else if ($this->M_users->update($id, $data)) {
				if ($value == 1) {
					$notify_type = 'success';
					$notify_content = $massage_success;
				} else {
					$notify_type = 'warning';
					$notify_content = $massage_warning;
				}
			} else {
				$notify_type = 'danger';
				$notify_content = 'Dữ liệu chưa lưu!';
			}
			$this->set_notify_admin($notify_type, $notify_content);
			$this->load->view('layout/notify-ajax', NULL);
		} else {
			redirect(base_url());
		}
	}

	function gets_item_field($field = '', $number = 3) {
		if ((trim($field) == '') || !in_array($field, $this->_allowed_field)) {
			return null;
		}
		$args = $this->default_args();
		$args[$field] = 1;
		if ($number > 0) {
			$rows = $this->M_users->gets($args, $number, 0);
		} else {
			$rows = $this->M_users->gets($args);
		}

		return $rows;
	}

	function login_attempt($seconds) {
		$this->load->model('users/m_users_attempts', 'M_users_attempts');
		$ip = $_SERVER['REMOTE_ADDR'];
		$oldest = strtotime(date("Y-m-d H:i:s") . " - " . $seconds . " seconds");
		$oldest = date("Y-m-d H:i:s", $oldest);
		$remove = $this->M_users_attempts->delete(array('when' => $oldest));
		$data = array('ip' => $ip, 'when' => date("Y-m-d H:i:s"));
		$insert = $this->M_users_attempts->add($data);
	}

	function login_attempt_count() {
		$this->load->model('users/m_users_attempts', 'M_users_attempts');
		$ip = $_SERVER['REMOTE_ADDR'];
		return $this->M_users_attempts->counts(array('ip' => $ip));
	}

	function ajax_login_attempt_reset() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Kiểm tra thông tin';

		$post = $this->input->post();
		if (!empty($post)) {
			$this->load->model('users/m_users_attempts', 'M_users_attempts');
			$ip = $this->input->post('ip');
			$bool = $this->M_users_attempts->delete(array('ip' => $ip));
			if ($bool) {
				$message['status'] = 'success';
				$message['content'] = null;
				$message['message'] = 'Tải dữ liệu thành công!';
			} else {
				$message['status'] = 'danger';
				$message['content'] = null;
				$message['message'] = 'Có lỗi xảy ra! Vui lòng kiểm tra lại!';
			}
		}
		echo json_encode($message);
		exit();
	}

	function ajax_generate_qr_code() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$response = array();
		$response['status'] = 'warning';
		$response['content'] = null;
		$response['message'] = 'Kiểm tra thông tin';

		$post = $this->input->post();
		if (!empty($post)) {
			$id = $this->input->post('id');
			$row = $this->get($id);
			$qr_code = isset($row['username']) ? $row['username'] : '';
			$response = generate_qr_code($qr_code);
			if($response['status'] === 'success'){
				$qr_code = $response['content'];
				$response['content'] = '<a href="' . get_admin_url('users/qr/download') . '?file=' . rawurlencode(base_url($qr_code)) . '"><img src="' . base_url($qr_code) . '" alt="" class="img-thumbnail"></a>';
			}
		}
		echo json_encode($response);
		exit();
	}

	function ajax_get_new_OTP() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Kiểm tra thông tin';

		$post = $this->input->post();
		if (!empty($post)) {
			$time = time();
			$OTP = random_string('numeric', 6);
			$sms_phone = $this->input->post('phone');
			$sms_content = "Ma OTP cua ban la: " . $OTP;
			$sms_json = send_sms($sms_phone, $sms_content);
			$sms_json = (array)json_decode($sms_json);
            if($sms_json['status'] != 'success'){
            	$message['status'] = 'danger';
            	$message['content'] = null;
            	$message['message'] = 'OTP chưa được gửi! Vui lòng thử lại sau!';
            	echo json_encode($message);
            	exit();
            }
			$args_sms = array(
				'brandname' => $this->config->item('brandname', 'sms'),
				'phone' => $sms_phone,
				'message' => $sms_content,
				'OTP' => $OTP,
				'expired' => $time + $this->config->item('expired', 'sms'),
				'status' => 1,
				'viewed' => 0,
				'created' => $time
			);
			$sms_id = modules::run('sms/add', $args_sms);
			//$sms_id = 1;
			if ($sms_id) {
				$message['status'] = 'success';
				$message['content'] = '<span class="input-group-text">Mã OTP sẽ hết hạn sau <span class="text-expired" id="countdown">' . $this->config->item('expired', 'sms') . 's</span></span>';
				$message['message'] = 'Mã OTP mới vừa được tạo! Vui lòng kiểm tra điện thoại và nhập lại!';
			} else {
				$message['status'] = 'danger';
				$message['content'] = null;
				$message['message'] = 'Có lỗi xảy ra! Vui lòng kiểm tra lại!';
			}
		}
		echo json_encode($message);
		exit();
	}

	function ajax_search() {
		/*if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$data = array();

		// $post = $this->input->post();
		$post = $this->input->get();
		if (!empty($post)) {
			// if($this->input->post('user_id')){
			// 	$user_id = (int)$this->input->post('user_id');
			// }else{
			// 	$user_id = get_current_user_id();
			// }

			$args = $this->default_args();
			// $args['search'] = $this->input->post('searchTerm');
			$args['in_role'] = array('AGENCY');
            // $args['not_in_id'] = array($user_id);
			$data = $this->M_users->search($args);
		}*/
		$data = array(
			'states' => array(
				'abc',
				'ledu',
				'ab',
				'le',
			)
		);
		echo json_encode($data);
		exit();
	}

	function ajax_json_tree() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$data = null;

		if ($this->input->post('id')) {
			$id = $this->input->post('id');
			if ($id == '#') {
				$this->_initialize_user();
				if(isset($this->_data['userid'])){
					$parent_id = (int) $this->_data['userid'];
					$childs = $this->M_users->get_childs_tree($parent_id);
					if(is_array($childs) && !empty($childs)){
						foreach ($childs as $key => $item){
							$root = (int) $item['userid'];
							//$revenue = formatRice(get_buy_user($root, TRUE));
							$revenue = formatRice(get_balance_accumulate_user($root));
							$child = [
								"text" => $item['full_name'] . ' - ' . $item['username'] . ' - ' . $revenue . ' - ' . $item['phone'],
								"id" => $root,
								"state" => [
									"opened" => false
								],
							];
							$counts_sub_childs = $this->M_users->counts_childs_tree($root);
							if($counts_sub_childs > 0){
								$child["children"] = true;
							}
							$data[] = $child;
						}
					}
				}
			} else {
				$parent_id = (int) $id;
				$childs = $this->M_users->get_childs_tree($parent_id);
				if(is_array($childs) && !empty($childs)){
					foreach ($childs as $key => $item){
						$root = (int) $item['userid'];
						//$revenue = formatRice(get_buy_user($root, TRUE));
						$revenue = formatRice(get_balance_accumulate_user($root));
						$child = [
							"text" => $item['full_name'] . ' - ' . $item['username'] . ' - ' . $revenue . ' - ' . $item['phone'],
							"id" => $root,
							"state" => "opened",
						];
						$counts_sub_childs = $this->M_users->counts_childs_tree($root);
						if($counts_sub_childs > 0){
							$child["state"] = "closed";
							$child["children"] = true;
						}
						$data[] = $child;
					}
				}
			}
		}
		header('Content-type: application/json');
		echo json_encode($data);
		exit();
	}

	function ajax_get() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Kiểm tra thông tin';

		$post = $this->input->post();
		if (!empty($post)) {
			$id = $this->input->post('id');
			$data = $this->get($id);
			if (is_array($data) && !empty($data)) {
				$message['status'] = 'success';
				$message['content'] = $data;
				$message['message'] = 'Tải dữ liệu thành công!';
			} else {
				$message['status'] = 'danger';
				$message['content'] = null;
				$message['message'] = 'Có lỗi xảy ra! Vui lòng kiểm tra lại!';
			}
		}
		echo json_encode($message);
		exit();
	}

	function default_args() {
		$order_by = array(
			'userid' => 'DESC',
			'full_name' => 'ASC',
		);
		$args = array();
		$args['order_by'] = $order_by;

		return $args;
	}

	function gets($options = array()) {
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
		return $this->M_users->gets($args);
	}

	function counts($options = array()) {
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
		return $this->M_users->counts($args);
	}

	function get($id) {
		return $this->M_users->get($id);
	}

	function get_by($options = array()) {
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
        return $this->M_users->get_by($args);
    }

    function download_qr(){
        $this->load->helper('download');
        $file = rawurldecode($this->input->get('file'));
        $data = file_get_contents($file);
        $file_name = basename($file);
        force_download($file_name, $data);
    }

	function gets_in_group($options = array()) {
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
		return $this->M_users->gets($args);
	}

	function get_num_all_data() {
		return $this->counts(array());
	}

	function get_num_new_data() {
		return $this->counts(array('active' => 0));
	}

	function set_last_login($userid) {
		$data = array('last_login' => time());
		return $this->M_users->update($userid, $data);
	}

	function set_last_ip($userid) {
		$data = array('last_ip' => $this->input->ip_address());
		return $this->M_users->update($userid, $data);
	}

	function set_last_agent($userid) {
		$this->load->library('user_agent');

		if ($this->agent->is_browser()) {
			$agent = $this->agent->browser() . ' ' . $this->agent->version();
		} elseif ($this->agent->is_robot()) {
			$agent = $this->agent->robot();
		} elseif ($this->agent->is_mobile()) {
			$agent = $this->agent->mobile();
		} else {
			$agent = 'Unidentified User Agent';
		}

		$data = array('last_agent' => $agent);

		return $this->M_users->update($userid, $data);
	}

	function admin_inactive_all() {
		$this->_initialize_admin();
		$this->redirect_admin();

		if(!$this->input->get('token')){
			$notify_type = 'danger';
			$notify_content = "Kiểm tra thông tin nhập!";
			$this->set_notify_admin($notify_type, $notify_content);
			redirect(get_admin_url('users/inactive'));
		}

		$month = 6;

		$get = $this->input->get();
		$this->_data['get'] = $get;

		$session_data = $this->session->userdata('logged_in');
		$group_id = isset($session_data['group_id']) ? $session_data['group_id'] : 0;
		$userid = isset($session_data['userid']) ? $session_data['userid'] : 0;

		$args = $this->default_args();
		if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }
		$args['admin_group_id'] = $group_id;
		$args['admin_userid'] = $userid;
		$args['last_order_date'] = strtotime("-$month month");
		// $args['last_login_start'] = strtotime("-$month month");
		$rows = $this->M_users->gets($args);
		/*echo "<pre>";
        print_r($rows);
        echo "</pre>";
		die;*/
		if(is_array($rows) && !empty($rows)){
			$in_ids = array_column($rows, 'userid');
			if($this->M_users->update_inactive($in_ids)){
				$notify_type = "success";
				$notify_content = "Đã khóa (tắt kích hoạt) các tài khoản yêu cầu!";
			}else{
				$notify_type = "danger";
				$notify_content = "Có lỗi xảy ra. Vui lòng thực hiện lại!";
			}
		} else {
			$notify_type = 'warning';
			$notify_content = "Không có tài khoản nào để khóa (tắt kích hoạt)!";
		}
		$this->set_notify_admin($notify_type, $notify_content);
		redirect(get_admin_url('users/inactive'));
	}

	function admin_inactive() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$month = 6;

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap3-dialog/css',
			'name' => 'bootstrap-dialog',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap3-dialog/js',
			'name' => 'bootstrap-dialog',
		);
		$this->set_plugins_admin();

		$this->_modules_script[] = array(
			'folder' => 'users',
			'name' => 'admin-inactive',
		);
		$this->set_modules();

		$get = $this->input->get();
		$this->_data['get'] = $get;

		$session_data = $this->session->userdata('logged_in');
		$group_id = isset($session_data['group_id']) ? $session_data['group_id'] : 0;
		$userid = isset($session_data['userid']) ? $session_data['userid'] : 0;

		$args = $this->default_args();
		if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }
		$args['admin_group_id'] = $group_id;
		$args['admin_userid'] = $userid;
		$args['last_order_date'] = strtotime("-$month month");
		
		// $args['interval_seconds'] = strtotime("-$month month");
		// $args['interval_seconds'] = strtotime("-20 day");
		// $args['last_login_start'] = strtotime("-$month month");
		// echo date('Y-m-d H:i:s', $args['interval_seconds']);
		// echo date('Y-m-d H:i:s', 1731030514);
		// die;

		$total = $this->M_users->counts($args);
		$perpage = isset($get['per_page']) ? $get['per_page'] : $this->config->item('per_page');
		$segment = 4;

		$this->load->library('pagination');
		$config['total_rows'] = $total;
		$config['per_page'] = $perpage;
		$config['full_tag_open'] = '<ul class="pagination no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = '&laquo;';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '&raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Trang trước &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&larr; Trang sau';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';

		$config['base_url'] = get_admin_url('users/inactive');
		$config['uri_segment'] = $segment;
		if (!empty($get)) {
			$config['suffix'] = '?' . http_build_query($get, '', "&");
			$config['first_url'] = get_admin_url('users/inactive' . '?' . http_build_query($get, '', "&"));
		}

		$this->pagination->initialize($config);
		$pagination = $this->pagination->create_links();
		$this->_data['pagination'] = $pagination;

		$offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
		$this->_data['rows'] = $this->M_users->gets($args, $perpage, $offset);

		$this->_data['breadcrumbs_module_func'] = 'Danh sách thành viên không mua hàng';
		$this->_data['title'] = 'Thành viên không mua hàng'  . ' - ' . $this->_data['title'];
		$this->_data['main_content'] = 'users/admin/view_page_inactive';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function fix_last_order_date(){
		/*$args = $this->default_args();
        $rows = $this->M_users->gets($args);*/
        $rows = $this->M_users->gets_short();
        if(is_array($rows) && !empty($rows)){
            foreach ($rows as $row) {
            	$user_id = $row['userid'];
            	$order_args = array(
            		'customer_id' => $user_id,
            		'transaction_status' => 1,
            		'order_by' => array(
						'order_date' => 'DESC',
					),
            	);
            	$order_row = $this->M_shops_orders->get_by_short($order_args);
            	if(is_array($order_row) && !empty($order_row)){
					$this->M_users->update($user_id, array('last_order_date' => $order_row['order_date']));
				}
			}
		}
		echo 'Completed!';
	}

	function index() {
		$this->_initialize_admin();
		$this->redirect_admin();

		/*if($this->input->get('debug')){
			$this->set_reward();
			die;
		}*/

		/*if($this->input->get('fix')){
			$this->fix_last_order_date();
			die;
		}*/

		/*$refer_key = 'DanhHappyGarden';
		$this->load->library('ciqrcode');
		$file_name = $refer_key . '.png';
		$params['data'] = site_url('dang-ky') . '?ref=' . $refer_key;
		// $params['data'] = $refer_key;
		$params['level'] = 'L';
		$params['size'] = 3;
		$params['savename'] = get_module_path('users_qr') . $file_name;
		$this->ciqrcode->generate($params);
		echo $params['savename'];
		die;*/

        /*$args = $this->default_args();
        $rows = $this->M_users->gets($args);
        if(is_array($rows) && !empty($rows)){
            $qr_field = 'refer_key';
            $qr_field_new = 'username';
            $this->load->library('ciqrcode');
            foreach ($rows as $row) {
                @unlink(FCPATH . get_module_path('users_qr') . $row[$qr_field] . ".png");
                //$qr_code = $this->__get_refer_key();
                $qr_code = $row[$qr_field_new];
                if(trim($qr_code) != ''){
                	//$this->M_users->update($row['userid'], array('refer_key' => $qr_code));
                    $file_name = $qr_code . '.png';
                    $params['data'] = site_url('dang-ky') . '?ref=' . $qr_code;
                    $params['level'] = 'L';
                    $params['size'] = 3;
                    $params['savename'] = get_module_path('users_qr') . $file_name;
                    $this->ciqrcode->generate($params);
                }
            }
        }
        die;*/

		$this->output->cache(true);

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap3-dialog/css',
			'name' => 'bootstrap-dialog',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap3-dialog/js',
			'name' => 'bootstrap-dialog',
		);
		$this->set_plugins_admin();

		$this->_modules_script[] = array(
			'folder' => 'users',
			'name' => 'admin-index',
		);
		$this->set_modules();

		$get = $this->input->get();
		$this->_data['get'] = $get;

		$session_data = $this->session->userdata('logged_in');
		$group_id = isset($session_data['group_id']) ? $session_data['group_id'] : 0;
		$userid = isset($session_data['userid']) ? $session_data['userid'] : 0;
		$user_role = isset($session_data['role']) ? $session_data['role'] : '';
		// $this->_data['role'] = $user_role;

		$args = $this->default_args();
		if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }
        if (isset($get['role']) && trim($get['role']) != '') {
            $args['role'] = $get['role'];
        }
		// $args['admin_group_id'] = $group_id;
		if($user_role != 'ADMIN'){
			$args['role'] = 'AGENCY';
		}
		$args['admin_userid'] = $userid;

		// $this->benchmark->mark('code_start');
		// $total = $this->counts($args);
		// $this->benchmark->mark('code_end');
		// echo $this->benchmark->elapsed_time('code_start', 'code_end');
		// die;
		$total = $this->counts($args);
		$per_page = isset($get['per_page']) ? $get['per_page'] : $this->config->item('per_page');
		$segment = 3;

		$this->load->library('pagination');
		$config['total_rows'] = $total;
		$config['per_page'] = $per_page;
		$config['full_tag_open'] = '<ul class="pagination no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';

		$config['first_link'] = '&laquo;';
		$config['first_tag_open'] = '<li class="prev page">';
		$config['first_tag_close'] = '</li>';

		$config['last_link'] = '&raquo;';
		$config['last_tag_open'] = '<li class="next page">';
		$config['last_tag_close'] = '</li>';

		$config['next_link'] = 'Trang trước &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&larr; Trang sau';
		$config['prev_tag_open'] = '<li class="prev page">';
		$config['prev_tag_close'] = '</li>';

		$config['cur_tag_open'] = '<li class="active"><a href="">';
		$config['cur_tag_close'] = '</a></li>';

		$config['num_tag_open'] = '<li class="page">';
		$config['num_tag_close'] = '</li>';

		$config['base_url'] = get_admin_url('users');
		$config['uri_segment'] = $segment;
		if (!empty($get)) {
			$config['suffix'] = '?' . http_build_query($get, '', "&");
			$config['first_url'] = get_admin_url('users' . '?' . http_build_query($get, '', "&"));
		}

		$this->pagination->initialize($config);
		$pagination = $this->pagination->create_links();
		$this->_data['pagination'] = $pagination;

		$offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
		$args['select_fields'] = TRUE;//'users.userid, users.referred_by, users.username, users.full_name, users.phone, users.email, users.role, users.stock, users.stock_official, users.dividend, users.active, users.is_wholesale, users.regdate';
		$this->_data['rows'] = $this->M_users->gets($args, $per_page, $offset);
		// $this->benchmark->mark('code_start');
		// $this->_data['rows'] = $this->M_users->gets($args, $per_page, $offset);
		// $this->benchmark->mark('code_end');
		// echo $this->benchmark->elapsed_time('code_start', 'code_end');
		// die;

		$this->_data['breadcrumbs_module_func'] = 'Danh sách thành viên';
		$this->_data['title'] = 'Danh sách thành viên';
		$this->_data['main_content'] = 'users/admin/view_page_index';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_export_excel() {
        $this->_initialize_admin();
		$this->redirect_admin();

        $get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'users';

		$session_data = $this->session->userdata('logged_in');
		$group_id = isset($session_data['group_id']) ? $session_data['group_id'] : 0;
		$userid = isset($session_data['userid']) ? $session_data['userid'] : 0;

		$args = $this->default_args();
		if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }
        if (isset($get['role']) && trim($get['role']) != '') {
            $args['role'] = $get['role'];
        }
		$args['admin_group_id'] = $group_id;
		$args['admin_userid'] = $userid;

        $rows = $this->gets($args);
        if (!is_array($rows) && empty($rows)) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'J';
        $letterColumn = range($firstColumn, $lastColumn);
        $hideColumn = 'K';

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'name' => 'Arial',
            'bold' => true,
            'color' => array(
                'rgb' => '333300',
            ),
        );

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3582F4'),
            ),
        );

        $this->excel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle("Danh sách thành viên")
            ->setSubject("Danh sách thành viên")
            ->setDescription("Danh sách thành viên")
            ->setKeywords("Danh sách thành viên")
            ->setCategory("Danh sách thành viên");
        $this->excel->getActiveSheet()->setTitle('Danh sách thành viên');

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', "Tài khoản")
            ->setCellValue('C1', "Họ tên")
            ->setCellValue('D1', "Email")
            ->setCellValue('E1', "Điện thoại")
            ->setCellValue('F1', "Người giới thiệu")
            ->setCellValue('G1', "Cổ phần nội bộ")
            ->setCellValue('H1', "Cổ phần chính thức")
            ->setCellValue('I1', "Điểm HK tri ân")
            ->setCellValue('J1', 'Ngày đăng ký');

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        // Add some data
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('J1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        // $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->applyFromArray($styleAlignmentCenter);

        // $this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->applyFromArray($styleAlignmentRight);

        //Highlight Header
        // $fillHighlightHeader = '92d050';
        // $this->excel->getActiveSheet()->getStyle('W1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('Z1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AA1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AB1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);

        foreach ($letterColumn as $column) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $i = 2;
        foreach ($rows as $row) {
        	$ref = '';
        	if($row['role'] == 'AGENCY' && $row['referred_by'] > 0){
        		$ref = $row['parent_full_name'] . ' (#ID: ' . $row['referred_by'] . ' - ĐT: ' . $row['parent_phone'] . ')';
        	}
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $row['userid'])
                ->setCellValue('B' . $i, $row['username'])
                ->setCellValue('C' . $i, $row['full_name'])
                ->setCellValue('D' . $i, $row['email'])
                ->setCellValue('E' . $i, $row['phone'])
                ->setCellValue('F' . $i, $ref)
                ->setCellValue('G' . $i, $row['stock'])
                ->setCellValue('H' . $i, $row['stock_official'])
                ->setCellValue('I' . $i, $row['dividend'])
                ->setCellValue('J' . $i, display_date($row['regdate']));

            $this->excel->getActiveSheet()->setCellValueExplicit('E' . $i, strval($row['phone']), PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            // $this->excel->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            // $this->excel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
            $this->excel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('J' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);

            $i++;
        }

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13
                )
            )
        );

        $filename = 'Danh-sach-thanh-vien_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    function admin_export_commission_excel() {
        $this->_initialize_admin();
		$this->redirect_admin();

        $get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'users';

		$session_data = $this->session->userdata('logged_in');
		$group_id = isset($session_data['group_id']) ? $session_data['group_id'] : 0;
		$userid = isset($session_data['userid']) ? $session_data['userid'] : 0;

		$args = $this->default_args();
		if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }
        if (isset($get['role']) && trim($get['role']) != '') {
            $args['role'] = $get['role'];
        }
		$args['admin_group_id'] = $group_id;
		$args['admin_userid'] = $userid;

        $rows = $this->gets($args);
        if (!is_array($rows) && empty($rows)) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'H';
        $letterColumn = range($firstColumn, $lastColumn);
        $hideColumn = 'I';

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'name' => 'Arial',
            'bold' => true,
            'color' => array(
                'rgb' => '333300',
            ),
        );

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3582F4'),
            ),
        );

        $this->excel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle("Danh sách hoa hồng thành viên")
            ->setSubject("Danh sách hoa hồng thành viên")
            ->setDescription("Danh sách hoa hồng thành viên")
            ->setKeywords("Danh sách hoa hồng thành viên")
            ->setCategory("Danh sách hoa hồng thành viên");
        $this->excel->getActiveSheet()->setTitle('Danh sách hoa hồng thành viên');

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', "Tài khoản")
            ->setCellValue('C1', "Họ tên")
            ->setCellValue('D1', "Email")
            ->setCellValue('E1', "Điện thoại")
            ->setCellValue('F1', "Người giới thiệu")
            ->setCellValue('G1', "Thông tin tài khoản ngân hàng")
            ->setCellValue('H1', "Số dư");

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        // Add some data
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        // $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        // $this->excel->getActiveSheet()->getStyle('J1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        // $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->applyFromArray($styleAlignmentCenter);

        // $this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->applyFromArray($styleAlignmentRight);

        //Highlight Header
        // $fillHighlightHeader = '92d050';
        // $this->excel->getActiveSheet()->getStyle('W1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('Z1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AA1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AB1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);

        foreach ($letterColumn as $column) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $i = 2;
        // $date_start = '01-' . date('m-Y');
        // $balance_options = array(
        // 	'date_start' => $start_date_start
        // );

        //tháng 04
        $date_start = '01-04-' . date('Y');
        $date_end = '30-04-' . date('Y');
        $start_date_start = get_start_date($date_start);
        $start_date_end = get_end_date($date_end);
        $balance_options = array(
        	// 'date_start' => $start_date_start,
        	// 'date_end' => $start_date_end,
        );
        foreach ($rows as $row) {
        	// $balance = get_balance_user($row['userid'], 0, $balance_options);
        	$balance = $this->M_users_commission->get_balance($row['userid'], $balance_options);
        	if($balance != 0){
	        	$ref = '';
	        	if($row['role'] == 'AGENCY' && $row['referred_by'] > 0){
	        		$ref = $row['parent_full_name'] . ' (#ID: ' . $row['referred_by'] . ' - ĐT: ' . $row['parent_phone'] . ')';
	        	}

	        	$info_withdrawal = get_info_withdrawal_user($row['userid']);
	        	$info_banker = '';
	        	if(isset($info_withdrawal['account_holder']) && trim($info_withdrawal['account_holder']) != ''){
	        		$info_banker .= "Thông tin: $info_withdrawal[account_holder]";
	        	}
	        	if(isset($info_withdrawal['account_number']) && trim($info_withdrawal['account_number']) != ''){
	        		$info_banker .= ", số tk $info_withdrawal[account_number], $info_withdrawal[bank_name], $info_withdrawal[bank_branch]";
	        	}

	            $this->excel->setActiveSheetIndex(0)
	                ->setCellValue('A' . $i, $row['userid'])
	                ->setCellValue('B' . $i, $row['username'])
	                ->setCellValue('C' . $i, $row['full_name'])
	                ->setCellValue('D' . $i, $row['email'])
	                ->setCellValue('E' . $i, $row['phone'])
	                ->setCellValue('F' . $i, $ref)
	                ->setCellValue('G' . $i, $info_banker)
	                ->setCellValue('H' . $i, $balance);

	            $this->excel->getActiveSheet()->setCellValueExplicit('E' . $i, strval($row['phone']), PHPExcel_Cell_DataType::TYPE_STRING);
	            $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
	            $this->excel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode($numberFormat);

	            $i++;
	        }
        }

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13
                )
            )
        );

        $filename = 'Danh-sach-hoa-hong-thanh-vien_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    function admin_export_reward_excel() {
        $this->_initialize_admin();
		$this->redirect_admin();

        $get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'users';

		$session_data = $this->session->userdata('logged_in');
		$group_id = isset($session_data['group_id']) ? $session_data['group_id'] : 0;
		$userid = isset($session_data['userid']) ? $session_data['userid'] : 0;

		$args = $this->default_args();
		if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }
        if (isset($get['role']) && trim($get['role']) != '') {
            $args['role'] = $get['role'];
        }
		$args['admin_group_id'] = $group_id;
		$args['admin_userid'] = $userid;

        $rows = $this->gets($args);
        if (!is_array($rows) && empty($rows)) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'K';
        $letterColumn = range($firstColumn, $lastColumn);
        $hideColumn = 'L';

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'name' => 'Arial',
            'bold' => true,
            'color' => array(
                'rgb' => '333300',
            ),
        );

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3582F4'),
            ),
        );

        $this->excel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle("Danh sách trả thưởng thành viên")
            ->setSubject("Danh sách trả thưởng thành viên")
            ->setDescription("Danh sách trả thưởng thành viên")
            ->setKeywords("Danh sách trả thưởng thành viên")
            ->setCategory("Danh sách trả thưởng thành viên");
        $this->excel->getActiveSheet()->setTitle('Danh sách trả thưởng thành viên');

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', "Tài khoản")
            ->setCellValue('C1', "Họ tên")
            ->setCellValue('D1', "Email")
            ->setCellValue('E1', "Điện thoại")
            ->setCellValue('F1', "Người giới thiệu")
            ->setCellValue('G1', "Thông tin tài khoản ngân hàng")
            ->setCellValue('H1', "Trả thưởng trực tiếp")
            ->setCellValue('I1', "Trả thưởng hệ thống")
            ->setCellValue('J1', "Thưởng doanh số F1")
            ->setCellValue('K1', 'Tổng cộng');

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        // Add some data
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('J1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        // $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->applyFromArray($styleAlignmentCenter);

        // $this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->applyFromArray($styleAlignmentRight);

        //Highlight Header
        // $fillHighlightHeader = '92d050';
        // $this->excel->getActiveSheet()->getStyle('W1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('Z1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AA1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AB1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);

        foreach ($letterColumn as $column) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $i = 2;
        foreach ($rows as $row) {
        	$total_accumulated = 0;
        	$users_F1 = $this->M_users->gets_F1($row['userid']);
        	if(is_array($users_F1) && !empty($users_F1)){
        		foreach ($users_F1 as $user_F1) {
        			$total_accumulated += get_accumulated_user_id($user_F1['userid']);
        		}
        	}

        	if($total_accumulated >= 2000000){
        		$percent = 10;
        		$revenue_bonus = $total_accumulated * $percent / 100;
        		//ghi giao dịch
        	}
        	/*$ref = '';
        	if($row['role'] == 'AGENCY' && $row['referred_by'] > 0){
        		$ref = $row['parent_full_name'] . ' (#ID: ' . $row['referred_by'] . ' - ĐT: ' . $row['parent_phone'] . ')';
        	}

        	$info_withdrawal = get_info_withdrawal_user($row['userid']);
        	$info_banker = '';
        	if(isset($info_withdrawal['account_holder']) && trim($info_withdrawal['account_holder']) != ''){
        		$info_banker .= "Thông tin: $info_withdrawal[account_holder]";
        	}
        	if(isset($info_withdrawal['account_number']) && trim($info_withdrawal['account_number']) != ''){
        		$info_banker .= ", số tk $info_withdrawal[account_number], $info_withdrawal[bank_name], $info_withdrawal[bank_branch]";
        	}

        	$accumulated = get_accumulated_user($row['userid']);

            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $row['userid'])
                ->setCellValue('B' . $i, $row['username'])
                ->setCellValue('C' . $i, $row['full_name'])
                ->setCellValue('D' . $i, $row['email'])
                ->setCellValue('E' . $i, $row['phone'])
                ->setCellValue('F' . $i, $ref)
                ->setCellValue('G' . $i, $info_banker)
                ->setCellValue('H' . $i, $row['stock_official'])
                ->setCellValue('I' . $i, $row['dividend'])
                ->setCellValue('J' . $i, $row['dividend'])
                ->setCellValue('K' . $i, $accumulated);

            $this->excel->getActiveSheet()->setCellValueExplicit('E' . $i, strval($row['phone']), PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            $this->excel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('J' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('K' . $i)->getNumberFormat()->setFormatCode($numberFormat);*/

            $i++;
        }

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13
                )
            )
        );

        $filename = 'Danh-sach-tra-thuong-thanh-vien_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

	function admin_ref_setting() {
	    $this->_initialize_admin();
	    $this->redirect_admin();

	    $this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;

	    $segment = 5;
	    $user_id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
	    $row = $this->M_users->get($user_id);
	    if(!(is_array($row) && !empty($row))){
	        redirect(get_admin_url($this->_module_slug));
	    }
	    $this->_data['row'] = $row;

	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation',
	        'name' => 'jquery.validate',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation/localization',
	        'name' => 'messages_vi',
	    );

	    $this->_plugins_css_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen.jquery',
	    );

	    $this->set_plugins_admin();

	    $this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-ref-setting',
	    );
	    $this->set_modules();

	    $post = $this->input->post();
	    if (!empty($post) && isset($post['referred_by'])) {
	        $err = FALSE;
	        $referred_by = $this->input->post('referred_by');
	        $data = array(
	            'referred_by' => $referred_by
	        );
	        if (!$this->M_users->update($user_id, $data)) {
	            $err = TRUE;
	        }

	        if ($err === FALSE) {
	            $notify_type = 'success';
	            $notify_content = 'Đã cài đặt người giới thiệu cho người dùng ' . $row['full_name'] . '!';
	            $this->set_notify_admin($notify_type, $notify_content);

	            redirect(get_admin_url($this->_module_slug));
	        } else {
	            $notify_type = 'danger';
	            $notify_content = 'Có lỗi xảy ra!';
	            $this->set_notify_admin($notify_type, $notify_content);
	        }
	    }

	    $args_ref = $this->default_args();
	    $args_ref['role'] = 'AGENCY';
	    $args_ref['not_in_id'] = array($user_id);
	    $this->_data['users'] = $this->M_users->gets_ref($args_ref);

	    $this->_data['title'] = 'Cài đặt người giới thiệu cho người dùng ' . $row['full_name'] . ' - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
	    $this->_data['main_content'] = 'users/admin/view_page_ref_setting';
	    $this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_dividend_setting() {
	    $this->_initialize_admin();
	    $this->redirect_admin();

	    $segment = 5;
	    $user_id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
	    $row = modules::run('users/get', $user_id);
	    $this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;
	    if(!(is_array($row) && !empty($row))){
	        redirect(get_admin_url($this->_module_slug));
	    }
	    $this->_data['row'] = $row;

	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation',
	        'name' => 'jquery.validate',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation/localization',
	        'name' => 'messages_vi',
	    );

	    $this->_plugins_css_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen.jquery',
	    );

	    $this->set_plugins_admin();

	    $this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-dividend-setting',
	    );
	    $this->set_modules();

	    $post = $this->input->post();
	    if (!empty($post)) {
	    	$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('dividend', 'Số lượng', 'trim|required');

			if ($this->form_validation->run($this)) {
		        $err = FALSE;
		        $dividend = $this->input->post('dividend');
		        $dividend_note = $this->input->post('dividend_note');
		        $data = array(
		            'dividend' => $dividend,
		            'dividend_note' => $dividend_note
		        );
		        if (!$this->M_users->update($user_id, $data)) {
		            $err = TRUE;
		        }
		        if ($err === FALSE) {
		            $notify_type = 'success';
		            $notify_content = 'Đã cài đặt cổ tức cổ phần nội bộ cho người dùng ' . $row['full_name'] . '!';
		            $this->set_notify_admin($notify_type, $notify_content);

		            redirect(get_admin_url($this->_module_slug));
		        } else {
		            $notify_type = 'danger';
		            $notify_content = 'Có lỗi xảy ra!';
		            $this->set_notify_admin($notify_type, $notify_content);
		        }
		    }
	    }

	    $this->_data['title'] = 'Cài đặt cổ tức cổ phần nội bộ cho người dùng ' . $row['full_name'] . ' - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
	    $this->_data['main_content'] = 'users/admin/view_page_dividend_setting';
	    $this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_stock_setting() {
	    $this->_initialize_admin();
	    $this->redirect_admin();

	    $segment = 5;
	    $user_id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
	    $row = modules::run('users/get', $user_id);
	    $this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;
	    if(!(is_array($row) && !empty($row))){
	        redirect(get_admin_url($this->_module_slug));
	    }
	    $this->_data['row'] = $row;

	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation',
	        'name' => 'jquery.validate',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation/localization',
	        'name' => 'messages_vi',
	    );

	    $this->_plugins_css_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen.jquery',
	    );

	    $this->set_plugins_admin();

	    $this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-stock-setting',
	    );
	    $this->set_modules();

	    $post = $this->input->post();
	    if (!empty($post)) {
	    	$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('stock', 'Số lượng', 'trim|required');

			if ($this->form_validation->run($this)) {
		        $err = FALSE;
		        $stock = $this->input->post('stock');
		        $data = array(
		            'stock' => $stock
		        );
		        if (!$this->M_users->update($user_id, $data)) {
		            $err = TRUE;
		        }
		        if ($err === FALSE) {
		            $notify_type = 'success';
		            $notify_content = 'Đã cài đặt cổ phiếu nội bộ cho người dùng ' . $row['full_name'] . '!';
		            $this->set_notify_admin($notify_type, $notify_content);

		            redirect(get_admin_url($this->_module_slug));
		        } else {
		            $notify_type = 'danger';
		            $notify_content = 'Có lỗi xảy ra!';
		            $this->set_notify_admin($notify_type, $notify_content);
		        }
		    }
	    }

	    $this->_data['title'] = 'Cài đặt cổ phiếu nội bộ cho người dùng ' . $row['full_name'] . ' - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
	    $this->_data['main_content'] = 'users/admin/view_page_stock_setting';
	    $this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_stock_official_setting() {
	    $this->_initialize_admin();
	    $this->redirect_admin();

	    $segment = 5;
	    $user_id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
	    $row = modules::run('users/get', $user_id);
	    $this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;
	    if(!(is_array($row) && !empty($row))){
	        redirect(get_admin_url($this->_module_slug));
	    }
	    $this->_data['row'] = $row;

	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation',
	        'name' => 'jquery.validate',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'jquery-validation/localization',
	        'name' => 'messages_vi',
	    );

	    $this->_plugins_css_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen.jquery',
	    );

	    $this->set_plugins_admin();

	    $this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-stock-official-setting',
	    );
	    $this->set_modules();

	    $post = $this->input->post();
	    if (!empty($post)) {
	    	$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('stock_official', 'Số lượng', 'trim|required');

			if ($this->form_validation->run($this)) {
		        $err = FALSE;
		        $stock_official = $this->input->post('stock_official');
		        $data = array(
		            'stock_official' => $stock_official
		        );
		        if (!$this->M_users->update($user_id, $data)) {
		            $err = TRUE;
		        }
		        if ($err === FALSE) {
		            $notify_type = 'success';
		            $notify_content = 'Đã cài đặt cổ phiếu chính thức cho người dùng ' . $row['full_name'] . '!';
		            $this->set_notify_admin($notify_type, $notify_content);

		            redirect(get_admin_url($this->_module_slug));
		        } else {
		            $notify_type = 'danger';
		            $notify_content = 'Có lỗi xảy ra!';
		            $this->set_notify_admin($notify_type, $notify_content);
		        }
		    }
	    }

	    $this->_data['title'] = 'Cài đặt cổ phiếu chính thức cho người dùng ' . $row['full_name'] . ' - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
	    $this->_data['main_content'] = 'users/admin/view_page_stock_official_setting';
	    $this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_lookups_reward() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker',
			'name' => 'app.editinfo',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->_plugins_css_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen',
	    );
	    $this->_plugins_script_admin[] = array(
	        'folder' => 'chosen',
	        'name' => 'chosen.jquery',
	    );

		$this->set_plugins_admin();

		$this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-lookups-reward',
	    );
	    $this->set_modules();

	    $get = $this->input->get();
		$this->_data['get'] = $get;
		
		$row_user = array();
		//thành viên chọn
		if (isset($get['user_id'])) {
			$user_id = (int) $get['user_id'];
			// $user_id = 1999;
			// $user_id = 4144;
			$row_user = $this->M_users->get($user_id);
			if(!(is_array($row_user) && !empty($row_user))){
				redirect(get_admin_url($this->_module_slug . '/lookups-reward'));
			}
		}
		$this->_data['row_user'] = $row_user;

		$rows = array();
		//theo ngay
		if (isset($get['month']) && trim($get['month']) != '') {
			//tính thưởng thêm theo doanh số
			$month = trim($get['month']);
			$time_month = strtotime("01-" . $month);
			$month_start = strtotime('first day of this month', $time_month);
			$month_end = strtotime('last day of this month', $time_month);
			// echo date('Y-m-d', $month_start);
			// echo "<br>" . date('Y-m-d', $month_end);
			// die;

			$options = array();
			$options['start_date_start'] = get_start_date(date('Y-m-d', $month_start));
			$options['start_date_end'] = get_end_date(date('Y-m-d', $month_end));

			$total_revenue_bonus = 0;
			$total_accumulated_F1 = 0;
			$users_F1 = $this->M_users->gets_F1($user_id);
			// debug_arr($users_F1); die;
			if(is_array($users_F1) && !empty($users_F1)){
			    foreach ($users_F1 as $user_F1) {
			        $accumulated_F1 = get_accumulated_commission_user_id($user_F1['userid'], $options);
			        /*if($accumulated_F1 >= 2000000){
			            $total_accumulated_F1 += $accumulated_F1;
			        }*/
			        $total_accumulated_F1 += $accumulated_F1;
			        $row_user_F1 = $this->M_users->get($user_F1['userid']);

			        $item = array();
			    	$item['user_id'] = $user_F1['userid'];
			    	$item['username'] = isset($row_user_F1['username']) ? $row_user_F1['username'] : '';
			    	$item['full_name'] = isset($row_user_F1['full_name']) ? $row_user_F1['full_name'] : '';
			    	$item['phone'] = isset($row_user_F1['phone']) ? $row_user_F1['phone'] : '';
			    	$item['accumulated'] = $accumulated_F1;
			        $rows[] = $item;
			        // debug_arr($item);
			    }
			}
			// if($total_accumulated_F1 > 0){
			if($total_accumulated_F1 >= 2000000){
			    $percent = 10;
			    $total_revenue_bonus = $total_accumulated_F1 * $percent / 100;
			}
			$this->_data['total_accumulated_F1'] = $total_accumulated_F1;
			$this->_data['total_revenue_bonus'] = $total_revenue_bonus;
			// die;
		}
		$this->_data['rows'] = $rows;

		$args_ref = $this->default_args();
	    $args_ref['role'] = 'AGENCY';
	    $this->_data['users'] = $this->M_users->gets_ref($args_ref);

		$this->_data['title'] = 'Tra cứu doanh số tích lũy trong tháng' . ' - ' . $this->_data['title'];
		$this->_data['main_content'] = 'users/admin/view_page_lookups_reward';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function set_reward() {
	    $get = $this->input->get();
		$this->_data['get'] = $get;
		
		$bool = FALSE;
		// $bool = TRUE;
		$year = date('Y');
		$last_month = date('m', strtotime("-1 month"));
		// echo $last_month;
		// die;
		$months = array($last_month);
		debug_arr($months);
		foreach ($months as $number) {
		    $month = $number . '-' . $year;
		    // echo "<br>" . $month;
			$time_month = strtotime("01-" . $month);
			$month_start = strtotime('first day of this month', $time_month);
			$month_end = strtotime('last day of this month', $time_month);
			echo "<br>" . date('Y-m-d', $month_start);
			echo "<br>" . date('Y-m-d', $month_end);

			$options = array();
			$options['start_date_start'] = get_start_date(date('Y-m-d', $month_start));
			$options['start_date_end'] = get_end_date(date('Y-m-d', $month_end));
			echo "<br>" . date('Y-m-d_H-i-s', $options['start_date_start']);
			echo "<br>" . date('Y-m-d_H-i-s', $options['start_date_end']);

			//tim danh sach cac F0 cua F1 co giao dich trong thang
			$users_commission_F0_in_month = $this->M_users_commission->gets_F0_in_month($options);
			// debug_arr($users_commission_F0_in_month);
			// die;
			// $time = time();
			$time = $options['start_date_end'];
			if($bool && is_array($users_commission_F0_in_month) && !empty($users_commission_F0_in_month)){
			    foreach ($users_commission_F0_in_month as $user_F0) {
			    	$user_id = $user_F0['userid'];
			    	if($user_id != 0){
				    	$total_revenue_bonus = 0;
				    	$total_accumulated_F1 = 0;
				    	$users_F1 = $this->M_users->gets_F1($user_id);
				    	// var_dump($users_F1); die;
				    	if(is_array($users_F1) && !empty($users_F1)){
				    	    foreach ($users_F1 as $user_F1) {
				    	        $accumulated_F1 = get_accumulated_commission_user_id($user_F1['userid'], $options);
				    	        /*if($accumulated_F1 >= 2000000){
					    	        $total_accumulated_F1 += $accumulated_F1;
					    	    }*/
					    	    $total_accumulated_F1 += $accumulated_F1;
				    	    }
				    	}
				    	// if($total_accumulated_F1 > 0){
				    	if($total_accumulated_F1 >= 2000000){
				    	    $percent = 10;
				    	    $total_revenue_bonus = $total_accumulated_F1 * $percent / 100;
			    			$payment = 'CREDIT_CARD';
			                $action = 'REVENUE_BONUS';
			                $value_cost = $total_accumulated_F1;
			                $value = $total_revenue_bonus;
			                $data_commission = array(
			                    'order_id' => NULL,
			                    'user_id' => $user_id,
			                    'extend_by' => NULL,
			                    'action' => $action,
			                    'payment' => $payment,
			                    'value_cost' => $value_cost,
			                    'percent' => $percent,
			                    'value' => $value,
			                    'message' => 'Cộng vào ví tiền thưởng kpi',
			                    'status' => 1,
			                    'created' => $time,
			                    'verified' => $time,
			                    'verify_by' => $this->_data['userid']
			                );
			                $commission_args = array(
			                    'user_id' => $user_id,
			                    'in_action' => array($action),
			                    'start_date_start' => $options['start_date_start'],
        						'start_date_end' => $options['start_date_end'],
			                );
			                $commission_row = $this->M_users_commission->get($commission_args);
			                if(!(is_array($commission_row) && !empty($commission_row))){
			                	$this->M_users_commission->add($data_commission);
			                }
				    	}
				    	
				    }
			    }
			}
			echo "<br>" . "Completed!";
		}
	}

	function admin_reward_old() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker',
			'name' => 'app.editinfo',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->set_plugins_admin();

		$this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-reward',
	    );
	    $this->set_modules();

	    $get = $this->input->get();
		$this->_data['get'] = $get;
		
		$rows = array();
		//theo ngay
		if (isset($get['month']) && trim($get['month']) != '') {
			$month = trim($get['month']);
			$time_month = strtotime("01-" . $month);
			$month_start = strtotime('first day of this month', $time_month);
			$month_end = strtotime('last day of this month', $time_month);
			/*echo date('Y-m-d', $month_start);
			echo "<br>" . date('Y-m-d', $month_end);
			die;*/

			$options = array();
			$options['start_date_start'] = get_start_date(date('Y-m-d', $month_start));
			$options['start_date_end'] = get_end_date(date('Y-m-d', $month_end));

			//tim danh sach cac F0 cua F1 co giao dich trong thang
			$users_commission_F0_in_month = $this->M_users_commission->gets_F0_in_month($options);
			// debug_arr($users_commission_F0_in_month);
			// die;
			$time = time();
			if(is_array($users_commission_F0_in_month) && !empty($users_commission_F0_in_month)){
			    foreach ($users_commission_F0_in_month as $user_F0) {
			    	$user_id = $user_F0['userid'];
			    	if($user_id != 0){
			    		// $item = $user_F0;
				    	$total_revenue_bonus = 0;
				    	$total_accumulated_F1 = 0;
				    	$users_F1 = $this->M_users->gets_F1($user_id);
				    	// var_dump($users_F1); die;
				    	if(is_array($users_F1) && !empty($users_F1)){
				    	    foreach ($users_F1 as $user_F1) {
				    	        $accumulated_F1 = get_accumulated_user_id($user_F1['userid'], $options);
				    	        /*if($accumulated_F1 >= 2000000){
					    	        $total_accumulated_F1 += $accumulated_F1;
					    	    }*/
					    	    $total_accumulated_F1 += $accumulated_F1;
				    	    }
				    	}
				    	// if($total_accumulated_F1 > 0){
				    	if($total_accumulated_F1 >= 2000000){
				    	    $percent = 10;
				    	    $total_revenue_bonus = $total_accumulated_F1 * $percent / 100;

				    	    $item = $user_F0;
				    	    $item['total_accumulated_F1'] = $total_accumulated_F1;
				    		$item['total_revenue_bonus'] = $total_revenue_bonus;

				    		$rows[] = $item;

				    		/*if($month == '07-2023' && isset($get['token']) && trim($get['token']) === 'da7feeb9d1b01101a21c9038aeb7d79c'){
				    			// echo 'OK'; die;
				    			$payment = 'CREDIT_CARD';
				                $action = 'REVENUE_BONUS';
				                $value_cost = $total_accumulated_F1;
				                $value = $total_revenue_bonus;
				                $data_commission = array(
				                    'order_id' => NULL,
				                    'user_id' => $user_id,
				                    'extend_by' => NULL,
				                    'action' => $action,
				                    'payment' => $payment,
				                    'value_cost' => $value_cost,
				                    'percent' => $percent,
				                    'value' => $value,
				                    'message' => 'Cộng vào ví tiền thưởng kpi',
				                    'status' => 1,
				                    'created' => $time,
				                    'verified' => $time,
				                    'verify_by' => $this->_data['userid']
				                );
			                    $this->M_users_commission->add($data_commission);
				    		}*/
				    	}
				    	
				    }
			    }
			}
		}
		$this->_data['rows'] = $rows;

		$this->_data['title'] = 'Thưởng thêm doanh số trong tháng ' . ' - ' . $this->_data['title'];
		$this->_data['main_content'] = 'users/admin/view_page_reward';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_reward() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$this->_module_slug = 'users';
	    $this->_data['module_slug'] = $this->_module_slug;

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker',
			'name' => 'app.editinfo',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->set_plugins_admin();

		$this->_modules_script[] = array(
	        'folder' => 'users',
	        'name' => 'admin-reward',
	    );
	    $this->set_modules();

	    $get = $this->input->get();
		$this->_data['get'] = $get;
		
		$rows = array();
		//theo ngay
		if (isset($get['month']) && trim($get['month']) != '') {
			$month = trim($get['month']);
			$time_month = strtotime("01-" . $month);
			$month_start = strtotime('first day of this month', $time_month);
			$month_end = strtotime('last day of this month', $time_month);
			/*echo date('Y-m-d', $month_start);
			echo "<br>" . date('Y-m-d', $month_end);
			die;*/

			$options = array(
				'in_action' => array('REVENUE_BONUS')
			);

			$options['start_date_start'] = get_start_date(date('Y-m-d', $month_start));
			$options['start_date_end'] = get_end_date(date('Y-m-d', $month_end));
			$rows = $this->M_users_commission->gets($options);
		}
		$this->_data['rows'] = $rows;

		$this->_data['title'] = 'Thưởng thêm doanh số trong tháng ' . ' - ' . $this->_data['title'];
		$this->_data['main_content'] = 'users/admin/view_page_reward';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_reward_export_excel() {
        $this->_initialize_admin();
		$this->redirect_admin();

        $get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'users/reward';

		$args = array(
			'action' => 'REVENUE_BONUS',
		);	
		//theo ngay
		if (isset($get['month']) && trim($get['month']) != '') {
			$month = trim($get['month']);
			$time_month = strtotime("01-" . $month);
			$month_start = strtotime('first day of this month', $time_month);
			$month_end = strtotime('last day of this month', $time_month);
			// echo date('Y-m-d', $month_start);
			// echo "<br>" . date('Y-m-d', $month_end);
			// die;

			$args['start_date_start'] = get_start_date(date('Y-m-d', $month_start));
			$args['start_date_end'] = get_end_date(date('Y-m-d', $month_end));
			// echo date('Y-m-d H:i:s', $args['start_date_start']);
			// echo "<br>" . date('Y-m-d H:i:s', $args['start_date_end']);
			// die;
		}else{
            $notify_type = 'danger';
            $notify_content = 'Chưa chọn tháng lấy dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

		$rows = $this->M_users_commission->gets($args);
		// var_dump($rows); die;
        if (!(is_array($rows) && !empty($rows))) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug . '?month=' . $month));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'G';
        $letterColumn = range($firstColumn, $lastColumn);

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'name' => 'Arial',
            'bold' => true,
            'color' => array(
                'rgb' => '333300',
            ),
        );

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3582F4'),
            ),
        );

        $file_title = "Trả thưởng tháng " . $month;
        $this->excel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle($file_title)
            ->setSubject($file_title)
            ->setDescription($file_title)
            ->setKeywords($file_title)
            ->setCategory($file_title);
        $this->excel->getActiveSheet()->setTitle($file_title);

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', "STT")
            ->setCellValue('B1', "ID")
            ->setCellValue('C1', "Tài khoản")
            ->setCellValue('D1', "Họ tên")
            ->setCellValue('E1', "Điện thoại")
            ->setCellValue('F1', "Tổng doanh thu trực tiếp")
            ->setCellValue('G1', "Thưởng doanh thu trực tiếp");

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->applyFromArray($styleAlignmentRight);
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->applyFromArray($styleAlignmentRight);
        $this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->applyFromArray($styleAlignmentRight);

        foreach ($letterColumn as $column) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $i = 2;
        $sort = 0;
        foreach ($rows as $row) {
        	$sort++;
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $sort)
                ->setCellValue('B' . $i, $row['id'])
                ->setCellValue('C' . $i, $row['username'])
                ->setCellValue('D' . $i, $row['full_name'])
                ->setCellValue('E' . $i, $row['phone'])
                ->setCellValue('F' . $i, $row['value_cost'])
                ->setCellValue('G' . $i, $row['value']);

            $this->excel->getActiveSheet()->setCellValueExplicit('E' . $i, strval($row['phone']), PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            $this->excel->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode($numberFormat);

            $i++;
        }

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13
                )
            )
        );

        $filename = 'Danh-sach-tra-thuong-thanh-vien-thang-' . $month . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    function admin_reward_export_excel_old() {
        $this->_initialize_admin();
		$this->redirect_admin();

        $get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'users/reward';

		$args = array(
			'action' => 'REVENUE_BONUS',
		);	
		//theo ngay
		if (isset($get['month']) && trim($get['month']) != '') {
			$month = trim($get['month']);
			$time_month = strtotime("01-" . $month);
			// $time_month = strtotime('next month', strtotime("01-" . $month));
			// $time_month = strtotime('last month', strtotime("01-" . $month));
			// $month = date('m-Y', $time_month);
			$month = date('m-Y', strtotime('last month', strtotime("01-" . $month)));
			
			$month_start = strtotime('first day of this month', $time_month);
			$month_end = strtotime('last day of this month', $time_month);
			// echo date('Y-m-d', $month_start);
			// echo "<br>" . date('Y-m-d', $month_end);
			// die;

			$args['start_date_start'] = get_start_date(date('Y-m-d', $month_start));
			$args['start_date_end'] = get_end_date(date('Y-m-d', $month_end));
			// echo date('Y-m-d H:i:s', $args['start_date_start']);
			// echo "<br>" . date('Y-m-d H:i:s', $args['start_date_end']);
			// die;
		}else{
            $notify_type = 'danger';
            $notify_content = 'Chưa chọn tháng lấy dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

		$rows = $this->M_users_commission->gets($args);
		// var_dump($rows); die;
        if (!(is_array($rows) && !empty($rows))) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug . '?month=' . $month));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'G';
        $letterColumn = range($firstColumn, $lastColumn);

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'name' => 'Arial',
            'bold' => true,
            'color' => array(
                'rgb' => '333300',
            ),
        );

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3582F4'),
            ),
        );

        $file_title = "Trả thưởng tháng " . $month;
        $this->excel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle($file_title)
            ->setSubject($file_title)
            ->setDescription($file_title)
            ->setKeywords($file_title)
            ->setCategory($file_title);
        $this->excel->getActiveSheet()->setTitle($file_title);

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', "STT")
            ->setCellValue('B1', "ID")
            ->setCellValue('C1', "Tài khoản")
            ->setCellValue('D1', "Họ tên")
            ->setCellValue('E1', "Điện thoại")
            ->setCellValue('F1', "Tổng doanh thu trực tiếp")
            ->setCellValue('G1', "Thưởng doanh thu trực tiếp");

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('B1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->applyFromArray($styleAlignmentRight);
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->applyFromArray($styleAlignmentRight);
        $this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->applyFromArray($styleAlignmentRight);

        foreach ($letterColumn as $column) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $i = 2;
        $sort = 0;
        foreach ($rows as $row) {
        	$sort++;
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $sort)
                ->setCellValue('B' . $i, $row['id'])
                ->setCellValue('C' . $i, $row['username'])
                ->setCellValue('D' . $i, $row['full_name'])
                ->setCellValue('E' . $i, $row['phone'])
                ->setCellValue('F' . $i, $row['value_cost'])
                ->setCellValue('G' . $i, $row['value']);

            $this->excel->getActiveSheet()->setCellValueExplicit('E' . $i, strval($row['phone']), PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            $this->excel->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode($numberFormat);

            $i++;
        }

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13
                )
            )
        );

        $filename = 'Danh-sach-tra-thuong-thanh-vien-thang-' . $month . '_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

	function admin_profile() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker',
			'name' => 'app.editinfo',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-fileinput/css',
			'name' => 'fileinput',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-fileinput/js',
			'name' => 'fileinput.min',
		);

		$this->set_plugins_admin();

		$post = $this->input->post();
		if (!empty($post)) {
			$this->load->helper('language');
			$this->lang->load('form_validation', 'vietnamese');
			$this->lang->load('user', 'vietnamese');

			if ($this->input->post('userid')) {
				$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
				$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|callback_check_current_username_availablity|min_length[2]|max_length[60]');
				$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_current_email_availablity');
				$this->form_validation->set_rules('password', 'Mật khẩu', 'matches[passconf]|alpha_numeric');
				$this->form_validation->set_rules('passconf', 'Lặp lại mật khẩu', 'alpha_numeric');
				$this->form_validation->set_rules('full_name', 'Họ tên', 'trim|required|min_length[3]|max_length[60]');
				if ($this->form_validation->run($this)) {
					$userid = $this->_data['userid'];
					$username = $this->input->post('username');
					$current_photo = $this->get($userid);
					$data = array(
						'username' => $username,
						'email' => $this->input->post('email'),
						'full_name' => $this->input->post('full_name'),
						'gender' => $this->input->post('gender'),
						'birthday' => strtotime($this->input->post('birthday')),
						'identity_card_date' => strtotime($this->input->post('identity_card_date')),
						'sig' => $this->input->post('sig'),
						'question' => $this->input->post('question'),
						'answer' => $this->input->post('answer'),
						'view_mail' => ($this->input->post('view_mail') ? 1 : 0),
					);
					$password = $this->input->post('password');
					if (trim($password) != '') {
						$data['password'] = $this->__encrip_password($password);
					}
					if ($this->M_users->update($userid, $data)) {
						$logged_in_session = $this->session->userdata('logged_in');
						$logged_in_session['full_name'] = $this->input->post('full_name');
						$logged_in_session['username'] = $username;

						/*
							                         * upload avartar
						*/
						$input_name = 'photo';
						$info = modules::run('files/index', $input_name, $this->_path);
						if (isset($info['uploads'])) {
							$upload_images = $info['uploads']; // thông tin ảnh upload
							if ($_FILES[$input_name]['size'] != 0) {
								foreach ($upload_images as $value) {
									$file_name = $value['file_name']; //tên ảnh
									$logged_in_session['photo'] = $file_name;
									$data_images = array(
										'photo' => $file_name,
									);
									$this->M_users->update($userid, $data_images);
								}
								if ($current_photo['photo'] != 'no_avatar.jpg') {
									@unlink(FCPATH . $this->_path . $current_photo['photo']);
								}
							}
						}
						$this->session->set_userdata('logged_in', $logged_in_session);

						$notify_type = 'success';
						$notify_content = 'Thông tin tài khoản đã cập nhật!';
					} else {
						$notify_type = 'danger';
						$notify_content = 'Thông tin tài khoản chưa cập nhật!';
					}
					$this->set_notify_admin($notify_type, $notify_content);
					redirect(get_admin_url());
				}
			}
		}

		$groups = modules::run('users/groups/gets');
		$this->_data['groups'] = $groups;

		$userid = $this->_data['userid'];
		if ($userid != 0) {
			$this->_modules_script[] = array(
				'folder' => 'users',
				'name' => 'admin-edit-validate',
			);
			$this->set_modules();
			$data_userid = $this->get($userid);

			$this->load->module('users/groups_users');
			$group_id = $this->groups_users->get_group_id($userid);
			$data_userid['group_id'] = $group_id['group_id'];

			$this->_data['row'] = $data_userid;
			$this->_data['breadcrumbs_module_func'] = 'Cập nhật tài khoản';
			$this->_data['title'] = 'Cập nhật tài khoản - ' . $this->_data['title_seo'];
		}

		$this->_data['main_content'] = 'users/admin/view_page_profile';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function login_by() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $redirect_page = get_admin_url();
        if ($this->input->get('redirect_page')) {
            $redirect_page = base64_decode($this->input->get('redirect_page'));
        }
        if(!(isset($this->_data['role']) && in_array($this->_data['role'], array('ADMIN', 'ACCOUNTANT')))){
        	$notify_type = 'danger';
        	$notify_content = 'Bạn không có quyền thực hiện chức năng này!';
        	$this->set_notify_admin($notify_type, $notify_content);
        	redirect($redirect_page);
        }

        $segment = 3;
        $user_id = ($this->uri->segment($segment) == '') ? 0 : (int) $this->uri->segment($segment);
        if ($user_id == 0) {
            $notify_type = 'danger';
            $notify_content = 'Người dùng không tồn tại! Vui lòng thực hiện lại!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect($redirect_page);
        } elseif ($this->session->has_userdata('logged_in_by')) {
            $this->session->unset_userdata('logged_in_by');
        }
        $row = $this->get($user_id);
        // echo "<pre>";
        // print_r($row);
        // echo "</pre>";
        // die();
        if (is_array($row) && !empty($row)) {
            $sess_array = array(
                'userid' => $row['userid'],
                'username' => $row['username'],
                'full_name' => $row['full_name'],
                'photo' => $row['photo'],
                'group_id' => $row['group_id'],
                'role' => $row['role'],
                'is_wholesale' => $row['is_wholesale'],
            );
            $this->session->set_userdata('logged_in_by', $sess_array);
            redirect(base_url());
        } else {
            $notify_type = 'danger';
            $notify_content = 'Người dùng không tồn tại! Vui lòng thực hiện lại!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect($redirect_page);
        }
    }

	function redirect_administrator() {
		if (!isset($this->ROLE) || $this->ROLE != 'admin') {
			$notify_type = 'danger';
			$notify_content = $this->_message_banned;
			$this->set_notify_admin($notify_type, $notify_content);
			redirect(get_admin_url());
		}
	}

	function admin_content() {
		$this->_initialize_admin();
		$this->redirect_admin();

		// generate_qr_code('HuongDiep');

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker',
			'name' => 'app.editinfo',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-fileinput/css',
			'name' => 'fileinput',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-fileinput/js',
			'name' => 'fileinput.min',
		);

		$this->set_plugins_admin();

		$post = $this->input->post();
		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|callback_check_current_username_availablity|min_length[2]|max_length[60]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_current_email_availablity');
			$this->form_validation->set_rules('identity_number_card', 'Số chứng minh nhân dân', 'trim|required|callback_check_current_identity_number_card_availablity');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'matches[passconf]|alpha_numeric');
			$this->form_validation->set_rules('passconf', 'Lặp lại mật khẩu', 'alpha_numeric');
			$this->form_validation->set_rules('full_name', 'Họ tên', 'trim|required|min_length[3]|max_length[60]');

			if ($this->form_validation->run($this)) {
				$time = time();
				$role = $this->input->post('role');
				// $group_id = ($role == 'ADMIN') ? 6 : 3;
				$group_id = display_value_array($this->config->item('role_group'), $role);
				if ($this->input->post('userid')) {
					$userid = $this->input->post('userid');
					$username = $this->input->post('username');
					$data = array(
						'username' => $username,
						'email' => $this->input->post('email'),
						'full_name' => $this->input->post('full_name'),
						'role' => $role,
						'phone' => $this->input->post('phone'),
						'address' => $this->input->post('address'),
						'gender' => $this->input->post('gender'),
						'view_mail' => $this->input->post('view_mail') ? 1 : 0,
						'is_wholesale' => $this->input->post('is_wholesale') ? 1 : 0,
                    	'identity_number_card' => $this->input->post('identity_number_card'),
						'birthday' => strtotime($this->input->post('birthday')),
						'identity_card_date' => strtotime($this->input->post('identity_card_date')),
						'tax_identification_number' => $this->input->post('tax_identification_number'),
						'modified' => $time,
					);

					$password = $this->input->post('password');
					if (trim($password) != '') {
						$data['password'] = $this->__encrip_password($password);
					}
					if ($this->M_users->update($userid, $data)) {
						$data_groups_users = array(
							'group_id' => $group_id,
						);
						modules::run('users/groups_users/update', $userid, $data_groups_users);

						$this->_upload_images($userid, 'photo');
						$this->_upload_images($userid, 'identity_card_front');
                    	$this->_upload_images($userid, 'identity_card_back');

						$notify_type = 'success';
						$notify_content = 'Thông tin tài khoản đã cập nhật!';
					} else {
						$notify_type = 'danger';
						$notify_content = 'Thông tin tài khoản chưa cập nhật!';
					}
					$this->set_notify_admin($notify_type, $notify_content);
					redirect(get_admin_url('users'));
				} else {
					$username = $this->input->post('username');
					//$refer_key = $this->__encrip_password(random_string('unique'));
					$refer_key = $this->__get_refer_key();
                    $data = array(
						'referred_by' => 0,
						'referred_status' => 0,
						'refer_key' => $refer_key,
						'username' => $username,
						'password' => $this->__encrip_password($this->input->post('password')),
						'email' => $this->input->post('email'),
						'full_name' => $this->input->post('full_name'),
						'role' => $role,
						'phone' => $this->input->post('phone'),
						'address' => $this->input->post('address'),
						'gender' => $this->input->post('gender'),
						'regdate' => $time,
						'last_order_date' => $time,
						'view_mail' => $this->input->post('view_mail') ? 1 : 0,
						'is_wholesale' => $this->input->post('is_wholesale') ? 1 : 0,
						'active' => 1,
						'identity_number_card' => $this->input->post('identity_number_card'),
						'birthday' => strtotime($this->input->post('birthday')),
						'identity_card_date' => strtotime($this->input->post('identity_card_date')),
						'tax_identification_number' => $this->input->post('tax_identification_number'),
						'created' => $time,
						'modified' => 0,
					);

					$userid = $this->M_users->add($data);
					if ($userid != 0) {
						$data_groups_users = array(
							'group_id' => $group_id,
							'userid' => $userid,
						);
						modules::run('users/groups_users/add', $data_groups_users);

						$this->_upload_images($userid, 'photo');
						$this->_upload_images($userid, 'identity_card_front');
                    	$this->_upload_images($userid, 'identity_card_back');

						$this->load->library('ciqrcode');
						$file_name = $refer_key . '.png';
						//$params['data'] = site_url('dang-ky') . '?ref=' . $refer_key;
						$params['data'] = $refer_key;
						$params['level'] = 'L';
						$params['size'] = 3;
						$params['savename'] = get_module_path('users_qr') . $file_name;
						$this->ciqrcode->generate($params);

						$notify_type = 'success';
						$notify_content = 'Tài khoản mới đã thêm!';
					} else {
						$notify_type = 'danger';
						$notify_content = 'Chưa thêm tài khoản!';
					}
					$this->set_notify_admin($notify_type, $notify_content);
					redirect(get_admin_url('users'));
				}
			}
		}

		$user_id = ($this->uri->segment(4) == '') ? 0 : (int) $this->uri->segment(4);
		if ($user_id != 0) {
			$this->_modules_script[] = array(
				'folder' => 'users',
				'name' => 'admin-edit-validate',
			);
			$this->set_modules();
			$this->_data['row'] = $this->get($user_id);
			$this->_data['breadcrumbs_module_func'] = 'Cập nhật tài khoản';
			$this->_data['title'] = 'Cập nhật tài khoản - ' . $this->_data['title_seo'];
		} else {
			$this->_modules_script[] = array(
				'folder' => 'users',
				'name' => 'admin-add-validate',
			);
			$this->set_modules();
			$this->_data['breadcrumbs_module_func'] = 'Thêm tài khoản mới';
			$this->_data['title'] = 'Thêm tài khoản mới - ' . $this->_data['title_seo'];
		}

		$this->_data['main_content'] = 'users/admin/view_page_content';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	private function __encrip_password($password) {
		return md5($password);
	}

	private function __get_refer_key() {
		$refer_key = strtolower(random_string('alpha', 3)) . '-' . random_string('numeric', 3);
		while(!$this->M_users->check_refer_key_availablity($refer_key)){
            $refer_key = strtolower(random_string('alpha', 3)) . '-' . random_string('numeric', 3);
        }
        return $refer_key;
	}

	private function _upload_images($id, $input_name = '') {
	    $row = $this->get($id);
	    $info = modules::run('files/index', $input_name, $this->_path);
	    if (isset($info['uploads'])) {
	        $upload_images = $info['uploads'];
	        if ($_FILES[$input_name]['size'] != 0) {
	            foreach ($upload_images as $value) {
	                $file_name = $value['file_name'];
	                $data_images = array(
	                    $input_name => $file_name
	                );
	                $this->M_users->update($id, $data_images);
	            }
	            if(isset($row[$input_name]) && trim($row[$input_name]) != ''){
	                @unlink(FCPATH . $this->_path . $row[$input_name]);
	            }
	        }
	    }
	}

	function site_tree_system() {
		$this->_initialize();
		$this->require_logged_in();

		$this->_plugins_css[] = array(
			'folder' => 'treetable',
			'name' => 'jquery-treetable',
		);
		$this->_plugins_script[] = array(
			'folder' => 'treetable',
			'name' => 'd3.min',
		);
		$this->_plugins_script[] = array(
			'folder' => 'treetable',
			'name' => 'jquery-treetable',
		);
		$this->_plugins_script[] = array(
			'folder' => 'treetable',
			'name' => 'run',
		);
		$this->set_plugins();

		$this->output->cache(true);
		$args = $this->default_args();
		$users = $this->M_users->gets($args);
		// if(is_array($users) && !empty($users)){
		// 	foreach ($users as $key => $value) {
		// 		$user_id = $value['userid'];
		// 		$withdrawal = modules::run('users/users_commission/get_total_value', array(
		// 			'user_id' => $user_id,
		// 			'status' => 1,
		// 			'in_action' => array('WITHDRAWAL')
		// 		));
		// 		$total_commission_buy = modules::run('users/users_commission/get_total_value', array(
		// 			'user_id' => $user_id,
		// 			'status' => 1,
		// 			'in_action' => array('SUB_BUY', 'SUB_BUY_ROOT')
		// 		));
		// 		$revenue = $total_commission_buy - abs($withdrawal);
		// 		$users[$key]['revenue'] = formatRice($revenue);
		// 	}
		// }
		$this->_data['users'] = $users;

		$user_id = $this->_data['userid'];
		$this->_data['user_id'] = $user_id;
		$withdrawal = modules::run('users/users_commission/get_total_value', array(
			'user_id' => $user_id,
			'status' => 1,
			'in_action' => array('WITHDRAWAL')
		));
		$total_commission_buy = modules::run('users/users_commission/get_total_value', array(
			'user_id' => $user_id,
			'status' => 1,
			'in_action' => array('SUB_BUY', 'SUB_BUY_ROOT')
		));
		$revenue = $total_commission_buy - abs($withdrawal);
		$this->_data['revenue'] = $revenue;

		$this->_breadcrumbs[] = array(
			'url' => current_full_url(),
			'name' => 'Hệ thống',
		);
		$this->set_breadcrumbs();

		$this->_data['title_seo'] = 'Hệ thống - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/user-tree-system';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function site_register() {
		$this->_initialize();
		$this->deny_logged_in();

		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->_plugins_css[] = array(
	        'folder' => 'bootstrap-fileinput/css',
	        'name' => 'fileinput'
	    );
	    $this->_plugins_script[] = array(
	        'folder' => 'bootstrap-fileinput/js',
	        'name' => 'fileinput.min'
	    );
		$this->set_plugins();

		$this->_modules_script[] = array(
			'folder' => 'users',
			'name' => 'register-validate',
		);
		$this->set_modules();

		$ref = $this->input->get('ref');
		$this->_data['ref'] = $ref;

		$post = $this->input->post();
		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('full_name', 'Họ tên', 'trim|required|min_length[5]|max_length[255]');
			$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|min_length[6]|max_length[20]|callback_check_username_availablity');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[6]|max_length[255]|callback_check_email_availablity');
			$this->form_validation->set_rules('phone', 'Số điện thoại', 'trim|required|min_length[10]|max_length[10]|callback_check_phone_availablity');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|min_length[6]|max_length[255]');
			$this->form_validation->set_rules('password_confirm', 'Nhập lại mật khẩu', 'trim|required|min_length[6]|max_length[255]');
			$this->form_validation->set_rules('address', 'Địa chỉ', 'trim|required');
			$this->form_validation->set_rules('identity_number_card', 'Số chứng minh nhân dân', 'trim|required|callback_check_identity_number_card_availablity');

			if ($this->form_validation->run($this)) {
				/*if (!$this->check_username_availablity()) {
					$notify_type = 'danger';
					$notify_content = 'Tên đăng nhập này đã tồn tại. Vui lòng chọn tên khác!';
					$this->set_notify($notify_type, $notify_content);
					redirect(current_full_url());
				} elseif (!$this->check_email_availablity()) {
					$notify_type = 'danger';
					$notify_content = 'Email này đã có người sử dụng. Vui lòng nhập email khác!';
					$this->set_notify($notify_type, $notify_content);
					redirect(current_full_url());
				} else {
				}*/
				$referred_by = 53;
                $ref = $this->input->post('ref');
                if(trim($ref) != ''){
                    $user_refer = $this->M_users->get_by(array('username' => $ref));
                    if(isset($user_refer['userid'])){
                        $referred_by = (int)$user_refer['userid'];
                        // $limit = 3;
                        // $counts_refer = $this->M_users->counts(array('referred_by' => $referred_by));
                        // if(($counts_refer + 1) > $limit){
                        // 	$notify_type = 'danger';
                        // 	$notify_content = "Người dùng này đã có tối đa $limit F1. Vui lòng nhập người giới thiệu khác!";
                        // 	$this->set_notify($notify_type, $notify_content);
                        // 	redirect(current_full_url());
                        // }
                    }
                }
				$refer_key = $this->__get_refer_key();
				$activation_key = $this->__encrip_password(random_string('unique'));
				$email = $this->input->post('email');
				$username = $this->input->post('username');
				$time = time();
				$data = array(
					'referred_by' => $referred_by,
                    'referred_status' => 0,
                    'refer_key' => $refer_key,
					'username' => $username,
					'password' => $this->__encrip_password($this->input->post('password')),
					'email' => $email,
					'full_name' => $this->input->post('full_name'),
					'role' => 'AGENCY',
					'phone' => $this->input->post('phone'),
					'address' => $this->input->post('address'),
                	'identity_number_card' => $this->input->post('identity_number_card'),
                	'tax_identification_number' => $this->input->post('tax_identification_number'),
					'gender' => 'M',
					'regdate' => $time,
					'last_order_date' => $time,
					'birthday' => 0,
					'view_mail' => 0,
					//'activation_key' => $activation_key,
					'activation_key' => '',
					'active' => 1,
					'created' => $time,
					'modified' => 0,
				);

				$userid = $this->M_users->add($data);
				if ($userid != 0) {
					$data_groups_users = array(
						'group_id' => 3,
						'userid' => $userid,
					);
					$this->M_groups_users->add($data_groups_users);

					$this->_upload_images($userid, 'identity_card_front');
                	$this->_upload_images($userid, 'identity_card_back');

					$this->load->library('ciqrcode');
					// $qr_code = $refer_key;
					$qr_code = $username;
					$file_name = $qr_code . '.png';
					$params['data'] = site_url('dang-ky') . '?ref=' . $qr_code;
					// $params['data'] = $qr_code;
					$params['level'] = 'L';
					$params['size'] = 3;
					$params['savename'] = get_module_path('users_qr') . $file_name;
					$this->ciqrcode->generate($params);

					$partial = array();
					$partial['data'] = $data;
					$data_sendmail = array(
						'sender_email' => $this->_data['email'],
						'sender_name' => $this->_data['site_name'] . ' - ' . $this->_data['email'],
						'receiver_email' => $email, //mail nhan thư
						'subject' => 'Xác nhận đăng ký thành viên',
						'message' => $this->load->view('layout/site/partial/html-template-verify-email-address', $partial, true),
					);
					modules::run('emails/send_mail', $data_sendmail);

					$data_sendmail = array(
						'sender_email' => $email,
						'sender_name' => $this->_data['site_name'] . ' - Đăng ký thành viên - ' . $email,
						'receiver_email' => $this->_data['email'], //mail nhan thư
						'subject' => 'Đăng ký thành viên mới',
						'message' => $this->load->view('layout/site/partial/html-template-notify-new-member', $partial, true),
					);
					modules::run('emails/send_mail', $data_sendmail);

					$notify_type = 'success';
					$notify_content = 'Đăng ký thành công! Vui lòng kiểm tra email và đăng nhập!';
					$this->set_notify($notify_type, $notify_content);
					redirect(site_url('dang-nhap'));
				} else {
					$notify_type = 'danger';
					$notify_content = 'Tài khoản chưa được tạo! Vui lòng đăng ký lại!';
					$this->set_notify($notify_type, $notify_content);
					redirect(current_full_url());
				}
			}
		}
		$this->_breadcrumbs[] = array(
			'url' => current_full_url(),
			'name' => 'Đăng ký',
		);
		$this->_data['breadcrumbs'] = $this->_breadcrumbs;
		$this->_data['title_seo'] = 'Đăng ký' . ' - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/register';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function site_verify_email_address() {
		$this->_initialize();
		$this->deny_logged_in();

		if (!$this->input->get('u') || !$this->input->get('code')) {
			show_404();
		}
		$get = $this->input->get();

		$code = $this->input->get('code');
		$username = $this->input->get('u');
		$user = $this->M_users->get_by_activation_key($username, $code);
		if (empty($user)) {
			$notify_type = 'danger';
			$notify_content = 'Thành viên không tồn tại vui lòng đăng ký!';
			$this->set_notify($notify_type, $notify_content);
		} else {
			$data = array(
				'activation_key' => '',
				'active' => 1,
			);
			if ($this->M_users->update($user['userid'], $data)) {
				$notify_type = 'success';
				$notify_content = 'Xác nhận đăng ký thành công! Bạn có thể đăng nhập ngay bây giờ!';
				$this->set_notify($notify_type, $notify_content);
				redirect(site_url('dang-nhap'));
			} else {
				$notify_type = 'warning';
				$notify_content = 'Thành viên không tồn tại! Vui lòng kiểm tra lại!';
				$this->set_notify($notify_type, $notify_content);
			}
		}
		$this->_breadcrumbs[] = array(
			'url' => site_url('xac-nhan-thanh-vien') . '?' . http_build_query($get, '', "&"),
			'name' => 'Xác nhận đăng ký thành viên',
		);
		$this->_data['breadcrumbs'] = $this->_breadcrumbs;

		$this->_data['title'] = 'Xác nhận đăng ký thành viên - ' . $this->_data['title'];
		$this->_data['main_content'] = 'layout/site/pages/verify-email-address';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function site_forget_password() {
		$this->_initialize();
		$this->deny_logged_in();

		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);
		$this->set_plugins();

		$this->_modules_script[] = array(
			'folder' => 'users',
			'name' => 'forget-password-validate',
		);
		$this->set_modules();

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[255]');

			if ($this->form_validation->run($this)) {
				$email = $this->input->post('email');
				$user = $this->M_users->get_by_email($email);

				if (!empty($user)) {
					$data = array(
						'activation_key' => $this->__encrip_password(random_string('unique')),
					);
					if ($this->M_users->update($user['userid'], $data)) {
						$partial = array();
						$user['activation_key'] = $data['activation_key'];
						$partial['data'] = $user;
						$data_sendmail = array(
							'sender_email' => $this->_data['email'],
							'sender_name' => $this->_data['site_name'] . ' - ' . $this->_data['email'],
							'receiver_email' => $email, //mail nhan thư
							'subject' => 'Quên mật khẩu',
							'message' => $this->load->view('layout/site/partial/html-template-forget-password', $partial, true),
						);
						modules::run('emails/send_mail', $data_sendmail);

						$notify_type = 'success';
						$notify_content = 'Bạn hãy xem mail để lấy lại mật khẩu!';
						$this->set_notify($notify_type, $notify_content);
					} else {
						$notify_type = 'danger';
						$notify_content = 'Có lỗi xảy ra vui lòng thực hiện lại!';
						$this->set_notify($notify_type, $notify_content);
					}
				} else {
					$notify_type = 'danger';
					$notify_content = 'Email không tồn tại!';
					$this->set_notify($notify_type, $notify_content);
				}
			}
		}

		$this->_breadcrumbs[] = array(
			'url' => site_url('quen-mat-khau'),
			'name' => 'Quên mật khẩu',
		);
		$this->_data['breadcrumbs'] = $this->_breadcrumbs;

		$this->_data['title'] = 'Quên mật khẩu - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/forget-password';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function site_login_facebook_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Kiểm tra thông tin';

		$access_token = $this->input->post('access_token');
		$url = "https://graph.facebook.com/me?fields=id,name,email,picture,link,gender,birthday,locale,last_name,first_name,cover&access_token=$access_token";
		$postData = array();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

		$response = curl_exec($ch);

		curl_close($ch);

		$nested_object = json_decode($response);
		$userProfile = json_decode(json_encode($nested_object), true);
		//var_dump($userProfile);

		if (is_array($userProfile) && !empty($userProfile)) {
			// Preparing data for database insertion
			$data = array();
			$data['oauth_provider'] = 'facebook';
			$data['oauth_uid'] = $userProfile['id'];
			$data['email'] = (isset($userProfile['email']) && ($userProfile['email'] != NULL)) ? $userProfile['email'] : '';
			$data['gender'] = ($userProfile['gender'] == 'male') ? 'M' : 'F';
			$data['locale'] = $userProfile['locale'];
			$data['profile_url'] = 'https://www.facebook.com/' . $userProfile['id'];
			$data['picture_url'] = $userProfile['picture']['data']['url'];
			// Insert or update user data
			$isset_user = $this->M_users->checkUser($data);
			if (is_array($isset_user) && !empty($isset_user)) {
				$row = $isset_user;
				$sess_array = array(
					'userid' => $row['userid'],
					'username' => $row['username'],
					'full_name' => $row['full_name'],
					'photo' => $row['photo'],
					'group_id' => $row['group_id'],
				);
				$this->session->set_userdata('logged_in', $sess_array);
				$this->set_last_login($row['userid']);
				$this->set_last_ip($row['userid']);
				$this->set_last_agent($row['userid']);
				$this->M_users->update($row['userid'], array('modified' => time()));
			} else {
				$birthday_time = 0;
				if (isset($userProfile['birthday'])) {
					$birthday = explode('/', $userProfile['birthday']);
					if (isset($birthday[0]) && isset($birthday[1]) && isset($birthday[2])) {
						$birthday_time = strtotime($birthday[2] . "-" . $birthday[0] . "-" . $birthday[1]);
					}
				}

				$data['username'] = $data['email'];
				$data['password'] = '';
				$data['full_name'] = $userProfile['last_name'] . ' ' . $userProfile['first_name'];
				$data['phone'] = '';
				$data['address'] = '';
				$data['regdate'] = time();
				$data['birthday'] = $birthday_time;
				$data['view_mail'] = 0;
				$data['active'] = 1;
				$data['created'] = time();
				$data['modified'] = 0;

				$userid = $this->M_users->add($data);
				if ($userid != 0) {
					$data_groups_users = array(
						'group_id' => 3,
						'userid' => $userid,
					);
					$this->M_groups_users->add($data_groups_users);
				}

				$row = $this->M_users->get($userid);
				$sess_array = array(
					'userid' => $row['userid'],
					'username' => $row['username'],
					'full_name' => $row['full_name'],
					'photo' => $row['photo'],
					'group_id' => $row['group_id'],
				);
				$this->session->set_userdata('logged_in', $sess_array);
				$this->set_last_login($row['userid']);
				$this->set_last_ip($row['userid']);
				$this->set_last_agent($row['userid']);
			}

			$message['status'] = 'success';
			$message['content'] = array('redirect' => site_url());
			$message['message'] = 'success!';
		}
		echo json_encode($message);
		exit();
	}

	function site_forget_password_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$this->_initialize();
		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[255]');

			if ($this->form_validation->run($this)) {
				$email = $this->input->post('email');
				$user = $this->M_users->get_by_email($email);

				if (!empty($user)) {
					$data = array(
						'activation_key' => $this->__encrip_password(random_string('unique')),
					);
					if ($this->M_users->update($user['userid'], $data)) {
						$partial = array();
						$user['activation_key'] = $data['activation_key'];
						$partial['data'] = $user;
						$data_sendmail = array(
							'sender_email' => $this->_data['email'],
							'sender_name' => $this->_data['site_name'] . ' - ' . $this->_data['email'],
							'receiver_email' => $email, //mail nhan thư
							'subject' => 'Quên mật khẩu',
							'message' => $this->load->view('layout/site/partial/html-template-forget-password', $partial, true),
						);
						modules::run('emails/send_mail', $data_sendmail);

						$message['status'] = 'success';
						$message['content'] = null;
						$message['message'] = 'Bạn hãy xem mail để lấy lại mật khẩu!';
					} else {
						$message['status'] = 'danger';
						$message['content'] = null;
						$message['message'] = 'Có lỗi xảy ra vui lòng thực hiện lại!';
					}
				} else {
					$message['status'] = 'danger';
					$message['content'] = null;
					$message['message'] = 'Email không tồn tại!';
				}
			}
		}
		echo json_encode($message);
		exit();
	}

	function site_reset_password() {
		$this->_initialize();
		if (!$this->input->get('u') || !$this->input->get('code')) {
			show_404();
		}

		$code = $this->input->get('code');
		$username = $this->input->get('u');
		$user = $this->M_users->get_by_activation_key($username, $code);
		if (empty($user)) {
			redirect(site_url());
		}

		$post = $this->input->post();
		if (!empty($post)) {
			$this->load->helper('language');
			$this->lang->load('form_validation', 'vietnamese');
			$this->lang->load('user', 'vietnamese');

			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|min_length[6]|max_length[255]');
			$this->form_validation->set_rules('password_confirm', 'Nhập lại mật khẩu', 'trim|required|min_length[6]|max_length[255]');

			if ($this->form_validation->run($this)) {
				$pass = $this->input->post('password');
				$cpass = $this->input->post('password_confirm');
				if ($pass != $cpass) {
					$notify_type = 'danger';
					$notify_content = 'Mật khẩu xác nhận không đúng!!';
					$this->set_notify($notify_type, $notify_content);
					redirect(site_url('reset-mat-khau') . "?u=" . $username . "&code=" . $code);
				} else {
					$data = array(
						'password' => $this->__encrip_password($pass),
						'activation_key' => '',
					);
					if ($this->M_users->update($user['userid'], $data)) {
						$notify_type = 'success';
						$notify_content = 'Mật khẩu đã đổi thành công!<br><a href="' . site_url() . '">Về trang chủ</a>';
						$this->set_notify($notify_type, $notify_content);
						redirect(current_full_url());
					} else {
						$notify_type = 'danger';
						$notify_content = 'Mật khẩu chưa được đổi! Vui lòng thực hiện lại!';
						$this->set_notify($notify_type, $notify_content);
						redirect(site_url('reset-mat-khau') . "?u=" . $username . "&code=" . $code);
					}
				}
			}
		}
		$this->_data['code'] = $code;
		$this->_data['username'] = $username;
		$this->_breadcrumbs[] = array(
			'url' => current_full_url(),
			'name' => 'Mật khẩu mới',
		);
		$this->_data['breadcrumbs'] = $this->_breadcrumbs;

		$this->_data['title'] = 'Quên mật khẩu - ' . $this->_data['title'];
		$this->_data['main_content'] = 'layout/site/pages/reset-password';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function site_change_password_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('current_password', 'Mật khẩu hiện tại', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('password', 'Mật khẩu mới', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('password_confirm', 'Nhập lại mật khẩu mới', 'trim|required|min_length[6]');

			if ($this->form_validation->run($this)) {
				if (!$this->is_current_password()) {
					$message['status'] = 'danger';
					$message['content'] = null;
					$message['message'] = 'Mật khẩu hiện tại không đúng!';
				} else {
					$password_confirm = $this->input->post('password_confirm');
					$password = $this->input->post('password');
					if ($password_confirm != $password) {
						$message['status'] = 'danger';
						$message['content'] = null;
						$message['message'] = 'Mật khẩu xác nhận không đúng!';
					} else {
						$password = $this->__encrip_password($password);
						$userid = $this->_data['userid'];
						$data = array(
							'password' => $password,
						);
						if ($this->M_users->update($userid, $data)) {
							$message['status'] = 'success';
							$message['content'] = null;
							$message['message'] = 'Mật khẩu đã đổi thành công!';
						} else {
							$message['status'] = 'danger';
							$message['content'] = null;
							$message['message'] = 'Mật khẩu chưa được đổi! Vui lòng thực hiện lại!';
						}
					}
				}
			}
		}
		echo json_encode($message);
		exit();
	}

	function site_register_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$this->_initialize();
		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('full_name', 'Họ tên', 'trim|required|min_length[5]|max_length[255]');
			$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|min_length[5]|max_length[255]');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[255]');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|min_length[6]|max_length[255]');

			if ($this->form_validation->run($this)) {
				if (!$this->check_username_availablity()) {
					$message['status'] = 'danger';
					$message['content'] = null;
					$message['message'] = 'Tên đăng nhập này đã tồn tại. Vui lòng chọn tên khác!';
				} elseif (!$this->check_email_availablity()) {
					$message['status'] = 'danger';
					$message['content'] = null;
					$message['message'] = 'Email này đã có người sử dụng. Vui lòng nhập email khác!';
				} else {
					$referred_by = 0;
	                $ref = $this->input->post('ref');
	                if(trim($ref) != ''){
	                    $user_refer = $this->M_users->get_by(array('refer_key' => $ref));
	                    if(isset($user_refer['userid'])){
	                        $referred_by = (int)$user_refer['userid'];
	                    }
	                }
					//$refer_key = $this->__encrip_password(random_string('unique'));
					$refer_key = $this->__get_refer_key();
					$activation_key = $this->__encrip_password(random_string('unique'));
					$email = $this->input->post('email');
					$username = $this->input->post('username');
					$data = array(
						'referred_by' => $referred_by,
                        'referred_status' => 0,
                        'refer_key' => $refer_key,
						'username' => $username,
						'password' => $this->__encrip_password($this->input->post('password')),
						'email' => $email,
						'full_name' => $this->input->post('full_name'),
						'role' => 'AGENCY',
						'phone' => $this->input->post('phone'),
						'address' => '',
						'gender' => 'M',
						'regdate' => time(),
						'birthday' => 0,
						'view_mail' => 0,
						'activation_key' => $activation_key,
						'active' => 0,
					);

					$userid = $this->M_users->add($data);
					if ($userid != 0) {
						$data_groups_users = array(
							'group_id' => 3,
							'userid' => $userid,
						);
						$this->M_groups_users->add($data_groups_users);

						$partial = array();
						$partial['data'] = $data;
						$data_sendmail = array(
							// 'sender_email' => get_first_element($this->_data['email']),
							// 'sender_name' => $this->_data['site_name'] . ' - ' . get_first_element($this->_data['email']),
							'sender_email' => $this->_data['email'],
							'sender_name' => $this->_data['site_name'] . ' - ' . $this->_data['email'],
							'receiver_email' => $email, //mail nhan thư
							'subject' => 'Xác nhận đăng ký thành viên',
							'message' => $this->load->view('layout/site/partial/html-template-verify-email-address', $partial, true),
						);
						modules::run('emails/send_mail', $data_sendmail);

						$data_sendmail = array(
							'sender_email' => $email,
							'sender_name' => $this->_data['site_name'] . ' - Đăng ký thành viên - ' . $email,
							'receiver_email' => $this->_data['email'], //mail nhan thư
							'subject' => 'Đăng ký thành viên mới',
							'message' => $this->load->view('layout/site/partial/html-template-notify-new-member', $partial, true),
						);
						modules::run('emails/send_mail', $data_sendmail);

						$message['status'] = 'success';
						$message['content'] = null;
						$message['message'] = 'Đăng ký thành công! Mời bạn xác nhận email để kích hoạt và đăng nhập!';
					} else {
						$message['status'] = 'danger';
						$message['content'] = null;
						$message['message'] = 'Tài khoản chưa được tạo! Vui lòng đăng ký lại!';
					}
				}
			}
		}
		echo json_encode($message);
		exit();
	}

	function site_login_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|min_length[3]|max_length[60]');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|min_length[6]|max_length[60]');

			if ($this->form_validation->run($this)) {
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$encrip_password = $this->__encrip_password($password);

				$row = $this->M_users->validate_login($username, $encrip_password);

				if (!empty($row)) {
					$sess_array = array(
						'userid' => $row['userid'],
						'username' => $row['username'],
						'full_name' => $row['full_name'],
						'photo' => $row['photo'],
						'group_id' => $row['group_id'],
	                    'role' => $row['role'],
					);
					$this->session->set_userdata('logged_in', $sess_array);
					$this->set_last_login($row['userid']);
					$this->set_last_ip($row['userid']);
					$this->set_last_agent($row['userid']);
					$message['status'] = 'success';
					$message['content'] = array('data' => $row);
					$message['message'] = 'Đăng nhập thành công!';
				} else {
					$message['status'] = 'danger';
					$message['content'] = null;
					$message['message'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
				}
			}
		}
		echo json_encode($message);
		exit();
	}

	function site_login() {
		$this->_initialize();
		$this->deny_logged_in();

		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);
		$this->set_plugins();

		$this->_modules_script[] = array(
			'folder' => 'users',
			'name' => 'login-validate',
		);
		$this->set_modules();

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required|min_length[3]|max_length[60]');
			$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required|min_length[6]|max_length[60]');

			if ($this->form_validation->run($this)) {
				$username = $this->input->post('username');
				$password = $this->input->post('password');
				$encrip_password = $this->__encrip_password($password);

				$row = $this->M_users->validate_login($username, $encrip_password);

				if (!empty($row)) {
					$sess_array = array(
						'userid' => $row['userid'],
						'username' => $row['username'],
						'full_name' => $row['full_name'],
						'photo' => $row['photo'],
						'group_id' => $row['group_id'],
						'role' => $row['role'],
						'is_wholesale' => $row['is_wholesale'],
					);
					$this->session->set_userdata('logged_in', $sess_array);
					$this->set_last_login($row['userid']);
					$this->set_last_ip($row['userid']);
					$this->set_last_agent($row['userid']);
					if ($this->input->get('redirect_page')) {
						$redirect_page = base64_decode($this->input->get('redirect_page'));
					} else {
						$redirect_page = base_url();
					}
					redirect($redirect_page);
				} else {
					$notify_type = 'danger';
					$notify_content = 'Tên đăng nhập hoặc mật khẩu không đúng!';
					$this->set_notify($notify_type, $notify_content);
				}
			}
		}

		$this->_breadcrumbs[] = array(
			'url' => base_url('dang-nhap'),
			'name' => 'Đăng nhập',
		);
		$this->set_breadcrumbs();

		$this->_data['title_seo'] = 'Đăng nhập' . ' - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/login';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function admin_index() {
		$this->_initialize_admin();
		if ($this->validate_admin_logged_in()) {
			$this->load->module('dashboard');
			$this->dashboard->index();
		} else {
			$this->_data['title'] = 'Đăng nhập quản trị - ' . $this->_data['title_seo'];
			$minutes = 1;
			$max_time_in_seconds = $minutes * 60;
			$max_attempts = 3;
			$ip = $_SERVER['REMOTE_ADDR'];
			$login_attempt_count = $this->login_attempt_count();
			$post = $this->input->post();
			if (!empty($post)) {
				$this->form_validation->set_rules('username', 'Tên đăng nhập', 'trim|required');
				$this->form_validation->set_rules('password', 'Mật khẩu', 'trim|required');

				if ($this->form_validation->run($this)) {
					if ($this->validate_login()) {
						$this->load->model('users/m_users_attempts', 'M_users_attempts');
						$remove = $this->M_users_attempts->delete(array('ip' => $ip));
						redirect(get_admin_url());
					} else {
						$this->login_attempt($max_time_in_seconds);
						$login_attempt_count = $this->login_attempt_count();
						if($login_attempt_count < $max_attempts) {
							$this->_data['messing'] = 'Tên đăng nhập hoặc mật khẩu chưa đúng!';
							$this->load->view('admin/view_page_admin_login', $this->_data);
						}else{
							redirect(get_admin_url());
							/*
							$this->_data['ip'] = $ip;
							$this->_data['messing'] = 'Bạn đã đăng nhập <span class="text-blue">sai quá ' . $max_attempts . ' lần</span> nên hiện <span class="text-blue">bị cấm trong ' . $minutes . ' phút</span>. Vui lòng thử lại sau <span class="text-expired" id="countdown">' . $max_time_in_seconds . 's</span>!';
							$this->_data['max_time_in_seconds'] = $max_time_in_seconds;
							$this->load->view('admin/view_page_admin_banned', $this->_data);
							*/
						}
					}
				}
			}else{
				if($login_attempt_count < $max_attempts) {
					$this->load->view('admin/view_page_admin_login', $this->_data);
				} else {
					$this->_data['ip'] = $ip;
					$this->_data['messing'] = 'Bạn đã đăng nhập <span class="text-blue">sai quá ' . $max_attempts . ' lần</span> nên hiện <span class="text-blue">bị cấm trong ' . $minutes . ' phút</span>. Vui lòng thử lại sau <span class="text-expired" id="countdown">' . $max_time_in_seconds . 's</span>!';
					$this->_data['max_time_in_seconds'] = $max_time_in_seconds;
					$this->load->view('admin/view_page_admin_banned', $this->_data);
				}
			}
		}
	}

	function validate_current_password() {
		$username = $this->_data['username'];
		$current_password = $this->__encrip_password($this->input->post('current_password'));

		$result = $this->M_users->validate_current_password($username, $current_password);

		if (!$result) {
			$notify_type = 'danger';
			$notify_content = 'Mật khẩu hiện tại không đúng!';
			$this->set_notify($notify_type, $notify_content);
			$this->form_validation->set_message('validate_current_password', '%s không đúng!');
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function is_current_password() {
		$username = $this->_data['username'];
		$current_password = $this->__encrip_password($this->input->post('current_password'));

		$result = $this->M_users->validate_current_password($username, $current_password);

		if (!$result) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function validate_login() {
		$username = $this->input->post('username');
		$password = $this->__encrip_password($this->input->post('password'));
		$is_admin = TRUE;

		$row = $this->M_users->validate_login($username, $password, $is_admin);
		// var_dump($row); die;
		if ($row) {
			$sess_array = array(
				'userid' => $row['userid'],
				'username' => $row['username'],
				'full_name' => $row['full_name'],
				'photo' => $row['photo'],
				'regdate' => $row['regdate'],
				'group_id' => $row['group_id'],
				'role' => $row['role'],
				'is_wholesale' => $row['is_wholesale'],
			);
			$this->session->set_userdata('logged_in', $sess_array);
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function validate_admin_logged_in() {
		if (!$this->session->userdata('logged_in')) {
			return FALSE;
		} else {
			$session_data = $this->session->userdata('logged_in');
			if (isset($session_data['group_id']) && $session_data['group_id'] > 3) {
				return TRUE;
			} else {
				return FALSE;
			}
		}
	}

	function validate_user_logged_in() {
		if (!$this->session->userdata('logged_in')) {
			return FALSE;
		} else {
			return TRUE;
		}
	}

	function deny_logged_in() {
		if ($this->session->userdata('logged_in')) {
			redirect(base_url());
		}
	}

	function require_logged_in() {
		if (!$this->session->userdata('logged_in')) {
			if ($this->input->post('ajax') == '1') {
				$this->_status = "danger";
				$this->_message = "Bạn không có quyền truy cập vào trang này!";

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
				exit();
			} else {
				$notify_type = 'danger';
				$notify_content = 'Mời bạn đăng nhập để sử dụng chức năng này!';
				$this->set_notify($notify_type, $notify_content);
				redirect(site_url('dang-nhap') . '?redirect_page=' . base64_encode(current_full_url()));
			}
		}
	}

	function require_admin_logged_in() {
		if (!$this->validate_admin_logged_in()) {
			if ($this->input->post('ajax') == '1') {
				$this->_status = "danger";
				$this->_message = "Bạn không có quyền truy cập vào trang này!";

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
				exit;
			} else {
				redirect(base_url());
			}
		}
	}

	function logout() {
        if ($this->session->has_userdata('logged_in_by')) {
            $this->session->unset_userdata('logged_in_by');
            redirect(get_admin_url());
        } else {
            $this->session->sess_destroy();
        }
        redirect(base_url());
    }

	function site_changepass() {
		$this->_initialize();
		$this->require_logged_in();

		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);
		$this->set_plugins();

		$this->_modules_script[] = array(
			'folder' => 'users',
			'name' => 'changepass-validate',
		);
		$this->set_modules();

		$post = $this->input->post();

		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('current_password', 'Mật khẩu hiện tại', 'trim|required|min_length[6]|callback_validate_current_password');
			$this->form_validation->set_rules('password', 'Mật khẩu mới', 'trim|required|min_length[6]');
			$this->form_validation->set_rules('password_confirm', 'Nhập lại mật khẩu mới', 'trim|required|min_length[6]');

			if ($this->form_validation->run($this)) {
				$password_confirm = $this->input->post('password_confirm');
				$password = $this->input->post('password');
				if ($password_confirm != $password) {
					$notify_type = 'danger';
					$notify_content = 'Mật khẩu xác nhận không đúng!!';
					$this->set_notify($notify_type, $notify_content);
					redirect(site_url('doi-mat-khau'));
				}

				$password = $this->__encrip_password($password);
				$userid = $this->_data['userid'];
				$data = array(
					'password' => $password,
				);
				if ($this->M_users->update($userid, $data)) {
					$notify_type = 'success';
					$notify_content = 'Mật khẩu đã đổi thành công!';
				} else {
					$notify_type = 'danger';
					$notify_content = 'Mật khẩu chưa được đổi! Vui lòng thực hiện lại!';
				}
				$this->set_notify($notify_type, $notify_content);
				redirect(site_url('doi-mat-khau'));
			}
		}
		$this->_data['user'] = $this->M_users->get_by_username($this->_data['username']);

		$this->_breadcrumbs[] = array(
			'url' => site_url('doi-mat-khau'),
			'name' => 'Đổi mật khẩu',
		);
		$this->set_breadcrumbs();

		$this->_data['title_seo'] = 'Đổi mật khẩu - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/changepass';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function site_profile() {
		$this->_initialize();
        $this->require_logged_in();
		
		$this->_plugins_css[] = array(
            'folder' => 'bootstrap-datepicker/css',
            'name' => 'bootstrap-datepicker'
        );
        $this->_plugins_css[] = array(
            'folder' => 'bootstrap-datepicker/css',
            'name' => 'bootstrap-datepicker3'
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker/js',
            'name' => 'bootstrap-datepicker'
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker/locales',
            'name' => 'bootstrap-datepicker.vi.min'
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker',
            'name' => 'app.editinfo'
        );
        $this->_plugins_script[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );
        $this->_plugins_script[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi'
        );
		$this->_plugins_script[] = array(
			'folder' => 'clipboard.js/dist',
			'name' => 'clipboard',
		);
        $this->set_plugins();

        $this->_modules_script[] = array(
            'folder' => 'users',
            'name' => 'profile-validate'
        );
        $this->set_modules();

        $post = $this->input->post();

        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('full_name', 'Họ tên', 'trim|required|min_length[6]');
            $this->form_validation->set_rules('address', 'Địa chỉ', 'trim|required');
            $this->form_validation->set_rules('phone', 'Số điện thoại', 'trim|required|exact_length[10]');

            if ($this->form_validation->run($this)) {
                $userid = $this->_data['userid'];
                // $birthday_day = $this->input->post('day');
                // $birthday_month = $this->input->post('month');
                // $birthday_year = $this->input->post('year');
                // $birthday = $birthday_day . '-' . $birthday_month . '-' . $birthday_year;
                $data = array(
                    'full_name' => $this->input->post('full_name'),
                    'phone' => $this->input->post('phone'),
                    // 'gender' => $this->input->post('gender'),
                    'birthday' => strtotime($this->input->post('birthday')),
                    'identity_card_date' => strtotime($this->input->post('identity_card_date')),
                    'address' => $this->input->post('address'),
                    'account_holder' => $this->input->post('account_holder'),
                    'account_number' => $this->input->post('account_number'),
                    'banker_id' => $this->input->post('banker_id'),
                    'branch_bank' => $this->input->post('branch_bank'),
                );

                if ($this->M_users->update($userid, $data)) {
                    $logged_in_session = $this->session->userdata('logged_in');
                    $logged_in_session['full_name'] = $this->input->post('full_name');
                    /*
					* upload avartar
					*/
					$row = $this->get($userid);
					$input_name = 'photo';
					$info = modules::run('files/index', $input_name, $this->_path);
					if (isset($info['uploads'])) {
						$upload_images = $info['uploads']; // thông tin ảnh upload
						if ($_FILES[$input_name]['size'] != 0) {
							foreach ($upload_images as $value) {
								$file_name = $value['file_name']; //tên ảnh
								$logged_in_session['photo'] = $file_name;
								$data_images = array(
									'photo' => $file_name,
								);
								$this->M_users->update($userid, $data_images);
							}
							@unlink(FCPATH . $this->_path . $row['photo']);
							$logged_in_session['photo'] = $file_name;
						}
					}
					$this->session->set_userdata('logged_in', $logged_in_session);

                    $notify_type = 'success';
                    $notify_content = 'Cập nhật thông tin cá nhân thành công!';
                    $this->set_notify($notify_type, $notify_content);
                } else {
                    $notify_type = 'danger';
                    $notify_content = 'Thông tin cá nhân chưa được đổi! Vui lòng thực hiện lại!';
                    $this->set_notify($notify_type, $notify_content);
                }
                redirect(site_url('trang-ca-nhan'));
            }
        }
        $this->_data['row'] = $this->get($this->_data['userid']);
        $this->_data['banker'] = modules::run('banker/gets');

        $this->_breadcrumbs[] = array(
            'url' => site_url('trang-ca-nhan'),
            'name' => 'Trang cá nhân'
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Trang cá nhân - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/profile';
        $this->load->view('layout/site/layout', $this->_data);
    }

	function active() {
		$post = $this->input->post();
		if (!empty($post)) {
			$active = $this->input->post('active');
			if ($this->update_active($active)) {
				if ($active == 1) {
					$notify_type = 'success';
					$notify_content = 'Thành viên đã được kích hoạt!';
				} else {
					$notify_type = 'warning';
					$notify_content = 'Đã tắt kích hoạt thành viên!';
				}
			} else {
				$notify_type = 'danger';
				$notify_content = 'Dữ liệu chưa lưu!';
			}
			$this->set_notify_admin($notify_type, $notify_content);
			$this->load->view('users/admin/notify', NULL);
		} else {
			redirect(base_url());
		}
	}

	function check_current_username_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'true';
		$this->_message_danger = 'false';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$username = $this->input->post('username');
				$userid = $this->input->post('userid');
				if ($this->M_users->check_current_username_availablity($username, $userid)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$username = $this->input->post('username');
				$userid = $this->input->post('userid');
				if ($this->M_users->check_current_username_availablity($username, $userid)) {
					return TRUE;
				} else {
					$this->form_validation->set_message(__FUNCTION__, '%s đã được sử dụng!');
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_current_email_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'true';
		$this->_message_danger = 'false';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$email = $this->input->post('email');
				$userid = $this->input->post('userid');
				if ($this->M_users->check_current_email_availablity($email, $userid)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$email = $this->input->post('email');
				$userid = $this->input->post('userid');
				if ($this->M_users->check_current_email_availablity($email, $userid)) {
					return TRUE;
				} else {
					$this->form_validation->set_message(__FUNCTION__, '%s đã được sử dụng!');
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_current_identity_number_card_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'true';
		$this->_message_danger = 'false';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$identity_number_card = $this->input->post('identity_number_card');
				$userid = $this->input->post('userid');
				if ($this->M_users->check_current_username_availablity($identity_number_card, $userid)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$identity_number_card = $this->input->post('identity_number_card');
				$userid = $this->input->post('userid');
				if ($this->M_users->check_current_identity_number_card_availablity($identity_number_card, $userid)) {
					return TRUE;
				} else {
					$this->form_validation->set_message(__FUNCTION__, '%s đã được sử dụng!');
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_current_password_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'Mật khẩu hiện tại đúng!';
		$this->_message_danger = 'Mật khẩu hiện tại không đúng!';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$current_password = $this->__encrip_password($this->input->post('current_password'));
				$username = $this->_data['username'];
				if ($this->M_users->check_current_password_availablity($username, $current_password)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$current_password = $this->__encrip_password($this->input->post('current_password'));
				$username = $this->_data['username'];
				if ($this->M_users->check_current_password_availablity($username, $current_password)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_username_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'Bạn có thể sử dụng tên đăng nhập này!';
		$this->_message_danger = 'Tên đăng nhập này đã có người sử dụng!';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$username = $this->input->post('username');
				if ($this->M_users->check_username_availablity($username)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}
				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$username = $this->input->post('username');
				if ($this->M_users->check_username_availablity($username)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_email_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'Bạn có thể sử dụng email này!';
		$this->_message_danger = 'Email này đã có người sử dụng!';

		$username = isset($this->_data['username']) ? $this->_data['username'] : '';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$email = $this->input->post('email');
				if ($this->M_users->check_email_availablity($email, $username)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";

					$this->_message = $this->_message_danger;
				}
				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$email = $this->input->post('email');
				if ($this->M_users->check_email_availablity($email)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_refer_key_availablity($refer_key = '') {
		$post = $this->input->post();
		$this->_message_success = 'Bạn có thể sử dụng tên đăng nhập này!';
		$this->_message_danger = 'Tên đăng nhập này đã có người sử dụng!';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$refer_key = $this->input->post('refer_key');
				if ($this->M_users->check_refer_key_availablity($refer_key)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}
				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				if ($this->M_users->check_refer_key_availablity($refer_key)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_phone_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'Bạn có thể sử dụng số điện thoại này!';
		$this->_message_danger = 'Số điện thoại này đã có người sử dụng!';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$phone = $this->input->post('phone');
				if ($this->M_users->check_phone_availablity($phone)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}
				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$phone = $this->input->post('phone');
				if ($this->M_users->check_phone_availablity($phone)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_identity_number_card_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'Bạn có thể sử dụng số chứng minh nhân dân này!';
		$this->_message_danger = 'Số chứng minh nhân dân này đã có người sử dụng!';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$identity_number_card = $this->input->post('identity_number_card');
				if ($this->M_users->check_identity_number_card_availablity($identity_number_card)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}
				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$identity_number_card = $this->input->post('identity_number_card');
				if ($this->M_users->check_identity_number_card_availablity($identity_number_card)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function update_active($active = 1) {
		$userid = $this->input->post('userid');
		$data = array(
			'active' => $active,
		);
		return $this->M_users->update($userid, $data);
	}

	function admin_delete() {
		$this->_initialize_admin();
		$this->_message_success = 'Đã xóa tài khoản và dữ liệu liên quan!';
		$this->_message_warning = 'Tài khoản này không tồn tại!';
		if ($this->validate_admin_logged_in() == TRUE) {
			if ($this->input->post('ajax') == '1') {
				$id = $this->input->post('id');
				if ($id != 0) {
					$row = $this->get($id);
					$this->load->module('users/groups_users');
					$group_id = $this->groups_users->get_group_id($id);
					if ($group_id['group_id'] < 4) {
						if ($this->M_users->delete($id)) {
							$this->groups_users->delete($id);
							if ($row['photo'] != 'no_avatar.jpg') {
								@unlink(FCPATH . $this->_path . $row['photo']);
							}
							@unlink(FCPATH . get_module_path('users_qr') . $row['refer_key'] . ".png");
							$this->_status = "success";
							$this->_message = $this->_message_success;
						} else {
							$this->_status = "danger";
							$this->_message = $this->_message_danger;
						}
					} else {
						$this->_status = "danger";
						$this->_message = "Không được xóa tài khoản admin!";
					}
				} else {
					$this->_status = "warning";
					$this->_message = $this->_message_warning;
				}

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$id = $this->input->get('id');
				if ($id != 0) {
					$row = $this->get($id);
					$this->load->module('users/groups_users');
					$group_id = $this->groups_users->get_group_id($id);
					if ($group_id['group_id'] < 4) {
						if ($this->M_users->delete($id)) {
							$this->load->module('users/groups_users');
							$this->groups_users->delete($id);
							if ($row['photo'] != 'no_avatar.jpg') {
								@unlink(FCPATH . $this->_path . $row['photo']);
							}
							@unlink(FCPATH . get_module_path('users_qr') . $row['refer_key'] . ".png");
							$notify_type = 'success';
							$notify_content = $this->_message_success;
						} else {
							$notify_type = 'danger';
							$notify_content = $this->_message_danger;
						}
					} else {
						$notify_type = "danger";
						$notify_content = "Không được xóa tài khoản admin!";
					}
				} else {
					$notify_type = 'warning';
					$notify_content = $this->_message_warning;
				}
				$this->set_notify_admin($notify_type, $notify_content);
				redirect(get_admin_url('users'));
			}
		} else {
			if ($this->input->post('ajax') == '1') {
				$this->_status = "danger";
				$this->_message = $this->_message_banned;

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$notify_type = 'danger';
				$notify_content = $this->_message_banned;
				$this->set_notify_admin($notify_type, $notify_content);
				redirect(get_admin_url('users'));
			}
		}
	}

}

/* End of file users.php */
/* Location: ./application/modules/users/controllers/users.php */

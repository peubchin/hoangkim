<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Sms extends Layout {

    private $_module_slug = 'sms';

    function __construct() {
        parent::__construct();
        $this->_data['module_slug'] = $this->_module_slug;

        $this->_data['breadcrumbs_module_name'] = 'SMS';
        $this->_breadcrumbs_admin[] = array(
            'url' => 'sms',
            'name' => $this->_data['breadcrumbs_module_name']
        );
        $this->set_breadcrumbs_admin();
    }

	function default_args() {
		$order_by = array(
			'status' => 'ASC',
            'created' => 'DESC'
		);
		$args = array();
		$args['order_by'] = $order_by;

		return $args;
	}

    function counts($options = array()) {
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
        return $this->M_sms->counts($args);
    }

	function validate_exist($args) {
        $data = $this->get($args);

        if (is_array($data) && !empty($data)) {
            return TRUE;
        }

        return FALSE;
    }

    function get($args) {
        return $this->M_sms->get($args);
    }

    function gets($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}
        return $this->M_sms->gets($args);
    }

    function add($data = NULL) {
        if (empty($data)) {
            return 0;
        }
        return $this->M_sms->add($data);
    }

    function update($args, $data) {
        return $this->M_sms->update($args, $data);
    }

    function delete($args) {
        return $this->M_sms->delete($args);
    }

    function admin_sms_setting() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('sms_api_key', 'SMS API Key', 'trim|required');
            $this->form_validation->set_rules('sms_secret_key', 'SMS SECRET Key', 'trim|required');
            $this->form_validation->set_rules('sms_brandname', 'Brandname', 'trim|required');
            $this->form_validation->set_rules('sms_expired', 'Hiệu lực OTP (giây)', 'trim|required|is_natural_no_zero');

            if ($this->form_validation->run($this)) {
                $data = array(
                    'sms_api_key' => $this->input->post('sms_api_key'),
                    'sms_secret_key' => $this->input->post('sms_secret_key'),
                    'sms_brandname' => $this->input->post('sms_brandname'),
                    'sms_expired' => $this->input->post('sms_expired')
                );
                modules::run('configs/update_systems', $data);

                $notify_type = 'success';
                $notify_content = 'Đã lưu cấu hình SMS!';
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(current_url());
            }
        }
        $this->_data['breadcrumbs_module_func'] = 'Cấu hình SMS';
        $this->_breadcrumbs_admin[] = array(
            'url' => 'setting',
            'name' => $this->_data['breadcrumbs_module_func']
        );
        $this->set_breadcrumbs_admin();

        $configs = $this->M_configs->get_configs();
        $this->_data['configs'] = modules::run('configs/set_configs', $configs);

        $this->_data['title'] = 'Cấu hình SMS - ' . $this->_data['title'];
        $this->_data['main_content'] = 'sms/admin/view_page_sms_setting';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_index() {
        $this->_initialize_admin();
        $this->redirect_admin();
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
            'folder' => 'sms',
            'name' => 'admin-items',
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        $order_by = array(
            'id' => 'DESC',
            // 'status' => 'ASC',
            // 'created' => 'DESC'
        );
        $args['order_by'] = $order_by;

        if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
        }

        $total = $this->counts($args);
        $perpage = 50;
        $segment = 3;

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

        if (!empty($get)) {
            $config['base_url'] = get_admin_url($this->_module_slug);
            $config['suffix'] = '?' . http_build_query($get, '', "&");
            $config['first_url'] = get_admin_url($this->_module_slug . '?' . http_build_query($get, '', "&"));
            $config['uri_segment'] = $segment;
        } else {
            $config['base_url'] = get_admin_url($this->_module_slug);
            $config['uri_segment'] = $segment;
        }

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);

        $this->_data['rows'] = $this->M_sms->gets($args, $perpage, $offset);
        $this->_data['pagination'] = $pagination;

        $this->_data['breadcrumbs_module_func'] = 'Hệ thống tin nhắn';

        $this->_data['title'] = 'Hệ thống tin nhắn - ' . $this->_data['title'];
        $this->_data['main_content'] = 'sms/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_content() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate',
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi',
        );

        $this->_plugins_script_admin[] = array(
			'folder' => 'jquery-mask',
			'name' => 'jquery.mask',
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
            'name' => 'admin-content-commission-validate',
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('user_id', 'Chọn thành viên', 'trim|required');
            $this->form_validation->set_rules('amount', 'Nhập số tiền cần nạp', 'trim|required');

            if ($this->form_validation->run($this)) {
                $err = FALSE;
                $user_id = $this->input->post('user_id');
	            $amount = filter_var($this->input->post('amount'), FILTER_SANITIZE_NUMBER_INT);
	            $action = 'PAY_IN';
	            $value_cost = $amount;
	            $percent = 0;
	            $value = $amount;
	            $message = $this->input->post('message');
	            $verify_by = isset($this->_data['userid']) ? $this->_data['userid'] : NULL;
                $time = time();
	            $data = array(
	                'order_id' => NULL,
	                'user_id' => $user_id,
	                'extend_by' => NULL,
	                'action' => $action,
	                'value_cost' => $value_cost,
	                'percent' => $percent,
	                'value' => $value,
	                'message' => $message,
	                'status' => 1,
	                'created' => $time,
	                'verified' => $time,
					'verify_by' => $verify_by
	            );

                $insert_id = $this->add($data);
                if ($insert_id == 0) {
                    $err = TRUE;
                }

                if ($err === FALSE) {
                    $row = $this->get(array(
                        'id' => $insert_id
                    ));
                    if(is_array($row) && !empty($row)){
                        $value = formatRice($row['value_cost']);
                        $this->load->model('users/m_users_notification', 'M_users_notification');
                        $action = 'USER_PAY_IN';
                        $data_notification = array(
                            'actor_id' => $verify_by,
                            'notifier_id' => $row['user_id'],
                            'action' => $action,
                            'object_id' => $row['id'],
                            'title' => 'Nạp tiền vào tài khoản',
                            'description' => "Nạp tiền thành công, tài khoản của bạn được +" . $value . "đ vào ví",
                            'message' => "Hệ thống xác nhận bạn đã nạp " . $value . " vào ví tài khoản",
                            'history' => NULL,
                            'status' => -1,
                            'created' => $time
                        );
                        $notification_id = $this->M_users_notification->add($data_notification);

                        //push notification
                        $this->load->model('users/m_users_fcm_register', 'M_users_fcm_register');
                        $args_fcm_register = array(
                            'user_id' => $row['user_id'],
                            'order_by' => array(
                                'id' => 'DESC'
                            )
                        );
                        $fcm_register = $this->M_users_fcm_register->get_by($args_fcm_register);
                        if(isset($fcm_register['token'])){
                            $registration_ids = array(
                                $fcm_register['token']
                            );
                            $title = "Thông báo nạp tiền vào tài khoản";
                            $body = "Nạp tiền thành công, tài khoản của bạn được +" . $value . "đ";
                            $notification_options = array(
                                'title' => $title,
                                'body' => $body,
                            );
                            $notification_data = array(
                                "title" => $title,
                                "message" => $body,
                                "image" => "https://ledu.vn/media/images/slide-le-du1.png",
                                "action" => "BUY",
                                "action_destination" => $notification_id
                            );
                            $push_notification = push_notification($registration_ids, $notification_options, $notification_data);
                        }
                    }

                    $notify_type = 'success';
                    $notify_content = 'Nạp tiền cho thành viên thành công!';
                    $this->set_notify_admin($notify_type, $notify_content);

                    redirect(get_admin_url($this->_module_slug));
                } else {
                    $notify_type = 'danger';
                    $notify_content = 'Có lỗi xảy ra!';
                    $this->set_notify_admin($notify_type, $notify_content);
                }
            }
        }
        $this->_data['users'] = modules::run('users/gets', array('role' => 'MEMBER'));

        $this->_data['title'] = 'Nạp tiền vào tài khoản - ' . $this->_data['title'];
        $this->_data['main_content'] = 'sms/admin/view_page_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_main() {
        $this->_initialize_admin();
        $this->redirect_admin();
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'delete') {
                $this->_message_success = 'Đã xóa các tin nhắn được chọn!';
                $this->_message_warning = 'Bạn chưa chọn tin nhắn nào!';
                $ids = $this->input->post('idcheck');

                if (is_array($ids) && !empty($ids)) {
                    foreach ($ids as $id) {
                        $row = $this->get(array('id' => $id));
                        if (!empty($row) && $this->M_sms->delete(array('id' => $id))) {
                            $notify_type = 'success';
                            $notify_content = $this->_message_success;
                        } else {
                            $notify_type = 'danger';
                            $notify_content = $this->_message_danger;
                        }
                    }
                } else {
                    $notify_type = 'warning';
                    $notify_content = $this->_message_warning;
                }
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url($this->_module_slug));
            } elseif ($action == 'content') {
                redirect(get_admin_url($this->_module_slug . '/content'));
            }
        } else {
            redirect(get_admin_url($this->_module_slug));
        }
    }

    function admin_delete() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->_message_success = 'Đã xóa tin nhắn!';
        $this->_message_warning = 'Tin nhắn này không tồn tại!';
        $id = $this->input->get('id');
        if ($id != 0) {
            if ($this->M_sms->delete(array('id' => $id))) {
                $notify_type = 'success';
                $notify_content = $this->_message_success;
            } else {
                $notify_type = 'danger';
                $notify_content = $this->_message_danger;
            }
        } else {
            $notify_type = 'warning';
            $notify_content = $this->_message_warning;
        }
        $this->set_notify_admin($notify_type, $notify_content);
        redirect(get_admin_url($this->_module_slug));
    }
}
/* End of file Sms.php */
/* Location: ./application/modules/sms/controllers/Sms.php */
<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Users_commission extends Layout {

    private $_module_slug = 'commission';

    function __construct() {
        parent::__construct();
        $this->_data['module_slug'] = $this->_module_slug;
    }

    function ajax_change_status() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $message = array();
        $message['status'] = 'warning';
        $message['content'] = null;
        $message['message'] = show_alert_warning('Kiểm tra lại dữ liệu');

        $post = $this->input->post();
        if (!empty($post)) {
            $this->_initialize_admin();
            $err = FALSE;
            $id = $this->input->post('id');
            $row = $this->get(array(
                'id' => $id
            ));
            if(!(is_array($row) && !empty($row))){
                $message['status'] = 'error';
                $message['content'] = null;
                $message['message'] = show_alert_danger('Giao dịch không tồn tại!');
                echo json_encode($message);
                exit();
            }
            $status = (int)$this->input->post('status');
            $verify_by = $this->_data['userid'];
            $time = time();
            $args = array(
                'id' => $id
            );
            $data = array(
                'status' => $status,
                'verified' => $time,
                'verify_by' => $verify_by
            );
            $bool = $this->update($args, $data);
            if ($bool) {
                if($status == 1){
                    if(in_array($row['action'], array('WITHDRAWAL'))){
                        $user_id = $row['user_id'];
                        $balance = get_balance_user($user_id, $row['id']);
                        $amount = $row['value_cost'];
                        $withdrawal_fee = abs($this->M_users_commission->get_total(array(
                            'in_action' => array('WITHDRAWAL_FEE'),
                            'extend_by' => $id
                        )));
                        $total = $amount + $withdrawal_fee;
                        if($balance < $total){
                            $args = array(
                                'id' => $id
                            );
                            $data = array(
                                'status' => 0,
                                'verified' => 0,
                                'verify_by' => NULL
                            );
                            $this->update($args, $data);
                            $message['status'] = 'error';
                            $message['content'] = null;
                            $message['message'] = show_alert_danger('Số dư tài khoản không đủ điều kiện thực hiện giao dịch!');
                            echo json_encode($message);
                            exit();
                        }else{
                            $this->M_users_commission->update(array(
                                'in_action' => array('WITHDRAWAL_FEE'),
                                'extend_by' => $id
                            ), $data);
                        }
                    }elseif(in_array($row['action'], array('WITHDRAWAL_BONUS'))){
                        $user_id = $row['user_id'];
                        $balance = get_current_revenue_bonus_user($user_id, $row['id']);
                        $amount = $row['value_cost'];
                        $withdrawal_bonus_fee = abs($this->M_users_commission->get_total(array(
                            'in_action' => array('WITHDRAWAL_BONUS_FEE'),
                            'extend_by' => $id
                        )));
                        $total = $amount + $withdrawal_bonus_fee;
                        if($balance < $total){
                            $args = array(
                                'id' => $id
                            );
                            $data = array(
                                'status' => 0,
                                'verified' => 0,
                                'verify_by' => NULL
                            );
                            $this->update($args, $data);
                            $message['status'] = 'error';
                            $message['content'] = null;
                            $message['message'] = show_alert_danger('Số dư tiền thưởng không đủ điều kiện thực hiện giao dịch!');
                            echo json_encode($message);
                            exit();
                        }else{
                            $this->M_users_commission->update(array(
                                'in_action' => array('WITHDRAWAL_FEE'),
                                'extend_by' => $id
                            ), $data);
                        } 
                    }elseif(in_array($row['action'], array('BUY'))){
                        $id = $row['order_id'];
                        $output_voucher = $this->M_shops_orders->get($id);
                        if($id == 0 || !(is_array($output_voucher) && !empty($output_voucher))){
                            $message['status'] = 'error';
                            $message['content'] = null;
                            $message['message'] = show_alert_success('Đơn hàng không tồn tại! Vui lòng kiểm tra lại!');
                            echo json_encode($message);
                            exit();
                        }
                        /*
                        $user_id = $row['user_id'];
                        $balance = get_balance_user($user_id);
                        $amount = $row['value_cost'];
                        if($balance < $amount){
                            $args = array(
                                'id' => $id
                            );
                            $data = array(
                                'status' => 0,
                                'verified' => 0,
                                'verify_by' => NULL
                            );
                            $this->update($args, $data);
                            $message['status'] = 'error';
                            $message['content'] = null;
                            $message['message'] = show_alert_danger('Số dư tài khoản không đủ điều kiện thực hiện giao dịch!');
                            echo json_encode($message);
                            exit();
                        }
                        */
                        $data_output_voucher = array(
                            'transaction_status' => $status,
                            'modified' => $time
                        );
                        if ($this->M_shops_orders->update($id, $data_output_voucher)) {
                            $data_commission = array(
                                'status' => $status,
                                'verified' => $time,
                                'verify_by' => $verify_by
                            );
                            $this->M_users_commission->update(array('order_id' => $id), $data_commission);
                            $this->M_users->update($row['user_id'], array('last_order_date' => $time));
                        }
                    }
                    $message['status'] = 'success';
                    $message['content'] = display_label('Khả dụng');
                    $message['message'] = show_alert_success('Xác nhận yêu cầu thành công!');
                }else{
                    if(in_array($row['action'], array('BUY'))){
                        $id = $row['order_id'];
                        $output_voucher = $this->M_shops_orders->get($id);
                        if($id == 0 || !(is_array($output_voucher) && !empty($output_voucher))){
                            $message['status'] = 'error';
                            $message['content'] = null;
                            $message['message'] = show_alert_success('Đơn hàng không tồn tại! Vui lòng kiểm tra lại!');
                            echo json_encode($message);
                            exit();
                        }
                        $data_output_voucher = array(
                            'transaction_status' => $status,
                            'modified' => $time
                        );
                        if ($this->M_shops_orders->update($id, $data_output_voucher)) {
                            $data_commission = array(
                                'status' => $status,
                                'verified' => $time,
                                'verify_by' => $verify_by
                            );
                            $this->M_users_commission->update(array('order_id' => $id), $data_commission);
                        }
                        $message['status'] = 'success';
                        $message['content'] = display_label('Đã hủy yêu cầu', 'danger');
                        $message['message'] = show_alert_success('Hủy đơn hàng thành công!');
                    }elseif(in_array($row['action'], array('WITHDRAWAL'))){
                        $data_commission = array(
                            'status' => $status,
                            'verified' => $time,
                            'verify_by' => $verify_by
                        );
                        $this->M_users_commission->update(array(
                            'in_action' => array('WITHDRAWAL_FEE'),
                            'extend_by' => $id
                        ), $data_commission);
                        $message['status'] = 'success';
                        $message['content'] = display_label('Đã hủy yêu cầu', 'danger');
                        $message['message'] = show_alert_success('Hủy yêu cầu rút tiền thành công!');
                    }else{
                        $message['status'] = 'success';
                        $message['content'] = display_label('Đã hủy yêu cầu', 'danger');
                        $message['message'] = show_alert_success('Hủy yêu cầu thành công!');
                    }
                }
            } else {
                $message['status'] = 'error';
                $message['content'] = null;
                $message['message'] = show_alert_danger('Có lỗi xảy ra! Vui lòng thực hiện lại!');
            }
        }
        echo json_encode($message);
        exit();
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
        return $this->M_users_commission->counts($args);
    }

	function validate_exist($args) {
        $data = $this->get($args);

        if (is_array($data) && !empty($data)) {
            return TRUE;
        }

        return FALSE;
    }

    function get($args) {
        return $this->M_users_commission->get($args);
    }

    function gets($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}
        return $this->M_users_commission->gets($args);
    }

    function get_total_value($options = array()) {
        $value = 0;
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
        $row = $this->M_users_commission->get_total_value($args);
        if(isset($row['value'])){
            $value = $row['value'];
        }
        return $value;
    }

    function add($data = NULL) {
        if (empty($data)) {
            return 0;
        }
        return $this->M_users_commission->add($data);
    }

    function update($args, $data) {
        return $this->M_users_commission->update($args, $data);
    }

    function delete($args) {
        return $this->M_users_commission->delete($args);
    }

    function site_withdrawal_bonus() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $user_id = $this->_data['userid'];
        $user = modules::run('users/get', $user_id);
        if(!(is_array($user) || !empty($user))){
            redirect(site_url('dang-nhap'));
        }
        $this->_data['user'] = $user;

        $this->_plugins_script[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );
        $this->_plugins_script[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi'
        );
        $this->_plugins_script[] = array(
            'folder' => 'jquery-mask',
            'name' => 'jquery.mask'
        );
        $this->set_plugins();

        $this->_modules_script[] = array(
            'folder' => 'users',
            'name' => 'withdrawal-validate'
        );
        $this->set_modules();

        /*$balance = get_current_revenue_bonus_user($user_id);
        var_dump($balance);
        die;*/

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<p class="required">', '</p>');;
            $this->form_validation->set_rules('amount', 'Số tiền rút', 'trim|required');

            if ($this->form_validation->run($this)) {
                $amount = filter_var($this->input->post('amount'), FILTER_SANITIZE_NUMBER_FLOAT);
                $fee_percent = 10;
                // $fee = $amount * $fee_percent / 100;
                $fee = 0;
                $total = $amount + $fee;
                $balance = get_current_revenue_bonus_user($user_id);
                // var_dump($balance);
                // die;

                $args_pending = array();
                $args_pending['user_id'] = $user_id;
                $args_pending['in_action'] = array('WITHDRAWAL_BONUS');
                $args_pending['status'] = 0;
                $counts_pending = $this->M_users_commission->counts($args_pending);

                if($amount % 1000 != 0){
                    $notify_type = 'danger';
                    $notify_content = 'Rút tiền thưởng không hợp lệ! Vui lòng thực hiện lại!';
                    $this->set_notify($notify_type, $notify_content);
                    redirect(current_url());
                }elseif($amount < WITHDRAWAL_BONUS_MIN){
                    $notify_type = 'danger';
                    $notify_content = 'Giới hạn rút ví tối thiểu là ' . get_money_VND(WITHDRAWAL_BONUS_MIN) . '/lần!';
                    $this->set_notify($notify_type, $notify_content);
                    redirect(current_url());
                }elseif($balance < $total){
                    $notify_type = 'danger';
                    $notify_content = 'Số dư tiền thưởng không đủ điều kiện thực hiện giao dịch!';
                    $this->set_notify($notify_type, $notify_content);
                    redirect(current_url());
                }elseif($counts_pending > 0){
                    $notify_type = 'danger';
                    $notify_content = 'Bạn có yêu cầu rút tiền thưởng đang xử lý! Vui lòng chờ chúng tôi hoàn tất yêu cầu đó và thực hiện lại giao dịch!';
                    $this->set_notify($notify_type, $notify_content);
                    redirect(current_url());
                /*}elseif($user['account_holder'] == '' || $user['account_number'] == '' || $user['branch_bank'] == ''){
                    $notify_type = 'danger';
                    $notify_content = 'Thông tin ngân hàng chưa đủ điều kiện thực hiện giao dịch!';
                    $this->set_notify($notify_type, $notify_content);
                    redirect(current_url());*/
                }else{
                    // if($user_id == 1){
                    //     echo 'OK admin: ' . formatRice($balance); die;
                    // }
                    $time = time();
                    $payment = 'CREDIT_CARD';
                    $action = 'WITHDRAWAL_BONUS';
                    $value_cost = $amount;
                    $percent = 0;
                    $value = $amount;
                    $data_commission = array(
                        'order_id' => NULL,
                        'user_id' => $user_id,
                        'extend_by' => $user['banker_id'],
                        'action' => $action,
                        'payment' => $payment,
                        'value_cost' => $value_cost,
                        'percent' => $percent,
                        'value' => $value,
                        'message' => 'Thành viên rút tiền thưởng từ tiền thưởng doanh số',
                        'status' => 0,
                        'created' => $time
                    );
                    $commission_id = $this->M_users_commission->add($data_commission);
                    if($commission_id != 0){
                        /*
                        $action = 'WITHDRAWAL_BONUS_FEE';
                        $value_cost = $amount;
                        $percent = $fee_percent;
                        $value = $fee;
                        $data_commission_fee = array(
                            'order_id' => NULL,
                            'user_id' => $user_id,
                            'extend_by' => $commission_id,
                            'action' => $action,
                            'payment' => $payment,
                            'value_cost' => $value_cost,
                            'percent' => $percent,
                            'value' => $value,
                            'message' => 'Thuế thu nhập cá nhân tiền thưởng doanh số',
                            'status' => 0,
                            'created' => $time
                        );
                        $this->M_users_commission->add($data_commission_fee);
                        */

                        $full_name = isset($user['full_name']) ? $user['full_name'] : '';
                        $site_name = $this->_data['site_name'];
                        $receiver_email = $this->_data['email'];
                        $emails = explode(',', $receiver_email);
                        $site_email = get_first_element(array_map('trim', $emails));
                        $sender_email = $site_email;
                        $sender_name = $site_name;

                        $data_commission['id'] = $commission_id;
                        $data_commission['phone'] = isset($user['phone']) ? $user['phone'] : '';
                        $data_commission['email'] = isset($user['email']) ? $user['email'] : '';
                        $data_commission['full_name'] = $full_name;

                        $partial = array();
                        $partial['data'] = $data_commission;
                        $data_sendmail = array(
                            'sender_email' => $site_email,
                            'sender_name' => $sender_name . ' - ' . $site_email,
                            'receiver_email' => $receiver_email, //mail nhan thư
                            // 'receiver_email' => 'lenhan10th@gmail.com', //mail nhan thư
                            'subject' => 'Yêu cầu rút tiền thưởng - ' . $site_name,
                            'message' => $this->load->view('layout/site/partial/html-template-withdrawal-bonus', $partial, true)
                        );
                        modules::run('emails/send_mail', $data_sendmail);
                        $notify_type = 'success';
                        $notify_content = 'Rút tiền thưởng thành công! Vui lòng chờ xác nhận!';
                    } else {
                        $notify_type = 'danger';
                        $notify_content = 'Chưa rút được tiền thưởng! Vui lòng thực hiện lại!';
                    }
                    $this->set_notify($notify_type, $notify_content);
                    redirect(current_url());
                }
            }
        }
        $this->_data['banker'] = modules::run('banker/gets');
        $this->_breadcrumbs[] = array(
            'url' => site_url('rut-tien-thuong'),
            'name' => 'Rút tiền thưởng',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Rút tiền thưởng - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-withdrawal-bonus';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_history() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->_plugins_css[] = array(
            'folder' => 'bootstrap-datepicker/css',
            'name' => 'bootstrap-datepicker',
        );
        $this->_plugins_css[] = array(
            'folder' => 'bootstrap-datepicker/css',
            'name' => 'bootstrap-datepicker3',
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker/js',
            'name' => 'bootstrap-datepicker',
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker/locales',
            'name' => 'bootstrap-datepicker.vi.min',
        );
        $this->set_plugins();

        $this->_modules_script[] = array(
            'folder' => 'users',
            'name' => 'admin-commission-items',
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $this->output->cache(true);
        $_module_slug = 'lich-su-giao-dich';
        $this->_data['module_slug'] = $_module_slug;
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;

        //theo ngay
        if (isset($get['fromday']) && trim($get['fromday']) != '') {
            $args['start_date_start'] = get_start_date($get['fromday']);
        }
        if (isset($get['today']) && trim($get['today']) != '') {
            $args['start_date_end'] = get_end_date($get['today']);
        }

        if (isset($get['action']) && trim($get['action']) != '') {
            $args['action'] = $get['action'];
        }

        $total = $this->M_users_commission->counts($args);
        $perpage = 20;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = 4;
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        if (!empty($get)) {
            $config['base_url'] = base_url($_module_slug);
            $config['suffix'] = '?' . http_build_query($get, '', "&");
            $config['first_url'] = site_url($_module_slug . '?' . http_build_query($get, '', "&"));
            $config['uri_segment'] = $segment;
        } else {
            $config['base_url'] = base_url($_module_slug);
            $config['first_url'] = site_url($_module_slug);
            $config['uri_segment'] = $segment;
        }

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Lịch sử giao dịch',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Lịch sử giao dịch - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_history_export_excel() {
        $this->_initialize();
        modules::run('users/require_logged_in');
        $user_id = $this->_data['userid'];

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $order_by = array(
            'id' => 'DESC',
        );
        $args['order_by'] = $order_by;

        //theo ngay
        if (isset($get['fromday']) && trim($get['fromday']) != '') {
            $args['start_date_start'] = get_start_date($get['fromday']);
        }
        if (isset($get['today']) && trim($get['today']) != '') {
            $args['start_date_end'] = get_end_date($get['today']);
        }

        if (isset($get['action']) && trim($get['action']) != '') {
            $args['action'] = $get['action'];
        }

        $rows = $this->gets($args);
        if (!is_array($rows) && empty($rows)) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify($notify_type, $notify_content);
            redirect(site_url('lich-su-giao-dich'));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'G';
        $letterColumn = range($firstColumn, $lastColumn);
        $hideColumn = 'H';

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
            ->setTitle("Lịch sử giao dịch")
            ->setSubject("Lịch sử giao dịch")
            ->setDescription("Lịch sử giao dịch")
            ->setKeywords("Lịch sử giao dịch")
            ->setCategory("Lịch sử giao dịch");
        $this->excel->getActiveSheet()->setTitle('Lịch sử giao dịch');

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'ID')
            ->setCellValue('B1', "Hoạt động")
            ->setCellValue('C1', "Giá trị")
            ->setCellValue('D1', "Hoa hồng")
            ->setCellValue('E1', "Thời gian")
            ->setCellValue('F1', "Trạng thái")
            ->setCellValue('G1', "Ghi chú");

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        // Add some data
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        //$this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->applyFromArray($styleAlignmentRight);
        $this->excel->getActiveSheet()->getStyle('C1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('D1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('E1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        //$this->excel->getActiveSheet()->getStyle('G1')->getAlignment()->applyFromArray($styleAlignmentCenter);
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
            $status = '';
            if($row['status'] == 1){
                $status = 'Khả dụng';
            }elseif($row['status'] == 0){
                $status = 'Đã yêu cầu';
            }else{
                $status = 'Đã hủy yêu cầu';
            }
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $row['id'])
                ->setCellValue('B' . $i, display_value_array($this->config->item('users_modules_commission'), $row['action']))
                ->setCellValue('C' . $i, ($row['action'] == 'WITHDRAWAL' ? (-1) * $row['value_cost'] : $row['value_cost']))
                ->setCellValue('D' . $i, !in_array($row['action'], array('SUB_BUY', 'SUB_BUY_ROOT')) ? '' : $row['value'])
                ->setCellValue('E' . $i, display_date($row['created']))
                ->setCellValue('F' . $i, $status)
                ->setCellValue('G' . $i, $row['message']);

            // $this->excel->getActiveSheet()->setCellValueExplicit('E' . $i, strval($row['phone']), PHPExcel_Cell_DataType::TYPE_STRING);
            // $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            // $this->excel->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            // $this->excel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
            // $this->excel->getActiveSheet()->getStyle('G' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            // $this->excel->getActiveSheet()->getStyle('H' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            // $this->excel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode($numberFormat);
            $this->excel->getActiveSheet()->getStyle('C' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            $this->excel->getActiveSheet()->getStyle('D' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
            $this->excel->getActiveSheet()->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
            $this->excel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);

            
            // $this->excel->getActiveSheet()->getStyle('D' . $i)->getNumberFormat()->setFormatCode("+0;-0;0");//Thêm +, - hoặc không thêm tuỳ vào giá trị ô
            $this->excel->getActiveSheet()->getStyle('C' . $i)->getNumberFormat()->setFormatCode("[Green][>=0]+#,##0;[Red][<0]-#,##0;#,##0");
            $this->excel->getActiveSheet()->getStyle('D' . $i)->getNumberFormat()->setFormatCode("[Green][>=0]+#,##0;[Red][<0]-#,##0;#,##0");

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

        $filename = 'Lich-su-giao-dich_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

    function site_pay_in_history() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'lich-su-nap-tien';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('PAY_IN');
        $args_success = $args_pending = $args;

        $args_success['status'] = 1;
        $total_success = $this->M_users_commission->get_total($args_success);
        $this->_data['total_success'] = $total_success;

        $args_pending['status'] = 0;
        $total_pending = $this->M_users_commission->get_total($args_pending);
        $this->_data['total_pending'] = $total_pending;

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;
        // echo "<pre>";
        // print_r($rows);
        // echo "</pre>";
        // die();

        // $partial = array();
        // $partial['data'] = null;
        // if (is_array($rows) && !empty($rows)) {
        //     $users_referred_commission_percent = 2;
        //     foreach ($rows as $value) {
        //         $total_users_referred_commission = 0;
        //         $commission = $value['commission'];
        //         $commission_price = $value['commission_price'];
        //         if ($commission > 0 && $commission_price > 0) {
        //             $total_users_referred_commission = $commission_price * $users_referred_commission_percent / $commission;
        //         }

        //         $row = $this->get($value['product_id']); // lấy thông tin chi tiết các sản phẩm có trong giỏ hàng
        //         $row['quantity'] = $value["quantity"];
        //         $row['price'] = $value["price"];
        //         $row['promotion_price'] = $value["promotion_price"];
        //         $row['unit'] = display_value_array($unit, $value["unit_id"]);
        //         $row['order_code'] = $value['order_code'];
        //         $row['commission'] = $commission;
        //         $row['commission_price'] = $total_users_referred_commission;
        //         $row['created'] = $value['created'];
        //         $partial['data'][] = $row;
        //     }
        // }
        // $this->_data['rows'] = $this->load->view('layout/site/partial/commission-on-sales', $partial, true);

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Lịch sử nạp tiền',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Lịch sử nạp tiền - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-pay-in-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_buy() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'lich-su-mua-hang';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('BUY');
        $args_success = $args_pending = $args;

        $args_success['status'] = 1;
        $total_success = $this->M_users_commission->get_total($args_success);
        $this->_data['total_success'] = $total_success;

        $args_pending['status'] = 0;
        $total_pending = $this->M_users_commission->get_total($args_pending);
        $this->_data['total_pending'] = $total_pending;

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Lịch sử mua hàng',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Lịch sử mua hàng - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-buy-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_withdrawal_history() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'lich-su-rut-tien';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('WITHDRAWAL');
        $args_success = $args_pending = $args;

        $args_success['status'] = 1;
        $total_success = $this->M_users_commission->get_total($args_success);
        $this->_data['total_success'] = $total_success;

        $args_pending['status'] = 0;
        $total_pending = $this->M_users_commission->get_total($args_pending);
        $this->_data['total_pending'] = $total_pending;

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Lịch sử rút tiền',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Lịch sử rút tiền - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-withdrawal-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_withdrawal_bonus_history() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'lich-su-rut-tien-thuong';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('WITHDRAWAL_BONUS');
        $args_success = $args_pending = $args;

        $args_success['status'] = 1;
        $total_success = $this->M_users_commission->get_total($args_success);
        $this->_data['total_success'] = $total_success;

        $args_pending['status'] = 0;
        $total_pending = $this->M_users_commission->get_total($args_pending);
        $this->_data['total_pending'] = $total_pending;

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Lịch sử rút tiền thưởng',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Lịch sử rút tiền thưởng - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-withdrawal-bonus-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_revenue_bonus_history() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'lich-su-thuong-doanh-so';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('REVENUE_BONUS');

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $title = 'Lịch sử thưởng doanh số';
        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => $title,
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = $title . ' - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-revenue-bonus-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_commission_buy_package() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'hoa-hong-gioi-thieu-mo-the';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('SUB_BUY_PACKAGE', 'SUB_BUY_PACKAGE_ROOT');
        $args_success = $args_pending = $args;

        $args_success['status'] = 1;
        $total_success = $this->M_users_commission->get_total($args_success);
        $this->_data['total_success'] = $total_success;

        $args_pending['status'] = 0;
        $total_pending = $this->M_users_commission->get_total($args_pending);
        $this->_data['total_pending'] = $total_pending;

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Hoa hồng giới thiệu mở thẻ',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Hoa hồng giới thiệu mở thẻ - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-commission-buy-package-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function site_commission_buy() {
        $this->_initialize();
        modules::run('users/require_logged_in');

        $this->output->cache(true);
        $_module_slug = 'hoa-hong-he-thong-su-dung-dich-vu';
        $user_id = $this->_data['userid'];

        $args = $this->default_args();
        $args['user_id'] = $user_id;
        $args['in_action'] = array('SUB_BUY', 'SUB_BUY_ROOT');
        $args_success = $args_pending = $args;

        $args_success['status'] = 1;
        $total_success = $this->M_users_commission->get_total($args_success);
        $this->_data['total_success'] = $total_success;

        $args_pending['status'] = 0;
        $total_pending = $this->M_users_commission->get_total($args_pending);
        $this->_data['total_pending'] = $total_pending;

        $total = $this->M_users_commission->counts($args);
        $perpage = 5;
        $segment = 2;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['num_links'] = $this->config->item('num_links');
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';

        $config['first_link'] = '&larr;';
        $config['first_tag_open'] = '<li class="prev page">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = '&rarr;';
        $config['last_tag_open'] = '<li class="next page">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="next page">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="prev page">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="active"><a href="">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page">';
        $config['num_tag_close'] = '</li>';

        $config['base_url'] = base_url($_module_slug);
        $config['first_url'] = site_url($_module_slug);
        $config['uri_segment'] = $segment;

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $rows = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;

        $this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Hoa hồng hệ thống sử dụng dịch vụ',
        );
        $this->set_breadcrumbs();

        $this->_data['title_seo'] = 'Hoa hồng hệ thống sử dụng dịch vụ - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/user-commission-buy-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function admin_config() {
        $this->_initialize_admin();
        $this->redirect_admin();

        /*$segment = 5;
        $user_id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $row = modules::run('users/get', $user_id);
        $this->_module_slug = 'users';
        $this->_data['module_slug'] = $this->_module_slug;
        if(!(is_array($row) && !empty($row))){
            redirect(get_admin_url($this->_module_slug));
        }
        $this->_data['row'] = $row;*/

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
            'name' => 'admin-commission-config',
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('token', '', 'trim|required');

            if ($this->form_validation->run($this)) {
                $data = array(
                    'enable_withdrawal' => $this->input->post('enable_withdrawal') ? 1 : 0,
                    'enable_withdrawal_bonus' => $this->input->post('enable_withdrawal_bonus') ? 1 : 0,
                );
                // $data = array();
                /*foreach ($config_fields as $field) {
                    $post_name = str_replace($field_prefix, '', $field);
                    $value = $this->input->post($post_name);
                    if(in_array($post_name, array('per_page', 'related', 'cat_per_page', 'featured'))){
                        $value = filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                    }elseif(in_array($post_name, array('related_status'))){
                        $value = $this->input->post($post_name) ? 1 : 0;
                    }
                    $data[$field] = $value;
                }*/
                // pre($post);
                // pre($data, TRUE);
                // update_batch_config_value($data);
                // var_dump($data);
                // die;

                $bool = $this->M_configs->update_batch($data);
                if($bool){
                    $notify_type = 'success';
                    $notify_content = 'Đã lưu thông tin cấu hình!';
                    $this->set_notify_admin($notify_type, $notify_content);
                    redirect(get_admin_url($this->_module_slug));
                } else {
                    $notify_type = 'danger';
                    $notify_content = 'Có lỗi xảy ra! Vui lòng thực hiện lại';
                    $this->set_notify_admin($notify_type, $notify_content);
                }
            }
        }

        $field_prefix = '';
        $config_fields = array(
            $field_prefix . 'enable_withdrawal',
            $field_prefix . 'enable_withdrawal_bonus',
        );
        $row = $this->M_configs->gets($config_fields);
        $this->_data['row'] = $row;
        // var_dump($row);
        // $enable_withdrawal = get_config_value('enable_withdrawal');
        // $enable_withdrawal = filter_var(get_config_value('enable_withdrawal'), FILTER_VALIDATE_BOOLEAN);
        // var_dump($enable_withdrawal);
        // die;

        $this->_data['title'] = 'Cài đặt cấu hình giao dịch' . ' - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
        $this->_data['main_content'] = 'users/admin/view_page_commission_config';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_export_excel() {
        $this->_initialize_admin();
        $this->redirect_admin();

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

        //theo ngay
        if (isset($get['fromday']) && trim($get['fromday']) != '') {
            $args['start_date_start'] = get_start_date($get['fromday']);
        }
        if (isset($get['today']) && trim($get['today']) != '') {
            $args['start_date_end'] = get_end_date($get['today']);
        }

        if (isset($get['status']) && trim($get['status']) != '') {
            $args['status'] = (int) $get['status'];
        }

        if (isset($get['action']) && trim($get['action']) != '') {
            $args['action'] = $get['action'];
        }

        $rows = $this->gets($args);
        if (!is_array($rows) && empty($rows)) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

        $this->load->library('excel');
        $objPHPExcel = $this->excel;

        $glue = '|';
        $font_name = 'Arial';
        $firstColumn = 'A';
        $lastColumn = 'K';
        $letterColumn = range($firstColumn, $lastColumn);

        $characters = range($firstColumn, $lastColumn);
        $characters_length = count($characters);
        $fields = array(
            "ordinal_number" => "STT",
            "id" => "ID",
            "full_name" => "Người dùng",
            "action" => "Hoạt động",
            "value_cost" => "Giá trị",
            "value" => "Hoa hồng",
            "order_code" => "Mã đơn hàng(nếu có)",
            "order_amount" => "Giá trị đơn hàng(nếu có)",
            "created" => "Thời gian",
            "status" => "Trạng thái",
            "message" => "Ghi chú",
        );

        $fields_length = count($fields);
        $sheet_length = min($characters_length, $fields_length);

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'font' => array(
                'name' => $font_name,
                'bold' => true,
                'color' => array(
                    'rgb' => '333300',
                ),
            ),
        );
        $fillHeader = 'FFFF00';

        $fillHighlightHeader = '92d050';

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array(
                    'rgb' => '3582F4'
                ),
            ),
        );
        
        $objPHPExcel->setActiveSheetIndex(0);
        $objSheet = $objPHPExcel->getActiveSheet();

        $objPHPExcel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle("Hệ thống giao dịch")
            ->setSubject("Hệ thống giao dịch")
            ->setDescription("Hệ thống giao dịch")
            ->setKeywords("Hệ thống giao dịch")
            ->setCategory("Hệ thống giao dịch");
        $objSheet->setTitle('Hệ thống giao dịch');

        $objSheet->setCellValue('A1', 'STT');
        $index = 0;
        foreach ($fields as $field => $header) {
            $objCell = $characters[$index] . '1';
            $objSheet->setCellValue($objCell, $header);
            $objSheet->getStyle($objCell)->getAlignment()->setWrapText(true);
            if(in_array($field, array('created'))){
                $objSheet->getStyle($objCell)->getAlignment()->applyFromArray($styleAlignmentCenter);
            }

            $index++;
        }
        
        //Header
        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB($fillHeader);
        
        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);
        
        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        foreach ($letterColumn as $column) {
            $objSheet->getColumnDimension($column)->setAutoSize(true);
        }

        $users_role = $this->config->item('role');
        $users_action = $this->config->item('users_modules_commission');

        $i = 2;
        $order = 0;
        foreach ($rows as $row) {
            $status = '';
            if($row['status'] == 1){
                $status = 'Khả dụng';
            }elseif($row['status'] == 0){
                $status = 'Đã yêu cầu';
            }else{
                $status = 'Đã hủy yêu cầu';
            }

            $order++;
            $objSheet->setCellValue('A' . $i, $order);
                $index = 0;
                foreach ($fields as $field => $header) {
                    /*$fields = array(
                        "order_code" => "Mã đơn hàng(nếu có)",
                        "order_amount" => "Giá trị đơn hàng(nếu có)",
                        "created" => "Thời gian",
                        "status" => "Trạng thái",
                        "message" => "Nội dung",
                        "note" => "Ghi chú",
                    );

                            ->setCellValue('F' . $i, display_date($row['created']))
                            ->setCellValue('G' . $i, $status)
                            ->setCellValue('H' . $i, $row['message']);*/



                    $action = $row['action'];
                    $objCell = $characters[$index] . $i;
                    if(in_array($field, array('ordinal_number'))){
                        $objSheet->setCellValue($objCell, $order);
                    }elseif(in_array($field, array('full_name'))){
                        $content = $row[$field];
                        $content .= ' (' . $row['username'] . ') [' . display_value_array($users_role, $row['role']) . ']';
                        // if(trim($row['username']) != ''){
                        //     // $content .= "\n" . $row['lead_full_name_phone'];
                        //     $content .= ' (' . $row['username'] . ') [' . display_value_array($users_role, $row['role']) . ']';
                        // }
                        $objSheet->setCellValue($objCell, $content);
                    }elseif(in_array($field, array('action'))){
                        $objSheet->setCellValue($objCell, display_value_array($users_action, $row[$field]));
                    }elseif(in_array($field, array('value_cost'))){
                        $content = ($row['action'] == 'WITHDRAWAL') ? (-1) * $row['value_cost'] : $row['value_cost'];
                        $objSheet->setCellValue($objCell, $content);
                        // $objSheet->getStyle($objCell)->getNumberFormat()->setFormatCode($numberFormat);
                        $objSheet->getStyle($objCell)->getNumberFormat()->setFormatCode("[Green][>=0]+#,##0;[Red][<0]-#,##0;#,##0");
                        $objSheet->getStyle($objCell)->getAlignment()->applyFromArray($styleAlignmentRight);
                    }elseif(in_array($field, array('value'))){
                        $content = !in_array($row['action'], array('SUB_BUY', 'SUB_BUY_ROOT')) ? '' : $row['value'];
                        $objSheet->setCellValue($objCell, $content);
                        // $objSheet->getStyle($objCell)->getNumberFormat()->setFormatCode($numberFormat);
                        $objSheet->getStyle($objCell)->getNumberFormat()->setFormatCode("[Green][>=0]+#,##0;[Red][<0]-#,##0;#,##0");
                        $objSheet->getStyle($objCell)->getAlignment()->applyFromArray($styleAlignmentRight);
                    }elseif(in_array($field, array('order_amount'))){
                        $content = in_array($action, array('SUB_BUY', 'SUB_BUY_ROOT')) ? $row['value_real'] : (in_array($action, array('BUY', 'SELL')) ? $row['value_accumulated'] : '');
                        $objSheet->setCellValue($objCell, $content);
                        $objSheet->getStyle($objCell)->getNumberFormat()->setFormatCode($numberFormat);
                    }elseif(in_array($field, array('created'))){
                        $objSheet->setCellValue($objCell, date('Y-m-d H:i:s', $row[$field]));
                        $objSheet->getStyle($objCell)->getNumberFormat()->setFormatCode('yyyy-mm-dd hh:mm:ss');
                        $objSheet->getStyle($objCell)->getAlignment()->applyFromArray($styleAlignmentCenter);
                    }elseif(in_array($field, array('status'))){
                        $objSheet->setCellValue($objCell, $status);
                    }else{
                        $objSheet->setCellValue($objCell, $row[$field]);
                    }

                    $index++;
                }
                // $objSheet->getStyle('D' . $i)->getNumberFormat()->setFormatCode('dd-mm-yyyy');
                // $objSheet->getStyle('C' . $i)->getAlignment()->applyFromArray($styleAlignmentRight);
                // $objSheet->getStyle('E' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
                // $objSheet->getStyle('F' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
                // $objSheet->getStyle('N' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
                // $objSheet->getStyle('B' . $i)->getAlignment()->setWrapText(true);
                // $objSheet->getStyle('H' . $i)->getAlignment()->setWrapText(true);

                // $objSheet->setCellValueExplicit('B' . $i, strval($row['import_and_export_business_code']), PHPExcel_Cell_DataType::TYPE_STRING);
                // $objSheet->getStyle('A' . $i)->getNumberFormat()->setFormatCode('dd-mm-yyyy');
                // $objSheet->getStyle('M' . $i)->getNumberFormat()->setFormatCode($numberFormat);

            $i++;
        }

        $objSheet->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13,
                    'name' => $font_name,
                ),
                'alignment' => array(
                    'vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP
                )
            )
        );

        $filename = 'He-thong-giao-dich_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
    }

    function admin_index() {
        $this->_initialize_admin();
        $this->redirect_admin();

        //echo get_balance_user(109); die;

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
            'name' => 'admin-commission-items',
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

        if (isset($get['status']) && trim($get['status']) != '') {
            $args['status'] = (int) $get['status'];
        }

        if (isset($get['action']) && trim($get['action']) != '') {
            $args['action'] = $get['action'];
        }

        //theo ngay
        if (isset($get['fromday']) && trim($get['fromday']) != '') {
            $args['start_date_start'] = get_start_date($get['fromday']);
        }
        if (isset($get['today']) && trim($get['today']) != '') {
            $args['start_date_end'] = get_end_date($get['today']);
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

        $this->_data['rows'] = $this->M_users_commission->gets($args, $perpage, $offset);
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Hệ thống giao dịch - ' . $this->_data['title'];
        $this->_data['main_content'] = 'users/admin/view_page_commission_index';
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
                        // $this->load->model('users/m_users_notification', 'M_users_notification');
                        // $action = 'USER_PAY_IN';
                        // $data_notification = array(
                        //     'actor_id' => $verify_by,
                        //     'notifier_id' => $row['user_id'],
                        //     'action' => $action,
                        //     'object_id' => $row['id'],
                        //     'title' => 'Nạp tiền vào tài khoản',
                        //     'description' => "Nạp tiền thành công, tài khoản của bạn được +" . $value . "đ vào ví",
                        //     'message' => "Hệ thống xác nhận bạn đã nạp " . $value . " vào ví tài khoản",
                        //     'history' => NULL,
                        //     'status' => -1,
                        //     'created' => $time
                        // );
                        // $notification_id = $this->M_users_notification->add($data_notification);

                        //push notification
                        // $this->load->model('users/m_users_fcm_register', 'M_users_fcm_register');
                        // $args_fcm_register = array(
                        //     'user_id' => $row['user_id'],
                        //     'order_by' => array(
                        //         'id' => 'DESC'
                        //     )
                        // );
                        // $fcm_register = $this->M_users_fcm_register->get_by($args_fcm_register);
                        // if(isset($fcm_register['token'])){
                        //     $registration_ids = array(
                        //         $fcm_register['token']
                        //     );
                        //     $title = "Thông báo nạp tiền vào tài khoản";
                        //     $body = "Nạp tiền thành công, tài khoản của bạn được +" . $value . "đ";
                        //     $notification_options = array(
                        //         'title' => $title,
                        //         'body' => $body,
                        //     );
                        //     $notification_data = array(
                        //         "title" => $title,
                        //         "message" => $body,
                        //         "image" => "https://ledu.vn/media/images/slide-le-du1.png",
                        //         "action" => "BUY",
                        //         "action_destination" => $notification_id
                        //     );
                        //     $push_notification = push_notification($registration_ids, $notification_options, $notification_data);
                        // }
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
        $this->_data['users'] = modules::run('users/gets', array('role' => 'AGENCY'));

        $this->_data['title'] = 'Nạp tiền vào tài khoản - ' . $this->_data['title'];
        $this->_data['main_content'] = 'users/admin/view_page_commission_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_main() {
        $this->_initialize_admin();
        $this->redirect_admin();
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'delete') {
                $this->_message_success = 'Đã xóa các giao dịch được chọn!';
                $this->_message_warning = 'Bạn chưa chọn giao dịch nào!';
                $ids = $this->input->post('idcheck');

                if (is_array($ids) && !empty($ids)) {
                    foreach ($ids as $id) {
                        $row = $this->get(array('id' => $id));
                        if (!empty($row) && $this->M_users_commission->delete(array('id' => $id))) {
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

        $this->_message_success = 'Đã xóa giao dịch!';
        $this->_message_warning = 'Giao dịch này không tồn tại!';
        $id = $this->input->get('id');
        $row = $this->get(array(
            'id' => $id
        ));
        if(is_array($row) && !empty($row)){
            if ($this->M_users_commission->delete(array('id' => $id))) {
            	if(in_array($row['action'], array('BUY'))){
                    $order_id = $row['order_id'];
                    $user_id = $row['user_id'];
                    $args_package = array(
                        'order_id' => $order_id,
                        'in_action' => array('SELL', 'BUY', 'BUY_SYSTEM'),
                    );
                    $this->M_users_commission->delete($args_package);
                    $args_package = array(
                        'order_id' => $order_id,
                        'in_action' => array('SUB_BUY_ROOT', 'SUB_BUY', 'SYSTEM'),
                        'extend_by' => $user_id
                    );
                    $this->M_users_commission->delete($args_package);
                    $this->M_shops_orders->delete($order_id);
                    modules::run('shops/order_details/admin_delete', $order_id); // xóa chi tiết đơn hàng
                }elseif(in_array($row['action'], array('WITHDRAWAL'))){
                    $args_package = array(
                        'in_action' => array('WITHDRAWAL_FEE'),
                        'extend_by' => $id
                    );
                    $this->M_users_commission->delete($args_package);
                }elseif(in_array($row['action'], array('WITHDRAWAL_BONUS'))){
                    $args_package = array(
                        'in_action' => array('WITHDRAWAL_BONUS_FEE'),
                        'extend_by' => $id
                    );
                    $this->M_users_commission->delete($args_package);
                }
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
/* End of file Users_commission.php */
/* Location: ./application/modules/users/controllers/Users_commission.php */
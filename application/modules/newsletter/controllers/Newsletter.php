<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Newsletter extends Layout {

    private $_module_slug = 'newsletter';

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('m_newsletter', 'M_newsletter');
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_data['breadcrumbs_module_name'] = 'Nhận Thông Báo';
    }

    function counts($args) {
        $this->load->model('m_newsletter', 'M_newsletter');
        return $this->M_newsletter->counts($args);
    }

    function get($id) {
        $this->load->model('m_newsletter', 'M_newsletter');
        return $this->M_newsletter->get($id);
    }

    function default_args() {
        $order_by = array(
            'add_time' => 'DESC',
            'edit_time' => 'DESC'
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function admin_index() {
		$this->_initialize_admin();
        $this->redirect_admin();

        $this->_plugins_css_admin[] = array(
            'folder' => 'bootstrap3-dialog/css',
            'name' => 'bootstrap-dialog'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'bootstrap3-dialog/js',
            'name' => 'bootstrap-dialog'
        );
        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'newsletter',
            'name' => 'admin-items'
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        if (isset($get['q']) && trim($get['q']) != '') {
            $args['email_address'] = $get['q'];
        }

        $total = $this->counts($args);
        $perpage = isset($get['per_page']) ? $get['per_page'] : $this->config->item('per_page');
        $segment = 3;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<ul class="pagination no-margin pull-right">';
        $config['full_tag_close'] = '</ul><!--pagination-->';

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

        $this->_data['rows'] = $this->M_newsletter->gets($args, $perpage, $offset);
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Danh sách email nhận thông báo - ' . $this->_data['title'];
        $this->_data['main_content'] = 'newsletter/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_add() {
        $data = array(
            'email_address' => $this->input->post('email_address'),
            'status' => 1,
            'add_time' => time(),
            'edit_time' => 0
        );

        return $this->M_newsletter->add($data);
    }

    function admin_update($id) {
        $data = array(
            'email_address' => $this->input->post('email_address'),
            'status' => 1,
            'edit_time' => time()
        );

        return $this->M_newsletter->update($id, $data);
    }

    function admin_delete() {
		$this->_initialize_admin();
        $this->load->module('users');

        $this->_message_success = 'Đã xóa thông tin!';
        $this->_message_warning = 'Thông tin này không tồn tại!';
        if ($this->users->validate_admin_logged_in() == TRUE) {
            if ($this->input->post('ajax') == '1') {
                $id = $this->input->post('id');
                if ($id != 0) {
                    $current_photo = $this->get($id);
                    if ($this->M_newsletter->delete($id)) {
                        $this->_status = "success";
                        $this->_message = $this->_message_success;
                    } else {
                        $this->_status = "danger";
                        $this->_message = $this->_message_danger;
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
                    $current_photo = $this->get($id);
                    if ($this->M_newsletter->delete($id)) {
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
                redirect(get_admin_url($this->_module_slug));
            }
        }
    }

    function admin_main() {
		$this->_initialize_admin();
        $this->redirect_admin();
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'update') {
                $this->_message_success = 'Đã cập nhật thông tin!';
                $this->_message_warning = 'Không có thông tin nào để cập nhật!';
                $ids = $this->input->post('ids');
                $orders = $this->input->post('order');
                $count = count($orders);
                if (!empty($ids) && !empty($orders)) {
                    for ($i = 0; $i < $count; $i++) {
                        $data = array(
                            'order' => $orders[$i]
                        );
                        $id = $ids[$i];
                        if ($this->M_newsletter->update($id, $data)) {
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
            }
        } else {
            redirect(get_admin_url($this->_module_slug));
        }
    }

    function index() {
        $this->_message_success = 'Bạn đã đăng ký nhận bản tin thành công!';
        $this->_message_warning = 'Bạn đã đăng ký nhận bản tin!';
        $this->form_validation->set_rules('signup_email', 'Email', 'trim|required|valid_email|xss_clean');

        if ($this->form_validation->run($this)) {
            if ($this->input->post('ajax') == '1') {
                $email_address = $this->input->post('signup_email');
                $isset_row = $this->get_by_email_address($email_address);
                if ($isset_row != null) {
                    $this->_status = "warning";
                    $this->_message = $this->_message_warning;
                } else {
                    $data = array(
                        'email_address' => $email_address,
                        'status' => 1,
                        'add_time' => time()
                    );

                    if ($this->M_newsletter->add($data)) {
                        $this->_status = "success";
                        $this->_message = $this->_message_success;
                    } else {
                        $this->_status = "danger";
                        $this->_message = $this->_message_danger;
                    }
                }
                $this->set_json_encode();
                $this->load->view('layout/json_data', $this->_data);
            } else {
                redirect(base_url());
            }
        } else {
            redirect(base_url());
        }
    }

    function get_by_email_address($email_address) {
        return $this->M_newsletter->get_by_email_address($email_address);
    }
    
    function gets() {
        $args = $this->default_args();
        $this->load->model('m_newsletter', 'M_newsletter');
        return $this->M_newsletter->gets($args);
    }

}

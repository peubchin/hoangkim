<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Email_logs extends Layout {

    private $_module_slug = 'emails/logs';

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('m_email_logs', 'M_email_logs');
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_data['breadcrumbs_module_name'] = 'Hệ thống gửi email';
    }

    function auto_send_email($number = 20) {
        $this->load->model('m_email_logs', 'M_email_logs');
        $args = $this->default_args();
        $args['status'] = 0;
        $list_email = $this->M_email_logs->gets($args, $number, 0);
        foreach ($list_email as $value) {
            $data_sendmail = array(
                'sender_name' => $value['to'],
                'receiver_email' => $value['email'],
                'subject' => $value['subject'],
                'message' => $value['content']
            );
            $is_send_mail = modules::run('emails/send_mail', $data_sendmail); // gửi mail
            if ($is_send_mail) {
                $id = $value['id'];
                $data = array(
                    'status' => 1,
                    'send_time' => time()
                );
                $this->admin_update($id, $data);
            }
        }
    }

    function counts($args) {
        return $this->M_email_logs->counts($args);
    }

    function get($id) {
        return $this->M_email_logs->get($id);
    }

    function default_args() {
        $order_by = array(
            'add_time' => 'DESC',
            'send_time' => 'DESC'
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
            'folder' => 'images',
            'name' => 'admin-items'
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        if (isset($get['status']) && trim($get['status']) != '') {
            $args['status'] = $get['status'];
        }
        
        if (isset($get['q']) && trim($get['q']) != '') {
            $args['to'] = $get['q'];
            $args['email'] = $get['q'];
            $args['subject'] = $get['q'];
            $args['content'] = $get['q'];
        }

        $total = $this->counts($args);
        $perpage = $this->input->get('per_page') ? $this->input->get('per_page') : $this->config->item('per_page'); /* Số bảng ghi muốn hiển thị trên một trang */
        $segment = 4;

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

        $this->_data['rows'] = $this->M_email_logs->gets($args, $perpage, $offset);
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Danh sách email logs - ' . $this->_data['title'];
        $this->_data['main_content'] = 'emails/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_add($data) {
        return $this->M_email_logs->add($data);
    }

    function admin_update($id, $data) {
        return $this->M_email_logs->update($id, $data);
    }

}

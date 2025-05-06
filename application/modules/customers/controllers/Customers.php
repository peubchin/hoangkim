<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Customers extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('customers/m_customers', 'M_customers');
        $this->_data['breadcrumbs_module_name'] = 'Khách hàng';
    }

    function default_args() {
        $order_by = array(
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
        $this->load->model('customers/m_customers', 'M_customers');
        return $this->M_customers->gets($args);
    }

    function counts($options = array()) {
        $default_args = $this->default_args();

        if (is_array($options) && !empty($options)) {
            $args = array_merge($default_args, $options);
        } else {
            $args = $default_args;
        }
        $this->load->model('customers/m_customers', 'M_customers');
        return $this->M_customers->counts($args);
    }

    function get($id) {
        $this->load->model('customers/m_customers', 'M_customers');
        return $this->M_customers->get($id);
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
            'folder' => 'customer',
            'name' => 'admin-index'
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        if (isset($get['q']) && trim($get['q']) != '') {
            $args[$get['q']] = $get['q'];
        }
        $total = $this->counts($args);
        $perpage = isset($get['per_page']) ? $get['per_page'] : $this->config->item('per_page');
        $segment = 3;

        $this->load->library('pagination'); # Tải bộ thư viện Pagination Class của CodeIgniter
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
            $config['base_url'] = get_admin_url('customers');
            $config['suffix'] = '?' . http_build_query($get, '', "&");
            $config['first_url'] = get_admin_url('customers' . '?' . http_build_query($get, '', "&"));
            $config['uri_segment'] = $segment;
        } else {
            $config['base_url'] = get_admin_url('customers');
            $config['uri_segment'] = $segment;
        }

        $this->pagination->initialize($config);  # Khởi tạo phân trang

        $pagination = $this->pagination->create_links(); # Tạo link phân trang
        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment); # Lấy offset
        # Đẩy dữ liệu ra view
        $this->_data['rows'] = $this->M_customers->gets($args, $perpage, $offset);
        $this->_data['pagination'] = $pagination;

        $this->_data['breadcrumbs_module_func'] = 'Danh sách khách hàng';
        $this->_data['title'] = 'Danh sách khách hàng';
        $this->_data['main_content'] = 'customers/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function site_add($data) {
        return $this->M_customers->add($data);
    }

    function admin_delete($id = 0) {
        $this->load->model('customers/m_customers', 'M_customers');
        return $this->M_customers->delete($id);
    }

}

/* End of file customers.php */
/* Location: ./application/modules/customers/controllers/customers.php */
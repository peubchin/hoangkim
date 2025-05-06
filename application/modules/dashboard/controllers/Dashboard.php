<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Dashboard extends Layout {

    function __construct() {
        parent::__construct();
        $this->_data['breadcrumbs_module_name'] = 'Bảng điều khiển';
    }

    function index() {
		$this->_initialize_admin();
        $this->_data['num_contact_new'] = modules::run('contact/num_rows_new');
        $this->_data['num_contact_all'] = modules::run('contact/counts', array());

        $this->_data['num_pages_all'] = modules::run('pages/counts_all', array());
        $this->_data['num_posts_all'] = modules::run('posts/counts', array());

        $this->_data['title'] = 'Bảng điều khiển - ' . $this->_data['title'];
        $this->_data['main_content'] = 'dashboard/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

}
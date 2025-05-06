<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Search extends Layout {

    function __construct() {
        parent::__construct();
        $this->_data['breadcrumbs_module_name'] = 'Tìm kiếm';
    }

    function index() {
        // $get = $this->input->get();
        // $q = isset($get['q']) ? $get['q'] : '';

        // if ($q == '') {
        //     redirect(base_url());
        // } else {
        //     $this->_data['get'] = $get;
        // }
        echo modules::run('shops/rows/site_search');
    }

}

<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Order_status extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('m_shops_order_status', 'M_shops_order_status');
    }

    function counts($args) {
        $this->load->model('m_shops_order_status', 'M_shops_order_status');
        return $this->M_shops_order_status->counts($args);
    }

    function get($id) {
        $this->load->model('m_shops_order_status', 'M_shops_order_status');
        return $this->M_shops_order_status->get($id);
    }

    function default_args() {
        $order_by = array(
            'order' => 'ASC',
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function gets() {
        $args = $this->default_args();
        $this->load->model('m_shops_order_status', 'M_shops_order_status');
        return $this->M_shops_order_status->gets($args);
    }

}

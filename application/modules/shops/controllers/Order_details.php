<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Order_details extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('m_shops_order_details', 'M_shops_order_details');
        $this->_data['breadcrumbs_module_name'] = 'Sản phẩm';
    }

    function site_add($data = NULL) {
        if (empty($data)) {
            return 0;
        }
        $this->load->model('m_shops_order_details', 'M_shops_order_details');
        return $this->M_shops_order_details->add($data);
    }

    function admin_delete($order_id = 0) {
        $this->load->model('m_shops_order_details', 'M_shops_order_details');
        return $this->M_shops_order_details->delete($order_id);
    }

    function get_data_in_order_id($order_id) {
        return $this->M_shops_order_details->get_data_in_order_id($order_id);
    }
    
    function get_best_seller($top = 4) {
        return $this->M_shops_order_details->get_best_seller($top);
    }

    function get_total_quantity() {
        $total = $this->M_shops_order_details->get_total_quantity();
        return isset($total['quantity']) ? (int) $total['quantity'] : 0;
    }
    
    function get_total_quantity_in_product_id($product_id = 0) {
        $total = $this->M_shops_order_details->get_total_quantity_in_product_id($product_id);
        return isset($total['quantity']) ? (int) $total['quantity'] : 0;
    }
    
    function validate_product_id($product_id = 0) {
        $this->load->model('m_shops_order_details', 'M_shops_order_details');
        return $this->M_shops_order_details->validate_product_id($product_id);
    }

}

/* End of file order_details.php */
/* Location: ./application/modules/shops/controllers/order_details.php */
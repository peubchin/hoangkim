<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Other extends Layout {
	
	public $_path = '';

    function __construct() {
        parent::__construct();
        $this->load->model('shops/m_shops_other', 'M_shops_other');
        $this->_data['breadcrumbs_module_name'] = 'Sản phẩm';
		$this->_path = get_module_path('shops');
    }
	
	function default_args() {
		$order_by = array(
			'order' => 'ASC'
		);
		$args = array();
		$args['order_by'] = $order_by;

		return $args;
	}
	
	function ajax_update() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $message = array();
        $message['status'] = 'warning';
        $message['content'] = null;
        $message['message'] = 'Kiểm tra thông tin nhập';

        $post = $this->input->post();
        if (!empty($post)) {
            $id = $this->input->post('id');
            $order = $this->input->post('order');
            $alt = $this->input->post('alt');
			$data = array(
				'alt' => $alt,
				'order' => $order,
				'edittime' => time(),
			);
			$args = array('id' => $id);

			if ($this->update($args, $data)) {				
				$message['status'] = 'success';
				$message['content'] = null;
				$message['message'] = 'Đã cập nhật dữ liệu thành công!';
			} else {
				$message['status'] = 'danger';
				$message['content'] = null;
				$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';
			}
        }
        echo json_encode($message);
        exit();
    }
	
	function ajax_delete() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $message = array();
        $message['status'] = 'warning';
        $message['content'] = null;
        $message['message'] = 'Kiểm tra thông tin nhập';

        $post = $this->input->post();
        if (!empty($post)) {
            $id = $this->input->post('id');
			$args = array('id' => $id);
			$row = $this->get($args);

			if(is_array($row) && !empty($row)){
				if ($this->delete($args)) {
					@unlink(FCPATH . $this->_path . $row['image']);
					$message['status'] = 'success';
					$message['content'] = null;
					$message['message'] = 'Đã xóa dữ liệu thành công!';
				} else {
					$message['status'] = 'danger';
					$message['content'] = null;
					$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';
				}
			}else{
				$message['status'] = 'danger';
				$message['content'] = null;
				$message['message'] = 'Dữ liệu không tồn tại!';
			}
        }
        echo json_encode($message);
        exit();
    }
	
	function validate_exist($args) {
        $data = $this->get($args);

        if (is_array($data) && !empty($data)) {
            return TRUE;
        }

        return FALSE;
    }

    function get($args) {
        return $this->M_shops_other->get($args);
    }

    function gets($options = array()) {
		$default_args = $this->default_args();
		
		if(is_array($options) && !empty($options)){
			$args = array_merge($default_args, $options);
		}else{
			$args = $default_args;
		}
		
        return $this->M_shops_other->gets($args);
    }

    function add($data = NULL) {
        if (empty($data)) {
            return 0;
        }
        return $this->M_shops_other->add($data);
    }

    function update($args, $data) {
        return $this->M_shops_other->update($args, $data);
    }

    function delete($args) {
        return $this->M_shops_other->delete($args);
    }
}

/* End of file other.php */
/* Location: ./application/modules/shops/controllers/other.php */
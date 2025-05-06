<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Tags_relationship extends Layout {

    function __construct() {
        parent::__construct();
        $this->load->model('tags/m_tags_relationship', 'M_tags_relationship');
        $this->_data['breadcrumbs_module_name'] = 'Tags';
    }

    function validate_exist_tag_id($tag_id = 0) {
        $data = $this->M_tags_relationship->validate_exist_tag_id($tag_id);

        if (is_array($data) && !empty($data)) {
            return TRUE;
        }

        return FALSE;
    }

    function validate_exist($object_id = 0, $tag_id = 0, $module = '') {
        $data = $this->M_tags_relationship->validate_exist($object_id, $tag_id, $module);

        if (is_array($data) && !empty($data)) {
            return TRUE;
        }

        return FALSE;
    }

    function get_data($object_id = 0, $tag_id = 0, $module = '') {
        return $this->M_tags_relationship->get_data($object_id, $tag_id, $module);
    }

    function admin_update($object_id = 0, $tag_id = 0, $data = array(), $module = '') {
        $this->load->model('m_tags_relationship', 'M_tags_relationship');
        return $this->M_tags_relationship->update($object_id, $tag_id, $data, $module);
    }

    function get_price_ajax() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $message = array();
        $message['status'] = 'warning';
        $message['content'] = null;
        $message['message'] = 'Kiểm tra thông tin nhập';

        $post = $this->input->post();
        if (!empty($post)) {
            $err = FALSE;
            $object_id = $this->input->post('object_id');
            $tag_id = $this->input->post('tag_id');

            $data = $this->M_tags_relationship->get_price($object_id, $tag_id);

            if (empty($data)) {
                $err = TRUE;
            }

            if ($err === FALSE) {
                if (isset($data['price'])) {
                    $data['price'] = formatRice($data['price']);
                }
                $message['status'] = 'success';
                $message['content'] = $data;
                $message['message'] = 'Đã lấy dữ liệu thành công!';
            } else {
                $message['status'] = 'danger';
                $message['content'] = null;
                $message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';
            }
        }
        echo json_encode($message);
        exit();
    }

    function admin_add($data = NULL) {
        if (empty($data)) {
            return FALSE;
        }
        $this->load->model('m_tags_relationship', 'M_tags_relationship');
        return $this->M_tags_relationship->add($data);
    }

    function admin_delete_by_object_id($object_id = 0, $module = '') {
        $this->load->model('m_tags_relationship', 'M_tags_relationship');
        return $this->M_tags_relationship->admin_delete_by_object_id($object_id, $module);
    }

    function get_data_by_object_id($object_id = 0, $module = '') {
        $this->load->model('m_tags_relationship', 'M_tags_relationship');
        return $this->M_tags_relationship->get_data_by_object_id($object_id, $module);
    }

}

/* End of file tags_relationship.php */
/* Location: ./application/modules/tags/controllers/tags_relationship.php */
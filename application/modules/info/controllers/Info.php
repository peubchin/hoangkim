<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Info extends Layout {

    private $_module_slug = 'info';

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('info/m_info', 'M_info');
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_data['breadcrumbs_module_name'] = 'Thông Tin';
    }

    function default_args() {
        $order_by = array(
            'post_type' => 'ASC',
            'position' => 'ASC',
            'order' => 'ASC'
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function counts($args) {
        return $this->M_info->counts($args);
    }

    function get($id) {
        return $this->M_info->get($id);
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
            'folder' => 'info',
            'name' => 'admin-items'
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        if (isset($get['post_type']) && trim($get['post_type']) != '') {
            $args['post_type'] = $get['post_type'];
        }
        if (isset($get['position']) && trim($get['position']) != '') {
            $args['position'] = $get['position'];
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

        $this->_data['rows'] = $this->M_info->gets($args, $perpage, $offset);
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Danh sách thông tin - ' . $this->_data['title'];
        $this->_data['main_content'] = 'info/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_add() {
        $post_type = $this->input->post('module');
        $position = $this->input->post('position');
        $data = array(
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content'),
            'link' => $this->input->post('link'),
            'order' => $this->get_max_order($post_type, $position) + 1,
            'post_type' => $post_type,
            'position' => $position,
            'add_time' => time(),
            'edit_time' => 0
        );

        return $this->M_info->add($data);
    }

    function admin_update($id) {
        $post_type = $this->input->post('module');
        $position = $this->input->post('position');
        $data_current = $this->get($id);
        $post_type_current = $data_current['post_type'];
        $position_current = $data_current['position'];

        $data = array(
            'title' => $this->input->post('title'),
            'content' => $this->input->post('content'),
            'link' => $this->input->post('link'),
            'post_type' => $post_type,
            'position' => $position,
            'edit_time' => time()
        );

        if ($post_type != $post_type_current || $position != $position_current) {
            $data['order'] = $this->get_max_order($post_type, $position) + 1; //gia tri sap xep lon nhat cua menu mới
        }

        if ($this->M_info->update($id, $data)) {
            if ($post_type != $post_type_current || $position != $position_current) {
                /* sap xep lại vị trí hiện tại */
                $args = $this->default_args();
                $args['post_type'] = $post_type_current;
                $args['position'] = $position_current;
                $rows_current = $this->M_info->gets($args);

                if (!empty($rows_current)) {
                    $i = 0;
                    foreach ($rows_current as $value) {
                        $i++;
                        $data_order = array(
                            'order' => $i
                        );
                        $this->M_info->update($value['id'], $data_order);
                    }
                }
            }
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function admin_delete() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->_message_success = 'Đã xóa thông tin!';
        $this->_message_danger = 'Thông tin chưa được xóa. Vui lòng kiểm tra lại!';
        $this->_message_warning = 'Thông tin này không tồn tại!';
        $id = $this->input->get('id');
		if ($id != 0) {
			$current_photo = $this->get($id);
			if ($this->M_info->delete($id)) {
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
                        if ($this->M_info->update($id, $data)) {
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

    function admin_content() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi'
        );

        $this->_plugins_css_admin[] = array(
            'folder' => 'bootstrap-fileinput/css',
            'name' => 'fileinput'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'bootstrap-fileinput/js',
            'name' => 'fileinput.min'
        );

        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'info',
            'name' => 'admin-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->load->helper('language');
            $this->lang->load('form_validation', 'vietnamese');

            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('title', 'Nhập tiêu đề', 'trim|required');

            if ($this->form_validation->run($this)) {
                if ($this->input->post('id')) {//update
                    $err = FALSE;
                    $id = $this->input->post('id');
                    if (!$this->admin_update($id)) {
                        $err = TRUE;
                    }

                    if ($err === FALSE) {
                        $notify_type = 'success';
                        $notify_content = 'Cập nhật thông tin thành công!';
                        $this->set_notify_admin($notify_type, $notify_content);

                        redirect(get_admin_url($this->_module_slug));
                    } else {
                        $notify_type = 'danger';
                        $notify_content = 'Có lỗi xảy ra!';
                        $this->set_notify_admin($notify_type, $notify_content);
                    }
                } else {//add
                    $err = FALSE;
                    $insert_id = $this->admin_add();
                    if ($insert_id == 0) {
                        $err = TRUE;
                    }

                    if ($err === FALSE) {
                        $notify_type = 'success';
                        $notify_content = 'Thông tin đã được thêm!';
                        $this->set_notify_admin($notify_type, $notify_content);

                        redirect(get_admin_url($this->_module_slug));
                    } else {
                        $notify_type = 'danger';
                        $notify_content = 'Có lỗi xảy ra!';
                        $this->set_notify_admin($notify_type, $notify_content);
                    }
                }
            }
        }

        $title = 'Thêm thông tin - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];

        $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
        if ($id != 0) {
            $row = $this->get($id);

            $this->_data['row'] = $row;
            $title = 'Cập nhật thông tin - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
        }

        //$this->session->set_userdata('KCFINDER', array('disabled' => false, 'uploadURL' => '/uploads'));
        //$_SESSION['KCFINDER'] = array();
        //$_SESSION['KCFINDER']['disabled'] = false;
        //$_SESSION['KCFINDER']['uploadURL'] = '/uploads';

        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));

        $this->_data['title'] = $title;
        $this->_data['main_content'] = 'info/admin/view_page_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function get_max_order($post_type = 'banner', $position = 'none') {
        $args = $this->default_args();
        $order_by = array(
            'order' => 'DESC'
        );
        $args['order_by'] = $order_by;
        $args['post_type'] = $post_type;
        $args['position'] = $position;
        $perpage = 1;
        $offset = 0;
        $rows = $this->M_info->gets($args, $perpage, $offset);
        $max_order = isset($rows[0]['order']) ? $rows[0]['order'] : 0;

        return (int) $max_order;
    }

    function get_info_position($post_type = 'all', $position = 'none', $single = false) {
        $this->load->model('info/m_info', 'M_info');
        $row = $this->M_info->get_info_position($post_type, $position, $single);

        return $row;
    }
}

/* End of file info.php */
/* Location: ./application/modules/info/controllers/info.php */
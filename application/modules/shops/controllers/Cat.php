<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Cat extends Layout {

    private $_module_slug = 'shops/cat';
    private $_path = '';

    function __construct() {
        parent::__construct();
        $this->_data['breadcrumbs_module_name'] = 'Sản phẩm';
        $this->_data['breadcrumbs_module_func'] = 'Danh mục sản phẩm';
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_path = get_module_path('shops_cat');
    }

    function admin_ajax_change_inhome() {
        $post = $this->input->post();
        if (!empty($post)) {
            $value = $this->input->post('value');
            $id = $this->input->post('id');
            $data = array(
                'inhome' => $value
            );
            if ($this->M_shops_cat->update($id, $data)) {
                if ($value == 1) {
                    $notify_type = 'success';
                    $notify_content = 'Đã bật hiển thị danh mục sản phẩm ngoài trang chủ!';
                } else {
                    $notify_type = 'warning';
                    $notify_content = 'Đã tắt hiển thị danh mục sản phẩm ngoài trang chủ!';
                }
            } else {
                $notify_type = 'danger';
                $notify_content = 'Dữ liệu chưa lưu!';
            }
            $this->set_notify_admin($notify_type, $notify_content);
            $this->load->view('layout/notify-ajax', NULL);
        } else {
            redirect(base_url());
        }
    }

    function default_args() {
        $order_by = array(
            'parent' => 'ASC',
            'order' => 'ASC',
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function counts($args) {
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        return $this->M_shops_cat->counts($args);
    }

    function get($id) {
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        return $this->M_shops_cat->get($id);
    }

    function gets($basic = false) {
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        $args = $this->default_args();
        $rows = $this->M_shops_cat->gets($args);
        if($basic){
            $data = $rows;
        }else{
            $data = array(
                'data_list' => $this->get_data_list($rows),
                'data_input' => $this->get_data_input($rows)
            );
        }

        return $data;
    }

    function get_in_alias($alias, $lang = '') {
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        return $this->M_shops_cat->get_in_alias($alias, $lang);
    }

    function gets_inhome($limit = 4) {
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        $args = $this->default_args();
        $args['inhome'] = 1;
		if($limit > 0){
			$rows = $this->M_shops_cat->gets($args, $limit, 0);
		}else{
			$rows = $this->M_shops_cat->gets($args);
		}
        return $rows;
    }
	
	function gets_data($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}
		$this->load->model('shops/m_shops_cat', 'M_shops_cat');
		$data = $this->M_shops_cat->gets($args);
		
        $categories = array();
        if(is_array($data) && !empty($data)){
            foreach($data as $key=>$value){
                $categories[$value['id']] = array(
                    'lid' => $value['id'],
                    'lalias' => $value['alias'],
                    'lname' => $value['name'],
                    'lurl' => site_url('danh-muc-san-pham' . '/' . $value['alias']),
                );
            }
        }
        return $categories;
    }
    
    function gets_root($parent = 0) {
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        $args = $this->default_args();
        $args['parent'] = $parent;
        $rows = $this->M_shops_cat->gets($args);
        return $rows;
    }

    function admin_main() {
        $this->_initialize_admin();
        $this->redirect_admin();
		
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'update') {
                $this->_message_success = 'Đã cập nhật danh mục sản phẩm!';
                $this->_message_warning = 'Không có danh mục sản phẩm nào để cập nhật!';
                $ids = $this->input->post('ids');
                $orders = $this->input->post('order');
                $count = count($orders);
                if (!empty($ids) && !empty($orders)) {
                    for ($i = 0; $i < $count; $i++) {
                        $data = array(
                            'order' => $orders[$i]
                        );
                        $id = $ids[$i];
                        if ($this->M_shops_cat->update($id, $data)) {
                            $notify_type = 'success';
                            $notify_content = $this->_message_success;
                            $this->output->clearCache();
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
            'folder' => 'shops',
            'name' => 'admin-cat-items'
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();
        $rows = $this->M_shops_cat->gets($args);
        $this->_data['data_list'] = $this->get_data_list($rows);
        $this->_data['data_input'] = $this->get_data_input($rows);
        $this->_data['rows'] = $rows;

        $this->_data['title'] = 'Quản lý danh mục sản phẩm - Sản phẩm - ' . $this->_data['title'];
        $this->_data['main_content'] = 'shops/admin/view_page_cat';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_content() {
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
            'folder' => 'shops',
            'name' => 'admin-cat-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('name', 'Tên danh mục sản phẩm', 'trim|required|xss_clean');
            $this->form_validation->set_rules('alias', 'Liên kết tĩnh', 'trim|required|xss_clean|callback_check_alias_availablity');

            if ($this->form_validation->run($this)) {
                if ($this->input->post('id')) {//update
                    $id = $this->input->post('id');
                    if ($this->admin_update($id)) {
                        $this->_upload_images($id, 'image');
                        $notify_type = 'success';
                        $notify_content = 'Dữ liệu đã lưu thay đổi!';
                    } else {
                        $notify_type = 'danger';
                        $notify_content = 'Dữ liệu chưa lưu thay đổi!';
                    }
                } else {//add
                    $insert_id = $this->admin_add();
                    if ($insert_id != 0) {
                        $this->_upload_images($insert_id, 'image');
                        $notify_type = 'success';
                        $notify_content = 'Dữ liệu đã lưu!';
                    } else {
                        $notify_type = 'danger';
                        $notify_content = 'Dữ liệu chưa lưu!';
                    }
                }
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url($this->_module_slug));
            } else {
                $id = ($this->uri->segment(5) == '') ? 0 : $this->uri->segment(5);
                if ($id > 0) {
                    $row = $this->get($id);
                }
                $this->_data['row'] = (isset($row) ? $row : null);
            }
        } else {//load
            $id = ($this->uri->segment(5) == '') ? 0 : $this->uri->segment(5);
            if ($id > 0) {
                $row = $this->get($id);
            }
            $this->_data['row'] = (isset($row) ? $row : null);
        }

        $args = $this->default_args();
        $rows = $this->M_shops_cat->gets($args);
        $this->_data['data_list'] = $this->get_data_list($rows);
        $this->_data['data_input'] = $this->get_data_input($rows);
        $this->_data['rows'] = $rows;

        if ($id > 0) {
            $this->_data['breadcrumbs_module_func'] .= ' (Cập nhật)';
        } else {
            $this->_data['breadcrumbs_module_func'] .= ' (Thêm mới)';
        }
        $this->_data['title'] = 'Quản lý danh mục sản phẩm - Sản phẩm - ' . $this->_data['title'];
        $this->_data['main_content'] = 'shops/admin/view_page_cat_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function check_alias_availablity() {//dùng để kiểm tra alias khi admin nhập alias danh mục sản phẩm
        $this->load->model('shops/m_shops_cat', 'M_shops_cat');
        $post = $this->input->post();
        $this->_message_success = 'true';
        $this->_message_danger = 'false';

        if (!empty($post)) {
            if ($this->input->post('ajax') == '1') {
                $alias = $this->input->post('alias');
                $id = $this->input->post('id');
                if ($this->M_shops_cat->check_alias_availablity($alias, $id)) {
                    $this->_status = "success";
                    $this->_message = $this->_message_success;
                } else {
                    $this->_status = "danger";
                    $this->_message = $this->_message_danger;
                }

                $this->set_json_encode();
                $this->load->view('layout/json_data', $this->_data);
            } else {
                $alias = $this->input->post('alias');
                $id = $this->input->post('id');
                if ($this->M_shops_cat->check_alias_availablity($alias, $id)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } else {
            redirect(base_url());
        }
    }

    function get_max_order($parent = 0) {
        $data = $this->M_shops_cat->get_max_order($parent);
        return isset($data['order']) ? (int) $data['order'] : 0;
    }

    function get_current_parent($id) {
        $result = $this->get($id);
        return isset($result['parent']) ? (int) $result['parent'] : 0;
    }

    function admin_add() {
        $parent = $this->input->post('parent');

        $data = array(
            'parent' => $parent,
            'name' => $this->input->post('name'),
            'alias' => $this->input->post('alias'),
            'slogun' => $this->input->post('slogun'),
            'title_seo' => $this->input->post('title_seo'),
            'description' => $this->input->post('description'),
            'keywords' => $this->input->post('keywords'),
            'other_seo' => $this->input->post('other_seo'),
            'h1_seo' => $this->input->post('h1_seo'),
            'inhome' => ($this->input->post('inhome') ? 1 : 0),
            'add_time' => time(),
            'edit_time' => 0,
            'order' => $this->get_max_order($parent) + 1,
            'status' => 1
        );

        return $this->M_shops_cat->add($data);
    }

    function admin_update($id) {
        $is_update_order = FALSE;
        $parent = $this->input->post('parent');
        $current = $this->get($id);

        $data = array(
            'parent' => $parent,
            'name' => $this->input->post('name'),
            'alias' => $this->input->post('alias'),
            'slogun' => $this->input->post('slogun'),
            'title_seo' => $this->input->post('title_seo'),
            'description' => $this->input->post('description'),
            'keywords' => $this->input->post('keywords'),
            'other_seo' => $this->input->post('other_seo'),
            'h1_seo' => $this->input->post('h1_seo'),
            'inhome' => ($this->input->post('inhome') ? 1 : 0),
            'edit_time' => time(),
            'status' => 1
        );

        if ($parent != $current['parent']) {
            $data['order'] = $this->get_max_order($parent) + 1; //gia tri sap xep lon nhat cua menu mới
            $is_update_order = TRUE;
        }

        if ($this->M_shops_cat->update($id, $data)) {
            if ($is_update_order) {
                /* sap xep lại loai san truoc khi update */
                $child = $this->get_child($current['parent']);
                if (!empty($child)) {
                    $i = 0;
                    foreach ($child as $value) {
                        $i++;
                        $data_child = array(
                            'order' => $i
                        );
                        $this->M_shops_cat->update($value['id'], $data_child);
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

        $id = ($this->uri->segment(5) == '') ? 0 : $this->uri->segment(5);
        if ($id != 0) {
            $row = $this->get($id);
            $has_child = $this->get_child($row['id']);

            $rows_in_cat = modules::run('shops/rows/get_items_in_cat_id', $id, 1);

            if (empty($has_child) && empty($rows_in_cat)) {
                if ($this->M_shops_cat->delete($id)) {
                    @unlink(FCPATH . $this->_path . $row['image']);

                    /* sap xep lai menu cha */
                    $child = $this->get_child($row['parent']);
                    if (!empty($child)) {
                        $i = 0;
                        foreach ($child as $value) {
                            $i++;
                            $data_child = array(
                                'order' => $i
                            );
                            $this->M_shops_cat->update($value['id'], $data_child);
                        }
                    }

                    $notify_type = 'success';
                    $notify_content = 'Xóa danh mục sản phẩm thành công!';
                } else {
                    $notify_type = 'danger';
                    $notify_content = 'Danh mục sản phẩm chưa xóa được!';
                }
            } else {
                $notify_type = 'danger';
                if (!empty($has_child)) {
                    $notify_content = 'Danh mục sản phẩm này có chứa danh mục sản phẩm con nên chưa xóa được! Hãy xóa các danh mục sản phẩm con và thực hiện lại!';
                } else {
                    $notify_content = 'Danh mục sản phẩm này có sản phẩm đang sử dụng nên chưa xóa được! Hãy xóa các sản phẩm trong danh mục sản phẩm này và thực hiện lại!';
                }
            }
        } else {
            $notify_type = 'warning';
            $notify_content = 'Danh mục sản phẩm không tồn tại!';
        }
        $this->set_notify_admin($notify_type, $notify_content);
        redirect(get_admin_url($this->_module_slug));
    }

    function get_child($parent) {
        return $this->M_shops_cat->get_child($parent);
    }

    function get_child_list($parent = 0) {
        $child_list = array();
        $child = $this->get_child($parent);
        foreach ($child as $value) {
            $child_list[] = (int) $value['id'];
        }

        return $child_list;
    }

    function get_data_list($cat = null) {
        $key_category_list = array();
        $root_category_list = array();
        $category_list = array();
        foreach ($cat as $value) {
            $sub = $this->get_child($value['id']);
            if (is_array($sub) && $sub != NULL) {
                $key_category_list[] = $value['id'];
            }
            if ($value['parent'] == 0) {
                $root_category_list[] = $value['id'];
            }
        }

        $category_list[0] = $root_category_list;

        foreach ($key_category_list as $value) {
            $category_list[$value] = $this->get_child_list($value);
        }

        return $category_list;
    }

    function get_data_input($cat = null) {
        $categories = array();
        $categories[0] = array(
            'lname' => 'Root',
            'lurl' => '#',
            'lid' => '#',
            'order' => '#',
            'linhome' => 0,
        );

        foreach ($cat as $value) {
            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => site_url($this->config->item('url_shops_cat') . '/' . $value['alias']),
                'lid' => $value['id'],
                'order' => $value['order'],
                'linhome' => $value['inhome'],
            );
        }
        return $categories;
    }

    private function _upload_images($id, $input_name) {
        $row = $this->get($id);
        $info = modules::run('files/index', $input_name, $this->_path);
        if (isset($info['uploads'])) {
            $upload_images = $info['uploads']; // thông tin ảnh upload
            if ($_FILES[$input_name]['size'] != 0) {
                foreach ($upload_images as $value) {
                    $file_name = $value['file_name']; //tên ảnh
                    $data_images = array(
                        'image' => $file_name
                    );
                    $this->M_shops_cat->update($id, $data_images);
                }
                @unlink(FCPATH . $this->_path . $row['image']);
            }
        }
    }

    function get_data_old(){
        die;
        $rows = $this->M_shops_rows->gets_cat();
        echo "<pre>";
        print_r($rows);
        echo "</pre>";
        die();
        if(is_array($rows) && !empty($rows)){
            foreach ($rows as $value) {
                $data = array(
                    'id' => $value['id'],
                    'parent' => $value['parent'],
                    'name' => $value['name'],
                    'alias' => $value['alias'],
                    'image' => $value['image'],
                    'slogun' => $value['slogun'],
                    'title_seo' => $value['title_seo'],
                    'description' => $value['description'],
                    'keywords' => $value['keywords'],
                    'other_seo' => $value['other_seo'],
                    'h1_seo' => $value['h1_seo'],
                    'inhome' => $value['inhome'],
                    'add_time' => time(),
                    'edit_time' => 0,
                    'order' => $value['order'],
                    'status' => 1
                );

                $this->M_shops_cat->add($data);
            }
        }
        echo "<pre>";
        print_r($rows);
        echo "</pre>";
        die('Completed!');
    }
}

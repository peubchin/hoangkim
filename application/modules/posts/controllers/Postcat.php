<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Postcat extends Layout {

    private $_module_slug = 'posts/cat';

    function __construct() {
        parent::__construct();
        $this->_data['module_slug'] = $this->_module_slug;
        $this->load->model('posts/m_postcat', 'M_postcat');
        $this->_data['breadcrumbs_module_name'] = 'Chủ đề';
    }
    
    function admin_ajax_change_inhome() {
        $post = $this->input->post();
        if (!empty($post)) {
            $value = $this->input->post('value');
            $id = $this->input->post('id');
            $data = array(
                'inhome' => $value
            );
            if ($this->M_postcat->update($id, $data)) {
                if ($value == 1) {
                    $notify_type = 'success';
                    $notify_content = 'Đã bật chủ đề ngoài trang chủ!';
                } else {
                    $notify_type = 'warning';
                    $notify_content = 'Đã tắt chủ đề ngoài trang chủ!';
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

    function gets($options = array()) {
        $default_args = array();

        if(is_array($options) && !empty($options)){
            $args = array_merge($default_args, $options);
        }else{
            $args = $default_args;
        }

        return $this->M_postcat->gets($args);
    }

    function admin_main() {
        $this->_initialize_admin();
        $this->redirect_admin();
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'update') {
                $this->_message_success = 'Đã cập nhật chủ đề!';
                $this->_message_warning = 'Không có chủ đề nào để cập nhật!';
                $ids = $this->input->post('ids');
                $orders = $this->input->post('order');
                $count = count($orders);
                if (!empty($ids) && !empty($orders)) {
                    for ($i = 0; $i < $count; $i++) {
                        $data = array(
                            'order' => $orders[$i]
                        );
                        $id = $ids[$i];
                        if ($this->M_postcat->update($id, $data)) {
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

        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation',
            'name' => 'jquery.validate'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'jquery-validation/localization',
            'name' => 'messages_vi'
        );
        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'posts',
            'name' => 'admin-cat-items'
        );
        $this->set_modules();


        $get = $this->input->get();
        $this->_data['get'] = $get;

        $data_rows = $this->M_postcat->get_data_all();

        $this->_data['menu_list'] = $this->get_menu_list_all($data_rows);
        $this->_data['menu_input'] = $this->get_input_all($data_rows);
        $this->_data['rows'] = $data_rows;

        $this->_data['breadcrumbs_module_func'] = '';
        $this->_data['title'] = 'Quản lý chủ đề - Chủ đề - ' . $this->_data['title'];
        $this->_data['main_content'] = 'posts/admin/view_page_cat_index';
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
        $this->set_plugins_admin();

        $this->_modules_script[] = array(
            'folder' => 'posts',
            'name' => 'admin-cat-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('name', 'Tên chủ đề', 'trim|required|xss_clean');
            $this->form_validation->set_rules('alias', 'Liên kết tĩnh', 'trim|required|xss_clean|callback_check_alias_availablity');

            if ($this->form_validation->run($this)) {
                if ($this->input->post('id')) {//update
                    $id = $this->input->post('id');
                    if ($this->admin_update($id)) {
                        $this->output->clearCache();
                        $notify_type = 'success';
                        $notify_content = 'Dữ liệu đã lưu thay đổi!';
                    } else {
                        $notify_type = 'danger';
                        $notify_content = 'Dữ liệu chưa lưu thay đổi!';
                    }
                } else {//add
                    if ($this->admin_add()) {
                        $this->output->clearCache();
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
                $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
                if ($id > 0) {
                    $cat = $this->M_postcat->get_news_cat($id);
                }
                $this->_data['row'] = (isset($cat[0]) ? $cat[0] : null);
            }
        } else {//load
            $id = ($this->uri->segment(5) == '') ? 0 : $this->uri->segment(5);

            if ($id > 0) {
                $row = $this->get_data_in_id($id);
            }
            $this->_data['row'] = (isset($row) ? $row : null);
        }
		$this->_data['postcat_list'] = modules::run('posts/postcat/get_menu_list');
		$this->_data['postcat_data'] = modules::run('posts/postcat/get_data');
		$this->_data['postcat_input'] = modules::run('posts/postcat/get_input');

        if ($id > 0) {
            $this->_data['breadcrumbs_module_func'] = 'Cập nhật';
        } else {
            $this->_data['breadcrumbs_module_func'] = 'Thêm mới';
        }
        $this->_data['title'] = 'Quản lý chủ đề - Chủ đề - ' . $this->_data['title'];
        $this->_data['main_content'] = 'posts/admin/view_page_cat';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function get_in_alias($alias) {
        $this->load->model('posts/m_postcat', 'M_postcat');
        return $this->M_postcat->get_in_alias($alias);
    }

    function get_data_in_id($id = 0) {
        return $this->M_postcat->get_data_in_id($id);
    }
	
	function get($id = 0) {
        return $this->M_postcat->get($id);
    }

    function get_max_order($parent = 0) {
        $data = $this->M_postcat->get_max_order($parent);
        return isset($data['order']) ? (int) $data['order'] : 0;
    }

    function get_current_parent($id) {
        $result = $this->get_data_in_id($id);
        return isset($result['parent']) ? (int) $result['parent'] : 0;
    }

    function admin_add() {
        $parent = $this->input->post('parent');

        $data = array(
            'parent' => $parent,
            'name' => $this->input->post('name'),
            'hometext' => $this->input->post('hometext'),
            'alias' => $this->input->post('alias'),
            'inhome' => $this->input->post('inhome') ? 1 : 0,
            'author_id' => $this->_data['userid'],
            'title_seo' => $this->input->post('title_seo'),
            'description' => $this->input->post('description'),
            'keywords' => $this->input->post('keywords'),
            'other_seo' => $this->input->post('other_seo'),
            'order' => $this->get_max_order($parent) + 1,
            'status' => 1,
            'add_time' => time()
        );

        return $this->M_postcat->add($data);
    }

    function admin_update($id) {
		$is_update_order = FALSE;
        $parent = $this->input->post('parent');
        $current = $this->get($id);

        $data = array(
            'parent' => $parent,
            'name' => $this->input->post('name'),
            'hometext' => $this->input->post('hometext'),
            'alias' => $this->input->post('alias'),
            'inhome' => $this->input->post('inhome') ? 1 : 0,
            'title_seo' => $this->input->post('title_seo'),
            'description' => $this->input->post('description'),
            'keywords' => $this->input->post('keywords'),
            'other_seo' => $this->input->post('other_seo'),
            'status' => 1,
            'edit_time' => time()
        );
		
		if ($parent != $current['parent']) {
            $data['order'] = $this->get_max_order($parent) + 1;
            $is_update_order = TRUE;
        }

        if ($this->M_postcat->update($id, $data)) {
            if ($is_update_order) {
                /* sap xep lại menu hiện tại */
                $child = $this->get_child($current['parent']);
                if (!empty($child)) {
                    $i = 0;
                    foreach ($child as $value) {
                        $i++;
                        $data_child = array(
                            'order' => $i
                        );
                        $this->M_postcat->update($value['id'], $data_child);
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
		
        $segment = 5;
        $id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        if ($id != 0) {
            $row = $this->get($id);
            $has_child = $this->get_child($row['id']);

            $posts_in_cat = modules::run('posts/postmeta/gets_in_key_and_value', 'post_cat_id', $id);

            if (empty($has_child) && empty($posts_in_cat)) {
                if ($this->M_postcat->delete($id)) {
                    /* sap xep lai menu cha */
                    $child = $this->get_child($row['parent']);
                    if (!empty($child)) {
                        $i = 0;
                        foreach ($child as $value) {
                            $i++;
                            $data_child = array(
                                'order' => $i
                            );
                            $this->M_postcat->update($value['id'], $data_child);
                        }
                    }

                    $notify_type = 'success';
                    $notify_content = 'Xóa chủ đề thành công!';
                } else {
                    $notify_type = 'danger';
                    $notify_content = 'Chủ đề chưa xóa được!';
                }
            } else {
                $notify_type = 'danger';
                if (!empty($has_child)) {
                    $notify_content = 'Chủ đề này có chứa chủ đề con nên chưa xóa được! Hãy xóa các chủ đề con và thực hiện lại!';
                } else {
                    $notify_content = 'Chủ đề này có chứa bài viết nên chưa xóa được! Hãy xóa các bài viết trong chủ đề này và thực hiện lại!';
                }
            }
        } else {
            $notify_type = 'warning';
            $notify_content = 'Chủ đề không tồn tại!';
        }
        $this->set_notify_admin($notify_type, $notify_content);
        redirect(get_admin_url($this->_module_slug));
    }

    function gets_inhome($limit = 3) {
        $this->load->model('posts/m_postcat', 'M_postcat');
        $args['inhome'] = 1;
        if ($limit == 0) {
            $rows = $this->M_postcat->gets($args);
        } else {
            $rows = $this->M_postcat->gets($args, $limit, 0);
        }
        return $rows;
    }

    function get_child($parent) {
        return $this->M_postcat->get_child($parent);
    }

    function get_input() {
        $categories = array();
        $categories[0] = array(
            'lname' => 'Root',
            'lurl' => '#',
            'linhome' => 0,
            'order' => '#',
        );
        $cat = $this->M_postcat->get_data_all();

        foreach ($cat as $value) {
            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => $value['id'],
                'linhome' => $value['inhome'],
                'order' => $value['order'],
            );
        }
        return $categories;
    }

    /* show menu */

    function get_data() {
        $categories = array();
        $categories[0] = array(
            'lname' => 'Root',
            'lurl' => '#',
            'linhome' => 0,
        );
        $cat = $this->M_postcat->get_data_all();

        foreach ($cat as $value) {
            $url = '';
            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => $url,
                'linhome' => $value['inhome']
            );
        }
        return $categories;
    }

    function get_menu_list() {
        $key_category_list = array();
        $root_category_list = array();
        $category_list = array();
        $cat = $this->M_postcat->get_data_all();
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
            $category_list[$value] = $this->get_category_list_sub($value);
        }

        return $category_list;
    }

    function get_category_list_sub($parent = 0) {
        $value_category_list = array();
        $sub_cat = $this->get_child($parent);
        foreach ($sub_cat as $value) {
            $value_category_list[] = (int) $value['id'];
        }

        return $value_category_list;
    }

    function get_all_data() {
        return $this->M_postcat->get_data_all();
    }

    function get_data_all($cat = null) {
        $categories = array();
        $categories[0] = array(
            'lname' => 'Root',
            'lurl' => '#',
        );

        foreach ($cat as $value) {
            $url = '';
            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => $url,
            );
        }
        return $categories;
    }

    function get_menu_list_all($cat = null) {
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
            $category_list[$value] = $this->get_category_list_sub($value);
        }

        return $category_list;
    }

    function get_input_all($cat = null) {
        $categories = array();
        $categories[0] = array(
            'lid' => 0,
            'lname' => 'Root',
            'lurl' => '#',
            'linhome' => 0,
            'order' => '#',
            'viewcat' => '',
            'add_time' => 0,
            'edit_time' => 0,
        );

        foreach ($cat as $value) {
            $categories[$value['id']] = array(
                'lid' => $value['id'],
                'lname' => $value['name'],
                'lurl' => $value['id'],
                'linhome' => $value['inhome'],
                'order' => $value['order'],
                'viewcat' => $value['viewcat'],
                'add_time' => $value['add_time'] > 0 ? display_date($value['add_time']) : "None",
                'edit_time' => $value['edit_time'] > 0 ? display_date($value['edit_time']) : "None",
            );
        }
        return $categories;
    }

    function check_alias_availablity() {//dùng để kiểm tra alias
        $this->load->model('posts/m_postcat', 'M_postcat');
        $post = $this->input->post();
        $this->_message_success = 'true';
        $this->_message_danger = 'false';

        if (!empty($post)) {
            if ($this->input->post('ajax') == '1') {
                $alias = $this->input->post('alias');
                $id = $this->input->post('id');
                if ($this->M_postcat->check_alias_availablity($alias, $id)) {
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
                if ($this->M_postcat->check_alias_availablity($alias, $id)) {
                    return TRUE;
                } else {
                    return FALSE;
                }
            }
        } else {
            redirect(base_url());
        }
    }

}

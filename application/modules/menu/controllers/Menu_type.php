<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Menu_type extends Layout {

    private $_module_slug = 'routes';
    private $_post_type = '';

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('menu/m_menu_type', 'M_menu_type');
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_data['breadcrumbs_module_name'] = 'Menu';
    }

    function default_args() {
        $order_by = array(
            'order' => 'ASC'
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function counts($args) {
        return $this->M_menu_type->counts($args);
    }

    function gets($args) {
        return $this->M_menu_type->gets($args);
    }

    function get($id) {
        $this->load->model('menu/m_menu_type', 'M_menu_type');
        return $this->M_menu_type->get($id);
    }

    public function admin_create_cache_routes() {
        $this->redirect_admin();

        $this->_message_success = 'Đã khởi tạo routes!';
        $this->_message_danger = 'Routes chưa được khởi tạo!';

        $this->load->helper('file');
        $is_error = FALSE;

        $first_line = "\n";
        $data = array();
        $data[] = '<?php';

        $args = $this->default_args();

        $routes = $this->gets($args);
        foreach ($routes as $value) {
            switch ($value['type']) {
                case 'categories':
                    $data[] = $first_line . '$route["' . $value['values'] . '/(:any)/(:num)"] = "' . "shops/rows/site_items_in_listcatid/$1/$2" . '";'; //theo danh sách phân trang
                    $data[] = '$route["' . $value['values'] . '/(:any)"] = "' . "shops/rows/site_items_in_listcatid/$1" . '";'; //theo danh sách
                    break;

                case 'products':
                    $data[] = $first_line . '$route["' . $value['values'] . '/(:any)/(:any)"] = "' . "shops/rows/site_details/$1/$2" . '";'; //chi tiết
                    break;

                case 'post':
                    $data[] = $first_line . '$route["' . '(:any)/(:any)"] = "' . "posts/site_details/$1" . '";'; //bài viết
                    break;

                case 'pages':
                    $data[] = $first_line . '$route["' . '(:any)"] = "' . "pages/site_details/$1" . '";'; //trang tĩnh
                    break;

                case 'post_categories':
                    $data[] = $first_line . '$route["' . $value['values'] . '/(:any)/(:num)"] = "' . "posts/site_items_in_cat/$1/$2" . '";'; //theo danh sách phân trang
                    $data[] = '$route["' . $value['values'] . '/(:any)"] = "' . "posts/site_items_in_cat/$1" . '";'; //theo danh sách
                    break;

                case 'posts':
                    $data[] = $first_line . '$route["' . $value['values'] . '"] = "' . "posts/index" . '";'; //tin tuc phân trang
                    $data[] = '$route["' . $value['values'] . '/([0-9\-]+)"] = "' . "posts/index/$1" . '";'; //tin tuc
                    break;

                case 'contact':
                    $data[] = $first_line . '$route["' . $value['values'] . '"] = "' . "contact/contact/index" . '";'; //lien he
                    break;

                case 'site_map':
                    $data[] = $first_line . '$route["' . $value['values'] . '"] = "' . "sitemap/index" . '";'; //site map
                    break;

                /* case 'products':
                  $data[] = $first_line . '$route["' . $value['values'] . '"] = "' . "shops/index" . '";'; //theo danh sách phân trang
                  $data[] = '$route["' . $value['values'] . '/([0-9\-]+)"] = "' . "shops/index/$1" . '";'; //theo danh sách
                  break;
                 * 
                 */

                default:
                    break;
            }
        }
        write_file(APPPATH . "cache/routes.php", "", 'w+'); //overite file

        foreach ($data as $value) {
            if (!write_file(APPPATH . "cache/routes.php", $value . "\n", 'a+')) {
                $is_error = TRUE;
            }
        }

        if ($this->input->post('is_ajax')) {
            if (!$is_error) {
                $this->_status = "success";
                $this->_message = $this->_message_success;
            } else {
                $this->_status = "danger";
                $this->_message = $this->_message_danger;
            }
            $this->set_json_encode();
            $this->load->view('layout/json_data', $this->_data);
        } else {
            if (!$is_error) {
                $notify_type = 'success';
                $notify_content = $this->_message_success;
            } else {
                $notify_type = 'danger';
                $notify_content = $this->_message_danger;
            }
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }
    }

    function get_data_all() {
        return $this->M_menu_type->gets(array());
    }

    function get_values_in_id($id = 0) {
        $values = '';
        $data = $this->get($id);
        if (!empty($data)) {
            $values = $data['values'];
        }
        return $values;
    }

    function get_data_in_type($type) {
        $this->load->model('menu/m_menu_type', 'M_menu_type');
        return $this->M_menu_type->get_data_in_type($type);
    }

    function get_values_type($type) {
        $data = $this->get_data_in_type($type);
        $values = isset($data['values']) ? $data['values'] : '';
        
        return $values;
    }

    function admin_index() {
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
            'folder' => 'routes',
            'name' => 'admin-routes'
        );
        $this->set_modules();

        if ($this->input->get()) {
            $this->_data['get'] = $this->input->get();
        }

        if ($this->input->get('post_type')) {
            $this->_post_type = $this->input->get('post_type');
        }

        $args = $this->default_args();
        $args['post_type'] = $this->_post_type;
        if ($this->input->get('position')) {
            $args['position'] = $this->input->get('position');
        }

        $total = $this->counts($args);
        $perpage = 5; /* Số bảng ghi muốn hiển thị trên một trang */

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
        $config['base_url'] = get_admin_url($this->_module_slug);
        $config['uri_segment'] = 3;

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        $offset = ($this->uri->segment(3) == '') ? 0 : $this->uri->segment(3);

        $this->_data['rows'] = $this->M_menu_type->gets($args, 0, 0);
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Danh sách routes - ' . $this->_data['title'];
        $this->_data['main_content'] = 'menu/admin/view_page_menu_type_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_add() {
        $data = array(
            'name' => $this->input->post('name'),
            'values' => $this->input->post('values'),
            'status' => 1,
            'add_time' => time(),
            'edit_time' => 0
        );

        return $this->M_menu_type->add($data);
    }

    function admin_update($id) {
        $data = array(
            'name' => $this->input->post('name'),
            'values' => $this->input->post('values'),
            'status' => 1,
            'edit_time' => time()
        );

        return $this->M_menu_type->update($id, $data);
    }

    function admin_delete() {
        $this->load->module('users');

        $this->_message_success = 'Đã xóa routes!';
        $this->_message_warning = 'Routes này không tồn tại!';
        if ($this->users->validate_admin_logged_in() == TRUE) {
            if ($this->input->post('ajax') == '1') {
                $id = $this->input->post('id');
                if ($id != 0) {
                    if ($this->M_menu_type->delete($id)) {
                        $this->_status = "success";
                        $this->_message = $this->_message_success;
                    } else {
                        $this->_status = "danger";
                        $this->_message = $this->_message_danger;
                    }
                } else {
                    $this->_status = "warning";
                    $this->_message = $this->_message_warning;
                }

                $this->set_json_encode();
                $this->load->view('layout/json_data', $this->_data);
            } else {
                $id = $this->input->get('id');
                if ($id != 0) {
                    if ($this->M_menu_type->delete($id)) {
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
        } else {
            if ($this->input->post('ajax') == '1') {
                $this->_status = "danger";
                $this->_message = $this->_message_banned;

                $this->set_json_encode();
                $this->load->view('layout/json_data', $this->_data);
            } else {
                $notify_type = 'danger';
                $notify_content = $this->_message_banned;
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url($this->_module_slug));
            }
        }
    }

    function admin_content() {
        $this->redirect_admin();

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
            'folder' => 'images',
            'name' => 'admin-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->load->helper('language');
            $this->lang->load('form_validation', 'vietnamese');

            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('name', 'Tên routes', 'trim|required');

            if ($this->form_validation->run($this)) {
                if ($this->input->post('id')) {//update
                    $err = FALSE;
                    $id = $this->input->post('id');
                    if (!$this->admin_update($id)) {
                        $err = TRUE;
                    }

                    if ($err === FALSE) {
                        $notify_type = 'success';
                        $notify_content = 'Cập nhật routes thành công!';
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
                        $notify_content = 'Routes đã được thêm!';
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

        $title = 'Thêm routes - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];

        $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
        if ($id != 0) {
            $row = $this->get($id);

            $this->_data['row'] = $row;
            $title = 'Cập nhật routes - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
        }

        $this->_data['title'] = $title;
        $this->_data['main_content'] = 'menu/admin/view_page_menu_type_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

}

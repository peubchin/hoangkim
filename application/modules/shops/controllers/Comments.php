<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Comments extends Layout {

    private $_module_slug = 'comments';

    function __construct() {
        parent::__construct();
        $this->load->model('comments/m_comments', 'M_comments');
        $this->_data['breadcrumbs_module_name'] = 'Bình luận';
        $this->_data['breadcrumbs_module_func'] = '';
        $this->_data['module_slug'] = $this->_module_slug;
    }
	
	function admin_ajax_change_status() {
		if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }
		
        $post = $this->input->post();
        if (!empty($post)) {
            $value = $this->input->post('value');
            $id = $this->input->post('id');
            $data = array(
                'status' => $value
            );
            if ($this->M_comments->update($id, $data)) {
                if ($value == 1) {
                    $notify_type = 'success';
                    $notify_content = 'Đã bật bình luận!';
                } else {
                    $notify_type = 'warning';
                    $notify_content = 'Đã tắt bình luận!';
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
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function counts($args) {
        $this->load->model('comments/m_comments', 'M_comments');
        return $this->M_comments->counts($args);
    }

    function get($id) {
        $this->load->model('comments/m_comments', 'M_comments');
        return $this->M_comments->get($id);
    }

    function gets($options = array()) {
        $this->load->model('comments/m_comments', 'M_comments');
        $default_args = $this->default_args();
        $args = array_merge($default_args, $options);
        $rows = $this->M_comments->gets($args);
        $data = array(
            'data_list' => $this->get_data_list($rows),
            'data_input' => $this->get_data_input($rows)
        );

        return $data;
    }

    function index_ajax() {
        if (!$this->input->is_ajax_request()) {
            exit('No direct script access allowed');
        }

        $message = array();
        $message['status'] = 'warning';
        $message['content'] = null;
        $message['message'] = 'Kiểm tra thông tin nhập';

        $post = $this->input->post();
        if (!empty($post)) {
            $this->load->model('comments/m_comments', 'M_comments');
            $err = FALSE;
            //$code = $this->input->post('code');

            $data['post_id'] = $this->input->post('post_id');
            $data['parent'] = $this->input->post('parent');
			$data['type'] = $this->input->post('type');
            $data['author'] = $this->input->post('author');
            $data['email'] = $this->input->post('email');
            $data['website'] = $this->input->post('website');
            $data['comment'] = $this->input->post('comment');

            $data['created'] = time();
            $data['modified'] = 0;
            $data['status'] = 1;

            $id = $this->M_comments->add($data);
            if ($id != 0) {
                $data['id'] = $id;
                $data['created'] = date('F d, Y', $data['created']) . ' at ' . date('h:i A', $data['created']);
                $partial = array();
                $partial['data'] = $data;
                $data['data'] = $this->load->view('layout/site/partial/comment_item', $partial, true);
            } else {
                $err = TRUE;
            }

            if ($err === FALSE) {
                $message['status'] = 'success';
                $message['content'] = $data;
                $message['message'] = 'Đã gửi bình luận thành công!';
            } else {
                $message['status'] = 'error';
                $message['content'] = null;
                $message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';
            }
        }
        echo json_encode($message);
        exit();
    }

    function admin_main() {
        $this->_initialize_admin();
		$this->redirect_admin();
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'update') {
                $this->_message_success = 'Đã cập nhật bình luận!';
                $this->_message_warning = 'Không có bình luận nào để cập nhật!';
                $ids = $this->input->post('ids');
                $orders = $this->input->post('order');
                $count = count($orders);
                if (!empty($ids) && !empty($orders)) {
                    for ($i = 0; $i < $count; $i++) {
                        $data = array(
                            'order' => $orders[$i]
                        );
                        $id = $ids[$i];
                        if ($this->M_comments->update($id, $data)) {
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
            'folder' => 'comments',
            'name' => 'admin-items'
        );
        $this->set_modules();

		$get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();

        $total = $this->M_comments->counts($args);

        $perpage = isset($get['per_page']) ? $get['per_page'] : $perpage = $this->config->item('per_page'); /* Số bảng ghi muốn hiển thị trên một trang */

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

		$segment = 3;
        if (!empty($get)) {
            $config['base_url'] = get_admin_url($this->_module_slug);
            $config['suffix'] = '?' . http_build_query($get, '', "&");
            $config['first_url'] = get_admin_url($this->_module_slug . '?' . http_build_query($get, '', "&")); //
            $config['uri_segment'] = $segment;
        } else {
            $config['base_url'] = get_admin_url($this->_module_slug);
            $config['uri_segment'] = $segment;
        }

        $this->pagination->initialize($config);

        $pagination = $this->pagination->create_links();
        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);

        $rows = $this->M_comments->gets($args, $perpage, $offset);
		if(is_array($rows) && !empty($rows)){
			foreach($rows as $key => $value){
				$title = $value['shops_title'];
				$row = modules::run('shops/rows/get', $value['product_id']);
				$link = (is_array($row) && !empty($row)) ? site_url('cong-viec' . '/' . $row['alias'] . '-' . $row['id']) : site_url();
				$rows[$key]['link'] = $link;
				$rows[$key]['title'] = $title;
			}
		}
        $this->_data['rows'] = $rows;
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Quản lý bình luận - ' . $this->_data['title'];
        $this->_data['main_content'] = 'comments/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }
	
	function admin_content() {
        $this->_initialize_admin();
		$this->redirect_admin();
		
		$id = (int) (($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4));
		if ($id == 0) {
			show_404();			
		}
		$row = $this->get($id);
		if (empty($row)) {
			show_404();			
		}
		$this->_data['row'] = $row;

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
            'folder' => 'comments',
            'name' => 'admin-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('comment', 'Nội dung bình luận', 'trim|required|xss_clean');

            if ($this->form_validation->run($this)) {
				$id = $row['id'];
				$data = array(
					'comment' => $this->input->post('comment'),
					'modified' => time()
				);
				if ($this->M_comments->update($id, $data)) {
					$notify_type = 'success';
					$notify_content = 'Dữ liệu đã lưu thay đổi!';
				} else {
					$notify_type = 'danger';
					$notify_content = 'Dữ liệu chưa lưu thay đổi!';
				}
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url($this->_module_slug));
            }
        }
		
        $this->_data['breadcrumbs_module_func'] = 'Cập nhật';
			
        $this->_data['title'] = 'Cập nhật - Bình luận - ' . $this->_data['title'];
        $this->_data['main_content'] = 'comments/admin/view_page_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_reply() {
        $this->_initialize_admin();
		$this->redirect_admin();
		
		$id = (int) (($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4));
		if ($id == 0) {
			show_404();			
		}
		$row = $this->get($id);
		if (empty($row)) {
			show_404();			
		}
		$this->_data['row'] = $row;

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
            'folder' => 'comments',
            'name' => 'admin-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('comment', 'Nội dung bình luận', 'trim|required|xss_clean');

            if ($this->form_validation->run($this)) {
				$userid = $this->_data['userid'];
				$user = modules::run('users/get', $userid);
				$data = array(
					'post_id' => $row['post_id'],
					'parent' => $row['id'],
					'type' => $row['type'],
					'author' => $user['full_name'],
					'email' => $user['email'],
					'website' => site_url(),
					'comment' => $this->input->post('comment'),
					'created' => time(),
					'modified' => 0,
					'status' => 1
				);
				$insert_id = $this->M_comments->add($data);
				
				if ($insert_id != 0) {
					$notify_type = 'success';
					$notify_content = 'Dữ liệu đã lưu!';
				} else {
					$notify_type = 'danger';
					$notify_content = 'Dữ liệu chưa lưu!';
				}
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url($this->_module_slug));
            }
        }
        $this->_data['breadcrumbs_module_func'] = 'Trả lời';
		
        $this->_data['title'] = 'Trả lời - Bình luận - ' . $this->_data['title'];
        $this->_data['main_content'] = 'comments/admin/view_page_reply';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function get_current_parent($id) {
        $result = $this->get($id);
        return isset($result['parent']) ? (int) $result['parent'] : 0;
    }

    function admin_delete() {
        $this->_initialize_admin();
		$this->redirect_admin();
		
        $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
        $row = $this->get($id);
        if ($id != 0 && !empty($row)) {
            $in_cat_id[] = $id;
            $cat = $this->gets();
            $in_cat_id = array_merge($in_cat_id, get_children($id, $cat['data_list'], $cat['data_input']));

            if ($this->M_comments->delete($in_cat_id)) {
                $notify_type = 'success';
                $notify_content = 'Xóa bình luận thành công!';
            } else {
                $notify_type = 'danger';
                $notify_content = 'Bình luận chưa xóa được!';
            }
        } else {
            $notify_type = 'warning';
            $notify_content = 'Bình luận không tồn tại!';
        }
        $this->set_notify_admin($notify_type, $notify_content);
        redirect(get_admin_url($this->_module_slug));
    }

    function get_child($parent) {
        return $this->M_comments->get_child($parent);
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
            'id' => '#',
			'type' => 0,
            'post_id' => 0,
            'author' => '#',
            'title' => '#',
            'email' => '#',
            'website' => '#',
            'comment' => '#',
            'parent' => 0,
            'created' => '#',
            'modified' => 0,
            'status' => 0
        );

        foreach ($cat as $value) {
            $categories[$value['id']] = array(
                'id' => $value['id'],
				'type' => $value['type'],
                'post_id' => $value['post_id'],
                'title' => $value['type'] == 'post' ? $value['post_title'] : $value['shop_title'],
                'author' => $value['author'],
                'email' => $value['email'],
                'website' => $value['website'],
                'comment' => $value['comment'],
                'parent' => $value['parent'],
                'created' => $value['created'],
                'modified' => $value['modified'],
                'status' => $value['status']
            );
        }
        return $categories;
    }

}

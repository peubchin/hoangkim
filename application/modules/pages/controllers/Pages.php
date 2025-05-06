<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Pages extends Layout {

    private $_module_slug = 'pages';
    private $_post_type = 'page';
    private $_postmeta = array('homeimgfile', 'homeimgalt');
    private $_required_field = array('title', 'alias', 'hometext', 'bodyhtml');
    private $_path = '';
	private $_allowed_field = array('inhome');

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('pages/m_pages', 'M_pages');
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_data['breadcrumbs_module_name'] = 'Bài viết';
        $this->_path = get_module_path('posts');
    }

	function admin_ajax_change_field() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$post = $this->input->post();
		if (!empty($post)) {
			$value = $this->input->post('value');
			$id = $this->input->post('id');
			$field = $this->input->post('field');
			$massage_success = $this->input->post('massage_success');
			$massage_warning = $this->input->post('massage_warning');
			$data = array(
				$field => $value,
			);
			if (!in_array($field, $this->_allowed_field)) {
				$notify_type = 'danger';
				$notify_content = 'Trường này không tồn tại!';
			} else if ($this->M_pages->update($id, $data)) {
				if ($value == 1) {
					$notify_type = 'success';
					$notify_content = $massage_success;
				} else {
					$notify_type = 'warning';
					$notify_content = $massage_warning;
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

	public function gets_item_field($field = '', $number = 3) {
		if ((trim($field) == '') || !in_array($field, $this->_allowed_field)) {
			return null;
		}
		$args = $this->default_args();
		$args[$field] = 1;
		if ($number > 0) {
			$rows = $this->M_pages->gets($args, $number, 0);
		} else {
			$rows = $this->M_pages->gets($args);
		}

		return $rows;
	}

    function default_args() {
        $order_by = array(
            'order' => 'ASC'
        );
        $args = array();
        $args['fields'] = $this->_postmeta;
        $args['order_by'] = $order_by;
        $args['group_by'] = array('id');

        return $args;
    }

	function counts($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}

        return $this->M_pages->counts($args);
    }

    function get($id, $alias = '') {
        return $this->M_pages->get($id, $alias);
    }

	function gets($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}

		return $this->M_pages->gets($args);
	}

    function get_max_order() {
        $args = $this->default_args();
        $order_by = array(
            'order' => 'DESC'
        );
        $args['order_by'] = $order_by;
        $perpage = 1;
        $offset = 0;
        $rows = $this->M_pages->gets($args, $perpage, $offset);
        $max_order = isset($rows[0]['order']) ? $rows[0]['order'] : 0;

        return (int) $max_order;
    }

    function re_order() {
        $args = $this->default_args();
        $rows = $this->M_pages->gets($args);
        if (!empty($rows)) {
            $i = 0;
            foreach ($rows as $value) {
                $i++;
                $data = array(
                    'order' => $i
                );
                $this->M_pages->update($value['id'], $data);
            }
        }
    }

    function site_details() {
		$this->_initialize();

        $segment = 1;
        $uri = explode("-", ($this->uri->segment($segment) == '') ? '' : $this->uri->segment($segment));

        if (count($uri) <= 1) {
            show_404();
        }

        $id = (int) end($uri);

        array_pop($uri);
        $alias = implode("-", $uri);

        if ($id == 0 || $alias == '') {
            show_404();
        }

        $row = $this->get($id);

        if (!empty($row)) {
            $title_seo = trim($row['title_seo']) != '' ? $row['title_seo'] : $row['title'];
            $keywords = $row['keywords'];
            $description = $row['description'];
            $other_seo = $row['other_seo'];
            if (trim($title_seo) != '') {
                $this->_data['title_seo'] = $title_seo . ' - ' . $this->_data['title_seo'];
            }
            if (trim($keywords) != '') {
                $this->_data['keywords'] = $keywords;
            }
            if (trim($description) != '') {
                $this->_data['description'] = $description;
            }
            if (trim($other_seo) != '') {
                $this->_data['other_seo'] = $other_seo;
            }
        } else {
            show_404();
        }
        $this->output->cache(true);

        $this->_data['row'] = $row;
        $this->update_view($row['id'], (int) $row['hitstotal'] + 1);

        $this->_breadcrumbs[] = array(
            'url' => site_url($row['alias'] . '-' . $row['id']),
            'name' => $row['title']
        );
        $this->set_breadcrumbs();

        $this->_data['main_content'] = 'layout/site/pages/single-page';
        $this->load->view('layout/site/layout', $this->_data);
    }

    function update_view($id, $view) {
        return $this->M_pages->update($id, array('hitstotal' => $view));
    }

    function index_admin() {
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
        $this->_init_fancybox();

        $this->_modules_script[] = array(
            'folder' => 'pages',
            'name' => 'admin-items'
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $args = $this->default_args();

        if (isset($get['q']) && trim($get['q']) != '') {
            $args['q'] = $get['q'];
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

        $rows = $this->M_pages->gets($args, $perpage, $offset);
        $this->_data['rows'] = $rows;
        $this->_data['pagination'] = $pagination;

        $this->_data['title'] = 'Danh sách bài viết - ' . $this->_data['title'];
        $this->_data['main_content'] = $this->_module_slug . '/admin/view_page_index';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_main() {
        $this->_initialize_admin();
        $this->redirect_admin();

        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'delete') {
                $this->_message_success = 'Đã xóa các trang tĩnh được chọn!';
                $this->_message_warning = 'Bạn chưa chọn trang tĩnh nào!';
                $ids = $this->input->post('idcheck');
                if (is_array($ids) && !empty($ids)) {
                    foreach ($ids as $id) {
                        if ($this->M_pages->delete($id)) {
                            $this->_delete_images($id);
                            modules::run('pages/postmeta/delete_multiple', $id);
                            $notify_type = 'success';
                            $notify_content = $this->_message_success;
                            $this->output->clearCache();
                        } else {
                            $notify_type = 'danger';
                            $notify_content = $this->_message_danger;
                        }
                    }
                    //sap xep lai
                    $this->re_order();
                } else {
                    $notify_type = 'warning';
                    $notify_content = $this->_message_warning;
                }
                $this->set_notify_admin($notify_type, $notify_content);
                redirect(get_admin_url($this->_module_slug));
            } elseif ($action == 'content') {
                redirect(get_admin_url($this->_module_slug . '/content'));
            } elseif ($action == 'update') {
                $this->_message_success = 'Đã cập nhật trang tĩnh!';
                $this->_message_warning = 'Không có trang tĩnh nào để cập nhật!';
                $ids = $this->input->post('ids');
                $orders = $this->input->post('order');
                $count = count($orders);
                if (!empty($ids) && !empty($orders)) {
                    for ($i = 0; $i < $count; $i++) {
                        $data = array(
                            'order' => $orders[$i]
                        );
                        $id = $ids[$i];
                        if ($this->M_pages->update($id, $data)) {
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

    function admin_add() {
        $args = array(
            'admin_id' => $this->_data['userid'],
            'addtime' => time(),
            'edittime' => 0,
            'title' => $this->input->post('title'),
            'alias' => $this->input->post('alias'),
            'hometext' => $this->input->post('hometext'),
            'bodyhtml' => $this->input->post('bodyhtml'),
            'inhome' => $this->input->post('inhome') ? 1 : 0,
            'title_seo' => $this->input->post('title_seo'),
            'description' => $this->input->post('description'),
            'keywords' => $this->input->post('keywords'),
            'other_seo' => $this->input->post('other_seo'),
            'h1_seo' => $this->input->post('h1_seo'),
            'order' => $this->get_max_order() + 1,
            'post_type' => $this->_post_type
        );

        return $this->M_pages->add($args);
    }

    function admin_update($id) {
        $args = array(
            'admin_id' => $this->_data['userid'],
            'edittime' => time(),
            'title' => $this->input->post('title'),
            'alias' => $this->input->post('alias'),
            'hometext' => $this->input->post('hometext'),
            'bodyhtml' => $this->input->post('bodyhtml'),
            'inhome' => $this->input->post('inhome') ? 1 : 0,
            'title_seo' => $this->input->post('title_seo'),
            'description' => $this->input->post('description'),
            'keywords' => $this->input->post('keywords'),
            'other_seo' => $this->input->post('other_seo'),
            'h1_seo' => $this->input->post('h1_seo'),
        );

        return $this->M_pages->update($id, $args);
    }

    function admin_delete() {
        $this->_initialize_admin();
        $this->redirect_admin();
		$id = $this->input->get('id');
		if ($id != 0) {
			if ($this->M_pages->delete($id)) {
				$this->_delete_images($id);
				modules::run('pages/postmeta/delete_multiple', $id);
				//sap xep lai
				$this->re_order();

				$notify_type = 'success';
				$notify_content = $this->_message_success;
				$this->output->clearCache();
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
            'folder' => 'tagmanager',
            'name' => 'tagmanager'
        );
        $this->_plugins_script_admin[] = array(
            'folder' => 'tagmanager',
            'name' => 'tagmanager'
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
            'folder' => 'pages',
            'name' => 'admin-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->load->helper('language');
            $this->lang->load('form_validation', 'vietnamese');

            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('title', 'Tên bài viết', 'trim|required|xss_clean');
            $this->form_validation->set_rules('alias', 'Liên kết tĩnh', 'trim|required|xss_clean');

            if ($this->form_validation->run($this)) {
                if ($this->input->post('id')) {//update
                    $err = FALSE;
                    $id = $this->input->post('id');
                    if (!$this->admin_update($id)) {
                        $err = TRUE;
                    } else {
                        //update postmeta
                        $postmeta = array(
                            'homeimgalt' => $this->input->post('homeimgalt')
                        );
                        foreach ($postmeta as $key => $value) {
                            $post_id = $id;
                            $meta_key = $key;
                            $meta_value = $value;
                            modules::run('pages/postmeta/update', $post_id, $meta_key, $meta_value);
                        }

                        //upload and update postmeta homeimgfile
                        $this->_upload_images($id, 'homeimg');
                    }

                    if ($err === FALSE) {
                        $this->output->clearCache();
                        $notify_type = 'success';
                        $notify_content = 'Cập nhật trang tĩnh thành công!';
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
                    } else {
                        //add postmeta
                        $postmeta = array(
                            'homeimgfile' => '',
                            'homeimgalt' => $this->input->post('homeimgalt')
                        );
                        foreach ($postmeta as $key => $value) {
                            $args = array(
                                'post_id' => $insert_id,
                                'meta_key' => $key,
                                'meta_value' => $value
                            );
                            modules::run('pages/postmeta/add', $args);
                        }

                        //upload and update postmeta homeimgfile
                        $this->_upload_images($insert_id, 'homeimg');
                    }

                    if ($err === FALSE) {
                        $this->output->clearCache();
                        $notify_type = 'success';
                        $notify_content = 'Trang tĩnh đã được thêm!';
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

        $this->load->library('ckeditor', array('instanceName' => 'CKEDITOR1', 'basePath' => base_url() . "ckeditor/", 'outPut' => true));
        $title = 'Thêm trang tĩnh - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];

        $id = ($this->uri->segment(4) == '') ? 0 : (int) $this->uri->segment(4);
        if ($id != 0) {
            $row = $this->M_pages->get($id);
            if (!empty($row)) {
                //parse
                if (!empty($post) && !empty($this->_required_field)) {
                    //if isset post and not empty array required_field: change value database by value post in input[name=$value]
                    foreach ($this->_required_field as $value) {
                        $row[$value] = $this->input->post($value);
                    }
                }

                $this->_data['row'] = $row;
                $title = 'Cập nhật trang tĩnh - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
            } else {
                //page id not exist
                redirect(get_admin_url($this->_module_slug));
            }
        }
        $this->_data['title'] = $title;
        $this->_data['main_content'] = 'pages/admin/view_page_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    private function _upload_images($id, $input_name) {
        $post_id = $id;
        $meta_key = 'homeimgfile';
        $current_photo = modules::run('pages/postmeta/get', $post_id, $meta_key);
        $info = modules::run('files/index', $input_name, $this->_path);
        if (isset($info['uploads'])) {
            $upload_images = $info['uploads']; // thông tin ảnh upload
            if ($_FILES[$input_name]['size'] != 0) {
                foreach ($upload_images as $value) {
                    $meta_value = $value['file_name']; //tên ảnh
                    modules::run('pages/postmeta/update', $post_id, $meta_key, $meta_value);
                }
                @unlink(FCPATH . $this->_path . $current_photo['meta_value']);
            }
        }
    }

    private function _delete_images($post_id = 0) {
        if ($post_id == 0) {
            show_404();
        }
        $meta_key = 'homeimgfile';
        $current_photo = modules::run('pages/postmeta/get', $post_id, $meta_key);
        @unlink(FCPATH . $this->_path . $current_photo['meta_value']);
    }

}

/* End of file pages.php */
/* Location: ./application/modules/pages/controllers/pages.php */

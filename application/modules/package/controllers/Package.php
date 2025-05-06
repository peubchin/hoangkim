<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Package extends Layout {

	private $_module_slug = 'package';

	function __construct() {
		parent::__construct();
		$this->_data['module_slug'] = $this->_module_slug;
		$this->_data['breadcrumbs_module_name'] = 'Gói sản phẩm';
	}

	function ajax_get() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}

		$message = array();
		$message['status'] = 'warning';
		$message['content'] = null;
		$message['message'] = 'Kiểm tra thông tin';

		$post = $this->input->post();
		if (!empty($post)) {
			$id = $this->input->post('id');
			$data = $this->get($id);
			if (is_array($data) && !empty($data)) {
				$message['status'] = 'success';
				$message['content'] = $data;
				$message['message'] = 'Tải dữ liệu thành công!';
			} else {
				$message['status'] = 'danger';
				$message['content'] = null;
				$message['message'] = 'Có lỗi xảy ra! Vui lòng kiểm tra lại!';
			}
		}
		echo json_encode($message);
		exit();
	}

	function default_args() {
		$order_by = array(
			'order' => 'DESC',
			'name' => 'ASC',
		);
		$args = array();
		$args['order_by'] = $order_by;

		return $args;
	}

	function counts($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}
		return $this->M_package->counts($args);
	}

	function gets($options = array()) {
		$default_args = $this->default_args();

		if (is_array($options) && !empty($options)) {
			$args = array_merge($default_args, $options);
		} else {
			$args = $default_args;
		}

		return $this->M_package->gets($args);
	}

	function get($id) {
		return $this->M_package->get($id);
	}

	function get_max_order() {
		$args = $this->default_args();
		$order_by = array(
			'order' => 'DESC',
		);
		$args['order_by'] = $order_by;
		$rows = $this->M_package->gets($args);
		$max_order = isset($rows[0]['order']) ? $rows[0]['order'] : 0;

		return (int) $max_order;
	}

	function re_order() {
		$args = $this->default_args();
		$order_by = array(
			'order' => 'ASC',
		);
		$args['order_by'] = $order_by;
		$rows = $this->gets($args);
		if (is_array($rows) && !empty($rows)) {
			$i = 0;
			foreach ($rows as $value) {
				$i++;
				$data = array(
					'order' => $i,
				);
				$this->M_package->update($value['id'], $data);
			}
		}
	}

	function admin_index() {
		$this->_initialize_admin();
		$this->redirect_admin();
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap3-dialog/css',
			'name' => 'bootstrap-dialog',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap3-dialog/js',
			'name' => 'bootstrap-dialog',
		);
		$this->set_plugins_admin();

		$this->_modules_script[] = array(
			'folder' => 'package',
			'name' => 'admin-items',
		);
		$this->set_modules();

		$get = $this->input->get();
		$this->_data['get'] = $get;

		$args = $this->default_args();
		$args['deleted'] = 0;

		if (isset($get['q']) && trim($get['q']) != '') {
			$args['name'] = $get['q'];
		}

		$total = $this->counts($args);
		$perpage = isset($get['per_page']) ? $get['per_page'] : $this->config->item('per_page');
		$segment = 3;

		$this->load->library('pagination');
		$config['total_rows'] = $total;
		$config['per_page'] = $perpage;
		$config['full_tag_open'] = '<ul class="pagination no-margin pull-right">';
		$config['full_tag_close'] = '</ul>';

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

		$this->_data['rows'] = $this->M_package->gets($args, $perpage, $offset);
		$this->_data['pagination'] = $pagination;

		$this->_data['title'] = 'Danh sách gói sản phẩm - ' . $this->_data['title'];
		$this->_data['main_content'] = 'package/admin/view_page_index';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_content() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation',
			'name' => 'jquery.validate',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery-validation/localization',
			'name' => 'messages_vi',
		);

		$this->_plugins_script_admin[] = array(
            'folder' => 'jquery-mask',
            'name' => 'jquery.mask'
        );

		$this->set_plugins_admin();

		$this->_modules_script[] = array(
			'folder' => 'package',
			'name' => 'admin-content-validate',
		);
		$this->set_modules();

		$post = $this->input->post();
		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('name', 'Nhập tên gói sản phẩm', 'trim|required|xss_clean');

			if ($this->form_validation->run($this)) {
				if ($this->input->post('id')) {
					//update
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
				} else {
					//add
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

		$segment = 4;
		$id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
		if ($id != 0) {
			$row = $this->get($id);
			$this->_data['row'] = $row;
			$title = 'Cập nhật thông tin - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
		}

		$this->_data['title'] = $title;
		$this->_data['main_content'] = 'package/admin/view_page_content';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_main() {
		$this->_initialize_admin();
		$this->redirect_admin();
		$post = $this->input->post();
		if (!empty($post)) {
			$action = $this->input->post('action');
			if ($action == 'update') {
				$this->_message_success = 'Đã cập nhật gói sản phẩm!';
				$this->_message_warning = 'Không có gói sản phẩm nào để cập nhật!';
				$ids = $this->input->post('ids');
				$orders = $this->input->post('order');
				$count = count($orders);
				if (!empty($ids) && !empty($orders)) {
					for ($i = 0; $i < $count; $i++) {
						$data = array(
							'order' => $orders[$i],
						);
						$id = $ids[$i];
						if ($this->M_package->update($id, $data)) {
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
			} elseif ($action == 'delete') {
				$this->_message_success = 'Đã xóa các gói sản phẩm được chọn!';
				$this->_message_warning = 'Bạn chưa chọn gói sản phẩm nào!';
				$ids = $this->input->post('idcheck');

				if (is_array($ids) && !empty($ids)) {
					foreach ($ids as $id) {
						$row = $this->get($id);
						if (!empty($row) && $this->M_package->delete($id)) {
							$notify_type = 'success';
							$notify_content = $this->_message_success;
						} else {
							$notify_type = 'danger';
							$notify_content = $this->_message_danger;
						}
					}
					$this->re_order();
				} else {
					$notify_type = 'warning';
					$notify_content = $this->_message_warning;
				}
				$this->set_notify_admin($notify_type, $notify_content);
				redirect(get_admin_url($this->_module_slug));
			} elseif ($action == 'content') {
				redirect(get_admin_url($this->_module_slug . '/content'));
			}
		} else {
			redirect(get_admin_url($this->_module_slug));
		}
	}

	function admin_add() {
		$data = array(
			'name' => $this->input->post('name'),
			'price' => filter_var($this->input->post('price'), FILTER_SANITIZE_NUMBER_FLOAT),
			'F0' => $this->input->post('F0'),
			'F1' => $this->input->post('F1'),
			'F2' => $this->input->post('F2'),
			'order' => $this->get_max_order() + 1,
			'status' => 1,
			'created' => time(),
			'modified' => 0,
		);

		return $this->M_package->add($data);
	}

	function admin_update($id) {
		$data = array(
			'name' => $this->input->post('name'),
			'price' => filter_var($this->input->post('price'), FILTER_SANITIZE_NUMBER_FLOAT),
			'F0' => $this->input->post('F0'),
			'F1' => $this->input->post('F1'),
			'F2' => $this->input->post('F2'),
			'modified' => time(),
		);
		return $this->M_package->update($id, $data);
	}

	function admin_delete() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$this->_message_success = 'Đã xóa thông tin!';
		$this->_message_warning = 'Thông tin này không tồn tại!';
		$id = $this->input->get('id');
		if ($id != 0) {
			if ($this->M_package->delete($id)) {
				$this->re_order();
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

}

/* End of file Package.php */
/* Location: ./application/modules/package/controllers/Package.php */

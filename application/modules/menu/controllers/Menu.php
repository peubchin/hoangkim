<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Menu extends Layout {

    private $_module_slug = 'menu';
    private $_menu_path = '';

    function __construct() {
        parent::__construct();
        $this->_data['breadcrumbs_module_name'] = 'Menu';
        $this->_data['module_slug'] = $this->_module_slug;
        $this->_menu_path = get_module_path('menu');
    }

    function default_args() {
        $order_by = array(
            'position' => 'DESC',
            'parent' => 'ASC',
            'order' => 'ASC',
        );
        $args = array();
        $args['order_by'] = $order_by;

        return $args;
    }

    function counts($args) {
        $this->load->model('menu/m_menu', 'M_menu');
        return $this->M_menu->counts($args);
    }

    function get($id) {
        $this->load->model('menu/m_menu', 'M_menu');
        return $this->M_menu->get($id);
    }

    function load_menu_position($position = 'Top') {
        $this->_data['menu_list'] = $this->get_menu_list($position);
        $this->_data['menu_input'] = $this->get_input($position);
    }

    function admin_main() {
        $this->_initialize_admin();
        $this->redirect_admin();
		
        $post = $this->input->post();
        if (!empty($post)) {
            $action = $this->input->post('action');
            if ($action == 'update') {
                $this->_message_success = 'Đã cập nhật menu!';
                $this->_message_warning = 'Không có menu nào để cập nhật!';
                $ids = $this->input->post('ids');
                $orders = $this->input->post('order');
                $count = count($orders);
                if (!empty($ids) && !empty($orders)) {
                    for ($i = 0; $i < $count; $i++) {
                        $data = array(
                            'order' => $orders[$i]
                        );
                        $id = $ids[$i];
                        if ($this->M_menu->update($id, $data)) {
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
            'folder' => 'menu',
            'name' => 'admin-items'
        );
        $this->set_modules();


        $get = $this->input->get();
        $this->_data['get'] = $get;
        $filter = $get;
        $args = $this->default_args();

        $data_rows = $this->M_menu->gets($args);

        $this->_data['menu_list'] = $this->get_menu_list_all($data_rows);
        $this->_data['menu_input'] = $this->get_input_all($data_rows);
        $this->_data['rows'] = $data_rows;
        $this->_data['menu_type_rows'] = modules::run('menu/menu_type/get_data_all');

        $this->_data['breadcrumbs_module_func'] = '';
        $this->_data['title'] = 'Quản lý menu - Menu - ' . $this->_data['title'];
        $this->_data['main_content'] = 'menu/admin/view_page_index';
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
            'folder' => 'menu',
            'name' => 'admin-content-validate'
        );
        $this->set_modules();

        $post = $this->input->post();
        if (!empty($post)) {
            $this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
            $this->form_validation->set_rules('name', 'Tên menu', 'trim|required');

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
                redirect(get_admin_url('menu'));
            } else {
                $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
                if ($id > 0) {
                    $cat = $this->M_menu->get_news_cat($id);
                }
                $this->_data['row'] = (isset($cat[0]) ? $cat[0] : null);
            }
        } else {//load
            $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
            if ($id > 0) {
                $row = $this->get_data_in_id($id);
                if (isset($row['menu_type'])) {
                    $menu_type = modules::run('menu/menu_type/get', $row['menu_type']);
                    $this->_data['type'] = $menu_type['type'];
                    $this->_data['values_selected'] = $row['values'];
                    $this->load_content_menu_type();
                }
            }
            $this->_data['row'] = (isset($row) ? $row : null);
        }
        $this->_data['menu_type_rows'] = modules::run('menu/menu_type/get_data_all');
		$main = 'Main';
		$this->_data['menu_main_list'] = modules::run('menu/get_menu_list', $main);
		$this->_data['menu_main_data'] = modules::run('menu/get_data', $main);
		$this->_data['menu_main_input'] = modules::run('menu/get_input', $main);

        if ($id > 0) {
            $this->_data['breadcrumbs_module_func'] = 'Cập nhật';
        } else {
            $this->_data['breadcrumbs_module_func'] = 'Thêm mới';
        }
        $this->_data['title'] = 'Quản lý menu - Menu - ' . $this->_data['title'];
        $this->_data['main_content'] = 'menu/admin/view_page_content';
        $this->load->view('layout/admin/view_layout', $this->_data);
    }

    function admin_get_menu_position() {
        $position = $this->input->post('position');
        $this->_data['position'] = $position;
        $this->load_menu_position($position);

        $this->load->view('menu/admin/view_menu_position', $this->_data);
    }

    function admin_get_menu_type() {
        $id = $this->input->post('menu_type');
        $menu_type = modules::run('menu/menu_type/get', $id);
        $this->_data['type'] = $menu_type['type'];

        $this->load_content_menu_type();

        $this->load->view('menu/admin/view_menu_type', $this->_data);
    }

    function load_content_menu_type() {
        //posts cat
        $this->_data['data_cat_news'] = modules::run('posts/postcat/get_all_data');

        //posts
        $this->_data['data_rows_news'] = modules::run('posts/gets');

        //pages
        $this->_data['data_rows_pages'] = modules::run('pages/gets');
		
		//shops cat		
		$shops_cat = modules::run('shops/cat/gets');
		$this->_data['shops_cat_list'] = $shops_cat['data_list']; //mang chua quan he cha con
		$this->_data['shops_cat_data'] = $shops_cat['data_input']; // mang chua du lieu tat ca cat (có link)
		$this->_data['shops_cat_input'] = $shops_cat['data_input']; // mang chua du lieu tat ca cat (không link chi co gia tri va ten de dung cho input)
		
		//shops rows
		$this->_data['data_rows_shops'] = modules::run('shops/rows/get_all_data');
    }

    function get_data_in_id($id = 0) {
        return $this->M_menu->get_data_in_id($id);
    }

    function get_max_order($parent = 0, $position) {
        $data = $this->M_menu->get_max_order($parent, $position);
        return isset($data['order']) ? (int) $data['order'] : 0;
    }
	
	function gets($position, $parent = 0) {
		$data = $this->M_menu->get_child_position_menu_top($parent, $position);
		return $this->parse_data($data);
	}

    function get_current_parent($id) {
        $result = $this->get_data_in_id($id);
        return isset($result['parent']) ? (int) $result['parent'] : 0;
    }

    function count_rows_in_id($id) {
        $result = $this->M_menu->get_news_rows_in_id($id);
        return !empty($result) ? count($result) : 0;
    }

    function admin_add() {
        $parent = $this->input->post('parent');

        $values = '';
        $data_values = $this->input->post('values');
        if (is_array($data_values)) {
            $values = implode(',', $data_values);
            if (isset($data_values[0]) && $data_values[0] == 0) {
                $values = 0;
            }
        } elseif (is_numeric($data_values)) {
            $values = $data_values;
        } elseif (trim($data_values) != '') {
            $values = $data_values;
        }

        $position = $this->input->post('position');

        $data = array(
            'parent' => $parent,
            'menu_type' => $this->input->post('menu_type'),
            'name' => $this->input->post('name'),
            'values' => $values,
            'position' => $position,
            'order' => $this->get_max_order($parent, $position) + 1,
            'status' => 1
        );

        return $this->M_menu->add($data);
    }

    function admin_update($id) {
        $is_update_order = FALSE;
        $parent = $this->input->post('parent');
        $current = $this->get($id);

        $values = '';
        $data_values = $this->input->post('values');
        if (is_array($data_values)) {
            $values = implode(',', $data_values);
            if (isset($data_values[0]) && $data_values[0] == 0) {
                $values = 0;
            }
        } elseif (is_numeric($data_values)) {
            $values = $data_values;
        } elseif (trim($data_values) != '') {
            $values = $data_values;
        }

        $position = $this->input->post('position');

        $data = array(
            'parent' => $parent,
            'menu_type' => $this->input->post('menu_type'),
            'name' => $this->input->post('name'),
            'values' => $values,
            'position' => $position,
            'status' => 1
        );



        if ($position != $current['position']) {
            $data['order'] = $this->get_max_order($parent, $position) + 1; //gia tri sap xep lon nhat cua menu mới
            $is_update_order = TRUE;
        } elseif ($parent != $current['parent']) {
            $data['order'] = $this->get_max_order($parent, $position) + 1; //gia tri sap xep lon nhat cua menu mới
            $is_update_order = TRUE;
        }

        if ($this->M_menu->update($id, $data)) {
            if ($is_update_order) {
                /* sap xep lại menu truoc khi luu */
                $child = $this->M_menu->get_child_position($current['parent'], $current['position']);
                if (!empty($child)) {
                    $i = 0;
                    foreach ($child as $value) {
                        $i++;
                        $data_child = array(
                            'order' => $i
                        );
                        $this->M_menu->update($value['id'], $data_child);
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
		
        $id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4);
        if ($id != 0) {
            $data_in_id = $this->get_data_in_id($id);
            $has_child = $this->M_menu->get_child_position($data_in_id['id'], $data_in_id['position']);

            if (empty($has_child)) {
                $current_photo = $data_in_id;
                if ($this->M_menu->delete($id)) {
                    @unlink(FCPATH . $this->_menu_path . $current_photo['image']);
                    /* sap xep lai menu cha */
                    $child = $this->M_menu->get_child_position($data_in_id['parent'], $data_in_id['position']);
                    if (!empty($child)) {
                        $i = 0;
                        foreach ($child as $value) {
                            $i++;
                            $data_child = array(
                                'order' => $i
                            );
                            $this->M_menu->update($value['id'], $data_child);
                        }
                    }

                    $notify_type = 'success';
                    $notify_content = 'Xóa menu thành công!';
                } else {
                    $notify_type = 'danger';
                    $notify_content = 'Menu chưa xóa được!';
                }
            } else {
                $notify_type = 'danger';
                $notify_content = 'Menu này có chứa menu con nên chưa xóa được! Hãy xóa các menu con và thực hiện lại!';
            }
        } else {
            $notify_type = 'warning';
            $notify_content = 'Menu không tồn tại!';
        }
        $this->set_notify_admin($notify_type, $notify_content);
        redirect(get_admin_url($this->_module_slug));
    }

    function get_child($parent) {
        return $this->M_menu->get_child($parent);
    }

    function get_input($position = 'Top') {
        $categories = array();
        $categories[0] = array(
            'lname' => 'Root',
            'lurl' => '#',
            'position' => '#',
            'order' => '#',
            'menu_type_id' => '#',
            'menu_type_name' => '#'
        );
        $cat = $this->M_menu->get_data_position($position);

        foreach ($cat as $value) {
            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => $value['id'],
                'position' => $value['position'],
                'order' => $value['order'],
                'menu_type_id' => $value['menu_type_id'],
                'menu_type_name' => $value['menu_type_name']
            );
        }
        return $categories;
    }

    /* show menu */
    function get_data($position = 'Top') {
		$categories = array();
		$categories[0] = array(
			'lname' => 'Root',
			'lurl' => '#',
			'limg' => '#',
		);
		$datas = $this->M_menu->get_data_position($position);

		foreach ($datas as $value) {
			$id_menutype = $value['menu_type'];
			$type = $value['type'];
			$ctrl = modules::run('menu/menu_type/get_values_in_id', $id_menutype);
			$url = '';
			$ldata = '';
			$values = $value['values'];

			if ($type == 'index') {
				$url = base_url();
			}
			//trang tinh
			elseif ($type == 'pages') {
				$alias_page = modules::run('pages/get', $values);
				if (!empty($alias_page)) {
					$url = site_url($ctrl . '/' . $alias_page['alias'] . '-' . $values);
				} else {
					$url = base_url();
				}
			}
			//danh muc bai viet
			elseif ($type == 'post_categories') {
				if ($values == 0 || $values == '') {
					$url = site_url();
				} else {
					$data = modules::run('posts/postcat/get_data_in_id', $values);
					if (!empty($data)) {
						$url = site_url($ctrl . "/" . $data['alias']);
					} else {
						$url = base_url();
					}
				}
			}
			//chi tiet bai viet
			elseif ($type == 'post') {
				$data = modules::run('posts/get', $values);
				if (!empty($data)) {
					$url = site_url($ctrl . "/" . $data['categories']['alias'] . '/' . $data['alias'] . "-" . $values);
				} else {
					$url = base_url();
				}
			}
			//danh mục sản phẩm
			elseif ($type == 'categories') {
				if ($values == 0 || $values == '') {
					$url = base_url();
				} else {
					$data = modules::run('shops/cat/get', $values);
					if (!empty($data)) {
						$url = site_url($ctrl . "/" . $data['alias']);
					} else {
						$url = base_url();
					}
				}
			}
			//chi tiet san pham
			elseif ($type == 'product') {
				$data = modules::run('shops/rows/get', $values);
				if (!empty($data)) {
					$url = site_url($ctrl . "/" . $data['cat_alias'] . '/' . $data['alias'] . "-" . $values);
				} else {
					$url = base_url();
				}
			}			
			elseif ($type == 'posts' || $type == 'contact' || $type == 'products') {
				$url = site_url($ctrl);
			}
			elseif ($type == 'site_map') {
				$url = site_url('site-map');
			}
			elseif ($type == 'lable') {
				$url = '';
			}
			elseif ($type == 'link') {
				$url = $values;
			}

			$categories[$value['id']] = array(
				'lname' => $value['name'],
				'lurl' => $url,
				'limg' => ($value['image'] != '') ? base_url(get_module_path('menu') . $value['image']) : '',
			);
		}

		return $categories;
	}

	function parse_data($datas) {
		$categories = array();
		if (is_array($datas) && !empty($datas)) {
			foreach ($datas as $value) {
				$menu_type = $value['menu_type'];
				$type = $value['type'];
				$ctrl = modules::run('menu/menu_type/get_values_in_id', $menu_type);
				$url = '';
				$ldata = '';
				$values = $value['values'];

				if ($type == 'index') {
					$url = base_url();
				}
				//trang tinh
				elseif ($type == 'pages') {
					$alias_page = modules::run('pages/get', $values);
					if (!empty($alias_page)) {
						$url = site_url($ctrl . '/' . $alias_page['alias'] . '-' . $values);
					} else {
						$url = base_url();
					}
				}
				//danh muc bai viet
				elseif ($type == 'post_categories') {
					if ($values == 0 || $values == '') {
						$url = site_url();
					} else {
						$data = modules::run('posts/postcat/get_data_in_id', $values);
						if (!empty($data)) {
							$url = site_url($ctrl . "/" . $data['alias']);
						} else {
							$url = base_url();
						}
					}
				}
				//chi tiet bai viet
				elseif ($type == 'post') {
					$data = modules::run('posts/get', $values);
					if (!empty($data)) {
						$url = site_url($ctrl . "/" . $data['categories']['alias'] . '/' . $data['alias'] . "-" . $values);
					} else {
						$url = base_url();
					}
				}
				//danh muc sản phẩm
				elseif ($type == 'categories') {
					if ($values == 0 || $values == '') {
						$url = base_url();
					} else {
						$data = modules::run('shops/cat/get', $values);
						if (!empty($data)) {
							$url = site_url($ctrl . "/" . $data['alias']);
						} else {
							$url = base_url();
						}
					}
				}
				//chi tiet san pham
				elseif ($type == 'product') {
					$data = modules::run('shops/rows/get', $values);
					if (!empty($data)) {
						$url = site_url($ctrl . "/" . $data['cat_alias'] . '/' . $data['alias'] . "-" . $values);
					} else {
						$url = base_url();
					}
				}
				elseif ($type == 'posts' || $type == 'contact' || $type == 'products') {
					$url = site_url($ctrl);
				}
				elseif ($type == 'lable') {
					$url = '';
				}
				elseif ($type == 'link') {
					$url = $values;
				}

				$categories[$value['id']] = array(
					'lname' => $value['name'],
					'lurl' => $url,
					'limg' => ($value['image'] != '') ? base_url(get_module_path('menu') . $value['image']) : '',
				);
			}
		}

		return $categories;
	}

    function get_menu_list($position = 'Top') {
        $key_category_list = array();
        $root_category_list = array();
        $category_list = array();
        $cat = $this->M_menu->get_data_position($position);
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

    function get_data_all($cat = null) {
        $this->load->module('menu/menu_type');

        $categories = array();
        $categories[0] = array(
            'lname' => 'Root',
            'lurl' => '#',
            'limg' => '#'
        );

        foreach ($cat as $value) {
            $id_menutype = $value['menu_type'];
            $ctrl = $this->menu_type->get_values_in_id($id_menutype);
            $url = '';
            $values = $value['values'];

            if ($id_menutype == 1) {
                $url = base_url();
            } elseif ($id_menutype == 2) {
                if ($values == 0) {
                    $url = base_url($ctrl);
                } else {
                    $alias_shops_cat = modules::run('shops/cat/get_alias_in_id', $values);
                    $url = base_url($ctrl . "/" . $alias_shops_cat . $this->_data['url_suffix']);
                }
            }
            //chi tiet san pham
            elseif ($id_menutype == 3) {
                $alias_shops_rows = modules::run('shops/rows/get_alias_in_id', $values);
                $url = base_url($ctrl . "/" . $alias_shops_rows . "-" . $values) . $this->_data['url_suffix'];
            }
            //trang tinh
            elseif ($id_menutype == 4) {
                $alias_page = modules::run('pages/get_alias_in_id', $values);
                $url = base_url($ctrl . '/' . $alias_page . '-' . $values) . $this->_data['url_suffix'];
            }
            //danh muc bai viet
            elseif ($id_menutype == 5) {
                if ($values == 0) {
                    $url = base_url($ctrl);
                } else {
                    $alias_news_cat = modules::run('news/news_cat/get_alias_in_id', $values);
                    $url = base_url($ctrl . "/" . $alias_news_cat) . $this->_data['url_suffix'];
                }
            }
            //chi tiet bai viet
            elseif ($id_menutype == 6) {
                $post_rows = modules::run('posts/get', $values);
                $url = base_url($ctrl . "/" . $post_rows['alias'] . "-" . $values) . $this->_data['url_suffix'];
            }
            //lien ket ngoai
            elseif ($id_menutype == 7) {
                $url = $values;
            }
            //danh muc tin tuc
            elseif ($id_menutype == 8) {
                if ($values == 0) {
                    $url = base_url($ctrl);
                } else {
                    $alias_news_cat = modules::run('news/news_cat/get_alias_in_id', $values);
                    $url = base_url($ctrl . "/" . $alias_news_cat) . $this->_data['url_suffix'];
                }
            }

            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => $url,
                'limg' => base_url($value['images'])
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
            'lname' => 'Root',
            'lurl' => '#',
            'position' => '#',
            'order' => '#',
            'menu_type_id' => '#',
            'menu_type_name' => '#'
        );

        foreach ($cat as $value) {
            $categories[$value['id']] = array(
                'lname' => $value['name'],
                'lurl' => $value['id'],
                'position' => $value['position'],
                'order' => $value['order'],
                'menu_type_id' => $value['menu_type_id'],
                'menu_type_name' => $value['menu_type_name']
            );
        }
        return $categories;
    }

    private function _upload_images($id, $input_name) {
        $current_photo = $this->get($id);
        $info = modules::run('files/index', $input_name, $this->_menu_path);
        if (isset($info['uploads'])) {
            $upload_images = $info['uploads']; // thông tin ảnh upload
            if ($_FILES[$input_name]['size'] != 0) {
                foreach ($upload_images as $value) {
                    $file_name = $value['file_name']; //tên ảnh
                    $data_images = array(
                        'image' => $file_name
                    );
                    $this->M_menu->update($id, $data_images);
                }
                @unlink(FCPATH . $this->_menu_path . $current_photo['image']);
            }
        }
    }

}

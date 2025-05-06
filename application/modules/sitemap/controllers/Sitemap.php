<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';
class Sitemap extends Layout {

	private $_module_slug = 'sitemap';

	function __construct() {
		parent::__construct();
		$this->_data['module_slug'] = $this->_module_slug;
		$this->_data['breadcrumbs_module_name'] = 'Sitemap';
	}

	function admin_index() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$action = 'Tạo';
		$sitemap_file = 'sitemap.xml';
		$sitemap_file_exists = validate_file_exists($sitemap_file);
		if ($sitemap_file_exists) {
			$action = 'Cập nhật';
		}

		$post = $this->input->post();
		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('allowed_sitemap', 'Đồng ý ' . $action . ' sitemap', 'required');

			if ($this->form_validation->run($this)) {
				if ($this->input->post('allowed_sitemap')) {
					if ($sitemap_file_exists) {
						@unlink(FCPATH . $sitemap_file);
					}

					$this->load->library('sitemaps');

					$items = array();
					$items[] = array(
						"loc" => substr(base_url(), 0, -1),
						"lastmod" => date("c", time()),
						"changefreq" => "hourly",
						"priority" => "0.8",
					);

					$items[] = array(
						"loc" => site_url('lien-he'),
						"lastmod" => date("c", time()),
						"changefreq" => "hourly",
						"priority" => "0.8",
					);

					$items[] = array(
						"loc" => site_url($this->config->item('url_shops_rows')),
						"lastmod" => date("c", time()),
						"changefreq" => "hourly",
						"priority" => "0.8",
					);

					$items[] = array(
						"loc" => site_url($this->config->item('url_posts_rows')),
						"lastmod" => date("c", time()),
						"changefreq" => "hourly",
						"priority" => "0.8",
					);

					$pages = modules::run('pages/gets');
					if (is_array($pages) && !empty($pages)) {
						foreach ($pages as $value) {
							$lastmod = $value['edittime'] > 0 ? $value['edittime'] : $value['addtime'];
							$items[] = array(
								"loc" => site_url($value['alias'] . '-' . $value['id']),
								"lastmod" => date("c", $lastmod),
								"changefreq" => "hourly",
								"priority" => "0.8",
							);
						}
					}

                    $posts_cat = modules::run('posts/postcat/gets');
                    if (is_array($posts_cat) && !empty($posts_cat)) {
                        foreach ($posts_cat as $value) {
                            $lastmod = $value['edit_time'] > 0 ? $value['edit_time'] : $value['add_time'];
                            $items[] = array(
                                "loc" => site_url($this->config->item('url_posts_cat') . '/' . $value['alias']),
                                "lastmod" => date("c", $lastmod),
                                "changefreq" => "hourly",
                                "priority" => "0.8",
                            );
                        }
                    }

					$posts = modules::run('posts/gets');
					if (is_array($posts) && !empty($posts)) {
						foreach ($posts as $value) {
							$value['categories'] = modules::run('posts/postcat/get_data_in_id', $value['post_cat_id']);
							$lastmod = $value['edittime'] > 0 ? $value['edittime'] : $value['addtime'];
							$items[] = array(
								"loc" => site_url($value['categories']['alias'] . '/' . $value['alias'] . '-' . $value['id']),
								"lastmod" => date("c", $lastmod),
								"changefreq" => "hourly",
								"priority" => "0.8",
							);
						}
					}

                    $shops_cat = modules::run('shops/cat/gets', true);
                    if (is_array($shops_cat) && !empty($shops_cat)) {
                        foreach ($shops_cat as $value) {
                            $lastmod = $value['edit_time'] > 0 ? $value['edit_time'] : $value['add_time'];
                            $items[] = array(
                                "loc" => site_url($this->config->item('url_shops_cat') . '/' . $value['alias']),
                                "lastmod" => date("c", $lastmod),
                                "changefreq" => "hourly",
                                "priority" => "0.8",
                            );
                        }
                    }

					$shops = modules::run('shops/rows/gets');
					if (is_array($shops) && !empty($shops)) {
						foreach ($shops as $value) {
							$lastmod = $value['edittime'] > 0 ? $value['edittime'] : $value['addtime'];
							$items[] = array(
								"loc" => site_url($this->config->item('url_shops_rows') . '/' . $value['cat_alias'] . '/' . $value['alias'] . '-' . $value['id']),
								"lastmod" => date("c", $lastmod),
								"changefreq" => "hourly",
								"priority" => "0.8",
							);
						}
					}

					if (is_array($items) && !empty($items)) {
						foreach ($items as $item) {
							$this->sitemaps->add_item($item);
						}
					}
					// file name may change due to compression
					$file_name = $this->sitemaps->build($sitemap_file);
					$reponses = $this->sitemaps->ping(site_url($file_name));

					$notify_type = 'success';
					$notify_content = $action . ' sitemap thành công!';
					$this->set_notify_admin($notify_type, $notify_content);
					redirect(get_admin_url($this->_module_slug));
				}
			}
		}

		$this->_data['sitemap_file'] = $sitemap_file;
		$this->_data['sitemap_file_exists'] = $sitemap_file_exists;
		$this->_data['action'] = $action;
		$this->_data['title'] = $action . ' sitemap - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
		$this->_data['main_content'] = 'sitemap/admin/view_page_index';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}
}
/* End of file sitemap.php */
/* Location: ./application/modules/sitemap/controllers/sitemap.php */
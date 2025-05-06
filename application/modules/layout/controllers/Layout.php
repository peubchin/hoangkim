<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}

class Layout extends MX_Controller {
	public $ROLE = 0;
	protected $_data = array();
	protected $_status = '';
	protected $_message = '';
	protected $_message_success = '';
	protected $_message_warning = '';
	protected $_message_danger = 'Rất tiếc! Có lỗi kỹ thuật!';
	protected $_message_banned = 'Không có quyền truy cập vào khu vực này!';
	protected $_breadcrumbs = array();
	protected $_breadcrumbs_admin = array();
	protected $_add_css = array();
	protected $_add_js = array();
	protected $_plugins_css = array();
	protected $_plugins_script = array();
	protected $_plugins_css_admin = array();
	protected $_plugins_script_admin = array();
	protected $_modules_css = array();
	protected $_modules_script = array();

	function __construct() {
		parent::__construct();
	}

	public function _initialize_global($configs) {
		$this->config->set_item('url_posts_cat', modules::run('menu/menu_type/get_values_type', 'post_categories'));
		$this->config->set_item('url_posts_rows', modules::run('menu/menu_type/get_values_type', 'posts'));
		$this->config->set_item('url_contact', modules::run('menu/menu_type/get_values_type', 'contact'));
		$this->config->set_item('url_shops_cat', modules::run('menu/menu_type/get_values_type', 'categories'));
		$this->config->set_item('url_shops_rows', modules::run('menu/menu_type/get_values_type', 'products'));

		$parse = parse_url(base_url());
		$this->_data['host'] = $parse['host'];

		$this->_data['site_name'] = $configs['site_name'];
		$this->_data['title'] = $configs['site_name'];
		$this->_data['title_seo'] = $configs['title_seo'];
		$this->_data['other_seo'] = $configs['other_seo'];
		$this->_data['h1_seo'] = $configs['h1_seo'];
		$this->_data['h2_seo'] = $configs['h2_seo'];
		$this->_data['description'] = $configs['site_description'];
		$this->_data['keywords'] = $configs['site_keywords'];
		$this->_data['site_icon'] = $configs['site_icon'];
		$this->_data['email'] = $configs['site_email'];

		$this->_initialize_user();
	}

	public function _initialize_user() {
		// $this->_data['logged_in'] = FALSE;
		// if ($this->session->has_userdata('logged_in')) {
		// 	$this->_data['logged_in'] = TRUE;
		// 	$session_data = $this->session->userdata('logged_in');
		// 	$this->_data['userid'] = $session_data['userid'];
		// 	$this->_data['username'] = $session_data['username'];
		// 	$this->_data['full_name'] = $session_data['full_name'];
		// 	$this->_data['photo'] = $session_data['photo'];
		// 	$this->_data['regdate'] = isset($session_data['regdate']) ? $session_data['regdate'] : '';
		// }

		$this->_data['logged_in'] = FALSE;
		if ($this->session->has_userdata('logged_in')) {
			$this->_data['logged_in'] = TRUE;
			$session_data = $this->session->userdata('logged_in');
			$this->_data['userid'] = $session_data['userid'];
			$this->_data['username'] = $session_data['username'];
			$this->_data['full_name'] = $session_data['full_name'];
			$this->_data['photo'] = $session_data['photo'];
			$this->_data['regdate'] = isset($session_data['regdate']) ? $session_data['regdate'] : '';
			$this->_data['group_id'] = $session_data['group_id'];
			$this->_data['role'] = isset($session_data['role']) ? $session_data['role'] : '';
			$this->_data['is_wholesale'] = isset($session_data['is_wholesale']) ? filter_var($session_data['is_wholesale'], FILTER_VALIDATE_BOOLEAN) : FALSE;
			$this->ROLE = $session_data['group_id'];
		}
		if ($this->session->has_userdata('logged_in_by')) {
			$this->_data['logged_in'] = TRUE;
			$session_data = $this->session->userdata('logged_in_by');
			$this->_data['userid'] = $session_data['userid'];
			$this->_data['username'] = $session_data['username'];
			$this->_data['full_name'] = $session_data['full_name'];
			$this->_data['photo'] = $session_data['photo'];
			$this->_data['regdate'] = isset($session_data['regdate']) ? $session_data['regdate'] : '';
			$this->_data['group_id'] = $session_data['group_id'];
			$this->_data['role'] = isset($session_data['role']) ? $session_data['role'] : '';
			$this->_data['is_wholesale'] = isset($session_data['is_wholesale']) ? filter_var($session_data['is_wholesale'], FILTER_VALIDATE_BOOLEAN) : TRUE;
			$this->ROLE = $session_data['group_id'];
		}
	}

	public function _initialize() {
		$configs = $this->get_configs();
		$this->_initialize_global($configs);

		$shops_cat = modules::run('shops/cat/gets');
		$this->_data['shops_cat_list'] = $shops_cat['data_list']; //mang chua quan he cha con
		$this->_data['shops_cat_data'] = $shops_cat['data_input']; // mang chua du lieu tat ca cat (có link)
		$this->_data['shops_cat_input'] = $shops_cat['data_input']; // mang chua du lieu tat ca cat (không link chi co gia tri va ten de dung cho input)

		$this->_data['postcat_list'] = modules::run('posts/postcat/get_menu_list');
		$this->_data['postcat_data'] = modules::run('posts/postcat/get_data');
		$this->_data['postcat_input'] = modules::run('posts/postcat/get_input');

		$main = 'Main';
		$this->_data['menu_main_list'] = modules::run('menu/get_menu_list', $main);
		$this->_data['menu_main_data'] = modules::run('menu/get_data', $main);
		$this->_data['menu_main_input'] = modules::run('menu/get_input', $main);

		$bottom = 'Bottom';
		$this->_data['menu_bottom_list'] = modules::run('menu/get_menu_list', $bottom);
		$this->_data['menu_bottom_data'] = modules::run('menu/get_data', $bottom);
		$this->_data['menu_bottom_input'] = modules::run('menu/get_input', $bottom);

		$left = 'Left';
		$this->_data['menu_left_list'] = modules::run('menu/get_menu_list', $left);
		$this->_data['menu_left_data'] = modules::run('menu/get_data', $left);
		$this->_data['menu_left_input'] = modules::run('menu/get_input', $left);

		$right = 'Right';
		$this->_data['menu_right_list'] = modules::run('menu/get_menu_list', $right);
		$this->_data['menu_right_data'] = modules::run('menu/get_data', $right);
		$this->_data['menu_right_input'] = modules::run('menu/get_input', $right);

		$this->_breadcrumbs[] = array(
			'url' => base_url(),
			'name' => 'Trang chủ',
		);
		$this->set_breadcrumbs();

		$this->_data['analytics_UA_code'] = $configs['analytics_UA_code'];
		$this->_data['display_copyright_developer'] = $configs['display_copyright_developer'];

		$this->_data['site_hotline'] = $configs['site_hotline'];
		$this->_data['site_address'] = $configs['site_address'];
		$this->_data['site_phone'] = $configs['site_phone'];

		$this->_data['favicon'] = $configs['favicon'];
		$this->_data['site_logo'] = $configs['site_logo'];
		$this->_data['site_logo_footer'] = $configs['site_logo_footer'];

		$this->_data['fb_page'] = $configs['fb_page'];
		$this->_data['iframe_map'] = $configs['iframe_map'];
		$this->_data['site_content_contact'] = $configs['site_content_contact'];
		$this->_data['iframe_video'] = $configs['iframe_video'];

		$this->_data['facebook_fanpage'] = $configs['facebook_fanpage'];
		$this->_data['google_plus'] = $configs['google_plus'];
		$this->_data['youtube_page'] = $configs['youtube_page'];
		$this->_data['twitter_page'] = $configs['twitter_page'];
		$this->_data['skype_page'] = $configs['skype_page'];
		$this->_data['linkedin_page'] = $configs['linkedin_page'];
		$this->_data['payment_info'] = $configs['payment_info'];

		$this->_load_menu_main();
		$this->_load_category_product();
		$this->_load_category_search();

		//search
		$this->_data['q'] = $this->input->get('q');

		$this->_modules_script[] = array(
			'folder' => 'shops',
			'name' => 'cart',
		);

		$this->_modules_script[] = array(
			'folder' => 'newsletter',
			'name' => 'newsletter',
		);

		$cat_shop_none = modules::run('images/get_images_position', 'cat_shop', 'none');
		$this->_data['cat_shop_none'] = $cat_shop_none;

		$baner_home_none = modules::run('images/get_images_position', 'baner_home', 'none');
		$this->_data['baner_home_none'] = $baner_home_none;

		//Vanphongdaidien
		$info_address_center_none = modules::run('info/get_info_position', 'address_center', 'none', TRUE);
		$this->_data['info_address_center_none'] = $info_address_center_none;

		//Service Footer
		$info_service_footer_none = modules::run('info/get_info_position', 'service_footer', 'none', TRUE);
		$this->_data['info_service_footer_none'] = $info_service_footer_none;

		//Welcome
		$info_welcome_none = modules::run('info/get_info_position', 'welcome', 'none', TRUE);
		$this->_data['info_welcome_none'] = $info_welcome_none;

		//Company name
		$info_companyname_none = modules::run('info/get_info_position', 'companyname', 'none', TRUE);
		$this->_data['info_companyname_none'] = $info_companyname_none;

		//Noprice Infomation
		$info_noprice_infomation_none = modules::run('info/get_info_position', 'noprice_infomation', 'none', TRUE);
		$this->_data['info_noprice_infomation_none'] = $info_noprice_infomation_none;

		//info
		$info_all_none = modules::run('info/get_info_position', 'all', 'none', TRUE);
		$this->_data['info_all_none'] = $info_all_none;

		//result_order
		$info_result_order_none = modules::run('info/get_info_position', 'result_order', 'none', TRUE);
		$this->_data['info_result_order_none'] = $info_result_order_none;

		//copyright
		$info_copyright_none = modules::run('info/get_info_position', 'copyright', 'none', TRUE);
		$this->_data['info_copyright_none'] = $info_copyright_none;

		//Contact
		$info_contact_none = modules::run('info/get_info_position', 'contact', 'none', TRUE);
		$this->_data['info_contact_none'] = $info_contact_none;

		//Address
		$info_address_none = modules::run('info/get_info_position', 'address', 'none', TRUE);
		$this->_data['info_address_none'] = $info_address_none;

		//Certify
		$info_certify_none = modules::run('info/get_info_position', 'certify', 'none', TRUE);
		$this->_data['info_certify_none'] = $info_certify_none;

		//Partner
		$info_partner_none = modules::run('info/get_info_position', 'partner', 'none', TRUE);
		$this->_data['info_partner_none'] = $info_partner_none;

		//DVKH Hotline
		$info_hotline_dvkh_none = modules::run('info/get_info_position', 'hotline_dvkh', 'none', TRUE);
		$this->_data['info_hotline_dvkh_none'] = $info_hotline_dvkh_none;

		//DVKH Email
		$info_email_dvkh_none = modules::run('info/get_info_position', 'email_dvkh', 'none', TRUE);
		$this->_data['info_email_dvkh_none'] = $info_email_dvkh_none;

		//DVKH Skype
		$info_skype_dvkh_none = modules::run('info/get_info_position', 'skype_dvkh', 'none', TRUE);
		$this->_data['info_skype_dvkh_none'] = $info_skype_dvkh_none;

		//Hotline Scroll
		$info_hotline_scroll_none = modules::run('info/get_info_position', 'hotline_scroll', 'none', TRUE);
		$this->_data['info_hotline_scroll_none'] = $info_hotline_scroll_none;

		//link_video
		$info_link_video_none = modules::run('info/get_info_position', 'link_video', 'none', TRUE);
		$this->_data['info_link_video_none'] = $info_link_video_none;

		//content_main
		$info_content_main_none = modules::run('info/get_info_position', 'content_main', 'none', TRUE);
		$this->_data['info_content_main_none'] = $info_content_main_none;

		//Advertise Right Top
		$advertise_right_top_none = modules::run('images/get_images_position', 'advertise_right_top', 'none');
		$this->_data['advertise_right_top_none'] = $advertise_right_top_none;

		//Advertise Sidebar
		$advertise_sidebar_none = modules::run('images/get_images_position', 'advertise_sidebar', 'none');
		$this->_data['advertise_sidebar_none'] = $advertise_sidebar_none;

		//Advertise Bottom
		$advertise_bottom_none = modules::run('images/get_images_position', 'advertise_bottom', 'none');
		$this->_data['advertise_bottom_none'] = $advertise_bottom_none;

		//Bannerbottom
		$bannerbottom_none = modules::run('images/get_images_position', 'bannerbottom', 'none');
		$this->_data['bannerbottom_none'] = $bannerbottom_none;

		//Partner
		$partner_none = modules::run('images/get_images_position', 'partner', 'none');
		$this->_data['partner_none'] = $partner_none;

		//Service
		$service_none = modules::run('images/get_images_position', 'service', 'none');
		$this->_data['service_none'] = $service_none;

		//Advertise
		$advertise_none = modules::run('images/get_images_position', 'advertise', 'none');
		$this->_data['advertise_none'] = $advertise_none;

		//Footer Introdue
		$footer_introdue_none = modules::run('images/get_images_position', 'footer_introdue', 'none');
		$this->_data['footer_introdue_none'] = $footer_introdue_none;

		//login_introdue
		$login_introdue_none = modules::run('images/get_images_position', 'login_introdue', 'none');
		$this->_data['login_introdue_none'] = $login_introdue_none;

		//products bestseller
		$products_bestseller = modules::run('shops/rows/gets_item_field', 'is_bestseller', 0);
		$partial = array();
		$partial['data'] = $products_bestseller;
		$this->_data['products_bestseller'] = $this->load->view('layout/site/partial/product_bestseller', $partial, true);

		//products bestview
		$products_bestview = modules::run('shops/rows/gets_item_field', 'is_bestview', 0);
		$partial = array();
		$partial['data'] = $products_bestview;
		$this->_data['products_bestview'] = $this->load->view('layout/site/partial/product_bestview', $partial, true);

		//hotline none
		//$hotline_none = modules::run('images/get_images_position', 'hotline', 'none');
		//$this->_data['hotline_none'] = isset($hotline_none[0]) ? $hotline_none[0] : NULL;
		if (!is_home()) {
			//posts featured
			$posts_featured = modules::run('posts/gets_item_field', 'is_featured', 0);
			$partial = array();
			$partial['data'] = $posts_featured;
			$this->_data['posts_featured'] = $this->load->view('layout/site/partial/post_featured', $partial, true);

		}

		//set all css, js, plugins
		$this->add_css();
		$this->add_js();
		$this->set_plugins();
		$this->set_modules();
	}

	public function _initialize_admin() {
		$configs = $this->get_configs();
		$this->_initialize_global($configs);
		//$this->_initialize_user();

		$this->_data['breadcrumbs_module_name'] = '';
		$this->_data['breadcrumbs_module_func'] = '';

		$this->_breadcrumbs_admin[] = array(
			'url' => '',
			'name' => '<i class="fa fa-dashboard"></i> Admin',
		);
		$this->set_breadcrumbs_admin();

		$this->_plugins_css_admin[] = array(
			'folder' => 'iCheck',
			'name' => 'all',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'iCheck',
			'name' => 'icheck',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'iCheck',
			'name' => 'app.icheck',
		);
		//set all css, js, plugins
		$this->set_plugins_admin();
		$this->set_modules();

		$this->_data['num_rows_contact'] = modules::run('contact/num_rows_new');
		$this->_data['num_rows_order'] = modules::run('shops/orders/num_rows_new');

		$this->_data['menu_admin_active'] = ($this->uri->segment(2) == '') ? '' : $this->uri->segment(2);
	}

	public function _load_category_product($parent = 0) {
		$data_category_product = modules::run('shops/cat/gets_data', array('parent' => $parent));
		$column = 3;
		$html_category_product = '';
		if (is_array($data_category_product) && !empty($data_category_product)) {
			foreach ($data_category_product as $key => $value) {
				$is_second = FALSE;
				$data_category_product_1 = modules::run('shops/cat/gets_data', array('parent' => $key));
				if (is_array($data_category_product_1) && !empty($data_category_product_1)) {
					$is_second = TRUE;
				}
				$html_category_product .= '<li><a' . ($is_second ? ' class="ipad-lans"' : '') . ' href="' . $value['lurl'] . '">' . $value['lname'] . ($is_second ? ' <i class="fas fa-chevron-right"></i>' : '') . '</a>';
				if ($is_second) {
					$count_data_category_product_1 = count($data_category_product_1);
					$items_row = floor($count_data_category_product_1 / $column); //so item it nhat tren mot co
					$items_residual = $count_data_category_product_1 % $column; //0,1 hoac 2
					if ($items_residual == 0) {
						$start = 0;
						$length = $items_row * 1;
						$data_category_product_1_1 = array_slice($data_category_product_1, $start, $length, TRUE);
						$start = $items_row * 1;
						$length = $items_row * 1;
						$data_category_product_1_2 = array_slice($data_category_product_1, $start, $length, TRUE);
						$start = $items_row * 2;
						$length = $count_data_category_product_1 - 1;
						$data_category_product_1_3 = array_slice($data_category_product_1, $start, $length, TRUE);
					} elseif ($items_residual == 1) {
						$start = 0;
						$length = $items_row * 1 + 1;
						$data_category_product_1_1 = array_slice($data_category_product_1, $start, $length, TRUE);
						$start = $items_row * 1 + 1;
						$length = $items_row * 1;
						$data_category_product_1_2 = array_slice($data_category_product_1, $start, $length, TRUE);
						$start = $items_row * 2 + 1;
						$length = $count_data_category_product_1 - 1;
						$data_category_product_1_3 = array_slice($data_category_product_1, $start, $length, TRUE);
					} else {
						$start = 0;
						$length = $items_row * 1 + 1;
						$data_category_product_1_1 = array_slice($data_category_product_1, $start, $length, TRUE);
						$start = $items_row * 1 + 1;
						$length = $items_row * 1 + 1;
						$data_category_product_1_2 = array_slice($data_category_product_1, $start, $length, TRUE);
						$start = $items_row * 2 + 2;
						$length = $count_data_category_product_1 - 1;
						$data_category_product_1_3 = array_slice($data_category_product_1, $start, $length, TRUE);
					}
					/*
						var_dump($items_row);
						var_dump($items_residual);
						var_dump($data_category_product_1);
						var_dump($data_category_product_1_1);
						var_dump($data_category_product_1_2);
						var_dump($data_category_product_1_3);
					*/
					$html_category_product .= '<div class="wsmenu-submenu-sub" id="scroll-bar">';
					$html_category_product .= '<div class="rows-menu">';
					if (is_array($data_category_product_1_1) && !empty($data_category_product_1_1)) {
						$html_category_product .= '<ul class="col-xl-3 col-lg-4 col-md-12 link-list">';
						foreach ($data_category_product_1_1 as $key1 => $value1) {
							$html_category_product .= '<li><a href="' . $value1['lurl'] . '"><i class="fa fa-square"></i>' . $value1['lname'] . '</a></li>';
						}
						$html_category_product .= '</ul>';
					}
					if (is_array($data_category_product_1_2) && !empty($data_category_product_1_2)) {
						$html_category_product .= '<ul class="col-xl-3 col-lg-4 col-md-12 link-list">';
						foreach ($data_category_product_1_2 as $key1 => $value1) {
							$html_category_product .= '<li><a href="' . $value1['lurl'] . '"><i class="fa fa-square"></i>' . $value1['lname'] . '</a></li>';
						}
						$html_category_product .= '</ul>';
					}
					if (is_array($data_category_product_1_3) && !empty($data_category_product_1_3)) {
						$html_category_product .= '<ul class="col-xl-3 col-lg-4 col-md-12 link-list">';
						foreach ($data_category_product_1_3 as $key1 => $value1) {
							$html_category_product .= '<li><a href="' . $value1['lurl'] . '"><i class="fa fa-square"></i>' . $value1['lname'] . '</a></li>';
						}
						$html_category_product .= '</ul>';
					}
					$html_category_product .= '</div>';
					$html_category_product .= '</div>';
				}
				$html_category_product .= "</li>";
			}
		}
		$this->_data['html_category_product'] = $html_category_product;
		//var_dump($html_category_product); die;
	}

	public function _load_category_search() {
		$search_param = 'all';
		$get = $this->input->get();
		if (isset($get['search_param']) && $get['search_param'] != 'all') {
			$search_param = $get['search_param'];
		}
		$data_category_search = modules::run('shops/cat/gets_data', array('parent' => 0));
		$html_category_search = '';
		if (is_array($data_category_search) && !empty($data_category_search)) {
			foreach ($data_category_search as $key => $value) {
				$html_category_search .= '<a href="#' . $value['lid'] . '" class="dropdown-item">' . $value['lname'] . '</a>';
			}
		}
		$this->_data['html_category_search'] = $html_category_search;
	}

	function _load_menu_main() {
		$main = 'Main';
		$data_menu_main = modules::run('menu/gets', $main, 0);
		$html_menu_main = '';
		if (is_array($data_menu_main) && !empty($data_menu_main)) {
			foreach ($data_menu_main as $key => $value) {
				$html_menu_main .= "<li><a" . ($value['lurl'] == current_url() ? " class=\"active\"" : '') . " href=\"" . $value['lurl'] . "\">" . $value['lname'] . "</a></li>";
			}
		}

		/*
			$main = 'Main';
			$data_menu_main = modules::run('menu/gets', $main, 0);
			$html_menu_main = '';
			$html_menu_main_mobile = '';
			if (is_array($data_menu_main) && !empty($data_menu_main)) {
				foreach ($data_menu_main as $key => $value) {
					$is_second = FALSE;
					$data_menu_main_1 = modules::run('menu/gets', $main, $key);
					if (is_array($data_menu_main_1) && !empty($data_menu_main_1)) {
						$is_second = TRUE;
					}
					$html_menu_main .= '<li class="menu-item' . (($value['lurl'] == current_url()) ? ' active' : '') . ($is_second ? ' menu-item-has-children has-sub narrow' : '') . '"><a href="' . $value['lurl'] . '"' . (($value['lurl'] == current_url()) ? ' class="current"' : ''). '>' . $value['lname'] . '</a>';
					$html_menu_main_mobile .= '<li class="menu-item' . (($value['lurl'] == current_url()) ? ' active' : '') . ($is_second ? ' menu-item-has-children has-sub' : '') . '"><a href="' . $value['lurl'] . '">' . $value['lname'] . '</a>';
					if ($is_second) {
						$html_menu_main .= '<div class="popup">';
							$html_menu_main .= '<div class="inner">';
								$html_menu_main .= '<ul class="sub-menu">';

						$html_menu_main_mobile .= '<span class="arrow"></span>';
						$html_menu_main_mobile .= '<ul class="sub-menu">';
						foreach ($data_menu_main_1 as $key1 => $value1) {
							$is_third = FALSE;
							$data_menu_main_2 = modules::run('menu/gets', $main, $key1);
							if (is_array($data_menu_main_2) && !empty($data_menu_main_2)) {
								$is_third = TRUE;
							}
							$html_menu_main .= '<li class="menu-item' . ($is_third ? ' menu-item-has-children' : '') . '"><a href="' . $value1['lurl'] . '">' . $value1['lname'] . '</a>';

							$html_menu_main_mobile .= '<li class="menu-item"><a href="' . $value1['lurl'] . '">' . $value1['lname'] . '</a>';

							if ($is_third) {
								$html_menu_main .= '<ul class="sub-menu">';

								$html_menu_main_mobile .= '<span class="arrow"></span>';
								$html_menu_main_mobile .= '<ul class="sub-menu">';
								foreach ($data_menu_main_2 as $value2) {
									$html_menu_main .= '<li class="menu-item"><a href="' . $value2['lurl'] . '">' . $value2['lname'] . '</a></li>';
									$html_menu_main_mobile .= '<li class="menu-item"><a href="' . $value2['lurl'] . '">' . $value2['lname'] . '</a></li>';
								}
								$html_menu_main .= '</ul>';

								$html_menu_main_mobile .= '</ul>';
							}
							$html_menu_main .= '</li>';
							$html_menu_main_mobile .= '</li>';
						}
								$html_menu_main .= '</ul>';
							$html_menu_main .= '</div>';
						$html_menu_main .= '</div>';
						$html_menu_main_mobile .= '</ul>';
					}
					$html_menu_main .= "</li>";
					$html_menu_main_mobile .= "</li>";
				}
			}
		*/
		$this->_data['html_menu_main'] = $html_menu_main;
	}

	function _init_fancybox() {
		$this->_plugins_css_admin[] = array(
			'folder' => 'fancy-box/source',
			'name' => 'jquery.fancybox',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'fancy-box/source',
			'name' => 'jquery.fancybox',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'fancy-box/source',
			'name' => 'jquery.fancybox.pack',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'fancy-box/lib',
			'name' => 'jquery.mousewheel-3.0.6.pack',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'fancy-box',
			'name' => 'jquery-apps',
		);
		$this->set_plugins_admin();
	}

	function index_admin() {
		$this->load->module('users');
		$this->users->admin_index();
	}

	protected function set_breadcrumbs() {
		$this->_data['breadcrumbs'] = $this->_breadcrumbs;
	}

	protected function set_breadcrumbs_admin() {
		$this->_data['breadcrumbs_admin'] = $this->_breadcrumbs_admin;
	}

	protected function set_plugins() {
		$this->_data['plugins_css'] = $this->_plugins_css;
		$this->_data['plugins_script'] = $this->_plugins_script;
	}

	protected function set_plugins_admin() {
		$this->_data['plugins_css_admin'] = $this->_plugins_css_admin;
		$this->_data['plugins_script_admin'] = $this->_plugins_script_admin;
	}

	protected function set_modules() {
		$this->_data['modules_css'] = $this->_modules_css;
		$this->_data['modules_script'] = $this->_modules_script;
	}

	protected function add_css() {
		$this->_data['add_css'] = $this->_add_css;
	}

	protected function add_js() {
		$this->_data['add_js'] = $this->_add_js;
	}

	protected function get_configs() {
		$configs = $this->M_configs->get_configs();
		return $this->set_configs($configs);
	}

	private function set_configs($data) {
		$configs = array();
		if (is_array($data) && !empty($data)) {
			foreach ($data as $value) {
				$configs[$value['config_name']] = $value['config_value'];
			}
		}
		return $configs;
	}

	function index() {
		$this->_initialize();

		//slideshow none
		$slideshow_none = modules::run('images/get_images_position', 'slideshow', 'none');
		$this->_data['slideshow_none'] = $slideshow_none;

		//products featured
		/*$products_featured = modules::run('shops/rows/gets_item_field', 'is_featured', 0);
		$partial = array();
		$partial['data'] = $products_featured;
		$this->_data['products_featured'] = $this->load->view('layout/site/partial/product_featured', $partial, true);*/

		//products wholesale
		$products_wholesale = null;
		if (isset($this->_data['is_wholesale']) && $this->_data['is_wholesale']) {
			$products_wholesale = modules::run('shops/rows/gets_item_field', 'is_wholesale', 0);
		}
		$partial = array();
		$partial['data'] = $products_wholesale;
		$this->_data['products_wholesale'] = $this->load->view('layout/site/partial/product_wholesale', $partial, true);

		//products new
		$products_new = modules::run('shops/rows/gets_item_field', 'is_new', 0);
		$partial = array();
		$partial['data'] = $products_new;
		$this->_data['products_new'] = $this->load->view('layout/site/partial/product_new', $partial, true);

		//products promotion
		/*$products_promotion = modules::run('shops/rows/gets_item_field', 'is_promotion', 4);
		$partial = array();
		$partial['data'] = $products_promotion;
		$this->_data['products_promotion'] = $this->load->view('layout/site/partial/product_promotion', $partial, true);*/

		//products cat home
		// $products_cat_home = modules::run('shops/cat/gets_inhome', 0);
		// if (is_array($products_cat_home) && !empty($products_cat_home)) {
		//     foreach ($products_cat_home as $key => $value) {
		//         $products_cat_home[$key]['items'] = modules::run('shops/rows/get_items_in_cat_id', $value['id'], 8);
		//     }
		// }
		// $partial = array();
		// $partial['data'] = $products_cat_home;
		// $this->_data['products_cat_home'] = $this->load->view('layout/site/partial/product_cat_home', $partial, true);

		//products cat home
		/*$products_cat_home = modules::run('shops/cat/gets_root', 0);
		if (is_array($products_cat_home) && !empty($products_cat_home)) {
			foreach ($products_cat_home as $key => $value) {
				$childs = modules::run('shops/cat/gets_root', $value['id']);
				if (is_array($childs) && !empty($childs)) {
					foreach ($childs as $k => $v) {
						$childs[$k]['items'] = modules::run('shops/rows/get_items_in_cat_id', $v['id'], 8);
					}
				}
				$products_cat_home[$key]['childs'] = $childs;
			}
		}
			
			
		$partial = array();
		$partial['data'] = $products_cat_home;
		$this->_data['products_cat_home'] = $this->load->view('layout/site/partial/product_cat_home', $partial, true);*/

		//posts home
		/*$posts_home = modules::run('posts/gets_item_field', 'inhome', 0);
		$partial = array();
		$partial['data'] = $posts_home;
		$this->_data['posts_home'] = $this->load->view('layout/site/partial/post_home', $partial, true);*/

		//posts new
		$posts_new = modules::run('posts/gets_item_field', 'is_new', 0);
		$partial = array();
		$partial['data'] = $posts_new;
		$this->_data['posts_new'] = $this->load->view('layout/site/partial/post_new', $partial, true);


		$total = modules::run('posts/counts');;
		$perpage = (int) isset($this->_data['per_page_posts_cat'])
			? $this->_data['per_page_posts_cat']
			: ($this->config->item('per_page')
				? $this->config->item('per_page')
				: 6);

		$this->load->library('pagination');
		$config = [
			'total_rows' => $total,
			'per_page' => $perpage,
			'full_tag_open' => '<ul class="pagination">',
			'full_tag_close' => '</ul>',

			'first_link' => '&larr,',
			'first_tag_open' => '<li class="prev page">',
			'first_tag_close' => '</li>',

			'last_link' => '&rarr,',
			'last_tag_open' => '<li class="next page">',
			'last_tag_close' => '</li>',

			'next_link' => '<i class="bi bi-chevron-right"></i>',
			'next_tag_open' => '<li class="next page">',
			'next_tag_close' => '</li>',

			'prev_link' => '<i class="bi bi-chevron-left"></i>',
			'prev_tag_open' => '<li class="prev page">',
			'prev_tag_close' => '</li>',

			'cur_tag_open' => '<li><a href="" class="active">',
			'cur_tag_close' => '</a></li>',

			'num_tag_open' => '<li class="page">',
			'num_tag_close' => '</li>',

			'base_url' => base_url(),
			'first_url' => site_url(),
		];
		$segment = 3;
		$config['uri_segment'] = $segment;

		$this->pagination->initialize($config);

		$pagination = $this->pagination->create_links();
		$offset =
			$this->uri->segment($segment) == ''
			? 0
			: $this->uri->segment($segment);

		$rows = $this->M_posts->gets($args, $perpage, $offset);
		$this->_data['pagination'] = $pagination;

		$this->_data['main_content'] = 'layout/site/pages/main';
		$this->load->view('site/layout', $this->_data);
	}

	protected function logged_in() {
		if ($this->session->userdata('logged_in')) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	protected function redirect_register() {
		if (!$this->logged_in()) {
			redirect(site_url('register'));
		}
	}

	protected function redirect_login() {
		if (!$this->logged_in()) {
			redirect(site_url('login'));
		}
	}

	protected function redirect_admin() {
		if (!$this->session->userdata('logged_in')) {
			redirect(get_admin_url());
		} else {
			$access_role = $this->access_role();
			if ($access_role < 4) {
				redirect(base_url());
			}
		}
	}

	protected function access_role() {
		$this->load->model('users/m_groups_users', 'M_groups_users');
		$userid = isset($this->_data['userid']) ? $this->_data['userid'] : 0;
		$result = $this->M_groups_users->get_group_id($userid);
		return isset($result['group_id']) ? (int) $result['group_id'] : 2;
	}

	protected function set_notify($notify_type, $notify_content) {
		//set notify
		$sess_array = array(
			'notify_type' => $notify_type,
			'notify_content' => $notify_content,
		);
		$this->session->set_userdata('notify_current', $sess_array);
	}

	protected function set_notify_admin($notify_type, $notify_content) {
		//set notify
		$sess_array = array(
			'notify_type' => $notify_type,
			'notify_content' => $notify_content,
		);
		$this->session->set_userdata('notify_current_admin', $sess_array);
	}

	protected function set_message_success() {
		$sess_array = array(
			'notify_type' => 'success',
			'notify_content' => $this->_message_success,
		);
		$this->session->set_userdata('notify_current_admin', $sess_array);
	}

	protected function set_message_warning() {
		$sess_array = array(
			'notify_type' => 'warning',
			'notify_content' => $this->_message_warning,
		);
		$this->session->set_userdata('notify_current_admin', $sess_array);
	}

	protected function set_message_danger() {
		$sess_array = array(
			'notify_type' => 'danger',
			'notify_content' => $this->_message_danger,
		);
		$this->session->set_userdata('notify_current_admin', $sess_array);
	}

	protected function set_message_banned() {
		$sess_array = array(
			'notify_type' => 'danger',
			'notify_content' => $this->_message_banned,
		);
		$this->session->set_userdata('notify_current_admin', $sess_array);
	}

	protected function set_json_encode() {
		$this->_data['json_encode'] = array(
			'status' => $this->_status,
			'message' => $this->_message,
		);
	}

	function get_alias() {
		$str = $this->input->post('title');
		$this->_data['str'] = $str;
		$this->load->view('layout/admin/view_alias', $this->_data);
	}

	protected function show_message() {
		$this->_data['box'] = array(
			'status' => $this->input->post('status'),
			'message' => $this->input->post('message'),
		);
		$this->load->view('layout/message', $this->_data);
	}

	function load_language() {
		$this->lang->load('admin', $this->language);
		$this->lang->load('site', $this->language);
	}

	function set_current_url($url) {
		$this->session->set_userdata('url', base64_encode($url));
	}

	function redirect_after() {
		if ($this->session->userdata('url')) {
			$url = base64_decode($this->session->userdata('url'));
			$this->session->unset_userdata('url');
		} else {
			$url = base_url();
		}
		redirect($url);
	}

}

/* End of file layout.php */
/* Location: ./application/modules/layout/controllers/layout.php */

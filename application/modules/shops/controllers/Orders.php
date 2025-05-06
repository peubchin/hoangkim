<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Orders extends Layout {

	private $_module_slug = 'orders';

	function __construct() {
		parent::__construct();
		$this->_data['module_slug'] = $this->_module_slug;
		$this->_data['breadcrumbs_module_name'] = 'Đơn đặt hàng';
	}

	function default_args() {
		$order_by = array(
			'shops_order.created' => 'DESC',
		);
		$args = array();
		$args['order_by'] = $order_by;

		return $args;
	}

	function cart() {
		$this->_initialize();

		if($this->input->get('debug')){
			echo 'DEBUG: <br/>';
			//die;
			$cart = $this->cart->contents();
			$order_id = 0;
			//debug_arr($cart, true);
			$order_total_real = 0;
			$history_container = array();
			$history_current = array();
			$items_order_details = array();
			$order_VAT = 0;
			foreach ($cart as $item) {
				$VAT = get_VAT_percent_product($item['VAT']);
				$VAT_value = 0;
				$item_VAT_value = get_VAT_product($item['price'], $item['VAT'], $item['qty']);
				$item_subtotal = $item['subtotal'];
				if($item_VAT_value > 0){
					$VAT_value = $item_subtotal - $item_VAT_value;// số tiền VAT của sp
					$item_subtotal -= $VAT_value;// số tiền sp chưa VAT
					$order_VAT += $VAT_value;// cộng dồn VAT của sp
				}

				$product_id = $item['id'];
				$name = $item['name'];
				$price = $item['unit_price'];
				$promotion_price = $item['price'];
				$quantity = (int) $item['qty'];
				$monetized = $item['subtotal'];

				$item_commission = $monetized;
				$item_cost_price = (float) $item['cost_price'];
				if($item_cost_price > 0){
					$item_commission = $quantity * $item_cost_price;
				}
				$order_total_real += $item_commission;
				
				$order_detail_agrs = array(
					'order_id' => $order_id,
					'name' => $name,
					'product_id' => $product_id,
					'price' => $price,
					'promotion_price' => $promotion_price,
					'percent_discount' => 0,
					'VAT' => $VAT,
					'VAT_value' => $VAT_value,
					'quantity' => $quantity,
					'monetized' => $monetized,
				);
				$items_order_details[] = $order_detail_agrs;
			}
			debug_arr($items_order_details);
			echo 'order_VAT: ' . $order_VAT;
			die;

			$bool = $this->M_shops_order_details->adds($items_order_details);
			var_dump($bool); die;
		}

		$this->_breadcrumbs[] = array(
			'url' => current_url(),
			'name' => 'Giỏ hàng',
		);
		$this->_data['breadcrumbs'] = $this->_breadcrumbs;

		$this->_data['title_seo'] = 'Giỏ hàng' . ' - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/cart';
		$this->load->view('layout/site/layout', $this->_data);
	}

    function order_history() {
		$this->_initialize();
        modules::run('users/require_logged_in');

        $this->_plugins_css[] = array(
            'folder' => 'bootstrap-datepicker/css',
            'name' => 'bootstrap-datepicker',
        );
        $this->_plugins_css[] = array(
            'folder' => 'bootstrap-datepicker/css',
            'name' => 'bootstrap-datepicker3',
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker/js',
            'name' => 'bootstrap-datepicker',
        );
        $this->_plugins_script[] = array(
            'folder' => 'bootstrap-datepicker/locales',
            'name' => 'bootstrap-datepicker.vi.min',
        );
        $this->set_plugins();

        $this->_modules_script[] = array(
            'folder' => 'shops',
            'name' => 'site-order-history',
        );
        $this->set_modules();

        $get = $this->input->get();
        $this->_data['get'] = $get;

        $this->output->cache(true);
        $_module_slug = 'quan-ly-don-hang';
        $this->_data['module_slug'] = $_module_slug;
        $user_id = $this->_data['userid'];

        $segment = 2;
		$this->load->model('m_shops_orders', 'M_shops_orders');

		$args = $this->default_args();
		$args['customer_id'] = $user_id;

		//theo ngay
		if (isset($get['fromday']) && trim($get['fromday']) != '') {
			$args['start_date_start'] = get_start_date($get['fromday']);
		}
		if (isset($get['today']) && trim($get['today']) != '') {
			$args['start_date_end'] = get_end_date($get['today']);
		}

		if (isset($get['status']) && trim($get['status']) != '') {
		    $args['transaction_status'] = $get['status'];
		}

        $total = $this->M_shops_orders->counts($args);
        $perpage = 12;

        $this->load->library('pagination');
        $config['total_rows'] = $total;
        $config['per_page'] = $perpage;
        $config['full_tag_open'] = '<ul class="pagination">';
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
            $config['base_url'] = base_url($_module_slug);
            $config['suffix'] = '?' . http_build_query($get, '', "&");
            $config['first_url'] = site_url($_module_slug . '?' . http_build_query($get, '', "&"));
            $config['uri_segment'] = $segment;
        } else {
            $config['base_url'] = base_url($_module_slug);
            $config['first_url'] = site_url($_module_slug);
            $config['uri_segment'] = $segment;
        }

        $this->pagination->initialize($config);
        $pagination = $this->pagination->create_links();
        $this->_data['pagination'] = $pagination;

        $offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $this->_data['rows'] = $this->M_shops_orders->gets($args, $perpage, $offset);
        
        $this->_breadcrumbs[] = array(
            'url' => site_url('quan-ly-don-hang'),
            'name' => 'Đơn mua'
        );
        $this->_data['breadcrumbs'] = $this->_breadcrumbs;

        $this->_data['title_seo'] = 'Đơn mua' . ' - ' . $this->_data['title_seo'];
        $this->_data['main_content'] = 'layout/site/pages/order-history';
        $this->load->view('layout/site/layout', $this->_data);
    }

	function site_order_history_detail() {
		$this->_initialize();

		$segment = 2;
		$order_id = ($this->uri->segment($segment) == '') ? 0 : (int) $this->uri->segment($segment);
		$order = $this->get($order_id);
		if (!is_array($order) || empty($order)) {
			redirect(base_url());
		}
		$this->_data['order'] = $order;

		$customer = modules::run('users/get', $order["order_customer_id"]); // lấy thông tin khách hàng
		$this->_data['customer'] = $customer;

		$order_items = modules::run('shops/order_details/get_data_in_order_id', $order_id); // lấy các sản phẩm có id giỏ hàng

		$products = array();
		foreach ($order_items as $value) {
			$arr = modules::run('shops/rows/get', $value['product_id']); // lấy thông tin chi tiết các sản phẩm có trong giỏ hàng
			$arr['name'] = (isset($arr['title']) && trim($arr['title']) != '') ? $arr['title'] : $value["name"];
			$arr['quantity'] = $value["quantity"];
			$arr['price'] = $value["price"];
			$arr['promotion_price'] = $value["promotion_price"];
			$arr['percent_discount'] = $value["percent_discount"];
			$arr['VAT'] = $value["VAT"];
			$arr['VAT_value'] = $value["VAT_value"];
			$arr['monetized'] = $value["monetized"];
			$products[] = $arr;
		}
		$this->_data['products'] = $products;
		
		$this->_breadcrumbs[] = array(
            'url' => current_url(),
            'name' => 'Xem đơn hàng'
        );
        $this->_data['breadcrumbs'] = $this->_breadcrumbs;
		
		$this->_data['title'] = 'Xem đơn hàng' . ' ' . $order['order_code'] . ' - ' . $this->_data['title'] . ' Admin';
		$this->_data['main_content'] = 'layout/site/pages/order-history-detail';
		$this->load->view('layout/site/layout', $this->_data);
    }

    function site_order_cancel() {
        $this->_initialize();
        $this->redirect_login();

        $segment = 2;
        $order_id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
        $order = $this->get($order_id);
        if(is_array($order) && !empty($order)){
            $this->update_transaction_status($order_id, -1);
            $notify_type = 'success';
            $notify_content = "Đã hủy đơn đặt hàng thành công!";
        }else{
            $notify_type = 'danger';
            $notify_content = "Đơn đặt hàng này không tồn tại!";
        }
        $this->set_notify($notify_type, $notify_content);
        redirect(site_url('quan-ly-don-hang'));
    }

	function site_add($args) {
		return $this->M_shops_orders->add($args);
	}

	function site_order_success() {
		$this->_initialize();

		$segment = 2;
		$order_id = ($this->uri->segment($segment) == '') ? 0 : (int) $this->uri->segment($segment);
		$order = $this->get($order_id);
		if (!is_array($order) || empty($order)) {
			redirect(base_url());
		}
		$this->_data['order'] = $order;

		$customer = modules::run('users/get', $order["order_customer_id"]); // lấy thông tin khách hàng
		$this->_data['customer'] = $customer;

		$order_items = modules::run('shops/order_details/get_data_in_order_id', $order_id); // lấy các sản phẩm có id giỏ hàng

		$products = array();
		foreach ($order_items as $value) {
			$arr = modules::run('shops/rows/get', $value['product_id']); // lấy thông tin chi tiết các sản phẩm có trong giỏ hàng
			$arr['name'] = (isset($arr['title']) && trim($arr['title']) != '') ? $arr['title'] : $value["name"];
			$arr['quantity'] = $value["quantity"];
			$arr['price'] = $value["price"];
			$arr['promotion_price'] = $value["promotion_price"];
			$arr['percent_discount'] = $value["percent_discount"];
			$arr['VAT'] = $value["VAT"];
			$arr['VAT_value'] = $value["VAT_value"];
			$arr['monetized'] = $value["monetized"];
			$products[] = $arr;
		}
		$this->_data['products'] = $products;

		$this->_breadcrumbs[] = array(
			'url' => site_url('ket-qua-thanh-toan'),
			'name' => 'Kết quả thanh toán',
		);
		$this->set_breadcrumbs();

		$this->_data['title_seo'] = 'Kết quả thanh toán' . ' - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/checkout-result';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function admin_view() {
		$this->_initialize_admin();
		$this->redirect_admin();
		// echo get_price_before_tax(116000, 8);
		// echo "<br/>";
		// echo get_price_before_tax(510000, 8);
		// die;

		$this->load->module('users');
		$this->_message_success = 'Xác nhận thanh toán thành công!';
		$this->_message_danger = 'Lỗi kỹ thuật, vui lòng kiểm tra lại!';
		$this->_message_warning = 'Đơn hàng này không tồn tại!';
		$this->_message_banned = 'Bạn không có quyền truy cập vào khu vực này!';
		if ($this->users->validate_admin_logged_in() == TRUE) {
			$post = $this->input->post();

			$this->_plugins_script_admin[] = array(
				'folder' => 'bootbox',
				'name' => 'bootbox',
			);
			$this->_plugins_script_admin[] = array(
				'folder' => 'jQuery-Plugin-To-Print-Any-Part-Of-Your-Page-Print',
				'name' => 'jQuery.print',
			);
			$this->set_plugins_admin();

			$this->_modules_script[] = array(
				'folder' => 'shops',
				'name' => 'admin-order-view',
			);
			$this->set_modules();

			$order_id = ($this->uri->segment(4) == '') ? 0 : $this->uri->segment(4); # Lấy id giỏ hàng
			$order = $this->get($order_id); //lấy thông tin giỏ hàng
			if(!(is_array($order) && !empty($order))){
				redirect(get_admin_url('orders'));
			}
			$this->_data['order'] = $order;
			// $history_current = isset($order['history']) ? @unserialize($order['history']) : array();
			// echo "<pre>";
			// print_r($history_current);
			// echo "</pre>";
			// die();

			$customer = modules::run('users/get', $order["order_customer_id"]); // lấy thông tin khách hàng
			$this->_data['customer'] = $customer;

			$order_items = modules::run('shops/order_details/get_data_in_order_id', $order_id); // lấy các sản phẩm có id giỏ hàng

			$products = array();

			foreach ($order_items as $value) {
				$arr = modules::run('shops/rows/get', $value['product_id']); // lấy thông tin chi tiết các sản phẩm có trong giỏ hàng
				$arr['name'] = (isset($arr['title']) && trim($arr['title']) != '') ? $arr['title'] : $value["name"];
				$arr['quantity'] = $value["quantity"];
				$arr['price'] = $value["price"];
				$arr['promotion_price'] = $value["promotion_price"];
				$arr['percent_discount'] = $value["percent_discount"];
				$arr['VAT'] = $value["VAT"];
				$arr['VAT_value'] = $value["VAT_value"];
				$arr['monetized'] = $value["monetized"];
				$products[] = $arr;
			}
			$this->_data['products'] = $products;
			$this->_data['breadcrumbs_module_func'] = 'Xem đơn hàng';
			$this->_data['title'] = 'Xem đơn hàng' . ' ' . $order['order_code'] . ' - ' . $this->_data['title'] . ' Admin';

			if (!empty($post)) {
				if ($this->input->post('ajax') == '1') {
					if ($this->input->post('get_data') == '1') {
						$this->load->view('shops/admin/view_page_order_view', $this->_data);
					} else {
			        	$id = (int) $this->input->post('id');
			    		$order = $this->get($id);
			    		if($id == 0 || !(is_array($order) && !empty($order))){
			    			$this->_status = "danger";
							$this->_message = 'Đơn hàng không tồn tại! Vui lòng kiểm tra lại!';
			    		}else{
			    			$verify_by = isset($order['order_customer_id']) ? $order['order_customer_id'] : NULL;
			        		$time = time();
					    	$status = (int) $this->input->post('status');
					    	if($status == 1){
		    					$data_order = array(
		    						'transaction_status' => $status,
		    						'modified' => $time
		    					);
		    		    		if ($this->M_shops_orders->update($id, $data_order)) {
		    		        		$data_commission = array(
		    						    'status' => $status,
		    						    'verified' => $time,
		    						    'verify_by' => $verify_by
		    						);
		    						$this->M_users_commission->update(array('order_id' => $id), $data_commission);
		    						/*
		    						//thong bao
		    						$users_commission = $this->M_users_commission->gets(array(
		    							'order_id' => $id,
		    							'not_in_user_id' => array($verify_by, $order['created_by'])
		    						));
		    						if(is_array($users_commission) && !empty($users_commission)){
		    							foreach ($users_commission as $value) {
		    								$actor_id = $verify_by;
		    								$notifier_id = $value['user_id'];
		    								$action = 'USER_COMMISSION';
		    								$object_id = $value['id'];
		    								$order_code = $order['order_code'];
		    								$data_notification = array(
		    									'actor_id' => $actor_id,
		    									'notifier_id' => $notifier_id,
		    									'action' => $action,
		    									'object_id' => $object_id,
		    									'title' => 'Hưởng hoa hồng mua hàng',
		    									'description' => 'Hưởng hoa hồng từ đơn hàng #' . $order_code,
		    									'message' => "Hưởng hoa hồng từ đơn hàng #" . $order_code . " của đối tác",
		    									'history' => NULL,
		    									'status' => -1,
		    									'created' => $time
		    								);
		    								$this->M_notification->add($data_notification);
		    							}
		    						}

		    						//gui thong bao cho partner
		    						$actor_id = $verify_by;
		    						$notifier_id = $order['created_by'];
		    						$action = 'USER_SELL';
		    						$object_id = $order['order_id'];
		    						$order_code = $order['order_code'];
		    						$data_notification = array(
		    							'actor_id' => $actor_id,
		    							'notifier_id' => $notifier_id,
		    							'action' => $action,
		    							'object_id' => $object_id,
		    							'title' => 'Thành viên xác nhận đơn hàng',
		    							'description' => 'Thành viên đã xác nhận đơn hàng #' . $order_code,
		    							'message' => "Thành viên đã xác nhận thanh toán đơn hàng #" . $order_code,
		    							'history' => NULL,
		    							'status' => -1,
		    							'created' => $time
		    						);
		    		        		$notification_id = $this->M_notification->add($data_notification);

		    		        		//push notification
		    		        		$args_fcm_register = array(
		    			                'user_id' => $notifier_id,
		    			                'order_by' => array(
		    			        			'id' => 'DESC'
		    			        		)
		    			            );
		    			            $fcm_register = $this->M_fcm_register->get_by($args_fcm_register);
		    			            if(isset($fcm_register['token'])){
		    			        		$registration_ids = array(
		    						        $fcm_register['token']
		    						    );
		    						    $title = 'Thông báo thành viên xác nhận đơn hàng LeDu';
		    			        		$body = 'Thành viên đã xác nhận thanh toán đơn hàng #' . $order_code;
		    			        		$notification_options = array(
		    			        			'title' => $title,
		    			        			'body' => $body,
		    			        		);
		    			        		$notification_data = array(
		    			        			"title" => $title,
		    			        			"message" => $body,
		    			        			"image" => "https://ledu.vn/media/images/slide-le-du1.png",
		    			        			"action" => "BUY",
		    			        			"action_destination" => $notification_id
		    			        		);
		    			        		$push_notification = push_notification_partner($registration_ids, $notification_options, $notification_data);
		    			        	}

		    			        	$subs = $this->M_users_commission->gets(array(
		    			        	    'order_id' => $object_id,
		    			        	    'in_action' => array('SUB_BUY_ROOT', 'SUB_BUY'),
		    			        	    'extend_by' => $verify_by
		    			        	));
		    			        	if(is_array($subs) && !empty($subs)){
		    			        	    foreach ($subs as $sub) {
		    			        	        $type = '';
		    			        	        if($sub['action'] == 'SUB_BUY_ROOT'){
		    			        	            $type = 'trực tiếp ';
		    			        	        }
		    			        	        $value = formatRice($sub['value']);
		    			        	        $value_cost = formatRice($sub['value_cost']);
		    			        	        $percent = $sub['percent'];
		    			        	        $action = 'USER_COMMISSION_BUY';
		    			        	        $data_notification = array(
		    			        	            'actor_id' => $verify_by,
		    			        	            'notifier_id' => $sub['user_id'],
		    			        	            'action' => $action,
		    			        	            'object_id' => $sub['id'],
		    			        	            'title' => "Hưởng hoa hồng " . $type . "từ thành viên thanh toán đơn hàng",
		    			        	            'description' => "Người dùng hưởng hoa hồng " . $type . $percent . "%(" . $value . "đ) từ thành viên thanh toán đơn hàng #" . $order_code . " giá trị " . $value_cost . "đ",
		    			        	            'message' => "Bạn đã hưởng hoa hồng " . $type . $percent . "%(" . $value . "đ) từ thành viên thanh toán đơn hàng #" . $order_code . " giá trị " . $value_cost . "đ",
		    			        	            'history' => NULL,
		    			        	            'status' => -1,
		    			        	            'created' => $time
		    			        	        );
		    			        	        $notification_id = $this->M_notification->add($data_notification);

		    			        	        //push notification
		    			        	        $args_fcm_register = array(
		    			        	            'user_id' => $sub['user_id'],
		    			        	            'order_by' => array(
		    			        	                'id' => 'DESC'
		    			        	            )
		    			        	        );
		    			        	        $fcm_register = $this->M_fcm_register->get_by($args_fcm_register);
		    			        	        if(isset($fcm_register['token'])){
		    			        	            $registration_ids = array(
		    			        	                $fcm_register['token']
		    			        	            );
		    			        	            $title = "Thông báo hưởng hoa hồng " . $type . "từ thành viên thanh toán đơn hàng";
		    			        	            $body = "Hưởng hoa hồng " . $type . $percent . "%(" . $value . "đ) từ thành viên thanh toán đơn hàng #" . $order_code . " giá trị " . $value_cost . "đ";
		    			        	            $notification_options = array(
		    			        	                'title' => $title,
		    			        	                'body' => $body,
		    			        	            );
		    			        	            $notification_data = array(
		    			        	                "title" => $title,
		    			        	                "message" => $body,
		    			        	                "image" => "https://ledu.vn/media/images/slide-le-du1.png",
		    			        	                "action" => "USER_COMMISSION_BUY",
		    			        	                "action_destination" => $notification_id
		    			        	            );
		    			        	            $push_notification = push_notification($registration_ids, $notification_options, $notification_data);
		    			        	        }
		    			        	    }
		    			        	}
		    			        	*/
            		        		$this->_status = "success";
        							$this->_message = 'Thanh toán đơn hàng thành công!';
            		        	} else {
            		        		$this->_status = "danger";
            		        		$this->_message = 'Chưa thể thực hiện thanh toán đơn hàng! Vui lòng thực hiện lại!';
            		        	}
					    	}else{
		    					$data_order = array(
		    						'transaction_status' => $status,
		    						'modified' => $time
		    					);
		    		    		if ($this->M_shops_orders->update($id, $data_order)) {
		    		        		$data_commission = array(
		    						    'status' => $status,
		    						    'verified' => $time,
		    						    'verify_by' => $verify_by
		    						);
		    						$this->M_users_commission->update(array('order_id' => $id), $data_commission);

		    						/*
		    						//gui thong bao cho partner
		    						$actor_id = $verify_by;
		    						$notifier_id = $order['created_by'];
		    						$action = 'USER_SELL';
		    						$object_id = $order['order_id'];
		    						$order_code = $order['order_code'];
		    						$data_notification = array(
		    							'actor_id' => $actor_id,
		    							'notifier_id' => $notifier_id,
		    							'action' => $action,
		    							'object_id' => $object_id,
		    							'title' => 'Thành viên hủy đơn hàng',
		    							'description' => 'Thành viên đã hủy đơn hàng #' . $order_code,
		    							'message' => "Thành viên đã hủy thanh toán đơn hàng #" . $order_code,
		    							'history' => NULL,
		    							'status' => -1,
		    							'created' => $time
		    						);
		    		        		$notification_id = $this->M_notification->add($data_notification);

		    		        		//push notification
		    		        		$args_fcm_register = array(
		    			                'user_id' => $notifier_id,
		    			                'order_by' => array(
		    			        			'id' => 'DESC'
		    			        		)
		    			            );
		    			            $fcm_register = $this->M_fcm_register->get_by($args_fcm_register);
		    			            if(isset($fcm_register['token'])){
		    			        		$registration_ids = array(
		    						        $fcm_register['token']
		    						    );
		    						    $title = 'Thông báo thành viên hủy đơn hàng LeDu';
		    			        		$body = 'Thành viên đã hủy thanh toán đơn hàng #' . $order_code;
		    			        		$notification_options = array(
		    			        			'title' => $title,
		    			        			'body' => $body,
		    			        		);
		    			        		$notification_data = array(
		    			        			"title" => $title,
		    			        			"message" => $body,
		    			        			"image" => "https://ledu.vn/media/images/slide-le-du1.png",
		    			        			"action" => "BUY",
		    			        			"action_destination" => $notification_id
		    			        		);
		    			        		$push_notification = push_notification_partner($registration_ids, $notification_options, $notification_data);
		    			        	}
		    			        	//send email admin
		    			        	$user_id = $verify_by;
		    			        	$user = $this->M_users->get($user_id);
		    			        	$full_name = isset($user['full_name']) ? $user['full_name'] : '';
		    			        	$site_name = $this->M_configs->get_config_value('site_name');
		    			        	$receiver_email = $this->M_configs->get_config_value('site_email');
		    			        	$emails = explode(',', $receiver_email);
		    			        	$site_email = get_first_element(array_map('trim', $emails));
		    			        	$sender_email = $site_email;
		    			        	$sender_name = $site_name;
		    			        	$order['modified'] = $time;

		    			        	$partial = array();
		    			        	$partial['data'] = $order;
		    			        	$data_sendmail = array(
		    			        	    'sender_email' => $site_email,
		    			        	    'sender_name' => $sender_name . ' - ' . $site_email,
		    			        	    'receiver_email' => $receiver_email, //mail nhan thư
		    			        	    'subject' => 'Thành viên hủy đơn hàng - ' . $site_name,
		    			        	    'message' => $this->load->view('html-template-buy-cancel', $partial, true)
		    			        	);
		    			        	$bool = $this->_send_mail($data_sendmail);
		    			        	*/
		    		        		$this->_status = "success";
									$this->_message = 'Hủy đơn hàng thành công!';
		    		        	} else {
		    		        		$this->_status = "danger";
		    		        		$this->_message = 'Chưa thể thực hiện hủy đơn hàng! Vui lòng thực hiện lại!';
		    		        	}
					    	}
			    		}
						/*
						$order_id = $this->input->post('order_id');
						if ($order_id != 0) {
							$data = array(
								'transaction_status' => 4,
							);
							if ($this->M_shops_orders->update($order_id, $data)) {
								//xac nhan giao dich
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
						*/
						$this->set_json_encode();
						$this->load->view('layout/json_data', $this->_data);
					}
				} else {
					$order_id = $this->input->post('order_id');
					if ($order_id != 0) {
						$data = array(
							'transaction_status' => 4,
						);
						if ($this->M_shops_order->update($order_id, $data)) {

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
					redirect(get_admin_url('shops/or_view/' . $order_id));
				}
			} else {
				if (isset($order['order_viewed']) && $order['order_viewed'] != 1) {
					$this->M_shops_orders->update($order['order_id'], array('order_viewed' => 1, 'view_time' => time()));
				}
				$this->_data['main_content'] = 'shops/admin/view_page_order_view';
				$this->load->view('layout/admin/view_layout', $this->_data);
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
				redirect(get_admin_url('orders'));
			}
		}
	}

	function site_html_or_view($order_id = 0) {
		$order = $this->get($order_id); //lấy thông tin giỏ hàng
		$this->_data['order'] = $order;

		$customer = modules::run('users/get', $order["order_customer_id"]); // lấy thông tin khách hàng
		$this->_data['customer'] = $customer;

		$order_items = modules::run('shops/order_details/get_data_in_order_id', $order_id); // lấy các sản phẩm có id giỏ hàng

		$products = array();

		foreach ($order_items as $value) {
			$arr = modules::run('shops/rows/get', $value['product_id']); // lấy thông tin chi tiết các sản phẩm có trong giỏ hàng
			$arr['name'] = (isset($arr['title']) && trim($arr['title']) != '') ? $arr['title'] : $value["name"];
			$arr['quantity'] = $value["quantity"];
			$arr['price'] = $value["price"];
			$arr['promotion_price'] = $value["promotion_price"];
			$arr['percent_discount'] = $value["percent_discount"];
			$arr['VAT'] = $value["VAT"];
			$arr['VAT_value'] = $value["VAT_value"];
			$arr['monetized'] = $value["monetized"];
			$products[] = $arr;
		}
		$this->_data['products'] = $products;
		return $this->load->view('layout/site/partial/html-template-order', $this->_data, true);
	}

	function gets($args, $perpage = 5, $offset = -1) {
		return $this->M_shops_orders->gets($args, $perpage, $offset);
	}

	function counts($args) {
		return $this->M_shops_orders->counts($args);
	}

	function get($order_id) {
		return $this->M_shops_orders->get($order_id);
	}

	function num_rows_new() {
		$this->load->model('m_shops_orders', 'M_shops_orders');
		$args['order_viewed'] = 0;
		return $this->counts($args);
	}

	function check_order_code_availablity($order_code = '') {
		$this->load->model('m_shops_orders', 'M_shops_orders');
		return $this->M_shops_orders->check_order_code_availablity($order_code);
	}

	function get_code($id) {
		$code = $this->get_max_code();
		while (!$this->M_shops_orders->check_code_availablity($code, $id)) {
			$code = $this->get_max_code();
		}

		return $code;
	}

	function get_max_code() {
		$args = $this->default_args();
		$order_by = array(
			'order_id' => 'DESC',
			// 'order_code' => 'DESC',
		);
		$args['order_by'] = $order_by;
		$rows = $this->M_shops_orders->gets($args, 1, 0);
		$code = (int) (isset($rows[0]['order_code']) ? filter_var($rows[0]['order_code'], FILTER_SANITIZE_NUMBER_INT) : 0) + 1;
		return ORDER_CODE_PREFIX . $code;
		// return ORDER_CODE_PREFIX . str_pad($code, ORDER_CODE_LENGHT, "0", STR_PAD_LEFT);
	}

	function check_code_availablity() {
		$post = $this->input->post();
		$this->_message_success = 'true';
		$this->_message_danger = 'false';

		if (!empty($post)) {
			$code = $this->input->post('code');
			$id = $this->input->post('id');
			if ($this->input->post('ajax') == '1') {
				if ($this->M_shops_orders->check_code_availablity($code, $id)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				if ($this->M_shops_orders->check_code_availablity($code, $id)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function check_code_format() {
		$post = $this->input->post();
		$this->_message_success = 'true';
		$this->_message_danger = 'false';

		if (!empty($post)) {
			if ($this->input->post('ajax') == '1') {
				$code = $this->input->post('code');
				if ($this->is_code_format($code)) {
					$this->_status = "success";
					$this->_message = $this->_message_success;
				} else {
					$this->_status = "danger";
					$this->_message = $this->_message_danger;
				}

				$this->set_json_encode();
				$this->load->view('layout/json_data', $this->_data);
			} else {
				$code = $this->input->post('code');
				if ($this->is_code_format($code)) {
					return TRUE;
				} else {
					return FALSE;
				}
			}
		} else {
			redirect(base_url());
		}
	}

	function is_code_format($code = '') {
		return TRUE;
		// $result = preg_match("/" . ORDER_CODE_PREFIX . "[0-9]{" . ORDER_CODE_LENGHT . "," . ORDER_CODE_LENGHT . "}$/", $code);
		// return ($result == 1) ? TRUE : FALSE;
	}

	function admin_content_ajax() {
		if (!$this->input->is_ajax_request()) {
			exit('No direct script access allowed');
		}
		$message = array();
		$message['status'] = 'error';
		$message['content'] = null;
		$message['message'] = 'Kiểm tra thông tin nhập';

		$post = $this->input->post();
		if (!empty($post)) {
			$this->form_validation->set_error_delimiters('<span class="help-block">', '</span>');
			$this->form_validation->set_rules('customer_id', 'Khách hàng', 'required');

			if ($this->form_validation->run($this)) {
				$this->_initialize_admin();
				$this->redirect_admin();
				$err = FALSE;
				$time = time();
				$id = (int) $this->input->post('id');
				$order_total = 0;
				$order_VAT = 0;
				$order_total_real = 0;
				$order_total_accumulated = 0;
				$current_consignments = array();
				$consignments = array();
				$tblAppendGrid = 'tblAppendGrid_';
				$rowOrder = explode(',', $this->input->post($tblAppendGrid . 'rowOrder'));
				if (is_array($rowOrder) && !empty($rowOrder)) {
					foreach ($rowOrder as $i) {
						$product_id = $this->input->post($tblAppendGrid . 'product_id' . '_' . $i);
						$quantity = (int) $this->input->post($tblAppendGrid . 'quantity' . '_' . $i);
						$unit_price = filter_var($this->input->post($tblAppendGrid . 'unit_price' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						//$promotion_price = filter_var($this->input->post($tblAppendGrid . 'promotion_price' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						$promotion_price = filter_var($this->input->post($tblAppendGrid . 'product_price' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						$cost_price = filter_var($this->input->post($tblAppendGrid . 'cost_price' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						$VAT = filter_var($this->input->post($tblAppendGrid . 'VAT' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						$VAT_value = filter_var($this->input->post($tblAppendGrid . 'VAT_value' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						$monetized = filter_var($this->input->post($tblAppendGrid . 'monetized' . '_' . $i), FILTER_SANITIZE_NUMBER_FLOAT);
						$order_total += $monetized;

						$consignments[] = array(
							'product_id' => $product_id,
							'quantity' => $quantity,
							'unit_price' => $unit_price,
							'promotion_price' => $promotion_price,
							'cost_price' => $cost_price,
							'VAT' => $VAT,
							'VAT_value' => $VAT_value,
							'monetized' => $monetized
						);
					}
					//168: ledutest2
					// $customer_id = $this->input->post('customer_id');
					// echo $customer_id;
					// echo "<pre>";
					// print_r($consignments);
					// echo "</pre>";
					// die();
					if (!empty($consignments)) {
						// echo "<pre>";
						// print_r($consignments);
						// echo "</pre>";
						// die();
						$current_page = $this->input->post('current_page');
						$order_date = $this->input->post('order_date');

						$order_discount_percent = $order_discount = $order_monetized = 0;
						// if($order_total >= 4000000){
						// 	$order_discount_percent = 10;
						// }elseif($order_total >= 2000000){
						// 	$order_discount_percent = 5;
						// }
						$order_discount = $order_total * $order_discount_percent / 100;
						$order_monetized = $order_total - $order_discount;

						$customer_id = $this->input->post('customer_id');
						$customer = $this->M_users->get($customer_id);

						$data_order_args = array(
							'order_customer_id' => $customer_id,
							'order_amount' => $order_total,
							'order_discount_percent' => $order_discount_percent,
							'order_discount' => $order_discount,
							'order_monetized' => $order_monetized,
							'order_email' => $customer['email'],
							'order_ship_full_name' => $customer['full_name'],
							'order_ship_address' => $customer['address'],
							'order_phone' => $customer['phone'],
							'order_note' => $this->input->post('note'),
							'order_date' => (trim($order_date) != '') ? get_current_date($order_date) : 0,
						);

						if ($id != 0) {
							$data_order_args['modified'] = $time;
							if (!$this->M_shops_orders->update($id, $data_order_args)) {
								$err = TRUE;
							} else {
								$this->M_shops_order_details->delete($id);
							}
							$order_id = $id;
						} else {
							$order_code = $this->get_max_code();
							if (!$this->check_order_code_availablity($order_code)) {
								$order_code = $this->get_max_code();
							}
							$data_order_args['order_code'] = $order_code;
							$data_order_args['admin_id'] = $this->_data['userid'];
							$data_order_args['transaction_status'] = 0;
							$data_order_args['post_ip'] = ip2long($this->input->ip_address());
							$data_order_args['expiry_time'] = $time + 3600 * 24 * 3;
							$data_order_args['created'] = $time;
							$order_id = $this->M_shops_orders->add($data_order_args);
						}

						if ($order_id != 0) {
							$order = $this->M_shops_orders->get($order_id);
							$history_container = array();
							$history_current = isset($order['history']) ? @unserialize($order['history']) : array();
							$items_order_details = array();
							foreach ($consignments as $value) {
								$product_id = $value['product_id'];
								$product = modules::run('shops/rows/get', $product_id);
								$name = isset($product['title']) ? $product['title'] : NULL;

								$price = $value['unit_price'];
								$promotion_price = $value['promotion_price'];
								$VAT = $value['VAT'];
								$VAT_value = $value['VAT_value'];
								$quantity = (int)$value['quantity'];
								$monetized = $value['monetized'];

								$order_VAT += $VAT_value;// cộng dồn VAT của sp

								$item_commission = $monetized;
								$item_cost_price = (float) $value['cost_price'];
								if($item_cost_price > 0){
									$item_commission = $quantity * $item_cost_price;
								}
								$order_total_real += $item_commission;

								$item_accumulated_price = 0;
								$accumulated_price = isset($product['product_accumulated_price']) ? (float) $product['product_accumulated_price'] : 0;
								if($accumulated_price > 0){
									$item_accumulated_price = abs($quantity * $accumulated_price);
								}
								$order_total_accumulated += $item_accumulated_price;

								$order_detail_args = array(
									'order_id' => $order_id,
									'name' => $name,
									'product_id' => $product_id,
									'price' => $price,
									'promotion_price' => $promotion_price,
									'accumulated_price' => $accumulated_price,
									'percent_discount' => 0,
									'VAT' => $VAT,
									'VAT_value' => $VAT_value,
									'quantity' => $quantity,
									'monetized' => $monetized,
								);
								$items_order_details[] = $order_detail_args;
							}
							$bool = $this->M_shops_order_details->adds($items_order_details);
							if(in_array($customer_id, array(1))){//, 168
								$message['status'] = 'success';
								$message['content'] = null;
								$message['message'] = 'Admin testing: ' . var_dump($bool);
								echo json_encode($message);
								exit();
							}

							$F1 = 10;
							// $F2 = 3;
							// $F3 = 2;
							$F2 = 7;
							$F3 = 5;
							//$F2 = $F3 = $F4 = $F5 = $F6 = $F3 = $F7 = $F8 = $F9 = $F10 = 1.25;
							$product_id = 0;
							if($id == 0){
								$history = array();
								$action = 'SELL';
								$value_cost = $order_monetized;
								$percent = 0;
								$value = $value_cost;
			        			$user_id = $this->_data['userid'];
			        			$data_commission = array(
			        				'order_id' => $order_id,
			        				'product_id' => $product_id,
			        			    'user_id' => $user_id,
			        			    'extend_by' => NULL,
			        			    'action' => $action,
			        			    'value_cost' => $value_cost,
			        			    'percent' => $percent,
			        			    'value' => $value,
			        			    'value_real' => $order_total_real,
			        			    'value_accumulated' => $order_total_accumulated,
			        			    'message' => 'Người dùng bán hàng',
			        			    'status' => 0,
			        			    'created' => $time
			        			);
			        			$this->M_users_commission->add($data_commission);
			        			$history[] = $data_commission;

			        			$action = 'BUY';
			        			$value_cost = (-1) * $order_monetized;
			        			$percent = 0;
			        			$value = $value_cost;
								$data_commission = array(
									'order_id' => $order_id,
									'product_id' => $product_id,
								    'user_id' => $customer_id,
								    'extend_by' => NULL,
								    'action' => $action,
								    'value_cost' => $value_cost,
								    'percent' => $percent,
								    'value' => $value,
								    'value_real' => $order_total_real,
								    'value_accumulated' => $order_total_accumulated,
								    'message' => 'Người dùng mua sản phẩm',
								    'status' => 0,
								    'created' => $time
								);
								$this->M_users_commission->add($data_commission);
								$history[] = $data_commission;

								/*$args = modules::run('users/default_args');
								$users = $this->M_users->gets($args);*/
								$users = $this->M_users->gets_short_multiple();
								$data = get_data_parent_level($users, $customer_id);
								$root = $data['root'];
								$subs = $data['subs'];

								if($root != 0){
									$action = 'SUB_BUY_ROOT';
									$value_cost = $order_total_real;
									$percent = $F1;
									$value = $value_cost * $percent / 100;
									$data_commission = array(
									    'order_id' => $order_id,
									    'product_id' => $product_id,
									    'user_id' => $root,
									    'extend_by' => $customer_id,
									    'action' => $action,
									    'value_cost' => $value_cost,
									    'percent' => $percent,
									    'value' => $value,
									    'value_real' => $order_total,
									    'value_accumulated' => $order_total_accumulated,
									    'message' => 'Người dùng được hưởng hoa hồng từ người dùng cấp dưới trực tiếp mua sản phẩm',
									    'status' => 0,
									    'created' => $time
									);
									$this->M_users_commission->add($data_commission);
									$history[] = $data_commission;
								}
								if(is_array($subs) && !empty($subs)){
									$arr_subs_percent = array($F2, $F3);
									//$arr_subs_percent = array($F2, $F3, $F4, $F5, $F6, $F7, $F8, $F9, $F10);
									$i = 0;
									foreach ($subs as $sub) {
										if($i <= 1){
											$action = 'SUB_BUY';
											$value_cost = $order_total_real;
											$percent = isset($arr_subs_percent[$i]) ? $arr_subs_percent[$i] : 0;
											$value = $value_cost * $percent / 100;
											$i++;
											$data_commission = array(
												'order_id' => $order_id,
												'product_id' => $product_id,
											    'user_id' => $sub,
											    'extend_by' => $customer_id,
											    'action' => $action,
											    'value_cost' => $value_cost,
											    'percent' => $percent,
											    'value' => $value,
											    'value_real' => $order_total,
											    'value_accumulated' => $order_total_accumulated,
											    'message' => 'Người dùng được hưởng hoa hồng từ người dùng cấp dưới mua sản phẩm',
											    'status' => 0,
											    'created' => $time
											);
											$this->M_users_commission->add($data_commission);
											$history[] = $data_commission;
										}else{
											break;
										}
									}
								}
								$history_container[] = $history;
			        		}else{
			        			$this->M_users_commission->delete(array(
			        				'order_id' => $order_id,
			        				//'not_in_product_id' => array_column($consignments, 'product_id'),
			        			));

								$history = array();
								$action = 'SELL';
								$value_cost = $order_monetized;
								$percent = 0;
								$value = $value_cost;
			        			$user_id = $this->_data['userid'];
			        			$data_commission = array(
			        				'order_id' => $order_id,
			        				'product_id' => $product_id,
			        			    'user_id' => $user_id,
			        			    'extend_by' => NULL,
			        			    'action' => $action,
			        			    'value_cost' => $value_cost,
			        			    'percent' => $percent,
			        			    'value' => $value,
			        			    'message' => 'Người dùng bán hàng',
			        			    'status' => 0,
			        			    'created' => $time
			        			);
			        			$args_commission_exist = array(
			        				'order_id' => $order_id,
			        				//'product_id' => $product_id,
			        			    'action' => $action,
			        			);
			        			$row_commission = $this->M_users_commission->get($args_commission_exist);
			        			if(isset($row_commission['id'])){
			        				$this->M_users_commission->update(array('id' => $row_commission['id']), $data_commission);
				        		}else{
				        			$this->M_users_commission->add($data_commission);
				        		}
			        			$history[] = $data_commission;

			        			$action = 'BUY';
			        			$value_cost = (-1) * $order_monetized;
			        			$percent = 0;
			        			$value = $value_cost;
								$data_commission = array(
									'order_id' => $order_id,
									'product_id' => $product_id,
								    'user_id' => $customer_id,
								    'extend_by' => NULL,
								    'action' => $action,
								    'value_cost' => $value_cost,
								    'percent' => $percent,
								    'value' => $value,
								    'message' => 'Người dùng mua sản phẩm',
								    'status' => 0,
								    'created' => $time
								);
								$args_commission_exist = array(
			        				'order_id' => $order_id,
			        				//'product_id' => $product_id,
			        			    'action' => $action,
			        			);
			        			$row_commission = $this->M_users_commission->get($args_commission_exist);
			        			if(isset($row_commission['id'])){
			        				$this->M_users_commission->update(array('id' => $row_commission['id']), $data_commission);
				        		}else{
				        			$this->M_users_commission->add($data_commission);
				        		}
								$history[] = $data_commission;

								$args = modules::run('users/default_args');
								$users = $this->M_users->gets($args);
								$data = get_data_parent_level($users, $customer_id);
								$root = $data['root'];
								$subs = $data['subs'];

								if($root != 0){
									$action = 'SUB_BUY_ROOT';
									$value_cost = $order_monetized;
									$percent = $F1;
									$value = $value_cost * $percent / 100;
									$data_commission = array(
									    'order_id' => $order_id,
									    'product_id' => $product_id,
									    'user_id' => $root,
									    'extend_by' => $customer_id,
									    'action' => $action,
									    'value_cost' => $value_cost,
									    'percent' => $percent,
									    'value' => $value,
									    'message' => 'Người dùng được hưởng hoa hồng từ người dùng cấp dưới trực tiếp mua sản phẩm',
									    'status' => 0,
									    'created' => $time
									);
									$args_commission_exist = array(
				        				'order_id' => $order_id,
				        				//'product_id' => $product_id,
				        			    'action' => $action,
				        			);
				        			$row_commission = $this->M_users_commission->get($args_commission_exist);
				        			if(isset($row_commission['id'])){
				        				$this->M_users_commission->update(array('id' => $row_commission['id']), $data_commission);
					        		}else{
					        			$this->M_users_commission->add($data_commission);
					        		}
									$history[] = $data_commission;
								}
								if(is_array($subs) && !empty($subs)){
									$arr_subs_percent = array($F2, $F3);
									//$arr_subs_percent = array($F2, $F3, $F4, $F5, $F6, $F7, $F8, $F9, $F10);
									$i = 0;
									foreach ($subs as $sub) {
										if($i <= 1){
											$action = 'SUB_BUY';
											$value_cost = $order_monetized;
											$percent = isset($arr_subs_percent[$i]) ? $arr_subs_percent[$i] : 0;
											$value = $value_cost * $percent / 100;
											$i++;
											$data_commission = array(
												'order_id' => $order_id,
												'product_id' => $product_id,
											    'user_id' => $sub,
											    'extend_by' => $customer_id,
											    'action' => $action,
											    'value_cost' => $value_cost,
											    'percent' => $percent,
											    'value' => $value,
											    'message' => 'Người dùng được hưởng hoa hồng từ người dùng cấp dưới mua sản phẩm',
											    'status' => 0,
											    'created' => $time
											);
						        			$args_commission_exist = array(
						        				'order_id' => $order_id,
						        				//'product_id' => $product_id,
						        			    'action' => $action,
						        			);
						        			$row_commission = $this->M_users_commission->get($args_commission_exist);
						        			if(isset($row_commission['id'])){
						        				$this->M_users_commission->update(array('id' => $row_commission['id']), $data_commission);
							        		}else{
							        			$this->M_users_commission->add($data_commission);
							        		}
											$history[] = $data_commission;
										}else{
											break;
										}
									}
								}
								$history_container[] = $history;
			        		}
							$history_current[] = $history_container;
							$history_bool = $this->M_shops_orders->update($order_id, array(
								'order_VAT' => $order_VAT,
								'order_total_real' => $order_total_real,
								'order_total_accumulated' => $order_total_accumulated,
								'history' => serialize($history_current)
							));
						} else {
							$err = TRUE;
						}
					}

					if ($err === FALSE) {
						$message['status'] = 'success';
						$message['content'] = array('data' => (isset($order_id) && $order_id > 0) ? get_admin_url($this->_module_slug . '/view/' . $order_id) : get_admin_url($this->_module_slug));
						$message['message'] = 'Hóa đơn bán hàng đã lưu!';
					} else {
						$message['status'] = 'error';
						$message['content'] = null;
						$message['message'] = 'Có lỗi xảy ra! Vui lòng thực hiện lại!';
					}
				} else {
					$message['status'] = 'error';
					$message['content'] = null;
					$message['message'] = 'Chưa chọn sản phẩm! Vui lòng chọn lại sản phẩm và thực hiện lại!';
				}
			}
		}
		echo json_encode($message);
		exit();
	}

	function admin_content() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$get = $this->input->get();
		$this->_data['get'] = $get;

		$max_code = $this->get_max_code();
		$this->_data['max_code'] = $max_code;
		// echo $max_code; die;

		$user_id = $this->_data['userid'];
		$user = $this->M_users->get($user_id);
		$this->_data['user'] = $user;

		$customers_args = array(
			'role' => 'AGENCY',
			'order_by' => array(
				'userid' => 'DESC',
				'full_name' => 'ASC',
			),
		);
		$customers = $this->M_users->gets_customers($customers_args);
		/*if (is_array($customers) && !empty($customers)) {
			foreach ($customers as $key => $value) {
				$customers[$key]['full_name'] = $value['full_name'] . (trim($value['phone']) != '' ? ' - ' . $value['phone'] : '');
			}
		}*/
		$this->_data['customers'] = $customers;
		// pre($customers);
		// die;

		$is_admin = (isset($this->_data['role']) && in_array($this->_data['role'], array('ADMIN')));

		$title = 'Hóa đơn bán hàng - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
		$segment = 4;
		$id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
		$row = $this->get($id);
		if ($id != 0 && is_array($row) && !empty($row)) {
			if ($row['transaction_status'] != 0) {
				$notify_type = 'warning';
				$notify_content = " Đơn đặt hàng này không thể cập nhật!";
				$this->set_notify_admin($notify_type, $notify_content);
				redirect(get_admin_url('orders'));
			}
			$this->_data['current_page'] = (int) $this->input->get('current_page');
			$row['items'] = modules::run('shops/order_details/get_data_in_order_id', $id);
			$this->_data['row'] = $row;
			$title = 'Cập nhật hóa đơn bán hàng - ' . $this->_data['breadcrumbs_module_name'] . ' - ' . $this->_data['title'];
		}
		$this->_plugins_css_admin[] = array(
			'folder' => 'jquery.appendGrid',
			'name' => 'jquery-ui.structure.min',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'jquery.appendGrid',
			'name' => 'jquery-ui.theme.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery.appendGrid',
			'name' => 'jquery-ui-1.11.1.min',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'jquery.appendGrid',
			'name' => 'jquery.appendGrid-1.6.2',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'jquery.appendGrid',
			'name' => 'jquery.appendGrid-1.6.2',
		);

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker',
			'name' => 'app.editinfo',
		);
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
			'name' => 'jquery.mask',
		);

		$this->_plugins_css_admin[] = array(
			'folder' => 'chosen',
			'name' => 'chosen',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'chosen',
			'name' => 'chosen.jquery',
		);

		$this->set_plugins_admin();

		$this->_modules_script[] = array(
			'folder' => 'shops',
			'name' => 'admin-order-validate',
		);
		$this->set_modules();

		$args_products = array();
		$order_by = array(
			'order' => 'DESC',
			'title' => 'ASC',
            'addtime' => 'DESC',
            'edittime' => 'DESC',
        );
        $args_products['order_by'] = $order_by;
        // $args_products['status'] = 1;

		$products =  $this->M_shops_rows->gets_products($args_products);
		$this->_data['products'] = $products;
		//debug_arr($products, TRUE);
		// pre($products);
		// die;

		$this->_data['title'] = $title;
		$this->_data['main_content'] = 'shops/admin/view_page_order_content';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function index() {
		$this->_initialize_admin();
		$this->redirect_admin();
		/*
		$rows = $this->gets(array(
			'order_by' => array(
				'shops_order.created' => 'ASC',
			)
		));
		if(is_array($rows) && !empty($rows)){
			foreach ($rows as $value) {
				$order_code = ORDER_CODE_PREFIX . (int) str_replace(ORDER_CODE_PREFIX, '', $value['order_code']);
				echo "$order_code<br>";
				$data = array(
					'order_code' => $order_code,
				);
				$this->M_shops_orders->update($value['order_id'], $data);
			}
		}
		// var_dump($rows);
		die;
		*/

		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_css_admin[] = array(
			'folder' => 'bootstrap-datepicker/css',
			'name' => 'bootstrap-datepicker3',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/js',
			'name' => 'bootstrap-datepicker',
		);
		$this->_plugins_script_admin[] = array(
			'folder' => 'bootstrap-datepicker/locales',
			'name' => 'bootstrap-datepicker.vi.min',
		);

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
			'folder' => 'shops',
			'name' => 'admin-order',
		);
		$this->set_modules();

		$get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'orders';
		$args = $this->default_args();

		//theo ngay
		if (isset($get['fromday']) && trim($get['fromday']) != '') {
			$args['start_date_start'] = get_start_date($get['fromday']);
		}
		if (isset($get['today']) && trim($get['today']) != '') {
			$args['start_date_end'] = get_end_date($get['today']);
		}

		if (isset($get['q']) && trim($get['q']) != '') {
			$args['q'] = $get['q'];
		}

		$total = $this->counts($args);
		$perpage = isset($get['per_page']) ? $get['per_page'] : $this->config->item('per_page');
		$segment = 3;

		$this->load->library('pagination'); # Tải bộ thư viện Pagination Class của CodeIgniter
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

		$config['next_link'] = $this->lang->line('next_page') . ' &rarr;';
		$config['next_tag_open'] = '<li class="next page">';
		$config['next_tag_close'] = '</li>';

		$config['prev_link'] = '&larr; ' . $this->lang->line('prev_page');
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
        } else {
            $config['base_url'] = get_admin_url($this->_module_slug);
        }
		$config['uri_segment'] = $segment;
		$this->pagination->initialize($config);

		$pagination = $this->pagination->create_links();
		$offset = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
		
		$this->_data['rows'] = $this->gets($args, $perpage, $offset);
		$this->_data['pagination'] = $pagination;

		$this->_data['breadcrumbs_module_func'] = 'Danh sách đơn đặt hàng';
		$this->_data['title'] = 'Đơn đặt hàng' . ' - ' . $this->_data['title'];
		$this->_data['main_content'] = 'shops/admin/view_page_order';
		$this->load->view('layout/admin/view_layout', $this->_data);
	}

	function admin_export_excel() {
        $this->_initialize_admin();
		$this->redirect_admin();

        $get = $this->input->get();
		$this->_data['get'] = $get;

		$this->_module_slug = 'orders';
		$args = $this->default_args();
		//theo ngay
		if (isset($get['fromday']) && trim($get['fromday']) != '') {
			$args['start_date_start'] = get_start_date($get['fromday']);
		}
		if (isset($get['today']) && trim($get['today']) != '') {
			$args['start_date_end'] = get_end_date($get['today']);
		}

		if (isset($get['q']) && trim($get['q']) != '') {
			$args['q'] = $get['q'];
		}

        $rows = $this->gets($args);
        if (!is_array($rows) && empty($rows)) {
            $notify_type = 'danger';
            $notify_content = 'Chưa có dữ liệu!';
            $this->set_notify_admin($notify_type, $notify_content);
            redirect(get_admin_url($this->_module_slug));
        }

        $this->load->library('excel');

        $glue = '|';
        $firstColumn = 'A';
        $lastColumn = 'I';
        $letterColumn = range($firstColumn, 'I');
        $hideColumn = 'J';

        $numberFormat = '#,##0';

        $styleAlignmentCenter = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
        );

        $styleAlignmentRight = array(
            'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_RIGHT,
        );

        $styleHeader = array(
            'name' => 'Arial',
            'bold' => true,
            'color' => array(
                'rgb' => '333300',
            ),
        );

        $styleHighlight = array(
            'font' => array(
                'bold' => true,
                'color' => array('rgb' => '3582F4'),
            ),
        );

        $this->excel->getProperties()->setCreator("Admin")
            ->setLastModifiedBy("Admin")
            ->setTitle("Danh sách đơn hàng")
            ->setSubject("Danh sách đơn hàng")
            ->setDescription("Danh sách đơn hàng")
            ->setKeywords("Danh sách đơn hàng")
            ->setCategory("Danh sách đơn hàng");
        $this->excel->getActiveSheet()->setTitle('Danh sách đơn hàng');

        $this->excel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Mã đơn hàng')
            ->setCellValue('B1', "Khách hàng")
            ->setCellValue('C1', "Email")
            ->setCellValue('D1', "Điện thoại")
            ->setCellValue('E1', "Địa chỉ")
            ->setCellValue('F1', 'Ngày đặt')
            ->setCellValue('G1', 'Ghi chú')
            ->setCellValue('H1', 'Trạng thái thanh toán')
            ->setCellValue('I1', 'Thành tiền (vnđ)');

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFill()->getStartColor()->setARGB('FFFF00');
        // Add some data
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

        //Header
        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . "1")->getFont()->applyFromArray($styleHeader);

        //Alignment
        $this->excel->getActiveSheet()->getStyle('F1')->getAlignment()->applyFromArray($styleAlignmentCenter);
        // $this->excel->getActiveSheet()->getStyle('P1')->getAlignment()->applyFromArray($styleAlignmentCenter);

        $this->excel->getActiveSheet()->getStyle('I1')->getAlignment()->applyFromArray($styleAlignmentRight);

        //Highlight Header
        // $fillHighlightHeader = '92d050';
        // $this->excel->getActiveSheet()->getStyle('W1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('Z1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AA1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);
        // $this->excel->getActiveSheet()->getStyle('AB1')->getFill()->getStartColor()->setARGB($fillHighlightHeader);

        foreach ($letterColumn as $column) {
            $this->excel->getActiveSheet()->getColumnDimension($column)->setAutoSize(true);
        }
        $order_status = $this->config->item('order_status');
        $i = 2;
        foreach ($rows as $row) {
            $this->excel->setActiveSheetIndex(0)
                ->setCellValue('A' . $i, $row['order_code'])
                ->setCellValue('B' . $i, $row['order_ship_full_name'])
                ->setCellValue('C' . $i, $row['order_email'])
                ->setCellValue('D' . $i, $row['order_phone'])
                ->setCellValue('E' . $i, $row['order_ship_address'])
                ->setCellValue('F' . $i, date('d/m/Y', $row['created']))
                ->setCellValue('G' . $i, $row['order_note'])
                ->setCellValue('H' . $i, display_value_array($order_status, $row['transaction_status']))
                ->setCellValue('I' . $i, $row['order_monetized']);

            $this->excel->getActiveSheet()->setCellValueExplicit('A' . $i, strval($row['order_code']), PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->setCellValueExplicit('D' . $i, strval($row['order_phone']), PHPExcel_Cell_DataType::TYPE_STRING);
            $this->excel->getActiveSheet()->getStyle('F' . $i)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
            $this->excel->getActiveSheet()->getStyle('F' . $i)->getAlignment()->applyFromArray($styleAlignmentCenter);
            $this->excel->getActiveSheet()->getStyle('I' . $i)->getNumberFormat()->setFormatCode($numberFormat);

            $i++;
        }

        $this->excel->getActiveSheet()->getStyle($firstColumn . "1:" . $lastColumn . ($i - 1))->applyFromArray(
            array(
                'borders' => array(
                    'allborders' => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                    )
                ),
                'font' => array(
                    'size' => 13
                )
            )
        );

        $filename = 'Danh-sach-don-hang_' . date('Y-m-d_H-i-s') . '.xlsx';
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
        $objWriter->save('php://output');
    }

	function update_transaction_status($order_id, $transaction_status) {
		$data = array(
			'transaction_status' => $transaction_status,
		);

		return $this->M_shops_orders->update($order_id, $data);
	}

	function delete() {
		$this->_initialize_admin();
		$this->redirect_admin();

		$segment = 4;
		$id = ($this->uri->segment($segment) == '') ? 0 : $this->uri->segment($segment);
		$row = $this->get($id);
		if (is_array($row) && !empty($row)) {
			/*
			if ($this->M_shops_orders->delete($id)) {
				$order_id = $row["order_id"];
				modules::run('shops/order_details/admin_delete', $order_id); // xóa chi tiết đơn hàng
				$this->M_users_commission->delete(array('order_id' => $order_id)); // xóa giao dịch
				$notify_type = 'success';
				$notify_content = "Đã xóa đơn đặt hàng!";
			} else {
				$notify_type = 'danger';
				$notify_content = " Đơn đặt hàng chưa được xóa!";
			}
			*/
			if ($row['transaction_status'] == 0) {
				if ($this->M_shops_orders->delete($id)) {
					$order_id = $row["order_id"];
					/*
					$row_commission = $this->M_users_commission->get(array(
			            'order_id' => $order_id
			        ));
			        if(is_array($row_commission) && !empty($row_commission)){
			            if ($this->M_users_commission->delete(array('id' => $row_commission['id']))) {
		                    $user_id = $row_commission['user_id'];
		                    $args_package = array(
		                        'order_id' => $order_id,
		                        'in_action' => array('SELL', 'BUY', 'BUY_SYSTEM'),
		                    );
		                    $this->M_users_commission->delete($args_package);
		                    $args_package = array(
		                        'order_id' => $order_id,
		                        'in_action' => array('SUB_BUY_ROOT', 'SUB_BUY', 'SYSTEM'),
		                        'extend_by' => $user_id
		                    );
		                    $this->M_users_commission->delete($args_package);
		                }
	                }
	                */
					modules::run('shops/order_details/admin_delete', $order_id); // xóa chi tiết đơn hàng
					$this->M_users_commission->delete(array('order_id' => $order_id)); // xóa giao dịch
					$notify_type = 'success';
					$notify_content = "Đã xóa đơn đặt hàng!";
				} else {
					$notify_type = 'danger';
					$notify_content = " Đơn đặt hàng chưa được xóa!";
				}
			} else {
				$notify_type = 'danger';
				$notify_content = 'Không thể xóa đơn hàng này!';
			}
		} else {
			$notify_type = 'warning';
			$notify_content = " Đơn đặt hàng này không tồn tại!";
		}
		$this->set_notify_admin($notify_type, $notify_content);
		redirect(get_admin_url('orders'));
	}

	function get_num_all_data() {
		$args = $this->default_args();
		return $this->counts($args);
	}

	function get_num_new_data() {
		$args = $this->default_args();
		$args['order_viewed'] = 0;
		return $this->counts($args);
	}

	function get_data_in_customer_id($customer_id = 0) {
		$args = $this->default_args();
		$args['order_user_id'] = $customer_id;
		return $this->gets($args);
	}

}

/* End of file Orders.php */
/* Location: ./application/modules/shops/controllers/Orders.php */
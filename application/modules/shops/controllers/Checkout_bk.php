<?php
if (!defined('BASEPATH')) {
	exit('No direct script access allowed');
}
include_once APPPATH . '/modules/layout/controllers/Layout.php';

class Checkout extends Layout {

	function __construct() {
		parent::__construct();
		$this->_data['breadcrumbs_module_name'] = 'Giỏ hàng';
	}

	function site_index() {
		$this->_initialize();
		modules::run('users/require_logged_in');
		if (!$this->cart->contents()) {
			redirect(site_url('gio-hang'));
		}
		// echo "<pre>";
		// print_r($this->cart->contents());
		// echo "</pre>";
		// die();

		$post = $this->input->post();
		if (!empty($post)) {
			$cart = $this->cart->contents();
			$order_discount_percent = $order_discount = $order_monetized = 0;
			$order_total = $this->cart->total();
			if($order_total >= 4000000){
				$order_discount_percent = 10;
			}elseif($order_total >= 2000000){
				$order_discount_percent = 5;
			}
			$order_discount = $order_total * $order_discount_percent / 100;
			$order_monetized = $order_total - $order_discount;

			$customer_id = isset($this->_data['userid']) ? $this->_data['userid'] : 0;
			$customer = modules::run('users/get', $customer_id);
			$time = time();

			$order_code = $this->get_max_code();
			if (!modules::run('shops/orders/check_order_code_availablity', $order_code)) {
				$order_code = $this->get_max_code();
			}
			$order_agrs = array(
				'order_code' => $order_code,
				'order_email' => $this->input->post('email'),
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
				'order_shipping' => 0,
				'order_note' => $this->input->post('order_note'),
				'forms_of_payment' => $this->input->post('forms_of_payment'),
				'admin_id' => 0,
				'transaction_status' => 0,
				'order_viewed' => 0,
				'post_ip' => $this->input->ip_address(),
				'expiry_time' => $time + 3600 * 24 * 3, //3 ngày
				'created' => $time,
			);
			$order_id = modules::run('shops/orders/site_add', $order_agrs); // thêm đơn hàng

			if ($order_id != 0) {
				$history_container = array();
				$history_current = array();
				foreach ($cart as $item) {
					$product_id = $item['id'];
					$F0 = $F1 = $F2 = $F3 = $F4 = $F5 = $F6 = $F3 = $F7 = $F8 = $F9 = $F10 = 0;
					$name = $item['name'];
					$price = $item['unit_price'];
					$promotion_price = $item['price'];;
					//$promotion_price = $price * $F0;
					$quantity = $item['qty'];
					$monetized = $item['subtotal'];
					$product = modules::run('shops/rows/get', $product_id);
					if(is_array($product) && !empty($product)){
						$F0 = $product['F0'];
						$F1 = $quantity * $product['F1'];
						$F2 = $quantity * $product['F2'];
						$F3 = $quantity * $product['F3'];
						$F4 = $quantity * $product['F4'];
						$F5 = $quantity * $product['F5'];
						$F6 = $quantity * $product['F6'];
						$F7 = $quantity * $product['F7'];
						$F8 = $quantity * $product['F8'];
						$F9 = $quantity * $product['F9'];
						$F10 = $quantity * $product['F10'];
						//$name = $product['title'];
						//$price = $product['price'];
					}
					//$monetized = $promotion_price * $quantity;
					$order_detail_agrs = array(
						'order_id' => $order_id,
						'name' => $name,
						'product_id' => $product_id,
						'price' => $price,
						'promotion_price' => $promotion_price,
						'percent_discount' => $F0,
						'quantity' => $quantity,
						'monetized' => $monetized,
					);
					$bool = modules::run('shops/order_details/site_add', $order_detail_agrs);
					if (!$bool) {
						$err = TRUE;
					}else{
						$payment = 'CREDIT_CARD';
						$history = array();
						$action = 'SELL';
	        			$value_cost = $quantity * $price;
	        			// $percent = $F0;
	        			// $value = $monetized;
	        			$value = $monetized;
	        			$percent = 100 * $monetized/$value_cost;
	        			$data_commission = array(
	        				'order_id' => $order_id,
	        				'product_id' => $product_id,
	        			    'user_id' => 1,
	        			    'extend_by' => NULL,
	        			    'action' => $action,
	        			    'value_cost' => $value_cost,
	        			    'payment' => $payment,
	        			    'percent' => $percent,
	        			    'value' => $value,
	        			    'message' => 'Người dùng bán hàng',
	        			    'status' => 0,
	        			    'created' => $time
	        			);
	        			modules::run('users/users_commission/add', $data_commission);
	        			$history[] = $data_commission;

	        			$action = 'BUY';
	        			$value_cost = (-1) * $quantity * $price;
	        			// $percent = $F0;
	        			// $value = (-1) * $monetized;
	        			$value = (-1) * $monetized;
	        			$percent = abs(100 * $monetized/$value_cost);
						$data_commission = array(
							'order_id' => $order_id,
							'product_id' => $product_id,
						    'user_id' => $customer_id,
						    'extend_by' => NULL,
						    'action' => $action,
						    'value_cost' => $value_cost,
						    'payment' => $payment,
						    'percent' => $percent,
						    'value' => $value,
						    'message' => 'Người dùng mua sản phẩm',
						    'status' => 0,
						    'created' => $time
						);
						modules::run('users/users_commission/add', $data_commission);
						$history[] = $data_commission;

						$args = modules::run('users/default_args');
						$users = $this->M_users->gets($args);
						$data = get_data_parent_level($users, $customer_id);
						$root = $data['root'];
						$subs = $data['subs'];

						if($root != 0){
							$action = 'SUB_BUY_ROOT';
							$value_cost = $quantity * $price;
							// $percent = $F1;
							// $value = $quantity * $price * $percent / 100;
							$value = $F1;
							$percent =  100 * $value/$value_cost;
							$data_commission = array(
							    'order_id' => $order_id,
							    'product_id' => $product_id,
							    'user_id' => $root,
							    'extend_by' => $customer_id,
							    'action' => $action,
							    'value_cost' => $value_cost,
							    'payment' => $payment,
							    'percent' => $percent,
							    'value' => $value,
							    'message' => 'Người dùng được hưởng hoa hồng từ người dùng cấp dưới trực tiếp mua sản phẩm',
							    'status' => 0,
							    'created' => $time
							);
							modules::run('users/users_commission/add', $data_commission);
							$history[] = $data_commission;
						}
						if(is_array($subs) && !empty($subs)){
							$arr_subs_percent = array($F2, $F3, $F4, $F5, $F6, $F7, $F8, $F9, $F10);
							$i = 0;
							foreach ($subs as $sub) {
								$action = 'SUB_BUY';
								$value_cost = $quantity * $price;
								//$percent = $F2;
								//$value = $quantity * $price * $percent / 100;
								$value = isset($arr_subs_percent[$i]) ? $arr_subs_percent[$i] : 0;
								$i++;
								$percent = 100 * $value/$value_cost;
								$data_commission = array(
									'order_id' => $order_id,
									'product_id' => $product_id,
								    'user_id' => $sub,
								    'extend_by' => $customer_id,
								    'action' => $action,
								    'value_cost' => $value_cost,
								    'payment' => $payment,
								    'percent' => $percent,
								    'value' => $value,
								    'message' => 'Người dùng được hưởng hoa hồng từ người dùng cấp dưới mua sản phẩm',
								    'status' => 0,
								    'created' => $time
								);
								modules::run('users/users_commission/add', $data_commission);
								$history[] = $data_commission;
							}
						}
						$history_container[] = $history;
					}
				}
				$history_current[] = $history_container;
				$history_bool = $this->M_shops_orders->update($order_id, array('history' => serialize($history_current)));

				$site_name = $this->_data['site_name'];
				$subject = "Thông tin mua hàng - " . $this->_data['site_name'];

				$core_message = modules::run('shops/orders/site_html_or_view', $order_id);
				$message = $core_message;
				$data_sendmail = array(
					'sender_email' => $this->_data['email'],
					'sender_name' => $site_name,
					'receiver_email' => array($customer_agrs['email'], $this->_data['email'], 'lenhan10th@gmail.com'),
					'subject' => $subject,
					'message' => $message,
				);
				modules::run('emails/send_mail', $data_sendmail); // gửi mail

				//unset session cart
				$this->cart->destroy();
				redirect(site_url('ket-qua-thanh-toan/' . $order_id));
			} else {
				$notify_type = 'danger';
				$notify_content = '<strong>Có lỗi xảy ra!</strong> Vui lòng thực hiện lại';
				$this->set_notify($notify_type, $notify_content);
			}
		}

		$this->_data['user'] = NULL;
		if ($this->session->has_userdata('logged_in')) {
			$this->_data['user'] = $this->M_users->get_by_username($this->_data['username']);
		}

		$this->_breadcrumbs[] = array(
			'url' => site_url('thanh-toan'),
			'name' => 'Thanh toán',
		);
		$this->set_breadcrumbs();

		$this->_data['title_seo'] = 'Thanh toán' . ' - ' . $this->_data['title_seo'];
		$this->_data['main_content'] = 'layout/site/pages/checkout';
		$this->load->view('layout/site/layout', $this->_data);
	}

	function get_max_code() {
		return modules::run('shops/orders/get_max_code');
		// $order_by = array(
		// 	'order_code' => 'DESC',
		// );
		// $args['order_by'] = $order_by;
		// $rows = modules::run('shops/orders/gets', $args, 1, 0);
		// $code = (int) (isset($rows[0]['order_code']) ? filter_var($rows[0]['order_code'], FILTER_SANITIZE_NUMBER_INT) : 0) + 1;
		// return ORDER_CODE_PREFIX . str_pad($code, ORDER_CODE_LENGHT, "0", STR_PAD_LEFT);
	}

}

/* End of file checkout.php */
/* Location: ./application/modules/shops/controllers/checkout.php */

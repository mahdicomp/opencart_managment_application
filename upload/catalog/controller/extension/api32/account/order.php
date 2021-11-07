<?php
class ControllerExtensionApi32AccountOrder extends Controller {
	public function index() {
	     if($this->valid($this->request->get['token'])){
			$this->load->controller('extension/api32/common/language');
            $this->load->controller('extension/api32/common/currency');
	$json = array();

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id= 0;
		}

		$this->load->model('extension/api/account/order');
	

		$order_total = $this->model_extension_api_account_order->getTotalOrders($customer_id);

		$results = $this->model_extension_api_account_order->getOrders(($page - 1) * 10, 10,$customer_id);

		foreach ($results as $result) {
			$product_total = $this->model_extension_api_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_extension_api_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$json['orders'][] = array(
				'id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])
			);
		}
		
  	if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
 	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
	public function info() {
	   if($this->valid($this->request->get['token'])){
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('account/order');

		if (isset($this->request->post['order_id'])) {
			$order_id = $this->request->post['order_id'];
		} else {
			$order_id = 3;
		}
		
			if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id= 12;
		}

	
		$this->load->model('extension/api/account/order');

		$order_info = $this->model_extension_api_account_order->getOrder($order_id,$customer_id);

		if ($order_info) {
		

			if ($order_info['invoice_no']) {
				$data['invoice_no'] = $order_info['invoice_prefix'] . $order_info['invoice_no'];
			} else {
				$data['invoice_no'] = '';
			}

			$json['order_id'] = $order_id;
			$json['date_added'] = ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($order_info['date_added'])) : date($this->language->get('date_format_short'), strtotime($order_info['date_added']));

			if ($order_info['payment_address_format']) {
				$format = $order_info['payment_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['payment_firstname'],
				'lastname'  => $order_info['payment_lastname'],
				'company'   => $order_info['payment_company'],
				'address_1' => $order_info['payment_address_1'],
				'address_2' => $order_info['payment_address_2'],
				'city'      => $order_info['payment_city'],
				'postcode'  => $order_info['payment_postcode'],
				'zone'      => $order_info['payment_zone'],
				'zone_code' => $order_info['payment_zone_code'],
				'country'   => $order_info['payment_country']
			);

			$json['payment_address'] = str_replace(array("\r\n", "\r", "\n"), '<br />', preg_replace(array("/\s\s+/", "/\r\r+/", "/\n\n+/"), '<br />', trim(str_replace($find, $replace, $format))));

			$json['payment_method'] = $order_info['payment_method'];

			if ($order_info['shipping_address_format']) {
				$format = $order_info['shipping_address_format'];
			} else {
				$format = '{firstname} {lastname}' . "\n" . '{company}' . "\n" . '{address_1}' . "\n" . '{address_2}' . "\n" . '{city} {postcode}' . "\n" . '{zone}' . "\n" . '{country}';
			}

			$find = array(
				'{firstname}',
				'{lastname}',
				'{company}',
				'{address_1}',
				'{address_2}',
				'{city}',
				'{postcode}',
				'{zone}',
				'{zone_code}',
				'{country}'
			);

			$replace = array(
				'firstname' => $order_info['shipping_firstname'],
				'lastname'  => $order_info['shipping_lastname'],
				'company'   => $order_info['shipping_company'],
				'address_1' => $order_info['shipping_address_1'],
				'address_2' => $order_info['shipping_address_2'],
				'city'      => $order_info['shipping_city'],
				'postcode'  => $order_info['shipping_postcode'],
				'zone'      => $order_info['shipping_zone'],
				'zone_code' => $order_info['shipping_zone_code'],
				'country'   => $order_info['shipping_country']
			);

		
			$json['shipping_method'] = $order_info['shipping_method'];

			$this->load->model('catalog/product');
			$this->load->model('tool/upload');

			// Products
			$json['products'] = array();

			$products = $this->model_extension_api_account_order->getOrderProducts($order_id);

			foreach ($products as $product) {
				$option_data = array();

				$options = $this->model_extension_api_account_order->getOrderOptions($order_id, $product['order_product_id']);

				foreach ($options as $option) {
					if ($option['type'] != 'file') {
						$value = $option['value'];
					} else {
						$upload_info = $this->model_tool_upload->getUploadByCode($option['value']);

						if ($upload_info) {
							$value = $upload_info['name'];
						} else {
							$value = '';
						}
					}

					$option_data[] = array(
						'name'  => $option['name'],
						'value' => (utf8_strlen($value) > 20 ? utf8_substr($value, 0, 20) . '..' : $value)
					);
				}

				$product_info = $this->model_catalog_product->getProduct($product['product_id']);

				if ($product_info) {
					$reorder = $this->url->link('account/order/reorder', 'order_id=' . $order_id . '&order_product_id=' . $product['order_product_id'], true);
				} else {
					$reorder = '';
				}
		//	print_r($product_info['image']);
				if ($product_info['image']) {
				    $this->load->model('tool/image');
					$image = $this->model_tool_image->resize($product_info['image'], 120 , 120);
				} else {
					$image = '';
				}

				$json['products'][] = array(
					'name'     => $product['name'],
					'product_id'     => $product['product_id'],
					'image'     => $image,
					'model'    => $product['model'],
					'option'   => $option_data,
					'quantity' => $product['quantity'],
					'price'    => $this->currency->format($product['price'] + ($this->config->get('config_tax') ? $product['tax'] : 0), $order_info['currency_code'], $order_info['currency_value']),
					'total'    => $this->currency->format($product['total'] + ($this->config->get('config_tax') ? ($product['tax'] * $product['quantity']) : 0), $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			// Voucher
			$json['vouchers'] = array();

			$vouchers = $this->model_extension_api_account_order->getOrderVouchers($order_id);

			foreach ($vouchers as $voucher) {
				$json['vouchers'][] = array(
					'description' => $voucher['description'],
					'amount'      => $this->currency->format($voucher['amount'], $order_info['currency_code'], $order_info['currency_value'])
				);
			}

			// Totals
			$json['totals'] = array();

			$totals = $this->model_extension_api_account_order->getOrderTotals($order_id);

			foreach ($totals as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value']),
				);
			}

			$json['comment'] = nl2br($order_info['comment']);

			// History
			$json['histories'] = array();

			$results = $this->model_extension_api_account_order->getOrderHistories($order_id);

			foreach ($results as $result) {
				$json['histories'][] = array(
					'date_added' => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => $result['notify'] ? nl2br($result['comment']) : ''
				);
			}

			
		} else {
			$json['error']=$this->language->get('text_empty');
		}
		
	    	$jsonout['orders']=$json;
		
			if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
 	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

	public function getOrderBystatus() {
	     if($this->valid($this->request->get['token'])){
			$this->load->controller('extension/api32/common/language');
            $this->load->controller('extension/api32/common/currency');
	$json = array();

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		
		if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id= 0;
		}
		
			if (isset($this->request->post['order_status_id'])) {
			$order_status_id = $this->request->post['order_status_id'];
    		} else {
    			$order_status_id= 0;
    		}
		$this->load->model('extension/api/account/order');
	     $results = $this->model_extension_api_account_order->getOrderBystatus($order_status_id,$customer_id);
		foreach ($results as $result) {
			$product_total = $this->model_extension_api_account_order->getTotalOrderProductsByOrderId($result['order_id']);
			$voucher_total = $this->model_extension_api_account_order->getTotalOrderVouchersByOrderId($result['order_id']);

			$json['orders'][] = array(
				'id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'products'   => ($product_total + $voucher_total),
				'total'      => $this->currency->format($result['total'], $result['currency_code'], $result['currency_value'])
			);
		}
		
  	if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
 	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
	private function valid($token) {
	    
    	    if($token==$this->config->get('storeapp_token')){
    	        return true;
    	        
    	    }else {
    	        return false;
    	    }
	   }

}
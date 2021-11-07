<?php
class ControllerExtensionApi32AccountReturn extends Controller {
	private $error = array();

	public function index() {
		

		
    	$this->load->controller('api32/common/language');
    	$this->load->controller('api32/common/currency');
        $this->customer->loginWithId($this->request->post['customer_id']);
		$this->load->model('account/return');

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

		$data['returns'] = array();

		$return_total = $this->model_account_return->getTotalReturns();

		$results = $this->model_account_return->getReturns(($page - 1) * 10, 10);

		foreach ($results as $result) {
			$data['returns'][] = array(
				'return_id'  => $result['return_id'],
				'order_id'   => $result['order_id'],
				'name'       => $result['firstname'] . ' ' . $result['lastname'],
				'status'     => $result['status'],
				'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'href'       => $this->url->link('account/return/info', 'return_id=' . $result['return_id'] . $url, true)
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
		$this->response->setOutput(json_encode($data));
	}

	public function info() {
	    
	    $this->load->controller('api32/common/language');
    	$this->load->controller('api32/common/currency');
        $this->customer->loginWithId($this->request->post['customer_id']);
		$this->load->language('account/return');

		if (isset($this->request->post['return_id'])) {
			$return_id = $this->request->post['return_id'];
		} else {
			$return_id = 0;
		}

	

		$this->load->model('account/return');

		$return_info = $this->model_account_return->getReturn($return_id);

		if ($return_info) {
		
			$data['return_id'] = $return_info['return_id'];
			$data['order_id'] = $return_info['order_id'];
			$data['date_ordered'] = date($this->language->get('date_format_short'), strtotime($return_info['date_ordered']));
			$data['date_added'] = date($this->language->get('date_format_short'), strtotime($return_info['date_added']));
			$data['firstname'] = $return_info['firstname'];
			$data['lastname'] = $return_info['lastname'];
			$data['email'] = $return_info['email'];
			$data['telephone'] = $return_info['telephone'];
			$data['product'] = $return_info['product'];
			$data['model'] = $return_info['model'];
			$data['quantity'] = $return_info['quantity'];
			$data['reason'] = $return_info['reason'];
			$data['opened'] = $return_info['opened'] ? $this->language->get('text_yes') : $this->language->get('text_no');
			$data['comment'] = nl2br($return_info['comment']);
			$data['action'] = $return_info['action'];

			$data['histories'] = array();

			$results = $this->model_account_return->getReturnHistories($this->request->get['return_id']);

			foreach ($results as $result) {
				$data['histories'][] = array(
					'date_added' => date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'status'     => $result['status'],
					'comment'    => nl2br($result['comment'])
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
		$this->response->setOutput(json_encode($data));
		}
	}

	public function add() {
		$this->load->language('account/return');
        $this->load->controller('api32/common/language');
    	$this->load->controller('api32/common/currency');
        $this->customer->loginWithId($this->request->post['customer_id']);
		$this->load->model('account/return');

		

		$this->load->model('account/order');

		if (isset($this->request->post['order_id'])) {
			$order_info = $this->model_account_order->getOrder($this->request->post['order_id']);
		}

		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_info = $this->model_catalog_product->getProduct($this->request->post['product_id']);
		}

		if (isset($this->request->post['order_id'])) {
			$data['order_id'] = $this->request->post['order_id'];
		} elseif (!empty($order_info)) {
			$data['order_id'] = $order_info['order_id'];
		} else {
			$data['order_id'] = '';
		}

		if (isset($this->request->post['date_ordered'])) {
			$data['date_ordered'] = $this->request->post['date_ordered'];
		} elseif (!empty($order_info)) {
			$data['date_ordered'] = date('Y-m-d', strtotime($order_info['date_added']));
		} else {
			$data['date_ordered'] = '';
		}

		if (isset($this->request->post['firstname'])) {
			$data['firstname'] = $this->request->post['firstname'];
		} elseif (!empty($order_info)) {
			$data['firstname'] = $order_info['firstname'];
		} else {
			$data['firstname'] = $this->customer->getFirstName();
		}

		if (isset($this->request->post['lastname'])) {
			$data['lastname'] = $this->request->post['lastname'];
		} elseif (!empty($order_info)) {
			$data['lastname'] = $order_info['lastname'];
		} else {
			$data['lastname'] = $this->customer->getLastName();
		}

		if (isset($this->request->post['email'])) {
			$data['email'] = $this->request->post['email'];
		} elseif (!empty($order_info)) {
			$data['email'] = $order_info['email'];
		} else {
			$data['email'] = $this->customer->getEmail();
		}

		if (isset($this->request->post['telephone'])) {
			$data['telephone'] = $this->request->post['telephone'];
		} elseif (!empty($order_info)) {
			$data['telephone'] = $order_info['telephone'];
		} else {
			$data['telephone'] = $this->customer->getTelephone();
		}

		if (isset($this->request->post['product'])) {
			$data['product'] = $this->request->post['product'];
		} elseif (!empty($product_info)) {
			$data['product'] = $product_info['name'];
		} else {
			$data['product'] = '';
		}

		if (isset($this->request->post['model'])) {
			$data['model'] = $this->request->post['model'];
		} elseif (!empty($product_info)) {
			$data['model'] = $product_info['model'];
		} else {
			$data['model'] = '';
		}

		if (isset($this->request->post['quantity'])) {
			$data['quantity'] = $this->request->post['quantity'];
		} else {
			$data['quantity'] = 1;
		}

		if (isset($this->request->post['opened'])) {
			$data['opened'] = $this->request->post['opened'];
		} else {
			$data['opened'] = false;
		}

		if (isset($this->request->post['return_reason_id'])) {
			$data['return_reason_id'] = $this->request->post['return_reason_id'];
		} else {
			$data['return_reason_id'] = '';
		}

		$this->load->model('localisation/return_reason');

		$data['return_reasons'] = $this->model_localisation_return_reason->getReturnReasons();

		if (isset($this->request->post['comment'])) {
			$data['comment'] = $this->request->post['comment'];
		} else {
			$data['comment'] = '';
		}

		

		if ($this->config->get('config_return_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_return_id'));

			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/agree', 'information_id=' . $this->config->get('config_return_id'), true), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		if (isset($this->request->post['agree'])) {
			$data['agree'] = $this->request->post['agree'];
		} else {
			$data['agree'] = false;
		}

		if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
 	    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	protected function validate() {
		if (!$this->request->post['order_id']) {
			$this->error['order_id'] = $this->language->get('error_order_id');
		}

		if ((utf8_strlen(trim($this->request->post['firstname'])) < 1) || (utf8_strlen(trim($this->request->post['firstname'])) > 32)) {
			$this->error['firstname'] = $this->language->get('error_firstname');
		}

		if ((utf8_strlen(trim($this->request->post['lastname'])) < 1) || (utf8_strlen(trim($this->request->post['lastname'])) > 32)) {
			$this->error['lastname'] = $this->language->get('error_lastname');
		}

		if ((utf8_strlen($this->request->post['email']) > 96) || !filter_var($this->request->post['email'], FILTER_VALIDATE_EMAIL)) {
			$this->error['email'] = $this->language->get('error_email');
		}

		if ((utf8_strlen($this->request->post['telephone']) < 3) || (utf8_strlen($this->request->post['telephone']) > 32)) {
			$this->error['telephone'] = $this->language->get('error_telephone');
		}

		if ((utf8_strlen($this->request->post['product']) < 1) || (utf8_strlen($this->request->post['product']) > 255)) {
			$this->error['product'] = $this->language->get('error_product');
		}

		if ((utf8_strlen($this->request->post['model']) < 1) || (utf8_strlen($this->request->post['model']) > 64)) {
			$this->error['model'] = $this->language->get('error_model');
		}

		if (empty($this->request->post['return_reason_id'])) {
			$this->error['reason'] = $this->language->get('error_reason');
		}

		

		if ($this->config->get('config_return_id')) {
			$this->load->model('catalog/information');

			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_return_id'));

			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}

		return !$this->error;
	}

	public function confirm() {
		$this->load->language('account/return');
			$this->load->model('account/return');
        $this->load->controller('api32/common/language');
    	$this->load->controller('api32/common/currency');
        $this->customer->loginWithId($this->request->post['customer_id']);
        if ( $this->validate()) {
			$data['result'] = 'ok';	
				$this->model_account_return->addReturn($this->request->post);
		}else {
		    $data['result'] = 'error';
		}
	
		
			if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
 	    $this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}
}

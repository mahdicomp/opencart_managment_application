<?php
class ControllerExtensionApi32AccountWishList extends Controller {
	public function index() {
		//echo 1;
        $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		$this->load->language('account/wishlist');

		$this->load->model('account/wishlist');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
          $this->customer->loginWithId($this->request->post['customer_id']);
		if (isset($this->request->post['remove'])) {
			// Remove Wishlist
			$this->model_account_wishlist->deleteWishlist($this->request->post['remove']);
            	$data['success'] = "ok";
			$this->session->data['success'] = $this->language->get('text_remove');

		}
    // echo 1;
        
		$data['products'] = array();

		$results = $this->model_account_wishlist->getWishlist();

		foreach ($results as $result) {
			$product_info = $this->model_catalog_product->getProduct($result['product_id']);

			if ($product_info) {
				if ($product_info['image']) {
					$image = $this->model_tool_image->resize($product_info['image'],120, 120);
				} else {
					$image = false;
				}

				if ($product_info['quantity'] <= 0) {
					$stock = $product_info['stock_status'];
				} elseif ($this->config->get('config_stock_display')) {
					$stock = $product_info['quantity'];
				} else {
					$stock = $this->language->get('text_instock');
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				$data['products'][] = array(
					'id' => $product_info['product_id'],
					'thumb'      => $image,
					'name'       => $product_info['name'],
					'model'      => $product_info['model'],
					'stock'      => $stock,
					'price'      => $price,
					'special'    => $special,
					'href'       => $this->url->link('extension/restapi/product/product', 'product_id=' . $product_info['product_id']),
					'remove'     => $this->url->link('extension/restapi/account/wishlist', 'remove=' . $product_info['product_id'])
				);
			} else {
				$this->model_account_wishlist->deleteWishlist($result['product_id']);
			}
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

	public function add() {
	    $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		$this->load->language('account/wishlist');
       
		$json = array();

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);

		if ($product_info) {
		 if(isset($this->request->post['customer_id'])){
			 $this->customer->loginWithId($this->request->post['customer_id']);
				// Edit customers cart
				$this->load->model('account/wishlist');

				$this->model_account_wishlist->addWishlist($this->request->post['product_id']);

				$json['success'] = sprintf($this->language->get('text_success'), $this->url->link('extension/restapi/product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('extension/restapi/account/wishlist'));
        	} else {
				$json['error'] = sprintf($this->language->get('text_login'), $this->url->link('extension/restapi/account/login', '', true), $this->url->link('extension/restapi/account/register', '', true), $this->url->link('extension/restapi/product/product', 'product_id=' . (int)$this->request->post['product_id']), $product_info['name'], $this->url->link('extension/restapi/account/wishlist'));

			}
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

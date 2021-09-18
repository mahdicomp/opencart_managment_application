<?php
class ControllerExtensionApi32CheckoutShipping extends Controller {
    
  
	public function address() {
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		$this->load->language('extension/api/shipping');

		$json = array();
		$customer_id=$this->request->post['customer_id'];
		$addresses=$this->getAddresses($customer_id) ;
		if($addresses){
		   $json['addresses']=$addresses;
		    
		}else{
		    $json['error']= $this->language->get('error_address');
		    
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function methods() {
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('extension/api/shipping');


		$json = array();
	
		if (isset($this->request->post['address_id']) && isset($this->request->post['customer_id'])) {
        	$this->customer->loginWithId($this->request->post['customer_id']);
        	if(isset($this->request->post['session_id'])){
        		$session_id=	$this->request->post['session_id']; 
        	 }elseif(isset($this->request->post['id'])){
        		 $session_id=	$this->request->post['id']; 
        	 }
        	$this->session->start($session_id); 
        	 
           
        	if ($this->cart->hasShipping()) {
        		$this->load->model('extension/api/account/address');
        		
        		$method_data = array();
                $method_data2 = array();
        	    $shipping_address=$this->model_extension_api_account_address->getAddress($this->request->post['address_id'],$this->request->post['customer_id']);
        	    
        		$json['shipping_methods'] = array();
        
        		$this->load->model('setting/extension');
        
        		$results = $this->model_setting_extension->getExtensions('shipping');
                 	$ShippingMethod = $this->config->get('storeapp_ShippingMethod');
        		foreach ($results as $result) {
        			if ($this->config->get('shipping_' . $result['code'] . '_status')) {
        				$this->load->model('extension/shipping/' . $result['code']);
      
        				$quote = $this->{'model_extension_shipping_' . $result['code']}->getQuote($shipping_address);
        			
                        if(isset($ShippingMethod[$result['code']]) &&  $ShippingMethod[$result['code']]['icon']){
                           $icon=HTTP_SERVER.'image/'.$ShippingMethod[$result['code']]['icon']; 
                        }else {
                            $icon=false;
                        }
        				if ($quote) {
        		        	$json['shipping_methods'][] = array(
        					'title'      => $quote['title'],
        					'icon'      => $icon,
        					'quote'      => array_values($quote['quote']),
        					'sort_order' => $quote['sort_order'],
        					'error'      => $quote['error']
        				);
        			}
        					
        		}
        				 
        			
        	}
				//echo $a;
	         // print_r($method_data);
				$sort_order = array();
               
			//	foreach ($method_data as $key => $value) {
				//	$sort_order[$key] = $value['sort_order'];
			//	}

                //array_multisort($sort_order, SORT_ASC, $method_data);

		      //$json['shipping_methods'] = $method_data;
		    
		//	print_r($method_data);	
		}
			
		} else {
			$json['error'] = $this->language->get('error_no_shipping');
		}
	 
	    $json['hasShipping']=$this->cart->hasShipping();
		
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

	public function method() {
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('extension/api/shipping');

		// Delete old shipping method so not to cause any issues if there is an error
		unset($this->session->data['shipping_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			if ($this->cart->hasShipping()) {
				// Shipping Address
				if (!isset($this->session->data['shipping_address'])) {
					$json['error'] = $this->language->get('error_address');
				}

				// Shipping Method
				if (empty($this->session->data['shipping_methods'])) {
					$json['error'] = $this->language->get('error_no_shipping');
				} elseif (!isset($this->request->post['shipping_method'])) {
					$json['error'] = $this->language->get('error_method');
				} else {
					$shipping = explode('.', $this->request->post['shipping_method']);

					if (!isset($shipping[0]) || !isset($shipping[1]) || !isset($this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]])) {
						$json['error'] = $this->language->get('error_method');
					}
				}

				if (!$json) {
					$this->session->data['shipping_method'] = $this->session->data['shipping_methods'][$shipping[0]]['quote'][$shipping[1]];

					$json['success'] = $this->language->get('text_method');
				}
			} else {
				unset($this->session->data['shipping_address']);
				unset($this->session->data['shipping_method']);
				unset($this->session->data['shipping_methods']);
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	

}

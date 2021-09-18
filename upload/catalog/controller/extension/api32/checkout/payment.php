<?php
class ControllerExtensionApi32CheckoutPayment extends Controller {

	public function methods() {
	    $customer_id= $this->request->post['customer_id'];
        $address_id=$this->request->post['address_id'];
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('extension/api/payment');
		
		// Delete past shipping methods and method just in case there is an error
		unset($this->session->data['payment_methods']);
		unset($this->session->data['payment_method']);

		$json = array();

		if (isset($this->request->post['address_id']) && isset($this->request->post['customer_id'])) {
		   $customer_id= $this->request->post['customer_id'];
		   if(isset($this->request->post['session_id'])){
			$session_id=	$this->request->post['session_id']; 
		 }elseif(isset($this->request->post['id'])){
			 $session_id=	$this->request->post['id']; 
		 }
		
		    $this->session->data['session_id']=$session_id;

		    $this->customer->loginWithId($this->request->post['customer_id']);
			$this->session->start($session_id);  
			
				// Totals
				$totals = array();
				$taxes = $this->cart->getTaxes();
				$total = 0;

				// Because __call can not keep var references so we put them into an array. 
				$total_data = array(
					'totals' => &$totals,
					'taxes'  => &$taxes,
					'total'  => &$total
				);

				$this->load->model('setting/extension');

				$sort_order = array();

				$results = $this->model_setting_extension->getExtensions('total');

				foreach ($results as $key => $value) {
					$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
				}

				array_multisort($sort_order, SORT_ASC, $results);
               $json['totals']=array();
				foreach ($results as $result) {
					if ($this->config->get('total_' . $result['code'] . '_status')) {
					    
						$this->load->model('extension/total/' . $result['code']);
						//echo $result['code'];
					array_push($json['totals'],$result['code']);
						
						
						// We have to put the totals in an array so that they pass by reference.
						$this->{'model_extension_total_' . $result['code']}->getTotal($total_data,$customer_id,$session_id);
					}
				}
				
					$this->load->model('extension/api/account/address');
			//	echo 3;
			$payment_address=$this->model_extension_api_account_address->getAddress($address_id,$customer_id) ;
           
             ///////////wallet 
             
             
             unset($this->session->data['use_e_wallet']);
             
             $json['wallet_balance'] = 0;
			$json['check_wallet_voucher'] = false;
			if($this->config->get('payment_e_wallet_payment_status')){
				if(isset($this->session->data['vouchers_key']))
					$json['check_wallet_voucher'] = isset($this->session->data['vouchers'][$this->session->data['vouchers_key']]);
				$this->load->model('account/e_wallet');
				$wallet_balance            = $this->model_account_e_wallet->getBalance();
				$json['wallettext']        = '';
				$json['wallet_total']      = $total;
				$json['e_wallet_title']    = $this->config->get('e_wallet_title');
				$json['e_wallet_payments'] = $this->config->get('e_wallet_payments');
				if(!$json['e_wallet_payments']){
					$json['e_wallet_payments']=array();
				}
				$json['wallet_balance'] = $wallet_balance;
				$remain_balance = $total - $wallet_balance;
				$json['remain_wallet_balance'] = $remain_balance;
				if((int)$wallet_balance > 0 && (float)$total > (float)$wallet_balance){
					if(isset($this->session->data['currency'])){
						$config_currency =$this->session->data['currency'];
					}else{
						$config_currency =$this->config->get('config_currency');
					}
					$wallettext = "Payment to be made <b>".$this->currency->format($total,$config_currency).'</b>';
					$wallettext .= " - ".$data['e_wallet_title'].' <b>'.$this->currency->format($wallet_balance,$config_currency).' </b>';
					$wallettext .= " Select an option to pay balance <b>".$this->currency->format($remain_balance,$config_currency)."</b>.";
					$json['wallettext'] = $wallettext;
				}else if((int)$wallet_balance > 0 && (float)$total < (float)$wallet_balance){
					$json['wallettext'] = "Awesome! You have sufficient balance in your ".$data['e_wallet_title'].'.';
				}

			}
             
             ///end wallet
				// Payment Methods
				$json['payment_methods'] = array();

				$this->load->model('setting/extension');

				$results = $this->model_setting_extension->getExtensions('payment');

				$recurring = $this->cart->hasRecurringProducts();
                	$PaymentMethod = $this->config->get('storeapp_PaymentMethod');
                       
				foreach ($results as $result) {
					if ($this->config->get('payment_' . $result['code'] . '_status')) {
						$this->load->model('extension/payment/' . $result['code']);

						$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($payment_address, $total);
                   
                   
						if ($method) {
						    if(isset($PaymentMethod[$result['code']]) && $PaymentMethod[$result['code']]['icon'] ){
                           $icon=HTTPS_SERVER.'image/'.$PaymentMethod[$result['code']]['icon']; 
                        }else {
                            $icon=false;
                        }
						    $method = array_merge($method, array("icon"=>$icon));
						    
							if ($recurring) {
								if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
									$json['payment_methods'][] = $method;
										$method_data[$result['code']] = $method;
								}
							} else {
								$json['payment_methods'][] = $method;
									$method_data[$result['code']] = $method;
							}
						}
					}
				}

				$sort_order = array();

				foreach ($json['payment_methods'] as $key => $value) {
					$sort_order[$key] = $value['sort_order'];
				}

				array_multisort($sort_order, SORT_ASC, $json['payment_methods']);
				
                ///new wallet
			$json['e_method']=0;
			//print_r($method_data);
			if(isset($method_data['e_wallet_payment']) && (int)$json['wallet_balance'] > 0 && !$json['check_wallet_voucher']){
				$e_wallet_payment = $method_data['e_wallet_payment'];
				unset($json['payment_methods']['e_wallet_payment']);
				$json['e_method'] = 123;
			}

			if($json['check_wallet_voucher']){
				unset($json['payment_methods']['e_wallet_payment']);
				foreach($json['payment_methods'] as $payment_method){
					if(!in_array($payment_method['code'],$json['e_wallet_payments'])){
						unset($json['payment_methods'][$payment_method['code']]);
					}
				}
			}
			///end wallet
				
			}else {
			    	$json['error'] = $this->language->get('error_no_payment');
			    
			}
			
			$json['bank'] = html_entity_decode($this->config->get('payment_bank_transfer_bank' . $this->config->get('config_language_id')));
			
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
		$this->load->language('extension/api/payment');

		// Delete old payment method so not to cause any issues if there is an error
		unset($this->session->data['payment_method']);

		$json = array();

		if (!isset($this->session->data['api_id'])) {
			$json['error'] = $this->language->get('error_permission');
		} else {
			// Payment Address
			if (!isset($this->session->data['payment_address'])) {
				$json['error'] = $this->language->get('error_address');
			}

			// Payment Method
			if (empty($this->session->data['payment_methods'])) {
				$json['error'] = $this->language->get('error_no_payment');
			} elseif (!isset($this->request->post['payment_method'])) {
				$json['error'] = $this->language->get('error_method');
			} elseif (!isset($this->session->data['payment_methods'][$this->request->post['payment_method']])) {
				$json['error'] = $this->language->get('error_method');
			}

			if (!$json) {
				$this->session->data['payment_method'] = $this->session->data['payment_methods'][$this->request->post['payment_method']];

				$json['success'] = $this->language->get('text_method');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

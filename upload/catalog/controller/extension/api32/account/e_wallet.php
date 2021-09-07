<?php
class ControllerExtensionApi32accountewallet extends Controller {
	private $error = array();
	public function index() {
	
         	if($this->valid($this->request->get['token'])){
	    	    $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		 $this->customer->loginWithId($this->request->post['customer_id']);
		 
		$this->load->language('account/e_wallet');
	
		$this->load->model('account/e_wallet');
		$page = 1;
		$limit = 20;
		$url ='';
		//$data['ccurrency'] = $this->currency;
		if(isset($this->request->post['page'])) $page = (int)$this->request->post['page'];
		if(isset($this->request->get['limit'])) $limit = (int)$this->request->get['limit'];
		$filter = array(
			'start' => ($page - 1) * $limit,
			'limit' => $limit
		);
	////	$filter['datefrom'] = $data['datefrom'] = date('m/d/Y');
	//	$filter['dateto'] = $data['dateto'] = date('m/d/Y');
		
		$this->load->model('tool/image');
		$data['e_wallet_icon_url'] = $this->model_tool_image->resize($this->config->get('e_wallet_icon'), 30,30);
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];
			$data['config_currency'] =$this->session->data['currency'];
		}else{
			$config_currency =$this->config->get('config_currency');
			$data['config_currency'] =$this->config->get('config_currency');
		}
		$bank = $this->model_account_e_wallet->getbank();
		if($bank){
			$data['bank'] = array(
				'name' => $bank['bank_name'],
				'branch_number' => $bank['branch_code'],
				'swift_code' => $bank['swift'],
				'ifsc_code' => $bank['ifsc'],
				'account_name' => $bank['ac_name'],
				'account_number' => $bank['ac_no'],
			);
		}else{
			$data['bank'] = array('name'=>'','branch_number'=>'','swift_code'=>'','ifsc_code'=>'','account_name'=>'','account_number'=>'');
		}
		$data['balance'] = $this->currency->format($this->model_account_e_wallet->getBalance(),$config_currency);
 
		$e_wallet_list = $this->model_account_e_wallet->gettransaction($filter);
		$totaltrasaction = $this->model_account_e_wallet->gettransactiontotal($filter);
		$data['openningbalance'] = $this->model_account_e_wallet->getopenningbalance($filter);
		$data['e_wallet_list'] = array();
		$data['count']=false;
		foreach ($e_wallet_list as $v){
			$data['e_wallet_list'][] = array(
				'transaction_id' => $v['transaction_id'],
				'description' => $v['description'],
				'credit' => ($v['price'] >= 0 ? $this->currency->format($v['price'],$config_currency) : ''),
				'debit' => ($v['price'] < 0 ? $this->currency->format(abs($v['price']),$config_currency) : ''),
				'balance' => $this->currency->format($v['balance'],$config_currency),
				'o_credit' => ($v['price'] > 0 ? $v['price'] : 0),
				'o_debit' => ($v['price'] < 0 ? abs($v['price']) :0),
				'o_balance' => $v['balance'],
				'date' => date('d-m-Y h:i A',strtotime($v['date_added']))
			); 
			$data['count']=true;
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
	public function send_money(){
		if($this->valid($this->request->get['token'])){
	        $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		
        $this->customer->loginWithId($this->request->post['customer_id']);
         	 
	//	$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['email'])){
			$this->load->model('account/customer');
			if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] <= 0){
				$data['error_warning'] = "Invalid Amount..!";
			}
			if(!$data['error_warning']){
				$s_currency = $this->session->data['currency'];
				$c_currency = $this->config->get('config_currency');
				$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $c_currency);
				$amountmax = $this->currency->format((float)$this->config->get('e_wallet_max_send'),$s_currency);
				$amountmin = $this->currency->format((float)$this->config->get('e_wallet_min_send'),$s_currency);
				if((int)$amount > $this->config->get('e_wallet_max_send')){
					$data['error_warning'] = 'Please Enter Below '.$amountmax.' Amount..!';
				}else if((int)$amount < $this->config->get('e_wallet_min_send')){
					$data['error_warning'] = 'Please Enter Above '.$amountmin.' Amount..!';
				}else if((float)$balance < (float)$amount){
					$data['error_warning'] = "Insufficient Balance .!";
				}else{
					$email = $this->request->post['email'];
					$per = DB_PREFIX;
					$c_info = $this->db->query("SELECT * FROM  `{$per}customer` WHERE  (`email` LIKE '{$email}' or `telephone` = '{$email}')
						AND (`email` NOT LIKE '{$this->customer->getEmail()}' AND `telephone` != '{$this->customer->getTelephone()}')");
					if($c_info->num_rows != 1){
						$data['error_warning'] = 'Invalid Email Id / Telephone Or not Exists..';
					}
					$c_info = $c_info->row;
				}
			}
			if(!$data['error_warning']){
				$d = array(
					'customer_id' => $c_info['customer_id'],
					'name' => $c_info['firstname'].' '.$c_info['lastname'],
					'amount' => $amount,
					'email' => $c_info['email'],
				);
				$this->model_account_e_wallet->sendmoney($d);
				$data['success']=true;
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
	}
	public function withdrawreq(){
	    	if($this->valid($this->request->get['token'])){
		
		$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['amount'])){
			$this->load->model('account/customer');
			if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] <= 0){
				$data['error_warning'] = "Invalid Amount..!";
			}
			if(!$data['error_warning']){
				$s_currency = $this->session->data['currency'];
				$c_currency = $this->config->get('config_currency');
				$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $c_currency);
				$amountmax = $this->currency->format((float)$this->config->get('e_wallet_max_send'),$s_currency);
				$amountmin = $this->currency->format((float)$this->config->get('e_wallet_min_send'),$s_currency);
				if((int)$amount > $this->config->get('e_wallet_max_withdraw')){
					$data['error_warning'] = 'Please Enter Below '.$amountmax.' Amount..!';
				}else if((int)$amount < $this->config->get('e_wallet_min_withdraw')){
					$data['error_warning'] = 'Please Enter Above '.$amountmin.' Amount..!';
				}else if((float)$balance < (float)$amount){
					$data['error_warning'] = "Insufficient Balance .!";
				}
			}
			 
			if(!$data['error_warning']){
				$d =array(					
					'amount' => $amount,				
				);
				$this->model_account_e_wallet->withdrawmoney($d);
				$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
				die;
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

	
	}
	
	
	public function useWallet()	{
	    if($this->valid($this->request->get['token'])){
	        $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		$this->load->language('account/wk_wallet_system');
		$json = array();
		if(isset($this->request->post['id'])){
				 $session_id=	$this->request->post['id']; 
				 $this->session->start($session_id); 
			 }
		//	 echo 1;
			
        $this->customer->loginWithId($this->request->post['customer_id']);
        unset($this->session->data['use_e_wallet']);
		if ($this->customer->getId()) {
         //echo 1;
			if($this->config->get('payment_e_wallet_payment_status') && isset($this->request->post['use_e_wallet']) && $this->request->post['payment_method'] != 'e_wallet_payment'){
			$this->session->data['use_e_wallet'] = true;
		//	echo 1;
	    	}

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
          // print_r(	$results);
			foreach ($results as $key => $value) {
				$sort_order[$key] = $this->config->get('total_' . $value['code'] . '_sort_order');
			}

			array_multisort($sort_order, SORT_ASC, $results);

			foreach ($results as $result) {
				if ($this->config->get('total_' . $result['code'] . '_status')) {
					$this->load->model('extension/total/' . $result['code']);
                   // echo $result['code'];
					// We have to put the totals in an array so that they pass by reference.
					$this->{'model_extension_total_' . $result['code']}->getTotal($total_data);
				}
			}
			
			$json['totals'] = array();

			foreach ($totals as $total) {
				$json['totals'][] = array(
					'title' => $total['title'],
					'text'  => $this->currency->format($total['value'], $this->session->data['currency'])
				);
			}

			if (isset($this->session->data['wk_wallet_payment'])) {
				if (isset($this->session->data['wk_cart_amount']) && ($this->session->data['wk_cart_amount'] <= $wallet_balance)) {
					$result['code'] = 'wk_wallet_system_payment';
					$this->load->model('extension/payment/' . $result['code']);
					$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						$method_data[$result['code']] = $method;
					}
					$json['payment_methods'] = $this->session->data['payment_methods'] = $method_data;
				} else {
					$json['text'] = $this->language->get('text_preffered_method1');
				}
			}
		}

		if (isset($json['text'])) {
			// Payment Methods
			$method_data = array();

			$this->load->model('setting/extension');

			$results = $this->model_setting_extension->getExtensions('payment');
			$recurring = $this->cart->hasRecurringProducts();

			foreach ($results as $result) {
				if ($this->config->get('payment_' . $result['code'] . '_status')) {
					$this->load->model('extension/payment/' . $result['code']);

					$method = $this->{'model_extension_payment_' . $result['code']}->getMethod($this->session->data['payment_address'], $total);

					if ($method) {
						if ($recurring) {
							if (property_exists($this->{'model_extension_payment_' . $result['code']}, 'recurringPayments') && $this->{'model_extension_payment_' . $result['code']}->recurringPayments()) {
								$method_data[$result['code']] = $method;
							}
						} else {
							$method_data[$result['code']] = $method;
						}
					}
				}
			}

			$sort_order = array();

			foreach ($method_data as $key => $value) {
				$sort_order[$key] = $value['sort_order'];
			}

			array_multisort($sort_order, SORT_ASC, $method_data);
			$json['payment_methods'] = $this->session->data['payment_methods'] = $method_data;
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
	
	
	
	public function add_money(){
		   if($this->valid($this->request->get['token'])){
	    	    $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		 $this->customer->loginWithId($this->request->post['customer_id']);
		 $customer_id=$this->request->post['customer_id'];
		 
		if(!isset($this->request->post['amount']) || (int)$this->request->post['amount'] == 0){
			$this->session->data['error'] = 'Please Enter Valid Amount..!';
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$s_currency = $this->session->data['currency'];
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$amount = $this->currency->convert($this->request->post['amount'], $s_currency, $config_currency);
		$amountmax = $this->currency->format((float)$this->config->get('e_wallet_max_add'),$s_currency);
		$amountmin = $this->currency->format((float)$this->config->get('e_wallet_min_add'),$s_currency);
		if((int)$amount > $this->config->get('e_wallet_max_add')){
			$this->session->data['error'] = 'Please Enter Below '.$amountmax.' Amount..!';
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}else if((int)$amount < $this->config->get('e_wallet_min_add')){
			$this->session->data['error'] = 'Please Enter Above '.$amountmin.' Amount..!';
			$this->response->redirect($this->url->link('account/e_wallet', '', 'SSL'));
		}
		$this->load->model('tool/image');
		$this->cart->clear();
		$this->session->data['vouchers'] = array();
		$vouchers_key = 'e_wallet_vouchers';
		$this->session->data['vouchers_key'] = 'e_wallet_vouchers';
		$vimage = 'no_image.png';
		if($this->config->get('e_wallet_image')){
			$vimage = $this->config->get('e_wallet_image');
		}
		$vimage = $this->model_tool_image->resize($vimage, $this->config->get($this->config->get('config_theme') . '_image_cart_width'), $this->config->get($this->config->get('config_theme') . '_image_cart_height'));
		$this->session->data['vouchers'][$vouchers_key] = array(
			'description'      => 'Add Money In Wallet',
			'to_name'          => $vouchers_key,
			'to_email'         => $this->customer->getEmail(),
			'from_name'        => $this->customer->getFirstName(),
			'from_email'       => $this->customer->getEmail(),
			'voucher_theme_id' => -1,
			'message'          => 'Add Money In Wallet..!',
			'image'            => $vimage,
			'amount'           => $amount,
		);
		$this->response->redirect($this->url->link('checkout/e_checkout', '', 'SSL'));
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
	public function add_bank(){
		$json = array();
		if(isset($this->request->post['bank'])){
			$this->load->model('account/e_wallet');
			$bank = $this->request->post['bank'];
			$data = array(
				'bank_name' => $bank['name'],
				'branch_code' => $bank['branch_number'],
				'swift' => $bank['swift_code'],
				'ifsc' => $bank['ifsc_code'],
				'ac_name' => $bank['account_name'],
				'ac_no' => $bank['account_number']
			);
			$this->model_account_e_wallet->setbank($data);
			$json['success'] = 1;
		}
		header("Content-Type: application/json; charset=UTF-8");
		echo json_encode($json); die;
	}
	public function redeem_voucher(){
	    
	    if($this->valid($this->request->get['token'])){
	    	    $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		 $this->customer->loginWithId($this->request->post['customer_id']);
		 $customer_id=$this->request->post['customer_id'];
		$per = DB_PREFIX;

	
		
		//$data = $this->load->language('account/e_wallet');
		$this->load->model('account/e_wallet');
		$balance = $this->model_account_e_wallet->getBalance();
		if(isset($this->session->data['currency'])){
			$config_currency =$this->session->data['currency'];				
		}else{
			$config_currency =$this->config->get('config_currency');				
		}
		$data['balance'] = $this->currency->format($balance,$config_currency);
		$data['error_warning'] = '';
		if($this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['vouchar_code'])){

			// $str = "SELECT count(vouchar_id) FROM `{$per}e_wallet_vouchar_list` WHERE vouchar_code= '". $this->request->post['vouchar_name'] ."' ";
			// $vouchar_found = $this->db->query($str)->row['total'];

			$str = "SELECT vouchar_id,used_by,user_limit,vouchar_amount,c_code FROM `{$per}e_wallet_vouchar_list` WHERE vouchar_code= '". $this->request->post['vouchar_code'] ."' AND `status`='1' ";

			$vouchar_found = $this->db->query($str);
			// echo "<pre>";print_r($vouchar_found);echo "</pre>";die;
			$used_by = '';
			$data['error_warning'] = '';
			if (isset($vouchar_found->row['vouchar_id']) && $vouchar_found->row['vouchar_id']) {
				$vouchar_id = $vouchar_found->row['vouchar_id'];
				$user_limit = $vouchar_found->row['user_limit'];
				$vouchar_amount = $vouchar_found->row['vouchar_amount'];
				$used_by_count = 0;
				$used_by_array = array();
				$curr_currency = $this->session->data['currency'];
				$config_currency = $this->config->get('config_currency');
				$vouchar_amount = $this->currency->convert($vouchar_amount,$vouchar_found->row['c_code'],$curr_currency);
				if (!empty($vouchar_found->row['used_by'])) {
					// $used_by_array = array_merge($used_by_array,$vouchar_found->row['used_by']);
					$used_by_array = explode("','", $vouchar_found->row['used_by'] );
				}
				// echo "<pre>";print_r($used_by_array);echo "</pre>";die;
				// TESTC
				if (!in_array($customer_id , $used_by_array)) {

					if (empty($vouchar_found->row['used_by'])) {
						$used_by_array[] = $customer_id;
					}

					if (count($used_by_array) <= $user_limit) {
						$used_by = implode("','",$used_by_array);

						$str = "UPDATE `{$per}e_wallet_vouchar_list` set used_by='". $used_by ."' WHERE vouchar_id= '". $vouchar_id ."' ";

						$this->db->query($str);

						$transaction_data = array(
							'customer_id' => $customer_id,
							'amount' => $vouchar_amount,
							'desc' => 'Added by redeem voucher.'
						);

						$this->load->model('account/e_wallet');
						$this->model_account_e_wallet->addtransaction($transaction_data);
					}else{
						$data['error_warning'] = "Invalid Vouchar.!";
					}

				}else{
					$data['error_warning'] = "Invalid Vouchar.!";
				}
				// echo "<pre>";print_r($str);echo "</pre>";die;
			}else{
				$data['error_warning'] = "Invalid Vouchar.!";
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
	}
	
		public function addtransaction() {
	        if($this->valid($this->request->get['token'])){
	    	    $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		 $this->customer->loginWithId($this->request->post['customer_id']);
			$this->load->model('account/e_wallet');
			$wallet_balance = $this->model_account_e_wallet->getBalance();
			$this->load->model('checkout/order');
			$o_info = $this->model_checkout_order->getOrder($this->request->post['order_id']);
			$amount = $o_info['total'];
			if((int)$wallet_balance > 0 && (float)$amount <= (float)$wallet_balance){
				if($o_info['currency_code'] != $this->config->get('config_currency')){
					$amount = $this->currency->convert($amount,$o_info['currency_code'],$this->config->get('config_currency'));
				}
				$data = array(
					'desc' => 'Paid for Order, Order Id is: #'.$this->request->post['order_id'],
					'amount' => -$amount,
				);
				$this->model_account_e_wallet->addtransaction($data);
				$this->model_checkout_order->addOrderHistory($this->request->post['order_id'], $this->config->get('payment_e_wallet_payment_order_status_id'));
					$json['success'] = "ok";
			}else{
				$json['error'] = "You have Insufficient Balance in Your ".$this->config->get('e_wallet_title').'..!';
				header("Content-Type: application/json; charset=UTF-8");
				echo json_encode($json);
				die;
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
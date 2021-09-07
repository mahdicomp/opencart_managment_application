<?php
class ControllerExtensionApi32AccountLoginVerify extends Controller {
	private $error = array();

	public function index() {
    	$this->load->controller('extension/api32/common/language');
		$this->load->model('account/customer');

      $json=array();
		

		$this->load->language('account/login');

		
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			 $data['result'] ="OK";
         $data['name'] = $this->customer->getFirstName() .' '.$this->customer->getLastName();
         $data['mobile'] = $this->customer->getTelephone();
         $data['customer_id'] = $this->customer->getId();
       
        
         if(isset($this->request->post['session_id'])){
			$session_id=	$this->request->post['session_id']; 
		 }elseif(isset($this->request->post['id'])){
			 $session_id=	$this->request->post['id']; 
		 }else {
          $session_id=$this->cart->getSession();   
        }
        
    
        
        $this->session->start($data['session_id']);    
             
         if ($this->customer->getId()) {
		   
			// We want to change the session ID on all the old items in the customers cart
			$this->db->query("UPDATE " . DB_PREFIX . "cart SET session_id = '" . $this->db->escape($this->session->getId()) . "' WHERE api_id = '0' AND customer_id = '" . (int)$this->customer->getId() . "'");

			// Once the customer is logged in we want to update the customers cart
			$cart_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "cart WHERE api_id = '0' AND customer_id = '0' AND session_id = '" . $this->db->escape($session_id) . "'");
			foreach ($cart_query->rows as $cart) {
				$this->db->query("DELETE FROM " . DB_PREFIX . "cart WHERE cart_id = '" . (int)$cart['cart_id'] . "'");
//echo $cart['product_id'];
				// The advantage of using $this->add is that it will check if the products already exist and increaser the quantity if necessary.
				$this->cart->add($cart['product_id'], $cart['quantity'], json_decode($cart['option']), $cart['recurring_id']);
			}
		}
		$data['session_id'] = $this->cart->getSession();
		
         $data['countproduct'] =  $this->cart->countNumProducts() ;
		

			
		}
		
		if (isset($this->session->data['error'])) {
			$data['error_warning'] = $this->session->data['error'];

			unset($this->session->data['error']);
		} elseif (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
	$json=	$data;

      
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

	
	 
	 
	public function send_code() {
	    if($this->valid($this->request->get['token'])){
         $username= $this->convertPersianNumbersToEnglish($this->request->post['username']);
       
		$data=array();
	$this->load->model('account/customer');
		$customer_info = $this->model_account_customer->getCustomerByEmail($username);

		if ($customer_info) {
			
		$verify_code=rand(1000,10000);
			/////////send sms user
	     if($this->config->get('sms_status')=="1"){
	       $this->sms = new Sms();
		   $pattern_code=$this->config->get('logindigikala_paterncode');
		    $verify_message =$verify_code." کد تایید شما در سایت ناویرا ";
            $input_data = array("verification-code" => $verify_code);
			$this->sms->send_sms($customer_info['telephone'],$verify_message,$this->config->get('sms_user'),$this->config->get('sms_pass'),$this->config->get('sms_samane_sms'),$this->config->get('sms_from'),'pattern',$pattern_code, $input_data);
		     	$data['code']=$verify_code;	
			$data['loging']="login";
			}
			
		}else {
		    if($this->config->get('sms_status')=="1"){
		        $verify_code=rand(1000,10000);
		        $verify_message =$verify_code." کد تایید شما در سایت ناویرا ";
	       $this->sms = new Sms();
		   $pattern_code=$this->config->get('logindigikala_paterncode');
            $input_data = array("verification-code" => $verify_code);
			$this->sms->send_sms($username,$verify_message,$this->config->get('sms_user'),$this->config->get('sms_pass'),$this->config->get('sms_samane_sms'),$this->config->get('sms_from'),'pattern',$pattern_code, $input_data);
		
			}
		$data['code']=$verify_code;	
		$data['loging']="error";
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
	
		private function valid($token) {
	    
    	    if($token==$this->config->get('storeapp_token')){
    	        return true;
    	        
    	    }else {
    	        return false;
    	    }
	   }
	protected function validate() {
         $username= $this->convertPersianNumbersToEnglish($this->request->post['username']);
       
		// Check how many login attempts have been made.
		$login_info = $this->model_account_customer->getLoginAttempts($username);

		if ($login_info && ($login_info['total'] >= $this->config->get('config_login_attempts')) && strtotime('-1 hour') < strtotime($login_info['date_modified'])) {
			$this->error['warning'] = $this->language->get('error_attempts');
		}

		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($username);

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		if (!$this->error) {
			if (!$this->customer->login_verify($username, $this->convertPersianNumbersToEnglish($this->request->post['code']))) {
			    
			   
				$this->error['warning'] = "شماره موبایل و یا کد اشتباه است";

				$this->model_account_customer->addLoginAttempt($username);
			} else {
			   
				$this->model_account_customer->deleteLoginAttempts($username);
			}
		}

		return !$this->error;
	}
	
	private	function convertPersianNumbersToEnglish($number)
    {
        
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $num = range(0, 9);
        return str_replace($persian, $num, $number);
    }
}

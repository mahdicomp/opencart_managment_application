<?php
class ControllerExtensionApi32AccountPassword extends Controller {
	private $error = array();

	public function index() {
	

    	$this->load->controller('extension/api32/common/language');
		$this->load->language('account/password');
	 	$this->customer->loginWithId($this->request->post['customer_id']);
		if ($this->request->server['REQUEST_METHOD'] == 'POST'){
		  
			$this->load->model('account/customer');
      
          $result=$this->model_account_customer->checkPassword($this->request->post['customer_id'],$this->convertPersianNumbersToEnglish($this->request->post['oldpassword']));	
          if (!$this->model_account_customer->checkPassword($this->request->post['customer_id'],$this->convertPersianNumbersToEnglish($this->request->post['oldpassword']))) {	
			$data['error'] =$this->language->get('error_old_password');
	    	}
 
 
          if ((utf8_strlen(html_entity_decode($this->convertPersianNumbersToEnglish($this->request->post['newpassword']), ENT_QUOTES, 'UTF-8')) < 4) || (utf8_strlen(html_entity_decode($this->convertPersianNumbersToEnglish($this->request->post['newpassword']), ENT_QUOTES, 'UTF-8')) > 40)) {
			$data['error'] = $this->language->get('error_password');
		}

	
		
		if(isset($data['error'])){
		    
		}else {
		    if($this->customer->getEmail()){
		        $username=$this->customer->getEmail();
		        
		        
		    }else{
		        $username=$this->customer->getTelephone();
		        
		    }
		    
			$this->model_account_customer->editPassword($username, $this->convertPersianNumbersToEnglish($this->request->post['newpassword']));

			$data['success'] = $this->language->get('text_success');
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


  	private function convertPersianNumbersToEnglish($number)
    {
        
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $num = range(0, 9);
        return str_replace($persian, $num, $number);
    }

}
<?php
class ControllerExtensionApi32AccountRegisterVerify extends Controller {
	private $error = array();

	public function index() {
	
       if($this->valid($this->request->get['token'])){
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('account/register');
        $data['error']=false;
		
		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') ) {
		    
        
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['telephone'])) {
				$data['error'] = $this->language->get('error_exists_phonenumber');
			}
		    if($data['error']){
				
			}else {
				$this->request->post['email']=$this->request->post['telephone'].'@mail.com';
				$customer_id = $this->model_account_customer->addCustomer($this->request->post);
				$data['result'] = "OK";
				
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


	public function customfield() {
    	$this->load->controller('extension/api32/common/language');
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	private function valid($token) {
    
	    if($token==$this->config->get('storeapp_token')){
	        return true;
	        
	    }else {
	        return false;
	    }
    }
   
   	private function convertPersianNumbersToEnglish($number)
    {
        
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $num = range(0, 9);
        return str_replace($persian, $num, $number);
    }
}
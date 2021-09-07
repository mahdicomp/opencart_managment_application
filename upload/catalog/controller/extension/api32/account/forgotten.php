<?php
class ControllerExtensionApi32AccountForgotten extends Controller {
	private $error = array();

	public function index() {
	   if($this->valid($this->request->get['token'])){
		$this->load->controller('extension/api32/common/language');

		$this->load->language('account/forgotten');


		$this->load->model('account/customer');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$code=rand(1000,10000);
			$this->model_account_customer->editCode($this->request->post['email'], $code);
      	
			if($this->config->get('sms_status')=="1"){
				$this->sms = new Sms();
				$message=$code;
				$this->sms->send_sms($this->request->post['email'],$message,$this->config->get('sms_user'),$this->config->get('sms_pass'),$this->config->get('sms_samane_sms'),$this->config->get('sms_from'),$this->config->get('sms_sample'));
				
			
			}

			$data['success'] = $this->language->get('text_success');

		}
		else {
			    $data['error']=$this->language->get('error_email');
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

	protected function validate() {
		if (!isset($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		} elseif (!$this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
			$this->error['warning'] = $this->language->get('error_email');
		}
		
		// Check if customer has been approved.
		$customer_info = $this->model_account_customer->getCustomerByEmail($this->request->post['email']);

		if ($customer_info && !$customer_info['status']) {
			$this->error['warning'] = $this->language->get('error_approved');
		}

		return !$this->error;
	}
	
		private function valid($token) {
	    
    	    if($token==$this->config->get('storeapp_token')){
    	        return true;
    	        
    	    }else {
    	        return false;
    	    }
	   }
}

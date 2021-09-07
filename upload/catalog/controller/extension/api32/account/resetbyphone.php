<?php
class ControllerExtensionApi32AccountResetbyphone extends Controller {
	private $error = array();

	public function index() {
	

    	$this->load->controller('extension/api32/common/language');
		
		$this->load->model('account/customer');

		$this->load->language('account/reset');

		$this->document->setTitle($this->language->get('heading_title'));

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$customer_info = $this->model_account_customer->getCustomerByCode($this->request->post['code']);
			
			$this->model_account_customer->editPassword($customer_info['email'], $this->request->post['password']);


			$data['success'] = $this->language->get('text_success');

				
		}
		else {
		    $data['error']=$this->language->get('text_success');
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
		if ((utf8_strlen($this->request->post['password']) < 4) || (utf8_strlen($this->request->post['password']) > 20)) {
			$this->error['password'] = $this->language->get('error_password');
		}

		if ($this->request->post['confirm'] != $this->request->post['password']) {
			$this->error['confirm'] = $this->language->get('error_confirm');
		}
		
		if ($this->request->post['code']) {
			$customer_info = $this->model_account_customer->getCustomerByCode($this->request->post['code']);
			if (!$customer_info) {
			$this->error['code'] =   $this->language->get('error_code');
		    }
		}else {
		$this->error['code'] =  $this->language->get('error_code');
		}
			

		return !$this->error;
	}
}

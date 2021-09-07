<?php 
class ControllerExtensionApi32ExtensionModuleSmsVerify extends Controller {
	public function index() {
         if (isset($this->request->post['module_sms_verify_tozih'])) {
			$data['module_sms_verify_tozih'] = $this->request->post['module_sms_verify_tozih'];
		} else {
        	$data['module_sms_verify_tozih'] =html_entity_decode($this->config->get('module_sms_verify_tozih'), ENT_QUOTES, 'UTF-8'); 
		}
		$data['sms_verify_status'] =$this->config->get('module_sms_verify_status');
			$this->customer->loginWithId($this->request->post['customer_id']);
		$data['yourtelephone'] =$this->customer->getTelephone();
		 $data['account_verify']=$this->customer->getAccountVerify();
	//return $this->load->view('extension/module/sms_verify', $data);
		
		 
		
			if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
		$this->response->setOutput(json_encode($data));		
	}
	
	
	public function UpdateCode(){
	     if($this->valid($this->request->get['token'])){
			$json=array();
			$this->load->model('account/sms_verify');
				$this->customer->loginWithId($this->request->post['customer_id']);
			 $result=$this->model_account_sms_verify->UpdateCode();
			 if($result){
			 $json['success']="کد فعال سازی به موبایل شما ارسال شد";
			 }else {
			  $json['error']="خطا در ارسال کد فعال سازی";
			 }
			 if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
			$this->response->setOutput(json_encode($json));
	}
}
	public function confirm(){
	if (isset($this->request->post['sms_verify_code']) && $this->request->post['sms_verify_code']) {
			$sms_verify_code = $this->request->post['sms_verify_code'];
		} else {
			$sms_verify_code = 1;
		}
			$json=array();
			$this->load->model('account/sms_verify');
				$this->customer->loginWithId($this->request->post['customer_id']);
			 $result=$this->model_account_sms_verify->ConfirmCode($sms_verify_code);
			 if($result){
			 $this->model_account_sms_verify->UpdateStatus(1);
			 $json['success']="حساب شما تایید شد";
			 }else {
			  $json['error']="کد وارد شده با کد تایید برابر نیست";
			 }
			 if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
			$this->response->setOutput(json_encode($json));
	}

	
	
		private function valid($token) {
	    
    	    if($token==$this->config->get('storeapp_token')){
    	        return true;
    	        
    	    }else {
    	        return false;
    	    }
	   }
}
?>
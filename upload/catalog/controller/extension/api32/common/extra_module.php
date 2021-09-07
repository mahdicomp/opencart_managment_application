<?php
class ControllerExtensionApi32CommonExtraModule extends Controller {
	public function index() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('common/module');

	
	
		$data['ticket_status']=	$this->config->get('storeapp_ticket');
		$data['multivendor_status']=	$this->config->get('storeapp_multivendor');
		$data['storeapp_ewallet']=	$this->config->get('storeapp_ewallet');
		$data['smsverify_status']=	$this->config->get('storeapp_smsverify');
		$data['storeapp_delivery_time']=	$this->config->get('storeapp_delivery_time');
		
		$data['reward_status']=	$this->config->get('storeapp_reward');
		$data['letmeknow_status']=	$this->config->get('storeapp_letmeknow');
	
	
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

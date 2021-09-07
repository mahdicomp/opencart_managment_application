<?php
class ControllerExtensionApi32AccountAddress extends Controller {
	public function index() {
	    if($this->valid($this->request->get['token'])){
        	$this->load->controller('extension/api32/common/language');
    		$this->load->language('account/address');
            $customer_id= $this->request->post['customer_id'];
            $json = array();
    		//$customer_id=12;
    		$this->load->model('extension/api/account/address');
    		$addresses=$this->model_extension_api_account_address->getAddresses($customer_id) ;
    		if($addresses){
    		   $json['addresses']=$addresses;
    		    
    		}else{
    		    $json['error']=$this->language->get('error_delete');
    		    
    		}
    		$json['country_show'] = $this->config->get('storeapp_country_show');
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
	
	public function get() {
	    if($this->valid($this->request->get['token'])){
        	$this->load->controller('extension/api32/common/language');
    	    $this->load->language('extension/api/shipping');
            $customer_id= $this->request->post['customer_id'];
            $address_id=$this->request->post['address_id'];
        	$json = array();
        	
        	$this->load->model('localisation/zone');

	    	$this->load->model('localisation/country');

	     	$json['countries'] = $this->model_localisation_country->getCountries();
			$this->load->model('extension/api/account/address');
			//	echo 3;
			$address=$this->model_extension_api_account_address->getAddress($address_id,$customer_id) ;
    		if($address){
    		   $json=$address;
    		    
    		}else{
    		    $json['error']="--".$address_id.'--'.$customer_id;
    		    
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
	
	
	public function add() {
	    if($this->valid($this->request->get['token'])){
        	$this->load->controller('extension/api32/common/language');
		    $this->load->language('extension/api/shipping');

			$json = array();
		
		
		
			$this->load->model('extension/api/account/address');
			if(!isset($this->request->post['country_id'])){
			$this->request->post['country_id']=$this->config->get('config_country_id');
			}
			$result=$this->model_extension_api_account_address->addAddress($this->request->post['customer_id'], $this->request->post) ;
    		if($result){
    		    $json['result']=$this->language->get('text_add');
    		    
    		}else{
    		    $json['error']=$this->language->get('error_delete');
    		    
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
	
	
	public function zone() {
    	$this->load->controller('extension/api32/common/language');

		$json = array();
		$this->load->model('localisation/zone');

		$json['zone'] = $this->model_localisation_zone->getZonesByCountryId($this->request->post['country_id']);
	
		
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
	
	public function country() {
		$json = array();

			$this->load->model('localisation/country');

	     	$json['countries'] = $this->model_localisation_country->getCountries();
	     		$json['country_show'] = $this->config->get('storeapp_country_show');
	     		$json['config_country_id']=$this->config->get('config_country_id');

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
	
	public function delete() {
	    if($this->valid($this->request->get['token'])){
        	$json = array();
        	$this->load->model('extension/api/account/address');
			$this->model_extension_api_account_address->deleteAddress($this->request->post['address_id'],$this->request->post['customer_id']);
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
	
	public function edit() {
	    if($this->valid($this->request->get['token'])){
	    	$json = array();
	        $this->load->model('extension/api/account/address');
	        if(!isset($this->request->post['country_id'])){
			$this->request->post['country_id']=$this->config->get('config_country_id');
	        }
			$this->model_extension_api_account_address->editAddress($this->request->post['address_id'],$this->request->post['customer_id'], $this->request->post);
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
	
		public function customfield() {
    	$this->load->controller('extension/api32/common/language');
		$json = array();

		$json['custom_fields'] = array();
		
		$this->load->model('account/custom_field');

		$custom_fields = $this->model_account_custom_field->getCustomFields($this->config->get('config_customer_group_id'));

		foreach ($custom_fields as $custom_field) {
			if ($custom_field['location'] == 'address') {
				$json['custom_fields'][] = $custom_field;
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

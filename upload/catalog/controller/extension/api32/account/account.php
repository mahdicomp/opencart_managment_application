<?php
class ControllerExtensionApi32AccountAccount extends Controller {
	public function index() {
		
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');

		 $this->customer->loginWithId($this->request->post['customer_id']);
		$data['totalreward'] =(int)$this->customer->getRewardPoints();
		
			if (isset($this->request->post['customer_id'])) {
			$customer_id = $this->request->post['customer_id'];
		} else {
			$customer_id= 0;
		}

		$this->load->model('extension/api/account/order');
	

		$data['order_total'] = $this->model_extension_api_account_order->getTotalOrders($customer_id);
        //////////informatino
      $this->load->model('catalog/information');

		$data['informations'] = array();

		$informations=$this->model_catalog_information->getInformations();
		///
		$data['profile_banner']=HTTPS_SERVER.'image/'.$this->config->get('storeapp_profile_banner');
		$data['profile_url']=$this->config->get('storeapp_profile_url');
		$data['contactus_callnumber']=$this->config->get('storeapp_contactus_callnumber');
		 
		$results = $this->config->get('storeapp_order_status');
		foreach ($results as $result) {
				$data['order_status'][] = array(
					'status' => $result['status'],
					'id' => $result['id'],
					'name'  =>$this->model_extension_api_account_order->getOrderStatusName($result['id']) ,
					'total'  =>$this->model_extension_api_account_order->getOrderStatusTotal($result['id'],$customer_id) ,
					'icon' => HTTPS_SERVER.'image/'.$result['icon'],
				);
		} 
		foreach ($informations as $result) {
				$data['informations'][] = array(
					'description' =>html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'),
					'information_id' => $result['information_id'],
					'title' => $result['title'],
				
				);
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
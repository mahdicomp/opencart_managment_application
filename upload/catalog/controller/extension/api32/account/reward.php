<?php
class ControllerExtensionApi32AccountReward extends Controller {
	public function index() {
		
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');

		$this->load->language('account/reward');

	
		$this->load->model('account/reward');

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

		$data['rewards'] = array();
     
		$filter_data = array(
			'sort'  => 'date_added',
			'order' => 'DESC',
			'start' => ($page - 1) * 10,
			'limit' => 10
		);
         $this->customer->loginWithId($this->request->post['customer_id']);
		$data['count'] = $this->model_account_reward->getTotalRewards();

		$results = $this->model_account_reward->getRewards($filter_data);

		foreach ($results as $result) {
			$data['rewards'][] = array(
				'order_id'    => $result['order_id'],
				'points'      => $result['points'],
				'description' => $result['description'],
				'date_added' => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				
			);
		}

		
		$data['total'] =(int)$this->customer->getRewardPoints();

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
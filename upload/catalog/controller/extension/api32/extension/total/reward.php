<?php
class ControllerExtensionApi32ExtensionTotalReward extends Controller {
	public function index() {
		$points = $this->customer->getRewardPoints();

		$points_total = 0;

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}

		if ($points && $points_total && $this->config->get('total_reward_status')) {
    	    $this->load->controller('extension/api32/common/language');
    	    $this->load->controller('extension/api32/common/currency');
			$this->load->language('extension/total/reward');

			$data['heading_title'] = sprintf($this->language->get('heading_title'), $points);

			$data['entry_reward'] = sprintf($this->language->get('entry_reward'), $points_total);

			if (isset($this->session->data['reward'])) {
				$data['reward'] = $this->session->data['reward'];
			} else {
				$data['reward'] = '';
			}

			return $this->load->view('extension/total/reward', $data);
		}
	}

	public function reward() {
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('extension/total/reward');

		$json = array();
      	$this->customer->loginWithId($this->request->post['customer_id']);
		  if(isset($this->request->post['session_id'])){
			$session_id=	$this->request->post['session_id']; 
		 }elseif(isset($this->request->post['id'])){
			 $session_id=	$this->request->post['id']; 
		 }
		$this->session->start($session_id); 
		$points = $this->customer->getRewardPoints();

		$points_total = 0;
	

		foreach ($this->cart->getProducts() as $product) {
			if ($product['points']) {
				$points_total += $product['points'];
			}
		}
		if (empty($this->request->post['reward'])) {
			$json['error'] = $this->language->get('error_reward');
		}

		if ($this->convertPersianNumbersToEnglish($this->request->post['reward']) > $points) {
		    
			$json['error'] = sprintf($this->language->get('error_points'), $this->request->post['reward']);
		}

		if ($this->convertPersianNumbersToEnglish($this->request->post['reward']) > $points_total) {
		   
			$json['error'] = sprintf($this->language->get('error_maximum'), $points_total);
		}

		if (!$json) {
			$this->session->data['reward'] = abs($this->convertPersianNumbersToEnglish($this->request->post['reward']));

			$json['success'] = $this->language->get('text_success');

			
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
	
	private function convertPersianNumbersToEnglish($number)
    {
        
        $persian = array('۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹');
        $num = range(0, 9);
        return str_replace($persian, $num, $number);
    }
}

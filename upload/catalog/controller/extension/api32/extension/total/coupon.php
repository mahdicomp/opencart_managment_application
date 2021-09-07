<?php
class ControllerExtensionApi32ExtensionTotalCoupon extends Controller {
	public function index() {
		if ($this->config->get('total_coupon_status')) {
    	    $this->load->controller('extension/api32/common/language');
    	    $this->load->controller('extension/api32/common/currency');

			$this->load->language('extension/total/coupon');

			if (isset($this->session->data['coupon'])) {
				$data['coupon'] = $this->session->data['coupon'];
			} else {
				$data['coupon'] = '';
			}

			return $this->load->view('extension/total/coupon', $data);
		}
	}

	public function coupon() {
    	$this->load->controller('extension/api32/common/language');
		$this->load->language('extension/total/coupon');

		$json = array();

		$this->load->model('extension/total/coupon');

		if (isset($this->request->post['coupon'])) {
			$coupon =  $this->convertPersianNumbersToEnglish($this->request->post['coupon']);
		} else {
			$coupon = '';
		}
		$this->customer->loginWithId($this->request->post['customer_id']);
			if(isset($this->request->post['session_id'])){
				$session_id=	$this->request->post['session_id']; 
			 }elseif(isset($this->request->post['id'])){
				 $session_id=	$this->request->post['id']; 
			 }
			$this->session->start($session_id); 

		$coupon_info = $this->model_extension_total_coupon->getCoupon($coupon);

		if (empty($this->request->post['coupon'])) {
			$json['error'] = $this->language->get('error_empty');

			unset($this->session->data['coupon']);
		} elseif ($coupon_info) {
			$this->session->data['coupon'] =  $this->convertPersianNumbersToEnglish($this->request->post['coupon']);

			$this->session->data['success'] = $this->language->get('text_success');

			$json['success'] = $this->language->get('text_success');
		} else {
			$json['error'] = $this->language->get('error_coupon');
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

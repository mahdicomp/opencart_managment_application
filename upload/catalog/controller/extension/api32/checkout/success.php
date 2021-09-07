<?php
class ControllerExtensionApi32CheckoutSuccess extends Controller {
	public function index() {
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		$this->customer->loginWithId($this->request->post['customer_id']);
		if(isset($this->request->post['session_id'])){
			$session_id=	$this->request->post['session_id']; 
		 }elseif(isset($this->request->post['id'])){
			 $session_id=	$this->request->post['id']; 
		 }
		$this->session->start($session_id);
		$this->cart->clear();

		unset($this->session->data['shipping_method']);
		unset($this->session->data['shipping_methods']);
		unset($this->session->data['payment_method']);
		unset($this->session->data['payment_methods']);
		unset($this->session->data['guest']);
		unset($this->session->data['comment']);
		unset($this->session->data['order_id']);
		unset($this->session->data['coupon']);
		unset($this->session->data['reward']);
		unset($this->session->data['voucher']);
		unset($this->session->data['vouchers']);
		unset($this->session->data['totals']);
		

		if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}

       $json="";
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
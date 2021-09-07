<?php
class ControllerExtensionApi32CheckoutConfirmapp extends Controller {
	public function index() {
    	
           	$this->session->data['order_id'] =	$this->request->get['order_id'] ;
           	$this->session->data['payment_method']['code'] =	$this->request->get['payment_method'];
			
		$data['payment'] = $this->load->controller('extension/payment/' . $this->session->data['payment_method']['code']);
        $data['back'] = $this->url->link('account/address', '', true);

		$data['column_left'] = $this->load->controller('common/column_left');
		$data['column_right'] = $this->load->controller('common/column_right');
		$data['content_top'] = $this->load->controller('common/content_top');
		$data['content_bottom'] = $this->load->controller('common/content_bottom');
		$data['footer'] = $this->load->controller('common/footer');
		$data['header'] = $this->load->controller('common/header');

		$this->response->setOutput($this->load->view('checkout/confirmapp', $data));
		
		
	}
}
?>
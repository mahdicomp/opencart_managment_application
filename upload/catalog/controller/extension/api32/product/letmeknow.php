<?php 
class ControllerExtensionApi32ProductLetmeknow extends Controller {
	private $error = array(); 


  
	public function confirm(){
      $json=array();
	  $valid=array();
	   $valid=$this->validate();
     if($valid !='true'){
		$json['error']=$valid;
	 
	 
	 }else {
	/*
	$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(html_entity_decode(sprintf($this->language->get('heading_title_letmeknow'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
			$mail->setText(strip_tags(html_entity_decode(sprintf($this->request->post['body_message'], $this->request->post['name']), ENT_QUOTES, 'UTF-8')));
			$mail->send();
			*/
			
			$this->db->query('INSERT INTO ' . DB_PREFIX . 'letmeknow SET name = "' . $this->db->escape($this->request->post['name']) . '", email = "' . $this->db->escape($this->request->post['email']) . '", mobile = "' . $this->db->escape($this->request->post['mobile']) . '", product_id = "' . $this->db->escape($this->request->post['product_id']) . '", language_id = "'.$this->config->get('config_language_id').'", date_added = NOW()');
			$this->load->controller('extension/api32/common/language');
			$this->load->controller('extension/api32/common/currency');
			$this->language->load('product/letmeknow');
			$json['success']= $this->language->get('text_ok');
			
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
	
	public function confirmcat(){
      $json=array();
	  $valid=array();
	   $valid=$this->validatecat();
     if($valid !='true'){
		$json['msg']=$valid;
	 
	 
	 }else {
	/*
	$mail = new Mail();
			$mail->protocol = $this->config->get('config_mail_protocol');
			$mail->parameter = $this->config->get('config_mail_parameter');
			$mail->hostname = $this->config->get('config_smtp_host');
			$mail->username = $this->config->get('config_smtp_username');
			$mail->password = $this->config->get('config_smtp_password');
			$mail->port = $this->config->get('config_smtp_port');
			$mail->timeout = $this->config->get('config_smtp_timeout');				
			$mail->setTo($this->config->get('config_email'));
	  		$mail->setFrom($this->request->post['email']);
	  		$mail->setSender($this->request->post['name']);
	  		$mail->setSubject(html_entity_decode(sprintf($this->language->get('heading_title_letmeknow'), $this->request->post['name']), ENT_QUOTES, 'UTF-8'));
			$mail->setText(strip_tags(html_entity_decode(sprintf($this->request->post['body_message'], $this->request->post['name']), ENT_QUOTES, 'UTF-8')));
			$mail->send();
			*/
			
			$this->db->query('INSERT INTO ' . DB_PREFIX . 'letmeknow SET name = "' . $this->db->escape($this->request->post['notify_name']) . '", email = "' . $this->db->escape($this->request->post['notify_email']) . '", mobile = "' . $this->db->escape($this->request->post['notify_phone']) . '", product_id = "' . $this->db->escape($this->request->post['notify_product_id']) . '", language_id = "'.$this->config->get('config_language_id').'", date_added = NOW()');
			$this->load->controller('extension/api32/common/language');
			$this->language->load('product/letmeknow');
			$json['sucess']= $this->language->get('text_ok');
			$json['msg']= $this->language->get('text_ok');
			
	}	
	$this->response->setOutput(json_encode($json));	
	
	}
	
  	private function validate() {
        $this->load->controller('extension/api32/common/language');
		$this->language->load('product/letmeknow');
		$this->load->model('catalog/letmeknow');
	$json=array();
    	if ((utf8_strlen($this->request->post['name']) < 3) || (utf8_strlen($this->request->post['name']) > 32)) {
      		$json['error'] = $this->language->get('error_name');
    	}
		if ((utf8_strlen($this->request->post['mobile']) < 10) || (utf8_strlen($this->request->post['mobile']) > 32)) {
      		$json['error'] = $this->language->get('error_mobile');
    	}
		
    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
      		$json['error'] = $this->language->get('error_email');
    	}
		
			if( $this->model_catalog_letmeknow->CheckExistMobile($this->request->post['mobile'],$this->request->post['product_id'])){
		$json['error'] = "این شماره قبلا برای این محصول ثبت شده است";
		}
		
    	
		if( $this->model_catalog_letmeknow->CheckExistEmail($this->request->post['email'],$this->request->post['product_id'])){
		$json['error'] = "این ایمیل قبلا برای این محصول ثبت شده است";
		}

    	
		if (!isset($json['error']) ) {
	  		return true;
		} else {
	  		return $json['error'];
		}  	  
  	}

private function validatecat() {
	$this->load->controller('extension/api32/common/language');
	$this->language->load('product/letmeknow');
	$this->load->model('catalog/letmeknow');
	$json=array();
    	if ((utf8_strlen($this->request->post['notify_name']) < 3) || (utf8_strlen($this->request->post['notify_name']) > 32)) {
      		$json['error']['notify_name'] = $this->language->get('error_name');
    	}
		if ((utf8_strlen($this->request->post['notify_phone']) < 10) || (utf8_strlen($this->request->post['notify_phone']) > 32)) {
      		$json['error']['notify_phone'] = $this->language->get('error_mobile');
    	}
		if( $this->model_catalog_letmeknow->CheckExistMobile($this->request->post['notify_phone'],$this->request->post['notify_product_id'])){
		$json['error']['notify_phone'] = "این شماره قبلا برای این محصول ثبت شده است";
		}
		
    	if (!preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['notify_email'])) {
      		$json['error']['notify_email'] = $this->language->get('error_email');
    	}
		if( $this->model_catalog_letmeknow->CheckExistEmail($this->request->post['notify_email'],$this->request->post['notify_product_id'])){
		$json['error']['notify_email'] = "این ایمیل قبلا برای این محصول ثبت شده است";
		}

    	
		if (!isset($json['error']) ) {
	  		return true;
		} else {
	  		return $json['error'];
		}  	  
  	}

		
}
?>

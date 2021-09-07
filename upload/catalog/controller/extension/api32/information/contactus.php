<?php
class ControllerExtensionApi32InformationContactus extends Controller {
	private $error = array();

	public function index() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('information/contact');

		
// 		$this->load->model('tool/image');

// 		if ($this->config->get('config_image')) {
// 			$data['image'] = $this->model_tool_image->resize($this->config->get('config_image'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_location_height'));
// 		} else {
// 			$data['image'] = false;
// 		}


	    if($this->config->get('storeapp_contactus_icon')){
	        $data['contactus_icon'] = HTTP_SERVER.'image/'.$this->config->get('storeapp_contactus_icon');
	    }
		$data['store_name']             = $this->config->get('storeapp_contactus_namestore');
		$data['store_description']             = $this->config->get('storeapp_contactus_description');
		$data['contactus_email_title']             = $this->config->get('storeapp_contactus_email_title');
		$data['contactus_email_description']             = $this->config->get('storeapp_contactus_email_description');
		$data['contactus_email_subject']         = $this->config->get('storeapp_contactus_subject_email');
		$data['contactus_email_message']         = $this->config->get('storeapp_contactus_message_email');
		$data['contactus_email_icon'] = HTTPS_SERVER.'image/'.$this->config->get('storeapp_contactus_email_icon');
		
		$data['contactus_call_title']             = $this->config->get('storeapp_contactus_call_title');
		$data['contactus_call_description']             = $this->config->get('storeapp_contactus_call_description');
		$data['contactus_callnumber']         = $this->config->get('storeapp_contactus_callnumber');
		$data['contactus_call_icon'] = HTTPS_SERVER.'image/'.$this->config->get('storeapp_contactus_call_icon');
		
		$data['contactus_privacy_link']          = str_replace('&amp;','&',$this->config->get('storeapp_contactus_privacy_link'));
		$data['contactus_complaints_link']          = str_replace('&amp;','&',$this->config->get('storeapp_contactus_complaints_link'));
	
		$data['contactus_subject_share']         = $this->config->get('storeapp_contactus_subject_share');
		$data['contactus_message_share']         = $this->config->get('storeapp_contactus_message_share');
		$data['contactus_url_share']             = str_replace('&','&amp;',$this->config->get('storeapp_contactus_url_share'));
		$data['contactus_text']                  = $this->config->get('storeapp_contactus_text');
	
		$results = $this->config->get('storeapp_socials');

		foreach ($results as $result) {
			
				$data['socials'][] = array(
					'title' => $result['title'],
					'url'  => $result['url'],
					'icon' => HTTPS_SERVER.'/image/'.$result['icon'],
					'description' =>$result['description'],
					
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

<?php
class ControllerExtensionApi32ExtensionModuleHtml extends Controller {
	public function index() {
		
			if(isset($this->request->post['language'])){
			$language=	$this->request->post['language']; 
		 }else{
			 $language=	$this->config->get('config_language'); 
		 }
		
         $data['result']='OK';
         $html=$this->config->get('storeapp_html_description');
         $data['html'] = html_entity_decode($html[$language]);

         //  $data['html'] = '<h1>My First Heading</h1><p>slama</p>';
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
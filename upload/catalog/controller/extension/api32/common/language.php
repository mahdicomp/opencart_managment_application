<?php 
class ControllerExtensionApi32CommonLanguage extends Controller {
    public function index(){
	       
     	if(isset($this->request->post['language'])){
     	   $code = $this->request->post['language']; 
     	}else{
     	  $code = $this->config->get('config_language'); 
     	}
	     	
	    $language = new Language($code);
		$language->load($code);
		$this->registry->set('language', $language);
		$this->load->model('localisation/language');
		
		$languages = $this->model_localisation_language->getLanguages();
		$this->config->set('config_language_id', $languages[$code]['language_id']);	
	    $this->session->data['language']=$code;
	   
	 $data="";
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


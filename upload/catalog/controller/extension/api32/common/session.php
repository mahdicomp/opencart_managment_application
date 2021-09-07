<?php
class ControllerExtensionApi32CommonSession extends Controller {
	public function index() {
		
            if($this->valid($this->request->get['token'])){
		

			if(isset($this->request->post['id'])){
					 $session_id=	$this->request->post['id']; 
					 	$this->session->start($session_id); 
				 }
			
	           
	             
             $data['session_id']=$this->session->getId();
	           
	            

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
	private function valid($token) {
	    
    	    if($token==$this->config->get('storeapp_token')){
    	        return true;
    	        
    	    }else {
    	        return false;
    	    }
	   }
}

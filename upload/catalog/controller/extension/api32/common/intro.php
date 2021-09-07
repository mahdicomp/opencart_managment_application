<?php
class ControllerExtensionApi32CommonIntro extends Controller {
    public function index() {
	   
          $data['intro_status'] = $this->config->get('storeapp_intro_status');
          $data['intros'] = array();
        $storeapp_intros=$this->config->get('storeapp_intros');
    $data['intros'] = array();
       if(is_array($this->config->get('storeapp_intros'))){
		foreach ($storeapp_intros as $result) {
		   // print_r($result[$this->config->get('config_language')]);
			if (is_file(DIR_IMAGE . $result['image'])) {
			    
			    
				$data['intros'][] = array(
					
					'title' => $result['title'],
					'type' => $result['type'],
					'id' => $result['id'],
					'description'  => $result['description'],
					'icon' => HTTPS_SERVER.'image/'.$result['icon'],
					'image' => HTTPS_SERVER.'image/'.$result['image']
				);
			}
		}
		}
       // $data['intros']=false;
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

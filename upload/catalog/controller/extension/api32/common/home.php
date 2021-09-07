<?php
class ControllerExtensionApi32CommonHome extends Controller {
	public function index() {
		$this->load->language('common/menu');
       if($this->valid($this->request->get['token'])){
	     $this->load->controller('extension/api32/common/language');
        $this->load->controller('extension/api32/common/currency');
	  $data['success']=true;
        
        
       }else {
           $data['error']="token not set .please set token in admin management application with token in global variable ";
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
	

	
	public function getLayouts() {
	
        $this->load->model('extension/api/home/home');
	$language=$this->request->post['language'];
	
	$data['header_design']=$this->config->get('storeapp_header_design');
	
	$layouts=$this->config->get('storeapp_layout_active');
	$data['layout']=array();
	foreach($layouts as $key=>$layout){
	if($key==$language){
	 foreach($layout as $layout_id){
	   $data['layout'][]=array(
	   'layout_id'=>$layout_id,
	    'layout_name'=> $this->model_extension_api_home_home->getHomePageLayout($layout_id),
	    'layout_sort'=> $this->model_extension_api_home_home->getHomePageLayoutSort($layout_id)
		);
	}
	$sort_order = array();

			foreach ($data['layout'] as $key => $value) {
				$sort_order[$key] = $value['layout_sort'];
			}

			array_multisort($sort_order, SORT_ASC, $data['layout']);
		}
	}
	//print_r($data['layout']);
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
	

	private function valid($token) {
    
	    if($token==$this->config->get('storeapp_token')){
	        return true;
	        
	    }else {
	        return false;
	    }
    }

	
}

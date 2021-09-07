<?php 
class ControllerExtensionApi32CommonColor extends Controller {
    public function index(){
       
        $data['primary_bg_color']         = $this->config->get('storeapp_primary_bg_color');
        $data['primary_text_color']         = $this->config->get('storeapp_primary_text_color');
	    $data['colorheader']         = $this->config->get('storeapp_colorheader');
	    $data['colorheader_text']         = $this->config->get('storeapp_colorheader_text');
	    $data['bg_color']       = $this->config->get('storeapp_bg_color');
	   
	    $data['colorcathome']        = $this->config->get('storeapp_colorcathome');
	    $data['colortextcathome']    = $this->config->get('storeapp_colortextcathome');
	    
	     $data['disablebtncolor_text'] = $this->config->get('storeapp_disable_btn_color_text');
        $data['disablebtncolor'] = $this->config->get('storeapp_disable_btn_color');
	    //font family
	     $data['fontfamily_ltr']   = $this->config->get('storeapp_fontfamily_ltr');
	     $data['fontfamily_rtl']   = $this->config->get('storeapp_fontfamily_rtl');
	      
	    $data['language']            = $this->config->get('storeapp_language_first');
	    $data['currency']            = $this->config->get('config_currency');
	    $this->session->data['language']=$this->config->get('storeapp_language_first');
	     $this->session->data['currency']=$this->config->get('config_currency');
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
	 
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


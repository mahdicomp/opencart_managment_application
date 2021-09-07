<?php
class ControllerExtensionApi32CommonToken extends Controller {
    public function index() {
	    $this->load->language('common/menu');
		
        $token          = $this->db->escape($this->request->post['token']);
        $customer_id    = $this->db->escape($this->request->post['customer_id']);
        $uuid           = $this->db->escape($this->request->post['uuid']);
        $manufacturer   = $this->db->escape($this->request->post['manufacturer']);
        $model          = $this->db->escape($this->request->post['model']);
        $version        = $this->db->escape($this->request->post['version']);
        $platform       = $this->db->escape($this->request->post['platform']);
        $serial         = $this->db->escape($this->request->post['serial']);
        $os             = $this->db->escape($this->request->post['os']);
        $versionApp     = $this->db->escape($this->request->post['versionApp']);
        if($this->request->post['customer_id']==0){
            $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "token_device WHERE token = '" . $token . "'");
            if($query->num_rows){
                $this->db->query("UPDATE " . DB_PREFIX . "token_device SET customer_id = '" .  $customer_id . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', version = '" . $version . "', platform = '" . $platform . "', os = '" . $os . "', versionApp = '" . $versionApp . "',date_modified = NOW() WHERE token = '" . $token . "'");
            }else{
                $this->db->query("INSERT INTO " . DB_PREFIX . "token_device SET customer_id = '" .  $customer_id . "', token = '" . $token . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', version = '" . $version . "', platform = '" . $platform . "', os = '" . $os . "', versionApp = '" . $versionApp . "',date_added = NOW(),date_modified = NOW()");    
            }
            // $this->db->query("INSERT INTO " . DB_PREFIX . "token_device SET customer_id = '" .  $customer_id . "', token = '" . $token . "', uuid = '" . $uuid . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', version = '" . $version . "', platform = '" . $platform . "', serial = '" . $serial . "', os = '" . $os . "', versionApp = '" . $versionApp . "',date_added = NOW()");
            
        }else if($this->request->post['version']!=""){
            $this->db->query("UPDATE " . DB_PREFIX . "token_device SET customer_id = '" .  $customer_id . "', manufacturer = '" . $manufacturer . "', model = '" . $model . "', version = '" . $version . "', platform = '" . $platform . "', os = '" . $os . "', versionApp = '" . $versionApp . "',date_modified = NOW() WHERE token = '" . $token . "'");
        }else {
          $this->db->query("UPDATE " . DB_PREFIX . "token_device SET customer_id = '" .  $customer_id . "',date_modified = NOW() WHERE token = '" . $token . "'");
        }
        $data="ok";

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

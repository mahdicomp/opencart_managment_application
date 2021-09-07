<?php
class ControllerExtensionApi32CommonModule extends Controller {
	public function index() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('common/module');

		// Menu
		
	    $data['homemodule'] = array();
	    	if(isset($this->request->post['language'])){
			$language=	$this->request->post['language']; 
		 }else{
			 $language=	$this->config->get('config_language'); 
		 }
	//	 echo $language;
	    $data['storename'] = $this->config->get('storeapp_name');
	    if($this->config->get('storeapp_icon')){
	        $icon=$this->config->get('storeapp_icon');
	      //  print_r($icon);
	        $data['storeicon'] = HTTP_SERVER.'image/'.$icon[$language];
	    }
		 $data['first_checkout'] = HTTP_SERVER.'image/'.$this->config->get('storeapp_checkout_first_icon');
	    //////////////////get module opencart 
		 if(isset($this->request->post['layout_id'])){
			  $layout_id=$this->request->post['layout_id'];
			 
		
		 $sql = 'Select id_component from ' . DB_PREFIX . 'storeapp_layout_component where id_layout = '. (int) $layout_id .' order by position asc';
        $query = $this->db->query($sql); 
        $components = $query->rows;
		 $i=1;
		 foreach ($components as $key => $comp) {
                $query = $this->db->query('Select id_component_type from ' . DB_PREFIX . 'storeapp_layout_component where id_component = '.  $comp['id_component']) ;
                $component_type_id = $query->row['id_component_type'];
                $query = $this->db->query('Select component_name from ' . DB_PREFIX . 'storeapp_component_types where id = '.  $component_type_id) ;
                $component_type = $query->row['component_name'];
				 $query = $this->db->query('Select * from  ' . DB_PREFIX . 'storeapp_product_data where id_component =' . (int) $comp['id_component']);
        $product_data = $query->row;
		$product_type="";
        if (count($product_data) > 0) {
            $product_type = $product_data['product_type'];
			}
                $data['homemodule'][]=array(
							"name"=>$component_type,
							"value"=>1,
							"id_component"=>$comp['id_component'],
							"product_type"=> $product_type,
							"sort"=>$i
			);
			$i++;
          }				  
		
	
			$sort_order = array();

			foreach ($data['homemodule'] as $key => $value) {
			    
				$sort_order[$key] = $value['sort'];
			    
			}

			array_multisort($sort_order, SORT_ASC, $data['homemodule']);
		 }	
		$data['languages_active']=	$this->config->get('storeapp_language_active');	
	
	   $data['currencies'] = array();
        $this->load->model('localisation/currency');
		$results = $this->model_localisation_currency->getCurrencies();

		foreach ($results as $result) {
			if ($result['status']) {
				$data['currencies'][] = array(
					'title'        => $result['title'],
					'code'         => $result['code']
					
				);
			}
		}
	
		$data['ticket_status']=	$this->config->get('storeapp_ticket');
		$data['multivendor_status']=	$this->config->get('storeapp_multivendor');
		$data['smsverify_status']=	$this->config->get('storeapp_smsverify');
		$data['reward_status']=	$this->config->get('storeapp_reward');
		$data['letmeknow_status']=	$this->config->get('storeapp_letmeknow');
		$data['information_help']=	$this->config->get('storeapp_information_help');
		$data['inappbrowser']=	$this->config->get('storeapp_inappbrowser');
		$data['product_button_design']=	$this->config->get('storeapp_product_list_design');
			////////update////////////
		
			$data['update_url']=	$this->config->get('storeapp_update_url');
			$data['update_message']=html_entity_decode(	$this->config->get('storeapp_update_message'), ENT_QUOTES, 'UTF-8');
			$data['update_version']=	$this->config->get('storeapp_update_version');
			$data['update_need']=	$this->config->get('storeapp_update_need');
			$data['update_alert']=	$this->config->get('storeapp_update_alert');
		
		
		///////end update/////////////
			$data['filter_display']=	$this->config->get('storeapp_filter_design');

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

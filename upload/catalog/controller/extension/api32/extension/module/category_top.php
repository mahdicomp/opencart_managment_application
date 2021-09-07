<?php
class ControllerExtensionApi32ExtensionModuleCategoryTop extends Controller {
	public function index() {
		
		  $this->load->controller('extension/api32/common/language');
		// Menu
		$data['categories'] = array();
		$this->load->model('catalog/category');
		$this->load->model('catalog/product');
		$this->load->model('tool/image');
		
        $id_component= $this->request->post['id_component'];
        $category_in_home=0;
      $query = $this->db->query('SELECT *  FROM ' . DB_PREFIX . 'storeapp_top_category
                            where id_component = '.(int)$id_component);
                    $categories = $query->rows; 
                    if (is_array($categories)) {
                        if (count($categories) > 0 && !empty($categories)) {
                            foreach ($categories as $k => $value) {
                               $sql = 'SELECT name  FROM ' . DB_PREFIX . 'category_description
                                        where category_id = '.(int)$value['id_category'] .' And language_id = '.(int)$this->config->get('config_language_id');
                                $query = $this->db->query($sql);
                                if(isset($query->row['name'])) {
                                    $category_name = $query->row['name'];
                                } else {
                                    $category_name = "";
                                }
                               
                                $id = $value['id_category'];
                                if ($value['image_url'] != '') {
                                    $image_src= HTTPS_SERVER.'image/'.$value['image_url'];
                                }
                                $image_contentMode = $value['image_content_mode']?'scaleAspectFit':'scaleAspectFill';
                                $name = $category_name;
								$category_in_home=$value['category_in_home'];
                               $data['categories'][] = array(
								'name'     => str_replace('&amp;', '&', $name),
								"category_id" =>  $id,
								'imageurl' => $image_src
								
							);
                                
                            }
                           
							
                         
                        }
                    }
        
	  
     $data['show_category']=$this->config->get('storeapp_select_show_category'); 
     $data['show_category_inhome']=$category_in_home;
	 if($category_in_home==0){
       $categories = $this->model_catalog_category->getCategories(0);
        $flag=true;
		foreach ($categories as $category) {
			 $children_data = array();
			 
             	
			if($flag){
                $data['first_category_id']=$category['category_id'];
                $flag=false;
                }
           if ($category['image']) {
             	$thumb = $this->model_tool_image->resize($category['image'], '200', '200');
	
			} else {
				$thumb= '';
			}
			
			
				$data['categories'][] = array(
					'name'     => str_replace('&amp;', '&', $category['name']),
					"category_id" =>  $category['category_id'],
					'imageurl' => $thumb
					
				);
			
		}
		
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
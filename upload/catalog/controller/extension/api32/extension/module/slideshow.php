<?php
class ControllerExtensionApi32ExtensionModuleSlideshow extends Controller {
	public function index() {
		static $module = 0;		

    	$this->load->controller('extension/api32/common/language');
		$this->load->model('design/banner');
		$this->load->model('tool/image');
        $this->load->model('catalog/category');
		  $data = array();
		   $id_component= $this->request->post['id_component'];
                    $square_banner_data = array();
                    $sql = 'Select * from  ' . DB_PREFIX . 'storeapp_banners where id_component =' . (int) $id_component;
		        $query = $this->db->query($sql);
                    $banner_data = $query->rows;
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            
                            foreach ($banner_data as $k => $bd) {
                              
                               $category="";
                                if ($bd['redirect_activity'] == 'category') {
                                    $target_id = $bd['category_id'];
									 $category_info = $this->model_catalog_category->getCategory($target_id);
                                     if ($category_info) {
									$category = $category_info['name'];
								  }
                                } else {
                                    $target_id = $bd['product_id'];
                                }
								$slideshow_design = $bd['banner_design'];
											   
									  $data['slides'][] = array(
									'title' => $bd['redirect_activity'],
									'link'  => $target_id,
									'category' => $category,
									'imageurl' => HTTPS_SERVER.'image/'.$bd['image_url']
								   );
								 }
									   }
                    } 
			$data['slideshow_design'] = $slideshow_design;
         $data['result']='OK';
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
<?php
class ControllerExtensionApi32ExtensionModuleBanner extends Controller {
	public function index() {
		static $module = 0;	
    	$this->load->controller('extension/api32/common/language');	
       $this->load->model('catalog/category');
		$this->load->model('design/banner');
		$this->load->model('tool/image');
		$category = '';
         $id_component= $this->request->post['id_component'];
		 $sql = 'Select heading from  ' . DB_PREFIX . 'storeapp_components_heading where id_component =' . (int) $id_component;
                    $query = $this->db->query($sql);
                    $banner_heading = '';
                    if($query->num_rows) {
                       $data['banner_heading'] = $query->row['heading'];
                    }
                    
                    $square_banner_data = array();
                    $sql = 'Select * from  ' . DB_PREFIX . 'storeapp_banners where id_component =' . (int) $id_component;
                    $query = $this->db->query($sql);
                    $banner_data = $query->rows;
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            $i = 0;
                            foreach ($banner_data as $k => $bd) {
                                $category = "";
                                $data['click_target'] = $bd['redirect_activity'];
                                 if ($bd['redirect_activity'] == 'category') {
                                    $target_id = $bd['category_id'];
									 $category_info = $this->model_catalog_category->getCategory($target_id);
                                     if ($category_info) {
									$category = $category_info['name'];
								  }
                                } else {
                                    $target_id = $bd['product_id'];
                                }
                                $src= HTTPS_SERVER.'image/'.$bd['image_url'];
                                	$banner_design = $bd['banner_design'];
								 $image_contentMode = ($bd['image_content_mode']==0)?'100':'50';
							//	 echo $bd['image_content_mode'];
                                $data['banners'][] = array(
									'title' => $bd['redirect_activity'],
									'link'  => $target_id,
									'category' => $category,
									'imageurl' => $src,
									'size' =>$image_contentMode,
									'sort' =>$i,
								);
								$i++;
                            }
                           
                        }
                    }
		
		$data['banner_design']=$banner_design;
		
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
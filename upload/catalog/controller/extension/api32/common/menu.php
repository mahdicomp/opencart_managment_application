<?php
class ControllerExtensionApi32CommonMenu extends Controller {
	public function index() {
		$this->load->language('common/menu');
       $this->load->controller('extension/api32/common/language');
		// Menu
		$this->load->model('catalog/category');
        $this->load->model('catalog/product');
		$this->load->model('tool/image');
		$data['categories'] = array();
		$parentid= $this->request->post['parentid'];
     
      
     $data['show_category']=$this->config->get('storeapp_select_show_category'); 
     $data['show_category_inhome']=$this->config->get('storeapp_select_show_category_inhome');
      if($this->config->get('storeapp_select_show_category_inhome')=="0"){
          
	     	foreach ($this->config->get('storeapp_categorie_icons') as $category) {
      	// Level 1
      	     if ($category['icon']) {
                     	$thumb = $this->model_tool_image->resize($category['icon'], '200', '200');
        	
        			} else {
        				$thumb= '';
        			}
        		$category_info = $this->model_catalog_category->getCategory ($category['id']);
				$data['categories_image'][] = array(
					'name'     => str_replace('&amp;', '&', $category_info['name']),
					"category_id" =>  $category['id'],
					'imageurl' => $thumb
					
				);
           	}
      }
          
        
		$categories = $this->model_catalog_category->getCategories($parentid);
        $flag=true;
		foreach ($categories as $category) {
			 $children_data = array();
			  
                $children = $this->model_catalog_category->getCategories($category['category_id']);
                 $subchildrenparenthasfalse=0;
                foreach($children as $child) {
                    $filter_data = array('filter_category_id' => $child['category_id'], 'filter_sub_category' => true);
                    if ($child['image']) {
                     	$thumb = $this->model_tool_image->resize($child['image'], '200', '200');
        	
        			} else {
        				$thumb= '';
        			}
        	    $children2 = $this->model_catalog_category->getCategories($child['category_id']);
        	    $children2level_data = array();
        	     $subchildren=false;   
                  $subchildrenparent=false;
                  
        		 foreach($children2 as $child2) {
        		   $subchildren=true;
        		   //if()
                   $subchildrenparent=true;   
                    //$subchildrenparenthasfalse=2;
                    $filter_data = array('filter_category_id' => $child2['category_id'], 'filter_sub_category' => true);
                    if ($child2['image']) {
                     	$thumb2 = $this->model_tool_image->resize($child2['image'], '200', '200');
        	
        			} else {
        				$thumb2= '';
        			}	
        			$children2level_data[]=array(
                        'category_id' => $child2['category_id'],
                        'name' => str_replace('&amp;', '&', $child2['name']) ,
                        'count' =>$this->config->get('config_product_count') ? ' ' . $this->model_catalog_product->getTotalProducts($filter_data) . '' : '',
                       'imageurl' => $thumb2   
                       );
                       
                }
                
                if($subchildrenparent==false) $subchildrenparenthasfalse=1;
        			
                    $children_data[] = array(
                        'category_id' => $child['category_id'],
                        'children'    => $children2level_data,
                        'subchildren'    => $subchildren,
                        'name' => str_replace('&amp;', '&', $child['name']) ,
                        'count' =>$this->config->get('config_product_count') ? ' ' . $this->model_catalog_product->getTotalProducts($filter_data) . '' : '',
                       'imageurl' => $thumb   
                       );
                }
				// Level 2
			
				
			if($flag){
                $data['first_category_id']=$category['category_id'];
                $flag=false;
                }
           if ($category['image']) {
             	$thumb = $this->model_tool_image->resize($category['image'], '200', '200');
	
			} else {
				$thumb= '';
			}
			
			$this->load->model('extension/api/catalog/manufacturer');
            $brands = $this->model_extension_api_catalog_manufacturer->getBrandsByCategoryId($category['category_id']);
            $brandslist = array();
            foreach ($brands as $brand) {
            	$image = $brand['image'];
            	$brandslist[] = array(
            		'name'			=> $brand['name'],
            		'image'			=> $this->model_tool_image->resize($brand['image'], 100, 100),
            		'manufacturer_id' => $brand['manufacturer_id'] 
            		
            	);
            }
				// Level 1
				$data['categories'][] = array(
					'name'     => str_replace('&amp;', '&', $category['name']),
					"category_id" =>  $category['category_id'],
				    "brands" =>   $brandslist,
				    'subchildrenparenthasfalse'    => $subchildrenparenthasfalse,
					'children'    => $children_data,
					'imageurl' => $thumb
					
				);
			
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

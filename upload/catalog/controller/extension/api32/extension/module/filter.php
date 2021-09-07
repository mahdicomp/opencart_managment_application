<?php
class ControllerExtensionApi32ExtensionModuleFilter extends Controller {
	public function index() {
		if (isset($this->request->post['category_id'])) {
			$category_id = $this->request->post['category_id'];
		} else {
			$category_id = 0;
		}
	
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');

		

		$this->load->model('catalog/category');

		$category_info = $this->model_catalog_category->getCategory($category_id);

		if ($category_info) {
			$this->load->language('extension/module/filter');

			
			$filter_data = array( 'filter_category_id' => $category_id );
			$this->load->model('extension/api/catalog/filter');
			$fprice = $this->model_extension_api_catalog_filter->getMinPrice($filter_data ); 
			$data['min_price'] = $fprice['min']; 
			$data['max_price'] = $fprice['max']; 
			
			$this->load->model('extension/api/catalog/manufacturer');
            $brands = $this->model_extension_api_catalog_manufacturer->getBrandsByCategoryId($category_id);
           
            foreach ($brands as $brand) {
            	
            	$data['manufacturers'][] = array(
            		'name'			=> $brand['name'],
            		'manufacturer_id' => $brand['manufacturer_id'] 
            		
            	);
            }
		//	$data['manufacturers'][] = $manufacturerscategory;
		
              $attributes_groups=array();
          
         $attributes_groups = $this->model_extension_api_catalog_filter->getAttributes($category_id);
          
           
             $sections = [];
			  foreach ($attributes_groups as $attribute_groups_id => $attribute_group) {

                 
				$i=0;
                foreach ($attribute_group['attribute'] as $attribute_id => $attribute_value) {
                //print_r($attribute_value);
                        $values = [];
                        foreach ($attribute_value['values'] as $value) {
						//	print_r($value);
                            if (!empty($value) || $value === '0') {

                                $input_label = $value;
                                $is_enable = true;
                              

                                $values[] = array(
                                    'input_value' => $value['value'],
									'input_id' => $i,
									'id' => $attribute_id,
                                
                                );
                            }
							$i++;
                        }

                        if ($values) {
                           
                            $sections[] = array(
                                'id' => $attribute_id,
                                'name' => $attribute_value['attribute_name'],
                                'values' => $values,
                                
                            );
                        }
                    }
                

                if ($sections) {
                    $section_groups[] = array(
                        'group_name' => $attribute_group['name'],
                        'sections' => $sections,
                    );
                    $section_all[] = $sections;
                }
				$i++;
		}
            
        
		//	print_r($section_all);
			$data['attribute'] =$sections;
			
		//	$data['attribute']=array();
			
			$data['options'] = $this->model_extension_api_catalog_filter->getCategoryOptions($category_id);
		
			if (isset($this->request->post['filter'])) {
				$data['filter_category'] = explode(',', $this->request->post['filter']);
			} else {
				$data['filter_category'] = array();
			}

			$this->load->model('catalog/product');

			$data['filter_groups'] = array();

			$filter_groups = $this->model_extension_api_catalog_filter->getCategoryFilters($category_id);

			if ($filter_groups) {
				foreach ($filter_groups as $filter_group) {
					$childen_data = array();

					foreach ($filter_group['filter'] as $filter) {
						$filter_data = array(
							'filter_category_id' => $category_id,
							'filter_filter'      => $filter['filter_id']
						);
                        if($this->model_catalog_product->getTotalProducts($filter_data) >0){
    						$childen_data[] = array(
    							'filter_id' => $filter['filter_id'],
    							'name'      => $filter['name'] 
    						);
                        }
					}
                   if($childen_data){
					$data['filter_groups'][] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $childen_data
					);
                   }
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
}
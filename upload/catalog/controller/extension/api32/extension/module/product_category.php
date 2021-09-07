<?php
class ControllerExtensionApi32ExtensionModuleProductCategory extends Controller {
	public function index() {
	    
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
       $this->load->model('catalog/category');
      	$this->load->model('catalog/product');
		$this->load->model('tool/image');
		$this->load->language('extension/module/product_category');
		  /////////new wishlist ///////
    	$this->load->model('account/wishlist');
	  if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		//////////

		  $id_component= $this->request->post['id_component'];
		 $query = $this->db->query('Select * from  ' . DB_PREFIX . 'storeapp_product_data where id_component =' . (int) $id_component);
        $product_data = $query->row;
        if (count($product_data) > 0) {
        $queryhead = $this->db->query('Select * from  ' . DB_PREFIX . 'storeapp_components_heading where id_component =' . (int) $id_component);
        $heading = $queryhead->row;
	
		   $category = $this->model_catalog_category->getCategory($product_data['id_category']);
			if ($category) {
			
					$limit = $this->config->get('storeapp_limit_home');
				
				
				$products_array = array();
				$sort = 'p.quantity';
				$order = 'DESC';
					if (isset($this->request->post['page'])) {
            			$page = $this->request->post['page'];
            		} else {
            			$page = 1;
            		}
				$filter_data = array(
					'filter_category_id' => $product_data['id_category'],
					'filter_sub_category'=> true,
					'sort'               => $sort,
					'order'              => $order,
					'start'              => ($page - 1) * $limit,
					'limit'              => $limit
				);
				
				$products = $this->model_catalog_product->getProducts($filter_data);
				//print_r($products );
		        $json['count']=false;
				foreach ($products as $product) {
					$product_info = $this->model_catalog_product->getProduct($product['product_id']);
		
					if ($product_info) {
						if ($product_info['image']) {
							$image = $this->model_tool_image->resize($product_info['image'], 200, 200);
						} else {
							$image = $this->model_tool_image->resize('placeholder.png', 200, 200);
						}
		
						
				
			    if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					if($this->config->get('storeapp_quantity_priceshow') && $product_info['quantity']<1 ){
						$price = 'تمام شد';
					}else {
						$price = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				    }
				} else {
					$price = false;
				}

				if ((float)$product_info['special']) {
					$special = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = -1;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}
		
				if ($this->config->get('config_review_status')) {
							$rating = $product_info['rating'];
				} else {
							$rating = false;
				}
						
					
              if((float)$product_info['special']){
                  $percentage= round(100/($product_info['price']/($product_info['price'] - $product_info['special'])), 1, PHP_ROUND_HALF_UP);
                 }else {
                  $percentage=false;   
                 }
		           
						$products_array[] = array(
							'id'  => $product_info['product_id'],
							'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($product_info['product_id']),
							'imageurl'       => $image,
							'name'        => utf8_substr(strip_tags(html_entity_decode( str_replace('&amp;', '&', $product_info['name']), ENT_QUOTES, 'UTF-8')), 0, 40),
							'price'       => $price,
							'special_price'     => $special,
							 'percentage'     =>	$percentage,
							'tax'         => $tax
						);
					}
					$json['count']=true;
					 
				}
				
			
				if(isset($heading['icon'])){
        		  	$icon =HTTPS_SERVER.'image/'.$heading['icon'];
        		}else {
        		    $icon =null;
        		}
				$json['categories'][] = array(
				'id' => $product_data['id_category'],
				'name'        => utf8_substr(strip_tags(html_entity_decode( str_replace('&amp;', '&', $category['name']), ENT_QUOTES, 'UTF-8')), 0, 100),
				'icon'        =>  $icon ,
				'count'=>$json['count'],
				'products'    => $products_array
				);
			}
		   
		 
		}
		
		
		if ($json['categories']) {
		    
		    if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
			
			
			
		}
	}
}
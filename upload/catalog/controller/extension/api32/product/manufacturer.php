<?php
class ControllerExtensionApi32ProductManufacturer extends Controller {
	public function index() {
	    
	      $this->load->controller('extension/api32/common/language');
        $this->load->controller('extension/api32/common/currency');
		$this->load->language('product/manufacturer');

		$this->load->model('catalog/manufacturer');

		$this->load->model('tool/image');

	

		$data['manufacturer'] = array();

		$results = $this->model_catalog_manufacturer->getManufacturers();

		foreach ($results as $result) {
			if (is_numeric(utf8_substr($result['name'], 0, 1))) {
				$key = '0 - 9';
			} else {
				$key = utf8_substr(utf8_strtoupper($result['name']), 0, 1);
			}
			if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}

			$data['manufacturer'][] = array(
				'name' => $result['name'],
					'image' =>$image,
				'manufacturer_id' => $result['manufacturer_id']
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

	public function info() {
	      $this->load->controller('extension/api32/common/language');
        $this->load->controller('extension/api32/common/currency');
		$this->load->language('product/manufacturer');

		$this->load->model('catalog/manufacturer');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
		
		/////////new wishlist ///////
    	$this->load->model('account/wishlist');
	    if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		//////////

		if (isset($this->request->post['manufacturer_id'])) {
			$manufacturer_id = (int)$this->request->post['manufacturer_id'];
		} else {
			$manufacturer_id = 0;
		}

        	if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
	    	} else {
			$page = 1;
	    	}
		
			if(isset($this->request->post['sort'])){
             $sortaray=explode("&",$this->request->post['sort']);
             $data['sort']=$sortaray[0];
             $order = str_replace('amp;', '', $sortaray[1]);
             $data['order']= $order;
             //echo $data['sort'];
            }

		if (isset($data['sort'])) {
			$sort = $data['sort'];
		} else {
			$sort = 'p.date_added';
		}

		if (isset($data['order'])) {
			$order = $data['order'];
		} else {
			$order = 'DESC';
		}

		if (isset($this->request->post['limit'])) {
			$limit = (int)$this->request->post['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

	

		$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($manufacturer_id);

		if ($manufacturer_info) {
		

			

			$data['products'] = array();

			$filter_data = array(
				'filter_manufacturer_id' => $manufacturer_id,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
			);

			$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			$results = $this->model_catalog_product->getProducts($filter_data);
            $data['count']=false;
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					if($this->config->get('storeapp_quantity_priceshow') && $result['quantity']==0 ){
						$price = 'تمام شد';
					}else {
						$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				    }
				} else {
					$price = false;
				}

				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = -1;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}
				 
              if((float)$result['special']){
                  $percentage= round(100/($result['price']/($result['price'] - $result['special'])), 1, PHP_ROUND_HALF_UP);
                 }else {
                  $percentage=false;   
                 }

				$data['products'][] = array(
					'id'  => $result['product_id'],
					'imageurl'       => $image,
					'name'        => $result['name'],
					'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($result['product_id']),
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special_price'     => $special,
					  'percentage'     =>	$percentage,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					
				);
				
				
				 $data['count']=	true ;
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

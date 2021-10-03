<?php
class ControllerExtensionApi32ProductCategory extends Controller {
	public function index() {
        $this->load->controller('extension/api32/common/language');
        $this->load->controller('extension/api32/common/currency');
		$this->load->language('product/category');

		$this->load->model('catalog/category');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');

        /////////new wishlist ///////
    	$this->load->model('account/wishlist');
	    if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		//////////
		
		if (isset($this->request->post['filter'])) {
			$filter = $this->request->post['filter'];
		} else {
			$filter = '';
		}
		$filter_price = array();
        $rate = (float) $this->currency->getValue($this->session->data['currency']);
        if (isset($this->request->post['price'])) {
            $price_data = explode(',', $this->request->post['price'] );
            if( count($price_data) == 2  ){
				$filter_price['min_price'] = ceil($price_data[0] / $rate - 1);
				$filter_price['max_price'] = round($price_data[1] / $rate);
			}
            
         }   
        $manufacturers = array();
        if( isset($this->request->post['manufacturer']) && $this->request->post['manufacturer'] ){
         
            $manufacturersarray = explode( ",", $this->request->post['manufacturer'] );
                foreach ($manufacturersarray as $key => $value) {
                    if($value)
                     $manufacturers[$key] = (int)$value;
                }
         }
        $filter_price= $filter_price;
		//print_r($filter_price);
       //print_r($manufacturers);
        $filter_manufacturers= $manufacturers;
		
		if (isset($this->request->post['filter_quantity'])) {
			$filter_quantity =true;
		} else {
			$filter_quantity = false;
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
			$sort = 'p.sort_order';
		}

		if (isset($data['order'])) {
			$order = $data['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
		if (isset($this->request->post['options'])) {
			$options = $this->request->post['options'];
		} else {
			$options = false;
		}
			
		if (isset($this->request->post['attributes'])) {
			$filter_attributes = array_filter($this->request->post['attributes']);
		} else {
			//$filter_attributes[4][] = '14';
			//$filter_attributes[2][] = '4';
            $filter_attributes=false;
		}

		if (isset($this->request->post['limit'])) {
			$limit = (int)$this->request->post['limit'];
		} else {
			$limit = $this->config->get('theme_' . $this->config->get('config_theme') . '_product_limit');
		}

	
		$category_id=$this->request->post['category_id'];

		$category_info = $this->model_catalog_category->getCategory($category_id);
		//$data['count']=false;

		if ($category_info) {
			

			


			$results = $this->model_catalog_category->getCategories($category_id);

		

			$data['products'] = array();

			$filter_data = array(
				'filter_category_id'     => $category_id,
				'filter_filter'          => $filter,
				'filter_sub_category'    => true,
				'filter_options'         => $options,
				'filter_attributes'      => $filter_attributes,
				'filter_price'           => $filter_price,
				'filter_manufacturers'   => $filter_manufacturers,
				'filter_quantity'        => $filter_quantity,
				'sort'                   => $sort,
				'order'                  => $order,
				'start'                  => ($page - 1) * $limit,
				'limit'                  => $limit
			);

            $this->load->model('extension/module/filter_pro');
			$product_total = $this->model_extension_module_filter_pro->getTotalProducts($filter_data);
            //$product_total = $this->model_catalog_product->getTotalProducts($filter_data);

			if($sort=='p.sell'){
    			$this->load->model('extension/api/catalog/product');
    			$results = $this->model_extension_api_catalog_product->getBestSellerProductsCategory($filter_data);
			}else {
    			$results = $this->model_extension_module_filter_pro->getProducts($filter_data);
                //$results = $this->model_catalog_product->getProducts($filter_data);
			}
            $data['count']=false;
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				    //$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					if($this->config->get('storeapp_quantity_priceshow') && $result['quantity']<1){
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
					'id'             => $result['product_id'],
					'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($result['product_id']),
					'imageurl'       => $image,
					'name'           => str_replace('&amp;', '&', $result['name']),
					'description'    => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'          => $price,
					'special_price'  => $special,
					  'percentage'     =>	$percentage,
					'tax'            => $tax,
					'minimum'        => $result['minimum'] > 0 ? $result['minimum'] : 1,
				);
				$data['count']=true;
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

<?php
class ControllerExtensionApi32ExtensionModuleSpecial extends Controller {
	public function index() {
    	$this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
		$this->load->language('extension/module/special');
        
        	if(isset($this->request->post['language'])){
			$language=	$this->request->post['language']; 
		 }else{
			 $language=	$this->config->get('config_language'); 
		 }
		     $id_component= $this->request->post['id_component'];
		             $sql = 'Select heading from  ' . DB_PREFIX . 'storeapp_components_heading where id_component =' . (int) $id_component ;
                    $query = $this->db->query($sql);
                    $banner_heading = '';
                    if($query->num_rows) {
                        $data['banner_heading']= $query->row['heading'];
                    }
                    $square_banner_data = array();
                    $sql = 'Select * from  ' . DB_PREFIX . 'storeapp_banners where id_component =' . (int) $id_component;
                    $query = $this->db->query($sql);
                    $banner_data = $query->rows;
                    if (is_array($banner_data)) {
                        $number_of_entries = count($banner_data);
                        if ($number_of_entries > 0) {
                            
                            foreach ($banner_data as $k => $bd) {
                                $data = array();
                                $data['click_target'] = $bd['redirect_activity'];
                                if ($bd['redirect_activity'] == 'category') {
                                    $data['target_id'] = $bd['category_id'];
                                    $category_id=$bd['category_id'];
                                } else {
                                    $data['target_id'] = $bd['product_id'];
                                }
                                $src = HTTPS_SERVER.'image/'.$bd['image_url'];
                                
                              
                                $upto_time = $bd['countdown'];
                                $text_color = array();
                                $text_color = explode("#", $bd['text_color']);
                                $timer_text_color = $text_color[1];
                                
                                if ($bd['is_enabled_background_color'] == 1) {
                                    $background_color = array();
                                    $background_color = explode("#", $bd['background_color']);
                                    $timer_background_color = $background_color[1];
                                } else {
                                    $timer_background_color = '';
                                }
                                
                            }
                           
                        }
                    }
		 
		

		$this->load->model('extension/api/catalog/product');

		$this->load->model('tool/image');

	  /////////new wishlist ///////
    	$this->load->model('account/wishlist');
	    if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		//////////
		$data['products'] = array();

			$data['products'] = array();
		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}
  
         $data['storeapp_special_design'] = $this->config->get('storeapp_special_design');
         $data['storeapp_special_background'] = $timer_background_color;
         $this->load->model('tool/image');
         
         $data['storeapp_special_background_image'] =$src;
         
       $data['special_text_color'] = $timer_text_color;
        $data['storeapp_special_date'] = ($this->language->get('code') == 'fa') ? jtg($upto_time) : $upto_time;
  
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
			if (isset($category_id)) {
			$filter_category_id = $category_id;
		} else {
			$filter_category_id = '';
		}
		

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'filter_category_id' => $filter_category_id,
			'start'                  => ($page - 1) * $this->config->get('storeapp_limit_home'),
			'limit'                  => $this->config->get('storeapp_limit_home')
		);


		$results = $this->model_extension_api_catalog_product->getProductSpecials($filter_data);
        $data['count']=false;
		if ($results) {
			foreach ($results as $result) {
					if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], 250, 250);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png',250, 250);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					if($this->config->get('storeapp_quantity_priceshow') && $result['quantity']<1 ){
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
					$rating = $result['rating'];
				} else {
					$rating = false;
				}
				$percentage= round(100/($result['price']/($result['price'] - $result['special'])), 1, PHP_ROUND_HALF_UP);

				$data['products'][] = array(
					'id'  => $result['product_id'],
					'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($result['product_id']),
					'sold' 	     =>  $this->model_extension_api_catalog_product->getUnitsSold($result['product_id']),
					'quantity'        => $result['quantity'],
					'imageurl'       => $image,
					'name'        => $result['name'],
					'date_start'        => $result['date_start'],
					'date_end'        => $result['date_end'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special_price'     => $special,
				     'percentage'     =>	$percentage,
					'tax'         => $tax
				
				);
				$data['count']=true;
			}
				

		}
		
		  if($this->config->get('storeapp_special_icon')){
		  	$data['icon'] =HTTPS_SERVER.'image/'.$this->config->get('storeapp_special_icon');
		}else {
		    $data['icon'] =null;
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
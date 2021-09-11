<?php
class ControllerExtensionApi32ExtensionModuleLatest extends Controller {
	public function index() {
	
        $this->load->controller('extension/api32/common/language');
        $this->load->controller('extension/api32/common/currency');

		$this->load->language('extension/module/latest');

		$this->load->model('catalog/product');

		$this->load->model('tool/image');
			$data['result'] = "ok";



         /////////new wishlist ///////
    	$this->load->model('account/wishlist');
		if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		//////////
         
		$data['products'] = array();
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

		$filter_data = array(
			'sort'  => $sort,
			'order' => $order,
			'start'                  => ($page - 1) * $this->config->get('storeapp_limit_home'),
			'limit'                  => $this->config->get('storeapp_limit_home')
		);
		

		$results = $this->model_catalog_product->getProducts($filter_data);
	
        $data['count']=false;
		if ($results) {
		    $data['result'] = "ok";
			foreach ($results as $result) {
			    
			    
				if ($result['image']) {
				  //  $image = HTTP_SERVER.'/image/'.$result['image'];
					$image = $this->model_tool_image->resize($result['image'], 250, 250);
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', 250, 250);
				}

				if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
				//	$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
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

              if((float)$result['special']){
                  $percentage= round(100/($result['price']/($result['price'] - $result['special'])), 1, PHP_ROUND_HALF_UP);
                 }else {
                  $percentage=false;   
                 }
				$data['products'][] = array(
					'id'  => $result['product_id'],
					'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($result['product_id']),
					'imageurl'       => $image,
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, $this->config->get($this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special_price'     => $special,
					'percentage'     =>	$percentage,
					'tax'         => $tax
				
				);
				 $data['count']=true;
			}
           
			 
			
		}
		$id_component= $this->request->post['id_component'];
		$queryhead = $this->db->query('Select * from  ' . DB_PREFIX . 'storeapp_components_heading where id_component =' . (int) $id_component);
        $heading = $queryhead->row;
		if(!empty($heading)){
			   if($heading['icon']){
				$data['icon'] =HTTPS_SERVER.'image/'.$heading['icon'];
			}else {
				$data['icon'] =null;
			}
			if($heading['heading']){
				$data['heading'] =$heading['heading'];
			}else {
				$data['heading'] =null;
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

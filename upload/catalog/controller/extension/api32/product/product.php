<?php
class ControllerExtensionApi32ProductProduct extends Controller {
	private $error = array();

	public function index() {
	
	      if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		  }
		  $this->load->model('account/wishlist');
        $this->load->controller('extension/api32/common/language');
        $this->load->controller('extension/api32/common/currency');
		$this->load->language('product/product');

	       if (isset($this->request->post['product_id'])) {
			$product_id = (int)$this->request->post['product_id'];
		} else {
			$product_id = 0;
		}
          /////////new wishlist ///////
    	    if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		//////////
		$this->load->model('catalog/product');

		$product_info = $this->model_catalog_product->getProduct($product_id);
       
		if ($product_info) {
		

		
			$this->load->model('catalog/review');

		    $data['share'] = $this->url->link('product/product', 'product_id=' . (int)$this->request->post['product_id']);

			$data['id'] = (int)$this->request->post['product_id'];
			$data['manufacturer'] = $product_info['manufacturer'];
		    $this->load->model('catalog/manufacturer');
		    	$this->load->model('tool/image');
			$manufacturer_info = $this->model_catalog_manufacturer->getManufacturer($product_info['manufacturer_id']);
           if ($manufacturer_info) {
               $data['manufacturer_image'] =$this->model_tool_image->resize($manufacturer_info['image'], 200, 200);
           }
			$data['name'] = str_replace('&amp;', '&', $product_info['name']);
			$data['model'] = $product_info['model'];
			$data['reward'] = $product_info['reward'];
			$data['quantity'] = $product_info['quantity'];
			$data['points'] = $product_info['points'];
			$data['description'] = html_entity_decode($product_info['description'], ENT_QUOTES, 'UTF-8');
            //updated		
            $data['attribute'] = $this->model_catalog_product->getProductAttributes($this->request->post['product_id']);
            $data['review_total'] =$this->model_catalog_review->getTotalReviewsByProductId($this->request->post['product_id']);
            $data['wishlist']	 =$this->model_account_wishlist->getWishlistByproductId($this->request->post['product_id']);

			if ($product_info['quantity'] <= 0) {
				$data['stock'] = $product_info['stock_status'];
			} elseif ($this->config->get('config_stock_display')) {
				$data['stock'] = $product_info['quantity'];
			} else {
				$data['stock'] = $this->language->get('text_instock');
			}
			$this->load->model('extension/api/catalog/product');
			$dates = $this->model_extension_api_catalog_product->getProductSpecialDates($product_id);
			if (isset($dates['date_end']) && ($dates['date_end'] != "0000-00-00")) {
				$data['special_end'] = date($this->language->get('date_format_short'), strtotime($dates['date_end']));
			} else {
				$data['special_end'] = '';
			}
			
		if ($product_info['quantity'] <= 0 && (!$this->config->get('config_stock_checkout') || $this->config->get('config_stock_warning'))) {
		   $data['status'] =false;
		}else {
		    $data['status']=true;
		}


		

			if ($product_info['image']) {
				$data['thumb'] = HTTP_SERVER.'/image/'.$product_info['image'];  //$this->model_tool_image->resize($product_info['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height'));
				$data['thumbpro'] = $this->model_tool_image->resize($product_info['image'], "500", "500");
			} else {
				$data['thumb'] = '';
				$data['thumbpro'] = '';
			}

			$data['images'] = array();
			array_push($data['images'], array("imageurl" =>  	$data['thumb']) ) ;

			$results = $this->model_catalog_product->getProductImages($this->request->post['product_id']);

			foreach ($results as $result) {
				$data['images'][] = array(
					'imageurl' => HTTP_SERVER.'/image/'.$result['image']//$this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_thumb_height')),
				);
			}
			
			$data['imagespro'] = array();
		 	array_push($data['imagespro'], array("imageurlpro" =>  	$data['thumbpro']) ) ;
			$resultspro = $this->model_catalog_product->getProductImages($this->request->post['product_id']);

			foreach ($resultspro as $result) {
				$data['imagespro'][] = array(
					'imageurlpro' => $this->model_tool_image->resize($result['image'], "500", "500"),
				);
			}
	 

			if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
			if($this->config->get('storeapp_quantity_priceshow') && $product_info['quantity']==0 ){
					$data['price'] = false;
					}else {
	                  $data['price'] = $this->currency->format($this->tax->calculate($product_info['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
			       }
			} else {
				$data['price'] ='';
			}

			if ((float)$product_info['special']) {
				$data['special_price'] = $this->currency->format($this->tax->calculate($product_info['special'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				$data['percentage']= round(100/($product_info['price']/($product_info['price'] - $product_info['special'])), 1, PHP_ROUND_HALF_UP);
			} else {
				$data['special_price'] = -1;
				$data['percentage']= false;
			}

			if ($this->config->get('config_tax')) {
				$data['tax'] = $this->currency->format((float)$product_info['special'] ? $product_info['special'] : $product_info['price'], $this->session->data['currency']);
			} else {
				$data['tax'] = false;
			}

			$discounts = $this->model_catalog_product->getProductDiscounts($this->request->post['product_id']);

			$data['discounts'] = array();

			foreach ($discounts as $discount) {
				$data['discounts'][] = array(
					'quantity' => $discount['quantity'],
					'price'    => $this->currency->format($this->tax->calculate($discount['price'], $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency'])
				);
			}

			$data['options'] = array();

			foreach ($this->model_catalog_product->getProductOptions($this->request->post['product_id']) as $option) {
				$product_option_value_data = array();

				foreach ($option['product_option_value'] as $option_value) {
					if (!$option_value['subtract'] || ($option_value['quantity'] > 0)) {
						if ((($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) && (float)$option_value['price']) {
							$price = $this->currency->format($this->tax->calculate($option_value['price'], $product_info['tax_class_id'], $this->config->get('config_tax') ? 'P' : false), $this->session->data['currency']);
						} else {
							$price = false;
						}

						$product_option_value_data[] = array(
							'product_option_value_id' => $option_value['product_option_value_id'],
							'option_value_id'         => $option_value['option_value_id'],
							'name'                    => $option_value['name'],
							'image'                   => $this->model_tool_image->resize($option_value['image'], 50, 50),
							'price'                   => $price,
							'price_prefix'            => $option_value['price_prefix']
						);
					}
				}

				$data['options'][] = array(
					'product_option_id'    => $option['product_option_id'],
					'product_option_value' => $product_option_value_data,
					'option_id'            => $option['option_id'],
					'name'                 => $option['name'],
					'type'                 => $option['type'],
					'value'                => $option['value'],
					'required'             => $option['required']
				);
			}

			if ($product_info['minimum']) {
				$data['minimum'] = $product_info['minimum'];
			} else {
				$data['minimum'] = 1;
			}

		

		  	$data['review_status'] = $this->config->get('config_review_status');	

		//	$data['reviews'] = sprintf($this->language->get('text_reviews'), (int)$product_info['reviews']);
			$data['rating'] = (int)$product_info['rating'];

           

			$data['relate'] = array();

			$results = $this->model_catalog_product->getProductRelated($this->request->post['product_id']);

			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
				} else {
					$image = $this->model_tool_image->resize('placeholder.png', $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_related_height'));
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
					$special =-1;
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
				$data['relate'][] = array(
					'id'  => $result['product_id'],
					'imageurl'       => $image,
					'name'        => str_replace('&amp;', '&', $result['name']),
					'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($result['product_id']),
					'description' => utf8_substr(trim(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8'))), 0, $this->config->get('theme_' . $this->config->get('config_theme') . '_product_description_length')) . '..',
					'price'       => $price,
					'special_price'     => $special,
					'percentage'     =>	$percentage,
					'tax'         => $tax,
					'minimum'     => $result['minimum'] > 0 ? $result['minimum'] : 1,
					'rating'      => $rating,
					'href'        => $this->url->link('product/product', 'product_id=' . $result['product_id'])
				);
			}


			$this->model_catalog_product->updateViewed($this->request->post['product_id']);
			
			///vendor code ////
			if($this->config->get('tmd_vendor_status')){
            //Vendor Id 
            $vendor_status = $this->config->get('tmd_vendor_status');
			
			$imagewidths = $this->config->get('tmd_vendor_imgwidth');
			$imageheights = $this->config->get('tmd_vendor_imgheight');
			
			if(!empty($imagewidths)){
				$imagewidth = $imagewidths;
			} else {
				$imagewidth = 100;
				
			}

			if(!empty($imageheights)){
				$imageheight = $imageheights;
			} else {
				$imageheight = 100;
				
			}	
			
			$this->load->model('vendor/vendor');
			$this->load->model('tool/image');
			if(isset($this->request->post['product_id'])){
			$sellerproduct_info = $this->model_vendor_vendor->getSellerProduct($this->request->post['product_id']);
			if(isset($sellerproduct_info['vendor_id'])) {
				$seller_info = $this->model_vendor_vendor->getSellerInfo($sellerproduct_info['vendor_id']);
				
			}
			}
			
			if(isset($seller_info['display_name'])){
				$dname = $seller_info['display_name'];
			} else {
				$dname='';
			}
			
			if(isset($seller_info['countryname'])){
				$data['countryname'] = $seller_info['countryname'];
			} else {
				$data['countryname']='';
			}
			
			if(isset($sellerproduct_info['vendor_id'])){
			$seller_vendor_id = $sellerproduct_info['vendor_id'];
			} else {				
			$seller_vendor_id='';	
			}
			$totalcount = $this->model_vendor_vendor->getTotalProducts($seller_vendor_id);
			
			
			if(isset($totalcount)){
				$data['totalproducts'] = $totalcount;
			} else {
				$data['totalproducts']='';
			}
			
			if(isset($seller_info['vendor_id'])){
				$vendor_ids = $seller_info['vendor_id'];
			} else {
				$vendor_ids='';
			}
			
			if(!empty($seller_info['image'])){
				$sellerimage = $this->model_tool_image->resize($seller_info['image'],$imagewidth,$imageheight);
			} else {
				$sellerimage = $this->model_tool_image->resize('placeholder.png',$imagewidth,$imageheight);
			}
			
			$data['sellerimage'] = $sellerimage;
			$data['dname'] = $dname;
			$data['href']  =$this->url->link('vendor/vendor_profile&vendor_id='.$vendor_ids, '', true);
			$data['vendor_ids']   = $vendor_ids;
			$data['followerstotal'] = $this->model_vendor_vendor->getTotalFollowers($vendor_id);
		 }//end vendor
			
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

	public function attribute() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('product/product');

		$this->load->model('catalog/product');

		
      $data['detail'] = $this->model_catalog_product->getProductAttributes($this->request->post['product_id']);

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
	
	public function review() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('product/product');

		$this->load->model('catalog/review');

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

		$data['detail'] = array();

		$review_total = $this->model_catalog_review->getTotalReviewsByProductId($this->request->post['product_id']);

		$results = $this->model_catalog_review->getReviewsByProductId($this->request->post['product_id'], ($page - 1) * 5, 5);
             $data['count']=false;
		foreach ($results as $result) {
			$data['detail'][] = array(
				'author'     => $result['author'],
				'review_id'   => $result['review_id'],
				'text'       => nl2br($result['text']),
				'rating'     => (int)$result['rating'],
				'date_added' => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added']))
				);
				   $data['count']=true;
		}
          $data['review_guest'] =$this->config->get('config_review_guest');
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

	public function write() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('product/product');

		$json = array();

		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
		
             

			if (!isset($json['error'])) {
				$this->load->model('catalog/review');

				$this->model_catalog_review->addReview($this->request->post['product_id'], $this->request->post);

				$json['success'] = $this->language->get('text_success');
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function getRecurringDescription() {
        $this->load->controller('extension/api32/common/language');
		$this->load->language('product/product');
		$this->load->model('catalog/product');

		if (isset($this->request->post['product_id'])) {
			$product_id = $this->request->post['product_id'];
		} else {
			$product_id = 0;
		}

		if (isset($this->request->post['recurring_id'])) {
			$recurring_id = $this->request->post['recurring_id'];
		} else {
			$recurring_id = 0;
		}

		if (isset($this->request->post['quantity'])) {
			$quantity = $this->request->post['quantity'];
		} else {
			$quantity = 1;
		}

		$product_info = $this->model_catalog_product->getProduct($product_id);
		
		$recurring_info = $this->model_catalog_product->getProfile($product_id, $recurring_id);

		$json = array();

		if ($product_info && $recurring_info) {
			if (!$json) {
				$frequencies = array(
					'day'        => $this->language->get('text_day'),
					'week'       => $this->language->get('text_week'),
					'semi_month' => $this->language->get('text_semi_month'),
					'month'      => $this->language->get('text_month'),
					'year'       => $this->language->get('text_year'),
				);

				if ($recurring_info['trial_status'] == 1) {
					$price = $this->currency->format($this->tax->calculate($recurring_info['trial_price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
					$trial_text = sprintf($this->language->get('text_trial_description'), $price, $recurring_info['trial_cycle'], $frequencies[$recurring_info['trial_frequency']], $recurring_info['trial_duration']) . ' ';
				} else {
					$trial_text = '';
				}

				$price = $this->currency->format($this->tax->calculate($recurring_info['price'] * $quantity, $product_info['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);

				if ($recurring_info['duration']) {
					$text = $trial_text . sprintf($this->language->get('text_payment_description'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				} else {
					$text = $trial_text . sprintf($this->language->get('text_payment_cancel'), $price, $recurring_info['cycle'], $frequencies[$recurring_info['frequency']], $recurring_info['duration']);
				}

				$json['success'] = $text;
			}
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}

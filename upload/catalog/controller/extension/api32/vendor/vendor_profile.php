<?php
class ControllerExtensionApi32vendorvendorprofile extends Controller {
	private $error = array();
	public function index() {
         $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
        // add login to check wishlist 
        if(isset($this->request->post['customer_id'])){
			$this->customer->loginWithId($this->request->post['customer_id']);
			$this->config->set('config_customer_group_id', $this->customer->getGroupId());
		}
		  
        //add wishlist
        $this->load->model('account/wishlist');
		$this->load->language('vendor/vendor_profile');
            
	

		/* 03-10-2019 */
		$this->load->model('vendor/vendor');

			$vendorproduct_id = $this->model_vendor_vendor->getSellerChat($this->request->post['vendor_id']);

			if(!empty($vendorproduct_id['vendor_id'])){
			$vendor_ids = $vendorproduct_id['vendor_id'];
			} else {
			$vendor_ids = '';
			}

			$vendorchat_ids = $this->model_vendor_vendor->getChatid($vendor_ids);

			if(!empty($vendorchat_ids['message'])){
			$data['vendorchat_id'] = $vendorchat_ids['message'];
			} else {
			$data['vendorchat_id'] = '';
			}
	
       

		$this->load->model('vendor/vendor');
		$this->load->model('tool/image');



		/* 01-02-2019 */
		if (isset($this->request->post['vendor_id'])) {
			$vendor_id = (int)$this->request->post['vendor_id'];
		} else {
			$vendor_id = 0;
		}
		/* 01-02-2019 */
		if(isset($vendor_id)) {
			 $vendor_info = $this->model_vendor_vendor->getVendor($vendor_id);
		}

		/* 01-02-2019 */
		if ($vendor_info) {
		/* 01-02-2019 */

		if(isset($vendor_info['display_name'])){
			$this->document->setTitle($vendor_info['display_name']);
		}
		/* new code */

		if(!empty($vendor_info['facebook_url'])){
			$facebookurl = $vendor_info['facebook_url'];
		} else {
			$facebookurl = '';
		}

		if(!empty($vendor_info['google_url'])){
			$googleurl = $vendor_info['google_url'];
		} else {
			$googleurl = '';
		}
		$data['facebookurl'] = $facebookurl;
		$data['googleurl'] = $googleurl;
		$data['vendorfindme'] =  $this->url->link('vendor/findme','&vendor_id=' .$vendor_info['vendor_id']);

		//25-3-2019 start
	
		$data['communication_status'] = $this->config->get('tmdcommunication_status');
		$data['updfiletype'] = $this->config->get('tmdcommunication_imagetype');
	
		if(!empty($vendor_info['store_logowidth'])){
			$store_logowidth = $vendor_info['store_logowidth'];
		} else {
			$store_logowidth = 75;
		}

		if(!empty($vendor_info['store_logoheight'])){
			$store_logoheight = $vendor_info['store_logoheight'];
		} else {
			$store_logoheight = 75;
		}

		if(!empty($vendor_info['store_bannerwidth'])){
			$store_bannerwidth = $vendor_info['store_bannerwidth'];
		} else {
			$store_bannerwidth = 1200;
		}

		if(!empty($vendor_info['store_bannerheight'])){
			$store_bannerheight = $vendor_info['store_bannerheight'];
		} else {
			$store_bannerheight = 400;
		}

		if(!empty($vendor_info['image'])){
			$images = $this->model_tool_image->resize($vendor_info['image'],150,150);
		} else {
			$images = $this->model_tool_image->resize('placeholder.png',150,150);
		}

		if(!empty($vendor_info['banner'])){
			$banners = $this->model_tool_image->resize($vendor_info['banner'],$store_bannerwidth,$store_bannerheight);
		} else {
			$banners = $this->model_tool_image->resize('placeholder.png',$store_bannerwidth,$store_bannerheight);
		}

		if(!empty($vendor_info['logo'])){
			$logos = $this->model_tool_image->resize($vendor_info['logo'],$store_logowidth, $store_logoheight);
		} else {
			$logos = $this->model_tool_image->resize('placeholder.png',$store_logowidth,$store_logoheight);
		}

		if(!empty($vendor_info['store_about'])){
			$store_about = $vendor_info['store_about'];
		} else {
			$store_about = '';
		}

        /* update code 29-1-2019 */
		/* update code 04-6-2019 */

	   /* update code 12-6-2019 */

			$storedescription = strip_tags(trim(html_entity_decode($vendor_info['description'], ENT_QUOTES, 'UTF-8')));
			if(!empty($storedescription)) {
			$data['storedescription'] = html_entity_decode($vendor_info['description'], ENT_QUOTES, 'UTF-8');
			} else {
			$data['storedescription'] = '';
			}

			$shipping_policy = strip_tags(trim(html_entity_decode($vendor_info['shipping_policy'], ENT_QUOTES, 'UTF-8')));
			if(!empty($shipping_policy)){
				$data['shipping_policy'] =  html_entity_decode($vendor_info['shipping_policy'], ENT_QUOTES, 'UTF-8');
			} else {
				$data['shipping_policy']  = '';
			}


			$return_policy = strip_tags(trim(html_entity_decode($vendor_info['return_policy'], ENT_QUOTES, 'UTF-8')));

			if(!empty($return_policy)){
				$data['return_policy']  =  html_entity_decode($vendor_info['return_policy'], ENT_QUOTES, 'UTF-8');
			} else {
				$data['return_policy']  = '';
			}

		    /* update code 12-6-2019 */

		    /* update code 29-1-2019 */

			if(!empty($vendor_info['display_name'])){
				$display_name = $vendor_info['display_name'];
			} else {
				$display_name = '';
			}
			if(!empty($vendor_info['vendor_id'])){
				$vendor_id = $vendor_info['vendor_id'];
			} else {
				$vendor_id = '';
			}
     	//print_r($vendor_id);

		if(!empty($vendor_info['telephone'])){
			$vendortelephone = $vendor_info['telephone'];
		} else {
			$vendortelephone = '';
		}
		if(!empty($vendor_info['name'])){
			$vendorname = $vendor_info['name'];
		} else {
			$vendorname = '';
		}
		if(!empty($vendor_info['map_url'])){
			$map_url = $vendor_info['map_url'];
		} else {
			$map_url = '';
		}

		if(!empty($vendor_info['about'])){
			$aboutvendor = $vendor_info['about'];
		} else {
			$aboutvendor = '';
		}

		if(!empty($vendor_info['text'])) {
			$ratingtext = $vendor_info['text'];
		} else {
			$ratingtext='';
		}

		if(!empty($vendor_info['email'])){
			$vendoremail = $vendor_info['email'];
		} else {
			$vendoremail = '';
		}

		$data['banners'] 		= $banners;
		$data['logos'] 		    = $logos;
		$data['store_about'] 	= $store_about;
		$data['name'] 			= $vendorname;
		$data['map_url'] 		= $map_url;

		$data['images'] 		= $images;
		$data['display_name'] 	= $display_name;
		$data['vendor_id'] 	    = $vendor_id;
		$data['catevendor_id'] 	= $vendor_id;
		$data['email'] 			= $vendoremail;
		$data['telephone'] 		= $vendortelephone;
		$data['about'] 			= $aboutvendor;
		$data['ratingtext'] 	= $ratingtext;


// Get Vendor Id More than One Time Insert Start //
		$data['customerloggin'] = $this->customer->isLogged();
		$write_infos = $this->model_vendor_vendor->getWriteReview($this->request->post['vendor_id']);

		if(isset($vendor_info['vendor_id'])){
			$vids = $vendor_info['vendor_id'];
		} else {
			$vids='';
		}

		if(isset($write_infos['customer_id'])){
			$ids = $write_infos['customer_id'];
		} else {
			$ids='';
		}

		if(isset($write_infos['vendor_id'])){
			$vendorids = $write_infos['vendor_id'];
		} else {
			$vendorids='';
		}
		$data['ids'] = $ids;
		$data['vids'] = $vids;
		$data['vendorids'] = $vendorids;

		$data['totals'] = $this->model_vendor_vendor->getTotalCollections($vendor_id);
		$data['sellertotal'] = $this->model_vendor_vendor->getTotalSellerReview($vendor_id);
		$data['producttotal_review'] = $this->model_vendor_vendor->getTotalProductReview($vendor_id);

		/* 25-04-2019 */
		$data['vendorcontact'] = $this->config->get('vendor_hidevendorcontact');
		/* 25-04-2019 */
/// Follow And Following ////
		$data['loggin'] = $this->vendor->isLogged();
		$data['custloggin'] = $this->customer->isLogged();

		if(isset($this->request->post['vendor_id'])) {
			$data['requets']=$this->model_vendor_vendor->getFollow($this->request->post['vendor_id']);
		} else if(isset($this->request->post['vendor_id'])) {
			$data['requets']=$this->model_vendor_vendor->getDelete($this->request->post['vendor_id']);
		}else {
			$data['requets']='';
		}

		$data['followerstotal'] = $this->model_vendor_vendor->getTotalFollowers($vendor_id);


	/* add new code 03 10 2019 replace */
	$data['reviewvalue'] = $this->model_vendor_vendor->getVendorSumValue($vendor_id);
	/* add new code 03 10 2019 replace */

		$data['field_infos']=array();


		$field_infos = $this->model_vendor_vendor->getFieldReviews($vendor_id);

			foreach($field_infos as $field_info){

				$ven_info = $this->model_vendor_vendor->getVendor($field_info['vendor_id']);
				if(isset($ven_info['display_name'])) {
					$fnames = $ven_info['display_name'];
				} else {
					$fnames='';
				}

				$cus_info = $this->model_vendor_vendor->getCustomer($field_info['customer_id']);
				if(isset($cus_info['firstname'])) {
					$cnames = $cus_info['firstname']. ' ' .$cus_info['lastname'];
				} else {
					$cnames='';
				}


				if(isset($ven_info['about'])) {
					$abouts = $ven_info['about'];
				} else {
					$abouts='';
				}

				$ratings=array();
				$rating_infos = $this->model_vendor_vendor->getField($field_info['review_id'],$field_info['vendor_id']);

				foreach($rating_infos as $rating_info){
					$ratings[]=array(
						'field_name'=> $rating_info['field_name'],
						'value' 	=> $rating_info['value']

					);
				}

				$data['field_infos'][]=array(
					'review_id' => $field_info['review_id'],
					'reviewtext' => $field_info['text'],
					//'fnames' 	=> $fnames,
					'cnames' 	=> $cnames,
					'abouts' 	=> $abouts,
					'ratings' 	=> $ratings,
					'date_added' => $field_info['date_added']
				);

			}
// Seller Review End //



// Product With Seller Review Start //

		$data['sellerreviews']=array();
		if(!empty($vendor_info)){
			$vendor_info= $vendor_info;
		} else{
			$vendor_info='';
		}
		$proreviews = $this->model_vendor_vendor->getProReview($vendor_id);
		foreach($proreviews as $proreview){
			$vendorinfo = $this->model_vendor_vendor->getVendor($proreview['vendor_id']);


			if(isset($vendorinfo['date_added'])) {
				$date_added = $vendorinfo['date_added'];
			} else {
				$date_added='';
			}
			$products = $this->model_vendor_vendor->getProduct($proreview['product_id']);

			if(isset($products['name'])) {
				$names = $products['name'];
			} else {
				$names='';
			}
			if(isset($products['product_id'])) {
				$product_ids = $products['product_id'];
			} else {
				$product_ids='';
			}
			$data['sellerreviews'][]=array(
				'rating' 		=> $proreview['rating'],
				'text' 			=> $proreview['text'],
				'author' 		=> $proreview['author'],
				'names' 		=> $names,
				'date_added' 	=> $date_added,
				'href'  => $this->url->link('product/product','&product_id=' .$product_ids)
			);
		}

// Product With Seller Review End //

		$this->load->model('vendor/vendor');
		$data['review_fields'] = $this->model_vendor_vendor->getReviewFields($data);

		if (isset($this->request->post['reviewfield'])) {
			$data['reviewfield'] = $this->request->post['reviewfield'];
		} elseif (isset($review_info['reviewfield'])) {
			$data['reviewfield'] = $this->model_vendor_vendor->getFieldSubmits($this->request->post['review_id']);
		} else {
			$data['reviewfield'] = array();
		}

/// Collection Product Start ///

		if (isset($this->request->post['filter'])) {
			$filter = $this->request->post['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = '';
		}

		if (isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

		if (isset($this->request->post['limit'])) {
			$limit = (int)$this->request->post['limit'];
		} else {
			$limit = $this->config->get($this->config->get('config_theme') . '_product_limit');
		}

		if (isset($this->request->post['path'])) {

		$url = '';

		if (isset($this->request->post['filter'])) {
			$url .= '&filter=' . $this->request->post['filter'];
		}

		if (isset($this->request->post['sort'])) {
			$url .= '&sort=' . $this->request->post['sort'];
		}

		if (isset($this->request->post['order'])) {
			$url .= '&order=' . $this->request->post['order'];
		}

		if (isset($this->request->post['limit'])) {
			$url .= '&limit=' . $this->request->post['limit'];
		}


		$path = '';

			$parts = explode('_', (string)$this->request->post['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

			
			}
		} else {
			$category_id = 0;
		}



		$data['products']= array();
		$filter2=array(
			/* new code */
			'filter_category_id' => $category_id,
			/* new code */
			'vendor_id'     => $vendor_id,
			'filter_filter' => $filter,
			'sort'          => $sort,
			'order'         => $order,
			'start'         => ($page - 1) * $limit,
			'limit'         => $limit
		);

		$this->load->model('vendor/store');

		$product_total = $this->model_vendor_store->getTotalProducts($filter2);
		$products = $this->model_vendor_store->getProducts($filter2);

		foreach($products as $product) {
			if (is_file(DIR_IMAGE . $product['image'])){
				$pimage = $this->model_tool_image->resize($product['image'],   $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}else{
				$pimage = $this->model_tool_image->resize('no_image.png',  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}

			$pros_info = $this->model_vendor_vendor->getProRev($product['product_id']);

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product['special']) {
					$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = -1;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

			if(isset($pros_info['rating'])) {
				$ratings = $pros_info['rating'];
			} else {
				$ratings='';
			}
            
            // add wishlist 
            $data['wishlist']	 =$this->model_account_wishlist->getWishlistByproductId($product['product_id']);

			$data['products'][] = array(
			     //change product_id to id
			     //change special to special_price
			     //change pimage to imageurl
			     //add wishlist item
				'id' 	=> $product['product_id'],
				'name'   		=> $product['name'],
				'description'	=>utf8_substr(trim(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'))), 0,120),
				'price'       => $price,
				'special_price'     => $special,
				'tax'         => $tax,
				'imageurl' 		=> $pimage,
				'ratings' 		=> $ratings,
				'minimum'       => $product['minimum'] > 0 ? $product['minimum'] : 1,
				'href'   		=> $this->url->link('product/product','&product_id=' .$product['product_id']),
				'wishlist' 	 =>$this->model_account_wishlist->getWishlistByproductId($product['product_id']),
			);
		}
		$url ='';
		

	


		/// Collection Product End ///

		/// Product Category Start ///
			if (isset($parts[0])) {
			$data['category_id'] = $parts[0];
			} else {
				$data['category_id'] = 0;
			}

			if (isset($parts[1])) {
			$data['child_id'] = $parts[1];
			} else {
				$data['child_id'] = 0;
			}

		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$data['categories']=array();
		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			/* sub cate */

			$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach($children as $child) {
					
					$filter_productdata = array(
						'filter_category_id' => $child['category_id'],
						'vendor_id'     => $vendor_id,
						'filter_sub_category' => true
					);

						/* 29 01 2020 sub3 */
						$children_data1 = array();
						$children1 = $this->model_catalog_category->getCategories($child['category_id']);

						foreach($children1 as $child1) {
							$filter_productdata1 = array(
								'filter_category_id' => $child1['category_id'],
								'vendor_id'=> $vendor_id,
								'filter_sub_category' => true
							);
							
							$totalproducts = $this->model_vendor_store->getTotalProducts($filter_productdata1);
							  
							if($totalproducts!=0){
								$children_data1[] = array(
									'category_id' => $child1['category_id'],
									'name' => $child1['name'] . (' (' . $this->model_vendor_store->getTotalProducts($filter_productdata1) . ')')

								);
							}
						}
						/* 29 01 2020 sub3 */
					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'] . (' (' . $this->model_vendor_store->getTotalProducts($filter_productdata) . ')'),
						/* 29 01 2020 sub3 */
						'children1'    => $children_data1,
						/* 29 01 2020 sub3 */

					);
				}

			/* sub cate */

			$category_infos = $this->model_catalog_category->getCategory($category['category_id']);

			if(isset($category_infos['name'])){
				$categoryname = $category_infos['name'];
			} else {
				$categoryname='';
			}

			$filter_productdata = array(
				'filter_category_id'  => $category['category_id'],
				'vendor_id'     => $vendor_id,
				'filter_sub_category' => true
			);

			$vcategorytotal = $this->model_vendor_store->getTotalProducts($filter_productdata);

			if($vcategorytotal > 0){
				$data['categories'][]=array(
					'category_id' => $category['category_id'],
					'categoryname' => $categoryname . (' (' . $vcategorytotal . ')'),
					'children'    => $children_data,

				);

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
		/* 01-02-2019 */

	}

	function review(){
		$json = array();
		$this->load->model('vendor/vendor');
		$this->load->language('vendor/vendor_profile');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(empty($this->request->post['text'])){
				$json['error']='Please add Comment here';
			} else {

			 $this->model_vendor_vendor->addReview($this->request->post,$this->request->post['vendor_id']);

			$json['success'] = $this->language->get('text_success');
			}
		}
		$this->index();
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

	public function loadcategory() {

        $this->load->language('vendor/vendor_profile');

		$this->load->model('vendor/vendor');
		$this->load->model('tool/image');

		if (isset($this->request->post['filter'])) {
			$filter = $this->request->post['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = '';
		}

		if (isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

		/* 01-07-2019 update with 9 */

		if (isset($this->request->post['limit'])) {
			$limit = (int)$this->request->post['limit'];
		} else {
			$limit = 9;
		}

		if(isset($this->request->post['vendor_id'])) {
			 $vendor_info = $this->model_vendor_vendor->getVendor($this->request->post['vendor_id']);

		}

		if(!empty($vendor_info['vendor_id'])){
			$vendor_id = $vendor_info['vendor_id'];
		} else {
			$vendor_id = '';
		}

		 $data['vendor_id'] 	    = $vendor_id;


		/* 01-07-2019 */
		if (isset($this->request->post['category_id'])) {
		/* 01-07-2019 */
		$url = '';

		if (isset($this->request->post['filter'])) {
			$url .= '&filter=' . $this->request->post['filter'];
		}

		if (isset($this->request->post['sort'])) {
			$url .= '&sort=' . $this->request->post['sort'];
		}

		if (isset($this->request->post['order'])) {
			$url .= '&order=' . $this->request->post['order'];
		}

		if (isset($this->request->post['limit'])) {
			$url .= '&limit=' . $this->request->post['limit'];
		}

		$path = '';

			$parts = explode('_', (string)$this->request->post['category_id']);

			 $category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$data['products']= array();

		$filter2=array(
			'filter_category_id' => $category_id,
			'vendor_id'     => $vendor_id,
			'filter_filter' => $filter,
			'sort'          => $sort,
			'order'         => $order,
			'start'         => ($page - 1) * $limit,
			'limit'         => $limit
		);

		$this->load->model('vendor/store');

		$product_total = $this->model_vendor_store->getTotalProducts($filter2);
		$products = $this->model_vendor_store->getProducts($filter2);

		foreach($products as $product) {
			if (is_file(DIR_IMAGE . $product['image'])){
				$pimage = $this->model_tool_image->resize($product['image'],   $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}else{
				$pimage = $this->model_tool_image->resize('no_image.png',  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}

			$pros_info = $this->model_vendor_vendor->getProRev($product['product_id']);

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product['special']) {
					$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

			if(isset($pros_info['rating'])) {
				$ratings = $pros_info['rating'];
			} else {
				$ratings='';
			}

			$data['products'][] = array(
				'product_id' 	=> $product['product_id'],
				'name'   		=> $product['name'],
				'description'	=>utf8_substr(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8')), 0, 120),
				'price'       => $price,
				'special'     => $special,
				'tax'         => $tax,
				'pimage' 		=> $pimage,
				'ratings' 		=> $ratings,
				'minimum'       => $product['minimum'] > 0 ? $product['minimum'] : 1,
				'href'   		=> $this->url->link('product/product','&product_id=' .$product['product_id'])
			);
		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = $limit;
		/* 01-07-2019 update with  */
		$pagination->url = $this->url->link('vendor/vendor_profile/loadcategory','vendor_id=' . $vendor_id .'&category_id=' . $category_id .'&page={page}');
		/* 01-07-2019 update with  */
		$data['pagination'] = $pagination->render();

		$data['button_cart'] = $this->language->get('button_cart');
		$data['button_wishlist'] = $this->language->get('button_wishlist');
		$data['button_compare'] = $this->language->get('button_compare');
		/* 01-07-2019 update with 9 */
		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * 9) + 1 : 0, ((($page - 1) * 9) > ($product_total - 9)) ? $product_total : ((($page - 1) * 9) + 9), $product_total, ceil($product_total / 9));
		/* 01-07-2019 update with 9 */
		$data['sort'] = $sort;
		$data['order'] = $order;
		$data['limit'] = $limit;


		$this->response->setOutput($this->load->view('vendor/loadcategory', $data));

	}

	function follow(){
		$json = array();
		$this->load->model('vendor/vendor');
		$this->load->language('vendor/vendor_profile');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->model_vendor_vendor->addFollow($this->request->post['vendor_id'],$this->request->post);
			$json['success'] = $this->language->get('text_success');

		}
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

	function delfollow(){
		$json = array();
		$this->load->model('vendor/vendor');
		$this->load->language('vendor/vendor_profile');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {

			$this->model_vendor_vendor->getDelete($this->request->post['vendor_id']);
			$json['success'] = $this->language->get('text_success');

		}
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
	//25-3-2019 start

	function sendmessage(){
		$json = array();
		$this->load->model('vendor/vendor');
		$this->load->language('vendor/vendor_profile');
		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if(empty($this->request->post['subject'])) {
				$json['error']= $this->language->get('error_subject');
			}
			else {
			$this->model_vendor_vendor->Addmessage($this->request->post,$this->request->post['vendor_id']);
				$json['success'] = $this->language->get('success_msg');
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
		$this->response->setOutput(json_encode($json));
	}

	//25-3-2019 end

	/* 01-07-2019 start */
	public function vendorproduct() {
		$this->load->language('vendor/vendor_profile');
		$this->load->model('vendor/vendor');
		$this->load->model('tool/image');

		if (isset($this->request->post['filter'])) {
			$filter = $this->request->post['filter'];
		} else {
			$filter = '';
		}

		if (isset($this->request->post['sort'])) {
			$sort = $this->request->post['sort'];
		} else {
			$sort = '';
		}

		if (isset($this->request->post['order'])) {
			$order = $this->request->post['order'];
		} else {
			$order = 'ASC';
		}

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}


		if (isset($this->request->post['limit'])) {
			$limit = (int)$this->request->post['limit'];
		} else {
			$limit = 9;
		}

		if(isset($this->request->post['vendor_id'])) {
			 $vendor_info = $this->model_vendor_vendor->getVendor($this->request->post['vendor_id']);

		}

		if(!empty($vendor_info['vendor_id'])){
			$vendor_id = $vendor_info['vendor_id'];
		} else {
			$vendor_id = '';
		}

		 $data['vendor_id'] 	    = $vendor_id;

		if (isset($this->request->post['path'])) {

		$url = '';

		if (isset($this->request->post['filter'])) {
			$url .= '&filter=' . $this->request->post['filter'];
		}

		if (isset($this->request->post['sort'])) {
			$url .= '&sort=' . $this->request->post['sort'];
		}

		if (isset($this->request->post['order'])) {
			$url .= '&order=' . $this->request->post['order'];
		}

		if (isset($this->request->post['limit'])) {
			$url .= '&limit=' . $this->request->post['limit'];
		}

		$path = '';

			$parts = explode('_', (string)$this->request->post['path']);

			$category_id = (int)array_pop($parts);

			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}

				$category_info = $this->model_catalog_category->getCategory($path_id);

				if ($category_info) {
					$data['breadcrumbs'][] = array(
						'text' => $category_info['name'],
						'href' => $this->url->link('product/category', 'path=' . $path . $url)
					);
				}
			}
		} else {
			$category_id = 0;
		}

		$data['products']= array();
		 $filter2=array(
			'filter_category_id' => $category_id,
			'vendor_id'     => $vendor_id,
			'filter_filter' => $filter,
			'sort'          => $sort,
			'order'         => $order,
			'start'         => ($page - 1) * $limit,
			'limit'         => $limit
		);

		$this->load->model('vendor/store');

		$product_total = $this->model_vendor_store->getTotalProducts($filter2);
		$products = $this->model_vendor_store->getProducts($filter2);

		foreach($products as $product) {
			if (is_file(DIR_IMAGE . $product['image'])){
				$pimage = $this->model_tool_image->resize($product['image'],   $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}else{
				$pimage = $this->model_tool_image->resize('no_image.png',  $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_width'), $this->config->get('theme_' . $this->config->get('config_theme') . '_image_product_height'));
			}

			$pros_info = $this->model_vendor_vendor->getProRev($product['product_id']);

            if ($this->customer->isLogged() || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$price = false;
				}

				if ((float)$product['special']) {
					$special = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id'], $this->config->get('config_tax')), $this->session->data['currency']);
				} else {
					$special = false;
				}

				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$product['special'] ? $product['special'] : $product['price'], $this->session->data['currency']);
				} else {
					$tax = false;
				}

			if(isset($pros_info['rating'])) {
				$ratings = $pros_info['rating'];
			} else {
				$ratings='';
			}

			$data['products'][] = array(
				'product_id' 	=> $product['product_id'],
				'name'   		=> $product['name'],
				'description'	=>utf8_substr(trim(strip_tags(html_entity_decode($product['description'], ENT_QUOTES, 'UTF-8'))), 0,120),
				'price'       => $price,
				'special'     => $special,
				'extra_buttons'  => defined('JOURNAL3_ACTIVE') ? $this->journal3->productExtraButton($product, $price, $special) : null,
				'tax'         => $tax,
				'pimage' 		=> $pimage,
				'ratings' 		=> $ratings,
				'minimum'       => $product['minimum'] > 0 ? $product['minimum'] : 1,
				'href'   		=> $this->url->link('product/product','&product_id=' .$product['product_id'])
			);
		}



		$this->load->model('catalog/category');
		$this->load->model('catalog/product');

		$data['categories']=array();
		$categories = $this->model_catalog_category->getCategories(0);

		foreach ($categories as $category) {
			$children_data = array();

				$children = $this->model_catalog_category->getCategories($category['category_id']);

				foreach($children as $child) {
					$filter_productdata = array('filter_category_id' => $child['category_id'],'vendor_id'     => $vendor_id,  'filter_sub_category' => true);

					$children_data[] = array(
						'category_id' => $child['category_id'],
						'name' => $child['name'] . (' (' . $this->model_vendor_store->getTotalProducts($filter_productdata) . ')'),

					);
				}


			$category_infos = $this->model_catalog_category->getCategory($category['category_id']);

			if(isset($category_infos['name'])){
				$categoryname = $category_infos['name'];
			} else {
				$categoryname='';
			}

			$filter_productdata = array(
				'filter_category_id'  => $category['category_id'],
				'vendor_id'     => $vendor_id,
				'filter_sub_category' => true
			);

			$vcategorytotal = $this->model_vendor_store->getTotalProducts($filter_productdata);

			if($vcategorytotal > 0){
				$data['categories'][]=array(
					'category_id' => $category['category_id'],
					'categoryname' => $categoryname . (' (' . $vcategorytotal . ')'),
					'children'    => $children_data,

				);

			}

		}

		$pagination = new Pagination();
		$pagination->total = $product_total;
		$pagination->page = $page;
		$pagination->limit = 9;
		$pagination->url = $this->url->link('vendor/vendor_profile/vendorproduct', 'vendor_id=' . $this->request->post['vendor_id'] . '&page={page}');

		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($product_total) ? (($page - 1) * 9) + 1 : 0, ((($page - 1) * 9) > ($product_total - 9)) ? $product_total : ((($page - 1) * 9) + 9), $product_total, ceil($product_total / 9));

		$this->response->setOutput($this->load->view('vendor/vendorproduct', $data));
	}

	/* 01-07-2019 end */

}

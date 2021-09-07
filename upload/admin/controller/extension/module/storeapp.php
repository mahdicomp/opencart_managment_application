<?php
class ControllerExtensionModuleStoreapp extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('extension/module/storeapp');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');
          $this->load->model('tool/image');
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('storeapp', $this->request->post);
			$this->model_setting_setting->editSetting('module_storeapp', $this->request->post);
			
			$this->session->data['success'] = $this->language->get('text_success');

		}
		
		$data['heading_title']  = $this->language->get('heading_title');
		$data['text_edit']  = $this->language->get('heading_title');
		
         $data['user_token'] = $this->session->data['user_token'];
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['action'] = $this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], true);

		$data['cancel'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);


		if (isset($this->request->post['storeapp_name'])) {
			$data['storeapp_name'] = $this->request->post['storeapp_name'];
		} else {
			$data['storeapp_name'] = $this->config->get('storeapp_name');
		}

		if (isset($this->request->post['storeapp_icon'])) {
			$data['storeapp_icon'] = $this->request->post['storeapp_icon'];
		} else {
			$data['storeapp_icon'] = $this->config->get('storeapp_icon');
		}

		
		 if (isset($this->request->post['storeapp_token'])) {
			$data['storeapp_token'] = $this->request->post['storeapp_token'];
		} else {
			$data['storeapp_token'] = $this->config->get('storeapp_token');
		}

		 if (isset($this->request->post['storeapp_serverKey'])) {
			$data['storeapp_serverKey'] = $this->request->post['storeapp_serverKey'];
		} else {
			$data['storeapp_serverKey'] = $this->config->get('storeapp_serverKey');
		}
		
               ///////////////start tab extra ////
               
		if (isset($this->request->post['storeapp_multivendor'])) {
			$data['storeapp_multivendor'] = $this->request->post['storeapp_multivendor'];
		} else {
			$data['storeapp_multivendor'] = $this->config->get('storeapp_multivendor');
		}
		
		if (isset($this->request->post['storeapp_multivendor_name'])) {
			$data['storeapp_multivendor_name'] = $this->request->post['storeapp_multivendor_name'];
		} else {
			$data['storeapp_multivendor_name'] = $this->config->get('storeapp_multivendor_name');
		}
		
		
		
		if (isset($this->request->post['storeapp_ewallet'])) {
			$data['storeapp_ewallet'] = $this->request->post['storeapp_ewallet'];
		} else {
			$data['storeapp_ewallet'] = $this->config->get('storeapp_ewallet');
		}
			if (isset($this->request->post['storeapp_ewallet_name'])) {
			$data['storeapp_ewallet_name'] = $this->request->post['storeapp_ewallet_name'];
		} else {
			$data['storeapp_ewallet_name'] = $this->config->get('storeapp_ewallet_name');
		}
		
		
		if (isset($this->request->post['storeapp_delivery_time'])) {
			$data['storeapp_delivery_time'] = $this->request->post['storeapp_delivery_time'];
		} else {
			$data['storeapp_delivery_time'] = $this->config->get('storeapp_delivery_time');
		}
		
		
		
		if (isset($this->request->post['storeapp_ticket'])) {
			$data['storeapp_ticket'] = $this->request->post['storeapp_ticket'];
		} else {
			$data['storeapp_ticket'] = $this->config->get('storeapp_ticket');
		}
		
		
		if (file_exists(DIR_APPLICATION . 'controller/extension/module/pincodedays.php')){
		   $data['delivery_time_purchase']= true;
		}
		
		if (file_exists(DIR_APPLICATION . 'controller/extension/module/e_wallet.php')){
		   $data['e_wallet_purchase']= true;
		}
		
		if (file_exists(DIR_APPLICATION . 'controller/extension/module/simple_support.php')){
		   $data['simple_support_purchase']= true;
		}
		
		if (file_exists(DIR_APPLICATION . 'controller/extension/module/tmd_vendor.php')){
		   $data['tmd_vendor_purchase']= true;
		}
		
			if (file_exists(DIR_APPLICATION . 'controller/extension/module/sms_verify.php')){
		   $data['sms_verify_purchase']= true;
		}
		
		/////////////end tab extra////////////////////////
        
		if (isset($this->request->post['storeapp_reward'])) {
			$data['storeapp_reward'] = $this->request->post['storeapp_reward'];
		} else {
			$data['storeapp_reward'] = $this->config->get('storeapp_reward');
		}

		
		if (isset($this->request->post['storeapp_smsverify'])) {
			$data['storeapp_smsverify'] = $this->request->post['storeapp_smsverify'];
		} else {
			$data['storeapp_smsverify'] = $this->config->get('storeapp_smsverify');
		}
		
		
		if (isset($this->request->post['storeapp_information_help'])) {
			$data['storeapp_information_help'] = $this->request->post['storeapp_information_help'];
		} else {
			$data['storeapp_information_help'] = $this->config->get('storeapp_information_help');
		}


		
		if (isset($this->request->post['module_storeapp_status'])) {
			$data['module_storeapp_status'] = $this->request->post['module_storeapp_status'];
		} else {
			$data['module_storeapp_status'] = $this->config->get('module_storeapp_status');
		}
		
		
		//////////////////////////start home page layout ////////////////////
		$this->load->model('extension/new/storeapp');
          $layouts = $this->model_extension_new_storeapp->getHomePageLayouts();
        if(!empty($layouts)) {
            foreach($layouts as $layout){
                $data['layouts'][] = array(
                    'layout_id' =>  $layout['id_layout'],
                    'layout_sort' =>  $layout['layout_sort'],
                    'layout_name' => $layout['layout_name'],
                    'layout_url' => "",
                );
            }
        }
        if (isset($this->request->post['webservice']['homepage_layout'])) {
            $data['homepage_layout'] = $this->request->post['webservice']['homepage_layout'];
        } else if (!empty($settings['webservice']['homepage_layout'])) {
            $data['homepage_layout'] = $settings['webservice']['homepage_layout'];
        } else {
            $data['homepage_layout'] = 1;
        }
		
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////     start code contact us	    	//////////////     
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if (isset($this->request->post['storeapp_contactus_namestore'])) {
			$data['storeapp_contactus_namestore'] = $this->request->post['storeapp_contactus_namestore'];
		} else {
			$data['storeapp_contactus_namestore'] = $this->config->get('storeapp_contactus_namestore');
		}
		
		
		if (isset($this->request->post['storeapp_contactus_icon'])) {
			$data['storeapp_contactus_icon'] = $this->request->post['storeapp_contactus_icon'];
		} else {
			$data['storeapp_contactus_icon'] = $this->config->get('storeapp_contactus_icon');
		}
		
		if (isset($this->request->post['storeapp_contactus_privacy_link'])) {
			$data['storeapp_contactus_privacy_link'] = $this->request->post['storeapp_contactus_privacy_link'];
		} else {
			$data['storeapp_contactus_privacy_link'] = $this->config->get('storeapp_contactus_privacy_link');
		}
		
		if (isset($this->request->post['storeapp_contactus_complaints_link'])) {
			$data['storeapp_contactus_complaints_link'] = $this->request->post['storeapp_contactus_complaints_link'];
		} else {
			$data['storeapp_contactus_complaints_link'] = $this->config->get('storeapp_contactus_complaints_link');
		}
		
		if (isset($this->request->post['storeapp_contactus_description'])) {
			$data['storeapp_contactus_description'] = $this->request->post['storeapp_contactus_description'];
		} else {
			$data['storeapp_contactus_description'] = $this->config->get('storeapp_contactus_description');
		}
		
		
		if (isset($this->request->post['storeapp_contactus_callnumber'])) {
			$data['storeapp_contactus_callnumber'] = $this->request->post['storeapp_contactus_callnumber'];
		} else {
			$data['storeapp_contactus_callnumber'] = $this->config->get('storeapp_contactus_callnumber');
		}
		
		if (isset($this->request->post['storeapp_contactus_call_title'])) {
			$data['storeapp_contactus_call_title'] = $this->request->post['storeapp_contactus_call_title'];
		} else {
			$data['storeapp_contactus_call_title'] = $this->config->get('storeapp_contactus_call_title');
		}
		
		if (isset($this->request->post['storeapp_contactus_callnumber'])) {
			$data['storeapp_contactus_callnumber'] = $this->request->post['storeapp_contactus_callnumber'];
		} else {
			$data['storeapp_contactus_callnumber'] = $this->config->get('storeapp_contactus_callnumber');
		}
		
			if (isset($this->request->post['storeapp_contactus_call_icon'])) {
			$data['storeapp_contactus_call_icon'] = $this->request->post['storeapp_contactus_call_icon'];
		} else {
			$data['storeapp_contactus_call_icon'] = $this->config->get('storeapp_contactus_call_icon');
		}
		//print_r($data['storeapp_contactus_call_icon']);
			
		if (isset($this->request->post['storeapp_contactus_email_title'])) {
			$data['storeapp_contactus_email_title'] = $this->request->post['storeapp_contactus_email_title'];
		} else {
			$data['storeapp_contactus_email_title'] = $this->config->get('storeapp_contactus_email_title');
		}
		
			
		if (isset($this->request->post['storeapp_contactus_email_description'])) {
			$data['storeapp_contactus_email_description'] = $this->request->post['storeapp_contactus_email_description'];
		} else {
			$data['storeapp_contactus_email_description'] = $this->config->get('storeapp_contactus_email_description');
		}
		
		
		
		if (isset($this->request->post['storeapp_contactus_email'])) {
			$data['storeapp_contactus_email'] = $this->request->post['storeapp_contactus_email'];
		} else {
			$data['storeapp_contactus_email'] = $this->config->get('storeapp_contactus_email');
		}
		
		if (isset($this->request->post['storeapp_contactus_subject_email'])) {
			$data['storeapp_contactus_subject_email'] = $this->request->post['storeapp_contactus_subject_email'];
		} else {
			$data['storeapp_contactus_subject_email'] = $this->config->get('storeapp_contactus_subject_email');
		}
		
		if (isset($this->request->post['storeapp_contactus_message_email'])) {
			$data['storeapp_contactus_message_email'] = $this->request->post['storeapp_contactus_message_email'];
		} else {
			$data['storeapp_contactus_message_email'] = $this->config->get('storeapp_contactus_message_email');
		}
		
		if (isset($this->request->post['storeapp_contactus_email_icon'])) {
			$data['storeapp_contactus_email_icon'] = $this->request->post['storeapp_contactus_email_icon'];
		} else {
			$data['storeapp_contactus_email_icon'] = $this->config->get('storeapp_contactus_email_icon');
		}
		
		
		
		
		if (isset($this->request->post['storeapp_contactus_subject_share'])) {
			$data['storeapp_contactus_subject_share'] = $this->request->post['storeapp_contactus_subject_share'];
		} else {
			$data['storeapp_contactus_subject_share'] = $this->config->get('storeapp_contactus_subject_share');
		}
		
		if (isset($this->request->post['storeapp_contactus_message_share'])) {
			$data['storeapp_contactus_message_share'] = $this->request->post['storeapp_contactus_message_share'];
		} else {
			$data['storeapp_contactus_message_share'] = $this->config->get('storeapp_contactus_message_share');
		}
		
		
		if (isset($this->request->post['storeapp_contactus_url_share'])) {
			$data['storeapp_contactus_url_share'] = $this->request->post['storeapp_contactus_url_share'];
		} else {
			$data['storeapp_contactus_url_share'] = $this->config->get('storeapp_contactus_url_share');
		}
		
		
		if (isset($this->request->post['storeapp_contactus_text'])) {
			$data['storeapp_contactus_text'] = $this->request->post['storeapp_contactus_text'];
		} else {
			$data['storeapp_contactus_text'] = $this->config->get('storeapp_contactus_text');
		}
		
		if (isset($this->request->post['storeapp_socials'])) {
			$data['storeapp_socials'] = $this->request->post['storeapp_socials'];
		} else {
			$data['storeapp_socials'] = $this->config->get('storeapp_socials');
		}
		
		
	   
		if (isset($this->request->post['storeapp_quantity_priceshow'])) {
			$data['storeapp_quantity_priceshow'] = $this->request->post['storeapp_quantity_priceshow'];
		}  else {
			$data['storeapp_quantity_priceshow'] = $this->config->get('storeapp_quantity_priceshow');
		}
		
		
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////     end code contact us	    	//////////////    
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if (isset($this->request->post['storeapp_layout_active'])) {
			$data['storeapp_layout_active'] = $this->request->post['storeapp_layout_active'];
		} else {
		$data['storeapp_layout_active'] = $this->config->get('storeapp_layout_active');
		}
		
   // var_dump($data['storeapp_layout_active']);
		if (isset($this->request->post['storeapp_slideshow'])) {
			$data['storeapp_slideshow'] = $this->request->post['storeapp_slideshow'];
		} else {
			$data['storeapp_slideshow'] = $this->config->get('storeapp_slideshow');
		}

		if (isset($this->request->post['storeapp_categoryscroll'])) {
			$data['storeapp_categoryscroll'] = $this->request->post['storeapp_categoryscroll'];
		} else {
			$data['storeapp_categoryscroll'] = $this->config->get('storeapp_categoryscroll');
		}

		if (isset($this->request->post['storeapp_specialhome'])) {
			$data['storeapp_specialhome'] = $this->request->post['storeapp_specialhome'];
		} else {
			$data['storeapp_specialhome'] = $this->config->get('storeapp_specialhome');
		}

		if (isset($this->request->post['storeapp_banner'])) {
			$data['storeapp_banner'] = $this->request->post['storeapp_banner'];
		} else {
			$data['storeapp_banner'] = $this->config->get('storeapp_banner');
		}

		if (isset($this->request->post['storeapp_latest'])) {
			$data['storeapp_latest'] = $this->request->post['storeapp_latest'];
		} else {
			$data['storeapp_latest'] = $this->config->get('storeapp_latest');
		}
		
		
		if (isset($this->request->post['storeapp_popular'])) {
			$data['storeapp_popular'] = $this->request->post['storeapp_popular'];
		} else {
			$data['storeapp_popular'] = $this->config->get('storeapp_popular');
		}
		
		if (isset($this->request->post['storeapp_bestsellers'])) {
			$data['storeapp_bestsellers'] = $this->request->post['storeapp_bestsellers'];
		} else {
			$data['storeapp_bestsellers'] = $this->config->get('storeapp_bestsellers');
		}
		
		if (isset($this->request->post['storeapp_letmeknow'])) {
			$data['storeapp_letmeknow'] = $this->request->post['storeapp_letmeknow'];
		} else {
			$data['storeapp_letmeknow'] = $this->config->get('storeapp_letmeknow');
		}

		if (isset($this->request->post['storeapp_categoryproduct'])) {
			$data['storeapp_categoryproduct'] = $this->request->post['storeapp_categoryproduct'];
		} else {
			$data['storeapp_categoryproduct'] = $this->config->get('storeapp_categoryproduct');
		}
		
		if (isset($this->request->post['storeapp_categoryproducttab'])) {
			$data['storeapp_categoryproducttab'] = $this->request->post['storeapp_categoryproducttab'];
		} else {
			$data['storeapp_categoryproducttab'] = $this->config->get('storeapp_categoryproducttab');
		}
		
		if (isset($this->request->post['storeapp_html'])) {
			$data['storeapp_html'] = $this->request->post['storeapp_html'];
		} else {
			$data['storeapp_html'] = $this->config->get('storeapp_html');
		}

		
		
		if (isset($this->request->post['storeapp_slideshow_sort'])) {
			$data['storeapp_slideshow_sort'] = $this->request->post['storeapp_slideshow_sort'];
		} else {
			$data['storeapp_slideshow_sort'] = $this->config->get('storeapp_slideshow_sort');
		}

		if (isset($this->request->post['storeapp_categoryscroll_sort'])) {
			$data['storeapp_categoryscroll_sort'] = $this->request->post['storeapp_categoryscroll_sort'];
		} else {
			$data['storeapp_categoryscroll_sort'] = $this->config->get('storeapp_categoryscroll_sort');
		}

		if (isset($this->request->post['storeapp_specialhome_sort'])) {
			$data['storeapp_specialhome_sort'] = $this->request->post['storeapp_specialhome_sort'];
		} else {
			$data['storeapp_specialhome_sort'] = $this->config->get('storeapp_specialhome_sort');
		}

		if (isset($this->request->post['storeapp_banner_sort'])) {
			$data['storeapp_banner_sort'] = $this->request->post['storeapp_banner_sort'];
		} else {
			$data['storeapp_banner_sort'] = $this->config->get('storeapp_banner_sort');
		}

		if (isset($this->request->post['storeapp_latest_sort'])) {
			$data['storeapp_latest_sort'] = $this->request->post['storeapp_latest_sort'];
		} else {
			$data['storeapp_latest_sort'] = $this->config->get('storeapp_latest_sort');
		}
		
		
		
		if (isset($this->request->post['storeapp_popular_sort'])) {
			$data['storeapp_popular_sort'] = $this->request->post['storeapp_popular_sort'];
		} else {
			$data['storeapp_popular_sort'] = $this->config->get('storeapp_popular_sort');
		}
		
		if (isset($this->request->post['storeapp_bestsellers_sort'])) {
			$data['storeapp_bestsellers_sort'] = $this->request->post['storeapp_bestsellers_sort'];
		} else {
			$data['storeapp_bestsellers_sort'] = $this->config->get('storeapp_bestsellers_sort');
		}
		
		

		if (isset($this->request->post['storeapp_categoryproduct_sort'])) {
			$data['storeapp_categoryproduct_sort'] = $this->request->post['storeapp_categoryproduct_sort'];
		} else {
			$data['storeapp_categoryproduct_sort'] = $this->config->get('storeapp_categoryproduct_sort');
		}
		
		
		if (isset($this->request->post['storeapp_categoryproducttab_sort'])) {
			$data['storeapp_categoryproducttab_sort'] = $this->request->post['storeapp_categoryproducttab_sort'];
		} else {
			$data['storeapp_categoryproducttab_sort'] = $this->config->get('storeapp_categoryproducttab_sort');
		}
		
		
		if (isset($this->request->post['storeapp_html_sort'])) {
			$data['storeapp_html_sort'] = $this->request->post['storeapp_html_sort'];
		} else {
			$data['storeapp_html_sort'] = $this->config->get('storeapp_html_sort');
		}
		$this->load->model('catalog/category');
		
		$categories = $this->model_catalog_category->getCategories( array('limit' => 999999999, 'start'=>0 ) );
		
		$data['categories'] = $categories;
		
		if (isset($this->request->post['storeapp_categories'])) {
			$data['storeapp_categories'] = $this->request->post['storeapp_categories'];
		} else {
			$data['storeapp_categories'] = $this->config->get('storeapp_categories');
		}
		
		
		
		if (isset($this->request->post['storeapp_select_show_category'])) {
			$data['storeapp_select_show_category'] = $this->request->post['storeapp_select_show_category'];
		} else {
			$data['storeapp_select_show_category'] = $this->config->get('storeapp_select_show_category');
		}
		
		
		
		if (isset($this->request->post['storeapp_select_show_category_inhome'])) {
			$data['storeapp_select_show_category_inhome'] = $this->request->post['storeapp_select_show_category_inhome'];
		} else {
			$data['storeapp_select_show_category_inhome'] = $this->config->get('storeapp_select_show_category_inhome');
		}
		
		
		if (isset($this->request->post['storeapp_categorie_icons'])) {
			$data['storeapp_categorie_icons'] = $this->request->post['storeapp_categorie_icons'];
		} else {
			$data['storeapp_categorie_icons'] = $this->config->get('storeapp_categorie_icons');
		}
		
		if (isset($this->request->post['storeapp_special_date'])) {
			$data['storeapp_special_date'] = $this->request->post['storeapp_special_date'];
		} else {
			$data['storeapp_special_date'] = $this->config->get('storeapp_special_date');
		}
		
		if (isset($this->request->post['storeapp_special_background'])) {
			$data['storeapp_special_background'] = $this->request->post['storeapp_special_background'];
		} elseif($this->config->get('storeapp_special_background')) {
			$data['storeapp_special_background'] = $this->config->get('storeapp_special_background');
		}else {
			$data['storeapp_special_background'] = "#FF004E";
		}
		
		$data['HTTP_CATALOG'] = HTTP_CATALOG.'image/';
		if (isset($this->request->post['storeapp_special_background_image'])) {
			$data['storeapp_special_background_image'] = $this->request->post['storeapp_special_background_image'];
		} elseif($this->config->get('storeapp_special_background_image')) {
			$data['storeapp_special_background_image'] = $this->config->get('storeapp_special_background_image');
		}else {
			$data['storeapp_special_background_image'] =  HTTP_CATALOG."image/catalog/pic.png";
		}
		
		
		if (isset($this->request->post['storeapp_https'])) {
			$data['storeapp_https'] = $this->request->post['storeapp_https'];
		} else {
			$data['storeapp_https'] = $this->config->get('storeapp_https');
		}

		
		if (isset($this->request->post['storeapp_name'])) {
			$data['storeapp_name'] = $this->request->post['storeapp_name'];
		} else {
			$data['storeapp_name'] = $this->config->get('storeapp_name');
		}
		
		if (isset($this->request->post['storeapp_limit_home'])) {
			$data['storeapp_limit_home'] = $this->request->post['storeapp_limit_home'];
		} else {
			$data['storeapp_limit_home'] = $this->config->get('storeapp_limit_home');
		}
		
		if (isset($this->request->post['storeapp_limit_other'])) {
			$data['storeapp_limit_other'] = $this->request->post['storeapp_limit_other'];
		} else {
			$data['storeapp_limit_other'] = $this->config->get('storeapp_limit_other');
		}
		
		
		if (isset($this->request->post['storeapp_html_description'])) {
			$data['storeapp_html_description'] = $this->request->post['storeapp_html_description'];
		} else {
			$data['storeapp_html_description'] = $this->config->get('storeapp_html_description');
		}
		
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////     start code color	    	//////////////    
		/////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
		
	    
		
		if (isset($this->request->post['storeapp_primary_bg_color'])) {
			$data['storeapp_primary_bg_color'] = $this->request->post['storeapp_primary_bg_color'];
		} else {
			$data['storeapp_primary_bg_color'] = $this->config->get('storeapp_primary_bg_color');
		}
		
		if (isset($this->request->post['storeapp_primary_text_color'])) {
			$data['storeapp_primary_text_color'] = $this->request->post['storeapp_primary_text_color'];
		} else {
			$data['storeapp_primary_text_color'] = $this->config->get('storeapp_primary_text_color');
		}
		
		 if (isset($this->request->post['storeapp_disable_btn_color_text'])) {
			$data['storeapp_disable_btn_color_text'] = $this->request->post['storeapp_disable_btn_color_text'];
		} else {
			$data['storeapp_disable_btn_color_text'] = $this->config->get('storeapp_disable_btn_color_text');
		}
	
		 if (isset($this->request->post['storeapp_disable_btn_color'])) {
			$data['storeapp_disable_btn_color'] = $this->request->post['storeapp_disable_btn_color'];
		} else {
			$data['storeapp_disable_btn_color'] = $this->config->get('storeapp_disable_btn_color');
		}
		
		
		
	    if (isset($this->request->post['storeapp_colorheader'])) {
			$data['storeapp_colorheader'] = $this->request->post['storeapp_colorheader'];
		} else {
			$data['storeapp_colorheader'] = $this->config->get('storeapp_colorheader');
		}
		
		if (isset($this->request->post['storeapp_colorheader_text'])) {
			$data['storeapp_colorheader_text'] = $this->request->post['storeapp_colorheader_text'];
		} else {
			$data['storeapp_colorheader_text'] = $this->config->get('storeapp_colorheader_text');
		}
		
		
		
		
		 if (isset($this->request->post['storeapp_disable_btn_color_text'])) {
			$data['storeapp_disable_btn_color_text'] = $this->request->post['storeapp_disable_btn_color_text'];
		} else {
			$data['storeapp_disable_btn_color_text'] = $this->config->get('storeapp_disable_btn_color_text');
		}
	
		 if (isset($this->request->post['storeapp_disable_btn_color'])) {
			$data['storeapp_disable_btn_color'] = $this->request->post['storeapp_disable_btn_color'];
		} else {
			$data['storeapp_disable_btn_color'] = $this->config->get('storeapp_disable_btn_color');
		}
		
		
		
		
		 if (isset($this->request->post['storeapp_bg_color'])) {
			$data['storeapp_bg_color'] = $this->request->post['storeapp_bg_color'];
		} else {
			$data['storeapp_bg_color'] = $this->config->get('storeapp_bg_color');
		}
		
		
		
		 if (isset($this->request->post['storeapp_colortab'])) {
			$data['storeapp_colortab'] = $this->request->post['storeapp_colortab'];
		} else {
			$data['storeapp_colortab'] = $this->config->get('storeapp_colortab');
		}
		
	    if (isset($this->request->post['storeapp_colorfinish'])) {
			$data['storeapp_colorfinish'] = $this->request->post['storeapp_colorfinish'];
		} else {
			$data['storeapp_colorfinish'] = $this->config->get('storeapp_colorfinish');
		}
		
	    if (isset($this->request->post['storeapp_colorcat'])) {
			$data['storeapp_colorcat'] = $this->request->post['storeapp_colorcat'];
		} else {
			$data['storeapp_colorcat'] = $this->config->get('storeapp_colorcat');
		}
		
	    if (isset($this->request->post['storeapp_colortextcat'])) {
			$data['storeapp_colortextcat'] = $this->request->post['storeapp_colortextcat'];
		} else {
			$data['storeapp_colortextcat'] = $this->config->get('storeapp_colortextcat');
		}
		
	    if (isset($this->request->post['storeapp_colorcathome'])) {
			$data['storeapp_colorcathome'] = $this->request->post['storeapp_colorcathome'];
		} else {
			$data['storeapp_colorcathome'] = $this->config->get('storeapp_colorcathome');
		}
		
	    if (isset($this->request->post['storeapp_colortextcathome'])) {
			$data['storeapp_colortextcathome'] = $this->request->post['storeapp_colortextcathome'];
		} else {
			$data['storeapp_colortextcathome'] = $this->config->get('storeapp_colortextcathome');
		}
		
	    if (isset($this->request->post['storeapp_colortextmore'])) {
			$data['storeapp_colortextmore'] = $this->request->post['storeapp_colortextmore'];
		} else {
			$data['storeapp_colortextmore'] = $this->config->get('storeapp_colortextmore');
		}
		
	    if (isset($this->request->post['storeapp_colorbtn'])) {
			$data['storeapp_colorbtn'] = $this->request->post['storeapp_colorbtn'];
		} else {
			$data['storeapp_colorbtn'] = $this->config->get('storeapp_colorbtn');
		}
		
	    if (isset($this->request->post['storeapp_colortextbtn'])) {
			$data['storeapp_colortextbtn'] = $this->request->post['storeapp_colortextbtn'];
		} else {
			$data['storeapp_colortextbtn'] = $this->config->get('storeapp_colortextbtn');
		}
		
	    if (isset($this->request->post['storeapp_colorbtncall'])) {
			$data['storeapp_colorbtncall'] = $this->request->post['storeapp_colorbtncall'];
		} else {
			$data['storeapp_colorbtncall'] = $this->config->get('storeapp_colorbtncall');
		}
		
	    if (isset($this->request->post['storeapp_colortextbtncall'])) {
			$data['storeapp_colortextbtncall'] = $this->request->post['storeapp_colortextbtncall'];
		} else {
			$data['storeapp_colortextbtncall'] = $this->config->get('storeapp_colortextbtncall');
		}
		
	    if (isset($this->request->post['storeapp_colorbtnmail'])) {
			$data['storeapp_colorbtnmail'] = $this->request->post['storeapp_colorbtnmail'];
		} else {
			$data['storeapp_colorbtnmail'] = $this->config->get('storeapp_colorbtnmail');
		}
		
	    if (isset($this->request->post['storeapp_colortextbtnmail'])) {
			$data['storeapp_colortextbtnmail'] = $this->request->post['storeapp_colortextbtnmail'];
		} else {
			$data['storeapp_colortextbtnmail'] = $this->config->get('storeapp_colortextbtnmail');
		}
		
	    if (isset($this->request->post['storeapp_colorbtnshare'])) {
			$data['storeapp_colorbtnshare'] = $this->request->post['storeapp_colorbtnshare'];
		} else {
			$data['storeapp_colorbtnshare'] = $this->config->get('storeapp_colorbtnshare');
		}
		
	    if (isset($this->request->post['storeapp_colortextbtnshare'])) {
			$data['storeapp_colortextbtnshare'] = $this->request->post['storeapp_colortextbtnshare'];
		} else {
			$data['storeapp_colortextbtnshare'] = $this->config->get('storeapp_colortextbtnshare');
		}
		
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////
		//////////////     end code color	    	//////////////   
		///////////////////////////////////////////////////////////////////////////////////////////////////////////////  
		
		
		if (isset($this->request->post['storeapp_banners'])) {
			$data['storeapp_banners'] = $this->request->post['storeapp_banners'];
		} else {
			$data['storeapp_banners'] = $this->config->get('storeapp_banners');
		}
		$data['HTTP_CATALOG'] = HTTP_CATALOG.'image/';
		
		
		
		
		if (isset($this->request->post['storeapp_intros'])) {
			$data['storeapp_intros'] = $this->request->post['storeapp_intros'];
		}  else {
			$data['storeapp_intros'] = $this->config->get('storeapp_intros');
		}
      
		if (isset($this->request->post['storeapp_intro_status'])) {
			$data['storeapp_intro_status'] = $this->request->post['storeapp_intro_status'];
		}  else {
			$data['storeapp_intro_status'] = $this->config->get('storeapp_intro_status');
		}
		
        //  print_r($data['storeapp_intros'] );
	
		if (isset($this->request->post['storeapp_about_us'])) {
			$data['storeapp_about_us'] = $this->request->post['storeapp_about_us'];
		} else {
			$data['storeapp_about_us'] = $this->config->get('storeapp_about_us');
		}

		if (isset($this->request->post['storeapp_terms'])) {
			$data['storeapp_terms'] = $this->request->post['storeapp_terms'];
		} else {
			$data['storeapp_terms'] = $this->config->get('storeapp_terms');
		}

		
		if (isset($this->request->post['storeapp_privacy_policy'])) {
			$data['storeapp_privacy_policy'] = $this->request->post['storeapp_privacy_policy'];
		} else {
			$data['storeapp_privacy_policy'] = $this->config->get('storeapp_privacy_policy');
		}
		
		if (isset($this->request->post['storeapp_inappbrowser'])) {
			$data['storeapp_inappbrowser'] = $this->request->post['storeapp_inappbrowser'];
		} else {
			$data['storeapp_inappbrowser'] = $this->config->get('storeapp_inappbrowser');
		}
		
		
			
		//////////////////////////////////////////////////update////////////////////////////////////
		
		
		
		
		if (isset($this->request->post['storeapp_update_alert'])) {
			$data['storeapp_update_alert'] = $this->request->post['storeapp_update_alert'];
		} else {
			$data['storeapp_update_alert'] = $this->config->get('storeapp_update_alert');
		}
		
			if (isset($this->request->post['storeapp_update_need'])) {
			$data['storeapp_update_need'] = $this->request->post['storeapp_update_need'];
		} else {
			$data['storeapp_update_need'] = $this->config->get('storeapp_update_need');
		}
		
		
			if (isset($this->request->post['storeapp_update_version'])) {
			$data['storeapp_update_version'] = $this->request->post['storeapp_update_version'];
		} else {
			$data['storeapp_update_version'] = $this->config->get('storeapp_update_version');
		}
		
		
			if (isset($this->request->post['storeapp_update_url'])) {
			$data['storeapp_update_url'] = $this->request->post['storeapp_update_url'];
		} else {
			$data['storeapp_update_url'] = $this->config->get('storeapp_update_url');
		}
		
		
			if (isset($this->request->post['storeapp_update_message'])) {
			$data['storeapp_update_message'] = $this->request->post['storeapp_update_message'];
		} else {
			$data['storeapp_update_message'] = $this->config->get('storeapp_update_message');
		}
		
		
		if (isset($this->request->post['storeapp_language_active'])) {
			$data['storeapp_language_active'] = $this->request->post['storeapp_language_active'];
		} else {
			$data['storeapp_language_active'] = $this->config->get('storeapp_language_active');
		}
		
		
		if (isset($this->request->post['storeapp_language_first'])) {
			$data['storeapp_language_first'] = $this->request->post['storeapp_language_first'];
		} else {
			$data['storeapp_language_first'] = $this->config->get('storeapp_language_first');
		}
		
			
		if (isset($this->request->post['storeapp_country_show'])) {
			$data['storeapp_country_show'] = $this->request->post['storeapp_country_show'];
		} else {
			$data['storeapp_country_show'] = $this->config->get('storeapp_country_show');
		}
		
		$this->load->model('localisation/language');

		$data['languages'] = $this->model_localisation_language->getLanguages();
		
		
	
		$data['language_actives']=array(
		    array(
		        "language_id"=>"1",
		        "name"=>"persian",
		        "code"=>"fa-ir"
		        ),
		    array(
		        "language_id"=>"2",
		        "name"=>"english",
		        "code"=>"en-gb"
		        ),
		        array(
		        "language_id"=>"3",
		        "name"=>"arabic",
		        "code"=>"ar"
		        ),
		    
		    );
		    
		    	$data['header_designs']=array(
			    "1"=>$this->language->get('text_header_design1'),
			    "2"=>$this->language->get('text_header_design2'),
			    
			    );
			    
			    
		    	$data['filter_designs']=array(
			    "1"=>$this->language->get('text_filter_design1'),
			    "2"=>$this->language->get('text_filter_design2'),
			    
			    );
		
			$data['category_page_design']=array(
			    "0"=>$this->language->get('text_category_page_design1'),
			    "1"=>$this->language->get('text_category_page_design2'),
			    "2"=>$this->language->get('text_category_page_design3'),
			    "3"=>$this->language->get('text_category_page_design4')
			    );
			    
			    $data['product_list_design']=array(
    			    "0"=>$this->language->get('text_product_list_design1'),
    			    "1"=>$this->language->get('text_product_list_design2'),
    			    "2"=>$this->language->get('text_product_list_design3'),
    			    "3"=>$this->language->get('text_product_list_design4'),
    			    "4"=>$this->language->get('text_product_list_design5'),
    			    "5"=>$this->language->get('text_product_list_design6'),
			    );
			    
			 
		if (isset($this->request->post['storeapp_header_design'])) {
	    	$data['storeapp_header_design'] = $this->request->post['storeapp_header_design'];
		} else {
			$data['storeapp_header_design'] = $this->config->get('storeapp_header_design');
		}
		
			if (isset($this->request->post['storeapp_filter_design'])) {
	    	$data['storeapp_filter_design'] = $this->request->post['storeapp_filter_design'];
		} else {
			$data['storeapp_filter_design'] = $this->config->get('storeapp_filter_design');
		}
		
		if (isset($this->request->post['storeapp_product_list_design'])) {
			$data['storeapp_product_list_design'] = $this->request->post['storeapp_product_list_design'];
		} else {
			$data['storeapp_product_list_design'] = $this->config->get('storeapp_product_list_design');
		}
			    
			    
			     $data['font_family']=array(
			    "iranyekan"=>"iranyekan",
			    "tajawal"=>"tajawal",
			    "ProximaNova"=>"ProximaNova"
			    );
			    	
		if (isset($this->request->post['storeapp_fontfamily_rtl'])) {
			$data['storeapp_fontfamily_rtl'] = $this->request->post['storeapp_fontfamily_rtl'];
		} else {
			$data['storeapp_fontfamily_rtl'] = $this->config->get('storeapp_fontfamily_rtl');
		}
		
		if (isset($this->request->post['storeapp_fontfamily_ltr'])) {
			$data['storeapp_fontfamily_ltr'] = $this->request->post['storeapp_fontfamily_ltr'];
		} else {
			$data['storeapp_fontfamily_ltr'] = $this->config->get('storeapp_fontfamily_ltr');
		}
		
		/////////////////icon////////////////////////////////
		
		if (isset($this->request->post['storeapp_latest_icon'])) {
			$data['storeapp_latest_icon'] =$this->request->post['storeapp_latest_icon'];
		} elseif($this->config->get('storeapp_latest_icon')) {
			$data['storeapp_latest_icon'] = $this->config->get('storeapp_latest_icon');
		}else {
		    	$data['storeapp_latest_icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['storeapp_popular_icon'])) {
			$data['storeapp_popular_icon'] = $this->request->post['storeapp_popular_icon'];
		} elseif($this->config->get('storeapp_popular_icon')) {
			$data['storeapp_popular_icon'] = $this->config->get('storeapp_bestseller_icon');
		}else {
		    	$data['storeapp_popular_icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		
		if (isset($this->request->post['storeapp_bestseller_icon'])) {
			$data['storeapp_bestseller_icon'] =$this->request->post['storeapp_bestseller_icon'];
		} elseif($this->config->get('storeapp_bestseller_icon')) {
			$data['storeapp_bestseller_icon'] =$this->config->get('storeapp_bestseller_icon');
		}else {
		    	$data['storeapp_bestseller_icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		if (isset($this->request->post['storeapp_special_icon'])) {
			$data['storeapp_special_icon'] = $this->request->post['storeapp_special_icon'];
		} elseif($this->config->get('storeapp_special_icon')) {
			$data['storeapp_special_icon'] = $this->config->get('storeapp_special_icon');
		}else {
		  $data['storeapp_special_icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
		
			
		if (isset($this->request->post['storeapp_checkout_first_icon'])) {
			$data['storeapp_checkout_first_icon'] = $this->request->post['storeapp_checkout_first_icon'];
		} elseif($this->config->get('storeapp_checkout_first_icon')) {
			$data['storeapp_checkout_first_icon'] = $this->config->get('storeapp_checkout_first_icon');
		}else {
		  $data['storeapp_checkout_first_icon'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
			if (isset($this->request->post['storeapp_profile_banner'])) {
			$data['storeapp_profile_banner'] = $this->request->post['storeapp_profile_banner'];
		} elseif($this->config->get('storeapp_profile_banner')) {
			$data['storeapp_profile_banner'] = $this->config->get('storeapp_profile_banner');
		}else {
		  $data['storeapp_profile_banner'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		}
		
			if (isset($this->request->post['storeapp_profile_url'])) {
			$data['storeapp_profile_url'] = $this->request->post['storeapp_profile_url'];
		} elseif($this->config->get('storeapp_profile_url')) {
			$data['storeapp_profile_url'] = $this->config->get('storeapp_profile_url');
		}
		
		if (isset($this->request->post['storeapp_ShippingMethod'])) {
			$data['storeapp_ShippingMethod'] = $this->request->post['storeapp_ShippingMethod'];
		} else {
			$data['storeapp_ShippingMethod'] = $this->config->get('storeapp_ShippingMethod');
		}
		
		
		if (isset($this->request->post['storeapp_PaymentMethod'])) {
			$data['storeapp_PaymentMethod'] = $this->request->post['storeapp_PaymentMethod'];
		} else {
			$data['storeapp_PaymentMethod'] = $this->config->get('storeapp_PaymentMethod');
		}
		
		///////shipping-and-payment 
		$data['ShippingMethods'] = $this->getShippingMethods();
		$data['PaymentMethods'] = $this->getPaymentMethods();
		
		////////////////////////////////////////end update/////////////////////
		
		
		///////////ordder_status////
		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();
		
		if (isset($this->request->post['storeapp_order_status'])) {
			$data['storeapp_order_status'] = $this->request->post['storeapp_order_status'];
		} else {
			$data['storeapp_order_status'] = $this->config->get('storeapp_order_status');
		}
		//print_r($data['storeapp_order_status']);
		////////////////////////////////////////end update/////////////////////
		$this->load->model('catalog/information');

		$data['informations'] = $this->model_catalog_information->getInformations();
       $data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$this->load->model('tool/image');
		$data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['placeholder'] = $this->model_tool_image->resize('no_image.png', 100, 100);
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/module/storeapp', $data));
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/module/storeapp')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

	

		return !$this->error;
	}
	


	
	  public function deleteLayout() {
        if(isset($this->request->get['layout_id'])) {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layouts where id_layout="'.$this->request->get['layout_id'].'"');
            $query = $this->db->query('SELECT id_component  FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->get['layout_id'].'"');
            foreach($query->rows as $component_id){
                $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_banners where id_component="'.$component_id['id_component'].'"');
                $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_top_category where id_component="'.$component_id['id_component'].'"');
                $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_product_data where id_component="'.$component_id['id_component'].'"');
            }
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_layout="'.$this->request->get['layout_id'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->get['layout_id'].'"');
            $this->response->redirect($this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        } else {
            $this->response->redirect($this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], 'SSL'));
        }
    }
	
	
	  public function saveLayout() {
            if(isset($this->request->post['name'])) {
                $this->db->query('INSERT INTO '.DB_PREFIX.'storeapp_layouts (layout_name) VALUES ("'.$this->request->post['name'].'")');
                $id_layout=$this->db->getLastId();
                echo $id_layout; die;
            } else {
                $this->response->redirect($this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], 'SSL'));
            }
        
    }
	
	  public function layout() {
        
        ini_set('display_errors', 1);
         ini_set('display_startup_errors', 1);
          error_reporting(E_ALL);
        
        $data = array();
        
        $this->load->language('extension/module/storeapp');
        
        $direction = $this->language->get('direction');
        
        $this->document->addStyle('view/stylesheet/mab_layout/jquery.growl.css');
        $this->document->addStyle('view/stylesheet/mab_layout/admin-theme.css');
		 if ($direction == 'rtl') {
           $this->document->addStyle('view/stylesheet/mab_layout/layout_rtl.css');
        }else {
           $this->document->addStyle('view/stylesheet/mab_layout/layout.css');
        }
       // $this->document->addStyle('view/stylesheet/mab_layout/layout.css');
        $this->document->addStyle('view/stylesheet/mab_layout/layout_preview.css');
        $this->document->addStyle('view/stylesheet/mab_layout/CustomScrollbar.css');
        $this->document->addScript('view/javascript/mab_layout/jquery.growl.js');
        $this->document->addScript('view/javascript/mab_layout/jquery-ui.min.js');
        $this->document->addScript('view/javascript/mab_layout/CustomScrollbar.min.js');
        
        $data['heading_title'] = $this->language->get('heading_title');
        
        $data['user_token'] = $this->session->data['user_token'];
        if($this->request->get['layout_id']) {
            $data['id_layout'] = $this->request->get['layout_id'];
        } else {
            $this->response->redirect($this->url->link('extension/module/storeapp', '', 'SSL'));
        }
        $this->document->addScript('view/javascript/velovalidation.js');
        
        if (isset($this->session->data['success'])) {
            $data['success'] = $this->session->data['success'];
           unset($this->session->data['success']);
        } else {
            $data['success'] = '';
        }
        
        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }
        
        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_edit'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );
        
        $data['breadcrumbs'][] = array(
            'text' => 'Home Page Layout',
            'href' => $this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], 'SSL')
        );
        
        $this->load->model('extension/new/storeapp');
        $components = array();
        $components = $this->model_extension_new_storeapp->getComponents($data['id_layout']);
        if(!empty($components)) {
            foreach($components as $component) {
                $query = $this->db->query('Select id_component_type from ' . DB_PREFIX . 'storeapp_layout_component where id_component = '.  $component['id_component']) ;
                $component_type_id = $query->row['id_component_type'];
                $query = $this->db->query('Select component_name from ' . DB_PREFIX . 'storeapp_component_types where id = '.  $component_type_id) ;
                $component_type = $query->row['component_name'];
                $products = array();
                $component_data = array();
                if ($component_type == 'top_category') {
                    $component_data = $this->model_extension_new_storeapp->getTopCategoryData($component['id_component']);
                } else if($component_type == 'products_square' || $component_type == 'products_grid' || $component_type == 'products_horizontal'){
                   // $component_data = $this->model_extension_new_storeapp->getProductsByComponent($component['id_layout'],$component['id_component']);
                    $component_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($component['id_layout'],$component['id_component']);
                   // $products = $this->getProductsComponentData($component['id_component']);
                } else if($component_type == 'banner_square' || $component_type == 'banners_countdown' || $component_type == 'banners_grid' || $component_type == 'banner_horizontal_slider'){
                    $component_data = $this->model_extension_new_storeapp->getBannerByComponent($component['id_layout'],$component['id_component']);
                    $component_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($component['id_layout'],$component['id_component']);
                } 
                $data['components'][] = array(
                    'id_component' => $component['id_component'],
                    'id_layout' => $component['id_layout'],
                    'component_type' => $this->model_extension_new_storeapp->getComponentTypeByID($component['id_component_type']),
                    'component_heading' => @$component['component_heading'],
                    'data' => $component_data,
                    'product_data' =>  $products  
                );
            }
        }
        $setting_data = $this->config->get('storeapp');
       // $data['recent_products'] = $this->getLatestProducts(3,'scaleAspectFit',$setting_data);
        $data['number_of_components'] = count($components);
        $this->load->model('catalog/category');
        $this->load->model('localisation/language');
        
        
        $this->load->model('tool/image');
        
        $data['thumb'] = $this->model_tool_image->resize('no_image.png', 100, 100);
        
        $data['entry_serial_number'] = $this->language->get('entry_serial_number');
        $data['entry_component_heading'] = $this->language->get('entry_component_heading');
        $data['entry_tab_top_categories'] = $this->language->get('entry_tab_top_categories');
        $data['entry_tab_banner_square'] = $this->language->get('entry_tab_banner_square');
        $data['entry_tab_banner_grid'] = $this->language->get('entry_tab_banner_grid');
        $data['entry_tab_banner_countdown'] = $this->language->get('entry_tab_banner_countdown');
        $data['entry_tab_banner_sliding'] = $this->language->get('entry_tab_banner_sliding');
        $data['entry_tab_product_square'] = $this->language->get('entry_tab_product_square');
        $data['entry_tab_product_horizontal'] = $this->language->get('entry_tab_product_horizontal');
        $data['entry_tab_product_grid'] = $this->language->get('entry_tab_product_grid');
        $data['entry_tab_product_recent'] = $this->language->get('entry_tab_product_recent');
        $data['entry_edit_component'] = $this->language->get('entry_edit_component');
        $data['select_scale_aspect_fit'] = $this->language->get('select_scale_aspect_fit');
        $data['select_scale_aspect_fill'] = $this->language->get('select_scale_aspect_fill');
        $data['entry_image_content_mode'] = $this->language->get('entry_image_content_mode');
        $data['entry_select_category'] = $this->language->get('entry_select_category');
        $data['entry_select_first_category'] = $this->language->get('entry_select_first_category');
        $data['entry_select_second_category'] = $this->language->get('entry_select_second_category');
        $data['entry_select_third_category'] = $this->language->get('entry_select_third_category');
        $data['entry_select_fourth_category'] = $this->language->get('entry_select_fourth_category');
        $data['entry_select_fifth_category'] = $this->language->get('entry_select_fifth_category');
        $data['entry_select_sixth_category'] = $this->language->get('entry_select_sixth_category');
        $data['entry_select_seventh_category'] = $this->language->get('entry_select_seventh_category');
        $data['entry_select_eigth_category'] = $this->language->get('entry_select_eigth_category');
        $data['entry_select_redirect_activity'] = $this->language->get('entry_select_redirect_activity');
        $data['entry_heading'] = $this->language->get('entry_heading');
        $data['entry_category_image'] = $this->language->get('entry_category_image');
        $data['column_image'] = $this->language->get('column_image');
        $data['column_redirect'] = $this->language->get('column_redirect');
        $data['column_category_id'] = $this->language->get('column_category_id');
        $data['column_product_id'] = $this->language->get('column_product_id');
        $data['column_delete'] = $this->language->get('column_delete');
        $data['select_home'] = $this->language->get('select_home');
        $data['select_category'] = $this->language->get('select_category');
        $data['select_product'] = $this->language->get('select_product');
        $data['entry_link_to'] = $this->language->get('entry_link_to');
        $data['select_link_type_first'] = $this->language->get('select_link_type_first');
        $data['entry_number_of_products'] = $this->language->get('entry_number_of_products');
        $data['select_product_type'] = $this->language->get('select_product_type');
        $data['select_best_seller_product'] = $this->language->get('select_best_seller_product');
        $data['select_featured_product'] = $this->language->get('select_featured_product');
        $data['select_new_products'] = $this->language->get('select_new_products');
        $data['select_from_category'] = $this->language->get('select_from_category');
        $data['select_custom_product'] = $this->language->get('select_custom_product');
        $data['entry_enabled'] = $this->language->get('entry_enabled');
        $data['entry_disabled'] = $this->language->get('entry_disabled');
        $data['entry_back_color'] = $this->language->get('entry_back_color');
        $data['entry_timer_back_color'] = $this->language->get('entry_timer_back_color');
        $data['entry_time_text_color'] = $this->language->get('entry_time_text_color');
        $data['button_cancel'] = $this->language->get('button_cancel');
        $data['success_message_js'] =  $this->language->get('success_message_js');
        $data['min_category_limit_js'] =  $this->language->get('min_category_limit_js');
        $data['error_check_message_js'] =  $this->language->get('error_check_message_js');     
        $data['component_add_js'] =  $this->language->get('component_add_js');
        $data['component_delete'] =  $this->language->get('component_delete');
        $data['limit_reached'] =  $this->language->get('limit_reached');
        $data['position_update'] =  $this->language->get('position_update');
        $data['banner_delete_message'] =  $this->language->get('banner_delete_message');
        $data['banner_success_message'] =  $this->language->get('banner_success_message');
        $data['select_category_error'] =  $this->language->get('select_category_error');
        $data['empty_heading_error'] =  $this->language->get('empty_heading_error');
        $data['success_heading_message'] =  $this->language->get('success_heading_message');
        
        $data['error_text_color'] = $this->language->get('error_text_color');
        $data['error_background_color'] = $this->language->get('error_background_color');
        $data['error_countdown'] = $this->language->get('error_countdown');
        $data['select_redirect_activity_error'] = $this->language->get('select_redirect_activity_error');
        
        
        
        $results = $this->model_catalog_category->getCategories();

        $this->load->model('setting/setting');
        $settings = $this->model_setting_setting->getSetting('storeapp', 0);
        
        if (empty($settings['storeapp'])) {
            $data['custom_css'] = '';
            $data['category_image_width'] = '200';
            $data['category_image_height'] = '200';
            $data['product_image_width'] = '200';
            $data['product_image_height'] = '200';
            $data['whatsapp_chat_status'] = 0;
            $data['tab_bar_enabled'] = 1;
            $data['mobile_app_spin_win'] = 0;
            $data['fingerprint_login_status'] = 0;
            $data['phone_registration_status'] = 0;
            $data['mandatory_phone_registration_status'] = 0;
            $data['whatsapp_phone_number'] = '';
            $data['app_button_color'] = '#00a781';
            $data['app_theme_color'] = '#c3000f';
            $data['app_button_text_color'] = '#ffffff';
            $data['app_background_color'] = '#ffffff';
            $data['logo_status'] = 0;
            $data['image_for_logo'] = '';
            $data['redirect_cart_status'] = 0;
            $data['image_for_logo'] = '';
        } else {
          
           
            $data['entry_home'] = $this->language->get('entry_home');
        }
        
        $data['languages'] = $this->model_localisation_language->getLanguages();
        
        foreach($data['languages'] as $language) {
            if($language['status']==1) {
                $data['active_languages'][] = $language['language_id'];
            }
        }
        
        foreach ($results as $result) {
            $response[] = array(
                'category_id' => $result['category_id'],
                'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
            );
        }
        $sort_order = array();
        foreach ($response as $key => $value) {
            $sort_order[$key] = $value['name'];
        }
        array_multisort($sort_order, SORT_ASC, $response);
        
		
		
        $data['categories'] = $response;
        $this->load->model('localisation/language');
     
     	
     		$data['category_top_designs']=array(
			    "0"=>$this->language->get('text_category_top_design1'),
			    "1"=>$this->language->get('text_category_top_design2'),
			    "2"=>$this->language->get('text_category_top_design3'),
			    "3"=>$this->language->get('text_category_top_design4'),
			    "4"=>$this->language->get('text_category_top_design5')
			    );
     	
     	
		
        $data['https_catalog'] = HTTPS_CATALOG;
        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');
        $data['cancel'] = $this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'], 'SSL');
        $this->response->setOutput($this->load->view('extension/module/storeapp_layout',$data));
    }
	 public function saveComponent() {
        $this->load->model('extension/new/storeapp');
        $component_id = $this->model_extension_new_storeapp->saveComponent($this->request->post);
        echo $component_id; die;
    }
	 public function savePosition() {
            $this->load->model('extension/new/storeapp');
            $position_array = explode(',',$this->request->post['position_array']);
            foreach($position_array as $position=>$component) {
                $component_array = explode('_',$component);
                if(isset($component_array[3])) {
                    $component_id = $component_array[3];
                    $this->model_extension_new_storeapp->updateLayoutPosition($position,$this->request->post['id_layout'],$component_id);
                }    
            }
//        $this->load->model('webservice/webservice');
//        $component_id = $this->model_extension_new_storeapp->saveComponent($this->request->post);
//        echo $component_id; die;
    }
	
	 public function getComponentType() {
        
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->load->model('extension/new/storeapp');
        
        
		$component['id_layout'] = $this->request->post['id_layout'];
        $component['id_component'] = $this->request->post['id_component'];
        
        $query = $this->db->query('Select id_component_type from ' . DB_PREFIX . 'storeapp_layout_component where id_component = '.  $this->request->post['id_component']) ;
        $component_type_id = $query->row['id_component_type'];
        $query = $this->db->query('Select component_name from ' . DB_PREFIX . 'storeapp_component_types where id = '.  $component_type_id) ;
        $component_type = $query->row['component_name'];
        $products = array();
        if ($component_type == 'top_category') {
            $component_data = $this->model_extension_new_storeapp->getTopCategoryData($component['id_component']);
        } else if($component_type == 'products_square' || $component_type == 'products_grid' || $component_type == 'products_horizontal'){
            $component_data = $this->model_extension_new_storeapp->getProductsByComponent($component['id_layout'],$component['id_component']);
            $component_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($component['id_layout'],$component['id_component']);
             $products = $this->getProductsComponentData($component['id_component']);
        } else if($component_type == 'banner_square' || $component_type == 'banners_countdown' || $component_type == 'banners_grid' || $component_type == 'banner_horizontal_slider'){
            $component_data = $this->model_extension_new_storeapp->getBannerByComponent($component['id_layout'],$component['id_component']);
            $component_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($component['id_layout'],$component['id_component']);
        } 
		else if($component_type == 'html' ){
            $component_data = $this->model_extension_new_storeapp->getHtmlByComponent($component['id_layout'],$component['id_component']);
            $component_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($component['id_layout'],$component['id_component']);
        } 
        $data = array(
            'type' => $component_type,
            'component_data' => $component_data,
            'products' => $products
        );
        echo json_encode($data); die;
    }
    public function saveElement() {
        $this->load->model('extension/new/storeapp');
        if(isset($this->request->get['saveTopcategoryFormData']) && $this->request->get['saveTopcategoryFormData']=='true') {
            $this->model_extension_new_storeapp->saveTopCategoryData($this->request->post);
            $category_data = $this->model_extension_new_storeapp->getTopCategoryData($this->request->post['id_component']);
            echo json_encode($category_data); die;
        } else if(isset($this->request->get['saveBannerSquare']) && $this->request->get['saveBannerSquare']=='true') {
            $bs_image_row = $this->request->post['bs_image_row'];
            $sno = $bs_image_row+1;
            $product_id = 0;
            $category_id = 0;
            if($this->request->post['redirect_activity']==2) {
                $product_id =  $this->request->post['link_to'];
                $redirect_activity = 'Product';
            } else if($this->request->post['redirect_activity']==1) {
                $category_id = $this->request->post['link_to'];
                $redirect_activity = 'Category';
            } else {
                $redirect_activity = 'Home';
            }
            $id=$this->model_extension_new_storeapp->saveBannerSquareData($this->request->post);
            
            $html = "<tr id='bs-image-".$bs_image_row."'>";
            $html .= "<td class='text-left'>".$sno."</td>";                                                                                           
            $html .= "<td class='text-center'>";
            if ($this->request->post['image_url']) {
                $html .= "<img src='../image/".$this->request->post['image_url']."' class='img-thumbnail' style='width:60px !important; height:60px !important;'/>";
            } else {
            $html .= "<span class='img-thumbnail list'><i class='fa fa-camera fa-2x'></i></span>";
            }                                                                                           
            $html .= "<td class='text-left'>".$redirect_activity."</td>";                                                                                           
            $html .= "<td class='text-left'>".$category_id."</td>";                                                                                           
            $html .= "<td class='text-left'>".$product_id."</td>";      
            $html .='<input type="hidden" value="'.$id.'" id="bs-image-id-'.$bs_image_row.'"/>';
            $html .="<td><button type='button' onclick='deleteImage(".$bs_image_row.");' data-toggle='tooltip' title='' class='btn btn-danger' data-original-title='Delete Banner'><i class='fa fa-minus'></i></button>";
            $html .= "</tr>";
            echo $html; die;
        } else if(isset($this->request->get['saveProductFormData']) && $this->request->get['saveProductFormData']=='true') {
            $this->load->model('extension/new/storeapp');
            $this->model_extension_new_storeapp->saveProductData($this->request->post);
        } else if(isset($this->request->get['saveBannerCountdown']) && $this->request->get['saveBannerCountdown']=='true') {
            $bc_image_row = $this->request->post['bc_image_row'];
            $sno = $bc_image_row+1;
            $product_id = 0;
            $category_id = 0;
            if($this->request->post['redirect_activity']==2) {
                $product_id =  $this->request->post['link_to'];
                $redirect_activity = 'Product';
            } else if($this->request->post['redirect_activity']==1) {
                $category_id = $this->request->post['link_to'];
                $redirect_activity = 'Category';
            } else {
                $redirect_activity = 'Home';
            }
            $id = $this->model_extension_new_storeapp->saveBannerCountdownData($this->request->post);
            $html = "<tr id='bc-image-".$bc_image_row."'>";
            $html .= "<td class='text-left'>".$sno."</td>";                                                                                           
            $html .= "<td class='text-center'>";
            if ($this->request->post['image_url']) {
                $html .= "<img src='../image/".$this->request->post['image_url']."' class='img-thumbnail' style='width:60px !important; height:60px !important;'/>";
            } else {
            $html .= "<span class='img-thumbnail list'><i class='fa fa-camera fa-2x'></i></span>";
            }
            $html .= "<td class='text-left'>".$this->request->post['bc_countdown_validity']."</td>";
            $html .= "<td class='text-left'>".$this->request->post['bc_background_color']."</td>";
            $html .= "<td class='text-left'>".$this->request->post['bc_text_color']."</td>"; 
            $html .= "<td class='text-left'>".$redirect_activity."</td>";                                                                                           
            $html .= "<td class='text-left'>".$category_id."</td>";                                                                                           
            $html .= "<td class='text-left'>".$product_id."</td>"; 
            $html .='<input type="hidden" value="'.$id.'" id="bc-image-id-'.$bc_image_row.'"/>';
            $html .="<td><button type='button' onclick='deleteBCImage(.$bc_image_row.);' data-toggle='tooltip' title='' class='btn btn-danger' data-original-title='Delete Banner'><i class='fa fa-minus'></i></button>";
            $html .= "</tr>";
            echo $html; die;
        }
        die();
    }
	
	 public function selectCategory() {
        $json = array();

        $json = $this->selectCategoryFilter();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }
	  //Function to get categories
    public function selectCategoryFilter() {

        $response = array();

        $this->load->model('catalog/category');

        $filter_data = array(
            'filter_name' => ''
        );

        $results = $this->model_catalog_category->getCategories($filter_data);

        foreach ($results as $result) {
            $response[] = array(
                'category_id' => $result['category_id'],
                'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
            );
        }


        $sort_order = array();

        foreach ($response as $key => $value) {
            $sort_order[$key] = $value['name'];
        }

        array_multisort($sort_order, SORT_ASC, $response);

        return $response;
    }

    public function selectProduct() {
        $json = array();

        $json = $this->selectProductFilter();

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($json));
    }

    //Function to get products
    public function selectProductFilter() {

        $response = array();

        $this->load->model('catalog/product');
        $this->load->model('catalog/option');

        $filter_data = array(
            'filter_name' => ''
        );

        $results = $this->model_catalog_product->getProducts($filter_data);

        foreach ($results as $result) {

            $response[] = array(
                'product_id' => $result['product_id'],
                'name' => strip_tags(html_entity_decode($result['name'], ENT_QUOTES, 'UTF-8'))
            );
        }

        return $response;
    }
	
	
	  public function getComponent() {
       $this->load->model('extension/new/storeapp');
       $this->load->model('catalog/product');
        if(isset($this->request->get['getCategoryForm']) && $this->request->get['getCategoryForm'] =='true') {
            $category_data = $this->model_extension_new_storeapp->getTopCategoryData($this->request->post['id_component']);
            $this->response->setOutput(json_encode($category_data));
        }
        if(isset($this->request->get['getBannerForm']) && $this->request->get['getBannerForm'] =='true') {
            $banner_data = array();
            $banner_data = $this->model_extension_new_storeapp->getBannerByComponent($this->request->post['id_layout'],$this->request->post['id_component']);
            $banner_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($this->request->post['id_layout'],$this->request->post['id_component']);
            $this->response->setOutput(json_encode($banner_data));
        }
        if(isset($this->request->get['getBannerCountdownForm']) && $this->request->get['getBannerCountdownForm'] =='true') {
            $banner_data = array();
            $banner_data = $this->model_extension_new_storeapp->getBannerByComponent($this->request->post['id_layout'],$this->request->post['id_component']);
            $banner_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($this->request->post['id_layout'],$this->request->post['id_component']);
            $this->response->setOutput(json_encode($banner_data));
        }
        if(isset($this->request->get['getProductForm']) && $this->request->get['getProductForm'] =='true') {
            $product_data = array();
            $product_data = $this->model_extension_new_storeapp->getProductsByComponent($this->request->post['id_layout'],$this->request->post['id_component']);
            $product_data[0]['heading'] = $this->model_extension_new_storeapp->getComponentHeading($this->request->post['id_layout'],$this->request->post['id_component']);
            $this->response->setOutput(json_encode($product_data));
        } if(isset($this->request->get['ProductRecent']) && $this->request->get['ProductRecent'] =='true') {
            $heading = $this->model_extension_new_storeapp->getComponentHeading($this->request->post['id_layout'],$this->request->post['id_component']);
            $this->response->setOutput(json_encode($heading));
        }else if(isset($this->request->get['html']) && $this->request->get['html'] =='true') {
            $html_data = $this->model_extension_new_storeapp->getHtmlByComponent($this->request->post['id_layout'],$this->request->post['id_component']);
            $this->response->setOutput(json_encode($html_data));
        }
    }
    public function getFeaturedProducts($number_of_products=10,$image_content_mode=1,$setting_data) {
        
        $store_id = $this->config->get('config_store_id');
        $this->load->model('setting/setting');
        $webservice_setting = $this->model_setting_setting->getSetting('storeapp', $store_id);

        $layout_id = '1';
        $code = 'featured';
        $this->load->model('extension/new/storeapp');
        $this->load->model('design/layout');
        $this->load->model('setting/module');
        $this->load->model('catalog/product');
        $getHomeTopFeaturedProducts = $this->model_extension_new_storeapp->getHomeLayoutModules($layout_id, $code);
        $data['products'] = array();
        if (count($getHomeTopFeaturedProducts) > 0) {

                $modules = $this->model_design_layout->getLayoutModules($layout_id, 'content_top');
                    
                    foreach ($modules as $module) {

                        $part = explode('.', $module['code']);
                        if (isset($part[0])) {
                            $code1 = $part[0];
}

                        if ($code1 && $code1 == $code) {
                            if (isset($part[1])) {
                                $setting_info = $this->model_setting_module->getModule($part[1]);

                                if ($setting_info && $setting_info['status']) {
                                    $getModulesSettingArray = $setting_info;
                                    if (array_key_exists('product', $getModulesSettingArray) && count($getModulesSettingArray['product']) > 0) {
                                        $featuredProducts = array_slice($getModulesSettingArray['product'], 0, $getModulesSettingArray['limit'], true);
                                        $width = $getModulesSettingArray['width'];
                                        $height = $getModulesSettingArray['height'];
                                        
                                        if (!empty($featuredProducts)) {
                                            foreach ($featuredProducts as $featuredProduct) {
                                                $productInfo = $this->model_catalog_product->getProduct($featuredProduct);
                                                if ($productInfo) {
                                                     $image = HTTPS_CATALOG.'image/'.($productInfo['image']);
                                                    //Check if webp image exists
                                                    $info = pathinfo($image);
                                                    if (isset($info['extension'])) {
                                                        $directory = str_replace(HTTP_SERVER . 'image/cache/', DIR_IMAGE . 'cache/', $info['dirname']);
                                                        if (file_exists($directory . '/' . $info['filename'] . '.webp')) {
                                                            $image = $info['dirname'] . '/' . $info['filename'] . '.webp';
                                                        }
                                                    }
                                                    

                                                    if (isset($productInfo['special'])) {
                                                        if($productInfo['price']>0) {
                                                            $discount_price = $this->currency->format($this->tax->calculate($productInfo['special'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                                                            $act_price = $this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax'));
                                                            $disc_price = $this->tax->calculate($productInfo['special'], $productInfo['tax_class_id'], $this->config->get('config_tax'));
                                                            $discount_perc = (($act_price - $disc_price) / $act_price) * 100;
                                                            $discount_percentage = number_format($discount_perc, 2);
                                                        } else{
                                                            $productInfo['price']=$productInfo['special'];
                                                            $discount_price = $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                                                            $discount_percentage = 0;

                                                        }
                                                    } else {
                                                        $discount_price = $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                                                        $discount_percentage = 0;
                                                    }
                                                    if ($productInfo['date_added'] >= $setting_data['new_product_start_date']) {
                                                        $is_new = 1;
                                                    } else {
                                                        $is_new = 0;
                                                    }
                                                    $data['products'][] = array(
                                                        'id' => $productInfo['product_id'],
                                                        'name' => html_entity_decode(str_replace('&quot;', '"', $productInfo['name'])),
                                                        'available_for_order' => 1,
                                                        'new_products' => $is_new,
                                                        'on_sale_products' => $discount_percentage ? 1 : 0,
                                                        //'category_name' => html_entity_decode(isset($parent_category['name']) ? $parent_category['name'] : ''),
                                                        'ClickActivityName' => 'ProductActivity',
                                                        //'category_id' => isset($parent_category['category_id']) ? $parent_category['category_id'] : '',
                                                        'price' => $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency')),
                                                        'src' => $image,
                                                        'discount_price' => $discount_price,
                                                        'discount_percentage' => $discount_percentage,
                                                        //'is_in_wishlist' => $this->isInWishlist($productInfo['product_id']),
                                                        'image_contentMode' => $image_content_mode,
                                                    );
                                                }
                                            }
                                        }
                                        break;
                                    }
                                }
                            } 
                        }
                    }
                }
        
        return $data['products'];
    }
	
	
	  public function deleteComponent() {
        if(isset($this->request->get['deleteTopcategorycomponent'])&& $this->request->get['deleteTopcategorycomponent']=='true') {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->post['id_layout'].'" and id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_top_category where id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_component="'.$this->request->post['id_component'].'"');
            
        } else if(isset($this->request->get['deleteBannercomponent'])&& $this->request->get['deleteBannercomponent']=='true') {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->post['id_layout'].'" and id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_banners where id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_component="'.$this->request->post['id_component'].'"');
            
        } else if(isset($this->request->get['deleteProductcomponent'])&& $this->request->get['deleteProductcomponent']=='true') {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->post['id_layout'].'" and id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_product_data where id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_component="'.$this->request->post['id_component'].'"');
            
        } else if(isset($this->request->get['deleteHtmlcomponent'])&& $this->request->get['deleteHtmlcomponent']=='true') {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->post['id_layout'].'" and id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_html where id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_component="'.$this->request->post['id_component'].'"');
            
        }else {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_layout_component where id_layout="'.$this->request->post['id_layout'].'" and id_component="'.$this->request->post['id_component'].'"');
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_component="'.$this->request->post['id_component'].'"');
        }
    }
	
	public function deleteImage() {
        $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_banners where id_component="'.$this->request->post['id_component'].'" and id="'.$this->request->post['banner_id'].'"');
        $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading where id_component="'.$this->request->post['id_component'].'"');
    }
    
    public function saveHeading() {
        if(isset($this->request->post['heading'])) {
            $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_components_heading WHERE id_component = "'.(int)$this->request->post['id_component'].'"');
             if(isset($this->request->post['icon'])) {
            $this->db->query('INSERT INTO '.DB_PREFIX.'storeapp_components_heading(id_layout,id_component,heading,icon) VALUES ("'.(int)$this->request->post['id_layout'].'","'.(int)$this->request->post['id_component'].'","'.$this->request->post['heading'].'","'.$this->request->post['icon'].'")');
             }else {
              $this->db->query('INSERT INTO '.DB_PREFIX.'storeapp_components_heading(id_layout,id_component,heading) VALUES ("'.(int)$this->request->post['id_layout'].'","'.(int)$this->request->post['id_component'].'","'.$this->request->post['heading'].'")');
               
             }
        }
    }
	public function saveHtml() {
        if(isset($this->request->post['text'])) {
		   $this->db->query('DELETE FROM '.DB_PREFIX.'storeapp_html where id_component="'.$this->request->post['id_component'].'"');
      
            $this->db->query('INSERT INTO '.DB_PREFIX.'storeapp_html(id_component,text) VALUES ("'.(int)$this->request->post['id_component'].'","'.$this->request->post['text'].'")');
        }
    }

	  public function getBestSellerProducts($number_of_products=10,$image_content_mode=1,$setting_data) {
        
        $this->load->model('extension/new/storeapp');
        $this->load->model('setting/setting');

        $store_id = $this->config->get('config_store_id');
        $webservice_setting = $this->model_setting_setting->getSetting('storeapp', $store_id);

        $productInfoData = $this->model_extension_new_storeapp->getBestSellerProducts($number_of_products);

            if (!empty($productInfoData)) {
                foreach ($productInfoData as $productInfo) {

                    $image = HTTPS_CATALOG.'image/'.$productInfo['image'];
                    //Check if webp image exists
                    $info = pathinfo($image);
                    if (isset($info['extension'])) {
                        $directory = str_replace(HTTP_SERVER . 'image/cache/', DIR_IMAGE . 'cache/', $info['dirname']);
                        if (file_exists($directory . '/' . $info['filename'] . '.webp')) {
                            $image = $info['dirname'] . '/' . $info['filename'] . '.webp';
                        }
                    }
                    if (isset($productInfo['special'])) {
                        if($productInfo['price']>0) {
                            $discount_price = $this->currency->format($this->tax->calculate($productInfo['special'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                            $act_price = $this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax'));
                            $disc_price = $this->tax->calculate($productInfo['special'], $productInfo['tax_class_id'], $this->config->get('config_tax'));
                            $discount_perc = (($act_price - $disc_price) / $act_price) * 100;
                            $discount_percentage = number_format($discount_perc, 2);
                        } else{
                            $productInfo['price']=$productInfo['special'];
                            $discount_price = $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                            $discount_percentage = 0;
                            
                        }
                    } else {
                        $discount_price = $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                        $discount_percentage = 0;
                    }

                    if ($productInfo['date_added'] >= $setting_data['new_product_start_date']) {
                        $is_new = 1;
                    } else {
                        $is_new = 0;
                    }
                    $data['products'][] = array(
                        'id' => $productInfo['product_id'],
                        'name' => html_entity_decode(str_replace('&quot;', '"', $productInfo['name'])),
                        'available_for_order' => 1,
                        'new_products' => $is_new,
                        'on_sale_products' => $discount_percentage ? 1 : 0,
                        'ClickActivityName' => 'ProductActivity',
                        'price' => $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency')),
                        'src' => $image,
                        'discount_price' => $discount_price,
                        'discount_percentage' => $discount_percentage,
                        'image_contentMode' => $image_content_mode,
                    );
                }
            }
        return $data['products'];
    }
    
    public function getLatestProducts($number_of_products=10,$image_content_mode=1,$setting_data) {
        
        $this->load->model('setting/setting');
        $this->load->model('catalog/product');
        $store_id = $this->config->get('config_store_id');
        $webservice_setting = $this->model_setting_setting->getSetting('storeapp', $store_id);
        
        //print_r($setting_data); die;
        
        $filter_data = array(
            'sort' => 'p.date_added',
            'order' => 'DESC',
            'start' => 0,
            'limit' => $number_of_products
        );

        $productInfoData = $this->model_catalog_product->getProducts($filter_data);

        if (!empty($productInfoData)) {
            $i = 0;
            foreach ($productInfoData as $productInfo) {
                if($i<$number_of_products) {
                    $image = HTTPS_CATALOG.'image/'.$productInfo['image'];
                    //Check if webp image exists
                    $info = pathinfo($image);
                    if (isset($info['extension'])) {
                        $directory = str_replace(HTTP_SERVER . 'image/cache/', DIR_IMAGE . 'cache/', $info['dirname']);
                        if (file_exists($directory . '/' . $info['filename'] . '.webp')) {
                            $image = $info['dirname'] . '/' . $info['filename'] . '.webp';
                        }
                    }

                    if (isset($productInfo['special'])) {
                        if($productInfo['price']>0) {
                            $discount_price = $this->currency->format($this->tax->calculate($productInfo['special'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                            $act_price = $this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax'));
                            $disc_price = $this->tax->calculate($productInfo['special'], $productInfo['tax_class_id'], $this->config->get('config_tax'));
                            $discount_perc = (($act_price - $disc_price) / $act_price) * 100;
                            $discount_percentage = number_format($discount_perc, 2);
                        } else{
                            $productInfo['price']=$productInfo['special'];
                            $discount_price = $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                            $discount_percentage = 0;

                        }
                    } else {
                        $discount_price = $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency'));
                        $discount_percentage = 0;
                    }
                    if ($productInfo['date_added'] >= $setting_data['new_product_start_date']) {
                        $is_new = 1;
                    } else {
                        $is_new = 0;
                    }
                    $data['products'][] = array(
                        'id' => $productInfo['product_id'],
                        'name' => html_entity_decode(str_replace('&quot;', '"', $productInfo['name'])),
                        'available_for_order' => 1,
                        'new_products' => $is_new,
                        'on_sale_products' => $discount_percentage ? 1 : 0,
                        //'category_name' => html_entity_decode(isset($parent_category['name']) ? $parent_category['name'] : ''),
                        'ClickActivityName' => 'ProductActivity',
                        'category_id' => isset($parent_category['category_id']) ? $parent_category['category_id'] : '',
                        'price' => $this->currency->format($this->tax->calculate($productInfo['price'], $productInfo['tax_class_id'], $this->config->get('config_tax')), $this->config->get('config_currency')),
                        'src' => $image,
                        'discount_price' => $discount_price,
                        'discount_percentage' => $discount_percentage,
                        //'is_in_wishlist' => $this->isInWishlist($productInfo['product_id']),
                        'image_contentMode' => $image_content_mode,
                    );
                } else {
                    break;
                }
            }
        }
        return $data['products'];
    }
    
	
	
	  public function getProductsComponentData($id_component){
        $setting_data = $this->config->get('storeapp');
        $query = $this->db->query('Select * from  ' . DB_PREFIX . 'storeapp_product_data where id_component =' . (int) $id_component);
        $product_data = $query->row;
        if (count($product_data) > 0) {
            $product_type = $product_data['product_type'];
            $number_of_products = $product_data['number_of_products'];
            $image_content_mode = $product_data['image_content_mode']?'scaleAspectFit':'scaleAspectFill';
            $products = array();
            if ($product_type == 'best_seller') {
                $products = $this->getBestSellerProducts($number_of_products, $image_content_mode,$setting_data);
            } elseif ($product_type == 'new_products') {
                $products = $this->getLatestProducts($number_of_products,$image_content_mode,$setting_data);
            } elseif ($product_type == 'featured_products') {
                $products = $this->getFeaturedProducts($number_of_products,$image_content_mode,$setting_data);
            } elseif ($product_type == 'special_products') {
                $products = $this->getSpecialProducts($number_of_products,$image_content_mode,$setting_data);
            } elseif ($product_type == 'from_category') {
                $product_list = array();
                $product_list = explode(',', $product_data['category_products']);
                $products = $this->getProducts($product_list, $number_of_products, $image_content_mode);
            } elseif ($product_type == 'custom_product') {
                $product_list = array();
                $product_list = explode(',', $product_data['custom_products']);
                $products = $this->getProducts($product_list, $number_of_products, $image_content_mode);
            }
            $sliced_product = array();
            $sliced_product = array_slice($products, 0, $number_of_products);
            return $sliced_product;
        }
    }
    
	
	public function send(){
	$json = array();
	$tokens=array();
		$tokensnew=array();
	$notification_title=$this->request->post['title'];
	$notification_text=$this->request->post['text'];
	
	$sql = "SELECT token FROM `" . DB_PREFIX . "token_device`";
	   $query = $this->db->query($sql);
		$results=$query->rows;
		foreach($results as $result){
		array_push($tokens,$result['token']);
		}
	//	print_r($tokens);
	
		require_once(DIR_SYSTEM . 'library/fcm/fcm.php');
		$this->fcm = new Fcm();
    	$slice=count($tokens)/50;
    	$slice=$slice+1;
        $j =0;
        $step = 50; // set the step value (in your case it will 50.) 
        for($i = 0 ;$i<$slice;$i++)
        {
         $tokensnew = array_slice($tokens,$j,$step);
         $j = $j+$step; // increment counter by to step position. 
         
        
		$result=$this->fcm->send_notification($notification_text,$notification_title,$tokensnew,$this->config->get('storeapp_serverKey'),$this->config->get('storeapp_https'));
        }
	     $this->load->model('new/notification');
		 $this->request->post['customer_id']=0;
         $this->model_new_notification->addNotification($this->request->post);
		 
	$this->session->data['install_total'] = $result;
			
	$json['alert']="تعداد پیام های ارسالی:".$result;
	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function uninstall() {
		
		$this->db->query("
			DROP TABLE IF EXISTS `".DB_PREFIX."token_device`");
			
			 $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_banners`");
			
				 $this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_layouts`");
			
				$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_product_data`");
			
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_components_heading`");
			
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_component_types`");
			
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_layout_component`");
			$this->db->query("DROP TABLE IF EXISTS `".DB_PREFIX."storeapp_top_category`");

	}
	public function update(){
		$json = array();
		$tokens=array();
		$query = $this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."token_device` (
			  `token_device` int(11) NOT NULL AUTO_INCREMENT,
			  `token` varchar(256) NOT NULL ,
			  `customer_id` int(100) NOT NULL,
			  `date_modified` datetime NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`token_device`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;
		");
		$query = $this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."notification` (
			  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(256) NOT NULL ,
			  `message` text,
			  `message_long` text,
			  `customer_id` int(11) NOT NULL ,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`notification_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;
		");
		
			//$col = $this->db->query("SELECT lat FROM `".DB_PREFIX."address`");
			$col = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."address` LIKE 'lat'");
			if (!$col->num_rows) {
				 $this->db->query("ALTER TABLE `".DB_PREFIX."address` ADD lat varchar(255) NULL");
				$this->db->query("ALTER TABLE `".DB_PREFIX."address` ADD lng varchar(255) NULL");

			}
		 
			$collnglat = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."order` LIKE 'shipping_lnglat'");
			if (!$collnglat->num_rows) {
			 $this->db->query("ALTER TABLE `".DB_PREFIX."order` ADD shipping_lnglat varchar(255) NULL");
	         }
			 $coltoken = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."token_device` LIKE 'platform'");
			if (!$coltoken->num_rows) {
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `version` varchar(11) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `os` varchar(255) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `platform` varchar(255) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `model` varchar(255) NOT NULL");
		     $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `manufacturer` varchar(255) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD  `versionApp` varchar(11) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD  `status` tinyint(1) NOT NULL DEFAULT 1");
	         }
	
	$json['alert']='ok';
	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
		public function install() {
		$this->load->model('setting/setting');

		$data['storeapp_name'] = 'digikala';
		$data['storeapp_token'] = '7540063758593333333';
		$data['storeapp_limit_home'] = '10';
		$data['storeapp_limit_other'] = '20';
		
		$data['storeapp_language_first'] = 'en-gb';
		$data['storeapp_quantity_priceshow'] = '1';
		
		
		$data['storeapp_information_help'] = '6';
		$data['storeapp_inappbrowser'] = '_blank';
		
		$data['storeapp_header_design'] = '2';
		$data['storeapp_filter_design'] = '2';
		$data['storeapp_select_show_category'] = '1';
		$data['storeapp_product_list_design'] = '0';
		
		
		$data['storeapp_fontfamily_rtl'] = 'iranyekan';
		$data['storeapp_fontfamily_ltr'] = 'ProximaNova';
		
		$data['storeapp_intro_status'] = '1';
		$data['storeapp_checkout_first_icon'] = 'http://opencart-ir.com/appnew/image/cache/no_image-100x100.png';
		$data['storeapp_profile_banner'] = 'catalog/app_image/20191017035659_922.png';
		$data['storeapp_profile_url'] = 'http://opencart-ir.com/appnew/iphone';
		///color////
		$data['storeapp_primary_bg_color'] = '#ff1443';
		$data['storeapp_primary_text_color'] = '#ffffff';
		$data['storeapp_colorheader'] = '#f2f2f2';
		$data['storeapp_colorheader_text'] = '#616161';
		$data['storeapp_bg_color'] = '#efefef';
		$data['storeapp_colorcathome'] = '#ffffff';
		$data['storeapp_colortextcathome'] = '#000000';
		$data['storeapp_disable_btn_color_text'] = '#d6d6d6';
		$data['storeapp_disable_btn_color'] = '#d6d6d6';
		
		///end color ////
		$data['storeapp_contactus_namestore'] = 'ionic 5 opencart';
		$data['storeapp_contactus_icon'] = 'catalog/app_image/logos/opencart logo similar to digicala3.png';
		$data['storeapp_contactus_privacy_link'] = 'http://opencart-ir.com/appnew/privacy';
		$data['storeapp_contactus_complaints_link'] = 'http://opencart-ir.com/appnew/privacy';
		$data['storeapp_contactus_description'] = 'test';
		$data['storeapp_contactus_email'] = '6';
		$data['storeapp_contactus_email_title'] = 'test';
		$data['storeapp_contactus_email_icon'] = 'catalog/app_image/icons/email.png';
		$data['storeapp_contactus_callnumber'] = 'tel:+989925863411';
		//new
		$data['storeapp_contactus_call_title'] = 'call us ';
		$this->model_setting_setting->editSetting('storeapp', $data);
		$data2=array();
		$data2['storeapp_language_active'] = '["fa-ir","en-gb","ar"]';
		$data2['storeapp_icon'] = '{"fa-ir":"catalog\/app_image\/logos\/opencartlogo_fasi.png","en-gb":"catalog\/app_image\/logos\/opencart logo similar to digicala3.png","ar":"catalog\/app_image\/logos\/opencartlogo_fasi.png"}';	
		$data2['storeapp_layout_active'] = '{"fa-ir":["3","12"],"en-gb":["9","7"],"ar":["10","11"]}';
		$data2['storeapp_intros'] = '[{"image":"catalog\/app_image\/intro\/Untitled-1.gif","title":"Home","type":"category","id":"","icon":"catalog\/app_image\/home.png","description":"in home page you can access to all application functionality and watch all content like categories and etc"},{"image":"catalog\/app_image\/intro\/Untitled-2.gif","title":"change language ","type":"product","id":"","icon":"catalog\/app_image\/translate.png","description":"if you need to change application language for better understand you can navigate to language page"}]';
		$data2['storeapp_order_status'] = '{"7":{"name":"Canceled","id":"7","status":"1","icon":"catalog\/app_image\/checkout\/ \u0642\u0631\u0645\u0632.png"},"9":{"name":"Canceled Reversal","id":"9","status":"0","icon":""},"13":{"name":"Chargeback","id":"13","status":"0","icon":""},"5":{"name":"Complete","id":"5","status":"1","icon":"catalog\/app_image\/checkout\/ \u062a\u062d\u0648\u06cc\u0644 \u0634\u062f\u0647.png"},"8":{"name":"Denied","id":"8","status":"1","icon":"catalog\/app_image\/checkout\/ \u0642\u0631\u0645\u0632.png"},"14":{"name":"Expired","id":"14","status":"0","icon":""},"10":{"name":"Failed","id":"10","status":"0","icon":""},"1":{"name":"Pending","id":"1","status":"1","icon":"catalog\/app_image\/checkout\/ \u0632\u0631\u062f.png"},"15":{"name":"Processed","id":"15","status":"1","icon":"catalog\/app_image\/process.png"},"2":{"name":"Processing","id":"2","status":"1","icon":"catalog\/app_image\/process.png"},"11":{"name":"Refunded","id":"11","status":"0","icon":""},"12":{"name":"Reversed","id":"12","status":"0","icon":""},"3":{"name":"Shipped","id":"3","status":"1","icon":"catalog\/app_image\/purchase.png"},"16":{"name":"Voided","id":"16","status":"0","icon":""}}';
	    $data2['storeapp_socials'] = '[{"title":"telegram ","url":"https:\/\/t.me\/ali_talaee_eng","icon":"catalog\/app_image\/icons\/Telegram.png","description":"support on telegram"},{"title":"whatsapp","url":"+989925863411","icon":"catalog\/app_image\/icons\/Whatsapp.png","description":"support on whats app"},{"title":"facebook","url":"SWEN.FB","icon":"catalog\/app_image\/icons\/facebook.png","description":"support on Facebook"},{"title":"twitter","url":"Twitter","icon":"catalog\/app_image\/icons\/Twiter.png","description":"support on Twitter"},{"title":"instagram","url":"swen.ig","icon":"catalog\/app_image\/icons\/instagram.png","description":"support on Instagram"}]';
		$code='storeapp';
		$store_id=0;
		foreach ($data2 as $key => $value) {
				$this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `code` = '" . $this->db->escape($code) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "', serialized = '1'");
				
		}
		$query = $this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."token_device` (
			  `token_device` int(11) NOT NULL AUTO_INCREMENT,
			  `token` varchar(256) NOT NULL ,
			  `customer_id` int(100) NOT NULL,
			  `date_modified` datetime NOT NULL,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`token_device`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;
		");
		$query = $this->db->query("
			CREATE TABLE IF NOT EXISTS `".DB_PREFIX."notification` (
			  `notification_id` int(11) NOT NULL AUTO_INCREMENT,
			  `title` varchar(256) NOT NULL ,
			  `message` text,
			  `message_long` text,
			  `customer_id` int(11) NOT NULL ,
			  `date_added` datetime NOT NULL,
			  PRIMARY KEY (`notification_id`)
			) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin ;
		");
		
			
			$col = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."address` LIKE 'lat'");
			if (!$col->num_rows) {
				 $this->db->query("ALTER TABLE `".DB_PREFIX."address` ADD lat varchar(255) NULL");
				$this->db->query("ALTER TABLE `".DB_PREFIX."address` ADD lng varchar(255) NULL");

			}
		 
			$collnglat = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."order` LIKE 'shipping_lnglat'");
			if (!$collnglat->num_rows) {
			 $this->db->query("ALTER TABLE `".DB_PREFIX."order` ADD shipping_lnglat varchar(255) NULL");
	         }
			 $coltoken = $this->db->query("SHOW COLUMNS FROM `".DB_PREFIX."token_device` LIKE 'platform'");
			if (!$coltoken->num_rows) {
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `version` varchar(11) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `os` varchar(255) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `platform` varchar(255) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `model` varchar(255) NOT NULL");
		     $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD `manufacturer` varchar(255) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD  `versionApp` varchar(11) NOT NULL");
			 $this->db->query("ALTER TABLE `".DB_PREFIX."token_device` ADD  `status` tinyint(1) NOT NULL DEFAULT 1");
	         }
			 
	        $this->load->model('user/user_group');
            $this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/new/list_phone' );
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/new/list_phone' );
			
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'access', 'extension/new/notification' );
			$this->model_user_user_group->addPermission($this->user->getGroupId(), 'modify', 'extension/new/notification' );
			
			
			/////////////new //////////
		 $create_table_layouts = "
           CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_layouts` (
          `id_layout` int(11) NOT NULL AUTO_INCREMENT,
          `layout_name` varchar(200) NOT NULL,
		  `layout_sort` int(11) NOT NULL,
          PRIMARY KEY (`id_layout`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_layouts);
		 $insert_table_layouts = "INSERT INTO `" . DB_PREFIX . "storeapp_layouts` (`id_layout`, `layout_name`, `layout_sort`) VALUES
					(3, 'موبایل و لب تاپ', 2),
					(9, 'Home 1', 1),
					(7, 'Home 2', 2),
					(12, 'لوازم خانگی', 1),
					(10, 'الصفحة الرئيسية', 1),
					(11, 'السوائل و المشروبات', 0),
					(13, 'test', 0);
					";
		 $this->db->query($insert_table_layouts);
		 $create_table_product_data = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_product_data` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `product_type` varchar(200) NOT NULL,
          `category_products` text,
          `custom_products` text,
          `image_content_mode` varchar(200) NOT NULL,
          `number_of_products` int(11) NOT NULL,
          `id_category` int(11) DEFAULT NULL,
          `id_component` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_product_data);
		
		$insert_table_product_data = "INSERT INTO `" . DB_PREFIX . "storeapp_product_data` (`id`, `product_type`, `category_products`, `custom_products`, `image_content_mode`, `number_of_products`, `id_category`, `id_component`) VALUES
				(66, 'from_category', '', '', '0', 10, 59, 128),
				(62, 'new_products', '', '', '1', 10, 0, 135),
				(63, 'new_products', '', '', '0', 10, 0, 140),
				(79, 'featured_products', '', '', '0', 10, 0, 105),
				(112, 'new_products', '', '', '1', 10, 0, 85),
				(31, 'new_products', '', '', '0', 10, 0, 59),
				(32, 'new_products', '', '', '0', 10, 0, 61),
				(33, 'new_products', '', '', '0', 10, 0, 63),
				(34, 'new_products', '', '', '1', 10, 0, 64),
				(35, 'new_products', '', '', '0', 10, 0, 71),
				(36, 'new_products', '', '', '0', 10, 0, 73),
				(37, 'new_products', '', '', '0', 10, 0, 75),
				(38, 'new_products', '', '', '0', 10, 0, 76),
				(113, 'featured_products', '', '', '1', 10, 0, 86),
				(114, 'featured_products', '', '', '1', 10, 0, 89),
				(106, 'new_products', '', '', '1', 10, 0, 92),
				(102, 'new_products', '', '', '1', 10, 0, 96),
				(109, 'featured_products', '', '', '1', 10, 0, 148),
				(116, 'best_seller', '', '', '0', 10, 0, 100),
				(69, 'from_category', '', '', '1', 10, 20, 103),
				(77, 'new_products', '', '', '0', 10, 0, 143),
				(73, 'from_category', '', '', '0', 10, 57, 145),
				(97, 'featured_products', '', '', '1', 10, 0, 147),
				(94, 'featured_products', '', '', '1', 10, 0, 151),
				(110, 'new_products', '', '', '1', 10, 0, 153),
				(111, 'new_products', '', '', '1', 10, 0, 156),
				(115, 'featured_products', '', '', 'null', 23, 0, 116);";
				
				$this->db->query($insert_table_product_data);
		
		$create_table_banner_data = "
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_banners` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `id_component` int(11) NOT NULL,
              `id_banner_type` int(11) NOT NULL,
              `countdown` varchar(200) DEFAULT NULL,
              `product_id` int(10) DEFAULT NULL,
              `category_id` int(10) DEFAULT NULL,
              `redirect_activity` enum('category','product','home') DEFAULT NULL,
              `image_url` longtext,
              `image_type` enum('url','image') DEFAULT NULL,
			  `banner_design`  int(10) DEFAULT NULL,
              `image_content_mode` varchar(200) NOT NULL,
              `banner_heading` varchar(200) DEFAULT NULL,
              `background_color` varchar(11) DEFAULT NULL,
              `is_enabled_background_color` int(10) NOT NULL DEFAULT '1',
              `text_color` varchar(11) DEFAULT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_banner_data);
		
		$insert_table_banner_data = "	
			INSERT INTO `" . DB_PREFIX . "storeapp_banners` (`id`, `id_component`, `id_banner_type`, `countdown`, `product_id`, `category_id`, `redirect_activity`, `image_url`, `image_type`, `banner_design`, `image_content_mode`, `banner_heading`, `background_color`, `is_enabled_background_color`, `text_color`) VALUES
			(24, 39, 0, '2021-05-27 19:05:00', 0, 0, 'home', 'catalog/app_image/c4cc3441.png', NULL, 1, '1', NULL, '#6bb927', 1, '#ffffff'),
			(4, 10, 0, NULL, 0, 34, 'category', 'catalog/app_image/b1.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(5, 10, 0, NULL, 0, 0, 'home', 'catalog/app_image/b2.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(6, 10, 0, NULL, 0, 33, 'category', 'catalog/app_image/b3.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(7, 10, 0, NULL, 110, 0, 'product', 'catalog/app_image/b4.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(8, 11, 0, NULL, 0, 0, 'home', 'catalog/app_image/1d2fb017c29b082164073dd9507ef62c85fe6446_1619878259.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(9, 11, 0, NULL, 0, 0, 'home', 'catalog/app_image/3eb5b5ffe989a15e412ef803a18e3f900d04eb08_1620209558.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(10, 11, 0, NULL, 0, 0, 'home', 'catalog/app_image/7fa0a84d0ea88439227fc6b4f99d02ae510e80c3_1619794600.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(12, 11, 0, NULL, 0, 34, 'category', 'catalog/app_image/1d2fb017c29b082164073dd9507ef62c85fe6446_1619878259.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(17, 9, 0, NULL, 0, 0, 'home', 'catalog/app_image/b9.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(18, 9, 0, NULL, 0, 0, 'home', 'catalog/app_image/b9.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(14, 17, 0, NULL, 0, 0, 'home', 'catalog/app_image/b2.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(15, 17, 0, NULL, 0, 0, 'home', 'catalog/app_image/b7.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(16, 17, 0, NULL, 0, 34, 'category', 'catalog/app_image/b6.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(28, 43, 0, NULL, 0, 0, 'home', 'catalog/app_image/b9.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(124, 38, 0, '2021-12-21 09:12:00', 0, 0, 'home', 'catalog/app_image/special.png', NULL, 1, '1', NULL, '#ef394e	', 1, '#ffffff'),
			(30, 45, 0, NULL, 0, 0, 'home', 'catalog/app_image/b9.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(69, 70, 0, '2021-05-24 10:05:05', 0, 34, 'category', 'catalog/app_image/Arabic/ خاص۲.png', NULL, 1, '0', NULL, '#6BB926', 1, '#000'),
			(68, 68, 0, '2021-05-24 10:05:52', 0, 0, 'home', 'catalog/app_image/Arabic/ خاص.png', NULL, 1, '0', NULL, '#EF394E', 1, '#000'),
			(186, 138, 0, NULL, 0, 0, 'home', 'catalog/app_image/1d2fb017c29b082164073dd9507ef62c85fe6446_1619878259.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(223, 78, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/954954cc42317b32f079fab0b98af86b3a71e096_1607016116.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(98, 95, 0, NULL, 0, 34, 'category', 'catalog/app_image/1d2fb017c29b082164073dd9507ef62c85fe6446_1619878259.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(96, 93, 0, NULL, 0, 34, 'category', 'catalog/app_image/8efcf8d1dd4eb498a5b5ff4a6bfac3041fd30cce_1602932546.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(166, 129, 0, NULL, 121, 0, 'product', 'catalog/app_image/English/20210517043608_959.jpg.png', NULL, 3, '1  ', NULL, NULL, 1, NULL),
			(175, 57, 0, NULL, 121, 0, 'product', 'catalog/app_image/English/KurtaPalazzoSetsWeb-a45fb.jpg', NULL, 3, '0  ', NULL, NULL, 1, NULL),
			(129, 54, 0, '2021-12-30 07:12:00', 0, 20, 'category', 'catalog/app_image/special-offer2.png', NULL, 1, '1', NULL, '#EF394E	', 1, '#ffffff'),
			(44, 55, 0, NULL, 0, 0, 'home', 'catalog/app_image/baker-ted-baker-data.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(45, 55, 0, NULL, 0, 34, 'category', 'catalog/app_image/summer-bedding-data.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(165, 129, 0, NULL, 0, 20, 'category', 'catalog/app_image/English/20210430044654_228.jpg.png', NULL, 3, '1  ', NULL, NULL, 1, NULL),
			(109, 58, 0, '2021-05-25 17:05:12', 0, 0, 'home', 'catalog/app_image/special-offer.png', NULL, 1, '1', NULL, '#6BB926', 1, '#000'),
			(50, 60, 0, NULL, 0, 34, 'category', 'catalog/app_image/English/KurtaPalazzoSetsWeb-a45fb.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(51, 62, 0, NULL, 121, 0, 'product', 'catalog/app_image/English/BoysClothingKidsWeb-e20b4.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(210, 65, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/f80e1a870761766dcee5e2c4e5c15507a20c8dd2_1624695507.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(56, 66, 0, NULL, 0, 0, 'home', 'catalog/app_image/Arabic/e3e26578-f46a-4745-9cf0-1ab999747735_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(57, 66, 0, NULL, 0, 0, 'home', 'catalog/app_image/Arabic/fa51a54b-e723-418c-b137-7c81f440b023_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(58, 66, 0, NULL, 121, 0, 'product', 'catalog/app_image/Arabic/38e960c8-c1b0-4d6c-910e-dbd11056a80a_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(59, 69, 0, NULL, 104, 0, 'product', 'catalog/app_image/Arabic/fa51a54b-e723-418c-b137-7c81f440b023_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(60, 72, 0, NULL, 0, 34, 'category', 'catalog/app_image/Arabic/0d37b079-dbfd-4e04-8351-83cc745fc861_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(61, 74, 0, NULL, 111, 0, 'product', 'catalog/app_image/Arabic/e3e26578-f46a-4745-9cf0-1ab999747735_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(230, 77, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/954954cc42317b32f079fab0b98af86b3a71e096_1607016116.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(229, 77, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/91d40f91c1c0eefd70b719390da19a0dad12d795_1599385683.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(88, 84, 0, NULL, 0, 0, 'home', 'catalog/banner/arabic/ar_mb-mega-01.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(70, 79, 0, NULL, 0, 34, 'category', 'catalog/app_image/Arabic/38e960c8-c1b0-4d6c-910e-dbd11056a80a_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(71, 79, 0, NULL, 0, 34, 'category', 'catalog/app_image/Arabic/7dfe4f7c-a978-439b-8f30-2a9ed1082d57_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(72, 79, 0, NULL, 0, 34, 'category', 'catalog/app_image/Arabic/cfdc79f6-42b9-48ab-a1cc-51a51aed2a51_800x800.jpg', NULL, 1, '0', NULL, NULL, 1, NULL),
			(85, 81, 0, NULL, 99, 0, 'product', 'catalog/banner/arabic/ar_mb-mega-07.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(83, 81, 0, NULL, 0, 0, 'home', 'catalog/banner/arabic/ar_mb-mega-01.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(84, 81, 0, NULL, 0, 0, 'home', 'catalog/banner/arabic/ar_mb-mega-05.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(228, 83, 0, '2021-08-07 07:08:00', 0, 0, 'home', 'catalog/app_image/Arabic/ خاص.png', NULL, 1, '1', NULL, '#ff0000', 1, '#ffffff'),
			(86, 81, 0, NULL, 0, 0, 'home', 'catalog/banner/arabic/ar_mb-mega-09.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(89, 84, 0, NULL, 0, 0, 'home', 'catalog/banner/arabic/ar_mb-mega-05.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(219, 90, 0, NULL, 0, 0, 'home', 'catalog/app_image/persion/e88671911597cd3cf5a5e199a1ad448a3ad41ab6_1625645438.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(218, 90, 0, NULL, 0, 0, 'home', 'catalog/app_image/persion/82b4b56a4fbf0bdba5934d1fca6ec66b66e26065_1623412509.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(99, 95, 0, NULL, 110, 0, 'product', 'catalog/app_image/3eb5b5ffe989a15e412ef803a18e3f900d04eb08_1620209558.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(100, 95, 0, NULL, 0, 0, 'home', 'catalog/app_image/7fa0a84d0ea88439227fc6b4f99d02ae510e80c3_1619794600.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(101, 95, 0, NULL, 0, 34, 'category', 'catalog/app_image/3eb5b5ffe989a15e412ef803a18e3f900d04eb08_1620209558.jpg', NULL, 1, '1', NULL, NULL, 1, NULL),
			(103, 102, 0, '2021-05-25 10:05:73', 0, 0, 'home', 'catalog/app_image/special-offer.png', NULL, 1, '1', NULL, '#008f44', 1, '#b37100'),
			(207, 107, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210710024912_779.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(206, 107, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210710024532_605.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(199, 52, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/20210624023114_971.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(115, 10, 0, NULL, 0, 0, 'home', 'catalog/app_image/b5.jpg', NULL, 2, '1  ', NULL, NULL, 1, NULL),
			(128, 124, 0, '2021-10-28 09:10:00', 0, 26, 'category', 'catalog/app_image/c4cc3441.png', NULL, 1, '1', NULL, '#6bb927', 1, '#ffffff'),
			(201, 52, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/20210710024532_605.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(200, 52, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/20210709011127_421.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(153, 108, 0, '2021-07-10 09:07:00', 0, 0, 'home', 'catalog/app_image/special-offer2.png', NULL, 1, '1', NULL, '#fe9616', 1, '#ffffff'),
			(211, 65, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/ea1056e4d2cc9b21838ac905dff7da2e1ae5978e_1612288934.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(209, 65, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/91d40f91c1c0eefd70b719390da19a0dad12d795_1599385683.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(176, 57, 0, NULL, 0, 20, 'category', 'catalog/app_image/English/CottonKurtiWeb-9d800.jpg', NULL, 3, '0  ', NULL, NULL, 1, NULL),
			(217, 90, 0, NULL, 0, 0, 'home', 'catalog/app_image/persion/9dd90e770bf7328c0cc5c1a036363bce495d87cf_1625309012.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(194, 94, 0, '2021-07-30 16:07:00', 0, 0, 'home', 'catalog/app_image/special.png', NULL, 1, '1', NULL, '#ff0a3d', 1, '#ffffff'),
			(205, 107, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210709011127_421.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(196, 101, 0, '2021-11-30 16:11:00', 0, 0, 'home', 'catalog/app_image/special-offer2.png', NULL, 1, '1', NULL, '#ff0000', 1, '#ffffff'),
			(198, 141, 0, '2021-11-24 16:11:00', 0, 59, 'category', 'catalog/app_image/c4cc3441.png', NULL, 1, '1', NULL, '#6bb927', 1, '#ffffff'),
			(202, 52, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210710024912_779.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(203, 52, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210710025337_587.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(204, 107, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210624023114_971.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(208, 107, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/slides/20210710025337_587.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(212, 65, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/954954cc42317b32f079fab0b98af86b3a71e096_1607016116.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(215, 104, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/b67f4e67e500f696497e5bd5aa2a260aad5bbdc4_1625640285.jpg', NULL, 1, '0  ', NULL, NULL, 1, NULL),
			(216, 142, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/5630e17bd68f61c1dfb2b7fbb11187f72c514e22_1625857309.jpg', NULL, 1, '0  ', NULL, NULL, 1, NULL),
			(220, 90, 0, NULL, 0, 0, 'home', 'catalog/app_image/persion/b0312a51c39932a0a40456c70d4befdc4e183ca1_1622033546.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(221, 90, 0, NULL, 0, 0, 'home', 'catalog/app_image/persion/a2b6999f046ceca6ae8d91aa7b713697d3b91f23_1625663286.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(222, 146, 0, '2021-08-07 16:08:00', 0, 0, 'home', 'catalog/app_image/c4cc3441.png', NULL, 1, '1', NULL, '#00dcaf', 1, '#ffffff'),
			(224, 78, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/91d40f91c1c0eefd70b719390da19a0dad12d795_1599385683.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(225, 78, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/ea1056e4d2cc9b21838ac905dff7da2e1ae5978e_1612288934.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(226, 78, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/f80e1a870761766dcee5e2c4e5c15507a20c8dd2_1624695507.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(227, 150, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/5630e17bd68f61c1dfb2b7fbb11187f72c514e22_1625857309.jpg', NULL, 1, '0  ', NULL, NULL, 1, NULL),
			(231, 77, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/ea1056e4d2cc9b21838ac905dff7da2e1ae5978e_1612288934.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(232, 77, 0, NULL, 0, 0, 'home', 'catalog/app_image/English/banner-grid/f80e1a870761766dcee5e2c4e5c15507a20c8dd2_1624695507.jpg', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(233, 152, 0, '2021-08-07 08:08:00', 0, 0, 'home', 'catalog/app_image/Arabic/ خاص۲.png', NULL, 1, '1', NULL, '#66ffea', 1, '#ffffff'),
			(234, 81, 0, NULL, 0, 0, 'home', 'catalog/app_image/icons/download.png', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(236, 84, 0, NULL, 0, 0, 'home', 'catalog/app_image/icons/Graphicloads-100-Flat-New.png', NULL, 1, '1  ', NULL, NULL, 1, NULL),
			(239, 87, 0, NULL, 0, 0, 'home', 'catalog/app_image/Arabic/dxture-samples-1140x380.jpg', NULL, 1, '0  ', NULL, NULL, 1, NULL),
			(240, 155, 0, NULL, 0, 0, 'home', 'catalog/app_image/Arabic/e3e26578-f46a-4745-9cf0-1ab999747735_800x800.jpg', NULL, 1, '0  ', NULL, NULL, 1, NULL);";

		$this->db->query($insert_table_banner_data);
		  //Changes
        $create_table_component_heading = "
            CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_components_heading` (
              `heading_id` int(100) NOT NULL AUTO_INCREMENT,
              `id_layout` int(100) NOT NULL,
              `id_component` int(100) NOT NULL,
              `heading` varchar(1000) NOT NULL,
              `icon` varchar(1000) NOT NULL,
              PRIMARY KEY (`heading_id`)
            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_component_heading);
		
		
		$insert_table_component_heading = "INSERT INTO `" . DB_PREFIX . "storeapp_components_heading` (`heading_id`, `id_layout`, `id_component`, `heading`, `icon`) VALUES
			(16, 3, 138, 'سلام', '0'),
			(14, 3, 135, 'sssssssssss', '0'),
			(21, 3, 128, 'ssssssss', 'catalog/app/logo.png'),
			(20, 3, 140, 'dddddddddd', 'catalog/icons/new.png'),
			(22, 9, 103, 'desktop', 'catalog/icons/motor.png'),
			(27, 9, 143, 'new', 'catalog/app_image/icons/Graphicloads-100-Flat-New.png'),
			(29, 9, 105, 'featured', 'catalog/app_image/icons/depositphotos_139478932-stock-illustration-most-watched-sign-icon-most.png'),
			(26, 9, 145, 'tablet', 'catalog/icons/motor.png'),
			(74, 9, 100, 'bestseller', 'catalog/app_image/istockphoto-1167481470-612x612.png'),
			(30, 7, 59, 'Newest1', ''),
			(50, 12, 147, 'جدیدترین ها', 'catalog/app_image/icons/Graphicloads-100-Flat-New.png'),
			(54, 12, 96, 'رومیزی', 'catalog/app_image/icons/download.png'),
			(57, 12, 92, 'پرفروش ترین ها', 'catalog/app_image/istockphoto-1167481470-612x612.png'),
			(60, 12, 148, 'محصولات منتخب', 'catalog/app_image/3496370.png'),
			(48, 12, 151, 'تبلت', 'catalog/app_image/icons/icon.png'),
			(66, 11, 153, 'سطح المكتب', 'catalog/app_image/icons/download.png'),
			(72, 11, 87, '', ''),
			(68, 11, 156, 'أحدث', 'catalog/app_image/icons/Graphicloads-100-Flat-New.png'),
			(69, 11, 85, 'أفضل البائعين', 'catalog/app_image/istockphoto-1167481470-612x612.png'),
			(70, 11, 86, 'منتجات مختارة', 'catalog/app_image/3496370.png'),
			(71, 11, 89, 'لوح', 'catalog/app_image/icons/icon.png'),
			(73, 13, 116, 'eeeeeee', 'catalog/category/1.jpg');";
			
          $this->db->query($insert_table_component_heading);
   
		 $create_table_component_type = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_component_types` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `component_name` varchar(200) NOT NULL,
             PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_component_type);
        
        $count_component_types = $this->db->query("SELECT * FROM `" . DB_PREFIX . "storeapp_component_types`");
        if(!($count_component_types->num_rows)) {
            $insert_component_type = "INSERT INTO `" . DB_PREFIX . "storeapp_component_types` (`id`, `component_name`) VALUES
                (1, 'top_category'),
                (2, 'banner_square'),
                (3, 'banners_countdown'),
                (4, 'banners_grid'),
                (5, 'banner_horizontal_slider'),
                (6, 'products_square'),
                (7, 'products_horizontal'),
                (8, 'products_recent'),
                (9, 'products_grid');";
        
        $this->db->query($insert_component_type);
        }
		
		  $create_table_component_layout = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_layout_component` (
          `id_component` int(11) NOT NULL AUTO_INCREMENT,
          `id_layout` int(11) NOT NULL,
          `id_component_type` int(11) NOT NULL,
          `position` int(11) NOT NULL,
          PRIMARY KEY (`id_component`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_component_layout);
		
		
			  $insert_table_component_layout = "INSERT INTO `" . DB_PREFIX . "storeapp_layout_component` (`id_component`, `id_layout`, `id_component_type`, `position`) VALUES
			(148, 12, 7, 10),
			(153, 11, 6, 6),
			(138, 3, 4, 4),
			(151, 12, 9, 11),
			(143, 9, 6, 7),
			(131, 13, 4, 11),
			(128, 3, 9, 0),
			(140, 3, 6, 5),
			(135, 3, 7, 2),
			(80, 11, 1, 1),
			(79, 11, 5, 0),
			(78, 12, 4, 3),
			(52, 7, 5, 0),
			(53, 7, 1, 1),
			(141, 9, 3, 4),
			(57, 7, 4, 3),
			(142, 9, 4, 8),
			(59, 7, 6, 4),
			(60, 7, 4, 5),
			(61, 7, 6, 6),
			(62, 7, 4, 7),
			(63, 7, 7, 8),
			(64, 7, 9, 9),
			(65, 9, 4, 3),
			(66, 10, 5, 1),
			(67, 10, 1, 2),
			(68, 10, 3, 3),
			(69, 10, 4, 4),
			(70, 10, 3, 5),
			(71, 10, 6, 6),
			(72, 10, 4, 7),
			(73, 10, 6, 8),
			(74, 10, 4, 9),
			(75, 10, 7, 10),
			(76, 10, 9, 11),
			(77, 11, 4, 3),
			(83, 11, 3, 2),
			(156, 11, 6, 9),
			(85, 11, 7, 12),
			(86, 11, 7, 13),
			(87, 11, 4, 7),
			(89, 11, 9, 14),
			(90, 12, 5, 0),
			(91, 12, 1, 1),
			(92, 12, 7, 9),
			(93, 12, 4, 6),
			(94, 12, 3, 2),
			(147, 12, 6, 7),
			(96, 12, 6, 5),
			(150, 12, 4, 8),
			(101, 9, 3, 2),
			(99, 9, 1, 1),
			(100, 9, 7, 9),
			(145, 9, 9, 12),
			(103, 9, 6, 5),
			(104, 9, 4, 6),
			(105, 9, 7, 10),
			(146, 12, 3, 4),
			(107, 9, 5, 0),
			(152, 11, 3, 4),
			(112, 13, 1, 1),
			(155, 11, 4, 11),
			(113, 13, 5, 0),
			(114, 13, 3, 2),
			(115, 13, 6, 3),
			(116, 13, 7, 4),
			(117, 13, 3, 5),
			(118, 13, 2, 7),
			(119, 13, 2, 6),
			(157, 13, 2, 12);";
			$this->db->query($insert_table_component_layout);

		 $create_table_top_category = "
        CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "storeapp_top_category` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `id_component` int(11) NOT NULL,
          `id_category` varchar(200) NOT NULL,
          `image_url` longtext,
          `image_content_mode` varchar(200) DEFAULT NULL,
		  `category_in_home` int(11) NOT NULL,
          PRIMARY KEY (`id`)
        ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8  COLLATE=utf8_general_ci;";
        
        $this->db->query($create_table_top_category);     

				 $create_table_top_category = "INSERT INTO `" . DB_PREFIX . "storeapp_top_category` (`id`, `id_component`, `id_category`, `image_url`, `image_content_mode`, `category_in_home`) VALUES
			(127, 91, '60', 'catalog/category/top-category/topCat/en_IconeOpt01_SportsFitness (1).jpg', '1', 3),
			(35, 80, '33', 'catalog/category/top-category/topCat/en_IconeOpt01_Game.jpg', '0', 1),
			(34, 80, '59', 'catalog/category/top-category/topCat/en_IconeOpt01_HomeAppliances.jpg', '0', 1),
			(33, 80, '20', 'catalog/category/top-category/topCat/en_IconeOpt01_Fragrances.jpg', '0', 1),
			(32, 80, '34', 'catalog/category/top-category/topCat/en_IconeOpt01_Electronic01.jpg', '0', 1),
			(149, 53, '27', 'catalog/app_image/category2.png', '1', 1),
			(146, 53, '63', 'catalog/app_image/category4.png', '1', 1),
			(28, 67, '33', 'catalog/app_image/category.png', '0', 1),
			(29, 67, '34', 'catalog/app_image/category1.png', '0', 1),
			(30, 67, '196', 'catalog/app_image/category2.png', '0', 1),
			(31, 67, '229', 'catalog/app_image/category5.png', '0', 1),
			(123, 91, '34', 'catalog/category/top-category/topCat/en_IconeOpt01_Beauty.jpg', '1', 3),
			(124, 91, '33', 'catalog/category/top-category/topCat/en_IconeOpt01_Toys (1).jpg', '1', 3),
			(125, 91, '27', 'catalog/category/top-category/topCat/en_IconeOpt01_KitchenDinning.jpg', '1', 3),
			(126, 91, '26', 'catalog/category/top-category/topCat/en_IconeOpt01_Mobiles.jpg', '1', 3),
			(131, 99, '60', 'catalog/category/top-category/topCat/en_IconeOpt01_Game.jpg', '1', 1),
			(130, 99, '20', 'catalog/category/top-category/topCat/en_IconeOpt01_KitchenDinning.jpg', '1', 1),
			(128, 99, '34', 'catalog/category/top-category/topCat/en_IconeOpt01_Beauty.jpg', '1', 1),
			(129, 99, '33', 'catalog/category/top-category/topCat/en_IconeOpt01_Fragrances.jpg', '1', 1),
			(148, 53, '20', 'catalog/app_image/category3.png', '1', 1),
			(147, 53, '46', 'catalog/app_image/category3.png', '1', 1),
			(145, 53, '272', 'catalog/app_image/category5.png', '1', 1),
			(144, 53, '386', 'catalog/app_image/icons/category/smart-watch-body-temperature-5f52286025668.jpg', '1', 1),
			(150, 53, '33', 'catalog/app_image/category5.png', '1', 1),
			(151, 53, '64', 'catalog/app_image/category.png', '1', 1),
			(158, 112, '55', 'catalog/category/13.jpg', '1', 4),
			(157, 112, '34', 'catalog/category/13.jpg', '1', 4),
			(156, 112, '43', 'catalog/category/13.jpg', '1', 4),
			(159, 112, '38', 'catalog/app_image/English/20210621064825_683.jpg', '1', 4);";
					
	}
	
	

		public function show_category() {
		$this->load->model('tool/image');
		if (is_file(DIR_IMAGE . 'catalog/category'.$this->request->get['category'].'.png')) {
			$this->response->setOutput($this->model_tool_image->resize('catalog/category'.$this->request->get['category'].'.png',150,340));
		} else {
			$this->response->setOutput($server . 'image/no_image.png');
		}
	}
	
	
		public function show_product() {
		$this->load->model('tool/image');
		if (is_file(DIR_IMAGE . 'catalog/product'.$this->request->get['product'].'.png')) {
			$this->response->setOutput($this->model_tool_image->resize('catalog/product'.$this->request->get['product'].'.png',200,100));
		} else {
			$this->response->setOutput(DIR_IMAGE .'image/no_image.png');
		}
	  }
	  
	  
	  	
	  	public function show_header() {
		$this->load->model('tool/image');
		if (is_file(DIR_IMAGE . 'catalog/app_image/screen/header'.$this->request->get['design'].'.png')) {
			$this->response->setOutput($this->model_tool_image->resize('catalog/app_image/screen/header'.$this->request->get['design'].'.png',100,200));
		} else {
			$this->response->setOutput(DIR_IMAGE .'image/no_image.png');
		}
	  }
	  public function show_filter() {
		$this->load->model('tool/image');

		if (is_file(DIR_IMAGE . 'catalog/app_image/screen/filter'.$this->request->get['design'].'.jpg')) {
			$this->response->setOutput($this->model_tool_image->resize('catalog/app_image/screen/filter'.$this->request->get['design'].'.jpg',100,200));
		} else {
			$this->response->setOutput(DIR_IMAGE .'image/no_image.png');
		}
	  }
	
	
	public function getShippingMethods() {
        
            $shipping_methods = glob(DIR_APPLICATION . 'controller/extension/shipping/*.php');
       
        $result = array();

        foreach ($shipping_methods as $shipping){
            $shipping = basename($shipping, '.php');
           
            $this->load->language('extension/shipping/' . $shipping);
            
            if(VERSION >= '3.0.0.0'){
                $shipping_status = $this->config->get('shipping_'.$shipping.'_status');
            }else{
                $shipping_status = $this->config->get($shipping.'_status');
            }
            if(isset($shipping_status)){
                $result[] = array(
                    'code' => $shipping,
                    'title' => $this->language->get('heading_title')
                );
            }
        }
        return $result;
    }

    public function getPaymentMethods(){
        
        $payment_methods = glob(DIR_APPLICATION . 'controller/extension/payment/*.php');
        $result = array();
        foreach ($payment_methods as $payment){
            $payment = basename($payment, '.php');
           
                $this->load->language('extension/payment/' . $payment);
            
            if(VERSION>='3.0.0.0'){
                $payment_status = $this->config->get('payment_'.$payment.'_status');
            }else{
                $payment_status = $this->config->get($payment.'_status');
            }

            if(isset($payment_status)){
                $result[] = array(
                    'code' => $payment,
                    'title' => $this->language->get('heading_title')
                );
            }
        }
        return $result;
    }
    
     public function addSort(){
		$json = array();
			
	$sort=$this->request->get['sort'];
	$layout_id=$this->request->get['layout_id'];
	 $this->load->model('extension/new/storeapp');
    $this->model_extension_new_storeapp->updateLayoutSort($sort,$layout_id);
	
	
	$json['success']='ok';
	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
     public function EditLayoutname(){
		$json = array();
			
	$name=$this->request->get['name'];
	$layout_id=$this->request->get['layout_id'];
	 $this->load->model('extension/new/storeapp');
    $this->model_extension_extension_new_storeapp->updateLayoutName($name,$layout_id);
	
	
	$json['success']='ok';
	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	
	////list iphone //// ///////////////////////////////////////////////////////////////////////////////
	
	 public function list_phone() {  
	    $this->load->language('extension/new/list_phone');
		$this->document->setTitle($this->language->get('heading_title'));
 	    $data['breadcrumbs'] = array();
        $data['delete'] = $this->url->link('extension/new/list_phone/delete', 'user_token=' . $this->session->data['user_token'] , true);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true)
		);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

	    $this->getListListPhone();
	}
	
	public function getListListPhone() { 
		 $this->load->language('extension/new/list_phone');
		$url = '';		
		
		$this->load->model('extension/new/token');
		$this->load->model('customer/customer');
		$list_phone_total = $this->model_extension_new_token->getTokensTotal();
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}
		
		$data = array(
			'start'    => ($page - 1) * 20,
			'limit'    => 20
		);
		
		$results = $this->model_extension_new_token->getTokens($data);
		
		if($results){
    		foreach ($results as $result) {
    		    if($result['customer_id'] <> '0'){
    		        $customer = $this->model_customer_customer->getCustomer($result['customer_id']);
    		        $customer_name = $customer['firstname'] .' '. $customer['lastname'];
    		    }else{
    		        $customer_name  = $this->language->get('text_Guest');
    		    }
    		    if($result['status'] <> '0'){
    		        $status_user = $this->language->get('text_active');
    		    }else{
    		        $status_user  = $this->language->get('text_inactive');
    		    }
    		  //  print_r( $result );
    		  //echo $result['token_device'];
    			 $data['list_phones'][] = array(
    				'token_device'         => $result['token_device'],
    				'token'                => $result['token'],
    				'version'              => $result['version'],
    				'os'                   => $result['os'],
    				'platform'             => $result['platform'],
    				'model'                => $result['model'],
    				'manufacturer'         => $result['manufacturer'],
    				'versionApp'           => $result['versionApp'],
    				'customer_id'          => $result['customer_id'],
    				'date_added'           => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				    'date_modified'        => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_modified'])) : date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
    				'customer_name'        => $customer_name,
    				'status_user'          => $status_user,
    				'selected'             => isset($this->request->post['selected']) && in_array($result['list_phone_id'], $this->request->post['selected']),
    			);
    		}
		} else {
		 $data['list_phones'] = "";	
		}
		  $data['user_token'] = $this->session->data['user_token'];
		$data['heading_title']              = $this->language->get('heading_title');
		$data['text_list_phone']            = $this->language->get('text_list_phone');
		$data['text_no_results']            = $this->language->get('text_no_results');
    	$data['column_order_no']            = $this->language->get('column_order_no');
		$data['column_name']                = $this->language->get('column_name');
		$data['column_mail']                = $this->language->get('column_mail');
		$data['column_email']               = $this->language->get('column_email');
		$data['column_telephone']           = $this->language->get('column_telephone');
        $data['column_bank']                = $this->language->get('column_bank');
		$data['column_amount']              = $this->language->get('column_amount');
		$data['column_list_phoneid']        = $this->language->get('column_list_phoneid');
		$data['column_payment']             = $this->language->get('column_payment');
		$data['column_no_list_phone']       = $this->language->get('column_no_list_phone');
		$data['column_datap']               = $this->language->get('column_datap');
		$data['column_msg']                 = $this->language->get('column_msg');
		$data['column_massage']             = $this->language->get('column_massage');
		$data['column_status']              = $this->language->get('column_status');
		$data['column_date_added']          = $this->language->get('column_date_added');
       
	    $data['button_delete']              = $this->language->get('button_delete');
	    $data['entry_list_phone_complate']  = $this->language->get('entry_list_phone_complate');
	    $data['entry_list_phone_error']     = $this->language->get('entry_list_phone_error');
	    $data['entry_list_phone_barrasi']   = $this->language->get('entry_list_phone_barrasi');
		$data['user_token']                 = $this->session->data['user_token'];
		$data['total_ios']                  = $this->model_extension_new_token->getPlatform('ios');
		$data['total_android']              = $this->model_extension_new_token->getPlatform('android');
		$data['total_device']               = $this->model_extension_new_token->getTokensTotal();
		$data['total_device_enable']               = $this->model_extension_new_token->getTokensTotalEnable();
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
		
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}				
	
		
		$pagination         = new Pagination();
		$pagination->total  = $list_phone_total;
		$pagination->page   = $page;
		$pagination->limit  = 20;
		
		$pagination->url = $this->url->link('extension/module/storeapp/list_phone', 'user_token=' . $this->session->data['user_token'] . $url . '&page={page}', 'SSL');
			
		$data['pagination']      = $pagination->render();		
        $data['results']         = sprintf($this->language->get('text_pagination'), ($list_phone_total) ? (($page - 1) * 20) + 1 : 0, ((($page - 1) * 20) > ($list_phone_total - 20)) ? $list_phone_total : ((($page - 1) * 20) + 20), $list_phone_total, ceil($list_phone_total / 20));
		$data['header']          = $this->load->controller('common/header');
		$data['column_left']     = $this->load->controller('common/column_left');
		$data['footer']          = $this->load->controller('common/footer');
        $data['delete']          = $this->url->link('extension/module/storeapp/deleteListPhone', 'user_token=' . $this->session->data['user_token'] . $url,true);
        
		$this->response->setOutput($this->load->view('extension/new/list_phone', $data));
	}
		
  		public function deleteListPhone() {
		$this->load->language('extension/new/token');
 $this->load->language('extension/new/list_phone');
		$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('extension/new/token');

    	if (isset($this->request->post['selected']) && ($this->validateDelete())) {
    	   
			foreach ($this->request->post['selected'] as $tokenid) {
			    
				$this->model_extension_new_token->deleteToken($tokenid);
			}
       
			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

											
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

		//	$this->response->redirect($this->url->link('extension/new/list_phone', 'user_token=' . $this->session->data['user_token'] . $url, true));
    	}

    	$this->getListListPhone();
  	}
	private function validateDelete() {
    	if (!$this->user->hasPermission('modify', 'extension/module/storeapp')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
  	
  	
  		public function sendListphone() {
  		    $this->load->language('extension/new/list_phone'); 
    	if (isset($this->request->get['token_device'])){
			$data['token_device'] = $this->request->get['token_device'];
		}else{
			$data['token_device'] = '';
		}
		  $data['user_token'] = $this->session->data['user_token'];
		    $json=array();
			$json['output'] = $this->load->view('extension/new/send_notification', $data);		
	     	$this->response->setOutput(json_encode($json));	
  	}
  	
  		public function send_notification() {
  		     $this->load->language('extension/new/list_phone');
  		   	$json = array();
	       $tokens=array();
	
	$notification_title=$this->request->post['title'];
	$notification_text=$this->request->post['text'];
	if (isset($this->request->post['token_device'])){
			$token_device = $this->request->post['token_device'];
		}else{
			$token_device = '';
		}
		
		array_push($tokens,$token_device);
		
		require_once(DIR_SYSTEM . 'library/fcm/fcm.php');
		$this->fcm = new Fcm();
		$result=$this->fcm->send_notification($notification_text,$notification_title,$tokens,$this->config->get('storeapp_serverKey'),$this->config->get('storeapp_https'));
		
		if($result!='1'){
		$this->load->model('extension/new/token');
	    $this->model_extension_new_token->UpdateStatus($token_device);
		}
    	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json)); 
    	
		
  	}
  	
 	public function checkenable() {
  		  
	       
	
    	$notification_title='test';
    	$notification_text='test';
    	  $url = "https://fcm.googleapis.com/fcm/send";
              $serverKey= $this->config->get('storeapp_serverKey');
    	$sql = "SELECT token FROM `" . DB_PREFIX . "token_device` where status=1 ";
	   $query = $this->db->query($sql);
		$results=$query->rows;
		foreach($results as $result){
		     	$json = array();
		     	$tokens=array();
    		
    		array_push($tokens,$result['token']);
              $data = array(
                "registration_ids" => $tokens,            // for multiple devices 
                "notification2" => array( 
                    "title" => $notification_title, 
                    "body" =>$notification_text,
                    "message"=>$notification_text
                   
                ),
                
            ); 
        //print_r($data);
            $json = json_encode($data); 
         
            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'Authorization: key='. $serverKey;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST,"POST");
            curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
            curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
            $response = curl_exec($ch);
            
             $cur_message=json_decode($response,true);
            
           $success= $cur_message['success'];
            //print_r($result);
            if ($response === FALSE) {
            die('FCM Send Error: ' . curl_error($ch));
            }
            curl_close($ch);
            		if($success!='1'){
            		$this->load->model('extension/new/token');
            	    $this->model_extension_new_token->UpdateStatus($result['token']);
            		}
		}
    	$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json)); 
    	
		
  	}
	
	
	/////notification /////////////////////////////////////////////////////////////////////////////////////////////////
		public function notification() {  
		$this->load->language('extension/new/notification');

		$this->document->setTitle($this->language->get('heading_title'));
 	      $data['breadcrumbs'] = array();
              $data['delete'] = $this->url->link('extension/modue/storapp/delete', 'user_token=' . $this->session->data['user_token'] , true);
		
		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'user_token=' . $this->session->data['user_token'], true)
		);
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

	    $this->getListNotification();
	}
	public function getListNotification() { 
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
						
	
								
		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}

   		
		
		
		$this->load->model('extension/new/notification');
	
		$notification_total =$this->model_extension_new_notification->getnotificationsTotal();
		
		
		
			if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}
		
			$data = array(
			'start'                  => ($page - 1) * 20,
			'limit'                  => 20
		);
		$results = $this->model_extension_new_notification->getnotifications($data);
		if($results){
		foreach ($results as $result) {
			 $data['notifications'][] = array(
				'title'     => $result['title'],
				'message'   => $result['message'],
				'notification_id' => $result['notification_id'],
					'date_added'    => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
				'selected'      => isset($this->request->post['selected']) && in_array($result['notification_id'], $this->request->post['selected']),
			);
		}
		} else {
		 $data['notifications']="";	
		}
		 $data['heading_title'] = $this->language->get('heading_title');
		 $data['text_notification'] = $this->language->get('text_notification');
		 $data['text_no_results'] = $this->language->get('text_no_results');
    	 $data['column_order_no'] = $this->language->get('column_order_no');
		 $data['column_name'] = $this->language->get('column_name');
		 $data['column_mail'] = $this->language->get('column_mail');
		 $data['column_email'] = $this->language->get('column_email');
		 $data['column_telephone'] = $this->language->get('column_telephone');
         $data['column_bank'] = $this->language->get('column_bank');
		 $data['column_amount'] = $this->language->get('column_amount');
		 $data['column_notificationid'] = $this->language->get('column_notificationid');
		 $data['column_payment'] = $this->language->get('column_payment');
		 $data['column_no_notification'] = $this->language->get('column_no_notification');
		 $data['column_datap'] = $this->language->get('column_datap');
		 $data['column_msg'] = $this->language->get('column_msg');
		 $data['column_massage'] = $this->language->get('column_massage');
		 $data['column_status'] = $this->language->get('column_status');
		 $data['column_date_added'] = $this->language->get('column_date_added');
       
	    $data['button_delete'] = $this->language->get('button_delete');
	    $data['entry_notification_complate'] = $this->language->get('entry_notification_complate');
	    $data['entry_notification_error'] = $this->language->get('entry_notification_error');
	    $data['entry_notification_barrasi'] = $this->language->get('entry_notification_barrasi');
		
		$data['user_token'] = $this->session->data['user_token'];

	

					
	
		
		$pagination = new Pagination();
		$pagination->total = $notification_total;
		$pagination->page = $page;
		$pagination->limit = $this->config->get('config_limit_admin');
	    $pagination->url = $this->url->link('extension/new/notification', 'user_token=' . $this->session->data['user_token'] . '&page={page}', 'SSL');
			
		 $data['pagination'] = $pagination->render();		
    	$data['results'] = sprintf($this->language->get('text_pagination'), ($notification_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($notification_total - $this->config->get('config_limit_admin'))) ? $notification_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $notification_total, ceil($notification_total / $this->config->get('config_limit_admin')));

		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
        $data['delete'] = $this->url->link('extension/module/storeapp/deleteNotification', 'user_token=' . $this->session->data['user_token'] ,true);
		$this->response->setOutput($this->load->view('extension/new/notification', $data));
	}
		
  	public function deleteNotification() {
		$this->load->language('extension/new/notification');

		$this->document->setTitle($this->language->get('heading_title'));

			$this->load->model('extension/new/notification');

    	if (isset($this->request->post['selected']) && ($this->validateDeleteNotification())) {
			foreach ($this->request->post['selected'] as $notificationid) {
				$this->model_extension_new_notification->deletenotification($notificationid);
			}

			$this->session->data['success'] = $this->language->get('text_success');

			$url = '';

											
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}

			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}

			$this->response->redirect($this->url->link('extension/module/storeapp', 'user_token=' . $this->session->data['user_token'] . $url, true));
    	}

    	$this->getListNotification();
  	}
	   	private function validateDeleteNotification() {
    	if (!$this->user->hasPermission('modify', 'extension/module/storeapp')) {
			$this->error['warning'] = $this->language->get('error_permission');
    	}

		if (!$this->error) {
	  		return true;
		} else {
	  		return false;
		}
  	}
	

}
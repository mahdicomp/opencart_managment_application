<?php
class ModelExtensionNewStoreapp extends Model {	
	public function getHomePageLayouts(){
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'storeapp_layouts');
            if ($query->num_rows) {
                return $query->rows;
            }else{
                return false;
            }
        }
		
		
		
		 public function getComponents($layout_id){
         
            $query = $this->db->query('SELECT * FROM '. DB_PREFIX . 'storeapp_layout_component where id_layout = '.$layout_id.' ORDER BY position ASC');
            return $query->rows;
            
        }
		
        
        public function getLastPosition($layout_id) {
            $query = $this->db->query('SELECT MAX(position) as max FROM ' . DB_PREFIX . 'storeapp_layout_component WHERE id_layout = '.$layout_id.'');
            if ($query->num_rows > 0) {
                return $query->row['max']+1;
            }else{
                return 0;
            }
        }
        
        public function updateLayoutPosition($pos,$id_layout,$id_component) {
            $this->db->query('UPDATE ' . DB_PREFIX . 'storeapp_layout_component SET position="'.$pos.'" WHERE id_layout = "'.$id_layout.'" AND id_component = "'.$id_component.'"');
        }
        
        public function updateLayoutSort($sort,$id_layout) {
            $this->db->query('UPDATE ' . DB_PREFIX . 'storeapp_layouts SET layout_sort="'.(int)$sort.'" WHERE id_layout = "'.$id_layout.'"');
        }
        
        public function updateLayoutName($name,$id_layout) {
            $this->db->query('UPDATE ' . DB_PREFIX . 'storeapp_layouts SET layout_name="'.$name.'" WHERE id_layout = "'.$id_layout.'"');
        }
	    
		   public function getComponentIdByType($type) {
            $query = $this->db->query('SELECT id  FROM ' . DB_PREFIX . 'storeapp_component_types WHERE component_name="'.$type.'"');
            if ($query->num_rows > 0) {
                return $query->row['id'];
            }else{
                return 1;
            }
        }
		
	public function getComponentHeading($layout_id,$component_id){
            $query = $this->db->query('SELECT heading,icon FROM ' . DB_PREFIX .'storeapp_components_heading WHERE id_layout = '.$layout_id.' and id_component = '.$component_id.'');
            if ($query->num_rows) {
                return $query->rows;
            }else{
                return false;
            }
        }
		
		
		  public function getTopCategoryData($component_id) {
            $query = $this->db->query('SELECT *  FROM ' . DB_PREFIX . 'storeapp_top_category WHERE id_component="'.$component_id.'" ORDER BY id ASC');
            if ($query->num_rows > 0) {
                return $query->rows;
            }else{
                return 1;
            }
        }
		
        public function getComponentTypeByID($component_type_id) {
            $query = $this->db->query('SELECT component_name  FROM ' . DB_PREFIX . 'storeapp_component_types WHERE id='.$component_type_id.'');
            if ($query->num_rows > 0) {
                return $query->row['component_name'];
            }else{
                return 1;
            }
        }
		
        
        public function saveComponent($data){
            $position = $this->getLastPosition($data['id_layout']);
            $component_type_id = $this->getComponentIdByType($data['component_type']);
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_layout_component (id_layout,id_component_type,position) VALUES ("'.$data['id_layout'].'","'.$component_type_id.'","'.$position.'")');
            return $this->db->getLastId();
            
        }
		
		     public function saveBannerSquareData($data){
            $product_id = 0;
            $category_id = 0;
            if($data['redirect_activity']==2) {
                $product_id =  $data['link_to'];
            } else if($data['redirect_activity']==1) {
                $category_id = $data['link_to'];
            }
            if(isset($data['banner_design']))
            {
                $banner_design=$data['banner_design'];
            }else {
                $banner_design='1';
            }
            $redirect_id = $data['redirect_activity'];
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_banners (id_component,id_banner_type,product_id,category_id,redirect_activity,image_url,banner_design,image_content_mode) VALUES ("'.$data['id_component'].'",0,"'.$product_id.'","'.$category_id.'","'.$redirect_id.'","'.$data['image_url'].'","'.$banner_design.'","'.$data['image_content_mode'].'")');
            return $this->db->getLastId();
        }
		
		  public function saveHtmlData($data){
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_html (id_component,id_banner_type,product_id,category_id,redirect_activity,image_url,image_content_mode) VALUES ("'.$data['id_component'].'",0,"'.$product_id.'","'.$category_id.'","'.$redirect_id.'","'.$data['image_url'].'","'.$data['image_content_mode'].'")');
           
            return $this->db->getLastId();
        }
        
        public function saveBannerCountdownData($data){
            $product_id = 0;
            $category_id = 0;
            if($data['redirect_activity']==2) {
                $product_id =  $data['link_to'];
            } else if($data['redirect_activity']==1) {
                $category_id = $data['link_to'];
            }
            $redirect_id = $data['redirect_activity'];
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_banners (id_component,id_banner_type,countdown,product_id,category_id,redirect_activity,image_url,image_content_mode,background_color,text_color,is_enabled_background_color) VALUES ("'.$data['id_component'].'",0,"'.$data['bc_countdown_validity'].'","'.$product_id.'","'.$category_id.'","'.$redirect_id.'","'.$data['image_url'].'","'.$data['image_content_mode'].'","'.$data['bc_background_color'].'","'.$data['bc_text_color'].'","'.$data['bc_back_color_status'].'")');
            return $this->db->getLastId();
        }
		
		 public function saveProductData($data){
            if(!isset($data['category_id'])) {
                $data['category_id'] = 0;
            }
            if(!isset($data['product_list'])){
                $data['product_list']="";
            }
            $this->db->query('DELETE FROM '. DB_PREFIX . 'storeapp_product_data WHERE id_component = "'.$data['id_component'].'" ');
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_product_data (id_component,product_type,category_products,custom_products,image_content_mode,number_of_products,id_category) VALUES ("'.$data['id_component'].'","'.$data['product_type'].'","'.$data['category_products'].'","'.$data['product_list'].'","'.$data['image_content_mode'].'","'.$data['number_of_product'].'","'.$data['category_id'].'")');
        }
        
        public function saveTopCategoryData($data){
            
            $this->db->query('DELETE FROM '. DB_PREFIX . 'storeapp_top_category WHERE id_component = "'.$data['id_component'].'" ');
            
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_1'].'","'.$data['image_1'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_2'].'","'.$data['image_2'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_3'].'","'.$data['image_3'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_4'].'","'.$data['image_4'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            if(isset($data['image_5'])) {
                $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_5'].'","'.$data['image_5'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            }
            if(isset($data['image_6'])) {
                $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_6'].'","'.$data['image_6'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            }
            if(isset($data['image_7'])) {
                $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_7'].'","'.$data['image_7'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            }
            if(isset($data['image_8'])) {
                $this->db->query('INSERT INTO '. DB_PREFIX . 'storeapp_top_category (id_component,id_category,image_url,image_content_mode,category_in_home) VALUES ("'.$data['id_component'].'","'.$data['id_category_8'].'","'.$data['image_8'].'","'.$data['image_content_mode'].'","'.$data['category_in_home'].'")');
            }
            return true;
        }
        
		
		 public function getHtmlByComponent($layout_id,$component_id) {
            
        $query = $this->db->query('SELECT sh.* FROM '.DB_PREFIX.'storeapp_layout_component slc,'.DB_PREFIX.'storeapp_html sh,'.DB_PREFIX.'storeapp_component_types sct  WHERE sct.id = slc.id_component_type and slc.id_component = sh.id_component AND slc.id_layout ="'.$layout_id.'" and sh.id_component ="'.$component_id.'" AND sct.component_name = "html"');
            if ($query->num_rows > 0) {
                return $query->rows;
            }
        }
		  
        public function getBannerByComponent($layout_id,$component_id) {
            
        $query = $this->db->query('SELECT sb.* FROM '.DB_PREFIX.'storeapp_layout_component slc,'.DB_PREFIX.'storeapp_banners sb,'.DB_PREFIX.'storeapp_component_types sct  WHERE sct.id = slc.id_component_type and slc.id_component = sb.id_component AND slc.id_layout ="'.$layout_id.'" and sb.id_component ="'.$component_id.'" AND (sct.component_name = "banner_square" OR sct.component_name = "banner_horizontal_slider" OR sct.component_name = "banners_grid" OR sct.component_name = "banners_countdown")');
            if ($query->num_rows > 0) {
                return $query->rows;
            }
        }
        public function getProductsByComponent($layout_id,$component_id) {
            $query = $this->db->query('SELECT * FROM ' . DB_PREFIX . 'storeapp_layout_component slc,' . DB_PREFIX . 'storeapp_product_data sp,' . DB_PREFIX . 'storeapp_component_types sct  WHERE sct.id = slc.id_component_type and slc.id_component = sp.id_component  and (sct.component_name = "products_square" OR sct.component_name = "products_horizontal" OR sct.component_name = "products_recent" OR sct.component_name = "products_grid") AND slc.id_layout ="'.$layout_id.'" and sp.id_component ="'.$component_id.'" ');
            if ($query->num_rows > 0) {
                return $query->rows;
            }
        }
        
    
       
        
    
        public function getBestSellerProducts($limit) {
		$product_data = $this->cache->get('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit);
        
		if (!$product_data) {
			$product_data = array();
        
			$query = $this->db->query("SELECT op.product_id, SUM(op.quantity) AS total FROM " . DB_PREFIX . "order_product op LEFT JOIN `" . DB_PREFIX . "order` o ON (op.order_id = o.order_id) LEFT JOIN `" . DB_PREFIX . "product` p ON (op.product_id = p.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE o.order_status_id > '0' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "' GROUP BY op.product_id ORDER BY total DESC LIMIT " . (int)$limit);
                        $this->load->model('catalog/product');
			foreach ($query->rows as $result) {
				$product_data[$result['product_id']] = $this->model_catalog_product->getProduct($result['product_id']);
}

			$this->cache->set('product.bestseller.' . (int)$this->config->get('config_language_id') . '.' . (int)$this->config->get('config_store_id') . '.' . $this->config->get('config_customer_group_id') . '.' . (int)$limit, $product_data);
		}

		return $product_data;
	}
		
	
}
?>
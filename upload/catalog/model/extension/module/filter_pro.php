<?php
class ModelExtensionModuleFilterPro extends Model {
	
	public function getProduct($product_id) {
		$query = $this->db->query("SELECT DISTINCT *, pd.name AS name, p.image, m.name AS manufacturer, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special, (SELECT points FROM " . DB_PREFIX . "product_reward pr WHERE pr.product_id = p.product_id AND pr.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "') AS reward, (SELECT ss.name FROM " . DB_PREFIX . "stock_status ss WHERE ss.stock_status_id = p.stock_status_id AND ss.language_id = '" . (int)$this->config->get('config_language_id') . "') AS stock_status, (SELECT wcd.unit FROM " . DB_PREFIX . "weight_class_description wcd WHERE p.weight_class_id = wcd.weight_class_id AND wcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS weight_class, (SELECT lcd.unit FROM " . DB_PREFIX . "length_class_description lcd WHERE p.length_class_id = lcd.length_class_id AND lcd.language_id = '" . (int)$this->config->get('config_language_id') . "') AS length_class, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT COUNT(*) AS total FROM " . DB_PREFIX . "review r2 WHERE r2.product_id = p.product_id AND r2.status = '1' GROUP BY r2.product_id) AS reviews, p.sort_order FROM " . DB_PREFIX . "product p LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) LEFT JOIN " . DB_PREFIX . "manufacturer m ON (p.manufacturer_id = m.manufacturer_id) WHERE p.product_id = '" . (int)$product_id . "' AND pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'");

		if ($query->num_rows) {
			$product_data_array = array(
				'product_id'       => $query->row['product_id'],
				'name'             => $query->row['name'],
				'description'      => $query->row['description'],
				'meta_title'       => $query->row['meta_title'],
				'meta_description' => $query->row['meta_description'],
				'meta_keyword'     => $query->row['meta_keyword'],
				'tag'              => $query->row['tag'],
				'model'            => $query->row['model'],
				'sku'              => $query->row['sku'],
				'upc'              => $query->row['upc'],
				'ean'              => $query->row['ean'],
				'jan'              => $query->row['jan'],
				'isbn'             => $query->row['isbn'],
				'mpn'              => $query->row['mpn'],
				'location'         => $query->row['location'],
				'quantity'         => $query->row['quantity'],
				'stock_status'     => $query->row['stock_status'],
				'image'            => $query->row['image'],
				'manufacturer_id'  => $query->row['manufacturer_id'],
				'manufacturer'     => $query->row['manufacturer'],
				'price'            => ($query->row['discount'] ? $query->row['discount'] : $query->row['price']),
				'special'          => $query->row['special'],
				'reward'           => $query->row['reward'],
				'points'           => $query->row['points'],
				'tax_class_id'     => $query->row['tax_class_id'],
				'date_available'   => $query->row['date_available'],
				'weight'           => $query->row['weight'],
				'weight_class_id'  => $query->row['weight_class_id'],
				'length'           => $query->row['length'],
				'width'            => $query->row['width'],
				'height'           => $query->row['height'],
				'length_class_id'  => $query->row['length_class_id'],
				'subtract'         => $query->row['subtract'],
				'rating'           => round($query->row['rating']),
				'reviews'          => $query->row['reviews'] ? $query->row['reviews'] : 0,
				'minimum'          => $query->row['minimum'],
				'sort_order'       => $query->row['sort_order'],
				'status'           => $query->row['status'],
				'date_added'       => $query->row['date_added'],
				'date_modified'    => $query->row['date_modified'],
				'viewed'           => $query->row['viewed']
			);	
			return $product_data_array;
		} else {
			return false;
		}
	}

	public function getProducts($data = array()) {
		$sql = "SELECT p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
         
		// if(!empty($data['filter_options'])){
		//    $sql.= " LEFT JOIN `".DB_PREFIX."product_option_value` pov ON (pov.product_id = p.product_id) ";
		//}
		
		
        //OPTIONS JOIN BEGIN
        if ($data['filter_options']) {
			 $filtersoptions = array();
			$filtersoptions = explode(',', $data['filter_options']);
            foreach ($filtersoptions as $key => $option_group) {
                $sql .= " LEFT JOIN " . DB_PREFIX . "product_option_value pov" . $key . " ON (p.product_id = pov" . $key . ".product_id)";
            }
        }
        //OPTIONS JOIN END
		
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		/*OPTIONS*/
		if(!empty($data['filter_options'])){
		    
		    $filtersoptions = array();

				$filtersoptions = explode(',', $data['filter_options']);
				foreach ($filtersoptions as $key => $option_value_id) {
                $option_ids = [];
               
                    if ($option_value_id) {
                        $option_ids[] = (int)$option_value_id;
                    }
                

                if ($option_ids) {
                    $sql .= " AND pov" . $key . ".option_value_id ='" . $option_value_id . "'";
                }
            }
				
			
		}
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}
		
		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}

		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		if (!empty($data['filter_quantity'])) {
			$sql .= " AND p.quantity > 0";
		}
		/* Price range */
        if  (!empty($data['filter_price'])) {
            $min_price = $data['filter_price']['min_price'];
            $max_price = $data['filter_price']['max_price'];
            $sql_filter_special = "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)";
            $sql_sl_discount = "(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)";
            $sql .= " AND (CASE WHEN " . $sql_filter_special . " IS NOT NULL THEN " . $sql_filter_special . " WHEN " . $sql_sl_discount . " IS NOT NULL THEN ". $sql_sl_discount . " ELSE p.price END) >='". $min_price ."'" ;
            $sql .= " AND (CASE WHEN " . $sql_filter_special . " IS NOT NULL THEN " . $sql_filter_special . " WHEN " . $sql_sl_discount . " IS NOT NULL THEN ". $sql_sl_discount . " ELSE p.price END) <='". $max_price ."'";
        }
        /* End */
        if (!empty($data['filter_manufacturers']) && is_array($data['filter_manufacturers']) ) {
            $sql .= " AND p.manufacturer_id IN (" . implode(",", $data['filter_manufacturers']) . ")";
        }
		$sql .= " GROUP BY p.product_id";
      //echo $sql;
		$sort_data = array(
			'pd.name',
			'p.model',
			'p.quantity',
			'p.price',
			'rating',
			'p.sort_order',
			'p.date_added'
		);

		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
			if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
				$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
			} elseif ($data['sort'] == 'p.price') {
				$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
			} else {
				$sql .= " ORDER BY " . $data['sort'];
			}
		} else {
			$sql .= " ORDER BY p.sort_order";
		}

		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC, LCASE(pd.name) DESC";
		} else {
			$sql .= " ASC, LCASE(pd.name) ASC";
		}
	//	echo $sql;
		  
          if (!empty($data['filter_attributes'])) {
		   
            $query = $this->db->query($sql);
                $without_attr_product_ids = [];
                foreach ($query->rows as $row) {
                    $without_attr_product_ids[] = "'" . $row['product_id'] . "'";
                }

                if ($without_attr_product_ids) {
                    $sql = "SELECT
                          DISTINCT(pa.product_id),
                          p.price,
                          (SELECT AVG(rating) AS total
                               FROM " . DB_PREFIX . "review r1
                               WHERE r1.product_id = p.product_id
                                 AND r1.status = '1'
                               GROUP BY r1.product_id) AS rating,
                          (SELECT price
                               FROM " . DB_PREFIX . "product_discount pd2
                               WHERE pd2.product_id = pa.product_id
                                 AND pd2.customer_group_id = '1'
                                 AND pd2.quantity = '1'
                                 AND ((pd2.date_start = '0000-00-00'
                                       OR pd2.date_start < NOW())
                                      AND (pd2.date_end = '0000-00-00'
                                           OR pd2.date_end > NOW()))
                               ORDER BY pd2.priority ASC, pd2.price ASC
                               LIMIT 1) AS discount,
                               (SELECT price
                                   FROM " . DB_PREFIX . "product_special ps
                                   WHERE ps.product_id = pa.product_id
                                     AND ps.customer_group_id = '1'
                                     AND ((ps.date_start = '0000-00-00'
                                           OR ps.date_start < NOW())
                                          AND (ps.date_end = '0000-00-00'
                                               OR ps.date_end > NOW()))
                                   ORDER BY ps.priority ASC, ps.price ASC
                                   LIMIT 1) AS special
                        FROM " . DB_PREFIX . "product_attribute pa";

                    $attribute_ids = array();
                    foreach ($data['filter_attributes'] as $key => $attribute_values) {
                        //foreach ($attribute_values as $attribute_value) {
                        //echo $attribute_value;
                            if ($attribute_values) {
                                $attribute_ids[$key] = "'" . $attribute_values . "'";
                            }
						
                       // }
						

                        if (!empty($attribute_ids[$key])) {
                            $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa" . $key . " ON (pa.product_id = pa" . $key . ".product_id)";
                        }
                    }
					

                    $sql .= " LEFT JOIN (SELECT price, product_id FROM " . DB_PREFIX . "product_discount) AS pd2 ON (pd2.product_id = pa.product_id)
                    LEFT JOIN (SELECT price, product_id FROM " . DB_PREFIX . "product_special) AS ps ON (ps.product_id = pa.product_id)
                    LEFT JOIN (SELECT price, sort_order, model, product_id FROM " . DB_PREFIX . "product) AS p ON (p.product_id = pa.product_id)
                    LEFT JOIN (SELECT name, product_id FROM " . DB_PREFIX . "product_description) AS pd ON (pd.product_id = pa.product_id)";

                    $sql .= " WHERE pa.product_id IN (" . implode(',', $without_attr_product_ids) . ")";

                     if ($attribute_ids) {
                        foreach ($attribute_ids as $key => $ids) {
						$sql .= " AND pa" . $key . ".attribute_id =". $key ;
                            $sql .= " AND pa" . $key . ".text =". $ids ;
                        }
                    }
                    

                    	$sql .= " GROUP BY p.product_id";

					$sort_data = array(
						'pd.name',
						'p.model',
						'p.quantity',
						'p.price',
						'rating',
						'p.sort_order',
						'p.date_added'
					);

					if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
						if ($data['sort'] == 'pd.name' || $data['sort'] == 'p.model') {
							$sql .= " ORDER BY LCASE(" . $data['sort'] . ")";
						} elseif ($data['sort'] == 'p.price') {
							$sql .= " ORDER BY (CASE WHEN special IS NOT NULL THEN special WHEN discount IS NOT NULL THEN discount ELSE p.price END)";
						} else {
							$sql .= " ORDER BY " . $data['sort'];
						}
					} else {
						$sql .= " ORDER BY p.sort_order";
					}

					if (isset($data['order']) && ($data['order'] == 'DESC')) {
						$sql .= " DESC, LCASE(pd.name) DESC";
					} else {
						$sql .= " ASC, LCASE(pd.name) ASC";
					}
					
					}
					
					}//end atrribute
					

		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}

			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			//echo $sql; die;
		}
      //echo $sql; 
		$product_data = array();

		$query = $this->db->query($sql);

		foreach ($query->rows as $result) {
			$product_data[$result['product_id']] = $this->getProduct($result['product_id']);
		}

		return $product_data;
	}


	public function getTotalProducts($data = array()) {
		$sql = "SELECT p.price,p.product_id, (SELECT AVG(rating) AS total FROM " . DB_PREFIX . "review r1 WHERE r1.product_id = p.product_id AND r1.status = '1' GROUP BY r1.product_id) AS rating, (SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1) AS discount, (SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1) AS special";

      

		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			} else {
				$sql .= " FROM " . DB_PREFIX . "product_to_category p2c";
			}

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}
			if(!empty($data['filter_options'])){
						$sql.= " LEFT JOIN `".DB_PREFIX."product_option_value` pov ON (pov.product_id = p.product_id) ";
					}
		 
		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";
		
		/*OPTIONS*/
		if(!empty($data['filter_options'])){
		    
		    $implodeoptions = array();

				$filtersoptions = explode(',', $data['filter_options']);

				foreach ($filtersoptions as $filter_id) {
				if($filter_id){
					$sql.= " AND pov.option_value_id= '" . (int)$filter_id . "'";
					}
				}
			
		}
		if (!empty($data['filter_category_id'])) {
			if (!empty($data['filter_sub_category'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			} else {
				$sql .= " AND p2c.category_id = '" . (int)$data['filter_category_id'] . "'";
			}

			if (!empty($data['filter_filter'])) {
				$implode = array();

				$filters = explode(',', $data['filter_filter']);

				foreach ($filters as $filter_id) {
					$implode[] = (int)$filter_id;
				}

				$sql .= " AND pf.filter_id IN (" . implode(',', $implode) . ")";
			}
		}

		if (!empty($data['filter_name']) || !empty($data['filter_tag'])) {
			$sql .= " AND (";

			if (!empty($data['filter_name'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_name'])));

				foreach ($words as $word) {
					$implode[] = "pd.name LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}

				if (!empty($data['filter_description'])) {
					$sql .= " OR pd.description LIKE '%" . $this->db->escape($data['filter_name']) . "%'";
				}
			}

			if (!empty($data['filter_name']) && !empty($data['filter_tag'])) {
				$sql .= " OR ";
			}

			if (!empty($data['filter_tag'])) {
				$implode = array();

				$words = explode(' ', trim(preg_replace('/\s+/', ' ', $data['filter_tag'])));

				foreach ($words as $word) {
					$implode[] = "pd.tag LIKE '%" . $this->db->escape($word) . "%'";
				}

				if ($implode) {
					$sql .= " " . implode(" AND ", $implode) . "";
				}
			}

			if (!empty($data['filter_name'])) {
				$sql .= " OR LCASE(p.model) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.sku) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.upc) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.ean) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.jan) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.isbn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
				$sql .= " OR LCASE(p.mpn) = '" . $this->db->escape(utf8_strtolower($data['filter_name'])) . "'";
			}

			$sql .= ")";
		}
		
		/* Price range */
        if  (!empty($data['filter_price'])) {
            $min_price = $data['filter_price']['min_price'];
            $max_price = $data['filter_price']['max_price'];
            $sql_filter_special = "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)";
            $sql_sl_discount = "(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)";
            $sql .= " AND (CASE WHEN " . $sql_filter_special . " IS NOT NULL THEN " . $sql_filter_special . " WHEN " . $sql_sl_discount . " IS NOT NULL THEN ". $sql_sl_discount . " ELSE p.price END) >='". $min_price ."'" ;
            $sql .= " AND (CASE WHEN " . $sql_filter_special . " IS NOT NULL THEN " . $sql_filter_special . " WHEN " . $sql_sl_discount . " IS NOT NULL THEN ". $sql_sl_discount . " ELSE p.price END) <='". $max_price ."'";
        }
        /* End */
		if (!empty($data['filter_manufacturer_id'])) {
			$sql .= " AND p.manufacturer_id = '" . (int)$data['filter_manufacturer_id'] . "'";
		}
		if (!empty($data['filter_quantity'])) {
			$sql .= " AND p.quantity > 0";
		}
		/* Price range */
        if  (!empty($data['filter_price'])) {
            $min_price = $data['filter_price']['min_price'];
            $max_price = $data['filter_price']['max_price'];
			//echo $min_price;
            $sql_filter_special = "(SELECT price FROM " . DB_PREFIX . "product_special ps WHERE ps.product_id = p.product_id AND ps.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND ((ps.date_start = '0000-00-00' OR ps.date_start < NOW()) AND (ps.date_end = '0000-00-00' OR ps.date_end > NOW())) ORDER BY ps.priority ASC, ps.price ASC LIMIT 1)";
            $sql_sl_discount = "(SELECT price FROM " . DB_PREFIX . "product_discount pd2 WHERE pd2.product_id = p.product_id AND pd2.customer_group_id = '" . (int)$this->config->get('config_customer_group_id') . "' AND pd2.quantity = '1' AND ((pd2.date_start = '0000-00-00' OR pd2.date_start < NOW()) AND (pd2.date_end = '0000-00-00' OR pd2.date_end > NOW())) ORDER BY pd2.priority ASC, pd2.price ASC LIMIT 1)";
            $sql .= " AND (CASE WHEN " . $sql_filter_special . " IS NOT NULL THEN " . $sql_filter_special . " WHEN " . $sql_sl_discount . " IS NOT NULL THEN ". $sql_sl_discount . " ELSE p.price END) >='". $min_price ."'" ;
            $sql .= " AND (CASE WHEN " . $sql_filter_special . " IS NOT NULL THEN " . $sql_filter_special . " WHEN " . $sql_sl_discount . " IS NOT NULL THEN ". $sql_sl_discount . " ELSE p.price END) <='". $max_price ."'";
        }
		//echo $sql;//
        /* End */
        if (!empty($data['filter_manufacturers']) && is_array($data['filter_manufacturers']) ) {
            $sql .= " AND p.manufacturer_id IN (" . implode(",", $data['filter_manufacturers']) . ")";
        }
		$query = $this->db->query($sql);
       if (!empty($data['filter_attributes'])) {
		   
           // $query = $this->db->query($sql);
                $without_attr_product_ids = [];
                foreach ($query->rows as $row) {
                    $without_attr_product_ids[] = "'" . $row['product_id'] . "'";
                }
              //  print_r($query->rows);
                //print_r($without_attr_product_ids);
                if ($without_attr_product_ids) {
                    $sql = "SELECT
                          DISTINCT(pa.product_id),
                          p.price,
                          (SELECT AVG(rating) AS total
                               FROM " . DB_PREFIX . "review r1
                               WHERE r1.product_id = p.product_id
                                 AND r1.status = '1'
                               GROUP BY r1.product_id) AS rating,
                          (SELECT price
                               FROM " . DB_PREFIX . "product_discount pd2
                               WHERE pd2.product_id = pa.product_id
                                 AND pd2.customer_group_id = '1'
                                 AND pd2.quantity = '1'
                                 AND ((pd2.date_start = '0000-00-00'
                                       OR pd2.date_start < NOW())
                                      AND (pd2.date_end = '0000-00-00'
                                           OR pd2.date_end > NOW()))
                               ORDER BY pd2.priority ASC, pd2.price ASC
                               LIMIT 1) AS discount,
                               (SELECT price
                                   FROM " . DB_PREFIX . "product_special ps
                                   WHERE ps.product_id = pa.product_id
                                     AND ps.customer_group_id = '1'
                                     AND ((ps.date_start = '0000-00-00'
                                           OR ps.date_start < NOW())
                                          AND (ps.date_end = '0000-00-00'
                                               OR ps.date_end > NOW()))
                                   ORDER BY ps.priority ASC, ps.price ASC
                                   LIMIT 1) AS special
                        FROM " . DB_PREFIX . "product_attribute pa";

                    $attribute_ids = array();
                   // print_r($data['filter_attributes']);
                    foreach ($data['filter_attributes'] as $key => $attribute_values) {
                      //  foreach ($attribute_values as $attribute_value) {
                            if ($attribute_values) {
                                $attribute_ids[$key] = "'" . $attribute_values . "'";
                            }
							
                       // }
                       
						//print_r($attribute_ids);

                        if (!empty($attribute_ids[$key])) {
                            $sql .= " LEFT JOIN " . DB_PREFIX . "product_attribute pa" . $key . " ON (pa.product_id = pa" . $key . ".product_id)";
//                    $sql .= " AND text IN (" . implode(',', $attribute_value_ids) . ")";
                        }
                    }
					//echo $sql;

                    $sql .= " LEFT JOIN (SELECT price, product_id FROM " . DB_PREFIX . "product_discount) AS pd2 ON (pd2.product_id = pa.product_id)
                    LEFT JOIN (SELECT price, product_id FROM " . DB_PREFIX . "product_special) AS ps ON (ps.product_id = pa.product_id)
                    LEFT JOIN (SELECT price, sort_order, model, product_id FROM " . DB_PREFIX . "product) AS p ON (p.product_id = pa.product_id)
                    LEFT JOIN (SELECT name, product_id FROM " . DB_PREFIX . "product_description) AS pd ON (pd.product_id = pa.product_id)";

                    $sql .= " WHERE pa.product_id IN (" . implode(',', $without_attr_product_ids) . ")";

                    if ($attribute_ids) {
                        foreach ($attribute_ids as $key => $ids) {
						$sql .= " AND pa" . $key . ".attribute_id =". $key ;
                            $sql .= " AND pa" . $key . ".text=".$ids;
                        }
                    }

                   
                }
            }
            //echo $sql;
           
        $query = $this->db->query($sql);
      // print_r($query->row);
		$total = count($query->rows);

        return $total;
	}

	
}

<?php
class ModelExtensionApiCatalogFilter extends Model {
	public function getMinPrice($data = array()) {
		$sql = "SELECT MIN( p.price) as min , MAX( p.price) max";

		if (!empty($data['filter_category_id'])) {
			
				$sql .= " FROM " . DB_PREFIX . "category_path cp LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON (cp.category_id = p2c.category_id)";
			

			if (!empty($data['filter_filter'])) {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product_filter pf ON (p2c.product_id = pf.product_id) LEFT JOIN " . DB_PREFIX . "product p ON (pf.product_id = p.product_id)";
			} else {
				$sql .= " LEFT JOIN " . DB_PREFIX . "product p ON (p2c.product_id = p.product_id)";
			}
		} else {
			$sql .= " FROM " . DB_PREFIX . "product p";
		}

		$sql .= " LEFT JOIN " . DB_PREFIX . "product_description pd ON (p.product_id = pd.product_id) LEFT JOIN " . DB_PREFIX . "product_to_store p2s ON (p.product_id = p2s.product_id) WHERE pd.language_id = '" . (int)$this->config->get('config_language_id') . "' AND p.status = '1' AND p.date_available <= NOW() AND p2s.store_id = '" . (int)$this->config->get('config_store_id') . "'";

		if (!empty($data['filter_category_id'])) {
				$sql .= " AND cp.path_id = '" . (int)$data['filter_category_id'] . "'";
			

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

		$query = $this->db->query($sql);

		return $query->row;
	}
	
	
	 public function getCategoryOptions($category_id) {
        $sql = '
            SELECT DISTINCT od.option_id, od.name, o.type 
            	FROM `'. DB_PREFIX .'product_option_value` pov
                INNER JOIN `'. DB_PREFIX .'product_to_category` pc on pov.`product_id` = pc.`product_id`
                INNER JOIN `'. DB_PREFIX .'option_description` od on od.`option_id` = pov.`option_id`
            	INNER JOIN `'. DB_PREFIX .'category_path` cp on cp.category_id = pc.category_id
                INNER JOIN `'. DB_PREFIX .'option` o ON o.option_id = pov.option_id
            WHERE 
          od.language_id ='. (int)$this->config->get('config_language_id')
            .' and cp.path_id = '.(int)$category_id;
        
        $query = $this->db->query($sql);

        $options = array();
        foreach ($query->rows as $result) {
            $sql = 'SELECT distinct od.option_value_id,od.name FROM `'. DB_PREFIX .'option_value_description` od
                    INNER JOIN `'. DB_PREFIX .'product_option_value` pov ON pov.option_value_id = od.option_value_id
                    INNER JOIN `'. DB_PREFIX .'product_to_category` pc on pov.`product_id` = pc.`product_id`
            	    INNER JOIN `'. DB_PREFIX .'category_path` cp on cp.category_id = pc.category_id
                    WHERE od.option_id = '.$result['option_id'].'
                    and od.language_id ='. (int)$this->config->get('config_language_id')
                    .' and cp.path_id = '.(int)$category_id
                    .' order by od.name';
            $option_values_query = $this->db->query($sql);
            
            $option_values = array();
            foreach ($option_values_query->rows as $value) {
                $option_values[]=array('option_value_id'=>$value['option_value_id'],'name'=>$value['name']);
            }
            
            $options[] = array(
                'option_id' =>$result['option_id'],
                'name'=>$result['name'],
                'type'=>$result['type'],
                'option_values'=>$option_values
            );
            
        }
        
        return $options;
    }
   
	
	 public function getAttributes($category_id)
    {
        $product_attribute_group_data = array();

		$product_attribute_group_query = $this->db->query("SELECT ag.attribute_group_id, agd.name FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_group ag ON (a.attribute_group_id = ag.attribute_group_id) LEFT JOIN " . DB_PREFIX . "attribute_group_description agd ON (ag.attribute_group_id = agd.attribute_group_id)  LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(pa.product_id=p2c.product_id)  WHERE  p2c.category_id IN
                    (SELECT category_id FROM " . DB_PREFIX . "category_path WHERE path_id = " . (int)$category_id . ") AND agd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY ag.attribute_group_id ORDER BY ag.sort_order, agd.name");
         //print_r($product_attribute_group_query->rows);
		foreach ($product_attribute_group_query->rows as $product_attribute_group) {
		    $results=array();
			$product_attribute_data = array();

			$product_attribute_query = $this->db->query("SELECT a.attribute_id, ad.name, pa.text FROM " . DB_PREFIX . "product_attribute pa LEFT JOIN " . DB_PREFIX . "attribute a ON (pa.attribute_id = a.attribute_id) LEFT JOIN " . DB_PREFIX . "attribute_description ad ON (a.attribute_id = ad.attribute_id) LEFT JOIN " . DB_PREFIX . "product_to_category p2c ON(pa.product_id=p2c.product_id)  WHERE  p2c.category_id IN
                    (SELECT category_id FROM " . DB_PREFIX . "category_path WHERE path_id = " . (int)$category_id . ") AND a.attribute_group_id = '" . (int)$product_attribute_group['attribute_group_id'] . "' AND ad.language_id = '" . (int)$this->config->get('config_language_id') . "' AND pa.language_id = '" . (int)$this->config->get('config_language_id') . "'  ORDER BY a.sort_order, ad.name");

			foreach ($product_attribute_query->rows as $product_attribute) {
			
            if (!isset($results[$product_attribute['attribute_id']])) {
				$results[$product_attribute['attribute_id']] = array(
					'attribute_id'   => $product_attribute['attribute_id'],
					'attribute_name' => $product_attribute['name'],
					'values'         => array(),
				);
			}
			$results[$product_attribute['attribute_id']]['values'][$product_attribute['text']] = array(
				
				'value' => $product_attribute['text'],
			
			);
			}

			$product_attribute_group_data[] = array(
				'attribute_group_id' => $product_attribute_group['attribute_group_id'],
				'name'               => $product_attribute_group['name'],
				'attribute'          => $results
			);
		}

		return $product_attribute_group_data;
    }
	
	  private function getCustomerGroup()
    {
        if ($this->customer->isLogged()) {
            $customer_group_id = $this->customer->getGroupId();
            return $customer_group_id;
        } else {
            $customer_group_id = $this->config->get('config_customer_group_id');
            return $customer_group_id;
        }
    }


	public function getCategoryFilters($category_id) {
		$implode = array();

		//$query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter cf LEFT JOIN ".DB_PREFIX ."category_path cp ON(cf.category_id=cp.category_id)  WHERE  cp.path_id = '" . (int)$category_id . "'");
       //print_r($query->rows());
	   $query = $this->db->query("SELECT filter_id FROM " . DB_PREFIX . "category_filter ");
      
		foreach ($query->rows as $result) {
			$implode[] = (int)$result['filter_id'];
		}

		$filter_group_data = array();

		if ($implode) {
			//$filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");
           $filter_group_query = $this->db->query("SELECT DISTINCT f.filter_group_id, fgd.name, fg.sort_order FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_group fg ON (f.filter_group_id = fg.filter_group_id) LEFT JOIN " . DB_PREFIX . "filter_group_description fgd ON (fg.filter_group_id = fgd.filter_group_id) WHERE  fgd.language_id = '" . (int)$this->config->get('config_language_id') . "' GROUP BY f.filter_group_id ORDER BY fg.sort_order, LCASE(fgd.name)");

			foreach ($filter_group_query->rows as $filter_group) {
				$filter_data = array();

				//$filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE f.filter_id IN (" . implode(',', $implode) . ") AND f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");
                  $filter_query = $this->db->query("SELECT DISTINCT f.filter_id, fd.name FROM " . DB_PREFIX . "filter f LEFT JOIN " . DB_PREFIX . "filter_description fd ON (f.filter_id = fd.filter_id) WHERE  f.filter_group_id = '" . (int)$filter_group['filter_group_id'] . "' AND fd.language_id = '" . (int)$this->config->get('config_language_id') . "' ORDER BY f.sort_order, LCASE(fd.name)");
          
				foreach ($filter_query->rows as $filter) {
					$filter_data[] = array(
						'filter_id' => $filter['filter_id'],
						'name'      => $filter['name']
					);
				}

				if ($filter_data) {
					$filter_group_data[] = array(
						'filter_group_id' => $filter_group['filter_group_id'],
						'name'            => $filter_group['name'],
						'filter'          => $filter_data
					);
				}
			}
		}

		return $filter_group_data;
	}
}

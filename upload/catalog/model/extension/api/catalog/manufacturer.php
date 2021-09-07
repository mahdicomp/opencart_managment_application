<?php
class ModelExtensionApiCatalogManufacturer extends Model {
        public function getBrandsByCategoryId($category_id) {
            $query = $this->db->query("
        		SELECT
        			m.* 
        		FROM
        			" . DB_PREFIX . "product p 
        		RIGHT JOIN " . DB_PREFIX . "product_to_category p2c ON 
        			p.product_id = p2c.product_id 
        		LEFT JOIN " . DB_PREFIX . "manufacturer m ON 
        			p.manufacturer_id = m.manufacturer_id
        		WHERE 
        			p2c.category_id = " . (int)$category_id . " AND 
        			m.manufacturer_id IS NOT NULL
        		GROUP BY m.manufacturer_id
            ");
            return $query->rows;
        } 
}
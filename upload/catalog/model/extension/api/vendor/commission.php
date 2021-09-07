<?php
class ModelExtensionApiVendorCommission extends Model {
	/* update 06-03-2019 update function and query */		
	public function getTotaCommission($data,$vendor_id,$order_product_id){
		$sql = "SELECT sum(totalcommission) AS total FROM " . DB_PREFIX . "vendor_order_product WHERE order_product_id='".$order_product_id."' ";
	/* update 06-03-2019 update function and query */	
		if(isset($data['vendor_id'])){
			$sql .= " and vendor_id='".$data['vendor_id']."'";
		}

		$sql .= " AND vendor_id ='".$vendor_id. "'";
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}	
	
	
	public function getOrderCurrency($order_id){
		$sql = "SELECT * FROM `" . DB_PREFIX . "order` WHERE order_id='".$order_id."'";
		$query = $this->db->query($sql);
		return $query->row;
	}	
				
 	public function getCommissionReports($data=array()){
		/* update 08-04-2019 start */
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
		
		$sql="SELECT * FROM " . DB_PREFIX . "vendor_order_product WHERE order_product_id<>0 AND order_status_id IN(" . implode(",", $implode) . ") ";
		/* update 08-04-2019 end */
		
		if(isset($data['vendor_id'])){
			$sql .= " and vendor_id='".$data['vendor_id']."'";
		}	
		
		$sort_data = array(
			'order_product_id'
		);
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		 	$sql .= " ORDER BY " . $data['sort'];
		} else {
		 	$sql .= " ORDER BY order_product_id";
		}
		if (isset($data['order']) && ($data['order'] == 'DESC')) {
			$sql .= " DESC";
		} else {
			$sql .= " ASC";
		}
		if (isset($data['start']) || isset($data['limit'])) {
			if ($data['start'] < 0) {
				$data['start'] = 0;
			}
			if ($data['limit'] < 1) {
				$data['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;	
 	}
 	public function getTotalCommissionReport($data=array()) {
		/* update 08-04-2019 start */
		$implode = array();

		foreach ($this->config->get('config_complete_status') as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
		
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "vendor_order_product WHERE order_product_id<>0 AND order_status_id IN(" . implode(",", $implode) . ") ";
		/* update 08-04-2019 end */
		if(isset($data['vendor_id'])){
			$sql .= " and vendor_id='".$data['vendor_id']."'";
		}
		
		$query = $this->db->query($sql);
		return $query->row['total'];
	}
	
}

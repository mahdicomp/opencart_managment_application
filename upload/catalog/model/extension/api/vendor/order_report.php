<?php
class ModelExtensionApiVendorOrderReport extends Model {
	
	public function getTotalReport($data) {
	
		$implode = array();
		
		$vendorstatus = $this->config->get('vendor_showorder_status');
		$defaultstatus = $this->config->get('config_complete_status');
		
		if(!empty($vendorstatus)){
			foreach ($vendorstatus as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
			}
		} else {
			foreach ($defaultstatus as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
		}
			
		$sql = "SELECT * FROM " . DB_PREFIX . "vendor_order_product vop LEFT JOIN `" . DB_PREFIX . "order` o ON (vop.order_id = o.order_id) WHERE vop.vendor_id='".$this->vendor->getId()."' AND vop.order_status_id IN(" . implode(",", $implode) . ")";
		
		
		if (isset($data['filter_seller'])){
		 	$sql .=" AND vop.vendor_id like '".$this->db->escape($data['filter_seller'])."%'";
		}

		if (isset($data['filter_customer'])){
		 	$sql .=" AND o.customer_id like '".$this->db->escape($data['filter_customer'])."%'";
		}
		
		if (isset($data['filter_status'])){
		 	$sql .=" AND vop.order_status_id like '".$this->db->escape($data['filter_status'])."%'";
		}
		
		if (isset($data['filter_order_id'])){
		 	$sql .=" AND o.order_id like '".$this->db->escape($data['filter_order_id'])."%'";
		}
		
		if (isset($data['filter_date'])){
		 	$sql .=" AND vop.date_added like '".$this->db->escape($data['filter_date'])."%'";
		}
		
		$sql .= " GROUP by vop.order_id";
		
		$query = $this->db->query($sql);
		
		return $query->num_rows;
	}
	
	public function getReports($data){		
		
		$vendorstatus = $this->config->get('vendor_showorder_status');
		$defaultstatus = $this->config->get('config_complete_status');
		
		if(!empty($vendorstatus)){
			foreach ($vendorstatus as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
			}
		} else {
			foreach ($defaultstatus as $order_status_id) {
			$implode[] = "'" . (int)$order_status_id . "'";
		}
		}
		
		$sql = "SELECT *,vop. order_status_id FROM " . DB_PREFIX . "vendor_order_product vop LEFT JOIN `" . DB_PREFIX . "order` o ON (vop.order_id = o.order_id) WHERE  vop.order_status_id IN(" . implode(",", $implode) . ") AND vop.order_id ";
		
		if (isset($data['filter_seller'])) {
		 	$sql .=" and vop.vendor_id like '".$this->db->escape($data['filter_seller'])."%'";
		}

		if (isset($data['filter_customer'])){
		 	$sql .=" and o.customer_id like '".$this->db->escape($data['filter_customer'])."%'";
		}
		
		if (isset($data['filter_status'])){
		 	$sql .=" and vop.order_status_id like '".$this->db->escape($data['filter_status'])."%'";
		}
		
		if (isset($data['filter_order_id'])){
		 	$sql .=" and vop.order_id like '".$this->db->escape($data['filter_order_id'])."%'";
		}
		
		if (isset($data['filter_date'])){
		 	$sql .=" and vop.date_added like '".$this->db->escape($data['filter_date'])."%'";
		}
		
		$sort_data = array(
			'vop.order_product_id',
			'vop.name',
			'vop.date_added',
			'vop.order_id',
			'o.firstname'
		);
	
		$sql .= " AND vop.vendor_id='".$this->vendor->getId()."'";
		$sql .= " GROUP by vop.order_id";
		
		if (isset($data['sort']) && in_array($data['sort'], $sort_data)) {
		 	$sql .= " ORDER BY " . $data['sort'];
		} else {
		 	$sql .= " ORDER BY vop.order_id";
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
	
		
	
	public function getAdminOrderStatuss ($order_id) {
		$sql="SELECT * FROM `" . DB_PREFIX . "order` where order_id='".$order_id."'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}	
	
	public function getOrderStatus ($order_status_id) {
		/* 27 04 2020 update query */
		$sql="SELECT * FROM `" . DB_PREFIX . "order_status` where order_status_id='".$order_status_id."' AND language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}
	
	public function getAdminOrderStatus ($order_status_id) {
		/* 27 04 2020 update query */
		$sql="SELECT * FROM `" . DB_PREFIX . "order_status` where order_status_id='".$order_status_id."'  AND language_id = '" . (int)$this->config->get('config_language_id') . "'";
		$query = $this->db->query($sql);
		
		return $query->row;
	}	
	
}	
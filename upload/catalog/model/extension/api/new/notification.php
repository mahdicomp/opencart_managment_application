<?php
class ModelExtensionApiNewNotification extends Model {
	public function getTotalNotification($data = array()) {
			$sql = "SELECT COUNT(DISTINCT(notification_id)) AS total FROM `" . DB_PREFIX . "notification` WHERE customer_id IN (0, '" . (int)$data['customer_id'] . "')"; 	
			
			$query = $this->db->query($sql);
			
			return $query->row['total'];		
		}	
		
		public function getnotifications($data = array()) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "notification` WHERE  customer_id IN (0, '" . (int)$data['customer_id'] . "')"; 
			
				$sql .= " ORDER BY date_added";	
				$sql .= " DESC";
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
		
			
		
		
		public function getnotificationInfo($notification_id) {
			$sql = "SELECT * FROM `" . DB_PREFIX . "notification` WHERE notification_id='" . (int)$notification_id . "'"; 
			
			$query = $this->db->query($sql);
			
			return $query->row;
		}
}

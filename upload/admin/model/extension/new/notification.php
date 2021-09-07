<?php
class ModelExtensionNewNotification extends Model {	
		public function getnotifications($data) {
		$sql = "SELECT  * FROM `" . DB_PREFIX . "notification` WHERE date_added < NOW() ";
		
		

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .= "ORDER BY date_added DESC  LIMIT " . (int)$data['start'] . "," . (int)$data['limit'] ;
			}
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
	

	
	public function getnotificationsTotal() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "notification`";
		$query = $this->db->query($sql);
		return $query->row['total'];	
	}	
	
	public function addNotification($data) {
		$this->db->query('INSERT INTO ' . DB_PREFIX . 'notification SET title = "' . $this->db->escape($data['title']) . '", message = "' . $this->db->escape($data['text']) . '", message_long = "' . $this->db->escape($data['message_long']) . '", customer_id = "' . (int)$data['customer_id'] . '", date_added = NOW()');
				
	}	
		
	
	
	public function deletenotification($notification_id) {
	$this->db->query("DELETE FROM `" . DB_PREFIX . "notification` WHERE notification_id = '" . (int)$notification_id . "'");
	}
	
	
}
?>
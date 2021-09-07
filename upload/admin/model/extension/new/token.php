<?php
class ModelExtensionNewToken extends Model {	
	public function getTokens($data) {
		$sql = "SELECT  * FROM `" . DB_PREFIX . "token_device`  ";
		
		

			if (isset($data['start']) || isset($data['limit'])) {
				if ($data['start'] < 0) {
					$data['start'] = 0;
				}				
	
				if ($data['limit'] < 1) {
					$data['limit'] = 20;
				}	
			
				$sql .=  " ORDER BY date_added DESC  LIMIT " . (int)$data['start'] . "," . (int)$data['limit'];
			}
		$query = $this->db->query($sql);
		
		return $query->rows;
	}	
	

	
	public function getTokensTotal() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "token_device`";
		$query = $this->db->query($sql);
		return $query->row['total'];	
	}	
	
	
	public function getTokensTotalEnable() {
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "token_device` WHERE status=1";
		$query = $this->db->query($sql);
		return $query->row['total'];	
	}	
	
	
	
	public function getPlatform($platform) {
	    
		$sql = "SELECT COUNT(*) AS total FROM `" . DB_PREFIX . "token_device` WHERE os= '" . $this->db->escape($platform) . "'";
		$query = $this->db->query($sql);
		if ($query->num_rows){
	    	return $query->row['total'];	
		}else {
		    return 0;
		}
	
	}
	
	
	public function deleteToken($token_device_id) {
	$this->db->query("DELETE FROM `" . DB_PREFIX . "token_device` WHERE token_device = '" . (int)$token_device_id . "'");
	}
		
	public function UpdateStatus($token) {
	$this->db->query("UPDATE " . DB_PREFIX . "token_device SET status = '0' WHERE token = '" . $this->db->escape($token) . "'");
	}
	
	
}
?>
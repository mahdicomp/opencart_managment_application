<?php
class ModelExtensionApiAccountDownload extends Model {
	public function gettoken() {
	    $query = $this->db->query("SELECT token FROM `" . DB_PREFIX . "token_device`  WHERE customer_id = '" . (int)$this->customer->getId() . "'");
	    if($query->num_rows) {
	    return $query->row['token'];
	    }else {
	        return false;
	    }
	}
	public function getCustomerid($token) {
	    $query = $this->db->query("SELECT customer_id FROM `" . DB_PREFIX . "token_device`  WHERE token = '" . $this->db->escape($token) . "'");
	    return $query->row['customer_id'];
	}
}
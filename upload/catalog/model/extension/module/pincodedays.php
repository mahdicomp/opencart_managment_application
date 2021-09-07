<?php
class ModelExtensionModulePincodedays extends Model {

	public function getBlockedDays() {
		$postcodetocheck = "";
		if(isset($this->session->data['shipping_address']) && isset($this->session->data['shipping_address']['postcode'])) {
			$postcodetocheck = strtolower($this->session->data['shipping_address']['postcode']);
		} else if(isset($this->session->data['payment_address']) && isset($this->session->data['payment_address']['postcode'])) {
			$postcodetocheck = strtolower($this->session->data['payment_address']['postcode']);
		}
		$blockdays = array();
		if(!$postcodetocheck && $this->customer->getId()) {
			$address_id = $this->customer->getAddressId();
			if($address_id){
				$address_query =  $this->db->query("SELECT postcode FROM `" . DB_PREFIX . "address` WHERE address_id = '".(int)$address_id."'");
				if($address_query->num_rows){
					$postcodetocheck = $address_query->row['postcode'];
				}
			}
		}

		if($postcodetocheck) {
			$replacecharacters = array(" ","-");
			$postcodetocheck = str_replace($replacecharacters, "", $postcodetocheck);
			$postcodetocheck = strtolower($postcodetocheck);
			//$postcodetocheck = substr($postcode, 0, $this->config->get('module_pincodedays_lengthtoconsider')); 
			$bit = 1;
			if($this->config->get('module_pincodedays_monday')) {
				$days = $this->config->get('module_pincodedays_monday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							 if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			
			if($bit) {
				$blockdays[] = 1; 
			}

			$bit = 1;
			if($this->config->get('module_pincodedays_tuesday')) {
				$days = $this->config->get('module_pincodedays_tuesday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							 if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			if($bit) {
				$blockdays[] = 2; 
			}

			$bit = 1;
			if($this->config->get('module_pincodedays_wednesday')) {
				$days = $this->config->get('module_pincodedays_wednesday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							 if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			if($bit) {
				$blockdays[] = 3; 
			}

			$bit = 1;
			if($this->config->get('module_pincodedays_thursday')) {
				$days = $this->config->get('module_pincodedays_thursday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			if($bit) {
				$blockdays[] = 4; 
			}

			$bit = 1;
			if($this->config->get('module_pincodedays_friday')) {
				$days = $this->config->get('module_pincodedays_friday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							 if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			if($bit) {
				$blockdays[] = 5; 
			}


			$bit = 1;
			if($this->config->get('module_pincodedays_saturday')) {
				$days = $this->config->get('module_pincodedays_saturday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							 if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			if($bit) {
				$blockdays[] = 6; 
			}

			$bit = 1;
			if($this->config->get('module_pincodedays_sunday')) {
				$days = $this->config->get('module_pincodedays_sunday');
				if($days == "ALL") {
					$bit = 0;
				} else {
					$daysarray = explode(",", $days);
					if(!empty($daysarray)) {
						foreach ($daysarray as $key => $value) {
							$temp = strpos($postcodetocheck, strtolower($value));
							 if ($temp !== false && $temp == 0) {
								$bit = 0;
								break;
							}
						}
					}
				}	
			}
			if($bit) {
				$blockdays[] = 0; 
			}
			
		} else {
			$bit = 1;
			if($this->config->get('module_pincodedays_monday')) {
				$days = $this->config->get('module_pincodedays_monday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 1; 
			}
			$bit = 1;
			if($this->config->get('module_pincodedays_tuesday')) {
				$days = $this->config->get('module_pincodedays_tuesday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 2; 
			}
			$bit = 1;
			if($this->config->get('module_pincodedays_wednesday')) {
				$days = $this->config->get('module_pincodedays_wednesday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 3; 
			}
			$bit = 1;
			if($this->config->get('module_pincodedays_thursday')) {
				$days = $this->config->get('module_pincodedays_thursday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 4; 
			}
			$bit = 1;
			if($this->config->get('module_pincodedays_friday')) {
				$days = $this->config->get('module_pincodedays_friday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 5; 
			}
			$bit = 1;
			if($this->config->get('module_pincodedays_saturday')) {
				$days = $this->config->get('module_pincodedays_saturday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 6; 
			}
			$bit = 1;
			if($this->config->get('module_pincodedays_sunday')) {
				$days = $this->config->get('module_pincodedays_sunday');
				if($days == "ALL") {
					$bit = 0;
				}
			}
			if($bit) {
				$blockdays[] = 0; 
			}
		}
		
		if(!empty($blockdays)) {
			asort($blockdays);
			$this->session->data['blockdays'] = $blockdays;
			return implode(",", $blockdays);
		} else {
			unset($this->session->data['blockdays']);
			return "";
		}
	}

	public function getTimeSlots() {
		$timeslot_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "timeslots_days` td LEFT JOIN `" . DB_PREFIX . "timeslots` t ON t.timeid = td.timeid WHERE 1");
		$timeslot_array = array();
		$orderleft_array = array();
		$today = 1;
		$format = "g:i a";
		if($this->config->get('module_pincodedays_timeformat')) {
			$format = "G:i";
		}
		if($timeslot_query->num_rows) {
			foreach ($timeslot_query->rows as $key => $timeslot) {
				$starttime = date($format, strtotime($timeslot['starttime']));
				$endtime = date($format, strtotime($timeslot['endtime']));
				$timeslot_array[$timeslot['weekday']][$timeslot['timeid']] = $starttime." - ".$endtime;
				$orderleft_array[$timeslot['weekday']][$timeslot['timeid']] = $timeslot['numberofslots'];
			}
		}
		
		$this->session->data['orderleft_array'] = $orderleft_array;
		return $timeslot_array;
	}

	public function getTimeZoneName($timezone_id) {
		$timeslot_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "timeslots_timezone` WHERE timezone_id = '".(int)$timezone_id."'");
		if($timeslot_query->num_rows) {
				return $timeslot_query->row['area'];
		}
		return "Asia/Kolkata";
	}

	public function getTimeSlotsForToday($weekday, $timezone) {

		$timeslot_array = array();
		if(!$this->config->get('module_pincodedays_samedaydelivery')) {
			return $timeslot_array;
		}

		date_default_timezone_set($timezone);
		$target_time_zone = new DateTimeZone($timezone);
	  	$date_time = new DateTime('now', $target_time_zone);
		$this->db->query("SET @@session.time_zone='".$date_time->format('P')."'");

		$timeslot_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "timeslots_days` td LEFT JOIN `" . DB_PREFIX . "timeslots` t ON t.timeid = td.timeid WHERE weekday = '".(int)$weekday."' AND starttime > CURTIME()");
		$today = 1;
		$format = "g:i a";
		if($this->config->get('module_pincodedays_timeformat')) {
			$format = "G:i";
		}
		if($timeslot_query->num_rows) {
			foreach ($timeslot_query->rows as $key => $timeslot) {
				$starttime = date($format, strtotime($timeslot['starttime']));
				$endtime = date($format, strtotime($timeslot['endtime']));
				$timeslot_array[$timeslot['weekday']][$timeslot['timeid']] = $starttime." - ".$endtime;
			}
		}
		return $timeslot_array;
	}

	public function getBlockedTimeSlotId($date, $weekday) {
		$returnarray = array();
		$date = date("Y-m-d",strtotime($date));
		$gettotalorders = $this->getTotalOrders($date);
		if($gettotalorders->row['total']) {
			$gettimesots = $this->db->query("SELECT * FROM `" . DB_PREFIX . "timeslots_days` WHERE weekday = '".(int)$weekday."' AND numberofslots != 0");
			if($gettimesots->num_rows) {
				foreach ($gettimesots->rows as $key => $value) {
					$getTotalSlots = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "order_deliverydate` WHERE deliverydate = '".$this->db->escape($date)."' AND timeslot_id = '".$value['timeid']."'");
					if ($getTotalSlots->row['total'] >= $value['numberofslots']) {
						$returnarray[] = $value['timeid'];
					}
				}
			} 
		} 
		return $returnarray;
	}

	public function getTotalOrders($date) {
		$date = date("Y-m-d",strtotime($date));
		$query = $this->db->query("SELECT count(*) AS total FROM `" . DB_PREFIX . "order_deliverydate` od LEFT JOIN `" . DB_PREFIX . "order` o ON (od.order_id = o.order_id) WHERE o.order_status_id NOT IN (0,7,8,10,11,12,16) AND od.deliverydate = '".$this->db->escape($date)."'");
		return $query;
	}

	public function getNumberOfSlots($date, $weekday, $blockeddays) {
		$date = date("Y-m-d",strtotime($date));
		$returnarray = array();
		$timeslots = $this->session->data['orderleft_array'][$weekday];

		if($timeslots) {
			foreach ($timeslots as $key => $value) {
				if(in_array($key, $blockeddays)) {
					continue;
				}
				$getTotalSlots = $this->db->query("SELECT count(*) as total FROM `" . DB_PREFIX . "order_deliverydate` WHERE deliverydate = '".$this->db->escape($date)."' AND timeslot_id = '".$key."'");
				if ($getTotalSlots->row['total'] < $value) {
					$value = $value - $getTotalSlots->row['total'];
				}
				$returnarray[$key] = $value;
			}
		}
		return $returnarray;
	}

	public function saveDateTime($order_id) {

		if(isset($this->session->data['pincodedays_deliverydate']) && $order_id) {
			$deliverydate_query = $this->db->query("DELETE FROM " . DB_PREFIX . "order_deliverydate WHERE order_id = '" . (int)$order_id . "'");
			$converteddate = date("Y-m-d",strtotime($this->session->data['pincodedays_deliverydate']));
			$this->db->query("INSERT INTO " . DB_PREFIX . "order_deliverydate SET order_id = '" . (int)$order_id . "', deliverydate = '" . $converteddate . "'");
			if(isset($this->session->data['pincodedays_timeslot'])) {
				$delivery_id = $this->db->getLastId();
				$deliverytime_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "timeslots WHERE timeid = '".(int)$this->session->data['pincodedays_timeslot']."'");
				$format = "g:i a";
				if($this->config->get('module_pincodedays_timeformat')) {
					$format = "G:i";
				}
				if($deliverytime_query->num_rows) {
					$starttime = date($format, strtotime($deliverytime_query->row['starttime']));
					$endtime = date($format, strtotime($deliverytime_query->row['endtime']));
					$this->db->query("UPDATE " . DB_PREFIX . "order_deliverydate SET deliverytime = '".$starttime.' - '.$endtime."', timeslot_id = '".(int)$this->session->data['pincodedays_timeslot']."' WHERE order_id = '" . (int)$order_id . "'");
				}
			}
		}

	}

}
?>
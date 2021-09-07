<?php
class ControllerExtensionApi32ExtensionModuleDatatimeSlot extends Controller {

	public function index() {

		unset($this->session->data['pincodedays_deliverydate']);
		unset($this->session->data['pincodedays_timeslot']);
		unset($this->session->data['pincodedays_daynumber']);
       
       $this->dateview();
       $this->loadjs();
		$json = array();
		$this->language->load('extension/module/pincodedays');
		$todayslots = 0;
		$areslotsavailable = 0;
	//	echo $this->session->data['dateboxshown'];
	//	if(isset($this->session->data['dateboxshown']) && $this->session->data['dateview']) {
	unset($this->session->data['pincodedays_deliverydate']);
				unset($this->session->data['pincodedays_timeslot']);
				unset($this->session->data['pincodedays_daynumber']);
				$this->load->language('extension/module/pincodedays');
				if(isset($this->session->data['dateboxshown']) && $this->session->data['dateboxshown']) {
				    	
				    //echo 1;
					if($this->config->get('module_pincodedays_status')) {
						if (isset($this->request->post['deliverydate'])) {
							if($this->request->post['deliverydate'] == "") {
								if(!$this->config->get('module_pincodedays_isitoptional')) {
									$json['error']['warning'] = $this->language->get('error_deliverydate_empty');
								}
							} else {
								$date = date_parse($this->request->post['deliverydate']);
								$result = checkdate($date['month'],$date['day'],$date['year']);
								if($result && !$json)  {
									$daynumber =  date('N', strtotime($this->request->post['deliverydate']));
									if($daynumber == 7) {$daynumber = 0;}
									if(isset($this->session->data['blockdays'])) {
										if(in_array($daynumber,$this->session->data['blockdays'])) {
											$json['error']['warning'] = $this->language->get('error_deliverydate_blocked');
										} 
									}
									if(isset($this->session->data['holiday_block']) && !empty($this->session->data['holiday_block'])) {
										if(in_array($this->request->post['deliverydate'],$this->session->data['holiday_block'])) {
											$json['error']['warning'] = $this->language->get('error_deliverydate_blocked');
										} 
									}
									$postdatetime = strtotime(date($this->request->post['deliverydate']));
									$currentdatetime = strtotime(date('d-m-Y'));
									
									if($postdatetime < $currentdatetime) {
									   $json['error']['warning'] = $this->language->get('error_deliverydate_old');
									}

									if($this->config->get('module_pincodedays_numberblock')) {
										$days = $this->config->get('module_pincodedays_numberblock') - 1;
										$currentdatetime = strtotime("+".$days." day", $currentdatetime);
										if($postdatetime < $currentdatetime) {
										   $json['error'] = $this->language->get('error_deliverydate_blocked');
										}
									}
                               
									if($postdatetime == $currentdatetime) {
									    
										if(isset($this->session->data['deliverytoday']) && !$this->session->data['deliverytoday']) {
											$json['error']['warning'] =  $this->language->get('error_deliveryslots_over');
										} else {
											if(isset($this->session->data['todaytimeslot']) && $this->session->data['todaytimeslot']) {
												$this->load->model("extension/module/pincodedays");
												if(isset($this->session->data['timezonename'])) {
													$timezone = $this->session->data['timezonename'];
												} else {
													$timezone = "Asia/Kolkata";
												}
												$data['timeslots_today']  = $this->model_extension_module_pincodedays->getTimeSlotsForToday($daynumber,$timezone);
												if(empty($data['timeslots_today'])) {
													$json['error']['warning'] =  $this->language->get('error_deliveryslots_over');
												}
												$json['timeslots_today']=$data['timeslots_today'];
											}	
										}
									}
									$this->load->model("extension/module/pincodedays");
									$blockedslots = $this->model_extension_module_pincodedays->getBlockedTimeSlotId($this->request->post['deliverydate'],$daynumber);
									if(isset($this->session->data['timeslots'][$daynumber])) {
										$totaltimeslots = count($this->session->data['timeslots'][$daynumber]);
										$totalblocks = count($blockedslots);
										if($totalblocks == $totaltimeslots) {
											$json['error']['warning'] = $this->language->get('error_alltimeslot_full');
										}					
									}
									if(!$json ) {
										$this->session->data['pincodedays_deliverydate'] = $this->request->post['deliverydate'];
										if(isset($this->session->data['timeslots']) && isset($this->session->data['timeslots'][$daynumber]) && isset($this->request->post['time_slots'])) {
											if(array_key_exists($this->request->post['time_slots'], $this->session->data['timeslots'][$daynumber])) {
												if(empty($blockedslots) || (!empty($blockedslots) && !in_array($this->request->post['time_slots'],$blockedslots))) {
													$this->session->data['pincodedays_timeslot'] = $this->request->post['time_slots'];
												}
											}
										}
										$this->session->data['pincodedays_daynumber'] = $daynumber;
									}
								} else {
									$json['error']['warning'] = $this->language->get('error_deliverydate_invalid');
								}
							}
						} else {
							$json['error']['warning'] = $this->language->get('error_deliverydate_empty');
						}
					}
					$json['timeslots'] = $this->model_extension_module_pincodedays->getTimeSlots();
				}
         if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function runFunctions(&$route, &$args, &$output) {
		$order_id = 0;

		if(isset($output) && !empty($output) && !is_array($output)) {
			$order_id = $output;
		} else if (isset($args[0])) {
			$order_id = $args[0];
		}
			
		$this->load->model("extension/module/pincodedays");
		if($order_id) {
			$this->model_extension_module_pincodedays->saveDateTime($order_id);
		}
	}

	public function dateview() {
		$timezoneid = $this->config->get('module_pincodedays_timezone');
		if(!$timezoneid) {$timezoneid = "247";}
		$this->load->model("extension/module/pincodedays");
		$timezonename = $this->model_extension_module_pincodedays->getTimeZoneName($timezoneid);
		$this->session->data['timezonename'] = $timezonename;
		date_default_timezone_set($timezonename);
		$daynum = date("N", strtotime(date("d-m-Y")));
		if($daynum == 7) {$daynum = 0;}
		$holidays_array_session = $data['timeslots_today'] = $holidays_array = array();
		if($this->config->get('module_pincodedays_status')) {
			$data['pincodedays'] = 1;
			$this->session->data['numberblock'] = 0;
			if($this->config->get('module_pincodedays_samedaydelivery')) {
				$data['deliverytoday']= 1;
			} else {
				$data['deliverytoday']= 0;
			}
		
			
			$data['blockdays'] = $this->model_extension_module_pincodedays->getBlockedDays();
			$data['timeslots'] = $this->model_extension_module_pincodedays->getTimeSlots();
			$this->session->data['timeslots'] = $data['timeslots'];
			if(isset($data['timeslots'][$daynum]) && !empty($data['timeslots'][$daynum])) {
				$this->session->data['todaytimeslot'] = 1;
				$data['timeslots_today']  = $this->model_extension_module_pincodedays->getTimeSlotsForToday($daynum,$timezonename);
				if(empty($data['timeslots_today'])) {
					$data['deliverytoday']= 0;
				}
			} else {
				$this->session->data['todaytimeslot'] = 0;
			}
			if($this->config->get('module_pincodedays_numberblock')) {
				$this->session->data['numberblock'] = $this->config->get('module_pincodedays_numberblock');
			} else if(!$data['deliverytoday']) {
				$this->session->data['numberblock'] = 1;
			}
			if($this->config->get('module_pincodedays_dateformat')) {
				$data['dateformat'] = "MM/DD/YYYY";
			} else {
				$data['dateformat'] = "DD-MM-YYYY";
			}

			$this->session->data['deliverytoday'] = $data['deliverytoday'];
			$this->session->data['daysofweekdisabled'] = "[".$data['blockdays']."]";
			$this->language->load('extension/module/pincodedays');
			$data['text_sdelivery_date'] =  $this->language->get('text_sdelivery_date');
			$data['text_enterdate'] =  $this->language->get('text_enterdate');
			$data['error_dmy_format'] =  $this->language->get('error_dmy_format');
			$data['error_mdy_format'] =  $this->language->get('error_mdy_format');
			
			$data['isitoptional'] =  ($this->config->get('module_pincodedays_isitoptional')) ? "":"required";
			$holidays = $this->config->get('module_pincodedays_holidays');
			if(!$this->config->get('module_pincodedays_samedaydelivery')) {
				$holidays .= ",".date('m/d/Y');
			}
			if($this->config->get('module_pincodedays_numberblock')) {
				$days = $this->config->get('module_pincodedays_numberblock');
				for($i=0;$i<$days;$i++) {
					$date = strtotime("+".$i." day");
					$holidays .= ",".date('m/d/Y', $date);
				}
			}
			$holidays = explode(",", $holidays);
			foreach ($holidays as $key => $value) {
				$holidays_array[] = "moment('".$value."','MM/DD/YYYY')";
				if(!$this->config->get('module_pincodedays_dateformat')) {
					$holidays_array_session[] = date("d-m-Y", strtotime($value));
				}
			}
			$this->session->data['holiday_block'] = $holidays_array_session;
			$this->session->data['holidaysstring'] = implode(",", $holidays_array);
		} else {
			$data['pincodedays'] = 0;
		}
		$data['pincodedays_deliverydate'] = '';
		if (isset($this->session->data['pincodedays_deliverydate'])) {
			$date = date_parse($this->session->data['pincodedays_deliverydate']);
			$result = checkdate($date['month'],$date['day'],$date['year']);
			if($result) {
				if(!$this->config->get('module_pincodedays_dateformat')) {
					$data['pincodedays_deliverydate'] =  date("d-m-Y", strtotime($this->session->data['pincodedays_deliverydate']));
				} else {
					$data['pincodedays_deliverydate'] =  date("m/d/Y", strtotime($this->session->data['pincodedays_deliverydate']));
				}
			}
		}
		if (isset($this->session->data['pincodedays_timeslot'])) {
			$data['pincodedays_timeslot'] = $this->session->data['pincodedays_timeslot'];
		} else {
			$data['pincodedays_timeslot'] = 0;
		}
		if (isset($this->session->data['pincodedays_daynumber'])) {
			$data['pincodedays_daynumber'] = $this->session->data['pincodedays_daynumber'];
		} else {
			$data['pincodedays_daynumber'] = NULL;
		}
     // echo $data['pincodedays'];
		if($data['pincodedays']) {
			$this->session->data['dateboxshown'] = 1;
		} else {
			unset($this->session->data['dateboxshown']);
		}
		$this->session->data['dateformat'] = $data['dateformat'];

		 if (isset($_SERVER['HTTP_ORIGIN'])) {
    		$this->response->addHeader("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
    		$this->response->addHeader('Access-Control-Allow-Credentials: ' . 'true');
    		$this->response->addHeader('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
    		$this->response->addHeader('Access-Control-Max-Age: 1000');
    		$this->response->addHeader('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');
    	}
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($data));
	}

	public function loadjs() {
		if (isset($this->session->data['numberblock'])) {
			$data['numberblock'] = $this->session->data['numberblock'];
		} else {
			$data['numberblock'] = 0;
		}
		if (isset($this->session->data['pincodedays_daynumber'])) {
			$data['pincodedays_daynumber'] = $this->session->data['pincodedays_daynumber'];
		} else {
			$data['pincodedays_daynumber'] = 0;
		}
		if (isset($this->session->data['daysofweekdisabled'])) {
			$data['daysofweekdisabled'] = $this->session->data['daysofweekdisabled'];
		} else {
			$data['daysofweekdisabled'] = 0;
		}
		if (isset($this->session->data['holidaysstring'])) {
			$data['holidaysstring'] = $this->session->data['holidaysstring'];
		} else {
			$data['holidaysstring'] = 0;
		}
		if (isset($this->session->data['pincodedays_timeslot'])) {
			$data['pincodedays_timeslot'] = $this->session->data['pincodedays_timeslot'];
		} else {
			$data['pincodedays_timeslot'] = 0;
		}
		if (isset($this->session->data['dateformat'])) {
			$data['dateformat'] = $this->session->data['dateformat'];
		} else {
			$data['dateformat'] = "DD-MM-YYYY";
		}
		return $this->load->view('extension/module/pincodedays_js', $data);
	}	

}
?>
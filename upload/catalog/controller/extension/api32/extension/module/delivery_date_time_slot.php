<?php
class ControllerExtensionApi32ExtensionModuleDeliveryDateTimeSlot extends Controller {
	public function index() {
		
		if($this->config->get('module_eb_delivery_date_time_zone_id')){
			date_default_timezone_set($this->config->get('module_eb_delivery_date_time_zone_id')); 	
		}
		
		$data['start'] = $this->config->get('module_eb_delivery_date_time_start_days');
		$data['delivery_time'] = $this->config->get('module_eb_delivery_date_time_enable_delivery_time');
		$data['date_time_layout'] = $this->config->get('module_eb_delivery_date_time_layout');
		
		$today=date('Y-m-d');
		$data['next_date'] = date('Y-m-d', strtotime($today. ' + '.$data['start'].' days'));
		
		$data['end'] = $this->config->get('module_eb_delivery_date_time_end_days');
		
		$disable_days = $this->config->get('module_eb_delivery_date_time_holidays');
		if($disable_days){
			$disable_days = explode(',',$disable_days);
			$data['disable_days'] = json_encode($disable_days);
		}else{
			$data['disable_days'] = '';
		}
		
		if(!empty($this->session->data['delivery_date'])){
			$data['delivery_date'] = $this->session->data['delivery_date'];
		}else{
			$data['delivery_date'] = '';
		}
		
		$data['delivery_date_required'] = $this->config->get('module_eb_delivery_date_time_required');
		$data['delivery_time_required'] = $this->config->get('module_eb_delivery_date_time_delivery_time_required');
		
		$data['delivery_weekend'] = ($this->config->get('module_eb_delivery_date_time_weekend') ? $this->config->get('module_eb_delivery_date_time_weekend') : array());
		
		$languages = $this->config->get('module_eb_delivery_date_time');
		
		$data['heading_title']  = (!empty($languages[$this->config->get('config_language_id')]['heading']) ? $languages[$this->config->get('config_language_id')]['heading'] : 'Estimated Delivery Date');
		
		$data['date_label']  = (!empty($languages[$this->config->get('config_language_id')]['date_text']) ? $languages[$this->config->get('config_language_id')]['date_text'] : 'Delivery Date');
		
		$data['time_label']  = (!empty($languages[$this->config->get('config_language_id')]['time_text']) ? $languages[$this->config->get('config_language_id')]['time_text'] : 'Delivery Time');
		
		
		$data['delivery_date_time_slot'] = $this->load->controller('extension/eb_delivery_date_time_slot/delivery_date_time_slot/datejs');	
		
		if($this->config->get('module_eb_delivery_date_time_status')){
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
	}
	
	
		public function datejs() {
		
		$this->load->language('extension/eb_delivery_date_time_slot/delivery_date_time_slot');
		
		
		
		if($this->config->get('module_eb_delivery_date_time_zone_id')){
			date_default_timezone_set($this->config->get('module_eb_delivery_date_time_zone_id')); 	
		}
		
		$data['start'] = $this->config->get('module_eb_delivery_date_time_start_days');
		
		
		
		$today=date('Y-m-d');
		$data['next_date'] = date('Y-m-d', strtotime($today. ' + '.$data['start'].' days'));
		
		$data['end'] = $this->config->get('module_eb_delivery_date_time_end_days');
		
		$disable_days = $this->config->get('module_eb_delivery_date_time_holidays');
		if($disable_days){
			$disable_days = explode(',',$disable_days);
			$data['disable_days'] = json_encode($disable_days);
		}else{
			$data['disable_days'] = '';
		}
		
		if(!empty($this->session->data['delivery_date'])){
			$data['delivery_date'] = $this->session->data['delivery_date'];
		}else{
			$data['delivery_date'] = '';
		}
		
		
		
		$data['delivery_required'] = $this->config->get('module_eb_delivery_date_time_required');
		
		$data['delivery_weekend'] = ($this->config->get('module_eb_delivery_date_time_weekend') ? $this->config->get('module_eb_delivery_date_time_weekend') : array());
		
		$languages = $this->config->get('module_eb_delivery_date_time');
		
		$data['heading_title']  = (!empty($languages['delivery']['heading_title'][$this->config->get('config_language_id')]) ? $languages['delivery']['heading_title'][$this->config->get('config_language_id')] : 'Estimated Delivery Date');
		
		$data['label']  = (!empty($languages['delivery']['label'][$this->config->get('config_language_id')]) ? $languages['delivery']['label'][$this->config->get('config_language_id')] : 'Delivery Date');
		
		
		if($this->config->get('module_eb_delivery_date_time_status')){
			if(version_compare(VERSION,'2.2.0.0','>=')){
				return $this->load->view('extension/eb_delivery_date_time_slot/delivery_date_time_slot_js', $data);
			}else{
				if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/extension/eb_delivery_date_time_slot/delivery_date_time_slot_js.tpl')) {
					return $this->load->view($this->config->get('config_template') . '/template/extension/eb_delivery_date_time_slot/delivery_date_time_slot_js.tpl', $data);
				} else {
					return $this->load->view('default/template/extension/eb_delivery_date_time_slot/delivery_date_time_slot_js.tpl', $data);
				}
			}
		}
	}
	
	public function gettimeslot(){
		if($this->config->get('module_eb_delivery_date_time_zone_id')){
			date_default_timezone_set($this->config->get('module_eb_delivery_date_time_zone_id')); 	
		}
		
		$json=array();
		if(isset($this->request->post['delivery_date'])){
			$delivery_date = $this->request->post['delivery_date'];
		}else{
			$delivery_date = '';
		}
		$this->session->data['eb_delivery_date'] = (isset($this->request->post['delivery_date']) ? $this->request->post['delivery_date'] : '');
		
		$today =  strtotime(date("Y-m-d H:i:s"));
		$todayweekday =  date('l',strtotime($delivery_date));
		
		$timeslots = $this->config->get('module_eb_delivery_date_time_time_slot');
		if($this->config->get('module_eb_delivery_date_time_layout')){
			$json['html']= '';
		}else{
			$json['html']= '<option value="">'.$this->language->get('text_select').'</option>';
		}
		foreach($timeslots as $slot){
			if(isset($slot['store']) && in_array($this->config->get('config_store_id'),$slot['store'])){
				if(isset($slot['weekofdays']) && in_array($todayweekday,$slot['weekofdays'])){
					$cost = $slot['cost'];
					$start_date = strtotime($delivery_date.' '.$slot['from']);
					$end_date = strtotime($delivery_date.' '.$slot['to']);
					
					if($this->config->get('module_eb_delivery_date_time_time_format')){
						$from = date('H:i',$start_date);
						$to = date('H:i',$end_date);
					}else{
						$from = $slot['from'];
						$to = $slot['to'];
					}
					
					if($start_date > $today){
						
					  if($this->config->get('module_eb_delivery_date_time_layout')){
							$json['html'] .= '<div class="radio"><label><input type="radio" id="deliverytime" name="delivery_time" value="'.$from.'-'.$to.'" />'.$from.' - '.$to.  ($cost ? ' --- <b>' .$this->currency->format($cost,$this->session->data['currency']) .'</b>' : '') . '</label></div>';
						}else{
							$json['html'] .= '<option value="'.$from.'-'.$to.'">'.$from.' - '.$to. ($cost ? ' --- ' .$this->currency->format($cost,$this->session->data['currency']) : '') . '</option>';
						}
						
					$json['option'][]= array(
					'from'    => $from,
					'to' => $to,
				    	);	
					}
					
				}
			}
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
	
	public function timeslotupdate(){
		$json=array();
		$this->session->data['eb_delivery_time'] = (isset($this->request->post['delivery_time']) ? $this->request->post['delivery_time'] : '');
		
		print_r(json_encode($json));
	}
}
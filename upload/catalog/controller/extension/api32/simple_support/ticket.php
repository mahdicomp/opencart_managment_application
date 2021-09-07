<?php
	class ControllerExtensionApi32SimpleSupportTicket extends Controller {
		
		private $error = array();
		
		public function index() {
		     if($this->valid($this->request->get['token'])){
		   $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
			$this->customer->loginWithId($this->request->post['customer_id']);
			
			
			$this->language->load('simple_support/ticket');

		
			
			$this->load->model('simple_support/ticket');
			
			
			
			
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			} else {
				$page = 1;
			}	
			
			if(isset($this->request->get['filter_search'])) {
				$filter_search = $this->request->get['filter_search'];
			} else {
				$filter_search = '';
			}
			
		
			
			$data['tickets'] = array();
			
			$filter_data = array(		
				'filter_search'	=> $filter_search,		  
				'sort'  => 'date_added',
				'order' => 'ASC',
				'start' => ($page - 1) * 10,
				'limit' => 10
			);
			
			$data['ticket_total'] = $this->model_simple_support_ticket->getTotalViewed();
			
			$results = $this->model_simple_support_ticket->getTickets($filter_data);
			
			foreach ($results as $result) {
				$data['tickets'][] = array(
					'simple_support_ticket_id'	=> $result['simple_support_ticket_id'],
					'ticket_id'      			=> $result['ticket_id'],
					'subject'      				=> $result['subject'],
					'department_name'			=> $result['department_name'],
					'status_name'				=> $result['status_name'],
					'viewed'			    	=> $result['viewed'],
					'color_code'				=> $this->getcode($result['color_name']),
					'date_added' 				=> ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'date_modified'  			=> ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_modified'])) : date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
					'href'        				=> $this->url->link('simple_support/ticket/info', 'simple_support_ticket_id=' . $result['simple_support_ticket_id'], 'SSL')
				);
			}	
			
			
				$data['success'] = '';
			
			
		
			
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
		public function new_ticket() {
			
			 if($this->valid($this->request->get['token'])){
			        $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
			$this->language->load('simple_support/ticket');

			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('simple_support/ticket');
			
		
				$this->customer->loginWithId($this->request->post['customer_id']);
			
			$data['departments'] = $this->model_simple_support_ticket->getDepartments();
			
			
			
			if ($this->request->server['REQUEST_METHOD'] == 'POST'  && isset($this->request->post['department_id']) ){
			
		
				if ((utf8_strlen($this->request->post['subject']) < 3) || (utf8_strlen($this->request->post['subject']) > 256)) {
					$data['error'] = $this->language->get('error_subject');
				}
						
	
			if (utf8_strlen($this->request->post['description']) < 3) {
					$data['error']= $this->language->get('error_description');
			}
			if(isset($data['error'])){
			    
			    
			}else{
			    $this->request->post['filename']="";
			  // print_r($this->request->post);
				$this->model_simple_support_ticket->addTicket($this->request->post);
	
				$data['success'] = $this->language->get('text_success');
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
		$this->response->setOutput(json_encode($data));	
    				
		}
		}
		
		public function info() {
		     if($this->valid($this->request->get['token'])){
		            $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
				$this->customer->loginWithId($this->request->post['customer_id']);
			$this->language->load('simple_support/ticket');

			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('simple_support/ticket');
			
			if(isset($this->request->post['simple_support_ticket_id'])) {
				$simple_support_ticket_id = $this->request->post['simple_support_ticket_id'];
			} else {
				$simple_support_ticket_id = 0;
			}
			
			
		
			
			$this->model_simple_support_ticket->addViewed($simple_support_ticket_id,1);
			
				if ($this->request->server['REQUEST_METHOD'] == 'POST'  && isset($this->request->post['description']) ){
			
						
	
			if (utf8_strlen($this->request->post['description']) < 2) {
					$data['error']= $this->language->get('error_description');
			}
			if(isset($data['error'])){
			    
			    
			}else{
				
				$this->model_simple_support_ticket->addHistory($simple_support_ticket_id, $this->request->post);
	
				$data['success'] = $this->language->get('text_update_ticket');
			}
				}
			
	        	$result = $this->model_simple_support_ticket->getTicketInfo($simple_support_ticket_id);
	        //	print_r($result);
	        		$data['simple_ticket_info'][] =array(
					'simple_support_ticket_id'   	=> $result['simple_support_ticket_id'],
					'ticket_id'   	=> $result['ticket_id'],
			     	'user_id'   	=> $result['user_id'],
					'customer_id'   	=> $result['customer_id'],
					'viewed'				=> $result['viewed'],
					'status_name'   	=> $result['status_name'],
					'department_name'=>$result['department_name'],
					'description'   	=> $result['description'],
					'subject'							=> $result['subject'],
					'simple_support_ticket_status_id'							=> $result['simple_support_ticket_status_id'],
					'date_modified' 							=> ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_modified'])) : date($this->language->get('date_format_short'), strtotime($result['date_modified'])),
	        		'date_added' 							=>($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added']))
	        	);
				$data['histories'] = array();
					
			if (isset($this->request->post['page'])) {
				$page = $this->request->post['page'];
			} else {
				$page = 1;
			}	
				
			$results = $this->model_simple_support_ticket->getTicketHistories($simple_support_ticket_id, ($page - 1) * 10, 10);
	      		
			foreach ($results as $result) {
				
				if($result['customer_id']) {
					$name = $this->customer->getFirstName() . " " . $this->customer->getLastName();
					
				} else {
					$info = $this->model_simple_support_ticket->getUser($result['user_id']);
					
					$name = $info['firstname'] . " " . $info['lastname'];
				}
				
				$images = $this->model_simple_support_ticket->getImages($result['simple_support_ticket_history_id']);
				
				$ticket_images = array();
				
				foreach($images as $image) {
					$ticket_images[] = array(
						'value' => utf8_substr($image['image'], 0, utf8_strrpos($image['image'], '.')),
						'href'  => $this->url->link('simple_support/ticket/download', 'simple_support_ticket_images_id=' . $image['simple_support_ticket_images_id'], 'SSL')
					);					
				}
				
	        	$data['histories'][] = array(
					'simple_support_ticket_history_id'   	=> $result['simple_support_ticket_history_id'],
					'user_id'   	=> $result['user_id'],
					'name'   								=> $name,
					'ticket_images'							=> $ticket_images,
					'description'							=> $result['description'],
	        		'date_added' 							=>  date($this->config->get('config_shamsidate_format'), strtotime($result['date_added']))
	        	);
	      	}	
			
			
			
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
		
		public function history() {
		     if($this->valid($this->request->get['token'])){
		            $this->load->controller('extension/api32/common/language');
    	$this->load->controller('extension/api32/common/currency');
			$this->language->load('simple_support/ticket');
		
			$this->load->model('simple_support/ticket');
					
			$data['text_no_results'] = $this->language->get('text_no_results');
			
			if (isset($this->request->get['page'])) {
				$page = $this->request->get['page'];
			} else {
				$page = 1;
			}  
			
			$data['histories'] = array();
				
			$results = $this->model_simple_support_ticket->getTicketHistories($this->request->post['simple_support_ticket_id'], ($page - 1) * 10, 10);
	      		
			foreach ($results as $result) {
				
				if($result['customer_id']) {
					$name = $this->customer->getFirstName() . " " . $this->customer->getLastName();
					
				} else {
					$info = $this->model_simple_support_ticket->getUser($result['user_id']);
					
					$name = $info['firstname'] . " " . $info['lastname'];
				}
				
				$images = $this->model_simple_support_ticket->getImages($result['simple_support_ticket_history_id']);
				
				$ticket_images = array();
				
				foreach($images as $image) {
					$ticket_images[] = array(
						'value' => utf8_substr($image['image'], 0, utf8_strrpos($image['image'], '.')),
						'href'  => $this->url->link('simple_support/ticket/download', 'simple_support_ticket_images_id=' . $image['simple_support_ticket_images_id'], 'SSL')
					);					
				}
				
	        	$data['histories'][] = array(
					'simple_support_ticket_history_id'   	=> $result['simple_support_ticket_history_id'],
					'name'   								=> $name,
					'ticket_images'							=> $ticket_images,
					'description'							=> $result['description'],
	        		'date_added' 							=> date($this->config->get('config_shamsidate_format'), strtotime($result['date_added']))
	        	);
	      	}			
			
			
            
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
		
		public function upload() {
    		$this->load->language('simple_support/ticket');
    
    		$json = array();
    
    		if (!empty($this->request->files['file']['name']) && is_file($this->request->files['file']['tmp_name'])) {
    			// Sanitize the filename
    			$filename = basename(preg_replace('/[^a-zA-Z0-9\.\-\s+]/', '', html_entity_decode($this->request->files['file']['name'], ENT_QUOTES, 'UTF-8')));
    
    			// Validate the filename length
    			if ((utf8_strlen($filename) < 3) || (utf8_strlen($filename) > 64)) {
    				$json['error'] = $this->language->get('error_filename');
    			}
    
    			// Allowed file extension types
    			$allowed = array();
    
    			$extension_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_ext_allowed'));
    
    			$filetypes = explode("\n", $extension_allowed);
    
    			foreach ($filetypes as $filetype) {
    				$allowed[] = trim($filetype);
    			}
    
    			if (!in_array(strtolower(substr(strrchr($filename, '.'), 1)), $allowed)) {
    				$json['error'] = $this->language->get('error_filetype');
    			}
    
    			// Allowed file mime types
    			$allowed = array();
    
    			$mime_allowed = preg_replace('~\r?\n~', "\n", $this->config->get('config_file_mime_allowed'));
    
    			$filetypes = explode("\n", $mime_allowed);
    
    			foreach ($filetypes as $filetype) {
    				$allowed[] = trim($filetype);
    			}
    
    			if (!in_array($this->request->files['file']['type'], $allowed)) {
    				$json['error'] = $this->language->get('error_filetype');
    			}
    
    			// Check to see if any PHP files are trying to be uploaded
    			$content = file_get_contents($this->request->files['file']['tmp_name']);
    
    			if (preg_match('/\<\?php/i', $content)) {
    				$json['error'] = $this->language->get('error_filetype');
    			}
    
    			// Return any upload error
    			if ($this->request->files['file']['error'] != UPLOAD_ERR_OK) {
    				$json['error'] = $this->language->get('error_upload_' . $this->request->files['file']['error']);
    			}
    		} else {
    			$json['error'] = $this->language->get('error_upload');
    		}
    
    		if (!$json) {
    			$file = $filename . '.' . md5(mt_rand());
    
    			move_uploaded_file($this->request->files['file']['tmp_name'], DIR_UPLOAD . $file);
    
    			// Hide the uploaded file name so people can not link to it directly.
    			$this->load->model('tool/upload');
    
    			$json['code'] = $this->model_tool_upload->addUpload($filename, $file);
    
    			$json['success'] = $this->language->get('text_upload');
                
                $json['filename'] = $filename;
                
                $json['file']= $file;
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
		
			public function getcode($color_name) {
				
				
			
				switch ($color_name) {
    case "نارنجی":
        return "#ff6600";
        break;
    case "قرمز":
        return "red";
        break;
    case "سبز":
         return "green";
        break;
		case "زرد":
         return "yellow";
        break;
    
    default:
        return "#ff6600";
} 
				
				
			}
		
		
		private function valid($token) {
	    
    	    if($token==$this->config->get('storeapp_token')){
    	        return true;
    	        
    	    }else {
    	        return false;
    	    }
	   }
	}
?>
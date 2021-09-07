<?php
	class ControllerExtensionApi32SimpleSupportHome extends Controller {
		public function index() {
			$this->language->load('simple_support/home');

			$this->document->setTitle($this->language->get('heading_title'));
			
			$this->load->model('simple_support/faq_group');
			
			$data['faq_groups'] = array();
			
			$this->document->addStyle('catalog/view/theme/default/stylesheet/simple_support_custom.css');
			
			if(isset($this->request->get['simple_support_faq_group_id'])) {
				$faq_group = $this->model_simple_support_faq_group->getFaqGroup($this->request->get['simple_support_faq_group_id']);
				
				if($faq_group) {
					$faqs = array();
					$results = $this->model_simple_support_faq_group->getFaqsGroupWise($faq_group['simple_support_faq_group_id']);
					$i=0;
					foreach($results as $result) {
                        $i++;
						$faqs[] = array(
							'simple_support_faq_id'	=> $result['simple_support_faq_id'],
							'question'				=> $result['question'],
							'answer'				=> html_entity_decode($result['answer'], ENT_QUOTES, 'UTF-8'),
                            'counter'               => $i
						);	
					}	
					
					if($faqs) {
						$data['faq_groups'][] = array(
							'name'     => $faq_group['name'],
							'faqs' => $faqs,
						);
					}
					
				} 			
			} else {
				$faq_groups = $this->model_simple_support_faq_group->getFaqGroups();
				$i=0;
				foreach($faq_groups as $faq_group) {
					
					$faq_group_faqs = array();					
					$faqs = array();
			
					$results = $this->model_simple_support_faq_group->getFaqsGroupWise($faq_group['simple_support_faq_group_id']);
					
					foreach($results as $result) {
					    $i++;
						$faqs[] = array(
							'simple_support_faq_id'	=> $result['simple_support_faq_id'],
							'question'				=> $result['question'],
							'answer'				=> html_entity_decode($result['answer'], ENT_QUOTES, 'UTF-8'),
                            'counter'               => $i
						);	
					}	
					
					if($faqs) {
						$data['faq_groups'][] = array(
							'name'     => $faq_group['name'],
							'faqs' => $faqs,
						);
					}									
				}
			}
			
			//print "<pre>"; print_r($data['faq_groups']); exit;
			
			$data['text_no_faq_found'] = $this->language->get('text_no_faq_found');
			$data['text_support_ticket'] = $this->language->get('text_support_ticket');
			
			$data['support_ticket'] = $this->url->link('simple_support/ticket', '', 'SSL');
			
			$data['breadcrumbs'] = array();
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_home'),
				'href'      => $this->url->link('common/home'),
				'separator' => false
			);
	
			$data['breadcrumbs'][] = array(
				'text'      => $this->language->get('heading_title'),
				'href'      => $this->url->link('simple_support/home', '', 'SSL'),
				'separator' => $this->language->get('text_separator')
			);
			
			if (isset($this->session->data['success'])) {
				$data['success'] = $this->session->data['success'];
	
				unset($this->session->data['success']);
			} else {
				$data['success'] = '';
			}
	
			$data['heading_title'] = $this->language->get('heading_title');
			
			$data['column_left'] = $this->load->controller('common/column_left');
    		$data['column_right'] = $this->load->controller('common/column_right');
    		$data['content_top'] = $this->load->controller('common/content_top');
    		$data['content_bottom'] = $this->load->controller('common/content_bottom');
    		$data['footer'] = $this->load->controller('common/footer');
    		$data['header'] = $this->load->controller('common/header');
	
		
    			$this->response->setOutput($this->load->view('simple_support/home', $data));
    			
		}
	}
?>
<?php
class ControllerExtensionApi32VendorPlusminusReport  extends Controller {
	private $error = array();
	public function index() {
		$this->load->language('vendor/plusminus_report');
		$this->document->setTitle($this->language->get('heading_title'));
		$this->load->model('vendor/plusminus_report');	
		
		if (isset($this->request->get['filter_date_form'])) {
			$filter_date_form = $this->request->get['filter_date_form'];
		} else {
			$filter_date_form = false;
		}

		if (isset($this->request->get['filter_date_to'])) {
			$filter_date_to = $this->request->get['filter_date_to'];
		} else {
			$filter_date_to = false;
		}

		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'pay_id';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else {
			$page = 1;
		}
		
		$url = '';
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}

		if (isset($this->request->get['filter_date_form'])) {
			$url .= '&filter_date_form=' . $this->request->get['filter_date_form'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}

		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard',  true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('vendor/plusminus_report',  $url, true)
		);
		
		$data['add']	=$this->url->link('vendor/plusminus_report/add',$url,true);
		$data['delete']	=$this->url->link('vendor/plusminus_report/delete',$url,true);
		
		$data['incomes'] = array();

		$filter_data = array(
			'vendor_id' => $this->vendor->getId(),
			'filter_date_form' => $filter_date_form,
			'filter_date_to'   => $filter_date_to,
			'sort'      => $sort,
			'order' 	=> $order,
			'start' 	=> ($page - 1) * $this->config->get('config_limit_admin'),
			'limit' 	=> $this->config->get('config_limit_admin')
		);

		$this->load->model('vendor/vendor');
		$this->load->model('vendor/mybalance');
		
		$report_total = $this->model_vendor_plusminus_report->getTotalPlusminus($filter_data);
		$reports = $this->model_vendor_plusminus_report->getPlusminuses($filter_data);
		
	 	foreach($reports as $report) {
		 	$seller_info = $this->model_vendor_vendor->getVendor($report['vendor_id']);
		 	if(isset($seller_info['display_name'])) {
		 		$sellername = $seller_info['display_name'];
		 	} else {
		 		$sellername='';
		 	}

			$data['incomes'][] = array(
				'vendor_id' 		=> $report['vendor_id'],
				'amount' 			=> $this->currency->format($report['amount'], $this->config->get('config_currency')),
				'date_added' 		=> $report['date_added'],
				'payment_method' 	=> $report['payment_method'],
				'sellername' 		=> $sellername,
			);
	 	}
   		
		$data['heading_title']          = $this->language->get('heading_title');
		$data['text_list']           	= $this->language->get('text_list');
		$data['text_no_results'] 		= $this->language->get('text_no_results');
		$data['text_confirm']			= $this->language->get('text_confirm');
		$data['text_none'] 				= $this->language->get('text_none');
	 	$data['text_enable']            = $this->language->get('text_enable');
		$data['text_disable']           = $this->language->get('text_disable');
		$data['text_select']            = $this->language->get('text_select');

		$data['entry_from']             = $this->language->get('entry_from');
		$data['entry_to']               = $this->language->get('entry_to');
		$data['entry_t_amount']         = $this->language->get('entry_t_amount');
		$data['entry_s_amount']         = $this->language->get('entry_s_amount');
		$data['entry_a_amount']         = $this->language->get('entry_a_amount');

		$data['column_seller']		    = $this->language->get('column_seller');
		$data['column_tamount']			= $this->language->get('column_tamount');
		$data['column_samount']			= $this->language->get('column_samount');
		$data['column_admin_amount']	= $this->language->get('column_admin_amount');
		$data['column_amount']			= $this->language->get('column_amount');
		$data['column_payment_method']	= $this->language->get('column_payment_method');
		$data['column_remaining']		= $this->language->get('column_remaining');
		$data['column_date']			= $this->language->get('column_date');
		$data['column_action']			= $this->language->get('column_action');

		$data['button_delete']          = $this->language->get('button_delete');
		$data['button_filter']          = $this->language->get('button_filter');
		$data['button_pay']             = $this->language->get('button_pay');
		$data['text_confirm']           = $this->language->get('text_confirm');
		$data['name']                   = $this->language->get('name');

		$filter1=array(
			'vendor_id' 	=> $this->vendor->getId(),
		);

		$data['total'] = $this->model_vendor_mybalance->getVendorTotal($filter1);

		$data['totalcommission'] = $this->model_vendor_mybalance->getTotalAmount($filter1);
		
		$data['totalamount'] = $data['total'];

		$data['payamount'] = $this->model_vendor_mybalance->getAmount($filter1);
		
		$seller_info = $this->model_vendor_mybalance->getVendorOrder($this->vendor->getId());		
		if(!empty($seller_info['tax'])){
			$seller_tax = $seller_info['tax'];
		} else{
			$seller_tax =0;
		}
		
		if(!empty($seller_info['tmdshippingcost'])){
			$tmdshippingcost = $seller_info['tmdshippingcost'];
		} else{
			$tmdshippingcost =0;
		}
		
		
		$totalcommissions = $this->model_vendor_mybalance->getTotalCommissionamount($filter_data,$this->vendor->getId());
		
		
		$remaining_amounts = $data['totalamount']-$data['payamount']+$seller_tax+$tmdshippingcost-$totalcommissions;
		
		$data['remaining_amount'] = $this->currency->format($remaining_amounts,$this->session->data['currency']);
		
		
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];

			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}

		if (isset($this->request->post['selected'])) {
			$data['selected'] = (array)$this->request->post['selected'];
		} else {
			$data['selected'] = array();
		}
		
		$url = '';
				
		if (isset($this->request->get['filter_date_form'])) {
			$url .= '&filter_date_form=' . $this->request->get['filter_date_form'];
		}
		
		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}
		
		if ($order == 'ASC') {
			$url .= '&order=DESC';
		} else {
			$url .= '&order=ASC';
		}

		if (isset($this->request->get['page'])) {
			$url .= '&page=' . $this->request->get['page'];
		}
		
		$url = '';

		if (isset($this->request->get['filter_date_form'])) {
			$url .= '&filter_date_form=' . $this->request->get['filter_date_form'];
		}

		if (isset($this->request->get['filter_date_to'])) {
			$url .= '&filter_date_to=' . $this->request->get['filter_date_to'];
		}
		
		if (isset($this->request->get['sort'])) {
			$url .= '&sort=' . $this->request->get['sort'];
		}
		if (isset($this->request->get['order'])) {
			$url .= '&order=' . $this->request->get['order'];
		}
				       
		$pagination 		= new Pagination();
		$pagination->total 	= $report_total;
		$pagination->page  	= $page;
		$pagination->limit 	= $this->config->get('config_limit_admin');
		$pagination->url   	= $this->url->link('vendor/plusminus_report', $url . '&page={page}', true);
		$data['pagination'] = $pagination->render();

		$data['results'] = sprintf($this->language->get('text_pagination'), ($report_total) ? (($page - 1) * $this->config->get('config_limit_admin')) + 1 : 0, ((($page - 1) * $this->config->get('config_limit_admin')) > ($report_total - $this->config->get('config_limit_admin'))) ? $report_total : ((($page - 1) * $this->config->get('config_limit_admin')) + $this->config->get('config_limit_admin')), $report_total, ceil($report_total / $this->config->get('config_limit_admin')));
		
		$data['filter_date_form']		= $filter_date_form;
		$data['filter_date_to']		= $filter_date_to;
		$data['sort']		= $sort;
		$data['order']		= $order;
						
		$data['header'] 		= $this->load->controller('vendor/header');
		$data['column_left'] 	= $this->load->controller('vendor/column_left');
		$data['footer'] 		= $this->load->controller('vendor/footer');
		
		$this->response->setOutput($this->load->view('vendor/plusminus_report', $data));
	}		
}
?>
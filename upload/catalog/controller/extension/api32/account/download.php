<?php
class ControllerExtensionApi32AccountDownload extends Controller {
	public function index() {
		if($this->valid($this->request->get['token'])){

    	$json = array();


		$this->load->language('account/download');

		$this->customer->loginWithId($this->request->post['customer_id']);

		$this->load->model('account/download');
		
		
		$this->load->model('extension/api/account/download');
		
        $token = $this->model_extension_api_account_download->gettoken();

		if (isset($this->request->post['page'])) {
			$page = $this->request->post['page'];
		} else {
			$page = 1;
		}

		$data['downloads'] = array();

		$download_total = $this->model_account_download->getTotalDownloads();

		$results = $this->model_account_download->getDownloads(($page - 1) * 10, 10);

		foreach ($results as $result) {
			if (file_exists(DIR_DOWNLOAD . $result['filename'])) {
				$size = filesize(DIR_DOWNLOAD . $result['filename']);

				$i = 0;

				$suffix = array(
					'B',
					'KB',
					'MB',
					'GB',
					'TB',
					'PB',
					'EB',
					'ZB',
					'YB'
				);

				while (($size / 1024) > 1) {
					$size = $size / 1024;
					$i++;
				}

				$data['downloads'][] = array(
					'order_id'   => $result['order_id'],
					'date_added' => ($this->language->get('code') == 'fa') ? jdate($this->config->get('config_shamsidate_format'), strtotime($result['date_added'])) : date($this->language->get('date_format_short'), strtotime($result['date_added'])),
					'name'       => $result['name'],
					'size'       => round(substr($size, 0, strpos($size, '.') + 4), 2) . $suffix[$i],
				// 	'href'       => $this->url->link('extension/api32/account/download/download', 'download_id=' . $result['download_id'], true)
                    'href'       => str_replace('&amp;','&',$this->url->link('extension/api32/account/download/download', 'download_id=' . $result['download_id'] . '&token=' . $token, true))
				);
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

	public function download() {
	    $this->load->controller('extension/api32/common/language');

        //echo 21;
		$this->load->model('account/download');

		$this->load->model('extension/api/account/download');
		
        $customer_id = $this->model_extension_api_account_download->getCustomerid($this->request->get['token']);
        // echo $customer_id;
        // echo $this->request->get['token'];
        $this->customer->loginWithId($customer_id);
		if (isset($this->request->get['download_id'])) {
			$download_id = $this->request->get['download_id'];
		} else {
			$download_id = 0;
		}

		$download_info = $this->model_account_download->getDownload($download_id);
		print_r($download_info);

		if ($download_info) {
			$file = DIR_DOWNLOAD . $download_info['filename'];
			$mask = basename($download_info['mask']);

			if (!headers_sent()) {
				if (file_exists($file)) {
					header('Content-Type: application/octet-stream');
					header('Content-Disposition: attachment; filename="' . ($mask ? $mask : basename($file)) . '"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header('Pragma: public');
					header('Content-Length: ' . filesize($file));

					if (ob_get_level()) {
						ob_end_clean();
					}

					readfile($file, 'rb');

					exit();
				} else {
					exit('Error: Could not find file ' . $file . '!');
				}
			} else {
				exit('Error: Headers already sent out!');
			}
		} else {
		
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
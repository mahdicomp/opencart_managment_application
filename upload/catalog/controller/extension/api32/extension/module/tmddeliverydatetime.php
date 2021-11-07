<?php
class ControllerExtensionApi32ExtensionModuleTmddeliverydatetime extends Controller {

	public function index() {

	// 15 05 2019 //
				$data['tmddeldatetime_status'] 	= $this->config->get('module_tmddeliverydatetime_status');
			/*29 04 2020 */
				$data['want_to_comment']= $this->config->get('module_tmddeliverydatetime_want_to_comment');
				$data['datestatus'] 	= $this->config->get('module_tmddeliverydatetime_datestatus');
				$data['timereq'] 	= $this->config->get('module_tmddeliverydatetime_timestatus');
				$data['typetimes'] 		= $this->config->get('module_tmddeliverydatetime_typetime');
				$data['datedeactives']=array();
				$datedeactives = $this->config->get('module_tmddeliverydatetime_datedeactive');
				if(!empty($datedeactives)){
				foreach ($datedeactives as $deactive) {
					$data['datedeactives'][]='"'.$deactive['de_date'].'"';
				}
				}
				if(!empty($data['datedeactives'])) {
					$data['datedeactive'] = implode(',',$data['datedeactives']);
				}else{
					$data['datedeactive'] ='';
				}

				$data['selecttimes']=array();
				$deliverytimes = $this->config->get('module_tmddeliverydatetime_delivery_time');
				if(!empty($deliverytimes)){
				foreach ($deliverytimes as $time) {
					$data['selecttimes'][]=array('time' => $time['time']);
				}
				}
				if(!empty($this->config->get('module_tmddeliverydatetime_store'))) {
					$data['tmddeldatetime_store'] = $this->config->get('module_tmddeliverydatetime_store');
				}else{
					$data['tmddeldatetime_store'] =array();
				}

				$data['configstore'] = $this->config->get('config_store_id');
				if (in_array($data['configstore'], $data['tmddeldatetime_store'])) {
					$data['tmdstore'] = $data['tmddeldatetime_store'];
				} else {
					$data['tmdstore'] ='';
				}

				$tmdtimezone = $this->config->get('module_tmddeliverydatetime_timezone');
				date_default_timezone_set($tmdtimezone);

				$cutoftime 	= $this->config->get('module_tmddeliverydatetime_cutoftime');
				$datestart 	= $this->config->get('module_tmddeliverydatetime_datestart');
				$dateend 	= $this->config->get('module_tmddeliverydatetime_dateend');

				if ((date('H') >= $cutoftime) && $cutoftime!=0) {
					$i=$datestart+1;
					$data['newdate']=date('Y-m-d', strtotime(date('y-m-d') . ' +'.$i.' day'));
					$data['newdatecount']=$i;
				} else {
					$data['newdate']=date('Y-m-d', strtotime(date('y-m-d') . ' +'.$datestart.' day'));
					$data['newdatecount']=$datestart;
				}
				$data['enddate']=date('Y-m-d', strtotime(date('y-m-d') . ' +'.$dateend.' day'));

			/*29 04 2020 */

				$tmddelilables=$this->config->get('module_tmddeliverydatetime_multi');

				if(!empty($this->config->get('module_tmddeliverydatetime_week'))) {
					$data['tmddeldatetime_week'] = implode(',',$this->config->get('module_tmddeliverydatetime_week'));
				}else{
					$data['tmddeldatetime_week'] ='';
				}

				if(!empty($tmddelilables[$this->config->get('config_language_id')]['datetext'])){
					$data['entry_deliverydate'] = $tmddelilables[$this->config->get('config_language_id')]['datetext'];
				} else {
					$data['entry_deliverydate'] = $this->language->get('entry_deliverydate');
				}

				if(!empty($tmddelilables[$this->config->get('config_language_id')]['timetext'])){
					$data['entry_deliverytime'] = $tmddelilables[$this->config->get('config_language_id')]['timetext'];
				} else {
					$data['entry_deliverytime'] = $this->language->get('entry_deliverytime');
				}

			// 15 05 2019 //
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
?>
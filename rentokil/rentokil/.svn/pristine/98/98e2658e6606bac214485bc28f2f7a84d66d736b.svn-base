<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_qualityservice {

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('qualityservice_model');
	}

	public function get_quality_accounts(){
		return $this->CI->qualityservice_model->get_quality_accounts();
	}
	
	public function call_quality_service($acc_codes, $start_dates, $end_dates,$last_update_flag) {
		$client = new nusoap_client ( QUALITY_SERVICE_URL, false );
		$err = $client->getError ();
		// If there is any error return false
		if ($err) {
			echo $err;
			return false;
		}
		//Pass blank end date for daily sync and end date for sync
		$final_end_dates = ($last_update_flag) ? "": $end_dates;
		
		// $param = array('chr_DateTimeStamp' => '27/11/2013 12:51:00,27/11/2013 12:51:00','chr_acccode'=>'666,494','chr_endDate' => '01/12/2013 12:51:00,01/12/2013 12:51:00');
		$param = array ('chr_DateTimeStamp' => $start_dates,'chr_acccode' => $acc_codes,'chr_endDate' => $final_end_dates);
		// 		echo "<pre>";
		// 		print_r($param);
		$client->timeout = 100;
		$client->response_timeout = 100;
	
		// $result = $client->call('wsvisits', $param, 'urn:wsvisits:wsvisits', '', false, true);
		$result = $client->call('wsvisits_v2', $param, 'urn:wsIF:wsIF', '', false, true );
		echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
		echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
		// Check for a fault
		if ($client->fault) {
			print_r($result);
			return false;
		} else {
			// Check for errors
			$err = $client->getError();
			if ($err) {
				echo $err;
				return false;
			} else {
				// Process the data
				$result['acc_codes'] = $acc_codes;
				$date_arr = explode(",",$end_dates);
				$result['end_date'] = $date_arr[0];
				$result['last_update_flag']= $last_update_flag;
				return $this->process_quality_audit_data($result); // process quality audit data.
			}
		}
		// 		 echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
		// 		 echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
		// 		 echo '<h2>Debug</h2><pre>' . htmlspecialchars($client->debug_str, ENT_QUOTES) . '</pre>';
	}
	
	public function process_quality_audit_data($data){
		return $this->CI->qualityservice_model->process_quality_audit_data($data);
	}
	public function update_qaacc_last_recived($acccodes,$end_date){
		return $this->CI->qualityservice_model->update_qaacc_last_recived($acccodes, $end_date);
	}
}
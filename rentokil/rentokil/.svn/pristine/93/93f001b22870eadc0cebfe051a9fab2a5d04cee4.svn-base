<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'business/business_qualityservice.php';

class qualityservice extends CI_Controller {
	public function __construct() {
		parent::__construct ();
		$this->load->library ( "nusoap_lib" );
	}
	
	private function getacccodes(){
		$client = new nusoap_client ( QUALITY_SERVICE_URL, false );
		$err = $client->getError ();
		// If there is any error return false
		if ($err) {
			echo $err;
		}
		$param = array ('CHR_name' => '');
		$result = $client->call('wscuscodes', $param, 'urn:wsIF:wsIF', '', false, true );
		echo '<h2>Request</h2><pre>' . htmlspecialchars($client->request, ENT_QUOTES) . '</pre>';
		echo '<h2>Response</h2><pre>' . htmlspecialchars($client->response, ENT_QUOTES) . '</pre>';
		
		
		
		
	}
	// Index function...
	public function index() {
		
		$return_flag = false;
		// Fetch all account codes and last updated date time and current date time from
		$bw_obj = new Business_qualityservice();
		$input_params = $bw_obj->get_quality_accounts(); // process digital form request.
		if ($input_params != null && count ( $input_params ) > 0) {
			// Call webservice to fetch the data...
			$return_flag = $bw_obj->call_quality_service ( $input_params [0]->acc_codes, $input_params [0]->start_dates, $input_params [0]->end_dates, true );
		}
		
		if(!$return_flag) {
			echo "Failed to execute";
			//Update database with failed....	
			if($input_params [0]->acc_codes!= "") {
				$date_arr = explode(",",$input_params [0]->end_dates);
				$input_params = $bw_obj->update_qaacc_last_recived($input_params [0]->acc_codes,$date_arr[0]);
				}
			}else 
				echo "done";
	}
	
}


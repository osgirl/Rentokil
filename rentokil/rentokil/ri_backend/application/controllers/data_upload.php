<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Data_upload extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('customeradmin_model');
		$this->load->model('user_model');
		$this->load->model('common_model');
		$this->load->library('excel');
	}

	/* Import the data */
	public function import_data()
	{
		ob_start();
		$this->load->library('uploadhandler');
		//$upload_handler = new Uploadhandler();

		// upload yet to be done
		if (!empty($_POST['session_id']))
		{
			$user_session = $_POST['session_id'];
			if(!validate_session($user_session))	// Session validation
			{
				$error_obj = new stdClass();
				$error_obj->error = TRUE;
				$error_obj->error_msg = INVALID_SESSION;
				$error_obj->session_status = FALSE;
				echo json_encode($error_obj);
				exit;
			}

			$data = $this->security->xss_clean($_POST);

			$data['upload_file_path']  = $this->session->userdata('UploadFilePath');
			$this->session->unset_userdata('UploadFilePath');
			switch ($data['import_type']){
				/* Setup Data */
				case "setup":
					$data['from'] = FROM_IMPORT_SETUP_ENTITIES;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					/*validate profile access */
					if(!validate_cadmin_profile_access(SETUP_DATA_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN))	// validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_setup_file($data);
					break;
					/* HH Reports */
				case "hh":
					$data['from'] = FROM_IMPORT_HH_REPORTS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					/*validate profile access */
					if(!validate_cadmin_profile_access(HH_DATA_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN)) // validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_hh_file($data);
					break;
					/* NHH Reports */
				case "nhh":
					$data['from'] = FROM_IMPORT_NHH_REPORTS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					/*validate profile access */
					if(!validate_cadmin_profile_access(NHH_DATA_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN)) // validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_nhh_file($data);
					break;
					/* Target Data */
				case "target":
					$data['from'] = FROM_IMPORT_TARGET_DATA;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					/*validate profile access */
					if(!validate_cadmin_profile_access(TARGET_DATA_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN))	// validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_target_file($data);
					break;
					/* School Document */
				case "school_document":
					$data['from'] = USER_FROM_IMPORT_SCHOOL_DOCUMENTS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
									
					if(!validate_role(USER))	// validating the user role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_school($data))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_school_document_file($data);
					break;
					/* Admin School Document */
				case "school_document_admin":
					$data['from'] = FROM_IMPORT_SCHOOL_DOCUMENTS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$export_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					/*validate profile access */
					if(!validate_cadmin_profile_access(DOCUMENTS_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN))	// validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_school($data))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_school_document_file($data);
					break;
					/* Energy Document */
				case "energy_document":
					$data['from'] = USER_FROM_IMPORT_ENERGY_DOCUMENTS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
										
					if(!validate_role(USER))	// validating the user admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_energy_document_file($data);
					break;
					/* Admin Energy document */
				case "energy_document_admin":
					$data['from'] = FROM_IMPORT_ENERGY_DOCUMENTS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					/*validate profile access */
					if(!validate_cadmin_profile_access(ENERGY_DOCUMENTS_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN))	// validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_energy_document_file($data);
					break;
					/* Pupils Import */
				case "pupils":
					$data['from'] = FROM_IMPORT_PUPILS;
					$data['msg'] = LOG_WRITE;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					/*validate profile access */
					if(!validate_cadmin_profile_access(PUPIL_IMPORT_ACCESS))
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = TRUE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN))	// validating the customer admin role
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						//ob_start();
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_pupils_file($data);
					break;
				default:
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = IMPORT_TYPE_INVALID;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					break;
			}
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = FALSE;
			echo json_encode($error_obj);
			exit;
		}
	}

	/*Generting the excel file*/
	public function export_data()
	{
		/*$_POST = array(
				'session_id' => '25d4769194e10106ae65220ddf6045ad',
				'export_type' => 'export_quality_auditor_filter',
				'start_date' => '2013-01-24 08:55:18', 
				'end_date' => '2013-01-30 22:22:08', 
				'ac_id' => '1', 
				'st' => '1', 
				'ar' => '1', 
				'sar' => '1', 
				'p' => '"Corridors", "Kitchen, Vending, and Breakout Areas"', 
				'au' => '1'
				);*/
		
		/*$_POST = array(
				'session_id' => '37e94fe2c070f0960658a406feddcec2',
				'export_type' => 'export_daily_meal_order_custom',
				'start_date' => '2013-11-13', 
				'ft' => '1', 
				'p' => '1,2', 
				'w' => '1,2,3,4,5,6,7,8', 
				'dw' => '0,1,2,3', 
				'mt' => '0,1'
				);*/
		
		if (!empty($_POST['session_id']))	//post method validation for session
		{
			$user_session = $_POST['session_id'];
			if(!validate_session($user_session))	//check whether session is valid or not
			{
				$error_obj = new stdClass();
				$error_obj->error = TRUE;
				$error_obj->error_msg = INVALID_SESSION;
				$error_obj->session_status = FALSE;
				echo json_encode($error_obj);
				exit;
			}
				
			//$data = $this->security->xss_clean($_POST);
			/*$_POST['contract_id'] = 80;
			$_POST['export_type'] = "order_items";
			$_POST['start_date'] = "2013-05-12";
			$_POST['end_date'] = "2013-06-28";*/
			$data = $_POST;
			
			//print_r($_POST);
			/*$data['contract_id'] = 47;
			 $data['export_type'] = "pupils";
			 $data['school_id'] = 99;*/

			/*$data['contract_id'] = "47";
			 $data['export_type'] = "payment_items";
			 $data['start_date'] = "2013-05-12";
			 $data['end_date'] = "2013-06-12";*/		
			
			switch ($data['export_type']){
				case "pupils":
					$data['from'] = FROM_EXPORT_PUPILS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					if(!validate_role(CUSTOMER_ADMIN))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_school($data))	//validating the school id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_pupils($data);	//Function to generate the .csv file for pupils id
					break;
					
				case "export_school_pupils":
					
					$data['from'] = FROM_EXPORT_SCHOOL_PUPILS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_school($data))	//validating the school id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
						
					$this->process_export_pupils($data);	//Function to generate the .csv file for pupils id
					break;

				case "chart":
					$data['from'] = FROM_EXPORT_HIGH_CHARTS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}		
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_highcharts($data);	//Function to generate the .csv file for pupils id
					break;

				case "payment_items":
					$data['from'] = FROM_EXPORT_PAYMENT_ITEMS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(CUSTOMER_ADMIN))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_payment_items($data);
					break;
				
				case "order_items":
					$data['from'] = FROM_EXPORT_ORDER_ITEMS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(CUSTOMER_ADMIN))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_order_items($data);
					break;
				
				case "batch_order_items":
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = FROM_EXPORT_BATCH_ORDER_ITEMS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_order_items($data);
					break;
				
				case "digital_xml_file":
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if($data['dfid'] == '' || $data['dfid'] == NULL)
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = INPUT_DATA_MISSING;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}

					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_digital_xml_file($data);
					break;
				
				case "pupil_balances":
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = FROM_EXPORT_PUPIL_BALANCES;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
						
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
						
					if(!validate_role(CUSTOMER_ADMIN))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_pupil_balances($data);
					break;
				
				case "export_digital_form":
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_digital_forms($data);
					break;
				
				case "export_df_tdm":
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_digital_forms_tdm($data);
					break;
				
				case "export_df_tdm_custom":
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_digital_forms_tdm_custom($data);
					break;
				
				case "export_quality_auditor_filter":
					
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = USER_FROM_QUALITY_AUDIT_DASHBOARD;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_quality_auditor_filter($data);
					break;
					
				case "export_daily_meal_order":
					
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
					
					$data['from'] = USER_FROM_DAILY_MEAL_ORDERS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
					
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->process_export_daily_meal_order($data);
					break;
					
				case "export_daily_meal_order_custom":
						
					$data['user_id'] = $this->session->userdata('user_info')->user_id;
					$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
						
					$data['from'] = USER_FROM_DAILY_MEAL_ORDERS;
					$data['msg'] = LOG_READ;
					$log_msg = get_session_log_message($data);
						
					if(!validate_contract($data['contract_id']))	//validating the contract id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
						
					if(!validate_role(USER))	// validating role id
					{
						// Save session log
						$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
						session_log_message_helper($log_data);
						// Save session log - ends
						$error_obj = new stdClass();
						$error_obj->error = TRUE;
						$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
						$error_obj->session_status = FALSE;
						echo json_encode($error_obj);
						exit;
					}
					// Save session log
					$log_data['message'] = $log_msg.LOG_AUTHORISED;
					session_log_message_helper($log_data);
					// Save session log - ends
					$this->export_daily_meal_order_custom($data);
					break;
			}
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = INVALID_SESSION;
			$error_obj->session_status = FALSE;
			echo json_encode($error_obj);
			exit;
		}
	}

	/* Setup Data */
	private function process_setup_file($data)
	{
		//require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		//$input_file = FILE_UPLOAD_PATH.'Data Sample - Group setup import.xls';
		$input_file = $data['upload_file_path'];
		$file_name = pathinfo($input_file,PATHINFO_BASENAME);	// Getting the file name
		//echo 'Loading file ',$file_name,' using IOFactory to identify the format<br />';
		$objReader = PHPExcel_IOFactory::createReaderForFile($input_file);
		//echo get_class($objReader);
		$file_type = PHPExcel_IOFactory::identify($input_file);
		//print_r($file_type);
		//exit;

		/* Check the file type */
		if($file_type == 'Excel5' || $file_type == 'Excel2007' )
		{
			$objReader->setReadDataOnly(TRUE);
			$objPHPExcel = $objReader->load($input_file);
		}
		else
		{
			$objReader = new PHPExcel_Reader_CSV();
			$objPHPExcel = $objReader->load($input_file);
		}
		//echo '<hr />';

		$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		//echo 'original count: '.count(array_filter(array_map('array_filter', $sheet_data))).'<br><br>';
		//$data = array_filter(array_map('array_filter', $sheet_data));
		//echo '<pre>';
		//var_dump($sheet_data);
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['file_name'] = $file_name;

		$setup_titles = array_values(array_map('trim', array_filter($sheet_data[SETUP_TITLE_ROW_NO])));
		$setup_titles_actual = unserialize(SETUP_TITLE_COLS);

		/*if(!empty($sheet_data[1]['A']) && !empty($sheet_data[1]['B']) && !empty($sheet_data[1]['C']) && !empty($sheet_data[1]['D']) && !empty($sheet_data[1]['E']) && !empty($sheet_data[1]['F']) && !empty($sheet_data[1]['G']))
		 {*/
		// Column name check
		/*$contract_name = trim($sheet_data[1]['A']);
		 $division = trim($sheet_data[1]['B']);
		 $sub_division = trim($sheet_data[1]['C']);
		 $site_name = trim($sheet_data[1]['D']);
		 $mrn = trim($sheet_data[1]['E']);
		 $utility = trim($sheet_data[1]['F']);
		 $hh_nhh = trim($sheet_data[1]['G']);*/

		//if($contract_name == CONTRACT_NAME && $division == DIVISION && $sub_division == SUB_DIVISION && $site_name == SITE_NAME && $mrn == MRN && $utility == UTILITY && $hh_nhh == HH_NHH)
		if($this->global_column_check($setup_titles_actual,$setup_titles,$data))	//Check the column
		{
			//unset($contract_name);
			// Remove null array from import file
			foreach ($sheet_data as $sheet_row)
			{
				if(array_filter($sheet_row))
				{
					$fine_data[] = $sheet_row;
					$mrn_data[] = trim($sheet_row['E']);
				}
			}
			$mrn_data_cnt = count($mrn_data);
			$mrn_unique_cnt = count(array_unique($mrn_data));
			//echo '<pre>';
			//print_r($fine_data);
			$cnt_fine_data = count($fine_data);
			//print_r(array_map('unserialize', array_unique(array_map('serialize', $fine_data)))); exit;
			$unique_data = array_map('unserialize', array_unique(array_map('serialize', $fine_data)));
			//echo count($unique_data);
			//echo '<pre>';
			//print_r($unique_data);
			$cnt_unique_data = count($unique_data);
			if(($cnt_fine_data == $cnt_unique_data) && ($mrn_data_cnt == $mrn_unique_cnt))
			{
				$i = 0;
				unset($sheet_row);
				foreach ($unique_data as $sheet_row)
				{
					// Removing unwanted coloumns from the excel
					array_splice($sheet_row, 7);
					// Removing whitespaces in cell values
					$sheet_row = array_map('trim', $sheet_row);

					$req_data = $sheet_row;
					array_splice($req_data, 1, 2);
					$null_cnt = $this->global_check_null($req_data);

					$row_no = $i+1;

					// Null check for required columns
					if ($null_cnt > 0)
					{
						$error_msg = str_replace(REPLACE_STRING, $row_no, EMPTY_DATA);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);

						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}

					if($i > 0)
					{


						// Validating HH/NHH
						$sheet_row['G'] = strtolower($sheet_row['G']);
						if(($sheet_row['G'] != 'hh') && ($sheet_row['G'] != 'nhh'))
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, SETUP_HH_NHH_ERR);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// validating contract name with selected contract
						$contract_name = $this->customeradmin_model->get_contract_name($data['contract_id']);
						if($contract_name != $sheet_row['A'])
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_CONTRACT);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// validating utility and hh/nhh is already having kwh(energy value)
						if(!$this->utility_hh_nhh_check($sheet_row['E'],$sheet_row['F'],$sheet_row['G'],$data['contract_id']))
						{
							$error_msg = str_replace(REPLACE_STRING, $sheet_row['E'], KWH_ALREADY_EXISTS);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}
						if(empty($sheet_row['B']) && empty($sheet_row['C']))
						{
							$sheet_row['B'] = $sheet_row['A'];
							$sheet_row['C'] = $sheet_row['A'];
						}
						elseif (empty($sheet_row['B']) && !empty($sheet_row['C']))
						{
							$sheet_row['B'] = $sheet_row['A'];
						}
						elseif (!empty($sheet_row['B']) && empty($sheet_row['C']))
						{
							$sheet_row['C'] = $sheet_row['B'];
						}
						
						// To unset array data which is already existing in database
						if(!$this->setup_data_dup_check($sheet_row['B'],$sheet_row['C'],$sheet_row['D'],$sheet_row['E'],$sheet_row['F'],$sheet_row['G'],$data['contract_id']))
						{
							/*$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_DATABASE);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;*/
							unset($sheet_row);
						}
						
						// final data array
						if(isset($sheet_row))
						$final_data[] = $sheet_row;
					}
					$i++;
				}
				
				if(empty($final_data))
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
				else
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$this->customeradmin_model->import_setup_data($final_data,$data['contract_id'],$file_id,$data['cuser_id']);
	
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
				
			}
			else
			{
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = DUPLICATE_DATA;
				$this->customeradmin_model->mark_file_upload($data);

				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = DUPLICATE_DATA;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
		}
		/*else
		 {
		 $data['upload_status'] = FAILURE;
		 $data['error_msg'] = INVALID_COLUMN;
		 $this->customeradmin_model->mark_file_upload($data);

		 $import_data->error = TRUE;
		 $import_data->error_msg = INVALID_COLUMN;
		 $import_data->session_status = TRUE;
		 echo json_encode($import_data);
		 }*/
		/*}
		 else
		 {
		 $data['upload_status'] = FAILURE;
		 $data['error_msg'] = INVALID_COLUMN;
		 $this->customeradmin_model->mark_file_upload($data);

		 $import_data->error = TRUE;
		 $import_data->error_msg = INVALID_COLUMN;
		 $import_data->session_status = TRUE;
		 echo json_encode($import_data);
		 }*/
	}

	/* Validation for HH NHH Check */
	private function utility_hh_nhh_check($mrn, $utility, $hh_nhh, $contract_id)
	{
		$hh_chk_cnt_res = $this->customeradmin_model->utility_hh_check($mrn, $utility, $hh_nhh, $contract_id);
		$hh_chk_cnt = $hh_chk_cnt_res->chk_count;
		$nhh_chk_cnt_res = $this->customeradmin_model->utility_nhh_check($mrn, $utility, $hh_nhh, $contract_id);
		$nhh_chk_cnt = $nhh_chk_cnt_res->chk_count;
		if(($hh_chk_cnt > 0) || ($nhh_chk_cnt > 0))
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/* Validation for Setup data duplication */
	private function setup_data_dup_check($division, $sub_division, $site_name, $mrn, $utility, $hh_nhh, $contract_id)
	{
		$setup_data_cnt_res = $this->customeradmin_model->setup_data_dup_check($division, $sub_division, $site_name, $mrn, $utility, $hh_nhh, $contract_id);
		if($setup_data_cnt_res->dup_count > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/* Validation for Target data duplication */
	private function target_data_dup_check($target_data, $mrn, $contract_id)
	{
		$data = array_combine(unserialize(TARGET_DB_COLS), $target_data);
		$target_setup_check = $this->customeradmin_model->target_data_dup_check($data, $mrn, $contract_id);
		if($target_setup_check->chk_cnt > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/* HH File */
	private function process_hh_file($data)
	{
		//require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';
		//$input_file = FILE_UPLOAD_PATH.'Data Sample - HH data import_xls.xls';
		$input_file = $data['upload_file_path'];
		$file_name = pathinfo($input_file,PATHINFO_BASENAME);
		//echo 'Loading file ',$file_name,' using IOFactory to identify the format<br />';
		$objReader = PHPExcel_IOFactory::createReaderForFile($input_file);
		//echo get_class($objReader);
		$file_type = PHPExcel_IOFactory::identify($input_file);
		//print_r($file_type);
		//exit;

		if($file_type == 'Excel5' || $file_type == 'Excel2007' )
		{
			//$objReader->setReadDataOnly(TRUE);
			$objPHPExcel = $objReader->load($input_file);
		}
		else
		{
			$objReader = new PHPExcel_Reader_CSV();
			$objPHPExcel = $objReader->load($input_file);
		}
		//echo '<hr />';

		$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		//echo 'original count: '.count(array_filter(array_map('array_filter', $sheet_data))).'<br><br>';
		//$data = array_filter(array_map('array_filter', $sheet_data));
		//echo '<pre>';
		//var_dump($sheet_data);
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['file_name'] = $file_name;

		// test data
		//$data['import_type'] = 'HH';
		//$data['contract_id'] = 7;
		//$data['upload_file_path'] = "C:/test/test";

		$hh_titles = array_values(array_map('trim', array_filter($sheet_data[HH_TITLE_ROW_NO])));
		$hh_titles_actual = unserialize(HH_TITLE_COLS);
		/*if(!empty($sheet_data[2]['A']) && !empty($sheet_data[2]['B']) && !empty($sheet_data[2]['C']) && !empty($sheet_data[2]['D']) && !empty($sheet_data[2]['E']) && !empty($sheet_data[2]['F']) && !empty($sheet_data[2]['G']) && !empty($sheet_data[2]['H']) && !empty($sheet_data[2]['I']) && !empty($sheet_data[2]['J']) && !empty($sheet_data[2]['K']) && !empty($sheet_data[2]['L']) && !empty($sheet_data[2]['M']) && !empty($sheet_data[2]['N']) && !empty($sheet_data[2]['O']) && !empty($sheet_data[2]['P']) && !empty($sheet_data[2]['Q']) && !empty($sheet_data[2]['R']) && !empty($sheet_data[2]['S']) && !empty($sheet_data[2]['T']) && !empty($sheet_data[2]['U']) && !empty($sheet_data[2]['V']) && !empty($sheet_data[2]['W']) && !empty($sheet_data[2]['X']) && !empty($sheet_data[2]['Y']) && !empty($sheet_data[2]['Z']) && !empty($sheet_data[2]['AA']) && !empty($sheet_data[2]['AB']) && !empty($sheet_data[2]['AC']) && !empty($sheet_data[2]['AD']) && !empty($sheet_data[2]['AE']) && !empty($sheet_data[2]['AF']) && !empty($sheet_data[2]['AG']) && !empty($sheet_data[2]['AH']) && !empty($sheet_data[2]['AI']) && !empty($sheet_data[2]['AJ']) && !empty($sheet_data[2]['AK']) && !empty($sheet_data[2]['AL']) && !empty($sheet_data[2]['AM']) && !empty($sheet_data[2]['AN']) && !empty($sheet_data[2]['AO']) && !empty($sheet_data[2]['AP']) && !empty($sheet_data[2]['AQ']) && !empty($sheet_data[2]['AR']) && !empty($sheet_data[2]['AS']) && !empty($sheet_data[2]['AT']) && !empty($sheet_data[2]['AU']) && !empty($sheet_data[2]['AV']) && !empty($sheet_data[2]['AW']) && !empty($sheet_data[2]['AX']) && !empty($sheet_data[2]['AY']) && !empty($sheet_data[2]['AZ']) && !empty($sheet_data[2]['BA']) && !empty($sheet_data[2]['BB']))
		 {*/

		// Column name check
		if($this->global_column_check($hh_titles_actual,$hh_titles,$data))
		{
			/*$contract_name = trim($sheet_data[2]['A']);
			 $division = trim($sheet_data[2]['B']);
			 $sub_division = trim($sheet_data[2]['C']);
			 $site_name = trim($sheet_data[2]['D']);
			 $mrn = trim($sheet_data[2]['E']);
			 $hh_date = trim($sheet_data[2]['F']);
			 $t0030 = trim($sheet_data[2]['G']);
			 $t0100 = trim($sheet_data[2]['H']);
			 $t0130 = trim($sheet_data[2]['I']);
			 $t0200 = trim($sheet_data[2]['J']);
			 $t0230 = trim($sheet_data[2]['K']);
			 $t0300 = trim($sheet_data[2]['L']);
			 $t0330 = trim($sheet_data[2]['M']);
			 $t0400 = trim($sheet_data[2]['N']);
			 $t0430 = trim($sheet_data[2]['O']);
			 $t0500 = trim($sheet_data[2]['P']);
			 $t0530 = trim($sheet_data[2]['Q']);
			 $t0600 = trim($sheet_data[2]['R']);
			 $t0630 = trim($sheet_data[2]['S']);
			 $t0700 = trim($sheet_data[2]['T']);
			 $t0730 = trim($sheet_data[2]['U']);
			 $t0800 = trim($sheet_data[2]['V']);
			 $t0830 = trim($sheet_data[2]['W']);
			 $t0900 = trim($sheet_data[2]['X']);
			 $t0930 = trim($sheet_data[2]['Y']);
			 $t1000 = trim($sheet_data[2]['Z']);
			 $t1030 = trim($sheet_data[2]['AA']);
			 $t1100 = trim($sheet_data[2]['AB']);
			 $t1130 = trim($sheet_data[2]['AC']);
			 $t1200 = trim($sheet_data[2]['AD']);
			 $t1230 = trim($sheet_data[2]['AE']);
			 $t1300 = trim($sheet_data[2]['AF']);
			 $t1330 = trim($sheet_data[2]['AG']);
			 $t1400 = trim($sheet_data[2]['AH']);
			 $t1430 = trim($sheet_data[2]['AI']);
			 $t1500 = trim($sheet_data[2]['AJ']);
			 $t1530 = trim($sheet_data[2]['AK']);
			 $t1600 = trim($sheet_data[2]['AL']);
			 $t1630 = trim($sheet_data[2]['AM']);
			 $t1700 = trim($sheet_data[2]['AN']);
			 $t1730 = trim($sheet_data[2]['AO']);
			 $t1800 = trim($sheet_data[2]['AP']);
			 $t1830 = trim($sheet_data[2]['AQ']);
			 $t1900 = trim($sheet_data[2]['AR']);
			 $t1930 = trim($sheet_data[2]['AS']);
			 $t2000 = trim($sheet_data[2]['AT']);
			 $t2030 = trim($sheet_data[2]['AU']);
			 $t2100 = trim($sheet_data[2]['AV']);
			 $t2130 = trim($sheet_data[2]['AW']);
			 $t2200 = trim($sheet_data[2]['AX']);
			 $t2230 = trim($sheet_data[2]['AY']);
			 $t2300 = trim($sheet_data[2]['AZ']);
			 $t2330 = trim($sheet_data[2]['AA']);
			 $t2400 = trim($sheet_data[2]['BB']);

			 if($contract_name == CONTRACT_NAME && $division == DIVISION && $sub_division == SUB_DIVISION && $site_name == SITE_NAME && $mrn == MRN && $t0030 == T0030 && $t0100 == T0100 && $t0130 == T0130 && $t0200 == T0200 && $t0230 == T0230 && $t0300 == T0300 && $t0330 == T0330 && $t0400 == T0400 && $t0430 == T0430 && $t0500 == T0500 && $t0530 == T0530 && $t0600 == T0600 && $t0630 == T0630 && $t0700 )
			 {*/
			//unset($contract_name);

			// Remove null array from import file
			$j = 0;
			foreach ($sheet_data as $sheet_row)
			{
				if($j != 0)	// To skip first row empty scenario
				{
					if(array_filter($sheet_row))
					{
						$fine_data[] = $sheet_row;
						$mrn_date_data[] = trim($sheet_row['E']).trim($sheet_row['F']);
					}
				}
				else
				{
					$fine_data[] = $sheet_row;
				}
				$j++;
			}
			$mrn_date_data_cnt = count($mrn_date_data);
			$mrn_date_unique_cnt = count(array_unique($mrn_date_data));
			$cnt_fine_data = count($fine_data);
			$unique_data = array_map('unserialize', array_unique(array_map('serialize', $fine_data)));
			$cnt_unique_data = count($unique_data);

			if(($cnt_fine_data == $cnt_unique_data) && ($mrn_date_data_cnt == $mrn_date_unique_cnt))
			{
				$i = 0;
				unset($sheet_row);
				foreach ($unique_data as $sheet_row)
				{
					if($i > 1)
					{
						// Removing unwanted coloumns from the excel
						array_splice($sheet_row, 54);
						// Removing whitespaces in cell values
						$sheet_row = array_map('trim', $sheet_row);
						
						$row_no = $i+1;

						// Null check for required columns
						$req_data = $sheet_row;
						array_splice($req_data, 1, 2);
						
						$null_cnt = $this->global_check_null($req_data);
						//$req_data_cnt = count(array_filter($req_data));
						//$actual_req_cnt = count(unserialize(HH_REQUIRED_COLS));
						
						//if ($req_data_cnt != $actual_req_cnt)
						if($null_cnt > 0)
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, EMPTY_DATA);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// validating contract name with selected contract
						$contract_name = $this->customeradmin_model->get_contract_name($data['contract_id']);
						if($contract_name != $sheet_row['A'])
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_CONTRACT);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// Appending values for "Division"/"Sub-Division" if empty
						if(empty($sheet_row['B']) && empty($sheet_row['C']))
						{
							$sheet_row['B'] = $sheet_row['A'];
							$sheet_row['C'] = $sheet_row['A'];
						}
						elseif (empty($sheet_row['B']) && !empty($sheet_row['C']))
						{
							$sheet_row['B'] = $sheet_row['A'];
						}
						elseif (!empty($sheet_row['B']) && empty($sheet_row['C']))
						{
							$sheet_row['C'] = $sheet_row['B'];
						}

						// Validating whether the MRN data available in "setup" table
						if(!$this->global_setup_check($sheet_row['B'],$sheet_row['C'],$sheet_row['D'],$sheet_row['E'],NULL,$data['contract_id'],$data['import_type']))
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_SETUPDATA);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// Validating whether all numeric values are numeric
						array_splice($req_data, 0, 4);
						$pattern = '/^\d+\.?\d*$/';
						$j = 7; //removing first six columns
						$k = 0;
						$req_data_keys = array_keys($req_data);
						foreach ($req_data as $num_chk_data)
						{
							if(!preg_match('/^\d+\.?\d*$/', $num_chk_data))
							{
								//$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_VALUE_HH. ' Column ' . $j);
								$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_VALUE_COLUMN);
								$error_msg = str_replace(REPLACE_STRING_COL, $j, $error_msg);
								$data['upload_status'] = FAILURE;
								$data['error_msg'] = $error_msg;
								$this->customeradmin_model->mark_file_upload($data);

								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = $error_msg;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}
							$sheet_row[$req_data_keys[$k]] = round($num_chk_data,4);
							$k++;
							$j++;
						}
						
						$hh_data = $sheet_row;
						array_splice($hh_data, 0, 5);
						$mrn = $sheet_row['E'];
						//$hh_date = PHPExcel_Style_NumberFormat::toFormattedString($sheet_row['F'], "DD/MM/YYYY");
						$hh_date = $sheet_row['F'];
						$hh_date_arr = explode('/', $hh_date);

						// Validating hh date
						if((!$this->global_check_int($hh_date_arr[0]) || !$this->global_check_int($hh_date_arr[1]) || !$this->global_check_int($hh_date_arr[2])) || (!checkdate($hh_date_arr[1], $hh_date_arr[0], $hh_date_arr[2])) || $hh_date_arr[2] > 9999 || $hh_date_arr[2] < 1000)
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_DATE);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						$hh_date_arr = array_reverse($hh_date_arr);
						$hh_date = implode('-', $hh_date_arr);
						$hh_data['F'] = $hh_date;
						
						// Validating row for duplicate entry in database
						if(!$this->hh_data_dup_check($hh_data, $mrn, $data['contract_id']))
						{
							/*$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_DATABASE);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);

							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;*/
							unset($hh_data);
						}
						
						// final data array
						if(isset($hh_data))
						{
							$hh_data['E'] = $mrn;
							$final_data[] = $hh_data;
						}
					}
					$i++;
				}
				//echo '<pre>';
				//print_r($final_data);
				//exit;
				if(empty($final_data))
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
				else
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$this->customeradmin_model->import_hh_data($final_data,$file_id,$data['cuser_id'],$data['contract_id'],$data['import_type']);
	
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}

			}
			else
			{
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = DUPLICATE_DATA;
				$this->customeradmin_model->mark_file_upload($data);

				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = DUPLICATE_DATA;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
			/*}
			 else
			 {
			 $data['upload_status'] = FAILURE;
			 $data['error_msg'] = INVALID_COLUMN;
			 //$this->customeradmin_model->mark_file_upload($data);

			 $import_data->error = TRUE;
			 $import_data->error_msg = INVALID_COLUMN;
			 $import_data->session_status = TRUE;
			 echo json_encode($import_data);
			 }*/
		}
		/*else
		 {
		 $data['upload_status'] = FAILURE;
		 $data['error_msg'] = INVALID_COLUMN;
		 $this->customeradmin_model->mark_file_upload($data);

		 $import_data->error = TRUE;
		 $import_data->error_msg = INVALID_COLUMN;
		 $import_data->session_status = TRUE;
		 echo json_encode($import_data);
		 }*/
	}

	/* Validation for Setup data duplication*/
	private function global_setup_check($division, $sub_division, $site_name, $mrn, $utility, $contract_id,$import_type)
	{
		$global_setup_check = $this->customeradmin_model->global_setup_check($division, $sub_division, $site_name, $mrn, $utility, $contract_id,$import_type);
		if($global_setup_check->chk_cnt > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/* Validation for HH Data duplication */
	private function hh_data_dup_check($hh_data, $mrn, $contract_id)
	{
		$data = array_combine(unserialize(HH_DB_COLS), $hh_data);
		$hh_data_dup_check = $this->customeradmin_model->hh_data_dup_check($data, $mrn, $contract_id);
		if($hh_data_dup_check->chk_cnt > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/* Validation for NULL */
	private function global_check_null($cell_data)
	{
		$keys = array_keys($cell_data);
		$i = 0;
		$null_cnt = 0;
		foreach ($cell_data as $data)
		{
			if(@$data[$keys[$i]] == '')
			{
				$null_cnt++;
			}
			$i++;
		}
		return $null_cnt;
	}

	/* Validation for check whether integer or not */
	private function global_check_int($var)
	{
		/*if(is_numeric($var))
		 {
		 if(intval($var) == $var)
		 {
		 return true;
		 }
		 }
		 else
		 {
		 return false;
		 }*/
		return (is_numeric($var) ? intval($var) == $var : false);
	}

	/* Get the Numeric column name */
	private function return_numeric_column_name($sel_column, $row_no, $data)
	{
		$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_VALUE_COLUMN);
		$error_msg = str_replace(REPLACE_STRING_COL, $sel_column, $error_msg);
		$data['upload_status'] = FAILURE;
		$data['error_msg'] = $error_msg;
		$this->customeradmin_model->mark_file_upload($data);
		$import_data = new stdClass();
		$import_data->error = TRUE;
		$import_data->error_msg = $error_msg;
		$import_data->session_status = TRUE;
		echo json_encode($import_data);
		exit;
	}
	
	/* Check Column */
	private function global_column_check($actual_column,$extracted_column,$data)
	{
		if(count($extracted_column) == 0)
		{
			$error_msg = str_replace(REPLACE_STRING, 1, INVALID_COLUMN_NAME);
			$data['upload_status'] = FAILURE;
			$data['error_msg'] = $error_msg;
			$this->customeradmin_model->mark_file_upload($data);
			$import_data = new stdClass();
			$import_data->error = TRUE;
			$import_data->error_msg = $error_msg;
			$import_data->session_status = TRUE;
			echo json_encode($import_data);
			exit;
		}

		$keys = array_keys($actual_column);
		for($i=0; $i<count($actual_column); $i++)
		{
			if(empty($extracted_column[$i]))
			{
				$error_msg = str_replace(REPLACE_STRING, $keys[$i]+1, INVALID_COLUMN_NAME);
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = $error_msg;
				$this->customeradmin_model->mark_file_upload($data);
				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = $error_msg;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
				exit;
			}
			if ($extracted_column[$i] != $actual_column[$keys[$i]])
			{
				$error_msg = str_replace(REPLACE_STRING, $keys[$i]+1, INVALID_COLUMN_NAME);
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = $error_msg;
				$this->customeradmin_model->mark_file_upload($data);
				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = $error_msg;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
				exit;
			}
		}
		return TRUE;
	}

	/* NHH Data */
	private function process_nhh_file($data)
	{
		//$input_file = FILE_UPLOAD_PATH.'Real Data NHH.xls';
		$input_file = $data['upload_file_path'];
		$file_name = pathinfo($input_file,PATHINFO_BASENAME);
		$objReader = PHPExcel_IOFactory::createReaderForFile($input_file);
		$file_type = PHPExcel_IOFactory::identify($input_file);

		if($file_type == 'Excel5' || $file_type == 'Excel2007' )
		{
			//$objReader->setReadDataOnly(TRUE);			// commented for date validation issue
			$objPHPExcel = $objReader->load($input_file);
		}
		else
		{
			$objReader = new PHPExcel_Reader_CSV();
			$objPHPExcel = $objReader->load($input_file);
		}
		//echo '<hr />';

		$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		//echo '<pre>';
		//var_dump($sheet_data);
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['file_name'] = $file_name;
		// test data
		//$data['import_type'] = 'NHH';
		//$data['contract_id'] = 9;
		//$data['upload_file_path'] = "C:/test/nhh";

		// check column title
		/*$check_column = $this->check_column_title(NHH_TITLE_COLS,$sheet_data[NHH_TITLE_ROW_NO]);
		 if($check_column->error)
		 {
		 //
		 }*/

		$nhh_titles = array_values(array_map('trim', array_filter($sheet_data[NHH_TITLE_ROW_NO])));
		$nhh_titles_actual = unserialize(NHH_TITLE_COLS);

		if($this->global_column_check($nhh_titles_actual,$nhh_titles,$data))
		{
			// Remove null array from import file
			//$j = 0;
			foreach ($sheet_data as $sheet_row)
			{
				//if($j != 0)	// To skip first row empty scenario
				//{
				if(array_filter($sheet_row))
				{
					$fine_data[] = $sheet_row;
					$mrn_date_data[] = trim($sheet_row['E'])."#".trim($sheet_row['F'])."#".trim($sheet_row['G']);
				}
				//}
				//else
				//{
				//$fine_data[] = $sheet_row;
				//}
				//$j++;
			}
			$mrn_data_cnt = count($mrn_date_data);
			$mrn_data_unique = array_unique($mrn_date_data);
			$mrn_unique_cnt = count($mrn_data_unique);
			//echo '<pre>';
			//print_r($fine_data);
			$cnt_fine_data = count($fine_data);
			//print_r(array_map('unserialize', array_unique(array_map('serialize', $fine_data)))); exit;
			$unique_data = array_map('unserialize', array_unique(array_map('serialize', $fine_data)));
			//echo count($unique_data);
			//echo '<pre>';
			//print_r($unique_data);
			$cnt_unique_data = count($unique_data);

			if(($cnt_fine_data == $cnt_unique_data) && ($mrn_data_cnt == $mrn_unique_cnt))
			{
				$row_no = 1;
				unset($sheet_row);
				foreach ($unique_data as $sheet_row)
				{
					if($row_no == 1) {
						$row_no++;
						continue;
					}
					// Removing unwanted coloumns from the excel
					array_splice($sheet_row, 8);
					// Removing whitespaces in cell values
					$sheet_row = array_map('trim', $sheet_row);

					// Null check for required columns
					$req_data = $sheet_row;
					array_splice($req_data, 1, 2);
					$null_cnt = $this->global_check_null($req_data);

					if ($null_cnt > 0)
					{
						$error_msg = str_replace(REPLACE_STRING, $row_no, EMPTY_DATA);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}

					//For validating the duplicate/overlap in the excel file.
					$nhh_mrn = $sheet_row['E'];
					$nhh_date_from = $sheet_row['F'];
					$nhh_date_to = $sheet_row['G'];
					$nhh_date_from_arr = explode('/', $nhh_date_from);
					$nhh_date_to_arr = explode('/', $nhh_date_to);
					//validate the date for the first row only. for others it will done in the sub loop.
					if($row_no==2 && !$this->validate_date($nhh_date_from_arr, $nhh_date_to_arr)) {
						$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_DATE);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					} else if($row_no==2 && !$this->validate_date_range($nhh_date_from_arr, $nhh_date_to_arr))
					{
						$error_msg = str_replace(REPLACE_STRING, $row_no, DATE_RANGE_INVALID);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}
					for($j=$row_no;$j<count($mrn_data_unique); $j++){
						$mrn_from_to_arr1 = explode("#", $mrn_data_unique[$j]);
						$nhh_mrn1 = $mrn_from_to_arr1[0];
						$nhh_date_from1 = $mrn_from_to_arr1[1];
						$nhh_date_to1 = $mrn_from_to_arr1[2];
						$nhh_date_from_arr1 = explode('/', $nhh_date_from1);
						$nhh_date_to_arr1 = explode('/', $nhh_date_to1);
						if(!$this->validate_date($nhh_date_from_arr1, $nhh_date_to_arr1)) {
							$error_msg = str_replace(REPLACE_STRING, $j+1, INVALID_DATE);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}
						else if(!$this->validate_date_range($nhh_date_from_arr1, $nhh_date_to_arr1)) {
							$error_msg = str_replace(REPLACE_STRING, $j+1, DATE_RANGE_INVALID);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}
						else if ($nhh_mrn == $nhh_mrn1) {

							$nhh_date_from_i = implode('-', array_reverse($nhh_date_from_arr));
							$nhh_date_to_i = implode('-', array_reverse($nhh_date_to_arr));
							$nhh_date_from_j = implode('-', array_reverse($nhh_date_from_arr1));
							$nhh_date_to_j = implode('-', array_reverse($nhh_date_to_arr1));
							//echo $nhh_date_from_j. "  " .strtotime($nhh_date_from_j) . " <br / >"  . $nhh_date_from_i. " "  .strtotime($nhh_date_from_i) . "  <br / > " .  $nhh_date_to_i."  " .strtotime($nhh_date_to_i);
							//if((strtotime($nhh_date_from_j) >= strtotime($nhh_date_from_i) && strtotime($nhh_date_from_j) <= strtotime($nhh_date_to_i)))
							//echo "true";
							//else
							//echo "false";
							//exit;
							if((strtotime($nhh_date_from_j) >= strtotime($nhh_date_from_i) && strtotime($nhh_date_from_j) <= strtotime($nhh_date_to_i))
							|| (strtotime($nhh_date_to_j) >= strtotime($nhh_date_from_i) && strtotime($nhh_date_to_j) <= strtotime($nhh_date_to_i))
							|| (strtotime($nhh_date_from_i) >= strtotime($nhh_date_from_j) && strtotime($nhh_date_from_i) <= strtotime($nhh_date_to_j))
							|| (strtotime($nhh_date_to_i) >= strtotime($nhh_date_from_j) && strtotime($nhh_date_to_i) <= strtotime($nhh_date_to_j)))
							{
								$data['upload_status'] = FAILURE;
								$data['error_msg'] = DUPLICATE_DATA;
								$this->customeradmin_model->mark_file_upload($data);
								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = DUPLICATE_DATA;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}
						}
					}  // end  of $j

					// validating contract name with selected contract
					$contract_name = $this->customeradmin_model->get_contract_name($data['contract_id']);
					if($contract_name != $sheet_row['A'])
					{
						$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_CONTRACT);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}

					// Appending values for "Division"/"Sub-Division" if empty
					if(empty($sheet_row['B']) && empty($sheet_row['C']))
					{
						$sheet_row['B'] = $sheet_row['A'];
						$sheet_row['C'] = $sheet_row['A'];
					}
					elseif (empty($sheet_row['B']) && !empty($sheet_row['C']))
					{
						$sheet_row['B'] = $sheet_row['A'];
					}
					elseif (!empty($sheet_row['B']) && empty($sheet_row['C']))
					{
						$sheet_row['C'] = $sheet_row['B'];
					}

					// Validating whether the MRN data available in "setup" table
					if(!$this->global_setup_check($sheet_row['B'],$sheet_row['C'],$sheet_row['D'],$sheet_row['E'],NULL, $data['contract_id'],$data['import_type']))
					{
						$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_SETUPDATA);

						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}

					// Validating whether all numeric values are numeric
					$pattern = '/^\d+\.?\d*$/';
					if(!preg_match('/^\d+\.?\d*$/', $sheet_row['H']))
					{
						$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_VALUE);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}
					$sheet_row['H'] = round($sheet_row['H'],4);

					$nhh_date_from_arr = array_reverse($nhh_date_from_arr);
					$nhh_date_from = implode('-', $nhh_date_from_arr);
					$sheet_row['F'] = $nhh_date_from;
					$nhh_date_to_arr = array_reverse($nhh_date_to_arr);
					$nhh_date_to = implode('-', $nhh_date_to_arr);
					$sheet_row['G'] = $nhh_date_to;

					//Validating row for duplicate entry in database
					if(!$this->nhh_data_dup_check($sheet_row['E'],$sheet_row['F'],$sheet_row['G'],$sheet_row['H'],$data['contract_id']))
					{
						/*$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_DATABASE);

						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);

						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;*/
						unset($sheet_row);
					}
						
					if(isset($sheet_row))
					$final_data[] = $sheet_row;
					
					$row_no++;
				} // End of for each
				
				if(empty($final_data))
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
				else
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$this->customeradmin_model->import_nhh_data($final_data,$file_id,$data['cuser_id'],$data['contract_id'],$data['import_type']);
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
			}
			else
			{
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = DUPLICATE_DATA;
				$this->customeradmin_model->mark_file_upload($data);
				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = DUPLICATE_DATA;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
		}
	}

	/* Validation for NHH Data duplication */
	private function nhh_data_dup_check($mrn, $from_date, $to_date, $kwh, $contract_id)
	{
		$nhh_data_dup_check = $this->customeradmin_model->nhh_data_dup_check($mrn, $from_date, $to_date, $kwh, $contract_id);
		if($nhh_data_dup_check->chk_cnt > 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/* Data Validation */
	private function validate_date($nhh_date_from_arr, $nhh_date_to_arr) {
		$return_val = true;
		// Validating nhh date
		if(count($nhh_date_from_arr)!=3 || count($nhh_date_to_arr)!=3)
		{
			$return_val = false;
			return;
		}
		if(!checkdate($nhh_date_from_arr[1], $nhh_date_from_arr[0], $nhh_date_from_arr[2]) || !checkdate($nhh_date_to_arr[1], $nhh_date_to_arr[0], $nhh_date_to_arr[2]))
		{
			$return_val = false;
			return;
		}
		return $return_val;
	}

	/* Data Range Validation */
	private function validate_date_range($nhh_date_from_arr, $nhh_date_to_arr){
		$nhh_date_from_arr = array_reverse($nhh_date_from_arr);
		$nhh_date_from = implode('-', $nhh_date_from_arr);

		//$sheet_row['F'] = $nhh_date_from;
		$nhh_date_to_arr = array_reverse($nhh_date_to_arr);
		$nhh_date_to = implode('-', $nhh_date_to_arr);
		//$sheet_row['G'] = $nhh_date_to;

		// Validating from_date and to_date
		if(strtotime($nhh_date_to) < strtotime($nhh_date_from))
		return false;
		else
		return true;
	}

	/* Month Validation */
	private function month_validation($month)
	{
		$month_names = array("January","February","March","April","May","June","July","August","September","October","November","December");
		if (in_array(ucwords(strtolower($month)), $month_names))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	/* function to set the target data */

	private function process_target_file($data)
	{
		require_once APPPATH.'third_party/PHPExcel/PHPExcel/IOFactory.php';    //testing
		$input_file = $data['upload_file_path']; // production
		//$fn = 'Energy Dashboard Import - Target.xls';//testing
		//$input_file = FILE_UPLOAD_PATH.$fn;//testing
		$file_name = pathinfo($input_file,PATHINFO_BASENAME);
		$objReader = PHPExcel_IOFactory::createReaderForFile($input_file);
		$file_type = PHPExcel_IOFactory::identify($input_file);
		if($file_type == 'Excel5' || $file_type == 'Excel2007' )
		{
			$objReader->setReadDataOnly(TRUE);
			$objPHPExcel = $objReader->load($input_file);
		}
		else
		{
			$objReader = new PHPExcel_Reader_CSV();
			$objPHPExcel = $objReader->load($input_file);
		}

		$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);


		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['file_name'] = $file_name;

		// test data
		/*

		$data['import_type'] = 'target';
		$data['contract_id'] = 89;
		$data['upload_file_path'] = "C:/Users/307092/Desktop/uploaded files";
		*/

		$targetdata_titles = array_values(array_map('trim', array_filter($sheet_data[TD_TITLE_ROW_NO])));
		$targetdata_titles_actual = unserialize(TD_TITLE_COLS);

		$valid_column = $this->global_column_check($targetdata_titles_actual,$targetdata_titles,$data);
		// Column name check
		if ($valid_column)
		{

			foreach ($sheet_data as $sheet_row)
			{
				if(array_filter($sheet_row))
				{
					$fine_data[] = $sheet_row;
					$mrn_data[] = trim($sheet_row['E']).'#'.trim($sheet_row['G']).'#'.trim($sheet_row['H']);
				}
			}

			$mrn_data_cnt = count($mrn_data);
			$mrn_unique_cnt = count(array_unique($mrn_data));


			$cnt_fine_data = count($fine_data);
			$unique_data = array_map('unserialize', array_unique(array_map('serialize', $fine_data)));
			$cnt_unique_data = count($unique_data);

			if (($cnt_fine_data == $cnt_unique_data) && ($mrn_data_cnt == $mrn_unique_cnt))
			{
				$i = 0;
				unset($sheet_row);
				foreach ($unique_data as $sheet_row)
				{

					if ($i == 0)
					{
						$row_no = 1;
					}
					else
					{
						$row_no = $i+1;
					}
					// Removing unwanted coloumns from the excel
					array_splice($sheet_row, 11);
					// Removing whitespaces in cell values
					$sheet_row = array_map('trim', $sheet_row);

					$req_data = $sheet_row;
					array_splice($req_data, 1, 2);
					$null_cnt = $this->global_check_null($req_data);
					// Null check for required columns
					if ($null_cnt > 0)
					{

						$error_msg = str_replace(REPLACE_STRING, $row_no, EMPTY_DATA);
						$data['upload_status'] = FAILURE;
						$data['error_msg'] = $error_msg;
						$this->customeradmin_model->mark_file_upload($data);
						$import_data = new stdClass();
						$import_data->error = TRUE;
						$import_data->error_msg = $error_msg;
						$import_data->session_status = TRUE;
						echo json_encode($import_data);
						exit;
					}

					if($i>0) {
						$contract_name = $this->customeradmin_model->get_contract_name($data['contract_id']);

						if($contract_name != $sheet_row['A'])
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_CONTRACT);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						//Allowing Blank Value For �Division (site class)� & �Sub Division (site class)�
						if(empty($sheet_row['B']) && empty($sheet_row['C']))
						{
							$sheet_row['B'] = $sheet_row['A'];
							$sheet_row['C'] = $sheet_row['A'];
						}
						elseif (empty($sheet_row['B']) && !empty($sheet_row['C']))
						{
							$sheet_row['B'] = $sheet_row['A'];
						}
						elseif (!empty($sheet_row['B']) && empty($sheet_row['C']))
						{
							$sheet_row['C'] = $sheet_row['B'];
						}

						// Validating whether the MRN data available in "setup" table
						if(!$this->global_setup_check($sheet_row['B'],$sheet_row['C'],$sheet_row['D'],$sheet_row['E'],$sheet_row['F'],$data['contract_id'],$data['import_type']))
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_SETUPDATA);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// Validating whether all numeric values are numeric
						$num_data = $sheet_row;
						array_splice($num_data,0,4);
						array_splice($num_data,0,2);
						array_splice($num_data,1,1);
						$selected_value_keys = array_keys($num_data);
						$m=0;
						foreach ($num_data as $num_chk_data)
						{
							if ((is_numeric($num_chk_data) == '') || (is_numeric($num_chk_data) == FALSE))
							{
								$this->return_numeric_column_name($selected_value_keys[$m],$row_no,$data);
							}
							$m++;
						}
						$sheet_row['I'] = round($sheet_row['I'], 4);
						$sheet_row['K'] = round($sheet_row['K'], 4);
						
						$target_data = $sheet_row;
						$temp_data = $target_data;
						array_splice($temp_data, 0, 6);
						$mrn = $sheet_row['E'];
						$target_data['E'] = $mrn;


						// Year Validation
						if (($sheet_row['G'] < '1901') || ($sheet_row['G'] > '2155'))
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_YEAR);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// Month Validation
						if (!$this->month_validation($sheet_row['H']))
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, INVALID_MONTH);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}


						// Validating row for duplicate entry in database
						if(!$this->target_data_dup_check($temp_data, $mrn, $data['contract_id']))
						{
							/*$error_msg = str_replace(REPLACE_STRING, $row_no, DATA_EXISTS_IN_DATABASE);

							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;*/
							unset($target_data);
						}
						
						if(isset($target_data))
						$final_data[] = $target_data;
					}

					$i++;
				}
				
				if(empty($final_data))
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
				else 
				{
					$data['upload_status'] = SUCCESS;
					$data['error_msg'] = NULL;
					$file_id = $this->customeradmin_model->mark_file_upload($data);
					$this->customeradmin_model->import_target_data($final_data,$data['contract_id'],$file_id,$data['cuser_id']);
					$import_data = new stdClass();
					$import_data->error = FALSE;
					$import_data->session_status = TRUE;
					echo json_encode($import_data);
				}
			}
			else
			{
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = DUPLICATE_DATA;
				$this->customeradmin_model->mark_file_upload($data);
				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = DUPLICATE_DATA;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
		}
		else
		{
			$result = array_diff($targetdata_titles, $targetdata_titles_actual);
			$column_no = REPLACE_STRING;
			foreach($result as $key => $value)
			{
				$column_no = $key;
				$column_no = $column_no + 1;
				break;
			}
			$error_msg = str_replace(REPLACE_STRING, $column_no, INVALID_COLUMN);

			$data['upload_status'] = FAILURE;
			$data['error_msg'] = $error_msg;
			$this->customeradmin_model->mark_file_upload($data);
			$import_data = new stdClass();
			$import_data->error = TRUE;
			$import_data->error_msg = $error_msg;
			$import_data->session_status = TRUE;
			echo json_encode($import_data);
		}
	}

	/* End Of Target Data Function  */

	/* start for school_document data function */
	private function process_school_document_file($data)
	{
		if (!empty($data['school_id'])&&(!empty($data['upload_file_path'])))
		{
			$data['file_name'] = pathinfo($data['upload_file_path'],PATHINFO_BASENAME);
			$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
			$import_data = $this->common_model->import_school_document($data);
			
			if($import_data)
			{
				$import_data->error = FALSE;
				$import_data->error_msg = "";
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
			else
			{
				$import_data->error = TRUE;
				$import_data->error_msg = DATABASE_QUERY_FAILED;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
		} else {
			$import_data->error = TRUE;
			$import_data->error_msg = INPUT_DATA_MISSING;
			$import_data->session_status = TRUE;
			echo json_encode($import_data);
		}
	}
	/* End for school_document data function */


	/* start for energy_document data function */
	private function process_energy_document_file($data)
	{
		if (!empty($data['contract_id'])&&(!empty($data['upload_file_path'])))
		{
			$data['file_name'] = pathinfo($data['upload_file_path'],PATHINFO_BASENAME);
			$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
			$import_data = $this->common_model->import_energy_document($data);
			
			$import_data->error = FALSE;
			$import_data->error_msg = "";
			$import_data->session_status = TRUE;
			echo json_encode($import_data);
		} else {
			$import_data = new stdClass();
			$import_data->error = TRUE;
			$import_data->error_msg = INPUT_DATA_MISSING;
			$import_data->session_status = TRUE;
			echo json_encode($import_data);
		}
	}
	/* End for school_document data function */

	/* Start of Pupil import data function */
	private function process_pupils_file($data)
	{
		//$input_file = FILE_UPLOAD_PATH.'Schools Cashless Import - Children.xlsx';
		$input_file = $data['upload_file_path'];
		$file_name = pathinfo($input_file,PATHINFO_BASENAME);
		$objReader = PHPExcel_IOFactory::createReaderForFile($input_file);
		$file_type = PHPExcel_IOFactory::identify($input_file);

		if($file_type == 'Excel5' || $file_type == 'Excel2007' )
		{
			$objReader->setReadDataOnly(TRUE);
			$objPHPExcel = $objReader->load($input_file);
		}
		else
		{
			$objReader = new PHPExcel_Reader_CSV();
			$objPHPExcel = $objReader->load($input_file);
		}

		$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		//echo '<pre>';
		//print_r($sheet_data); exit;
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['file_name'] = $file_name;
		// test data
		//$data['import_type'] = 'pupils';
		//$data['contract_id'] = 11;
		//$data['upload_file_path'] = "C:/test/pupil";

		$pupils_titles = array_values(array_map('trim', array_filter($sheet_data[PUPILS_TITLE_ROW_NO])));
		$pupils_titles_actual = unserialize(PUPILS_TITLE_COLS);

		if($this->global_column_check($pupils_titles_actual,$pupils_titles,$data))
		{
			// Remove null array from import file
			foreach ($sheet_data as $sheet_row)
			{
				if(array_filter($sheet_row))
				{
					$fine_data[] = $sheet_row;
					$pupil_data[] = trim($sheet_row['A']).trim($sheet_row['B']).trim($sheet_row['C']).trim($sheet_row['D']).trim($sheet_row['H']);
					if(!empty($sheet_row['K']))
					$pupil_id[] = trim($sheet_row['K']);
				}
			}
			$pupil_data_cnt = count($pupil_data);
			$pupil_unique_cnt = count(array_unique($pupil_data));

			$pupil_id_cnt = count($pupil_id);
			$pupil_id_unique_cnt = count(array_unique($pupil_id));
			//echo '<pre>';
			//print_r($fine_data);
			$cnt_fine_data = count($fine_data);
			//print_r(array_map('unserialize', array_unique(array_map('serialize', $fine_data)))); exit;
			$unique_data = array_map('unserialize', array_unique(array_map('serialize', $fine_data)));
			//echo count($unique_data);
			//echo '<pre>';
			//print_r($unique_data);
			$cnt_unique_data = count($unique_data);
			if(($cnt_fine_data == $cnt_unique_data) && ($pupil_data_cnt == $pupil_unique_cnt) && ($pupil_id_cnt == $pupil_id_unique_cnt))
			{
				$i = 0;
				unset($sheet_row);
				foreach ($unique_data as $sheet_row)
				{
					// Removing whitespaces in cell values
					$sheet_row = array_map('trim', $sheet_row);

					if($i > 0)
					{
						$row_no = $i+1;

						$req_data = $sheet_row;
						array_splice($req_data, 2, 1);
						//array_splice($req_data, 9, 1);
						array_splice($req_data, 9);
						//echo '<pre>';
						//print_r($req_data); exit;
						$null_cnt = $this->global_check_null($req_data);

						// Null check for required columns
						if ($null_cnt > 0)
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_EMPTY_DATA);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// Validating Columns �Active�, �FSM� and �Adult�
						$yes_no_check_arr = array('7'=>strtolower($sheet_row['G']),'9'=>strtolower($sheet_row['I']),'10'=>strtolower($sheet_row['J']));
						$yes_no_check_res = $this->pupils_yes_no_check($yes_no_check_arr);
						if($yes_no_check_res->error)
						{
							$col_no = $yes_no_check_res->col_no;
							$error_msg1 = str_replace(REPLACE_STRING, $row_no, PUPILS_YES_NO);
							$error_msg = str_replace(REPLACE_STRING_COL, $col_no, $error_msg1);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}
						
						// Validate "FSM" and "Adult"						
						if(strtolower($sheet_row['J']) == PUPILS_YES && strtolower($sheet_row['I']) == PUPILS_YES)
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, FSM_ADULT_EQUAL);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}
						
						$yes_no_int_arr = $yes_no_check_res->yes_no_int;
						$sheet_row['G'] = $yes_no_int_arr[7];
						$sheet_row['I'] = $yes_no_int_arr[9];
						$sheet_row['J'] = $yes_no_int_arr[10];

						// Validating "Pupil duplicate"
						$pattern = '/^\d+\.?\d*$/';
						if(!preg_match('/^\d+\.?\d*$/', $sheet_row['H']))
						{
							$error_msg = str_replace(REPLACE_STRING, $row_no, PUPILS_NUMERIC);
							$data['upload_status'] = FAILURE;
							$data['error_msg'] = $error_msg;
							$this->customeradmin_model->mark_file_upload($data);
							$import_data = new stdClass();
							$import_data->error = TRUE;
							$import_data->error_msg = $error_msg;
							$import_data->session_status = TRUE;
							echo json_encode($import_data);
							exit;
						}

						// Validating School, Year group and Class in database
						if(empty($sheet_row['K']))
						{
							if(!$this->check_school_year_class($sheet_row['A'],$sheet_row['E'],$sheet_row['F'],$data['contract_id']))
							{
								$error_msg = str_replace(REPLACE_STRING, $row_no, PUPILS_SYC_INVALID);

								$data['upload_status'] = FAILURE;
								$data['error_msg'] = $error_msg;
								$this->customeradmin_model->mark_file_upload($data);
								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = $error_msg;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}
						}

						// Validating "Pupil Id" in the import
						if(!empty($sheet_row['K']))
						{
							$sheet_row['K'] = strtolower($sheet_row['K']);

							$pupil_id_arr = explode('/', $sheet_row['K']);
							if((count($pupil_id_arr) != 2) || (strlen($pupil_id_arr[0]) != 3) || (strlen($pupil_id_arr[1]) != 6))
							{
								$error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_ID_INVALID);
								$data['upload_status'] = FAILURE;
								$data['error_msg'] = $error_msg;
								$this->customeradmin_model->mark_file_upload($data);
								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = $error_msg;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}

							/*if((strlen($pupil_id_arr[0]) != 3) || (strlen($pupil_id_arr[1]) != 6))
							 {
							 $error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_ID_INVALID);
							 $data['upload_status'] = FAILURE;
							 $data['error_msg'] = $error_msg;
							 $this->customeradmin_model->mark_file_upload($data);

							 $import_data->error = TRUE;
							 $import_data->error_msg = $error_msg;
							 $import_data->session_status = TRUE;
							 echo json_encode($import_data);
							 exit;
							 }*/

							$check_pupil_id_cnt = $this->customeradmin_model->check_pupil_id($pupil_id_arr[0],$data['contract_id'],$sheet_row);
							if($check_pupil_id_cnt == 0)
							{
								$error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_ID_INVALID);
								$data['upload_status'] = FAILURE;
								$data['error_msg'] = $error_msg;
								$this->customeradmin_model->mark_file_upload($data);
								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = $error_msg;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}
						}

						// Validating row for duplicate entry in database
						if(empty($sheet_row['K']))
						{
							// Checking records without pupil id.
							$dup_chk_cnt = $this->customeradmin_model->pupils_dup_check_nopupilid($sheet_row,$data['contract_id']);
							if($dup_chk_cnt > 0)
							{
								$error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_DATA_EXISTS_IN_DATABASE);

								$data['upload_status'] = FAILURE;
								$data['error_msg'] = $error_msg;
								$this->customeradmin_model->mark_file_upload($data);
								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = $error_msg;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}
						}
						else
						{
							// Checking records with pupil id.
							$dup_chk_cnt = $this->customeradmin_model->pupils_dup_check_pupilid($sheet_row['G'],$sheet_row['I'],$sheet_row['J'],$sheet_row['K']);
							if($dup_chk_cnt > 0)
							{
								$error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_DATA_EXISTS_IN_DATABASE);

								$data['upload_status'] = FAILURE;
								$data['error_msg'] = $error_msg;
								$this->customeradmin_model->mark_file_upload($data);
								$import_data = new stdClass();
								$import_data->error = TRUE;
								$import_data->error_msg = $error_msg;
								$import_data->session_status = TRUE;
								echo json_encode($import_data);
								exit;
							}
							else 
							{
								$pupil_res = $this->customeradmin_model->get_pupil_records($sheet_row['K']);
								if($pupil_res->status != $sheet_row['G'])
								{
									if($sheet_row['G'] == 0)
									{
										/* Create a batch */
										$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
										$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_DEACTIVE_MESSAGE, 'key_values' => $batch_key);
										$data['reason_msg'] = generate_batch_system_messages($batch_data);
									}
									else
									{
										/* Create a batch */
										$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
										$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_ACTIVE_MESSAGE, 'key_values' => $batch_key);
										$data['reason_msg'] = generate_batch_system_messages($batch_data);
									}

									$batch_cancel_id = create_batch_cancel($data, PUPIL_ACTIVE_DATA_ID);
								}
								else if($pupil_res->fsm != $sheet_row['I'])
								{
									if($sheet_row['I'] == 0)
									{
										/* Create a batch */
										$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
										$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_FSM_DEACTIVE_MESSAGE, 'key_values' => $batch_key);
										$data['reason_msg'] = generate_batch_system_messages($batch_data);
									}
									else
									{
										/* Create a batch */
										$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
										$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_FSM_ACTIVE_MESSAGE, 'key_values' => $batch_key);
										$data['reason_msg'] = generate_batch_system_messages($batch_data);
									}								

									$batch_cancel_id = create_batch_cancel($data, PUPIL_FSM_DATA_ID);
								}
								else if($pupil_res->adult != $sheet_row['J'])
								{
									if($sheet_row['J'] == 0)
									{
										/* Create a batch */
										$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
										$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_ADULT_DEACTIVE_MESSAGE, 'key_values' => $batch_key);
										$data['reason_msg'] = generate_batch_system_messages($batch_data);
									}
									else
									{
										/* Create a batch */
										$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
										$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_ADULT_ACTIVE_MESSAGE, 'key_values' => $batch_key);
										$data['reason_msg'] = generate_batch_system_messages($batch_data);
									}

									$batch_cancel_id = create_batch_cancel($data, PUPIL_ADULT_DATA_ID);
								}
								
								/*Update the order_edited in order_items for that school_id */
								$update_qry = "UPDATE order_items SET order_edited = ". ACTIVE . ", batch_cancel_id = ". $batch_cancel_id ." where pupil_id = '". $sheet_row['K'] ."' and school_id = ". $pupil_res->school_id ." and order_edited =". INACTIVE ." and order_status =". ORDER_STATUS_NEW ." and collect_status =". INACTIVE;
								$query_status = $this->db->query($update_qry);
							}
						}
						/*$dup_chk_res = $this->customeradmin_model->pupils_data_dup_check($sheet_row,$data['contract_id']);
						 if(count($dup_chk_res) > 0)
						 {
						 if(strtolower($dup_chk_res[0]->pupil_id) != $sheet_row['K'])
						 {
						 $error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_ID_NOT_EXIST);

						 $data['upload_status'] = FAILURE;
						 $data['error_msg'] = $error_msg;
						 $this->customeradmin_model->mark_file_upload($data);

						 $import_data->error = TRUE;
						 $import_data->error_msg = $error_msg;
						 $import_data->session_status = TRUE;
						 echo json_encode($import_data);
						 exit;
						 }
						 if(($dup_chk_res[0]->status == $sheet_row['G']) && ($dup_chk_res[0]->fsm == $sheet_row['I']) && ($dup_chk_res[0]->adult == $sheet_row['J']))
						 {
						 $error_msg = str_replace(REPLACE_STRING, $row_no, PUPIL_DATA_EXISTS_IN_DATABASE);

						 $data['upload_status'] = FAILURE;
						 $data['error_msg'] = $error_msg;
						 $this->customeradmin_model->mark_file_upload($data);

						 $import_data->error = TRUE;
						 $import_data->error_msg = $error_msg;
						 $import_data->session_status = TRUE;
						 echo json_encode($import_data);
						 exit;
						 }
						 }*/

						// final data array
						$final_data[] = $sheet_row;
					}
					$i++;
				}

				$data['upload_status'] = SUCCESS;
				$data['error_msg'] = NULL;
				$file_id = $this->customeradmin_model->mark_file_upload($data);
				$this->customeradmin_model->import_pupils_data($final_data,$file_id,$data['cuser_id'],$data['contract_id']);
				$import_data = new stdClass();
				$import_data->error = FALSE;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
			else
			{
				$data['upload_status'] = FAILURE;
				$data['error_msg'] = PUPILS_DUPLICATE;
				$this->customeradmin_model->mark_file_upload($data);
				$import_data = new stdClass();
				$import_data->error = TRUE;
				$import_data->error_msg = PUPILS_DUPLICATE;
				$import_data->session_status = TRUE;
				echo json_encode($import_data);
			}
		}
	}
	/* End of Pupil import data function */

	/* Validation for School Year & Class for that contract id */
	private function check_school_year_class($school, $year, $class, $contract_id)
	{
		$check_syc = $this->customeradmin_model->check_school_year_class($school, $year, $class, $contract_id);
		if($check_syc > 0)
		return TRUE;
		else
		return FALSE;
	}

	/* Get Pupils */
	private function pupils_yes_no_check($data_arr)
	{
		$col_no_arr = array_keys($data_arr);
		$i = 0;
		foreach ($data_arr as $data)
		{
			if (($data == PUPILS_YES) || ($data == PUPILS_NO))
			{
				if($data == PUPILS_YES)
				$yes_no_int[$col_no_arr[$i]] = 1;
				else
				$yes_no_int[$col_no_arr[$i]] = 0;

				$i++;
				continue;
			}
			else
			{
				$col_no = $col_no_arr[$i];
				$err_obj = new stdClass();
				$err_obj->error = TRUE;
				$err_obj->col_no = $col_no;
				return $err_obj;
			}
		}
		$res_obj = new stdClass();
		$res_obj->yes_no_int = $yes_no_int;
		$res_obj->error = FALSE;
		return $res_obj;
	}

	/*Exporting the pupil data as .csv format */
	private function process_export_pupils($data_arr)
	{
		$export_data = $this->customeradmin_model->export_pupil_data($data_arr);	//Fetch the data from DB
		
		/*Check the array if data is exists or not*/
		if(count($export_data) <= 0)
		{
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		$excelObj = new PHPExcel();

		$excelObj->getActiveSheet()->setTitle(PUPIL_EXCEL_SHEET_NAME);	//Create the sheet name with title

		$columnname = PUPIL_EXCEL_COLUMN_NAME;
		$headings = unserialize(PUPILS_EXPORT_COLS);

		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.PUPILS_TITLE_ROW_NO, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.PUPILS_TITLE_ROW_NO)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.PUPILS_TITLE_ROW_NO)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}

		$excelObj->getActiveSheet()->freezePane('A2');

		foreach($export_data as $key => $value)
		{
			$columnname = PUPIL_EXCEL_COLUMN_NAME;
			foreach($value as $k => $v)
			{
				$dum_key = $key + 2;
				switch($columnname)
				{
					case 'A':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->school_name);
						break;
					case 'B':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->fname);
						break;
					case 'C':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->mname);
						break;
					case 'D':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->lname);
						break;
					case 'E':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->year_label);
						break;
					case 'F':
						$cls_name = $value->class_col;
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->$cls_name);
						break;
					case 'G':
						$status = PUPILS_YES;
						if($value->status != null && $value->status != "" && $value->status == 0)
						{
							$status = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $status);
						break;
					case 'H':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_dup);
						break;
					case 'I':
						$fsm = PUPILS_YES;
						if($value->fsm != null && $value->fsm != "" && $value->fsm == 0)
						{
							$fsm = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $fsm);
						break;
					case 'J':
						$adult = PUPILS_YES;
						if($value->adult != null && $value->adult != "" && $value->adult == 0)
						{
							$adult = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $adult);
						break;
					case 'K':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_id);
						break;
					case 'L':
						$username = $value->username;
						if($username == null || $username == "")
						{
							$username = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $username);
						break;
					case 'M':
						$title = $value->data_value;
						if($title == null || $title == "")
						{
							$title = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $title);
						break;
					case 'N':
						$fname = $value->first_name;
						if($fname == null || $fname == "")
						{
							$fname = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $fname);
						break;
					case 'O':
						$lname = $value->last_name;
						if($lname == null || $lname == "")
						{
							$lname = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $lname);
						break;
					case 'P':
						$email = $value->user_email;
						if($email == null || $email == "")
						{
							$email = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $email);
						break;
					case 'Q':
						$telephone = $value->telephone;
						if($telephone == null || $telephone == "")
						{
							$telephone = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $telephone);
						break;
					case 'R':
						$work_telephone = $value->work_telephone;
						if($work_telephone == null || $work_telephone == "")
						{
							$work_telephone = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $work_telephone);
						break;
					case 'S':
						$mobile = $value->mobile_number;
						if($mobile == null || $mobile == "")
						{
							$mobile = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $mobile);
						break;
					case 'T':
						$e_notify = PUPILS_YES;
						if($value->mail_notification == null || $value->mail_notification == "")
						{
							$e_notify = "";
						}
						else if($value->mail_notification == 0)
						{
							$e_notify = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $e_notify);
						break;
					case 'U':
						$sms_notify = PUPILS_YES;
						if($value->sms_notification == null || $value->sms_notification == "")
						{
							$sms_notify = "";
						}
						else if($value->sms_notification == 0)
						{
							$sms_notify = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $sms_notify);
						break;
				}
				$excelObj -> getActiveSheet()->getColumnDimension($columnname)->setAutoSize(true);	// Set the cell size to auto otherwise use setWidth()
				$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$columnname++;
			}
		}
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}

	/* Exporting HighCharts */
	public function process_export_highcharts($data)
	{
		ini_set('magic_quotes_gpc', 'off');

		$type = $data['type'];
		$svg = (string) $data['svg'];
		$filename = $data['filename'];

		if($type == null || $svg == null)	//Check the input parameter
		{
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = INPUT_DATA_MISSING;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		if(isset($data['width']) || $data['width'] == null)
		$chart_width = EXPORT_CHART_WIDTH;

		if (!$filename or !preg_match('/^[A-Za-z0-9\-_ ]+$/', $filename)) {
			$filename = 'chart';
		}

		if (get_magic_quotes_gpc()) {
			$svg = stripslashes($svg);
		}

		// check for malicious attack in SVG
		if(strpos($svg,"<!ENTITY") !== false || strpos($svg,"<!DOCTYPE") !== false){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = INVALID_SVG;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		$tempName = md5(rand());

		if ($type == 'image/png') {
			$typeString = '-m image/png';
			$ext = 'png';

		} elseif ($type == 'image/jpeg') {
			$typeString = '-m image/jpeg -q 0.99';
			$ext = 'jpg';

		} elseif ($type == 'application/pdf') {
			$typeString = '-m application/pdf';
			$ext = 'pdf';

		} elseif ($type == 'image/svg+xml') {
			$ext = 'svg';

		} else { // prevent fallthrough from global variables
			$ext = 'txt';
		}


		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$outfile = TEMP_DOWNLOAD_FILE_PATH.$tempName.".".$ext;	//Full path for that file

		if (isset($typeString)) {

			// size
			if ($chart_width) {
				$width = (int)$chart_width;
				if ($width) $width = "-w $width";
			}

			$svg_file_path = TEMP_DOWNLOAD_FILE_PATH.$tempName.".svg";

			// generate the temporary file
			if (!file_put_contents($svg_file_path, $svg)) {
				$export_obj = new stdClass();
				$export_obj->error = TRUE;
				$export_obj->error_msg = EXPORT_FOLDER_PERMISSION_ERROR;
				$export_obj->session_status = TRUE;
				echo json_encode($export_obj);
				exit;
			}

			// do the conversion
			$jar_path = APPPATH."/third_party/HighCharts/batik-rasterizer.jar";
			shell_exec("java -jar $jar_path $typeString -d $outfile $width $svg_file_path"); //original

			// check the file
			if (!is_file($outfile) || filesize($outfile) < 10) {
				$export_obj = new stdClass();
				$export_obj->error = TRUE;
				$export_obj->error_msg = SVG_ERROR;
				$export_obj->session_status = TRUE;
				echo json_encode($export_obj);
				exit;
			}
			else {
				header("Content-Disposition: attachment; filename=\"$filename.$ext\"");
				header("Content-Type: $type");
				echo file_get_contents($outfile);
			}

			// delete it
			unlink($svg_file_path);
			unlink($outfile);

			// SVG can be streamed directly back
		} else if ($ext == 'svg') {
			header("Content-Disposition: attachment; filename=\"$filename.$ext\"");
			header("Content-Type: $type");
			echo $svg;

		} else {
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = INVALID_TYPE_ERROR;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
	}
	
	/* To export the payment items */
	public function process_export_payment_items($data)
	{
		$contract_name = $this->customeradmin_model->get_contract_name($data['contract_id']);	// Get the contract name
		
		$export_data = $this->customeradmin_model->export_payment_items($data);	//Fetch the data from DB
		
		$title = "Payment Items for Contract ". $contract_name . " from " . date("d/m/Y", strtotime($data['start_date'])) ." to ". date("d/m/Y", strtotime($data['end_date']));

		/*Check the array if data is exists or not*/
		if(count($export_data) <= 0)
		{
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		$excelObj = new PHPExcel();

		$excelObj->getActiveSheet()->setTitle(PAYMENT_EXCEL_SHEET_NAME);	//Create the sheet name with title

		$excelObj->getActiveSheet()->setCellValue('A'.PAYMENT_TITLE_ROW_NO, $title);

		$columnname = PAYMENT_EXCEL_COLUMN_NAME;
		$headings = unserialize(PAYMNET_TITLE_COLS);

		$title_row = 3;
		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$title_row, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.$title_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.$title_row)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}

		foreach($export_data as $key => $value)
		{
			$dum_key = $key + 4;
			$columnname = PAYMENT_EXCEL_COLUMN_NAME;

			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->payment_id);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->contract_name);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->school_key);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->school_name);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->cdate);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->username);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_id);
			$columnname++;
				
			switch($value->card_cash)
			{
				case CARD:
					$columnname++;
					if($value->pay_refund == REFUND)
					{
						$amount = '-'.$value->amount;
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $amount);
					}
					else
					{
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->amount);
					}
					$columnname++;
					break;

				case CASH:
					if($value->pay_refund == REFUND)
					{
						$amount = '-'.$value->amount;
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $amount);
					}
					else
					{
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->amount);
					}					
					$columnname++;
					$columnname++;
					break;
			}
				
			switch($value->pay_refund)
			{
				case PAYMENT:
					$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, PAYMENT_CSV);
					$columnname++;
					break;

				case REFUND:
					$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, REFUND_CSV);
					$columnname++;
					break;
			}
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->transaction_fee);
			$columnname++;
			
			$pgtr = '';
			if($value->pgtr_id == NULL || $value->pgtr_id == '')
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $pgtr);
			}
			else
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pgtr_id);
			}
			$columnname++;
			
			$auth_id = '';
			if($value->pgauth_id == NULL || $value->pgauth_id == '')
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $auth_id);
			}
			else
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pgauth_id);
			}
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->description);
			
			/*$msg = TRANSACTION_INITIATED;
			if($value->description == NULL || $value->description == '')
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $msg);
			}
			else 
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->description);
			}*/

			$excelObj -> getActiveSheet()->getColumnDimension($columnname)->setAutoSize(true);	// Set the cell size to auto otherwise use setWidth()
			$excelObj -> getActiveSheet()->getStyle($columnname.$key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
		}
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	/* To export the order items */
	public function process_export_order_items($data)
	{
		$contract_name = $this->customeradmin_model->get_contract_name($data['contract_id']);	// Get the contract name
		
		$export_data = array();
		$title = "Order Items for Contract";
		if(isset($data['batch_cancel_id']))
		{
			$export_data = $this->user_model->export_batch_order_items($data);
			$title = "Order Items for Contract ". $contract_name;
		}
		else 
		{
			$export_data = $this->customeradmin_model->export_order_items($data);	//Fetch the data from DB
			$title = "Order Items for Contract ". $contract_name . " from " . date("d/m/Y", strtotime($data['start_date'])) ." to ". date("d/m/Y", strtotime($data['end_date']));
		}		
		
		/*Check the array if data is exists or not*/
		if(count($export_data) <= 0)
		{
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
		
		$excelObj = new PHPExcel();

		$excelObj->getActiveSheet()->setTitle(ORDER_EXCEL_SHEET_NAME);	//Create the sheet name with title

		$excelObj->getActiveSheet()->setCellValue('A'.ORDER_TITLE_ROW_NO, $title);

		$columnname = ORDER_EXCEL_COLUMN_NAME;
		$headings = unserialize(ORDER_TITLE_COLS);
		
		$title_row = 3;
		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$title_row, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.$title_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.$title_row)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}

		foreach($export_data as $key => $value)
		{
			$dum_key = $key + 4;
			$columnname = PAYMENT_EXCEL_COLUMN_NAME;

			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->order_id);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->school_key);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->school_name);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->fulfilment_date);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->order_date);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->username);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_id);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->fname);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->mname);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->lname);
			$columnname++;					
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->year_label);
			$columnname++;
			if($value->class_col != '' || $value->class_col != NULL)
			{
				$cls_name = $value->class_col;
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->$cls_name);
				$columnname++;
			}
			else
			{
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, '');
				$columnname++;
			}
			
			$active_status = PUPILS_YES;
			if($value->status != null && $value->status != "" && $value->status == 0)
			{
				$active_status = PUPILS_NO;
			}
			else if($value->status == null || $value->status == "")
			{
				$active_status = '';
			}
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $active_status);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_dup);
			$columnname++;
			
			$fsm_status = PUPILS_YES;
			if($value->fsm != null && $value->fsm != "" && $value->fsm == 0)
			{
				$fsm_status = PUPILS_NO;
			}
			else if($value->fsm == null || $value->fsm == "")
			{
				$fsm_status = '';
			}
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $fsm_status);
			$columnname++;
			
			$adult_status = PUPILS_YES;
			if($value->adult != null && $value->adult != "" && $value->adult == 0)
			{
				$adult_status = PUPILS_NO;
			}
			else if($value->adult == null || $value->adult == "")
			{
				$adult_status = '';
			}
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $adult_status);
			$columnname++;
	
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->main_meal);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->main_net);
			$columnname++;			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->snacks);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->snacks_net);
			$columnname++;			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->hospitality_desc);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->order_status);
			$columnname++;
			
			$impact_batch = '';
			if($value->impact_batch != null && $value->impact_batch != '')
			{
				$impact_batch = $value->impact_batch;
			}
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $impact_batch);
			$columnname++;
			
			$sys_msg = '';
			if($value->sys_msg != null && $value->sys_msg != '')
			{
				$sys_msg = $value->sys_msg;
			}
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $sys_msg);
			
			$columnname++;
			
			$user_msg = '';
			if($value->user_msg != null && $value->user_msg != '')
			{
				$user_msg = $value->user_msg;
			}
			$user_msg = trim(preg_replace('/\s+/', ' ', $user_msg));
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $user_msg);			
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->card_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->card_vat);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->cash_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->cash_vat);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->fsm_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->fsm_vat);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_card_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_card_vat);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_cash_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_cash_vat);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_sa_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_sa_vat);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_hos_net);
			$columnname++;
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->a_hos_vat);
			$columnname++;

			$excelObj -> getActiveSheet()->getColumnDimension($columnname)->setAutoSize(true);	// Set the cell size to auto otherwise use setWidth()
			$excelObj -> getActiveSheet()->getStyle($columnname.$key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				
		}
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	public function process_export_digital_xml_file($data)
	{
		$res_apps = $this->user_model->view_digital_form($data);
		
		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}
		
		$tempName = md5(rand());
		$file = TEMP_DOWNLOAD_FILE_PATH. $tempName . XML_FILE_FORMAT;
		file_put_contents($file, $res_apps->form_xml);

		$export_obj = new stdClass();
		$export_obj->temp_file = $tempName;
		$export_obj->app_name = $res_apps->app_name;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);
		exit;
	}
	
	/*Exporting the pupil balances as .csv format */
	private function process_export_pupil_balances($data_arr)
	{
		$contract_name = $this->customeradmin_model->get_contract_name($data_arr['contract_id']);	// Get the contract name
		
		$export_data = array();
		$date = date('d/m/Y H:i:s');
		
		$title = "Balances Report for Contract ". $contract_name . " as of " . $date;
		
		$export_data = $this->customeradmin_model->export_pupil_balances($data_arr);	//Fetch the data from DB
		
		/*Check the array if data is exists or not*/
		if(count($export_data) <= 0)
		{
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		$excelObj = new PHPExcel();

		$excelObj->getActiveSheet()->setTitle(PUPIL_BALANCE_EXCEL_SHEET_NAME);	//Create the sheet name with title
		
		$excelObj->getActiveSheet()->setCellValue('A'.ORDER_TITLE_ROW_NO, $title);

		$columnname = PUPIL_EXCEL_COLUMN_NAME;
		$headings = unserialize(PUPIL_BALANCE_EXPORT_COLS);
		
		$title_row = 3;
		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$title_row, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.$title_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.$title_row)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}

		$excelObj->getActiveSheet()->freezePane('A2');

		foreach($export_data as $key => $value)
		{
			$columnname = PUPIL_EXCEL_COLUMN_NAME;
			foreach($value as $k => $v)
			{
				$dum_key = $key + 4;
				switch($columnname)
				{
					case 'A':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_id);
						break;
					case 'B':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->cash_balance);
						break;
					case 'C':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->card_balance);
						break;
					case 'D':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->school_name);
						break;
					case 'E':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->fname);
						break;
					case 'F':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->mname);
						break;
					case 'G':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->lname);
						break;
					case 'H':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->year_label);
						break;
					case 'I':
						$cls_name = $value->class_col;
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->$cls_name);
						break;
					case 'J':
						$status = PUPILS_YES;
						if($value->status != null && $value->status != "" && $value->status == 0)
						{
							$status = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $status);
						break;
					case 'K':
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pupil_dup);
						break;
					case 'L':
						$fsm = PUPILS_YES;
						if($value->fsm != null && $value->fsm != "" && $value->fsm == 0)
						{
							$fsm = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $fsm);
						break;
					case 'M':
						$adult = PUPILS_YES;
						if($value->adult != null && $value->adult != "" && $value->adult == 0)
						{
							$adult = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $adult);
						break;
					case 'N':
						$username = $value->username;
						if($username == null || $username == "")
						{
							$username = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $username);
						break;
					case 'O':
						$title = $value->data_value;
						if($title == null || $title == "")
						{
							$title = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $title);
						break;
					case 'P':
						$fname = $value->first_name;
						if($fname == null || $fname == "")
						{
							$fname = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $fname);
						break;
					case 'Q':
						$lname = $value->last_name;
						if($lname == null || $lname == "")
						{
							$lname = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $lname);
						break;
					case 'R':
						$email = $value->user_email;
						if($email == null || $email == "")
						{
							$email = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $email);
						break;
					case 'S':
						$telephone = $value->telephone;
						if($telephone == null || $telephone == "")
						{
							$telephone = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $telephone);
						break;
					case 'T':
						$work_telephone = $value->work_telephone;
						if($work_telephone == null || $work_telephone == "")
						{
							$work_telephone = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $work_telephone);
						break;
					case 'U':
						$mobile = $value->mobile_number;
						if($mobile == null || $mobile == "")
						{
							$mobile = "";
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $mobile);
						break;
					case 'V':
						$e_notify = PUPILS_YES;
						if($value->mail_notification == null || $value->mail_notification == "")
						{
							$e_notify = "";
						}
						else if($value->mail_notification == 0)
						{
							$e_notify = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $e_notify);
						break;
					case 'W':
						$sms_notify = PUPILS_YES;
						if($value->sms_notification == null || $value->sms_notification == "")
						{
							$sms_notify = "";
						}
						else if($value->sms_notification == 0)
						{
							$sms_notify = PUPILS_NO;
						}
						$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $sms_notify);
						break;
				}
				$excelObj -> getActiveSheet()->getColumnDimension($columnname)->setAutoSize(true);	// Set the cell size to auto otherwise use setWidth()
				$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
				$columnname++;
			}
		}
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	/* Export digital form filter to excel */
	private function process_export_digital_forms($data)
	{
		$title = date("dS M Y", strtotime($data['start_date'])) . ' - ' . date("dS M Y", strtotime($data['end_date']));
		
		if($data['ft'] == "" 
				|| $data['p'] == ""
				|| $data['w']  == ""
				|| $data['dw'] == ""
				|| $data['mt'] == ""
				|| ($data['w']  == "0" && strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") === false)
				|| ($data['dw']  == "0" && strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") === false && strpos(",".$data['ft'].",",",".ADHOC_FORM_TYPE_ID.",") === false)
		){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
		
		$export_data = $this->user_model->export_digital_forms($data);	//Fetch the data from DB
		
		/*Check the array if data is exists or not*/
		if(count($export_data->week) <= 0 && count($export_data->days) <= 0 && count($export_data->menu_arr) <= 0 && count($export_data->menu_quan) <= 0 && count($export_data->custom_orders) <= 0 && count($export_data->adhoc_arr) <= 0)
		{
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
		
		$excelObj = new PHPExcel();

		$excelObj->getActiveSheet()->setTitle(DIGITAL_FORM_EXCEL_PAGE_TITLE);	//Create the sheet name with title
		
		$excelObj->getActiveSheet()->setCellValue('A'.ORDER_TITLE_ROW_NO, $title);

		$dum_key = 1;
		
		if(count($export_data->week) > 0 && count($export_data->days) > 0 && count($export_data->menu_arr) > 0 && count($export_data->menu_quan) > 0)
		{
			foreach($export_data->week as $key => $value)
			{
				$columnname = PUPIL_EXCEL_COLUMN_NAME;
				$dum_key = $dum_key + 1;
				// Day of week Name
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->dayofweek);
				$columnname++;
				foreach($export_data->days as $ky => $val)
				{
					foreach($val as $x => $y)
					{
						if($y->dow == $value->dayofweek)
						{
							$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $y->date_for);
							$columnname++;
						}
					}
				}
					
				$menu_key = $value->dayofweek;
                                if($menu_key == "")
                                       $menu_key = 0;
				$menu_row_key = $dum_key;
				
				foreach($export_data->menu_arr->$menu_key as $p => $q)
				{
					$columnname = PUPIL_EXCEL_COLUMN_NAME;
					$menu_row_key = $menu_row_key + 1;
					$excelObj -> getActiveSheet()->setCellValue($columnname.$menu_row_key, $q->menu);
					$columnname++;
					
					foreach($export_data->menu_quan as $m => $n)
					{
						foreach($n as $k => $v)
						{
							if($value->dayofweek === $v->dow && $q->menu === $v->menu)
							{
								$excelObj -> getActiveSheet()->setCellValue($columnname.$menu_row_key, $v->ln);
								$columnname++;
							}
						}
					}
					
					

					/*foreach($export_data->days as $ky => $val)
					{
						foreach($val as $x => $y)
						{
							foreach($export_data->menu_quan as $m => $n)
							{
								foreach($n as $k => $v)
								{
									if(strtotime($y->date_for) === strtotime($v->day) && $y->dow === $value->dayofweek && $value->dayofweek === $v->dow && $y->dow == $v->dow && $q->menu === $v->menu)
									{
										$excelObj -> getActiveSheet()->setCellValue($columnname.$menu_row_key, $v->ln);
										$columnname++;
										break;
									}
								}
							}
						}
					}*/
				}
				
				$dum_key = $menu_row_key;
			}
		}
		if(count($export_data->custom_orders) > 0)
		{
			// For custom orders
			$dum_key = $dum_key + 1;

			$excelObj->getActiveSheet()->setCellValue('A'.$dum_key, CUSTOM_ORDER_TITLE);

			$columnname = ORDER_EXCEL_COLUMN_NAME;
			$headings = unserialize(CUSTOM_ORDER_EXCEL_TITLE_COLS);

			$dum_key = $dum_key + 1;
			foreach($headings as $value)
			{
				$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value);
				$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
				$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->applyFromArray(array("font" => array("bold" => true)));	// text bold
				$columnname++;
			}

			foreach($export_data->custom_orders as $key => $value)
			{
				$dum_key = $dum_key + 1;
				$columnname = PUPIL_EXCEL_COLUMN_NAME;
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->wn);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->dayofweek);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->date_rec);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->ln);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->meal_order);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->comp);
				$columnname++;
			}
		}

		// For Adhoc digital forms
		if(count($export_data->adhoc_arr) > 0)
		{
			$dum_key = $dum_key + 1;
			$excelObj->getActiveSheet()->setCellValue('A'.$dum_key, ADHOC_DIGITAL_FORM_TITLE);

			$columnname = ORDER_EXCEL_COLUMN_NAME;
			$headings = unserialize(ADHOC_EXCEL_TITLE_COLS);
			
			$dum_key = $dum_key + 1;
			foreach($headings as $value)
			{
				$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value);
				$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
				$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->applyFromArray(array("font" => array("bold" => true)));	// text bold
				$columnname++;
			}
			
			foreach($export_data->adhoc_arr as $key => $value)
			{
				$dum_key = $dum_key + 1;
				$columnname = PUPIL_EXCEL_COLUMN_NAME;
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->wn);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->date_rec);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->ad);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->mt);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->ct);
				$columnname++;
					
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->md);
				$columnname++;
			}
		}
		
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file
                
		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	public function process_export_digital_forms_tdm($data)
	{
		$title = date("dS M Y", strtotime($data['start_date'])) . ' - ' . date("dS M Y", strtotime($data['end_date']));
		
		$title = DIGITAL_FORM_TOTAL_DAILY_MEAL ." " . $title;
		
		$days_no = intval((abs(strtotime($data['end_date']) - strtotime($data['start_date'])))/86400) + 1;
		$day_res = $data['start_date'];
		for($i=0; $i<$days_no; $i++)
		{
			if($i > 0)
			{
				$date = new DateTime($day_res);
				$date->add(new DateInterval('P1D'));
				$day_res = $date->format('Y-m-d');
				//$date_disp = $date->format('jS M');
			}
			else 
			{
				$date = new DateTime($day_res);
				$day_res = $date->format('Y-m-d');
				//$date_disp = $date->format('jS M');
			}
			//$disp_days[] = $date_disp;
			$days_res[] = $day_res;
		}
		$data['days'] = $days_res;
		
		$export_data = $this->user_model->get_df_tdm_numbers($data);	//Fetch the data from DB
		
		if(count($export_data->wln) <= 0 && count($export_data->tdm <= 0)){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
		
		$excelObj = new PHPExcel();

		$excelObj->getActiveSheet()->setTitle(DIGITAL_FORM_TDM_TITLE);	//Create the sheet name with title		
		$excelObj->getActiveSheet()->setCellValue('A'.ORDER_TITLE_ROW_NO, $title);

		$dum_key = 1;
		
		$columnname = 'A';
		$dum_key = $dum_key + 1;
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, DIGITAL_FORM_TDM_REPORTING_TIME_TITLE);
		$columnname++;
		
		$report_time = $export_data->rt[0]->rt;
		
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $report_time);
		
		/* Menu Indicator */
		$indicator = "";
		if($data['dfi_id'] == 0)
		{
			$indicator = DIGITAL_FORM_TDM_NO_INDICATOR_DESC;
		}
		else
		{
			$res_form_indicator = $this->user_model->get_df_indicator_desc($data);	//Fetch the data from DB
			
			if(count($res_form_indicator) > 0)
			{
				$indicator = $res_form_indicator[0]->mt;
				if($res_form_indicator[0]->mt_desc != '')
					$indicator = $indicator . '-' . $res_form_indicator[0]->mt_desc;
			}
		}
		
		$columnname = 'A';
		$dum_key = $dum_key + 1;
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, DIGITAL_FORM_TDM_INDICATOR);
		$columnname++;
		
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $indicator);
		
		$dum_key = $dum_key + 1;
		$columnname = 'B';
		foreach($export_data->wln as $key => $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value->wn);
			$columnname++;
		}
		
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, DIGITAL_FORM_TDM_TOTAL_TITLE);
		
		$dum_key = $dum_key + 1;
		$columnname = 'A';
		
		foreach($export_data->tdm as $key => $value)
		{
			foreach($value as $ky => $val)
			{
				if($ky == 0)
				{
					$day = date("dS M Y", strtotime($val));
					$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $day);
					$columnname++;
				}
				else
				{
					$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $val);
					$columnname++;
				}
			}
			$dum_key = $dum_key + 1;
			$columnname = 'A';
		}
		
		$total_cnt = 0;
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, DIGITAL_FORM_TDM_TOTAL_TITLE);
		$columnname++;
		foreach($export_data->wln as $key => $value)
		{
			$total_cnt = $total_cnt + $value->cnt;
			$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value->cnt);
			$columnname++;
		}
		
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $total_cnt);
		
		$dum_key = $dum_key + 1;
		$columnname = 'B';
		
		$cus_title = $export_data->cus_cnt . " " . DIGITAL_FORM_TDM_CUSTOM_COUNT_TITLE;
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $cus_title);
		
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	public function process_export_digital_forms_tdm_custom($data)
	{
		$export_data = $this->user_model->export_digital_form_tdm_custom($data);	//Fetch the data from DB
		
		if(count($export_data) <= 0){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		$excelObj = new PHPExcel();
		
		$excelObj->getActiveSheet()->setTitle(DIGITAL_FORM_EXCEL_PAGE_TITLE);	//Create the sheet name with title

		$dum_key = 1;

		$excelObj->getActiveSheet()->setCellValue('A'.$dum_key, CUSTOM_ORDER_TITLE);

		$columnname = ORDER_EXCEL_COLUMN_NAME;
		$headings = unserialize(CUSTOM_ORDER_EXCEL_TITLE_COLS);

		$dum_key = $dum_key + 1;
		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}
		
		foreach($export_data as $key => $value)
		{
			$dum_key = $dum_key + 1;
			$columnname = 'A';
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->wn);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->dayofweek);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->date_rec);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->ln);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->meal_order);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->comp);
			$columnname++;
		}
		
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	public function process_export_quality_auditor_filter($data)
	{
		$export_data = $this->user_model->export_quality_auditor_filter($data);	//Fetch the data from DB
		
		if(count($export_data) <= 0){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}

		$excelObj = new PHPExcel();
		
		$excelObj->getActiveSheet()->setTitle(QA_FILTER_PAGE_TITLE);	//Create the sheet name with title

		$dum_key = 1;
		
		$columnname = ORDER_EXCEL_COLUMN_NAME;
		$headings = unserialize(QA_FILTER_EXCEL_TITLE_CLS);
		
		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}
		
		foreach($export_data as $key => $value)
		{
			$dum_key = $dum_key + 1;
			$columnname = 'A';
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->sname);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->adate);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->amonth);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->aud_name);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->time_in);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->time_out);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->aname);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->saname);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pname);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->ind_name);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pvalue);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pweight);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->pvalue);
			$columnname++;
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->cmt);
			$columnname++;
		}
		
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	public function process_export_daily_meal_order($data)
	{
		$date_title = date("dS M Y", strtotime($data['start_date']));
		
		$export_data = $this->user_model->get_daily_meal_orders($data);	//Fetch the data from DB
		
		if(count($export_data) <= 0){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
		
		$excelObj = new PHPExcel();
		
		$excelObj->getActiveSheet()->setTitle(DMO_EXCEL_PAGE_TITLE);	//Create the sheet name with title

		$dum_key = 1;
		
		$columnname = 'A';
		switch($data['ft'])
		{
			case 1:
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, DAILY_MEAL_ORDER_LUNCH);
				$columnname++;
				break;
			case 2:
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, DAILY_MEAL_ORDER_DINNER);
				$columnname++;
				break;
			case 3:
				$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, DAILY_MEAL_ORDER_ADHOC);
				$columnname++;
		}
		$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $date_title);
		$columnname++;
		
		$dum_key = $dum_key + 1;
		$columnname = 'A';
		$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, DMO_REPORTING_TIME_TITLE);
		$columnname++;
		
		$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $export_data->rt[0]->rt);
		$columnname++;
		
		$dum_key = $dum_key + 1;
		$columnname = 'B';
		foreach($export_data->wln as $key => $value)
		{
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->wn);
			$columnname++;
		}
		
		$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, DMO_TOTAL_TITLE);
		
		$column_count = 0;
		$column_obj = new stdClass();
		foreach($export_data->dm as $key => $value)
		{
			$dum_key = $dum_key + 1;
			$columnname = 'A';			
			
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value[0]);
			$columnname++;
			
			$count = 0;
			foreach($export_data->wln as $k => $v)
			{
				if(isset($value[$v->wid]))
				{
					$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value[$v->wid]);
					$column_obj->$columnname = $column_obj->$columnname + $value[$v->wid];
					$columnname++;
					$count = $count + $value[$v->wid];
					
				}
				else 
				{
					$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key,'0');
					$column_obj->$columnname = $column_obj->$columnname + 0;
					$columnname++;
				}
			}
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key,$count);
			$columnname++;
		}
		
		$dum_key = $dum_key + 1;
		$total_count = 0;
		$columnname = 'A';
		$total_columnname = 'A';
		$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, DMO_TOTAL_TITLE);
		foreach($column_obj as $key => $value)
		{
			$excelObj -> getActiveSheet()->setCellValue($key.$dum_key, $value);
			$total_count = $total_count + $value;
			$total_columnname = $key;
		}
		
		$total_columnname++;
		$excelObj -> getActiveSheet()->setCellValue($total_columnname.$dum_key, $total_count);
		
		$dum_key = $dum_key + 1;
		$columnname = 'B';
		
		$cus_title = $export_data->cus_cnt . " " . DIGITAL_FORM_TDM_CUSTOM_COUNT_TITLE;
		$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $cus_title);
		
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
	
	public function export_daily_meal_order_custom($data)
	{
		$export_data = $this->user_model->export_dmo_custom_order($data);	//Fetch the data from DB

		if(count($export_data) <= 0){
			$export_obj = new stdClass();
			$export_obj->error = TRUE;
			$export_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			$export_obj->session_status = TRUE;
			echo json_encode($export_obj);
			exit;
		}
		
		$excelObj = new PHPExcel();
		
		$excelObj->getActiveSheet()->setTitle(DIGITAL_FORM_EXCEL_PAGE_TITLE);	//Create the sheet name with title

		$dum_key = 1;

		$excelObj->getActiveSheet()->setCellValue('A'.$dum_key, CUSTOM_ORDER_TITLE);

		$columnname = ORDER_EXCEL_COLUMN_NAME;
		$headings = unserialize(CUSTOM_ORDER_EXCEL_TITLE_COLS);

		$dum_key = $dum_key + 1;
		foreach($headings as $value)
		{
			$excelObj->getActiveSheet()->setCellValue($columnname.$dum_key, $value);
			$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);	//cell alignment
			$excelObj -> getActiveSheet()->getStyle($columnname.$dum_key)->applyFromArray(array("font" => array("bold" => true)));	// text bold
			$columnname++;
		}
		
		foreach($export_data as $key => $value)
		{
			$dum_key = $dum_key + 1;
			$columnname = 'A';
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->wn);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->dayofweek);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->date_rec);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->ln);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->meal_order);
			$columnname++;
				
			$excelObj -> getActiveSheet()->setCellValue($columnname.$dum_key, $value->comp);
			$columnname++;
		}
		
		ob_start();

		/* Check whether the download directory is exists or not. If not means create the directory */
		if(!is_dir(TEMP_DOWNLOAD_FILE_PATH))
		{
			mkdir(TEMP_DOWNLOAD_FILE_PATH, 0777);	//Directory creation with full permission
		}

		$milliseconds = round(microtime(true) * 1000);	// Generating the milliseconds for file name

		$filename = TEMP_DOWNLOAD_FILE_PATH.$milliseconds.EXPORT_FILE_EXTENSION;	//Full path for that file

		$objWriter = PHPExcel_IOFactory::createWriter($excelObj, EXPORT_FILE_TYPE);
		$objWriter->setPreCalculateFormulas(FALSE);
		$objWriter->save($filename);	// Save file into the directory

		$export_obj = new stdClass();
		$export_obj->temp_file = $milliseconds;
		$export_obj->error = FALSE;
		$export_obj->session_status = TRUE;
		echo json_encode($export_obj);

		ob_end_flush();
		exit;
	}
}
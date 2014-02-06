<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'business/business_user.php';
require_once APPPATH.'business/business_common.php';

class User extends CI_Controller {

	public function __construct()
	{
		parent::__construct();

		if(!validate_role(USER))	// Role validation
		{
			//ob_start();
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = FALSE;
			echo json_encode($error_obj);
			exit;
		}

		if (!empty($_POST['session_id']))
		{
			$user_session = $_POST['session_id'];
			if(!validate_session($user_session))	// Session Validation
			{
				$error_obj->error = TRUE;
				$error_obj->error_msg = INVALID_SESSION;
				$error_obj->session_status = FALSE;
				echo json_encode($error_obj);
				exit;
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

	/* Getting the Monthly Chart */
	public function get_monthly_chart()
	{
		$data = $this->security->xss_clean($_POST);
		
		if($data['utility_type'] == 'Sum')
			$data['from'] = USER_FROM_GET_MONTHLY_CHART_SUMMARY;
		else if($data['utility_type'] == 'Gas')
			$data['from'] = USER_FROM_GET_MONTHLY_CHART_GAS;
		else
			$data['from'] = USER_FROM_GET_MONTHLY_CHART_ELECTRICITY;
			
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		
		if((!empty($data['session_id'])) && (!empty($data['sel_date'])))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			//$data['contract_id'] =1;
			//$data['sel_date'] = "1/3/2013";
			$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
			$bw_obj = new Business_user();
			$chart_data = $bw_obj->get_monthly_chart($data);
			$get_month_chart->chart_data = $chart_data;
			$get_month_chart->error = FALSE;
			$get_month_chart->error_msg = "";
			$get_month_chart->session_status = TRUE;
			//echo "<pre>";
			//print_r($get_month_chart);
			echo json_encode($get_month_chart);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$get_month_chart->error = TRUE;
			$get_month_chart->error_msg = INPUT_DATA_MISSING;
			$get_month_chart->session_status = TRUE;
			echo json_encode($get_month_chart);
		}

	}

	/* Getting the Daily Chart */
	public function get_daily_chart()
	{
		$data = $this->security->xss_clean($_POST);
		
		if($data['utility_type'] == 'Sum')
			$data['from'] = USER_FROM_GET_DAILY_CHART_SUMMARY;
		else if($data['utility_type'] == 'Gas')
			$data['from'] = USER_FROM_GET_DAILY_CHART_GAS;
		else
			$data['from'] = USER_FROM_GET_DAILY_CHART_ELECTRICITY;
			
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if((!empty($data['session_id'])) && (!empty($data['sel_date'])))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			//$data['contract_id'] =1;
			//$data['sel_date'] ='29/3/2013';
			$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
			$bw_obj = new Business_user();
			$chart_data = $bw_obj->get_daily_chart($data);
			$get_daily_chart->chart_data = $chart_data;
			$get_daily_chart->error = FALSE;
			$get_daily_chart->error_msg = "";
			$get_daily_chart->session_status = TRUE;
			echo json_encode($get_daily_chart);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$get_daily_chart->error = TRUE;
			$get_daily_chart->error_msg = INPUT_DATA_MISSING;
			$get_daily_chart->session_status = TRUE;
			echo json_encode($get_daily_chart);
		}
	}

	/* Getting the Half Hourly Chart */
	public function get_halfhourly_chart(){
		$data = $this->security->xss_clean($_POST);
		
		if($data['utility_type'] == 'Sum')
			$data['from'] = USER_FROM_GET_HH_CHART_SUMMARY;
		else
			$data['from'] = USER_FROM_GET_HH_CHART_ELECTRICITY;
			
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		
		if((!empty($data['session_id'])) && (!empty($data['sel_date'])))	// input validation
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			//$data['contract_id'] =1;
			//$data['sel_date'] ='29/3/2013';
			$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
			$bw_obj = new Business_user();
			$chart_data = $bw_obj->get_halfhourly_chart($data);
			$get_hh_chart->chart_data = $chart_data;
			$get_hh_chart->error = FALSE;
			$get_hh_chart->error_msg = "";
			$get_hh_chart->session_status = TRUE;
			echo json_encode($get_hh_chart);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$get_hh_chart->error = TRUE;
			$get_hh_chart->error_msg = INPUT_DATA_MISSING;
			$get_hh_chart->session_status = TRUE;
			echo json_encode($get_hh_chart);
		}

	}

	/* Getting the schools */
	public function get_schools()
	{
		//1.validate session
		//2.call business object to get the schools
		//3.return an object

		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_schools($data);
		$contract_res = $bw_obj->get_contract($data);
		$school_obj->contract_res = $contract_res;
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	// To get list of students for a parent
	public function get_schools_orders() {
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = USER_FROM_GET_SCHOOLS_ORDERS;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		//$data = array('contract_id'=>'135');
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_schools_orders($data);
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
		exit;
	}
	//To get the list of schools for school admins
	public function get_schools_admins()
	{
		//1.validate session
		//2.call business object to get the schools for admins
		//3.return an object
	
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = USER_FROM_GET_SCHOOLS_ADMINS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_schools_admins($data);
		$contract_res = $bw_obj->get_contract($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$school_obj->contract_res = $contract_res;
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}
	
	//To get the list of schools for school admins
	public function check_all_schools_status()
	{
		//1.validate session
		//2.call business object to get the schools for admins
		//3.return an object
	
		//$data = array('contract_id'=>27);
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = USER_FROM_CHECK_ALL_SCHOOL_STATUS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->check_all_schools_status($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$school_obj->check_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Getting the school detail information */
	public function get_school_details(){
		//$data['school_id'] = "284";
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = USER_FROM_GET_SCHOOL_DETAILS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		if(!validate_school_admin($data))
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
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_school_details($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Save the school details */
	public function save_school_details(){
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		//$data['user_id'] = 1;
		//$data['form_data'] ='hdnSchoolId=31&txtAddress1=address+11&txtAddress2=address22&txtAddress3=address+33&txtCity=city&txtCounty=country&txtPostcode=AA9A+9AA&txtOffC1Name=contact+1+name&txtOffC2Name=contact+2+name&txtOffC1Email=contact1email%40email.com&txtOffC2Email=contact2email%40email.com&txtOffC1Telephone=11111111111&txtOffC2Telephone=22222222222&txtY0=reception1&chkY0=1&txtY0C1=class1&chkY0C1=1&txtY0C2=class2&chkY0C2=1&txtY0C3=class3&chkY0C3=1&txtY0C4=class4&chkY0C4=1&txtY0C5=class5&chkY0C5=1&txtY0C6=class6&chkY0C6=1&txtY1=Year1&chkY1=1&txtY1C1=class1&chkY1C1=1&txtY1C2=class2&chkY1C2=1&txtY1C3=class3&chkY1C3=1&txtY1C4=class4&chkY1C4=1&txtY1C5=class5&chkY1C5=1&txtY1C6=class6&chkY1C6=1&txtY2=&txtY2C1=&txtY2C2=&txtY2C3=&txtY2C4=&txtY2C5=&txtY2C6=&txtY3=&txtY3C1=&txtY3C2=&txtY3C3=&txtY3C4=&txtY3C5=&txtY3C6=&txtY4=&txtY4C1=&txtY4C2=&txtY4C3=&txtY4C4=&txtY4C5=&txtY4C6=&txtY5=&txtY5C1=&txtY5C2=&txtY5C3=&txtY5C4=&txtY5C5=&txtY5C6=&txtY6=&txtY6C1=&txtY6C2=&txtY6C3=&txtY6C4=&txtY6C5=&txtY6C6=';
		//$data['contract_id'] = 1;
		
		
		parse_str( $data['form_data'], $school_data);
		
		$req_data = array('school_id' => $school_data['hdnSchoolId']);
		$req_data['from'] = USER_FROM_SAVE_SCHOOL_DETAILS;	
		$req_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($req_data);
		
		if(count($school_data) > 0)
		{
			$val_school_data['school_id'] = $school_data['hdnSchoolId'];	
			$val_school_data['contract_id'] = $data['contract_id'];
			$val_school_data['user_id'] = $data['user_id'];	
			/* School Validation */
			if(!validate_school_admin($val_school_data))
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
		}
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->save_school_details($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
				
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Getting the school documents */
	public function get_school_documents(){
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = CUSTOMER_ADMIN;
		
		$data['from'] = USER_FROM_GET_SCHOOL_DOCUMENTS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if($data['school_id'] != 0)
		{
			if(!validate_school_admin($data))
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
		}
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_school_documents($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		$school_obj->schools_documents_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Getting the comments for school documents */
	public function get_school_document_comments() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = CUSTOMER_ADMIN;
		
		$data['from'] = USER_FROM_GET_SCHOOL_DOCUMENT_COMMENTS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_school_document_comments($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Update the school document status */
	public function update_school_document_status() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_UPDATE_SCHOOL_DOCUMENT_STATUS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->update_school_document_status($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Inserting the comments for school document */
	public function insert_document_comments() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_INSERT_DOCUMENT_COMMENTS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->insert_document_comments($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Deleting the comments */
	public function delete_document() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_DELETE_DOCUMENT;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->delete_document($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Getting the energy documents */
	public function get_energy_documents(){
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = CUSTOMER_ADMIN;
		
		$data['from'] = USER_FROM_GET_ENERGY_DOCUMENTS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->get_energy_documents($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$energy_obj->energy_documents_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Inserting the comments for energy document */
	public function insert_energy_document_comments() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_INSERT_ENERGY_DOCUMENT_COMMENTS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->insert_energy_document_comments($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Updating the energy document status */
	public function update_energy_document_status() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_UPDATE_ENERGY_DOCUMENT_STATUS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->update_energy_document_status($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Getting the energy document comments */
	public function get_energy_document_comments() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = CUSTOMER_ADMIN;
		
		$data['from'] = USER_FROM_GET_ENERGY_DOCUMENT_COMMENTS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->get_energy_document_comments($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Deleting the energy document */
	public function delete_energy_document() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_DELETE_ENERGY_DOCUMENT;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->delete_energy_document($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	// Parent add pupil using pupil ID
	public function add_pupil() {
		//$data['pupil_id'] = 'ay1/bh1la8';
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_ADD_PUPIL;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_user();
		$add_pupil_res = $bw_obj->add_pupil($data);
		if($add_pupil_res->error)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
		
			echo json_encode($add_pupil_res);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$add_pupil_obj = new stdClass();
			$add_pupil_obj->error = FALSE;
			$add_pupil_obj->session_status = TRUE;
			echo json_encode($add_pupil_obj);
		}
	}

	// To get list of students for a parent
	public function get_pupils() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		//$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_GET_PUPILS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_user();
		$get_pupils_res = $bw_obj->get_pupils($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		$get_pupils_obj = new stdClass();
		$get_pupils_obj->get_pupils_res = $get_pupils_res;
		$get_pupils_obj->error = FALSE;
		$get_pupils_obj->session_status = TRUE;
		echo json_encode($get_pupils_obj);
	}

	// Parent unassign a child from a school
	public function pupil_unassign()
	{
		$data = $this->security->xss_clean($_POST);
		//$data['pupil_id'] = 121;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_PUPIL_UNASSIGN;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		if(!validate_student_contract($data['contract_id'], $data['pupil_id']))
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
			
		$bw_obj = new Business_user();
		$add_pupil_obj = $bw_obj->pupil_unassign($data);
		echo json_encode($add_pupil_obj);
	}

	// Parent edit child information
	public function edit_pupils()
	{
		/*$data['pupils_data'] = array(
		 array(
		 'pupil_id' => '119',
		 'fname' => 'fffff',
		 'lname' => 'lllll',
		 'mname' => 'mmmmmm',
		 'year'	=> '113',
		 'class' => 'class1_name',
		 ),
		 array(
		 'pupil_id' => '120',
		 'fname' => 'towtow',
		 'lname' => 'lala',
		 'mname' => 'malala',
		 'year'	=> '117',
		 'class' => 'class2_name',
		 ),
		 );*/
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_EDIT_PUPILS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
				
		/*Validation for students using pupil id and contract id */
		foreach ($data['pupils_data'] as $pupil_data)
		{			
			if(!validate_student_contract($data['contract_id'], $pupil_data['pupil_id']))
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
		}
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
				
		$bw_obj = new Business_user();
		$edit_pupils_obj = $bw_obj->edit_pupils($data);
		echo json_encode($edit_pupils_obj);
	}

	public function get_school_menu_details(){
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_SCHOOL_MENU_DETAILS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if(!validate_school_admin($data))
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
		$bw_obj = new Business_user();
		$menus_res = $bw_obj->get_school_menu_details($data);
		$menus_obj->menus_res = $menus_res;
		$menus_obj->error = FALSE;
		$menus_obj->session_status = TRUE;
		echo json_encode($menus_obj);
	}

	public function save_school_menu_details(){
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_SAVE_SCHOOL_MENU_DETAILS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		if(!validate_school_admin($data))
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
		
		$bw_obj = new Business_user();
		$menus_res = $bw_obj->save_school_menu_details($data);
		$menus_obj->menus_res = $menus_res;
		$menus_obj->error = FALSE;
		$menus_obj->session_status = TRUE;
		echo json_encode($menus_obj);
	}
	
	/* Get Temporary search pupils */
	public function get_order_search_pupils()
	{
		//$data = array('pupil_id'=>'','fname'=>'','mname'=>'','lname'=>'', 'school_id' => '347');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_ORDER_SEARCH_PUPILS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_user();
		$search_pupils_res = $bw_obj->get_order_search_pupils($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$search_pupils_obj = new stdClass();
		$search_pupils_obj->search_pupils_res = $search_pupils_res;
		$search_pupils_obj->error = FALSE;
		$search_pupils_obj->session_status = TRUE;
		echo json_encode($search_pupils_obj);
	}
	
	public function get_pupils_order_menu()
	{
		$data = $this->security->xss_clean($_POST);
  		/*$data['contract_id'] = 27;
  		$data['current_date'] = '2013-10-07';
  		$data['current_week'] = '2013-10-07';
  		$data['school_id'] = 347;
  		$data['week_day'] = 1;*/
  		//$data['temp_pupil'] = array('CA3/6nzltl', 'CA3/o61fek');

		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_PUPILS_ORDER_MENU;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
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
		
		if(isset($data['temp_pupil']))
		{
			foreach($data['temp_pupil'] as $key => $value)
			{
				if(!validate_student_contract($data['contract_id'], $value))
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
			}			
		}
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$order_menu_res = $bw_obj->get_pupils_order_menu($data);
		$order_menu_obj->order_menu_res = $order_menu_res;
		$order_menu_obj->error = FALSE;
		$order_menu_obj->session_status = TRUE;
		echo json_encode($order_menu_obj);
	}
	
	public function get_pupils_order_menu_details(){
		$data = $this->security->xss_clean($_POST);
		// 		$data['contract_id'] = 27;
		// 		$data['current_date'] = '2013-06-11';
		// 		$data['school_id'] = 281;
		// 		$data['student_id'] = 163;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_PUPILS_ORDER_MENU;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
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

		if(!validate_student_contract($data['contract_id'], $data['pupil_id']))
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
			
		$bw_obj = new Business_user();
		$order_menu_res = $bw_obj->get_pupils_order_menu_details($data);
		
		$order_menu_obj->order_menu_res = $order_menu_res;
		$order_menu_obj->error = FALSE;
		$order_menu_obj->session_status = TRUE;
		echo json_encode($order_menu_obj);
	}
	
	// To search and get list of pupils to school office
	public function search_pupils() {
		//$data = array('pupil_id'=>'','fname'=>'','mname'=>'','lname'=>'');
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_SEARCH_PUPILS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_user();
		$search_pupils_res = $bw_obj->search_pupils($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$search_pupils_obj = new stdClass();
		$search_pupils_obj->search_pupils_res = $search_pupils_res;
		$search_pupils_obj->username = $this->session->userdata('user_info')->username;
		$search_pupils_obj->error = FALSE;
		$search_pupils_obj->session_status = TRUE;
		echo json_encode($search_pupils_obj);
	}
	
	/* To save the cash refund */
	public function save_cash_refund() {
		
		/*$refund_arr = array(
                     	 array(
                            	'pupil_id' => 'CA3/1oxfge',
                            	'cash_balance' => 20.00),
                     	 array(
                            	'pupil_id' => 'CA3/yzyn6m',
                            	'cash_balance' => 10.00)
                       	 );*/
		//$data['refund_data'] = $refund_arr;
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$req_data = array('pupils_data' => $data['refund_data']);
		$req_data['from'] = USER_FROM_SAVE_CASH_REFUND;
		$req_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($req_data);
		
		/* Validation for contract id & students id */
		$refund_arr = $data['refund_data'];
		
		foreach($refund_arr as $key => $value)
		{			
			if(!validate_student_contract($data['contract_id'], $value['pupil_id']))
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
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$data['payment_id'] = create_transaction_id($data['contract_id'], CASH, PAYMENT_ITEMS);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['trans_type'] = CASH;
		$data['trans_mode'] = REFUND;
		$bw_obj = new Business_user();
		$save_refund_res = $bw_obj->save_cash_refund($data);	// Call to save refund
		echo json_encode($save_refund_res);
	}
	
	// To make cash payment in school office
	public function make_cash_payment()
	{
		/*$data['pupils_res'] =  Array
				        	(
				            Array
				                (
				                    'pupil_id' => 'CA3/1oxfge',
				                    'amount' => '10.00'
				                ),
				
				            Array
				                (
				                    'pupil_id' => 'CA3/yzyn6m',
				                    'amount' => '11.5'
				                )
				
				       		 );*/
		
		
		$data = $this->security->xss_clean($_POST);
		$data['amount_type'] = CASH;
		$data['payment'] = PAYMENT;
		$data['trans_type'] = PAYMENT_ITEMS;
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$req_data = array('pupils_data' => $data['pupils_res']);
		$req_data['from'] = USER_FROM_MAKE_CASH_PAYMENT;
		$req_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($req_data);
		
		$val_count = 0;
		foreach ($data['pupils_res'] as $pupils_data)
		{
			if(!validate_student_contract($data['contract_id'], $pupils_data['pupil_id']))
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

			if(!(is_numeric($pupils_data['amount'])) ||($pupils_data['amount'] <= 0))
			{
				$val_count++;				
			}
		}
		
		if($val_count > 0)
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
				
		$data['payment_id'] = create_transaction_id($data['contract_id'], $data['amount_type'], $data['trans_type']);
		
		$bw_obj = new Business_user();
		$cash_payment_res = $bw_obj->make_cash_payment($data);
		echo json_encode($cash_payment_res);
	}
	
	/* Get the full History */
	public function get_full_history()
	{
		//$data = array('school_id' => 281, 'page_no' => 1);
		
		$data = $this->security->xss_clean($_POST);
		
		$data['trans_mode'] = CASH;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_FULL_HISTORY;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if($data['page_no'] < 1)
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
		
		if($data['school_id'] != "")
		{
			if(!validate_school_admin($data))
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
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
				
		$bw_obj = new Business_user();
		$full_history_res = $bw_obj->get_full_history($data);
		echo json_encode($full_history_res);
	}
	
	public function get_pupils_topay()
	{
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_GET_PUPILS_TOPAY;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_user();
		$get_pupils_res = $bw_obj->get_pupils_topay($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
				
		$get_pupils_obj = new stdClass();
		$get_pupils_obj->get_pupils_res = $get_pupils_res;
		$get_pupils_obj->username = $this->session->userdata('user_info')->username;
		$get_pupils_obj->error = FALSE;
		$get_pupils_obj->session_status = TRUE;
		echo json_encode($get_pupils_obj);
	}
	
	/*public function make_card_payment()
	{		
		$data = $this->security->xss_clean($_POST);
		$data['amount_type'] = CARD;
		$data['payment'] = PAYMENT;
		$data['trans_type'] = PAYMENT_ITEMS;
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$req_data = array('pupils_data' => $data['pupils_res']);
		$req_data['from'] = USER_FROM_MAKE_CARD_PAYMENT;
		$req_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($req_data);
		
		$allocation_percentage = 0;
		$total_amt = 0;
		
		$val_count = 0;
		foreach ($data['pupils_res'] as $pupils_data)
		{
			if($pupils_data['allocation_percentage'] == '' || $pupils_data['pupil_id'] == '' || $pupils_data['card_type'] == '' || $pupils_data['amount'] == '')
			{
				$val_count++;				
			}							
			
			if(!validate_student_contract($data['contract_id'], $pupils_data['pupil_id']))
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
			
			$allocation_percentage += $pupils_data['allocation_percentage'];
			$total_amt += $pupils_data['amount'];				
				
			if(!(is_numeric($pupils_data['amount'])) ||($pupils_data['amount'] <= 0))
			{
				$val_count++;
			}
		}
		
		if($val_count > 0)
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

		if($allocation_percentage != 100)
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
		
		$data['total_amt'] = $total_amt;
		$data['payment_id'] = create_transaction_id($data['contract_id'], $data['amount_type'], $data['trans_type']);
		$bw_obj = new Business_user();
		$card_payment_res = $bw_obj->make_card_payment($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		echo json_encode($card_payment_res);
	}*/
	
	/* Get the Updated Payment History */
	public function get_card_payment_history()
	{
		//$data = array('page_no' => 1);
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = USER_FROM_GET_CARD_PAYMENT_HISTORY;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);

		if($data['page_no'] < 1)
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
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_user();
		$history_res = $bw_obj->get_card_payment_history($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		$history_obj->history_res = $history_res;
		$history_obj->error = FALSE;
		$history_obj->session_status = TRUE;
		echo json_encode($history_obj);
	}
	
	/* save the order information */
	public function save_order_items(){
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
// 		$data['contract_id'] = 80;
// 		$data['current_date'] ='2013-07-02';
// 		$data['isAdult']=1;
// 		$data['isFsm'] = 1;
// 		$data['isInvoice']=false;
// 		$data['school_id'] = 124;
// 		$data['student_id'] = 183;
// 		$data['order_id'] = 'YO8/OM/00000000272';
// 		$data['myInOrdids'] = ',6196,';
// 		$data['order_details']= Array(
									
// 									Array
// 									(
// 											'mtype' => 11,
// 											'scmid' => 6196,
// 											'netAmt' => 7.75
											
// 									),
// 								Array
// 								(
// 										'mtype' => 12,
// 										'scmid' => 6310,
// 										'netAmt' => 1.55
// 								),
// 								);

		$data['from'] = USER_FROM_SAVE_ORDER_ITEMS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
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

		if(!validate_student_contract($data['contract_id'], $data['pupil_id']))
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
		
		$bw_obj = new Business_user();
		$order_res = $bw_obj->save_order_items($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		$order_obj->history_res = $order_res;
		$order_obj->error = FALSE;
		$order_obj->session_status = TRUE;
		echo json_encode($order_obj);
	}
	/* cancel the order information */
	public function cancel_order_items(){
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
// 		$data['contract_id'] = 27;
// 		$data['current_date'] ='2013-06-27';
// 		$data['school_id'] = 246;
// 		$data['student_id'] = 208;
// 		$data['order_id'] = 'CA3/OM/00000000012';

		$data['from'] = USER_FROM_CANCEL_ORDER_ITEMS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
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
		
		if(!validate_student_contract($data['contract_id'], $data['pupil_id']))
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
		
		$bw_obj = new Business_user();
		$order_res = $bw_obj->cancel_order_items($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		$order_obj->history_res = $order_res;
		$order_obj->error = FALSE;
		$order_obj->session_status = TRUE;
		echo json_encode($order_obj);
	}
	
	public function get_schools_meal_collection()
	{
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_SCHOOLS_MEAL_COLLECTION;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		$bw_obj = new Business_user();
		$school_res = $bw_obj->get_schools_meal_collection($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		echo json_encode($school_res);
	}
	
	public function get_daily_meal_collection_year_class()
	{
		//$data = array('school_id' => 246, 'fulfilment_date' => '2013-10-17');
		
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_DAILY_MEAL_COLLECTION_YEAR_CLASS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if(!isset($data['fulfilment_date']))
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
		
		if(!validate_school_admin($data))
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
		
		// Validate the given fulfilment date is future date or not
		$fuldate_time = strtotime($data['fulfilment_date']);
		if($fuldate_time > time())
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
			
		$bw_obj = new Business_user();
		$meal_res = $bw_obj->get_daily_meal_collection_year_class($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		echo json_encode($meal_res);
	}
	
	public function get_daily_meal_collection_students()
	{
		//$data = array('school_id' => 221, 'year_no' => 6, 'class_key' => 'class1_name', 'class_name' => 'Class 1', 'collect_status' => 0, 'fulfilment_date' => '2013-06-27');	
		
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_DAILY_MEAL_COLLECTION_STUDENTS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
	
		if(!validate_school_admin($data))
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
		
		// Validate the given fulfilment date is future date or not
		$fuldate_time = strtotime($data['fulfilment_date']);
		if($fuldate_time > time())
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
		
		$bw_obj = new Business_user();
		$meal_res = $bw_obj->get_daily_meal_collection_students($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		echo json_encode($meal_res);
		
	}
	
	public function print_daily_meal_collection()
	{
		//$data = array('school_id' => 246, 'fulfilment_date' => '2013-10-17');
		
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_DAILY_MEAL_COLLECTION_YEAR_CLASS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if(!validate_school_admin($data))
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
		
		$bw_obj = new Business_user();
		$meal_res = $bw_obj->print_daily_meal_collection($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		echo json_encode($meal_res);
	}
	
	public function update_daily_meal_collection_status()
	{
		//$data = array('collect_status' => 0, 'school_id' => 281, 'students_id' => 166, 'order_id' => 'CA3/OM/00000000008', 'fulfilment_date' => '2013-07-08');
		
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_UDPATE_DAILY_MEAL_COLLECTION_STATUS;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		
		if(!validate_school_admin($data))
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
		
		$bw_obj = new Business_user();
		$meal_res = $bw_obj->update_daily_meal_collection_status($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		echo json_encode($meal_res);
	}
	
	public function get_meal_order_summary()
	{
//		$data = array('school_id' => "131", 'start_date' => '2013-06-20', 'end_date' => '2013-07-20');	
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
// 		$data['school_id'] = 327;
// 		$data['start_date'] ='2013-08-19';
// 		$data['end_date'] = '2013-08-23';

		$data['from'] = USER_FROM_GET_MEAL_ORDER_SUMMARY;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		if($data['school_id'] != '0')
		{			
			if(!validate_school_admin($data))
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
				return $error_obj;
			}
		}
		
		$bw_obj = new Business_common();
		$meal_summary_res = $bw_obj->get_meal_order_summary($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		echo json_encode($meal_summary_res);
	}
	
	public function get_user_authroization()
	{
		//$data = array('school_id' => 313, 'start_date' => '2013-06-20', 'end_date' => '2013-07-20');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
	
		$bw_obj = new Business_user();
		$auth_res = $bw_obj->get_user_authroization($data);
		$auth_obj->auth_res = $auth_res;
		$auth_obj->error = FALSE;
		$auth_obj->session_status = TRUE;
		echo json_encode($auth_obj);
	}
	
	public function initiate_card_payment()
	{
		/*$data['pupils_res'] =  Array
				        	(
				            Array
				                (
				                    'pupil_id' => 'CA3/1oxfge',
				                    'amount' => '10.5',
				                    'card_type' => 'dc',
				                    'allocation_percentage' => '50'
				                ),
				
				            Array
				                (
				                    'pupil_id' => 'CA3/yzyn6m',
				                    'amount' => '11.5',
				                    'card_type' => 'cc',
				                    'allocation_percentage' => '50'
				                )
				       		 );*/
		
		$data = $this->security->xss_clean($_POST);
		$data['amount_type'] = CARD;
		$data['payment'] = PAYMENT;
		$data['trans_type'] = PAYMENT_ITEMS;
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$req_data = array('pupils_data' => $data['pupils_res']);
		$req_data['from'] = USER_FROM_MAKE_CARD_PAYMENT;
		$req_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($req_data);
		
		$allocation_percentage = 0;
		$total_amt = 0;
		
		$val_count = 0;
		foreach ($data['pupils_res'] as $pupils_data)
		{
			if($pupils_data['allocation_percentage'] == '' || $pupils_data['pupil_id'] == '' || $pupils_data['card_type'] == '' || $pupils_data['amount'] == '')
			{
				$val_count++;				
			}							
			
			if(!validate_student_contract($data['contract_id'], $pupils_data['pupil_id']))
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
			
			$allocation_percentage += $pupils_data['allocation_percentage'];
			$total_amt += $pupils_data['amount'];				
				
			if(!(is_numeric($pupils_data['amount'])) ||($pupils_data['amount'] <= 0))
			{
				$val_count++;
			}
		}
		
		if($val_count > 0)
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

		if($allocation_percentage != 100)
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
		
		$data['total_amt'] = $total_amt;
		$data['payment_id'] = create_transaction_id($data['contract_id'], $data['amount_type'], $data['trans_type']);
		$bw_obj = new Business_user();
		$total_amount = $bw_obj->initiate_card_payment($data);
		if($total_amount)
		{
			$amount = $total_amount * 100;
                        //$amount = '3021'; - reverted back to original amount 
			$digest = $data['payment_id']. $amount. YES_PAY_PASSWORD;
			$sha_digest = sha1($digest);
			$res = array('merchant_id' => YES_PAY_MERCHANT_ID, 'password' => YES_PAY_PASSWORD, 'mtr' => $data['payment_id'], 'digest' => $sha_digest, 'amount' => $amount);
			
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$payment_obj = new stdClass();
			$payment_obj->error = FALSE;
			$payment_obj->payment_res = $res;
			$payment_obj->session_status = TRUE;
			
			echo json_encode($payment_obj);
			exit;
		}
		else 
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = DATABASE_QUERY_FAILED;
			$error_obj->session_status = TRUE;
			
			echo json_encode($error_obj);
			exit;
		}
	}
	
	public function save_card_payment()
	{
		/*$data['mtr'] = 'CA3/WP/00000000105';
		$data['pgtr'] = 'PGTR641940645';
		$data['yp_code'] = 0;
		$data['auth_id'] = 'AUTH3F95FBFE9251CD5D3D6FAC5B2966DE01';*/
		$data = $this->security->xss_clean($_POST);
		
		$data['amount_type'] = CARD;
		$data['payment'] = PAYMENT;
		$data['trans_type'] = PAYMENT_ITEMS;
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$bw_obj = new Business_user();
		$card_res = $bw_obj->save_card_payment($data);
		
		echo json_encode($card_res);
		exit;
	}
	
	public function cancel_card_payment()
	{
		/*$data['mtr'] = 'CA3/WP/00000000142';
		$data['yp_code'] = 555;*/
		$data = $this->security->xss_clean($_POST);
		
		$data['amount_type'] = CARD;
		$data['payment'] = PAYMENT;
		$data['trans_type'] = PAYMENT_ITEMS;
		$data['cuser_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$bw_obj = new Business_user();
		$card_res = $bw_obj->cancel_card_payment($data);
		
		echo json_encode($card_res);
		exit;
	}
	
	
	/* For resource management setcion...*/
	
	/* Get the zone dashboard */
	
	public function get_zone_dashboard()
	{
		
		//$data = array('page_no'=>'1');		
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_ZONE_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
			
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
			
		$bw_obj = new Business_common();
		$zone_res = $bw_obj->get_zone_dashboard($data);	//get zone Dashboard
		
		$zone_obj = new stdClass();
		$zone_obj->zond_dash = $zone_res;
		$zone_obj->error = FALSE;
		$zone_obj->session_status = TRUE;
		echo json_encode($zone_obj);
	}
	
	/* Get the zone chart details */
	public function get_zone_chart_details()
	{
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_GET_ZONE_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
			
		if(!validate_zone($data))	//Validating zone
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
			
		$bw_obj = new Business_common();
		$zone_res = $bw_obj->get_zone_chart_details($data);	//get zone chart details
	
		$zone_obj = new stdClass();
		$zone_obj->zone_details = $zone_res;
		$zone_obj->error = FALSE;
		$zone_obj->session_status = TRUE;
		/*echo "<pre>";
			print_r($zone_obj);*/
		echo json_encode($zone_obj);
	}
	
	// For school closing
	public function school_close()
	{
		//$data = array('school_id' => "347", 'close_till' => '2013-12-20', 'reason' => 'test reason');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_SCHOOL_CLOSE;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_school_admin($data))
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
		
		if($data['school_id'] != '0')
		{
			if(!validate_school($data))	//Validating schools
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
		else 
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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
		$bw_obj = new Business_common();
		$school_close_res = $bw_obj->school_close($data);
		echo json_encode($school_close_res);
	}
	
	// For school open
	public function school_open()
	{
		//$data = array('school_id' => "281");
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_SCHOOL_CLOSE;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_school_admin($data))
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
		
		if($data['school_id'] != '0')
		{
			if(!validate_school($data))	//Validating schools
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
		else 
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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
		$bw_obj = new Business_common();
		$school_open_res = $bw_obj->school_open($data);
		echo json_encode($school_open_res);
	}
	
	/* Get Batch order Cancellation */
	public function get_batch_order_cancellation()
	{
		//$data = array('school_id' => '347', 'clear' => '0', 'page' => 1);
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_BATCH_ORDER_CANCELLATION;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_school_admin($data))
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

		if($data['school_id'] != '0')
		{
			if(!validate_school($data))	//Validating schools
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
		else 
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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
		$bw_obj = new Business_user();
		$batch_res = $bw_obj->get_batch_order_cancellation($data);
		echo json_encode($batch_res);
	}
	
	/* Cancel order items using batch cancel id */
	public function batch_cancel_order_items()
	{
		//$data = array('school_id' => '347', 'batch_cancel_id' => '2');
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_UPDATE_BATCH_ORDER_CANCEL_ORDER;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_school_admin($data))
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

		if($data['school_id'] != '0')
		{
			if(!validate_school($data))	//Validating schools
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
		else 
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		if(!validate_batch_cancel_id($data['batch_cancel_id']))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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
		$bw_obj = new Business_user();
		$batch_res = $bw_obj->batch_cancel_order_items($data);
		echo json_encode($batch_res);
	}
	
	/* Update the user message in batch order */
	public function update_batch_order_user_msg()
	{
		//$data = array('batch_cancel_id' => '2', 'user_msg' => 'Test Message');		
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_UPDATE_BATCH_ORDER_USER_MESSAGE;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_batch_cancel_id($data['batch_cancel_id']))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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
		$bw_obj = new Business_user();
		$batch_res = $bw_obj->update_batch_order_user_msg($data);
		echo json_encode($batch_res);
	}
	
	/* Update the user message in batch order */
	public function update_batch_order_clear_flag()
	{
		//$data = array('batch_cancel_id' => '2', 'clear' => '1');		
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_UPDATE_BATCH_ORDER_CLEAR_FLAG;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_batch_cancel_id($data['batch_cancel_id']))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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
		$bw_obj = new Business_user();
		$batch_res = $bw_obj->update_batch_order_clear_flag($data);
		echo json_encode($batch_res);
	}
        
	/*
	 * Email Parents from Batch Order
	 */
	public function batch_email_parents()
	{
		/*$data['batch_cancel_id'] = '18';
		$data['email_from'] = 'noreply@gmail.com';
		$data['email_sub'] = 'Batch Order';
		$data['email_msg'] = 'Message';*/
		
		$data = $this->security->xss_clean($_POST);

		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_UPDATE_BATCH_ORDER_CLEAR_FLAG;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];

		if(!validate_batch_cancel_id($data['batch_cancel_id']))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
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

		$bw_obj = new Business_user();
		$batch_email_res = $bw_obj->batch_email_parents($data);
		echo json_encode($batch_email_res);
	}
	
	public function asset_dash_search()
	{
		//$data = array('zone_id'=>2);
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_ADD_ASSET;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_common();
		$asset_res = $bw_obj->asset_dash_search($data);	//get asset result
		$asset_obj = new stdClass();
		$asset_obj->asset_details = $asset_res;
		$asset_obj->error = FALSE;
		$asset_obj->session_status = TRUE;
		echo json_encode($asset_obj);
	}
	
	
	public function digital_form_load(){
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		if(isset($data['type']))
		{
			if($data['type'] == 'df')
				$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
			else if($data['type'] == 'tdm')
				$data['from'] = USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS;
			else if($data['type'] == 'dmo')
				$data['from'] = USER_FROM_DAILY_MEAL_ORDERS;
			else
			{
				$error_obj->error = TRUE;
				$error_obj->error_msg = INPUT_DATA_MISSING;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
			
			$data['msg'] = LOG_READ;
			$log_msg = get_session_log_message($data);
			$log_data['contract_id'] = $data['contract_id'];
			
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
		}
		else 
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$bw_obj = new Business_user();
		$digital_form_res = $bw_obj->digital_form_load($data);	//digital_form_filter_load
		$digital_form_obj = new stdClass();
		$digital_form_obj->df = $digital_form_res;
		$digital_form_obj->error = FALSE;
		$digital_form_obj->session_status = TRUE;
		echo json_encode($digital_form_obj);
		
	}
	
	public function digital_form_filter(){
		$data = $this->security->xss_clean($_POST);
		//$data = array('start_date' => '2012-10-28', 'end_date' => '2013-11-12','ft' => '1,2,3,4' ,'p' => '1,2,3','w'=>'0,1','dw'=>'0,1,2,3,4','mt'=>'0','isex'=>'1','page_no'=> 1);
		 
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$digital_form_res = $bw_obj->digital_form_filter($data);	//digital_formfilter
		$digital_form_obj = new stdClass();
		$digital_form_obj->df = $digital_form_res;
		$digital_form_obj->error = FALSE;
		$digital_form_obj->session_status = TRUE;
		echo json_encode($digital_form_obj);
	}
	
	public function view_digital_form()
	{
		$data = $this->security->xss_clean($_POST);
		//$data = array('dfid' => 1);
		
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
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$digital_form_res = $bw_obj->view_digital_form($data);
		echo json_encode($digital_form_res);
		exit;
	}
	
	public function total_daily_meal_filter(){
		
		$data = $this->security->xss_clean($_POST);
		//$data = array('start_date' => '2012-10-28', 'end_date' => '2013-11-12','ft' => '0' ,'p' => '1,2,3','w'=>'0,1,2,3,4,5','dw'=>'0,1,2,3,4','mt'=>'0,1,2,3,4,5,6,7,8,9,10,11,12','isex'=>'1');
			
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$digital_form_res = $bw_obj->total_daily_meal_filter($data);	//digital_formfilter
		$digital_form_obj = new stdClass();
		$digital_form_obj->df = $digital_form_res;
		$digital_form_obj->error = FALSE;
		$digital_form_obj->session_status = TRUE;
		
		//echo "<pre>";
		//print_r($digital_form_obj);
		echo json_encode($digital_form_obj);
		
	}
	
	/* Getting digital form late number details */
	public function get_digital_form_late_number_details()
	{
		$data = $this->security->xss_clean($_POST);
		//$data = array('dfid' => 15, 'ft' => 'L');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['dfid'] == '' || $data['dfid'] == NULL || $data['ft'] == '' || $data['ft'] == NULL)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$late_number_res = $bw_obj->get_digital_form_late_number_details($data);
		echo json_encode($late_number_res);
		exit;
	}
	
	/* Save late number details in digital form */
	public function save_digital_form_late_numbers()
	{
		$data = $this->security->xss_clean($_POST);
		
		/*$data['dfid'] = 15;
		$data['ft'] = 'L';
		$data['wid'] = 2;
		$data['late_details'] = array(
										array('ldfid' => 31, 'nln' => 10),
										array('ldfid' => 32, 'nln' => 10)
										);*/

		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['dfid'] == '' || $data['dfid'] == NULL || $data['ft'] == '' || $data['ft'] == NULL || $data['wid'] == '' || $data['wid'] == NULL)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$late_number_res = $bw_obj->save_digital_form_late_numbers($data);
		echo json_encode($late_number_res);
		exit;
	}
	
	/* Getting digital form exception details */
	public function get_digital_form_exception_details()
	{
		$data = $this->security->xss_clean($_POST);
		//$data = array('dfid' => 15, 'ft' => 'L');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['dfid'] == '' || $data['dfid'] == NULL || $data['ft'] == '' || $data['ft'] == NULL)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$late_number_res = $bw_obj->get_digital_form_exception_details($data);
		echo json_encode($late_number_res);
		exit;
	}
	
	/* Getting digital form exception details */
	public function save_digital_form_exception_details()
	{
		$data = $this->security->xss_clean($_POST);
		//$data = array('dfid' => 15, 'ft' => 'D', 'exp_status' => 2, 'exp_reason' => 'Approved');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_DIG_FORMS;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['dfid'] == '' || $data['dfid'] == NULL || $data['ft'] == '' || $data['ft'] == NULL || $data['exp_status'] == '' || $data['exp_status'] == NULL || $data['exp_reason'] == '' || $data['exp_reason'] == NULL)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$late_number_res = $bw_obj->save_digital_form_exception_details($data);
		echo json_encode($late_number_res);
		exit;
	}
	
	public function get_df_indicators()
	{
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$df_indicators_res = $bw_obj->get_df_indicators($data);	//get digital form indicators
		echo json_encode($df_indicators_res);
	}
	
	// To get total daily meal numbers report
	public function get_df_tdm_numbers()
	{
		$data = $this->security->xss_clean($_POST);
		//$data = array('dfi_id' => '2', 'dfi_desc'=>'Main meal', 'dw' => '0,1,2,3,4,5,6', 'start_date'=>'2013-09-24', 'end_date' => '2013-11-18', 'ft' => '2', 'isex'=>0, 'mt'=>'0,1,2,3,4', 'p'=>'1,2,3,5,4','w'=>'0,1,2,3,4,5');
		
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['muser_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$df_tdm_numbers_res = $bw_obj->get_df_tdm_numbers($data);
		//echo '<pre>';
		//print_r($df_tdm_numbers_res); exit;
		echo json_encode($df_tdm_numbers_res);
	}
	
	/* Get Quality Auditor Master data */
	public function view_quality_auditor_load()
	{
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_QUALITY_AUDIT_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$quality_audit_res = $bw_obj->view_quality_auditor_load($data);
		echo json_encode($quality_audit_res);
		exit;
	}
	
	/* Get quality audit data based on filter criteria */
	public function quality_audit_filter(){
		$data = $this->security->xss_clean($_POST);
		/*$data = array(
				'start_date' => '2013-01-24 08:55:18', 
				'end_date' => '2013-01-30 09:22:08', 
				'ac_id' => '1', 
				'st' => '1', 
				'ar' => '1', 
				'sar' => '1', 
				'p' => '"Corridors", "Kitchen, Vending, and Breakout Areas"', 
				'au' => '1'
				);*/
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = USER_FROM_QUALITY_AUDIT_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);

		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends

		$bw_obj = new Business_user();
		$qa_res = $bw_obj->quality_audit_filter($data);	//digital_formfilter
		$qa_obj = new stdClass();
		$qa_obj->qa_res = $qa_res;
		$qa_obj->error = FALSE;
		$qa_obj->session_status = TRUE;
		echo json_encode($qa_obj);
	}
	
	public function get_daily_meal_orders(){

		$data = $this->security->xss_clean($_POST);
		//$data = array('start_date' => '2013-10-04', 'ft' => '1' ,'p' => '1,2,3','w'=>'0,1,2,3,4,5','dw'=>'0,1,2,3,4','mt'=>'0,1,2,3,4,5,6,7,8,9,10,11,12','isex'=>'1');
			
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_DAILY_MEAL_ORDERS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$digital_form_res = $bw_obj->get_daily_meal_orders($data);	//digital_formfilter
		$digital_form_obj = new stdClass();
		$digital_form_obj->df = $digital_form_res;
		$digital_form_obj->error = FALSE;
		$digital_form_obj->session_status = TRUE;
		
		//echo "<pre>";
		//print_r($digital_form_obj);
		echo json_encode($digital_form_obj);
	}
	
	/* Get SLA Report */
	public function get_sla_report(){			
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_REPORT_BUILDER;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$sla_report_res = $bw_obj->get_sla_report($data);	//digital_formfilter
		$sla_report_obj = new stdClass();
		$sla_report_obj->sla_res = $sla_report_res;
		$sla_report_obj->error = FALSE;
		$sla_report_obj->session_status = TRUE;

		echo json_encode($sla_report_obj);
	}
	
	/* Delete SLA Report */
	public function delete_sla_report(){
		$data = $this->security->xss_clean($_POST);
		//$data = array('report_id' => '1', 'account_id' => '5');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_REPORT_BUILDER;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['report_id'] == '' || $data['account_id'] == '')
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
		
		// Validate account
		if(!validate_qa_account($data))
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
		
		$bw_obj = new Business_user();
		$sla_report_res = $bw_obj->delete_sla_report($data);	//digital_formfilter
		$sla_report_obj = new stdClass();
		$sla_report_obj->sla_res = $sla_report_res;
		$sla_report_obj->error = FALSE;
		$sla_report_obj->session_status = TRUE;

		echo json_encode($sla_report_obj);
	}
	
	
	public function get_invoice_orders(){
		$data = $this->security->xss_clean($_POST);
		//$data = array('school_id' => '347','page_no'=>1);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_user();
		$invoice_order_res = $bw_obj->get_invoice_orders($data);	//Invoice Orders
		$invoice_order_obj = new stdClass();
		$invoice_order_obj->ino = $invoice_order_res;
		$invoice_order_obj->error = FALSE;
		$invoice_order_obj->session_status = TRUE;
		
		//echo "<pre>";
		//print_r($invoice_order_obj);
		echo json_encode($invoice_order_obj);
	} 
	
	public function get_inv_order_details(){
	
		$data = $this->security->xss_clean($_POST);
		//$data = array('invid' => '1');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		
		$bw_obj = new Business_user();
		$hosp_order_res = $bw_obj->get_inv_order_details($data);	//Invoice Order details
		$hosp_order_obj = new stdClass();
		$hosp_order_obj->ino = $hosp_order_res;
		$hosp_order_obj->error = FALSE;
		$hosp_order_obj->session_status = TRUE;
		
		//echo "<pre>";
		//print_r($hosp_order_obj);
		echo json_encode($hosp_order_obj);
	}
	
	public function save_inv_order_details(){

		$data = $this->security->xss_clean($_POST);
		//$data = array('invid'=>'9', 'school_id'=>'347', 'event_date'=>'2013-12-28', 'd'=>'this is a descriptionup', 'amt'=>'1000');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['school_id'] == '' || $data['event_date'] == '')
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
		
		$bw_obj = new Business_user();
		$inv_order_res = $bw_obj->save_inv_order_details($data);	//save invoice order details
		$inv_order_obj = new stdClass();
		$inv_order_obj->sla_res = $inv_order_res;
		$inv_order_obj->error = FALSE;
		$inv_order_obj->session_status = TRUE;
		
		echo json_encode($inv_order_obj);
		
	}
	
	public function cancel_inv_order_details(){
		
		$data = $this->security->xss_clean($_POST);
		//$data = array('invid'=>'9');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if($data['invid'] == '')
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
		
		$bw_obj = new Business_user();
		$inv_order_res = $bw_obj->cancel_inv_order_details($data);	//save invoice order details
		$inv_order_obj = new stdClass();
		$inv_order_obj->sla_res = $inv_order_res;
		$inv_order_obj->error = FALSE;
		$inv_order_obj->session_status = TRUE;
		
		echo json_encode($inv_order_obj);
		
	}
	public function search_pupil_debt_order(){
		$data = $this->security->xss_clean($_POST);
		//$data = array('pupil_id'=>'','fname'=>'','mname'=>'','lname'=>'', 'school_id' => '221','pupils'=>'YO8/14rv02,YO8/a2wh17');
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		
		$bw_obj = new Business_user();
		$pupil_res = $bw_obj->search_pupil_debt_order($data);	//Search pupils
		$pupil_obj = new stdClass();
		$pupil_obj->pup = $pupil_res;
		$pupil_obj->error = FALSE;
		$pupil_obj->session_status = TRUE;
		
		//echo "<pre>";
		//print_r($pupil_obj);
		echo json_encode($pupil_obj);
	}

	public function save_pupil_meal_order_details(){
	
		$data = $this->security->xss_clean($_POST);
		//$data = array('invid'=>'24', 'school_id'=>'347', 'event_date'=>'2013-12-28', 'pupils'=>'YO8/14rv02,YO8/3frc2o');
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
	
		if($data['school_id'] == '' || $data['event_date'] == '')
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
	
		$bw_obj = new Business_user();
		$inv_order_res = $bw_obj->save_pupil_meal_order_details($data);	//save pupil meal order details
		$inv_order_obj = new stdClass();
		$inv_order_obj->sla_res = $inv_order_res;
		$inv_order_obj->error = FALSE;
		$inv_order_obj->session_status = TRUE;
	
		echo json_encode($inv_order_obj);
	
	}
	
	public function get_pupil_inv_order_details(){
	
		$data = $this->security->xss_clean($_POST);
		//$data = array('invid' => '24');
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = USER_FROM_INVOICE_ORDERS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
	
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
	
	
		$bw_obj = new Business_user();
		$hosp_order_res = $bw_obj->get_pupil_inv_order_details($data);	//pupil meal debt Invoice Order details
		$hosp_order_obj = new stdClass();
		$hosp_order_obj->ino = $hosp_order_res;
		$hosp_order_obj->error = FALSE;
		$hosp_order_obj->session_status = TRUE;
	
		//echo "<pre>";
		//print_r($hosp_order_obj);
		echo json_encode($hosp_order_obj);
	}
	
	/* Get Year & Class details for Particular Schools */
	public function get_schools_year_class_details()
	{
		//$data = array('school_id' => '281');
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = USER_FROM_MANAGE_PUPILS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
	
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
	
		$bw_obj = new Business_user();
		$school_yc_res = $bw_obj->get_schools_year_class_details($data);	//pupil meal debt Invoice Order details
		$school_yc_obj = new stdClass();
		$school_yc_obj->yc_res = $school_yc_res;
		$school_yc_obj->error = FALSE;
		$school_yc_obj->session_status = TRUE;
	
		echo json_encode($school_yc_obj);
	}
	
	/* Search Pupils - Manage Pupils */
	public function get_school_pupil_search()
	{
		/*$data = array('school_id' => '281',
					 	'pupil_list' => '', 
						'pupil_id' => '', 
						'fname' => 'John', 
						'mname' => '',
		 				'lname' => '', 
		 				'year_id' => '', 
		 				'class_col' => '', 
		 				'class_name' => '', 
		 				'all_year' => '', 
		 				'all_class' => '',
		 				'page' => 1);*/
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_MANAGE_PUPILS;
		$data['msg'] = LOG_READ;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if(!validate_school_admin($data))
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
	
		$bw_obj = new Business_user();
		$school_pupil_res = $bw_obj->get_school_pupil_search($data);	//pupil meal debt Invoice Order details
		$school_pupil_obj = new stdClass();
		$school_pupil_obj->search_pupils_res = $school_pupil_res;
		$school_pupil_obj->error = FALSE;
		$school_pupil_obj->session_status = TRUE;
	
		echo json_encode($school_pupil_obj);
	}
	
	/* Update FSM, Adult, Active for pupils */
	public function update_pupil_fsm_adult_acive_status()
	{
		//$data = array('school_id' => '281', 'pupil_id' => 'CA3/1oxfge', 'fsm' => '1', 'adult' => '1', 'active' => '1');
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = USER_FROM_MANAGE_PUPILS;
		$data['msg'] = LOG_WRITE;
		$log_data['contract_id'] = $data['contract_id'];
		$log_msg = get_session_log_message($data);
		
		if(!validate_school_admin($data))
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
		
		if(!validate_student_contract($data['contract_id'], $data['pupil_id']))
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
	
		$bw_obj = new Business_user();
		$update_pupil_res = $bw_obj->update_pupil_fsm_adult_acive_status($data);	//pupil meal debt Invoice Order details
		$update_pupil_obj = new stdClass();
		$update_pupil_obj->update_pupils = $update_pupil_res;
		$update_pupil_obj->error = FALSE;
		$update_pupil_obj->session_status = TRUE;
	
		echo json_encode($update_pupil_obj);
	}
}
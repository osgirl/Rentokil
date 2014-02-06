<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'business/business_customeradmin.php';
require_once APPPATH.'business/business_common.php';

class Customeradmin extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		
		/* Validating the Role */
		if(!validate_role(CUSTOMER_ADMIN))
		{
		 //ob_start();
		 $error_obj->error = TRUE;
		 $error_obj->error_msg = UNAUTHOURIZED_ACCESS;
		 $error_obj->session_status = FALSE;
		 echo json_encode($error_obj);
		 exit;
		}
                
		/* Validating the session id*/
		if (!empty($_POST['session_id']))
		{
		 $user_session = $_POST['session_id'];
		 if(!validate_session($user_session))
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
		 $error_obj->error = TRUE;
		 $error_obj->error_msg = INPUT_DATA_MISSING;
		 $error_obj->session_status = FALSE;
		 echo json_encode($error_obj);
		 exit;
		}
	}
	
	public function save_contract_session()
	{
		//$data['contract_id'] = 80;
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$bw_obj = new Business_customeradmin();
		$con_res = $bw_obj->save_contract_session($data);
		
		echo json_encode($con_res);
		exit;
	}

	/* Get User details */
	public function get_users()
	{
		//$data = array('customer_id'=>$customer_id,'role_id'=>'3');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		$data['from'] = FROM_GET_USERS;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);

			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
			
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
			
		$data['role_id'] = USER;
		$bw_obj = new Business_customeradmin();
		$users_res = $bw_obj->get_users($data);
		$user_obj = new stdClass();
		$user_obj->users_res = $users_res;
		$user_obj->error = FALSE;
		$user_obj->session_status = TRUE;
		echo json_encode($user_obj);
	}

	/* Creating the User */
	public function create_user()
	{
		//$data = array('customer_id'=>'1','user_fname'=>'tst user','user_lname'=>'TT','user_email'=>'dsef2344@dfdf.com');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		$data['from'] = FROM_CREATE_USERS;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(USERS_ACCESS))
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$bw_obj = new Business_customeradmin();
		/* Validating the post variables */
		if (($data['user_fname']!='')&&($data['user_lname']!='')&&($data['user_email']!=''))
		{
			$create_user = $bw_obj->create_user($data);
			if ($create_user->error)
			{
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				
				$create_user_obj->error = TRUE;
				$create_user_obj->error_msg = $create_user->error_msg;
				$create_user_obj->session_status = TRUE;
				echo json_encode($create_user_obj);
			}
			else
			{
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				
				$create_user_obj->error = FALSE;
				$create_user_obj->session_status = TRUE;
				echo json_encode($create_user_obj);
			}
		}
		else
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			
			$create_user_obj->error = TRUE;
			$create_user_obj->error_msg = INPUT_DATA_MISSING;
			$create_user_obj->session_status = TRUE;
			echo json_encode($create_user_obj);
		}
	}
	
	/* Updating the user status */
	public function update_user_status()
	{
		//$data = array('customer_id'=>$customer_id,'role_id'=>'3','status'=>'1','user_id'=>'4');

		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['role_id'] = USER;
		
		$data['from'] = FROM_UPTATE_USER_STATUS;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(USERS_ACCESS))
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}

		$bw_obj = new Business_customeradmin();
		
		/* Checking whethere the User id is null or not */
		if (($data['user_id']!='') && ($data['status']!=''))
		{
			if(!validate_user_customer($data))	//validating the user_id & customer_id
			{
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		
			$update_user_status = $bw_obj->update_user_status($data);
			if ($update_user_status)
			{
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				
				$update_user_status_obj->error = FALSE;
				$update_user_status_obj->session_status = TRUE;
				echo json_encode($update_user_status_obj);
			}
			else
			{
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				
				$update_user_status_obj->error = TRUE;
				$update_user_status_obj->session_status = TRUE;
				echo json_encode($update_user_status_obj);
			}
		}
		else
		{
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
				
			$update_user_status_obj->error = TRUE;
			$update_user_status_obj->error_msg = INPUT_DATA_MISSING;
			$update_user_status_obj->session_status = TRUE;
			echo json_encode($update_user_status_obj);
		}
	}

	/* Creating the contract */
	public function create_contract()
	{
		//$data = array('contract_name'=>'Test contract4');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		/*validate profile access */
		if(!validate_create_contract_access($data))
		{			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}

		$bw_obj = new Business_customeradmin();
		if (!empty($data['contract_name'])&&!empty($data['customer_id']))
		{
			$create_contract = $bw_obj->create_contract($data);				
			echo $create_contract;
		}
		else
		{
			$create_contract_obj->error = TRUE;
			$create_contract_obj->error_msg = INPUT_DATA_MISSING;
			$create_contract_obj->session_status = TRUE;
			echo json_encode($create_contract_obj);
		}
	}

	/* Creating the school */
	public function create_school()
	{
		//$data = array('school_name'=>'testschools','contract_id'=>'135','user_id'=>5,'production_status'=>0, 'production_id'=>4);
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_CREATE_SCHOOL;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SCHOOL_CONFIGURATION_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		/* Validating the input variables */
		if (($data['school_name']!='')&&($data['contract_id']!='')&&($data['production_status']!==''))
		{
			if(!validate_contract($data['contract_id']))	//validating the contract id
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
			
			$bw_obj = new Business_customeradmin();
			$create_school = $bw_obj->create_school($data);
			if ($create_school->error)
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
			
				$create_school_obj->error = TRUE;
				$create_school_obj->error_msg = $create_school->error_msg;
				$create_school_obj->session_status = TRUE;
				echo json_encode($create_school_obj);
			}
			else
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				
				$create_school_obj->error = FALSE;
				$create_school_obj->session_status = TRUE;
				echo json_encode($create_school_obj);
			}
		}
		else
		{
			$create_school_obj->error = TRUE;
			$create_school_obj->error_msg = INPUT_DATA_MISSING;
			$create_school_obj->session_status = TRUE;
			echo json_encode($create_school_obj);
		}
	}
	
	/* Get the school details */
	public function get_schools()
	{
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		$data['from'] = FROM_GET_SCHOOLS;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$schools_res = $bw_obj->get_schools($data);	//get school details
		$contract_res = $bw_obj->get_contract($data);	// get contract details
		$school_obj = new stdClass();
		$school_obj->contract_res = $contract_res;
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}
	
	/* Editing the profile details */
	public function edit_profile()
	{
		//$user_session = $this->session->userdata('user_session');
		/*$data = array(
		 'title_id'=>'1',
		 'first_name'=>'testfirst 111',
		 'last_name'=>'testlast 222',
		 'user_email'=>'newemail@test.com',
		 'telephone'=>'+90 823773636'
			);*/
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		$bw_obj = new Business_customeradmin();
		if (!empty($data['title_id'])&&!empty($data['user_email'])&&!empty($data['user_id']))	// Validating input data
		{
			if(!validate_user_customer($data))	//validating the user_id & customer_id
			{
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
			
			$edit_profile = $bw_obj->edit_profile($data);
			echo $edit_profile;
		}
		else
		{			
			$edit_profile_obj->error = TRUE;
			$edit_profile_obj->error_msg = INPUT_DATA_MISSING;
			$edit_profile_obj->session_status = TRUE;
			echo json_encode($edit_profile_obj);
		}
	}
	
	/* Getting the contract details */
	public function get_contracts()
	{
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_customeradmin();
		$contracts_res = $bw_obj->get_contracts($data);
		$contract_obj->contracts_res = $contracts_res;
		$contract_obj->error = FALSE;
		$contract_obj->session_status = TRUE;
		echo json_encode($contract_obj);
	}
	
	/* Get the user titles */
	public function get_user_titles()
	{
		$data = $this->security->xss_clean($_POST);
		$user_titles_res = get_user_titles();
		$user_titles_obj->user_titles_res = $user_titles_res;
		$user_titles_obj->error = FALSE;
		$user_titles_obj->session_status = TRUE;
		echo json_encode($user_titles_obj);
	}

	/* Get the data value from DB */
	public function get_data_any()
	{
		//$data = array('data_ref'=>'user_titles');
		$data = $this->security->xss_clean($_POST);
		$data_any_res = get_data_any($data);
		$data_any_obj->data_any_res = $data_any_res;
		$data_any_obj->error = FALSE;
		$data_any_obj->session_status = TRUE;
		echo json_encode($data_any_obj);
	}
	
	/* Update the contract details */
	public function edit_contract()
	{		
		//$data = array('contract_id' => 67, 'contract_name' => 'testing2');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = FROM_EDIT_CONTRACT;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(CONFIGURE_SETTING_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
			
		$bw_obj = new Business_customeradmin();
		$edit_contract = $bw_obj->edit_contract($data);
		echo json_encode($edit_contract);
	}
	
	/* Update the school details */
	public function edit_schools()
	{
		/*$data = array(
		 //'session_id' => 'XXX',
		 'schools_edit' => array(
		 array(
		 'school_id'	=> 286,
		 'contract_id'	=> 135,
		 'school_name'	=> 'school 2',
		 'production_id'	=> 285,
		 //'pupils_no'	=> 50,
		 'production_status' => 0,
		 'status'		=> 1,
		 ),
		 )
		 );*/
			
		$data = $this->security->xss_clean($_POST);
		
		$msg_data['schools'] = $data['schools_edit'];
		$msg_data['from'] = FROM_EDIT_SCHOOLS;		
		$msg_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($msg_data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SERVEYS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		if (!empty($data['schools_edit']))	// Validating the schools edit option 
		{
			//$data['customer_id'] = 1;
			//$data['muser_id'] = 5;
			$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
			$data['muser_id'] = $this->session->userdata('user_info')->user_id;
			
			foreach ($data['schools_edit'] as $school_data)
			{
				if(!validate_school($school_data))	//Validating schools
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
			
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$bw_obj = new Business_customeradmin();
			$edit_school_obj = $bw_obj->edit_schools($data);
			echo json_encode($edit_school_obj);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$edit_school_obj->error = TRUE;
			$edit_school_obj->error_msg = INPUT_DATA_MISSING;
			$edit_school_obj->session_status = TRUE;
			echo json_encode($edit_school_obj);
		}
	}
	
	/* Update the school status */
	public function update_school_status()
	{
		$data = $this->security->xss_clean($_POST);
		/*$data = array(
		 'school_id'=>'1',
		 'contract_id'=>'1',
		 'status'=>'1',
		 );*/
		
		$data['from'] = FROM_UPDATE_SCHOOL_STATUS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SERVEYS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if (($data['school_id']!='') && ($data['contract_id']!='') && ($data['status']!==''))	// input validation
		{
			$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
			$data['muser_id'] = $this->session->userdata('user_info')->user_id;
			
			if(!validate_contract($data['contract_id']))	//validating the contract id
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
			
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$bw_obj = new Business_customeradmin();
			$update_school_status_obj = $bw_obj->update_school_status($data);
			echo json_encode($update_school_status_obj);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$update_school_status_obj->error = TRUE;
			$update_school_status_obj->error_msg = INPUT_DATA_MISSING;
			$update_school_status_obj->session_status = TRUE;
			echo json_encode($update_school_status_obj);
		}
			
	}
	
	/* Getting the HH Reports */
	public function get_HH_reports(){
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_customeradmin();
		
		$data['from'] = FROM_GET_HH_REPORTS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
			
		$HH_reports_res = $bw_obj->get_HH_reports($data);
		$HH_reports_obj->HH_reports_res = $HH_reports_res;
		$HH_reports_obj->error = FALSE;
		$HH_reports_obj->session_status = TRUE;
		//print_r($HH_reports_obj);
		echo json_encode($HH_reports_obj);
	}
	
	/* Getting NHH Reports */
	public function get_NHH_reports(){
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_NHH_REPORTS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$NHH_reports_res = $bw_obj->get_NHH_reports($data);
		$NHH_reports_obj->NHH_reports_res = $NHH_reports_res;
		$NHH_reports_obj->error = FALSE;
		$NHH_reports_obj->session_status = TRUE;
		//print_r($HH_reports_obj);
		echo json_encode($NHH_reports_obj);
	}
	
	/* Getting the Target Data values */
	public function get_target_data(){
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_TARGET_DATA;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$target_data_res = $bw_obj->get_target_data($data);
		$target_data_obj->target_data_res = $target_data_res;
		$target_data_obj->error = FALSE;
		$target_data_obj->session_status = TRUE;
		echo json_encode($target_data_obj);
	}
	
	/* Getting Setup Entities */
	public function get_setup_entities(){
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_SETUP_ENTITIES;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$setup_entities_res = $bw_obj->get_setup_entities($data);

		$setup_entities_obj->setup_entities_res = $setup_entities_res;
		$setup_entities_obj->error = FALSE;
		$setup_entities_obj->session_status = TRUE;
		//print_r($setup_entities_obj);
		echo json_encode($setup_entities_obj);
	}
	
	/* Getting Data History */
	public function get_data_history(){
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_DATA_HISTORY;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_customeradmin();
		$history_data_res = $bw_obj->get_data_history($data);
		$history_data_obj->history_data_res = $history_data_res;
		$history_data_obj->error = FALSE;
		$history_data_obj->session_status = TRUE;
		//print_r($setup_entities_obj);
		echo json_encode($history_data_obj);
	}

	/* Getting the school details */
	public function get_school_details(){
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_SCHOOL_DETAILS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_school_details($data);
		$school_obj->schools_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Update school details */
	public function save_school_details(){
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		//$data['user_id'] = 1;
		//$data['form_data'] ='hdnSchoolId=31&txtAddress1=address+11&txtAddress2=address22&txtAddress3=address+33&txtCity=city&txtCounty=country&txtPostcode=AA9A+9AA&txtOffC1Name=contact+1+name&txtOffC2Name=contact+2+name&txtOffC1Email=contact1email%40email.com&txtOffC2Email=contact2email%40email.com&txtOffC1Telephone=11111111111&txtOffC2Telephone=22222222222&txtY0=reception1&chkY0=1&txtY0C1=class1&chkY0C1=1&txtY0C2=class2&chkY0C2=1&txtY0C3=class3&chkY0C3=1&txtY0C4=class4&chkY0C4=1&txtY0C5=class5&chkY0C5=1&txtY0C6=class6&chkY0C6=1&txtY1=Year1&chkY1=1&txtY1C1=class1&chkY1C1=1&txtY1C2=class2&chkY1C2=1&txtY1C3=class3&chkY1C3=1&txtY1C4=class4&chkY1C4=1&txtY1C5=class5&chkY1C5=1&txtY1C6=class6&chkY1C6=1&txtY2=&txtY2C1=&txtY2C2=&txtY2C3=&txtY2C4=&txtY2C5=&txtY2C6=&txtY3=&txtY3C1=&txtY3C2=&txtY3C3=&txtY3C4=&txtY3C5=&txtY3C6=&txtY4=&txtY4C1=&txtY4C2=&txtY4C3=&txtY4C4=&txtY4C5=&txtY4C6=&txtY5=&txtY5C1=&txtY5C2=&txtY5C3=&txtY5C4=&txtY5C5=&txtY5C6=&txtY6=&txtY6C1=&txtY6C2=&txtY6C3=&txtY6C4=&txtY6C5=&txtY6C6=';
		//$data['contract_id'] = 1;
		
		parse_str( $data['form_data'], $form_data);
		$req_data = array('school_id' => $form_data['hdnSchoolId'], 'contract_id' => $data['contract_id']);
		$req_data['from'] = FROM_SAVE_SCHOOL_DETAILS;	
		$req_data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($req_data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(MY_SCHOOLS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}		
		
		$school_data = array('school_id' => $form_data['hdnSchoolId'], 'contract_id' => $data['contract_id']);
		if(!validate_school($school_data))	//Validating schools
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
		
		$bw_obj = new Business_common();
		if($bw_obj->validate_school_key($data)) {	// Validate school key
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$schools_res = $bw_obj->save_school_details($data);
			$school_obj->schools_res = $schools_res;
			$school_obj->error = FALSE;
			$school_obj->session_status = TRUE;
			echo json_encode($school_obj);
		}  else {
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$school_obj->error = TRUE;
			$school_obj->error_msg = "Please enter unique school Id.";
			$school_obj->session_status = TRUE;
			echo json_encode($school_obj);
		}
	}

	/* Getting the school documents */
	public function get_school_documents(){
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = USER;
		
		$data['from'] = FROM_GET_SCHOOL_DOCUMENTS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		if(null != $data['school_id'] && $data['school_id'] != "")
		{
			if($data['school_id'] != 0)
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
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->get_school_documents($data);
		$school_obj->schools_documents_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}
	
	/* Getting the comments for school documents */
	public function get_school_document_comments() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = USER;
		
		$data['from'] = FROM_GET_SCHOOL_DOCUMENT_COMMENTS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$schools_res = $bw_obj->get_school_document_comments($data);
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}
	
	/* Updating the status for school document */
	public function update_school_document_status() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_UPDATE_SCHOOL_DOCUMENT_STATUS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(DOCUMENTS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->update_school_document_status($data);
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}
	
	/* Inserting the school document comments */
	public function insert_document_comments() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_INSERT_DOCUMENT_COMMENTS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(DOCUMENTS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->insert_document_comments($data);
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Deleting the comments */
	public function delete_document() {
		//$data = array('contract_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_DELETE_DOCUMENT;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(DOCUMENTS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_common();
		$schools_res = $bw_obj->delete_document($data);
		$school_obj->schools_rep_comm_res = $schools_res;
		$school_obj->error = FALSE;
		$school_obj->session_status = TRUE;
		echo json_encode($school_obj);
	}

	/* Import the pupil data */
	public function get_pupil_import(){
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_PUPIL_IMPORT;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
			
		$bw_obj = new Business_customeradmin();
		$pupil_import_res = $bw_obj->get_pupil_import($data);
		$pupil_import_obj->pupil_import_res = $pupil_import_res;
		$pupil_import_obj->error = FALSE;
		$pupil_import_obj->session_status = TRUE;
		//print_r($pupil_import_obj);
		echo json_encode($pupil_import_obj);
	}

	/* Getting the energy documents */
	public function get_energy_documents(){
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_ENERGY_DOCUMENTS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = USER;
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->get_energy_documents($data);
		$energy_obj->energy_documents_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Inserting the comments for energy document */
	public function insert_energy_document_comments() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = FROM_INSERT_ENERGY_DOCUMENT_COMMENTS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(ENERGY_DOCUMENTS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$energy_res = $bw_obj->insert_energy_document_comments($data);
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Updating the energy document status */
	public function update_energy_document_status() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = FROM_UPDATE_ENERGY_DOCUMENT_STATUS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(ENERGY_DOCUMENTS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$energy_res = $bw_obj->update_energy_document_status($data);
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Getting the enerdy document comments */
	public function get_energy_document_comments() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['other_role_id'] = USER;
		
		$data['from'] = FROM_GET_ENERGY_DOCUMENT_COMMENTS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$energy_res = $bw_obj->get_energy_document_comments($data);
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/* Deleting the comment in energy document */
	public function delete_energy_document() {
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = FROM_DELETE_ENERGY_DOCUMENT;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(ENERGY_DOCUMENTS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$bw_obj = new Business_common();
		$energy_res = $bw_obj->delete_energy_document($data);
		$energy_obj->energy_rep_comm_res = $energy_res;
		$energy_obj->error = FALSE;
		$energy_obj->session_status = TRUE;
		echo json_encode($energy_obj);
	}

	/*Get the contract settings*/
	public function get_contract_settings() {
		$data = $this->security->xss_clean($_POST);
		//$data['contract_id'] = 80;
		
		$data['from'] = FROM_GET_CONTRACT_SETTINGS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$bw_obj = new Business_customeradmin();
		$contracts_res = $bw_obj->get_contract_settings($data);
		$contract_obj->contracts_res = $contracts_res;
		$contract_obj->error = FALSE;
		$contract_obj->session_status = TRUE;
		echo json_encode($contract_obj);
	}

	/*Update contract settings*/
	public function update_contract_settings() {
		$data = $this->security->xss_clean($_POST);
		/*$data['contract_id'] = 80;
		$data['tminus'] = 6;
		$data['adult_invoice'] = '0';
		$data['min_card_pay'] = 7.00;
		$data['vat'] = 25;
		$data['dc_fee'] = 0.50;
		$data['cc_fee'] = 6.00;
		$data['refund_fee'] = 5.00;

		$menu_array = array(
		array(
		 'con_cater_menu_settings_id' => 1,
		 'menu_cycles' => 3,
		 'menu_start_date' => "2013-06-01",
		 'menu_sequence' => 1),
		array(
		 'con_cater_menu_settings_id' => 2,
		 'menu_cycles' => 4,
		 'menu_start_date' => "2013-05-20",
		 'menu_sequence' => 2)
		);

		$data['menu_data'] = $menu_array;*/
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['from'] = FROM_UPDATE_CONTRACT_SETTINGS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SETTINGS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
			
		$menu_input_status = TRUE;
		foreach($data['menu_data'] as $key => $value)	// validate menu input data
		{
			if($value['con_cater_menu_settings_id'] == '' || $value['menu_cycles'] == '' || $value['menu_start_date'] == '' || $value['menu_sequence'] == '')
			{
				$menu_input_status = FALSE;
				break;
			}
		}
		
		if($data['contract_id'] != '' && $data['tminus'] != '' && $data['adult_invoice'] != '' && $data['min_card_pay'] != '' && $data['vat'] != '' && $data['dc_fee'] != '' && $data['cc_fee'] != '' && $data['refund_fee'] != '' && $menu_input_status)	//Validate contract input data
		{
			if(!validate_contract($data['contract_id']))	//validating the contract id
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
			$data['muser_id'] = $this->session->userdata('user_info')->user_id;
			$bw_obj = new Business_customeradmin();
			$contracts_res = $bw_obj->update_contract_settings($data);
			echo json_encode($contracts_res);
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			/* If any input data is missing means we will show the error */
			$contracts_res->error = TRUE;
			$contracts_res->error_msg = INPUT_DATA_MISSING;
			$contracts_res->session_status = TRUE;
			echo json_encode($contracts_res);
			exit;
		}
	}

	/* Update Menu Option Status (Enable or Disable) */
	public function update_menu_option_status()
	{
		$data = $this->security->xss_clean($_POST);
		/*$data = array(
		 'option_id'=> '1',
		 'option_status'=> '1'
		 );*/
		
		$data['from'] = FROM_UPDATE_MENU_OPTION_STATUS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(MENUS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		if (($data['option_id']!='') && ($data['option_status']!=''))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			//$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
			$data['muser_id'] = $this->session->userdata('user_info')->user_id;
			$bw_obj = new Business_customeradmin();
			$update_option_status_obj = $bw_obj->update_menu_option_status($data);
			echo json_encode($update_option_status_obj);
			exit;
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$update_option_status_obj->error = TRUE;
			$update_option_status_obj->error_msg = INPUT_DATA_MISSING;
			$update_option_status_obj->session_status = TRUE;
			echo json_encode($update_option_status_obj);
			exit;
		}
	}
	
	/* Get the Menu Details */
	public function get_menu_details(){
		//$user_session = $this->session->userdata('user_session');
		$data = $this->security->xss_clean($_POST);
			
		// 				$data['contract_id'] = 32;
		// 				$data['menu_seq'] =1;
		// 				$data['week_cycle'] =1;
		
		$data['from'] = FROM_GET_MENU_DETAILS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$menus_res = $bw_obj->get_menu_details($data);
		$menus_obj->menus_res = $menus_res;
		$menus_obj->error = FALSE;
		$menus_obj->session_status = TRUE;
		echo json_encode($menus_obj);
	}

	/* Save the menu details */
	public function save_menu_details(){
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = FROM_SAVE_MENU_DETAILS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(MENUS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$menus_res = $bw_obj->save_menu_details($data);
		$menus_obj->menus_res = $menus_res;
		$menus_obj->error = FALSE;
		$menus_obj->session_status = TRUE;
		echo json_encode($menus_obj);
	}
	
	/* Get the Search Pupils for Card */
	public function get_card_search_pupils()
	{
		//$data = array('transaction_id'=> 'oil/WP/00000000033', 'contract_id' => 128);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['from'] = FROM_GET_CARD_SEARCH_PUPILS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$card_search_pupils_res = $bw_obj->get_card_search_pupils($data);
		echo json_encode($card_search_pupils_res);
	}
	
	//To Save the card refund details
	/*public function save_card_refund() {
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_SAVE_CARD_REUND;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		//validate profile access
		if(!validate_cadmin_profile_access(CARD_REFUNDS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// validation for students id and contract id
		$refund_arr = $data['refund_data'];
		foreach($refund_arr as $key => $value)
		{
			if(!validate_student_contract($data['contract_id'], $value['pupil_id']))
			{
				$error_obj = new stdClass();
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
		
		$data['trans_type'] = CARD;
		$data['trans_mode'] = REFUND;
		$data['payment_id'] = create_transaction_id($data['contract_id'], CARD, PAYMENT_ITEMS);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;	
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$bw_obj = new Business_customeradmin();
		$save_refund_res = $bw_obj->save_card_refund($data);
		echo json_encode($save_refund_res);
	}*/
	
	/* To Get the Full History for payment and refund */
	public function get_card_full_history()
	{
		//$data = array('contract_id' => 27, 'page_no' => 1);
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_CARD_FULL_HISTORY;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		$data['trans_mode'] = CARD;
		
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
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$full_history_res = $bw_obj->get_card_full_history($data);
		echo json_encode($full_history_res);
	}
	
	/* PHP Info screen */
	public function get_php_info()
	{
		ob_start();
		phpinfo();
		$pinfo = ob_get_contents();
		ob_end_clean();
		
		$info_res = preg_replace( '%^.*<body>(.*)</body>.*$%ms', '$1', $pinfo);
		
		$info_obj = new stdClass();
		$info_obj->info_res = $info_res;
		$info_obj->error = FALSE;
		$info_obj->session_status = TRUE;
		echo json_encode($info_obj);
		exit;
	}
	
	/* Get Session log information for particular contract */
	public function get_session_log_contract()
	{
		//$data['contract_id'] = 80;
		$data = $this->security->xss_clean($_POST);
		
		/*$data['from'] = FROM_GET_SESSION_LOG_CONTRACT;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];*/
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			/*// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends*/
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
			
		/*// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends*/
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$bw_obj = new Business_customeradmin();
		$session_res = $bw_obj->get_session_log_contract($data);
		echo json_encode($session_res);
	}
	
	/* Get Session log information using pagination */
	public function get_session_log_navigation()
	{
		/*$data['contract_id'] = 80;
		$data['page_no'] = 1;*/
		
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		/*$data['from'] = FROM_GET_SESSION_LOG_NAVIGATION;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];*/
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			/*// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends*/
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
			
		/*// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends*/
		
		$bw_obj = new Business_customeradmin();
		$session_res = $bw_obj->get_session_log_navigation($data);
		echo json_encode($session_res);
	}
	
	/* Save contract & user information for session log */
	public function save_session_log_contract()
	{
		/*$data['contract_id'] = 80;
		$data['session_log_contract'] = 1; 
		$data['user_data'] = array(
								 array(
		 									'user_id' => '159',
		 									'session_log' => '1'
									 ),
		 						array(
		 									'user_id' => '160',
		 									'session_log' => '1'
		 							)
		 );*/
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		$data['from'] = FROM_SAVE_SESSION_LOG_CONTRACT;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SESSION_LOGS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}		
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				$chk_data = array('user_id' => $value['user_id'], 'customer_id' => $data['customer_id']);
				if(!validate_user_customer($chk_data))	//validating the user_id & customer_id
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
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		$bw_obj = new Business_customeradmin();
		$session_res = $bw_obj->save_session_log_contract($data);
		echo json_encode($session_res);
	}
	
	/* Delete the session log for particular contract */
	public function purge_session_log_contract()
	{
		//$data['contract_id'] = 80;
		
		$data = $this->security->xss_clean($_POST);
		
		/*$data['from'] = FROM_PURGE_SESSION_LOG_CONTRACT;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];*/
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SESSION_LOGS_ACCESS))
		{
			/*// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends*/
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}

		if(!validate_contract($data['contract_id']))	//validating the contract id
		{
			/*// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends*/
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
			
		/*// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends*/
		$bw_obj = new Business_customeradmin();
		$session_res = $bw_obj->purge_session_log_contract($data);
		echo json_encode($session_res);
	}
	
	/* Get All profiles for a contract */
	public function get_profile_master_details()
	{
		//$data['contract_id'] = 80;
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_PROFILE_MASTER_DETAILS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->get_profile_master_details($data);
		echo json_encode($profile_res);
	}
	
	/* Create New Profile for a contract */
	public function create_profile_contract()
	{
		/*$data['contract_id'] = 80;
		$data['profile_name'] = 'Energy Analyst';*/
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_CREATE_PROFILE_CONTRACT;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(PROFILES_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->create_profile_contract($data);
		echo json_encode($profile_res);
	}
	
	/* Get Profile Details */
	public function get_profile_details_contract()
	{
		/*$data['contract_id'] = 80;
		$data['profile_id'] = 2;*/
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_PROFILE_DETAILS_CONTRACT;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
			
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->get_profile_details_contract($data);
		echo json_encode($profile_res);
	}
	
	/* Save Profile details */
	public function save_profile_details()
	{
		/*$data['contract_id'] = 80;
		$data['profile_id'] = 2;
		
		$data['profile_name'] = "Energy Analyst";
		$data['skin_id'] = 1;
		$data['self_registration'] = 1;
		$data['hide_main_nav'] = 1;
		
		$data['m_module_id'] = 2;
		
		$data['profile_s_module_data'] = array(3, 4);
		$data['profile_ss_module_data'] = array(1, 2);
		
		$data['user_data'] = array(
								 array(
		 									'user_id' => '159',
		 									'profile_id' => '2'
									 ),
		 						array(
		 									'user_id' => '160',
		 									'profile_id' => '0'
		 							)
		 );*/
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		
		$data['from'] = FROM_SAVE_PROFILE_DETAILS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(PROFILES_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				$chk_data = array('user_id' => $value['user_id'], 'customer_id' => $data['customer_id']);
					
				if(!validate_user_customer($chk_data))	//validating the user_id & customer_id
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
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->save_profile_details($data);
		echo json_encode($profile_res);
	}
	
	/* Delete Profile */
	public function delete_profile_details()
	{
		/*$data['contract_id'] = 80;
		$data['profile_id'] = 1;*/
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_DELETE_PROFILE_DETAILS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(PROFILES_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->delete_profile_details($data);
		echo json_encode($profile_res);
	}
	
	/* Delete energy data */
	public function purge_energy_data()
	{
		/*$data['contract_id'] = 80;
		$data['start_month'] = 6;
		$data['start_year'] = 2020;
		$data['end_month'] = 7;
		$data['end_year'] = 2020;*/
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_PURGE_ENERGY_DATA;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(PURGE_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$start_month = get_month_name($data['start_month']);
		$end_month = get_month_name($data['end_month']);
		
		/* Calculate starting date from month and year */
		$strtime = $data['start_year'] . "-". $data['start_month'] . "-01";
		$start_date = date("Y-m-d", strtotime($strtime));
		$data['start_date'] = $start_date;
		
		/* Calculate end date from month and year */
		$str_time = $end_month . " ". $data['end_year'];
		$ts = strtotime($str_time);
		$edate = date('t', $ts);
		$strtime = $data['end_year'] . "-". $data['end_month'] . "-". $edate;
		$end_date = date("Y-m-d", strtotime($strtime));
		$data['end_date'] = $end_date;
		
		$start_month = strtotime($data['start_date']);
		$end_month = strtotime($data['end_date']);
		if($start_month > $end_month)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = DATE_VALIDATION_ERROR;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->purge_energy_data($data);
		echo json_encode($profile_res);
	}
	
	/* Configure contract */
	public function get_users_configure_contract()
	{
		//$data = array('contract_id' => 80);
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
		$data['role_id'] = CUSTOMER_ADMIN;
		
		$data['from'] = FROM_GET_USERS_CONFIGURE_CONTRACT;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$profile_res = $bw_obj->get_users_configure_contract($data);
		echo json_encode($profile_res);
	}
	
	/* Save configure contract */
	public function save_users_configure_contract()
	{
		/*$data['contract_id'] = 80;
		$data['user_data'] = array(
								 array(
		 									'user_id' => '249',
		 									'status' => '1'
									 )
		 );*/
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_SAVE_USERS_CONFIGURE_CONTRACT;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];		
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(ADMINISTRATORS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		
		$bw_obj = new Business_customeradmin();
		$user_res = $bw_obj->save_users_configure_contract($data);
		echo json_encode($user_res);
	}
	
	// To get skins list for contract
	function get_skins() 
	{
		//$data['contract_id'] = 128;
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_SKINS;	
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_customeradmin();
		$get_skins_res = $bw_obj->get_skins($data);
		echo json_encode($get_skins_res);
	}
	
	// To get skins list for contract
	function get_skin_details() 
	{
		//$data['contract_id'] = 128;
		//$data['skin_id'] = 119;
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_GET_SKIN_DETAILS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$bw_obj = new Business_customeradmin();
		$get_skin_details = $bw_obj->get_skin_details($data);
		echo json_encode($get_skin_details);
	}
	
	// To upload images for a skin
	function skin_image_upload() 
	{
		$data = $this->security->xss_clean($_POST);
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SKINS_ACCESS))
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		switch ($data['img_type']) {
			case "logo":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "smartphone":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "header_div":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "level12_bg":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "level2_bg":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "no_nav":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "widget_header":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			case "select_bg":
				if($this->skin_move_temp_file($data,$_FILES))
				{
					$skin_obj = new stdClass();
					$skin_obj->error = FALSE;
					$skin_obj->session_status = TRUE;
					echo json_encode($skin_obj);
					exit;
				}
				else 
				{
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = FILE_UPLOAD_ERROR;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			break;
			default:
				$error_obj = new stdClass();
				$error_obj->error = TRUE;
				$error_obj->error_msg = FILE_UPLOAD_ERROR;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			break;
		}
	}
	
	function skin_move_temp_file($data, $file_data) 
	{
		$img_type = $data['img_type'];
		
		if(isset($data['max_h']) && isset($data['max_w']) && isset($data['min_h']) && isset($data['min_w']))
		{			
			$image_info = getimagesize($file_data["files"]["tmp_name"][0]);
			$width = $image_info[0];
			$height = $image_info[1];
			$min_h = $data['min_h'];
			$max_h = $data['max_h'];
			$min_w = $data['min_w'];
			$max_w = $data['max_w'];
			
			if($width < $min_w || $width > $max_w || $height < $min_h || $height > $max_h)
			{
				return FALSE;
			}
		}
		
		$session_id = $this->session->userdata('user_session');

		if($file_data["files"]["tmp_name"][0])
		{
			/* Check whether the temp directory is exists or not. If not then create new directory */
			if(!is_dir(TEMP_SKIN_PATH))
			{
				mkdir(TEMP_SKIN_PATH, 0755);	//Directory creation with full permission
			};
			
			if(!is_dir(TEMP_SKIN_PATH.'/'.$session_id))
			{
				mkdir(TEMP_SKIN_PATH.'/'.$session_id, 0755);	//Directory creation with full permission
			};
			//echo TEMP_SKIN_PATH.$session_id.'/'.$img_type.'.'.SKIN_FILE_FORMAT;
			move_uploaded_file($file_data["files"]["tmp_name"][0],TEMP_SKIN_PATH.$session_id.'/'.$img_type.'.'.SKIN_FILE_FORMAT);
			return TRUE;
		}
		else
		return FALSE;
	}
	
	// To create new skin for contract
	function create_skin() 
	{
		/*$data['contract_id'] = 80;
		$data['skin_name'] = 'New3';*/
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_CREATE_SKIN;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SKINS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['default'] = INACTIVE;
		$bw_obj = new Business_customeradmin();
		$create_skin_res = $bw_obj->create_skin($data);
		echo json_encode($create_skin_res);
	}
	
	// To save skin for contract
	function edit_skin()
	{
		/*$data['contract_id'] = 80;
		$data['sid'] = 55;
		$data['sn'] = "NewSkin1";
		$data['logo'] = 1;
		$data['logo_def'] = 1;
		$data['smartphone'] = 1;
		$data['smartphone_def'] = 1;
		$data['header_div'] = 1;
		$data['header_div_def'] = 1;
		$data['level12_bg'] = 1;
		$data['level12_bg_def'] = 1;
		$data['level2_bg'] = 1;
		$data['level2_bg_def'] = 1;
		$data['no_nav'] = 1;
		$data['no_nav_def'] = 1;
		$data['widget_header'] = 1;
		$data['widget_header_def'] = 1;
		$data['select_bg'] = 1;
		$data['select_bg_def'] = 1;
		$data['hlc'] = "77caff";
		$data['hlhc'] = "77caff";
		$data['hc'] = "77caff";
		$data['plc'] = "77caff";
		$data['plhc'] = "77caff";
		$data['pb'] = "77caff";
		$data['lfbt'] = "New Title1";
		$data['lfbd1'] = "Desc 1";
		$data['lfbl1'] = "#";
		$data['lfbd2'] = "Desc 2";
		$data['lfbl2'] = "#";
		$data['lfbd3'] = "Desc 3";
		$data['lfbl3'] = "#";
		$data['lfbd4'] = "Desc 4";
		$data['lfbl4'] = "#";
		$data['rfbt'] = "Test title";
		$data['rfbd1'] = "RDesc 1";
		$data['rfbl1'] = "#";
		$data['rfbd2'] = "RDesc 2";
		$data['rfbl2'] = "#";
		$data['rfbd3'] = "RDesc 3";
		$data['rfbl3'] = "#";
		$data['rfbd4'] = "RDesc 4";
		$data['rfbl4'] = "#";
		$data['lfbs1'] = 0;
		$data['lfbs2'] = 0;
		$data['lfbs3'] = 0;
		$data['lfbs4'] = 0;
		$data['rfbs1'] = 0;
		$data['rfbs2'] = 0;
		$data['rfbs3'] = 0;
		$data['rfbs4'] = 0;
		$data['fct'] = "&copy;2012 Rentokil Initial plc. Registered in England 5393279.  Registered office: 2 City Place, Beehive Ring Road, Gatwick, RH6 0HA.  The names Rentokil&reg; and Initial&reg; are registered trade marks.";*/
	
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_EDIT_SKINS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SKINS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		if($data['contract_id'] == '' || $data['sid'] == '' || $data['sn'] == '' || $data['logo'] == '' || $data['smartphone'] == '' || $data['header_div'] == '' || $data['level12_bg'] == '' || $data['level2_bg'] == '' || $data['no_nav'] == '' || $data['widget_header'] == '' || $data['select_bg'] == '' || $data['hlc'] == '' || $data['hlhc'] == '' || $data['hc'] == '' || $data['plc'] == '' || $data['plhc'] == '' || $data['pb'] == '' || $data['lfbs1'] === NULL || $data['lfbs2'] === NULL || $data['lfbs3'] === NULL || $data['lfbs4'] === NULL || $data['rfbs1'] === NULL || $data['rfbs2'] === NULL || $data['rfbs3'] === NULL || $data['rfbs4'] === NULL || $data['fct'] === '')
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$bw_obj = new Business_customeradmin();
		$edit_skin_res = $bw_obj->edit_skin($data);
		echo json_encode($edit_skin_res);		
	}
	
	public function delete_skin()
	{
		/*$data['contract_id'] = 80;
		$data['sid'] = 142;*/
		
		$data = $this->security->xss_clean($_POST);
		
		$data['from'] = FROM_DELETE_SKINS;	
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(SKINS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		if($data['contract_id'] == '' || $data['sid'] == '')
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$bw_obj = new Business_customeradmin();
		$delete_skin_res = $bw_obj->delete_skin($data);
		echo json_encode($delete_skin_res);
	}
	public function get_meal_order_summary()
	{
		//		$data = array('school_id' => "131", 'start_date' => '2013-06-20', 'end_date' => '2013-07-20');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		// 		$data['school_id'] = 327;
		// 		$data['start_date'] ='2013-08-19';
		// 		$data['end_date'] = '2013-08-23';

		$data['from'] = FROM_GET_MEAL_ORDER_SUMMARY;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		if(!validate_contract($data['contract_id']))	//validating the contract id
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

		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		$bw_obj = new Business_common();
		$meal_summary_res = $bw_obj->get_meal_order_summary($data);
		echo json_encode($meal_summary_res);
	}
	
	public function make_card_refund()
	{
		//$data['transaction_id'] = "CA3/WP/00000000105";
		
		$data = $this->security->xss_clean($_POST);		
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;	
		
		$data['from'] = FROM_SAVE_CARD_REUND;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(CARD_REFUNDS_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Checking CURL is installed or not
		if(!function_exists('curl_version'))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = CURL_INSTALLATION_PROBLEM;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$bw_obj = new Business_customeradmin();
	
		// Validation for transaction Id
		if(!$bw_obj->validateTransactionId($data))
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
		
		$available_refund = $bw_obj->get_card_available_refund($data);
		if($available_refund <= 0)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = NO_REFUND;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$refund_res = $bw_obj->get_payment_details($data);	// Get the payment details using transaction id		
		
		$log_msg = $log_msg . ' Pupil ';
		foreach ($refund_res as $key => $value)
		{
			$log_msg = $log_msg . $value->pupil_id . ', ';
		}
		
		$data['trans_fee_status'] = $refund_res[0]->trans_fee_status;
		$data['refund_fee'] = $refund_res[0]->refund_fee;
		$data['pgtr_id'] = $refund_res[0]->pgtr_id;
		$pupil_count = 0;
		
		$card_balance = 0;
		$trans_amount = $available_refund;
		foreach ($refund_res as $refund_data)
		{
			$card_balance += $refund_data->card_balance;
			$pupil_count++;
		}
		
		// Check the overall card balance
		if($card_balance == 0)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = CARD_BALANCE_ERROR;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$payment_id = create_transaction_id($data['contract_id'], CARD, PAYMENT_ITEMS);		// create transaction id
		
		// Check the card balance and overall transaction amount
		if($card_balance < $trans_amount)
		{
			foreach ($refund_res as $refund_data)
			{
				$tmp_data['pupil_id'] =  $refund_data->pupil_id;
				$tmp_data['refund_amount'] =  $refund_data->card_balance;
				$tmp_data['refund_ref_pid'] =  $data['transaction_id'];
				$tmp_data['pgtr_id'] =  $refund_data->pgtr_id;
				$tmp_data['card_cash'] = CARD;
				$tmp_data['pay_refund'] = REFUND;
				$tmp_data['payment_id'] =  $payment_id;
				if($data['trans_fee_status'])
				$tmp_data['transaction_fee'] =  $data['refund_fee'] / $pupil_count;
				else 
				$tmp_data['transaction_fee'] = 0;
				
				$tmp_data['user_id'] = $data['user_id'];
				
				$final_data[] = $tmp_data;
			}
			unset($tmp_data);			
		}
		else 
		{
			$amount_per_pupil = $trans_amount/$pupil_count;
			$temp_total_refund = 0;
			foreach ($refund_res as $refund_data)
			{
				if($refund_data->card_balance >= $amount_per_pupil)
					$tmp_data['refund_amount'] =  $amount_per_pupil;
				else
					$tmp_data['refund_amount'] = $refund_data->card_balance;

				$tmp_data['card_balance'] =  $refund_data->card_balance;
				$tmp_data['pupil_id'] =  $refund_data->pupil_id;
				$tmp_data['refund_ref_pid'] =  $data['transaction_id'];
				$tmp_data['pgtr_id'] =  $refund_data->pgtr_id;
				$tmp_data['card_cash'] = CARD;
				$tmp_data['pay_refund'] = REFUND;
				$tmp_data['payment_id'] =  $payment_id;
				if($data['trans_fee_status'])
				$tmp_data['transaction_fee'] =  $data['refund_fee'] / $pupil_count;
				else
				$tmp_data['transaction_fee'] = 0;
				
				$tmp_data['user_id'] = $data['user_id'];

				$temp_total_refund += $tmp_data['refund_amount'];

				$pre_final_data[] = $tmp_data;
			}
			unset($tmp_data);
						
			if($temp_total_refund == $trans_amount)
			{
				$final_data = $pre_final_data;
			}
			else 
			{
				$missing_amt = $trans_amount - $temp_total_refund;				
				foreach ($pre_final_data as $check_data) 
				{
					if(($check_data['card_balance'] > $amount_per_pupil) && ($missing_amt > 0))
					{
						if(($check_data['card_balance'] - $amount_per_pupil) >= $missing_amt)
						{
							$tmp_data['refund_amount'] =  $amount_per_pupil + $missing_amt;
							$missing_amt = 0;
						}
						else 
						{
							$tmp_data['refund_amount'] = $check_data['card_balance'];
							$missing_amt -= ($check_data['card_balance'] - $amount_per_pupil);
						}
						
						$check_data['refund_amount'] = $tmp_data['refund_amount'];
						$final_data[] = $check_data;
					}
					else 
						$final_data[] = $check_data;
				}
			}
		}
		
		$initiate_res = $bw_obj->initiate_card_refund($final_data);
		
		if(!$initiate_res)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = DATABASE_QUERY_FAILED;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$amount = $trans_amount - $data['refund_fee'];
		$amount = str_replace('.', '',$trans_amount);
		//$amount = '3021';	// Hardcoded amount for testing yes pay environment. It should be removed in real time
		$merchantid = YES_PAY_MERCHANT_ID;
		$pgtr = $data['pgtr_id'];
		$transaction_id = $data['transaction_id'];
			
		$digest = $transaction_id. $pgtr . $amount. YES_PAY_PASSWORD;
		$sha_digest = sha1($digest);

		$ch = curl_init() or die(curl_error());
		$params="merchantID=$merchantid&MTR=$transaction_id&PGTR=$pgtr&refundAmount=$amount&digest=$sha_digest";
		curl_setopt($ch, CURLOPT_POST,1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_URL,YES_PAY_REFUND_URL);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			
		$yes_obj = new stdClass();
		$yes_obj->error = TRUE;
		$yes_obj->yes_msg = CURL_CONNECTION_PROBLEM;
		$yes_obj->session_status = TRUE;
		$err = json_encode($yes_obj);
			
		$res=curl_exec($ch) or die($err);
		curl_close($ch);
			
		$res = explode('&',$res);
		foreach($res as $key => $value)
		{
			$result = explode('=', $value);
			switch($result[0])
			{
				case 'MTR':
					$mtr_id = $result[1];
					break;
				case 'result':
					$result_code = $result[1];
					$data['yp_code'] = $result_code;
					break;
				case 'PGTR':
					$pgtr_id = $result[1];
					break;
				case 'digest':
					$digest = $result[1];
					break;
				case 'transIdData':
					$transIdData = $result[1];
					break;
			}
		}
			
		if(isset($mtr_id) && isset($result_code))
		{
			$ref_data['payment_id'] = $payment_id;
			$ref_data['yp_code'] = $result_code;
			if(isset($pgtr_id))
			{
				$ref_data['pgtr_id'] = $pgtr_id;
			}	
			$save_res = $bw_obj->change_card_refund_status($ref_data);
			
			if($result_code == 0)
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
			}
			else 
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
			}
			
			echo json_encode($save_res);
			exit;
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$error_obj->error = TRUE;
			$error_obj->error_msg = CONNECTION_PROBLEM;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		exit;
	}
	
	/* For resource management setcion...*/
	
	/* Get the zone dashboard */
	public function get_zone_dashboard()
	{
		//$data = array('contract_id'=>'80','page_no'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
	
		$data['from'] = FROM_GET_ZONE_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$zone_res = $bw_obj->get_zone_dashboard($data);	//get zone Dashboard
		
		$zone_obj = new stdClass();
		$zone_obj->zond_dash = $zone_res;
		$zone_obj->error = FALSE;
		$zone_obj->session_status = TRUE;
		echo json_encode($zone_obj);
	}
	
	
	/* Creating zone */
	public function add_edit_zone()
	{
		//$data = array('zone_id'=>1, 'zone_name'=>'test1','contract_id'=>'80','description'=>'descritpion...222..','device_id'=>33, 'serial_no'=>35354,'high_threshold'=>15,'low_threshold'=>5,'network_id'=>15,'network_desc'=>'','timeout'=>30);
		$data = $this->security->xss_clean($_POST);
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$data['from'] = FROM_ADD_ZONE;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		/*validate profile access */
		if(!validate_cadmin_profile_access(ZONE_DASHBOARD_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
	
		/* Validating the input variables */
		if (($data['zone_name']!='')&&($data['description']!='')&&($data['device_id']!=='')&&($data['serial_no']!=='')&&($data['high_threshold']!=='')&&($data['low_threshold']!=='')&&($data['timeout']!==''))
		{			
			if(!validate_contract($data['contract_id']))	//validating the contract id
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
				
			$bw_obj = new Business_customeradmin();
			$add_zone = $bw_obj->add_edit_zone($data);
			
			if ($add_zone->error)
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$add_zone_obj = new stdClass();
				$add_zone_obj->error = TRUE;
				$add_zone_obj->error_msg = $add_zone->error_msg;
				$add_zone_obj->session_status = TRUE;
				echo json_encode($add_zone_obj);
			}
			else
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$zone_obj = new stdClass();
				$zone_obj->zone_details = $add_zone->add_edit_zone;
				$zone_obj->error = FALSE;
				$zone_obj->session_status = TRUE;
				echo json_encode($zone_obj);
			}
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$add_zone_obj = new stdClass();
			$add_zone_obj->error = TRUE;
			$add_zone_obj->error_msg = INPUT_DATA_MISSING;
			$add_zone_obj->session_status = TRUE;
			echo json_encode($add_zone_obj);
		}
	}
	
	/* Get the zone details */
	public function get_zone_details()
	{
		//$data = array('contract_id'=>'80','zone_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
	
		$data['from'] = FROM_GET_ZONE_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		$zone_res = $bw_obj->get_zone_details($data);	//get zone details
	
		$zone_obj = new stdClass();
		$zone_obj->zond_details = $zone_res;
		$zone_obj->error = FALSE;
		$zone_obj->session_status = TRUE;
		echo json_encode($zone_obj);
	}
	
	
	/* deleting zone */
	public function delete_zone()
	{
		//$data = array('contract_id'=>'80','zone_id'=>'1');
		$data = $this->security->xss_clean($_POST);
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
	
		$data['from'] = FROM_ADD_ZONE;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		/*validate profile access */
		if(!validate_cadmin_profile_access(ZONE_DASHBOARD_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
				
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
	
		/* Validating the input variables */
		if (($data['zone_id']!=''))
		{
			if(!validate_contract($data['contract_id']))	//validating the contract id
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
	
			$bw_obj = new Business_customeradmin();
			$delete_zone = $bw_obj->delete_zone($data);

			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$zone_obj = new stdClass();
			$zone_obj->error = FALSE;
			$zone_obj->session_status = TRUE;
			echo json_encode($zone_obj);
			exit;
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$add_zone_obj = new stdClass();
			$add_zone_obj->error = TRUE;
			$add_zone_obj->error_msg = INPUT_DATA_MISSING;
			$add_zone_obj->session_status = TRUE;
			echo json_encode($add_zone_obj);
			exit;
		}
	}
	
	
	/* Get the zone chart details */
	public function get_zone_chart_details()
	{
		//$data = array('contract_id'=>'80','zone_id'=>'1','end_date'=>'2013-10-1');
		$data = $this->security->xss_clean($_POST);
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
	
		$data['from'] = FROM_GET_ZONE_DASHBOARD;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_contract($data['contract_id']))	//validating the contract id
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
		/* Validating the input variables */
		if (($data['zone_id']=='')|| ($data['end_date']==''))
		{
		
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
			
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
		//$data = array('school_id' => "281", 'close_till' => '2013-10-20', 'reason' => 'test reason');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;

		$data['from'] = FROM_SCHOOL_CLOSE;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
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
	
	public function add_edit_asset()
	{
		//$data = array('tag_no'=>'0003', 'description'=>'testing 2','asset_type_id'=>'27','oem_code'=>'22','asset_other_desc'=>'other test asset','timeout'=>30, 'asset_id'=>2);
		$data = $this->security->xss_clean($_POST);
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = FROM_ADD_ASSET;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		/*validate profile access */
		if(!validate_cadmin_profile_access(ASSET_DASHBOARD_ACCESS))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		/* Validating the input variables */
		if (($data['tag_no']!='')&&($data['description']!='')&&($data['asset_type_id']!=='')&&($data['oem_code']!=='')&&($data['timeout']!==''))
		{			
			$bw_obj = new Business_customeradmin();
			$add_asset = $bw_obj->add_edit_asset($data);
			
			if ($add_asset->error)
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$add_asset_obj = new stdClass();
				$add_asset_obj->error = TRUE;
				$add_asset_obj->error_msg = $add_asset->error_msg;
				$add_asset_obj->session_status = TRUE;
				echo json_encode($add_asset_obj);
			}
			else
			{
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$asset_obj = new stdClass();
				$asset_obj->asset_details = $add_asset;
				$asset_obj->error = FALSE;
				$asset_obj->session_status = TRUE;
				echo json_encode($asset_obj);
			}
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$add_asset_obj = new stdClass();
			$add_asset_obj->error = TRUE;
			$add_asset_obj->error_msg = INPUT_DATA_MISSING;
			$add_asset_obj->session_status = TRUE;
			echo json_encode($add_asset_obj);
		}
	}
	
	public function get_asset_details()
	{
		//$data = array('asset_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_ADD_ASSET;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_asset($data))	//Validating asset
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
		
		$bw_obj = new Business_customeradmin();
		$asset_res = $bw_obj->get_asset_details($data);	//get asset details
		$asset_read_res = $bw_obj->get_asset_read_details($data);	//get asset last and previous reading details
		$asset_obj = new stdClass();
		$asset_obj->asset_details = $asset_res;
		$asset_obj->asset_read_res = $asset_read_res;
		$asset_obj->error = FALSE;
		$asset_obj->session_status = TRUE;
		echo json_encode($asset_obj);
	}
	
	// Delete Asset
	public function delete_asset()
	{
		//$data = array('asset_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_ADD_ASSET;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_asset($data))	//Validating asset
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
		
		$bw_obj = new Business_customeradmin();
		$asset_res = $bw_obj->delete_asset($data);
		$asset_obj = new stdClass();
		$asset_obj->error = FALSE;
		$asset_obj->session_status = TRUE;
		echo json_encode($asset_obj);
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
        
	// Digital App - Get default values navigation location & form type

	function get_navigation_form_details()
	{
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_customeradmin();
		$addapp_data = $bw_obj->get_navigation_form_details();	//get asset details
		$asset_obj = new stdClass();
		$asset_obj->add_app = $addapp_data;
		$asset_obj->error = FALSE;
		$asset_obj->session_status = TRUE;
		echo json_encode($asset_obj);
	}

	function get_digital_app_details()
	{
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_customeradmin();
		$loadapp_data = $bw_obj->get_digital_app_details($data);	//get asset details
		$asset_obj = new stdClass();
		$asset_obj->app_data = $loadapp_data;
		$asset_obj->error = FALSE;
		$asset_obj->session_status = TRUE;
		echo json_encode($asset_obj);

	}

	function add_edit_digital_apps()
	{
		//$data = array('app_id' => '26', 'app_name' => 'test', 'app_label' => 'testing', 'timeout' => '10', 'description' => 'testing changes', 'nav_loc' => '5', 'frm_type' => '4', 'upld_tplate' => '0', 'template_name' => '', 'disallow_forms' => '1', 'start_hour' => '11', 'start_min' => '30', 'end_hour' => '12', 'end_min' => '15');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
                
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		//validating the input values
		if (($data['app_name'] != '') && ($data['app_label'] != '') && ($data['timeout'] != '') && ($data['description'] != '') && ($data['nav_loc'] != '') && ($data['frm_type'] != '') && ($data['upld_tplate'] != '') && ($data['disallow_forms'] != '') && ($data['start_hour'] != '') && ($data['start_min'] != '') && ($data['end_hour'] != '') && ($data['end_min'] != ''))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends

			$bw_obj = new Business_customeradmin();
			$add_App = $bw_obj->add_edit_digital_apps($data);

			echo json_encode($add_App);
			exit;
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$add_App_obj = new stdClass();
			$add_App_obj->error = TRUE;
			$add_App_obj->error_msg = INPUT_DATA_MISSING;
			$add_App_obj->session_status = TRUE;
			echo json_encode($add_App_obj);
		}

	}
	public function delete_digital_app_details()
	{
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
			
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends

		$bw_obj = new Business_customeradmin();
		$app_res = $bw_obj->delete_digital_app_details($data);
		echo json_encode($app_res);
	}

	function get_digital_app_info()
	{
		$data = $this->security->xss_clean($_POST);
		
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		// Save session log
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		// Save session log - ends
		
		$bw_obj = new Business_customeradmin();
		$appinfo_data = $bw_obj->get_App_info($data);	//get asset details
		$asset_obj = new stdClass();
		$asset_obj->add_app = $appinfo_data;
		$asset_obj->error = FALSE;
		$asset_obj->session_status = TRUE;
		echo json_encode($asset_obj);

	}
	
	// To upload zip file
	function upload_zip_file() 
	{
		$data = $this->security->xss_clean($_POST);
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(DIGITAL_PENS_ACCESS))
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		if($this->upload_digital_forms($data,$_FILES))
		{	
			$form_obj = new stdClass();
			$form_obj->error = FALSE;
			$form_obj->session_status = TRUE;
			echo json_encode($form_obj);
			exit;
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = FILE_UPLOAD_ERROR;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
	}
	
	public function delete_digital_zip_file()
	{
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$zip_path = ZIP_UPLOAD_PATH . $data['contract_id'] . '/' . $data['app_id'];
		
		if(is_dir($zip_path))
		{
			delete_directory_recursion($zip_path);
		}
	
		if(!file_exists($zip_path) && !is_dir($zip_path))
		{
			$bw_obj = new Business_customeradmin();
			$appinfo_data = $bw_obj->delete_digital_zip_file($data);
		
			$form_obj = new stdClass();
			$form_obj->error = FALSE;
			$form_obj->session_status = TRUE;
			echo json_encode($form_obj);
			exit;
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = ZIP_DELETE_ERROR;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
	}
	
	public function upload_digital_forms($data, $file_data)
	{
		$digital_form_id = $data['digital_form_id'];		
		$session_id = $this->session->userdata('user_session');

		if($file_data["files"]["tmp_name"][0])
		{
			$template_name = $file_data["files"]["name"];
			/* Check whether the temp directory is exists or not. If not then create new directory */
			if(!is_dir(TEMP_ZIP_UPLOAD_PATH))
			{
				mkdir(TEMP_ZIP_UPLOAD_PATH, 0755);	//Directory creation with full permission
			};
			
			if(file_exists(TEMP_ZIP_UPLOAD_PATH . $session_id) && is_dir(TEMP_ZIP_UPLOAD_PATH . $session_id))
			{
				delete_directory_recursion(TEMP_ZIP_UPLOAD_PATH . $session_id);
			}
				
			if(!is_dir(TEMP_ZIP_UPLOAD_PATH . $session_id))
			{
				mkdir(TEMP_ZIP_UPLOAD_PATH . $session_id, 0755);	//Directory creation with full permission
			};
			if(move_uploaded_file($file_data["files"]["tmp_name"][0],TEMP_ZIP_UPLOAD_PATH.$session_id.'/'.$template_name[0]))
			{
				$this->load->library('unzip');
				$this->unzip->allow(array('css', 'png', 'jpeg', 'jpg', 'html'));
				if($this->unzip->extract(TEMP_ZIP_UPLOAD_PATH.'/'.$session_id.'/'.$template_name[0], TEMP_ZIP_UPLOAD_PATH.$session_id))
				{
					$zip_path = TEMP_ZIP_UPLOAD_PATH.$session_id .'/';

					$page_url = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
					$page_url= $page_url.$_SERVER["SERVER_NAME"];

					if($this->check_html_file($zip_path)) // Check if html file is there or not
					{
						$htmlFileName = $this->get_html_file_name($zip_path);
							
						// Rename the HTML file with corresponding id
						$oldName = $zip_path . $htmlFileName;
						$filetype = $this->get_file_extenstion($oldName);
						$newFileName = 'temp.' . $filetype;
						$newName = $zip_path . $newFileName;
							
						if(rename($oldName, $newName))		// Rename the file
						{
							$file_link = $page_url . '/' . ZIP_FOLDER_NAME_UPLOAD . ZIP_TEMP_FOLDER_NAME_UPLOAD . $session_id.'/' . $newFileName;

							$form_obj = new stdClass();
							$form_obj->error = FALSE;
							$form_obj->link = $file_link;
							$form_obj->upload_file_name = $template_name[0];
							$form_obj->session_status = TRUE;
							echo json_encode($form_obj);
							exit;
						}
						else
						{
							$error_obj = new stdClass();
							$error_obj->error = TRUE;
							$error_obj->error_msg = FILE_UPLOAD_ERROR;
							$error_obj->session_status = TRUE;
							echo json_encode($error_obj);
							exit;
						}
					}
					else
					{
						$folder_count = $this->folder_validation($zip_path);
						if($folder_count == 0)
						{
							$error_obj = new stdClass();
							$error_obj->error = TRUE;
							$error_obj->error_msg = ZIP_UPLOAD_HTML_ERROR;
							$error_obj->session_status = TRUE;
							echo json_encode($error_obj);
							exit;
						}
						else if($folder_count > 0)
						{
							$folder_array = array();
							$folder_array = $this->get_folder_array($zip_path);
		
							if(count($folder_array) > 1)
							{
								$error_obj = new stdClass();
								$error_obj->error = TRUE;
								$error_obj->error_msg = ZIP_UPLOAD_FOLDER_ERROR;
								$error_obj->session_status = TRUE;
								echo json_encode($error_obj);
								exit;
							}

							$folder_name = $folder_array[0];	// folder name
								
							$folder_path = $zip_path . $folder_name;	// Folder path
								
							$file_array = $this->get_file_list($folder_path);	// Get the file details from the folder
								
							if($this->check_html_file($folder_path))	// Check if file contains HTML or not
							{
								$htmlFileName = $this->get_html_file_name($folder_path);

								$oldName = $folder_path . '/' . $htmlFileName;
								$filetype = $this->get_file_extenstion($oldName);
								$newFileName = 'temp.' . $filetype;
								$newName = $folder_path . '/' . $newFileName;

								if(rename($oldName, $newName))		// Rename the file
								{
									$file_list_array = $this->get_file_list($folder_path);
									
									foreach($file_list_array as $key => $value)
									{
										if($value != '' && $value != '..' && $value != '.')
										{
											$src = $folder_path . '/' . $value;
											$dest = $zip_path. $value;
											if(rename ($src, $dest))
											{
												if(file_exists($src))
													unlink($src);
											}
											else
											{
												$error_obj = new stdClass();
												$error_obj->error = TRUE;
												$error_obj->error_msg = ZIP_UPLOAD_MOVE_ERROR;
												$error_obj->session_status = TRUE;
												echo json_encode($error_obj);
												exit;
											}
										}
									}
									if(is_dir($folder_path))
										delete_directory_recursion($folder_path);
										
									$file_link = $page_url . '/' . ZIP_FOLDER_NAME_UPLOAD . ZIP_TEMP_FOLDER_NAME_UPLOAD . $session_id.'/' . $newFileName;
									
									$form_obj = new stdClass();
									$form_obj->error = FALSE;
									$form_obj->link = $file_link;
									$form_obj->upload_file_name = $template_name;
									$form_obj->session_status = TRUE;
									echo json_encode($form_obj);
									exit;
								}
								else
								{
									$error_obj = new stdClass();
									$error_obj->error = TRUE;
									$error_obj->error_msg = FILE_UPLOAD_ERROR;
									$error_obj->session_status = TRUE;
									echo json_encode($error_obj);
									exit;
								}
							}
							else
							{
								$error_obj = new stdClass();
								$error_obj->error = TRUE;
								$error_obj->error_msg = ZIP_UPLOAD_HTML_ERROR;
								$error_obj->session_status = TRUE;
								echo json_encode($error_obj);
								exit;
							}
						}
						else 
						{
							$error_obj = new stdClass();
							$error_obj->error = TRUE;
							$error_obj->error_msg = FILE_UPLOAD_ERROR;
							$error_obj->session_status = TRUE;
							echo json_encode($error_obj);
							exit;
						}
					}

					$form_obj = new stdClass();
					$form_obj->error = FALSE;
					$form_obj->session_status = TRUE;
					echo json_encode($form_obj);
					exit;
				}
				else
				{
					$zip_err = $this->unzip->error_string();		// Error in zip folders
					
					if(count($zip_err) > 0)
						$err = $zip_err[0];
					else
						$err = FILE_UPLOAD_ERROR;
						
					$error_obj = new stdClass();
					$error_obj->error = TRUE;
					$error_obj->error_msg = $err;
					$error_obj->session_status = TRUE;
					echo json_encode($error_obj);
					exit;
				}
			}
			else
			{
				$error_obj = new stdClass();
				$error_obj->error = TRUE;
				$error_obj->error_msg = FILE_UPLOAD_ERROR;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
	}
	
	/* Get the html file from the extracted zip folder */
	private function get_html_file_name($path)
	{
		$count = 0;
		if (is_dir($path)) {
			if ($dir_handler = opendir($path)) {
				while (($file = readdir($dir_handler)) !== false) {
					$path_info = pathinfo($file);
					$filetype =  $path_info['extension'];
					if($filetype == 'htm' || $filetype == 'html')
					{
						return $file;
					}
				}
				closedir($dir_handler);
			}
		}
		else
		return FALSE;
	}
	
	/* Get the file extenstion for given file */
	private function get_file_extenstion($file)
	{
		$path_info = pathinfo($file);
		$filetype =  $path_info['extension'];
		return $filetype;
	}
	
	// To check given zip file is more than one directory or not
	private function folder_validation($path)
	{
		$count = 0;
		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$count++;
			}
		}
		return $count;
	}
	
	/* Get all folders from the directory */
	private function get_folder_array($path)
	{
		$folder_array = array();
		$dir = new DirectoryIterator($path);
		foreach ($dir as $fileinfo) {
			if ($fileinfo->isDir() && !$fileinfo->isDot()) {
				$folder_array[] = $fileinfo->getFilename();
			}
		}
		return $folder_array;
	}
	
	/* Check given file is html or not */
	public function check_html_file($path)
	{
		$count = 0;
		if (is_dir($path)) {
			if ($dir_handler = opendir($path)) {
				while (($file = readdir($dir_handler)) !== false) {
					$path_info = pathinfo($file);
					if(isset($path_info['extension']))
					{
						$filetype =  $path_info['extension'];
						if($filetype == 'htm' || $filetype == 'html')
						{
							$count++;
						}
					}
				}
				closedir($dir_handler);
				if($count == 0 || $count > 1)
				return FALSE;
				else
				return TRUE;
			}
		}
		else
		return FALSE;
	}
	
	/* Get the file list from the directory */
	private function get_file_list($path)
	{
		$file_array = array();
		if (is_dir($path)) {
			if ($dir_handler = opendir($path)) {
				while (($file = readdir($dir_handler)) !== false) {
					if(!is_dir($path.$file))
					{
						$file_array[] = $file;
					}
				}
				closedir($dir_handler);
			}
		}
		return $file_array;
	}
	
	function add_edit_digital_pen()
	{
		//$data = array('dp_id' => 4, 'pen_id' => 'adfadse11', 'label' => 'test', 'timeout' => '30', 'description' => 'testing changes');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
                
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		//validating the input values
		if (($data['pen_id'] != '') && ($data['label'] != '') && ($data['timeout'] != '') && ($data['description'] != ''))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends

			$bw_obj = new Business_customeradmin();
			$pen_res = $bw_obj->add_edit_digital_pen($data);

			echo json_encode($pen_res);
			exit;
		}
		else
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			$add_App_obj = new stdClass();
			$add_App_obj->error = TRUE;
			$add_App_obj->error_msg = INPUT_DATA_MISSING;
			$add_App_obj->session_status = TRUE;
			echo json_encode($add_App_obj);
		}

	}
	
	public function get_digital_pen_details()
	{
		//$data = array('dp_id'=>'5');
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_digital_pen($data))	//Validating digital_pen
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
		
		$bw_obj = new Business_customeradmin();
		$digital_pen_res = $bw_obj->get_digital_pen_details($data);	//get digital_pen details
		$digital_pen_obj = new stdClass();
		$digital_pen_obj->digital_pen_details = $digital_pen_res;
		$digital_pen_obj->error = FALSE;
		$digital_pen_obj->session_status = TRUE;
		echo json_encode($digital_pen_obj);
	}
	
	/* Get digital pens list */
	public function get_digital_pens()
	{
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$digital_pens_res = $bw_obj->get_digital_pens($data);
		$user_obj = new stdClass();
		$user_obj->digital_pens_res = $digital_pens_res;
		$user_obj->error = FALSE;
		$user_obj->session_status = TRUE;
		echo json_encode($user_obj);
	}
	
	// To delete digital pen
	public function delete_digital_pen()
	{
		//$data = array('dp_id'=>5);
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
	
		$data['from'] = FROM_GET_DIGITAL_PEN_APP;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
	
		if(!validate_digital_pen($data))	//Validating digital_pen
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
		
		$bw_obj = new Business_customeradmin();
		$digital_pen_res = $bw_obj->delete_digital_pen($data);
		$digital_pen_obj = new stdClass();
		$digital_pen_obj->error = FALSE;
		$digital_pen_obj->session_status = TRUE;
		echo json_encode($digital_pen_obj);
	}
	
	/* Get quality audit accounts list */
	public function get_qa_accounts()
	{
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
			
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_accounts_res = $bw_obj->get_qa_accounts($data);
		$qa_accounts_obj = new stdClass();
		$qa_accounts_obj->qa_accounts_res = $qa_accounts_res;
		$qa_accounts_obj->error = FALSE;
		$qa_accounts_obj->session_status = TRUE;
		echo json_encode($qa_accounts_obj);
	}
	
	/* Get QA account details */
	public function get_qa_account_details()
	{
		//$data = array('account_id'=>1);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
			
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
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
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_accounts_res = $bw_obj->get_qa_account_details($data);
		echo json_encode($qa_accounts_res);
	}
	
	/* Get QA User access level list */
	public function get_qa_user_access_details()
	{
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
			
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_accounts_res = $bw_obj->get_qa_user_access_details($data);
		echo json_encode($qa_accounts_res);
	}
	
	/* Add or Edit QA Account details */
	public function add_edit_qa_account()
	{
		/*$data = array(
				'account_name' => 'test_account',
				'code' => '212',
				'desc' => 'Test',
				'sync_status' => '1',
				'start_date' => '2013-12-18',
				'date_status' => '1',
				'user_data' => array(
									array(
										'user_id' => 2,
										'cr_sla' => 1,
										'ad_hoc' => 1,
										'v_sla' => 1
										),
									array(
										'user_id' => 125,
										'cr_sla' => 0,
										'ad_hoc' => 1,
										'v_sla' => 0
										)
									)
			);*/
		/*$data = array(
				'account_id' => 5,
				'account_name' => 'test_account',
				'code' => '222',
				'desc' => 'Test',
				'sync_status' => '1',
				'start_date' => '2013-12-18',
				'user_data' => array(
									array(
										'user_id' => 125,
										'cr_sla' => 0,
										'ad_hoc' => 1,
										'v_sla' => 0
										)
									)
			);*/
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(QUALITY_AUDIT_ACCESS))
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		if($data['account_id'] != '')
		{
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
		}
		if(($data['sync_status'] !='' )&&($data['start_date'] !='' ))
		{
			$bw_obj = new Business_common();
			$date_res = $bw_obj->get_current_date_time($data);	//get school details

			$data['current_date'] = $date_res->syear .'-'. $date_res->smonth .'-'. $date_res->sday .' '. $date_res->shour .':'. $date_res->smin .':'. $date_res->ssec;
		}
		
		if($data['account_name'] == '' || $data['code'] == '' || $data['sync_status'] > 1)
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
		
		/* validate contract_id & user_id */
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				$valid_data = array('user_id' => $value['user_id'], 'contract_id' => $data['contract_id'], 'customer_id' => $this->session->userdata('user_info')->customer_id);
				if(!validate_user_customer_contract($valid_data))
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
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$add_edit_res = $bw_obj->add_edit_qa_account($data);
		echo json_encode($add_edit_res);
	}
	
	/* Delete QA Account */
	public function delete_qa_account()
	{
		//$data = array('account_id' => 4);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(QUALITY_AUDIT_ACCESS))
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
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
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$delete_res = $bw_obj->delete_qa_account($data);
		echo json_encode($delete_res);
	}
	
	/* Get quality audit groups list */
	public function get_qa_groups()
	{
		//$data = array('account_id'=>1);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
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
		
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_groups_res = $bw_obj->get_qa_groups($data);
		$qa_groups_obj = new stdClass();
		$qa_groups_obj->qa_groups_res = $qa_groups_res;
		$qa_groups_obj->error = FALSE;
		$qa_groups_obj->session_status = TRUE;
		echo json_encode($qa_groups_obj);
	}
	
	public function get_qa_group_indicators()
	{
		//$data = array('account_id'=>1, 'group_id' => 8);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
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
		
		if($data['group_id'] != 0)
		{
			// Validate account & group
			if(!validate_qa_account_group($data))
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
		
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_groups_res = $bw_obj->get_qa_group_indicators($data);
		$qa_groups_obj = new stdClass();
		$qa_groups_obj->qa_groups_res = $qa_groups_res;
		$qa_groups_obj->error = FALSE;
		$qa_groups_obj->session_status = TRUE;
		echo json_encode($qa_groups_obj);
	}
	
	/* Get quality audit groups list */
	public function get_qa_group_details()
	{
		//$data = array('account_id'=>1, 'group_id' => 4);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
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
		
		// Validate account & group
		if(!validate_qa_account_group($data))
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
		
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_READ;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_groups_res = $bw_obj->get_qa_group_details($data);
		$qa_groups_obj = new stdClass();
		$qa_groups_obj->qa_groups_res = $qa_groups_res;
		$qa_groups_obj->error = FALSE;
		$qa_groups_obj->session_status = TRUE;
		echo json_encode($qa_groups_obj);
	}
	
	public function add_edit_qa_groups()
	{
		/*$data = array
			(
				'group_id' => 8,
				'account_id' => 1,
				'group_name' => 'test_group_8',
				'select_point_ind' => array(
									array(
										'ind_id' => 6,
										'seq_no' => 1
										)
								),
				'remove_point_ind' => array(5),
				'red_from' => '0',
				'red_to' => '70',
				'amber_from' => '80',
				'amber_to' => '90',
				'green_from' => '90',
				'green_to' => '100',
				'purple_from' => '',
				'purple_to' => '',
				'blue_from' => '',
				'blue_to' => ''
			);*/
	
		/*$data = array
			(
				'account_id' => 1,
				'group_name' => 'test_group8',
				'select_point_ind' => array(
									array(
										'ind_id' => 5,
										'seq_no' => 1
										),
									array(
										'ind_id' => 6,
										'seq_no' => 2
										)
								),
				'red_from' => '0',
				'red_to' => '10',
				'amber_from' => '11',
				'amber_to' => '30',
				'green_from' => '81',
				'green_to' => '100',
				'purple_from' => '31',
				'purple_to' => '50',
				'blue_from' => '51',
				'blue_to' => '80'
			);*/
		$data = $this->security->xss_clean($_POST);
	
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(QUALITY_AUDIT_ACCESS))
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		$data['from'] = FROM_QUALITY_AUDIT;
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);
		$log_data['contract_id'] = $data['contract_id'];
		
		/* Validating the input variables */
		if (($data['account_id']=='')||($data['group_name']==''))
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$add_group_obj = new stdClass();
			$add_group_obj->error = TRUE;
			$add_group_obj->error_msg = INPUT_DATA_MISSING;
			$add_group_obj->session_status = TRUE;
			echo json_encode($add_group_obj);
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
		
		/*$red_diff = $data['red_to'] - $data['red_from'];
		$amber_diff = $data['amber_to'] - $data['amber_from'];
		$green_diff = $data['green_to'] - $data['green_from'];
		$purple_diff = $data['purple_to'] - $data['purple_from'];
		$blue_diff = $data['blue_to'] - $data['blue_from'];
		
		$rag_status = $red_diff + $amber_diff + $green_diff + $purple_diff + $blue_diff;
		if($rag_status != 100)
		{
			// Save session log
			$log_data['message'] = $log_msg.LOG_NOT_AUTHORISED;
			session_log_message_helper($log_data);
			// Save session log - ends
			
			$add_group_obj = new stdClass();
			$add_group_obj->error = TRUE;
			$add_group_obj->error_msg = QA_GROUP_RAG_STATUS_WRONG;
			$add_group_obj->session_status = TRUE;
			echo json_encode($add_group_obj);
			exit;
		}*/
		if($data['red_from'] == '')
                    $data['red_from'] = NULL;
                if($data['red_to'] == '')
                    $data['red_to'] = NULL;
                if($data['amber_from'] == '')
                    $data['amber_from'] = NULL;
                if($data['amber_to'] == '')
                    $data['amber_to'] = NULL;
                if($data['green_from'] == '')
                    $data['green_from'] = NULL;
                if($data['green_to'] == '')
                    $data['green_to'] = NULL;                
                if($data['purple_from'] == '')
                    $data['purple_from'] = NULL;
                if($data['purple_to'] == '')
                    $data['purple_to'] = NULL;
                if($data['blue_from'] == '')
                    $data['blue_from'] = NULL;
                if($data['blue_to'] == '')
                    $data['blue_to'] = NULL;                

		$bw_obj = new Business_customeradmin();
		$add_group = $bw_obj->add_edit_qa_groups($data);
		
		echo json_encode($add_group);
	}
	
	/* Delete quality audit group */
	public function delete_qa_group()
	{
		//$data = array('account_id'=>1, 'group_id' => 7);
		$data = $this->security->xss_clean($_POST);
		$data['contract_id'] = $this->session->userdata('user_info')->contract_id;
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		/*validate profile access */
		if(!validate_cadmin_profile_access(QUALITY_AUDIT_ACCESS))
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = PROFILE_NOT_EXIST;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		// Validate account & group
		if(!validate_qa_account_group($data))
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
		if(!validate_qa_account_group($data))
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
		
		$data['from'] = FROM_QUALITY_AUDIT;		
		$data['msg'] = LOG_WRITE;
		$log_msg = get_session_log_message($data);		
		$log_data['contract_id'] = $data['contract_id'];
		
		$log_data['message'] = $log_msg.LOG_AUTHORISED;
		session_log_message_helper($log_data);
		
		$bw_obj = new Business_customeradmin();
		$qa_groups_res = $bw_obj->delete_qa_group($data);
		$qa_groups_obj = new stdClass();
		$qa_groups_obj->delete_res = $qa_groups_res;
		$qa_groups_obj->error = FALSE;
		$qa_groups_obj->session_status = TRUE;
		echo json_encode($qa_groups_obj);
	}
}
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_customeradmin {

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('customeradmin_model');
	}
	
	public function save_contract_session($data)
	{
		$con_res = $this->CI->customeradmin_model->save_contract_session($data);
		
		if($con_res)
		{
			$user_info = $this->CI->session->userdata('user_info');
			
			$user_info->session_log = $con_res->session_log;
			$user_info->contract_id = $con_res->contract_id;
			$user_info->contract_name = $con_res->contract_name;
			$user_info->contract_key = $con_res->contract_key;
			
			$this->CI->session->unset_userdata('user_info');
			$this->CI->session->set_userdata('user_info', $user_info);
			
			$con_obj = new stdClass();
			$con_obj->con_res = TRUE;
			$con_obj->error = FALSE;
			$con_obj->session_status = TRUE;
			return $con_obj;
		}		
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}

	public function get_users($data)
	{
		$users_res = $this->CI->customeradmin_model->get_users($data);		
		return $users_res;
	}

	public function update_user_status($data)
	{
		$update_user_status = $this->CI->customeradmin_model->update_user_status($data);		
		return $update_user_status;
	}

	public function create_user($data)
	{
		$check_user_duplicate = $this->CI->customeradmin_model->check_user_duplicate($data);
		
		if($check_user_duplicate->user_dup_count>0)
		{
			$create_user_err->error_msg = CADMIN_USER_DUPLICATE;
			$create_user_err->error = TRUE;
			return $create_user_err;
		}
		else
		{
			$data['username'] = $data['user_email'];
			
			$exp_arr = explode('+', $data['username']);
			$arr_count = count($exp_arr);

			$user_email = '';
			if($arr_count > 1)
			{
				$domain_addr = explode('@', $exp_arr[$arr_count-1]);
					
				$str = '';
				for($i=0; $i<($arr_count-1); $i++)
				{
					if($i == 0)
					$str = $exp_arr[$i];
					else
					$str = $str . '+' . $exp_arr[$i];
				}
				$user_email = $str . '@' . $domain_addr[1];
			}
			else
			{
				$user_email = $data['username'];
			}
			
			$data['user_email']= $user_email;
			//$password = mt_rand();
			$password = generate_password();
			/*if(strlen($password) < RANDOM_PASSWORD_LENGTH)
			{
				$password = RANDOM_PASS_FS;
			}*/
			$data['password'] = md5($password);
			$data['role_id'] = USER;
			$data['status'] = DEFAULT_STATUS;
			$data['chg_pwd'] = ACTIVE;
			
			$user_id = $this->CI->customeradmin_model->create_user($data);
			if($user_id)
			{
				$data['password_email'] = $password;
				$data['user_id'] = $user_id;
				$new_user_email_res = new_user_email($data);	// Send mail to user admin
				if (!$new_user_email_res)
				{
					$create_user->error_msg = CADMIN_USER_EMAIL_FAILED;
					$create_user->error = TRUE;
					return $create_user;
				}
				else
				{
					$create_user->error = FALSE;
					return $create_user;
				}
			}
			else
			{
				$create_user->error_msg = DATABASE_QUERY_FAILED;
				$create_user->error = TRUE;
			}
		}
	}

	/* Creating the contract Id */
	public function create_contract($data)
	{
		/* Check whether contract id is already exists or not */
		$check_contract_duplicate = $this->CI->customeradmin_model->check_contract_duplicate($data);
		if($check_contract_duplicate->contract_dup_count>0)
		{
			$create_contract_err->error_msg = CADMIN_CONTRACT_DUPLICATE;
			$create_contract_err->error = TRUE;
			$create_contract_err->session_status = TRUE;
			return json_encode($create_contract_err);
		}
		else
		{
			$data['contract_key'] = $this->create_contract_key($data['customer_id']);	// Generating the contract key
			if($data['contract_key'] != NULL && $data['contract_key'] != '')
			{
				$data['status'] = DEFAULT_STATUS;
				$data['cuser_id'] = $this->CI->session->userdata('user_info')->user_id;
					
				$contract_id = $this->CI->customeradmin_model->create_contract($data);

				if($contract_id != null && $contract_id != "")
				{
					/* Create the skins */
					$skin_data['skin_name'] = DEFAULT_SKIN_NAME;
					$skin_data['contract_id'] = $contract_id;
					$skin_data['user_id'] = $data['cuser_id'];
					$skin_data['default'] = DEFAULT_STATUS;
					$this->create_skin($skin_data);

					/* Store contract details in session */
					$data['contract_id'] = $contract_id;
					$con_res = $this->CI->customeradmin_model->save_contract_session($data);
					if($con_res)
					{
						$user_info = $this->CI->session->userdata('user_info');

						$user_info->session_log = $con_res->session_log;
						$user_info->contract_id = $con_res->contract_id;
						$user_info->contract_name = $con_res->contract_name;
						$user_info->contract_key = $con_res->contract_key;

						$this->CI->session->unset_userdata('user_info');
						$this->CI->session->set_userdata('user_info', $user_info);
					}

					$create_contract->contract_id = $contract_id;
					$create_contract->contract_key = $data['contract_key'];
					$create_contract->error = FALSE;
					$create_contract->session_status = TRUE;
					return json_encode($create_contract);
						
				}
				else
				{
					$create_contract->error_msg = DATABASE_QUERY_FAILED;
					$create_contract->error = TRUE;
					$create_contract->session_status = TRUE;
					return json_encode($create_contract);
				}
			}
			else 
			{
				$create_contract->error_msg = CADMIN_CONTRACT_CREATION;
				$create_contract->error = TRUE;
				$create_contract->session_status = TRUE;
				return json_encode($create_contract);
			}
		}
	}

	/*public function create_menus_exist_contracts()
	{
		if (!$this->CI->customeradmin_model->create_menus_exist_contracts())
		{
			$create_menus_obj->error = TRUE;
			$create_menus_obj->error_msg = DATABASE_QUERY_FAILED;
			$create_menus_obj->session_status = TRUE;
			return json_encode($create_menus_obj);
		}
		else
		{
			$create_menus_obj->error = FALSE;
			$create_menus_obj->session_status = TRUE;
			return json_encode($create_menus_obj);
		}
	}*/

	public function create_school($data)
	{
		$check_school_duplicate = $this->CI->customeradmin_model->check_school_duplicate($data);
		
		if($check_school_duplicate->school_dup_count>0)
		{
			$create_school_err->error_msg = CADMIN_SCHOOL_DUPLICATE;
			$create_school_err->error = TRUE;
			return $create_school_err;
		}
		else
		{
			$data['status'] = DEFAULT_STATUS;
			$data['cuser_id'] = $data['user_id'];

			if($this->CI->customeradmin_model->create_school($data))
			{
				$create_school->error = FALSE;
				return $create_school;
			}
		}
	}

	public function edit_profile($data)
	{
		if (!$this->CI->customeradmin_model->edit_profile($data))
		{
			$edit_profile_obj->error = TRUE;
			$edit_profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$edit_profile_obj->session_status = TRUE;
			return json_encode($edit_profile_obj);
		}
		else
		{
			$edit_profile_obj->error = FALSE;
			$edit_profile_obj->session_status = TRUE;
			return json_encode($edit_profile_obj);
		}
	}

	public function get_contracts($data)
	{
		$contracts_res = $this->CI->customeradmin_model->get_contracts($data);
		return $contracts_res;
	}

	public function edit_contract($data)
	{		
		/* Check whether contract id is already exists or not */
		$check_contract_duplicate = $this->CI->customeradmin_model->check_contract_duplicate_edit($data);
		
		if($check_contract_duplicate->contract_dup_count>0)
		{
			$edit_contract_obj = new stdClass();
			$edit_contract_obj->error_msg = CADMIN_CONTRACT_DUPLICATE;
			$edit_contract_obj->error = TRUE;
			$edit_contract_obj->session_status = TRUE;
			return $edit_contract_obj;
		}
		else
		{
			$edit_contract_res = $this->CI->customeradmin_model->edit_contract($data);
				
			if($edit_contract_res > 0)
			{
				$edit_contract_obj = new stdClass();
				$edit_contract_obj->contract_res = $edit_contract_res;
				$edit_contract_obj->error = FALSE;
				$edit_contract_obj->session_status = TRUE;
				return $edit_contract_obj;
					
			}
			else
			{
				$edit_contract_obj = new stdClass();
				$edit_contract_obj->error = TRUE;
				$edit_contract_obj->error_msg = DATABASE_QUERY_FAILED;
				$edit_contract_obj->session_status = TRUE;
				return $edit_contract_obj;
			}
		}
	}

	public function edit_schools($data)
	{
		foreach ($data['schools_edit'] as $schools_data)
		{
			// Added customer check
			$customer_check = $this->CI->customeradmin_model->school_check_customer($data['customer_id'],$schools_data['contract_id'],$schools_data['school_id']);
			if ($customer_check->chk_cus_count>0)
			{
				$check_school_duplicate = $this->CI->customeradmin_model->check_school_duplicate($schools_data);
				if($check_school_duplicate->school_dup_count>0)
				{
					$edit_school_err->error_msg = DUPLICATE_ENTRY;
					$edit_school_err->error = TRUE;
					return $edit_school_err;
				}
				else
				{
					$schools_data['muser_id'] = $data['muser_id'];
					if(!$this->CI->customeradmin_model->edit_schools($schools_data))
					{
						$edit_school_err->error = TRUE;
						$edit_school_err->error_msg = DATABASE_QUERY_FAILED;
						$edit_school_err->session_status = TRUE;
						return $edit_school_err;
					}
				}
			}
			else
			{
				$edit_school_err->error = TRUE;
				$edit_school_err->error_msg = UNAUTHOURIZED_ACCESS;
				$edit_school_err->session_status = TRUE;
				return $edit_school_err;
			}
		}
		$edit_school_obj->error = FALSE;
		$edit_school_obj->session_status = TRUE;
		return $edit_school_obj;
	}

	public function update_school_status($data)
	{
		// Added customer check
		$customer_check = $this->CI->customeradmin_model->school_check_customer($data['customer_id'],$data['contract_id'],$data['school_id']);
		if ($customer_check->chk_cus_count>0)
		{
			if(!$this->CI->customeradmin_model->update_school_status($data))
			{
				$update_school_status_err->error = TRUE;
				$update_school_status_err->error_msg = DATABASE_QUERY_FAILED;
				$update_school_status_err->session_status = TRUE;
				return $update_school_status_err;
			}
			else
			{
				$update_school_status->error = FALSE;
				$update_school_status->session_status = TRUE;
				return $update_school_status;
			}
		}
		else
		{
			$update_school_status_err->error = TRUE;
			$update_school_status_err->error_msg = UNAUTHOURIZED_ACCESS;
			$update_school_status_err->session_status = TRUE;
			return $update_school_status_err;
		}
	}

	public function  get_HH_reports($data)
	{
		return $this->CI->customeradmin_model->get_HH_reports($data);
	}

	public function  get_NHH_reports($data)
	{
		return $this->CI->customeradmin_model->get_NHH_reports($data);
	}
	public function  get_target_data($data)
	{
		return $this->CI->customeradmin_model->get_target_data($data);
	}

	public function get_setup_entities($data)
	{
		return  $this->CI->customeradmin_model->get_setup_entities($data);
	}

	public function get_data_history($data){
		return $this->CI->customeradmin_model->get_data_history($data);
	}

	private function create_contract_key($customer_id)
	{
		$contract_key = get_random_alphanum(3);	
		if(strlen($contract_key) == 3 && !$this->CI->customeradmin_model->check_contract_key($contract_key, $customer_id))
			return $contract_key;
		else 
			$this->create_contract_key($customer_id);
	}

	public function  get_pupil_import($data)
	{
		return $this->CI->customeradmin_model->get_pupil_import($data);
	}

	public function get_contract_settings($data)
	{
		return $this->CI->customeradmin_model->get_contract_settings($data);
	}

	public function get_menu_details($data)
	{
		return $this->CI->customeradmin_model->get_menu_details($data);
	}

	public function save_menu_details($data)
	{
		return $this->CI->customeradmin_model->save_menu_details($data);
	}

	/* Update contract settings */
	public function update_contract_settings($data)
	{		
		/* Update the contract settings in DB. Show the messages according to the query status */
		if(!$this->CI->customeradmin_model->update_contract_settings($data))
		{
			$update_settings_status_err->error = TRUE;
			$update_settings_status_err->error_msg = DATABASE_QUERY_FAILED;
			$update_settings_status_err->session_status = TRUE;
			return $update_settings_status_err;
		}
		else
		{
			$update_settings_status->error = FALSE;
			$update_settings_status->session_status = TRUE;
			return $update_settings_status;
		}
	}

	/* Update Menu Option Status (Enable or Disable) */
	public function update_menu_option_status($data)
	{
		/* Update the menu options in DB. Show the messages according to the query status */
		if(!$this->CI->customeradmin_model->update_menu_option_status($data))
		{
			$update_option_status_err->error = TRUE;
			$update_option_status_err->error_msg = DATABASE_QUERY_FAILED;
			$update_option_status_err->session_status = TRUE;
			return $update_option_status_err;
		}
		else
		{
			$update_option_status->error = FALSE;
			$update_option_status->session_status = TRUE;
			return $update_option_status;
		}
	}
	
	public function get_card_search_pupils($data)
	{
		$pupil_res = $this->CI->customeradmin_model->get_card_search_pupils($data);
		if($pupil_res > 0)
		{
			$card_balance = 0;
			foreach ($pupil_res as $res)
			{
				$card_balance += $res->card_balance;
			}
			$available_refund = $this->CI->customeradmin_model->get_card_available_refund($data);
			
			if($card_balance < $available_refund)
			$available_refund = $card_balance; 
			
			$pupil_obj = new stdClass();
			$pupil_obj->search_pupils_res = $pupil_res;
			$pupil_obj->available_refund = $available_refund;
			$pupil_obj->error = FALSE;
			$pupil_obj->session_status = TRUE;
			return $pupil_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}
	
	public function initiate_card_refund($data) {
		$save_res = $this->CI->customeradmin_model->initiate_card_refund($data);
		return $save_res;
	}
	
	/*public function save_card_refund($data) {
		$save_res = $this->CI->customeradmin_model->save_card_refund($data);
		if($save_res)
		{			
			$payment_res = $this->CI->customeradmin_model->get_updated_card_payment_items($data['payment_id']);

			$save_card_refund_obj = new stdClass();
			$save_card_refund_obj->save_card_refund_res = $payment_res;
			$save_card_refund_obj->error = FALSE;
			$save_card_refund_obj->session_status = TRUE;
			return $save_card_refund_obj;
		}
		else
		{
			$save_card_refund_obj = new stdClass();
			$save_card_refund_obj->error = TRUE;
			$save_card_refund_obj->error_msg = DATABASE_QUERY_FAILED;
			$save_card_refund_obj->session_status = TRUE;
			return $save_card_refund_obj;
		}
	}*/
	
	public function get_card_full_history($data)
	{
		$full_history_res = $this->CI->customeradmin_model->get_card_full_history($data);
		if($full_history_res)
		{
			$full_history_obj = new stdClass();
			$full_history_obj->full_history_res = $full_history_res;
			$full_history_obj->error = FALSE;
			$full_history_obj->session_status = TRUE;
			return $full_history_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}
	
	public function get_session_log_contract($data)
	{
		$session_log_res = $this->CI->customeradmin_model->get_session_log_contract($data);
		if($session_log_res > 0)
		{
			$session_obj = new stdClass();
			$session_obj->session_res = $session_log_res;
			$session_obj->error = FALSE;
			$session_obj->session_status = TRUE;
			return $session_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}
	
	public function get_session_log_navigation($data)
	{
		$session_log_res = $this->CI->customeradmin_model->get_session_log_navigation($data);
		if($session_log_res > 0)
		{
			$session_obj = new stdClass();
			$session_obj->log_records = $session_log_res;
			$session_obj->error = FALSE;
			$session_obj->session_status = TRUE;
			return $session_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}
	
	public function save_session_log_contract($data) {
		$save_res = $this->CI->customeradmin_model->save_session_log_contract($data);
		if($save_res)
		{
			$save_session_log_obj = new stdClass();
			$save_session_log_obj->save_session_log_res = $save_res;
			$save_session_log_obj->error = FALSE;
			$save_session_log_obj->session_status = TRUE;
			return $save_session_log_obj;
			
		}
		else
		{
			$save_session_log_obj = new stdClass();
			$save_session_log_obj->error = TRUE;
			$save_session_log_obj->error_msg = DATABASE_QUERY_FAILED;
			$save_session_log_obj->session_status = TRUE;
			return $save_session_log_obj;
		}
	}
	
	public function purge_session_log_contract($data) {
		$purge_res = $this->CI->customeradmin_model->purge_session_log_contract($data);
		if($purge_res)
		{
			$purge_obj = new stdClass();
			$purge_obj->purge_res = $purge_res;
			$purge_obj->error = FALSE;
			$purge_obj->session_status = TRUE;
			return $purge_obj;
			
		}
		else
		{
			$purge_obj = new stdClass();
			$purge_obj->error = TRUE;
			$purge_obj->error_msg = DATABASE_QUERY_FAILED;
			$purge_obj->session_status = TRUE;
			return $purge_obj;
		}
	}
	
	public function get_profile_master_details($data) {
		$profile_res = $this->CI->customeradmin_model->get_profile_master_details($data);
		if($profile_res > 0)
		{
			$profile_obj = new stdClass();
			$profile_obj->profile_res = $profile_res;
			$profile_obj->error = FALSE;
			$profile_obj->session_status = TRUE;
			return $profile_obj;
			
		}
		else
		{
			$profile_obj = new stdClass();
			$profile_obj->error = TRUE;
			$profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$profile_obj->session_status = TRUE;
			return $profile_obj;
		}
	}
	
	public function create_profile_contract($data) {
		$check_profile = $this->CI->customeradmin_model->check_profile_name_exists($data);
		if($check_profile->profile_dup_count > 0)
		{
			$create_profile_obj = new stdClass();
			$create_profile_obj->error = TRUE;
			$create_profile_obj->error_msg = PROFILE_EXIST;
			$create_profile_obj->session_status = TRUE;
			return $create_profile_obj;
		}
		$create_profile_res = $this->CI->customeradmin_model->create_profile_contract($data);
		if($create_profile_res)
		{
			$create_profile_obj = new stdClass();
			$create_profile_obj->create_profile_res = $create_profile_res;
			$create_profile_obj->error = FALSE;
			$create_profile_obj->session_status = TRUE;
			return $create_profile_obj;
			
		}
		else
		{
			$create_profile_obj = new stdClass();
			$create_profile_obj->error = TRUE;
			$create_profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$create_profile_obj->session_status = TRUE;
			return $create_profile_obj;
		}
	}
	
	public function get_profile_details_contract($data) {
		$profile_res = $this->CI->customeradmin_model->get_profile_details_contract($data);
		if($profile_res > 0)
		{
			$profile_obj = new stdClass();
			$profile_obj->profile_res = $profile_res;
			$profile_obj->error = FALSE;
			$profile_obj->session_status = TRUE;
			return $profile_obj;
				
		}
		else
		{
			$profile_obj = new stdClass();
			$profile_obj->error = TRUE;
			$profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$profile_obj->session_status = TRUE;
			return $profile_obj;
		}
	}
	
	public function save_profile_details($data) {
		$check_profile = $this->CI->customeradmin_model->check_profile_name_exists_edit($data);
		if($check_profile->profile_dup_count > 0)
		{
			$create_profile_obj = new stdClass();
			$create_profile_obj->error = TRUE;
			$create_profile_obj->error_msg = PROFILE_EXIST;
			$create_profile_obj->session_status = TRUE;
			return $create_profile_obj;
		}
		$save_profile_res = $this->CI->customeradmin_model->save_profile_details($data);
		if($save_profile_res > 0)
		{
			$profile_obj = new stdClass();
			$profile_obj->profile_res = $save_profile_res;
			$profile_obj->error = FALSE;
			$profile_obj->session_status = TRUE;
			return $profile_obj;
			
		}
		else
		{
			$profile_obj = new stdClass();
			$profile_obj->error = TRUE;
			$profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$profile_obj->session_status = TRUE;
			return $profile_obj;
		}
	}
	
	public function delete_profile_details($data) {
		$delete_profile_res = $this->CI->customeradmin_model->delete_profile_details($data);
		if($delete_profile_res)
		{
			$delete_profile_obj = new stdClass();
			$delete_profile_obj->delete_res = $delete_profile_res;
			$delete_profile_obj->error = FALSE;
			$delete_profile_obj->session_status = TRUE;
			return $delete_profile_obj;
			
		}
		else
		{
			$delete_profile_obj = new stdClass();
			$delete_profile_obj->error = TRUE;
			$delete_profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$delete_profile_obj->session_status = TRUE;
			return $delete_profile_obj;
		}
	}
	
	/* Delete energy data */
	public function purge_energy_data($data) {
		$purge_energy_res = $this->CI->customeradmin_model->purge_energy_data($data);
		if($purge_energy_res > 0)
		{
			$purge_energy_obj = new stdClass();
			$purge_energy_obj->purge_res = TRUE;
			$purge_energy_obj->error = FALSE;
			$purge_energy_obj->session_status = TRUE;
			return $purge_energy_obj;
		}
		else 
		{
			$purge_energy_obj = new stdClass();
			$purge_energy_obj->error = TRUE;
			$purge_energy_obj->error_msg = PURGE_ENERGY_DATA_NOT_FOUND;
			$purge_energy_obj->session_status = TRUE;
			return $purge_energy_obj;
		}
	}
	
	public function get_users_configure_contract($data) {
		$user_res = $this->CI->customeradmin_model->get_users_configure_contract($data);
		if($user_res > 0)
		{
			$users_obj = new stdClass();
			$users_obj->user_res = $user_res;
			$users_obj->error = FALSE;
			$users_obj->session_status = TRUE;
			return $users_obj;
				
		}
		else
		{
			$users_obj = new stdClass();
			$users_obj->error = TRUE;
			$users_obj->error_msg = DATABASE_QUERY_FAILED;
			$users_obj->session_status = TRUE;
			return $users_obj;
		}
	}
	
	public function save_users_configure_contract($data) {
		$save_res = $this->CI->customeradmin_model->save_users_configure_contract($data);
		if($save_res)
		{
			$save_users_obj = new stdClass();
			$save_users_obj->save_configure_res = $save_res;
			$save_users_obj->error = FALSE;
			$save_users_obj->session_status = TRUE;
			return $save_users_obj;
			
		}
		else
		{
			$save_users_obj = new stdClass();
			$save_users_obj->error = TRUE;
			$save_users_obj->error_msg = DATABASE_QUERY_FAILED;
			$save_users_obj->session_status = TRUE;
			return $save_users_obj;
		}
	}
	
	// To get skins list for contract
	public function get_skins($data)
	{
		$get_skins_res = $this->CI->customeradmin_model->get_skins($data);
		$get_skins_obj = new stdClass();
		$get_skins_obj->get_skins_res = $get_skins_res;
		$get_skins_obj->error = FALSE;
		$get_skins_obj->session_status = TRUE;
		return $get_skins_obj;
	}
	
	// To create new skin
	public function create_skin($data)
	{		
		$check_skin_duplicate = $this->CI->customeradmin_model->check_skin_duplicate($data);
		if($check_skin_duplicate)
		{
			$create_skin_err = new stdClass();
			$create_skin_err->error_msg = SKIN_DUPLICATE_ERROR;
			$create_skin_err->error = TRUE;
			$create_skin_err->session_status = TRUE;
			return $create_skin_err;
		}
		else
		{
			$insert_data = unserialize(SKIN_DEFAULT_VALUES);
			/*echo '<pre>';
			print_r($insert_data); exit;*/
			$insert_data['skin_name'] = $data['skin_name'];
			$insert_data['contract_id'] = $data['contract_id'];
			$insert_data['status'] = DEFAULT_STATUS;
			$insert_data['default'] = $data['default'];
			$insert_data['cuser_id'] = $data['user_id'];
			
			$skin_id = $this->CI->customeradmin_model->create_skin($insert_data);
			if($skin_id)
			{
				if(!is_dir(SKIN_PATH.$skin_id))
				{
					mkdir(SKIN_PATH.$skin_id, 0755);	//Directory creation with full permission
				};
				$src = SKIN_PATH."default/";
				$dst = SKIN_PATH.$skin_id."/";
				//shell_exec("cp -r $src $dst");
				$handle = opendir(SKIN_PATH."default"); 
				while (($file = readdir($handle))!=false) 
				{ 
					if($file != "." && $file != "..") 
					{ 
					copy ("$src/$file", "$dst/$file"); 
					} 
				} 
				closedir($handle);
				
				/* Creating a default css file */
				$css_str = SKIN_DEFAULT_CSS;
				$default_data = unserialize(SKIN_DEFAULT_VALUES);

				$css_str = str_replace(SKIN_HEADER_COLOR_REPLACE_TAG, "#".$default_data['header_link_color'], SKIN_DEFAULT_CSS);
				$css_str = str_replace(SKIN_HEADER_HOVER_COLOR_REPLACE_TAG, "#".$default_data['header_link_hcolor'], $css_str);
				$css_str = str_replace(SKIN_HEADINGS_COLOR_REPLACE_TAG, "#".$default_data['headings_color'], $css_str);
				$css_str = str_replace(SKIN_PAGE_LINK_COLOR_REPLACE_TAG, "#".$default_data['page_link_color'], $css_str);
				$css_str = str_replace(SKIN_PAGE_LINK_HOVER_COLOR_REPLACE_TAG, "#".$default_data['page_link_hcolor'], $css_str);
				$css_str = str_replace(SKIN_PAGE_BG_COLOR_REPLACE_TAG, "#".$default_data['page_bgcolor'], $css_str);

				$skin_css_file = $skin_id.SKIN_CSS_FILE_EXTENSION;

				$css_path = SKIN_PATH.$skin_id."/".$skin_css_file;
				
				file_put_contents($css_path, $css_str);
				/* css file creation ends here */
				
				$create_skin = new stdClass();
				$create_skin->error = FALSE;
				$create_skin->session_status = TRUE;
				$create_skin->skin_id = $skin_id;				
				return $create_skin;
			}
			else
			{
				$error_obj = new stdClass();
				$error_obj->error = TRUE;
				$error_obj->error_msg = DATABASE_QUERY_FAILED;
				$error_obj->session_status = TRUE;
				return $error_obj;
			}
		}
	}
	
	// To get skins details for contract
	public function get_skin_details($data)
	{
		if($this->CI->customeradmin_model->validate_skin($data))
		{
			$get_skin_details_res = $this->CI->customeradmin_model->get_skin_details($data);
			$get_skin_details_obj = new stdClass();
			$get_skin_details_obj->get_skin_details_res = $get_skin_details_res;
			$get_skin_details_obj->error = FALSE;
			$get_skin_details_obj->session_status = TRUE;
			return $get_skin_details_obj;
		}
		else 
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
	}
	
	// To get skins details for contract
	public function edit_skin($data)
	{
		$skin_data = array('skin_id' => $data['sid'], 'skin_name' => $data['sn'], 'contract_id' => $data['contract_id']);

		if(!$this->CI->customeradmin_model->validate_skin($skin_data))
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
		
		$check_skin_duplicate = $this->CI->customeradmin_model->check_skin_duplicate($skin_data);
		if($check_skin_duplicate)
		{
			$create_skin_err = new stdClass();
			$create_skin_err->error_msg = SKIN_DUPLICATE_ERROR;
			$create_skin_err->error = TRUE;
			$create_skin_err->session_status = TRUE;
			return $create_skin_err;
		}

		$skin_res = $this->CI->customeradmin_model->edit_skin($data);
		if($skin_res)
		{
			$move_res = $this->skin_move_temp_to_real_path($data);

			if($move_res)
			{
				/* Creating a default css file */
				$css_str = SKIN_DEFAULT_CSS;
				$default_data = unserialize(SKIN_DEFAULT_VALUES);

				$css_str = str_replace(SKIN_HEADER_COLOR_REPLACE_TAG, "#".$data['hlc'], SKIN_DEFAULT_CSS);
				$css_str = str_replace(SKIN_HEADER_HOVER_COLOR_REPLACE_TAG, "#".$data['hlhc'], $css_str);
				$css_str = str_replace(SKIN_HEADINGS_COLOR_REPLACE_TAG, "#".$data['hc'], $css_str);
				$css_str = str_replace(SKIN_PAGE_LINK_COLOR_REPLACE_TAG, "#".$data['plc'], $css_str);
				$css_str = str_replace(SKIN_PAGE_LINK_HOVER_COLOR_REPLACE_TAG, "#".$data['plhc'], $css_str);
				$css_str = str_replace(SKIN_PAGE_BG_COLOR_REPLACE_TAG, "#".$data['pb'], $css_str);

				$skin_css_file = $data['sid'].SKIN_CSS_FILE_EXTENSION;

				$css_path = SKIN_PATH.$data['sid']."/".$skin_css_file;
				
				file_put_contents($css_path, $css_str);
				/* css file creation ends here */
				
				$edit_skin_obj = new stdClass();
				$edit_skin_obj->edit_skin_res = $skin_res;
				$edit_skin_obj->error = FALSE;
				$edit_skin_obj->session_status = TRUE;
				return $edit_skin_obj;
			}
			else
			{
				$edit_skin_obj = new stdClass();
				$edit_skin_obj->error = TRUE;
				$edit_skin_obj->error_msg = FILE_UPLOAD_ERROR;
				$edit_skin_obj->session_status = TRUE;
				return $edit_skin_obj;
			}			
		}
		else
		{
			$edit_skin_obj = new stdClass();
			$edit_skin_obj->error = TRUE;
			$edit_skin_obj->error_msg = DATABASE_QUERY_FAILED;
			$edit_skin_obj->session_status = TRUE;
			return $edit_skin_obj;
		}
		
		
	}
	
	/* Move the skin files from temporary path to skin id path */
	private function skin_move_temp_to_real_path($data) 
	{
		$session_id = $this->CI->session->userdata('user_session');
		$skin_id = $data['sid'];
		foreach($data as $key => $value)
		{
			if(!is_dir(SKIN_PATH.$skin_id))
			{
				mkdir(SKIN_PATH.$skin_id, 0755);	//Directory creation with full permission
			};
			
			$src = TEMP_SKIN_PATH.$session_id.'/'.$key.'.'.SKIN_FILE_FORMAT;
			$dst = SKIN_PATH.$skin_id.'/'.$key.'.'.SKIN_FILE_FORMAT;
			$default_src = DEFAULT_SKIN_PATH.$key.'.'.SKIN_FILE_FORMAT;
			
			switch($key)
			{				
				case "logo":					
					if($value == ACTIVE)
					{
						if($data['logo_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
					
				case "smartphone":
					if($value == ACTIVE)
					{
						if($data['smartphone_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
				
				case "header_div":
					if($value == ACTIVE)
					{
						if($data['header_div_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
					
				case "level12_bg":
					if($value == ACTIVE)
					{
						if($data['level12_bg_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
					
				case "level2_bg":
					if($value == ACTIVE)
					{
						if($data['level2_bg_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
				
				case "no_nav":
					if($value == ACTIVE)
					{
						if($data['no_nav_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
					
				case "widget_header":
					if($value == ACTIVE)
					{
						if($data['widget_header_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
				
				case "select_bg":
					if($value == ACTIVE)
					{
						if($data['select_bg_def'] == ACTIVE && file_exists($default_src))
						{
							copy ($default_src, $dst);
						}												
						else if(file_exists($src))
						{
							copy ($src, $dst);
						}
					}
					break;
			}
		}
		return TRUE;
	}
	
	// To delete skins details for contract
	public function delete_skin($data)
	{
		$skin_data = array('skin_id' => $data['sid'], 'contract_id' => $data['contract_id']);

		if(!$this->CI->customeradmin_model->validate_skin($skin_data))
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
		
		if(($this->CI->customeradmin_model->check_skin_profile($skin_data)) > 0)
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = SKIN_DELETE_PROFILE_ERROR;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}

		if(!$this->delete_skin_directory($skin_data))
		{
			$delete_skin_obj = new stdClass();
			$delete_skin_obj->error = TRUE;
			$delete_skin_obj->error_msg = FILE_DELETE_ERROR;
			$delete_skin_obj->session_status = TRUE;
			return $delete_skin_obj;
		}
		
		$skin_res = $this->CI->customeradmin_model->delete_skin($data);
		if($skin_res)
		{
			$delete_skin_obj = new stdClass();
			$delete_skin_obj->delete_skin_res = $skin_res;
			$delete_skin_obj->error = FALSE;
			$delete_skin_obj->session_status = TRUE;
			return $delete_skin_obj;
		}
		else
		{
			$delete_skin_obj = new stdClass();
			$delete_skin_obj->error = TRUE;
			$delete_skin_obj->error_msg = DATABASE_QUERY_FAILED;
			$delete_skin_obj->session_status = TRUE;
			return $delete_skin_obj;
		}
	}
	
	private function delete_skin_directory($data)
	{
		$skin_dir_path = SKIN_PATH.$data['skin_id'];
		
		if(is_dir($skin_dir_path))
		{
			foreach(glob($skin_dir_path . '/*') as $file) {
				if(is_dir($file)) rmdir($file); else unlink($file);
			}
			rmdir($skin_dir_path);
			return TRUE;
		}
		return FALSE;
	}
	
	public function validateTransactionId($data)
	{
		$payment_res = $this->CI->customeradmin_model->validateTransactionId($data);
		return $payment_res;
	}
	
	public function get_payment_details($data)
	{
		$payment_res = $this->CI->customeradmin_model->get_payment_details($data);
		return $payment_res;
	}
	
	public function get_card_available_refund($data)
	{
		$available_refund = $this->CI->customeradmin_model->get_card_available_refund($data);
		return $available_refund;
	}
	
	public function change_card_refund_status($data)
	{
		$save_res = $this->CI->customeradmin_model->change_card_refund_status($data);
		if($save_res)
		{
			$yp_msg = $this->CI->customeradmin_model->get_yp_error_code($data['yp_code']);
			$payment_res = $this->CI->customeradmin_model->get_updated_card_payment_items($data['payment_id']);

			$save_card_refund_obj = new stdClass();
			$save_card_refund_obj->card_refund_res = $payment_res;
			$save_card_refund_obj->yp_status = $data['yp_code'];
			$save_card_refund_obj->yp_msg = $yp_msg;	
			$save_card_refund_obj->error = FALSE;
			$save_card_refund_obj->session_status = TRUE;
			return $save_card_refund_obj;
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = DATABASE_QUERY_FAILED;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
	}
	
	public function get_yp_error_code($data)
	{
		$yp_error_res = $this->CI->customeradmin_model->get_yp_error_code($data);
		if(!$yp_error_res)
		{
			$yp_error_err->error = TRUE;
			$yp_error_err->error_msg = DATABASE_QUERY_FAILED;
			$yp_error_err->session_status = TRUE;
			return $yp_error_err;
		}
		else
		{
			$yp_error->error = FALSE;
			$yp_error->refund_res = $error_res;
			$yp_error->session_status = TRUE;
			return $yp_error;
		}
	}
	
	/* For resource management setcion...*/
	
	/* For adding zone */	
	public function add_edit_zone($data)
	{
		// first check the zone name duplicate
		// Second check the device id, serial number combination duplicate
		$chk_dup_zone_name = $this->CI->customeradmin_model->check_duplicate_zone_name($data);
		if($chk_dup_zone_name->cnt > 0 ){
			$error_obj = new stdClass();
			$error_obj->error_msg = CADMIN_ZONE_NAME_DUPLICATE;
			$error_obj->error = TRUE;
			
			return $error_obj;
		}else {
			$chk_dup_zone_device = $this->CI->customeradmin_model->check_duplicate_zone_device($data);
			if($chk_dup_zone_device->cnt > 0 ){
					
				$error_obj = new stdClass();
				$error_obj->error_msg = CADMIN_ZONE_DEVICE_DUPLICATE;
				$error_obj->error = TRUE;
				return $error_obj;
			} else 
			{
				
				$add_edit_zone = $this->CI->customeradmin_model->add_edit_zone($data);
				
				$add_edit_zone_obj = new stdClass();
				$add_edit_zone_obj->error = FALSE;
				$add_edit_zone_obj->add_edit_zone = $add_edit_zone;			
				return $add_edit_zone_obj;
			}
		}
	}
	
	/* For deleting zone */
	public function delete_zone($data)
	{
		return $this->CI->customeradmin_model->delete_zone($data);
				
	}
	
	public function add_edit_asset($data)
	{
		// check the asset duplicate
		$chk_dup_res = $this->CI->customeradmin_model->check_duplicate_asset($data);
		if($chk_dup_res->cnt > 0 )
		{
			$error_obj = new stdClass();
			$error_obj->error_msg = CADMIN_ASSET_DUPLICATE;
			$error_obj->error = TRUE;
			
			return $error_obj;
		}
		else 
		{
			$add_edit_asset = $this->CI->customeradmin_model->add_edit_asset($data);
			$add_edit_asset->error = FALSE;
			return $add_edit_asset;
		}
	}
	
	public function get_asset_details($data)
	{
		return $this->CI->customeradmin_model->get_asset_details($data);
	}
	
	public function get_asset_read_details($data)
	{
		return $this->CI->customeradmin_model->get_asset_read_details($data);
	}
	
	public function delete_asset($data)
	{
		return $this->CI->customeradmin_model->delete_asset($data);
	}
	
	public function delete_digital_zip_file($data)
	{
		return $this->CI->customeradmin_model->delete_digital_zip_file($data);
	}

	public function get_navigation_form_details()
	{
		return $this->CI->customeradmin_model->get_navigation_form_details();
	}

	public function get_digital_app_details($data)
	{
		return $this->CI->customeradmin_model->get_digital_app_details($data);
	}

	public function get_App_info($data)
	{
		$dst = ZIP_UPLOAD_PATH. $data['contract_id'] . '/' . $data['app_id'].'/';
		if(is_dir($dst))
		{
			$htmlFileName = $this->get_html_file_name($dst);
			$oldName = $dst . $htmlFileName;
			$ext = $this->get_file_extenstion($oldName);
			$newFileName = $data['app_id'] . '.' . $ext;
			$newName = $dst . $newFileName;

			$page_url = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
			$page_url= $page_url.$_SERVER["SERVER_NAME"] . '/' . ZIP_FOLDER_NAME_UPLOAD . $data['contract_id'] . '/' . $data['app_id'] . '/' . $newFileName;

			$data['preview_link'] = $page_url;
		}
		return $this->CI->customeradmin_model->get_digital_app_info($data);
	}


	public function add_edit_digital_apps($data)
	{
		$chk_form_dup = $this->CI->customeradmin_model->check_digital_form_duplicate($data);
		if(!$chk_form_dup && $data['frm_type'] != OTHER_FORM_TYPE_ID)
		{
			$error_obj = new stdClass();

			if($data['frm_type'] == LUNCH_FORM_TYPE_ID)
				$error_obj->error_msg = CADMIN_LUNCH_NAME_DUPLICATE;
			else if($data['frm_type'] == DINNER_FORM_TYPE_ID)
				$error_obj->error_msg = CADMIN_DINNER_NAME_DUPLICATE;
			else if ($data['frm_type'] == ADHOC_FORM_TYPE_ID)
				$error_obj->error_msg = CADMIN_ADHOC_NAME_DUPLICATE;
			 
			$error_obj->session_status = TRUE;
			$error_obj->error = TRUE;
			return $error_obj;
		}
		 
		$chk_dup_app_name = $this->CI->customeradmin_model->check_duplicate_digital_app_name($data);
		if($chk_dup_app_name->cnt > 0 ){
			$error_obj = new stdClass();
			$error_obj->error_msg = CADMIN_APP_NAME_DUPLICATE;
			$error_obj->session_status = TRUE;
			$error_obj->error = TRUE;
			return $error_obj;
		}
		else
		{
			$digital_id = $this->CI->customeradmin_model->add_edit_digital_apps($data);
			if($digital_id)
			{
				if($data['upld_tplate'] == 1)
				{
					if(!is_dir(ZIP_UPLOAD_PATH. $data['contract_id']))
					{
						mkdir(ZIP_UPLOAD_PATH. $data['contract_id'], 0755);
					}

					$session_id = $this->CI->session->userdata('user_session');

					$src = TEMP_ZIP_UPLOAD_PATH.$session_id.'/';
					$dst = ZIP_UPLOAD_PATH. $data['contract_id'] . '/' . $digital_id.'/';

					if(!is_dir($dst))
					{
						mkdir($dst, 0755);
					}

					if(is_dir($src))
					{
						$file_list_array = $this->get_file_list($src);
						foreach($file_list_array as $key => $value)
						{
							if($value != '' && $value != '..' && $value != '.')
							{
								$src_file = $src . '/' . $value;
								$dest = $dst. $value;
									
								if(rename ($src_file, $dest))
								{
									if(file_exists($src_file))
										unlink($src_file);
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
						if(is_dir($src))
							delete_directory_recursion($src);
							
						if(file_exists($dst))
						{
							$htmlFileName = $this->get_html_file_name($dst);
							$oldName = $dst . $htmlFileName;
							$ext = $this->get_file_extenstion($oldName);
							$newFileName = $digital_id . '.' . $ext;
							$newName = $dst . $newFileName;
							if(rename($oldName, $newName))		// Rename the file
							{
								if(is_dir($dst))
								{
									$htmlFileName = $this->get_html_file_name($dst);
									$oldName = $dst . $htmlFileName;
									$ext = $this->get_file_extenstion($oldName);
									$newFileName = $digital_id . '.' . $ext;
									$newName = $dst . $newFileName;

									$page_url = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
									$page_url= $page_url.$_SERVER["SERVER_NAME"] . '/' . ZIP_FOLDER_NAME_UPLOAD . $data['contract_id'] . '/' . $digital_id . '/' . $newFileName;

									$preview_link = $page_url;

									$add_edit_App_obj = new stdClass();
									$add_edit_App_obj->error = FALSE;
									$add_edit_App_obj->session_status = TRUE;
									$add_edit_App_obj->preview_link = $preview_link;
									$add_edit_App_obj->add_edit_app = $digital_id;
									return $add_edit_App_obj;
								}
							}
							else	// Need to changed
							{
								if(is_dir($dst))
								{
									$htmlFileName = $this->get_html_file_name($dst);
									$oldName = $dst . $htmlFileName;
									$ext = $this->get_file_extenstion($oldName);
									$newFileName = $digital_id . '.' . $ext;
									$newName = $dst . $newFileName;

									$page_url = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
									$page_url= $page_url.$_SERVER["SERVER_NAME"] . '/' . ZIP_FOLDER_NAME_UPLOAD . $data['contract_id'] . '/' . $digital_id . '/' . $newFileName;

									$preview_link = $page_url;

									$add_edit_App_obj = new stdClass();
									$add_edit_App_obj->error = FALSE;
									$add_edit_App_obj->session_status = TRUE;
									$add_edit_App_obj->preview_link = $preview_link;
									$add_edit_App_obj->add_edit_app = $digital_id;
									return $add_edit_App_obj;
								}
							}
						}
					}
				}
			}
			$add_edit_App_obj = new stdClass();
			$add_edit_App_obj->error = FALSE;
			$add_edit_App_obj->session_status = TRUE;
			$add_edit_App_obj->add_edit_app = $digital_id;
			return $add_edit_App_obj;
		}
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

	/* Get the file extenstion for given file */
	private function get_file_extenstion($file)
	{
		$path_info = pathinfo($file);
		$filetype =  $path_info['extension'];
		return $filetype;
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

	public function delete_digital_app_details($data) {
		$delete_app_res = $this->CI->customeradmin_model->delete_digital_app_details($data);
		if($delete_app_res)
		{
			$zip_path = ZIP_UPLOAD_PATH . $data['contract_id'] . '/' . $data['app_id'];
			
			if(is_dir($zip_path))
				delete_directory_recursion($zip_path);
			
			$delete_app_obj = new stdClass();
			$delete_app_obj->delete_res = $delete_app_res;
			$delete_app_obj->error = FALSE;
			$delete_app_obj->session_status = TRUE;
			return $delete_app_obj;
				
		}
		else
		{
			$delete_app_obj = new stdClass();
			$delete_app_obj->error = TRUE;
			$delete_app_obj->error_msg = DATABASE_QUERY_FAILED;
			$delete_app_obj->session_status = TRUE;
			return $delete_app_obj;
		}
	}
	
	public function add_edit_digital_pen($data)
	{
		// check the digital_pen duplicate
		$chk_dup_res = $this->CI->customeradmin_model->check_duplicate_digital_pen($data);
		if($chk_dup_res->cnt > 0 )
		{
			$error_obj = new stdClass();
			$error_obj->error_msg = DIGITAL_PEN_DUPLICATE;
			$error_obj->error = TRUE;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
		else 
		{
			$digital_pen_id = $this->CI->customeradmin_model->add_edit_digital_pen($data);
			$add_edit_digital_pen = new stdClass();
			$add_edit_digital_pen->error = FALSE;
			$add_edit_digital_pen->session_status = TRUE;
			$add_edit_digital_pen->dp_id = $digital_pen_id;
			return $add_edit_digital_pen;
		}
	}
	
	public function get_digital_pen_details($data)
	{
		return $this->CI->customeradmin_model->get_digital_pen_details($data);
	}
	
	public function get_digital_pens($data)
	{
		return $this->CI->customeradmin_model->get_digital_pens($data);
	}
	
	public function delete_digital_pen($data)
	{
		return $this->CI->customeradmin_model->delete_digital_pen($data);
	}
	
	public function get_qa_accounts($data)
	{
		return $this->CI->customeradmin_model->get_qa_accounts($data);
	}
	
	public function get_qa_account_details($data)
	{
		$account_details = $this->CI->customeradmin_model->get_qa_account_details($data);
		$user_details = $this->CI->customeradmin_model->get_qa_user_access_details($data);
		$account_obj = new stdClass();
		$account_obj->error = FALSE;
		$account_obj->session_status = TRUE;
		$account_obj->acc_res = $account_details;
		$account_obj->user_res = $user_details;
		return $account_obj;
	}
	
	public function get_qa_user_access_details($data)
	{
		$user_details = $this->CI->customeradmin_model->get_qa_user_access_details($data);
		$account_obj = new stdClass();
		$account_obj->error = FALSE;
		$account_obj->session_status = TRUE;
		$account_obj->user_res = $user_details;
		return $account_obj;
	}
	
	public function add_edit_qa_account($data)
	{
		$chk_dup_res = $this->CI->customeradmin_model->check_duplicate_qa_account($data);
		$chk_dup_code = $this->CI->customeradmin_model->check_duplicate_qa_account_code($data);
		
		if($chk_dup_res->acc_dup_count > 0 )
		{
			$error_obj = new stdClass();
			$error_obj->error_msg = QA_ACCOUNT_DUPLICATE;
			$error_obj->error = TRUE;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
		else if($chk_dup_code->acc_dup_code_count > 0 )
		{
			$error_obj = new stdClass();
			$error_obj->error_msg = QA_ACCOUNT_CODE_DUPLICATE;
			$error_obj->error = TRUE;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
		else 
		{
			$add_edit_res = $this->CI->customeradmin_model->add_edit_qa_account($data);
			$account_obj = new stdClass();
			$account_obj->error = FALSE;
			$account_obj->session_status = TRUE;
			$account_obj->add_edit_res = $add_edit_res;
			return $account_obj;
		}
	}
	
	public function delete_qa_account($data)
	{
		$delete_res = $this->CI->customeradmin_model->delete_qa_account($data);
		$account_obj = new stdClass();
		$account_obj->error = FALSE;
		$account_obj->session_status = TRUE;
		$account_obj->delete_res = $delete_res;
		return $account_obj;
	}
	
	public function get_qa_groups($data)
	{
		return $this->CI->customeradmin_model->get_qa_groups($data);
	}
	
	public function get_qa_group_indicators($data)
	{
		return $this->CI->customeradmin_model->get_qa_group_indicators($data);
	}
	
	public function get_qa_group_details($data)
	{
		return $this->CI->customeradmin_model->get_qa_group_details($data);
	}
	
	public function add_edit_qa_groups($data)
	{
		// check the asset duplicate
		$chk_dup_res = $this->CI->customeradmin_model->check_duplicate_qa_groups($data);
		
		if($chk_dup_res->group_dup_count > 0 )
		{
			$error_obj = new stdClass();
			$error_obj->error_msg = QA_GROUP_DUPLICATE;
			$error_obj->error = TRUE;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
		else 
		{
			$add_edit_group = $this->CI->customeradmin_model->add_edit_qa_groups($data);
			
			$add_edit_group_obj = new stdClass();
			$add_edit_group_obj->error = FALSE;
			$add_edit_group_obj->add_edit_group = $add_edit_group;
			$add_edit_group_obj->session_status = TRUE;
			return $add_edit_group_obj;
		}
	}
	
	public function delete_qa_group($data)
	{
		return $this->CI->customeradmin_model->delete_qa_group($data);
	}
}
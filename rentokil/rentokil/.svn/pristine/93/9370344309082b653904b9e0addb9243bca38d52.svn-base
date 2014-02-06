<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_superadmin {
	
	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('superadmin_model');
	}
	
	public function  get_customers()
	{
		$customers_res = $this->CI->superadmin_model->get_customers();
		return $customers_res;
	}
	
	public function create_customer($data)
	{
		$check_customer_duplicate = $this->CI->superadmin_model->check_customer_duplicate($data);
		if($check_customer_duplicate->cus_dup_count>0)
		{
			$create_customer_err->error_msg = SADMIN_CUSTOMER_DUPLICATE;
			$create_customer_err->error = TRUE;
			return $create_customer_err;
		}
		else 
		{
			$customer_id = $this->CI->superadmin_model->create_customer($data);
			if($customer_id)
			{
				$create_customer_obj = new stdClass();
				$create_customer_obj->customer_id = $customer_id;
				$create_customer_obj->error = FALSE;
				$create_customer_obj->session_status = TRUE;
				return $create_customer_obj;
			}
		}
	}
	
	public function edit_customer($data)
	{
		$check_customer_duplicate = $this->CI->superadmin_model->check_customer_duplicate($data);
		if($check_customer_duplicate->cus_dup_count>0)
		{
			$edit_customer_err->error_msg = SADMIN_CUSTOMER_NAME_DUPLICATE;
			$edit_customer_err->error = TRUE;
			return $edit_customer_err;
		}
		else 
		{
			if($this->CI->superadmin_model->edit_customer($data))
			{
				$edit_customer->error = FALSE;
				return $edit_customer;
			}
		}
	}
	
	public function update_customer_status($data)
	{
		$update_customer_status = $this->CI->superadmin_model->update_customer_status($data);
		return $update_customer_status;
	}
	
	public function update_customer_admin_status($data)
	{
		$update_customer_admin_status = $this->CI->superadmin_model->update_customer_admin_status($data);
		return $update_customer_admin_status;
	}
	
	public function get_customer_admin($data)
	{
		$get_customer_admin = $this->CI->superadmin_model->get_customer_admin($data);
		return $get_customer_admin;
	}
	
	public function sa_get_contracts($data)
	{
		$get_contracts = $this->CI->superadmin_model->sa_get_contracts($data);
		return $get_contracts;
	}
	
	public function sa_get_users_configure_contract($data)
	{
		$get_users_res = $this->CI->superadmin_model->sa_get_users_configure_contract($data);
		return $get_users_res;
	}
	
	public function sa_save_configure_contract($data)
	{
		$save_users_res = $this->CI->superadmin_model->sa_save_configure_contract($data);
		if($save_users_res)
		{
			$save_users_obj = new stdClass();
			$save_users_obj->save_configure_res = $save_users_res;
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
	
	public function create_customer_admin($data)
	{
		$check_customer_admin_duplicate = $this->CI->superadmin_model->check_customer_admin_duplicate($data);
		if($check_customer_admin_duplicate->cus_admin_dup_count>0)
		{
			$create_customer_admin_err->error_msg = SADMIN_CADMIN_DUPLICATE;
			$create_customer_admin_err->error = TRUE;
			return $create_customer_admin_err;
		}
		else 
		{
			$data['username'] = $data['admin_email'];
			
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
			
			$data['admin_email']= $user_email;
			
			//$password = mt_rand();
			$password = generate_password();
			/*if(strlen($password) < RANDOM_PASSWORD_LENGTH)
			{
				$password = RANDOM_PASS_FS;
			}*/
			$data['password'] = md5($password);
			$data['role_id'] = CUSTOMER_ADMIN;
			$data['status'] = DEFAULT_STATUS;
			$data['chg_pwd'] = ACTIVE;
			
			$user_id = $this->CI->superadmin_model->create_customer_admin($data);
			
			if($user_id)
			{
				$data['password_email'] = $password;
				$data['user_id'] = $user_id;
				$new_customer_admin_email_res = new_customer_admin_email($data);	// Send mail to customer admin
				if (!$new_customer_admin_email_res)
				{
					$create_customer_admin->error_msg = SADMIN_CADMIN_EMAIL_FAILED;
					$create_customer_admin->error = TRUE;
					return $create_customer_admin;
				}
				else 
				{
					$create_customer_admin->error = FALSE;
					return $create_customer_admin;
				}
			}
			else
			{
				$create_customer_admin->error_msg = DATABASE_QUERY_FAILED;
				$create_customer_admin->error = TRUE;	
			}
		}
	}
	
	public function admin_create_profile($data) {
		$check_profile = $this->CI->superadmin_model->check_profile_name_exists($data);
		if($check_profile->profile_dup_count > 0)
		{
			$create_profile_obj = new stdClass();
			$create_profile_obj->error = TRUE;
			$create_profile_obj->error_msg = PROFILE_EXIST;
			$create_profile_obj->session_status = TRUE;
			return $create_profile_obj;
		}
		$create_profile_res = $this->CI->superadmin_model->admin_create_profile($data);
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
	
	public function get_admin_profile_master_details($data) {
		$profile_res = $this->CI->superadmin_model->get_admin_profile_master_details($data);
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
	
	public function get_admin_profile_details_contract($data) {
		$profile_res = $this->CI->superadmin_model->get_admin_profile_details_contract($data);
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
	
	public function save_admin_profile_details($data) {
		$check_profile = $this->CI->superadmin_model->check_profile_name_exists($data);
		if($check_profile->profile_dup_count > 0)
		{
			$create_profile_obj = new stdClass();
			$create_profile_obj->error = TRUE;
			$create_profile_obj->error_msg = PROFILE_EXIST;
			$create_profile_obj->session_status = TRUE;
			return $create_profile_obj;
		}
		$save_profile_res = $this->CI->superadmin_model->save_admin_profile_details($data);
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
	
	public function delete_admin_profile_details($data) {
		$delete_profile_res = $this->CI->superadmin_model->delete_admin_profile_details($data);
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
}
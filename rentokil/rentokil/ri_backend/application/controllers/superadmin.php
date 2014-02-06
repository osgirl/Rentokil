<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'business/business_superadmin.php';

class Superadmin extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
		/* Validating the Role */
		
		if(!validate_role(SUPER_ADMIN))
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
	
	/* Getting the customer details */
	public function get_customers()
	{
        //1.validate session
        //2.call business object to get the customers
        //3.return an object
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();
		$customers_res = $bw_obj->get_customers();
		$customer_obj->error = FALSE;
		$customer_obj->customers_res = $customers_res;
		$customer_obj->session_status = TRUE;
		echo json_encode($customer_obj);
	}
	
	/* Creating the customer */
	public function create_customer()
	{
		//$data = array('customer_name'=>'test cust123');
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();
		if ($data['customer_name']!='')
		{
			$create_customer = $bw_obj->create_customer($data);
			if ($create_customer->error)
			{
				$create_customer_obj->error = TRUE;
				$create_customer_obj->error_msg = $create_customer->error_msg;
				$create_customer_obj->session_status = TRUE;
				echo json_encode($create_customer_obj);
			}
			else 
			{
				echo json_encode($create_customer);
			}
		}
		else 
		{
			$create_customer_obj->error = TRUE;
			$create_customer_obj->error_msg = INPUT_DATA_MISSING;
			$create_customer_obj->session_status = TRUE;
			echo json_encode($create_customer_obj);
		}
	}
	
	/* Update the customer details */
	public function edit_customer()
	{
		//$data = array('customer_name'=>'test 111','customer_id'=>4);
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();
		if ($data['customer_name']!='')
		{
			$create_customer = $bw_obj->edit_customer($data);
			if ($create_customer->error)
			{
				$edit_customer_obj->error = TRUE;
				$edit_customer_obj->error_msg = $create_customer->error_msg;
				$edit_customer_obj->session_status = TRUE;
				echo json_encode($edit_customer_obj);
			}
			else 
			{
				$edit_customer_obj->error = FALSE;
				$edit_customer_obj->session_status = TRUE;
				echo json_encode($edit_customer_obj);
			}
		}
		else 
		{
			$edit_customer_obj->error = TRUE;
			$edit_customer_obj->error_msg = INPUT_DATA_MISSING;
			$edit_customer_obj->session_status = TRUE;
			echo json_encode($edit_customer_obj);
		}
	}
	
	/* Creating the Customer Admin */
	public function create_customer_admin()
	{
		//$data = array('customer_id'=>'1','admin_fname'=>'test123','admin_lname'=>'last343','admin_email'=>'testadmin555@9889dfd.com');
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();
		if (($data['customer_id']!='')&&($data['admin_fname']!='')&&($data['admin_lname']!='')&&($data['admin_email']!=''))
		{
			$create_customer_admin = $bw_obj->create_customer_admin($data);
			if ($create_customer_admin->error)
			{
				$create_customer_admin_obj->error = TRUE;
				$create_customer_admin_obj->error_msg = $create_customer_admin->error_msg;
				$create_customer_admin_obj->session_status = TRUE;
				echo json_encode($create_customer_admin_obj);
			}
			else 
			{
				$create_customer_admin_obj->error = FALSE;
				$create_customer_admin_obj->session_status = TRUE;
				echo json_encode($create_customer_admin_obj);
			}
		}
		else 
		{
			$create_customer_admin_obj->error = TRUE;
			$create_customer_admin_obj->error_msg = INPUT_DATA_MISSING;
			$create_customer_admin_obj->session_status = TRUE;
			echo json_encode($create_customer_admin_obj);
		}
	}
	
	/* Updating the customer status */
	public function update_customer_status()
	{
		//$data = array('customer_id'=>'3','status'=>'1');
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();

		if (($data['customer_id']!='') && ($data['status']!=''))
		{
			$update_customer_status = $bw_obj->update_customer_status($data);
			if ($update_customer_status)
			{
				$update_customer_status_obj->error = FALSE;
				$update_customer_status_obj->session_status = TRUE;
				echo json_encode($update_customer_status_obj);
			}
			else 
			{
				$update_customer_status_obj->error = TRUE;
				$update_customer_status_obj->session_status = TRUE;
				echo json_encode($update_customer_status_obj);
			}
		}
		else 
		{
			$update_customer_status_obj->error = TRUE;
			$update_customer_status_obj->error_msg = INPUT_DATA_MISSING;
			$update_customer_status_obj->session_status = TRUE;
			echo json_encode($update_customer_status_obj);
		}
	}
	
	/* Updating the customer admin status */
	public function update_customer_admin_status()
	{
		//$data = array('customer_admin_id'=>'3','status'=>'0');
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();

		if (($data['customer_admin_id']!='') && ($data['status']!=''))	// input validation
		{
			$update_customer_admin_status = $bw_obj->update_customer_admin_status($data);
			if ($update_customer_admin_status)
			{
				$update_customer_admin_status_obj->error = FALSE;
				$update_customer_admin_status_obj->session_status = TRUE;
				echo json_encode($update_customer_admin_status_obj);
			}
			else 
			{
				$update_customer_admin_status_obj->error = TRUE;
				$update_customer_admin_status_obj->session_status = TRUE;
				echo json_encode($update_customer_admin_status_obj);
			}
		}
		else 
		{
			$update_customer_admin_status_obj->error = TRUE;
			$update_customer_admin_status_obj->error_msg = INPUT_DATA_MISSING;
			$update_customer_admin_status_obj->session_status = TRUE;
			echo json_encode($update_customer_admin_status_obj);
		}
	}
	
	/* Getting the Customer admin details */
	public function get_customer_admin()
	{
		//$data = array('customer_id'=>'1');
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();

		if ($data['customer_id']!='')
		{
			$get_customer_admin = $bw_obj->get_customer_admin($data);
			
			$get_customer_admin_obj->error = FALSE;
			$get_customer_admin_obj->session_status = TRUE;
			$get_customer_admin_obj->customer_admin_res = $get_customer_admin;
			echo json_encode($get_customer_admin_obj);
		}
		else 
		{
			$get_customer_admin_obj->error = TRUE;
			$get_customer_admin_obj->error_msg = INPUT_DATA_MISSING;
			$get_customer_admin_obj->session_status = TRUE;
			echo json_encode($get_customer_admin_obj);
		}
	}
	
	/* Get Contracts */
	public function sa_get_contracts()
	{
		//$data = array('customer_id'=>'1');
		
		$data = $this->security->xss_clean($_POST);
		$bw_obj = new Business_superadmin();
		
		if ($data['customer_id']!='')
		{
			$get_contracts = $bw_obj->sa_get_contracts($data);
			
			$get_contracts_obj->error = FALSE;
			$get_contracts_obj->session_status = TRUE;
			$get_contracts_obj->customer_admin_res = $get_contracts;
			echo json_encode($get_contracts_obj);
		}
		else 
		{
			$get_contracts_obj->error = TRUE;
			$get_contracts_obj->error_msg = INPUT_DATA_MISSING;
			$get_contracts_obj->session_status = TRUE;
			echo json_encode($get_contracts_obj);
		}
	}
	
	/* Configure contract for super admin*/
	public function sa_get_users_configure_contract()
	{
		//$data = array('customer_id'=>'1', 'contract_id' => 80);
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		$data['role_id'] = CUSTOMER_ADMIN;
		
		$bw_obj = new Business_superadmin();
		
		if ($data['customer_id']!='' && $data['contract_id'] != '')
		{
			if(!validate_contract_sadmin($data))	//validating the contract id
			{
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
			$get_user_res = $bw_obj->sa_get_users_configure_contract($data);
			
			$get_user_obj->error = FALSE;
			$get_user_obj->session_status = TRUE;
			$get_user_obj->user_res = $get_user_res;
			echo json_encode($get_user_obj);
		}
		else 
		{
			$get_user_obj->error = TRUE;
			$get_user_obj->error_msg = INPUT_DATA_MISSING;
			$get_user_obj->session_status = TRUE;
			echo json_encode($get_user_obj);
		}
	}
	
	/*Save Contracts in super admin */
	public function sa_save_configure_contract()
	{
		/*$data['contract_id'] = 80;
		$data['customer_id'] = 1;
		$data['user_data'] = array(
								 array(
		 									'user_id' => '249',
		 									'status' => '1'
									 )
		 );*/
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		if ($data['customer_id'] == '' && $data['contract_id'] == '')
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		if(!validate_contract_sadmin($data))	//validating the contract id
		{
			$error_obj->error = TRUE;
			$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
			$error_obj->session_status = TRUE;
			echo json_encode($error_obj);
			exit;
		}
		
		foreach($data['user_data'] as $key => $value)
		{
			$chk_data = array('user_id' => $value['user_id'], 'customer_id' => $data['customer_id']);
			if(!validate_user_customer($chk_data))	//validating the user_id & customer_id
			{
				$error_obj->error = TRUE;
				$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				$error_obj->session_status = TRUE;
				echo json_encode($error_obj);
				exit;
			}
		}
		$bw_obj = new Business_superadmin();
		$user_res = $bw_obj->sa_save_configure_contract($data);
		echo json_encode($user_res);
	}
	
	/* Create New Profile for a Super Admin */
	public function admin_create_profile()
	{
		/*$data['customer_id'] = 1;
		$data['profile_name'] = 'Profile1';*/
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		
		$bw_obj = new Business_superadmin();
		$profile_res = $bw_obj->admin_create_profile($data);
		echo json_encode($profile_res);
	}
	
	/* Get All profiles for a contract */
	public function get_admin_profile_master_details()
	{
		//$data['customer_id'] = 1;
		
		$data = $this->security->xss_clean($_POST);
		if ($data['customer_id']=='')
		{
			$get_user_obj->error = TRUE;
			$get_user_obj->error_msg = INPUT_DATA_MISSING;
			$get_user_obj->session_status = TRUE;
			echo json_encode($get_user_obj);
			exit;
		}
		$bw_obj = new Business_superadmin();
		$profile_res = $bw_obj->get_admin_profile_master_details($data);
		echo json_encode($profile_res);
	}
	
	/* Get Profile Details */
	public function get_admin_profile_details_contract()
	{
		/*$data['customer_id'] = 1;
		$data['ad_profile_id'] = 1;*/
		
		$data = $this->security->xss_clean($_POST);
		if ($data['customer_id']=='')
		{
			$get_user_obj->error = TRUE;
			$get_user_obj->error_msg = INPUT_DATA_MISSING;
			$get_user_obj->session_status = TRUE;
			echo json_encode($get_user_obj);
			exit;
		}
		$bw_obj = new Business_superadmin();
		$profile_res = $bw_obj->get_admin_profile_details_contract($data);
		echo json_encode($profile_res);
	}
	
	/* Save Profile details */
	public function save_admin_profile_details()
	{
		/*$data['customer_id'] = 1;
		$data['ad_profile_id'] = 1;
		
		$data['profile_name'] = "Energy Analyst";
		$data['self_reg'] = 1;
		$data['create_con'] = 1;
		
		$data['ad_m_mod_id'] = 2;
		
		$data['profile_s_module_data'] = array(3, 4);
		$data['profile_ss_module_data'] = array(1, 2);
		
		$data['user_data'] = array(
								 array(
		 									'user_id' => '159',
		 									'ad_profile_id' => '2'
									 ),
		 						array(
		 									'user_id' => '160',
		 									'ad_profile_id' => '0'
		 							)
		 );*/
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		if ($data['customer_id']=='')
		{
			$get_user_obj->error = TRUE;
			$get_user_obj->error_msg = INPUT_DATA_MISSING;
			$get_user_obj->session_status = TRUE;
			echo json_encode($get_user_obj);
			exit;
		}
		
		$bw_obj = new Business_superadmin();
		$profile_res = $bw_obj->save_admin_profile_details($data);
		echo json_encode($profile_res);
	}
	
	/* Delete Profile */
	public function delete_admin_profile_details()
	{
		/*$data['customer_id'] = 1;
		$data['ad_profile_id'] = 1;*/
		
		$data = $this->security->xss_clean($_POST);
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		if ($data['customer_id']=='')
		{
			$get_user_obj->error = TRUE;
			$get_user_obj->error_msg = INPUT_DATA_MISSING;
			$get_user_obj->session_status = TRUE;
			echo json_encode($get_user_obj);
			exit;
		}
		
		$bw_obj = new Business_superadmin();
		$profile_res = $bw_obj->delete_admin_profile_details($data);
		echo json_encode($profile_res);
	}
}
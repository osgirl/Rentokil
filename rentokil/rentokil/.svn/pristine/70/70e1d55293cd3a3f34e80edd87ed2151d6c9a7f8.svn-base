<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Superadmin_model extends CI_Model {
	
	function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        //$this->load->database();
    }
    
	public function get_customers()
	{
		//$user_info = $this->session->userdata('user_info');
		$this->db->select('customer_id,customer_name,status');
		$this->db->from('customers');
		$this->db->order_by("customer_name", "asc");
		$query = $this->db->get();
		$res_customers = $query->result();
		return $res_customers;
	}
	
	public function create_customer($cus_data)
	{
		$data = array(
		   'customer_name' => $cus_data['customer_name'] ,
		   'status' => 1 
		);
		$create_customer = $this->db->insert('customers', $data); 
		$customer_id = $this->db->insert_id();
		return $customer_id;
	}
	
	public function check_customer_duplicate($cus_data)
	{
		$this->db->select('COUNT(1) as cus_dup_count');
		$this->db->from('customers');
		$this->db->where('customer_name',$cus_data['customer_name']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function edit_customer($cus_data)
	{
		$data = array(
		   'customer_name' => $cus_data['customer_name'] ,
		);
		$this->db->where('customer_id', $cus_data['customer_id']);
		$create_customer = $this->db->update('customers', $data); 
		return $create_customer;
	}
	
	public function update_customer_status($cus_data)
	{
		$data = array(
		   'status' => $cus_data['status']
		);
		$this->db->where('customer_id', $cus_data['customer_id']);
		$update_customer_status = $this->db->update('customers', $data); 
		return $update_customer_status;
	}
	
	public function update_customer_admin_status($cus_data)
	{
		$data = array(
		   'status' => $cus_data['status']
		);
		$this->db->where('user_id', $cus_data['customer_admin_id']);
		$update_customer_status = $this->db->update('users', $data); 
		return $update_customer_status;
	}
	
	public function get_customer_admin($data)
	{
		$this->db->select('user_id,user_email,username,first_name,last_name,u.status,p.profile_name');
		$this->db->from('users u');
		$this->db->join('ad_profiles p', 'p.ad_profile_id = u.profile_id', 'left');
		$this->db->where('role_id', CUSTOMER_ADMIN);
		$this->db->where('u.customer_id',$data['customer_id']);
		$this->db->order_by("first_name", "asc");
		$query = $this->db->get();
		$res_get_customer_admin = $query->result();
		//echo $this->db->last_query(); exit;
		return $res_get_customer_admin;
	}
	
	public function sa_get_contracts($data)
	{
		$this->db->select('contract_id, contract_name');
		$this->db->from('contracts');
		$this->db->where('customer_id',$data['customer_id']);
		$query = $this->db->get();
		$get_contracts = $query->result();
		return $get_contracts;
	}
	
	public function sa_get_users_configure_contract($data)
	{
		$qry = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join ad_profiles p on u.profile_id = p.ad_profile_id where u.customer_id = ". $data['customer_id'] ." and u.role_id = ". $data['role_id'] ." and u.user_id NOT IN (Select user_id from cadmin_contract where contract_id = ". $data['contract_id'] .")";
		
		$query = $this->db->query($qry);
		$res_available_users = $query->result();
		
		$qry = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join ad_profiles p on u.profile_id = p.ad_profile_id, cadmin_contract cu where u.user_id = cu.user_id and u.customer_id = ". $data['customer_id'] ." and u.role_id = ". $data['role_id'] ." and cu.contract_id = ". $data['contract_id'];
		
		$query = $this->db->query($qry);
		$res_selected_users = $query->result();
		
		$res_users_info[0]->available_users = $res_available_users;
		$res_users_info[0]->selected_users = $res_selected_users;
		
		return $res_users_info;
	}
	
	public function sa_save_configure_contract($data)
	{		
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				if($value['status'] == ACTIVE)
				{
					$qry = "DELETE from cadmin_contract where contract_id = ". $data['contract_id'] ." and user_id = ". $value['user_id'];
					$qry_res = $this->db->query($qry);
					
					if($qry_res)
					{
						$insert_data = array(
								'contract_id' => $data['contract_id'],
								'user_id' => $value['user_id'],
								'cuser_id' => $data['user_id']);

						$this->db->set('cdate','NOW()', FALSE);
						$insert_res = $this->db->insert('cadmin_contract', $insert_data);
					}						
				}
				else if($value['status'] == INACTIVE)
				{
					$qry = "DELETE from cadmin_contract where contract_id = ". $data['contract_id'] ." and user_id = ". $value['user_id'];
					$qry_res = $this->db->query($qry);
				}
			}
			return TRUE;
		}
		else 
		{
			return FALSE;
		}	
	}
	
	public function check_customer_admin_duplicate($data)
	{
		$this->db->select('COUNT(1) as cus_admin_dup_count');
		$this->db->from('users');
		$this->db->where('username',$data['admin_email']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function create_customer_admin($data)
	{
		$data = array(
		    'role_id' => $data['role_id'] ,
		    'customer_id' => $data['customer_id'] ,
			'username' => $data['username'] ,
			'password' => $data['password'] ,
			'first_name' => $data['admin_fname'] ,
			'last_name' => $data['admin_lname'] ,
			'user_email' => $data['admin_email'] ,
			'status' => $data['status'],
			'chg_pwd' => $data['chg_pwd']
			);
		$this->db->set('cdate', 'NOW()', FALSE);
		$create_customer = $this->db->insert('users', $data);
		$cadmin_id = $this->db->insert_id();
		return $cadmin_id;
	}
	
	public function check_profile_name_exists($data)
	{
		$this->db->select('COUNT(*) as profile_dup_count');
		$this->db->from('ad_profiles');
		$this->db->where('profile_name',$data['profile_name']);
		$this->db->where('customer_id', $data['customer_id']);
		if(isset($data['ad_profile_id']))
		{
			$this->db->where('ad_profile_id != ', $data['ad_profile_id']);
		}
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function admin_create_profile($data)
	{			
		$profile_data = array(
		    'customer_id' => $data['customer_id'],
		    'profile_name' => $data['profile_name'],
			'status' => ACTIVE,
			'cuser_id' => $data['user_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$create_profile = $this->db->insert('ad_profiles', $profile_data);
		$profile_id = $this->db->insert_id();
		return $profile_id;
	}
	
	public function get_admin_profile_master_details($data)
	{
		$this->db->select('ad_profile_id as profile_id, profile_name');
		$this->db->from('ad_profiles');
		$this->db->where('status', ACTIVE);
		$this->db->where('customer_id', $data['customer_id']);
		$this->db->order_by("profile_name", "asc");
		
		$query = $this->db->get();
		$res_profiles = $query->result();
		
		$qry_str = "SELECT a.ad_m_mod_id as m_module_id, a.ad_m_mod_name as m_module_name, CASE WHEN (count(s.ad_m_mod_id)) > 0 THEN '1' ELSE '0' END AS hierarchy from ad_m_mod a LEFT join ad_s_mod s on a.ad_m_mod_id = s.ad_m_mod_id where a.status = ". ACTIVE ." group by a.ad_m_mod_name order by a.sequence_no";
		
		$query = $this->db->query($qry_str);
		$res_m_modules = $query->result();
		
		foreach($res_m_modules as $key => $value)
		{
			$qry_str = "SELECT a.ad_s_mod_id as s_module_id, a.ad_m_mod_id as m_module_id, a.ad_s_mod_name as s_module_name, CASE WHEN (count(s.ad_ss_mod_id)) > 0 THEN '1' ELSE '0' END AS hierarchy from ad_s_mod a LEFT join ad_ss_mod s on a.ad_s_mod_id = s.ad_s_mod_id where a.status = ". ACTIVE . " and a.ad_m_mod_id = ". $value->m_module_id ." group by a.ad_s_mod_name order by a.sequence_no";
			
			$query = $this->db->query($qry_str);
			$res_s_modules = $query->result();		
			
			$keyvalue = $value->m_module_name;
			$m_modules[]->$keyvalue = $res_s_modules;
			
			foreach($res_s_modules as $k => $v)
			{
				$ss_modules = $this->get_ss_modules_parent($v->s_module_id);

				if(count($ss_modules) > 0)
				{
					$kv = $v->s_module_name;
					$sub_modules[]->$kv = $ss_modules;
				}
			}			
		}
		
		$profile_res[0]->profiles = $res_profiles;
		$profile_res[0]->m_modules = $res_m_modules;
		
		if(isset($m_modules))
			$profile_res[0]->sub_modules = $m_modules;
		
		if(isset($sub_modules))
			$profile_res[0]->sub_sub_modules = $sub_modules;
		
		return $profile_res;
	}
	
	public function get_admin_profile_details_contract($data)
	{
		$this->db->select('profile_name, ad_profile_id as profile_id, ad_m_mod_id as m_module_id, self_reg as self_registration, create_con, status');
		$this->db->from('ad_profiles');
		$this->db->where('ad_profile_id', $data['ad_profile_id']);
		$this->db->where('customer_id', $data['customer_id']);
		
		$query = $this->db->get();
		$res_profile = $query->result();
		
		$this->db->select('ad_s_mod_id as s_module_id');
		$this->db->from('ad_profiles_s_mod');
		$this->db->where('ad_profile_id', $data['ad_profile_id']);
		
		$query = $this->db->get();
		$res_modules = $query->result();
		
		$this->db->select('ad_ss_mod_id as ss_module_id');
		$this->db->from('ad_profiles_ss_mod');
		$this->db->where('ad_profile_id', $data['ad_profile_id']);
		
		$query = $this->db->get();
		$res_ss_modules = $query->result();
	
		$qry_str = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join ad_profiles p on u.profile_id = p.ad_profile_id where u.customer_id = ". $data['customer_id'] ." and u.role_id = ". CUSTOMER_ADMIN . " and u.profile_id != ". $data['ad_profile_id'] ." order by u.first_name";
		
		$query = $this->db->query($qry_str);
		$res_avialable_users = $query->result();
		
		$qry_str = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join ad_profiles p on u.profile_id = p.ad_profile_id where u.profile_id = ". $data['ad_profile_id'] ."  and p.ad_profile_id = ". $data['ad_profile_id'] ." and  u.customer_id = ". $data['customer_id'] ." and u.role_id = ". CUSTOMER_ADMIN ." order by u.first_name";
		
		$query = $this->db->query($qry_str);
		$res_selected_users = $query->result();
		
		$profile_res[0]->profile_res = $res_profile;
		$profile_res[0]->profile_main_modules = $res_modules;
		$profile_res[0]->profile_sub_modules = $res_ss_modules;
		$profile_res[0]->available_users = $res_avialable_users;
		$profile_res[0]->selected_users = $res_selected_users;		
		
		return $profile_res;
	}
	
	public function get_ss_modules_parent($s_module_id)
	{
		$this->db->select('ad_ss_mod_id as ss_module_id, ad_s_mod_id as s_module_id, ad_p_ss_mod_id as p_ss_module_id, ad_ss_mod_name as ss_module_name');
		$this->db->from('ad_ss_mod');
		$this->db->where('status', ACTIVE);		
		$this->db->where('ad_s_mod_id', $s_module_id);
		$this->db->order_by('sequence_no', 'ASC');
				
		$query = $this->db->get();
		$res_ss_modules = $query->result();
		
		$res = array();
		if(count($res_ss_modules) > 0)
		{			
			foreach($res_ss_modules as $key => $value)
			{
				if($value->p_ss_module_id == 0)
				{
					$result = $this->get_sub_sub_modules($value->ss_module_id);
				
					if(count($result) > 0)
					{
						if($value->p_ss_module_id == 0)
						{									
							$keyvalue = $value->ss_module_name;
							$dum_array = array();
							$dum_array[0] = $value;
							$dum_array[1] = $result;							
							$res[]->$keyvalue = $dum_array;							
						}
					}
					else
					{
						$res_empty = array();
						$keyvalue = $value->ss_module_name;
						$dum_array = array();
						$dum_array[0] = $value;
						$dum_array[1] = $res_empty;			
						$res[]->$keyvalue = $dum_array;
					}
				}
			}		
		}
		return $res;
	}
	
	public function get_sub_sub_modules($module_id)
	{
		$this->db->select('ad_ss_mod_id as ss_module_id, ad_s_mod_id as s_module_id, ad_ss_mod_name as ss_module_name');
		$this->db->from('ad_ss_mod');
		$this->db->where('status', ACTIVE);
		$this->db->where('ad_p_ss_mod_id', $module_id);
		$this->db->order_by('sequence_no', 'ASC');
		
		$query = $this->db->get();
		$res_ss_modules = $query->result();
		return $res_ss_modules;
	}
	
	public function save_admin_profile_details($data)
	{		
		if($data['self_reg'] == 1)
		{
			$update_profile_data = array(
											'self_reg' => INACTIVE
										);
			$this->db->where('customer_id',$data['customer_id']);
			$this->db->update('ad_profiles',$update_profile_data);
		}
		
		$profile_data = array(
								'profile_name' => $data['profile_name'],
								'ad_m_mod_id' => $data['ad_m_mod_id'],
								'self_reg' => $data['self_reg'],
								'create_con' => $data['create_con'],
								'status' => ACTIVE,
								'cuser_id' => $data['user_id']);
		
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->where('ad_profile_id',$data['ad_profile_id']);
		$this->db->where('customer_id',$data['customer_id']);
		$this->db->update('ad_profiles',$profile_data);		
		
		$delete_query_str = "DELETE FROM ad_profiles_s_mod WHERE ad_profile_id = ". $data['ad_profile_id']; 
		$delete_query = $this->db->query($delete_query_str);
		
		if($delete_query)
		{
			if(isset($data['profile_s_module_data']))
			{
				for($i = 0; $i<count($data['profile_s_module_data']); $i++)
				{
					$sub_data = array(
								'ad_profile_id' => $data['ad_profile_id'],
								'ad_s_mod_id' => $data['profile_s_module_data'][$i],
								'cuser_id' => $data['user_id']);
					$this->db->set('cdate','NOW()', FALSE);
					$this->db->insert('ad_profiles_s_mod', $sub_data);
				}
			}
		}
		
		$delete_query_str = "DELETE FROM ad_profiles_ss_mod WHERE ad_profile_id = ". $data['ad_profile_id']; 
		$delete_query = $this->db->query($delete_query_str);
		
		if($delete_query)
		{
			if(isset($data['profile_ss_module_data']))
			{
				for($i = 0; $i<count($data['profile_ss_module_data']); $i++)
				{
					$sub_sub_data = array(
								'ad_profile_id' => $data['ad_profile_id'],
								'ad_ss_mod_id' => $data['profile_ss_module_data'][$i],
								'cuser_id' => $data['user_id']);
					$this->db->set('cdate','NOW()', FALSE);
					$this->db->insert('ad_profiles_ss_mod', $sub_sub_data);
				}
			}
		}
		
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				$user_data_arr = array(
		   					'profile_id' => $value['ad_profile_id'],
							'muser_id' => $data['user_id']
				);
				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->where('user_id', $value['user_id']);
				$this->db->where('customer_id', $data['customer_id']);
				$this->db->update('users', $user_data_arr);
			}
		}
		
		$this->db->select('ad_profile_id, profile_name');
		$this->db->from('ad_profiles');
		$this->db->where('status', ACTIVE);
		$this->db->where('customer_id', $data['customer_id']);
		$this->db->order_by("profile_name", "asc");
		
		$query = $this->db->get();
		$res_profiles = $query->result();
		
		return $res_profiles;
	}
	
	public function delete_admin_profile_details($data)
	{
		$update_data = array(
		  					'profile_id' => INACTIVE,
							'muser_id' => $data['user_id']
					);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('profile_id', $data['ad_profile_id']);
		$res_update = $this->db->update('users', $update_data);

		if($res_update)
		{
			$delete_qry = "DELETE from ad_profiles where ad_profile_id = ". $data['ad_profile_id'];
			$query_status = $this->db->query($delete_qry);
		}
		
		return $query_status;
	}
	
}

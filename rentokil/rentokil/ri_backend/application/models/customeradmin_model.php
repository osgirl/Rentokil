<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customeradmin_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		//$this->load->database();
	}
	
	public function save_contract_session($data)
	{
		$this->db->select('session_log');
		$this->db->from('users');
		$this->db->where('user_id', $data['user_id']);
		$query = $this->db->get();
		$res_users = $query->result();
		
		$this->db->select('contract_name, contract_key, session_log');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('customer_id', $data['customer_id']);
		$query = $this->db->get();
		$res_con = $query->result();
		
		$con_obj = new stdClass();
		$con_obj->contract_id = $data['contract_id'];
		$con_obj->contract_name = $res_con[0]->contract_name;
		$con_obj->contract_key = $res_con[0]->contract_key;
		$con_obj->session_log = ($res_users[0]->session_log == 1 && $res_con[0]->session_log == 1) ? 1 : 0;
		
		return $con_obj;
	}

	public function get_users($data)
	{
		//$user_info = $this->session->userdata('user_info');
		$this->db->select('user_id,username,user_email,u.status,first_name,last_name,p.profile_name');
		$this->db->from('users u');
		$this->db->join('profiles p', 'p.profile_id = u.profile_id', 'left');
		$this->db->where('u.customer_id',$data['customer_id']);
		$this->db->where('u.role_id',$data['role_id']);
		$this->db->where('u.contract_id',$data['contract_id']);
		$this->db->order_by('first_name', 'asc');
		$query = $this->db->get();
                $res_users = $query->result();
		return $res_users;
	}

	public function check_user_duplicate($cus_data)
	{
		$this->db->select('COUNT(1) as user_dup_count');
		$this->db->from('users');
		$this->db->where('username',$cus_data['user_email']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}

	public function update_user_status($cus_data)
	{
		$data = array(
		   'status' => $cus_data['status']
		);
		$this->db->where('user_id', $cus_data['user_id']);
		$this->db->where('customer_id', $cus_data['customer_id']);
		$this->db->where('role_id', $cus_data['role_id']);
		$update_user_status = $this->db->update('users', $data);
		return $update_user_status;
	}


	public function create_user($data)
	{
		$data = array(
		    'role_id' => $data['role_id'] ,
		    'customer_id' => $data['customer_id'] ,
			'username' => $data['username'] ,
			'password' => $data['password'] ,
			'first_name' => $data['user_fname'] ,
			'last_name' => $data['user_lname'] ,
			'user_email' => $data['user_email'] ,
			'status' => $data['status'] ,
			'contract_id' => $data['contract_id'],
			'chg_pwd' => $data['chg_pwd']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$create_user = $this->db->insert('users', $data);
		$user_id = $this->db->insert_id();
		return $user_id;
	}

	public function check_school_duplicate($data)
	{
		$this->db->select('COUNT(*) as school_dup_count');
		$this->db->from('schools');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('school_name',$data['school_name']);
		if(!empty($data['school_id']))
		{
			$this->db->where('school_id !=',$data['school_id']);
		}
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}

	public function create_school($school_data)
	{
		$data = array(
		    'contract_id' => $school_data['contract_id'] ,
		    'school_name' => $school_data['school_name'] ,
		//'pupils_no' => $school_data['pupils_no'] ,
			'production_status' => $school_data['production_status'] ,
			'status' => $school_data['status'] ,
			'cuser_id' => $school_data['cuser_id']
		);
		
		if(!$school_data['production_status'])
		$data['production_id'] = $school_data['production_id'];

		$this->db->set('cdate', 'NOW()', FALSE);
		$this->db->insert('schools', $data);

		$school_id = $this->db->insert_id();
		if($school_data['production_status'])
		{
			$data['production_id'] = $school_id;
			$this->db->set('production_id', $school_id);
			$this->db->where('school_id', $school_id);
			$this->db->update('schools');
		}

		//Need to insert 6 rows for school_classes
		$school_classes_data = array(
				'school_id' => $school_id,
				'year_no' => 1,
				'year_label' =>'Reception',
				'year_status' =>0,
				'class1_name' => 'Class 1',
				'class1_status' => 0,
				'class2_name' => 'Class 2',
				'class2_status' => 0,
				'class3_name' => 'Class 3',
				'class3_status' => 0,
				'class4_name' => 'Class 4',
				'class4_status' => 0,
				'class5_name' => 'Class 5',
				'class5_status' => 0,
				'class6_name' => 'Class 6',
				'class6_status' => 0,
				'cuser_id' => $school_data['cuser_id']
		);

		$this->db->set('cdate', 'NOW()', FALSE);
		$add_school_classes = $this->db->insert('school_classes', $school_classes_data);

		for($i=1; $i<7;$i++) {
			$school_classes_data = array(
					'school_id' => $school_id,
					'year_no' => ($i+1),
					'year_label' =>'Year ' .($i),
					'year_status' =>0,
					'class1_name' => 'Class 1',
					'class1_status' => 0,
					'class2_name' => 'Class 2',
					'class2_status' => 0,
					'class3_name' => 'Class 3',
					'class3_status' => 0,
					'class4_name' => 'Class 4',
					'class4_status' => 0,
					'class5_name' => 'Class 5',
					'class5_status' => 0,
					'class6_name' => 'Class 6',
					'class6_status' => 0,
					'cuser_id' => $school_data['cuser_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_school_classes = $this->db->insert('school_classes', $school_classes_data);
		}
		
		$query_str = "SELECT a.con_cater_menu_details_id, a.option_status FROM con_cater_menu_details a, con_cater_menu_settings b WHERE b.contract_id = ".$school_data['contract_id']." and a.option_status = ". ACTIVE ." and a.con_cater_menu_settings_id = b.con_cater_menu_settings_id";
		$query = $this->db->query($query_str);
		$menu_details = $query->result();
		
		foreach($menu_details as $key => $value)
		{
			$cater_menu_data = array(
					'school_id' => $school_id,
					'con_cater_menu_details_id' => $value->con_cater_menu_details_id,
					'status' => $value->option_status,
					'cuser_id' => $school_data['cuser_id']);
			
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_cater_menu_settings = $this->db->insert('sc_cater_menu_settings', $cater_menu_data);
		}		
		return TRUE;
	}

	public function edit_profile($profile_data)
	{
		$data = array(
				'title_id' => $profile_data['title_id'],
				'first_name' => $profile_data['first_name'],
				'last_name' => $profile_data['last_name'],
				'user_email' => $profile_data['user_email'],
				'telephone' => $profile_data['telephone'],
				'muser_id' => $profile_data['user_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('user_id', $profile_data['user_id']);
		$edit_profile = $this->db->update('users', $data);
		return $edit_profile;
	}

	public function check_contract_duplicate($data)
	{
		$this->db->select('COUNT(*) as contract_dup_count');
		$this->db->from('contracts');
		$this->db->where('customer_id',$data['customer_id']);
		$this->db->where('contract_name',$data['contract_name']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function check_contract_duplicate_edit($data)
	{
		$this->db->select('COUNT(*) as contract_dup_count');
		$this->db->from('contracts');
		$this->db->where('customer_id',$data['customer_id']);
		$this->db->where('contract_name',$data['contract_name']);
		$this->db->where('contract_id != ', $data['contract_id']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}

	/* Creating the contract */
	public function create_contract($data)
	{
		/*$CI =& get_instance();
		$CI->load->model('common_model');
		$menu_data_array = $CI->common_model->get_data_any(MENU_TYPE_ID);*/
		$data = array(
		    'customer_id' => $data['customer_id'] ,
		    'contract_name' => $data['contract_name'] ,
		 	'contract_key' => $data['contract_key'] ,
			'status' => $data['status'] ,
			'cuser_id' => $data['cuser_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$create_contract = $this->db->insert('contracts', $data);		//Insert query for contract

		$contract_id = $this->db->insert_id();

		/* Inserting the menus */
		$query_status = FALSE;
		if($create_contract)
		{
			/* Inserting for Contract admin access */
			$con_data = array(
								'contract_id' => $contract_id,
								'user_id' => $data['cuser_id'],
								'cuser_id' => $data['cuser_id']);
			
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('cadmin_contract', $con_data);
			
			$order_seq_data = array(
								'contract_id' => $contract_id,
								'order_seq_no' => 0,
								'payment_seq_no' => 0);
			
			$create_order_seq = $this->db->insert('order_sequence', $order_seq_data);
			
			for($i = 0; $i < CATERING_MENU_COUNT; $i++)	// Menu cycles is get from constants
			{
				$a = $i * 2;
				$menu_cycle = $i + 1;
				$menu_start_date = date('Y-m-d', strtotime('last monday -'. $a .' week'));		//generate the menu start date
				$menu_data = array(
						'contract_id' => $contract_id,
						'menu_cycles' => CATERING_DEFAULT_MENU,
						'menu_start_date' => $menu_start_date,
						'menu_sequence' => $menu_cycle,
						'menu_status' => ACTIVE,
						'cuser_id' => $data['cuser_id']);
				$this->db->set('cdate', 'NOW()', FALSE);
				$query_status = $this->db->insert('con_cater_menu_settings', $menu_data);

				$menu_settings_id = $this->db->insert_id();
				
				$menu_data_array = get_data_any(array('data_ref'=>'menu_type'));
				
				if($query_status)
				{
					foreach($menu_data_array as $key => $value)		//Menu type
					{
						$meal_type_id = $value->data_value_id;
							
						for($k=1; $k<=CATERING_MENU_CYCLES_COUNT; $k++)		//Menu Cycles
						{
							$week_cycle_id = $k;

							for($p=1; $p<=WEEK_DAYS_COUNT; $p++)	// Week Days
							{
								$week_day_id = $p;
									
								for($q=1; $q<=MENU_OPTIONS_COUNT; $q++)		//Menu Options
								{
									$option_sequence = $q;

									$option_array = array(
										'con_cater_menu_settings_id' => $menu_settings_id,
										'week_cycle' => $week_cycle_id,
										'week_day' => $week_day_id,
										'meal_type' => $meal_type_id,
										'option_sequence' => $option_sequence,
										'option_details' => '',
										'option_cost' => 0.00,
										'option_status' => INACTIVE,
										'cuser_id' => $data['cuser_id']);
										
									$this->db->set('cdate', 'NOW()', FALSE);
									$opt_query_status = $this->db->insert('con_cater_menu_details', $option_array);
								}
							}
						}
					}
				}
			}
		}
		return $contract_id;
	}
	
	/*public function get_contracts_existing_menu()
	{
		$query = $this->db->query("select DISTINCT a.contract_id from contracts a, con_cater_menu_settings b where a.contract_id <> b.contract_id");
		return $query->result();
	}*/

	/* Creating the contract */
	/*public function create_menus_exist_contracts($data)
	{
		$CI =& get_instance();
		$CI->load->model('common_model');
		$menu_data_array = $CI->common_model->get_data_any(MENU_TYPE_ID);
	
		$contracts = $this->get_contracts_existing_menu();
		
		foreach($contracts as $key => $value)
		{
			$data = array(
		   		'min_card_pay' => 5.00,
		    	'vat' => 20.00,
		 		'dc_fee' => 0.30,
				'cc_fee' => 5.00,
				'tminus' => 4
			);
		
			$this->db->where('contract_id', $value->contract_id);
			$edit_contract = $this->db->update('contracts', $data);
			
			$contract_id = $value->contract_id;
			
			if($edit_contract)
			{
				for($i = 0; $i < CATERING_MENU_COUNT; $i++)	// Menu cycles is get from constants
				{
					$a = $i * 2;
					$menu_cycle = $i + 1;
					$menu_start_date = date('Y-m-d', strtotime('last monday -'. $a .' week'));		//generate the menu start date
					$menu_data = array(
							'contract_id' => $contract_id,
							'menu_cycles' => CATERING_DEFAULT_MENU,
							'menu_start_date' => $menu_start_date,
							'menu_sequence' => $menu_cycle,
							'menu_status' => ACTIVE,
							'cuser_id' => CUSTOMER_ADMIN);
					$this->db->set('cdate', 'NOW()', FALSE);
					$query_status = $this->db->insert('con_cater_menu_settings', $menu_data);

					$menu_settings_id = $this->db->insert_id();

					if($query_status)
					{
						foreach($menu_data_array as $key => $value)		//Menu type
						{
							$meal_type_id = $value->data_value_id;
							
							for($k=1; $k<=CATERING_MENU_CYCLES_COUNT; $k++)		//Menu Cycles
							{
								$week_cycle_id = $k;

								for($p=1; $p<=WEEK_DAYS_COUNT; $p++)	// Week Days
								{
									$week_day_id = $p;
									
									for($q=1; $q<=MENU_OPTIONS_COUNT; $q++)		//Menu Options
									{
										$option_sequence = $q;
		
										$option_array = array(
											'con_cater_menu_settings_id' => $menu_settings_id,
											'week_cycle' => $week_cycle_id,
											'week_day' => $week_day_id,
											'meal_type' => $meal_type_id,
											'option_sequence' => $option_sequence,
											'option_details' => '',
											'option_cost' => 0.00,
											'option_status' => INACTIVE,
											'cuser_id' => CUSTOMER_ADMIN);
										
										$this->db->set('cdate', 'NOW()', FALSE);
										$opt_query_status = $this->db->insert('con_cater_menu_details', $option_array);
									}
								}
							}
						}
					}
				}
			}
		}
		return $edit_contract;
	}*/

	public function check_contract_key($contract_key, $customer_id)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('contracts');
		$this->db->where('customer_id',$customer_id);
		$this->db->where('contract_key',$contract_key);
		$query = $this->db->get();
		$check_key_res = $query->result();
		return $check_key_res[0]->cnt;
	}

	public function get_contracts($data)
	{
		$this->db->select('c.contract_id, contract_name, status, contract_key, session_log');
		$this->db->from('contracts c');
		$this->db->join('cadmin_contract ca', 'ca.contract_id = c.contract_id');
		$this->db->where('ca.user_id', $data['user_id']);
		$this->db->where('c.customer_id',$data['customer_id']);
		$this->db->order_by("c.contract_name", "asc");
		$query = $this->db->get();
		$res_contracts = $query->result();
		return $res_contracts;
		
		/*$this->db->select('contract_id,contract_name,status,contract_key,session_log');
		$this->db->from('contracts');
		$this->db->where('customer_id',$data['customer_id']);
		$this->db->order_by("contract_name", "asc");
		$query = $this->db->get();
		$res_contracts = $query->result();
		return $res_contracts;*/
	}

	public function get_contract_key($contract_id)
	{
		$this->db->select('contract_key');
		$this->db->from('contracts');
		$this->db->where('contract_id',$contract_id);
		$query = $this->db->get();
		$res_contract_key = $query->result();
		return $res_contract_key[0]->contract_key;
	}

	public function edit_contract($data)
	{
		/*$data = array(
		//'contract_name' => $contract_data['contract_name'],
				'min_order' => $contract_data['min_order'],
		//'status' => $contract_data['status'],
				'muser_id' => $contract_data['muser_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('contract_id', $contract_data['contract_id']);
		$this->db->where('customer_id', $contract_data['customer_id']);
		$edit_contract = $this->db->update('contracts', $data);
		return $edit_contract;*/
		
		$contract_data = array(
				'contract_name' => $data['contract_name'],
				'muser_id' => $data['user_id']);

		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('contract_id', $data['contract_id']);
		$contract_query_status = $this->db->update('contracts', $contract_data);	//Update in contracts table
		return $contract_query_status;
	}

	/*public function update_contract_offers($offer_datas)
	 {
		$this->db->delete('contract_offers', array('contract_id' => $offer_datas[0]['contract_id']));
		foreach ($offer_datas as $offer_data)
		{
		$data = array(
		'contract_id' => $offer_data['contract_id'] ,
		'spend' => $offer_data['spend'] ,
		'reward' => $offer_data['reward'] ,
		'cuser_id' => $offer_data['cuser_id'],
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$update_contract_offers = $this->db->insert('contract_offers', $data);
		}
		return $update_contract_offers;
		}*/

	public function edit_schools($school_data)
	{
		$this->db->select('production_id, production_status');
		$this->db->from('schools ');
		$this->db->where('school_id',$school_data['school_id']);
		$query = $this->db->get();		
		$res_prod_check = $query->result();
		$prv_prod_id = $res_prod_check[0]->production_status;
		
		$data = array(
				'school_name' => $school_data['school_name'],
		//'pupils_no' => $school_data['pupils_no'],
				'production_status' => $school_data['production_status'],
				'production_id' => $school_data['production_id'],
				'muser_id' => $school_data['muser_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('school_id', $school_data['school_id']);
		$this->db->where('contract_id', $school_data['contract_id']);
		$edit_school = $this->db->update('schools', $data);
		
		$update_cnt = $this->db->affected_rows();
		/* Check if the school is updated or not */
		if($prv_prod_id != $school_data['production_id'] && $update_cnt > 0)
		{			
			/* If production status is active means, delete the previous records and insert the enabled option from contract */
			if($school_data['production_status'] == ACTIVE)
			{
				/* Create a batch */
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, SCHOOL_REPLACE_STRING);
				$school_str = mysql_real_escape_string(SERVERY_PROD_CONTRACT_UPDATE_MESSAGE);
				$batch_data = array('user_id' => $school_data['muser_id'], 'school_id' => $school_data['school_id'], 'str' => $school_str, 'key_values' => $batch_key);
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				$data['user_id'] = $school_data['muser_id'];
				$batch_cancel_id = create_batch_cancel($data, SERVERY_PRODUCTION_RELATIONSHIP_DATA_ID);
				
				$delete_qry = "DELETE from sc_cater_menu_settings where school_id = ". $school_data['school_id'];
				$query_status = $this->db->query($delete_qry);
				if(query_status)
				{
					$query_str = "Insert into sc_cater_menu_settings(school_id, con_cater_menu_details_id, status, cuser_id, cdate)
									SELECT ". $school_data['school_id'] .", a.con_cater_menu_details_id, a.option_status, ". $school_data['muser_id'] .", NOW() FROM con_cater_menu_details a, con_cater_menu_settings b WHERE b.contract_id = ".$school_data['contract_id']." and a.option_status = ". ACTIVE ." and a.con_cater_menu_settings_id = b.con_cater_menu_settings_id";
					$query = $this->db->query($query_str);					
				}			
			}
			/* If production status is not active means, delete the previous records and insert the enabled option from production school */
			else
			{
				/* Create a batch */
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, SCHOOL_NAME_PREVIOUS, SCHOOL_REPLACE_STRING);
				$school_str = mysql_real_escape_string(SERVERY_PROD_UPDATE_MESSAGE);
				$batch_data = array('user_id' => $school_data['muser_id'], 'school_id' => $school_data['school_id'], 'production_id' => $school_data['production_id'], 'str' => $school_str, 'key_values' => $batch_key);
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				$data['user_id'] = $school_data['muser_id'];
				$batch_cancel_id = create_batch_cancel($data, SERVERY_PRODUCTION_RELATIONSHIP_DATA_ID);
				
				$delete_qry = "DELETE from sc_cater_menu_settings where school_id = ". $school_data['school_id'];
				$query_status = $this->db->query($delete_qry);
				if(query_status)
				{
					$query_str = "INSERT into sc_cater_menu_settings (school_id, con_cater_menu_details_id, status, cuser_id, cdate)
									SELECT ". $school_data['school_id'] .", con_cater_menu_details_id, status, ". $school_data['muser_id'] .", NOW() FROM sc_cater_menu_settings WHERE school_id = ".$school_data['production_id'];
					$query = $this->db->query($query_str);					
				}				
			}
			
			/*Update the order_edited in order_items for that school_id */
			/*$update_qry = "UPDATE order_items SET order_edited = ". ACTIVE . ", batch_cancel_id = ". $batch_cancel_id ." where school_id = ". $school_data['school_id'];
			$query_status = $this->db->query($update_qry);*/
			
			/*Update the order_edited in order_items for that school_id */
			$update_qry = "UPDATE order_items SET order_edited = ". ACTIVE . ", batch_cancel_id = ". $batch_cancel_id ." where school_id = ". $school_data['school_id'] ." and order_edited =". INACTIVE ." and order_status =". ORDER_STATUS_NEW ." and collect_status =". INACTIVE;
			$query_status = $this->db->query($update_qry);
		}
		return $edit_school;
	}

	public function school_check_customer($customer_id,$contract_id,$school_id)
	{
		$this->db->select('COUNT(*) as chk_cus_count');
		$this->db->from('customers cus');
		$this->db->join('contracts con', 'cus.customer_id = con.customer_id');
		$this->db->join('schools sch', 'con.contract_id = sch.contract_id');
		$this->db->where('cus.customer_id', $customer_id);
		$this->db->where('con.contract_id', $contract_id);
		$this->db->where('sch.school_id', $school_id);
		$query = $this->db->get();
		$school_check_customer = $query->result();
		return $school_check_customer[0];
	}

	public function update_school_status($school_data)
	{
		$data = array(
		    'status' => $school_data['status'],
			'production_status' => ACTIVE,
			'production_id' => $school_data['school_id'],
			'muser_id' => $school_data['muser_id']
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('school_id', $school_data['school_id']);
		$this->db->where('contract_id', $school_data['contract_id']);
		$update_school_status = $this->db->update('schools', $data);
		
		if($update_school_status)
		{
			if($school_data['status'] == 0)
			{
				/* Create a batch */
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, SCHOOL_REPLACE_STRING);
				$batch_data = array('user_id' => $school_data['muser_id'], 'school_id' => $school_data['school_id'], 'str' => SCHOOL_DISABLE_UPDATE_MESSAGE, 'key_values' => $batch_key);
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				$data['user_id'] = $school_data['muser_id'];
				$batch_cancel_id = create_batch_cancel($data, SCHOOL_ENABLED_DISABLED_DATA_ID);
			}
			else
			{
				/* Create a batch */
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, SCHOOL_REPLACE_STRING);
				$batch_data = array('user_id' => $school_data['muser_id'], 'school_id' => $school_data['school_id'], 'str' => SCHOOL_ENABLE_UPDATE_MESSAGE, 'key_values' => $batch_key);
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				$data['user_id'] = $school_data['muser_id'];
				$batch_cancel_id = create_batch_cancel($data, SCHOOL_ENABLED_DISABLED_DATA_ID);
			}

			/*Update the order_edited in order_items for that school_id */
			$update_qry = "UPDATE order_items SET order_edited = ". ACTIVE . ", batch_cancel_id = ". $batch_cancel_id ." where school_id = ". $school_data['school_id'] ." and order_edited =". INACTIVE ." and order_status =". ORDER_STATUS_NEW ." and collect_status =". INACTIVE;
			$query_status = $this->db->query($update_qry);
		}
		return $update_school_status;
	}

	public function get_contract_name($contract_id)
	{
		$this->db->select('contract_name');
		$this->db->from('contracts');
		$this->db->where('status',ACTIVE);
		$this->db->where('contract_id',$contract_id);
		$query = $this->db->get();
		$res_contract_name = $query->result();
		return $res_contract_name[0]->contract_name;
	}

	public function utility_hh_check($mrn, $utility, $hh_nhh, $contract_id)
	{
		$this->db->select('COUNT(*) as chk_count');
		$this->db->from('setup_data s');
		$this->db->join('hh_data hh', 's.setup_data_id = hh.setup_data_id');
		$this->db->where('s.mrn', $mrn);
		$this->db->where('s.contract_id', $contract_id);
		$this->db->where('s.active', ACTIVE);
		$this->db->where("(s.utility != '$utility' || s.hh_nhh != '$hh_nhh')");
		$query = $this->db->get();
		$utility_hh_check = $query->result();
		return $utility_hh_check[0];
	}

	public function utility_nhh_check($mrn, $utility, $hh_nhh, $contract_id)
	{
		$this->db->select('COUNT(*) as chk_count');
		$this->db->from('setup_data s');
		$this->db->join('nhh_data hh', 's.setup_data_id = hh.setup_data_id');
		$this->db->where('s.mrn', $mrn);
		$this->db->where('s.contract_id', $contract_id);
		$this->db->where('s.active', ACTIVE);
		$this->db->where("(s.utility != '$utility' || s.hh_nhh != '$hh_nhh')");
		$query = $this->db->get();
		$utility_nhh_check = $query->result();
		return $utility_nhh_check[0];
	}

	public function setup_data_dup_check($division, $sub_division, $site_name, $mrn, $utility, $hh_nhh, $contract_id)
	{
		$this->db->select('COUNT(*) as dup_count');
		$this->db->from('setup_data');
		$this->db->where('contract_id', $contract_id);
		$this->db->where('division', $division);
		$this->db->where('sub_division', $sub_division);
		$this->db->where('site_name', $site_name);
		$this->db->where('mrn', $mrn);
		$this->db->where('utility', $utility);
		$this->db->where('hh_nhh', $hh_nhh);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$setup_data_dup_check = $query->result();
		return $setup_data_dup_check[0];
	}

	public function get_HH_reports($data)
	{
		$sqlst = "SELECT t.file_id, t.file_name, t.cdate, t.upload_status, t.error_message, t.New_Records_Added,t.Existing_Records_Amended,
					CASE WHEN ISNULL(t1.last_mod_date) THEN ' ' ELSE DATE_FORMAT(t1.last_mod_date,'%d/%c/%Y %H:%i') END AS last_mod_date, 
					CASE WHEN ISNULL(t1.last_mod_by) THEN u1.username ELSE u1.username END AS last_mod_by 
				 FROM (
					SELECT 
					file_id, 
					file_name, 
					DATE_FORMAT(f.cdate,'%d/%c/%Y %H:%i') AS cdate,
					f.cdate AS actual_date,
					upload_status,
					error_message,
					COALESCE(c.co,0) AS 'New_Records_Added',
					COALESCE(d.co,0) AS 'Existing_Records_Amended',
					f.cuser_id AS fileuser	
					FROM files f
					LEFT JOIN 
					  (SELECT cfile_id, COUNT(1) co FROM hh_data WHERE active = 1
					   GROUP BY cfile_id) c ON c.cfile_id = f.file_id
					LEFT JOIN
						(SELECT mfile_id, COUNT(1) co FROM hh_data 
						 GROUP BY mfile_id) d on d.mfile_id = f.file_id
					WHERE bsdata_type ='HH' AND f.contract_id = ?
					) t
					INNER JOIN 
						(SELECT 
						f.file_id,
						CASE 
						 WHEN ISNULL(h.mdate) =1 AND ISNULL(h.cdate) =1 THEN h2.mdate 
						WHEN ISNULL(h.mdate) =0 THEN h.mdate ELSE h.cdate 
						END AS 'last_mod_date',
						CASE 
						 WHEN ISNULL(h.mdate) =1 AND ISNULL(h.cdate) =1 THEN h2.muser_id
						WHEN ISNULL(h.mdate) =0 THEN h.muser_id ELSE h.cuser_id
						END AS 'last_mod_by'
						FROM files f
						LEFT  JOIN hh_data h ON f.file_id = h.cfile_id 
						LEFT  JOIN hh_data h2 ON f.file_id = h2.mfile_id 
						WHERE bsdata_type ='HH' AND f.contract_id = ?
						ORDER BY file_id, last_mod_date desc ) t1
					ON t.file_id = t1.file_id
					LEFT  JOIN users u ON u.user_id = t1.last_mod_by
					LEFT JOIN users u1 ON u1.user_id = t.fileuser 
					GROUP BY t1.file_id
					ORDER BY t.actual_date desc";

		$query = $this->db->query($sqlst, array($data['contract_id'], $data['contract_id']));
		$res_hh_reports = $query->result();
		return $res_hh_reports;
	}


	public function get_NHH_reports($data)
	{
		$sqlst = "SELECT t.file_id, t.file_name, t.cdate, t.upload_status, t.error_message, t.New_Records_Added,t.Existing_Records_Amended,
					CASE WHEN ISNULL(t1.last_mod_date) THEN ' ' ELSE DATE_FORMAT(t1.last_mod_date,'%d/%c/%Y %H:%i') END AS last_mod_date,
					CASE WHEN ISNULL(t1.last_mod_by) THEN u1.username ELSE u1.username END AS last_mod_by
				 FROM (
					SELECT
					file_id,
					file_name,
					DATE_FORMAT(f.cdate,'%d/%c/%Y %H:%i') AS cdate,
					f.cdate AS actual_date,
					upload_status,
					error_message,
					COALESCE(c.co,0) AS 'New_Records_Added',
					COALESCE(d.co,0) AS 'Existing_Records_Amended',
					f.cuser_id AS fileuser
					FROM files f
					LEFT JOIN 
					  (SELECT cfile_id, COUNT(1) co FROM nhh_data WHERE active = 1
					   GROUP BY cfile_id) c ON c.cfile_id = f.file_id
					LEFT JOIN
						(SELECT mfile_id, COUNT(1) co FROM nhh_data 
						 GROUP BY mfile_id) d on d.mfile_id = f.file_id
					WHERE bsdata_type ='NHH' AND f.contract_id = ?
					) t
					INNER JOIN
						(SELECT
						f.file_id,
						CASE
						 WHEN ISNULL(nh.mdate) =1 AND ISNULL(nh.cdate) =1 THEN nh2.mdate
						WHEN ISNULL(nh.mdate) =0 THEN nh.mdate ELSE nh.cdate
						END as 'last_mod_date',
						CASE
						 WHEN ISNULL(nh.mdate) =1 AND ISNULL(nh.cdate) =1 THEN nh2.muser_id
						WHEN ISNULL(nh.mdate) =0 THEN nh.muser_id ELSE nh.cuser_id
						END as 'last_mod_by'
						FROM files f
						LEFT  JOIN nhh_data nh ON f.file_id = nh.cfile_id
						LEFT  JOIN nhh_data nh2 ON f.file_id = nh2.mfile_id
						WHERE bsdata_type ='NHH' AND f.contract_id = ?
						ORDER BY file_id, last_mod_date desc ) t1
					ON t.file_id = t1.file_id
					LEFT  JOIN users u ON u.user_id = t1.last_mod_by
					LEFT JOIN users u1 ON u1.user_id = t.fileuser
					GROUP BY t1.file_id
					ORDER BY t.actual_date desc";

		$query = $this->db->query($sqlst, array($data['contract_id'], $data['contract_id']));
		$res_nhh_reports = $query->result();
		return $res_nhh_reports;
	}

	public function get_target_data($data)
	{
		$sqlst = "SELECT t.file_id, t.file_name, t.cdate, t.upload_status, t.error_message, t.New_Records_Added,t.Existing_Records_Amended,
					CASE WHEN ISNULL(t1.last_mod_date) THEN ' ' ELSE DATE_FORMAT(t1.last_mod_date,'%d/%c/%Y %H:%i') END AS last_mod_date,
					CASE WHEN ISNULL(t1.last_mod_by) THEN u1.username ELSE u1.username END AS last_mod_by
				 FROM (
					SELECT
					file_id,
					file_name,
					DATE_FORMAT(f.cdate,'%d/%c/%Y %H:%i') AS cdate,
					f.cdate AS actual_date,
					upload_status,
					error_message,
					COALESCE(c.co,0) AS 'New_Records_Added',
					COALESCE(d.co,0) AS 'Existing_Records_Amended',
					f.cuser_id AS fileuser
					FROM files f
					LEFT JOIN 
					  (SELECT cfile_id, COUNT(1) co FROM target_data WHERE active = 1
					   GROUP BY cfile_id) c ON c.cfile_id = f.file_id
					LEFT JOIN
						(SELECT mfile_id, COUNT(1) co FROM target_data 
						 GROUP BY mfile_id) d on d.mfile_id = f.file_id
					WHERE bsdata_type ='target' AND f.contract_id = ?
					) t
					INNER JOIN
						(SELECT
						f.file_id,
						CASE
						 WHEN ISNULL(h.mdate) =1 AND ISNULL(h.cdate) =1 THEN h2.mdate
						WHEN ISNULL(h.mdate) =0 THEN h.mdate ELSE h.cdate
						END AS 'last_mod_date',
						CASE
						 WHEN ISNULL(h.mdate) =1 AND ISNULL(h.cdate) =1 THEN h2.muser_id
						WHEN ISNULL(h.mdate) =0 THEN h.muser_id ELSE h.cuser_id
						END AS 'last_mod_by'
						FROM files f
						LEFT  JOIN target_data h ON f.file_id = h.cfile_id
						LEFT  JOIN target_data h2 ON f.file_id = h2.mfile_id
						WHERE bsdata_type ='target' AND f.contract_id = ?
						ORDER BY file_id, last_mod_date desc ) t1
					ON t.file_id = t1.file_id
					LEFT  JOIN users u ON u.user_id = t1.last_mod_by
					LEFT JOIN users u1 ON u1.user_id = t.fileuser
					GROUP BY t1.file_id
					ORDER BY t.actual_date desc";

		$query = $this->db->query($sqlst, array($data['contract_id'], $data['contract_id']));
		$res_target_data = $query->result();
		return $res_target_data;
	}

	function mark_file_upload($file_data)
	{
		$data = array(
		    'contract_id' => $file_data['contract_id'] ,
		    'file_name' => $file_data['file_name'] ,
			'bsdata_type' => $file_data['import_type'] ,
			'upload_status' => $file_data['upload_status'],
			'error_message' => $file_data['error_msg'],
			'file_path' => $file_data['upload_file_path'],
			'cuser_id' => $file_data['cuser_id'],
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$this->db->insert('files', $data);
		return $this->db->insert_id();
	}

	function import_setup_data($setup_data,$contract_id,$file_id,$user_id)
	{
		foreach ($setup_data as $file_data)
		{
			$mrn = $file_data['E'];
			$data = array(
			    'contract_id' => $contract_id ,
			    'division' => $file_data['B'] ,
				'sub_division' => $file_data['C'] ,
				'site_name' => $file_data['D'],
				'mrn' => $file_data['E'],
				'utility' => $file_data['F'],
				'hh_nhh' => $file_data['G'],
				'active' => ACTIVE,
			);

			$this->db->select('COUNT(*) as cnt');
			$this->db->from('setup_data');
			$this->db->where('mrn', $mrn);
			$this->db->where('contract_id', $contract_id);
			$this->db->where('active', ACTIVE);
			$query = $this->db->get();
			$setup_chk = $query->result();

			if($setup_chk[0]->cnt > 0)
			{
				$this->db->select('cuser_id, cfile_id, cdate');
				$this->db->from('setup_data');
				$this->db->where('mrn', $mrn);
				$this->db->where('contract_id', $contract_id);
				$this->db->where('active', ACTIVE);
				$query = $this->db->get();
				$setup_cur_res = $query->result();

				$this->db->set('active', INACTIVE);
				$this->db->where('mrn', $mrn);
				$this->db->where('contract_id', $contract_id);
				$this->db->where('active', ACTIVE);
				$this->db->update('setup_data');

				$data['cfile_id'] = $setup_cur_res[0]->cfile_id;
				$data['cuser_id'] = $setup_cur_res[0]->cuser_id;
				$data['cdate'] = $setup_cur_res[0]->cdate;
				$data['mfile_id'] = $file_id;
				$data['muser_id'] = $user_id;
				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->insert('setup_data', $data);
			}
			else
			{
				$data['cfile_id'] = $file_id;
				$data['cuser_id'] = $user_id;
				$this->db->set('cdate', 'NOW()', FALSE);
				$this->db->insert('setup_data', $data);
			}
		}
	}

	public function import_target_data($target_data,$contract_id,$file_id,$user_id)
	{
		foreach ($target_data as $file_data)
		{
			$mrn = $file_data['E'];
			$this->db->select('setup_data_id');
			$this->db->from('setup_data');
			$this->db->where('contract_id', $contract_id);
			$this->db->where('mrn', $mrn);
			$this->db->where('active', ACTIVE);
			$query = $this->db->get();
			$target_res = $query->result();
			$setup_data_id = $target_res[0]->setup_data_id;
			$data = array(
			    'setup_data_id' => $setup_data_id ,
			    'baseline_year' => $file_data['G'] ,
				'baseline_month' => $file_data['H'] ,
				'kwh_per_month' => $file_data['I'],
				'epc' => $file_data['J'],
				'avg_per_kwh' => $file_data['K']
			);

			$this->db->select('COUNT(*) as cnt');
			$this->db->from('target_data td');
			$this->db->join('setup_data s', 's.setup_data_id = td.setup_data_id');
			$this->db->where('s.contract_id', $contract_id);
			$this->db->where('s.mrn', $mrn);
			$this->db->where('td.baseline_year', $file_data['G']);
			$this->db->where('td.baseline_month', $file_data['H']);
			$this->db->where('td.active', ACTIVE);
			$query = $this->db->get();
			$target_chk = $query->result();
			if($target_chk[0]->cnt > 0)
			{
				$this->db->select('td.target_data_id, td.cfile_id, td.cuser_id, td.cdate');
				$this->db->from('target_data td');
				$this->db->join('setup_data s', 's.setup_data_id = td.setup_data_id');
				$this->db->where('s.contract_id', $contract_id);
				$this->db->where('s.mrn', $mrn);
				$this->db->where('td.baseline_year', $file_data['G']);
				$this->db->where('td.baseline_month', $file_data['H']);
				$this->db->where('td.active', ACTIVE);
				$query = $this->db->get();
				$target_data_id_chk = $query->result();
				$this->db->set('active', INACTIVE);
				$this->db->where('target_data_id', $target_data_id_chk[0]->target_data_id);
				$this->db->update('target_data');
				$data['cfile_id'] = $target_data_id_chk[0]->cfile_id;
				$data['cuser_id'] = $target_data_id_chk[0]->cuser_id;
				$data['cdate']    = $target_data_id_chk[0]->cdate;
				$data['mfile_id'] = $file_id;
				$data['muser_id'] = $user_id;
				$data['active'] = ACTIVE;
				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->insert('target_data', $data);
			}
			else
			{
				$data['cfile_id'] = $file_id;
				$data['cuser_id'] = $user_id;
				$data['active'] = ACTIVE;
				$this->db->set('cdate', 'NOW()', FALSE);
				$this->db->insert('target_data', $data);
			}
		}
	}

	public function get_setup_entities($data)
	{
		$sqlst = "SELECT   t.file_id, t.file_name, t.cdate, t.upload_status, t.error_message, t.New_Records_Added,t.Existing_Records_Amended,
					CASE WHEN ISNULL(t1.last_mod_date) THEN ' ' ELSE DATE_FORMAT(t1.last_mod_date,'%d/%c/%Y %H:%i') END AS last_mod_date, 
					CASE WHEN ISNULL(t1.last_mod_by) THEN u1.username ELSE u1.username END AS last_mod_by 
				 FROM (
					SELECT  
					file_id,
					file_name,
					DATE_FORMAT(f.cdate,'%d/%c/%Y %H:%i') AS cdate,
					f.cdate AS actual_date,
					upload_status,
					error_message,
					COALESCE(c.co,0) AS 'New_Records_Added',
					COALESCE(d.co,0) AS 'Existing_Records_Amended',
					f.cuser_id AS fileuser				
					FROM files f
					LEFT JOIN 
					  (SELECT cfile_id, COUNT(1) co FROM setup_data WHERE active = 1
					   GROUP BY cfile_id) c ON c.cfile_id = f.file_id
					LEFT JOIN
						(SELECT mfile_id, COUNT(1) co FROM setup_data 
						 GROUP BY mfile_id) d on d.mfile_id = f.file_id
					WHERE bsdata_type ='setup' AND f.contract_id = ?
					) t
					INNER JOIN
						(SELECT 
						f.file_id,
						CASE
						 WHEN ISNULL(s1.mdate) =1 AND ISNULL(s1.cdate) =1 THEN s2.mdate
						WHEN ISNULL(s1.mdate) =0 THEN s1.mdate ELSE s1.cdate
						END AS 'last_mod_date',
						CASE
						 WHEN ISNULL(s1.mdate) =1 AND ISNULL(s1.cdate) =1 THEN s2.muser_id
						WHEN ISNULL(s1.mdate) =0 THEN s1.muser_id ELSE s1.cuser_id
						END AS 'last_mod_by'
						FROM files f
						LEFT  join setup_data s1 ON f.file_id = s1.cfile_id
						LEFT  join setup_data s2 ON f.file_id = s2.mfile_id
						WHERE bsdata_type ='setup' AND f.contract_id = ?
						ORDER BY file_id, last_mod_date desc ) t1
					ON t.file_id = t1.file_id
					LEFT JOIN users u ON u.user_id = t1.last_mod_by
					LEFT JOIN users u1 ON u1.user_id = t.fileuser 
					GROUP BY t1.file_id
					ORDER BY t.actual_date desc";

		//echo $sqlst;
		$query = $this->db->query($sqlst, array($data['contract_id'], $data['contract_id']));
		$res_setup_entities = $query->result();
		return $res_setup_entities;
	}

	public function get_data_history($data) {
		$form_type =$data['form_type'];
			
		if ($form_type == "hh")
		$sqlst = "SELECT h.muser_id, u.username AS Modified_user,
					DATE_FORMAT(h.mdate, '%d/%c/%Y %H:%i') AS modified_date,
					COUNT(1) AS Records_ammended
					FROM hh_data h
					INNER JOIN users u on h.muser_id = u.user_id
					WHERE cfile_id = ".$data['file_id']." AND mfile_id IS NOT NULL
					GROUP BY  h.muser_id, h.mdate";

		else if ($form_type == "nhh")
		$sqlst = "SELECT h.muser_id, u.username AS Modified_user,
					DATE_FORMAT(h.mdate, '%d/%c/%Y %H:%i') AS modified_date,
					COUNT(1) AS Records_ammended
					FROM nhh_data h
					INNER JOIN users u on h.muser_id = u.user_id
					WHERE cfile_id = ".$data['file_id']." AND mfile_id IS NOT NULL
					GROUP BY  h.muser_id, h.mdate";
		else if ($form_type == "target")
		$sqlst = "SELECT h.muser_id, u.username AS Modified_user,
					DATE_FORMAT(h.mdate, '%d/%c/%Y %H:%i') AS modified_date,
					COUNT(1) AS Records_ammended
					FROM target_data h
					INNER JOIN users u on h.muser_id = u.user_id
					WHERE cfile_id = ".$data['file_id']." AND mfile_id IS NOT NULL
					GROUP BY  h.muser_id, h.mdate";
		else if($form_type == "setup")
		$sqlst = "SELECT h.muser_id, u.username AS Modified_user,
					DATE_FORMAT(h.mdate, '%d/%c/%Y %H:%i') AS modified_date,
					COUNT(1) AS Records_ammended
					FROM setup_data h
					INNER JOIN users u on h.muser_id = u.user_id
					WHERE cfile_id = ".$data['file_id']." AND mfile_id IS NOT NULL
					GROUP BY  h.muser_id, h.mdate";

		//echo $sqlst;
		$query = $this->db->query($sqlst);
		$history_data_obj = $query->result();
		return $history_data_obj;
	}

	public function global_setup_check($division, $sub_division, $site_name, $mrn, $utility, $contract_id, $import_type)
	{
		$this->db->select('COUNT(*) as chk_cnt');
		$this->db->from('setup_data');
		$this->db->where('contract_id', $contract_id);
		$this->db->where('division', $division);
		$this->db->where('sub_division', $sub_division);
		$this->db->where('site_name', $site_name);
		$this->db->where('mrn', $mrn);
		$this->db->where('active', ACTIVE);
		if ($import_type != 'target') {
			$this->db->where('hh_nhh', $import_type);
		}
		if ($import_type == 'target') {
			$this->db->where('utility', $utility);
		}
		$query = $this->db->get();
		$global_setup_check = $query->result();
		return $global_setup_check[0];
	}

	public function hh_data_dup_check($hh_data, $mrn, $contract_id)
	{
		$this->db->select('COUNT(*) as chk_cnt');
		$this->db->from('hh_data hh');
		$this->db->join('setup_data s', 's.setup_data_id = hh.setup_data_id');
		$this->db->where('s.contract_id', $contract_id);
		$this->db->where('s.mrn', $mrn);
		$this->db->where('hh.active', ACTIVE);
		$this->db->where($hh_data);
		$query = $this->db->get();
		$nhh_data_dup_check = $query->result();
		return $nhh_data_dup_check[0];
	}

	public function target_data_dup_check($target_data, $mrn, $contract_id)
	{
		$this->db->select('COUNT(*) as chk_cnt');
		$this->db->from('target_data td');
		$this->db->join('setup_data s', 's.setup_data_id = td.setup_data_id');
		$this->db->where('s.contract_id', $contract_id);
		$this->db->where('s.mrn', $mrn);
		$this->db->where('td.active', ACTIVE);
		$this->db->where($target_data);
		$query = $this->db->get();
		$target_setup_check = $query->result();
		return $target_setup_check[0];
	}

	function import_hh_data($hh_data,$file_id,$user_id,$contract_id,$import_type)
	{
		foreach ($hh_data as $file_data)
		{
			$mrn = $file_data['E'];
			$hh_date = $file_data['F'];
			unset($file_data['E']);

			$this->db->select('setup_data_id');
			$this->db->from('setup_data');
			$this->db->where('contract_id', $contract_id);
			$this->db->where('mrn', $mrn);
			$this->db->where('hh_nhh', $import_type);
			$this->db->where('active', ACTIVE);
			$query = $this->db->get();
			$hh_setup_res = $query->result();
			$setup_data_id = $hh_setup_res[0]->setup_data_id;

			$this->db->select('COUNT(*) as cnt');
			$this->db->from('hh_data');
			$this->db->where('setup_data_id', $setup_data_id);
			$this->db->where('hh_date', $hh_date);
			$this->db->where('active', ACTIVE);
			$query = $this->db->get();
			$hh_chk_res = $query->result();
			//echo $this->db->last_query();
			//exit;
			unset($data);
			$data = array_combine(unserialize(HH_DB_COLS), $file_data);
			if($hh_chk_res[0]->cnt > 0)
			{
				$this->db->select('cfile_id,cdate,cuser_id');
				$this->db->from('hh_data');
				$this->db->where('setup_data_id', $setup_data_id);
				$this->db->where('hh_date', $hh_date);
				$this->db->where('active', ACTIVE);
				$query = $this->db->get();
				$hh_cur_res = $query->result();

				$this->db->set('active', INACTIVE);
				$this->db->where('setup_data_id', $setup_data_id);
				$this->db->where('hh_date', $hh_date);
				$this->db->where('active', ACTIVE);
				$this->db->update('hh_data');

				$data['setup_data_id'] = $setup_data_id;
				$data['cfile_id'] = $hh_cur_res[0]->cfile_id;
				$data['cdate'] = $hh_cur_res[0]->cdate;
				$data['cuser_id'] = $hh_cur_res[0]->cuser_id;
				$data['muser_id'] = $user_id;
				$data['mfile_id'] = $file_id;
				$data['active'] = ACTIVE;

				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->insert('hh_data', $data);

				// hh stats table drop/insert
				$this->db->where('setup_data_id', $setup_data_id);
				$this->db->where('stats_date', $hh_date);
				$this->db->delete('hh_stats_data');

				$hh_stats_data = array_combine(unserialize(HH_STATS_DB_COLS), $file_data);
				$hh_stats_data['setup_data_id'] = $setup_data_id;
				$this->db->insert('hh_stats_data', $hh_stats_data);

				// daily stats table drop/insert
				$this->db->where('setup_data_id', $setup_data_id);
				$this->db->where('stats_date', $hh_date);
				$this->db->delete('daily_stats_data');

				$daily_stats_data['setup_data_id'] = $setup_data_id;
				$daily_stats_data['stats_date'] = $hh_date;
				unset($file_data['F']);
				$daily_stats_data['kwh'] = array_sum($file_data);

				$this->db->insert('daily_stats_data', $daily_stats_data);
			}
			else
			{
				$data['setup_data_id'] = $setup_data_id;
				$data['cuser_id'] = $user_id;
				$data['cfile_id'] = $file_id;
				$data['active'] = ACTIVE;

				$this->db->set('cdate', 'NOW()', FALSE);
				$this->db->insert('hh_data', $data);

				// hh stats table insert
				$hh_stats_data = array_combine(unserialize(HH_STATS_DB_COLS), $file_data);
				$hh_stats_data['setup_data_id'] = $setup_data_id;
				$this->db->insert('hh_stats_data', $hh_stats_data);

				// daily stats table insert
				$daily_stats_data['setup_data_id'] = $setup_data_id;
				$daily_stats_data['stats_date'] = $hh_date;
				unset($file_data['F']);
				$daily_stats_data['kwh'] = array_sum($file_data);

				$this->db->insert('daily_stats_data', $daily_stats_data);
			}
		}
	}

	public function nhh_data_dup_check($mrn, $from_date, $to_date, $kwh, $contract_id)
	{
		$this->db->select('COUNT(*) as chk_cnt');
		$this->db->from('nhh_data nhh');
		$this->db->join('setup_data s', 's.setup_data_id = nhh.setup_data_id');
		$this->db->where('s.contract_id', $contract_id);
		$this->db->where('s.mrn', $mrn);
		$this->db->where('nhh.active', ACTIVE);
		$this->db->where('nhh.from_date', $from_date);
		$this->db->where('nhh.to_date', $to_date);
		$this->db->where('nhh.kwh_used', $kwh);
		$query = $this->db->get();
		$nhh_data_dup_check = $query->result();
		return $nhh_data_dup_check[0];
	}

	function import_nhh_data($nhh_data,$file_id,$user_id,$contract_id,$import_type)
	{
		foreach ($nhh_data as $file_data)
		{
			$mrn = $file_data['E'];
			$from_date = $file_data['F'];
			$to_date = $file_data['G'];
			$kwh_used = $file_data['H'];

			$this->db->select('setup_data_id');
			$this->db->from('setup_data');
			$this->db->where('contract_id', $contract_id);
			$this->db->where('mrn', $mrn);
			$this->db->where('hh_nhh', $import_type);
			$this->db->where('active', ACTIVE);
			$query = $this->db->get();
			$hh_setup_res = $query->result();
			$setup_data_id = $hh_setup_res[0]->setup_data_id;

			/*$sql = "SELECT COUNT(*) as cnt
			 FROM nhh_data
			 WHERE from_date >= ?
			 AND to_date <= ?
			 AND setup_data_id = ?
			 AND active = ?";*/
			$sql = "SELECT COUNT(*) as cnt
					FROM nhh_data
					WHERE (
					(
					from_date >= ? && from_date <= ?
					)
					OR (
					to_date >= ? && to_date <= ?
					)
					OR (
					?
					BETWEEN from_date
					AND to_date
					OR ?
					BETWEEN from_date
					AND to_date
					)
					)
					AND setup_data_id = ?
					AND active = ?";
			$query = $this->db->query($sql, array($from_date,$to_date,$from_date,$to_date,$from_date,$to_date,$setup_data_id,ACTIVE));
			$hh_chk_res = $query->result();
			unset($data);
			if($hh_chk_res[0]->cnt > 0)
			{
				/*$this->db->select('cfile_id,cdate,cuser_id');
				 $this->db->from('nhh_data');
				 $this->db->where('setup_data_id', $setup_data_id);
				 $this->db->where('from_date >=', $from_date);
				 $this->db->where('to_date <=', $to_date);
				 $this->db->where('active', ACTIVE);
				 $this->db->order_by('cdate', 'ASC');
				 $this->db->limit(1);
				 $query = $this->db->get();*/
				$sql = "SELECT cfile_id,cdate,cuser_id
						FROM nhh_data
						WHERE (
						(
						from_date >= ? && from_date <= ?
						)
						OR (
						to_date >= ? && to_date <= ?
						)
						OR (
						?
						BETWEEN from_date
						AND to_date
						OR ?
						BETWEEN from_date
						AND to_date
						)
						)
						AND setup_data_id = ?
						AND active = ?
						ORDER BY cdate ASC
						LIMIT 1";
				$query = $this->db->query($sql, array($from_date,$to_date,$from_date,$to_date,$from_date,$to_date,$setup_data_id,ACTIVE));
				$nhh_cur_res = $query->result();

				/*$this->db->set('active', INACTIVE);
				 $this->db->where('setup_data_id', $setup_data_id);
				 $this->db->where('from_date >=', $from_date);
				 $this->db->where('to_date <=', $to_date);
				 $this->db->where('active', ACTIVE);
				 $this->db->update('nhh_data');*/
				$sql = "UPDATE nhh_data
						SET
						active = ?
						WHERE (
						(
						from_date >= ? && from_date <= ?
						)
						OR (
						to_date >= ? && to_date <= ?
						)
						OR (
						?
						BETWEEN from_date
						AND to_date
						OR ?
						BETWEEN from_date
						AND to_date
						)
						)
						AND setup_data_id = ?
						AND active = ?
						";
				$query = $this->db->query($sql, array(INACTIVE,$from_date,$to_date,$from_date,$to_date,$from_date,$to_date,$setup_data_id,ACTIVE));


				$data['setup_data_id'] = $setup_data_id;
				$data['from_date'] = $from_date;
				$data['to_date'] = $to_date;
				$data['kwh_used'] = $kwh_used;
				$data['cfile_id'] = $nhh_cur_res[0]->cfile_id;
				$data['cdate'] = $nhh_cur_res[0]->cdate;
				$data['cuser_id'] = $nhh_cur_res[0]->cuser_id;
				$data['muser_id'] = $user_id;
				$data['mfile_id'] = $file_id;
				$data['active'] = ACTIVE;

				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->insert('nhh_data', $data);

				//$date1 = new DateTime("2007-03-26");
				//$date2 = new DateTime("2007-03-29");
				//$interval = $date1->diff($date2); print_r($interval); exit;
				$days_no = intval((abs(strtotime($to_date) - strtotime($from_date)))/86400) + 1;		// 86400 is number of seconds in a day
				$hh_stats_kwh = ($kwh_used/$days_no)/48;	// 48 is for half hourly calculation for a day

				// hh stats table drop/insert
				$this->db->where('setup_data_id', $setup_data_id);
				$this->db->where('stats_date >=', $from_date);
				$this->db->where('stats_date <=', $to_date);
				$this->db->delete('hh_stats_data');

				$hh_stats_date = $from_date;
				for($i=0; $i<$days_no; $i++)
				{
					unset($insert_data);
					if($i > 0)
					{
						$date = new DateTime($hh_stats_date);
						$date->add(new DateInterval('P1D'));
						$hh_stats_date = $date->format('Y-m-d');
					}
					$hh_stats_col_cnt = count(unserialize(HH_STATS_DB_COLS));
					$insert_data[] = $hh_stats_date;
					for($j=1; $j<$hh_stats_col_cnt; $j++)
					{
						$insert_data[] = $hh_stats_kwh;
					}
					$hh_stats_data = array_combine(unserialize(HH_STATS_DB_COLS), $insert_data);
					$hh_stats_data['setup_data_id'] = $setup_data_id;
					$this->db->insert('hh_stats_data', $hh_stats_data);
				}

				$daily_stats_kwh = $kwh_used/$days_no;

				// daily stats table drop/insert
				$this->db->where('setup_data_id', $setup_data_id);
				$this->db->where('stats_date >=', $from_date);
				$this->db->where('stats_date <=', $to_date);
				$this->db->delete('daily_stats_data');

				$daily_stats_date = $from_date;
				for($i=0; $i<$days_no; $i++)
				{
					if($i > 0)
					{
						$date = new DateTime($daily_stats_date);
						$date->add(new DateInterval('P1D'));
						$daily_stats_date = $date->format('Y-m-d');
					}
					$daily_stats_data['setup_data_id'] = $setup_data_id;
					$daily_stats_data['stats_date'] = $daily_stats_date;
					$daily_stats_data['kwh'] = $daily_stats_kwh;

					$this->db->insert('daily_stats_data', $daily_stats_data);
				}
			}
			else
			{
				$data['setup_data_id'] = $setup_data_id;
				$data['from_date'] = $from_date;
				$data['to_date'] = $to_date;
				$data['kwh_used'] = $kwh_used;
				$data['cuser_id'] = $user_id;
				$data['cfile_id'] = $file_id;
				$data['active'] = ACTIVE;

				$this->db->set('cdate', 'NOW()', FALSE);
				$this->db->insert('nhh_data', $data);

				$days_no = intval((abs(strtotime($to_date) - strtotime($from_date)))/86400) + 1;		// 86400 is number of seconds in a day
				$hh_stats_kwh = ($kwh_used/$days_no)/48;	// 48 is for half hourly calculation for a day

				// hh stats table insert
				$hh_stats_date = $from_date;
				for($i=0; $i<$days_no; $i++)
				{
					unset($insert_data);
					if($i > 0)
					{
						$date = new DateTime($hh_stats_date);
						$date->add(new DateInterval('P1D'));
						$hh_stats_date = $date->format('Y-m-d');
					}
					$hh_stats_col_cnt = count(unserialize(HH_STATS_DB_COLS));
					$insert_data[] = $hh_stats_date;
					for($j=1; $j<$hh_stats_col_cnt; $j++)
					{
						$insert_data[] = $hh_stats_kwh;
					}
					$hh_stats_data = array_combine(unserialize(HH_STATS_DB_COLS), $insert_data);
					$hh_stats_data['setup_data_id'] = $setup_data_id;
					//$this->db->delete('hh_stats_data', array('stats_date' => $hh_stats_date)); // Added to avoid multiple insert
					$this->db->insert('hh_stats_data', $hh_stats_data);
				}

				$daily_stats_kwh = $kwh_used/$days_no;

				// daily stats table insert
				$daily_stats_date = $from_date;
				for($i=0; $i<$days_no; $i++)
				{
					if($i > 0)
					{
						$date = new DateTime($daily_stats_date);
						$date->add(new DateInterval('P1D'));
						$daily_stats_date = $date->format('Y-m-d');
					}
					$daily_stats_data['setup_data_id'] = $setup_data_id;
					$daily_stats_data['stats_date'] = $daily_stats_date;
					$daily_stats_data['kwh'] = $daily_stats_kwh;
					//$this->db->delete('daily_stats_data', array('stats_date' => $daily_stats_date)); // Added to avoid multiple insert
					$this->db->insert('daily_stats_data', $daily_stats_data);
				}
			}
		}
	}

	public function get_pupil_import($data)
	{
		$sqlst = "SELECT file_id, file_name, DATE_FORMAT(f.cdate,'%d/%c/%Y %H:%i') AS cdate, u.username as importedby, upload_status, error_message, COALESCE(c.co,0) AS 'New_Records_Added', COALESCE(d.co,0) AS 'Existing_Records_Amended'FROM files f
				LEFT JOIN (SELECT cfile_id, COUNT(1) co FROM students WHERE active = 1 GROUP BY cfile_id) c ON c.cfile_id = f.file_id 
				LEFT JOIN (SELECT mfile_id, COUNT(1) co FROM students GROUP BY mfile_id) d on d.mfile_id = f.file_id 
				INNER JOIN users u on f.cuser_id = u.user_id
				WHERE bsdata_type ='pupils' AND upload_status =1 AND f.contract_id =? ORDER BY f.cdate DESC";
		
		$query = $this->db->query($sqlst, array($data['contract_id']));
		$res_pupil_import = $query->result();
		return $res_pupil_import;
	}

	public function check_school_year_class($school, $year, $class, $contract_id)
	{
		$sql = "SELECT COUNT(*) as cnt
				FROM `school_classes` sc
				JOIN `schools` s ON sc.school_id = s.school_id
				WHERE `year_label` = ?
				AND year_status = ? 	
				AND s.school_name = ?
				AND s.contract_id = ?
				AND s.status = ?
				AND (
				(
				`class1_name` = ? && class1_status = ?
				) || ( `class2_name` = ? && class2_status = ? ) || ( `class3_name` = ? && class3_status = ? ) || ( `class4_name` = ? && class4_status = ? ) || ( `class5_name` = ? && class5_status = ? ) || ( `class6_name` = ? && class6_status = ? )
				)";
		$query = $this->db->query($sql, array($year,ACTIVE,$school,$contract_id,ACTIVE,$class,ACTIVE,$class,ACTIVE,$class,ACTIVE,$class,ACTIVE,$class,ACTIVE,$class,ACTIVE));
		$check_syc_res = $query->result();
		//echo $this->db->last_query(); exit;
		return $check_syc_res[0]->cnt;
	}

	public function pupils_dup_check_nopupilid($data, $contract_id)
	{
		/*$sql = "SELECT fsm,st.status,adult,pupil_id
		 FROM `school_classes` sc
		 JOIN students st ON st.`school_classes_id` = sc.`school_classes_id`
		 JOIN schools s ON sc.`school_id` = s.`school_id`
		 WHERE st.fname = ?
		 AND st.mname = ?
		 AND st.lname = ?
		 AND st.pupil_dup = ?
		 AND st.active = ?
		 AND s.school_name = ?
		 AND s.contract_id = ?
		 AND s.status = ?
		 AND year_status = ?
		 AND ?
		 IN (
		 `year_label`
		 )
		 AND (
		 (
		 `class1_name` = ? && class1_status = ?
		 ) || ( `class2_name` = ? && class2_status = ? ) || ( `class3_name` = ? && class3_status = ? ) || ( `class4_name` = ? && class4_status = ? ) || ( `class5_name` = ? && class5_status = ? ) || ( `class6_name` = ? && class6_status = ? )
		 )";*/
		$sql = "SELECT COUNT(1) as cnt
				FROM students st
				JOIN school_classes sc ON st.`school_classes_id` = sc.`school_classes_id`
				JOIN schools s ON sc.`school_id` = s.`school_id`
				WHERE st.fname = ?
				AND st.mname = ?
				AND st.lname = ?
				AND st.pupil_dup = ?
				AND st.active = ?
				AND s.school_name = ?
				AND s.contract_id = ?
				AND s.status = ?
				";
		$query = $this->db->query($sql, array($data['B'],$data['C'],$data['D'],$data['H'],ACTIVE,$data['A'],$contract_id,ACTIVE));
		$pupils_dup_check_nopupilid = $query->result();
		return $pupils_dup_check_nopupilid[0]->cnt;
	}

	public function pupils_dup_check_pupilid($status, $fsm, $adult, $pupil_id)
	{
		$sql = "SELECT COUNT(1) as cnt
				FROM students
				WHERE fsm = ?
				AND adult = ?
				AND status = ?
				AND active = ?
				AND pupil_id = ?
				";
		$query = $this->db->query($sql, array($fsm, $adult, $status, ACTIVE, $pupil_id));
		$pupils_dup_check_pupilid = $query->result();
		return $pupils_dup_check_pupilid[0]->cnt;
	}
	
	public function get_pupil_records($pupil_id)
	{
		$this->db->select('fsm, adult, status, sc.school_id');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'sc.school_classes_id = st.school_classes_id');
		$this->db->where('pupil_id', $pupil_id);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$pupil_res = $query->result();
		return $pupil_res[0];
	}

	public function check_pupil_id($contract_key, $contract_id, $data)
	{
		$sql = "SELECT COUNT(*) as cnt
				FROM students st
				JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id
				JOIN schools s ON s.school_id = sc.school_id
				JOIN contracts c ON s.contract_id = c.contract_id
				WHERE c.contract_key = ?
				AND c.contract_id = ?
				AND st.pupil_id = ?
				AND s.school_name = ?
				AND s.status = ?";
		$query = $query = $this->db->query($sql, array($contract_key, $contract_id, $data['K'], $data['A'], ACTIVE));
		$pupils_data_dup_check = $query->result();
		return $pupils_data_dup_check[0]->cnt;
	}

	function import_pupils_data($pupils_data,$file_id,$user_id,$contract_id)
	{
		foreach ($pupils_data as $file_data)
		{
			/*$sql = "SELECT COUNT(*) as cnt
			 FROM `school_classes` sc
			 JOIN students st ON st.`school_classes_id` = sc.`school_classes_id`
			 JOIN schools s ON sc.`school_id` = s.`school_id`
			 WHERE st.fname = ?
			 AND st.mname = ?
			 AND st.lname = ?
			 AND st.pupil_dup = ?
			 AND st.active = ?
			 AND s.school_name = ?
			 AND s.contract_id = ?
			 AND ?
			 IN (
			 `year_label`
			 )
			 AND ?
			 IN (
			 class1_name, `class2_name` , `class3_name` , `class4_name` , `class5_name` , `class6_name`
			 )";
			 $query = $this->db->query($sql, array($file_data['B'],$file_data['C'],$file_data['D'],$file_data['J'],ACTIVE,$file_data['A'],$contract_id,$file_data['E'],$file_data['F']));
			 $pupils_chk_res = $query->result();*/

			/*$sql = "SELECT school_classes_id
			 FROM `school_classes` sc
			 JOIN schools s ON sc.`school_id` = s.`school_id`
			 WHERE s.school_name = ?
			 AND s.contract_id = ?
			 AND sc.year_status = ?
			 AND ?
			 IN (
			 `year_label`
			 )
			 AND ?
			 IN (
			 class1_name, `class2_name` , `class3_name` , `class4_name` , `class5_name` , `class6_name`
			 )";*/

			unset($data);
			//if($pupils_chk_res[0]->cnt > 0)
			if(!empty($file_data['K']))
			{
				$this->db->select('school_classes_id,user_id,fname,mname,lname,pupil_id,pupil_key,class_col,pupil_dup,fsm,adult,status,cfile_id,cdate,cuser_id,cash_balance,card_balance');
				$this->db->from('students');
				//$this->db->where('school_classes_id', $school_classes_id);
				//$this->db->where('fname', $file_data['B']);
				//$this->db->where('mname', $file_data['C']);
				//$this->db->where('lname', $file_data['D']);
				//$this->db->where('pupil_dup', $file_data['H']);
				$this->db->where('pupil_id', $file_data['K']);
				$this->db->where('active', ACTIVE);
				$query = $this->db->get();
				$pupils_cur_res = $query->result();

				$this->db->set('active', INACTIVE);
				//$this->db->where('school_classes_id', $school_classes_id);
				//$this->db->where('fname', $file_data['B']);
				//$this->db->where('mname', $file_data['C']);
				//$this->db->where('lname', $file_data['D']);
				//$this->db->where('pupil_dup', $file_data['H']);
				$this->db->where('pupil_id', $file_data['K']);
				$this->db->where('active', ACTIVE);
				$this->db->update('students');
				
				

				$data['school_classes_id'] = $pupils_cur_res[0]->school_classes_id;
				$data['user_id'] = $pupils_cur_res[0]->user_id;
				$data['fname'] = $pupils_cur_res[0]->fname;
				$data['mname'] = $pupils_cur_res[0]->mname;
				$data['lname'] = $pupils_cur_res[0]->lname;
				$data['pupil_dup'] = $pupils_cur_res[0]->pupil_dup;
				$data['fsm'] = $file_data['I'];
				$data['adult'] = $file_data['J'];
				$data['status'] = $file_data['G'];
				$data['pupil_id'] = $pupils_cur_res[0]->pupil_id;
				$data['pupil_key'] = $pupils_cur_res[0]->pupil_key;
				$data['class_col'] = $pupils_cur_res[0]->class_col;
				$data['cfile_id'] = $pupils_cur_res[0]->cfile_id;
				$data['cdate'] = $pupils_cur_res[0]->cdate;
				$data['cuser_id'] = $pupils_cur_res[0]->cuser_id;
				$data['muser_id'] = $user_id;
				$data['mfile_id'] = $file_id;
				$data['active'] = ACTIVE;
				$data['cash_balance'] = $pupils_cur_res[0]->cash_balance;
				$data['card_balance'] = $pupils_cur_res[0]->card_balance;

				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->insert('students', $data);
				
				/* Update order_edited for particular student in order_items table */
				if($pupils_cur_res[0]->status != $file_data['G'] || $pupils_cur_res[0]->fsm != $file_data['I'] || $pupils_cur_res[0]->adult != $file_data['J'])
				{
					$update_qry = "UPDATE order_items set order_edited = ". ACTIVE ." where pupil_id ='". $file_data['K'] ."'";
					$query_status = $this->db->query($update_qry);
				}
			}
			else
			{
				$sql = "SELECT school_classes_id,class1_name,class2_name,class3_name,class4_name,class5_name,class6_name
					FROM `school_classes` sc
					JOIN schools s ON sc.`school_id` = s.`school_id`
					WHERE s.school_name = ?
					AND s.contract_id = ?
					AND s.status = ?
					AND year_status = ?
					AND ?
					IN (
					`year_label`
					)
					AND (
					(
					`class1_name` = ? && class1_status = ?
					) || ( `class2_name` = ? && class2_status = ? ) || ( `class3_name` = ? && class3_status = ? ) || ( `class4_name` = ? && class4_status = ? ) || ( `class5_name` = ? && class5_status = ? ) || ( `class6_name` = ? && class6_status = ? )
					)";
				$query = $this->db->query($sql, array($file_data['A'],$contract_id,ACTIVE,ACTIVE,$file_data['E'],$file_data['F'],ACTIVE,$file_data['F'],ACTIVE,$file_data['F'],ACTIVE,$file_data['F'],ACTIVE,$file_data['F'],ACTIVE,$file_data['F'],ACTIVE));
				$school_classes_res = $query->result();
				//echo $this->db->last_query(); exit;
				$school_classes_id = $school_classes_res[0]->school_classes_id;
					
				if($school_classes_res[0]->class1_name == $file_data['F'])
				$class_col = 'class1_name';
				elseif ($school_classes_res[0]->class2_name == $file_data['F'])
				$class_col = 'class2_name';
				elseif ($school_classes_res[0]->class3_name == $file_data['F'])
				$class_col = 'class3_name';
				elseif ($school_classes_res[0]->class4_name == $file_data['F'])
				$class_col = 'class4_name';
				elseif ($school_classes_res[0]->class5_name == $file_data['F'])
				$class_col = 'class5_name';
				elseif ($school_classes_res[0]->class6_name == $file_data['F'])
				$class_col = 'class6_name';
					
				$pupil_key = $this->create_pupil_key($contract_id, 6);	// 6 represents no. of digits
				$contract_key = $this->get_contract_key($contract_id);
				$pupil_id = $contract_key.'/'.$pupil_key;
				$data['school_classes_id'] = $school_classes_id;
				$data['fname'] = $file_data['B'];
				$data['mname'] = $file_data['C'];
				$data['lname'] = $file_data['D'];
				$data['pupil_id'] = $pupil_id;
				$data['pupil_key'] = $pupil_key;
				$data['class_col'] = $class_col;
				$data['pupil_dup'] = $file_data['H'];
				$data['fsm'] = $file_data['I'];
				$data['adult'] = $file_data['J'];
				$data['status'] = $file_data['G'];
				$data['cfile_id'] = $file_id;
				$data['cuser_id'] = $user_id;
				$data['active'] = ACTIVE;

				$this->db->set('cdate', 'NOW()', FALSE);
				$this->db->insert('students', $data);
			}
		}
	}

	public function create_pupil_key($contract_id, $length)
	{
		$pupil_key = get_random_alphanum($length);
		if($pupil_key == null || $pupil_key == "")
		{
			$this->create_pupil_key($contract_id, $length);
		}
		else if(!$this->check_pupil_key($pupil_key, $contract_id))
		{
			return $pupil_key;
		}
		else
		{
			$this->create_pupil_key($contract_id, $length);
		}
	}

	public function check_pupil_key($pupil_key, $contract_id)
	{
		$this->db->select('COUNT(*) as cnt');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->where('s.contract_id',$contract_id);
		$this->db->where('pupil_key',$pupil_key);
		$query = $this->db->get();
		$check_key_res = $query->result();
		return $check_key_res[0]->cnt;
	}

	public function export_pupil_data($data)
	{
		$this->db->select('a.school_name, c.fname, c.mname, c.lname, b.year_label, b.class1_name, b.class2_name, b.class3_name, b.class4_name, b.class5_name, b.class6_name, c.class_col, c.status, c.pupil_dup, c.fsm, c.adult, c.pupil_id, u.username, t.data_value, u.first_name, u.last_name, u.user_email, u.telephone, u.work_telephone, u.mobile_number, u.mail_notification, u.sms_notification');
		$this->db->from('schools a');
		$this->db->join('school_classes b', 'b.school_id = a.school_id');
		$this->db->join('students c', 'c.school_classes_id = b.school_classes_id');
		$this->db->join('users u', 'u.user_id = c.user_id', 'left');
		$this->db->join('data_value t', 't.data_value_id = u.title_id', 'left');
		$this->db->where('a.contract_id',$data['contract_id']);
		$this->db->where('a.school_id',$data['school_id']);
		$this->db->where('b.school_id',$data['school_id']);
		$this->db->where('c.active', ACTIVE);
		//$this->db->where('c.status', ACTIVE);
		$query = $this->db->get();
		$res_contracts = $query->result();
		//echo $this->db->last_query(); exit;
		return $res_contracts;
	}

	/* Get the contracat settings using contract id */
	public function get_contract_settings($data)
	{
		$this->db->select('tminus, min_card_pay, vat, trans_fee_status, dc_fee, cc_fee, refund_fee, adult_invoice, unspecified_mealcost usmc');
		$this->db->from('contracts');
		$this->db->where('customer_id',$data['customer_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$res_contract = $query->result();
		$menu = $this->get_catering_menu_settings($data);	// call to get the catering menu settings
		$res_contract[0]->menu = $menu;
		return $res_contract;
	}

	/* Get the catering menu settings using contract id */
	public function get_catering_menu_settings($data)
	{
		$this->db->select("con_cater_menu_settings_id, menu_cycles, DATE_FORMAT( menu_start_date, '%d/%m/%Y')  AS menu_start_date, menu_sequence",FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$res_menu = $query->result();
		return $res_menu;
	}
	public function get_menu_details($data){

		$this->db->select("COUNT(1) AS c", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$res_menu_count = $query->result();

		$this->db->select("con_cater_menu_settings_id AS menu_id, ". $data['week_cycle'] ." AS w ,menu_cycles AS wc, DATE_FORMAT(menu_start_date, '%d/%m/%Y' ) AS mdate, menu_sequence AS mseq", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('menu_sequence',$data['menu_seq']);
		$query = $this->db->get();
		$res_menu_settings = $query->result();
		
		$this->db->select("con_cater_menu_settings_id AS menu_id ", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('menu_start_date <= CURDATE()');
		$this->db->where('menu_status = 1');
		$this->db->order_by("menu_start_date", "DESC");
		$this->db->limit(1);
		$query = $this->db->get();
		$res_menu_active = $query->result();
		
		$this->db->select('con_cater_menu_details_id AS mid, week_day AS w, meal_type AS m, option_details AS d , option_cost AS c, option_status AS s');
		$this->db->from('con_cater_menu_details');
		$this->db->where('con_cater_menu_settings_id',$res_menu_settings[0]->menu_id);
		$this->db->where('week_cycle',$data['week_cycle']);
		$this->db->order_by("week_day", "ASC");
		$this->db->order_by("meal_type", "ASC");
		$this->db->order_by("option_sequence", "ASC");
		$query = $this->db->get();
		$res_menu_details = $query->result();
		$res_menu[0]->mc = $res_menu_count;
		$res_menu[0]->ms = $res_menu_settings;
		$res_menu[0]->ma = $res_menu_active;
		$res_menu[0]->md = $res_menu_details;

		return $res_menu;
	}

	public function save_menu_details($data){
		$batch_chk = 0;	
		$con_ids ="";
		//Update the each menu option row.
		foreach ($data['menu_details'] as $menus_data)
		{
			$qry_str = "SELECT o.school_id, c.meal_type, 
								case when c.option_details != '". $menus_data['od'] ."' then 0 
								when c.option_cost != '". $menus_data['oc'] ."' then 1 
								when option_status != '". $menus_data['os'] ."' then 2 END as opt 
						FROM order_items o INNER JOIN sc_cater_menu_settings  s on s.sc_cater_menu_settings_id = o.sc_cater_menu_settings_id, 
						con_cater_menu_details c WHERE s.con_cater_menu_details_id = ". $menus_data['cmid'] ."
						AND  c.con_cater_menu_details_id = ". $menus_data['cmid'] ."
						AND fulfilment_date >= DATE(NOW())
						AND o.collect_status = ". INACTIVE ."
						AND o.order_edited = ". INACTIVE ."
						AND o.order_status = ". ORDER_STATUS_NEW ." group by o.school_id";
						
			$query = $this->db->query($qry_str);
			$option_res = $query->result();
			
			$this->db->set('option_details', $menus_data['od']);
			$this->db->set('option_cost', $menus_data['oc']);
			$this->db->set('option_status', $menus_data['os']);
			$this->db->set('muser_id', $data['user_id']);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('con_cater_menu_details_id ', $menus_data['cmid']);
			$this->db->update('con_cater_menu_details');
			
			foreach($option_res as $k => $v)
			{
				if($v->opt == 0)
				{
					if($v->meal_type == MEAL_TYPE_MAINMEAL)
					{
						$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, MENU_NUMBER_REPLACE_STRING, WEEK_NUMBER_REPLACE_STRING);
						$batch_data = array('user_id' => $data['user_id'], 'menu_no' => $data['menu_seq'], 'week_no' => $data['week_cycle'], 'str' => MAIN_MENU_DESC_UPDATE_MESSAGE, 'key_values' => $batch_key);
						$data['reason_msg'] = generate_batch_system_messages($batch_data);

						$batch_cancel_id = create_batch_cancel($data, MAIN_DESCRIPTION_DATA_ID);
					}
					else if($v->meal_type == MEAL_TYPE_SNACK)
					{
						$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, MENU_NUMBER_REPLACE_STRING, WEEK_NUMBER_REPLACE_STRING);
						$batch_data = array('user_id' => $data['user_id'], 'menu_no' => $data['menu_seq'], 'week_no' => $data['week_cycle'], 'str' => SNACK_MENU_DESC_UPDATE_MESSAGE, 'key_values' => $batch_key);
						$data['reason_msg'] = generate_batch_system_messages($batch_data);

						$batch_cancel_id = create_batch_cancel($data, SNACK_DESCRIPTION_DATA_ID);
					}
				}
				else if($v->opt == 1)
				{
					if($v->meal_type == MEAL_TYPE_MAINMEAL)
					{
						$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, MENU_NUMBER_REPLACE_STRING, WEEK_NUMBER_REPLACE_STRING);
						$batch_data = array('user_id' => $data['user_id'], 'menu_no' => $data['menu_seq'], 'week_no' => $data['week_cycle'], 'str' => MAIN_MENU_NET_AMT_UPDATE_MESSAGE, 'key_values' => $batch_key);
						$data['reason_msg'] = generate_batch_system_messages($batch_data);
							
						$batch_cancel_id = create_batch_cancel($data, MAIN_DESCRIPTION_DATA_ID);
					}
					else if($v->meal_type == MEAL_TYPE_SNACK)
					{
						$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, MENU_NUMBER_REPLACE_STRING, WEEK_NUMBER_REPLACE_STRING);
						$batch_data = array('user_id' => $data['user_id'], 'menu_no' => $data['menu_seq'], 'week_no' => $data['week_cycle'], 'str' => SNACK_MENU_NET_AMT_UPDATE_MESSAGE, 'key_values' => $batch_key);
						$data['reason_msg'] = generate_batch_system_messages($batch_data);
							
						$batch_cancel_id = create_batch_cancel($data, SNACK_DESCRIPTION_DATA_ID);
					}
				}
				else if($v->opt == 2)
				{
					$batch_key = array(NAME_REPLACE_STRING, SCHOOL_REPLACE_STRING, DATE_REPLACE_STRING, MENU_NUMBER_REPLACE_STRING, WEEK_NUMBER_REPLACE_STRING);
					$batch_data = array('user_id' => $data['user_id'], 'menu_no' => $data['menu_seq'], 'week_no' => $data['week_cycle'], 'school_id' => $v->school_id, 'str' => MENU_ITEM_CONTRACT_DESELECT_UPDATE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
						
					$batch_cancel_id = create_batch_cancel($data, MAIN_DESCRIPTION_DATA_ID);
				}
				
				/* Update the order edited status for previous orders */
				$update_query_str ="UPDATE order_items o1 SET o1.order_edited = 1, o1.batch_cancel_id = ". $batch_cancel_id ." WHERE o1.order_id in (
									SELECT order_id FROM (
															SELECT order_id FROM 
															order_items o INNER JOIN
															sc_cater_menu_settings  s on s.sc_cater_menu_settings_id = o.sc_cater_menu_settings_id
															WHERE con_cater_menu_details_id =". $menus_data['cmid'] ." AND fulfilment_date >= DATE(NOW()) AND collect_status = ". INACTIVE .")a 
														) AND o1.order_edited = ". INACTIVE ." and o1.order_status = ". ORDER_STATUS_NEW ." and o1.school_id =". $v->school_id;
					
				$update_query = $this->db->query($update_query_str);
			}
		}
			
		//Need to insert school menu details based on the selection.
		$query_str ="INSERT INTO sc_cater_menu_settings(school_id, con_cater_menu_details_id,status, cuser_id, cdate)
				SELECT s.school_id, cd.con_cater_menu_details_id ,1 ,".$data['user_id'].",CURDATE() FROM con_cater_menu_details cd 
				INNER JOIN con_cater_menu_settings cs on cs.con_cater_menu_settings_id = cd.con_cater_menu_settings_id
				LEFT JOIN schools s on cs.contract_id = s.contract_id
				LEFT JOIN sc_cater_menu_settings sc on cd.con_cater_menu_details_id = sc.con_cater_menu_details_id and s.school_id = sc.school_id
				WHERE cs.contract_id = ".$data['contract_id']." AND cs.menu_sequence = ".$data['menu_seq']." AND week_cycle = ".$data['week_cycle']." and option_status = 1
				AND sc.status IS NULL AND (SELECT COUNT(1) from schools where contract_id=".$data['contract_id'].") > 0"; 
		
		$query = $this->db->query($query_str);
		
		//Need to delete school menu details based on option status.
		$query_str ="DELETE ss FROM sc_cater_menu_settings ss
					INNER JOIN con_cater_menu_details cd ON cd.con_cater_menu_details_id = ss.con_cater_menu_details_id
					INNER JOIN con_cater_menu_settings cs ON cs.con_cater_menu_settings_id = cd.con_cater_menu_settings_id
					INNER JOIN schools s ON cs.contract_id = s.contract_id
					WHERE cs.contract_id =".$data['contract_id']."
					AND cs.menu_sequence =".$data['menu_seq']."
					AND week_cycle =".$data['week_cycle']."
					AND option_status =0";
		
		$query = $this->db->query($query_str);
		
	}
	/* Update the contract settings in DB */
	public function update_contract_settings($data)
	{
		$option_query_status = FALSE;

		if($data['adult_invoice'] == 0) {
			//Get the adult_invoice is changed or not.
			$this->db->select('adult_invoice');
			$this->db->from('contracts');
			$this->db->where('contract_id',$data['contract_id']);
			$con_query = $this->db->get();
			$con_query_details = $con_query->result();
			//If adult_invoice was checked eariler and any orders greater than today with invoice to school checked will be marked as edited.
			if($con_query_details[0]->adult_invoice == 1){
				
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
				$batch_data = array('user_id' => $data['user_id'], 'str' => INVOICE_MEALS_UPDATE_MESSAGE, 'key_values' => $batch_key);
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				
				$batch_cancel_id = create_batch_cancel($data, INVOICE_MEALS_DATA_ID);

				$update_order_query = " UPDATE order_items o INNER JOIN schools s ON o.school_id = s.school_id
												AND s.contract_id = ".$data['contract_id']." 
												SET order_edited = 1, batch_cancel_id = ". $batch_cancel_id ."
												WHERE fulfilment_date > NOW() AND invoice_school = 1
												AND o.order_edited = ". INACTIVE ." 
												AND o.order_status = ". ORDER_STATUS_NEW ." 
												AND o.collect_status =". INACTIVE;
					
				$this->db->query($update_order_query);
			}
		}
		
		$contract_data = array(
				'tminus' => $data['tminus'],
				'adult_invoice' => $data['adult_invoice'],
				'min_card_pay' => $data['min_card_pay'],
				'vat' => $data['vat'],
				'trans_fee_status' => $data['trans_fee_status'],
				'dc_fee' => $data['dc_fee'],
				'cc_fee' => $data['cc_fee'],
				'refund_fee' => $data['refund_fee'],
				'unspecified_mealcost' => $data['us_mealcost'],
				'muser_id' => $data['user_id']);

		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('contract_id', $data['contract_id']);
		$contract_query_status = $this->db->update('contracts', $contract_data);	//Update in contracts table
		
		if($contract_query_status)
		{
			$option_array = $data['menu_data'];
			
			//Check whether the menu cycles or menu start is changed or not.
			$change_flag = false;
			$this->db->select('menu_cycles, menu_start_date');
			$this->db->from('con_cater_menu_settings');
			$this->db->where('contract_id',$data['contract_id']);
			$this->db->order_by('menu_sequence');
			$query = $this->db->get();
			$res_menu_settings = $query->result();
			
			if($res_menu_settings[0]->menu_cycles != $option_array[0]['menu_cycles'] ||
				$res_menu_settings[0]->menu_start_date != $option_array[0]['menu_start_date'] || 
				$res_menu_settings[1]->menu_cycles != $option_array[1]['menu_cycles'] ||
				$res_menu_settings[1]->menu_start_date != $option_array[1]['menu_start_date'])
				{
					$i=0;
					foreach($option_array as $key => $value)
					{
						$menu_data = array(
								'menu_cycles' => $value['menu_cycles'],
								'menu_start_date' => $value['menu_start_date'],
								'muser_id' => $data['user_id']);
					
						$this->db->set('mdate', 'NOW()', FALSE);
						$this->db->where('contract_id', $data['contract_id']);
						$this->db->where('con_cater_menu_settings_id', $value['con_cater_menu_settings_id']);
						$this->db->where('menu_sequence', $value['menu_sequence']);
						$menu_query_status = $this->db->update('con_cater_menu_settings', $menu_data);	//Update in Menu settings table						
						
						$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
						$batch_data = array('user_id' => $data['user_id'], 'str' => NAME_DATE_UPDATE_MESSAGE, 'key_values' => $batch_key);
						$date_msg = generate_batch_system_messages($batch_data);
						
						// Nullified the values if menu cycle id is greater than the given menu cycle id
						if($menu_query_status && ($res_menu_settings[$i++]->menu_cycles > $value['menu_cycles']))
						{
							$menu_no = $i;
							$prv_menu = $res_menu_settings[$menu_no]->menu_cycles;
							$cur_menu = $value['menu_cycles'];
							$batch_key = array(MENU_NUMBER_REPLACE_STRING, MENU_PREVIOUS_CYCLE, MENU_CURRENT_CYCLE);
							$batch_data = array('menu_no' => $menu_no, 'prv_menu_cycle' => $prv_menu, 'cur_menu_cycle' => $cur_menu, 'str' => MENU_CYCLE_UPDATE_MESSAGE, 'key_values' => $batch_key);

							$data['reason_msg'] = generate_batch_system_messages($batch_data);
							$data['reason_msg'] = $data['reason_msg'] . " " . $date_msg;
							$batch_cancel_id = create_batch_cancel($data, MENU_CYCLE_DATA_ID);
							
							$data_key = 0;
							$option_data = array(
									'option_details' => '',
									'option_cost' => 0.00,
									'option_status' => INACTIVE,
									'muser_id' => $data['muser_id']);
					
							$this->db->set('mdate', 'NOW()', FALSE);
							$this->db->where('week_cycle > ', $value['menu_cycles']);
							$this->db->where('con_cater_menu_settings_id', $value['con_cater_menu_settings_id']);
							$option_query_status = $this->db->update('con_cater_menu_details', $option_data);	//Update in Menu details table
							
							$qry_str = "SELECT o.school_id FROM order_items o INNER JOIN sc_cater_menu_settings s ON o.sc_cater_menu_settings_id = s.sc_cater_menu_settings_id
										INNER JOIN con_cater_menu_details c ON c.con_cater_menu_details_id  = s.con_cater_menu_details_id
										WHERE c.con_cater_menu_settings_id  = ?
										AND fulfilment_date > DATE(NOW())
										AND collect_status = ?
										AND order_edited = ?
										AND order_status = ? group by o.school_id";
							
							$query = $this->db->query($qry_str, array($value['con_cater_menu_settings_id'], INACTIVE, INACTIVE, ORDER_STATUS_NEW));
							$sch_res = $query->result();
							
							foreach($sch_res as $k => $v)
							{								
								if(isset($batch_cancel_id))
								{
									// Need to update the order_items with order_edited as 1.
									$update_order_query = "UPDATE order_items  o INNER JOIN sc_cater_menu_settings s ON o.sc_cater_menu_settings_id = s.sc_cater_menu_settings_id
												INNER JOIN con_cater_menu_details c ON c.con_cater_menu_details_id  = s.con_cater_menu_details_id
												SET order_edited = 1, batch_cancel_id = ?
											WHERE c.con_cater_menu_settings_id  = ? AND fulfilment_date > NOW() and o.order_edited = ? and o.order_status = ? and o.collect_status = ? AND o.school_id = ?";
									$this->db->query($update_order_query, array($batch_cancel_id, $value['con_cater_menu_settings_id'], INACTIVE, ORDER_STATUS_NEW, INACTIVE, $v->school_id));
								}
								else
								{
									// Need to update the order_items with order_edited as 1.
									$update_order_query = "UPDATE order_items  o INNER JOIN sc_cater_menu_settings s ON o.sc_cater_menu_settings_id = s.sc_cater_menu_settings_id
												INNER JOIN con_cater_menu_details c ON c.con_cater_menu_details_id  = s.con_cater_menu_details_id
												SET order_edited = 1
											WHERE c.con_cater_menu_settings_id  = ? AND fulfilment_date > NOW() and o.order_edited = ? and o.order_status = ? and o.collect_status = ? AND o.school_id = ?";
									$this->db->query($update_order_query, array($value['con_cater_menu_settings_id'], INACTIVE, ORDER_STATUS_NEW, INACTIVE, $v->school_id));
								}								
							}
						}						
					}
					
				//Need to prepare two arrays for active menus with previous and new menu cycles, start date data.
				//Cancel the orders if the menus are not same.
				
				//Get the max order date
				$this->db->select('MAX(fulfilment_date) ful_date, DATE(DATE_SUB(NOW(), INTERVAL (IF(DAYOFWEEK(NOW())=1, 6, DAYOFWEEK(NOW())-2)) DAY)) as lastmonday', false);
				$this->db->from('order_items o');
				$this->db->from('schools s', 'o.school_id = s.school_id');
				$this->db->where('s.contract_id',$data['contract_id']);
				$this->db->where('o.fulfilment_date > ','DATE(NOW())',false);
				$query = $this->db->get();
				$res_orders = $query->result();
				//echo $this->db->last_query();
				//Get last monday's

				$m1_date = date('Y-m-d',strtotime($res_menu_settings[0]->menu_start_date));
				$m2_date = date('Y-m-d',strtotime($res_menu_settings[1]->menu_start_date));
				$m1_new_date = date('Y-m-d',strtotime($option_array[0]['menu_start_date']));
				$m2_new_date = date('Y-m-d',strtotime($option_array[1]['menu_start_date']));
				
				$last_mon = date('Y-m-d',strtotime($res_orders[0]->lastmonday));
				$ful_date = date('Y-m-d',strtotime($res_orders[0]->ful_date));
				
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
				$batch_data = array('user_id' => $data['muser_id'], 'str' => NAME_DATE_UPDATE_MESSAGE, 'key_values' => $batch_key);
				$date_msg = generate_batch_system_messages($batch_data);
						
				$msg = '';
				if($res_menu_settings[0]->menu_start_date != $option_array[0]['menu_start_date'])	// changing the menu1 start date
				{
					$batch_key = array(MENU_NUMBER_REPLACE_STRING, MENU_PREVIOUS_START_DATE, MENU_CURRENT_START_DATE);
					$batch_data = array('menu_no' => 1, 'prv_start_date' => $res_menu_settings[0]->menu_start_date, 'cur_start_date' => $option_array[0]['menu_start_date'], 'str' => MENU_DATE_UPDATE_MESSAGE, 'key_values' => $batch_key);
					
					$msg = generate_batch_system_messages($batch_data);
				}
				if($res_menu_settings[1]->menu_start_date != $option_array[1]['menu_start_date'])	// changing the menu2 start date
				{
					$batch_key = array(MENU_NUMBER_REPLACE_STRING, MENU_PREVIOUS_START_DATE, MENU_CURRENT_START_DATE);
					$batch_data = array('menu_no' => 2, 'prv_start_date' => $res_menu_settings[1]->menu_start_date, 'cur_start_date' => $option_array[1]['menu_start_date'], 'str' => MENU_DATE_UPDATE_MESSAGE, 'key_values' => $batch_key);
					
					if($msg == '')
						$msg = $msg .generate_batch_system_messages($batch_data);
					else
						$msg = $msg . ', ' . generate_batch_system_messages($batch_data);
				}
				
				$data['reason_msg'] = $msg;
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				$data['reason_msg'] = $data['reason_msg'] . " " . $date_msg;
				$sch_data = array();
				
				while($ful_date>=$last_mon){
					// for existing....
					$m1_int = date_diff(date_create($last_mon) ,  date_create($m1_date))->format('%R%a');
					$m2_int = date_diff(date_create($last_mon) ,  date_create($m2_date))->format('%R%a');
					
					$active_menu_int = ($m1_int >0) ? "m2" : (($m2_int>0)? "m1" :(($m1_int<$m2_int)?"m2":"m1"));
					$active_cycle_int =($active_menu_int == "m1")?(($m1_int*-1/7) % $res_menu_settings[0]->menu_cycles) + 1:(($m2_int*-1/7) % $res_menu_settings[1]->menu_cycles) + 1;  
					
					//For new values...
					$m1_new = date_diff(date_create($last_mon) ,  date_create($m1_new_date))->format('%R%a');
					$m2_new = date_diff(date_create($last_mon) ,  date_create($m2_new_date))->format('%R%a');
					
					$active_menu_new = ($m1_new >0) ? "m2" : (($m2_new>0)? "m1" :(($m1_new<$m2_new)?"m2":"m1"));
					$active_cycle_new =($active_menu_new == "m1")?(($m1_new*-1/7) % $res_menu_settings[0]->menu_cycles) + 1:(($m2_new*-1/7) % $res_menu_settings[1]->menu_cycles) + 1;
						
					//echo $active_menu_int." " . $active_menu_new ."  " . $active_cycle_int." ". $active_cycle_new;
					if($active_menu_int!= $active_menu_new || $active_cycle_int!= $active_cycle_new){
						
						$qry_str = "SELECT o.school_id FROM order_items o INNER JOIN schools s ON o.school_id = s.school_id 
									WHERE s.contract_id = ? 
									AND fulfilment_date between ? AND  ? 
									AND o.order_edited = ?
									AND o.collect_status = ?
									AND o.order_status = ? group by o.school_id";
						
						$query = $this->db->query($qry_str, array($data['contract_id'], $last_mon, date('Y-m-d',strtotime($last_mon.' + 1 week')), INACTIVE, INACTIVE, ORDER_STATUS_NEW));
						$sch_res = $query->result();
						
						if(count($sch_res) > 0)
						{
							foreach($sch_res as $k => $v)
							{
								$k = 0;
								foreach($sch_data as $ky => $vl)	// To check if batch is created for that school already or not
								{
									if($v->school_id == $vl['sch_id'])
									{
										$k = 1;
										$batch_id = $vl['batch_id'];
										break;
									}
								}
								
								if($k == 1)
								{
									$batch_cancel_id = $batch_id;
								}
								else
								{
									$batch_cancel_id = create_batch_cancel($data, MENU_START_DATE_DATA_ID);
									$sch_data[] = array('sch_id' => $v->school_id, 'batch_id' => $batch_cancel_id);
								}
								
								if(isset($batch_cancel_id))
								{
									$update_order_query = " UPDATE order_items o INNER JOIN schools s ON o.school_id = s.school_id
												AND s.contract_id = ?
												SET order_edited = 1, batch_cancel_id = ?
												WHERE fulfilment_date between ? AND  ?
												AND o.school_id = ?
												AND o.order_edited = ?
												AND o.collect_status = ?
												AND o.order_status = ?";
										
									$this->db->query($update_order_query, array($data['contract_id'], $batch_cancel_id, $last_mon, date('Y-m-d',strtotime($last_mon.' + 1 week')), $v->school_id, INACTIVE, INACTIVE, ORDER_STATUS_NEW));
								}
								else
								{
									$update_order_query = " UPDATE order_items o INNER JOIN schools s ON o.school_id = s.school_id
												AND s.contract_id = ?
												SET order_edited = 1
												WHERE fulfilment_date between ? AND  ?
												AND o.school_id = ?
												AND o.order_edited = ?
												AND o.collect_status = ?
												AND o.order_status = ?";

									$this->db->query($update_order_query, array($data['contract_id'], $last_mon, date('Y-m-d',strtotime($last_mon.' + 1 week')), $v->school_id, INACTIVE, INACTIVE, ORDER_STATUS_NEW));
								}
							}
						}
					}
					$last_mon= date('Y-m-d',strtotime($last_mon.' + 1 week'));
				}
				return $menu_query_status;
			}
		}
		return $contract_query_status;
	}

	/* Update Menu Option Status (Enable or Disable) */
	public function update_menu_option_status($data)
	{
		$option_data = array(
				'option_status' => $data['option_status'],
				'muser_id' => $data['muser_id']);

		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('con_cater_menu_details_id	', $data['option_id']);
		return $this->db->update('con_cater_menu_details', $option_data);	//Update in con_cater_menu_details table
	}
	
	/* To get the payment items */
	public function export_payment_items($data)
	{
		//$qry_str = "SELECT a.payment_id, a.pupil_id, a.amount, a.transaction_fee, a.card_cash, a.pay_refund, DATE_FORMAT(date(a.cdate), '%d/%m/%Y') as cdate, d.school_key, d.school_name, e.contract_name, f.username from payment_items a, students b, school_classes c, schools d, contracts e, users f where a.pupil_id = b.pupil_id and b.active = ". ACTIVE ." and b.school_classes_id = c.school_classes_id and c.school_id = d.school_id and d.contract_id = e.contract_id and f.user_id = a.cuser_id and a.status = ". ACTIVE ." and d.contract_id = ". $data['contract_id'] ." and e.contract_id = ". $data['contract_id'] ." and date(a.cdate) between '". $data['start_date'] ."' and '". $data['end_date'] ."' order by d.school_id, a.cdate";
		
		$this->db->select('p.payment_id, p.pupil_id, p.amount, p.pgtr_id, p.pgauth_id, p.transaction_fee, p.card_cash, p.pay_refund, case when y.description is NULL then "'. TRANSACTION_INITIATED .'" else y.description end as description, s.school_key, s.school_name, c.contract_name, u.username', FALSE);
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y') as cdate", FALSE);
		$this->db->from('payment_items p');
		$this->db->join('yp_codes y', 'y.code = p.yp_code', 'LEFT');
		$this->db->join('students st', 'st.pupil_id = p.pupil_id');
		$this->db->join('school_classes sc', 'sc.school_classes_id = st.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('contracts c', 'c.contract_id = s.contract_id');
		$this->db->join('users u', 'u.user_id = p.cuser_id');
		$this->db->where('c.contract_id', $data['contract_id']);
		$this->db->where('s.contract_id', $data['contract_id']);
		$this->db->where('date(p.cdate) >=', $data['start_date']);
		$this->db->where('date(p.cdate) <=', $data['end_date']);
		$this->db->order_by('s.school_id', 'desc');
		$this->db->order_by('p.cdate', 'desc');
		
		$query = $this->db->get();
		$payment_query_data = $query->result(); 
		
		return $payment_query_data;
	}
	
	/* To get the export items */
	public function export_order_items($data)
	{
		//$qry = "SELECT a.order_id, DATE_FORMAT(date(a.fulfilment_date), '%d/%m/%Y') as fulfilment_date, DATE_FORMAT(max(date(a.order_date)), '%d/%m/%Y') as order_date, CASE WHEN a.order_status = 0 AND a.order_edited = 0 then 'New' WHEN a.order_status = 0 AND a.order_edited = 1 then 'Edited' ELSE 'Cancelled' end as order_status, SUM(a.card_net) as card_net, SUM(a.card_vat) as card_vat, SUM(a.cash_net) as cash_net, SUM(a.cash_vat) as cash_vat, SUM(a.fsm_net) as fsm_net, SUM(a.fsm_vat) as fsm_vat, SUM(a.a_card_net) as a_card_net, SUM(a.a_card_vat) as a_card_vat, SUM(a.a_cash_net) as a_cash_net, SUM(a.a_cash_vat) as a_cash_vat, SUM(a.a_sa_net) as a_sa_net, SUM(a.a_sa_vat) as a_sa_vat, SUM(a.a_hos_net) as a_hos_net, SUM(a.a_hos_vat) as a_hos_vat from order_items a, schools b where a.school_id = b.school_id and b.contract_id = ". $data['contract_id'] ." group by order_id";
		$qry = "SELECT a.order_id, DATE_FORMAT(date(a.fulfilment_date), '%d/%m/%Y') as fulfilment_date, DATE_FORMAT(max(date(a.order_date)), '%d/%m/%Y') as order_date, CASE WHEN a.order_status = 0 AND a.order_edited = 0 then 'New' WHEN a.order_status = 0 AND a.order_edited = 1 then 'Edited' ELSE 'Cancelled' end as order_status, c.batch_cancel_id, c.system_msg, c.user_msg, SUM(a.card_net) as card_net, SUM(a.card_vat) as card_vat, SUM(a.cash_net) as cash_net, SUM(a.cash_vat) as cash_vat, SUM(a.fsm_net) as fsm_net, SUM(a.fsm_vat) as fsm_vat, SUM(a.a_card_net) as a_card_net, SUM(a.a_card_vat) as a_card_vat, SUM(a.a_cash_net) as a_cash_net, SUM(a.a_cash_vat) as a_cash_vat, SUM(a.a_sa_net) as a_sa_net, SUM(a.a_sa_vat) as a_sa_vat, SUM(a.a_hos_net) as a_hos_net, SUM(a.a_hos_vat) as a_hos_vat from order_items a LEFT JOIN batch_cancel c ON a.batch_cancel_id = c.batch_cancel_id, schools b where a.school_id = b.school_id and b.contract_id = ". $data['contract_id'] ." group by order_id";
		
		$order_query = $this->db->query($qry);
		$order_data = $order_query->result();	
		
		foreach($order_data as $key => $value)
		{
			//$qry_str = "SELECT a.meal_type, a.option_details, a.option_cost, a.hospitality_desc, b.pupil_id, b.fname, b.mname, b.lname, a.fsm, a.adult, b.class_col, b.pupil_dup, a.status, c.school_key, c.school_name, d.year_label, d.class1_name, d.class2_name, d.class3_name, d.class4_name, d.class5_name, d.class6_name, e.username from order_items a, students b, schools c, school_classes d, users e where a.order_id = '". $value->order_id . "' and a.pupil_id = b.pupil_id and b.active = ". ACTIVE . " and a.school_id = c.school_id and b.school_classes_id = d.school_classes_id and e.user_id = a.cuser_id and c.contract_id = ". $data['contract_id'] ." and date(a.fulfilment_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."' order by c.school_id, a.fulfilment_date";
			
			$qry_str = "SELECT a.meal_type, a.option_details, a.option_cost, a.hospitality_desc, b.pupil_id, b.fname, b.mname, b.lname, a.fsm, a.adult, b.class_col, b.pupil_dup, a.status, c.school_key, c.school_name, d.year_label, d.class1_name, d.class2_name, d.class3_name, d.class4_name, d.class5_name, d.class6_name, e.username 
						FROM order_items a
							LEFT JOIN students b ON a.pupil_id = b.pupil_id AND b.active = 1 
							INNER JOIN schools c ON a.school_id = c.school_id
							LEFT JOIN school_classes d ON b.school_classes_id = d.school_classes_id
							INNER JOIN users e ON e.user_id = a.cuser_id
						WHERE 
							a.order_id = '". $value->order_id . "'
							AND c.contract_id = ". $data['contract_id'] ."
							AND date(a.fulfilment_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'
						order by c.school_id, a.fulfilment_date";
			
			$query = $this->db->query($qry_str);
			$order_query_data = $query->result();
			
			if(count($order_query_data) > 0)
			{
				$stu_obj = new stdClass();
				$stu_obj->order_id = $value->order_id;
				$stu_obj->fulfilment_date = $value->fulfilment_date;
				$stu_obj->order_date = $value->order_date;
				$stu_obj->order_status = $value->order_status;
				$stu_obj->impact_batch = $value->batch_cancel_id;
				$stu_obj->sys_msg = $value->system_msg;
				$stu_obj->user_msg = $value->user_msg;
				$stu_obj->card_net = $value->card_net;
				$stu_obj->card_vat = $value->card_vat;
				$stu_obj->cash_net = $value->cash_net;
				$stu_obj->cash_vat = $value->cash_vat;
				$stu_obj->fsm_net = $value->fsm_net;
				$stu_obj->fsm_vat = $value->fsm_vat;
				$stu_obj->a_card_net = $value->a_card_net;
				$stu_obj->a_card_vat = $value->a_card_vat;
				$stu_obj->a_cash_net = $value->a_cash_net;
				$stu_obj->a_cash_vat = $value->a_cash_vat;
				$stu_obj->a_sa_net = $value->a_sa_net;
				$stu_obj->a_sa_vat = $value->a_sa_vat;
				$stu_obj->a_hos_net = $value->a_hos_net;
				$stu_obj->a_hos_vat = $value->a_hos_vat;
	
				$main_meal = "";
				$main_meal_net = "";
				$snacks = "";
				$snacks_net = "";
				$hosp_desc = "";
							
				foreach($order_query_data as $k => $val)
				{
					if($val->meal_type != '' && $val->meal_type != NULL)
					{
						switch($val->meal_type)
						{
							case MEAL_TYPE_MAINMEAL:
								if($main_meal != "")
								$main_meal = $main_meal . "; " . $val->option_details;
								else
								$main_meal = $val->option_details;
									
								if($main_meal_net != "")
								$main_meal_net = $main_meal_net . "; " . $val->option_cost;
								else
								$main_meal_net = $val->option_cost;
								break;

							case MEAL_TYPE_SNACK:
								if($snacks != "")
								$snacks = $snacks . "; " . $val->option_details;
								else
								$snacks = $val->option_details;
									
								if($snacks_net != "")
								$snacks_net = $snacks_net . "; " . $val->option_cost;
								else
								$snacks_net = $val->option_cost;
								break;
						}
					}
					
					if($val->hospitality_desc)
					{
						if($hosp_desc == "")
							$hosp_desc = $val->hospitality_desc;
						else
							$hosp_desc = $hosp_desc . "; ". $val->hospitality_desc;
					}
				}
				
				$stu_obj->main_meal = $main_meal;
				$stu_obj->main_net = $main_meal_net;
				
				$stu_obj->snacks = $snacks;
				$stu_obj->snacks_net = $snacks_net;
				
				$stu_obj->hospitality_desc = $hosp_desc;
				
				$stu_obj->pupil_id = $order_query_data[0]->pupil_id;
				$stu_obj->fname = $order_query_data[0]->fname;
				$stu_obj->mname = $order_query_data[0]->mname;
				$stu_obj->lname = $order_query_data[0]->lname;
				$stu_obj->fsm = $order_query_data[0]->fsm;
				$stu_obj->adult = $order_query_data[0]->adult;
				$stu_obj->class_col = $order_query_data[0]->class_col;
				$stu_obj->pupil_dup = $order_query_data[0]->pupil_dup;
				$stu_obj->status = $order_query_data[0]->status;
				$stu_obj->school_key = $order_query_data[0]->school_key;
				$stu_obj->school_name = $order_query_data[0]->school_name;
				$stu_obj->year_label = $order_query_data[0]->year_label;
				$stu_obj->class1_name = $order_query_data[0]->class1_name;
				$stu_obj->class2_name = $order_query_data[0]->class2_name;
				$stu_obj->class3_name = $order_query_data[0]->class3_name;
				$stu_obj->class4_name = $order_query_data[0]->class4_name;
				$stu_obj->class5_name = $order_query_data[0]->class5_name;
				$stu_obj->class6_name = $order_query_data[0]->class6_name;
				$stu_obj->username = $order_query_data[0]->username;
				
				$order_res[] = $stu_obj;
			}			
		}
		return $order_res;
	}
	
	/* To Get the search pupils for card */
	public function get_card_search_pupils($data)
	{
		$this->db->select('p.payment_id as transaction_id, fname,mname,lname,st.pupil_id,fsm,st.card_balance, p.amount as trans_amount, s.school_name,adult,u.contract_id as user_contract_id,u.username,c.trans_fee_status as trans_fee_status, c.refund_fee, p.transaction_fee');
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as date", FALSE);
		$this->db->from('students st');
		$this->db->join('payment_items p', 'p.pupil_id = st.pupil_id');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('users u', 'u.user_id = st.user_id');
		$this->db->join('contracts c', 'c.contract_id = u.contract_id');
		$this->db->where('p.payment_id', $data['transaction_id']);
		$this->db->where('p.card_cash', CARD);
		$this->db->where('p.pay_refund', PAYMENT);
		$this->db->where('p.yp_code', YES_PAY_SUCCESS);
		$this->db->where('p.pgtr_id !=', '');
		$this->db->where('p.status', ACTIVE);					
		$this->db->where('st.active', ACTIVE);
		$this->db->where('c.contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_pupils = $query->result();
		return $res_pupils;
		
		/*$this->db->select('students_id,fname,mname,lname,pupil_id,fsm,card_balance,s.school_name,s.school_id,adult,u.contract_id as user_contract_id,u.username');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('users u', 'u.user_id = st.user_id');
		$this->db->where('u.username', $data['username']);
		$this->db->where('u.contract_id', $data['contract_id']);
		//$this->db->where('st.status', ACTIVE);
		$this->db->where('st.active', ACTIVE);
		$query = $this->db->get();
		$res_pupils = $query->result();
		return $res_pupils;*/
	}
	
	/* To Save card refund */
	/*public function save_card_refund($data)
	{
		$refund_arr = $data['refund_data'];
		$card_ref_query_status = FALSE;
		
		foreach($refund_arr as $key => $value)
		{
			$card_res = $this->get_students_card_balance($value['pupil_id']);
			if(($card_res[0]->card_balance) > 0)
			{
				$refund_data = array(
					'pupil_id' => $value['pupil_id'],
					'payment_id' => $data['payment_id'],
					'amount' => $card_res[0]->card_balance,
					'transaction_fee' => 0.00,
					'card_cash' => $data['trans_type'],
					'pay_refund' => $data['trans_mode'],
					'status' => ACTIVE,
					'cuser_id' => $data['user_id']);

				$this->db->set('cdate', 'NOW()', FALSE);
				$card_ref_query_status = $this->db->insert('payment_items', $refund_data);
				
				// Update the students card balance
				if($card_ref_query_status)
				{
					$this->update_card_balance($value['pupil_id'],$value['card_balance'],$data['trans_mode'],$data['trans_type']);
				}
			}		
		}
		return $card_ref_query_status;
	}*/
	
	// To get card available refund
	public function get_card_available_refund($data) 
	{
		$sql = "SELECT (
				(
				SELECT CASE WHEN SUM( amount ) IS NULL
				THEN 0
				ELSE SUM( amount )
				END
				FROM payment_items
				WHERE payment_id = '".$data['transaction_id']."'
				AND card_cash = ".CARD."
				AND pay_refund = ".PAYMENT."
				AND yp_code = ".YES_PAY_SUCCESS."
				AND status = ".ACTIVE."
				) - (
				SELECT CASE WHEN SUM( amount ) IS NULL
				THEN 0
				ELSE SUM( amount )
				END
				FROM payment_items
				WHERE refund_ref_pid = '".$data['transaction_id']."'
				AND card_cash = ".CARD."
				AND pay_refund = ".REFUND."
				AND yp_code = ".YES_PAY_SUCCESS."
				AND status = ".ACTIVE." )
				) AS available_refund";
		$query = $this->db->query($sql);
		$res = $query->result();
		return $res[0]->available_refund;
	}
	
	/* To get the students card balance */
	public function get_students_card_balance($pupil_id)
	{
		$this->db->select('card_balance');
		$this->db->from('students');
		$this->db->where('pupil_id', $pupil_id);
		//$this->db->where('status', ACTIVE);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$balance_res = $query->result();
		return $balance_res;
	}
	
	/* Update the students card balance */
	public function update_card_balance($pupil_id,$amount,$update_type,$trans_mode)
	{
		$this->db->select('cash_balance,card_balance');
		$this->db->from('students');
		$this->db->where('pupil_id',$pupil_id);
		//$this->db->where('status', ACTIVE);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$res_balance = $query->result();
		
		/* Check the transaction mode */
		if($trans_mode == CARD)
		{
			if($update_type == PAYMENT)
			$updated_card_balance = $res_balance[0]->card_balance + $amount;
			else 
			$updated_card_balance = $res_balance[0]->card_balance - $amount;
			
			//$qry = "UPDATE students SET card_balance = ". $updated_card_balance ." where pupil_id = '". $pupil_id ."' and status = ". ACTIVE." and active = ". ACTIVE;
			$qry = "UPDATE students SET card_balance = ". $updated_card_balance ." where pupil_id = '". $pupil_id ."' and active = ". ACTIVE;
		}
		else
		{
			if($update_type == PAYMENT)
			$updated_cash_balance = $res_balance[0]->cash_balance + $amount;
			else 
			$updated_cash_balance = $res_balance[0]->cash_balance - $amount;
			
			//$qry = "UPDATE students SET cash_balance = ". $updated_cash_balance ." where pupil_id = '". $pupil_id ."' and status = ". ACTIVE." and active = ". ACTIVE;
			$qry = "UPDATE students SET cash_balance = ". $updated_cash_balance ." where pupil_id = '". $pupil_id ."' and active = ". ACTIVE;
		}
		$this->db->query($qry);
	}
	
	/* Get the updated card payment */
	public function get_updated_card_payment_items($payment_id)
	{
		$this->db->select('fname, mname, lname, fsm, s.school_name, adult, p.payment_id, p.pupil_id, p.amount, p.transaction_fee, u.username');
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as cdate", FALSE);
		$this->db->from('students st');			
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');	
		$this->db->join('payment_items p', 'st.pupil_id = p.pupil_id');
		$this->db->join('users u', 'u.user_id = p.cuser_id');
		//$this->db->where('st.status', ACTIVE);	
		$this->db->where('st.active', ACTIVE);
		//$this->db->where('p.yp_code' , INACTIVE);
		$this->db->where('p.card_cash', CARD);
		$this->db->where('p.pay_refund', REFUND);
		$this->db->where('p.status', ACTIVE);	
		$this->db->where('p.payment_id', $payment_id);
		$query = $this->db->get();		
		$res_pupils = $query->result();
			
		return $res_pupils;
	}
	
	/* To get the full history for card payement & refund */
	public function get_card_full_history($data)
	{
		$total_record = CARD_REFUND_NAVIGATION_COUNT;
		if($data['page_no'] == 1)
		{
			$this->db->select('COUNT(1) as count');
			$this->db->from('students st');
			$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
			$this->db->join('schools s', 's.school_id = sc.school_id');
			$this->db->join('payment_items p', 'st.pupil_id = p.pupil_id');
			$this->db->join('yp_codes yp', 'yp.code = p.yp_code', 'left');
			$this->db->join('users u', 'u.user_id = p.cuser_id');
			$this->db->where('s.contract_id', $data['contract_id']);
			$this->db->where('st.active', ACTIVE);
			//$this->db->where('st.status', ACTIVE);
			$this->db->where('p.card_cash', $data['trans_mode']);
			$this->db->where('p.pay_refund', REFUND);
			$this->db->order_by('p.cdate', 'desc');

			$query = $this->db->get();

			$res_records = $query->result();
			
			$res_history_info->total_history_records = $res_records[0]->count;
			
			$start = 0;
			$end = $total_record;
		}
		else
		{
			$end = $data['page_no'] * $total_record;
			$start = $end - $total_record;
		}	
		
		$this->db->select('fname, mname, lname, fsm, s.school_name, adult, p.payment_id, p.pupil_id, p.pgtr_id, p.amount, p.pay_refund, p.yp_code, case when yp.description is NULL then "'. TRANSACTION_INITIATED .'" else yp.description end as description, u.username', FALSE);
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as cdate", FALSE);
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('payment_items p', 'st.pupil_id = p.pupil_id');
		$this->db->join('yp_codes yp', 'yp.code = p.yp_code', 'left');
		$this->db->join('users u', 'u.user_id = p.cuser_id');
		$this->db->where('s.contract_id', $data['contract_id']);
		$this->db->where('st.active', ACTIVE);
		//$this->db->where('st.status', ACTIVE);
		$this->db->where('p.card_cash', $data['trans_mode']);
		$this->db->where('p.pay_refund', REFUND);
		$this->db->order_by('p.cdate', 'desc');
		$this->db->limit($total_record, $start);
		
		$query = $this->db->get();
		$res_history_info->history_records = $query->result();	
		
		return $res_history_info;
	}
	
	/* To get the full history for card payement & refund */
	public function get_session_log_contract($data)
	{
		$this->db->select('session_log');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		
		$query = $this->db->get();
		$res_session = $query->result();
		
		//$qry_str = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join profiles p on u.profile_id = p.profile_id where u.session_log = ". INACTIVE . " and ((u.customer_id = ". $data['customer_id'] ." and u.role_id = ". CUSTOMER_ADMIN .") or u.contract_id = ". $data['contract_id'] .") order by u.first_name";
		$qry_str = "Select user_id, first_name, last_name, username, profile_name
					from
					(
						Select user_id, first_name, last_name, username, COALESCE(p.profile_name, '') as profile_name from users u left join profiles p on u.profile_id = p.profile_id where u.session_log = ? and u.role_id = ? and u.contract_id = ?
						Union 
						Select user_id, first_name, last_name, username, COALESCE(ap.profile_name, '') as profile_name from users us left join ad_profiles ap on us.profile_id = ap.ad_profile_id where us.session_log = ? and us.customer_id = ? and us.role_id = ?
					) T order by first_name";
		
		$query = $this->db->query($qry_str, array(INACTIVE, USER, $data['contract_id'], INACTIVE, $data['customer_id'], CUSTOMER_ADMIN));
		$res_avialable_users = $query->result();
		
		//$qry_str = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join profiles p on u.profile_id = p.profile_id where u.session_log = ". ACTIVE . " and ((u.customer_id = ". $data['customer_id'] ." and u.role_id = ". CUSTOMER_ADMIN .") or u.contract_id = ". $data['contract_id'] .") order by u.first_name";
		$qry_str = "Select user_id, first_name, last_name, username, profile_name
					from
					(
						Select user_id, first_name, last_name, username, COALESCE(p.profile_name, '') as profile_name from users u left join profiles p on u.profile_id = p.profile_id where u.session_log = ? and u.role_id = ? and u.contract_id = ?
						Union 
						Select user_id, first_name, last_name, username, COALESCE(ap.profile_name, '') as profile_name from users us left join ad_profiles ap on us.profile_id = ap.ad_profile_id where us.session_log = ? and us.customer_id = ? and us.role_id = ?
					) T order by first_name";
		
		$query = $this->db->query($qry_str, array(ACTIVE, USER, $data['contract_id'], ACTIVE, $data['customer_id'], CUSTOMER_ADMIN));
		$res_selected_users = $query->result();
		
		//$qry = "SELECT COUNT(*) as count FROM session_logs WHERE (user_id = ANY (SELECT user_id from users where contract_id = ". $data['contract_id']." AND session_log = ". ACTIVE .")) or (user_id = ANY (SELECT user_id from users where role_id = ". CUSTOMER_ADMIN ." AND session_log = ". ACTIVE ."))";
		$qry = "SELECT COUNT(*) as count FROM session_logs WHERE contract_id = ?";
		
		$query = $this->db->query($qry, array($data['contract_id']));
		$res_logs_count = $query->result();
		
		$log_data = array('customer_id' => $data['customer_id'], 'contract_id' => $data['contract_id'], 'page_no' => 1);
		$res_logs = $this->get_session_log_navigation($log_data);
		
		$res_session_info[0]->contract_session = $res_session[0]->session_log;
		$res_session_info[0]->available_users = $res_avialable_users;
		$res_session_info[0]->selected_users = $res_selected_users;
		$res_session_info[0]->total_logs = $res_logs_count[0]->count;
		$res_session_info[0]->log_records = $res_logs;

		return $res_session_info;
	}
	
	public function get_session_log_navigation($data)
	{
		$total_record = SESSION_LOG_NAVIGATION_COUNT;
		if($data['page_no'] == 1)
		{
			$start = 0;
			$end = $total_record;
		}
		else
		{
			$end = $data['page_no'] * $total_record;
			$start = $end - $total_record;
		}
		
		//$qry = "SELECT u.user_id, u.username, s.ip_address, DATE_FORMAT(s.cdate, '%d/%m/%Y %H:%i')as cdate, s.message from users u, session_logs s where u.user_id = s.user_id and ((u.customer_id = ". $data['customer_id'] ." and u.role_id = ". CUSTOMER_ADMIN .") or u.contract_id = ". $data['contract_id'] .") and u.user_id = ANY (SELECT user_id from users) order by s.cdate DESC LIMIT ". $start . ", ". $total_record;
		$qry = "SELECT u.user_id, u.username, s.ip_address, DATE_FORMAT(s.cdate, '%d/%m/%Y %H:%i')as cdate, s.message from users u, session_logs s where u.user_id = s.user_id and u.customer_id = ? and s.contract_id = ? order by s.cdate DESC LIMIT ?, ?";
		
		$query = $this->db->query($qry, array($data['customer_id'], $data['contract_id'], $start, $total_record));
		$res_logs_data = $query->result();
		
		return $res_logs_data;
	}
	
	public function save_session_log_contract($data)
	{
		if($data['session_log_contract'] != null && $data['session_log_contract'] != '')
		{
			$contract_data = array(
						'session_log' => $data['session_log_contract']
				);
			$this->db->where('contract_id',$data['contract_id']);
			$this->db->where('customer_id', $data['customer_id']);
			$update_status = $this->db->update('contracts',$contract_data);
		}
		
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				$this->db->select('role_id');
				$this->db->from('users');
				$this->db->where('user_id', $value['user_id']);
				$this->db->where('customer_id', $data['customer_id']);
					
				$query = $this->db->get();
				$res_user_role = $query->result();

					
				$user_data = array(
						'session_log' => $value['session_log']
				);
				$this->db->where('user_id', $value['user_id']);
				if($res_user_role[0]->role_id == USER)
				{
					$this->db->where('contract_id',$data['contract_id']);
				}
				$this->db->where('customer_id', $data['customer_id']);
				$res_update_session = $this->db->update('users',$user_data);
			}
		}
		
		if(isset($res_update_session))
			return $res_update_session;
		else if(isset($update_status))
			return $update_status;
		else
			return FALSE;
	}
	
	public function purge_session_log_contract($data)
	{
		//$delete_query_str = "DELETE FROM session_logs where user_id  = ANY (Select user_id from users where contract_id = ". $data['contract_id'] .")";
		$delete_query_str = "DELETE FROM session_logs where contract_id = ". $data['contract_id'];
		$delete_query = $this->db->query($delete_query_str);
		return $delete_query;
	}
	
	public function get_profile_master_details($data)
	{
		$this->db->select('profile_id, profile_name');
		$this->db->from('profiles');
		$this->db->where('status', ACTIVE);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->order_by("profile_name", "asc");
		
		$query = $this->db->get();
		$res_profiles = $query->result();
		
		$this->db->select('skin_id, skin_name');
		$this->db->from('skins');
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->order_by('cdate', 'ASC');
		$this->db->where('status', ACTIVE);
		
		$query = $this->db->get();
		$res_skin = $query->result();
		
		$qry_str = "SELECT a.m_module_id, a.m_module_name, CASE WHEN (count(s.m_module_id)) > 0 THEN '1' ELSE '0' END AS hierarchy from m_modules a LEFT join s_modules s on a.m_module_id = s.m_module_id where a.status = ". ACTIVE ." group by a.m_module_name order by a.sequence_no";
		
		$query = $this->db->query($qry_str);
		$res_m_modules = $query->result();
		
		$i=0; $j=0;
		foreach($res_m_modules as $key => $value)
		{
			$qry_str = "SELECT a.s_module_id, a.m_module_id, a.s_module_name, CASE WHEN (count(s.ss_module_id)) > 0 THEN '1' ELSE '0' END AS hierarchy from s_modules a LEFT join ss_modules s on a.s_module_id = s.s_module_id where a.status = ". ACTIVE . " and a.m_module_id = ". $value->m_module_id ." group by a.s_module_name order by a.sequence_no";
			
			$query = $this->db->query($qry_str);
			$res_s_modules = $query->result();		
			
			$keyvalue = $value->m_module_name;
			$m_modules[$i] = new stdClass();
			$m_modules[$i]->$keyvalue = $res_s_modules;
			
			$i= $i+1;
			foreach($res_s_modules as $k => $v)
			{
				$ss_modules = $this->get_ss_modules_parent($v->s_module_id);

				if(count($ss_modules) > 0)
				{
					$kv = $v->s_module_name;
					$sub_modules[$j] = new stdClass();
					$sub_modules[$j]->$kv = $ss_modules;
					$j = $j+1;
				}
			}
		}
		//print_r($m_modules);
		$profile_res[0] = new stdClass();
		$profile_res[0]->profiles = $res_profiles;
		$profile_res[0]->skin = $res_skin;
		$profile_res[0]->m_modules = $res_m_modules;
		
		if(isset($m_modules))
			$profile_res[0]->sub_modules = $m_modules;
		
		if(isset($sub_modules))
			$profile_res[0]->sub_sub_modules = $sub_modules;
		
		return $profile_res;
	}
	
	public function check_profile_name_exists($data)
	{
		$this->db->select('COUNT(*) as profile_dup_count');
		$this->db->from('profiles');
		$this->db->where('profile_name',$data['profile_name']);
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function check_profile_name_exists_edit($data)
	{
		$this->db->select('COUNT(*) as profile_dup_count');
		$this->db->from('profiles');
		$this->db->where('profile_name',$data['profile_name']);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('profile_id != ',$data['profile_id']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function create_profile_contract($data)
	{
		$this->db->select('skin_id');
		$this->db->from('skins');
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('default', ACTIVE);
		$query = $this->db->get();
		$res_skin = $query->result();
		
		$skin_id = 0;
		if(isset($res_skin[0]->skin_id))
		{
			$skin_id = $res_skin[0]->skin_id;
		}
		
		$data = array(
		    'contract_id' => $data['contract_id'] ,
		    'profile_name' => $data['profile_name'] ,
			'skin_id' => $skin_id ,
			'status' => ACTIVE,
			'cuser_id' => $data['user_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$create_profile = $this->db->insert('profiles', $data);
		$profile_id = $this->db->insert_id();
		return $profile_id;
	}
	
	public function get_profile_details_contract($data)
	{
		$this->db->select('profile_name, profile_id, skin_id, m_module_id, self_registration, hide_main_nav, status');
		$this->db->from('profiles');
		$this->db->where('profile_id', $data['profile_id']);
		$this->db->where('contract_id', $data['contract_id']);
		
		$query = $this->db->get();
		$res_profile = $query->result();
		
		$this->db->select('s_module_id');
		$this->db->from('profiles_s_modules');
		$this->db->where('profile_id', $data['profile_id']);
		
		$query = $this->db->get();
		$res_modules = $query->result();
		
		$this->db->select('ss_module_id');
		$this->db->from('profiles_ss_modules');
		$this->db->where('profile_id', $data['profile_id']);
		
		$query = $this->db->get();
		$res_ss_modules = $query->result();
	
		$qry_str = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join profiles p on u.profile_id = p.profile_id where u.contract_id = ? and u.profile_id != ? order by u.first_name";
		
		$query = $this->db->query($qry_str, array($data['contract_id'],$data['profile_id']));
		$res_avialable_users = $query->result();
		
		$qry_str = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join profiles p on u.profile_id = p.profile_id where u.profile_id = ?  and p.profile_id = ? and  u.contract_id = ? order by u.first_name";
		
		$query = $this->db->query($qry_str, array($data['profile_id'], $data['profile_id'], $data['contract_id']));
		$res_selected_users = $query->result();
		
		$profile_res[0] =  new stdClass();
		$profile_res[0]->profile_res = $res_profile;
		$profile_res[0]->profile_main_modules = $res_modules;
		$profile_res[0]->profile_sub_modules = $res_ss_modules;
		$profile_res[0]->available_users = $res_avialable_users;
		$profile_res[0]->selected_users = $res_selected_users;		
		
		return $profile_res;
	}
	
	public function get_ss_modules_parent($s_module_id)
	{
		$this->db->select('ss_module_id, s_module_id, p_ss_module_id, ss_module_name');
		$this->db->from('ss_modules');
		$this->db->where('status', ACTIVE);		
		$this->db->where('s_module_id', $s_module_id);
		$this->db->order_by('sequence_no', 'ASC');
				
		$query = $this->db->get();
		$res_ss_modules = $query->result();
		
		$res = array();
		$i=0;
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
							$res[$i] = new stdClass();							
							$res[$i]->$keyvalue = $dum_array;
							$i= $i+1;
						}
					}
					else
					{
						$res_empty = array();
						$keyvalue = $value->ss_module_name;
						$dum_array = array();
						$dum_array[0] = $value;
						$dum_array[1] = $res_empty;	
						$res[$i] = new stdClass();
						$res[$i]->$keyvalue = $dum_array;
						$i= $i+1;
					}
					
				}
			}		
		}
		return $res;
	}
	
	public function get_sub_sub_modules($module_id)
	{
		$this->db->select('ss_module_id, s_module_id, ss_module_name');
		$this->db->from('ss_modules');
		$this->db->where('status', ACTIVE);
		$this->db->where('p_ss_module_id', $module_id);
		$this->db->order_by('sequence_no', 'ASC');
				
		$query = $this->db->get();
		$res_ss_modules = $query->result();
		return $res_ss_modules;
	}
	
	public function save_profile_details($data)
	{		
		if($data['self_registration'] == 1)
		{
			$update_profile_data = array(
											'self_registration' => INACTIVE
										);
			$this->db->where('contract_id',$data['contract_id']);
			$this->db->update('profiles',$update_profile_data);
		}
		
		$profile_data = array(
								'profile_name' => $data['profile_name'],
								'skin_id' => $data['skin_id'],
								'm_module_id' => $data['m_module_id'],
								'self_registration' => $data['self_registration'],
								'hide_main_nav' => $data['hide_main_nav'],
								'status' => ACTIVE,
								'cuser_id' => $data['user_id']);
		
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->where('profile_id',$data['profile_id']);
		$this->db->update('profiles',$profile_data);		
		
		$delete_query_str = "DELETE FROM profiles_s_modules WHERE profile_id = ? ";
		$delete_query = $this->db->query($delete_query_str, array($data['profile_id']));
		
		if($delete_query)
		{
			if(isset($data['profile_s_module_data']))
			{
				for($i = 0; $i<count($data['profile_s_module_data']); $i++)
				{
					$sub_data = array(
								'profile_id' => $data['profile_id'],
								's_module_id' => $data['profile_s_module_data'][$i],
								'cuser_id' => $data['user_id']);
					$this->db->set('cdate','NOW()', FALSE);
					$this->db->insert('profiles_s_modules', $sub_data);
				}
			}
		}
		
		$delete_query_str = "DELETE FROM profiles_ss_modules WHERE profile_id = ?"; 
		$delete_query = $this->db->query($delete_query_str, array($data['profile_id']));
		
		if($delete_query)
		{
			if(isset($data['profile_ss_module_data']))
			{
				for($i = 0; $i<count($data['profile_ss_module_data']); $i++)
				{
					$sub_sub_data = array(
								'profile_id' => $data['profile_id'],
								'ss_module_id' => $data['profile_ss_module_data'][$i],
								'cuser_id' => $data['user_id']);
					$this->db->set('cdate','NOW()', FALSE);
					$this->db->insert('profiles_ss_modules', $sub_sub_data);
				}
			}
		}
		
		if(isset($data['user_data']))
		{
			foreach($data['user_data'] as $key => $value)
			{
				$user_data_arr = array(
		   					'profile_id' => $value['profile_id'],
							'muser_id' => $data['user_id']
				);
				$this->db->set('mdate', 'NOW()', FALSE);
				$this->db->where('user_id', $value['user_id']);
				$this->db->update('users', $user_data_arr);
				
				if($data['self_registration'] == 1 && $value['profile_id'] != 0)
				{
					$del_qry = "DELETE from school_admins where user_id = ?";
					$query_status = $this->db->query($del_qry, array($value['user_id']));
				}
			}
		}
		
		$this->db->select('profile_id, profile_name');
		$this->db->from('profiles');
		$this->db->where('status', ACTIVE);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->order_by("profile_name", "asc");
		
		$query = $this->db->get();
		$res_profiles = $query->result();
		
		return $res_profiles;
	}
	
	public function delete_profile_details($data)
	{
		$update_data = array(
		  					'profile_id' => INACTIVE,
							'muser_id' => $data['user_id']
					);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('profile_id', $data['profile_id']);
		$res_update = $this->db->update('users', $update_data);

		if($res_update)
		{
			$delete_qry = "DELETE from profiles where profile_id = ". $data['profile_id'];
			$query_status = $this->db->query($delete_qry);
		}
		
		return $query_status;
	}
	
	/* Delete energy data */
	public function purge_energy_data($data)
	{
		$validate_count = 0;
		
		/* delete hh_data */
		$qry_str = "SELECT count(1) as count from hh_data where date(hh_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
		$query = $this->db->query($qry_str);
		$res_hh_data = $query->result();
		
		if($res_hh_data[0]->count)
		{
			$validate_count++;
			$qry = "DELETE from hh_data where date(hh_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
			$this->db->query($qry);
		}
		
		/* delete hh_stats_data */
		$qry_str = "SELECT count(1) as count from hh_stats_data where date(stats_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
		$query = $this->db->query($qry_str);
		$res_hh_stats_data = $query->result();		
		
		if($res_hh_stats_data[0]->count)
		{
			$validate_count++;
			$qry = "DELETE from hh_stats_data where date(stats_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
			$this->db->query($qry);
		}
		
		/* delete daily_stats_data */
		$qry_str = "SELECT count(1) as count from daily_stats_data where date(stats_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
		$query = $this->db->query($qry_str);
		$res_daily_stats_data = $query->result();		
		
		if($res_daily_stats_data[0]->count)
		{
			$validate_count++;
			$qry = "DELETE from daily_stats_data where date(stats_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
			$this->db->query($qry);
		}		
		
		$start_month = strtotime($data['start_date']);
		$end_month = strtotime($data['end_date']);
		while($start_month <= $end_month)
		{
			$mon = date("F", $start_month);
			$yer = date("Y", $start_month);
			
			$qry_str = "SELECT count(1) as count from target_data where baseline_year ='". $yer ."' and baseline_month ='". $mon ."'";
			$query = $this->db->query($qry_str);
			$res_target_data = $query->result();
		
			if($res_target_data[0]->count)
			{
				$validate_count++;
				$qry = "DELETE from target_data where baseline_year ='". $yer ."' and baseline_month ='". $mon ."'";
				$this->db->query($qry);
			}
			
			$start_month = strtotime("+1 month", $start_month);
		}
		
		$qry_str = "SELECT count(1) as count from nhh_data where date(from_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."' or date(to_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
		$query = $this->db->query($qry_str);
		$res_nhh_data = $query->result();
			
		if($res_nhh_data[0]->count)
		{
			$validate_count++;
			$qry = "DELETE from nhh_data where date(from_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."' or date(to_date) between '". $data['start_date'] ."' and '". $data['end_date'] ."'";
			$this->db->query($qry);
		}
		
		return $validate_count;
	}
	
	/* Get Contract configured users */
	public function get_users_configure_contract($data)
	{
		$qry = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join ad_profiles p on u.profile_id = p.ad_profile_id where u.customer_id = ? and u.role_id = ? and u.user_id NOT IN (Select user_id from cadmin_contract where contract_id = ?)";
		
		$query = $this->db->query($qry, array($data['customer_id'], $data['role_id'], $data['contract_id']));
		$res_available_users = $query->result();
		
		$qry = "SELECT u.user_id, u.first_name, u.last_name, u.username, COALESCE(p.profile_name, '') as profile_name from users u left join ad_profiles p on u.profile_id = p.ad_profile_id, cadmin_contract cu where u.user_id = cu.user_id and u.customer_id = ? and u.role_id = ? and cu.contract_id = ?";
		
		$query = $this->db->query($qry, array($data['customer_id'], $data['role_id'], $data['contract_id']));
		$res_selected_users = $query->result();
		
		$res_users_info[0]->available_users = $res_available_users;
		$res_users_info[0]->selected_users = $res_selected_users;
		
		return $res_users_info;
	}
	
	public function save_users_configure_contract($data)
	{
		/*$qry_res = FALSE;
		$insert_res = FALSE;
		
		if(isset($data['user_data']))
		{
			if(count($data['user_data']) > 0)
			{
				$qry = "DELETE from cadmin_contract where contract_id = ". $data['contract_id'];
				$qry_res = $this->db->query($qry);
				
				if($qry_res)
				{
					foreach($data['user_data'] as $value)
					{
						$user_data = array(
								'contract_id' => $data['contract_id'],
								'user_id' => $value,
								'cuser_id' => $data['user_id']);
						
						$this->db->set('cdate','NOW()', FALSE);
						$insert_res = $this->db->insert('cadmin_contract', $user_data);
					}
				}
			}
		}
		
		if($qry_res && $insert_res)
			return TRUE;
		else
			return FALSE;*/
		
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
	
	// To get skins list for contract
	public function get_skins($data)
	{
		$this->db->select('skin_id, skin_name');
		$this->db->from('skins');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('default', INACTIVE);
		$query = $this->db->get();
		$res_get_skins = $query->result();
		return $res_get_skins;
	}
	
	public function check_skin_duplicate($data)
	{
		$this->db->select('COUNT(*) as skin_dup_cnt');
		$this->db->from('skins');
		$this->db->where('skin_name',$data['skin_name']);
		$this->db->where('contract_id', $data['contract_id']);
		if(isset($data['skin_id']))
		{
			$this->db->where('skin_id != ', $data['skin_id']);
		}
		$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0]->skin_dup_cnt;
	}
	
	public function check_skin_profile($data)
	{
		$this->db->select('COUNT(*) as skin_cnt');
		$this->db->from('profiles');
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('skin_id', $data['skin_id']);
		$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		$check_skin_res = $query->result();
		return $check_skin_res[0]->skin_cnt;
	}
	
	public function create_skin($insert_data)
	{
		$this->db->set('cdate', 'NOW()', FALSE);
		$this->db->insert('skins', $insert_data);
		$create_skin_id = $this->db->insert_id();
		return $create_skin_id;
	}
	
	public function validate_skin($data)
	{
		$this->db->select('COUNT(*) as skin_cnt');
		$this->db->from('skins');
		$this->db->where('skin_id',$data['skin_id']);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('default', INACTIVE);
		$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		$validate_skin = $query->result();
		return $validate_skin[0]->skin_cnt;
	}
	
	// To get skins details for contract
	public function get_skin_details($data)
	{
		$this->db->select('skin_name sn,header_link_color hlc,header_link_hcolor hlhc,headings_color hc,page_link_color plc,page_link_hcolor plhc,page_bgcolor pb,left_foot_block_title lfbt,left_foot_block_d1 lfbd1,left_foot_block_11 lfbl1,left_foot_block_d2 lfbd2,left_foot_block_l2 lfbl2,left_foot_block_d3 lfbd3,left_foot_block_l3 lfbl3,left_foot_block_d4 lfbd4,left_foot_block_l4 lfbl4,right_foot_block_title rfbt,right_foot_block_d1 rfbd1,right_foot_block_11 rfbl1,right_foot_block_d2 rfbd2,right_foot_block_l2 rfbl2,right_foot_block_d3 rfbd3,right_foot_block_l3 rfbl3,right_foot_block_d4 rfbd4,right_foot_block_l4 rfbl4,left_foot_block_s1 lfbs1,left_foot_block_s2 lfbs2,left_foot_block_s3 lfbs3,left_foot_block_s4 lfbs4,right_foot_block_s1 rfbs1,right_foot_block_s2 rfbs2,right_foot_block_s3 rfbs3,right_foot_block_s4 rfbs4,footer_copy_text fct');
		$this->db->from('skins');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('skin_id',$data['skin_id']);
		$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		$get_skin_details = $query->result();
		return $get_skin_details;
	}

	/* update skin details */
	public function edit_skin($data)
	{
		$update_data = array(
								'skin_name' => $data['sn'],
								'header_link_color' => $data['hlc'],
								'header_link_hcolor' => $data['hlhc'],
								'headings_color' => $data['hc'],
								'page_link_color' => $data['plc'],
								'page_link_hcolor' => $data['plhc'],
								'page_bgcolor' => $data['pb'],
								'left_foot_block_title' => $data['lfbt'],
								'left_foot_block_d1' => $data['lfbd1'],
								'left_foot_block_11' => $data['lfbl1'],
								'left_foot_block_d2' => $data['lfbd2'],
								'left_foot_block_l2' => $data['lfbl2'],
								'left_foot_block_d3' => $data['lfbd3'],
								'left_foot_block_l3' => $data['lfbl3'],
								'left_foot_block_d4' => $data['lfbd4'],
								'left_foot_block_l4' => $data['lfbl4'],
								'right_foot_block_title' => $data['rfbt'],
								'right_foot_block_d1' => $data['rfbd1'],
								'right_foot_block_11' => $data['rfbl1'],
								'right_foot_block_d2' => $data['rfbd2'],
								'right_foot_block_l2' => $data['rfbl2'],
								'right_foot_block_d3' => $data['rfbd3'],
								'right_foot_block_l3' => $data['rfbl3'],
								'right_foot_block_d4' => $data['rfbd4'],
								'right_foot_block_l4' => $data['rfbl4'],
								'left_foot_block_s1' => $data['lfbs1'],
								'left_foot_block_s2' => $data['lfbs2'],
								'left_foot_block_s3' => $data['lfbs3'],
								'left_foot_block_s4' => $data['lfbs4'],
								'right_foot_block_s1' => $data['rfbs1'],
								'right_foot_block_s2' => $data['rfbs2'],
								'right_foot_block_s3' => $data['rfbs3'],
								'right_foot_block_s4' => $data['rfbs4'],
								'footer_copy_text' => $data['fct'],
								'muser_id' => $data['user_id']);
		
		$this->db->set('mdate', 'NOW()', FALSE);		
		$this->db->where('skin_id', $data['sid']);
		$this->db->where('contract_id', $data['contract_id']);
		$update_status = $this->db->update('skins', $update_data);
		
		$skin_res = array();
		if($update_status)
		{
			$skin_res = $this->get_skins($data);
		}
		
		return $skin_res;
	}
	
	public function delete_skin($data)
	{
		$qry = "DELETE from skins where skin_id = ". $data['sid'] ." and contract_id = ". $data['contract_id'];
		$qry_res = $this->db->query($qry);
		
		return $qry_res;
	}
	
	// Validate the Transaction Id
	public function validateTransactionId($data)
	{
		$this->db->select('COUNT(1) as count');
		$this->db->from('payment_items');
		$this->db->where('payment_id', $data['transaction_id']);
		$this->db->where('card_cash', CARD);
		$this->db->where('pay_refund', PAYMENT);
		$this->db->where('yp_code', INACTIVE);
		$this->db->where('pgtr_id !=', '');
		$query = $this->db->get();
		$chk_id = $query->result();
		$check_trans_id_cnt = $chk_id[0]->count;
		return $check_trans_id_cnt;
	}
	
	// To get payment details
	public function get_payment_details($data)
	{
		$this->db->select('pgtr_id, st.card_balance, trans_fee_status, refund_fee, p.pupil_id');
		$this->db->from('payment_items p');
		$this->db->join('students st', 'p.pupil_id = st.pupil_id');
		$this->db->join('users u', 'u.user_id = st.user_id');
		$this->db->join('contracts c', 'c.contract_id = u.contract_id');
		$this->db->where('payment_id', $data['transaction_id']);
		$this->db->where('card_cash', CARD);
		$this->db->where('pay_refund', PAYMENT);
		$this->db->where('p.status', ACTIVE);
		$this->db->where('p.yp_code', INACTIVE);
		$this->db->where('st.active', ACTIVE);
		$query = $this->db->get();
		$payment_details = $query->result();
		return $payment_details;
	}
	
	public function get_yp_error_code($yp_code)
	{
		$this->db->select('description');
		$this->db->from('yp_codes');
		$this->db->where('code', $yp_code);
		$query = $this->db->get();
		$yp_res = $query->result();
		if($yp_res)
			return $yp_res[0]->description;
		else
			return '';
	}
	
	/* To initiate card refund */
	public function initiate_card_refund($data)
	{
		$card_ref_query_status = FALSE;
		
		foreach($data as $key => $value)
		{
			$refund_data = array(
					'pupil_id' => $value['pupil_id'],
					'payment_id' => $value['payment_id'],
					'refund_ref_pid' => $value['refund_ref_pid'],
					'amount' => $value['refund_amount'],
					'transaction_fee' => $value['transaction_fee'],
					'card_cash' => CARD,
					'pay_refund' => REFUND,
					'status' => INACTIVE,
					'cuser_id' => $value['user_id']);
			
			$this->db->set('cdate', 'NOW()', FALSE);
			$card_ref_query_status = $this->db->insert('payment_items', $refund_data);
		}		
		return $card_ref_query_status;
	}
	
	public function change_card_refund_status($data)
	{	
		$pgtr = '';
		if(isset($data['pgtr_id']))
		{
			$pgtr = $data['pgtr_id'];
		}
		$refund_data = array(
					'status' => ACTIVE,
					'pgtr_id' => $pgtr,
					'yp_code' => $data['yp_code']);
		$this->db->where('payment_id', $data['payment_id']);
		$this->db->where('status', INACTIVE);
		$update_payment_status = $this->db->update('payment_items', $refund_data);
		
		if($update_payment_status && ($data['yp_code'] == 0))
		{		
			$this->db->select('pupil_id, amount');
			$this->db->from('payment_items');
			$this->db->where('payment_id', $data['payment_id']);
			$query = $this->db->get();
			$pupil_res = $query->result();
			
			foreach($pupil_res as $key => $value)
			{
				$this->update_card_balance($value->pupil_id,$value->amount,REFUND,CARD);
			}		
		}
		
		return $update_payment_status;
	}
	
	
	/* For resource management setcion...*/
	
	/* for checking the duplicate zone name */
	public function check_duplicate_zone_name($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('zone');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('zone_name',$data['zone_name']);
		$this->db->where('active',ACTIVE);
		if(!empty($data['zone_id']))
		{
			$this->db->where('zone_id !=',$data['zone_id']);
		}
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	/* for checking the duplicate zone device and serial number combination */
	public function check_duplicate_zone_device($data){
		
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('zone');
		$this->db->where('device_id',$data['device_id']);
		$this->db->where('serial_id',$data['serial_no']);
		$this->db->where('active',ACTIVE);
		if(!empty($data['zone_id']))
		{
			$this->db->where('zone_id !=',$data['zone_id']);
		}
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	/* For adding zone */
	public function add_edit_zone($data)
	{
		//Save the zone information...
		if(!empty($data['zone_id']))
		{
			$zone_id = $data['zone_id'];
			//Update information....
			$data = array(
					'zone_name' => $data['zone_name'],
					'description' => $data['description'] ,
					'device_id' => $data['device_id'] ,
					'serial_id' => $data['serial_no'] ,
					'high_threshold' => $data['high_threshold'] ,
					'low_threshold' => $data['low_threshold'] ,
					'network_id' => $data['network_id'] ,
					'network_desc' => $data['network_desc'] ,
					'timeout' => $data['timeout'],
					'tagread_timeout' => $data['tagtimeout'],
					'muser_id' =>$data['user_id'],
			);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('zone_id', $zone_id);
			$this->db->where('active', ACTIVE);
			$edit_zone = $this->db->update('zone', $data);
			
			
		} else {
			//Insert information
			
			$data = array(
					'contract_id' => $data['contract_id'],
					'zone_name' => $data['zone_name'],
					'description' => $data['description'] ,
					'device_id' => $data['device_id'] ,
					'serial_id' => $data['serial_no'] ,
					'high_threshold' => $data['high_threshold'] ,
					'low_threshold' => $data['low_threshold'] ,
					'network_id' => $data['network_id'] ,
					'network_desc' => $data['network_desc'] ,
					'timeout' => $data['timeout'],
					'tagread_timeout' => $data['tagtimeout'],
					'last_count' =>0,
					'active' =>ACTIVE,
					'cuser_id' =>$data['user_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_zone = $this->db->insert('zone', $data);
			$zone_id = $this->db->insert_id();
			//return $zone_id;
		}
		
		$this->db->select('zone_id, last_contact, last_count, firmware');
		$this->db->from('zone');
		$this->db->where('zone_id',$zone_id);
		$query = $this->db->get();
		$zone_details_res = $query->result();
		return $zone_details_res;
		
	}
	/* for deleting the zone */
	public function delete_zone($data){
		//Update information....
		$data = array(
				'zone_id'=> $data['zone_id'],
				'active' => INACTIVE,
				'muser_id' => $data['user_id']		
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('zone_id', $data['zone_id']);
		$this->db->where('active', ACTIVE);
		$edit_zone = $this->db->update('zone', $data);
	}
	
	public function check_duplicate_asset($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('asset');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('tag_number',$data['tag_no']);
		$this->db->where('active',ACTIVE);
		if(!empty($data['asset_id']))
		{
			$this->db->where('asset_id !=',$data['asset_id']);
		}
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function add_edit_asset($data)
	{
		//Save the asset information...
		if(!empty($data['asset_id']))
		{
			$asset_id = $data['asset_id'];
			//Update information....
			$update_data = array(
					'tag_number' => $data['tag_no'],
					'description' => $data['description'] ,
					'asset_type_id' => $data['asset_type_id'] ,
					'asset_other_desc' => $data['asset_other_desc'] ,
					'oem_code' => $data['oem_code'] ,
					'timeout' => $data['timeout'] ,
					'muser_id' =>$data['user_id'],
			);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('asset_id', $data['asset_id']);
			$this->db->where('contract_id', $data['contract_id']);
			$this->db->where('active', ACTIVE);
			$edit_asset = $this->db->update('asset', $update_data);
			
			
		} else {
			//Insert information
			
			$data = array(
					'tag_number' => $data['tag_no'],
					'contract_id' => $data['contract_id'],
					'description' => $data['description'] ,
					'asset_type_id' => $data['asset_type_id'] ,
					'asset_other_desc' => $data['asset_other_desc'] ,
					'oem_code' => $data['oem_code'] ,
					'timeout' => $data['timeout'] ,
					'active' => ACTIVE,
					'cuser_id' =>$data['user_id'],
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_asset = $this->db->insert('asset', $data);
			$asset_id = $this->db->insert_id();
			//return $asset_id;
		}
		
		$this->db->select('asset_id, battery_status');
		$this->db->from('asset');
		$this->db->where('asset_id',$asset_id);
		$query = $this->db->get();
		$asset_details_res = $query->result();
		return $asset_details_res;
	}
	
	/*get the asset detials */
	public function get_asset_details($data)
	{
		$this->db->select('tag_number, description, asset_type_id, asset_other_desc, oem_code, timeout, battery_status');
		$this->db->from('asset');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('asset_id',$data['asset_id']);
		$this->db->where('active',ACTIVE);
		$query = $this->db->get();
		$asset_res = $query->result();
		return $asset_res;
	}
	
	public function get_asset_read_details($data)
	{
		$this->db->select("DATE_FORMAT(cdate,'%d/%c/%Y %H:%i:%s') as read_date, (SELECT zone_name FROM zone WHERE zone_id = ats.zone_id) as zone_name,read_point", FALSE);
		$this->db->from('asset_tracking_status ats');
		$this->db->where('asset_id',$data['asset_id']);
		$this->db->order_by('cdate','desc');
		$query = $this->db->get();
		$asset_read_res = $query->result();
		return $asset_read_res;
	}
	
	/* for deleting asset */
	public function delete_asset($data){
		//Update information....
		$update_data = array(
				'active' => INACTIVE,
				'muser_id' => $data['user_id']		
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('asset_id', $data['asset_id']);
		$this->db->where('active', ACTIVE);
		$delete_asset = $this->db->update('asset', $update_data);
	}
        
        public function get_navigation_form_details()
        {
            $this->db->select('s_module_id as mid, s_module_name as mn');
            $this->db->from('s_modules');
            $this->db->where('digital_pen_status' ,ACTIVE);
            $query = $this->db->get();
            $navloc_data = $query->result();
            
            $this->db->select('digital_form_type_id as did, type_name as tn');
            $this->db->from('digital_form_type');
            $query = $this->db->get();
            $dgform_data = $query->result();
            
            $addapp_res[0] = new stdClass();
            $addapp_res[0]->navLoc = $navloc_data;
            $addapp_res[0]->formTyp = $dgform_data;
            return $addapp_res;
        }
        
        public function get_digital_app_details($data)
        {
            $this->db->select('digital_app_id as da_id, app_name as an, label as lbl, description as dsc, timeout as tout, template_path as tp');
            $this->db->from('digital_app');
            $this->db->where('status' ,ACTIVE);
            $this->db->where('contract_id',$data['contract_id']);
            $this->db->order_by('app_name', 'asc');
            $query = $this->db->get();
            $app_data = $query->result();
            $appres_res[0] = new stdClass();
            $appres_res[0]->app_data = $app_data;
            return $appres_res;
        }
        
        public function delete_digital_zip_file($data)
        {
        	$insert_data = array(
					'template_path' => '' ,
					'muser_id' =>$data['user_id'],
        	);
        	$this->db->set('mdate', 'NOW()', FALSE);
        	$this->db->where('contract_id', $data['contract_id']);
        	$this->db->where('digital_app_id', $data['app_id']);
        	$this->db->where('status', ACTIVE);
        	$edit_app = $this->db->update('digital_app', $insert_data);
        	return $edit_app;
        }
        
        public function get_digital_app_info($data)
        {
        	$this->db->select('s_module_id as mid, s_module_name as mn');
        	$this->db->from('s_modules');
        	$this->db->where('digital_pen_status' ,ACTIVE);
        	$query = $this->db->get();
        	$navloc_data = $query->result();

        	$this->db->select('digital_form_type_id as did, type_name as tn');
        	$this->db->from('digital_form_type');
        	$query = $this->db->get();
        	$dgform_data = $query->result();
			
        	$qry = "SELECT digital_app_id as da_id, app_name as an, label as lbl, description as dsc, timeout as tout, navigation_location as nl, form_type_id as fid, template_path as tp, '". $data['preview_link'] ."' as link, disallow_forms as df, LPAD(unexp_start_hour, 2, '0') as st_hr, LPAD(unexp_start_min, 2, '0') as st_min, LPAD(unexp_end_hour, 2, '0') as end_hr, unexp_end_min as end_min FROM digital_app 
        			WHERE digital_app_id = ". $data['app_id'];
        	
        	$app_query= $this->db->query($qry);
        	$app_data = $app_query->result();
        	
        	/*$this->db->select('digital_app_id as da_id, app_name as an, label as lbl, description as dsc, timeout as tout, navigation_location as nl, form_type_id as fid, template_path as tp, '. $data['preview_link'] .' as link');
        	$this->db->from('digital_app');
        	$this->db->where('digital_app_id' ,$data['app_id']);
        	$query = $this->db->get();
        	$app_data = $query->result();*/

        	$app_res[0] = new stdClass();
        	$app_res[0]->navLoc = $navloc_data;
        	$app_res[0]->formTyp = $dgform_data;
        	$app_res[0]->appdata = $app_data;
        	return $app_res;
        }
        
        public function check_digital_form_duplicate($data)
        {
        	$this->db->select('COUNT(form_type_id) as cnt ');
        	$this->db->from('digital_app d');
        	$this->db->where('contract_id', $data['contract_id']);
        	$this->db->where('form_type_id', $data['frm_type']);
        	$this->db->where('navigation_location', $data['nav_loc']);
        	if(!empty($data['app_id']))
        	{
        		$this->db->where('digital_app_id !=', $data['app_id']);
        	}
        	$query = $this->db->get();

        	$check_dup_res = $query->result();
        	if($check_dup_res[0]->cnt == 0)
        		return TRUE;
        	else
        		return FALSE;
        }


        public function check_duplicate_digital_app_name($data)
        {
        	$this->db->select('COUNT(app_name) as cnt ');
        	$this->db->from('digital_app d');
        	$this->db->join('contracts c', 'd.contract_id = c.contract_id');
        	$this->db->where('d.contract_id',$data['contract_id']);
        	$this->db->where('c.contract_id',$data['contract_id']);
        	$this->db->where('c.customer_id', $data['customer_id']);
        	$this->db->where('d.app_name',$data['app_name']);        	
        	if(!empty($data['app_id']))
        	{
        		$this->db->where('d.digital_app_id !=',$data['app_id']);
        	}
        	$query = $this->db->get();

        	$check_dup_res = $query->result();
        	return $check_dup_res[0];
        }
        
        public function add_edit_digital_apps($data)
        {
        	$temp_path = '';
        	if($data['upld_tplate'] == 1)
        	{
				$temp_path = $data['template_name'];
        	}
        	else if($data['upld_tplate'] == 0 && $data['template_name'] != '')
        	{
        		$temp_path = $data['template_name'];
        	}
        	
        	//Save the zone information...
        	if(!empty($data['app_id']))
        	{
        		$app_id = $data['app_id'];
        		//Update information....
        		$data = array(
					'contract_id' => $data['contract_id'],
					'app_name' => $data['app_name'],
                                        'label' => $data['app_label'],
                                        'timeout' => $data['timeout'],
					'description' => $data['description'] ,
					'navigation_location' => $data['nav_loc'] ,
					'form_type_id' => $data['frm_type'] ,
					'template_path' => $temp_path ,
        			'disallow_forms' => $data['disallow_forms'],
        			'unexp_start_hour' => $data['start_hour'],
        			'unexp_start_min' => $data['start_min'],
        			'unexp_end_hour' => $data['end_hour'],
        			'unexp_end_min' => $data['end_min'],
					'status' =>ACTIVE,
					'muser_id' =>$data['user_id'],
        		);
        		$this->db->set('mdate', 'NOW()', FALSE);
        		$this->db->where('digital_app_id', $app_id);
        		$this->db->where('status', ACTIVE);
        		$edit_zone = $this->db->update('digital_app', $data);
				return $app_id;
        	} 
        	else 
        	{
        		$data = array(
					'contract_id' => $data['contract_id'],
					'app_name' => $data['app_name'],
                                        'label' => $data['app_label'],
                                        'timeout' => $data['timeout'],
					'description' => $data['description'] ,
					'navigation_location' => $data['nav_loc'] ,
					'form_type_id' => $data['frm_type'] ,
					'template_path' => $temp_path ,
        			'disallow_forms' => $data['disallow_forms'],
        			'unexp_start_hour' => $data['start_hour'],
        			'unexp_start_min' => $data['start_min'],
        			'unexp_end_hour' => $data['end_hour'],
        			'unexp_end_min' => $data['end_min'],
					'status' =>ACTIVE,
					'cuser_id' =>$data['user_id']
        		);
        		$this->db->set('cdate', 'NOW()', FALSE);
        		$add_App = $this->db->insert('digital_app', $data);
        		return $this->db->insert_id();
        	}
        }
        
	public function delete_digital_app_details($data)
	{
		$delete_qry = "DELETE from digital_app where digital_app_id = ". $data['app_id'];
		$query_status = $this->db->query($delete_qry);
		return $query_status;
	}
	
	public function check_duplicate_digital_pen($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('digital_pen');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('pen_id',$data['pen_id']);
		$this->db->where('status',ACTIVE);
		if(!empty($data['dp_id']))
		{
			$this->db->where('digital_pen_id !=',$data['dp_id']);
		}
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function add_edit_digital_pen($data)
	{
		//Save the digital_pen information...
		if(!empty($data['dp_id']))
		{
			$digital_pen_id = $data['dp_id'];
			//Update information....
			$update_data = array(
					'pen_id' => $data['pen_id'],
					'description' => $data['description'] ,
					'label_name' => $data['label'] ,
					'timeout' => $data['timeout'] ,
					'muser_id' => $data['user_id'],
			);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('digital_pen_id', $digital_pen_id);
			$this->db->where('contract_id', $data['contract_id']);
			$this->db->where('status', ACTIVE);
			$this->db->update('digital_pen', $update_data);
			return $digital_pen_id;
		} 
		else 
		{
			//Insert information
			$insert_data = array(
					'pen_id' => $data['pen_id'],
					'description' => $data['description'] ,
					'label_name' => $data['label'] ,
					'timeout' => $data['timeout'] ,
					'contract_id' => $data['contract_id'],
					'status' => ACTIVE,
					'cuser_id' =>$data['user_id'],
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('digital_pen', $insert_data);
			$digital_pen_id = $this->db->insert_id();
			return $digital_pen_id;
		}
	}
	
	/*get the digital_pen detials */
	public function get_digital_pen_details($data)
	{
		$this->db->select('digital_pen_id as dp_id, pen_id, label_name as label, description, timeout');
		$this->db->from('digital_pen');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('digital_pen_id',$data['dp_id']);
		$this->db->where('status',ACTIVE);
		$query = $this->db->get();
		$digital_pen_res = $query->result();
		return $digital_pen_res;
	}
	
	/*get the digital_pen list */
	public function get_digital_pens($data)
	{
		$this->db->select('digital_pen_id dp_id, pen_id pid, label_name ln, description pd, timeout tout');
		$this->db->from('digital_pen');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('status',ACTIVE);
		$this->db->order_by('pen_id', 'asc');
		$query = $this->db->get();
		$digital_pen_res = $query->result();
		return $digital_pen_res;
	}
	
	/* for deleting digital_pen */
	public function delete_digital_pen($data)
	{
		//Update information....
		$update_data = array(
				'status' => INACTIVE,
				'muser_id' => $data['user_id']		
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('digital_pen_id', $data['dp_id']);
		$this->db->where('status', ACTIVE);
		$this->db->update('digital_pen', $update_data);
	}
	
	/* export pupil balances as resport */
	public function export_pupil_balances($data)
	{
		$this->db->select("a.school_name, c.fname, c.mname, c.lname, b.year_label, b.class1_name, b.class2_name, b.class3_name, b.class4_name, b.class5_name, b.class6_name, c.class_col, c.status, c.pupil_dup, c.fsm, c.adult, c.pupil_id, c.cash_balance, c.card_balance, u.username, t.data_value, u.first_name, u.last_name, u.user_email, u.telephone, u.work_telephone, u.mobile_number, u.mail_notification, u.sms_notification");
		$this->db->from("students c");
		$this->db->join("school_classes b", "b.school_classes_id = c.school_classes_id");
		$this->db->join("schools a", "a.school_id = b.school_id");
		$this->db->join("users u", "u.user_id = c.user_id AND u.contract_id = ". $data['contract_id'], "left");
		$this->db->join("data_value t", "t.data_value_id = u.title_id", "left");
		$this->db->where("a.contract_id", $data['contract_id']);
		$this->db->where("c.active", ACTIVE);
		$this->db->where("(c.cash_balance > 0 OR c.card_balance > 0)");
		$this->db->order_by("a.school_name", "asc");
		$this->db->order_by("c.lname", "asc");
		$this->db->order_by("c.fname", "asc");
		$query = $this->db->get();
		$res_pupils = $query->result();
		
		return $res_pupils;
	}
	
	/* Get quality audit accounts list */
	public function get_qa_accounts($data)
	{
		$acc_qry = "SELECT qa_account_id aid, account_name an, account_code ac, description ad, last_status als, CASE WHEN mdate IS NULL THEN cdate ELSE mdate END AS alm, (SELECT COUNT(1) FROM qa_account_group ag WHERE ag.qa_account_id = aid AND is_default = 0 and status = ". ACTIVE .") as acg
						FROM qa_account a
							WHERE contract_id = ". $data['contract_id'] ."
							AND active = ". ACTIVE ."
							ORDER BY
							CASE 
								WHEN mdate IS NULL THEN cdate 
								ELSE mdate 
							END DESC";
		
		$account_query= $this->db->query($acc_qry);
		$qa_accounts_res = $account_query->result();
		return $qa_accounts_res;
		
		/*$this->db->select('qa_account_id aid, account_name an, account_code ac, description ad, last_status als, mdate alm');
		$this->db->select('(SELECT COUNT(1) FROM qa_account_group ag WHERE ag.qa_account_id = aid AND is_default = 0) as acg', FALSE);
		$this->db->from('qa_account a');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('active',ACTIVE);
		$this->db->order_by('mdate', 'desc');
		$query = $this->db->get();
		$qa_accounts_res = $query->result();
		return $qa_accounts_res;*/
	}
	
	public function get_qa_account_details($data)
	{
		$this->db->select('qa_account_id aid,account_name, account_code, description, sync_status, sync_startdate, last_status');
		$this->db->from('qa_account');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('qa_account_id',$data['account_id']);
		$this->db->where('active',ACTIVE);
		$query = $this->db->get();
		$qa_accounts_res = $query->result();
		return $qa_accounts_res;
	}
	
	public function get_qa_user_access_details($data)
	{
		if(isset($data['account_id']))
		{
			$this->db->select('u.user_id, username, first_name, last_name, create_sla_report, run_adhoc_report, view_sla_report');
			$this->db->from('users u');
			$this->db->join('qa_user_access ua', 'ua.user_id = u.user_id AND ua.qa_account_id = '. $data['account_id'], 'left');
			$this->db->where('u.contract_id', $data['contract_id']);
			$query = $this->db->get();
			$res_pupils = $query->result();
			return $res_pupils;
		}
		else
		{
			$this->db->select('user_id, username, first_name, last_name');
			$this->db->from('users u');
			$this->db->where('u.contract_id', $data['contract_id']);
			$query = $this->db->get();
			$res_pupils = $query->result();
			return $res_pupils;
		}
	}
	
	public function check_duplicate_qa_account($data)
	{
		$this->db->select('COUNT(1) as acc_dup_count');
		$this->db->from('qa_account');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('account_name',$data['account_name']);
        $this->db->where('active',ACTIVE);
		if(isset($data['account_id']))
			$this->db->where('qa_account_id != ', $data['account_id']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function check_duplicate_qa_account_code($data)
	{
		$this->db->select('COUNT(1) as acc_dup_code_count');
		$this->db->from('qa_account');
		//$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('account_code',$data['code']);
		if(isset($data['account_id']))
			$this->db->where('qa_account_id != ', $data['account_id']);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	/* Add or Edit QA Account details */
	public function add_edit_qa_account($data)
	{
		if($data['account_id'] != '')
		{
			$update_acc_data = array(
										'account_name' => $data['account_name'],
										'account_code' => $data['code'],
										'description' => $data['desc'],
										'sync_status' => $data['sync_status'],
										'sync_startdate' => $data['start_date'],
										'muser_id' =>$data['user_id']										
			);

			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->set('last_updated', 'NOW()', FALSE);
			$this->db->where('qa_account_id', $data['account_id']);
			$this->db->where('contract_id', $data['contract_id']);
			$update_qa_account = $this->db->update('qa_account', $update_acc_data);	
			
			if(isset($data['user_data']) && $data['account_id'] != '')
			{
				$delete_user_access = "DELETE FROM qa_user_access WHERE qa_account_id = ". $data['account_id'];
				$qry_res = $this->db->query($delete_user_access);
				foreach($data['user_data'] as $key => $value)
				{
					$insert_user_data = array(
										'user_id' => $value['user_id'],
										'qa_account_id' => $data['account_id'],
										'create_sla_report' => $value['cr_sla'],
										'run_adhoc_report' => $value['ad_hoc'],
										'view_sla_report' => $value['v_sla'],
										'cuser_id' =>$data['user_id']
					);

					$this->db->set('cdate', 'NOW()', FALSE);
					$insert_user_access = $this->db->insert('qa_user_access', $insert_user_data);
				}
			}
			
			if($data['sync_status'] != '' && $data['start_date'] != '' && isset($data['current_date']))
			{
				$pay_data = array('account_code' => $data['code'], 'start_date' => $data['start_date'], 'end_date' => $data['current_date']);
				$payload_json = json_encode($pay_data);
				
				$job_data = array(
					'job_type_id' => ACC_SYNC_ID,
					'job_batch_type_id' => ACC_SYNC_TYPE_ID,
					'payload_xml' => $payload_json,
					'job_status' => INACTIVE,
					'cuser_id' => $data['user_id']);

				$this->db->set('cdate', 'NOW()', FALSE);
				$job_qry_status = $this->db->insert('jobs', $job_data);
			}
			return $update_qa_account;
		}
		else
		{
			$account_id = '';
			$insert_acc_data = array(
										'contract_id' => $data['contract_id'],
										'account_name' => $data['account_name'],
										'account_code' => $data['code'],
										'description' => $data['desc'],
										'sync_status' => $data['sync_status'],
										'sync_startdate' => $data['start_date'],
										'active' => ACTIVE,
										'cuser_id' =>$data['user_id']
			);

			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->set('last_updated', 'NOW()', FALSE);
			$insert_qa_account = $this->db->insert('qa_account', $insert_acc_data);			
			$account_id = $this->db->insert_id();
			
			if(isset($data['current_date']) && $data['sync_status'] == 1)
			{
				$pay_data = array('account_code' => $data['code'], 'start_date' => $data['start_date'], 'end_date' => $data['current_date']);
				$payload_json = json_encode($pay_data);
				
				$job_data = array(
					'job_type_id' => ACC_SYNC_ID,
					'job_batch_type_id' => ACC_SYNC_TYPE_ID,
					'payload_xml' => $payload_json,
					'job_status' => INACTIVE,
					'cuser_id' => $data['user_id']);

				$this->db->set('cdate', 'NOW()', FALSE);
				$job_qry_status = $this->db->insert('jobs', $job_data);
			}
			
			if(count($data['user_data']) > 0 && $account_id != '')
			{
				foreach($data['user_data'] as $key => $value)
				{
					$insert_user_data = array(
										'user_id' => $value['user_id'],
										'qa_account_id' => $account_id,
										'create_sla_report' => $value['cr_sla'],
										'run_adhoc_report' => $value['ad_hoc'],
										'view_sla_report' => $value['v_sla'],
										'cuser_id' =>$data['user_id']
					);

					$this->db->set('cdate', 'NOW()', FALSE);
					$insert_user_access = $this->db->insert('qa_user_access', $insert_user_data);
				}
			}
			
			$insert_def_data = array(
								'qa_account_id' => $account_id,
								'group_name' => 'default',
								'red_from' => '0',
								'red_to' => '10',
								'amber_from' => '11',
								'amber_to' => '30',
								'green_from' => '81',
								'green_to' => '100',
								'purple_from' => '31',
								'purple_to' => '50',
								'blue_from' => '51',
								'blue_to' => '80',
								'is_default' => ACTIVE,
								'status' => ACTIVE,
								'cuser_id' =>$data['user_id']
			);

			$this->db->set('cdate', 'NOW()', FALSE);
			$insert_user_access = $this->db->insert('qa_account_group', $insert_def_data);
			return $account_id;
		}
	}
	
	public function delete_qa_account($data)
	{
		$update_acc_data = array(
									'active' => INACTIVE
								);

		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('qa_account_id', $data['account_id']);
		$this->db->where('contract_id', $data['contract_id']);
		$update_qa_account = $this->db->update('qa_account', $update_acc_data);
		
		if($update_qa_account)
		{
			$delete_user_access = "DELETE FROM qa_user_access WHERE qa_account_id = ". $data['account_id'];
			$qry_res = $this->db->query($delete_user_access);
		}
		return $update_qa_account;
	}
	
	/* Get quality audit groups list */
	public function get_qa_groups($data)
	{
		$this->db->select('ag.qa_account_group_id gid, ag.group_name gn, GROUP_CONCAT(pi.point_indicator_name) pin, ag.mdate alm');
		$this->db->from('qa_account_group ag');
		$this->db->join('qa_account_group_point_indicator agpi', 'agpi.qa_account_group_id = ag.qa_account_group_id', 'left');
		$this->db->join('qa_point_indicator pi', 'pi.qa_point_indicator_id = agpi.qa_point_indicator_id AND pi.qa_account_id = '. $data['account_id'] .'', 'left');
		$this->db->where('ag.qa_account_id',$data['account_id']);
		$this->db->where('ag.status',ACTIVE);
		$this->db->where('ag.is_default',INACTIVE);
		$this->db->group_by('ag.qa_account_group_id');
		$this->db->order_by('ag.mdate', 'desc');
		$query = $this->db->get();
		$qa_groups_res = $query->result();
		return $qa_groups_res;
	}
	
	public function get_qa_group_indicators($data)
	{
		$qa_ind_res = new stdClass();
		if($data['group_id'] != 0)
		{			
			$this->db->select('pi.qa_point_indicator_id ind_id, point_indicator_name ind_name, point_value p_val, group_name gr_name, agpi.sequence_no');
			$this->db->from('qa_point_indicator pi');
			$this->db->join('qa_account_group_point_indicator agpi', 'pi.qa_point_indicator_id = agpi.qa_point_indicator_id');
			$this->db->join('qa_account_group ag', 'ag.qa_account_group_id = agpi.qa_account_group_id');
			$this->db->where('pi.qa_account_id', $data['account_id']);
			$this->db->where('ag.qa_account_id', $data['account_id']);
			$this->db->where('ag.qa_account_group_id', $data['group_id']);
			$this->db->where('is_default', INACTIVE);
            $this->db->order_by('agpi.sequence_no');
			$query = $this->db->get();
			$selected_qa_group_ind_res = $query->result();			
			
			$this->db->select('pi.qa_point_indicator_id ind_id, ag.qa_account_group_id, point_indicator_name ind_name, point_value p_val, group_name gr_name');
			$this->db->from('qa_point_indicator pi');
			$this->db->join('qa_account_group_point_indicator agpi', 'pi.qa_point_indicator_id = agpi.qa_point_indicator_id', 'left');
			$this->db->join('qa_account_group ag', 'ag.qa_account_group_id = agpi.qa_account_group_id', 'right');
			$this->db->where('pi.qa_account_id', $data['account_id']);
			$this->db->where('ag.qa_account_id', $data['account_id']);
			$this->db->where('ag.qa_account_group_id != ', $data['group_id']);
			$query = $this->db->get();
			$available_qa_group_ind_res = $query->result();
			
			$qa_ind_res->selected_res = $selected_qa_group_ind_res;
			$qa_ind_res->available_res = $available_qa_group_ind_res;
		}
		else
		{
			$this->db->select('pi.qa_point_indicator_id ind_id, point_indicator_name ind_name, point_value p_val, group_name gr_name');
			$this->db->from('qa_point_indicator pi');
			$this->db->join('qa_account_group_point_indicator agpi', 'pi.qa_point_indicator_id = agpi.qa_point_indicator_id', 'left');
			$this->db->join('qa_account_group ag', 'ag.qa_account_group_id = agpi.qa_account_group_id', 'left');
			$this->db->where('pi.qa_account_id', $data['account_id']);
			$this->db->where('ag.qa_account_id', $data['account_id']);
			$query = $this->db->get();
			$available_qa_group_ind_res = $query->result();
			$qa_ind_res->available_res = $available_qa_group_ind_res;
		}
		return $qa_ind_res;
	}
	
	public function get_qa_group_details($data)
	{
		$this->db->select('group_name gr_name, red_from, red_to, amber_from, amber_to, green_from, green_to, purple_from, purple_to, blue_from, blue_to');
		$this->db->from('qa_account_group');
		$this->db->where('qa_account_group_id', $data['group_id']);
		$this->db->where('qa_account_id', $data['account_id']);
		$this->db->where('is_default', INACTIVE);
		$query = $this->db->get();
		$group_res = $query->result();
		return $group_res;
	}
	
	public function check_duplicate_qa_groups($data)
	{
		$this->db->select('COUNT(1) as group_dup_count');
		$this->db->from('qa_account_group');
		$this->db->where('qa_account_id',$data['account_id']);
		$this->db->where('group_name',$data['group_name']);
		if($data['group_id'] != 0)
		{
			$this->db->where('qa_account_group_id !=',$data['group_id']);
		}
		$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		$check_dup_res = $query->result();
		return $check_dup_res[0];
	}
	
	public function add_edit_qa_groups($data)
	{
		// Edit Group Information
		if($data['group_id'] != 0)
		{
			$red_from = "ag.red_from =". $data['red_from'];
			if($data['red_from'] == NULL)
			$red_from = "ag.red_from IS NULL";
				
			$red_to = "ag.red_to = ". $data['red_to'];
			if($data['red_to'] == NULL)
			$red_to = "ag.red_to IS NULL";

			$amber_from = "ag.amber_from = ". $data['amber_from'];
			if($data['amber_from'] == NULL)
			$amber_from = "ag.amber_from IS NULL";

			$amber_to = "ag.amber_to = ". $data['amber_to'];
			if($data['amber_to'] == NULL)
			$amber_to = "ag.amber_from IS NULL";

			$green_from = "ag.green_from = ". $data['green_from'];
			if($data['green_from'] == NULL)
			$green_from = "ag.green_from IS NULL";

			$green_to = "ag.green_to = ". $data['green_to'];
			if($data['green_to'] == NULL)
			$green_to = "ag.amber_from IS NULL";

			$purple_from = "ag.purple_from = ". $data['purple_from'];
			if($data['purple_from'] == NULL)
			$purple_from = "ag.green_to IS NULL";

			$purple_to = "ag.purple_to = ". $data['purple_to'];
			if($data['purple_to'] == NULL)
			$purple_to = "ag.purple_to IS NULL";

			$blue_from = "ag.blue_from = ". $data['blue_from'];
			if($data['blue_from'] == NULL)
			$blue_from = "ag.blue_from IS NULL";

			$blue_to = "ag.blue_to = ". $data['blue_to'];
			if($data['blue_to'] == NULL)
			$blue_to = "ag.blue_to IS NULL";
				
			$select_ind = '';
			$select_ind_count = 0;
				
			if(isset($data['select_point_ind']) && $data['select_point_ind'] != '')
			{
				$select_ind_count = count($data['select_point_ind']);
				foreach($data['select_point_ind'] as $key => $value)
				{
					if($select_ind == '')
					$select_ind = $value['ind_id'];
					else
					$select_ind = $select_ind . ', '. $value['ind_id'];
				}
			}
				
			$remove_ind = '';
			if(isset($data['remove_point_ind']) && $data['remove_point_ind'] != '')
			{
				foreach($data['remove_point_ind'] as $key => $value)
				{
					if($remove_ind == '')
					$remove_ind = $value;
					else
					$remove_ind = $remove_ind . ', '. $value;
				}
			}
				
			$qry = "SELECT COUNT(1) as cnt FROM qa_account_group ag
						WHERE 
							". $red_from ."
							AND ". $red_to ."
							AND ". $amber_from ."
							AND ". $amber_to ."
							AND ". $green_from ."
							AND ". $green_to ."
							AND ". $purple_from ."
							AND ". $purple_to ."
							AND ". $blue_from ."
							AND ". $blue_to ."
							AND ag.qa_account_group_id = ". $data['group_id'];
				
			$chk_rag_query= $this->db->query($qry);
			$chk_rag_res = $chk_rag_query->result();
			$chk_rag = $chk_rag_res[0]->cnt;
				
			$ind_qry = "SELECT CASE WHEN (COUNT(1) - ". $select_ind_count .") = 0 THEN 1 ELSE 0 END AS diff
							FROM qa_account_group_point_indicator
								WHERE qa_account_group_id = ". $data['group_id'] ." 
										AND qa_point_indicator_id IN ('". $select_ind ."')";
				
			$chk_ind_query= $this->db->query($ind_qry);
			$chk_ind_res = $chk_ind_query->result();
			$chk_ind = $chk_ind_res[0]->diff;
				
			$update_data = array(
								'group_name' => $data['group_name'],
								'red_from' => $data['red_from'],
								'red_to' => $data['red_to'],
								'amber_from' => $data['amber_from'],
								'amber_to' => $data['amber_to'],
								'green_from' => $data['green_from'],
								'green_to' => $data['green_to'],
								'purple_from' => $data['purple_from'],
								'purple_to' => $data['purple_to'],
								'blue_from' => $data['blue_from'],
								'blue_to' => $data['blue_to'],
								'muser_id' =>$data['user_id']
			);
				
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('qa_account_group_id', $data['group_id']);
			$this->db->where('qa_account_id', $data['account_id']);
			$this->db->where('is_default', INACTIVE);
			$this->db->where('status', ACTIVE);
			$edit_qa_group = $this->db->update('qa_account_group', $update_data);
				
			$edit_qa_group = TRUE;
			if($edit_qa_group)
			{
				$this->db->select('ag.qa_account_group_id group_id');
				$this->db->from('qa_account_group_point_indicator gpi');
				$this->db->join('qa_account_group ag', 'ag.qa_account_group_id = gpi.qa_account_group_id');
				$this->db->where('ag.qa_account_id', $data['account_id']);
				$this->db->where('ag.is_default', ACTIVE);
				$query = $this->db->get();
				$def_res = $query->result();
				
				if((isset($data['select_point_ind']) || isset($data['remove_point_ind'])) && (count($def_res) > 0))
				{
					$delete_point_ind_qry = "DELETE FROM qa_account_group_point_indicator WHERE qa_account_group_id = ". $data['group_id'];
					$qry_res = $this->db->query($delete_point_ind_qry);
				}
					
				if(isset($data['select_point_ind']) && (count($def_res) > 0))
				{
					foreach($data['select_point_ind'] as $key => $value)
					{
						if(count($def_res) > 0)
						{
							$delete_poing_ind_qry = "DELETE FROM qa_account_group_point_indicator WHERE qa_account_group_id = ". $def_res[0]->group_id ." and qa_point_indicator_id = ". $value['ind_id'];
							$qry_res = $this->db->query($delete_poing_ind_qry);
						}
							
						$insert_ind_data = array(
										'qa_account_group_id' => $data['group_id'],
										'qa_point_indicator_id' => $value['ind_id'],
										'sequence_no' => $value['seq_no'],
										'cuser_id' =>$data['user_id']
						);

						$this->db->set('cdate', 'NOW()', FALSE);
						$insert_qa_group = $this->db->insert('qa_account_group_point_indicator', $insert_ind_data);
					}
				}

				if(isset($data['remove_point_ind']) && (count($def_res) > 0))
				{
					$seq_no = 0;

					foreach($data['remove_point_ind'] as $key => $value)
					{
						$seq_no++;
						$insert_ind_data = array(
										'qa_account_group_id' => $def_res[0]->group_id,
										'qa_point_indicator_id' => $value,
										'sequence_no' => $seq_no,
										'muser_id' =>$data['user_id']
						);

						$this->db->set('cdate', 'NOW()', FALSE);
						$insert_qa_group = $this->db->insert('qa_account_group_point_indicator', $insert_ind_data);
					}
				}

				if($chk_rag == 0 || $chk_ind == 0)
				{
					$delete_sla_grp_qry = "DELETE FROM qa_sla_report_groups WHERE qa_account_group_id = ". $data['group_id'];
					$qry_res = $this->db->query($delete_sla_grp_qry);
				}
			}
			return $edit_qa_group;
		}
		else
		{
			// Insert group information
			$insert_data = array(
								'qa_account_id' => $data['account_id'],
								'group_name' => $data['group_name'],
								'red_from' => $data['red_from'],
								'red_to' => $data['red_to'],
								'amber_from' => $data['amber_from'],
								'amber_to' => $data['amber_to'],
								'green_from' => $data['green_from'],
								'green_to' => $data['green_to'],
								'purple_from' => $data['purple_from'],
								'purple_to' => $data['purple_to'],
								'blue_from' => $data['blue_from'],
								'blue_to' => $data['blue_to'],
								'is_default' => INACTIVE,
								'status' => ACTIVE,
								'cuser_id' =>$data['user_id'],
								'muser_id' =>$data['user_id']
			);

			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->set('mdate', 'NOW()', FALSE);
			$insert_qa_group = $this->db->insert('qa_account_group', $insert_data);
				
			$group_id = $this->db->insert_id();
				
			if($insert_qa_group)
			{
				foreach($data['select_point_ind'] as $key => $value)
				{
					$this->db->select('ag.qa_account_group_id group_id');
					$this->db->from('qa_account_group_point_indicator gpi');
					$this->db->join('qa_account_group ag', 'ag.qa_account_group_id = gpi.qa_account_group_id');
					$this->db->where('ag.qa_account_id', $data['account_id']);
					$this->db->where('ag.is_default', ACTIVE);
					$query = $this->db->get();
					$def_res = $query->result();
						
					if(count($def_res) > 0)
					{
						$delete_poing_ind_qry = "DELETE FROM qa_account_group_point_indicator WHERE qa_account_group_id = ". $def_res[0]->group_id ." and qa_point_indicator_id = ". $value['ind_id'];
						$qry_res = $this->db->query($delete_poing_ind_qry);
					}
						
					$insert_ind_data = array(
										'qa_account_group_id' => $group_id,
										'qa_point_indicator_id' => $value['ind_id'],
										'sequence_no' => $value['seq_no'],
										'cuser_id' =>$data['user_id']
					);

					$this->db->set('cdate', 'NOW()', FALSE);
					$insert_qa_group = $this->db->insert('qa_account_group_point_indicator', $insert_ind_data);
				}
			}
			return $group_id;
		}
	}
	
	public function delete_qa_group($data)
	{
		$this->db->select('ag.qa_account_group_id group_id');
		$this->db->from('qa_account_group_point_indicator gpi');
		$this->db->join('qa_account_group ag', 'ag.qa_account_group_id = gpi.qa_account_group_id');
		$this->db->where('ag.qa_account_id', $data['account_id']);
		$this->db->where('ag.is_default', ACTIVE);
		$query = $this->db->get();
		$def_res = $query->result();
		
		$this->db->select('qa_point_indicator_id ind_id');
		$this->db->from('qa_account_group_point_indicator');
		$this->db->where('qa_account_group_id', $data['group_id']);
		$query = $this->db->get();
		$ind_res = $query->result();
		
		foreach($ind_res as $key => $value)
		{
			$seq_no = 0;
			$insert_ind_data = array(
										'qa_account_group_id' => $def_res[0]->group_id,
										'qa_point_indicator_id' => $value->ind_id,
										'sequence_no' => $seq_no,
										'cuser_id' =>$data['user_id']
			);

			$this->db->set('cdate', 'NOW()', FALSE);
			$insert_qa_group = $this->db->insert('qa_account_group_point_indicator', $insert_ind_data);
		}
		
		$delete_point_ind_qry = "DELETE FROM qa_account_group_point_indicator WHERE qa_account_group_id = ". $data['group_id'];
		$qry_res = $this->db->query($delete_point_ind_qry);
		
		$update_data = array(
								'status	' => INACTIVE,
								'muser_id' =>$data['user_id']
		);
			
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('qa_account_group_id', $data['group_id']);
		$this->db->where('qa_account_id', $data['account_id']);
		$this->db->where('is_default', INACTIVE);
		$edit_qa_group = $this->db->update('qa_account_group', $update_data);
		
		return TRUE;
	}
}
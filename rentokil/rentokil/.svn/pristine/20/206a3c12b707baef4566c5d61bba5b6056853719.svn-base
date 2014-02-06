<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_common {

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('common_model');
	}

	public function edit_profile($data,$change_pwd)
	{
		if ($this->CI->common_model->edit_profile($data,$change_pwd))
		{
			$edit_profile_obj->error = FALSE;
			$edit_profile_obj->error_msg = MYPROFILE_UPDATE;
			$edit_profile_obj->session_status = TRUE;
			echo json_encode($edit_profile_obj);
			exit;
		}
		else
		{
			$edit_profile_obj->error = TRUE;
			$edit_profile_obj->error_msg = DATABASE_QUERY_FAILED;
			$edit_profile_obj->session_status = TRUE;
			echo json_encode($edit_profile_obj);
			exit;
		}
	}

	public function password_validation($profile_data,$change_pwd)
	{
		if ($change_pwd)
		{
			if ($this->CI->common_model->password_validation($profile_data))
			{
				$this->edit_profile($profile_data,$change_pwd);
			}
			else
			{
				$edit_profile_obj->error = TRUE;
				$edit_profile_obj->error_msg = INVALID_PASSWORD;
				$edit_profile_obj->session_status = TRUE;
				echo json_encode($edit_profile_obj);
				exit;
			}
		}
		else
		{
			$this->edit_profile($profile_data,$change_pwd);
		}
	}

	public function get_schools($data)
	{
		$schools_res = $this->CI->common_model->get_schools($data);
		return $schools_res;
	}

	public function get_schools_orders($data)
	{
		$schools_res = $this->CI->common_model->get_schools_orders($data);
		return $schools_res;
	}
	
	public function get_schools_admins($data)
	{
		$schools_res = $this->CI->common_model->get_schools_admins($data);
		return $schools_res;
	}
	
	public function check_all_schools_status($data)
	{
		$schools_res = $this->CI->common_model->check_all_schools_status($data);
		return $schools_res;
	}

	public function get_contract($data)
	{
		$contract_res = $this->CI->common_model->get_contract($data);
		return $contract_res;
	}

	public function get_school_details($data)
	{
		$schools_res = $this->CI->common_model->get_school_details($data);
		return $schools_res;
	}
	public function save_school_details($data){
		$schools_res = $this->CI->common_model->save_school_details($data);
		return $schools_res;
	}
	public function validate_school_key($data){
		return $this->CI->common_model->validate_school_key($data);
	}

	public function  get_school_documents($data)
	{
		$schools_res = $this->CI->common_model->get_school_documents($data);
		return $schools_res;
	}

	public function get_school_document_comments($data){
		$schools_res = $this->CI->common_model->get_school_document_comments($data);
		return $schools_res;
	}

	public function update_school_document_status($data){
		$schools_res = $this->CI->common_model->update_school_document_status($data);
		return $schools_res;
	}

	public function insert_document_comments($data) {
		$schools_res = $this->CI->common_model->insert_document_comments($data);
		return $schools_res;
	}
	public function delete_document($data) {
		$schools_res = $this->CI->common_model->delete_document($data);
		return $schools_res;
	}

	public function  get_energy_documents($data)
	{
		$schools_res = $this->CI->common_model->get_energy_documents($data);
		return $schools_res;
	}

	public function insert_energy_document_comments($data) {
		$energy_res = $this->CI->common_model->insert_energy_document_comments($data);
		return $energy_res;
	}

	public function update_energy_document_status($data){
		$energy_res = $this->CI->common_model->update_energy_document_status($data);
		return $energy_res;
	}

	public function get_energy_document_comments($data){
		$energy_res = $this->CI->common_model->get_energy_document_comments($data);
		return $energy_res;
	}

	public function delete_energy_document($data) {
		$energy_res = $this->CI->common_model->delete_energy_document($data);
		return $energy_res;
	}

	public function download_file($data){
		switch ($data['download_type']){
			case "school_document":
				$data['from'] = FROM_GET_SCHOOL_DOCUMENTS;
				$data['msg'] = LOG_READ;
				$log_msg = get_session_log_message($data);
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$file_details = $this->CI->common_model->get_school_document_file($data);
				break;
			case "energy_document":
				$data['from'] = FROM_GET_ENERGY_DOCUMENTS;
				$data['msg'] = LOG_READ;
				$log_msg = get_session_log_message($data);
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$file_details = $this->CI->common_model->get_energy_document_file($data);
				break;
			case "hh":
				$data['from'] = FROM_GET_HH_REPORTS;
				$data['msg'] = LOG_READ;
				$log_msg = get_session_log_message($data);
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$file_details = $this->CI->common_model->get_download_failed_import($data);
				break;
			case "nhh":
				$data['from'] = FROM_GET_NHH_REPORTS;
				$data['msg'] = LOG_READ;
				$log_msg = get_session_log_message($data);
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$file_details = $this->CI->common_model->get_download_failed_import($data);
				break;
			case "target":
				$data['from'] = FROM_GET_TARGET_DATA;
				$data['msg'] = LOG_READ;
				$log_msg = get_session_log_message($data);
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$file_details = $this->CI->common_model->get_download_failed_import($data);
				break;
			case "setup":
				$data['from'] = FROM_GET_SETUP_ENTITIES;
				$data['msg'] = LOG_READ;
				$log_msg = get_session_log_message($data);
				// Save session log
				$log_data['message'] = $log_msg.LOG_AUTHORISED;
				session_log_message_helper($log_data);
				// Save session log - ends
				$file_details = $this->CI->common_model->get_download_failed_import($data);
				break;
			default:
				$error_obj->error = TRUE;
				$error_obj->error_msg = IMPORT_TYPE_INVALID;
				$error_obj->session_status = TRUE;
				echo $error_obj;
				break;
		}
		return $file_details;
	}

	public function add_parent_user($data)
	{
		if ($this->CI->common_model->user_exist($data)) {
			if ($this->CI->common_model->pupil_exist($data))
			{
				$student_id = $this->CI->common_model->get_student_id($data);
				$data['student_id'] = $student_id;
				$pupil_id = explode("/", $data['sampleId']);
				$data['pupil_id'] = $pupil_id[0];
				if ($this->CI->common_model->registered_users($data['sampleId'])) {
					
					$contract_res= $this->CI->common_model->get_contract_customer_id($data);
					$data['contract_id'] = $contract_res[0]->contract_id;
					$data['customer_id'] = $contract_res[0]->customer_id;
					
					$profile_res= $this->CI->common_model->get_contract_profile_id($data);
					if(count($profile_res) > 0)
					{
						$data['profile_id'] = $profile_res[0]->profile_id;
					}
					else
					{
						$parent_user_obj->error = TRUE;
						$parent_user_obj->error_msg = NO_PROFILE_CONTRACT;
						echo json_encode($parent_user_obj);
						exit;
					}
					
					$data['userName'] = $data['emailAddress'];
						
					$exp_arr = explode('+', $data['userName']);
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
						$user_email = $data['userName'];
					}
						
					$data['emailAddress']= $user_email;

					$password = generate_password();
					
					/*if(strlen($password) < RANDOM_PASSWORD_LENGTH)
					{
						$password = RANDOM_PASS_FS;
					}*/
					
					$data['password_db'] = md5($password);
					$data['password'] = $password;
					
					$user_id = $this->CI->common_model->add_parent_user($data);
					if($user_id)
					{
						$data['user_id'] = $user_id;
						
						$new_Parent_user_email_res = new_parent_user_email($data);	// Send mail
						if (!$new_Parent_user_email_res)
						{
							$parent_user_obj->error_msg = EMAIL_FAILED;
							$parent_user_obj->error = TRUE;
							return $parent_user_obj;
						}
						else
						{
							$parent_user_obj->error = FALSE;
							return $parent_user_obj;
						}
					}
				}
				else
				{
					$parent_user_obj->error = TRUE;
					$parent_user_obj->error_msg = PUPIL_ID_ALREADY_REGISTERED;
					echo json_encode($parent_user_obj);
					exit;
				}
			}
			else
			{
				$parent_user_obj->error = TRUE;
				$parent_user_obj->error_msg = PUPIL_NOT_EXIST;
				echo json_encode($parent_user_obj);
				exit;
			}
		} else {
			$parent_user_obj->error = TRUE;
			$parent_user_obj->error_msg = USER_EXIST;
			echo json_encode($parent_user_obj);
			exit;
		}
	}
	
	public function forgot_password($data)
	{
		$user_email_res = $this->CI->common_model->get_user_email($data);
		if($user_email_res->user_email == '' || $user_email_res->user_email == NULL)
		{
			$forgot_password_err->error_msg = INVALID_USERNAME;
			$forgot_password_err->error = TRUE;
			return $forgot_password_err;
		}
		else 
		{
			$password = generate_password();
			//sleep(1);
			/*if(strlen($password) < RANDOM_PASSWORD_LENGTH)
			{
				$password = RANDOM_PASS_FS;
			}*/
			$data['password'] = md5($password);
			$data['user_id'] = $user_email_res->user_id;
			$data['fname'] = $user_email_res->first_name;
			$data['lname'] = $user_email_res->last_name;
			$data['user_email'] = $user_email_res->user_email;
			
			if($this->CI->common_model->update_password($data))
			{
				$data['password_email'] = $password;				
				$forgot_password_email_res = forgot_password_email($data);	// Send mail to user admin
				if (!$forgot_password_email_res)
				{
					$forgot_password->error_msg = EMAIL_FAILED;
					$forgot_password->error = TRUE;
					return $forgot_password;
				}
				else
				{
					$forgot_password->email_id = $data['user_email'];
					$forgot_password->error = FALSE;
					return $forgot_password;
				}
			}
			else
			{
				$forgot_password->error_msg = DATABASE_QUERY_FAILED;
				$forgot_password->error = TRUE;
			}
		}
	}
	
	public function get_user_details($data)
	{
		$user_id = number_decrypt($data['key']);
		
		$check_user = $this->CI->common_model->check_user_id($user_id);
		if($check_user)
		{
			$user_res = $this->CI->common_model->get_user_details($user_id);
			
			$get_user_obj = new stdClass();
			$get_user_obj->user_res = $user_res;
			$get_user_obj->error = FALSE;
			return $get_user_obj;
		}
		else
		{
			$get_user_err = new stdClass();
			$get_user_err->error = TRUE;
			$get_user_err->error_msg = UNAUTHOURIZED_ACCESS;
			return $get_user_err;
		}
	}
	
	public function save_change_password($data)
	{	
		$check_user = $this->CI->common_model->check_user_id($data['user_id']);
		if($check_user)
		{
			$pwd_res = $this->CI->common_model->save_change_password($data);
			if($pwd_res)
			{
				require_once APPPATH.'business/business_user.php';
				$bw_obj = new Business_user();

				$login_obj->username = $data['username'];
				$login_obj->password = $data['password'];

				$login_res = $bw_obj->validate_login($login_obj);
				
				return $login_res;
			}
			else
			{
				$chg_pwd_err = new stdClass();
				$chg_pwd_err->error = TRUE;
				$chg_pwd_err->error_msg = DATABASE_QUERY_FAILED;
				return $chg_pwd_err;
			}
		}
		else 
		{
			$chg_pwd_err = new stdClass();
			$chg_pwd_err->error = TRUE;
			$chg_pwd_err->error_msg = UNAUTHOURIZED_ACCESS;
			return $chg_pwd_err;
		}		
	}
	

	public function create_skins_default_folders()
	{
		if(!is_dir(SKIN_PATH))
		{
			mkdir(SKIN_PATH,0755);	//Directory creation with full permission
			mkdir(SKIN_PATH."default",0755);	//Directory creation with full permission
			
			$src = str_replace('ri_backend/index.php','ri_frontend/img/skins/default/',$_SERVER['SCRIPT_FILENAME']);
			$dst = SKIN_PATH."default/";
			
			$handle = opendir($src);
			while (($file = readdir($handle))!=false)
			{
				if($file != "." && $file != "..")
				{
					echo "File copying...". $src."/".$file ."<br>";
					copy ("$src/$file", "$dst/$file");
				}
			}
		};
	}
	
	public function create_folders_existing_skins()
	{
		$skin_data = $this->CI->common_model->get_existing_skins();
		foreach($skin_data as $key => $value)
		{
			$skin_id = $value->skin_id;
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
			}			
		}
		$create_skin = new stdClass();
		$create_skin->error = FALSE;
		$create_skin->session_status = TRUE;
		return $create_skin;
	}
	
	public function get_meal_order_summary($data)
	{
		$meal_summary_res = $this->CI->common_model->get_meal_order_summary($data);
			
		$meal_obj = new stdClass();
		$meal_obj->meal_summary_res = $meal_summary_res;
		$meal_obj->error = FALSE;
		$meal_obj->session_status = TRUE;
		return $meal_obj;
	}


	/* For resource management setcion...*/
	
	/* Get the zone dashboard */
	
	public function get_zone_dashboard($data)
	{
		return $this->CI->common_model->get_zone_dashboard($data);
	}
	
	public function get_zone_details($data)
	{
		return $this->CI->common_model->get_zone_details($data);
	}
	
	public function get_zone_chart_details($data)
	{
		return $this->CI->common_model->get_zone_chart_details($data);
	}
	
	/*public function get_userinfo($user_id)
	{
		return $this->CI->common_model->get_userinfo($user_id);
	}*/
	
	public function school_close($data)
	{
		if($this->CI->common_model->school_close($data))
		{		
			$batch_key = array(SCHOOL_REPLACE_STRING, NAME_REPLACE_STRING, DATE_REPLACE_STRING, SCHOOL_CLOSED_TILL_REPLACE_STRING, REASON_REPLACE_STRING);
			$batch_data = array('school_id' => $data['school_id'], 'close_till' => $data['close_till'], 'reason' => $data['reason'], 'user_id' => $data['user_id'], 'str' => SCHOOL_CLOSED_SYSTEM_MESSAGE, 'key_values' => $batch_key);
			$data['reason_msg'] = generate_batch_system_messages($batch_data);

			$order_res = $this->CI->common_model->get_orders_schools($data);

			if(count($order_res) > 0)
			{
				foreach($order_res as $key => $value)
				{
					$batch_cancel_id = create_batch_cancel($data, SCHOOL_CLOSE_DATA_ID);
					$update_status = $this->CI->common_model->update_orders_school_close($data, $batch_cancel_id, $value->fulfilment_date);
				}
			}
			$email_res = $this->CI->common_model->get_school_admin_emails($data['school_id']);
			if(count($email_res) > 0)
			{
				$school_name = $this->CI->common_model->get_school_name($data['school_id']);
				$close_date = date('d/m/Y', strtotime($data['close_till']));
				$mail_data = array('email_arr' => $email_res, 'close_till' => $close_date, 'reason' => $data['reason'], 'school_name' => $school_name);
				$close_email_res = school_close_email($mail_data);	// Send mail to user admin
				if (!$close_email_res)
				{
					$school_close = new stdClass();
					$school_close->error = TRUE;
					$school_close->error_msg = CADMIN_USER_EMAIL_FAILED;
					$school_close->session_status = TRUE;
					return $school_close;
				}
				else
				{
					$school_close = new stdClass();
					$school_close->error = FALSE;
					$school_close->session_status = TRUE;
					return $school_close;
				}
			}
			else
			{
				$school_close = new stdClass();
				$school_close->error = FALSE;
				$school_close->session_status = TRUE;
				return $school_close;
			}
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}
	
	public function school_open($data)
	{
		$data['close_till'] = $this->CI->common_model->get_school_close_till_date($data);	// getting the school closed date		
		if($this->CI->common_model->school_open($data))
		{
			if($this->CI->common_model->update_orders_school_open($data))
			{
				$email_res = $this->CI->common_model->get_school_admin_emails($data['school_id']);
				if(count($email_res) > 0)
				{
					$school_name = $this->CI->common_model->get_school_name($data['school_id']);
					$mail_data = array('email_arr' => $email_res, 'school_name' => $school_name);
					$close_email_res = school_open_email($mail_data);	// Send mail to user admin
					if (!$close_email_res)
					{
						$school_open = new stdClass();
						$school_open->error = TRUE;
						$school_open->error_msg = CADMIN_USER_EMAIL_FAILED;
						$school_open->session_status = TRUE;
						return $school_open;
					}
					else
					{
						$school_open = new stdClass();
						$school_open->error = FALSE;
						$school_open->session_status = TRUE;
						return $school_open;
					}
				}
				else
				{
					$school_open = new stdClass();
					$school_open->error = FALSE;
					$school_open->session_status = TRUE;
					return $school_open;
				}
			}
			else
			{
				$err_obj = new stdClass();
				$err_obj->error = TRUE;
				$err_obj->error_msg = DATABASE_QUERY_FAILED;
				return $err_obj;
			}
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->error_msg = DATABASE_QUERY_FAILED;
			return $err_obj;
		}
	}
	
	public function process_discovery_request($from, $user_agent, $content_length, $post_data){
		
		//Validate the headers for valid source or not.
		if($content_length == 20)
			return $this->CI->common_model->process_heartbeat_request($from, $user_agent,$post_data);
		else 
			return $this->CI->common_model->process_tagread_request($from, $user_agent,$post_data);
			
	}
	
	public function get_current_date_time()
	{
		return $this->CI->common_model->get_current_date_time();
	}
	
	public function asset_dash_search($data)
	{
		return $this->CI->common_model->asset_dash_search($data);
	}
	
	public function process_digital_form_request($post_data){
		return $this->CI->common_model->process_digital_form_request($post_data); 
	}
}
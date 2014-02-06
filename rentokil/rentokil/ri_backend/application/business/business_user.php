<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Business_user {

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->model('user_model');
	}

	public function  validate_login($auth_obj)
	{				
		$auth_res = $this->CI->user_model->chk_user($auth_obj);
		
		if($auth_res == null){
			$err_obj->error = TRUE;
			$err_obj->auth = FALSE;
			$err_obj->pauth = FALSE;
			$err_obj->mauth = FALSE;
			$err_obj->access = FALSE;
			$err_obj->session_status = FALSE;
			return $err_obj;
		}
		
		if($auth_res->user_auth_count>0)
		{
			if($auth_res->status != DEFAULT_STATUS)
			{
				$err_obj->error = TRUE;
				$err_obj->auth = TRUE;
				$err_obj->pauth = TRUE;
				$err_obj->mauth = TRUE;
				$err_obj->access = FALSE;				
				$err_obj->session_status = FALSE;
				return $err_obj;
			}
			if(($auth_res->role_id == USER || $auth_res->role_id == CUSTOMER_ADMIN) && $auth_res->profile_id == 0)
			{
				//If no profile is configured
				$err_obj->error = TRUE;
				$err_obj->auth = TRUE;
				$err_obj->pauth = FALSE;
				$err_obj->mauth = TRUE;
				$err_obj->access = TRUE;
				$err_obj->session_status = FALSE;
				return $err_obj;
			}			
			else {
				$user_session = md5(microtime().mt_rand());
				$this->CI->session->set_userdata('user_session', $user_session);
				$user_info = $this->CI->user_model->get_user_info($auth_res->user_id);
				
				if($user_info->role_id == USER)
				{
					if($user_info->default_mod == "")	// check the profile modules
					{
						$err_obj->error = TRUE;
						$err_obj->auth = TRUE;
						$err_obj->pauth = TRUE;
						$err_obj->mauth = FALSE;
						$err_obj->access = TRUE;
						$err_obj->session_status = FALSE;
						return $err_obj;
					}
					
					/* Get the skin details */
					$skin_data = array('contract_id' => $user_info->contract_id, 'profile_id' => $user_info->profile_id);
					$skin_res = $this->CI->user_model->get_skin_info($skin_data);
				}
				else if($user_info->role_id == CUSTOMER_ADMIN)
				{
					$profile_res = $this->CI->user_model->get_cadmin_profile_info($user_info->profile_id);	// get the profile details
				}
				
				$data['user_id'] = $user_info->user_id;
				$data['ip_address'] = $_SERVER['REMOTE_ADDR'];
				$this->CI->user_model->update_user_activity($data);	// Update user activity				
				
				$login_res->error = FALSE;
				$login_res->auth = TRUE;
				$login_res->pauth = TRUE;
				$login_res->mauth = TRUE;
				$login_res->access = TRUE;
				if($auth_res->chg_pwd == ACTIVE)
					$login_res->chg_pwd = TRUE;
				else
					$login_res->chg_pwd = FALSE;
				$login_res->user_info = $user_info;
				if(isset($skin_res))
				{
					$login_res->skin_info = $skin_res;
				}
				if(isset($profile_res))
				{
					$login_res->profiles = $profile_res;
				}
				$login_res->session_id = $user_session;
				$login_res->session_status = TRUE;
				$this->CI->session->set_userdata('user_info', $login_res->user_info);
				return $login_res;
			}
		}
		else
		{
			$err_obj->error = TRUE;
			$err_obj->auth = FALSE;
			$err_obj->pauth = TRUE;
			$err_obj->mauth = TRUE;
			$err_obj->access = TRUE;
			$err_obj->session_status = FALSE;
			return $err_obj;
		}
	}

	public function get_halfhourly_chart($data) {
		$chart_data = $this->CI->user_model->get_halfhourly_chart($data);
		return $chart_data;
	}

	public function get_daily_chart($data) {
		$chart_data = $this->CI->user_model->get_daily_chart($data);
		return $chart_data;
	}

	public function get_monthly_chart($data) {
		$chart_data = $this->CI->user_model->get_monthly_chart($data);
		return $chart_data;
	}

	public function add_pupil($data) {
		$check_pupil_id_cnt = $this->CI->user_model->check_pupil_id($data);
		if($check_pupil_id_cnt > 0)
		{
			$pupil_id_arr = explode('/', $data['pupil_id']);
			$check_contract_key_cnt = $this->CI->user_model->check_contract_key($data['contract_id'],$pupil_id_arr[0]);
			if($check_contract_key_cnt > 0)
			{
				$this->CI->user_model->add_pupil($data);
				$add_pupil_obj = new stdClass();
				$add_pupil_obj->error = FALSE;
				$add_pupil_obj->session_status = TRUE;
				return $add_pupil_obj;
			}
			else
			{
				$err_obj = new stdClass();
				$err_obj->error = TRUE;
				$err_obj->session_status = TRUE;
				$err_obj->error_msg = PUPIL_ID_INVALID_ADD;
				return $err_obj;
			}
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = PUPIL_ID_INVALID_ADD;
			return $err_obj;
		}
	}

	public function get_pupils($data) {
		$get_pupils = $this->CI->user_model->get_pupils($data);
		return $get_pupils;
	}

	public function pupil_unassign($data)
	{
		$check_pupil = $this->check_pupil($data);
		if($check_pupil)
		{
			$this->CI->user_model->pupil_unassign($data);
			$pupil_unassign = new stdClass();
			$pupil_unassign->error = FALSE;
			$pupil_unassign->session_status = TRUE;
			return $pupil_unassign;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = UNAUTHOURIZED_ACCESS;
			return $err_obj;
		}

	}

	public function check_pupil($data) {
		$pupil_chk_cnt = $this->CI->user_model->check_pupil($data);
		if($pupil_chk_cnt > 0)
		return TRUE;
		else
		return FALSE;
	}

	public function edit_pupils($data) {
		//$check_data = $this->CI->user_model->validate_parent($data);
		if($this->CI->user_model->edit_pupils($data))
		{
			$edit_pupils = new stdClass();
			$edit_pupils->error = FALSE;
			$edit_pupils->session_status = TRUE;
			return $edit_pupils;
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
	public function get_school_menu_details($data)
	{
		$school_menu_details = $this->CI->user_model->get_school_menu_details($data);
		return $school_menu_details;
	}
	public function save_school_menu_details($data)
	{
		$save_res = $this->CI->user_model->save_school_menu_details($data);
		return $save_res;
	}

	public function get_pupils_order_menu($data)
	{
		$get_res = $this->CI->user_model->get_pupils_order_menu($data);
		return $get_res;
	}
	
	public function get_order_search_pupils($data)
	{
		$search_pupils = $this->CI->user_model->get_order_search_pupils($data);
		
		foreach($search_pupils as $key => $value)
		{
			foreach($value as $k => $v)
			{
				$cls_name = $value->class_col;
				if($k == $cls_name)
				{
					$value->class_name = $value->$cls_name;
				}
			}
		}
		return $search_pupils;
	}
	
	public function get_pupils_order_menu_details($data)
	{
		$get_res = $this->CI->user_model->get_pupils_order_menu_details($data);
		return $get_res;
	}
	
	public function save_order_items($data)
	{
		$save_res = $this->CI->user_model->save_order_items($data);
		return $save_res;
	}
	
	public function cancel_order_items($data)
	{
		$cancel_res = $this->CI->user_model->cancel_order_items($data);
		return $cancel_res;
	}

	public function search_pupils($data) {
		$search_pupils = $this->CI->user_model->search_pupils($data);
		
		foreach($search_pupils as $key => $value)
		{
			foreach($value as $k => $v)
			{
				$cls_name = $value->class_col;
				if($k == $cls_name)
				{
					$value->class_name = $value->$cls_name;
				}
			}
		}
		return $search_pupils;
	}

	public function save_cash_refund($data) {
		$save_res = $this->CI->user_model->save_cash_refund($data);
		if($save_res)
		{
			$payment_res = $this->CI->user_model->get_updated_payment_items($data['payment_id']);
			
			$save_cash_refund_obj = new stdClass();
			$save_cash_refund_obj->save_cash_refund_res = $payment_res;
			$save_cash_refund_obj->error = FALSE;
			$save_cash_refund_obj->session_status = TRUE;
			return $save_cash_refund_obj;
		}
		else
		{
			$save_cash_refund_obj = new stdClass();
			$save_cash_refund_obj->error = TRUE;
			$save_cash_refund_obj->error_msg = DATABASE_QUERY_FAILED;
			$save_cash_refund_obj->session_status = TRUE;
			return $save_cash_refund_obj;
		}
	}

	public function make_cash_payment($data) {
		$payment_id = $this->CI->user_model->make_cash_payment($data);
		if($payment_id)
		{
			$cur_payment_his = $this->CI->user_model->get_updated_payment_items($payment_id);
			$cur_payment_his_obj = new stdClass();
			$cur_payment_his_obj->cash_payment_res = $cur_payment_his;
			$cur_payment_his_obj->error = FALSE;
			$cur_payment_his_obj->session_status = TRUE;
			return $cur_payment_his_obj;
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
	
	public function get_full_history($data)
	{
		$full_history_res = $this->CI->user_model->get_full_history($data);
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
	
	public function get_pupils_topay($data) {
		$get_pupils = $this->CI->user_model->get_pupils_topay($data);
		return $get_pupils;
	}
	
	/*public function make_card_payment($data) {
		
		$trans_fee_res = $this->CI->user_model->get_trans_fee($data['contract_id']);
		
		if($trans_fee_res->trans_fee_status)	//Check the SQL Select query status
		{			
			$count = 0;		// Intialize the variable for store the transaction fee into an exiting array
			foreach ($data['pupils_res'] as $pupils_data)	
			{
				if($pupils_data['card_type'] == CREDIT_CARD)	// Check the type of card
				{
					$total_transaction_fee = $data['total_amt'] * ($trans_fee_res->cc_fee / 100);	// Calculate total transaction fee
					$transaction_fee = $total_transaction_fee * ($pupils_data['allocation_percentage'] / 100);	// Calculate individual transaction fee from the total transaction fee
					$data['pupils_res'][$count]['transaction_fee'] = $transaction_fee;		//update the array with new transaction fee
				}
				else
				{
					$transaction_fee = $trans_fee_res->dc_fee * ($pupils_data['allocation_percentage'] / 100);			// Calculate transaction fee for debit card		
					$data['pupils_res'][$count]['transaction_fee'] = $transaction_fee;		//update the array with new transaction fee
				}
				$count++;
			}			
		}
		else 
		{
			$count = 0;
			foreach ($data['pupils_res'] as $pupils_data)
			{
				$data['pupils_res'][$count]['transaction_fee'] = 0;
				$count++;
			}
		}
		$payment_id = $this->CI->user_model->make_card_payment($data);
		if($payment_id)
		{
			$cur_payment_his = $this->CI->user_model->get_updated_payment_items($payment_id);
			$cur_payment_his_obj = new stdClass();
			$cur_payment_his_obj->card_payment_res = $cur_payment_his;
			$cur_payment_his_obj->error = FALSE;
			$cur_payment_his_obj->session_status = TRUE;
			return $cur_payment_his_obj;
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = DATABASE_QUERY_FAILED;
			$error_obj->session_status = TRUE;
			return $error_obj;
		}
	}*/
	
	public function get_card_payment_history($data) {
		$history_res = $this->CI->user_model->get_card_payment_history($data);
		return $history_res;
	}
	
	public function get_schools_meal_collection($data)
	{
		$school_res = $this->CI->user_model->get_schools_meal_collection($data);
		
		if($school_res > 0)
		{
			$school_obj = new stdClass();
			$school_obj->school_res = $school_res;
			$school_obj->error = FALSE;
			$school_obj->session_status = TRUE;
			return $school_obj;
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
	
	public function get_daily_meal_collection_year_class($data)
	{
		$meal_res = $this->CI->user_model->get_daily_meal_collection_year_class($data);
		if($meal_res > 0)
		{
			$meal_obj = new stdClass();
			$meal_obj->meal_res = $meal_res;
			$meal_obj->error = FALSE;
			$meal_obj->session_status = TRUE;
			return $meal_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			return $err_obj;
		}
	}
	
	public function print_daily_meal_collection($data)
	{
		$meal_res = $this->CI->user_model->print_daily_meal_collection($data);
		if($meal_res > 0)
		{
			$meal_obj = new stdClass();
			$meal_obj->meal_res = $meal_res;
			$meal_obj->error = FALSE;
			$meal_obj->session_status = TRUE;
			return $meal_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			return $err_obj;
		}
	}
	
	public function get_daily_meal_collection_students($data)
	{
		$meal_res = $this->CI->user_model->get_daily_meal_collection_students($data);
		if($meal_res > 0)
		{
			$meal_obj = new stdClass();
			$meal_obj->meal_res = $meal_res;
			$meal_obj->error = FALSE;
			$meal_obj->session_status = TRUE;
			return $meal_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = EXPORT_PUPIL_NO_DATA;
			return $err_obj;
		}
	}
	
	public function update_daily_meal_collection_status($data)
	{
		if(!$this->CI->user_model->update_daily_meal_collection_status($data))
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
	
	public function get_user_authroization($data){
		return $this->CI->user_model->get_user_authroization($data);
	}
	
	public function initiate_card_payment($data) {
		
		$trans_fee_res = $this->CI->user_model->get_trans_fee($data['contract_id']);
		
		$overall_transaction_fee = 0;
		
		if($trans_fee_res->trans_fee_status)	//Check the SQL Select query status
		{			
			$count = 0;		// Intialize the variable for store the transaction fee into an exiting array
			foreach ($data['pupils_res'] as $pupils_data)	
			{
				if($pupils_data['card_type'] == CREDIT_CARD)	// Check the type of card
				{
					$total_transaction_fee = $data['total_amt'] * ($trans_fee_res->cc_fee / 100);	// Calculate total transaction fee
					$overall_transaction_fee = $total_transaction_fee;
					$transaction_fee = $total_transaction_fee * ($pupils_data['allocation_percentage'] / 100);	// Calculate individual transaction fee from the total transaction fee
					$data['pupils_res'][$count]['transaction_fee'] = $transaction_fee;		//update the array with new transaction fee
				}
				else
				{
					$transaction_fee = $trans_fee_res->dc_fee * ($pupils_data['allocation_percentage'] / 100);			// Calculate transaction fee for debit card
					$overall_transaction_fee = $trans_fee_res->dc_fee;		
					$data['pupils_res'][$count]['transaction_fee'] = $transaction_fee;		//update the array with new transaction fee
				}
				$count++;
			}			
		}
		else 
		{
			$count = 0;
			$overall_transaction_fee = 0;
			foreach ($data['pupils_res'] as $pupils_data)
			{
				$data['pupils_res'][$count]['transaction_fee'] = 0;				
				$count++;
			}
		}
		$res = $this->CI->user_model->initiate_card_payment($data);
		if($res)
		{
			$total_amt = $data['total_amt'] + $overall_transaction_fee;
			return $total_amt;
		}
		return $res;
	}
	
	public function save_card_payment($data)
	{
		if(!$this->CI->user_model->save_card_payment($data))
		{
			$update_settings_status_err->error = TRUE;
			$update_settings_status_err->error_msg = DATABASE_QUERY_FAILED;
			$update_settings_status_err->session_status = TRUE;
			return $update_settings_status_err;
		}
		else
		{
			$yp_msg = $this->CI->user_model->get_yp_error_code($data['yp_code']);
			$cur_payment_his = $this->CI->user_model->get_updated_payment_items($data['mtr']);
			$cur_payment_his_obj = new stdClass();
			$cur_payment_his_obj->card_payment_res = $cur_payment_his;
			$cur_payment_his_obj->yp_status = $data['yp_code'];
			$cur_payment_his_obj->yp_msg = $yp_msg;			
			$cur_payment_his_obj->error = FALSE;
			$cur_payment_his_obj->session_status = TRUE;
			return $cur_payment_his_obj;
		}
	}
	
	public function cancel_card_payment($data)
	{
		$cancel_pay_res = $this->CI->user_model->cancel_card_payment($data);
		if(!$cancel_pay_res)
		{
			$cancel_pay_err = new stdClass();
			$cancel_pay_err->error = TRUE;
			$cancel_pay_err->error_msg = DATABASE_QUERY_FAILED;
			$cancel_pay_err->session_status = TRUE;
			return $cancel_pay_err;
		}
		else
		{
			$cancel_pay_obj = new stdClass();
			$cancel_pay_obj->error = FALSE;
			$cancel_pay_obj->cancel_res = $cancel_pay_res;
			$cancel_pay_obj->session_status = TRUE;
			return $cancel_pay_obj;
		}
	}
	
	public function get_batch_order_cancellation($data)
	{
		$batch_res = $this->CI->user_model->get_batch_order_cancellation($data);
		if(count($batch_res) > 0)
		{
			$batch_obj = new stdClass();
			$batch_obj->batch_res = $batch_res;
			$batch_obj->error = FALSE;
			$batch_obj->session_status = TRUE;
			return $batch_obj;
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = NO_BATCH_DATA;
			return $err_obj;
		}
	}
	
	public function batch_cancel_order_items($data) {
		if($this->CI->user_model->batch_cancel_order_items($data))
		{
			$batch_cancel = new stdClass();
			$batch_cancel->error = FALSE;
			$batch_cancel->session_status = TRUE;
			return $batch_cancel;
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
	
	public function update_batch_order_user_msg($data) {
		if($this->CI->user_model->update_batch_order_user_msg($data))
		{
			$update_batch = new stdClass();
			$update_batch->error = FALSE;
			$update_batch->session_status = TRUE;
			return $update_batch;
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
	
	public function update_batch_order_clear_flag($data) {
		if($this->CI->user_model->update_batch_order_clear_flag($data))
		{
			$update_batch = new stdClass();
			$update_batch->error = FALSE;
			$update_batch->session_status = TRUE;
			return $update_batch;
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
        
	public function batch_email_parents($data) {
		if($this->CI->user_model->batch_email_parents($data))
		{
			$batch_email_res = new stdClass();
			$batch_email_res->error = FALSE;
			$batch_email_res->session_status = TRUE;
			return $batch_email_res;
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
	
	public function digital_form_load($data){
		return $this->CI->user_model->digital_form_load($data);
	}
	
	public function digital_form_filter($data){
		return $this->CI->user_model->digital_form_filter($data);
	}
	
	public function view_digital_form($data){
		$res_apps = $this->CI->user_model->view_digital_form($data);
		
		$xml_obj = new SimpleXMLElement($res_apps->form_xml);
		$page_xml = $xml_obj ->data ->page;
		$xml_string = $page_xml->asXML();
		
		$page_url = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$page_url= $page_url.$_SERVER["SERVER_NAME"] . '/' . ZIP_FOLDER_NAME_UPLOAD . $res_apps->contract_id . '/' . $res_apps->digital_app_id;
		
		$zip_path = ZIP_UPLOAD_PATH . $res_apps->contract_id . '/' . $res_apps->digital_app_id;
		
		$htmlFileName = $this->get_html_file_name($zip_path);
		$link = $page_url . '/' .$htmlFileName;
		
		$form_res = new stdClass();
		$form_res->error = FALSE;
		$form_res->xml_data = $xml_string;
		$form_res->link = $link;
		$form_res->session_status = TRUE;
		return $form_res;
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
	
	public function total_daily_meal_filter($data){
		return $this->CI->user_model->total_daily_meal_filter($data);
	}
	
	public function get_digital_form_late_number_details($data){
		if($this->CI->user_model->validate_digital_form($data)){
			return $this->CI->user_model->get_digital_form_late_number_details($data);
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = INVALID_DIGITAL_FORM_ID;
			return $err_obj;
		}
		
	}
	
	/* save digital form late number details */
	public function save_digital_form_late_numbers($data){
		if($this->CI->user_model->validate_digital_form($data)){
			if($this->CI->user_model->validate_digital_form_ward_id($data))
			{
				if($this->CI->user_model->save_digital_form_late_numbers($data))
				{
					$form_res = new stdClass();
					$form_res->error = FALSE;
					$form_res->session_status = TRUE;
					return $form_res;
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
			else 
			{
				$err_obj = new stdClass();
				$err_obj->error = TRUE;
				$err_obj->session_status = TRUE;
				$err_obj->error_msg = INVALID_DIGITAL_FORM_WARD_ID;
				return $err_obj;
			}
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = INVALID_DIGITAL_FORM_ID;
			return $err_obj;
		}
	}
	
	/* get digital form exception details */
	public function get_digital_form_exception_details($data){
		if($this->CI->user_model->validate_digital_form($data)){
			return $this->CI->user_model->get_digital_form_exception_details($data);
		}
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = INVALID_DIGITAL_FORM_ID;
			return $err_obj;
		}
	}
	
	/* save digital form late number details */
	public function save_digital_form_exception_details($data){
		if($this->CI->user_model->validate_digital_form($data)){
			if($this->CI->user_model->save_digital_form_exception_details($data))
			{
				$form_res = new stdClass();
				$form_res->error = FALSE;
				$form_res->session_status = TRUE;
				return $form_res;
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
		else
		{
			$err_obj = new stdClass();
			$err_obj->error = TRUE;
			$err_obj->session_status = TRUE;
			$err_obj->error_msg = INVALID_DIGITAL_FORM_ID;
			return $err_obj;
		}
	}
	
	public function get_df_indicators($data)
	{
		$df_indicators_res = $this->CI->user_model->get_df_indicators($data);
		$df_indicators_obj = new stdClass();
		$df_indicators_obj->dfi_res = $df_indicators_res;
		$df_indicators_obj->error = FALSE;
		$df_indicators_obj->session_status = TRUE;
		return $df_indicators_obj;
	}
	
	public function get_df_tdm_numbers($data)
	{
		if($data['dfi_id'] != 0)
		$this->CI->user_model->update_df_indicator_desc($data);
		
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
		$df_tdm_numbers_res = $this->CI->user_model->get_df_tdm_numbers($data);
		//$df_tdm_numbers_res['disp_days'] = $disp_days;
		$df_tdm_numbers_obj = new stdClass();
		$df_tdm_numbers_obj->dfi_res = $df_tdm_numbers_res;
		$df_tdm_numbers_obj->error = FALSE;
		$df_tdm_numbers_obj->session_status = TRUE;
		return $df_tdm_numbers_obj;
	}
	
	public function view_quality_auditor_load($data){
		$qa_res = $this->CI->user_model->view_quality_auditor_load($data);
		$quality_audit_obj = new stdClass();
		$quality_audit_obj->qa_res = $qa_res;
		$quality_audit_obj->error = FALSE;
		$quality_audit_obj->session_status = TRUE;
		return $quality_audit_obj;
	}
	
	public function quality_audit_filter($data){
		return $this->CI->user_model->quality_audit_filter($data);
	}
	
	public function get_daily_meal_orders($data){
		return $this->CI->user_model->get_daily_meal_orders($data);
	}
	
	public function get_sla_report($data){
		return $this->CI->user_model->get_sla_report($data);
	}
	
	public function delete_sla_report($data){
		return $this->CI->user_model->delete_sla_report($data);
	}
	
	public function get_invoice_orders($data){
		return $this->CI->user_model->get_invoice_orders($data);
	}
	public function get_inv_order_details($data){
		return $this->CI->user_model->get_inv_order_details($data);
	}
	public function save_inv_order_details($data){
		return $this->CI->user_model->save_inv_order_details($data);
	}
	public function cancel_inv_order_details($data){
		return $this->CI->user_model->cancel_inv_order_details($data);
	}
	public function search_pupil_debt_order($data){
		return $this->CI->user_model->search_pupil_debt_order($data);
	}
	public function save_pupil_meal_order_details($data){
		return $this->CI->user_model->save_pupil_meal_order_details($data);
	}
	public function get_pupil_inv_order_details($data){
		return $this->CI->user_model->get_pupil_inv_order_details($data);
	}
	
	public function get_schools_year_class_details($data){
		return $this->CI->user_model->get_schools_year_class_details($data);
	}
	
	public function get_school_pupil_search($data){
		return $this->CI->user_model->get_school_pupil_search($data);
	}
	
	public function update_pupil_fsm_adult_acive_status($data){
		return $this->CI->user_model->update_pupil_fsm_adult_acive_status($data);
	}
}
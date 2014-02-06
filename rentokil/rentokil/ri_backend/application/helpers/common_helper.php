<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* Session Validation */
if ( ! function_exists('validate_session'))
{
	function validate_session($user_session)
	{
		$CI =& get_instance();
		if($user_session == $CI->session->userdata('user_session'))
		{
			return TRUE;
		}
		else
		return FALSE;
	}
}

/*if ( ! function_exists('random_alpha_generator'))
 {
 function random_alpha_generator($length)
 {
 $random= "";
 srand((double)microtime()*1000000);

 $data = "AbcDE123IJKLMN67QRSTUVWXYZ";
 $data .= "aBCdefghijklmn123opq45rs67tuv89wxyz";
 $data .= "0FGH45OP89";

 for($i = 0; $i < $length; $i++)
 {
 $random .= substr($data, (rand()%(strlen($data))), 1);
 }

 return $random;
 }
 }*/

/* Generating email for New Customer Admin */
if ( ! function_exists('new_customer_admin_email'))
{
	function new_customer_admin_email($data)
	{
		$en_userid = number_encrypt($data['user_id']);
		$pageURL = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$pageURL= $pageURL.$_SERVER["SERVER_NAME"];
		$changepwdURL = $pageURL.CHANGE_PASSWORD_FRONTEND_LINK."?".CHANGE_PASSWORD_LINK_KEY."=".$en_userid;
		$CI =& get_instance();
		
		$message = '<html><body>Dear '. $data['admin_fname'].' '.$data['admin_lname'].',<br><br>
					We have setup your account. Please click the link below to login to the system:<br><br>
					<a href="'. $changepwdURL .'">Click here for first login</a><br><br>
					If the above link does not work, your registration details are recorded below:<br><br>
					Web site: '.$pageURL.'<br>
					Username: '.$data['username'].'<br>
					Password: '.$data['password_email'].'<br><br>
					Once you have logged in, you will be asked to change your password to something more memorable.<br><br>
					In the future, you can change your password and update your profile details at anytime under the "My Profile" option in the top right of your screen.<br><br>
					Regards, <br><br>';
		
		$CI->load->library('email');
		
		if ((strpos($pageURL, EDEN_CHECK_QA) !== false) || (strpos($pageURL, EDN_CHECK_LOCALHOST) !== false) || (strpos($pageURL, EDEN_CHECK_FMINISIGHT) !== false))
		{
			$msg = ADMIN_EMAIL_TITLE .'</body></html>';
			$CI->email->from(ADMIN_EMAIL, ADMIN_EMAIL_TITLE);
		}
		else
		{
			$msg = ADMIN_REGISTER_EMAIL_TITLE .'</body></html>';
			$CI->email->from(ADMIN_EMAIL, ADMIN_REGISTER_EMAIL_TITLE);
		}
		
		$message = $message . $msg;
		
		$CI->email->to($data['admin_email']);
		$CI->email->subject('Welcome to Facilities InSight');
		$CI->email->message($message);
		$new_customer_admin_email_res = $CI->email->send();
		return $new_customer_admin_email_res;
		
		
		
		/*$CI =& get_instance();
		
		$lastname = ' '. $data['admin_lname'];
		$message = '<html><body>
					Hello '. $data['admin_fname'] . $lastname .',<br><br>
					We\'ve setup your account. Please find your login information below,<br><br>
					Username : '.$data['username'].'<br>
					Password : '.$data['password_email'].'<br><br>
					Thanks, <br>
					Admin.</body></html>';
		$CI->load->library('email');
		$CI->email->from(ADMIN_EMAIL, ADMIN_EMAIL_TITLE);
		$CI->email->to($data['admin_email']);
		$CI->email->subject('Welcome to Facilities InSight');
		$CI->email->message($message);
		$new_customer_admin_email_res = $CI->email->send();
		//echo $CI->email->print_debugger(); exit;
		return $new_customer_admin_email_res;*/
	}
}

/* Generating email for New User */
if ( ! function_exists('new_user_email'))
{
	function new_user_email($data)
	{
		$en_userid = number_encrypt($data['user_id']);
		$pageURL = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$pageURL= $pageURL.$_SERVER["SERVER_NAME"];
		$changepwdURL = $pageURL.CHANGE_PASSWORD_FRONTEND_LINK."?".CHANGE_PASSWORD_LINK_KEY."=".$en_userid;
		$CI =& get_instance();
		
		$message = '<html><body>Dear '. $data['user_fname'].' '.$data['user_lname'].',<br><br>
					We have setup your account. Please click the link below to login to the system:<br><br>
					<a href="'. $changepwdURL .'">Click here for first login</a><br><br>
					If the above link does not work, your registration details are recorded below:<br><br>
					Web site: '.$pageURL.'<br>
					Username: '.$data['username'].'<br>
					Password: '.$data['password_email'].'<br><br>
					Once you have logged in, you will be asked to change your password to something more memorable.<br><br> 
					In the future, you can change your password and update your profile details at anytime under the "My Profile" option in the top right of your screen.<br><br>
					Regards, <br><br>';
					
		$CI->load->library('email');
		
		if ((strpos($pageURL, EDEN_CHECK_QA) !== false) || (strpos($pageURL, EDN_CHECK_LOCALHOST) !== false) || (strpos($pageURL, EDEN_CHECK_FMINISIGHT) !== false))
		{
			$msg = ADMIN_EMAIL_TITLE .'</body></html>';
			$CI->email->from(ADMIN_EMAIL, ADMIN_EMAIL_TITLE);
		}
		else
		{
			$msg = ADMIN_REGISTER_EMAIL_TITLE .'</body></html>';
			$CI->email->from(ADMIN_EMAIL, ADMIN_REGISTER_EMAIL_TITLE);
		}
			
		$message = $message . $msg;
		$CI->email->to($data['user_email']);
		$CI->email->subject('Welcome to Facilities InSight');
		$CI->email->message($message);
		$new_user_email_res = $CI->email->send();
		return $new_user_email_res;
		
		
		/*$CI =& get_instance();
		
		$lastname = ' '. $data['user_lname'];
		$message = '<html><body>
					Hello '. $data['user_fname'] .$lastname .',<br><br>
					We\'ve setup your account. Please find your login information below,<br><br>
					Username : '.$data['username'].'<br>
					Password : '.$data['password_email'].'<br><br>
					Thanks, <br>
					Admin.</body></html>';
		$CI->load->library('email');
		$CI->email->from(ADMIN_EMAIL, ADMIN_EMAIL_TITLE);
		$CI->email->to($data['user_email']);
		$CI->email->subject('Welcome to Facilities InSight');
		$CI->email->message($message);
		$new_user_email_res = $CI->email->send();
		return $new_user_email_res;*/
	}
}

/* Generating email for Forgot Password */
if ( ! function_exists('forgot_password_email'))
{
	function forgot_password_email($data)
	{
		$CI =& get_instance();
		
		$lastname = ' '. $data['lname'];
		$message = '<html><body>
					Hello '. $data['fname'] . $lastname .',<br><br>
					We understand you forgot your password, find a new password below. You can change this to something easier to remember under the "My Profile" option in the top right of the screen.<br><br>
					Username : '.$data['username'].'<br>
					Password : '.$data['password_email'].'<br><br>
					Thanks, <br>
					Admin.</body></html>';
		$CI->load->library('email');
		$CI->email->from(ADMIN_EMAIL, ADMIN_EMAIL_TITLE);
		$CI->email->to($data['user_email']);
		$CI->email->subject('Forgotten Password');
		$CI->email->message($message);
		$forgot_password_email_res = $CI->email->send();
		return $forgot_password_email_res;
	}
}

/* Validation for Role */
if ( ! function_exists('validate_role'))
{
	function validate_role($class_role_id)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$user_role_id = $CI->session->userdata('user_info')->role_id;
			if($class_role_id == $user_role_id)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}

	}
}

/* Get the user titles */
if ( ! function_exists('get_user_titles'))
{
	function get_user_titles()
	{
		$CI =& get_instance();
		$CI->load->model('common_model');
		$get_user_titles = $CI->common_model->get_user_titles(USER_TITLES_ID);
		return $get_user_titles;
	}
}

/* Get the data values from DB */
if ( ! function_exists('get_data_any'))
{
	function get_data_any($data)
	{
		switch ($data['data_ref']) {
			case "user_titles":
				$data_ref_id = USER_TITLES_ID;
				break;
			case "school_years":
				$data_ref_id = SCHOOL_YEARS_ID;
				break;
			case "school_reports_status":
				$data_ref_id = SCHOOL_REPORTS_STATUS_ID;
				break;
			case "menu_type":
				$data_ref_id = MENU_TYPE_ID;
				break;
			case "network":
				$data_ref_id = NETWORK_TYPE_ID;
				break;
			case "asset_type":
				$data_ref_id = ASSET_TYPE_ID;
				break;
			case "job_email":
				$data_ref_id = JOB_EMAIL_TYPE_ID;
				break;
			default:
				$data_ref_id = FALSE;
				break;
		}
		$CI =& get_instance();
		$CI->load->model('common_model');
		$get_data_any = $CI->common_model->get_data_any($data_ref_id);
		return $get_data_any;
	}
}

if ( ! function_exists('lastDateOfMonth')) {
	function lastDateOfMonth($Month, $Year=-1) {

		if ($Year < 0) $Year = 0+date("Y");
		$aMonth         = mktime(0, 0, 0, $Month, 1, $Year);
		$NumOfDay       = 0+date("t", $aMonth);
		$LastDayOfMonth = mktime(0, 0, 0, $Month, $NumOfDay, $Year);
		return $LastDayOfMonth;
	}
}

/* Validation for Contract id */
if ( ! function_exists('validate_contract'))
{
	function validate_contract($contract_id)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$customer_id = $CI->session->userdata('user_info')->customer_id;
			$CI->load->model('common_model');
			$cus_con_res = $CI->common_model->validate_contract($contract_id,$customer_id);
			
			if($cus_con_res->cnt > 0)
			{
				if($CI->session->userdata('user_info')->role_id == CUSTOMER_ADMIN)
				{
					$user_id = $CI->session->userdata('user_info')->user_id;
					$cus_admin_res = $CI->common_model->validate_contract_admin($contract_id,$user_id);
					
					if($cus_admin_res->cnt > 0)
					{
						return TRUE;
					}
					else
					{
						return FALSE;
					}
				}
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validation for Contract id */
if ( ! function_exists('validate_contract_sadmin'))
{
	function validate_contract_sadmin($data)
	{
		$CI =& get_instance();
		$CI->load->model('common_model');
		$cus_con_res = $CI->common_model->validate_contract($data['contract_id'],$data['customer_id']);
			
		if($cus_con_res->cnt > 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validation for shcool id*/
if ( ! function_exists('validate_school'))
{
	function validate_school($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$cus_con_res = $CI->common_model->validate_school($data);
			if($cus_con_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validation for Batch cancel id*/
if ( ! function_exists('validate_batch_cancel_id'))
{
	function validate_batch_cancel_id($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$batch_res = $CI->common_model->validate_batch_cancel_id($data);
			if($batch_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validation for shcool id*/
if ( ! function_exists('validate_school_admin'))
{
	function validate_school_admin($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$cus_con_res = $CI->common_model->validate_school_admin($data);
			if($cus_con_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* To get the random alphanumeric */
if ( ! function_exists('get_random_alphanum'))
{
	function get_random_alphanum($length)
	{
		//$char = substr(md5(rand()), 0, 3);
		
		//$character_array = array_merge(range('a', 'z'), range(0, 9));
		/*$character_array = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$string = "";
		for($i = 0; $i < $length; $i++) {
			//$string .= $character_array[rand(0, (count($character_array) - 1))];
			$string .= $character_array[rand(0, (strlen($character_array) - 1))];
		}
		return $string;*/
		//return substr(md5(mt_rand()), 0, $length);
		$alphaNumeric = '';
		$alphaNumeric = ALPHABETS_SMALL . NUMBERS;

		$al_num = NULL;
		for($i = 0; $i < $length; $i++)
		{
			$al_num = $al_num. $alphaNumeric[mt_rand(0, 35)];
		}
		
		return $al_num;
	}
}

/* Generate Random Password */
/*if ( ! function_exists('generate_password'))
{
	function generate_password($length)
	{		
		$alpha = get_random_alphanum(RANDOM_ALPHA_NUMERIC_LENGTH);	// Get the alphanumeric and split the string into lowercase and uppercase strings
		$alpha_lower = substr($alpha, 0, RANDOM_ALPHA_NUMERIC_UPPER_CASE_LENGTH);
		$alpha_caps = strtoupper(substr($alpha, RANDOM_ALPHA_NUMERIC_UPPER_CASE_LENGTH, strlen($alpha)));

		$alpha_numeric = $alpha_lower. $alpha_caps;
		$special = substr(str_shuffle('!@$#*<>'),0,1);	// For special characters

		$pw = $alpha_numeric . $special;
		
		return $pw;
	}
}*/

/* Generate Random Password */
if ( ! function_exists('generate_password'))
{
	function generate_password()
	{
		$alpha_numeric = '';
		$pwd = '';
		$special = SPECIAL_CHARACTERS;
		$small = ALPHABETS_SMALL;
		$capital= ALPHABETS_CAPITAL;
		$number =NUMBERS;		
		
		$alpha_numeric = ALPHABETS_SMALL. ALPHABETS_CAPITAL. NUMBERS;		
		$pwd = str_shuffle($small[(mt_rand(0,25))].$capital[(mt_rand(0,25))].$number[(mt_rand(0, 9))]);
		
		for ($i = 0; $i < 7; $i++)
		{
			$pwd = $pwd.$alpha_numeric[mt_rand(0, 62)];
		}
	
		$pwd = str_shuffle($pwd). $special[(mt_rand(0, 6))];		
		return $pwd;
	}
}

/* To check if the string contains number or not */
if( ! function_exists('ContainsNumbers'))
{
	function ContainsNumbers($String){
		return preg_match('/\d/', $String) > 0;
	}
}

/* Generating email for new parent user */
if ( ! function_exists('new_parent_user_email'))
{
	function new_parent_user_email($data)
	{
		$en_userid = number_encrypt($data['user_id']);
		$pageURL = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$pageURL= $pageURL.$_SERVER["SERVER_NAME"];
		$changepwdURL = $pageURL.CHANGE_PASSWORD_FRONTEND_LINK."?".CHANGE_PASSWORD_LINK_KEY."=".$en_userid;
		$CI =& get_instance();
		$message = '<html><body>Dear '. $data['firstName'].' '.$data['lastName'].',<br><br>
					Thank you for registering for online school meals. Please click the link below to login to the system:<br><br>
					<a href="'. $changepwdURL .'">Click here for first login</a><br><br>
					If the above link does not work, your registration details are recorded below:<br><br>
					Web site: '.$pageURL.'<br>
					Username: '.$data['emailAddress'].'<br>
					Password: '.$data['password'].'<br><br>
					Once you have logged in, you will be asked to change your password to something more memorable.<br><br>
					In the future, you can change your password and update your profile details at anytime under the "My Profile" option in the top right of your screen.<br><br>
					Regards, <br><br>
					Eden Team</body></html>';
		
		$CI->load->library('email');
		
		$CI->email->from(ADMIN_EMAIL, ADMIN_REGISTER_EMAIL_TITLE);
		$CI->email->to($data['emailAddress']);
		$CI->email->subject('Eden Team : Your user login information');
		$CI->email->message($message);
		$new_parent_email_res = $CI->email->send();
		return $new_parent_email_res;
	}
}

// To create transaction ID for both order items and payment items
if( ! function_exists('create_transaction_id'))
{
	function create_transaction_id($contract_id, $trans_mode, $trans_type){
		$CI =& get_instance();
		$CI->load->model('customeradmin_model');
		$CI->load->model('common_model');
		$contract_key = $CI->customeradmin_model->get_contract_key($contract_id);
		$sequence_no_res = $CI->common_model->get_sequence_no($contract_id);
		
		/* Check the transation type */
		if($trans_type == PAYMENT_ITEMS)
		{
			$CI->common_model->update_order_seq($contract_id, $trans_type);
			$sequence_no = $sequence_no_res->payment_seq_no;
			
			if($trans_mode == CARD)
			$code = CARD_CODE;
			else 
			$code = CASH_CODE;
		}
		else 
		{
			$CI->common_model->update_order_seq($contract_id, $trans_type);
			$sequence_no = $sequence_no_res->order_seq_no;
			
			if($trans_mode == MEAL_ORDER)
			$code = MEAL_CODE;
			else 
			$code = HOS_CODE;
		}
		
		$sequence_no++;
		for ($i=strlen($sequence_no); strlen($sequence_no) < TRANSACTION_ID_LENGTH; $i++)
		{
			$sequence_no = '0'.$sequence_no;
		}
		
		$transaction_id = $contract_key.'/'.$code.'/'.$sequence_no;
    	return $transaction_id;
	}
}

/* To Update the order sequence */
/*if( ! function_exists('update_order_sequence'))
{
	function update_order_sequence($contract_id, $trans_type){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$update_res = $CI->common_model->update_order_seq($contract_id, $trans_type);
		return $update_res;
	}
}*/

/* Validate the student id and contract id */
if( ! function_exists('validate_student_contract'))
{
	function validate_student_contract($contract_id, $pupil_id){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$check_cnt = $CI->common_model->validate_student_contract($contract_id, $pupil_id);
		if ($check_cnt > 0)
		return TRUE;
		else 
		return FALSE;
	}
}

/* Validate the user id and customer id */
if( ! function_exists('validate_user_customer'))
{
	function validate_user_customer($data){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$check_cnt = $CI->common_model->validate_user_customer($data);
		if ($check_cnt > 0)
		return TRUE;
		else 
		return FALSE;
	}
}

/* Get IP Address */
if( ! function_exists('get_client_ip_address'))
{
	function get_client_ip_address() {
    	$ipaddress = '';
    	if (getenv('HTTP_CLIENT_IP'))
        	$ipaddress = getenv('HTTP_CLIENT_IP');
    	else if(getenv('HTTP_X_FORWARDED_FOR'))
        	$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    	else if(getenv('HTTP_X_FORWARDED'))
        	$ipaddress = getenv('HTTP_X_FORWARDED');
   	 	else if(getenv('HTTP_FORWARDED_FOR'))
        	$ipaddress = getenv('HTTP_FORWARDED_FOR');
    	else if(getenv('HTTP_FORWARDED'))
        	$ipaddress = getenv('HTTP_FORWARDED');
    	else if(getenv('REMOTE_ADDR'))
        	$ipaddress = getenv('REMOTE_ADDR');
 
    	return $ipaddress;
	}
}

/* Validate the student id and contract id */
if( ! function_exists('session_log'))
{
	function get_session_log_message($data){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$msg = form_log_message($data);
		$msg = $msg . " " . $data['from'];
		return $msg;
	}
}

function form_log_message($data)
{
	$CI =& get_instance();
	$CI->load->model('common_model');
	$msg = $data['msg'];
	if($CI->session->userdata('user_session'))
	{
		$msg = $msg . ' Contract ' . $CI->session->userdata('user_info')->contract_name .',';
		$msg = $msg . ' Customer ' . $CI->session->userdata('user_info')->customer_name .',';
	}
	
	foreach($data as $key => $value)
	{
		switch($key)
		{
			/*case "contract_id":
				$contract_name = $CI->common_model->get_contract_name($value);
				$msg = $msg . ' Contract ' . $contract_name . ',';
				break;*/
				
			case "schools":
				$msg = $msg . ' Schools - ';
				foreach($value as $k => $v)
				{
					$school_name = $CI->common_model->get_school_name($v['school_id']);
					if($school_name != '')
						$msg =  $msg . $school_name . ',';
				}
				break;
				
			case "school_id":
				if($value != 0 || $value != NULL || $value != '')
				{
					$school_name = $CI->common_model->get_school_name($value);
					if($school_name != '')
						$msg = $msg . ' School ' . $school_name . ',';
				}				
				break;

			case "pupil_id":
				//$username = $CI->common_model->get_student_name($value);
				$msg = $msg . ' Pupil ' . $value . ',';
				break;
				
			case "pupils_data":
				$msg = $msg . ' Pupils - ';
				foreach($value as $k => $v)
				{
					//$school_name = $CI->common_model->get_school_name($v['school_id']);
					$msg =  $msg . $v['pupil_id'] . ',';
				}
				break;
			
			case "temp_pupil":
				$msg = $msg . ' Temporary Pupils - ';
				foreach($value as $k => $v)
				{
					//$school_name = $CI->common_model->get_school_name($v['school_id']);
					$msg =  $msg . $v . ',';
				}
				break;
			
			case "transaction_id":
				$msg = $msg . ' Transaction Id - ' . $value . ',';
				break;
		}
	}
	return $msg;
}

/* Save session log */
if( ! function_exists('session_log_message_helper'))
{
	function session_log_message_helper($data){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		if($CI->session->userdata('user_session'))
		{
			if($CI->session->userdata('user_info')->session_log == ACTIVE)
			{
				$data['user_id'] = $CI->session->userdata('user_info')->user_id;
				$data['contract_id'] = 	$CI->session->userdata('user_info')->contract_id;
				
				$data['ip_address'] = get_client_ip_address();
				$save_res = $CI->common_model->save_session_log_messages($data);
				return $save_res;
			}
		}			
	}
}

/* Validate the student id and contract id */
if( ! function_exists('get_month_name'))
{
	function get_month_name($data){
		$monthNum = $data;
		$monthName = date("F", mktime(0, 0, 0, $monthNum, 10));
		return $monthName;
	}
}

/* Validate the Customer admin profile access */
if( ! function_exists('validate_cadmin_profile_access'))
{
	function validate_cadmin_profile_access($ss_mod_id){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		if($CI->session->userdata('user_session'))
		{
			$data['ss_mod_id'] = $ss_mod_id;
			$data['profile_id'] = $CI->session->userdata('user_info')->profile_id;
			$chk_status = $CI->common_model->validate_cadmin_profile_access($data);				
			return $chk_status;
		}
		else
		{
			return FALSE;
		}		
	}
}

/* Validation for create contract access for customer admin */
if( ! function_exists('validate_create_contract_access'))
{
	function validate_create_contract_access($data){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$chk_status = $CI->common_model->validate_create_contract_access($data);				
		return $chk_status;	
	}
}

/* Number encryption */
if( ! function_exists('number_encrypt'))
{
	function number_encrypt($num){
		$en_num = base64_encode(rand(1000, 9999).$num);
		return $en_num;
	}
}

/* Number decryption */
if( ! function_exists('number_decrypt'))
{
	function number_decrypt($num){
		$de_num = base64_decode($num);
		$de_num = substr($de_num, 4);
		return $de_num;
	}
}

/* Validation for zone id*/
if ( ! function_exists('validate_zone'))
{
	function validate_zone($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$cus_con_res = $CI->common_model->validate_zone($data);
			if($cus_con_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Generate system messages for Impact order items */
if ( ! function_exists('generate_batch_system_messages'))
{
	function generate_batch_system_messages($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			
			if(isset($data['user_id']))
			{
				$name_res = $CI->common_model->get_userinfo($data['user_id']);
				$name = $name_res->first_name . ' ' . $name_res ->last_name;
			}
			
			$str = $data['str'];
			foreach($data['key_values'] as $key => $value)
			{
				switch($value)
				{
					case NAME_REPLACE_STRING:
						if(isset($name))
						{
							$str = str_replace(NAME_REPLACE_STRING, $name, $str);
						}
						break;
						
					case SCHOOL_REPLACE_STRING:
						if(isset($data['school_id']))
						{
							$school_name = $CI->common_model->get_school_name($data['school_id']);
							$str = str_replace(SCHOOL_REPLACE_STRING, $school_name, $str);
						}
						else 
						{
							$str = str_replace(SCHOOL_REPLACE_STRING, $data['school_name'], $str);
						}
						break;
						
					case SCHOOL_CLOSED_TILL_REPLACE_STRING:
						$str = str_replace(SCHOOL_CLOSED_TILL_REPLACE_STRING, $data['close_till'], $str);
						break;
						
					case REASON_REPLACE_STRING:
						$str = str_replace(REASON_REPLACE_STRING, $data['reason'], $str);
						break;
						
					case MENU_PREVIOUS_START_DATE:
						$prv_date = date('d/m/Y', strtotime($data['prv_start_date']));
						$str = str_replace(MENU_PREVIOUS_START_DATE, $prv_date, $str);
						break;
						
					case MENU_CURRENT_START_DATE:
						$cur_date = date('d/m/Y', strtotime($data['cur_start_date']));
						$str = str_replace(MENU_CURRENT_START_DATE, $cur_date, $str);
						break;
					
					case MENU_PREVIOUS_CYCLE:
						$str = str_replace(MENU_PREVIOUS_CYCLE, $data['prv_menu_cycle'], $str);
						break;
						
					case MENU_CURRENT_CYCLE:
						$str = str_replace(MENU_CURRENT_CYCLE, $data['cur_menu_cycle'], $str);
						break;
					
					case MENU_NUMBER_REPLACE_STRING:
						$str = str_replace(MENU_NUMBER_REPLACE_STRING, $data['menu_no'], $str);
						break;
					
					case WEEK_NUMBER_REPLACE_STRING:
						$str = str_replace(WEEK_NUMBER_REPLACE_STRING, $data['week_no'], $str);
						break;
					
					case SCHOOL_NAME_PREVIOUS:
						$school_name = $CI->common_model->get_school_name($data['production_id']);
						$str = str_replace(SCHOOL_NAME_PREVIOUS, $school_name, $str);
				}			
			}
			
			return $str;
		}
	}
}

/* Generating email for school close */
if ( ! function_exists('school_close_email'))
{
	function school_close_email($data)
	{
		$CI =& get_instance();
		
		$pageURL= $_SERVER["SERVER_NAME"];
		$from_addr = 'admin@'.$pageURL;
		
		$close_email_res = FALSE;
		$sub = 'School Closed: '. $data['school_name'];
		foreach($data['email_arr'] as $key => $value)
		{
			$message = '<html><body>
					Dear '. $value->first_name .' '. $value->last_name .',<br><br>
					There are meal order changes in EdenPay that need to be reviewed. This is because the school has been closed for reason: '. $data['reason'] .'. The school is closed until: '. $data['close_till'] .'.<br><br>
					Please review in the "Batch Order Cancellation" screen to decide if notifications need to be sent to parents, if orders need to be cancelled or if the changes can be ignored<br><br>
					Regards, <br>
					The Eden Team.</body></html>';
			$CI->load->library('email');
			$CI->email->from(SCHOOL_ADMIN_EMAIL, ADMIN_REGISTER_EMAIL_TITLE);
			$CI->email->to($value->user_email);
			$CI->email->subject($sub);
			$CI->email->message($message);
			$close_email_res = $CI->email->send();
		}
		return $close_email_res;
	}
}

/* Generating email for school close */
if ( ! function_exists('school_open_email'))
{
	function school_open_email($data)
	{
		$CI =& get_instance();
		
		$pageURL= $_SERVER["SERVER_NAME"];
		$from_addr = 'admin@'.$pageURL;
		
		$open_email_res = FALSE;
		$sub = 'School Re-opened: '. $data['school_name'];
		foreach($data['email_arr'] as $key => $value)
		{
			$message = '<html><body>
					Dear '. $value->first_name .' '. $value->last_name .',<br><br>
					We previously notified you that there are meal order changes in EdenPay that need to be reviewed because the school was closed. This is to inform you that the school has been reopened as of today.<br><br>
					Please review in the "Batch Order Cancellation" screen to decide if notifications need to be sent to parents, if orders need to be cancelled or if the changes can be ignored<br><br>
					Regards, <br>
					The Eden Team.</body></html>';
			$CI->load->library('email');
			$CI->email->from(SCHOOL_ADMIN_EMAIL, ADMIN_REGISTER_EMAIL_TITLE);
			$CI->email->to($value->user_email);
			$CI->email->subject($sub);
			$CI->email->message($message);
			$open_email_res = $CI->email->send();
		}		
		return $open_email_res;
	}
}

/* Validation for asset id*/
if ( ! function_exists('validate_asset'))
{
	function validate_asset($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$cus_con_res = $CI->common_model->validate_asset($data);
			if($cus_con_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Create batch cancel id */
if ( ! function_exists('create_batch_cancel'))
{
	function create_batch_cancel($data, $cancel_type_id)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$batch_cancel_id = $CI->common_model->create_batch_cancel($data, $cancel_type_id);
			return $batch_cancel_id;
		}
		else
		{
			return FALSE;
		}
	}
}

/* delete a folder and sub folder from a directory using recursion */
if ( ! function_exists('delete_directory_recursion'))
{
	function delete_directory_recursion($dir_path)
	{
		if(is_dir($dir_path))
		{
			foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir_path, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path)
			{
				$path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
			}
			rmdir($dir_path);
		}
	}
}

/* Validation for digital_pen id*/
if ( ! function_exists('validate_digital_pen'))
{
	function validate_digital_pen($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$pen_res = $CI->common_model->validate_digital_pen($data);
			if($pen_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validation for QA Account*/
if ( ! function_exists('validate_qa_account'))
{
	function validate_qa_account($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$acc_res = $CI->common_model->validate_qa_account($data);
			if($acc_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validation for QA Account & Group*/
if ( ! function_exists('validate_qa_account_group'))
{
	function validate_qa_account_group($data)
	{
		$CI =& get_instance();
		if($CI->session->userdata('user_session'))
		{
			$CI->load->model('common_model');
			$acc_res = $CI->common_model->validate_qa_account_group($data);
			if($acc_res->cnt > 0)
			{
				return TRUE;
			}
			else
			{
				return FALSE;
			}
		}
		else
		{
			return FALSE;
		}
	}
}

/* Validate the user id and customer id */
if( ! function_exists('validate_user_customer_contract'))
{
	function validate_user_customer_contract($data){
		$CI =& get_instance();
		$CI->load->model('common_model');
		
		$check_cnt = $CI->common_model->validate_user_customer_contract($data);
		if ($check_cnt > 0)
		return TRUE;
		else 
		return FALSE;
	}
}
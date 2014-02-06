<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'business/business_common.php';

class Common extends CI_Controller {
	
	public function __construct()
	{
		parent::__construct();
	}
	
	/* Getting the data values from DB */
	public function get_data_any()
	{
		$data = $this->security->xss_clean($_POST);
		//$data = array('data_ref'=>'user_titles');
		//$data = array('data_ref'=>'school_years');
		//$data = array('data_ref'=>'network');
		//$data = array('data_ref'=>'asset_type');
		
		isset($data['session_id']) ? $user_session = $data['session_id'] : $user_session = NULL;
		
		if(validate_session($user_session)) {	// Session Validation
			if(!empty($data['data_ref']))
			{
				$data_any_res = get_data_any($data);
				$data_any_obj->data_any_res = $data_any_res;
				$data_any_obj->error = FALSE;
				$data_any_obj->session_status = TRUE;
				echo json_encode($data_any_obj);
			}
			else
			{
				$data_any_obj->error = TRUE;
				$data_any_obj->error_msg = INPUT_DATA_MISSING;
				$data_any_obj->session_status = TRUE;
				echo json_encode($data_any_obj);
			}
		} else {
			$err_obj->session_status = FALSE;
			echo json_encode($err_obj);
		}
	}
	
	/* Editting the profile */
	public function edit_profile()
	{
		$data = $this->security->xss_clean($_POST);
		$user_session = $data['session_id'];
		$change_pwd = FALSE;
		if(validate_session($user_session)) {
		$data['user_id'] = $this->session->userdata('user_info')->user_id;
		if (!empty($data['title_id'])&&!empty($data['user_email'])&&!empty($data['user_id'])	// Check the input data 
			&&!empty($data['first_name'])&&!empty($data['last_name']))
		{
			if (!empty($data['current_pwd']))
			{
			  if (!empty($data['new_pwd'])&&!empty($data['renew_pwd']))
			  {
			  	$change_pwd = TRUE;
			  } 
			  else 
			  {
			  	$change_pwd = FALSE;
			  }
			}
			else
			{
			   $change_pwd = FALSE;
			}
			$bw_obj = new Business_common();
			$edit_profile = $bw_obj->password_validation($data,$change_pwd);	// validating the password
		}
		else 
		{
			$edit_profile_obj->error = TRUE;
			$edit_profile_obj->error_msg = INPUT_DATA_MISSING;
			$edit_profile_obj->session_status = TRUE;
			echo json_encode($edit_profile_obj);
		}
		} else {
			$err_obj->session_status = FALSE;
			echo json_encode($err_obj);
		}
	}
	
	/* Session Validation */
	public function validatesession(){
		$data = $this->security->xss_clean($_POST);
		if(!empty($data['session_id'])) {
		$user_session = $data['session_id'];
		if(validate_session($user_session)) {
			$validate_session_obj->session_status = TRUE;
			echo json_encode($validate_session_obj);
		} else {
			$err_obj->session_status = FALSE;
			echo json_encode($err_obj);
		}
		} else {
			$err_obj->session_status = FALSE;
			echo json_encode($err_obj);
		}
	}
	
	/* Removing the session */
	public function remove_session(){
		ob_start();
		$this->session->unset_userdata('user_session');
	}
	
	/* Checking the contract id is valid or not */
	public function check_contract()
	{
		$data = $this->security->xss_clean($_POST);
		if(!validate_contract($data['contract_id']))
 		{
			$error_obj->error = TRUE;
	 		$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
	 		$error_obj->session_status = TRUE;
	 		echo json_encode($error_obj);
	 		exit;
 		}
	}
	
	/* Common dowload file */
	public function download_file($user_session, $download_type, $id, $app_name = NULL)  {
		//if(validate_session($user_session)) {	// Session validation
			$data['id'] = $id;
			$data['user_id'] = $this->session->userdata('user_info')->user_id;
			$data['customer_id'] = $this->session->userdata('user_info')->customer_id;
			$data['download_type'] = $download_type;
			$data['app_name'] = $app_name;
			$this->load->helper('download');
			
			/* For export pupil user story*/
			if($data['download_type'] == "pupils")
			{		
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = EXPORT_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export pupil - school office user story*/
			if($data['download_type'] == "export_school_pupils")
			{
				$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
				$file_name = EXPORT_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
				unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
				force_download($file_name, $file_data);
				exit;
			}
			
			if($data['download_type'] == "payment_items")
			{
				$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
				$file_name = EXPORT_PAYMENT_ITEMS_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
				unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
				force_download($file_name, $file_data);
				exit;
			}
			
			if($data['download_type'] == "order_items")
			{
				$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
				$file_name = EXPORT_ORDER_ITEMS_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
				unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
				force_download($file_name, $file_data);
				exit;
			}
			
			if($data['download_type'] == "batch_order_items")
			{
				$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
				$file_name = EXPORT_ORDER_ITEMS_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
				unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
				force_download($file_name, $file_data);
				exit;
			}
			
			if($data['download_type'] == "digital_xml_file")
			{
				$app_name = $data['app_name'];
				$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].XML_FILE_FORMAT);	// Get the data from that .csv
				$file_name = $app_name.XML_FILE_FORMAT;	// Concat file name with extension
				unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].XML_FILE_FORMAT);	// delete the file
				force_download($file_name, $file_data);
				exit;
			}
			
			/* For export pupil balances*/
			if($data['download_type'] == "pupil_balances")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = EXPORT_PUPIL_BALANCE_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export export digital form filter*/
			if($data['download_type'] == "export_digital_form")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = DIGITAL_FORM_EXCEL_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export export digital form total daily meal number*/
			if($data['download_type'] == "export_df_tdm")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = DIGITAL_FORM_TDM_EXCEL_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export export digital form total daily meal number for custom orders*/
			if($data['download_type'] == "export_df_tdm_custom")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = DIGITAL_FORM_TDM_CUSTOM_EXCEL_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export export quality audit filter*/
			if($data['download_type'] == "export_quality_auditor_filter")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = QA_EXCEL_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export export DMO*/
			if($data['download_type'] == "export_daily_meal_order")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = DMO_EXCEL_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For export export DMO Custom*/
			if($data['download_type'] == "export_daily_meal_order_custom")
			{
				$filename = TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION;
				if(file_exists($filename))
				{
					$file_data = file_get_contents(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// Get the data from that .csv
					$file_name = DMO_CUSTOM_EXCEL_FILE_NAME.EXPORT_FILE_EXTENSION;	// Concat file name with extension
					unlink(TEMP_DOWNLOAD_FILE_PATH.$data['id'].EXPORT_FILE_EXTENSION);	// delete the file
					force_download($file_name, $file_data);
					exit;
				}
				exit;
			}
			
			/* For download skin images*/
			if($data['download_type'] == "skin_img")
			{
				$img_res = explode('-', $id);
				$img_type = $img_res[0];
				$skin_id = $img_res[1];
				if ($img_type && $skin_id)
				{
					$filename = SKIN_PATH.$skin_id.'/'.$img_type.'.'.SKIN_FILE_FORMAT;
					if(file_exists($filename))
					{
						$file_data = file_get_contents(SKIN_PATH.$skin_id.'/'.$img_type.'.'.SKIN_FILE_FORMAT);	// Get the data from that .csv
						$file_name = $img_type.'.'.SKIN_FILE_FORMAT;	// Concat file name with extension
						force_download($file_name, $file_data);
						exit;
					}
					exit;
				}
				exit;
			}
			
			$bw_obj = new Business_common();
			$file_details = $bw_obj->download_file($data);
			if(!empty($file_details)) {
				$file_data = file_get_contents($file_details[0]->file_path);
				$file_name = $file_details[0]->file_name;
				force_download($file_name, $file_data);
			} else {
				//$error_obj->error = TRUE;
				//$error_obj->error_msg = UNAUTHOURIZED_ACCESS;
				//$error_obj->session_status = TRUE;
				print_r(UNAUTHOURIZED_ACCESS);
			}
		/*} else {
			//$err_obj->session_status = FALSE;
			print_r(INVALID_SESSION);
		}*/
	}
	
	/* Validating the Parent */
	public function parent_validation()
	{
		/* Input validation */
		if (!empty($_POST['firstName'])&&!empty($_POST['lastName'])&&!empty($_POST['emailAddress'])&&!empty($_POST['sampleId']))
		{
			$data = $this->security->xss_clean($_POST);
			$data['role_id'] = USER;
			$bw_obj = new Business_common();
			$add_parent = $bw_obj->add_parent_user($data);
			if ($add_parent == EMAIL_FAILED)	// Checking the email status
			{
				$parent_validation_obj->error = TRUE;
				$parent_validation_obj->error_msg = $add_parent ;
				echo json_encode($parent_validation_obj);
			}
			else if ($add_parent)
			{
				$error_msg = NEW_PARENT_SUCCESS;
				$parent_validation_obj->error = FALSE;
				$parent_validation_obj->error_msg = $error_msg;
				echo json_encode($parent_validation_obj);
			}
			else {
				$parent_validation_obj->error = TRUE;
				$parent_validation_obj->error_msg = DATABASE_QUERY_FAILED ;
				echo json_encode($parent_validation_obj);
			}
		}
		else
		{
			$parent_validation_obj->error = TRUE;
			$parent_validation_obj->error_msg = INPUT_DATA_MISSING;
			echo json_encode($parent_validation_obj);
		}
	}
	/*public function minify_files()
	{
		$this->load->driver('minify');
		$file = '../ri_frontend/js/autosave.js';
		$contents = $this->minify->js->min($file);
		$this->minify->save_file($contents, '../ri_frontend/css/all.js');
		$this->load->driver('minify');
		echo $this->minify->combine_directory('../ri_frontend/js/', array('all.js'));
		
	}*/
	
	/* Forgot Password */
	public function forgot_password()
	{
		//$data['username'] = 'alex';
		$data = $this->security->xss_clean($_POST);
		if($data['username'] != '')
		{
			$bw_obj = new Business_common();
			$forgot_password_res = $bw_obj->forgot_password($data);
			echo json_encode($forgot_password_res);
			exit;
		}
		else
		{
			$forgot_obj->error = TRUE;
			$forgot_obj->error_msg = INPUT_DATA_MISSING;
			$forgot_obj->session_status = TRUE;
			echo json_encode($forgot_obj);
			exit;
		}
	}
	
	/* Get user details for change password */
	public function get_user_details()
	{
		//$data['key'] = number_encrypt(424);
		//$data['key'] = 'NDc5NzQyNA==';
		$data = $this->security->xss_clean($_POST);
		
		if($data['key'] != '')
		{
			$bw_obj = new Business_common();
			$user_res = $bw_obj->get_user_details($data);
			echo json_encode($user_res);
			exit;
		}
		else
		{
			$chk_user_err = new stdClass();
			$chk_user_err->error = TRUE;
			$chk_user_err->error_msg = UNAUTHOURIZED_ACCESS;
			$chk_user_err->session_status = TRUE;
			echo json_encode($chk_user_err);
			exit;
		}
	}
	
	/* Save password details - Force change password */
	public function save_change_password()
	{
		/*$data['user_id'] = '424';
		$data['password'] = 'user';
		$data['username'] = 'a9@gmail.com';*/
		$data = $this->security->xss_clean($_POST);
		
		if($data['user_id'] != '' && $data['password'] != '' && $data['username'] != '')
		{
			$bw_obj = new Business_common();
			$user_res = $bw_obj->save_change_password($data);
			echo json_encode($user_res);
			exit;
		}
		else
		{
			$chg_pass_err = new stdClass();
			$chg_pass_err->error = TRUE;
			$chg_pass_err->error_msg = UNAUTHOURIZED_ACCESS;
			echo json_encode($chg_pass_err);
			exit;
		}
	}
	
	// Folders creation for existing skins
	function create_skins_default_folders()
	{
		$bw_obj = new Business_common();
		$create_skin_res = $bw_obj->create_skins_default_folders();
		echo json_encode($create_skin_res);
	}
	
	// Folders creation for existing skins
	function create_folders_existing_skins()
	{
		$bw_obj = new Business_common();
		$create_skin_res = $bw_obj->create_folders_existing_skins();
		echo json_encode($create_skin_res);
	}
	
	function get_current_date_time()
	{
		$bw_obj = new Business_common();
		$date_res = $bw_obj->get_current_date_time();
		echo json_encode($date_res);
	}
	
	public function unzip_data() 
	{
		$this->load->library('unzip');
		$this->unzip->allow(array('css', 'png', 'jpeg', 'jpg', 'html'));
		$this->unzip->extract(FILE_UPLOAD_PATH.'app_template.zip', FILE_UPLOAD_PATH.'unzip/');
	}
	
	// Folders creation for existing digital form
	function create_digital_form_folders()
	{
		if(!is_dir(ZIP_UPLOAD_PATH))
		{
			mkdir(ZIP_UPLOAD_PATH,0755); //Digital form Directory creation with full permission
		}
		
		if(is_dir(ZIP_UPLOAD_PATH))
		{
			if(!is_dir(TEMP_ZIP_UPLOAD_PATH))
			{
				mkdir(TEMP_ZIP_UPLOAD_PATH,0755);	//Temp Directory creation with full permission
			}
		}
		
		if(is_dir(ZIP_UPLOAD_PATH) && is_dir(TEMP_ZIP_UPLOAD_PATH))
			echo TRUE;
		else 
			echo FALSE;
	}
	
	function check()
	{
		$menu_desc = "[M]F";
		if($menu_desc != ""){ 
			preg_match_all('/\[([A-Za-z0-9 ]+?)\]/',$menu_desc, $matches);
			
			for($j=0;$j<count($matches[1]);$j++){
				$ind_arr = str_split($matches[1][$j]);
				for($k=0;$k<count($ind_arr);$k++){
					echo $ind_arr[$k];
				}
				exit;
			}
			echo "<pre>";
			print_r($matches);
			exit;
		}
	}
	public function upload_photo()
	{
		$new_image_name = "test.jpg";
		print_r($_FILES);
		//move_uploaded_file($file_data["files"]["tmp_name"][0],TEMP_SKIN_PATH.$session_id.'/'.$img_type.'.'.SKIN_FILE_FORMAT);
		move_uploaded_file($_FILES["file"]["tmp_name"],FILE_UPLOAD_PATH.'/'.$new_image_name);
	}
} 
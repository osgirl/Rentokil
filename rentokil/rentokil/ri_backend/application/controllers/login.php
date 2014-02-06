<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH.'business/business_user.php';

class Login extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	// To validate All user login
	public function validate_login()
	{
 		if(!empty($_POST['username']) && !empty($_POST['password']))
 		{
			//$data = array('username'=>'cadmin','password'=>'cadmin');
			$data = $this->security->xss_clean($_POST);
			$username = $data['username'];
			$password = $data['password'];
			
			$login_obj = new stdClass();
			$login_obj->username = $username;
			$login_obj->password = $password;
			$bw_obj = new Business_user();
			$login_res = $bw_obj->validate_login($login_obj);
			
	      	echo json_encode($login_res);	      	
		}
		else
		{
			$error_obj = new stdClass();
			$error_obj->error = TRUE;
			$error_obj->error_msg = INPUT_DATA_MISSING;
			$error_obj->session_status = FALSE;
			echo json_encode($error_obj);
			exit;
		}
	}
	
}
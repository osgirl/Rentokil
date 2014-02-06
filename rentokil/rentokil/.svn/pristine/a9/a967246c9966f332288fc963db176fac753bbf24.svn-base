<?php defined('BASEPATH') OR exit('No direct script access allowed');
//require APPPATH.'/libraries/REST_Controller.php';
require_once APPPATH.'business/business_common.php';


class executeservice extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
	
	}
	//protected $rest_format = 'php';
    function discovery()
    {

    	$headers = getallheaders();
		
    	if(array_key_exists('From',$headers) && array_key_exists('User-Agent',$headers) && array_key_exists('Content-Length',$headers)){
    		
    		$from = $headers['From'];
    		//First check for User-Agent1 and get that value otherwise get User-Agent. This is for our test harness app because we are using a browser app which will give default User-Agent as browser settings.
    		$user_agent = (array_key_exists('User-Agent1',$headers)) ? $headers['User-Agent1'] :  $headers['User-Agent'];
    		$content_length = $headers['Content-Length'];

    		$post_data = file_get_contents("php://input");

    		$bw_obj = new Business_common();
    		$flag = $bw_obj->process_discovery_request($from, $user_agent, $content_length, $post_data);	//process discovery message
    		
    		$return_msg = ($flag) ? "done" : "";
    		echo $return_msg;
    	}
    	else {
    		echo "";
    		//$this->response("", 404); // 200 being the HTTP response code
    	}
		 
    	//$message = $from . "  ddd " . $user_agent.$this->post();
    	//$this->response("done", 200); // 200 being the HTTP response code
// 		 //$var = $headers['From']. "  ".$headers['User-Agent']."  ".$headers['Content-Type'].$headers['User-Length'];
//         //$this->some_model->updateUser( $this->get('id') );
//         //$message = array('a' => $this->post('a'), 's' => $this->post('s'), 'message' => $var.'ADDED!');
// 		//$var = $headers['From']. "  ".$headers['User-Agent']."  ".$headers['Content-Type'].$headers['User-Length'];
		
// 		 $message = $headers['User-Agent']."TESTST".$this->post('a').$this->post('s');
		 
// 		 //file_put_contents("test.txt", "HERE:".$this->post('a').$this->post('s')."\n");
// 		 //echo $message;
// 		 $this->response($message, 200); // 200 being the HTTP response code
    }
    
    function processform(){
    	
    	$post_data = file_get_contents("php://input");
    	if($post_data == ""){
    		foreach($_FILES as $file)
    			$post_data =  file_get_contents($file['tmp_name']);
    	}
    	
    	$post_data = str_replace('<?xml version="1.0"?>',"",$post_data);
    	
    	// Validate whether the received is in proper xml format or not. If not, return false.
    	$input_obj = simplexml_load_string($post_data);
    	if($input_obj===false){
    		echo "invalid request";
    		exit();
    	}
    	
    	$bw_obj = new Business_common();
    	$flag = $bw_obj->process_digital_form_request($post_data);	//process digital form request.
    	
    	if($flag)
    		echo "done";
    	else 
    		echo "invalid request";
    }
 }
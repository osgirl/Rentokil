<?php

class payment extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
	}
	
	public function getPaymentValues()
	{
		$data = $this->security->xss_clean($_GET);
		
		echo json_encode($data);
		exit;
	}
	
	public function payment_initiate()
	{
		$pay_obj->success = TRUE;
		$pay_obj->error = FALSE;
		echo json_encode($pay_obj);
	}
	
	public function payment_end()
	{
		$pay_obj->success = TRUE;
		$pay_obj->error = FALSE;
		echo json_encode($pay_obj);
	}
}
?>
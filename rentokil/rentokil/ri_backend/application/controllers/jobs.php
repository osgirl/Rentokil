<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'business/business_qualityservice.php';

class Jobs extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('jobs_model');
		$this->load->library ( "nusoap_lib" );
	}
	
	// To execute all async tasks
	public function execute_jobs()
	{
 		/*$this->load->library('email');
		$this->email->from(ADMIN_EMAIL, ADMIN_REGISTER_EMAIL_TITLE);
		$this->email->to('solairajesh.n@cognizant.com');
		$this->email->subject('Email from cron');
		$this->email->message('This is a message from cron');
		$this->email->send();*/
		$jobs_res = $this->jobs_model->get_jobs();
		
		if(!empty($jobs_res))
		{
			foreach ($jobs_res as $job)
			{
				
				$this->jobs_model->update_job_start($job->job_id);
				if(!empty($job->payload_xml)){
					$job_type_id = $job->job_type_id;
					switch($job_type_id){
						case JOB_EMAIL:
							$this->process_email_jobs($job);
							break;
						case JOB_QASYNC:
							$this->process_qasync_jobs($job);
							break;
						default:
							$this->jobs_model->update_job_status($job->job_id, JOB_INVALID);
							break;
					}
					
				} else
					$this->jobs_model->update_job_status($job->job_id, JOB_INVALID);
				
				//Update the job
				$this->jobs_model->update_job_end($job->job_id);
			}
		}
	}
	
	private function process_email_jobs($job_data)
	{
		$this->load->library('email');
		
		if($job_data->job_batch_type_id == JOB_BATCH_CANCEL) 
		{
			$this->process_batch_cancel_job($job_data);
		}
	}
	
	private function process_batch_cancel_job($job)
	{
			$data_obj = json_decode($job->payload_xml);
			if($data_obj->to_email)
			{
				$this->email->from($data_obj->from_email, ADMIN_REGISTER_EMAIL_TITLE);
				$this->email->to($data_obj->to_email);
				$this->email->subject($data_obj->subject);
				$this->email->message($data_obj->email_content);
				
				if($this->email->send())
				{
					$this->jobs_model->update_job_status($job->job_id, JOB_SUCCESS);
				}
				else 
				{
					$fail_reason = $this->email->print_debugger();
					$this->jobs_model->update_job_status($job->job_id, JOB_FAILED);
					$this->jobs_model->update_fail_reason($job->job_id, $fail_reason);
				}
			}
			else 
			{
				$this->jobs_model->update_job_status($job->job_id, JOB_INVALID);
			}

	}
	
	private function process_qasync_jobs($job_data){
		
		if($job_data->job_batch_type_id == JOB_BATCH_QASYNC)
		{
// 			echo "<pre>";
// 			print_r($job_data);

			$retun_flag = true;
			//Here we need to sync and update the data......
			$data_obj = json_decode($job_data->payload_xml);
			$start_date = date("d-m-Y H:i:s", strtotime($data_obj->start_date));
			$start_end_date = date("d-m-Y H:i:s",strtotime("+1 days", strtotime($start_date)));
			$end_date = date("d-m-Y H:i:s", strtotime($data_obj->end_date));

			$bw_obj = new Business_qualityservice();
			while((strtotime($end_date)- strtotime($start_end_date)) >= 0) {
				$return_flag = $bw_obj->call_quality_service ( $data_obj->account_code,$start_date, $start_end_date,false);
				if($return_flag) {
					$start_date = $start_end_date;
					$start_end_date = date("d-m-Y H:i:s",strtotime("+1 days", strtotime($start_date)));
				} else {
					$this->jobs_model->update_job_status($job_data->job_id, JOB_FAILED);
					$this->jobs_model->update_fail_reason($job_data->job_id, "There is an error processing this request at start date: " . $start_date ." !. Please try again later.");
					break;
				}
			}
			
			if($retun_flag){
				//Call the webservice once again for that day...
				$return_flag = $bw_obj->call_quality_service ( $data_obj->account_code,$start_date, $end_date,false);
			
				if(!$return_flag) {
					$this->jobs_model->update_job_status($job_data->job_id, JOB_FAILED);
					$this->jobs_model->update_fail_reason($job_data->job_id, "There is an error processing this request at start date: " . $start_date ." !. Please try again later.");
				}else 
					$this->jobs_model->update_job_status($job_data->job_id, JOB_SUCCESS);
		  	}  
		}
	}
}
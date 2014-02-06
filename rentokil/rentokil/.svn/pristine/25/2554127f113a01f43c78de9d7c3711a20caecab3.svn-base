<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jobs_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
	}

	public function get_jobs()
	{
		$this->db->select('job_id, job_type_id, job_batch_type_id, payload_xml');
		$this->db->from('jobs');
		$this->db->where('job_status',JOB_NOT_STARTED);
		$this->db->where('ignore_status',INACTIVE);
		$query = $this->db->get();
		$res_jobs = $query->result();
		return $res_jobs;
	}
	
	public function update_job_start($job_id)
	{
		$this->db->set('process_start_date', 'NOW()', FALSE);
		$this->db->where('job_id', $job_id);
		$this->db->update('jobs');
	}

	public function update_job_end($job_id)
	{
		$this->db->set('process_complete_date', 'NOW()', FALSE);
		$this->db->where('job_id', $job_id);
		$this->db->update('jobs');
	}
	
	public function update_job_status($job_id, $status)
	{
		$this->db->set('job_status', $status);
		$this->db->where('job_id', $job_id);
		$this->db->update('jobs');
	}
	
	public function update_fail_reason($job_id, $fail_reason)
	{
		$this->db->set('failure_reason', $fail_reason);
		$this->db->where('job_id', $job_id);
		$this->db->update('jobs');
	}
}
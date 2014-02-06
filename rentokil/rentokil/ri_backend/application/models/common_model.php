<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class simple_xml_extended extends SimpleXMLElement
{
	public function Attribute($name)
	{
		foreach($this->Attributes() as $key=>$val)
		{
			if($key == $name)
				return (string)$val;
		}
	}
}


class Common_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		// $this->load->database();
	}
	
	public function validate_cadmin_profile_access($data)
	{
		$this->db->select('COUNT(1) as prf_acc_cnt');
		$this->db->from('ad_profiles_ss_mod');
		$this->db->where('ad_profile_id', $data['profile_id']);
		$this->db->where('ad_ss_mod_id', $data['ss_mod_id']);
		$query = $this->db->get();
		$profile_access_res = $query->result();
		
		if($profile_access_res[0]->prf_acc_cnt > 0)
			return TRUE;
		else
			return FALSE;
	}
	
	public function validate_create_contract_access($data)
	{
		$this->db->select('create_con');
		$this->db->from('ad_profiles p');
		$this->db->join('users u', 'p.ad_profile_id = u.profile_id');
		$this->db->where('u.customer_id', $data['customer_id']);
		$this->db->where('u.role_id', CUSTOMER_ADMIN);
		$this->db->where('u.user_id', $data['user_id']);
		$this->db->where('p.customer_id', $data['customer_id']);
		$query = $this->db->get();
		$con_access_res = $query->result();
		
		if(count($con_access_res) > 0)
		{
			return $con_access_res[0]->create_con;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_user_titles($data_id)
	{
		$this->db->select('data_value_id,data_value');
		$this->db->from('data_value');
		$this->db->where('data_id',$data_id);
		$this->db->order_by("sequence_no", "asc");
		$query = $this->db->get();
		$get_user_titles = $query->result();
		return $get_user_titles;
	}

	public function get_data_any($data_id)
	{
		$this->db->select('data_value_id,data_value');
		$this->db->from('data_value');
		$this->db->where('data_id',$data_id);
		$query = $this->db->get();
		$get_data_any = $query->result();
		return $get_data_any;
	}

	public function edit_profile($profile_data,$change_pwd)
	{
		$data = array(
				'title_id' => $profile_data['title_id'],
				'first_name' => $profile_data['first_name'],
				'last_name' => $profile_data['last_name'],
				'user_email' => $profile_data['user_email'],
				'telephone' => $profile_data['telephone'],
				'work_telephone'=>$profile_data['work_tel'],
			    'mobile_number'=>$profile_data['mobile_tel'],
				'muser_id' => $profile_data['user_id'],
				'sms_notification'=> $profile_data['check_sms'],
				'mail_notification'=> $profile_data['check_email']
		);
		if ($change_pwd) {
			$data = array('password'=>md5($profile_data['renew_pwd']));
		}
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('user_id', $profile_data['user_id']);
		$edit_profile = $this->db->update('users', $data);
		return TRUE;
	}

	public function password_validation($profile_data)
	{
		$this->db->select('count(username) as count');
		$this->db->from('users');
		$this->db->where('user_id',$profile_data['user_id']);
		$this->db->where('password',md5($profile_data['current_pwd']));
		$query = $this->db->get();
		$pwd_count = $query->result();
		if ($pwd_count[0]->count == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function get_schools($data)
	{
		$this->db->select('school_id,school_name,production_status,production_id,status');
		$this->db->from('schools');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->order_by("school_name", "asc");
		$query = $this->db->get();
		$res_schools = $query->result();
		return $res_schools;
	}
	
	public function get_schools_orders($data)
	{
		$query_str = "Select DISTINCT a.school_id,a.school_name,a.production_status,a.production_id,a.status from schools a, school_classes b, students c where a.school_id = b.school_id and b.school_classes_id = c.school_classes_id and a.contract_id = ". $data['contract_id'] ." and c.user_id = ". $data['user_id']."  AND c.active = 1 ORDER BY school_name ASC";
		$query = $this->db->query($query_str);
		$res_schools = $query->result();
		return $res_schools;
	}
	
	public function get_schools_admins($data)
	{		
		$this->db->select('s.school_id,school_name,production_status,production_id,status');
		$this->db->from('schools s');
		$this->db->join('school_admins sa', 's.school_id=sa.school_id');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('user_id',$data['user_id']);
		$this->db->order_by("school_name", "asc");
		$query = $this->db->get();
		$res_schools = $query->result();
		return $res_schools;
	}
	
	public function check_all_schools_status($data)
	{
		$qry_str = "select ((SELECT count(school_id) from schools where contract_id = ". $data['contract_id'] .") - count(a.school_id)) as result from schools a, school_admins sa where contract_id = ". $data['contract_id'] ." and a.school_id = sa.school_id and sa.user_id = ". $data['user_id'];
		
		$query = $this->db->query($qry_str);
		$res_schools = $query->result();
		
		if($res_schools[0]->result > 0)
			return FALSE;
		else
			return TRUE;
	}

	public function get_contract($data)
	{
		$this->db->select('contract_name,status,min_card_pay');
		$this->db->from('contracts');
		$this->db->where('customer_id',$data['customer_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$res_contract = $query->result();
		//echo $this->db->last_query(); exit;
		$contract_offers = $this->get_contract_offers($data);
		$res_contract[0]->contract_offers = $contract_offers;
		return $res_contract;
	}

	public function get_contract_offers($data)
	{
		$this->db->select('contract_offers_id,spend,reward');
		$this->db->from('contract_offers');
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$res_contract_offers = $query->result();
		return $res_contract_offers;
	}

	public function get_school_details($data)
	{
		$query_str ="SELECT s1.school_id AS sid, s1.school_key AS sk, s1.school_name AS sn, s1.production_id as pid, s2.school_name AS psn, s1.address1 AS a1, s1.address2 AS a2, s1.address3 AS a3, s1.city AS c, s1.country AS co, s1.postcode AS po, s1.contact1_name AS c1n, s1.contact1_email AS c1e, s1.contact1_phone AS c1p, s1.contact2_name AS c2n, s1.contact2_email AS c2e, s1.contact2_phone AS c2p, DATE_FORMAT(s1.closed_till,'%e/%m/%Y') as closed_till,s1.closed_reason,(SELECT CONCAT_WS(' ',first_name,last_name) from users WHERE user_id = s1.closed_by) as closed_by, CASE WHEN s1.closed_till >= DATE(NOW()) THEN 1 ELSE 0 END AS closed_status
				FROM schools s1 INNER JOIN schools s2 ON s1.production_id = s2.school_id
				WHERE s1.school_id = ?";
		$query = $this->db->query($query_str, array($data['school_id']));
		$res_schools = $query->result();

		$query_str ="SELECT  year_label AS yl, year_status AS ys, class1_name AS c1n, class1_status AS c1s, class2_name AS c2n, class2_status AS c2s, class3_name AS c3n, class3_status AS c3s, class4_name AS c4n, class4_status AS c4s, class5_name AS c5n, class5_status AS c5s, class6_name AS c6n, class6_status AS c6s
				FROM school_classes where school_id = ? ORDER BY year_no ASC";

		$query = $this->db->query($query_str, array($data['school_id']));
		$res_school_classes = $query->result();
		$res_schools[0] -> school_classes = $res_school_classes;
		$school_admin_query_str ="SELECT u.user_id, first_name, last_name, username, profile_name, user_email,telephone, work_telephone, mobile_number, s.user_id as school_admin FROM users u
									LEFT JOIN profiles p on u.profile_id = p.profile_id and u.contract_id = p.contract_id
									LEFT JOIN school_admins s on u.user_id = s.user_id and s.school_id = ?
								WHERE u.contract_id = ? and role_id = ? AND (p.self_registration IS NULL OR p.self_registration = 0) order by first_name";
		
		$school_admin_query = $this->db->query($school_admin_query_str, array($data['school_id'], $data['contract_id'], USER));
		$res_school_admins = $school_admin_query->result();
		$res_schools[0] -> school_admins = $res_school_admins;
		return $res_schools;
	}

	public function save_school_details($data){
		parse_str( $data['form_data'], $form_data);

		$school_data = array(
			'school_key' => array_key_exists('txtSchoolId',$form_data) ? $form_data['txtSchoolId'] : "",
			'address1' => array_key_exists('txtAddress1', $form_data) ?  $form_data['txtAddress1'] :  "",
			'address2' => array_key_exists('txtAddress2', $form_data) ? $form_data['txtAddress2']: "",
			'address3' => array_key_exists('txtAddress3', $form_data) ? $form_data['txtAddress3'] :"",
			'city' => array_key_exists('txtCity', $form_data) ?  $form_data['txtCity'] : "",
			'country' => array_key_exists('txtCounty', $form_data) ? $form_data['txtCounty']:"",
			'postcode' => array_key_exists('txtPostcode', $form_data) ? $form_data['txtPostcode']:"",
			'contact1_name' => array_key_exists('txtOffC1Name', $form_data) ? $form_data['txtOffC1Name']:"",
			'contact1_email' => array_key_exists('txtOffC1Email', $form_data) ? $form_data['txtOffC1Email']:"",
			'contact1_phone' => array_key_exists('txtOffC1Telephone', $form_data) ? $form_data['txtOffC1Telephone']:"",
			'contact2_name' => array_key_exists('txtOffC2Name', $form_data) ? $form_data['txtOffC2Name']:"",
			'contact2_email' => array_key_exists('txtOffC2Email', $form_data) ? $form_data['txtOffC2Email']:"",
			'contact2_phone' => array_key_exists('txtOffC2Telephone', $form_data) ? $form_data['txtOffC2Telephone']:"",
			'muser_id' => $data['user_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('school_id', $form_data['hdnSchoolId']);
		$edit_school = $this->db->update('schools', $school_data);

		//delete existing school admins rows
		$this->db->where('school_id', $form_data['hdnSchoolId']);
		$this->db->delete('school_admins');
		
		if(array_key_exists('selUsers',$form_data)){
			//Insert newly selected school admins
			$selArray = explode(",", $form_data['selUsers']);
			foreach($selArray as $selUser){
				$school_admin_arr = array(
						'school_id' => $form_data['hdnSchoolId'],
						'user_id' => $selUser,
						'cuser_id' => $data['user_id']);
				$this->db->set('cdate', 'NOW()', FALSE);
				$sa = $this->db->insert('school_admins', $school_admin_arr);
			}
		}
		
		
		$this->db->select('MAX(year_no) AS Y');
		$this->db->from('school_classes');
		$this->db->where('school_id',$form_data['hdnSchoolId']);
		$query = $this->db->get();
		$res_school_classes = $query->result();
		$max_year_num = $res_school_classes[0]->Y;

		for($i=0; $i<$max_year_num;$i++) {
			$school_classes_data = array(
				'year_label' => $form_data['txtY'.$i],
				'year_status' =>(array_key_exists('chkY'.$i, $form_data) ? 1: 0),
				'class1_name' => $form_data['txtY'.$i.'C1'],
				'class1_status' => (array_key_exists('chkY'.$i.'C1', $form_data) ? 1 : 0),
				'class2_name' => $form_data['txtY'.$i.'C2'],
				'class2_status' => (array_key_exists('chkY'.$i.'C2', $form_data) ? 1 : 0),
				'class3_name' => $form_data['txtY'.$i.'C3'],
				'class3_status' => (array_key_exists('chkY'.$i.'C3', $form_data) ? 1 : 0),
				'class4_name' => $form_data['txtY'.$i.'C4'],
				'class4_status' => (array_key_exists('chkY'.$i.'C4', $form_data) ? 1 : 0),
				'class5_name' => $form_data['txtY'.$i.'C5'],
				'class5_status' => (array_key_exists('chkY'.$i.'C5', $form_data) ? 1 :0),
				'class6_name' => $form_data['txtY'.$i.'C6'],
				'class6_status' => (array_key_exists('chkY'.$i.'C6', $form_data) ? 1 : 0),
				'muser_id' => $data['user_id'],
			);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('school_id', $form_data['hdnSchoolId']);
			$this->db->where('year_no', ($i+1));
			$edit_school_classes = $this->db->update('school_classes', $school_classes_data);
		}
		$add_school_classes = null;
		for(;$i<7; $i++){
			$school_classes_data = array(
				'school_id' =>$form_data['hdnSchoolId'],
				'year_no' => ($i+1),
				'year_label' => $form_data['txtY'.$i],
				'year_status' => $form_data['chkY'.$i],
				'class1_name' => $form_data['txtY'.$i.'C1'],
				'class1_status' => (array_key_exists('chkY'.$i.'C1', $form_data) ? 1 : 0),
				'class2_name' => $form_data['txtY'.$i.'C2'],
				'class2_status' => (array_key_exists('chkY'.$i.'C2', $form_data) ? 1 : 0),
				'class3_name' => $form_data['txtY'.$i.'C3'],
				'class3_status' => (array_key_exists('chkY'.$i.'C3', $form_data) ? 1 : 0),
				'class4_name' => $form_data['txtY'.$i.'C4'],
				'class4_status' => (array_key_exists('chkY'.$i.'C4', $form_data) ? 1 : 0),
				'class5_name' => $form_data['txtY'.$i.'C5'],
				'class5_status' => (array_key_exists('chkY'.$i.'C5', $form_data) ? 1 :0),
				'class6_name' => $form_data['txtY'.$i.'C6'],
				'class6_status' => (array_key_exists('chkY'.$i.'C6', $form_data) ? 1 : 0),
				'cuser_id' => $data['user_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_school_classes = $this->db->insert('school_classes', $school_classes_data);
		}
		$res_schools[0] -> edit_school = $edit_school;
		$res_schools[1] -> edit_school_classes = $edit_school_classes;
		$res_schools[2] -> add_school_classes = $add_school_classes;
		return $res_schools;
	}

	public function validate_contract($contract_id,$customer_id)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('contracts');
		$this->db->where('contract_id',$contract_id);
		$this->db->where('customer_id',$customer_id);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		return $cus_con_res[0];
	}
	
	public function validate_contract_admin($contract_id,$user_id)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('cadmin_contract');
		$this->db->where('contract_id',$contract_id);
		$this->db->where('user_id',$user_id);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		return $cus_con_res[0];
	}

	public function validate_school($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('schools');
		$this->db->where('school_id',$data['school_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		return $cus_con_res[0];
	}
	
	public function validate_school_admin($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('school_admins');
		$this->db->where('school_id',$data['school_id']);
		$this->db->where('user_id', $data['user_id']);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		return $cus_con_res[0];
	}

	public function get_school_documents($data)
	{
		$query_str ="SELECT sr.school_documents_id, sr.school_id, s.school_name, file_name, file_path, document_status, d.data_value AS status, DATE_FORMAT(sr.cdate,'%d/%m/%Y at %H:%i') as cdate, sr.cuser_id, u.username, COALESCE(t.comment_text,'') AS comment, COALESCE(t.comment_status,1) AS comm_status, GREATEST(sr.cdate,COALESCE(t.cdate,sr.cdate)) as latest_date
					FROM school_documents sr INNER JOIN users u ON sr.cuser_id = u.user_id
					INNER JOIN schools s ON sr.school_id = s.school_id
					INNER JOIN contracts c ON s.contract_id = c.contract_id INNER JOIN data_value d on sr.document_status = d.data_value_id
					LEFT JOIN (SELECT sr.school_documents_id, sr.comment_text, sr.comment_status, sr.cdate  from school_documents_comments sr
							   INNER JOIN(SELECT school_documents_id, MAX(school_documents_comments_id) srcid FROM `school_documents_comments` src
							   INNER JOIN users u ON u.user_id = src.cuser_id and role_id = ? GROUP BY school_documents_id ) t1 
							   ON t1.srcid = sr.school_documents_comments_id and t1.school_documents_id = sr.school_documents_id ) t ON sr.school_documents_id = t.school_documents_id WHERE sr.status=1 AND c.contract_id = ?";
		
		$qry_arr = array($data['other_role_id'], $data['contract_id']);
		if(!empty($data['school_id']) && $data['school_id'] != 0)
		{
			$query_str = $query_str." AND s.school_id = ?";
			$qry_arr[] = $data['school_id'];
		}

		if($data['hide_comp'] =='true')
		$query_str = $query_str." AND d.data_value !='Completed'";
			
		$query_str = $query_str." ORDER BY latest_date DESC";
		$query = $this->db->query($query_str, $qry_arr);
		$schools_rep = $query->result();

		$schools_res = $this->get_schools($data);
		$schools_documents[0]->schools_res =$schools_res;

		$school_document_status_res = $this->get_data_any(SCHOOL_REPORTS_STATUS_ID);
		$schools_documents[1]->school_document_status_res=$school_document_status_res;
		$schools_documents[2]->school_rep = $schools_rep;

		return $schools_documents;
	}

	public function import_school_document($data) {
		$school_document_data = array(
				'school_id' => $data['school_id'],
				'file_name' => $data['file_name'],
				'file_path' => $data['upload_file_path'],
				'document_status' => $data['document_status'],
				'status' => 1,
				'cuser_id' => $data['user_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$add_school_documents = $this->db->insert('school_documents', $school_document_data);
		$school_documents_id = $this->db->insert_id();
		if(!empty($data['comments'])) {
			$school_document_comm_data = array(
					'school_documents_id' => $school_documents_id,
					'comment_text' => $data['comments'],
					'comment_status' => 0,  // 0 is for unread and 1 is for read
					'cuser_id' => $data['user_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_school_documents = $this->db->insert('school_documents_comments', $school_document_comm_data);
		}
		return $add_school_documents;
	}


	public function get_school_document_comments($data){

		$query_str ="SELECT comment_text, DATE_FORMAT(sr.cdate,'%d/%m/%Y %H:%i') AS cdate, u.username, r.role_name FROM
					 school_documents_comments sr  INNER JOIN users u ON sr.cuser_id = u.user_id
					 INNER JOIN roles r ON r.role_id = u.role_id
					 WHERE sr.school_documents_id = ".$data['school_document_id'];

		$query_str = $query_str." ORDER BY sr.cdate DESC";
		$query = $this->db->query($query_str);
		$schools_rep_comm = $query->result();

		$school_comment_id_query = "SELECT school_documents_comments_id FROM  school_documents_comments  sr
				INNER JOIN users u ON sr.cuser_id = u.user_id AND u.role_id = ".$data['other_role_id']." WHERE sr.school_documents_id = ". $data['school_document_id']." AND comment_status=0";


		$query = $this->db->query($school_comment_id_query);
		$school_comment_id = $query->result();

		$school_documents_comments_id = array();
		for($i=0;$i<count($school_comment_id);$i++)
		$school_documents_comments_id[$i] = $school_comment_id[$i]->school_documents_comments_id;

		//$school_documents_comments_id = substr($school_documents_comments_id, 0, -1);
		if(count($school_documents_comments_id) >0)
		{
			$document_data = array(
			  				'comment_status' => 1,
			  				'muser_id' => $data['user_id'],
			);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where_in('school_documents_comments_id', $school_documents_comments_id);
			$school_documents = $this->db->update('school_documents_comments', $document_data);
		}

		return $schools_rep_comm;
	}
	public function update_school_document_status($data){
		$document_data = array(
				'document_status' => $data['status'],
				'muser_id' => $data['user_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('school_documents_id', $data['school_document_id']);
		$school_documents = $this->db->update('school_documents', $document_data);
		return $school_documents;
	}

	public function insert_document_comments($data){
		$comment_data = array(
				'school_documents_id' => $data['school_document_id'],
				'comment_text' => $data['comments'],
				'comment_status' => 0, // 0 is for unread and 1 is for read
				'cuser_id' => $data['user_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$add_document_comments = $this->db->insert('school_documents_comments', $comment_data);
		return $add_document_comments;
	}

	public function delete_document($data){
		$document_data = array(
				'muser_id' => $data['user_id'],
				'status' => 0
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('school_documents_id', $data['school_document_id']);
		$school_documents = $this->db->update('school_documents', $document_data);
		return $school_documents;
	}


	public function get_school_document_file($data) {

		$query_str ="SELECT sr.file_name, sr.file_path FROM school_documents sr INNER JOIN schools s ON sr.school_id = s.school_id INNER JOIN
				(SELECT c.contract_id FROM contracts c INNER JOIN users u ON c.contract_id = u.contract_id INNER JOIN roles r on u.role_id =r.role_id WHERE u.user_id = ".$data['user_id']." AND r.role_name = 'USER'
				UNION ALL
				SELECT c.contract_id FROM contracts c INNER JOIN users u on u.customer_id = c.customer_id INNER JOIN roles r on u.role_id =r.role_id WHERE u.user_id = ".$data['user_id']." AND r.role_name='Customer Admin') t 
				ON s.contract_id = t.contract_id WHERE sr.school_documents_id = ".$data['id']." AND sr.status = 1";

		$query = $this->db->query($query_str);
		$file_details = $query->result();

		return $file_details;
	}

	public function get_energy_document_file($data) {

		$query_str ="SELECT sr.file_name, sr.file_path FROM energy_documents sr INNER JOIN contracts s ON sr.contract_id = s.contract_id INNER JOIN
				(SELECT c.contract_id FROM contracts c INNER JOIN users u ON c.contract_id = u.contract_id INNER JOIN roles r on u.role_id =r.role_id WHERE u.user_id = ".$data['user_id']." AND r.role_name = 'USER'
				UNION ALL
				SELECT c.contract_id FROM contracts c INNER JOIN users u on u.customer_id = c.customer_id INNER JOIN roles r on u.role_id =r.role_id WHERE u.user_id = ".$data['user_id']." AND r.role_name='Customer Admin') t 
				ON s.contract_id = t.contract_id WHERE sr.energy_documents_id = ".$data['id']." AND sr.status = 1";

		$query = $this->db->query($query_str);
		$file_details = $query->result();
		return $file_details;
	}

	public function get_energy_documents($data)
	{
		$qry_arr = array();
		$query_str ="SELECT sr.energy_documents_id, sr.contract_id, s.contract_name, file_name, file_path, document_status, d.data_value AS status, DATE_FORMAT(sr.cdate,'%d/%m/%Y at %H:%i') as cdate, sr.cuser_id, u.username, COALESCE(t.comment_text,'') AS comment, COALESCE(t.comment_status,1) AS comm_status, GREATEST(sr.cdate,COALESCE(t.cdate,sr.cdate)) as latest_date
					FROM energy_documents sr INNER JOIN users u ON sr.cuser_id = u.user_id
					INNER JOIN contracts s ON sr.contract_id = s.contract_id
					INNER JOIN data_value d on sr.document_status = d.data_value_id
					LEFT JOIN (SELECT sr.energy_documents_id, sr.comment_text, sr.comment_status, sr.cdate  from energy_documents_comments sr
							   INNER JOIN(SELECT energy_documents_id, MAX(energy_documents_comments_id) srcid FROM `energy_documents_comments` src
							   INNER JOIN users u ON u.user_id = src.cuser_id and role_id = ? GROUP BY energy_documents_id ) t1 
							   ON t1.srcid = sr.energy_documents_comments_id and t1.energy_documents_id = sr.energy_documents_id ) t ON sr.energy_documents_id = t.energy_documents_id WHERE sr.status=1 AND s.contract_id = ?";
		
		$qry_arr = array($data['other_role_id'], $data['contract_id']);
		
		if(!empty($data['contract_id']) && $data['contract_id'] != 0)
		{
			$query_str = $query_str." AND s.contract_id = ". $data['contract_id'];
			$qry_arr[] = $data['contract_id'];
		}

		if($data['hide_comp'] =='true')
		{
			$query_str = $query_str." AND d.data_value !='Completed'";
		}
			
		$query_str = $query_str." ORDER BY latest_date DESC";
		$query = $this->db->query($query_str, $qry_arr);

		$energy_rep = $query->result();
		//echo $this->db->last_query(); exit;
		//$energy_res = $this->get_contract($data);

		//$energy_documents[0]->energy_res =$energy_res;

		$energy_document_status_res = $this->get_data_any(SCHOOL_REPORTS_STATUS_ID);
		$energy_documents[0]->energy_document_status_res=$energy_document_status_res;
		$energy_documents[1]->energy_rep = $energy_rep;

		return $energy_documents;
	}

	public function import_energy_document($data) {
		$energy_document_data = array(
				'contract_id' => $data['contract_id'],
				'file_name' => $data['file_name'],
				'file_path' => $data['upload_file_path'],
				'document_status' => $data['document_status'],
				'status' => 1,
				'cuser_id' => $data['user_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$add_energy_documents = $this->db->insert('energy_documents', $energy_document_data);
		$energy_documents_id = $this->db->insert_id();
		if(!empty($data['comments'])) {
			$energy_document_comm_data = array(
					'energy_documents_id' => $energy_documents_id,
					'comment_text' => $data['comments'],
					'comment_status' => 0,  // 0 is for unread and 1 is for read
					'cuser_id' => $data['user_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$add_energy_documents = $this->db->insert('energy_documents_comments', $energy_document_comm_data);
		}
		return $add_energy_documents;
	}

	public function get_energy_document_comments($data){
		$query_str ="SELECT comment_text, DATE_FORMAT(sr.cdate,'%d/%m/%Y %H:%i') AS cdate, u.username, r.role_name FROM
					 energy_documents_comments sr  INNER JOIN users u ON sr.cuser_id = u.user_id
					 INNER JOIN roles r ON r.role_id = u.role_id
					 WHERE sr.energy_documents_id = ".$data['energy_documents_id'];

		$query_str = $query_str." ORDER BY sr.cdate DESC";
		$query = $this->db->query($query_str);
		$energy_rep_comm = $query->result();

		$energy_comment_id_query = "SELECT energy_documents_comments_id FROM  energy_documents_comments  sr
				INNER JOIN users u ON sr.cuser_id = u.user_id AND u.role_id = ".$data['other_role_id']." WHERE sr.energy_documents_id = ". $data['energy_documents_id']." AND comment_status=0";


		$query = $this->db->query($energy_comment_id_query);
		$energy_comment_id = $query->result();

		$energy_documents_comments_id = array();
		for($i=0;$i<count($energy_comment_id);$i++)
		$energy_documents_comments_id[$i] = $energy_comment_id[$i]->energy_documents_comments_id;

		if(count($energy_documents_comments_id) >0)
		{
			$document_data = array(
			  				'comment_status' => 1,
			  				'muser_id' => $data['user_id'],
			);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where_in('energy_documents_comments_id', $energy_documents_comments_id);
			$energy_documents = $this->db->update('energy_documents_comments', $document_data);
		}
		return $energy_rep_comm;
	}

	public function update_energy_document_status($data){
		$document_data = array(
				'document_status' => $data['status'],
				'muser_id' => $data['user_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('energy_documents_id', $data['energy_documents_id']);
		$energy_documents = $this->db->update('energy_documents', $document_data);
		return $energy_documents;
	}

	public function insert_energy_document_comments($data){
		$comment_data = array(
				'energy_documents_id' => $data['energy_documents_id'],
				'comment_text' => $data['comments'],
				'comment_status' => 0, // 0 is for unread and 1 is for read
				'cuser_id' => $data['user_id']
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$add_energy_document_comments = $this->db->insert('energy_documents_comments', $comment_data);
		return $add_energy_document_comments;
	}

	public function delete_energy_document($data){
		$energy_document_data = array(
				'muser_id' => $data['user_id'],
				'status' => 0
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('energy_documents_id', $data['energy_documents_id']);
		$energy_documents = $this->db->update('energy_documents', $energy_document_data);
		return $energy_documents;
	}

	public function pupil_exist($data) {
		$pupil_count = $this->get_pupils_from_school($data);
		if ($pupil_count == 0)
		{
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	public function user_exist($data) {
		$user_count = $this->get_users($data);
		if ($user_count == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function get_users($data)
	{
		$this->db->select('count(username) as count');
		$this->db->from('users');
		$this->db->where('username',$data['emailAddress']); //Here sampleId is pupil Id
		$query = $this->db->get();
		$user_con_res = $query->result();
		return $user_con_res[0]->count;
	}

	public function get_pupils_from_school($data)
	{
		$this->db->select('count(pupil_id) as count');
		$this->db->from('students');
		$this->db->where('pupil_id',$data['sampleId']); //Here sampleId is pupil Id
		$query = $this->db->get();
		$pupils_con_res = $query->result();

		return $pupils_con_res[0]->count;
	}

	public function get_student_id($data)
	{
		$this->db->select('students_id');
		$this->db->from('students');
		$this->db->where('pupil_id',$data['sampleId']); //Here sampleId is pupil Id
		$query = $this->db->get();
		$students_res = $query->result();
		return $students_res[0]->students_id;
	}

	public function registered_pupil($data)
	{
		$this->db->select('count(username) as count');
		$this->db->from('users');
		$this->db->where('students_id',$data); //student id
		$query = $this->db->get();
		$students_con_res = $query->result();
		if ($students_con_res[0]->count == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function registered_users($data)
	{
		$this->db->select('count(user_id) as count');
		$this->db->from('students');
		$this->db->where('pupil_id',$data);
		$query = $this->db->get();
		$students_con_res = $query->result();
		if ($students_con_res[0]->count == 0)
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}

	public function add_parent_user($data){
		/*$contract_res= $this->get_contract_customer_id($data);
		$data['contract_id'] = $contract_res[0]->contract_id;
		$data['customer_id'] = $contract_res[0]->customer_id;*/
		$parent_registration_data = array(
				'role_id'   => $data['role_id'],
				'customer_id'=> $data['customer_id'],
				'profile_id' => $data['profile_id'],
		        'username'  => $data['userName'],
				'password'  => $data['password_db'],
		        'first_name' => $data['firstName'],
				'last_name' => $data['lastName'],
				'user_email' => $data['emailAddress'],
				'status' => ACTIVE, // 1 is for Active and 0 is for InActive
			    'cuser_id' => 0,// 0 is for New User
				'contract_id'=> $data['contract_id'],
				'chg_pwd' => ACTIVE		
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$this->db->insert('users', $parent_registration_data);
		$user_id = $this->db->insert_id();
		
		$update_student_data = array(
									'user_id' => $user_id
								);
		$this->db->where('pupil_id',$data['sampleId']);
		//$this->db->where('status', ACTIVE);
		$this->db->where('active', ACTIVE);
		$this->db->update('students',$update_student_data);
		
		return $user_id;
	}

	public function get_contract_customer_id($data)
	{
		$this->db->select('contract_id,customer_id');
		$this->db->from('contracts');
		$this->db->where('contract_key',$data['pupil_id']);
		$query = $this->db->get();
		$contract_res = $query->result();
		return $contract_res;
	}
	
	public function get_contract_profile_id($data)
	{
		$this->db->select('profile_id');
		$this->db->from('profiles');
		$this->db->where('self_registration',ACTIVE);
		$this->db->where('status', ACTIVE);
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$profile_res = $query->result();
		return $profile_res;
	}

	/*
	 public function get_user_id($data)
	 {
		$this->db->select('user_id');
		$this->db->from('users');
		$this->db->where('username',$data['emailAddress']);
		$query = $this->db->get();
		$user_res = $query->result();
		//echo $this->db->last_query(); exit;
		return $user_res[0]->user_id;
		}
		*/

	/*
	 public function update_user_id($data){
		$report_data = array(
		'user_id' => $data['user_id']
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('students_id', $data['student_id']);
		$student_rep = $this->db->update('students', $report_data);
		//echo $this->db->last_query(); exit;
		return $student_rep;
		}*/

	public function get_download_failed_import($data) {

		$query_str ="SELECT f.file_name, f.file_path FROM files f INNER JOIN contracts c ON f.contract_id = c.contract_id
					INNER JOIN users u ON u.customer_id = c.customer_id 
					WHERE u.user_id = ".$data['user_id']." AND u.role_id = ".CUSTOMER_ADMIN." AND bsdata_type = '".$data['download_type']."' 
					AND f.file_id =".$data['id'];

		$query = $this->db->query($query_str);
		$file_details = $query->result();
		return $file_details;
	}
	public function validate_school_key($data){
		parse_str( $data['form_data'], $form_data);

		if(!array_key_exists('txtSchoolId',$form_data))
			return true;
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('schools');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('school_key',$form_data['txtSchoolId']);
		$this->db->where('school_id !=' , $form_data['hdnSchoolId']);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		if($cus_con_res[0]->cnt == 0)
			return true;
		else 
			return false;
	}
	
	public function get_sequence_no($contract_id)
	{
		$this->db->select('order_seq_no,payment_seq_no');
		$this->db->from('order_sequence');
		$this->db->where('contract_id',$contract_id);
		$query = $this->db->get();
		$sequence_no_res = $query->result();
		return $sequence_no_res[0];
	}
	
	public function update_order_seq($contract_id, $trans_type)
	{
		if($trans_type == PAYMENT_ITEMS)
		{
			$query = "UPDATE order_sequence SET payment_seq_no = payment_seq_no+1 where contract_id = ".$contract_id;
		}
		else
		{
			$query = "UPDATE order_sequence SET order_seq_no = order_seq_no+1 where contract_id = ".$contract_id;
		}
		$qry = $this->db->query($query);
		return $qry;
	}
	
	public function validate_student_contract($contract_id, $pupil_id)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->where('s.contract_id', $contract_id);
		$this->db->where('st.pupil_id', $pupil_id);
		//$this->db->where('st.status', ACTIVE);
		$query = $this->db->get();
		$stu_con_res = $query->result();
		return $stu_con_res[0]->cnt;
	}
	
	public function validate_user_customer($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('users');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('customer_id', $data['customer_id']);
		$query = $this->db->get();
		$user_con_res = $query->result();
		return $user_con_res[0]->cnt;
	}
	
	public function get_user_email($data)
	{
		$this->db->select('user_id, first_name, last_name, user_email');
		$this->db->from('users');
		$this->db->where('username',$data['username']);
		$query = $this->db->get();
		$user_res = $query->result();
		return $user_res[0];
	}
	
	public function update_password($data)
	{
		$user_data = array(
				'password' => $data['password']
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('user_id', $data['user_id']);
		$update_password = $this->db->update('users', $user_data);
		return $update_password;
	}
	
	public function save_session_log_messages($data)
	{			
		$log_data = array(
			'user_id' => $data['user_id'],
			'contract_id' => $data['contract_id'],
			'ip_address' => $data['ip_address'],
			'message' => $data['message'],
		);
		$this->db->set('cdate', 'NOW()', FALSE);
		$save_session_log = $this->db->insert('session_logs', $log_data);
		return TRUE;
	}
	
	public function check_user_id($user_id)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('users');
		$this->db->where('user_id', $user_id);
		$this->db->where('chg_pwd', ACTIVE);
		//$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		
		$chk_res = $query->result();
		return $chk_res[0]->cnt;
	}
	
	public function get_user_details($user_id)
	{
		$this->db->select('user_id, username, first_name, last_name');
		$this->db->from('users');
		$this->db->where('user_id', $user_id);
		$this->db->where('chg_pwd', ACTIVE);
		//$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		
		$user_res = $query->result();
		return $user_res;
	}
	
	public function save_change_password($data)
	{	
		$user_data = array(
				'password' => md5($data['password']),
				'chg_pwd' => INACTIVE
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('user_id', $data['user_id']);
		$update_password = $this->db->update('users', $user_data);
		
		return $update_password;
	}
	
	public function get_existing_skins()
	{
		$this->db->select('skin_id');
		$this->db->from('skins');
		$query = $this->db->get();
		$get_skin_details = $query->result();
		return $get_skin_details;
	}
	
	public function get_meal_order_summary($data)
	{
		//$sql = "SELECT DATE_FORMAT(NOW(), '%e/%c/%Y,%H:%i:%s') as cur_date";
		$sql = "SELECT DATE_FORMAT(NOW(), '%M %d, %Y, %H:%i:%s') as cur_date";
		$query = $this->db->query($sql);
		$date_res = $query->result();
		$summary_res['date_time'] = $date_res[0]->cur_date;
		
		/* Get the school close related details */
		$qry = "SELECT DATE_FORMAT(s.closed_till,'%e/%m/%Y') as closed_till, s.closed_reason, (SELECT CONCAT_WS(' ',first_name,last_name) from users WHERE user_id = s.closed_by) as closed_by, CASE WHEN s.closed_till > DATE(NOW()) THEN 1 ELSE 0 END AS closed_status FROM schools s where s.school_id = ?";
		$query = $this->db->query($qry, array($data['school_id']));
		$school_details = $query->result();
		$summary_res['close_details'] = $school_details[0];
	
		//Get the menu cycle, week cycle for the given date
		$menu_week_sql = "SELECT menu_sequence, week_cycle,menu_start_date FROM order_items o
				INNER JOIN sc_cater_menu_settings sc on o.sc_cater_menu_settings_id = sc.sc_cater_menu_settings_id
				INNER JOIN con_cater_menu_details cmd on sc.con_cater_menu_details_id = cmd.con_cater_menu_details_id
				INNER JOIN con_cater_menu_settings cms on cmd.con_cater_menu_settings_id = cms.con_cater_menu_settings_id
				INNER JOIN schools s on o.school_id = s.school_id and s.school_id = ? AND s.contract_id = ?
				WHERE fulfilment_date  BETWEEN ? AND ?
				AND o.order_status =0 AND o.order_edited = 0 LIMIT 1";
		$menu_week_query = $this->db->query($menu_week_sql,array($data['school_id'],$data['contract_id'],$data['start_date'],$data['end_date']));
		$menu_week_res = $menu_week_query->result();
	
	
		if($menu_week_res!=null) {
			// Now find out the previous week start date for given menu cycle and week cycle.
			$prev_week_sql = "SELECT DATE_SUB(fulfilment_date, INTERVAL WEEKDAY(fulfilment_date) DAY) AS fulfilment_date ,DATEDIFF(? , DATE_SUB(fulfilment_date, INTERVAL WEEKDAY(fulfilment_date) DAY)) AS no_days FROM order_items o
					INNER JOIN sc_cater_menu_settings sc on o.sc_cater_menu_settings_id = sc.sc_cater_menu_settings_id
					INNER JOIN con_cater_menu_details cmd on sc.con_cater_menu_details_id = cmd.con_cater_menu_details_id
					INNER JOIN con_cater_menu_settings cms on cmd.con_cater_menu_settings_id = cms.con_cater_menu_settings_id
					INNER JOIN schools s on o.school_id = s.school_id and s.school_id = ? AND s.contract_id = ?
				WHERE fulfilment_date  < ?  AND fulfilment_date  >='".$menu_week_res[0]->menu_start_date."'
				AND o.order_status = 0 AND o.order_edited = 0 AND menu_sequence = ".$menu_week_res[0]->menu_sequence." and week_cycle = ".$menu_week_res[0]->week_cycle."
				ORDER BY fulfilment_date DESC LIMIT 1";
	
			$prev_week_query = $this->db->query($prev_week_sql, array($data['start_date'],$data['school_id'],$data['contract_id'],$data['start_date']));
			$prev_week_res = $prev_week_query->result();
	
			//prepare the query for current week menu cyecl
			$total_query_sql = "SELECT fulfilment_date,meal_type,Count(1) t1, 0 t2 FROM order_items o
				INNER JOIN schools s on o.school_id = s.school_id and s.school_id = ? AND s.contract_id = ?
				WHERE o.order_status = 0 AND o.order_edited = 0
				AND fulfilment_date BETWEEN ? AND ?
				GROUP BY fulfilment_date,meal_type";
			$arg_arr = array($data['school_id'],$data['contract_id'],$data['start_date'],$data['end_date']);
			$total_prev_query_sql ="";
			$detail_sub_query ="LEFT JOIN (SELECT 0 AS sc_cater_menu_settings_id, 0  t2 )b on sc.sc_cater_menu_settings_id = b.sc_cater_menu_settings_id";
			if($prev_week_res != null ){
	
				//prepare the query for previous menu cycle
				$total_prev_query_sql = " UNION ALL SELECT DATE_ADD(fulfilment_date, INTERVAL ".$prev_week_res[0]->no_days." DAY) AS fulfilment_date,meal_type,0 t1, Count(1) t2 FROM order_items o
				INNER JOIN schools s on o.school_id = s.school_id and s.school_id = ? AND s.contract_id = ?
				WHERE o.order_status = 0 AND o.order_edited = 0
				AND fulfilment_date BETWEEN '".$prev_week_res[0]->fulfilment_date."' AND DATE_ADD('".$prev_week_res[0]->fulfilment_date."', INTERVAL 5 DAY)
				GROUP BY fulfilment_date,meal_type";
					
				$detail_sub_query ="LEFT  JOIN (SELECT DATE_ADD(fulfilment_date, INTERVAL ".$prev_week_res[0]->no_days." DAY) AS fulfilment_date,   sc_cater_menu_settings_id , Count(1) t2  FROM order_items WHERE school_id = ".$this->db->escape_str($data['school_id'])."  AND fulfilment_date BETWEEN '".$prev_week_res[0]->fulfilment_date."' AND DATE_ADD('".$prev_week_res[0]->fulfilment_date."', INTERVAL 5 DAY)
				AND order_status = 0 AND order_edited = 0 GROUP BY fulfilment_date,   sc_cater_menu_settings_id)b ON sc.sc_cater_menu_settings_id = b.sc_cater_menu_settings_id";
				
				array_push($arg_arr, $data['school_id'],$data['contract_id']);
			}
			$total_query_sql = "SELECT DATE_FORMAT(date(fulfilment_date), '%d/%m/%Y') AS d ,meal_type mt, SUM(t1) t1, SUM(t2) t2 FROM(".$total_query_sql.$total_prev_query_sql.") a GROUP BY fulfilment_date,meal_type ORDER BY fulfilment_date, meal_type";
			$total_query = $this->db->query($total_query_sql, $arg_arr);
			$total_query_res = $total_query->result();
			$summary_res['total_res'] = $total_query_res;
	
			//Now get the individual data for each date...
	
			$details_query_sql ="SELECT DATE_FORMAT(DATE_ADD(?, INTERVAL week_day-1 DAY), '%d/%m/%Y') AS d, meal_type AS mt, option_sequence AS os,option_details AS od, CASE WHEN a.t1 IS NULL THEN 0 ELSE a.t1 END AS t1, CASE WHEN b.t2 IS NULL THEN '-' ELSE b.t2 END AS t2
				FROM
				sc_cater_menu_settings sc
				INNER JOIN con_cater_menu_details cmd on cmd.con_cater_menu_details_id = sc.con_cater_menu_details_id
				INNER JOIN con_cater_menu_settings cms on cms.con_cater_menu_settings_id = cmd.con_cater_menu_settings_id
				LEFT  JOIN (
				SELECT fulfilment_date,   sc_cater_menu_settings_id , Count(1) t1  FROM order_items WHERE school_id = ?  AND fulfilment_date BETWEEN ? AND ?
				AND order_status = 0 AND order_edited = 0 GROUP BY fulfilment_date,   sc_cater_menu_settings_id)a ON sc.sc_cater_menu_settings_id = a.sc_cater_menu_settings_id ".$detail_sub_query."
				WHERE school_id = ? AND sc.status = 1 AND menu_sequence = ".$menu_week_res[0]->menu_sequence." and week_cycle = ".$menu_week_res[0]->week_cycle." AND contract_id = ?
				AND option_status = 1
				ORDER BY week_day, meal_type, option_sequence";
			$details_query = $this->db->query($details_query_sql, array($data['start_date'],$data['school_id'],$data['start_date'],$data['end_date'],$data['school_id'],$data['contract_id']));
			$details_query_res = $details_query->result();
			$summary_res['detail_res'] = $details_query_res;
	
			//echo $details_query_sql;
	
		}
		return $summary_res;
	}
	
	public function get_contract_name($contract_id)
	{
		$this->db->select('contract_name');
		$this->db->from('contracts');
		$this->db->where('contract_id', $contract_id);
		
		$query = $this->db->get();
		$contract_res = $query->result();
		
		return $contract_res[0]->contract_name;
	}
	
	public function get_customer_name($customer_id)
	{
		$this->db->select('customer_name');
		$this->db->from('customers');
		$this->db->where('customer_id', $customer_id);
		
		$query = $this->db->get();
		$customer_res = $query->result();
		
		return $customer_res[0]->customer_name;
	}
	
	public function get_school_name($school_id)
	{
		$this->db->select('school_name');
		$this->db->from('schools');
		$this->db->where('school_id', $school_id);
		
		$query = $this->db->get();
		$school_res = $query->result();
		
		if(count($school_res) > 0)
			return $school_res[0]->school_name;
		else
			return "";
	}
	
	public function get_username($user_id)
	{
		$this->db->select('username');
		$this->db->from('users');
		$this->db->where('user_id', $user_id);
		
		$query = $this->db->get();
		$user_res = $query->result();
		
		return $user_res[0]->username;
	}
	

	/* For resource management setcion...*/
	
	/* Get the zone dashboard */
	public function get_zone_dashboard($data)
	{
		
		$zone_count_sql = "SELECT COUNT(1) c FROM zone WHERE contract_id = ? AND active = ?";
		$zone_count_query = $this->db->query($zone_count_sql, array($data['contract_id'], ACTIVE));
		$zone_count_res = $zone_count_query->result();
		$zone_dashboard = new stdClass();
		$zone_dashboard->tc = $zone_count_res[0]->c;
	
		/*$end = $data['page_no'] * ZONE_DASHBOARD_COUNT;
		$start = $end - ZONE_DASHBOARD_COUNT;
		
		$zone_sql = "SELECT zone_id zid, zone_name zn, description d, high_threshold ht, low_threshold lt, last_count lc,
					CASE WHEN last_contact IS NULL or DATE_SUB(NOW(), INTERVAL timeout MINUTE) > last_contact  THEN 'b'
					WHEN  last_count > high_threshold THEN 'o'
					WHEN  last_count < low_threshold THEN 'r' 
					ELSE  'g' END AS zst FROM `zone` WHERE contract_id = ".$data['contract_id']." AND active = ".ACTIVE."
					ORDER BY zone_name ASC
					LIMIT " .$start ."," .$end;*/
		
		if($data['page_no'] == 1)
			$start = 0;
		else
			$start = ($data['page_no'] - 1) * ZONE_DASHBOARD_COUNT;
		
		$zone_sql = "SELECT zone_id zid, zone_name zn, description d, high_threshold ht, low_threshold lt, COALESCE(a.n,0) lc,
					CASE WHEN last_contact IS NULL or DATE_SUB(NOW(), INTERVAL timeout MINUTE) > last_contact  THEN 'b'
					WHEN  last_count > high_threshold THEN 'o'
					WHEN  last_count < low_threshold THEN 'r' 
					ELSE  'g' END AS zst
					FROM zone z
					LEFT JOIN (SELECT last_contact_zone_id,count(1) n FROM asset
								 WHERE contract_id = ?
								AND  active = ? AND is_removed = 0 AND DATE_SUB(NOW(), INTERVAL timeout HOUR) <= last_contact 
								GROUP BY last_contact_zone_id) a 
								ON a.last_contact_zone_id = z.zone_id 
					WHERE contract_id = ? AND active = ?
					ORDER BY zone_name ASC
					LIMIT ?,?";
			
		//echo $zone_sql;
		
		$zone_query = $this->db->query($zone_sql, array($data['contract_id'], ACTIVE, $data['contract_id'], ACTIVE, $start, ZONE_DASHBOARD_COUNT));
		$zone_query_res = $zone_query->result();
		$zone_dashboard->zlist = $zone_query_res;
		return $zone_dashboard;
	}
	
	/*Validate zone in the given contract */	
	public function validate_zone($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('zone');
		$this->db->where('zone_id',$data['zone_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('active',ACTIVE);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		//echo $this->db->last_query();
		return $cus_con_res[0];
	}
	
	/*get the zone detials */
	public function get_zone_details($data){
		
		$zone_sql = "SELECT zone_name zn, description d, device_id did, serial_id sid, 
						high_threshold ht, low_threshold lt, network_id nid, network_desc ndes, timeout t, tagread_timeout tt, last_contact ld, 
						(SELECT COUNT(1) FROM asset WHERE active = ? AND is_removed = 0 AND DATE_SUB(NOW(), INTERVAL timeout HOUR) <= last_contact
						AND last_contact_zone_id = ?) lc, firmware f
					FROM zone z
					WHERE zone_id = ? AND active = ?";
		
		$zone_query = $this->db->query($zone_sql, array(ACTIVE, $data['zone_id'], $data['zone_id'], ACTIVE));
		//echo $this->db->last_query();
		$zone_query_res = $zone_query->result();
		
		return $zone_query_res;
	}
	
	/*get the zone detials */
	
	public function get_zone_chart_details($data){
	
		$end_date = $data['end_date'];
		$end_date = date('Y/m/d', strtotime(str_replace('/', '-', $end_date)));
		
		//For last 31 days of data
		$start_date = date('Y/m/j' , strtotime ( '-30 day' , strtotime ($end_date )));
		
		$zone_sql = "SELECT zone_name, date_format('".$start_date."','%D %M') st, date_format('".$end_date."', '%D %M %Y') ed FROM zone WHERE zone_id = ".$data['zone_id']." AND active = ".ACTIVE;
		$zone_query = $this->db->query($zone_sql);
		$zone_query_res = $zone_query->result();
		
		$zone_chart_sql ="SELECT  DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY) d, COALESCE(zd.min_value,0) min, COALESCE(zd.max_value,0) max, COALESCE(zd.avg_value,0) avg, COALESCE(zd.high_threshold,0) ht, COALESCE(zd.low_threshold,0) lt
							FROM numbers n
							LEFT JOIN zone_daily_status zd ON DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY)  = zd.daily_date AND zone_id =".$data['zone_id']."
							WHERE n.id<=31";
		
		
		$zone_chart_query= $this->db->query($zone_chart_sql);
		$zone_chart_data = $zone_chart_query->result();
				
		$chart_data = array(
				'zone_name' => $zone_query_res[0]->zone_name,
				'sub_title' => $zone_query_res[0]->st.' - ' . $zone_query_res[0]->ed,
				'zone_data' => $zone_chart_data				
		);
		
// 		echo "<pre>";
// 		print_r($chart_data);
		
		return $chart_data;
	}
	
	public function get_userinfo($user_id)
	{
		$this->db->select('first_name, last_name');
		$this->db->from('users');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get();
		
		$user_res = $query->result();
		return $user_res[0];
	}
	
	public function get_school_admin_emails($school_id)
	{
		$qry = "SELECT first_name, last_name, user_email from users where user_id IN(SELECT user_id from school_admins where school_id = ". $school_id .") and mail_notification = ". ACTIVE;
		$query = $this->db->query($qry);
		$res_users = $query->result();
		
		return $res_users;
	}

	public function school_close($data)
	{
		$close_data = array(
				'closed_till' => $data['close_till'],
				'closed_reason' => $data['reason'],
				'closed_by' => $data['user_id'],
		);
		$this->db->set('closed_on', 'DATE(NOW())', FALSE);
		$this->db->where('school_id', $data['school_id']);
		$school_close = $this->db->update('schools', $close_data);
		return $school_close;
	}
	
	public function school_open($data)
	{
		$close_data = array(
				'closed_till' => NULL,
				'opened_by' => $data['user_id'],
		);
		$this->db->where('school_id', $data['school_id']);
		$school_open = $this->db->update('schools', $close_data);
		return $school_open;
	}
	
	public function create_batch_cancel($data, $cancel_type_id)
	{
		$qry = "INSERT into batch_cancel (batch_type_id, system_msg, cuser_id, active, cdate) values (". $cancel_type_id .", (SELECT REPLACE('". $data['reason_msg'] ."', '". DATE_REPLACE_STRING ."', DATE_FORMAT(NOW(), '%H:%i on %d/%m/%Y'))), ". $data['user_id'] .", ".ACTIVE.", NOW())";
		$query = $this->db->query($qry);
		//$file_details = $query->result();
		$batch_cancel_id = $this->db->insert_id();
		return $batch_cancel_id;
	}
	
	public function update_orders_school_close($data, $batch_cancel_id, $ful_date)
	{
		$close_data = array(
				'batch_cancel_id' => $batch_cancel_id,
				'order_edited' => ACTIVE,
				'muser_id' => $data['user_id'],
		);
		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('fulfilment_date =', $ful_date);
		$this->db->where('school_id', $data['school_id']);
		$this->db->where('order_edited', INACTIVE);
		$this->db->where('order_status', ORDER_STATUS_NEW);
		$this->db->where('collect_status', INACTIVE);
		$update_orders = $this->db->update('order_items', $close_data);
		return $update_orders;
	}
	
	public function get_school_close_till_date($data)
	{
		$this->db->select('closed_till');
		$this->db->from('schools');
		$this->db->where('school_id', $data['school_id']);
		$query = $this->db->get();
		$school_res = $query->result();
		return $school_res[0]->closed_till;
	}
	
	public function update_orders_school_open($data)
	{
		$qry = "SELECT o.batch_cancel_id FROM order_items o, batch_cancel b WHERE
					b.batch_cancel_id = o.batch_cancel_id
					AND b.batch_type_id = ". SCHOOL_CLOSE_DATA_ID ."
					AND school_id = ". $data['school_id'] ."
					AND fulfilment_date >= DATE(NOW())
					AND fulfilment_date <= '". $data['close_till'] ."'
					AND order_edited = ". ACTIVE ."
					AND order_status = ". ORDER_STATUS_NEW ."
					AND collect_status = ". INACTIVE ."
					group by o.batch_cancel_id";
		
		$batch_query= $this->db->query($qry);
		$batch_res = $batch_query->result();
		
		foreach($batch_res as $key => $value)
		{
			$open_data = array(
					'order_edited' => INACTIVE,
					'muser_id' => $data['user_id'],
			);

			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('fulfilment_date >= DATE(NOW())');
			$this->db->where('fulfilment_date <=', $data['close_till']);
			$this->db->where('school_id', $data['school_id']);
			$this->db->where('batch_cancel_id', $value->batch_cancel_id);
			$this->db->where('order_edited', ACTIVE);
			$this->db->where('order_status', ORDER_STATUS_NEW);
			$this->db->where('collect_status', INACTIVE);
			$update_orders = $this->db->update('order_items', $open_data);
		}
		
		return TRUE;
	}

	public function process_heartbeat_request($from, $user_agent,$post_data){
		
		//Insert into Service_status table
		//Get the Zone Id from the given device id, Serial Number
		
		$service_type_sql = "SELECT data_value_id FROM data_value WHERE data_value ='Asset Zone'";
		$service_type_query= $this->db->query($service_type_sql);
		$service_type_data = $service_type_query->result();
		
		$service_status_arr = array(
				'service_type_id' =>$service_type_data[0]->data_value_id,
				'headers' => $from.",".$user_agent,
				'body' => $post_data);
		$this->db->set('cdate', 'NOW()', FALSE);
		$sa = $this->db->insert('service_status', $service_status_arr);
		
		$fromArray = explode("/", $from);
		
		$zone_sql = "SELECT zone_id, timeout, CASE WHEN last_contact IS NOT NULL AND DATE_SUB(NOW(), INTERVAL timeout MINUTE) <= last_contact THEN 2 ELSE 1 END AS status,
				CASE WHEN tagread_lastcontact IS NULL or DATE_SUB(NOW(), INTERVAL timeout MINUTE) > tagread_lastcontact THEN 2 ELSE 1 END AS tagread_status   
				FROM zone WHERE device_id = '".$fromArray[0]."' AND serial_id = '".$fromArray[1]."' AND active = ".ACTIVE;
		$zone_query= $this->db->query($zone_sql);
		$zone_data = $zone_query->result();
		
		if($zone_data !=null){
			//Update zone row 
			$zone_update_sql = "UPDATE zone SET last_contact=NOW(), firmware ='".$user_agent."' WHERE zone_id='". $zone_data[0]->zone_id."'";
			$zone_update_query= $this->db->query($zone_update_sql);
						
			$resource_status = $zone_data[0]->status;
			if($zone_data[0]->status == 1)
				$resource_details = "Last message accepted, status ok";
			else 
				$resource_details = "Asset Zone out of timeout period";
			$resource_timeout = $zone_data[0]->timeout;
			
			if($zone_data[0]->tagread_status == 1){
				//Received after time period... then update all assets to is removed.
				$asset_update_isremoved_sql = "UPDATE asset SET is_removed = 1  WHERE last_contact_zone_id= '".$zone_data[0]->zone_id."'";
				$asset_update_isremoved_query= $this->db->query($asset_update_isremoved_sql);
			}
		}
		else {
			$resource_status = 3;
			$resource_details = "Asset Zone not recognised";
			$resource_timeout = 'null';
		}
		
		//Insert/update a row in resource_system_status table.
		$resource_system_sql = "SELECT resource_system_id FROM resource_system_status WHERE device_id = '".$fromArray[0]."' AND serial_id ='".$fromArray[1]."' AND type_id=".$service_type_data[0]->data_value_id;
		$resource_system_query= $this->db->query($resource_system_sql);
		$resource_system_data = $resource_system_query->result();
		
		if($resource_system_data != null){
			//Update a row
			$resource_update_sql = "UPDATE resource_system_status SET details='".$resource_details."', status ='".$resource_status."', last_contact_date=NOW(), timeout=".$resource_timeout.", mdate=NOW() WHERE resource_system_id =" .$resource_system_data[0]->resource_system_id;
			$resource_update_query= $this->db->query($resource_update_sql);
			
		} else {
			//Insert a row
			$resource_insert_sql = "INSERT INTO resource_system_status(type_id, device_id, serial_id, details, status, last_contact_date, timeout, cdate) VALUES(".$service_type_data[0]->data_value_id.", '".$fromArray[0]."','".$fromArray[1]."', '".$resource_details."', '".$resource_status."', NOW(),".$resource_timeout.", NOW())";
			$resource_insert_query= $this->db->query($resource_insert_sql);
		}
		return true;
		
	}
	
	public function process_tagread_request($from, $user_agent,$post_data){
		
		//Insert a row in service_status table 
		
		$service_type_sql = "SELECT data_value_id FROM data_value WHERE data_value ='Asset Zone'";
		$service_type_query= $this->db->query($service_type_sql);
		$service_type_data = $service_type_query->result();
		
		$service_status_arr = array(
				'service_type_id' =>$service_type_data[0]->data_value_id,
				'headers' => $from.",".$user_agent,
				'body' => $post_data);
		$this->db->set('cdate', 'NOW()', FALSE);
		$sa = $this->db->insert('service_status', $service_status_arr);
		
		//Vallidate zone
		
		$fromArray = explode("/", $from);
		
		$zone_sql = "SELECT zone_id, contract_id, timeout, low_threshold, high_threshold, CASE WHEN last_contact IS NULL or DATE_SUB(NOW(), INTERVAL timeout MINUTE) > last_contact THEN 2 ELSE 1 END AS status  FROM zone WHERE device_id = '".$fromArray[0]."' AND serial_id = '".$fromArray[1]."' AND active = ".ACTIVE;
		$zone_query= $this->db->query($zone_sql);
		$zone_data = $zone_query->result();
		
		if($zone_data !=null){
			
			$resource_status = $zone_data[0]->status;
			if($zone_data[0]->status == 1)
				$resource_details = "Last message accepted, status ok";
			else
				$resource_details = "Asset Zone out of timeout period";
			$resource_timeout = $zone_data[0]->timeout;
			
			$assets_row_arr = explode(",", $post_data);
			
			$no_assets = count($assets_row_arr) - 1;
			
			$valid_asset_count = 0;
			
		
			//insert/Update zone daily status
			$zone_daily_status_sql  = "SELECT zone_daily_status_id,min_value,max_value,avg_value,request_count FROM zone_daily_status WHERE zone_id= ".$zone_data[0]->zone_id ." AND daily_date=CURRENT_DATE()";
			$zone_daily_status_query= $this->db->query($zone_daily_status_sql);
			$zone_daily_status_data = $zone_daily_status_query->result();
			
			if($zone_daily_status_data != null) {
				$min_value = ($no_assets < $zone_daily_status_data[0]->min_value) ? $no_assets : $zone_daily_status_data[0]->min_value;
				$max_value = ($no_assets > $zone_daily_status_data[0]->max_value) ? $no_assets : $zone_daily_status_data[0]->max_value;
				$request_count = $zone_daily_status_data[0]->request_count + 1;
				$avg_value = (($zone_daily_status_data[0]->avg_value * $zone_daily_status_data[0]->request_count) + $no_assets)/ $request_count;
								
				$zone_daily_update_sql = "UPDATE zone_daily_status SET min_value = ".$min_value.", max_value= ".$max_value.", avg_value= ".$avg_value.", request_count = ".$request_count.", high_threshold=".$zone_data[0]->high_threshold.", low_threshold= ".$zone_data[0]->low_threshold.", mdate= NOW() WHERE zone_daily_status_id= ".$zone_daily_status_data[0]->zone_daily_status_id;
				$zone_daily_update_query= $this->db->query($zone_daily_update_sql);
				
			} else {
				$zone_daily_insert_sql = "INSERT INTO zone_daily_status (zone_id, daily_date, min_value, max_value, avg_value, request_count, high_threshold, low_threshold, cdate) VALUES('".$zone_data[0]->zone_id."',CURRENT_DATE(), ".$no_assets.", ".$no_assets.",".$no_assets.",1, ".$zone_data[0]->high_threshold.", ".$zone_data[0]->low_threshold.", NOW())";
				$zone_daily_insert_query= $this->db->query($zone_daily_insert_sql);
			}
			
			$device_type_sql = "SELECT data_value_id FROM data_value WHERE data_value ='Asset Tag'";
			$device_type_query= $this->db->query($device_type_sql);
			$device_type_data = $device_type_query->result();
			
			//Update all assets to is_removed to 1 where last_contact_zone_id is current zone_id
			
			//Update asset table
			$asset_update_isremoved_sql = "UPDATE asset SET is_removed = 1  WHERE last_contact_zone_id= '".$zone_data[0]->zone_id."'";
			$asset_update_isremoved_query= $this->db->query($asset_update_isremoved_sql);
			
			//Loop through each asset and validate and add/edit resource_device_status table for monitoring and also update assets table
			for($i=0;$i<$no_assets;$i++){

				$assets_arr  = explode("&", $assets_row_arr[$i]);
				$oem_code = substr($assets_arr[1],2,2);
				$tag_number = hexdec(substr($assets_arr[1],4,6));
				$battery_status = substr($assets_arr[1],-1);
				$date_time = substr($assets_arr[2],2);
				$read_point = substr($assets_arr[3],2);
				
				$date_time =  substr($date_time,4,4)."-".substr($date_time,2,2)."-".substr($date_time,0,2)." ".substr($date_time,8,2).":".substr($date_time,10,2).":".substr($date_time,12,2);
				
				$asset_sql = "SELECT asset_id, timeout, battery_status, battery_low_since, CASE WHEN last_contact IS NULL or DATE_SUB('".$date_time."', INTERVAL timeout MINUTE) > last_contact THEN 2 ELSE 1 END AS status  FROM asset WHERE contract_id=".$zone_data[0]->contract_id." AND tag_number = '".$tag_number."' AND oem_code='".$oem_code."' AND active =".ACTIVE;
				$asset_query= $this->db->query($asset_sql);
				$asset_data = $asset_query->result(); 	
					
				
				if($asset_data !=null) {
					// if zone is recognised 	
					$resource_device_status = $asset_data[0]->status;
					if($asset_data[0]->status == 1)
						$resource_device_details = "Last message accepted, status ok";
					else
						$resource_device_details = "Asset Tag out of timeout period";
					$resource_device_timeout = $asset_data[0]->timeout;
					
					if($battery_status == "H")
						$battery_status_since = 'null';
					else if($asset_data[0]->battery_low_since == null || $asset_data[0]->battery_low_since == '')
						$battery_status_since = 'NOW()';
					else 
						$battery_status_since = "'".$asset_data[0]->battery_low_since."'";
					
					//Update asset table
					$asset_update_sql = "UPDATE asset SET last_contact='".$date_time ."', battery_status='".$battery_status."', battery_low_since= ".$battery_status_since.", last_contact_zone_id= '".$zone_data[0]->zone_id."', is_removed = 0  WHERE asset_id=".$asset_data[0]->asset_id;
					$asset_update_query= $this->db->query($asset_update_sql);
			
					//Delete and insert asset tracking status to keep only two records for asset_id
 					$asset_tracking_delete_sql = "DELETE a FROM  asset_tracking_status a
 												LEFT JOIN asset_tracking_status b ON a.asset_tracking_status_id < b.asset_tracking_status_id AND a.asset_id = b.asset_id AND a.asset_id = ".$asset_data[0]->asset_id." WHERE a.asset_id = b.asset_id IS NOT NULL";
 					$asset_tracking_delete_query= $this->db->query($asset_tracking_delete_sql);
				
					$asset_tracking_insert_sql = "INSERT INTO asset_tracking_status(asset_id, zone_id, read_point, last_contact, cdate) VALUES(".$asset_data[0]->asset_id.", ".$zone_data[0]->zone_id.",'".$read_point."', '".$date_time."',NOW())";
					$asset_tracking_insert_query= $this->db->query($asset_tracking_insert_sql);

					$valid_asset_count = $valid_asset_count +1;
				} else {
					//if zone is not recognised, insert a row in resource_device_status table
					$resource_device_status = 3;
					$resource_device_details = "Asset Zone is recognised and Asset Tag not recognised";
					$resource_device_timeout = 'null';
				}
				
			
				
				//Insert/update a row in resource_system_status table.
				$resource_device_sql = "SELECT resource_device_id FROM resource_device_status WHERE tag_number= '".$tag_number."' AND oem_code ='".$oem_code."' AND type_id=".$device_type_data[0]->data_value_id;
				$resource_device_query= $this->db->query($resource_device_sql);
				$resource_device_data = $resource_device_query->result();
				
				if($resource_device_data != null){
					//Update a row
					$resource_device_update_sql = "UPDATE resource_device_status SET details='".$resource_device_details."', status ='".$resource_device_status."', last_contact_date='".$date_time."', timeout=".$resource_device_timeout.", mdate=NOW() WHERE resource_device_id =" .$resource_device_data[0]->resource_device_id;
					$resource_device_update_query= $this->db->query($resource_device_update_sql);
				} else {
					//Insert a row
					$resource_device_insert_sql = "INSERT INTO resource_device_status(type_id, tag_number, oem_code, details, status, last_contact_date, timeout, cdate) VALUES(".$device_type_data[0]->data_value_id.", '".$tag_number."','".$oem_code."', '".$resource_device_details."', '".$resource_device_status."', '".$date_time."',".$resource_device_timeout.", NOW())";
					$resource_device_insert_query= $this->db->query($resource_device_insert_sql);
				}
			}
			
			//Update zone row
			$zone_update_sql = "UPDATE zone SET last_contact=NOW(),last_count=".$valid_asset_count.", firmware ='".$user_agent."', tagread_lastcontact = NOW() WHERE zone_id=". $zone_data[0]->zone_id;
			$zone_update_query= $this->db->query($zone_update_sql);
			
		} else {
			
			$resource_status = 3;
			$resource_details = "Asset Zone not recognised";
			$resource_timeout = 'null';
			//Loop through all assets and mark them as not recognised and insert in resource_device_status.
			
			$resource_device_status = 3;
			$resource_device_details = "Asset Zone is not recognised and Asset Tag not recognised";
			$resource_device_timeout = 'null';
			
			$device_type_sql = "SELECT data_value_id FROM data_value WHERE data_value ='Asset Tag'";
			$device_type_query= $this->db->query($device_type_sql);
			$device_type_data = $device_type_query->result();
			
			
			$assets_row_arr = explode(",", $post_data);
			$no_assets = count($assets_row_arr) - 1;
			for($i=0;$i<$no_assets;$i++){
			
				$assets_arr  = explode("&", $assets_row_arr[$i]);
				$oem_code = substr($assets_arr[1],2,2);
				$tag_number = substr($assets_arr[1],4,6);
				$battery_status = substr($assets_arr[1],-1);
				$date_time = substr($assets_arr[2],2);
				$read_point = substr($assets_arr[3],2);
			
				$date_time =  substr($date_time,4,4)."-".substr($date_time,2,2)."-".substr($date_time,0,2)." ".substr($date_time,8,2).":".substr($date_time,10,2).":".substr($date_time,12,2);
			
				
				//Insert/update a row in resource_system_status table.
				$resource_device_sql = "SELECT resource_device_id FROM resource_device_status WHERE tag_number= '".$tag_number."' AND oem_code ='".$oem_code."' AND type_id=".$device_type_data[0]->data_value_id;
				$resource_device_query= $this->db->query($resource_device_sql);
				$resource_device_data = $resource_device_query->result();
				
				if($resource_device_data != null){
					//Update a row
					$resource_device_update_sql = "UPDATE resource_device_status SET details='".$resource_device_details."', status ='".$resource_device_status."', last_contact_date='".$date_time."', timeout=".$resource_device_timeout.", mdate=NOW() WHERE resource_device_id =" .$resource_device_data[0]->resource_device_id;
					$resource_device_update_query= $this->db->query($resource_device_update_sql);
				} else {
					//Insert a row
					$resource_device_insert_sql = "INSERT INTO resource_device_status(type_id, tag_number, oem_code, details, status, last_contact_date, timeout, cdate) VALUES(".$device_type_data[0]->data_value_id.", '".$tag_number."','".$oem_code."', '".$resource_device_details."', '".$resource_device_status."', '".$date_time."',".$resource_device_timeout.", NOW())";
					$resource_device_insert_query= $this->db->query($resource_device_insert_sql);
				}
			}
					
		}
		
		//Insert/update a row in resource_system_status table.
		$resource_system_sql = "SELECT resource_system_id FROM resource_system_status WHERE device_id = '".$fromArray[0]."' AND serial_id ='".$fromArray[1]."' AND type_id=".$service_type_data[0]->data_value_id;
		$resource_system_query= $this->db->query($resource_system_sql);
		$resource_system_data = $resource_system_query->result();
		
		if($resource_system_data != null){
			//Update a row
			$resource_update_sql = "UPDATE resource_system_status SET details='".$resource_details."', status ='".$resource_status."', last_contact_date=NOW(), timeout=".$resource_timeout.", mdate=NOW() WHERE resource_system_id =" .$resource_system_data[0]->resource_system_id;
			$resource_update_query= $this->db->query($resource_update_sql);
				
		} else {
			//Insert a row
			$resource_insert_sql = "INSERT INTO resource_system_status(type_id, device_id, serial_id, details, status, last_contact_date, timeout, cdate) VALUES(".$service_type_data[0]->data_value_id.", '".$fromArray[0]."','".$fromArray[1]."', '".$resource_details."', '".$resource_status."', NOW(),".$resource_timeout.", NOW())";
			$resource_insert_query= $this->db->query($resource_insert_sql);
		}
		
		return true;
	} 

	
	public function validate_batch_cancel_id($batch_cancel_id)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('batch_cancel');
		$this->db->where('batch_cancel_id',$batch_cancel_id);
		$this->db->where('active', ACTIVE);
		$this->db->where('cancel_status', INACTIVE);
		$query = $this->db->get();
		$batch_res = $query->result();
		return $batch_res[0];
	}
	
	/*Validate asset in the given contract */	
	public function validate_asset($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('asset');
		$this->db->where('asset_id',$data['asset_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('active',ACTIVE);
		$query = $this->db->get();
		$cus_con_res = $query->result();
		//echo $this->db->last_query();
		return $cus_con_res[0];
	}
	
	/* Getting the current date and time */
	public function get_current_date_time()
	{
		$qry = "SELECT DATE_FORMAT(NOW(), '%d') as sday, DATE_FORMAT(NOW(), '%m') as smonth, DATE_FORMAT(NOW(), '%Y') as syear, DATE_FORMAT(NOW(), '%H') as shour, DATE_FORMAT(NOW(), '%i') as smin, DATE_FORMAT(NOW(), '%S') as ssec";
		$query = $this->db->query($qry);
		$date_res = $query->result();
		
		return $date_res[0];
	}
	
	/* Get the name & date using batch id */
	public function get_order_edit_user_info($batch_id)
	{
		$this->db->select('first_name, last_name');
		$this->db->select("DATE_FORMAT(o.mdate, '%d/%m/%Y %H:%i') as mdate", FALSE);
		$this->db->from('users u');
		$this->db->from('order_items o', 'o.muser_id = u.user_id');
		$this->db->where('o.batch_cancel_id', $batch_id);
		$query = $this->db->get();
		$user_res = $query->result();
		return $user_res[0];
	}
	
	public function asset_dash_search($data)
	{
		/*$sql = "SELECT a.asset_id, CONCAT_WS( '', oem_code, tag_number ) AS asset_no, description, d.data_value AS asset_type, asset_other_desc, battery_status, DATEDIFF( NOW( ) , battery_low_since ) AS low_battery_days,
				CASE WHEN TIMESTAMPDIFF(
				MINUTE , last_contact, NOW( ) ) > timeout
				THEN 1
				ELSE 0
				END AS not_responding,
				(SELECT (SELECT zone_name from zone where zone_id=ats.zone_id) from asset_tracking_status ats where ats.asset_id=a.asset_id order by mdate desc limit 0,1) as zone_latest,
				(SELECT (SELECT zone_name from zone where zone_id=ats.zone_id) from asset_tracking_status ats where ats.asset_id=a.asset_id order by mdate desc limit 1,1) as zone_previous,
				(SELECT  DATE_FORMAT(ats.mdate,'%d/%c/%Y %H:%i:%s')  from asset_tracking_status ats where ats.asset_id=a.asset_id order by mdate desc limit 0,1) as last_read,
				(SELECT DATE_FORMAT(ats.mdate,'%d/%c/%Y %H:%i:%s') from asset_tracking_status ats where ats.asset_id=a.asset_id order by mdate desc limit 1,1) as previous_read
				FROM asset a
				INNER JOIN data_value d ON a.asset_type_id = d.data_value_id
				WHERE a.active = ?
				AND a.contract_id = ?";*/
		if(!empty($data['zone_id']))
		{
			$sql = "SELECT DISTINCT a.asset_id aid, CONCAT_WS( '/', oem_code, tag_number ) AS anum, description ades, d.data_value AS atyp, asset_other_desc atypod,
					(SELECT DATE_FORMAT( ats1.last_contact, '%d/%c/%Y %H:%i:%s' )
					FROM asset_tracking_status ats1
					WHERE ats1.asset_id = a.asset_id
					ORDER BY ats1.last_contact DESC
					LIMIT 1 , 1) AS pr,
					DATE_FORMAT( at.last_contact, '%d/%c/%Y %H:%i:%s' ) AS lr,
					CASE WHEN is_removed = 1 THEN 'b'
					ELSE 'g'
					END As ast, battery_status bs, DATEDIFF( NOW( ) , battery_low_since ) AS lbd, 
					(SELECT (SELECT zone_name from zone where zone_id=ats2.zone_id) from asset_tracking_status ats2 where ats2.asset_id=a.asset_id order by ats2.asset_tracking_status_id desc limit 0,1) as zl,
					(SELECT (SELECT zone_name from zone where zone_id=ats3.zone_id) from asset_tracking_status ats3 where ats3.asset_id=a.asset_id order by ats3.asset_tracking_status_id desc limit 1,1) as zp
					FROM asset a
					INNER JOIN asset_tracking_status at ON a.asset_id = at.asset_id
					INNER JOIN data_value d ON a.asset_type_id = d.data_value_id
					WHERE a.contract_id = ?
					AND a.active = ?
					AND a.last_contact IS NULL or DATE_SUB(NOW(), INTERVAL timeout HOUR) < a.last_contact
					AND at.zone_id = ?
					AND (
					SELECT ats.zone_id
					FROM asset_tracking_status ats
					WHERE ats.asset_id = at.asset_id
					ORDER BY ats.last_contact DESC
					LIMIT 0 , 1
					) = at.zone_id
					AND (
					SELECT ats.last_contact
					FROM asset_tracking_status ats
					WHERE ats.asset_id = at.asset_id
					ORDER BY ats.last_contact DESC
					LIMIT 0 , 1
					) = at.last_contact
					ORDER BY at.last_contact DESC";
			$query = $this->db->query($sql, array($data['contract_id'],ACTIVE,$data['zone_id']));
		}
		else
		{
			$sql = "SELECT a.asset_id aid, CONCAT_WS( '/', oem_code, tag_number ) AS anum, description ades, d.data_value AS atyp, asset_other_desc atypod,
					CASE WHEN a.last_contact IS NULL or DATE_SUB(NOW(), INTERVAL timeout HOUR) > a.last_contact  THEN 'o'
					WHEN is_removed = 1 THEN 'b'
					ELSE 'g'
					END As ast,
					battery_status bs, DATEDIFF( NOW( ) , battery_low_since ) AS lbd,
					(SELECT (SELECT zone_name from zone where zone_id=ats.zone_id) from asset_tracking_status ats where ats.asset_id=a.asset_id order by asset_tracking_status_id desc limit 0,1) as zl,
					(SELECT (SELECT zone_name from zone where zone_id=ats.zone_id) from asset_tracking_status ats where ats.asset_id=a.asset_id order by asset_tracking_status_id desc limit 1,1) as zp,
	                (SELECT  DATE_FORMAT(ats.last_contact,'%d/%c/%Y %H:%i:%s')  from asset_tracking_status ats where ats.asset_id=a.asset_id order by asset_tracking_status_id desc limit 0,1) as lr,
	                (SELECT DATE_FORMAT(ats.last_contact,'%d/%c/%Y %H:%i:%s') from asset_tracking_status ats where ats.asset_id=a.asset_id order by asset_tracking_status_id desc limit 1,1) as pr
					FROM asset a
					INNER JOIN data_value d ON a.asset_type_id = d.data_value_id
					WHERE a.contract_id = ?
					AND a.active = ?
					ORDER BY lr DESC";
			$query = $this->db->query($sql, array($data['contract_id'],ACTIVE));
		}
		$asset_dash_res = $query->result();
		return $asset_dash_res;
	}
	
	/* Get the orders till close date of school */
	public function get_orders_schools($data)
	{
		$this->db->select('fulfilment_date');
		$this->db->from('order_items');
		$this->db->where('school_id', $data['school_id']);
		$this->db->where('order_edited', INACTIVE);
		$this->db->where('order_status', ORDER_STATUS_NEW);
		$this->db->where('collect_status', INACTIVE);
		$this->db->where('fulfilment_date >= DATE(NOW())');
		$this->db->where('fulfilment_date <=', $data['close_till']);
		$this->db->group_by('fulfilment_date');
		$query = $this->db->get();
		$order_res = $query->result();
		return $order_res;
	}
	
	
	//For processing the digital form request from webservice
	public function process_digital_form_request($post_data){
		
		$input_obj = simplexml_load_string($post_data,'simple_xml_extended');
		
		$app_name = $input_obj ->header->appname;
		$form_key = $input_obj ->header->formkey;
		$pens = $input_obj ->header->pens;
		$data = $input_obj ->data;
		$server_received = $input_obj->header->formkey->Attribute('serverreceived');
		$pen_count = count($input_obj->header->pens->pen);
		
		//Validate whether the received xml contains the header(appname, formkey with serverreceived, perns) and data tags.
 		if($app_name == null || $form_key == null || $pens == null || $data == null || $server_received == null || $pen_count == 0){			
 			return false;
 		}
 		
 		
 		$service_type_sql = "SELECT data_value_id FROM data_value WHERE data_value ='Digital Pens,App'";
 		$service_type_query= $this->db->query($service_type_sql);
 		$service_type_data = $service_type_query->result();
 		
 		$server_received_arr = explode("T",$server_received);
 	
 		//Check Whether the app name is configured in the applicaiton or not.
 		
 		$chk_app_str = "SELECT digital_app_id , contract_id, navigation_location, form_type_id, timeout, disallow_forms, 
 				CASE WHEN  MAKETIME(unexp_start_hour,unexp_start_min,00) <= CAST('".$server_received_arr[1]."' AS time) AND  
 					MAKETIME(unexp_end_hour, unexp_end_min,00) >= CAST('".$server_received_arr[1]."' AS time) THEN 1 ELSE 0 END AS unexp_time,
 				CASE WHEN last_contact IS NULL THEN 1
 					 WHEN DATE_ADD(NOW(), INTERVAL timeout HOUR) > last_contact THEN 2 ELSE 1 END AS status
 				FROM digital_app WHERE app_name='".$app_name."' AND status = '".ACTIVE."'";
 		$chk_app_query= $this->db->query($chk_app_str);
 		$chk_app_data = $chk_app_query->result();
 		
 		if($chk_app_data != null) {
 			//Check the time whether the message is received in expected timezone or not.
 			$resource_timeout = $chk_app_data[0]->timeout;
 			$is_excluded =0;
 			$excluded_reason = 'null';
 			if($chk_app_data[0]->disallow_forms == 1 && $chk_app_data[0]->unexp_time == 1){
 				$is_excluded =1;
 				$excluded_reason ="'Received during unexpected period'";
 			}
 			
 			if($chk_app_data[0]->status == 1) {
 					$resource_status = 1;
 					$resource_details = "Last message accepted, status ok";
 				} else {
 					$resource_status = 2;
 					$resource_details = "Digital App has not sent a form within timeout period";
 				}
 				//Update the digital_app table for last_contact
 				$digital_app_update_sql = "UPDATE digital_app SET last_contact = NOW(), mdate = NOW() WHERE digital_app_id= '".$chk_app_data[0]->digital_app_id."'";
				$digital_app_update_query= $this->db->query($digital_app_update_sql);
 				
 				//We should validate each pen and insert/update in resource_device_status table..
 				
 				$is_pen_exists = true;
 				
 				//For getting the Digital pen data value id.
 				$device_type_sql = "SELECT data_value_id FROM data_value WHERE data_value ='Digital Pens,Pen'";
 				$device_type_query= $this->db->query($device_type_sql);
 				$device_type_data = $device_type_query->result();
 				
 				for($i=0;$i<$pen_count;$i++){

 					$chk_pen_str = "SELECT digital_pen_id,timeout, CASE WHEN last_contact IS NULL THEN 1
 					 WHEN DATE_ADD(NOW(), INTERVAL timeout HOUR) > last_contact THEN 2 ELSE 1 END AS status FROM digital_pen WHERE contract_id='".$chk_app_data[0]->contract_id."' AND pen_id='".$pens->pen[$i]['penid']."' AND status='".ACTIVE."'";
 					$chk_pen_query= $this->db->query($chk_pen_str);
 					$chk_pen_data = $chk_pen_query->result();
 					
 					if($chk_pen_data != null) {
 						$pens->pen[$i]['digital_pen_id'] = $chk_pen_data[0]->digital_pen_id;
 						
 						$resource_device_status = $chk_pen_data[0]->status;
 						if($chk_pen_data[0]->status == 1)
 							$resource_device_details = "Last message accepted, status ok";
 						else 
 							$resource_device_details = "Digital Pen has not sent a form within timeout period";
 						$resource_device_timeout = $chk_pen_data[0]->timeout;
 						
 					
 						//Update the digital_pen table for last_contact
 						$digital_pen_update_sql = "UPDATE digital_pen SET last_contact = NOW(), mdate = NOW() WHERE digital_pen_id= '".$chk_pen_data[0]->digital_pen_id."'";
 						$digital_pen_update_query= $this->db->query($digital_pen_update_sql);
 					} else {
 						
 						$is_pen_exists = false;
 						$resource_device_status = 3;
 						$resource_device_details = "Digital App is recognised but Digital Pen is not recognised";
 						$resource_device_timeout = 'null';
 					}
 					//Insert/update a row in resource_system_status table.
 					$resource_device_sql = "SELECT resource_device_id FROM resource_device_status WHERE pen_id= '".$pens->pen[$i]['penid']."' AND type_id=".$device_type_data[0]->data_value_id;
 					$resource_device_query= $this->db->query($resource_device_sql);
 					$resource_device_data = $resource_device_query->result();
 					
 					if($resource_device_data != null){
 						//Update a row
 						$resource_device_update_sql = "UPDATE resource_device_status SET details='".$resource_device_details."', status ='".$resource_device_status."', last_contact_date=NOW(), timeout=".$resource_device_timeout.", mdate=NOW() WHERE resource_device_id =" .$resource_device_data[0]->resource_device_id;
 						$resource_device_update_query= $this->db->query($resource_device_update_sql);
 					} else {
 						//Insert a row
 						$resource_device_insert_sql = "INSERT INTO resource_device_status(type_id, pen_id, details, status, last_contact_date, timeout, cdate) VALUES(".$device_type_data[0]->data_value_id.", '".$pens->pen[$i]['penid']."', '".$resource_device_details."', '".$resource_device_status."', NOW(),".$resource_device_timeout.", NOW())";
 						$resource_device_insert_query= $this->db->query($resource_device_insert_sql);
 					}
 					
 				}
 				
 				if($is_pen_exists){
 					//Insert into digital form table....
 					//$server_received_date = substr($server_received,0,4)."-" . substr($server_received,4,2)."-". substr($server_received,6,2)." " . $server_received_arr[1];
 					$server_received_date = substr($server_received,0,10)." " . $server_received_arr[1];
 					
 					$digital_form_str = "INSERT INTO digital_form(digital_app_id, digital_form_type_id, form_key, server_received, form_xml, cdate, exception_status, exception_reason) 
 										VALUES('".$chk_app_data[0]->digital_app_id."','".$chk_app_data[0]->form_type_id."','".$form_key."','".$server_received_date."','".$post_data."' , NOW(),'".$is_excluded."',".$excluded_reason.")";
 					//echo $digital_form_str;
 					$digital_form_query = $this->db->query($digital_form_str);
 					$digital_form_id = $this->db->insert_id();
 					
 					
 					
 					//Insert all pens information in digital_form_pen table....
 					for($i=0;$i<$pen_count;$i++){
 						$digital_form_pen_str ="INSERT INTO digital_form_pen (digital_pen_id, digital_form_id) VALUES('".$pens->pen[$i]['digital_pen_id']."','".$digital_form_id."')";
 						$digital_form_pen_query = $this->db->query($digital_form_pen_str);
 					}

 					//Now process the specific form (lunch, dinner or adhoc) based on the navigation location and form type....

 					$return_flag = true;
 					//Check whether the form is for patient catering or not.
 					$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND s_module_id ='".$chk_app_data[0]->navigation_location."' AND status ='".ACTIVE."'";
 					$patient_cat_query= $this->db->query($patient_cat_str);
 					$patient_cat_data = $patient_cat_query->result();
 					if($patient_cat_data != null) {
 						$return_flag  = $this->process_lunch_dinner_adhoc_form($chk_app_data[0]->contract_id, $chk_app_data[0]->form_type_id, $digital_form_id,  $data);
 					}
 					//echo $digital_form_id;
 			}
 			else 
 				$return_flag = true;
 		}
 		else {
 			$return_flag = true;
 			//Insert in to resource_system_status...
 			$resource_status = 3;
 			$resource_details = "Digital App not recognised";
 			$resource_timeout = 'null';
 		}
 		
	//Insert/update a row in resource_system_status table.
		$resource_system_sql = "SELECT resource_system_id FROM resource_system_status WHERE app_name = '".$app_name."' AND type_id=".$service_type_data[0]->data_value_id;
		$resource_system_query= $this->db->query($resource_system_sql);
		$resource_system_data = $resource_system_query->result();
		
		if($resource_system_data != null){
			//Update a row
			$resource_update_sql = "UPDATE resource_system_status SET details='".$resource_details."', status ='".$resource_status."', last_contact_date=NOW(), timeout=".$resource_timeout.", mdate=NOW() WHERE resource_system_id =" .$resource_system_data[0]->resource_system_id;
			$resource_update_query= $this->db->query($resource_update_sql);
			
		} else {
			//Insert a row
			$resource_insert_sql = "INSERT INTO resource_system_status(type_id, app_name, details, status, last_contact_date, timeout, cdate) VALUES(".$service_type_data[0]->data_value_id.", '".$app_name."','".$resource_details."', '".$resource_status."', NOW(),".$resource_timeout.", NOW())";
			$resource_insert_query= $this->db->query($resource_insert_sql);
		}
		return $return_flag;
	}
	
	/* Process the form based on the form type.... */
	public function process_lunch_dinner_adhoc_form($contract_id, $form_type_id, $form_id, $data){
		
		//Get the form type_name, total_rows, custom_rows from digital_form_type
		
		$digital_form_type_str = "SELECT type_name, total_rows, custom_rows FROM digital_form_type WHERE digital_form_type_id='".$form_type_id."'";
		$digital_form_type_query= $this->db->query($digital_form_type_str);
		$digital_form_type_data = $digital_form_type_query->result();
		
		if($digital_form_type_data!=null) {
			$form_type = $digital_form_type_data[0]->type_name;
			
			//Process the lunch form
			if($form_type == "Lunch"){
				
				$ward = $data->page->Ward;
				//Check the ward exists or not, if not insert a record in digital_form_ward table
				$form_ward_str = "SELECT digital_form_ward_id FROM digital_form_ward WHERE digital_form_ward_name = '".$ward."' AND contract_id = '".$contract_id."'";
				$form_ward_query= $this->db->query($form_ward_str);
				$form_ward_data = $form_ward_query->result();
				if($form_ward_data == null) {
					$form_ward_insert_str = "INSERT INTO digital_form_ward(contract_id, digital_form_ward_name) VALUES(".$contract_id.", '".$ward."')";
					$form_ward_insert_query= $this->db->query($form_ward_insert_str);
					$ward_id = $this->db->insert_id();
				}
				else 
					$ward_id = $form_ward_data[0]->digital_form_ward_id;
				
				$day_of_week = $data->page->Day;
				//Check the day of week exists or not, if not insert a record in digital_form_dayofweek table
				$form_dayofweek_str = "SELECT digital_form_dayofweek_id FROM digital_form_dayofweek WHERE digital_form_dayofweek_name = '".$day_of_week."' AND contract_id = '".$contract_id."'";
				$form_dayofweek_query= $this->db->query($form_dayofweek_str);
				$form_dayofweek_data = $form_dayofweek_query->result();
				if($form_dayofweek_data == null) {
					$form_dayofweek_insert_str = "INSERT INTO digital_form_dayofweek(contract_id, digital_form_dayofweek_name) VALUES(".$contract_id.", '".$day_of_week."')";
					$form_dayofweek_insert_query= $this->db->query($form_dayofweek_insert_str);
					$dayofweek_id = $this->db->insert_id();
				}
				else
					$dayofweek_id = $form_dayofweek_data[0]->digital_form_dayofweek_id;
				
				
				$form_completed_by = $data->page->FormCompletedBy;
				$total_rows = $digital_form_type_data[0]->total_rows;
				$custom_rows = $digital_form_type_data[0] ->custom_rows;
				
				for($i=1;$i<=$total_rows;$i++){

					$menu_desc= $data->page->{"MenuOption".$i};
					if($menu_desc != ""){ //Process the message if the menu description is not blank
						$menu_qty = ($data->page->{"Qty".$i} != null) ? $data->page->{"Qty".$i} : 0;
						
						$pos = strpos($custom_rows, ",".$i.",");
						$is_custom = ($pos === false) ? 0 : 1;
						
						//Insert in to Digital Lunch form
						$digital_lunch_str = "INSERT INTO digital_lunch_form (digital_form_id, digital_form_ward_id,digital_form_ward_id_up, digital_form_dayofweek_id, menu_description, menu_quantitiy, is_custom, form_completed_by, late_numbers, cdate)
								VALUES ('".$form_id."', '".$ward_id."', '".$ward_id."', '".$dayofweek_id."','".$menu_desc."','".$menu_qty."','".$is_custom."','".$form_completed_by."', '".$menu_qty."', NOW())";
						$digital_lunch_query= $this->db->query($digital_lunch_str);
						$digital_lunch_id = $this->db->insert_id();
						
						if($is_custom == 0) {
							//Get the form indicators
							preg_match_all('/\[([A-Za-z0-9 ]+?)\]/',$menu_desc, $matches);
							
							for($j=0;$j<count($matches[1]);$j++){

								$ind_arr = str_split($matches[1][$j]);
								for($k=0;$k<count($ind_arr);$k++){
									//Check whether the form indicator exist or not in the digital_form_indicators
									$form_indicator_str = "SELECT digital_form_indicator_id FROM digital_form_indicator WHERE menu_type = '".$ind_arr[$k]."' AND contract_id = '".$contract_id."'";
									$form_indicator_query= $this->db->query($form_indicator_str);
									$form_indicator_data = $form_indicator_query->result();
									
									if($form_indicator_data == null) {
										$form_indicator_insert_str = "INSERT INTO digital_form_indicator(contract_id, menu_type, cdate) VALUES(".$contract_id.", '".$ind_arr[$k]."',NOW())";
										$form_indicator_insert_query= $this->db->query($form_indicator_insert_str);
										$indicator_id = $this->db->insert_id();
									} else
										$indicator_id = $form_indicator_data[0]->digital_form_indicator_id;
									
									
									//Insert into digital_lunch_form_indicator
									$digital_form_indicator_str = "INSERT INTO digital_lunch_form_indicator (digital_lunch_form_id, digital_form_indicator_id) VALUES ('".$digital_lunch_id."','".$indicator_id."')";
									$digital_form_indicator_query = $this->db->query($digital_form_indicator_str);
								}
								
								
							}
						}
					}
				}
			} else if($form_type == "Dinner"){ //Process Dinner form
				
				$ward = $data->page->Ward;
				//Check the ward exists or not, if not insert a record in digital_form_ward table
				$form_ward_str = "SELECT digital_form_ward_id FROM digital_form_ward WHERE digital_form_ward_name = '".$ward."' AND contract_id = '".$contract_id."'";
				$form_ward_query= $this->db->query($form_ward_str);
				$form_ward_data = $form_ward_query->result();
				if($form_ward_data == null) {
					$form_ward_insert_str = "INSERT INTO digital_form_ward(contract_id, digital_form_ward_name) VALUES(".$contract_id.", '".$ward."')";
					$form_ward_insert_query= $this->db->query($form_ward_insert_str);
					$ward_id = $this->db->insert_id();
				}
				else
					$ward_id = $form_ward_data[0]->digital_form_ward_id;
				$day_of_week = $data->page->Day;
				//Check the day of week exists or not, if not insert a record in digital_form_dayofweek table
				$form_dayofweek_str = "SELECT digital_form_dayofweek_id FROM digital_form_dayofweek WHERE digital_form_dayofweek_name = '".$day_of_week."' AND contract_id = '".$contract_id."'";
				$form_dayofweek_query= $this->db->query($form_dayofweek_str);
				$form_dayofweek_data = $form_dayofweek_query->result();
				if($form_dayofweek_data == null) {
					$form_dayofweek_insert_str = "INSERT INTO digital_form_dayofweek(contract_id, digital_form_dayofweek_name) VALUES(".$contract_id.", '".$day_of_week."')";
					$form_dayofweek_insert_query= $this->db->query($form_dayofweek_insert_str);
					$dayofweek_id = $this->db->insert_id();
				}
				else
					$dayofweek_id = $form_dayofweek_data[0]->digital_form_dayofweek_id;
				
				$form_completed_by = $data->page->FormCompletedBy;
				$total_rows = $digital_form_type_data[0]->total_rows;
				$custom_rows = $digital_form_type_data[0] ->custom_rows;
				
				for($i=1;$i<=$total_rows;$i++){
				
					$menu_desc= $data->page->{"MenuOption".$i};
					if($menu_desc != ""){ //Process the message if the menu description is not blank
						$menu_qty = ($data->page->{"Qty".$i} != null) ? $data->page->{"Qty".$i} : 0;
				
						$pos = strpos($custom_rows, ",".$i.",");
						$is_custom = ($pos === false) ? 0 : 1;
				
						//Insert in to Digital dinner form
						$digital_dinner_str = "INSERT INTO digital_dinner_form (digital_form_id, digital_form_ward_id, digital_form_ward_id_up, digital_form_dayofweek_id, menu_description, menu_quantitiy, is_custom, form_completed_by, late_numbers, cdate)
								VALUES ('".$form_id."', '".$ward_id."','".$ward_id."', '".$dayofweek_id."','".$menu_desc."','".$menu_qty."','".$is_custom."','".$form_completed_by."', '".$menu_qty."', NOW())";
						$digital_dinner_str= $this->db->query($digital_dinner_str);
						$digital_dinner_id = $this->db->insert_id();
				
						if($is_custom == 0) {
							//Get the form indicators
							preg_match_all('/\[([A-Za-z0-9 ]+?)\]/',$menu_desc, $matches);
					
							for($j=0;$j<count($matches[0]);$j++){
								$ind_arr = str_split($matches[1][$j]);
								
								for($k=0;$k<count($ind_arr);$k++){
								//Check whether the form indicator exist or not in the digital_form_indicators
								$form_indicator_str = "SELECT digital_form_indicator_id FROM digital_form_indicator WHERE menu_type = '".$ind_arr[$k]."' AND contract_id = '".$contract_id."'";
								$form_indicator_query= $this->db->query($form_indicator_str);
								$form_indicator_data = $form_indicator_query->result();
									
								if($form_indicator_data == null) {
									$form_indicator_insert_str = "INSERT INTO digital_form_indicator(contract_id, menu_type, cdate) VALUES(".$contract_id.", '".$ind_arr[$k]."',NOW())";
									$form_indicator_insert_query= $this->db->query($form_indicator_insert_str);
									$indicator_id = $this->db->insert_id();
								} else
									$indicator_id = $form_indicator_data[0]->digital_form_indicator_id;
									
									
								//Insert into digital_lunch_form_indicator
								$digital_form_indicator_str = "INSERT INTO digital_dinner_form_indicator (digital_dinner_form_id, digital_form_indicator_id) VALUES ('".$digital_dinner_id."','".$indicator_id."')";
								$digital_form_indicator_query = $this->db->query($digital_form_indicator_str);
								}
							}
						}
					}
				}
			} else if($form_type == "Ad-hoc") { //Process Ad-hoc form

				//Get the number of rows which means number of sections in adhoc form
				$total_rows = $digital_form_type_data[0]->total_rows;	
				$form_completed_by = $data->page->FormCompletedBy;
				$adhoc_date = $data->page->Date;
				for($i=1;$i<=$total_rows;$i++){
				
					$menu_desc= $data->page->{"Description".$i};

					if($menu_desc != ""){
					$ward = $data->page->{"Ward".$i};
					//Check the ward exists or not, if not insert a record in digital_form_ward table
					$form_ward_str = "SELECT digital_form_ward_id FROM digital_form_ward WHERE digital_form_ward_name = '".$ward."' AND contract_id = '".$contract_id."'";
					$form_ward_query= $this->db->query($form_ward_str);
					$form_ward_data = $form_ward_query->result();
					if($form_ward_data == null) {
						$form_ward_insert_str = "INSERT INTO digital_form_ward(contract_id, digital_form_ward_name) VALUES(".$contract_id.", '".$ward."')";
						$form_ward_insert_query= $this->db->query($form_ward_insert_str);
						$ward_id = $this->db->insert_id();
					}
					else
						$ward_id = $form_ward_data[0]->digital_form_ward_id;
					
					$cost_type = $data->page->{"Cost".$i};
						
					$menu_time = $data->page->{"Time".$i};
						
					
					//Insert in to adhoc form
					$digital_dinner_str = "INSERT INTO digital_adhoc_form (digital_form_id, digital_form_ward_id, adhoc_date, cost_type, menu_description, menu_time, form_completed_by, cdate)
								VALUES ('".$form_id."', '".$ward_id."', '".$adhoc_date."','".$cost_type."','".$menu_desc."','".$menu_time."','".$form_completed_by."', NOW())";
					$digital_dinner_str= $this->db->query($digital_dinner_str);
					}
				}
				
			}
			//print_r($data);
		}
		
		return true;
	}
	/*Validate digital_pen in the given contract */	
	public function validate_digital_pen($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('digital_pen');
		$this->db->where('digital_pen_id',$data['dp_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('status',ACTIVE);
		$query = $this->db->get();
		$dp_res = $query->result();
		//echo $this->db->last_query();
		return $dp_res[0];
	}
	public function validate_qa_account($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('qa_account');
		$this->db->where('qa_account_id',$data['account_id']);
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$acc_res = $query->result();
		return $acc_res[0];
	}
	
	public function validate_qa_account_group($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('qa_account_group');
		$this->db->where('qa_account_id',$data['account_id']);
		$this->db->where('qa_account_group_id',$data['group_id']);
		$this->db->where('status', ACTIVE);
		$query = $this->db->get();
		$acc_res = $query->result();
		return $acc_res[0];
	}
	
	public function validate_user_customer_contract($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('users');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('customer_id', $data['customer_id']);
		$query = $this->db->get();
		$user_con_res = $query->result();
		return $user_con_res[0]->cnt;
	}
}
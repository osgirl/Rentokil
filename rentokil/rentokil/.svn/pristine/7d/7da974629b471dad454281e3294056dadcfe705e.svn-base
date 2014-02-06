<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Qualityservice_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		// $this->load->database();
	}
	
		
	public function get_quality_accounts(){
		$this->db->select("GROUP_CONCAT(account_code) acc_codes, GROUP_CONCAT(DATE_FORMAT(last_updated,'%d-%c-%Y %H:%i:%s')) start_dates, GROUP_CONCAT(DATE_FORMAT(NOW(),'%d-%c-%Y %H:%i:%s')) end_dates",false);
		$this->db->from('qa_account');
		$this->db->where('active',ACTIVE);
		$query = $this->db->get();
		$acc_res = $query->result();
		//echo $this->db->last_query();
		return $acc_res;
		
	}
	
	public function process_quality_audit_data($data){
// 	 	echo '<h2>Result</h2><pre>';
//  		print_r ( $data );
//  		// Here we need to process the returned data.
		
		$end_date = date("Y-m-d H:i:s", strtotime($data['end_date']));
		$acc_codes  = ','.$data['acc_codes'].',';

		if(is_array($data['visits'])== 1) {
			$visit_rows = $data['visits']['visitsRow'];
		if(count($visit_rows) > 0 && is_array($visit_rows[0]) == "") 
			$acc_codes = $this->process_quality_audit_record($data,$visit_rows,$end_date,$acc_codes);
		else {
			for($i=0;$i<count($visit_rows);$i++)
				$acc_codes = $this->process_quality_audit_record($data,$visit_rows[$i],$end_date,$acc_codes);
			 //End of audit row loop..
			}
		}
		
		if(strlen($acc_codes) > 2) {
			//Update all rows with
			$acc_codes = substr($acc_codes,1,strlen($acc_codes)-2);
			$last_update_msg = ($data['last_update_flag']) ? "'".$end_date."'" : "last_updated";  

			$message = "No Audit records received on ".$data['end_date'];
			$acc_codes = "'".str_replace(",","','",$acc_codes)."'";
			$update_account_str = "UPDATE qa_account SET last_status ='".$message."' ,last_updated=".$last_update_msg." WHERE account_code IN(".$acc_codes.")";
			$update_account_query = $this->db->query($update_account_str);
		}
		return true;
	}
	
	private function process_quality_audit_record($data, $visitRow,$end_date, $acc_codes){
		$vkey = $visitRow['vkey'];
		$total_rows = 0;
		//Check whehter the audit record is already added or not.
		$qa_audit_query_str = "SELECT qa_audit_id FROM qa_audit WHERE audit_key ='".$vkey."'";
		$qa_audit_query= $this->db->query($qa_audit_query_str);
		$qa_audit_data = $qa_audit_query->result();
			
		$account_code = $visitRow['acccode'];
			
		//Get the QA account id from database
		$qa_account_query_str = "SELECT qa_account_id AS acc_id FROM qa_account WHERE account_code = '".$account_code."'";
		$qa_account_query= $this->db->query($qa_account_query_str);
		$qa_account_data = $qa_account_query->result();
			
		$account_id = $qa_account_data[0]->acc_id;
			
		if($qa_audit_data == null){
			//Check whether site is already configured or not.
			$qa_site_query_str = "SELECT qa_site_id AS site_id, site_name FROM qa_site WHERE qa_account_id = '".$account_id."' AND site_code ='".$visitRow['contno']."'";
			$qa_site_query= $this->db->query($qa_site_query_str);
			$qa_site_data = $qa_site_query->result();
			if($qa_site_data == null){
				//Insert a qa_site id row
				$insert_site_str = "INSERT INTO qa_site(qa_account_id, site_name, site_code, cdate)
	 									VALUES('".$account_id."','".mysql_real_escape_string($visitRow['custnam'])."','".$visitRow['contno']."', NOW())";
				$insert_site_query = $this->db->query($insert_site_str);
				$site_id = $this->db->insert_id();
			} else {
				$site_id = $qa_site_data[0]->site_id;
				//Update if the site is not same as
				if($qa_site_data[0]->site_name != $visitRow['custnam']){
					$update_site_str = "UPDATE qa_site SET site_name ='".mysql_real_escape_string($visitRow['custnam'])."' WHERE qa_site_id='".$site_id."'";
					$update_site_query = $this->db->query($update_site_str);
				}
			}
		
			//Check whether the auditor is already configured or not.
			$qa_auditor_query_str = "SELECT qa_auditor_id AS auditor_id FROM qa_auditor WHERE qa_account_id = '".$account_id."' AND auditor_name ='".$visitRow['vuser']."'";
			$qa_auditor_query= $this->db->query($qa_auditor_query_str);
			$qa_auditor_data = $qa_auditor_query->result();
			if($qa_auditor_data == null){
				//Insert a qa_site id row
				$insert_auditor_str = "INSERT INTO qa_auditor(qa_account_id, auditor_name, cdate)
	 									VALUES('".$account_id."','".$visitRow['vuser']."', NOW())";
				$insert_auditor_query = $this->db->query($insert_auditor_str);
				$auditor_id = $this->db->insert_id();
			} else
				$auditor_id = $qa_auditor_data[0]->auditor_id;
		
			//Insert a audit record
			$insert_qudit_str = "INSERT INTO qa_audit(qa_account_id, qa_auditor_id, qa_site_id, audit_key, audit_date, signed_offuser, audit_month, audit_start_time, audit_end_time, rag_status, overall_rating, cdate)
	 									VALUES('".$account_id."','".$auditor_id."','".$site_id."','".$vkey."','".$visitRow['vdate']."','".$visitRow['signedoff']."','".$visitRow['vMONTH']."','".$visitRow['itime']."','".$visitRow['ctime']."','".$visitRow['RAG']."','".$visitRow['overall']."', NOW())";
			$insert_qudit_query = $this->db->query($insert_qudit_str);
			$audit_id = $this->db->insert_id();
		
			//Loop through all details rows and insert area, subarea, audit records...
		
			$visit_detail_rows = $data['visitDetails']['visitDetailsRow'];
			for($j=0;$j<count($visit_detail_rows);$j++){
				//Check whether vkey is same as visit rows
				if($vkey == $visit_detail_rows[$j]['vkey']){
						
					$total_rows++;
					//Check whether the area is already configured or not.
					$qa_area_query_str = "SELECT qa_area_id AS area_id, area_name FROM qa_area WHERE qa_site_id = '".$site_id."' AND area_code ='".$visit_detail_rows[$j]['areaid']."'";
					$qa_area_query= $this->db->query($qa_area_query_str);
					$qa_area_data = $qa_area_query->result();
					if($qa_area_data == null){
						//Insert a qa_area id row
						$insert_area_str = "INSERT INTO qa_area(qa_site_id, area_name, area_code, cdate)
	 									VALUES('".$site_id."','".mysql_real_escape_string($visit_detail_rows[$j]['area'])."','".$visit_detail_rows[$j]['areaid']."', NOW())";
						$insert_area_query = $this->db->query($insert_area_str);
						$area_id = $this->db->insert_id();
					} else{
						$area_id = $qa_area_data[0]->area_id;
		
						//Update if the area is not same as
						if($qa_area_data[0]->area_name != $visit_detail_rows[$j]['area']){
							$update_area_str = "UPDATE qa_area SET area_name ='".mysql_real_escape_string($visit_detail_rows[$j]['area'])."' WHERE qa_area_id='".$area_id."'";
							$update_area_query = $this->db->query($update_area_str);
						}
					}
					//Check whether the subarea is already configured or not.
					$qa_subarea_query_str = "SELECT qa_subarea_id AS subarea_id,subarea_name FROM qa_subarea WHERE qa_area_id = '".$area_id."' AND subarea_code ='".$visit_detail_rows[$j]['subareaid']."'";
					$qa_subarea_query= $this->db->query($qa_subarea_query_str);
					$qa_subarea_data = $qa_subarea_query->result();
					if($qa_subarea_data == null){
						//Insert a qa_area id row
						$insert_subarea_str = "INSERT INTO qa_subarea(qa_area_id, subarea_name, subarea_code, cdate)
	 									VALUES('".$area_id."','".mysql_real_escape_string($visit_detail_rows[$j]['subarea'])."','".$visit_detail_rows[$j]['subareaid']."', NOW())";
						$insert_subarea_query = $this->db->query($insert_subarea_str);
						$subarea_id = $this->db->insert_id();
					} else {
						$subarea_id = $qa_subarea_data[0]->subarea_id;
							
						//Update if the subarea is not same as
						if($qa_subarea_data[0]->subarea_name != $visit_detail_rows[$j]['subarea']){
							$update_subarea_str = "UPDATE qa_subarea SET subarea_name ='".mysql_real_escape_string($visit_detail_rows[$j]['subarea'])."' WHERE qa_subarea_id='".$subarea_id."'";
							$update_subarea_query = $this->db->query($update_subarea_str);
						}
					}
					//Check whether the point indicator exists or not
					$qa_pointind_query_str = "SELECT qa_point_indicator_id AS point_ind FROM qa_point_indicator WHERE qa_account_id = '".$account_id."' AND point_indicator_name ='".$visit_detail_rows[$j]['pointscore']."'";
					$qa_pointind_query= $this->db->query($qa_pointind_query_str);
					$qa_pointind_data = $qa_pointind_query->result();
					if($qa_pointind_data == null){
						//Insert a qa_area id row
						$insert_pointind_str = "INSERT INTO qa_point_indicator(qa_account_id, point_indicator_name, point_value, cdate)
	 									VALUES('".$account_id."','".$visit_detail_rows[$j]['pointscore']."','".$visit_detail_rows[$j]['pointvalue']."', NOW())";
						$insert_pointind_query = $this->db->query($insert_pointind_str);
						$pointind_id = $this->db->insert_id();
							
						//Insert into qa_account_group_point_indicator for default....
						//Get the default account group id for default and sequence number
		
						$qa_accgrp_query_str = "SELECT ag.qa_account_group_id, COALESCE(MAX(sequence_no),0) + 1 AS sequence_no
													FROM qa_account_group ag
													LEFT JOIN qa_account_group_point_indicator agp ON ag.qa_account_group_id = agp.qa_account_group_id
													WHERE qa_account_id ='".$account_id."' AND is_default = '".ACTIVE."'";
						$qa_accgrp_query= $this->db->query($qa_accgrp_query_str);
						$qa_accgrp_data = $qa_accgrp_query->result();
		
						//Insert a row in account group point indicator
							
						$insert_accgrppointind_str = "INSERT INTO qa_account_group_point_indicator(qa_account_group_id, qa_point_indicator_id, sequence_no, cdate,cuser_id)
	 									VALUES('".$qa_accgrp_data[0]->qa_account_group_id."','".$pointind_id."','".$qa_accgrp_data[0]->sequence_no."', NOW(),1)";
						$insert_pointind_query = $this->db->query($insert_accgrppointind_str);
		
					} else
						$pointind_id = $qa_pointind_data[0]->point_ind;
		
					//Insert the qa audit point record
					$insert_auditpoint_str = "INSERT INTO qa_audit_point(qa_audit_id, qa_subarea_id, point_name, point_code, qa_point_indicator_id, point_color, point_weight, point_value, comment,cdate)
	 									VALUES('".$audit_id."','".$subarea_id."','".mysql_real_escape_string($visit_detail_rows[$j]['point'])."','".$visit_detail_rows[$j]['pointid']."','".$pointind_id."', '".$visit_detail_rows[$j]['pointcolor']."','".$visit_detail_rows[$j]['weight']."','".$visit_detail_rows[$j]['pointvalue']."', '".mysql_real_escape_string($visit_detail_rows[$j]['com'])."',NOW())";
					$insert_auditpoint_query = $this->db->query($insert_auditpoint_str);
		
		
					//echo $account_id . "    " . $site_id. "   ".$auditor_id." ".$audit_id." " . $area_id." ". $subarea_id. "   ". $pointind_id ." ". $total_rows ."<br>";
				} //End of details rows for vkey condition
			}  // End of details rows loop
		}  //  End of audit rows exists or not condition
		
		//Update the account table for last received message and last
		if($total_rows > 0)
			$message = "Received ".$total_rows." Audit record(s) on ".$data['end_date'];
		else
			$message = "No Audit records received on ".$data['end_date'];

		$last_update_msg = ($data['last_update_flag']) ? "'".$end_date."'" : "last_updated";
		
		$update_account_str = "UPDATE qa_account SET last_status ='".$message."' ,last_updated=".$last_update_msg." WHERE qa_account_id = '".$account_id."'";
		
		$update_account_query = $this->db->query($update_account_str);
		$acc_codes = str_replace(",".$account_code.",",",",$acc_codes);
		
		return $acc_codes;
	}
	public function update_qaacc_last_recived($acccodes, $end_date){
		$message = "Failed to Connect on ".$end_date;
		$acccodes = "'".str_replace(",","','",$acccodes)."'";
		$update_account_str = "UPDATE qa_account SET last_status ='".$message."'  WHERE account_code IN(".$acccodes.")";
		$update_account_query = $this->db->query($update_account_str);
	}
}
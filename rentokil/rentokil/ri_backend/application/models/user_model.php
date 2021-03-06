<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	function __construct()
	{
		// Call the Model constructor
		parent::__construct();
		//$this->load->database();
	}

	public function chk_user($auth_obj)
	{
		$username = $auth_obj->username;
		$password = md5($auth_obj->password);

		$this->db->select('1 as user_auth_count, user_id, profile_id, role_id, status, chg_pwd',false);
		$this->db->from('users');
		$this->db->where('username',$username);
		$this->db->where('password',$password);
		//$this->db->where('status',DEFAULT_STATUS);
		$query = $this->db->get();
		$res_auth = $query->result();
		return (count($res_auth)>0) ? $res_auth[0] : null;
	}

	public function get_user_info($user_id)
	{
		$this->db->select('username, role_id,u.customer_id,first_name,last_name,title_id,user_email,profile_id,telephone,work_telephone,mobile_number,sms_notification,mail_notification,u.session_log as user_log,u.contract_id,con.session_log as con_log,contract_name,contract_key,con.vat AS vat');
		$this->db->from('users u');
		$this->db->join('contracts con', 'con.contract_id = u.contract_id','left');
		$this->db->where('u.user_id',$user_id);
		$this->db->where('u.status',DEFAULT_STATUS);
		$query = $this->db->get();
		$res_user = $query->result();
		
		$user_obj = new stdClass();
		$user_obj->first_name = $res_user[0]->first_name;
		$user_obj->last_name = $res_user[0]->last_name;
		$user_obj->user_id = $user_id;
		$user_obj->username = $res_user[0]->username;
		$user_obj->user_email = $res_user[0]->user_email;
		$user_obj->profile_id = $res_user[0]->profile_id;
		$user_obj->telephone = $res_user[0]->telephone;
		$user_obj->work_telephone = $res_user[0]->work_telephone;
		$user_obj->mobile_number = $res_user[0]->mobile_number;
		$user_obj->mail_notification = $res_user[0]->mail_notification;
		$user_obj->sms_notification = $res_user[0]->sms_notification;
		
		$user_obj->contract_id = $res_user[0]->contract_id;
		$user_obj->contract_name = $res_user[0]->contract_name;
		$user_obj->contract_key = $res_user[0]->contract_key;
		$user_obj->vat = $res_user[0]->vat;
		
		if($res_user[0]->role_id == USER)
		{
			$m_query_str = "SELECT a.s_module_code m, a.m_module_name mname FROM (
					SELECT m.m_module_id, m_module_name, s.sequence_no, s.s_module_code
					FROM m_modules m INNER JOIN s_modules s on m.m_module_id = s.m_module_id AND m.status = ".ACTIVE." ) a
				INNER JOIN( 
					SELECT m_module_id , min(s.sequence_no) min_val FROM users u 
							INNER JOIN profiles_s_modules psm ON u.profile_id = psm.profile_id
							INNER JOIN s_modules s ON psm.s_module_id = s.s_module_id
							WHERE u.user_id =  ".$user_id."
							AND s.status= ".ACTIVE."  GROUP BY s.m_module_id) b ON a.m_module_id = b.m_module_id AND a.sequence_no = b.min_val LIMIT 1";

		//echo $m_query_str;
		$m_query = $this->db->query($m_query_str);
		$m_query_data = $m_query->result();
			
			
// 			$this->db->select('m.m_module_code');
// 			$this->db->from('users u');
// 			$this->db->join('profiles_s_modules ps', 'u.profile_id = ps.profile_id');
// 			$this->db->join('s_modules s', 'ps.s_module_id = s.s_module_id');
// 			$this->db->join('m_modules m', 's.m_module_id = m.m_module_id');
// 			$this->db->where('user_id',$user_id);
// 			$this->db->where('s.status',ACTIVE);
// 			$this->db->where('m.status',ACTIVE);
// 			$this->db->order_by('m.sequence_no','ASC');
// 			$this->db->order_by('s.sequence_no','ASC');
// 			$this->db->limit(1);
// 			$query_profiles = $this->db->get();
// 			$res_user_profiles = $query_profiles->result();		
// 			echo $this->db->last_query();	

			$user_obj->session_log = ($res_user[0]->user_log == 1 && $res_user[0]->con_log == 1) ? 1 : 0;
			//$user_obj->default_mod = (count($res_user_profiles) >0) ? $res_user_profiles[0]->m_module_code : "";
			
			$user_obj->default_mod = (count($m_query_data) >0) ? $m_query_data[0]->m : "";
			
			$this->db->select('COUNT(1) as cnt');
			$this->db->from('school_admins');
			$this->db->where('user_id', $user_id);
			$query = $this->db->get();
			$res_adm = $query->result();
			
			if($res_adm[0]->cnt > 0)
				$user_obj->sch_adm = TRUE;
			else
				$user_obj->sch_adm = FALSE;			
		}
		if($res_user[0]->role_id == CUSTOMER_ADMIN)
		{
			$user_obj->session_log = $res_user[0]->user_log;
		}		
		
		$this->db->select('customer_id,customer_name');
		$this->db->from('customers');
		$this->db->where('customer_id',$res_user[0]->customer_id);
		$query = $this->db->get();
		$res_customer = $query->result();
		
		$this->db->select('role_id,role_name');
		$this->db->from('roles');
		$this->db->where('role_id',$res_user[0]->role_id);
		$query = $this->db->get();
		$res_role = $query->result();
		
		$this->db->select('data_value');
		$this->db->from('data_value');
		$this->db->where('data_value_id',$res_user[0]->title_id);
		$query = $this->db->get();
		$res_title = $query->result();
		if (!empty($res_title[0]->data_value))
		{
			$user_obj->title = $res_title[0]->data_value;
		}
		else
		{
			$user_obj->title = null;
		}
		
		$user_obj->customer_name = $res_customer[0]->customer_name;
		$user_obj->customer_id = $res_customer[0]->customer_id;
		$user_obj->role_name = $res_role[0]->role_name;
		$user_obj->role_id = $res_role[0]->role_id;
		
		return $user_obj;
	}
	
	function get_skin_info($data)
	{
		$this->db->select('skin_id');
		$this->db->from('profiles');
		$this->db->where('profile_id', $data['profile_id']);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->where('status', ACTIVE);
			
		$query = $this->db->get();
		$res_profile = $query->result();
		
		if(count($res_profile) > 0)
		{
			$this->db->select('skin_id as sid, left_foot_block_title lfbt, left_foot_block_d1 lfbd1, left_foot_block_11 lfbl1, left_foot_block_d2 lfbd2, left_foot_block_l2 lfbl2, left_foot_block_d3 lfbd3, left_foot_block_l3 lfbl3, left_foot_block_d4 lfbd4, left_foot_block_l4 lfbl4, right_foot_block_title rfbt, right_foot_block_d1 rfbd1, right_foot_block_11 rfbl1, right_foot_block_d2 rfbd2, right_foot_block_l2 rfbl2, right_foot_block_d3 rfbd3, right_foot_block_l3 rfbl3, right_foot_block_d4 rfbd4, right_foot_block_l4 rfbl4, left_foot_block_s1 lfbs1, left_foot_block_s2 lfbs2, left_foot_block_s3 lfbs3, left_foot_block_s4 lfbs4, right_foot_block_s1 rfbs1, right_foot_block_s2 rfbs2, right_foot_block_s3 rfbs3, right_foot_block_s4 rfbs4, footer_copy_text fct');
			$this->db->from('skins');
			$this->db->where('contract_id', $data['contract_id']);
			$this->db->where('skin_id', $res_profile[0]->skin_id);

			$query = $this->db->get();
			$res_skin = $query->result();
			return $res_skin[0];
		}
		else
		{
			return FALSE;
		}		
	}
	
	function get_cadmin_profile_info($profile_id)
	{
		$this->db->select('ss.ad_ss_mod_code');
		$this->db->from('ad_ss_mod ss');		
		$this->db->join('ad_profiles_ss_mod pss', 'pss.ad_ss_mod_id = ss.ad_ss_mod_id');
		$this->db->where('pss.ad_profile_id', $profile_id);
			
		$query = $this->db->get();
		$res_cadmin_profiles = $query->result();
		
		$this->db->select('create_con');
		$this->db->from('ad_profiles');
		$this->db->where('ad_profile_id', $profile_id);
		
		$query = $this->db->get();
		$res_con = $query->result();
		
		if($res_con)
		{
			$profile_obj->profile_status = ACTIVE;
			$profile_obj->cr_con = $res_con[0]->create_con;
			
			$profile_obj->profile_res = array();
			if(count($res_cadmin_profiles) > 0)
			{
				foreach($res_cadmin_profiles as $key => $value)
				{
					$prf_ary[] = $value->ad_ss_mod_code;
				}
				$profile_obj->profile_res = $prf_ary;
			}			
		}
		else
		{
			$profile_obj->profile_status = INACTIVE;
			$profile_obj->cr_con = INACTIVE;			
			$profile_obj->profile_res = array();
		}
		return $profile_obj;
	}
	
	function update_user_activity($user_data)
	{
		$data = array(
					'last_ip' => $user_data['ip_address']
		);
		$this->db->set('last_login','NOW()', FALSE);
		$this->db->where('user_id',$user_data['user_id']);
		$this->db->update('users',$data);
	}
	
	public function get_profile_name($profile_id)
	{
		$this->db->select('profile_name');
		$this->db->from('profiles');
		$this->db->where('profile_id', $profile_id);
		
		$query = $this->db->get();
		$res_profiles = $query->result();
		
		return $res_profiles;
	}

	public function get_halfhourly_chart($data) {
		//calculate the last 15 days of data.
		$end_date = $data['sel_date'];
		$end_date = date('Y/m/d', strtotime(str_replace('/', '-', $end_date)));

		//For last two weeks 15 days actual data
		$start_date = date('Y/m/j' , strtotime ( '-14 day' , strtotime ($end_date )));

		if(strtolower($data['utility_type']) =="gas")
		$utility_query = " AND s.utility='GAS' ";
		else if (strtolower($data['utility_type']) =="ele")
		$utility_query = " AND s.utility='Electricity' ";
		else 
			$utility_query ="";

		$query_str ="SELECT " . $data['contract_id']. " as contract_id, '" . $start_date . "' as d";
		for($i=13; $i>=1; $i--) {
			//$d = date('Y/m/j' , strtotime ( '-'.$i.' day' , strtotime ($end_date )));
			$query_str = $query_str ." union SELECT " . $data['contract_id']. " as contract_id, '" . date('Y/m/j' , strtotime ( '-' . $i .' day' , strtotime ($end_date ))) . "' as d";

		}
		$query_str = $query_str. " union  SELECT " . $data['contract_id']. " as contract_id, '" . $end_date  . "' as d";

		$query_str = "SELECT CONCAT(COALESCE(b.t0030,0),',', COALESCE(b.t0100,0),',',COALESCE(b.t0130,0),',',COALESCE(b.t0200,0),',',COALESCE(b.t0230,0),',',COALESCE(b.t0300,0),',',COALESCE(b.t0330,0),',',COALESCE(b.t0400,0),',',COALESCE(b.t0430,0),',',COALESCE(b.t0500,0),',',COALESCE(b.t0530,0),',',COALESCE(b.t0600,0),',',COALESCE(b.t0630,0),',',COALESCE(b.t0700,0),',',COALESCE(b.t0730,0),',',COALESCE(b.t0800,0),',',COALESCE(b.t0830,0),',',COALESCE(b.t0900,0),',',COALESCE(b.t0930,0),',',COALESCE(b.t1000,0),',',COALESCE(b.t1030,0),',',COALESCE(b.t1100,0),',',COALESCE(b.t1130,0),',',COALESCE(b.t1200,0),',',COALESCE(b.t1230,0),',',COALESCE(b.t1300,0),',',COALESCE(b.t1330,0),',',COALESCE(b.t1400,0),',',COALESCE(b.t1430,0),',',COALESCE(b.t1500,0),',',COALESCE(b.t1530,0),',',COALESCE(b.t1600,0),',',COALESCE(b.t1630,0),',',COALESCE(b.t1700,0),',',COALESCE(b.t1730,0),',',COALESCE(b.t1800,0),',',COALESCE(b.t1830,0),',',COALESCE(b.t1900,0),',',COALESCE(b.t1930,0),',',COALESCE(b.t2000,0),',',COALESCE(b.t2030,0),',',COALESCE(b.t2100,0),',',COALESCE(b.t2130,0),',',COALESCE(b.t2200,0),',',COALESCE(b.t2230,0),',',COALESCE(b.t2300,0),',',COALESCE(b.t2330,0),',',COALESCE(b.t2400,0)) C
				FROM ( ". $query_str ." )a LEFT JOIN (SELECT s.contract_id as contract_id, h.stats_date, SUM(h.t0030) t0030, SUM(h.t0100) AS  t0100,SUM(h.t0130) AS  t0130,SUM(h.t0200) AS  t0200,SUM(h.t0230) AS  t0230,SUM(h.t0300) AS  t0300,SUM(h.t0330) AS  t0330,SUM(h.t0400) AS  t0400,SUM(h.t0430) AS  t0430,SUM(h.t0500) AS  t0500,SUM(h.t0530) AS  t0530,SUM(h.t0600) AS  t0600,SUM(h.t0630) AS  t0630,SUM(h.t0700) AS  t0700,SUM(h.t0730)AS t0730,SUM(h.t0800) AS  t0800,SUM(h.t0830) AS  t0830,SUM(h.t0900) AS  t0900,SUM(h.t0930) AS  t0930,SUM(h.t1000) AS  t1000,SUM(h.t1030) AS  t1030,SUM(h.t1100) AS t1100,SUM(h.t1130) AS  t1130,SUM(h.t1200) AS  t1200,SUM(h.t1230) AS  t1230,SUM(h.t1300) AS  t1300,SUM(h.t1330) AS  t1330,SUM(h.t1400) AS  t1400,SUM(h.t1430) AS t1430,SUM(h.t1500) AS  t1500,SUM(h.t1530) AS  t1530,SUM(h.t1600) AS  t1600,SUM(h.t1630) AS  t1630,SUM(h.t1700) AS  t1700,SUM(h.t1730) AS  t1730,SUM(h.t1800) AS t1800,SUM(h.t1830) AS  t1830,SUM(h.t1900) AS  t1900,SUM(h.t1930) AS  t1930,SUM(h.t2000) AS  t2000,SUM(h.t2030) AS  t2030,SUM(h.t2100) AS  t2100,SUM(h.t2130) AS t2130,SUM(h.t2200) AS  t2200,SUM(h.t2230) AS  t2230,SUM(h.t2300) AS  t2300,SUM(h.t2330) AS  t2330,SUM(h.t2400) AS  t2400 
						FROM hh_stats_data h INNER JOIN setup_data s on h.setup_data_id = s.setup_data_id AND s.active=1 and s.contract_id = ".$data['contract_id']."".$utility_query ."
				 WHERE stats_date between '". $start_date ."' AND '". $end_date ."' GROUP BY s.contract_id, h.stats_date ) b on a.contract_id = b.contract_id and a.d = b.stats_date"; 


		//echo $query_str;
		$query = $this->db->query($query_str);
		$query_data = $query->result();
		
		$str = "";
		foreach($query_data as $obj){
			$str = $str . $obj->C .',';
		}
		$str = substr($str, 0, -1);

		$chart_data = array(
				'start_date' => $start_date,
				'actual' => array($str)
		);
		return $chart_data;
	}

	public function get_daily_chart($data) {
		//calculate the last 15 days of data.
		$end_date = $data['sel_date'];
		$end_date = date('Y/m/d', strtotime(str_replace('/', '-', $end_date)));

		//For last 30 days of data
		$start_date = date('Y/m/j' , strtotime ( '-30 day' , strtotime ($end_date )));
		
		$utility_query = "";
		if(strtolower($data['utility_type']) =="gas")
		$utility_query = " AND s.utility='GAS' ";
		else if (strtolower($data['utility_type']) =="ele")
		$utility_query = " AND s.utility='Electricity' ";

		$actual_query_str ="SELECT
							DATE_FORMAT(DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY),'%D %b') stats_date,
							ROUND(COALESCE(b.k,0),2) AS k,
							ROUND(COALESCE(b.baseline,0),2) AS baseline,
							ROUND(COALESCE(b.epc,0),2) AS epc,
							ROUND(COALESCE(b.energysavings,0),2) AS energysavings,
							ROUND(COALESCE(b.costsavings/100,0),2) AS costsavings
							FROM numbers n 
							LEFT JOIN(
								SELECT a.contract_id, a.stats_date,
									 	SUM(a.kwh) AS k, 
									 	SUM(a.baseline) AS baseline, 
										SUM(a.epc)AS epc, 
										SUM(a.energysavings) AS energysavings, 
										SUM(a.costsavings) AS costsavings   
								FROM(
									SELECT s.contract_id AS contract_id,stats_date,
											d.kwh AS kwh, 
											kwh_per_month/Day(LAST_DAY(stats_date)) AS baseline, 
											(kwh_per_month - (epc * kwh_per_month /100))/Day(LAST_DAY(stats_date)) AS epc, 
											(kwh_per_month/Day(LAST_DAY(stats_date)) - kwh) AS energysavings, 
											(kwh_per_month/Day(LAST_DAY(stats_date)) - kwh) * t.avg_per_kwh AS costsavings 
									FROM daily_stats_data d 
									INNER JOIN setup_data s ON d.setup_data_id = s.setup_data_id AND s.active=1 AND s.contract_id = ".$data['contract_id']."".$utility_query ."
									LEFT JOIN target_data t ON s.setup_data_id = t.setup_data_id AND t.baseline_year = YEAR(stats_date) AND t.baseline_month = MONTHNAME(stats_date) AND t.active=1 
									WHERE stats_date between '".$start_date."' and '".$end_date."'
								) a GROUP BY a.stats_date
							) b ON DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY)  = b.stats_date WHERE DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY) <= '".date('Y-m-d', strtotime($end_date))."'";


		//echo $actual_query_str;
		$query = $this->db->query($actual_query_str);
		$actual_query_data = $query->result();

		$i=0;
		$actual_str = "";
		$baseline_str = "";
		$epc_str ="";
		$es_str = "";
		$cs_str ="";
		foreach($actual_query_data as $obj){
			$actual_str = $actual_str . $obj->k .',';
			$baseline_str = $baseline_str . $obj->baseline .',';
			$epc_str = $epc_str.$obj->epc .',';
			$es_str = $es_str.$obj->energysavings.',';
			$cs_str = $cs_str.$obj->costsavings.',';
			$day_str[$i++] = $obj->stats_date;
		}

		$actual_str = substr($actual_str, 0, -1);
		$baseline_str = substr($baseline_str, 0, -1);
		$epc_str = substr($epc_str,0,-1);
		$es_str = substr($es_str,0,-1);
		$cs_str = substr($cs_str,0,-1);

		$chart_data = array(
				'start_date' => $start_date,
				'actual' => array($actual_str),
				'baseline' => array($baseline_str),
				'epc' => array($epc_str),
				'es' => array($es_str),
				'cs' => array($cs_str),
				'day' => $day_str,
		//'day' => array($start_date,'16th Jan 2013','17th Jan 2013','18th Jan 2013','19th Jan 2013','20th Jan 2013','21th Jan 2013'),
		);
		return $chart_data;
	}

	public function get_monthly_chart($data) {

		$end_date = $data['sel_date'];
		$end_date = date('Y/m/d', strtotime(str_replace('/', '-', $end_date)));
		$start_date = date('Y/m/j' , strtotime ( '-11 month' , strtotime ($end_date )));
		$end_date = date('Y/m/d', mktime(0,0,0,date('m', strtotime($end_date)), date('t',strtotime($end_date)), date('Y', strtotime($end_date))));

		$d = date('n', strtotime($start_date));
		$y = date('Y', strtotime($start_date));
		$utility_query = "";
		if(strtolower($data['utility_type']) =="gas")
			$utility_query = " AND s.utility='GAS' ";
		else if (strtolower($data['utility_type']) =="ele")
			$utility_query = " AND s.utility='Electricity' ";
			
		$first_query_str = "SELECT " . $data['contract_id']. " AS contract_id, '" . $d . "' as m, '".$y."' as y";
			
		for($i=1; $i<12; $i++)
		{
			if($d==12) {
				$d =0;
				$y++;
			}
			$d++;
			$first_query_str = $first_query_str." UNION SELECT " . $data['contract_id']. " as contract_id, '" . $d . "' as m, '".$y."' as y";
		}

		$query_str ="SELECT CONCAT(LEFT(MONTHNAME(STR_TO_DATE(a.m, '%m')),3), ' ',RIGHT(a.y,2)) mon,
							ROUND(COALESCE(b.k,0),2) AS k,
							ROUND(COALESCE(b.baseline,0),2) AS baseline,
							ROUND(COALESCE(b.epc,0),2) AS epc,
							ROUND(COALESCE(b.energysavings,0),2) AS energysavings,
							ROUND(COALESCE(b.costsavings/100,0),2) AS costsavings
							FROM ( " . $first_query_str.") a
							LEFT JOIN
							(
								SELECT t.contract_id,t.m, t.y, 
								SUM(t.kwh)  AS k,
								SUM(t.baseline) AS baseline, 
								SUM(t.epc)AS epc, 
								SUM(t.energysavings) AS energysavings, 
								SUM(t.costsavings) AS costsavings
								FROM numbers n 
								LEFT JOIN(
										SELECT s.contract_id AS contract_id, stats_date,MONTH(stats_date) AS m, YEAR(stats_date) AS y, 
										d.kwh, kwh_per_month/Day(LAST_DAY(stats_date)) AS baseline,
						  				(kwh_per_month - (epc * kwh_per_month /100))/Day(LAST_DAY(stats_date)) AS epc,
										(kwh_per_month/Day(LAST_DAY(stats_date)) - kwh) AS energysavings,
										(kwh_per_month/Day(LAST_DAY(stats_date)) - kwh) * t.avg_per_kwh AS costsavings
										FROM daily_stats_data d 
										INNER JOIN setup_data s ON d.setup_data_id = s.setup_data_id AND s.active=1 AND s.contract_id = ".$data['contract_id']."".$utility_query ." 
										LEFT  JOIN target_data t ON s.setup_data_id = t.setup_data_id AND t.baseline_year = YEAR(stats_date) and t.baseline_month = MONTHNAME(stats_date) AND t.active=1
										WHERE stats_date BETWEEN '".$start_date."' AND '".$end_date."'
									) t ON DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY)  = t.stats_date WHERE DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY) <= '".date('Y-m-d', strtotime($end_date))."' AND t.kwh IS NOT NULL
									GROUP BY t.m, t.y
									ORDER BY t.y,t.m
							) b ON a.contract_id = b.contract_id AND a.m = b.m AND a.y = b.y";
			


		//echo $query_str;
		$query = $this->db->query($query_str);
		$query_data = $query->result();

		$i=0;
		$actual_str = "";
		$baseline_str = "";
		$epc_str = "";
		$es_str = "";
		$cs_str = "";
		foreach($query_data as $obj){
			$actual_str = $actual_str . $obj->k .',';
			$baseline_str = $baseline_str . $obj->baseline .',';
			$epc_str = $epc_str.$obj->epc .',';
			$es_str = $es_str.$obj->energysavings.',';
			$cs_str = $cs_str.$obj->costsavings.',';
			$mon_str[$i++] = $obj->mon;
		}

		$actual_str = substr($actual_str, 0, -1);
		$baseline_str = substr($baseline_str, 0, -1);
		$epc_str = substr($epc_str,0,-1);
		$es_str = substr($es_str,0,-1);
		$cs_str = substr($cs_str,0,-1);

		//to fetch number of contracts in the given contract_id
		$contract_query_str = "SELECT COUNT(1)c FROM setup_data s WHERE s.active=1 AND s.contract_id = ".$data['contract_id']."".$utility_query ."";

		$query = $this->db->query($contract_query_str);
		$contract_query_data = $query->result();
			
		$total_contracts =  $contract_query_data[0]->c;
			
			
		//Pending for missing data display from daily_stats_data for given time range.
		$missing_query_str = "SELECT DATE_FORMAT(DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY),'%b %D') d,
									CASE WHEN curdate() >= DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY) THEN 'N' ELSE 'Y' END AS future FROM numbers n
				LEFT JOIN (SELECT stats_date, SUM(d.kwh) AS kwh, COUNT(1)c FROM daily_stats_data d
							INNER JOIN setup_data s ON d.setup_data_id = s.setup_data_id AND s.active=1 AND s.contract_id = ".$data['contract_id']."".$utility_query ."
							AND d.stats_date BETWEEN '".$start_date."' AND '".$end_date."' GROUP BY stats_date) a
				ON DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY)  = a.stats_date WHERE DATE_ADD('".$start_date."', INTERVAL n.id - 1 DAY) <= '".date('Y-m-d', strtotime($end_date))."'
				 AND (a.kwh IS NULL OR a.c!=". $total_contracts.")";

		//echo $missing_query_str;
		$query = $this->db->query($missing_query_str);
		$missing_query_data = $query->result();

		$future_flag ="N";
		$missing_str = "";
		foreach($missing_query_data as $obj){
			if($obj->future =='Y') {
				$future_flag ="Y";
				break;
			}
			$missing_str = $missing_str . $obj->d .', ';
		}
		if($future_flag =="Y") {
			if(strlen($missing_str) > 0) {
				$missing_str = substr($missing_str, 0, -2);
				$missing_str = $missing_str ." and future dates";
			}else
			$missing_str = $missing_str ." future dates";
		}
		else if(strlen($missing_str) > 0)
		$missing_str = substr($missing_str, 0, -2);

		$chart_data = array(
				'actual' => array($actual_str),
				'baseline' => array($baseline_str),
				'epc' => array($epc_str),
				'es' => array($es_str),
				'cs' => array($cs_str),
				'month' => $mon_str,
				'missing' => $missing_str,
		);
		return $chart_data;
	}

	public function add_pupil($data)
	{
		$this->db->set('st.user_id', $data['user_id']);
		$this->db->where('s.contract_id',$data['contract_id']);
		$this->db->where('st.pupil_id', $data['pupil_id']);
		$this->db->where('st.active', ACTIVE);
		
		$this->db->where('st.school_classes_id = sc.school_classes_id');
		$this->db->where('s.school_id = sc.school_id');
		
		$this->db->update('students st, school_classes sc, schools s');
	}

	public function get_pupils($data)
	{
		$this->db->select('students_id,fname,mname,lname,pupil_id,fsm,s.school_name,s.school_id,adult,st.school_classes_id AS year_Id,st.class_col AS class_name');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('active', ACTIVE);
		$this->db->order_by("fname", "asc");
		$query = $this->db->get();
		$res_pupils = $query->result();		

		/*$this->db->select('s.school_id,year_label,year_status,class1_name,class1_status,class2_name,class2_status,class3_name,class3_status,class4_name,class4_status,class5_name,class5_status,class6_name,class6_status');
		 $this->db->from('school_classes sc');
		 $this->db->join('schools s', 's.school_id = sc.school_id');
		 $this->db->join('students st', 'st.school_classes_id = sc.school_classes_id');
		 $this->db->where('st.user_id', $data['user_id']);
		 $this->db->where('active', ACTIVE);
		 //$this->db->group_by('s.school_id'); */
		// 		$this->db->select('s.school_id');
		// 		$this->db->from('students st');
		// 		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		// 		$this->db->join('schools s', 's.school_id = sc.school_id');
		// 		$this->db->where('user_id', $data['user_id']);
		// 		$this->db->where('active', ACTIVE);
		// 		$this->db->group_by('s.school_id');
		// 		$query = $this->db->get();
		// 		$school_id_res = $query->result();
		// 		echo $this->db->last_query();

		/*$sql = "SELECT school_id, (
					CASE WHEN year_status =0
					THEN CONCAT_WS( ' ', year_label, '(Inactive)' )
					ELSE year_label
					END
					) AS year_val, 
					school_classes_id AS year_Id,
					(
					CASE WHEN class1_status =0
					THEN CONCAT_WS( ' ', class1_name, '(Inactive)' )
					ELSE class1_name
					END
					) AS class1_name, (
					
					CASE WHEN class2_status =0
					THEN CONCAT_WS( ' ', class2_name, '(Inactive)' )
					ELSE class2_name
					END
					) AS class2_name, (
					
					CASE WHEN class3_status =0
					THEN CONCAT_WS( ' ', class3_name, '(Inactive)' )
					ELSE class3_name
					END
					) AS class3_name, (
					
					CASE WHEN class4_status =0
					THEN CONCAT_WS( ' ', class4_name, '(Inactive)' )
					ELSE class4_name
					END
					) AS class4_name, (
					
					CASE WHEN class5_status =0
					THEN CONCAT_WS( ' ', class5_name, '(Inactive)' )
					ELSE class5_name
					END
					) AS class5_name, (
					
					CASE WHEN class6_status =0
					THEN CONCAT_WS( ' ', class6_name, '(Inactive)' )
					ELSE class6_name
					END
					) AS class6_name
					FROM school_classes
					WHERE school_id IN(SELECT s.school_id 
							FROM (students st) JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id 
							JOIN schools s ON s.school_id = sc.school_id 
							WHERE user_id = '".$data['user_id']."' AND active = ". ACTIVE." GROUP BY s.school_id)
					ORDER BY school_id,year_no";*/
		
		$sql = "SELECT sc.school_id, (
					CASE WHEN year_status =0
					THEN CONCAT_WS( ' ', year_label, '(Inactive)' ) 
					ELSE year_label 
					END 
					) AS year_val, 
					school_classes_id AS year_Id, 
					( 
					CASE WHEN class1_status =0 
					THEN CONCAT_WS( ' ', class1_name, '(Inactive)' ) 
					ELSE class1_name 
					END ) AS class1_name, ( 
					CASE WHEN class2_status =0 
					THEN CONCAT_WS( ' ', class2_name, '(Inactive)' ) 
					ELSE class2_name 
					END 
					) AS class2_name, ( 
					CASE WHEN class3_status =0 
					THEN CONCAT_WS( ' ', class3_name, '(Inactive)' ) 
					ELSE class3_name END ) 
					AS class3_name, ( 
					CASE WHEN class4_status =0 
					THEN CONCAT_WS( ' ', class4_name, '(Inactive)' ) 
					ELSE class4_name END ) 
					AS class4_name, ( 
					CASE WHEN class5_status =0 
					THEN CONCAT_WS( ' ', class5_name, '(Inactive)' ) 
					ELSE class5_name END ) 
					AS class5_name, ( 
					CASE WHEN class6_status =0 
					THEN CONCAT_WS( ' ', class6_name, '(Inactive)' ) 
					ELSE class6_name END ) 
					AS class6_name 
					FROM school_classes sc
					inner join (SELECT s.school_id 
									FROM (students st) JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id 
									JOIN schools s ON s.school_id = sc.school_id 
									WHERE user_id = '".$data['user_id']."' AND active = ". ACTIVE." GROUP BY s.school_id) 
									t on sc.school_id = t.school_id
					ORDER BY school_id,year_no";
			
		$query = $this->db->query($sql);
		$year_class_res[] = $query->result();
			
		$pupils_class_res['pupils_res'] = $res_pupils;
		$pupils_class_res['year_class_res'] = $year_class_res;
		return $pupils_class_res;
	}

	public function check_pupil_id($data)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('students');
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where("user_id IS NULL");
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$check_pupil_id_res = $query->result();
		$check_pupil_id_cnt = $check_pupil_id_res[0]->cnt;
		return $check_pupil_id_cnt;
	}

	public function check_contract_key($contract_id,$contract_key)
	{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('contracts');
		$this->db->where('contract_id',$contract_id);
		$this->db->where('contract_key',$contract_key);
		$query = $this->db->get();
		$check_key_res = $query->result();
		return $check_key_res[0]->cnt;
	}

	public function check_pupil($data) {
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('students');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$check_pupil_res = $query->result();
		$check_pupil_cnt = $check_pupil_res[0]->cnt;
		return $check_pupil_cnt;
	}

	public function pupil_unassign($data) {
		$this->db->set('user_id', NULL);
		//$this->db->set('muser_id', $data['user_id']);
		//$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where('active', ACTIVE);
		$this->db->update('students');
	}

	/*public function validate_parent($data)
	 {
		foreach ($data['pupils_data'] as $pupil_data)
		{
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('students');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('students_id', $pupil_data['student_id']);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$check_parent_res = $query->result();
		//echo $this->db->last_query(); exit;
		$check_parent_cnt = $check_parent_res[0]->cnt;
		if($check_parent_cnt == 0)
		{
		return FALSE;
		}
		}
		return TRUE;
		}*/

	public function edit_pupils($data)
	{
		foreach ($data['pupils_data'] as $pupil_data)
		{
			$this->db->set('fname', $pupil_data['fname']);
			$this->db->set('mname', $pupil_data['mname']);
			$this->db->set('lname', $pupil_data['lname']);
			$this->db->set('school_classes_id', $pupil_data['year']);
			$this->db->set('class_col', $pupil_data['class']);
			$this->db->where('pupil_id', $pupil_data['pupil_id']);
			$this->db->where('user_id', $data['user_id']);
			$this->db->where('active', ACTIVE);
			$this->db->update('students');
		}
		return TRUE;
	}

	public function get_school_menu_details($data){

		$this->db->select("COUNT(1) AS c", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$query = $this->db->get();
		$res_menu_count = $query->result();

		$this->db->select("con_cater_menu_settings_id AS menu_id, ". $this->db->escape_str($data['week_cycle']) ." AS w ,menu_cycles AS wc, DATE_FORMAT(menu_start_date, '%d/%m/%Y' ) AS mdate, menu_sequence AS mseq", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('menu_sequence',$data['menu_seq']);
		$query = $this->db->get();
		$res_menu_settings = $query->result();

		$this->db->select("con_cater_menu_settings_id AS menu_id ", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where('menu_start_date <= CURDATE()');
		$this->db->where('menu_status = 1');
		$this->db->order_by("menu_start_date", "DESC");
		$this->db->limit(1);
		$query = $this->db->get();
		$res_menu_active = $query->result();

		$this->db->select('con_cater_menu_details_id AS mid, week_day AS w, meal_type AS m, option_details AS d , option_cost AS c, option_status AS s');
		$this->db->from('con_cater_menu_details');
		$this->db->where('con_cater_menu_settings_id',$res_menu_settings[0]->menu_id);
		$this->db->where('week_cycle',$data['week_cycle']);
		$this->db->order_by("week_day", "ASC");
		$this->db->order_by("meal_type", "ASC");
		$this->db->order_by("option_sequence", "ASC");
		$query = $this->db->get();
		$res_menu_details = $query->result();

		$this->db->select('sc.con_cater_menu_details_id AS mid, status s');
		$this->db->from('sc_cater_menu_settings sc');
		$this->db->join('con_cater_menu_details cm', 'sc.con_cater_menu_details_id = cm.con_cater_menu_details_id');
		$this->db->where('con_cater_menu_settings_id',$res_menu_settings[0]->menu_id);
		$this->db->where('week_cycle',$data['week_cycle']);
		$this->db->where('school_id',$data['school_id']);
		$query = $this->db->get();
		$res_school_details = $query->result();

		$this->db->select('sc.con_cater_menu_details_id AS mid, sc.status s');
		$this->db->from('sc_cater_menu_settings sc');
		$this->db->join('schools s', 'sc.school_id= s.production_id');
		$this->db->join('con_cater_menu_details cm', 'sc.con_cater_menu_details_id = cm.con_cater_menu_details_id');
		$this->db->where('s.school_id',$data['school_id']);
		$this->db->where('con_cater_menu_settings_id',$res_menu_settings[0]->menu_id);
		$this->db->where('week_cycle',$data['week_cycle']);
		$query = $this->db->get();
		$res_prod_school_details = $query->result();

		$this->db->select('COUNT(1) as cnt');
		$this->db->from('schools ');
		$this->db->where('school_id',$data['school_id']);
		$this->db->where('production_status',ACTIVE);
		$query = $this->db->get();
		$res_prod_check = $query->result();



		// 		if($res_prod_check[0]->cnt > 0)
		// 		{
		// 			$this->db->select('con_cater_menu_details_id AS mid, status s');
		// 			$this->db->from('sc_cater_menu_settings ');
		// 			$this->db->where('school_id',$data['school_id']);
		// 			$query = $this->db->get();
		// 			$res_school_details = $query->result();
		// 		}
		// 		else
		// 		{
		// 			$sql = "SELECT con_cater_menu_details_id AS mid,
		// 					STATUS s
		// 					FROM sc_cater_menu_settings
		// 					WHERE school_id = (
		// 					SELECT production_id
		// 					FROM schools
		// 					WHERE school_id = ? )";
		// 			$query = $this->db->query($sql, array($data['school_id']));
		// 			$res_school_details = $query->result();
		// 		}
		
		/* Get the school close related details */
		$qry = "SELECT DATE_FORMAT(s.closed_till,'%e/%m/%Y') as closed_till, s.closed_reason, (SELECT CONCAT_WS(' ',first_name,last_name) from users WHERE user_id = s.closed_by) as closed_by, CASE WHEN s.closed_till > DATE(NOW()) THEN 1 ELSE 0 END AS closed_status FROM schools s where s.school_id = ?";
		$query = $this->db->query($qry, array($data['school_id']));
		$school_details = $query->result();

		$res_menu[0]->mc = $res_menu_count;
		$res_menu[0]->ms = $res_menu_settings;
		$res_menu[0]->ma = $res_menu_active;
		$res_menu[0]->md = $res_menu_details;
		$res_menu[0]->sd = $res_school_details;
		$res_menu[0]->spd = $res_prod_school_details;		
		$res_menu[0]->p = $res_prod_check[0]->cnt;
		$res_menu[0]->scd = $school_details[0];

		return $res_menu;
	}

	public function save_school_menu_details($data){
		$con_ids ="";
		//Update the each menu option row.
		foreach ($data['school_menu_details'] as $schools_menus_data)
		{
			// Get old value to update order items
			$this->db->select('status');
			$this->db->from('sc_cater_menu_settings');
			$this->db->where('school_id', $data['school_id']);
			$this->db->where('con_cater_menu_details_id ', $schools_menus_data['cmid']);
			$query = $this->db->get();
			$res_old_status = $query->result();
			$old_status = $res_old_status[0]->status;
			if($old_status != $schools_menus_data['os'])
			{
				$con_ids = $con_ids.$schools_menus_data['cmid'].",";
				$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING, MENU_NUMBER_REPLACE_STRING, WEEK_NUMBER_REPLACE_STRING, SCHOOL_REPLACE_STRING);
				$batch_data = array('user_id' => $data['user_id'], 'school_id' => $data['school_id'], 'menu_no' => $data['menu_seq'], 'week_no' => $data['week_cycle'], 'str' => MENU_ITEM_DESELECT_UPDATE_MESSAGE, 'key_values' => $batch_key);
				$data['reason_msg'] = generate_batch_system_messages($batch_data);
				
				$batch_cancel_id = create_batch_cancel($data, MENU_ITEM_DESELECT_DATA_ID);
			}
			
			$this->db->set('status', $schools_menus_data['os']);
			$this->db->set('muser_id', $data['user_id']);
			$this->db->set('mdate', 'NOW()', FALSE);
			$this->db->where('school_id', $data['school_id']);
			$this->db->where('con_cater_menu_details_id ', $schools_menus_data['cmid']);
			$this->db->update('sc_cater_menu_settings');
		}
		
		//Update all future orders.
		$con_ids = substr($con_ids, 0, -1);
		if($con_ids)
		{
			$update_query_str ="UPDATE order_items o1 SET o1.order_edited = 1, o1.batch_cancel_id = ". $batch_cancel_id  ." WHERE o1.order_id in (
					SELECT order_id FROM (
				SELECT order_id FROM 
				order_items o INNER JOIN
			sc_cater_menu_settings  s on s.sc_cater_menu_settings_id = o.sc_cater_menu_settings_id
				WHERE  con_cater_menu_details_id IN (".$con_ids.") AND fulfilment_date >= DATE(NOW()) AND collect_status = 0)a )";
			$update_query = $this->db->query($update_query_str);
		}

		//get whether the school is  production or not.
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('schools ');
		$this->db->where('school_id',$data['school_id']);
		$this->db->where('production_status',ACTIVE);
		$query = $this->db->get();
		$res_prod_check = $query->result();
		if($res_prod_check[0]->cnt > 0){
			//If the school is production then insert the newly enabled options to it's servery school.
			$query_str  ="INSERT INTO sc_cater_menu_settings(school_id, con_cater_menu_details_id,status, cuser_id, cdate)
						SELECT s.school_id, sc.con_cater_menu_details_id,1,".$data['user_id'].",NOW() FROM schools s 
						INNER JOIN sc_cater_menu_settings sc ON s.production_id = sc.school_id
						INNER JOIN con_cater_menu_details cd ON sc.con_cater_menu_details_id = cd.con_cater_menu_details_id
						INNER JOIN con_cater_menu_settings cs on cs.con_cater_menu_settings_id = cd.con_cater_menu_settings_id
						AND cs.contract_id = ".$data['contract_id']." AND cs.menu_sequence = ".$data['menu_seq']."
						AND week_cycle = ".$data['week_cycle']."  AND sc.status = 1
						LEFT JOIN(
							SELECT sc.school_id, sc.con_cater_menu_details_id
							FROM sc_cater_menu_settings sc
							INNER JOIN con_cater_menu_details cd ON sc.con_cater_menu_details_id = cd.con_cater_menu_details_id
							INNER JOIN con_cater_menu_settings cs ON cs.con_cater_menu_settings_id = cd.con_cater_menu_settings_id
							AND cs.contract_id = ".$data['contract_id']." AND cs.menu_sequence = ".$data['menu_seq']."
							INNER JOIN schools  s on sc.school_id = s.school_id AND production_id = ".$data['school_id']." AND production_status = 0
							AND week_cycle = ".$data['week_cycle']." )a ON a.school_id = s.school_id AND sc.con_cater_menu_details_id = a.con_cater_menu_details_id
						WHERE s.production_status = 0 AND production_id = ".$data['school_id']."
						AND a.school_id IS NULL AND a.con_cater_menu_details_id IS NULL";


			$query = $this->db->query($query_str);
			//Delete the servery school rows if the parent production school options are disabled.
			$query_str = "DELETE  sc
							FROM  sc_cater_menu_settings sc 
							INNER JOIN con_cater_menu_details cd ON sc.con_cater_menu_details_id = cd.con_cater_menu_details_id 
							INNER JOIN con_cater_menu_settings cs ON cs.con_cater_menu_settings_id = cd.con_cater_menu_settings_id 
							AND cs.contract_id = ".$data['contract_id']." AND cs.menu_sequence = ".$data['menu_seq']." 
							INNER JOIN schools s on sc.school_id = s.school_id AND production_id = ".$data['school_id']." AND production_status = 0 AND week_cycle = ".$data['week_cycle']." AND sc.status = 0";
			$query = $this->db->query($query_str);

		}
	}
	
	/* Get search pupils for order details - school admin */
	public function get_order_search_pupils($data)
	{
		$student_query_str = "SELECT pupil_id
			FROM (students st)
			JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id
			JOIN schools s ON s.school_id = sc.school_id
			WHERE user_id =  ? AND s.school_id =  ? AND active = ".ACTIVE;
		
		$student_query = $this->db->query($student_query_str, array($data['user_id'],$data['school_id']));
		$res_order_pupils = $student_query->result();
		
		$pupil_val = "";
		foreach($res_order_pupils as $key => $value)
		{
			if (strpos($pupil_val,'pupil_id') !== false)
			{
				$pupil_val = $pupil_val . " AND pupil_id != '". $value->pupil_id ."'";
			}
			else
			{
				$pupil_val = $pupil_val. "pupil_id != '". $value->pupil_id ."'";
			}
		}

		if($pupil_val == "")
			$qry_str = "SELECT students_id, fname, mname, lname, pupil_id, fsm, s.school_name, adult, sc.year_label, st.class_col, class1_name, class2_name, class3_name, class4_name, class5_name, class6_name from students st, school_classes sc, schools s, school_admins sa where st.school_classes_id = sc.school_classes_id and s.school_id = sc.school_id and s.school_id = sa.school_id and s.school_id = ? and s.contract_id = ? and st.active = ".ACTIVE." and sa.user_id = ? and pupil_id like '%".$this->db->escape_str($data['pupil_id'])."%' and fname like '%".$this->db->escape_str($data['fname'])."%' and mname like '%".$this->db->escape_str($data['mname'])."%' and lname like '%".$this->db->escape_str($data['lname'])."%'";
		else
			$qry_str = "SELECT students_id, fname, mname, lname, pupil_id, fsm, s.school_name, adult, sc.year_label, st.class_col, class1_name, class2_name, class3_name, class4_name, class5_name, class6_name from students st, school_classes sc, schools s, school_admins sa where st.school_classes_id = sc.school_classes_id and s.school_id = sc.school_id and s.school_id = sa.school_id and s.school_id = ? and s.contract_id = ? and st.active = ".ACTIVE." and sa.user_id = ? and pupil_id like '%".$this->db->escape_str($data['pupil_id'])."%' and fname like '%".$this->db->escape_str($data['fname'])."%' and mname like '%".$this->db->escape_str($data['mname'])."%' and lname like '%".$this->db->escape_str($data['lname'])."%' and ". $pupil_val;
		
		$query = $this->db->query($qry_str, array($data['school_id'],$data['contract_id'],$data['user_id']));
		$res_pupils = $query->result();
		
		return $res_pupils;
	}

	public function get_pupils_order_menu($data)
	{
		/* Get the school close related details */
		$qry = "SELECT DATE_FORMAT(s.closed_till,'%e/%m/%Y') as closed_till, s.closed_reason, (SELECT CONCAT_WS(' ',first_name,last_name) from users WHERE user_id = s.closed_by) as closed_by, CASE WHEN s.closed_till > DATE(NOW()) THEN 1 ELSE 0 END AS closed_status FROM schools s where s.school_id = ?";
		$query = $this->db->query($qry, array($data['school_id']));
		$school_details = $query->result();
		
		//First get the active menu and active menu id
		$this->db->select("con_cater_menu_settings_id AS menu_id , menu_cycles, menu_start_date", FALSE);
		$this->db->from('con_cater_menu_settings');
		$this->db->where('contract_id',$data['contract_id']);
		$this->db->where("menu_start_date <= '". $data['current_date']."'");
		$this->db->where('menu_status = 1');
		$this->db->order_by("menu_start_date", "DESC");
		$this->db->limit(1);
		$query = $this->db->get();
		$res_menu_active = $query->result();
		//echo $this->db->last_query();

		$active_menu_id = $res_menu_active[0]->menu_id;
		$menu_cycles = $res_menu_active[0]->menu_cycles;
		$menu_start_date= $res_menu_active[0]->menu_start_date;

		//Get active week cycle in the given menu
		$week_cycle = 1;
		while(true){
			$pDate = date('Y-m-d',strtotime($menu_start_date.' + 0 week'));
			$nDate = date('Y-m-d',strtotime($menu_start_date.' + 1 week'));
			//echo $pDate. " " . $nDate ."<br/>";
			if($data['current_date'] >= $pDate && $data['current_date'] <$nDate)
			break;
			$menu_start_date = $nDate;
			$week_cycle = ($week_cycle==$menu_cycles)? 1: ($week_cycle+1);
		}

		//Get whether the previous week data exists or not based on the order_items and min of menu start dates.
		$pre_query_str = "SELECT CASE WHEN Min(start_date) < ? THEN 1 ELSE 0 END AS isPreExists
					FROM (
						SELECT min(menu_start_date) start_date FROM con_cater_menu_settings WHERE contract_id = ?
						UNION ALL
						SELECT min(fulfilment_date) start_date FROM order_items WHERE school_id = ? AND order_type='".MEAL_ORDER."')a";


		$pre_query = $this->db->query($pre_query_str, array($data['current_week'],$data['contract_id'],$data['school_id']));
		$pre_query_data = $pre_query->result();


		//Get the contract related information like vat and tminus and validate wheteher the order is editable or not. editable 0 means no, editable 1 means yes
		$current_date_time = $data['current_date'] ." 12:00:00";
		$this->db->select("vat, tminus,adult_invoice, CASE WHEN DATE_SUB('".$current_date_time."', INTERVAL tminus HOUR) < NOW() THEN 'no' ELSE 'yes' END AS editable,date_format( '".$this->db->escape_str($data['current_week'])."', '%D %M %Y' ) mon, date_format(DATE_ADD( '".$this->db->escape_str($data['current_week'])."', INTERVAL 1 DAY ) , '%D %M %Y' ) tue, date_format(DATE_ADD( '".$this->db->escape_str($data['current_week'])."', INTERVAL 2 DAY ) , '%D %M %Y' ) wed,date_format(DATE_ADD( '".$this->db->escape_str($data['current_week'])."', INTERVAL 3 DAY ) , '%D %M %Y' ) thu,date_format(DATE_ADD( '".$this->db->escape_str($data['current_week'])."', INTERVAL 4 DAY ) , '%D %M %Y' ) fri", false);
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_contracts = $query->result();
		//echo $this->db->last_query();

// 		$this->db->select('students_id,pupil_id,fname,mname,lname,st.status AS student_status,sc.year_status,st.class_col AS class_name,class1_status,class2_status,class3_status,class4_status,class5_status,class6_status,s.status AS school_status, (st.cash_balance+st.card_balance) AS balance, st.fsm, st.adult');
// 		$this->db->from('students st');
// 		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
// 		$this->db->join('schools s', 's.school_id = sc.school_id');
// 		$this->db->where('user_id', $data['user_id']);
// 		$this->db->where('s.school_id', $data['school_id']);
// 		$this->db->where('active', ACTIVE);
// 		$query = $this->db->get();
// 		$res_pupils = $query->result();
		
		
		$student_query_str = "SELECT students_id, pupil_id, fname, mname, lname, st.status AS student_status, sc.year_status, st.class_col AS class_name, class1_status, class2_status, class3_status, class4_status, class5_status, class6_status, s.status AS school_status, (st.cash_balance+st.card_balance) AS balance, st.fsm, st.adult,
		(select 1 from order_items WHERE pupil_id = st.pupil_id and fulfilment_date = ? AND order_type=".MEAL_ORDER." AND order_status = ".ORDER_STATUS_NEW." LIMIT 1) as order_exists
		FROM (students st)
		JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id
		JOIN schools s ON s.school_id = sc.school_id
		WHERE user_id =  ? AND s.school_id =  ? AND active = ".ACTIVE;
		
		$student_query = $this->db->query($student_query_str, array($data['current_date'],$data['user_id'],$data['school_id']));
		$res_pupils = $student_query->result();
		
		if(isset($data['temp_pupil']))
		{
			$temp_pupil_val = "";
			$res_temp_pupils = array();
			foreach($data['temp_pupil'] as $key => $value)
			{
				if (strpos($temp_pupil_val,'pupil_id') !== false) 
				{
					$temp_pupil_val = $temp_pupil_val . "OR pupil_id = '". $value ."'";
				}
				else
				{
					$temp_pupil_val = $temp_pupil_val . "pupil_id = '". $value ."'";
				}
			}
			
			$temp_stu_query_str = "SELECT students_id, pupil_id, fname, mname, lname, st.status AS student_status, sc.year_status, st.class_col AS class_name, class1_status, class2_status, class3_status, class4_status, class5_status, class6_status, s.status AS school_status, (st.cash_balance+st.card_balance) AS balance, st.fsm, st.adult,
			(select 1 from order_items WHERE pupil_id = st.pupil_id and fulfilment_date = ? AND order_type=".MEAL_ORDER." AND order_status = ".ORDER_STATUS_NEW." LIMIT 1) as order_exists
			FROM (students st)
			JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id
			JOIN schools s ON s.school_id = sc.school_id WHERE ". $temp_pupil_val;
			
			$temp_student_query = $this->db->query($temp_stu_query_str, array($data['current_date']));
			$res_temp_pupils = $temp_student_query->result();
		}

		$res_pupils_order_menu->active_menu = $active_menu_id;
		$res_pupils_order_menu->week_cycle = $week_cycle;
		$res_pupils_order_menu->vat = $res_contracts[0]->vat;
		$res_pupils_order_menu->tminus = $res_contracts[0]->tminus;
		$res_pupils_order_menu->invoice = $res_contracts[0]->adult_invoice;
		$res_pupils_order_menu->iseditable = $res_contracts[0]->editable;
		$res_pupils_order_menu->mon = $res_contracts[0]->mon;
		$res_pupils_order_menu->tue = $res_contracts[0]->tue;
		$res_pupils_order_menu->wed = $res_contracts[0]->wed;
		$res_pupils_order_menu->thu = $res_contracts[0]->thu;
		$res_pupils_order_menu->fri = $res_contracts[0]->fri;
		$res_pupils_order_menu->pre_exits = $pre_query_data[0]->isPreExists;
		$res_pupils_order_menu->res_pupils = $res_pupils;
		$res_pupils_order_menu->close_details = $school_details[0];
		if(isset($res_temp_pupils))
			$res_pupils_order_menu->res_temp_pupils = $res_temp_pupils;
		//print_r($res_pupils_order_menu);

		return $res_pupils_order_menu;
	}
	public function get_pupils_order_menu_details($data){
				
		//Need to get the existing order details for this student.
		$qry = "SELECT sc_cater_menu_settings_id AS smid, order_item_id AS oid, order_id , meal_type AS m, option_details AS d, option_cost AS c, invoice_school AS inv, order_edited AS oe, (SELECT case when user_msg != '' then user_msg else system_msg end AS oem from batch_cancel where batch_cancel_id = o.batch_cancel_id) as oem from order_items o where pupil_id = ? and fulfilment_date = ? and order_type = ". MEAL_ORDER ." and order_status = ". ORDER_STATUS_NEW ." order by meal_type ASC, option_sequence ASC";
		$query = $this->db->query($qry, array($data['pupil_id'],$data['current_date']));
		$res_order_details = $query->result();
		
		/*$this->db->select('sc_cater_menu_settings_id AS smid, order_item_id AS oid, order_id , meal_type AS m, option_details AS d, option_cost AS c, invoice_school AS inv, order_edited AS oe');
		$this->db->from('order_items');
		$this->db->WHERE('pupil_id', $data['pupil_id']);
		$this->db->WHERE('fulfilment_date', $data['current_date']);
		$this->db->WHERE('order_type', MEAL_ORDER);
		$this->db->WHERE('order_status', ORDER_STATUS_NEW);
		$this->db->order_by("meal_type", "ASC");
		$this->db->order_by("option_sequence", "ASC");
		$query = $this->db->get();
		$res_order_details= $query->result();*/

		//Here we need to the school specific menu details/Order details.
		$this->db->select('sc.sc_cater_menu_settings_id AS mid, meal_type AS m, option_details AS d , option_cost AS c');
		$this->db->from('sc_cater_menu_settings sc');
		$this->db->join('con_cater_menu_details cm', 'cm.con_cater_menu_details_id = sc.con_cater_menu_details_id');
		$this->db->where('cm.con_cater_menu_settings_id',$data['menu_id']);
		$this->db->where('sc.school_id',$data['school_id']);
		$this->db->where('sc.status',ACTIVE);
		$this->db->where('cm.week_cycle',$data['week_cycle']);
		$this->db->where('cm.week_day',$data['week_day']);
		$this->db->where('cm.option_status',ACTIVE);
		$this->db->order_by("meal_type", "ASC");
		$this->db->order_by("option_sequence", "ASC");
		$query = $this->db->get();
		$res_menu_details= $query->result();
		//echo $this->db->last_query();

		$res_menu_order_details->menu = $res_menu_details;
		$res_menu_order_details->order = $res_order_details;
		
		return $res_menu_order_details;

	}
	/* Search Pupils for Card & Cash Payment */
	public function search_pupils($data)
	{
		$this->db->select('students_id,fname,mname,lname,pupil_id,fsm,cash_balance,s.school_name,s.school_id,adult,sc.year_label,st.class_col,class1_name,class2_name,class3_name,class4_name,class5_name,class6_name');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('school_admins sa', 's.school_id = sa.school_id');
		$this->db->where('s.contract_id', $data['contract_id']);
		//$this->db->where('st.status', ACTIVE);
		$this->db->where('st.active', ACTIVE);
		$this->db->where('sa.user_id', $data['user_id']);
		$this->db->like('pupil_id', $data['pupil_id']);
		$this->db->like('fname', $data['fname']);
		$this->db->like('mname', $data['mname']);
		$this->db->like('lname', $data['lname']);
		$query = $this->db->get();
		$res_pupils = $query->result();
		return $res_pupils;
	}

	/* To save cash refund */
	public function save_cash_refund($data)
	{
		$refund_arr = $data['refund_data'];
		$cash_ref_query_status = FALSE;
		foreach($refund_arr as $key => $value)
		{
			$cash_res = $this->get_students_cash_balance($value['pupil_id']);

			if(($cash_res[0]->cash_balance) > 0)
			{
				$refund_data = array(
					'pupil_id' => $value['pupil_id'],
					'payment_id' => $data['payment_id'],
					'amount' => $cash_res[0]->cash_balance,
					'transaction_fee' => 0.00,
					'card_cash' => $data['trans_type'],
					'pay_refund' => $data['trans_mode'],
					'status' => ACTIVE,
					'cuser_id' => $data['user_id']);

				$this->db->set('cdate', 'NOW()', FALSE);
				$cash_ref_query_status = $this->db->insert('payment_items', $refund_data);

				/* Update the cash balance for the students id table */
				if($cash_ref_query_status)
				{
					$this->update_cash_balance($value['pupil_id'],$value['cash_balance'],$data['trans_mode'],$data['trans_type']);
				}
			}
		}
		return $cash_ref_query_status;
	}

	/* To Get the Students Cash Balance */
	public function get_students_cash_balance($pupil_id)
	{
		$this->db->select('cash_balance');
		$this->db->from('students');
		$this->db->where('pupil_id', $pupil_id);
		//$this->db->where('status', ACTIVE);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$balance_res = $query->result();
		return $balance_res;
	}

	/* To Get the Updated Payment History */
	public function get_updated_payment_items($payment_id)
	{
		$this->db->select('fname, mname, lname, fsm, s.school_name, adult, sc.year_label, st.class_col, class1_name, class1_name, class2_name, class3_name, class4_name, class5_name, class6_name, p.payment_id, p.pupil_id, p.amount, p.transaction_fee, u.username');
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as cdate", FALSE);
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('payment_items p', 'st.pupil_id = p.pupil_id');
		$this->db->join('users u', 'u.user_id = p.cuser_id');
		//$this->db->where('st.status', ACTIVE);
		$this->db->where('st.active', ACTIVE);
		$this->db->where('p.payment_id', $payment_id);
		$this->db->where('p.status', ACTIVE);
		$query = $this->db->get();
		$res_pupils = $query->result();
			
		return $res_pupils;
	}
	
	public function get_yp_error_code($yp_code)
	{
		$this->db->select('description');
		$this->db->from('yp_codes');
		$this->db->where('code', $yp_code);
		$query = $this->db->get();
		$yp_res = $query->result();
		if($yp_res)
			return $yp_res[0]->description;
		else
			return '';
	}

	/* To make cash payment */
	public function make_cash_payment($data){
		foreach ($data['pupils_res'] as $pupils_data)
		{
			$insert_data = array(
				'pupil_id' => $pupils_data['pupil_id'],
				'amount' => $pupils_data['amount'],
				'payment_id' => $data['payment_id'],
				'card_cash' => $data['amount_type'],
				'pay_refund' => $data['payment'],
				'status' => ACTIVE,
				'cuser_id' => $data['cuser_id'],
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('payment_items', $insert_data);

			/* Update the cash Balance */
			$this->update_cash_balance($pupils_data['pupil_id'],$pupils_data['amount'],$data['payment'],$data['amount_type']);
		}
		return $data['payment_id'];
	}
	
	public function get_full_history($data)
	{
		$total_record = SCHOOL_PAYMENT_HISTORY_NAVIGATION_COUNT;
		if($data['page_no'] == 1)
		{
			$this->db->select('COUNT(1) as count');
			$this->db->from('students st');
			$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
			$this->db->join('schools s', 's.school_id = sc.school_id');
			$this->db->join('payment_items p', 'st.pupil_id= p.pupil_id');
			$this->db->join('school_admins a', 's.school_id = a.school_id');
			if($data['school_id'] != "")
			{
				$this->db->where('s.school_id', $data['school_id']);
			}
			$this->db->where('s.contract_id', $data['contract_id']);
			$this->db->where('a.user_id', $data['user_id']);
			//$this->db->where('st.status', ACTIVE);
			$this->db->where('st.active', ACTIVE);
			$this->db->where('p.card_cash', $data['trans_mode']);
			$this->db->order_by('p.cdate', 'desc');

			$query = $this->db->get();
			$res_records = $query->result();
			
			$res_history_info->total_history_records = $res_records[0]->count;
			
			$start = 0;
			$end = $total_record;
		}
		else
		{
			$end = $data['page_no'] * $total_record;
			$start = $end - $total_record;
		}
		
		$this->db->select('fname, mname, lname, fsm, s.school_name, adult, p.payment_id, p.pupil_id, p.amount, p.pay_refund, u.username');
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as cdate", FALSE);
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('payment_items p', 'st.pupil_id= p.pupil_id');
		$this->db->join('school_admins a', 's.school_id = a.school_id');
		$this->db->join('users u', 'u.user_id = p.cuser_id');
		if($data['school_id'] != "")
		{
			$this->db->where('s.school_id', $data['school_id']);
		}
		$this->db->where('s.contract_id', $data['contract_id']);
		$this->db->where('a.user_id', $data['user_id']);
		//$this->db->where('st.status', ACTIVE);
		$this->db->where('st.active', ACTIVE);
		$this->db->where('p.card_cash', $data['trans_mode']);
		$this->db->order_by('p.cdate', 'desc');
		$this->db->limit($total_record, $start);

		$query = $this->db->get();
		$res_history_info->history_records = $query->result();
		
		return $res_history_info;
	}

	public function get_pupils_topay($data)
	{
		$this->db->select('students_id,fname,mname,lname,pupil_id,fsm,card_balance,s.school_name,adult,st.class_col,class1_name,class1_name,class2_name,class3_name,class4_name,class5_name,class6_name,c.min_card_pay,c.trans_fee_status,CASE WHEN c.trans_fee_status = 0 THEN dc_fee = 0 ELSE dc_fee END AS dc_fee,CASE WHEN c.trans_fee_status = 0 THEN cc_fee = 0 ELSE cc_fee END AS cc_fee', FALSE);
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('contracts c', 's.contract_id = c.contract_id');
		$this->db->where('user_id', $data['user_id']);
		$this->db->where('st.active', ACTIVE);
		//$this->db->where('st.status', ACTIVE);
		$query = $this->db->get();
		$res_pupils = $query->result();
		return $res_pupils;
	}

	public function get_trans_fee($contract_id)
	{
		$this->db->select('dc_fee,cc_fee,trans_fee_status');
		$this->db->from('contracts');
		$this->db->where('contract_id', $contract_id);
		$query = $this->db->get();
		$res_trans_fee = $query->result();
		return $res_trans_fee[0];
	}

	/*public function make_card_payment($data){
		foreach ($data['pupils_res'] as $pupils_data)
		{
			$insert_data = array(
				'pupil_id' => $pupils_data['pupil_id'],
				'amount' => $pupils_data['amount'],
				'payment_id' => $data['payment_id'],
				'card_cash' => $data['amount_type'],
				'transaction_fee' => $pupils_data['transaction_fee'],
				'pay_refund' => $data['payment'],
				'status' => ACTIVE,
				'cuser_id' => $data['cuser_id'],
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('payment_items', $insert_data);

			// Update the cash balance for the students id table
			$this->update_cash_balance($pupils_data['pupil_id'],$pupils_data['amount'],$data['payment'],$data['amount_type']);
		}
		return $data['payment_id'];
	}*/

	/* Update the Cash Balance using students id */
	public function update_cash_balance($pupil_id,$amount,$update_type,$trans_mode)
	{
		$this->db->select('cash_balance,card_balance');
		$this->db->from('students');
		$this->db->where('pupil_id',$pupil_id);
		//$this->db->where('status', ACTIVE);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$res_balance = $query->result();

		/* Check the transaction Mode */
		if($trans_mode == CARD)
		{
			if($update_type == PAYMENT)
			$updated_card_balance = $res_balance[0]->card_balance + $amount;
			else
			$updated_card_balance = $res_balance[0]->card_balance - $amount;

			//$qry = "UPDATE students SET card_balance = ". $updated_card_balance ." where pupil_id = '". $pupil_id ."' and status = ". ACTIVE ." and active = ". ACTIVE;
			$qry = "UPDATE students SET card_balance = ". $updated_card_balance ." where pupil_id = '". $pupil_id ."' and active = ". ACTIVE;
		}
		else
		{
			if($update_type == PAYMENT)
			$updated_cash_balance = $res_balance[0]->cash_balance + $amount;
			else
			$updated_cash_balance = $res_balance[0]->cash_balance - $amount;

			//$qry = "UPDATE students SET cash_balance = ". $updated_cash_balance ." where pupil_id = '". $pupil_id ."' and status = ". ACTIVE ." and active = ". ACTIVE;
			$qry = "UPDATE students SET cash_balance = ". $updated_cash_balance ." where pupil_id = '". $pupil_id ."' and active = ". ACTIVE;
		}
		$this->db->query($qry);
	}
	
	/* To Get the Card Payment History */
	public function get_card_payment_history($data)
	{
		$total_record = PARENT_PAYMENT_HISTORY_NAVIGATION_COUNT;
		
		if($data['page_no'] == 1)
		{
			$this->db->select('COUNT(1) as count');
			//$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as trans_date", FALSE);
			$this->db->from('students st');
			//$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
			//$this->db->join('schools s', 's.school_id = sc.school_id');
			//$this->db->join('school_admins sa', 's.school_id=sa.school_id');
			$this->db->join('payment_items p', 'st.pupil_id = p.pupil_id');
			//$this->db->join('yp_codes yp', 'yp.code = p.yp_code', 'left');		// commented for performance
			$this->db->join('users u', 'u.user_id = p.cuser_id');
			//$this->db->where('st.status', ACTIVE);
			$this->db->where('st.active', ACTIVE);
			$this->db->where('st.user_id', $data['user_id']);
			//$this->db->order_by('p.cdate', 'desc');					// commented for performance

			$query = $this->db->get();

			$res_records = $query->result();
			
			$res_history_info->total_history_records = $res_records[0]->count;
			
			/* Count for start & end records */
			$start = 0;
			$end = $total_record;
		}
		else
		{
			$end = $data['page_no'] * $total_record;
			$start = $end - $total_record;
		}
		
		$this->db->select('fname, mname, lname, fsm, s.school_name, adult, p.payment_id, p.amount, p.pupil_id, p.pgtr_id, p.transaction_fee, p.pay_refund, p.yp_code, case when yp.description is NULL then "'. TRANSACTION_INITIATED .'" else yp.description end as description, u.username', FALSE);
		$this->db->select("DATE_FORMAT(p.cdate, '%d/%m/%Y %H:%i') as trans_date", FALSE);
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		//$this->db->join('school_admins sa', 's.school_id=sa.school_id');
		$this->db->join('payment_items p', 'st.pupil_id = p.pupil_id');
		$this->db->join('yp_codes yp', 'yp.code = p.yp_code', 'left');
		$this->db->join('users u', 'u.user_id = p.cuser_id');
		//$this->db->where('st.status', ACTIVE);
		$this->db->where('st.active', ACTIVE);
		$this->db->where('st.user_id', $data['user_id']);
		$this->db->order_by('p.cdate', 'desc');
		$this->db->limit($total_record, $start);

		$query = $this->db->get();
		
		$res_history_info->history_records = $query->result();
		
		return $res_history_info;
	}

	/* Save the order information */
	public function save_order_items($data){
		//First Identify the type of order and calcualte the (card_net, card_vat, cash_net, cash_vat,fsm_net, fsm_vat, a_card_net, a_card_vat, a_cash_net, a_card_net, a_sa_net, a_sa_vat)

		//Get the student details..
		$this->db->select('fsm, adult, cash_balance, card_balance',false);
		$this->db->from('students');
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where('status', ACTIVE);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$res_student = $query->result();

		$this->db->select('vat');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_contract = $query->result();


		//Get the order_id and sc_cater_menu_settings_id from order_items for concurrent window scenarios.
		$this->db->select('sc_cater_menu_settings_id AS scmid, order_id',false);
		$this->db->from('order_items');
		$this->db->where('fulfilment_date', $data['current_date']);
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where('order_status', ORDER_STATUS_NEW);
		$order_query = $this->db->get();
		$res_order_items = $order_query->result();

		if(count($res_order_items) >0 ){
			$str=",";
			$data['order_id'] =$res_order_items[0]->order_id;
			foreach($res_order_items as $obj)
			$str = $str.$obj->scmid.",";

			$data['myInOrdids'] = $str;
		}
		
		//Get the VAT percentage
		$cash_balance = $res_student[0]->cash_balance;
		$card_balance = $res_student[0]->card_balance;
		$va_per = $res_contract[0]->vat;
		$order_id = ($data['order_id']== "") ? create_transaction_id($data['contract_id'],MEAL_ORDER, ORDER_ITEMS) : $data['order_id'];


		//If user is an adult then cancel the order and insert new order
		if($res_student[0]->adult == 1 && $data['order_id']!= ""){
			//Fetch the old invoice value

			$this->db->select('order_item_id, invoice_school',false);
			$this->db->from('order_items');
			$this->db->where('order_id', $data['order_id']);
			$this->db->where('order_status', ORDER_STATUS_NEW);
			$query = $this->db->get();
			$res_order = $query->result();

			//Check whether the invoice to school is changed or not. If changed cancel the order and re-insert again.
			if(($data['isInvoice'] == 'true' && $res_order[0]->invoice_school == 0) ||($data['isInvoice'] == 'false' && $res_order[0]->invoice_school == 1) ){
				$this->cancel_order_items($data);
				$data['myInOrdids'] =",,";
				$order_id = create_transaction_id($data['contract_id'],MEAL_ORDER, ORDER_ITEMS);
					
				$this->db->select('cash_balance, card_balance',false);
				$this->db->from('students');
				$this->db->where('pupil_id', $data['pupil_id']);
				$this->db->where('active', ACTIVE);
				$query = $this->db->get();
				$res_student_inv = $query->result();
				$cash_balance = $res_student_inv[0]->cash_balance;
				$card_balance = $res_student_inv[0]->card_balance;
			}

		}

		$order_seq = 0;
		//loop through the selected order items.
		foreach ($data['order_details'] as $order_data)
		{

			//To check whether the id is already inserted or not in the inital records.If not insert the record.
			if(!empty($order_data) && strpos($data['myInOrdids'],",".$order_data['scmid'].",") === false){
				$card_net=0;$card_vat =0;$cash_net =0; $cash_vat=0;$fsm_net=0;$fsm_vat=0;$a_card_net=0;$a_card_vat=0;$a_cash_net=0;$a_cash_vat=0;$a_sa_net=0;$a_sa_vat=0;$a_hos_net=0;$a_hos_vat=0;
				$school_id = 0;$meal_type=0; $option_details="";$option_cost=0;$invoice_school=0;
				//Find out the option details from the given school menu settings id

				$this->db->select('school_id, meal_type, option_details, option_cost, option_sequence');
				$this->db->from('sc_cater_menu_settings sc');
				$this->db->join('con_cater_menu_details cm','sc.con_cater_menu_details_id = cm.con_cater_menu_details_id');
				$this->db->where('sc.sc_cater_menu_settings_id',$order_data['scmid']);
				$this->db->where('sc.status',ACTIVE);
				$this->db->where('cm.option_status',ACTIVE);
				$query = $this->db->get();
				$res_school_settings = $query->result();

				$school_id = $res_school_settings[0]->school_id;
				$meal_type = $res_school_settings[0]->meal_type;
				$option_details = $res_school_settings[0]->option_details;
				$option_cost = $res_school_settings[0]->option_cost;
				$option_vat =round(($option_cost * $va_per)/100,2);
				$option_sequence = $res_school_settings[0]->option_sequence;

				// For the main meal and FSM then charge it to FSM account
				if($meal_type == MEAL_TYPE_MAINMEAL && $res_student[0]->fsm == 1){
					$fsm_net=$option_cost;
					$fsm_vat=$option_vat;
				}
				else if ($data['isInvoice'] == 'true' && $res_student[0]->adult == 1){ // If adult and invoice to school is checked'
					$a_sa_net=$option_cost;
					$a_sa_vat=$option_vat;
					$invoice_school = 1;
				}
				else if ($res_student[0]->adult == 1) { //If adult and invoice to school is not checked.
					$net_amt = $option_cost + $option_vat;
					if($cash_balance < $net_amt){  // if cash balance is less then net amount
						$a_cash_net = round(($cash_balance*100) /($va_per + 100),2);
						$a_cash_vat =  $cash_balance - $a_cash_net;

						$card_amt = $net_amt - $cash_balance;
						$a_card_net=round(($card_amt*100) /($va_per + 100),2);
						$a_card_vat=$card_amt-$a_card_net;

						//deduct the money from available balance.
						$cash_balance =0;
						$card_balance = $card_balance - $card_amt;

					} else {
						$a_cash_net=$option_cost;
						$a_cash_vat=$option_vat;
						//deduct the money from available balance.
						$cash_balance =$cash_balance - $net_amt;
					}
				} else {  //If parent places the order then no VAT will be deducted from the cash/card balance but will be added in the cash_vat/card_vat;
					if($cash_balance < $option_cost){ //If cash balance is less then total cost excluding vat.
						$cash_net = $cash_balance;
						$cash_vat = round(($cash_balance * $va_per)/100,2);

						$card_net = $option_cost - $cash_net;
						$card_vat = round(($card_net * $va_per)/100,2);

						//deduct the money from available balance.
						$cash_balance=0;
						$card_balance = $card_balance - $card_net;
					}else {
						$cash_net = $option_cost;
						$cash_vat = $option_vat;
						//deduct the money from available balance.
						$cash_balance=$cash_balance - $cash_net;
					}
				}

				//Insert the order_items select some values from cater menu settings
				$order_query_data[$order_seq++] = array(
				'school_id' => $school_id,
				'sc_cater_menu_settings_id' => $order_data['scmid'],
				'pupil_id' => $data['pupil_id'],
				'fsm'=> $res_student[0]->fsm,
				'adult'=> $res_student[0]->adult,
				'status'=> ACTIVE,
				'order_id' =>$order_id,
				'order_type' => MEAL_ORDER,
				'fulfilment_date' => $data['current_date'],
				'order_userid' => $data['user_id'],
				'order_status' => ORDER_STATUS_NEW,
				'meal_type' => $meal_type,
				'option_details' => $option_details,
				'option_cost' => $option_cost,
				'option_vat' =>$option_vat,
				'option_sequence'=>$option_sequence,
				'card_net' =>$card_net,
				'card_vat' =>$card_vat,
				'cash_net' =>$cash_net,
				'cash_vat' =>$cash_vat,
				'fsm_net' =>$fsm_net,
				'fsm_vat' =>$fsm_vat,
				'a_card_net' =>$a_card_net,
				'a_card_vat' =>$a_card_vat,
				'a_cash_net' => $a_cash_net,
				'a_cash_vat' => $a_cash_vat,
				'a_sa_net' => $a_sa_net,
				'a_sa_vat' => $a_sa_vat,
				'invoice_school' =>$invoice_school,
				'cuser_id' => $data['user_id']
				);
				// 		$this->db->set('order_date', 'NOW()', FALSE);
				// 		$this->db->set('cdate', 'NOW()', FALSE);
				// 		$order_query_res = $this->db->insert('order_items', $order_query_data);
				//echo $this->db->last_query()."<br >";
			}
			else {
				$data['myInOrdids'] = str_replace(",".$order_data['scmid'].",",",",$data['myInOrdids']);
			}
		}

		// 	echo "<pre>";
		// 	print_r($order_query_data);
		
		$order_items_str = "";
		
		if($data['myInOrdids'] !=",,"){
			$myInOrdIds = explode(",",$data['myInOrdids']);
			for($i=1; $i<count($myInOrdIds); $i++){
				if($myInOrdIds[$i] != ""){
					//Get the details of the order
					$this->db->select('order_item_id AS oid, meal_type AS m, option_cost AS c, option_vat AS v, card_net, card_vat, cash_net, cash_vat, a_card_net, a_card_vat, a_cash_net, a_cash_vat');
					$this->db->from('order_items');
					$this->db->WHERE('order_id', $order_id);
					$this->db->where('sc_cater_menu_settings_id',$myInOrdIds[$i]);
					$this->db->WHERE('pupil_id', $data['pupil_id']);
					$this->db->WHERE('fulfilment_date', $data['current_date']);
					$this->db->WHERE('order_type', MEAL_ORDER);
					$this->db->WHERE('order_status', ORDER_STATUS_NEW);
					$query = $this->db->get();
					$res_order_details= $query->result();


					//Calculate the return balance
					if($res_student[0]->adult == 1){
						$cash_balance = $cash_balance + $res_order_details[0]->a_cash_net + $res_order_details[0]->a_cash_vat;
						$card_balance = $card_balance + $res_order_details[0]->a_card_net + $res_order_details[0]->a_card_vat;
					} else {
						$cash_balance = $cash_balance + $res_order_details[0]->cash_net;
						$card_balance = $card_balance + $res_order_details[0]->card_net;
					}

					$order_items_str = $order_items_str.$res_order_details[0]->oid.",";

					// 			//Update the order row to cancelled.
					// 			$order_query_data = array(
					// 					'order_status' => ORDER_STATUS_CANCEL,
					// 					'muser_id' =>$data['user_id']
					// 			);
					// 			$this->db->set('mdate','NOW()', FALSE);
					// 			$this->db->where('order_item_id',$res_order_details[0]->oid);
					// 			$this->db->update('order_items',$order_query_data);
					// 			echo $this->db->last_query();
				}
			}
		}
		if($order_items_str !="") {
			$order_items_str = substr($order_items_str, 0, -1);
		}

		//For two window scenario... insert and update data after checking the balance.
		if($cash_balance >=0 && $card_balance >= 0){
			for($i=0; $i<count($order_query_data); $i++){
				$this->db->set('order_date', 'NOW()', FALSE);
				$this->db->set('cdate', 'NOW()', FALSE);
				$order_query_res = $this->db->insert('order_items', $order_query_data[$i]);
			}

			//$this->db->where('order_item_id',$res_order_details[0]->oid);
			//$this->db->delete('order_items');
			//Now we need to update the order_items to cancel the removed items.
			//Instead of updating the row, we will be deleting the rows which are removed from UI.
			if($order_items_str !="") {
				$delete_query_str = "DELETE  FROM order_items
							WHERE order_item_id IN (".$order_items_str.")"; 
				$delete_query = $this->db->query($delete_query_str);
			}

			//If the cash balance or card balance is changed then update them student
			if($res_student[0]->cash_balance != $cash_balance || $res_student[0]->card_balance != $card_balance)
			{
					
				$st_data = array(
						'cash_balance' => $cash_balance,
						'card_balance' => $card_balance,
				);
				$this->db->where('pupil_id',$data['pupil_id']);
				$this->db->where('active',ACTIVE);
				$this->db->update('students',$st_data);
					
			}
			$res_order_menu->balance = number_format($cash_balance + $card_balance,2);
			$res_order_menu->order_id= $order_id;
			$res_order_menu->error = FALSE;
		}
		else {
			$res_order_menu->error = TRUE;
			$res_order_menu->error_msg = "Order rejected because of insufficient funds.";
		}
		return $res_order_menu;
	}
	/* cancel the order information */
	public function cancel_order_items($data){

		//Get the student details..
		$this->db->select('fsm, adult, cash_balance, card_balance',false);
		$this->db->from('students');
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$res_student = $query->result();

		$cash_balance = 0;
		$card_balance = 0;

		//Get the details of the order
		$this->db->select('order_item_id AS oid, meal_type AS m, option_cost AS c, option_vat AS v, card_net, card_vat, cash_net, cash_vat, a_card_net, a_card_vat, a_cash_net, a_cash_vat,fsm, adult, status');
		$this->db->from('order_items');
		$this->db->WHERE('order_id',  $data['order_id']);
		$this->db->WHERE('pupil_id', $data['pupil_id']);
		$this->db->WHERE('fulfilment_date', $data['current_date']);
		$this->db->WHERE('order_type', MEAL_ORDER);
		$this->db->WHERE('order_status', ORDER_STATUS_NEW);
		$query = $this->db->get();
		$res_order_details= $query->result();
		//echo $this->db->last_query();

		foreach($res_order_details as $obj){
			//Calculate the return balance
			if($obj->adult == 1){
				$cash_balance = $cash_balance + $obj->a_cash_net + $obj->a_cash_vat;
				$card_balance = $card_balance + $obj->a_card_net + $obj->a_card_vat;
			} else {
				$cash_balance = $cash_balance + $obj->cash_net;
				$card_balance = $card_balance + $obj->card_net;
			}
		}

		//Cancel the order
		$order_query_data = array(
				'order_status' => ORDER_STATUS_CANCEL,
				'muser_id' =>$data['user_id']
		);
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->WHERE('order_id',  $data['order_id']);
		$this->db->WHERE('pupil_id', $data['pupil_id']);
		$this->db->WHERE('fulfilment_date', $data['current_date']);
		$this->db->WHERE('order_type', MEAL_ORDER);
		$this->db->WHERE('order_status', ORDER_STATUS_NEW);
		$this->db->update('order_items',$order_query_data);

		//Update the balance amount...
		$st_data = array(
				'cash_balance' => $res_student[0]->cash_balance + $cash_balance,
				'card_balance' => $res_student[0]->card_balance + $card_balance,
		);
		$this->db->where('pupil_id',$data['pupil_id']);
		$this->db->where('active', ACTIVE);
		$this->db->update('students',$st_data);

		$res_order_menu->balance = number_format($res_student[0]->cash_balance + $cash_balance + $res_student[0]->card_balance + $card_balance,2);
		//$res_order_menu->order_id= $order_id;
		$res_order_menu->order_id= $data['order_id'];
		return $res_order_menu;
	}

	public function get_schools_meal_collection($data)
	{
		// Calculate Fulfilment Date
		$date = date('Y-m-d');
		
		$qry = "SELECT CASE WHEN DATE_SUB('".$date." 12:00:00', INTERVAL tminus HOUR) > NOW() THEN DATE_SUB('".$date."', INTERVAL tminus DAY) ELSE '".$date."' END AS tminus FROM contracts WHERE contract_id = ". $data['contract_id'];

		$query = $this->db->query($qry);
		$deadline_data = $query->result();

		$ful_date = $deadline_data[0]->tminus;

		//$ful_date = "2013-06-27";
		// Fulfilment Date calculation ends
		
		//$qry_str = "SELECT a.school_id, a.school_name from schools a, order_items b where a.school_id = b.school_id and a.contract_id = ". $data['contract_id'] ." group by b.school_id order by a.school_name";
		$qry_str = "SELECT a.school_id, a.school_name from schools a, order_items b, school_admins sa where a.school_id = b.school_id  and a.school_id = sa.school_id and b.order_status = 0 and b.fulfilment_date = '". $ful_date . "' and a.contract_id = ". $data['contract_id'] ." and sa.user_id = ". $data['user_id'] ." group by b.school_id order by a.school_name";
		
		$query = $this->db->query($qry_str);
		$schools_data = $query->result();

		return $schools_data;
	}
	
	public function print_daily_meal_collection($data)
	{
		$qry_str = "Select year_label, year_no, year_status, CASE WHEN class1_status = 1 then class1_name end as class1_name, CASE WHEN class2_status = 1 then class2_name end as class2_name, CASE WHEN class3_status = 1 then class3_name end as class3_name, CASE WHEN class4_status = 1 then class4_name end as class4_name, CASE WHEN class5_status = 1 then class5_name end as class5_name, CASE WHEN class6_status = 1 then class6_name end as class6_name from school_classes where school_id = ". $data['school_id'] ." and year_status = 1";

		$query = $this->db->query($qry_str);
		$schools_data = $query->result();

		foreach($schools_data as $key => $value)
		{
			$class_res = array();
			if($value->class1_name == NULL && $value->class2_name == NULL && $value->class3_name == NULL && $value->class4_name == NULL && $value->class5_name == NULL && $value->class6_name == NULL)
			{
				unset($schools_data[$key]);
			}
			else
			{
				if($value->class1_name == NULL)
				unset($value->class1_name);
				else
				$class_res[] = array('class_key' => 'class1_name', 'class_name' => $value->class1_name);
				if($value->class2_name == NULL)
				unset($value->class2_name);
				else
				$class_res[] = array('class_key' => 'class2_name', 'class_name' => $value->class2_name);
				if($value->class3_name == NULL)
				unset($value->class3_name);
				else
				$class_res[] = array('class_key' => 'class3_name', 'class_name' => $value->class3_name);
				if($value->class4_name == NULL)
				unset($value->class4_name);
				else
				$class_res[] = array('class_key' => 'class4_name', 'class_name' => $value->class4_name);
				if($value->class5_name == NULL)
				unset($value->class5_name);
				else
				$class_res[] = array('class_key' => 'class5_name', 'class_name' => $value->class5_name);
				if($value->class6_name == NULL)
				unset($value->class6_name);
				else
				$class_res[] = array('class_key' => 'class6_name', 'class_name' => $value->class6_name);

				unset($value->class1_name);
				unset($value->class2_name);
				unset($value->class3_name);
				unset($value->class4_name);
				unset($value->class5_name);
				unset($value->class6_name);

				$schools_data[$key]->class_res = $class_res;
			}
		}

		foreach($schools_data as $key => $value)
		{
			$new_schools_data[] = $value;
		}

		$result_array[]->school_res = $new_schools_data;
		
		$ful_date = $data['fulfilment_date'];
		$fulfilment_date_format = date("F j, Y", strtotime($ful_date));
		$result_array[]->fulfilment_date = $ful_date;

		/* Fulfilment Date calculation ends */;
		
		if(count($new_schools_data) > 0)
		{
			foreach($new_schools_data as $key => $value)
			{
				foreach($value->class_res as $k => $v)
				{
					$data_arr = array('school_id' => $data['school_id'], 'year_no' => $value->year_no, 'class_key' => $v['class_key'], 'class_name' => $v['class_name'], 'contract_id' => $data['contract_id'], 'fulfilment_date' => $ful_date);
					
					$students_res[] = $this->print_daily_meal_collection_students($data_arr);
				}
			}
			$result_array[]->student_res = $students_res;
		}
		$result_array[]->fulfilment_date_format = $fulfilment_date_format;
	
		return $result_array;
	}
	
	public function print_daily_meal_collection_students($data)
	{
		$fuldate_time = date('Y-m-d', strtotime($data['fulfilment_date']));
		
		/* To check the tminus deadline for previous day and current day */
		/*$meal_status = 0;
		if($fuldate_time == date('Y-m-d', strtotime(' -1 day')))
		{
			$qry = "SELECT CASE WHEN DATE_ADD(DATE_SUB('". $data['fulfilment_date'] ." 12:00:00', INTERVAL tminus HOUR), INTERVAL 1 DAY) < NOW() THEN 0 ELSE 1 END AS deadline FROM contracts WHERE contract_id = ". $data['contract_id'];
			
			$query = $this->db->query($qry);
			$deadline_data = $query->result();

			$meal_status = $deadline_data[0]->deadline;
		}
		else if($fuldate_time == date('Y-m-d'))
		{
			$qry = "SELECT CASE WHEN (DATE_SUB('". $data['fulfilment_date'] ." 12:00:00', INTERVAL tminus HOUR)) > NOW() THEN 0 ELSE 1 END AS deadline FROM contracts WHERE contract_id = ". $data['contract_id'];
			
			$query = $this->db->query($qry);
			$deadline_data = $query->result();

			$meal_status = $deadline_data[0]->deadline;
		}*/
		
		$qry = "SELECT a.order_id from order_items a, students b, school_classes c where a.order_status = 0 and a.pupil_id = b.pupil_id and b.active = ". ACTIVE ." and b.school_classes_id = c.school_classes_id and c.school_id = ". $data['school_id'] ." and c.year_no = ". $data['year_no'] ." and c.". $data['class_key'] ."  = '". $data['class_name'] ."' and a.fulfilment_date = '". $data['fulfilment_date'] ."' and b.class_col = '". $data['class_key'] ."' group by a.order_id";
		
		$order_query = $this->db->query($qry);
		$order_data = $order_query->result();

		$student_res = array();
		foreach($order_data as $key => $value)
		{
			$stu_qry = "SELECT a.pupil_id, b.fname, b.mname, b.lname, b.adult, a.fulfilment_date, a.meal_type, a.option_details, a.order_id, a.collect_status from order_items a, students b where a.pupil_id = b.pupil_id and b.active = ". ACTIVE ." and a.order_id = '". $value->order_id ."'";
			
			$query = $this->db->query($stu_qry);
			$order_query_data = $query->result();

			$stu_obj = new stdClass();
			$stu_obj->year_no = $data['year_no'];
			$stu_obj->class_name = $data['class_key'];
			$stu_obj->pupil_id = $order_query_data[0]->pupil_id;
			$stu_obj->fname = $order_query_data[0]->fname;
			$stu_obj->mname = $order_query_data[0]->mname;
			$stu_obj->lname = $order_query_data[0]->lname;
			$stu_obj->adult = $order_query_data[0]->adult;
			$stu_obj->fulfilment_date = $order_query_data[0]->fulfilment_date;
			$stu_obj->order_id = $order_query_data[0]->order_id;
			$stu_obj->collect_status = $order_query_data[0]->collect_status;

			$main_meal = "";
			$snacks = "";
			foreach($order_query_data as $key => $value)
			{
				switch($value->meal_type)
				{
					case MEAL_TYPE_MAINMEAL:
						if($main_meal != "")
						$main_meal = $main_meal . "; " . $value->option_details;
						else
						$main_meal = $value->option_details;
						break;

					case MEAL_TYPE_SNACK:
						if($snacks != "")
						$snacks = $snacks . "; " . $value->option_details;
						else
						$snacks = $value->option_details;
						break;
				}
			}
			$stu_obj->main_meal = $main_meal;
			$stu_obj->snacks = $snacks;

			if($order_query_data[0]->pupil_id != null)
			$student_res[] = $stu_obj;
		}
		//$result_arr = array();
		//$result_arr[]->student_res = $student_res;
		//$result_arr[]->meal_status = $meal_status;
		return $student_res;
		//return $result_arr;
	}

	public function get_daily_meal_collection_year_class($data)
	{
		/* Get the school close related details */
		$qry = "SELECT DATE_FORMAT(s.closed_till,'%e/%m/%Y') as closed_till, s.closed_reason, (SELECT CONCAT_WS(' ',first_name,last_name) from users WHERE user_id = s.closed_by) as closed_by, CASE WHEN s.closed_till > DATE(NOW()) THEN 1 ELSE 0 END AS closed_status FROM schools s where s.school_id = ?";
		$query = $this->db->query($qry, array($data['school_id']));
		$school_details = $query->result();
		
		$qry_str = "Select year_label, year_no, year_status, CASE WHEN class1_status = 1 then class1_name end as class1_name, CASE WHEN class2_status = 1 then class2_name end as class2_name, CASE WHEN class3_status = 1 then class3_name end as class3_name, CASE WHEN class4_status = 1 then class4_name end as class4_name, CASE WHEN class5_status = 1 then class5_name end as class5_name, CASE WHEN class6_status = 1 then class6_name end as class6_name from school_classes where school_id = ? and year_status = 1";

		$query = $this->db->query($qry_str, array($data['school_id']));
		$schools_data = $query->result();

		foreach($schools_data as $key => $value)
		{
			$class_res = array();
			if($value->class1_name == NULL && $value->class2_name == NULL && $value->class3_name == NULL && $value->class4_name == NULL && $value->class5_name == NULL && $value->class6_name == NULL)
			{
				unset($schools_data[$key]);
			}
			else
			{
				if($value->class1_name == NULL)
				unset($value->class1_name);
				else
				$class_res[] = array('class_key' => 'class1_name', 'class_name' => $value->class1_name);
				if($value->class2_name == NULL)
				unset($value->class2_name);
				else
				$class_res[] = array('class_key' => 'class2_name', 'class_name' => $value->class2_name);
				if($value->class3_name == NULL)
				unset($value->class3_name);
				else
				$class_res[] = array('class_key' => 'class3_name', 'class_name' => $value->class3_name);
				if($value->class4_name == NULL)
				unset($value->class4_name);
				else
				$class_res[] = array('class_key' => 'class4_name', 'class_name' => $value->class4_name);
				if($value->class5_name == NULL)
				unset($value->class5_name);
				else
				$class_res[] = array('class_key' => 'class5_name', 'class_name' => $value->class5_name);
				if($value->class6_name == NULL)
				unset($value->class6_name);
				else
				$class_res[] = array('class_key' => 'class6_name', 'class_name' => $value->class6_name);

				unset($value->class1_name);
				unset($value->class2_name);
				unset($value->class3_name);
				unset($value->class4_name);
				unset($value->class5_name);
				unset($value->class6_name);

				$schools_data[$key]->class_res = $class_res;
			}
		}
		
		$new_schools_data = array();
		foreach($schools_data as $key => $value)
		{
			$new_schools_data[] = $value;
		}

		$result_array[]->school_res = $new_schools_data;
		
		// Check if the given date is current date or not to calculate the tminus deadline
		//$fuldate_time = date('Y-m-d', strtotime($data['fulfilment_date']));		
		
		/*if($fuldate_time == date('Y-m-d'))
		{
			// Calculate Fulfilment Date 
			$date = date('Y-m-d');
			$qry = "SELECT CASE WHEN DATE_ADD(DATE_SUB('".$date." 12:00:00', INTERVAL tminus HOUR), INTERVAL 1 DAY) < NOW() THEN DATE_ADD('".$date."', INTERVAL 1 DAY) ELSE '".$date."' END AS tminus FROM contracts WHERE contract_id = ". $data['contract_id'];
			echo $qry;
			exit;
			$query = $this->db->query($qry);
			$deadline_data = $query->result();

			$ful_date = $deadline_data[0]->tminus;
		}
		else 
		{
			$ful_date = $data['fulfilment_date'];
		}*/
		
		$ful_date = $data['fulfilment_date'];
		$fulfilment_date_format = date("F j, Y", strtotime($ful_date));
		$result_array[]->fulfilment_date = $ful_date;

		if(count($new_schools_data) > 0)
		{
			if($new_schools_data[0]->class_res[0]['class_key'] == 'class1_name' && $new_schools_data[0]->class_res[0]['class_name']!= null)
			{
				$class_name = $new_schools_data[0]->class_res[0]['class_name'];
				$class_key = "class1_name";
			}
			else if($new_schools_data[0]->class_res[0]['class_key'] == 'class2_name' && $new_schools_data[0]->class_res[0]['class_name']!= null)
			{
				$class_name = $new_schools_data[0]->class_res[0]['class_name'];
				$class_key = "class2_name";
			}
			else if($new_schools_data[0]->class_res[0]['class_key'] == 'class3_name' && $new_schools_data[0]->class_res[0]['class_name']!= null)
			{
				$class_name = $new_schools_data[0]->class_res[0]['class_name'];
				$class_key = "class3_name";
			}
			else if($new_schools_data[0]->class_res[0]['class_key'] == 'class4_name' && $new_schools_data[0]->class_res[0]['class_name']!= null)
			{
				$class_name = $new_schools_data[0]->class_res[0]['class_name'];
				$class_key = "class4_name";
			}
			else if($new_schools_data[0]->class_res[0]['class_key'] == 'class5_name' && $new_schools_data[0]->class_res[0]['class_name']!= null)
			{
				$class_name = $new_schools_data[0]->class_res[0]['class_name'];
				$class_key = "class4_name";
			}
			else if($new_schools_data[0]->class_res[0]['class_key'] == 'class6_name' && $new_schools_data[0]->class_res[0]['class_name']!= null)
			{
				$class_name = $new_schools_data[0]->class_res[0]['class_name'];
				$class_key = "class6_name";
			}

			$data_arr = array('school_id' => $data['school_id'], 'year_no' => $new_schools_data[0]->year_no, 'class_key' => $class_key, 'class_name' => $class_name, 'contract_id' => $data['contract_id'], 'collect_status' => 0, 'fulfilment_date' => $ful_date);

			$students_res = $this->get_daily_meal_collection_students($data_arr);
			$result_array[]->student_res = $students_res;
		}
		$result_array[]->fulfilment_date_format = $fulfilment_date_format;
		$result_array[]->close_details = $school_details[0];
		return $result_array;
	}
	
	public function get_daily_meal_collection_students($data)
	{
		// Check if the given date is current date or not to calculate the tminus deadline
		$ful_status = 0;
		$fuldate_time = date('Y-m-d', strtotime($data['fulfilment_date']));
		
		/* To check the tminus deadline for previous day and current day */
		$meal_status = 0;
		if($fuldate_time == date('Y-m-d', strtotime(' -1 day')))
		{
			$qry = "SELECT CASE WHEN DATE_ADD(DATE_SUB('".$this->db->escape_str($data['fulfilment_date'])." 12:00:00', INTERVAL tminus HOUR), INTERVAL 1 DAY) < NOW() THEN 0 ELSE 1 END AS deadline FROM contracts WHERE contract_id = ". $data['contract_id'];
			
			$query = $this->db->query($qry);
			$deadline_data = $query->result();

			$meal_status = $deadline_data[0]->deadline;
		}
		else if($fuldate_time == date('Y-m-d'))
		{
			$qry = "SELECT CASE WHEN (DATE_SUB('".$this->db->escape_str($data['fulfilment_date'])." 12:00:00', INTERVAL tminus HOUR)) > NOW() THEN 0 ELSE 1 END AS deadline FROM contracts WHERE contract_id = ". $data['contract_id'];
			
			$query = $this->db->query($qry);
			$deadline_data = $query->result();

			$meal_status = $deadline_data[0]->deadline;
		}
		
		$ful_date = $data['fulfilment_date'];
		
		$qry = "SELECT a.order_id from order_items a, students b, school_classes c where a.order_status = 0 and a.pupil_id = b.pupil_id and b.active = ". ACTIVE ." and b.school_classes_id = c.school_classes_id and c.school_id = ? and c.year_no = ? and c.".$this->db->escape_str($data['class_key'])."  = ? and a.fulfilment_date = ? and a.collect_status = ? and b.class_col = ? group by a.order_id";
		
		$order_query = $this->db->query($qry, array($data['school_id'],$data['year_no'],$data['class_name'],$ful_date,$data['collect_status'],$data['class_key']));
		$order_data = $order_query->result();

		$student_res = array();
		foreach($order_data as $key => $value)
		{
			$stu_qry = "SELECT a.pupil_id, b.fname, b.mname, b.lname, b.adult, a.fulfilment_date, a.meal_type, a.option_details, a.order_id, a.collect_status from order_items a, students b where a.pupil_id = b.pupil_id and b.active = ". ACTIVE ." and a.order_id = '". $value->order_id ."'";
			
			$query = $this->db->query($stu_qry);
			$order_query_data = $query->result();

			$stu_obj = new stdClass();
			$stu_obj->pupil_id = $order_query_data[0]->pupil_id;
			$stu_obj->fname = $order_query_data[0]->fname;
			$stu_obj->mname = $order_query_data[0]->mname;
			$stu_obj->lname = $order_query_data[0]->lname;
			$stu_obj->adult = $order_query_data[0]->adult;
			$stu_obj->fulfilment_date = $order_query_data[0]->fulfilment_date;
			$stu_obj->order_id = $order_query_data[0]->order_id;
			$stu_obj->collect_status = $order_query_data[0]->collect_status;

			$main_meal = "";
			$snacks = "";
			foreach($order_query_data as $key => $value)
			{
				switch($value->meal_type)
				{
					case MEAL_TYPE_MAINMEAL:
						if($main_meal != "")
						$main_meal = $main_meal . "; " . $value->option_details;
						else
						$main_meal = $value->option_details;
						break;

					case MEAL_TYPE_SNACK:
						if($snacks != "")
						$snacks = $snacks . "; " . $value->option_details;
						else
						$snacks = $value->option_details;
						break;
				}
			}
			$stu_obj->main_meal = $main_meal;
			$stu_obj->snacks = $snacks;

			if($order_query_data[0]->pupil_id != null)
			$student_res[] = $stu_obj;
		}
		$result_arr = array();
		$result_arr[]->student_res = $student_res;
		$result_arr[]->meal_status = $meal_status;

		return $result_arr;
	}

	public function update_daily_meal_collection_status($data)
	{
		$order_data = array(
				'collect_status' => $data['collect_status'],
				'muser_id' => $data['user_id']);

		$this->db->set('mdate', 'NOW()', FALSE);
		$this->db->where('school_id', $data['school_id']);
		$this->db->where('pupil_id', $data['pupil_id']);
		$this->db->where('order_id', $data['order_id']);
		$this->db->where('fulfilment_date', $data['fulfilment_date']);
		$order_query_status = $this->db->update('order_items', $order_data);

		return $order_query_status;
	}

	
	public function get_user_authroization($data){

		
		$m_query_str = "SELECT a.s_module_code m, a.m_module_name mname, a.m_module_code mc FROM (
					SELECT m.m_module_id, m_module_name, m.m_module_code, s.sequence_no, s.s_module_code
					FROM m_modules m INNER JOIN s_modules s on m.m_module_id = s.m_module_id AND m.status = ? ) a
				INNER JOIN( 
					SELECT m_module_id , min(s.sequence_no) min_val FROM users u 
							INNER JOIN profiles_s_modules psm ON u.profile_id = psm.profile_id
							INNER JOIN s_modules s ON psm.s_module_id = s.s_module_id
							WHERE u.user_id =  ?
							AND s.status= ?  GROUP BY s.m_module_id) b ON a.m_module_id = b.m_module_id AND a.sequence_no = b.min_val";

		//echo $m_query_str;
		$m_query = $this->db->query($m_query_str, array(ACTIVE,$data['user_id'],ACTIVE));
		$m_query_data = $m_query->result();
		
		$this->db->select('s_module_code sm, s_module_name smname, p.hide_main_nav');
		$this->db->from('users u');
		$this->db->join('profiles p', 'u.profile_id = p.profile_id');
		$this->db->join('profiles_s_modules ps', 'ps.profile_id = p.profile_id');
		$this->db->join('s_modules s', 'ps.s_module_id = s.s_module_id');
		$this->db->join('m_modules m', 's.m_module_id = m.m_module_id');
		$this->db->where('user_id',$data['user_id']);
		$this->db->where('s.status',ACTIVE);
		$this->db->where('m.status',ACTIVE);
		$this->db->where('m.m_module_code ',$data['module_code']);
		$this->db->order_by('s.sequence_no','ASC');
		$query_s_modules = $this->db->get();
		$res_user_s_modules = $query_s_modules->result();
		
		//echo $this->db->last_query();
		
// 		$ss_mod_query_str = "SELECT ss.ss_module_code AS ssm, ss.ss_module_name AS ssmname, ss.pcode FROM users u
// 							INNER JOIN profiles_ss_modules pss ON pss.profile_id = u.profile_id
// 							INNER JOIN( SELECT ss.ss_module_id, ss.ss_module_name, ss.s_module_id, ss.ss_module_code,ss1.ss_module_code pcode FROM ss_modules ss
// 										LEFT JOIN ss_modules ss1 ON ss1.ss_module_id = ss.p_ss_module_id AND ss.status = ".ACTIVE." and ss1.status =".ACTIVE.")ss ON pss.ss_module_id = ss.ss_module_id
// 							INNER JOIN s_modules s ON ss.s_module_id = s.s_module_id
// 							INNER JOIN m_modules m ON s.m_module_id = m.m_module_id
// 							WHERE u.user_id = ".$data['user_id']."
// 							AND m.m_module_code = '".$data['module_code']."' AND s.status = ".ACTIVE." AND m.status = ".ACTIVE."";

		$ss_mod_query_str = "SELECT ss.sscode AS sscode, ss.ssmname AS ssmname, ss.pcode AS pcode FROM users u
							INNER JOIN profiles_ss_modules pss ON pss.profile_id = u.profile_id
							INNER JOIN (
								SELECT ss.ss_module_id, ss.ss_module_code sscode, ss.sequence_no seq, ssp.ss_module_code pcode, ss.ss_module_name ssmname
								FROM ss_modules ss
								LEFT JOIN ss_modules ssp ON ss.p_ss_module_id = ssp.ss_module_id
								INNER JOIN s_modules s ON ss.s_module_id = s.s_module_id AND s.s_module_code ='".$data['submodule_code']."'
								INNER JOIN m_modules m ON s.m_module_id = m.m_module_id AND m.m_module_code = '".$data['module_code']."'
								WHERE ss.status=".ACTIVE." AND s.status =".ACTIVE." AND m.status= ".ACTIVE."
								) ss ON pss.ss_module_id = ss.ss_module_id
							WHERE u.user_id = ".$data['user_id']."
							ORDER BY ss.seq ASC";
		
		//echo $ss_mod_query_str;
		
		$ss_mod_query = $this->db->query($ss_mod_query_str);
		$ss_mod_query_data = $ss_mod_query->result();
		
// 		$css_mod_query_str = "SELECT ss.ss_module_code AS ssm, ss.ss_module_name AS ssmname, ssp.ss_module_code AS pssm FROM users u
// 							INNER JOIN profiles_ss_modules pss ON pss.profile_id = u.profile_id
// 							INNER JOIN ss_modules ss on pss.ss_module_id = ss.ss_module_id
// 							INNER JOIN ss_modules ssp ON ssp.ss_module_id = ss.p_ss_module_id
// 							INNER JOIN s_modules s ON ss.s_module_id = s.s_module_id
// 							INNER JOIN m_modules m ON s.m_module_id = m.m_module_id
// 							WHERE u.user_id = ".$data['user_id']."
// 							AND m.m_module_code = '".$data['module_code']."' AND s.status = ".ACTIVE." AND m.status = ".ACTIVE." AND ss.status =".ACTIVE."
// 							ORDER BY ss.p_ss_module_id, ss.sequence_no ASC";
		
// 		$css_mod_query = $this->db->query($css_mod_query_str);
// 		$css_mod_query_data = $css_mod_query->result();
		
		$res_user_modules = new stdClass();
		$res_user_modules->m = $m_query_data;
		$res_user_modules-> sm = $res_user_s_modules;
		$res_user_modules-> ssm = $ss_mod_query_data;
		//$res_user_modules-> cssm = $css_mod_query_data;
		return $res_user_modules;
	}
	
	public function initiate_card_payment($data){
		$insert_status = FALSE;
		foreach ($data['pupils_res'] as $pupils_data)
		{
			$insert_data = array(
				'pupil_id' => $pupils_data['pupil_id'],
				'amount' => $pupils_data['amount'],
				'payment_id' => $data['payment_id'],
				'card_cash' => $data['amount_type'],
				'transaction_fee' => $pupils_data['transaction_fee'],
				'pay_refund' => $data['payment'],
				'status' => INACTIVE,
				'cuser_id' => $data['cuser_id'],
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$insert_status = $this->db->insert('payment_items', $insert_data);
			
			/* Update the cash balance for the students id table */
			//$this->update_cash_balance($pupils_data['pupil_id'],$pupils_data['amount'],$data['payment'],$data['amount_type']);
		}
		return $insert_status;
	}
	
	public function save_card_payment($data)
	{
		$pgtr = '';
		$auth_id = '';
		if(isset($data['pgtr']))
		{
			$pgtr = $data['pgtr'];
		}
		if(isset($data['auth_id']))
		{
			$auth_id = $data['auth_id'];
		}
		$card_data = array(
				'status' => ACTIVE,
				'pgtr_id' => $pgtr,
				'yp_code' => $data['yp_code'],
				'pgauth_id' => $auth_id);

		$this->db->where('payment_id', $data['mtr']);
		$this->db->where('card_cash', $data['amount_type']);
		$this->db->where('pay_refund', $data['payment']);
		$this->db->where('status', INACTIVE);
		$card_status = $this->db->update('payment_items', $card_data);
		
		if($card_status && $data['yp_code'] == 0)
		{
			$this->db->select('pupil_id, amount');
			$this->db->from('payment_items');
			$this->db->where('payment_id', $data['mtr']);
			$query = $this->db->get();
			$pupil_res = $query->result();
			
			foreach($pupil_res as $key => $value)
			{
				$this->update_cash_balance($value->pupil_id,$value->amount,$data['payment'],$data['amount_type']);
			}
		}

		return $card_status;
	}
	
	public function cancel_card_payment($data)
	{
		$cancel_data = array(
								'yp_code' => $data['yp_code'],
								'status' => ACTIVE);
		
		$this->db->where('payment_id', $data['mtr']);
		$this->db->where('card_cash', $data['amount_type']);
		$this->db->where('pay_refund', $data['payment']);
		$this->db->where('status', INACTIVE);
		$cancel_status = $this->db->update('payment_items', $cancel_data);
		
		return $cancel_status;
	}
	
	public function get_batch_order_cancellation($data)
	{
		$res_batch_order = array();
		
		if($data['page'] == 1)
			$start = 0;		
		else 
			$start = ($data['page'] - 1) * BATCH_ORDER_CANCELLATION_PAGINATION_COUNT;

		$qry = "SELECT b.batch_cancel_id as cnt FROM batch_cancel b, order_items o WHERE b.batch_cancel_id = o.batch_cancel_id and o.school_id = ". $data['school_id'] ." and o.order_edited = ". ACTIVE ." and o.order_status = ". ORDER_STATUS_NEW ." and o.collect_status = ". INACTIVE ." and active = ". ACTIVE ." and b.cancel_status = ". INACTIVE ." and clear = ". $data['clear'] ." group by o.batch_cancel_id";
		
		$query = $this->db->query($qry);
		$batch_count = $query->result();
			
		//$qry_str = "SELECT b.batch_cancel_id, b.system_msg, b.user_msg, b.email_status, b.cancel_status, (SELECT COUNT(1) from order_items o where CURDATE() <= o.fulfilment_date and o.order_edited = ". ACTIVE ." and o.order_status = ". ORDER_STATUS_NEW ." AND o.batch_cancel_id = b.batch_cancel_id and o.school_id = ". $data['school_id'].") as future, (SELECT COUNT(1) from order_items o where CURDATE() > o.fulfilment_date and o.order_edited = ". ACTIVE ." and o.order_status = ". ORDER_STATUS_NEW ." AND o.batch_cancel_id = b.batch_cancel_id and o.school_id = ". $data['school_id'] .") as past from batch_cancel b, order_items o1 where b.batch_cancel_id = o1.batch_cancel_id and o1.school_id = ". $data['school_id'] ." and o1.order_edited = ". ACTIVE ." and o1.order_status = ". ORDER_STATUS_NEW ." and b.active = ". ACTIVE ." and b.cancel_status = ". INACTIVE ." and b.clear = ". $data['clear'] ." group by o1.batch_cancel_id LIMIT ". $start .", ".BATCH_ORDER_CANCELLATION_PAGINATION_COUNT;
		$qry_str = "SELECT b.batch_cancel_id, b.system_msg, b.user_msg, b.email_status, b.cancel_status, (SELECT COUNT(1) as future FROM (SELECT * from order_items o where CURDATE() <= o.fulfilment_date and o.order_edited = ". ACTIVE ." and o.order_status = ". ORDER_STATUS_NEW ." AND o.collect_status = ". INACTIVE ." AND o.batch_cancel_id = batch_cancel_id and o.school_id = ". $data['school_id'] ." group by o.order_id) future where batch_cancel_id = b.batch_cancel_id) as future, (SELECT COUNT(1) as past FROM (SELECT * from order_items o where CURDATE() > o.fulfilment_date and o.order_edited = ". ACTIVE ." and o.order_status = ". ORDER_STATUS_NEW ." AND o.collect_status = ". INACTIVE ." AND o.batch_cancel_id = batch_cancel_id and o.school_id = ". $data['school_id'] ." group by o.order_id) past where batch_cancel_id = b.batch_cancel_id) as past FROM batch_cancel b, order_items o1 where b.batch_cancel_id = o1.batch_cancel_id and o1.school_id = ". $data['school_id'] ." and o1.order_edited = ". ACTIVE ." and o1.order_status = ". ORDER_STATUS_NEW ." and o1.collect_status = ". INACTIVE ." and b.active = ". ACTIVE ." and b.cancel_status = ". INACTIVE ." and b.clear = ". $data['clear'] ." group by o1.batch_cancel_id LIMIT ". $start .", ".BATCH_ORDER_CANCELLATION_PAGINATION_COUNT;
		
		$query = $this->db->query($qry_str);
		$batch_res = $query->result();
		
		$res_batch_order['count'] = count($batch_count);
		$res_batch_order['batch_order_res'] = $batch_res;
		
		return $res_batch_order;
	}
	
	/* To get the export items */
	public function export_batch_order_items($data)
	{
		$qry = "SELECT a.order_id, DATE_FORMAT(date(a.fulfilment_date), '%d/%m/%Y') as fulfilment_date, DATE_FORMAT(max(date(a.order_date)), '%d/%m/%Y') as order_date, CASE WHEN a.order_status = 0 AND a.order_edited = 0 then 'New' WHEN a.order_status = 0 AND a.order_edited = 1 then 'Edited' ELSE 'Cancelled' end as order_status, c.batch_cancel_id, c.system_msg, c.user_msg, SUM(a.card_net) as card_net, SUM(a.card_vat) as card_vat, SUM(a.cash_net) as cash_net, SUM(a.cash_vat) as cash_vat, SUM(a.fsm_net) as fsm_net, SUM(a.fsm_vat) as fsm_vat, SUM(a.a_card_net) as a_card_net, SUM(a.a_card_vat) as a_card_vat, SUM(a.a_cash_net) as a_cash_net, SUM(a.a_cash_vat) as a_cash_vat, SUM(a.a_sa_net) as a_sa_net, SUM(a.a_sa_vat) as a_sa_vat, SUM(a.a_hos_net) as a_hos_net, SUM(a.a_hos_vat) as a_hos_vat from order_items a, schools b, batch_cancel c where a.school_id = b.school_id and a.batch_cancel_id = c.batch_cancel_id and c.batch_cancel_id = ". $data['batch_cancel_id'] ." and c.cancel_status = ". INACTIVE ." and a.collect_status = ". INACTIVE ." and a.order_edited =". ACTIVE ." and a.order_status = ". ORDER_STATUS_NEW ." and b.contract_id = ". $data['contract_id'] ." group by order_id";
		
		$order_query = $this->db->query($qry);
		$order_data = $order_query->result();	
		
		foreach($order_data as $key => $value)
		{
			$qry_str = "SELECT a.meal_type, a.option_details, a.option_cost, a.hospitality_desc, b.pupil_id, b.fname, b.mname, b.lname, a.fsm, a.adult, b.class_col, b.pupil_dup, a.status, c.school_key, c.school_name, d.year_label, d.class1_name, d.class2_name, d.class3_name, d.class4_name, d.class5_name, d.class6_name, e.username from order_items a, students b, schools c, school_classes d, users e where a.order_id = '". $value->order_id . "' and a.pupil_id = b.pupil_id and b.active = ". ACTIVE . " and a.school_id = c.school_id and b.school_classes_id = d.school_classes_id and e.user_id = a.cuser_id and c.contract_id = ". $data['contract_id'] ." order by c.school_id, a.fulfilment_date";
			
			$query = $this->db->query($qry_str);
			$order_query_data = $query->result();
			
			if(count($order_query_data) > 0)
			{
				$stu_obj = new stdClass();
				$stu_obj->order_id = $value->order_id;
				$stu_obj->fulfilment_date = $value->fulfilment_date;
				$stu_obj->order_date = $value->order_date;
				$stu_obj->order_status = $value->order_status;
				$stu_obj->impact_batch = $value->batch_cancel_id;
				$stu_obj->sys_msg = $value->system_msg;
				$stu_obj->user_msg = $value->user_msg;
				$stu_obj->card_net = $value->card_net;
				$stu_obj->card_vat = $value->card_vat;
				$stu_obj->cash_net = $value->cash_net;
				$stu_obj->cash_vat = $value->cash_vat;
				$stu_obj->fsm_net = $value->fsm_net;
				$stu_obj->fsm_vat = $value->fsm_vat;
				$stu_obj->a_card_net = $value->a_card_net;
				$stu_obj->a_card_vat = $value->a_card_vat;
				$stu_obj->a_cash_net = $value->a_cash_net;
				$stu_obj->a_cash_vat = $value->a_cash_vat;
				$stu_obj->a_sa_net = $value->a_sa_net;
				$stu_obj->a_sa_vat = $value->a_sa_vat;
				$stu_obj->a_hos_net = $value->a_hos_net;
				$stu_obj->a_hos_vat = $value->a_hos_vat;
	
				$main_meal = "";
				$main_meal_net = "";
				$snacks = "";
				$snacks_net = "";
				$hosp_desc = "";
							
				foreach($order_query_data as $k => $val)
				{
					switch($val->meal_type)
					{
						case MEAL_TYPE_MAINMEAL:
							if($main_meal != "")					
								$main_meal = $main_meal . "; " . $val->option_details;
							else 
								$main_meal = $val->option_details;
							
							if($main_meal_net != "")
								$main_meal_net = $main_meal_net . "; " . $val->option_cost;
							else
								$main_meal_net = $val->option_cost;					
							break;
						
						case MEAL_TYPE_SNACK:
							if($snacks != "")					
								$snacks = $snacks . "; " . $val->option_details;
							else 
								$snacks = $val->option_details;
							
							if($snacks_net != "")
								$snacks_net = $snacks_net . "; " . $val->option_cost;
							else
								$snacks_net = $val->option_cost;
							break;
					}
					
					if($val->hospitality_desc)
					{
						if($hosp_desc == "")
							$hosp_desc = $val->hospitality_desc;
						else
							$hosp_desc = $hosp_desc . "; ". $val->hospitality_desc;
					}
				}
				
				$stu_obj->main_meal = $main_meal;
				$stu_obj->main_net = $main_meal_net;
				
				$stu_obj->snacks = $snacks;
				$stu_obj->snacks_net = $snacks_net;
				
				$stu_obj->hospitality_desc = $hosp_desc;
				
				$stu_obj->pupil_id = $order_query_data[0]->pupil_id;
				$stu_obj->fname = $order_query_data[0]->fname;
				$stu_obj->mname = $order_query_data[0]->mname;
				$stu_obj->lname = $order_query_data[0]->lname;
				$stu_obj->fsm = $order_query_data[0]->fsm;
				$stu_obj->adult = $order_query_data[0]->adult;
				$stu_obj->class_col = $order_query_data[0]->class_col;
				$stu_obj->pupil_dup = $order_query_data[0]->pupil_dup;
				$stu_obj->status = $order_query_data[0]->status;
				$stu_obj->school_key = $order_query_data[0]->school_key;
				$stu_obj->school_name = $order_query_data[0]->school_name;
				$stu_obj->year_label = $order_query_data[0]->year_label;
				$stu_obj->class1_name = $order_query_data[0]->class1_name;
				$stu_obj->class2_name = $order_query_data[0]->class2_name;
				$stu_obj->class3_name = $order_query_data[0]->class3_name;
				$stu_obj->class4_name = $order_query_data[0]->class4_name;
				$stu_obj->class5_name = $order_query_data[0]->class5_name;
				$stu_obj->class6_name = $order_query_data[0]->class6_name;
				$stu_obj->username = $order_query_data[0]->username;
				
				$order_res[] = $stu_obj;
			}			
		}
		return $order_res;
	}
	
	/* cancel the order information */
	public function batch_cancel_order_items($data){
		
		$this->db->select('order_item_id AS oid, pupil_id, card_net, cash_net, a_cash_net, a_card_net, a_card_vat, a_cash_vat, adult');
		$this->db->from('order_items');
		$this->db->where('batch_cancel_id', $data['batch_cancel_id']);
		$this->db->where('school_id', $data['school_id']);
		$this->db->where('order_status', ORDER_STATUS_NEW);
		$this->db->where('order_edited', ACTIVE);
		$query = $this->db->get();
		$res_order_details= $query->result();
		
		foreach($res_order_details as $obj)
		{
			
			$cash_balance = 0;
			$card_balance = 0;
			//Calculate the return balance
			if($obj->adult == 1){
				$cash_balance = $cash_balance + $obj->a_cash_net + $obj->a_cash_vat;
				$card_balance = $card_balance + $obj->a_card_net + $obj->a_card_vat;
			} else {
				$cash_balance = $cash_balance + $obj->cash_net;
				$card_balance = $card_balance + $obj->card_net;
			}
			
			//Cancel the order
			$order_query_data = array(
				'order_status' => ORDER_STATUS_CANCEL,
				'muser_id' =>$data['user_id']
			);
			$this->db->set('mdate','NOW()', FALSE);
			$this->db->WHERE('order_item_id',  $obj->oid);
			$this->db->WHERE('pupil_id', $obj->pupil_id);
			$this->db->WHERE('order_type', MEAL_ORDER);
			$this->db->WHERE('order_status', ORDER_STATUS_NEW);
			$this->db->WHERE('order_edited', ACTIVE);
			$this->db->WHERE('batch_cancel_id', $data['batch_cancel_id']);
			$this->db->update('order_items',$order_query_data);
			
			//Get the student details..
			$this->db->select('fsm, adult, cash_balance, card_balance',false);
			$this->db->from('students');
			$this->db->where('pupil_id', $obj->pupil_id);
			$this->db->where('active', ACTIVE);
			$query = $this->db->get();
			$res_student = $query->result();

			foreach($res_student as $key => $value)
			{
				//Update the balance amount...
				$st_data = array(
				'cash_balance' => $value->cash_balance + $cash_balance,
				'card_balance' => $value->card_balance + $card_balance,
				);
				$this->db->where('pupil_id', $obj->pupil_id);
				$this->db->where('active', ACTIVE);
				$this->db->update('students',$st_data);
			}			
		}

		//Update batch cancel table
		$batch_data = array(
				'cancel_status' => ACTIVE,
				'muser_id' =>$data['user_id']
		);
		$this->db->set('mdate','NOW()', FALSE);		
		$this->db->where('batch_cancel_id', $data['batch_cancel_id']);
		$update_res = $this->db->update('batch_cancel',$batch_data);
		return $update_res;
	}
	
	public function update_batch_order_user_msg($data)
	{
		$user_msg_data = array(
				'user_msg' => $data['user_msg'],
				'muser_id' =>$data['user_id']
		);
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->where('batch_cancel_id', $data['batch_cancel_id']);
		$this->db->where('active', ACTIVE);
		$update_res = $this->db->update('batch_cancel',$user_msg_data);
		return $update_res;
	}
	
	public function update_batch_order_clear_flag($data)
	{
		$clear_data = array(
				'clear' => $data['clear'],
				'muser_id' =>$data['user_id']
		);
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->where('batch_cancel_id', $data['batch_cancel_id']);
		$this->db->where('active', ACTIVE);
		$update_res = $this->db->update('batch_cancel',$clear_data);
		return $update_res;
	}
        
	public function batch_email_parents($data)
	{
		$order_qry = "SELECT s.fname, s.lname, s.user_id, o.fulfilment_date FROM students s, order_items o WHERE
							s.pupil_id = o.pupil_id 
							AND o.batch_cancel_id = ". $data['batch_cancel_id'] ."
							AND o.fulfilment_date >= DATE(NOW())
							AND s.status = ". ACTIVE ."
							AND s.active = ". ACTIVE ."
						group by o.order_id";
		
		$order_data = $this->db->query($order_qry);
		$order_res = $order_data->result();
		
		$user_arr = array();
		
		foreach($order_res as $key => $value)
		{
			if(count($user_arr) > 0)
			{
				foreach($user_arr as $k => $v)
				{
					if($v['user_id'] == $value->user_id)
						break;
					else
						$user_arr[] = array('user_id' => $value->user_id);
				}
			}
			else
			{
				$user_arr[] = array('user_id' => $value->user_id);
			}
		}
		
		$serverName= $_SERVER["SERVER_NAME"];
		$from_addr = 'admin@'.$serverName;
		
		foreach($user_arr as $key => $value)
		{
			$qry = "SELECT user_email, first_name, last_name FROM users WHERE user_id = ". $value['user_id'] ." AND mail_notification = ". ACTIVE;
			
			$user_data = $this->db->query($qry);
			$user_res = $user_data->result();
			
			if(count($user_res) > 0)
			{
				$msg = '<html><body>';
				$msg =  'Dear '. $user_res[0]->first_name .' '. $user_res[0]->last_name .',<br><br>
					The following orders have been updated by the Eden Team<br><br>';
				
				foreach($order_res as $k => $v)
				{
					if($value['user_id'] == $v->user_id)
					{
						$msg = $msg . '- '. $v->fname .' '. $v->lname .' meal order for '. $v->fulfilment_date .'<br>';
					}
				}
				
				$msg = $msg .'<br>' .$data['email_msg'] .'</body></html>';
				$msg = nl2br($msg);
				
				$pay_load_obj = new stdClass();
				$pay_load_obj->to_email = $user_res[0]->user_email;
				$pay_load_obj->from_email = $from_addr;
				$pay_load_obj->subject = $data['email_sub'];
				$pay_load_obj->email_content = $msg;
				
				$json_str = json_encode($pay_load_obj);
				
				$job_data = array(
					'job_type_id' => JOB_EMAIL_TYPE_ID,
					'job_batch_type_id' => JOB_BATCH_TYPE_ID,
					'payload_xml' => $json_str,
					'job_status' => INACTIVE,
					'cuser_id' => $data['user_id']);

				$this->db->set('cdate', 'NOW()', FALSE);
				$job_qry_status = $this->db->insert('jobs', $job_data);
			}			
		}
		
		$email_data = array(
				'email_status' => ACTIVE,
				'muser_id' =>$data['user_id']
		);
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->where('batch_cancel_id', $data['batch_cancel_id']);
		$this->db->where('active', ACTIVE);
		$update_res = $this->db->update('batch_cancel',$email_data);
		return $update_res;
	}
	
	public function digital_form_load($data){
		
		$digital_form_type_str = "SELECT digital_form_type_id ftid, type_name tn FROM digital_form_type";
		$digital_form_type_query= $this->db->query($digital_form_type_str);
		$digital_form_type_data = $digital_form_type_query->result();
		
		$digital_pen_str = "SELECT digital_pen_id dpid, label_name pn FROM digital_pen WHERE contract_id='".$data['contract_id']."' AND status ='".ACTIVE."'";
		$digital_pen_query= $this->db->query($digital_pen_str);
		$digital_pen_data = $digital_pen_query->result();

		$digital_ward_str = "SELECT digital_form_ward_id wid, digital_form_ward_name wn FROM digital_form_ward WHERE contract_id='".$data['contract_id']."'";
		$digital_ward_query= $this->db->query($digital_ward_str);
		$digital_ward_data = $digital_ward_query->result();
		
		$digital_dayofweek_str = "SELECT digital_form_dayofweek_id did, digital_form_dayofweek_name dn FROM digital_form_dayofweek WHERE contract_id='".$data['contract_id']."'";
		$digital_dayofweek_query= $this->db->query($digital_dayofweek_str);
		$digital_dayofweek_data = $digital_dayofweek_query->result();
		
		$digital_form_ind_str = "SELECT digital_form_indicator_id mtid, menu_type mttype, menu_type_description mtd FROM digital_form_indicator WHERE contract_id='".$data['contract_id']."'";
		$digital_form_ind_query= $this->db->query($digital_form_ind_str);
		$digital_form_ind_data = $digital_form_ind_query->result();
		
		
		$digital_form_res = new stdClass();
		$digital_form_res->ft =$digital_form_type_data;
		$digital_form_res->p = $digital_pen_data;
		$digital_form_res->w = $digital_ward_data;
		$digital_form_res->d = $digital_dayofweek_data;
		$digital_form_res->mt = $digital_form_ind_data;
		
		return $digital_form_res;
	}
	
	public function digital_form_filter($data){
		
		//Get the navigation location Id
		$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
		$patient_cat_query= $this->db->query($patient_cat_str);
		$patient_cat_data = $patient_cat_query->result();
		
		//If selected form type is blank or selected pens are blank 
		//If selected ward is blank or selected day of week is blank or selected menutype is blank
		//If no ward selected and other form is not selected
		//If no day of week selected and other form is not selected
		if($data['ft'] == "" 
				|| $data['p'] == ""
				|| $data['w']  == ""
				|| $data['dw'] == ""
				|| $data['mt'] == ""
				|| ($data['w']  == "0" && strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") === false)
				|| ($data['dw']  == "0" && strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") === false && strpos(",".$data['ft'].",",",".ADHOC_FORM_TYPE_ID.",") === false)
		){
			$digital_form_res = new stdClass();
			$digital_form_res->tc = 0;
			$digital_form_res->fd = array();
			return $digital_form_res; 
		}
		$form_ex_str = ($data['isex'] == "0") ? " AND (df.exception_status = 0 or df.exception_status = 2)" : "";
		$custom_str = ($data['custom'] == "1") ? " AND is_custom='".ACTIVE."'" : "";
		
		//Get all digital_form_ids based on given ward criteria
		$digital_sub_ward_str = "";
		$digital_ward_str = " SELECT 0 as digital_form_id, '' AS digital_form_ward_name ";

		//If the ward contains no ward and form type contains other then select all other digital forms
		if(strpos(",".$data['w'].",",",0,") !== false  && strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") !== false)
		$digital_sub_ward_str = "SELECT digital_form_id FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND  df.digital_form_type_id = '".OTHER_FORM_TYPE_ID."' 
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str;
		
		//If the selected ward doens't have No ward only then, return all digital formid based on selected wards.
		if($data['w']  != "0") {
			$digital_sub_ward_str = $digital_sub_ward_str. (($digital_sub_ward_str != "") ? " UNION " : ""). 
							"SELECT digital_form_id FROM digital_lunch_form WHERE digital_form_ward_id_up IN (".$data['w'].") ".$custom_str."
							UNION
							SELECT digital_form_id FROM digital_dinner_form WHERE digital_form_ward_id_up IN (".$data['w'].") ".$custom_str."
							UNION
							SELECT digital_form_id FROM digital_adhoc_form WHERE digital_form_ward_id IN (".$data['w'].")";
			
			//Get all digital form ward names
			$digital_ward_str = "SELECT digital_form_id, dw.digital_form_ward_name
					FROM digital_lunch_form dl
					INNER JOIN digital_form_ward dw ON dl.digital_form_ward_id_up = dw.digital_form_ward_id
					WHERE dl.digital_form_ward_id_up IN (".$data['w'].") ".$custom_str."
					UNION
					SELECT digital_form_id, dw.digital_form_ward_name
					FROM digital_dinner_form dl
					INNER JOIN digital_form_ward dw ON dl.digital_form_ward_id_up = dw.digital_form_ward_id
					WHERE dl.digital_form_ward_id_up IN (".$data['w'].") ".$custom_str."
					UNION
					SELECT digital_form_id, group_concat(digital_form_ward_name )
					FROM digital_adhoc_form dl
					INNER JOIN digital_form_ward dw ON dl.digital_form_ward_id = dw.digital_form_ward_id
					WHERE dl.digital_form_ward_id IN (".$data['w'].")
					GROUP BY digital_form_id";
			
		}
		//echo $digital_ward_str;
		
		//Get all digital_form_ids based on given day of week criteria
		$digital_sub_dw_str = "";
		$digital_dow_str = " SELECT 0 as digital_form_id, '' AS digital_form_dayofweek_name ";
		
		// if day of week contains no day of week and form type contains other or adhoc then select all adhoc & other forms.
		if(strpos(",".$data['dw'].",",",0,") !== false && (strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") !== false || strpos(",".$data['ft'].",",",".ADHOC_FORM_TYPE_ID.",") !== false))
			$digital_sub_dw_str = " SELECT digital_form_id FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND  df.digital_form_type_id IN('".OTHER_FORM_TYPE_ID."','".ADHOC_FORM_TYPE_ID."')
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str;
		
		//If the selected day of week doens't have No day of week only then, return all digital formid based on selected day of week.
		if($data['dw']  != "0") {
			$digital_sub_dw_str = $digital_sub_dw_str. (($digital_sub_dw_str != "") ? " UNION " : ""). "
						SELECT digital_form_id FROM digital_lunch_form WHERE digital_form_dayofweek_id IN (".$data['dw'].") ".$custom_str."
						UNION
						SELECT digital_form_id FROM digital_dinner_form WHERE digital_form_dayofweek_id IN (".$data['dw'].") ".$custom_str."";

			$digital_dow_str = "SELECT digital_form_id, digital_form_dayofweek_name FROM digital_lunch_form dl
									INNER JOIN digital_form_dayofweek dfw ON dl.digital_form_dayofweek_id = dfw.digital_form_dayofweek_id
									WHERE dl.digital_form_dayofweek_id IN (".$data['dw'].") ".$custom_str."
									UNION
									SELECT digital_form_id, digital_form_dayofweek_name FROM digital_dinner_form dl
									INNER JOIN digital_form_dayofweek dfw ON dl.digital_form_dayofweek_id = dfw.digital_form_dayofweek_id
									WHERE dl.digital_form_dayofweek_id IN (".$data['dw'].") ".$custom_str."";
		}
		//Get all digital_form_ids based on the given menu type criteria
		$digtal_sub_mt_str = "";
		//if the selected menu type has only No Menu Type then
		if($data['mt']  == "0"){
			//if day of week contains no menu type and form type contains other or adhoc then select all adhoc & other forms.
			if(strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") !== false || strpos(",".$data['ft'].",",",".ADHOC_FORM_TYPE_ID.",") !== false)
				$digtal_sub_mt_str = "SELECT digital_form_id FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND  df.digital_form_type_id IN('".OTHER_FORM_TYPE_ID."','".ADHOC_FORM_TYPE_ID."')
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str;
				
			//if day of week contains no menu type and form type contains lunch then select lunch forms having no menu type.
			if(strpos(",".$data['ft'].",",",".LUNCH_FORM_TYPE_ID.",") !== false)
				$digtal_sub_mt_str = $digtal_sub_mt_str. (($digtal_sub_mt_str != "") ? " UNION " : ""). "
					SELECT df.digital_form_id from digital_lunch_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_lunch_form_indicator dlfi on dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						WHERE dlfi.digital_lunch_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str .$custom_str;
				
			//if day of week contains no menu type and form type contains dinner then select dinner forms having no menu type.
			if(strpos(",".$data['ft'].",",",".DINNER_FORM_TYPE_ID.",") !== false)
				$digtal_sub_mt_str = $digtal_sub_mt_str. (($digtal_sub_mt_str != "") ? " UNION " : ""). "
					SELECT df.digital_form_id from digital_dinner_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_dinner_form_indicator dlfi on dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						WHERE dlfi.digital_dinner_form_id IS NULL 
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str. $custom_str;
		} else {
			// if the selected menu type contains No Menu type
			if(strpos(",".$data['mt'].",",",0,") !== false){
				//if day of week contains no menu type and form type contains other or adhoc then select all adhoc & other forms.
				if(strpos(",".$data['ft'].",",",".OTHER_FORM_TYPE_ID.",") !== false || strpos(",".$data['ft'].",",",".ADHOC_FORM_TYPE_ID.",") !== false)
					$digtal_sub_mt_str = "SELECT digital_form_id FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND  df.digital_form_type_id IN('".OTHER_FORM_TYPE_ID."','".ADHOC_FORM_TYPE_ID."')
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str;
					
				//if day of week contains no menu type and form type contains lunch then select lunch forms having no menu type.
				if(strpos(",".$data['ft'].",",",".LUNCH_FORM_TYPE_ID.",") !== false)
					$digtal_sub_mt_str = $digtal_sub_mt_str. (($digtal_sub_mt_str != "") ? " UNION " : ""). "
					SELECT df.digital_form_id from digital_lunch_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_lunch_form_indicator dlfi on dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						WHERE dlfi.digital_lunch_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str . $custom_str;
					
				//if day of week contains no menu type and form type contains dinner then select dinner forms having no menu type.
				if(strpos(",".$data['ft'].",",",".DINNER_FORM_TYPE_ID.",") !== false)
					$digtal_sub_mt_str = $digtal_sub_mt_str. (($digtal_sub_mt_str != "") ? " UNION " : ""). "
					SELECT df.digital_form_id from digital_dinner_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_dinner_form_indicator dlfi on dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						WHERE dlfi.digital_dinner_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' " .$form_ex_str. $custom_str;
					
			}
			
			if($data['mt']  != "0"){
				$digtal_sub_mt_str = $digtal_sub_mt_str.  (($digtal_sub_mt_str != "") ? " UNION " : ""). "
						SELECT dl.digital_form_id
						FROM digital_lunch_form dl
						INNER JOIN digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].") ".$custom_str."
						UNION
						SELECT dl.digital_form_id
						FROM digital_dinner_form dl
						INNER JOIN digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].") ".$custom_str."";
			}
		}
		
		
		//echo $digtal_sub_mt_str;
		
		$digital_form_str = "SELECT distinct df.digital_form_id FROM digital_app da
							INNER JOIN digital_form df ON da.digital_app_id = df.digital_app_id AND da.contract_id = '".$data['contract_id']."'
							INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id
							INNER JOIN (".$digital_sub_ward_str.") a ON a.digital_form_id = df.digital_form_id
							INNER JOIN (".$digital_sub_dw_str.") b ON b.digital_form_id = df.digital_form_id
							INNER JOIN (".$digtal_sub_mt_str.") c ON c.digital_form_id = df.digital_form_id
							WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
							AND  df.digital_form_type_id IN(".$data['ft'].")
							AND  dp.digital_pen_id IN (".$data['p'].")
							AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' ". $form_ex_str;
		
		
		//echo $digital_form_str;
		$digital_form_count_str = "SELECT COUNT(1) c FROM (".$digital_form_str.") a ";
		//echo $digital_form_count_str;
		
		$digital_form_count_query= $this->db->query($digital_form_count_str);
		$digital_form_count_data = $digital_form_count_query->result();
		
		if($data['page_no'] == 1)
			$start = 0;
		else
			$start = ($data['page_no'] - 1) * DIGITAL_FROMS_COUNT;
		
		$digital_form_main_str = "SELECT 
									df.digital_form_id dfid,
									DATE_FORMAT(df.server_received, '%d/%c/%Y %H:%i %p') d,
									da.label al,
									dp.label_name pl,
									COALESCE(b.digital_form_ward_name,'') w,
									COALESCE(c.digital_form_dayofweek_name,'') dw,
									exception_status es,
									COALESCE(exception_reason,'-') ex,
									CASE WHEN digital_form_type_id = ".LUNCH_FORM_TYPE_ID." THEN 'L'
										WHEN digital_form_type_id = ".DINNER_FORM_TYPE_ID." THEN 'D'
										WHEN digital_form_type_id = ".ADHOC_FORM_TYPE_ID." THEN 'A'
									ELSE 'O' END AS ft,
									CASE WHEN da.template_path = '' or da.template_path = 'Choose...' THEN 0 ELSE 1 END fx,
									COALESCE(d.diff,'0') AS ln
								FROM digital_app da
								INNER JOIN digital_form df ON da.digital_app_id = df.digital_app_id AND da.contract_id = '".$data['contract_id']."'
								INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id
								INNER JOIN digital_pen dp ON dfp.digital_pen_id = dp.digital_pen_id AND dp.contract_id = '".$data['contract_id']."'
									INNER JOIN (".$digital_form_str.")  a on a.digital_form_id = df.digital_form_id
									LEFT JOIN (".$digital_ward_str.") b ON b.digital_form_id = df.digital_form_id
									LEFT JOIN (".$digital_dow_str.") c ON c.digital_form_id = df.digital_form_id
								LEFT JOIN (
									SELECT dl.digital_form_id,  (menu_quantitiy -late_numbers) diff
										FROM digital_lunch_form dl 
										INNER JOIN digital_form df ON dl.digital_form_id = df.digital_form_id
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id AND da.contract_id =  '".$data['contract_id']."' ".$custom_str."
										WHERE (menu_quantitiy -late_numbers) !=0
										UNION
										SELECT dl.digital_form_id,  (menu_quantitiy -late_numbers) diff
										FROM digital_dinner_form dl 
										INNER JOIN digital_form df ON dl.digital_form_id = df.digital_form_id
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id AND da.contract_id =  '".$data['contract_id']."' ".$custom_str."
										WHERE (menu_quantitiy -late_numbers) !=0
												
								) d ON d.digital_form_id = df.digital_form_id
								GROUP BY df.digital_form_id
								ORDER BY df.server_received DESC
								LIMIT " .$start ."," .DIGITAL_FROMS_COUNT;
		
		//echo $digital_form_main_str;
		$digital_form_main_query= $this->db->query($digital_form_main_str);
		$digital_form_main_data = $digital_form_main_query->result();
		
		$digital_form_res->tc = $digital_form_count_data[0]->c;
		$digital_form_res->fd = $digital_form_main_data;
		
		//echo "<pre>";
		//print_r($digital_form_res);
		return $digital_form_res;
	}
	
	public function view_digital_form($data)
	{
		$qry = "SELECT da.digital_app_id, da.app_name, da.contract_id, df.form_xml FROM 
					digital_app da, digital_form df WHERE
					df.digital_form_id = ". $data['dfid'] ."
					AND da.digital_app_id = df.digital_app_id
					AND da.status = ". ACTIVE;
		
		$digital_form_query= $this->db->query($qry);
		$digital_form_data = $digital_form_query->result();
		return $digital_form_data[0];
	}
	public function total_daily_meal_filter($data){
	//Get the navigation location Id
	$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
	$patient_cat_query= $this->db->query($patient_cat_str);
	$patient_cat_data = $patient_cat_query->result();

	//Return empty result if
	//Pens are blank or wards are blank or
	//Lunch&Dinner and day of week is blank or "No day of week" selected or Lunch and day of week is "No day of week" selected or dinner and day of week is "No day of week" selected
	//Adhoc "No Menu type" is not selected.
	if($data['p'] == "" || 
		$data['w'] == "" ||
		($data['ft'] == 0 && ($data['dw']=="" || $data['dw']=="0")) ||
		($data['ft'] == LUNCH_FORM_TYPE_ID && ($data['dw']=="" || $data['dw']=="0")) ||
		($data['ft'] == DINNER_FORM_TYPE_ID && ($data['dw']=="" || $data['dw']=="0")) || 
		($data['ft'] == ADHOC_FORM_TYPE_ID && strpos(",".$data['mt'].",",",0,") === false))
	{
			return new stdClass();
	}
	
	$form_ex_str = "";
	//$form_ex_str = ($data['isex'] == "0") ? " AND (df.exception_status = 0 or df.exception_status = 2)" : "";

	$digital_sub_query = "";
	
		if($data['ft'] == 0) { //Lunch & Dinner
			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digital_sub_query = "SELECT dl.digital_form_id FROM
					digital_lunch_form dl
					LEFT JOIN digital_lunch_form_indicator dlfi ON dl.digital_lunch_form_id = dlfi.digital_lunch_form_id
					WHERE dl.digital_form_ward_id_up IN (".$data['w'].")
							AND digital_form_dayofweek_id IN (".$data['dw'].")
							AND dlfi.digital_form_indicator_id IS NULL
							UNION
							SELECT dl.digital_form_id FROM
							digital_dinner_form dl
							LEFT JOIN digital_dinner_form_indicator dlfi ON dl.digital_dinner_form_id = dlfi.digital_dinner_form_id
							WHERE dl.digital_form_ward_id_up IN (".$data['w'].")
									AND digital_form_dayofweek_id IN (".$data['dw'].")
									AND dlfi.digital_form_indicator_id IS NULL";
			}
			
			//echo $digital_sub_query;
			
			$digital_sub_query = ($digital_sub_query != "") ? " INNER JOIN (".$digital_sub_query.") a ON a.digital_form_id = df.digital_form_id " : "";

			$digital_form_query_str = "SELECT DISTINCT dfi.digital_form_indicator_id dfid, menu_type mt, menu_type_description mtd
						FROM digital_form_indicator dfi
						INNER JOIN digital_lunch_form_indicator dlfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
						INNER JOIN digital_lunch_form dlf ON dlf.digital_lunch_form_id = dlfi.digital_lunch_form_id
						INNER JOIN digital_form df ON df.digital_form_id = dlf.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						".$digital_sub_query."
						WHERE date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."'
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")
							AND dlfi.digital_form_indicator_id IN (".$data['mt'].")
							AND df.exception_status = ".INACTIVE."
							AND dfi.contract_id = '".$data['contract_id']."' ". $form_ex_str.
						" UNION 
						SELECT DISTINCT dfi.digital_form_indicator_id dfid, menu_type mt, menu_type_description mtd
						FROM digital_form_indicator dfi
						INNER JOIN digital_dinner_form_indicator dlfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
						INNER JOIN digital_dinner_form dlf ON dlf.digital_dinner_form_id = dlfi.digital_dinner_form_id
						INNER JOIN digital_form df ON df.digital_form_id = dlf.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						".$digital_sub_query."
						WHERE date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."'
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")
							AND dlfi.digital_form_indicator_id IN (".$data['mt'].")
							AND df.exception_status = ".INACTIVE."
							AND dfi.contract_id = '".$data['contract_id']."' ". $form_ex_str;
			
			//echo $digital_form_query_str;
			$digital_form_query= $this->db->query($digital_form_query_str);
			$digital_form_data = $digital_form_query->result();
		} 
		else if($data['ft'] == LUNCH_FORM_TYPE_ID){  //Lunch

			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digital_sub_query = "SELECT dl.digital_form_id FROM
					digital_lunch_form dl 
					LEFT JOIN digital_lunch_form_indicator dlfi ON dl.digital_lunch_form_id = dlfi.digital_lunch_form_id 
					WHERE dl.digital_form_ward_id_up IN (".$data['w'].") 
							AND digital_form_dayofweek_id IN (".$data['dw'].") 
							AND dlfi.digital_form_indicator_id IS NULL"; 
			}

			//echo $digital_sub_query;

			$digital_sub_query = ($digital_sub_query != "") ? " INNER JOIN (".$digital_sub_query.") a ON a.digital_form_id = df.digital_form_id " : "";
			
			$digital_form_query_str = "SELECT DISTINCT dfi.digital_form_indicator_id dfid, menu_type mt, menu_type_description mtd
						FROM digital_form_indicator dfi
						INNER JOIN digital_lunch_form_indicator dlfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
						INNER JOIN digital_lunch_form dlf ON dlf.digital_lunch_form_id = dlfi.digital_lunch_form_id
						INNER JOIN digital_form df ON df.digital_form_id = dlf.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						".$digital_sub_query."
						WHERE date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."'
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")
							AND dlfi.digital_form_indicator_id IN (".$data['mt'].")
							AND df.exception_status = ".INACTIVE."
							AND dfi.contract_id = '".$data['contract_id']."' ". $form_ex_str;
			
			//echo $digital_form_query_str;
			$digital_form_query= $this->db->query($digital_form_query_str);
			$digital_form_data = $digital_form_query->result();
		
		}
		else if($data['ft'] == DINNER_FORM_TYPE_ID){  //Dinner

			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digital_sub_query = "SELECT dl.digital_form_id FROM
					digital_dinner_form dl
					LEFT JOIN digital_dinner_form_indicator dlfi ON dl.digital_dinner_form_id = dlfi.digital_dinner_form_id
					WHERE dl.digital_form_ward_id_up IN (".$data['w'].")
							AND digital_form_dayofweek_id IN (".$data['dw'].")
							AND dlfi.digital_form_indicator_id IS NULL";
			}
			
			//echo $digital_sub_query;
			
			$digital_sub_query = ($digital_sub_query != "") ? " INNER JOIN (".$digital_sub_query.") a ON a.digital_form_id = df.digital_form_id " : "";
				
			$digital_form_query_str = "SELECT DISTINCT dfi.digital_form_indicator_id dfid, menu_type mt, menu_type_description mtd
						FROM digital_form_indicator dfi
						INNER JOIN digital_dinner_form_indicator dlfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
						INNER JOIN digital_dinner_form dlf ON dlf.digital_dinner_form_id = dlfi.digital_dinner_form_id
						INNER JOIN digital_form df ON df.digital_form_id = dlf.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						".$digital_sub_query."
						WHERE date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."'
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")
							AND dlfi.digital_form_indicator_id IN (".$data['mt'].")
							AND df.exception_status = ".INACTIVE."
							AND dfi.contract_id = '".$data['contract_id']."' ". $form_ex_str;
				
			//echo $digital_form_query_str;
			$digital_form_query= $this->db->query($digital_form_query_str);
			$digital_form_data = $digital_form_query->result();
		}
		else if($data['ft'] == ADHOC_FORM_TYPE_ID){  //ad-hoc
				
			$digital_form_data = array();
		}
		
		$c = count($digital_form_data);
		$digital_form_data[$c] = new stdClass();
		$digital_form_data[$c] ->dfid = 0;
		$digital_form_data[$c] ->mt= "No Indicators";
		$digital_form_data[$c] ->mtd = "";
		
		
// 		$digital_form_ind_str = "SELECT digital_form_indicator_id mtid, menu_type mttype, menu_type_description mtd FROM digital_form_indicator WHERE contract_id='".$data['contract_id']."'";
// 		$digital_form_ind_query= $this->db->query($digital_form_ind_str);
// 		$digital_form_ind_data = $digital_form_ind_query->result();
		
		
		
		//for Total daily meal filter .. this will return the total indicators.
		return $digital_form_data;
	}
	
	public function validate_digital_form($data)
	{
		$this->db->select('COUNT(1) AS cnt');
		$this->db->from('digital_form df');
		$this->db->join('digital_app da', 'da.digital_app_id = df.digital_app_id');
		$this->db->where('digital_form_id', $data['dfid']);
		$this->db->where('da.contract_id', $data['contract_id']);
		
		if($data['ft'] === 'L')
			$this->db->where('df.digital_form_type_id', DIGITAL_FORM_LUNCH_TYPE_ID);
		else if($data['ft'] === 'D')
			$this->db->where('df.digital_form_type_id', DIGITAL_FORM_DINNER_TYPE_ID);
		else if($data['ft'] === 'A')
			$this->db->where('df.digital_form_type_id', DIGITAL_FORM_ADHOC_TYPE_ID);
		else
			$this->db->where('df.digital_form_type_id', DIGITAL_FORM_OTHER_TYPE_ID);
		
		$query = $this->db->get();
		$chk_form_details= $query->result();
		
		return $chk_form_details[0]->cnt;
	}
	
	public function get_digital_form_late_number_details($data)
	{
		$this->db->select('digital_form_ward_id AS wid, digital_form_ward_name AS wn');
		$this->db->from('digital_form_ward');
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_ward_details= $query->result();
		
		$res_form_details = array();
		if($data['ft'] == DIGITAL_LUNCH_FORM_TYPE_DEF)
		{
			$this->db->select('dlf.digital_form_ward_id_up AS dfwid');
			$this->db->from('digital_lunch_form dlf');
			$this->db->join('digital_form df', 'df.digital_form_id = dlf.digital_form_id');
			$this->db->join('digital_app da', 'da.digital_app_id = df.digital_app_id');
			//$this->db->where('dlf.is_custom', INACTIVE);
			$this->db->where('df.digital_form_id', $data['dfid']);
			$this->db->where('da.contract_id', $data['contract_id']);

			$query = $this->db->get();
			$res_select_ward_details= $query->result();
			
			$this->db->distinct();
			$this->db->select('dlf.digital_lunch_form_id AS ldfid, dlf.menu_description AS md, dfi.menu_type AS mt, dlf.menu_quantitiy AS oln, dlf.late_numbers AS nln');
			$this->db->from('digital_lunch_form dlf');
			$this->db->join('digital_lunch_form_indicator dlfi', 'dlfi.digital_lunch_form_id = dlf.digital_lunch_form_id', 'LEFT');
			$this->db->join('digital_form_indicator dfi', 'dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id', 'LEFT');
			$this->db->join('digital_form df', 'df.digital_form_id = dlf.digital_form_id');
			$this->db->join('digital_app da', 'da.digital_app_id = df.digital_app_id');
			//$this->db->where('dlf.is_custom', INACTIVE);
			$this->db->where('df.digital_form_id', $data['dfid']);
			$this->db->where('da.contract_id', $data['contract_id']);
			
			$query = $this->db->get();
			$res_form_details= $query->result();
		}
		
		if($data['ft'] == DIGITAL_DINNER_FORM_TYPE_DEF)
		{
			$this->db->select('ddf.digital_form_ward_id_up AS dfwid');
			$this->db->from('digital_dinner_form ddf');
			$this->db->join('digital_form df', 'df.digital_form_id = ddf.digital_form_id');
			$this->db->join('digital_app da', 'da.digital_app_id = df.digital_app_id');
			//$this->db->where('ddf.is_custom', INACTIVE);
			$this->db->where('df.digital_form_id', $data['dfid']);
			$this->db->where('da.contract_id', $data['contract_id']);

			$query = $this->db->get();
			$res_select_ward_details= $query->result();
			
			$this->db->distinct();
			$this->db->select('ddf.digital_dinner_form_id AS ldfid, ddf.menu_description AS md, dfi.menu_type AS mt, ddf.menu_quantitiy AS oln, ddf.late_numbers AS nln');
			$this->db->from('digital_dinner_form ddf');
			$this->db->join('digital_dinner_form_indicator ddfi', 'ddfi.digital_dinner_form_id = ddf.digital_dinner_form_id', 'LEFT');
			$this->db->join('digital_form_indicator dfi', 'dfi.digital_form_indicator_id = ddfi.digital_form_indicator_id', 'LEFT');
			$this->db->join('digital_form df', 'df.digital_form_id = ddf.digital_form_id');
			$this->db->join('digital_app da', 'da.digital_app_id = df.digital_app_id');
			//$this->db->where('ddf.is_custom', INACTIVE);
			$this->db->where('df.digital_form_id', $data['dfid']);
			$this->db->where('da.contract_id', $data['contract_id']);
			
			$query = $this->db->get();
			$res_form_details= $query->result();
			
		}
		
		$form_res = new stdClass();
		$form_res->ward_details = $res_ward_details;
		
		if(isset($res_select_ward_details))
		{
			if(count($res_select_ward_details) > 0)
			{
				$form_res->df_ward = $res_select_ward_details[0]->dfwid;
			}
		}
		$form_res->form_details = $res_form_details;
		$form_res->error = FALSE;
		$form_res->session_status = TRUE;
		return $form_res;
	}
	
	public function validate_digital_form_ward_id($data)
	{
		$this->db->select('COUNT(1) AS cnt');
		$this->db->from('digital_form_ward');
		$this->db->where('digital_form_ward_id', $data['wid']);
		$this->db->where('contract_id', $data['contract_id']);
		
		$query = $this->db->get();
		$chk_form_details= $query->result();
		
		return $chk_form_details[0]->cnt;
	}
	
	public function save_digital_form_late_numbers($data)
	{
		if($data['ft'] == DIGITAL_LUNCH_FORM_TYPE_DEF)
		{
			$ward_data = array(
					'digital_form_ward_id_up' => $data['wid']
			);
			$this->db->where('digital_form_id',$data['dfid']);
			$this->db->update('digital_lunch_form',$ward_data);
			
			if(isset($data['late_details']) && count($data['late_details']) > 0)
			{
				foreach($data['late_details'] as $key => $value)
				{
					$late_data = array(
										'late_numbers' => $value['nln']
									);
					$this->db->where('digital_form_id',$data['dfid']);
					$this->db->where('digital_lunch_form_id', $value['ldfid']);
					//$this->db->where('is_custom', INACTIVE);
					$this->db->update('digital_lunch_form',$late_data);
				}
			}
		}
		
		if($data['ft'] == DIGITAL_DINNER_FORM_TYPE_DEF)
		{
			$ward_data = array(
					'digital_form_ward_id_up' => $data['wid']
			);
			$this->db->where('digital_form_id',$data['dfid']);
			$this->db->update('digital_dinner_form',$ward_data);
			
			if(isset($data['late_details']) && count($data['late_details']) > 0)
			{
				foreach($data['late_details'] as $key => $value)
				{
					$late_data = array(
										'late_numbers' => $value['nln']
					);
					$this->db->where('digital_form_id',$data['dfid']);
					$this->db->where('digital_dinner_form_id', $value['ldfid']);
					//$this->db->where('is_custom', INACTIVE);
					$this->db->update('digital_dinner_form',$late_data);
				}
			}
		}
		return TRUE;
	}
	
	/* Get digital form exception details */
	public function get_digital_form_exception_details($data)
	{
		$this->db->select('exception_status AS exp_status, exception_reason AS exp_reason');
		$this->db->from('digital_form');
		$this->db->where('digital_form_id', $data['dfid']);
		
		if($data['ft'] == DIGITAL_LUNCH_FORM_TYPE_DEF)
			$this->db->where('digital_form_type_id', DIGITAL_FORM_LUNCH_TYPE_ID);
			
		else if($data['ft'] == DIGITAL_DINNER_FORM_TYPE_DEF)
			$this->db->where('digital_form_type_id', DIGITAL_FORM_DINNER_TYPE_ID);
		
		else if($data['ft'] == DIGITAL_ADHOC_FORM_TYPE_DEF)
			$this->db->where('digital_form_type_id', DIGITAL_FORM_ADHOC_TYPE_ID);
			
		else
			$this->db->where('digital_form_type_id', DIGITAL_FORM_OTHER_TYPE_ID);
		
		$query = $this->db->get();
		$exception_details= $query->result();
		
		$exp_res->exception_details = $exception_details[0];
		$exp_res->error = FALSE;
		$exp_res->session_status = TRUE;
		return $exp_res;
	}
	
	/* Save digital form excetpion details */
	public function save_digital_form_exception_details($data)
	{
		$exp_data = array(
							'exception_status' => $data['exp_status'],
							'exception_reason' => $data['exp_reason']
						);
		$this->db->where('digital_form_id',$data['dfid']);
		
		if($data['ft'] == DIGITAL_LUNCH_FORM_TYPE_DEF)
			$this->db->where('digital_form_type_id', DIGITAL_FORM_LUNCH_TYPE_ID);
			
		else if($data['ft'] == DIGITAL_DINNER_FORM_TYPE_DEF)
			$this->db->where('digital_form_type_id', DIGITAL_FORM_DINNER_TYPE_ID);
		
		else if($data['ft'] == DIGITAL_ADHOC_FORM_TYPE_DEF)
			$this->db->where('digital_form_type_id', DIGITAL_FORM_ADHOC_TYPE_ID);
			
		else
			$this->db->where('digital_form_type_id', DIGITAL_FORM_OTHER_TYPE_ID);
			
		$res = $this->db->update('digital_form',$exp_data);
		return $res;
	}
	
	public function export_digital_forms($data){
		
		$form_ex_str = ($data['isex'] == "0") ? " AND (df.exception_status = 0 or df.exception_status = 2)" : "";
		
		$export_obj = new stdClass();
		
		//Get the navigation location Id
		$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
		$patient_cat_query= $this->db->query($patient_cat_str);
		$patient_cat_data = $patient_cat_query->result();
		
		$digital_lunch_form_indicator_cond_str = "";
		$digital_dinner_form_indicator_cond_str = "";
		$digital_lunch_join_str = "";
		$digital_dinner_join_str = "";
		// if the selected menu type contains No Menu type
		if(strpos(",".$data['mt'].",",",0,") !== false){
			
			//if day of week contains no menu type and form type contains lunch then select lunch forms having no menu type.
			if(strpos(",".$data['ft'].",",",".LUNCH_FORM_TYPE_ID.",") !== false)
			{
				$digital_lunch_form_indicator_cond_str = "AND dfli.digital_lunch_form_id IS NULL ";
				$digital_lunch_join_str = "LEFT JOIN digital_lunch_form_indicator dfli ON dfli.digital_lunch_form_id = dlf.digital_lunch_form_id  ";
			}
			else
			{
				$digital_lunch_join_str = "LEFT JOIN digital_lunch_form_indicator dfli ON dfli.digital_lunch_form_id = dlf.digital_lunch_form_id  ";
			}
			
			//if day of week contains no menu type and form type contains dinner then select dinner forms having no menu type.
			if(strpos(",".$data['ft'].",",",".DINNER_FORM_TYPE_ID.",") !== false)
			{
				$digital_dinner_form_indicator_cond_str = "AND dfdi.digital_dinner_form_id IS NULL ";
				$digital_dinner_join_str = "LEFT JOIN digital_dinner_form_indicator dfdi ON dfdi.digital_dinner_form_id = ddf.digital_dinner_form_id ";
			}
			else
			{
				$digital_dinner_join_str = "LEFT JOIN digital_dinner_form_indicator dfdi ON dfdi.digital_dinner_form_id = ddf.digital_dinner_form_id ";
			}
		}	
		else if($data['mt'] != "0")
		{
			if(strpos(",".$data['ft'].",",",".LUNCH_FORM_TYPE_ID.",") !== false)
			{
				if($digital_lunch_form_indicator_cond_str == "")
					$digital_lunch_form_indicator_cond_str = $digital_lunch_form_indicator_cond_str . "AND dfli.digital_form_indicator_id IN (". $data['mt'] .") ";
				else
					$digital_lunch_form_indicator_cond_str = "AND (dfli.digital_lunch_form_id IS NULL OR dfli.digital_form_indicator_id IN (". $data['mt'] .")) ";
				
				$digital_lunch_join_str = "INNER JOIN digital_lunch_form_indicator dfli ON dfli.digital_lunch_form_id = dlf.digital_lunch_form_id  ";
			}
			
			if(strpos(",".$data['ft'].",",",".DINNER_FORM_TYPE_ID.",") !== false)
			{
				if($digital_dinner_form_indicator_cond_str == "")
					$digital_dinner_form_indicator_cond_str = $digital_dinner_form_indicator_cond_str ."AND dfdi.digital_form_indicator_id IN (". $data['mt'] .") ";
				else
					$digital_dinner_form_indicator_cond_str = "AND (dfdi.digital_dinner_form_id IS NULL OR dfdi.digital_form_indicator_id IN (". $data['mt'] .")) ";
				
				$digital_dinner_join_str = "INNER JOIN digital_dinner_form_indicator dfdi ON dfdi.digital_dinner_form_id = ddf.digital_dinner_form_id  ";
			}	
		}
		else 
		{
			$digital_lunch_join_str = "LEFT JOIN digital_lunch_form_indicator dfli ON dfli.digital_lunch_form_id = dlf.digital_lunch_form_id  ";
			$digital_dinner_join_str = "LEFT JOIN digital_dinner_form_indicator dfdi ON dfdi.digital_dinner_form_id = ddf.digital_dinner_form_id ";
		}
		
		$res_dayofweek = array();
		$dayofweek_qry = "SELECT distinct dow_id, dayofweek 
								FROM ((SELECT dw.digital_form_dayofweek_id AS dow_id, digital_form_dayofweek_name AS dayofweek FROM digital_form_dayofweek dw 
								LEFT JOIN digital_lunch_form dlf ON dlf.digital_form_dayofweek_id = dw.digital_form_dayofweek_id 
								INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id
									WHERE date(server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND dlf.is_custom = ". INACTIVE ."
										". $form_ex_str ."
								group by digital_form_dayofweek_name)
							UNION ALL
								(SELECT dw.digital_form_dayofweek_id AS dow_id, digital_form_dayofweek_name AS dayofweek FROM digital_form_dayofweek dw 
								LEFT JOIN digital_dinner_form ddf ON ddf.digital_form_dayofweek_id = dw.digital_form_dayofweek_id 
								INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id
									WHERE date(server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
									AND da.contract_id = ". $data['contract_id'] ."
									AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
									AND df.digital_form_type_id IN (". $data['ft'] .")
									AND digital_form_ward_id_up IN (". $data['w'] .")
									AND dp.digital_pen_id IN (". $data['p'] .")
									AND ddf.digital_form_dayofweek_id IN (". $data['dw'] .")
									AND ddf.is_custom = ". INACTIVE ."
									". $form_ex_str ."
							group by digital_form_dayofweek_name)) t order by dow_id";
		
		$query = $this->db->query($dayofweek_qry);
		$res_dayofweek = $query->result();
		
		$no_of_days = array();
		$menus = new stdClass();
		$menu_quan_arr = array();
		
		foreach($res_dayofweek as $key => $value)
		{
			$menu_qry = "SELECT DISTINCT menu, menu_description, dfid
							FROM ((SELECT menu_description, digital_lunch_form_indicator_id AS dfid, dlf.menu_description AS menu
									FROM 
										digital_lunch_form dlf 
										LEFT JOIN digital_lunch_form_indicator dfli ON dfli.digital_lunch_form_id = dlf.digital_lunch_form_id 
										INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfli.digital_form_indicator_id ". $digital_lunch_form_indicator_cond_str ."
									WHERE 
										dlf.digital_form_dayofweek_id = ". $value->dow_id ."
										AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.is_custom = ". INACTIVE ."
										". $form_ex_str ."
									group by dlf.menu_description)
							UNION ALL
								(SELECT menu_description, digital_dinner_form_indicator_id AS dfid, ddf.menu_description AS menu
									FROM 
										digital_dinner_form ddf 
										LEFT JOIN digital_dinner_form_indicator dfdi ON dfdi.digital_dinner_form_id = ddf.digital_dinner_form_id 
										INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfdi.digital_form_indicator_id ". $digital_dinner_form_indicator_cond_str ."
									WHERE 
										ddf.digital_form_dayofweek_id = ". $value->dow_id ."
										AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND ddf.is_custom = ". INACTIVE ."
										". $form_ex_str ."										
									group by ddf.menu_description)) t";
			
			$query = $this->db->query($menu_qry);
			$res_menu = $query->result();
			
			$all_menu_qry = "SELECT DISTINCT digital_form_id
							FROM ((SELECT df.digital_form_id
									FROM 
										digital_lunch_form dlf ". $digital_lunch_join_str ."
										INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfli.digital_form_indicator_id ". $digital_lunch_form_indicator_cond_str ."
									WHERE 
										dlf.digital_form_dayofweek_id = ". $value->dow_id ."
										AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.is_custom = ". INACTIVE ."
										". $form_ex_str ."
										
									group by df.digital_form_id, dlf.menu_description)
							UNION ALL
								(SELECT df.digital_form_id
									FROM 
										digital_dinner_form ddf ". $digital_dinner_join_str ."
										INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfdi.digital_form_indicator_id ". $digital_dinner_form_indicator_cond_str ."
									WHERE 
										ddf.digital_form_dayofweek_id = ". $value->dow_id ."
										AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND ddf.is_custom = ". INACTIVE ."
										". $form_ex_str ."
										
									group by df.digital_form_id, ddf.menu_description)) t";
			
			$query = $this->db->query($all_menu_qry);
			$res_all_menu = $query->result();
			
			$digital_form_id_str = "";
			foreach($res_all_menu as $r => $s)
			{  
				if($digital_form_id_str == "")
					$digital_form_id_str = $s->digital_form_id;
				else
					$digital_form_id_str = $digital_form_id_str .", " . $s->digital_form_id;
			}
			
			$menu_key = $value->dayofweek;
                        if($menu_key == "")
                               $menu_key = 0;
                        $menus->$menu_key = $res_menu;
                        
			$day_qry = "SELECT DISTINCT date_for, d, dow
							FROM ((SELECT DATE_FORMAT(df.server_received, '%eth %b %Y') date_for, DATE_FORMAT(df.server_received, '%Y-%m-%d') d, '". $value->dayofweek ."' AS dow FROM digital_form df
								INNER JOIN digital_lunch_form dlf ON dlf.digital_form_id = df.digital_form_id 
								". $digital_lunch_join_str ."
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
								LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfli.digital_form_indicator_id ". $digital_lunch_form_indicator_cond_str ."
								WHERE 
									dlf.digital_form_dayofweek_id = ". $value->dow_id ."
									AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
									AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
									AND da.contract_id = ". $data['contract_id'] ."
									AND df.digital_form_type_id IN (". $data['ft'] .")
									AND digital_form_ward_id_up IN (". $data['w'] .")
									AND dp.digital_pen_id IN (". $data['p'] .")
									AND dlf.is_custom = ". INACTIVE ."
									". $form_ex_str ."
								group by date(df.server_received))
						UNION ALL
							(SELECT DATE_FORMAT(df.server_received, '%eth %b %Y') date_for, DATE_FORMAT(df.server_received, '%Y-%m-%d') d, '". $value->dayofweek ."' AS dow FROM digital_form df
								INNER JOIN digital_dinner_form ddf ON ddf.digital_form_id = df.digital_form_id 
								". $digital_dinner_join_str ."
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
								LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfdi.digital_form_indicator_id ". $digital_dinner_form_indicator_cond_str ."
								WHERE 
									ddf.digital_form_dayofweek_id = ". $value->dow_id ."
									AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
									AND da.contract_id = ". $data['contract_id'] ."
									AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
									AND df.digital_form_type_id IN (". $data['ft'] .")
									AND digital_form_ward_id_up IN (". $data['w'] .")
									AND dp.digital_pen_id IN (". $data['p'] .")
									AND ddf.is_custom = ". INACTIVE ."
									". $form_ex_str ."
								group by date(df.server_received))) t
								order by d";
			
			$query = $this->db->query($day_qry);
			$res_days = $query->result();
			$no_of_days[] = $res_days;
			
			if(count($res_menu) > 0 && count($res_days) > 0)
			{
				foreach($res_menu as $m => $n)
				{
					if($n->dfid == "")
						$n->dfid = NULL;
						
					$dw_quan = array();
					foreach($res_days as $p => $q)
					{
						$quan_qry = "SELECT day, dow, menu, SUM(ln) AS ln
								FROM ((SELECT '". $q->date_for ."' AS day, '". $value->dayofweek ."' AS dow,
											'" . $n->menu ."' AS menu,
											COALESCE(SUM(dlf.late_numbers),0) AS ln 
											FROM 
												digital_lunch_form dlf LEFT JOIN digital_lunch_form_indicator dfli ON dfli.digital_lunch_form_id = dlf.digital_lunch_form_id AND dfli.digital_lunch_form_indicator_id = '". $n->dfid ."' 
												LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfli.digital_form_indicator_id 
												INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
												INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id 
												INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id  
											WHERE  
												date(df.server_received) = '". $q->d ."' 
												AND df.digital_form_id IN (". $digital_form_id_str .") 
												AND dlf.digital_form_dayofweek_id = ". $value->dow_id ." 
												AND dlf.menu_description = '". $n->menu ."' 
												AND da.navigation_location='".$patient_cat_data[0]->s_module_id."' 
												AND da.contract_id = ". $data['contract_id'] ." 
												AND df.digital_form_type_id IN (". $data['ft'] .")
												AND digital_form_ward_id_up IN (". $data['w'] .") 
												AND dp.digital_pen_id IN (". $data['p'] .") 
												AND dlf.is_custom = ". INACTIVE ." 
												". $form_ex_str .") 
								UNION ALL 
									(SELECT '". $q->date_for ."' AS day, '". $value->dayofweek ."' AS dow, 
											'" . $n->menu ."' AS menu, 
											COALESCE(SUM(ddf.late_numbers),0) AS ln  
										FROM  
											digital_dinner_form ddf LEFT JOIN digital_dinner_form_indicator dfdi ON dfdi.digital_dinner_form_id = ddf.digital_dinner_form_id AND dfdi.digital_dinner_form_indicator_id = '". $n->dfid ."' 
											LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfdi.digital_form_indicator_id 
											INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
											INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id 
											INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										WHERE  
											date(df.server_received) = '". $q->d ."' 
											AND df.digital_form_id IN (". $digital_form_id_str .") 
											AND ddf.digital_form_dayofweek_id = ". $value->dow_id ." 
											AND ddf.menu_description = '". $n->menu ."' 
											AND da.contract_id = ". $data['contract_id'] ." 
											AND da.navigation_location='".$patient_cat_data[0]->s_module_id."' 
											AND df.digital_form_type_id IN (". $data['ft'] .")
											AND digital_form_ward_id_up IN (". $data['w'] .") 
											AND dp.digital_pen_id IN (". $data['p'] .") 
											AND ddf.is_custom = ". INACTIVE ." 
											". $form_ex_str .")) t 
										group by menu";
						
						$query = $this->db->query($quan_qry);
						$res_quantity = $query->result();
							
						$dw_quan[] = $res_quantity[0];
					}
					$menu_quan_arr[] = $dw_quan;
				}
			}
		}
		
		$custom_day_qry = "SELECT DISTINCT date_for, d
							FROM ((SELECT DATE_FORMAT(df.server_received, '%eth %b %Y') date_for, DATE_FORMAT(df.server_received, '%Y-%m-%d') d FROM digital_form df
								INNER JOIN digital_lunch_form dlf ON dlf.digital_form_id = df.digital_form_id 
								". $digital_lunch_join_str ."
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
								LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfli.digital_form_indicator_id ". $digital_lunch_form_indicator_cond_str ."
								WHERE 
									dlf.digital_form_dayofweek_id IN (". $data['dw'] .")
									AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
									AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
									AND da.contract_id = ". $data['contract_id'] ."
									AND df.digital_form_type_id IN (". $data['ft'] .")
									AND digital_form_ward_id_up IN (". $data['w'] .")
									AND dp.digital_pen_id IN (". $data['p'] .")
									AND dlf.is_custom = ". INACTIVE ."
									". $form_ex_str ."
								group by date(df.server_received))
						UNION ALL
							(SELECT DATE_FORMAT(df.server_received, '%eth %b %Y') date_for, DATE_FORMAT(df.server_received, '%Y-%m-%d') d FROM digital_form df
								INNER JOIN digital_dinner_form ddf ON ddf.digital_form_id = df.digital_form_id 
								". $digital_dinner_join_str ."
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
								LEFT JOIN digital_form_indicator dfi ON dfi.digital_form_indicator_id = dfdi.digital_form_indicator_id ". $digital_dinner_form_indicator_cond_str ."
								WHERE 
									ddf.digital_form_dayofweek_id IN (". $data['dw'] .")
									AND date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
									AND da.contract_id = ". $data['contract_id'] ."
									AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
									AND df.digital_form_type_id IN (". $data['ft'] .")
									AND digital_form_ward_id_up IN (". $data['w'] .")
									AND dp.digital_pen_id IN (". $data['p'] .")
									AND ddf.is_custom = ". INACTIVE ."
									". $form_ex_str ."
								group by date(df.server_received))) t
								order by d";
		
		$query = $this->db->query($custom_day_qry);
		$custom_res_days = $query->result();
		
		$res_custom = array();
		if(count($custom_res_days) > 0)
		{
			$custom_qry = "SELECT wn, dayofweek, date_rec, ln, meal_order, comp
							FROM ((SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(dlf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_lunch_form dlf										
										INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = dlf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = dlf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND dlf.is_custom = ". ACTIVE ."
										". $form_ex_str ."
									group by date(df.server_received), dlf.digital_form_dayofweek_id, dfw.digital_form_ward_id
									order by date(df.server_received))
						UNION ALL
								(SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(ddf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_dinner_form ddf
										INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = ddf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = ddf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". $data['ft'] .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND ddf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND ddf.is_custom = ". ACTIVE ."
										". $form_ex_str ."
								group by date(df.server_received), ddf.digital_form_dayofweek_id, dfw.digital_form_ward_id
								order by date(df.server_received))) T";

			$query = $this->db->query($custom_qry);
			$res_custom = $query->result();
		}
		
		$res_adhoc = array();
		if(strpos(",".$data['ft'].",",",3,") !== false)
		{
			$adhoc_qry = "SELECT digital_form_ward_name AS wn, DATE_FORMAT(df.server_received, '%eth %b %Y') AS date_rec, adhoc_date AS ad, DATE_FORMAT(menu_time, '%H:%i') AS mt, cost_type AS ct, daf.menu_description AS md
							FROM digital_adhoc_form daf
								INNER JOIN digital_form df ON daf.digital_form_id = df.digital_form_id
								INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
								INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id
								INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = daf.digital_form_ward_id
							WHERE
								date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
								AND da.navigation_location= '".$patient_cat_data[0]->s_module_id."'
								AND da.contract_id = ". $data['contract_id'] ."
								AND df.digital_form_type_id = ". ADHOC_FORM_TYPE_ID ."
								AND daf.digital_form_ward_id IN (". $data['w'] .")
								AND dp.digital_pen_id IN (". $data['p'] .")
							order by date(df.server_received)";
			
			$query = $this->db->query($adhoc_qry);
			$res_adhoc = $query->result();
		}
		
		$export_obj->week = $res_dayofweek;
		$export_obj->days = $no_of_days;
		$export_obj->menu_arr = $menus;
		$export_obj->menu_quan = $menu_quan_arr;
		$export_obj->custom_orders = $res_custom;
		$export_obj->adhoc_arr = $res_adhoc;
		
		return $export_obj;
	}
	
	public function get_df_indicators($data)
	{
		$this->db->select('digital_form_indicator_id dfi_id, menu_type mt, menu_type_description mtd');
		$this->db->from('digital_form_indicator');
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_df_indicators = $query->result();
		return $res_df_indicators;
	}
	
	public function get_df_indicator_desc($data)
	{
		$qry = "SELECT CONCAT('[', menu_type, ']') mt, menu_type_description mt_desc FROM digital_form_indicator WHERE digital_form_indicator_id = ". $data['dfi_id'] ." and contract_id = ".$data['contract_id'];
		
		$dfi_query= $this->db->query($qry);
		$res_df_indicators = $dfi_query->result();
	
		return $res_df_indicators;
	}
	
	public function update_df_indicator_desc($data)
	{
		$this->db->set('menu_type_description', $data['dfi_desc']);
		$this->db->set('mdate','NOW()', FALSE);
		$this->db->set('muser_id', $data['muser_id']);
		$this->db->where('digital_form_indicator_id', $data['dfi_id']);
		$this->db->where('contract_id', $data['contract_id']);
		$this->db->update('digital_form_indicator');
	}
	
	public function get_df_tdm_numbers($data)
	{
		//Get the navigation location Id
		$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
		$patient_cat_query= $this->db->query($patient_cat_str);
		$patient_cat_data = $patient_cat_query->result();
		
		//Return empty result if
		//Pens are blank or wards are blank or
		//Lunch&Dinner and day of week is blank or "No day of week" selected or Lunch and day of week is "No day of week" selected or dinner and day of week is "No day of week" selected
		//Adhoc "No Menu type" is not selected.
		if($data['p'] == "" || 
			$data['w'] == "" ||
			($data['ft'] == 0 && ($data['dw']=="" || $data['dw']=="0")) ||
			($data['ft'] == LUNCH_FORM_TYPE_ID && ($data['dw']=="" || $data['dw']=="0")) ||
			($data['ft'] == DINNER_FORM_TYPE_ID && ($data['dw']=="" || $data['dw']=="0")) || 
			($data['ft'] == ADHOC_FORM_TYPE_ID && strpos(",".$data['mt'].",",",0,") === false))
		{
				return new stdClass();
		}
		
		$form_ex_str = "";
		//$form_ex_str = ($data['isex'] == "0") ? " AND (df.exception_status = 0 or df.exception_status = 2)" : "";
		
		$digital_custom_query_str = "";
		$digital_form_query_str = "";
		$digital_latenumbers_query_str = "";
		
		if($data['ft'] == 0) { //Lunch & Dinner
			$digtal_sub_mt_str = "";
			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digtal_sub_mt_str = "
						SELECT df.digital_form_id FROM digital_lunch_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_lunch_form_indicator dlfi on dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						WHERE dlfi.digital_lunch_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						UNION 
						SELECT df.digital_form_id FROM digital_dinner_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_dinner_form_indicator dlfi on dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						WHERE dlfi.digital_dinner_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION;
			}
			
			if($data['mt']  != "0"){
				$digtal_sub_mt_str = $digtal_sub_mt_str.  (($digtal_sub_mt_str != "") ? " UNION " : ""). "
						SELECT dl.digital_form_id
						FROM digital_lunch_form dl
						INNER JOIN digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].")
						UNION 
						SELECT dl.digital_form_id
						FROM digital_dinner_form dl
						INNER JOIN digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].")";
			}
			
			$join_type = " LEFT JOIN ";
			$sql_for_indicator = " IS NULL";
			
			if($data['dfi_id'] != 0) {
				$sql_for_indicator = " = ".$data['dfi_id']."";
				$join_type = " INNER JOIN ";
			}
 			$digtal_sub_mt_str = ($digtal_sub_mt_str != "") ? " INNER JOIN (".$digtal_sub_mt_str.") a ON a.digital_form_id = dlf.digital_form_id " : "";
			
// 			//For total indicator filtering...
// 			$digtal_sub_mt_str = "SELECT DISTINCT dlf.digital_lunch_form_id
// 						FROM digital_lunch_form dlf
// 						".$join_type." digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dlf.digital_lunch_form_id
// 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
// 						".$digtal_sub_mt_str."
// 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator." UNION
// 						SELECT DISTINCT dlf.digital_dinner_form_id
// 						FROM digital_dinner_form dlf
// 						".$join_type." digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dlf.digital_dinner_form_id
// 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
// 						".$digtal_sub_mt_str."
// 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator;
				
			//$digtal_sub_mt_str = ($digtal_sub_mt_str != "") ? " INNER JOIN (".$digtal_sub_mt_str.") a ON a.digital_form_id = dlf.digital_form_id " : "";
			
			$digital_form_query_str = "SELECT d, digital_form_ward_id_up, SUM(n) n, ldfid, ddfid
					FROM (SELECT b.d, dlf.digital_form_ward_id_up, SUM(late_numbers) n, b.es,
							CASE WHEN b.es = ".DIGITAL_FORM_APPROVE_EXCEPTION." THEN '' ELSE GROUP_CONCAT(DISTINCT dlf.digital_form_id) END AS ldfid,
							'' ddfid
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d, df.exception_status es FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_lunch_form_id
 						FROM digital_lunch_form dlf
 						".$join_type." digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dlf.digital_lunch_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_lunch_form_id = dlf.digital_lunch_form_id
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") 
						GROUP BY b.d, dlf.digital_form_ward_id_up, b.es
						UNION ALL
						SELECT b.d, dlf.digital_form_ward_id_up, SUM(late_numbers) n, b.es,
						''ldfid, 
						CASE WHEN b.es = ".DIGITAL_FORM_APPROVE_EXCEPTION." THEN '' ELSE GROUP_CONCAT(DISTINCT dlf.digital_form_id) END AS ddfid
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d, df.exception_status es FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_dinner_form_id
 						FROM digital_dinner_form dlf
 						".$join_type." digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dlf.digital_dinner_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_dinner_form_id = dlf.digital_dinner_form_id
 						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") GROUP BY b.d, dlf.digital_form_ward_id_up, b.es) a 
						GROUP BY a.d, a.digital_form_ward_id_up";
	
			//echo $digital_form_query_str;
							
			$digital_latenumbers_query_str = "SELECT d, digital_form_ward_id_up, SUM(n) n
					FROM (SELECT b.d, dlf.digital_form_ward_id_up, CASE WHEN late_numbers - menu_quantitiy = 0 THEN 0 ELSE 1 END as n 
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_lunch_form_id
 						FROM digital_lunch_form dlf
 						".$join_type." digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dlf.digital_lunch_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_lunch_form_id = dlf.digital_lunch_form_id
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") 
						UNION ALL
						SELECT b.d, dlf.digital_form_ward_id_up, CASE WHEN late_numbers - menu_quantitiy = 0 THEN 0 ELSE 1 END as n
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_dinner_form_id
 						FROM digital_dinner_form dlf
 						".$join_type." digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dlf.digital_dinner_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_dinner_form_id = dlf.digital_dinner_form_id
 						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ) a GROUP BY a.d, a.digital_form_ward_id_up";
			
			//echo $digital_latenumbers_query_str;
			
			$digital_custom_query_str = "SELECT SUM(n) n
					FROM (SELECT SUM(late_numbers) n
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.is_custom = ".ACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") 
						GROUP BY b.d, dlf.digital_form_ward_id_up
						UNION ALL
						SELECT SUM(late_numbers) n
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.is_custom = ".ACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")  GROUP BY b.d, dlf.digital_form_ward_id_up) a";			
			
		} 
		else if($data['ft'] == LUNCH_FORM_TYPE_ID){  // For lunch form
			$digtal_sub_mt_str = "";
				//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
				if(strpos(",".$data['mt'].",",",0,") !== false){
					$digtal_sub_mt_str = "
						SELECT df.digital_form_id FROM digital_lunch_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_lunch_form_indicator dlfi on dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						WHERE dlfi.digital_lunch_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION;
				}
				
				if($data['mt']  != "0"){
					$digtal_sub_mt_str = $digtal_sub_mt_str.  (($digtal_sub_mt_str != "") ? " UNION " : ""). "
						SELECT dl.digital_form_id
						FROM digital_lunch_form dl
						INNER JOIN digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].")";
				}
				
				$join_type = " LEFT JOIN ";
				$sql_for_indicator = " IS NULL";
				
				if($data['dfi_id'] != 0) {
					$sql_for_indicator = " = ".$data['dfi_id']."";
					$join_type = " INNER JOIN ";
				} 
					
				$digtal_sub_mt_str = ($digtal_sub_mt_str != "") ? " INNER JOIN (".$digtal_sub_mt_str.") a ON a.digital_form_id = dlf.digital_form_id " : "";

				$digital_form_query_str = "
						SELECT d, digital_form_ward_id_up, SUM(n) n, ldfid, ddfid
					FROM (SELECT b.d, dlf.digital_form_ward_id_up, SUM(late_numbers) n, b.es,
							CASE WHEN b.es = ".DIGITAL_FORM_APPROVE_EXCEPTION." THEN '' ELSE GROUP_CONCAT(DISTINCT dlf.digital_form_id) END AS ldfid,
							'' ddfid
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d, df.exception_status es FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_lunch_form_id
 						FROM digital_lunch_form dlf
 						".$join_type." digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dlf.digital_lunch_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_lunch_form_id = dlf.digital_lunch_form_id
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") 
							GROUP BY b.d, dlf.digital_form_ward_id_up, b.es) a GROUP BY a.d, a.digital_form_ward_id_up";
			
				$digital_latenumbers_query_str = "SELECT d, digital_form_ward_id_up, SUM(n) n
					FROM (SELECT b.d, dlf.digital_form_ward_id_up, CASE WHEN late_numbers - menu_quantitiy = 0 THEN 0 ELSE 1 END as n
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_lunch_form_id
 						FROM digital_lunch_form dlf
 						".$join_type." digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dlf.digital_lunch_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_lunch_form_id = dlf.digital_lunch_form_id
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ) a GROUP BY a.d, a.digital_form_ward_id_up";
			
			$digital_custom_query_str = "SELECT SUM(late_numbers) n
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.is_custom = ".ACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ";
			
			
		}
		else if($data['ft'] == DINNER_FORM_TYPE_ID){
			$digtal_sub_mt_str = "";
			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digtal_sub_mt_str = "
						SELECT df.digital_form_id FROM digital_dinner_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_dinner_form_indicator dlfi on dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						WHERE dlfi.digital_dinner_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION;
			}
			
			if($data['mt']  != "0"){
				$digtal_sub_mt_str = $digtal_sub_mt_str.  (($digtal_sub_mt_str != "") ? " UNION " : ""). "
						SELECT dl.digital_form_id
						FROM digital_dinner_form dl
						INNER JOIN digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].")";
			}
			
			$join_type = " LEFT JOIN ";
			$sql_for_indicator = " IS NULL";
			
			if($data['dfi_id'] != 0) {
				$sql_for_indicator = " = ".$data['dfi_id']."";
				$join_type = " INNER JOIN ";
			}
				
			$digtal_sub_mt_str = ($digtal_sub_mt_str != "") ? " INNER JOIN (".$digtal_sub_mt_str.") a ON a.digital_form_id = dlf.digital_form_id " : "";
			
		$digital_form_query_str = "
				SELECT d, digital_form_ward_id_up, SUM(n) n, ldfid, ddfid
					FROM (SELECT b.d, dlf.digital_form_ward_id_up, SUM(late_numbers) n, b.es,
							'' ldfid,
							CASE WHEN b.es = ".DIGITAL_FORM_APPROVE_EXCEPTION." THEN '' ELSE GROUP_CONCAT(DISTINCT dlf.digital_form_id) END AS ddfid
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d, df.exception_status es FROM digital_form df 
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_dinner_form_id
 						FROM digital_dinner_form dlf
 						".$join_type." digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dlf.digital_dinner_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_dinner_form_id = dlf.digital_dinner_form_id
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") 
							GROUP BY b.d, dlf.digital_form_ward_id_up, b.es ) a GROUP BY a.d, a.digital_form_ward_id_up";
			
			//echo $digital_form_query_str;
			
		$digital_latenumbers_query_str = "SELECT d, digital_form_ward_id_up, SUM(n) n
					FROM (SELECT b.d, dlf.digital_form_ward_id_up, CASE WHEN late_numbers - menu_quantitiy = 0 THEN 0 ELSE 1 END as n
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						INNER JOIN (SELECT DISTINCT dlf.digital_dinner_form_id
 						FROM digital_dinner_form dlf
 						".$join_type." digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dlf.digital_dinner_form_id
 						".$join_type." digital_form_indicator dfi ON dfi.digital_form_indicator_id = dlfi.digital_form_indicator_id
 						WHERE dlfi.digital_form_indicator_id ".$sql_for_indicator.") mt ON mt.digital_dinner_form_id = dlf.digital_dinner_form_id
 						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ) a GROUP BY a.d, a.digital_form_ward_id_up";
			
			$digital_custom_query_str = "SELECT SUM(late_numbers) n
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.is_custom = ".ACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ";
			
		}
		else if($data['ft'] == ADHOC_FORM_TYPE_ID){
			
			$digital_form_query_str = "SELECT b.d, dlf.digital_form_ward_id AS digital_form_ward_id_up, count(1) n, '' ldfid, '' ddfid
						FROM digital_adhoc_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) BETWEEN '".$data['start_date']."' AND '".$data['end_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.digital_form_ward_id IN (".$data['w'].")
							GROUP BY b.d, dlf.digital_form_ward_id";

			//echo $digital_form_query_str;
			
		}
		
		$digital_form_query_str = "SELECT DATE_ADD('".$data['start_date']."', INTERVAL n.id - 1 DAY) day, dfw.digital_form_ward_id wid, COALESCE(b.n,0) cnt, b.ldfid, b.ddfid
									FROM numbers n
									JOIN digital_form_ward dfw
									LEFT JOIN (".$digital_form_query_str.") b ON  DATE_ADD('".$data['start_date']."', INTERVAL n.id - 1 DAY)  = b.d
									AND b.digital_form_ward_id_up = dfw.digital_form_ward_id
									WHERE dfw.contract_id = '".$data['contract_id']."' AND dfw.digital_form_ward_id IN (".$data['w'].") AND DATE_ADD('".$data['start_date']."', INTERVAL n.id - 1 DAY)  <= '".$data['end_date']."'
									ORDER BY n.id, dfw.digital_form_ward_name";
			
	
		//echo $digital_form_query_str;
		$digital_form_query= $this->db->query($digital_form_query_str);
		$digital_form_data = $digital_form_query->result();
		
		//echo "<pre>";
		//print_r($digital_form_data);
		
		$digital_ward_query_str = "SELECT digital_form_ward_id wid, CASE WHEN digital_form_ward_name ='' THEN 'Blank' ELSE digital_form_ward_name END wn FROM digital_form_ward dfw WHERE dfw.contract_id = '".$data['contract_id']."' AND dfw.digital_form_ward_id IN (".$data['w'].") ORDER BY dfw.digital_form_ward_name";
		$digital_ward_query= $this->db->query($digital_ward_query_str);
		$digital_ward_data = $digital_ward_query->result();
		
		//To get report time
		$date_time_query_str = "SELECT DATE_FORMAT(NOW(), '%H:%i on %D %b %Y') AS rt";
		$date_time_query = $this->db->query($date_time_query_str);
		$date_time_data = $date_time_query->result();
		
		
		$digital_form_data_arr = array();
		$digital_multirows_arr = array();
		$k=0;
		for($i=0;$i< count($digital_form_data);){
			$tot_count = 0;
			$digital_form_data_arr[$k][0] = $digital_form_data[$i]->day;
			for($j=0;$j< count($digital_ward_data);$j++,$i++){
				$digital_form_data_arr[$k][$j+1] = $digital_form_data[$i]->cnt;
				$digital_ward_data[$j]->cnt = $digital_ward_data[$j]->cnt +$digital_form_data[$i]->cnt;  
				$tot_count = $tot_count + $digital_form_data[$i]->cnt;
				
				if(strpos($digital_form_data[$i]->ldfid, ',') !== false || strpos($digital_form_data[$i]->ddfid, ',') !== false)
					$digital_multirows_arr[$digital_form_data[$i]->day][$digital_ward_data[$j]->wid] = 1;
			}
			$digital_form_data_arr[$k][$j+1] = $tot_count;
			$k++;
		}
		
		//Need to get the date and time
		//Need to get the ward names in separate list
		//Need to count in separate list
		//Need to get the yellow colored rows in separated list
		//Need to get the red colord rows in separated list.
		
		$day_ward_ln_obj = new stdClass();
		$day_ward_ln_obj->rt = $date_time_data;
		$day_ward_ln_obj->wln= $digital_ward_data;
		$day_ward_ln_obj->tdm = $digital_form_data_arr;
		$day_ward_ln_obj->mr = $digital_multirows_arr;

		//For late numbers
		if($digital_latenumbers_query_str != ""){
		
			$digital_latenumbers_query_str = "SELECT d, digital_form_ward_id_up wid FROM (".$digital_latenumbers_query_str.") b WHERE n>0";
			$digital_latenumbers_query= $this->db->query($digital_latenumbers_query_str);
			$digital_latenumbers_data = $digital_latenumbers_query->result();
			//echo "<pre>";
			//print_r($digital_latenumbers_data);
			
			$digital_latenumbers_arr = array();
			for($i=0;$i< count($digital_latenumbers_data);$i++){
				$digital_latenumbers_arr[$digital_latenumbers_data[$i]->d][$digital_latenumbers_data[$i]->wid] = 1;
			}
			$day_ward_ln_obj->ln = $digital_latenumbers_arr;
		}
		
 		if($digital_custom_query_str != ""){
 		$digital_custom_query= $this->db->query($digital_custom_query_str);
 		$digital_custom_data = $digital_custom_query->result();
 			$day_ward_ln_obj->cus_cnt = $digital_custom_data[0]->n;
 		}
 		
		//echo "<pre>";
		//print_r($day_ward_ln_obj);
		return $day_ward_ln_obj;
	}
	
	public function export_digital_form_tdm_custom($data)
	{
		//$form_ex_str = ($data['isex'] == "0") ? " AND (df.exception_status = 0 or df.exception_status = 2)" : "";
		
		//Get the navigation location Id
		$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
		$patient_cat_query= $this->db->query($patient_cat_str);
		$patient_cat_data = $patient_cat_query->result();
		
		if($data['ft'] == LUNCH_FORM_TYPE_ID)		// Lunch
		{
			$custom_qry = "SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(dlf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_lunch_form dlf
										INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = dlf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = dlf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". LUNCH_FORM_TYPE_ID .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND dlf.is_custom = ". ACTIVE ."
										AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
									group by date(df.server_received), dlf.digital_form_dayofweek_id, dfw.digital_form_ward_id
									order by date(df.server_received)";
		}
		else if($data['ft'] == DINNER_FORM_TYPE_ID)		//Dinner
		{
			$custom_qry = "SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(ddf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_dinner_form ddf
										INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = ddf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = ddf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". DINNER_FORM_TYPE_ID .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND ddf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND ddf.is_custom = ". ACTIVE ."
										AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
								group by date(df.server_received), ddf.digital_form_dayofweek_id, dfw.digital_form_ward_id
								order by date(df.server_received)";
		}
		else		// Lunch & Dinner
		{
			$custom_qry = "SELECT wn, dayofweek, date_rec, ln, meal_order, comp
							FROM ((SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(dlf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_lunch_form dlf
										INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = dlf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = dlf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". LUNCH_FORM_TYPE_ID .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND dlf.is_custom = ". ACTIVE ."
										AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
									group by date(df.server_received), dlf.digital_form_dayofweek_id, dfw.digital_form_ward_id
									order by date(df.server_received))
						UNION ALL
								(SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(ddf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_dinner_form ddf
										INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = ddf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = ddf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) BETWEEN '". $data['start_date'] ."' AND '". $data['end_date'] ."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". DINNER_FORM_TYPE_ID.")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND ddf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND ddf.is_custom = ". ACTIVE ."
										AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
								group by date(df.server_received), ddf.digital_form_dayofweek_id, dfw.digital_form_ward_id
								order by date(df.server_received))) T order by date_rec";
		}
		
		$query = $this->db->query($custom_qry);
		$res_custom = $query->result();
		
		return $res_custom;
	}
	
	public function view_quality_auditor_load($data)
	{
		$account_data = array();
		$site_data_res = new stdClass();
		$area_data_res = new stdClass();
		$sub_area_res = new stdclass();
		$point_data_res = new stdclass();
		$auditor_data = array();
		
		$quality_audit_res = new stdClass();
		
		$account_str = "SELECT ac.qa_account_id ac_id, ac.account_name ac FROM qa_account ac
							INNER JOIN qa_user_access ua ON ua.qa_account_id = ac.qa_account_id
							WHERE ua.user_id = ". $data['user_id'] ."
								AND ua.run_adhoc_report = ". ACTIVE ."
								AND ac.contract_id = ". $data['contract_id'] ."
								AND ac.active = ". ACTIVE ." 
						ORDER BY account_name";

		$account_query= $this->db->query($account_str);
		$account_data = $account_query->result();
		
		$quality_audit_res->Account = $account_data;
		
		foreach($account_data as $key => $value)
		{
			$site_str = "SELECT s.qa_site_id st_id, site_name st FROM qa_site s
							INNER JOIN qa_account acc ON acc.qa_account_id = s.qa_account_id 
							WHERE acc.contract_id = ". $data['contract_id'] ."
								AND acc.qa_account_id = ". $value->ac_id ."
						order by s.site_name";
			
			$site_query= $this->db->query($site_str);
			$site_data = $site_query->result();
			
			if(count($site_data) > 0)
			{
				$acc_id = $value->ac_id;
				$site_data_res->$acc_id = $site_data;
			}
		}
		$quality_audit_res->Site = $site_data_res;
		
		foreach($site_data_res as $key => $value)
		{
			foreach($value as $ky => $val)
			{
				$area_str = "SELECT qa_area_id arn_id, area_name arn, s.site_name h1 FROM qa_area a
								INNER JOIN qa_site s ON s.qa_site_id = a.qa_site_id
								INNER JOIN qa_account acc ON acc.qa_account_id = s.qa_account_id 
							WHERE acc.contract_id = ". $data['contract_id'] ."
							AND a.qa_site_id = $val->st_id
							order by a.area_name";
				
				$area_query= $this->db->query($area_str);
				$area_data = $area_query->result();
				
				if(count($area_data) > 0)
				{
					$st_id = $val->st_id;
					$area_data_res->$st_id = $area_data;
				}
			}
		}
		$quality_audit_res->Area = $area_data_res;
		
		foreach($area_data_res as $key => $value)
		{
			foreach($value as $ky => $val)
			{
				$sub_area_str = "SELECT qa_subarea_id sub_id, subarea_name san, s.site_name h1, a.area_name h2  FROM qa_subarea qs
									INNER JOIN qa_area a ON a.qa_area_id = qs.qa_area_id
									INNER JOIN qa_site s ON s.qa_site_id = a.qa_site_id
									INNER JOIN qa_account acc ON acc.qa_account_id = s.qa_account_id 
								WHERE acc.contract_id = ". $data['contract_id'] ."
								AND qs.qa_area_id = ". $val->arn_id ."
								order by subarea_name";
				
				$sub_area_query= $this->db->query($sub_area_str);
				$sub_area_data = $sub_area_query->result();
				
				if(count($sub_area_data) > 0)
				{
					$area_id = $val->arn_id;
					$sub_area_res->$area_id = $sub_area_data;
				}
			}
		}
		$quality_audit_res->SubArea = $sub_area_res;
		
		foreach($sub_area_res as $key => $value)
		{
			foreach($value as $ky => $val)
			{
				$point_str = "SELECT qa_audit_point_id p_id, point_name po FROM qa_audit_point qp
								INNER JOIN qa_subarea qs ON qs.qa_subarea_id = qp.qa_subarea_id
								INNER JOIN qa_area a ON a.qa_area_id = qs.qa_area_id
								INNER JOIN qa_site s ON s.qa_site_id = a.qa_site_id
								INNER JOIN qa_account acc ON acc.qa_account_id = s.qa_account_id 
								INNER JOIN qa_audit aud ON aud.qa_audit_id = qp.qa_audit_id AND aud.qa_account_id = acc.qa_account_id
							WHERE acc.contract_id = ". $data['contract_id'] ."
							AND qp.qa_subarea_id = ". $val->sub_id ."
					 		order by qp.point_name";
				
				$point_query= $this->db->query($point_str);
				$point_data = $point_query->result();
				
				if(count($point_data) > 0)
				{
					$sub_id = $val->sub_id;
					$point_data_res->$sub_id = $point_data;
				}
			}
		}
		
		$quality_audit_res->Point = $point_data_res;
		
		$acc_str = "";
		foreach($account_data as $key => $value)
		{
			if($acc_str == "")
				$acc_str = $value->ac_id;
			else
				$acc_str = $acc_str . ", ". $value->ac_id;
		}
		
		if($acc_str != "")
		{
			$auditor_str = "SELECT qa_auditor_id qaid, auditor_name audn, qa_account_id ac_id FROM qa_auditor qaa
						where qaa.qa_account_id IN (". $acc_str .")";

			$auditor_query= $this->db->query($auditor_str);
			$auditor_data = $auditor_query->result();
		}
			
		$quality_audit_res->Auditor = $auditor_data;
		
		return $quality_audit_res;
	}
	
	public function quality_audit_filter($data)
	{		
		$ind_qry = "SELECT poi.qa_point_indicator_id ind_id, acg.qa_account_group_id group_id, poi.point_indicator_name ind_name FROM qa_point_indicator poi
						INNER JOIN qa_audit_point ap ON ap.qa_point_indicator_id = poi.qa_point_indicator_id
						INNER JOIN qa_audit qa ON qa.qa_audit_id = ap.qa_audit_id
						INNER JOIN qa_auditor aud ON aud.qa_auditor_id = qa.qa_auditor_id
						INNER JOIN qa_account_group_point_indicator gind ON gind.qa_point_indicator_id = poi.qa_point_indicator_id
						INNER JOIN qa_account_group acg ON acg.qa_account_group_id = gind.qa_account_group_id
						INNER JOIN qa_account acc ON acc.qa_account_id = poi.qa_account_id
						WHERE CONCAT(qa.audit_date, ' ', qa.audit_start_time) >= '". $data['start_date'] ."'
								AND CONCAT(qa.audit_date, ' ', qa.audit_end_time) <= '". $data['end_date'] ."'
								AND poi.qa_account_id = ". $data['ac_id'] ."
								AND ap.point_name IN (". $data['p'] .")
								AND aud.qa_auditor_id IN (". $data['au'] .")
								AND acg.qa_account_id = ". $data['ac_id'] ."
								AND acc.active = ". ACTIVE . "
						GROUP BY acg.qa_account_group_id, poi.point_indicator_name
						ORDER BY acg.group_name, gind.sequence_no";
		
		$ind_query= $this->db->query($ind_qry);
		$ind_data = $ind_query->result();
		
		$site_list_str = "SELECT qa_site_id sid, site_name sname FROM qa_site 
							WHERE qa_account_id = '".$data['ac_id']."' 
							AND qa_site_id IN(".$data['st'].")";
		$site_list_query = $this->db->query($site_list_str);
		$site_list_data = $site_list_query->result();
		

		$site_str = "SELECT qs.qa_site_id sid, 
							qa.qa_account_id pid, 
							qap.qa_point_indicator_id indid, 
							count(qpi.point_indicator_name) cnt, 
							sum(qpi.point_indicator_name)/count(qpi.point_indicator_name) avg
						FROM 
						qa_audit qa
						INNER JOIN qa_audit_point qap on qa.qa_audit_id = qap.qa_audit_id
						INNER JOIN qa_account acc ON acc.qa_account_id = qa.qa_account_id 
						INNER JOIN qa_site qs ON qs.qa_site_id = qa.qa_site_id
						INNER JOIN qa_area qar ON qar.qa_site_id = qs.qa_site_id AND qa.qa_site_id = qar.qa_site_id 
						INNER JOIN qa_point_indicator qpi on qpi.qa_point_indicator_id= qap.qa_point_indicator_id AND qpi.qa_account_id = qa.qa_account_id
						INNER JOIN qa_account_group_point_indicator qagpi ON qagpi.qa_point_indicator_id = qpi.qa_point_indicator_id
						WHERE qa.qa_account_id = '".$data['ac_id']."'
						AND CONCAT(qa.audit_date, ' ', qa.audit_start_time) >= '".$data['start_date']."' AND CONCAT(qa.audit_date, ' ', qa.audit_end_time) <= '".$data['end_date']."'
						AND qa.qa_site_id IN (".$data['st'].")
						AND qar.qa_area_id IN (". $data['ar'] .")
						AND qap.qa_subarea_id IN(".$data['sar'].")
						AND qap.point_name IN (".$data['p'].")
						AND acc.active = ".ACTIVE."
						AND qa.qa_auditor_id IN (".$data['au'].")
						GROUP BY qs.qa_site_id, qa.qa_account_id , qap.qa_point_indicator_id
						ORDER BY qagpi.sequence_no";
		
		$site_query = $this->db->query($site_str);
		$site_data = $site_query->result();
		//echo $this->db->last_query();
		
		$area_list_str = "SELECT qa_area_id sid, area_name sname, qa_site_id  pid
							FROM qa_area 
							WHERE qa_site_id IN(".$data['st'].")
							AND qa_area_id IN(".$data['ar'].")";
		
		$area_list_query = $this->db->query($area_list_str);
		$area_list_data = $area_list_query->result();
		
		
		$area_str = "SELECT qar.qa_area_id sid, 
							qs.qa_site_id pid, 
							qap.qa_point_indicator_id indid, 
							count(qpi.point_indicator_name) cnt, 
							sum(qpi.point_indicator_name)/count(qpi.point_indicator_name) avg
						FROM 
						qa_audit qa
						INNER JOIN qa_audit_point qap on qa.qa_audit_id = qap.qa_audit_id
						INNER JOIN qa_account acc ON acc.qa_account_id = qa.qa_account_id 
						INNER JOIN qa_site qs ON qs.qa_site_id = qa.qa_site_id
						INNER JOIN qa_area qar ON qar.qa_site_id = qs.qa_site_id AND qa.qa_site_id = qar.qa_site_id 
						INNER JOIN qa_point_indicator qpi on qpi.qa_point_indicator_id= qap.qa_point_indicator_id AND qpi.qa_account_id = qa.qa_account_id
						INNER JOIN qa_account_group_point_indicator qagpi ON qagpi.qa_point_indicator_id = qpi.qa_point_indicator_id
						WHERE qa.qa_account_id = '".$data['ac_id']."'
						AND CONCAT(qa.audit_date, ' ', qa.audit_start_time) >= '".$data['start_date']."' AND CONCAT(qa.audit_date, ' ', qa.audit_end_time) <= '".$data['end_date']."'
						AND qa.qa_site_id IN (".$data['st'].")
						AND qar.qa_area_id IN (". $data['ar'] .")
						AND qap.qa_subarea_id IN(".$data['sar'].")
						AND qap.point_name IN (".$data['p'].")
						AND acc.active = ".ACTIVE."
						AND qa.qa_auditor_id IN (".$data['au'].")
						GROUP BY qar.qa_area_id, qs.qa_site_id , qap.qa_point_indicator_id
						ORDER BY qagpi.sequence_no";
		
		$area_query= $this->db->query($area_str);
		$area_data = $area_query->result();
		
		$subarea_list_str = "SELECT qa_subarea_id sid, subarea_name sname, qa_area_id pid
							FROM qa_subarea
							WHERE qa_area_id IN(".$data['ar'].")
							AND qa_subarea_id IN(".$data['sar'].")";
		
		$subarea_list_query = $this->db->query($subarea_list_str);
		$subarea_list_data = $subarea_list_query->result();
		
		$subarea_str = "SELECT qap.qa_subarea_id sid, 
							qar.qa_area_id pid, 
							qap.qa_point_indicator_id indid, 
							count(qpi.point_indicator_name) cnt, 
							sum(qpi.point_indicator_name)/count(qpi.point_indicator_name) avg
						FROM 
						qa_audit qa
						INNER JOIN qa_audit_point qap on qa.qa_audit_id = qap.qa_audit_id
						INNER JOIN qa_account acc ON acc.qa_account_id = qa.qa_account_id 
						INNER JOIN qa_site qs ON qs.qa_site_id = qa.qa_site_id
						INNER JOIN qa_area qar ON qar.qa_site_id = qs.qa_site_id AND qa.qa_site_id = qar.qa_site_id 
						INNER JOIN qa_point_indicator qpi on qpi.qa_point_indicator_id= qap.qa_point_indicator_id AND qpi.qa_account_id = qa.qa_account_id
						INNER JOIN qa_account_group_point_indicator qagpi ON qagpi.qa_point_indicator_id = qpi.qa_point_indicator_id
						WHERE qa.qa_account_id = '".$data['ac_id']."'
						AND CONCAT(qa.audit_date, ' ', qa.audit_start_time) >= '".$data['start_date']."' AND CONCAT(qa.audit_date, ' ', qa.audit_end_time) <= '".$data['end_date']."'
						AND qa.qa_site_id IN (".$data['st'].")
						AND qar.qa_area_id IN (". $data['ar'] .")
						AND qap.qa_subarea_id IN(".$data['sar'].")
						AND qap.point_name IN (".$data['p'].")
						AND acc.active = ".ACTIVE."
						AND qa.qa_auditor_id IN (".$data['au'].")
						GROUP BY qap.qa_subarea_id, qar.qa_area_id , qap.qa_point_indicator_id
						ORDER BY qagpi.sequence_no";
		
		$subarea_query= $this->db->query($subarea_str);
		$subarea_data = $subarea_query->result();
		
		
		$point_qry = "SELECT qap.point_name sid, 
							qap.qa_subarea_id pid, 
							qap.qa_point_indicator_id indid, 
							count(qpi.point_indicator_name) cnt, 
							sum(qpi.point_indicator_name)/count(qpi.point_indicator_name) avg
						FROM 
						qa_audit qa
						INNER JOIN qa_audit_point qap on qa.qa_audit_id = qap.qa_audit_id
						INNER JOIN qa_account acc ON acc.qa_account_id = qa.qa_account_id 
						INNER JOIN qa_site qs ON qs.qa_site_id = qa.qa_site_id
						INNER JOIN qa_area qar ON qar.qa_site_id = qs.qa_site_id AND qa.qa_site_id = qar.qa_site_id 
						INNER JOIN qa_point_indicator qpi on qpi.qa_point_indicator_id= qap.qa_point_indicator_id AND qpi.qa_account_id = qa.qa_account_id
						INNER JOIN qa_account_group_point_indicator qagpi ON qagpi.qa_point_indicator_id = qpi.qa_point_indicator_id
						WHERE qa.qa_account_id = '".$data['ac_id']."'
						AND CONCAT(qa.audit_date, ' ', qa.audit_start_time) >= '".$data['start_date']."' AND CONCAT(qa.audit_date, ' ', qa.audit_end_time) <= '".$data['end_date']."'
						AND qa.qa_site_id IN (".$data['st'].")
						AND qar.qa_area_id IN (". $data['ar'] .")
						AND qap.qa_subarea_id IN(".$data['sar'].")
						AND qap.point_name IN (".$data['p'].")
						AND acc.active = ".ACTIVE."
						AND qa.qa_auditor_id IN (".$data['au'].")
						GROUP BY qap.point_name, qap.qa_subarea_id , qap.qa_point_indicator_id
						ORDER BY qagpi.sequence_no";
		
		//echo $point_qry;
		
		$point_query= $this->db->query($point_qry);
		$point_data = $point_query->result();
		
		$quality_audit_res = new stdClass();
		$quality_audit_res->Indicator = $ind_data;
		$quality_audit_res->Site_list = $site_list_data;
		$quality_audit_res->Site = $site_data;
		$quality_audit_res->Area_list = $area_list_data;
		$quality_audit_res->Area = $area_data;
		$quality_audit_res->Subarea_list = $subarea_list_data;
		$quality_audit_res->Subarea = $subarea_data;
		$quality_audit_res->Point = $point_data;

		return $quality_audit_res;
	}
	
	public function export_quality_auditor_filter($data)
	{
		$export_qry = "SELECT st.site_name sname, ar.area_name aname, sub.subarea_name saname, ap.point_name pname, ap.point_color pcolor, ap.point_weight pweight, ap.point_value pvalue, DATE_FORMAT(qa.audit_date, '%m/%d/%Y') AS adate, DATE_FORMAT(qa.audit_date, '%b-%y') amonth, qa.audit_start_time time_in, aud.auditor_name aud_name, qa.audit_end_time time_out, poi.point_indicator_name ind_name, ap.comment cmt FROM qa_audit_point ap
						INNER JOIN qa_point_indicator poi ON poi.qa_point_indicator_id = ap.qa_point_indicator_id
						INNER JOIN qa_audit qa ON qa.qa_audit_id = ap.qa_audit_id
						INNER JOIN qa_auditor aud ON aud.qa_auditor_id = qa.qa_auditor_id
						INNER JOIN qa_account_group_point_indicator gind ON gind.qa_point_indicator_id = poi.qa_point_indicator_id
						INNER JOIN qa_account_group acg ON acg.qa_account_group_id = gind.qa_account_group_id
						INNER JOIN qa_subarea sub ON sub.qa_subarea_id = ap.qa_subarea_id
						INNER JOIN qa_area ar ON ar.qa_area_id = sub.qa_area_id
						INNER JOIN qa_site st ON st.qa_site_id = ar.qa_site_id
						INNER JOIN qa_account acc ON acc.qa_account_id = poi.qa_account_id AND acc.qa_account_id = st.qa_account_id
						INNER JOIN qa_user_access ua ON ua.qa_account_id = acc.qa_account_id
						WHERE CONCAT(qa.audit_date, ' ', qa.audit_start_time) >= '". $data['start_date'] ."'
							AND CONCAT(qa.audit_date, ' ', qa.audit_end_time) <= '". $data['end_date'] ."'
							AND ua.user_id = ". $data['user_id'] ."
							AND ua.run_adhoc_report = ". ACTIVE ."
							AND poi.qa_account_id = ". $data['ac_id'] ."
							AND aud.qa_account_id = ". $data['ac_id'] ."
							AND acc.qa_account_id = ". $data['ac_id'] ."
							AND ap.point_name IN (". $data['p'] .")
							AND ap.qa_subarea_id IN (". $data['sar'] .")
							AND aud.qa_auditor_id IN (". $data['au'] .")
							AND ar.qa_area_id IN (". $data['ar'] .")
							AND st.qa_site_id IN (". $data['st'] .")
							AND acc.active = ". ACTIVE ."
						ORDER BY gind.sequence_no";
		
		$qa_export_query = $this->db->query($export_qry);
		$export_data = $qa_export_query->result();
		
		return $export_data;
	}
	
	public function get_daily_meal_orders($data){
		
		//Get the navigation location Id
		$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
		$patient_cat_query= $this->db->query($patient_cat_str);
		$patient_cat_data = $patient_cat_query->result();
		
		//Return empty result if
		//Pens are blank or wards are blank or
		//Lunch and day of week is "No day of week" selected or dinner and day of week is "No day of week" selected
		//Adhoc "No Menu type" is not selected.
		if($data['p'] == "" ||
		$data['w'] == "" ||
		($data['ft'] == LUNCH_FORM_TYPE_ID && ($data['dw']=="" || $data['dw']=="0")) ||
		($data['ft'] == DINNER_FORM_TYPE_ID && ($data['dw']=="" || $data['dw']=="0")) ||
		($data['ft'] == ADHOC_FORM_TYPE_ID && strpos(",".$data['mt'].",",",0,") === false))
		{
			return new stdClass();
		}
		
		
		$digtal_sub_mt_str = "";		
		$digital_custom_query_str = "";
		$digital_form_query_str = "";
		$digital_latenumbers_query_str = "";
		
		if($data['ft'] == LUNCH_FORM_TYPE_ID){  // For lunch form
			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digtal_sub_mt_str = "
						SELECT df.digital_form_id FROM digital_lunch_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_lunch_form_indicator dlfi on dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						WHERE dlfi.digital_lunch_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION;
			}
		
			if($data['mt']  != "0"){
				$digtal_sub_mt_str = $digtal_sub_mt_str.  (($digtal_sub_mt_str != "") ? " UNION " : ""). "
						SELECT dl.digital_form_id
						FROM digital_lunch_form dl
						INNER JOIN digital_lunch_form_indicator dlfi ON dlfi.digital_lunch_form_id = dl.digital_lunch_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].")";
			}
		
			$digtal_sub_mt_str = ($digtal_sub_mt_str != "") ? " INNER JOIN (".$digtal_sub_mt_str.") a ON a.digital_form_id = dlf.digital_form_id " : "";
			$digital_form_query_str = "
						SELECT a.md, digital_form_ward_id_up wid, SUM(n) n, dfid
					FROM (SELECT dlf.menu_description md, dlf.digital_form_ward_id_up, SUM(late_numbers) n, b.es,
							CASE WHEN b.es = ".DIGITAL_FORM_APPROVE_EXCEPTION." THEN '' ELSE GROUP_CONCAT(DISTINCT dlf.digital_form_id) END AS dfid
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, df.exception_status es FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")
							GROUP BY dlf.menu_description, dlf.digital_form_ward_id_up, b.es) a 
					GROUP BY a.md, a.digital_form_ward_id_up";
				
			
			$digital_latenumbers_query_str = "SELECT a.md md, digital_form_ward_id_up, SUM(n) n
					FROM (SELECT dlf.menu_description md, dlf.digital_form_ward_id_up, CASE WHEN late_numbers - menu_quantitiy = 0 THEN 0 ELSE 1 END as n
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."'  AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ) a 
							GROUP BY a.md, a.digital_form_ward_id_up";
				
			$digital_custom_query_str = "SELECT SUM(late_numbers) n
						FROM digital_lunch_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.is_custom = ".ACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ";
				
				
		} else  if($data['ft'] == DINNER_FORM_TYPE_ID){  // For Dinner form
		
			//If the menutype contains "No MenuType" then filter all digital forms which doesn't have indicators.
			if(strpos(",".$data['mt'].",",",0,") !== false){
				$digtal_sub_mt_str = "
						SELECT df.digital_form_id FROM digital_dinner_form dl
						INNER JOIN digital_form df ON df.digital_form_id = dl.digital_form_id
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						LEFT JOIN digital_dinner_form_indicator dlfi on dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						WHERE dlfi.digital_dinner_form_id IS NULL
						AND da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND date(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION;
			}
			
			if($data['mt']  != "0"){
				$digtal_sub_mt_str = $digtal_sub_mt_str.  (($digtal_sub_mt_str != "") ? " UNION " : ""). "
						SELECT dl.digital_form_id
						FROM digital_dinner_form dl
						INNER JOIN digital_dinner_form_indicator dlfi ON dlfi.digital_dinner_form_id = dl.digital_dinner_form_id
						AND dlfi.digital_form_indicator_id IN (".$data['mt'].")";
			}
			
			$digtal_sub_mt_str = ($digtal_sub_mt_str != "") ? " INNER JOIN (".$digtal_sub_mt_str.") a ON a.digital_form_id = dlf.digital_form_id " : "";

			$digital_form_query_str = "
						SELECT a.md, digital_form_ward_id_up wid, SUM(n) n, dfid
					FROM (SELECT dlf.menu_description md, dlf.digital_form_ward_id_up, SUM(late_numbers) n, b.es,
							CASE WHEN b.es = ".DIGITAL_FORM_APPROVE_EXCEPTION." THEN '' ELSE GROUP_CONCAT(DISTINCT dlf.digital_form_id) END AS dfid
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, df.exception_status es FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].")
							GROUP BY dlf.menu_description, dlf.digital_form_ward_id_up, b.es) a
					GROUP BY a.md, a.digital_form_ward_id_up";
			
				
			$digital_latenumbers_query_str = "SELECT a.md md, digital_form_ward_id_up, SUM(n) n
					FROM (SELECT dlf.menu_description md, dlf.digital_form_ward_id_up, CASE WHEN late_numbers - menu_quantitiy = 0 THEN 0 ELSE 1 END as n
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."'  AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						".$digtal_sub_mt_str."
						WHERE dlf.is_custom = ".INACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ) a
							GROUP BY a.md, a.digital_form_ward_id_up";
			
			$digital_custom_query_str = "SELECT SUM(late_numbers) n
						FROM digital_dinner_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						WHERE dlf.is_custom = ".ACTIVE."
							AND dlf.digital_form_ward_id_up IN (".$data['w'].")
							AND dlf.digital_form_dayofweek_id IN (".$data['dw'].") ";
		} else if($data['ft'] == ADHOC_FORM_TYPE_ID){ //Adhoc form
			
			
			$digital_form_query_str = "SELECT dlf.menu_description md, dlf.digital_form_ward_id AS wid, count(1) n, '' dfid
						FROM digital_adhoc_form dlf
					    INNER JOIN(SELECT DISTINCT df.digital_form_id, DATE(df.server_received) d FROM digital_form df
						INNER JOIN digital_app da ON df.digital_app_id = da.digital_app_id AND da.contract_id = '".$data['contract_id']."'
						INNER JOIN digital_form_pen dfp ON df.digital_form_id = dfp.digital_form_id AND dfp.digital_pen_id IN (".$data['p'].")
						WHERE da.navigation_location='".$patient_cat_data[0]->s_module_id."'
						AND DATE(df.server_received) = '".$data['start_date']."' AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
						) b ON b.digital_form_id = dlf.digital_form_id
						INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = dlf.digital_form_ward_id
						WHERE dlf.digital_form_ward_id IN (".$data['w'].")
						GROUP BY dlf.menu_description, dlf.digital_form_ward_id";
		}
 		
		//Need to get the daily meal numbers
		//echo $digital_form_query_str;
		$digital_form_query= $this->db->query($digital_form_query_str);
		$digital_form_data = $digital_form_query->result();
		
		//echo "<pre>";
		//print_r($digital_form_data);
		
		//To get report time
		$date_time_query_str = "SELECT DATE_FORMAT(NOW(), '%H:%i on %D %b %Y') AS rt";
		$date_time_query = $this->db->query($date_time_query_str);
		$date_time_data = $date_time_query->result();
		
		//To get the ward names
		$digital_ward_query_str = "SELECT digital_form_ward_id wid, CASE WHEN digital_form_ward_name ='' THEN 'Blank' ELSE digital_form_ward_name END wn ,0 cnt FROM digital_form_ward dfw WHERE dfw.contract_id = '".$data['contract_id']."' AND dfw.digital_form_ward_id IN (".$data['w'].") ORDER BY dfw.digital_form_ward_name";
		$digital_ward_query= $this->db->query($digital_ward_query_str);
		$digital_ward_data = $digital_ward_query->result();
		
		
		$digital_latenumbers_data =null;		
		//	For late numbers
		if($digital_latenumbers_query_str != ""){
		
			$digital_latenumbers_query_str = "SELECT md, digital_form_ward_id_up wid FROM (".$digital_latenumbers_query_str.") b WHERE n>0";
			$digital_latenumbers_query= $this->db->query($digital_latenumbers_query_str);
			$digital_latenumbers_data = $digital_latenumbers_query->result();
			//echo "<pre>";
			//print_r($digital_latenumbers_data);
		}
		
		$digital_form_data_arr = array();
 		$digital_multirows_arr = array();
 		$digital_latenumbers_arr = array();
 		
 		//Loop through and populate the form data and multi rows
 		//Form data contains columns like menu description, available ward counts only.
 		//multi rows array contains like which position will have the yellow like [1][wid] means 1st row and wid column.
 		
 		for($i=0;$i< count($digital_form_data);$i++){
 			$is_menu_exists = false; 
 			for($j=0;$j<count($digital_form_data_arr);$j++){
 				if($digital_form_data_arr[$j][0] == $digital_form_data[$i]->md) {
 					$is_menu_exists = true;
 					break;
 				}
 			}
 			if(!$is_menu_exists)
 				$digital_form_data_arr[$j][0] = $digital_form_data[$i]->md;
 			$digital_form_data_arr[$j][$digital_form_data[$i]->wid] = $digital_form_data[$i]->n;
 			if(strpos($digital_form_data[$i]->dfid, ',') !== false)
 				$digital_multirows_arr[$j][$digital_form_data[$i]->wid] = 1;

 			if($digital_latenumbers_data!=null) {
				for($lnCount=0; $lnCount< count($digital_latenumbers_data);$lnCount++)
 				if($digital_latenumbers_data[$lnCount]->md == $digital_form_data[$i]->md)
 					$digital_latenumbers_arr[$j][$digital_latenumbers_data[$lnCount]->wid] = 1;
 				}
 		}
 		
		

		//Need to get the date and time
		//Need to get the ward names in separate list
		//Need to count in separate list
		//Need to get the yellow colored rows in separated list
		//Need to get the red colord rows in separated list.
		$day_ward_ln_obj = new stdClass();
		$day_ward_ln_obj->rt = $date_time_data;
		$day_ward_ln_obj->wln= $digital_ward_data;
		$day_ward_ln_obj->dm = $digital_form_data_arr;
		$day_ward_ln_obj->mr = $digital_multirows_arr;
		$day_ward_ln_obj->ln = $digital_latenumbers_arr;
		
		if($digital_custom_query_str != ""){
			$digital_custom_query= $this->db->query($digital_custom_query_str);
			$digital_custom_data = $digital_custom_query->result();
			$day_ward_ln_obj->cus_cnt = $digital_custom_data[0]->n;
		}
			
		//echo "<pre>";
		//print_r($day_ward_ln_obj);
		return $day_ward_ln_obj;
		
		
	}
	
	public function export_dmo_custom_order($data)
	{
		//Get the navigation location Id
		$patient_cat_str ="SELECT s_module_id FROM s_modules WHERE s_module_code ='L2PCAT' AND status ='".ACTIVE."'";
		$patient_cat_query= $this->db->query($patient_cat_str);
		$patient_cat_data = $patient_cat_query->result();
		
		$digital_custom_query_str = '';
		$digital_custom_data = array();
		if($data['ft'] == LUNCH_FORM_TYPE_ID)
		{			
			$digital_custom_query_str = "SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(dlf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_lunch_form dlf
										INNER JOIN digital_form df ON dlf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = dlf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = dlf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) = '".$data['start_date']."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". LUNCH_FORM_TYPE_ID .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND dlf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND dlf.is_custom = ". ACTIVE ."
										AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
									group by date(df.server_received), dlf.digital_form_dayofweek_id, dfw.digital_form_ward_id
									order by date(df.server_received)";
		}
		else  if($data['ft'] == DINNER_FORM_TYPE_ID)
		{			
			$digital_custom_query_str = "SELECT digital_form_ward_name AS wn, digital_form_dayofweek_name AS dayofweek, DATE_FORMAT(df.server_received, '%eth %b %Y') date_rec, SUM(late_numbers) as ln, group_concat(ddf.menu_description) as meal_order, form_completed_by AS comp
									FROM digital_dinner_form ddf
										INNER JOIN digital_form df ON ddf.digital_form_id = df.digital_form_id 
										INNER JOIN digital_app da ON da.digital_app_id = df.digital_app_id
										INNER JOIN digital_form_pen dp ON df.digital_form_id = dp.digital_form_id 
										INNER JOIN digital_form_ward dfw ON dfw.digital_form_ward_id = ddf.digital_form_ward_id_up
										INNER JOIN digital_form_dayofweek dfdw ON dfdw.digital_form_dayofweek_id = ddf.digital_form_dayofweek_id 
									WHERE
										date(df.server_received) = '".$data['start_date']."'
										AND da.navigation_location = '".$patient_cat_data[0]->s_module_id."'
										AND da.contract_id = ". $data['contract_id'] ."
										AND df.digital_form_type_id IN (". DINNER_FORM_TYPE_ID .")
										AND digital_form_ward_id_up IN (". $data['w'] .")
										AND dp.digital_pen_id IN (". $data['p'] .")
										AND ddf.digital_form_dayofweek_id IN (". $data['dw'] .")
										AND ddf.is_custom = ". ACTIVE ."
										AND df.exception_status !=".DIGITAL_FORM_EXCLUDE_EXCEPTION."
								group by date(df.server_received), ddf.digital_form_dayofweek_id, dfw.digital_form_ward_id
								order by date(df.server_received)";
		}
		else
		{
			return $digital_custom_data;
		}
		
		$digital_custom_query= $this->db->query($digital_custom_query_str);
		$digital_custom_data = $digital_custom_query->result();
		return $digital_custom_data;
	}
	
	public function get_sla_report($data)
	{
		$res = new stdClass();
		
		$qry = "SELECT CASE WHEN SUM(create_sla_report) >= 1 THEN 1 ELSE 0 END AS cr_sla_access, CASE WHEN SUM(run_adhoc_report) >= 1 THEN 1 ELSE 0 END AS adhoc_access, CASE WHEN SUM(view_sla_report) >= 1 THEN 1 ELSE 0 END AS view_sla_access FROM qa_user_access ua
					WHERE ua.user_id = ". $data['user_id'];
		
		$access_query= $this->db->query($qry);
		$access_data = $access_query->result();
		
		$rep_qry = "SELECT qa_sla_report_id id, report_name, rp.qa_account_id account_id, account_name, CASE WHEN rp.mdate IS NULL THEN rp.cdate ELSE rp.mdate END AS lm, ua.create_sla_report sla_mod_access, ua.run_adhoc_report adhoc_access
						FROM qa_sla_report rp
						INNER JOIN qa_account ac ON ac.qa_account_id = rp.qa_account_id
						INNER JOIN qa_user_access ua ON ua.qa_account_id = rp.qa_account_id AND ua.qa_account_id = ac.qa_account_id
						WHERE ua.user_id = ". $data['user_id'] ."
							AND view_sla_report = ". ACTIVE ."
							AND ac.active = ". ACTIVE ."
							AND rp.status = ". ACTIVE ."
						ORDER BY
							CASE 
								WHEN rp.mdate IS NULL THEN rp.cdate 
								ELSE rp.mdate 
							END DESC";
		
		$report_query= $this->db->query($rep_qry);
		$res_report = $report_query->result();
		
		/*$this->db->select('qa_sla_report_id id, report_name, rp.qa_account_id account_id, account_name, CASE WHEN rp.mdate = null THEN rp.cdate ELSE rp.mdate END AS lm, ua.create_sla_report sla_mod_access, ua.run_adhoc_report adhoc_access', false);
		$this->db->from('qa_sla_report rp');
		$this->db->join('qa_account ac', 'ac.qa_account_id = rp.qa_account_id');
		$this->db->join('qa_user_access ua', 'ua.qa_account_id = rp.qa_account_id AND ua.qa_account_id = ac.qa_account_id');
		$this->db->where('ua.user_id', $data['user_id']);
		$this->db->where('view_sla_report', ACTIVE);
		$this->db->where('ac.active', ACTIVE);
		$this->db->where('rp.status', ACTIVE);
		$this->db->order_by('rp.mdate');
		$query = $this->db->get();
		$res_report = $query->result();*/
		
		$res->access = $access_data;
		$res->report = $res_report;
		return $res;
	}
	
	public function delete_sla_report($data)
	{
		$update_qry = "UPDATE qa_sla_report rp INNER JOIN qa_account ac ON ac.qa_account_id = rp.qa_account_id
							INNER JOIN qa_user_access ua ON ua.qa_account_id = rp.qa_account_id AND ua.qa_account_id = ac.qa_account_id
						SET status = ". INACTIVE ." 
						WHERE 
							rp.qa_sla_report_id = ". $data['report_id'] ."
							AND rp.qa_account_id = ". $data['account_id'] ."
							AND ac.qa_account_id = ". $data['account_id'] ."
							AND ua.qa_account_id = ". $data['account_id'] ."
							AND ac.active = ". ACTIVE ."
							AND ua.user_id = ". $data['user_id'] ."
							AND create_sla_report = ". ACTIVE;
		
		$qry_res = $this->db->query($update_qry);
		return $qry_res;
	}
	
	public function get_invoice_orders($data){
		
		$total_record = INVOICE_ORDER_COUNT;
		$start= ($data['page_no'] -1) * $total_record;
		
		$this->db->select('COUNT(1) as cnt');
		$this->db->from('hospitality_order ho');
		$this->db->join('order_items o', 'ho.hospitality_order_id = o.hospitality_order_id');
		$this->db->where('o.school_id', $data['school_id']);
		$query = $this->db->get();
		$res_count = $query->result();
		
		//echo $this->db->last_query();
		
		$res_hospitality = new stdClass();
		$res_hospitality->tot_cnt = $res_count[0]->cnt;
		
		$this->db->select("ho.hospitality_order_id AS invid, o.order_id AS oid,hospitality_order_type_id AS otypeid, COALESCE(ho.mdate,ho.cdate) lm, DATE_FORMAT(COALESCE(ho.mdate,ho.cdate), '%d/%m/%Y' ) AS lmdate,u.username lmuser, DATE_FORMAT(o.fulfilment_date, '%d/%m/%Y')AS odate,  (o.option_cost + o.option_vat) AS oc,  LEFT(o.hospitality_desc,50) AS d, ho.status AS status, CASE WHEN DATEDIFF(CURDATE(),o.fulfilment_date) > 0 THEN 1 ELSE 0 END  AS is_edit",false);
		$this->db->from('hospitality_order ho');
		$this->db->join('order_items o', 'ho.hospitality_order_id = o.hospitality_order_id');
		$this->db->join('users u', 'u.user_id = COALESCE(ho.muser_id,ho.cuser_id)');
		$this->db->where('o.school_id', $data['school_id']);
		$this->db->order_by('lm', 'desc');
		$this->db->limit($total_record, $start);
		$query = $this->db->get();
		$res_orders = $query->result();
		$res_hospitality->hosp_rows = $res_orders;
		
		return $res_hospitality;

	}
	public function get_inv_order_details($data){		
		$this->db->select("o.school_id AS sid, DATE_FORMAT(o.fulfilment_date, '%d/%m/%Y')AS odate,  o.option_cost oc, o.option_vat AS ov,  o.hospitality_desc AS d",false);
		$this->db->from('hospitality_order ho');
		$this->db->join('order_items o', 'ho.hospitality_order_id = o.hospitality_order_id');
		$this->db->where('ho.hospitality_order_id', $data['invid']);
		$query = $this->db->get();
		$res_orders = $query->result();
		//echo $this->db->last_query();
		$res_hospitality = new stdClass();
		$res_hospitality->hosp_res = $res_orders;
		
		return $res_hospitality;
	}
	
	public function save_inv_order_details($data){

		
		$this->db->select('vat');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_contract = $query->result();
		
		$res_hospitality = new stdClass();
		
		//For insert
		if($data['invid'] == ""){
			
			$insert_data = array(
					'hospitality_order_type_id' => HOSP_TYPE_INVOCIE,
					'status' => ORDER_STATUS_NEW,
					'cuser_id' => $data['user_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('hospitality_order', $insert_data);
			$hospitality_order_id = $this->db->insert_id();
			
			$order_id = create_transaction_id($data['contract_id'],HOSPITALITY, ORDER_ITEMS);
			
			$vat = ($data['amt'] * $res_contract[0]->vat)/100;
			$order_query_data = array(
				'school_id' => $data['school_id'],
				'order_id' =>$order_id,
				'order_type' => HOSPITALITY,
				'fulfilment_date' => $data['event_date'],
				'order_userid' => $data['user_id'],
				'order_status' => ORDER_STATUS_NEW,
				'hospitality_order_id' => $hospitality_order_id,
				'hospitality_desc' => $data['d'],
				'option_cost' => $data['amt'],
				'option_vat' =>$vat,
				'a_hos_net' => $data['amt'],
				'a_hos_vat' => $vat,
				'cuser_id' => $data['user_id']
				);
			
			$this->db->set('order_date', 'NOW()', FALSE);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('order_items', $order_query_data);
			$order_id = $this->db->insert_id();
			
			
			$res_hospitality->hos_order_id = $hospitality_order_id;
			
		} else {  // For update
			$this->db->set('muser_id', $data['user_id']);
			$this->db->set('mdate', 'NOW()',false);
			$this->db->where('hospitality_order_id',$data['invid']);
			$this->db->update('hospitality_order');
			
			
			$vat = ($data['amt'] * $res_contract[0]->vat)/100;
			
			$this->db->set('fulfilment_date', $data['event_date']);
			$this->db->set('hospitality_desc', $data['d']);
			$this->db->set('option_cost', $data['amt']);
			$this->db->set('option_vat', $vat);
			$this->db->set('a_hos_net', $data['amt']);
			$this->db->set('a_hos_vat', $vat);
			$this->db->set('muser_id', $data['user_id']);
			$this->db->set('mdate', 'NOW()',false);
			$this->db->where('hospitality_order_id',$data['invid']);
			$this->db->update('order_items');
			
			
			$res_hospitality->hos_order_id = $data['invid'];
			
		}
		return $res_hospitality;
	}
	public function cancel_inv_order_details($data){
		
		$this->db->set('status', ORDER_STATUS_CANCEL);
		$this->db->set('muser_id', $data['user_id']);
		$this->db->set('mdate', 'NOW()',false);
		$this->db->where('hospitality_order_id',$data['invid']);
		$this->db->update('hospitality_order');
		
			
		$this->db->set('order_status', ORDER_STATUS_CANCEL);
		$this->db->set('muser_id', $data['user_id']);
		$this->db->set('mdate', 'NOW()',false);
		$this->db->where('hospitality_order_id',$data['invid']);
		$this->db->update('order_items');
		
		$res_hospitality = new stdClass();
		$res_hospitality->hos_order_id = $data['invid'];
		
		return $res_hospitality;
	}
	public function search_pupil_debt_order($data){
		
		
		$this->db->select('vat AS vat, unspecified_mealcost AS mc');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$con_query = $this->db->get();
		$con_res = $con_query->result();
		
		
		$pupils = "";
		if($data['pupils'] != "")
			$pupils = "'".str_replace(",","','",$data['pupils'])."'";
		$this->db->distinct();	
		$this->db->select('students_id,fname,mname,lname,st.pupil_id,fsm,cash_balance,s.school_name,s.school_id,adult');
		$this->db->from('students st');
		$this->db->join('school_classes sc', 'st.school_classes_id = sc.school_classes_id');
		$this->db->join('schools s', 's.school_id = sc.school_id');
		$this->db->join('school_admins sa', 's.school_id = sa.school_id');
		
		if($pupils != "")
			$this->db->join('(SELECT pupil_id from students WHERE pupil_id IN ('.$pupils.'))a', 'st.pupil_id = a.pupil_id',LEFT);
		
		$this->db->where('s.contract_id', $data['contract_id']);
		$this->db->where('s.school_id', $data['school_id']);
		$this->db->where('st.active', ACTIVE);
		$this->db->where('st.fsm', '0');
		if($pupils != "")
			$this->db->where('a.pupil_id IS NULL',null, false);
		$this->db->like('st.pupil_id', $data['pupil_id']);
		$this->db->like('fname', $data['fname']);
		$this->db->like('mname', $data['mname']);
		$this->db->like('lname', $data['lname']);
		$query = $this->db->get();
		$res_pupils = $query->result();
		$pupil_data = new stdClass();
		$pupil_data->cs = $con_res;
		$pupil_data->p = $res_pupils;
		
		return $pupil_data;		
	}
	
	public function save_pupil_meal_order_details($data){
		
		$this->db->select('vat, unspecified_mealcost');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$query = $this->db->get();
		$res_contract = $query->result();
		
		$res_hospitality = new stdClass();
		//For insert
		if($data['invid'] == ""){
			
			$insert_data = array(
					'hospitality_order_type_id' => HOSP_TYPE_PUPIL_DEBT,
					'status' => ORDER_STATUS_NEW,
					'cuser_id' => $data['user_id']
			);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('hospitality_order', $insert_data);
			$hospitality_order_id = $this->db->insert_id();
			
			$pupils = "";
			if($data['pupils'] != "")
				$pupils = "'".str_replace(",","','",$data['pupils'])."'";
			
			$hos_adult_pupil_query_str = "INSERT INTO hospitality_order_pupils(hospitality_order_id, pupil_id,fname,mname, lname, fsm,adult,status, cdate,cuser_id)
					SELECT ".$hospitality_order_id.",pupil_id, fname, mname, lname, fsm,adult,status, NOW(), ".$data['user_id']." FROM  students where pupil_id IN (".$pupils.") AND active = ".ACTIVE." AND adult=1";
			$hos_adult_pupil_query= $this->db->query($hos_adult_pupil_query_str);
			$adult_count = $this->db->affected_rows();
			
			$hos_non_adult_pupil_query_str = "INSERT INTO hospitality_order_pupils(hospitality_order_id, pupil_id,fname, mname, lname, fsm,adult,status, cdate,cuser_id)
					SELECT ".$hospitality_order_id.",pupil_id, fname, mname, lname, fsm,adult,status, NOW(), ".$data['user_id']." FROM  students where pupil_id IN (".$pupils.") AND active = ".ACTIVE." AND adult=0";
			$hos_non_adult_pupil_query= $this->db->query($hos_non_adult_pupil_query_str);
			$non_adult_count = $this->db->affected_rows();
			
			$amt = $res_contract[0]->unspecified_mealcost * ($adult_count + $non_adult_count);
			$adult_amt = $res_contract[0]->unspecified_mealcost * $adult_count;
			$order_id = create_transaction_id($data['contract_id'],HOSPITALITY, ORDER_ITEMS);
			
			$vat = ($adult_amt * $res_contract[0]->vat)/100;
			$order_query_data = array(
					'school_id' => $data['school_id'],
					'order_id' =>$order_id,
					'order_type' => HOSPITALITY,
					'fulfilment_date' => $data['event_date'],
					'order_userid' => $data['user_id'],
					'order_status' => ORDER_STATUS_NEW,
					'hospitality_order_id' => $hospitality_order_id,
					'hospitality_desc' => "Pupil meal debt order for ".($adult_count + $non_adult_count)." pupil(s).",
					'option_cost' => $amt,
					'option_vat' =>$vat,
					'a_hos_net' => $amt,
					'a_hos_vat' => $vat,
					'cuser_id' => $data['user_id']
			);
				
			$this->db->set('order_date', 'NOW()', FALSE);
			$this->db->set('cdate', 'NOW()', FALSE);
			$this->db->insert('order_items', $order_query_data);
			$order_id = $this->db->insert_id();
			
			$res_hospitality->hos_order_id = $hospitality_order_id;
		} else {
			$this->db->set('muser_id', $data['user_id']);
			$this->db->set('mdate', 'NOW()',false);
			$this->db->where('hospitality_order_id',$data['invid']);
			$this->db->update('hospitality_order');
			
			$delete_hos_pup_query_str = "DELETE FROM hospitality_order_pupils WHERE hospitality_order_id='".$data['invid']."'";
			$this->db->query($delete_hos_pup_query_str);
			
			$pupils = "";
			if($data['pupils'] != "")
				$pupils = "'".str_replace(",","','",$data['pupils'])."'";
				
			$hos_adult_pupil_query_str = "INSERT INTO hospitality_order_pupils(hospitality_order_id, pupil_id, fname,mname, lname,fsm,adult,status, cdate,cuser_id)
					SELECT ".$data['invid'].",pupil_id, fname, mname, lname, fsm,adult,status, NOW(), ".$data['user_id']." FROM  students where pupil_id IN (".$pupils.") AND active = ".ACTIVE." AND adult=1";
			$hos_adult_pupil_query= $this->db->query($hos_adult_pupil_query_str);
			$adult_count = $this->db->affected_rows();
				
			$hos_non_adult_pupil_query_str = "INSERT INTO hospitality_order_pupils(hospitality_order_id, pupil_id, fname,mname, lname,fsm,adult,status, cdate,cuser_id)
					SELECT ".$data['invid'].",pupil_id, fname, mname, lname, fsm,adult,status, NOW(), ".$data['user_id']." FROM  students where pupil_id IN (".$pupils.") AND active = ".ACTIVE." AND adult=0";
			$hos_non_adult_pupil_query= $this->db->query($hos_non_adult_pupil_query_str);
			$non_adult_count = $this->db->affected_rows();
				
			$amt = $res_contract[0]->unspecified_mealcost * ($adult_count + $non_adult_count);
			$adult_amt = $res_contract[0]->unspecified_mealcost * $adult_count;
			
			$vat = ($adult_amt * $res_contract[0]->vat)/100;
				
			$this->db->set('fulfilment_date', $data['event_date']);
			$this->db->set('hospitality_desc', "Pupil meal debt order for ".($adult_count + $non_adult_count)." pupil(s).");
			$this->db->set('option_cost', $amt);
			$this->db->set('option_vat', $vat);
			$this->db->set('a_hos_net', $amt);
			$this->db->set('a_hos_vat', $vat);
			$this->db->set('muser_id', $data['user_id']);
			$this->db->set('mdate', 'NOW()',false);
			$this->db->where('hospitality_order_id',$data['invid']);
			$this->db->update('order_items');
				
			$res_hospitality->hos_order_id = $data['invid'];
			
		}
		
		return $res_hospitality;
	}
	
	public function get_pupil_inv_order_details($data){

		
		$this->db->select('vat AS vat, unspecified_mealcost AS mc');
		$this->db->from('contracts');
		$this->db->where('contract_id', $data['contract_id']);
		$con_query = $this->db->get();
		$con_res = $con_query->result();
		
		$this->db->select("o.school_id AS sid, DATE_FORMAT(o.fulfilment_date, '%d/%m/%Y')AS odate,  o.option_cost oc, o.option_vat AS ov",false);
		$this->db->from('hospitality_order ho');
		$this->db->join('order_items o', 'ho.hospitality_order_id = o.hospitality_order_id');
		$this->db->where('ho.hospitality_order_id', $data['invid']);
		$query = $this->db->get();
		$res_orders = $query->result();
		//echo $this->db->last_query();

		
		$this->db->select('pupil_id, fname,mname,lname,fsm, adult');
		$this->db->from('hospitality_order_pupils');
		$this->db->where('hospitality_order_id', $data['invid']);
		$res_pupils_query = $this->db->get();
		$res_pupils = $res_pupils_query->result();
		
		$res_hospitality = new stdClass();
		$res_hospitality->cs = $con_res;
		$res_hospitality->hosp_res = $res_orders;
		$res_hospitality->p = $res_pupils;		
		
		return $res_hospitality;
		
	}
	
	public function get_schools_year_class_details($data)
	{
		$sql = "SELECT sc.school_id, year_no, (
					CASE WHEN year_status =0
					THEN CONCAT_WS( ' ', year_label, '(Inactive)' ) 
					ELSE year_label 
					END 
					) AS year_val, 
					school_classes_id AS year_Id, 
					( 
					CASE WHEN class1_status =0 
					THEN CONCAT_WS( ' ', class1_name, '(Inactive)' ) 
					ELSE class1_name 
					END ) AS class1_name, ( 
					CASE WHEN class2_status =0 
					THEN CONCAT_WS( ' ', class2_name, '(Inactive)' ) 
					ELSE class2_name 
					END 
					) AS class2_name, ( 
					CASE WHEN class3_status =0 
					THEN CONCAT_WS( ' ', class3_name, '(Inactive)' ) 
					ELSE class3_name END ) 
					AS class3_name, ( 
					CASE WHEN class4_status =0 
					THEN CONCAT_WS( ' ', class4_name, '(Inactive)' ) 
					ELSE class4_name END ) 
					AS class4_name, ( 
					CASE WHEN class5_status =0 
					THEN CONCAT_WS( ' ', class5_name, '(Inactive)' ) 
					ELSE class5_name END ) 
					AS class5_name, ( 
					CASE WHEN class6_status =0 
					THEN CONCAT_WS( ' ', class6_name, '(Inactive)' ) 
					ELSE class6_name END ) 
					AS class6_name 
					FROM school_classes sc
					inner join (SELECT s.school_id 
									FROM (students st) JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id 
									JOIN schools s ON s.school_id = sc.school_id 
									WHERE active = ". ACTIVE." GROUP BY s.school_id) 
									t on sc.school_id = t.school_id
					WHERE sc.school_id = ". $data['school_id'] ."
					ORDER BY year_no";
		
		$query = $this->db->query($sql);
		$year_class_res = $query->result();
		return $year_class_res;
		
		/*$this->db->select('year_no y_no, year_label y_label');
		$this->db->from('school_classes');
		$this->db->where('school_id', $data['school_id']);
		$this->db->where('year_status', ACTIVE);
		$query = $this->db->get();
		$year_res = $query->result();
		
		$qry = "SELECT year_no, 
					CASE WHEN class1_status = 1 THEN class1_name END AS class1_name, 
					CASE WHEN class2_status = 1 THEN class2_name END AS class2_name, 
					CASE WHEN class3_status = 1 THEN class3_name END AS class3_name,
					CASE WHEN class4_status = 1 THEN class4_name END AS class4_name,
					CASE WHEN class5_status = 1 THEN class5_name END AS class5_name,
					CASE WHEN class6_status = 1 THEN class6_name END AS class6_name
				FROM school_classes
					WHERE school_id = ". $data['school_id'] ."
						AND year_status = ". ACTIVE;
		
		$class_query= $this->db->query($qry);
		$class_res = $class_query->result();
		
		$res_school_yc = new stdClass();
		$res_school_yc->year_res = $year_res;
		$res_school_yc->class_res = $class_res;
		
		return $res_school_yc;*/
	}
	
	public function get_school_pupil_search($data)
	{
		if($data['page'] == 1)
			$start = 0;
		else
			$start = ($data['page'] - 1) * MANAGE_PUPILS_SEARCH_PAGINATION_COUNT;
		
		$list_str = '';
		if($data['pupil_list'] != '')
			$list_str = " OR st.students_id IN (". $data['pupil_list'] .")";
		
		$pupil_str = '';
		if($data['pupil_id'] != '')
			$pupil_str = " AND st.pupil_id like '%". $data['pupil_id'] ."%'";
		
		$name_str = '';
		if($data['fname'] != '' || $data['mname'] != '' || $data['lname'] != '')
		{
			$name_str = " AND fname like '%". $data['fname'] ."%'
							AND mname like '%". $data['mname'] ."%'
							AND lname like '%". $data['lname'] ."%'";
		}
		
		$year_str = '';
		if($data['year_id'] != '')
		{
			$year_str = " AND sc.school_classes_id = ". $data['year_id'];
		}
		
		$class_str = '';
		if($data['class_name'] != '' && $data['class_col'] != '')
		{
			$class_name = str_replace(" (Inactive)","",$data['class_name']);
			$class_col = 'sc.'. $data['class_col'];
			$class_str = " AND ". $class_col ." = '". $class_name ."'";
		}
		
		$cnt_qry = "SELECT students_id s_id, fname, mname, lname, pupil_id pup_id, fsm, s.school_name sname, s.school_id school_id, adult, st.status active, st.school_classes_id AS year_Id, st.class_col AS class_name, username pname
					FROM students st
						INNER JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id
						INNER JOIN schools s ON s.school_id = sc.school_id
						INNER JOIN school_admins sa ON s.school_id = sa.school_id
						LEFT JOIN users u ON u.user_id = st.user_id
					WHERE 
						s.contract_id = ". $data['contract_id'] ."
						AND s.school_id = ". $data['school_id'] ."
						AND st.active = ". ACTIVE ."
						". $pupil_str ."
						". $name_str ."
						". $year_str ."
						". $class_str ."
						". $list_str ."
					GROUP BY st.students_id";
		
		$res_pupils_cnt = $this->db->query($cnt_qry)->num_rows();
			
		$qry = "SELECT students_id s_id, fname, mname, lname, pupil_id pup_id, fsm, s.school_name sname, s.school_id school_id, adult, st.status active, st.school_classes_id AS year_Id, st.class_col AS class_name, username pname
					FROM students st
						INNER JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id
						INNER JOIN schools s ON s.school_id = sc.school_id
						INNER JOIN school_admins sa ON s.school_id = sa.school_id
						LEFT JOIN users u ON u.user_id = st.user_id
					WHERE 
						s.contract_id = ". $data['contract_id'] ."
						AND s.school_id = ". $data['school_id'] ."
						AND st.active = ". ACTIVE ."
						". $pupil_str ."
						". $name_str ."
						". $year_str ."
						". $class_str ."
						". $list_str ."
					GROUP BY st.students_id LIMIT ". $start .", ".MANAGE_PUPILS_SEARCH_PAGINATION_COUNT;
		
		$query = $this->db->query($qry);
		$res_pupils = $query->result();
		
		$sql = "SELECT sc.school_id, (
					CASE WHEN year_status =0
					THEN CONCAT_WS( ' ', year_label, '(Inactive)' ) 
					ELSE year_label 
					END 
					) AS year_val, 
					school_classes_id AS year_Id, 
					( 
					CASE WHEN class1_status =0 
					THEN CONCAT_WS( ' ', class1_name, '(Inactive)' ) 
					ELSE class1_name 
					END ) AS class1_name, ( 
					CASE WHEN class2_status =0 
					THEN CONCAT_WS( ' ', class2_name, '(Inactive)' ) 
					ELSE class2_name 
					END 
					) AS class2_name, ( 
					CASE WHEN class3_status =0 
					THEN CONCAT_WS( ' ', class3_name, '(Inactive)' ) 
					ELSE class3_name END ) 
					AS class3_name, ( 
					CASE WHEN class4_status =0 
					THEN CONCAT_WS( ' ', class4_name, '(Inactive)' ) 
					ELSE class4_name END ) 
					AS class4_name, ( 
					CASE WHEN class5_status =0 
					THEN CONCAT_WS( ' ', class5_name, '(Inactive)' ) 
					ELSE class5_name END ) 
					AS class5_name, ( 
					CASE WHEN class6_status =0 
					THEN CONCAT_WS( ' ', class6_name, '(Inactive)' ) 
					ELSE class6_name END ) 
					AS class6_name 
					FROM school_classes sc
					inner join (SELECT s.school_id 
									FROM (students st) JOIN school_classes sc ON st.school_classes_id = sc.school_classes_id 
									JOIN schools s ON s.school_id = sc.school_id 
									WHERE active = ". ACTIVE." GROUP BY s.school_id) 
									t on sc.school_id = t.school_id
					WHERE sc.school_id = ". $data['school_id'] ."
					ORDER BY school_id,year_no";
			
		$query = $this->db->query($sql);
		$year_class_res = $query->result();
		
		$pupils_class_res['pupils_cnt'] = $res_pupils_cnt;
		$pupils_class_res['pupils_res'] = $res_pupils;
		$pupils_class_res['year_class_res'] = $year_class_res;
		return $pupils_class_res;
	}
	
	public function update_pupil_fsm_adult_acive_status($data)
	{
		$this->db->select('fsm, adult, status');
		$this->db->from('students st');
		$this->db->where('st.pupil_id', $data['pupil_id']);
		$this->db->where('active', ACTIVE);
		$query = $this->db->get();
		$pupil_res = $query->result();
		
		$batch_cancel_id = '';
		if(count($pupil_res) > 0)
		{
			if($pupil_res[0]->status != $data['active'])
			{
				if($data['active'] == 1)
				{
					/* Create a batch */
					$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
					$batch_data = array('user_id' => $data['user_id'], 'str' => PUPIL_ACTIVE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
				}
				else
				{
					/* Create a batch */
					$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
					$batch_data = array('user_id' => $data['user_id'], 'str' => PUPIL_DEACTIVE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
				}
				
				$batch_cancel_id = create_batch_cancel($data, PUPIL_ACTIVE_DATA_ID);
			}
			else if($pupil_res[0]->fsm != $data['fsm'])
			{
				if($data['fsm'] == 1)
				{
					/* Create a batch */
					$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
					$batch_data = array('user_id' => $data['user_id'], 'str' => PUPIL_FSM_ACTIVE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
				}
				else
				{
					/* Create a batch */
					$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
					$batch_data = array('user_id' => $data['user_id'], 'str' => PUPIL_FSM_DEACTIVE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
				}
				
				$batch_cancel_id = create_batch_cancel($data, PUPIL_FSM_DATA_ID);
			}
			else if($pupil_res[0]->adult != $data['adult'])
			{
				if($data['adult'] == 1)
				{
					/* Create a batch */
					$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
					$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_ADULT_ACTIVE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
				}
				else
				{
					/* Create a batch */
					$batch_key = array(NAME_REPLACE_STRING, DATE_REPLACE_STRING);
					$batch_data = array('user_id' => $data['cuser_id'], 'str' => PUPIL_ADULT_DEACTIVE_MESSAGE, 'key_values' => $batch_key);
					$data['reason_msg'] = generate_batch_system_messages($batch_data);
				}
				
				$batch_cancel_id = create_batch_cancel($data, PUPIL_ADULT_DATA_ID);
			}
			
			if($batch_cancel_id != '')
			{
				/*Update the order_edited in order_items for that school_id */
				$update_qry = "UPDATE order_items SET order_edited = ". ACTIVE . ", batch_cancel_id = ". $batch_cancel_id ." where pupil_id = '". $data['pupil_id'] ."' and school_id = ". $data['school_id'] ." and order_edited =". INACTIVE ." and order_status =". ORDER_STATUS_NEW ." and collect_status =". INACTIVE;
				$query_status = $this->db->query($update_qry);
			}
			
			$this->db->set('fsm', $data['fsm']);
			$this->db->set('adult', $data['adult']);
			$this->db->set('status', $data['active']);
			$this->db->where('pupil_id', $data['pupil_id']);
			$this->db->where('active', ACTIVE);
			$this->db->update('students');
			
			return TRUE;
		}
	}
}
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class qa_script extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->library('excel');
	}

	public function index()
	{
		
		
		$pay_load_obj = new stdClass();
		$pay_load_obj->account_id = 494;
		$pay_load_obj->start_date = '2013-01-01 00:00:00';
		$pay_load_obj->end_date = '2013-12-16 14:23:21';
		
		$json_str = json_encode($pay_load_obj);
		
		echo $json_str;
		exit;

		$str = "HCMPRD-";
		if ( ! preg_match("/^[A-Za-z0-9:_\/-< >?-]+$/i", $str))
		{
			echo 'Disallowed Key Characters.'.$str;exit;
		}
		exit;
		
		$input_file = "C:\\Users\\kpabbisetty\\Documents\\Projects\\Rentokil\\QAwebservice\\QAAuditData1.xlsx";
		$file_name = pathinfo($input_file,PATHINFO_BASENAME);	// Getting the file name
		//echo 'Loading file ',$file_name,' using IOFactory to identify the format<br />';
		$objReader = PHPExcel_IOFactory::createReaderForFile($input_file);
		//echo get_class($objReader);
		$file_type = PHPExcel_IOFactory::identify($input_file);
		//print_r($file_type);
		//exit;

		/* Check the file type */
		if($file_type == 'Excel5' || $file_type == 'Excel2007' )
		{
			$objReader->setReadDataOnly(TRUE);
			$objPHPExcel = $objReader->load($input_file);
		}
		else
		{
			$objReader = new PHPExcel_Reader_CSV();
			$objPHPExcel = $objReader->load($input_file);
		}
		//echo '<hr />';

		$sheet_data = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
		//echo 'original count: '.count(array_filter(array_map('array_filter', $sheet_data))).'<br><br>';
		//$data = array_filter(array_map('array_filter', $sheet_data));
		
		
		$site_arr = array();
		$area_arr = array();
		$subarea_arr = array();
		$point_arr = array();
		
		$array = array(0 => 'blue', 1 => 'red', 2 => 'green', 3 => 'red');
			
		
		$i=0;
		foreach ($sheet_data as $sheet_row){
			if($i==0){$i++; continue;}	

			$site_id  = array_search($sheet_row["A"], $site_arr); 
			if($site_id ===FALSE){
				$site_id = count($site_arr);
				$site_arr[$site_id] = $sheet_row["A"];
				//$site_id = "";
			}
			if(count($area_arr[$site_id]) == 0 ) {
				$area_id = 0;
			$area_arr[$site_id][$area_id] = $sheet_row["B"];
			}
			else {
				$area_id = array_search($sheet_row["B"],$area_arr[$site_id]);
				if($area_id === FALSE){
					$area_id = count($area_arr[$site_id]);
					$area_arr[$site_id][$area_id] = $sheet_row["B"];
				}
			}
			
			if(count($subarea_arr[$site_id][$area_id]) == 0) {
				$subarea_id = 0;
				$subarea_arr[$site_id][$area_id][$subarea_id] = $sheet_row["C"];

			} else {
				$subarea_id = array_search($sheet_row["C"],$subarea_arr[$site_id][$area_id]);
				if($subarea_id === FALSE){
					$subarea_id = count($subarea_arr[$site_id][$area_id]);
					$subarea_arr[$site_id][$area_id][$subarea_id] = $sheet_row["C"];
				}
			}
			
			if(count($point_arr[$site_id][$area_id][$subarea_id]) == 0){
				$point_id = 0;
				$p_data = new stdClass();
				$p_data->p = $sheet_row["D"];
				$p_data->s = $sheet_row["E"];
				$p_data->c = $sheet_row["F"];
				
				$point_arr[$site_id][$area_id][$subarea_id][$point_id] = $p_data; 
			}else {
				$point_id = array_search($sheet_row["D"], $point_arr[$site_id][$area_id][$subarea_id]);
				if($point_id === FALSE){
					$point_id = count($point_arr[$site_id][$area_id][$subarea_id]);
					$p_data = new stdClass();
					$p_data->p = $sheet_row["D"];
					$p_data->s = $sheet_row["E"];
					$p_data->c = $sheet_row["F"];
					$point_arr[$site_id][$area_id][$subarea_id][$point_id] = $p_data;
				}
			}
			
			
// 			if(!in_array($sheet_row["A"],$site_arr)) {
// 				$site_id =count($site_arr); 
// 				$site_arr[$site_id] = $sheet_row["A"];
				
// 				if(!in_array($sheet_row["B"],$area_arr)){
// 					$area_id = count($area_arr);
// 					$area_arr[$site_id][$area_id] = $sheet_row["B"];
				
// 				if(!in_array($sheet_row["C"],$subarea_arr)){
// 					$subarea_id = count($subarea_arr);
// 					$subarea_arr[$site_id][$area_id][$subarea_id] = $sheet_row["C"];
					
					
					
// 					}
// 				}
// 			}
			
			$i++;
		}
		
		$query_str = "";
		$account_id = 1;
		//$point_row =0;
		for($site_id=0; $site_id<count($site_arr);$site_id++){
			$query_str = $query_str. "insert into qa_site(qa_account_id, site, cdate) values(".($account_id).",'" . $site_arr[$site_id] . "',Now());" ."<br>";

			for($area_id =0; $area_id <count($area_arr[$site_id]); $area_id++){
				$query_str = $query_str. "insert into qa_area(qa_site_id, area, cdate) values(".($site_id+$account_id).",'".$area_arr[$site_id][$area_id]."',Now());" ."<br>";
		
			for($subarea_id=0; $subarea_id <count($subarea_arr[$site_id][$area_id]) ; $subarea_id++){
				$query_str = $query_str. "insert into qa_subarea(qa_area_id, subarea, cdate) values(".($area_id+$site_id+$account_id).",'".$subarea_arr[$site_id][$area_id][$subarea_id]."',Now());" ."<br>";
				
				for($point_id=0; $point_id<count($point_arr[$site_id][$area_id][$subarea_id]); $point_id++){
					$obj = $point_arr[$site_id][$area_id][$subarea_id][$point_id];
					$query_str = $query_str. "insert into qa_point(qa_subarea_id, qa_auditor_id, point, score,color_indicator, audit_date,cdate) values(".($point_id + $area_id+$site_id+$account_id).",1,'".$obj->p."','".$obj->s."','".$obj->c."','2013-08-26',NOW());" ."<br>";
			//		$point_row++;
				}
				}
			}				
			
		}
		
		echo '<pre>';
		echo $query_str;
		
		//echo $point_row;
		//print_r($point_arr);
		//var_dump($area_arr);
	}

}
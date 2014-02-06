<?php
 	if(isset($_GET['id']) && isset($_GET['img_name']))
	{
		$image_path = TEMP_SKIN_PATH.$_GET['id']."/".$_GET['img_name'];
		if(file_exists($image_path))
		{
			$content = file_get_contents($image_path);
		}
		header('Content-Type: image/png');
	} 	
	else if(isset($_GET['img_name']))
	{
		$image_path = SKIN_PATH.$_GET['skin_id']."/".$_GET['img_name'];
		if(file_exists($image_path))
		{
			$content = file_get_contents($image_path);
		}
		header('Content-Type: image/png');	
	}	
	else
	{
		$css_path = SKIN_PATH.$_GET['skin_id']."/".$_GET['skin_id'].".css";
		
		$pageURL = ($_SERVER["SERVER_PORT"] == '443'||(isSet($_SERVER['HTTPS'])  && $_SERVER['HTTPS'] == 'on')) ? 'https://' : 'http://';
		$pageURL .= ($_SERVER['SERVER_PORT'] != '80' && $_SERVER['SERVER_PORT'] != '443') ? $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"] : $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		
		if(file_exists($css_path))
		{
			$content = file_get_contents($css_path);

			$default_data = unserialize(SKIN_DEFAULT_VALUES);

			$imageURL = $pageURL . "&img_name=";

			$content = str_replace($default_data['logo_path'], $imageURL.$default_data['logo_path'], $content);
			$content = str_replace($default_data['smartphone_path'], $imageURL.$default_data['smartphone_path'], $content);
			$content = str_replace($default_data['header_div_path'], $imageURL.$default_data['header_div_path'], $content);
			$content = str_replace($default_data['level12_bg_path'], $imageURL.$default_data['level12_bg_path'], $content);
			$content = str_replace($default_data['level2_bg_path'], $imageURL.$default_data['level2_bg_path'], $content);
			$content = str_replace($default_data['no_nav_path'], $imageURL.$default_data['no_nav_path'], $content);
			$content = str_replace($default_data['widget_header_path'], $imageURL.$default_data['widget_header_path'], $content);
			$content = str_replace($default_data['select_bg_path'], $imageURL.$default_data['select_bg_path'], $content);
			header("content-type: text/css");
		}		
	}
	
	if(isset($content))
		echo $content;
	exit;
?>
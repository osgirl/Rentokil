<?php /* file: ./application/views/_box_view_errors.php */ ?>
<!doctype html>
<head>
<title>View errors</title>
</head>
<body>
  <div>
    <?php 
      $ff = $this->config->item('log_path') .'log-' .date('Y-m-d') .'.php';
     
      if (file_exists($ff))
      {
        // get file in a string
        $ff = highlight_file($ff, TRUE);
    
        // cosmetic
        $ff = str_replace('ERROR', '<br />Error<br />', $ff);
        
        // show errors
        echo $ff;
  
        // maybe delete
        echo '<p>' .anchor('view_log/delete_log_file', 'Delete log file') .'</p>';
      }else{
        // notification
        echo heading('It&#39; gone: '. $ff,4);
      }
    ?>

    <?php /* include('_java_close_window.php'); */ ?>
    <input 
      id='close'
      style='background:#f90 none; color:#00f;'
      type='button' value="Close Window">
  </div>
</body></html> 
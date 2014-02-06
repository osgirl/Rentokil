<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// file save as  ./application/controllers/c_view_errors.php
 
//========================================
class c_view_errors extends CI_Controller
{
	
 function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->helper('html');
    }

private $atts = array(
              'width'      => '800',
              'height'     => '600',
              'scrollbars' => 'yes',
              'status'     => 'yes',
              'resizable'  => 'yes',
              'screenx'    => '123',
              'screeny'    => '42'
            );
            
//===================================================
public function index($tmp=NULL)
{
  $data['view_errors'] =  anchor_popup('view_log', 'View errors', $this->atts); 
  
  $this->load->view('view_log');
  
}//endfunc


//===================================================
function delete_log_file()
{
  $ff = $this->config->item('log_path') .'log-' .date('Y-m-d') .'.php';

  // delete file
  if(file_exists($ff) && unlink($ff))
  {
    $this->load->view('view_pop_errors');
  }else{
    echo heading('Big problem :( <br />' .__FILE__);
  }
}

public function show_log_file($filname)
{
	?>
<!doctype html>
<head>
<title>View errors</title>
</head>
<body>
  <div>
    <?php 
      $ff = $this->config->item('log_path') .$filname;
     
      if (file_exists($ff))
      {
        // get file in a string
        $ff = highlight_file($ff, TRUE);
    
        // cosmetic
        $ff = str_replace('ERROR', '<br />Error<br />', $ff);
        
        // show errors
        echo $ff;
      }else{
        // notification
        echo heading('It&#39; gone: '. $ff,4);
      }
    ?>

    <?php /* include('_java_close_window.php'); */ ?>
  </div>
</body></html> 
<?php 
}


}//endclass 
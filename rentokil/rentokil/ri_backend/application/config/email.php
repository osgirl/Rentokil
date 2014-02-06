<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*$config['useragent'] = "CodeIgniter";
$config['mailpath'] = "/usr/sbin/sendmail";
$config['protocol'] = "smtp";
$config['smtp_host'] = "apprelay.cts.com";
$config['smtp_port'] = "25";
$config['mailtype'] = "html";
$config['charset']  = "iso-8859-1";
$config['newline']  = "\r\n";
$config['wordwrap'] = TRUE;*/

/*$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['charset'] = 'iso-8859-1';
$config['wordwrap'] = TRUE;*/
$config['mailtype'] = "html";

define('ADMIN_EMAIL','noreply@riwebqa.cognizant.com');
define('SCHOOL_ADMIN_EMAIL', 'admin@riwebqa.cognizant.com');
define('ADMIN_EMAIL_TITLE','FM insight');
define('ADMIN_REGISTER_EMAIL_TITLE','Eden Team');

//For cookie secure....
$config['cookie_secure']	= FALSE;
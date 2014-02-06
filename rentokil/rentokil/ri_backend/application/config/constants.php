<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
 |--------------------------------------------------------------------------
 | File and Directory Modes
 |--------------------------------------------------------------------------
 |
 | These prefs are used when checking and setting modes when working
 | with the file system.  The defaults are fine on servers with proper
 | security, but you may wish (or even need) to change the values in
 | certain environments (Apache running a separate process for each
 | user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
 | always be used to set the mode correctly.
 |
 */
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
 |--------------------------------------------------------------------------
 | File Stream Modes
 |--------------------------------------------------------------------------
 |
 | These modes are used when working with fopen()/popen()
 |
 */

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
 |--------------------------------------------------------------------------
 | Definitions for RI starts
 |--------------------------------------------------------------------------
 |
 |
 */
// Definitions for path
//$path = dirname(__FILE__).'/../../';
define('ROOT_PATH', $_SERVER['DOCUMENT_ROOT']);
define('FILE_UPLOAD_PATH',ROOT_PATH.'/assets/' );
define('TEMP_DOWNLOAD_FILE_PATH',FILE_UPLOAD_PATH.'temp_download/' );	//Download directory for excel
define('TEMP_SKIN_PATH',FILE_UPLOAD_PATH.'temp_skin/' );	// Skin images temp upload path(It will be deleted periodically)
define('ZIP_UPLOAD_PATH', FILE_UPLOAD_PATH . 'digital_forms/');	// Zip path
define('TEMP_ZIP_UPLOAD_PATH', ZIP_UPLOAD_PATH.'temp/' );	// temporary path for zip files(It will be deleted periodically)
define('ZIP_TEMP_FOLDER_NAME_UPLOAD', 'temp/');
define('ZIP_FOLDER_NAME_UPLOAD', 'digital_forms/');
define('SKIN_PATH',FILE_UPLOAD_PATH.'skins/' );		// All skin images uploaded here as per their skin_id
define('DEFAULT_SKIN_PATH', FILE_UPLOAD_PATH.'skins/default/');
define('CHANGE_PASSWORD_FRONTEND_LINK', '/ri_frontend/fpchange.html');	// It must change while front end folder or file name will change means
define('TEMP_PHONEGAP_IMAGE_PATH',FILE_UPLOAD_PATH.'temp_phonegap/' );
//define('PARENT_REGISTER_LINK', '/register/');
// Definitions for values

define('ZIP_FILE_FORMAT', 'zip');
define('XML_FILE_FORMAT', '.xml');

define('CHANGE_PASSWORD_LINK_KEY', 'key');

define('DEFAULT_STATUS', 1);
define('ACTIVE', 1);
define('INACTIVE', 0);

define('SUPER_ADMIN',1);
define('CUSTOMER_ADMIN',2);
define('USER',3);

define('USER_TITLES_ID',1);	// value is from "data" table "data_id"
define('SCHOOL_YEARS_ID',2);	// value is from "data" table "data_id"
define('SCHOOL_REPORTS_STATUS_ID',3);	// value is from "data" table "data_id"
define('MENU_TYPE_ID', 4);	//value is from "data" table "data_id" for menu type
define('NETWORK_TYPE_ID', 5);	//value is from "data" table "data_id" for network type
define('ASSET_TYPE_ID', 6);	//value is from "data" table "data_id" for asset type
define('JOB_EMAIL_TYPE_ID', 10); //value is from "data" table "data_id" for job email

define('JOB_BATCH_TYPE_ID', 46); // value is from "data value table"

define('ACC_SYNC_ID',12);	// value is from "data" table "data_id"
define('ACC_SYNC_TYPE_ID', 50); // value is from "data value table"

define('SUCCESS', 1);
define('FAILURE', 0);

// Setup data file column names
define('CONTRACT_NAME', 'Group (Customer)');
define('DIVISION', 'Division (site class)');
define('SUB_DIVISION', 'Sub Division (site class)');
define('SITE_NAME', 'Site Name');
define('MRN', 'Meter reference');
define('UTILITY', 'Utility');
define('HH_NHH', 'HH/NHH');
define('SETUP_TITLE_ROW_NO', 1);
$setup_cols = serialize(array(CONTRACT_NAME, DIVISION, SUB_DIVISION, SITE_NAME, MRN, UTILITY, HH_NHH));
define('SETUP_TITLE_COLS', $setup_cols);


// Definitions for error response

define('INPUT_DATA_MISSING', 'Some input data missing.');
define('DUPLICATE_ENTRY', 'Some duplicate entry exist.');
define('UNAUTHOURIZED_ACCESS', 'Unauthorized access.');
define('DATE_VALIDATION_ERROR', 'End Date should be greater than Start Date');
define('DATABASE_QUERY_FAILED','Database query execution failed.');
define('INVALID_SESSION','Invalid Session.');
define('EMAIL_FAILED', 'Unable to send email');
define('USER_EXIST', 'User Already Exist');
define('PROFILE_EXIST', 'Profile already exists');
define('NEW_PARENT_SUCCESS', 'Your registration completed successfully!');
define('INVALID_USERNAME', 'Account not found.');
define('MYPROFILE_UPDATE', 'Profile updated sucessfully');
define('PURGE_ENERGY_DATA_NOT_FOUND', 'No records found.');
define('FILE_UPLOAD_ERROR', 'Error in file upload');
define('ZIP_DELETE_ERROR', 'Error in deleting zip file');
define('ZIP_UPLOAD_HTML_ERROR', 'Should contain one .html file');
define('ZIP_UPLOAD_MOVE_ERROR', 'File move error');
define('ZIP_UPLOAD_FOLDER_ERROR', 'Should not contain more than one folder');
define('FILE_DELETE_ERROR', 'Error in file deletion');
define('SKIN_DELETE_PROFILE_ERROR', 'Skin already mapped to the profile. Can not be deleted');
define('SKIN_DELETE_ONLY_ONE_ERROR', 'Default skin can not be deleted.');
define('SKIN_DUPLICATE_ERROR', 'Skin name already exists');
define('PROFILE_NOT_EXIST', 'You are not authorized to edit this page.');
define('CURL_CONNECTION_PROBLEM', 'CURL Connection Problem.');
define('CURL_INSTALLATION_PROBLEM', 'Check administrator.');
define('CONNECTION_PROBLEM', 'Connection Problem.');
define('INVALID_DIGITAL_FORM_ID', 'Invalid Digital form ID');
define('INVALID_DIGITAL_FORM_WARD_ID', 'Invalid Digital form ward ID');

define('SADMIN_CUSTOMER_DUPLICATE', 'Customer already exists');
define('SADMIN_CUSTOMER_NAME_DUPLICATE', 'Customer name already exists');
define('SADMIN_CADMIN_DUPLICATE', 'Email address already exists');
define('SADMIN_CADMIN_EMAIL_FAILED', 'Unable to send email');
define('CADMIN_SCHOOL_DUPLICATE', 'School already exists');
define('CADMIN_CONTRACT_DUPLICATE', 'Contract already exists');
define('CADMIN_USER_DUPLICATE', 'User already exists');
define('CADMIN_USER_EMAIL_FAILED', 'Unable to send email');

define('CADMIN_CONTRACT_CREATION', 'Problem in create contract key');

define('CARD_BALANCE_ERROR', 'Check the card balance.');

define('CADMIN_ZONE_NAME_DUPLICATE','Zone name is already exists.');
define('CADMIN_APP_NAME_DUPLICATE','App name already exists.');
define('CADMIN_LUNCH_NAME_DUPLICATE','Lunch form already exists for a contract.');
define('CADMIN_DINNER_NAME_DUPLICATE','Dinner form already exists for a contract.');
define('CADMIN_ADHOC_NAME_DUPLICATE','Ad-hoc form already exists for a contract.');
define('CADMIN_ZONE_DEVICE_DUPLICATE','Device ID and Serial Number combination is already configured in the system.');
define('CADMIN_ASSET_DUPLICATE','Asset tag is already exists.');

define('LUNCH_FORM_TYPE_ID', 1);
define('DINNER_FORM_TYPE_ID', 2);
define('ADHOC_FORM_TYPE_ID', 3);
define('OTHER_FORM_TYPE_ID', 4);

define('NO_BATCH_DATA', 'No data found');

define('REPLACE_STRING', '%###%');
define('REPLACE_STRING_COL', '%$$$%');
//define('REPLACE_COLUMN_NAME', '%####%');
//define('DUPLICATE_DATA', 'Conflicting / overlapping data in row '.REPLACE_STRING.'.');
define('DUPLICATE_DATA', 'Conflicting / overlapping data in import file.');
define('INVALID_CONTRACT', 'Contract name and Group name in row '.REPLACE_STRING.' must match.');
define('INVALID_COLUMN', 'Unexpected column name in column '.REPLACE_STRING.'.');
define('EMPTY_DATA', 'Empty cells in row '.REPLACE_STRING.'.');
define('INVALID_COLUMN_NAME', 'Unexpected column name in column '.REPLACE_STRING.'.');
define('KWH_ALREADY_EXISTS', 'Can not change Utility and HH/NHH values if meter readings already exist for "'.REPLACE_STRING.'"');
define('DATA_EXISTS_IN_DATABASE', 'Row '.REPLACE_STRING.' has already been imported.');
define('DATA_EXISTS_IN_SETUPDATA', 'Row '.REPLACE_STRING.' not setup in database.');
define('SETUP_HH_NHH_ERR', 'Row '.REPLACE_STRING.' should have either "HH" OR "NHH" for column HH/NHH.');
define('INVALID_VALUE', 'Unexpected value in row '.REPLACE_STRING.'.');
define('INVALID_DATE', 'Date column must be in DD/MM/YYYY format in row '.REPLACE_STRING.'.');
define('INVALID_YEAR', INVALID_VALUE.' Year must be in the range 1901 to 2155');
define('INVALID_MONTH', INVALID_VALUE.' Month must be like January, Feburary, March ..');
define('INVALID_VALUE_COLUMN', 'Unexpected value in row '.REPLACE_STRING.' , column '.REPLACE_STRING_COL.'.');

define('IMPORT_TYPE_INVALID', 'Import type not valid.');

define('RANDOM_PASSWORD_LENGTH', 11);	//Random password length. It should be RANDOM_ALPHA_NUMERIC_LENGTH + 1.
define('RANDOM_ALPHA_NUMERIC_LENGTH', 10);	// Random alpha numeric lenth for password generator
define('RANDOM_ALPHA_NUMERIC_UPPER_CASE_LENGTH', 5); //Its for upper case. It should be double the RANDOM_ALPHA_NUMERIC_LENGTH
define('RANDOM_PASS_FS','L9ybD6>x8xV');
// HH Data file column names
/*define('T0030', '00:30');
 define('T0100', '01:00');
 define('T0130', '01:30');
 define('T0200', '02:00');
 define('T0230', '02:30');
 define('T0300', '03:00');
 define('T0330', '03:30');
 define('T0400', '04:00');
 define('T0430', '04:30');
 define('T0500', '05:00');
 define('T0530', '05:30');
 define('T0600', '06:00');
 define('T0630', '06:30');
 define('T0700', '07:00');
 define('T0730', '07:30');
 define('T0800', '08:00');
 define('T0830', '08:30');
 define('T0900', '09:00');
 define('T0930', '09:30');
 define('T1000', '10:00');
 define('T1030', '10:30');
 define('T1100', '11:00');
 define('T1130', '11:30');
 define('T1200', '12:00');
 define('T1230', '12:30');
 define('T1300', '13:00');
 define('T1330', '13:30');
 define('T1400', '14:00');
 define('T1500', '15:00');
 define('T1530', '15:30');
 define('T1600', '16:00');
 define('T1630', '16:30');
 define('T1700', '17:00');
 define('T1730', '17:30');
 define('T1800', '18:00');
 define('T1830', '18:30');
 define('T1900', '19:00');
 define('T1930', '19:30');
 define('T2000', '20:00');
 define('T2030', '20:30');
 define('T2100', '21:00');
 define('T2130', '21:30');
 define('T2200', '22:00');
 define('T2230', '22:30');
 define('T2300', '23:00');
 define('T2330', '23:30');
 define('T2400', '24:00');*/
define('HH_DATE', 'Date');
$hh_file_cols = serialize(array(CONTRACT_NAME, DIVISION, SUB_DIVISION, SITE_NAME, MRN, HH_DATE, '00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30','24:00'));
define('HH_TITLE_COLS', $hh_file_cols);
$hh_req_cols = serialize(array(CONTRACT_NAME, SITE_NAME, MRN, HH_DATE, '00:30','01:00','01:30','02:00','02:30','03:00','03:30','04:00','04:30','05:00','05:30','06:00','06:30','07:00','07:30','08:00','08:30','09:00','09:30','10:00','10:30','11:00','11:30','12:00','12:30','13:00','13:30','14:00','14:30','15:00','15:30','16:00','16:30','17:00','17:30','18:00','18:30','19:00','19:30','20:00','20:30','21:00','21:30','22:00','22:30','23:00','23:30','24:00'));
define('HH_REQUIRED_COLS', $hh_req_cols);
define('HH_TITLE_ROW_NO', 2);
$hh_db_cols = serialize(array('hh_date','t0030','t0100','t0130','t0200','t0230','t0300','t0330','t0400','t0430','t0500','t0530','t0600','t0630','t0700','t0730','t0800','t0830','t0900','t0930','t1000','t1030','t1100','t1130','t1200','t1230','t1300','t1330','t1400','t1430','t1500','t1530','t1600','t1630','t1700','t1730','t1800','t1830','t1900','t1930','t2000','t2030','t2100','t2130','t2200','t2230','t2300','t2330','t2400'));
define('HH_DB_COLS', $hh_db_cols);
$hh_stats_db_cols = serialize(array('stats_date','t0030','t0100','t0130','t0200','t0230','t0300','t0330','t0400','t0430','t0500','t0530','t0600','t0630','t0700','t0730','t0800','t0830','t0900','t0930','t1000','t1030','t1100','t1130','t1200','t1230','t1300','t1330','t1400','t1430','t1500','t1530','t1600','t1630','t1700','t1730','t1800','t1830','t1900','t1930','t2000','t2030','t2100','t2130','t2200','t2230','t2300','t2330','t2400'));
define('HH_STATS_DB_COLS', $hh_stats_db_cols);
define('NHH_TITLE_ROW_NO', 1);
$nhh_cols = serialize(array(CONTRACT_NAME, DIVISION, SUB_DIVISION, SITE_NAME, MRN, 'From Date', 'To Date', 'kWh used'));
define('NHH_TITLE_COLS', $nhh_cols);
define('DATE_RANGE_INVALID', 'Unexpected date range in row '.REPLACE_STRING.'.');

// Target data file column names

define('BASELINE_YEAR', 'Baseline Year');
define('BASELINE_MONTH', 'Baseline Month');
define('KWH_PER_MONTH', 'kWh per month');
define('EPC_PERCENTAGE', 'EPC %');
define('AVG_P_PER_KWH', 'Average p per kWh');

define('TD_TITLE_ROW_NO', 1);
$targetdata_file_cols = serialize(array('A'=>CONTRACT_NAME, 'B'=>DIVISION, 'C'=>SUB_DIVISION, 'D'=>SITE_NAME, 'E'=>MRN,	'F'=>UTILITY, 'G'=>BASELINE_YEAR, 'H'=>BASELINE_MONTH, 'I'=>KWH_PER_MONTH, 'J'=>EPC_PERCENTAGE, 'K'=>AVG_P_PER_KWH));
define('TD_TITLE_COLS', $targetdata_file_cols);

$target_db_cols = serialize(array('baseline_year','baseline_month','kwh_per_month','epc','avg_per_kwh'));
define('TARGET_DB_COLS', $target_db_cols);
define('RECAPTCHA_PRIVATE_KEY','6LdReOESAAAAAHHPUIL5gREVtavU7uAqjMpzx-2t');//6LfkqeASAAAAAJtHSnEZ-_p9nTpHgOlqvIxFhR7F
define('PUPIL_NOT_EXIST','Pupil ID Not Exist');
define('PUPIL_ID_ALREADY_REGISTERED','Pupil ID Already Registered');
define('NO_PROFILE_CONTRACT', 'Default Profile not set.');
define('INVALID_PASSWORD','Current password not correct');
define('PUPILS_TITLE_ROW_NO', 1);
$pupils_file_cols = serialize(array('School', 'First Name', 'Middle Name', 'Last Name', 'Year Group', 'Class', 'Active', 'Pupil Duplicate', 'FSM', 'Adult', 'Pupil ID'));
define('PUPILS_TITLE_COLS', $pupils_file_cols);
$pupils_export_cols = serialize(array('School', 'First Name', 'Middle Name', 'Last Name', 'Year Group', 'Class', 'Active', 'Pupil Duplicate', 'FSM', 'Adult', 'Pupil ID', 'Username', 'Title', 'First Name', 'Last Name', 'Email', 'Home Telephone', 'Work Telephone', 'Mobile Number', 'Notify via Email', 'Notify via SMS'));
define('PUPILS_EXPORT_COLS', $pupils_export_cols);
define('PUPIL_EXCEL_COLUMN_NAME', 'A');
define('PUPIL_EXCEL_SHEET_NAME', 'Pupil Data');

define('PUPIL_BALANCE_EXCEL_SHEET_NAME', 'Pupil Balances');
$pupil_balance_export_cols = serialize(array('Pupil ID', 'Cash Balance', 'Card Balance', 'School', 'Pupil First Name', 'Pupil Middle Name', 'Pupil Last Name', 'Year Group', 'Class', 'Active', 'Pupil Duplicate', 'FSM', 'Adult', 'Username', 'Title', 'User First Name', 'User Last Name', 'Email', 'Home Telephone', 'Work Telephone', 'Mobile Number', 'Notify via Email', 'Notify via SMS'));
define('PUPIL_BALANCE_EXPORT_COLS', $pupil_balance_export_cols);

define('PUPILS_SYC_INVALID', 'Combined School, Year Group and Class in row '.REPLACE_STRING.' does not exist in system');
define('PUPILS_DUPLICATE', 'Duplicate Pupil found in import file.');
define('PUPILS_YES', 'yes');
define('PUPILS_NO', 'no');

define('PUPILS_YES_NO', 'Column '.REPLACE_STRING_COL.' must be Yes or No in row '.REPLACE_STRING.'.');
define('PUPILS_NUMERIC', '"Pupil Duplicate" must be numeric in row '.REPLACE_STRING.'.');
define('FSM_ADULT_EQUAL', '"FSM is not available for adults in row '. REPLACE_STRING.'.');
define('PUPIL_DATA_EXISTS_IN_DATABASE', 'Identical data already imported in row '.REPLACE_STRING.'.');
define('PUPIL_ID_INVALID', 'Pupil ID in row '.REPLACE_STRING.' does not exist for this school.');
define('PUPIL_ID_NOT_EXIST', 'Pupil ID in row '.REPLACE_STRING.' required to update existing information.');
define('PUPILS', 'pupils');
define('PUPIL_EMPTY_DATA', 'Blank value in row '.REPLACE_STRING.' is not allowed');
define('PUPIL_ID_INVALID_ADD', 'Pupil ID invalid or already assigned');

define('UNIT_TEST_FILE_TYPE', 'Excel2007');
define('EXPORT_FILE_TYPE', 'CSV');	// File Type
define('EXPORT_FILE_EXTENSION', '.csv');	// File extension
define('EXPORT_FILE_NAME', 'pupil_data');	// File name
define('EXPORT_PUPIL_NO_DATA', 'No records found.' );

define('EXPORT_PAYMENT_ITEMS_FILE_NAME', 'payment_items');	// File name
define('EXPORT_ORDER_ITEMS_FILE_NAME', 'order_items');	// File name
define('EXPORT_PUPIL_BALANCE_FILE_NAME', 'pupil_balance_reports');	// File name

define('CATERING_MENU_CYCLES_COUNT', 6);
define('CATERING_MENU_COUNT', 2);
define('CATERING_DEFAULT_MENU', 2);
define('WEEK_DAYS_COUNT', 5);
define('MENU_OPTIONS_COUNT', 12);

// Menu values
define('MAIN_MEAL', 'Main Meal');
define('SNACK', 'Snack Options');
define('DESERT', 'Desert');

define('EXPORT_HIGHCHARTS_FILE_NAME', 'chart');
define('EXPORT_CHART_WIDTH', 1000);
define('INVALID_SVG', 'Invalid SVG Format');
define('EXPORT_FOLDER_PERMISSION_ERROR', 'Folder creation failed');
define('SVG_ERROR', 'Error while converting SVG');
define('INVALID_TYPE_ERROR', 'Invalid type');

define('PAYMENT_TITLE_ROW_NO', 1);
$payment_file_cols = serialize(array('Transaction ID', 'Contract Name', 'School ID', 'School Name', 'Transaction Date', 'Username', 'Pupil ID', 'Cash-Amt', 'Card-Amt', 'Payment/Refund', 'Card-Trns', 'WorldPay Reference', 'WorldPay Authentication ID', 'Result'));
define('PAYMNET_TITLE_COLS', $payment_file_cols);
define('PAYMENT_EXCEL_COLUMN_NAME', 'A');
define('PAYMENT_EXCEL_SHEET_NAME', 'Payment Items');

define('ORDER_TITLE_ROW_NO', 1);
$order_file_cols = serialize(array('Order ID', 'School ID', 'School Name', 'Fulfilment Date ', 'Order Date ', 'Username', 'Pupil ID', 'First Name', 'Middle Name', 'Last Name', 'Year Group', 'Class', 'Active', 'Pupil Duplicate', 'FSM', 'Adult', 'Main Description', 'Main Net', 'Snack Description', 'Snack Net', 'Hospitality Description', 'Order Status', 'Impacted Batch', 'System Message', 'User Message', 'Card Net', 'Card VAT', 'Cash Net', 'Cash VAT', 'FSM Net', 'FSM VAT', 'A-Card Net', 'A-Card VAT', 'A-Cash Net', 'A-Cash VAT', 'A-SA Net', 'A-SA VAT', 'A-Hosp Net', 'A-Hosp VAT'));
define('ORDER_TITLE_COLS', $order_file_cols);
define('ORDER_EXCEL_COLUMN_NAME', 'A');
define('ORDER_EXCEL_SHEET_NAME', 'Order Items');

define('CARD', 1);
define('CASH', 0);
define('PAYMENT', 1);
define('REFUND', 0);
define('YES_PAY_MERCHANT_ID', '000000000000111');
define('YES_PAY_MERCHANT_ID_DUMMY', '0000000000002');
define('YES_PAY_PASSWORD', 'test');
define('PAYMENT_CSV', 'Payment');
define('REFUND_CSV',  'Refund');
define('TRANSACTION_ID_LENGTH', 11);
define('CARD_CODE', 'WP');
define('CASH_CODE', 'CA');
define('MEAL_ORDER', 1);
define('HOSPITALITY', 0);
define('MEAL_CODE', 'OM');
define('HOS_CODE', 'OH');
define('PAYMENT_ITEMS', 1);
define('ORDER_ITEMS', 0);
define('CREDIT_CARD', 'cc');
define('DEBIT_CARD', 'dc');
define('ORDER_STATUS_NEW', 0);
define('ORDER_STATUS_CANCEL', 1);
define('MEAL_TYPE_MAINMEAL', 11);
define('MEAL_TYPE_SNACK', 12);
define('MEAL_TYPE_DESERT', 13);

define('DEFAULT_SKIN_NAME', 'DEFAULT');

// Access logging statements 
define('LOG_REPLACE_STRING', '%###%');
define('LOGIN_SUCCESSFUL', 'Successful login');
define('LOGOUT_SUCCESSFUL', 'Successful logout');
define('PROFILE_REQUEST', 'Session requested to access the profile');
define('PROFILE_SUCCESS_REQUEST', 'Profile access granted');
define('PROFILE_FAIL_REQUEST', 'Profile access failure');
define('LOGIN_PROFILE', 'Profile access granted. Logged in to '. LOG_REPLACE_STRING .' profile');
define('LOG_GET_MONTHLY_CHART', 'Accessed energy monthly chart');
define('LOG_GET_HH_CHART', 'Accessed energy half hourly chart');
define('LOG_GET_DAILY_CHART', 'Accessed energy daily chart');
define('LOG_GET_NHH_CHART', 'Accessed energy non half hourly chart');
define('LOG_GET_TARGET_DATA', 'Accessed energy target data');
define('LOG_GET_SETUP_DATA', 'Accessed energy setup entities');
define('LOG_GET_DATA_HISTORY', 'Accessed energy documents history');
define('LOG_ADD_PUPIL', 'Added pupil with pupil ID '.LOG_REPLACE_STRING.'.');
define('LOG_GET_PUPILS', 'Accessed pupils list');
define('LOG_UNASSIGN_PUPIL', 'Unassigned pupil with pupil ID '.LOG_REPLACE_STRING.'.');
define('LOG_EDIT_PUPILS', 'Pupils information updated');
define('LOG_GET_SCHOOL_MENU_DETAILS', 'Accessed school menu options');
define('LOG_SAVE_SCHOOL_MENU_DETAILS', 'Modified school menu options');
define('LOG_GET_PUPILS_ORDER_MENU', 'Accessed pupils menu');
define('LOG_GET_PUPILS_ORDER_MENU_DETAILS', 'Accessed pupils menu options');
define('LOG_SAVE_PUPIL_ORDER', 'Items added/removed for Order id '.LOG_REPLACE_STRING.'.');
define('LOG_CANCEL_PUPIL_ORDER', 'Order cancelled for Order id '.LOG_REPLACE_STRING.'.');
define('LOG_SEARCH_PUPILS', 'Done pupils search');
define('LOG_CASH_REFUND', 'Cash refund done for ');
define('LOG_CASH_PAYMENT', 'Cash payment done for ');
define('LOG_CASH_HISTORY', 'Accessed cash history');
define('LOG_GET_PUPILS_TOPAY', 'Accessed pupils list for card payment');
define('LOG_CARD_PAYMENT', 'Card payment done for ');
define('LOG_CARD_HISTORY', 'Accessed card history');
define('LOG_SCHOOL_MEAL_COL', 'Accessed schools in daily meal collection');
define('LOG_YEAR_CLASS_MEAL_COL', 'Accessed year and class in daily meal collection');
define('LOG_STUDENTS_MEAL_COL', 'Accessed students information in daily meal collection');
define('LOG_COLLECTED_MEAL_COL', 'Order Id '.LOG_REPLACE_STRING.' is collected');
define('LOG_MEAL_ORDER_SUMMARY', 'Accessed kitchen meal order summary');
define('LOG_GET_SCHOOLS_ADMINS', 'Accessed contract and schools informations');
define('LOG_GET_SCHOOL_DETAILS', 'Accessed contract and schools years/classes and school admins informations');
define('LOG_SCHOOL_DOCUMENT', 'A school document is uploaded');
define('LOG_GET_SCHOOL_DOC', 'Accessed school documents');
define('LOG_GET_SCHOOL_DOC_COM', 'Accessed school document comments');
define('LOG_GET_SCHOOL_DOC_COM_UP', 'Updated school document comments');
define('LOG_GET_SCHOOL_DOC_COM_ADD', 'Added school document comments');
define('LOG_GET_SCHOOL_DOC_COM_DEL', 'School document comments deleted');
define('LOG_ENERGY_DOCUMENT', 'A energy document is uploaded');
define('LOG_GET_ENERGY_DOC', 'Accessed energy documents');
define('LOG_GET_ENERGY_DOC_COM', 'Accessed energy document comments');
define('LOG_GET_ENERGY_DOC_COM_UP', 'Updated energy document comments');
define('LOG_GET_ENERGY_DOC_COM_ADD', 'Added energy document comments');
define('LOG_GET_ENERGY_DOC_COM_DEL', 'Energy document comments deleted');
define('LOG_MYPROFILE_UPDATE', 'My profile information updated');
define('LOG_SCHOOL_REQUEST', 'Session requested for school access');
define('LOG_SCHOOL_SUCCESS_REQUEST', 'School access are verified. Access granted');
define('LOG_SCHOOL_FAIL_REQUEST', 'Schools access are verified. Access denied');
define('LOG_STUDENT_REQUEST', 'Session requested for pupils access');
define('LOG_STUDENT_SUCCESS_REQUEST', 'Pupils access are verified. Access granted');
define('LOG_STUDENT_FAIL_REQUEST', 'Pupils access are verified. Access denied');
define('LOG_CA_GET_USERS', 'Accessed user list');
define('LOG_CA_UPDATE_USERS', 'Accessed to update user');
define('LOG_CA_CREATE_USER', 'Accessed to create user');
define('LOG_CA_CHECK_USER_DUPLICATE', 'Check if username is already exists or not');
define('LOG_CA_CHECK_USER_DUPLICATE_EXISTS', 'Username already exists. Permission denied');
define('LOG_CA_CHECK_USER_DUPLICATE_NOT_EXISTS', 'Username not exists. Access for user creation granted');
define('LOG_CA_CREATE_CONTRACT', 'Accessed to create contract');
define('LOG_CA_CHECK_CONTRACT_DUPLICATE', 'Check if contract is already exists or not');
define('LOG_CA_CHECK_CONTRACT_DUPLICATE_EXISTS', 'Contract already exists. Permission denied');
define('LOG_CA_CHECK_CONTRACT_DUPLICATE_NOT_EXISTS', 'Contract not exists. Access for contract creation granted');
define('LOG_CA_CREATE_SCHOOL', 'Accessed to create school');
define('LOG_CA_CHECK_SCHOOL_DUPLICATE', 'Check if school is already exists or not');
define('LOG_CA_CHECK_SCHOOL_DUPLICATE_EXISTS', 'School already exists. Permission denied');
define('LOG_CA_CHECK_SCHOOL_DUPLICATE_NOT_EXISTS', 'School not exists. Access for school creation granted');
define('LOG_CA_EDIT_PROFILE', 'Accessed to edit profile');
define('LOG_CA_GET_CONTRACTS', 'Accessed to get contract list');
define('LOG_CA_EDIT_CONTRACTS', 'Accessed to edit contract');
define('LOG_CA_EDIT_CONTRACT_DUPLICATE_NOT_EXISTS', 'Contract not exists. Access for edit contract granted');
define('LOG_CA_EDIT_SCHOOL', 'Accessed to edit schools');
define('LOG_CA_SCHOOL_CUSTOMER_REQUEST', 'Requested for school access');
define('LOG_CA_SCHOOL_CUSTOMER_SUCCESS_REQUEST', 'School, Customer, Contract are verified. Access granted');
define('LOG_CA_SCHOOL_CUSTOMER_FAIL_REQUEST', 'School, Customer, Contract are verified. Access denied');
define('LOG_CA_EDIT_SCHOOL_DUPLICATE_NOT_EXISTS', 'School not exists. Access for school creation granted');
define('LOG_CA_UPDATE_SCHOOL', 'Accessed to update school status');
define('LOG_CA_PUPIL_IMPORT', 'Accessed to import pupils');
define('LOG_CA_GET_CONTRACT_SETTINGS', 'Accessed to get the contract settings');
define('LOG_CA_UPDATE_CONTRACT_SETTINGS', 'Accessed to update the contract settings');
define('LOG_CA_GET_MENU_DETAILS', 'Accessed to get menu details');
define('LOG_CA_SAVE_MENU_DETAILS', 'Accessed to save menu details');
define('LOG_CA_UPDATE_MENU_OPTION_STATUS', 'Accessed to update menu option status');
define('LOG_CA_GET_SEARCH_PUPILS', 'Accessed to get pupils using searching criteria');
define('LOG_CA_SAVE_CARD_REFUND', 'Accessed to save pupil card refund');
define('LOG_CA_CARD_FULL_HISTORY', 'Accessed to get card full history details');
define('LOG_CA_SESSION_LOGS', 'Accessed to get session logs');
define('LOG_CA_SESSION_LOGS_HISTORY', 'Accessed to get session logs history');
define('LOG_CA_SAVE_SESSION_LOGS', 'Accessed to save user information for session logs');
define('LOG_CA_PURGE_SESSION_LOGS', 'Accessed to delete session logs history');
define('LOG_CA_GET_MASTER_PROFILE_DETAILS', 'Accessed to get master profile details');
define('LOG_CA_GET_PROFILE_DETAILS', 'Accessed to get profile details');
define('LOG_CA_SAVE_PROFILE_DETAILS', 'Accessed to save profile details');
define('LOG_CA_CREATE_PROFILE', 'Accessed to create profile');
define('LOG_CA_CHECK_PROFILE_DUPLICATE', 'Check if profile is already exists or not');
define('LOG_CA_CHECK_PROFILE_DUPLICATE_EXISTS', 'Profile already exists. Permission denied');
define('LOG_CA_CHECK_PROFILE_DUPLICATE_NOT_EXISTS', 'Profile not exists. Access granted');
define('LOG_CA_DELETE_PROFILE_DETAILS', 'Accessed to delete profile details');
define('LOG_CA_DELETE_ENERGY_DETAILS', 'Accessed to delete energy details');
define('LOG_CA_GET_USERS_CONFIGURE_CONTRACT', 'Accessed to get user list configured to contract');
define('LOG_CA_SAVE_USERS_CONFIGURE_CONTRACT', 'Accessed to save the users for contract');
define('LOG_CA_GET_SCHOOLS', 'Accessed to get schools');
define('LOG_CA_GET_SCHOOL_DETAILS', 'Accessed to get school details');
define('LOG_CA_SAVE_SCHOOL_DETAILS', 'Accessed to save school details');
define('LOG_CA_SCHOOL_DOCUMENTS', 'Accessed to get school documents');
define('LOG_CA_GET_SCHOOL_DOCUMENT_COMMENTS', 'Accessed to get school document comments');
define('LOG_CA_UPDATE_SCHOOL_DOCUMENT_COMMENTS', 'Accessed to update school document comments');
define('LOG_CA_INSERT_SCHOOL_DOCUMENT_COMMENTS', 'Accessed to insert school document comments');
define('LOG_CA_DELETE_SCHOOL_DOCUMENTS', 'Accessed to delete school documents');
define('LOG_CA_GET_ENERGY_DOCUMENTS', 'Accessed to get energy documents');
define('LOG_CA_INSERT_ENERGY_DOCUMENT_COMMENTS', 'Accessed to insert energy document comments');
define('LOG_CA_UPDATE_ENERGY_DOCUMENT_STATUS', 'Accessed to update energy document status');
define('LOG_CA_GET_ENERGY_DOCUMENT_COMMENTS', 'Accessed to get energy document comments');
define('LOG_CA_DELETE_ENERGY_DOCUMENT', 'Accessed to delete energy document');
define('LOG_USER_CUSTOMER_REQUEST', 'Session requested for User Id & customer Id access');
define('LOG_USER_CUSTOMER_SUCCESS_REQUEST', 'User Id & Customer Id are verified. Access granted');
define('LOG_USER_CUSTOMER_FAIL_REQUEST', 'User Id & Customer Id access are verified. Access denied');
define('LOG_CONTRACT_REQUEST', 'Session requested for contract access');
define('LOG_CONTRACT_SUCCESS_REQUEST', 'Contract access is verified. Access granted');
define('LOG_CONTRACT_FAIL_REQUEST', 'Contract access is verified. Access denied');
define('LOG_STUDENT_CONTRACT_REQUEST', 'Session requested for Student Id & Contract Id access');
define('LOG_STUDENT_CONTRACT_SUCCESS_REQUEST', 'Student Id & Contract Id access is verified. Access granted');
define('LOG_STUDENT_CONTRACT_FAIL_REQUEST', 'Student Id & Contract Id access is verified. Access denied');
define('LOG_CA_EXPORT_PUPILS', 'Accessed to export pupils');
define('LOG_CA_EXPORT_SCHOOL_PUPILS', 'Accessed to export school pupils');
define('LOG_CA_EXPORT_HIGH_CHARTS', 'Accessed to export High Charts');
define('LOG_CA_EXPORT_PAYMENT_ITEMS', 'Accessed to export Payment Items');
define('LOG_CA_EXPORT_ORDER_ITEMS', 'Accessed to export Order Items');
define('LOG_CA_IMPORT_SETUP_FILES', 'Accessed to import Setup data');
define('LOG_CA_IMPORT_HALF_HOURLY_CHARTS', 'Accessed to import Half Hourly data');
define('LOG_CA_IMPORT_NON_HALF_HOURLY_DATA', 'Accessed to import Non Half Hourly data');
define('LOG_CA_IMPORT_TARGET_DATA', 'Accessed to import Target data');
define('LOG_CA_IMPORT_SCHOOL_DOCUMENT', 'Accessed to import school documents');
define('LOG_CA_IMPORT_ENERGY_DOCUMENT', 'Accessed to import energy documents');
define('LOG_CA_IMPORT_IMPORT_PUPILS', 'Accessed to import school pupils');

/* Customer Admin Level */
define('FROM_GET_USERS', 'Home/Configure Contract/Users');
define('FROM_CREATE_USERS', 'Home/Configure Contract/Users');
define('FROM_UPTATE_USER_STATUS', 'Home/Configure Contract/Users');
define('FROM_CREATE_SCHOOL', 'Home/School Configuration/Servery\'s');
define('FROM_GET_SCHOOLS', 'Home/School Configuration/Servery\'s');
define('FROM_EDIT_CONTRACT', 'Home/Configure Contract/Settings');
define('FROM_EDIT_SCHOOLS', 'People Services/School Configuration/Servery\'s');
define('FROM_UPDATE_SCHOOL_STATUS', 'People Services/School Configuration/Servery\'s');
define('FROM_GET_HH_REPORTS', 'Building Services/Energy/HH Data');
define('FROM_IMPORT_HH_REPORTS', 'Building Services/Energy/HH Data');
define('FROM_GET_NHH_REPORTS', 'Building Services/Energy/NHH Data');
define('FROM_IMPORT_NHH_REPORTS', 'Building Services/Energy/NHH Data');
define('FROM_GET_TARGET_DATA', 'Building Services/Energy/Target Data');
define('FROM_IMPORT_TARGET_DATA', 'Building Services/Energy/Target Data');
define('FROM_GET_SETUP_ENTITIES', 'Building Services/Energy/Setup Data');
define('FROM_IMPORT_SETUP_ENTITIES', 'Building Services/Energy/Setup Data');
define('FROM_GET_DATA_HISTORY', 'Building Services/Energy/Setup Data');
define('FROM_EXPORT_HIGH_CHARTS', 'Building Services/Energy');
define('FROM_EXPORT_SCHOOL_PUPILS', 'People Services/Catering/School Office/My Schools');
define('FROM_GET_SCHOOL_DETAILS', 'People Services/Catering/School Office/My Schools');
define('FROM_SAVE_SCHOOL_DETAILS', 'People Services/Catering/School Office/My Schools');
define('FROM_IMPORT_SCHOOL_DOCUMENTS', 'People Services/Catering/Documents');
define('FROM_GET_SCHOOL_DOCUMENTS', 'People Services/Catering/Documents');
define('FROM_GET_SCHOOL_DOCUMENT_COMMENTS', 'People Services/Catering/Documents');
define('FROM_UPDATE_SCHOOL_DOCUMENT_STATUS', 'People Services/Catering/Documents');
define('FROM_INSERT_DOCUMENT_COMMENTS', 'People Services/Catering/Documents');
define('FROM_DELETE_DOCUMENT', 'People Services/Catering/Documents');
define('FROM_EXPORT_PAYMENT_ITEMS', 'People Services/Catering/Accounts/Reports');
define('FROM_EXPORT_PUPIL_BALANCES', 'People Services/Catering/Accounts/Reports');
define('FROM_EXPORT_ORDER_ITEMS', 'People Services/Catering/Accounts/Reports');
define('FROM_IMPORT_PUPILS', 'People Services/Catering/Pupil Import');
define('FROM_EXPORT_PUPILS', 'People Services/Catering/Pupil Import');
define('FROM_GET_PUPIL_IMPORT', 'People Services/Catering/Pupil Import');
define('FROM_IMPORT_ENERGY_DOCUMENTS', 'Building Services/Energy/Documents');
define('FROM_GET_ENERGY_DOCUMENTS', 'Building Services/Energy/Documents');
define('FROM_INSERT_ENERGY_DOCUMENT_COMMENTS', 'Building Services/Energy/Documents');
define('FROM_UPDATE_ENERGY_DOCUMENT_STATUS', 'Building Services/Energy/Documents');
define('FROM_GET_ENERGY_DOCUMENT_COMMENTS', 'Building Services/Energy/Documents');
define('FROM_DELETE_ENERGY_DOCUMENT', 'Building Services/Energy/Documents');
define('FROM_GET_CONTRACT_SETTINGS', 'People Services/Catering/Settings');
define('FROM_UPDATE_CONTRACT_SETTINGS', 'People Services/Catering/Settings');
define('FROM_UPDATE_MENU_OPTION_STATUS', 'People Services/Catering/School Configuration/Menus');
define('FROM_GET_MENU_DETAILS', 'People Services/Catering/School Configuration/Menus');
define('FROM_SAVE_MENU_DETAILS', 'People Services/Catering/School Configuration/Menus');
define('FROM_GET_CARD_SEARCH_PUPILS', 'People Services/Catering/Accounts/Card Refunds');
define('FROM_SAVE_CARD_REUND', 'People Services/Catering/Accounts/Card Refunds');
define('FROM_GET_CARD_FULL_HISTORY', 'People Services/Catering/Accounts/Card Refund History');
define('FROM_GET_SESSION_LOG_CONTRACT', 'Home/System Admin/Session Logs');
define('FROM_GET_SESSION_LOG_NAVIGATION', 'Home/System Admin/Session Logs');
define('FROM_SAVE_SESSION_LOG_CONTRACT', 'Home/System Admin/Session Logs');
define('FROM_PURGE_SESSION_LOG_CONTRACT', 'Home/System Admin/Session Logs');
define('FROM_GET_PROFILE_MASTER_DETAILS', 'Home/Configure Contract/Profiles');
define('FROM_CREATE_PROFILE_CONTRACT', 'Home/Configure Contract/Profiles');
define('FROM_GET_PROFILE_DETAILS_CONTRACT', 'Home/Configure Contract/Profiles');
define('FROM_SAVE_PROFILE_DETAILS', 'Home/Configure Contract/Profiles');
define('FROM_DELETE_PROFILE_DETAILS', 'Home/Configure Contract/Profiles');
define('FROM_PURGE_ENERGY_DATA', 'Building Services/Energy/Purge Data');
define('FROM_GET_USERS_CONFIGURE_CONTRACT', 'Home/Configure Contract/Administrators');
define('FROM_SAVE_USERS_CONFIGURE_CONTRACT', 'Home/Configure Contract/Administrators');
define('FROM_GET_SKINS', 'Home/Configure Contract/Skins');
define('FROM_GET_SKIN_DETAILS', 'Home/Configure Contract/Skins');
define('FROM_CREATE_SKIN', 'Home/Configure Contract/Skins');
define('FROM_EDIT_SKINS', 'Home/Configure Contract/Skins');
define('FROM_DELETE_SKINS', 'Home/Configure Contract/Skins');
define('FROM_GET_MEAL_ORDER_SUMMARY', 'People Services/Catering/School Office/Meal Order Summary');
define('FROM_GET_ZONE_DASHBOARD', 'Resource Management/Asset Tracking/Zone Dashboard');
define('FROM_ADD_ZONE', 'Resource Management/Asset Tracking/Zone Dashboard');
define('FROM_ADD_ASSET', 'Resource Management/Asset Tracking/Asset Dashboard');
define('FROM_SCHOOL_CLOSE', 'People Services/Catering/School Office/My Schools');
define('FROM_BATCH_ORDER_CANCELLATION', 'People Services/Catering/School Office/Batch Order Cancellation');
define('FROM_EXPORT_BATCH_ORDER_ITEMS', 'People Services/Catering/School Office/Batch Order Cancellation');
define('FROM_UPDATE_BATCH_ORDER_CANCEL_ORDER', 'People Services/Catering/School Office/Batch Order Cancellation');
define('FROM_UPDATE_BATCH_ORDER_USER_MESSAGE', 'People Services/Catering/School Office/Batch Order Cancellation');
define('FROM_UPDATE_BATCH_ORDER_CLEAR_FLAG', 'People Services/Catering/School Office/Batch Order Cancellation');
define('FROM_GET_DIGITAL_PEN_APP', 'People Services/Configure Contract/Digital Pens');
define('FROM_QUALITY_AUDIT', 'People Services/Configure Contract/External Interfaces/Quality Audit');

/* User Level */
define('USER_FROM_GET_MONTHLY_CHART_SUMMARY', 'Building Services/Energy/Summary/Annual');
define('USER_FROM_GET_MONTHLY_CHART_GAS', 'Building Services/Energy/Gas/Annual');
define('USER_FROM_GET_MONTHLY_CHART_ELECTRICITY', 'Building Services/Energy/Electricity/Annual');
define('USER_FROM_GET_DAILY_CHART_SUMMARY', 'Building Services/Energy/Summary/Monthly');
define('USER_FROM_GET_DAILY_CHART_GAS', 'Building Services/Energy/Gas/Monthly');
define('USER_FROM_GET_DAILY_CHART_ELECTRICITY', 'Building Services/Energy/Electricity/Monthly');
define('USER_FROM_GET_HH_CHART_SUMMARY', 'Building Services/Energy/Summary/Half Hourly');
define('USER_FROM_GET_HH_CHART_ELECTRICITY', 'Building Services/Energy/Electricity/Half Hourly');
define('USER_FROM_GET_SCHOOLS_ORDERS', 'People Services/Catering/My Orders');
define('USER_FROM_GET_SCHOOLS_ADMINS', 'People Services/Catering/School Office');
define('USER_FROM_CHECK_ALL_SCHOOL_STATUS', 'People Services/Catering/Documents');
define('USER_FROM_GET_SCHOOL_DETAILS', 'People Services/Catering/School Office/My Schools');
define('USER_FROM_SAVE_SCHOOL_DETAILS', 'People Services/Catering/School Office/My Schools');
define('USER_FROM_IMPORT_SCHOOL_DOCUMENTS', 'People Services/Catering/Documents');
define('USER_FROM_GET_SCHOOL_DOCUMENTS', 'People Services/Catering/Documents');
define('USER_FROM_GET_SCHOOL_DOCUMENT_COMMENTS', 'People Services/Catering/Documents');
define('USER_FROM_UPDATE_SCHOOL_DOCUMENT_STATUS', 'People Services/Catering/Documents');
define('USER_FROM_INSERT_DOCUMENT_COMMENTS', 'People Services/Catering/Documents');
define('USER_FROM_DELETE_DOCUMENT', 'People Services/Catering/Documents');
define('USER_FROM_IMPORT_ENERGY_DOCUMENTS', 'Building Services/Energy/Documents');
define('USER_FROM_GET_ENERGY_DOCUMENTS', 'Building Services/Energy/Documents');
define('USER_FROM_INSERT_ENERGY_DOCUMENT_COMMENTS', 'Building Services/Energy/Documents');
define('USER_FROM_UPDATE_ENERGY_DOCUMENT_STATUS', 'Building Services/Energy/Documents');
define('USER_FROM_GET_ENERGY_DOCUMENT_COMMENTS', 'Building Services/Energy/Documents');
define('USER_FROM_DELETE_ENERGY_DOCUMENT', 'Building Services/Energy/Documents');
define('USER_FROM_ADD_PUPIL', 'People Services/Catering/My Pupils');
define('USER_FROM_GET_PUPILS', 'People Services/Catering/My Pupils');
define('USER_FROM_PUPIL_UNASSIGN', 'People Services/Catering/My Pupils');
define('USER_FROM_EDIT_PUPILS', 'People Services/Catering/My Pupils');
define('USER_FROM_MANAGE_PUPILS', 'People Services/Catering/My Pupils/Manage Pupils');
define('USER_FROM_GET_SCHOOL_MENU_DETAILS', 'People Services/Catering/School Office/My Menus');
define('USER_FROM_SAVE_SCHOOL_MENU_DETAILS', 'People Services/Catering/School Office/My Menus');
define('USER_FROM_GET_PUPILS_ORDER_MENU', 'People Services/Catering/My Orders');
define('USER_FROM_GET_PUPIL_ORDER_MENU_DETAILS', 'People Services/Catering/My Orders');
define('USER_FROM_SEARCH_PUPILS', 'People Services/Catering/Payments');
define('USER_FROM_ORDER_SEARCH_PUPILS', 'People Services/Catering/My Orders');
define('USER_FROM_SAVE_CASH_REFUND', 'People Services/Catering/Payments/Make Cash Refund');
define('USER_FROM_MAKE_CASH_PAYMENT', 'People Services/Catering/Payments/Make Cash Payment');
define('USER_FROM_GET_FULL_HISTORY', 'People Services/Catering/Payments/School Payment History');
define('USER_FROM_GET_PUPILS_TOPAY', 'People Services/Catering/Payments/Make Card Payment');
define('USER_FROM_MAKE_CARD_PAYMENT', 'People Services/Catering/Payments/Make Card Payment');
define('USER_FROM_GET_CARD_PAYMENT_HISTORY', 'People Services/Catering/Payments/Make Card Payment');
define('USER_FROM_SAVE_ORDER_ITEMS', 'People Services/Catering/My Orders');
define('USER_FROM_CANCEL_ORDER_ITEMS', 'People Services/Catering/My Orders');
define('USER_FROM_GET_SCHOOLS_MEAL_COLLECTION', 'People Services/Catering/School Office/Daily Meal Collection');
define('USER_FROM_GET_DAILY_MEAL_COLLECTION_YEAR_CLASS', 'People Services/Catering/School Office/Daily Meal Collection');
define('USER_FROM_GET_DAILY_MEAL_COLLECTION_STUDENTS', 'People Services/Catering/School Office/Daily Meal Collection');
define('USER_FROM_UDPATE_DAILY_MEAL_COLLECTION_STATUS', 'People Services/Catering/School Office/Daily Meal Collection');
define('USER_FROM_GET_MEAL_ORDER_SUMMARY', 'People Services/Catering/School Office/Meal Order Summary');
define('USER_FROM_GET_ZONE_DASHBOARD', 'Resource Management/Asset Tracking/Zone Dashboard');
define('USER_FROM_PATIENT_CAT_DIG_FORMS', 'People Services/Patient Catering/Digital Forms');
define('USER_FROM_PATIENT_CAT_TOTAL_MEAL_NUMBERS', 'People Services/Patient Catering/Total Daily Meal Numbers');
define('USER_FROM_QUALITY_AUDIT_DASHBOARD', 'Performance Reports/Quality Audit/Report Graphs');
define('USER_FROM_DAILY_MEAL_ORDERS', 'People Services/Patient Catering/Daily Meal Orders');
define('USER_FROM_REPORT_BUILDER', 'Performance Reports/Quality Audit/Report Builder');
define('USER_FROM_INVOICE_ORDERS', 'People Services/Catering/School Office/Invoice Orders');


/* Hospitality Orders start */
define('HOSP_TYPE_INVOCIE', 0);
define('HOSP_TYPE_PUPIL_DEBT', 1);
define('INVOICE_ORDER_COUNT', 10);

 /*Hospitality Orders end */
/* Session Log static messages */
define('LOG_READ', 'Read:');
define('LOG_WRITE', 'Write:');
define('LOG_AUTHORISED', ' - authorised');
define('LOG_NOT_AUTHORISED', ' - Not authorised');

define('SESSION_LOG_NAVIGATION_COUNT', 50);
define('PARENT_PAYMENT_HISTORY_NAVIGATION_COUNT', 20);
define('SCHOOL_PAYMENT_HISTORY_NAVIGATION_COUNT', 20);
define('CARD_REFUND_NAVIGATION_COUNT', 20);
define('ZONE_DASHBOARD_COUNT',12);
define('DIGITAL_FROMS_COUNT',50);

// Skins definitions
define('SKIN_FILE_FORMAT', 'png');
define('SKIN_DEFAULT_VALUES', serialize(array(
											"logo_path"=>'logo.png',
											"smartphone_path"=>'smartphone.png',
											"header_div_path"=>'header_div.png',
											"header_link_color"=>'77caff',
											"header_link_hcolor"=>'000000',
											"headings_color"=>'004976',
											"page_link_color"=>'004976',
											"page_link_hcolor"=>'000000',
											"page_bgcolor"=>'ebebeb',
											"level12_bg_path"=>'level12_bg.png',
											"level2_bg_path"=>'level2_bg.png',
											"no_nav_path"=>'no_nav.png',
											"widget_header_path"=>'widget_header.png',
											"select_bg_path"=>'select_bg.png',
											"left_foot_block_title"=>'Useful links',
											"left_foot_block_d1"=>'Documentation',
											"left_foot_block_11"=>'#',
											"left_foot_block_d2"=>'Contact us',
											"left_foot_block_l2"=>'#',
											"left_foot_block_d3"=>'Privacy',
											"left_foot_block_l3"=>'#',
											"left_foot_block_d4"=>'Policy',
											"left_foot_block_l4"=>'#',
											"right_foot_block_title"=>'About Initial',
											"right_foot_block_d1"=>'About us',
											"right_foot_block_11"=>'#',
											"right_foot_block_d2"=>'Contact us',
											"right_foot_block_l2"=>'#',
											"right_foot_block_d3"=>'Careers',
											"right_foot_block_l3"=>'#',
											"right_foot_block_d4"=>'Media',
											"right_foot_block_l4"=>'#',
											"left_foot_block_s1"=>ACTIVE,
											"left_foot_block_s2"=>ACTIVE,
											"left_foot_block_s3"=>ACTIVE,
											"left_foot_block_s4"=>ACTIVE,
											"right_foot_block_s1"=>ACTIVE,
											"right_foot_block_s2"=>ACTIVE,
											"right_foot_block_s3"=>ACTIVE,
											"right_foot_block_s4"=>ACTIVE,
											"footer_copy_text"=>"&copy;2012 Rentokil Initial plc. Registered in England 5393279.  Registered office: 2 City Place, Beehive Ring Road, Gatwick, RH6 0HA.  The names Rentokil&reg; and Initial&reg; are registered trade marks.",
											)));
$skin_default_css = "h1.logo a {
						background: transparent url('logo.png') no-repeat;
					}
					@media (max-width: 767px) {
						h1.logo a {
							background: transparent url('smartphone.png') no-repeat;
						}
					}
					userlogininfo .accountlinks {
						color: #@@@(header_link_color)@@@;
					}
					.userlogininfo .accountlinks a {
						color: #@@@(header_link_color)@@@;
					}
					.userlogininfo .accountlinks a:hover {
						color: #@@@(header_link_hcolor)@@@;
					}
					h3 {
						color: #@@@(headings_color)@@@;
					}
					h5 {
						color: #@@@(headings_color)@@@;
					}
					footer h3 {
						color:#@@@(headings_color)@@@;
					}
					footer div.footercontent span {
						color: #@@@(headings_color)@@@;
					}
					div.footercontent span.footerContentlinks a, div.footercontent span.footerContentlinks a:visited {
						color: #8e8e8e!important;
						text-decoration: none;
					}
					div.footercontent span.footerContentlinks a:hover {
						color: #8e8e8e!important;
                                                text-decoration: underline;
					}
					.modal-header h3 {
						color:#@@@(headings_color)@@@;
					}
					a {
						color: #@@@(page_link_color)@@@;
					}
					a:hover {
						color: #@@@(page_link_hcolor)@@@;
					}
					body {
						background-image:url('level12_bg.png');
						background-color: #@@@(page_bgcolor)@@@;
						background-repeat:repeat-x;
					}
					body.guest {
						background-image:url('level2_bg.png');
						background-repeat:repeat-x;
					}
					body.dashboard{
						background-image:url('no_nav.png');
						background-repeat:repeat-x;
					}
					.widget {
						background: #fff url('widget_header.png') repeat-x top;
					}
					select {
						background-image: url('select_bg.png');
					}
					span.select-wrap {
						background: url('select_bg.png') no-repeat right 0px;
					}";
define('SKIN_DEFAULT_CSS', $skin_default_css);
define('SKIN_HEADER_COLOR_REPLACE_TAG', '#@@@(header_link_color)@@@');
define('SKIN_HEADER_HOVER_COLOR_REPLACE_TAG', '#@@@(header_link_hcolor)@@@');
define('SKIN_HEADINGS_COLOR_REPLACE_TAG', '#@@@(headings_color)@@@');
define('SKIN_PAGE_LINK_COLOR_REPLACE_TAG', '#@@@(page_link_color)@@@');
define('SKIN_PAGE_LINK_HOVER_COLOR_REPLACE_TAG', '#@@@(page_link_hcolor)@@@');
define('SKIN_PAGE_BG_COLOR_REPLACE_TAG', '#@@@(page_bgcolor)@@@');

define('SKIN_CSS_FILE_EXTENSION', '.css');

/* CAdmin - profile access. Values are ad_ss_mod_id from ad_ss_mod_id table */
define('USERS_ACCESS', 2);
define('ADMINISTRATORS_ACCESS', 3);
define('CONFIGURE_SETTING_ACCESS', 4);
define('PROFILES_ACCESS', 5);
define('SKINS_ACCESS', 6);
define('SESSION_LOGS_ACCESS', 7);
define('PHP_INFO_ACCESS', 8);
define('PUPIL_IMPORT_ACCESS', 9);
define('DOCUMENTS_ACCESS', 10);
define('SETTINGS_ACCESS', 11);
define('SCHOOL_CONFIGURATION_ACCESS', 12);
define('SERVEYS_ACCESS', 13);
define('MENUS_ACCESS', 14);
define('SCHOOL_OFFICE_ACCESS', 15);
define('MY_SCHOOLS_ACCESS', 16);
define('MEAL_ORDER_SUMMARY_ACCESS', 17);
define('ACCOUNTS_ACCESS', 18);
define('REPORTS_ACCESS', 19);
define('CARD_REFUNDS_ACCESS', 20);
define('CARD_REFUND_HISTORY_ACCESS', 21);
define('SETUP_DATA_ACCESS', 22);
define('TARGET_DATA_ACCESS', 23);
define('HH_DATA_ACCESS', 24);
define('NHH_DATA_ACCESS', 25);
define('PURGE_ACCESS', 26);
define('ENERGY_DOCUMENTS_ACCESS', 27);
define('ZONE_DASHBOARD_ACCESS', 28);
define('ASSET_DASHBOARD_ACCESS', 29);
define('DIGITAL_PENS_ACCESS', 35);
define('DIGITAL_PEN_ACCESS', 36);
define('QUALITY_AUDIT_ACCESS', 37);

define('EDEN_CHECK_QA', 'riwebqa');
define('EDN_CHECK_LOCALHOST', 'localhost');
define('EDEN_CHECK_FMINISIGHT', 'fminsight.net');

define('ALPHABETS_SMALL', 'abcdefghijklmnopqrstuvwxyz');
define('ALPHABETS_CAPITAL', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
define('SPECIAL_CHARACTERS', '!@$#*<>');
define("NUMBERS", '0123456789');

define('TRANSACTION_INITIATED', 'Transaction initiated');
define('YES_PAY_CANCEL_ERROR_CODE', '555');
define('YES_PAY_REFUND_URL', 'https://www.yes-pay.net/embossServlet/refund');
define('YES_PAY_SUCCESS', 0);
define('NO_REFUND', 'No refund available');

define('SCHOOL_CLOSE_DATA_ID', 43);
define('MAIN_DESCRIPTION_DATA_ID', 35);
define('SNACK_DESCRIPTION_DATA_ID', 37);
define('SERVERY_PRODUCTION_RELATIONSHIP_DATA_ID', 41);
define('SCHOOL_ENABLED_DISABLED_DATA_ID', 42);
define('PUPIL_ACTIVE_DATA_ID', 32);
define('PUPIL_FSM_DATA_ID', 33);
define('PUPIL_ADULT_DATA_ID', 34);
define('MENU_ITEM_DESELECT_DATA_ID', 44);
define('MENU_START_DATE_DATA_ID', 39);
define('MENU_CYCLE_DATA_ID', 40);
define('INVOICE_MEALS_DATA_ID', 45);

define('BATCH_ORDER_DATE_TIME_FORMAT', '%d/%m/%Y %H:%i');
define('BATCH_ORDER_CANCELLATION_PAGINATION_COUNT', 10);

define('SCHOOL_REPLACE_STRING', '%$SCHOOL_NAME$%');
define('NAME_REPLACE_STRING', '%$NAME$%');
define('DATE_REPLACE_STRING', '%$DATE$%');
define('SCHOOL_CLOSED_TILL_REPLACE_STRING', '%$CLOSED_DATE$%');
define('REASON_REPLACE_STRING', '%$REASON$%');
define('MENU_NUMBER_REPLACE_STRING', '%MENU_NUMBER%');
define('WEEK_NUMBER_REPLACE_STRING', '%$WEEK_NUMBER$%');
define('MENU_PREVIOUS_CYCLE', '%$MENU_PREVIOUS_CYCLE$%');
define('MENU_CURRENT_CYCLE', '%$MENU_CURRENT_CYCLE$%');
define('MENU_PREVIOUS_START_DATE', '%$MENU_PREVIOUS_START_DATE$%');
define('MENU_CURRENT_START_DATE', '%$MENU_CURRENT_START_DATE$%');
define('SCHOOL_NAME_PREVIOUS', '%$SCHOOL_NAME_PREVIOUS$%');

define('PUPIL_ACTIVE_MESSAGE', 'Pupil has been reactivated by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('PUPIL_DEACTIVE_MESSAGE', 'Pupil has been deactivated by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('PUPIL_FSM_ACTIVE_MESSAGE', 'Pupil has been updated to allow FSM by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('PUPIL_FSM_DEACTIVE_MESSAGE', 'Pupil has been updated to not allow FSM by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('PUPIL_ADULT_ACTIVE_MESSAGE', 'Pupil has been changed from Child to Adult by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('PUPIL_ADULT_DEACTIVE_MESSAGE', 'Pupil has been changed from Adult to Child by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('MAIN_MENU_DESC_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING . ', Week '. WEEK_NUMBER_REPLACE_STRING .' main menu description has been updated by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('MAIN_MENU_NET_AMT_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING . ', Week '. WEEK_NUMBER_REPLACE_STRING .' main menu price has been updated by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('SNACK_MENU_DESC_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING . ', Week '. WEEK_NUMBER_REPLACE_STRING .' snack menu description has been updated by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('SNACK_MENU_NET_AMT_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING . ', Week '. WEEK_NUMBER_REPLACE_STRING .' snack menu price has been updated by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('MENU_START_DATE_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING .' start date has been changed from '. MENU_PREVIOUS_START_DATE .' to '. MENU_CURRENT_START_DATE);
define('MENU_CYCLE_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING .' menu cycles has been changed from '. MENU_PREVIOUS_CYCLE .' to '. MENU_CURRENT_CYCLE);
define('NAME_DATE_UPDATE_MESSAGE', ' by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('MENU_DATE_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING .' start date has been changed from '. MENU_PREVIOUS_START_DATE .' to '. MENU_CURRENT_START_DATE);
define('SERVERY_PROD_UPDATE_MESSAGE', 'Servery / production relationship changed by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING .'. '. SCHOOL_REPLACE_STRING .' now selects it\'s menus from '. SCHOOL_NAME_PREVIOUS);
define('SERVERY_PROD_CONTRACT_UPDATE_MESSAGE', 'Servery / production relationship changed by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING .'. '. SCHOOL_REPLACE_STRING .' now selects it\'s menus from the master menu on the contract');
define('SCHOOL_ENABLE_UPDATE_MESSAGE', SCHOOL_REPLACE_STRING .' enabled by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('SCHOOL_DISABLE_UPDATE_MESSAGE', SCHOOL_REPLACE_STRING .' disabled by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('SCHOOL_CLOSED_SYSTEM_MESSAGE', SCHOOL_REPLACE_STRING .' closed by '.NAME_REPLACE_STRING.' at '.DATE_REPLACE_STRING.'. Last day of school closure is '.SCHOOL_CLOSED_TILL_REPLACE_STRING.' for reason: '.REASON_REPLACE_STRING);
define('MENU_ITEM_DESELECT_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING .', Week '. WEEK_NUMBER_REPLACE_STRING .' menu item selection changed in '. SCHOOL_REPLACE_STRING .' by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('MENU_ITEM_CONTRACT_DESELECT_UPDATE_MESSAGE', 'Menu '. MENU_NUMBER_REPLACE_STRING .', Week '. WEEK_NUMBER_REPLACE_STRING .' menu item selection changed in '. SCHOOL_REPLACE_STRING .' by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);
define('INVOICE_MEALS_UPDATE_MESSAGE', 'Invoice Meals to school has been changed by '. NAME_REPLACE_STRING .' at '. DATE_REPLACE_STRING);

define('JOB_EMAIL', 10);
define('JOB_BATCH_CANCEL', 46);
define('JOB_NOT_STARTED', 0);
define('JOB_SUCCESS', 1);
define('JOB_FAILED', 2);
define('JOB_INVALID', 3);
define('JOB_QASYNC',12);
define('JOB_BATCH_QASYNC',50);

define('DIGITAL_PEN_DUPLICATE','Digital pen is already exists.');

define('DIGITAL_LUNCH_FORM_TYPE_DEF', 'L');
define('DIGITAL_DINNER_FORM_TYPE_DEF', 'D');
define('DIGITAL_ADHOC_FORM_TYPE_DEF', 'A');
define('DIGITAL_OTHER_FORM_TYPE_DEF', 'O');

define('DIGITAL_FORM_LUNCH_TYPE_ID', 1);	//value is from digital_form_type
define('DIGITAL_FORM_DINNER_TYPE_ID', 2);	//value is from digital_form_type
define('DIGITAL_FORM_ADHOC_TYPE_ID', 3);	//value is from digital_form_type
define('DIGITAL_FORM_OTHER_TYPE_ID', 4);	//value is from digital_form_type

define('DIGITAL_FORM_INCLUDE_EXCEPTION', 0);
define('DIGITAL_FORM_EXCLUDE_EXCEPTION', 1);
define('DIGITAL_FORM_APPROVE_EXCEPTION', 2);

define('DIGITAL_FORM_EXCEL_PAGE_TITLE', 'Meal numbers by menu');
define('DIGITAL_FORM_EXCEL_FILE_NAME', 'Export_Digital_Forms');
define('CUSTOM_ORDER_TITLE', 'Custom Orders (sorted on date received)');
define('ADHOC_DIGITAL_FORM_TITLE', 'Adhoc Orders (sorted on date received)');
$custom_order_file_cols = serialize(array('Ward', 'Day of week', 'Date received', 'Quantity', 'Meal order', 'Completed by'));
define('CUSTOM_ORDER_EXCEL_TITLE_COLS', $custom_order_file_cols);
$adhoc_file_cols = serialize(array('Ward', 'Day of week', 'Date on form', 'Time on form', 'Cost', 'Description'));
define('ADHOC_EXCEL_TITLE_COLS', $adhoc_file_cols);

define('DIGITAL_FORM_TOTAL_DAILY_MEAL', 'Total Daily Meal Numbers ');
define('DIGITAL_FORM_TDM_TITLE', 'Total Daily Meal Numbers');
define('DIGITAL_FORM_TDM_REPORTING_TIME_TITLE', 'Report time');
define('DIGITAL_FORM_TDM_EXCEL_FILE_NAME', 'Export_TDM_reports');
define('DIGITAL_FORM_TDM_CUSTOM_EXCEL_FILE_NAME', 'Export_Custom_reports');
define('DIGITAL_FORM_TDM_CUSTOM_COUNT_TITLE', 'Customer Orders Available in Report');
define('DIGITAL_FORM_TDM_TOTAL_TITLE', 'Total');
define('DIGITAL_FORM_TDM_INDICATOR', 'Menu Indicator');
define('DIGITAL_FORM_TDM_NO_INDICATOR_DESC', '[No Indicators]');

define('DAILY_MEAL_ORDER_LUNCH', 'Lunch Orders');
define('DAILY_MEAL_ORDER_DINNER', 'Dinner Orders');
define('DAILY_MEAL_ORDER_ADHOC', 'AdHoc Orders');
define('DMO_EXCEL_PAGE_TITLE', 'Daily meal orders');
define('DMO_REPORTING_TIME_TITLE', 'Report time');
define('DMO_TOTAL_TITLE', 'Total');
define('DMO_EXCEL_FILE_NAME', 'Export_DMO_reports');
define('DMO_CUSTOM_EXCEL_FILE_NAME', 'Export_DMO_custom_reports');

define('QA_ACCOUNT_DUPLICATE','Account name is already exists.');
define('QA_ACCOUNT_CODE_DUPLICATE','Account code is already exists.');

define('QA_GROUP_DUPLICATE','Group name is already exists.');
define('QA_GROUP_RAG_STATUS_WRONG','RAG status values should covered from 0% to 100%.');

define('QA_FILTER_PAGE_TITLE', 'Auditdetails');
define('QA_EXCEL_FILE_NAME', 'Export_Quality_Audit');
$qa_filter_cols = serialize(array('Site', 'Audit Date', 'Month', 'Auditor', 'Time in', 'Time out', 'Area', 'Sub Area', 'Point', 'Point Score', 'Point Value', 'Point Weight', 'Point Color', 'Comment'));
define('QA_FILTER_EXCEL_TITLE_CLS', $qa_filter_cols);

define('MANAGE_PUPILS_SEARCH_PAGINATION_COUNT', 10);

/* End of file constants.php */
/* Location: ./application/config/constants.php */

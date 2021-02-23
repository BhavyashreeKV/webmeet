<?php 
/* Create your development configuration in this file
* Eg: $config['your_config'] = 'value_for_it'; 
*/

// $config['company_name'] = 'Kognitiva WebMeeting';
$config['email'] = 'e.p.vasudevan@pointservices.se';
// $config['no_reply_email'] = 'no-reply@pointservices.se';
$config['no_reply_email'] = 'noreply@ktrehab.se';

$config['access'] = array('admin'=>'Admin','employee'=>'Employee');
$config['status'] = array(1=>'Active',0=>'Inactive');


/* custom date format to display */
$config['date_format'] = 'm/d/Y';
$config['date_time_format'] = 'm/d/Y H:i:s';

/* Set the default admin folder path or name */
$config['admin_folder'] = 'admin';
$config['behandlare_folder'] = 'behandlare';
$config['patient_folder'] = 'patient';

/* Meeting status */
$config['booking_status'] = array('new'=>'New','rescheduled'=>'Re-scheduled','cancelled'=>'Cancelled','missed'=>'Missed','completed'=>'Completed');
$config['re_status'] = array('rescheduled'=>'Re-scheduled','cancelled'=>'Cancelled','missed'=>'Missed','completed'=>'Completed');
$config['srch_status'] = array(''=>'Select','new'=>'New','rescheduled'=>'Re-scheduled','missed'=>'Missed','completed'=>'Completed');
$config['srch_status_sw'] = array(''=>'välja','new'=>'Ny','rescheduled'=>'Omplanerad','missed'=>'Missat','completed'=>'Slutfört','cancelled'=>'Inställt');

/* OpenVIDU Server Credentials */
/* OPENVIDU_SERVER_URL */
$config['OPENVIDU_SERVER_URL'] = 'https://webmeeting-test.pointservices.se:4443';
// $config['OPENVIDU_SERVER_URL'] = 'https://webbmote.ktrehab.se:4443';
$config['OPENVIDU_SERVER_SECRET'] = 'WEBPOINT';


/* SMS APi - BASE URL & Credentials */
$config['smsCurl'] = 'https://api.genericmobile.se/SmsGateway/api/v1';
$config['sms_logs'] = 1;


/* Email Logs */
$config['email_logs'] = 1;
/* Bankid Logs */
$config['bankid_logs'] = 1;

/* Treatment specialist Privileges */
$config['doc_privileges'] = array('no_patient'=>'Can\'t Add Own Meetings','own_patient'=>'Can Add Meetings','isolated_patient'=>'Create Isolated Patient with Own Meeting');

/* Bankid Environment */
// $config['bankid_env'] = 'TEST'; /* TEST | PRODUCTION */
$config['bankid_env'] = 'TEST';

$config['BnkAppLaunchURL'] = 'bankid://?autostarttoken=[TOKEN]';



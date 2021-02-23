<?php
/*
Generic Messaging API to send sms via PHP CURL.
Created By : Vasudevan.EP
Company : Pointservices - Sweden AB
Reference Link : https://docs.generic.se/messageapi/

Short Description : This file helps you to send Immediate SMS, Scheduled SMS, Falsh SMS, Valid Period SMS, Delete the scheduled SMS, Statistics of the SMS
Note : Set the following datas to run the code.Set the variables in the config file.
"sms_username" = Username used in authentication of the api
"sms_secret" = Secret Key used in authentication of the api
"smsCurl" = Enter the base url of the API.
-------------------------------------------------------------------------------------------------------------------------------------
 Send Immediate Message  
----------------------------------------------------------------------------------------------------------------------------------------
 This will send the Immediate sms to the users mobile no
 Params Expalnations
    1, $mobile no  = can be single mobile no or array of Mobile numbers.Note : Use the mobile no with the country code in front of the number.
    2, $text_message = Send the relevant text message.
    3, $flash = A flash message is delivered and shown directly on the mobile device display. The message will be shown until the receiver 
                discards it and the message will not be stored by default. Enable this feature by setting Flash to true.
    4, $validPeriodMins = Text messages can't be delivered to mobile phones that are turned off or by other means unreachable.
                However messages are stored and new delivery attempts are made for a certain time, default is 
                4320 minutes (3 days). After this period the message is dropped.The validity period starts when the 
                message is sent.
   Params Required :
    Mobile No, Text to send.             
 ---------------------------------------------------------------------------------------------------------------------------------------*/
function send_sms($mobile_no = array(),$text_message,$flash=false,$validPeriodMins=false)
{

    $auth_token = utf8_encode(base64_encode( config_item('sms_username').':'.config_item('sms_secret')));
    $C_url = config_item('smsCurl').'/message';
    header('Content-type: text/html; charset=UTF-8');
    $post_data['From'] = 'kognitiva';
    $post_data['To'] = is_array($mobile_no)?$mobile_no:array($mobile_no);
    $post_data['Text'] = $text_message;
    if($flash)
    $post_data['Flash'] = true; //to set the message as a flash message
    if($validPeriodMins)
    $post_data['ValidityPeriodMinutes'] = $validPeriodMins; //set time if msg is not send to the user(switch off or not reachable) then it will resend once again in mins.
    $post = json_encode($post_data); // Encode the data array into a JSON string
    
    $ch = curl_init($C_url); // Initialise cURL
    $authorization = "Authorization: Basic ".$auth_token; // Prepare the authorisation token
    $header = array('Content-Type: application/json' , $authorization );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $header); // Inject the token into the header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
    $result = curl_exec($ch); // Execute the cURL statement
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //Get the Status code for the CURL run
    curl_close($ch); // Close the cURL connection

    $return['status'] = $httpcode;
    $return['result'] = json_decode($result);

    if(config_item('sms_logs'))
    {
        $save=[];
        $save['to'] = $mobile_no;
        $save['content'] = $text_message;
        $save['send'] = $httpcode == 201 ? 1 : 0;
        $save['send_time'] = date('Y-m-d H:i:s');
        $save['error_log'] = json_encode($return['result']);
        // print_a($save,true);
        $CI =& get_instance();
        $CI->Common_model->save_tbl('sms_notification_logs',$save);
    }
    return $return;
}

/* ------------------------------------------------------------------------------------------------------------------------------------------
    Send Scheduled SMS to User
    -----------------------------------------------------------------------------------------------------------------------------------------
    Params Explaination
    1, $mobile no  = can be single mobile no or array of Mobile numbers.Note : Use the mobile no with the country code in front of the number.
    2, $text_message = Send the relevant text message.
    3, $scheduledatetime = By specifying SendAt it's possible to schedule messages to be sent at a specified date and time.
            Use the time format ex. 2019-12-21 13:00:00
    
    Note: In response you will get Batch id which can be used to remove the batch data.
    Params Required :
    Mobile No, Text to send, Scheduled time to send sms.
   -------------------------------------------------------------------------------------------------------------------------------------------- */
function send_scheduled_sms($mobile_no = array(),$text_message,$scheduledatetime)
{
    $auth_token = base64_encode( config_item('sms_username').':'.config_item('sms_secret'));
    $C_url = config_item('smsCurl').'/message';
    
    $post_data['From'] = 'kognitiva';
    $post_data['To'] = is_array($mobile_no)?$mobile_no:array($mobile_no);
    $post_data['Text'] = $text_message;
    $post_data['SendAt'] = date("c",strtotime($scheduledatetime)); //time format ex. 2019-12-21 13:00:00
    $post = json_encode($post_data); // Encode the data array into a JSON string

    $ch = curl_init($C_url); // Initialise cURL
    $authorization = "Authorization:Basic ".$auth_token; // Prepare the authorisation token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1); // Specify the request method as POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post); // Set the posted fields
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
    $result = curl_exec($ch); // Execute the cURL statement
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //Get the Status code for the CURL run
    curl_close($ch); // Close the cURL connection

    $return['status'] = $httpcode;
    $return['result'] = json_decode($result);

    if(config_item('sms_logs'))
    {
        $save['from'] = $post_data['From'];
        $save['to'] = $post_data['To'];
        $save['content'] = $post_data['Text'];
        $save['send'] = $httpcode == 201 ? 1 : 0;
        $save['send_time'] = $scheduledatetime;
        $save['error_log'] = json_encode($return['result']);
        $CI =& get_instance();
        $CI->Common_model->save_tbl('sms_notification_logs',$save);
    }

    return $return;
}


/*-----------------------------------------------------------------------------------------------------------------------------------------------
 Delete the Shceduled SMS
 -------------------------------------------------------------------------------------------------------------------------------------------------
 Used to delete the batch sms or scheduled sms. Send Batch id to delete if already scheduled.

 Params Required : 
 Batch id.
 ---------------------------------------------------------------------------------------------------------------------------------------------- */
function delete_scheduled_sms($batch_id)
{
    $auth_token = base64_encode( config_item('sms_username').':'.config_item('sms_secret'));
    $C_url = config_item('smsCurl').'/message/'.$batch_id;

    // header('Content-Type: application/json'); // Specify the type of data
    $ch = curl_init($C_url); // Initialise cURL
    $authorization = "Authorization:Basic ".$auth_token; // Prepare the authorisation token
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE"); // Specify the request method as DELETE
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
    $result = curl_exec($ch); // Execute the cURL statement
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //Get the Status code for the CURL run
    curl_close($ch); // Close the cURL connection

    $return['status'] = $httpcode;
    $return['result'] = json_decode($result);
    return $return;
}

/*----------------------------------------------------------------------------------------------------------------------------------------------------
    Get Statistics of SMS
    -------------------------------------------------------------------------------------------------------------------------------------------------
    An admin user may obtain statistics of SMS sent during a given time period.
    
    Params required:
    Start date, End date.
    -------------------------------------------------------------------------------------------------------------------------------------------------*/

function get_statistics($from_date,$to_date)
{

        $auth_token = base64_encode( config_item('sms_username').':'.config_item('sms_secret'));
        $C_url = config_item('smsCurl').'/statistics'.'/'.config_item('sms_username').'/sent?start='.date("c",strtotime($from_date)).'&end='.date('c',strtotime($to_date));

        // header('Content-Type: application/json'); // Specify the type of data
        $ch = curl_init($C_url); // Initialise cURL
        $authorization = "Authorization:Basic ".$auth_token; // Prepare the authorisation token
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $authorization )); // Inject the token into the header
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); // This will follow any redirects
        $result = curl_exec($ch); // Execute the cURL statement
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //Get the Status code for the CURL run
        curl_close($ch); // Close the cURL connection
        $return['status'] = $httpcode;
        $return['result'] = json_decode($result);
        return $return;
}
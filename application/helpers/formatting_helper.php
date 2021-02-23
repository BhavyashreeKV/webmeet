<?php 
function get_email_config()
{
    $CI = &get_instance();
    $config =$mail=array();
    $email_settings = $CI->db->select('settings_key,setting')->where('code','email')->where('status',1)->get('settings')->result();
    foreach($email_settings as $es){
        $mail[$es->settings_key] = $es->setting;
    }
    if(isset($mail['email_protocol']) && $mail['email_protocol']=='smtp') {
        $config['protocol'] = $mail['email_protocol'];
        $config['smtp_host'] = $mail['email_host'];
        $config['smtp_port'] = $mail['email_port'];
        $config['smtp_user'] = $mail['email_username'];
        $config['smtp_pass'] = $mail['email_password'];
    }
    if(isset($mail['smtp_crypto']))
    {
        $config['smtp_crypto'] = 'tls';
    }
    $config['smtp_timeout'] = '7';
    $config['charset'] = 'UTF-8';
    $config['wordwrap'] = FALSE;
    $config['newline'] = "\r\n";
    $config['mailtype'] = 'HTML'; // or text
    $config['validate'] = TRUE;
// print_a($mail,true);
    return $config;
}

function print_a($data,$exit=false)
{
    echo '<pre>'; print_r($data); echo '</pre>';
    if($exit) exit;
}

function print_last_query()
{
    $CI = &get_instance();
    echo $CI->db->last_query(); exit;
}

function save_data($tbl,$id,$fileds = array(),$custom=array())  
{
    $CI = &get_instance();
    $save = array();

    $save['id'] = $id;
    
    if(!empty($custom))
    {
        foreach($custom as $key=>$value)
        {
            $save[$key] = $value;
        }
    }
    foreach($fileds as $filed)
    {
       
        $save[$filed] = ($CI->input->post($filed)!='')?$CI->input->post($filed):'';
         
    }

    return $CI->Common_model->save_tbl($tbl,$save);
}

function set_validation_rules($array = array())
{
    $CI = &get_instance();
    $CI->load->library('form_validation');
    if(!empty($array))
    {
        foreach($array as $arr)
        {
            $CI->form_validation->set_rules($arr['name'],$arr['label'],$arr['rules']);  
        }
    }
    if($CI->form_validation->run() == false)
    {
        return false;
    }
    else{
        return true;
    }
}

function get_user_porfileimage($user_id)
{
    $CI = &get_instance();
    $user = $CI->db->where('id',$user_id)->get('users')->row();
    if($user->profile_img != NULL)
    {
        return upload_url('profile/',$user->profile_img);
    }
    else{
        return $CI->config->base_url('uploads/avatar.jpg');
    }
}

function OV_RestApi_Pst($url='/api/sessions',$post=array())
{
        $auth_token = base64_encode( 'OPENVIDUAPP:'.config_item('OPENVIDU_SERVER_SECRET'));
        $C_url = config_item('OPENVIDU_SERVER_URL').$url;
        $post = json_encode($post); // Encode the data array into a JSON string

        header('Content-Type: application/json'); // Specify the type of data
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
        return $return;
}

function OV_RestApi_Get($url='/api/sessions',$post=array())
{
        $auth_token = base64_encode( 'OPENVIDUAPP:'.config_item('OPENVIDU_SERVER_SECRET'));
        $C_url = config_item('OPENVIDU_SERVER_URL').$url;
        $post = json_encode($post); // Encode the data array into a JSON string

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
function OV_RestApi_Del($url='/api/sessions')
{
        $auth_token = base64_encode( 'OPENVIDUAPP:'.config_item('OPENVIDU_SERVER_SECRET'));
        $C_url = config_item('OPENVIDU_SERVER_URL').$url;

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

function getStartAndEndDate($week, $year) {
    $dateTime = new DateTime();
    $dateTime->setISODate($year, $week);
    $result['start_date'] = $dateTime->format('Y-m-d');
    $dateTime->modify('+6 days');
    $result['end_date'] = $dateTime->format('Y-m-d');
    return $result;
  }

  function checkswedenphone($phone_no)
  {
    if(!empty($phone_no))
    {
      $mobile_number = $phone_no;
      
      switch (true) {
          case (preg_match('#^7\d{8}$#', $mobile_number)):
              $mobile_number = '+46' . $mobile_number;
              break;
          case (preg_match('#^07\d{8}$#', $mobile_number)):
              $mobile_number = '+46' . substr($mobile_number, 1);
              break;
          case (preg_match('#^467\d{8}$#', $mobile_number)):
              $mobile_number = '+' . $mobile_number;
              break;
          case (preg_match('#^00467\d{8}$#', $mobile_number)):
              $mobile_number = '+' . substr($mobile_number, 2);
              break;
          case (preg_match('#^\+467\d{8}$#', $mobile_number)):
              break;
      }
        return $mobile_number;
      
    }
  }

  function log_bankid($personal_id,$meeting_id=NULL,$atoken=NULL,$request=NULL,$response=NULL,$status=NULL,$error_log=NULL)
  {
      if(config_item('bankid_logs'))
      {
          $save['personal_id'] = $personal_id;
          $save['meeting_id'] = $meeting_id;
          $save['autostarttoken'] = $atoken;
          $save['request'] = $request;
          $save['response'] = $response;
          $save['status'] = $status;
          $save['error_log'] = $error_log;
          save_data('bankauth_log',false,[],$save);
      }
        

  }

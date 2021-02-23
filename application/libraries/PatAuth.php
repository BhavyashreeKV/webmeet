<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class PatAuth
{
    var $CI;

    function __construct()
    {
        $this->CI =& get_instance();
        // $this->CI->load->database();
        $this->CI->load->helper('url');
    }
    
    
    /*
    this checks to see if the admin is logged in
    we can provide a link to redirect to, and for the login page, we have $default_redirect,
    this way we can check if they are already logged in, but we won't get stuck in an infinite loop if it returns false.
    */
    function is_logged_in($redirect = false, $default_redirect = true)
    {
        
        //var_dump($this->CI->session->userdata('session_id'));

        //$redirect allows us to choose where a customer will get redirected to after they login
        //$default_redirect points is to the login page, if you do not want this, you can set it to false and then redirect wherever you wish.

        $admin = $this->CI->session->userdata('patient');
        
        if (!$admin)
        {
            //check the cookie
            if(isset($_COOKIE['KogwebmeetingPat']))
            {
                //the cookie is there, lets log the customer back in.
                $info = $this->aes256Decrypt(base64_decode($_COOKIE['KogwebmeetingPat']));
                $cred = json_decode($info, true);

                if(is_array($cred))
                {
                    if( $this->login($cred['personal_id'], $cred['password']) )
                    {
                        return $this->is_logged_in($redirect, $default_redirect);
                    }
                }
            }

            if ($redirect)
            {
                $this->CI->session->set_flashdata('redirect', $redirect);
            }
                
            if ($default_redirect)
            {
                redirect('/login');
            }
            
            return false;
        }
        else
        {
           return true;
        }
    }
    /*
    this function does the logging in.
    */
    function login($personal_id, $password, $remember=false,$login_type=false)
    {
        // make sure the username doesn't go into the query as false or 0
        if(!$personal_id)
        {
            return false;
        }

        $this->CI->db->select('*');
        $this->CI->db->where('personal_id', $personal_id);
        $this->CI->db->where('password',  sha1($password));
        $this->CI->db->where('type',2);
        $this->CI->db->where('status',1);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('users');
        $result = $result->row_array();
        
        if (sizeof($result) > 0)
        {
            $user = array();
            $user['patient'] = array();
            $user['patient']['id'] = $result['id'];
            $user['patient']['name'] = $result['firstname'].' '.$result['lastname'];
            $user['patient']['email'] = $result['email'];
            $user['patient']['personal_id'] = $result['personal_id'];
            $user['patient']['phone'] = $result['phone'];
            // $user['patient']['privilege'] = $result['privilege'];
            $user['patient']['profile_pic'] = $result['profile_img'];

            if(config_item('enable_2-way_authentication'))
            {
                
                $otp = rand(111111,999999);
                if(config_item('send_sms'))
                {
                    if(!empty($result['phone']))
                    {
                        $this->CI->load->helper('sms');
                        $sms_template = $this->CI->Common_model->get_tbl_row('sms_templates',6);
                        $message = str_replace('{fullname}',$result['firstname'].' '.$result['lastname'],$sms_template->message);
                        $message = str_replace('{otp_number}',$otp,$message);
                        $reponse = send_sms('+'.$result['phone'],$message);

                        
                    }    
                }

                if(config_item('send_email_notification'))
                {
                    $row = $this->CI->Common_model->get_tbl_row('email_templates',array('id'=>7));

                    /* Re-place the Subject content */
                    $subject = $row->subject;

                    $message = str_replace('{fullname}',$result['firstname'].' '.$result['lastname'],$row->message);
                    $message = str_replace('{otp_number}',$otp,$message);
                    $message = str_replace('{company_name}',config_item('company_name'),$message);
                    
                    $to = $result['email'];
                    // print_r($to);exit;
                    /* Send email now */
                    $this->CI->load->library('emailnotification');
                    $this->CI->emailnotification->debug_email($row->from_email,$to,$subject,$message,config_item('company_name'));
                    
                }
                $this->CI->db->where('id',$result['id'])->update('users',array('otp'=>$otp,'resend_otp_count'=>1));
                $this->CI->session->set_userdata('2way_auth',$result['personal_id']);
                $this->CI->session->set_flashdata('message','Please check you registered mobile or email id to get your otp');
                redirect('login/check_otp');
            }

            if($remember)
            {
                $loginCred = json_encode(array('personal_id'=>$personal_id, 'password'=>$password));
                $loginCred = base64_encode($this->aes256Encrypt($loginCred));
                //remember the user for 6 months
                $this->generateCookie($loginCred, strtotime('+6 months'));
            }

            $this->CI->session->set_userdata($user);
            return true;
        }
        else
        {
            return false;
        }
    }
    
    private function generateCookie($data, $expire)
    {
        setcookie('KogwebmeetingPat', $data, $expire, '/', $_SERVER['HTTP_HOST']);
    }

    /* private function aes256Encrypt($data)
    {
        $key = config_item('encryption_key');
        if(32 !== strlen($key))
        {
            $key = hash('SHA256', $key, true);
        }
        $padding = 16 - (strlen($data) % 16);
        $data .= str_repeat(chr($padding), $padding);
        return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
    }

    private function aes256Decrypt($data) {
        $key = config_item('encryption_key');
        if(32 !== strlen($key))
        {
            $key = hash('SHA256', $key, true);
        }
        $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, str_repeat("\0", 16));
        $padding = ord($data[strlen($data) - 1]); 
        return substr($data, 0, -$padding); 
    } */

    private function aes256Encrypt($data)
    {
        $key = config_item('encryption_key');
        $method='AES-256-CBC';
        if(32 !== strlen($key))
        {
            $key = hash('SHA256', $key, true);
        }
        $ivSize = openssl_cipher_iv_length($method);
        $iv = openssl_random_pseudo_bytes($ivSize);
        $encrypted = openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv);
        $encrypted = base64_encode($iv . $encrypted);
        return $encrypted;
    }

    private function aes256Decrypt($data) {
        $key = config_item('encryption_key');
        $method='AES-256-CBC';
        if(32 !== strlen($key))
        {
            $key = hash('SHA256', $key, true);
        }
        $data = base64_decode($data);
        $ivSize = openssl_cipher_iv_length($method);
        $iv = substr($data, 0, $ivSize);
        $data = openssl_decrypt(substr($data, $ivSize), $method, $key, OPENSSL_RAW_DATA, $iv);
        return $data;
    }

    /*
    this function does the logging out
    */
    function logout()
    {
        $this->CI->session->unset_userdata('patient');
        //force expire the cookie
        $this->generateCookie('[]', time()-3600);
    }

    /*
    This function sends an reset password link to the users email address.
    */
    function reset_password($email)
    {
        $admin = $this->get_user_by_email($email);
        if ($admin)
        {
            $this->CI->load->helper('string');
            $this->CI->load->library('email');
            
            $auth_key       = random_string('alnum', 8);
            $admin1['id']  = $admin['id'];
            $admin1['auth_key']  = $auth_key;
            $this->save($admin1);

            $config= get_email_config();
            $config['mailtype'] = 'text';
            $this->CI->email->initialize($config);

            $this->CI->email->from(config_item('no_reply_email'), config_item('company_name'));
            $this->CI->email->to($admin['email']);

            $this->CI->email->subject(config_item('company_name').': Patient Password Reset');
            $message =  'Dear '.$admin['firstname'].'<br>';
            $post_link = site_url('reset_password_patient?authkey='.$auth_key);
            $link = '<a href="'.$post_link.'">Click here</a>';
            $mailmessage = str_replace('{clickhere}',$link,lang('reset_message'));
            $mailmessage = str_replace('{link}',$post_link,$mailmessage);
            $message .= $mailmessage;
            // print_r($admin['email']);exit;
            $this->CI->email->message($message);
            $this->CI->email->send();
            return true;
        }
        else
        {
            return false;
        }
    }
    
    /*
    This function gets the user by their email address and returns the values in an array
    it is not intended to be called outside this class
    */
    private function get_user_by_email($email)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('email', $email);
        $this->CI->db->where('type',2);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('users');
        $result = $result->row_array();

        if (sizeof($result) > 0)
        {
            return $result; 
        }
        else
        {
            return false;
        }
    }
    
    /*
    This function takes admin array and inserts/updates it to the database
    */
    function save($admin)
    {
        if ($admin['id'])
        {
            $this->CI->db->where('id', $admin['id']);
            $this->CI->db->update('users', $admin);
        }
        else
        {
            $this->CI->db->insert('users', $admin);
        }
    }
    
    
      
    
    function check_id($str)
    {
        $this->CI->db->select('id');
        $this->CI->db->from('users');
        $this->CI->db->where('id', $str);
        $count = $this->CI->db->count_all_results();
        
        if ($count > 0)
        {
            return true;
        }
        else
        {
            return false;
        }   
    }
    
    function update_profile($data)
    {
        $sess_data = $this->CI->session->userdata('patient');
        if(isset($data['fullname']))
        $sess_data['name'] = $data['firstname'].' '.$data['lastname'];
        if(isset($data['email']))
        $sess_data['email'] = $data['email'];
        if(isset($data['profile_pic']))
        $sess_data['profile_pic'] = $data['profile_pic'];
        if(isset($data['phone']))
        $sess_data['phone'] = $data['phone'];
        if(isset($data['personal_id']))
        $sess_data['personal_id'] = $data['personal_id'];
        
        
        
        $this->CI->session->set_userdata('patient',$sess_data);
    }

    function set_sessionData($personal_id)
    {
        $this->CI->db->select('*');
        $this->CI->db->where('personal_id', $personal_id);
        $this->CI->db->where('type',2);
        $this->CI->db->where('status',1);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('users');
        $result = $result->row_array();
        
        if (sizeof($result) > 0)
        {
            $user = array();
            $user['patient'] = array();
            $user['patient']['id'] = $result['id'];
            $user['patient']['name'] = $result['firstname'].' '.$result['lastname'];
            $user['patient']['email'] = $result['email'];
            $user['patient']['personal_id'] = $result['personal_id'];
            $user['patient']['phone'] = $result['phone'];
            $user['patient']['profile_pic'] = $result['profile_img'];

            $this->CI->session->set_userdata($user);
            return true;
        }
        else
        {
            return false;
        }
    }
    

}
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

use Dimafe6\BankID\Model\CollectResponse;

require (__DIR__.'/BankIdServices.php');


class BankidAuth 
{
    var $CI;
    var $bankIDService;
    function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->database();
        $this->CI->load->helper('url');

        if(config_item('bankid_env') == 'TEST')
        {
            $BANKIDURL = 'https://appapi2.test.bankid.com/rp/v5.1/';
            $cert_url = realpath(__DIR__ . '/../../uploads/FPTestcert3.pem');
           
        }
        else
        {
            $BANKIDURL = 'https://appapi2.bankid.com/rp/v5.1/';
            $cert_url = realpath(__DIR__ . '/../../uploads/key/bnkserv2.pem');
        }
        
        $this->bankIDService = new BankidServices(
            $BANKIDURL,
            $_SERVER["REMOTE_ADDR"],
            [
                'verify' => false,
                'cert'   => $cert_url,
            ]
        );
    }

    /**
     * @return signResponse
     * 
     * return the orderRef and the tokenaccess
     */
    public function signResponse($personal_id=null)
    {
        /* Run the auth service to get the response */
        $signResponse = $this->bankIDService->getAuthResponse($personal_id);

        if(isset($signResponse->responseCode) && $signResponse->responseCode == 200)
        $this->StoreSignResponse($signResponse,$personal_id);

         return $signResponse;

    }


    function StoreSignResponse($response,$personal_id=null)
    {
        $response = $response;
        $response->personal_id = $personal_id;

        $this->CI->session->set_userdata('bankID_auth',$response);
        // print_a($this->CI->session->userdata('bankID_auth'),true);
        return $response;
    }

    function LaunchApp($token='',$returnRedirectURL='')
    {
              
        $C_url = str_replace('[TOKEN]',$token,config_item('BnkAppLaunchURL'));

        // header('Content-Type: application/json'); // Specify the type of data
        $ch = curl_init($C_url); // Initialise cURL
        $result = curl_exec($ch); // Execute the cURL statement
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE); //Get the Status code for the CURL run
        curl_close($ch); // Close the cURL connection
        $return['status'] = $httpcode;
        $return['result'] = json_decode($result);
        return $C_url;
    }

    function CollectSignResponse($signResponse)
    {
        $attempts = 0;
        do {
          
            // logConsole('CollectData',$signResponse);
            // fwrite(STDOUT, "\nWaiting confirmation from BankID application...\n");
            sleep(5);

            $collectResponse = $this->bankIDService->collectResponse($signResponse->orderRef);
            $attempts++;
            // print_a($collectResponse);
            if($collectResponse->status == CollectResponse::STATUS_FAILED)
            {
                return $collectResponse;
            }
        } while ($collectResponse->status !== CollectResponse::STATUS_COMPLETED && $attempts <= 8);

        return $collectResponse;
    }

    function Login($signResponse)
    {
        if(!$signResponse)
        {
            return false;
        }

        $user = $signResponse->completionData->user;
        $this->CI->db->select('*');
        $this->CI->db->where('personal_id', $user->personalNumber);
        $this->CI->db->limit(1);
        $result = $this->CI->db->get('users');
       
        $result = $result->row_array();
        //  print_a($this->CI->db->last_query());       
        if (!empty($result))
        {
            if($result['type'] == 1)
            {
                $type = 'behandlare';
            }
            else
            {
                $type = 'patient';
            }

            $login_user[$type] = array();
            $login_user[$type]['id'] = $result['id'];
            $login_user[$type]['name'] = $result['firstname'].' '.$result['lastname'];
            $login_user[$type]['email'] = $result['email'];
            $login_user[$type]['personal_id'] = $result['personal_id'];
            $login_user[$type]['phone'] = $result['phone'];
            if($result['type'] == 2)
            $login_user[$type]['privilege'] = $result['privilege'];
            $login_user[$type]['profile_pic'] = $result['profile_img'];

            /* set last logged in time of the user */
            $save['id'] = $result['id'];
            $save['last_logged_in'] = date('Y-m-d H:i:s');
            $this->CI->db->where('id',$save['id'])->update('users',$save);

            $this->CI->session->set_userdata($login_user);
            /* Remove the Old Session  */
            $this->CI->session->unset_userdata('bankID_auth');
            return true;
        }
        else
        {
            return false;
        }
    }

    function logout()
    {
        $this->CI->session->unset_userdata('user');
        //force expire the cookie
       
    }

    function bankidTempLogin($signResponse)
    {
        if(!$signResponse)
        {
            return false;
        }

        $user = $signResponse->completionData->user;
        if(isset($signResponse->completionData))
        {
            $login_user['patient'] = array();
            $login_user['patient']['name'] = $user->name;
            $login_user['patient']['personal_number'] = $user->personalNumber;
            $login_user['patient']['ip'] = $signResponse->completionData->device->ipAddress;
            /* Set Temp Login */
            $this->CI->session->set_userdata($login_user);
            // $this->generateCookie('temp_login',base64_encode(json_encode($login_user)));
            
            // print_a($this->CI->session->userdata('temp_user'));
            /* Remove the Old Session  */
            // $this->CI->session->unset_userdata('bankID_auth');
            return true;
        }
        else
        {
            return false;
        }
    }

    function generateCookie($name,$data)
    {
        setcookie($name,$data,strtotime('+1 hour'),$_SERVER['HTTP_HOST']);
    }
    function bankidTempLogout()
    {
        // $this->CI->session->unset_userdata('temp_user');
       return true;
    }
}
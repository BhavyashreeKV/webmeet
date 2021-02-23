<?php 
class Login extends BaseController
{
    function __construct()
	{
		parent::__construct();
		$lang = $this->check_lang();
		// print_r($lang);exit;
		$this->lang->load(config_item('patient_folder').'/login',$lang);
		$this->load->helper('personalnummer');
		define('PATIENT_FOLDER',config_item('patient_folder'));
    }

    function index()
    { 
        //we check if they are logged in, generally this would be done in the constructor, but we want to allow customers to log out still
		//or still be able to either retrieve their password or anything else this controller may be extended to do
		$redirect	= $this->patauth->is_logged_in(false, false);
		//if they are logged in, we send them back to the dashboard by default, if they are not logging in
		if ($redirect)
		{
			redirect();
        }
        
		$this->load->helper('form');
		$data['page_title'] = lang('login');
        $data['redirect']	= $this->session->flashdata('redirect');
		
		$set_rules[]=array('name'=>'personal_id','label'=>lang('personal_id'),'rules'=>'trim|required');
		$set_rules[]=array('name'=>'password','label'=>lang('password'),'rules'=>'trim|required');
		$set_rules[]=array('name'=>'remember','label'=>lang('remember'),'rules'=>'trim');

		if(!set_validation_rules($set_rules))
        {
            $this->load->view(PATIENT_FOLDER.'/auth/auth_login', $data);
        }
        else
        {
			$login = false;
			$submit = $this->input->post('submit');
			if($submit)
			{
				$this->load->library('user_agent');
				if ($this->agent->is_browser() || $this->agent->is_mobile())
				{
					$personal_id	= $this->input->post('personal_id');
					$password		= $this->input->post('password');
					$remember   	= $this->input->post('remember');
					$redirect		= $this->input->post('redirect');
				
					$login		= $this->patauth->login($personal_id, $password, $remember);
				}
			}
			if ($login)
			{
				if ($redirect == '')
				{
					$redirect = 'meetings';
				}
				redirect($redirect);
			}
			else
			{
				//this adds the redirect back to flash data if they provide an incorrect credentials
				$this->session->set_flashdata('redirect', $redirect);
				$this->session->set_flashdata('error', lang('error_authentication_failed'));
				redirect('login');
			}
		}
    
    }

    function logout()
	{

		$this->patauth->logout();
		
		//when someone logs out, automatically redirect them to the login page.
		$this->session->set_flashdata('message', lang('message_logged_out'));
		redirect('login');
    } 
    
    function recover_password()
	{
		
		$email = $this->input->post('email');
		if ($email && !empty($email))
		{
			/* If registered user a maill will be triggered to the registered 
			*  username or else error will be thown back
			*/
			$reset_pass = $this->patauth->reset_password($email); 
			if($reset_pass)
			{
				$this->session->set_flashdata('message',lang('reset_password_mail'));
				redirect('login/recover_password');
			}
			else
			{
				$this->session->set_flashdata('error',lang('check_username'));
				redirect('login/recover_password');
				}
        }
		$this->load->view(PATIENT_FOLDER.'/auth/auth_recover_password');
	}

	function reset_password_patient()
    {
        if($this->input->get('authkey')!='')
        {
            $this->db->where('auth_key',$this->input->get('authkey'));
            $user= $this->db->get('users')->row_array();
            
            if(count($user)>0)
            {
				$this->load->library('form_validation');
                $this->form_validation->set_rules('password', lang('password'), 'trim|required|min_length[6]');
                $this->form_validation->set_rules('confirm', lang('confirm_password'), 'required|matches[password]');
                if ($this->form_validation->run() == FALSE)
                {
                    $data['errors'] = $this->form_validation->error_array();
                    $this->load->view(PATIENT_FOLDER.'/auth/auth_update_password',$data);
        
                }
                else { 

					$save['id']=$user['id'];
                    $save['password']=sha1($this->input->post('password'));
					$save['auth_key']='';
					$this->Common_model->save_tbl('users',$save);
					
					$this->session->set_flashdata('message',lang('update_password_relogin'));
                    redirect('login');
                }
            }
            else
            {
                redirect('login');
            }
        }
        else
        {
            redirect('login');
        }
	}
	
	function set_lang()
	{
		$lang = $_GET['l'];
		$this->change_lang($lang);
		redirect($_SERVER['HTTP_REFERER']);
	}

	function check_otp()
	{
		if(!$this->session->userdata('2way_auth'))
		{
			redirect('/login');
		}
		$redirect	= $this->patauth->is_logged_in(false, false);
		//if they are logged in, we send them back to the dashboard by default, if they are not logging in
		if ($redirect)
		{
			redirect($this->config->item('behandlare_folder').'/dashboard');
        }

		$data['page_title'] = '2-Way Authentication';
		if($this->input->post())
		{
			/* verify OTP */
			$otp_number = $this->input->post('otp_number');
			$arr['where'] = array('otp' => $otp_number,'personal_id'=>$this->session->userdata('2way_auth'));
			$return = $this->Common_model->check_data('users',$arr);
			// print_r($this->db->last_query());exit;
			if(!$return)
			{
				$this->session->set_flashdata('error',lang('recheck_otp'));
				redirect('/login/check_otp');
			}
			else
			{
				$this->patauth->set_sessionData($this->session->userdata('2way_auth'));
				$this->db->where('personal_id',$this->session->userdata('2way_auth'))->update('users',array('otp'=>null,'resend_otp_count'=>0,'last_logged_in'=>date('Y-m-d H:i:s')));
				$this->session->unset_userdata('2way_auth');

				redirect('meetings');
			}
		}

		$this->load->view(PATIENT_FOLDER.'/auth/auth_check_otp', $data);
	}

	function resend_otp()
	{
		if(!$this->session->userdata('2way_auth'))
		{
			redirect('/login');
		}

		$result = $this->db->where('personal_id',$this->session->userdata('2way_auth'))->where('resend_otp_count <',3)->get('users')->row_array();
		if($result)
		{
			if(config_item('enable_2-way_authentication'))
            {
                $otp = $result['otp'];
                if(config_item('send_sms'))
                {
                    if(!empty($result['phone']))
                    {
                        $this->load->helper('sms');
                        $sms_template = $this->Common_model->get_tbl_row('sms_templates',6);
                        $message = str_replace('{fullname}',$result['firstname'].' '.$result['lastname'],$sms_template->message);
                        $message = str_replace('{otp_number}',$otp,$message);
						$reponse = send_sms('+'.$result['phone'],$message);
                    }    
                }

                if(config_item('send_email_notification'))
                {
                    $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>7));

                    /* Re-place the Subject content */
                    $subject = $row->subject;

                    $message = str_replace('{fullname}',$result['firstname'].' '.$result['lastname'],$row->message);
                    $message = str_replace('{otp_number}',$otp,$message);
                    $message = str_replace('{company_name}',config_item('company_name'),$message);
                    
                    $to = $result['email'];
                    // print_r($to);exit;
                    /* Send email now */
                    $this->load->library('emailnotification');
                    $this->emailnotification->send_email($row->from_email,$to,$subject,$message,config_item('company_name'));
                    
                }
                $this->db->where('id',$result['id'])->update('users',array('resend_otp_count'=>$result['resend_otp_count']+1));
				
				
				$this->session->set_flashdata('message',sprintf(lang('still_attempt'),(3 - $result['resend_otp_count'])));
            }
		}
		else
		{
			$this->session->set_flashdata('error',lang('all_attempt_over'));	
		}
		redirect('/login/check_otp');

	}
	function gua()
    {
        $this->load->library('user_agent');

        if ($this->agent->is_browser())
        {
                $agent = $this->agent->browser();

        }
        elseif ($this->agent->is_mobile())
        {
                $agent = $this->agent->mobile();
        }
        else{
            $agent = 'Unidentified User Agent';
        }
        if($agent == 'Chrome' || $agent == 'Firefox' || $agent == 'Opera' || $agent == 'Safari')
        {
            echo true;
        }
        else
        {
            echo 'error';
        }
        
	}
	
	function LoginV2()
	{

		$redirect	= $this->patauth->is_logged_in(false, false);
		//if they are logged in, we send them back to the dashboard by default, if they are not logging in
		if ($redirect)
		{
			redirect();
        }
		$data['page_title'] = 'Login BankID';
		$data['redirect']	= $this->session->flashdata('redirect');
		
		$set_rules[]=array('name'=>'personal_id','label'=>lang('personal_id'),'rules'=>'trim|required');
		if(!set_validation_rules($set_rules))
        {
            $this->load->view(PATIENT_FOLDER.'/auth/auth_loginType', $data);
        }
        else
        {
			// print_a($this->session->userdata('bankID_auth'),true);
			$personal_id	= $this->input->post('personal_id');
			$this->load->library('BankidAuth');
			// TODO comment the below code.
			// $this->session->unset_userdata('bankID_auth');
			if($this->session->userdata('bankID_auth'))
			{
				$session = $this->session->userdata('bankID_auth');
				// print_a($session);
				// check sesssion if the personal id is already exist use the same personal id to run collect.
				if($session->personal_id != $personal_id)
				{
					$response = $this->bankidauth->signResponse($personal_id);
				}
				else
				{
					 $this->recollect($session);
				}
				
			}
			else
			{
				$response = $this->bankidauth->signResponse($personal_id);
			}
			
			if(isset($response->autoStartToken))
			{
				$resp_app = $this->bankidauth->LaunchApp($response->autoStartToken,site_url('login/recollect'));
				// print_a($resp_app,true);
				$this->recollect($response);
			}

			

		}
		
	}

	function recollect($response=null)
	{
		$this->load->library('BankidAuth');
		if(is_null($response))
		{
			$response = $this->session->userdata('bankID_auth');
			// echo 123;exit;
		}
		// print_a($response,true);
		$collection_data = $this->bankidauth->CollectSignResponse($response);
		// print_a($collection_data,true);
		if(!is_null($collection_data))
		{
			if($collection_data->responseCode == 200)
			{

				if(isset($collection_data->status) && $collection_data->status == 'complete'  && $this->bankidauth->Login($collection_data))
				{
					// print_a($this->session->all_userdata());
					log_bankid($response->personal_id,NULL,$response->orderRef,$response->orderRef,json_encode($collection_data),$collection_data->status,NULL);
					$this->session->unset_userdata('bankID_auth');
					echo json_encode(array('success_msg_reload'=>'reload'));
				}
				else
				{
					log_bankid($response->personal_id,NULL,$response->orderRef,$response->orderRef,NULL,$collection_data->status,json_encode($collection_data));
					echo json_encode(array('error'=>lang('login_failed')));
				}
			}
			else
			{
				log_bankid($response->personal_id,NULL,$response->orderRef,$response->orderRef,NULL,$collection_data->status,json_encode($collection_data));
				echo json_encode(array('error'=>lang('login_failed')));
			}
			// print_a($collection_data,true);

		}
			
	}
	
	

	function loginv3()
	{
		$personal_id = $this->input->post('personal_id');
		
		if(!luhnChecksum($personal_id))
		{
			log_bankid($personal_id,NULL,NULL,$personal_id,'Invalid Personal Number','failed','Invalid Personal Number');
			$return['error'] = "Invalid Personal Number";
			echo json_encode($return);die;
		}
			$personal_id	= AddCenturyToPersonalid($personal_id);
			$this->load->library('BankidAuth');
			$this->session->unset_userdata('bankID_auth');
			if($this->session->userdata('bankID_auth'))
			{
				$session = $this->session->userdata('bankID_auth');
				// check sesssion if the personal id is already exist use the same personal id to run collect.
				if($session->personal_id != $personal_id)
				{
					$response = $this->bankidauth->signResponse($personal_id);
					// echo 'if-signinresponse';
				}
				else
				{
					
					 $this->recollect($session);
					// echo 'recollect';
				}
				
			}
			else
			{
				$response = $this->bankidauth->signResponse($personal_id);
				// echo 'else-signin-response';
				
			}

			if(isset($response->responseCode) && $response->responseCode == 400)
			{
				log_bankid($personal_id,NULL,NULL,$personal_id,'Error','failed',$response->message->details);
				$return['error'] = $response->message->details;
				echo json_encode($return);
			}
			if(isset($response->autoStartToken))
			{
				$resp_app = $this->bankidauth->LaunchApp($response->autoStartToken,site_url('login/recollect'));
				$return['launch_app'] = $resp_app;
				log_bankid($personal_id,NULL,$response->autoStartToken,$personal_id,$resp_app,'Login Initiated',NULL);
				echo json_encode($return);
				
			}

			// $this->recollect($response);

	}

	
}
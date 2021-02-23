<?php
 if ( ! defined('BASEPATH')) die('No direct script access allowed');
class Login extends BaseController
{
    function __construct()
	{
		parent::__construct();
		$this->lang->load('login');
    }
    
    function index()
    { 
        //we check if they are logged in, generally this would be done in the constructor, but we want to allow customers to log out still
		//or still be able to either retrieve their password or anything else this controller may be extended to do
		$redirect	= $this->auth->is_logged_in(false, false);
		//if they are logged in, we send them back to the dashboard by default, if they are not logging in
		if ($redirect)
		{
			redirect($this->config->item('admin_folder').'/dashboard');
        }
        
        $this->load->helper('form');
		$data['redirect']	= $this->session->flashdata('redirect');
		$submitted 			= $this->input->post('submitted');
		if ($submitted)
		{
			$username	= $this->input->post('username');
			$password	= $this->input->post('password');
			$remember   = $this->input->post('remember');
			$redirect	= $this->input->post('redirect');
			$login		= $this->auth->login_admin($username, $password, $remember);
			if ($login)
			{
				if ($redirect == '')
				{
					$redirect = config_item('admin_folder').'/dashboard';
				}
				redirect($redirect);
			}
			else
			{
				//this adds the redirect back to flash data if they provide an incorrect credentials
				$this->session->set_flashdata('redirect', $redirect);
				$this->session->set_flashdata('error', lang('error_authentication_failed'));
				redirect(config_item('admin_folder').'/login');
			}
		}
		$this->load->view('login', $data);
    }


    function logout()
	{
		$save['id'] = get_user_detail('id');
		$save['last_logout_date'] = date('Y-m-d H:i:s');
		$this->db->where('id',$save['id'])->update('admin',$save);

		$this->auth->logout();
		
		//when someone logs out, automatically redirect them to the login page.
		$this->session->set_flashdata('message', lang('message_logged_out'));
		redirect(config_item('admin_folder').'/login');
	} 

	function recover_password()
	{

		$data['page_title'] = 'Recover Password';

		$username = $this->input->post('username');
		if ($username && !empty($username))
		{
			/* If registered user a maill will be triggered to the registered 
			*  username or else error will be thown back
			*/
			$reset_pass = $this->auth->reset_password($username); 
			if($reset_pass)
			{
				$this->session->set_flashdata('message',lang('reset_password_mail'));
				redirect(config_item('admin_folder').'/login/recover_password');
			}
			else
			{
				$this->session->set_flashdata('error',lang('check_username'));
				redirect(config_item('admin_folder').'/login/recover_password');
			}

		}
		
		$this->load->view('recover_password');
	}

	function reset_password()
    {
        if($this->input->get('authkey')!='')
        {
            $this->db->where('auth_key',$this->input->get('authkey'));
            $user= $this->db->get('admin')->row_array();
            
            if(count($user)>0)
            {
				$this->load->library('form_validation');
                $this->form_validation->set_rules('password', lang('password'), 'trim|required|min_length[6]');
                $this->form_validation->set_rules('confirm', lang('confirm_password'), 'required|matches[password]');
                if ($this->form_validation->run() == FALSE)
                {
                    $data['errors'] = $this->form_validation->error_array();
                    // print_a($data['errors']);
                    $this->load->view('new_password',$data);
        
                }
                else { 
					// print_a($user,true);
                    $save['id']=$user['id'];
                    $save['password']=sha1($this->input->post('password'));
					$save['auth_key']='';
					// print_a($save,true);
					$this->Common_model->save_tbl('admin',$save);
					
					$this->session->set_flashdata('message',lang('update_password_relogin'));
                    redirect(site_url(config_item('admin_folder').'/login'));
                }
                
                
            }
            else
            {
                redirect(config_item('admin_folder').'/login');
            }
        }
        else
        {
            redirect(config_item('admin_folder').'/login');
        }
	}
	
	function emial_auth()
	{
		$user = $this->session->userdata('auth');

		if(!$user)
		{
			redirect(config_item('admin_folder').'/login');
		}

		if($this->input->post('submitted'))
		{
			$email = $this->input->post('email');

			if($user['email'] == $email)
			{
				$auth_key       = rand(100000,999999);

				$save['id'] = $user['id'];
				$save['auth_key'] = $auth_key;
				$this->auth->save($save);

				/* Send an OTP email to registered address */
				$this->load->helper('string');
				$this->load->library('email');
				$config= get_email_config();
				$config['mailtype'] = 'html';
				$this->email->initialize($config);
	
				$this->email->from(config_item('no_reply_email'), config_item('company_name'));
				$this->email->to($email);
	
				$this->email->subject(config_item('company_name').': Multifactor Login Authentication');
				$message =  'Dear '.$user['username'].'<br>';
				
				$mailmessage = str_replace('{otp}',$auth_key,lang('multifactor_auth_message'));
				$message .= $mailmessage;
				// print_r($admin['email']);exit;
				$this->email->message($message);
				$this->email->send();

				msg_flashdata(lang('otp_email_send'));
				redirect(config_item('admin_folder').'/login/otp_verification');
			}
			else{
				error_flashdata('Enter an registered email id');
				redirect(config_item('admin_folder').'/login/emial_auth');
			}
		}

		$this->load->view(config_item('admin_folder').'/auth/auth_multifactor_email');
	}

	/* ----------------------------------------------------------
	check the otp field and verify them from database and
	destroy the accesskey and temp session and update the admin 
	original session to login. Update the browser cookie 
	-----------------------------------------------------------*/
	function otp_verification()
	{
		$user = $this->session->userdata('auth');

		if(!$user)
		{
			redirect(config_item('admin_folder').'/login');
		}

		if($this->input->post('submitted'))
		{
			$otp = $this->input->post('onetimepass');
			// print_a($user,1);
			if($this->auth->check_otp($user['id'],$otp))
			{
				/* remove the OTP */
				$save['id'] = $user['id'];
				$save['auth_key'] = '';
				$this->auth->save($save);

				/* Set the cookie for multifactor verification */
				$this->load->library('user_agent');
				$agent = base64_encode($this->agent->browser().':'.$this->agent->version());
				setcookie('Kogadmin', $agent, strtotime('+6 months'), '/', $_SERVER['HTTP_HOST']);

				/* Now reset the admin session */
				$this->auth->set_admin_session($user['id']);

				/* Destroy the Temp session */
				$this->session->unset_userdata('auth');

				redirect(config_item('admin_folder').'/dashboard');
				
			}
			else
			{
				error_flashdata('Recheck OTP!');
				redirect(config_item('admin_folder').'/login/otp_verification');
			}
		}

		$this->load->view(config_item('admin_folder').'/auth/auth_otp_verification');
	}
}
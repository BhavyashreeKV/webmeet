<?php 
 if ( ! defined('BASEPATH')) die('No direct script access allowed');
/* 
*This is a base class for all the Admin pages where all the 
*Common Initialization will be done.
*/
class BaseController extends CI_Controller
{
    public function __construct()
    {
        
        parent::__construct();

        //load database and base libraries, helpers and models
        $this->load->database();
        
        $this->load->library(array('session','Auth','BehAuth','PatAuth'));
        $this->load->helper(array('url', 'file', 'formatting','string', 'html', 'language', 'date', 'inflector','currency'));
        $this->load->model(array('Common_model'));

        $web_settings = $this->db->select('settings_key,setting')->where('code','website')->get('settings')->result();
        foreach($web_settings as $es){
            $this->config->set_item($es->settings_key,$es->setting);
        }
       
    }

    function check_lang()
    {
        if($this->session->userdata('language'))
        {
            return $this->session->userdata('language');
        }
        else
        {
            // return config_item('language');
            return 'swedish';
        }
    }

    /* Set Language */
    function change_lang($lang)
    {
        $this->session->set_userdata('language',$lang);
    }
}

class Admin_Controller extends BaseController
{
    var $admin;
    function __construct()
    {
        parent::__construct();
        
        $this->auth->is_logged_in(uri_string());
        $this->lang->load(array('common'));
        define('ADMIN_FOLDER',config_item('admin_folder'));
        $this->admin_url = site_url(config_item('admin_folder').'/');
        $this->admin = $this->session->userdata('user');
    }
    function view($view, $vars = array(), $partial = false)
    {
     
        
        if ($partial) {
            $this->load->view(ADMIN_FOLDER.'/'.$view, $vars);
            
        } else {
            $this->load->view(ADMIN_FOLDER.'/common/header', $vars);
            $this->load->view(ADMIN_FOLDER.'/'.$view, $vars);
            $this->load->view(ADMIN_FOLDER.'/common/footer', $vars);
        }
        
    }
}

class Behandlare_Controller extends BaseController
{
    
    var $behandlare;
    
    function __construct()
    {
        parent::__construct();
        
        define('HANDLER_FOLDER',config_item('behandlare_folder'));
        $this->behauth->is_logged_in(uri_string());
        $this->lang->load(array('common'));
        $this->behandlare = $this->session->userdata('behandlare');
    }
    function view($view, $vars = array(), $partial = false)
    {
        if($partial)
        {
            $this->load->view(HANDLER_FOLDER.'/'.$view, $vars);
        }
        else
        {
            $this->load->view(HANDLER_FOLDER.'/common/header', $vars);
            $this->load->view(HANDLER_FOLDER.'/'.$view, $vars);
            $this->load->view(HANDLER_FOLDER.'/common/footer', $vars);
        }
       
        
    }
    
}

class Patient_Controller extends BaseController
{
    var $patient;

    function __construct()
    {
        parent::__construct();
        
        define('PATIENT_FOLDER',config_item('patient_folder'));
        $this->patauth->is_logged_in(uri_string());
        $this->lang->load(array('common'));
        $this->patient = $this->session->userdata('patient');
    }
    
    function view($view, $vars = array(), $partial = false)
    {
        if($partial)
        {
            $this->load->view(PATIENT_FOLDER.'/'.$view, $vars);
        }
        else
        {
            $this->load->view(PATIENT_FOLDER.'/common/header', $vars);
            $this->load->view(PATIENT_FOLDER.'/'.$view, $vars);
            $this->load->view(PATIENT_FOLDER.'/common/footer', $vars);
        }
    }
}
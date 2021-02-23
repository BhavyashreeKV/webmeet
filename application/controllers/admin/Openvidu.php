<?php 
class Openvidu extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('settings_model');
    }

    function index()
    {
        $data['page_title'] = lang('openvidu_settings');

        $data['all_sessions'] = OV_RestApi_Get();
        $this->view('openvidu/openvidu_index',$data);
    }

    function remove_session($session_id=false)
    {
        if(!$session_id)
        {
            error_flashdata(lang('sessionid_not_found'));
            redirect(admin_url('openvidu'));
        }

        $return  = OV_RestApi_Del('/api/sessions/'.$session_id);

        print_a($return);
        if($return['status'] == 204)
        {
            msg_flashdata(lang('session_deleted'));
        }
        else
        {
            error_flashdata(lang('sessionid_not_found'));
        }
        redirect(admin_url('openvidu'));
    }
}
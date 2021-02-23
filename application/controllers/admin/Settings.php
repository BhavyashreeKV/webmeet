<?php 
class Settings extends Admin_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->library('form_validation');
        $this->auth->check_privilege(array('admin'),'dashboard');
        $this->lang->load('settings');
        
    }

    function index()
	{
        $data['cod'] = $this->input->get('t',true)!='' ? $this->input->get('t',true) : 'website';
        $data['page_title'] = ucfirst($data['cod']).' - Settings';
        $w_where = array('where'=>array('code'=>$data['cod']),'order_by'=>'sequence ASC');
        $data['w_config']  = $this->Common_model->get_tbl_list('settings',$w_where);
        $data['themes'] = array();
        $this->load->library(array('form_validation'));
        $this->load->helper('form');
        $this->form_validation->set_rules('name', 'lang:company_name', 'trim');
        if ($this->form_validation->run() == FALSE)
        {
            $data['error'] = validation_errors();
            $this->view('settings/settings', $data);
        }
        else
        {
            $this->session->set_flashdata('message', lang('config_updated_message'));
            //print_r($this->input->post()); exit;
            $this->load->library('upload');
            foreach ($data['w_config'] as $setting)
            {
                if ($setting->type=="file") {
                    $config['upload_path'] = 'uploads/site_images/';
                    $config['allowed_types']	= '*';
                    $config['encrypt_name'] = true;
                    $this->upload->initialize($config);
                    $uploaded = $this->upload->do_upload($setting->settings_key);
                    // if(!empty($setting->setting) || $setting->setting !='' || $setting->setting !=0){
                    if ($uploaded) {
                        if($setting->setting !='') {
                            $file = 'uploads/site_images/' . $setting->setting;
                            if (file_exists($file)) {
                                unlink($file);
                            }
                        }
                        $image			= $this->upload->data();
                        $save['setting']	= $image['file_name'];
                        $this->db->where('settings_key', $setting->settings_key);
                        $this->db->update('settings', $save);
                    }
//                    if (!$uploaded ) {
//                                print_r($this->upload->display_errors()); exit;
//                    }
                }
                else
                {
                    $save['setting']=$this->input->post($setting->settings_key);
                    $this->db->where('settings_key', $setting->settings_key);
                    $this->db->update('settings', $save);
//                    echo $this->db->last_query(); exit;
                }
            }
            $this->session->set_flashdata('message', lang('message_saved'));
            if($data['cod'] == 'commision')
            {
                redirect('settings/commision_settings');
            }
            redirect(ADMIN_FOLDER.'/settings?t='.$data['cod']);
        }
    }
    
    function form($id=false)
    {
        $this->load->library(array('form_validation'));
        $this->load->helper(array('form','inflector','string'));
        $data = $this->Common_model->get_tbl_fields('settings');
        if($id) {
            $data = (array) $this->Common_model->get_tbl_row('settings', $id);
            if(empty($data)){
                $this->session->set_flashdata('error', lang('error_not_found'));
                redirect(ADMIN_FOLDER.'/settings');
            }
        }
        $data['page_title'] = 'Settings - Form';
        $this->form_validation->set_rules('name', 'lang:name', 'trim|required');
        $this->form_validation->set_rules('type', 'lang:type', 'trim|required');
        $this->form_validation->set_rules('sequence', 'lang:sequence', 'trim|numeric');
        $this->form_validation->set_rules('options', 'lang:options', 'trim');
        if ($this->form_validation->run() == FALSE)
        {
            $this->view('settings/settings_form',$data);
        }
        else {
            $save['id'] = $id;
            $save['name'] = $this->input->post('name');
            $save['code'] = $this->input->post('code');
            $save['settings_key'] = underscore(strip_quotes(str_replace(",", "_", $this->input->post('name'))));
            // $save['setting'] = $this->input->post('setting');
            $save['type'] = $this->input->post('type');
            $save['sequence'] = $this->input->post('sequence');
            $save['options'] = $this->input->post('options');
            $this->Common_model->save_tbl('settings',$save);
            $this->session->set_flashdata('message', lang('message_saved'));
            redirect(ADMIN_FOLDER.'/settings?t='.$save['code']);

        }
    }

    function email_templates($id=false)
    {
        
        $data= $this->Common_model->get_tbl_fields('email_templates');
        /* $data['to_email'] = serialize(array()); */
        if($id)
        {
            $data = $this->Common_model->get_tbl_row('email_templates',array('id'=>$id),'*','','array');
        }
        $data['form_page'] = TRUE;
        $data['clist'] = $this->Common_model->get_tbl_list('email_templates');
        $data['page_title'] = lang('email_templates');
        
        // $arr['key'] = 'email';$arr['value'] = 'fullname';$arr['where'] = array('status'=>1);
        // $data['all_user_emails'] = $this->Common_model->get_keyvalue_tbl('admin',$arr);
        
        $this->form_validation->set_rules('name',lang('name'),'trim');
        $this->form_validation->set_rules('subject',lang('subject'),'trim|required');
        $this->form_validation->set_rules('message',lang('message'),'trim|required');
        $this->form_validation->set_rules('from_email',lang('from_email'),'trim');
        // $this->form_validation->set_rules('to_email[]',lang('to_email'),'trim');

        if($this->form_validation->run() == false)
        {
            $this->view('settings/settings_emailtemplate',$data);
        }
        else
        {
            
            $save['id'] = $id;
            $save['name'] = $_POST['name'];
            $save['subject']  = $_POST['subject'];
            $save['message'] = $_POST['message'];
            $save['from_email'] = $_POST['from_email'];
            // $save['to_email'] = (!empty($_POST['to_email']))?serialize($_POST['to_email']):serialize(array());
            
            $this->Common_model->save_tbl('email_templates',$save);

            msg_flashdata(lang('template_saved_successfully'));
            redirect(ADMIN_FOLDER.'/settings/email_templates');
        }
        
    }

    function sms_templates($id=false)
    {
        $data= $this->Common_model->get_tbl_fields('sms_templates');
        if($id)
        {
            $data = $this->Common_model->get_tbl_row('sms_templates',array('id'=>$id),'*','','array');
        }
        $data['form_page'] = TRUE;
        $data['page_title'] = lang('sms_templates');
        $data['templates'] = $this->Common_model->get_tbl_list('sms_templates');

        $set_rules[]=array('name'=>'name','label'=>lang('name'),'rules'=>'trim|required');
        $set_rules[]=array('name'=>'message','label'=>lang('message'),'rules'=>'trim|required');
        if(!set_validation_rules($set_rules))
        {
            $this->view('settings/settings_smstemplate',$data);
        }
        else
        {
            $fields = array('name','message');
            save_data('sms_templates',$id,$fields);
            
            msg_flashdata(lang('template_saved_successfully'));
            redirect(ADMIN_FOLDER.'/settings/sms_templates');
        }
    }
}
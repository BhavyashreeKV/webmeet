<?php
if ( ! defined('BASEPATH')) die('No direct script access allowed');
class Doctors extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lang->load('doctor');
        $this->auth->check_privilege(array('booker'),'dashboard');
    }

    function index()
    {
        $data['page_title'] = lang('manage_doctors');
        $data['perpage'] = $arr['perpage'] = 100;
        $page = 0;
        $arr['where'] =$arr1['where'] = array('type'=>'1');
        $arr['order_by'] = 'id DESC';
        if($this->input->get('term'))
        {
            $arr['like'] = array('firstname'=>$this->input->get('term'));
            $arr['or_like'] = array('lastname'=>$this->input->get('term'),'email'=>$this->input->get('term'),'hsaid'=>$this->input->get('term'),'personal_id'=>$this->input->get('term'));

        }        
        if($this->input->get('page') > 0)
        {
            $page = ($this->input->get('page') - 1) * $data['perpage'];    
        }
        if($this->input->get('sort'))
        {
            $arr['order_by'] = $this->input->get('sort').' '.$this->input->get('by');
        }
        $arr['page'] = $page; 
        $data['lists'] = $this->Common_model->get_tbl_list('users',$arr);
        $arr1['count'] = TRUE;
        $data['total_rows'] = $this->Common_model->get_tbl_list('users',$arr1);

        $this->view('doctors/doctor_list',$data);
    }

    function form($id=false)
    {
        $data = $this->Common_model->get_tbl_fields('users');

        if($id)
        {
            $data    = (array)$this->Common_model->get_tbl_row('users', $id);
        }

        $this->load->helper('form');
        $this->load->library('form_validation');
        $data['page_title'] = lang('doctor_form');
        $set_rules[]=array('name'=>'firstname','label'=>lang('firstname'),'rules'=>'trim|required');
        $set_rules[]=array('name'=>'lastname','label'=>lang('lastname'),'rules'=>'trim|required');
        // $set_rules[]=array('name'=>'hsaid','label'=>lang('hsaid'),'rules'=>'trim|required');
        $set_rules[]=array('name'=>'personal_id','label'=>lang('personal_id'),'rules'=>'trim|required|callback_check_personal_id['.$id.']');
        $set_rules[]=array('name'=>'email','label'=>lang('email'),'rules'=>'trim|required|valid_email|callback_check_email['.$id.']');
        $set_rules[]=array('name'=>'phone','label'=>lang('phone'),'rules'=>'trim|required');
        $set_rules[]=array('name'=>'status','label'=>lang('status'),'rules'=>'trim|required');
        if(!$id)
        {
            $set_rules[]=array('name'=>'password','label'=>lang('password'),'rules'=>'trim|required');
            $set_rules[]=array('name'=>'confirm','label'=>lang('confirm_password'),'rules'=>'trim|required|matches[password]');
        }
        else
        {
            $set_rules[]=array('name'=>'password','label'=>lang('password'),'rules'=>'trim');
            $set_rules[]=array('name'=>'confirm','label'=>lang('confirm_password'),'rules'=>'trim|matches[password]');
        }

        if(!set_validation_rules($set_rules))
        {
            $this->view('doctors/doctor_form',$data);
        }
        else
        {
            $custom['type'] = 1;
            $custom['password'] = !empty($this->input->post('password')) ? sha1($this->input->post('password')) : $data['password'];
            $fields = array('firstname','lastname','personal_id','email','phone','hsaid','privilege','status');
            save_data('users',$id,$fields,$custom);
            // print_last_query();
            /* Send when new email & SMS user is added */
            if(!$id)
            {
                if(config_item('send_email_notification')){
                    /* Fetch the email template to notify the patient login credentails*/
                    $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>9));
                    $fullname = $this->input->post('firstname').' '.$this->input->post('lastname');
                    /* Re-place the Message content */
                    $message = str_replace('{fullname}',$fullname,$row->message);
                    $message = str_replace('{weblink}',site_url(),$message);
                    $message = str_replace('{password}',$this->input->post('password'),$message);
                    $message = str_replace('{personalid}',$this->input->post('personal_id'),$message);
                    $message = str_replace('{company_name}',config_item('company_name'),$message);
                    $to = $this->input->post('email');
                    /* Send email now */
                    $this->load->library('emailnotification');
                    $this->emailnotification->send_email($row->from_email,$to,$row->subject,$message,config_item('company_name'));
                }
               
            }
            msg_flashdata(lang('message_saved'));
            redirect(ADMIN_FOLDER. '/doctors');

        }
        
    }

    function delete($id=false)
    {
        if(!$id)
        {
            error_flashdata(lang('page_not_available'));
            redirect(ADMIN_FOLDER.'/doctors');
        }
        /* Check the tbl for the data before deleting */
        $check = $this->Common_model->check_data('users',array('where'=>array('id'=>$id)));
        if($check)
        {
            $this->Common_model->delete_tbl('users',array('where'=>array('id'=>$id)));
            msg_flashdata(lang('successfully_deleted'));            
        }
        else
        {
            error_flashdata(lang('error_not_found'));
        }
        redirect(ADMIN_FOLDER.'/doctors');
    }

    function check_email($email,$id)
    {
        $data['where'] = array('email'=>$email);
        if($id){
            $data['where'] = array('id !='=>$id,'email'=>$email);
        }
       
        $check = $this->Common_model->check_data('users',$data);
        
        if(!$check)
        {
          return true;  
        }
        else
        {
            $this->form_validation->set_message('check_email',lang('email_already_taken'));
            return false;
        }
    }

    function check_personal_id($personal_id,$id)
    {
        $data['where'] = array('personal_id'=>$personal_id);
        if($id){
            $data['where'] = array('id !='=>$id,'personal_id'=>$personal_id);
        }
       
        $check = $this->Common_model->check_data('users',$data);
        
        if(!$check)
        {
          return true;  
        }
        else
        {
            $this->form_validation->set_message('check_personal_id',lang('personal_id_unique'));
            return false;
        }
    }

    /* Get Patinets list of Doctor */
    function patients($doc_id)
    {
        $data['page_title'] = lang('manage_doctors').' - '.lang('patients');
        $data['perpage'] = $arr['perpage'] = 100;
        $page = 0;
        $arr['where'] =$arr1['where'] = array('type'=>'2','added_by'=>$doc_id);
        $arr['order_by'] = 'id DESC';
        if($this->input->get('term'))
        {
            $arr['like'] = array('firstname'=>$this->input->get('term'));
            $arr['or_like'] = array('lastname'=>$this->input->get('term'),'email'=>$this->input->get('term'),'hsaid'=>$this->input->get('term'),'personal_id'=>$this->input->get('term'));

        }        
        if($this->input->get('page') > 0)
        {
            $page = ($this->input->get('page') - 1) * $data['perpage'];    
        }
        if($this->input->get('sort'))
        {
            $arr['order_by'] = $this->input->get('sort').' '.$this->input->get('by');
        }
        $arr['page'] = $page; 
        $data['lists'] = $this->Common_model->get_tbl_list('users',$arr);
        $arr1['count'] = TRUE;
        $data['total_rows'] = $this->Common_model->get_tbl_list('users',$arr1);

        $data['doctor_id'] = $doc_id;
        $data['ts'] = $this->Common_model->get_tbl_list('users',['where'=>['type'=>1,'id !='=>$doc_id,'privilege' => 'isolated_patient']]);

        $this->view('doctors/patient_list',$data);
    }

    /* Update New Treatment Specialists */
    function cng_treatment_spl($doctor_id)
    {
       $pat_id = $this->input->get('pat_id');
        if($pat_id)
        {
            $added_by = $this->input->get('treatement');
            if($added_by)
            {
                $save['id'] = $pat_id;
                $save['added_by'] = $added_by;
                
                $this->Common_model->save_tbl('users',$save);
                $this->session->set_flashdata('message','Treatement Specialist has been updated successfully!');
                redirect(config_item('admin_folder').'/doctors/patients/'.$doctor_id);
            }
        }
        redirect(config_item('admin_folder').'/doctors/patients/'.$doctor_id);
    }
}
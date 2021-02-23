<?php 
class Secure extends Patient_Controller
{
    function __construct()
    {
        parent::__construct();
        $lang = $this->check_lang();
        $this->lang->load('patient/login_lang',$lang);
    }

    function my_account()
    {
        $data = $this->Common_model->get_tbl_row('users',array('id'=>get_patient_detail('id')),'*','','arr');
        $data['page_title'] = lang('my_profile');
        $this->view('auth/auth_profile',$data);
    }

    function edit_profile()
    {
    //    print_a($this->session->userdata('patient'));

        $id = get_patient_detail('id');
        $data = $this->Common_model->get_tbl_row('users',array('id'=>get_patient_detail('id')),'*','','arr');
        $set_rules[]=array('name'=>'firstname','label'=>lang('firstname'),'rules'=>'trim');
        $set_rules[]=array('name'=>'lastname','label'=>lang('lastname'),'rules'=>'trim');
        // $set_rules[]=array('name'=>'personal_id','label'=>lang('personal_id'),'rules'=>'trim|required|callback_check_personal_id['.$id.']');
        $set_rules[]=array('name'=>'email','label'=>lang('email'),'rules'=>'trim|required|valid_email|callback_check_email['.$id.']');
        $set_rules[]=array('name'=>'phone','label'=>lang('phone'),'rules'=>'trim|required');
        $data['page_title'] = lang('my_profile');
        
        if(!set_validation_rules($set_rules))
        {
            // echo validation_errors();
            $this->view('auth/auth_edit_profile',$data);
        }
        else
        {

            $custom = array();
            $this->load->library('upload');
            $config['upload_path'] = 'uploads/profile/';
            $config['allowed_types']	= 'jpg|png|gif';
            $config['encrypt_name'] = true;
            $this->upload->initialize($config);
            $uploaded = $this->upload->do_upload('profile_img');
            if ($uploaded) {
                if($data['profile_img'] !='') {
                    $file = 'uploads/profile/' . $data['profile_img'];
                    if (file_exists($file)) {
                        unlink($file);
                    }

                    /* Remove image from thumb folder too */
                    $thumb_file = 'uploads/profile/thumb/' . $data['profile_img'];
                    if (file_exists($thumb_file)) {
                        unlink($thumb_file);
                    }
                }
                $image			= $this->upload->data();
                $custom['profile_img']	= $image['file_name'];

                 //thumb image from original enable GD2 library for image resize
                $this->load->library('image_lib');
                $config['image_library'] = 'gd2';
                $config['source_image'] = 'uploads/profile/' . $custom['profile_img'];
                $config['new_image'] = 'uploads/profile/thumb/' . $custom['profile_img'];
                $config['maintain_ratio'] = FALSE;
                $config['width'] = 80;
                $config['height'] = 80;
                $config['quality'] = '60%';
                $this->image_lib->initialize($config);
                $this->image_lib->resize();
                $this->image_lib->clear();
                
                $upfiled['profile_pic'] = $image['file_name'];
            }
            
            $fields = array('email','phone');
            save_data('users',$id,$fields,$custom);

            /* set session of the patient */
            foreach($fields as $filed)
            {
                $upfiled[$filed] = ($this->input->post($filed)!='')?$this->input->post($filed):'';
            }
            $this->patauth->update_profile($upfiled);


            msg_flashdata(lang('profile_updated'));
            redirect('my_profile');

        }
        
    }

    function check_email($email,$id)
    {
        $data['where'] = array('email'=>$email);
        if($id){
            $data['where'] = array('id !='=>$id,'email'=>$email,'type'=>2);
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
            $data['where'] = array('id !='=>$id,'personal_id'=>$personal_id,'type'=>2);
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

    function update_password()
    {
        $data['page_title'] = lang('upadte_password');
        $id = get_patient_detail('id');

        /* get the old password for validation and update the new password */
        $set_rules[]=array('name'=>'old_password','label'=>lang('old_password'),'rules'=>'trim|required|callback_check_currentpassword');
        $set_rules[]=array('name'=>'password','label'=>lang('password'),'rules'=>'trim|required|min_length[6]|alpha_numeric');
        $set_rules[]=array('name'=>'confirm','label'=>lang('confirm_password'),'rules'=>'trim|required|matches[password]');
        if(!set_validation_rules($set_rules))
        {
            $this->view('/auth/auth_profile_updatepassword', $data);
        }
        else
        {
           
            $save['id'] = get_patient_detail('id');
            $save['password'] = sha1($this->input->post('password'));

            $this->Common_model->save_tbl('users',$save);

            msg_flashdata(lang('profile_updated'));
            redirect('my_profile');
        }
    }

    function check_currentpassword($str)
    {
        $data['where'] = array('password'=>sha1($str),'id'=>get_patient_detail('id'));
        $check = $this->Common_model->check_data('users',$data);
        if($check)
        {
          return true;  
        }
        else
        {
            $this->form_validation->set_message('check_currentpassword',lang('password_wrong'));
            return false;
        }
    }
}
<?php 
class Users extends Admin_Controller
{
    function __construct()
	{
        parent::__construct();
        $this->auth->check_privilege(array('admin'),'dashboard');
        $this->User = get_useradata('user');
        
    }

    function index()
    {
        $this->auth->check_privilege(array('admin'),'dashboard');
        $data['admin'] = TRUE;
        $data['page_title'] = lang('manage_users');
        $data['user_list']  = TRUE;
        $data['perpage'] = $arr['perpage'] = 100;
        $page = $this->input->get('page',true);
        $data['page'] = $arr['page'] = ($page>0) ? ($page-1)*$data['perpage'] :0;
        $arr['order_by'] = 'id DESC';
        $data['users']     = $this->Common_model->get_tbl_list('admin',$arr);
        $arr2['count'] = true;
        $data['total_rows'] = $this->Common_model->get_tbl_list('admin',$arr2);

        $this->view('users/user_list',$data);
    }

    function form($id=false)
    {
        $this->auth->check_privilege(array('admin'),'dashboard');
        $data = $this->Common_model->get_tbl_fields('admin');
        $data['privilege'] = array();
        if($id)
        {
            $data    = (array)$this->Common_model->get_tbl_row('admin', $id);
            $data['privilege'] = json_decode($data['privilege']);

        }
        $this->load->helper('form');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('fullname','Full Name','trim|required');  
        $this->form_validation->set_rules('username','Username','required|callback_check_username['.$id.']');
        $this->form_validation->set_rules('email','Email','required|valid_email');
        $this->form_validation->set_rules('status', 'Status', 'trim|required|numeric');
        $this->form_validation->set_rules('privilege[]','Privilege','trim|required'); 
        $this->form_validation->set_rules('phone', 'Phone', 'trim|max_length[15]');        
        if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm', 'Confirm Password', 'required|matches[password]');
        }

    
        $data['form_page'] = TRUE;
        $data['user_script'] = TRUE;
        $data['page_title'] = lang('user_form');
        if($this->form_validation->run() == false)
        {
           
            $this->view('users/user_form',$data);
        }
        else
        {
            $save['id']       = $id;
            $save['fullname']     = $this->input->post('fullname');
            $save['username'] = $this->input->post('username');
            $save['email']    = $this->input->post('email');
            if ($this->input->post('password') != '' || !$id) {
                $save['password'] = sha1($this->input->post('password'));
            }
            $save['status']    = $this->input->post('status');
            $save['phone']  = $this->input->post('phone');
            $save['privilege'] = json_encode($this->input->post('privilege'));
            
            $id = $this->Common_model->save_tbl('admin', $save);

            

            if($id == get_user_detail('id'))
            {
                /* update session data */
                $this->auth->update_profile($save);
            }
            msg_flashdata(lang('message_saved'));
            redirect(ADMIN_FOLDER. '/users');
        }

    }

    function delete($id=false)
    {
        $this->auth->check_privilege(array('admin'),'dashboard');
        if(!$id)
        {
            error_flashdata(lang('page_not_available'));
            redirect(ADMIN_FOLDER.'/users');
        }

        $check = $this->Common_model->check_data('admin',array('where'=>array('id'=>$id)));
        if($check)
        {
            $this->Common_model->delete_tbl('admin',array('where'=>array('id'=>$id)));

            msg_flashdata(lang('successfully_deleted'));
            
        }
        else
        {
            error_flashdata(lang('error_not_found'));
        }
        redirect(ADMIN_FOLDER.'/users');
    }

    function check_username($username,$id)
    {
        
        $data['where'] = array('username'=>$username);
        if($id){
            $data['where'] = array('id !='=>$id,'username'=>$username);
        }
       
        $check = $this->Common_model->check_data('admin',$data);
        
        if(!$check)
        {
          return true;  
        }
        else
        {
            $this->form_validation->set_message('check_username',lang('username_already_taken'));
            return false;
            
        }
    }

   
}
<?php 
class Dashboard extends Admin_Controller
{
    
    function __construct()
	{
		parent::__construct();
        $this->load->model(array('booking_model'));
        
    }

    function index()
    {
        $data['dashboard_script'] = TRUE;
        $data['page_title'] = 'Dashboard';

        /* Total Meetings */
        $arr['count'] = TRUE;
        $data['total_meetings'] = $this->Common_model->get_tbl_list('bookings',$arr);

        $arr1['count'] = TRUE;
        $arr1['where'] = array('booking_date'=>date('Y-m-d'));
        $data['total_todays_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr1);

        $arr2['count'] = TRUE;
        $arr2['where'] = array('booking_date >='=>date('Y-m-d'),'end_datetime >'=>time());
        $data['total_upcoming_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr2);
        
        $arr3['count'] = TRUE;
        $arr3['where'] = array('status'=>'cancelled');
        $data['total_cancelled_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr2);

        $arr4['count'] = TRUE;
        $arr4['where'] = array('booking_date >='=>date('Y-m-d',strtotime('this week')),'booking_date <='=>date('Y-m-d',strtotime('this week +6 days')));
        $data['total_thisweek_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr4);

        $arr5['count'] = TRUE;
        $arr5['where'] = array('status'=>'missed');
        $data['total_missed_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr5);
       
        $arr6['count'] = TRUE;
        $arr6['where'] = array('status'=>'completed');
        $data['total_completed_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr6);

        /* This week meetings */
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['patients'] = $this->booking_model->get_dd_all_users(2);
        $arr7['where'] = array('booking_date >='=>date('Y-m-d',strtotime('this week')),'booking_date <='=>date('Y-m-d',strtotime('this week +6 days')));
        $data['thisweek_meeting'] = $this->Common_model->get_tbl_list('bookings',$arr7);

        $this->view('dashboard',$data);
    }

    function  profile()
    {
        $id = get_user_detail('id');
        $data = $this->Common_model->get_tbl_fields('admin');

        if($id)
        {
            $data    = (array)$this->Common_model->get_tbl_row('admin', $id);
           
        }
        $this->load->helper('form');
        $this->load->library(array('form_validation','upload'));
        $this->form_validation->set_rules('fullname','Full Name','trim|required');  
        $this->form_validation->set_rules('email','Email','required|valid_email');
        $this->form_validation->set_rules('phone', 'Phone   ', 'trim|max_length[15]');        
        if ($this->input->post('password') != '' || $this->input->post('confirm') != '' || !$id) {
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('confirm', 'Confirm Password', 'required|matches[password]');
        }

       
        $data['form_page'] = TRUE;
        $data['page_title'] = lang('my_profile');


        if($this->form_validation->run() == false)
        {
            $this->view('users/user_profile',$data);
        }
        else
        {
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
                $save['profile_img']	= $image['file_name'];

                 //thumb image from original enable GD2 library for image resize
                 $this->load->library('image_lib');
                 $config['image_library'] = 'gd2';
                 $config['source_image'] = 'uploads/profile/' . $save['profile_img'];
                 $config['new_image'] = 'uploads/profile/thumb/' . $save['profile_img'];
                 $config['maintain_ratio'] = FALSE;
                 $config['width'] = 80;
                 $config['height'] = 80;
                 $config['quality'] = '60%';
                 $this->image_lib->initialize($config);
                 $this->image_lib->resize();
                 $this->image_lib->clear();
                
            }
           
            $save['id'] = $id;
            $save['fullname'] = $this->input->post('fullname');
            $save['email'] = $this->input->post('email');
            $save['phone']  = $this->input->post('phone');

            if ($this->input->post('password') != '' || !$id) {
                $save['password'] = sha1($this->input->post('password'));
            }

             
            $id = $this->Common_model->save_tbl('admin',$save);
            
        /* update session data */
        $this->auth->update_profile($save);

            msg_flashdata('user profile updated');
            redirect(ADMIN_FOLDER.'/dashboard/profile');
        }
    }
}
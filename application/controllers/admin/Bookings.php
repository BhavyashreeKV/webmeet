<?php
class Bookings extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->lang->load('meetings');
        $this->load->model('booking_model');
        $this->auth->check_privilege(array('booker'),'dashboard');
    }

    function index($status='new',$id = false)
    {
        $data= $this->Common_model->get_tbl_fields('bookings');
     
        $data['status'] = $status;
        if($id)
        {
            $data = $this->Common_model->get_tbl_row('bookings',array('id'=>$id),'*','','arr');
            $data['start_datetime'] = empty($data['start_datetime']) || $data['start_datetime'] == NULL ?'': date('H:i',$data['start_datetime']);
            $data['end_datetime'] = empty($data['end_datetime']) || $data['start_datetime'] == NULL ? '' : date('H:i',$data['end_datetime']);
            
        }
        // print_a($data);
        $data['b_id'] = $id;

        $data['page_title'] = 'Book A Meeting';
        // $data['booking_script'] = TRUE;

        $data['all_doctors'] = $this->booking_model->get_most_booked_docs();
        // $data['all_doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['all_patients'] = $this->booking_model->get_dd_all_users(2);
        $data['last_scheduled'] = $this->booking_model->get_bookings(15, 0);

        
        $set_rules[] = array('name' => 'booking_date', 'label' => lang('date'), 'rules' => 'trim|required');
        $set_rules[] = array('name' => 'startdate', 'label' => lang('start_date'), 'rules' => 'trim|required|callback_check_meetings_start['.$id.']');
        $set_rules[] = array('name' => 'enddate', 'label' => lang('end_date'), 'rules' => 'trim|required|callback_check_meetings_end['.$id.']');
        if($status != 'new')
        {
            $set_rules[] = array('name' => 'status', 'label' => lang('status'), 'rules' => 'trim|required');
        }
        $set_rules[] = array('name' => 'doctor_id', 'label' => lang('doctor'), 'rules' => 'trim|required');
        $set_rules[] = array('name' => 'patient_id', 'label' => lang('patient'), 'rules' => 'trim|required|callback_check_patient['.$id.']');

        if (!set_validation_rules($set_rules)) {
            $this->view('booking/booking_schedule', $data);
        } else {
            $custom['start_datetime'] = strtotime($this->input->post('booking_date') . ' ' . $this->input->post('startdate'));
            $custom['end_datetime'] = strtotime($this->input->post('booking_date') . ' ' . $this->input->post('enddate'));
            $custom['booking_date'] = date('Y-m-d', strtotime($this->input->post('booking_date')));
            $custom['created_by'] = get_user_detail('id');
            /* if($data['status'] != $_POST['status'])
            {
                $custom['notify'] = 1;
            } */
            $custom['notify'] = $this->input->post('notify')?$this->input->post('notify'):0;
            if(!$id)
            $custom['meeting_id'] = rand(100000, 999999);
            $fields = array('status', 'patient_id', 'doctor_id');
            $booking_id = save_data('bookings', $id, $fields, $custom);

            /* Send Notification regarding the meeting to the doctor and to the customer regarding new the meeting. */            
            if(!$id)
            {
                $meeting_datetime = $this->input->post('booking_date').' - '.$this->input->post('startdate').' to '.$this->input->post('enddate');
                $this->booking_model->notify_new_meeting($booking_id,$meeting_datetime);

            }
            if($id && $_POST['status'] == 'rescheduled')
            {
                $meeting_datetime = $this->input->post('booking_date').' - '.$this->input->post('startdate').' to '.$this->input->post('enddate');
                $this->booking_model->notify_rescheduled_meeting($booking_id,$meeting_datetime);
            }
            if($id && $_POST['status'] == 'cancelled')
            {
                $meeting_datetime = $this->input->post('booking_date').' - '.$this->input->post('startdate').' to '.$this->input->post('enddate');
                $this->booking_model->notify_cancelled_meeting($booking_id,$meeting_datetime);
                $custom['id'] = $booking_id;
                $custom['start_datetime'] = NULL;
                $custom['end_datetime'] = NULL;
                $this->Common_model->save_tbl('bookings',$custom);

            }

            msg_flashdata(lang('message_saved'));
            redirect(ADMIN_FOLDER . '/bookings');
        }
    }

    function get_doctors_meeting()
    {
        $start_Date = $this->input->post('bdate');
        $doc = $this->input->post('doc');

        $start_Date = date('Y-m-d', strtotime($start_Date));
        
        $meetings = $this->booking_model->get_docMeetingDates($doc, $start_Date);
        // print_last_query();
        $doctr = $this->Common_model->get_tbl_row('users', array('id' => $doc));
        $return = array();
        $return['list'] = '';
        if (!empty($meetings)) {
            foreach ($meetings as $meeting) {
                $return['list'] .= '<li class="list-group-item">' . $meeting->meeting_id . ' - (<span class="badge badge-success">' . date('h:i A', $meeting->start_datetime) . '</span> <=> <span class="badge badge-success">' . date('h:i A', $meeting->end_datetime) . '</span>) <div>Patient Name: <span class="badge badge-warning"> '.$meeting->firstname.' '.$meeting->lastname.'</span></div></li>';
            }
        } else {
            $return['list'] = '<li class="list-group-item">No Meetings for the day</li>';
        }

        $return['doctor'] = $doctr->firstname . ' ' . $doctr->lastname;

        echo json_encode($return);
    }

    public function posts()
    {

        $columns = array(
            6 => 'id',
            7 => 'meeting_id',
            3 => 'status',
            4 => 'doctor_fullname',
            5 => 'patient_fullname',
            1 => 'booking_date',
            2 => 'start_datetime',
            0 =>'action',  
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_order = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:6;
        $order = $columns[$post_order];
        $dir = $this->input->post('order')[0]['dir'];

        $totalData = $this->booking_model->allposts_count();
       
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            $posts = $this->booking_model->allposts($limit, $start, $order, $dir);
            // print_a($posts);
        } else {
            $search = $this->input->post('search')['value'];

            $posts =  $this->booking_model->posts_search($limit, $start, $search, $order, $dir);

            $totalFiltered = $this->booking_model->posts_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $nestedData['action'] = '';
                    if($post->status != 'completed')
                    {
                        $nestedData['action'] =  '<a class="btn btn-outline-primary btn-icon waves-effect" data-toggle="tooltip" data-original-title="Change meeting status" href="'.admin_url('bookings/index/rescheduled/'.$post->id).'"><i class="fa fa-retweet"></i></a>';
                    }
                $nestedData['action'] .= ' <a class="btn btn-outline-danger btn-icon waves-effect" onclick="return areyousure()" data-toggle="tooltip" data-original-title="Delete" href="'.admin_url('bookings/delete/'.$post->id).'"><i class="fa fa-times"></i></a>'; 
                $nestedData['id'] = $post->id;
                $nestedData['meeting_id'] = $post->meeting_id;
                $nestedData['status'] = ucfirst($post->status);
                $nestedData['doctor_fullname'] = '<span class="lead">'.ucfirst($post->doctor_fullname).'</span>';
                $nestedData['patient_fullname'] = '<span class="lead">'.ucfirst($post->patient_fullname).'</span>';
                $nestedData['booking_date'] = $post->booking_date;
                $nestedData['meeting_time'] = date('H:i:s',$post->start_datetime).' to '.date('H:i:s',$post->end_datetime);

                $data[] = $nestedData;
            }
        }

        $json_data = array(
            "draw"            => intval($this->input->post('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $data
        );

        echo json_encode($json_data);
    }

    function check_meetings_start($start_time,$id)
    {
        $doc_id = $this->input->post('doctor_id');
        $booking_date = $this->input->post('booking_date');

        $startdatetime = strtotime($booking_date.' '.$start_time);
        if($id)
        {
            $available_time = $this->booking_model->check_startdate($doc_id,$startdatetime,$id);
        }
        else
        {
            $available_time = $this->booking_model->check_startdate($doc_id,$startdatetime);
        }
        

        if($available_time)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('check_meetings_start','Start Time already in schedule! try some other time.');
            return false;
        }

    }

    function check_meetings_end($end_time,$id)
    {
    
        $doc_id = $this->input->post('doctor_id');
        $booking_date = $this->input->post('booking_date');

        $datetime = strtotime($booking_date.' '.$end_time);
        
        if($id)
        {
            $available_time = $this->booking_model->check_enddate($doc_id,$datetime,$id);
        }
        else
        {
            $available_time = $this->booking_model->check_enddate($doc_id,$datetime);
        }

        if($available_time)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('check_meetings_end','End Time already in schedule! try some other time.');
            return false;
        }

    }

    function check_patient($pat_id,$id)
    {
        $start_datetime = strtotime($this->input->post('booking_date') . ' ' . $this->input->post('startdate'));
        $end_datetime = strtotime($this->input->post('booking_date') . ' ' . $this->input->post('enddate'));

        if($id)
        {
            $availability = $this->booking_model->check_pat_availability($pat_id,$start_datetime,$end_datetime,$id);
        }
        else
        {
            $availability = $this->booking_model->check_pat_availability($pat_id,$start_datetime,$end_datetime);
        }
        if($availability)
        {
            return true;
        }
        else
        {
            $this->form_validation->set_message('check_patient','Patient have another appointment. Please select some other time');
            return false;
        }

    }

    function delete($id)
    {
        if(!$id)
        {
            error_flashdata(lang('page_not_available'));
            redirect(ADMIN_FOLDER.'/bookings');
        }
        /* Check the tbl for the data before deleting */
        $check = $this->Common_model->check_data('bookings',array('where'=>array('id'=>$id)));
        if($check)
        {
            $this->Common_model->delete_tbl('bookings',array('where'=>array('id'=>$id)));
            msg_flashdata(lang('successfully_deleted'));            
        }
        else
        {
            error_flashdata(lang('error_not_found'));
        }
        redirect(ADMIN_FOLDER.'/bookings');
    }
}

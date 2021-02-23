<?php 
class Conference extends Patient_Controller
{

    function __construct()
    {
        parent::__construct();
        $lang = $this->check_lang();
        $this->lang->load('patient/meetings',$lang);
        $this->load->model('booking_model');
    }

    function attend($meeting_id)
    {
        if(!$meeting_id)
        {
            error_flashdata('Cant access this session');
            redirect('meetings');
        }
        
        $meeting_details = $this->Common_model->get_tbl_row('bookings',array('meeting_id'=>$meeting_id,'patient_id'=>get_patient_detail('id'),'status !='=>'completed'));
        if(empty($meeting_details))
        {
            error_flashdata('Cant access this session');
            redirect('meetings');
        }
        if (($meeting_details->start_datetime - (2 * 60)) < strtotime(date('d-m-Y H:i:s')) && ($meeting_details->end_datetime) > strtotime(date('d-m-Y H:i:s'))) {
        $data['page_title'] = "Conference ($meeting_details->meeting_id)";
        $data['meeting_details'] = $meeting_details;
        $data['doctor_details'] = $this->Common_model->get_tbl_row('users',array('id'=>$meeting_details->doctor_id),'CONCAT(firstname," ",lastname) as fullname,email,phone');
        $this->view('conference/conference_join_meeting',$data,true);
        }
        else
        {
            error_flashdata('Cant access this session');
            redirect('meetings');
        }

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

    function get_session($ajax=false)
    {
        if($ajax)
        {
            $meeting_id = $this->input->post('m1d');
            if(!$meeting_id)
            {
                return die;
            }
            $meetings = $this->Common_model->get_tbl_row('bookings',array('meeting_id'=>$meeting_id));
            /*  Check if session is already created or not. If "Yes" then move to the pass the saved sessionid as response,
                if "not" please store the new data and store it into the table and send created session id as response */
            if(empty($meetings->ov_sess))
            {   
                $post['customSessionId'] = strval($meetings->meeting_id);
                $response = OV_RestApi_Pst('/api/sessions',$post);
                if($response['status'] == 200)
                {
                    /* Save the created session from Openvidu platform */
                    $save['id'] = $meetings->id;
                    $save['ov_sess'] = $response['result']->id;
                    $this->Common_model->save_tbl('bookings',$save);
                    echo $response['result']->id;
                    // echo json_encode($response['result']);
                }
                else if($response['status'] == 400)
                {
                    http_response_code(400);
                    echo "No connection to OpenVidu Server. This may be a certificate error";
                    exit;
                }
                else
                {
                    // echo json_encode($response['result']);
                    echo strval($meeting_id);
                }
                
            }
            else
            {
                // echo json_encode(array('id'=>$meetings->ov_sess,'createdAt'=>'1573214440343'));
                echo strval($meetings->ov_sess);
            }
        }
    }

  
    function get_tokens($ajax=false)
    {
        if($ajax)
        {
           $session_id = $this->input->post('sess');
            // print_a($session_id);
            
            $meetings = $this->Common_model->get_tbl_row('bookings',array('meeting_id'=>$session_id));
            if(!$session_id)
            {
                http_response_code(400);
                echo 'Error in the connection';
            }
            else
            {
                $post['session'] = strval($session_id);
                
                $post['data'] = get_patient_detail('name');
                
                $response = OV_RestApi_Pst('/api/tokens',$post);
                // print_a($response);
                if($response['status'] == 200)
                {
                    /* Save the created session from Openvidu platform */
                    $save['id'] = $meetings->id;
                    $save['pat_sess'] = 1;
                    $this->Common_model->save_tbl('bookings',$save);
                    
                    echo json_encode($response['result']);
                }
                else
                {

                    $save['id'] = $meetings->id;
                    $save['ov_sess'] = NULL;
                    $this->Common_model->save_tbl('bookings',$save);

                    http_response_code(405);
                    echo 'Session not set';
                }

            }

        }
    }

    function rate_review($id=false)
    {
        if($this->input->post('submitted') && $this->input->post('rating') != '')
        {
            // $save['id'] = $id;
            $save['booking_id'] = $this->input->post('booking_id');
            $save['rating'] = $this->input->post('rating');
            $save['review'] = $this->input->post('review');
            $save['type'] = 'Patient';
            $save['user_id'] = get_patient_detail('id');
        
            $this->Common_model->save_tbl('ratings',$save);

            /* Close or End the meeting by changing the status to complete */
           /*  $ssave['id'] = $this->input->post('booking_id');
            $ssave['status'] = 'Completed';
            $this->Common_model->save_tbl('bookings',$ssave); */
            $this->complete_sesion($save['booking_id']);

            $return['success_msg_redirect'] = 'Thank you for your feedback';
            

            echo json_encode($return);
        }
        else
        {
            echo json_encode(array('error'=>'Rating field is required'));
        }
    }
    
    function complete_sesion($meeting_id)
    {
        /* Close the meeting */
        $ssave['id'] = $meeting_id;
        $ssave['end_datetime'] = time();
        $ssave['status'] = 'Completed';
        $this->Common_model->save_tbl('bookings',$ssave);
    }

    function check_leave_status($session_id)
    {
        $arra['where'] = array('doc_sess'=>1,'pat_sess'=>1,'meeting_id'=>$session_id);
        $resp = $this->Common_model->check_data('bookings',$arra);

        if($resp)
        {
            echo 'sessioncompleted';
        }
        else
        {
            echo 'sessionincompleted';
        }
    }
}
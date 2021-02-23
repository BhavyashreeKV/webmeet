<?php 
class Booking_model extends CI_Model
{
    function get_dd_all_users($type=1)
    {
        $this->db->where('type',$type);
        $result = $this->db->get('users')->result();

        $return = array(''=>'-- Select --');
        foreach($result as $result)
        {
            $return[$result->id] = $result->firstname.' '.$result->lastname.' - '.$result->personal_id;
        }

        return $return;

    }

    function get_docMeetingDates($doc,$stdate,$edate=false)
    {
        $this->db->select('bookings.*,users.firstname,users.lastname');
        $this->db->where('doctor_id',$doc);
        $this->db->group_start();
        $this->db->where('booking_date',$stdate);
        if($edate)
        $this->db->or_where('booking_date',$edate);
        $this->db->group_end();
        $this->db->join('users','users.id = bookings.patient_id');
        $this->db->order_by('start_datetime','ASC');
        $this->db->where_in('bookings.status',array('new','rescheduled'));
        return $this->db->get('bookings')->result();
    }

    function get_bookings($perpage = 15,$page=false)
    {
        $this->db->limit($perpage);
        $this->db->offset($page);
        $this->db->order_by('id','DESC');
        return $this->db->get('bookings')->result();


    }

    function allposts_count($immediate=false)
    {   
        if($immediate)
        {
            $this->db->join('immediate_bookings','immediate_bookings.booking_id = bookings.id');
        }
        $query = $this
                ->db
                ->get('bookings');
        
        return $query->num_rows();  

    }
    function allgroup_posts_count($immediate=false)
    {   
        $this->db->where('meeting_type',3);
        $query = $this
                ->db
                ->get('bookings');
        
        return $query->num_rows();  

    }
    
    function allposts($limit=false,$start,$col,$dir,$immediate=false)
    {   
                if($immediate)
                $this->db->select('immediate_bookings.personal_id,immediate_bookings.auth_patient,immediate_bookings.phone');
                $this
                ->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname
                ,DATE_FORMAT(FROM_UNIXTIME(bookings.start_datetime), \' %H:%i:%s\') as starttime,DATE_FORMAT(FROM_UNIXTIME(bookings.end_datetime), \' %H:%i:%s\') as endtime');
                if($limit)
                $this->db->limit($limit,$start);
                if($immediate)
                {
                    $this->db->join('immediate_bookings','immediate_bookings.booking_id = bookings.id');
                }
                $query = $this->db->order_by($col,$dir)
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id','left outer')
                ->get('bookings');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function allgroup_posts($limit=false,$start,$col,$dir)
{   
        $this->db->where('meeting_type',3);
        $this
        ->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname
        ,DATE_FORMAT(FROM_UNIXTIME(bookings.start_datetime), \' %H:%i:%s\') as starttime,DATE_FORMAT(FROM_UNIXTIME(bookings.end_datetime), \' %H:%i:%s\') as endtime');
        if($limit)
        $this->db->limit($limit,$start);
        $query = $this->db->order_by($col,$dir)
        ->join('users as u1','bookings.doctor_id = u1.id')
        ->join('users as u2','bookings.patient_id = u2.id','left outer')
        ->get('bookings');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }
   
    function posts_search($limit=false,$start,$search,$col,$dir,$immediate=false)
    {
                if($immediate)
                $this->db->select('immediate_bookings.personal_id,immediate_bookings.auth_patient,immediate_bookings.phone');
                $this
                ->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname
                ,DATE_FORMAT(FROM_UNIXTIME(bookings.start_datetime), \' %H:%i:%s\') as starttime,DATE_FORMAT(FROM_UNIXTIME(bookings.end_datetime), \' %H:%i:%s\') as endtime')
                ->group_start()
                ->like('bookings.id',$search)
                ->or_like('meeting_id',$search)
                ->or_like('booking_date',$search)
                ->or_like('bookings.status',$search)
                ->or_like('CONCAT(u2.firstname," ",u2.lastname)',$search)
                ->or_like('CONCAT(u1.firstname," ",u1.lastname)',$search)
                ->group_end();
                if($limit)
                $this->db->limit($limit,$start);
                if($immediate)
                {
                    $this->db->join('immediate_bookings','immediate_bookings.booking_id = bookings.id');
                }
                $query =  $this->db
                ->order_by($col,$dir)
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id','left outer')
                ->get('bookings');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }
    function group_posts_search($limit=false,$start,$search,$col,$dir)
    {
                $this->db->where('meeting_type',3);
                $this
                ->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname
                ,DATE_FORMAT(FROM_UNIXTIME(bookings.start_datetime), \' %H:%i:%s\') as starttime,DATE_FORMAT(FROM_UNIXTIME(bookings.end_datetime), \' %H:%i:%s\') as endtime')
                ->group_start()
                ->like('bookings.id',$search)
                ->or_like('meeting_id',$search)
                ->or_like('booking_date',$search)
                ->or_like('bookings.status',$search)
                ->or_like('CONCAT(u2.firstname," ",u2.lastname)',$search)
                ->or_like('CONCAT(u1.firstname," ",u1.lastname)',$search)
                ->group_end();
                if($limit)
                $this->db->limit($limit,$start);
                
                $query =  $this->db
                ->order_by($col,$dir)
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id','left outer')
                ->get('bookings');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function posts_search_count($search)
    {
        $query = $this
                ->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname
                ,DATE_FORMAT(FROM_UNIXTIME(bookings.start_datetime), \' %H:%i:%s\') as starttime,DATE_FORMAT(FROM_UNIXTIME(bookings.end_datetime), \' %H:%i:%s\') as endtime')
                ->group_start()
                ->like('bookings.id',$search)
                ->or_like('meeting_id',$search)
                ->or_like('booking_date',$search)
                ->or_like('bookings.status',$search)
                ->or_like('CONCAT(u2.firstname," ",u2.lastname)',$search)
                ->or_like('CONCAT(u1.firstname," ",u1.lastname)',$search)
                ->group_end()
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id','left outer')
                ->get('bookings');
    
        return $query->num_rows();
    } 
   
    function check_startdate($doc_id,$datetime,$id=false)
    {
        $this->db->where('id !=',$id);
        $check = $this->db->where('doctor_id',$doc_id)
            ->where('start_datetime <=',$datetime)
            ->where('end_datetime >',$datetime)
            ->where('booking_date',date('Y-m-d',$datetime))
            ->get('bookings')->num_rows();

        if($check > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
        
    }

    function check_enddate($doc_id,$datetime,$id=false)
    {
        if($id)
        $this->db->where('id !=',$id);
        $check = $this->db->where('doctor_id',$doc_id)
            ->where('start_datetime <',$datetime)
            ->where('end_datetime >=',$datetime)
            ->where('booking_date',date('Y-m-d',$datetime))
            ->get('bookings')->num_rows();

        if($check > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
        
    }

    function check_pat_availability($patient_id,$startdate,$enddate,$id=false)
    {
        if($id)
        $this->db->where('id !=',$id);
        $check = $this->db->where('patient_id',$patient_id)
        ->group_start()
        ->where('start_datetime <=',$startdate)
        ->where('end_datetime >',$startdate)
        ->or_where('start_datetime <',$enddate)
        ->where('end_datetime >=',$enddate)
        ->group_end()
        ->get('bookings')->num_rows();
        if($check > 0)
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    function store_batchalert_noti($meeting_id,$status_id,$reponse)
    {
        $not['meeting_id'] = $meeting_id;
        $not['status'] = $status_id;
        $not['message_id'] = isset($reponse->MessageId[0])?$reponse->MessageId[0]:0;
        $not['batch_id'] = isset($reponse->BatchId)?$reponse->BatchId:NULL;
        $not['notification'] = json_encode($reponse);
        $this->Common_model->save_tbl('sms_notification_alert',$not); 
    }

    function notify_new_meeting($booking_id,$meeting_datetime)
    {
        $booking = $this->db->where('id',$booking_id)->get('bookings')->row();
        if($booking->notify == 1)
        {
            /* Get Patient details */
            $patient = $this->Common_model->get_tbl_row('users',array('id'=>$booking->patient_id),'firstname,lastname,email,phone');
            /* Get Doctors details */
            $doctor = $this->Common_model->get_tbl_row('users',array('id'=>$booking->doctor_id),'id,firstname,lastname,email,phone');
            if(config_item('send_email_notification')){
                /* Fetch the email template for new meeting assigned to Doctor*/
                $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>1));

                /* Re-place the Subject content */
                $subject = str_replace('{meeting_id}',$booking->meeting_id,$row->subject);
                $subject = str_replace('{date_time}',$meeting_datetime,$subject);
                $subject = str_replace('{status}',ucfirst($booking->status),$subject);
                /* Re-place the Message content */
                $message = str_replace('{meeting_id}',$booking->meeting_id,$row->message);
                $message = str_replace('{status}',ucfirst($booking->status),$message);
                $message = str_replace('{date_time}',$meeting_datetime,$message);
                $message = str_replace('{company_name}',config_item('company_name'),$message);
                $message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$message);
                $to = $doctor->email;
                /* Send email now */
                $this->load->library('emailnotification');
                $this->emailnotification->send_email($row->from_email,$to,$subject,$message,config_item('company_name'));

                /* Fetch the email template for new meeting assigned to Patient*/
                $p_row = $this->Common_model->get_tbl_row('email_templates',array('id'=>3));
                /* Re-place the Subject content */
                $p_subject = str_replace('{meeting_id}',$booking->meeting_id,$p_row->subject);
                $p_subject = str_replace('{date_time}',$meeting_datetime,$p_subject);
                $p_subject = str_replace('{status}',ucfirst($booking->status),$p_subject);
                /* Re-place the Message content */
                $p_message = str_replace('{meeting_id}',$booking->meeting_id,$p_row->message);
                $p_message = str_replace('{status}',ucfirst($booking->status),$p_message);
                $p_message = str_replace('{date_time}',$meeting_datetime,$p_message);
                $p_message = str_replace('{company_name}',config_item('company_name'),$p_message);
                $p_message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$p_message);
                $p_message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$p_message);
                $receiver = $patient->email;
                /* Send email Now */
                $this->emailnotification->send_email($p_row->from_email,$receiver,$p_subject,$p_message,config_item('company_name'));
            }
            /* Send Imidiate SMS */
            if(config_item('send_sms'))
            {
                $this->load->helper('sms');
                if(!empty($patient->phone))
                {   /* Notify Customer */
                    $sms_template = $this->Common_model->get_tbl_row('sms_templates',2);
                    $message = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                    $message = str_replace('{date_time}',$meeting_datetime,$message);
                    $message = str_replace('{status}',ucfirst($booking->status),$message);
                    $message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$message);
                    $message = str_replace('{mobile_no}',$doctor->phone,$message);
                    $reponse = send_sms('+'.$patient->phone,$message); /* Send SMS */
                    if($reponse['status'] != 201)
                    {
                        $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                    }
                }
                if(!empty($doctor->phone))
                {
                    /* Notify the Doctor */
                    $sms_template = $this->Common_model->get_tbl_row('sms_templates',1);
                    $dmessage = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                    $dmessage = str_replace('{date_time}',$meeting_datetime,$dmessage);
                    $dmessage = str_replace('{status}',ucfirst($booking->status),$dmessage);
                    $dmessage = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$dmessage);
                    $dmessage = str_replace('{mobile_no}',$patient->phone,$dmessage);
                    $reponse = send_sms('+'.$doctor->phone,$dmessage); /* Send SMS */
                    if($reponse['status'] != 201)
                    {
                        $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                    }
                }
            }

            $save['id'] = $booking_id;
            $save['notify'] = 0;
            $this->Common_model->save_tbl('bookings',$save);

            /* Set Chron for remainder email */
            $this->set_email_queue($booking_id,$meeting_datetime);
        }
        
        return true;
    }

    function notify_rescheduled_meeting($booking_id,$meeting_datetime)
    {
        $booking = $this->db->where('id',$booking_id)->get('bookings')->row();
        if($booking->notify == 1)
        {
            /* Get Patient details */
            $patient = $this->Common_model->get_tbl_row('users',array('id'=>$booking->patient_id),'firstname,lastname,email,phone');
            /* Get Doctors details */
            $doctor = $this->Common_model->get_tbl_row('users',array('id'=>$booking->doctor_id),'id,firstname,lastname,email,phone');
            if(config_item('send_email_notification')){
                /* Fetch the email template for new meeting assigned to Doctor*/
                $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>1));

                /* Re-place the Subject content */
                $subject = str_replace('{meeting_id}',$booking->meeting_id,$row->subject);
                $subject = str_replace('{date_time}',$meeting_datetime,$subject);
                $subject = str_replace('{status}',ucfirst($booking->status),$subject);
                /* Re-place the Message content */
                $message = str_replace('{meeting_id}',$booking->meeting_id,$row->message);
                $message = str_replace('{status}',ucfirst($booking->status),$message);
                $message = str_replace('{date_time}',$meeting_datetime,$message);
                $message = str_replace('{company_name}',config_item('company_name'),$message);
                $message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$message);
                $to = $doctor->email;
                /* Send email now */
                $this->load->library('emailnotification');
                $this->emailnotification->send_email($row->from_email,$to,$subject,$message,config_item('company_name'));

                /* Fetch the email template for new meeting assigned to Patient*/
                $p_row = $this->Common_model->get_tbl_row('email_templates',array('id'=>3));
                /* Re-place the Subject content */
                $p_subject = str_replace('{meeting_id}',$booking->meeting_id,$p_row->subject);
                $p_subject = str_replace('{date_time}',$meeting_datetime,$p_subject);
                $p_subject = str_replace('{status}',ucfirst($booking->status),$p_subject);
                /* Re-place the Message content */
                $p_message = str_replace('{meeting_id}',$booking->meeting_id,$p_row->message);
                $p_message = str_replace('{status}',ucfirst($booking->status),$p_message);
                $p_message = str_replace('{date_time}',$meeting_datetime,$p_message);
                $p_message = str_replace('{company_name}',config_item('company_name'),$p_message);
                $p_message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$p_message);
                $p_message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$p_message);
                $receiver = $patient->email;
                /* Send email Now */
                $this->emailnotification->send_email($p_row->from_email,$receiver,$p_subject,$p_message,config_item('company_name'));
            }
            /* Send Imidiate SMS */
            if(config_item('send_sms'))
            {
                
                $this->load->helper('sms');
                if(!empty($patient->phone))
                {   /* Notify Customer */ 
                    $sms_template = $this->Common_model->get_tbl_row('sms_templates',2);
                    $message = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                    $message = str_replace('{date_time}',$meeting_datetime,$message);
                    $message = str_replace('{status}',ucfirst($booking->status),$message);
                    $message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$message);
                    $message = str_replace('{mobile_no}',$doctor->phone,$message);
                    $reponse = send_sms('+'.$patient->phone,$message); /* Send SMS */
                    if($reponse['status'] != 201)
                    {
                        $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                    }
                }
                if(!empty($doctor->phone))
                {
                    /* Notify the Doctor */
                    $sms_template = $this->Common_model->get_tbl_row('sms_templates',1);
                    $dmessage = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                    $dmessage = str_replace('{date_time}',$meeting_datetime,$dmessage);
                    $dmessage = str_replace('{status}',ucfirst($booking->status),$dmessage);
                    $dmessage = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$dmessage);
                    $dmessage = str_replace('{mobile_no}',$patient->phone,$dmessage);
                    $reponse = send_sms('+'.$doctor->phone,$dmessage); /* Send SMS */
                    if($reponse['status'] != 201)
                    {
                        $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                    }
                }
            }

            $save['id'] = $booking_id;
            $save['notify'] = 0;
            $this->Common_model->save_tbl('bookings',$save);
            /* Remove the scheduled chron from the queue table */
            $this->db->where('meeting_id',$booking->meeting_id)->delete('chron_email_queue');
            /* Set Chron for remainder email */
            $this->set_email_queue($booking_id,$meeting_datetime);
        }
        return true;
    }

    function notify_cancelled_meeting($booking_id,$meeting_datetime)
    {
        $booking = $this->db->where('id',$booking_id)->get('bookings')->row();
        if($booking->notify == 1)
        {
            // $meeting_datetime = date('d-m-Y',strtotime($meeting_datetime));
            /* Get Patient details */
            $patient = $this->Common_model->get_tbl_row('users',array('id'=>$booking->patient_id),'firstname,lastname,email,phone');
            /* Get Doctors details */
            $doctor = $this->Common_model->get_tbl_row('users',array('id'=>$booking->doctor_id),'id,firstname,lastname,email,phone');
            if(config_item('send_email_notification')){
                /* Fetch the email template for new meeting assigned to Doctor*/
                $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>5));

                /* Re-place the Subject content */
                $subject = str_replace('{meeting_id}',$booking->meeting_id,$row->subject);
                $subject = str_replace('{date_time}',$meeting_datetime,$subject);
                $subject = str_replace('{status}',ucfirst($booking->status),$subject);
                /* Re-place the Message content */
                $message = str_replace('{meeting_id}',$booking->meeting_id,$row->message);
                $message = str_replace('{date_time}',$meeting_datetime,$message);
                $message = str_replace('{company_name}',config_item('company_name'),$message);
                $message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$message);
                $to = $doctor->email;
                /* Send email now */
                $this->load->library('emailnotification');
                $this->emailnotification->send_email($row->from_email,$to,$subject,$message,config_item('company_name'));

                /* Fetch the email template for new meeting assigned to Patient*/
                $p_row = $this->Common_model->get_tbl_row('email_templates',array('id'=>6));
                /* Re-place the Subject content */
                $p_subject = str_replace('{meeting_id}',$booking->meeting_id,$p_row->subject);
                $p_subject = str_replace('{date_time}',$meeting_datetime,$p_subject);
                $p_subject = str_replace('{status}',ucfirst($booking->status),$p_subject);
                /* Re-place the Message content */
                $p_message = str_replace('{meeting_id}',$booking->meeting_id,$p_row->message);
                $p_message = str_replace('{date_time}',$meeting_datetime,$p_message);
                $p_message = str_replace('{company_name}',config_item('company_name'),$p_message);
                $p_message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$p_message);
                $p_message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$p_message);
                $receiver = $patient->email;
                /* Send email Now */
                $this->emailnotification->send_email($p_row->from_email,$receiver,$p_subject,$p_message,config_item('company_name'));
            }
            /* Send Imidiate SMS */
            if(config_item('send_sms'))
            {
                $this->load->helper('sms');
                /* Remove if old Batch SMS is there */
                /* Check And Delete the SMS if already shcheduled */
                $this->check_del_oldbatch($booking->meeting_id);

                if(!empty($patient->phone))
                {   /* Notify Customer */
                    $sms_template = $this->Common_model->get_tbl_row('sms_templates',5);
                    $message = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                    $message = str_replace('{date_time}',$meeting_datetime,$message);
                    $message = str_replace('{status}',ucfirst($booking->status),$message);
                    $message = str_replace('{user_name}',$doctor->firstname.' '.$doctor->lastname,$message);
                    $reponse = send_sms('+'.$patient->phone,$message); /* Send SMS */
                    if($reponse['status'] != 201)
                    {
                        $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                    }
                }
                if(!empty($doctor->phone))
                {
                    /* Notify the Doctor */
                    $sms_template = $this->Common_model->get_tbl_row('sms_templates',5);
                    $dmessage = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                    $dmessage = str_replace('{date_time}',$meeting_datetime,$dmessage);
                    $dmessage = str_replace('{status}',ucfirst($booking->status),$dmessage);
                    $dmessage = str_replace('{user_name}',$patient->firstname.' '.$patient->lastname,$dmessage);
                    $reponse = send_sms('+'.$doctor->phone,$dmessage); /* Send SMS */
                    if($reponse['status'] != 201)
                    {
                        $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                    }
                }
            }

            $save['id'] = $booking_id;
            $save['notify'] = 0;
            $this->Common_model->save_tbl('bookings',$save);

            /* Remove the scheduled chron from the queue table */
            $this->db->where('meeting_id',$booking->meeting_id)->delete('chron_email_queue');
        }
        return true;
    }

    function set_email_queue($booking_id,$meeting_datetime)
    {
        $booking = $this->db->where('id',$booking_id)->get('bookings')->row();
        /* Get Patient details */
        $patient = $this->Common_model->get_tbl_row('users',array('id'=>$booking->patient_id),'firstname,lastname,email,phone');
        /* Get Doctors details */
        $doctor = $this->Common_model->get_tbl_row('users',array('id'=>$booking->doctor_id),'id,firstname,lastname,email,phone');

        /* Set 10 min before the start time */
        // $remainder_time = date('Y-m-d H:i:s',($booking->start_datetime - (1 * 60 * 60)));
        $remainder_time = $booking->start_datetime-(10*60);
        // print_a($remainder_time,true);
        /* Fetch the remainder email template for meeting assigned to Doctor*/
        $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>2));
        /* Re-place the Subject content */
        $subject = str_replace('{meeting_id}',$booking->meeting_id,$row->subject);
        $subject = str_replace('{date_time}',$meeting_datetime,$subject);
        /* Re-place the Message content */
        $message = str_replace('{meeting_id}',$booking->meeting_id,$row->message);
        $message = str_replace('{status}',ucfirst($booking->status),$message);
        $message = str_replace('{date_time}',$meeting_datetime,$message);
        $message = str_replace('{company_name}',config_item('company_name'),$message);
        $message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$message);

        $cron['to_email'] = $doctor->email;
        $cron['from_email'] = $row->from_email;
        $cron['meeting_id'] = $booking->meeting_id;
        $cron['send_datetime'] = $remainder_time;
        $cron['subject'] = $subject;
        $cron['message'] = $message;
        $cron['queued'] = 1;
        $this->Common_model->save_tbl('chron_email_queue',$cron);

         /* Fetch the email template for new meeting assigned to Patient*/
         $p_row = $this->Common_model->get_tbl_row('email_templates',array('id'=>4));
         /* Re-place the Subject content */
         $p_subject = str_replace('{meeting_id}',$booking->meeting_id,$p_row->subject);
         $p_subject = str_replace('{date_time}',$meeting_datetime,$p_subject);
         /* Re-place the Message content */
         $p_message = str_replace('{meeting_id}',$booking->meeting_id,$p_row->message);
         $p_message = str_replace('{date_time}',$meeting_datetime,$p_message);
         $p_message = str_replace('{company_name}',config_item('company_name'),$p_message);
         $p_message = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$p_message);
         $p_message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$p_message);
         $receiver = $patient->email;

         $cron['to_email'] = $patient->email;
         $cron['from_email'] = $p_row->from_email;
         $cron['meeting_id'] = $booking->meeting_id;
         $cron['send_datetime'] = $remainder_time;
         $cron['subject'] = $p_subject;
         $cron['message'] = $p_message;
         $cron['queued'] = 1;
         $this->Common_model->save_tbl('chron_email_queue',$cron);

         /* Send Scheduled SMS */
         if(config_item('send_sms'))
         {
             $this->load->helper('sms');
            /* Check And Delete the SMS if already shcheduled */
             $this->check_del_oldbatch($booking->meeting_id);

             if(!empty($patient->phone))
             {   /* Schedule Customers SMS */
                 $sms_template = $this->Common_model->get_tbl_row('sms_templates',4);
                 $message = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                 $message = str_replace('{date_time}',$meeting_datetime,$message);
                 $message = str_replace('{status}',ucfirst($booking->status),$message);
                 $message = str_replace('{doctor_name}',$doctor->firstname.' '.$doctor->lastname,$message);
                 $reponse = send_scheduled_sms('+'.$patient->phone,$message,date('Y-m-d H:i:s',$remainder_time)); /* Send SMS */
                //  print_a($reponse,true);
                 if($reponse['status'])
                 {
                     $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                 }
             }
             if(!empty($doctor->phone))
             {
                 /* Notify the Doctor */
                 $sms_template = $this->Common_model->get_tbl_row('sms_templates',3);
                 $dmessage = str_replace('{meeting_id}',$booking->meeting_id,$sms_template->message);
                 $dmessage = str_replace('{date_time}',$meeting_datetime,$dmessage);
                 $dmessage = str_replace('{status}',ucfirst($booking->status),$dmessage);
                 $dmessage = str_replace('{patient_name}',$patient->firstname.' '.$patient->lastname,$dmessage);
                 $reponse = send_scheduled_sms('+'.$doctor->phone,$dmessage,date('Y-m-d H:i:s',$remainder_time)); /* Send SMS */
                 if($reponse['status'])
                 {
                     $this->store_batchalert_noti($booking->meeting_id,$reponse['status'],$reponse['result']);
                 }
             }
         }

         
    }

    function set_immediate_im_email_queue($booking_id,$meeting_datetime,$to_email,$pivot_id)
    {
        $booking = $this->db->where('id',$booking_id)->get('bookings')->row();

        $patient_meeting_link = site_url('immediate_conference/'.$booking->meeting_id);
        $mail = explode('@',$to_email);
        /* Fetch the group email template for meeting assigned to Users */
        $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>11));
        /* Re-place the Subject content */
        $subject = str_replace('{meeting_id}',$booking->meeting_id,$row->subject);
        $subject = str_replace('{date_time}',$meeting_datetime,$subject);
        /* Re-place the Message content */
        $message = str_replace('{meeting_id}',$booking->meeting_id,$row->message);
        $message = str_replace('{status}',ucfirst($booking->status),$message);
        $message = str_replace('{date_time}',$meeting_datetime,$message);
        $message = str_replace('{company_name}',config_item('company_name'),$message);
        $message = str_replace('{web_link}',$patient_meeting_link,$message);
        $message = str_replace('{user}',$mail[0],$message);

        $cron['to_email'] = $to_email;
        $cron['from_email'] = $row->from_email;
        $cron['meeting_id'] = $booking->meeting_id;
        $cron['send_datetime'] = time();
        $cron['subject'] = $subject;
        $cron['message'] = $message;
        $cron['queued'] = 1;
        $cron['immediate_email'] = 1;
        $cron['pivot_id'] = $pivot_id;
        $this->Common_model->save_tbl('chron_email_queue',$cron);

        return true;
    }

    function set_immediate_group_email_queue($booking_id,$meeting_datetime,$to_email,$pivot_id,$token)
    {
        $booking = $this->db->where('id',$booking_id)->get('bookings')->row();

        $patient_meeting_link = site_url('group_conference/'.$token.'/'.$booking->meeting_id);
        $mail = explode('@',$to_email);
        /* Fetch the group email template for meeting assigned to Users */
        $row = $this->Common_model->get_tbl_row('email_templates',array('id'=>10));
        /* Re-place the Subject content */
        $subject = str_replace('{meeting_id}',$booking->meeting_id,$row->subject);
        $subject = str_replace('{date_time}',$meeting_datetime,$subject);
        /* Re-place the Message content */
        $message = str_replace('{meeting_id}',$booking->meeting_id,$row->message);
        $message = str_replace('{status}',ucfirst($booking->status),$message);
        $message = str_replace('{date_time}',$meeting_datetime,$message);
        $message = str_replace('{company_name}',config_item('company_name'),$message);
        $message = str_replace('{web_link}',$patient_meeting_link,$message);
        $message = str_replace('{user}',$mail[0],$message);

        $cron['to_email'] = $to_email;
        $cron['from_email'] = $row->from_email;
        $cron['meeting_id'] = $booking->meeting_id;
        $cron['send_datetime'] = time();
        $cron['subject'] = $subject;
        $cron['message'] = $message;
        $cron['queued'] = 1;
        $cron['immediate_email'] = 1;
        $cron['pivot_id'] = $pivot_id;
        $this->Common_model->save_tbl('chron_email_queue',$cron);

        return true;
    }

    /* Check the old batch id for the rescheduled meeting or delete meeting and stop sending the Scheduled SMS. */
    function check_del_oldbatch($meeting_id)
    {
        $this->db->where('meeting_id',$meeting_id);
        $results = $this->db->get('sms_notification_alert')->result();

        if(!empty($results))
        {
            $this->load->helper('sms');
            foreach($results as $result)
            {
                if($result->batch_id != NULL)
                {
                    $result = delete_scheduled_sms($result->batch_id);
                    if($result['status'] == 204)
                    {
                        $this->db->where('id',$result->id)->delete('sms_notification_alert');
                    }
                }
            }
        }
        
    }


    /* Behandlare panel methods starts here */
    function get_todays_meeting($doc_id = false, $patient_id=false)
    {

        $this->db->select('bookings.*,CONCAT(doc.firstname," ",doc.lastname) as doc_fullname,CONCAT(pat.firstname," ",pat.lastname) as pat_fullname,pat.email as pat_email,doc.email as doc_email');
        if($doc_id)
        $this->db->where('bookings.doctor_id',$doc_id);
        if($patient_id)
        $this->db->where('bookings.patient_id',$patient_id);
        $this->db->where_not_in('bookings.status',array('cancelled','completed'));
        $this->db->where('booking_date',date('Y-m-d'));
        $this->db->join('users as doc','doc.id = bookings.doctor_id');
        $this->db->join('users as pat','pat.id = bookings.patient_id');
        return $this->db->get('bookings')->result();
    }

    function get_user_meetings($doc_id =false,$patient_id=false)
    {
        $this->db->select('bookings.*,CONCAT(doc.firstname," ",doc.lastname) as doc_fullname,CONCAT(pat.firstname," ",pat.lastname) as pat_fullname,pat.email as pat_email,doc.email as doc_email');
        if($doc_id)
        $this->db->where('bookings.doctor_id',$doc_id);
        if($patient_id)
        $this->db->where('bookings.patient_id',$patient_id);
        $this->db->join('users as doc','doc.id = bookings.doctor_id');
        $this->db->join('users as pat','pat.id = bookings.patient_id');
        $this->db->limit(10);
        $this->db->order_by('bookings.booking_date','DESC');
        return $this->db->get('bookings')->result();
    }
    
    function get_upcoming_meeting($doc_id = false, $patient_id=false,$limit=false,$offset=false)
    {
        
        $this->db->select('bookings.*,CONCAT(doc.firstname," ",doc.lastname) as doc_fullname,CONCAT(pat.firstname," ",pat.lastname) as pat_fullname,pat.email as pat_email,doc.email as doc_email');
        if($doc_id)
        $this->db->where('bookings.doctor_id',$doc_id);
        if($patient_id)
        $this->db->where('bookings.patient_id',$patient_id);
        $this->db->where_not_in('bookings.status',array('cancelled','completed','missed'));
        $this->db->where('booking_date >=',date('Y-m-d'));
        $this->db->where('end_datetime >',time());
        $this->db->join('users as doc','doc.id = bookings.doctor_id');
        $this->db->join('users as pat','pat.id = bookings.patient_id');
        if($limit)
        $this->db->limit($limit,$offset);
        $this->db->order_by('bookings.booking_date','ASC');
        return $this->db->get('bookings')->result();
    }

    function allNotes_count()
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
        $this->db->join('bookings','bookings.meeting_id = notes.meeting_id');
        $query = $this->db->get('notes');
        return $query->num_rows();  

    }

    
    function allNotes($limit,$start,$col,$dir)
    {   
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
       $query = $this->db->select('notes.*,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,bookings.booking_date')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->join('bookings','bookings.meeting_id = notes.meeting_id')
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id')
                ->get('notes');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function notes_search($limit,$start,$search,$col,$dir)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
        $query = $this
                ->db->select('notes.*,bookings.booking_date,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname')
                ->group_start()
                ->like('notes.notes',$search)
                ->or_like('notes.meeting_id',$search)
                ->or_like('bookings.booking_date',$search)
                ->or_like('CONCAT(u1.firstname," ",u1.lastname)',$search)
                ->or_like('CONCAT(u2.firstname," ",u2.lastname)',$search)
                ->group_end()
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->join('bookings','bookings.meeting_id = notes.meeting_id')
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id')
                ->get('notes');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function notes_search_count($search)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
        $query = $this
                ->db->select('notes.*,bookings.booking_date,CONCAT(u1.firstname," ",u1.lastname) as doctor_fullname,CONCAT(u2.firstname," ",u2.lastname) as patient_fullname')
                ->group_start()
                ->like('bookings.id',$search)
                ->or_like('notes.meeting_id',$search)
                ->or_like('bookings.booking_date',$search)
                ->or_like('CONCAT(u2.firstname," ",u2.lastname)',$search)
                ->or_like('CONCAT(u1.firstname," ",u1.lastname)',$search)
                ->group_end()
                ->join('bookings','bookings.meeting_id = notes.meeting_id')
                ->join('users as u1','bookings.doctor_id = u1.id')
                ->join('users as u2','bookings.patient_id = u2.id')
                ->get('notes');
    
        return $query->num_rows();
    } 

    function get_most_booked_docs()
    {
        $this->db->select('count(*),MIN(users.id) as id,MIN(users.firstname) as firstname,MIN(users.lastname) as lastname,MIN(users.personal_id) as personal_id');
        $this->db->where('type',1);
        $this->db->group_by('users.id');
        $this->db->order_by('count(doctor_id)','DESC');
        $this->db->join('bookings','bookings.doctor_id = users.id','left outer');    
        $result = $this->db->get('users')->result();

        // print_a($result,true);
        $return = array(''=>'-- Select --');
        foreach($result as $result)
        {
            $return[$result->id] = $result->firstname.' '.$result->lastname.' - '.$result->personal_id;
        }

        return $return;
    }


    function allNotes_log_count($id)
    {
        $this->db->where('note_id',$id);
        $query = $this->db->get('notes_viewedby');
        return $query->num_rows();  

    }

    function allNotes_log($limit,$start,$col,$dir,$id)
    {   
        $this->db->where('note_id',$id);
       $query = $this->db->select('notes_viewedby.*,admin.fullname')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->join('admin','notes_viewedby.admin_id = admin.id')
                ->get('notes_viewedby');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function notes_log_search($limit,$start,$search,$col,$dir,$id)
    {
        $this->db->where('note_id',$id);
        $query = $this
                ->db->select('notes_viewedby.*,admin.fullname')
                ->group_start()
                ->like('notes_viewedby.added_date',$search)
                ->or_like('admin.fullname',$search)
                ->group_end()
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->join('admin','notes_viewedby.admin_id = admin.id')
                ->get('notes_viewedby');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function notes_log_search_count($search,$id)
    {
        $this->db->where('note_id',$id);
        $query = $this
                ->db->select('notes_viewedby.*,admin.fullname')
                ->group_start()
                ->like('notes_viewedby.added_date',$search)
                ->or_like('admin.fullname',$search)
                ->group_end()
                ->join('admin','notes_viewedby.admin_id = admin.id')
                ->get('notes_viewedby');
    
        return $query->num_rows();
    } 

    function allPatient_count()
    {
        $this->db->where('type',2);
        $query = $this->db->get('users');
        return $query->num_rows();  
    }

    
    function allPatient($limit,$start,$col,$dir)
    {   
        $this->db->where('type',2);
       $query = $this->db->select('id,firstname,lastname,email,phone,personal_id')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('users');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function patient_search($limit,$start,$search,$col,$dir)
    {
        
        $query = $this
                ->db->select('id,firstname,lastname,email,phone,personal_id')
                ->group_start()
                ->like('firstname',$search)
                ->or_like('lastname',$search)
                ->or_like('email',$search)
                ->or_like('phone',$search)
                ->or_like('personal_id',$search)
                ->group_end()
                ->where('type',2)
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('users');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function patient_search_count($search)
    {
        
        $query = $this
                ->db->select('id,firstname,lastname,email,phone,personal_id')
                ->group_start()
                ->like('firstname',$search)
                ->or_like('lastname',$search)
                ->or_like('email',$search)
                ->or_like('phone',$search)
                ->or_like('personal_id',$search)
                ->group_end()
                ->where('type',2)
                ->get('users');
    
        return $query->num_rows();
    }

    /* Immediate Meetings Model Starts here */
    function get_meeting_detail($booking_id)
    {
        $this->db->where('meeting_id',$booking_id);
        return $this->db->get('bookings')->row();
    }

}
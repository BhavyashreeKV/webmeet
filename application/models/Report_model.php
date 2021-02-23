<?php 
class Report_model extends CI_Model
{
    function get_bookers_reports()
    {
        $return = array();
        $this->db->select('MIN(fullname) as fullname,COUNT(bookings.id) as total,MIN(admin.id) as id');
        $this->db->like('privilege','booker');
        $this->db->join('bookings','bookings.created_by = admin.id');
        $this->db->where('created_by_type','admin');
        $result = $this->db->get('admin')->result();
        foreach($result as $re)
        {
            $re->last30days = $this->get_last_thirtydaysreport($re->id);
            $re->last7days = $this->get_last_sevendaysreport($re->id);
            $return[] = $re;
        }

        return $return;
    }


    function get_last_thirtydaysreport($admin_id)
    {
        $this->db->where('admin.id',$admin_id);
        $this->db->where('created_by_type','admin');
        $this->db->where('booking_date >=',date('Y-m-d',strtotime('-30 days')));
        $this->db->join('bookings','bookings.created_by = admin.id');
        return $this->db->get('admin')->num_rows();

    }

    function get_last_sevendaysreport($admin_id)
    {
        $this->db->where('admin.id',$admin_id);
        $this->db->where('created_by_type','admin');
        $this->db->where('booking_date >=',date('Y-m-d',strtotime('-7 days')));
        $this->db->join('bookings','bookings.created_by = admin.id');
        return $this->db->get('admin')->num_rows();

    }

    function get_monthlyMeetings($status=false)
    {
        $return = array();

        $month_range = range(1,date('n'));
        foreach($month_range as $k=>$range)
        {
            $return[$k]['label'] = date('F',strtotime(date('Y-'.(int)$range.'-01')));

            $return[$k]['all_meetings'] = $this->get_meetingCnt_monthly(false,$range);
            $return[$k]['completed'] = $this->get_meetingCnt_monthly('completed',$range);
            $return[$k]['missed'] = $this->get_meetingCnt_monthly('missed',$range);
            $return[$k]['cancelled'] = $this->get_meetingCnt_monthly('cancelled',$range);
            $return[$k]['upcoming'] = $this->get_meetingCnt_monthly('upcoming',$range);

        }

        return $return;
    }

    
    function get_meetingCnt_monthly($status = false,$month=false)
    {
        if($status && $status !='upcoming')
        $this->db->where('status',$status);
        
        if($status && $status == 'upcoming')
        $this->db->where('booking_date >=',date('Y-m-d'))->where('end_datetime >=',time());
        
        if($month)
        $this->db->where('Month(booking_date)',date('m',strtotime(date('Y-'.$month.'-01'))));
        return $this->db->count_all_results('bookings');
    }
    
    function get_weekly_meetings($start_date,$end_date)
    {
        $i=0;
        $label = $all_meeting = $completed = $missed = $cancelled = array();
        while (strtotime($start_date) <= strtotime($end_date)) {
            // echo "$start_date\n";
            $current_label = date('d-M-Y',strtotime($start_date));
            $call_meetings = $this->get_meetingCnt_day(false,$start_date);
            $ccompleted = $this->get_meetingCnt_day('completed',$start_date);
            $cmissed = $this->get_meetingCnt_day('missed',$start_date);
            $ccancelled = $this->get_meetingCnt_day('cancelled',$start_date);

            array_push($label,$current_label);
            array_push($all_meeting,$call_meetings);
            array_push($completed,$ccompleted);
            array_push($missed,$cmissed);
            array_push($cancelled,$ccancelled);

           $i++; $start_date = date ("Y-m-d", strtotime("+1 day", strtotime($start_date)));
        }

        $return['label'] = $label;
        $return['all_meeting'] = $all_meeting;
        $return['completed'] = $completed;
        $return['missed'] = $missed;
        $return['cancelled'] = $cancelled;

        return $return;
    }

    function get_meetingCnt_day($status = false,$date = false)
    {
        if($status)
        $this->db->where('status',$status);

        if($date)
        $this->db->where('booking_date',date('Y-m-d',strtotime($date)));
        return $this->db->count_all_results('bookings');
    
    }
    
    function get_allpatients_meetingCnt($perpage=false,$page=false,$count=false)
    {
        $this->db->select('COUNT(bookings.id) as total_meetings,CONCAT(MIN(users.firstname)," ",MIN(users.lastname)) as fullname,bookings.patient_id as user_id');
        $this->db->join('users','users.id = bookings.patient_id');
        $this->db->group_by('bookings.patient_id');
        $this->db->order_by('total_meetings','DESC');
        $this->db->order_by('users.firstname','ASC');
        if($perpage)
        {
            $this->db->limit($perpage,$page);
        }
        if($count)
        return $this->db->count_all_results('bookings');

        return $this->db->get('bookings')->result();
    }

    /* Get the Patient's status wise meetings counts for graphs */
    function get_ps_meetings($user_id)
    {
            $return['all_meetings'] = $this->get_pat_meetingCnts(false,$user_id);
            $return['completed'] = $this->get_pat_meetingCnts('completed',$user_id);
            $return['missed'] = $this->get_pat_meetingCnts('missed',$user_id);
            $return['cancelled'] = $this->get_pat_meetingCnts('cancelled',$user_id);
            $return['upcoming'] = $this->get_pat_meetingCnts('upcoming',$user_id);
            return $return;
    }

    function get_pat_meetingCnts($status=false,$user_id)
    {
        if($status && $status !='upcoming')
        $this->db->where('status',$status);
        
        if($status && $status == 'upcoming')
        $this->db->where('booking_date >=',date('Y-m-d'))->where('end_datetime >=',time());
        
        $this->db->where('patient_id',$user_id);
        return $this->db->count_all_results('bookings');
    }

    function get_individual_pmeetings($user_id,$count=false,$perpage=false,$offset=false)
    {
        $this->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as pat_fullname,CONCAT(u2.firstname," ",u2.lastname) as doc_fullname,FROM_UNIXTIME(bookings.start_datetime,"%H:%i %p") as starttime,FROM_UNIXTIME(bookings.end_datetime,"%H:%i %p") as endtime');
        $this->db->where('patient_id',$user_id);
        
        if($this->input->get('start_date') && $this->input->get('end_date'))
        {
            $this->db->where('booking_date >',date('Y-m-d',strtotime($this->input->get('start_date'))));
            $this->db->where('booking_date <',date('Y-m-d',strtotime($this->input->get('end_date').' +1 day')));
        }
        else
        {
            $this->db->where('bookings.booking_date >',date('Y-m-d',strtotime('-30 days')));
        }
        if($this->input->get('st'))
        {
            $this->db->where('bookings.status',$_GET['st']);
        }
        if($this->input->get('dt'))
        {
            $this->db->where('bookings.doctor_id',$_GET['dt']);
        }
        $this->db->join('users as u1','bookings.patient_id = u1.id');
        $this->db->join('users as u2','bookings.doctor_id = u2.id');
        $this->db->where_in('bookings.status',array('new','rescheduled','missed','completed'));
        $this->db->order_by('bookings.booking_date','DESC');
        if($perpage)
        $this->db->limit($perpage,$offset);
        if($count)
        return $this->db->count_all_results('bookings');
        
        return $this->db->get('bookings')->result();

    }

    function get_alldoctors_meetingCnt($perpage=false,$page=false,$count=false)
    {
        $this->db->select('COUNT(bookings.id) as total_meetings,CONCAT(MIN(users.firstname)," ",MIN(users.lastname)) as fullname,bookings.doctor_id as user_id');
        $this->db->join('users','users.id = bookings.doctor_id');
        $this->db->group_by('bookings.doctor_id');
        $this->db->order_by('total_meetings','DESC');
        $this->db->order_by('users.firstname','ASC');
        if($perpage)
        {
            $this->db->limit($perpage,$page);
        }
        if($count)
        return $this->db->count_all_results('bookings');

        return $this->db->get('bookings')->result();
    }

    function get_ds_meetings($user_id)
    {
            $return['all_meetings'] = $this->get_doc_meetingCnts(false,$user_id);
            $return['completed'] = $this->get_doc_meetingCnts('completed',$user_id);
            $return['missed'] = $this->get_doc_meetingCnts('missed',$user_id);
            $return['cancelled'] = $this->get_doc_meetingCnts('cancelled',$user_id);
            $return['upcoming'] = $this->get_doc_meetingCnts('upcoming',$user_id);
            return $return;
    }

    function get_doc_meetingCnts($status=false,$user_id)
    {
        if($status && $status !='upcoming')
        $this->db->where('status',$status);
        
        if($status && $status == 'upcoming')
        $this->db->where('booking_date >=',date('Y-m-d'))->where('end_datetime >=',time());
        
        $this->db->where('doctor_id',$user_id);
        return $this->db->count_all_results('bookings');
    }

    function get_individual_dmeetings($user_id,$count=false,$perpage=false,$offset=false)
    {
        $this->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as pat_fullname,CONCAT(u2.firstname," ",u2.lastname) as doc_fullname,FROM_UNIXTIME(bookings.start_datetime,"%H:%i %p") as starttime,FROM_UNIXTIME(bookings.end_datetime,"%H:%i %p") as endtime');
        $this->db->where('doctor_id',$user_id);
        
        if($this->input->get('start_date') && $this->input->get('end_date'))
        {
            $this->db->where('booking_date >',date('Y-m-d',strtotime($this->input->get('start_date'))));
            $this->db->where('booking_date <',date('Y-m-d',strtotime($this->input->get('end_date').' +1 day')));
        }
        else
        {
            $this->db->where('bookings.booking_date >',date('Y-m-d',strtotime('-30 days')));
        }
        if($this->input->get('st'))
        {
            $this->db->where('bookings.status',$_GET['st']);
        }
        if($this->input->get('pt'))
        {
            $this->db->where('bookings.patient_id',$_GET['pt']);
        }
        $this->db->join('users as u1','bookings.patient_id = u1.id');
        $this->db->join('users as u2','bookings.doctor_id = u2.id');
        $this->db->where_in('bookings.status',array('new','rescheduled','missed','completed'));
        $this->db->order_by('bookings.booking_date','DESC');
        if($perpage)
        $this->db->limit($perpage,$offset);
        if($count)
        return $this->db->count_all_results('bookings');
        
        return $this->db->get('bookings')->result();

    }

    function get_docOwnMeeting($user_id,$count=false,$perpage=false,$offset=false)
    {
        $this->db->select('bookings.*,CONCAT(u1.firstname," ",u1.lastname) as pat_fullname,FROM_UNIXTIME(bookings.start_datetime,"%H:%i %p") as starttime,FROM_UNIXTIME(bookings.end_datetime,"%H:%i %p") as endtime');
        if($this->input->get('start_date') && $this->input->get('end_date'))
        {
            $this->db->where('booking_date >',date('Y-m-d',strtotime($this->input->get('start_date'))));
            $this->db->where('booking_date <',date('Y-m-d',strtotime($this->input->get('end_date').' +1 day')));
        }
        else
        {
            $this->db->where('bookings.booking_date >',date('Y-m-d',strtotime('-30 days')));
        }
        if($this->input->get('st'))
        {
            $this->db->where('bookings.status',$_GET['st']);
        }
        $this->db->where('created_by_type','behandlare');
        $this->db->where('created_by',$user_id);
        $this->db->join('users as u1','bookings.patient_id = u1.id');   
        $this->db->order_by('bookings.booking_date','DESC');
        if($perpage)
        $this->db->limit($perpage,$offset);
        if($count)
        return $this->db->count_all_results('bookings');
        
        return $this->db->get('bookings')->result();
    }

    function get_docOwnMeetingCard($user_id)
    {
        $where = array('created_by_type'=>'behandlare','created_by'=>$user_id);
        if($this->input->get('start_date') && $this->input->get('end_date'))
        {
            $date_array = array('booking_date >' => date('Y-m-d',strtotime($this->input->get('start_date'))),'booking_date <' => date('Y-m-d',strtotime($this->input->get('end_date').' +1 day')));
            $where = array_merge($where,$date_array);
        }
        else
        {
            $date_array = array('bookings.booking_date >'=> date('Y-m-d',strtotime('-30 days')));
            $where = array_merge($where,$date_array);
        }

        $return['total_meetings'] = $this->db->where($where)->count_all_results('bookings');
        $return['cancelled'] = $this->db->where($where)->where('status','cancelled')->count_all_results('bookings');
        $return['missed'] = $this->db->where($where)->where('status','missed')->count_all_results('bookings');
        $return['completed'] = $this->db->where($where)->where('status','completed')->count_all_results('bookings');

        return $return;
    }

    function same_meetings()
    {
        $this->db->select('count(bookings.id) as total_meeting,MIN(doctor_id),MIN(patient_id),MIN(CONCAT(u1.firstname," ",u1.lastname)) as doc_fullname,MIN(CONCAT(u2.firstname," ",u2.lastname)) as pat_fullname');
        $this->db->where('bookings.status','completed');
        $this->db->join('users as u1','bookings.doctor_id = u1.id');
        $this->db->join('users as u2','bookings.patient_id = u2.id');
        $this->db->where('bookings.booking_date >',date('Y-m-d',strtotime('-90 days')));
        $this->db->order_by('count(bookings.id)','DESC');
        return $this->db->group_by('bookings.doctor_id')->group_by('bookings.patient_id')->get('bookings')->result();
    }
}
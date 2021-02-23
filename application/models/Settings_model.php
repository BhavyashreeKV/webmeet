<?php
class Settings_model extends CI_Model
{
    function  get_queue_tbl($time = false,$duration=(10*60))
    {
        $this->db->where('queued',1);
        $this->db->where('send',0);
        if($time)
        {
            $this->db->where('send_datetime >',$time);
            $this->db->where('send_datetime <=',$time + $duration);
            // $this->db->where('send_datetime <=',$time + (10 * 60));
        }
        $result = $this->db->get('chron_email_queue')->result();
        return $result;
       /*  print_last_query();*/
        // print_a($result); 
        
    }

    function get_missed_meetings()
    {
        $this->db->group_start();
            $this->db->where('ov_sess',NULL);
            $this->db->or_where('doc_sess',0);
            $this->db->or_where('pat_sess',0);
        $this->db->group_end();
        $this->db->where('start_datetime <',(time()));
        $this->db->where('end_datetime <',(time()));
        $this->db->where_in('status',array('new','rescheduled'));
        return $this->db->get('bookings')->result();
    }

    function get_old_queue()
    {
        $this->db->where('send_datetime <=',strtotime('-1 days'));
        return $this->db->get('chron_email_queue')->result();
    }

    function get_unsend_email()
    {
        $time = time();
        $this->db->where('queued',1);
        $this->db->where('immediate_email',1);
        $this->db->where('send',0);
        $this->db->where('send_attempt < ',4);
        if($time)
        {
            $this->db->where('send_datetime <',$time);
            // $this->db->where('send_datetime <=',$time + $duration);
            // $this->db->where('send_datetime <=',$time + (10 * 60));
        }
        $result = $this->db->get('chron_email_queue')->result();
        return $result;
    }
}
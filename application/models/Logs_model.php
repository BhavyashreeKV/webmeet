<?php 
class Logs_model extends CI_Model
{

    function emailLogs_count()
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('from')))
        {
            $this->db->where('email_logs.from',$this->input->post('from'));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('email_logs.to',$this->input->post('to'));
        }
        $query = $this->db->get('email_logs');
        return $query->num_rows();  

    }
    function emailLogs_countlim($limit,$start,$col,$dir)
    {   
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('from')))
        {
            $this->db->where('email_logs.from',$this->input->post('from'));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('email_logs.to',$this->input->post('to'));
        }
       $query = $this->db->select('*')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('email_logs');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function emaillogs_search($limit,$start,$search,$col,$dir)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('from')))
        {
            $this->db->where('email_logs.from',$this->input->post('from'));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('email_logs.to',$this->input->post('to'));
        }
        $query = $this
                ->db->select('*')
                ->group_start()
                ->like('email_logs.added_date',$search)
                ->or_like('email_logs.from',$search)
                ->or_like('email_logs.to',$search)
                ->group_end()
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('email_logs');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function emaillogs_search_count($search)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('from')))
        {
            $this->db->where('email_logs.from',$this->input->post('from'));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('email_logs.to',$this->input->post('to'));
        }
        $query = $this
                ->db->select('*')
                ->group_start()
                ->like('email_logs.added_date',$search)
                ->or_like('email_logs.from',$search)
                ->or_like('email_logs.to',$search)
                ->group_end()
                ->get('email_logs');
    
        return $query->num_rows();
    }
    /*======sms logs start=======*/
    function smsLogs_count()
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('sms_notification_logs.to',$this->input->post('to'));
        }
        $query = $this->db->get('sms_notification_logs');
        return $query->num_rows();  

    }
    function smsLogs_countlim($limit,$start,$col,$dir)
    {   
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('sms_notification_logs.to',$this->input->post('to'));
        }
       $query = $this->db->select('*')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('sms_notification_logs');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function smslogs_search($limit,$start,$search,$col,$dir)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('sms_notification_logs.to',$this->input->post('to'));
        }
        $query = $this
                ->db->select('*')
                ->group_start()
                ->like('sms_notification_logs.added_date',$search)
                ->or_like('sms_notification_logs.from',$search)
                ->or_like('sms_notification_logs.to',$search)
                ->group_end()
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('sms_notification_logs');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function smslogs_search_count($search)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('to')))
        {
            $this->db->where('sms_notification_logs.id',$this->input->post('to'));
        }
        $query = $this
                ->db->select('*')
                ->group_start()
                ->like('sms_notification_logs.added_date',$search)
                ->or_like('sms_notification_logs.from',$search)
                ->or_like('sms_notification_logs.to',$search)
                ->group_end()
                ->get('sms_notification_logs');
    
        return $query->num_rows();
    }

    /*============== banauth_logs start===========*/
    function bankauthLogs_count()
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('personal_id')))
        {
            $this->db->where('bankauth_log.personal_id',$this->input->post('personal_id'));
        }
        if(!empty($this->input->post('status')))
        {
            $this->db->where('bankauth_log.status',$this->input->post('status'));
        }
        $query = $this->db->get('bankauth_log');
        return $query->num_rows();  

    }
    function bankauthLogs_countlim($limit,$start,$col,$dir)
    {   
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('personal_id')))
        {
            $this->db->where('bankauth_log.personal_id',$this->input->post('personal_id'));
        }
        if(!empty($this->input->post('status')))
        {
            $this->db->where('bankauth_log.status',$this->input->post('status'));
        }
       $query = $this->db->select('*')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('bankauth_log');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
        
    }

    function bankauthlogs_search($limit,$start,$search,$col,$dir)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('personal_id')))
        {
            $this->db->where('bankauth_log.personal_id',$this->input->post('personal_id'));
        }
        if(!empty($this->input->post('status')))
        {
            $this->db->where('bankauth_log.status',$this->input->post('status'));
        }
        $query = $this
                ->db->select('*')
                ->group_start()
                ->like('bankauth_log.added_date',$search)
                ->or_like('bankauth_log.personal_id',$search)
                ->or_like('bankauth_log.status',$search)
                ->group_end()
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->get('bankauth_log');
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function bankauthlogs_search_count($search)
    {
        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('added_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('added_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if(!empty($this->input->post('personal_id')))
        {
            $this->db->where('bankauth_log.personal_id',$this->input->post('personal_id'));
        }
        if(!empty($this->input->post('status')))
        {
            $this->db->where('bankauth_log.status',$this->input->post('status'));
        }
        $query = $this
                ->db->select('*')
                ->group_start()
                ->like('bankauth_log.added_date',$search)
                ->or_like('bankauth_log.personal_id',$search)
                ->or_like('bankauth_log.status',$search)
                ->group_end()
                ->get('bankauth_log');
    
        return $query->num_rows();
    }

}
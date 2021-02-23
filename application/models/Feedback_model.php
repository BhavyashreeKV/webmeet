<?php 
class Feedback_model extends CI_Model
{
    function allFeedback_count()
    {
        return $this->db->get('notes')->num_rows();
    }

    function allFeedback($type,$limit,$start,$col,$dir)
    {
        $query = $this->db->select('ratings.*,bookings.meeting_id,bookings.booking_date,CONCAT(users.firstname," ",users.lastname) as fullname')
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->join('bookings','bookings.id = ratings.booking_id')
                ->join('users','ratings.user_id = users.id')
                ->where('ratings.type',$type)
                ->get('ratings');
        
        if($query->num_rows()>0)
        {
            return $query->result(); 
        }
        else
        {
            return null;
        }
    }

    function feedback_search($type,$limit,$start,$search,$col,$dir)
    {
        $query = $this
                ->db->select('ratings.*,bookings.meeting_id,bookings.booking_date,CONCAT(users.firstname," ",users.lastname) as fullname')
                ->group_start()
                ->like('ratings.review',$search)
                ->or_like('CONCAT(users.firstname," ",users.lastname)',$search)
                ->or_like('bookings.booking_date',$search)
                ->or_like('bookings.meeting_id',$search)
                ->group_end()
                ->limit($limit,$start)
                ->order_by($col,$dir)
                ->where('ratings.type',$type)
                ->join('bookings','bookings.id = ratings.booking_id')
                ->join('users','ratings.user_id = users.id')
                ->get('ratings');
        
       
        if($query->num_rows()>0)
        {
            return $query->result();  
        }
        else
        {
            return null;
        }
    }

    function feedback_search_count($type,$search)
    {
        $query = $this
                ->db->select('ratings.*,bookings.meeting_id,bookings.booking_date,CONCAT(users.firstname," ",users.lastname) as fullname')
                ->group_start()
                ->like('ratings.review',$search)
                ->or_like('CONCAT(users.firstname," ",users.lastname)',$search)
                ->or_like('bookings.booking_date',$search)
                ->or_like('bookings.meeting_id',$search)
                ->group_end()
                ->where('ratings.type',$type)
                ->join('bookings','bookings.id = ratings.booking_id')
                ->join('users','ratings.user_id = users.id')
                ->get('ratings');
    
        return $query->num_rows();
    } 
}
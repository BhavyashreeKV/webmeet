<?php 
/* Created on 10-7-2019
* Author : E.P.vasudevan
 */
class Common_model extends CI_model
{
    /* -----------------------------------------
    * Save the data to any table if unique id is 
    * there update the record
    -----------------------------------------*/
    function save_tbl($tbl,$data)
    {
        if(isset($data['id']) && $data['id']!='')
        {
            $this->db->where('id',$data['id'])->update($tbl,$data);
            return $data['id'];
        } else {
            $this->db->insert($tbl,$data);
            return $this->db->insert_id();
        }
    }

    /*  Get the data in list format
    ********************************************************************
    *    use "Sel" variable to select, can use where, or where, where in
    *    like, Or like, group, order by, limit, offset
    *    join with 1 table only, use type as object array or array
    *******************************************************************/

    function get_tbl_list($tbl, $data = array(),$typ='obj')
    {
        if (!empty($data['sel'])) {
            $this->db->select($data['sel']);
        }
        if(!empty($data['where_in_key']) && !empty($data['where_in_value']))
        {
            $this->db->where_in($data['where_in_key'],$data['where_in_value']);
        }
        if(!empty($data['where']) && !empty($data['or_where'])){ $this->db->group_start(); }
        if (!empty($data['where'])) {
            $this->db->where($data['where']);
        }
        if (!empty($data['or_where'])) {
            $this->db->or_where($data['or_where']);
        }
        if(!empty($data['where']) && !empty($data['or_where'])){ $this->db->group_end(); }
        if(!empty($data['like']) && !empty($data['or_like'])){ $this->db->group_start(); }
        if (!empty($data['like'])) {
            $this->db->like($data['like']);
        }
        if (!empty($data['or_like'])) {
            $this->db->or_like($data['or_like']);
        }
        if(!empty($data['like']) && !empty($data['or_like'])){ $this->db->group_end(); }
        if (!empty($data['order_by'])) {
            $this->db->order_by($data['order_by']);
        }
        if(!empty($data['join']) && !empty($data['join_table']))
        {
            $this->db->join($data['join_table'],$data['join']);
        }
        if (!empty($data['perpage'])) {
            $this->db->limit($data['perpage']);
        }
        if (!empty($data['page'])) {
            $this->db->offset($data['page']);
        }
        if (!empty($data['count'])) {
            return $this->db->count_all_results($tbl);
        } else {
            if ($typ == 'csv') {
                $this->load->dbutil();
                $csv = $this->db->get_compiled_select($tbl);
                $query = $this->db->query($csv);
                return $this->dbutil->csv_from_result($query);
            } else {
                if ($typ == 'obj') {
                    return $this->db->get($tbl)->result();
                } else {
                    return $this->db->get($tbl)->result_array();
                }
            }
        }
    }

    /* 
    -------------------------------------
    *  Get table row in array or object
    *  returns single row data 
    ---------------------------------------*/
    function get_tbl_row($tbl,$id,$sel='*',$order_by='',$typ='obj')
    {
        $this->db->select($sel);
        if(is_array($id)) {
            $this->db->where($id);
        }else {
            $this->db->where('id',$id);
        }
        if (!empty($order_by)) {
            $this->db->order_by($order_by);
        }
        if($typ=='obj') {
            return $this->db->get($tbl)->row();
        } else {
            return $this->db->get($tbl)->row_array();
        }
    }

    /* --------------------------
    Delete table row fileds or field
    ------------------------------------ */
    function delete_tbl($tbl,$arr)
    {
        if (!empty($arr['where'])) {
            $this->db->where($arr['where']);
        }
        $this->db->delete($tbl);
    }
    
    /*--------------------------- 
    *Get the tbl fields in array key value as empty
     ----------------------------------------------*/
    function get_tbl_fields($tbl)
    {
        $data = $this->db->list_fields($tbl);
        return array_fill_keys($data, '');
    }

    /* check the table with number of rows */
    function check_data($table = 'users',$data=array())
    {
        if (!empty($data['where'])) {
            $this->db->where($data['where']);
        }
        $rows = $this->db->get($table)->num_rows();
        if($rows > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*--------------------------- 
    *Get the tbl fields in array key value pair
     ----------------------------------------------*/
     function get_keyvalue_tbl($tbl, $data = array(),$select='*')
    {
        $this->db->select($select);
        if(!empty($data['order_by'])) {
            $this->db->order_by($data['order_by']);
        }
        if (!empty($data['where'])) {
            $this->db->where($data['where']);
        }
        $key = $data['key'];
        $value = $data['value'];
        $result = $this->db->get($tbl)->result();
        $return = array();
        
        if (!empty($key) && !empty($value)) {
            foreach ($result as $item) {
                $return[$item->$key] = $item->$value;
            }
        }
        return $return;
    }

    function get_tbl_field_ids_inarray($tbl,$data=array())
    {
       
        if (!empty($data['sel'])) {
            $this->db->select($data['sel']);
        }
        if (!empty($data['where'])) {
            $this->db->where($data['where']);
        }
        if (!empty($data['or_where'])) {
            $this->db->or_where($data['or_where']);
        }
        if (!empty($data['order_by'])) {
            $this->db->order_by($data['order_by']);
        }
        if(!empty($data['join']) && !empty($data['join_table']))
        {
            $this->db->join($data['join_table'],$data['join']);
        }
        $result = $this->db->get($tbl)->result();

        $return_array = array();
        
        if(!empty($result)){
            
            foreach($result as $re)
            { 
                if(!empty($data['array_field']))
                $dataproject_id = $data['array_field'];array_push($return_array,$re->$dataproject_id);
            }
        }
        
        return $return_array;

    }

    function get_commision_lists()
    {
        $result = $this->db->get('commision_settings')->result();
        $return = array();
        foreach($result as $res)
        {
            $return[$res->id] = $res->s_level == 0?'Under '.$res->salary:'Over '.$res->salary;
        }
        return $return;
    }
   
    function get_salary_list($user_id)
    {
        $this->db->where('user_id',$user_id);
        return $this->db->get('salary_history')->result();
    }
}
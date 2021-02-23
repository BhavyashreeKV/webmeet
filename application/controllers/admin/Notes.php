<?php 
class Notes extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->auth->check_privilege(array('booker'),'dashboard');
        $this->lang->load('meetings');
        $this->load->model('booking_model');
    }

    function index()
    {
        $data['page_title'] = lang('manage_notes');
        $data['datatables'] = TRUE;
        $data['notesDT'] = TRUE;
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['patients'] = $this->booking_model->get_dd_all_users(2);

        $this->view('notes/notes_index',$data);
    }

    function behandlareNotes()
    {

        $columns = array(
            
            0 => 'action',  
            1 => 'doctor_fullname',
            2 => 'patient_fullname',
            3 => 'meeting_id',
            4 => 'booking_date',
            5 => 'notes',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:3;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];

        
        $totalData = $this->booking_model->allNotes_count();
       
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            // $this->db->where('notes.doctor_id',get_behandlare_detail('id'));
            $posts = $this->booking_model->allNotes($limit, $start, $order, $dir);
           
        } else {
            $search = $this->input->post('search')['value'];
            
            // $this->db->where('notes.doctor_id',get_behandlare_detail('id'));
            $posts =  $this->booking_model->notes_search($limit, $start, $search, $order, $dir);
            
            // $this->db->where('notes.doctor_id',get_behandlare_detail('id'));
            $totalFiltered = $this->booking_model->notes_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $nestedData['DT_RowId'] = 'row_'.$post->id; 
                $history = '<a class="btn btn-outline-primary btn-icon waves-effect eNotes" href="'.admin_url('notes/history/'.$post->id).'" data-meeting_id="'.$post->meeting_id.'"><i class="fa fa-list"></i></a>';
                $nestedData['action'] = '<a class="btn btn-outline-primary btn-icon waves-effect eNotes" href="javascript:;" data-meeting_id="'.$post->meeting_id.'"><i class="fa fa-pencil"></i></a> '.$history.' <a href="javascript:;" class="btn btn-outline-danger btn-icon waves-effect delNotes" rel="'.admin_url('notes/delete/'.$post->id).'"><i class="fa fa-times"></i></a>'; 
                $nestedData['patient_fullname'] = '<span>'.ucfirst($post->patient_fullname).'</span>';
                $nestedData['doctor_fullname'] = '<span>'.ucfirst($post->doctor_fullname).'</span>';
                $nestedData['meeting_id'] = $post->meeting_id;
                $nestedData['booking_date'] = $post->booking_date;
                $view_more = '<a href="javascript:;" class="text-primary view_notes" rel="'.$post->id.'" id="note'.$post->id.'">View Notes</a> ';
                $hide = ' <a href="javascript:;" class="text-primary hide_notes" rel="'.$post->id.'">Hide Note</a> ';
                $nestedData['notes'] = $view_more.'<span class="d-none" id="d_note'.$post->id.'">'.$post->notes.$hide.'</span>';
                
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

    function delete($id)
    {
        if($id)
        {
            $this->Common_model->delete_tbl('notes',array('where'=>array('id'=>$id)));
        }
        echo 1;
    }

    function get_notes($meeting_id)
    {
        
        $data['content'] = $this->Common_model->get_tbl_row('notes',array('meeting_id'=>$meeting_id));
        
        $data['id'] = false;
        $data['notes'] = '';
        // print_a($data['content']);
        if(!empty($data['content']))
        {
            $data['id'] = $data['content']->id;$data['notes'] = $data['content']->notes;
        }
        $data['meeting_id'] = $meeting_id;
        $this->view('notes/notes_editform',$data,true);

    }

    function update_viewedby()
    {
        $note_id = $this->input->post('note_id');
        $action = 'viewed';
        
        $save['admin_id'] = get_user_detail('id');
        $save['note_id'] = $note_id;
        $save['action'] = $action;

        $id = $this->Common_model->save_tbl('notes_viewedby',$save);

        echo $id;

    }

    function history($notes_id)
    {
        if(!$notes_id)
        {
            redirect(admin_url('notes'));
        }

        $data['page_title'] = 'Notes History';
        $data['note_id'] = $notes_id;

        $this->view('notes/notes_history',$data);
    }

    function notes_log()
    {
        $columns = array(
            
            0 => 'action',  
            1 => 'fullname',
            2 => 'added_date',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:2;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];

        $note_id = $this->input->post('note_id');

        $totalData = $this->booking_model->allNotes_log_count($note_id);
       
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            // $this->db->where('notes.doctor_id',get_behandlare_detail('id'));
            $posts = $this->booking_model->allNotes_log($limit, $start, $order, $dir,$note_id);
           
        } else {
            $search = $this->input->post('search')['value'];
            
            // $this->db->where('notes.doctor_id',get_behandlare_detail('id'));
            $posts =  $this->booking_model->notes_log_search($limit, $start, $search, $order, $dir,$note_id);
            
            // $this->db->where('notes.doctor_id',get_behandlare_detail('id'));
            $totalFiltered = $this->booking_model->notes_log_search_count($search,$note_id);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $nestedData['DT_RowId'] = 'row_'.$post->id; 
                $nestedData['action'] = $post->action;
                $nestedData['fullname'] = '<span>'.ucfirst($post->fullname).'</span>';
                $nestedData['added_date'] = $post->added_date;
                
                
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
}
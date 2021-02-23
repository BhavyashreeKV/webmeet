<?php 
class Feedback extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->auth->check_privilege(array('booker'),'dashboard');
        $this->lang->load('meetings');
        $this->load->model('feedback_model');
    }

    function index()
    {
        $data['page_title'] = lang('feedback');
        $data['datatables'] = TRUE;
        $this->view('feedback/feedback_index',$data);
        
    }

    function all_feedbacks()
    {
        $columns = array(
            
            0 => 'action',  
            1 => 'meeting_id',
            2 => 'booking_date',
            3 => 'fullname',
            4 => 'type',
            5 => 'rating',
            6 => 'review',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $type = $this->input->post('is_category')?$this->input->post('is_category'):'Doctor';
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:1;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];
        $totalData = $this->feedback_model->allFeedback_count();
        
        $totalFiltered = $totalData;
        if (empty($this->input->post('search')['value'])) {
            $posts = $this->feedback_model->allFeedback($type,$limit, $start, $order, $dir);
        } else {
            $search = $this->input->post('search')['value'];
            
            $posts =  $this->feedback_model->feedback_search($type,$limit, $start, $search, $order, $dir);
            
            $totalFiltered = $this->feedback_model->feedback_search_count($type,$search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $nestedData['action'] = '<a href="javascript:;" class="btn btn-outline-danger btn-icon waves-effect delNotes" rel="'.admin_url('feedback/delete/'.$post->id).'"><i class="fa fa-times"></i></a>'; 
                $nestedData['meeting_id'] = $post->meeting_id;
                $nestedData['booking_date'] = $post->booking_date;
                $nestedData['fullname'] = ucfirst($post->fullname);
                $nestedData['type'] = $post->type == 'Doctor'?'Treatment Specialists':$post->type;
                $nestedData['rating'] = $post->rating.' <i class="fa fa-star text-warning"></i>\'s';
                $nestedData['review'] = $post->review;
                
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
            $this->Common_model->delete_tbl('ratings',array('where'=>array('id'=>$id)));
        }
        echo 1;
    }
}
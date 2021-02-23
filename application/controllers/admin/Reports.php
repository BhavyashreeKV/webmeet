<?php 
class Reports extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model(array('booking_model','Report_model'));
        $this->lang->load('reports_lang');
        $this->load->library(array('Excel','pdfgenerator'));
    }

    function upcoming_meetings()
    {
        $data['page_title'] = lang('upcoming_meetings');
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['patients'] = $this->booking_model->get_dd_all_users(2);
        $this->view('reports/reports_upcoming',$data);
    }

    function upcoming_posts()
    {
        $columns = array(
            0 =>'action',
            1 => 'id',
            2 => 'meeting_id',
            3 => 'status',
            4 => 'doctor_fullname',
            5 => 'patient_fullname',
            6 => 'booking_date',
            7 => 'start_datetime',
              
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:2;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];

        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if($this->input->post('status'))
        {
            $this->db->where('bookings.status',$this->input->post('status'));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
        $this->db->where(array('bookings.booking_date >='=>date('Y-m-d'),'bookings.end_datetime >'=>time()));
        $totalData = $this->booking_model->allposts_count();
       
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $this->db->where(array('bookings.booking_date >='=>date('Y-m-d'),'bookings.end_datetime >'=>time()));
            $posts = $this->booking_model->allposts($limit, $start, $order, $dir);
            // print_a($posts);
        } else {
            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $search = $this->input->post('search')['value'];
            $this->db->where(array('bookings.booking_date >='=>date('Y-m-d'),'bookings.end_datetime >'=>time()));
            $posts =  $this->booking_model->posts_search($limit, $start, $search, $order, $dir);

            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $this->db->where(array('bookings.booking_date >='=>date('Y-m-d'),'bookings.end_datetime >'=>time()));
            $totalFiltered = $this->booking_model->posts_search_count($search);
        }
        if($this->input->post('export'))
        {
           
            /* Export and die */
            if($this->input->post('export') === 'Excel')
            {
                $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
                $export_data['display_fileds'] = array('id','meeting_id','status','doctor_fullname','patient_fullname','booking_date','starttime','endtime');
                $export_data['log_data'] = $posts;
                $filepath = $this->excel->render_excel($export_data,'Upcoming-Meetings-Report',true);
                $w['filepath'] = $filepath; 
                $w['filename'] = 'Upcoming-Meeting-Report.xlsx';
                echo json_encode($w);
                die;
            }
            if($this->input->post('export') === 'PDF')
            {
                $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
                $export_data['display_fileds'] = array('id','meeting_id','status','doctor_fullname','patient_fullname','booking_date','starttime','endtime');
                $export_data['log_data'] = $posts;
                $orien = (count($export_data['display_columns']) > 8)?'landscape':'portrait';
                $html  = $this->pdfgenerator->render_table($export_data);
                $filepath = $this->pdfgenerator->generate($html,'Upcoming-Meeting-Report',0,'A4',$orien,true);
                $w['filepath'] = $filepath; 
                $w['filename'] = 'Upcoming-Meeting-Report.pdf';
                echo json_encode($w);
                die;
            }
        }
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if($post->status == 'completed'){$color="success";}elseif($post->status == 'new'||$post->status == 'rescheduled'){$color="primary";}else{$color='danger';}       
                $nestedData['action'] = '<a class="btn btn-outline-danger btn-icon waves-effect" onclick="return areyousure()" href="'.admin_url('bookings/delete/'.$post->id).'"><i class="fa fa-times"></i></a>'; 
                $nestedData['id'] = $post->id;
                $nestedData['meeting_id'] = $post->meeting_id;
                $nestedData['status'] = '<span class="badge badge-'.$color.'">'.ucfirst($post->status).'</span>';
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


    function all_meetings()
    {
        $data['page_title'] = lang('all_meetings');
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['patients'] = $this->booking_model->get_dd_all_users(2);
        $this->view('reports/reports_allmeetings',$data);
    }

    function all_posts($immediate=false)
    {
        $columns = array(
            0 =>'action',  
            1 => 'booking_date',
            2 => 'start_datetime',
            3 => 'status',
            4 => 'doctor_fullname',
            5 => 'patient_fullname',
            6 => 'meeting_id',
            7 => 'id',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:2;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];
        if($this->input->post('export'))
        {
            $limit=0;
        }

        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if($this->input->post('status'))
        {
            $this->db->where('bookings.status',$this->input->post('status'));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
        $totalData = $this->booking_model->allposts_count($immediate);
       
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $posts = $this->booking_model->allposts($limit, $start, $order, $dir,$immediate);
            // print_a($posts);
        } else {
            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $search = $this->input->post('search')['value'];
            $posts =  $this->booking_model->posts_search($limit, $start, $search, $order, $dir,$immediate);

            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $totalFiltered = $this->booking_model->posts_search_count($search);
        }
        if($this->input->post('export'))
        {
           
            /* Export and die */
            if($this->input->post('export') === 'Excel')
            {
                $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
                $export_data['display_fileds'] = array('id','meeting_id','status','doctor_fullname','patient_fullname','booking_date','starttime','endtime');
                $export_data['log_data'] = $posts;
                $filepath = $this->excel->render_excel($export_data,'Meetings-Report',true);
                $w['filepath'] = $filepath; 
                $w['filename'] = 'Meeting-Report.xlsx';
                echo json_encode($w);
                die;
            }
            if($this->input->post('export') === 'PDF')
            {
                $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
                $export_data['display_fileds'] = array('id','meeting_id','status','doctor_fullname','patient_fullname','booking_date','starttime','endtime');
                $export_data['log_data'] = $posts;
                $orien = (count($export_data['display_columns']) > 8)?'landscape':'portrait';
                $html  = $this->pdfgenerator->render_table($export_data);
                $filepath = $this->pdfgenerator->generate($html,'Meeting-Report',0,'A4',$orien,true);
                $w['filepath'] = $filepath; 
                $w['filename'] = 'Meeting-Report.pdf';
                echo json_encode($w);
                die;
            }
        }
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                $imm = '';
                
                if($post->status == 'completed'){$color="success";}elseif($post->status == 'new'||$post->status == 'rescheduled'){$color="primary";}else{$color='danger';}               

                $nestedData['action'] = '<a class="btn btn-outline-danger btn-icon waves-effect" onclick="return areyousure()" href="'.admin_url('bookings/delete/'.$post->id).'"><i class="fa fa-times"></i></a>'; 
                $nestedData['booking_date'] = $post->booking_date;
                $nestedData['meeting_time'] = date('H:i:s',$post->start_datetime).' to '.date('H:i:s',$post->end_datetime);
                $nestedData['status'] = '<span class="badge badge-'.$color.'">'.ucfirst($post->status).'</span>';
                if($immediate)
                {
                    $bankd_id = $post->auth_patient ? 'Yes':'No';
                    $color = $post->auth_patient ? 'success':'danger';

                    $nestedData['auth_patient'] = '<span class="badge badge-'.$color.'">'.$bankd_id.'</span>';
                    $imm = ' ('.$post->personal_id.')'; 

                    $nestedData['phone'] = $post->phone;
                    // $link = $this->Common_model->get_tbl_row('immediate_bookings',array('booking_id'=>$postbooking_id));

                    // $nestedData['sms'] = $post->phone;
                }
                
                $nestedData['doctor_fullname'] = '<span class="lead">'.ucfirst($post->doctor_fullname).'</span>';
                $nestedData['patient_fullname'] = '<span class="lead">'.ucfirst($post->patient_fullname).$imm.'</span>';
                $nestedData['id'] = $post->id;
                $nestedData['meeting_id'] = $post->meeting_id;

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


    function bookers_report()
    {
        $data['page_title'] = 'Bookers Reports';

        $data['bookers_list'] = $this->Report_model->get_bookers_reports();

        $this->view('reports/reports_bookers',$data);
    }

    function monthly_meetings_status()
    {
        $data['page_title'] = 'All Monthly Meeting Status';

        $results = $this->Report_model->get_monthlyMeetings();

        $labels = $all_meetings = $completed = $missed = $cancelled = $upcoming = array();
        
        foreach($results as $result)
        {
            array_push($labels,$result['label']);
            array_push($all_meetings,$result['all_meetings']);
            array_push($completed,$result['completed']);
            array_push($missed,$result['missed']);
            array_push($cancelled,$result['cancelled']);
            array_push($upcoming,$result['upcoming']);
        }

            $data['monthly_meetings']['labels'] = $labels;
            $data['monthly_meetings']['all_meetings'] = $all_meetings;
            $data['monthly_meetings']['completed'] = $completed;
            $data['monthly_meetings']['missed'] = $missed;
            $data['monthly_meetings']['cancelled'] = $cancelled;
            $data['monthly_meetings']['upcoming'] = $upcoming;
        // print_a($data['monthly_meetings'],true);

        $this->view('reports/reports_monthlyMeetingStatus',$data);
    }

    function get_weely_date()
    {
        $week = $this->input->post('weekno'); 
        
        $dates = getStartAndEndDate($week,date('Y'));
        
        $results = $this->Report_model->get_weekly_meetings($dates['start_date'],$dates['end_date']);
        
        echo json_encode($results);
    }

    function patient_meetings()
    {
        $data['page_title'] = 'Patient wise meetings';

        /* Get all the patients meetings */
        $data['perpage'] = $arr['perpage'] = 100;
        $page = $this->input->get('page',true);
        $data['page'] = $arr['page'] = ($page>0) ? ($page-1)*$data['perpage'] :0;
        $data['patients'] = $this->Report_model->get_allpatients_meetingCnt($data['perpage'],$data['page']);

        $data['total_rows'] = $this->Report_model->get_allpatients_meetingCnt(false,false,true);

        $this->view('reports/reports_all_patients_list',$data);

    }

    function individual_pat_reports($user_id=false)
    {
        $this->load->helper('form');
        if(!$user_id)
        {
            error_flashdata('Page you are looking is not found');
            redirect(admin_url('reports/patient_meetings'));
        }

        $data['user'] = $this->Common_model->get_tbl_row('users',array('id'=>$user_id,'type'=>2));

        if(empty($data['user']))
        {
            error_flashdata('Patient not found');
            redirect(admin_url('reports/patient_meetings'));
        }

        $data['page_title'] = $data['user']->firstname.' '.$data['user']->lastname.' reports';
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        /* Default Get last 30 days report if searched then show them the search based reports */
        $status_graph =array(); 
        $data['grp_status'] = $this->Report_model->get_ps_meetings($user_id);
        array_push($status_graph,$data['grp_status']['completed']);
        array_push($status_graph,$data['grp_status']['cancelled']);
        array_push($status_graph,$data['grp_status']['upcoming']);
        array_push($status_graph,$data['grp_status']['missed']);
        $data['piegraphData'] = json_encode($status_graph); 
        $data['perpage'] = 100;
        $page = $this->input->get('page',true);
        $data['page'] = $arr['page'] = ($page>0) ? ($page-1)*$data['perpage'] :0;
        if($this->input->get('export') == 'excel')
        {
            $data['perpage'] = $page = false;
        }
        $data['all_pat_meetings'] = $this->Report_model->get_individual_pmeetings($user_id,false,$data['perpage'],$page);
        // print_last_query();
        $data['total_rows'] = $this->Report_model->get_individual_pmeetings($user_id,true);
        
        if($this->input->get('export') === 'excel')
        {
            $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
            $export_data['display_fileds'] = array('id','meeting_id','status','doc_fullname','pat_fullname','booking_date','starttime','endtime');
            $export_data['log_data'] = $data['all_pat_meetings'];
            $this->excel->render_excel($export_data,'Travel-Excel-Report');
            die;
        }
        $this->view('reports/reports_individualPatient_report',$data);

    }

    function doctor_meetings()
    {
        $data['page_title'] = lang('doctors').' wise meetings';

        /* Get all the patients meetings */
        $data['perpage'] = $arr['perpage'] = 100;
        $page = $this->input->get('page',true);
        $data['page'] = $arr['page'] = ($page>0) ? ($page-1)*$data['perpage'] :0;
        $data['patients'] = $this->Report_model->get_alldoctors_meetingCnt($data['perpage'],$data['page']);

        $data['total_rows'] = $this->Report_model->get_alldoctors_meetingCnt(false,false,true);

        $this->view('reports/reports_all_doctors_list',$data);

    }

    function individual_doc_reports($user_id=false)
    {
        $this->load->helper('form');
        if(!$user_id)
        {
            error_flashdata('Page you are looking is not found');
            redirect(admin_url('reports/doctor_meetings'));
        }

        $data['user'] = $this->Common_model->get_tbl_row('users',array('id'=>$user_id,'type'=>1));

        if(empty($data['user']))
        {
            error_flashdata('Doctor not found');
            redirect(admin_url('reports/doctor_meetings'));
        }
        $data['default_text'] = $this->input->get('start_date')?'':'Showing latest 30 days report';
        $data['page_title'] = $data['user']->firstname.' '.$data['user']->lastname.' reports';
        $data['doctors'] = $this->booking_model->get_dd_all_users(2);
        /* Default Get last 30 days report if searched then show them the search based reports */
        $status_graph =array(); 
        $data['grp_status'] = $this->Report_model->get_ds_meetings($user_id);
        array_push($status_graph,$data['grp_status']['completed']);
        array_push($status_graph,$data['grp_status']['cancelled']);
        array_push($status_graph,$data['grp_status']['upcoming']);
        array_push($status_graph,$data['grp_status']['missed']);
        $data['piegraphData'] = json_encode($status_graph); 
        $data['perpage'] = 100;
        $page = $this->input->get('page',true);
        $data['page'] = $arr['page'] = ($page>0) ? ($page-1)*$data['perpage'] :0;
        if($this->input->get('export') == 'excel')
        {
            $data['perpage'] = $page = false;
        }
        $data['all_pat_meetings'] = $this->Report_model->get_individual_dmeetings($user_id,false,$data['perpage'],$page);
        // print_last_query();
        $data['total_rows'] = $this->Report_model->get_individual_dmeetings($user_id,true);
        
        if($this->input->get('export') === 'excel')
        {
            $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
            $export_data['display_fileds'] = array('id','meeting_id','status','doc_fullname','pat_fullname','booking_date','starttime','endtime');
            $export_data['log_data'] = $data['all_pat_meetings'];
            $this->excel->render_excel($export_data,'Travel-Excel-Report');
            die;
        }
        $this->view('reports/reports_individualDoctor_report',$data);
    }

    function own_meetings($user_id=false)
    {
        $this->load->helper('form');
        if(!$user_id)
        {
            error_flashdata('Page you are looking is not found');
            redirect(admin_url('reports/doctor_meetings'));
        }

        $data['user'] = $this->Common_model->get_tbl_row('users',array('id'=>$user_id,'type'=>1));

        if(empty($data['user']))
        {
            error_flashdata('Treatment Specialist not found');
            redirect(admin_url('reports/doctor_meetings'));
        }
        if($data['user']->privilege != 'own_patient')
        {
            error_flashdata('Treatment Specialist have no privilege');
            redirect(admin_url('reports/doctor_meetings'));
        }
        $data['default_text'] = $this->input->get('start_date')?'':'Showing latest 30 days report';
        $data['page_title'] = 'Doctors Meetings';

        $data['perpage'] = 100;
        $page = $this->input->get('page',true);
        $data['page'] = $arr['page'] = ($page>0) ? ($page-1)*$data['perpage'] :0;
        if($this->input->get('export') == 'excel')
        {
            $data['perpage'] = $page = false;
        }

        $data['card_datas'] = $this->Report_model->get_docOwnMeetingCard($user_id);
        
        $data['doc_meetings'] = $this->Report_model->get_docOwnMeeting($user_id,false,$data['perpage'],$page);
        $data['total_rows'] = $this->Report_model->get_docOwnMeeting($user_id,true);

        if($this->input->get('export') === 'excel')
        {
            $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time','Created Date');
            $export_data['display_fileds'] = array('id','meeting_id','status','doc_fullname','pat_fullname','booking_date','starttime','endtime','added_date');
            $export_data['log_data'] = $data['doc_meetings'];
            $this->excel->render_excel($export_data,'Own-Meetings-Report');
            die;
        }
        $this->view('reports/reports_ownDoctorMeetings_report',$data);

    }

    function historical_report()
    {
        $data['page_title'] = 'Historically Same Meetings';

        $data['lists'] = $this->Report_model->same_meetings();
        // print_last_query();
        // print_a($data['lists'],true);
        $this->view('reports/reports_historicalsamedata',$data);
    }

    function immediate_meetings()
    {
        $data['page_title'] = 'Quick Meetings';
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['patients'] = $this->booking_model->get_dd_all_users(2);
        $this->view('reports/reports_immediate_meetings',$data);
    }

    function group_meetings()
    {   
        $data['page_title'] = 'Group Meetings';
        $data['doctors'] = $this->booking_model->get_dd_all_users(1);
        $data['patients'] = $this->booking_model->get_dd_all_users(2);
        $this->view('reports/reports_group_meetings',$data);
    }

    function group_meeting_data()
    {
        $columns = array(
            0 =>'action',  
            1 => 'booking_date',
            2 => 'start_datetime',
            3 => 'status',
            4 => 'doctor_fullname',
            5 => 'participant_counts',
            6 => 'meeting_id',
            7 => 'id',
           
        );

        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:2;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];
        if($this->input->post('export'))
        {
            $limit=0;
        }

        if($this->input->post('startdate')!='')
        {
         $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
        }
        if($this->input->post('enddate'))
        {
            $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
        }
        if($this->input->post('status'))
        {
            $this->db->where('bookings.status',$this->input->post('status'));
        }
        if(!empty($this->input->post('doctor')))
        {
            $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
        }
        if(!empty($this->input->post('patient')))
        {
            $this->db->where('bookings.patient_id',$this->input->post('patient'));
        }
        $totalData = $this->booking_model->allgroup_posts_count();
       
        $totalFiltered = $totalData;

        if (empty($this->input->post('search')['value'])) {
            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            // if(!empty($this->input->post('patient')))
            // {
            //     $this->db->where('bookings.patient_id',$this->input->post('patient'));
            // }
            $posts = $this->booking_model->allgroup_posts($limit, $start, $order, $dir);
            // print_a($posts);
        } else {
            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $search = $this->input->post('search')['value'];
            $posts =  $this->booking_model->group_posts_search($limit, $start, $search, $order, $dir);

            if($this->input->post('startdate')!='')
            {
            $this->db->where(array('booking_date >='=>date('Y-m-d',strtotime($this->input->post('startdate')))));
            }
            if($this->input->post('enddate'))
            {
                $this->db->where(array('booking_date <'=>date('Y-m-d',strtotime($this->input->post('enddate').'+1 day'))));
            }
            if($this->input->post('status'))
            {
                $this->db->where('bookings.status',$this->input->post('status'));
            }
            if(!empty($this->input->post('doctor')))
            {
                $this->db->where('bookings.doctor_id',$this->input->post('doctor'));
            }
            if(!empty($this->input->post('patient')))
            {
                $this->db->where('bookings.patient_id',$this->input->post('patient'));
            }
            $totalFiltered = $this->booking_model->posts_search_count($search);
        }
        if($this->input->post('export'))
        {
           
            /* Export and die */
            if($this->input->post('export') === 'Excel')
            {
                $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
                $export_data['display_fileds'] = array('id','meeting_id','status','doctor_fullname','patient_fullname','booking_date','starttime','endtime');
                $export_data['log_data'] = $posts;
                $filepath = $this->excel->render_excel($export_data,'Meetings-Report',true);
                $w['filepath'] = $filepath; 
                $w['filename'] = 'Meeting-Report.xlsx';
                echo json_encode($w);
                die;
            }
            if($this->input->post('export') === 'PDF')
            {
                $export_data['display_columns'] = array('ID','meeting_id','Status','Doctor Fullname','Patient Fullname','Booking Date','Start Time','End Time');
                $export_data['display_fileds'] = array('id','meeting_id','status','doctor_fullname','patient_fullname','booking_date','starttime','endtime');
                $export_data['log_data'] = $posts;
                $orien = (count($export_data['display_columns']) > 8)?'landscape':'portrait';
                $html  = $this->pdfgenerator->render_table($export_data);
                $filepath = $this->pdfgenerator->generate($html,'Meeting-Report',0,'A4',$orien,true);
                $w['filepath'] = $filepath; 
                $w['filename'] = 'Meeting-Report.pdf';
                echo json_encode($w);
                die;
            }
        }
        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                
                $arr['count'] = TRUE;
                $arr['where'] = array('booking_id'=>$post->id);
                $participant_count = $this->Common_model->get_tbl_list('group_bookings',$arr);
                if($post->status == 'completed'){$color="success";}elseif($post->status == 'new'||$post->status == 'rescheduled'){$color="primary";}else{$color='danger';}               

                $nestedData['action'] = '<a class="btn btn-outline-danger btn-icon waves-effect" onclick="return areyousure()" href="'.admin_url('bookings/delete/'.$post->id).'"><i class="fa fa-times"></i></a>'; 
                $nestedData['booking_date'] = $post->booking_date;
                $nestedData['meeting_time'] = date('H:i:s',$post->start_datetime).' to '.date('H:i:s',$post->end_datetime);
                $nestedData['status'] = '<span class="badge badge-'.$color.'">'.ucfirst($post->status).'</span>';
                $nestedData['doctor_fullname'] = '<span class="lead">'.ucfirst($post->doctor_fullname).'</span>';
                $nestedData['participant_count'] = $participant_count. ' <a href="javascript:;" class="participant" data-id="'.$post->id.'">View Participants</a>';
                $nestedData['id'] = $post->id;
                $nestedData['meeting_id'] = $post->meeting_id;

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

    function prt_detials($booking_id)
    {
        $arr['where'] = ['booking_id'=>$booking_id];
        $result = $this->Common_model->get_tbl_list('group_bookings',$arr);

        $return = '';
        foreach($result as $res)
        {
            $return .= '<tr>
            <td>'.$res->personal_id.'</td>
            <td>'.$res->phone.'</td>
            <td>'.$res->email.'</td>
            <td>'.$res->fullname.'</td>
            <td>'.($res->auth_patient ? 'Yes' : 'No').'</td>
            <td>'.($res->pat_session ? 'Yes' : 'No').'</td>
            <td>'.($res->chat_disable ? 'Yes' : 'No').'</td>
            
        </tr>';
        }

        echo $return;
    }
}
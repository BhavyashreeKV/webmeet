<?php 
class Logs extends Admin_Controller
{
    function __construct()
	{
		parent::__construct();
        $this->load->library('form_validation');
        $this->auth->check_privilege(array('admin'),'dashboard');
        $this->lang->load('reports_lang');
        $this->load->model('logs_model');
        
    }

    function email_logs()
    {
        $tablename = 'email_logs';
        $data['page_title'] = lang('email_logs');
        $data['datatables'] = TRUE;

        $arr['key'] = $arr['value'] = 'from';
        $data['from_arra'] = $this->Common_model->get_keyvalue_tbl('email_logs',$arr,'DISTINCT(email_logs.from)');

        $arr['key'] = $arr['value'] = 'to';
        $data['to_arra'] = $this->Common_model->get_keyvalue_tbl('email_logs',$arr,'DISTINCT(email_logs.to)');
        $this->view('logs/email_logs',$data);
    }

    function email_log()
    {
        $columns = array(
            
            0 => 'from',  
            1 => 'to',
            2 => 'send',
            3 => 'subject',
            4 => 'content',
            5 => 'error_log',
           
        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:3;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];

        
        $totalData = $this->logs_model->emailLogs_count();
        $totalFiltered = $totalData;
        if (empty($this->input->post('search')['value'])) {
            $posts = $this->logs_model->emailLogs_countlim($limit, $start, $order, $dir);
           
        } else {
            $search = $this->input->post('search')['value'];
            $posts =  $this->logs_model->emaillogs_search($limit, $start, $search, $order, $dir);
            
            $totalFiltered = $this->logs_model->emaillogs_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $nestedData['DT_RowId'] = 'row_'.$post->id; 
                $nestedData['from'] = $post->from;
                $nestedData['to'] = $post->to;

                $sent = $post->send ? 'SMS Sent':'Not Sent';
                $color = $post->send ? 'success':'danger';
                $nestedData['send'] = '<span class="badge badge-'.$color.'">'.$sent.'</span>';

                $nestedData['subject'] = $post->subject;
                $view_more = '<a href="javascript:;" class="text-primary view_content" rel="'.$post->id.'" id="log'.$post->id.'">View More</a> ';
                $hide = ' <a href="javascript:;" class="text-primary hide_content" rel="'.$post->id.'">Hide</a> ';
                $nestedData['content'] = $view_more.'<span class="d-none" id="d_log'.$post->id.'">'.$post->content.$hide.'</span>';


                $view_more = '<a href="javascript:;" class="text-primary view_error" rel="'.$post->id.'" id="log1'.$post->id.'">View More</a> ';
                $hide = ' <a href="javascript:;" class="text-primary hide_error" rel="'.$post->id.'">Hide</a> ';
                if ($post->error_log != NULL)
                {
                    $myString = $post->error_log;
                     if (str_contains($myString, 'MessageId')) 
                       {
                            $myString = substr($myString, 15, -3);
                        }
                     else if (str_contains($myString, 'Code'))
                        {
                            $myString = json_decode($myString, true);
                            if(isset($myString))
                            {
                                $myString = "ResponseCode=".$myString["Code"]."</br>Description=".$myString["Description"];
                            }
                        }
                     else
                        {
                            $myString = $post->error_log;
                        }

                    $nestedData['error_log'] = $view_more.'<span class="d-none" id="e_log'.$post->id.'">'.$myString.$hide.'</span>';
                }
                else
                {
                    $nestedData['error_log'] = $view_more.'<span class="d-none" id="e_log'.$post->id.'">'."--".$hide.'</span>';
                }
                
                
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

    function sms_logs()
    {
        $arr['key'] = $arr['value'] = 'send';
        $data['send_arra'] = $this->Common_model->get_keyvalue_tbl('sms_notification_logs',$arr,'DISTINCT(sms_notification_logs.send)');

        $arr['key'] = $arr['value'] = 'to';
        $data['to_arra'] = $this->Common_model->get_keyvalue_tbl('sms_notification_logs',$arr,'DISTINCT(sms_notification_logs.to)');
        $data['page_title'] = lang('sms_logs');
        $data['datatables'] = TRUE;
        $this->view('logs/sms_logs',$data);
    }

    function sms_log()
    {
        $columns = array(
            
            0 => 'to',  
            1 => 'send',
            2 => 'send_time',
            3 => 'content',
            4 => 'error_log',
           
        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:3;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];

        
        $totalData = $this->logs_model->smsLogs_count();
        $totalFiltered = $totalData;
        if (empty($this->input->post('search')['value'])) {
            $posts = $this->logs_model->smsLogs_countlim($limit, $start, $order, $dir);
           
        } else {
            $search = $this->input->post('search')['value'];
            $posts =  $this->logs_model->smslogs_search($limit, $start, $search, $order, $dir);
            
            $totalFiltered = $this->logs_model->smslogs_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {

                $nestedData['DT_RowId'] = 'row_'.$post->id; 
                $nestedData['to'] = $post->to;

                $sent = $post->send ? 'SMS Sent':'Not Sent';
                $color = $post->send ? 'success':'danger';
                $nestedData['send'] = '<span class="badge badge-'.$color.'">'.$sent.'</span>';

                $nestedData['send_time'] = $post->send_time;

                $view_more = '<a href="javascript:;" class="text-primary view_content" rel="'.$post->id.'" id="log'.$post->id.'">View More</a> ';
                $hide = ' <a href="javascript:;" class="text-primary hide_content" rel="'.$post->id.'">Hide</a> ';
                $nestedData['content'] = $view_more.'<span class="d-none" id="d_log'.$post->id.'">'.$post->content.$hide.'</span>';

                $view_more = '<a href="javascript:;" class="text-primary view_error" rel="'.$post->id.'" id="log1'.$post->id.'">View More</a> ';
                $hide = ' <a href="javascript:;" class="text-primary hide_error" rel="'.$post->id.'">Hide</a> ';

                if ($post->error_log != NULL)
                {
                    $myString = $post->error_log;
                     if (str_contains($myString, 'MessageId')) 
                       {
                            $myString = substr($myString, 15, -3);
                        }
                     else if (str_contains($myString, 'Code'))
                        {
                            $myString = json_decode($myString, true);
                            if(isset($myString))
                            {
                                $myString = "ResponseCode=".$myString["Code"]."</br>Description=".$myString["Description"];
                            }
                        }
                     else
                        {
                            $myString = $post->error_log;
                        }

                    $nestedData['error_log'] = $view_more.'<span class="d-none" id="e_log'.$post->id.'">'.$myString.$hide.'</span>';
                }
                else
                {
                    $nestedData['error_log'] = $view_more.'<span class="d-none" id="e_log'.$post->id.'">'."--".$hide.'</span>';
                }
                


                
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

    function bankauth_logs()
    {
        $arr['key'] = $arr['value'] = 'personal_id';
        $data['pid_arra'] = $this->Common_model->get_keyvalue_tbl('bankauth_log',$arr,'DISTINCT(bankauth_log.personal_id)');

        $arr['key'] = $arr['value'] = 'status';
        $data['status_arra'] = $this->Common_model->get_keyvalue_tbl('bankauth_log',$arr,'DISTINCT(bankauth_log.status)');
        $data['page_title'] = lang('bankauth_logs');
        $data['datatables'] = TRUE;
        $this->view('logs/bankauth_logs',$data);
    }

    function bankauth_log()
    {
        $columns = array(
            
            0 => 'personal_id',  
            1 => 'meeting_id',
            2 => 'status',
            3 => 'autostarttoken',
            4 => 'request',
            5 => 'response',
            6 => 'error_log',
           
        );
        $limit = $this->input->post('length');
        $start = $this->input->post('start');
        $post_column = isset($this->input->post('order')[0]['column'])?$this->input->post('order')[0]['column']:3;
        $order = $columns[$post_column];
        $dir = $this->input->post('order')[0]['dir'];

        
        $totalData = $this->logs_model->bankauthLogs_count();
        $totalFiltered = $totalData;
        if (empty($this->input->post('search')['value'])) {
            $posts = $this->logs_model->bankauthLogs_countlim($limit, $start, $order, $dir);
           
        } else {
            $search = $this->input->post('search')['value'];
            $posts =  $this->logs_model->bankauthlogs_search($limit, $start, $search, $order, $dir);
            
            $totalFiltered = $this->logs_model->bankauthlogs_search_count($search);
        }

        $data = array();
        if (!empty($posts)) {
            foreach ($posts as $post) {
                if($post->status == 'Login Initiated' ||$post->status == 'complete'){$color="success";}elseif($post->status == 'pending'){$color="primary";}else{$color='danger';}       

                $nestedData['DT_RowId'] = 'row_'.$post->id; 
                $nestedData['personal_id'] = $post->personal_id;
                if ($post->meeting_id != NULL)
                {
                    $nestedData['meeting_id'] = $post->meeting_id;
                }
                else
                {
                    $nestedData['meeting_id'] = "--";
                }

                $nestedData['status'] = '<span class="badge badge-'.$color.'">'.ucfirst($post->status).'</span>';

                if ($post->autostarttoken != NULL)
                {
                    $strreplace = $post->autostarttoken;
                    $strreplace = substr($strreplace, 0, -5).'*****';
                    $nestedData['autostarttoken'] = $strreplace;
                }
                else
                {
                    $nestedData['autostarttoken'] = "--";
                }

                if ($post->request != NULL)
                {
                    $nestedData['request'] = $post->request;
                }
                else
                {
                    $nestedData['request'] = "--";
                }
                if ($post->response != NULL)
                {
                    $myString = $post->response;
                    $myString = json_decode($myString, true);
                    if(isset($myString))
                    {
                       $myString = $myString["orderRef"];
                       $myString = substr($myString, 0, -5).'*****';
                    }
                    else
                    {
                        $mymainString = $post->response;
                        if (str_contains($mymainString, 'autostarttoken')) 
                       {
                            $myString = substr($mymainString, 25, -5).'*****';
                        }
                        else
                        {
                            $myString = $post->response;
                        } 
                    }
                   
                    $nestedData['response'] = $myString;
                }
                else
                {
                    $nestedData['response'] = "--";
                }

                $view_more = '<a href="javascript:;" class="text-primary view_error" rel="'.$post->id.'" id="log1'.$post->id.'">View More</a> ';
                $hide = ' <a href="javascript:;" class="text-primary hide_error" rel="'.$post->id.'">Hide</a> ';
                if ($post->error_log != NULL)
                {
                    $myString = $post->error_log;
                    $myString = json_decode($myString, true);
                    if(isset($myString))
                    {
                        $myString = "OrderRef=".$myString["orderRef"]."</br>HintCode=".$myString["hintCode"] ."</br>ResponseCode=". $myString["responseCode"];
                    }
                    else
                    {
                        $myString = $post->error_log;
                    }
                    $nestedData['error_log'] = $view_more.'<span class="d-none" id="e_log'.$post->id.'">'.$myString.$hide.'</span>';
                }
                else
                {
                    $nestedData['error_log'] = $view_more.'<span class="d-none" id="e_log'.$post->id.'">'."--".$hide.'</span>';
                }

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
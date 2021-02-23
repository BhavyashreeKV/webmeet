<?php 
function template_assets($uri = '')
{
    $CI =& get_instance();
    return $CI->config->base_url('assets_v1/' . $uri);
}

/* Get Theme assets url - Frontend */
function theme_assets($uri = '')
{
    $CI =& get_instance();
    return $CI->config->base_url('theme_assets/' . $uri);
}

/* Get the current url with get variables */
function current_url_with_get()
{
    $CI =& get_instance();
    $get_sort='';
    if($CI->input->get())
    {
        $get_sort	= '?'.http_build_query($CI->input->get());
    }
    return $CI->config->site_url($CI->uri->uri_string().$get_sort);
}

/* Session functions starts here */

function error_flashdata($message)
{
    $CI =& get_instance();
    $CI->session->set_flashdata('error',$message);
}
function msg_flashdata($message)
{
    $CI =& get_instance();
    $CI->session->set_flashdata('message',$message);
}
function create_userdata($session_name,$session_data)
{
    $CI =& get_instance();
    $CI->session->set_userdata($session_name,$session_data);
    return true;
}
function get_useradata($session_name)
{
    $CI =& get_instance();
    return $CI->session->userdata($session_name);
}

/* Session function ends here */

/* Get the pagination with get function  */
function pagination_get($total_rows, $per_page)
    {
        if($total_rows <= $per_page)
        {
            return FALSE;
        }
        $CI =& get_instance();
        $max_pages = ceil($total_rows / $per_page);
        $get       = $CI->input->get();
        if(!isset($get['page']))
        {
            $get['page'] = 1;
        }
        $current_page = $get['page'];
        if($current_page == 0 || $max_pages < $current_page)
        {
            $current_page = 1;
        }
        $previous = '';
        if($current_page > 1)
        {
            $get['page'] = $current_page - 1;
            $query_p     = http_build_query($get);
            $previous    = '<li class="page-item"><a class="page-link" href="' . $CI->config->site_url($CI->uri->uri_string() . '?' . $query_p) . '" aria-label="Previous"><i class="fa fa-chevron-left"></i></a></li>';
        }
        $next = '';
        if($current_page < $max_pages)
        {
            $get['page'] = $current_page + 1;
            $query_p     = http_build_query($get);
            $next        = '<li class="page-item"><a class="page-link" href="' . $CI->config->site_url($CI->uri->uri_string() . '?' . $query_p) . '" aria-label="Next"><i class="fa fa-chevron-right"></i></a></li>';
        }
        $current = '<li class="page-item active"><a class="page-link">' . $current_page . '</a></li>';
        $p_steps = '';
        $prv     = $current_page - 3;
        for($i = $prv; $i < $current_page; $i++)
        {
            if($i > 0)
            {
                $get['page'] = $i;
                $query_p     = http_build_query($get);
                $p_steps     .= '<li class="page-item"><a class="page-link" href="' . $CI->config->site_url($CI->uri->uri_string() . '?' . $query_p) . '">' . $i . '</a></li>';
            }
        }
        $n_steps = '';
        $nxt     = ($current_page + 3);
        if($nxt > $max_pages)
        {
            $nxt = $max_pages;
        }
        for($i = $current_page + 1; $i <= $nxt; $i++)
        {
            $get['page'] = $i;
            $query_p     = http_build_query($get);
            $n_steps     .= '<li class="page-item"><a class="page-link" href="' . $CI->config->site_url($CI->uri->uri_string() . '?' . $query_p) . '">' . $i . '</a></li>';
        }
        return '<nav class="pt-3"><ul class="pagination">' . $previous . $p_steps . $current . $n_steps . $next . '</ul></nav>';
    }

    /* Pagination in ajax methods */
    function pagination_ajax($total_rows, $per_page,$method_name="GetTMRecords")
    {
        if ($total_rows <= $per_page) {
            return FALSE;
        }
        $CI =& get_instance();
        $max_pages = ceil($total_rows / $per_page);
        $get = $CI->input->get();
        if (!isset($get['page'])) {
            $get['page'] = 1;
        }
        $current_page = $get['page'];
        if ($current_page == 0 || $max_pages < $current_page) {
            $current_page = 1;
        }
        $previous = '';
        if ($current_page > 1) {
            $get['page'] = $current_page - 1;
            $previous = '<li class="page-item"><a class="page-link" onclick="'.$method_name.'(' . $get['page'] . ')" aria-label="Previous" style="cursor:pointer"><span aria-hidden="true"><i class="fal fa-chevron-left"></i></span></a></li>';
        }
        $next = '';
        if ($current_page < $max_pages) {
            $get['page'] = $current_page + 1;
            $next = '<li class="page-item"><a class="page-link" onclick="'.$method_name.'(' . $get['page'] . ')" aria-label="Next" style="cursor:pointer"><span aria-hidden="true"><i class="fal fa-chevron-right"></i></span></a></li>';
        }
        $current = '<li class="page-item active"><a class="page-link" >' . $current_page . '</a></li>';
        $p_steps = '';
        $prv = $current_page - 3;
        for ($i = $prv; $i < $current_page; $i++) {
            if ($i > 0) {
                $get['page'] = $i;
                $p_steps .= '<li class="page-item"><a class="page-link" onclick="'.$method_name.'(' . $get['page'] . ')" style="cursor:pointer">' . $i . '</a></li>';
            }
        }
        $n_steps = '';
        $nxt = ($current_page + 3);
        if ($nxt > $max_pages) {
            $nxt = $max_pages;
        }
        for ($i = $current_page + 1; $i <= $nxt; $i++) {
            $get['page'] = $i;
            $n_steps .= '<li class="page-item"><a class="page-link" onclick="'.$method_name.'(' . $get['page'] . ')" style="cursor:pointer">' . $i . '</a></li>';
        }
        return '<nav><ul class="pagination">' . $previous . $p_steps . $current . $n_steps . $next . '</ul></nav>';
    }

    /*-----------------------------------------------------------------------
     Get the user datails from the current session and pass the requested filed if needed 
     ----------------------------------------------------------------------------*/
    function get_user_detail($field=false)
    {
        $CI =& get_instance();
        $user =  $CI->session->userdata('user');
        if(!empty($user)){
            if($field) {
                // If it's an array of values, then loop over each, to move down the customer array
                if(is_array($field)) {
                    $return = $user;
                    foreach($field as $v) {
                        if(isset($return[$v])) {
                            $return = $return[$v];
                        }
                    }
                    // ... to return the last requested value
                    return $return;
                    // ... otherwise, just return the requested value
                } elseif(isset($user[$field])) {
                    return $user[$field];
                } else{
                    return false;
                }
            }
            return $user;
        }
        else
        {
            return false;
        }
        
    }
    
    function get_behandlare_detail($field=false)
    {
        $CI =& get_instance();
        $user =  $CI->session->userdata('behandlare');
        if(!empty($user)){
            if($field) {
                // If it's an array of values, then loop over each, to move down the customer array
                if(is_array($field)) {
                    $return = $user;
                    foreach($field as $v) {
                        if(isset($return[$v])) {
                            $return = $return[$v];
                        }
                    }
                    // ... to return the last requested value
                    return $return;
                    // ... otherwise, just return the requested value
                } elseif(isset($user[$field])) {
                    return $user[$field];
                } else{
                    return false;
                }
            }
            return $user;
        }
        else
        {
            return false;
        }
        
    }
    /* Get Patient Session details */
    function get_patient_detail($field=false)
    {
        $CI =& get_instance();
        $user =  $CI->session->userdata('patient');
        if(!empty($user)){
            if($field) {
                // If it's an array of values, then loop over each, to move down the customer array
                if(is_array($field)) {
                    $return = $user;
                    foreach($field as $v) {
                        if(isset($return[$v])) {
                            $return = $return[$v];
                        }
                    }
                    // ... to return the last requested value
                    return $return;
                    // ... otherwise, just return the requested value
                } elseif(isset($user[$field])) {
                    return $user[$field];
                } else{
                    return false;
                }
            }
            return $user;
        }
        else
        {
            return false;
        }
        
    }

    /* Get the Upload folder images and contents */
    function upload_url($uri=false,$img=false)
    {
        $CI =& get_instance();
            if( $uri && file_exists('uploads/'.$uri.$img)){
                return $CI->config->base_url('uploads' . '/' . $uri.$img);
            }else{
                return $CI->config->base_url('uploads/no_content.png');
            }
    }

    function sort_field($field_name,$field_display)
    {
        $sort_order = 'asc';
        $CI =& get_instance();
        $get       = $CI->input->get();
        $get['sort'] = $field_name;
        $get['by'] = (isset($get['by']) && $get['by'] == $sort_order)?'desc':'asc';
        $query_p     = http_build_query($get);

        if($get['by'] == 'desc' && $_GET['sort'] == $field_name){ $field_display ='<i class="icon-arrow-down-circle text-primary"></i> '.$field_display;}else{ $field_display ='<i class="icon-arrow-up-circle text-primary"></i> '.$field_display;}
        return anchor(site_url($CI->uri->uri_string() . '?' . $query_p), $field_display,'class="text-dark"');
        
    }

    /* ------------------------------------
    Set the Admin template default url 
    ---------------------------------------*/
    function admin_url($uri='')
    {
        $CI =& get_instance();
        return $CI->admin_url . $uri;
    }

    /* --------------------------------------
    Set the Behandlare default url
    --------------------------------------- */
    function behandlare_url($uri='')
    {
        $CI =& get_instance();
        return site_url(config_item('behandlare_folder').'/') . $uri;
    }
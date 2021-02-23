<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Emailnotification
{
    var $CI,$is_logger;

    function __construct()
    {
        /*  Set the logger to TRUE to save the logs in table. 
        |   If set to false logs will not be set 
        | */
        $this->is_logger = config_item('email_logs');

        $this->CI =& get_instance();
        $this->CI->load->helper('formatting');
    }

    /* Used to send the email from the custom template */
    function send_email($from, $to,$subject = false, $message = false, $from_name = false, $cc = false, $bcc = false, $attached = false, $reply_to = false)
    {
        $this->CI->load->library('email');
        $config = get_email_config();
        $config['mailtype'] = 'html';
        $this->CI->email->initialize($config);
        $message = ($message) ? $message : '';

        $this->CI->email->from($from, $from_name);
        $this->CI->email->to($to);
        if ($reply_to) $this->email->reply_to($reply_to);
        if ($cc) $this->CI->email->cc($cc);
        if ($bcc) $this->CI->email->bcc($bcc);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        if ($attached) $this->CI->email->attach($attached);
        //Send mail
        if ($this->CI->email->send()) {
            $this->CI->email->clear();
            if($this->is_logger)
            {
                $this->log_mail($from,$to,$subject,$message);
            }
            return true;
        } else {
            log_message('error', $this->CI->email->print_debugger(['header']));
            if($this->is_logger)
            {
                $this->log_mail($from,$to,$subject,$message,$this->CI->email->print_debugger(['header']));
            }
            $this->CI->email->clear();

            return false;
        }
    }

    /* The below function is used to  check the custom email or to check the configuration */
    function debug_email($from, $to,$subject = false, $message = false, $from_name = false, $cc = false, $bcc = false, $attached = false, $reply_to = false)
    {
        $this->CI->load->library('email');
        $config = get_email_config();
        $config['mailtype'] = 'html';
        $this->CI->email->initialize($config);

        $message = ($message) ? $message : '';

        $this->CI->email->from($from, $from_name);
        $this->CI->email->to($to);
        if ($reply_to) $this->email->reply_to($reply_to);
        if ($cc) $this->CI->email->cc($cc);
        if ($bcc) $this->CI->email->bcc($bcc);
        $this->CI->email->subject($subject);
        $this->CI->email->message($message);
        if ($attached) $this->CI->email->attach($attached);
        $this->CI->email->send();
        echo $this->CI->email->print_debugger();
        log_message('error', $this->CI->email->print_debugger(['header']));
    }

    /* Store the Logs data into the system. */
    function log_mail($from,$to,$subject,$content,$error_log=false)
    {
        $save_data['from'] = $from;
        $save_data['to'] = $to;
        $save_data['subject'] = $subject;
        $save_data['content'] = $content;
        $save_data['send'] = $error_log ? 0 : 1;
        $save_data['error_log'] = (string)$error_log;

        $this->CI->load->model('Common_model');
        $this->CI->Common_model->save_tbl('email_logs',$save_data);
        return true;
    }
}
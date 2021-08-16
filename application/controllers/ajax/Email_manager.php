<?php defined('BASEPATH') || exit('No direct script access allowed');

class Email_manager extends CI_Controller
{
    //
    private $resp;
    //
    public function __construct()
    {
        parent::__construct();
        //
        $this->resp = [
            'Status' => false,
            'Message' => 'Invalid Request.'
        ];
    }


    /**
     * 
     */
    function SendManualEmailReminderToEmployee(){
        //
        $res = $this->resp;
        //
        if(
            !$this->input->is_ajax_request() || 
            !$this->input->post(NULL, TRUE) || 
            $this->input->method() != 'post' 
        ){
            res($res);
        }
        //
        $post = $this->input->post(NULL, TRUE);
        //
        $replaceArray = [];
        $replaceArray['username'] = ucwords($post['first_name'].' '.$post['last_name']);
        $replaceArray['baseurl'] = base_url();
        $replaceArray['company_name'] = $post['company_name'];
        //
        log_and_send_templated_email(
            HR_DOCUMENTS_NOTIFICATION_EMS,
            $post['email'],
            $replaceArray,
            message_header_footer($post['company_sid'], $post['company_name'])
        );
    }

}
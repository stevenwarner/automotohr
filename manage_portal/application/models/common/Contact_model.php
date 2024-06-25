<?php
class Contact_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function send_enquiry($employer_id, $name, $email, $message, $data)
    { // Contact us form email function
        $Companyemail                                                           = $data['company_details']['email'];
        $CompanyName                                                            = $data['company_details']['CompanyName'];
        // show contact us enquiry to employers private message module *** StarT ***
        $this->db->select('sid');
        $this->db->where('parent_sid', $employer_id);
        $this->db->from('users');
        $all_company_users                                                      = $this->db->get()->result_array();

        foreach ($all_company_users as $value) {
            $message_data                                   = array();
            $message_data['to_id']                          = $value['sid'];
            $message_data['from_id']                        = $email;
            $message_data['from_type']                      = 'contact_us';
            $message_data['to_type']                        = 'employer';
            $message_data['date']                           = date('Y-m-d H:i:s');
            $message_data['subject']                        = 'Contact Us Enquiry from: ' . $name;
            $message_data['message']                        = $message;
            $message_data['outbox']                         = 0;
            $this->db->insert('private_message', $message_data);
        }
        // show contact us enquiry to employers private message module *** e n d ***
        $staus                                                                  = '';

        if ($Companyemail) { //sending mail
            $from                                                               = $message_data['from_id'];
            $to                                                                 = $Companyemail;
            $subject                                                            = 'Contact Us Enquiry Notification';
            $body = '<div class="body-content" style="width:100%; float:left; padding:20px 20px 60px 20px; box-sizing:border-box; background:url(www.' . STORE_DOMAIN . '/assets/images/bg-body.jpg);">'
                . '<h2 style="width:100%; margin:0 0 20px 0;">Hello ' . $CompanyName . ',</h2>'
                . '<br><br><b>'
                . $message_data['from_id'] . '</b> has sent you an Enquiry.'
                . '<br><br><b>'
                . 'Date:</b> '
                . date('Y-m-d H:i:s')
                . '<br><br><b>'
                . 'Subject:</b> '
                . $message_data['subject']
                . '<br><hr>'
                . $message_data["message"] . '<br><br>'
                . '<a href="www.' . STORE_DOMAIN . '/login">Login to you acount to reply this Enquiry.</a><br>'
                . '</div>'
                . '<div class="footer" style="width:100%; float:left; background-color:#000; padding:20px 30px; box-sizing:border-box;"><div style="float:left; width:50%; "><h3 style="color:#fff; margin:0;">Who we Are</h3><p style="color:#fff; font-style:italic;">Integer efficitur dapibus dolor,. </p></div><div style="float:right; width:45%; "><h3 style="color:#fff; margin:0;">Contact Us</h3><li style="color:#fff; margin:3px 0; list-style:none; float:left; width: 100%;"><img style="margin:3px 10px 0 0; float:left;" src="www.' . STORE_DOMAIN . '/assets/images/icon-1.png"><span style="margin:5px 0 0 0; float:left; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px; font-style:italic;"><a style="color:#fff; float:left; margin-top:5px;" href="mailto:' . $Companyemail . '">' . $Companyemail . '</a></span></li><li style="color:#fff; margin:3px 0; list-style:none; float:left; width: 100%;"><img style="margin:3px 10px 0 0; float:left;" src="www.' . STORE_DOMAIN . '/assets/images/icon-3.png"><span style="margin:5px 0 0 0; float:left; font-family: "Open Sans", sans-serif; font-weight:600; font-size:14px; "><a style="color:#fff; float:left; margin-top:5px; text-decoration:none; font-style:italic;" href="' . $data["domain_name"] . '">' . $data["domain_name"] . '</a></span></li></ul></div></div></div>';

            sendMail($from, $to, $subject, $body, $name);
        }

        // add the details in database table - contactus_log
        $insert_data            = array(
            'name'                                  => $name,
            'email'                                 => $email,
            'message'                               => $message,
            'company_name'                          => $CompanyName,
            'company_id'                            => $employer_id,
            'date'                                  => date('Y-m-d H:i:s'),
            'status'                                => $staus
        );

        $result                                                                 = $this->db->insert('contactus_log', $insert_data);
        return (!$result) ? $this->session->set_flashdata('message', '<b>Failed:</b>Could not send your Enquiry, Please try Again!') : $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your Enquiry, we will contact you soon.');
    }

    // Join our talent network Function
    function talent_network_enquiry($email, $first_name, $last_name, $country, $zipcode, $desired_job_title, $interest_level, $job_interest_text, $resume, $data)
    {
        $sid                                                                    = $data['company_details']['sid'];
        $company_email                                                          = $data['company_details']['email'];
        $CompanyName                                                            = $data['company_details']['CompanyName'];
        $staus                                                                  = ''; // it is give the status whether email is sent or not
        if ($company_email) {
            // send email at company email address
        }

        // add the details in database table - contactus_log
        $insert_data            = array(
            'employer_sid'                          => $sid,
            'email'                                 => $email,
            'first_name'                            => $first_name,
            'last_name'                             => $last_name,
            'country'                               => $country,
            'zipcode'                               => $zipcode,
            'desired_job_title'                     => $desired_job_title,
            'interest_level'                        => $interest_level,
            'resume'                                => $resume,
            'job_interest_text'                     => $job_interest_text,
            'date'                                  => date('Y-m-d H:i:s')
        );
        //$result = $this->db->insert('portal_join_network', $insert_data);
        //return (!$result) ? $this->session->set_flashdata('message', '<b>Failed:</b>Could not send your Enquiry, Please try Again!') : $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your interest in our Talent Network, we will contact you soon.');
    }

    // Join our talent network Function
    function talent_network_applicant($email, $first_name, $last_name, $country, $city, $phone_number, $desired_job_title, $resume, $data, $cover_letter, $state, $date_applied, $video_source = NULL, $video_id = NULL)
    {
        $sid                                                                    = $data['company_details']['sid'];
        $company_email                                                          = $data['company_details']['email'];
        $CompanyName                                                            = $data['company_details']['CompanyName'];
        $staus                                                                  = ''; // it is give the status whether email is sent or not

        if ($company_email) {
            // send email at company email address
        }
        // add the details in database table - contactus_log
        $insert_data = array(
            'employer_sid'                                      => $sid,
            'email'                                             => $email,
            'first_name'                                        => $first_name,
            'last_name'                                         => $last_name,
            'country'                                           => $country,
            'state'                                             => $state,
            'city'                                              => $city,
            'phone_number'                                      => $phone_number,
            'resume'                                            => $resume,
            'YouTube_Video'                                     => $video_id,
            //'cover_letter'                                      => $cover_letter,
            //'date_applied'                                      => $date_applied
        );
        if ($video_source != null) $insert_data['video_type'] = $video_source;

        $this->db->insert('portal_job_applications', $insert_data);

        if ($this->db->affected_rows() != 1) {
            return $result[1] = 0; // data insert failed
        } else {
            $this->session->set_flashdata('message', '<b>Success!</b> You have successfully applied for the job!');
            $result[0] = $this->db->insert_id();
            $result[1] = 1; // data insert successful
            return $result;
        }
    }

    // save message to system private meassges table for keeping it track
    public function save_message($product_data)
    {
        $product_data['outbox']                                 = 1;
        $this->db->insert('private_message', $product_data);
        //$product_data['outbox'] = 0;
        //$this->db->insert('private_message', $product_data);
        return "Message Saved Successfully";
    }
    // save message in auto responder email log
    public function save_email_log_autoresponder($email_log)
    {
        return $this->db->insert('email_log_autoresponder', $email_log);
    }

    public function get_talent_config($company_id)
    {
        $this->db->where("company_sid", $company_id);
        $record_obj = $this->db->get('talent_network_content_config');
        $result = $record_obj->result_array();
        $record_obj->free_result();
        return $result;
    }

    function fetch_job_fair_forms($company_sid, $form_type = 'custom', $status = 1)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        //        $this->db->where('form_type', $form_type);
        $this->db->where('status', $status);
        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function fetch_job_fair_forms_questions($job_fairs_forms_sid, $field_type = 'default')
    {
        $this->db->select('*');
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
        $this->db->where('field_display_status', 1);
        $this->db->where('field_type', $field_type);
        if ($field_type != 'default') {
            $this->db->order_by('sort_order', "ASC");
        }
        $record_obj = $this->db->get('job_fairs_forms_questions');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function fetch_job_fair_forms_questions_option($job_fairs_forms_sid)
    {
        $this->db->select('*');
        $this->db->where('job_fairs_forms_sid', $job_fairs_forms_sid);
        $record_obj = $this->db->get('job_fairs_forms_questions_option');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function custom_job_fair_entry_to_ats($insert_data)
    {
        $this->db->insert('portal_job_applications', $insert_data);

        if ($this->db->affected_rows() != 1) {
            return $result[1] = 0; // data insert failed
        } else {
            $this->session->set_flashdata('message', '<b>Success!</b> You have successfully applied for the job!');
            $result[0] = $this->db->insert_id();
            $result[1] = 1; // data insert successful
            return $result;
        }
    }

    function fetch_job_fair_by_page_url($page_url)
    {
        $this->db->select('sid, title, button_background_color, button_text_color');
        $this->db->where('page_url', $page_url);
        $record_obj = $this->db->get('job_fairs_forms');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function update_fair_page_url($sid, $page_url)
    {
        $data = array('job_fair_page_url' => $page_url);
        $this->db->where('sid', $sid);
        $this->db->update('portal_themes_pages', $data);
    }

    function fetch_form_details_by_id($sid, $form_type)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        if ($form_type == 'default') {
            $record_obj = $this->db->get('job_fairs_recruitment');
        } else {
            $record_obj = $this->db->get('job_fairs_forms');
        }

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }
}

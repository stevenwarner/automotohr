<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Demo_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function free_demo($first_name, $last_name, $email, $phone_number, $company_name, $company_size, $state, $country, $job_role, $client_source, $client_message, $status = 0, $is_admin = 0, $manual_entry = 0, $newsletter_subscrib, $city, $street, $zip_code, $timezone = NULL)
    {
        $insert_data = array(
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'company_name' => $company_name,
            'company_size' => $company_size,
            'state' => $state,
            'country' => $country,
            'job_role' => $job_role,
            'date_requested' => date('Y-m-d H:i:s'),
            'status' => $status,
            'client_source' => $client_source,
            'client_message' => $client_message,
            'ip_address' => getUserIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'manual_entry' => $manual_entry,
            'newsletter_subscribe' => $newsletter_subscrib,
            'city' => $city,
            'street' => $street,
            'timezone' => $timezone,
            'zip_code' => $zip_code
        );

        $result = $this->db->insert('free_demo_requests', $insert_data);

        if (!$result) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not send your Enquiry, Please try Again!');
        } else {
            if ($is_admin == 0) {
                $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your free demo request, we will contact you soon.');
                $from = FROM_EMAIL_NOTIFICATIONS;
                $body = '<p><img src="' . base_url() . 'assets/images/new_logo.JPG"/></p>'
                    . 'Dear Admin,'
                    . '<br><br>There is new demo request at '
                    . FROM_STORE_NAME
                    . '. <br><br> Demo Request Details are:'
                    . '<br><b>Contact Name: </b>' . $first_name . ' ' . $last_name
                    . '<br><b>Company Name: </b>' . $company_name
                    . '<br><b>Contact No: </b>' . $phone_number
                    . '<br><b>Company Email: </b>' . $email
                    . '<br><b>Company Size: </b>' . $company_size
                    . '<br><b>Job Role: </b>' . $job_role
                    . '<br><b>State: </b>' . $state
                    . '<br><b>Country: </b>' . $country
                    . '<br><b>How did you hear about us?: </b>' . $client_source
                    . '<br><b>Message: </b>' . $client_message
                    . '<br><br>To view details, Please go to admin panel'
                    . '<br><br><br>' . STORE_NAME;

                $system_notification_emails = get_system_notification_emails('free_demo_enquiry_emails');
                $from_email = $email;
                $from_name = ucwords($first_name . ' ' . $last_name);
                $reply_to_email = $email;
                $reply_to_name = ucwords($first_name . ' ' . $last_name);
                $cc = null;
                $subject = 'New Demo Request';

                if (!empty($system_notification_emails)) {
                    foreach ($system_notification_emails as $system_notification_email) {
                        $this->send_mail_with_cc($from_email, $system_notification_email['email'], $cc, $subject, $body, $from_name, $reply_to_email, $reply_to_name);
                    }
                }
            } else {
                $this->session->set_flashdata('message', '<b>Success: </b>Potential Client added successfully.');
            }
        }
    }

    function free_demo_new($first_name, $email, $phone_number, $company_name, $date_requested, $schedule_demo, $client_source, $ppc = 0, $message, $company_size, $newsletter_subscribe, $job_role, $manual_entry = 0, $country = '', $state = '')
    {
        $insert_data = array(
            'first_name' => $first_name,
            'email' => $email,
            'phone_number' => $phone_number,
            'company_name' => $company_name,
            'date_requested' => $date_requested,
            'schedule_date' => $schedule_demo,
            'company_size' => $company_size,
            'newsletter_subscribe' => $newsletter_subscribe,
            'ip_address' => getUserIP(),
            'user_agent' => $_SERVER['HTTP_USER_AGENT'],
            'manual_entry' => $manual_entry,
            'client_source' => $client_source,
            'ppc' => $ppc,
            'client_message' => $message,
            'job_role' => $job_role
        );

        if ($country) {
            $insert_data["country"] = $country;
        }
        if ($state) {
            $insert_data["state"] = $state;
        }

        $result = $this->db->insert('free_demo_requests', $insert_data);

        if (!$result) {
            $this->session->set_flashdata('message', '<b>Failed: </b>Could not send your Enquiry, Please try Again!');
        } else {
            $this->session->set_flashdata('message', '<b>Success: </b>Thank you for your free demo request, we will contact you soon.');
            $from = FROM_EMAIL_NOTIFICATIONS;
            $body = '<p><img src="' . base_url() . 'assets/images/new_logo.JPG"/></p>'
                . 'Dear Admin,'
                . '<br><br>There is new demo request at '
                . FROM_STORE_NAME
                . '. <br><br> Demo Request Details are:'
                . '<br><b>Contact Name: </b>' . $first_name
                . '<br><b>Company Name: </b>' . $company_name
                . '<br><b>Contact No: </b>' . $phone_number
                . '<br><b>Scheduled At: </b>' . $schedule_demo
                . '<br><b>Company Email: </b>' . $email
                . '<br><b>How did you hear about us?: </b>' . $client_source
                . '<br><br>To view details, Please go to admin panel'
                . '<br><br><br>' . STORE_NAME;

            $system_notification_emails = get_system_notification_emails('free_demo_enquiry_emails');
            $from_email = $email;
            $from_name = ucwords($first_name);
            $reply_to_email = $email;
            $reply_to_name = ucwords($first_name);
            $cc = null;
            $subject = 'New Demo Request';

            if (!empty($system_notification_emails)) {
                foreach ($system_notification_emails as $system_notification_email) {
                    $this->send_mail_with_cc($from_email, $system_notification_email['email'], $cc, $subject, $body, $from_name, $reply_to_email, $reply_to_name);
                }
            }
        }
    }

    function get_all_messages_total()
    {
        $this->db->select('*');
        //$this->db->where('admin_reply', '0');
        $this->db->order_by("sid", "desc");
        $data = $this->db->get('free_demo_requests');
        return $data->num_rows();
    }

    function get_all_messages($limit, $start)
    {
        $this->db->select('*');
        //$this->db->where('admin_reply', '0');
        $this->db->order_by("sid", "desc");
        $this->db->limit($limit, $start);
        $data = $this->db->get('free_demo_requests');
        return $data->result_array();
    }

    function get_unread_enquires_count()
    {
        $this->db->select('*');
        $this->db->where('admin_reply', '0');
        $this->db->where('status', '0');
        return $this->db->get('free_demo_requests')->num_rows();
    }

    function get_inbox_message_detail($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('free_demo_requests')->result_array();
    }

    public function mark_read($sid)
    {
        $data = array('status' => 1);
        $this->db->where('sid', $sid);
        $this->db->update('free_demo_requests', $data);
    }

    public function save_email_log($data)
    {
        $data['date'] = date('Y-m-d H:i:s');
        $data['admin'] = 'admin';
        $data['status'] = 'Delivered';
        $this->db->insert('email_log', $data);
    }

    public function insert_demo_request_note($data)
    {
        $this->db->insert('free_demo_requests_notes', $data);
    }

    public function get_demo_request_notes($user_sid, $user_type)
    {
        $this->db->select('free_demo_requests_notes.*');
        $this->db->select('administrator_users.first_name');
        $this->db->select('administrator_users.last_name');
        $this->db->where('free_demo_requests_notes.demo_sid', $user_sid);
        $this->db->where('free_demo_requests_notes.user_type', $user_type);
        $this->db->join('administrator_users', 'administrator_users.id = free_demo_requests_notes.created_by', 'left');
        $record_obj = $this->db->get('free_demo_requests_notes');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function delete_demo_request_note($user_sid, $user_type, $note_sid)
    {
        $this->db->where('demo_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('sid', $note_sid);
        $this->db->delete('free_demo_requests_notes');
    }

    public function delete_demo_request($user_sid, $user_type)
    {
        $this->db->where('sid', $user_sid);
        $this->db->delete('free_demo_requests');
        $this->db->where('demo_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->delete('free_demo_requests_notes');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->delete('free_demo_requests_schedules');
    }

    public function add_new_schedule_record($data)
    {
        $this->db->insert('free_demo_requests_schedules', $data);
    }

    public function get_schedule_records($user_sid, $user_type)
    {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->order_by('sid', 'ASC');
        $records_obj = $this->db->get('free_demo_requests_schedules');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function delete_schedule_record($schedule_sid)
    {
        $this->db->where('sid', $schedule_sid);
        $this->db->delete('free_demo_requests_schedules');
    }

    public function set_schedule_status($schedule_sid, $status = 'pending')
    {
        $this->db->where('sid', $schedule_sid);
        $data = array();
        $data['schedule_status'] = 'completed';
        $this->db->update('free_demo_requests_schedules', $data);
    }

    public function get_scheduled_tasks_for_cron($datetime)
    {
        $this->db->select('free_demo_requests_schedules.*');
        $this->db->select('administrator_users.first_name as created_by_first_name');
        $this->db->select('administrator_users.last_name as created_by_last_name');
        $this->db->select('administrator_users.email as created_by_email');
        $this->db->where('free_demo_requests_schedules.reminder_email_status', 0);
        $this->db->where('free_demo_requests_schedules.schedule_status', 'pending');
        $this->db->where('free_demo_requests_schedules.schedule_datetime', $datetime);
        $this->db->join('administrator_users', 'administrator_users.id = free_demo_requests_schedules.created_by', 'left');
        $records_obj = $this->db->get('free_demo_requests_schedules');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_email_trigger_status($schedule_sid)
    {
        $data_to_update = array();
        $data_to_update['reminder_email_status'] = 1;
        $data_to_update['reminder_email_triggered_date'] = date('Y-m-d H:i:s');
        $this->db->where('sid', $schedule_sid);
        $this->db->update('free_demo_requests_schedules', $data_to_update);
    }

    function set_demo_request_reply_status($demo_sid, $status = 1)
    {
        $data_to_update = array();
        $data_to_update['admin_reply'] = $status;
        $this->db->where('sid', $demo_sid);
        $this->db->update('free_demo_requests', $data_to_update);
    }

    function update_demo_request($demo_sid, $data)
    {
        $this->db->where('sid', $demo_sid);
        $this->db->update('free_demo_requests', $data);
    }

    function update_demo_request_notes($demo_sid, $data)
    {
        $this->db->where('sid', $demo_sid);
        $this->db->update('free_demo_requests_notes', $data);
    }

    function get_additional_contacts($sid)
    {
        $this->db->select('*');
        $this->db->where('demo_sid', $sid);
        $this->db->where('primary_person', 0);
        $records_obj = $this->db->get('free_demo_additional_contact');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_additional_pp_phone_number($sid)
    {
        $this->db->select('*');
        $this->db->where('demo_sid', $sid);
        $this->db->where('primary_person', 1);
        $this->db->where('contact_type', 'phone');
        $records_obj = $this->db->get('free_demo_additional_contact');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_additional_pp_email($sid)
    {
        $this->db->select('*');
        $this->db->where('demo_sid', $sid);
        $this->db->where('primary_person', 1);
        $this->db->where('contact_type', 'email');
        $records_obj = $this->db->get('free_demo_additional_contact');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function send_mail_with_cc($from, $to, $cc, $subject, $body, $fromName = NULL, $replyTo = NULL, $replyToName = null)
    {
        require_once(APPPATH . 'libraries/phpmailer/PHPMailerAutoload.php');
        $mail = new PHPMailer;

        if ($replyTo == NULL) {
            $mail->addReplyTo($from, $fromName);
        } else {
            $mail->addReplyTo($replyTo, $replyToName);
        }

        $mail->From = $from;
        $mail->FromName = $fromName;
        $mail->addAddress($to);
        $mail->addCC($cc);
        $mail->CharSet = 'UTF-8';
        //$mail->addBCC('prosaifhasi@gmail.com');
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        // attach the SMTP creds
        attachSMTPToMailer($mail, $from);
        $mail->send();
    }

    function save_demo_reply($data)
    {
        $this->db->insert('demo_enquiry_admin_reply', $data);
        return $this->db->insert_id();
    }

    function get_demo_reply($user_sid, $user_type)
    {
        $this->db->where('demo_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->order_by('reply_date', 'DESC');
        return $this->db->get('demo_enquiry_admin_reply')->result_array();
    }

    function get_reply_by_id($sid)
    {
        $this->db->where('sid', $sid);
        return $this->db->get('demo_enquiry_admin_reply')->result_array();
    }

    function update_contact_status($message_sid, $status)
    {
        $data_to_update = array();
        $data_to_update['contact_status'] = $status;

        $this->db->where('sid', $message_sid);
        $this->db->update('free_demo_requests', $data_to_update);
    }

    function get_additional_user_contact($sid)
    {
        $this->db->where('sid', $sid);
        return $this->db->get('free_demo_additional_contact')->result_array();
    }

    function edit_additional_user_contact($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('free_demo_additional_contact', $data_to_update);
    }

    function add_new_additional_contact($data_to_insert)
    {
        $this->db->insert('free_demo_additional_contact', $data_to_insert);
    }

    public function delete_additional_user_contact($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('free_demo_additional_contact');
    }

    function get_status_name($code)
    {
        $this->db->where('css_class', $code);
        $this->db->select('name');
        return $this->db->get('admin_status_bars')->result_array();
    }

    function get_all_status_name()
    {

        $this->db->select('admin_status_bars.name');
        $this->db->select('admin_status_bars.css_class');
        $this->db->join('free_demo_requests', 'admin_status_bars.css_class = free_demo_requests.contact_status', 'right');
        $record_obj = $this->db->get('admin_status_bars');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function check_reffer_affiliater($email)
    {
        $this->db->select('sid');
        $this->db->where('email', $email);
        return $this->db->count_all_results('free_demo_requests');
    }

    public function validate_affiliate_video_status($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $result = $this->db->get('demo_affiliate_configurations')->result_array();
        return $result;
    }

    /**
     * Fetch Admin templates from db
     *
     * @return Array|Bool
     */
    function fetch_admin_templates()
    {
        $result = $this->db
            ->select('
            sid AS id, name as templateName, subject, text as body
        ')
            ->from('email_templates')
            ->where('status', 1)
            ->where('group', 'super_admin_templates')
            ->order_by('name', 'ASC')
            ->get();
        $result_arr = $result->result_array();
        $result = $result->free_result();
        return $result_arr;
    }
}

<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Referred_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_referred_clients($limit, $start) {

        $this->db->select('free_demo_requests.*');
        $this->db->select('marketing_agencies.full_name');
        $this->db->where('free_demo_requests.is_reffered', 1);
        $this->db->join('marketing_agencies', 'marketing_agencies.sid = free_demo_requests.refferred_by_sid', 'left');
        $this->db->order_by('date_requested','DESC');
        $record_obj = $this->db->get('free_demo_requests');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_all_status_name() {

        $this->db->select('admin_status_bars.name');
        $this->db->select('admin_status_bars.css_class');
        $this->db->join('affiliations', 'admin_status_bars.css_class = affiliations.contact_status', 'right');
        $record_obj = $this->db->get('admin_status_bars');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_all_messages($limit, $start) {
        $this->db->select('*');
        $this->db->order_by("sid", "desc");
        $this->db->limit($limit, $start);
        $data = $this->db->get('free_demo_requests');
        return $data->result_array();
    }

    function get_all_messages_total() {
        $this->db->select('*');
        $this->db->order_by("sid", "desc");
        $data = $this->db->get('free_demo_requests');
        return $data->num_rows();
    }

    function get_unread_enquires_count() {
        $this->db->select('*');
        $this->db->where('admin_reply', '0');
        $this->db->where('status', '0');
        return $this->db->get('free_demo_requests')->num_rows();
    }

    function get_status() {
        $this->db->select('sid, name, css_class, active, status_order, status_type, bar_bgcolor');
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('admin_status_bars')->result_array();
    }

    function get_referral_reply($sid) {
        $this->db->where('affiliation_sid', $sid);
        $this->db->order_by('reply_date','DESC');
        return $this->db->get('affiliates_email_reply')->result_array();
    }

    function get_referral_client_reply($user_sid, $user_type) {
        $this->db->where('demo_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->order_by('reply_date','DESC');
        return $this->db->get('demo_enquiry_admin_reply')->result_array();
    }

    function delete_demo_request($user_sid, $user_type) {
        $this->db->where('sid', $user_sid);
        $this->db->delete('free_demo_requests');
        $this->db->where('demo_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->delete('free_demo_requests_notes');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->delete('free_demo_requests_schedules');
    }

    function get_inbox_message_user_detail($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('affiliations')->result_array();
    }

    function get_inbox_message_client_detail($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('free_demo_requests')->result_array();
    }

    public function get_demo_request_notes($demo_request_sid) {
        $this->db->select('affilates_notes.*');
        $this->db->select('administrator_users.first_name');
        $this->db->select('administrator_users.last_name');
        $this->db->where('affilates_notes.affiliate_sid', $demo_request_sid);
        $this->db->join('administrator_users', 'administrator_users.id = affilates_notes.created_by', 'left');
        $this->db->order_by('created_date','DESC');
        $record_obj = $this->db->get('affilates_notes');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function get_refer_request_notes($user_sid, $user_type) {
        $this->db->select('free_demo_requests_notes.*');
        $this->db->select('administrator_users.first_name');
        $this->db->select('administrator_users.last_name');
        $this->db->where('free_demo_requests_notes.demo_sid', $user_sid);
        $this->db->where('free_demo_requests_notes.user_type', $user_type);
        $this->db->join('administrator_users', 'administrator_users.id = free_demo_requests_notes.created_by', 'left');
        $this->db->order_by('created_date','DESC');
        $record_obj = $this->db->get('free_demo_requests_notes');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function change_status($id, $status) {
        $this->db->where('sid', $id);
        $this->db->update('affiliations', $status);
    }

    public function get_referred_user($sid) {
        $this->db->select('affiliations.*');
        $this->db->select('marketing_agencies.full_name');
        $this->db->where('affiliations.sid', $sid);
        $this->db->join('marketing_agencies', 'marketing_agencies.sid = affiliations.refferred_by_sid', 'left');
        $this->db->order_by('created_date','DESC');
        $record_obj = $this->db->get('affiliations');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function get_referred_client($sid) {
        $this->db->select('free_demo_requests.*');
        $this->db->select('marketing_agencies.full_name');
        $this->db->where('free_demo_requests.sid', $sid);
        $this->db->join('marketing_agencies', 'marketing_agencies.sid = free_demo_requests.refferred_by_sid', 'left');
        $this->db->order_by('created_date','DESC');
        $record_obj = $this->db->get('free_demo_requests');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_admin_status() {
        $this->db->select('sid, name, css_class, active, status_order, status_type, bar_bgcolor');
        $this->db->order_by('status_order', 'asc');
        return $this->db->get('admin_status_bars')->result_array();
    }

    function get_status_name($code)
    {
        $this->db->where('css_class', $code);
        $this->db->select('name');
        return $this->db->get('admin_status_bars')->result_array();
    }

    public function insert_referral_note($data) {
        $this->db->insert('affilates_notes', $data);
    }

    public function insert_referral_client_note($data) {
        $this->db->insert('free_demo_requests_notes', $data);
    }

    function update_referral_notes($affiliate_sid, $data) {
        $this->db->where('sid', $affiliate_sid);
        $this->db->update('affilates_notes', $data);
    }

    function update_referral_client_notes($refer_sid, $data) {
        $this->db->where('sid', $refer_sid);
        $this->db->update('free_demo_requests_notes', $data);
    }

    public function delete_referral_note($affiliate_sid, $note_sid) {
        $this->db->where('affiliate_sid', $affiliate_sid);
        $this->db->where('sid', $note_sid);
        $this->db->delete('affilates_notes');
    }

    public function delete_referral_client_note($user_sid, $user_type, $note_sid) {

        $this->db->where('demo_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('sid', $note_sid);
        $this->db->delete('free_demo_requests_notes');
    }

    public function delete_affilate_request($affiliate_sid) {
        $this->db->where('sid', $affiliate_sid);
        $this->db->delete('affiliations');
        $this->db->where('demo_sid', $affiliate_sid);
        $this->db->delete('affilates_notes');
    }

    public function get_referred_user_detail($sid) {
        $this->db->where('sid', $sid);
        $result = $this->db->get('free_demo_requests')->result_array();
        return $result;
    }

    function update_details($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('free_demo_requests', $data_to_update);
    }

    public function check_referral_user($email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $result = $this->db->get('free_demo_requests')->result_array();
        return $result;
    }

    public function get_referral_user_email($sid) {
        $this->db->select('email');
        $this->db->where('sid', $sid);
        $result = $this->db->get('free_demo_requests')->result_array();
        return $result;
    }

    function check_marketing_agency($email) {
        $this->db->where('email', $email);
        $result = $this->db->get('marketing_agencies')->num_rows();
        return $result;
    }

    function insert_marketing_agency($data_to_insert) {
        $this->db->insert('marketing_agencies', $data_to_insert);
        return $this->db->insert_id();
    }

    function update_contact_status($message_sid, $status) {
        $data_to_update = array();
        $data_to_update['contact_status'] = $status;
        $this->db->where('sid', $message_sid);
        $this->db->update('free_demo_requests', $data_to_update);
    }

    function save_email_log($data) {
        $data['date'] = date('Y-m-d H:i:s');
        $data['status'] = 'Delivered';
        $this->db->insert('email_log', $data);
    }

    function save_referral_reply($data) {
        $this->db->insert('demo_enquiry_admin_reply', $data);
        return $this->db->insert_id();
    }

    function get_reply_by_id($sid) {
        $this->db->where('sid', $sid);
        return $this->db->get('demo_enquiry_admin_reply')->result_array();
    }

    function get_referred_detail($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('free_demo_requests')->result_array();
    }

    function get_email_templates() {
        $this->db->select('sid, template_name');
        $this->db->where('template_code', 'super_admin');
        $this->db->where('company_sid', 1);
        return $this->db->get('portal_email_templates')->result_array();
    }

    function get_email_template_body($template_sid) {
        $this->db->select('message_body');
        $this->db->where('sid', $template_sid);
        $this->db->where('template_code', 'super_admin');
        $this->db->where('company_sid', 1);
        return $this->db->get('portal_email_templates')->result_array();
    }

    public function get_all_countries() {
        $this->db->select('country_name');
        $this->db->order_by('order', 'ASC');
        $result = $this->db->get('countries')->result_array();
        return $result;
    }

    public function add_new_schedule_record($data) {
        $this->db->insert('free_demo_requests_schedules', $data);
    }

    public function mark_read($sid) {
        $data = array('status' => 1);
        $this->db->where('sid', $sid);
        $this->db->update('free_demo_requests', $data);
    }

    public function get_schedule_records($user_sid, $user_type) {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->order_by('sid', 'ASC');
        $records_obj = $this->db->get('free_demo_requests_schedules');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function delete_schedule_record($schedule_sid) {
        $this->db->where('sid', $schedule_sid);
        $this->db->delete('free_demo_requests_schedules');
    }

    public function set_schedule_status($schedule_sid) {
        $this->db->where('sid', $schedule_sid);
        $data = array();
        $data['schedule_status'] = 'completed';
        $this->db->update('free_demo_requests_schedules', $data);
    }

}
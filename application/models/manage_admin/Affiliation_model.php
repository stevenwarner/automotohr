<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Affiliation_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_affiliations($limit, $start) {

        $this->db->select('*');
        $this->db->order_by("sid", "desc");
        $this->db->where('is_reffered', 0);
        $this->db->limit($limit, $start);
        $data = $this->db->get('affiliations');
        return $data->result_array();
    }

    public function get_affiliation($sid) {
        $this->db->where('sid', $sid);
        $result = $this->db->get('affiliations')->result_array();
        return $result;
    }

    public function change_status($id, $status) {
        $this->db->where('sid', $id);
        $this->db->update('affiliations', $status);
    }

    function insert_marketing_agency($data_to_insert) {
        $this->db->insert('marketing_agencies', $data_to_insert);
        return $this->db->insert_id();
    }

    function check_marketing_agency($email) {
        $this->db->where('email', $email);
        $result = $this->db->get('marketing_agencies')->num_rows();
        return $result;
    }
    
    function update_contact_status($message_sid, $status) {
        $data_to_update = array();
        $data_to_update['contact_status'] = $status;
        $this->db->where('sid', $message_sid);
        $this->db->update('affiliations', $data_to_update);
    }
    
    function update_details($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('affiliations', $data_to_update);
    }

    function get_affiliate_details($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('affiliations')->result_array();
    }

    function save_email_log($data) {
        $data['date'] = date('Y-m-d H:i:s');
        $data['status'] = 'Delivered';
        $this->db->insert('email_log', $data);
    }

    function save_affiliates_reply($data) {
        $this->db->insert('affiliates_email_reply', $data);
        return $this->db->insert_id();
    }

    function get_affiliate_reply($sid) {
        $this->db->where('affiliation_sid', $sid);
        $this->db->order_by('reply_date','DESC');
        return $this->db->get('affiliates_email_reply')->result_array();
    }

    function get_reply_by_id($sid) {
        $this->db->where('sid', $sid);
        return $this->db->get('affiliates_email_reply')->result_array();
    }

    function get_inbox_message_detail($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        return $this->db->get('affiliations')->result_array();
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

    public function insert_affilate_note($data) {
        $this->db->insert('affilates_notes', $data);
    }

    function update_affilate_notes($affiliate_sid, $data) {
        $this->db->where('sid', $affiliate_sid);
        $this->db->update('affilates_notes', $data);
    }

    public function delete_affilate_note($affiliate_sid, $note_sid) {
        $this->db->where('affiliate_sid', $affiliate_sid);
        $this->db->where('sid', $note_sid);
        $this->db->delete('affilates_notes');
    }

    public function delete_affilate_request($affiliate_sid) {
        $this->db->where('sid', $affiliate_sid);
        $this->db->delete('affiliations');
        $this->db->where('demo_sid', $affiliate_sid);
        $this->db->delete('affilates_notes');
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

    function get_all_status_name() {

        $this->db->select('admin_status_bars.name');
        $this->db->select('admin_status_bars.css_class');
        $this->db->join('affiliations', 'admin_status_bars.css_class = affiliations.contact_status', 'right');
        $record_obj = $this->db->get('admin_status_bars');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    public function get_all_referred_affiliates($limit, $start) {

        $this->db->select('affiliations.*');
        $this->db->select('marketing_agencies.full_name');
        $this->db->where('affiliations.is_reffered', 1);
        $this->db->join('marketing_agencies', 'marketing_agencies.sid = affiliations.refferred_by_sid', 'left');
        $this->db->order_by('request_date','DESC');
        $record_obj = $this->db->get('affiliations');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_all_messages_total() {
        $this->db->select('*');
        $this->db->order_by("sid", "desc");
        $data = $this->db->get('free_demo_requests');
        return $data->num_rows();
    }

    public function get_all_referred_clients($limit, $start) {

        $this->db->select('client_refer_by_affiliate.*');
        $this->db->select('marketing_agencies.full_name');
        $this->db->where('client_refer_by_affiliate.is_reffered', 1);
        $this->db->join('marketing_agencies', 'marketing_agencies.sid = client_refer_by_affiliate.refferred_by_sid', 'left');
        $this->db->order_by('created_date','DESC');
        $record_obj = $this->db->get('client_refer_by_affiliate');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
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

    public function get_referral_user_email($sid) {
        $this->db->select('email');
        $this->db->where('sid', $sid);
        $result = $this->db->get('affiliations')->result_array();
        return $result;
    }

    public function check_referral_user($email) {
        $this->db->select('sid');
        $this->db->where('email', $email);
        $result = $this->db->get('affiliations')->result_array();
        return $result;
    }

    public function add_new_schedule_record($data) {
        $this->db->insert('free_demo_requests_schedules', $data);
    }

    public function get_schedule_records($user_sid) {
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', 'affiliate_user');
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


    /**
     * Fetch Admin templates from db
     *
     * @return Array|Bool
     */
    function fetch_admin_templates(){
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
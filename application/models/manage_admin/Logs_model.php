<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class logs_model extends CI_Model
{
    public function get_contact_logs($search)
    {
        $this->db->where($search);
        $this->db->order_by("sid", "desc");
        $data = $this->db->get('contactus_log');
        return $data->result_array();
    }

    public function get_contact_logs_date($search, $between)
    {
        $this->db->where($search);
        $this->db->where($between);
        $data = $this->db->get('contactus_log');
        return $data->result_array();
    }

    public function get_contactus_log_detail($log_id)
    {
        $this->db->where('sid', $log_id);
        $data = $this->db->get('contactus_log');
        return $data->result_array();
    }

    public function get_email_enquiries_logs($user_name, $email, $start_date, $end_date, $limit = null, $offset = null, $count_only = false, $name_search = 'all')
    {
        $this->db->select('email_log.*');

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('email_log.date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('email_log.date >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('email_log.date <=', $end_date);
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if (!empty($user_name) && $user_name != 'all') {
            $this->db->like('email_log.username', $user_name);
        }

        if (!empty($email) && $email != 'all') {
            $this->db->like('email_log.email', $email);
        }

        if (!empty($name_search) && $name_search != 'all') {
            $this->db->like('email_log.message', $name_search);
        }

        $this->db->order_by('date', 'DESC');

        if ($count_only == true) {
            $this->db->from('email_log');
            return $this->db->count_all_results();
        } else {
            $this->db->from('email_log');
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            //            echo $this->db->last_query();

            foreach ($records_arr as $key => $record) {
                $username = $record['username'];

                if (intval($username) > 0) {
                    $this->db->select('first_name');
                    $this->db->select('last_name');
                    $this->db->where('sid', $username);
                    $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                    $user_obj = $this->db->get('users');
                    $user_arr = $user_obj->result_array();
                    $user_obj->free_result();

                    if (!empty($user_arr)) {
                        $user_arr = $user_arr[0];
                        $username = $user_arr['first_name'] . ' ' . $user_arr['last_name'];
                    } else {
                        $username = 'Administrator';
                    }
                } else if (empty($username)) {
                    $username = 'Administrator';
                }

                $records_arr[$key]['username'] = ucwords($username);
            }

            return $records_arr;
        }
    }
    //SMS Enquiries log
    public function get_sms_enquiries_logs($sender, $status = "all", $email, $start_date, $end_date, $limit = null, $offset = null, $count_only = false, $name_search = 'all')
    {
        $this->db->select('portal_sms_log.*');
        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_sms_log.created_at BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('portal_sms_log.created_at >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('portal_sms_log.created_at <=', $end_date);
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if (!empty($name_search) && $name_search != 'all') {
            $this->db->like('portal_sms_log.user_name', $name_search);
        }
        if ($status != '' && $status != 'all') {
            $this->db->like('portal_sms_log.is_sent', $status);
        }

        if (!empty($sender) && $sender != 'all') {
            $this->db->like('portal_sms_log.sender_phone_number', $sender);
        }

        if (!empty($email) && $email != 'all') {
            $this->db->like('portal_sms_log.user_email_address', $email);
        }

        $this->db->order_by('created_at', 'DESC');

        if ($count_only == true) {
            $this->db->from('portal_sms_log');
            return $this->db->count_all_results();
        } else {
            $this->db->from('portal_sms_log');
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            return $records_arr;
        }
    }

    public function get_notification_email_logs($from, $to_email, $start_date, $end_date, $limit = null, $offset = null, $count_only = false)
    {
        $this->db->select('*');

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('sent_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('sent_date >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('sent_date <=', $end_date);
        }

        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }

        if (!empty($to_email) && $to_email != 'all') {
            $this->db->like('receiver', $to_email);
        }

        if (!empty($from) && $from != 'all') {
            $this->db->like('message', $from);
        }

        $this->db->order_by('sent_date', 'DESC');

        if ($count_only == true) {
            $this->db->from('notifications_emails_log');
            $count = $this->db->count_all_results();
            return $count;
        } else {
            $this->db->from('notifications_emails_log');
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            //
            return $records_arr;
        }
    }

    public function get_email_logs($limit, $start, $search)
    {
        $this->db->select('*,email_log.sid as email_sid');
        //$this->db->join('users', 'users.sid = email_log.username');
        $this->db->where($search);
        $this->db->order_by("email_log.sid", "desc");
        $this->db->limit($limit, $start);
        $data = $this->db->get('email_log');
        return $data->result_array();
    }

    public function get_email_log($search, $between = '')
    {
        $this->db->select('*,email_log.sid as email_sid');
        $this->db->where($search);
        if (isset($between) && $between != '') {
            $this->db->where($between);
        }
        $this->db->order_by("email_log.sid", "desc");
        $data = $this->db->get('email_log');

        return $data->num_rows();
    }

    public function get_email_logs_date($limit, $start, $search, $between)
    {
        $this->db->select('*,email_log.sid as email_sid');
        //$this->db->join('users', 'users.sid = email_log.username');
        $this->db->where($search);
        $this->db->where($between);
        $this->db->order_by("email_log.sid", "desc");
        $this->db->limit($limit, $start);
        $data = $this->db->get('email_log');
        return $data->result_array();
    }

    public function get_email_log_detail($log_id)
    {
        //$this->db->join('users', 'users.sid = email_log.username');
        $this->db->query("SET NAMES 'utf8mb4'");
        $this->db->where('email_log.sid', $log_id);
        $data = $this->db->get('email_log');
        return $data->result_array();
    }
    public function get_sms_log_detail($log_id)
    {
        //$this->db->join('users', 'users.sid = email_log.username');
        $this->db->where('portal_sms_log.sid', $log_id);
        $data = $this->db->get('portal_sms_log');
        return $data->result_array();
    }

    public function get_notification_email_log_detail($log_id)
    {
        //$this->db->join('users', 'users.sid = email_log.username');
        $this->db->where('notifications_emails_log.sid', $log_id);
        $data = $this->db->get('notifications_emails_log');
        return $data->result_array();
    }

    public function get_username_by_sid($sid)
    {
        $this->db->select('username');
        $this->db->where('sid', $sid);
        $data = $this->db->get('users');
        return $data->result_array();
    }

    public function update_resend_status($edit_id)
    {
        $update_data = array(
            'resend_flag'   =>  1,
            'resend_date'   =>  date('Y-m-d H:i:s')
        );
        $this->db->where('sid', $edit_id);
        $this->db->update('email_log', $update_data);
    }
    public function get_company_sms_info()
    {
        $this->db->select('portal_company_sms_module.phone_number,users.CompanyName');
        $this->db->join('users', 'users.sid=portal_company_sms_module.company_sid');
        return $this->db->get('portal_company_sms_module')->result_array();
    }
    public function get_company_sms_enquiries_logs()
    {
        $this->db->select('portal_sms_log.*');
        return $this->db->get('portal_sms_log')->result_array();
    }
    public function insert_module_data($data)
    {
    }
    public function get_module_data($module_name, $is_disabled, $is_ems_module, $stage, $limit = null, $offset = null, $count_only = false)
    {
        if ($limit !== null && $offset !== null) {
            $this->db->limit($limit, $offset);
        }
        if (!empty($module_name) && $module_name != 'all') {
            $this->db->where('module_name', $module_name);
        }
        if (!empty($is_disabled) && $is_disabled != 'all') {
            $this->db->where('is_disabled', $is_disabled);
        }
        if (!empty($is_ems_module) && $is_ems_module != 'all') {
            $this->db->where('is_ems_module', $is_ems_module);
        }
        if (!empty($stage) && $stage != 'all') {
            $this->db->where('stage', $stage);
        }
        if ($count_only == true) {
            $count = $this->db->count_all_results('modules');
            return $count;
        } else {
            $query = $this->db->get('modules');
            return $query->result_array();
        }
    }
    public function get_specific_module_data($sid)
    {
        $this->db->where('sid', $sid);
        $query = $this->db->get('modules');
        return $query->result_array();
    }
    public function update_module_data($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('modules', $data);
    }
    public function get_all_companies($moduleId)
    {
        $a = $this->db
            ->select('sid, CompanyName, "0" as status, ssn')
            ->where('parent_sid', 0)
            ->where('active', 1)
            ->order_by('CompanyName', 'ASC')
            ->get('users');
        //
        $b = $a->result_array();
        $a->free_result();
        foreach ($b as $key => $value) {
            if (
                $this->db
                ->where('company_sid', $value['sid'])
                ->where('module_sid', $moduleId)
                ->where('is_active', 1)
                ->count_all_results('company_modules')
            ) {
                // Active
                $b[$key]['status'] = 1;
            }
        }
        return $b;
    }

    function getModuleInfo($sid){
        return $this->db->select('sid, module_name')
        ->where('sid', $sid)
        ->get('modules')
        ->row_array();
    }

    function get_all_active_companies() {
        $result = $this->db->query("SELECT `sid`, `CompanyName` FROM `users` WHERE `parent_sid` = '0' AND `career_site_listings_only` = 0 AND `active` = '1' AND (`expiry_date` > '2016-04-20 13:26:27' OR `expiry_date` IS NULL)")->result_array();
        if (count($result) > 0) {
            //
            return $result;
        } else {
            return array();
        }
    }

    function get_company_module_status ($company_sid, $module_sid) {
        $this->db->select('is_active');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('module_sid', $module_sid);
        $record_obj = $this->db->get('company_modules');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr["is_active"];
        } else {
            return 0;
        }
    }

    function UpdateCompanyData($companyId, $oldStatus){
        //
        if(
            !$this->db
            ->where('company_sid', $companyId)
            ->where('module_sid', 7)
            ->count_all_results('company_modules')
        ){
            $this->db->insert(
                'company_modules', [
                    'module_sid' => 7,
                    'company_sid' => $companyId,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                    'updated_at' => date('Y-m-d H:i:s', strtotime('now'))
                ]
            );
        }
        //
        $this->db
            ->where('company_sid', $companyId)
            ->where('module_sid', 7)
            ->update(
                'company_modules', [
                    'is_active' => $oldStatus ? 0 : 1,
                    'updated_at' => date('Y-m-d H:i:s', strtotime('now'))
                ]
            );
    }

    function getCompanyPayrollInfo($company_sid){
        return $this->db
        ->select('refresh_token, access_token, gusto_company_uid')
        ->where('company_sid', $company_sid)
        ->get("payroll_companies")
        ->row_array();
    }
}

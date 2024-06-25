<?php

class Users_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function login($username, $password)
    {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('username', $username);
        $this->db->where('password', MD5($password));
        $this->db->limit(1);
        $executive_query = $this->db->get();
        //echo $this->db->last_query();
        if ($executive_query->num_rows() == 1) {
            $executive_users = $executive_query->result_array();
            $status = $executive_users[0]['active']; // check the status whether the account is active or inactive

            if ($status == 1) {
                $data['status'] = 'active';
                $data['executive_user'] = $executive_users[0];
            } else {
                $data['status'] = 'inactive';
            }
        } else {
            $data['status'] = 'not_found';
        }
        return $data;
    }

    function get_executive_users_companies($executive_admin_sid = NULL, $keyword = NULL)
    {
        if ($executive_admin_sid == NULL || $executive_admin_sid == 0) {
            $this->session->set_flashdata('message', '<b>Error:</b> No Company Found!');
            redirect(base_url('dashboard'), "refresh");
        }

        $this->db->select('t1.sid');
        $this->db->select('t1.executive_admin_sid');
        $this->db->select('t1.company_sid');
        $this->db->select('t1.logged_in_sid');
        $this->db->select('t2.CompanyName as company_name');
        $this->db->select('t2.PhoneNumber');
        $this->db->select('t2.Location_Address');

        $this->db->select('t2.company_status');
        $this->db->select('t3.sub_domain as company_website');
        $this->db->from('executive_user_companies as t1');
        $this->db->join('users as t2', 't2.sid = t1.company_sid', 'left');
        $this->db->join('portal_employer as t3', 't3.user_sid = t1.company_sid', 'left');
        $this->db->where('t1.executive_admin_sid', $executive_admin_sid);
        $this->db->where('t2.active', 1);

        if ($keyword != NULL || $keyword != '') {
            $this->db->like('t2.CompanyName', $keyword);
        }

        $this->db->order_by('t2.sid', 'desc');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function check_user($username)
    {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('username', $username);
        $this->db->limit(1);
        $executive_query = $this->db->get();

        if ($executive_query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function check_email($email)
    {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $executive_query = $this->db->get();

        if ($executive_query->num_rows() > 0) {
            return false;
        } else {
            return true;
        }
    }

    function check_company($admin_id, $company_id)
    {
        $this->db->select('*');
        $this->db->from('executive_user_companies');
        $this->db->where('executive_admin_sid', $admin_id);
        $this->db->where('company_sid', $company_id);
        $this->db->limit(1);
        $result = $this->db->get();

        if ($result->num_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function save_email_logs($data)
    {
        $this->db->insert('email_log', $data);
    }

    function varification_key($user_email, $random_string)
    {
        $this->db->where('email', $user_email);
        $data = array('activation_code' => $random_string);
        $this->db->update('executive_users', $data);
    }

    function varification_user_key($user_name, $random_key)
    {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('username', $user_name);
        $this->db->where('activation_code', $random_key);
        $data = $this->db->get();

        if ($data->num_rows() > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function change_password($password, $user, $key)
    {
        $data = array('password' => $password);
        $this->db->where('username', $user);
        $this->db->where('activation_code', $key);
        $this->db->update('executive_users', $data);
    }

    function reset_key($user)
    {
        $data = array('activation_code' => NULL);
        $this->db->where('username', $user);
        $this->db->update('executive_users', $data);
    }

    function email_user_data($email)
    {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('email', $email);
        $this->db->limit(1);
        $query_result = $this->db->get();

        if ($query_result->num_rows() > 0) {
            return $row = $query_result->row_array();
        }
    }

    function username_user_data($user)
    {
        $this->db->select('*');
        $this->db->from('executive_users');
        $this->db->where('username', $user);
        $this->db->limit(1);
        $query_result = $this->db->get();

        if ($query_result->num_rows() > 0) {
            return $row = $query_result->row_array();
        }
    }

    function user_data_by_id($sid)
    {
        $this->db->from('executive_users');
        $this->db->where('sid', $sid);
        $this->db->limit(1);
        $query_result = $this->db->get();

        if ($query_result->num_rows() > 0) {
            $row = $query_result->row_array();
            return $row;
        }
    }

    function get_company_employees($company_id)
    {
        $this->db->select('sid, first_name, last_name, username, email, registration_date, active');
        $this->db->from('users');
        $this->db->where('parent_sid', $company_id);
        $employees = $this->db->get();

        $all_employees =  $employees->result_array();

        $this->GetEmployeeStatus($all_employees, 1);
        return $all_employees;
    }


    private function GetEmployeeStatus(&$employees, $status = 1)
    {
        //
        if (empty($employees)) {
            return false;
        }
        //
        $employeeIds = array_column($employees, 'sid');
        //
        $statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire,termination_reason')
            ->where_in('employee_sid', $employeeIds)
            ->where('employee_status', $status)
            ->get('terminated_employees')
            ->result_array();
        //
        $last_statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire, employee_status,termination_reason')
            ->where_in('employee_sid', $employeeIds)
            ->order_by('terminated_employees.sid', 'DESC')
            ->get('terminated_employees')
            ->result_array();
        //
        if (!empty($statuses)) {
            //
            $tmp = [];
            //
            foreach ($statuses as $stat) {
                //
                $tmp[$stat['employee_sid']] = $stat;
            }
            //
            $statuses = $tmp;
            //
            $tmp = [];
            //
            foreach ($last_statuses as $stat) {
                //
                if (!isset($tmp[$stat['employee_sid']])) {
                    $tmp[$stat['employee_sid']] = $stat;
                }
            }
            //
            $last_statuses = $tmp;
            //
            unset($tmp);
        }
        //
        foreach ($employees as $index => $employee) {
            //
            $employees[$index]['last_status'] = isset($statuses[$employee['sid']]) ? $statuses[$employee['sid']] : [];
            $employees[$index]['last_status_2'] = isset($last_statuses[$employee['sid']]) ? $last_statuses[$employee['sid']] : [];
            $employees[$index]['last_status_text'] = isset($last_statuses[$employee['sid']]) ? GetEmployeeStatusText($last_statuses[$employee['sid']]['employee_status']) : '';
        }
        //
        return true;
    }


    function get_admin_invoices($company_sid = null, $invoice_status = 'active')
    {
        $this->db->select('*');
        $this->db->order_by('sid', 'DESC');

        if ($company_sid != null) {
            $this->db->where('company_sid', $company_sid);
        }
        $this->db->where('invoice_status', $invoice_status);
        $invoices = $this->db->get('admin_invoices')->result_array();

        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $this->db->select('item_name');
                $this->db->where('invoice_sid', $invoice['sid']);
                $invoice_item_names = $this->db->get('admin_invoice_items')->result_array();

                if (!empty($invoice_item_names)) {
                    $invoices[$key]['item_names'] = $invoice_item_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }

        return $invoices;
    }

    public function get_admin_marketplace_invoices($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        $invoices = $this->db->get('invoices')->result_array();

        if (!empty($invoices)) {
            foreach ($invoices as $key => $invoice) {
                $items_ids = $invoice['product_sid'];

                $this->db->select('name');
                $this->db->where('sid IN ( ' . $items_ids . ' ) ');
                $items_names = $this->db->get('products')->result_array();

                if (!empty($items_names)) {
                    $invoices[$key]['item_names'] = $items_names;
                } else {
                    $invoices[$key]['item_names'] = array();
                }
            }
        }

        return $invoices;
    }

    public function get_parent_company($company_sid)
    {
        $this->db->select('*');
        $this->db->where('is_primary_admin', 1);
        $this->db->where('parent_sid', $company_sid);
        $admin = $this->db->get('users')->result_array();

        if (empty($admin)) {
            $this->db->select('*');
            $this->db->limit(1);
            $this->db->where('sid', intval($company_sid) + 1);
            $this->db->where('parent_sid', $company_sid);
            $admin = $this->db->get('users')->result_array();
        }

        return $admin;
    }

    function get_company_details($company_id)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('sid', $company_id);
        $company = $this->db->get();
        return $company->result_array();
    }

    function get_company_exists($company_id)
    {
        $this->db->select('sid');
        $this->db->where('sid', $company_id);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_portal_details($company_id)
    {
        $this->db->select('*');
        $this->db->from('portal_employer');
        $this->db->where('user_sid', $company_id);
        $this->db->limit(1);
        $portal = $this->db->get()->result_array();

        if (!empty($portal)) {
            return $portal[0];
        } else {
            return array();
        }
    }

    function check_key($key)
    {
        $this->db->select('username');
        $this->db->from('executive_users');
        $this->db->where('salt', $key);
        $this->db->limit(1);
        $query_result = $this->db->get()->result_array();
        if (sizeof($query_result) > 0) {
            return $query_result[0]['username'];
        } else {
            return 'not_found';
        }
    }

    function updatePass($password, $key, $username)
    {
        $data = array('password' => MD5($password), 'salt' => NULL);
        $this->db->where('salt', $key);
        $this->db->where('username', $username);
        $this->db->update('executive_users', $data);
        //        $update_id = $this->db->affected_rows();
        //        if($update_id){
        //            $this->db->select('*');
        //            $this->db->from('executive_users');
        //            $this->db->where('id', $update_id);
        //            $this->db->limit(1);
        //            $executive_query = $this->db->get();
        //            //echo $this->db->last_query();
        //            if ($executive_query->num_rows() == 1) {
        //                $executive_users = $executive_query->result_array();
        //                $status = $executive_users[0]['active']; // check the status whether the account is active or inactive
        //
        //                if ($status == 1) {
        //
        //                    $data['status'] = 'active';
        //                    $data['executive_user'] = $executive_users[0];
        //                } else {
        //                    $data['status'] = 'inactive';
        //                }
        //            }
        //        }
        //        else
        //            return false;
    }

    function update_executive_admin_profile($sid, $data)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->update('executive_users', $data);

        if ($result) { // record is updated, Update all companies accounts + session.
            $this->db->select('logged_in_sid');
            $this->db->where('executive_admin_sid', $sid);
            $record_obj = $this->db->get('executive_user_companies');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $data_to_update = array(
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'timezone' => $data['timezone'],
                    'PhoneNumber' => $data['direct_business_number'],
                    'job_title' => $data['job_title'],
                    'YouTubeVideo' => $data['video'],
                    'user_shift_minutes' => $data['shift_mins'],
                    'user_shift_hours' => $data['shift_hours']
                );


                if (isset($data['profile_picture'])) {  //profile_picture
                    $data_to_update['profile_picture'] = $data['profile_picture'];
                }

                foreach ($record_arr as $users) {
                    $this->db->where('sid', $users['logged_in_sid']);
                    $this->db->update('users', $data_to_update);
                }
            } // update session informtion.

            $this->db->select('*');
            $this->db->where('sid', $sid);
            $record_obj = $this->db->get('executive_users');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            $session_data = array();
            $session_data['status'] = 'active';
            $session_data['executive_user'] = $record_arr[0];
            $this->session->set_userdata('executive_loggedin', $session_data);
            return 'success';
        } else {
            return 'error';
        }
    }

    function change_login_cred($password, $id)
    {
        $data = array('password' => $password);
        $this->db->where('sid', $id);
        $this->db->update('executive_users', $data);
    }

    function get_employee_email($sid)
    {
        $this->db->select('email');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $email = null;

        if (!empty($records_arr)) {
            $email = $records_arr[0]['email'];
        }
        return $email;
    }

    function getExecutiveCompanies($executiveUserSid)
    {
        // Get companies belong to executive admin
        $result =
            $this->db
            ->select('company_sid')
            ->from('executive_user_companies')
            ->where('executive_admin_sid', $executiveUserSid)
            ->get();
        //
        $companySids = $result->result_array();
        $result = $result->free_result();
        //
        if (!sizeof($companySids)) return array();
        //
        $ids = '';
        foreach ($companySids as $k0 => $v0) $ids .= $v0['company_sid'] . ',';
        return rtrim($ids, ',');
    }

    /**
     *
     *
     */
    function getSearchedUsersCount($executiveUserSid, $executiveCompanyIds, $query)
    {
        //
        $phoneQuery = preg_match('/@/', $query) ? '' : preg_replace('/[^0-9]/', '', $query);
        // Get Employees
        $employeeCount = $this->db
            ->from('users')
            ->join('users as company', 'company.sid = users.parent_sid', 'left')
            ->group_start()
            ->like('users.first_name', $query)
            ->or_like('users.last_name', $query)
            ->or_like('users.email', $query);
        if ($phoneQuery) {
            $this->db->or_like('REGEXP_REPLACE(users.PhoneNumber,"[^0-9]","")', $phoneQuery, false);
        }
        $this->db->group_end()
            ->where_in('users.parent_sid', $executiveCompanyIds, false)
            ->count_all_results();

        // Get Applicants
        $applicantCount = $this->db
            ->from('portal_job_applications')
            ->join('users', 'users.sid = portal_job_applications.employer_sid', 'left')
            ->group_start()
            ->like('portal_job_applications.first_name', $query)
            ->or_like('portal_job_applications.last_name', $query)
            ->or_like('portal_job_applications.email', $query);
        if ($phoneQuery) {
            $this->db->or_like('REGEXP_REPLACE(portal_job_applications.phone_number,"[^0-9]","")', $phoneQuery, false);
        }
        $this->db->group_end()
            ->where_in('portal_job_applications.employer_sid', $executiveCompanyIds, false)
            ->where('portal_job_applications.hired_sid IS NULL', NULL)
            ->count_all_results();
        //
        return $employeeCount + $applicantCount;
    }

    /**
     *
     *
     */
    function getSearchedUsers(
        $executiveUserSid,
        $executiveCompanyIds,
        $query,
        $inset,
        $offset,
        $limit
    ) {
        $phoneQuery = preg_match('/@/', $query) ? '' : preg_replace('/[^0-9]/', '', $query);
        // Get Employees
        $this->db
            ->select('
            users.sid,
            users.applicant_sid as applicant_sid,
            users.job_title as last_job_title,
            users.email as user_email,
            DATE_FORMAT(users.registration_date,"%m-%d-%Y") as user_registration_date,
            company.CompanyName as company_name,
            users.parent_sid as company_sid,
            users.active as is_active,
            users.archived as is_archived,
            users.terminated_status as is_terminated,
            users.first_name,
            users.last_name,
            users.middle_name,
            users.timezone,
            users.access_level,
            users.access_level_plus,
            users.pay_plan_flag,
            users.PhoneNumber,
            users.is_executive_admin,
            "employee" as user_type,
            "0" as job_count
        ')
            ->from('users')
            ->join('users as company', 'company.sid = users.parent_sid', 'left')
            ->group_start()
            ->like('LOWER(CONCAT(users.first_name," ",users.last_name))', strtolower(urldecode($query)))
            ->or_like('users.email', $query);
        if ($phoneQuery) {
            $this->db->or_like('REGEXP_REPLACE(users.PhoneNumber,"[^0-9]","")', $phoneQuery, false);
            $this->db->or_like('REGEXP_REPLACE(users.PhoneNumber,"[^0-9]","")', '1' . $phoneQuery, false);
            $this->db->or_like('REGEXP_REPLACE(users.PhoneNumber,"[^0-9]","")', '+1' . $phoneQuery, false);
        }
        $this->db->group_end()
            ->limit($offset, $inset)
            ->order_by('first_name', 'ASC')
            ->where_in('users.parent_sid', $executiveCompanyIds, false);
        //
        $result = $this->db->get();
        //
        $employees = $result->result_array();
        $result = $result->free_result();

        // Get Applicants
        $result = $this->db
            ->select('
            "0" as sid,
            concat(portal_job_applications.first_name," ",portal_job_applications.last_name) as user_name,
            portal_job_applications.email as user_email,
            "" as last_job_title,
            portal_job_applications.sid as applicant_sid,
            DATE_FORMAT(portal_job_applications.date_applied,"%m-%d-%Y") as user_registration_date,
            users.CompanyName as company_name,
            portal_job_applications.employer_sid as company_sid,
            portal_job_applications.archived as is_archived,
            portal_job_applications.phone_number as PhoneNumber,
            "applicant" as user_type,
            "0" as job_count
        ')
            ->from('portal_job_applications')
            ->join('users', 'users.sid = portal_job_applications.employer_sid', 'left')
            ->group_start()
            ->like('LOWER(CONCAT(portal_job_applications.first_name," ",portal_job_applications.last_name))', strtolower(urldecode($query)))
            ->or_like('portal_job_applications.email', $query);
        if ($phoneQuery) {
            $this->db->or_like('REGEXP_REPLACE(portal_job_applications.phone_number,"[^0-9]","")', $phoneQuery, false);
        }
        $result = $this->db->group_end()
            ->limit($offset, $inset)
            ->order_by('user_name', 'ASC')
            ->where_in('portal_job_applications.employer_sid', $executiveCompanyIds, false)
            ->where('portal_job_applications.hired_sid IS NULL', NULL)
            ->get();
        //
        $applicants = $result->result_array();
        $result = $result->free_result();
        //
        if (sizeof($applicants)) {
            foreach ($applicants as $k0 => $v0) {
                $applicants[$k0]['job_count'] = 0;
                if ($v0['last_job_title'] != '' && $v0['last_job_title'] != null) continue;

                $result = $this->db
                    ->select('
                    portal_applicant_jobs_list.desired_job_title,
                    portal_job_listings.Title
                ')
                    ->from('portal_applicant_jobs_list')
                    ->where('portal_applicant_jobs_list.portal_job_applications_sid', $v0['applicant_sid'])
                    ->join('portal_job_listings', 'portal_applicant_jobs_list.job_sid = portal_job_listings.sid', 'left')
                    ->get();
                //
                $jobs = $result->result_array();
                $result = $result->free_result();

                $applicants[$k0]['jobs'] = $jobs;
                $applicants[$k0]['job_count'] = sizeof($jobs);
            }
        }
        //
        if (!empty($employees)) {
            foreach ($employees as $index => $value) {
                $employees[$index]['user_name'] = remakeEmployeeName($value);
            }
        }
        //
        $all_employees = array_merge_recursive($applicants, $employees);
        $this->GetEmployeeStatus($all_employees, 1);
        return $all_employees;
        //return array_merge_recursive($applicants, $employees);
    }

    //
    function assigned_incidents_count($employee_id,$company_id)
    {
        $this->db->select('sid');
        $this->db->where('employer_sid', $employee_id);
        $this->db->where('company_sid', $company_id);
        $this->db->where('incident_status', 'pending');
        $this->db->from('incident_assigned_emp');

        return $this->db->count_all_results();
    }

    function checkExecutiveAdmin($email)
    {
        if (
            $this->db
            ->where('email', $email)
            ->where('active', 1)
            ->count_all_results('executive_users')
        ) {
            return 'yes';
        } else {
            return 'no';
        }
    }
}

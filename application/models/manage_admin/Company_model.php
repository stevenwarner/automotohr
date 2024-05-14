<?php
class Company_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_companies($limit = NULL, $start = NULL, $search = array(), $status = 'all')
    {
        $this->db->select('*');
        $this->db->from('users');

        if ($limit != NULL && $start != NULL) {
            $this->db->limit($limit, $start);
        }

        if (strtolower($status) != 'all') {
            $this->db->where('active', $status);
        }

        $this->db->where('parent_sid', 0);
        $this->db->where($search);
        $this->db->where('is_paid', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by("sid", "desc");
        $record_obj = $this->db->get();
        $result = $record_obj->result_array();
        $record_obj->free_result();
        return $result;
    }

    function total_companies($search = array())
    {
        foreach ($search as $key => $value) {
            if ($key != 'is_paid') {
                $this->db->like($key, $value);
                unset($search[$key]);
            }
        }
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('parent_sid', 0);
        //        $this->db->where('is_paid', 1);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where($search);
        return $this->db->get()->num_rows();
    }

    function get_all_companies_date($contact_name, $company_name, $company_type, $company_status, $start_date, $end_date, $limit = null, $start = null, $columns = '*')
    {
        if (!empty($contact_name) && $contact_name != 'all') {
            $this->db->like('ContactName', $contact_name);
        }
        if (!empty($company_name) && $company_name != 'all') {
            $this->db->like('CompanyName', $company_name);
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('registration_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('registration_date >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('registration_date <=', $end_date);
        }
        if ($limit !== null && $start !== null) {
            $this->db->limit($limit, $start);
        }

        $this->db->select($columns);
        $this->db->from('users');

        if ($company_status != 'all') {
            $this->db->where('active', $company_status);
        }

        $this->db->where('is_paid', $company_type);
        $this->db->where('parent_sid', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->order_by('sid', 'desc');

        $record_obj = $this->db->get();
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function total_companies_date($contact_name, $company_name, $company_type, $company_status, $start_date, $end_date)
    {
        if (!empty($contact_name) && $contact_name != 'all') {
            $this->db->like('ContactName', $contact_name);
        }
        if (!empty($company_name) && $company_name != 'all') {
            $this->db->like('CompanyName', $company_name);
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('registration_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('registration_date >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('registration_date <=', $end_date);
        }

        $this->db->select('*');
        $this->db->from('users');

        if ($company_status != 'all') {
            $this->db->where('active', $company_status);
        }

        $this->db->where('is_paid', $company_type);
        $this->db->where('parent_sid', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        return $this->db->count_all_results();
    }

    function getCompanyApprovers(
        $companySid,
        $approver,
        $status,
        $count = FALSE,
        $start = 0,
        $end = 0
    ) {

        if ($count) {
            //
            $this->db
                ->from('timeoff_approvers')
                ->join('users', 'timeoff_approvers.employee_sid = users.sid', 'inner')
                ->join('departments_management', 'departments_management.sid = timeoff_approvers.department_sid', 'left')
                ->where('timeoff_approvers.company_sid', $companySid)
                ->order_by('timeoff_approvers.sid', 'DESC');
            // Search Filter
            if ($status != 'all') $this->db->where('timeoff_approvers.is_archived', $status);
            if ($approver != 'all') $this->db->where('timeoff_approvers.employee_sid', $approver); //
            return $this->db->count_all_results();
        }
        //
        $this->db
            ->select('
           timeoff_approvers.sid as approver_id,
           timeoff_approvers.department_sid as department_id,
           timeoff_approvers.status,
           timeoff_approvers.is_archived,
           timeoff_approvers.created_at,
           users.profile_picture as img,
           users.employee_number,
           users.sid as employee_id,
           users.employee_number,
           CONCAT(users.first_name," ", users.last_name) as full_name,
           departments_management.name as department_name
       ')
            ->from('timeoff_approvers')
            ->join('users', 'timeoff_approvers.employee_sid = users.sid', 'inner')
            ->join('departments_management', 'departments_management.sid = timeoff_approvers.department_sid', 'left')
            ->where('timeoff_approvers.company_sid', $companySid)
            ->order_by('timeoff_approvers.sort_order', 'ASC')
            ->limit($start, $end);
        // Search Filter
        if ($status != 'all') $this->db->where('timeoff_approvers.is_archived', $status);
        if ($approver != 'all') $this->db->where('timeoff_approvers.employee_sid', $approver);
        //
        $result = $this->db->get();
        $approvers = $result->result_array();
        $result  = $result->free_result();
        //
        if (!sizeof($approvers)) return array();

        return $approvers;
    }

    function count_all_employers()
    {
        $this->db->select('sid');
        $this->db->where('parent_sid > ', 0);
        $this->db->from('users');
        return $this->db->count_all_results();
    }

    function get_all_employers_new($limit, $offset, $keyword = null, $status = 2, $count_only = false, $company = null, $contact_name = null)
    {
        $this->db->select('table_one.sid');
        $this->db->select('table_one.first_name');
        $this->db->select('table_one.last_name');
        $this->db->select('table_one.middle_name');
        $this->db->select('table_one.nick_name');
        $this->db->select('table_one.username');
        $this->db->select('table_one.password');
        $this->db->select('table_one.email');
        $this->db->select('table_one.job_title');
        $this->db->select('table_one.registration_date');
        $this->db->select('table_one.joined_at');
        $this->db->select('table_one.rehire_date');
        $this->db->select('table_one.access_level');
        $this->db->select('table_one.access_level_plus');
        $this->db->select('table_one.pay_plan_flag');
        $this->db->select('table_one.profile_picture');
        $this->db->select('table_one.active');
        $this->db->select('table_one.archived');
        $this->db->select('table_one.system_user_date');
        $this->db->select('table_one.general_status');
        $this->db->select('table_two.CompanyName as company_name');
        $this->db->select('table_one.complynet_onboard');
        $this->db->select('table_one.parent_sid');
        $this->db->select('table_one.transfer_date');
        $this->db->select('table_one.languages_speak');

        $this->db->select('table_one.complynet_job_title');
        $this->db->select('table_one.PhoneNumber');



        $this->db->where('table_one.is_executive_admin <', 1);
        $this->db->where('table_one.parent_sid > ', 0);

        /*
        if ($status != 2) {
            $this->db->where('table_one.active', $status);
        }
      */

        if ($status == 'active') {
            $this->db->where('table_one.active', 1);
            $this->db->where('table_one.terminated_status', 0);
        }

        if ($status == 'terminated') {
            $this->db->where('table_one.terminated_status', 1);
        }

        if ($status != 'all' && $status != 'active' && $status != 'terminated') {
            $this->db->where('LCASE(table_one.general_status) ', $status);
        }



        $this->db->order_by('table_one.sid', 'desc');

        if ($company != null && $company != 'all') {
            $this->db->group_start();
            $this->db->like('REPLACE(table_two.CompanyName, " ", "") ', str_replace(' ', '', $company));
            $this->db->group_end();
        }

        if (($keyword != null && $keyword != 'all')) {
            $multiple_keywords = explode(',', $keyword);
            $this->db->group_start();

            for ($i = 0; $i < count($multiple_keywords); $i++) {
                $phoneRegex = strpos($multiple_keywords[$i], '@') !== false ? '' : preg_replace('/[^0-9]/', '', $multiple_keywords[$i]);
                $this->db->or_like('table_one.email', $multiple_keywords[$i]);
                $this->db->or_like('table_one.username', $multiple_keywords[$i]);
                if ($phoneRegex) {
                    $this->db->or_like('REGEXP_REPLACE(table_one.PhoneNumber, "[^0-9]", "")', preg_replace('/[^0-9]/', '', $multiple_keywords[$i]), false);
                }
                $this->db->or_like('table_one.job_title', $multiple_keywords[$i]);
                $this->db->or_like('table_one.access_level', $multiple_keywords[$i]);
                $this->db->or_like('table_one.registration_date', $multiple_keywords[$i]);
            }

            $this->db->group_end();
        }


        if ($contact_name != null && $contact_name != 'all') {
            $this->db->group_start();
            $this->db->where("(replace(lower(concat(table_one.first_name,'',table_one.last_name)),' ','') LIKE '%" . (preg_replace('/\s+/', '', strtolower($contact_name))) . "%' or table_one.nick_name LIKE '%" . (preg_replace('/\s+/', '', strtolower($contact_name))) . "%')  ");
            $this->db->group_end();
        }


        $this->db->join('users as table_two', 'table_one.parent_sid = table_two.sid', 'left');
        $this->db->from('users as table_one');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit, $offset);
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            //
            $this->GetEmployeeStatus($records_arr);

            $this->GetEmployeeDepartmentsTeams($records_arr);
            //
            return $records_arr;
        }
    }

    //
    private function GetEmployeeDepartmentsTeams(&$employees)
    {
        //
        if (empty($employees)) {
            return false;
        }
        //
        $employeeIds = array_column($employees, 'sid');
        //
        $employeeDepartmentTeams =
            $this->db->select("
            departments_management.name as department_name,
            departments_team_management.name,
            departments_employee_2_team.employee_sid
        ")
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid')
            ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid')
            ->where('departments_management.is_deleted', 0)
            ->where('departments_team_management.is_deleted', 0)
            ->where_in('departments_employee_2_team.employee_sid', $employeeIds)
            ->get('departments_employee_2_team')
            ->result_array();
        //
        if (!empty($employeeDepartmentTeams)) {
            //
            $tmp = [];
            //
            foreach ($employeeDepartmentTeams as $stat) {
                //
                if (!isset($tmp[$stat['employee_sid']])) {
                    $tmp[$stat['employee_sid']] = [
                        'departments' => [],
                        'teams' => []
                    ];
                }
                //
                $tmp[$stat['employee_sid']]['departments'][] = $stat['department_name'];
                $tmp[$stat['employee_sid']]['teams'][] = $stat['name'];
            }
            //
            $employeeDepartmentTeams = $tmp;
            //
            $tmp = [];
            //
            unset($tmp);
        }

        //
        foreach ($employees as $index => $employee) {
            //
            if (isset($employeeDepartmentTeams[$employee['sid']])) {

                $employees[$index] = array_merge($employee, $employeeDepartmentTeams[$employee['sid']]);
            } else {
                $employees[$index]['departments'] = [];
                $employees[$index]['teams'] = [];
            }
        }
        //
        return true;
    }


    private function GetEmployeeStatus(&$employees, $status = 1)
    {
        //
        if (empty($employees)) {
            return false;
        }
        $transferRecords = $this->db
            ->select('new_employee_sid')
            ->get('employees_transfer_log')
            ->result_array();
        //
        $transferIds = array_column($transferRecords, 'new_employee_sid');
        //
        $employeeIds = array_column($employees, 'sid');
        //
        $statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire')
            ->where_in('employee_sid', $employeeIds)
            ->where('employee_status', $status)
            ->get('terminated_employees')
            ->result_array();
        //
        $last_statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire, employee_status')
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
            if (in_array($employee['sid'], $transferIds)) {
                $transferDate = get_employee_transfer_date($employee['sid']);
                $employees[$index]['trensfer_date'] = $transferDate;
            }
            //
            $employees[$index]['last_status'] = isset($statuses[$employee['sid']]) ? $statuses[$employee['sid']] : [];
            $employees[$index]['last_status_2'] = isset($last_statuses[$employee['sid']]) ? $last_statuses[$employee['sid']] : [];
            $employees[$index]['last_status_text'] = isset($last_statuses[$employee['sid']]) ? GetEmployeeStatusText($last_statuses[$employee['sid']]['employee_status']) : '';
        }
        //
        return true;
    }

    public function GetCurrentEmployeeStatus(&$employees_sid, $status = 1)
    {
        //
        if (empty($employees_sid)) {
            return false;
        }
        //
        $last_statuses = $this->db
            ->select('employee_sid, termination_date, status_change_date, details, do_not_hire, employee_status')
            ->where_in('employee_sid', $employees_sid)
            ->order_by('terminated_employees.sid', 'DESC')
            ->get('terminated_employees')
            ->row_array();
        //
        $last_status_text = isset($last_statuses['employee_status']) ? GetEmployeeStatusText($last_statuses['employee_status']) : '';

        return $last_status_text;
    }

    function get_all_employers($limit, $start, $search)
    {
        $this->db->select('*');
        $this->db->limit($limit, $start);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('is_executive_admin <', 1);
        $this->db->where('parent_sid > ', 0);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by("sid", "desc");
        $this->db->where($search);
        $record_obj = $this->db->get('users');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function total_employers($search)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid > ', 0);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where('is_executive_admin <', 1);
        $this->db->where($search);
        return $this->db->get()->num_rows();
    }

    function get_company_ids_search($company_name)
    {
        $this->db->select('sid');
        $this->db->like('CompanyName', $company_name);
        $this->db->where('parent_sid', 0);
        $this->db->where('career_page_type', 'standard_career_site');
        $record_obj = $this->db->get('users');
        $companyids = $record_obj->result_array();
        $record_obj->free_result();

        if (sizeof($companyids) > 0) {
            if (sizeof($companyids) == 1 && isset($companyids[0]['sid'])) {
                return $companyids[0]['sid'];
            } else {
                $company_ids = array();

                foreach ($companyids as $id) {
                    $company_ids[] = $id['sid'];
                }

                return $company_ids;
            }
        } else {
            return 0;
        }
    }

    function get_all_employers_date($limit, $start, $search, $between)
    {
        if (isset($search['CompanyName'])) {
            $company_ids = $this->get_company_ids_search($search['CompanyName']);

            if (is_array($company_ids) == true) {
                $this->db->where_in('parent_sid', $company_ids);
            } else {
                $this->db->where('parent_sid', $company_ids);
            }

            unset($search['CompanyName']);
        }

        foreach ($search as $key => $value) {
            if ($key != 'active') {
                $this->db->like($key, $value);
                unset($search[$key]);
            }
        }

        $this->db->select('*');
        $this->db->limit($limit, $start);
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid > ', 0);
        $this->db->where('is_executive_admin <', 1);
        $this->db->where($search);
        $this->db->where($between);
        $this->db->order_by("sid", "desc");

        $record_obj = $this->db->get('users');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function total_employers_date($search, $between)
    {
        if (isset($search['CompanyName'])) {
            $company_ids = $this->get_company_ids_search($search['CompanyName']);

            if (is_array($company_ids) == true) {
                $this->db->where_in('parent_sid', $company_ids);
            } else {
                $this->db->where('parent_sid', $company_ids);
            }

            unset($search['CompanyName']);
        }

        foreach ($search as $key => $value) {
            if ($key != 'active') {
                $this->db->like($key, $value);
                unset($search[$key]);
            }
        }

        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid > ', 0);
        $this->db->where('is_executive_admin <', 1);
        $this->db->where($search);
        $this->db->where($between);
        return $this->db->get()->num_rows();
    }

    public function get_details($sid = NULL, $custom_check)
    {
        $this->db->select('*');

        if ($custom_check == 'company') {
            $this->db->where('parent_sid', 0);
            $this->db->where('sid', $sid);
            $this->db->where('career_page_type', 'standard_career_site');
            $this->db->from('users');
        } else if ($custom_check == 'employer') {
            $this->db->where('parent_sid > ', 0);
            $this->db->where('sid', $sid);
            $this->db->where('career_page_type', 'standard_career_site');
            $this->db->from('users');
        } else if ($custom_check == 'portal') {
            $this->db->where('user_sid', $sid);
            $this->db->from('portal_employer');
        }

        $results = $this->db->get()->result_array();
        //
        if ($custom_check == 'employer') {
            $this->GetEmployeeStatus($results);
        }

        return $results;
    }

    //
    function getEmployeeCreator($sid)
    {
        $a = $this->db
            ->select('
            first_name,
            last_name,
            email,
            job_title,
            access_level,
            access_level_plus,
            pay_plan_flag,
            is_executive_admin,
            active
        ')
            ->where('sid', $sid)
            ->get('users');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }

    public function get_active_countries()
    {
        $this->db->select('*');
        $this->db->where('active', '1');
        $this->db->order_by("order", "asc");

        $record_obj = $this->db->get('countries');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        return $data;
    }

    public function get_active_states($sid = NULL)
    {
        $this->db->select('sid, state_code, state_name');
        $this->db->where('country_sid', $sid);
        $this->db->order_by('order', 'asc');
        $this->db->where('active', '1');
        $record_obj = $this->db->get('states');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function update_user($sid, $data, $type = NULL)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->update('users', $data);
        (!$result) ? $this->session->set_flashdata('message', 'Update Failed, Please try Again!') : $this->session->set_flashdata('message', $type . ' updated successfully');
    }

    function update_company_timezone($sid, $timezone)
    {
        $data = array('company_timezone' => $timezone);
        $this->db->where('user_sid', $sid);
        $result = $this->db->update('portal_employer', $data);
    }

    function perform_multiple_action($type, $action, $data)
    {
        if ($action == 'activate') {
            $set_active = array('active' => '1');
            $set_portal_status = array('status' => '1');

            foreach ($data as $value) {
                if ($type == 'company') {
                    $this->db->trans_start();
                    $this->db->query("UPDATE `users` SET `active` = '1' WHERE `sid` = '" . $value . "'");
                    //$this->db->query("UPDATE `users` SET `active` = '1' WHERE `parent_sid` = '".$value."'");
                    $this->db->query("UPDATE `portal_employer` SET `status` = '1' WHERE `user_sid` = '" . $value . "'");
                    $this->db->trans_complete();
                } else {
                    $this->db->where('sid', $value);
                    $this->db->update('users', $set_active);
                }
            }
        } else if ($action == 'deactivate') {
            $set_active = array('active' => '0');
            $set_portal_status = array('status' => '0');

            foreach ($data as $value) {
                if ($type == 'company') {
                    $this->db->trans_start();
                    $this->db->query("UPDATE `users` SET `active` = '0' WHERE `sid` = '" . $value . "'");
                    //$this->db->query("UPDATE `users` SET `active` = '0' WHERE `parent_sid` = '".$value."'");
                    $this->db->query("UPDATE `portal_employer` SET `status` = '0' WHERE `user_sid` = '" . $value . "'");
                    $this->db->trans_complete();
                } else {
                    $this->db->where('sid', $value);
                    $this->db->update('users', $set_active);
                }
            }
        } else if ($action == 'delete') {
            foreach ($data as $value) {
                if ($type == 'company') {
                    $this->db->delete('portal_themes_pages', array('company_id ' => $value));
                    $this->db->delete('portal_themes_meta_data', array('company_id ' => $value));
                    $this->db->delete('portal_themes', array('user_sid ' => $value));
                    $this->db->delete('portal_testimonials', array('company_id ' => $value));
                    $this->db->delete('portal_screening_questionnaires', array('employer_sid ' => $value));
                    $this->db->delete('portal_schedule_event', array('employers_sid ' => $value, 'users_type', 'employee'));
                    //deleting all notes against applicant by company Id and all notes of employees by employee Id
                    $empCompIds = get_all_emoloyee_by_company($value);
                    $this->db->where_in('employers_sid', $empCompIds)->delete('portal_misc_notes');
                    //deleting all ratings against applicant by company Id and all notes of employees by employee Id
                    $this->db->where_in('employer_sid', $empCompIds)->delete('portal_applicant_rating');
                    //deleting all applicant attachments against applicant by company Id and all notes of employees by employee Id
                    $this->db->where_in('employer_sid', $empCompIds)->delete('portal_applicant_attachments');
                    $this->db->delete('users', array('sid' => $value));
                    $this->db->delete('users', array('parent_sid' => $value));
                    $this->db->delete('portal_employer', array('user_sid' => $value));
                    $this->db->delete('portal_job_listings', array('user_sid ' => $value));
                    $this->db->delete('portal_job_applications', array('employer_sid ' => $value));
                } else {
                    $this->db->delete('portal_applicant_rating', array('employer_sid' => $value, 'users_type', 'employee'));
                    $this->db->delete('portal_applicant_rating', array('employer_sid' => $value, 'users_type', 'applicant'));
                    $this->db->delete('portal_applicant_rating', array('applicant_job_sid' => $value, 'users_type', 'applicant'));
                    $this->db->delete('portal_misc_notes', array('employers_sid' => $value, 'users_type', 'employee'));
                    $this->db->delete('portal_applicant_attachments', array('employer_sid' => $value, 'users_type', 'employee'));
                    $this->db->where('sid', $value);
                    $this->db->delete('users');
                }
            }
        }

        return true;
    }

    function delete_employer($id)
    {
        $this->db->delete('portal_applicant_rating', array('employer_sid' => $id, 'users_type', 'employee'));
        $this->db->delete('portal_applicant_rating', array('employer_sid' => $id, 'users_type', 'applicant'));
        $this->db->delete('portal_applicant_rating', array('applicant_job_sid' => $id, 'users_type', 'applicant'));
        $this->db->delete('portal_misc_notes', array('employers_sid' => $id, 'users_type', 'employee'));
        $this->db->delete('portal_applicant_attachments', array('employer_sid' => $id, 'users_type', 'employee'));
        $this->db->where('sid', $id);
        $this->db->delete('users');
    }

    function get_employers()
    {
        $this->db->select('sid, username, email, parent_sid');
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('parent_sid > ', 0);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $record_obj = $this->db->get('users');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function updateUserDocument($receiver_sid, $dataToUpdate)
    {
        $this->db->where('receiver_sid', $receiver_sid)->update('hr_user_document', $dataToUpdate);
    }

    function get_company_theme_detail($company_sid, $select)
    {
        $result = $this->db->select($select)->where('theme_name', 'theme-4')->where('user_sid', $company_sid)->get('portal_themes')->result_array();

        if (!empty($result))
            return $result[0][$select];
        else
            return 0;
    }

    function get_company_facebook_api_detail($company_sid, $select)
    {
        $result = $this->db->select($select)->where('company_sid', $company_sid)->get('facebook_configuration')->result_array();

        if (!empty($result))
            return $result[0];
        else
            return 0;
    }

    function updateEnterpriseTheme($company_sid, $dataToUpdate)
    {
        $this->db->where('user_sid', $company_sid)->where('theme_name', 'theme-4')->update('portal_themes', $dataToUpdate);
    }

    function updateFacebookStatus($company_sid, $dataToUpdate)
    {
        $result = $this->db->where('company_sid', $company_sid)->get('facebook_configuration')->result_array();

        if (empty($result)) {
            $facebook_cofig = "INSERT INTO `facebook_configuration` (`company_sid`,`purchased`)VALUES ($company_sid,1)";
            $this->db->query($facebook_cofig);
        }

        $this->db->where('company_sid', $company_sid)->update('facebook_configuration', $dataToUpdate);
    }

    function updateToActiveOtherTheme($company_sid, $dataToUpdate)
    {
        $this->db->where('user_sid', $company_sid)->where('theme_name', 'theme-1')->update('portal_themes', $dataToUpdate);
    }

    function set_company_active_status($company_sid, $active_status)
    {
        $data = array();
        $data['active'] = intval($active_status);
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $data);
        //syc active status to auto.careers
        $company_details = $data;
        $company_details['sid'] = $company_sid;
        $company_data['company_details'] = $company_details;
        send_settings_to_remarket(REMARKET_PORTAL_SYNC_COMPANY_URL, $company_data);
        //Update Company Status
        //Update Company Portal Status
        $data = array();
        $data['status'] = intval($active_status);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $data);
        //Update Company Portal Status
    }

    function set_company_powered_by_status($company_sid, $footer_powered_by_logo)
    {
        $data = array();
        $data['footer_powered_by_logo'] = intval($footer_powered_by_logo);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $data);
    }

    function set_header_video_overlay_status($company_sid, $header_video_overlay_status)
    {
        $data = array();
        $data['header_video_overlay'] = intval($header_video_overlay_status);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $data);

        //
        $this->db->select('company_id, meta_key, meta_value');
        $this->db->where('meta_key', 'site_settings');
        $this->db->where('company_id', $company_sid);
        $result = $this->db->get('portal_themes_meta_data')->result_array();

        if (!empty($result)) {
            foreach ($result as $row) {
                $meta_values = unserialize($row['meta_value']);
                $metaValue =  [
                    'enable_header_bg' => isset($meta_values['enable_header_bg']) ? $meta_values['enable_header_bg'] : 1,
                    'enable_header_overlay' => intval($header_video_overlay_status)
                ];
                $data2 =  array(
                    'meta_value' => serialize($metaValue)
                );
                //
                $this->db->where('company_id', $row['company_id']);
                $this->db->where('meta_key', 'site_settings');
                $this->db->update('portal_themes_meta_data', $data2);
            }
        }
    }

    function set_eeo_footer_text_status($company_sid, $eeo_footer_text_status)
    {
        $data = array();
        $data['eeo_footer_text'] = intval($eeo_footer_text_status);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $data);
    }

    /**
     * Set the SMS module check
     * Created on: 18-07-2019
     *
     * @param $company_sid Integer
     * @param $sms_module_status Integer
     *
     * @return VOID
     */
    function set_sms_module_status($company_sid, $sms_module_status)
    {
        // For user
        $this->db->where('sid', $company_sid);
        $this->db->update('users', array(
            'sms_module_status' => intval($sms_module_status)
        ));
    }

    /**
     * Set the Phone regex module check
     * Created on: 25-07-2019
     *
     * @param $company_sid Integer
     * @param $phone_pattern_module Integer
     *
     * @return VOID
     */
    function set_phone_pattern_module($company_sid, $phone_pattern_module)
    {
        // For user
        $this->db->where('sid', $company_sid);
        $this->db->update('users', array(
            'phone_pattern_module' => intval($phone_pattern_module)
        ));
    }

    function set_company_user_active_status($company_sid, $user_sid, $active_status)
    {
        $dataToSave = array();
        $dataToSave['active'] = intval($active_status);
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $user_sid);
        $this->db->update('users', $dataToSave);
    }

    function get_all_platform_packages($package_type = 'account-package')
    {
        $this->db->select('*');
        $this->db->order_by('sort_order', 'ASC');
        $this->db->where('product_type', $package_type);

        $record_obj = $this->db->get('products');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function get_company_details($company_sid)
    {
        $this->db->select('users.*');
        $this->db->select('portal_employer.enable_captcha');
        $this->db->select('products.name as package_name');
        $this->db->select('products.short_description as package_description');
        $this->db->select('products.includes_facebook_api as package_includes_facebook_api');
        $this->db->select('products.includes_deluxe_theme as package_includes_deluxe_theme');
        $this->db->select('facebook_configuration.purchased as facebook_purchased_status');
        $this->db->select('facebook_configuration.purchase_date as facebook_purchased_date');
        $this->db->select('facebook_configuration.expiry_date as facebook_expiry_date');
        $this->db->select('portal_themes.purchased as deluxe_theme_purchased');
        $this->db->select('portal_themes.purchase_date as deluxe_theme_purchased_date');
        $this->db->select('portal_themes.expiry_date as deluxe_theme_expiry_date');
        $this->db->select('countries.country_name as country_name');
        $this->db->select('countries.country_name as country_name');
        $this->db->select('states.state_name as state_name');
        $this->db->where('users.sid', $company_sid);
        $this->db->where('portal_themes.theme_name', 'theme-4');
        $this->db->join('products', 'users.account_package_sid = products.sid', 'left');
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $this->db->join('facebook_configuration', 'users.sid = facebook_configuration.company_sid', 'left');
        $this->db->join('portal_themes', 'users.sid = portal_themes.user_sid', 'left');
        $this->db->join('countries', 'users.Location_Country = countries.sid', 'left');
        $this->db->join('states', 'users.Location_State = states.sid', 'left');
        $data = $this->db->get('users')->result_array();

        if (sizeof($data) < 1) {
            return array();
        }
        //Get Employee Count
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $number_of_employees = $this->db->get('users')->num_rows();
        //Get Applicant Count
        $this->db->select('portal_applicant_jobs_list.job_sid');
        $this->db->select('portal_applicant_jobs_list.date_applied');
        $this->db->select('portal_applicant_jobs_list.approval_status');
        $this->db->select('portal_job_applications.hired_status');
        $this->db->where('portal_applicant_jobs_list.company_sid', $company_sid);
        $this->db->where('portal_job_applications.hired_status', 0);
        $this->db->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left');
        $number_of_applicants = $this->db->get('portal_applicant_jobs_list')->num_rows();

        if (!empty($company_sid)) {
            $data = $data[0];
            $data['number_of_employees'] = $number_of_employees;
            $data['number_of_applicants'] = $number_of_applicants;
            return $data;
        } else {
            return array();
        }
    }

    function set_company_status($company_sid, $status = 1)
    { //Update Company Status
        $data = array();
        $data['active'] = intval($status);
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $data);
    }

    function set_company_portal_status($company_sid, $status = 1)
    { //Update Company Portal Status
        $data = array();
        $data['status'] = intval($status);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $data);
    }

    function get_company_portal_status($company_sid)
    {
        $this->db->select('status');
        $this->db->where('user_sid', $company_sid);
        $data = $this->db->get('portal_employer')->result_array();

        if (!empty($data)) {
            $data = $data[0];
            return $data['status'];
        } else {
            return 0;
        }
    }

    function activate_trial_period($company_sid, $number_of_days, $enable_facebook_api, $enable_deluxe_theme)
    { // check if it is trail start or update
        $data_trial = $this->get_trial_period_details($company_sid);
        $admin_sid = $this->ion_auth->user()->row()->id;
        $current_date = date('Y-m-d H:i:s');
        $expiry_date = date('Y-m-d H:i:s', date_add_day(date('Y-m-d H:i:s'), $number_of_days));
        // update the expiry date of company
        $dataToSave = array();
        $dataToSave['account_package_sid'] = 0;
        $dataToSave['expiry_date'] = $expiry_date;
        //$dataToSave['has_job_approval_rights'] = 1;
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $dataToSave);

        if ($enable_facebook_api == 1) { // enable facebook API for the company
            $dataToSave = array();
            $dataToSave['purchased'] = 1;
            $dataToSave['purchase_date'] = $current_date;
            $dataToSave['expiry_date'] = $expiry_date;
            $this->db->where('company_sid', $company_sid);
            $this->db->update('facebook_configuration', $dataToSave);
        }

        if ($enable_deluxe_theme == 1) { // enable deluxe theme for the company
            $dataToSave = array();
            $dataToSave['purchased'] = 1;
            $this->db->where('user_sid', $company_sid);
            $this->db->where('theme_name', 'theme-4');
            $this->db->update('portal_themes', $dataToSave);
        }

        //Activate Portal
        $dataToSave = array();
        $dataToSave['status'] = 1;
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $dataToSave);

        if (empty($data_trial)) { // add an entry in trial period table
            $dataToInsert = array();
            $dataToInsert['company_sid'] = $company_sid;
            $dataToInsert['start_date'] = $current_date;
            $dataToInsert['end_date'] = $expiry_date;
            $dataToInsert['created_by_sid'] = $admin_sid;
            $dataToInsert['number_of_days'] = $number_of_days;
            $dataToInsert['status'] = 'enabled';
            $this->db->insert('trial_period', $dataToInsert);
            $trial_period_sid = $this->db->insert_id();
            $dataToInsertHistory = array();
            $dataToInsertHistory['tp_sid'] = $trial_period_sid;
            $dataToInsertHistory['operation_performed'] = 'insert';
            $dataToInsertHistory['operation_datetime'] = $current_date;
            $dataToInsertHistory['operation_admin_sid'] = $admin_sid;
            $dataToInsertHistory['old_expiry_date'] = NULL;
            $dataToInsertHistory['new_expiry_date'] = $expiry_date;
            $dataToInsertHistory['number_of_days'] = $number_of_days;
            $dataToInsertHistory['company_sid'] = $company_sid;
            $this->db->insert('trial_period_histroy', $dataToInsertHistory);
            $this->session->set_flashdata('message', 'Trial Period Successfully Activated!');
        } else { // update trail period table
            $dataToupdate = array();
            $dataToupdate['end_date'] = $expiry_date;
            $dataToupdate['number_of_days'] = $number_of_days;
            $dataToupdate['status'] = 'enabled';
            $trial_period_sid = $data_trial['sid'];
            $old_expiry_date = $data_trial['end_date'];
            $this->db->where('sid', $trial_period_sid);
            $this->db->update('trial_period', $dataToupdate);
            // insert data in trial history table
            $dataToInsertHistory = array();
            $dataToInsertHistory['tp_sid'] = $trial_period_sid;
            $dataToInsertHistory['operation_performed'] = 'update';
            $dataToInsertHistory['operation_datetime'] = $current_date;
            $dataToInsertHistory['operation_admin_sid'] = $admin_sid;
            $dataToInsertHistory['old_expiry_date'] = $old_expiry_date;
            $dataToInsertHistory['new_expiry_date'] = $expiry_date;
            $dataToInsertHistory['number_of_days'] = $number_of_days;
            $dataToInsertHistory['company_sid'] = $company_sid;
            $this->db->insert('trial_period_histroy', $dataToInsertHistory);
            $this->session->set_flashdata('message', 'Trial Period Updated Successfully!');
        }
    }

    function end_trial_period($company_sid, $trial_sid)
    { //Mark Trial Period As Disabled
        $dataToUpdate = array();
        $dataToUpdate['status'] = 'disabled';
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $trial_sid);
        $this->db->update('trial_period', $dataToUpdate);
        //Store Trial Update History
        $dataToInsert = array();
        $dataToInsert['operation_performed'] = 'trial_manually_ended';
        $dataToInsert['operation_datetime'] = date('Y-m-d H:i:s');
        $dataToInsert['tp_sid'] = $trial_sid;
        $dataToInsert['company_sid'] = $company_sid;
        $this->db->insert('trial_period_histroy', $dataToInsert);
        //Set Company Expiry to current date and Account Package ID to be 0
        $dataToUpdate = array();
        $dataToUpdate['expiry_date'] = date('Y-m-d H:i:s');
        $dataToUpdate['account_package_sid'] = 0;
        $dataToUpdate['number_of_rooftops'] = 0;
        $this->db->where('sid', $company_sid);
        $this->db->update('users', $dataToUpdate);
        //Deactivate Portal
        $dataToUpdate = array();
        $dataToUpdate['status'] = 0;
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $dataToUpdate);
        //Deactivate All Themes
        $dataToUpdate = array();
        $dataToUpdate['purchased'] = 0;
        $dataToUpdate['theme_status'] = 0;
        $this->db->where('user_sid', $company_sid);
        //$this->db->where('theme_name', 'theme-4');
        $this->db->update('portal_themes', $dataToUpdate);
        //Activate Theme-1
        $dataToUpdate = array();
        $dataToUpdate['theme_status'] = 1;
        $this->db->where('user_sid', $company_sid);
        $this->db->where('theme_name', 'theme-1');
        $this->db->update('portal_themes', $dataToUpdate);
        //Deactivate Facebook API
        $dataToUpdate = array();
        $dataToUpdate['purchased'] = 0;
        $this->db->where('company_sid', $company_sid);
        $this->db->update('facebook_configuration', $dataToUpdate);
    }

    function get_trial_period_details($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('trial_period');
        $data_trial = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($data_trial)) {
            return null;
        } else {
            $data_trial = $data_trial[0];
            $trial_sid = $data_trial['sid'];
            return $data_trial;
        }
    }

    function get_company_default_cc($company_sid)
    {
        $data = $this->db->where('company_sid', $company_sid)
            ->where('is_default', 1)
            ->get('emp_cards')->row_array();

        if (!empty($data)) {
            return $data;
        } else {
            $data = $this->db->where('company_sid', $company_sid)
                ->limit(1)
                ->get('emp_cards')->row_array();
            return $data;
        }
    }

    function insert_admin_company_note($company_sid, $created_by, $note_type, $note_text)
    {
        $dataToInsert = array();
        $dataToInsert['company_sid'] = $company_sid;
        $dataToInsert['created_by'] = $created_by;
        $dataToInsert['note_type'] = $note_type;
        $dataToInsert['note_text'] = $note_text;
        $dataToInsert['created_date'] = date('Y-m-d H:i:s');
        $this->db->insert('admin_company_notes', $dataToInsert);
    }

    function update_admin_company_note($sid, $company_sid, $note_type, $note_text)
    {
        $dataToInsert = array();
        $dataToInsert['note_type'] = $note_type;
        $dataToInsert['note_text'] = $note_text;
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->update('admin_company_notes', $dataToInsert);
    }

    function save_admin_company_note($sid, $company_sid, $created_by, $note_type, $note_text)
    {
        if ($sid == null) {
            $this->insert_admin_company_note($company_sid, $created_by, $note_type, $note_text);
            $note_sid = $this->db->insert_id();
            $this->insert_admin_company_note_modification_record($company_sid, $note_sid, $note_type, $note_text, 'created', $created_by);
        } else {
            $this->update_admin_company_note($sid, $company_sid, $note_type, $note_text);
            $this->insert_admin_company_note_modification_record($company_sid, $sid, $note_type, $note_text, 'modified', $created_by);
        }
    }

    function insert_admin_company_note_modification_record($company_sid, $note_sid, $note_type, $note_text, $operation, $performed_by)
    {
        $dataToSave = array();
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['note_sid'] = $note_sid;
        $dataToSave['note_type'] = $note_type;
        $dataToSave['note_text'] = $note_text;
        $dataToSave['operation'] = $operation;
        $dataToSave['operation_date'] = date('Y-m-d H:i:s');
        $dataToSave['performed_by'] = $performed_by;
        $this->db->insert('admin_company_notes_modification_history', $dataToSave);
    }

    function get_admin_company_notes($company_sid, $note_type)
    {
        $this->db->select('admin_company_notes.*');
        $this->db->select('administrator_users.first_name as admin_first_name, administrator_users.last_name as admin_last_name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('note_type', $note_type);
        $this->db->order_by('sid', 'DESC');
        $this->db->join('administrator_users', 'administrator_users.id = admin_company_notes.created_by', 'left');
        $record_obj = $this->db->get('admin_company_notes');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function get_admin_company_note($company_sid, $note_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $note_sid);
        $data_ = $this->db->get('admin_company_notes')->result_array();

        if (!empty($data_)) {
            return $data_[0];
        } else {
            return $data_;
        }
    }

    function delete_admin_company_note($company_sid, $note_sid, $note_type, $note_text, $deleted_by)
    {
        $this->db->where('sid', $note_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->delete('admin_company_notes');
        $this->insert_admin_company_note_modification_record($company_sid, $note_type, $note_text, 'deleted', $deleted_by);
    }

    function get_documents_status($company_sid)
    {
        $this->db->select('users.CompanyName');
        $this->db->select('form_document_credit_card_authorization.status as cc_auth_status');
        $this->db->select('form_document_eula.status as eula_status');
        $this->db->select('form_document_company_contacts.status as company_contacts_status');
        $this->db->select('form_payroll_agreement.status as fpa_status');
        $this->db->select('form_document_payroll_credit_card_authorization.status as payroll_cc_auth_status');

        $this->db->where('users.sid', $company_sid);
        $this->db->join('form_document_credit_card_authorization', 'form_document_credit_card_authorization.company_sid = users.sid', 'left');
        $this->db->join('form_document_eula', 'form_document_eula.company_sid = users.sid', 'left');
        $this->db->join('form_document_company_contacts', 'form_document_company_contacts.company_sid = users.sid', 'left');
        $this->db->join('form_payroll_agreement', 'form_payroll_agreement.company_sid = users.sid', 'left');
        $this->db->join('form_document_payroll_credit_card_authorization', 'form_document_payroll_credit_card_authorization.company_sid = users.sid', 'left');

        $record_obj = $this->db->get('users');
        $data = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    function get_company_trial_period_detail($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('trial_period');
        $dataFromTable = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($dataFromTable)) {
            return $dataFromTable[0];
        } else {
            return array();
        }
    }

    function add_exec_admin($data)
    { // function for adding new executive administrator
        if (isset($data['user_shift_minutes']) && (trim($data['user_shift_minutes']) == '') || $data['user_shift_minutes'] == null) unset($data['user_shift_minutes']);
        if (isset($data['user_shift_hours']) && trim($data['user_shift_hours']) == '' || $data['user_shift_hours'] == null) unset($data['user_shift_hours']);
        $result = $this->db->insert('executive_users', $data);
        return $this->db->insert_id();
    }

    function get_executive_administrators($name = 'all', $email = 'all')
    { // function for getting all data from the executive_users table
        if (!empty($email) && $email != 'all') {
            $this->db->where('email', $email);
        }

        if (!empty($name) && $name != 'all') {
            $name_parts = explode(' ', $name);

            if (count($name_parts) == 1) {
                $this->db->like('first_name', $name_parts[0]);
                $this->db->or_like('last_name', $name_parts[0]);
            } else {
                $this->db->like('first_name', $name_parts[0]);
                $this->db->group_start();

                for ($i = 1; $i < count($name_parts); $i++) {
                    $this->db->or_like('last_name', $name_parts[$i]);
                }

                $this->db->group_end();
            }
        }

        $this->db->order_by('sid', 'DESC');
        $data = $this->db->get('executive_users');
        return $data->result();
    }

    function get_administrator($admin_id)
    { // function to get one administrator by id for editing
        $this->db->select('*');
        $this->db->where('sid', $admin_id);
        $record_obj = $this->db->get('executive_users');
        $administrator = $record_obj->result_array();
        $record_obj->free_result();
        return $administrator;
    }

    function edit_admin($admin_id, $data)
    { // function to update administrator data by id
        $this->db->where('sid', $admin_id);
        $result = $this->db->update('executive_users', $data);
        if ($result) { // record is updated, Update all companies accounts + session.
            $this->db->select('logged_in_sid');
            $this->db->where('executive_admin_sid', $admin_id);
            $record_obj = $this->db->get('executive_user_companies');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            if (!empty($record_arr)) {
                $data_to_update = array(
                    'first_name' => $data['first_name'],
                    'last_name' => $data['last_name'],
                    'email' => $data['email'],
                    'active' => $data['active'],
                    'timezone' => $data['timezone'],
                    'access_level' => $data['access_level'],
                    'PhoneNumber' => $data['direct_business_number'],
                    'job_title' => $data['job_title']
                );

                if (isset($data['profile_picture']) && $data['profile_picture'] != NULL) {  //profile_picture
                    $data_to_update['profile_picture'] = $data['profile_picture'];
                }

                foreach ($record_arr as $users) {
                    $this->db->where('sid', $users['logged_in_sid']);
                    $this->db->update('users', $data_to_update);
                }
            } // update session informtion.

            $this->db->select('*');
            $this->db->where('sid', $admin_id);
            $record_obj = $this->db->get('executive_users');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            $session_data = array();
            $session_data['status'] = 'active';
            $session_data['executive_user'] = $record_arr[0];
            $this->session->set_userdata('executive_loggedin', $session_data);
        }

        return $result;
    }

    function get_admin_companies($admin_id)
    { // function to get all the companies against one administrator
        $this->db->select('*');
        $this->db->where('executive_admin_sid', $admin_id);
        $record_obj = $this->db->get('executive_user_companies');
        $companies = $record_obj->result_array();
        $record_obj->free_result();
        return $companies;
    }

    function get_admin_exec_account($email_address, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 1);
        $this->db->where('email', $email_address);
        $this->db->limit(1);
        $record_obj = $this->db->get('users');
        $admin = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($admin)) {
            return $admin[0];
        } else {
            return array();
        }
    }

    function get_executive_admin_info_from_users_table($company_sid, $executive_admin_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $executive_admin_sid);

        $record_obj = $this->db->get('executive_users');
        $admin_array = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($admin_array)) {
            $admin_array = $admin_array[0];
            $this->db->select('*');
            $this->db->where('parent_sid', $company_sid);
            $this->db->where('is_executive_admin', '1');
            $this->db->where('email', $admin_array['email']);

            $record_obj = $this->db->get('users');
            $executive_admin_details = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($executive_admin_details)) {
                return $executive_admin_details[0];
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function add_company($data, $company_sid, $username = null, $password = null, $career_page_type = null)
    {
        $this->db->select('*');
        $this->db->where('sid', $data['executive_admin_sid']);
        $admin_array = $this->db->get('executive_users')->result_array();
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', '1');
        $this->db->where('email', $admin_array[0]['email']);

        $record_obj = $this->db->get('users');
        $check_duplicate_company = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($check_duplicate_company) && !empty($admin_array)) {
            $insert_data = array();
            $insert_data['ip_address'] = $admin_array[0]['ip_address'];
            $insert_data['username'] = ($username == null ? $admin_array[0]['username'] . '_executive_admin_' . $company_sid : $username);

            if ($password != null) {
                $insert_data['password'] = do_hash($password, 'md5');
            }

            $insert_data['email'] = $admin_array[0]['email'];
            $insert_data['activation_key'] = $admin_array[0]['activation_code'];
            $insert_data['first_name'] = $admin_array[0]['first_name'];
            $insert_data['last_name'] = $admin_array[0]['last_name'];
            $insert_data['job_title'] = $admin_array[0]['job_title'];
            $insert_data['cell_number'] = $admin_array[0]['cell_number'];
            $insert_data['profile_picture'] = $admin_array[0]['profile_picture'];
            $insert_data['parent_sid'] = $company_sid;
            $insert_data['registration_date'] = date('Y-m-d H:i:s');
            $insert_data['CompanyName'] = $data['company_name'];
            $insert_data['is_primary_admin'] = 1;
            $insert_data['is_executive_admin'] = 1;

            if ($career_page_type != null) {
                $insert_data['career_page_type'] = $career_page_type;
            }

            $this->db->insert('users', $insert_data);
            $id = $this->db->insert_id();
        } else {
            $id = $check_duplicate_company[0]['sid'];
            $this->db->where('sid', $id);
            $data_to_update = array();
            $data_to_update['active'] = 1;
            $data_to_update['email'] = $admin_array[0]['email'];
            $data_to_update['activation_key'] = $admin_array[0]['activation_code'];
            $data_to_update['first_name'] = $admin_array[0]['first_name'];
            $data_to_update['last_name'] = $admin_array[0]['last_name'];
            $data_to_update['job_title'] = $admin_array[0]['job_title'];
            $data_to_update['cell_number'] = $admin_array[0]['cell_number'];
            $data_to_update['profile_picture'] = $admin_array[0]['profile_picture'];

            if ($password != null) {
                $data_to_update['password'] = do_hash($password, 'md5');
            }

            if ($username != null) {
                $data_to_update['username'] = $username;
            }

            $this->db->update('users', $data_to_update);
        }

        $this->db->select('*');
        $this->db->where('executive_admin_sid', $data['executive_admin_sid']);
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('executive_user_companies');
        $company_info = $record_obj->result_array();
        $record_obj->free_result();

        if (isset($id)) {
            $data['logged_in_sid'] = $id;
        }

        if (!empty($company_info)) {
            $sid = $company_info[0]['sid'];
            $this->db->where('sid', $sid);
            $this->db->update('executive_user_companies', $data);
        } else {
            $this->db->insert('executive_user_companies', $data);
        }
    }

    function executive_admin_company_remove($sid, $logged_in_sid)
    {
        $this->db->where('sid', $logged_in_sid);
        $this->db->update('users', array('active' => 0));
        $result = $this->db->delete('executive_user_companies', array('sid ' => $sid));
        return $result;
    }

    function executive_admin_user_delete($administrator_sid)
    {
        //
        $this->db->select('email');
        $this->db->where('sid', $administrator_sid);
        $admin_array = $this->db->get('executive_users')->row_array();
        //
        $this->db->where('email', $admin_array['email']);
        $this->db->where('is_executive_admin', '1');
        $this->db->delete('users');
    }

    function executive_admin_delete($administrator_sid)
    {
        $this->db->delete('executive_user_companies', array('executive_admin_sid ' => $administrator_sid));
        $result = $this->db->delete('executive_users', array('sid ' => $administrator_sid));
        return $result;
    }

    function executive_admin_activation($administrator_sid)
    {
        $this->db->select('active');
        $this->db->where('sid', $administrator_sid);
        $record_obj = $this->db->get('executive_users');
        $status = $record_obj->result_array();
        $record_obj->free_result();
        $status = $status[0]['active'];

        if ($status == '1') {
            $this->db->where('sid', $administrator_sid);
            $result = $this->db->update('executive_users', array('active' => 0));
        } else {
            $this->db->where('sid', $administrator_sid);
            $result = $this->db->update('executive_users', array('active' => 1));
        }

        $this->manageExecutiveAdminOnAllStores($administrator_sid, $status);

        return $result;
    }

    public function manageExecutiveAdminOnAllStores($executiveAdminId, $status)
    {
        //
        $inStores = $this->db
            ->select('logged_in_sid')
            ->where('executive_admin_sid', $executiveAdminId)
            ->get('executive_user_companies')
            ->result_array();
        //
        if (empty($inStores)) {
            return false;
        }
        //
        foreach ($inStores as $inStore) {
            $this->db
                ->where('sid', $inStore['logged_in_sid'])
                ->update('users', ['active' => $status == '1' ? 0 : 1]);
        }
        //
        return true;
    }

    function get_company_name($company_sid)
    {
        $this->db->select('CompanyName');
        $this->db->where('sid', $company_sid);
        $record_obj = $this->db->get('users');
        $company_record = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($company_record)) {
            return $company_record[0]['CompanyName'];
        } else {
            return '';
        }
    }

    function add_new_employer($company_sid, $field_data = array())
    {
        $data_to_insert = array();
        $data_to_insert['parent_sid'] = $company_sid;

        if (!empty($field_data)) {
            $data_to_insert = array_merge($data_to_insert, $field_data);
        }

        $this->db->insert('users', $data_to_insert);
        return $this->db->insert_id();
    }

    function get_security_access_levels()
    {
        $this->db->select('access_level');
        $this->db->where('status', 1);
        $record_obj = $this->db->get('security_access_level');
        $access_levels = $record_obj->result_array();
        $record_obj->free_result();
        $my_return = array();

        foreach ($access_levels as $access_level) {
            $my_return[] = $access_level['access_level'];
        }

        return $my_return;
    }

    function get_automotive_groups()
    {
        $this->db->select('*');
        $this->db->order_by('LOWER(group_name)', 'ASC');

        $record_obj = $this->db->get('automotive_groups');
        $data = $record_obj->result_array();
        $record_obj->free_result();
        return $data;
    }

    function get_automotive_group_member_companies($automotive_group_sid, $fetch_only_registered = null, $exec_admin_sid = 0)
    {
        $this->db->select('*');
        $this->db->where('automotive_group_sid', $automotive_group_sid);

        if ($fetch_only_registered == true) {
            $this->db->where('is_registered_in_ahr', 1);
        } elseif ($fetch_only_registered == false) {
            $this->db->where('is_registered_in_ahr', 0);
        }

        $record_obj = $this->db->get('automotive_group_companies');
        $member_companies = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($member_companies)) {
            foreach ($member_companies as $key => $member_company) {
                $company_sid = $member_company['company_sid'];
                $this->db->select('sid, CompanyName');
                $this->db->where('sid', $company_sid);

                $record_obj = $this->db->get('users');
                $company_info = $record_obj->result_array();
                $record_obj->free_result();

                if (!empty($company_info)) {
                    $member_companies[$key]['registered_company_info'] = $company_info[0];
                } else {
                    $member_companies[$key]['registered_company_info'] = array();
                }

                if ($exec_admin_sid > 0) {
                    $this->db->select('*');
                    $this->db->where('executive_admin_sid', $exec_admin_sid);
                    $this->db->where('company_sid', $company_sid);

                    $record_obj = $this->db->get('executive_user_companies');
                    $exec_admin_company = $record_obj->result_array();
                    $record_obj->free_result();

                    if (!empty($exec_admin_company)) {
                        $exec_admin_company = $exec_admin_company[0];
                        $member_companies[$key]['executive_user_company'] = $exec_admin_company;
                    } else {
                        $member_companies[$key]['executive_user_company'] = array();
                    }
                }
            }
        }

        return $member_companies;
    }

    function get_executive_admin_company_details($executive_admin_sid, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('executive_admin_sid', $executive_admin_sid);
        $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('executive_user_companies');
        $executive_admin_company_info = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($executive_admin_company_info)) {
            return $executive_admin_company_info[0];
        } else {
            return array();
        }
    }

    function get_automotive_group_details($automotive_group_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $automotive_group_sid);

        $record_obj = $this->db->get('automotive_groups');
        $group_details = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($group_details)) {
            $group_details = $group_details[0];
            $corporate_company_sid = $group_details['corporate_company_sid'];

            if ($corporate_company_sid > 0) {
                $this->db->select('*');
                $this->db->where('sid', $corporate_company_sid);
                $corporate_company_detail = $this->db->get('users')->result_array();

                if (!empty($corporate_company_detail)) {
                    $group_details['corporate_company'] = $corporate_company_detail[0];
                } else {
                    $group_details['corporate_company'] = array();
                }
            }
            return $group_details;
        } else {
            return array();
        }
    }

    function get_brands_by_company($company_sid)
    {
        $this->db->select('distinct(oem_brand_sid), oem_brands.*');
        $this->db->where('oem_brands_companies.company_sid', $company_sid);
        $this->db->join('oem_brands', 'oem_brands_companies.oem_brand_sid = oem_brands.sid');

        $record_obj = $this->db->get('oem_brands_companies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_groups_by_company($company_sid)
    {
        $this->db->select('automotive_groups.*');
        $this->db->where('automotive_group_companies.company_sid', $company_sid);
        $this->db->order_by("LOWER(automotive_groups.group_name)", "asc");
        $this->db->join('automotive_group_companies', 'automotive_group_companies.automotive_group_sid = automotive_groups.sid');

        $record_obj = $this->db->get('automotive_groups');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_all_marketing_agencies()
    {
        $this->db->select('*');
        $this->db->where('is_deleted', 0);
        $this->db->order_by('sid', 'desc');

        $record_obj = $this->db->get('marketing_agencies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function check_if_exec_admin_has_access_to_corp_co($exec_admin_sid, $corporate_company_sid)
    {
        $this->db->select('*');
        $this->db->where('executive_admin_sid', $exec_admin_sid);
        $this->db->where('company_sid', $corporate_company_sid);
        $count = $this->db->get('executive_user_companies')->num_rows();

        if ($count > 0) {
            return true;
        } else {
            return false;
        }
    }

    function get_executive_admin_companies($executive_admin_sid)
    {
        $this->db->select('*');
        $this->db->where('executive_admin_sid', $executive_admin_sid);
        $record_obj = $this->db->get('executive_user_companies');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_industry_categories()
    {
        $this->db->select('sid, industry_name');
        $record_obj = $this->db->get('job_category_industries');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_industry_category($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('job_category_industries');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_portal_details($sid)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $sid);
        $record_obj = $this->db->get('portal_employer');
        $portal_detail = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($portal_detail)) {
            return $portal_detail[0];
        } else {
            return array();
        }
    }

    function get_all_executive_admin_companies()
    {
        $this->db->select('users.sid, users.career_page_type, users.CompanyName, portal_employer.sub_domain');
        $this->db->where('parent_sid', 0);
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('is_executive_admin', 0);
        $this->db->order_by('sid', 'desc');
        $this->db->join('portal_employer', 'users.sid = portal_employer.user_sid', 'left');
        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_job_fair_status($company_sid)
    {
        $this->db->select('status');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (empty($record_arr)) { // no record found, verify company id
            $this->db->select('username');
            $this->db->where('sid', $company_sid);
            $record_obj = $this->db->get('users');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) { // company exists
                $data_to_insert = array(
                    'company_sid' => $company_sid,
                    'title' => 'Job Fair',
                    'content' => '<p>Joining our Talent Network will enhance your job search and application process. Whether you choose to apply or just leave your information, we look forward to staying connected with you.</p>
                                                            <ul>
                                                                    <li>Receive alerts with new job opportunities that match your interests</li>
                                                                    <li>Receive relevant communications and updates from our organization</li>
                                                                    <li>Share job opportunities with family and friends through Social Media or email</li>
                                                            </ul>',
                    'video_id' => 'bYWCYtRrpXE'
                );
                $this->db->insert('job_fairs_recruitment', $data_to_insert);
                return 0;
            } else {
                return 0;
            }
        } else {
            return $record_arr[0]['status'];
        }
    }

    function get_job_fair_data($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('job_fairs_recruitment');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_footer_logo_data($company_sid)
    {
        $this->db->select('footer_powered_by_logo, footer_logo_type, footer_logo_text, footer_logo_image, copyright_company_status, copyright_company_name');
        $this->db->where('user_sid', $company_sid);
        $record_obj = $this->db->get('portal_employer');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function save_job_fair($data)
    {
        $this->db->where('company_sid', $data['company_sid']);
        $result = $this->db->get('job_fairs_recruitment')->num_rows();

        if ($result > 0) {
            $this->db->where('company_sid', $data['company_sid']);
            $result = $this->db->update('job_fairs_recruitment', $data);
        } else { // insert
            $result = $this->db->insert('job_fairs_recruitment', $data);
        }

        return $result;
    }

    function get_contact_info($company_sid)
    {
        $this->db->where('company_id', $company_sid);
        $records_obj = $this->db->get('contact_info_for_company');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function add_update_contact_info($company_sid, $SalesPhoneNumber, $SalesEmail, $TechnicalSupportPhoneNumber, $TechnicalSupportEmail)
    {
        $dataToInsert = array();
        $dataToInsert['company_id'] = $company_sid;
        $dataToInsert['exec_sales_phone_no'] = $SalesPhoneNumber;
        $dataToInsert['exec_sales_email'] = $SalesEmail;
        $dataToInsert['tech_support_phone_no'] = $TechnicalSupportPhoneNumber;
        $dataToInsert['tech_support_email'] = $TechnicalSupportEmail;
        $this->db->where('company_id', $company_sid);
        $result = $this->db->get('contact_info_for_company')->num_rows();

        if ($result > 0) {
            $this->db->where('company_id', $company_sid);
            $this->db->update('contact_info_for_company', $dataToInsert);
        } else {
            $this->db->insert('contact_info_for_company', $dataToInsert);
        }
    }

    function get_additional_content_boxes($sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $sid);
        $this->db->from('portal_theme4_additional_sections');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function add_additional_content_boxes($data)
    {
        $this->db->insert('portal_theme4_additional_sections', $data);
        return $this->db->insert_id();
    }

    function update_additional_content_boxes($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('portal_theme4_additional_sections', $data);
    }

    function get_additional_box($sid)
    {
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_theme4_additional_sections');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function delete_additional_content_boxes($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('portal_theme4_additional_sections');
    }

    function get_reassign_configured_companies($company_sid)
    {
        $this->db->select('users.CompanyName');
        $this->db->where('reassign_candidate_companies.company_sid', $company_sid);
        $this->db->where('reassign_candidate_companies.status', 1);
        $this->db->join('users', 'users.sid=reassign_candidate_companies.linked_company_sid', 'left');
        $records_obj = $this->db->get('reassign_candidate_companies');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_user_status($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('users', $data);
    }

    function update_employer_status($sid, $data)
    {
        $this->db->where('user_sid', $sid);
        return $this->db->update('portal_employer', $data);
    }

    function insert_job_visibility_record_for_non_applicants($company_sid)
    {
        $data_to_insert = array();
        $data_to_insert['job_sid'] = 0;
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['employer_sid'] = 0;
        $this->db->insert('portal_job_listings_visibility', $data_to_insert);
        return $this->db->insert_id();
    }

    function get_employee_details($sid)
    {
        $this->db->select('first_name, last_name, username, access_level, salt, email');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_excetive_admin($sid, $dataToUpdate)
    {
        $this->db->where('sid', $sid)->set($dataToUpdate)->update('executive_users');
    }

    function update_footer_logo($sid, $dataToUpdate)
    {
        $this->db->where('user_sid', $sid);
        $this->db->update('portal_employer', $dataToUpdate);
    }

    /**
     * Fetch Company details by employee id
     * Created on: 30-04-2019
     *
     * @return Array|Bool
     */
    function fetch_company_details_by_employee_id($employee_id)
    {
        $result = $this->db
            ->select('
            company.CompanyName as company_name,
            company.PhoneNumber as company_phone,
            company.Location_Address as company_address,
            company.email  as company_email,
            company.WebSite  as career_site_url
        ')
            ->from('users')
            ->where('users.sid', $employee_id)
            ->join('users as company', 'company.sid = users.parent_sid', 'left')
            ->get();
        $result_arr = $result->row_array();
        $result = $result->free_result();
        return $result_arr;
    }

    function get_company_employers($company_sid)
    {
        $this->db->select('email,access_level,profile_picture,sid,first_name,last_name,registration_date,is_executive_admin,complynet_credentials,pay_plan_flag');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_configured_access_level_plus_employers($company_sid)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->where('access_level_plus', 1);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_configured_DPO_employers($employee_sid, $flag)
    {
        $update_array = array();
        $update_array['doc_preview_only'] = $flag;
        $this->db->where('sid', $employee_sid);
        $this->db->update('users', $update_array);
    }

    function update_configured_pay_plan_employers($employee_sid, $flag)
    {
        $update_array = array();
        $update_array['pay_plan_flag'] = $flag;
        $this->db->where('sid', $employee_sid);
        $this->db->update('users', $update_array);
    }

    function add_configured_access_level_plus_employers($employee_sid)
    {
        $update_array = array();
        $update_array['access_level_plus'] = 1;
        $this->db->where('sid', $employee_sid);
        $this->db->update('users', $update_array);
    }

    function delete_configured_access_level_plus_employers($employee_sid)
    {
        $update_array = array();
        $update_array['access_level_plus'] = 0;
        $this->db->where('sid', $employee_sid);
        $this->db->update('users', $update_array);
    }

    function fetch_details($id)
    {
        $this->db->select('mykey, myvalue, mydomain, mytype, mysec');
        $this->db->where('myid', $id);
        $record_obj = $this->db->get('portal_themes_data');
        $result = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($result)) {
            $data = $result[0];
            $sec = $data['mysec'];
            $type = $data['mytype'];
            $key = $data['mykey'];
            $value = $data['myvalue'];

            $enc = openssl_decrypt($type, "AES-128-ECB", $sec);
            $key = openssl_decrypt($key, $enc, $sec);
            $value = openssl_decrypt($value, $enc, $sec);

            return array('auth_user' => $key, 'auth_pass' => $value);
        } else {
            return array();
        }
    }

    /**
     * Get the company purchese phone number
     * Created on: 18-07-2019
     *
     * @param $company_sid Integer
     *
     * @return Array|Bool
     */
    function get_company_phone_by_sid($company_sid)
    {
        $result =
            $this->db
            ->select('phone_number')
            ->from('portal_company_sms_module')
            ->where('company_sid', $company_sid)
            ->limit(1)
            ->order_by('sid', 'DESC')
            ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        //
        return !sizeof($result_arr) ? false : $result_arr;
    }


    /**
     * Check company phone record already exist
     * Created on: 17-03-2021
     *
     * @param $company_sid Integer
     *
     * @return Integer|Bool
     */
    function check_company_row_exist($company_sid)
    {
        $result =
            $this->db
            ->select('phone_number')
            ->from('portal_company_sms_module')
            ->where('company_sid', $company_sid)
            ->limit(1)
            ->order_by('sid', 'DESC')
            ->get();
        //
        $result_arr = $result->row_array();
        $result     = $result->free_result();
        //
        return !sizeof($result_arr) ? false : true;
    }

    function update_company_phone_number($company_sid, $dataToUpdate)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->update('portal_company_sms_module', $dataToUpdate);
    }


    /**
     * Save company phone number
     * Created on: 18-07-2019
     *
     * @param $insert_array Array
     *
     * @return Integer|Bool
     */
    function save_company_phone_number($insert_array)
    {
        $inserted = $this->db->insert('portal_company_sms_module', $insert_array);
        return !$inserted ? false : $this->db->insert_id();
    }


    /**
     * Get company column by sid
     * Created on: 24-07-2019
     *
     * @param $company_sid  String
     * @param $column       String Optional
     *
     * @return String|Integer
     */
    function get_company_column($company_sid, $column = 'CompanyName')
    {

        $result = $this
            ->db
            ->select($column)
            ->from('users')
            ->where('parent_sid', 0)
            ->where('sid', $company_sid)
            ->limit(1)
            ->get();

        $result_arr = $result->row_array();
        $result     = $result->free_result();

        return isset($result_arr[$column]) ? $result_arr[$column] : 0;
    }

    function get_customize_career_site_data($company_sid)
    {
        $this->db->select('status, menu, footer, inactive_pages');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('customize_career_site');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        if (isset($record_arr[0])) {
            $record_arr[0]['inactive_pages'] = json_decode($record_arr[0]['inactive_pages'], true);
            return $record_arr[0];
        } else {
            $record_arr['status'] = 0;
            $record_arr['menu'] = 1;
            $record_arr['footer'] = 1;
            $record_arr['inactive_pages'] = [];
            return $record_arr;
        }
    }
    function update_customize_career_site($company_sid, $dataToUpdate)
    {
        $this->db->where('company_sid', $company_sid);
        $q = $this->db->get('customize_career_site');

        if ($q->num_rows() > 0) {
            $this->db->where('company_sid', $company_sid);
            $this->db->update('customize_career_site', $dataToUpdate);
        } else {
            $this->db->set('company_sid', $company_sid);
            $this->db->insert('customize_career_site', $dataToUpdate);
        }
    }
    public function get_career_site_pages($company_sid)
    {
        $this->db->select('page_name,page_title');
        $this->db->where('company_id', $company_sid);
        return $this->db->get('portal_themes_pages')->result_array();
    }
    //
    function getDynamicModulesByCompany($companyId)
    {
        $a = $this->db
            ->select('sid, module_name, "0" as status')
            ->where('is_disabled', 0)
            ->where('stage', 'production')
            ->order_by('module_name', 'ASC')
            ->get('modules');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if (!sizeof($b)) {
            return $b;
        }
        //
        foreach ($b as $k => $v) {
            if (
                $this->db
                ->where('module_sid', $v['sid'])
                ->where('is_active', 1)
                ->where('company_sid', $companyId)
                ->count_all_results('company_modules')
            ) {
                $b[$k]['status'] = 1;
            }
        }

        return $b;
    }

    //
    function update_module_status()
    {
        $post = $this->input->post(NULL, TRUE);
        if (!sizeof($post)) return false;
        // Check if company exists in modules
        if (
            $this->db
            ->where('company_sid', $post['CompanyId'])
            ->where('module_sid', $post['Id'])
            ->count_all_results('company_modules')
        ) {
            $this->db
                ->where('company_sid', $post['CompanyId'])
                ->where('module_sid', $post['Id'])
                ->update('company_modules', array(
                    'is_active' => $post['Status'] == 1 ? 0 : 1
                ));
            return true;
        } else {
            $this->db
                ->insert('company_modules', array(
                    'company_sid' => $post['CompanyId'],
                    'module_sid' => $post['Id'],
                    'is_active' => 1
                ));
            return $this->db->insert_id();
        }
    }
    public function sync_company_details_to_remarket($company_details)
    {
        $this->db->select('sub_domain');
        $this->db->where('user_sid', $company_details['sid']);
        $record_obj = $this->db->get('portal_employer');
        $portal_employer = $record_obj->row_array();
        $company_details['sub_domain'] = $portal_employer['sub_domain'];
        $record_obj = $this->db->select('Logo')
            ->where('sid', $company_details['sid'])
            ->get('users');

        $result =  $record_obj->row_array();
        $record_obj->free_result();
        if (isset($result['Logo']))
            $company_details['Logo'] = $result['Logo'];

        $company_data['company_details'] = $company_details;
        send_settings_to_remarket(REMARKET_PORTAL_SYNC_COMPANY_URL, $company_data);
    }



    //
    function getCompanyActiveEmployees($companySid)
    {
        $a = $this->db
            ->select('sid, first_name, last_name, access_level, access_level_plus, is_executive_admin, pay_plan_flag, job_title')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->order_by('concat(first_name,last_name)', 'ASC', false)
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function getCompanyActiveDepartments($companySid)
    {
        $a = $this->db
            ->select('sid, name')
            ->where('company_sid', $companySid)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->order_by('sort_order', 'ASC')
            ->get('departments_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function getSingleApprover($approversid)
    {
        $a = $this->db
            ->select('employee_sid, department_sid, is_archived, sort_order')
            ->where('sid', $approversid)
            ->get('timeoff_approvers');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function checkApprover($post)
    {
        $this->db
            ->where('employee_sid', $post['employeeSid'])
            ->where('department_sid', $post['departmentSid']);

        if (isset($post['approversid'])) $this->db->where('sid <> ', $post['approversid']);

        return $this->db->count_all_results('timeoff_approvers');
    }

    //
    function changeApproverStatus(
        $sid,
        $status
    ) {
        return $this->db
            ->where('sid', $sid)
            ->update('timeoff_approvers', array(
                'is_archived' => $status
            ));
    }

    //
    function updateApprover(
        $post
    ) {
        return $this->db
            ->where('sid', $post['approverSid'])
            ->update('timeoff_approvers', array(
                'is_archived' => $post['isArchived'],
                'employee_sid' => $post['employeeSid'],
                'department_sid' => $post['departmentSid']
            ));
    }

    //
    function addApprover(
        $post
    ) {
        //
        $a = $this->db
            ->select('sid')
            ->where('parent_sid', $post['companySid'])
            ->where('active', 1)
            ->group_start()
            ->where('is_primary_admin', 1)
            ->or_where('access_level_plus', 1)
            ->group_end()
            ->where('terminated_status', 0)
            ->limit(1)
            ->order_by('is_primary_admin', 'ASC')
            ->get('users');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        $creatorSid = $b['sid'];
        //
        $this->db
            ->insert('timeoff_approvers', array(
                'is_archived' => $post['isArchived'],
                'employee_sid' => $post['employeeSid'],
                'creator_sid' => $creatorSid,
                'company_sid' => $post['companySid'],
                'department_sid' => $post['departmentSid']
            ));
        //
        return $this->db->insert_id();
    }

    //
    function getPhoneNumber($companyId)
    {
        return $this->db
            ->where('company_sid', $companyId)
            ->get('portal_company_sms_module')
            ->row_array();
    }

    //
    function GetCompanyEmail($companyId)
    {
        //
        $query = $this->db->select('email')
            ->where('sid', $companyId)
            ->get('users');
        //
        $b = $query->row_array();
        //
        $query = $query->free_result();
        //
        return $b;
    }

    //
    function UpdateCompanyIndeed(
        $name,
        $email,
        $phone,
        $companyId
    ) {
        //
        if ($this->db->where('company_sid', $companyId)->count_all_results('company_indeed_details')) {
            //
            $this->db->where('company_sid', $companyId)
                ->update('company_indeed_details', [
                    'contact_name' => $name,
                    'contact_email' => $email,
                    'contact_phone' => $phone,
                    'updated_at' => date("Y-m-d H:i:s", strtotime('now'))
                ]);
        } else {
            //
            $this->db
                ->insert('company_indeed_details', [
                    'contact_name' => $name,
                    'contact_email' => $email,
                    'contact_phone' => $phone,
                    'company_sid' => $companyId,
                    'created_at' => date("Y-m-d H:i:s", strtotime('now')),
                    'updated_at' => date("Y-m-d H:i:s", strtotime('now'))
                ]);
        }
    }

    //
    function GetCompanyIndeedDetails($companyId)
    {
        return $this->db->where('company_sid', $companyId)->get('company_indeed_details')->row_array();
    }


    function GetEmployeeById($employeeId, $columns = '*')
    {
        //
        return $this->db
            ->select($columns)
            ->where('sid', $employeeId)
            ->get('users')
            ->row_array();
    }

    function get_all_documents_category($company_sid, $status = NULL, $sort_order = NULL)
    {
        //
        addDefaultCategoriesIntoCompany($company_sid);
        //
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->or_where('sid', PP_CATEGORY_SID);

        if ($status != NULL) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('sort_order', 'asc');

        $records_obj = $this->db->get('documents_category_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_all_assign_documents($company_sid, $employee_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type ', "employee");
        $this->db->where('user_sid ', $employee_sid);

        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('PhoneNumber as phone');
        $this->db->select('verification_key');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_employee_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function check_offer_letter_moved($document_sid, $document_type)
    {
        $this->db->select('*');;
        $this->db->where('doc_sid', $document_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    function insert_documents_assignment_record_history($data_to_insert)
    {
        $this->db->insert('documents_assigned_history', $data_to_insert);
    }

    function disable_all_previous_letter($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_type', $document_type);
        $this->db->set('status', 0);
        $this->db->set('archive', 1);
        $this->db->update('documents_assigned');
    }

    function insertDocumentsAssignmentRecord($data_to_insert)
    {
        $this->db->insert('documents_assigned', $data_to_insert);
        return $this->db->insert_id();
    }

    function add_update_categories_2_documents($document_sid, $categories, $document_type)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->where('document_type', $document_type);
        $this->db->delete('documents_2_category');
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $this->db->insert('documents_2_category', ['document_sid' => $document_sid, 'category_sid' => $category, 'document_type' => $document_type]);
            }
        }
    }

    public function get_employee_status_detail($sid)
    {
        $this->db->select('*');
        $this->db->where('employee_sid', $sid);
        $this->db->order_by('sid', 'DESC');
        $records_obj = $this->db->get('terminated_employees');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function get_terminated_employees_documents($sid, $record_sid)
    {
        $this->db->select('file_name, file_code, file_type');
        $this->db->where('terminated_user_id', $sid);
        $this->db->where('terminated_record_sid', $record_sid);
        $this->db->where('status', 1);
        $result = $this->db->get('terminated_employees_documents')->result_array();
        return $result;
    }

    public function terminate_user($sid, $data)
    {
        //Insert Terminated Data
        $this->db->insert('terminated_employees', $data);
        $record_sid = $this->db->insert_id();

        //Enable Files if uploaded
        $data_to_update = array();
        $data_to_update['status'] = 1;
        $data_to_update['terminated_record_sid'] = $record_sid;
        $this->db->where('status', 0);
        $this->db->where('terminated_user_id', $sid);
        $this->db->where('terminated_record_sid', 0);
        $this->db->update('terminated_employees_documents', $data_to_update);
        //
        return $record_sid;
    }

    public function change_terminate_user_status($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('users', $data_to_update);
    }

    function get_status_by_id($status_id)
    {
        $this->db->where('sid', $status_id);
        return $this->db->get('terminated_employees')->row_array();
    }

    function get_status_documents($status_id)
    {
        $this->db->where('terminated_record_sid', $status_id);
        $this->db->where('status', 1);
        return $this->db->get('terminated_employees_documents')->result_array();
    }

    public function update_terminate_user($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('terminated_employees', $data);
    }

    function check_for_main_status_update($emp_sid, $status_id)
    {
        $this->db->select('sid');
        $this->db->where('employee_sid', $emp_sid);
        $this->db->order_by('sid', 'DESC');
        $status_result = $this->db->get('terminated_employees')->row_array();
        if (sizeof($status_result)) {
            if ($status_result['sid'] == $status_id) {
                return true;
            }
        }
        return false;
    }

    /**
     * Update rehire date in employee status
     * 
     * @param number $employeeId
     * @return
     */
    function updateEmployeeRehireDate($rehireDate, $employeeId, $changed_by)
    {
        //
        $this->db->select('sid');
        $this->db->where('employee_status', 8);
        $this->db->where('employee_sid', $employeeId);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('terminated_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $row_sid = $record_arr['sid'];
            //
            $data_to_update = array();
            $data_to_update['status_change_date'] = $rehireDate;
            //            
            $this->db->where('sid', $row_sid);
            $this->db->update('terminated_employees', $data_to_update);
        } else {
            $data_to_insert = array();
            $data_to_insert['employee_status'] = 8;
            $data_to_insert['details'] = '';
            $data_to_insert['status_change_date'] = $rehireDate;
            $data_to_insert['termination_date'] = $rehireDate;
            $data_to_insert['employee_sid '] = $employeeId;
            $data_to_insert['changed_by'] = $changed_by;
            $data_to_insert['ip_address'] = getUserIP();
            $data_to_insert['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
            $data_to_insert['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $this->db->insert('terminated_employees', $data_to_insert);
        }
    }

    function get_user_data($sid)
    {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('sid', $sid);
        $query_result = $this->db->get();

        if ($query_result->num_rows() > 0) {
            return $row = $query_result->row_array();
        }
    }

    function update_gender_in_eeoc_form($user_type, $user_sid, $dataToUpdate)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        $this->db->from('portal_eeo_form');
        $record_count = $this->db->count_all_results();

        if ($record_count > 0) {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->update('portal_eeo_form', $dataToUpdate);
        }
    }



    function get_company_all_default_categories($company_sid)
    {
        $this->db->select('sid, name, status, sort_order, created_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->group_start();
        $this->db->or_where('is_default', 1);
        $this->db->or_where('default_category_sid!=', 0);
        $this->db->group_end();

        $records_obj = $this->db->get('documents_category_management');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }




    public function get_e_signature($executive_user_sid, $status = 1)
    {

        $this->db->select('*');
        $this->db->where('user_sid', $executive_user_sid);
        if ($status == 1) {
            $this->db->where('status', 1);
        }
        $this->db->from('executive_signatures_data');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }


    public function apply_e_signature($executive_user_sid)
    {
        $executive_user_signature = $this->get_e_signature($executive_user_sid, 1);

        if (empty($executive_user_signature)) {
            return false;
        }

        $this->db->select('executive_admin_sid,company_sid,logged_in_sid');
        $this->db->where('executive_admin_sid', $executive_user_sid);
        $this->db->from('executive_user_companies');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //
        if (empty($records_arr)) {
            return false;
        }
        //
        foreach ($records_arr  as $companies_row) {
            //
            $data_to_save = array();
            $data_to_save['first_name'] = $executive_user_signature['first_name'];
            $data_to_save['last_name'] =  $executive_user_signature['last_name'];
            $data_to_save['email_address'] = $executive_user_signature['email_address'];
            $data_to_save['signature'] = $executive_user_signature['signature'];
            $data_to_save['init_signature'] = $executive_user_signature['init_signature'];
            $data_to_save['signature_hash'] = $executive_user_signature['signature_hash'];
            $data_to_save['signature_timestamp'] = $executive_user_signature['signature_timestamp'];
            $data_to_save['signature_bas64_image'] = $executive_user_signature['signature_bas64_image'];
            $data_to_save['init_signature_bas64_image'] = $executive_user_signature['init_signature_bas64_image'];
            $data_to_save['active_signature'] = $executive_user_signature['active_signature'];
            $data_to_save['ip_address'] = $executive_user_signature['ip_address'];
            $data_to_save['user_agent'] = $executive_user_signature['user_agent'];
            $data_to_save['user_consent'] = $executive_user_signature['user_consent'];
            //
            $whereArray = [
                'company_sid' => $companies_row['company_sid'],
                'user_sid' => $companies_row['logged_in_sid'],
                'user_type' => 'employee'
            ];
            // Check if the e signature is already been assigned
            if ($this->db->where($whereArray)->count_all_results('e_signatures_data')) {
                //
                $this->db->where($whereArray)->update('e_signatures_data', $data_to_save);
            } else {
                //
                $data_to_save['company_sid'] = $companies_row['company_sid'];
                $data_to_save['user_sid'] = $companies_row['logged_in_sid'];
                $data_to_save['user_type'] = 'employee';
                $data_to_save['is_active'] = 1;
                //
                $this->db->insert('e_signatures_data', $data_to_save);
            }
        }
        return true;
    }

    //
    function add_new_employer_to_team($data_to_insert)
    {

        $this->db->insert('departments_employee_2_team', $data_to_insert);
        return $this->db->insert_id();
    }


    //
    public function employees_transfer_log_update($sid, $data_transfer_log_update)
    {

        $this->db->select('new_employee_sid');
        $this->db->where('new_employee_sid', $sid);
        $result = $this->db->get('employees_transfer_log')->row_array();

        if (!empty($result)) {
            $data_update['employee_copy_date'] = $data_transfer_log_update['employee_copy_date'];
            $this->db->where('new_employee_sid', $sid);
            $this->db->update('employees_transfer_log', $data_update);
        } else {
            $data_transfer_log_update['from_company_sid'] = 0;
            $data_transfer_log_update['previous_employee_sid'] = 0;
            $data_transfer_log_update['employee_copy_date'] = $data_transfer_log_update['employee_copy_date'];
            $data_transfer_log_update['last_update'] = date('Y-m-d H:i:s');
            $data_transfer_log_update['new_employee_sid'] = $sid;
            $this->db->insert('employees_transfer_log', $data_transfer_log_update);
        }
    }

    //

    function get_helpbox_info($company_sid)
    {
        $this->db->where('company_id', $company_sid);
        $records_obj = $this->db->get('helpbox_info_for_company');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function add_update_helpbox_info($company_sid, $helpboxTitle, $helpboxEmail, $helpboxPhoneNumber, $helpboxStatus, $helpButtonText)
    {
        $dataToInsert = array();
        $dataToInsert['company_id'] = $company_sid;
        $dataToInsert['box_title'] = $helpboxTitle;
        $dataToInsert['box_support_email'] = $helpboxEmail;
        $dataToInsert['box_support_phone_number'] = $helpboxPhoneNumber;
        $dataToInsert['box_status'] = $helpboxStatus;
        $dataToInsert['box_status'] = $helpboxStatus;
        $dataToInsert['button_text'] = $helpButtonText;
        //
        if (
            $this->db
            ->where('company_id', $company_sid)
            ->count_all_results('helpbox_info_for_company')
        ) {
            $this->db->where('company_id', $company_sid);
            $this->db->update('helpbox_info_for_company', $dataToInsert);
        } else {
            $this->db->insert('helpbox_info_for_company', $dataToInsert);
        }
    }

    //
    function set_bulk_email_status($company_sid, $bulk_email_status)
    {
        $data = array();
        $data['bulk_email'] = intval($bulk_email_status);
        $this->db->where('user_sid', $company_sid);
        $this->db->update('portal_employer', $data);
    }



    //
    function get_executive_user_logged_in_sids($sid)
    {
        $this->db->select('logged_in_sid');
        $this->db->where('executive_admin_sid', $sid);
        $record_obj = $this->db->get('executive_user_companies');
        $data = $record_obj->result_array();
        $logged_in_sid = [];
        if (!empty($data)) {
            foreach ($data as $key => $val) {
                $logged_in_sid[] = $val['logged_in_sid'];
            }
        }
        return $logged_in_sid;
    }




    function set_executive_access_level_plus($sids, $action)
    {
        $data = array();
        $data['access_level_plus'] = $action == 1 ? '1' : '0';
        $this->db->where_in('sid', $sids);
        $this->db->update('users', $data);
    }



    //
    function set_executive_access_level_plus_single_company($executiveAdminSid, $companySid, $action)
    {

        $this->db->select('logged_in_sid');
        $this->db->where('executive_admin_sid', $executiveAdminSid);
        $this->db->where('company_sid', $companySid);
        $result = $this->db->get('executive_user_companies')->row_array();

        if (!empty($result)) {

            $data = array();
            if ($action == 'mark_admin_plus') {
                $data['access_level_plus'] =  1;
            } elseif ($action == 'unmark_admin_plus') {
                $data['access_level_plus'] =  0;
            }

            $this->db->where('sid', $result['logged_in_sid']);
            $this->db->where('parent_sid', $companySid);
            $this->db->update('users', $data);
        }
    }



    function getEmployesTransferLog($limit, $offset, $keyword = null, $status = 2, $count_only = false, $company = null, $contact_name = null)
    {
        $this->db->select('table_one.sid');
        $this->db->select('table_one.first_name');
        $this->db->select('table_one.last_name');
        $this->db->select('table_one.middle_name');
        $this->db->select('table_one.nick_name');
        $this->db->select('table_one.username');
        $this->db->select('table_one.password');
        $this->db->select('table_one.email');
        $this->db->select('table_one.job_title');
        $this->db->select('table_one.registration_date');
        $this->db->select('table_one.joined_at');
        $this->db->select('table_one.rehire_date');
        $this->db->select('table_one.access_level');
        $this->db->select('table_one.access_level_plus');
        $this->db->select('table_one.pay_plan_flag');
        $this->db->select('table_one.profile_picture');
        $this->db->select('table_one.active');
        $this->db->select('table_one.archived');
        $this->db->select('table_one.system_user_date');
        $this->db->select('table_one.general_status');
        $this->db->select('table_two.CompanyName as company_name');
        $this->db->select('table_one.complynet_onboard');
        $this->db->select('table_one.parent_sid');
        $this->db->select('table_one.transfer_date');
        $this->db->select('table_one.languages_speak');

        $this->db->select('table_one.complynet_job_title');


        $this->db->where('table_one.is_executive_admin <', 1);
        $this->db->where('table_one.parent_sid > ', 0);

        /*
        if ($status != 2) {
            $this->db->where('table_one.active', $status);
        }
      */

        if ($status == 'active') {
            $this->db->where('table_one.active', 1);
            $this->db->where('table_one.terminated_status', 0);
        }

        if ($status == 'terminated') {
            $this->db->where('table_one.terminated_status', 1);
        }

        if ($status != 'all' && $status != 'active' && $status != 'terminated') {
            $this->db->where('LCASE(table_one.general_status) ', $status);
        }



        $this->db->order_by('table_one.sid', 'desc');

        if ($company != null && $company != 'all') {
            $this->db->group_start();
            $this->db->like('REPLACE(table_two.CompanyName, " ", "") ', str_replace(' ', '', $company));
            $this->db->group_end();
        }

        if (($keyword != null && $keyword != 'all')) {
            $multiple_keywords = explode(',', $keyword);
            $this->db->group_start();

            for ($i = 0; $i < count($multiple_keywords); $i++) {
                $phoneRegex = strpos($multiple_keywords[$i], '@') !== false ? '' : preg_replace('/[^0-9]/', '', $multiple_keywords[$i]);
                $this->db->or_like('table_one.email', $multiple_keywords[$i]);
                $this->db->or_like('table_one.username', $multiple_keywords[$i]);
                if ($phoneRegex) {
                    $this->db->or_like('REGEXP_REPLACE(table_one.PhoneNumber, "[^0-9]", "")', preg_replace('/[^0-9]/', '', $multiple_keywords[$i]), false);
                }
                $this->db->or_like('table_one.job_title', $multiple_keywords[$i]);
                $this->db->or_like('table_one.access_level', $multiple_keywords[$i]);
                $this->db->or_like('table_one.registration_date', $multiple_keywords[$i]);
            }

            $this->db->group_end();
        }


        if ($contact_name != null && $contact_name != 'all') {
            $this->db->group_start();
            $this->db->where("(lower(concat(table_one.first_name,'',table_one.last_name)) LIKE '%" . (preg_replace('/\s+/', '', strtolower($contact_name))) . "%' or table_one.nick_name LIKE '%" . (preg_replace('/\s+/', '', strtolower($contact_name))) . "%')  ");
            $this->db->group_end();
        }


        $this->db->join('users as table_two', 'table_one.parent_sid = table_two.sid', 'left');
        $this->db->from('users as table_one');

        if ($count_only == true) {
            return $this->db->count_all_results();
        } else {
            $this->db->limit($limit, $offset);
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            //
            $this->GetEmployeeStatus($records_arr);

            $this->GetEmployeeDepartmentsTeams($records_arr);
            //
            return $records_arr;
        }
    }



    //
    public function checkIsEmployeeTransferred($employeeId)
    {
        $this->db->select('employees_transfer_log.sid, employees_transfer_log.previous_employee_sid, employees_transfer_log.from_company_sid, employees_transfer_log.new_employee_sid, employees_transfer_log.to_company_sid,employees_transfer_log.employee_copy_date,users.CompanyName as fromcompany , tocompany.CompanyName as tocompany');
        $this->db->join('users', 'employees_transfer_log.from_company_sid = users.sid');
        $this->db->join('users as tocompany', 'employees_transfer_log.to_company_sid = tocompany.sid');
        $this->db->where_in('new_employee_sid', $employeeId);
        $this->db->order_by('sid', 'DESC');
        $record_obj = $this->db->get('employees_transfer_log');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {

            //
            $assignedDocuments = $this->get_assigned_documents_history($record_arr['from_company_sid'], $record_arr['previous_employee_sid']);

            $docw4 = $this->get_w4_documents_history($record_arr['previous_employee_sid']);
            $doci9 = $this->get_i9_documents_history($record_arr['previous_employee_sid']);
            $docw9 = $this->get_w9_documents_history($record_arr['previous_employee_sid']);

            $emergencyContact = $this->get_emergency_contacts_history($record_arr['previous_employee_sid']);
            $dependents = $this->get_dependents_history($record_arr['previous_employee_sid']);
            $directDeposit = $this->get_direct_deposit_history($record_arr['previous_employee_sid']);
            $license = $this->get_license_history($record_arr['previous_employee_sid']);

            $resultArray[$record_arr['sid']] = [
                'newCompanyId' => $record_arr['to_company_sid'],
                'newEmployeeId' => $record_arr['new_employee_sid'],
                'oldEmployeeId' => $record_arr['previous_employee_sid'],
                'oldCompanyId' => $record_arr['from_company_sid'],
                'copyDate' => $record_arr['employee_copy_date'],
                'fromCompany' => $record_arr['fromcompany'],
                'toCompany' => $record_arr['tocompany'],
                'assignedDocuments' => $assignedDocuments,
                'docw4' => $docw4,
                'docw9' => $docw9,
                'doci9' => $doci9,
                'emergencyContact' => $emergencyContact,
                'dependents' => $dependents,
                'directDeposit' => $directDeposit,
                'license' => $license,

            ];

            //
            $secondaryResult = $this->isSecondaryEmployeeTransferred($record_arr['previous_employee_sid'], $record_arr['from_company_sid'], $resultArray);
            return $secondaryResult;
        } else {
            return array();
        }
    }

    public function isSecondaryEmployeeTransferred($employeeId, $companyId, $resultArray)
    {

        $this->db->select('employees_transfer_log.sid, employees_transfer_log.previous_employee_sid, employees_transfer_log.from_company_sid, employees_transfer_log.new_employee_sid, employees_transfer_log.to_company_sid,employees_transfer_log.employee_copy_date,users.CompanyName as fromcompany,tocompany.CompanyName as tocompany');
        $this->db->join('users', 'employees_transfer_log.from_company_sid = users.sid');
        $this->db->join('users as tocompany', 'employees_transfer_log.to_company_sid = tocompany.sid');
        $this->db->where('new_employee_sid', $employeeId);
        $this->db->where('to_company_sid', $companyId);
        $record_obj = $this->db->get('employees_transfer_log');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            //
            if (!array_key_exists($record_arr['sid'], $resultArray)) {

                $assignedDocuments = $this->get_assigned_documents_history($record_arr['from_company_sid'], $record_arr['previous_employee_sid']);
                $docw4 = $this->get_w4_documents_history($record_arr['previous_employee_sid']);
                $doci9 = $this->get_i9_documents_history($record_arr['previous_employee_sid']);
                $docw9 = $this->get_w9_documents_history($record_arr['previous_employee_sid']);
                $emergencyContact = $this->get_emergency_contacts_history($record_arr['previous_employee_sid']);
                $dependents = $this->get_dependents_history($record_arr['previous_employee_sid']);
                $directDeposit = $this->get_direct_deposit_history($record_arr['previous_employee_sid']);
                $license = $this->get_license_history($record_arr['previous_employee_sid']);

                $resultArray[$record_arr['sid']] = [
                    'newCompanyId' => $record_arr['to_company_sid'],
                    'newEmployeeId' => $record_arr['new_employee_sid'],
                    'oldEmployeeId' => $record_arr['previous_employee_sid'],
                    'oldCompanyId' => $record_arr['from_company_sid'],
                    'copyDate' => $record_arr['employee_copy_date'],
                    'fromCompany' => $record_arr['fromcompany'],
                    'toCompany' => $record_arr['tocompany'],
                    'assignedDocuments' => $assignedDocuments,
                    'docw4' => $docw4,
                    'docw9' => $docw9,
                    'doci9' => $doci9,
                    'emergencyContact' => $emergencyContact,
                    'dependents' => $dependents,
                    'directDeposit' => $directDeposit,
                    'license' => $license,

                ];
                return $this->isSecondaryEmployeeTransferred($record_arr['previous_employee_sid'], $record_arr['from_company_sid'], $resultArray);
            }
        }
        return $resultArray;
    }


    function get_assigned_documents_history($company_sid, $employee_sid)
    {

        $this->db->select('document_type,document_title');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $employee_sid);
        $this->db->where('user_type', 'employee');

        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }


    //
    function get_emergency_contacts_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('users_sid', $employee_sid);
        $this->db->where('users_type', 'employee');
        $this->db->from('emergency_contacts');
        return $this->db->count_all_results();
    }

    //
    function get_license_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('users_sid', $employee_sid);
        $this->db->where('users_type', 'employee');
        $this->db->from('license_information');
        return $this->db->count_all_results();
    }

    //
    function get_dependents_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('users_sid', $employee_sid);
        $this->db->where('users_type', 'employee');
        $this->db->from('dependant_information');
        return $this->db->count_all_results();
    }

    //
    function get_direct_deposit_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('users_sid', $employee_sid);
        $this->db->where('users_type', 'employee');
        $this->db->from('bank_account_details');
        return $this->db->count_all_results();
    }
    //
    function get_w4_documents_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('employer_sid', $employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->from('form_w4_original');
        return $this->db->count_all_results();
    }

    //
    function get_w9_documents_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('user_sid', $employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->from('applicant_w9form');
        return $this->db->count_all_results();
    }

    //
    function get_i9_documents_history($employee_sid)
    {
        $this->db->select('sid');
        $this->db->where('user_sid', $employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->from('applicant_i9form');
        return $this->db->count_all_results();
    }

    public function deleteEmployeeStatus($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('terminated_employees');
    }

    //
    function update_incident_status($sid, $data)
    {
        $this->db->where('company_sid', $sid);
        $this->db->where('module_sid', '13');
        $this->db->update('company_modules', $data);
    }


    //
    function getDocumentsStatusNew($company_sid)
    {
        //
        $documentsArray = [];

        $companyData = $this->db
            ->select('CompanyName')
            ->where('sid', $company_sid)
            ->get('users')
            ->row_array();

        //
        $documentCreditCard = $this->db
            ->select('status as cc_auth_status')
            ->where('company_sid', $company_sid)
            ->order_by('sid', 'DESC')
            ->get('form_document_credit_card_authorization')
            ->row_array();

        if (empty($documentCreditCard)) {
            $documentCreditCard['cc_auth_status'] = '';
        }

        //
        $documentEula = $this->db
            ->select('status as eula_status')
            ->where('company_sid', $company_sid)
            ->order_by('sid', 'DESC')
            ->get('form_document_eula')
            ->row_array();

        if (empty($documentEula)) {
            $documentEula['eula_status'] = '';
        }

        //
        $payrollAgreement = $this->db
            ->select('status as fpa_status')
            ->where('company_sid', $company_sid)
            ->order_by('sid', 'DESC')
            ->get('form_payroll_agreement')
            ->row_array();

        if (empty($payrollAgreement)) {
            $payrollAgreement['fpa_status'] = '';
        }

        //
        $documentCompanyContacts = $this->db
            ->select('status as company_contacts_status')
            ->where('company_sid', $company_sid)
            ->order_by('sid', 'DESC')
            ->get('form_document_company_contacts')
            ->row_array();

        if (empty($documentCompanyContacts)) {
            $documentCompanyContacts['company_contacts_status'] = '';
        }

        //
        $payrollCreditCardAuthorization = $this->db
            ->select('status as payroll_cc_auth_status')
            ->where('company_sid', $company_sid)
            ->order_by('sid', 'DESC')
            ->get('form_document_payroll_credit_card_authorization')
            ->row_array();

        if (empty($payrollCreditCardAuthorization)) {
            $payrollCreditCardAuthorization['company_contacts_status'] = '';
        }

        //
        $documentsArray = array_merge($documentsArray, $companyData, $documentCreditCard, $documentEula, $payrollAgreement, $documentCompanyContacts, $payrollCreditCardAuthorization);

        return  $documentsArray;
    }
}

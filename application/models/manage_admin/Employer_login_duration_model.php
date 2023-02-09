<?php

class Employer_login_duration_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_companies($columns = '*') {
        $excluded_companies = $this->get_excluded_company_sids();
        $this->db->select(is_array($columns) ? implode(',', $columns) : $columns);
        $this->db->where('parent_sid', 0);
        if (!empty($excluded_companies)) {
            $this->db->where('sid NOT IN ( ' . implode(',', $excluded_companies) . ' )');
        }
        $this->db->order_by('CompanyName', 'ASC');
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('active', 1);
        if(is_array($columns)){
            $this->db->where('is_paid', 1);
        }
        return $this->db->get('users')->result_array();
    }

    public function get_all_employers($company_sid) {
        $this->db->select('*');
        $this->db->where('parent_sid', $company_sid);
        $this->db->order_by('sid', 'DESC');
        return $this->db->get('users')->result_array();
    }

    public function get_activity_log($employer_sid, $year, $month, $day, $hour) {
        $this->db->select('parent_sid');
        $this->db->where('sid', $employer_sid);
        $employer_info = $this->db->get('users')->result_array();

        if (!empty($employer_info)) {
            $employer_info = $employer_info[0];
            $company_sid = $employer_info['parent_sid'];
            $date_start = $year . '-' . $month . '-' . $day . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00:00';
            $date_end = $year . '-' . $month . '-' . $day . ' ' . str_pad($hour, 2, '0', STR_PAD_LEFT) . ':59:59';
            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $employer_sid);
            $this->db->where("action_timestamp >=", $date_start);
            $this->db->where("action_timestamp <=", $date_end);
            return $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $date_start))->result_array();
        }
    }

    public function get_active_employers($company_sid, $start_date, $end_date) {
        $this->db->distinct();
        $this->db->select('employer_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where("action_timestamp >=", $start_date);
        $this->db->where("action_timestamp <=", $end_date);
        return $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
    }

    public function get_activity_log_company($company_sid, $date = '12/30/2016') {
        $date_parts = explode('/', $date);
        $start_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1] . ' ' . ' 00:00:00';
        $end_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1] . ' ' . '23:59:59';
        $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);

        foreach ($active_employers as $key => $active_employer) {
            $this->db->select('*');
            $this->db->where("action_timestamp >=", $start_date);
            $this->db->where("action_timestamp <=", $end_date);
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $active_employer['employer_sid']);
            $this->db->order_by('employer_ip', 'DESC');
            $employer_logs = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();

            if (!empty($employer_logs)) {
                $active_employers[$key]['activity_logs'] = $employer_logs;
            }
        }

        return $active_employers;
    }

    public function generate_activity_log_data_for_view($company_sid, $start_date, $end_date, $employer_sid = null) {
        //Handle Manual Employer Sid Feed
        if ($employer_sid == null) {
            $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);
        } else {
            $active_employers = array();
            $active_employers[] = array('employer_sid' => $employer_sid);
        }

        foreach ($active_employers as $key => $active_employer) {
            $this->db->select('*');
            $this->db->where("action_timestamp >=", $start_date);
            $this->db->where("action_timestamp <=", $end_date);
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $active_employer['employer_sid']);
            $this->db->order_by('action_timestamp', 'ASC');
            $this->db->order_by('employer_ip', 'ASC');
            $this->db->limit(10);
            $employer_logs = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
            $logs_to_return = array();

            if (!empty($employer_logs)) {
                $first_log = $employer_logs[0];

                foreach ($employer_logs as $employer_log) {
                    $first_ip = $first_log['employer_ip'];
                    $first_ip_array_key = str_replace('.', '_', $first_ip);
                    $first_ip_array_key = str_replace('::1', '000_000_000_000', $first_ip_array_key);
                    $current_ip = $employer_log['employer_ip'];
                    $current_ip_array_key = str_replace('.', '_', $current_ip);
                    $current_ip_array_key = str_replace('::1', '000_000_000_000', $current_ip_array_key);

                    if ($first_ip_array_key == $current_ip_array_key) {
                        $logs_to_return[$first_ip_array_key][] = $employer_log;
                    } else {
                        $logs_to_return[$current_ip_array_key][] = $employer_log;
                        $first_log = $employer_log;
                    }
                }

                $active_employers[$key]['activity_logs'] = $logs_to_return;
            }
        }


        foreach ($active_employers as $emp_key => $employer) {
            $activity_logs = $employer['activity_logs'];
            $time_spent = 10;

            foreach ($activity_logs as $ip_log_key => $ip_logs) {
                $time_spent = 10;
                $first_activity = $ip_logs[0];

                foreach ($ip_logs as $act_key => $activity_log) {
                    $first_activity_datetime = new DateTime($first_activity['action_timestamp']);
                    $current_activity_datetime = new DateTime($activity_log['action_timestamp']);
                    $first_activity_ten_min_window = $first_activity_datetime->add(date_interval_create_from_date_string('10 min'));
                    $first_activity_ten_min_window_unix = $first_activity_ten_min_window->getTimestamp();
                    $current_activity_datetime_unix = $current_activity_datetime->getTimestamp();
                    $first_ip = $first_activity['employer_ip'];
                    $first_ip_array_key = str_replace('.', '_', $first_ip);
                    $first_ip_array_key = str_replace('::1', '000_000_000_000', $first_ip_array_key);
                    $current_ip = $activity_log['employer_ip'];
                    $current_ip_array_key = str_replace('.', '_', $current_ip);
                    $current_ip_array_key = str_replace('::1', '000_000_000_000', $current_ip_array_key);

                    if ($current_activity_datetime_unix < $first_activity_ten_min_window_unix) {
                        
                    } else {
                        if ($first_ip_array_key == $current_ip_array_key) {
                            $time_spent = $time_spent + 10;
                        } else {
                            $time_spent = 10;
                        }

                        $first_activity = $activity_log;
                    }

                    $employer_activity_records['activities'][$ip_log_key]['act_details'] = $activity_log;
                    $employer_activity_records['activities'][$ip_log_key]['time_spent'] = $time_spent;
                    $employer_activity_records['activities'][$ip_log_key]['log_count'] = $act_key + 1;
                    $active_employers[$emp_key]['employer_name'] = $activity_log['employer_name'];
                }

                $time_spent = 10;
            }

            /* $active_employers[$emp_key]['employer_info']['total_time_spent'] = $total_time_spent;
              $active_employers[$emp_key]['employer_info']['record_count'] = $total_record_count; */

            $active_employers[$emp_key]['activity_logs'] = $employer_activity_records['activities'];
        }

        foreach ($active_employers as $employer_key => $employer_logs) {
            $total_time_spent = 0;
            $total_logs = 0;
            
            foreach ($employer_logs['activity_logs'] as $ip_logs) {
                $total_time_spent += intval($ip_logs['time_spent']);
                $active_employers[$employer_key]['total_time_spent'] = $total_time_spent;
                $total_logs += intval($ip_logs['log_count']);
                $active_employers[$employer_key]['total_logs'] = $total_logs;
            }
        }

        return $active_employers;
    }

    public function get_all_inactive_employees($company_sid, $start_date, $end_date, $columns = '*') {
        $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);
        $active_employers_sids = array();
        
        foreach ($active_employers as $active_employer) {
            $active_employers_sids[] = $active_employer['employer_sid'];
        }

        $this->db->select(is_array($columns) ? implode(',', $columns) : $columns);
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_executive_admin', 0);

        if (!empty($active_employers_sids)) {
            $this->db->where('sid NOT IN ( ' . implode(',', $active_employers_sids) . ' )');
        }

        $inactive_employers = $this->db->get('users')->result_array();
        return $inactive_employers;
    }

    public function get_company_activity_overview($start_date, $end_date, $get_details = false) {
        $companies = $this->get_all_companies();

        foreach ($companies as $key => $company) {
            $company_sid = $company['sid'];
            //Get Activity Information from Activity Tracker
            $this->db->distinct();
            $this->db->select('employer_sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where("action_timestamp >=", $start_date);
            $this->db->where("action_timestamp <=", $end_date);
            $active_employers = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
            $active_employers_sids = array();
            
            foreach ($active_employers as $active_employer) {
                $active_employers_sids[] = $active_employer['employer_sid'];
            }
            //Get Inactive Employers
            $inactive_employers = array();
            $this->db->select('*');
            $this->db->where('parent_sid', $company_sid);
            $this->db->order_by('is_executive_admin', 'DESC');
            $this->db->order_by('access_level', 'ASC');

            if (!empty($active_employers_sids)) {
                $this->db->where('sid NOT IN ( ' . implode(',', $active_employers_sids) . ' )');
            }

            $inactive_employers = $this->db->get('users')->result_array();
            //Get Active Employers
            $active_employers = array();

            if (!empty($active_employers_sids)) {
                $this->db->select('*');
                $this->db->where('parent_sid', $company_sid);
                $this->db->order_by('is_executive_admin', 'DESC');
                $this->db->order_by('access_level', 'ASC');
                $this->db->where('sid IN ( ' . implode(',', $active_employers_sids) . ' )');
                $active_employers = $this->db->get('users')->result_array();
            }

            if ($get_details == true) {
                if (!empty($active_employers)) {
                    foreach ($active_employers as $act_emp_key => $active_employer) {
                        $activity_details = $this->generate_activity_log_data_for_view($company_sid, $start_date, $end_date, $active_employer['sid']);
                        
                        if (!empty($activity_details)) {
                            $active_employers[$act_emp_key]['details'] = $activity_details[0];
                        } else {
                            $active_employers[$act_emp_key]['details'] = array();
                        }
                    }
                }
            }

            $companies[$key]['active_employers'] = $active_employers;
            $companies[$key]['inactive_employers'] = $inactive_employers;
        }

        return $companies;
    }

    function get_excluded_company_sids() {
        $this->db->select('company_sid');
        $this->db->from('companies_excluded_from_reporting');
        $record_obj = $this->db->get();
        $companies = $record_obj->result_array();
        $record_obj->free_result();
        $return_array = array();

        foreach ($companies as $company) {
            $return_array[] = $company['company_sid'];
        }
        
        return $return_array;
    }

    /**
     * Get user active timeframe
     * Created on: 15-08-2019
     *
     * @param $employerId Integer
     * @param $startDate  String    (YYYY-MM-DD)
     * @param $endDate    String    (YYYY-MM-DD)
     *
     * @return  Array|Bool
     */
    function getEmployeeActivityLog(
        $employerId, 
        $startDate, 
        $endDate
    ){
        // Check if employer exists
        $result = $this->db
        ->select('parent_sid')
        ->from('users')
        ->where('sid', $employerId)
        ->get();
        //
        $employerInfo = $result->row_array();
        $result       = $result->free_result();
        //
        if(!sizeof($employerInfo)) return false;
        //
        $companyId = $employerInfo['parent_sid'];
        //
        $result = $this->db
        ->select('DISTINCT DATE_FORMAT(action_timestamp, "%d-%H") as action_timestamp')
        ->from(checkAndGetArchiveTable('logged_in_activitiy_tracker', $startDate))
        ->where('company_sid', $companyId)
        ->where('employer_sid', $employerId)
        ->where("action_timestamp >=", $startDate)
        ->where("action_timestamp <=", $endDate)
        ->order_by('sid', 'ASC')
        ->get();
        //
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        return $result_arr;
    }

    //
    function GetTrackerCE($start_date, $end_date){
        $this->db->select('company_sid, company_name')->distinct();
        $this->db->where("action_timestamp >=", $start_date);
        $this->db->where("action_timestamp <=", $end_date);
        $query = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date));
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        if(empty($result)){
            return [];
        }
        //
        return $result;
    }

    //
    function GetTrackerReport(
        $start_date,
        $end_date,
        $companyId
    ){
        $this->db->select('
            employer_sid,
            employer_name,
            user_agent,
            action_timestamp,
            employer_ip
        ');
        $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        $this->db->where('company_sid', $companyId);
        $this->db->order_by('action_timestamp', 'ASC');
        //
        $query = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date));
        //
        $records = $query->result_array();
        //
        $query->free_result();
        //
        if(empty($records)){
            return [];
        }
        //
        $mainEmployeeArray = [];
        //
        foreach($records as $record){
            //
            $ip = str_replace('::1', '000_000_000_000', str_replace('.', '_', $record['employer_ip']));
            //
            if(!isset($mainEmployeeArray[$record['employer_sid']])){
                $mainEmployeeArray[$record['employer_sid']] = [
                    'time_spent' => 0,
                    'log_count' => 0,
                    'employee_name' => $record['employer_name'],
                    'first_activity' => $record,
                    'first_ip' => $ip
                ];
            }
            //
            if(!isset($mainEmployeeArray[$record['employer_sid']]['ips'][$ip])){
                $mainEmployeeArray[$record['employer_sid']]['ips'][$ip] = [
                    'time_spent' => 0,
                    'user_agent' => ''
                ];
            }
            //
            $first_activity = $mainEmployeeArray[$record['employer_sid']]['first_activity'];
            //
            $first_time = addTimeToDate($first_activity['action_timestamp'], 'T10M', 'Y-m-d H:i:s');
            //
            $current_time = $record['action_timestamp'];
            //
            if($current_time >= $first_time){
                //
                if($mainEmployeeArray[$record['employer_sid']]['first_ip'] == $ip){
                } else{
                    // $mainEmployeeArray[$record['employer_sid']]['time_spent'] = 10;
                }
                //
                $mainEmployeeArray[$record['employer_sid']]['log_count'] ++;
                // Replace first with current
                $mainEmployeeArray[$record['employer_sid']]['first_activity'] = $record;
                $mainEmployeeArray[$record['employer_sid']]['first_ip'] = $ip;
            }
            //
            $mainEmployeeArray[$record['employer_sid']]['time_spent'] += 10;
            $mainEmployeeArray[$record['employer_sid']]['ips'][$ip]['time_spent'] += 10;
            $mainEmployeeArray[$record['employer_sid']]['ips'][$ip]['user_agent'] = $record['user_agent'];
        }
        //
        return array_values($mainEmployeeArray);
    }

    /**
     * Get active companies
     * Created on: 03-10-2022
     *
     * 
     * @param $start_date  String    (YYYY-MM-DD)
     * @param $end_date    String    (YYYY-MM-DD)
     *
     * @return  Array
     */
    function GetTrackerCompanies($start_date, $end_date){
        $this->db->select('company_sid, company_name')->distinct();
        $this->db->where("action_timestamp >=", $start_date);
        $this->db->where("action_timestamp <=", $end_date);
        $this->db->order_by('company_name', 'ASC');
        $query = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date));
        //
        $result = $query->result_array();
        //
        $query->free_result();
        //
        if(empty($result)){
            return [];
        }
        //
        return $result;
    }

    /**
     * Get user active timeframe new
     * Created on: 03-10-2022
     *
     * @param $company_sid Integer
     * @param $start_date  String    (YYYY-MM-DD)
     * @param $end_date    String    (YYYY-MM-DD)
     *
     * @return  Array
     */
    public function generate_company_employes_activity_log_detail($company_sid, $start_date, $end_date) {
        //
        $this->db->select('*');
        $this->db->where("action_timestamp >=", $start_date);
        $this->db->where("action_timestamp <=", $end_date);
        $this->db->where('company_sid', $company_sid);
        $company_logs = $this->db->get(checkAndGetArchiveTable('logged_in_activitiy_tracker', $start_date))->result_array();
        //
        $logs_to_return = array();
        //
        if (!empty($company_logs)) {
            //
            foreach ($company_logs as $log) {
                $employee_sid = $log["employer_sid"];
                $current_employee_ip = $log["employer_ip"];
                //
                // Push employee into an array if not exist in it
                if (!array_key_exists($employee_sid, $logs_to_return)) {
                    $logs_to_return[$employee_sid]["employee_name"] = $log["employer_name"];
                    $logs_to_return[$employee_sid]["total_time_spent_in_minutes"] = 0;
                    //
                    $activity_log = array();
                    $activity_log['last_action_timestamp'] =  $log["action_timestamp"];
                    $activity_log['user_agent'] =  $log["user_agent"];
                    $activity_log['minutes'] =  0;
                    //
                    $logs_to_return[$employee_sid]["ips"][$current_employee_ip] =  $activity_log;
                }
                //
                //Push new IP into ips array if not exist in employee ips
                if (!array_key_exists($current_employee_ip, $logs_to_return[$employee_sid]["ips"])) {
                    //
                    $activity_log = array();
                    $activity_log['last_action_timestamp'] =  $log["action_timestamp"];
                    $activity_log['user_agent'] =  $log["user_agent"];
                    $activity_log['minutes'] =  0;
                    //
                    $logs_to_return[$employee_sid]["ips"][$current_employee_ip] =  $activity_log;
                }
                //
                // Calculate time between two timestamps start   
                $previous_hours = $logs_to_return[$employee_sid]["ips"][$current_employee_ip]['hours'];
                $previous_minutes = $logs_to_return[$employee_sid]["ips"][$current_employee_ip]['minutes'];
                //
                $previous_timestamp = new DateTime($logs_to_return[$employee_sid]["ips"][$current_employee_ip]['last_action_timestamp']);
                $current_timestamp = new DateTime($log["action_timestamp"]);
                //
                if ($previous_timestamp < $current_timestamp) {
                    //
                    $logs_to_return[$employee_sid]["ips"][$current_employee_ip]['last_action_timestamp'] =  $log["action_timestamp"];
                    //
                    $current_hours = $previous_timestamp->diff($current_timestamp)->h;
                    $current_minutes = $previous_timestamp->diff($current_timestamp)->i;
                    //
                    $logs_to_return[$employee_sid]["ips"][$current_employee_ip]['minutes'] = $previous_minutes + $current_minutes + ($current_hours * 60);
                    //
                    $logs_to_return[$employee_sid]["total_time_spent_in_minutes"] += $current_minutes + ($current_hours * 60);
                    
                }
                // Calculate time between two timestamps end
            }
        }

        $logs_to_return = $this->fix_zero_iteration($logs_to_return);

        return $logs_to_return;
    }

    public function get_company_activity_overview_new($company_sid, $start_date, $end_date, $columns = '*') {
        //
        $company_active_employees = $this->get_active_employers($company_sid, $start_date, $end_date);
        //
        $active_employees_sids = array();
        //   
        if (!empty($company_active_employees)) {
            foreach ($company_active_employees as $active_employee) {
                array_push($active_employees_sids, $active_employee['employer_sid']);
            }
        } 
        //
        $this->db->select(is_array($columns) ? implode(',', $columns) : $columns);
        $this->db->where('parent_sid', $company_sid);
        $this->db->order_by('is_executive_admin', 'DESC');
        $this->db->order_by('access_level', 'ASC');
        $company_employees = $this->db->get('users')->result_array();
        //
        $active_employees = array();
        $inactive_employees = array();
        //
        foreach ($company_employees as $key => $employee) {
            //
            $employee["access_level"]   = ucwords($employee['access_level']);
            $employee["employee_name"]  = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
            $employee["PhoneNumber"]    = $employee['PhoneNumber'] == '' ? 'Not Available' : $employee['PhoneNumber'];
            $employee["job_title"]      = $employee['job_title'] != '' ? ucwords($employee['job_title']) : 'Not Available';
            //
            if (in_array($employee["sid"], $active_employees_sids)) {
                array_push($active_employees, $employee);
            } else {
                array_push($inactive_employees, $employee);
            }
        }
        //
        $return_obj = array(
            "active_employees" => $active_employees,
            "inactive_employees" => $inactive_employees
        );
        //
        return $return_obj;
    } 

    public function get_company_employees_detail_overview_log($company_sid, $start_date, $end_date, $columns = '*') {
        //
        $company_active_employees = $this->generate_company_employes_activity_log_detail($company_sid, $start_date, $end_date);
        //
        $this->db->select(is_array($columns) ? implode(',', $columns) : $columns);
        $this->db->where('parent_sid', $company_sid);
        $this->db->order_by('is_executive_admin', 'DESC');
        $this->db->order_by('access_level', 'ASC');
        $company_employees = $this->db->get('users')->result_array();
        //
        $active_employees = array();
        $inactive_employees = array();
        //
        foreach ($company_employees as $key => $employee) {
            //
            $employee["access_level"]   = $employee['is_executive_admin'] == 1 ? "Executive Admin" : ucwords($employee['access_level']);
            $employee["employee_name"]  = ucwords($employee['first_name'] . ' ' . $employee['last_name']);
            $employee["PhoneNumber"]    = $employee['PhoneNumber'] == '' ? 'Not Available' : $employee['PhoneNumber'];
            $employee["job_title"]      = $employee['job_title'] != '' ? ucwords($employee['job_title']) : 'Not Available';
            $employee["total_time"]     = 0;
            $employee["ips"]            = [];
            //
            if (array_key_exists($employee["sid"], $company_active_employees)) {
                $employee["total_time"]     = $company_active_employees[$employee["sid"]]["total_time_spent_in_minutes"];
                $employee["ips"]            = $company_active_employees[$employee["sid"]]["ips"];
                array_push($active_employees, $employee);
            } else {
                array_push($inactive_employees, $employee);
            }

        }
        //
        $return_obj = array(
            "active_employees" => $active_employees,
            "inactive_employees" => $inactive_employees
        );
        //
        return $return_obj;
    }  

    public function fix_zero_iteration ($employees) {
        //
        foreach ($employees as $e_key => $employee) {
            if (!empty($employee["ips"])) {
                foreach ($employee["ips"] as $ip_key => $ip) {
                    if ($ip["minutes"] == 0) {
                        $employees[$e_key]["ips"][$ip_key]["minutes"] += 10;
                        $employees[$e_key]['total_time_spent_in_minutes'] += 10;
                    }
                }
            }
        }
        //
        return $employees;
    } 
}

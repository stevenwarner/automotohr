<?php

class Employer_login_duration_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function get_all_companies($columns = '*') {
        $excluded_companies = $this->get_excluded_company_sids();
        $this->db->select(is_array($columns) ? implode(',', $columns) : $columns);
        $this->db->where('parent_sid', 0);
        $this->db->where('sid NOT IN ( ' . implode(',', $excluded_companies) . ' )');
        $this->db->order_by('sid', 'DESC');
        $this->db->where('career_page_type', 'standard_career_site');
        $this->db->where('active', 1);
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
            $this->db->where("action_timestamp BETWEEN '" . $date_start . "' AND '" . $date_end . "'");
            return $this->db->get('logged_in_activitiy_tracker')->result_array();
        }
    }

    public function get_active_employers($company_sid, $start_date, $end_date) {
        $this->db->distinct();
        $this->db->select('employer_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
        return $this->db->get('logged_in_activitiy_tracker')->result_array();
    }

    public function get_activity_log_company($company_sid, $date = '12/30/2016') {
        $date_parts = explode('/', $date);
        $start_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1] . ' ' . ' 00:00:00';
        $end_date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1] . ' ' . '23:59:59';
        $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);

        foreach ($active_employers as $key => $active_employer) {
            $this->db->select('*');
            $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $active_employer['employer_sid']);
            $this->db->order_by('employer_ip', 'DESC');
            $employer_logs = $this->db->get('logged_in_activitiy_tracker')->result_array();

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
            $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
            $this->db->where('company_sid', $company_sid);
            $this->db->where('employer_sid', $active_employer['employer_sid']);
            $this->db->order_by('action_timestamp', 'ASC');
            $this->db->order_by('employer_ip', 'ASC');
            $this->db->limit(10);
            $employer_logs = $this->db->get('logged_in_activitiy_tracker')->result_array();
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

    public function get_all_inactive_employees($company_sid, $start_date, $end_date) {
        $active_employers = $this->get_active_employers($company_sid, $start_date, $end_date);
        $active_employers_sids = array();
        
        foreach ($active_employers as $active_employer) {
            $active_employers_sids[] = $active_employer['employer_sid'];
        }

        $this->db->select('*');
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
            $this->db->where("action_timestamp BETWEEN '" . $start_date . "' AND '" . $end_date . "'");
            $active_employers = $this->db->get('logged_in_activitiy_tracker')->result_array();
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
        ->from('logged_in_activitiy_tracker')
        ->where('company_sid', $companyId)
        ->where('employer_sid', $employerId)
        ->where("DATE_FORMAT(action_timestamp, '%Y-%m-%d') BETWEEN '" . $startDate . "' AND '" . $endDate . "'")
        ->order_by('sid', 'ASC')
        ->get();
        //
        $result_arr = $result->result_array();
        $result     = $result->free_result();
        return $result_arr;
    }
}

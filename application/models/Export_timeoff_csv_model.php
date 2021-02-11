<?php

class Export_timeoff_csv_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }


    function get_all_employees($company_sid) {
//        $this->db->select('email');
//        $this->db->select('Location_Country');
//        $this->db->select('Location_State');
//        $this->db->select('Location_City');
//        $this->db->select('Location_Address');
//        $this->db->select('Location_ZipCode');
//        $this->db->select('PhoneNumber');
//        $this->db->select('profile_picture');
//        $this->db->select('first_name');
//        $this->db->select('last_name');
//        $this->db->select('access_level');
//        $this->db->select('job_title');
        $this->db->select('concat(first_name," ",last_name) as full_name, sid, first_name, last_name, is_executive_admin, access_level, access_level_plus, pay_plan_flag, job_title');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('active', '1');
        $this->db->order_by('concat(first_name,last_name)', 'ASC', false);
        $this->db->where('terminated_status', 0);

        
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!empty($records_arr)){
            return $records_arr;
        } else {
            return array();
        }
    }
    
    function get_csv_applicants($company_sid, $app_sid,$archive,$status,$start_date,$end_date){
        //$this->db->select('portal_applicant_jobs_list.sid');
        $this->db->distinct();
        $this->db->select('users.employee_number');
        $this->db->select('users.first_name');
        $this->db->select('users.last_name');
        $this->db->select('concat(users.first_name," ",users.last_name) as full_name');
        $this->db->select('users.job_title');
        $this->db->select('users.is_executive_admin');
        $this->db->select('users.access_level_plus');
        $this->db->select('users.pay_plan_flag');
        $this->db->select('users.access_level');
        $this->db->select('timeoff_policies.title as policy');
        $this->db->select('timeoff_category_list.category_name as type');
        $this->db->select('timeoff_requests.sid');
        $this->db->select('timeoff_requests.request_from_date as from');
        $this->db->select('timeoff_requests.request_to_date as to');
        $this->db->select('timeoff_requests.requested_time as time');
        $this->db->select('timeoff_requests.reason as reason');
        $this->db->select('timeoff_requests.status');
        $this->db->select('timeoff_requests.level_at');
        $this->db->select('timeoff_requests.is_partial_leave');
        $this->db->select('timeoff_requests.timeoff_days');
        $this->db->where('timeoff_requests.company_sid', $company_sid);
        $this->db->where('timeoff_requests.is_draft', 0);
        $this->db->where('timeoff_requests.archive', $archive);

        if (!empty($app_sid) && $app_sid != 'all') {
            $this->db->where('users.sid', $app_sid);
        }

        if (!empty($status) && !in_array('all',$status)) {
            $this->db->where_in('timeoff_requests.status', $status);
        }

        if ((!empty($start_date) || !is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('timeoff_requests.request_from_date BETWEEN \'' . $start_date . '\' AND \'' . $end_date . '\'');
        } else if ((!empty($start_date) || !is_null($start_date)) && (empty($end_date) || is_null($end_date))) {
            $this->db->where('timeoff_requests.request_from_date >=', $start_date);
        } else if ((empty($start_date) || is_null($start_date)) && (!empty($end_date) || !is_null($end_date))) {
            $this->db->where('timeoff_requests.request_from_date <=', $end_date);
        }
        
        $this->db->join('users', 'users.sid = timeoff_requests.employee_sid', 'inner');
        $this->db->join('timeoff_policies', 'timeoff_policies.sid = timeoff_requests.timeoff_policy_sid', 'inner');
        $this->db->join('timeoff_policy_categories', 'timeoff_policy_categories.timeoff_policy_sid = timeoff_requests.timeoff_policy_sid', 'inner');
        $this->db->join('timeoff_categories', 'timeoff_categories.sid = timeoff_policy_categories.timeoff_category_sid', 'inner');
        $this->db->join('timeoff_category_list', 'timeoff_category_list.sid = timeoff_categories.timeoff_category_list_sid', 'inner');
        $this->db->group_by('timeoff_requests.sid'); // check it over
        $applicants_obj = $this->db->get('timeoff_requests');
        $applicants_arr = $applicants_obj->result_array();
        $applicants_obj->free_result();
        //
        if(sizeof($applicants_arr)){
            foreach($applicants_arr as $k => $app){
                $a = 
                $this->db
                ->select('reason')
                ->where('timeoff_request_sid', $app['sid'])
                ->order_by('created_at', 'DESC')
                ->limit(1)
                ->get('timeoff_request_history')
                ->row_array();
                //
                if(!sizeof($a)) $applicants_arr[$k]['comment'] = '';
                else $applicants_arr[$k]['comment'] = $a['reason'];
            }
        }
//        echo '<pre>';
//        echo $this->db->last_query();
//        exit;
        return $applicants_arr;
    }

}

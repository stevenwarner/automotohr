<?php
class Learning_center_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function get_all_employees($company_sid) {
        $this->db->select('sid, first_name, last_name, email, access_level, access_level_plus, pay_plan_flag, is_executive_admin, job_title');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('active', 1);
        $this->db->from('users');
        $this->db->order_by('concat(first_name,last_name)', 'ASC', false);
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function getActiveDepartments($company_sid) {
        $this->db->select('sid, name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('is_deleted', 0);
        $this->db->from('departments_management');
        $this->db->order_by('name', 'asc');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_onboarding_applicants($company_sid) {
        $this->db->select('sid, first_name, last_name');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('is_onboarding', 1);
        $this->db->from('portal_job_applications');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_online_videos($company_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('sid', 'desc');
        $this->db->from('learning_center_online_videos');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_online_video($video_sid) {
        $this->db->select('*');
        $this->db->where('sid', $video_sid);
        $this->db->order_by('sid', 'desc');
        $this->db->from('learning_center_online_videos');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $video_info = $records_arr[0];
            $applicants = $this->get_online_videos_assignments_records('applicant', $video_info['sid']);
            $employees = $this->get_online_videos_assignments_records('employee', $video_info['sid']);
            $video_info['applicants'] = $applicants;
            $video_info['employees'] = $employees;
            return $video_info;
        } else {
            return array();
        }
    }

    function get_empty_video_record($employer_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', 0);
        $this->db->where('created_by_sid', $employer_sid);
        $this->db->from('learning_center_online_videos');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            $video_info = $records_arr[0];
            $applicants = $this->get_online_videos_assignments_records('applicant', $video_info['sid']);
            $employees = $this->get_online_videos_assignments_records('employee', $video_info['sid']);
            $video_info['applicants'] = $applicants;
            $video_info['employees'] = $employees;
            return $video_info;
        } else {
            return array();
        }
    }

    function insert_training_video($data) {
        $this->db->insert('learning_center_online_videos', $data);
        return $this->db->insert_id();
    }

    function update_training_video($video_sid, $data) {
        $this->db->where('sid', $video_sid);
        $this->db->update('learning_center_online_videos', $data);
    }

    function delete_training_video($video_sid) {
        $this->db->where('sid', $video_sid);
        $this->db->delete('learning_center_online_videos');
        $this->db->where('learning_center_online_videos_sid');
        $this->db->delete('learning_center_online_videos_assignments');
    }

    function insert_training_session_record($data) {
        $this->db->insert('learning_center_training_sessions', $data);
    }

    function get_video_status($video_sid) {
        $this->db->select('video_source, video_id');
        $this->db->where('sid', $video_sid);
        $this->db->from('learning_center_online_videos');
        $records_obj = $this->db->get();

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_training_session_record($session_sid, $data) {
        $this->db->where('sid', $session_sid);
        $this->db->update('learning_center_training_sessions', $data);
    }

    function get_training_session($session_sid) {
        $this->db->select('
            portal_schedule_event.sid as event_sid,
            portal_schedule_event.duration,
            portal_schedule_event.reminder_flag as duration_check,
            learning_center_training_sessions.sid,
            learning_center_training_sessions.company_sid,
            learning_center_training_sessions.session_topic,
            learning_center_training_sessions.online_video_sid,
            learning_center_training_sessions.session_description,
            learning_center_training_sessions.session_location,
            date_format(learning_center_training_sessions.session_date, "%m-%d-%Y") as session_date,
            date_format(learning_center_training_sessions.session_start_time, "%h:%i %p") as session_start_time,
            date_format(learning_center_training_sessions.session_end_time, "%h:%i %p") as session_end_time,
            learning_center_training_sessions.employees_assigned_to
        ');
        $this->db->where('learning_center_training_sessions.sid', $session_sid);
        $this->db->join('portal_schedule_event', 'portal_schedule_event.learning_center_training_sessions = learning_center_training_sessions.sid', 'inner');
        $this->db->from('learning_center_training_sessions');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!sizeof($records_arr)) return array();
        $session_info = $records_arr[0];
        $applicants = $this->get_training_session_assignments_records('applicant', $session_info['sid']);
        $employees = $this->get_training_session_assignments_records('employee', $session_info['sid']);
        $session_info['applicants'] = $applicants;
        $session_info['employees'] = $employees;
        //
        $session_info['external_employees'] = $this->get_training_session_assignments_records('non-employee', $session_info['sid']);
        return $session_info;
    }

    function get_all_training_sessions($company_sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('session_date', 'ASC');
        $this->db->order_by('session_start_time', 'ASC');
        $this->db->from('learning_center_training_sessions');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function set_training_session_status($session_sid, $status) {
        $this->db->where('sid', $session_sid);
        $this->db->set('session_status', $status);
        $this->db->update('learning_center_training_sessions');
    }

    function delete_training_session($session_sid) {
        $this->db->where('sid', $session_sid);
        $this->db->delete('learning_center_training_sessions');
        $this->db->where('training_session_sid', $session_sid);
        $this->db->delete('learning_center_training_sessions_assignments');
    }


    /**
     * Delete event by LC
     * Created on: 09-05-2019
     *
     * @param session_sid Integer
     *
     * @return VOID
     *
     */
    function delete_event_by_training_session_id($session_sid) {
        $this->db->where('learning_center_training_sessions', $session_sid);
        $this->db->delete('portal_schedule_event');
    }

    function set_online_videos_assignment_status($online_video_sid, $status = 0) {
        $this->db->where('learning_center_online_videos_sid', $online_video_sid);
        $this->db->set('status', $status);
        $this->db->update('learning_center_online_videos_assignments');
    }

    function insert_online_videos_assignments_record($data_to_insert) {
        $this->db->insert('learning_center_online_videos_assignments', $data_to_insert);
    }

    function delete_all_assign_video_user ($video_id) {
        $this->db->where('learning_center_online_videos_sid', $video_id);
        $this->db->delete('learning_center_online_videos_assignments');
    }

    function get_online_videos_assignments_records($user_type, $video_id) {
        $this->db->where('learning_center_online_videos_sid', $video_id);
        $this->db->where('user_type', $user_type);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('learning_center_online_videos_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_last_active_video_assignments($video_sid) {
        $this->db->select('*');
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('learning_center_online_videos_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function insert_training_session_assignment_record($data_to_insert) {
        $this->db->insert('learning_center_training_sessions_assignments', $data_to_insert);
    }

    function set_training_session_assignment_status($session_sid, $status = 0) {
        $this->db->where('training_session_sid', $session_sid);
        $this->db->set('status', $status);
        $this->db->update('learning_center_training_sessions_assignments');
    }

    function get_last_active_training_session_assignments($session_sid) {
        $this->db->select('*');
        $this->db->where('training_session_sid', $session_sid);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('learning_center_training_sessions_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    // Updated on: 10-06-2019
    function get_training_session_assignments_records($user_type, $training_session_sid) {
        $this->db->select('
            learning_center_training_sessions_assignments.*,
            concat(users.first_name," ", users.last_name) as full_name,
            users.email
        ');
        // Added on: 09-05-2019
        // Get non-employee interviewers
        // show email field
        // if($user_type == 'non-employee'){
        //     $this->db->select('portal_schedule_event_external_participants.show_email');
        //     $this->db->join('portal_schedule_event', 'learning_center_training_sessions_assignments.training_session_sid = portal_schedule_event.learning_center_training_sessions', 'left');
        //     $this->db->join('portal_schedule_event_external_participants', 'portal_schedule_event_external_participants.event_sid = portal_schedule_event.sid', 'left');
        //     $this->db->where('portal_schedule_event_external_participants.email = learning_center_training_sessions_assignments.email_address', NULL);
        // }
        $this->db->where('training_session_sid', $training_session_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('status', 1);
        $this->db->join('users', 'learning_center_training_sessions_assignments.user_sid = users.sid', 'left');
        $records_obj = $this->db->get('learning_center_training_sessions_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_my_online_videos($user_type, $user_sid) {
        $this->db->select('learning_center_online_videos_assignments.*');
        $this->db->select('learning_center_online_videos.sid');
        $this->db->select('learning_center_online_videos.created_date');
        $this->db->select('learning_center_online_videos.video_title');
        $this->db->select('learning_center_online_videos.video_description');
        $this->db->select('learning_center_online_videos.video_id');
        $this->db->select('learning_center_online_videos.video_source');
        $this->db->where('learning_center_online_videos_assignments.user_type', $user_type);
        $this->db->where('learning_center_online_videos_assignments.user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_assignments.status', 1);
        $this->db->order_by('learning_center_online_videos_assignments.date_assigned', 'DESC');
        $this->db->join('learning_center_online_videos', 'learning_center_online_videos.sid = learning_center_online_videos_assignments.learning_center_online_videos_sid', 'left');
        $records_obj = $this->db->get('learning_center_online_videos_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_my_all_online_videos($user_type, $user_sid, $company_sid) {
        // Get all employees
        $this->db->select('sid, created_date, video_title, video_description, video_source, video_id')
        ->where('company_sid', $company_sid)
        ->where('employees_assigned_to', 'all')
        ->order_by('created_date', 'DESC');
        //
        $a = $this->db->get('learning_center_online_videos');
        $b = $a->result_array();
        $a->free_result();
        //
        $ids = array();
        //
        if(sizeof($b)) foreach ($b as $k => $v) $ids[$v['sid']] = $v['sid'];
        //
        $r = $b;

        // Get specific employees
        $a = $this->db->select('learning_center_online_videos.sid')
        ->select('learning_center_online_videos.created_date')
        ->select('learning_center_online_videos.video_title')
        ->select('learning_center_online_videos.video_description')
        ->select('learning_center_online_videos.video_source')
        ->select('learning_center_online_videos.video_id')
        ->select('learning_center_online_videos_assignments.learning_center_online_videos_sid')
        ->where('learning_center_online_videos_assignments.user_type', $user_type)
        ->where('learning_center_online_videos_assignments.user_sid', $user_sid)
        ->where('learning_center_online_videos_assignments.status', 1)
        ->order_by('learning_center_online_videos_assignments.date_assigned', 'DESC')
        ->join('learning_center_online_videos', 'learning_center_online_videos.sid = learning_center_online_videos_assignments.learning_center_online_videos_sid', 'left')
        ->get('learning_center_online_videos_assignments');
        //
        $b = $a->result_array();
        $a->free_result();
        //
        if(sizeof($b)) foreach ($b as $k => $v) $ids[$v['sid']] = $v['sid'];
        //
        $r = array_merge($r, $b);
        //
        $ids = array_values($ids);

        // Check for departments
        $this->db
        ->select('sid, created_date, video_title, video_description, video_source, video_id, department_sids')
        ->where('company_sid', $company_sid)
        ->group_start()
        ->where('department_sids', 'all')
        ->or_where('department_sids <> ', 'all')
        ->group_end()
        ->where('department_sids IS NOT NULL', NULL)
        ->where('employees_assigned_to', 'specific')
        ->order_by('created_date', 'DESC');
        //
        if(sizeof($ids)) $this->db->where_not_in('sid', $ids);
        //
        $a = $this->db->get('learning_center_online_videos');
        $b = $a->result_array();
        $a->free_result();
        //
        if(!sizeof($b)) return $r;
        //
        $d = array();
        //
        $dept = $this->getDepartmentEmployees($company_sid, 'all', true);
        //
        foreach ($b as $k => $v) {
            if($v['department_sids'] == 'all'){
                if(!isset($dept[$v['department_sids']][$user_sid])) unset($b[$k]);
            } else {
                $t = explode(',', $v['department_sids']);
                foreach ($t as $k0 => $v0) {
                    if(!isset($dept[$v0][$user_sid])) unset($b[$k]);
                }
            }
        }
        //
        $r = array_merge($r, $b);
        //
        function r($a, $b){
            return $a['created_date'] < $b['created_date'] ? true : false;
        }
        //
        asort($r, 'r');
        return $r;
    }

    function get_single_online_video($video_sid, $company_sid = 0) {
        $this->db->select('*');
        $this->db->where('sid', $video_sid);

        if($company_sid>0) {
            $this->db->where('company_sid', $company_sid);
        }

        $video_obj = $this->db->get('learning_center_online_videos');
        $video_arr = $video_obj->result_array();
        $video_obj->free_result();

        if (!empty($video_arr)) {
            return $video_arr[0];
        } else {
            return array();
        }
    }

    function get_single_training_session($video_sid) {
        $this->db->select('*');
        $this->db->where('sid', $video_sid);
        $video_obj = $this->db->get('learning_center_training_sessions');
        $video_arr = $video_obj->result_array();
        $video_obj->free_result();

        if (!empty($video_arr)) {
            return $video_arr[0];
        } else {
            return array();
        }
    }

    function get_watched_video_date($user_type, $user_sid, $video_sid) {
        $this->db->select('date_watched');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $video_obj = $this->db->get('learning_center_online_videos_assignments');
        $video_arr = $video_obj->result_array();
        $video_obj->free_result();

        if (!empty($video_arr)) {
            return $video_arr[0];
        } else {
            return array();
        }
    }

    function update_video_completed_status($user_type, $user_sid, $video_sid, $video_duration) {

        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $this->db->set('watched', 1);
        $this->db->set('date_watched', date('Y-m-d H:i:s'));
        $this->db->set('duration', $video_duration);
        $this->db->update('learning_center_online_videos_assignments');
    }

    function update_video_watched_status($user_type, $user_sid, $video_sid) {

        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $this->db->set('watched', 1);
        $this->db->set('date_watched', date('Y-m-d H:i:s'));
        $this->db->update('learning_center_online_videos_assignments');
    }

    function update_video_watched_duration($user_type, $user_sid, $video_sid, $video_duration) {

        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $this->db->set('duration', $video_duration);
        $this->db->update('learning_center_online_videos_assignments');
    }

    function update_video_watched_completed($user_type, $user_sid, $video_sid, $video_duration) {

        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $this->db->set('duration', $video_duration);
        $this->db->set('completed', 1);
        $this->db->update('learning_center_online_videos_assignments');
    }

    function get_video_assignment($user_type, $user_sid, $video_sid, $company_sid = NULL) {
        // Check if already assigned to employee or not
        $a = $this->db->where('user_type', $user_type)
        ->where('user_sid', $user_sid)
        ->where('learning_center_online_videos_sid', $video_sid)
        ->get('learning_center_online_videos_assignments');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if(sizeof($b)) return $b;
        //
        if($company_sid == NULL) return 0;
        //
        // Get all videos with assign to yes
        $a = $this->db
        ->select('sid, department_sids, employees_assigned_to')
        ->where('company_sid', $company_sid)
        ->where('sid', $video_sid)
        ->group_start()
        ->where('employees_assigned_to', 'all')
        ->or_group_start()
        ->where('department_sids', 'all')
        ->or_where('department_sids <> ', 'all')
        ->group_end()
        ->group_end()
        ->get('learning_center_online_videos');
        //
        $b = $a->row_array();
        $a = $a->free_result(); 
        //
        if(!sizeof($b)) return 0;
        //
        $doInsert = false;
        //
        if($b['employees_assigned_to'] == 'all') $doInsert = true;
        else{
            // Fetch all departments
            $dept = $this->getDepartmentEmployees($company_sid, 'all', true);
            //
            if(!sizeof($dept)) return 0;
            //
            $t = explode(',', $b['department_sids']);
            //
            foreach ($t as $k => $v) {
                if(isset($dept[$v][$user_sid])){
                    $doInsert = true;
                    break;
                }               
            }
            // 
        }
        //
        if(!$doInsert) return 0;
        //
        $data_to_insert = array();
        $data_to_insert['learning_center_online_videos_sid'] = $video_sid;
        $data_to_insert['user_type'] = $user_type;
        $data_to_insert['user_sid'] = $user_sid;
        $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
        $data_to_insert['status'] = 0;
        //
        $this->db->insert('learning_center_online_videos_assignments', $data_to_insert);
        //
        $a = $this->db
        ->where('user_type', $user_type)
        ->select('*')
        ->where('user_sid', $user_sid)
        ->where('learning_center_online_videos_sid', $video_sid)
        ->get('learning_center_online_videos_assignments');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    function check_and_assigned_video_record($video_sid_arr, $type, $sid, $company_sid){
        if(sizeof($video_sid_arr)){
            foreach($video_sid_arr as $vsid){
                $this->db->from('learning_center_online_videos_assignments');
                $this->db->where('user_type', $type);
                $this->db->where('user_sid', $sid);
                $this->db->where('learning_center_online_videos_sid', $vsid);
                $assigned = $this->db->count_all_result();
                if(!$assigned){
                    $data_to_insert = array();
                    $data_to_insert['learning_center_online_videos_sid'] = $vsid;
                    $data_to_insert['user_type'] = $type;
                    $data_to_insert['user_sid'] = $sid;
                    $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                    $data_to_insert['status'] = 1;
                    $data_to_insert['from_training_session'] = 1;
                    $this->db->insert('learning_center_online_videos_assignments', $data_to_insert);
                }
            }
        }
    }

    function get_session_video_assignment($user_type, $user_sid, $video_sid, $company_sid = NULL, $session_id) {

        $this->db->select('online_video_sid');
        $this->db->where('sid', $session_id);
        $this->db->where('company_sid', $company_sid);
        $result = $this->db->get('learning_center_training_sessions')->result_array()[0]['online_video_sid'];
        $record_arr = explode(',',$result);

        if (in_array($video_sid, $record_arr)) {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('learning_center_online_videos_sid', $video_sid);
            $record_obj = $this->db->get('learning_center_online_videos_assignments');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                return $record_arr[0];
            } else {
                $data_to_insert = array();
                $data_to_insert['learning_center_online_videos_sid'] = $video_sid;
                $data_to_insert['user_type'] = $user_type;
                $data_to_insert['user_sid'] = $user_sid;
                $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                $data_to_insert['status'] = 0;
                $this->db->insert('learning_center_online_videos_assignments', $data_to_insert);

                $this->db->where('user_type', $user_type);
                $this->db->select('*');
                $this->db->where('user_sid', $user_sid);
                $this->db->where('learning_center_online_videos_sid', $video_sid);
                $record_obj = $this->db->get('learning_center_online_videos_assignments');
                $return_data = $record_obj->result_array();
                $record_obj->free_result();
                return $return_data[0];
            }
        } else {
                return 0;
        }
    }

//    function get_training_assignment($user_type, $user_sid, $video_sid, $company_sid = NULL) {
//        $this->db->where('user_type', $user_type);
//        $this->db->where('user_sid', $user_sid);
//        $this->db->where('training_session_sid', $video_sid);
//        $record_obj = $this->db->get('learning_center_training_sessions_assignments');
//        $record_arr = $record_obj->result_array();
//        $record_obj->free_result();
//
//        if (!empty($record_arr)) {
//            return $record_arr[0];
//        } else { // check whether the video is assigned to all members of the company
//            if($company_sid != NULL) {
//                $this->db->select('sid');
//                $this->db->where('company_sid', $company_sid);
//                $this->db->where('sid', $video_sid);
//                $this->db->where('employees_assigned_to', 'all');
//                $record_obj = $this->db->get('learning_center_training_sessions');
//                $data_exists = $record_obj->result_array();
//                $record_obj->free_result($data_exists);
//
//                if(!empty($data_exists)) {
//                    $data_to_insert = array();
//                    $data_to_insert['training_session_sid'] = $video_sid;
//                    $data_to_insert['user_type'] = $user_type;
//                    $data_to_insert['user_sid'] = $user_sid;
//                    $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
//                    $data_to_insert['status'] = 0;
//                    $this->db->insert('learning_center_training_sessions_assignments', $data_to_insert);
////                    echo 'here<pre>'; print_r($data_to_insert); exit;
//                    $this->db->where('user_type', $user_type);
//                    $this->db->select('*');
//                    $this->db->where('user_sid', $user_sid);
//                    $this->db->where('training_session_sid', $video_sid);
//                    $record_obj = $this->db->get('learning_center_training_sessions_assignments');
//                    $return_data = $record_obj->result_array();
//                    $record_obj->free_result();
//                    return $return_data;
//                } else {
//                    return 0;
//                }
//            } else {
//                return 0;
//            }
//        }
//    }

    // Check for t.session
    // if found then check employee
    // employee not found add it in session
    // return data
    function get_training_assignment($user_type, $user_sid, $video_sid, $company_sid = NULL) {
        $this->db->select('sid,company_sid,employees_assigned_to, session_topic,session_description,session_location,session_date,session_start_time,session_end_time,session_status,online_video_sid');
        $this->db->where('sid', $video_sid);
        // Added on: 10-05-2019
        if($company_sid != NULL) $this->db->where('company_sid', $company_sid);

        $record_obj = $this->db->get('learning_center_training_sessions');
        $data_exists = $record_obj->result_array();
        $record_obj->free_result();
//        echo '<pre>';
//        print_r($data_exists);
//        die();

        if(!empty($data_exists)){
            $this->db->select('attend_status, attend_status_date, status, user_sid, sid as assigned_sid');
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('training_session_sid', $video_sid);
            // Added on: 10-06-2019
            $this->db->order_by('status', 'DESC');
            $record_obj = $this->db->get('learning_center_training_sessions_assignments');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();

            if(empty($record_arr)) {
                $data_to_insert = array();
                $data_to_insert['training_session_sid'] = $video_sid;
                $data_to_insert['user_type'] = $user_type;
                $data_to_insert['user_sid'] = $user_sid;
                $data_to_insert['date_assigned'] = date('Y-m-d H:i:s');
                // Updated on: 10-05-2019
                $data_to_insert['status'] = 1;
                $this->db->insert('learning_center_training_sessions_assignments', $data_to_insert);
                $this->db->where('user_type', $user_type);
                $this->db->select('t1.sid,t1.company_sid,t1.employees_assigned_to, t1.created_date, t1.session_topic, t1.online_video_sid, t1.session_description, t1.session_location, t1.session_date, t1.session_start_time, t1.session_end_time, t1.session_status,t2.attend_status,t2.attend_status_date,t2.status, t2.sid as assigned_sid, t2.user_sid');
                $this->db->where('user_sid', $user_sid);
                $this->db->where('training_session_sid', $video_sid);
                $this->db->join('learning_center_training_sessions as t1', 't2.training_session_sid = t1.sid', 'left');
                $record_obj = $this->db->get('learning_center_training_sessions_assignments as t2');
                $return_data = $record_obj->result_array();
                $record_obj->free_result();
                return $return_data;
            } else {
                $data_exists[0]['attend_status'] = $record_arr[0]['attend_status'];
                $data_exists[0]['attend_status_date'] = $record_arr[0]['attend_status_date'];
                $data_exists[0]['status'] = $record_arr[0]['status'];
                $data_exists[0]['assigned_sid'] = $record_arr[0]['assigned_sid'];
                $data_exists[0]['user_sid'] = $record_arr[0]['user_sid'];
                return $data_exists;
            }
        }else{
            return 0;
        }
    }

    function get_assigned_training_sessions($user_type, $user_sid) {
        $this->db->select('t1.*');
        $this->db->select('t2.session_topic');
        $this->db->select('t2.session_description');
        $this->db->select('t2.session_location');
        $this->db->select('t2.session_date');
        $this->db->select('t2.session_start_time');
        $this->db->select('t2.session_end_time');
        $this->db->select('t2.session_status');
        $this->db->where('t1.user_type', $user_type);
        $this->db->where('t1.user_sid', $user_sid);
        $this->db->where('t1.status', 1);
        $this->db->where('t2.session_status', 'scheduled');
        $this->db->join('learning_center_training_sessions as t2', 't1.training_session_sid = t2.sid', 'left');
        $records_obj = $this->db->get('learning_center_training_sessions_assignments as t1');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_assigned_all_training_sessions($user_type, $user_sid, $company_sid) {
        $this->db->select('sid, created_date, session_topic, session_date, session_start_time, session_end_time, session_status');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employees_assigned_to', 'all');
        $this->db->order_by('created_date', 'DESC');
        $records_obj = $this->db->get('learning_center_training_sessions');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();


       // if(empty($records_arr)) {
        $this->db->select('t1.sid, t1.created_date, t1.session_topic, t1.session_date, t1.session_start_time, t1.session_end_time, t1.session_status');

            $this->db->where('t2.user_type', $user_type);
            $this->db->where('t2.user_sid', $user_sid);
//            $this->db->where('t2.session_status', 'scheduled');
            $this->db->where('t2.status', 1);
            $this->db->join('learning_center_training_sessions as t1', 't2.training_session_sid = t1.sid', 'left');
            $records_obj = $this->db->get('learning_center_training_sessions_assignments as t2');
            $records_arr2 = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr) && !empty($records_arr2)) {
                $return_data = array_merge($records_arr, $records_arr2);

            } else if (!empty($records_arr)) {
                $return_data = $records_arr;
            } else if (!empty($records_arr2)){
                $return_data = $records_arr2;
            }
            // Remove repeating id sessions
            $ids = array_column($return_data, 'sid');
            $ids = array_unique($ids);
            $return_data = array_filter($return_data, function ($key, $value) use ($ids) {
                return in_array($value, array_keys($ids));
            }, ARRAY_FILTER_USE_BOTH);
       // }
//        echo '<pre>';
//        print_r($return_data);
//        die();
        return $return_data;
    }

    function update_attend_status($user_type, $user_sid, $session_assignment_sid, $attend_status) {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('sid', $session_assignment_sid);
        $this->db->set('attend_status', $attend_status);
        $this->db->set('attend_status_date', date('Y-m-d H:i:s'));

        if ($attend_status == 'attended') {
            $this->db->set('attended', 1);
            $this->db->set('date_attended', date('Y-m-d H:i:s'));
        }

        $this->db->update('learning_center_training_sessions_assignments');
    }

    function get_assigned_online_videos($user_type, $user_sid) {
        $this->db->select('learning_center_online_videos_sid');
        $this->db->where('learning_center_online_videos_assignments.user_type', $user_type);
        $this->db->where('learning_center_online_videos_assignments.user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_assignments.status', 1);
        $this->db->order_by('learning_center_online_videos_assignments.date_assigned', 'DESC');
        $records_obj = $this->db->get('learning_center_online_videos_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_assigned_training_session($user_type, $user_sid) {
        $this->db->select('training_session_sid');
        $this->db->where('learning_center_training_sessions_assignments.user_type', $user_type);
        $this->db->where('learning_center_training_sessions_assignments.user_sid', $user_sid);
        $this->db->where('learning_center_training_sessions_assignments.status', 1);
        $this->db->order_by('learning_center_training_sessions_assignments.date_assigned', 'DESC');
        $records_obj = $this->db->get('learning_center_training_sessions_assignments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL) {
        $this->db->where('applicant_job_sid', $app_id);

        if ($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }

        if ($date != NULL) { // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $this->db->from('portal_applicant_rating');
        $rows = $this->db->count_all_results();

//        echo $this->db->last_query(); exit;
        if ($rows > 0) {
            $this->db->select_sum('rating');
            $this->db->where('applicant_job_sid', $app_id);
            $this->db->where('users_type', $users_type);
            $this->db->from('portal_applicant_rating');
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
            $data = round($records_arr[0]['rating'] / $rows, 2);
            return $data;
        }
    }

    function insert_schedule_event($data){
        $this->db->insert('portal_schedule_event',$data);
        return $this->db->insert_id();
    }

    function delete_schedule_event($id){
        $this->db->where('sid',$id);
//        $this->db->where('employers_sid <> applicant_job_sid');
        $this->db->delete('portal_schedule_event');
    }

    function get_specific_employee($sid){
        $this->db->select('sid, first_name, last_name, email');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function update_schedule_event($event_ids,$id,$data){

        $this->db->where('applicant_job_sid',$id);
        $this->db->where_in('sid',$event_ids);
        $this->db->update('portal_schedule_event',$data);

        $this->db->select('sid,category');
        $this->db->where_in('sid',$event_ids);
        $this->db->where('applicant_job_sid',$id);
        $result = $this->db->get('portal_schedule_event')->result_array();

        if(sizeof($result)>0){
            $updated_detail = array();
            $updated_detail['id'] = $result[0]['sid'];
            $updated_detail['category'] = $result[0]['category'];
            return $updated_detail;
        }else{
            $data['created_on'] = date('Y-m-d H:i:s');
            $data['category'] = 'training-session';
            $insert_id = $this->insert_schedule_event($data);
            $updated_detail['id'] = $insert_id;
            $updated_detail['category'] = 'training-session';
            return $updated_detail;
        }
    }

    function update_notInList_session_assignment($session_sid,$selected){
        $this->db->where('training_session_sid',$session_sid);
        $this->db->where('user_sid',$selected);
        $this->db->update('learning_center_training_sessions_assignments',array('status' => 1));
    }

    function getScreeningQuestionnaires($empId) {
        $screening_questions = array();
        $this->db->where('employer_sid', $empId);
        $this->db->where('type', 'learning_center');
        $result_data_list = $this->db->get('portal_screening_questionnaires');
        if ($result_data_list->num_rows() > 0) {
            foreach ($result_data_list->result_array() as $row) {
                $screening_questions[] = array("sid" => $row['sid'], "caption" => $row['name'], "employer_sid" => $row['employer_sid'], "passing_score" => $row['passing_score']);
            }
        }
        return $screening_questions;
    }

    function get_video_title($vid){
        $this->db->select('video_title');
        $this->db->where('sid',$vid);
        // Updated on: 08-05-2019
        $result = $this->db->get('learning_center_online_videos')->row_array();
        if(sizeof($result))
            return $result['video_title'];

        return $result;
    }

    function get_screening_questionnaire_by_id($sid) {
        $this->db->select('name, employer_sid, passing_score, auto_reply_pass, email_text_pass, auto_reply_fail, email_text_fail');
        $this->db->where('sid', $sid);
        $this->db->from('portal_screening_questionnaires');
        return $this->db->get()->result_array();
    }

    function get_screenings_count_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->from('portal_questions');
        return $this->db->get()->num_rows();
    }

    function get_screening_questions_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questionnaire_sid', $sid);
        $this->db->from('portal_questions');
        return $this->db->get()->result_array();
    }

    function get_screening_answer_count_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->from('portal_question_option');
        return $this->db->get()->num_rows();
    }

    function get_screening_answers_by_id($sid) {
        $this->db->select('*');
        $this->db->where('questions_sid', $sid);
        $this->db->from('portal_question_option');
        return $this->db->get()->result_array();
    }

    function get_possible_score_of_questions($sid, $type) {
        $result = 0;

        if($type == 'multilist') {
            $this->db->select_sum('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr)){
                $result = $records_arr[0]['score'];
            }
        } else {
            $this->db->select_max('score');
            $this->db->where('questions_sid', $sid);

            $records_obj = $this->db->get('portal_question_option');
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();


            if(!empty($records_arr)){
                $result = $records_arr[0]['score'];
            }
        }

        return $result;
    }

    function get_individual_question_details($sid) {
        $this->db->select('value, score, result_status');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_question_option');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $result = array();

        if(!empty($records_arr)){
            $result = $records_arr[0];
        }

        return $result;
    }

    function insert_questionnaire_result($data) {
        $this->db->insert('learning_center_screening_questionnaire', $data);
    }

    function update_video_attempt_status($user_type, $user_sid, $video_sid) {

        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('learning_center_online_videos_sid', $video_sid);
        $this->db->set('attempt_status', 1);
        $this->db->update('learning_center_online_videos_assignments');
    }

    function get_video_questionnaire_attempt($video_id,$assign_id){
        $this->db->select('attend_timestamp,questionnaire_result');
        $this->db->where('video_sid',$video_id);
        $this->db->where('video_assign_sid',$assign_id);
        $result = $this->db->get('learning_center_screening_questionnaire')->result_array();
        return $result;
    }

    function insert_attached_document($data_to_insert) {
        $this->db->insert('learning_center_attachment', $data_to_insert);
        return $this->db->insert_id();

    }

    function get_attached_document($video_sid) {
        $this->db->select('*');
        $this->db->where('video_sid', $video_sid);
        $this->db->where('status', 1);
        $this->db->from('learning_center_attachment');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function delete_attach_document($sid, $data_to_update) {
        $this->db->where('sid', $sid);
        $this->db->update('learning_center_attachment', $data_to_update);
    }

    function get_attach_document($sid, $company_sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->from('learning_center_attachment');
        $record_obj = $this->db->get();

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_document_tracking ($company_sid, $user_sid, $user_type, $sid) {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('document_sid', $sid);
        $this->db->from('learning_center_document_tracking');
        $record_obj = $this->db->get();

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_preview_attach_document($data_to_insert) {
        $this->db->insert('learning_center_document_tracking', $data_to_insert);
    }

    function update_download_status($company_sid, $user_sid, $user_type, $document_sid, $data_to_update) {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('document_sid', $document_sid);
        $this->db->update('learning_center_document_tracking', $data_to_update);
    }

    function update_supporting_document_state($document_sid, $company_sid, $video_sid, $data_to_update) {
        $this->db->where('sid', $document_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('video_sid', $video_sid);
        $this->db->update('learning_center_attachment', $data_to_update);
    }

    function get_supported_document($sid, $video_sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->where('video_sid', $video_sid);
        $this->db->from('learning_center_attachment');
        $record_obj = $this->db->get();

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_supporting_document($document_sid, $video_sid, $data_to_update) {
        $this->db->where('sid', $document_sid);
        $this->db->where('video_sid', $video_sid);
        $this->db->update('learning_center_attachment', $data_to_update);
    }

    function fetch_resource_category($cat_sid){
        $this->db->where('category_id', $cat_sid);
        $result = $this->db->get('document_library_sub_menu')->result_array();
        return $result;
    }


    /**
     * Get event external particpants
     * Created on: 07-05-2019
     *
     * @param $event_sid Integer
     *
     * @return Array|Bool
     */
    private function get_external_employees($event_sid){
        return false;
    }


    /**
     * Set status
     * Created on: 10-05-2019
     *
     * @param $learning_center_training_sessions Integer
     * @param $status String
     *
     * @return Array|Bool
     */
    function set_event_status_by_lc($learning_center_training_sessions, $status){
        $this->db
        ->where('learning_center_training_sessions', $learning_center_training_sessions)
        ->update('portal_schedule_event', array(
            'event_status' => $status
        ));
    }

    /**
     * Get trainig session
     * Created on: 10-05-2019
     *
     * @param $company_sid Integer
     * @param $status String
     * @param $page Integer
     * @param $limit Integer
     * @param $employeeId Integer
     *
     * @return Array|Bool
     */
    function get_training_sessions($company_sid, $page, $limit, $status = 'pending', $employeeId, $add = false) {
        //
        $curdate = reset_datetime(array(
            'datetime' => date('Y-m-d'),
            '_this' => $this,
            'from_format' => 'Y-m-d',
            'format' => 'Y-m-d'
        ));
        //
        $start_limit = $page == 1 ? 0 : (($page * $limit) - $limit);
        // Set condition symbol
        $conditionSymbol = ' >= ';
        if($status == 'expired') $conditionSymbol = ' < ';
        //
        $this->db
        ->select('
            session_topic,
            employees_assigned_to,
            date_format(session_date, "%m-%d-%Y") as session_date,
            date_format(session_start_time, "%H:%i") as session_start_time,
            date_format(session_end_time, "%H:%i") as session_end_time,
            session_status,
            sid as id,
            IF(date_format(session_date, "%Y-%m-%d") < CURDATE(), true, false) as is_expired,
            IF(session_status = "scheduled", "pending", session_status) as session_status
        ', false)
        ->from('learning_center_training_sessions')
        ->where('company_sid', $company_sid)
        ->order_by('session_date < "'.$curdate.'"', 'ASC', false)
        ->order_by('session_date >= "'.$curdate.'"', 'ASC', false)
        ->limit($limit, $start_limit);

        // if($status == 'pending') {
        //     $this->db->group_start();
        //     $this->db->where('session_status', $status);
        //     $this->db->or_where('session_status', 'scheduled');
        //     $this->db->group_end();
        // } else $this->db->where('session_status', $status);
        $result = $this->db->get();
        //
        $result_arr = $result->result_array();
        $result->free_result();
        $exclude = 0;
        //
        if(sizeof($result_arr))
            foreach ($result_arr as $k0 => $v0) {
                if($status == 'pending' && ($v0['session_status'] != 'pending' && $v0['session_status'] != 'scheduled')) {
                    $exclude++;continue;
                } else if($status == 'confirmed' && $v0['session_status'] != 'confirmed') {
                    $exclude++;continue;
                } else if($status == 'completed' && $v0['session_status'] != 'completed') {
                    $exclude++;continue;
                } else if($status == 'cancelled' && $v0['session_status'] != 'cancelled') {
                    $exclude++;continue;
                } else if($status == 'expired' && $v0['session_status'] != 'expired') {
                    $exclude++; continue;
                }
                //
                if(!$add){
                    //
                    if($v0['employees_assigned_to'] == 'specific'){
                        // Check if it is assigned to login employee
                        if($this->db
                        ->where('training_session_sid', $v0['id'])
                        ->where('user_sid', $employeeId)
                        ->where('user_type', 'employee')
                        ->count_all_results('learning_center_training_sessions_assignments') == 0) {
                            unset($result_arr[$k0]);
                            $exclude++;
                            continue;
                        }
                    }
                }
                //
                $result_arr[$k0]['session_date'] = reset_datetime(array('datetime' => $v0['session_date'], '_this' => $this, 'from_format' => 'm-d-Y'));
                $result_arr[$k0]['session_date_db'] = reset_datetime(array('datetime' => $v0['session_date'], '_this' => $this, 'from_format' => 'm-d-Y', 'format' => 'Y-m-d'));
                $result_arr[$k0]['session_start_time'] = reset_datetime(array('datetime' => $v0['session_start_time'], '_this' => $this, 'from_format' => 'H:i', 'format' => 'H:i'));
                $result_arr[$k0]['session_end_time'] = reset_datetime(array('datetime' => $v0['session_end_time'], '_this' => $this, 'from_format' => 'H:i', 'format' => 'H:i'));
            }
        //

        if($page != 1) return $result_arr;
        // Only fetch results for first
        // time
        $counted_results =  $this->db
        ->from('learning_center_training_sessions')
        ->where('company_sid', $company_sid)
        ->where('session_status', $status)
        ->count_all_results();

        $count_completed = $count_pending = $count_expired = $count_cancelled = $count_confirmed = 0;

        if($status != 'expired'){
            // Get expired count
            $count_expired =  $this->db
            ->select('id')
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->count_all_results();
        }

        if($status != 'confirmed'){
            // Get confirmed count
            $count_confirmed = $this->db
            ->select('id')
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->where('session_status', 'confirmed')
            ->count_all_results();
        }

        if($status != 'cancelled'){
            // Get cancelled count
            $count_cancelled = $this->db
            ->select('id')
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->where('session_status', 'cancelled')
            ->count_all_results();
        }

        if($status != 'pending'){
            // Get pending count
            $count_pending = $this->db
            ->select('id')
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->group_start()
            ->where('session_status', 'pending')
            ->or_where('session_status', 'scheduled')
            ->group_end()
            ->count_all_results();
        }

        if($status != 'completed'){
            // Get pending count
            $count_completed = $this->db
            ->select('id')
            ->from('learning_center_training_sessions')
            ->where('company_sid', $company_sid)
            ->where('session_status', 'completed')
            ->count_all_results();
        }
        //

        $result_arr = array(
            'TotalRecords' => $counted_results,
            'TotalExpired' => $count_expired,
            'TotalCancelled' => $count_cancelled,
            'TotalConfirmed' => $count_confirmed,
            'TotalPending' => $count_pending,
            'TotalCompleted' => $count_completed,
            'Records' => $result_arr
        );

        return $result_arr;
    }

    /**
     * Fetch all active training sessions
     * Created on: 14-05-2019
     *
     * @param $company_sid Integer
     *
     * @return Array
     */
    function get_all_training_sessions_new($company_sid) {
        $result = $this->db
        ->select('
            session_topic,
            session_date,
            session_start_time,
            session_end_time,
            session_status,
            session_description,
            session_location,
            sid
        ')
        ->where('company_sid', $company_sid)
        ->where('session_date >= CURDATE()', null)
        ->order_by('session_date', 'ASC')
        ->order_by('session_start_time', 'ASC')
        ->from('learning_center_training_sessions')
        ->get();
        $records_arr = $result->result_array();
        $result->free_result();
        return $records_arr;
    }

    /**
     * Fetch all assigned training sessions
     * with 'pending status'
     * Created on: 14-05-2019
     *
     * @param $user_type String
     * @param $user_sid Integer
     *
     * @return Array
     */
    function get_assigned_training_sessions_new($user_type, $user_sid) {
        $result = $this->db
        ->select('
            t1.attend_status,
            t1.sid,
            t1.user_type,
            t1.user_sid,
            t2.session_topic,
            t2.session_description,
            t2.session_location,
            t2.session_date,
            t2.session_start_time,
            t2.session_end_time,
            "scheduled" as session_status,
            IF( t2.session_date < CURDATE(), true, false) as is_expired
        ', false)
        ->from('learning_center_training_sessions_assignments as t1')
        ->join('learning_center_training_sessions as t2', 't1.training_session_sid = t2.sid', 'left')
        ->where('t1.user_type', $user_type)
        ->where('t1.user_sid', $user_sid)
        ->where('t1.status', 1)
        ->group_start()
        ->where('t2.session_status', 'pending')
        ->or_where('t2.session_status', 'scheduled')
        ->group_end()
        ->where('t2.session_date >= CURDATE()', null)
        ->order_by('t2.session_date < CURDATE()', null)
        ->get();
        $records_arr = $result->result_array();
        $result->free_result();
        return $records_arr;
    }

    function add_event_history($event_history_array){
        return
            $this->db
                ->insert('portal_event_history', $event_history_array );
    }

    function get_event_sid($training_sid){
        $this->db->select('sid');
        $this->db->where('learning_center_training_sessions',$training_sid);
        $event_sid = $this->db->get('portal_schedule_event')->result_array();
        if(sizeof($event_sid)){
            return $event_sid[0]['sid'];
        }else{
            return 0;
        }
    }


    function getDepartmentEmployees(
        $companySid,
        $departmentSids,
        $dept = FALSE
    ){
        $this->db
        ->select('users.sid')
        ->join('users', 'users.sid = departments_employee_2_team.employee_sid', 'inner')
        ->where('users.active', 1)
        ->where('users.parent_sid', $companySid)
        ->where('users.terminated_status', 0);
        //
        if($dept){
            $this->db
            ->select('departments_management.sid as department_sid')
            ->select('departments_management.name')
            ->where('departments_management.is_deleted', 0)
            ->where('departments_management.status', 1)
            ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner');
        }
        //
        if($departmentSids != 'all') $this->db->where_in('departments_employee_2_team.sid', $departmentSids);
        //
        $a = $this->db->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if(!sizeof($b)) return array();
        //
        $r = array();
        //
        if(!$dept) foreach ($b as $k => $v) $r[] = $v['sid'];
        else {
            foreach ($b as $k => $v) {
                $r[$v['department_sid']][$v['sid']] = true;
                $r['all'][$v['sid']] = true;
            }
        }
        //
        return $r;
    }

    function getDepartmentEmployeesList(
        $companySid,
        $departmentSids
    ){
        $this->db
        ->select('
            users.sid,
            users.first_name,
            users.last_name,
            users.email
        ')
        ->join('users', 'users.sid = departments_employee_2_team.employee_sid')
        ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid')
        ->where('departments_management.status', 1)
        ->where('departments_management.is_deleted', 0)
        ->where('users.active', 1)
        ->where('users.parent_sid', $companySid)
        ->where('users.terminated_status', 0);
        //
        if(array_search('-1', $departmentSids) === false){ $this->db->where_in('departments_employee_2_team.department_sid', $departmentSids);}
        //
        $a = $this->db->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }


    //
    function getActiveEmployees(
        $companySid,
        $employeeSids = array()
    ){
        $this->db
        ->select('sid, first_name, last_name, email')
        ->where('active', 1)
        ->where('parent_sid', $companySid)
        ->where('terminated_status', 0);
        //
        if(sizeof($employeeSids)) $this->db->where_in('sid', $employeeSids);
        //
        $a = $this->db->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    function check_video_expiry($video_sid, $expired_number, $expired_type) {
        $this->db->select('sid');
        $this->db->where('sid', $video_sid);
        $this->db->where('expired_number', $expired_number);
        $this->db->where('expired_type', $expired_type);
        $records_obj = $this->db->get('learning_center_online_videos');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

}

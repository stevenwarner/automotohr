<?php
class Announcement_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }
    
    function get_all_events($company_sid, $employee_sid = null, $limit = null, $start = null){
        $this->db->select('employee_announcement.*,users.first_name,users.last_name');
        $this->db->where("company_sid", $company_sid);
        if($employee_sid != null && $employee_sid != 0){
            $this->db->group_start();
            $this->db->where("FIND_IN_SET('$employee_sid',announcement_for) <>", 0);
            $this->db->or_where('announcement_for', 0);
            $this->db->group_end();
        }
        if($limit != null){
            $this->db->limit($limit, $start);
        }
        $this->db->order_by("sid", "desc");
        $this->db->join('users','users.sid = employee_announcement.created_by','left');
        return $this->db->get("employee_announcement")->result_array();
    }
    
    function get_all_events_count($company_sid, $employee_sid = null){
        if($employee_sid != null && $employee_sid != 0){
            $this->db->group_start();
            $this->db->where("FIND_IN_SET('$employee_sid',announcement_for) <>", 0);
            $this->db->or_where('announcement_for', 0);
            $this->db->group_end();
        }
        $this->db->where("company_sid", $company_sid);
        return $this->db->get("employee_announcement")->num_rows();
    }

    function get_all_employees($company_sid) {
        $this->db->select('sid,email,first_name,last_name,username, access_level, access_level_plus, is_executive_admin, job_title, pay_plan_flag');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->from('users');
        return $this->db->get()->result_array();
    }

    function get_employee_info($employee_sid) {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('username');
        $this->db->where('sid', $employee_sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function add_event($insert_array){
        $this->db->insert('employee_announcement', $insert_array);
        return $this->db->insert_id();
    }

    function update_event($update,$id){
        $this->db->where('sid',$id);
        $this->db->update('employee_announcement',$update);
    }

    function get_announcement_video_source($video_sid) {
        $this->db->select('section_video_source, section_video');
        $this->db->where('sid', $video_sid);
        $this->db->from('employee_announcement');
        $records_obj = $this->db->get();
        
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_event_by_id($event_id){
        $this->db->select('employee_announcement.*,users.first_name,users.last_name');
        $this->db->where('employee_announcement.sid',$event_id);
        $this->db->join('users','users.sid = employee_announcement.created_by','left');
        return $this->db->get('employee_announcement')->result_array();

    }

    public function validate_affiliate_video_status($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $result = $this->db->get('employee_announcement')->result_array();
        return $result;
    }

    public function get_all_departments_team($cid) {
        $this->db->select('departments_team_management.sid ,departments_team_management.name as team_name, departments_management.name as dept_name');
        $this->db->where('departments_team_management.company_sid', $cid);
        $this->db->where('departments_team_management.is_deleted', 0);
        $this->db->join('departments_management','departments_management.sid = departments_team_management.department_sid','left');
        $result = $this->db->get('departments_team_management')->result_array();
        return $result;
    }

    public function add_announcement_document($announcement_document){
        $this->db->insert('employee_announcement_documents', $announcement_document);
        return $this->db->insert_id();
    }

    public function fetch_related_team_members($team_sid){
        $this->db->select('employee_sid');
        $this->db->where('team_sid', $team_sid);
        $emp = $this->db->get('departments_employee_2_team')->result_array();
        return $emp;
    }

    public function fetch_related_documents($event_sid){
        $this->db->where('announcement_sid', $event_sid);
        $emp = $this->db->get('employee_announcement_documents')->result_array();
        return $emp;
    }

    public function delete_file($file_sid){
        $this->db->where('sid', $file_sid);
        $this->db->delete('employee_announcement_documents');
    }

}
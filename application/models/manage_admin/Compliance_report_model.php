<?php class Compliance_report_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    function add_compliance_type($data) {
        $this->db->insert('compliance_type', $data);
        return $this->db->insert_id();
    }

    function get_all_compliance_types() {
        $this->db->select('*');
        $this->db->where('safety_checklist', '0');
        $types = $this->db->get('compliance_type')->result_array();
        return $types;
    }

    function get_all_check_list() {
        $this->db->select('*');
        $this->db->where('safety_checklist', '1');
        $types = $this->db->get('compliance_type')->result_array();
        return $types;
    }

    function get_incident_types_company_specific($cid) {
        $this->db->select('id, incident_name, safety_checklist');
        $this->db->where('status', 1);
        $this->db->where('fillable_by', 'team');
        $records_obj = $this->db->get('compliance_type');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $key => $it) {
                $this->db->select('id');
                $this->db->where('incident_type_id', $it['id']);
                $this->db->where('company_id', $cid);
                $this->db->from('incident_type_configuration');
                $count = $this->db->count_all_results();
                $records_arr[$key]['count'] = $count;
            }
        }
        return $records_arr;
    }

    function get_compliance_type($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $type = $this->db->get('compliance_type')->result_array();
        return $type;
    }

    function get_manager_list() {
        $this->db->select('id, incident_name');
        $this->db->where('fillable_by', 'manager');
        $this->db->where('status', '1');
        $managers = $this->db->get('compliance_type')->result_array();
        return $managers;
    }

    function update_compliance_type($id, $data) {
        $this->db->where('id', $id);
        $type = $this->db->update('compliance_type', $data);
        return $type;
    }

    function update_compliance_question($id, $data) {
        $this->db->where('id', $id);
        $type = $this->db->update('compliance_questions', $data);
        return $type;
    }

    function fetch_compliance_name($id) {
        $this->db->select('compliance_name, safety_checklist');
        $this->db->where('id', $id);
        $name = $this->db->get('compliance_type')->result_array();
        return $name;
    }

    function fetch_questions($id) {
        $this->db->select('compliance_questions.*', 'compliance_type.compliance_name');
        $this->db->where('compliance_type_id', $id);
        $this->db->join('compliance_type', 'compliance_questions.compliance_type_id = compliance_type.id', 'left');
        $questions = $this->db->get('compliance_questions')->result_array();
        return $questions;
    }

    function add_new_question($data) {
        $this->db->insert('compliance_questions', $data);
        return $this->db->insert_id();
    }

    function get_question($id) {
        $this->db->where('id', $id);
        $result = $this->db->get('compliance_questions')->result_array();
        return $result;
    }

    function get_all_incidents() {
        $this->db->select('incident_reporting.*');
        $this->db->select('table1.first_name');
        $this->db->select('table1.last_name');
        $this->db->select('table2.CompanyName');
        $where = "incident_reporting.report_type <> ''";
        $this->db->where($where);
        $this->db->order_by('id', 'DESC');
        $this->db->join('users as table1', 'table1.sid = incident_reporting.employer_sid', 'left');
        $this->db->join('users as table2', 'table2.sid = incident_reporting.company_sid', 'left');
        $incidents = $this->db->get('incident_reporting')->result_array();
        return $incidents;
    }

    function get_specific_incident($id) {
        $this->db->select('que_ans.*,incident_reporting.company_sid');
        $this->db->select('incident_reporting.report_type');
        $this->db->where('incident_reporting.id', $id);
        $this->db->join('incident_reporting_question_answer as que_ans', 'que_ans.incident_reporting_id = incident_reporting.id', 'left');
        $incident = $this->db->get('incident_reporting')->result_array();
        return $incident;
    }

    function incident_related_documents($id) {
        $this->db->where('incident_reporting_id', $id);
        $docs = $this->db->get('incident_reporting_documents')->result_array();
        return $docs;
    }

    function get_all_admins($cid) {
        $this->db->select('first_name,last_name,sid');
        $this->db->where('parent_sid', $cid);
        $this->db->where('access_level', 'Admin');
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        return $this->db->get('users')->result_array();
    }

    function update_assign_to($id, $admin, $flag) {
        $this->db->where('id', $id);
        $this->db->update('incident_reporting', array('status' => 'Assigned'));
        if ($flag == 'in') {
            $data = array(
                'incident_reporting_id' => $id,
                'assigned_to' => $admin
            );
            $this->db->insert('incident_assigned', $data);
        } elseif ($flag == 'up') {
            $this->db->where('incident_reporting_id', $id);
            $this->db->where('assigned_to', $admin);
            $this->db->delete('incident_assigned');
        }
    }

    function get_assigned_admins($id) {
        $this->db->select('assigned_to');
        $this->db->where('incident_reporting_id', $id);
        $admins = $this->db->get('incident_assigned')->result_array();
        return $admins;
    }

    function get_company_employees($cid) {
        $this->db->select('email,access_level,profile_picture,sid,first_name,last_name,registration_date,is_executive_admin');
        $this->db->where('parent_sid', $cid);
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        return $this->db->get('users')->result_array();
    }

    function insert_incident_configuration($data) {
        $this->db->insert('incident_type_configuration', $data);
        return $this->db->insert_id();
    }

    function get_configured_employees($cid, $inc_id) {
        $this->db->select('employer_id');
        $this->db->where('company_id', $cid);
        $this->db->where('incident_type_id', $inc_id);
        return $this->db->get('incident_type_configuration')->result_array();
    }

    function delete_incident_configuration($emp, $inc_id) {
        $this->db->where('employer_id', $emp);
        $this->db->where('incident_type_id', $inc_id);
        $this->db->delete('incident_type_configuration');
    }

    function get_company_incident_name($cid, $inc_id) {
        $this->db->select('CompanyName');
        $this->db->where('sid', $cid);
        $company = $this->db->get('users')->result_array()[0]['CompanyName'];
        $this->db->select('incident_name');
        $this->db->where('id', $inc_id);
        $inc_name = $this->db->get('compliance_type')->result_array()[0]['incident_name'];
        $result_array['company'] = $company;
        $result_array['inc'] = $inc_name;
        return $result_array;
    }

    function get_incident_comments($id) {
        $this->db->select('comment,date_time,t1.username as user1,t2.username as user2,response_type, t1.profile_picture as pp1, t2.profile_picture as pp2');
        $this->db->where('incident_reporting_id', $id);
        $this->db->join('users as t1', 't1.sid = incident_reporting_comments.applicant_sid', 'left');
        $this->db->join('users as t2', 't2.sid = incident_reporting_comments.employer_sid', 'left');
        $this->db->order_by("incident_reporting_comments.id", "asc");
        $comments = $this->db->get('incident_reporting_comments')->result_array();
        return $comments;
    }

    function get_com_and_emp_name($id) {
        $this->db->select('t1.first_name as fname, t1.last_name as lname, t2.CompanyName');
        $this->db->where('incident_reporting.id', $id);
        $this->db->join('users as t1', 't1.sid = incident_reporting.employer_sid', 'left');
        $this->db->join('users as t2', 't2.sid = incident_reporting.company_sid', 'left');
        $result = $this->db->get('incident_reporting')->result_array();
        return $result;
    }

    function get_all_radio_questions($incident_type_id = 0){
        $this->db->select('label,id');
        $this->db->where('compliance_type_id',$incident_type_id);
        $this->db->where('question_type','radio');
        $result = $this->db->get('compliance_questions')->result_array();

        return $result;
    }


    //
    function add_compliance_incident_type($data) {
        $this->db->insert('compliance_incident_type', $data);
        return $this->db->insert_id();
    }

    
    //
    function update_compliance_incident_type($id, $data) {
        $this->db->where('id', $id);
        $type = $this->db->update('compliance_incident_type', $data);
        return $type;
    }


    //
    function get_all_compliance_incidents() {
        $this->db->select('*');
        $types = $this->db->get('compliance_incident_type')->result_array();
        return $types;
    }

    //
    function get_compliance_incident_type($id) {
        $this->db->select('*');
        $this->db->where('id', $id);
        $type = $this->db->get('compliance_incident_type')->result_array();
        return $type;
    }
    

    //
    function update_incident_type($id, $data) {
        $this->db->where('id', $id);
        $type = $this->db->update('compliance_incident_type', $data);
        return $type;
    }
}
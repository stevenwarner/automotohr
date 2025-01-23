<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Compliance_report_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function fetch_reports_user_guide($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$guide = $this->db->get('incident_type')->result_array();
		return $guide;
	}

    function fetch_all_question($id)
	{
		$this->db->where('incident_type_id', $id);
		$this->db->where('status', 1);
		$questions = $this->db->get('incident_questions')->result_array();
		return $questions;
	}

    function getAllCompanyEmployeesForComplianceSafety($company_sid)
	{
		$this->db->select('sid,first_name, last_name, email, PhoneNumber');
		$this->db->where('parent_sid', $company_sid);
		$this->db->where('active', 1);
		$this->db->where('terminated_status', 0);
		$this->db->order_by("is_executive_admin", SORT_ORDER);
		$result = $this->db->get('users')->result_array();
		return $result;
	}

    function get_all_employees($company_sid)
	{
		$this->db->select('sid');
		$this->db->select('first_name');
		$this->db->select('last_name');
		$this->db->order_by("concat(first_name,' ',last_name)", "ASC", false);
		$this->db->select('email');
		$this->db->select('access_level');
		$this->db->select('access_level_plus');
		$this->db->select('is_executive_admin');
		$this->db->select('pay_plan_flag');
		$this->db->select('job_title');

		$this->db->where('parent_sid', $company_sid);
		$this->db->where('username !=', '');
		$this->db->where('terminated_status', 0);
		$this->db->where('active', 1);

		$this->db->order_by('access_level', 'ASC');

		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		return $records_arr;
	}

    function update_incident_report($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('incident_reporting', $data);
	}

    function update_incident_related_video($incident_sid, $video_to_update)
	{
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('is_incident_reported', 0);
		$this->db->update('incident_related_videos', $video_to_update);
	}

    function insert_incident_reporting($insert)
	{
		$this->db->insert('incident_reporting', $insert);
		return $this->db->insert_id();
	}

    function get_specific_question($id)
	{
		$this->db->where('id', $id);
		$this->db->select('label');
		$result = $this->db->get('incident_questions')->result_array();
		$label = "";

		if (sizeof($result) > 0) {
			$label = $result[0]['label'];
		}

		return $label;
	}

    function insert_inc_que_ans($insert)
	{
		$this->db->insert('incident_reporting_question_answer', $insert);
		return $this->db->insert_id();
	}

    function assign_incident_to_emp($data)
	{
		$this->db->insert('incident_assigned_emp', $data);
		return $this->db->insert_id();
	}

    function fetch_employee_name_by_sid($sid, $model_flag = 0)
	{
		$this->db->select('first_name, last_name, email');
		$this->db->where('sid', $sid);
		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		if ($model_flag) {
			if (!empty($records_arr)) {
				return $records_arr[0]['first_name'] . ' ' . $records_arr[0]['last_name'];
			}
		} else {
			return $records_arr;
		}
	}

	function insert_incident_video_reccord($data_to_insert)
	{
		$this->db->insert('incident_related_videos', $data_to_insert);
		return $this->db->insert_id();
	}

	function insert_incident_docs($data)
	{
		$this->db->insert('incident_reporting_documents', $data);
		return $this->db->insert_id();
	}
		
}
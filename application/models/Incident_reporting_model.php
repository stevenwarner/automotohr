<?php

class Incident_reporting_model extends CI_Model
{

	function __construct()
	{
		parent::__construct();
	}

	function fetch_all_types()
	{
		$this->db->where('status', 1);
		$types = $this->db->get('incident_type')->result_array();
		return $types;
	}

	function fetch_all_question($id)
	{
		$this->db->where('incident_type_id', $id);
		$this->db->where('status', 1);
		$questions = $this->db->get('incident_questions')->result_array();
		return $questions;
	}

	function insert_incident_reporting($insert)
	{
		$this->db->insert('incident_reporting', $insert);
		return $this->db->insert_id();
	}

	function insert_incident_report_log ($insert) {
		$this->db->insert('incident_report_log', $insert);
	}

	function insert_safety_checklist($insert)
	{
		$this->db->insert('incident_reporting_checklist', $insert);
		return $this->db->insert_id();
	}

	function insert_manager_report($insert)
	{
		$this->db->insert('incident_manager_response', $insert);
		return $this->db->insert_id();
	}

	function insert_inc_que_ans($insert)
	{
		$this->db->insert('incident_reporting_question_answer', $insert);
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

	function fetch_reports_user_guide($id)
	{
		$this->db->select('*');
		$this->db->where('id', $id);
		$guide = $this->db->get('incident_type')->result_array();
		return $guide;
	}

	function insert_incident_docs($data)
	{
		$this->db->insert('incident_reporting_documents', $data);
		return $this->db->insert_id();
	}

	function is_incident_respond($incident_sid)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_sid);
		$this->db->where('applicant_sid', NULL);
		$records_obj = $this->db->get('incident_reporting_comments');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
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

	function insert_new_manager_to_incident($data_to_insert)
	{
		$this->db->insert('incident_assigned_emp', $data_to_insert);
	}

	function insert_incident_manager_history($history)
	{
		$this->db->insert('incident_assigned_manager_history', $history);
	}

	function update_incident_manager($incident_sid, $company_sid, $employer_sid, $data_to_update)
	{
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('company_sid', $company_sid);
		$this->db->where('employer_sid', $employer_sid);
		$this->db->update('incident_assigned_emp', $data_to_update);
	}

	function change_incident_type($incident_sid, $data_to_update)
	{
		$this->db->where('id', $incident_sid);
		$this->db->update('incident_reporting', $data_to_update);
	}

	function get_assigned_date($incident_sid, $company_sid, $employer_sid)
	{
		$this->db->select('assigned_date');
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('company_sid', $company_sid);
		$this->db->where('employer_sid', $employer_sid);
		$records_obj = $this->db->get('incident_assigned_emp');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['assigned_date'];
		}

		return $return_data;
	}

	function view_incidents($company_sid, $employer_sid)
	{
		$this->db->select('incident_reporting.*,incident_type.incident_name, COUNT(CASE WHEN incident_reporting_comments.read_flag = 0 && incident_reporting_comments.response_type = "Response" && incident_reporting_comments.applicant_sid IS NOT NULL THEN 1 END) as pending');
		$this->db->where('incident_reporting.company_sid', $company_sid);
		$this->db->group_start();
		$this->db->where('incident_reporting.employer_sid', $employer_sid);
		$this->db->or_where('incident_reporting.on_behalf_employee_sid', $employer_sid);
		$this->db->group_end();

		$this->db->where('incident_type.status', 1);
		// $this->db->where('incident_reporting.report_type', 'confidential');
		$where = "incident_reporting.report_type <> ''";
		$this->db->where($where);
		$this->db->join('incident_type', 'incident_reporting.incident_type_id = incident_type.id', 'left');
		$this->db->join('incident_reporting_comments', 'incident_reporting_comments.incident_reporting_id = incident_reporting.id', 'left');
		$this->db->order_by('incident_reporting.id', 'desc');
		$this->db->group_by('incident_reporting.id');
		$incidents = $this->db->get('incident_reporting')->result_array();
		return $incidents;
	}

	function view_specific_incident($id)
	{
		$this->db->select('que_ans.*');
		$this->db->select('incident_reporting.report_type,incident_reporting.status');
		$this->db->where('incident_reporting.id', $id);
		$this->db->join('incident_reporting_question_answer as que_ans', 'que_ans.incident_reporting_id = incident_reporting.id', 'left');
		$incident = $this->db->get('incident_reporting')->result_array();
		return $incident;
	}

	function incident_related_documents($id)
	{
		$this->db->where('incident_reporting_id', $id);
		$docs = $this->db->get('incident_reporting_documents')->result_array();
		return $docs;
	}

	function fetch_general_guide($company_sid, $inc_id = NULL)
	{
		$this->db->select('incident_type.instructions,incident_type.reasons,incident_type.incident_name');
		$this->db->where('incident_type.status', 1);

		if ($inc_id != NULL) {
			$this->db->where('incident_type.id', $inc_id);
		}

		$this->db->where('company_id', $company_sid);
		$this->db->group_by('incident_type_configuration.incident_type_id');
		$this->db->join('incident_type', 'incident_type.id = incident_type_configuration.incident_type_id', 'left');
		$guide = $this->db->get('incident_type_configuration')->result_array();
		return $guide;
	}

	// function assigned($id, $cid) {
	//     $this->db->select('incident_reporting.report_type,incident_reporting.status,incident_reporting.current_date,incident_reporting.id,incident_type.incident_name');
	//     $this->db->where('incident_type_configuration.employer_id', $id);
	//     $this->db->where('incident_reporting.employer_sid <> ', $id);
	//     $this->db->where('incident_reporting.company_sid', $cid);
	//     $this->db->where('incident_reporting.report_type <> ', '');
	//     $this->db->join('incident_type_configuration', 'incident_type_configuration.incident_type_id = incident_reporting.incident_type_id', 'left');
	//     $this->db->join('incident_type', 'incident_type.id = incident_reporting.incident_type_id', 'left');
	//     $this->db->order_by('incident_reporting.id', 'desc');
	//     $assign = $this->db->get('incident_reporting')->result_array();
	//     return $assign;
	// }

	function assigned_incidents_new_flow($id, $cid)
	{
		$this->db->select('incident_reporting.report_type,incident_reporting.status,incident_reporting.current_date,incident_reporting.id,incident_type.incident_name,incident_assigned_emp.incident_status, incident_reporting.incident_type_id,');
		$this->db->where('incident_assigned_emp.employer_sid', $id);
		$this->db->where('incident_assigned_emp.company_sid', $cid);
		$this->db->where('incident_assigned_emp.assigned_status', 1);
		$this->db->join('incident_assigned_emp', 'incident_assigned_emp.incident_sid = incident_reporting.id', 'left');
		$this->db->join('incident_type', 'incident_type.id = incident_reporting.incident_type_id', 'left');
		$this->db->order_by('incident_reporting.id', 'desc');
		$assign = $this->db->get('incident_reporting')->result_array();

		return $assign;
	}

	function view_single_assign($id)
	{
		$this->db->select('que_ans.*');
		$this->db->select('incident_reporting.report_type, incident_reporting.status, incident_reporting.employer_sid as reporter_id');
		$this->db->select('users.first_name,users.last_name,users.PhoneNumber,users.job_title,users.email');
		//        $this->db->select('incident_reporting_documents.file_name,incident_reporting_documents.file_code');
		$this->db->where('incident_reporting.id', $id);
		$this->db->join('incident_reporting_question_answer as que_ans', 'que_ans.incident_reporting_id = incident_reporting.id', 'left');
		$this->db->join('users', 'users.sid = incident_reporting.employer_sid', 'left');
		//        $this->db->join('incident_reporting_documents','incident_reporting_documents.incident_reporting_id = incident_reporting.id', 'left');

		$incident = $this->db->get('incident_reporting')->result_array();
		return $incident;
	}

	function get_incident_report_docs($id, $type = 'all')
	{
		$this->db->where('incident_reporting_id', $id);
		$this->db->where('file_type', 'Incident file');
		if ($type != 'all') {
			$this->db->where('is_archived', $type);
		}
		$this->db->order_by('uploaded_date', 'desc');
		$result = $this->db->get('incident_reporting_documents')->result_array();
		return $result;
	}

	function get_library_documents($incident_sid)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_sid);
		$this->db->order_by('uploaded_date', 'desc');
		$records_obj = $this->db->get('incident_reporting_documents');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_library_media($incident_sid)
	{
		$this->db->select('*');
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('is_incident_reported', 1);
		$this->db->order_by('uploaded_date', 'desc');
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_user_library_documents($incident_sid, $user_sid, $user_type)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_sid);

		if (filter_var($user_sid, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('manual_email', $user_sid);
		} else {
			$this->db->where('employer_id', $user_sid);
		}

		$this->db->where('user_type', $user_type);
		$this->db->where('file_type', 'attach_file');
		$this->db->order_by('uploaded_date', 'desc');
		$records_obj = $this->db->get('incident_reporting_documents');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_user_library_media($incident_sid, $user_sid, $user_type)
	{
		$this->db->select('*');
		$this->db->where('incident_sid', $incident_sid);

		if (filter_var($user_sid, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('manual_email', $user_sid);
		} else {
			$this->db->where('uploaded_by', $user_sid);
		}

		$this->db->where('user_type', $user_type);
		$this->db->where('file_type', 'attach_file');
		$this->db->where('is_incident_reported', 1);
		$this->db->order_by('uploaded_date', 'desc');
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_incident_report_docs_to_download($id, $report_type)
	{
		$this->db->where('incident_reporting_id', $id);
		if ($report_type == '1') {
			$this->db->where('is_archived', 0);
		}
		$result = $this->db->get('incident_reporting_documents')->result_array();
		return $result;
	}

	function add_incident_comment($data)
	{
		$this->db->insert('incident_reporting_comments', $data);
		return $this->db->insert_id();
	}

	function get_reporter_incident_comments($id)
	{
		$this->db->select('comment,date_time,t1.username as user1,t2.username as user2,response_type, t1.profile_picture as pp1, t2.profile_picture as pp2');
		$this->db->where('incident_reporting_id', $id);
		$this->db->where('response_type <>', 'Personal');
		$this->db->join('users as t1', 't1.sid = incident_reporting_comments.applicant_sid', 'left');
		$this->db->join('users as t2', 't2.sid = incident_reporting_comments.employer_sid', 'left');
		$this->db->order_by("incident_reporting_comments.id", "asc");
		$comments = $this->db->get('incident_reporting_comments')->result_array();
		return $comments;
	}

	function get_reporter_incident_emails($id)
	{
		$this->db->select('sender_sid,receiver_sid,subject,message_body,send_date,t1.username as user1,t2.username as user2, t1.profile_picture as pp1, t2.profile_picture as pp2');
		$this->db->where('incident_reporting_id', $id);
		$this->db->join('users as t1', 't1.sid = incident_reporting_emails.receiver_sid', 'left');
		$this->db->join('users as t2', 't2.sid = incident_reporting_emails.sender_sid', 'left');
		$this->db->order_by("incident_reporting_emails.sid", "asc");
		$emails = $this->db->get('incident_reporting_emails')->result_array();
		return $emails;
	}

	function get_incident_related_emails($user_sid, $employee_sid, $incident_reporting_id)
	{
		$where = "(sender_sid='" . $user_sid . "' AND receiver_sid='" . $employee_sid . "' OR sender_sid='" . $employee_sid . "' AND receiver_sid='" . $user_sid . "')";
		$this->db->select('*');
		$this->db->where($where);
		$this->db->where('incident_reporting_id', $incident_reporting_id);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('incident_reporting_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_incident_related_single_email($sid, $incident_sid)
	{
		$this->db->select('*');
		$this->db->where('sid', $sid);
		$this->db->where('incident_reporting_id', $incident_sid);
		$records_obj = $this->db->get('incident_reporting_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function get_incident_emails_by_address($email, $employee_sid, $incident_reporting_id)
	{
		$where = "(sender_sid='0' AND receiver_sid='" . $employee_sid . "' AND manual_email='" . $email . "' OR sender_sid='" . $employee_sid . "' AND receiver_sid='0' AND manual_email='" . $email . "')";
		$this->db->select('*');
		$this->db->where($where);
		$this->db->where('incident_reporting_id', $incident_reporting_id);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('incident_reporting_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_company_sid_by_incident_id($sid)
	{
		$this->db->select('company_sid');
		$this->db->where('id', $sid);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = 'N/A';

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['company_sid'];
		}

		return $return_data;
	}

	function get_reporter_incident_response($id)
	{
		$this->db->select('incident_manager_response.*,users.username,users.profile_picture');
		$this->db->where('incident_manager_response.incident_reporting_sid', $id);
		$this->db->where('incident_manager_response.show_to_reported', 1);
		$this->db->join('users', 'incident_manager_response.manager_sid = users.sid', 'left');
		$this->db->order_by('incident_manager_response.sid', 'desc');
		$incidents = $this->db->get('incident_manager_response')->result_array();
		return $incidents;
	}

	function get_incident_comments($id)
	{
		$this->db->select('comment,date_time,t1.username as user1,t2.username as user2,response_type, t1.profile_picture as pp1, t2.profile_picture as pp2, incident_reporting_comments.applicant_sid as emp_id');
		$this->db->where('incident_reporting_id', $id);
		$this->db->join('users as t1', 't1.sid = incident_reporting_comments.applicant_sid', 'left');
		$this->db->join('users as t2', 't2.sid = incident_reporting_comments.employer_sid', 'left');
		// $this->db->order_by("incident_reporting_comments.id", "asc");
		$this->db->order_by("incident_reporting_comments.date_time", "desc");
		$comments = $this->db->get('incident_reporting_comments')->result_array();
		return $comments;
	}

	function get_incident_types_company_specific($cid)
	{
		$this->db->select('*');
		$this->db->where('status', 1);
		$this->db->where('safety_checklist', 0);
		$this->db->where('fillable_by', 'team');
		$records_obj = $this->db->get('incident_type');
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

	function get_incident_types_company_specific_safety_sheets($cid)
	{
		$this->db->select('*');
		$this->db->where('status', 1);
		$this->db->where('safety_checklist', 1);
		$records_obj = $this->db->get('incident_type');
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

	function get_incident_reported_by_company_specific($cid, $eid)
	{
		$this->db->select('incident_reporting.id,incident_reporting.current_date,incident_reporting.incident_name,incident_reporting.report_type,users.first_name,users.last_name');
		$this->db->where('incident_reporting.company_sid', $cid);
		$this->db->where('incident_reporting.employer_sid !=', $eid);
		$this->db->join('users', 'users.sid = incident_reporting.employer_sid', 'left');
		$result = $this->db->get('incident_reporting')->result_array();
		return $result;
	}

	function get_reported_incident($incident_id)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_id);
		$result = $this->db->get('incident_reporting_question_answer')->result_array();
		return $result;
	}

	function get_incident_reported_by($id)
	{
		$this->db->select('incident_reporting.id,incident_reporting.current_date,incident_reporting.incident_name,incident_reporting.incident_type_id,incident_reporting.report_type,incident_reporting.employer_sid,users.first_name,users.last_name');
		$this->db->where('incident_reporting.id', $id);
		$this->db->join('users', 'users.sid = incident_reporting.employer_sid', 'left');
		$emp = $this->db->get('incident_reporting')->result_array();
		return $emp;
	}

	//    function get_configured_employees($cid, $inc_id) {
	//        $this->db->select('incident_type_configuration.employer_id,users.email,users.first_name,users.last_name');
	//        $this->db->where('incident_type_configuration.company_id', $cid);
	//        $this->db->where('incident_type_configuration.incident_type_id', $inc_id);
	//        $this->db->join('users', 'users.sid = incident_type_configuration.employer_id', 'left');
	//        $emp = $this->db->get('incident_type_configuration')->result_array();
	//        return $emp;
	//    }

	function update_assign_to($id, $admin, $flag)
	{
		//        $this->db->where('id',$id);
		//        $this->db->update('incident_reporting',array('status'=>'Assigned'));
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

	function check_assign_access($id, $com, $emp)
	{
		$this->db->select('incident_reporting.id');
		$this->db->where('incident_reporting.id', $id);
		$this->db->where('incident_type_configuration.employer_id', $emp);
		$this->db->where('incident_type_configuration.company_id', $com);
		$this->db->where('incident_reporting.company_sid', $com);
		$this->db->where('incident_reporting.employer_sid <>', $emp);
		$this->db->join('incident_type_configuration', 'incident_type_configuration.incident_type_id = incident_reporting.incident_type_id', 'left');
		$result = $this->db->get('incident_reporting')->result_array();
		return $result;
	}

	function check_view_access($id)
	{
		$this->db->select('incident_reporting.employer_sid,incident_reporting.on_behalf_employee_sid');
		$this->db->where('incident_reporting.id', $id);
		$result = $this->db->get('incident_reporting')->result_array();
		return $result;
	}

	function get_submitted_safety_checklist()
	{
		// $this->db->select('sid, submitted_checklist_name, submitted_time, type');
		$this->db->select('incident_reporting_checklist.sid,incident_reporting_checklist.type,incident_reporting_checklist.submitted_time,incident_reporting_checklist.submitted_checklist_name,users.first_name,users.last_name');
		$this->db->join('users', 'users.sid = incident_reporting_checklist.submitted_by', 'left');
		$checklists = $this->db->get('incident_reporting_checklist')->result_array();
		return $checklists;
	}

	function view_submitted_safety_checklist($sid)
	{
		$this->db->select('*');
		$this->db->where('sid', $sid);
		$checklist = $this->db->get('incident_reporting_checklist')->result_array();
		return $checklist;
	}

	function submitted_checklist_user($sid)
	{
		$this->db->select('username');
		$this->db->where('sid', $sid);
		$userName = $this->db->get('users')->result_array();
		return $userName;
	}

	function fetch_incident_managers($incident_id, $company_sid)
	{
		$this->db->select('employer_id');
		$this->db->where('incident_type_id', $incident_id);
		$this->db->where('company_id', $company_sid);
		$this->db->from('incident_type_configuration');
		$records_obj = $this->db->get();
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_array = array();

		if (!empty($records_arr)) {
			foreach ($records_arr as $employee) {
				$employee_id = $employee['employer_id'];
				$employee_name = $this->fetch_employee_name_by_sid($employee_id, 1);
				if (!empty($employee_name)) {
					$return_array[] = array(
						'employee_id' => $employee_id,
						'employee_name' => $employee_name
					);
				}
			}
		}

		return $return_array;
	}

	function fetch_incident_assigned_managers($incident_id, $company_sid)
	{
		$this->db->select('employer_sid');
		$this->db->where('incident_sid', $incident_id);
		$this->db->where('company_sid', $company_sid);
		$this->db->where('assigned_status', 1);
		$this->db->from('incident_assigned_emp');
		$records_obj = $this->db->get();
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_array = array();

		if (!empty($records_arr)) {
			foreach ($records_arr as $employee) {
				$employee_id = $employee['employer_sid'];
				$employee_name = $this->fetch_employee_name_by_sid($employee_id, 1);
				if (!empty($employee_name)) {
					$return_array[] = array(
						'employee_id' => $employee_id,
						'employee_name' => $employee_name
					);
				}
			}
		}

		return $return_array;
	}

	function check_receiver_status($incident_sid, $company_sid, $employee_sid)
	{
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('company_sid', $company_sid);
		$this->db->where('employer_sid', $employee_sid);
		return $this->db->get('incident_assigned_emp')->num_rows();
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

	function assign_incident_to_emp($data)
	{
		$this->db->insert('incident_assigned_emp', $data);
		return $this->db->insert_id();
	}

	function update_comment_read_status($id)
	{
		$data = array('read_flag' => 1);
		$this->db->where('incident_reporting_id', $id);
		$this->db->update('incident_reporting_comments', $data);
		return $this->db->insert_id();
	}

	function get_incident_type_id($id, $company_sid)
	{
		$this->db->select('incident_type_id');
		$this->db->where('id', $id);
		$this->db->where('company_sid', $company_sid);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = 0;

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['incident_type_id'];
		}

		return $return_data;
	}

	function get_incident_name_by_id($id, $company_sid)
	{
		$this->db->select('incident_name');
		$this->db->where('id', $id);
		$this->db->where('company_sid', $company_sid);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['incident_name'];
		}

		return $return_data;
	}

	function get_reported_incident_type($id, $company_sid)
	{
		$this->db->select('report_type');
		$this->db->where('id', $id);
		$this->db->where('company_sid', $company_sid);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['report_type'];
		}

		return $return_data;
	}

	function insert_incident_email_record($data_to_insert)
	{
		$this->db->insert('incident_reporting_emails', $data_to_insert);
		return $this->db->insert_id();
	}

	function insert_email_attachment($data_to_insert)
	{
		$this->db->insert('incident_email_attachments', $data_to_insert);
	}

	function fetch_incident_reporter($id)
	{
		$this->db->select('employer_sid');
		$this->db->where('id', $id);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['employer_sid'];
		}

		return $return_data;
	}

	function add_new_witness($data_to_insert)
	{
		$this->db->insert('incident_related_witnesses', $data_to_insert);
	}

	function get_reporter_incident_witnesses($incident_reporting_id, $incident_type_id)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_reporting_id);
		$this->db->where('incident_type_id', $incident_type_id);
		$this->db->order_by('reported_date', 'desc');
		$records_obj = $this->db->get('incident_related_witnesses');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_all_witnesses($incident_sid, $company_sid)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_sid);
		$this->db->where('company_sid', $company_sid);
		$records_obj = $this->db->get('incident_related_witnesses');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_incident_related_witnesses($incident_reporting_id)
	{
		$this->db->select('*');
		$this->db->where('incident_reporting_id', $incident_reporting_id);
		$records_obj = $this->db->get('incident_related_witnesses');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_incident_related_videos($incident_reporting_id, $report_type)
	{
		$where = "(video_type ='youtube'  OR video_type ='vimeo')";
		$this->db->select('*');
		$this->db->where('incident_sid', $incident_reporting_id);
		$this->db->where('is_incident_reported', 1);
		$this->db->where($where);
		if ($report_type == '1') {
			$this->db->where('is_archived', 0);
		}
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_witness_info_by_id($witness_id, $incident_reporting_id)
	{
		$this->db->select('*');
		$this->db->where('sid', $witness_id);
		$this->db->where('incident_reporting_id', $incident_reporting_id);
		$records_obj = $this->db->get('incident_related_witnesses');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function get_employee_info_by_id($user_sid)
	{
		$this->db->select('first_name, last_name, email, profile_picture, PhoneNumber, parent_sid');

		$this->db->where('sid', $user_sid);
		// $this->db->where('parent_sid', $company_sid);
		$this->db->where('active', 1);
		$this->db->where('terminated_status', 0);
		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function fetch_all_company_employees($company_sid)
	{
		$this->db->select('sid,first_name, last_name, email, PhoneNumber');
		$this->db->where('parent_sid', $company_sid);
		$this->db->where('active', 1);
		$this->db->where('terminated_status', 0);
		$this->db->order_by(SORT_COLUMN, SORT_ORDER);
		$result = $this->db->get('users')->result_array();
		return $result;
	}

	function fetch_company_employee_id($company_sid, $email)
	{
		$this->db->select('sid');
		$this->db->where('email', $email);
		$this->db->where('parent_sid', $company_sid);
		$this->db->where('active', 1);
		$this->db->where('terminated_status', 0);

		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['sid'];
		}

		return $return_data;
	}

	function fetch_incident_reported_messages($inc_type, $inc_reported_id, $receiver_id)
	{
		$this->db->select('*');
		$this->db->where('incident_type_id', $inc_type);
		$this->db->where('incident_reporting_id', $inc_reported_id);
		$this->db->where('sender_sid', $receiver_id);
		$this->db->or_where('receiver_sid', $receiver_id);

		$records_obj = $this->db->get('incident_reporting_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_company_name_by_sid($sid)
	{
		$this->db->select('CompanyName');
		$this->db->where('sid', $sid);
		$this->db->where('active', 1);
		$this->db->where('terminated_status', 0);
		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['CompanyName'];
		}

		return $return_data;
	}

	function get_incident_related_comments($sid)
	{
		$this->db->select('employer_sid, applicant_sid, comment, date_time');
		$this->db->where('incident_reporting_id', $sid);
		$this->db->where('response_type', 'Response');
		$records_obj = $this->db->get('incident_reporting_comments');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_incident_detail($sid)
	{
		$this->db->select('incident_name, employer_sid, current_date, report_type');
		$this->db->where('id', $sid);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_employee_title($sid)
	{
		$this->db->select('job_title');
		$this->db->where('sid', $sid);
		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = 'N/A';

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['job_title'];
		}

		return $return_data;
	}

	function insert_incident_video_reccord($data_to_insert)
	{
		$this->db->insert('incident_related_videos', $data_to_insert);
		return $this->db->insert_id();
	}

	function get_incident_videos($incident_sid, $type = 'all')
	{
		$this->db->select('*');
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('is_incident_reported', 1);
		$this->db->where('file_type', 'Incident file');
		$this->db->order_by('uploaded_date', 'desc');
		if ($type != 'all') $this->db->where('is_archived', $type);
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_single_incident_video($video_sid)
	{
		$this->db->select('*');
		$this->db->where('sid', $video_sid);
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function get_single_incident_document($document_sid)
	{
		$this->db->select('*');
		$this->db->where('id', $document_sid);
		$records_obj = $this->db->get('incident_reporting_documents');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function getManualEmails($employerSid, $incidentReportingSid, $employee)
	{
		$result = $this->db
			->where('manual_email IS NOT NULL', null)
			->where('incident_reporting_id', $incidentReportingSid)
			->group_start()
			->where('sender_sid', $employerSid)
			->or_where('receiver_sid', $employerSid)
			->group_end()
			// ->order_by('sid', 'ASC')
			->order_by('send_date', 'desc')
			->get('incident_reporting_emails');
		//
		$manualEmails = $result->result_array();
		$result = $result->free_result();
		//
		if (!sizeof($manualEmails)) return array();
		$emails = array();
		foreach ($manualEmails as $k0 => $v0) {
			if (!isset($emails[$v0['manual_email']])) {
				$emails[$v0['manual_email']]['name']          = ucwords(strtolower($employee['first_name'] . ' ' . $employee['last_name'])) . ' ( Manager )';
				$emails[$v0['manual_email']]['user_one']      = 0;
				$emails[$v0['manual_email']]['user_one_email'] = $v0['manual_email'];
				$emails[$v0['manual_email']]['user_two']      = $employerSid;
				$emails[$v0['manual_email']]['incident_id']   = $incidentReportingSid;
			}
			$emails[$v0['manual_email']]['emails'][] = $v0;
		}
		return array_values($emails);
	}

	function get_manual_emails($employee_sid, $incident_sid, $employee_name)
	{
		$result = $this->db
			->where('manual_email IS NOT NULL', null)
			->where('incident_reporting_id', $incident_sid)
			->group_start()
			->where('sender_sid', $employee_sid)
			->or_where('receiver_sid', $employee_sid)
			->group_end()
			// ->order_by('sid', 'ASC')
			->order_by('send_date', 'desc')
			->get('incident_reporting_emails');
		//
		$manualEmails = $result->result_array();
		$result = $result->free_result();
		//
		if (!sizeof($manualEmails)) return array();
		$emails = array();
		foreach ($manualEmails as $k0 => $v0) {
			if (!isset($emails[$v0['manual_email']])) {
				$emails[$v0['manual_email']]['name']          = ucwords($employee_name) . ' ( Manager )';
				$emails[$v0['manual_email']]['user_one']      = 0;
				$emails[$v0['manual_email']]['user_one_email'] = $v0['manual_email'];
				$emails[$v0['manual_email']]['user_two']      = $employee_sid;
				$emails[$v0['manual_email']]['incident_id']   = $incident_sid;
			}
			$emails[$v0['manual_email']]['emails'][] = $v0;
		}

		return array_values($emails);
	}

	function moveDocumentToArchive($documentSid)
	{
		$this->db->where('id', $documentSid)->update('incident_reporting_documents', array('is_archived' => 1));
	}

	function moveDocumentToActive($documentSid)
	{
		$this->db->where('id', $documentSid)->update('incident_reporting_documents', array('is_archived' => 0));
	}

	function moveVideoToArchive($videoSid)
	{
		$this->db->where('sid', $videoSid)->update('incident_related_videos', array('is_archived' => 1));
	}

	function moveVideoToActive($videoSid)
	{
		$this->db->where('sid', $videoSid)->update('incident_related_videos', array('is_archived' => 0));
	}

	function update_incident_document($document_sid, $incident_sid, $data_to_update)
	{
		$this->db->where('id', $document_sid);
		$this->db->where('incident_reporting_id', $incident_sid);
		$this->db->update('incident_reporting_documents', $data_to_update);
	}

	function update_incident_video($video_sid, $incident_sid, $data_to_update)
	{
		$this->db->where('sid', $video_sid);
		$this->db->where('incident_sid', $incident_sid);
		$this->db->update('incident_related_videos', $data_to_update);
	}

	function get_incident_image($sid)
	{
		$this->db->select('file_code');
		$this->db->where('id', $sid);
		$records_obj = $this->db->get('incident_reporting_documents');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = NULL;

		if (!empty($records_arr)) {
			$return_data = $records_arr[0]['file_code'];
		}

		return $return_data;
	}

	function get_incident_related_video($video_sid)
	{
		$this->db->select('*');
		$this->db->where('sid', $video_sid);
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function get_all_related_videos($incident_sid, $report_type)
	{
		$this->db->select('*');
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('is_incident_reported', 1);
		if ($report_type == '1') {
			$this->db->where('is_archived', 0);
		}
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function get_assign_manager_info($employer_sid, $company_sid, $incident_sid)
	{
		$this->db->select('sid, incident_status');
		$this->db->where('employer_sid', $employer_sid);
		$this->db->where('company_sid', $company_sid);
		$this->db->where('incident_sid', $incident_sid);
		$records_obj = $this->db->get('incident_assigned_emp');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function update_assign_manager_status($sid, $data_to_update)
	{
		$this->db->where('sid', $sid);
		$this->db->update('incident_assigned_emp', $data_to_update);
	}

	function update_email_is_read_flag($sid, $data_to_update)
	{
		$this->db->where('sid', $sid);
		$this->db->update('incident_reporting_emails', $data_to_update);
	}

	function get_email_sender_info($sid)
	{
		$this->db->select('manual_email, sender_sid, incident_reporting_id');
		$this->db->where('sid', $sid);
		$records_obj = $this->db->get('incident_reporting_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function get_attach_file_info($sid, $type)
	{
		$this->db->select('*');
		if ($type == "media") {
			$this->db->where('sid', $sid);
			$records_obj = $this->db->get('incident_related_videos');
		} else if ($type == "document") {
			$this->db->where('id', $sid);
			$records_obj = $this->db->get('incident_reporting_documents');
		}

		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr[0];
		}

		return $return_data;
	}

	function is_it_email_attachment($item_path)
	{
		$this->db->select('*');
		$this->db->where('item_path', $item_path);
		$records_obj = $this->db->get('incident_email_attachments');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = 0;

		if (!empty($records_arr)) {
			$return_data = 1;
		}

		return $return_data;
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



	function get_employee_detail($employee_sid)
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
		$this->db->where('sid', $employee_sid);
		$this->db->where('username !=', '');
		$this->db->where('terminated_status', 0);
		$this->db->where('active', 1);

		$this->db->order_by('access_level', 'ASC');

		$records_obj = $this->db->get('users');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		return $records_arr;
	}

	function checkManualUserIsAnEmployee($email, $companyId)
	{
		if (
            $this->db
            ->where('email', $email)
            ->where('parent_sid', $companyId)
            ->count_all_results('users')
        ) {
            return true;
        } else {
            return false;
        }
	}

	function isIncidentManager($email, $companyId, $incidentId)
	{
		$this->db->select('sid');
		$this->db->where('email', $email);
		$this->db->where('parent_sid', $companyId);
		$record_obj = $this->db->get('users');
		$employeeId = $record_obj->row_array()['sid'];
		$record_obj->free_result();
		//
		if (
            $this->db
            ->where('employer_sid', $employeeId)
			->where('id', $incidentId)
            ->count_all_results('incident_reporting')
        ) {
            return "reporter";
        } else if (
			$this->db
            ->where('employer_sid', $employeeId)
			->where('incident_sid', $incidentId)
            ->count_all_results('incident_assigned_emp')
		) {
            return "incident_manager";
        }
		//
		return "out_sider";
		
	}

	public function getMyEmails ($incidentId, $employeeId) {
		$where = "(sender_sid='" . $employeeId . "' OR receiver_sid='" . $employeeId . "')";
		$this->db->select('*');
		$this->db->where($where);
		$this->db->where('incident_reporting_id', $incidentId);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('incident_reporting_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		$incident_emails = array();

		if (!empty($records_arr)) {
			foreach ($records_arr as $email) {
				$email['reverse_check'] = 0;
				$userId = 0;
				//
				if ($email['receiver_sid'] == $employeeId)  {
					$email['email_status'] = 'received';
					$userId = $email['sender_sid'];

				} else {
					$userId = $email['receiver_sid'];
					$email['email_status'] = 'send';
				}
				//
				if (!array_key_exists($userId, $incident_emails)) {
					//
					$userName = '';
					//
					if (str_replace('_wid', '', $userId) != $userId) {
                        $witnessId = str_replace('_wid', '', $userId);
                        $witnessInfo = $this->get_witness_info_by_id($witnessId, $incidentId);
						$userName = $witnessInfo['witness_name'] . " (Other Witness)";
						//
						$email['manual_email'] = $witnessInfo['witness_email'];
					} else {
						$employeeInfo = $this->get_employee_info_by_id($userId);
						$userType = $this->getUserType($employeeInfo, $incidentId, $userId);
						//
						$userName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
					}
					//	
					$incident_emails[$userId]['userName'] = $userName;
					$incident_emails[$userId]['userId'] = $userId;
					$incident_emails[$userId]['employeeId'] = $employeeId;
					$incident_emails[$userId]['incidentId'] = $incidentId;
				}
				//
				$incident_emails[$userId]['emails'][] = $email;
			}
		}
		//
		return $incident_emails;
	}

	public function getUserType ($userInfo, $incidentId, $userId) {
		if (
            $this->db
            ->where('witness_email', $userInfo['email'])
			->where('incident_reporting_id', $incidentId)
            ->count_all_results('incident_related_witnesses')
        ) {
            return "Company Witness";
        } else if (
			$this->db
            ->where('employer_sid', $userId)
			->where('incident_sid', $incidentId)
            ->count_all_results('incident_assigned_emp')
		) {
            return "Assigned Manager";
        } else {
			return "Company Employee";
		}
	}

	public function getComplianceSafetyTitle ($incidentId) {
		$this->db->select('compliance_safety_title');
		$this->db->where('id', $incidentId);
		//
		$record_obj = $this->db->get('incident_reporting');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		return $record_arr['compliance_safety_title'];
	}
}

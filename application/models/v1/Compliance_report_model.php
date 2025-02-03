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

    function updateComplianceReport($id, $data)
	{
		$this->db->where('id', $id);
		$this->db->update('incident_reporting', $data);
	}

    function updateComplianceRelatedVideo($incident_sid, $video_to_update)
	{
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('is_incident_reported', 0);
		$this->db->update('incident_related_videos', $video_to_update);
	}

    function insertComplianceReport($insert)
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

    function insertComplianceQuestionAnswer($insert)
	{
		$this->db->insert('incident_reporting_question_answer', $insert);
		return $this->db->insert_id();
	}

    function assignComplianceReportToEmployees($data)
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

	function insertComplianceVideoRecord($data_to_insert)
	{
		$this->db->insert('incident_related_videos', $data_to_insert);
		return $this->db->insert_id();
	}

	function insertComplianceDocument($data)
	{
		$this->db->insert('incident_reporting_documents', $data);
		return $this->db->insert_id();
	}

	function getComplianceVideo($video_sid)
	{
		$this->db->select('*');
		$this->db->where('sid', $video_sid);
		$records_obj = $this->db->get('incident_related_videos');
		$records_arr = $records_obj->row_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function updateComplianceVideo($video_sid, $incident_sid, $data_to_update)
	{
		$this->db->where('sid', $video_sid);
		$this->db->where('incident_sid', $incident_sid);
		$this->db->update('incident_related_videos', $data_to_update);
	}

	function isEmailAttachment($item_path)
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

	function updateComplianceDocument($document_sid, $incident_sid, $data_to_update)
	{
		$this->db->where('id', $document_sid);
		$this->db->where('incident_reporting_id', $incident_sid);
		$this->db->update('incident_reporting_documents', $data_to_update);
	}

	function getComplianceDocument($document_sid)
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

	function getComplianceTypeId($id, $company_sid)
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

	public function getComplianceSafetyTitle ($incidentId) {
		$this->db->select('compliance_safety_title');
		$this->db->where('id', $incidentId);
		//
		$record_obj = $this->db->get('incident_reporting');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		return $record_arr['compliance_safety_title'];
	}

	function getComplianceReportName($id, $company_sid)
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

	function getReportedComplianceReportType($id, $company_sid)
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

	function getComplianceReportAssignedManagers($incident_id, $company_sid)
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

	function getComplianceReportInitiator($id)
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

	function getComplianceReportInitiatorName($id)
	{
		$this->db->select('employer_sid');
		$this->db->where('id', $id);
		$records_obj = $this->db->get('incident_reporting');
		$records_arr = $records_obj->row_array();
		$records_obj->free_result();

		return getEmployeeOnlyNameBySID($records_arr['employer_sid']);
	}

	function fetchComplianceManagers($incident_id, $company_sid)
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

	function view_single_assign($id)
	{
		$this->db->select('que_ans.*');
		$this->db->select('incident_reporting.report_type, incident_reporting.status, incident_reporting.employer_sid as reporter_id');
		$this->db->select('users.first_name,users.last_name,users.PhoneNumber,users.job_title,users.email');
		$this->db->where('incident_reporting.id', $id);
		$this->db->join('incident_reporting_question_answer as que_ans', 'que_ans.incident_reporting_id = incident_reporting.id', 'left');
		$this->db->join('users', 'users.sid = incident_reporting.employer_sid', 'left');

		$incident = $this->db->get('incident_reporting')->result_array();
		return $incident;
	}

	function getComplianceVideos($incident_sid, $type = 'all')
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

	function getComplianceDocuments($id, $type = 'all')
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
					if ($email["manual_email"]) {
						$split_email = explode('@', $email['manual_email']);
                        $userName = $split_email[0].' (OutSider)';
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

	public function getOtherEmails ($incidentId, $employeeId) {
		// get all user
		$this->db->select('employer_sid');
		$this->db->where('incident_sid', $incidentId);
		$this->db->where('employer_sid !=', $employeeId);
		//
		$records_obj = $this->db->get('incident_assigned_emp');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		//
		$incident_emails = array();
		//
		if ($records_arr) {
			//
			$otherEmployees = array_column($records_arr, 'employer_sid');
			//
			foreach ($otherEmployees as $otherEmployeeId) {
				$where = "(sender_sid ='" . $otherEmployeeId . "' OR receiver_sid ='" . $otherEmployeeId . "')";
				$this->db->select('*');
				$this->db->where('sender_sid !=', $employeeId);
				$this->db->where('receiver_sid !=', $employeeId);
				$this->db->where($where);
				$this->db->where('incident_reporting_id', $incidentId);
				$this->db->order_by('send_date', 'desc');
				$records_obj = $this->db->get('incident_reporting_emails');
				$records_arr = $records_obj->result_array();
				$records_obj->free_result();
				//
				$otherEmployeesEmails = [];
				//
				if (!empty($records_arr)) {
					foreach ($records_arr as $email) {
						$email['reverse_check'] = 0;
						$userId = 0;
						//
						if ($email['receiver_sid'] == $otherEmployeeId)  {
							$email['email_status'] = 'received';
							$userId = $email['sender_sid'];
		
						} else {
							$userId = $email['receiver_sid'];
							$email['email_status'] = 'send';
						}
						//
						if (!array_key_exists($userId, $otherEmployeesEmails)) {
							//
							$userName = '';
							//
							$employeeInfo = $this->get_employee_info_by_id($userId);
							if ($email["manual_email"]) {
								$split_email = explode('@', $email['manual_email']);
								$userName = $split_email[0].' (OutSider)';
							} else {
								$employeeInfo = $this->get_employee_info_by_id($userId);
								$userType = $this->getUserType($employeeInfo, $incidentId, $userId);
								//
								$userName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
							}
							//	
							$otherEmployeesEmails[$userId]['userName'] = $userName;
							$otherEmployeesEmails[$userId]['userId'] = $userId;
							$otherEmployeesEmails[$userId]['employeeId'] = $employeeId;
							$otherEmployeesEmails[$userId]['incidentId'] = $incidentId;
						}
						//
						$otherEmployeesEmails[$userId]['emails'][] = $email;
					}
					//
					$incident_emails[$otherEmployeeId] = $otherEmployeesEmails;
				}
				//

			}
		}
		//
		return $incident_emails;
	}

	public function getPrivateUserEmails ($incidentId, $emailId) {
		$this->db->select('*');
		$this->db->where('manual_email', $emailId);
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
				if ($email['receiver_sid'] == 0)  {
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
					if ($email["manual_email"]) {
						$split_email = explode('@', $email['manual_email']);
                        $userName = $split_email[0].' (OutSider)';
					} else {
						$employeeInfo = $this->get_employee_info_by_id($userId);
						$userType = $this->getUserType($employeeInfo, $incidentId, $userId);
						//
						$userName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
					}
					//	
					$incident_emails[$userId]['userName'] = $userName;
					$incident_emails[$userId]['userId'] = $userId;
					$incident_emails[$userId]['employeeId'] = $emailId;
					$incident_emails[$userId]['incidentId'] = $incidentId;
				}
				//
				$incident_emails[$userId]['emails'][] = $email;
			}
		}
		//
		return $incident_emails;
	}

	function getOtherUsersEmails ($incidentId, $emailId) {
		$this->db->select('employer_sid');
		$this->db->where('incident_sid', $incidentId);
		//
		$records_obj = $this->db->get('incident_assigned_emp');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		//
		$incident_emails = array();
		//
		if ($records_arr) {
			//
			$otherEmployees = array_column($records_arr, 'employer_sid');
			//
			foreach ($otherEmployees as $otherEmployeeId) {
				$where = "(sender_sid ='" . $otherEmployeeId . "' OR receiver_sid ='" . $otherEmployeeId . "')";
				$this->db->select('*');
				$this->db->where('manual_email <>', $emailId);
				$this->db->where($where);
				$this->db->where('incident_reporting_id', $incidentId);
				$this->db->order_by('send_date', 'desc');
				$records_obj = $this->db->get('incident_reporting_emails');
				$records_arr = $records_obj->result_array();
				$records_obj->free_result();
				//
				$otherEmployeesEmails = [];
				//
				if (!empty($records_arr)) {
					foreach ($records_arr as $email) {
						$email['reverse_check'] = 0;
						$userId = 0;
						//
						if ($email['receiver_sid'] == $otherEmployeeId)  {
							$email['email_status'] = 'received';
							$userId = $email['sender_sid'];
		
						} else {
							$userId = $email['receiver_sid'];
							$email['email_status'] = 'send';
						}
						//
						if (!array_key_exists($userId, $otherEmployeesEmails)) {
							//
							$userName = '';
							//
							if ($email["manual_email"]) {
								$split_email = explode('@', $email['manual_email']);
								$userName = $split_email[0].' (OutSider)';
							} else {
								$employeeInfo = $this->get_employee_info_by_id($userId);
								$userType = $this->getUserType($employeeInfo, $incidentId, $userId);
								//
								$userName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
							}
							//	
							$otherEmployeesEmails[$userId]['userName'] = $userName;
							$otherEmployeesEmails[$userId]['userId'] = $userId;
							$otherEmployeesEmails[$userId]['employeeId'] = $otherEmployeeId;
							$otherEmployeesEmails[$userId]['incidentId'] = $incidentId;
						}
						//
						$otherEmployeesEmails[$userId]['emails'][] = $email;
					}
					//
					$incident_emails[$otherEmployeeId] = $otherEmployeesEmails;
				}
				//

			}
		}
		//
		return $incident_emails;
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

	function getComplianceReportEmails($user_sid, $employee_sid, $incident_reporting_id)
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

	function getComplianceReportComments($id)
	{
		$this->db->select('
			comment,date_time,
			t1.username as user1,
			t2.username as user2,
			response_type, 
			t1.profile_picture as pp1,
			t2.profile_picture as pp2,
			incident_reporting_comments.applicant_sid as emp_id,
			incident_reporting_comments.manual_email
		');
		$this->db->where('incident_reporting_id', $id);
		$this->db->join('users as t1', 't1.sid = incident_reporting_comments.applicant_sid', 'left');
		$this->db->join('users as t2', 't2.sid = incident_reporting_comments.employer_sid', 'left');
		// $this->db->order_by("incident_reporting_comments.id", "asc");
		$this->db->order_by("incident_reporting_comments.date_time", "desc");
		$comments = $this->db->get('incident_reporting_comments')->result_array();
		//
		return $comments;
	}

	public function getComplianceSafetyReportEmployees ($incidentId) {
		$this->db->select('employer_sid');
		$this->db->where('incident_sid', $incidentId);
		//
		$records_obj = $this->db->get('incident_assigned_emp');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		//
		return array_column($records_arr, 'employer_sid');
	}

	function insertComplianceReportEmailRecord($data_to_insert)
	{
		$this->db->insert('incident_reporting_emails', $data_to_insert);
		return $this->db->insert_id();
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

	function getUserInfoByEmail ($email, $companyId) {
		$this->db->select('sid, first_name, last_name');
		$this->db->where('email', $email);
		$this->db->where('parent_sid', $companyId);
		//
		$record_obj = $this->db->get('users');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		//
		return $record_arr;
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

	function insert_email_attachment($data_to_insert)
	{
		$this->db->insert('incident_email_attachments', $data_to_insert);
	}

	function isComplianceReportManager($email, $companyId, $incidentId)
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

	function addComplianceReportComment($data)
	{
		$this->db->insert('incident_reporting_comments', $data);
		return $this->db->insert_id();
	}

	function insertNewEmployeeToComplianceReport($data_to_insert)
	{
		$this->db->insert('incident_assigned_emp', $data_to_insert);
		return $this->db->insert_id();
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

	function getComplianceReportDetail($sid)
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

	function getComplianceReportSingleEmail($sid, $incident_sid)
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

	function getComplianceReportEmailsByEmailAddress($email, $employee_sid, $incident_reporting_id)
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



	function getComplianceReportRelatedComments($sid)
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


	function getComplianceReportVideos($incident_reporting_id, $report_type)
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

	function getComplianceReportDocumentsToDownload($id, $report_type)
	{
		$this->db->where('incident_reporting_id', $id);
		if ($report_type == '1') {
			$this->db->where('is_archived', 0);
		}
		$result = $this->db->get('incident_reporting_documents')->result_array();
		return $result;
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

	function getComplianceVideoById($video_sid)
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

	function getComplianceReportImage($sid)
	{
		$this->db->select('file_code');
		$this->db->where('id', $sid);
		$records_obj = $this->db->get('incident_reporting_documents');
		$records_arr = $records_obj->row_array();
		$records_obj->free_result();
		$return_data = NULL;

		if (!empty($records_arr)) {
			$return_data = $records_arr['file_code'];
		}

		return $return_data;
	}

	function insertComplianceReportLog ($insert) {
		$this->db->insert('incident_report_log', $insert);
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

	function getIncidentIDByVideoId($videoId)
	{
		//
		$this->db->select('incident_sid');
		$this->db->where('sid', $videoId);
		$record_obj = $this->db->get('incident_related_videos');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		//
		return $record_arr['incident_sid'];
	}

	function getIncidentIDByDocumentId($documentId)
	{
		//
		$this->db->select('incident_reporting_id');
		$this->db->where('sid', $documentId);
		$record_obj = $this->db->get('incident_reporting_documents');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		//
		return $record_arr['incident_reporting_id'];
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

	function getCompanyIDByComplianceReportID($sid)
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

	function check_receiver_status($incident_sid, $company_sid, $employee_sid)
	{
		$this->db->where('incident_sid', $incident_sid);
		$this->db->where('company_sid', $company_sid);
		$this->db->where('employer_sid', $employee_sid);
		return $this->db->get('incident_assigned_emp')->num_rows();
	}

	public function isEmployeeInitiatedReport ($incidentId, $companyId, $employeeId) {
		if (
            $this->db
			->where('id', $incidentId)
            ->where('employer_sid', $employeeId)
            ->where('company_sid', $companyId)
            ->count_all_results('incident_reporting')
        ) {
            return true;
        } else {
            return false;
        }
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

	function insertComplianceTrackingRecord($dataToInsert)
	{
		$this->db->insert('compliance_report_tracking', $dataToInsert);
	}

	function insertVideoHistory($data)
	{
		$this->db->insert('compliance_report_videos_history', $data);
		return $this->db->insert_id();
	}

	function insertDocumentHistory($data)
	{
		$this->db->insert('compliance_report_documents_history', $data);
		return $this->db->insert_id();
	}

	public function checkManualUserExist ($emailId, $reportId) {
		if (
            !$this->db
			->where('email', $emailId)
            ->where('compliance_report_sid', $reportId)
            ->count_all_results('compliance_report_outsider_user')
        ) {
			//
			$session = $this->session->userdata('logged_in');
            $employeeId = $session["employer_detail"]["sid"];
			//
			$outsiderUser = array();
			$outsiderUser['compliance_report_sid'] = $reportId;
			$outsiderUser['email'] = $emailId;
			$outsiderUser['added_by'] = $employeeId;
			$outsiderUser['created_at'] = date('Y-m-d H:i:s');
			//
			$this->db->insert('compliance_report_outsider_user', $outsiderUser);
			$userId = $this->db->insert_id();
			//
			$trackingObj = array(
				'compliance_report_sid' => $reportId,
				'employee_sid' => $employeeId,
				'item_type' => "outsider_user",
				'action' => "add",
				'item_sid' => $userId,
				'created_at' => date('Y-m-d H:i:s')
			);
			//
			$this->compliance_report_model->insertComplianceTrackingRecord($trackingObj);
        }
	}

	public function checkUserHasPermissionToReport ($emailId, $reportId) {
		if (
            $this->db
			->where('email', $emailId)
            ->where('compliance_report_sid', $reportId)
			->where('is_active', 1)
            ->count_all_results('compliance_report_outsider_user')
        ) {
			return true;
        } else {
			return false;
		}
	}

}
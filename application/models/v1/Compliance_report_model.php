<?php if (!defined('BASEPATH'))
	exit('No direct script access allowed');

class Compliance_report_model extends CI_Model
{
	private $userFields;
	private $allowedCSP;
	public function __construct()
	{
		parent::__construct();
		$this->userFields =
			"`users`.`first_name`,
			`users`.`middle_name`,
			`users`.`last_name`,
			`users`.`job_title`,
			`users`.`access_level`,
			`users`.`access_level_plus`,
			`users`.`is_executive_admin`,
			`users`.`pay_plan_flag`";
		//
		$this->allowedCSP = [
			"implements" => false,
			"reports" => [],
			"incidents" => [],
		];
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

	public function getComplianceSafetyTitle($incidentId)
	{
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
		$this->db->where('file_type', 'compliance report file');
		$this->db->order_by('uploaded_date', 'desc');
		if ($type != 'all')
			$this->db->where('is_archived', $type);
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
		$this->db->where('file_type', 'compliance report file');
		if ($type != 'all') {
			$this->db->where('is_archived', $type);
		}
		$this->db->order_by('uploaded_date', 'desc');
		$result = $this->db->get('incident_reporting_documents')->result_array();
		return $result;
	}

	public function getMyEmails($incidentId, $employeeId)
	{
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
				if ($email['receiver_sid'] == $employeeId) {
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
						$userName = $split_email[0] . ' (OutSider)';
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

	public function getOtherEmails($incidentId, $employeeId)
	{
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
						if ($email['receiver_sid'] == $otherEmployeeId) {
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
								$userName = $split_email[0] . ' (OutSider)';
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

	public function getPrivateUserEmails($incidentId, $emailId)
	{
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
				if ($email['receiver_sid'] == 0) {
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
						$userName = $split_email[0] . ' (OutSider)';
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

	function getOtherUsersEmails($incidentId, $emailId)
	{
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
						if ($email['receiver_sid'] == $otherEmployeeId) {
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
								$userName = $split_email[0] . ' (OutSider)';
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

	public function getUserType($userInfo, $incidentId, $userId)
	{
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
		if (!sizeof($manualEmails))
			return array();
		$emails = array();
		foreach ($manualEmails as $k0 => $v0) {
			if (!isset($emails[$v0['manual_email']])) {
				$emails[$v0['manual_email']]['name'] = ucwords($employee_name) . ' ( Manager )';
				$emails[$v0['manual_email']]['user_one'] = 0;
				$emails[$v0['manual_email']]['user_one_email'] = $v0['manual_email'];
				$emails[$v0['manual_email']]['user_two'] = $employee_sid;
				$emails[$v0['manual_email']]['incident_id'] = $incident_sid;
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

	public function getComplianceSafetyReportEmployees($incidentId)
	{
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

	function getUserInfoByEmail($email, $companyId)
	{
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

	function insertComplianceReportLog($insert)
	{
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

	public function isEmployeeInitiatedReport($incidentId, $companyId, $employeeId)
	{
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

	public function checkManualUserExist($emailId, $reportId)
	{
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

	public function checkUserHasPermissionToReport($emailId, $reportId)
	{
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

	// new functions

	/**
	 * Get all compliance reports
	 *
	 * @return array
	 */
	public function getAllReportTypes()
	{
		return $this
			->db
			->select([
				"id",
				"compliance_report_name",
				"instructions",
				"color_code",
				"bg_color_code",
			])
			->where("status", 1)
			->order_by("compliance_report_name", "ASC")
			->get("compliance_report_types")
			->result_array();
	}

	/**
	 * Get report type by id
	 *
	 * @param int $reportTypeId
	 * @return array
	 */
	public function getReportTypeById($reportTypeId)
	{
		return $this
			->db
			->select([
				"id",
				"compliance_report_name",
				"instructions",
				"color_code",
				"bg_color_code",
			])
			->where("id", $reportTypeId)
			->get("compliance_report_types")
			->row_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $companyId
	 * @param int $employeeId
	 * @return array
	 */
	public function getActiveEmployees($companyId, $employeeId)
	{
		return $this
			->db
			->select([
				"sid",
				"first_name",
				"middle_name",
				"last_name",
				"job_title",
				"access_level",
				"access_level_plus",
				"is_executive_admin",
				"pay_plan_flag",
			])
			->where("parent_sid", $companyId)
			->where("sid <>", $employeeId)
			->where("active", 1)
			->where("terminated_status", 0)
			->get("users")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportTypeId
	 * @param int $companyId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function addReport(
		int $reportTypeId,
		int $companyId,
		int $loggedInEmployeeId,
		array $post
	) {
		//
		$todayDateTime = getSystemDate();
		//
		// lets first add the report
		$reportData = [
			"report_type_sid" => $post["report_type"],
			"company_sid" => $companyId,
			"created_by" => $loggedInEmployeeId,
			"last_modified_by" => $loggedInEmployeeId,
			"title" => $post["report_title"],
			"report_date" => formatDateToDB(
				$post["report_date"],
				"m/d/Y",
				DB_DATE
			),
			"completion_date" => $post["report_completion_date"] ? formatDateToDB(
				$post["report_completion_date"],
				"m/d/Y",
				DB_DATE
			) : null,
			"status" => $post["report_status"],
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$reportData["answers_json"] = [];
		//
		if (isset($post["departments"])) {
			$reportData["answers_json"]["departments"] = $post["departments"];
		}
		if (array_key_exists("compliance_date", $post)) {
			$reportData["answers_json"]["compliance_date"] = $post["compliance_date"];
		}
		//
		$reportData["answers_json"] = json_encode($reportData["answers_json"]);
		//
		$this->db->insert("csp_reports", $reportData);
		//
		$reportId = $this->db->insert_id();
		//
		$jsonLogData = [
			'action' => 'create',
			'type' => 'report',
			'title' => $post["report_title"],
			'dateTime' => $todayDateTime,
			'fields' => [
				'report_date' => $post['report_date'],
				'completion_date' => $post['report_completion_date'],
				'status' => $post['report_status']
			]
		];
		//
		if (!$reportId) {
			return false;
		}
		//
		$insert = array();

		foreach ($post as $key => $val) {
			if (!$this->isQuestionField($key)) {
				continue;
			}
			$exp = explode('_', $key);
			if (sizeof($exp) > 1 && !empty($val)) {
				$insert['question'] = $this->getSpecificReportQuestion($exp[1]);

				if ($exp[0] == 'multi-list') {
					$val = serialize($val);
				}

				$insert['answer'] = strip_tags($val);
				$insert['csp_report_sid'] = $reportId;
				$this->insertCSPReportAnswer($insert);
			} elseif (sizeof($exp) == 1 && !empty($val) && $exp[0] == 'signature') {
				$insert['question'] = $exp[0];
				$insert['answer'] = strip_tags($val);
				$insert['csp_report_sid'] = $reportId;
				$this->insertCSPReportAnswer($insert);
			}
		}
		//
		if ($post["report_employees"]) {
			$reportEmployees = [];
			foreach ($post["report_employees"] as $employeeId) {
				$reportEmployees[] = [
					"csp_reports_sid" => $reportId,
					"employee_sid" => $employeeId,
					"created_by" => $loggedInEmployeeId,
					"created_at" => $todayDateTime,
					"updated_at" => $todayDateTime,
				];
			}
			$this->db->insert_batch("csp_reports_employees", $reportEmployees);
			//
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_internal_system_count" => count($reportEmployees),
				]);
			//
			$jsonLogData['internalEmployees'] = $post['report_employees'];
		}
		// add external employees
		if ($post["external_employees_names"]) {
			$externalEmployees = [];
			foreach ($post["external_employees_names"] as $key => $item) {
				$externalEmployees[] = [
					"csp_reports_sid" => $reportId,
					"is_external_employee" => 1,
					"external_name" => $item[0],
					"external_email" => $post["external_employees_emails"][$key][0],
					"created_by" => $loggedInEmployeeId,
					"created_at" => $todayDateTime,
					"updated_at" => $todayDateTime,
				];
			}
			$this->db->insert_batch("csp_reports_employees", $externalEmployees);
			//
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_external_employees_count" => count($externalEmployees),
				]);
			//
			$jsonLogData['externalEmployees'] = $post['external_employees_emails'];
		}
		//
		// Save log on create report
		$this->saveComplianceSafetyReportLog(
			[
				'reportId' => $reportId,
				'incidentId' => 0,
				'incidentItemId' => 0,
				'type' => 'main',
				'userType' => 'employee',
				'userId' => $loggedInEmployeeId,
				'jsonData' => $jsonLogData

			]
		);

		return $reportId;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportById(int $reportId, array $columns)
	{
		$report = $this
			->db
			->select($columns)
			->join(
				"compliance_report_types",
				"compliance_report_types.id = csp_reports.report_type_sid",
				"left"
			)
			->join(
				"users",
				"users.sid = csp_reports.last_modified_by",
				"left"
			)
			->where("csp_reports.sid", $reportId)
			->get("csp_reports")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		//
		$report["internal_employees"] = $this->getCSPReportInternalEmployeesById($reportId, [
			"sid",
			"employee_sid"
		]);
		$report["external_employees"] = $this->getCSPReportExternalEmployeesById($reportId, [
			"sid",
			"external_name",
			"external_email",
		]);
		//
		$report["notes"] = $this->getCSPReportNotesById($reportId, [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.created_by",
			"csp_reports_notes.updated_at",
			"csp_reports_notes.manual_email"
		]);
		//
		$report["documents"] = $this->getCSPReportFilesByType($reportId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.manual_email",
		], [
			"document",
			"file",
			"image",
		]);
		//
		$report["audios"] = $this->getCSPReportFilesByType($reportId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.manual_email",
		], [
			"audio",
			"video",
			"link",
		]);
		//
		$report["incidents"] = $this->getCSPReportIncidents($reportId, [
			"compliance_incident_types.compliance_incident_type_name",
			"csp_reports_incidents.sid",
			"csp_reports_incidents.status",
			"csp_reports_incidents.completed_at",
			"csp_reports_incidents.completed_by",
			"csp_reports_incidents.updated_at",
			"csp_reports_incidents.created_by"
		]);
		$report["question_answers"] = $this->getCSPReportQuestionAnswers($reportId);
		//
		// $report["emails"] = $this->getComplianceEmails($reportId, 0);
		$report["libraryItems"] = $this->getComplianceReportFiles($reportId, 0, 0);
		//
		return $report;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportByIdForEmail(int $reportId, array $columns)
	{
		$report = $this
			->db
			->select($columns)
			->join(
				"compliance_report_types",
				"compliance_report_types.id = csp_reports.report_type_sid",
				"left"
			)
			->join(
				"users",
				"users.sid = csp_reports.last_modified_by",
				"left"
			)
			->where("csp_reports.sid", $reportId)
			->get("csp_reports")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		//
		$report["internal_employees"] = $this->getCSPReportInternalEmployeesByIdNew($reportId, [
			"csp_reports_employees.sid",
			"csp_reports_employees.employee_sid",
			"csp_reports_employees.unique_code",
			"csp_reports_employees.csp_reports_incidents_items_sid",
			"users.first_name",
			"users.last_name",
			"users.email",
		], true);
		$report["external_employees"] = $this->getCSPReportExternalEmployeesByIdNew($reportId, [
			"sid",
			"csp_reports_employees.unique_code",
			"csp_reports_employees.csp_reports_incidents_items_sid",
			"external_name",
			"external_email",
		]);
		return $report;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPIncident(int $reportId, int $incidentId)
	{
		$report = $this
			->db
			->select("csp_reports_incidents.*")
			->select("compliance_incident_types.id as csp_incident_original_id")
			->select("compliance_incident_types.compliance_incident_type_name")
			->select("compliance_incident_types.description")
			->select($this->userFields)
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
				"inner"
			)
			->join(
				"users",
				"users.sid = csp_reports_incidents.last_modified_by",
				"left"
			)
			->where("csp_reports_incidents.sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		// get the list of items available to the incident
		$report["incident_items"] = $this->getCSPItems($report["csp_incident_original_id"]);
		$report["incidentItemsSelected"] = $this->getCSPAttachedItems($incidentId);
		//
		if ($report["incidentItemsSelected"]) {
			foreach ($report["incidentItemsSelected"] as $ikey => $item) {
				$report["incidentItemsSelected"][$ikey]['attachments'] = $this->getCSPItemAttachments($reportId, $incidentId, $item['sid']);
			}

		}
		//
		$report["internal_employees"] = $this
			->getCSPIncidentInternalEmployeesById($reportId, $incidentId, 0, [
				"csp_reports_employees.sid",
				"csp_reports_employees.employee_sid"
			]);
		//
		$report["external_employees"] = $this
			->getCSPIncidentExternalEmployeesById($reportId, $incidentId, 0, [
				"sid",
				"external_name",
				"external_email",
			]);
		//
		$report["notes"] = $this->getCSPIncidentNotesById($reportId, $incidentId, 0, [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.updated_at",
			"csp_reports_notes.created_by",
			"csp_reports_notes.manual_email"
		]);
		//
		$report["documents"] = $this->getCSPIncidentFilesByType(
			$reportId,
			$incidentId,
			0,
			[
				$this->userFields,
				"csp_reports_files.file_value",
				"csp_reports_files.sid",
				"csp_reports_files.title",
				"csp_reports_files.s3_file_value",
				"csp_reports_files.file_type",
				"csp_reports_files.created_at",
				"csp_reports_files.created_by",
				"csp_reports_files.manual_email"
			],
			[
				"document",
				"file",
				"image",
			]
		);
		//
		$report["audios"] = $this->getCSPIncidentFilesByType($reportId, $incidentId, 0, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.created_by",
			"csp_reports_files.manual_email"
		], [
			"audio",
			"video",
			"link",
		]);
		//
		$report["question_answers"] = $this->getCSPQuestionAnswers($incidentId);
		//
		// $report["emails"] = $this->getComplianceEmails($reportId, $incidentId);
		$report["libraryItems"] = $this->getComplianceReportFiles($reportId, $incidentId, 0);
		//
		return $report;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPIncidentForEmail(int $incidentId)
	{
		$report = $this
			->db
			->select("csp_reports.company_sid")
			->select("csp_reports_incidents.csp_reports_sid")
			->select("compliance_incident_types.compliance_incident_type_name")
			->join(
				"csp_reports",
				"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
				"inner"
			)
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
				"inner"
			)
			->join(
				"users",
				"users.sid = csp_reports_incidents.last_modified_by",
				"left"
			)
			->where("csp_reports_incidents.sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		//
		$report["internal_employees"] = $this
			->getCSPIncidentInternalEmployeesByIdNew($report["csp_reports_sid"], $incidentId, [
				"csp_reports_employees.sid",
				"csp_reports_employees.employee_sid",
				"csp_reports_employees.unique_code",
				"csp_reports_employees.csp_reports_incidents_items_sid",
				"users.first_name",
				"users.last_name",
				"users.email",
			]);
		//
		$report["external_employees"] = $this
			->getCSPIncidentExternalEmployeesByIdNew($report["csp_reports_sid"], $incidentId, [
				"sid",
				"csp_reports_employees.unique_code",
				"csp_reports_employees.csp_reports_incidents_items_sid",
				"external_name",
				"external_email",
			]);
		return $report;
	}


	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportInternalEmployeesById(int $reportId, array $columns, bool $join = false)
	{
		//
		if ($join) {
			$this
				->db
				->join(
					"users",
					"users.sid = csp_reports_employees.employee_sid"
				);
		}
		return $this->db
			->select($columns)
			->where("csp_reports_employees.csp_reports_sid", $reportId)
			->where("csp_reports_employees.is_external_employee", 0)
			->where("csp_reports_employees.csp_report_incident_sid", 0)
			->where("csp_reports_employees.status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportInternalEmployeesByIdNew(int $reportId, array $columns, bool $join = false)
	{
		//
		if ($join) {
			$this
				->db
				->join(
					"users",
					"users.sid = csp_reports_employees.employee_sid"
				);
		}
		return $this->db
			->select($columns)
			->where("csp_reports_employees.csp_reports_sid", $reportId)
			->where("csp_reports_employees.is_external_employee", 0)
			->where("csp_reports_employees.status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @return array
	 */
	public function getCSPIncidentInternalEmployeesById(int $reportId, int $incidentId, int $itemId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_employees.csp_reports_sid", $reportId)
			->where("csp_reports_employees.is_external_employee", 0)
			->where("csp_reports_employees.csp_report_incident_sid", $incidentId)
			->where("csp_reports_employees.csp_reports_incidents_items_sid", $itemId)
			->where("csp_reports_employees.status", 1)
			->join(
				"users",
				"users.sid = csp_reports_employees.employee_sid"
			)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @return array
	 */
	public function getCSPIncidentDepartmentsAndTeamsById(int $issueId)
	{
		return $this->db
			->select("allowed_departments, allowed_teams")
			->where("sid", $issueId)
			->get("csp_reports_incidents_items")
			->row_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @return array
	 */
	public function getCSPIncidentExternalEmployeesById(int $reportId, int $incidentId, int $itemId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_sid", $reportId)
			->where("is_external_employee", 1)
			->where("csp_report_incident_sid", $incidentId)
			->where("csp_reports_incidents_items_sid", $itemId)
			->where("status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @return array
	 */
	public function getCSPIncidentExternalEmployeesByIdNew(int $reportId, int $incidentId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_sid", $reportId)
			->where("is_external_employee", 1)
			->where("csp_report_incident_sid", $incidentId)
			->where("status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @return array
	 */
	public function getCSPIncidentInternalEmployeesByIdNew(int $reportId, int $incidentId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_employees.csp_reports_sid", $reportId)
			->where("csp_reports_employees.is_external_employee", 0)
			->where("csp_reports_employees.csp_report_incident_sid", $incidentId)
			->where("csp_reports_employees.status", 1)
			->join(
				"users",
				"users.sid = csp_reports_employees.employee_sid"
			)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportExternalEmployeesById(int $reportId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_sid", $reportId)
			->where("is_external_employee", 1)
			->where("csp_report_incident_sid", 0)
			->where("status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportExternalEmployeesByIdNew(int $reportId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_sid", $reportId)
			->where("is_external_employee", 1)
			->where("status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $recordId
	 * @return array
	 */
	public function deleteExternalEmployee(int $reportId, int $recordId)
	{
		$this->db
			->where("csp_reports_sid", $reportId)
			->where("sid", $recordId)
			->delete("csp_reports_employees");
	}

	public function getReportCurrentStatus($reportId)
	{
		$report = $this
			->db
			->select('status')
			->where("sid", $reportId)
			->get("csp_reports")
			->row_array();
		//
		return $report['status'];
	}
	/**
	 * Get all compliance reports
	 *
	 * @param int $reportTypeId
	 * @param int $companyId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function editReport(
		int $reportId,
		int $loggedInEmployeeId,
		array $post
	) {
		//
		$loggedInEmployeeName = '';
		$todayDateTime = getSystemDate();
		//
		if ($loggedInEmployeeId != 0) {
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
		}
		// lets first edit the report
		$reportData = [
			"last_modified_by" => $loggedInEmployeeName,
			"title" => $post["report_title"],
			"report_date" => formatDateToDB(
				$post["report_date"],
				"m/d/Y",
				DB_DATE
			),
			"completion_date" => $post["report_completion_date"] ? formatDateToDB(
				$post["report_completion_date"],
				"m/d/Y",
				DB_DATE
			) : null,
			"status" => $post["report_status"],
			"updated_at" => $todayDateTime,
		];
		//
		$currentStatus = $this->getReportCurrentStatus($reportId);
		//
		if ($post["report_status"] == 'completed' && $currentStatus != 'completed') {
			$reportData['completed_by'] = $loggedInEmployeeName;
		} else if ($post["report_status"] != 'completed' && $currentStatus == 'completed') {
			$reportData['completed_by'] = null;
		}
		//
		$this
			->db
			->where("sid", $reportId)
			->update("csp_reports", $reportData);
		//
		if ($loggedInEmployeeId != 0) {
			// Save log on update report
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => 0,
					'incidentItemId' => 0,
					'type' => 'main',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'update',
						'type' => 'report',
						'title' => $post["report_title"],
						'dateTime' => $todayDateTime,
						'fields' => [
							'report_date' => $post['report_date'],
							'completion_date' => $post['report_completion_date'],
							'status' => $post['report_status']
						],
						'internalEmployees' => $post['report_employees'],
						'externalEmployees' => $post['external_employees_emails']
					]
				]
			);
		}
		//
		$this->updateCSPEmployees(
			$post,
			$reportId,
			0,
			0,
			$loggedInEmployeeId
		);

		// $this->sendEmailsForCSPReport($reportId, CSP_UPDATED_EMAIL_TEMPLATE_ID);

		return true;
	}


	/**
	 * Get all compliance reports
	 *
	 * @param int $reportTypeId
	 * @param int $companyId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function editIncidentReport(
		int $reportId,
		int $incidentId,
		int $loggedInEmployeeId,
		array $post
	) {

		if (
			!$this->db->where([
				"disable_answers" => 1,
				"sid" => $incidentId,
			])->count_all_results("csp_reports_incidents")
		) {
			//
			$insert = array();

			foreach ($post as $key => $val) {
				if (!$this->isQuestionField($key)) {
					continue;
				}
				$exp = explode('_', $key);
				if (sizeof($exp) > 1 && !empty($val)) {
					$insert['question'] = $this->getSpecificQuestion($exp[1]);
					if (empty($insert['question'])) {
						continue;
					}

					if ($exp[0] == 'multi-list') {
						$val = serialize($val);
					}

					$insert['answer'] = strip_tags($val);
					$insert['csp_report_incident_sid'] = $incidentId;
					//
					if ($insert['question']) {
						$this->insertCSPIncidentAnswer($insert);
					}

				} elseif (sizeof($exp) == 1 && !empty($val) && $exp[0] == 'signature') {
					$insert['question'] = $exp[0];
					$insert['answer'] = strip_tags($val);
					$insert['csp_report_incident_sid'] = $incidentId;
					//
					if ($insert['question']) {
						$this->insertCSPIncidentAnswer($insert);
					}
				}
			}
		}
		//
		$loggedInEmployeeName = '';
		$todayDateTime = getSystemDate();
		//
		if ($loggedInEmployeeId != 0) {
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
		}
		// lets first edit the report
		$reportData = [
			"last_modified_by" => $loggedInEmployeeId,
			"completed_at" => $post["report_completion_date"] ? formatDateToDB(
				$post["report_completion_date"],
				"m/d/Y",
				DB_DATE
			) : null,
			"status" => $post["report_status"],
			"updated_at" => $todayDateTime,
			"disable_answers" => 1
		];
		//
		$reportData["fields_json"] = [];
		//
		if (isset($post["dynamicInput"])) {
			$reportData["fields_json"]["dynamicInput"] = $post["dynamicInput"];
		}
		if (array_key_exists("dynamicCheckbox", $post)) {
			$reportData["fields_json"]["dynamicCheckbox"] = $post["dynamicCheckbox"];
		}
		//
		$reportData["fields_json"] = json_encode($reportData["fields_json"]);
		//
		//
		$currentStatus = $this->getIncidentCurrentStatus($incidentId);
		//
		if ($post["report_status"] == 'completed' && $currentStatus != 'completed') {
			$reportData['completed_by'] = $loggedInEmployeeName;
		} else if ($post["report_status"] != 'completed' && $currentStatus == 'completed') {
			$reportData['completed_by'] = null;
		}
		//
		$this
			->db
			->where("sid", $incidentId)
			->update("csp_reports_incidents", $reportData);
		//
		$this->updateCSPEmployees(
			$post,
			$reportId,
			$incidentId,
			0,
			$loggedInEmployeeId
		);
		//
		// $this->sendEmailsForCSPIncident($incidentId, CSP_INCIDENT_UPDATED_EMAIL_TEMPLATE_ID);
		//
		if ($loggedInEmployeeId != 0) {
			// Save log on update incident
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => 0,
					'type' => 'incidents',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'update',
						'dateTime' => $todayDateTime,
						'fields' => [
							'completion_date' => $post['report_completion_date'],
							'status' => $post['report_status']
						],
						'internalEmployees' => $post['report_employees'],
						'externalEmployees' => $post['external_employees_emails']
					]
				]
			);
		}
		// $this->sendEmailsForCSPIncident($incidentId, CSP_INCIDENT_UPDATED_EMAIL_TEMPLATE_ID);

		return true;
	}

	public function getIncidentCurrentStatus($incidentId)
	{
		$report = $this
			->db
			->select('status')
			->where("sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array();
		//
		return $report['status'];
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function addNotesToReport(
		int $reportId,
		int $incidentId,
		int $loggedInEmployeeId,
		array $post
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$noteData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"note_type" => $post["type"],
			"notes" => $post["content"],
			"created_by" => $loggedInEmployeeId,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$this->db->insert("csp_reports_notes", $noteData);
		//
		$noteId = $this->db->insert_id();
		//
		if ($loggedInEmployeeId != 0 && $post["type"] == 'employee') {
			// Save log on add note
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => 0,
					'type' => 'notes',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => 'employee_note',
						'noteId' => $noteId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $noteId;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param array $columns
	 * @return array
	 */
	public function getCSPReportNotesById(int $reportId, array $columns)
	{
		return $this->db
			->select($columns, false)
			->where("csp_reports_sid", $reportId)
			->where("csp_incident_type_sid", 0)
			->join(
				"users",
				"users.sid = csp_reports_notes.created_by",
				"left"
			)
			->order_by("csp_reports_notes.sid", "DESC")
			->get("csp_reports_notes")
			->result_array();
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param array $columns
	 * @return array
	 */
	public function getCSPIncidentNotesById(int $reportId, int $incidentId, int $itemId, array $columns)
	{
		return $this->db
			->select($columns, false)
			->where("csp_reports_sid", $reportId)
			->where("csp_incident_type_sid", $incidentId)
			->where("csp_reports_incidents_items_sid", $itemId)
			->join(
				"users",
				"users.sid = csp_reports_notes.created_by",
				"left"
			)
			->get("csp_reports_notes")
			->result_array();
	}

	/**
	 * Add files to report
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param int $loggedInEmployeeId
	 * @param string $fileName
	 * @param string $originalFileName
	 * @param string $fileType
	 * @return array
	 */
	public function addFilesToReport(
		int $reportId,
		int $incidentId,
		int $itemId,
		int $loggedInEmployeeId,
		string $fileName,
		string $originalFileName,
		string $fileType,
		string $title
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$fileData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"csp_reports_incidents_items_sid" => $itemId,
			"file_type" => $fileType,
			"file_value" => $originalFileName,
			"s3_file_value" => $fileName,
			"title" => $title,
			"created_by" => $loggedInEmployeeId,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$this->db->insert("csp_reports_files", $fileData);
		//
		$fileId = $this->db->insert_id();
		//
		if ($loggedInEmployeeId != 0) {
			//
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			if ($reportId != 0 && $incidentId == 0) {
				$this->compliance_report_model->addManualUserEmail(
					$reportId,
					$dataToUpdate,
					'csp_reports'
				);
			}

			if ($incidentId != 0 && $itemId == 0) {
				$this->compliance_report_model->addManualUserEmail(
					$incidentId,
					$dataToUpdate,
					'csp_reports_incidents'
				);
			}
			// Save log on Add file to AWS
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => 0,
					'type' => 'files',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => $fileType,
						'title' => $title,
						'fileId' => $fileId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $fileId;
	}

	/**
	 * Get report files by type
	 *
	 * @param int $reportId
	 * @param array $columns
	 * @param array $type
	 * @return array
	 */
	public function getCSPReportFilesByType(int $reportId, array $columns, array $type)
	{
		$this->db->select($columns, false);
		$this->db->where("csp_reports_sid", $reportId);
		$this->db->where("csp_incident_type_sid", 0);
		$this->db->where_in("file_type", $type);
		$this->db->join(
			"users",
			"users.sid = csp_reports_files.created_by",
			"left"
		);
		return $this->db->get("csp_reports_files")->result_array();
	}

	/**
	 * Get report files by type
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param array $columns
	 * @param array $type
	 * @return array
	 */
	public function getCSPIncidentFilesByType(int $reportId, int $incidentId, int $itemId, array $columns, array $type)
	{
		$this->db->select($columns, false);
		$this->db->where("csp_reports_sid", $reportId);
		$this->db->where("csp_incident_type_sid", $incidentId);
		$this->db->where("csp_reports_incidents_items_sid", $itemId);
		$this->db->where_in("file_type", $type);
		$this->db->join(
			"users",
			"users.sid = csp_reports_files.created_by",
			"left"
		);
		return $this->db->get("csp_reports_files")->result_array();
	}


	/**
	 * Get report files by type
	 *
	 * @param int $reportId
	 * @param array $columns
	 * @param array $type
	 * @param string $status
	 * default -> all
	 * @return array
	 */
	public function getCSPReportIncidents(int $reportId, array $columns, string $status = "all")
	{
		$this->db->select($columns, false);
		$this->db->where("csp_reports_incidents.csp_reports_sid", $reportId);
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		$this->db->order_by("csp_reports_incidents.sid", "DESC");
		if ($status !== "all") {
			$this->db->where(
				"csp_reports_incidents.status",
				$status
			);
		}
		return $this->db->get("csp_reports_incidents")->result_array();
	}

	/**
	 * Get report files by type
	 *
	 * @param int $employeeId
	 * @param array $columns
	 * @param array $type
	 * @param string $status
	 * default -> all
	 * @return array
	 */
	public function getCSPAllowedIncidents(int $employeeId, array $columns, string $status = "all")
	{
		//
		if (!isMainAllowedForCSP($employeeId)) {
			$this->getAllowedCSPIds($employeeId);
			if (!$this->allowedCSP["reports"] && !$this->allowedCSP["incidents"]) {
				return [];
			}

			if ($status !== "all") {
				$this->db->where(
					"csp_reports_incidents.status",
					$status
				);
			}

			if ($this->allowedCSP["reports"] && $this->allowedCSP["incidents"]) {
				$this->db->group_start();
				$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
				$this->db->or_where_in("csp_reports_incidents.sid", $this->allowedCSP["incidents"]);
				$this->db->group_end();
			} else if ($this->allowedCSP["reports"] && !$this->allowedCSP["incidents"]) {
				$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
			} else if (!$this->allowedCSP["reports"] && $this->allowedCSP["incidents"]) {
				$this->db->where_in("csp_reports_incidents.sid", $this->allowedCSP["incidents"]);
			}
		}
		//
		$this->db->select($columns, false);
		$this->db->join(
			"users",
			"users.sid = csp_reports_incidents.created_by",
			"left"
		);
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		$this->db->join(
			"csp_reports",
			"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
			"inner"
		);

		return $this->db->get("csp_reports_incidents")->result_array();
	}

	/**
	 * Get file by id
	 *
	 * @param int $fileId
	 * @param array $columns
	 * @return array
	 */
	public function getFileById(int $fileId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("sid", $fileId)
			->get("csp_reports_files")
			->row_array();
	}

	/**
	 * Add files to report
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param int $loggedInEmployeeId
	 * @param string $link
	 * @param string $linkType
	 * @param string $title
	 * @return array
	 */
	public function addFilesLinkToReport(
		int $reportId,
		int $incidentId,
		int $itemId,
		int $loggedInEmployeeId,
		string $link,
		string $linkType,
		string $title
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$fileData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"csp_reports_incidents_items_sid" => $itemId,
			"file_type" => $linkType,
			"file_value" => $link,
			"s3_file_value" => $link,
			"title" => $title,
			"created_by" => $loggedInEmployeeId,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$this->db->insert("csp_reports_files", $fileData);
		$fileId = $this->db->insert_id();
		//
		if ($loggedInEmployeeId != 0) {
			//
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			if ($reportId != 0 && $incidentId == 0) {
				$this->compliance_report_model->addManualUserEmail(
					$reportId,
					$dataToUpdate,
					'csp_reports'
				);
			}

			if ($incidentId != 0 && $itemId == 0) {
				$this->compliance_report_model->addManualUserEmail(
					$incidentId,
					$dataToUpdate,
					'csp_reports_incidents'
				);
			}

			// Save log on Add file Link
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => $itemId,
					'type' => 'files',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => 'link',
						'title' => $title,
						'fileId' => $fileId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $fileId;
	}

	/**
	 * 
	 */
	public function getReportMapping(int $reportTypeId)
	{
		return $this
			->db
			->select([
				"compliance_incident_types.id",
				"compliance_incident_types.compliance_incident_type_name",
			])
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = compliance_report_incident_types_mapping.incident_sid",
				"inner"
			)
			->where("compliance_report_incident_types_mapping.report_sid", $reportTypeId)
			->where("compliance_incident_types.status", 1)
			->get("compliance_report_incident_types_mapping")
			->result_array();
	}
	/**
	 * 
	 */
	public function getAllIncidents()
	{
		return $this
			->db
			->select([
				"compliance_incident_types.id",
				"compliance_incident_types.compliance_incident_type_name",
			])
			->where("compliance_incident_types.status", 1)
			->get("compliance_incident_types")
			->result_array();
	}

	/**
	 * 
	 */
	public function attachIncidentToReport(
		int $reportId,
		int $incidentId,
		int $loggedInEmployeeId
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$fileData = [
			"csp_reports_sid" => $reportId,
			"incident_type_sid" => $incidentId,
			"created_by" => getUserNameBySID($loggedInEmployeeId),
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$this->db->insert("csp_reports_incidents", $fileData);
		$id = $this->db->insert_id();

		//
		$this
			->db
			->where("sid", $reportId)
			->set("csp_incident_count", "csp_incident_count + 1", false)
			->update("csp_reports");
		// send public link
		// $this->sendEmailsForCSPIncident($id);
		//
		if ($loggedInEmployeeId != 0) {
			$loggedInEmployeeName = '';
			//
			if ($loggedInEmployeeId != 0) {
				$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			}
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			$this->compliance_report_model->addManualUserEmail(
				$reportId,
				$dataToUpdate,
				'csp_reports'
			);
			//
			// Save log on add incident
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $id,
					'incidentItemId' => 0,
					'type' => 'main',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => 'incident',
						'incidentId' => $id,
						'incidentTypeId' => $incidentId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $id;
	}

	/**
	 * 
	 */
	public function detachReportIncidentById(
		int $incidentId
	) {
		//
		$this->db
			->where("sid", $incidentId)
			->delete("csp_reports_incidents");
	}

	public function fetchQuestions($id)
	{
		$this->db->select('compliance_incident_types_questions.*', 'compliance_incident_types.compliance_incident_type_name');
		$this->db->where('compliance_incident_types_id', $id);
		$this->db->where('compliance_incident_types_questions.status', 1);
		$this->db->join('compliance_incident_types', 'compliance_incident_types_questions.compliance_incident_types_id = compliance_incident_types.id', 'left');
		$questions = $this->db->get('compliance_incident_types_questions')->result_array();
		return $questions;
	}

	public function getReportQuestionsById($id)
	{
		$this->db->select('compliance_report_types_questions.*');
		$this->db->where('compliance_report_types_id', $id);
		$this->db->where('compliance_report_types_questions.status', 1);
		$questions = $this->db->get('compliance_report_types_questions')->result_array();
		return $questions;
	}

	public function getQuestion($id)
	{
		$this->db->where('id', $id);
		$result = $this->db->get('compliance_incident_types_questions')->result_array();
		return $result;
	}

	public function getSpecificQuestion($id)
	{
		$this->db->where('id', $id);
		$this->db->select("label");
		$result = $this->db->get('compliance_incident_types_questions')->row_array();
		return $result["label"];
	}

	public function getSpecificReportQuestion($id)
	{
		$this->db->where('id', $id);
		$this->db->select("label");
		$result = $this->db->get('compliance_report_types_questions')->row_array();
		return $result["label"];
	}

	function insertCSPIncidentAnswer($insert)
	{
		$this->db->insert('csp_reports_incidents_answers', $insert);
		return $this->db->insert_id();
	}

	function insertCSPReportAnswer($insert)
	{
		$this->db->insert('csp_reports_answers', $insert);
		return $this->db->insert_id();
	}

	function getCSPQuestionAnswers($incidentId)
	{
		$this->db->select('csp_reports_incidents_answers.*');
		$this->db->where('csp_reports_incidents_answers.csp_report_incident_sid', $incidentId);

		$incident = $this->db->get('csp_reports_incidents_answers')->result_array();
		return $incident;
	}


	function getCSPReportQuestionAnswers($reportId)
	{
		$this->db->select('csp_reports_answers.*');
		$this->db->where('csp_reports_answers.csp_report_sid', $reportId);

		$incident = $this->db->get('csp_reports_answers')->result_array();
		return $incident;
	}


	public function getCSPReport(
		int $companyId,
		int $employeeId,
		string $status
	) {
		//
		if (!isMainAllowedForCSP($employeeId)) {
			$this->getAllowedCSPIds($employeeId);
			$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
		} else {
			$this->db->where("csp_reports.created_by", $employeeId);
		}

		if ($status === "pending") {
			$this->db->where_in("csp_reports.status", [$status, ""]);
		} else {
			$this->db->where_in("csp_reports.status", [$status]);
		}
		//
		$records = $this
			->db
			->select([
				"csp_reports.sid",
				"csp_reports.title",
				"csp_reports.report_date",
				"csp_reports.completion_date",
				"csp_reports.status",
				"csp_reports.status",
				"csp_reports.allowed_internal_system_count",
				"csp_reports.allowed_external_employees_count",
				"compliance_report_types.compliance_report_name",
			])
			->where([
				"csp_reports.company_sid" => $companyId,
			])
			->join(
				"compliance_report_types",
				"compliance_report_types.id = csp_reports.report_type_sid",
				"left"
			)
			->order_by("csp_reports.sid", "DESC")
			->get("csp_reports")
			->result_array();
		//

		return $records;
	}

	/**
	 * check the access of employee
	 *
	 * @param int $employeeId
	 * @return int
	 */
	public function hasAccess(int $employeeId): int
	{
		//
		$isListedAsEmployee = $this
			->db
			->where(
				"csp_reports_employees.employee_sid",
				$employeeId
			)
			->count_all_results("csp_reports_employees");

		if ($isListedAsEmployee) {
			return 1;
		}
		// let's check it in CSP managers
		return $this
			->db
			->where("FIND_IN_SET($employeeId, csp_managers_ids)")
			->count_all_results("departments_management");
	}

	/**
	 * check the access of employee
	 *
	 * @param int $employeeId
	 * @param int $companyId
	 * @param bool $hasMainAccess
	 * @return int
	 */
	public function getPendingCountReportsByEmployeeId(
		int $employeeId,
		int $companyId,
		bool $hasMainAccess
	): int {
		//
		$where = [
			"csp_reports_incidents_items.completion_status" => "pending",
			"csp_reports.company_sid" => $companyId,
		];
		//
		if (!$hasMainAccess) {
			$where["csp_reports_employees.employee_sid"] = $employeeId;
		}
		//
		$record = $this
			->db
			->where($where)
			->join(
				"csp_reports_incidents_items",
				"csp_reports_incidents_items.sid = csp_reports_employees.csp_reports_incidents_items_sid",
				"inner"
			)
			->join(
				"csp_reports",
				"csp_reports.sid = csp_reports_employees.csp_reports_sid",
				"inner"
			)
			->count_all_results("csp_reports_employees");
		//
		if ($record) {
			return $record;
		}
		// check the department
		if (!$hasMainAccess) {
			// get the logged in employee CSP manager for teams and departments
			$departmentTeamIds = $this->getEmployeeDepartmentAndTeamIds(
				$employeeId
			);
			//
			if ($departmentTeamIds["departmentIds"]) {
				$record = 0;
				//
				foreach ($departmentTeamIds["departmentIds"] as $v0) {
					$allowed = $this
						->db
						->where("FIND_IN_SET({$v0}, allowed_departments) > ", 0)
						->count_all_results("csp_reports_incidents_items");

					if ($allowed) {
						$record = 1;
						break;
					}
				}

				if ($record) {
					return $record;
				}
			}
			//
			if ($departmentTeamIds["teamIds"]) {

				$record = 0;
				//
				foreach ($departmentTeamIds["teamIds"] as $v0) {
					$allowed = $this
						->db
						->where("FIND_IN_SET({$v0}, allowed_teams) > ", 0)
						->count_all_results("csp_reports_incidents_items");

					if ($allowed) {
						$record = 1;
						break;
					}
				}
			}
		}
		//
		return 0;
	}


	public function getEmployeeDepartmentAndTeamIds($employeeId)
	{
		$ra = [
			"departmentIds" => [],
			"teamIds" => [],
		];
		// get department ids
		$records = $this
			->db
			->select("sid")
			->where("is_deleted", 0)
			->where("FIND_IN_SET($employeeId, csp_managers_ids) > ", 0)
			->get("departments_management")
			->result_array();
		//
		if ($records) {
			$ra["departmentIds"] = array_column(
				$records,
				"sid"
			);
		}
		// get team ids
		$records = $this
			->db
			->select("departments_team_management.sid")
			->where("departments_team_management.is_deleted", 0)
			->where("departments_management.is_deleted", 0)
			->where("FIND_IN_SET($employeeId, departments_team_management.csp_managers_ids) > ", 0)
			->join(
				"departments_management",
				"departments_management.sid = departments_team_management.department_sid",
				"inner"
			)
			->get("departments_team_management")
			->result_array();
		//
		if ($records) {
			$ra["teamIds"] = array_column(
				$records,
				"sid"
			);
		}
		//
		return $ra;
	}

	/**
	 * get allowed CSP reports and incidents
	 *
	 * @param int $employeeId
	 */
	public function getAllowedCSPIds(int $employeeId)
	{
		//
		$where = [
			"csp_reports_employees.employee_sid" => $employeeId,
			"csp_reports_employees.csp_reports_incidents_items_sid <> " => 0,
		];
		//
		$records = $this
			->db
			->select("csp_reports_employees.csp_reports_sid")
			->select("csp_reports_employees.csp_report_incident_sid")
			->select("csp_reports_employees.csp_reports_incidents_items_sid")
			->where($where)
			->get("csp_reports_employees")
			->result_array();
		//
		if ($records) {
			$reports = [];
			$tasks = [];
			$incidents = [];
			//
			foreach ($records as $item) {
				// add report ids
				// if (!in_array($item["csp_reports_sid"], $reports)) {
				// 	$reports[] = $item["csp_reports_sid"];
				// }
				// if (!in_array($item["csp_report_incident_sid"], $incidents)) {
				// 	$incidents[] = $item["csp_report_incident_sid"];
				// }
				if (!in_array($item["csp_reports_incidents_items_sid"], $tasks)) {
					$tasks[] = $item["csp_reports_incidents_items_sid"];
				}
			}
		}

		// get the department ids
		$departmentIds = $this
			->db
			->select("sid")
			->where([
				"FIND_IN_SET({$employeeId}, csp_managers_ids) > " => 0,
				"status" => 1,
				"is_deleted" => 0,
			])
			->get("departments_management")
			->result_array();
		//
		if ($departmentIds) {
			$this->allowedCSP["departmentIds"] = array_column($departmentIds, "sid");
		}
		// get the department ids
		$teamIds = $this
			->db
			->select("departments_team_management.sid")
			->where([
				"FIND_IN_SET({$employeeId}, departments_team_management.csp_managers_ids) > " => 0,
				"departments_team_management.status" => 1,
				"departments_team_management.is_deleted" => 0,
				"departments_management.status" => 1,
				"departments_management.is_deleted" => 0,
			])
			->join(
				"departments_management",
				"departments_management.sid = departments_team_management.department_sid",
				"inner"
			)
			->get("departments_team_management")
			->result_array();
		//
		if ($teamIds) {
			$this->allowedCSP["teamIds"] = array_column($teamIds, "sid");
		}
		//
		$this->allowedCSP["implements"] = true;
		$this->allowedCSP["reports"] = $reports;
		$this->allowedCSP["incidents"] = $incidents;
		$this->allowedCSP["tasks"] = $tasks;
		$this->allowedCSP["employeeId"] = $employeeId;
	}

	/**
	 * Send email to the report employees
	 *
	 * @param int $reportId
	 */
	public function sendEmailsForCSPReport(int $reportId, int $templateId = CSP_ASSIGNED_EMAIL_TEMPLATE_ID)
	{
		// get report
		$report = $this
			->getCSPReportByIdForEmail(
				$reportId,
				[
					"csp_reports.company_sid",
					"csp_reports.title",
				]
			);
		//
		$companyName = getCompanyColumnById($report["company_sid"], "CompanyName")["CompanyName"];
		// get the company header
		$hf = message_header_footer(
			$report["company_sid"],
			$companyName
		);

		if ($report["external_employees"]) {
			foreach ($report["external_employees"] as $item) {
				if ($item['csp_reports_incidents_items_sid'] != 0) {
					//
					if (!$item["unique_code"]) {
						//
						$code = generateUniqueCode(15);
						// update unique code
						$this
							->db
							->where("sid", $item["sid"])
							->update("csp_reports_employees", [
								"unique_code" => $code
							]);
					} else {
						$code = $item["unique_code"];
					}
					// set replace array
					$ra = [
						"first_name" => $item["external_name"],
						"title" => $report["title"],
						"last_name" => "",
						"email" => $item["external_email"],
						"company_name" => $companyName,
						"csp_public_url" => generateEmailButton(
							"#fd7a2a",
							("csp/single/{$code}"),
							"View Compliance Safety Report"
						),
						"base_url" => base_url(),
					];
					//
					log_and_send_templated_email(
						$templateId,
						$item["external_email"],
						$ra,
						$hf,
						1,
						[]
					);
				}
			}
		}

		if ($report["internal_employees"]) {
			foreach ($report["internal_employees"] as $item) {
				if ($item['csp_reports_incidents_items_sid'] != 0) {
					//
					if (!$item["unique_code"]) {
						//
						$code = generateUniqueCode(15);
						// update unique code
						$this
							->db
							->where("sid", $item["sid"])
							->update("csp_reports_employees", [
								"unique_code" => $code
							]);
					} else {
						$code = $item["unique_code"];
					}
					// set replace array
					$ra = [
						"first_name" => $item["first_name"],
						"last_name" => $item["last_name"],
						"title" => $report["title"],
						"email" => $item["email"],
						"company_name" => $companyName,
						"csp_public_url" => generateEmailButton(
							"#fd7a2a",
							("csp/single/{$code}"),
							"View Compliance Safety Report"
						),
						"base_url" => base_url(),
					];
					//
					log_and_send_templated_email(
						$templateId,
						$item["email"],
						$ra,
						$hf,
						1,
						[]
					);
				}
			}
		}
	}

	/**
	 * Send email to the report employees
	 *
	 * @param int $reportId
	 */
	public function sendEmailsForCSPIncident(int $incidentId, int $templateId = CSP_INCIDENT_ASSIGNED_EMAIL_TEMPLATE_ID)
	{
		// get report
		$report = $this
			->getCSPIncidentForEmail(
				$incidentId
			);
		//
		$companyName = getCompanyColumnById($report["company_sid"], "CompanyName")["CompanyName"];
		// get the company header
		$hf = message_header_footer(
			$report["company_sid"],
			$companyName
		);

		if ($report["external_employees"]) {
			foreach ($report["external_employees"] as $item) {
				if ($item['csp_reports_incidents_items_sid'] != 0) {
					//
					if (!$item["unique_code"]) {
						//
						$code = generateUniqueCode(15);
						// update unique code
						$this
							->db
							->where("sid", $item["sid"])
							->update("csp_reports_employees", [
								"unique_code" => $code
							]);
					} else {
						$code = $item["unique_code"];
					}
					// set replace array
					$ra = [
						"first_name" => $item["external_name"],
						"title" => $report["compliance_incident_type_name"],
						"last_name" => "",
						"email" => $item["external_email"],
						"company_name" => $companyName,
						"csp_public_url" => generateEmailButton(
							"#fd7a2a",
							("csp/single/{$code}"),
							"View Compliance Safety Report Incident"
						),
						"base_url" => base_url(),
					];
					//
					log_and_send_templated_email(
						$templateId,
						$item["external_email"],
						$ra,
						$hf,
						1,
						[]
					);
				}
			}
		}

		if ($report["internal_employees"]) {
			foreach ($report["internal_employees"] as $item) {
				if ($item['csp_reports_incidents_items_sid'] != 0) {
					//
					if (!$item["unique_code"]) {
						//
						$code = generateUniqueCode(15);
						// update unique code
						$this
							->db
							->where("sid", $item["sid"])
							->update("csp_reports_employees", [
								"unique_code" => $code
							]);
					} else {
						$code = $item["unique_code"];
					}
					// set replace array
					$ra = [
						"first_name" => $item["first_name"],
						"last_name" => $item["last_name"],
						"title" => $report["compliance_incident_type_name"],
						"email" => $item["email"],
						"company_name" => $companyName,
						"csp_public_url" => generateEmailButton(
							"#fd7a2a",
							("csp/single/{$code}"),
							"View Compliance Safety Report Incident"
						),
						"base_url" => base_url(),
					];
					//
					log_and_send_templated_email(
						$templateId,
						$item["email"],
						$ra,
						$hf,
						1,
						[]
					);
				}
			}
		}
	}

	public function updateCSPEmployees($post, $reportId, $incidentId, $itemId, $loggedInEmployeeId)
	{
		//
		$todayDateTime = getSystemDate();
		// add internal employees
		if ($post["report_employees"]) {
			$employeeIds = [];
			//
			$reportEmployees = [];
			foreach ($post["report_employees"] as $employeeId) {
				//
				$employeeIds[] = $employeeId;
				//
				if (
					$this
						->db
						->where('csp_reports_sid', $reportId)
						->where('csp_report_incident_sid', $incidentId)
						->where('csp_reports_incidents_items_sid', $itemId)
						->where('employee_sid', $employeeId)
						->count_all_results('csp_reports_employees') > 0
				) {
					continue;
				}
				$reportEmployees[] = [
					"csp_reports_sid" => $reportId,
					"csp_report_incident_sid" => $incidentId,
					"csp_reports_incidents_items_sid" => $itemId,
					"employee_sid" => $employeeId,
					"created_by" => $loggedInEmployeeId,
					"created_at" => $todayDateTime,
					"updated_at" => $todayDateTime,
				];
			}
			if ($reportEmployees) {
				// insert new employees
				$this->db->insert_batch("csp_reports_employees", $reportEmployees);
			}
			// update the count
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_internal_system_count" => count($reportEmployees),
				]);
			//
			// delete
			$this
				->db
				->where('csp_reports_sid', $reportId)
				->where('csp_report_incident_sid', $incidentId)
				->where('csp_reports_incidents_items_sid', $itemId)
				->where('is_external_employee', 0)
				->where_not_in('employee_sid', $employeeIds)
				->delete("csp_reports_employees");
		} else {
			$this
				->db
				->where('csp_reports_sid', $reportId)
				->where('csp_report_incident_sid', $incidentId)
				->where('csp_reports_incidents_items_sid', $itemId)
				->where('is_external_employee', 0)
				->delete('csp_reports_employees');
			// update the count
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_internal_system_count" => 0,
				]);
		}

		// add internal employees
		if ($post["external_employees_names"]) {
			$employeeIds = [];
			//
			$reportEmployees = [];
			foreach ($post["external_employees_names"] as $key => $item) {
				//
				$employeeIds[] = $post["external_employees_emails"][$key][0];
				//
				if (
					$this
						->db
						->where('csp_reports_sid', $reportId)
						->where('csp_report_incident_sid', $incidentId)
						->where('csp_reports_incidents_items_sid', $itemId)
						->where('external_email', $post["external_employees_emails"][$key][0])
						->count_all_results('csp_reports_employees') > 0
				) {
					continue;
				}
				$reportEmployees[] = [
					"csp_reports_sid" => $reportId,
					"csp_report_incident_sid" => $incidentId,
					"csp_reports_incidents_items_sid" => $itemId,
					"is_external_employee" => 1,
					"external_name" => $item[0],
					"external_email" => $post["external_employees_emails"][$key][0],
					"created_by" => $loggedInEmployeeId,
					"created_at" => $todayDateTime,
					"updated_at" => $todayDateTime,
				];
			}
			if ($reportEmployees) {
				// insert new employees
				$this->db->insert_batch("csp_reports_employees", $reportEmployees);
			}
			// update the count
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_external_employees_count" => count($reportEmployees),
				]);
			//
			// delete
			$this
				->db
				->where('csp_reports_sid', $reportId)
				->where('csp_report_incident_sid', $incidentId)
				->where('csp_reports_incidents_items_sid', $itemId)
				->where('is_external_employee', 1)
				->where_not_in('external_email', $employeeIds)
				->delete("csp_reports_employees");
		} else {
			$this
				->db
				->where('csp_reports_sid', $reportId)
				->where('csp_report_incident_sid', $incidentId)
				->where('csp_reports_incidents_items_sid', $itemId)
				->where('is_external_employee', 1)
				->delete('csp_reports_employees');
			// update the count
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_external_employees_count" => 0,
				]);
		}
	}

	/**
	 * Public routes
	 */

	/**
	 * Verify token
	 *
	 * @param string $token
	 * @return bool
	 */
	public function verifyToken(string $token): bool
	{
		return $this
			->db
			->where("unique_code", $token)
			->where("status", 1)
			->limit(1)
			->count_all_results("csp_reports_employees");
	}

	/**
	 * Public routes
	 */
	public function getTokenDetails(string $token): array
	{
		return $this
			->db
			->select($this->userFields)
			->select("csp_reports.company_sid")
			->select("csp_reports_employees.sid")
			->select("csp_reports_employees.csp_reports_sid")
			->select("csp_reports_employees.csp_report_incident_sid")
			->select("csp_reports_employees.employee_sid")
			->select("csp_reports_employees.is_external_employee")
			->select("csp_reports_employees.external_email")
			->select("csp_reports_employees.external_name")
			->where("csp_reports_employees.unique_code", $token)
			->join(
				"csp_reports",
				"csp_reports.sid = csp_reports_employees.csp_reports_sid",
				"inner"
			)
			->join(
				"users",
				"users.sid = csp_reports_employees.employee_sid",
				"left"
			)
			->limit(1)
			->get("csp_reports_employees")
			->row_array();
	}

	/**
	 * Public routes
	 */
	public function getAllowedCSPIdsById(string $employeeIdOrEmail)
	{
		// get the 
		$where = [
			(int) $employeeIdOrEmail === 0
			? "csp_reports_employees.external_email"
			: "csp_reports_employees.employee_sid" => $employeeIdOrEmail
		];
		//
		$records = $this
			->db
			->select("csp_reports_employees.csp_reports_sid")
			->select("csp_reports_employees.csp_report_incident_sid")
			->select("csp_reports_employees.csp_reports_incidents_items_sid")
			->where($where)
			->get("csp_reports_employees")
			->result_array();
		//
		if ($records) {
			$reports = [];
			$incidents = [];
			$tasks = [];
			//
			foreach ($records as $item) {
				// if (!in_array($item["csp_reports_sid"], $reports) && $item["csp_report_incident_sid"] == 0) {
				// 	$reports[] = $item["csp_reports_sid"];
				// }
				// if (!in_array($item["csp_report_incident_sid"], $incidents) && $item["csp_report_incident_sid"] != 0) {
				// 	$incidents[] = $item["csp_report_incident_sid"];
				// }
				if (!in_array($item["csp_reports_incidents_items_sid"], $tasks) && $item["csp_reports_incidents_items_sid"] != 0) {
					$tasks[] = $item["csp_reports_incidents_items_sid"];
				}
			}
		}
		//
		$this->allowedCSP["implements"] = true;
		$this->allowedCSP["reports"] = $reports;
		$this->allowedCSP["incidents"] = $incidents;
		$this->allowedCSP["tasks"] = $tasks;
		$this->allowedCSP["employeeIdOrEmail"] = $employeeIdOrEmail;
	}


	public function getCSPReportPublic(
		int $companyId,
		$employeeIdOrEmail,
		string $status
	) {
		//
		$this->getAllowedCSPIdsById($employeeIdOrEmail);
		if (!$this->allowedCSP["reports"]) {
			return [];
		}
		$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
		//
		$records = $this
			->db
			->select([
				"csp_reports.sid",
				"csp_reports.title",
				"csp_reports.report_date",
				"csp_reports.completion_date",
				"csp_reports.status",
				"csp_reports.status",
				"csp_reports.allowed_internal_system_count",
				"csp_reports.allowed_external_employees_count",
				"compliance_report_types.compliance_report_name",
			])
			->where([
				"csp_reports.company_sid" => $companyId,
				"csp_reports.status" => $status
			])->join(
				"compliance_report_types",
				"compliance_report_types.id = csp_reports.report_type_sid",
				"left"
			)
			->order_by("csp_reports.sid", "DESC")
			->get("csp_reports")
			->result_array();
		//
		if ($records) {
			foreach ($records as $k0 => $v0) {
				$records[$k0]["incidents"] = $this->getCSPReportIncidents($v0["sid"], [
					"compliance_incident_types.compliance_incident_type_name",
				]);
			}
		}

		return $records;
	}

	/**
	 * Get report files by type
	 *
	 * @param int $employeeId
	 * @param array $columns
	 * @param array $type
	 * @param string $status
	 * default -> all
	 * @return array
	 */
	public function getCSPAllowedIncidentsPublic($employeeIdOrEmail, array $columns, string $status = "all")
	{
		//
		$this->getAllowedCSPIdsById($employeeIdOrEmail);
		if (!$this->allowedCSP["reports"] && !$this->allowedCSP["incidents"]) {
			return [];
		}

		if ($this->allowedCSP["reports"] && $this->allowedCSP["incidents"]) {
			$this->db->where_in("csp_reports_incidents.sid", $this->allowedCSP["incidents"]);
			$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
		} else if ($this->allowedCSP["reports"] && !$this->allowedCSP["incidents"]) {
			$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
		} else if (!$this->allowedCSP["reports"] && $this->allowedCSP["incidents"]) {
			$this->db->where_in("csp_reports_incidents.sid", $this->allowedCSP["incidents"]);
		}

		//
		$this->db->select($columns, false);
		$this->db->join(
			"users",
			"users.sid = csp_reports_incidents.created_by",
			"left"
		);
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		$this->db->join(
			"csp_reports",
			"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
			"inner"
		);
		if ($status !== "all") {
			$this->db->where(
				"csp_reports_incidents.status",
				$status
			);
		}
		return $this->db->get("csp_reports_incidents")->result_array();
	}

	public function getComplianceEmails($reportId, $incidentId = 0, $current_user)
	{
		$this->db->select('*');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('csp_reports_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		//
		$incident_emails = array();
		//
		if (!empty($records_arr)) {
			foreach ($records_arr as $email) {
				if ($email['sender_sid'] == 0) {
					$split_email = explode('@', $email['manual_email']);
					$userId = $split_email[0];
				} else {
					$userId = $email['sender_sid'];
				}
				//
				if (!array_key_exists($userId, $incident_emails)) {
					//
					$userName = '';
					//
					if ($email["manual_email"] && $email['sender_sid'] == 0) {
						$split_email = explode('@', $email['manual_email']);
						$userName = $split_email[0] . ' (OutSider)';
					} else {
						$employeeInfo = $this->get_employee_info_by_id($userId);
						$userType = $this->getUserType($employeeInfo, $incidentId, $userId);
						//
						$userName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
					}

					//	
					$incident_emails[$userId]['userName'] = $userName;
					$incident_emails[$userId]['userId'] = $userId;
				}
				//
				$email['email_type'] = 'Sent';
				$incident_emails[$userId]['emails'][] = $email;
				//
				if ($email['receiver_sid'] == $current_user || ($email['receiver_sid'] == 0 && $email["manual_email"] == $current_user)) {
					if ($email['receiver_sid'] == 0) {
						$split_email = explode('@', $email['manual_email']);
						$receiverId = $split_email[0];
					} else {
						$receiverId = $email['receiver_sid'];
					}
					//
					if (!array_key_exists($receiverId, $incident_emails)) {
						//
						$receiverName = '';
						//
						if ($email["manual_email"] && $email['receiver_sid'] == 0) {
							$split_email = explode('@', $email['manual_email']);
							$receiverName = $split_email[0] . ' (OutSider)';
						} else {
							$employeeInfo = $this->get_employee_info_by_id($receiverId);
							$userType = $this->getUserType($employeeInfo, $incidentId, $receiverId);
							//
							$receiverName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
						}

						//	
						$incident_emails[$receiverId]['userName'] = $receiverName;
						$incident_emails[$receiverId]['userId'] = $receiverId;

					}
					//
					$email['email_type'] = 'Received';
					$incident_emails[$receiverId]['emails'][] = $email;
				}
			}
		}
		//
		return $incident_emails;
	}

	function addComplianceReportEmail($data_to_insert)
	{
		$this->db->insert('csp_reports_emails', $data_to_insert);
		return $this->db->insert_id();
	}

	public function getComplianceReportFiles($reportId, $incidentId = 0, $itemId = 0)
	{
		$this->db->select('sid, file_type, file_value, s3_file_value, title');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $itemId);
		$records_obj = $this->db->get('csp_reports_files');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		//
		return $records_arr;
	}

	function addComplianceEmailAttachment($dataToInsert)
	{
		$this->db->insert('csp_reports_email_attachments', $dataToInsert);
	}

	/**
	 * Add files to report
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param string $loggedInEmployeeEmail
	 * @param string $link
	 * @param string $linkType
	 * @param string $title
	 * @return array
	 */
	public function addExternalEmployeeFilesLinkToReport(
		int $reportId,
		int $incidentId,
		string $loggedInEmployeeEmail,
		string $link,
		string $linkType,
		string $title
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$fileData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"file_type" => $linkType,
			"file_value" => $link,
			"s3_file_value" => $link,
			"title" => $title,
			"created_by" => 0,
			"manual_email" => $loggedInEmployeeEmail,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$this->db->insert("csp_reports_files", $fileData);
		//
		return $this->db->insert_id();
	}

	public function checkAndGetComplianceSafetyReportUserKey($userId, $reportId, $incidentId)
	{
		$this->db->select('unique_code');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_report_incident_sid', $incidentId);
		//
		if (filter_var($userId, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('external_email', $userId);
		} else {
			$this->db->where('employee_sid', $userId);
		}
		//
		$record_obj = $this->db->get('csp_reports_employees');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		$userKey = '';
		//
		if (!empty($record_arr)) {
			$userKey = $record_arr['unique_code'];
		}
		//
		return $userKey;
	}

	function getComplianceSafetyReportEmails($user_sid, $employee_sid, $reportId, $incidentId, $itemId = 0)
	{
		$where = "(sender_sid='" . $user_sid . "' AND receiver_sid='" . $employee_sid . "' OR sender_sid='" . $employee_sid . "' AND receiver_sid='" . $user_sid . "')";
		$this->db->select('*');
		$this->db->where($where);
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $itemId);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('csp_reports_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function getComplianceSafetyReportEmailsByEmailAddress($email, $employee_sid, $reportId, $incidentId, $itemId = 0)
	{
		$where = "(sender_sid='0' AND receiver_sid='" . $employee_sid . "' AND manual_email='" . $email . "' OR sender_sid='" . $employee_sid . "' AND receiver_sid='0' AND manual_email='" . $email . "')";
		$this->db->select('*');
		$this->db->where($where);
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $itemId);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('csp_reports_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}

	function getCompanyIDByComplianceSafetyReportID($reportId)
	{
		$this->db->select('company_sid');
		$this->db->where('sid', $reportId);
		$record_obj = $this->db->get('csp_reports');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		$return_data = 'N/A';

		if (!empty($record_arr)) {
			$return_data = $record_arr['company_sid'];
		}

		return $return_data;
	}

	function updateEmailReadFlag($sid, $data_to_update)
	{
		$this->db->where('sid', $sid);
		$this->db->update('csp_reports_emails', $data_to_update);
	}

	function getEmailSenderInfo($sid)
	{
		$this->db->select('manual_email, sender_sid, csp_reports_sid, csp_incident_type_sid');
		$this->db->where('sid', $sid);
		$records_obj = $this->db->get('csp_reports_emails');
		$records_arr = $records_obj->row_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$return_data = $records_arr;
		}

		return $return_data;
	}


	function isUserHaveNewEmail($userId, $reportId, $incidentId)
	{

		$this->db->select('sid');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		//
		if (filter_var($userId, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('manual_email', $userId);
			$this->db->where('receiver_sid', 0);
		} else {
			$this->db->where('receiver_sid', $userId);
		}
		//
		$this->db->where('is_read', 0);
		$this->db->from('csp_reports_emails');
		$result = $this->db->get()->result_array();
		$return_data = 0;

		if (!empty($result)) {
			$return_data = count($result);
		}

		return $return_data;
	}

	function isUserHaveUnreadMessage($receiverId, $senderId, $reportId, $incidentId)
	{
		//
		$this->db->select('sid');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		//
		if (filter_var($receiverId, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('manual_email', $receiverId);
			$this->db->where('receiver_sid', 0);
		} else {
			$this->db->where('receiver_sid', $receiverId);
		}
		//
		if (filter_var($senderId, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('manual_email', $senderId);
			$this->db->where('sender_sid', 0);
		} else {
			$this->db->where('sender_sid', $senderId);
		}
		//
		$this->db->where('is_read', 0);
		$this->db->from('csp_reports_emails');
		$result = $this->db->get()->result_array();
		$return_data = 0;

		if (!empty($result)) {
			$return_data = count($result);
		}

		return $return_data;
	}

	public function getSeverityLevels()
	{
		$records = $this
			->db
			->order_by("sid", "ASC")
			->get("compliance_severity_levels")
			->result_array();
		if (!$records) {
			return [];
		}
		$tmp = [];
		foreach ($records as $record) {
			$tmp[$record["sid"]] = $record;
		}
		return $tmp;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPReportByIdForDownload(int $reportId, array $columns)
	{
		$report = $this
			->db
			->select($columns)
			->join(
				"compliance_report_types",
				"compliance_report_types.id = csp_reports.report_type_sid",
				"left"
			)
			->join(
				"users",
				"users.sid = csp_reports.last_modified_by",
				"left"
			)
			->where("csp_reports.sid", $reportId)
			->get("csp_reports")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		//
		if ($report['job_title'] == '' && $report['job_title'] == null) {
			if (isset($report['is_executive_admin']) && $report['is_executive_admin'] != 0) {
				$report['job_title'] = 'Executive ' . $report['access_level'];
			}
			if ($report['access_level_plus'] == 1 && $report['pay_plan_flag'] == 1) {
				$report['job_title'] . ' Plus / Payroll';
			}
			if ($report['access_level_plus'] == 1) {
				$report['job_title'] . ' Plus';
			}
			if ($report['pay_plan_flag'] == 1) {
				$report['job_title'] . ' Payroll';
			}
		}
		//
		$report["internal_employees"] = $this->getCSPReportInternalEmployeesById($reportId, [
			"csp_reports_employees.sid",
			"csp_reports_employees.employee_sid",
			"csp_reports_employees.created_by",
			"csp_reports_employees.created_at"
		]);
		//
		$report["external_employees"] = $this->getCSPReportExternalEmployeesById($reportId, [
			"sid",
			"external_name",
			"external_email",
			"created_by",
			"created_at"
		]);
		//
		$report["notes"] = $this->getCSPReportNotesByIdAndType($reportId, 0, 'employee', [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.created_by",
			"csp_reports_notes.created_at",
		]);
		//
		$report["documents"] = $this->getCSPReportFilesByType($reportId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.created_by",
			"csp_reports_files.manual_email",
		], [
			"document",
			"file",
			"image",
		]);
		//
		$report["audios"] = $this->getCSPReportFilesByType($reportId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.created_by",
			"csp_reports_files.manual_email",
		], [
			"audio",
			"video",
			"link",
		]);
		//
		$report["incidents"] = $this->getCSPReportIncidents($reportId, [
			"compliance_incident_types.compliance_incident_type_name",
			"compliance_incident_types.description",
			"csp_reports_incidents.sid",
			"csp_reports_incidents.updated_at",
			"csp_reports_incidents.status",
			"csp_reports_incidents.completed_at",
			"csp_reports_incidents.created_by"
		]);
		//
		if ($report["incidents"]) {
			//
			// $report["incidentsDetail"] = [];
			$report["severity_status"] = $this->getSeverityLevels();
			//
			foreach ($report["incidents"] as $iKey => $incident) {
				//
				// $incidentsDetail = $this->getCSPIncidentByIdForDownload($reportId, $incident['sid']);
				$incidentsIssues = $this->getCSPIncidentIssuesByIdForDownload($reportId, $incident['sid']);
				$report["incidents"][$iKey]['issues'] = $incidentsIssues;
				// $report["incidentsDetail"][] = $incidentsDetail;
			}
		}
		//
		$report["question_answers"] = $this->getCSPReportQuestionAnswers($reportId);
		//
		$report["emails"] = $this->getComplianceEmailsForDownload($reportId, 0, 0);
		//
		$report["fileToDownload"] = $this->getComplianceFilesToDownload($reportId, 0, 0);
		//
		return $report;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPIncidentByIdForDownload(int $reportId, int $incidentId, $onlyIncident = false)
	{
		$report = $this
			->db
			->select("csp_reports_incidents.*")
			->select("compliance_incident_types.id as csp_incident_original_id")
			->select("compliance_incident_types.compliance_incident_type_name")
			->select("compliance_incident_types.description")
			->select("users.access_level")
			->select("users.access_level_plus")
			->select("users.pay_plan_flag")
			->select("users.job_title")
			->select("users.is_executive_admin")
			->select("users.email")
			->select("users.PhoneNumber")
			->select($this->userFields)
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
				"inner"
			)
			->join(
				"users",
				"users.sid = csp_reports_incidents.last_modified_by",
				"left"
			)
			->where("csp_reports_incidents.sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		// get the list of items available to the incident
		$report["incidentItemsSelected"] = $this->getCSPAttachedItems($incidentId);
		$report["severity_status"] = $this->getSeverityLevels();
		//
		if ($report["incidentItemsSelected"]) {
			//
			foreach ($report["incidentItemsSelected"] as $item) {
				$itemDetail = $this->getCSPIncidentItemByIdForDownload($reportId, $incidentId, $item['sid']);
				$report["itemDetail"][] = $itemDetail;
			}
		}
		//
		$report["internal_employees"] = $this
			->getCSPIncidentInternalEmployeesById($reportId, $incidentId, 0, [
				"csp_reports_employees.sid",
				"csp_reports_employees.employee_sid",
				"csp_reports_employees.created_by",
				"csp_reports_employees.created_at"
			]);
		//
		$report["external_employees"] = $this
			->getCSPIncidentExternalEmployeesById($reportId, $incidentId, 0, [
				"sid",
				"external_name",
				"external_email",
				"created_by",
				"created_at"
			]);
		//
		$report["notes"] = $this->getCSPReportNotesByIdAndType($reportId, $incidentId, 'employee', [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.created_by",
			"csp_reports_notes.created_at",
		]);
		//
		$report["documents"] = $this->getCSPIncidentFilesByType(
			$reportId,
			$incidentId,
			0,
			[
				$this->userFields,
				"csp_reports_files.file_value",
				"csp_reports_files.sid",
				"csp_reports_files.title",
				"csp_reports_files.s3_file_value",
				"csp_reports_files.file_type",
				"csp_reports_files.created_at",
				"csp_reports_files.created_by",
				"csp_reports_files.manual_email",
			],
			[
				"document",
				"file",
				"image",
			]
		);
		//
		$report["audios"] = $this->getCSPIncidentFilesByType($reportId, $incidentId, 0, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.created_by",
			"csp_reports_files.manual_email",
		], [
			"audio",
			"video",
			"link",
		]);
		//
		$report["question_answers"] = $this->getCSPQuestionAnswers($incidentId);
		//
		$report["emails"] = $this->getComplianceEmailsForDownload($reportId, $incidentId, 0);
		//
		if ($onlyIncident) {
			$report["fileToDownload"] = $this->getComplianceFilesToDownload($reportId, $incidentId, 0);
		}
		//
		return $report;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPIncidentIssuesByIdForDownload(int $reportId, int $incidentId, $onlyIncident = false)
	{
		// get the list of items available to the incident
		$report["incidentItemsSelected"] = $this->getCSPAttachedItems($incidentId);
		$issues = [];
		//
		if ($report["incidentItemsSelected"]) {
			//
			foreach ($report["incidentItemsSelected"] as $item) {
				$issues[] = $this->getCSPIncidentItemByIdForDownload($reportId, $incidentId, $item['sid']);
			}
		}
		//
		return $issues;
	}

	public function getComplianceFilesToDownload($reportId, $incidentId, $itemId)
	{
		$this->db->select('
			title,
		   	s3_file_value,
		   	file_type
		');
		$this->db->where('csp_reports_sid', $reportId);
		//
		$this->db->where('csp_incident_type_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $itemId);
		$this->db->where('file_type <>', 'link');
		//
		$records_obj = $this->db->get('csp_reports_files');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$link = '';
			foreach ($records_arr as $ikey => $item) {
				//
				if ($item['file_type'] == 'document' || $item['file_type'] == 'image') {
					if ($item["file_type"] === "image") {
						$link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
					} elseif ($item["file_type"] === "document") {
						if (preg_match("/doc|docx|xls|xlsx|ppt|pptx/i", $item["s3_file_value"])) {
							$link = 'https://view.officeapps.live.com/op/embed.aspx?src=' . AWS_S3_BUCKET_URL . $item["s3_file_value"];
						} else {
							$link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
						}
					}
					//
				} else {
					$link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
				}
				$return_data[$ikey]['file_name'] = str_replace(' ', '_', $item["title"]) . '_' . $item["s3_file_value"];
				$return_data[$ikey]['link'] = $link;
			}

		}
		//
		return $return_data;
	}

	/**
	 * Get compliance report notes	
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param string $type
	 * @param array $columns
	 * @return array
	 */
	public function getCSPReportNotesByIdAndType(int $reportId, int $incidentId, string $type = 'all', array $columns)
	{
		$this->db->select($columns, false);
		$this->db->where("csp_reports_sid", $reportId);
		$this->db->where("csp_incident_type_sid", $incidentId);
		if ($type != 'all') {
			$this->db->where("note_type", $type);
		}
		$this->db->join(
			"users",
			"users.sid = csp_reports_notes.created_by",
			"left"
		);
		$this->db->order_by("csp_reports_notes.sid", "DESC");
		$notes = $this->db->get('csp_reports_notes')->result_array();
		return $notes;
	}

	public function getComplianceEmailsForDownload($reportId, $incidentId = 0, $itemId = 0)
	{
		$this->db->select('*');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_incident_type_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $itemId);
		$this->db->order_by('send_date', 'desc');
		$records_obj = $this->db->get('csp_reports_emails');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		//
		$incident_emails = array();
		//
		if (!empty($records_arr)) {
			foreach ($records_arr as $email) {
				if ($email['sender_sid'] == 0) {
					$split_email = explode('@', $email['manual_email']);
					$userId = $split_email[0];
				} else {
					$userId = $email['sender_sid'];
				}
				//
				if (!array_key_exists($userId, $incident_emails)) {
					//
					$userName = '';
					//
					if ($email["manual_email"] && $email['sender_sid'] == 0) {
						$split_email = explode('@', $email['manual_email']);
						$userType = $this->checkUserHasPermissionToComplianceSafetyReport($email['manual_email'], 'email', $reportId, $incidentId);
						$userName = $split_email[0] . ' (' . $userType . ')';
					} else {
						$employeeInfo = $this->get_employee_info_by_id($userId);
						$userType = $this->checkUserHasPermissionToComplianceSafetyReport($userId, 'employee', $reportId, $incidentId);
						//
						$userName = $employeeInfo['first_name'] . ' ' . $employeeInfo['last_name'] . ' (' . $userType . ')';
					}

					//	
					$incident_emails[$userId]['userName'] = $userName;
					$incident_emails[$userId]['userId'] = $userId;
				}
				//
				$attachments = $this->getEmailAttachmentIds($email['sid']);
				$email['attachments'] = $attachments;
				//
				$email['email_type'] = 'Sent';
				$incident_emails[$userId]['user_emails'][] = $email;
				//
			}
		}
		//
		return $incident_emails;
	}

	public function checkUserHasPermissionToComplianceSafetyReport($employeeId, $type, $reportId, $incidentId)
	{
		if ($type == "email") {
			$this->db->where('external_email', $employeeId);
		} else {
			$this->db->where('employee_sid', $employeeId);
		}
		//
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_report_incident_sid', $incidentId);
		$this->db->where('status', 1);
		$count = $this->db->count_all_results('csp_reports_employees');
		if ($count > 0) {
			if ($type == "email") {
				return "External Employee";
			} else {
				return "Internal Employee";
			}
		} else {
			if ($type == "email") {
				return "OutSider";
			} else {
				return "Company Employee";
			}
		}
	}

	public function getEmailAttachmentIds($emailId)
	{
		$this->db->select('
			csp_reports_files.title,
		   	csp_reports_files.s3_file_value,
		   	csp_reports_files.file_type
		');
		$this->db->where('csp_reports_email_attachments.csp_reports_email_sid', $emailId);
		$this->db->join('csp_reports_files', 'csp_reports_files.sid = csp_reports_email_attachments.csp_reports_file_sid', 'left');
		$records_obj = $this->db->get('csp_reports_email_attachments');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		$return_data = array();

		if (!empty($records_arr)) {
			$link = '';
			foreach ($records_arr as $ikey => $item) {
				//
				if ($item['file_type'] == 'document' || $item['file_type'] == 'image') {
					if ($item["file_type"] === "image") {
						$link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
					} elseif ($item["file_type"] === "document") {
						if (preg_match("/doc|docx|xls|xlsx|ppt|pptx/i", $item["s3_file_value"])) {
							$link = 'https://view.officeapps.live.com/op/embed.aspx?src=' . AWS_S3_BUCKET_URL . $item["s3_file_value"];
						} else {
							$link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
						}
					}
					//
				} else {
					if ($item['file_type'] == 'link') {
						$link = $item['file_value'];
					} else {
						$link = AWS_S3_BUCKET_URL . $item["s3_file_value"];
					}
				}
				$return_data[$ikey]['title'] = $item["title"];
				$return_data[$ikey]['link'] = $link;
			}

		}

		return $return_data;
	}

	public function getCSPItems(int $complianceIncidentId, $parse = true)
	{
		$records = $this
			->db
			->select("sid, description, severity_level_sid, title")
			->where("compliance_report_incident_sid", $complianceIncidentId)
			->order_by("sid", "DESC")
			->get("compliance_report_incident_types")
			->result_array();
		//
		if (!$parse) {
			return $records;
		}
		//
		$tmp = [];
		foreach ($records as $record) {
			//
			$record["description"] = convertCSPTags(
				$record["description"]
			);
			//
			$tmp[] = $record;
		}
		//
		return $tmp;
	}

	public function getCSPAttachedItems(int $incidentId)
	{
		$records = $this->db
			->select([
				"csp_reports_incidents_items.sid",
				"csp_reports_incidents_items.answers_json",
				"csp_reports_incidents_items.severity_level_sid",
				"compliance_report_incident_types.title",
				"compliance_report_incident_types.description",
				"csp_reports_incidents_items.compliance_report_incident_types_sid",
			])
			->where([
				'csp_reports_incidents_items.csp_reports_incidents_sid' => $incidentId,
				'csp_reports_incidents_items.status' => 1,
			])
			->join(
				"compliance_report_incident_types",
				"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
				"inner"
			)
			->get('csp_reports_incidents_items')
			->result_array();
		//
		if (!$records) {
			return $records;
		}
		//
		$tmp = [];
		//
		foreach ($records as $rc) {
			$tmp[$rc["compliance_report_incident_types_sid"]] = $rc;
		}
		//
		return $tmp;
	}

	public function markAllItemsOfIncidentsInactive(
		$reportId,
		$incidentId,
		$loggedInEmployeeId,
		$post
	) {
		if ($post["ids"]) {
			$this->db->where_not_in(
				"compliance_report_incident_types_sid",
				$post["ids"]
			);
		}
		return $this->db
			->where('csp_reports_incidents_sid', $incidentId)
			->update('csp_reports_incidents_items', [
				"status" => 0,
				"updated_at" => getSystemDate()
			]);
	}

	public function attachItemToIncident(
		$reportId,
		$incidentId,
		$loggedInEmployeeId,
		$post
	) {
		// check if already exists
		$exists = $this->db
			->where([
				'csp_reports_incidents_sid' => $incidentId,
				'compliance_report_incident_types_sid' => $post['id']
			])
			->count_all_results('csp_reports_incidents_items');

		$todayDateTime = getSystemDate();
		// add new item
		$data = [
			'severity_level_sid' => $post["level"],
			'status' => $post["status"] == "active" ? 1 : 0,
			'answers_json' => json_encode([
				"dynamicInput" => $post["dynamicInput"],
				"dynamicCheckbox" => $post["dynamicCheckbox"],
			]),
			'updated_at' => $todayDateTime,
		];
		//
		if ($exists > 0) {
			return $this->db
				->where([
					'csp_reports_incidents_sid' => $incidentId,
					'compliance_report_incident_types_sid' => $post['id']
				])
				->update('csp_reports_incidents_items', $data);
		}
		//
		$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
		// add new item
		$data = [
			'csp_reports_incidents_sid' => $incidentId,
			'compliance_report_incident_types_sid' => $post['id'],
			'created_by' => $loggedInEmployeeName,
			'created_at' => $todayDateTime,
		];

		if ($post["level"]) {
			$data["severity_level_sid"] = $post["level"];
		}

		if ($post["status"]) {
			$data["status"] = $post["status"] == "active" ? 1 : 0;
		}

		if ($post["dynamicInput"] || $post["dynamicCheckbox"]) {
			$data["answers_json"] = json_encode([
				"dynamicInput" => $post["dynamicInput"],
				"dynamicCheckbox" => $post["dynamicCheckbox"],
			]);
		}

		$this->db->insert('csp_reports_incidents_items', $data);
		$itemId = $this->db->insert_id();
		//
		$dataToUpdate = [
			"last_modified_by" => $loggedInEmployeeName
		];
		//
		$this->compliance_report_model->addManualUserEmail(
			$incidentId,
			$dataToUpdate,
			'csp_reports_incidents'
		);
		//
		return $itemId;
	}

	public function updateAttachedItem(
		$reportId,
		$incidentId,
		$loggedInEmployeeId,
		$post
	) {

		$todayDateTime = getSystemDate();
		// add new item
		$data = [
			'answers_json' => json_encode([
				"dynamicInput" => $post["dynamicInput"],
				"dynamicCheckbox" => $post["dynamicCheckbox"],
			]),
			'updated_at' => $todayDateTime,
		];
		//
		$returnData = $this->db
			->where('sid', $post["id"])
			->update('csp_reports_incidents_items', $data);
		//
		if ($loggedInEmployeeId != 0) {
			//
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			$this->compliance_report_model->addManualUserEmail(
				$post["id"],
				$dataToUpdate,
				'csp_reports_incidents_items'
			);
			// Save log on update report
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => 0,
					'type' => 'incidents',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'update',
						'type' => 'items',
						'item_id' => $post["id"],
						'dateTime' => $todayDateTime,
						'fields' => [
							'dynamicInput' => $post["dynamicInput"],
							'dynamicCheckbox' => $post['dynamicCheckbox'],
							'status' => $post['status'],
							'level' => $post['level'],
						],
					]
				]
			);
		}
		//
		return $returnData;
	}

	public function getReportTitleById($reportId)
	{
		$this->db->select('title');
		$this->db->where('sid', $reportId);
		$record_obj = $this->db->get('csp_reports');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		//
		return $record_arr['title'];
	}

	public function checkEmployeeHaveReportAccess($userId, $reportId, $incidentId, $issueId = 0)
	{
		//
		if (
			$this->db
				->where("sid", $reportId)
				->where("created_by", $userId)
				->count_all_results('csp_reports')
		) {
			return 'access_report';
		} else if ($this->checkComplianceSafetyReportAssigned($userId, $reportId, 0, 0)) {
			return 'access_report';
		} else if ($this->checkComplianceSafetyReportAssigned($userId, $reportId, $incidentId, 0)) {
			return 'access_incident';
		} else if ($this->checkComplianceSafetyReportAssigned($userId, $reportId, $incidentId, $issueId)) {
			return 'access_issue';
		} else {
			return 'not_have_access';
		}
	}

	public function checkComplianceSafetyReportAssigned($userId, $reportId, $incidentId, $issueId)
	{
		$this->db->select('sid');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_report_incident_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $issueId);
		
		$this->db->where('status', 1);
		//
		if (filter_var($userId, FILTER_VALIDATE_EMAIL)) {
			$this->db->where('external_email', $userId);
		} else {
			$this->db->where('employee_sid', $userId);
		}
		//
		$record_obj = $this->db->get('csp_reports_employees');
		$record_arr = $record_obj->row_array();
		$record_obj->free_result();
		//
		$isAssign = false;
		//
		if (!empty($record_arr)) {
			$isAssign = true;
		}
		//
		return $isAssign;
	}

	/**
	 * Add files to report
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param string $loggedInEmployeeId
	 * @param string $fileName
	 * @param string $originalFileName
	 * @param string $fileType
	 * @return array
	 */
	public function addFilesToIncidentItem(
		int $reportId,
		int $incidentId,
		int $itemId,
		string $loggedInEmployeeId,
		string $fileName,
		string $originalFileName,
		string $fileType,
		string $title
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$fileData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"file_type" => $fileType,
			"file_value" => $originalFileName,
			"s3_file_value" => $fileName,
			"title" => $title,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
			"csp_reports_incidents_items_sid" => $itemId
		];
		//
		if (filter_var($loggedInEmployeeId, FILTER_VALIDATE_EMAIL)) {
			$fileData['manual_email'] = $loggedInEmployeeId;
			$fileData['created_by'] = 0;
		} else {
			$fileData['created_by'] = $loggedInEmployeeId;
		}
		//
		$this->db->insert("csp_reports_files", $fileData);
		//
		$fileId = $this->db->insert_id();
		//
		if ($loggedInEmployeeId != 0) {
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			$this->compliance_report_model->addManualUserEmail(
				$itemId,
				$dataToUpdate,
				'csp_reports_incidents_items'
			);
			// Save log on add note
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => $itemId,
					'type' => 'files',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => $fileType,
						'title' => $title,
						'fileId' => $fileId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $fileId;
	}

	/**
	 * Add files to report
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param string $loggedInEmployeeId
	 * @param string $link
	 * @param string $linkType
	 * @param string $title
	 * @return array
	 */
	public function addFilesLinkToIncidentItem(
		int $reportId,
		int $incidentId,
		int $itemId,
		string $loggedInEmployeeId,
		string $link,
		string $linkType,
		string $title
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$fileData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"file_type" => $linkType,
			"file_value" => $link,
			"s3_file_value" => $link,
			"title" => $title,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
			"csp_reports_incidents_items_sid" => $itemId
		];
		//
		if (filter_var($loggedInEmployeeId, FILTER_VALIDATE_EMAIL)) {
			$fileData['manual_email'] = $loggedInEmployeeId;
			$fileData['created_by'] = 0;
		} else {
			$fileData['created_by'] = $loggedInEmployeeId;
		}
		//
		$this->db->insert("csp_reports_files", $fileData);
		//
		$linkId = $this->db->insert_id();
		//
		if ($loggedInEmployeeId != 0) {
			//
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			$this->compliance_report_model->addManualUserEmail(
				$itemId,
				$dataToUpdate,
				'csp_reports_incidents_items'
			);
			// Save log on add note
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => $itemId,
					'type' => 'files',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => 'link',
						'title' => $title,
						'fileId' => $linkId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $linkId;

	}

	public function getCSPItemAttachments($reportId, $incidentId, $itemId)
	{
		$this->db->select("file_value,sid,title,s3_file_value,file_type,created_at,created_by,manual_email");
		$this->db->where("csp_reports_sid", $reportId);
		$this->db->where("csp_incident_type_sid", $incidentId);
		$this->db->where("csp_reports_incidents_items_sid", $itemId);
		return $this->db->get("csp_reports_files")->result_array();
	}

	public function getCSPIncidentItemInfo($reportId, $incidentId, $itemId)
	{
		$itemData = [];
		//
		$itemData = $this
			->db
			->select([
				"csp_reports_incidents_items.sid",
				"csp_reports_incidents_items.completion_status",
				"csp_reports_incidents_items.completion_date",
				"csp_reports_incidents_items.completed_by",
				"csp_reports_incidents_items.created_at",
				"csp_reports_incidents_items.updated_at",
				"csp_reports_incidents_items.last_modified_by",
				"csp_reports_incidents_items.answers_json",
				"csp_reports_incidents_items.question_answer_json",
				//
				"compliance_report_incident_types.title",
				"compliance_report_incident_types.description",
				// severity level
				"compliance_severity_levels.level",
				"compliance_severity_levels.txt_color",
				"compliance_severity_levels.bg_color",
				//
			])
			->select($this->userFields)
			->join(
				"compliance_report_incident_types",
				"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
				"inner"
			)
			->join(
				"compliance_severity_levels",
				"compliance_severity_levels.sid = csp_reports_incidents_items.severity_level_sid",
				"left"
			)
			->join(
				"users",
				"users.sid = csp_reports_incidents_items.completed_by",
				"left"
			)
			->where("csp_reports_incidents_items.sid", $itemId)
			->where("csp_reports_incidents_items.csp_reports_incidents_sid", $incidentId)
			->get("csp_reports_incidents_items")
			->row_array();
		//
		if (!$itemData) {
			return [];
		}
		// get the incident data
		$itemData["incident_name"] = $this
			->db
			->select("compliance_incident_types.compliance_incident_type_name")
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
				"inner"
			)
			->where("csp_reports_incidents.sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array()["compliance_incident_type_name"];

		// get the report data
		$itemData["report"] = $this
			->db
			->select("title")
			->where("csp_reports.sid", $reportId)
			->get("csp_reports")
			->row_array()["title"];
		//
		$itemData["internal_employees"] = [];
		//
		$internalEmployees = $this
			->getCSPIncidentInternalEmployeesById($reportId, $incidentId, $itemId, [
				"csp_reports_employees.sid",
				"csp_reports_employees.employee_sid",
				"csp_reports_employees.is_manager"
			]);
		//
		if ($internalEmployees) {
			foreach ($internalEmployees as $employeeInfo) {
				if ($employeeInfo['is_manager'] == 0) {
					$itemData["internal_employees"][] = $employeeInfo;
				}
			}
		}
		//
		$itemData["external_employees"] = $this
			->getCSPIncidentExternalEmployeesById($reportId, $incidentId, $itemId, [
				"sid",
				"external_name",
				"external_email",
			]);
		//	
		$departmentsTeams = $this->getCSPIncidentDepartmentsAndTeamsById($itemId);
		//
		$itemData["allowed_departments"] = isset($departmentsTeams['allowed_departments']) && !empty($departmentsTeams['allowed_departments']) ? explode(',', $departmentsTeams['allowed_departments']) : [];
		$itemData["allowed_teams"] = isset($departmentsTeams['allowed_teams']) && !empty($departmentsTeams['allowed_teams']) ? explode(',', $departmentsTeams['allowed_teams']) : [];
		//
		$itemData["notes"] = $this->getCSPIncidentNotesById($reportId, $incidentId, $itemId, [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.updated_at",
			"csp_reports_notes.created_by",
			"csp_reports_notes.manual_email"
		]);
		//
		$itemData["documents"] = $this->getCSPIncidentFilesByType(
			$reportId,
			$incidentId,
			$itemId,
			[
				$this->userFields,
				"csp_reports_files.file_value",
				"csp_reports_files.sid",
				"csp_reports_files.title",
				"csp_reports_files.s3_file_value",
				"csp_reports_files.file_type",
				"csp_reports_files.created_at",
				"csp_reports_files.manual_email"
			],
			[
				"document",
				"file",
				"image",
			]
		);
		//
		$itemData["audios"] = $this->getCSPIncidentFilesByType($reportId, $incidentId, $itemId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",


			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.manual_email"
		], [
			"audio",
			"video",
			"link",
		]);
		//
		$itemData["libraryItems"] = $this->getComplianceReportFiles($reportId, $incidentId, $itemId);
		//
		// _e($itemData,true,true);
		return $itemData;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function editIncidentItem(
		int $reportId,
		int $incidentId,
		int $itemId,
		int $loggedInEmployeeId,
		array $post
	) {
		//
		$updateItem = [];
		//
		if ($post['item_completion_date']) {
			$updateItem['completion_date'] = formatDateToDB(
				$post['item_completion_date'],
				"m/d/Y",
				DB_DATE
			);
		}
		if ($post["report_status"]) {
			$updateItem['completion_status'] = $post['report_status'];
		}
		if ($post["jsIncidentSeverityLevel"]) {
			$updateItem['severity_level_sid'] = $post['jsIncidentSeverityLevel'];
		}
		//
		$updateItem['last_modified_by'] = $loggedInEmployeeId;
		$updateItem['updated_at'] = getSystemDate();
		//
		if ($post["itemInput"] || $post["itemCheckbox"]) {
			//
			$updateItem['answers_json'] = json_encode([
				"dynamicInput" => explode(',', $post["itemInput"]),
				"dynamicCheckbox" => explode(',', $post["itemCheckbox"]),
			]);
		}
		//
		$this->db
			->where('sid', $itemId)
			->update('csp_reports_incidents_items', $updateItem);
		//
		$this->updateCSPEmployees(
			$post,
			$reportId,
			$incidentId,
			$itemId,
			$loggedInEmployeeId
		);
		//
		if ($loggedInEmployeeId != 0) {
			$loggedInEmployeeName = getUserNameBySID($loggedInEmployeeId);
			//
			$dataToUpdate = [
				"last_modified_by" => $loggedInEmployeeName
			];
			//
			$this->compliance_report_model->addManualUserEmail(
				$itemId,
				$dataToUpdate,
				'csp_reports_incidents_items'
			);
			// Save log on update incident item
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => $itemId,
					'type' => 'incident_item',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'update incident item',
						'dateTime' => getSystemDate(),
						'internalEmployees' => $post['report_employees'],
						'externalEmployees' => $post['external_employees_emails']
					]
				]
			);
		}
		//
		return true;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function addNotesToIncidentItem(
		int $reportId,
		int $incidentId,
		int $itemId,
		int $loggedInEmployeeId,
		array $post
	) {
		//
		$todayDateTime = getSystemDate();
		// lets first add the report
		$noteData = [
			"csp_reports_sid" => $reportId,
			"csp_incident_type_sid" => $incidentId,
			"csp_reports_incidents_items_sid" => $itemId,
			"note_type" => $post["type"],
			"notes" => $post["content"],
			"created_by" => $loggedInEmployeeId,
			"created_at" => $todayDateTime,
			"updated_at" => $todayDateTime,
		];
		//
		$this->db->insert("csp_reports_notes", $noteData);
		$noteId = $this->db->insert_id();
		//
		if ($loggedInEmployeeId != 0 && $post["type"] == 'employee') {
			// Save log on add note
			$this->saveComplianceSafetyReportLog(
				[
					'reportId' => $reportId,
					'incidentId' => $incidentId,
					'incidentItemId' => $itemId,
					'type' => 'notes',
					'userType' => 'employee',
					'userId' => $loggedInEmployeeId,
					'jsonData' => [
						'action' => 'create',
						'type' => 'employee_note',
						'noteId' => $noteId,
						'dateTime' => $todayDateTime
					]
				]
			);
		}
		//
		return $noteId;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $rowId
	 * @param string $emailId
	 * @param string $table
	 */
	public function addExternalUser(
		int $rowId,
		string $emailId,
		string $table
	) {
		//
		$this->db->where('sid', $rowId);
		$this->db->update($table, ['manual_email' => $emailId]);
	}

	/**
	 * Check if the field is a question field
	 *
	 * @param string $field
	 * @return bool
	 */
	private function isQuestionField($field): bool
	{
		if (
			preg_match("/^text_[0-9]/", $field)
			|| preg_match("/^radio_[0-9]/", $field)
			|| preg_match("/^list_[0-9]/", $field)
			|| preg_match("/^multi-list_[0-9]/", $field)
			|| preg_match("/^date_[0-9]/", $field)
			|| preg_match("/^time_[0-9]/", $field)
		) {
			return true;
		}
		//
		return false;
	}

	public function saveComplianceSafetyReportLog($data)
	{
		//
		$dataToInsert = [];
		//
		$dataToInsert['csp_reports_sid'] = $data['reportId'];
		$dataToInsert['csp_reports_incident_sid'] = $data['incidentId'];
		$dataToInsert['csp_reports_incident_item_sid'] = $data['incidentItemId'];
		if ($data['userType'] == 'employee') {
			$dataToInsert['employee_sid'] = $data['userId'];
		} else {
			$dataToInsert['employee_email'] = $data['userId'];
		}
		$dataToInsert['module_type'] = $data['type'];
		$dataToInsert['created_at'] = getSystemDate();
		$dataToInsert['action_json'] = json_encode($data['jsonData']);
		//
		$this->db->insert('csp_logs', $dataToInsert);
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getCSPIncidentItemByIdForDownload(int $reportId, int $incidentId, $itemId, $onlyItem = false)
	{
		$data = [];
		//
		$report = $this
			->db
			->select('title')
			->where("sid", $reportId)
			->get("csp_reports")
			->row_array();
		//
		$data['report_title'] = $report['title'];
		//	
		$incident = $this
			->db
			->select("compliance_incident_types.compliance_incident_type_name")
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
				"inner"
			)
			->where("csp_reports_incidents.sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array();
		//
		$data['incident_title'] = $incident['compliance_incident_type_name'];
		//
		$item = $this
			->db
			->select("
				csp_reports_incidents_items.created_by, 
				csp_reports_incidents_items.created_at, 
				csp_reports_incidents_items.completion_date,
				csp_reports_incidents_items.completion_status,
				compliance_report_incident_types.description,
				compliance_report_incident_types.title,
				csp_reports_incidents_items.severity_level_sid,
				csp_reports_incidents_items.answers_json,
				csp_reports_incidents_items.question_answer_json

			")
			->join(
				"compliance_report_incident_types",
				"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
				"inner"
			)
			->where("csp_reports_incidents_items.sid", $itemId)
			->get("csp_reports_incidents_items")
			->row_array();
		//
		if (!$item) {
			return [];
		}
		//
		$data["created_by"] = getEmployeeOnlyNameBySID($item['created_by']);
		$data["created_date"] = $item['created_at'];
		$data["completion_date"] = $item['completion_date'];
		$data["completion_status"] = $item['completion_status'];
		$data["issue_title"] = $item['title'];
		$data["issue_description"] = $item['description'];
		$data["severity_level_sid"] = $item['severity_level_sid'];
		$data["answers_json"] = $item['answers_json'];
		$data["question_answer_json"] = $item['question_answer_json'];
		//
		// get the list of items available to the incident
		$data["incidentItemsSelected"] = $this->getCSPAttachedItemByItemSid($itemId);
		//
		$data["internal_employees"] = $this
			->getCSPIncidentInternalEmployeesById($reportId, $incidentId, $itemId, [
				"csp_reports_employees.sid",
				"csp_reports_employees.employee_sid",
				"csp_reports_employees.created_by",
				"csp_reports_employees.created_at"
			]);
		//
		$data["external_employees"] = $this
			->getCSPIncidentExternalEmployeesById($reportId, $incidentId, $itemId, [
				"sid",
				"external_name",
				"external_email",
				"created_by",
				"created_at"
			]);
		//
		$data["notes"] = $this->getCSPReportNotesByIdAndType($reportId, $incidentId, 'employee', [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.created_by",
			"csp_reports_notes.created_at",
		]);
		//
		$data["documents"] = $this->getCSPIncidentFilesByType(
			$reportId,
			$incidentId,
			$itemId,
			[
				$this->userFields,
				"csp_reports_files.file_value",
				"csp_reports_files.sid",
				"csp_reports_files.title",
				"csp_reports_files.s3_file_value",
				"csp_reports_files.file_type",
				"csp_reports_files.created_at",
				"csp_reports_files.created_by",
				"csp_reports_files.manual_email",
			],
			[
				"document",
				"file",
				"image",
			]
		);
		//
		$data["audios"] = $this->getCSPIncidentFilesByType($reportId, $incidentId, $itemId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.created_by",
			"csp_reports_files.manual_email",
		], [
			"audio",
			"video",
			"link",
		]);
		//
		$data["emails"] = $this->getComplianceEmailsForDownload($reportId, $incidentId, $itemId);
		//
		if ($onlyItem) {
			$data["fileToDownload"] = $this->getComplianceFilesToDownload($reportId, $incidentId, $itemId);
		}
		//
		return $data;
	}

	public function getCSPAttachedItemByItemSid(int $itemId)
	{
		$record = $this->db
			->select([
				"csp_reports_incidents_items.sid",
				"csp_reports_incidents_items.answers_json",
				"csp_reports_incidents_items.severity_level_sid",
				"compliance_report_incident_types.description",
				"csp_reports_incidents_items.compliance_report_incident_types_sid",
			])
			->where([
				'csp_reports_incidents_items.sid' => $itemId,
			])
			->join(
				"compliance_report_incident_types",
				"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
				"inner"
			)
			->get('csp_reports_incidents_items')
			->row_array();
		//
		if (!$record) {
			return $record;
		}
		//
		$tmp = [];
		//
		foreach ($record as $rc) {
			$tmp[$rc["compliance_report_incident_types_sid"]] = $rc;
		}
		//
		return $tmp;
	}

	function addManualUserEmail($sid, $data_to_update, $table)
	{
		$this->db->where('sid', $sid);
		$this->db->update($table, $data_to_update);
	}

	function checkAndAddEmailInAddEmployee($reportId, $incidentId, $itemId, $emailId)
	{
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_report_incident_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $itemId);
		$this->db->where('created_by', 0);
		$this->db->group_start();
		$this->db->where('manual_email IS NULL', null, false);
		$this->db->or_where('manual_email', '');
		$this->db->group_end();
		$this->db->update('csp_reports_employees', ['manual_email' => $emailId]);
	}


	public function getCSPAttachedItemById(int $itemId)
	{
		$records = $this->db
			->select([
				"csp_reports_incidents_items.answers_json",
				"csp_reports_incidents_items.severity_level_sid",
				"compliance_report_incident_types.description",
				"csp_reports_incidents_items.compliance_report_incident_types_sid",
			])
			->where([
				'csp_reports_incidents_items.sid' => $itemId,
				'csp_reports_incidents_items.status' => 1,
			])
			->join(
				"compliance_report_incident_types",
				"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
				"inner"
			)
			->get('csp_reports_incidents_items')
			->row_array();
		//
		return $records;
	}

	public function getAllItemsWithIncidentsCPA(int $companyId, array $filter, bool $isMainCPA = true): array
	{
		$reportIds = [];
		if ($isMainCPA) {
			//
			if ((array_key_exists("departments", $filter) && $filter["departments"] != "") || (array_key_exists("teams", $filter) && $filter["teams"] != "")) {
				$this->db->select('csp_reports_employees.csp_reports_sid');
				$this->db->join(
					"csp_reports",
					"csp_reports.sid = csp_reports_employees.csp_reports_sid",
					"inner"
				);
				$this->db->where('csp_reports.company_sid', $companyId);
				$this->db->where('csp_reports_employees.status', 1);
				$this->db->group_start();
				if (array_key_exists("departments", $filter) && $filter["departments"] != "") {
					foreach ($filter["departments"] as $department) {
						$this->db->or_where('FIND_IN_SET("' . ($department) . '", allowed_departments) > 0', NULL, FALSE);
					}
				}
				// For teams
				if (array_key_exists("teams", $filter) && $filter["teams"] != "") {
					foreach ($filter["teams"] as $team) {
						$this->db->or_where('FIND_IN_SET("' . ($team) . '", allowed_teams) > 0', NULL, FALSE);
					}
				}
				$this->db->group_end();
				$records_obj = $this->db->get('csp_reports_employees');
				$records_arr = $records_obj->result_array();
				$records_obj->free_result();
				//
				$reportIds = array_column($records_arr, 'csp_reports_sid');
			}
		}

		$this->db->select([
			// Item columns
			"csp_reports_incidents_items.sid",
			"csp_reports_incidents_items.answers_json",
			"csp_reports_incidents_items.completion_status",
			"csp_reports_incidents_items.completion_date",
			"csp_reports_incidents_items.completed_by",
			"csp_reports_incidents_items.csp_reports_incidents_sid",
			// Add severity columns
			"compliance_severity_levels.level",
			"compliance_severity_levels.bg_color",
			"compliance_severity_levels.txt_color",
			// get the item description
			"compliance_report_incident_types.title as item_title",
			"compliance_report_incident_types.description",
			// get the incident description
			"compliance_incident_types.compliance_incident_type_name",
			// get report title
			"csp_reports.title",
			"csp_reports.report_date",
			"csp_reports_incidents.csp_reports_sid",
		]);
		//
		$this->db->select($this->userFields);
		// join with severity to get the colors
		$this->db->join(
			"compliance_severity_levels",
			"compliance_severity_levels.sid = csp_reports_incidents_items.severity_level_sid",
			"inner"
		);
		// join with the report incident types to get the description
		$this->db->join(
			"compliance_report_incident_types",
			"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
			"inner"
		);
		// join with the incident
		$this->db->join(
			"csp_reports_incidents",
			"csp_reports_incidents.sid = csp_reports_incidents_items.csp_reports_incidents_sid",
			"inner"
		);
		// join with the main incident
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		// join with the report
		$this->db->join(
			"csp_reports",
			"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
			"inner"
		);
		// join with the completed by
		$this->db->join(
			"users",
			"users.sid = csp_reports_incidents_items.completed_by",
			"left"
		);
		$this->db->where('csp_reports_incidents_items.status', 1);
		$this->db->where('csp_reports.company_sid', $companyId);
		//
		if ($reportIds) {
			$this->db->where_in("csp_reports.sid", $reportIds);
		}
		// check report date range
		if (array_key_exists("date_range", $filter) && $filter["date_range"] != "") {
			list($start_date, $end_date) = explode(" - ", $_GET['date_range']);
			$between = "report_date between '" . formatDateToDB($start_date, SITE_DATE, DB_DATE) . "' and '" . formatDateToDB($end_date, SITE_DATE, DB_DATE) . "'";
			$this->db->where($between);
		}

		// check for severity status
		if (array_key_exists("severity_level", $filter) && $filter["severity_level"] != "-1") {
			$this->db->where(
				"compliance_severity_levels.sid",
				$filter["severity_level"]
			);
		}
		// check for incident
		if (array_key_exists("incident", $filter) && $filter["incident"] != "-1") {
			$this->db->where(
				"csp_reports_incidents.sid",
				$filter["incident"]
			);
		}
		// check for status
		if (array_key_exists("status", $filter) && $filter["status"] != "-1") {
			$this->db->where(
				"csp_reports_incidents_items.completion_status",
				$filter["status"]
			);
		}
		// check report title
		if (array_key_exists("title", $filter) && $filter["title"] != "") {
			$like = " `csp_reports.title` LIKE '%" . $filter["title"] . "%' ";
			$this->db->where($like);
		}
		// strictly check for the allowed issues
		// if is not ain CPA
		if (!$isMainCPA) {
			if ($this->allowedCSP["tasks"]) {
				$this->db->where_in("csp_reports_incidents_items.sid", $this->allowedCSP["tasks"]);
			}
			//
			if (array_key_exists("departmentIds", $this->allowedCSP) || array_key_exists("teamIds", $this->allowedCSP)) {
				// check for departments
				$this->db->group_start();

				if (array_key_exists("departmentIds", $this->allowedCSP)) {
					$this->db->group_start();
					foreach ($this->allowedCSP["departmentIds"] as $v0) {
						$this->db->where("FIND_IN_SET({$v0}, csp_reports_incidents_items.allowed_departments) >", 0);
					}
					$this->db->group_end();
				}
				if (array_key_exists("teamIds", $this->allowedCSP)) {
					$this->db->or_group_start();
					foreach ($this->allowedCSP["teamIds"] as $v0) {
						$this->db->where("FIND_IN_SET({$v0}, csp_reports_incidents_items.allowed_teams) >", 0);
					}
					$this->db->group_end();
				}
				$this->db->group_end();
			}

		}

		//
		$this->db->order_by("csp_reports_incidents_items.sid", "DESC");
		$records_obj = $this->db->get('csp_reports_incidents_items');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();
		// _e($this->db->last_query(), print: true, true);

		//
		if ($records_arr) {
			$recordHolder = [];
			//
			foreach ($records_arr as $v0) {
				// check if 
				$reportId = $v0["csp_reports_sid"];
				//
				if (!array_key_exists($reportId, $recordHolder)) {
					$recordHolder[$reportId] = [
						"id" => $reportId,
						"title" => $v0["title"],
						"report_date" => $v0["report_date"],
						"issues" => [],
					];
				}

				// get attachments
				$v0["files"] = $this->getAllItemsFiles(
					$reportId,
					$v0["csp_reports_incidents_sid"],
					$v0["sid"]
				);

				//
				$recordHolder[$reportId]["issues"][] = $v0;
			}
			//
			$records_arr = array_values($recordHolder);
		}

		return $records_arr;
	}

	public function getAllIncidentsWithReports(
		int $companyId,
		bool $isMainCPA = true
	): array {
		$this->db->select([
			// Item columns
			"csp_reports_incidents.sid",
			"csp_reports_incidents.csp_reports_sid",
			// get the incident description
			"compliance_incident_types.compliance_incident_type_name",
			// get report title
			"csp_reports.title",
			"csp_reports.report_date",
		]);
		// join with the report incident types to get the description
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		// join with the report
		$this->db->join(
			"csp_reports",
			"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
			"inner"
		);
		$this->db->where('csp_reports.company_sid', $companyId);
		if (!$isMainCPA) {
			if ($this->allowedCSP["reports"] && $this->allowedCSP["incidents"]) {
				$this->db->group_start();
				$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
				$this->db->or_where_in("csp_reports_incidents.sid", $this->allowedCSP["incidents"]);
				$this->db->group_end();
			} else if ($this->allowedCSP["reports"] && !$this->allowedCSP["incidents"]) {
				$this->db->where_in("csp_reports.sid", $this->allowedCSP["reports"]);
			} else if (!$this->allowedCSP["reports"] && $this->allowedCSP["incidents"]) {
				$this->db->where_in("csp_reports_incidents.sid", $this->allowedCSP["incidents"]);
			}
		}
		$records = $this->db->get('csp_reports_incidents')->result_array();
		if ($records) {
			//
			$reportHolder = [];
			foreach ($records as $record) {
				if (!array_key_exists($record["csp_reports_sid"], $reportHolder)) {
					$reportHolder[$record["csp_reports_sid"]] = [
						"title" => $record["title"],
						"id" => $record["csp_reports_sid"],
						"report_date" => $record["report_date"],
						"incidents" => [],
					];
				}
				//
				$reportHolder[$record["csp_reports_sid"]]["incidents"][] = [
					"id" => $record["sid"],
					"name" => $record["compliance_incident_type_name"],
				];
			}

			return array_values($reportHolder);
		}
		//
		return [];
	}


	public function getAllEmployeeIncidentsWithReports(
		int $companyId,
		int $loggedInEmployeeId
	): array {
		//
		$this->getAllowedCSPIds($loggedInEmployeeId);

		return $this->getAllIncidentsWithReports(
			$companyId,
			false
		);
	}

	public function getAllEmployeeItemsWithIncidentsCPA(
		int $companyId,
		int $loggedInEmployeeId,
		array $filter
	): array {
		//
		$this->getAllowedCSPIds($loggedInEmployeeId);

		return $this->getAllItemsWithIncidentsCPA(
			$companyId,
			$filter,
			false
		);
	}

	public function getAllEmployeeIncidentsWithReportsPublic(
		int $companyId,
		string $emailOrId
	): array {
		//
		$this->getAllowedCSPIdsById($emailOrId);

		return $this->getAllIncidentsWithReports(
			$companyId,
			false
		);
	}

	public function getAllEmployeeItemsWithIncidentsCPAPublic(
		int $companyId,
		string $emailOrId,
		array $filter
	): array {
		//
		$this->getAllowedCSPIdsById($emailOrId);

		return $this->getAllItemsWithIncidentsCPA(
			$companyId,
			$filter,
			false
		);
	}

	public function updateIncidentItemStatus(
		int $reportId,
		int $incidentId,
		int $itemId,
		int $loggedInEmployeeId,
		string $status,
		string $completedAt
	) {
		//
		$updateItem = [];
		//
		if ($status == "completed") {
			$updateItem['completion_date'] = $completedAt;
			$updateItem['completed_by'] = $loggedInEmployeeId;
		}
		$updateItem['completion_status'] = $status;
		$updateItem['last_modified_by'] = $loggedInEmployeeId;
		$updateItem['updated_at'] = getSystemDate();
		$this->db
			->where('sid', $itemId)
			->update('csp_reports_incidents_items', $updateItem);

		$this->saveComplianceSafetyReportLog(
			[
				'reportId' => $reportId,
				'incidentId' => $incidentId,
				'incidentItemId' => $itemId,
				'type' => 'issue_progress',
				'userType' => 'employee',
				'userId' => $loggedInEmployeeId,
				'jsonData' => [
					"status" => $status,
					"completed_at" => $completedAt,
				]
			]
		);
		return true;
	}

	/**
	 * Delete attached file
	 *
	 * @param int $fileId
	 *
	 */
	public function deleteAttachedFile(int $fileId)
	{
		$this->db
			->where("sid", $fileId)
			->delete("csp_reports_files");
	}

	/**
	 * Send email to the issue employees
	 *
	 * @param int $issueId
	 */
	public function sendEmailsForCSPIssue(int $issueId, int $templateId = CSP_ASSIGNED_EMAIL_TEMPLATE_ID)
	{
		// get report
		$report = $this
			->getCSPIssueForEmail(
				$issueId,
				[
					"csp_reports.company_sid",
					"csp_reports.title",
				]
			);
		//
		$companyName = getCompanyColumnById($report["company_sid"], "CompanyName")["CompanyName"];
		// get the company header
		$hf = message_header_footer(
			$report["company_sid"],
			$companyName
		);

		if ($report["external_employees"]) {
			foreach ($report["external_employees"] as $item) {
				if ($item['csp_reports_incidents_items_sid'] != 0) {
					//
					if (!$item["unique_code"]) {
						//
						$code = generateUniqueCode(15);
						// update unique code
						$this
							->db
							->where("sid", $item["sid"])
							->update("csp_reports_employees", [
								"unique_code" => $code
							]);
					} else {
						$code = $item["unique_code"];
					}
					// set replace array
					$ra = [
						"first_name" => $item["external_name"],
						"title" => $report["title"],
						"last_name" => "",
						"email" => $item["external_email"],
						"company_name" => $companyName,
						"csp_public_url" => generateEmailButton(
							"#fd7a2a",
							("csp/single/{$code}"),
							"View Compliance Safety Report"
						),
						"base_url" => base_url(),
					];
					//
					log_and_send_templated_email(
						$templateId,
						$item["external_email"],
						$ra,
						$hf,
						1,
						[]
					);
				}
			}
		}

		if ($report["internal_employees"]) {
			foreach ($report["internal_employees"] as $item) {
				if ($item['csp_reports_incidents_items_sid'] != 0) {
					//
					if (!$item["unique_code"]) {
						//
						$code = generateUniqueCode(15);
						// update unique code
						$this
							->db
							->where("sid", $item["sid"])
							->update("csp_reports_employees", [
								"unique_code" => $code
							]);
					} else {
						$code = $item["unique_code"];
					}
					// set replace array
					$ra = [
						"first_name" => $item["first_name"],
						"last_name" => $item["last_name"],
						"title" => $report["title"],
						"email" => $item["email"],
						"company_name" => $companyName,
						"csp_public_url" => generateEmailButton(
							"#fd7a2a",
							("csp/single/{$code}"),
							"View Compliance Safety Report"
						),
						"base_url" => base_url(),
					];
					//
					log_and_send_templated_email(
						$templateId,
						$item["email"],
						$ra,
						$hf,
						1,
						[]
					);
				}
			}
		}
	}

	/**
	 * Send emails to employees
	 *
	 * @param int $issueId
	 * @return array
	 */
	public function getCSPIssueForEmail(int $issueId)
	{
		$incidentId = $this->db
			->select('csp_reports_incidents_sid')
			->where("sid", $issueId)
			->get("csp_reports_incidents_items")
			->row_array()['csp_reports_incidents_sid'];

		$report = $this
			->db
			->select("csp_reports.company_sid")
			->select("csp_reports.title")
			->select("csp_reports_incidents.csp_reports_sid")
			->select("compliance_incident_types.compliance_incident_type_name")
			->join(
				"csp_reports",
				"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
				"inner"
			)
			->join(
				"compliance_incident_types",
				"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
				"inner"
			)
			->join(
				"users",
				"users.sid = csp_reports_incidents.last_modified_by",
				"left"
			)
			->where("csp_reports_incidents.sid", $incidentId)
			->get("csp_reports_incidents")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		//
		$report["internal_employees"] = $this
			->getCSPIssueInternalEmployeesById($issueId, [
				"csp_reports_employees.sid",
				"csp_reports_employees.employee_sid",
				"csp_reports_employees.unique_code",
				"csp_reports_employees.csp_reports_incidents_items_sid",
				"users.first_name",
				"users.last_name",
				"users.email",
			]);
		//
		$report["external_employees"] = $this
			->getCSPIssueExternalEmployeesById($issueId, [
				"sid",
				"csp_reports_employees.unique_code",
				"csp_reports_employees.csp_reports_incidents_items_sid",
				"external_name",
				"external_email",
			]);
		//
		return $report;
	}

	/**
	 * Get all external employees
	 *
	 * @param int $issueId
	 * @param array $columns
	 * @return array
	 */
	public function getCSPIssueExternalEmployeesById(int $issueId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_incidents_items_sid", $issueId)
			->where("is_external_employee", 1)
			->where("status", 1)
			->get("csp_reports_employees")
			->result_array();
	}

	/**
	 * Get all internal employees
	 *
	 * @param int $issueId
	 * @param array $columns
	 * @return array
	 */
	public function getCSPIssueInternalEmployeesById(int $issueId, array $columns)
	{
		return $this->db
			->select($columns)
			->where("csp_reports_employees.csp_reports_incidents_items_sid", $issueId)
			->where("csp_reports_employees.is_external_employee", 0)
			->where("csp_reports_employees.status", 1)
			->join(
				"users",
				"users.sid = csp_reports_employees.employee_sid"
			)
			->get("csp_reports_employees")
			->result_array();
	}

	public function getCSPReportByIdNew(int $reportId, array $columns)
	{
		$report = $this
			->db
			->select($columns)
			->join(
				"compliance_report_types",
				"compliance_report_types.id = csp_reports.report_type_sid",
				"left"
			)
			->join(
				"users",
				"users.sid = csp_reports.last_modified_by",
				"left"
			)
			->where("csp_reports.sid", $reportId)
			->get("csp_reports")
			->row_array();
		//
		if (!$report) {
			return [];
		}
		//
		$report["notes"] = $this->getCSPReportNotesById($reportId, [
			$this->userFields,
			"users.profile_picture",
			"csp_reports_notes.note_type",
			"csp_reports_notes.notes",
			"csp_reports_notes.created_by",
			"csp_reports_notes.updated_at",
			"csp_reports_notes.manual_email"
		]);
		//
		$report["documents"] = $this->getCSPReportFilesByType($reportId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.manual_email",
		], [
			"document",
			"file",
			"image",
		]);
		//
		$report["audios"] = $this->getCSPReportFilesByType($reportId, [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.manual_email",
		], [
			"audio",
			"video",
			"link",
		]);
		//
		// $report["incidents"] = $this->getCSPReportIncidents($reportId, [
		// 	"compliance_incident_types.compliance_incident_type_name",
		// 	"csp_reports_incidents.sid",
		// 	"csp_reports_incidents.status",
		// 	"csp_reports_incidents.completed_at",
		// 	"csp_reports_incidents.completed_by",
		// 	"csp_reports_incidents.updated_at",
		// 	"csp_reports_incidents.created_by"
		// ]);

		$report["issuesWithIncident"] = $this->getIssuesWithIncident($reportId);
		$report["question_answers"] = $this->getCSPReportQuestionAnswers($reportId);
		//
		// $report["emails"] = $this->getComplianceEmails($reportId, 0);
		$report["libraryItems"] = $this->getComplianceReportFiles($reportId, 0, 0);
		//
		return $report;
	}

	public function getIssuesWithIncident(int $reportId): array
	{
		$this->db->select([
			// Item columns
			"csp_reports_incidents_items.sid",
			"csp_reports_incidents_items.answers_json",
			"csp_reports_incidents_items.completion_status",
			"csp_reports_incidents_items.completion_date",
			"csp_reports_incidents_items.completed_by",
			"csp_reports_incidents_items.csp_reports_incidents_sid",
			// Add severity columns
			"compliance_severity_levels.level",
			"compliance_severity_levels.bg_color",
			"compliance_severity_levels.txt_color",
			// get the item description
			"compliance_report_incident_types.title as title",
			"compliance_report_incident_types.description",
			// get the incident description
			"compliance_incident_types.compliance_incident_type_name",
			// get report title
			"csp_reports.report_date",
		]);
		//
		$this->db->select($this->userFields);
		// join with severity to get the colors
		$this->db->join(
			"compliance_severity_levels",
			"compliance_severity_levels.sid = csp_reports_incidents_items.severity_level_sid",
			"inner"
		);
		// join with the report incident types to get the description
		$this->db->join(
			"compliance_report_incident_types",
			"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
			"inner"
		);
		// join with the incident
		$this->db->join(
			"csp_reports_incidents",
			"csp_reports_incidents.sid = csp_reports_incidents_items.csp_reports_incidents_sid",
			"inner"
		);
		// join with the main incident
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		// join with the report
		$this->db->join(
			"csp_reports",
			"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
			"inner"
		);
		// join with the completed by
		$this->db->join(
			"users",
			"users.sid = csp_reports_incidents_items.completed_by",
			"left"
		);
		$this->db->where('csp_reports_incidents_items.status', 1);
		$this->db->where('csp_reports.sid', $reportId);

		//
		$this->db->order_by("csp_reports_incidents_items.sid", "DESC");
		$records_obj = $this->db->get('csp_reports_incidents_items');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();

		if ($records_arr) {
			foreach ($records_arr as $k0 => $v0) {
				$records_arr[$k0]["files"] = $this->getAllItemsFiles(
					$reportId,
					$v0["csp_reports_incidents_sid"],
					$v0["sid"]
				);
			}
		}
		//
		return $records_arr;
	}


	public function getAllIssues()
	{
		$this->db->select([
			// Item columns
			"compliance_report_incident_types.sid",
			"compliance_report_incident_types.title",
			"compliance_report_incident_types.description",
			"compliance_report_incident_types.compliance_report_incident_sid",
			// Add severity columns
			"compliance_severity_levels.level",
			"compliance_severity_levels.bg_color",
			"compliance_severity_levels.txt_color",
			//
			"compliance_incident_types.compliance_incident_type_name",
		]);
		$this->db->from("compliance_report_incident_types");
		$this->db->where("compliance_incident_types.status", 1);
		$this->db->order_by("compliance_report_incident_types.title", "ASC");
		// make join with severity levels
		$this->db->join(
			"compliance_severity_levels",
			"compliance_severity_levels.sid = compliance_report_incident_types.severity_level_sid",
			"inner"
		);
		// now join with incident types
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = compliance_report_incident_types.compliance_report_incident_sid",
			"inner"
		);
		$records_obj = $this->db->get();
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();

		//
		if ($records_arr) {
			$issueObject = [];
			foreach ($records_arr as $record) {
				if (!array_key_exists($record["compliance_report_incident_sid"], $issueObject)) {
					$issueObject[$record["compliance_report_incident_sid"]] = [
						"title" => $record["compliance_incident_type_name"],
						"issues" => [],
					];
				}
				//
				$issueObject[$record["compliance_report_incident_sid"]]["issues"][] = [
					"id" => $record["sid"],
					"name" => $record["title"],
					"description" => $record["description"],
					"level" => $record["level"],
					"bg_color" => $record["bg_color"],
					"txt_color" => $record["txt_color"],
					"incident_id" => $record["compliance_report_incident_sid"],
				];
			}

			return array_values($issueObject);
		}
		//
		return $records_arr;
	}

	public function getAllIssuesByReportId(int $reportTypeId)
	{
		// get all issues of the report type
		$records = $this
			->db
			->select("incident_sid")
			->where("report_sid", $reportTypeId)
			->get("compliance_report_incident_types_mapping")
			->result_array();
		//
		if (!$records) {
			return [];
		}
		//
		$incidentIds = array_column($records, "incident_sid");
		//
		if (!$incidentIds) {
			return [];
		}

		$this->db->select([
			// Item columns
			"compliance_report_incident_types.sid",
			"compliance_report_incident_types.title",
			"compliance_report_incident_types.description",
			"compliance_report_incident_types.compliance_report_incident_sid",
			// Add severity columns
			"compliance_severity_levels.level",
			"compliance_severity_levels.bg_color",
			"compliance_severity_levels.txt_color",
			//
			"compliance_incident_types.compliance_incident_type_name",
		]);
		$this->db->from("compliance_report_incident_types");
		$this->db->where("compliance_incident_types.status", 1);
		$this->db->where("compliance_report_incident_types.sid <>", 24);
		$this->db->where_in("compliance_incident_types.id", $incidentIds);
		// $this->db->where("compliance_incident_types.compliance_incident_type_name <> ", "manual");
		$this->db->order_by("compliance_report_incident_types.title", "ASC");
		// make join with severity levels
		$this->db->join(
			"compliance_severity_levels",
			"compliance_severity_levels.sid = compliance_report_incident_types.severity_level_sid",
			"inner"
		);
		// now join with incident types
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = compliance_report_incident_types.compliance_report_incident_sid",
			"inner"
		);
		$records_obj = $this->db->get();
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();

		//
		if ($records_arr) {
			$issueObject = [];
			foreach ($records_arr as $record) {
				if (!array_key_exists($record["compliance_report_incident_sid"], $issueObject)) {
					$issueObject[$record["compliance_report_incident_sid"]] = [
						"title" => $record["compliance_incident_type_name"],
						"issues" => [],
					];
				}
				//
				$issueObject[$record["compliance_report_incident_sid"]]["issues"][] = [
					"id" => $record["sid"],
					"name" => $record["title"],
					"description" => $record["description"],
					"level" => $record["level"],
					"bg_color" => $record["bg_color"],
					"txt_color" => $record["txt_color"],
					"incident_id" => $record["compliance_report_incident_sid"],
				];
			}
			// 
			return array_values($issueObject);
		}
		
		//
		return $records_arr;
	}

	public function checkIfIncidentExists($reportId, $incidentTypeId, $loggedInUserId)
	{
		//
		$this->db->select('sid');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('incident_type_sid', $incidentTypeId);
		$record = $this->db->get('csp_reports_incidents')->row_array();

		if ($record) {
			return $record['sid'];
		} else {
			$data = [
				'csp_reports_sid' => $reportId,
				'incident_type_sid' => $incidentTypeId,
				'created_by' => $loggedInUserId,
				'last_modified_by' => $loggedInUserId,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];
			$this->db->insert('csp_reports_incidents', $data);
			return $this->db->insert_id();
		}
	}

	public function checkIfManualIncidentExists($reportId, $loggedInUserId)
	{
		//
		$incidentTypeId = $this
			->db
			->select("id")
			->where("compliance_incident_type_name", "manual")
			->get("compliance_incident_types")
			->row_array()['id'];
		//
		if (!$incidentTypeId) {
			$data = [
				'compliance_incident_type_name' => "manual",
				'status' => 1,
				'description' => "Manual Incident. Please don't delete it.",
				'code' => ''
			];
			$this->db->insert('compliance_incident_types', $data);
			$incidentTypeId = $this->db->insert_id();
		}
		//
		$this->db->select('sid');
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('incident_type_sid', $incidentTypeId);
		$record = $this->db->get('csp_reports_incidents')->row_array();

		if ($record) {
			return $record['sid'];
		} else {
			$data = [
				'csp_reports_sid' => $reportId,
				'incident_type_sid' => $incidentTypeId,
				'created_by' => $loggedInUserId,
				'last_modified_by' => $loggedInUserId,
				'created_at' => date('Y-m-d H:i:s'),
				'updated_at' => date('Y-m-d H:i:s'),
			];
			//
			$this->db->insert('csp_reports_incidents', $data);
			return $this->db->insert_id();
		}
	}

	public function getIssue($issueId)
	{
		//
		$this->db->select([
			// Item columns
			"compliance_report_incident_types.sid",
			"compliance_report_incident_types.title",
			"compliance_report_incident_types.description",
			"compliance_report_incident_types.compliance_report_incident_sid",
			"compliance_report_incident_types.severity_level_sid",
		]);
		$this->db->from("compliance_report_incident_types");
		$this->db->limit(1);
		$this->db->where("compliance_report_incident_types.sid", $issueId);
		//
		$records_obj = $this->db->get();
		$records_arr = $records_obj->row_array();
		$records_obj->free_result();
		//
		return $records_arr;
	}

	public function getIssueByRecordId($issueId)
	{
		//
		$this->db->select([
			// Item columns
			"csp_reports_incidents_items.sid",
			"csp_reports_incidents_items.severity_level_sid",
			"csp_reports_incidents_items.answers_json",
			"csp_reports_incidents_items.question_answer_json",
			//
			"compliance_report_incident_types.sid as incident_type_sid",
			"compliance_report_incident_types.title",
			"compliance_report_incident_types.description",
			"compliance_report_incident_types.compliance_report_incident_sid",
		]);
		$this->db->join(
			"compliance_report_incident_types",
			"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
			"inner"
		);
		$this->db->from("csp_reports_incidents_items");
		$this->db->limit(1);
		$this->db->where("csp_reports_incidents_items.sid", $issueId);
		//
		$records_obj = $this->db->get();
		$records_arr = $records_obj->row_array();
		$records_obj->free_result();
		//
		return $records_arr;
	}

	public function attachIssueWithReport(
		$reportId,
		$cspIncidentId,
		$issueId,
		$severityLevelId,
		$checkboxes,
		$inputs,
		$loggedInEmployeeId
	) {
		// get the department ids from report if attached
		$departmentIds = $this->getReportDepartments($reportId);
		//
		$this
			->db
			->insert(
				"csp_reports_incidents_items",
				[
					"csp_reports_incidents_sid" => $cspIncidentId,
					"compliance_report_incident_types_sid" => $issueId,
					"severity_level_sid" => $severityLevelId,
					"answers_json" => json_encode([
						"dynamicInput" => $inputs,
						"dynamicCheckbox" => $checkboxes,
					]),
					"allowed_departments" => $departmentIds ? implode(",", $departmentIds) : null,
					"last_modified_by" => $loggedInEmployeeId,
					"created_by" => $loggedInEmployeeId,
					"created_at" => getSystemDate(),
					"updated_at" => getSystemDate(),
				]
			);
		return $this->db->insert_id();
	}

	public function getIssueTypeByRecordId(
		$incidentTypeId
	) {
		return $this
			->db
			->select("compliance_incident_type_name")
			->where("id", $incidentTypeId)
			->get("compliance_incident_types")
			->row_array()['compliance_incident_type_name'];
	}

	public function addComplianceReportType(
		$title,
		$description,
		$severityLevelId
	) {
		$incidentTypeId = $this
			->db
			->select("id")
			->where("compliance_incident_type_name", "manual")
			->get("compliance_incident_types")
			->row_array()['id'];
		//
		$this
			->db
			->insert(
				"compliance_report_incident_types",
				[
					"compliance_report_incident_sid" => $incidentTypeId,
					"severity_level_sid" => $severityLevelId,
					"description" => $description,
					"title" => $title,
				]
			);
		//	
		return $this->db->insert_id();
	}

	public function attachManualIssueWithReport(
		$reportId,
		$cspIncidentId,
		$issueTypeId,
		$severityLevelId,
		$loggedInEmployeeId
	) {
		// get the department ids from report if attached
		$departmentIds = $this->getReportDepartments($reportId);
		//
		$this
			->db
			->insert(
				"csp_reports_incidents_items",
				[
					"csp_reports_incidents_sid" => $cspIncidentId,
					"compliance_report_incident_types_sid" => $issueTypeId,
					"severity_level_sid" => $severityLevelId,
					"answers_json" => json_encode([
						"dynamicInput" => [],
						"description" => [],
					]),
					"allowed_departments" => $departmentIds ? implode(",", $departmentIds) : null,
					"last_modified_by" => $loggedInEmployeeId,
					"created_by" => $loggedInEmployeeId,
					"created_at" => getSystemDate(),
					"updated_at" => getSystemDate(),
				]
			);
		return $this->db->insert_id();
	}

	public function editComplianceReportType(
		$incidentTypeId,
		$title,
		$description,
		$severityLevelId
	) {
		//
		$this
			->db
			->where("sid", $incidentTypeId)
			->update(
				"compliance_report_incident_types",
				[
					"severity_level_sid" => $severityLevelId,
					"description" => $description,
					"title" => $title,
					"updated_at" => getSystemDate(),
				]
			);
		//	
	}

	public function updateManualIssueSeverityLevel(
		$issueId,
		$severityLevelId,
		$loggedInEmployeeId
	) {
		$this
			->db
			->where("sid", $issueId)
			->update(
				"csp_reports_incidents_items",
				[
					"severity_level_sid" => $severityLevelId,
					"last_modified_by" => $loggedInEmployeeId,
					"updated_at" => getSystemDate(),
				]
			);
	}

	public function editIssueWithReport(
		$issueId,
		$severityLevelId,
		$checkboxes,
		$inputs,
		$loggedInEmployeeId
	) {
		$this
			->db
			->where("sid", $issueId)
			->update(
				"csp_reports_incidents_items",
				[
					"severity_level_sid" => $severityLevelId,
					"answers_json" => json_encode([
						"dynamicInput" => $inputs,
						"dynamicCheckbox" => $checkboxes,
					]),
					"last_modified_by" => $loggedInEmployeeId,
					"updated_at" => getSystemDate(),
				]
			);
	}

	public function updateReportBasicInformation(
		$reportId,
		$reportTitle,
		$reportDate,
		$reportCompletionDate,
		$reportStatus,
		$loggedInEmployeeId
	) {
		//
		$updateArray = [
			"title" => $reportTitle,
			"report_date" => $reportDate,
			"status" => $reportStatus,
			"last_modified_by" => $loggedInEmployeeId,
			"updated_at" => getSystemDate(),
		];

		if ($reportStatus == "completed") {
			$updateArray["completion_date"] = $reportCompletionDate;
			$updateArray["completed_by"] = $loggedInEmployeeId;
		}
		$this
			->db
			->where("sid", $reportId)
			->update(
				"csp_reports",
				$updateArray
			);
	}

	public function deleteIssueFromReport($issueId)
	{
		$this->db
			->where("sid", $issueId)
			->delete("csp_reports_incidents_items");
	}

	public function getIssueBasicInfoBySid(int $issueId, array $column)
	{
		return $this->db
			->select($column)
			->where([
				'csp_reports_incidents_items.sid' => $issueId,
			])
			->join(
				"csp_reports_incidents",
				"csp_reports_incidents.sid = csp_reports_incidents_items.csp_reports_incidents_sid",
				"inner"
			)
			->get('csp_reports_incidents_items')
			->row_array();
		//
	}

	/**
	 * Get report files by type
	 *
	 * @param int $reportId
	 * @param array $columns
	 * @param array $type
	 * @return array
	 */
	public function getAllItemsFiles(int $reportId, int $incidentId, int $issueId)
	{
		$columns = [
			$this->userFields,
			"csp_reports_files.file_value",
			"csp_reports_files.sid",
			"csp_reports_files.title",
			"csp_reports_files.s3_file_value",
			"csp_reports_files.file_type",
			"csp_reports_files.created_at",
			"csp_reports_files.manual_email",
		];
		//
		$this->db->select($columns, false);
		$this->db->where("csp_reports_sid", $reportId);
		$this->db->where("csp_incident_type_sid", $incidentId);
		$this->db->where("csp_reports_incidents_items_sid", $issueId);
		$this->db->order_by("csp_reports_files.sid", "DESC");

		$this->db->join(
			"users",
			"users.sid = csp_reports_files.created_by",
			"left"
		);
		return $this->db->get("csp_reports_files")->result_array();
	}

	public function getAllItemsFilesCount($issueId)
	{
		return $this->db
			->where("csp_reports_incidents_items_sid", $issueId)
			->count_all_results('csp_reports_files');
	}

	public function markIssueDone(int $issueId, $loggedInEmployeeId)
	{
		$this
			->db
			->where("sid", $issueId)
			->update(
				"csp_reports_incidents_items",
				[
					"completion_status" => "completed",
					"completed_by" => $loggedInEmployeeId,
					"completion_date" => getSystemDate(DB_DATE),
					"updated_at" => getSystemDate()
				]
			);
	}

	public function updateItemEmployees($reportId, $incidentId, $issueId, $selectedEmployees, $loggedInEmployeeId)
	{
		if ($selectedEmployees) {
			$employeeIds = [];
			//
			$reportEmployees = [];
			foreach ($selectedEmployees as $employeeId) {
				//
				$employeeIds[] = $employeeId;
				//
				if (
					$this
						->db
						->where('csp_reports_sid', $reportId)
						->where('csp_report_incident_sid', $incidentId)
						->where('csp_reports_incidents_items_sid', $issueId)
						->where('employee_sid', $employeeId)
						->count_all_results('csp_reports_employees') > 0
				) {
					continue;
				}
				$reportEmployees[] = [
					"csp_reports_sid" => $reportId,
					"csp_report_incident_sid" => $incidentId,
					"csp_reports_incidents_items_sid" => $issueId,
					"employee_sid" => $employeeId,
					"created_by" => $loggedInEmployeeId,
					"created_at" => getSystemDate(),
					"updated_at" => getSystemDate(),
				];
			}
			if ($reportEmployees) {
				// insert new employees
				$this->db->insert_batch("csp_reports_employees", $reportEmployees);
			}
			// update the count
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_internal_system_count" => count($reportEmployees),
				]);
			//
			// delete
			$this
				->db
				->where('csp_reports_sid', $reportId)
				->where('csp_report_incident_sid', $incidentId)
				->where('csp_reports_incidents_items_sid', $issueId)
				->where('is_external_employee', 0)
				->where_not_in('employee_sid', $employeeIds)
				->delete("csp_reports_employees");
		} else {
			$this
				->db
				->where('csp_reports_sid', $reportId)
				->where('csp_report_incident_sid', $incidentId)
				->where('csp_reports_incidents_items_sid', $issueId)
				->where('is_external_employee', 0)
				->delete('csp_reports_employees');
			// update the count
			$this
				->db
				->where("sid", $reportId)
				->update("csp_reports", [
					"allowed_internal_system_count" => 0,
				]);
		}
	}

	public function updateItemExternalEmployee($reportId, $incidentId, $issueId, $employee, $loggedInEmployeeId)
	{
		// add external employees
		$externalEmployees = [
			"csp_reports_sid" => $reportId,
			"csp_report_incident_sid" => $incidentId,
			"csp_reports_incidents_items_sid" => $issueId,
			"is_external_employee" => 1,
			"external_name" => $employee["name"],
			"external_email" => $employee["email"],
			"created_by" => $loggedInEmployeeId,
			"created_at" => getSystemDate(),
			"updated_at" => getSystemDate(),
		];
		$this->db->insert("csp_reports_employees", $externalEmployees);

		return $this->db->insert_id();
	}

	function getDepartments($companySid)
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

	function getSelectedDepartments($departmentIds)
	{
		$a = $this->db
			->select('sid, name')
			->where_in('sid', $departmentIds)
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

	public function getActiveDepartments($companyId)
	{
		$a = $this->db
			->select('sid, name')
			->where('company_sid', $companyId)
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

	function getTeams($companySid, $departments)
	{
		//
		if (!$departments || !count($departments))
			return [];
		//
		$a = $this->db
			->select('sid, name')
			->where('company_sid', $companySid)
			->where('status', 1)
			->where('is_deleted', 0)
			->where_in('department_sid', array_column($departments, 'sid'))
			->order_by('sort_order', 'ASC')
			->get('departments_team_management');
		//
		$b = $a->result_array();
		$a = $a->free_result();
		//
		return $b;
	}

	/**
	 * Get all compliance reports
	 *
	 * @param int $reportId
	 * @param int $incidentId
	 * @param int $itemId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function addDepartmentsAndTeams(
		int $reportId,
		int $incidentId,
		int $issueId,
		int $loggedInEmployeeId,
		array $post
	) {
		//
		$this
			->db
			->where("sid", $issueId)
			->update(
				"csp_reports_incidents_items",
				[
					"allowed_departments" => isset($post['departments']) ? implode(',', $post['departments']) : NULL,
					"allowed_teams" => isset($post['teams']) ? implode(',', $post['teams']) : NULL,
					"last_modified_by" => $loggedInEmployeeId,
					"updated_at" => getSystemDate(),
				]
			);

		$this->db->where('csp_reports_sid', $reportId);
		$this->db->where('csp_report_incident_sid', $incidentId);
		$this->db->where('csp_reports_incidents_items_sid', $issueId);
		$this->db->where('is_manager', 1);
		$this->db->delete('csp_reports_employees');
		//
		$managersIds = $this->getDepartmentAndTeamManagers($post['departments'], $post['teams']);
		//
		if ($managersIds) {
			//
			$todayDateTime = getSystemDate();
			//
			foreach ($managersIds as $managerId) {
				//
				$dataToInsert = [];
				$dataToInsert['csp_reports_sid'] = $reportId;
				$dataToInsert['csp_report_incident_sid'] = $incidentId;
				$dataToInsert['csp_reports_incidents_items_sid'] = $issueId;
				$dataToInsert['employee_sid'] = $managerId;
				$dataToInsert['is_manager'] = 1;
				$dataToInsert['created_by'] = $loggedInEmployeeId;
				$dataToInsert['created_at'] = $todayDateTime;
				$dataToInsert['updated_at'] = $todayDateTime;
				//
				$this->db->insert('csp_reports_employees', $dataToInsert);
			}
		}
		//
		return true;
	}

	public function getDepartmentAndTeamManagers($departments, $teams)
	{
		$managerIdsList = [];
		//
		if ($departments) {
			foreach ($departments as $departmentId) {
				$this->db->select("csp_managers_ids");
				$this->db->where("sid", $departmentId);
				$this->db->where("is_deleted", 0);
				//
				$records_obj = $this->db->get("departments_management");
				$cspDepartmentManagerIds = $records_obj->row_array();
				$records_obj->free_result();
				//
				if ($cspDepartmentManagerIds) {
					//
					$departmentManagerIds = explode(',', $cspDepartmentManagerIds['csp_managers_ids']);
					//
					foreach ($departmentManagerIds as $managerId) {
						if (!in_array($managerId, $managerIdsList)) {
							$managerIdsList[] = $managerId;
						}
					}
				}
			}
		}

		if ($teams) {
			foreach ($teams as $teamId) {
				$this->db->select("csp_managers_ids");
				$this->db->where("sid", $teamId);
				$this->db->where("is_deleted", 0);
				//
				$records_obj = $this->db->get("departments_team_management");
				$cspTeamManagerIds = $records_obj->row_array();
				$records_obj->free_result();
				//
				if ($cspTeamManagerIds) {
					//
					$teamManagerIds = explode(',', $cspTeamManagerIds['csp_managers_ids']);
					//
					foreach ($teamManagerIds as $managerId) {
						if (!in_array($managerId, $managerIdsList)) {
							$managerIdsList[] = $managerId;
						}
					}
				}
			}
		}
		//
		return $managerIdsList;
	}

	/**
	 * save issue question
	 *
	 * @param int $issueId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function processIssueQuestion(
		int $issueId,
		int $loggedInEmployeeId,
		array $post
	) {
		//		
		$this
			->db
			->where("sid", $issueId)
			->update(
				"csp_reports_incidents_items",
				[
					"question_answer_json" => json_encode($post),
					"last_modified_by" => $loggedInEmployeeId,
					"updated_at" => getSystemDate(),
				]
			);
		//
		return true;
	}

	/**
	 * save report question
	 *
	 * @param int $reportId
	 * @param int $loggedInEmployeeId
	 * @param array $post
	 * @return array
	 */
	public function processReportQuestion(
		int $reportId,
		int $loggedInEmployeeId,
		array $post
	) {
		//		
		$this
			->db
			->where("sid", $reportId)
			->update(
				"csp_reports",
				[
					"answers_json" => json_encode($post),
					"last_modified_by" => $loggedInEmployeeId,
					"updated_at" => getSystemDate(),
				]
			);
		//
		return true;
	}

	/**
	 * get report departments
	 *
	 * @param int $reportId
	 * @return array
	 */
	public function getReportDepartments(
		int $reportId
	) {
		$departments = [];
		//		
		$this->db->select('answers_json');
		$this->db->where('sid', $reportId);
		$answers = $this->db->get('csp_reports')->row_array();
		//
		if ($answers) {
			$decodedJSON = json_decode(
				$answers['answers_json'],
				true
			);

			$departments = empty($decodedJSON['departments']) ? [] : $decodedJSON['departments'];
		}
		//
		return $departments;
	}

	/**
	 * manage departments and teams
	 *
	 */
	public function manageAllowedDepartmentsAndTeamsManagers()
	{
		$allowedDepartmentsAndTeams = $this->db
			->select("sid, csp_reports_incidents_sid, allowed_departments, allowed_teams")
			->get("csp_reports_incidents_items")
			->result_array();
		//
		$session = $this->session->userdata('logged_in');
		$loggedInEmployeeId = $session["employer_detail"]["sid"];
		//
		if ($allowedDepartmentsAndTeams) {
			foreach ($allowedDepartmentsAndTeams as $issueRow) {
				//
				$managerIdsList = [];
				//
				if ($issueRow['allowed_departments']) {
					//
					$departments = explode(',', $issueRow['allowed_departments']);
					//
					foreach ($departments as $departmentId) {
						$this->db->select("csp_managers_ids");
						$this->db->where("sid", $departmentId);
						$this->db->where("is_deleted", 0);
						//
						$records_obj = $this->db->get("departments_management");
						$cspDepartmentManagerIds = $records_obj->row_array();
						$records_obj->free_result();
						//
						if ($cspDepartmentManagerIds) {
							//
							$departmentManagerIds = explode(',', $cspDepartmentManagerIds['csp_managers_ids']);
							//
							foreach ($departmentManagerIds as $managerId) {
								if (!in_array($managerId, $managerIdsList)) {
									$managerIdsList[] = $managerId;
								}
							}
						}
					}
				}
				//
				if ($issueRow['allowed_teams']) {
					//
					$teams = explode(',', $issueRow['allowed_teams']);
					//
					foreach ($teams as $teamId) {
						$this->db->select("csp_managers_ids");
						$this->db->where("sid", $teamId);
						$this->db->where("is_deleted", 0);
						//
						$records_obj = $this->db->get("departments_team_management");
						$cspTeamManagerIds = $records_obj->row_array();
						$records_obj->free_result();
						//
						if ($cspTeamManagerIds) {
							//
							$teamManagerIds = explode(',', $cspTeamManagerIds['csp_managers_ids']);
							//
							foreach ($teamManagerIds as $managerId) {
								if (!in_array($managerId, $managerIdsList)) {
									$managerIdsList[] = $managerId;
								}
							}
						}
					}

				}
				//
				$issueId = $issueRow['sid'];
				$incidentId = $issueRow['csp_reports_incidents_sid'];
				$reportId = $this->getReportIdByIncidentID($incidentId);


				//
				$this->db->where('csp_reports_sid', $reportId);
				$this->db->where('csp_report_incident_sid', $incidentId);
				$this->db->where('csp_reports_incidents_items_sid', $issueId);
				$this->db->where('is_manager', 1);
				$this->db->delete('csp_reports_employees');
				//
				$managersIds = array_filter($managerIdsList);
				//
				if ($managersIds) {
					$todayDateTime = getSystemDate();
					//
					foreach ($managersIds as $managerId) {
						//
						$dataToInsert = [];
						$dataToInsert['csp_reports_sid'] = $reportId;
						$dataToInsert['csp_report_incident_sid'] = $incidentId;
						$dataToInsert['csp_reports_incidents_items_sid'] = $issueId;
						$dataToInsert['employee_sid'] = $managerId;
						$dataToInsert['is_manager'] = 1;
						$dataToInsert['created_by'] = $loggedInEmployeeId;
						$dataToInsert['created_at'] = $todayDateTime;
						$dataToInsert['updated_at'] = $todayDateTime;
						//
						$this->db->insert('csp_reports_employees', $dataToInsert);
					}
				}
			}
		}
	}

	public function getReportIdByIncidentID($incidentId)
	{
		$this->db->select('csp_reports_sid');
		$this->db->where('sid', $incidentId);
		$record = $this->db->get('csp_reports_incidents')->row_array();
		//
		return $record['csp_reports_sid'];
	}

	/**
	 * Summary of deleteReportById
	 * @param int $reportId
	 * @param int $companyId
	 * @return bool
	 */
	public function deleteReportById(int $reportId, int $companyId): bool
	{
		// Check if the report exists for the given company
		$this->db->where('sid', $reportId);
		$this->db->where('company_sid', $companyId);
		$reportExists = $this->db->count_all_results("csp_reports");

		if (!$reportExists) {
			return false; // Report does not exist for the company
		}

		$this->db->trans_start();

		// Delete related data from csp_reports_incidents
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->delete('csp_reports_incidents');

		// Delete related data from csp_reports_employees
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->delete('csp_reports_employees');

		// Delete related data from csp_reports_files
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->delete('csp_reports_files');

		// Delete related data from csp_reports_notes
		$this->db->where('csp_reports_sid', $reportId);
		$this->db->delete('csp_reports_notes');

		// Delete the report itself
		$this->db->where('sid', $reportId);
		$this->db->where('company_sid', $companyId);
		$this->db->delete('csp_reports');

		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	public function deleteAttachedDepartmentsAndTeams($issueId, $loggedInEmployeeId)
	{
		//
		$this
			->db
			->where("sid", $issueId)
			->update(
				"csp_reports_incidents_items",
				[
					"allowed_departments" => NULL,
					"allowed_teams" => NULL,
					"last_modified_by" => $loggedInEmployeeId,
					"updated_at" => getSystemDate(),
				]
			);
		//
		$this->deleteDepartmentManagers($issueId);
	}

	public function deleteDepartmentManagers($issueId)
	{
		$this->db->where('csp_reports_incidents_items_sid', $issueId);
		$this->db->where('is_manager', 1);
		$this->db->delete('csp_reports_employees');
	}


	public function isAllowedToAccessIssue(
		int $employeeId,
		int $issueId
	): int {
		return $this
			->db
			->where([
				"employee_sid" => $employeeId,
				"csp_reports_incidents_items_sid" => $issueId,
			])
			->count_all_results("csp_reports_employees");
	}

	public function getDepartmentsCSPManagers($departmentIds)
	{
		$records = $this
			->db
			->select("csp_managers_ids")
			->where_in("sid", $departmentIds)
			->where("status", 1)
			->where("is_deleted", 0)
			->get("departments_management")
			->result_array();
		//
		if (!$records) {
			return [];
		}
		//
		$ids = [];
		//
		foreach ($records as $v0) {
			$tmp = explode(",", $v0["csp_managers_ids"]);
			$ids = array_merge($ids, $tmp);
		}
		//
		return array_unique($ids);
	}

	public function getTeamsCSPManagers($teamIds)
	{
		$records = $this
			->db
			->select("departments_team_management.csp_managers_ids")
			->where_in("departments_team_management.sid", $teamIds)
			->where("departments_team_management.status", 1)
			->where("departments_team_management.is_deleted", 0)
			->where("departments_management.status", 1)
			->where("departments_management.is_deleted", 0)
			->join(
				"departments_management",
				"departments_management.sid = departments_team_management.department_sid",
				"inner"
			)
			->get("departments_team_management")
			->result_array();
		//
		if (!$records) {
			return [];
		}
		//
		$ids = [];
		//
		foreach ($records as $v0) {
			$tmp = explode(",", $v0["csp_managers_ids"]);
			$ids = array_merge($ids, $tmp);
		}
		//
		return array_unique($ids);
	}

	public function getAllSelectedReports(int $companyId, array $filter, bool $isMainCPA = true): array
	{
		$reportIds = [];
		//
		if ($isMainCPA) {
			//
			if ((array_key_exists("departments", $filter) && $filter["departments"] != "") || (array_key_exists("teams", $filter) && $filter["teams"] != "")) {
				$this->db->select('csp_reports_employees.csp_reports_sid');
				$this->db->join(
					"csp_reports",
					"csp_reports.sid = csp_reports_employees.csp_reports_sid",
					"inner"
				);
				$this->db->where('csp_reports.company_sid', $companyId);
				$this->db->where('csp_reports_employees.status', 1);
				$this->db->group_start();
				if (array_key_exists("departments", $filter) && $filter["departments"] != "") {
					foreach ($filter["departments"] as $department) {
						$this->db->or_where('FIND_IN_SET("' . ($department) . '", allowed_departments) > 0', NULL, FALSE);
					}
				}
				// For teams
				if (array_key_exists("teams", $filter) && $filter["teams"] != "") {
					foreach ($filter["teams"] as $team) {
						$this->db->or_where('FIND_IN_SET("' . ($team) . '", allowed_teams) > 0', NULL, FALSE);
					}
				}
				$this->db->group_end();
				$records_obj = $this->db->get('csp_reports_employees');
				$records_arr = $records_obj->result_array();
				$records_obj->free_result();
				//
				$reportIds = array_column($records_arr, 'csp_reports_sid');
			}
		}

		$this->db->select([
			// Item columns
			"csp_reports_incidents_items.sid",
			"csp_reports_incidents_items.answers_json",
			"csp_reports_incidents_items.completion_status",
			"csp_reports_incidents_items.question_answer_json",
			"csp_reports_incidents_items.allowed_departments",
			"csp_reports_incidents_items.allowed_teams",
			"csp_reports_incidents_items.completion_date",
			"csp_reports_incidents_items.completed_by",
			"csp_reports_incidents_items.csp_reports_incidents_sid",
			// Add severity columns
			"compliance_severity_levels.level",
			"compliance_severity_levels.bg_color",
			"compliance_severity_levels.txt_color",
			// get the item description
			"compliance_report_incident_types.title as item_title",
			"compliance_report_incident_types.description",
			// get the incident description
			"compliance_incident_types.compliance_incident_type_name",
			// get report title
			"csp_reports.sid as csp_report_sid",
			"csp_reports.title",
			"csp_reports.report_date",
			"csp_reports.disable_answers",
			"csp_reports.report_type_sid",
			"csp_reports.completion_date",
			"csp_reports.status as report_status",
			"csp_reports.updated_at",
		]);
		//
		$this->db->select($this->userFields);
		// join with severity to get the colors
		$this->db->join(
			"compliance_severity_levels",
			"compliance_severity_levels.sid = csp_reports_incidents_items.severity_level_sid",
			"inner"
		);
		// join with the report incident types to get the description
		$this->db->join(
			"compliance_report_incident_types",
			"compliance_report_incident_types.sid = csp_reports_incidents_items.compliance_report_incident_types_sid",
			"inner"
		);
		// join with the incident
		$this->db->join(
			"csp_reports_incidents",
			"csp_reports_incidents.sid = csp_reports_incidents_items.csp_reports_incidents_sid",
			"inner"
		);
		// join with the main incident
		$this->db->join(
			"compliance_incident_types",
			"compliance_incident_types.id = csp_reports_incidents.incident_type_sid",
			"inner"
		);
		// join with the report
		$this->db->join(
			"csp_reports",
			"csp_reports.sid = csp_reports_incidents.csp_reports_sid",
			"inner"
		);
		// join with the completed by
		$this->db->join(
			"users",
			"users.sid = csp_reports_incidents_items.completed_by",
			"left"
		);
		$this->db->where('csp_reports_incidents_items.status', 1);
		$this->db->where('csp_reports.company_sid', $companyId);
		//
		if ($reportIds) {
			$this->db->where_in("csp_reports.sid", $reportIds);
		}
		// check report date range
		if (array_key_exists("date_range", $filter) && $filter["date_range"] != "") {
			list($start_date, $end_date) = explode(" - ", $_GET['date_range']);
			$between = "report_date between '" . formatDateToDB($start_date, SITE_DATE, DB_DATE) . "' and '" . formatDateToDB($end_date, SITE_DATE, DB_DATE) . "'";
			$this->db->where($between);
		}

		// check for severity status
		if (array_key_exists("severity_level", $filter) && $filter["severity_level"] != "-1") {
			$this->db->where(
				"compliance_severity_levels.sid",
				$filter["severity_level"]
			);
		}
		// check for incident
		if (array_key_exists("incident", $filter) && $filter["incident"] != "-1") {
			$this->db->where(
				"csp_reports_incidents.sid",
				$filter["incident"]
			);
		}
		// check for status
		if (array_key_exists("status", $filter) && $filter["status"] != "-1") {
			$this->db->where(
				"csp_reports_incidents_items.completion_status",
				$filter["status"]
			);
		}
		// check report title
		if (array_key_exists("title", $filter) && $filter["title"] != "") {
			$like = " `csp_reports.title` LIKE '%" . $filter["title"] . "%' ";
			$this->db->where($like);
		}
		// strictly check for the allowed issues
		// if is not ain CPA
		if (!$isMainCPA) {
			if ($this->allowedCSP["tasks"]) {
				$this->db->where_in("csp_reports_incidents_items.sid", $this->allowedCSP["tasks"]);
			}
			//
			if (array_key_exists("departmentIds", $this->allowedCSP) || array_key_exists("teamIds", $this->allowedCSP)) {
				// check for departments
				$this->db->group_start();

				if (array_key_exists("departmentIds", $this->allowedCSP)) {
					$this->db->group_start();
					foreach ($this->allowedCSP["departmentIds"] as $v0) {
						$this->db->where("FIND_IN_SET({$v0}, csp_reports_incidents_items.allowed_departments) >", 0);
					}
					$this->db->group_end();
				}
				if (array_key_exists("teamIds", $this->allowedCSP)) {
					$this->db->or_group_start();
					foreach ($this->allowedCSP["teamIds"] as $v0) {
						$this->db->where("FIND_IN_SET({$v0}, csp_reports_incidents_items.allowed_teams) >", 0);
					}
					$this->db->group_end();
				}
				$this->db->group_end();
			}

		}

		//
		$this->db->order_by("csp_reports_incidents_items.sid", "DESC");
		$records_obj = $this->db->get('csp_reports_incidents_items');
		$records_arr = $records_obj->result_array();
		$records_obj->free_result();

		//
		if ($records_arr) {
			$recordHolder = [];
			//
			foreach ($records_arr as $v0) {
				// check if 
				$reportId = $v0["csp_report_sid"];
				//
				if (!array_key_exists($reportId, $recordHolder)) {
					$recordHolder[$reportId] = [
						"id" => $reportId,
						"title" => $v0["title"],
						"report_date" => $v0["report_date"],
						"issues" => [],
					];
				}

				// get attachments
				$v0["files"] = $this->getAllItemsFiles(
					$reportId,
					$v0["csp_reports_incidents_sid"],
					$v0["sid"]
				);

				//
				$recordHolder[$reportId]["issues"][] = $v0;
			}
			//
			$records_arr = array_values($recordHolder);
		}

		return $records_arr;
	}


}

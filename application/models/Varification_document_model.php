<?php

class Varification_document_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_users_pending_w4($company_sid, $user_type, $count = FALSE, $lists)
    {
        if ($user_type == 'employee') {
            $inactive_employee_sid = $lists;
        } else {
            //
            $inactive_applicant_sid = $lists;
        }
        //
        $this->db->select('user_type, employer_sid as user_sid, sent_date, signature_timestamp as filled_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee' && !empty($inactive_employee_sid)) {
            $this->db->group_start();
            $this->db->where_not_in('employer_sid', $inactive_employee_sid);
            $this->db->where('user_type', 'employee');
            $this->db->group_end();
        } else {
            if (!empty($inactive_applicant_sid)) {

                $this->db->group_start();
                $this->db->where_not_in('employer_sid', $inactive_applicant_sid);
                $this->db->where('user_type', 'applicant');
                $this->db->group_end();
            }
        }

        $this->db->group_start();
        $this->db->where('emp_identification_number', NULL);
        $this->db->or_where('emp_identification_number', "");
        $this->db->or_where('emp_name', NULL);
        $this->db->or_where('emp_name', "");
        $this->db->or_where('emp_address', NULL);
        $this->db->or_where('emp_address', "");
        $this->db->or_where('first_date_of_employment', NULL);
        $this->db->or_where('first_date_of_employment', "0000-00-00");
        $this->db->group_end();

        // $this->db->where('user_consent', 1);
        $this->db->where('status', 1);
        $this->db->where('uploaded_file', NULL);
        //
        if ($count) {
            return $this->db->count_all_results('form_w4_original');
        }
        $records_obj = $this->db->get('form_w4_original');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            foreach ($records_arr as $i_key => $value) {
                $records_arr[$i_key]['document_name'] = 'W4 Fillable';
            }
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_all_users_pending_i9($company_sid, $user_type, $count = FALSE, $lists)
    {

        if ($user_type == 'employee') {
            $inactive_employee_sid = $lists;
        } else {
            //
            $inactive_applicant_sid = $lists;
        }
        //
        $this->db->select('user_type, user_sid, sent_date, applicant_filled_date as filled_date');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);

        if ($user_type == 'employee' && !empty($inactive_employee_sid)) {
            $this->db->group_start();
            $this->db->where_not_in('user_sid', $inactive_employee_sid);
            $this->db->where('user_type', 'employee');
            $this->db->group_end();
        } else {
            if (!empty($inactive_applicant_sid)) {
                $this->db->group_start();
                $this->db->where_not_in('user_sid', $inactive_applicant_sid);
                $this->db->where('user_type', 'applicant');
                $this->db->group_end();
            }
        }

        $this->db->where('section2_sig_emp_auth_rep', NULL);
        $this->db->where('section3_emp_sign', NULL);
        $this->db->where('user_consent', 1);
        $this->db->where('status', 1);
        $this->db->where('s3_filename', NULL);
        //
        if ($count) {
            return $this->db->count_all_results('applicant_i9form');
        }
        $records_obj = $this->db->get('applicant_i9form');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            foreach ($records_arr as $i_key => $value) {
                $records_arr[$i_key]['document_name'] = 'I9 Fillable';
            }
            $return_data = $records_arr;
        }

        return $return_data;
    }

    //
    function getPendingAuthDocs($company_sid, $user_type, $count = FALSE, $employer = [], $lists)
    {

        if ($user_type == 'employee') {
            $inactive_employee_sid = $lists;
        } else {
            //
            $inactive_applicant_sid = $lists;
        }
        //
        if (!empty($employer)) {
            //
            $access_level = str_replace('-', '_', stringToSlug($employer['access_level']));
            //
            if (!$employer['access_level_plus'] && !in_array($access_level, ['admin'])) {
                //
                $departmentIds = $this->getMyDepartmentIds($employer['sid']);
                $teamIds = $this->getMyTeamIds($employer['sid']);
                //
                $this->db->group_start();
                $this->db->where('find_in_set("' . ($access_level) . '", allowed_roles) > 0', false, false);
                $this->db->or_where('find_in_set("' . ($employer['sid']) . '", allowed_employees) > 0', false, false);
                //
                if (!empty($departmentIds)) {
                    $this->db->or_where('find_in_set("' . ($employer['sid']) . '", allowed_departments) > 0', false, false);
                }
                //
                if (!empty($teamIds)) {
                    $this->db->or_where('find_in_set("' . ($employer['sid']) . '", allowed_teams) > 0', false, false);
                }
                $this->db->group_end();
            }
        }
        //
        $this->db
            ->from('documents_assigned')
            ->where('company_sid', $company_sid)
            ->where('user_type', $user_type)
            ->where('archive', 0)
            ->where('status', 1)
            ->where('authorized_signature IS NULL', null)
            ->like('document_description', '{{authorized_signature}}');
        //
        if (strtolower($user_type) == 'applicant' && !empty($inactive_applicant_sid)) {
            $this->db->where_not_in('user_sid', $inactive_applicant_sid);
        }
        //
        if (strtolower($user_type) == 'employee' && !empty($inactive_employee_sid)) {
            $this->db->where_not_in('user_sid', $inactive_employee_sid);
        }
        //
        if ($count) {
            return $this->db->count_all_results();
        }
        //
        $this->db->select('
            *,
            assigned_date as sent_date,
            document_title as document_name,
            IF(
                signature_timestamp is null ,
                IF(
                    uploaded_date IS NULL, 
                    IF( 
                        downloaded_date IS NULL, 
                        acknowledged_date, 
                        downloaded_date 
                    ), 
                    uploaded_date 
                ),
                signature_timestamp
            ) AS filled_date
        ');
        //
        $a = $this->db->get();
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        return $b;
    }

    function getAllCompanyInactiveEmployee($companySid)
    {
        $a = $this->db
            ->select('
            sid
        ')
            ->where('parent_sid', $companySid)
            ->where('active', 0)
            ->where('parent_sid <> ', 0)
            ->or_where('terminated_status', 1)
            ->order_by('first_name', 'ASC')
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    function getAllCompanyInactiveApplicant($companySid)
    {
        $a = $this->db
            ->select('
            portal_applicant_jobs_list.portal_job_applications_sid as sid
        ')
            ->where('portal_applicant_jobs_list.company_sid', $companySid)
            ->group_start()
            ->where('portal_applicant_jobs_list.archived', 1)
            ->or_where('portal_job_applications.hired_status', 1)
            ->group_end()
            ->join('portal_job_applications', 'portal_job_applications.sid = portal_applicant_jobs_list.portal_job_applications_sid', 'left')
            ->get('portal_applicant_jobs_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        return array_column($b, 'sid');
    }

    //
    function getMyDepartmentIds($employeId)
    {
        $a =
            $this->db->select('sid')
            ->where("find_in_set($employeId, supervisor) > 0", false, false)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get("departments_management");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        return !empty($b) ? array_column($b, 'sid') : [];
    }

    //
    function getMyTeamIds($employeId)
    {
        $a =
            $this->db->select('sid')
            ->where("find_in_set($employeId, team_lead) > 0", false, false)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get("departments_team_management");
        //
        $b = $a->result_array();
        //
        $a->free_result();
        //
        return !empty($b) ? array_column($b, 'sid') : [];
    }

    //
    public function getMyApprovalDocuments($employee_sid)
    {
        //
        $this->db->select('portal_document_assign_flow_employees.sid');

        $this->db->where('portal_document_assign_flow_employees.assigner_sid', $employee_sid);
        $this->db->where('portal_document_assign_flow_employees.status', 1);
        $this->db->where('portal_document_assign_flow_employees.assigner_turn', 1);
        $this->db->where('portal_document_assign_flow.assign_status', 1);
        $this->db->where('documents_assigned.approval_process', 1);

        $this->db->join('portal_document_assign_flow', 'portal_document_assign_flow.sid = portal_document_assign_flow_employees.portal_document_assign_sid', 'inner');
        $this->db->join('documents_assigned', 'documents_assigned.approval_flow_sid = portal_document_assign_flow.sid', 'inner');
        $records_obj = $this->db->get('portal_document_assign_flow_employees');

        $my_documents = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($my_documents)) {
            $return_data = $my_documents;
        }

        return $return_data;
    }


    public function getPendingStateForms(
        int $companyId,
        string $userType,
        bool $doCount,
        array $excludedUserIds
    ) {
        $this->db
            ->where("portal_state_form.user_type", $userType)
            ->where_not_in("portal_state_form.user_sid", $excludedUserIds)
            ->where("portal_state_form.company_sid", $companyId)
            ->where("portal_state_form.employer_consent", 0)
            ->where("portal_state_form.status", 1);
        //
        if ($doCount) {
            return $this->db
                ->count_all_results("portal_state_form");
        }
        return $this->db
            ->select("
                state_forms.title as document_name,
                portal_state_form.user_sid,
                portal_state_form.user_type,
                portal_state_form.user_consent_at,
            ")
            ->join(
                "state_forms",
                "portal_state_form.state_form_sid = state_forms.sid",
                "inner"
            )
            ->get("portal_state_form")
            ->result_array();
    }
}

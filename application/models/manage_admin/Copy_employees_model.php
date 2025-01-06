<?php
class Copy_employees_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all_corporate_groups($active = 1)
    {
        $this->db->select('sid, group_name');
        $this->db->where('corporate_company_sid <>', 0);
        $this->db->order_by('group_name', 'ASC');
        $this->db->from('automotive_groups');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_companies()
    {

        $this->db->select('sid, CompanyName');
        $this->db->where('active', 1);
        $this->db->where('is_paid', 1);
        $this->db->where('parent_sid', 0);
        $this->db->order_by('CompanyName', 'ASC');
        $this->db->from('users');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $result = array();
        if (!empty($records_arr)) {
            $result = $records_arr;
        }
        return $result;
    }

    function get_corporate_companies_by_id($sid)
    {
        $this->db->select('company_sid');
        $this->db->where('automotive_group_sid', $sid);
        $this->db->where('is_registered_in_ahr', 1);
        $this->db->from('automotive_group_companies');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        $result = array();
        if (!empty($records_arr)) {
            $result = $records_arr;
        }
        return $result;
    }

    function get_company_name_by_id($sid)
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

    function get_company_employee($sid, $type, $page, $limit, $employee_sortby, $employee_sort_orderby, $employee_keyword)
    {
        $start = $page == 1 ? 0 : ($page * $limit) - $limit;
        $this->db->select('sid, email, first_name, last_name, active, job_title, access_level, access_level_plus, pay_plan_flag, terminated_status');
        $this->db->where('parent_sid', $sid);
        $this->db->where('is_executive_admin', 0);
        // $this->db->order_by('first_name', 'ASC');

        if ($type == 'active') {
            $this->db->where('active', 1);
            $this->db->where('terminated_status', 0);
        }

        if ($type == 'terminated') {
            $this->db->where('terminated_status', 1);
        }
        if ($type != 'all' && $type != 'active' && $type != 'terminated' && $type != null) {
            $this->db->where('LCASE(general_status) ', $type);
        }


        if (trim($employee_keyword)) {
            //
            $keywords = explode(',', trim($employee_keyword));
            $this->db->group_start();
            //
            foreach ($keywords as $keyword) {
                $this->db->or_group_start();
                //
                $keyword = trim(urldecode($keyword));
                //
                if (strpos($keyword, '@') !== false) {
                    $this->db->or_where('email', $keyword);
                } else {
                    $this->db->where("first_name regexp '$keyword'", null, null);
                    $this->db->or_where("last_name regexp '$keyword'", null, null);
                    $this->db->or_where("nick_name regexp '$keyword'", null, null);
                    $this->db->or_where("extra_info regexp '$keyword'", null, null);
                    $this->db->or_where('lower(concat(first_name, last_name)) =', strtolower(preg_replace('/[^a-z0-9]/i', '', $keyword)));
                }
                $this->db->group_end();
            }
            $this->db->group_end();
        }


        $this->db->order_by($employee_sortby, $employee_sort_orderby);

        $records_obj = $this->db->limit($limit, $start)->get('users');

        // _e($this->db->last_query(), true);
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    function get_employee_count($sid, $type, $employee_keyword)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $sid);
        $this->db->where('is_executive_admin', 0);

        if ($type == 'active') {
            $this->db->where('active', 1);
            $this->db->where('terminated_status', 0);
        }

        if ($type == 'terminated') {
            $this->db->where('terminated_status', 1);
        }

        if ($type != 'all' && $type != 'active' && $type != 'terminated'  && $type != null) {
            $this->db->where('LCASE(general_status) ', $type);
        }


        if (trim($employee_keyword)) {
            //
            $keywords = explode(',', trim($employee_keyword));
            $this->db->group_start();
            //
            foreach ($keywords as $keyword) {
                $this->db->or_group_start();
                //
                $keyword = trim(urldecode($keyword));
                //
                if (strpos($keyword, '@') !== false) {
                    $this->db->or_where('email', $keyword);
                } else {
                    $this->db->where("first_name regexp '$keyword'", null, null);
                    $this->db->or_where("last_name regexp '$keyword'", null, null);
                    $this->db->or_where("nick_name regexp '$keyword'", null, null);
                    $this->db->or_where("extra_info regexp '$keyword'", null, null);
                    $this->db->or_where('lower(concat(first_name, last_name)) =', strtolower(preg_replace('/[^a-z0-9]/i', '', $keyword)));
                }
                $this->db->group_end();
            }
            $this->db->group_end();
        }


        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_count = 0;

        if (!empty($records_arr)) {
            $return_count = count($records_arr);
        }

        return $return_count;
    }

    function fetch_employee_by_sid($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr[0];
        }

        return $return_data;
    }

    function check_employee_exist($email, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('email', $email);
        $this->db->where('is_executive_admin', 0);
        $this->db->from('users');
        $ids = $this->db->count_all_results();

        return $ids != 0 ? true : false;
    }

    function check_employee_username_exist($username)
    {
        $this->db->select('sid');
        $this->db->where('username', $username);
        $this->db->from('users');
        $ids = $this->db->count_all_results();

        return $ids != 0 ? true : false;
    }

    function copy_user_to_other_company($data)
    {
        $this->db->insert('users', $data);
        return $this->db->insert_id();
    }

    function get_employee_e_signature($company_sid, $user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('is_active', 1);
        $this->db->limit(1);

        $record_obj = $this->db->get('e_signatures_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function copy_new_employee_e_signature($data)
    {
        $this->db->insert('e_signatures_data', $data);
    }

    function get_employee_specific_video($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
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

    function copy_new_employee_video($data)
    {
        $this->db->insert('learning_center_online_videos_assignments', $data);
    }

    function get_employee_specific_training_sessions($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
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

    function copy_new_employee_training_session($data)
    {
        $this->db->insert('learning_center_training_sessions_assignments', $data);
    }

    function get_license_details($user_type, $user_sid, $license_type)
    {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $this->db->where('license_type', $license_type);
        $this->db->limit(1);

        $record_obj = $this->db->get('license_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function copy_new_employee_license($data)
    {
        $this->db->insert('license_information', $data);
    }

    function get_dependant_information($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('users_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $record_obj = $this->db->get('dependant_information');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_dependent($data)
    {
        $this->db->insert('dependant_information', $data);
    }

    function get_employee_emergency_contacts($user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);
        $records_obj = $this->db->get('emergency_contacts');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_emergency_contacts($data)
    {
        $this->db->insert('emergency_contacts', $data);
    }

    function get_bank_detail($user_type, $user_sid)
    {
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $user_sid);

        $record_obj = $this->db->get('bank_account_details');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function copy_new_employee_bank_detail($data)
    {
        $this->db->insert('bank_account_details', $data);
    }

    function get_equipment_info($user_type, $users_sid)
    {
        $this->db->select('*');
        $this->db->where('users_type', $user_type);
        $this->db->where('users_sid', $users_sid);
        $this->db->where('delete_flag', 0);;

        $records_obj = $this->db->get('equipment_information');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_equipment($data)
    {
        $this->db->insert('equipment_information', $data);
    }

    function get_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1, $archive = 0)
    {

        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.archive', $archive);

        if ($fetch_offer_letter) {
            $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        //$this->db->order_by('documents_assigned.assigned_date', 'desc');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function copy_new_employee_assign_document($data)
    {
        $this->db->insert('documents_assigned', $data);
    }

    function get_assigned_offers($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.document_type', 'offer_letter');

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function copy_new_employee_offer_letter($data)
    {
        $this->db->insert('documents_assigned', $data);
    }

    function is_exist_in_eev_document($document_type, $company_sid, $user_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employee_sid', $user_sid);
        $this->db->where('document_type ', $document_type);
        $this->db->from('eev_documents');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function copy_new_employee_eev_form($data)
    {
        $this->db->insert('eev_documents', $data);
    }

    function fetch_form_for_front_end($form_name, $user_type, $user_sid)
    {
        $this->db->select('*');

        if ($form_name == 'w4') {
            $this->db->where('user_type', $user_type);
            $this->db->where('employer_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('form_w4_original');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'w9') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('applicant_w9form');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } else {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('status', 1);
            $this->db->from('applicant_i9form');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }

        if (sizeof($records_arr) > 0) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function copy_new_employee_w4_form($data)
    {
        $this->db->insert('form_w4_original', $data);
    }

    function copy_new_employee_w9_form($data)
    {
        $this->db->insert('applicant_w9form', $data);
    }

    function copy_new_employee_i9_form($data)
    {
        $this->db->insert('applicant_i9form', $data);
    }

    function get_all_extra_attached_document($user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('applicant_job_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $this->db->from('portal_applicant_attachments');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_extra_attachment($data)
    {
        $this->db->insert('portal_applicant_attachments', $data);
    }

    function get_all_documents_history($user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->from('documents_assigned_history');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_documents_history($data)
    {
        $this->db->insert('documents_assigned_history', $data);
    }

    function get_w4_history($user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->from('form_w4_original_history');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_w4_history($data)
    {
        $this->db->insert('form_w4_original_history', $data);
    }

    function get_w9_history($user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->from('applicant_w9form_history');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_w9_history($data)
    {
        $this->db->insert('applicant_w9form_history', $data);
    }

    function get_i9_history($user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->from('applicant_i9form_history');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_i9_history($data)
    {
        $this->db->insert('applicant_i9form_history', $data);
    }

    function get_resume_history($user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->from('resume_request_logs');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function copy_new_employee_request_log($data)
    {
        $this->db->insert('resume_request_logs', $data);
    }

    function maintain_employee_log_data($data)
    {
        $this->db->insert('employees_transfer_log', $data);
    }

    function get_employee_sid($email, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('email', $email);
        $this->db->where('is_executive_admin', 0);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $result = $records_obj->row_array();
        return $result["sid"];
    }


    //
    function update_user_olddata($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('users', $data);

        //
        $this->db->select('username');
        $this->db->where('sid', $sid);
        $this->db->from('users');
        $records_obj = $this->db->get();
        $result = $records_obj->row_array();
        return $result["username"];
    }

    //
    public function add_terminate_user_table($data)
    {
        $this->db->insert('terminated_employees', $data);
    }

    public function getEmployeeRequests($employeeSid, $companySid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $companySid);
        $this->db->where('employee_sid', $employeeSid);
        $this->db->from('timeoff_requests');
        $records_obj = $this->db->get();
        //
        if (!empty($records_obj)) {
            $result = $records_obj->result_array();
            $records_obj->free_result();
            //
            if (!empty($result)) {
                return $result;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function getCompanyPolicy($policySid)
    {
        $this->db->select('*');
        $this->db->where('sid', $policySid);
        $this->db->from('timeoff_policies');
        $record_obj = $this->db->get();
        //
        if (!empty($record_obj)) {
            $result = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($result)) {
                return $result;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function getPolicyType($typeSid, $companySid)
    {
        $this->db->select('timeoff_category_list_sid');
        $this->db->where('sid', $typeSid);
        $this->db->where('company_sid', $companySid);
        $record_obj = $this->db->get('timeoff_categories');
        //
        if (!empty($record_obj)) {
            $result = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($result)) {
                return $result['timeoff_category_list_sid'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function isPolicyCategoryExist($categorySid, $companySid)
    {
        //
        $this->db->reset_query();
        //
        $this->db->where('timeoff_category_list_sid', $categorySid);
        $this->db->where('company_sid', $companySid);
        $this->db->from('timeoff_categories');
        $ids = $this->db->count_all_results();
        //
        return $ids != 0 ? true : false;
    }

    public function getCategoryTypeSid($categorySid, $companySid)
    {
        $this->db->select('sid');
        $this->db->where('timeoff_category_list_sid', $categorySid);
        $this->db->where('company_sid', $companySid);
        $record_obj = $this->db->get('timeoff_categories');
        //
        if (!empty($record_obj)) {
            $result = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($result)) {
                return $result['sid'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * Get category type
     */
    public function getCategoryTypeById($categorySid, $companySid)
    {
        //
        $table = 'timeoff_categories';
        $where = ['timeoff_category_list_sid' => $categorySid, 'company_sid' => $companySid];

        $countedValues = $this->db
            ->where($where)
            ->count_all_results($table);
        //
        if ($countedValues == 0) {
            return 0;
        }
        // check for double entries
        if ($countedValues > 1) {
            // check for active one
            $activeOne = $this->db
                ->select('sid')
                ->where($where)
                ->where('is_archived', 0)
                ->get($table)
                ->row_array();
            //
            if ($activeOne) {
                return $activeOne['sid'];
            }
        }
        // check for active one
        $anyOne = $this->db
            ->select('sid')
            ->where($where)
            ->get($table)
            ->row_array();
        //
        return $anyOne['sid'];
    }

    public function insertCategory($insertArray)
    {
        //
        $this->db->insert('timeoff_categories', $insertArray);
        //
        return $this->db->insert_id();
    }

    public function isRequestPolicyExist($title, $categoryTypeSid, $companySid)
    {
        //
        $this->db->reset_query();
        //
        $this->db->where('title', $title);
        $this->db->where('type_sid', $categoryTypeSid);
        $this->db->where('company_sid', $companySid);
        $this->db->from('timeoff_policies');
        $ids = $this->db->count_all_results();
        //
        return $ids != 0 ? true : false;
    }

    public function getRequestPolicySid($title, $categoryTypeSid, $companySid)
    {
        $this->db->select('sid');
        $this->db->where('title', $title);
        $this->db->where('type_sid', $categoryTypeSid);
        $this->db->where('company_sid', $companySid);
        $record_obj = $this->db->get('timeoff_policies');
        //
        if (!empty($record_obj)) {
            $result = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($result)) {
                return $result['sid'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getRequestActivePolicyId($title, $categoryTypeSid, $companySid)
    {
        //
        $table = 'timeoff_policies';
        $where = [
            'title' => $title,
            'type_sid' => $categoryTypeSid,
            'company_sid' => $companySid
        ];

        $countedValues = $this->db
            ->where($where)
            ->count_all_results($table);
        //
        if ($countedValues == 0) {
            return 0;
        }
        // check for double entries
        if ($countedValues > 1) {
            // check for active one
            $activeOne = $this->db
                ->select('sid')
                ->where($where)
                ->where('is_archived', 0)
                ->get($table)
                ->row_array();
            //
            if ($activeOne) {
                return $activeOne['sid'];
            }
        }
        // check for active one
        $anyOne = $this->db
            ->select('sid')
            ->where($where)
            ->get($table)
            ->row_array();
        //
        return $anyOne['sid'];
    }

    public function getAssignedEmployees($title, $categoryTypeSid, $companySid)
    {
        $this->db->select('assigned_employees');
        $this->db->where('title', $title);
        $this->db->where('type_sid', $categoryTypeSid);
        $this->db->where('company_sid', $companySid);
        $record_obj = $this->db->get('timeoff_policies');
        //
        if (!empty($record_obj)) {
            $result = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($result)) {
                return $result['assigned_employees'];
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function updateCompanyPolicy($sid, $dataToUpdate)
    {
        $this->db->where('sid', $sid);
        $this->db->update('timeoff_policies', $dataToUpdate);
    }

    public function insertPolicy($insertArray)
    {
        //
        $this->db->insert('timeoff_policies', $insertArray);
        //
        return $this->db->insert_id();
    }

    public function checkTimeOffForSpecificEmployee(
        $companySid,
        $employeeSid,
        $policySid,
        $startDate,
        $endDate
    ) {
        //
        $this->db->reset_query();
        //
        $this->db->where('timeoff_policy_sid', $policySid);
        $this->db->where('company_sid', $companySid);
        $this->db->where('employee_sid', $employeeSid);
        $this->db->where('request_from_date', $startDate);
        $this->db->where('request_to_date', $endDate);
        $this->db->from('timeoff_requests');
        $ids = $this->db->count_all_results();
        //
        return $ids != 0 ? true : false;
    }

    public function insertTimeOffRequest($insertArray)
    {
        $this->db->insert('timeoff_requests', $insertArray);
        return $this->db->insert_id();
    }

    public function getTimeLineComment($requestSid, $approverSid)
    {
        $this->db->select('comment');
        $this->db->where('request_sid ', $requestSid);
        $this->db->where('employee_sid', $approverSid);
        $record_obj = $this->db->get('timeoff_request_timeline');
        //
        if (!empty($record_obj)) {
            $result = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($result)) {
                return $result['comment'];
            } else {
                return '';
            }
        } else {
            return '';
        }
    }

    public function insertRequestTimeLine($insertArray)
    {
        $this->db->insert('timeoff_request_timeline', $insertArray);
    }

    public function getEmployeeBalances($employeeSid)
    {
        $this->db->select('*');
        $this->db->where('user_sid ', $employeeSid);
        $this->db->from('timeoff_balances');
        $records_obj = $this->db->get();
        //
        if (!empty($records_obj)) {
            $result = $records_obj->result_array();
            $records_obj->free_result();
            //
            if (!empty($result)) {
                return $result;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    public function checkbalanceForSpecificEmployee(
        $employeeSid,
        $policySid,
        $balanceType,
        $balanceTime,
        $date
    ) {
        //
        $this->db->reset_query();
        //
        $this->db->where('user_sid', $employeeSid);
        $this->db->where('policy_sid ', $policySid);
        $this->db->where('is_added', $balanceType);
        $this->db->where('added_time', $balanceTime);
        $this->db->where('effective_at', $date);
        $this->db->from('timeoff_balances');
        $ids = $this->db->count_all_results();
        //
        return $ids != 0 ? true : false;
    }

    public function insertTimeOffBalance($insertArray)
    {
        $this->db->insert('timeoff_balances', $insertArray);
    }

    public function insertTrasnferLog($insertArray)
    {
        $this->db->insert('timeoff_transfer_log', $insertArray);
    }

    //
    public function getAllActivePolicies($companyId)
    {
        $this->db
            ->select('
                sid,
                title
            ')
            ->where('company_sid', $companyId)
            ->where('is_archived', 0)
            ->order_by('sort_order', 'ASC');
        //
        return $this->db->get('timeoff_policies')
            ->result_array();
    }
    
    //
    public function getAllCompanyPolicies(int $companyId): array
    {
        $this->db
            ->select('
                sid,
                title,
                is_archived,
                is_entitled_employee,
                assigned_employees
            ')
            ->where('company_sid', $companyId)
            ->order_by('is_archived', 'ASC');
        //
        return $this->db->get('timeoff_policies')
            ->result_array();
    }

    //
    public function getPoliciesByCompanyRequests(int $companyId, array $employeeIds): array
    {
        //
        $policyIds = $this->db
            ->select(
                'distinct(timeoff_policy_sid) as timeoff_policy_sid'
            )
            ->where('company_sid', $companyId)
            ->where_in('employee_sid', $employeeIds)
            ->get('timeoff_requests')
            ->result_array();
        //
        if (empty($policyIds)) {
            return [];
        }
        //
        return
            $this->db
            ->select('
                timeoff_policies.sid,
                timeoff_policies.title,
                timeoff_policies.policy_category_type,
                timeoff_policies.is_archived
            ')
            ->where('company_sid', $companyId)
            ->where_in('sid', array_column($policyIds, 'timeoff_policy_sid'))
            ->order_by('sort_order', 'ASC')
            ->get('timeoff_policies')
            ->result_array();
    }

    public function getAssignedDocumentId (int $companyId, array $document)
    {
        //
        $documentId = 0;
        //
        $record = $this->db
            ->select('sid')
            ->where('company_sid', $companyId)
            ->where('sid', $document['document_sid'])
            ->get('documents_management')
            ->row_array();
        //
        if (!$record) {
            //
            $result = $this->db
                 ->select('sid')
                 ->where('company_sid', $companyId)
                 ->where('document_title', $document['document_title'])
                 ->where('document_type', $document['document_type'])
                 ->get('documents_management')
                 ->row_array();
            //     
            if ($result) {
                $documentId = $result['sid'];
            } else {
                
                // Fetch document details
                $result = $this->db
                    ->select('*')
                    ->where('sid', $document['document_sid'])
                    ->get('documents_management');
                //
                $documentNew = $result->row_array();
                $result = $result->free_result();
                //
                if(!sizeof($documentNew)) return $documentId;
                //
                $fromCompanyId = $documentNew['company_sid'];
                //
                unset(
                    $documentNew['sid'],
                    $documentNew['date_created']
                );
                //
                $documentNew['employer_sid'] = 0;
                $documentNew['unique_key'] = generateRandomString(32);
                $documentNew['company_sid'] = $companyId;
                //
                if($documentNew['document_type'] == 'uploaded'){
                    
                    // Re-upload file to AWS
                    $basePath = ROOTPATH.'assets/tmp/'.strtolower(preg_replace('/\s+/', '_', $documentNew['company_sid'])).'-document/';
                    $fileName = generateRandomString(3).'-'.$documentNew['uploaded_document_s3_name'];
                    $filePath = $basePath.$fileName;
                    if(!file_exists($basePath)) mkdir($basePath, 0777, true);
                    @file_put_contents($filePath, @file_get_contents(AWS_S3_BUCKET_URL.$documentNew['uploaded_document_s3_name']));
                    //
                    downloadFileFromAWS($filePath, AWS_S3_BUCKET_URL.$documentNew['uploaded_document_s3_name']);
                    // Upload file to AWS
                    $this->load->library('aws_lib');
                    $options = [
                        'Bucket' => AWS_S3_BUCKET_NAME,
                        'Key' => $fileName,
                        'Body' => file_get_contents($filePath),
                        'ACL' => 'public-read',
                        'ContentType' => getMimeType($filePath)
                    ];
                    //
                    if ($options['Body'] == '') {
                        return $documentId;
                    }
                    //
                    $this->aws_lib->put_object($options);
                    $documentNew['uploaded_document_s3_name'] = $fileName;
                    unlink($filePath);
                }
                //
                $this->db->insert('documents_management', $documentNew);
                $documentId = $this->db->insert_id();
                
                
                $insertArray = array();
                $insertArray['admin_sid'] = 1;
                $insertArray['to_company_sid'] = $companyId;
                $insertArray['new_document_sid'] = $documentId;
                $insertArray['from_company_sid'] = $fromCompanyId;
                $insertArray['document_sid'] = $document['document_sid'];
                //
                $this->db->insert('copy_document_track', $insertArray);
            }        
        } else if ($record) {
            $documentId = $record['sid'];
        }
        //
        return $documentId;
    }

    public function getAssignedOfferLetterId (int $companyId, array $document)
    {
        //
        $offerLetterId = 0;
        //
        $record = $this->db
            ->select('sid')
            ->where('company_sid', $companyId)
            ->where('sid', $document['document_sid'])
            ->get('offer_letter')
            ->row_array();
        //
        if (!$record) {
            //
            $result = $this->db
                 ->select('sid')
                 ->where('company_sid', $companyId)
                 ->where('letter_name', $document['document_title'])
                 ->where('letter_type', $document['offer_letter_type'])
                 ->get('offer_letter')
                 ->row_array();
            //     
            if ($result) {
                $offerLetterId = $result['sid'];
            } else {
                // Fetch document details
                $result = $this->db
                    ->select('*')
                    ->where('sid', $document['document_sid'])
                    ->get('offer_letter');
                //
                $offerLetterNew = $result->row_array();
                $result = $result->free_result();
                //
                if(!sizeof($offerLetterNew)) return $offerLetterId;
                //
                $fromCompanyId = $offerLetterNew['company_sid'];
                //
                unset(
                    $offerLetterNew['sid'],
                    $offerLetterNew['signers']
                );
                //
                $offerLetterNew['employer_sid'] = 0;
                $offerLetterNew['company_sid'] = $companyId;
                //
                if($offerLetterNew['document_type'] == 'uploaded'){
                    // Re-upload file to AWS
                    $basePath = ROOTPATH.'assets/tmp/'.strtolower(preg_replace('/\s+/', '_', $offerLetterNew['company_sid'])).'-document/';
                    $fileName = generateRandomString(3).'-'.$offerLetterNew['uploaded_document_s3_name'];
                    $filePath = $basePath.$fileName;
                    if(!file_exists($basePath)) mkdir($basePath, 0777, true);
                    @file_put_contents($filePath, @file_get_contents(AWS_S3_BUCKET_URL.$offerLetterNew['uploaded_document_s3_name']));
                    //
                    downloadFileFromAWS($filePath, AWS_S3_BUCKET_URL.$offerLetterNew['uploaded_document_s3_name']);
                    // Upload file to AWS
                    $this->load->library('aws_lib');
                    $options = [
                        'Bucket' => AWS_S3_BUCKET_NAME,
                        'Key' => $fileName,
                        'Body' => file_get_contents($filePath),
                        'ACL' => 'public-read',
                        'ContentType' => getMimeType($filePath)
                    ];
                    //
                    if($options['Body'] == ''){
                        return $offerLetterId;
                    }
                    //
                    $this->aws_lib->put_object($options);
                    $offerLetterNew['uploaded_document_s3_name'] = $fileName;
                    unlink($filePath);
                }
                $this->db->insert('offer_letter', $offerLetterNew);
                $offerLetterId = $this->db->insert_id();
                
                //
                $insertArray = array();
                $insertArray['admin_sid'] = 1;
                $insertArray['to_company_sid'] = $companyId;
                $insertArray['new_document_sid'] = $offerLetterId;
                $insertArray['from_company_sid'] = $fromCompanyId;
                $insertArray['document_sid'] = $document['document_sid'];
                $insertArray['document_type'] = 'offer_letter';
                //
                $this->db->insert('copy_document_track', $insertArray);
            }    
        } else if ($record) {
            $offerLetterId = $record['sid'];
        }
        //
        return $offerLetterId;
    }

    public function copyEmployeeLMSCourses (array $data) {
        //
        if ($this->checkIfAppIsEnabled(MODULE_LMS, $data['oldCompanyId']) && $this->checkIfAppIsEnabled(MODULE_LMS, $data['newCompanyId']))  {
            //
            // $lmsJobTitleId = $this->getEmployeeJobTitle($data['oldEmployeeId']);
            //
            $courses = $this->getEmployeeCourses($data['oldEmployeeId'], $data['oldCompanyId']);
            //
            //
            if ($courses) {
                foreach ($courses as $course) {
                    // check this course exist into primary company
                    $newCourseId = $this->checkCourseExistIntoPrimaryCompany($course['course_sid'], $data['oldCompanyId'], $data['newCompanyId']);
                    //
                    if (
                        !$this
                        ->db
                        ->where([
                            "course_sid" => $newCourseId,
                            "company_sid" => $data['newCompanyId'],
                            "employee_sid" => $data['newEmployeeId']
                        ])
                        ->count_all_results("lms_employee_course")
                    ) {
                        $oldCourseId = $course['sid'];
                        unset($course['sid']);
                        $course['course_sid'] = $newCourseId;
                        $course['company_sid'] = $data['newCompanyId'];
                        $course['employee_sid'] = $data['newEmployeeId'];
                        //
                        $this->db->insert('lms_employee_course', $course);
                        //
                        $courseHistory = $this->getEmployeeCourseHistory($oldCourseId, $data['oldEmployeeId'], $data['oldCompanyId']);
                        //
                        if ($courseHistory) {
                            foreach ($courseHistory as $history) {
                                unset($history['sid']);
                                $history['course_sid'] = $newCourseId;
                                $history['company_sid'] = $data['newCompanyId'];
                                $history['employee_sid'] = $data['newEmployeeId'];
                                //
                                $this->db->insert('lms_employee_course_history', $history);
                            }
                        }
                    }
                }
            }
        }
    }

    private function checkIfAppIsEnabled(
        $ctl,
        $companyId
    ) {
        // Get the called controller name
        $ctl = trim(strtolower(preg_replace('/[^a-zA-Z]/', '', $ctl ? $ctl : '')));
        // If not a controller then pass
        if ($ctl == '') return;
        //
        $a = $this
            ->db
            ->select('sid, stage, is_disabled, is_ems_module')
            ->where('module_slug', $ctl)
            ->limit(1)
            ->get('modules');
        //
        $b = $a->row_array();
        $a->free_result();
        // If module doesn't exists then no need to continue
        if (!sizeof($b)) return true;

        // Lets check if module is activated against logged in company
        $a = $this
            ->db
            ->where('module_sid', $b['sid'])
            ->where('company_sid', $companyId)
            ->where('is_active', 1)
            ->get('company_modules');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        if (!sizeof($b)) {
            return false;
        }
        //
        return true;
    }

    private function getEmployeeJobTitle ($employeeId) {
        //
        $a = $this
            ->db
            ->select('lms_job_title')
            ->where('sid', $employeeId)
            ->get('users');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        if (empty($b['lms_job_title'])) {
            return 0;
        } else {
            return $b['lms_job_title'];
        }
    }

    private function getEmployeeCourses ($employeeId, $companyId) {
        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $result = $this->db->get('lms_employee_course')->result_array();
        //
        return $result;
    }

    private function getEmployeeCourseHistory ($courseId, $employeeId, $companyId) {
        $this->db->select('*');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employee_sid', $employeeId);
        $this->db->where('employee_sid', $employeeId);
        $result = $this->db->get('lms_employee_course_history')->result_array();
        //
        return $result;
    }

    function checkCourseExistIntoPrimaryCompany($courseId, $fromCompanyId, $toCompanyId)
    {
        $this->db->select('default_course_sid');
        $this->db->where('assigned_course_sid', $courseId);
        $this->db->where('assign_company_sid', $fromCompanyId);
        //
        $this->db->from('lms_assign_course_log');
        $record_obj = $this->db->get();
        $defaultCourseId = $record_obj->row_array()['default_course_sid'];
        $record_obj->free_result();
        //
        $this->db->select('assigned_course_sid');
        $this->db->where('default_course_sid', $defaultCourseId);
        $this->db->where('assign_company_sid', $toCompanyId);
        //
        $this->db->from('lms_assign_course_log');
        $record_obj = $this->db->get();
        $assignCourseId = $record_obj->row_array()['assigned_course_sid'];
        $record_obj->free_result();
        //
        if ($assignCourseId) {
            return $assignCourseId;
        } else {
            $this->db->select('*');
            $this->db->where('sid', $defaultCourseId);
            $defaultCourse = $this->db->get('lms_default_courses')->row_array();
            //
            unset($defaultCourse['sid']);
            $defaultCourse['company_sid'] = $toCompanyId;
            //
            $this->db->insert('lms_default_courses', $defaultCourse);
            $newDefaultId = $this->db->insert_id();
            //
            $dataToInsert = [];
            $dataToInsert['default_company_sid'] = 0;
            $dataToInsert['assign_company_sid'] = $toCompanyId;
            $dataToInsert['default_course_sid'] = $defaultCourseId;
            $dataToInsert['assigned_course_sid'] = $newDefaultId;
            //
            $this->db->insert('lms_assign_course_log', $dataToInsert);
            //
            $this->db->select('job_title_id');
            $this->db->where('lms_default_courses_sid', $courseId);
            $courseJobTitles = $this->db->get('lms_default_courses_job_titles')->result_array();
            //
            foreach ($courseJobTitles as $jobTitle) {
                $jobTitleToInsert = [];
                $jobTitleToInsert['lms_default_courses_sid'] = $newDefaultId;
                $jobTitleToInsert['job_title_id'] = $jobTitle['job_title_id'];
                //
                $this->db->insert('lms_default_courses_job_titles', $jobTitleToInsert);
            }
            //f
            return $newDefaultId;
        }
        //
    }


}

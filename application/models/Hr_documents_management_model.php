<?php

class Hr_documents_management_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all_documents($company_sid, $archive_status = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_specific', 0);

        if ($archive_status !== null) {
            $this->db->where('archive', $archive_status);
        }

        $this->db->order_by('sort_order', 'ASC');
        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    public function get_all_offer_letters($company_sid, $archive = null, $emploeeSid = false)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('is_specific', 0);
        // For specific employee
        if ($emploeeSid) $this->db->or_where('is_specific', $emploeeSid);
        $this->db->group_end();

        if (!is_null($archive)) {
            $this->db->where('archive', $archive);
        }

        $this->db->from('offer_letter');
        $this->db->order_by('sort_order', 'ASC');
        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_document_record($data_to_insert)
    {
        $this->db->insert('documents_management', $data_to_insert);
        return $this->db->insert_id();
    }

    public function add_new_offer_letter($offer_letter_data)
    {
        $this->db->insert('offer_letter', $offer_letter_data);
        return $this->db->insert_id();
    }

    function get_hr_document_details($company_sid, $sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('documents_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function change_eeoc_forms_status($sid)
    {
        $data_to_update = array();
        $data_to_update['eeo_form'] = 'No';
        $this->db->where('sid', $sid);
        $this->db->update('portal_applicant_jobs_list', $data_to_update);
    }

    function delete_eeoc_forms_info($sid)
    {
        $this->db->where('application_sid', $sid);
        $this->db->delete('portal_eeo_form');
    }

    function eeoc_forms_history($eeoc_form_history)
    {
        $this->db->insert('portal_eeo_form_history', $eeoc_form_history);
    }

    function update_documents($sid, $data, $table_name)
    {
        $this->db->where('sid', $sid);
        $this->db->update($table_name, $data);
        // updates the required field
        if ($table_name == 'documents_management' && isset($data['is_required'])) {
            $updatedata = [];
            $updatedata['is_required'] = $data['is_required'];

            $this->db->where('document_sid', $sid);
            $this->db->where('document_type <> ', 'offer_letter');
            $this->db->update('documents_assigned', $updatedata);
        }
    }

    function is_assigned_authorized_document_to_me($company_sid, $document_sid, $assigned_to_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_assigned_sid', $document_sid);
        $this->db->where('assigned_to_sid', $assigned_to_sid);
        // $this->db->where('assigned_status', 1);

        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    function update_assigned_authorized_document($document_assigned_sid, $assigned_to_sid, $data_to_update)
    {
        $this->db->where('document_assigned_sid', $document_assigned_sid);
        $this->db->where('assigned_to_sid', $assigned_to_sid);
        // $this->db->where('assigned_status', 1);
        $this->db->update('authorized_document_assigned_manager', $data_to_update);
    }

    function reassign_authorized_document($document_sid, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('authorized_document_assigned_manager', $data_to_update);
    }

    function assign_authorized_document_to_user($data_to_insert)
    {
        $this->db->insert('authorized_document_assigned_manager', $data_to_insert);
    }

    function update_generated_documents($document_sid, $user_sid, $user_type, $data)
    {
        $this->db->where('sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('documents_assigned', $data);
    }

    function update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function assign_revoke_assigned_documents($document_sid, $document_type, $user_sid, $user_type, $data)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('document_type', $document_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('documents_assigned', $data);
        //        change status of history table too
        $this->db->where('document_sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('document_type', $document_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('documents_assigned_history', $data);
    }

    function assign_revoke_assigned_offer_documents($document_type, $user_sid, $user_type, $data)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('document_type', $document_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('documents_assigned', $data);
    }

    function check_assigned_document($document_sid, $user_sid, $user_type)
    {
        $this->db->select('sid');
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('document_sid', $document_sid);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_offer_letter_details($company_sid, $sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('offer_letter');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function fetch_all_company_managers($company_sid, $employer_sid)
    {
        $ignore[] = '';
        // $ignore[] = $employer_sid;
        $this->db->select('sid, first_name, last_name, access_level_plus, pay_plan_flag, job_title, access_level, is_executive_admin, concat(first_name," ",last_name) as employee_name');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->where_not_in('sid', $ignore);
        $this->db->order_by('employee_name', 'ASC');

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    public function set_offer_letter_archive_status($offer_letter_sid, $archive_status)
    {
        $this->db->where('sid', $offer_letter_sid);
        $this->db->set('archive', $archive_status);
        $this->db->update('offer_letter');
    }

    public function delete_offer_letter($offer_letter_sid)
    {
        $this->db->where('sid', $offer_letter_sid);
        $this->db->delete('offer_letter');
    }

    function get_applicant_information($company_sid, $applicant_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('pictures');
        $this->db->select('phone_number as phone');
        $this->db->select('verification_key');
        $this->db->select('employee_status');
        $this->db->select('employee_type');
        $this->db->select('employer_sid AS parent_sid');
        $this->db->select('desired_job_title AS job_title');
        $this->db->where('employer_sid', $company_sid);
        $this->db->where('sid', $applicant_sid);

        $record_obj = $this->db->get('portal_job_applications');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_authorized_document_already_assigned($company_sid, $document_sid, $manager_sid)
    {
        $this->db->select('sid, assigned_to_signature');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_assigned_sid', $document_sid);
        $this->db->where('assigned_to_sid', $manager_sid);

        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function getAllCompanyInactiveEmployee($companySid)
    {
        $a = $this->db
            ->select('
            sid
        ')
            ->where('parent_sid', $companySid)
            ->where('active <>', 1)
            ->or_where('terminated_status <>', 0)
            ->order_by('concat(first_name,last_name)', 'ASC', false)
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

    function get_all_assigned_auth_documents($company_sid, $employer_sid, $ine = [], $ina = [])
    {
        $this->db->select('documents_assigned.*, authorized_document_assigned_manager.assigned_by_date');
        $this->db->where('authorized_document_assigned_manager.company_sid', $company_sid);
        $this->db->where('authorized_document_assigned_manager.assigned_to_sid', $employer_sid);
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->group_start();
        $this->db->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false);
        $this->db->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false);
        $this->db->group_end();
        $this->db->join('documents_assigned', 'documents_assigned.sid = authorized_document_assigned_manager.document_assigned_sid', 'inner');

        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $inactive_employee_sid = $ine ? $ine : $this->getAllCompanyInactiveEmployee($company_sid);
            //
            $inactive_applicant_sid = $ina ? $ina : $this->getAllCompanyInactiveApplicant($company_sid);
            //
            foreach ($record_arr as $d_key => $aut_doc) {
                if (in_array($aut_doc['user_sid'], $inactive_employee_sid) && $aut_doc['user_type'] == 'employee') {
                    unset($record_arr[$d_key]);
                } else if (in_array($aut_doc['user_sid'], $inactive_applicant_sid) && $aut_doc['user_type'] == 'applicant') {
                    unset($record_arr[$d_key]);
                }
            }
            //
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_paginate_auth_documents($company_sid, $employer_sid, $limit = null, $start = null, $ine = [], $ina = [])
    {
        $this->db->select('documents_assigned.*, authorized_document_assigned_manager.assigned_by_date, authorized_document_assigned_manager.is_archive as  assign_archive, authorized_document_assigned_manager.archived_by_date, authorized_document_assigned_manager.archived_by');
        $this->db->where('authorized_document_assigned_manager.company_sid', $company_sid);
        $this->db->where('authorized_document_assigned_manager.assigned_to_sid', $employer_sid);
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->group_start();
        $this->db->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false);
        $this->db->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false);
        $this->db->group_end();
        if ($limit != null) {
            $this->db->limit($limit, $start);
        }
        $this->db->join('documents_assigned', 'documents_assigned.sid = authorized_document_assigned_manager.document_assigned_sid', 'inner');
        // 
        $this->db->order_by('documents_assigned.authorized_signature', 'ASC', false);
        $this->db->order_by('assigned_by_date', 'DESC', false);
        //

        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $inactive_employee_sid = $ine ? $ine : $this->getAllCompanyInactiveEmployee($company_sid);
            //
            $inactive_applicant_sid = $ina ? $ina : $this->getAllCompanyInactiveApplicant($company_sid);
            //
            foreach ($record_arr as $d_key => $aut_doc) {
                if (in_array($aut_doc['user_sid'], $inactive_employee_sid) && $aut_doc['user_type'] == 'employee') {
                    unset($record_arr[$d_key]);
                } else if (in_array($aut_doc['user_sid'], $inactive_applicant_sid) && $aut_doc['user_type'] == 'applicant') {
                    unset($record_arr[$d_key]);
                }
            }
            //
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_assign_authorized_document($company_sid, $assign_document_sid)
    {
        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive,documents_management.visible_to_payroll');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('documents_assigned.sid', $assign_document_sid);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->where('status', 1);

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'inner');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_assign_authorized_offer_letter($company_sid, $assign_document_sid)
    {
        $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('documents_assigned.sid', $assign_document_sid);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->where('status', 1);

        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_employee_information($company_sid, $employee_sid)
    {
        $this->db->select('sid,document_sent_on');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->select('email');
        $this->db->select('PhoneNumber as phone');
        $this->db->select('verification_key');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('sid', $employee_sid);

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_archive_assigned_documents($company_sid, $user_type, $user_sid = null, $pp_flag = 0)
    {
        $payroll_sids = $this->get_payroll_documents_sids();
        $documents_management_sids = $payroll_sids['documents_management_sids'];
        $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

        $this->db->select('documents_assigned.sid, 
            documents_assigned.document_type, 
            documents_assigned.document_sid, 
            documents_assigned.document_title, 
            documents_assigned.document_description, 
            documents_assigned.assigned_date, documents_assigned.document_original_name, documents_assigned.visible_to_payroll, documents_assigned.document_s3_name, documents_assigned.archive as user_archived, documents_management.archive as company_archived');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('documents_assigned.user_type', $user_type);
        $this->db->where('documents_assigned.user_sid', $user_sid);

        $this->db->group_start();
        $this->db->where('documents_assigned.archive', 1);
        $this->db->or_where('documents_management.archive', 1);
        $this->db->group_end();

        $this->db->group_start();
        $this->db->where('documents_assigned.user_consent', 0);
        $this->db->or_where('documents_assigned.document_sid', 0);
        $this->db->group_end();

        if ($pp_flag) {
            $this->db->group_start();
            $this->db->where('documents_management.visible_to_payroll', 1);
            $this->db->or_where('documents_assigned.visible_to_payroll', 1);

            if (!empty($documents_management_sids)) {
                $this->db->or_where_in('documents_management.sid', $documents_management_sids);
            }

            if (!empty($documents_assigned_sids)) {
                $this->db->or_where_in('documents_assigned.sid', $documents_assigned_sids);
            }
            $this->db->group_end();
        }

        $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_user_assigned_manual_documents($company_sid, $user_type, $user_sid = null, $pp_flag = 0)
    {
        if ($pp_flag) {
            $payroll_sids = $this->get_payroll_documents_sids();
            $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];
        }

        $this->db->select('sid, document_type, document_sid, document_title, assigned_date, document_original_name, visible_to_payroll, document_s3_name');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('archive', 0);
        $this->db->where('status', 1);
        $this->db->where('document_sid', 0);
        $this->db->where('document_type', 'uploaded');

        if ($pp_flag) {
            $this->db->group_start();
            $this->db->where('visible_to_payroll', 1);

            if (!empty($documents_assigned_sids)) {
                $this->db->or_where_in('sid', $documents_assigned_sids);
            }
            $this->db->group_end();
        }

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1, $archive = 0, $pp_flag = 0, $ems = 0, $adf = 0)
    {
        if ($pp_flag) {
            $payroll_sids = $this->get_payroll_documents_sids();
            $documents_management_sids = $payroll_sids['documents_management_sids'];
            $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];
        }

        $this->db->select('
            documents_assigned.*,
            documents_management.acknowledgment_required,
            documents_management.download_required,
            documents_management.signature_required,
            documents_management.archive as company_archive,
            documents_management.visible_to_payroll, 
            documents_management.is_available_for_na,  
            documents_management.is_specific
        ');
        if (ASSIGNEDOCIMPL) $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required, documents_management.is_available_for_na');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        //  $this->db->where('documents_assigned.archive', $archive);
        //
        if ($adf == 1) {
            $this->db->where('approval_process', 0);
        }
        //
        if ($ems == 1) {
            // $this->db->where('documents_assigned.is_confidential', 0);
        }

        if ($fetch_offer_letter) {
            $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        if ($pp_flag) {
            $this->db->group_start();
            $this->db->where('documents_management.visible_to_payroll', 1);
            $this->db->or_where('documents_assigned.visible_to_payroll', 1);

            if (!empty($documents_management_sids)) {
                $this->db->or_where_in('documents_management.sid', $documents_management_sids);
            }

            if (!empty($documents_assigned_sids)) {
                $this->db->or_where_in('documents_assigned.sid', $documents_assigned_sids);
            }
            $this->db->group_end();
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        //$this->db->order_by('documents_assigned.assigned_date', 'desc');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (sizeof($record_arr)) {
            foreach ($record_arr as $k => $v) {
                $record_arr[$k]['letter_type'] = '';
                if ($v['document_type'] != 'offer_letter') continue;
                //
                $a = $this->db
                    ->select('letter_type')
                    ->where('sid', $v['document_sid'])
                    ->get('offer_letter');
                //
                $b = $a->row_array();
                $a = $a->free_result();
                //
                $record_arr[$k]['letter_type'] = $b['letter_type'];
            }
        }

        return $record_arr;
    }

    function get_archived_manual_documents($company_sid, $user_type, $user_sid = null, $status = 1, $pp_flag = 0)
    {
        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.archive', 1);
        $this->db->where('documents_assigned.document_type <>', 'offer_letter');

        if ($status) {
            $this->db->where('status', $status);
        }

        if ($pp_flag) {
            $this->db->where('documents_assigned.visible_to_payroll', 1);
        }


        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_payroll_documents_sids()
    {
        $this->db->select('document_sid, document_type');
        $this->db->where('category_sid', PP_CATEGORY_SID);
        $record_obj = $this->db->get('documents_2_category');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        $documents_management_sids = array();
        $documents_assigned_sids = array();
        $return_array = array();

        foreach ($record_arr as $key => $row) {
            if ($row['document_type'] == 'documents_management') {
                array_push($documents_management_sids, $row['document_sid']);
            } else {
                array_push($documents_assigned_sids, $row['document_sid']);
            }
        }

        $return_array['documents_management_sids'] = $documents_management_sids;
        $return_array['documents_assigned_sids'] = $documents_assigned_sids;

        return $return_array;
    }

    function get_all_assign_payroll_document($company_sid, $user_type, $user_sid)
    {
        $payroll_sids = $this->get_payroll_documents_sids();
        $documents_management_sids = $payroll_sids['documents_management_sids'];
        $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];

        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.company_sid', $company_sid);

        if (!empty($documents_management_sids) && !empty($documents_assigned_sids)) {
            $this->db->group_start();
            $this->db->where_in('documents_assigned.document_sid', $documents_management_sids);
            $this->db->or_where_in('documents_assigned.sid', $documents_assigned_sids);
            $this->db->group_end();
        } else if (!empty($documents_management_sids)) {
            $this->db->where_in('documents_assigned.document_sid', $documents_management_sids);
        } else if (!empty($documents_assigned_sids)) {
            $this->db->where_in('documents_assigned.sid', $documents_assigned_sids);
        }

        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->where('documents_assigned.document_type <>', 'offer_letter');

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');

        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr) && (!empty($documents_management_sids) || !empty($documents_assigned_sids))) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_all_visible_payroll_document($company_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->where('archive', 0);
        $this->db->where('visible_to_payroll', 1);
        $this->db->where('document_type <>', 'offer_letter');

        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_all_company_offers_letters($company_sid, $archive_status, $employeeSid = false)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('is_specific', 0);
        if ($employeeSid) $this->db->or_where('is_specific', $employeeSid);
        $this->db->group_end();

        if ($archive_status !== null) {
            $this->db->where('archive', $archive_status);
        }

        $this->db->order_by('sort_order', 'ASC');
        $records_obj = $this->db->get('offer_letter');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_assigned_offer_letter($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required, 
        offer_letter.is_available_for_na,
        offer_letter.allowed_teams,
        offer_letter.allowed_departments,
        offer_letter.allowed_employees,
        offer_letter.signers');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('documents_assigned.user_type', $user_type);
        $this->db->where('documents_assigned.user_sid', $user_sid);
        $this->db->where('documents_assigned.document_type', 'offer_letter');
        $this->db->where('status', $status);
        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_assigned_offers($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        // $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required, 
        //     offer_letter.is_available_for_na,
        //     offer_letter.allowed_teams,
        //     offer_letter.allowed_departments,
        //     offer_letter.allowed_employees,
        //     offer_letter.signers
        // ');
        $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required
        ');
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

    function get_current_assigned_offer_letter($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        // $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required, offer_letter.letter_type, 
        // offer_letter.is_available_for_na,
        // offer_letter.allowed_teams,
        // offer_letter.allowed_departments,
        // offer_letter.allowed_employees,
        // offer_letter.signers');
        $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required, offer_letter.letter_type');
        if (ASSIGNEDOCIMPL) $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.document_type', 'offer_letter');
        $this->db->where('documents_assigned.archive', 0);

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $this->getManagersList($record_arr);
            // return array();
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_manual_assigned_offers($company_sid, $user_type, $user_sid = null, $status = 1, $admin = 1)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('manual_document_type', 'offer_letter');

        if ($status) {
            $this->db->where('status', $status);
        }

        if ($admin == 0) {
            $this->db->where('document_type <>', 'confidential');
        }

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_assigned_offer_letter_history($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        $this->db->select('documents_assigned_history.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required');
        $this->db->where('documents_assigned_history.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned_history.document_type', 'offer_letter');

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('documents_assigned_history.assigned_date', 'DESC');
        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned_history.doc_sid', 'left');
        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_assigned_document($user_type, $user_sid, $document_sid, $doc = NULL)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.sid', $document_sid);
        $this->db->where('documents_assigned.status', 1);

        if ($doc == 'o') {
            $this->db->select('documents_assigned.*,offer_letter.acknowledgment_required,offer_letter.download_required,offer_letter.signature_required, offer_letter.letter_type');
            $this->db->where('documents_assigned.document_type', 'offer_letter');
            $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');
        } else {
            $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required, documents_assigned.document_description');
            $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        }
        //
        if (ASSIGNEDOCIMPL) $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required');

        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_authorized_signature_document($user_type, $user_sid, $document_sid)
    {
        $this->db->where('sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);

        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_submitted_generated_document($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_original_document($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('documents_management');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_submitted_document($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_offer_latter($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('offer_letter');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_document_record($user_type, $user_sid, $document_sid)
    {
        $this->db->select('document_title,document_description');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        $record_obj = $this->db->get('documents_assigned');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_document_attached_video($document_sid)
    {
        $this->db->select('video_required,video_source,video_url');
        $this->db->where('sid', $document_sid);
        $record_obj = $this->db->get('documents_management');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_document_history_record($user_type, $user_sid, $document_sid, $history_sid)
    {
        $this->db->select('document_title,document_description');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        $this->db->where('sid', $history_sid);
        $record_obj = $this->db->get('documents_assigned_history');
        $records_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    public function sign_document($company_sid, $user_type, $user_sid, $document_assignment_sid, $data_to_update)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('sid', $document_assignment_sid);
        $this->db->update('documents_assignment', $data_to_update);
    }

    function update_download_status($user_type, $user_sid, $document_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('sid', $document_sid);
        $this->db->where('status', 1);
        $this->db->set('downloaded', 1);
        $this->db->set('downloaded_date', date('Y-m-d H:i:s'));
        $this->db->update('documents_assigned');
    }

    function update_acknowledge_status($user_type, $user_sid, $document_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('sid', $document_sid);
        $this->db->where('status', 1);
        $this->db->set('acknowledged', 1);
        $this->db->set('acknowledged_date', date('Y-m-d H:i:s'));
        $this->db->update('documents_assigned');
    }

    function save_doc_history($data)
    {
        $this->db->insert('document_management_history', $data);
        return $this->db->insert_id();
    }

    public function fetch_form($form_name, $user_type, $user_sid)
    {
        $this->db->select('*');

        if ($form_name == 'w4') {
            $this->db->where('user_type', $user_type);
            $this->db->where('employer_sid', $user_sid);
            //            $this->db->where('status', 1);
            $this->db->from('form_w4_original');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'w9') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            //            $this->db->where('status', 1);
            $this->db->from('applicant_w9form');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } else {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            //            $this->db->where('status', 1);
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

    function fetch_track_record($document_sid, $type)
    {
        //
        $this->db->where('document_sid', $document_sid);
        $this->db->where('document_type', $type);
        $this->db->from('verification_documents_track');
        $this->db->order_by('sid', 'desc');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function fetch_form_history($form_name, $user_type, $user_sid)
    {
        $records_arr = [];
        $this->db->select('*');

        if ($form_name == 'w4') {
            $this->db->where('user_type', $user_type);
            $this->db->where('employer_sid', $user_sid);
            $this->db->from('form_w4_original_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'w9') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->from('applicant_w9form_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'i9') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->from('applicant_i9form_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }

        if ($form_name == 'eeoc') {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->from('portal_eeo_form_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }

        if (sizeof($records_arr) > 0) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function getUserVarificationHistoryDoc($sid, $table)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->from($table);

        $records_obj = $this->db->get();
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (sizeof($records_arr) > 0) {
            return $records_arr;
        } else {
            return array();
        }
    }

    public function fetch_form_for_front_end($form_name, $user_type, $user_sid)
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

    function get_company_detail($sid)
    {
        $this->db->where('sid', $sid);
        $result = $this->db->get('users')->result_array();

        if (!empty($result)) {
            return $result[0];
        }
    }

    function get_eeo_form_status($sid)
    {
        $this->db->select('eeo_form');
        $this->db->where('portal_job_applications_sid', $sid);
        $result = $this->db->get('portal_applicant_jobs_list')->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0]['eeo_form'];
        }

        return $return_data;
    }

    function get_eeo_form_info($sid, $type)
    {
        //
        checkAndSetEEOCForUser($sid, $type);
        //
        $this->db->select('*');
        $this->db->where('application_sid', $sid);
        $this->db->where('users_type', $type);
        $this->db->order_by('sid', 'DESC');
        $result = $this->db->get('portal_eeo_form')->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }

    function get_applicants_details($sid)
    {
        $this->db->select('sid, employer_sid as company_sid, first_name, last_name, email, address, city, country, state, zipcode, phone_number, pictures');
        $this->db->where('sid', $sid);
        $this->db->from('portal_job_applications');
        $data = array();

        $records_obj = $this->db->get();
        $result = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($result)) {
            $data = $result[0];
        }

        return $data;
    }

    function getApplicantAverageRating($app_id, $users_type = NULL, $date = NULL)
    {
        $this->db->where('applicant_job_sid', $app_id);

        if ($users_type != NULL) {
            $this->db->where('users_type', $users_type);
        }

        if ($date != NULL) { // get all rating after his/her hiring date.
            $this->db->where('date_added >', $date);
        }

        $this->db->from('portal_applicant_rating');
        $rows = $this->db->count_all_results();

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

    function insert_documents_assignment_record($data_to_insert)
    {
        $this->db->insert('documents_assigned', $data_to_insert);
        return $this->db->insert_id();
    }

    function insert_documents_assignment_flow($data_to_insert)
    {
        $this->db->insert('portal_document_assign_flow', $data_to_insert);
        return $this->db->insert_id();
    }

    function insert_assigner_employee($data_to_insert)
    {
        $this->db->insert('portal_document_assign_flow_employees', $data_to_insert);
        return $this->db->insert_id();
    }

    function insert_documents_assignment_record_history($data_to_insert)
    {
        $this->db->insert('documents_assigned_history', $data_to_insert);
    }

    function insert_w4_form_record($data_to_insert)
    {
        $this->db->insert('form_w4_original', $data_to_insert);
    }

    function w4_forms_history($data_to_insert)
    {
        $this->db->insert('form_w4_original_history', $data_to_insert);
    }

    function insert_w9_form_record($data_to_insert)
    {
        $this->db->insert('applicant_w9form', $data_to_insert);
    }

    function w9_forms_history($data_to_insert)
    {
        $this->db->insert('applicant_w9form_history', $data_to_insert);
    }

    function get_eev_uploaded_document($document_sid)
    {
        $this->db->where('sid', $document_sid);
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

    function is_w4_form_assign($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function is_w9_form_assign($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function is_i9_form_assign($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function is_eeoc_document_assign($user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')['portal_detail']['eeo_form_profile_status']) {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->where('is_expired', 0);
            $this->db->where('status', 1);
            $this->db->from('portal_eeo_form');
            //
            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();

            if (!empty($records_arr)) {
                return $records_arr[0];
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function check_w4_form_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_w9_form_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        //        $this->db->where('user_consent', NULL);
        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function deactivate_w4_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->set('status', 0);
        $this->db->from('form_w4_original');
        $this->db->update();
    }

    function update_w4_employer_info($user_type, $user_sid, $company_sid, $update_w4_employer)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->update('form_w4_original', $update_w4_employer);
    }

    function deactivate_w9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 0);
        $this->db->from('applicant_w9form');
        $this->db->update();
    }

    function activate_w4_forms($user_type, $user_sid, $data)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->update('form_w4_original', $data);
    }

    function activate_w9_forms($user_type, $user_sid, $data)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('applicant_w9form', $data);
    }

    function get_i9_form($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_w4_form($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('status', 1);
        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_i9_exist($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        //        $this->db->where('user_consent', NULL);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_eeoc_exist($user_sid, $user_type)
    {
        $this->db->where('application_sid', $user_sid);
        $this->db->where('users_type', $user_type);
        $this->db->from('portal_eeo_form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_eeoc_form_record($data_to_insert)
    {
        $this->db->insert("portal_eeo_form", $data_to_insert);
        return $this->db->insert_id();
    }

    function deactivate_i9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 0);
        $this->db->from('applicant_i9form');
        $this->db->update();
    }

    function deactivate_EEOC_forms($user_type, $user_sid)
    {
        // Check and move to history
        $this->CheckAndMoveToHistory($user_type, $user_sid);
        // 
        $updateArray = [];
        $updateArray['status'] = 0;
        $updateArray['is_expired'] = 0;
        $updateArray['is_latest'] = 0;
        $updateArray['us_citizen'] = NULL;
        $updateArray['visa_status'] = NULL;
        $updateArray['group_status'] = NULL;
        $updateArray['veteran'] = NULL;
        $updateArray['disability'] = NULL;
        $updateArray['gender'] = NULL;
        $updateArray['last_assigned_by'] = 0;
        $updateArray['last_sent_at'] = NULL;
        $updateArray['last_completed_on'] = NULL;
        $updateArray['is_opt_out'] = NULL;
        //
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        $this->db->update('portal_eeo_form', $updateArray);
    }

    function activate_EEOC_forms($user_type, $user_sid)
    {
        $updateArray = [];
        $updateArray['status'] = 1;
        $updateArray['is_latest'] = 1;
        $updateArray['is_opt_out'] = null;
        $updateArray['last_assigned_by'] = $this->session->userdata('logged_in')['employer_detail']['sid'];
        $updateArray['last_sent_at'] = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid', $user_sid);
        //
        $this->db->update('portal_eeo_form', $updateArray);

        //
        $this->CheckAndMoveToHistory($user_type, $user_sid);
    }

    //
    function CheckAndMoveToHistory($user_type, $user_sid)
    {
        //
        $forms = $this->db
            ->where('users_type', $user_type)
            ->where('application_sid', $user_sid)
            ->order_by('sid', 'desc')
            ->get('portal_eeo_form')
            ->result_array();
        //
        if (empty($forms)) {
            return false;
        }
        //
        $sid = 0;
        //
        foreach ($forms as $form) {
            //
            if ($sid == 0) {
                $sid = $form['sid'];
                //
                $form['eeo_form_sid'] = $form['sid'];
                //
                unset($form['sid']);
                //
                $this->db->insert('portal_eeo_form_history', $form);
                continue;
            }
            //
            if (empty($form['us_citizen'])) {
                $this->db->delete('portal_eeo_form', ['sid' => $form['sid']]);
            }
            //
            $form['eeo_form_sid'] = $form['sid'];
            //
            unset($form['sid']);
            //
            $this->db->insert('portal_eeo_form_history', $form);
        }
    }

    function activate_i9_forms($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('status', 1);
        $this->db->set('sent_date', date('Y-m-d H:i:s'));
        $this->db->from('applicant_i9form');
        $this->db->update();
    }

    function reassign_i9_forms($user_type, $user_sid, $updateArray)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('applicant_i9form', $updateArray);
    }

    function delete_i9_form($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('applicant_i9form');
    }

    function insert_i9_form_record($data_to_insert)
    {
        $data_to_insert['emp_app_sid'] = $data_to_insert['user_sid'];
        $this->db->insert('applicant_i9form', $data_to_insert);
    }

    function i9_forms_history($data_to_insert)
    {
        $this->db->insert('applicant_i9form_history', $data_to_insert);
    }

    function revoke_assigned_offer_letter($user_type, $user_sid, $offer_letter_sid)
    {
        $this->db->where('document_sid', $offer_letter_sid);
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->set('archive', 1);
        $this->db->update('documents_assigned');
    }

    function update_upload_status($company_sid, $user_type, $user_sid, $document_type, $document_sid, $uploaded_file)
    {
        $now = date('Y-m-d H:i:s');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('sid', $document_sid);
        $this->db->where('status', 1);
        $this->db->set('uploaded', 1);
        $this->db->set('user_consent', 1);
        $this->db->set('uploaded_date', $now);
        $this->db->set('uploaded_file', $uploaded_file);
        $this->db->update('documents_assigned');

        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['document_type'] = $document_type;
        $data_to_insert['document_sid'] = $document_sid;
        $data_to_insert['user_type'] = $user_type;
        $data_to_insert['user_sid'] = $user_sid;
        $data_to_insert['uploaded_date'] = $now;
        $data_to_insert['uploaded_file'] = $uploaded_file;
        $this->db->insert('document_user_activity_history', $data_to_insert);
    }

    function get_assigned_document_details($company_sid, $sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_assigned_documents_history($document_sid = 0, $user_type, $user_sid, $pp_flag = 0)
    {
        $this->db->select('documents_assigned_history.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required, documents_management.assign_type');
        $this->db->where('documents_assigned_history.document_type !=', 'offer_letter');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->order_by('documents_assigned_history.sid', 'DESC');
        if ($pp_flag) {
            $this->db->where('documents_2_category.category_sid', PP_CATEGORY_SID);
            $this->db->join('documents_2_category', 'documents_2_category.document_sid = documents_assigned_history.doc_sid', 'inner');
        } else {
            //$this->db->where_not_in('documents_assigned_history.doc_sid', 'Select `document_sid` from documents_2_category where `category_sid = `'.PP_CATEGORY_SID);
        }
        $this->db->join('documents_management', 'documents_management.sid = documents_assigned_history.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function getEmployeesWithPendingDoc_old(
        $company_sid,
        $employeeList = 'all',
        $documentList = 'all'
    ) {
        $this->db->select('user_sid,sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_consent', 0);
        $this->db->where('status', 1);
        $this->db->where('user_type', 'employee');
        $this->db->group_by('user_sid');
        //
        // _e($employeeList);
        if ($employeeList != 'all') $this->db->where_in('user_sid', explode(':', $employeeList));
        //
        if ($documentList != 'all') $this->db->where_in('document_sid', explode(':', $documentList));
        //
        $employee_sids = $this->db->get('documents_assigned')->result_array();

        $r = [];

        foreach ($employee_sids as $emp_key => $employee) {

            $employee_sid = $employee['user_sid'];

            $employee_sids[$emp_key]['Documents'] = array();
            $assigned_documents = $this->get_employee_assigned_documents($company_sid, 'employee', $employee_sid);
            // _e($assigned_documents);

            foreach ($assigned_documents as $document_key => $assigned_document) {
                //
                $is_magic_tag_exist = 0;
                $is_document_completed = 0;

                // if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['offer_letter_type'] == 'generated' || $assigned_document['offer_letter_type'] == 'hybrid_document' || $assigned_document['document_type'] == 'hybrid_document')) {
                if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {

                    $document_body = $assigned_document['document_description'];
                    //$magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');

                    $magic_codes = array('{{signature}}', '{{inital}}');

                    if (str_replace($magic_codes, '', $document_body) != $document_body) {
                        $is_magic_tag_exist = 1;
                    }
                }

                //  if ($assigned_document['approval_process'] == 0) {
                if ($assigned_document['document_type'] != 'offer_letter' && $assigned_document['archive'] != 1) {

                    if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist)) {

                        if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                            if ($is_magic_tag_exist == 1) {
                                if ($assigned_document['uploaded'] == 1) {
                                    $is_document_completed = 1;
                                } else {
                                    $is_document_completed = 0;
                                }
                            } else if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['acknowledgment_required'] == 1) {
                            if ($assigned_document['acknowledged'] == 1) {
                                $is_document_completed = 1;
                            } else if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['download_required'] == 1) {
                            if ($assigned_document['downloaded'] == 1) {
                                $is_document_completed = 1;
                            } else if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($assigned_document['signature_required'] == 1) {
                            if ($assigned_document['uploaded'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        } else if ($is_magic_tag_exist == 1) {
                            if ($assigned_document['user_consent'] == 1) {
                                $is_document_completed = 1;
                            } else {
                                $is_document_completed = 0;
                            }
                        }

                        if ($is_document_completed > 0) {
                            unset($assigned_documents[$document_key]);
                        } else {
                            $assigned_sids[] = $assigned_document['document_sid'];
                            $assigned_on = date('M d Y, D h:i:s', strtotime($assigned_document['assigned_date']));
                            $now = time();
                            $datediff = $now - strtotime($assigned_document['assigned_date']);
                            $days = round($datediff / (60 * 60 * 24));

                            $employee_sids[$emp_key]['Documents'][] = array('ID' => $assigned_document['document_sid'], 'Title' => $assigned_document['document_title'], 'Type' => ucwords($assigned_document['document_type']), 'AssignedOn' => $assigned_on, 'Days' =>  $days, 'AssignedBy' => $assigned_document['assigned_by']);
                        }
                    } else {
                        unset($assigned_documents[$document_key]);
                    }
                } else {
                    //
                }
            }

            $pending_documents  = count($assigned_documents);
            $pending_w4         = $this->is_employee_w4_document_pending('employee', $employee_sid);
            $pending_w9         = $this->is_employee_w9_document_pending('employee', $employee_sid);
            $pending_i9         = $this->is_employee_i9_document_pending('employee', $employee_sid);
            if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_employee_document_center']) {
                $pending_eeoc       = $this->is_eeoc_form_assign('employee', $employee_sid);
            } else {
                $pending_eeoc       = 0;
            }

            // Get General Documents
            $this->addGeneralDocuments(
                $company_sid,
                $employee['user_sid'],
                $employee_sids[$emp_key]['Documents']
            );

            // 
            if ($pending_w4 != 0) {
                $w4_info = $this->get_w4_document_assign_date('employee', $employee_sid);
                //
                $employee_sids[$emp_key]['Documents'][] = array(
                    'ID' => 0,
                    'Title' => 'W4 Fillable',
                    'Type' => 'Verification',
                    'AssignedOn' => $w4_info['assigned_on'],
                    'Days' => $w4_info['days']
                );
            }

            if ($pending_w9 != 0) {
                $w9_info = $this->get_w9_document_assign_date('employee', $employee_sid);
                //
                $employee_sids[$emp_key]['Documents'][] = array(
                    'ID' => 0,
                    'Title' => 'W9 Fillable',
                    'Type' => 'Verification',
                    'AssignedOn' => $w9_info['assigned_on'],
                    'Days' => $w9_info['days']
                );
            }

            if ($pending_i9 != 0) {
                $i9_info = $this->get_i9_document_assign_date('employee', $employee_sid);
                //
                $employee_sids[$emp_key]['Documents'][] = array(
                    'ID' => 0,
                    'Title' => 'I9 Fillable',
                    'Type' => 'Verification',
                    'AssignedOn' => $i9_info['assigned_on'],
                    'Days' => $i9_info['days']
                );
            }

            if ($pending_eeoc != 0) {
                $eeoc_info = $this->get_eeoc_document_assign_date('employee', $employee_sid);
                //
                $employee_sids[$emp_key]['Documents'][] = array(
                    'ID' => 0,
                    'Title' => 'EEOC Fillable',
                    'Type' => 'Verification',
                    'AssignedOn' => $eeoc_info['assigned_on'],
                    'AssignedBy' => $eeoc_info['assigned_by'],
                    'Days' => $eeoc_info['days']
                );
            }

            if ($pending_documents == 0 && $pending_w4 == 0 && $pending_w9 == 0 && $pending_i9 == 0) {
                unset($employee_sids[$emp_key]);
            } else {
                $r[$employee_sid]['Documents'] = $employee_sids[$emp_key]['Documents'];
            }
        }

        if ($documentList == 'all') {
            $ignore = array_column($employee_sids, 'user_sid');
            $this->db->select('sid');
            $this->db->where('parent_sid', $company_sid);
            $this->db->where('terminated_status', 0);
            $this->db->where('active', 1);
            if (!empty($ignore)) {
                $this->db->where_not_in('sid', $ignore);
            }

            $record_obj = $this->db->get('users');
            $other_employees = [];

            if ($record_obj !== FALSE && $record_obj->num_rows() > 0) {
                $other_employees = $record_obj->result_array();
                $record_obj->free_result();
            }


            if (!empty($other_employees)) {
                foreach ($other_employees as $other_key => $other_employee) {
                    $pending_w4         = $this->is_employee_w4_document_pending('employee', $other_employee['sid']);
                    $pending_w9         = $this->is_employee_w9_document_pending('employee', $other_employee['sid']);
                    $pending_i9         = $this->is_employee_i9_document_pending('employee', $other_employee['sid']);
                    // 
                    if ($pending_w4 != 0) {
                        $w4_info = $this->get_w4_document_assign_date('employee', $other_employee['sid']);
                        //
                        $other_employees[$other_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'W4 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => $w4_info['assigned_on'],
                            'Days' => $w4_info['days']
                        );
                    }

                    if ($pending_w9 != 0) {
                        $w9_info = $this->get_w9_document_assign_date('employee', $other_employee['sid']);
                        //
                        $other_employees[$other_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'W9 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => $w9_info['assigned_on'],
                            'Days' => $w9_info['days']
                        );
                    }

                    if ($pending_i9 != 0) {
                        $i9_info = $this->get_i9_document_assign_date('employee', $other_employee['sid']);
                        //
                        $other_employees[$other_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'I9 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => $i9_info['assigned_on'],
                            'Days' => $i9_info['days']
                        );
                    }
                    $generalDocuments = [];
                    // Get General Documents
                    $this->addGeneralDocuments(
                        $company_sid,
                        $other_employee['sid'],
                        $generalDocuments
                    );


                    if ($pending_w4 == 0 && $pending_w9 == 0 && $pending_i9 == 0 && count($generalDocuments) == 0) {
                        unset($employee_sids[$emp_key]);
                    } else {
                        $other_employees[$other_key]['Documents'] = array_merge(
                            $other_employees[$other_key]['Documents'],
                            $generalDocuments
                        );
                        $r[$other_employee['sid']]['Documents'] = $other_employees[$other_key]['Documents'];
                    }
                }
            }
        }

        return $r;
    }

    function getEmployeesWithPendingDoc(
        $company_sid,
        $employeeList = 'all',
        $documentList = 'all'
    ) {


        //
        $pendingDocuments = [];
        //
        $activeEmployees = explode(':', $employeeList);
        $filterDocuments = explode(':', $documentList);
        //
        $now = time();
        //
        if (!empty($activeEmployees)) {
            //
            foreach ($activeEmployees as $employee) {
                $pendingDocuments[$employee]['Documents'] = [];
            }

            //
            // Get all company assigned documents
            $this->db->select('
                documents_assigned.sid,
                documents_assigned.user_sid, 
                documents_assigned.assigned_by, 
                documents_assigned.document_description, 
                documents_assigned.document_title, 
                documents_assigned.document_type, 
                documents_assigned.acknowledgment_required, 
                documents_assigned.download_required, 
                documents_assigned.signature_required, 
                documents_assigned.uploaded,
                documents_assigned.acknowledged, 
                documents_assigned.downloaded, 
                documents_assigned.user_consent, 
                documents_assigned.document_sid, 
                documents_assigned.assigned_date
            ');
            //
            $this->db->where_in('documents_assigned.user_sid', $activeEmployees);
            $this->db->where('documents_assigned.user_consent', 0);
            $this->db->where('documents_assigned.status', 1);
            $this->db->where('documents_assigned.user_type', 'employee');
            $this->db->where('documents_assigned.archive', 0);
            $this->db->where('documents_management.archive', 0);
            $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
            //
            $employees_assigned_documents = $this->db->get('documents_assigned')->result_array();
            //
            if (!empty($employees_assigned_documents)) {
                foreach ($employees_assigned_documents as $assigned_document) {
                    //
                    $is_document_completed = $this->checkDocumentCompletionStatus($assigned_document);
                    //
                    if ($is_document_completed == 0) {
                        //
                        $employeeId = $assigned_document['user_sid'];
                        $assigned_on = date('M d Y, D h:i:s', strtotime($assigned_document['assigned_date']));
                        //
                        $datediff = $now - strtotime($assigned_document['assigned_date']);
                        $days = round($datediff / (60 * 60 * 24));

                        $pendingDocuments[$employeeId]['Documents'][] = array('ID' => $assigned_document['document_sid'], 'Title' => $assigned_document['document_title'], 'Type' => ucwords($assigned_document['document_type']), 'AssignedOn' => $assigned_on, 'Days' =>  $days, 'AssignedBy' => $assigned_document['assigned_by']);
                    }
                }
            }
            //
            // Get all company assigned offer letter
            $this->db->select('
                documents_assigned.sid,
                documents_assigned.user_sid, 
                documents_assigned.assigned_by, 
                documents_assigned.document_description, 
                documents_assigned.document_title, 
                documents_assigned.offer_letter_type as document_type,
                documents_assigned.acknowledgment_required, 
                documents_assigned.download_required, 
                documents_assigned.signature_required, 
                documents_assigned.uploaded,
                documents_assigned.acknowledged, 
                documents_assigned.downloaded, 
                documents_assigned.user_consent, 
                documents_assigned.document_sid, 
                documents_assigned.assigned_date
            ');
            //
            $this->db->where('documents_assigned.document_type', 'offer_letter');
            $this->db->where_in('documents_assigned.user_sid', $activeEmployees);
            $this->db->where('documents_assigned.user_consent', 0);
            $this->db->where('documents_assigned.status', 1);
            $this->db->where('documents_assigned.user_type', 'employee');
            $this->db->where('documents_assigned.archive', 0);
            $this->db->where('offer_letter.archive', 0);
            $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');
            //
            $employees_assigned_offer_letters = $this->db->get('documents_assigned')->result_array();
            //
            if (!empty($employees_assigned_offer_letters)) {
                //
                foreach ($employees_assigned_offer_letters as $assigned_offer_letter) {
                    //
                    $is_document_completed = $this->checkDocumentCompletionStatus($assigned_offer_letter);
                    //
                    if ($is_document_completed == 0) {
                        //
                        $employeeId = $assigned_offer_letter['user_sid'];
                        $assigned_on = date('M d Y, D h:i:s', strtotime($assigned_offer_letter['assigned_date']));
                        //
                        $datediff = $now - strtotime($assigned_offer_letter['assigned_date']);
                        $days = round($datediff / (60 * 60 * 24));

                        $pendingDocuments[$employeeId]['Documents'][] = array('ID' => $assigned_offer_letter['document_sid'], 'Title' => $assigned_offer_letter['document_title'], 'Type' => ucwords($assigned_offer_letter['document_type']) . ' Offer Letter', 'AssignedOn' => $assigned_on, 'Days' =>  $days, 'AssignedBy' => $assigned_offer_letter['assigned_by']);
                    }
                }
            }
            //
            // Get employees W4
            $this->db->select('sent_date, employer_sid');
            $this->db->where('user_type', 'employee');
            $this->db->where_in('employer_sid', $activeEmployees);
            $this->db->where('status', 1);
            $this->db->group_start();
            $this->db->where('user_consent ', 0);
            $this->db->or_where('user_consent', NULL);
            $this->db->group_end();
            $employees_w4_documents = $this->db->get('form_w4_original')->result_array();
            //
            if (!empty($employees_w4_documents)) {
                foreach ($employees_w4_documents as $employee_w4) {
                    //
                    $assigned_on = date('M d Y, D h:i:s', strtotime($employee_w4['sent_date']));
                    //
                    $datediff = $now - strtotime($employee_w4['sent_date']);
                    $days = round($datediff / (60 * 60 * 24));
                    //
                    $result['assigned_on'] = $assigned_on;
                    $result['days'] = $days;
                    //
                    $pendingDocuments[$employee_w4['employer_sid']]['Documents'][] = array(
                        'ID' => 0,
                        'Title' => 'W4 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $assigned_on,
                        'Days' => $days
                    );
                }
            }
            //
            // Get employees W9
            $this->db->select('sent_date, user_sid');
            $this->db->where('user_type', 'employee');
            $this->db->where_in('user_sid', $activeEmployees);
            $this->db->where('status', 1);
            $this->db->group_start();
            $this->db->where('user_consent ', 0);
            $this->db->or_where('user_consent', NULL);
            $this->db->group_end();

            $employees_w9_documents = $this->db->get('applicant_w9form')->result_array();
            //
            if (!empty($employees_w9_documents)) {
                foreach ($employees_w9_documents as $employee_w9) {
                    //
                    $assigned_on = date('M d Y, D h:i:s', strtotime($employee_w9['sent_date']));
                    //
                    $datediff = $now - strtotime($employee_w9['sent_date']);
                    $days = round($datediff / (60 * 60 * 24));
                    //
                    $result['assigned_on'] = $assigned_on;
                    $result['days'] = $days;
                    //
                    $pendingDocuments[$employee_w9['user_sid']]['Documents'][] = array(
                        'ID' => 0,
                        'Title' => 'W9 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $assigned_on,
                        'Days' => $days
                    );
                }
            }
            //
            // Get employees I9
            $this->db->select('sent_date, user_sid');
            $this->db->where('user_type', 'employee');
            $this->db->where_in('user_sid', $activeEmployees);
            $this->db->where('status', 1);
            $this->db->group_start();
            $this->db->where('user_consent ', 0);
            $this->db->or_where('user_consent', NULL);
            $this->db->group_end();
            //
            $employees_i9_documents = $this->db->get('applicant_i9form')->result_array();
            //
            if (!empty($employees_i9_documents)) {
                foreach ($employees_i9_documents as $employee_i9) {
                    //
                    $assigned_on = date('M d Y, D h:i:s', strtotime($employee_i9['sent_date']));
                    //
                    $datediff = $now - strtotime($employee_i9['sent_date']);
                    $days = round($datediff / (60 * 60 * 24));
                    //
                    $result['assigned_on'] = $assigned_on;
                    $result['days'] = $days;
                    //
                    $pendingDocuments[$employee_i9['user_sid']]['Documents'][] = array(
                        'ID' => 0,
                        'Title' => 'I9 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $assigned_on,
                        'Days' => $days
                    );
                }
            }
            //
            // Get EEOC
            if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_employee_document_center']) {
                //
                $this->db->select('last_sent_at, last_assigned_by, application_sid');
                $this->db->where('users_type', 'employee');
                $this->db->where_in('application_sid ', $activeEmployees);
                $this->db->where('is_expired', 0);
                $this->db->where('status', 1);
                //
                $employees_eeoc_documents = $this->db->get('portal_eeo_form')->result_array();
                //
                if (!empty($employees_eeoc_documents)) {
                    foreach ($employees_eeoc_documents as $employee_eeoc) {
                        //
                        $assigned_on = date('M d Y, D h:i:s', strtotime($employee_eeoc['last_sent_at']));
                        //
                        $datediff = $now - strtotime($employee_eeoc['last_sent_at']);
                        $days = round($datediff / (60 * 60 * 24));
                        //
                        $pendingDocuments[$employee_eeoc['application_sid']]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'EEOC Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => $assigned_on,
                            'AssignedBy' => $employee_eeoc['last_assigned_by'],
                            'Days' => $days
                        );
                    }
                }
            }
            //
            // Get employees General Documents
            $this->db->select('sid, user_sid, assigned_at, document_type');
            $this->db->where_in('user_sid', $activeEmployees);
            $this->db->where('user_type', 'employee');
            $this->db->where('status', 1);
            $this->db->where('is_completed', 0);
            //
            $employees_general_documents = $this->db->get('documents_assigned_general')->result_array();
            //
            if (!empty($employees_general_documents)) {
                foreach ($employees_general_documents as $employee_general) {
                    $this->db->select('user_sid');
                    $this->db->where('documents_assigned_general_sid', $employee_general['sid']);
                    $this->db->where('action', 'assign');
                    $this->db->limit(1);
                    $this->db->order_by('sid', 'desc');
                    //
                    $document_assign_by = $this->db->get('documents_assigned_general_assigners')->row_array();
                    $assign_by = 0;
                    //
                    if (!empty($document_assign_by)) {
                        $assign_by = $document_assign_by['user_sid'];
                    }
                    //
                    $assigned_on = date('M d Y, D h:i:s', strtotime($employee_general['assigned_at']));
                    //
                    $datediff = $now - strtotime($employee_general['assigned_at']);
                    $days = round($datediff / (60 * 60 * 24));
                    //
                    $pendingDocuments[$employee_general['user_sid']]['Documents'][] = array(
                        'ID' => 0,
                        'Title' => ucwords(str_replace('_', ' ', $employee_general['document_type'])),
                        'Type' => 'General',
                        'AssignedOn' => $assigned_on,
                        'Days' => $days,
                        'AssignedBy' => $assign_by
                    );
                }
            }
        }
        // get state forms
        $stateForms = $this->getEmployeesPendingStateForms(
            $company_sid,
            $activeEmployees
        );

        if ($stateForms) {
            foreach ($stateForms as $form) {
                $userId = $form["user_sid"];
                //
                $datediff = $now - strtotime($form['created_at']);
                $days = round($datediff / (60 * 60 * 24));
                //
                $pendingDocuments[$userId]['Documents'][] = array(
                    'ID' => 0,
                    'Title' => $form["title"],
                    'Type' => 'State Form',
                    'AssignedOn' => formatDateToDB($form["created_at"], DB_DATE_WITH_TIME, DATE_WITH_TIME),
                    'Days' => $days,
                    'AssignedBy' => ""
                );
            }
        }
        //
        // remove all those active company employees from list with no pending documents
        foreach ($pendingDocuments as $p_key => $pendingEmployeeDocuments) {
            if (empty($pendingEmployeeDocuments['Documents'])) {
                unset($pendingDocuments[$p_key]);
            } else {
                //
                $this->db->select('
                    sid, 
                    first_name, 
                    last_name,
                    email, 
                    is_executive_admin, 
                    access_level, 
                    access_level_plus,
                    pay_plan_flag,
                    job_title
                ');
                $this->db->where('sid', $p_key);
                $employeeInfo = $this->db->get('users')->row_array();
                //
                $pendingDocuments[$p_key]['sid'] = $employeeInfo['sid'];
                $pendingDocuments[$p_key]['first_name'] = $employeeInfo['first_name'];
                $pendingDocuments[$p_key]['last_name'] = $employeeInfo['last_name'];
                $pendingDocuments[$p_key]['email'] = $employeeInfo['email'];
                $pendingDocuments[$p_key]['is_executive_admin'] = $employeeInfo['is_executive_admin'];
                $pendingDocuments[$p_key]['access_level'] = $employeeInfo['access_level'];
                $pendingDocuments[$p_key]['access_level_plus'] = $employeeInfo['access_level_plus'];
                $pendingDocuments[$p_key]['pay_plan_flag'] = $employeeInfo['pay_plan_flag'];
                $pendingDocuments[$p_key]['job_title'] = $employeeInfo['job_title'];
            }
        }
        //
        return array_values($pendingDocuments);
    }

    public function getEmployeeOfferLetter($user_sid, $user_type)
    {
        $this->db->select('
            documents_assigned.sid,
            documents_assigned.user_sid, 
            documents_assigned.assigned_by, 
            documents_assigned.document_description, 
            documents_assigned.document_title, 
            documents_assigned.offer_letter_type as document_type,
            documents_assigned.acknowledgment_required, 
            documents_assigned.download_required, 
            documents_assigned.signature_required, 
            documents_assigned.uploaded,
            documents_assigned.acknowledged, 
            documents_assigned.downloaded, 
            documents_assigned.user_consent, 
            documents_assigned.document_sid, 
            documents_assigned.assigned_date
        ');
        //
        $this->db->where('documents_assigned.document_type', 'offer_letter');
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_assigned.user_type', $user_type);
        $this->db->where('documents_assigned.user_sid', $user_sid);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->where('offer_letter.archive', 0);
        $this->db->join('offer_letter', 'offer_letter.sid = documents_assigned.document_sid', 'left');
        return $this->db->get('documents_assigned')->row_array();
    }

    function getEmployeesWithPendingFederalFillable(
        $company_sid,
        $employeeList = 'all',
        $documentList = 'all'
    ) {
        //
        $pendingDocuments = [];
        //
        $activeEmployees = explode(':', $employeeList);
        //
        if (!empty($activeEmployees)) {
            //
            foreach ($activeEmployees as $employee) {
                $this->db->select('
                    sid, 
                    first_name, 
                    last_name,
                    email, 
                    is_executive_admin, 
                    access_level, 
                    access_level_plus,
                    pay_plan_flag,
                    job_title
                ');
                $this->db->where('sid', $employee);
                $employeeInfo = $this->db->get('users')->row_array();
                //
                $pendingDocuments[$employee]['sid'] = $employeeInfo['sid'];
                $pendingDocuments[$employee]['first_name'] = $employeeInfo['first_name'];
                $pendingDocuments[$employee]['last_name'] = $employeeInfo['last_name'];
                $pendingDocuments[$employee]['email'] = $employeeInfo['email'];
                $pendingDocuments[$employee]['is_executive_admin'] = $employeeInfo['is_executive_admin'];
                $pendingDocuments[$employee]['access_level'] = $employeeInfo['access_level'];
                $pendingDocuments[$employee]['access_level_plus'] = $employeeInfo['access_level_plus'];
                $pendingDocuments[$employee]['pay_plan_flag'] = $employeeInfo['pay_plan_flag'];
                $pendingDocuments[$employee]['job_title'] = $employeeInfo['job_title'];
                $pendingDocuments[$employee]['job_title'] = $employeeInfo['job_title'];
                $pendingDocuments[$employee]['any_pending'] = 'no';
                $pendingDocuments[$employee]['Documents']['w4'] = array(
                    'ID' => 0,
                    'Title' => 'W4 Fillable',
                    'Type' => 'Verification',
                    'AssignedOn' => "",
                    'Days' => "",
                    'Status' => 'not_assigned'
                );
                $pendingDocuments[$employee]['Documents']['i9'] = array(
                    'ID' => 0,
                    'Title' => 'I9 Fillable',
                    'Type' => 'Verification',
                    'AssignedOn' => "",
                    'Days' => "",
                    'Status' => 'not_assigned'
                );
            }
            //
            $now = time();
            //
            //
            // Get employees W4
            $this->db->select('sent_date, employer_sid, user_consent');
            $this->db->where('user_type', 'employee');
            $this->db->where_in('employer_sid', $activeEmployees);
            $this->db->where('status', 1);
            //
            $employees_w4_documents = $this->db->get('form_w4_original')->result_array();
            //
            if (!empty($employees_w4_documents)) {
                foreach ($employees_w4_documents as $employee_w4) {
                    //
                    $assigned_on = date('M d Y, D h:i:s', strtotime($employee_w4['sent_date']));
                    //
                    $datediff = $now - strtotime($employee_w4['sent_date']);
                    $days = round($datediff / (60 * 60 * 24));
                    //
                    $result['assigned_on'] = $assigned_on;
                    $result['days'] = $days;
                    //
                    $status = 'completed';
                    //
                    if ($employee_w4['user_consent'] != 1) {
                        $pendingDocuments[$employee_w4['employer_sid']]['any_pending'] = 'yes';
                        $status = 'pending';
                    }
                    //
                    $pendingDocuments[$employee_w4['employer_sid']]['Documents']['w4'] = array(
                        'ID' => 0,
                        'Title' => 'W4 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $assigned_on,
                        'Days' => $days,
                        'Status' => $status
                    );
                }
            }
            //
            // Get employees I9
            $this->db->select('sent_date, user_sid, user_consent');
            $this->db->where('user_type', 'employee');
            $this->db->where_in('user_sid', $activeEmployees);
            $this->db->where('status', 1);
            //
            $employees_i9_documents = $this->db->get('applicant_i9form')->result_array();
            //
            if (!empty($employees_i9_documents)) {
                foreach ($employees_i9_documents as $employee_i9) {
                    //
                    $assigned_on = date('M d Y, D h:i:s', strtotime($employee_i9['sent_date']));
                    //
                    $datediff = $now - strtotime($employee_i9['sent_date']);
                    $days = round($datediff / (60 * 60 * 24));
                    //
                    $result['assigned_on'] = $assigned_on;
                    $result['days'] = $days;
                    //
                    $status = 'completed';
                    //
                    if ($employee_i9['user_consent'] != 1) {
                        $pendingDocuments[$employee_i9['user_sid']]['any_pending'] = 'yes';
                        $status = 'pending';
                    }
                    //
                    $pendingDocuments[$employee_i9['user_sid']]['Documents']['i9'] = array(
                        'ID' => 0,
                        'Title' => 'I9 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $assigned_on,
                        'Days' => $days,
                        'Status' => $status
                    );
                }
            }
        }
        //
        // _e($pendingDocuments,true,true);
        return array_values($pendingDocuments);
    }

    function getEmployeesWithPendingFederalFillable_old(
        $company_sid,
        $employeeList = 'all',
        $documentList = 'all'
    ) {

        $r = array();

        $this->db->select('sid');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        if ($employeeList != 'all') $this->db->where_in('sid', explode(':', $employeeList));

        $record_obj = $this->db->get('users');
        $employees = $record_obj->result_array();
        $record_obj->free_result();
        //      
        if (!empty($employees)) {
            foreach ($employees as $emp_key => $employee) {
                $documents = explode(':', $documentList);
                // _e($documents,true,true);
                if (in_array('w4', $documents) || in_array('all', $documents)) {
                    $pending_w4_status = $this->is_employee_w4_document_pending_or_notassigned('employee', $employee['sid']);
                } else {
                    $pending_w4_status = 0;
                }
                //
                if (in_array('i9', $documents) || in_array('all', $documents)) {
                    $pending_i9_status = $this->is_employee_i9_document_pending_or_notassigned('employee', $employee['sid']);
                } else {
                    $pending_i9_status = 0;
                }

                if ($pending_w4_status == "not_assigned" || $pending_w4_status == "pending") {
                    if ($pending_w4_status == "pending") {
                        $w4_info = $this->get_w4_document_assign_date('employee', $employee['sid']);
                        //
                        $employees[$emp_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'W4 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => $w4_info['assigned_on'],
                            'Days' => $w4_info['days'],
                            'Status' => 'pending'
                        );
                    } else {
                        $employees[$emp_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'W4 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => "",
                            'Days' => "",
                            'Status' => 'not_assigned'
                        );
                    }
                } else if ($pending_w4_status == "completed") {
                    $w4_info = $this->get_w4_completed_document_assign_date('employee', $employee['sid']);
                    //
                    $employees[$emp_key]['Documents'][] = array(
                        'ID' => 0,
                        'Title' => 'W4 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $w4_info['assigned_on'],
                        'Days' => $w4_info['days'],
                        'Status' => 'completed'
                    );
                }

                if ($pending_i9_status == "not_assigned" || $pending_i9_status == "pending") {
                    if ($pending_i9_status == "pending") {
                        $i9_info = $this->get_i9_document_assign_date('employee', $employee['sid']);
                        //
                        $employees[$emp_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'I9 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => $i9_info['assigned_on'],
                            'Days' => $i9_info['days'],
                            'Status' => 'pending'
                        );
                    } else {
                        $employees[$emp_key]['Documents'][] = array(
                            'ID' => 0,
                            'Title' => 'I9 Fillable',
                            'Type' => 'Verification',
                            'AssignedOn' => "",
                            'Days' => "",
                            'Status' => 'not_assigned'
                        );
                    }
                } else if ($pending_i9_status == "completed") {
                    $i9_info = $this->get_i9_completed_document_assign_date('employee', $employee['sid']);
                    //
                    $employees[$emp_key]['Documents'][] = array(
                        'ID' => 0,
                        'Title' => 'I9 Fillable',
                        'Type' => 'Verification',
                        'AssignedOn' => $i9_info['assigned_on'],
                        'Days' => $i9_info['days'],
                        'Status' => 'completed'
                    );
                }

                if ($pending_w4_status === 0 && $pending_i9_status === 0) {
                    unset($employees[$emp_key]);
                } else {
                    $r[$employee['sid']]['Documents'] = $employees[$emp_key]['Documents'];
                }
            }
        }

        return $r;
    }

    function is_employee_w4_document_pending_or_notassigned($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('status', 1);

        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            if ($records_arr['user_consent'] == 1) {
                return 'completed';
            } else {
                return "pending";
            }
        } else {
            return "not_assigned";
        }
    }

    function is_employee_i9_document_pending_or_notassigned($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);

        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            if ($records_arr['user_consent'] == 1) {
                return 'completed';
            } else {
                return "pending";
            }
        } else {
            return "not_assigned";
        }
    }

    function get_w4_completed_document_assign_date($user_type, $user_sid)
    {
        $this->db->select('sent_date');
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('status', 1);

        $this->db->from('form_w4_original');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($record_arr['sent_date']));
            //
            $now = time();
            $datediff = $now - strtotime($record_arr['sent_date']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $result['assigned_on'] = $assigned_on;
            $result['days'] = $days;
            //
            return $result;
        } else {
            return array();
        }
    }

    function get_i9_completed_document_assign_date($user_type, $user_sid)
    {
        $this->db->select('sent_date');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('status', 1);

        $this->db->from('applicant_i9form');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($record_arr['sent_date']));
            //
            $now = time();
            $datediff = $now - strtotime($record_arr['sent_date']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $result['assigned_on'] = $assigned_on;
            $result['days'] = $days;
            //
            return $result;
        } else {
            return array();
        }
    }

    function get_employee_assigned_documents($company_sid, $user_type, $user_sid = null)
    {

        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive,documents_management.visible_to_payroll');
        if (ASSIGNEDOCIMPL) $this->db->select('documents_assigned.acknowledgment_required,documents_assigned.download_required,documents_assigned.signature_required');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->where('documents_management.archive', 0);
        $this->db->where('status', 1);
        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        return $record_arr;
    }

    function get_w4_document_assign_date($user_type, $user_sid)
    {
        $this->db->select('sent_date');
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('form_w4_original');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($record_arr['sent_date']));
            //
            $now = time();
            $datediff = $now - strtotime($record_arr['sent_date']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $result['assigned_on'] = $assigned_on;
            $result['days'] = $days;
            //
            return $result;
        } else {
            return array();
        }
    }

    function is_employee_w4_document_pending($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        // $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('form_w4_original');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_w9_document_assign_date($user_type, $user_sid)
    {
        $this->db->select('sent_date');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('applicant_w9form');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($record_arr['sent_date']));
            //
            $now = time();
            $datediff = $now - strtotime($record_arr['sent_date']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $result['assigned_on'] = $assigned_on;
            $result['days'] = $days;
            //
            return $result;
        } else {
            return array();
        }
    }

    function is_employee_w9_document_pending($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);

        $this->db->from('applicant_w9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_i9_document_assign_date($user_type, $user_sid)
    {
        $this->db->select('sent_date');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($record_arr['sent_date']));
            //
            $now = time();
            $datediff = $now - strtotime($record_arr['sent_date']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $result['assigned_on'] = $assigned_on;
            $result['days'] = $days;
            //
            return $result;
        } else {
            return array();
        }
    }

    function get_eeoc_document_assign_date($user_type, $user_sid)
    {
        $this->db->select('last_sent_at');
        $this->db->select('last_assigned_by');
        $this->db->where('users_type', $user_type);
        $this->db->where('application_sid ', $user_sid);
        $this->db->where('is_expired', 0);
        $this->db->where('status', 1);
        $this->db->from('portal_eeo_form');

        $record_obj = $this->db->get();
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($record_arr['last_sent_at']));
            //
            $now = time();
            $datediff = $now - strtotime($record_arr['last_sent_at']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $result['assigned_on'] = $assigned_on;
            $result['assigned_by'] = $record_arr['last_assigned_by'];
            $result['days'] = $days;
            //
            return $result;
        } else {
            return array();
        }
    }

    function is_employee_i9_document_pending($user_type, $user_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->group_start();
        $this->db->where('user_consent ', 0);
        $this->db->or_where('user_consent', NULL);
        $this->db->group_end();
        $this->db->where('status', 1);
        $this->db->from('applicant_i9form');

        $records_obj = $this->db->get();
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    private function is_eeoc_form_assign($user_type, $user_sid)
    {
        if ($this->session->userdata('logged_in')['portal_detail']['eeo_form_profile_status']) {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->where('is_expired', 0);
            $this->db->where('status', 1);
            $this->db->from('portal_eeo_form');
            return $this->db->count_all_results();
        } else {
            return 0;
        }
    }

    function getEmployeesDetails($employees)
    {
        return $this->db->select('sid,first_name,last_name,email, pay_plan_flag, is_executive_admin, access_level_plus, access_level, job_title')->where_in('sid', $employees)->order_by("concat(first_name,last_name)", "asc", false)
            ->where('terminated_status', 0)
            ->group_start()
            ->where('active', 1)
            ->where('general_status', 'active')
            ->group_end()
            ->get('users')->result_array();
    }

    function getEmployeePendingActionDocument($company_sid, $employee_type, $employee_id)
    {
        return $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required')
            ->where('documents_assigned.company_sid', $company_sid)
            ->where('documents_assigned.user_sid', $employee_id)
            ->where('documents_assigned.user_type', $employee_type)
            ->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left')
            ->get('documents_assigned')->result_array();
    }

    function getEmployerDetail($id)
    {
        $this->db->where('sid', $id);
        return $this->db->get('users')->row_array();
    }

    function get_all_assigned_documents($company_sid, $user_type, $user_sid = null, $status = 1)
    {
        $this->db->select('documents_assigned.*');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);

        if ($status) {
            $this->db->where('status', $status);
        }

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_uploaded_generated_documents($company_sid, $archive_status = null, $pp_flag = 0)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('is_specific', 0);

        if ($archive_status !== null) {
            $this->db->where('archive', $archive_status);
        }
        if ($pp_flag && !isPayrollOrPlus(true)) {
            $this->db->where('documents_2_category.category_sid', PP_CATEGORY_SID);
            $this->db->join('documents_2_category', 'documents_2_category.document_sid = documents_management.sid', 'inner');
        } else {
            //$this->db->where_not_in('documents_management.sid', 'Select `document_sid` from documents_2_category where `category_sid = `'.PP_CATEGORY_SID);
        }
        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        foreach ($records_arr as $key => $record) {
            $preview_url = AWS_S3_BUCKET_URL . $record['uploaded_document_s3_name'];
            $download_url = $preview_url;
            $records_arr[$key]['preview_url'] = $preview_url;
            $records_arr[$key]['download_url'] = $download_url;
        }

        return $records_arr;
    }

    function get_offer_letter($company_sid, $offer_letter_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('sid', $offer_letter_sid);
        $this->db->from('offer_letter');
        $record_obj = $this->db->get();
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function check_if_document_already_assigned($user_type, $user_sid, $document_type, $document_sid)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        //        $this->db->where('document_sid', $document_sid);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_assign_offer_letter($previous_assigned_sid, $data_to_update)
    {
        $this->db->where('sid', $previous_assigned_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function set_assigned_offer_letter_status($user_type, $user_sid, $document_type)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);
        $this->db->set('status', 0);
        $this->db->update('documents_assignment');
    }

    function getUserDocument($userSid)
    {
        $this->db->select('first_name,last_name,email');
        $this->db->where('sid', $userSid);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function get_admin_or_company_email_template($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('email_templates');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (count($record_arr) > 0) {
            return $record_arr;
        } else {
            return 0;
        }
    }

    function downloaded_generated_doc_on($company_sid, $user_sid, $document_sid, $user_type)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('sid', $document_sid);
        $this->db->where('status', 1);
        $this->db->set('downloaded', 1);
        $this->db->set('downloaded_date', date('Y-m-d H:i:s'));
        $this->db->update('documents_assigned');
    }

    function manager_document_activity_track($data_to_insert)
    {
        $this->db->insert('documents_assigned_manager_activity', $data_to_insert);
    }

    function get_hr_documents_section_records($status = null)
    {
        $this->db->select('*');

        if ($status !== null) {
            $this->db->where('status', $status);
        }

        $records_obj = $this->db->get('hr_documents_editors_data');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function fetch_documents_employees($doc_sid, $doc_type, $company_sid)
    {
        $this->db->select('user_sid');
        $this->db->from('documents_assigned');
        $this->db->where('document_sid', $doc_sid);
        $this->db->where('document_type', $doc_type);
        $where_clause = $this->db->get_compiled_select();
        $this->db->select('
            sid,
            first_name,
            last_name,
            access_level_plus,
            pay_plan_flag,
            access_level,
            is_executive_admin, 
            job_title
        ');
        $this->db->order_by("concat(first_name,' ',last_name)", "ASC", false);
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('active', 1);
        $this->db->where("`sid` NOT IN ($where_clause)", NULL, False);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function update_document_pending_status($document_sid = NULL)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('documents_assigned', array('is_pending' => 0));
    }

    function save_document_authorized_signature($data_to_insert)
    {
        $this->db->insert('documents_authorized_signature', $data_to_insert);
        return $this->db->insert_id();
    }

    function update_authorized_signature($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('documents_authorized_signature', $data_to_update);
    }

    function is_authorized_signature_exist($document_sid, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('document_sid', $document_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        $this->db->limit(1);
        $record_obj = $this->db->get('documents_authorized_signature');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (count($record_arr) > 0) {
            return $record_arr;
        } else {
            return 0;
        }
    }

    function remove_authorized_signature_if_exist($sid, $data_to_update)
    {
        $this->db->where('document_sid', $sid);
        $this->db->update('documents_authorized_signature', $data_to_update);
    }

    function update_hr_document($document_id, $data)
    {
        $this->db->where('sid', $document_id)->update('hr_documents', $data);
    }

    function get_all_hr_documents($company_sid = NULL)
    {
        $this->db->select('*');
        $this->db->where('documents_management_sid', 0);

        if ($company_sid != NULL) {
            $this->db->where('company_sid', $company_sid);
        }

        $this->db->order_by('sid', 'asc');
        $record_obj = $this->db->get('hr_documents');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function insert_document_management_history($data)
    {
        $this->db->insert('documents_management_history', $data);
    }

    function insert_offer_letter_history($data)
    {
        $this->db->insert('offer_letter_history', $data);
    }

    function insert_group_record($data_to_insert)
    {
        $this->db->insert('documents_group_management', $data_to_insert);
        return $this->db->insert_id();
    }

    function insert_group_history($data_to_insert)
    {
        $this->db->insert('documents_group_management_history', $data_to_insert);
    }

    function get_all_documents_group($company_sid, $status = NULL)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if ($status != NULL) {
            $this->db->where('status', $status);
        }

        $this->db->order_by('sort_order', 'asc');
        $records_obj = $this->db->get('documents_group_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_document_group($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('documents_group_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function update_document_group($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('documents_group_management', $data_to_update);
    }

    function get_all_document_2_group($group_sid)
    {
        $this->db->select('document_sid');
        $this->db->where('group_sid', $group_sid);
        $record_obj = $this->db->get('documents_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }



    function get_all_group_2_document($sid)
    {
        $this->db->select('group_sid');
        $this->db->where('document_sid', $sid);
        $record_obj = $this->db->get('documents_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function delete_document_2_group($group_sid)
    {
        $this->db->where('group_sid', $group_sid);
        $this->db->delete('documents_2_group');
    }

    function delete_group_2_document($document_sid)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->delete('documents_2_group');
    }

    function assign_document_2_group($data_to_insert)
    {
        $this->db->insert('documents_2_group', $data_to_insert);
    }

    function fetch_all_company_employees($company_sid)
    {
        $this->db->select('sid,first_name,last_name');
        $this->db->where('parent_sid', $company_sid);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    function assign_document_group_2_empliyees($data_to_insert)
    {
        $this->db->insert('documents_group_2_employee', $data_to_insert);
    }

    function change_document_assign_group_status($group_sid, $user_type, $user_sid, $data_to_update)
    {
        if ($user_type == 'employee') {
            $this->db->where('employer_sid ', $user_sid);
        } else if ($user_type == 'applicant') {
            $this->db->where('applicant_sid ', $user_sid);
        }

        $this->db->where('group_sid ', $group_sid);
        $this->db->update('documents_group_2_employee', $data_to_update);
    }

    function get_assign_group_documents($company_sid, $user_type, $user_sid)
    {
        $this->db->select('documents_2_group.document_sid, documents_group_2_employee.assigned_by_sid');
        $this->db->where('documents_group_2_employee.company_sid', $company_sid);
        $this->db->where('documents_group_2_employee.assign_status', 1);

        if ($user_type == 'employee') {
            $this->db->where('documents_group_2_employee.employer_sid', $user_sid);
        } else if ($user_type == 'applicant') {
            $this->db->where('documents_group_2_employee.applicant_sid', $user_sid);
        }

        $this->db->join('documents_2_group', 'documents_2_group.group_sid = documents_group_2_employee.group_sid', 'left');

        $record_obj = $this->db->get('documents_group_2_employee');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function check_document_already_assigned($company_sid, $user_type, $user_sid, $document_sid)
    {
        $this->db->select('sid, archive');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        //
        if (count($record_arr)) {
            if ($record_arr['archive'] == 1) {
                //
                $d = $record_arr;
                unset($d['sid']);
                $d['doc_sid'] = $record_arr['sid'];
                //
                $this->db->insert('documents_assigned_history', $d);
                //
                $this->db
                    ->where('sid', $record_arr['sid'])
                    ->delete('documents_assigned');
            }
            //
            return 1;
        }

        if (!empty($record_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function check_group_already_assigned($company_sid, $employer_sid, $group_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid', $employer_sid);
        $this->db->where('group_sid', $group_sid);
        $record_obj = $this->db->get('documents_group_2_employee');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function is_document_assign_2_group($group_sid)
    {
        $this->db->select('*');
        $this->db->where('group_sid', $group_sid);
        $record_obj = $this->db->get('documents_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_all_documents_group_assigned($company_sid, $user_type, $user_sid)
    {
        $this->db->select('group_sid');
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'employee') {
            $this->db->where('employer_sid', $user_sid);
        } else if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        }

        $record_obj = $this->db->get('documents_group_2_employee');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function unassign_system_document_from_group($sid)
    {
        $data_to_update = array();
        $data_to_update['w4'] = 0;
        $data_to_update['w9'] = 0;
        $data_to_update['i9'] = 0;
        $data_to_update['dependents'] = 0;
        $data_to_update['direct_deposit'] = 0;
        $data_to_update['drivers_license'] = 0;
        $data_to_update['emergency_contacts'] = 0;
        $data_to_update['occupational_license'] = 0;

        $this->db->where('sid', $sid);
        $this->db->update('documents_group_management', $data_to_update);
    }

    function assign_system_document_2_group($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('documents_group_management', $data_to_update);
    }

    function get_applicant_jobs_list_id($applicant_sid)
    {
        $this->db->select('sid');
        $this->db->where('portal_job_applications_sid', $applicant_sid);
        $result = $this->db->get('portal_applicant_jobs_list')->result_array()[0];
        return $result;
    }

    function get_group_id_based_on_document_id($document_sid)
    {
        $this->db->select('group_sid, document_sid');
        $this->db->where('document_sid', $document_sid);
        $record_obj = $this->db->get('documents_2_group');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }



    function get_all_documents_in_group($group_id, $archive = 2, $pp_flag = 0, $employeeSid = false)
    {
        $payroll_sids = $this->get_payroll_documents_sids();
        $documents_management_sids = $payroll_sids['documents_management_sids'];

        $this->db->select('documents_2_group.group_sid, documents_management.*');
        $this->db->where('documents_2_group.group_sid', $group_id);

        if ($archive < 2) {
            $this->db->where('documents_management.archive', $archive);
        }


        if ($pp_flag) {
            $this->db->group_start();
            $this->db->where('documents_management.visible_to_payroll', 1);
            if (!empty($documents_management_sids)) {
                $this->db->or_where_in('documents_management.sid', $documents_management_sids);
            }
            $this->db->group_end();
        }

        $this->db->group_start();
        $this->db->where('documents_management.is_specific', 0);
        if ($employeeSid) $this->db->or_where('documents_management.is_specific', $employeeSid);
        $this->db->group_end();

        $this->db->join('documents_management', 'documents_management.sid = documents_2_group.document_sid', 'left');

        $records_obj = $this->db->get('documents_2_group');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            foreach ($records_arr as $key => $document) {
                if ($document['group_sid'] != $group_id) {
                    unset($records_arr[$key]);
                }
            }
        }

        return $records_arr;
    }

    function test_query()
    {
        $this->db->select('documents_management.sid, ');
        /*
        SELECT Call.ID, Call.date, Call.phone_number
        FROM Call
        LEFT OUTER JOIN Phone_Book
        ON (Call.phone_number=Phone_book.phone_number)
        WHERE Phone_book.phone_number IS NULL */
    }

    function get_uncategorized_docs($company_sid, $document_ids, $status = 2, $pp_flag = 0, $employeeSid = false)
    {

        $payroll_sids = $this->get_payroll_documents_sids();
        $documents_management_sids = $payroll_sids['documents_management_sids'];

        foreach ($document_ids as $key => $value) {
            $pos = array_search($value, $documents_management_sids);
            unset($documents_management_sids[$pos]);
        }

        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);

        if (!empty($document_ids)) {
            $this->db->where_not_in('sid', $document_ids);
        }

        if ($status < 2) {
            $this->db->where('archive', $status);
        }

        if ($pp_flag) {
            $this->db->group_start();
            $this->db->where('documents_management.visible_to_payroll', 1);
            if (!empty($documents_management_sids)) {
                $this->db->or_where_in('documents_management.sid', $documents_management_sids);
            }
            $this->db->group_end();
        }

        $this->db->group_start();
        $this->db->where('documents_management.is_specific', 0);
        if ($employeeSid) $this->db->or_where('documents_management.is_specific', $employeeSid);
        $this->db->group_end();

        $this->db->order_by('sort_order', 'ASC');
        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        //
        if ($records_arr) {
            //
            $documentIds = array_column($records_arr, 'sid');
            //
            $skipIdObj = $this->getDocumentCategoryIds($documentIds);
            //
            if ($skipIdObj) {
                //
                foreach ($records_arr as $index => $value) {
                    //
                    if (isset($skipIdObj[$value['sid']]) && $value['archive'] == 0) {
                        unset($records_arr[$index]);
                    }
                }
                //
                $records_arr = array_values($records_arr);
            }
        }

        return $records_arr;
    }

    /**
     * Get company documents category ids
     *
     * @param array $ids
     * @param string $type optional
     * @return array
     */
    public function getDocumentCategoryIds(array $ids, string $type = 'documents_management')
    {
        $records =
            $this->db->select('DISTINCT(document_sid)')
            ->where_in([
                'document_sid' => $ids,
                'document_type' => $type
            ])
            ->get('documents_2_category')
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $record) {
            $tmp[$record['document_sid']] = true;
        }
        //
        return $tmp;
    }

    /**
     * Get company documents category ids
     *
     * @param array $ids
     * @param string $type optional
     * @return array
     */
    public function getDocumentGroupIds(array $ids, string $type = 'documents_management')
    {
        $records =
            $this->db->select('DISTINCT(document_sid)')
            ->where_in([
                'document_sid' => $ids
            ])
            ->get('documents_2_group')
            ->result_array();
        //
        if (!$records) {
            return [];
        }
        //
        $tmp = [];
        //
        foreach ($records as $record) {
            $tmp[$record['document_sid']] = true;
        }
        //
        return $tmp;
    }

    function get_access_level_manual_doc($company_sid, $employer_sid, $employer_type, $pp_flag = 0)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $employer_sid);
        $this->db->where('user_type', $employer_type);
        $this->db->where('documents_assigned.document_type', 'confidential');
        $this->db->where('documents_assigned.archive', 0);
        if ($pp_flag) {
            $this->db->where('documents_2_category.category_sid', PP_CATEGORY_SID);
            $this->db->join('documents_2_category', 'documents_2_category.document_sid = documents_assigned.sid', 'inner');
        } else {
            //$this->db->where_not_in('documents_assigned.sid', 'Select `document_sid` from documents_2_category where `category_sid = `'.PP_CATEGORY_SID);
        }
        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_distinct_group_docs($group_ids)
    {
        $this->db->distinct();
        $this->db->select('document_sid');
        $this->db->where_in('group_sid', $group_ids);
        $records_obj = $this->db->get('documents_2_group');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function insert_eev_document($eev_document_data)
    {
        if ($eev_document_data['sid'] > 0) {
            $this->db->where('sid', $eev_document_data['sid']);
            $this->db->update('eev_documents', $eev_document_data);
            unset($eev_document_data['sid']);
            $this->db->insert('eev_documents_history', $eev_document_data);
        } else {
            unset($eev_document_data['sid']);
            $this->db->insert('eev_documents', $eev_document_data);
            $insert_id = $this->db->insert_id();
            $this->db->insert('eev_documents_history', $eev_document_data);

            $this->db->where('employee_sid', $eev_document_data['employee_sid']);
            $this->db->where('form_type', $eev_document_data['document_type'] . "_assigned");
            $this->db->update(
                'eev_required_documents',
                [
                    'eev_documents_sid' => $insert_id,
                    'form_type' => 'uploaded'
                ]
            );
        }
        return $this->db->insert_id();
    }

    function get_form_uploaded($employee_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('employee_sid', $employee_sid);
        $this->db->where('document_type', $document_type);
        $records_obj = $this->db->get('eev_documents');
        $record = $records_obj->row_array();

        return $record;
    }

    function get_form_uploaded_by_id($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('eev_documents');
        $record = $records_obj->row_array();

        return $record;
    }

    function get_eev_required_document($employee_sid, $eev_documents_sid, $form_type)
    {
        $this->db->select('*');
        $this->db->where('employee_sid', $employee_sid);
        $this->db->where('eev_documents_sid', $eev_documents_sid);
        $this->db->where('form_type', $form_type);
        $this->db->order_by('sid', 'DESC');
        $records_obj = $this->db->get('eev_required_documents');
        $record = $records_obj->result_array();

        return $record;
    }

    function getUserSupportingDocument($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('eev_required_documents');
        $record = $records_obj->row_array();

        if (!empty($record)) {
            return $record;
        } else {
            return array();
        }
    }

    function addSupportingDocumentHistory($history)
    {
        $this->db->insert('eev_required_documents_history', $history);
        return $this->db->insert_id();
    }

    function deleteUserSupportingDocument($sid)
    {
        $this->db->where('sid', $sid);
        $this->db->delete('eev_required_documents');
    }

    function get_varification_supporting_document($employee_sid, $form_type)
    {
        $varification_type = '';
        //
        if ($form_type == "I9") {
            $varification_type = 'i9_assigned';
        } else if ($form_type == "W9") {
            $varification_type = 'w9_assigned';
        } else if ($form_type == "W4") {
            $varification_type = 'w4_assigned';
        }
        //
        $this->db->select('*');
        $this->db->where('employee_sid', $employee_sid);
        $this->db->where('form_type', $varification_type);
        $records_obj = $this->db->get('eev_required_documents');
        $records_arr = $records_obj->result_array();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function insert_required_document($data_to_insert)
    {
        if ($data_to_insert['sid'] > 0) {
            $this->db->where('sid', $data_to_insert['sid']);
            $this->db->update('eev_required_documents', $data_to_insert);
        } else {
            $this->db->insert('eev_required_documents', $data_to_insert);
        }
        return $this->db->insert_id();
    }

    function get_assigned_new_documents($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1)
    {

        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive');
        $this->db->where('documents_assigned.company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        //   $this->db->where('documents_management.archive', 0);

        if ($fetch_offer_letter) {
            $this->db->where('documents_assigned.document_type <>', 'offer_letter');
        }

        if ($status) {
            $this->db->where('status', $status);
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_total_documents_for_documents_assignment($company_sid, $pp_flag)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('archive', 0);

        if ($pp_flag) {
            $this->db->where('documents_management.visible_to_payroll', 1);
        }

        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_all_company_documents($company_sid, $employeeSid = false)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->group_start();
        $this->db->where('is_specific', 0);
        if ($employeeSid) $this->db->or_where('is_specific', $employeeSid);
        $this->db->group_end();
        $this->db->where('archive', 0);
        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_total_documents($company_sid, $pp_flag, $addGroupsAndCategories = FALSE)
    {
        $payroll_sids = $this->get_payroll_documents_sids();
        $documents_management_sids = $payroll_sids['documents_management_sids'];

        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('archive', 0);
        $this->db->where('is_specific', 0);
        $this->db->where('isdoctolibrary', 0);

        if ($pp_flag) {
            $this->db->group_start();
            $this->db->where('visible_to_payroll', 1);
            if (!empty($documents_management_sids)) {
                $this->db->or_where_in('documents_management.sid', $documents_management_sids);
            }
            $this->db->group_end();
        }

        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (sizeof($records_arr) && $addGroupsAndCategories) {
            $this->addGroupsAndCategories($records_arr);
        }
        return $records_arr;
    }


    //
    function addGroupsAndCategories(
        &$documents
    ) {
        //
        foreach ($documents as $k => $v) {
            // Get Groups 
            $a = $this->db
                ->select('group_sid')
                ->where('document_sid', $v['sid'])
                ->get('documents_2_group');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            $documents[$k]['groups'] = array();
            if (sizeof($b)) foreach ($b as $g) $documents[$k]['groups'][] = $g['group_sid'];
            // Get Categories 
            $a = $this->db
                ->select('category_sid')
                ->where('document_sid', $v['sid'])
                ->get('documents_2_category');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            $documents[$k]['categories'] = array();
            if (sizeof($b)) foreach ($b as $c) $documents[$k]['categories'][] = $c['category_sid'];
        }
    }

    function get_all_documents_in_category($category_sid, $archive = 2)
    {
        $this->db->select('documents_2_category.category_sid, documents_management.*');
        $this->db->where('documents_2_category.category_sid', $category_sid);

        if ($archive < 2) {
            $this->db->where('documents_management.archive', $archive);
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_2_category.document_sid', 'left');
        $records_obj = $this->db->get('documents_2_category');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }


    // start interaction with table "documents_category_management"

    function get_all_documents_category($company_sid, $status = NULL, $sort_order = NULL)
    {
        //
        addDefaultCategoriesIntoCompany($company_sid);
        //
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->or_where('sid', PP_CATEGORY_SID);

        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        // if($sort_order == 'descending') {
        //     $this->db->order_by('created_date', 'desc');
        // }else{
        //     $this->db->order_by('name', 'asc');
        // }

        $this->db->order_by('sort_order', 'asc');

        $records_obj = $this->db->get('documents_category_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function get_all_documents_active_categories($company_sid)
    {
        //
        addDefaultCategoriesIntoCompany($company_sid);
        //
        $this->db->select('*');
        $this->db->group_start();
        $this->db->where('documents_category_management.company_sid', $company_sid);
        $this->db->or_where('documents_2_category.category_sid', PP_CATEGORY_SID);
        $this->db->group_end();
        $this->db->where('documents_category_management.status', 1);
        $this->db->join('documents_2_category', 'documents_2_category.category_sid = documents_category_management.sid', 'left');

        $record_obj = $this->db->get('documents_category_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function categories_count($company_sid)
    {
        //
        addDefaultCategoriesIntoCompany($company_sid);
        //
        $this->db->select('COUNT(sid) as count')
            ->where('company_sid', $company_sid);
        $record_obj = $this->db->get('documents_category_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        if (!empty($record_arr)) {
            return $record_arr['count'] + 1;
        } else {
            return array();
        }
    }

    function checkCategoryName($categorySid, $companySid)
    {
        //
        addDefaultCategoriesIntoCompany($companySid);
        //
        $this->db
            ->where_in('company_sid', [$companySid])
            ->where('name', $this->input->post('name', true))
            ->from('documents_category_management')
            ->limit(1);

        if ($categorySid != null) $this->db->where("sid <> $categorySid", null);

        return $this->db->count_all_results() ? false : true;
    }

    //
    function getAllCategories(
        $companySid,
        $status = NULL,
        $sort_order = NULL
    ) {
        //
        addDefaultCategoriesIntoCompany($companySid);
        //
        $this->db->select('sid, name')
            ->group_start()
            ->where('company_sid', $companySid)
            ->or_where('sid', PP_CATEGORY_SID)
            ->group_end()
            ->order_by('sort_order', 'asc');
        //
        if ($status != NULL) $this->db->where('status', $status);
        //
        $a = $this->db->get('documents_category_management');
        //
        $b = $a->result_array();
        $a->free_result();

        //
        return $b;
    }

    //
    function getAllCompanyCategories(
        $companySid
    ) {
        //
        addDefaultCategoriesIntoCompany($companySid);
        //
        $a =
            $this->db
            ->select('sid, name')
            ->where('company_sid', $companySid)
            ->where('status', 1)
            ->or_where('sid', PP_CATEGORY_SID)
            ->order_by('sort_order', 'ASC')
            ->get('documents_category_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    function get_document_category($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('documents_category_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_document_category($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('documents_category_management', $data_to_update);
    }

    function insert_category_record($data_to_insert)
    {
        $this->db->insert('documents_category_management', $data_to_insert);
        return $this->db->insert_id();
    }

    // end interaction with table "documents_category_management"

    function insert_category_history($data_to_insert)
    {
        $this->db->insert('documents_category_management_history', $data_to_insert);
    }

    function is_document_assign_2_category($category_sid)
    {
        $this->db->select('*');
        $this->db->where('category_sid', $category_sid);
        $record_obj = $this->db->get('documents_2_category');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 1;
        } else {
            return 0;
        }
    }

    function get_all_documents_category_assigned($company_sid, $user_type, $user_sid)
    {
        $this->db->select('category_sid');
        $this->db->where('company_sid', $company_sid);

        if ($user_type == 'employee') {
            $this->db->where('employer_sid', $user_sid);
        } else if ($user_type == 'applicant') {
            $this->db->where('applicant_sid', $user_sid);
        }

        $record_obj = $this->db->get('documents_category_2_employee');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_document_2_category($category_sid)
    {
        $this->db->select('document_sid');
        $this->db->where('category_sid', $category_sid);
        $record_obj = $this->db->get('documents_2_category');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_manual_doc_visible_payroll_check($document_sid)
    {
        $this->db->select('visible_to_payroll');
        $this->db->where('sid', $document_sid);
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['visible_to_payroll'];
        } else {
            return 0;
        }
    }

    function get_assigned_offer_letter_title($offer_letter_sid)
    {
        $this->db->select('letter_name');
        $this->db->where('sid', $offer_letter_sid);
        $record_obj = $this->db->get('offer_letter');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['letter_name'];
        } else {
            return 'Offer Letter';
        }
    }

    function delete_document_2_category($category_sid)
    {
        $this->db->where('category_sid', $category_sid);
        $this->db->where('document_type', 'documents_management');
        $this->db->delete('documents_2_category');
    }

    function assign_document_2_category($data_to_insert)
    {

        $data_to_insert['document_type'] = 'documents_management';
        $this->db->insert('documents_2_category', $data_to_insert);
    }

    function add_update_categories_2_documents($document_sid, $categories, $document_type)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->where('document_type', $document_type);
        $this->db->delete('documents_2_category');
        if (is_array($categories)) {
            foreach ($categories as $category) {
                $this->db->insert('documents_2_category', ['document_sid' => $document_sid, 'category_sid' => $category, 'document_type' => $document_type]);
            }
        }
    }

    function update_documents_assignment_record($documents_assigned_sid, $data_to_update)
    {
        $previous_record = $this->get_assigned_submitted_document($documents_assigned_sid);
        $previous_record['doc_sid'] = $previous_record['sid'];
        unset($previous_record['sid']);
        $this->db->insert('documents_assigned_history', $previous_record);

        $this->db->where('sid', $documents_assigned_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function categrize_documents($company_sid, $completed_documents, $no_action_required_documents, $access_level_plus)
    {
        $categories = $this->get_all_documents_active_categories($company_sid);
        $categories_documents_completed = array();
        $cmpl_doc = array();
        $completed_documents_copy = $completed_documents;
        if (!empty($categories) && !empty($completed_documents_copy)) {
            foreach ($categories as $key => $category) {
                $categories_documents_completed[$category['category_sid']]['name'] = ucfirst($category['name']);
                $categories_documents_completed[$category['category_sid']]['category_sid'] = $category['category_sid'];
                foreach ($completed_documents_copy as $key => $completed_document) {
                    if ($category['document_sid'] == $completed_document['document_sid']) {
                        $cmpl_doc[] = $completed_document;
                        $categories_documents_completed[$category['category_sid']]['documents'][] = $completed_document;
                        unset($completed_documents[$key]);
                    }
                }
            }
        }
        if (is_array($completed_documents) && count($completed_documents) > 0) {
            $cat_index = count($categories);
            $categories_documents_completed[$cat_index]['name'] = "Uncategorized Documents";
            $categories_documents_completed[$cat_index]['category_sid'] = 0;
            $cmpl_doc = $completed_documents;
            $categories_documents_completed[$cat_index]['documents'] = $completed_documents;
        }
        $noa_docs = [];
        $no_action_required_documents_copy = $no_action_required_documents;
        $categories_no_action_documents = array();
        $no_action_document_categories = array();
        if (!empty($categories) && !empty($no_action_required_documents_copy)) {
            foreach ($categories as $key => $category) {
                $categories_no_action_documents[$category['category_sid']]['name'] = ucfirst($category['name']);
                $categories_no_action_documents[$category['category_sid']]['category_sid'] = $category['category_sid'];
                foreach ($no_action_required_documents_copy as $key => $no_action_document) {
                    if (($category['document_type'] == "documents_assigned" && $category['document_sid'] == $no_action_document['sid']) || ($category['document_type'] == "documents_management" && $category['document_sid'] == $no_action_document['document_sid'])) {
                        if (($access_level_plus && $no_action_document['document_type'] == "confidential") || $no_action_document['document_type'] != "confidential") {

                            $categories_no_action_documents[$category['category_sid']]['documents'][] = $no_action_document;
                            $no_action_document_categories[$no_action_document['sid']][] = $category['category_sid'];
                        }
                        unset($no_action_required_documents[$key]);
                    } else if ((!$access_level_plus && $no_action_document['document_type'] == "confidential")) {
                        unset($no_action_required_documents[$key]);
                    }
                }
            }
        }
        if (count($no_action_required_documents) > 0) {
            $cat_index = count($categories);
            $categories_no_action_documents[$cat_index]['name'] = "Uncategorized Documents";
            $categories_no_action_documents[$cat_index]['category_sid'] = 0;
            $categories_no_action_documents[$cat_index]['documents'] = $no_action_required_documents;
        }
        foreach ($categories_no_action_documents as $key => $categories_no_action_document) {
            if (isset($categories_no_action_document['documents'])) {
                $sorted_docs = $categories_no_action_document['documents'];
                usort($sorted_docs, function ($a, $b) {
                    return strtotime($a['assigned_date']) > strtotime($b['assigned_date']) ? -1 : 1;
                });
                $noa_docs = array_merge($noa_docs, $sorted_docs);
                $categories_no_action_documents[$key]['documents'] = $sorted_docs;
            }
        }
        $data['categories_no_action_documents'] = $categories_no_action_documents;
        $data['categories_documents_completed'] = $categories_documents_completed;
        $data['no_action_document_categories'] = $no_action_document_categories;
        $data['completed_documents'] = $cmpl_doc;
        $data['no_action_documents'] = $noa_docs;

        return $data;
    }
    function delete_category_2_document($document_sid)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->delete('documents_2_category');
    }
    function get_all_category_2_document($sid)
    {
        $this->db->select('category_sid');
        $this->db->where('document_sid', $sid);
        $record_obj = $this->db->get('documents_2_category');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }
    /**
     * Get email template
     * Created on: 10-02-2019
     *
     * @param Int   $adminEmailTemplateId
     * @param Int   $companySid
     *
     * @return Array
     */
    function getEmailTemplate($adminEmailTemplateId, $companySid)
    {
        // First check in company email templates
        $result = $this->db
            ->select('subject, message_body as body, from_name, from_email')
            ->from('portal_email_templates')
            ->where('admin_template_sid', $adminEmailTemplateId)
            ->where('company_sid', $companySid)
            ->where('status', 1)
            ->limit(1)
            ->get();
        //
        $templateArray = $result->row_array();
        $result = $result->free_result();
        // If template found then return it
        if (sizeof($templateArray)) return $templateArray;
        // Check and get the admin template
        $result = $this->db
            ->select('subject, text as body, from_name, from_email')
            ->from('email_templates')
            ->where('sid', $adminEmailTemplateId)
            ->where('status', 1)
            ->limit(1)
            ->get();
        //
        $templateArray = $result->row_array();
        $result = $result->free_result();
        return $templateArray;
    }

    function array_sort_by_column(&$arr, $col, $dir = SORT_DESC)
    {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }

        array_multisort($sort_col, $dir, $arr);
    }

    function change_document_status($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function getUploadedDocumentById($documentId)
    {
        $a = $this->db
            ->select('
            document_title as letter_name,
            document_type as letter_type,
            document_description as letter_body,
            uploaded_document_original_name,
            uploaded_document_s3_name,
            acknowledgment_required,
            download_required,
            signature_required,
            archive,
            sort_order,
            is_confidential,
            has_approval_flow,
            document_approval_note,
            document_approval_employees,
            confidential_employees,
            automatic_assign_in,
            automatic_assign_type,
            managers_list as signers,
            
        ')
            ->where('sid', $documentId)
            ->get('documents_management');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function insertOfferLetter($d)
    {
        $this->db->insert('offer_letter', $d);
        return $this->db->insert_id();
    }

    //
    function updateAssignedDocumentId($nDocumentId, $documentId)
    {
        $this->db
            ->where('document_sid', $documentId)
            ->update('documents_assigned', array(
                'document_sid' => $nDocumentId,
                'document_type' => 'offer_letter'
            ));

        $this->db
            ->where('document_sid', $documentId)
            ->update('documents_assignment', array(
                'document_sid' => $nDocumentId,
                'is_offer_letter' => 1,
                'document_type' => 'offer_letter'
            ));
        //
    }

    //
    function createConvertHistory($d)
    {
        $this->db->insert('document_to_pay_plan', $d);
    }

    //
    function getDocumentById($documentId)
    {
        $a = $this->db
            ->where('sid', $documentId)
            ->get('documents_management');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function getOfferLetterById($documentId)
    {
        $a = $this->db
            ->select('*, letter_name as document_title, letter_body as document_description')
            ->where('sid', $documentId)
            ->get('offer_letter');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function getAssignedDocumentById($Id)
    {
        $a = $this->db
            ->where('sid', $Id)
            ->get('documents_assigned');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function getAssignedGroupDocument($document_sid, $user_sid, $user_type)
    {
        $a = $this->db
            ->where('document_sid', $document_sid)
            ->where('user_sid', $user_sid)
            ->where('user_type', $user_type)
            ->get('documents_assigned');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function getAssignedDocumentByIdAndEmployeeId(
        $Id,
        $employeeId
    ) {
        $a = $this->db
            ->where('document_sid', $Id)
            ->where('user_sid', $employeeId)
            ->get('documents_assigned');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function removeDocument($documentId)
    {
        $this->db
            ->where('sid', $documentId)
            ->delete('documents_management');
    }
    //
    function get_assigned_documents2($company_sid, $user_type, $user_sid = null, $status = 1, $fetch_offer_letter = 1, $archive = 0, $pp_flag = 0)
    {
        $this->db->select('documents_assigned.*,documents_management.acknowledgment_required,documents_management.download_required,documents_management.signature_required,documents_management.archive, documents_management.is_available_for_na, documents_management.allowed_employees,  documents_management.allowed_departments, documents_management.allowed_teams');
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
        if ($pp_flag) {
            $this->db->where('documents_2_category.category_sid', PP_CATEGORY_SID);
            $this->db->join('documents_2_category', 'documents_2_category.document_sid = documents_assigned.sid', 'inner');
        }

        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'left');
        return $this->db->get_compiled_select('documents_assigned');
    }

    function check_applicant_offer_letter_exist($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function check_offer_letter_moved($document_sid, $document_type)
    {
        $this->db->select('*');
        $this->db->where('doc_sid', $document_sid);
        $this->db->where('document_type', $document_type);

        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return 'yes';
        } else {
            return 'no';
        }
    }

    //
    function deleteMovedLetter($documentAssignedId)
    {
        $this->db->where('sid', $documentAssignedId)
            ->delete('documents_assigned');
    }

    function disable_all_previous_letter($company_sid, $user_type, $user_sid, $document_type)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_type', $document_type);
        $this->db->set('status', 0);
        $this->db->set('archive', 1);
        $this->db->update('documents_assigned');
    }

    function set_offer_letter_verification_key($sid, $verification_key, $type = 'applicant')
    {
        $this->db->where('sid', $sid);

        if ($type == 'applicant') {
            $dataToUpdate = array();
            $dataToUpdate['verification_key'] = $verification_key;
            $this->db->update('portal_job_applications', $dataToUpdate);
        } else {

            $dataToUpdate = array();
            $dataToUpdate['emp_offer_letter_key'] = $verification_key;
            $this->db->update('users', $dataToUpdate);
        }
    }

    function get_requested_authorized_content($document_sid, $request_from)
    {
        $this->db->select('*');
        if ($request_from == 'assigned_document_history')
            $this->db->where('doc_sid', $document_sid);
        else
            $this->db->where('sid', $document_sid);

        if ($request_from == 'assigned_document') {
            $record_obj = $this->db->get('documents_assigned');
        } else if ($request_from == 'company_document') {
            $record_obj = $this->db->get('documents_management');
        } else if ($request_from == 'company_offer_letter') {
            $record_obj = $this->db->get('offer_letter');
        } else if ($request_from == 'assigned_document_history') {
            $record_obj = $this->db->get('documents_assigned_history');
        }

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_requested_content($document_sid, $request_type, $request_from, $request_for, $submitted = 0)
    {
        $this->db->select('*');
        if ($request_from == 'assigned_document_history')
            $this->db->where('doc_sid', $document_sid);
        else
            $this->db->where('sid', $document_sid);

        if ($request_from == 'assigned_document') {
            $record_obj = $this->db->get('documents_assigned');
        } else if ($request_from == 'company_document') {
            $record_obj = $this->db->get('documents_management');
        } else if ($request_from == 'company_offer_letter') {
            $record_obj = $this->db->get('offer_letter');
        } else if ($request_from == 'assigned_document_history') {
            $record_obj = $this->db->get('documents_assigned_history');
        }

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $company_sid = $record_arr[0]['company_sid'];
            $user_sid = isset($record_arr[0]['user_sid']) ? $record_arr[0]['user_sid'] : '';
            $user_type = isset($record_arr[0]['user_type']) ? $record_arr[0]['user_type'] : '';
            $document_body = $record_arr[0]['document_description'];

            if ($request_type == 'assigned' && ($request_from == 'assigned_document' || $request_from == 'assigned_document_history' || $request_from == 'company_document')) {

                if ($request_for == 'P&D') {
                    $contant_body = $this->replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_body);
                } else {
                    $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                }

                return $contant_body;
            } else if ($request_type == 'submitted' && ($request_from == 'assigned_document' || $request_from == 'assigned_document_history')) {
                if (!empty($record_arr[0]['authorized_signature'])) {
                    $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr[0]['authorized_signature'] . '" id="show_authorized_signature">';
                } else {
                    $authorized_signature_image = '------------------------------';
                }

                if (!empty($record_arr[0]['authorized_signature_date'])) {
                    $authorized_signature_date = '<p><strong>' . date_with_time($record_arr[0]['authorized_signature_date']) . '</strong></p>';
                } else {
                    $authorized_signature_date = 'Authorize Sign Date :------/-------/----------------';
                }

                $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr[0]['signature_base64'] . '">';
                $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr[0]['signature_initial'] . '">';
                $sign_date = '<p><strong>' . date_with_time($record_arr[0]['signature_timestamp']) . '</strong></p>';

                $document_body = str_replace('{{signature}}', $signature_bas64_image, $document_body);
                $document_body = str_replace('{{inital}}', $init_signature_bas64_image, $document_body);
                $document_body = str_replace('{{sign_date}}', $sign_date, $document_body);
                $document_body = str_replace('{{authorized_signature}}', $authorized_signature_image, $document_body);
                $document_body = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_body);

                if ($request_for == 'P&D') {

                    if (!empty($record_arr[0]['form_input_data'])) {
                        $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                        return $contant_body;
                    } else if (!empty($record_arr[0]['submitted_description'])) {
                        return $record_arr[0]['submitted_description'];
                    } else {
                        $contant_body = $this->replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_body);
                        return $contant_body;
                    }
                } else {
                    if (!empty($record_arr[0]['form_input_data'])) {
                        $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                        return $contant_body;
                    } else if (!empty($record_arr[0]['submitted_description'])) {
                        return $record_arr[0]['submitted_description'];
                    } else {
                        $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                        return $contant_body;
                    }
                }
            } else if ($request_type == 'company' && ($request_from == 'company_offer_letter' || $request_from == 'company_document')) {


                if ($request_for == 'P&D') {
                    $contant_body = $this->replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_body);
                } else {
                    $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                }

                return $contant_body;
            }
        } else {
            return array();
        }
    }

    function get_document_title($document_sid, $request_type, $request_from)
    {
        $this->db->select('*');
        $this->db->where('sid', $document_sid);

        if ($request_from == 'assigned_document') {
            $record_obj = $this->db->get('documents_assigned');
        } else if ($request_from == 'company_document') {
            $record_obj = $this->db->get('documents_management');
        } else if ($request_from == 'company_offer_letter') {
            $record_obj = $this->db->get('offer_letter');
        } else if ($request_from == 'assigned_document_history') {
            $record_obj = $this->db->get('documents_assigned_history');
        }

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $user_sid = $record_arr[0]['user_sid'];
            $user_type = $record_arr[0]['user_type'];
            $company_sid = $record_arr[0]['company_sid'];
            $user_name = '';

            if ($user_type == 'applicant') {
                $user_info = $this->get_applicant_information($company_sid, $user_sid);
                $user_name = $user_info['first_name'] . '_' . $user_info['last_name'];
            } else if ($user_type == 'employee') {
                $user_info = db_get_employee_profile($user_sid);
                $user_name = $user_info[0]['first_name'] . '_' . $user_info[0]['last_name'];
            }

            $document_title = $record_arr[0]['document_title'];
            $title = str_replace(" ", '_', $document_title);
            return $user_name . '_' . $title . '_' . date('Y_m_d');
        } else {
            return 'document';
        }
    }

    function replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_content)
    {
        $value = '------------------------------';
        $document_content = str_replace('{{first_name}}', $value, $document_content);
        $document_content = str_replace('{{firstname}}', $value, $document_content);
        $document_content = str_replace('{{last_name}}', $value, $document_content);
        $document_content = str_replace('{{lastname}}', $value, $document_content);
        $document_content = str_replace('{{email}}', $value, $document_content);
        $document_content = str_replace('{{job_title}}', $value, $document_content);
        $document_content = str_replace('{{company_name}}', $value, $document_content);
        $document_content = str_replace('{{company_address}}', $value, $document_content);
        $document_content = str_replace('{{company_phone}}', $value, $document_content);
        $document_content = str_replace('{{career_site_url}}', $value, $document_content);
        $document_content = str_replace('{{signature}}', $value, $document_content);
        $document_content = str_replace('{{inital}}', $value, $document_content);
        $document_content = str_replace('{{sign_date}}', $value, $document_content);
        $document_content = str_replace('{{signature_print_name}}', $value, $document_content);
        $document_content = str_replace('{{short_text}}', $value, $document_content);
        $value = '------/-------/----------------';
        $document_content = str_replace('{{start_date}}', $value, $document_content);

        $value = 'Date :------/-------/----------------';
        $document_content = str_replace('{{date}}', $value, $document_content);

        $value = 'Please contact with your manager';
        $document_content = str_replace('{{username}}', $value, $document_content);
        $document_content = str_replace('{{password}}', $value, $document_content);

        $authorized_signature = '------------------------------';
        $authorized_signature_date = 'Authorize Sign Date :------/-------/----------------';

        $document_content = str_replace('{{authorized_signature}}', $authorized_signature, $document_content);
        $document_content = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_content);

        $value = '<br><input type="checkbox" class="user_checkbox input-grey"/>';
        $document_content = str_replace('{{checkbox}}', $value, $document_content);

        $value = '<div style="border: 1px dotted #777; padding:5px;background-color:#eee;"  contenteditable="true"></div>';
        $document_content = str_replace('{{text}}', $value, $document_content);

        $value = '<div style="border: 1px dotted #777; padding:5px; min-height: 145px;background-color:#eee;" class="div-editable fillable_input_field" id="div_editable_text" contenteditable="true" data-placeholder="Type Here"></div>';
        $document_content = str_replace('{{text_area}}', $value, $document_content);

        return $document_content;
    }

    function update_employee($sid, $data)
    {
        $this->db->where('sid', $sid);
        $this->db->update('users', $data);
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

    function get_company_all_documents($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('archive', 0);
        $this->db->where('automatic_assign_in >', 0);
        $this->db->order_by('sort_order', 'ASC');
        $records_obj = $this->db->get('documents_management');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function checkAndAssignDoc($document, $cid, $cName)
    {
        $docId = $document['sid'];
        $days = $document['automatic_assign_in'];
        $type = $document['automatic_assign_type'];
        if ($type == 'days') {
            $date = date('Y-m-d H:i:s', strtotime('-' . $days . ' Days'));
            $date2 = date('Y-m-d', strtotime('-' . $days . ' Days'));
        } else {
            $date = date('Y-m-d H:i:s', strtotime('-' . $days . ' Months'));
            $date2 = date('Y-m-d', strtotime('-' . $days . ' Months'));
        }
        $this->db->select('sid,document_sent_on,first_name,last_name,email,PhoneNumber as phone');
        $this->db->where('parent_sid', $cid);
        $this->db->group_start();
        $this->db->where('registration_date <= ', $date);
        $this->db->or_where('joined_at <= ', $date2);
        $this->db->group_end();
        $this->db->where('active', 1);
        $this->db->where('terminated_status', 0);

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        if (!empty($records_arr)) {
            foreach ($records_arr as $user_info) {
                $check_exist = $this->check_assigned_document($docId, $user_info['sid'], 'employee');
                if (empty($check_exist)) {
                    $data_to_insert = array();
                    $data_to_insert['company_sid'] = $cid;
                    $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
                    $data_to_insert['assigned_by'] = 0;
                    $data_to_insert['user_type'] = 'employee';
                    $data_to_insert['user_sid'] = $user_info['sid'];
                    $data_to_insert['document_type'] = $document['document_type'];
                    $data_to_insert['document_sid'] = $document['sid'];
                    $data_to_insert['status'] = 1;
                    $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                    $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                    $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                    $data_to_insert['document_title'] = $document['document_title'];
                    $data_to_insert['document_description'] = $document['document_description'];
                    $this->insert_documents_assignment_record($data_to_insert);
                    //Send Email and SMS
                    //                    $replacement_array = array();
                    //                    $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                    //                    $replacement_array['company_name'] = ucwords($cName);
                    //                    $replacement_array['firstname'] = $user_info['first_name'];
                    //                    $replacement_array['lastname'] = $user_info['last_name'];
                    //                    $replacement_array['first_name'] = $user_info['first_name'];
                    //                    $replacement_array['last_name'] = $user_info['last_name'];
                    //                    $replacement_array['baseurl'] = base_url();
                    //                    $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                    //SMS Start
                    //                    if(empty($user_info['document_sent_on']) || $user_info['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s',strtotime('+'.DOCUMENT_SEND_DURATION.' hours',strtotime($user_info['document_sent_on'])))){
                    //                        $company_sms_notification_status = get_company_sms_status($this, $cid);
                    //                        if($company_sms_notification_status){
                    //                            $notify_by = get_employee_sms_status($this, $user_info['sid']);
                    //                            $sms_notify = 0 ;
                    //                            if(strpos($notify_by['notified_by'],'sms') !== false){
                    //                                $contact_no = $notify_by['PhoneNumber'];
                    //                                $sms_notify = 1;
                    //                            }
                    //                            if($sms_notify){
                    //                                $this->load->library('Twilioapp');
                    //                                // Send SMS
                    //                                $sms_template = get_company_sms_template($this,$cid,'hr_document_notification');
                    //                                $sms_body = replace_sms_body($sms_template['sms_body'],$replacement_array);
                    //                                sendSMS(
                    //                                    $contact_no,
                    //                                    $sms_body,
                    //                                    trim(ucwords(strtolower($replacement_array['company_name']))),
                    //                                    $user_info['email'],
                    //                                    $this,
                    //                                    $sms_notify,
                    //                                    $cid
                    //                                );
                    //                            }
                    //                        }
                    //                        log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);
                    //                        $this->update_employee($user_info['sid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                    //                    }
                }
            }
        }
    }

    function get_all_assigned_offer_letters($company_sid)
    {
        $this->db->select('*');
        $this->db->where('document_type', 'offer_letter');
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function get_all_user_assigned_offer_letter($company_sid, $user_sid, $user_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('document_type', 'offer_letter');
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function disable_current_processing_offer_letter($document_sid)
    {
        $this->db->where('sid', $document_sid);
        $this->db->set('status', 0);
        $this->db->set('archive', 1);
        $this->db->update('documents_assigned');
    }

    function get_current_user_signature($company_sid, $user_type, $user_sid)
    {
        $this->db->select('signature_bas64_image');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('is_active', 1);
        $record_obj = $this->db->get('e_signatures_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['signature_bas64_image'];
        } else {
            return '';
        }
    }

    function isSupportingDocumentExist($documents_sid, $user_sid, $type)
    {
        $this->db->where('eev_documents_sid', $documents_sid);
        $this->db->where('employee_sid', $user_sid);
        $this->db->where('form_type', $type);
        $this->db->from('eev_required_documents');
        return $this->db->count_all_results();
    }


    function deactivate_assign_authorized_documents($company_sid, $document_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_assigned_sid', $document_sid);
        $this->db->set('assigned_status', 0);
        $this->db->update('authorized_document_assigned_manager');
    }

    function fetch_authorized_doc_assign_user($company_sid, $assign_document_sid)
    {
        $this->db->select('assigned_to_sid, assigned_by_date, assigned_status, assigned_to_signature');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_assigned_sid', $assign_document_sid);
        $a = $this->db->get('authorized_document_assigned_manager');
        $b = $a->result_array();
        $a->free_result();
        //
        $r = array('row' => array(), 'ids' => array());
        //
        if (!sizeof($b)) return $r;
        //
        foreach ($b as $k => $v) {
            //
            if ($v['assigned_to_signature'] != null && $v['assigned_to_signature'] != '') $r['row'] = $v;
            //
            $r['ids'][] = $v['assigned_to_sid'];
        }
        //
        return $r;
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

    function getTeams($companySid, $departments)
    {
        //
        if (!$departments || !count($departments)) return [];
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

    function getEmployeeDepartmentsAndTeams(
        $companySid,
        $employerSid
    ) {
        //
        $r = [
            'Departments' => [],
            'Teams' => []
        ];
        // Check if employee belongs to a team
        // $a = $this->db
        // ->select('
        //     departments_management.sid as dSid,
        //     departments_team_management.sid as tSid
        // ')
        // ->join('departments_management', 'departments_management.sid = departments_employee_2_team.department_sid', 'inner')
        // ->join('departments_team_management', 'departments_team_management.sid = departments_employee_2_team.team_sid', 'inner')
        // ->where('departments_employee_2_team.employee_sid', $employerSid)
        // ->where('departments_team_management.company_sid', $companySid)
        // ->where('departments_team_management.status', 1)
        // ->where('departments_team_management.is_deleted', 0)
        // ->where('departments_management.status', 1)
        // ->where('departments_management.is_deleted', 0)
        // ->get('departments_employee_2_team');
        // //
        // $b = $a->result_array();
        // $a = $a->free_result();
        // //
        // if(count($b)) {
        //     $r['Departments'] = array_merge($r['Departments'], array_column($b, 'dSid'));
        //     $r['Teams'] = array_merge($r['Teams'], array_column($b, 'tSid'));
        // }
        //
        $a = $this->db
            ->select('sid, supervisor')
            ->where('company_sid', $companySid)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->order_by('sort_order', 'ASC')
            ->get('departments_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $dIds = [];
        //
        if ($b && count($b)) {
            //
            foreach ($b as $d) {
                $dIds[] = $d['sid'];
                if (in_array($employerSid, explode(',', $d['supervisor']))) $r['Departments'][] = $d['sid'];
            }
            //
            $a = $this->db
                ->select('sid, team_lead, department_sid')
                ->where('company_sid', $companySid)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->where_in('department_sid', $dIds)
                ->order_by('sort_order', 'ASC')
                ->get('departments_team_management');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            foreach ($b as $d) {
                if (in_array($d['department_sid'], $r['Departments'])) $r['Teams'][] = $d['sid'];
                if (in_array($employerSid, explode(',', $d['team_lead']))) $r['Teams'][] = $d['sid'];
            }
        }
        //
        $r['Departments'] = count($r['Departments']) ? array_unique($r['Departments'], SORT_STRING) : $r['Departments'];
        $r['Teams'] = count($r['Teams']) ? array_unique($r['Teams'], SORT_STRING) : $r['Teams'];
        //
        return $r;
    }


    function getEmployees($employeeList, $companySid)
    {
        //
        if (!in_array('-1', $employeeList)) {
            return $employeeList;
        };

        $a = $this->db
            ->select('sid')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = array();
        //
        if (sizeof($b)) $r = array_column($b, 'sid');
        return $r;
    }

    function getEmployeesFromDepartment($departmentList, $companySid)
    {
        //
        $departmentSids = array();
        if (array_search('-1', $departmentList) !== false) {
            $a = $this->db
                ->select('sid')
                ->where('company_sid', $companySid)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->get('departments_management');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            if (sizeof($b)) foreach ($b as $k => $v) $departmentSids[] = $v['sid'];
        } else $departmentSids = $departmentList;
        // 
        if (!sizeof($departmentSids)) return array();
        //
        $a = $this->db
            ->select('employee_sid, team_sid')
            ->where_in('department_sid', $departmentSids)
            ->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = array();
        //
        if (sizeof($b)) {
            //
            $activeTeamsByIds = $this->getActiveTeamsIdsByIds(
                array_column($b, 'team_sid')
            );
            //
            if (!$activeTeamsByIds) {
                return $r;
            }

            foreach ($b as $k => $v) {
                if (in_array($v['team_sid'], $activeTeamsByIds)) {
                    $r[] = $v['employee_sid'];
                }
            }
        }
        return $r;
    }

    public function getActiveTeamsIdsByIds($teamIds)
    {
        $a = $this->db
            ->select('sid')
            ->where_in('sid', $teamIds)
            ->where('status', 1)
            ->where('is_deleted', 0)
            ->get('departments_team_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b ? array_column($b, 'sid') : [];
    }

    function getEmployeesFromTeams($teamList, $companySid)
    {
        //
        $teamSids = array();
        //
        if (array_search('-1', $teamList) !== false) {
            $a = $this->db
                ->select('sid')
                ->where('company_sid', $companySid)
                ->where('status', 1)
                ->where('is_deleted', 0)
                ->get('departments_team_management');
            //
            $b = $a->result_array();
            $a = $a->free_result();
            //
            if (sizeof($b)) $teamSids = array_column($b, 'sid');
        } else $teamSids = $teamList;
        // 
        if (!sizeof($teamSids)) return array();
        //
        $a = $this->db
            ->select('employee_sid')
            ->where_in('team_sid', $teamSids)
            ->get('departments_employee_2_team');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = array();
        //
        if (sizeof($b)) $r = array_column($b, 'employee_sid');
        //
        return $r;
    }

    //
    private function flushManagersFromAssignedDocuments(
        $assignedSid
    ) {

        $this->db
            ->where('document_assigned_sid', $assignedSid)
            ->delete('authorized_document_assigned_manager');
    }

    //
    function addManagersToAssignedDocuments(
        $sids,
        $assignedSid,
        $companySid,
        $employerSid
    ) {
        // Flush previous managers
        $this->flushManagersFromAssignedDocuments($assignedSid);
        //
        if ($sids == null) return;
        //
        $sids = !is_array($sids) ? explode(',', $sids) : $sids;
        //
        foreach ($sids as $k => $v) {
            if ($v == 0) continue;
            $this->db
                ->insert('authorized_document_assigned_manager', array(
                    'company_sid' => $companySid,
                    'document_assigned_sid' => $assignedSid,
                    'assigned_by_sid' => $employerSid,
                    'assigned_to_sid' => $v,
                    'assigned_status' => 0
                ));
            //
            $this->sendEmailToAuthorizedManagers($v, $assignedSid);
        }
    }

    function sendEmailToAuthorizedManagers($managerId, $assignedDocumentId)
    {
        //
        $session = $this->session->userdata('logged_in');
        $CompanySID = $session['company_detail']['sid'];
        $CompanyName = ucwords($session['company_detail']['CompanyName']);
        //
        $hf = message_header_footer(
            $CompanySID,
            $CompanyName
        );
        // Details of the manager
        $managerInfo  = db_get_employee_profile($managerId);
        $assign_to_name  = ucwords($managerInfo[0]['first_name'] . ' ' . $managerInfo[0]['last_name']);
        $assign_to_email = $managerInfo[0]['email'];
        // Get the assigned user details by assigned document id
        $userDetails = $this->getAssignedDocumentUserDetailsById($assignedDocumentId);
        //Send Email
        $replacement_array = array();
        $replacement_array['baseurl']           = base_url();
        $replacement_array['assigned_to_name']  = ucwords($assign_to_name);
        $replacement_array['company_name']  = $CompanyName;
        $replacement_array['employee_name']  = ucwords($userDetails['first_name'] . ' ' . $userDetails['last_name']);
        $replacement_array['link']  = '<a style="background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" target="_blank" href="' . (base_url('authorized_document')) . '">Show Document</a>';
        //
        $user_extra_info = array();
        $user_extra_info['user_sid'] = $managerId;
        $user_extra_info['user_type'] = "employee";
        //
        if ($this->isActiveUser($managerId) && $this->doSendEmail($managerId, 'employee', "HREMSM1")) {
            //
            log_and_send_templated_email(HR_AUTHORIZED_DOCUMENTS_NOTIFICATION, $assign_to_email, $replacement_array, $hf, 1, $user_extra_info);
        }
    }

    //
    function get_authorized_document_assign_manager($company_sid, $document_sid)
    {

        $this->db->select('assigned_to_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_assigned_sid', $document_sid);

        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    //
    function getManagersByAssignedDocument(
        $sid
    ) {
        $a = $this->db
            ->select('assigned_to_sid')
            ->where('assigned_status', 1)
            ->where('document_assigned_sid', $sid)
            ->get('authorized_document_assigned_manager');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!sizeof($b)) return '';
        //
        return remakeEmployeeName(
            db_get_employee_profile(
                $b['assigned_to_sid']
            )[0]
        );
    }

    // Fetch all active employees
    function getAllActiveEmployees(
        $companySid,
        $withExec = true
    ) {
        $this->db
            ->select('
            sid, 
            first_name, 
            last_name, 
            is_executive_admin, 
            access_level, 
            access_level_plus,
            pay_plan_flag,
            job_title
        ')
            ->where('parent_sid', $companySid)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->order_by('first_name', 'ASC');
        //
        if (!$withExec) {
            $this->db->where('is_executive_admin', 0);
        }
        $a = $this->db->get('users');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        cleanTerminatedEmployees($b);
        //
        return $b;
    }

    // Fetch all active documents
    function getAllActiveDocuments(
        $companySid
    ) {
        $a = $this->db
            ->select('
            documents_management.sid,
            documents_management.document_title,
            if(documents_assigned.sid is null, "Not-Assigned", "Assigned") as status
        ')
            ->join('documents_assigned', 'documents_assigned.document_sid = documents_management.sid', 'left')
            ->where('documents_management.company_sid', $companySid)
            ->where('documents_assigned.document_type != ', 'offer_letter')
            ->where('documents_management.archive', 0)
            ->where('documents_management.is_specific', 0)
            ->order_by('documents_management.sort_order', 'ASC')
            ->get('documents_management');
        //
        $b = array_unique($a->result_array(), SORT_REGULAR);
        $a = $a->free_result();


        $a = $this->db
            ->select('
            offer_letter.sid,
            offer_letter.letter_name,
            if(documents_assigned.sid is null, "Not-Assigned", "Assigned") as status
        ')
            ->join('documents_assigned', 'documents_assigned.document_sid = offer_letter.sid', 'left')
            ->where('offer_letter.company_sid', $companySid)
            ->where('documents_assigned.document_type ', 'offer_letter')
            ->where('offer_letter.archive', 0)
            ->where('offer_letter.is_specific', 0)
            ->order_by('offer_letter.sort_order', 'ASC')
            ->get('offer_letter');
        //
        $c = array_unique($a->result_array(), SORT_REGULAR);
        $a = $a->free_result();
        //
        $b = array_merge($b, $c);

        //
        return ($b);
    }
    // Get ompany active documents
    function getAllGroups(
        $companySid,
        $status = NULL
    ) {
        $this->db
            ->select('sid, name')
            ->where('company_sid', $companySid)
            ->order_by('sort_order', 'asc');
        //
        if ($status != NULL) $this->db->where('status', $status);
        //
        $a = $this->db->get('documents_group_management');
        //
        $b = $a->result_array();
        $a->free_result();

        return $b;
    }

    //
    function getOfferLetterBySId(
        $sid,
        $columns = ['*']
    ) {
        $a = $this->db
            ->select(implode(',', $columns))
            ->where('sid', $sid)
            ->get('offer_letter');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function getDocumentHistoryById(
        $sid
    ) {
        $a = $this->db
            ->where('sid', $sid)
            ->get('documents_assigned_history');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        if (sizeof($b)) {
            $tbl = $b['user_type'] == 'employee' ? 'users' : 'portal_job_applications';
            $a = $this->db
                ->select('first_name, last_name, email')
                ->where('sid', $b['user_sid'])
                ->get($tbl);
            //
            $c = $a->row_array();
            $a->free_result();
            //
            $b = array_merge($b, $c);
        }
        //
        return $b;
    }

    // 
    function insertOfferLetterSpecific(
        $a
    ) {
        $this->db->insert('offer_letter', $a);
        return $this->db->insert_id();
    }

    // 
    function updateOfferLetterSpecific(
        $a,
        $sid
    ) {
        $this->db
            ->where('sid', $sid)
            ->update('offer_letter', $a);
        return $sid;
    }


    //
    function getOfferLetterByEmployeeSid(
        $employeeSid
    ) {
        $a = $this->db
            ->select('
            *,
            sid as doc_sid
        ')
            ->where('user_sid', $employeeSid)
            ->where('document_type', 'offer_letter')
            ->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    // 
    function insertOfferLetterIntoHistory(
        $a
    ) {
        $this->db->insert('documents_assigned_history', $a);
        return $this->db->insert_id();
    }

    // 
    function assignOfferLetter(
        $a
    ) {
        $this->db->insert('documents_assigned', $a);
        return $this->db->insert_id();
    }

    // 
    function removeAssignedOfferLetter(
        $sid
    ) {
        $this->db
            ->where('sid', $sid)
            ->delete('documents_assigned');
    }

    //
    function updateDocument(
        $sid,
        $u
    ) {
        //
        $this->db
            ->where('sid', $sid)
            ->update('documents_management', $u);
        //
        return $sid;
    }

    //
    function updateAssignedDocument(
        $sid,
        $u
    ) {
        //
        $this->db
            ->where('sid', $sid)
            ->update('documents_assigned', $u);
        //
        return $sid;
    }

    //
    function getManagersList(
        &$list
    ) {
        foreach ($list as $k => $v) {
            $list[$k]['signers'] = [];
            //
            $a = $this->db
                ->select('group_concat(assigned_to_sid) as signers')
                ->where('document_assigned_sid', $v['sid'])
                ->get('authorized_document_assigned_manager');
            //
            $b = $a->row_array();
            $a = $a->free_result();
            //
            if (sizeof($b)) $list[$k]['signers'] = $b['signers'];
        }
    }

    ///
    function getManagersListSingle(
        &$list
    ) {
        $list['signers'] = [];
        //
        $a = $this->db
            ->select('group_concat(assigned_to_sid) as signers')
            ->where('document_assigned_sid', $list['sid'])
            ->get('authorized_document_assigned_manager');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (sizeof($b)) $list['signers'] = $b['signers'];
    }

    //
    function revokeDocument(
        $documentSid,
        $userSid,
        $u
    ) {
        $this->db
            ->where('document_sid', $documentSid)
            ->where('user_sid', $userSid)
            ->update('documents_assigned', $u);
    }


    //
    function getEmployeeCompletedDocuments($cId, $id, $dIds = '')
    {
        //
        $r = [
            'Assigned' => [],
            'W4' => [],
            'W9' => [],
            'I9' => [],
            'direct_deposit' => '',
            'dependents' => '',
            'emergency_contacts' => '',
            'drivers_license' => '',
            'occupational_license' => '',
            'W4MN' => [],
        ];
        //
        $t = [];
        //
        $this->db
            ->select('*')
            ->where('user_sid', $id)
            ->where('user_type', 'employee')
            ->where('company_sid', $cId)
            ->where('archive', 0)
            ->where('status', 1)
            ->order_by('sid', 'DESC');
        //
        if (!empty($dIds)) {
            $this->db->where_in('sid', explode(':', $dIds));
        }
        //
        $a = $this->db->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (count($b)) {
            isDocumentCompleted($b);
            $r['Assigned'] = $b;
        }
        if (empty($dIds)) {
            //
            $this->getEmployeeI9Form($cId, $id, $r);
            $this->getEmployeeW9Form($cId, $id, $r);
            $this->getEmployeeW4Form($cId, $id, $r);
            $this->getEmployeeW4MNForm($cId, $id, $r);
            $this->getEmployeeGeneralDocuments($cId, $id, $r, true);
        }
        //
        return $r;
    }

    //
    function getUserNoActionDocuments($cId, $id, $type)
    {
        //
        $r = [
            'Assigned' => [],
            'W4' => [],
            'W9' => [],
            'I9' => [],
            'direct_deposit' => '',
            'dependents' => '',
            'emergency_contacts' => '',
            'drivers_license' => '',
            'occupational_license' => ''
        ];
        //
        $t = [];
        //
        $this->db
            ->select('*')
            ->where('user_sid', $id)
            ->where('user_type', $type)
            ->where('company_sid', $cId)
            ->where('archive', 0)
            ->where('status', 1)
            ->group_start()
            ->where('acknowledgment_required IS NULL', NULL)
            ->or_where('acknowledgment_required', '')
            ->or_where('acknowledgment_required', 0)
            ->group_end()
            ->group_start()
            ->where('download_required IS NULL', NULL)
            ->or_where('download_required', '')
            ->or_where('download_required', 0)
            ->group_end()
            ->group_start()
            ->where('signature_required IS NULL', NULL)
            ->or_where('signature_required', '')
            ->or_where('signature_required', 0)
            ->group_end()
            ->order_by('sid', 'DESC');
        //
        if (!empty($dIds)) {
            $this->db->where_in('sid', explode(':', $dIds));
        }
        //
        $a = $this->db->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (count($b)) {
            $r['Assigned'] = $b;
        }
        //
        return $r;
    }

    //
    function getEmployeeI9Form($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'employee')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('status', 1)
            ->get('applicant_i9form');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['I9'] = $b;
    }

    //
    function getEmployeeW9Form($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'employee')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('status', 1)
            ->get('applicant_w9form');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['W9'] = $b;
    }

    //
    function getEmployeeW4Form($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'employee')
            ->where('employer_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('status', 1)
            ->get('form_w4_original');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['W4'] = $b;
    }

    //
    function getEmployeeGeneralDocuments($cId, $id, &$od, $withTemplate = false)
    {
        //
        $a = $this->db
            ->select('document_type')
            ->where('user_type', 'employee')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('is_completed', 1)
            ->where('status', 1)
            ->get('documents_assigned_general');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $od['dependents'] = '';
        $od['direct_deposit'] = '';
        $od['drivers_license'] = '';
        $od['emergency_contacts'] = '';
        $od['occupational_license'] = '';
        //
        if (count($b)) {
            foreach ($b as $v) {
                //
                if (!$withTemplate) {
                    $od[$v['document_type']] = 1;
                    continue;
                }
                //
                $od[$v['document_type']] = $this->getGeneralDocument(
                    $id,
                    'employee',
                    $v['document_type']
                );
            }
        }
    }

    //
    function getApplicantCompletedDocuments($cId, $id)
    {
        //
        $r = [
            'Assigned' => [],
            'W4' => [],
            'W9' => [],
            'I9' => [],
        ];
        //
        $t = [];
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'applicant')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('archive', 0)
            ->where('status', 1)
            ->order_by('sid', 'DESC')
            ->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (count($b)) {
            isDocumentCompleted($b);
            $r['Assigned'] = $b;
        }
        //
        $this->getApplicantI9Form($cId, $id, $r);
        $this->getApplicantW9Form($cId, $id, $r);
        $this->getApplicantW4Form($cId, $id, $r);
        $this->getApplicantGeneralDocuments($cId, $id, $r, true);
        //
        return $r;
    }

    //
    function getApplicantI9Form($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'applicant')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('status', 1)
            ->get('applicant_i9form');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['I9'] = $b;
    }

    //
    function getApplicantW9Form($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'applicant')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('status', 1)
            ->get('applicant_w9form');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['W9'] = $b;
    }

    //
    function getApplicantW4Form($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'applicant')
            ->where('employer_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('status', 1)
            ->get('form_w4_original');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['W4'] = $b;
    }

    //
    function verifyAssignedDocument($sid, $companySid)
    {
        $a = $this->db
            ->select('user_sid, user_type, document_title, document_type, assigned_by')
            ->where('company_sid', $companySid)
            ->where('sid', $sid)
            ->where('status', 1)
            ->where('archive', 0)
            ->get('documents_assigned');
        //
        $b = $a->row_array();
        $a = $a->row_array();
        //
        if (!count($b)) return false;
        //
        $a = $this->db
            ->select('first_name, last_name, email')
            ->where('sid', $b['user_sid'])
            ->get($b['user_type'] == 'employee' ? 'users' : 'portal_job_applications');
        //
        $c = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($c)) return false;
        //
        $b['user'] = $c;
        //
        return $b;
    }


    //
    function updateAssignedDocumentLinkTime(
        $time,
        $sid
    ) {
        $this->db->where('sid', $sid)->update('documents_assigned', ['link_creation_time' => $time]);
    }

    function updateAssignedFederalFillableDocumentLinkTime(
        $time,
        $user_sid,
        $type
    ) {
        $table = "form_w4_original";
        //
        if ($type == "I9") {
            $table = "applicant_i9form";
        }
        //
        if ($type == "I9") {
            $this->db->where('user_sid', $user_sid);
        } else {
            $this->db->where('employer_sid', $user_sid);
        }
        //
        $this->db->where('user_type', 'applicant');
        $this->db->update(
            $table,
            [
                'link_creation_time' => $time
            ]
        );
    }

    //
    function checkForExiredToken(
        $sid,
        $type
    ) {
        //
        if ($type == 'document') {
            $a = $this->db
                ->where('sid', $sid)
                ->where('status', 1)
                ->where('archive', 0)
                ->get('documents_assigned');
        } else {
            $a = $this->db
                ->where('sid', $sid)
                ->where('status', 1)
                ->where('is_completed', 0)
                ->get('documents_assigned_general');
        }
        //
        $b = $a->row_array();
        $a = $a->free_result();
        if (!count($b)) return false;
        //
        $a = $this->db
            ->select('first_name, last_name, email')
            ->where('sid', $b['user_sid'])
            ->get($b['user_type'] == 'employee' ? 'users' : 'portal_job_applications');
        //
        $c = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($c)) return false;
        //
        $b['user'] = $c;
        //
        return $b;
    }

    function checkForFederalFillableExiredToken(
        $type,
        $user_sid
    ) {
        if ($type == 'I9') {
            $a = $this->db
                ->where('user_type', "applicant")
                ->where('user_sid', $user_sid)
                ->where('status', 1)
                ->get('applicant_i9form');
        } else {
            $a = $this->db
                ->where('user_type', "applicant")
                ->where('employer_sid', $user_sid)
                ->where('status', 1)
                ->get('form_w4_original');
        }
        //
        $b = $a->row_array();
        $a = $a->free_result();
        if (!count($b)) return false;
        //
        $a = $this->db
            ->select('first_name, last_name, email')
            ->where('sid', $user_sid)
            ->get('portal_job_applications');
        //
        $c = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($c)) return false;
        //
        $b['user'] = $c;
        //
        return $b;
    }

    function getCompanyInfo($sid)
    {
        $a = $this->db
            ->where('sid', $sid)
            ->order_by(SORT_COLUMN, SORT_ORDER)
            ->get('users');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    //
    function expireLinks()
    {
        $this->db
            ->where('link_creation_time <= ', strtotime('now'))
            ->update('documents_assigned', [
                'link_creation_time' => NULL
            ]);
    }

    //
    function updateMainDocument($u, $c)
    {
        $this->db
            ->where('sid', $c)
            ->update('documents_management', $u);
    }

    //
    function save_documents_download_history($data_to_insert)
    {
        $user_sid = $data_to_insert['user_sid'];
        $user_type = $data_to_insert['user_type'];
        $company_sid = $data_to_insert['company_sid'];
        $download_type = $data_to_insert['download_type'];

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('download_type', $download_type);

        $record_obj = $this->db->get('bulk_documents_download_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $sid =  $record_arr[0]['sid'];

            $this->db
                ->where('sid', $sid)
                ->update('bulk_documents_download_history', $data_to_insert);
        } else {
            $this->db->insert('bulk_documents_download_history', $data_to_insert);
        }
    }
    //
    function get_last_download_document_name($company_sid, $user_sid, $user_type, $download_type)
    {

        $this->db->where('company_sid', $company_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('download_type', $download_type);

        $record_obj = $this->db->get('bulk_documents_download_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return '';
        }
    }

    /**
     * get company accounts  
     * for calendars
     * 
     * @param $companyId Integer
     *
     * @return Array|Bool
     */
    function fetchEmployeesByCompanyId($companyId)
    {
        $result = $this->db
            ->select('sid as id')
            ->select('
            concat(first_name," ",last_name) as fullname,
            first_name,
            active,
            terminated_status,
            last_name,
            pay_plan_flag,
            access_level,
            access_level_plus,
            job_title
        ')
            ->select('case when is_executive_admin = 1 then "Executive Admin" else access_level end as employee_type', false)
            ->where('parent_sid', $companyId)
            ->from('users')
            ->order_by('fullname', 'ASC')
            ->get();
        // fetch result
        $result_arr = $result->result_array();
        // free result from memory 
        // and flush variable data
        $result = $result->free_result();
        // return output
        return $result_arr;
    }

    // 
    function getGeneralAssignedDocuments(
        $userSid,
        $userType,
        $companySid,
        $type = 'all'
    ) {
        //
        $this->db
            ->where('company_sid', $companySid)
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->order_by('sid', 'desc');
        //
        if ($type != 'all') $this->db->where('document_type', $type);
        //
        $a = $this->db->get('documents_assigned_general');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }


    // 
    function assignGeneralDocument(
        $userSid,
        $userType,
        $companySid,
        $documentType,
        $employerSid,
        $sid,
        $note = '',
        $is_required = 0
    ) {
        //
        $is_required = !$is_required ? 0 : $is_required;
        //
        $data['session'] = $this->session->userdata('logged_in');
        $assigned_by = $data['session']['employer_detail']['sid'];

        if ($sid == 0) {
            //



            $this->db
                ->insert(
                    'documents_assigned_general',
                    [
                        'company_sid' => $companySid,
                        'user_sid' => $userSid,
                        'user_type' => $userType,
                        'document_type' => $documentType,
                        'status' => 1,
                        'note' => $note,
                        'is_required' => $is_required,
                        'assigned_at' => date('Y-m-d H:i:s'),
                        'is_completed' => 0,
                        'assigned_by' => $assigned_by
                    ]
                );
            //
            $sid = $this->db->insert_id();
        } else {
            //
            $this->db
                ->where('sid', $sid)
                ->update(
                    'documents_assigned_general',
                    [
                        'status' => 1,
                        'note' => $note,
                        'is_required' => $is_required,
                        'assigned_at' => date('Y-m-d H:i:s'),
                        'is_completed' => 0,
                        'assigned_by' => $assigned_by
                    ]
                );
        }
        //
        $this->db
            ->insert(
                'documents_assigned_general_assigners',
                [
                    'documents_assigned_general_sid' => $sid,
                    'user_sid' => $employerSid,
                    'action' => 'assign'
                ]
            );
        //
        return $sid;
    }


    // 
    function revokeGeneralDocument(
        $userSid,
        $userType,
        $companySid,
        $documentType,
        $employerSid,
        $sid
    ) {
        //
        $this->db
            ->where('company_sid', $companySid)
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('document_type', $documentType)
            ->update(
                'documents_assigned_general',
                [
                    'status' => 0,
                    'is_completed' => 0
                ]
            );
        //
        $this->db
            ->insert(
                'documents_assigned_general_assigners',
                [
                    'documents_assigned_general_sid' => $sid,
                    'user_sid' => $employerSid,
                    'action' => 'revoke'
                ]
            );
        //
        return $sid;
    }


    // 
    function getGeneralDocumentCount(
        $userSid,
        $userType,
        $companySid,
        $type = 'not_completed'
    ) {
        //
        return
            $this->db
            ->where('company_sid', $companySid)
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('status', 1)
            ->where('is_completed', $type == 'completed' ? 1 : 0)
            ->count_all_results('documents_assigned_general');
    }

    // 
    function getGeneralDocuments(
        $userSid,
        $userType,
        $companySid,
        $type = 'not_completed'
    ) {
        //
        $a =
            $this->db
            ->where('company_sid', $companySid)
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('status', 1)
            ->where('is_completed', $type == 'completed' ? 1 : 0)
            ->get('documents_assigned_general');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }


    //
    private function addGeneralDocuments(
        $companySid,
        $userSid,
        &$to
    ) {
        // Get documents
        $documents = $this->getGeneralDocuments(
            $userSid,
            'employee',
            $companySid,
            'not_completed'
        );
        //
        if (!count($documents)) return;
        //
        foreach ($documents as $document) {
            $assigned_on = date('M d Y, D h:i:s', strtotime($document['assigned_at']));
            $assigned_by = $this->getGeneralDocumentAssignedById($document['sid']);
            //
            $now = time();
            $datediff = $now - strtotime($document['assigned_at']);
            $days = round($datediff / (60 * 60 * 24));
            //
            $to[] = array(
                'ID' => 0,
                'Title' => ucwords(str_replace('_', ' ', $document['document_type'])),
                'Type' => 'General',
                'AssignedOn' => $assigned_on,
                'Days' => $days,
                'AssignedBy' => $assigned_by
            );
        }
    }

    private function getGeneralDocumentAssignedById($document_sid)
    {
        $this->db->select('user_sid');
        $this->db->where('documents_assigned_general_sid', $document_sid);
        $record_obj = $this->db->get('documents_assigned_general_assigners');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = 0;

        if (!empty($record_arr)) {
            $return_data = $record_arr['user_sid'];
        }

        return $return_data;
    }


    //
    function getGeneralAssignedDocumentHistory(
        $generalDocumentSid,
        $type = 'employee'
    ) {
        //
        $this->db
            ->select('
            documents_assigned_general_assigners.action,
            documents_assigned_general_assigners.user_sid,
            documents_assigned_general_assigners.user_type,
            documents_assigned_general_assigners.assigned_from,
            documents_assigned_general_assigners.created_at
        ');
        $this->db->select('' . (getUserFields()) . '');
        $this->db->join('users', 'users.sid = documents_assigned_general_assigners.user_sid', 'left');
        $this->db->where('documents_assigned_general_assigners.documents_assigned_general_sid', $generalDocumentSid)
            ->order_by('documents_assigned_general_assigners.sid', 'DESC');
        //
        $a = $this->db->get('documents_assigned_general_assigners');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!count($b)) return $b;
        //
        $c = [];
        //
        foreach ($b as $k => $v) {
            //
            $c[$k]['action'] = $v['action'];
            $c[$k]['assigned_from'] = $v['assigned_from'];
            $c[$k]['created_at'] = $v['created_at'];
            // For applicant
            if ($v['user_type'] == 'applicant') {
                $a =
                    $this->db->select('
                    first_name,
                    last_name
                ')
                    ->where('sid', $v['user_sid'])
                    ->get('portal_job_applications')
                    ->row_array();
                //
                $c[$k]['name'] = ucwords($a['first_name'] . ' ' . $a['last_name']);
            } else {
                $c[$k]['name'] = remakeEmployeeName($v);
            }
            // Set the datetime
            $c[$k]['created_at'] = convertDateTimeToTimeZone(
                $c[$k]['created_at']
            );
        }
        return $c;
    }

    //
    function getUserData(
        $userSid,
        $userType,
        $companySid
    ) {
        //
        $columns = 'first_name, last_name, email, parent_sid, job_title';
        //
        if ($userType == 'applicant') {
            $columns = 'first_name, last_name, email, employer_sid AS parent_sid, desired_job_title AS job_title';
        }
        //
        $a =
            $this->db
            ->select($columns)
            ->where('sid', $userSid)
            ->where($userType == 'employee' ? 'parent_sid' : 'employer_sid', $companySid)
            ->get($userType == 'employee' ? 'users' : 'portal_job_applications');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function checkAndAssignGeneralDocument(
        $userSid,
        $userType,
        $companySid,
        $documentType,
        $employerSid,
        $isCompleted = false,
        $assignedAt = false
    ) {
        //
        $a =
            $this->db
            ->select('sid')
            ->where('document_type', $documentType)
            ->where('user_sid', $userSid)
            ->where('user_type', $userType)
            ->where('company_sid', $companySid)
            ->get('documents_assigned_general');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (
            !count($b)
        ) {
            //
            $key = 'man_d1';
            //
            if ($documentType == 'direct_deposit') {
                $key = 'man_d2';
            } else if ($documentType == 'drivers_license') {
                $key = 'man_d3';
            } else if ($documentType == 'emergency_contacts') {
                $key = 'man_d4';
            } else if ($documentType == 'occupational_license') {
                $key = 'man_d5';
            }
            //
            $isRequired = $this->getDefaultRequiredFlag(
                $companySid,
                $key
            );
            //
            $ins = [
                'company_sid' => $companySid,
                'user_sid' => $userSid,
                'user_type' => $userType,
                'document_type' => $documentType,
                'assigned_at' => date('Y-m-d H:i:s'),
                'status' => 1,
                'is_completed' => (int) $isCompleted,
                'is_required' => (int) $isRequired
            ];

            if ($assignedAt) {
                $ins['assigned_at'] = $assignedAt;
                $ins['updated_at'] = $assignedAt;
                $ins['created_at'] = $assignedAt;
            }
            //
            $this->db
                ->insert(
                    'documents_assigned_general',
                    $ins
                );
            //
            $insertId = $this->db->insert_id();
            //
            if ($isCompleted == 1) {
                //
                $this->db
                    ->insert(
                        'documents_assigned_general_assigners',
                        [
                            'documents_assigned_general_sid' => $insertId,
                            'user_sid' => $userSid,
                            'user_type' => $userType,
                            'assigned_from' => 'direct',
                            'created_at' => $assignedAt,
                            'action' => 'completed'
                        ]
                    );
            } else {
                //
                $this->db
                    ->insert(
                        'documents_assigned_general_assigners',
                        [
                            'documents_assigned_general_sid' => $insertId,
                            'user_sid' => $employerSid,
                            'user_type' => 'employee',
                            'assigned_from' => 'group',
                            'action' => 'assign'
                        ]
                    );
            }
            return $insertId;
        } else if (isset($b['status']) && $b['status'] == 0) {
            return 0;
        }
    }

    //
    function setGID(
        $userType,
        $userSid,
        $companySid,
        $employerSid
    ) {
        $c = [];
        // Get Direct Deposit
        $a =
            $this->db
            ->select('consent_date')
            ->where('users_sid', $userSid)
            ->where('users_type', $userType)
            ->where('company_sid', $companySid)
            ->get('bank_account_details');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        $c[] = count($b);
        //
        if (
            $c[0]
        ) {
            $this->checkAndAssignGeneralDocument(
                $userSid,
                $userType,
                $companySid,
                'direct_deposit',
                $employerSid,
                1,
                !empty($b['consent_date']) ? $b['consent_date'] . ' 00:00:00' : date('Y-m-d H:i:s', strtotime('now'))
            );
        }

        // Get Drivers License
        if (
            $c[] = $this->db
            ->where('users_sid', $userSid)
            ->where('users_type', $userType)
            ->where('license_type', 'drivers')
            ->count_all_results('license_information')
        ) {
            $this->checkAndAssignGeneralDocument(
                $userSid,
                $userType,
                $companySid,
                'drivers_license',
                $employerSid,
                1,
                date('Y-m-d H:i:s', strtotime('now'))
            );
        }

        // Get Occupational License
        if (
            $c[] = $this->db
            ->where('users_sid', $userSid)
            ->where('users_type', $userType)
            ->where('license_type', 'occupational')
            ->count_all_results('license_information')
        ) {
            $this->checkAndAssignGeneralDocument(
                $userSid,
                $userType,
                $companySid,
                'occupational_license',
                $employerSid,
                1,
                date('Y-m-d H:i:s', strtotime('now'))
            );
        }

        // Get Emergency Contacts
        if (
            $c[] = $this->db
            ->where('users_sid', $userSid)
            ->where('users_type', $userType)
            ->count_all_results('emergency_contacts')
        ) {
            $this->checkAndAssignGeneralDocument(
                $userSid,
                $userType,
                $companySid,
                'emergency_contacts',
                $employerSid,
                1,
                date('Y-m-d H:i:s', strtotime('now'))
            );
        }

        // Get Dependents
        if (
            $c[] = $this->db
            ->where('users_sid', $userSid)
            ->where('users_type', $userType)
            ->where('company_sid', $companySid)
            ->count_all_results('dependant_information')
        ) {
            $this->checkAndAssignGeneralDocument(
                $userSid,
                $userType,
                $companySid,
                'dependents',
                $employerSid,
                1,
                date('Y-m-d H:i:s', strtotime('now'))
            );
        }
        // For debugging purpose
        return $c;
    }

    //
    function getUserDataWithCompany(
        $userSid,
        $userType
    ) {
        //
        if ($userType == 'employee') {
            $this->db
                ->where('users.sid', $userSid)
                ->join('users as a', 'a.sid = users.parent_sid', 'inner')
                ->select('' . (getUserFields()) . ' a.CompanyName, users.parent_sid');
        } else {
            $this->db
                ->where('portal_job_applications.sid', $userSid)
                ->join('users as a', 'a.sid = portal_job_applications.employer_sid', 'inner')
                ->select('portal_job_applications.first_name, portal_job_applications.last_name, portal_job_applications.email, a.CompanyName');
        }
        //
        $a = $this->db->get($userType == 'employee' ? 'users' : 'portal_job_applications');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function getGeneralDocument(
        $userSid,
        $userType,
        $documentType
    ) {
        //
        $a = $this->db
            ->select('sid, document_type, company_sid')
            ->where('user_type', $userType)
            ->where('user_sid', $userSid)
            ->where('document_type', $documentType)
            ->where('is_completed', 1)
            ->where('status', 1)
            ->get('documents_assigned_general');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (!count($b)) return '';
        //
        $companySid = $b['company_sid'];
        //
        $template = '';
        //
        switch ($documentType) {
            case "dependents":
                //
                $this->load->model('dependents_model');
                //
                $data = $this->dependents_model->get_dependant_info($userType, $userSid);
                //
                if (count($data)) {
                    $data_countries = db_get_active_countries();
                    //
                    $d = [];

                    foreach ($data_countries as $value) {
                        $states = db_get_active_states($value['sid']);
                        //
                        foreach ($states as $state) {
                            //
                            if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                'Name' => $value['country_name'],
                                'States' => []
                            ];
                            //
                            $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                        }
                    }
                    //
                    $template = $this->load->view('hr_documents_management/templates/dependents', ['data' => $data, 'cs' => $d], true);
                }
                break;
                //
            case "emergency_contacts":
                //
                $this->load->model('emergency_contacts_model');
                //
                $data = $this->emergency_contacts_model->get_emergency_contacts($userType, $userSid);
                //
                if (count($data)) {
                    $data_countries = db_get_active_countries();
                    //
                    $d = [];

                    foreach ($data_countries as $value) {
                        $states = db_get_active_states($value['sid']);
                        //
                        foreach ($states as $state) {
                            //
                            if (!isset($d[$value['sid']])) $d[$value['sid']] = [
                                'Name' => $value['country_name'],
                                'States' => []
                            ];
                            //
                            $d[$value['sid']]['States'][$state['sid']] = ['Name' => $state['state_name']];
                        }
                    }
                    //
                    $template = $this->load->view('hr_documents_management/templates/emergency_contacts', ['data' => $data, 'cs' => $d], true);
                }
                break;
                //
            case "drivers_license":
                //
                $this->load->model('dashboard_model');
                //
                $data = $this->dashboard_model->get_license_info($userSid, $userType, 'drivers');
                //
                if (count($data)) {
                    //
                    $template = $this->load->view('hr_documents_management/templates/drivers_license', ['data' => $data], true);
                }
                break;
                //
            case "occupational_license":
                //
                $this->load->model('dashboard_model');
                //
                $data = $this->dashboard_model->get_license_info($userSid, $userType, 'occupational');
                //
                if (count($data)) {
                    //
                    $template = $this->load->view('hr_documents_management/templates/occupational_license', ['data' => $data], true);
                }
                break;
                //
            case "direct_deposit":
                //
                $this->load->model('direct_deposit_model');
                $data['users_type'] = $userType;
                $data['users_sid'] = $userSid;
                $data['type'] = 'prints';
                $employee_number = $this->direct_deposit_model->get_user_extra_info($userType, $userSid, $companySid);
                $data['employee_number'] = $employee_number;
                $data['data'] = $this->direct_deposit_model->getDDI($userType, $userSid, $companySid);
                //
                $data['data'][0]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][0]['voided_cheque']));
                if (isset($data['data'][1])) $data['data'][1]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][1]['voided_cheque']));

                $data[$userType] = $data['cn'] = $this->direct_deposit_model->getUserData($userSid, $userType);
                //
                $template = $this->load->view('hr_documents_management/templates/direct_deposit', $data, true);
                break;
        }
        //
        return $template;
    }

    //
    function getApplicantGeneralDocuments($cId, $id, &$od, $withTemplate = false)
    {
        //
        $a = $this->db
            ->select('document_type')
            ->where('user_type', 'applicant')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('is_completed', 1)
            ->where('status', 1)
            ->get('documents_assigned_general');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $od['dependents'] = '';
        $od['direct_deposit'] = '';
        $od['drivers_license'] = '';
        $od['emergency_contacts'] = '';
        $od['occupational_license'] = '';
        //
        if (count($b)) {
            foreach ($b as $v) {
                //
                if (!$withTemplate) {
                    $od[$v['document_type']] = 1;
                    continue;
                }
                //
                $od[$v['document_type']] = $this->getGeneralDocument(
                    $id,
                    'applicant',
                    $v['document_type']
                );
            }
        }
    }


    //
    function updateAssignedGDocumentLinkTime(
        $time,
        $sid
    ) {
        $this->db->where('sid', $sid)->update('documents_assigned_general', ['link_creation_time' => $time]);
    }


    //
    function getSingleDocumentCategories(
        $companySid,
        $documentSid,
        $assignedDocumentSid
    ) {
        //
        $a =
            $this->db
            ->select('category_sid')
            ->where('document_sid', $documentSid == 0 ? $assignedDocumentSid : $documentSid)
            ->where('document_type', $documentSid == 0 ? 'documents_assigned' : 'documents_management')
            ->get('documents_2_category');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        if (!count($b)) return $b;
        //
        return array_column($b, 'category_sid');
    }


    //
    function updateDocumentCategories(
        $documentSid,
        $assignedDocumentSid,
        $cats
    ) {
        //
        $id = $documentSid == 0 ? $assignedDocumentSid : $documentSid;
        $type = $documentSid == 0 ? 'documents_assigned' : 'documents_management';
        // Flush the old catgeories
        $this->db
            ->where('document_sid', $id)
            ->where('document_type', $type)
            ->delete('documents_2_category');
        //
        if ($cats !== null) {
            //
            $ins = [];
            //
            foreach ($cats as $cat) $ins[] = [
                'document_sid' => $id,
                'document_type' => $type,
                'category_sid' => $cat
            ];
            //
            $this->db
                ->insert_batch(
                    'documents_2_category',
                    $ins
                );
        }
        //
        return true;
    }


    //
    function getAssignAndSendDocuments(
        $type = false
    ) {
        //
        $this->db
            ->select('
            documents_management.sid, 
            documents_management.assign_type, 
            documents_management.assign_date, 
            documents_management.assign_time, 
            documents_management.assigned_employee_list,
            documents_assigned.assigned_date,
            users.timezone
        ')
            ->join('documents_assigned', 'documents_assigned.document_sid = documents_management.sid', 'inner')
            ->join('users', 'users.sid = documents_management.company_sid', 'inner')
            ->where('documents_management.assign_type <> ', 'none')
            ->where('documents_management.archive', 0)
            ->where('users.active', 1)
            ->where('users.terminated_status', 0);
        //
        if ($type) $this->db->where_in('documents_management.assign_type', $type);
        $a = $this->db->get('documents_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = [];
        if (count($b)) {
            foreach ($b as $v) {
                if (!isset($r[$v['sid']])) {
                    //	
                    $r[$v['sid']] = $v;
                }
            }
            //	
            $b = array_values($r);
        }
        //	
        return $b;
    }

    // TODO:
    // Assign document if it's not assigned
    function reAssignDocument(
        $documentSid,
        $employees
    ) {
        // Get the previous document
        $this->db
            ->where('documents_assigned.document_sid', $documentSid)
            ->where('documents_assigned.user_type', 'employee')
            ->join('users', 'users.sid = documents_assigned.user_sid')
            ->where('users.active', 1)
            ->where('users.terminated_status', 0);
        //
        if (!empty($employees)) {
            $this->db->where_in('documents_assigned.user_sid', $employees);
        }
        //
        $a = $this->db->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        // /
        if (!$b || !count($b)) return $b;
        //
        foreach ($b as $document) {
            // Save a copy in history
            $ld = $document;
            unset($ld['sid']);
            $ld['doc_sid'] = $document['sid'];
            $this->db->insert(
                'documents_assigned_history',
                $ld
            );

            // Flush data and everything else
            $this->db
                ->where('sid', $document['sid'])
                ->update(
                    'documents_assigned',
                    [
                        'acknowledged' => 0,
                        'acknowledged_date' => null,
                        'downloaded' => 0,
                        'downloaded_date' => null,
                        'uploaded' => 0,
                        'uploaded_date' => null,
                        'uploaded_file' => null,
                        'signature_timestamp' => null,
                        'signature' => null,
                        'signature_email' => null,
                        'signature_ip' => null,
                        'signature_base64' => null,
                        'signature_initial' => null,
                        'user_consent' => 0,
                        'status' => 1,
                        'assigned_date' => date('Y-m-d H:i:s', strtotime('now')),
                        'form_input_data' => null,
                        'authorized_signature' => null,
                        'authorized_signature_by' => null,
                        'authorized_signature_date' => null
                    ]
                );

            //
            $user_info = $this->get_employee_information($document['company_sid'], $document['user_sid']);
            //Send Email and SMS
            $replacement_array = array();
            $replacement_array['username'] = $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
            $replacement_array['company_name'] = ucwords($this->getCompanyInfo($document['company_sid'])['CompanyName']);
            $replacement_array['firstname'] = $user_info['first_name'];
            $replacement_array['lastname'] = $user_info['last_name'];
            $replacement_array['first_name'] = $user_info['first_name'];
            $replacement_array['last_name'] = $user_info['last_name'];
            $replacement_array['baseurl'] = base_url();
            $replacement_array['url'] = base_url('hr_documents_management/my_documents');
            //
            $user_extra_info = array();
            $user_extra_info['user_sid'] = $document['user_sid'];
            $user_extra_info['user_type'] = $document['user_type'];
            //
            if ($this->isActiveUser($document['user_sid'])) {
                //
                $this->load->model('hr_documents_management_model');
                if ($this->hr_documents_management_model->doSendEmail($document['user_sid'], $document['user_type'], "HREMS20")) {
                    //
                    log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, [], 1, $user_extra_info);
                }
            }
        }
    }

    function reAssignDocumentNew(
        $documentSid,
        $employees
    ) {
        // Get the previous document
        $this->db
            ->where('document_sid', $documentSid)
            ->where('user_type', 'employee');
        //
        if (!empty($employees)) $this->db->where_in('user_sid', $employees);
        //
        $a = $this->db->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        return $b;
    }

    //
    function getScheduledDocuments(
        $employee,
        $companySid
    ) {
        $this->db
            ->select('
            documents_management.sid as oSid,
            documents_management.document_title,
            documents_management.document_type,
            documents_management.assign_type,
            documents_management.assign_date,
            documents_management.assigned_employee_list,
            documents_assigned.updated_at,
            documents_assigned.*
        ')
            ->where('documents_management.archive', 0)
            ->where('documents_management.is_specific', 0)
            ->where('documents_management.company_sid', $companySid)
            ->where('documents_management.assign_type <> ', 'none')
            ->order_by('documents_assigned.assigned_date', 'desc')
            ->join('documents_assigned', 'documents_assigned.document_sid = documents_management.sid', 'left');
        //
        if ($employee != 'all') $this->db->where('documents_assigned.user_sid', $employee);
        //
        $a = $this->db->get('documents_management');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $c = ['assigned' => 0, 'completed' => 0];
        //
        if (count($b)) {
            $d = [];
            foreach ($b as $k => $v) {
                if (!empty($v['assigned_date']) && DateTime::createFromFormat('Y-m-d H:i:s', $v['assigned_date'])->format('Y-m-d') == date('Y-m-d', strtotime('now'))) {
                    if (isDocumentCompletedCheck($v, true)) $c['completed']++;
                    else $c['assigned']++;
                }
                //
                if (isset($d[$v['oSid']]) ||  ($v['sid'] == 0 && empty($v['document_title']))) {
                    unset($b[$k]);
                    continue;
                } else $d[$v['oSid']] = true;
                //

                //
                $b[$k] = [
                    'sid' => $v['oSid'],
                    'document_title' => $v['document_title'],
                    'document_type' => $v['document_type'],
                    'assign_type' => $v['assign_type'],
                    'assign_date' => $v['assign_date'],
                    'assigned_employee_list' => $v['assigned_employee_list'],
                    'aSid' => $v['sid'],
                    'updated_at' => $v['updated_at']
                ];
            }
            //
            $b = array_values($b);
        }
        //
        return ['Documents' => $b, 'Sa' => $c];
    }


    //
    function getScheduledDocumentsWithEmployees(
        $documentSid,
        $companySid
    ) {
        $this->db
            ->select('
            documents_assigned.*,
            ' . (getUserFields()) . '
        ')
            ->where('documents_assigned.company_sid', $companySid)
            ->where('documents_assigned.document_sid', $documentSid)
            ->where('documents_assigned.user_type', 'employee')
            ->order_by('documents_assigned.assigned_date', 'DESC')
            ->join('users', 'users.sid = documents_assigned.user_sid', 'inner');
        //
        $a = $this->db->get('documents_assigned');
        //
        $b = $a->result_array();
        $a = $a->free_result();
        //
        $r = [];
        //
        if (count($b)) {
            foreach ($b as $k => $v) {
                $r[] = [
                    'EmployeeName' => remakeEmployeeName($v),
                    'EmployeeEmail' => $v['email'],
                    'IsCompleted' => isDocumentCompletedCheck($v, true)
                ];
            }
        }
        //
        return $r;
    }


    //
    function getCompletedDocuments(
        $queryDate,
        $onlyAssignedDocuments = FALSE
    ) {

        $this->db
            ->select('
            portal_completed_documents_list.*,
            portal_completed_documents_list.sid as pSid,
            portal_completed_documents_list.company_sid as pCompanySid,
            portal_completed_documents_list.user_sid as pUserSid,
            portal_completed_documents_list.document_sid as pDocumentSid,
            portal_completed_documents_list.document_type as pDocumentType,
            documents_assigned.*,
            companies.CompanyName,
            ' . (getUserFields()) . '
        ')
            ->join('users', 'users.sid = portal_completed_documents_list.user_sid', 'inner')
            ->join('users as companies', 'companies.sid = portal_completed_documents_list.company_sid', 'inner')
            ->join('documents_assigned', 'documents_assigned.sid = portal_completed_documents_list.document_sid', 'left')
            ->where('portal_completed_documents_list.user_type', 'employee')
            ->where('portal_completed_documents_list.is_sent', '0')
            ->where('portal_completed_documents_list.completion_date', "" . ($queryDate) . "")
            ->where('users.terminated_status', '0')
            ->where('users.active', '1');
        //
        if ($onlyAssignedDocuments == 1) $this->db->where('portal_completed_documents_list.document_sid <> ', '0');
        else if ($onlyAssignedDocuments == 0) $this->db->where('portal_completed_documents_list.document_sid', '0');
        $a = $this->db->get('portal_completed_documents_list');
        //
        $b = $a->result_array();
        $a = $a->free_result();

        //
        if (!$b) return $b;
        //
        $d = [];
        //
        foreach ($b as $k => $v) {
            //
            if (!isset($d[$v['pCompanySid']])) $d[$v['pCompanySid']] = [];
            if (!isset($d[$v['pCompanySid']][$v['pUserSid']])) $d[$v['pCompanySid']][$v['pUserSid']] = [
                'sid' => $v['pSid'],
                'CompanyName' => $v['CompanyName'],
                'user_name' => remakeEmployeeName($v),
                'Documents' => []
            ];
            //
            if ($v['pDocumentSid'] != '0') {
                if (isDocumentCompletedCheck($v, true)) $d[$v['pCompanySid']][$v['pUserSid']]['Documents'][] = $v['document_title'];
            } else
                $d[$v['pCompanySid']][$v['pUserSid']]['Documents'][] = ucwords(str_replace('_', ' ', $v['pDocumentType']));
        }
        //
        return [
            'Ids' => array_column($b, 'pSid'),
            'Data' => $d
        ];
    }


    //
    function updateCompletedDocumentsStatusForReport(
        $ids
    ) {
        $this->db
            ->where_in('sid', $ids)
            ->update(
                'portal_completed_documents_list',
                [
                    'is_sent' => 1
                ]
            );
    }

    //
    function modify_offer_letter_data($document_sid, $data_to_update, $table_name = 'documents_assigned')
    {
        $this->db->where('sid', $document_sid);
        $this->db->update($table_name, $data_to_update);
    }

    //
    function getMyDepartTeams(
        $sid
    ) {
        //
        if (!$sid) {
            return ['departments' => [], 'teams' => []];
        }
        //
        $departments = $this->db
            ->select('sid')
            ->where('departments_management.is_deleted', 0)
            ->where('departments_management.status', 1)
            ->where('FIND_IN_SET(' . ($sid) . ', supervisor)', NULL, FALSE)
            ->get('departments_management')
            ->result_array();
        //
        if (!empty($departments)) {
            $departments = array_column($departments, 'sid');
            //
            $newDept = [];
            //
            foreach ($departments as $dept) {
                //
                $t = explode(',', $dept);
                //
                $newDept = array_merge($t, $newDept);
            }
            //
            $departments = $newDept;
        }
        //
        $teams = $this->db
            ->select('
            departments_team_management.sid
        ')
            ->join('departments_management', 'departments_management.sid = departments_team_management.department_sid', 'inner')
            ->where('departments_management.is_deleted', 0)
            ->where('departments_management.status', 1)
            ->where('departments_team_management.is_deleted', 0)
            ->where('departments_team_management.status', 1)
            ->where('FIND_IN_SET(' . ($sid) . ', team_lead)', NULL, FALSE)
            ->get('departments_team_management')
            ->result_array();
        //
        if (!empty($teams)) {
            //
            $teams = array_column($teams, 'sid');
            //
            $newDept = [];
            //
            foreach ($teams as $dept) {
                //
                $t = explode(',', $dept);
                //
                $newDept = array_merge($t, $newDept);
            }
            //
            $teams = $newDept;
        }
        //
        $departments = array_unique($departments, SORT_STRING);
        //
        return ['departments' => $departments, 'teams' => $teams];
    }

    //
    function hasAssignedDocuments(
        $companyId,
        $role,
        $sid,
        $ses
    ) {
        // Check for confidential as priority
        // on both main and assigned table
        if (
            $this->hasAccessToDocument($companyId, $sid, $ses['departments'], $ses['teams'], $role) ||
            $this->hasAccessToDocument($companyId, $sid, $ses['departments'], $ses['teams'], $role, 'documents_assigned')
        ) {
            return true;
        }
        //
        return false;
    }

    /**
     * 
     */
    public function hasAccessToDocument(
        $companyId,
        $employeeId,
        $departments,
        $teams,
        $role,
        $tbl = 'documents_management'
    ) {
        // In case of confidential
        if (
            $this->db
            ->where('company_sid', $companyId)
            ->where('is_confidential', 1)
            ->where('archive', 0)
            ->group_start()
            ->where('confidential_employees', '-1')
            ->or_where('confidential_employees', NULL)
            ->or_where('confidential_employees', '')
            ->or_where('FIND_IN_SET("' . ($employeeId) . '", confidential_employees) > 0', NULL, FALSE)
            ->group_end()
            ->count_all_results($tbl)
        ) {
            return true;
        }
        // In case of visibility
        $this->db
            ->where('company_sid', $companyId)
            ->where('archive', 0)
            ->where('is_confidential', 0)
            ->group_start()
            ->or_where('FIND_IN_SET("' . ($role) . '", ' . ($tbl == 'documents_assigned' ? 'allowed_roles' : 'is_available_for_na') . ') > 0', NULL, FALSE)
            ->or_where('FIND_IN_SET("' . ($employeeId) . '", allowed_employees) > 0', NULL, FALSE)
            ->or_where('FIND_IN_SET("-1", allowed_departments) > 0', NULL, FALSE)
            ->or_where('FIND_IN_SET("-1", allowed_teams) > 0', NULL, FALSE);
        // For departments
        if (!$departments) {
            foreach ($departments as $department) {
                $this->db->or_where('FIND_IN_SET("' . ($department) . '", allowed_departments) > 0', NULL, FALSE);
            }
        }
        // For teams
        if ($teams) {
            foreach ($teams as $team) {
                $this->db->or_where('FIND_IN_SET("' . ($team) . '", allowed_teams) > 0', NULL, FALSE);
            }
        }
        //
        return $this->db
            ->group_end()
            ->count_all_results($tbl);
    }

    //
    function getUncompletedGeneralAssignedDocuments($companyId, $user_sid, $user_type)
    {
        //
        $this->db->select('document_type');
        $this->db->from('documents_assigned_general');
        $this->db->where('company_sid', $companyId);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('is_completed', 0);
        $this->db->where('is_required', 1);
        $this->db->where('status', 1);
        //
        $data = $this->db->get()
            ->result_array();
        //
        if (empty($data)) return [];
        //
        $r = [];
        //
        foreach ($data as $doc) {
            if ($doc['document_type'] == 'drivers_license') $r[] = 'Drivers License';
            else if ($doc['document_type'] == 'dependents') $r[] = 'Dependents';
            else if ($doc['document_type'] == 'occupational_license') $r[] = 'Occupational License';
            else if ($doc['document_type'] == 'direct_deposit') $r[] = 'Direct Deposit Information';
            else if ($doc['document_type'] == 'emergency_contacts') $r[] = 'Emergency Contacts';
        }
        //
        return $r;
    }

    //
    function getUncompletedAssignedDocuments($companyId, $user_sid, $user_type)
    {

        //
        $r = [];
        //
        $this->db->select('sid');
        $this->db->from('applicant_w9form');
        $this->db->where('company_sid', $companyId);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('is_required', 1);
        $this->db->group_start();
        $this->db->where('user_consent', 0);
        $this->db->or_where('user_consent IS NULL ', FALSE, FALSE);
        $this->db->group_end();
        $this->db->where('status', 1);
        //
        $w9 = $this->db->get()->row_array();
        if (!empty($w9)) {
            $r[] = 'W9';
        }
        //
        $this->db->select('sid');
        $this->db->from('form_w4_original');
        $this->db->where('company_sid', $companyId);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('is_required', 1);
        $this->db->group_start();
        $this->db->where('user_consent', 0);
        $this->db->or_where('user_consent IS NULL ', FALSE, FALSE);
        $this->db->group_end();
        $this->db->where('status', 1);
        //
        $w4 = $this->db->get()->row_array();
        if (!empty($w4)) {
            $r[] = 'W4';
        }
        //
        $this->db->select('sid');
        $this->db->from('applicant_i9form');
        $this->db->where('company_sid', $companyId);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('is_required', 1);
        $this->db->group_start();
        $this->db->where('user_consent', 0);
        $this->db->or_where('user_consent IS NULL ', FALSE, FALSE);
        $this->db->group_end();
        $this->db->where('status', 1);
        //
        $i9 = $this->db->get()->row_array();
        if (!empty($i9)) {
            $r[] = 'I9';
        }
        //
        $this->db->select('documents_assigned.*');
        $this->db->from('documents_assigned');
        $this->db->join('documents_management', 'documents_management.sid = documents_assigned.document_sid', 'inner');
        $this->db->where('documents_assigned.company_sid', $companyId);
        $this->db->where('documents_assigned.user_sid', $user_sid);
        $this->db->where('documents_assigned.user_type', $user_type);
        $this->db->where('documents_assigned.is_required', 1);
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_management.archive', 0);
        //
        $documents = $this->db->get()
            ->result_array();

        //
        if (empty($documents)) {
            return $r;
        }
        //
        foreach ($documents as $document) {
            $chek = isDocumentCompletedCheck($document, true);
            if (!$chek) $r[] = $document['document_title'];
        }
        //
        return $r;
    }

    //
    function archive_authorized_document($document_sid, $user_sid, $action_name, $action_type)
    {
        $data_to_update = array();

        if ($action_name == 'archive') {
            $data_to_update['is_archive'] = 1;
            $data_to_update['archived_by'] = $user_sid;
            $data_to_update['archived_by_date'] = date('Y-m-d H:i:s', strtotime('now'));
        } else if ($action_name == 'active') {
            $data_to_update['is_archive'] = 0;
            $data_to_update['activated_by'] = $user_sid;
            $data_to_update['activated_by_date'] = date('Y-m-d H:i:s', strtotime('now'));
        }

        $this->db->where('document_assigned_sid', $document_sid);

        if ($action_type == 'single') {
            $this->db->where('assigned_to_sid', $user_sid);
        }

        $this->db->update('authorized_document_assigned_manager', $data_to_update);
    }


    //
    function getDocumentType($assignedDocumentId)
    {
        $a =
            $this->db
            ->select("offer_letter_type")
            ->from("documents_assigned")
            ->where("sid", $assignedDocumentId)
            ->get()
            ->row_array();
        //
        if (empty($a['offer_letter_type'])) {
            return 'document';
        } else {
            return 'offer_letter';
        }
    }

    function getAllAuthorizedAssignManagers($company_sid, $document_sid)
    {
        $this->db->select('users.first_name, users.last_name, users.email, users.sid');
        $this->db->where('authorized_document_assigned_manager.company_sid', $company_sid);
        $this->db->where('users.active', 1);
        $this->db->where('users.terminated_status', 0);
        $this->db->where('authorized_document_assigned_manager.document_assigned_sid', $document_sid);
        $this->db->join('users', 'users.sid = authorized_document_assigned_manager.assigned_to_sid', 'inner');
        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function getAuthorizedManagerTemplate($name)
    {
        $this->db->select('sid');
        $this->db->where('name', $name);
        $record_obj = $this->db->get('email_templates');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr['sid'];
        } else {
            return array();
        }
    }

    //
    function updateOfferLetter($data, $id)
    {
        $this->db
            ->where('sid', $id)
            ->update('offer_letter', $data);
    }

    function getAssignedDocumentColumn($assignedDocumentId, $columns = '*')
    {
        $a = $this->db
            ->select('' . (is_array($columns) ? implode(',', $columns) : $columns) . '')
            ->where('sid', $assignedDocumentId)
            ->get('documents_assigned');
        //
        $b = $a->row_array();
        //
        $a->free_result();
        //
        unset($a);
        //
        return $b;
    }

    //
    function getEEOCId($id, $type, $jobId, $location)
    {
        //
        $session = $this->session->userdata('logged_in');
        $assign_by_sid = $session['employer_detail']['sid'];
        //
        $a =
            $this->db
            ->select('*')
            ->where('application_sid', $id)
            ->where('users_type', $type)
            ->order_by('sid', 'desc')
            ->get('portal_eeo_form');
        //
        $b = $a->row_array();
        //
        if (empty($b)) {
            $this->db
                ->insert("portal_eeo_form", [
                    'application_sid' => $id,
                    'users_type' => $type,
                    'status' => 1,
                    'is_expired' => 0,
                    'is_latest' => 1,
                    'portal_applicant_jobs_list_sid' => $jobId,
                    'last_sent_at' => date('Y-m-d H:i:s', strtotime('now')),
                    'assigned_at' => date('Y-m-d H:i:s', strtotime('now')),
                    'last_assigned_by' => $assign_by_sid
                ]);
            //
            $sid = $this->db->insert_id();
            //
        } else {
            //
            $data_to_update = array();
            $data_to_update['status'] = 1;
            $data_to_update['is_latest'] = 1;
            $data_to_update['is_expired'] = 0;
            $data_to_update['is_opt_out'] = null;
            $data_to_update['last_completed_on'] = null;
            $data_to_update['last_assigned_by'] = $this->session->userdata('logged_in')['employer_detail']['sid'];
            $data_to_update['last_sent_at'] = date('Y-m-d H:i:s', strtotime('now'));
            $this->db->where('application_sid', $id);
            $this->db->where('users_type', $type);
            $this->db->update('portal_eeo_form', $data_to_update);
            //
            $sid =
                $this->db
                    ->select('sid')
                    ->where('application_sid', $id)
                    ->where('users_type', $type)
                    ->order_by('sid', 'desc')
                    ->get('portal_eeo_form')
                    ->row_array()['sid'];
            //
            checkAndSetEEOCForUser($id, $type);
        }
        //
        keepTrackVerificationDocument($assign_by_sid, $type, 'assign', $sid, 'eeoc', $location);
        //
        return $sid;
    }

    //
    function getEmployeeInfo($id)
    {
        $a =
            $this->db
            ->select('first_name, last_name, email, parent_sid, job_title')
            ->where('sid', $id)
            ->where('active', 1)
            ->where('terminated_status', 0)
            ->get('users');
        //
        $b = $a->row_array();
        //
        unset($a);
        //
        return $b;
    }

    //
    function getApplicantInfo($id)
    {
        $a =
            $this->db
            ->select('first_name, last_name, email, employer_sid AS parent_sid, desired_job_title AS job_title')
            ->where('sid', $id)
            ->get('portal_job_applications');
        //
        $b = $a->row_array();
        //
        unset($a);
        //
        return $b;
    }


    //
    function getEEOC($id)
    {
        $a =
            $this->db
            ->select('*')
            ->where('sid', $id)
            ->where('status', 1)
            ->get('portal_eeo_form');
        //
        $b = $a->row_array();
        //
        unset($a);
        //
        if (empty($b)) {
            return [];
        }
        //
        if ($b['users_type'] == 'employee') {
            $info = $this->getEmployeeInfo($b['application_sid']);
        } else if ($b['users_type'] == 'applicant') {
            $info = $this->getApplicantInfo($b['application_sid']);
        } else {
            $info = $this->getEmployeeInfo($b['application_sid']);
            $info2 = $this->getApplicantInfo($b['application_sid']);
            //
            if (!empty($info)) {
                $info = $info;
            }
            if (!empty($info2)) {
                $info = $info2;
            }
        }
        //
        $b['user'] = $info;
        //
        return $b;
    }


    //
    function updateEEOC($data, $cond)
    {
        $this->db
            ->where($cond)
            ->update('portal_eeo_form', $data);
    }

    //
    function GetCompanyPendingAuthorizedDocuments($company_sid, $employees = 'all')
    {
        $this->db->select('
            documents_assigned.*,
            authorized_document_assigned_manager.assigned_by_date,
            authorized_document_assigned_manager.is_archive as assign_archive,
            authorized_document_assigned_manager.archived_by_date,
            authorized_document_assigned_manager.assigned_to_sid,
            authorized_document_assigned_manager.archived_by
        ');
        //
        if ($employees != 'all') {
            $this->db->where_in('authorized_document_assigned_manager.assigned_to_sid', $employees);
        }
        $this->db->where('authorized_document_assigned_manager.company_sid', $company_sid);
        $this->db->where('documents_assigned.status', 1);
        $this->db->where('documents_assigned.archive', 0);
        $this->db->group_start();
        $this->db->where('documents_assigned.authorized_signature IS ', NULL);
        $this->db->or_where('documents_assigned.authorized_signature', '');
        $this->db->group_end();
        $this->db->group_start();
        $this->db->where('documents_assigned.document_description like "%{{authorized_signature}}%"', null, false);
        $this->db->or_where('documents_assigned.document_description like "%{{authorized_signature_date}}%"', null, false);
        $this->db->group_end();
        $this->db->join('documents_assigned', 'documents_assigned.sid = authorized_document_assigned_manager.document_assigned_sid', 'inner');
        $this->db->order_by('documents_assigned.authorized_signature', 'ASC', false);
        $this->db->order_by('assigned_by_date', 'DESC', false);
        //
        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (empty($record_arr)) {
            return [];
        }
        //
        $newArray = [];
        //
        $inactive_employee_sid = $this->getAllCompanyInactiveEmployee($company_sid);
        //
        $inactive_applicant_sid = $this->getAllCompanyInactiveApplicant($company_sid);
        // //
        foreach ($record_arr as $d_key => $aut_doc) {
            //
            if (
                (in_array($aut_doc['user_sid'], $inactive_employee_sid) && $aut_doc['user_type'] == 'employee') ||
                (in_array($aut_doc['user_sid'], $inactive_applicant_sid) && $aut_doc['user_type'] == 'applicant')
            ) {
                continue;
            }
            //
            if (!isset($newArray[$aut_doc['assigned_to_sid']])) {
                //
                $newArray[$aut_doc['assigned_to_sid']] = [];
            }
            //
            $newArray[$aut_doc['assigned_to_sid']][] = [
                'document_assigned_sid' => $aut_doc['sid'],
                'assigned_to_sid' => $aut_doc['assigned_to_sid'],
                'user_type' => $aut_doc['user_type'],
                'user_sid' => $aut_doc['user_sid'],
                'document_type' => $aut_doc['document_type'],
                'assigned_by' => $aut_doc['assigned_by'],
                'assigned_by_date' => $aut_doc['assigned_by_date'],
                'document_title' => $aut_doc['document_title'],
                'type' => 'AD'
            ];
        }
        //
        return $newArray;
    }

    //
    function GetCompletedDocumentsWithEmployees($company_sid)
    {
        //
        $query =
            $this->db
            ->select("
            documents_assigned.*,
            users.first_name,
            users.last_name,
            users.job_title,
            users.is_executive_admin,
            users.pay_plan_flag,
            users.access_level,
            users.access_level_plus
        ")
            ->join('users', 'users.sid = documents_assigned.user_sid', 'inner')
            ->where('users.active', 1)
            ->where('users.terminated_status', 0)
            ->where('documents_assigned.company_sid', $company_sid)
            ->where('documents_assigned.status', 1)
            ->where('documents_assigned.archive', 0)
            ->where('documents_assigned.user_type', 'employee')
            ->get('documents_assigned');
        //
        $documents = $query->result_array();
        $query->free_result();
        //
        if (empty($documents)) {
            return [];
        }
        //
        $returnArray = [];
        //
        foreach ($documents as $document) {
            //
            if (isDocumentCompletedCheck($document, true)) {
                //
                if (!isset($returnArray[$document['document_sid']])) {
                    $returnArray[$document['document_sid']] = [
                        'id' => $document['document_sid'],
                        'title' => $document['document_title'],
                        'employees' => []
                    ];
                }
                //
                $returnArray[$document['document_sid']]['employees'][] = [
                    'a_sid' => $document['sid'],
                    'sid' => $document['user_sid'],
                    'name' => remakeEmployeeName($document)
                ];
            }
        }
        //
        return $returnArray;
    }

    //
    function getCompanysid($sid, $type)
    {
        $company_sid = 0;
        //
        if ($type == "applicant") {
            $this->db->select('employer_sid');
            $this->db->where('sid', $sid);

            $record_obj = $this->db->get('portal_job_applications');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $company_sid = $record_arr['employer_sid'];
            }
        } else {
            $this->db->select('parent_sid');
            $this->db->where('sid', $sid);

            $record_obj = $this->db->get('users');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();

            if (!empty($record_arr)) {
                $company_sid = $record_arr['parent_sid'];
            }
        }
        //
        return $company_sid;
    }

    function get_portal_detail($company_id)
    {
        $this->db->select('eeo_form_status');
        $this->db->where('user_sid', $company_id);
        $result = $this->db->get('portal_employer')->row_array();

        if (!empty($result)) {
            return $result['eeo_form_status'];
        } else {
            return 0;
        }
    }

    public function hasEEOCPermission($company_id, $column)
    {
        //
        $record =
            $this->db->select($column)
            ->where('user_sid', $company_id)
            ->get('portal_employer')
            ->row_array();
        //
        if (empty($record)) {
            return 0;
        }
        //
        return $record[$column];
    }

    function get_user_eeo_form_info($sid, $type, $checkExpired = true)
    {
        $this->db->select('*');
        $this->db->where('application_sid', $sid);
        $this->db->where('users_type', $type);
        if ($checkExpired) {
            $this->db->where('is_expired', 1);
        }
        $this->db->order_by('sid', 'DESC');
        $result = $this->db->get('portal_eeo_form')->result_array();
        $return_data = array();

        if (!empty($result)) {
            $return_data = $result[0];
        }

        return $return_data;
    }

    public function getMyAssignApprovalInfo($employee_sid)
    {

        $this->db->select('portal_document_assign_flow_employees.sid');
        $this->db->select('portal_document_assign_flow_employees.portal_document_assign_sid');
        $this->db->select('portal_document_assign_flow_employees.assign_on');
        $this->db->select('portal_document_assign_flow_employees.assigner_sid as approver_sid');
        $this->db->select('documents_assigned.sid as document_sid');
        $this->db->select('documents_assigned.document_title');
        $this->db->select('documents_assigned.document_type');
        $this->db->select('documents_assigned.user_sid');
        $this->db->select('documents_assigned.user_type');
        $this->db->select('portal_document_assign_flow.assigner_note');

        $this->db->where('portal_document_assign_flow_employees.assigner_sid', $employee_sid);
        $this->db->where('portal_document_assign_flow_employees.status', 1);
        $this->db->where('portal_document_assign_flow_employees.assigner_turn', 1);
        $this->db->where('portal_document_assign_flow.assign_status', 1);
        $this->db->where('documents_assigned.approval_process', 1);

        $this->db->join('portal_document_assign_flow', 'portal_document_assign_flow.sid = portal_document_assign_flow_employees.portal_document_assign_sid', 'inner');
        $this->db->join('documents_assigned', 'documents_assigned.approval_flow_sid = portal_document_assign_flow.sid', 'inner');
        $records_obj = $this->db->get('portal_document_assign_flow_employees');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function GetMyAcceptedAndRejected($employee_sid)
    {
        //
        $this->db->select('sid, portal_document_assign_sid, assign_on, note as approval_note, approval_status, action_date');
        $this->db->where('assigner_sid', $employee_sid);
        $this->db->where('approval_status <> ', NULL);
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function getAssignApprovalDocumentInfo($document_sid)
    {
        //
        $this->db->select('document_sid, assigner_note');
        $this->db->where('sid', $document_sid);
        $this->db->where('assign_status', 1);
        $record_obj = $this->db->get('portal_document_assign_flow');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    function saveApproverAction($approver_reference, $document_sid, $data_to_update)
    {
        $this->db->where('portal_document_assign_sid', $document_sid);
        //
        if (is_numeric($approver_reference) && $approver_reference > 0) {
            $this->db->where('assigner_sid', $approver_reference);
        } else {
            $this->db->where('approver_email', $approver_reference);
        }
        //
        $this->db->update('portal_document_assign_flow_employees', $data_to_update);
    }

    function updateApproversInfo($flow_sid, $data_to_update)
    {
        $this->db->where('portal_document_assign_sid', $flow_sid);
        $this->db->update('portal_document_assign_flow_employees', $data_to_update);
    }

    function getnextApproversInfo($approval_flow_sid)
    {
        //
        $this->db->where('portal_document_assign_sid', $approval_flow_sid);
        $this->db->where('status', 1);
        $this->db->where('assigner_turn', 0);
        $this->db->where('assign_on', NULL);
        $this->db->limit(1);
        $this->db->order_by('sid', 'asc');
        $record_obj = $this->db->get('portal_document_assign_flow_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function GetAssignedIdById($sid)
    {
        //
        $this->db->select('portal_document_assign_sid');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('portal_document_assign_flow_employees');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        return $record_arr['portal_document_assign_sid'];
    }

    function GetAssignedDocumentInfo($sid)
    {
        //
        $this->db->where('document_sid', $sid);
        $record_obj = $this->db->get('portal_document_assign_flow');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function updateApprovalDocument($flow, $data_to_update)
    {
        $this->db->where('sid', $flow);
        $this->db->update('portal_document_assign_flow', $data_to_update);
    }

    function disableApproverAssignDocs($document_sid, $data_to_update)
    {
        $this->db->where('portal_document_assign_sid', $document_sid);
        $this->db->update('portal_document_assign_flow_employees', $data_to_update);
    }

    public function getApprovedDocumentInfo($document_sid)
    {
        //
        $this->db->select('*');
        $this->db->where('sid', $document_sid);
        $this->db->where('assign_status', 1);
        $record_obj = $this->db->get('portal_document_assign_flow');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function getAllDocumentInfo($document_sid)
    {
        //
        $this->db->select('*');
        $this->db->where('sid', $document_sid);
        $this->db->where('assign_status <>', 0);
        $record_obj = $this->db->get('portal_document_assign_flow');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function getAllDocumentApprovers($document_sid)
    {
        //
        $this->db->select('*');
        $this->db->where('portal_document_assign_sid', $document_sid);
        // $this->db->where('status', 1);
        $this->db->order_by('sid', 'desc');
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function get_user_approval_pending_documents($user_type, $user_sid)
    {
        //
        $this->db->select('document_sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('approval_process', 1);
        $this->db->where('document_type <>', "offer_letter");
        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function get_user_approval_pending_offer_letters($user_type, $user_sid)
    {
        //
        $this->db->select('document_sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('approval_process', 1);
        $this->db->where('document_type', "offer_letter");
        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function getApprovedDocumentRowSid($user_type, $user_sid, $document_sid)
    {
        //
        $this->db->select('sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('assign_status', 1);
        $this->db->where('document_sid', $document_sid);
        $record_obj = $this->db->get('portal_document_assign_flow');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = 0;

        if (!empty($record_arr)) {
            $return_data = $record_arr['sid'];
        }

        return $return_data;
    }

    /**
     * 
     */
    function DisableAssignedOfferLetter(
        $userId,
        $userType
    ) {
        // Get the ids
        $q =  $this->db
            ->select('sid')
            ->where([
                'user_sid' => $userId,
                'user_type' => $userType
            ])->get('portal_document_assign_flow')->result_array();
        //
        if (empty($q)) {
            return true;
        }
        //
        $ids = array_column($q, 'sid');
        //
        $this->db
            ->where([
                'user_sid' => $userId,
                'user_type' => $userType
            ])
            ->update('portal_document_assign_flow', [
                'status' => 0,
                'assign_status' => 0
            ]);
        //
        $this->db
            ->where_in('portal_document_assign_sid', $ids)
            ->update('portal_document_assign_flow_employees', [
                'status' => 0,
                'assigner_turn' => 0
            ]);
    }

    /**
     * Get the ids of all the documents
     * 
     * @param number $userId
     * @param string $userType
     * @return
     */
    function GetFlowDocumentIds(
        $userId,
        $userType
    ) {
        //
        $q = $this->db
            ->select('sid')
            ->where([
                'user_sid' => $userId,
                'user_type' => $userType
            ])
            ->get('portal_document_assign_flow')
            ->result_array();
        //
        if (empty($q)) {
            return [];
        }
        //
        return array_column($q, 'sid');
    }

    function get_requested_generated_document_content($document_sid, $request_from)
    {
        $this->db->select('*');
        $this->db->where('sid', $document_sid);

        if ($request_from == 'assigned_document') {
            $record_obj = $this->db->get('documents_assigned');
        } else if ($request_from == 'company_document') {
            $record_obj = $this->db->get('documents_management');
        } else if ($request_from == 'company_offer_letter') {
            $record_obj = $this->db->get('offer_letter');
        } else if ($request_from == 'assigned_document_history') {
            $record_obj = $this->db->get('documents_assigned_history');
        }

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_requested_generated_document_content_body($document_sid, $request_type, $request_from, $request_for, $submitted = 0)
    {
        $this->db->select('*');
        $this->db->where('sid', $document_sid);

        if ($request_from == 'assigned_document') {
            $record_obj = $this->db->get('documents_assigned');
        } else if ($request_from == 'company_document') {
            $record_obj = $this->db->get('documents_management');
        } else if ($request_from == 'company_offer_letter') {
            $record_obj = $this->db->get('offer_letter');
        } else if ($request_from == 'assigned_document_history') {
            $record_obj = $this->db->get('documents_assigned_history');
        }

        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {

            $company_sid = $record_arr[0]['company_sid'];
            $user_sid = isset($record_arr[0]['user_sid']) ? $record_arr[0]['user_sid'] : '';
            $user_type = isset($record_arr[0]['user_type']) ? $record_arr[0]['user_type'] : '';
            $document_body = $record_arr[0]['document_description'];

            if ($request_type == 'assigned' && ($request_from == 'assigned_document' || $request_from == 'assigned_document_history')) {

                if ($request_for == 'P&D') {
                    $contant_body = $this->replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_body);
                } else {
                    $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                }

                return $contant_body;
            } else if ($request_type == 'submitted' && ($request_from == 'assigned_document' || $request_from == 'assigned_document_history')) {
                if (!empty($record_arr[0]['authorized_signature'])) {
                    $authorized_signature_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr[0]['authorized_signature'] . '" id="show_authorized_signature">';
                } else {
                    $authorized_signature_image = '------------------------------';
                }

                if (!empty($record_arr[0]['authorized_signature_date'])) {
                    $authorized_signature_date = '<p><strong>' . date_with_time($record_arr[0]['authorized_signature_date']) . '</strong></p>';
                } else {
                    $authorized_signature_date = 'Authorize Sign Date :------/-------/----------------';
                }

                $signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr[0]['signature_base64'] . '">';
                $init_signature_bas64_image = '<img style="max-height: ' . SIGNATURE_MAX_HEIGHT . ';" src="' . $record_arr[0]['signature_initial'] . '">';
                $sign_date = '<p><strong>' . date_with_time($record_arr[0]['signature_timestamp']) . '</strong></p>';

                $document_body = str_replace('{{signature}}', $signature_bas64_image, $document_body);
                $document_body = str_replace('{{inital}}', $init_signature_bas64_image, $document_body);
                $document_body = str_replace('{{sign_date}}', $sign_date, $document_body);
                $document_body = str_replace('{{authorized_signature}}', $authorized_signature_image, $document_body);
                $document_body = str_replace('{{authorized_signature_date}}', $authorized_signature_date, $document_body);

                if ($request_for == 'P&D') {

                    if (!empty($record_arr[0]['form_input_data'])) {
                        $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                        return $contant_body;
                    } else if (!empty($record_arr[0]['submitted_description'])) {
                        return $record_arr[0]['submitted_description'];
                    } else {
                        $contant_body = $this->replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_body);
                        return $contant_body;
                    }
                } else {
                    if (!empty($record_arr[0]['form_input_data'])) {
                        $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                        return $contant_body;
                    } else if (!empty($record_arr[0]['submitted_description'])) {
                        return $record_arr[0]['submitted_description'];
                    } else {
                        $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                        return $contant_body;
                    }
                }
            } else if ($request_type == 'company' && ($request_from == 'company_offer_letter' || $request_from == 'company_document')) {


                if ($request_for == 'P&D') {
                    $contant_body = $this->replace_magic_tag_for_print_and_download($company_sid, $document_sid, $document_body);
                } else {
                    $contant_body = replace_tags_for_document($company_sid, $user_sid, $user_type, $document_body, $document_sid, 0, false, false, $submitted);
                }

                return $contant_body;
            }
        } else {
            return array();
        }
    }

    function change_group_document_status($document_sid, $user_type, $user_sid, $status)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        $this->db->set('status', $status);
        $this->db->update('documents_assigned');
    }

    function reassign_group_document($document_sid, $user_type, $user_sid, $data_to_update)
    {
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    function reassign_general_document($document_name, $user_sid, $user_type)
    {
        $this->db->select('sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_name);
        $records_obj = $this->db->get('documents_assigned_general');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        $return_data = "not_assign";

        if (!empty($records_arr)) {
            //
            $this->db->where('sid', $records_arr["sid"]);
            $this->db->set('is_completed', 0);
            $this->db->set('status', 1);
            $this->db->update('documents_assigned_general');
            //
            $this->db->where('documents_assigned_general_sid', $records_arr["sid"]);
            $this->db->set('action', "assign");
            $this->db->set('assigned_from', "group");
            $this->db->update('documents_assigned_general_assigners');
            //
            $return_data = "already_assign";
        }

        return $return_data;
    }


    function revoke_general_document($document_name, $user_sid, $user_type)
    {
        $this->db->select('sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_type', $document_name);
        $records_obj = $this->db->get('documents_assigned_general');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            //
            $this->db->where('sid', $records_arr["sid"]);
            $this->db->set('status', 0);
            $this->db->update('documents_assigned_general');
            //
            $this->db->where('documents_assigned_general_sid', $records_arr["sid"]);
            $this->db->set('action', "revoke");
            $this->db->set('assigned_from', "group");
            $this->db->update('documents_assigned_general_assigners');
            //
        }
    }

    function get_document_authorized_managers($company_sid, $document_sid)
    {
        $this->db->select('assigned_to_sid');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document_assigned_sid', $document_sid);
        //
        $record_obj = $this->db->get('authorized_document_assigned_manager');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function check_if_approval_document($user_type, $user_sid, $document_sid)
    {
        $this->db->select('sid');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('document_sid', $document_sid);
        //
        $record_obj = $this->db->get('portal_document_assign_flow');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            $this->db->select('assigner_sid');
            $this->db->where('portal_document_assign_sid', $record_arr["sid"]);
            //
            $record_obj = $this->db->get('portal_document_assign_flow_employees');
            $record_arr = $record_obj->result_array();
            $record_obj->free_result();
            //
            if (!empty($record_arr)) {
                return $record_arr;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function check_if_document_has_history($user_type, $user_sid, $document_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('doc_sid', $document_sid);
        $this->db->where('user_consent', 1);
        //
        $record_obj = $this->db->get('documents_assigned_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function is_I9_history_exist($i9_form_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('i9form_ref_sid', $i9_form_sid);
        $this->db->where('user_consent', 1);
        $this->db->order_by('sid', 'desc');
        //
        $record_obj = $this->db->get('applicant_i9form_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function is_W9_history_exist($w9_form_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('w9form_ref_sid', $w9_form_sid);
        $this->db->where('user_consent', 1);
        $this->db->order_by('sid', 'desc');
        //
        $record_obj = $this->db->get('applicant_w9form_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    function is_W4_history_exist($w4_form_sid, $user_type, $user_sid)
    {
        $this->db->select('*');
        $this->db->where('user_type', $user_type);
        $this->db->where('employer_sid', $user_sid);
        $this->db->where('form_w4_ref_sid', $w4_form_sid);
        $this->db->where('user_consent', 1);
        $this->db->order_by('sid', 'desc');
        //
        $record_obj = $this->db->get('form_w4_original_history');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    public function fetch_document_from_history($form_name, $document_sid, $user_type, $user_sid)
    {
        $this->db->select('*');

        if ($form_name == 'w4') {
            $this->db->where('user_type', $user_type);
            $this->db->where('employer_sid', $user_sid);
            $this->db->where('user_consent', 1);
            $this->db->from('form_w4_original_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } elseif ($form_name == 'w9') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('user_consent', 1);
            $this->db->from('applicant_w9form_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        } else {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('user_consent', 1);
            $this->db->from('applicant_i9form_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }

        if ($form_name == 'eeoc') {
            $this->db->where('users_type', $user_type);
            $this->db->where('application_sid', $user_sid);
            $this->db->where('user_consent', 1);
            $this->db->from('portal_eeo_form_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }

        if ($form_name == 'user_document') {
            $this->db->where('user_type', $user_type);
            $this->db->where('user_sid', $user_sid);
            $this->db->where('doc_sid', $document_sid);
            $this->db->where('user_consent', 1);
            $this->db->from('documents_assigned_history');
            $this->db->order_by('sid', 'desc');

            $records_obj = $this->db->get();
            $records_arr = $records_obj->result_array();
            $records_obj->free_result();
        }

        if (sizeof($records_arr) > 0) {
            return $records_arr;
        } else {
            return array();
        }
    }

    /**
     * Get all the active documents belongs
     * to library
     * 
     * @author Nisar [05-11-2022]
     * @param number $company_sid
     * @return array
     */
    function get_all_paginate_library_documents($company_sid)
    {
        $this->db->select('document_title,document_type,sid,sid as document_sid');
        $this->db->where('documents_management.company_sid', $company_sid);
        $this->db->where('documents_management.isdoctolibrary', 1);
        $this->db->where('documents_management.archive', 0);
        $this->db->order_by('documents_management.sid', 'DESC');
        //
        $record_obj = $this->db->get('documents_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        //
        return !$record_arr ? [] : $record_arr;
    }

    // Check and Assigne  groups
    //----------  Check and Assigne  groups
    function checkAndAssignGroups($company_sid, $user_type, $user_sid, $pp_flag, $employer_sid)
    {
        $groups = $this->get_all_documents_group($company_sid, 1);
        $active_groups = [];
        $in_active_groups = [];
        $group_ids = [];
        $group_status = '';
        $sendGroupEmail = 0;

        if (!empty($groups)) {
            foreach ($groups as $key => $group) {
                $document_status = $this->is_document_assign_2_group($group['sid']);
                $groups[$key]['document_status'] = $document_status;
                //start
                $group_status = $group['status'];
                $group_sid = $group['sid'];
                $group_ids[] = $group_sid;
                $group_documents = $this->get_all_documents_in_group($group_sid, 0, $pp_flag);

                if ($group_status) {
                    $active_groups[] = array(
                        'sid' => $group_sid,
                        'name' => $group['name'],
                        'sort_order' => $group['sort_order'],
                        'description' => $group['description'],
                        'created_date' => $group['created_date'],
                        'w4' => $group['w4'],
                        'w9' => $group['w9'],
                        'i9' => $group['i9'],
                        'eeoc' => $group['eeoc'],
                        'direct_deposit' => $group['direct_deposit'],
                        'drivers_license' => $group['drivers_license'],
                        'occupational_license' => $group['occupational_license'],
                        'emergency_contacts' => $group['emergency_contacts'],
                        'dependents' => $group['dependents'],
                        'documents_count' => count($group_documents),
                        'documents' => $group_documents
                    );
                } else {
                    $in_active_groups[] = array(
                        'sid' => $group_sid,
                        'name' => $group['name'],
                        'sort_order' => $group['sort_order'],
                        'description' => $group['description'],
                        'created_date' => $group['created_date'],
                        'w4' => $group['w4'],
                        'w9' => $group['w9'],
                        'i9' => $group['i9'],
                        'eeoc' => $group['eeoc'],
                        'direct_deposit' => $group['direct_deposit'],
                        'drivers_license' => $group['drivers_license'],
                        'occupational_license' => $group['occupational_license'],
                        'emergency_contacts' => $group['emergency_contacts'],
                        'dependents' => $group['dependents'],
                        'documents_count' => count($group_documents),
                        'documents' => $group_documents
                    );
                }

                //end 
            }
        }

        $groups_assign = $this->get_all_documents_group_assigned($company_sid, $user_type, $user_sid);
        $assigned_groups = array();



        if (!empty($groups_assign)) {
            foreach ($groups_assign as $value) {
                array_push($assigned_groups, $value['group_sid']);
                $system_document = $this->get_document_group($value['group_sid']);

                //start
                // General Documents 
                foreach ($system_document as $gk => $gv) {
                    //
                    if (!in_array($gk, [
                        'direct_deposit',
                        'drivers_license',
                        'occupational_license',
                        'emergency_contacts',
                        'dependents'
                    ])) continue;
                    //
                    if ($gv == 1) {
                        if ($this->checkAndAssignGeneralDocument(
                            $user_sid,
                            $user_type,
                            $company_sid,
                            $gk,
                            $employer_sid
                        )) {
                            //
                            $sendGroupEmail = 1;
                        }
                    }
                }
                //end

                if ($system_document['w4'] == 1) {
                    $is_w4_assign = $this->check_w4_form_exist($user_type, $user_sid);
                    if (empty($is_w4_assign)) {
                        $w4_data_to_insert = array();
                        $w4_data_to_insert['employer_sid'] = $user_sid;
                        $w4_data_to_insert['company_sid'] = $company_sid;
                        $w4_data_to_insert['user_type'] = $user_type;
                        $w4_data_to_insert['sent_status'] = 1;
                        $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                        $w4_data_to_insert['status'] = 1;
                        $this->insert_w4_form_record($w4_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }

                if ($system_document['w9'] == 1) {
                    $is_w9_assign = $this->check_w9_form_exist($user_type, $user_sid);

                    if (empty($is_w9_assign)) {
                        $w9_data_to_insert = array();
                        $w9_data_to_insert['user_sid'] = $user_sid;
                        $w9_data_to_insert['company_sid'] = $company_sid;
                        $w9_data_to_insert['user_type'] = $user_type;
                        $w9_data_to_insert['sent_status'] = 1;
                        $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                        $w9_data_to_insert['status'] = 1;
                        $this->insert_w9_form_record($w9_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }

                if ($system_document['i9'] == 1) {
                    $is_i9_assign = $this->check_i9_exist($user_type, $user_sid);

                    if (empty($is_i9_assign)) {
                        $i9_data_to_insert = array();
                        $i9_data_to_insert['user_sid'] = $user_sid;
                        $i9_data_to_insert['user_type'] = $user_type;
                        $i9_data_to_insert['company_sid'] = $company_sid;
                        $i9_data_to_insert['sent_status'] = 1;
                        $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                        $i9_data_to_insert['status'] = 1;
                        $this->insert_i9_form_record($i9_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }

                if (!empty($system_document['eeoc']) && $system_document['eeoc'] == 1) {
                    $is_eeoc_assign = $this->check_eeoc_exist($user_sid, $user_type);

                    if (empty($is_eeoc_assign)) {
                        $eeoc_data_to_insert = array();
                        $eeoc_data_to_insert['application_sid'] = $user_sid;
                        $eeoc_data_to_insert['users_type'] = $user_type;
                        $eeoc_data_to_insert['status'] = 1;
                        $eeoc_data_to_insert['is_expired'] = 0;
                        $eeoc_data_to_insert['portal_applicant_jobs_list_sid'] = $jobs_listing;
                        $eeoc_data_to_insert['last_sent_at'] = date('Y-m-d H:i:s', strtotime('now'));
                        $eeoc_data_to_insert['assigned_at'] = date('Y-m-d H:i:s', strtotime('now'));
                        $eeoc_data_to_insert['last_assigned_by'] = 0;
                        //
                        $this->insert_eeoc_form_record($eeoc_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }
            }
        }

        $doc_group_data['groups'] = $groups;
        $doc_group_data['group_status'] = $group_status;
        $doc_group_data['active_groups'] = $active_groups;
        $doc_group_data['in_active_groups'] = $in_active_groups;
        $doc_group_data['sendGroupEmail'] = $sendGroupEmail;
        $doc_group_data['group_ids'] = $group_ids;
        $doc_group_data['assigned_groups'] = $assigned_groups;
        return  $doc_group_data;
    }

    /**
     * Get the assigned document
     * 
     * @author Nisar [05-11-2022]
     * @param number $document_sid
     * @param number $userId
     * @param string $userType [applicant|employee]
     * @return array
     */
    function is_library_document_exist($document_sid, $userId, $userType = 'employee')
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->where('user_sid', $userId);
        $this->db->where('user_type', $userType);
        //
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        return !$record_arr ? [] : $record_arr;
    }

    /**
     * Get the original document by id
     * 
     * @author Nisar [05-11-2022]
     * @param number $sid
     * @return array
     */
    function get_documents_assigned($sid)
    {
        //
        $record_obj = $this->db
            ->where('sid', $sid)
            ->get('documents_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        return !$record_arr ? [] : $record_arr;
    }

    /**
     * Assigns a document
     * 
     * @author Nisar [05-11-2022]
     * @param array $data_to_insert
     * @return number
     */
    function insert_documents_assigned($data_to_insert)
    {
        $this->db->insert('documents_assigned', $data_to_insert);
        return $this->db->insert_id();
    }

    // Change Docuemnt  visibility on document center
    function change_document_visible($sid, $data_to_update)
    {
        $this->db->where('sid', $sid);
        $this->db->update('documents_assigned', $data_to_update);
    }


    function get_preview_document($type, $document_sid)
    {
        if ($type == "company") {
            $this->db->select('sid, document_title, document_type, document_description, uploaded_document_s3_name as document_s3_name');
        } else {
            $this->db->select('sid, document_title, document_type, document_description, document_s3_name, uploaded_file, form_input_data, submitted_description, authorized_signature, authorized_signature_date, signature_base64, signature_initial, signature_timestamp');
        }

        $this->db->where('sid', $document_sid);

        $record_obj = $this->db->get($type == "company" ? "documents_management" : "documents_assigned");
        $records_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        }
        //
        return [];
    }

    public function get_approval_document_information($document_sid, $user_type, $user_sid)
    {
        //
        $this->db->select('sid, company_sid, approval_flow_sid, document_title, document_type, document_approval_employees');
        $this->db->where('document_sid', $document_sid);
        $this->db->where('user_sid', $user_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('approval_process', 1);
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function get_document_approvers($flow_sid)
    {
        //
        $this->db->select('sid, assigner_sid, assigner_turn, assign_on, note as approval_note, approval_status, action_date, approver_email');
        $this->db->where('portal_document_assign_sid', $flow_sid);
        $this->db->order_by('sid', 'desc');
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function check_document_approver_turn($document_sid)
    {
        //
        $this->db->select('sid');
        $this->db->where('portal_document_assign_sid', $document_sid);
        $this->db->where('assigner_turn', 1);
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = 0;

        if (!empty($records_arr)) {
            $return_data = 1;
        }

        return $return_data;
    }

    public function get_approval_document_bySID($sid)
    {
        //
        $this->db->select('sid, assigner_note, assigned_date, assigned_by');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('portal_document_assign_flow');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function delete_document_approver_from_list($sid)
    {
        //
        $this->db->where('sid', $sid);
        $this->db->delete('portal_document_assign_flow_employees');
    }

    public function change_document_approval_status($document_sid, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }

    public function get_assigned_document_info_by_sid($sid)
    {
        $this->db->select('user_type, user_sid, document_title');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('documents_assigned');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function revoke_document_previous_flow($document_sid)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->set('assign_status', 0);
        $this->db->update('portal_document_assign_flow');
    }

    public function requiredDocumentLibrary($document_sid)
    {
        $this->db->where('sid', $document_sid);
        $this->db->set('status', 0);
        $this->db->update('documents_assigned');
    }

    public function get_approver_detail($approver_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $approver_sid);
        $this->db->where('active', 1);


        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr;
        } else {
            return array();
        }
    }

    /**
     * Check the email last sent date time
     * 
     * @param number $userId
     * @param string $userType
     * @param string $placeCode
     * 
     * @return boolean
     */
    public function doSendEmail(
        $userId,
        $userType = 'employee',
        $placeCode
    ) {
        //
        $this->logEmailPlace('HR_DOCUMENTS_NOTIFICATION_EMS', $userId, $placeCode, $userType);
        //
        $q = $this->db
            ->select('document_sent_on')
            ->where('sid', $userId)
            ->get($userType === 'employee' ? 'users' : 'portal_job_applications');
        //
        $r = $q->row_array();
        $q->free_result();
        //
        if (!empty($r['document_sent_on'])) {
            //
            if (date('Y-m-d', strtotime('now')) == DateTime::createFromFormat('Y-m-d H:i:s', $r['document_sent_on'])->format('Y-m-d')) {
                //
                $date1 = DateTime::createFromFormat('Y-m-d H:i:s', $r['document_sent_on']);
                $date2 = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s', strtotime('now')));
                //
                if ($date2->diff($date1)->format('%h') < 2) {
                    //
                    return false;
                }
            }
        }
        //
        $this->db
            ->where(
                'sid',
                $userId
            )->update($userType === 'employee' ? 'users' : 'portal_job_applications', [
                'document_sent_on' => date('Y-m-d H:i:s', strtotime('now'))
            ]);
        //
        return true;
    }

    /**
     * Saves the sent email
     * 
     * @param string $templateName
     * @param number $userId
     * @param string $placeCode
     * @param string $userType
     */
    public function logEmailPlace(
        $templateName,
        $userId,
        $placeCode,
        $userType = 'employee'
    ) {
        //
        $this->db->insert(
            'email_tracker',
            [
                'template_name' => $templateName,
                'user_sid' => $userId,
                'user_type' => $userType,
                'place_code' => $placeCode,
                'created_at' => date('Y-m-d H:i:s', strtotime('now'))
            ]
        );
        //
        return true;
    }

    /**
     * Check if employee is active
     * 
     * @version 1.0
     * @date    30/05/2022
     * 
     * @param number $userId
     * @param string $userType 
     *               This is optional for the time being
     * 
     * @return number
     */
    public function isActiveUser(
        $userId,
        $userType = 'employee'
    ) {
        //
        if (empty($userId) || $userId == 0 || $userType != 'employee') {
            return 1;
        }
        //
        return $this->db
            ->where([
                'sid' => $userId,
                'terminated_status' => 0,
                'active' => 1
            ])
            ->count_all_results('users');
    }


    /**
     * Update the general document required flag
     * 
     * @param number $documentId
     * @param string $documentType
     * @param number $userId
     * @param string $userType
     * @param string $isRequired
     */
    public function makeGeneralDocumentRequired(
        $documentId,
        $documentType,
        $userId,
        $userType,
        $isRequired
    ) {
        //
        $whereArray = [
            'user_sid' => $userId,
            'user_type' => $userType,
            'document_type' => $documentType,
            'sid' => $documentId
        ];
        //
        if (
            $this->db->where($whereArray)->count_all_results('documents_assigned_general')
        ) {
            $this->db->where($whereArray)->update('documents_assigned_general', [
                'is_required' => ($isRequired == 'on' ? 1 : 0)
            ]);
        }
    }

    /**
     * Get a column from portal
     * 
     * @param number $companyId
     * @param string $companyId
     * 
     * @return array|number
     */
    public function getDefaultRequiredFlag(
        $companyId,
        $column
    ) {
        //
        $q =
            $this->db
            ->select($column)
            ->where('user_sid', $companyId)
            ->get('portal_employer');
        //
        $d = $q->row_array();
        //
        $q = $q->free_result();
        //
        return $d ? $d[$column] : 0;
    }

    /**
     * 
     */
    public function getVerificationDocumentsForLibrary(
        $companyId,
        $userId,
        $userType = 'employee'
    ) {
        $r = [];
        // Check for I9 permission
        if ($this->getDefaultRequiredFlag($companyId, 'dl_i9')) {
            // 
            $r['I9'] = [
                'id' => 0,
                'status' => 0,
                'assigned_on' => '',
                'completed_on' => ''
            ];
            // Check if I9 is assigned
            $form = $this->getData('applicant_i9form', [
                'company_sid' => $companyId,
                'user_sid' => $userId,
                'user_type' => $userType
            ], [
                'sid',
                'status',
                'user_consent',
                'sent_date',
                'employer_filled_date'
            ], true);
            //
            if ($form) {
                $r['I9']['id'] = $form['sid'];
                $r['I9']['status'] = $form['user_consent'] == 1 ? 2 : ($form['status'] == 1 ?  1 : 0);
                $r['I9']['assigned_on'] = $form['sent_date'];
                $r['I9']['completed_on'] = $form['employer_filled_date'];
            }
        }
        // Check for W9 permission
        if ($this->getDefaultRequiredFlag($companyId, 'dl_w9')) {
            // 
            $r['W9'] = [
                'id' => 0,
                'status' => 0,
                'assigned_on' => '',
                'completed_on' => ''
            ];
            // Check if I9 is assigned
            $form = $this->getData('applicant_w9form', [
                'company_sid' => $companyId,
                'user_sid' => $userId,
                'user_type' => $userType
            ], [
                'sid',
                'status',
                'user_consent',
                'sent_date',
                'signature_timestamp'
            ], true);
            //
            if ($form) {
                $r['W9']['id'] = $form['sid'];
                $r['W9']['status'] = $form['user_consent'] == 1 ? 2 : ($form['status'] == 1 ?  1 : 0);
                $r['W9']['assigned_on'] = $form['sent_date'];
                $r['W9']['completed_on'] = $form['signature_timestamp'];
            }
        }
        // Check for W4 permission
        if ($this->getDefaultRequiredFlag($companyId, 'dl_w4')) {
            // 
            $r['W4'] = [
                'id' => 0,
                'status' => 0,
                'assigned_on' => '',
                'completed_on' => ''
            ];
            // Check if I9 is assigned
            $form = $this->getData('form_w4_original', [
                'company_sid' => $companyId,
                'employer_sid' => $userId,
                'user_type' => $userType
            ], [
                'sid',
                'status',
                'user_consent',
                'sent_date',
                'signature_timestamp'
            ], true);
            //
            if ($form) {
                $r['W4']['id'] = $form['sid'];
                $r['W4']['status'] = $form['user_consent'] == 1 ? 2 : ($form['status'] == 1 ?  1 : 0);
                $r['W4']['assigned_on'] = $form['sent_date'];
                $r['W4']['completed_on'] = $form['signature_timestamp'];
            }
        }
        //
        return $r;
    }


    /**
     * Get a tables data
     * 
     * @param string  $table
     * @param array   $where
     * @param array   $columns
     * @param boolean $single
     * 
     * @return array
     */
    public function getData(
        $table,
        $where,
        $columns,
        $single = false
    ) {
        //
        $q = $this->db->select($columns)
            ->where($where)
            ->get($table);
        //
        $d = $single ? $q->row_array() : $q->result_array();
        //
        $q = $q->free_result();
        //
        return $d;
    }

    /**
     * Handled I9 assignment process
     * 
     * @param number $companyId
     * @param number $userId
     * @param string $userType
     * 
     * @return number
     */
    public function handleI9Assign(
        $companyId,
        $userId,
        $userType
    ) {
        //
        $whereArray = [
            'company_sid' => $companyId,
            'user_sid' => $userId,
            'user_type' => $userType
        ];
        //
        $form = $this->getData('applicant_i9form', $whereArray, [
            'sid',
            'status',
            'user_consent'
        ], true);
        //
        $dateTime = date('Y-m-d H:i:s', strtotime('now'));
        // Needs to add a fresh record
        if (!$form) {
            $ins = array();
            $ins['user_sid'] = $userId;
            $ins['user_type'] = $userType;
            $ins['company_sid'] = $companyId;
            $ins['status'] = $ins['sent_status'] = 1;
            $ins['sent_date'] = $dateTime;
            $ins['emp_app_sid'] = $userId;
            //
            $this->db->insert('applicant_i9form', $ins);
            //
            return $this->db->insert_id();
        }
        // Means the user has completed and the document is assigned
        if ($form['status'] == 1 && $form['user_consent']) {
            // Fetch the old doc and move to history
            $fullForm = $this->getData('applicant_i9form', $whereArray, [
                '*'
            ], true);
            $fullForm['i9form_ref_sid'] = $fullForm['sid'];
            //
            unset($fullForm['sid']);
            //
            $this->i9_forms_history($fullForm);
        }
        // Flush the data
        $upd = array();
        $upd["status"] = 1;
        $upd["sent_status"] = 1;
        $upd["sent_date"] = $dateTime;
        $upd["section1_emp_signature"] = NULL;
        $upd["section1_emp_signature_init"] = NULL;
        $upd["section1_emp_signature_ip_address"] = NULL;
        $upd["section1_emp_signature_user_agent"] = NULL;
        $upd["section1_preparer_signature"] = NULL;
        $upd["section1_preparer_signature_init"] = NULL;
        $upd["section1_preparer_signature_ip_address"] = NULL;
        $upd["section1_preparer_signature_user_agent"] = NULL;
        $upd["section2_sig_emp_auth_rep"] = NULL;
        $upd["section3_emp_sign"] = NULL;
        $upd["employer_flag"] = NULL;
        $upd["user_consent"] = NULL;
        //
        $this->db->where($whereArray)->update('applicant_i9form', $upd);
        //
        return $form['sid'];
    }

    /**
     * Handled W9 assignment process
     * 
     * @param number $companyId
     * @param number $userId
     * @param string $userType
     * 
     * @return number
     */
    public function handleW9Assign(
        $companyId,
        $userId,
        $userType
    ) {
        //
        $whereArray = [
            'company_sid' => $companyId,
            'user_sid' => $userId,
            'user_type' => $userType
        ];
        //
        $form = $this->getData('applicant_w9form', $whereArray, [
            'sid',
            'status',
            'user_consent'
        ], true);
        //
        $dateTime = date('Y-m-d H:i:s', strtotime('now'));
        // Needs to add a fresh record
        if (!$form) {
            $ins = array();
            $ins['user_sid'] = $userId;
            $ins['user_type'] = $userType;
            $ins['company_sid'] = $companyId;
            $ins['status'] = $ins['sent_status'] = 1;
            $ins['sent_date'] = $dateTime;
            //
            $this->db->insert('applicant_w9form', $ins);
            //
            return $this->db->insert_id();
        }
        // Means the user has completed and the document is assigned
        if ($form['status'] == 1 && $form['user_consent']) {
            // Fetch the old doc and move to history
            $fullForm = $this->getData('applicant_w9form', $whereArray, [
                '*'
            ], true);
            $fullForm['w9form_ref_sid'] = $fullForm['sid'];
            //
            unset($fullForm['sid']);
            //
            $this->w9_forms_history($fullForm);
        }
        // Flush the data
        $upd = array();
        $upd["status"] =  1;
        $upd["sent_status"] =  1;
        $upd["sent_date"] = $dateTime;
        $upd["user_consent"] = NULL;
        $upd['user_agent'] = NULL;
        $upd['active_signature'] = NULL;
        $upd['signature'] = NULL;
        $upd['user_consent'] = NULL;
        $upd['signature_timestamp'] = NULL;
        $upd['signature_email_address'] = NULL;
        $upd['signature_bas64_image'] = NULL;
        $upd['init_signature_bas64_image'] = NULL;
        $upd['signature_ip_address'] = NULL;
        $upd['signature_user_agent'] = NULL;
        //
        $this->db->where($whereArray)->update('applicant_w9form', $upd);
        //
        return $form['sid'];
    }

    /**
     * Handled W4 assignment process
     * 
     * @param number $companyId
     * @param number $userId
     * @param string $userType
     * 
     * @return number
     */
    public function handleW4Assign(
        $companyId,
        $userId,
        $userType
    ) {
        //
        $whereArray = [
            'company_sid' => $companyId,
            'employer_sid' => $userId,
            'user_type' => $userType
        ];
        //
        $form = $this->getData('form_w4_original', $whereArray, [
            'sid',
            'status',
            'user_consent'
        ], true);
        //
        $dateTime = date('Y-m-d H:i:s', strtotime('now'));
        // Needs to add a fresh record
        if (!$form) {
            $ins = array();
            $ins['employer_sid'] = $userId;
            $ins['user_type'] = $userType;
            $ins['company_sid'] = $companyId;
            $ins['status'] = $ins['sent_status'] = 1;
            $ins['sent_date'] = $dateTime;
            //
            $this->db->insert('form_w4_original', $ins);
            //
            return $this->db->insert_id();
        }
        // Means the user has completed and the document is assigned
        if ($form['status'] == 1 && $form['user_consent']) {
            // Fetch the old doc and move to history
            $fullForm = $this->getData('form_w4_original', $whereArray, [
                '*'
            ], true);
            $fullForm['form_w4_ref_sid'] = $fullForm['sid'];
            //
            unset($fullForm['sid']);
            //
            $this->w4_forms_history($fullForm);
        }
        // Flush the data
        $upd = array();
        $upd["status"] = 1;
        $upd["sent_status"] = 1;
        $upd["sent_date"] = $dateTime;
        $upd["user_consent"] = NULL;
        $upd['signature_timestamp']        = NULL;
        $upd['signature_email_address']    = NULL;
        $upd['signature_bas64_image']      = NULL;
        $upd['init_signature_bas64_image'] = NULL;
        $upd['ip_address']                 = NULL;
        $upd['user_agent']                 = NULL;
        //
        $this->db->where($whereArray)->update('form_w4_original', $upd);
        //
        return $form['sid'];
    }

    /**
     * Get the user details of assigned
     * document
     * 
     * @author  Mubashir Ahmed
     * @version 1.0   
     * @date    06/02/2022
     * 
     * @param number $sid
     * @return array
     */
    public function getAssignedDocumentUserDetailsById($sid)
    {
        //
        $q = $this->db
            ->select('user_sid, user_type')
            ->where('sid', $sid)
            ->get('documents_assigned');
        //
        $d = $q->row_array();
        //
        if (!$d) {
            return [];
        }
        //
        $q = $this->db
            ->select('first_name, last_name')
            ->where('sid', $d['user_sid'])
            ->get($d['user_type'] == 'employee' ? 'users' : 'portal_job_applications')
            ->row_array();
        //
        return $q ? $q : [];
    }

    function getPendingApproversDocument($company_sid)
    {
        $this->db->select('sid, assigned_date, assigned_by, user_type, user_sid, document_sid, assign_status, assigner_note, flow_json');
        $this->db->where('assign_status <>', 2);
        $this->db->where('company_sid', $company_sid);
        $this->db->from('portal_document_assign_flow');
        $result = $this->db->get()->result_array();

        return $result;
    }
    public function get_approval_document_detail($document_sid, $status = true)
    {
        //
        $this->db->select('company_sid, user_sid, user_type, approval_flow_sid, document_title, document_type, document_description, document_s3_name, document_sid, acknowledgment_required, download_required, signature_required, is_required, assigned_by, document_approval_employees, has_approval_flow');
        $this->db->where('sid', $document_sid);
        //
        if ($status) {
            $this->db->where('approval_process', 1);
        }
        //
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function get_document_current_approver_sid($currentFlowId)
    {
        //
        $this->db->select('assigner_sid, approver_email');
        $this->db->where('portal_document_assign_sid', $currentFlowId);
        $this->db->where('assigner_turn', 1);
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = 0;

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function get_company_domain_by_sid($compnay_id)
    {
        //
        $this->db->select('sub_domain');
        $this->db->where('user_sid', $compnay_id);
        $records_obj = $this->db->get('portal_employer');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = "";

        if (!empty($records_arr)) {
            $return_data = $records_arr['sub_domain'];
        }

        return $return_data;
    }


    public function get_approver_note($document_flow_sid, $approver_reference)
    {
        //
        $this->db->select('note');
        $this->db->where('portal_document_assign_sid', $document_flow_sid);
        //
        if (is_numeric($approver_reference) && $approver_reference > 0) {
            $this->db->where('assigner_sid', $approver_reference);
        } else {
            $this->db->where('approver_email', $approver_reference);
        }
        //
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = 0;

        if (!empty($records_arr)) {
            $return_data = $records_arr['note'];
        }

        return $return_data;
    }

    public function get_document_approver_info($flow_sid, $approver_sid)
    {
        //
        $this->db->select('assign_on, approval_status, note, action_date');
        $this->db->where('portal_document_assign_sid', $flow_sid);
        $this->db->where('assigner_sid', $approver_sid);
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        $return_data = array();

        if (!empty($records_arr)) {
            $return_data = $records_arr;
        }

        return $return_data;
    }

    public function getDefaultApprovers($company_sid, $flow_sid, $status)
    {
        //
        //
        $return_data = "";
        //
        if ($status == 1) {
            $this->db->select('default_approvers');
            $this->db->where('company_sid', $company_sid);
            $record_obj = $this->db->get('notifications_emails_configuration');
            $record_arr = $record_obj->row_array();
            $record_obj->free_result();

            //
            if (!empty($record_arr) && $record_arr["default_approvers"] == 1) {
                $this->db->select('employer_sid, email');
                $this->db->where('company_sid', $company_sid);
                $this->db->where('notifications_type', "default_approvers");
                $this->db->where('status', "active");
                $records_obj = $this->db->get('notifications_emails_management');
                $default_approvers = $records_obj->result_array();
                $records_obj->free_result();
                //
                if (!empty($default_approvers)) {
                    foreach ($default_approvers as $approver) {
                        $this->db->select('sid');
                        $this->db->where('status', 1);
                        $this->db->where('portal_document_assign_sid', $flow_sid);
                        //
                        if ($approver["employer_sid"] == 0) {
                            $this->db->where('approver_email', $approver["email"]);
                        } else {
                            $this->db->where('assigner_sid', $approver["employer_sid"]);
                        }
                        //
                        $record_obj = $this->db->get('portal_document_assign_flow_employees');
                        $approver_data = $record_obj->row_array();
                        $record_obj->free_result();
                        //
                        if (empty($approver_data)) {
                            if ($approver["employer_sid"] == 0) {
                                $return_data = $approver["email"];
                            } else {
                                $return_data =  $approver["employer_sid"];
                            }
                            //
                            break;
                        }
                    }
                }
            }
        }

        return $return_data;
    }


    public function get_default_outer_approver($company_sid, $email)
    {
        //
        $this->db->select('contact_name, email');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('email', $email);
        $this->db->where('notifications_type', "default_approvers");
        $this->db->where('status', "active");
        $record_obj = $this->db->get('notifications_emails_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        $return_data = array();
        //
        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function is_document_has_approval_flow($document_sid)
    {
        //
        $this->db->select('has_approval_flow, document_approval_note');
        $this->db->where('sid', $document_sid);
        $record_obj = $this->db->get('documents_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        $return_data = array();
        //
        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function get_document_external_approver_info($flow_sid, $company_sid)
    {
        //
        $this->db->select('assign_on, action_date, note, approval_status, approver_email');
        $this->db->where('portal_document_assign_sid', $flow_sid);
        $this->db->where('assigner_sid', 0);
        $records_obj = $this->db->get('portal_document_assign_flow_employees');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //
        $return_approvers = array();
        //
        if (!empty($records_arr)) {
            foreach ($records_arr as $key => $approver) {
                $records_arr[$key]["approver_name"] = getDefaultApproverName($company_sid, $approver["approver_email"]);
            }
            //
            $return_approvers = $records_arr;
        }

        return $return_approvers;
    }

    public function is_default_approver($employee_sid)
    {
        //
        $this->db->select('sid');
        $this->db->where('employer_sid', $employee_sid);
        $this->db->where('notifications_type', "default_approvers");
        $this->db->where('status', "active");
        $record_obj = $this->db->get('notifications_emails_management');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        if (!empty($record_arr)) {
            return false;
        } else {
            return true;
        }
    }

    public function get_document_detail_for_end_process($document_sid)
    {
        //
        $this->db->select('company_sid, user_sid, user_type, assigned_by, document_title, document_description, sendEmail, managersList');
        $this->db->where('sid', $document_sid);
        //
        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        $return_data = array();

        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }

    public function is_document_has_approval_flow_offer_letter($document_sid)
    {
        //
        $this->db->select('has_approval_flow, document_approval_note');
        $this->db->where('sid', $document_sid);
        $record_obj = $this->db->get('offer_letter');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();
        //
        $return_data = array();
        //
        if (!empty($record_arr)) {
            $return_data = $record_arr;
        }

        return $return_data;
    }



    //
    function getSecureDocuemntById($document_sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $document_sid);
        $records_obj = $this->db->get('company_secure_documents');
        $records_arr = $records_obj->row_array();
        $records_obj->free_result();
        return $records_arr;
    }


    //
    function getDocumentByIdResourceDocuments($documentId)
    {
        $a = $this->db
            ->where('sid', $documentId)
            ->get('document_library_files');
        //
        $b = $a->row_array();
        $a->free_result();
        //
        return $b;
    }

    public function checkDocumentCompletionStatus($assigned_document)
    {
        //
        $is_magic_tag_exist = 0;
        $is_document_completed = 0;
        //
        if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
            //
            $document_body = $assigned_document['document_description'];
            $magic_codes = array('{{signature}}', '{{inital}}');
            //
            if (str_replace($magic_codes, '', $document_body) != $document_body) {
                $is_magic_tag_exist = 1;
            }
        }
        //
        if (($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist)) {
            //
            if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                }
            } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                if ($is_magic_tag_exist == 1) {
                    if ($assigned_document['uploaded'] == 1) {
                        $is_document_completed = 1;
                    } else {
                        $is_document_completed = 0;
                    }
                } else if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            } else if ($assigned_document['acknowledgment_required'] == 1) {
                if ($assigned_document['acknowledged'] == 1) {
                    $is_document_completed = 1;
                } else if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            } else if ($assigned_document['download_required'] == 1) {
                if ($assigned_document['downloaded'] == 1) {
                    $is_document_completed = 1;
                } else if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            } else if ($assigned_document['signature_required'] == 1) {
                if ($assigned_document['uploaded'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            } else if ($is_magic_tag_exist == 1) {
                if ($assigned_document['user_consent'] == 1) {
                    $is_document_completed = 1;
                } else {
                    $is_document_completed = 0;
                }
            }
        } else {
            $is_document_completed = 1;
        }
        //
        return $is_document_completed;
    }

    function checkDocumentIsManual($document_sid)
    {

        $this->db->select('document_type, document_sid');
        $this->db->where('sid', $document_sid);

        $this->db->where('document_sid', 0);
        $this->db->where('document_type', 'uploaded');

        $record_obj = $this->db->get('documents_assigned');
        $record_arr = $record_obj->row_array();
        $record_obj->free_result();

        if ($record_arr['document_sid'] == 0 && $record_arr['document_type'] == 'uploaded') {
            return true;
        } else {
            return false;
        }
    }

    public function deleteManualDocument($document_sid)
    {
        $this->db->where('sid', $document_sid);
        $this->db->delete('documents_assigned');
    }

    function updateManualDocumentData($documentId, $data)
    {
        $this->db->where('sid', $documentId);
        $this->db->update('documents_assigned', $data);
    }

    /**
     * get the company state forms
     *
     * @param int $companyId
     * @param int $userId
     * @param string $userType applicant|employee
     * @return array
     */
    public function getCompanyStateForms(
        int $companyId,
        int $userId,
        string $userType
    ): array {
        // not function for applicants yet
        if ($userType === 'applicant') {
            return [];
        }
        // get the company state
        $companyState = $this->getCompanyState($companyId);
        // when no state is set
        if ($companyState === 0) {
            return [];
        }
        // get the forms
        $forms = $this->db
            ->select("sid, title")
            ->where("state_sid", $companyState)
            ->get("state_forms")
            ->result_array();
        //
        if (!$forms) {
            return [];
        }
        //
        $returnArray = [
            "all" => [],
            "completed" => [],
            "not_completed" => []
        ];
        // loop through the forms
        foreach ($forms as $index => $value) {
            // get teh form status
            $value = array_merge($value, $this->checkStateFormAssignStatus($value["sid"], $userId, $userType));
            // push to all
            $returnArray["all"][] = $value;
            //
            if ($value["is_completed"]) {
                $returnArray["completed"][] = $value;
            } else if (!$value["is_completed"] && $value["status"] === "assigned") {
                $returnArray["not_completed"][] = $value;
            }
        }
        //
        return $returnArray;
    }

    /**
     * get the company state
     *
     * @param int $companyId
     * @return int
     */
    public function getCompanyState(int $companyId): int
    {
        $result = $this->db
            ->select("Location_State")
            ->where("users.sid", $companyId)
            ->get("users")
            ->row_array();
        //
        return $result && $result["Location_State"] ? $result["Location_State"] : 0;
    }

    /**
     * check the form status
     *
     * @param int $formId
     * @param int $userId
     * @param string $userType
     * @return string
     */
    public function checkStateFormAssignStatus(int $formId, int $userId, string $userType): array
    {
        // ste default array
        $returnArray = [
            "status" => "not_assigned",
            "is_completed" => false,
            "is_employer_completed" => false,
            "assigned_at" => "",
            "assigned_by" => "",
            "signed_at" => "",
            "form_data" => [],
            "employer_json" => [],
            "employee_section" => false
        ];
        // get the record
        $result = $this->db
            ->select("
                sid,
                status, 
                company_sid, 
                user_consent,
                created_at,
                assigned_by,
                user_consent_at,
                fields_json,
                employer_json,
                employer_consent,
                employer_concent_at
            ")
            ->where("state_form_sid", $formId)
            ->where("user_sid", $userId)
            ->where("user_type", $userType)
            ->get("portal_state_form")
            ->row_array();
        // not assigned
        if (!$result) {
            return $returnArray;
        }
        // get the
        $companyData = getDataForEmployerPrefill($result["company_sid"]);
        // when not being consent
        if (!$this->db->where([
            "user_sid" => $userId,
            "user_type" => $userType,
            "state_form_sid" => $formId,
            "employer_consent" => 1
        ])->count_all_results("portal_state_form")) {
            // when no data is set
            if (!$result["employer_json"]) {
                // set update array
                $updateArray = [];
                $updateArray["first_name"] = $companyData["CompanyName"];
                $updateArray["mn_tax_number"] = $companyData["mtin"];
                $updateArray["ssn"] = $companyData["ssn"];
                $updateArray["street_1"] = $companyData["Location_Address"];
                $updateArray["street_2"] = $companyData["Location_Address_2"];
                $updateArray["city"] = $companyData["Location_City"];
                $updateArray["state"] = $companyData["Location_State"];
                $updateArray["zip_code"] = $companyData["Location_ZipCode"];
                //
                $upd = [];
                $upd["employer_json"] = json_encode($updateArray);

                if (
                    $updateArray["mn_tax_number"] &&
                    $updateArray["first_name"] &&
                    $updateArray["ssn"] &&
                    $updateArray["street_1"] &&
                    $updateArray["city"] &&
                    $updateArray["state"] &&
                    $updateArray["zip_code"]
                ) {
                    $upd["employer_consent"] = 1;
                    $upd["employer_concent_at"] = getSystemDate();
                    $result["employer_consent"] = 1;
                    $result["employer_concent_at"] = getSystemDate();
                }
                //
                $this->db
                    ->where("sid", $result["sid"])
                    ->update("portal_state_form", $upd);
                //
                $result["employer_json"] = json_encode($updateArray);
            }
        }
        //
        if ($result['employer_consent'] == 1) {
            $returnArray['employee_section'] = true;
        }
        //
        if ($result["assigned_by"]) {
            $returnArray["assigned_by"] = getUserNameBySID($result["assigned_by"]);
        }
        // set it to revoked
        $returnArray["status"] = "revoked";
        // form is completed
        if ($result["user_consent"] == 1 && $result["status"] == 1) {
            $returnArray["status"] = "assigned";
            $returnArray["is_completed"] = true;
            $returnArray["assigned_at"] = $result["created_at"];
            $returnArray["signed_at"] = $result["user_consent_at"];
            $returnArray["form_data"] = json_decode($result["fields_json"], true);
        }
        // set to assigned
        else if ($result["status"] == 1) {
            $returnArray["status"] = "assigned";
            $returnArray["assigned_at"] = $result["created_at"];
        }

        if ($result["employer_consent"] == 1) {
            $returnArray["employer_json"] = json_decode($result["employer_json"], true);
            $returnArray["is_employer_completed"] = true;
        }
        //
        if ($result["fields_json"]) {
            $returnArray["form_data"] = json_decode($result["fields_json"], true);
        }
        //
        if ($result["employer_json"]) {
            $returnArray["employer_json"] = json_decode($result["employer_json"], true);
        }
        //
        return $returnArray;
    }

    /**
     * handle form process
     *
     * @param int $userId
     * @param string $userType
     * @param int $formId
     * @param string $eventType
     * @param bool $returnJson Optional
     */
    public function handleStateForm(int $userId, string $userType, int $formId, string $eventType, bool $returnJson = true)
    {
        $msg = "You have successfully assigned the form.";
        //
        $session = checkAndGetSession("all", true);
        // assign the form
        if ($eventType === "assign") {
            $insertArray = [];
            $insertArray["company_sid"] = $session["company_detail"]["sid"];
            $insertArray["state_form_sid"] = $formId;
            $insertArray["user_sid"] = $userId;
            $insertArray["user_type"] = $userType;
            $insertArray["fields_json"] = json_encode([]);
            $insertArray["status"] = 1;
            $insertArray["user_consent"] = 0;
            $insertArray["user_consent_at"] = null;
            $insertArray["assigned_by"] = getLoggedInPersonId();
            $insertArray["created_at"] = $insertArray["updated_at"]
                = getSystemDate();
            //
            $this->db->insert("portal_state_form", $insertArray);
        }
        // reassign form
        elseif ($eventType === "reassign") {
            $msg = "You have successfully re-assigned the form.";
            $insertArray = [];
            $insertArray["fields_json"] = json_encode([]);
            $insertArray["status"] = 1;
            $insertArray["user_consent"] = 0;
            $insertArray["user_consent_at"] = null;
            $insertArray["assigned_by"] = getLoggedInPersonId();
            $insertArray["created_at"] = $insertArray["updated_at"]
                = getSystemDate();
            //
            $this
                ->db
                ->where([
                    "user_sid" => $userId,
                    "user_type" => $userType,
                    "state_form_sid" => $formId,
                ])
                ->update("portal_state_form", $insertArray);
        }
        // revoke form
        else {
            $msg = "You have successfully revoked the form.";
            // get the form row
            $form = $this->db
                ->where([
                    "user_sid" => $userId,
                    "user_type" => $userType,
                    "state_form_sid" => $formId,
                ])
                ->get("portal_state_form")
                ->row_array();
            //
            $insertArray = $form;
            //
            unset(
                $insertArray["user_sid"],
                $insertArray["user_type"],
                $insertArray["company_sid"],
                $insertArray["sid"],
                $insertArray["state_form_sid"],
            );
            //
            $insertArray["portal_state_form_sid"] = $form["sid"];
            // add history
            $this->db->insert(
                "portal_state_form_history",
                $insertArray
            );
            // update status
            if ($this->db->insert_id()) {
                $this->db
                    ->where("sid", $form["sid"])
                    ->update(
                        "portal_state_form",
                        [
                            "status" => 0,
                            "user_consent" => 0,
                            "user_consent_at" => null,
                            "employer_consent" => 0,
                            "employer_concent_at" => null
                        ]
                    );
            }
        }

        if ($returnJson) {

            return SendResponse(
                200,
                [
                    "msg" => $msg
                ]
            );
        } else {
            return $msg;
        }
    }

    /**
     * get the company state form
     *
     * @param int $companyId
     * @param int $formId
     * @param int $userId
     * @param string $userType applicant|employee
     * @return array
     */
    public function getStateForm(
        int $companyId,
        int $formId,
        int $userId,
        string $userType
    ): array {
        // get the form
        $form = $this->db
            ->select("sid, title, form_slug")
            ->where("state_forms.sid", $formId)
            ->get("state_forms")
            ->row_array();
        //
        if (!$form) {
            return [];
        }
        // get teh form status
        $form = array_merge(
            $form,
            $this->checkStateFormAssignStatus(
                $form["sid"],
                $userId,
                $userType
            )
        );
        //
        return $form;
    }

    /**
     * get the company state form
     *
     * @return array
     */
    public function getCompanyStateForm(int $companyId): array
    {
        $companyState = $this->getCompanyState($companyId);
        // when no state is set
        if ($companyState === 0) {
            return [];
        }
        // get the forms
        return $this->db
            ->select("sid, title")
            ->where("state_sid", $companyState)
            ->get("state_forms")
            ->result_array();
    }

    /**
     * save the employee state form
     *
     * @param int $formId
     * @param int $employeeId
     * @param array $dataToUpdate
     */
    public function updateStateForm($formId, $employeeId, $dataToUpdate)
    {
        $this->db->where('state_form_sid', $formId);
        $this->db->where('user_sid', $employeeId);
        $this->db->update('portal_state_form', $dataToUpdate);
    }

    public function getStateFormInfo(int $formId)
    {
        return $this->db
            ->select("sid, title, form_slug")
            ->where("state_forms.sid", $formId)
            ->get("state_forms")
            ->row_array();
    }

    /**
     * save the employee state form
     *
     * @param int $formId
     * @param int $employeeId
     * @param array $dataToUpdate
     */
    public function getStates()
    {
        return $this->db
            ->select("sid, state_name, state_code")
            ->where([
                "country_sid" => 227,
                "active" => 1
            ])
            ->order_by("state_name", "ASC")
            ->get("states")
            ->result_array();
    }


    public function saveStateFormEmployerSection(
        int $formId,
        int $userId,
        string $userType,
        string $employerJson
    ) {
        $this->db->where([
            "user_sid" => $userId,
            "user_type" => $userType,
            "state_form_sid" => $formId,
        ])
            ->update(
                "portal_state_form",
                [
                    "employer_consent" => 1,
                    "employer_concent_at" => getSystemDate(),
                    "employer_json" => $employerJson,
                    "updated_at" => getSystemDate(),
                ]
            );

        return SendResponse(
            200,
            [
                "msg" => "You have successfully signed the employer section."
            ]
        );
    }

    /**
     * get the state forms
     */
    public function getEmployeesPendingStateForms(
        int $companyId,
        array $employeeIds
    ) {
        //
        $this->db
            ->select([
                "state_forms.title",
                "portal_state_form.user_sid",
                "portal_state_form.created_at",
            ])
            ->join(
                "state_forms",
                "portal_state_form.state_form_sid = state_forms.sid",
                "inner"
            )
            ->where("company_sid", $companyId)
            ->where("portal_state_form.user_consent", 0);
        //
        if (!in_array("all", $employeeIds)) {
            $this->db
                ->where_in("user_sid", $employeeIds)
                ->where("user_type", "employee");
        }
        //
        return $this->db
            ->get("portal_state_form")
            ->result_array();
    }


    public function getEmployeeData(int $employeeId)
    {
        //
        return $this->db
            ->select("
                first_name, 
                middle_initial, 
                last_name, 
                ssn, 
                Location_Address, 
                Location_Address_2, 
                Location_City, 
                Location_State, 
                Location_ZipCode, 
                Location_Country, 
                PhoneNumber, 
                marital_status
            ")
            ->where('sid', $employeeId)
            ->get("users")
            ->row_array();
    }

    /**
     * Assign the group documents to user
     *
     * @param int $userId
     * @param string $userType
     * @param int $sendGroupEmail
     * @param bool $checkAndAssignGroupDocuments Optional
     * @param int $companyId Optional 
     * @param int $jobId Optional 
     * @return array
     */
    public function assignGroupDocumentsToUser(
        int $userId,
        string $userType,
        int $sendGroupEmail,
        bool $checkAndAssignGroupDocuments = false,
        int $companyId = 0,
        int $jobId = 0
    ): array {
        // extract the group ids
        $groupIds = $this->getUserAssignedGroups($userId, $userType);
        // no groups found
        if (!$groupIds) {
            return ["errors" => ["No groups assigned."]];
        }
        // get the group documents
        $documents = $this->getGroupsDocuments($groupIds, $userId, $userType);
        //
        if ($documents["state_forms"]) {
            $this->handleUserStateForms(
                $documents["state_forms"],
                $userId,
                $userType
            );
        }
        // check and assign the group documents
        if ($checkAndAssignGroupDocuments) {
            $this->checkAndAssignGroupDocuments(
                $companyId,
                $jobId,
                $userId,
                $userType
            );
        }
        //
        return $documents;
    }

    /**
     * get assigned groups of user
     *
     * @param int $userId
     * @param string $userType
     * @return array
     */
    private function getUserAssignedGroups(
        int $userId,
        string $userType
    ): array {
        // get the user groups with documents id
        $groups =
            $this->db
            ->select("group_sid")
            ->where($userType === "applicant" ? "applicant_sid" : "employer_sid", $userId)
            ->where("assign_status", 1)
            ->get("documents_group_2_employee")
            ->result_array();
        // no groups found
        if (!$groups) {
            return [];
        }
        // extract the group ids
        return array_column(
            $groups,
            "group_sid"
        );
    }

    /**
     * get assigned groups documents
     *
     * @param array $groupIds
     * @param int $userId
     * @param string $userType
     * @return array
     */
    private function getGroupsDocuments(
        array $groupIds,
        int $userId,
        string $userType
    ): array {
        // get the group documents
        $groups = $this->db
            ->select("
                w4,
                w9,
                i9,
                eeoc,
                dependents,
                direct_deposit,
                drivers_license,
                emergency_contacts,
                occupational_license,
                state_forms_json
            ")
            ->where_in("sid", $groupIds)
            ->where("status", 1)
            ->get("documents_group_management")
            ->result_array();
        // check for empty documents
        if (!$groups) {
            return [];
        }
        // set default array
        $returnArray = [];
        $returnArray["w4"] = 0;
        $returnArray["w9"] = 0;
        $returnArray["i9"] = 0;
        $returnArray["eeoc"] = 0;
        $returnArray["dependents"] = 0;
        $returnArray["direct_deposit"] = 0;
        $returnArray["drivers_license"] = 0;
        $returnArray["emergency_contacts"] = 0;
        $returnArray["occupational_license"] = 0;
        $returnArray["state_forms"] = [];
        $returnArray["documents"] = [];
        //
        foreach ($groups as $v0) {
            // for w4
            $returnArray["w4"] = $returnArray["w4"] == 0 && $v0["w4"] != 0 ? 1 : $returnArray["w4"];
            // for w9
            $returnArray["w9"] = $returnArray["w9"] == 0 && $v0["w9"] != 0 ? 1 : $returnArray["w9"];
            // for i9
            $returnArray["i9"] = $returnArray["i9"] == 0 && $v0["i9"] != 0 ? 1 : $returnArray["i9"];
            // for eeoc
            $returnArray["eeoc"] = $returnArray["eeoc"] == 0 && $v0["eeoc"] != 0 ? 1 : $returnArray["eeoc"];
            // for dependents
            $returnArray["dependents"] = $returnArray["dependents"] == 0 && $v0["dependents"] != 0 ? 1 : $returnArray["dependents"];
            // for direct_deposit
            $returnArray["direct_deposit"] = $returnArray["direct_deposit"] == 0 && $v0["direct_deposit"] != 0 ? 1 : $returnArray["direct_deposit"];
            // for drivers_license
            $returnArray["drivers_license"] = $returnArray["drivers_license"] == 0 && $v0["drivers_license"] != 0 ? 1 : $returnArray["drivers_license"];
            // for emergency_contacts
            $returnArray["emergency_contacts"] = $returnArray["emergency_contacts"] == 0 && $v0["emergency_contacts"] != 0 ? 1 : $returnArray["emergency_contacts"];
            // for occupational_license
            $returnArray["occupational_license"] = $returnArray["occupational_license"] == 0 && $v0["occupational_license"] != 0 ? 1 : $returnArray["occupational_license"];
            // state forms
            $stateForms = json_decode(
                $v0["state_forms_json"],
                true
            );
            //
            if ($stateForms) {
                $returnArray["state_forms"] =
                    array_merge(
                        $returnArray["state_forms"],
                        $stateForms

                    );
            }
        }
        // lets get the group documents
        $documents = $this->db->select("document_sid")
            ->where_in("group_sid", $groupIds)
            ->get("documents_2_group")
            ->result_array();
        // when no documents found
        if (!$documents) {
            return $returnArray;
        }
        // extract document ids
        $returnArray["documents"] = array_unique(
            array_column(
                $documents,
                "document_sid"
            )
        );
        //
        return $returnArray;
    }

    /**
     * check and assign state forms
     *
     * @param array $stateFormIds
     * @param int $userId
     * @param string $userType
     * @return array
     */
    private function handleUserStateForms(
        array $stateFormIds,
        int $userId,
        string $userType
    ) {
        //
        foreach ($stateFormIds as $v0) {
            $statusArray = $this->checkStateFormAssignStatus(
                $v0,
                $userId,
                $userType
            );
            // only it was not assigned
            if ($statusArray["status"] === "not_assigned") {
                $this->handleStateForm(
                    $userId,
                    $userType,
                    $v0,
                    "assign",
                    false
                );
            }
        }
    }

    public function checkAndSetEmployerSection(
        array &$w4Form,
        string $userType,
        int $userId,
        int $companyId
    ) {
        //
        $data = getDataForEmployerPrefill(
            $companyId,
            $userId,
            $userType
        );
        //
        $updateArray = [];
        $updateArray["emp_name"] = $w4Form["emp_name"] ?? $data["CompanyName"];
        $updateArray["emp_address"] = $w4Form["emp_address"] ?? $data["companyAddress"];
        if (!$w4Form["first_date_of_employment"] && $w4Form["first_date_of_employment"] != "0000-00-00" && $data["first_day_of_employment"]) {
            $updateArray["first_date_of_employment"] = formatDateToDB($data["first_day_of_employment"], "m-d-Y", DB_DATE);
        }
        $updateArray["emp_identification_number"] = $w4Form["emp_identification_number"] ?? $data["ssn"];

        $w4Form = array_merge(
            $w4Form,
            $updateArray
        );
        if ($updateArray["first_date_of_employment"]) {
            $w4Form["first_date_of_employment"] = formatDateToDB(
                $updateArray["first_date_of_employment"],
                DB_DATE,
                "m-d-Y"
            );
        }
        //
        $this->db
            ->where("sid", $w4Form["sid"])
            ->update("form_w4_original", $updateArray);
    }


    //
    function getEmployeeW4MNForm($cId, $id, &$data)
    {
        //
        $a = $this->db
            ->select('*')
            ->where('user_type', 'employee')
            ->where('user_sid', $id)
            ->where('company_sid', $cId)
            ->where('user_consent', 1)
            ->where('employer_consent', 1)
            ->where('status', 1)
            ->get('portal_state_form');
        //
        $b = $a->row_array();
        $a = $a->free_result();
        //
        if (count($b)) $data['W4MN'] = $b;
    }

    /**
     * check and assign group documents
     * 
     * @param int $companyId
     * @param int $jobId
     * @param int $userId
     * @param string $userType
     * @return array
     */
    public function checkAndAssignGroupDocuments(
        int $companyId,
        int $jobId,
        int $userId,
        string $userType
    ) {

        // handle verification and general document assignment
        $sendGroupEmail = $this->handleVerificationAndGeneralDocumentAssignment(
            $companyId,
            $jobId,
            $userId,
            $userType
        );
        // handle verification and general document assignment
        $sendGroupEmail = $this->handleCompanyGroupDocumentsAssignment(
            $companyId,
            $userId,
            $userType
        );
    }

    /**
     * get assigned groups
     * 
     * @param int $userId
     * @param string $userType
     * @return array
     */
    public function getAssignedGroupForUser(
        int $userId,
        string $userType
    ): array {
        return
            $this->db
            ->select('group_sid')
            ->where(
                $userType == 'employee' ? 'employer_sid' : "applicant_sid",
                $userId
            )
            ->get('documents_group_2_employee')
            ->result_array();
    }

    /**
     * handles verification and general document group assignment
     * 
     * @param int $companyId
     * @param int $jobListingId
     * @param int $userId
     * @param string $userType
     * @return int
     */
    public function handleVerificationAndGeneralDocumentAssignment(
        int $companyId,
        int $jobListingId,
        int $userId,
        string $userType
    ): int {
        //
        $sendGroupEmail = 0;
        // get groups
        $assignedGroups = $this->getAssignedGroupForUser($userId, $userType);
        // handle general and verification documents
        if ($assignedGroups) {
            foreach ($assignedGroups as $value) {
                $system_document = $this->get_document_group($value['group_sid']);
                // General Documents
                foreach ($system_document as $gk => $gv) {
                    //
                    if (!in_array($gk, [
                        'direct_deposit',
                        'drivers_license',
                        'occupational_license',
                        'emergency_contacts',
                        'dependents'
                    ])) continue;
                    //
                    if ($gv == 1) {
                        if ($this->checkAndAssignGeneralDocument(
                            $userId,
                            $userType,
                            $companyId,
                            $gk,
                            $this->session->userdata("logged_in")["employer_detail"]["sid"] ?? $userId
                        )) {
                            //
                            $sendGroupEmail = 1;
                        }
                    }
                }
                // w4
                if (isset($system_document['w4']) && $system_document['w4'] == 1) {
                    $is_w4_assign = $this->check_w4_form_exist($userType, $userId);
                    if (empty($is_w4_assign)) {
                        $w4_data_to_insert = array();
                        $w4_data_to_insert['employer_sid'] = $userId;
                        $w4_data_to_insert['company_sid'] = $companyId;
                        $w4_data_to_insert['user_type'] = $userType;
                        $w4_data_to_insert['sent_status'] = 1;
                        $w4_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                        $w4_data_to_insert['status'] = 1;
                        $this->insert_w4_form_record($w4_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }
                // w9
                if (isset($system_document['w9']) && $system_document['w9'] == 1) {
                    $is_w9_assign = $this->check_w9_form_exist($userType, $userId);

                    if (empty($is_w9_assign)) {
                        $w9_data_to_insert = array();
                        $w9_data_to_insert['user_sid'] = $userId;
                        $w9_data_to_insert['company_sid'] = $companyId;
                        $w9_data_to_insert['user_type'] = $userType;
                        $w9_data_to_insert['sent_status'] = 1;
                        $w9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                        $w9_data_to_insert['status'] = 1;
                        $this->insert_w9_form_record($w9_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }
                // i9
                if (isset($system_document['i9']) && $system_document['i9'] == 1) {
                    $is_i9_assign = $this->check_i9_exist($userType, $userId);

                    if (empty($is_i9_assign)) {
                        $i9_data_to_insert = array();
                        $i9_data_to_insert['user_sid'] = $userId;
                        $i9_data_to_insert['user_type'] = $userType;
                        $i9_data_to_insert['company_sid'] = $companyId;
                        $i9_data_to_insert['sent_status'] = 1;
                        $i9_data_to_insert['sent_date'] = date('Y-m-d H:i:s');
                        $i9_data_to_insert['status'] = 1;
                        $this->insert_i9_form_record($i9_data_to_insert);
                        //
                        $sendGroupEmail = 1;
                    }
                }
                // EEOC
                if ($this->session->userdata('logged_in')['portal_detail']['eeo_on_employee_document_center']) {
                    if (!empty($system_document['eeoc']) && $system_document['eeoc'] == 1) {
                        $is_eeoc_assign = $this->check_eeoc_exist($userId, $userType);

                        if (empty($is_eeoc_assign)) {
                            $eeoc_data_to_insert = array();
                            $eeoc_data_to_insert['application_sid'] = $userId;
                            $eeoc_data_to_insert['users_type'] = $userType;
                            $eeoc_data_to_insert['status'] = 1;
                            $eeoc_data_to_insert['is_expired'] = 0;
                            $eeoc_data_to_insert['portal_applicant_jobs_list_sid'] = $jobListingId;
                            $eeoc_data_to_insert['last_sent_at'] = getSystemDate();
                            $eeoc_data_to_insert['assigned_at'] = getSystemDate();
                            $eeoc_data_to_insert['last_assigned_by'] = 0;
                            //
                            $this->insert_eeoc_form_record($eeoc_data_to_insert);
                            //
                            $sendGroupEmail = 1;
                        }
                    }
                }
            }
        }
        //
        return $sendGroupEmail;
    }

    /**
     * handles verification and general document group assignment
     * 
     * @param int $companyId
     * @param int $userId
     * @param string $userType
     * @return int
     */
    public function handleCompanyGroupDocumentsAssignment(
        int $companyId,
        int $userId,
        string $userType
    ): int {
        //
        $sendGroupEmail = 0;
        $assign_group_documents = $this->hr_documents_management_model->get_assign_group_documents($companyId, $userType, $userId);

        if (!empty($assign_group_documents)) {
            foreach ($assign_group_documents as $key => $assign_group_document) {
                $is_document_assign = $this->hr_documents_management_model->check_document_already_assigned($companyId, $userType, $userId, $assign_group_document['document_sid']);
                if ($is_document_assign == 0 && $assign_group_document['document_sid'] > 0) {
                    $document = $this->hr_documents_management_model->get_hr_document_details($companyId, $assign_group_document['document_sid']);

                    if (!empty($document)) {
                        $data_to_insert = array();
                        $data_to_insert['company_sid'] = $companyId;
                        $data_to_insert['assigned_date'] = getSystemDate();
                        $data_to_insert['assigned_by'] = $assign_group_document['assigned_by_sid'];
                        $data_to_insert['user_type'] = $userType;
                        $data_to_insert['user_sid'] = $userId;
                        $data_to_insert['document_type'] = $document['document_type'];
                        $data_to_insert['document_sid'] = $assign_group_document['document_sid'];
                        $data_to_insert['status'] = 1;
                        $data_to_insert['document_original_name'] = $document['uploaded_document_original_name'];
                        $data_to_insert['document_extension'] = $document['uploaded_document_extension'];
                        $data_to_insert['document_s3_name'] = $document['uploaded_document_s3_name'];
                        $data_to_insert['document_title'] = $document['document_title'];
                        $data_to_insert['document_description'] = $document['document_description'];
                        $data_to_insert['acknowledgment_required'] = $document['acknowledgment_required'];
                        $data_to_insert['signature_required'] = $document['signature_required'];
                        $data_to_insert['download_required'] = $document['download_required'];
                        $data_to_insert['is_confidential'] = $document['is_confidential'];
                        $data_to_insert['is_required'] = $document['is_required'];
                        //
                        $assignment_sid = $this->hr_documents_management_model->insert_documents_assignment_record($data_to_insert);
                        //
                        if ($document['document_type'] != "uploaded" && !empty($document['document_description'])) {
                            $isAuthorized = preg_match('/{{authorized_signature}}|{{authorized_signature_date}}/i', $document['document_description']);
                            //
                            if ($isAuthorized == 1) {
                                // Managers handling
                                $this->hr_documents_management_model->addManagersToAssignedDocuments(
                                    $document['managers_list'],
                                    $assignment_sid,
                                    $companyId,
                                    $assign_group_document['assigned_by_sid']
                                );
                            }
                        }
                        //
                        if ($document['has_approval_flow'] == 1) {
                            $this->handleApprovalFlow(
                                $assignment_sid,
                                $document['document_approval_note'],
                                $document["document_approval_employees"],
                                0,
                                $document['managers_list']
                            );
                        } else {
                            //
                            $sendGroupEmail = 1;
                        }
                    }
                }
            }
        }
        //
        return $sendGroupEmail;
    }

    /**
     * Handle document approval flow
     * 
     * @version 1.0
     * @date    04/15/2022
     * 
     * @param number $document_sid
     * @param string $initiator_note
     * @param array  $approvers_list
     * @param string $send_email
     * @param array  $managers_list
     * 
     * @return
     */
    private function handleApprovalFlow(
        $document_sid,
        $initiator_note,
        $approvers_list,
        $send_email,
        $managers_list
    ) {

        $session = $this->session->userdata('logged_in');
        $company_sid = $session['company_detail']['sid'];
        $employer_sid = $session['employer_detail']['sid'];
        //
        // Set insert data array
        //
        $ins = [];
        $ins['company_sid'] = $company_sid;
        $ins['document_sid'] = $document_sid;
        $ins['assigned_by'] = $employer_sid;
        $ins['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $ins['assigner_note'] = $initiator_note;
        $ins['status'] = 1;
        $ins['is_pending'] = 0; // 0 = Pending, 1 = Accepted, 2 = Rejected
        //
        // Lets revoke all previous document flows if exist
        $this->hr_documents_management_model->revoke_document_previous_flow($document_sid);

        // Lets insert the record
        $approvalInsertId = $this->hr_documents_management_model->insert_documents_assignment_flow($ins);
        //
        // Update user assigned document
        $this->hr_documents_management_model->change_document_approval_status(
            $document_sid,
            [
                'approval_process' => 1,
                'approval_flow_sid' => $approvalInsertId,
                'sendEmail' => $send_email,
                'managersList' => $managers_list,
                'has_approval_flow' => 1,
                'document_approval_employees' => $approvers_list,
                'document_approval_note' => $initiator_note,
            ]
        );
        //
        $this->addAndSendNotificationsToApprovalEmployees(
            $approvalInsertId,
            $document_sid,
            $approvers_list,
            $initiator_note
        );
        //
        return true;
    }

    /**
     * Add and sends email notifications
     * to selected approval employees
     * 
     * @version 1.0
     * @date    04/15/2022
     * 
     * @param number $approval_flow_sid
     * @param number $document_sid
     * @param array  $approvers_list
     * @param string $initiator_note
     */
    function addAndSendNotificationsToApprovalEmployees(
        $approval_flow_sid,
        $document_sid,
        $approvers_list,
        $initiator_note
    ) {

        if (!empty($approvers_list)) {
            $approvalEmployees = explode(",", $approvers_list);
            //
            foreach ($approvalEmployees as $key => $approver_sid) {
                $is_default_approver = $this->hr_documents_management_model->is_default_approver($approver_sid);
                //
                if ($is_default_approver) {
                    $data_to_insert = array();
                    $data_to_insert['portal_document_assign_sid'] = $approval_flow_sid;
                    $data_to_insert['assigner_sid'] = $approver_sid;
                    //
                    if ($key == 0) {
                        $data_to_insert['assign_on'] = date('Y-m-d H:i:s', strtotime('now'));
                        $data_to_insert['assigner_turn'] = 1;
                    }
                    //
                    $this->hr_documents_management_model->insert_assigner_employee($data_to_insert);
                    //
                    if ($key == 0) {
                        //
                        // Send Email to first approver of this document
                        $this->SendEmailToCurrentApprover($document_sid);
                    }
                }
            }
        } else {

            $document_info = $this->get_approval_document_detail($document_sid);
            //
            $default_approver = $this->getDefaultApprovers(
                $document_info['company_sid'],
                $document_info['approval_flow_sid'],
                $document_info['has_approval_flow']
            );

            if (!empty($default_approver)) {
                //
                $approver_sid = 0;
                $approver_email = "";
                //
                if (is_numeric($default_approver) && $default_approver > 0) {
                    $approver_sid = $default_approver;
                    //
                    $this->change_document_approval_status(
                        $document_sid,
                        [
                            'document_approval_employees' => $approver_sid
                        ]
                    );
                } else {
                    $approver_email = $default_approver;
                }
                //

                $this->insert_assigner_employee(
                    [
                        'portal_document_assign_sid' =>  $document_info['approval_flow_sid'],
                        'assigner_sid' => $approver_sid,
                        'approver_email' => $approver_email,
                        'assign_on' =>  date('Y-m-d H:i:s', strtotime('now')),
                        'assigner_turn' => 1,
                    ]
                );
                //
                // Send Email to first approver of this document
                $this->sendEmailToCurrentApprover($document_sid);
            }
        }
    }


    function sendEmailToCurrentApprover($document_sid)
    {

        //
        $document_info = $this->hr_documents_management_model->get_approval_document_detail($document_sid);
        //
        $current_approver_info = $this->hr_documents_management_model->get_document_current_approver_sid($document_info['approval_flow_sid']);
        //
        $approver_info = array();
        $current_approver_reference = '';
        //
        if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
            //
            $default_approver = $this->hr_documents_management_model->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
            //
            $approver_name = explode(" ", $default_approver["contact_name"]);
            //
            $approver_info['first_name'] = isset($approver_name[0]) ? $approver_name[0] : "";
            $approver_info['last_name'] = isset($approver_name[1]) ? $approver_name[1] : "";
            $approver_info['email'] = $default_approver["email"];
            //
            $current_approver_reference = $default_approver["email"];
        } else {
            //
            $approver_info = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $current_approver_info["assigner_sid"]);
            //
            $current_approver_reference = $current_approver_info["assigner_sid"];
        }

        //
        $approvers_flow_info = $this->hr_documents_management_model->get_approval_document_bySID($document_info['approval_flow_sid']);
        //
        // Get the initiator name
        $document_initiator_name = getUserNameBySID($approvers_flow_info["assigned_by"]);
        //
        // Get the company name
        $company_name = getCompanyNameBySid($document_info['company_sid']);
        //
        // Get assigned document user name
        if ($document_info['user_type'] == 'employee') {
            //
            $t = $this->hr_documents_management_model->get_employee_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        } else {
            //
            $t = $this->hr_documents_management_model->get_applicant_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        }
        //
        $hf = message_header_footer_domain($document_info['company_sid'], $company_name);
        //
        $this->load->library('encryption');
        //
        $this->encryption->initialize(
            get_encryption_initialize_array()
        );
        //
        $accept_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'accept')
        );
        //
        $reject_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'reject')
        );
        //
        $view_code = str_replace(
            ['/', '+'],
            ['$$ab$$', '$$ba$$'],
            $this->encryption->encrypt($document_sid . '/' . $current_approver_reference . '/' . 'view')
        );
        //
        $approval_public_link_accept = base_url("hr_documents_management/public_approval_document") . '/' . $accept_code;
        $approval_public_link_reject = base_url("hr_documents_management/public_approval_document") . '/' . $reject_code;
        $approval_public_link_view = base_url("hr_documents_management/public_approval_document") . '/' . $view_code;
        // 
        $replacement_array['initiator']             = $document_initiator_name;
        $replacement_array['contact-name']          = $document_assigned_user_name;
        $replacement_array['company_name']          = ucwords($company_name);
        $replacement_array['username']              = $replacement_array['contact-name'];
        $replacement_array['firstname']             = $approver_info['first_name'];
        $replacement_array['lastname']              = $approver_info['last_name'];
        $replacement_array['first_name']            = $approver_info['first_name'];
        $replacement_array['last_name']             = $approver_info['last_name'];
        $replacement_array['document_title']        = $document_info['document_title'];
        $replacement_array['user_type']             = $document_info['user_type'];
        $replacement_array['note']                  = $approvers_flow_info["assigner_note"];
        $replacement_array['baseurl']               = base_url();
        $replacement_array['accept_link']           = $approval_public_link_accept;
        $replacement_array['reject_link']           = $approval_public_link_reject;
        $replacement_array['view_link']             = $approval_public_link_view;
        //
        // Send email notification to approver with a private link
        log_and_send_templated_email(HR_DOCUMENTS_APPROVAL_FLOW, $approver_info['email'], $replacement_array, $hf, 1);
    }



    //
    function fetch_company_employees_by_id($company_sid, $employeeId)
    {
        $this->db->select('sid,first_name,last_name');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where_in('sid', $employeeId);
        $result = $this->db->get('users')->result_array();
        return $result;
    }

    public function moveDocumentsHistory ($applicantId, $employeeId) {
        //
        //General Documents
        $this->db->select('*');
        $this->db->where('user_sid', $applicantId);
        $this->db->where('user_type', 'applicant');
        $this->db->where('document_sid <>', 0);
        $records_obj = $this->db->get('documents_assigned_history');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        //
        if (!empty($records_arr)) {
           //
            foreach ($records_arr as $record) {
                //
                $this->db->select('sid');
                $this->db->where('document_sid', $record['document_sid']);
                $this->db->where('document_type', $record['document_type']);
                $this->db->where('user_sid', $employeeId);
                $this->db->where('user_type', 'employee');
                $record_obj = $this->db->get('documents_assigned');
                $record_arr = $record_obj->row_array();
                $record_obj->free_result();
                $return_data = array();
                //
                $newDocumentId = $record_arr['sid'];
                //
                unset($record['sid']);
                $record['doc_sid'] = $newDocumentId;
                $record['user_sid'] = $employeeId;
                $record['user_type'] = 'employee';
                //
                $this->db->insert('documents_assigned_history', $record);
                //
            }
        }
        //
        return true;
    }
}

<?php

class Hr_documents_management_model_new extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    //
    function update_assigned_documents($document_sid, $user_sid, $user_type, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->where('user_type', $user_type);
        $this->db->where('user_sid', $user_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }
    //
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

    //
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

    //
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
    //
    function insert_documents_assignment_record($data_to_insert)
    {
        $this->db->insert('documents_assigned', $data_to_insert);
        return $this->db->insert_id();
    }
    //
    function insert_documents_assignment_flow($data_to_insert)
    {
        $this->db->insert('portal_document_assign_flow', $data_to_insert);
        return $this->db->insert_id();
    }
    //
    function insert_assigner_employee($data_to_insert)
    {
        $this->db->insert('portal_document_assign_flow_employees', $data_to_insert);
        return $this->db->insert_id();
    }
    //
    function insert_documents_assignment_record_history($data_to_insert)
    {
        $this->db->insert('documents_assigned_history', $data_to_insert);
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

    //
    public function revoke_document_previous_flow($document_sid)
    {
        $this->db->where('document_sid', $document_sid);
        $this->db->set('assign_status', 0);
        $this->db->update('portal_document_assign_flow');
    }
    //
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
    //
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
    //
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
    //
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


    function update_assigned_document($document_sid, $data_to_update)
    {
        $this->db->where('sid', $document_sid);
        $this->db->update('documents_assigned', $data_to_update);
    }
}

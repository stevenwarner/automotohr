<?php

class Hr_document_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    function saveHrDocument($formpost) {
        $this->db->insert('hr_documents', $formpost);
        return $this->db->insert_id();
    }

    function getAllActiveDocuments($company_sid) {
        $this->db->order_by("sid", "desc");
        return $this->db->get_where('hr_documents', array('company_sid' => $company_sid, 'archive' => 1))->result_array();
    }

    function getalldocuments($company_sid = NULL) {
        $this->db->select('*');

        if ($company_sid != NULL) {
            $this->db->where('company_sid', $company_sid);
        }

        $this->db->order_by('sid', 'asc');
        $record_obj = $this->db->get('hr_documents');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function updateDocument($document_id, $updatedData) {
        $this->db->where('sid', $document_id)->update('hr_documents', $updatedData);
    }

    function getAllArchivedDocuments($company_sid) {
        $this->db->order_by("sid", "desc");
        return $this->db->get_where('hr_documents', array('company_sid' => $company_sid, 'archive' => 0))->result_array();
    }

    function getAllEmployeesByCompanyId($company_sid, $employer_id) {
        return $this->db->where('sid != ' . $employer_id)
                        ->where('parent_sid', $company_sid)
                        ->where('is_executive_admin', 0)
                        ->where('active', '1')
                        ->get('users')->result_array();
    }

    function get_document_detail($company_sid, $document_id) {
        return $this->db->get_where('hr_documents', array('company_sid' => $company_sid, 'sid' => $document_id))->result_array();
    }

    function updateHrDocument($document_id, $dataToUpdate) {
        $this->db->where('sid', $document_id)->update('hr_documents', $dataToUpdate);
    }

    function getCompanyOfferLetters($company_id) {
        $this->db->order_by("sid", "desc");
        return $this->db->get_where('offer_letter', array('company_sid' => $company_id))->result_array();
    }

    function saveOfferLetter($formpost) {
        $this->db->insert('offer_letter', $formpost);
    }

    function deleteOfferLetter($sid) {
        $this->db->where('sid', $sid)->delete('offer_letter');
    }

    function updateOfferLetter($sid, $dataToupdate) {
        $this->db->where('sid', $sid)->update('offer_letter', $dataToupdate);
    }

    function getAllEmployeesWithPendingAction($company_sid, $document_type) {
        return $this->db->select('sid,receiver_sid,document_sid')
                        ->where('company_sid', $company_sid)
                        ->where('document_type', $document_type)
                        ->order_by("sid", "desc")
                        ->get('hr_user_document')->result_array();
    }

    function getEmployeesDetails($employees) {
        return $this->db->select('sid,first_name,last_name,email')->where_in('sid', $employees)->order_by("sid", "desc")->get('users')->result_array();
    }

    function getEmployeePendingActionDocument($company_sid, $document_type, $employee_id) {
        return $this->db->select('*,hr_user_document.sid as userDocumentSid')
                        ->where('hr_user_document.company_sid', $company_sid)
                        ->where('hr_user_document.document_type', $document_type)
                        ->where('hr_user_document.receiver_sid', $employee_id)
                        ->join('hr_documents', 'hr_user_document.document_sid =hr_documents.sid')
                        ->get('hr_user_document')->result_array();
    }

    function getUserDocument($userDocumentSid) {
        return $this->db->select('*, hr_user_document.verification_key as ver_key')->join('users', 'hr_user_document.receiver_sid = users.sid')
                        ->get_where('hr_user_document', array('hr_user_document.sid' => $userDocumentSid))->result_array();
    }

    function uniqueDocumentName($document_name, $companyId) {
        return $this->db->where('company_sid', $companyId)
                        ->where('document_original_name', $document_name)
                        ->get('hr_documents')->num_rows();
    }

    function get_editors_data() {
        $this->db->where('sid', 1);
        $records_obj = $this->db->get('hr_documents_editors_data');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_hr_documents_section_record($data_to_insert) {
        $this->db->insert('hr_documents_editors_data', $data_to_insert);
    }

    function update_hr_documents_section_record($sid, $data_to_insert) {
        $this->db->where('sid', $sid);
        $this->db->update('hr_documents_editors_data', $data_to_insert);
    }

    function get_hr_documents_section_records($status = null) {
        $this->db->select('*');

        if ($status !== null) {
            $this->db->where('status', $status);
        }

        $records_obj = $this->db->get('hr_documents_editors_data');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function get_single_documents_section_record($sid) {
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('hr_documents_editors_data');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function delete_hr_documents_section($section_sid) {
        $this->db->where('sid', $section_sid);
        $this->db->delete('hr_documents_editors_data');
    }

    function get_admin_or_company_email_template($sid, $company_sid) {
        $this->db->select('*');
        $this->db->where('admin_template_sid', $sid);
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            $data = array();
            $data['from_email'] = $record_arr[0]['from_email'];
            $data['subject'] = $record_arr[0]['subject'];
            $data['from_name'] = $record_arr[0]['from_name'];
            $data['text'] = $record_arr[0]['message_body'];
            return $data;
        } else { // company does not have its own version
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
    }

    function get_unassigned_documents() { //hr_user_document
        $this->db->select('*');
        $this->db->where('documents_assigned_sid', 0);
        $this->db->where('document_type', 'document');
        $this->db->order_by('sid', 'asc');
        $record_obj = $this->db->get('hr_user_document');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

    function get_document_details($sid) {
        $this->db->select('sid, document_title, document_description, document_type, uploaded_document_original_name, uploaded_document_extension, uploaded_document_s3_name, unique_key');
        $this->db->where('copied_doc_sid', $sid);
        $record_obj = $this->db->get('documents_management');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function assign_document_details($data) {
        $this->db->insert('documents_assigned', $data);
        return $this->db->insert_id();
    }

    function update_hr_user_document($sid, $documents_assigned_sid) {
        $this->db->where('sid', $sid);
        $data_to_update = array();
        $data_to_update['documents_assigned_sid'] = $documents_assigned_sid;
        $this->db->update('hr_user_document', $data_to_update);
    }
    
    
    function update_hr_document($document_id, $data) {
        $this->db->where('sid', $document_id)->update('hr_documents', $data);
    }
    
    function get_all_hr_documents($company_sid = NULL) {
        $this->db->select('*');
        $this->db->where('documents_management_sid', 0);
        
        if($company_sid != NULL) {
             $this->db->where('company_sid', $company_sid);
        }
        
        $this->db->order_by('sid', 'asc');
        $record_obj = $this->db->get('hr_documents');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();
        return $record_arr;
    }

}

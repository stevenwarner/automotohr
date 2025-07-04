<?php

class Marketing_agency_documents_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all_companies_and_documents($marketing_agency_sid = 0, $marketing_agency_name = 'all')
    {
        $this->db->select('sid, full_name, status, email');
        $this->db->where('status', 1);
        $this->db->where('sid', $marketing_agency_sid);
        if(!empty($marketing_agency_name) && $marketing_agency_name != 'all'){
            $this->db->like('full_name', $marketing_agency_name);
        }
        $this->db->order_by('sid', 'DESC');
        $marketing_agencies = $this->db->get('marketing_agencies')->result_array();

        if (!empty($marketing_agencies)) {

            //Get Affiliate End User License Agreement Form
            $this->db->select('*');
            $this->db->limit(1);
            $this->db->order_by('sid', 'DESC');
            $this->db->where('marketing_agency_sid', $marketing_agency_sid);
            $eula = $this->db->get('form_affiliate_end_user_license_agreement')->result_array();

            if (!empty($eula)) {
                $marketing_agencies[0]['eula'] = $eula[0];
            } else {
                $verification_key = random_key(80);
                $this->insert_affiliate_document_record($marketing_agency_sid, $verification_key, 'generated');
                $this->db->select('*');
                $this->db->limit(1);
                $this->db->order_by('sid', 'DESC');
                $this->db->where('marketing_agency_sid', $marketing_agency_sid);
                $eula = $this->db->get('form_affiliate_end_user_license_agreement')->result_array();
                $marketing_agencies[0]['eula'] = $eula[0];
            }

            //Uploaded Documents
            $uploaded_documents = $this->get_all_forms_documents_uploaded($marketing_agency_sid);
            if (!empty($uploaded_documents)) {
                $marketing_agencies[0]['uploaded_documents'] = $uploaded_documents;
            } else {
                $marketing_agencies[0]['uploaded_documents'] = array();
            }
        }

        return $marketing_agencies;
    }

    function insert_document_record($document, $company_sid, $verification_key, $status, $additional_fields_data = array())
    {
        $dataToSave = array();
        $dataToSave['company_sid'] = $company_sid;
        $dataToSave['verification_key'] = $verification_key;
        $dataToSave['status'] = $status;
        $dataToSave['status_date'] = date('Y-m-d H:i:s');

        if (!empty($additional_fields_data)) {
            $dataToSave = array_merge($dataToSave, $additional_fields_data);
        }

        if (strtolower($document) == 'end_user_license_agreement') {
            $this->db->insert('form_document_eula', $dataToSave);
        } elseif (strtolower($document) == 'credit_card_authorization_form') {
            $this->db->insert('form_document_credit_card_authorization', $dataToSave);
        } elseif (strtolower($document) == 'company_contacts') {
            $this->db->insert('form_document_company_contacts', $dataToSave);
        } elseif (strtolower($document) == 'uploaded_document') {
            $this->db->insert('forms_documents_uploaded', $dataToSave);
        }

        $this->insert_document_ip_tracking_record($company_sid, 0, $_SERVER['REMOTE_ADDR'], $document, 'new_generated', $_SERVER['HTTP_USER_AGENT']);

    }

    function insert_affiliate_document_record($marketing_agency_sid, $verification_key, $status)
    {
        $dataToSave = array();
        $dataToSave['marketing_agency_sid'] = $marketing_agency_sid;
        $dataToSave['verification_key'] = $verification_key;
        $dataToSave['status'] = $status;
        $dataToSave['status_date'] = date('Y-m-d H:i:s');
        $this->db->insert('form_affiliate_end_user_license_agreement', $dataToSave);

        $this->insert_document_ip_tracking_record($marketing_agency_sid, 0, $_SERVER['REMOTE_ADDR'], 'form_affiliate_end_user_license_agreement', 'new_generated', $_SERVER['HTTP_USER_AGENT']);
    }

    function update_document_record($document, $verification_key, $fields_data = array(), $status = 'sent')
    {
        $this->db->where('verification_key', $verification_key);

        $dataToSave = array();
        if (strtolower($document) != 'uploaded_document'){
            $dataToSave['status_date'] = date('Y-m-d H:i:s');
        }
        $dataToSave['status'] = $status;

        $dataToSave = array_merge($dataToSave, $fields_data);


        if (!empty($dataToSave)) {
            if (strtolower($document) == 'credit_card_authorization_form') {
                $this->db->update('form_document_credit_card_authorization', $dataToSave);
            } elseif (strtolower($document) == 'form_affiliate_end_user_license_agreement') {
                $this->db->update('form_affiliate_end_user_license_agreement', $dataToSave);
            } elseif (strtolower($document) == 'end_user_license_agreement') {
                $this->db->update('form_document_eula', $dataToSave);
            } elseif (strtolower($document) == 'company_contacts') {
                $this->db->update('form_document_company_contacts', $dataToSave);
            } elseif (strtolower($document) == 'uploaded_document') {
                $this->db->update('marketing_agency_documents', $dataToSave);
            }
        }
    }

    function get_document_record($document, $verification_key)
    {
        $document_data = array();

        if ($document == 'form_affiliate_end_user_license_agreement') {

            $this->db->select('form_affiliate_end_user_license_agreement.*');
            $this->db->select('marketing_agencies.full_name, marketing_agencies.email, marketing_agencies.sid as marketing_agencies_sid');
            $this->db->limit(1);

            $this->db->where('form_affiliate_end_user_license_agreement.verification_key', $verification_key);
            $this->db->join('marketing_agencies', 'marketing_agencies.sid = form_affiliate_end_user_license_agreement.marketing_agency_sid', 'left');

            $document_data = $this->db->get('form_affiliate_end_user_license_agreement')->result_array();

        } elseif ($document == 'uploaded_document') {
            $this->db->select('marketing_agency_documents.*');
            $this->db->select('marketing_agencies.full_name, marketing_agencies.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('marketing_agency_documents.verification_key', $verification_key);

            $this->db->join('marketing_agencies', 'marketing_agencies.sid = marketing_agency_documents.marketing_agency_sid', 'left');

            $document_data = $this->db->get('marketing_agency_documents')->result_array();
        }

        if (!empty($document_data)) {
            return $document_data[0];
        } else {
            return array();
        }
    }

    function get_agent_record($sid, $target)
    {
        $agent_data = '';

        if ($target == 'Form_affiliate_end_user_license_agreement') {
            $this->db->select('sid, full_name, email');
            $this->db->where('sid', $sid);
            $agent_data = $this->db->get('marketing_agencies')->result_array();
        } elseif ($target == 'Form_end_user_license_agreement') {
            $this->db->select('sid, username as full_name, email');
            $this->db->where('sid', $sid);
            $agent_data = $this->db->get('users')->result_array();
        }
        

        if (!empty($agent_data)) {
            return $agent_data[0];
        } else {
            return array();
        }
    }

    function update_document_status($document, $verification_key, $status)
    {
        $dataToSave = array();
        $dataToSave['status'] = $status;

        $this->db->where('verification_key', $verification_key);

        if (!empty($dataToSave)) {
            if (strtolower($document) == 'credit_card_authorization') {
                $this->db->update('form_document_credit_card_authorization', $dataToSave);
            } elseif (strtolower($document) == 'form_affiliate_end_user_license_agreement') {
                $this->db->update('form_affiliate_end_user_license_agreement', $dataToSave);
            } elseif (strtolower($document) == 'company_contacts') {
                $this->db->update('form_document_company_contacts', $dataToSave);
            } elseif (strtolower($document) == 'uploaded_document') {
                $this->db->update('marketing_agency_documents', $dataToSave);
            }
        }
    }

    function insert_company_contact_records($company_sid, $company_contacts_data)
    {
        $this->delete_company_contact_records($company_sid);
        $this->db->insert_batch('form_document_company_contact_details', $company_contacts_data);
    }

    function delete_company_contact_records($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->delete('form_document_company_contact_details');
    }

    function get_company_employees($company_sid)
    {
        $this->db->select('sid, first_name, last_name, email');
        $this->db->where('parent_sid', $company_sid);
        $this->db->order_by(SORT_COLUMN,SORT_ORDER);
        $data = $this->db->get('users')->result_array();

        return $data;
    }

    function insert_document_email_history_record($company_sid, $document_sid, $forwarded_to_name, $forwarded_to_email)
    {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['document_sid'] = $document_sid;
        $data_to_insert['forwarded_on_date'] = date('Y-m-d H:i:s');
        $data_to_insert['forwarded_to_name'] = $forwarded_to_name;
        $data_to_insert['forwarded_to_email'] = $forwarded_to_email;

        $this->db->insert('forms_documents_email_history', $data_to_insert);
    }

    function get_document_sid($document, $verification_key)
    {
        $this->db->select('sid');
        $this->db->where('verification_key', $verification_key);

        $data = array();

        if (strtolower($document) == 'credit_card_authorization') {
            $data = $this->db->get('form_document_credit_card_authorization')->result_array();
        } elseif (strtolower($document) == 'end_user_license_agreement') {
            $data = $this->db->get('form_document_eula')->result_array();
        } elseif (strtolower($document) == 'company_contacts') {
            $data = $this->db->get('form_document_company_contacts')->result_array();
        }

        if (!empty($data)) {
            return $data[0]['sid'];
        } else {
            return 0;
        }
    }

    function get_all_forms_documents_uploaded($marketing_agency_sid)
    {
        $this->db->select('*');
        $this->db->where('marketing_agency_sid', $marketing_agency_sid);
        return $this->db->get('marketing_agency_documents')->result_array();
    }


    function update_database($sid, $cc_type, $cc_holder_name, $cc_number, $cc_expiration_month, $cc_expiration_year)
    {
        $this->db->where('sid', $sid);
        $dataToUpdate = array();
        $dataToUpdate['cc_type'] = $cc_type;
        $dataToUpdate['cc_holder_name'] = $cc_holder_name;
        $dataToUpdate['cc_number'] = $cc_number;
        $dataToUpdate['cc_expiration_month'] = $cc_expiration_month;
        $dataToUpdate['cc_expiration_year'] = $cc_expiration_year;
        $dataToUpdate['processed'] = 1;
        $this->db->update('form_document_credit_card_authorization', $dataToUpdate);
        //echo '<br><br> LAST QUERY: <br><br>'.$this->db->last_query();
    }

    function get_cc_auth()
    {
        $this->db->select('*');
        $this->db->where('processed', 0);
        $this->db->where('status', 'signed');
        $this->db->order_by('sid', 'ASC');
        return $this->db->get('form_document_credit_card_authorization')->result_array();
    }

    function insert_document_ip_tracking_record($company_sid, $logged_in_sid, $ip_address, $document, $document_status, $user_agent, $user_sid = null, $user_type = null)
    {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['logged_in_sid'] = $logged_in_sid;
        $data_to_insert['ip_address'] = $ip_address;
        $data_to_insert['document'] = $document;
        $data_to_insert['document_status'] = $document_status;
        $data_to_insert['user_agent'] = $user_agent;

        if($user_sid != null) {
            $data_to_insert['user_sid'] = $user_sid;
        }

        if($user_type != null) {
            $data_to_insert['user_type'] = $user_type;
        }

        $this->db->insert('documents_ip_tracking', $data_to_insert);
    }

    function get_document_ip_tracking_record($company_sid, $document, $status = 'signed', $user_sid = null, $user_type = null){
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document', $document);
        $this->db->where('document_status', $status);

        if($user_sid != null && $user_type != null) {
            $this->db->where('user_sid', $user_sid);
            $this->db->where('user_type', $user_type);
        }

        $this->db->order_by('sid', 'DESC');
        $this->db->limit(1);

        $records_obj = $this->db->get('documents_ip_tracking');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function update_commission_info($record_sid, $marketing_agency_sid, $value, $type){

        $this->db->where('sid', $record_sid);
        $this->db->where('marketing_agency_sid', $marketing_agency_sid);
        $dataToUpdate = array();
        
        if ($type == 'closed_qualified_customers') {
            $dataToUpdate['closed_qualified_customers'] = $value;
        } elseif ($type == 'commission_schedule_percentage') {
            $dataToUpdate['commission_schedule_percentage'] = $value;
        } elseif ($type == 'commission_schedule_flat') {
            $dataToUpdate['commission_schedule_flat'] = $value;
        }

        $this->db->update('form_affiliate_end_user_license_agreement', $dataToUpdate);
    }

    function get_applicant_signature($company_sid, $user_sid){
        $this->db->select('signature_bas64_image, signature_timestamp');
        $this->db->where('sid', $user_sid);
        $this->db->where('employer_sid', $company_sid);

        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_employee_signature ($sid) {
        $this->db->select('signature_bas64_image, signature_timestamp');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('e_signatures_data');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_marketing_document($data_to_insert) {
        $this->db->insert('marketing_agency_documents', $data_to_insert);
    }

    function active_deactive_status($sid, $data){
        $this->db->where('sid',$sid);
        $this->db->update('marketing_agency_documents',$data);
    }

    function get_marketing_agency_data($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('marketing_agencies');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_w9_form_record($data_to_insert) {
        $this->db->insert('affiliate_w9form', $data_to_insert);
    }

    function get_form_w9_status ($sid) {
        $this->db->select('w9_form_status');
        $this->db->where('affiliate_sid', $sid);
        $records_obj = $this->db->get('affiliate_w9form');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0]['w9_form_status'];
        } else {
            return array();
        }
    }

    function get_form_w9_data ($sid) {
        $this->db->select('*');
        $this->db->where('affiliate_sid', $sid);
        $records_obj = $this->db->get('affiliate_w9form');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function check_w9_form_exist($sid){
        $this->db->select('*');
        $this->db->where('affiliate_sid', $sid);
        $records_obj = $this->db->get('affiliate_w9form');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_w9_forms_history($data_to_insert) {
        $this->db->insert('affiliate_w9form_history', $data_to_insert);
    }

    function reassign_w9_forms($sid, $dataToUpdate)
    {
        $this->db->where('sid', $sid);
        $this->db->update('affiliate_w9form', $dataToUpdate);
    }

    function set_verification_key($sid, $verification_key)
    {
        $dataToUpdate = array();
        $dataToUpdate['verification_key'] = $verification_key;

        $this->db->where('sid', $sid);
        $this->db->update('marketing_agencies', $dataToUpdate);
    }

    function save_email_logs($data) {
        $this->db->insert('email_log', $data);
    }

    function get_w9_form_data($sid){
        $this->db->select('*');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('affiliate_w9form');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        
        if(!empty($records_arr)){
            return $records_arr[0];
        } else {
            return array();
        }
    }

}

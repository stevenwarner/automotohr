<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Merge_company_employee_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_employee_profile($sid)
    {
        $this->db->select('*');
        $this->db->where('sid', $sid);

        $result = $this->db->get('users')->result_array();

        if (empty($result)) {// applicant does not exits
            return array();
        } else {
            return $result[0];
        }
    }

    function update_company_employee($primary_employee_sid, $secondary_employee_sid)
    {
        //
        $secondary_employee = $this->get_employee_profile($secondary_employee_sid);
        $primary_employee = $this->get_employee_profile($primary_employee_sid);
        //
        $data_to_update = $this->findDifference($primary_employee, $secondary_employee);
        //
        if (!empty($data_to_update)) {
            $data['session'] = $this->session->userdata('logged_in');
            $merge_by = $data['session']['employer_detail']['sid'];
            //
            $merge_history = array(
                'primary_employee_sid' => $primary_employee_sid,
                'secondary_employee_sid' => $secondary_employee_sid,
                'primary_employee_profile_data' => serialize($primary_employee),
                'secondary_employee_profile_data' => serialize($secondary_employee),
                'updated_profile_data' => serialize($data_to_update),
                'merge_by' => $merge_by,
                'merge_at' => date('Y-m-d')
            );
            //
            $this->db->insert('employee_merge_history', $merge_history);
            //
            //Update primary Employee
            $this->db->where('sid', $primary_employee_sid);
            $this->db->update('users', $data_to_update);
            //
            //Delete secondary Employee
            $this->db->where('sid', $secondary_employee_sid);
            $this->db->delete('users');
        }
        //
    }

    function update_employee_emergency_contacts($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'first_name, last_name, email, Location_Country, Location_State, Location_City, Location_ZipCode, Location_Address, PhoneNumber, Relationship, priority';
        //
        $this->db->select($columns);
        $this->db->where('users_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_emergency_contacts = $this->db->get('emergency_contacts')->result_array();
        //
        if (count($secondary_emergency_contacts) > 0) {
            foreach ($secondary_emergency_contacts as $secondary_contact) {
                $this->db->select($columns);
                $this->db->where('users_sid', $primary_employee_sid);
                $this->db->where('users_type', 'employee');
                $this->db->where('email', $secondary_contact['email']);
                $primary_contact = $this->db->get('emergency_contacts')->row_array();
                //
                if (!empty($primary_contact)) {
                    $data_to_update = $this->findDifference($primary_contact, $secondary_contact);
                    //
                    if (!empty($data_to_update)) {
                        //Update primary Employee
                        $this->db->where('users_sid', $primary_employee_sid);
                        $this->db->where('users_type', 'employee');
                        $this->db->where('email', $secondary_contact['email']);
                        $this->db->update('emergency_contacts', $data_to_update);
                    }
                } else {
                    $primary_contact = array();
                    //
                    $data_to_insert = $this->findDifference($primary_contact, $secondary_contact);
                    $data_to_insert["users_sid"] = $primary_employee_sid;
                    $data_to_insert["users_type"] = "employee";
                    //
                    if (!empty($data_to_insert)) {
                        $this->db->insert('emergency_contacts', $data_to_insert);
                    }
                }
            }
        }
    }

    function update_employee_equipment_information($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'equipment_details, equipment_type, brand_name,imei_no, model,issue_date,color,notes,product_id,specification,vin_number,transmission_type,fuel_type,serial_number,acknowledgement_flag,acknowledgement_datetime,acknowledgement_notes,acknowledge_by_ip,delete_flag,delete_datetime,delete_by_id,delete_by_ip,assigned_id,assigned_by_ip';
        //
        $this->db->select($columns);
        $this->db->where('users_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_equipments = $this->db->get('equipment_information')->result_array();
        //
        if (count($secondary_equipments) > 0) {
            foreach ($secondary_equipments as $secondary_equipment) {
                $primary_equipment = array();
                //
                $data_to_insert = $this->findDifference($primary_equipment, $secondary_equipment);
                //
                $data_to_insert["users_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('equipment_information', $data_to_insert);
            }
        }
    }

    function update_employee_dependant_information($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'company_sid, dependant_details';
        //
        $this->db->select($columns);
        $this->db->where('users_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_dependants = $this->db->get('dependant_information')->result_array();
        //
        if (count($secondary_dependants) > 0) {
            foreach ($secondary_dependants as $secondary_dependant) {
                $primary_dependant = array();
                //
                $data_to_insert = $this->findDifference($primary_dependant, $secondary_dependant);
                //
                $data_to_insert["users_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('dependant_information', $data_to_insert);
            }
        }
    }

    function update_employee_license_information($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'license_type, license_details, license_file, last_notification_sent_at';
        //
        $this->db->select($columns);
        $this->db->where('users_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_licenses = $this->db->get('license_information')->result_array();
        //
        if (count($secondary_licenses) > 0) {
            foreach ($secondary_licenses as $secondary_license) {
                $primary_license = array();
                //
                $data_to_insert = $this->findDifference($primary_license, $secondary_license);
                //
                $data_to_insert["users_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('license_information', $data_to_insert);
            }
        } 
    }

    function update_employee_background_check($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'employer_sid, company_sid, product_sid, package_id, package_response, order_response, order_response_status, date_applied, date_response_received, product_price, product_name, product_type, product_image';
        //
        $this->db->select($columns);
        $this->db->where('users_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_background_checks = $this->db->get('background_check_orders')->result_array();
        //
        if (count($secondary_background_checks) > 0) {
            foreach ($secondary_background_checks as $secondary_check) {
                $primary_check = array();
                //
                $data_to_insert = $this->findDifference($primary_check, $secondary_check);
                //
                $data_to_insert["users_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('background_check_orders', $data_to_insert);
            }
        }
    }

    function update_employee_misc_notes($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'employers_sid, applicant_email, notes, insert_date, modified_date';
        //
        $this->db->select($columns);
        $this->db->where('applicant_job_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_notes = $this->db->get('portal_misc_notes')->result_array();
        //
        if (count($secondary_notes) > 0) {
            foreach ($secondary_notes as $secondary_note) {
                $primary_check = array();
                //
                $data_to_insert = $this->findDifference($primary_check, $secondary_check);
                //
                $data_to_insert["applicant_job_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('portal_misc_notes', $data_to_insert);
            }
        }
    }

    function update_employee_private_message($primary_employee_sid, $secondary_employee_email)
    {
        $primary_employee_info = get_employee_profile_info($primary_employee_sid);
        $primary_employee_email = $primary_employee_info['email'];
        //
        $columns = 'from_id, from_type, to_type, date, status, subject, message, outbox, anonym, attachment, job_id, identity_key, contact_name';
        //
        $this->db->select($columns);
        $this->db->where('to_id', $secondary_employee_email);
        $this->db->where('users_type', 'employee');
        $secondary_employee_messages = $this->db->get('private_message')->result_array();
        //
        if (count($secondary_employee_messages) > 0) {
            foreach ($secondary_employee_messages as $secondary_message) {
                $primary_message = array();
                //
                $data_to_insert = $this->findDifference($primary_message, $secondary_message);
                //
                $data_to_insert["to_id"] = $primary_employee_email;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('private_message', $data_to_insert);
            }
        }
    }

    function update_employee_rating($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'company_sid, employer_sid, applicant_email, rating, comment, date_added, attachment, attachment_extension';
        //
        $this->db->select($columns);
        $this->db->where('applicant_job_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_employee_rating = $this->db->get('portal_applicant_rating')->result_array();
        //
        if (count($secondary_employee_rating) > 0) {
            foreach ($secondary_employee_rating as $secondary_rating) {
                $primary_rating = array();
                //
                $data_to_insert = $this->findDifference($primary_rating, $secondary_rating);
                //
                $data_to_insert["applicant_job_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('portal_applicant_rating', $data_to_insert);
            }
        }
    }

    function update_employee_schedule_event($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'companys_sid, employers_sid, applicant_email, title, category, date, description, eventstarttime, eventendtime, interviewer, commentCheck,
        messageCheck, comment, subject, message, messageFile, address, goToMeetingCheck, meetingId, meetingCallNumber, meetingURL, created_on';
        //
        $this->db->select($columns);
        $this->db->where('applicant_job_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_employee_events = $this->db->get('portal_schedule_event')->result_array();
        //
        if (count($secondary_employee_events) > 0) {
            foreach ($secondary_employee_events as $secondary_event) {
                $primary_event = array();
                //
                $data_to_insert = $this->findDifference($primary_event, $secondary_event);
                //
                $data_to_insert["applicant_job_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('portal_schedule_event', $data_to_insert);
            }
        }
    }

    function update_employee_attachments($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'employer_sid, original_name, uploaded_name, date_uploaded, status';
        //
        $this->db->select($columns);
        $this->db->where('applicant_job_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'employee');
        $secondary_employee_attachments = $this->db->get('portal_applicant_attachments')->result_array();
        //
        if (count($secondary_employee_attachments) > 0) {
            foreach ($secondary_employee_attachments as $secondary_attachment) {
                $primary_attachment = array();
                //
                $data_to_insert = $this->findDifference($primary_attachment, $secondary_attachment);
                //
                $data_to_insert["applicant_job_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('portal_applicant_attachments', $data_to_insert);
            }
        }
    }

    function update_employee_reference_checks($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'company_sid, organization_name, department_name, branch_name, program_name, period_start, period_end, period, reference_type, reference_name, reference_title, reference_relation, reference_email, reference_phone, best_time_to_call, other_information, questionnaire_information, questionnaire_conducted_by, verified, status';
        //
        $this->db->select($columns);
        $this->db->where('user_sid', $secondary_employee_sid);
        $this->db->where('users_type', 'applicant');
        $secondary_employee_checks = $this->db->get('reference_checks')->result_array();
        //
        if (count($secondary_employee_checks) > 0) {
            foreach ($secondary_employee_checks as $secondary_check) {
                $primary_check = array();
                //
                $data_to_insert = $this->findDifference($primary_check, $secondary_check);
                //
                $data_to_insert["user_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('reference_checks', $data_to_insert);
            }
        }
    }

    function update_onboarding_configuration($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'employer_sid, company_sid, items, section';
        //
        $this->db->select($columns);
        $this->db->where('user_sid', $secondary_employee_sid);
        $this->db->where('user_type', 'applicant');
        $secondary_employee_configurations = $this->db->get('onboarding_applicants_configuration')->result_array();
        //
        if (!empty($secondary_employee_configurations)) {
            foreach ($secondary_employee_configurations as $secondary_configuration) {
                $primary_configuration = array();
                //
                $data_to_insert = $this->findDifference($primary_configuration, $secondary_configuration);
                //
                $data_to_insert["user_sid"] = $primary_employee_sid;
                $data_to_insert["user_type"] = "employee";
                //
                $this->db->insert('onboarding_applicants_configuration', $data_to_insert);
            }
        }
    }

    function update_documents($primary_employee_sid, $secondary_employee_sid)
    {
        //Fillable Forms
        //W4
        $this->db->select('sid');
        $this->db->where('employer_sid', $primary_employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->where('status', 1);
        $primary_employee_w4 = $this->db->get('form_w4_original')->row_array();
        //
        if(empty($primary_employee_w4)){
            $this->db->select('*');
            $this->db->where('employer_sid', $secondary_employee_sid);
            $this->db->where('user_type', 'employee');
            $this->db->where('status', 1);
            $secondary_employee_w4 = $this->db->get('form_w4_original')->row_array();
            //
            if(!empty($secondary_employee_w4)){
                unset($secondary_employee_w4['sid']);
                $secondary_employee_w4['employer_sid'] = $primary_employee_sid;
                $this->db->insert('form_w4_original', $secondary_employee_w4);
            }
        }

        //W9
        $this->db->select('sid');
        $this->db->where('user_sid', $primary_employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->where('status', 1);
        $primary_employee_w9 = $this->db->get('applicant_w9form')->row_array();
        //
        if(empty($primary_employee_w9)){
            $this->db->select('*');
            $this->db->where('user_sid', $secondary_employee_sid);
            $this->db->where('user_type', 'employee');
            $this->db->where('status', 1);
            $secondary_employee_w9 = $this->db->get('applicant_w9form')->row_array();
            //
            if(!empty($secondary_employee_w9)){
                unset($secondary_employee_w9['sid']);
                $secondary_employee_w9['user_sid'] = $primary_employee_sid;
                $this->db->insert('applicant_w9form', $secondary_employee_w9);
            }
        }

        //I9
        $this->db->select('sid');
        $this->db->where('user_sid', $primary_employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->where('status', 1);
        $primary_employee_i9 = $this->db->get('applicant_i9form')->row_array();
        //
        if(empty($primary_employee_i9)){
            $this->db->select('*');
            $this->db->where('user_sid', $secondary_employee_sid);
            $this->db->where('user_type', 'employee');
            $this->db->where('status', 1);
            $secondary_employee_i9 = $this->db->get('applicant_i9form')->row_array();
            //
            if(!empty($secondary_employee_i9)){
                unset($secondary_employee_i9['sid']);
                $secondary_employee_i9['user_sid'] = $primary_employee_sid;
                $this->db->insert('applicant_i9form', $secondary_employee_i9);
            }
        }

        //General Documents
        $this->db->select('sid');
        $this->db->where('user_sid', $primary_employee_sid);
        $this->db->where('user_type', 'employee');
        $primary_general_docs = $this->db->get('documents_assigned')->result_array();

        if (sizeof($primary_general_docs) == 0) {
            $this->db->select('*');
            $this->db->where('user_sid', $secondary_employee_sid);
            $this->db->where('user_type', 'employee');
            $secondary_general_docs = $this->db->get('documents_assigned')->result_array();
            //
            if (!empty($secondary_general_docs)) {
                foreach ($secondary_general_docs as $secondary_doc) {
                    unset($secondary_doc['sid']);
                    $secondary_doc['user_sid'] = $primary_employee_sid;
                    $secondary_doc['user_type'] = 'employee';
                    $this->db->insert('documents_assigned', $secondary_doc);
                }
            }
        } else {
            $already_sid = array();
            //
            foreach($primary_general_docs as $obj){
                $already_sid[] = $obj['sid'];
            }
            //
            $this->db->select('*');
            $this->db->where('user_sid', $secondary_employee_sid);
            $this->db->where('user_type', 'employee');
            $secondary_general_docs = $this->db->get('documents_assigned')->result_array();
            //
            if (!empty($secondary_general_docs)) {
                foreach ($secondary_general_docs as $secondary_doc) {
                    if(!in_array($secondary_doc['sid'],$already_sid)){
                        unset($secondary_doc['sid']);
                        $secondary_doc['user_sid'] = $hired_sid;
                        $secondary_doc['user_type'] = 'employee';
                        $this->db->insert('documents_assigned', $secondary_doc);
                    }
                }
            } 
        }
    }

    function update_employee_direct_deposit_information($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'company_sid, account_title, routing_transaction_number, account_number, financial_institution_name, account_type, voided_cheque, account_status, account_percentage, is_consent, instructions, user_signature, employee_number, print_name, consent_date, updated_by, is_payroll, is_deleted';
        //
        $this->db->select($columns);
        $this->db->where('users_type', 'employee');
        $this->db->where('users_sid', $secondary_employee_sid);
        $secondary_employee_direct_deposits = $this->db->get('bank_account_details')->result_array();
        //
        if (!empty($secondary_employee_direct_deposits)) {
            foreach ($secondary_employee_direct_deposits as $secondary_direct_deposit) {
                $primary_direct_deposit = array();
                //
                $data_to_insert = $this->findDifference($primary_direct_deposit, $secondary_direct_deposit);
                //
                $data_to_insert["users_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                $this->db->insert('bank_account_details', $data_to_insert);
            }
        }
    }

    function update_employee_e_signature($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'company_sid, first_name, last_name, email_address, signature, init_signature, signature_hash, signature_timestamp, signature_bas64_image, init_signature_bas64_image, active_signature, ip_address, latitude, longitude, user_agent, user_consent, is_active';
        //
        $this->db->select($columns);
        $this->db->where('user_sid', $secondary_employee_sid);
        $this->db->where('user_type', 'employee');
        $this->db->where('is_active', 1);
        $secondary_employee_signature = $this->db->get('e_signatures_data')->row_array();
        //
        if (!empty($secondary_employee_signature)) {     
            $this->db->select($columns);
            $this->db->where('user_sid', $primary_employee_sid);
            $this->db->where('user_type', 'employee');
            $this->db->where('is_active', 1);
            $primary_employee_signature = $this->db->get('e_signatures_data')->row_array();
            //
            if (!empty($primary_employee_signature)) {
                $data_to_update = $this->findDifference($primary_employee_signature, $secondary_employee_signature);
                //
                if (!empty($data_to_update)) {
                    //Update primary Employee
                    $this->db->where('user_sid', $primary_employee_sid);
                    $this->db->where('user_type', 'employee');
                    $this->db->update('e_signatures_data', $data_to_update);
                }
            } else {
                $primary_employee_signature = array();
                //
                $data_to_insert = $this->findDifference($primary_employee_signature, $secondary_employee_signature);
                $data_to_insert["user_sid"] = $primary_employee_sid;
                $data_to_insert["user_type"] = "employee";
                //
                if (!empty($data_to_insert)) {
                    $this->db->insert('e_signatures_data', $data_to_insert);
                }
            }
        }
    }

    function update_employee_eeoc_form ($primary_employee_sid, $secondary_employee_sid)
    {
        $columns = 'portal_applicant_jobs_list_sid, us_citizen, visa_status, group_status, veteran, disability, gender, is_latest, last_sent_at, is_expired';
        //
        $this->db->select($columns);
        $this->db->where('users_type', 'employee');
        $this->db->where('application_sid', $secondary_employee_sid);
        $secondary_employee_eeoc = $this->db->get('portal_eeo_form')->row_array();

        if (!empty($secondary_employee_eeoc)) {
            $this->db->where('users_type', 'employee');
            $this->db->where('application_sid', $primary_employee_sid);
            $primary_employee_eeoc = $this->db->get('portal_eeo_form')->row_array();
            //
            if (!empty($primary_employee_eeoc)) {
                $data_to_update = $this->findDifference($primary_employee_eeoc, $secondary_employee_eeoc);
                //
                if (!empty($data_to_update)) {
                    //Update primary Employee
                    $this->db->where('application_sid', $primary_employee_sid);
                    $this->db->where('users_type', 'employee');
                    $this->db->update('portal_eeo_form', $data_to_update);
                }
            } else {
                $primary_employee_eeoc = array();
                //
                $data_to_insert = $this->findDifference($primary_employee_eeoc, $secondary_employee_eeoc);
                $data_to_insert["application_sid"] = $primary_employee_sid;
                $data_to_insert["users_type"] = "employee";
                //
                if (!empty($data_to_insert)) {
                    $this->db->insert('portal_eeo_form', $data_to_insert);
                }
            }
        }
    }

    function findDifference ($primary_data, $secondary_data) {
        // 
        $difference = array();
        //
        if (!empty($primary_data)) {
            foreach ($primary_data as $key => $data) {
                //   
                if (empty($data) && !isset($data) || $data == "") {
                    if (!empty($secondary_data[$key])) {
                        $difference[$key] = $secondary_data[$key];
                    }       
                }
                //
                if ($this->is_serialized($data)) {
                    //
                    $primary_serialize = unserialize($data);
                    //
                    if (isset($secondary_data[$key])) {
                        $secondary_serialize = unserialize($secondary_data[$key]);
                        //
                        foreach ($secondary_serialize as $ss_key => $ss_data) {
                            if (empty($primary_serialize[$ss_key]) || !isset($primary_serialize[$ss_key])) {
                                $primary_serialize[$ss_key] = $secondary_serialize[$ss_key];       
                            }
                        }  
                        //
                        $difference[$key] = serialize($primary_serialize); 
                    }    
                }
                //
            }
        } else {
            if (!empty($secondary_data)) {
                foreach ($secondary_data as $key => $data) {
                    $difference[$key] = $data;
                }
            }
        }
        //
        return $difference;
    }

    function is_serialized( $data, $strict = true ) {
        // If it isn't a string, it isn't serialized.
        if ( ! is_string( $data ) ) {
            return false;
        }
        $data = trim( $data );
        if ( 'N;' === $data ) {
            return true;
        }
        if ( strlen( $data ) < 4 ) {
            return false;
        }
        if ( ':' !== $data[1] ) {
            return false;
        }
        if ( $strict ) {
            $lastc = substr( $data, -1 );
            if ( ';' !== $lastc && '}' !== $lastc ) {
                return false;
            }
        } else {
            $semicolon = strpos( $data, ';' );
            $brace     = strpos( $data, '}' );
            // Either ; or } must exist.
            if ( false === $semicolon && false === $brace ) {
                return false;
            }
            // But neither must be in the first X characters.
            if ( false !== $semicolon && $semicolon < 3 ) {
                return false;
            }
            if ( false !== $brace && $brace < 4 ) {
                return false;
            }
        }
        $token = $data[0];
        switch ( $token ) {
            case 's':
                if ( $strict ) {
                    if ( '"' !== substr( $data, -2, 1 ) ) {
                        return false;
                    }
                } elseif ( false === strpos( $data, '"' ) ) {
                    return false;
                }
                // Or else fall through.
            case 'a':
            case 'O':
                return (bool) preg_match( "/^{$token}:[0-9]+:/s", $data );
            case 'b':
            case 'i':
            case 'd':
                $end = $strict ? '$' : '';
                return (bool) preg_match( "/^{$token}:[0-9.E+-]+;$end/", $data );
        }
        return false;
    } 

}

<?php

class Documents_model extends CI_Model
{

    function __construct()
    {
        parent::__construct();
    }

    function get_all_companies_and_documents($company_sid = 0, $company_name = 'all')
    {
        $companies = array();
        if ($company_sid == 0) {
            $this->db->select('sid, CompanyName, active');
            // $this->db->where('active', 1);
            $this->db->where('parent_sid', 0);
            $this->db->where('career_page_type', 'standard_career_site');
            if (!empty($company_name) && $company_name != 'all') {
                $this->db->like('CompanyName', $company_name);
            }
            $this->db->order_by('sid', 'DESC');
            $companies = $this->db->get('users')->result_array();
        } else {
            $this->db->select('sid, CompanyName, active');
            $this->db->where('active', 1);
            $this->db->where('parent_sid', 0);
            $this->db->where('career_page_type', 'standard_career_site');
            $this->db->where('sid', $company_sid);
            if (!empty($company_name) && $company_name != 'all') {
                $this->db->like('CompanyName', $company_name);
            }
            $this->db->order_by('sid', 'DESC');
            $companies = $this->db->get('users')->result_array();
        }

        if (!empty($companies)) {
            foreach ($companies as $key => $company) {
                $company_sid = $company['sid'];

                //Get Credit Card Authorization Form
                $this->db->select('*');
                $this->db->limit(1);
                $this->db->order_by('sid', 'DESC');
                $this->db->where('company_sid', $company_sid);
                $cc_auth = $this->db->get('form_document_credit_card_authorization')->result_array();

                if (!empty($cc_auth)) {
                    $companies[$key]['cc_auth'] = $cc_auth[0];
                } else {
                    $companies[$key]['cc_auth'] = array();
                }

                //Get payroll Credit Card Authorization Form
                $this->db->select('*');
                $this->db->limit(1);
                $this->db->order_by('sid', 'DESC');
                $this->db->where('company_sid', $company_sid);
                $payroll_cc_auth = $this->db->get('form_document_payroll_credit_card_authorization')->result_array();

                if (!empty($payroll_cc_auth)) {
                    $companies[$key]['payroll_cc_auth'] = $payroll_cc_auth[0];
                } else {
                    $companies[$key]['payroll_cc_auth'] = array();
                }

                //Get End User License Agreement Form
                $this->db->select('*');
                $this->db->limit(1);
                $this->db->order_by('sid', 'DESC');
                $this->db->where('company_sid', $company_sid);
                $eula = $this->db->get('form_document_eula')->result_array();

                if (!empty($eula)) {
                    $companies[$key]['eula'] = $eula[0];
                } else {
                    $companies[$key]['eula'] = array();
                }



                //Get Payroll Agreement Form
                $this->db->select('*');
                $this->db->limit(1);
                $this->db->order_by('sid', 'DESC');
                $this->db->where('company_sid', $company_sid);
                $eula = $this->db->get('form_payroll_agreement')->result_array();

                if (!empty($eula)) {
                    $companies[$key]['fpa'] = $eula[0];
                } else {
                    $companies[$key]['fpa'] = array();
                }



                //Get Company Contacts Form
                $this->db->select('*');
                $this->db->limit(1);
                $this->db->order_by('sid', 'DESC');
                $this->db->where('company_sid', $company_sid);
                $eula = $this->db->get('form_document_company_contacts')->result_array();

                if (!empty($eula)) {
                    $companies[$key]['contacts'] = $eula[0];

                    $this->db->select('*');
                    $this->db->where('company_sid', $company_sid);
                    $contact_details = $this->db->get('form_document_company_contact_details')->result_array();

                    $companies[$key]['contacts']['contact_details'] = $contact_details;
                } else {
                    $companies[$key]['contacts'] = array();
                }

                //Uploaded Documents
                $uploaded_documents = $this->get_all_forms_documents_uploaded($company_sid);
                if (!empty($uploaded_documents)) {
                    $companies[$key]['uploaded_documents'] = $uploaded_documents;
                } else {
                    $companies[$key]['uploaded_documents'] = array();
                }


                //Admin User
                $this->db->select('sid, first_name, last_name, email, alternative_email');
                $this->db->where('is_primary_admin', 1);
                $this->db->where('parent_sid', $company_sid);
                $this->db->order_by(SORT_COLUMN, SORT_ORDER);
                $admin = $this->db->get('users')->result_array();

                if (!empty($admin)) {
                    $companies[$key]['administrator'] = $admin[0];
                } else {
                    $companies[$key]['administrator'] = array();
                }
            }
        }


        return $companies;
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

            $documentCreditCard = $this->db
                ->from('form_document_credit_card_authorization')
                ->where('company_sid', $company_sid)
                ->count_all_results();
            if ($documentCreditCard > 0) {
                return;
            }

            $this->db->insert('form_document_credit_card_authorization', $dataToSave);
        } elseif (strtolower($document) == 'company_contacts') {
            $this->db->insert('form_document_company_contacts', $dataToSave);
        } elseif (strtolower($document) == 'uploaded_document') {
            $this->db->insert('forms_documents_uploaded', $dataToSave);
        } elseif (strtolower($document) == 'payroll_agreement') {
            $this->db->insert('form_payroll_agreement', $dataToSave);
        } elseif (strtolower($document) == 'payroll_credit_card_authorization_form') {
            $this->db->insert('form_document_payroll_credit_card_authorization', $dataToSave);
        }

        $this->insert_document_ip_tracking_record($company_sid, 0, getUserIP(), $document, 'new_generated', $_SERVER['HTTP_USER_AGENT']);
    }

    function insert_affiliate_document_record($marketing_agency_sid, $verification_key, $status)
    {
        $dataToSave = array();
        $dataToSave['marketing_agency_sid'] = $marketing_agency_sid;
        $dataToSave['verification_key'] = $verification_key;
        $dataToSave['status'] = $status;
        $dataToSave['status_date'] = date('Y-m-d H:i:s');
        $this->db->insert('form_affiliate_end_user_license_agreement', $dataToSave);

        $this->insert_document_ip_tracking_record($marketing_agency_sid, 0, getUserIP(), 'form_affiliate_end_user_license_agreement', 'new_generated', $_SERVER['HTTP_USER_AGENT']);
    }

    function update_document_record($document, $verification_key, $fields_data = array(), $status = 'sent')
    {
        $this->db->where('verification_key', $verification_key);

        $dataToSave = array();
        $dataToSave['status_date'] = date('Y-m-d H:i:s');
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
                $this->db->update('forms_documents_uploaded', $dataToSave);
            } elseif (strtolower($document) == 'form_payroll_agreement') {
                $this->db->update('form_payroll_agreement', $dataToSave);
            } elseif (strtolower($document) == 'payroll_credit_card_authorization_form') {
                $this->db->update('form_document_payroll_credit_card_authorization', $dataToSave);
            }
        }
    }

    function get_document_record($document, $verification_key)
    {
        $document_data = array();

        if ($document == 'end_user_license_agreement') {

            $this->db->select('form_document_eula.*');
            $this->db->select('users.CompanyName, users.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('form_document_eula.verification_key', $verification_key);
            //$this->db->where('form_document_eula.status', 'sent');

            $this->db->join('users', 'users.sid = form_document_eula.company_sid', 'left');

            $document_data = $this->db->get('form_document_eula')->result_array();
        } elseif ($document == 'form_affiliate_end_user_license_agreement') {

            $this->db->select('form_affiliate_end_user_license_agreement.*');
            $this->db->select('marketing_agencies.full_name, marketing_agencies.email, marketing_agencies.sid as marketing_agencies_sid');
            $this->db->limit(1);

            $this->db->where('form_affiliate_end_user_license_agreement.verification_key', $verification_key);
            $this->db->join('marketing_agencies', 'marketing_agencies.sid = form_affiliate_end_user_license_agreement.marketing_agency_sid', 'left');

            $document_data = $this->db->get('form_affiliate_end_user_license_agreement')->result_array();
        } elseif ($document == 'credit_card_authorization_form') {
            $this->db->select('form_document_credit_card_authorization.*');
            $this->db->select('users.CompanyName, users.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('form_document_credit_card_authorization.verification_key', $verification_key);
            //$this->db->where('form_document_eula.status', 'sent');

            $this->db->join('users', 'users.sid = form_document_credit_card_authorization.company_sid', 'left');

            $document_data = $this->db->get('form_document_credit_card_authorization')->result_array();
        } elseif ($document == 'payroll_credit_card_authorization_form') {
            $this->db->select('form_document_payroll_credit_card_authorization.*');
            $this->db->select('users.CompanyName, users.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('form_document_payroll_credit_card_authorization.verification_key', $verification_key);
            //$this->db->where('form_document_eula.status', 'sent');

            $this->db->join('users', 'users.sid = form_document_payroll_credit_card_authorization.company_sid', 'left');

            $document_data = $this->db->get('form_document_payroll_credit_card_authorization')->result_array();
        } elseif ($document == 'company_contacts') {
            $this->db->select('form_document_company_contacts.*');
            $this->db->select('users.CompanyName, users.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('form_document_company_contacts.verification_key', $verification_key);
            //$this->db->where('form_document_eula.status', 'sent');

            $this->db->join('users', 'users.sid = form_document_company_contacts.company_sid', 'left');

            $document_data = $this->db->get('form_document_company_contacts')->result_array();


            $staff_members = array();
            $main_contact = array();
            $it_person = array();
            $creative_person = array();
            if (!empty($document_data)) {
                $document_sid = $document_data[0]['sid'];

                //Get Staff Members
                $this->db->where('form_document_company_contacts_sid', $document_sid);
                $this->db->where('contact_type', 'staff_member');
                $staff_members = $this->db->get('form_document_company_contact_details')->result_array();

                if (!empty($staff_members)) {
                    $document_data[0]['staff_members'] = $staff_members;
                } else {
                    $document_data[0]['staff_members'] = $staff_members;
                }


                //Get Main Contact
                $this->db->where('form_document_company_contacts_sid', $document_sid);
                $this->db->where('contact_type', 'main');
                $main_contact = $this->db->get('form_document_company_contact_details')->result_array();

                if (!empty($main_contact)) {
                    $document_data[0]['main_contact'] = $main_contact[0];
                } else {
                    $document_data[0]['main_contact'] = $main_contact;
                }


                //Get IT person
                $this->db->where('form_document_company_contacts_sid', $document_sid);
                $this->db->where('contact_type', 'it_person');
                $it_person = $this->db->get('form_document_company_contact_details')->result_array();

                if (!empty($it_person)) {
                    $document_data[0]['it_person'] = $it_person[0];
                } else {
                    $document_data[0]['it_person'] = $it_person;
                }


                //Get Creative Person
                $this->db->where('form_document_company_contacts_sid', $document_sid);
                $this->db->where('contact_type', 'creative_person');
                $creative_person = $this->db->get('form_document_company_contact_details')->result_array();

                if (!empty($it_person)) {
                    $document_data[0]['creative_person'] = $creative_person[0];
                } else {
                    $document_data[0]['creative_person'] = $creative_person;
                }
            }
        } elseif ($document == 'uploaded_document') {
            $this->db->select('forms_documents_uploaded.*');
            $this->db->select('users.CompanyName, users.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('forms_documents_uploaded.verification_key', $verification_key);
            //$this->db->where('form_document_eula.status', 'sent');

            $this->db->join('users', 'users.sid = forms_documents_uploaded.company_sid', 'left');

            $document_data = $this->db->get('forms_documents_uploaded')->result_array();
        } elseif ($document == 'form_payroll_agreement') {

            $this->db->select('form_payroll_agreement.*');
            $this->db->select('users.CompanyName, users.sid as users_company_sid');
            $this->db->limit(1);

            $this->db->where('form_payroll_agreement.verification_key', $verification_key);
            //$this->db->where('form_document_eula.status', 'sent');

            $this->db->join('users', 'users.sid = form_payroll_agreement.company_sid', 'left');

            $document_data = $this->db->get('form_payroll_agreement')->result_array();
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
        } elseif ($target == 'form_payroll_agreement') {
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
            } elseif (strtolower($document) == 'end_user_license_agreement') {
                $this->db->update('form_document_eula', $dataToSave);
            } elseif (strtolower($document) == 'company_contacts') {
                $this->db->update('form_document_company_contacts', $dataToSave);
            } elseif (strtolower($document) == 'uploaded_document') {
                $this->db->update('forms_documents_uploaded', $dataToSave);
            } elseif (strtolower($document) == 'form_payroll_agreement') {
                $this->db->update('form_payroll_agreement', $dataToSave);
            } elseif (strtolower($document) == 'payroll_form_credit_card_authorization') {
                $this->db->update('form_document_payroll_credit_card_authorization', $dataToSave);
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
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
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
        } elseif (strtolower($document) == 'form_payroll_agreement') {
            $data = $this->db->get('form_payroll_agreement')->result_array();
        } elseif (strtolower($document) == 'payroll_form_credit_card_authorization') {
            $data = $this->db->get('form_document_payroll_credit_card_authorization')->result_array();
        }

        if (!empty($data)) {
            return $data[0]['sid'];
        } else {
            return 0;
        }
    }

    function get_all_forms_documents_uploaded($company_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('forms_documents_uploaded')->result_array();
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

    function insert_document_ip_tracking_record(
        $company_sid,
        $logged_in_sid,
        $ip_address,
        $document,
        $document_status,
        $user_agent,
        $user_sid = null,
        $user_type = null
    ) {
        $data_to_insert = array();
        $data_to_insert['company_sid'] = $company_sid;
        $data_to_insert['logged_in_sid'] = $logged_in_sid;
        $data_to_insert['ip_address'] = $ip_address;
        $data_to_insert['document'] = $document;
        $data_to_insert['document_status'] = $document_status;
        $data_to_insert['user_agent'] = $user_agent;

        if ($user_sid != null) {
            $data_to_insert['user_sid'] = $user_sid;
        }

        if ($user_type != null) {
            $data_to_insert['user_type'] = $user_type;
        }

        $this->db->insert('documents_ip_tracking', $data_to_insert);
    }

    function get_document_ip_tracking_record($company_sid, $document, $status = 'signed', $user_sid = null, $user_type = null)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('document', $document);
        $this->db->where('document_status', $status);

        if ($user_sid != null && $user_type != null) {
            $this->db->where('user_sid', $user_sid);
            $this->db->where('user_type', $user_type);
        }

        $this->db->order_by('sid', 'DESC');
        $this->db->limit(1);

        $records_obj = $this->db->get('documents_ip_tracking');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function update_commission_info($record_sid, $marketing_agency_sid, $value, $type)
    {

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

    function get_applicant_signature($company_sid, $user_sid)
    {
        $this->db->select('signature_bas64_image, signature_timestamp');
        $this->db->where('sid', $user_sid);
        $this->db->where('employer_sid', $company_sid);

        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_employee_signature($sid)
    {
        $this->db->select('signature_bas64_image, signature_timestamp');
        $this->db->where('sid', $sid);
        $records_obj = $this->db->get('e_signatures_data');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_credit_card_information($verification_key)
    {
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);
        $records_obj = $this->db->get('form_document_credit_card_authorization');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }


    function get_payroll_credit_card_information($verification_key)
    {
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);
        $records_obj = $this->db->get('form_document_payroll_credit_card_authorization');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }


    function insert_payroll_credit_card_authorization_history($data_to_insert)
    {
        $this->db->insert('form_document_payroll_credit_card_authorization_history', $data_to_insert);
    }

    function regenerate_payroll_credit_card_authorization($record_sid, $data_to_update)
    {
        $this->db->where('sid', $record_sid);
        $this->db->update('form_document_payroll_credit_card_authorization', $data_to_update);
    }

    function insert_credit_card_authorization_history($data_to_insert)
    {
        $this->db->insert('form_document_credit_card_authorization_history', $data_to_insert);
    }

    function regenerate_credit_card_authorization($record_sid, $data_to_update)
    {
        $this->db->where('sid', $record_sid);
        $this->db->update('form_document_credit_card_authorization', $data_to_update);
    }

    function get_enduser_license_agreement_information($verification_key)
    {
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);
        $records_obj = $this->db->get('form_document_eula');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function insert_enduser_license_agreement_history($data_to_insert)
    {
        $this->db->insert('form_document_eula_history', $data_to_insert);
    }

    function regenerate_enduser_license_agreement($record_sid, $data_to_update)
    {
        $this->db->where('sid', $record_sid);
        $this->db->update('form_document_eula', $data_to_update);
    }

    function get_company_contacts_information($verification_key)
    {
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);
        $records_obj = $this->db->get('form_document_company_contacts');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    function get_company_contacts_details_information($company_sid, $record_sid)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('form_document_company_contacts_sid', $record_sid);
        $records_obj = $this->db->get('form_document_company_contact_details');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }

    function insert_company_contacts_history($data_to_insert)
    {
        $this->db->insert('form_document_company_contacts_history', $data_to_insert);
    }

    function regenerate_company_contacts_document($record_sid, $data_to_update)
    {
        $this->db->where('sid', $record_sid);
        $this->db->update('form_document_company_contacts', $data_to_update);
    }

    function insert_company_contacts_detail_history($data_to_insert)
    {
        $this->db->insert('form_document_company_contact_details_history', $data_to_insert);
    }

    function delete_company_contacts_detail($record_sid)
    {
        $this->db->where('sid', $record_sid);
        $this->db->delete('form_document_company_contact_details');
    }

    function get_all_default_categories()
    {
        $this->db->select('sid, name, status, sort_order, created_date');
        $this->db->where('company_sid', 0);
        $this->db->where('created_by_sid', 0);
        $records_obj = $this->db->get('documents_category_management');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr;
        } else {
            return array();
        }
    }


    function check_unique_with_name($company_sid, $name, $sid = null)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('name', $name);
        //
        if ($sid != null) {
            $this->db->where('sid <>', $sid);
        }
        //
        $result = $this->db->get('documents_category_management')->num_rows();
        //
        return $result;
    }

    function add_category($data)
    {
        $this->db->insert('documents_category_management', $data);
        return $this->db->insert_id();
    }

    function get_category($sid)
    {
        $this->db->select('sid, name, status, sort_order, description');
        $this->db->where('sid', $sid);
        $category = $this->db->get('documents_category_management')->row_array();
        //
        if (!empty($category)) {
            return $category;
        } else {
            return array();
        }
    }

    function update_category($sid, $data)
    {
        $this->db->where('sid', $sid);
        $category = $this->db->update('documents_category_management', $data);
        return $category;
    }

    //
    function get_enduser_payroll_agreement_information($verification_key)
    {
        $this->db->select('*');
        $this->db->where('verification_key', $verification_key);
        $records_obj = $this->db->get('form_payroll_agreement');

        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($records_arr)) {
            return $records_arr[0];
        } else {
            return array();
        }
    }

    //
    function insert_enduser_payroll_agreement_history($data_to_insert)
    {
        $this->db->insert('form_document_fpa_history', $data_to_insert);
    }

    //
    function regenerate_enduser_payroll_agreement($record_sid, $data_to_update)
    {
        $this->db->where('sid', $record_sid);
        $this->db->update('form_payroll_agreement', $data_to_update);
    }
}

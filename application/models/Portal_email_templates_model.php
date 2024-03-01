<?php
class portal_email_templates_model extends CI_Model
{

    function __construct()
    { // Call the Model constructor
        parent::__construct();
    }

    function getallemailtemplates($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 1);
        //$this->db->order_by('template_name', 'ASC');
        $this->db->order_by('is_custom', 'ASC');
        $this->db->order_by('template_name', 'ASC');
        return $this->db->get('portal_email_templates')->result_array();
    }

    function gettemplatedetails($sid, $company_sid)
    {
        $this->db->where('status', 1);
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('portal_email_templates')->result_array();
    }

    function getSingleTemplateDetails($sid)
    {
        $this->db->where('status', 1);
        $this->db->where('sid', $sid);
        $data = $this->db->get('portal_email_templates')->result_array();

        if (!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }

    function check_whether_table_exists($template_code, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('template_code', $template_code);
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('portal_email_templates')->result_array();
    }

    function check_admin_template_exists($admin_template_sid, $company_sid)
    {
        $this->db->select('sid');
        $this->db->where('admin_template_sid', $admin_template_sid);
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('portal_email_templates')->result_array();
    }

    function get_admin_template_by_id($sid)
    {
        $this->db->select('sid, name, from_name, from_email, subject, text');
        $this->db->where('sid', $sid);
        $record_obj = $this->db->get('email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function update_email_template($data, $sid)
    {
        $this->db->where('sid', $sid);
        $this->db->update('portal_email_templates', $data);
    }

    function check_default_tables($company_sid, $company_email, $company_name)
    {
        // updated on: 2019-04-19
        // Get template_codes from 
        // employee email templates
        $where_clause = $this->db
            ->select('template_code')
            ->from('portal_email_templates')
            ->where('is_custom', 0)
            ->where('company_sid', $company_sid)
            ->get_compiled_select();
        // Get diff templates 
        $result = $this->db
            ->select('template_code, name as template_name, subject, text as body, from_name, from_email, file as attachment')
            ->from('email_templates')
            ->where('group', 'portal_email_templates')
            ->where('subject <> ', null)
            ->group_start()
            ->where('text <> ', null)
            ->where('text <>', '')
            ->group_end()
            ->where('status', 1)
            ->where('template_code NOT IN (' . $where_clause . ')', null)
            ->get();
        // Store to a local variable
        $templates = $result->result_array();
        // Free results from memory
        $result = $result->free_result();
        //
        
        // If no difference found 
        // return false
        if (sizeof($templates)) {
            // Set created date
            // so don't need to call date event
            $date = date('Y-m-d H:i:s');
            // Loop through templates
            foreach ($templates as $k0 => $v0) {
                // Add templates to employee
                // email templates
                $this->db->insert(
                    "portal_email_templates",
                    array(
                        'template_code' => $v0['template_code'],
                        'template_name' => $v0['template_name'],
                        'subject'       => $v0['subject'],
                        'message_body'  => $v0['body'],
                        'from_name'     => $v0['from_name'] == '' || $v0['from_name'] == null ? '{{company_name}}' : $v0['from_name'],
                        'from_email'    => $v0['from_email'] == '' || $v0['from_email'] == null ? FROM_EMAIL_INFO : $v0['from_email'],
                        'enable_auto_responder' => 1,
                        'company_sid'           => $company_sid,
                        'created'               => $date
                    )
                );
                //
                if ($v0['attachment'] == '' || $v0['attachment'] == NULL) continue;
                //
                $insert_id = $this->db->insert_id();
                // TODO
                // Upload file to AWS
                $this->db->insert('portal_email_templates_attachments', array(
                    'portal_email_template_sid' => $insert_id,
                    'company_sid' => $company_sid,
                    'employer_sid' => $insert_id,
                    'attachment_aws_file' => $v0['attachment'],
                    'created_date' => $date,
                    'original_file_name' => $v0['attachment']
                ));
            }
        } 

        // Add Admin Templates to client end *** Start ***
        $hr_documents_notification = $this->check_admin_template_exists(HR_DOCUMENTS_NOTIFICATION, $company_sid);

        if (empty($hr_documents_notification)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(HR_DOCUMENTS_NOTIFICATION);

            if (!empty($admin_template_data)) {
                $data = array();
                $data['template_code']                                          = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $data['company_sid']                                            = $company_sid;
                $data['created']                                                = date('Y-m-d H:i:s');
                $data['template_name']                                          = $admin_template_data['name'];
                $data['from_name']                                              = '{{company_name}}';
                $data['from_email']                                             = $admin_template_data['from_email'];
                $data['subject']                                                = $admin_template_data['subject'];
                $data['message_body']                                           = $admin_template_data['text'];
                $data['admin_template_sid']                                     = $admin_template_data['sid'];
                $this->db->insert("portal_email_templates", $data);
            }
        }

        // Add Admin Templates to client
        $hr_documents_notification = $this->check_admin_template_exists(HR_DOCUMENTS_NOTIFICATION_EMS, $company_sid);

        if (empty($hr_documents_notification)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(HR_DOCUMENTS_NOTIFICATION_EMS);

            if (!empty($admin_template_data)) {
                $data = array();
                $data['template_code']                                          = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $data['company_sid']                                            = $company_sid;
                $data['created']                                                = date('Y-m-d H:i:s');
                $data['template_name']                                          = $admin_template_data['name'];
                $data['from_name']                                              = '{{company_name}}';
                $data['from_email']                                             = $admin_template_data['from_email'];
                $data['subject']                                                = $admin_template_data['subject'];
                $data['message_body']                                           = $admin_template_data['text'];
                $data['admin_template_sid']                                     = $admin_template_data['sid'];
                $this->db->insert("portal_email_templates", $data);
            }
        }


        $hr_documents_notification = $this->check_admin_template_exists(ON_BOARDING_REQUEST, $company_sid);

        if (empty($hr_documents_notification)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(ON_BOARDING_REQUEST);

            if (!empty($admin_template_data)) {
                $template_code = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $template_code = str_replace('-', '_', $template_code);

                $data_to_insert = array();
                $data_to_insert['template_code'] = $template_code;
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['created'] = date('Y-m-d H:i:s');
                $data_to_insert['template_name'] = $admin_template_data['name'];
                $data_to_insert['from_name'] = '{{company_name}}';
                $data_to_insert['from_email'] = $admin_template_data['from_email'];
                $data_to_insert['subject'] = $admin_template_data['subject'];
                $data_to_insert['message_body'] = $admin_template_data['text'];
                $data_to_insert['admin_template_sid'] = $admin_template_data['sid'];
                $data_to_insert['enable_auto_responder'] = 0;
                $this->db->insert("portal_email_templates", $data_to_insert);
            }
        }

        // Add Admin Templates to client end *** Start ***
        $document_notification_template = $this->check_admin_template_exists(DOCUMENT_NOTIFICATION_TEMPLATE, $company_sid);

        if (empty($document_notification_template)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(DOCUMENT_NOTIFICATION_TEMPLATE);

            if (!empty($admin_template_data)) {
                $data = array();
                $data['template_code']                                          = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $data['company_sid']                                            = $company_sid;
                $data['created']                                                = date('Y-m-d H:i:s');
                $data['template_name']                                          = $admin_template_data['name'];
                $data['from_name']                                              = '{{company_name}}';
                $data['from_email']                                             = $admin_template_data['from_email'];
                $data['subject']                                                = $admin_template_data['subject'];
                $data['message_body']                                           = $admin_template_data['text'];
                $data['admin_template_sid']                                     = $admin_template_data['sid'];
                $this->db->insert("portal_email_templates", $data);
            }
        }

        // Add Admin Templates to client end *** Start ***
        $dnat = $this->check_admin_template_exists(DOCUMENT_NOTIFICATION_ACTION_TEMPLATE, $company_sid);

        if (empty($dnat)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(DOCUMENT_NOTIFICATION_ACTION_TEMPLATE);

            if (!empty($admin_template_data)) {
                $data = array();
                $data['template_code']                                          = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $data['company_sid']                                            = $company_sid;
                $data['created']                                                = date('Y-m-d H:i:s');
                $data['template_name']                                          = $admin_template_data['name'];
                $data['from_name']                                              = '{{company_name}}';
                $data['from_email']                                             = $admin_template_data['from_email'];
                $data['subject']                                                = $admin_template_data['subject'];
                $data['message_body']                                           = $admin_template_data['text'];
                $data['admin_template_sid']                                     = $admin_template_data['sid'];
                $this->db->insert("portal_email_templates", $data);
            }
        }


        // Add Admin Templates to client end *** Start ***
        $dnaat = $this->check_admin_template_exists(DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE, $company_sid);

        if (empty($dnaat)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE);

            if (!empty($admin_template_data)) {
                $data = array();
                $data['template_code']                                          = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $data['company_sid']                                            = $company_sid;
                $data['created']                                                = date('Y-m-d H:i:s');
                $data['template_name']                                          = $admin_template_data['name'];
                $data['from_name']                                              = '{{company_name}}';
                $data['from_email']                                             = $admin_template_data['from_email'];
                $data['subject']                                                = $admin_template_data['subject'];
                $data['message_body']                                           = $admin_template_data['text'];
                $data['admin_template_sid']                                     = $admin_template_data['sid'];
                $this->db->insert("portal_email_templates", $data);
            }
        }
        // Add Admin Templates to Client end ***  End  ***


        // Add Admin Templates to client end *** Start ***
        $aysi = $this->check_admin_template_exists(ARE_YOU_STILL_INTERESTED, $company_sid);

        if (empty($aysi)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(ARE_YOU_STILL_INTERESTED);

            if (!empty($admin_template_data)) {
                $data = array();
                $data['template_code']                                          = str_replace(' ', '_', strtolower($admin_template_data['name']));
                $data['company_sid']                                            = $company_sid;
                $data['created']                                                = date('Y-m-d H:i:s');
                $data['template_name']                                          = $admin_template_data['name'];
                $data['from_name']                                              = '{{company_name}}';
                $data['from_email']                                             = $admin_template_data['from_email'];
                $data['subject']                                                = $admin_template_data['subject'];
                $data['message_body']                                           = $admin_template_data['text'];
                $data['enable_auto_responder']                                  = 0;
                $data['admin_template_sid']                                     = $admin_template_data['sid'];
                $this->db->insert("portal_email_templates", $data);
            }
        }
        // Add Admin Templates to Client end ***  End  ***

    }

    function getCompanyName($company_sid)
    {
        $this->db->select('CompanyName');
        $this->db->where('sid', $company_sid);
        $rows = $this->db->get('users')->result_array();

        if (!empty($rows)) {
            return $rows[0]['CompanyName'];
        } else {
            return '';
        }
    }

    function getCompanyPrimaryAdminEmail($company_sid)
    {
        $this->db->select('email');
        $this->db->where('parent_sid', $company_sid);
        $this->db->where('is_primary_admin', 1);
        $rows = $this->db->get('users')->result_array();

        if (!empty($rows)) {
            return $rows[0]['email'];
        } else {
            return '';
        }
    }

    function getemployeeemail($sid)
    {
        $this->db->select('email');
        $this->db->where('sid', $sid);

        $record_obj = $this->db->get('users');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0]['email'];
        } else {
            return 'not_found';
        }
    }

    function insert_portal_email_template($data)
    {
        $this->db->insert('portal_email_templates', $data);
        return $this->db->insert_id();
    }

    function get_portal_email_template_by_code($template_code, $company_sid)
    {
        $this->db->select('*');
        $this->db->where('template_code', $template_code);
        $this->db->where('company_sid', $company_sid);
        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function get_portal_email_template_by_Id($templateId)
    {
        $this->db->select('*');
        $this->db->where('sid', $templateId);
        $record_obj = $this->db->get('portal_email_templates');
        $record_arr = $record_obj->result_array();
        $record_obj->free_result();

        if (!empty($record_arr)) {
            return $record_arr[0];
        } else {
            return array();
        }
    }

    function insert_email_template_attachment_record($data_to_insert)
    {
        $this->db->insert('portal_email_templates_attachments', $data_to_insert);
    }

    function get_all_email_template_attachments($template_sid)
    {
        $this->db->where('portal_email_template_sid', $template_sid);
        $records_obj = $this->db->get('portal_email_templates_attachments');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();

        return $records_arr;
    }

    function delete_attachment($attachment_sid)
    {
        $this->db->where('sid', $attachment_sid);
        $this->db->delete('portal_email_templates_attachments');
    }

    function get_applicant_data($applicant_sid, $company_sid)
    {
        // Updated on: 29-04-2019
        $this->db->select('
            portal_job_applications.sid, 
            portal_job_applications.email, 
            portal_job_applications.first_name, 
            portal_job_applications.last_name, 
            portal_job_applications.job_sid, 
            portal_job_applications.phone_number, 
            portal_job_applications.city, 
            portal_job_applications.resume, 
            IF(portal_job_listings.Title = "", portal_applicant_jobs_list.desired_job_title, portal_job_listings.Title) as job_title
            ');
        $this->db->where('portal_job_applications.sid', $applicant_sid);
        $this->db->where('portal_job_applications.employer_sid', $company_sid);
        // Added on: 29-04-2019
        $this->db->join('portal_applicant_jobs_list', 'portal_applicant_jobs_list.portal_job_applications_sid  = portal_job_applications.sid', 'left');
        $this->db->join('portal_job_listings', 'portal_job_listings.sid = portal_applicant_jobs_list.job_sid', 'left');
        $records_obj = $this->db->get('portal_job_applications');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $result = array();

        if (!empty($records_arr)) {
            $result = $records_arr[0];
        }

        return $result;
    }

    public function save_message($product_data)
    {
        $product_data['outbox'] = 0;
        $this->db->insert('private_message', $product_data);
        $product_data['outbox'] = 1;
        $this->db->insert('private_message', $product_data);
        return 'Message Saved Successfully';
    }

    public function delete_custom_email_template($id)
    {
        $this->db->where('sid', $id);
        $this->db->delete('portal_email_templates');

        $this->db->where('portal_email_template_sid', $id);
        $this->db->delete('portal_email_templates_attachments');
    }

    public function get_company_custom_template_names($company_id, $sid = 0)
    {
        $this->db->select('template_name');
        $this->db->where('company_sid', $company_id);
        $this->db->where('is_custom', 1);
        if ($sid) {
            $this->db->where('sid <>', $sid);
        }
        $result = $this->db->get('portal_email_templates')->result_array();
        return $result;
    }



    function get_employee_data($employee_sid, $company_sid)
    {
        $this->db->select('sid,email, first_name, last_name, job_title');
        $this->db->where('sid', $employee_sid);
        $this->db->where('parent_sid', $company_sid);
        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        $result = array();

        if (!empty($records_arr)) {
            $result = $records_arr[0];
        }

        return $result;
    }
}

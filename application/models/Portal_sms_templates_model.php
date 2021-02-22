<?php
class portal_sms_templates_model extends CI_Model {

    function __construct() { // Call the Model constructor
        parent::__construct();
    }

    function getallsmstemplates($company_sid) {
        $this->db->where('company_sid', $company_sid);
        $this->db->order_by('is_custom', 'ASC');
        return $this->db->get('portal_sms_templates')->result_array();
    }

    function getTemplateDetails($sid, $company_sid) {
        $this->db->where('status', 1);
        $this->db->where('sid', $sid);
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('portal_sms_templates')->result_array();
    }

    function getSingleTemplateDetails($sid) {
        $this->db->where('status', 1);
        $this->db->where('sid', $sid);
        $data = $this->db->get('portal_sms_templates')->result_array();
        
        if(!empty($data)) {
            return $data[0];
        } else {
            return array();
        }
    }
    
    function check_whether_table_exists($template_code, $company_sid) {
        $this->db->select('sid');
        $this->db->where('template_code', $template_code);
        $this->db->where('company_sid', $company_sid);
        return $this->db->get('portal_sms_templates')->result_array();
    }

    function update_sms_template($data, $sid) {
        $this->db->where('sid', $sid);
        $this->db->update('portal_sms_templates', $data);
    }
    
    function check_default_tables($company_sid, $company_email, $company_name) {
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
        ->where('template_code NOT IN ('.$where_clause.')', null)
        ->get();
        // Store to a local variable
        $templates = $result->result_array();
        // Free results from memory
        $result = $result->free_result();
        // If no difference found 
        // return false
        if(sizeof($templates)){
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
                if($v0['attachment'] == '' || $v0['attachment'] == NULL) continue;
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
        
        if(empty($hr_documents_notification)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(HR_DOCUMENTS_NOTIFICATION);
            
            if(!empty($admin_template_data)) {
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
        if(empty($hr_documents_notification)) { // the template does not exists for following company, Please add it
            $admin_template_data = $this->get_admin_template_by_id(HR_DOCUMENTS_NOTIFICATION_EMS);
            
            if(!empty($admin_template_data)) {
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
        // Add Admin Templates to Client end ***  End  ***
    }

    function insert_portal_sms_template($data) {
        $this->db->insert('portal_sms_templates', $data);
        return $this->db->insert_id();
    }

    public function delete_custom_email_template($id){
        $this->db->where('sid',$id);
        $this->db->delete('portal_sms_templates');
    }

    public function get_company_custom_template_names($company_id,$sid = 0){
        $this->db->select('template_name');
        $this->db->where('company_sid',$company_id);
        if($sid){
            $this->db->where('sid <>',$sid);
        }
        $result = $this->db->get('portal_sms_templates')->result_array();
        return $result;
    }

    public function check_default_templates($company_id){
        $this->db->from('portal_sms_templates');
        $this->db->where('company_sid',$company_id);
        $result = $this->db->count_all_results();
        if($result == 0){
            $this->db->where('company_sid',0);
            $result = $this->db->get('portal_sms_templates')->result_array();
            foreach($result as $temp){
                unset($temp['sid']);
                $temp['company_sid'] = $company_id;
                $this->db->insert('portal_sms_templates',$temp);
            }
        }
    }

}
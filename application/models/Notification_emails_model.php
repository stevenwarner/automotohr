<?php class notification_emails_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_notification_emails($company_id, $notifications_type, $status = NULL)
    {
        $this->db->select('
            notifications_emails_management.*,
            users.access_level,
            users.access_level_plus,
            users.pay_plan_flag,
            users.job_title,
            users.is_executive_admin
        ');
        $this->db->join("users", "notifications_emails_management.employer_sid=users.sid", 'left');
        $this->db->where('company_sid', $company_id);
        $this->db->where('notifications_type', $notifications_type);
        if ($status != NULL) {
            $this->db->where('status', $status);
        }
        $this->db->order_by('notifications_emails_management.sid', 'desc');
        $result = $this->db->get('notifications_emails_management')->result_array();
        return $result;
    }

    function get_to_update_notification_emails($company_sid)
    {
        $this->db->select('sid, employer_sid, email');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('employer_sid > ', 0);
        return $this->db->get('notifications_emails_management')->result_array();
    }

    function check_employee_exists($employee_sid, $company_sid, $notifications_type)
    {
        $this->db->select('email');
        $this->db->where('sid', $employee_sid);
        $result = $this->db->get('users')->result_array();

        if (isset($result[0])) {
            $email = $result[0]['email'];
            $this->db->select('sid');
            $this->db->where('company_sid', $company_sid);
            $this->db->where('email', $email);
            $this->db->where('notifications_type', $notifications_type);
            $records = $this->db->get('notifications_emails_management')->num_rows();

            if ($records > 0) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function get_employee_data($employee_sid)
    {
        $this->db->select('sid, PhoneNumber, email, first_name, last_name');
        $this->db->where('sid', $employee_sid);
        $this->db->order_by(SORT_COLUMN, SORT_ORDER);
        return $this->db->get('users')->result_array();
    }

    function save_notification_email($data_to_save)
    {
        if (isset($data_to_save['perform_action'])) {
            unset($data_to_save['perform_action']);
        }

        $this->db->insert('notifications_emails_management', $data_to_save);
        $result = $this->db->affected_rows();

        if ($result == 1) {
            return 'success';
        } else {
            return 'fail';
        }
    }

    function check_unique_email($company_id, $email, $notifications_type)
    {
        $this->db->select('*');
        $this->db->where('company_sid', $company_id);
        $this->db->where('email', $email);
        $this->db->where('notifications_type', $notifications_type);
        $result = $this->db->get('notifications_emails_management')->result_array();
        $result = $this->db->affected_rows();

        if ($result == 1) {
            return 'exists';
        } else {
            return 'not_exists';
        }
    }

    function delete_contact($contact_sid)
    {
        $this->db->where('sid', $contact_sid);
        return $this->db->delete('notifications_emails_management');
    }

    function get_contact_details($contact_sid)
    {

        $this->db->where('sid', $contact_sid);
        $data = $this->db->get('notifications_emails_management')->result_array();
        if (isset($data[0])) {
            return $data[0];
        } else {
            return array();
        }
    }

    function update_contact($contact_sid, $update_data)
    {
        $this->db->where('sid', $contact_sid);
        return $this->db->update('notifications_emails_management', $update_data);
    }

    function get_notifications_status($company_sid, $notifications_type = NULL)
    {
        $result_row = array();

        if ($notifications_type != NULL) {
            $this->db->select($notifications_type);
        } else {
            $this->db->select('*');
        }

        $this->db->where('company_sid', $company_sid);
        $result_row = $this->db->get('notifications_emails_configuration')->result_array();

        if (!empty($result_row)) {
            return $result_row[0];
        } else {
            return array();
        }
    }

    function get_notifications_configuration_record($company_sid)
    {
        $this->db->where('company_sid', $company_sid);
        $row_data = $this->db->get('notifications_emails_configuration')->result_array();

        if (!empty($row_data)) {
            return $row_data[0];
        } else {
            return array();
        }
    }

    function insert_notifications_configuration_record($company_sid)
    {
        $data = array();
        $data['company_sid'] = $company_sid;
        $this->db->insert('notifications_emails_configuration', $data);
    }

    function update_notifications_configuration_record($company_sid, $data)
    {

        $this->db->where('company_sid', $company_sid);
        $this->db->update('notifications_emails_configuration', $data);
    }

    function get_all_employees($company_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->order_by("concat(first_name,' ',last_name)", "ASC", false);
        $this->db->select('email');
        $this->db->select('access_level');
        $this->db->select('access_level_plus');
        $this->db->select('is_executive_admin');
        $this->db->select('pay_plan_flag');
        $this->db->select('job_title');

        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);

        $this->db->order_by('access_level', 'ASC');

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }

    function get_company_sms_status($company_sid)
    {
        $this->db->select('sms_module_status');
        $this->db->where('sid', $company_sid);
        $sms_module = $this->db->get('users')->result_array();
        return $sms_module[0]['sms_module_status'];
    }

    function get_employee_sms_status($employee_sid)
    {
        $this->db->select('notified_by, PhoneNumber');
        $this->db->where('sid', $employee_sid);
        $sms_module = $this->db->get('users')->result_array();
        return $sms_module[0];
    }

    function get_active_default_approver($company_id)
    {
        $this->db->select('default_approvers');
        $this->db->where('company_sid', $company_id);
        $record_obj = $this->db->get('notifications_emails_configuration');
        $configuration_data = $record_obj->row_array();
        $record_obj->free_result();

        //
        if (!empty($configuration_data) && $configuration_data["default_approvers"] == 1) {
            $this->db->select('employer_sid, email');
            $this->db->where('company_sid', $company_id);
            $this->db->where('notifications_type', "default_approvers");
            $this->db->where('status', "active");
            $record_obj = $this->db->get('notifications_emails_management');
            $default_approvers = $record_obj->row_array();
            $record_obj->free_result();
            //
            if (!empty($default_approvers)) {
                return $default_approvers;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }

    function get_all_documents_without_approvers($company_id)
    {
        $this->db->select('sid, approval_flow_sid');
        $this->db->where('company_sid', $company_id);
        $this->db->where('has_approval_flow', 1);
        $this->db->where('status', 1);
        $records_obj = $this->db->get('documents_assigned');
        $approver_documents = $records_obj->result_array();
        $records_obj->free_result();

        if (!empty($approver_documents)) {
            foreach ($approver_documents as $dkey => $document) {
                $this->db->select('sid');
                $this->db->where('portal_document_assign_sid', $document["approval_flow_sid"]);
                $this->db->where('status', 1);
                $records_obj = $this->db->get('portal_document_assign_flow_employees');
                $approvers = $records_obj->result_array();
                $records_obj->free_result();
                //
                if (!empty($approvers)) {
                    unset($approver_documents[$dkey]);
                }
            }
            //
            return $approver_documents;
        } else {
            return array();
        }
    }


    //
    function get_all_employeesNew($company_sid)
    {
        $this->db->select('sid');
        $this->db->select('first_name');
        $this->db->select('last_name');
        $this->db->order_by("concat(first_name,' ',last_name)", "ASC", false);
        $this->db->select('email');
        $this->db->select('access_level');
        $this->db->select('access_level_plus');
        $this->db->select('is_executive_admin');
        $this->db->select('pay_plan_flag');
        $this->db->select('job_title');

        $this->db->where('parent_sid', $company_sid);
        $this->db->where('username !=', '');
        $this->db->where('terminated_status', 0);
        $this->db->where('active', 1);
        $this->db->where('access_level!=', 'Employee');

        $this->db->order_by('access_level', 'ASC');

        $records_obj = $this->db->get('users');
        $records_arr = $records_obj->result_array();
        $records_obj->free_result();
        return $records_arr;
    }
    //

    function setscheduleddocuments($company_sid, $data)
    {

        $this->db->select('sid');
        $this->db->where('company_sid', $company_sid);
        $records = $this->db->get('schedule_document')->num_rows();

        if ($records > 0) {
            $this->db->where('company_sid', $company_sid);
            $this->db->update('schedule_document', $data);
        } else {
            $this->db->insert('schedule_document', $data);
        }
    }

        function getscheduleddocuments($company_sid)
    {

        $this->db->where('company_sid', $company_sid);
        $records = $this->db->get('schedule_document')->row_array();
        return $records;
       
    }
}

<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Document management
 * Session base API's
 * 
 * @author  AutomotoHR
 * @link    www.automotohr.com
 * @author  Mubashar Ahmed <mubashar@automotohr.com>
 * @version 1.0
 */
class Documents_management_model extends CI_Model
{

    public function __construct()
    {
        // inherit
        parent::__construct();
    }

    /**
     * assigns w4 form
     * 
     * @param int    $userId
     * @param string $userType
     * @return array
     */
    public function assignW4Form(int $userId, string $userType): array
    {
        //
        $employerDetail = checkAndGetSession('employer_detail', true);
        //
        $w4_form_history = $this->checkW4FormExists($userId, $userType);
        // actual form
        if (!$w4_form_history) {
            $w4_data_to_insert = [];
            $w4_data_to_insert['employer_sid'] = $userId;
            $w4_data_to_insert['user_type'] = $userType;
            $w4_data_to_insert['company_sid'] = $employerDetail['parent_sid'];
            $w4_data_to_insert['sent_status'] = 1;
            $w4_data_to_insert['sent_date'] = getSystemDate();
            $w4_data_to_insert['status'] = 1;
            // insert
            $this->db->insert('form_w4_original', $w4_data_to_insert);
            // notifications
            if ($userType === 'employee') {
                $this->sendDocumentAssignmentNotifications(
                    $userId,
                    $userType,
                    $employerDetail['parent_sid']
                );
            }
            // get latest w4 id
            $w4_sid = getVerificationDocumentSid($userId, $userType, 'w4');
            // keep track of what happened
            keepTrackVerificationDocument(
                $employerDetail['sid'],
                "employee",
                'assign',
                $w4_sid,
                'w4',
                'Payroll'
            );
        } else {
            $w4_data_to_update = [];
            $w4_data_to_update['sent_date'] = getSystemDate();
            $w4_data_to_update['status'] = 1;
            $w4_data_to_update['signature_timestamp'] = null;
            $w4_data_to_update['signature_email_address'] = null;
            $w4_data_to_update['signature_bas64_image'] = null;
            $w4_data_to_update['init_signature_bas64_image'] = null;
            $w4_data_to_update['ip_address'] = null;
            $w4_data_to_update['user_agent'] = null;
            $w4_data_to_update['uploaded_file'] = null;
            $w4_data_to_update['uploaded_by_sid'] = 0;
            $w4_data_to_update['user_consent'] = 0;
            // update
            // $this->db->where('user_type', $userType)
            //     ->where('employer_sid', $userId)
            //     ->update('form_w4_original', $w4_data_to_update);
        }
        
        //
        return ['success' => true, 'message' => 'You have successfully assigned the W4 form.'];
    }

    /**
     * revoke w4 form
     * 
     * @param int    $userId
     * @param string $userType
     * @return array
     */
    public function revokeW4Form(int $userId, string $userType): array
    {
        //
        $employerDetail = checkAndGetSession('employer_detail', true);
        //
        $w4_form_history = $this->checkW4FormExists($userId, $userType);
        //
        $w4_form_history['form_w4_ref_sid'] = $w4_form_history['sid'];
        //
        unset($w4_form_history['sid']);
        // add to history
        $this->db->insert('form_w4_original_history', $w4_form_history);
        // deactivate all entries
        $this->db->where('user_type', $userType)
            ->where('employer_sid', $userId)
            ->set('status', 0)
            ->from('form_w4_original')
            ->update();
        // get latest w4 id
        $w4_sid = getVerificationDocumentSid($userId, $userType, 'w4');
        // keep track of what happened
        keepTrackVerificationDocument(
            $employerDetail['sid'],
            "employee",
            'revoke',
            $w4_sid,
            'w4',
            'Payroll'
        );
        //
        return ['success' => true, 'message' => 'You have successfully revoked the W4 form.'];
    }

    /**
     * check and sign form
     * 
     * @param string $documentType
     * @param int    $userId
     * @return array
     */
    public function checkAndSignDocument(string $documentType, int $userId): array
    {
        //
        $formName = '';
        //
        if ($documentType === 'direct_deposit') {
            $formName = 'employee_direct_deposit';
        } elseif ($documentType === 'w4') {
            $formName = 'US_W-4';
        }
        //
        if (!$formName) {
            return ['errors' => 'Invalid form name for payroll'];
        }
        // check if form exists
        $form = $this->db
            ->select('gusto_uuid, sid')
            ->where('employee_sid', $userId)
            ->where('form_name', $formName)
            ->get('gusto_employees_forms')
            ->row_array();
        //
        if (!$form) {
            return ['success' => true, 'message' => 'No form attached.'];
        }
        // load payroll model
        $this->load->model('v1/Payroll_model', 'payroll_model');
        //
        return $this->payroll_model->signEmployeeForm($userId, $form['gusto_uuid']);
    }

    /**
     * check if form is already assigned
     * 
     * @param int    $userId
     * @param string $userType
     * @return array
     */
    private function checkW4FormExists(int $userId, string $userType): array
    {
        return
            $this->db
            ->where('user_type', $userType)
            ->where('employer_sid', $userId)
            ->get('form_w4_original')
            ->row_array();
    }

    /**
     * check if form is already assigned
     * 
     * @param int    $userId
     * @param string $userType
     * @param int    $companyId
     * @return bool
     */
    private function sendDocumentAssignmentNotifications(
        int $userId,
        string $userType,
        int $companyId
    ): bool {
        // set columns
        $columns = [
            'first_name',
            'last_name',
            'document_sent_on',
            'email'
        ];
        // set table name
        $table = 'users';
        //
        if ($userType === 'applicant') {
            $table = 'portal_job_application';
        }
        // get user information
        $userInfo = $this->db
            ->select($columns)
            ->where('sid', $userId)
            ->get($table)
            ->row_array();
        // get company name
        $companyName = getCompanyNameBySid($companyId);
        //Send Email and SMS
        $replacement_array = array();
        $replacement_array['contact-name'] = ucwords($userInfo['first_name'] . ' ' . $userInfo['last_name']);
        $replacement_array['company_name'] = ucwords($companyName);
        $replacement_array['firstname'] = $replacement_array['first_name'] = $userInfo['first_name'];
        $replacement_array['lastname'] = $replacement_array['last_name'] = $userInfo['last_name'];
        $replacement_array['baseurl'] = base_url();
        $replacement_array['url'] = base_url('hr_documents_management/my_documents');
        //SMS Start
        if (empty($userInfo['document_sent_on']) || $userInfo['document_sent_on'] == NULL || date('Y-m-d H:i:s') > date('Y-m-d H:i:s', strtotime('+' . DOCUMENT_SEND_DURATION . ' hours', strtotime($userInfo['document_sent_on'])))) {
            // get people to send out SMS
            $company_sms_notification_status = get_company_sms_status($this, $companyId);
            //
            if ($company_sms_notification_status) {
                $notify_by = get_employee_sms_status($this, $userInfo['sid']);
                $sms_notify = 0;
                if (strpos($notify_by['notified_by'], 'sms') !== false) {
                    $contact_no = $notify_by['PhoneNumber'];
                    $sms_notify = 1;
                }
                if ($sms_notify) {
                    $this->load->library('Twilioapp');
                    // Send SMS
                    $sms_template = get_company_sms_template($this, $companyId, 'hr_document_notification');
                    $sms_body = replace_sms_body($sms_template['sms_body'], $replacement_array);
                    sendSMS(
                        $contact_no,
                        $sms_body,
                        trim(ucwords(strtolower($replacement_array['company_name']))),
                        $userInfo['email'],
                        $this,
                        $sms_notify,
                        $companyId
                    );
                }
            }
            //
            $user_extra_info = array();
            $user_extra_info['user_sid'] = $userId;
            $user_extra_info['user_type'] = $userType;
            // check if user is active
            if ($this->isActiveUser($userId, $userType)) {
                //
                $this->load->model('hr_documents_management_model');
                if ($this->hr_documents_management_model->doSendEmail($userId, $userType, "HREMS4")) {
                    log_and_send_templated_email(
                        HR_DOCUMENTS_NOTIFICATION_EMS,
                        $userInfo['email'],
                        $replacement_array,
                        [],
                        1,
                        $user_extra_info
                    );
                }
                //
                return true;
            }
            //
            return false;
        }
        //
        return false;
    }

    /**
     * Check if employee is active
     *
     * @version 1.0
     * @date    30/05/2022
     *
     * @param int $userId
     * @param string $userType 
     *               This is optional for the time being
     * @return int
     */
    public function isActiveUser(
        $userId,
        $userType = 'employee'
    ): int {
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
}

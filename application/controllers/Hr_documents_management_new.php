<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Hr_documents_management_new extends Public_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('hr_documents_management_model_new');
        $this->load->model('onboarding_model');
        $this->load->model('varification_document_model');
        $this->load->library('pagination');
    }

    //
    function handler()
    {

        $post = array();
        $post = $this->input->post(NULL, TRUE);
        //
        switch ($post['action']) {
                // 
            case "assign_document":

                //Get Document Basic Array
                $document_sid = $this->document_basic_info($post);
                //Document Upload
                $this->doucment_file_upload($post, $document_sid);
                // Document Visibility
                $this->document_visibility($post, $document_sid);
                // Document Settings - Confidential
                $this->document_confidential($post, $document_sid);
                // Document ApprovalFlow
                $this->document_approvalFlow($post, $document_sid);

                echo 'success';
                //
                break;
        }
        //
    }


    public function document_basic_info($post)
    {
        $desc = $this->input->post('desc');

        $a = array();
        $a['status'] = 1;
        $a['acknowledged'] = NULL;
        $a['acknowledged_date'] = NULL;
        $a['downloaded'] = NULL;
        $a['downloaded_date'] = NULL;
        $a['uploaded'] = NULL;
        $a['uploaded_date'] = NULL;
        $a['signature_timestamp'] = NULL;
        $a['signature'] = NULL;
        $a['signature_email'] = NULL;
        $a['signature_ip'] = NULL;
        $a['user_consent'] = 0;
        $a['archive'] = 0;
        $a['signature_base64'] = NULL;
        $a['signature_initial'] = NULL;
        $a['authorized_signature'] = NULL;
        $a['authorized_signature_by'] = NULL;
        $a['authorized_signature_date'] = NULL;
        $a['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $a['is_required'] = $post['isRequired'];
        $a['is_signature_required'] = $post['isSignatureRequired'];
        $a['company_sid'] = $post['CompanySid'];
        $a['assigned_date'] = date('Y-m-d H:i:s', strtotime('now'));
        $a['assigned_by'] = $post['EmployerSid'];
        $a['user_type'] = $post['Type'];
        $a['user_sid'] = $post['EmployeeSid'];
        $a['document_type'] = $post['documentType'];
        $a['document_title'] = $post['documentTitle'];
        if (isset($post['desc'])) $a['document_description'] = $desc;
        $a['document_sid'] = $post['documentSid'];
        $a['status'] = 1;
        $a['visible_to_payroll'] = $post['visibleToPayroll'];

        if (ASSIGNEDOCIMPL) {
            $a['signature_required'] = $post['isSignature'];
            $a['download_required'] = $post['isDownload'];
            $a['acknowledgment_required'] = $post['isAcknowledged'];
        }

        $assignInsertId = $this->check_document_history($post);

        if ($assignInsertId == null) {
            $assignInsertId = $this->hr_documents_management_model_new->insert_documents_assignment_record($a);
        } else {
            $assignInsertId = $this->hr_documents_management_model_new->updateAssignedDocument($assignInsertId, $a); // If already exists then update

        }

        return $assignInsertId;
    }

    public function doucment_file_upload($post, $document_sid)
    {

        $a = array();
        if (isset($post['file'])) {
            $a['document_s3_name'] = $_SERVER['HTTP_HOST'] != 'localhost' ? putFileOnAWSBase64($post['file']) : '0057-test_latest_uploaded_document-58-Yo2.pdf';
            $a['document_original_name'] = $post['fileOrigName'];
        } else if (sizeof($_FILES)) {

            //
            $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
            $uploaded_document_original_name = $post['documentTitle'];
            if (isset($_FILES['file']['name']) && $_FILES['file']['name'] != '' && $_SERVER['HTTP_HOST'] != 'localhost') {
                //
                $uploaded_document_s3_name = upload_file_to_aws('file', $post['CompanySid'], str_replace(' ', '_', $post['documentTitle']), $post['EmployeeSid'], AWS_S3_BUCKET_NAME);
                $uploaded_document_original_name = $_FILES['file']['name'];
            }
            //
            if ($uploaded_document_s3_name != 'error') {
                $a['document_original_name'] = $uploaded_document_original_name;
                $a['document_s3_name'] = $uploaded_document_s3_name;
            }
        }

        if (!empty($a)) {

            $this->hr_documents_management_model_new->update_assigned_document(
                $document_sid,
                $a
            );
        }
    }


    public function document_visibility($post, $document_sid)
    {
        $a = array();
        if (isset($post['roles'])) {
            $a['allowed_roles'] = $post['roles'];
        }
        if (isset($post['departments'])) {
            $a['allowed_departments'] = $post['departments'];
        }
        if (isset($post['teams'])) {
            $a['allowed_teams'] = $post['teams'];
        }
        if (isset($post['employees'])) {
            $a['allowed_employees'] = $post['employees'];
        }
        if (!empty($a)) {
            $this->hr_documents_management_model_new->update_assigned_document(
                $document_sid,
                $a
            );
        }
    }

    public function document_confidential($post, $document_sid)
    {
        $a = array();
        $a['is_confidential'] = isset($post['setting_is_confidential']) && $post['setting_is_confidential'] == 'on' ? 1 : 0;
        //
        $post['confidentialSelectedEmployees'] = $this->input->post('confidentialSelectedEmployees', true);

        //
        $a['confidential_employees'] = NULL;
        //
        if ($post['confidentialSelectedEmployees']) {
            $a['confidential_employees'] = in_array("-1", $post['confidentialSelectedEmployees']) ? "-1" : $post['confidentialSelectedEmployees'];
        }

        if (!empty($a)) {
            $this->hr_documents_management_model_new->update_assigned_document($document_sid, $a);
        }
    }

    public function document_approvalFlow($post, $document_sid)
    {
        $desc = $this->input->post('desc');
        if (isset($post["assigner"]) || $post["hasApprovalFlow"]) {
            //
            $managersList = '';
            //
            if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
                $managersList = $post['managerList'];
            }
            //
            $approvers_list = isset($post['assigner']) ? $post['assigner'] : "";
            $approvers_note = isset($post['assigner_note']) ? $post['assigner_note'] : "";
            //
            // When approval employees are selected
            $this->HandleApprovalFlow(
                $document_sid,
                $approvers_note,
                $approvers_list,
                $post['sendEmail'],
                $managersList
            );
        } else {
            // Check if it's Authorize document
            $this->document_is_authorized($post, $document_sid, $desc);
            // For email
            $this->document_send_email($post, $document_sid);
        }
    }

    public function document_send_email($post, $assignInsertId)
    {

        $is_manual = get_document_type($assignInsertId);
        if ($post['sendEmail'] == 'yes' && $is_manual == 'no') {

            $hf = message_header_footer_domain($post['CompanySid'], $post['CompanyName']);
            // Send Email and SMS
            $replacement_array = array();

            switch ($post['Type']) {
                case 'employee':
                    $user_info = $this->hr_documents_management_model_new->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
                    $is_hour = $this->is_one_hour_complete($user_info['document_sent_on']);
                    //
                    if ($is_hour > 0) {
                        //
                        $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                        $replacement_array['company_name'] = ucwords($post['CompanyName']);
                        $replacement_array['username'] = $replacement_array['contact-name'];
                        $replacement_array['firstname'] = $user_info['first_name'];
                        $replacement_array['lastname'] = $user_info['last_name'];
                        $replacement_array['first_name'] = $user_info['first_name'];
                        $replacement_array['last_name'] = $user_info['last_name'];
                        $replacement_array['baseurl'] = base_url();
                        $replacement_array['url'] = base_url('hr_documents_management/my_documents');
                        //
                        $this->hr_documents_management_model_new->update_employee($post['EmployeeSid'], array('document_sent_on' => date('Y-m-d H:i:s')));
                        //
                        if (sizeof($replacement_array)) {
                            //
                            $user_extra_info = array();
                            $user_extra_info['user_sid'] = $post['EmployeeSid'];
                            $user_extra_info['user_type'] = $post['Type'];
                            //
                            $this->load->model('Hr_documents_management_model', 'HRDMM');
                            if ($this->HRDMM->isActiveUser($post['EmployeeSid'], $post['Type'])) {
                                //
                                if ($this->hr_documents_management_model_new->doSendEmail($post['EmployeeSid'], $post['Type'], "HREMS16")) {
                                    log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                                }
                            }
                        }
                    }

                    $this->send_email_notifications($post);

                    break;

                case 'applicant':
                    $user_info = $this->hr_documents_management_model_new->get_applicant_information($post['CompanySid'], $post['EmployeeSid']);
                    //
                    $this->load->library('encryption', 'encrypt');
                    //
                    $time = strtotime('+10 days');
                    //
                    $encryptedKey = $this->encrypt->encode($assignInsertId . '/' . $user_info['sid'] . '/applicant/' . $time);
                    $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
                    //
                    $replacement_array['applicant_name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
                    $replacement_array['company_name'] = ucwords($post['CompanyName']);
                    $replacement_array['link'] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . ($post['document_title']) . '</a>';
                    //
                    $this->hr_documents_management_model_new
                        ->updateAssignedDocumentLinkTime(
                            $time,
                            $assignInsertId
                        );
                    if (sizeof($replacement_array)) {
                        //
                        $user_extra_info = array();
                        $user_extra_info['user_sid'] = $post['EmployeeSid'];
                        $user_extra_info['user_type'] = $post['Type'];
                        //
                        $this->load->model('Hr_documents_management_model_new', 'HRDMM');
                        if ($this->HRDMM->isActiveUser($post['EmployeeSid'], $post['Type'])) {
                            //
                            log_and_send_templated_email(HR_DOCUMENTS_FOR_APPLICANT, $user_info['email'], $replacement_array, $hf, 1, $user_extra_info);
                        }
                    }
                    break;
            }
            //
        }
    }

    public function document_is_authorized($post, $assignInsertId, $desc)
    {

        if (isset($post['desc']) && $post['managerList'] != null && str_replace('{{authorized_signature}}', '', $desc) != $desc) {
            // Managers handling
            $this->hr_documents_management_model_new->addManagersToAssignedDocuments(
                $post['managerList'],
                $assignInsertId,
                $post['CompanySid'],
                $post['EmployerSid']
            );
            //
            $this->hr_documents_management_model_new->update_assigned_document(
                $assignInsertId,
                [
                    'managersList' => $post['managerList']
                ]
            );
        }
    }

    public function send_email_notifications($post)
    {
        $assigner_firstname = $this->session->userData('logged_in')['employer_detail']['first_name'];
        $assigner_lastname = $this->session->userData('logged_in')['employer_detail']['last_name'];

        $user_info = $this->hr_documents_management_model_new->get_employee_information($post['CompanySid'], $post['EmployeeSid']);
        // Send document completion alert
        broadcastAlert(
            DOCUMENT_NOTIFICATION_ASSIGNED_TEMPLATE,
            'documents_status',
            'document_assigned',
            $post['CompanySid'],
            $post['CompanyName'],
            $assigner_firstname,
            $assigner_lastname,
            $post['EmployeeSid'],
            [
                'document_title' => $post['documentTitle'],
                'employee_name' => $user_info['first_name'] . ' ' . $user_info['last_name']
            ]
        );
    }

    public function check_document_history($post)
    {

        $assignInsertId = null;
        $assigned = $this->hr_documents_management_model_new->getAssignedDocumentByIdAndEmployeeId(
            $post['documentSid'],
            $post['EmployeeSid']
        );

        if (!empty($assigned)) {
            $assignInsertId = $assigned['sid'];
            unset($assigned['sid']);
            unset($assigned['is_pending']);
            $h = $assigned;
            $h['doc_sid'] = $assignInsertId;
            // Insert Document History
            $this->hr_documents_management_model_new->insert_documents_assignment_record_history($h);
        }
        return  $assignInsertId;
    }

    //========== End Functions



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
    private function HandleApprovalFlow(
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
        $this->hr_documents_management_model_new->revoke_document_previous_flow($document_sid);

        // Lets insert the record
        $approvalInsertId = $this->hr_documents_management_model_new->insert_documents_assignment_flow($ins);
        //
        // Update user assigned document
        $this->hr_documents_management_model_new->update_assigned_document(
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
        $this->AddAndSendNotificationsToApprovalEmployees(
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
    function AddAndSendNotificationsToApprovalEmployees(
        $approval_flow_sid,
        $document_sid,
        $approvers_list,
        $initiator_note
    ) {

        if (!empty($approvers_list)) {
            $approvalEmployees = explode(",", $approvers_list);
            //
            foreach ($approvalEmployees as $key => $approver_sid) {
                $is_default_approver = $this->hr_documents_management_model_new->is_default_approver($approver_sid);
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
                    $this->hr_documents_management_model_new->insert_assigner_employee($data_to_insert);
                    //
                    if ($key == 0) {
                        //
                        // Send Email to first approver of this document
                        $this->SendEmailToCurrentApprover($document_sid);
                    }
                }
            }
        } else {

            $document_info = $this->hr_documents_management_model_new->get_approval_document_detail($document_sid);
            //
            $default_approver = $this->hr_documents_management_model_new->getDefaultApprovers(
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
                    $this->hr_documents_management_model_new->update_assigned_document(
                        $document_sid,
                        [
                            'document_approval_employees' => $approver_sid
                        ]
                    );
                } else {
                    $approver_email = $default_approver;
                }
                //

                $this->hr_documents_management_model_new->insert_assigner_employee(
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
                $this->SendEmailToCurrentApprover($document_sid);
            }
        }
    }

    function SendEmailToCurrentApprover($document_sid)
    {

        //
        $document_info = $this->hr_documents_management_model_new->get_approval_document_detail($document_sid);
        //
        $current_approver_info = $this->hr_documents_management_model_new->get_document_current_approver_sid($document_info['approval_flow_sid']);
        //
        $approver_info = array();
        $current_approver_reference = '';
        //
        if ($current_approver_info["assigner_sid"] == 0 && !empty($current_approver_info["approver_email"])) {
            //
            $default_approver = $this->hr_documents_management_model_new->get_default_outer_approver($document_info['company_sid'], $current_approver_info["approver_email"]);
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
            $approver_info = $this->hr_documents_management_model_new->get_employee_information($document_info['company_sid'], $current_approver_info["assigner_sid"]);
            //
            $current_approver_reference = $current_approver_info["assigner_sid"];
        }

        //
        $approvers_flow_info = $this->hr_documents_management_model_new->get_approval_document_bySID($document_info['approval_flow_sid']);
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
            $t = $this->hr_documents_management_model_new->get_employee_information($document_info['company_sid'], $document_info['user_sid']);
            //
            $document_assigned_user_name = ucwords($t['first_name'] . ' ' . $t['last_name']);
        } else {
            //
            $t = $this->hr_documents_management_model_new->get_applicant_information($document_info['company_sid'], $document_info['user_sid']);
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

    function is_one_hour_complete($time)
    {
        if (empty($time)) {
            return 1;
        } else {
            $date1 = new DateTime($time);
            $date2 = new DateTime(date('Y-m-d H:i:s'));

            $diff = $date2->diff($date1);

            $hours = $diff->h;
            $hours = $hours + ($diff->days * 24);

            return $hours;
        }
    }
}

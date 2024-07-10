<?php defined('BASEPATH') || exit('No direct script access allowed');

class Common_ajax extends Public_Controller
{
    //
    function __construct()
    {
        parent::__construct();
        $this->load->model('common_ajax_model');
    }

    //
    function get_send_reminder_email_history($userId, $userType)
    {
        // Get the history
        $history = $this->common_ajax_model->get_send_reminder_email_history($userId, $userType);
        //
        header('Content-Type: application/json');
        echo json_encode($history);
        exit(0);
    }

    //
    function get_send_reminder_email_body()
    {
        //
        return $this->load->view('manage_employer/reminder_emails/index', [], false);
    }

    //
    function send_reminder_email_by_type()
    {
        // Set the post
        $post = $this->input->post(null);

        // Check if post not set
        if (empty($post)) {
            echo 'error';
            exit(0);
        }
        // Get user and company details
        $user_detail = $this->common_ajax_model->get_user_detail($post['userId'], $post['userType']);
        //
        $user_detail['type'] = $post['userType'];
        //
        $company_detail = $this->session->userdata('logged_in')['company_detail'];
        $employer_detail = $this->session->userdata('logged_in')['employer_detail'];
        //
        $email_hf = message_header_footer($company_detail['sid'], ucwords($company_detail['CompanyName']));
        //
        foreach ($post['type'] as $type) {
            // Let's record the action
            $this->common_ajax_model->send_reminder_email_record([
                'userId' => $post['userId'],
                'userType' => $post['userType'],
                'moduleType' => $type,
                'note' => $post['note'],
                'lastSenderSid' => $employer_detail['sid']
            ]);
            // Check if applicant
            if ($post['userType'] == 'applicant') {
                // Check and assign document

//
                if ($type == 'direct-deposit-information') {
                    $type = 'direct-deposit';
                }

                if ($type == 'emergency-contact') {
                    $type = 'emergency_contacts';
                }


                $this->assignDocumentToApplicant(
                    $post['userId'],
                    $post['userType'],
                    $company_detail['sid'],
                    str_replace('-', '_', $type),
                    $employer_detail['sid'],
                    $post['note'],
                    1,
                    $email_hf,
                    $user_detail,
                    $company_detail['CompanyName']
                );
            } else {
                $this->load->model('Hr_documents_management_model', 'HRDMM');
                if ($this->HRDMM->isActiveUser($post['userId'])) {
                    //
                    $this->send_email_reminder($type, $post['note'], $user_detail, $company_detail, $email_hf);
                }
            }
        }
        //
        echo 'success';
        exit(0);
    }

    //
    private function send_email_reminder($type, $note, $user_detail, $company_detail, $email_hf)
    {
        // Set link
        $link = '<a href="' . (base_url('general_info')) . '" style="padding: 10px; color: #ffffff; background-color: #0000ff; border-radius: 5px;">Go To Document</a>';
        //
        $email_slug = $type . '-reminder-email';
        // Get email template
        $template = $this->common_ajax_model->get_email_template_by_code($email_slug);
        // Set replace array
        $replaceArray = [];
        $replaceArray['{{first_name}}'] = ucwords($user_detail['first_name']);
        $replaceArray['{{last_name}}'] = ucwords($user_detail['last_name']);
        $replaceArray['{{company_name}}'] = ucwords($company_detail['CompanyName']);
        $replaceArray['{{company_address}}'] = $company_detail['Location_Address'];
        $replaceArray['{{company_phone}}'] = $company_detail['PhoneNumber'];
        $replaceArray['{{career_site_url}}'] = 'https://' . $email_hf['sub_domain'];
        $replaceArray['{{note}}'] = "<strong>Note:</strong>" . $note;
        $replaceArray['{{link}}'] = $link;
        //
        $indexes = array_keys($replaceArray);
        // Change subject
        $subject = str_replace($indexes, $replaceArray, $template['subject']);
        $body = $email_hf['header'] . str_replace($indexes, $replaceArray, $template['text']) . $email_hf['footer'];
        //
        $from_email = empty($template['from_email']) ? FROM_EMAIL_NOTIFICATIONS : $template['from_email'];
        $from_name = empty($template['from_name']) ? ucwords($company_detail['CompanyName']) : str_replace($indexes, $replaceArray, $template['from_name']);
        //
        log_and_sendEmail($from_email, $user_detail['email'], $subject, $body, $from_name);
    }

    //
    private function assignDocumentToApplicant(
        $user_id,
        $user_type,
        $company_id,
        $type,
        $employer_id,
        $note,
        $is_required,
        $email_hf,
        $user_detail,
        $company_name
    ) {
        //
        $this->load->model('hr_documents_management_model');
        //
        $insertId = $this->hr_documents_management_model->assignGeneralDocument(
            $user_id,
            $user_type,
            $company_id,
            $type,
            $employer_id,
            $this->common_ajax_model->check_if_already_assigned($user_id, $user_type, $company_id, $type),
            $note,
            $is_required
        );
        //
        // Send single document emails to applicant
        // Set email content
        $template = get_email_template(SINGLE_DOCUMENT_EMAIL_TEMPLATE);
        //
        $this->load->library('encryption', 'encrypt');
        //
        $time = strtotime('+10 days');
        //
        $encryptedKey = $this->encrypt->encode($insertId . '/' . $user_id . '/' . $user_type . '/' . $time . '/' . $type);
        $encryptedKey = str_replace(['/', '+'], ['$eb$eb$1', '$eb$eb$2'], $encryptedKey);
        //
        $user_detail['link'] = '<a style="color: #ffffff; background-color: #0000FF; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; border-radius: 5px; text-align: center; display:inline-block;" href="' . (base_url('document/' . ($encryptedKey) . '')) . '">' . (ucwords(preg_replace('/_/', ' ', $type))) . '</a>';
        //
        $emailTemplateBody = convert_email_template($template['text'], $user_detail);
        //
        $emailTemplateSubject = convert_email_template($template['subject'], $user_detail);
        //
        $body = $email_hf['header'];
        $body .= $emailTemplateBody;
        $body .= $email_hf['footer'];
        //
        $this->hr_documents_management_model
            ->updateAssignedGDocumentLinkTime(
                $time,
                $insertId
            );
        //
        log_and_sendEmail(
            FROM_EMAIL_NOTIFICATIONS,
            $user_detail['email'],
            $emailTemplateSubject,
            $body,
            $company_name
        );
    }
}

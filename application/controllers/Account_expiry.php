<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account_expiry extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('account_expiry_model');
    }

    public function index()
    {
        $data['title'] = "Account Expired";
        $data['information'] = 'There is nothing to worry about. Your account has only been deactivated and all of your data is still avaliable, To re-activate your account please fill out the form below with correct information and our team will get back to you as soon as possible.';

        if (isset($_SESSION['account_activation_flag']) && $_SESSION['account_activation_flag'] == "true") {
            $activation_key = $_SESSION['account_activation_key'];
            $_SESSION['account_activation_key'] = NULL;
            $_SESSION['account_activation_flag'] = NULL;
            $data['error_message'] = 'Your company account has expired. Please visit this <a href="' . base_url('account_activation') . '/' . $activation_key . '" target="_blank"><b>Link</b></a> or contact us at Accounts@autmotoHR.com to re-activate your company account.';
        } else {
            $data['error_message'] = 'Your company account has expired, Please contact us at Accounts@' . STORE_DOMAIN . ' to re-activate your company account.';
        }
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->form_validation->set_rules('CompanyName', 'Company name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('ContactName', 'Contact name', 'trim|xss_clean|required');
        $this->form_validation->set_rules('email', 'Your email', 'trim|xss_clean|required');
        $this->form_validation->set_rules('PhoneNumber', 'Your phone number', 'trim|xss_clean|required');
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('manage_employer/account_expiry', $data);
        } else {//POSTED DATA
            $formpost = $this->input->post(NULL, TRUE);
            $formpost['date_placed'] = date('Y-m-d H:i:s');
            $result = $this->account_expiry_model->save_account_expiry($formpost);
            if ($result) {
                $from = FROM_EMAIL_DEV;
                //$to = TO_EMAIL_STEVEN;

                $subject = 'Account Expiration Recovery';

                $body = '';
                $body .= '<p>Dear Admin, </p>';
                $body .= '<p>You have received an Company Re-activation Request. </p>';
                $body .= '<p></p>';
                $body .= '<p>Following are the details user provided us:</p>';
                $body .= '<p></p>';
                $body .= '<p><b>Company Name: </b>' . $formpost['CompanyName'] . '</p>';
                $body .= '<p><b>Contact Person Name: </b>' . $formpost['ContactName'] . '</p>';
                $body .= '<p><b>Contact Email: </b>' . $formpost['email'] . '</p>';
                $body .= '<p><b>Contact Phone: </b>' . $formpost['PhoneNumber'] . '</p>';
                $body .= '<p><b>Message: </b>' . $formpost['message'] . '</p>';
                $body .= '<p> ---------------------------------------------------------</p>';
                $body .= '<p>Automated Email;
                        Please Do Not reply!</p>';
                $body .= '<p> ---------------------------------------------------------</p>';

                //sendMail($from, $to, $subject, $body, STORE_NAME);

                //Send Emails Through System Notifications Email - Start
                $system_notification_emails = get_system_notification_emails('company_account_expiration_emails');

                if (!empty($system_notification_emails)) {
                    foreach ($system_notification_emails as $system_notification_email) {
                        sendMail($from, $system_notification_email['email'], $subject, $body, STORE_NAME);

                        //saving emial to email log
                        $emailData = array(
                            'date' => date('Y-m-d H:i:s'),
                            'subject' => $subject,
                            'email' => $system_notification_email['email'],
                            'message' => $body,
                        );
                        save_email_log_common($emailData);
                    }
                }
                //Send Emails Through System Notifications Email - End


                $this->session->set_flashdata('message ', '<b>Success:</b> Mail sent successfully.');
            } else {
                $this->session->set_flashdata('message ', '<b>Error:</b> Please try again later. Mail was not sent.');
            }
            redirect("account_expiry", "location");
        }
    }

}

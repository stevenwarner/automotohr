<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expirations_autoresponder extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('expirations_autoresponder_model');
    }

    public function index() {
        $companies = $this->expirations_autoresponder_model->GetAllActiveCompanies();
        $currentDateTimestamp = strtotime(date('Y-m-d H:i:s'));

        if($this->input->is_cli_request()) {
            foreach ($companies as $company) {
                //Send Credit Card Expiration Notification - Start
                $company_credit_cards = get_credit_cards($company['sid']);

                if(!empty($company_credit_cards)) {
                    foreach ($company_credit_cards as $credit_card) {
                        $expiration_month = $credit_card['expire_month'];
                        $expiration_year = $credit_card['expire_year'];

                        if (strtolower($expiration_month) != 'xx' && strtolower($expiration_year) != 'xxxx') {
                            $expiration_timestamp = mktime(0, 0, 0, $expiration_month, 1, $expiration_year);
                            //Credit Card Expires on the Last day of the Said Month.
                            $last_day_of_month = date('t', $expiration_timestamp);
                            $actual_expiration_timestamp = mktime(0, 0, 0, $expiration_month, $last_day_of_month, $expiration_year);
                            $alert_start = date_subtract_days(date('Y-m-d H:i:s', $actual_expiration_timestamp), 1); //CREDIT_CARD_EXPIRATION_NOTIFICATION_START_DAYS

                            if ($actual_expiration_timestamp >= $alert_start) {
                                if ($currentDateTimestamp <= $actual_expiration_timestamp && $currentDateTimestamp >= $alert_start) {
                                    if (false) { //Set to false as email should be only to steven.
//                                        foreach ($company_billing_contacts as $billing_contact) {
//                                            $email_address = $billing_contact['email_address'];
//                                            $contact_name = ucwords($billing_contact['contact_name']);
//                                            $replacement_array = array();
//                                            $replacement_array['billing_contact_name'] = $contact_name;
//                                            $replacement_array['company_name'] = ucwords($company['CompanyName']);
//                                            $replacement_array['card_number'] = $credit_card['number'];
//                                            $replacement_array['name_on_card'] = $credit_card['name_on_card'];
//                                            $replacement_array['expiration_month'] = $credit_card['expire_month'];
//                                            $replacement_array['expiration_year'] = $credit_card['expire_year'];
//                                            log_and_send_templated_email(CREDIT_CARD_EXPIRATION_NOTIFICATION, $email_address, $replacement_array);
//                                        }
                                    } else { //Email only to steven.
                                        $replacement_array = array();
                                        $replacement_array['billing_contact_name'] = 'Steven';
                                        $replacement_array['company_name'] = ucwords($company['CompanyName']);
                                        $replacement_array['card_number'] = $credit_card['number'];
                                        $replacement_array['name_on_card'] = $credit_card['name_on_card'];
                                        $replacement_array['expiration_month'] = $credit_card['expire_month'];
                                        $replacement_array['expiration_year'] = $credit_card['expire_year'];
                                        //log_and_send_templated_email(CREDIT_CARD_EXPIRATION_NOTIFICATION, TO_EMAIL_STEVEN, $replacement_array);
                                        //Send Emails Through System Notifications Email - Start
                                        $system_notification_emails = get_system_notification_emails('company_account_expiration_emails');

                                        if(!empty($system_notification_emails)) {
                                            foreach ($system_notification_emails as $system_notification_email) {
                                                log_and_send_templated_email(CREDIT_CARD_EXPIRATION_NOTIFICATION, $system_notification_email['email'], $replacement_array);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                } //Send Credit Card Expiration Notification - End
            }
        }
    }
}
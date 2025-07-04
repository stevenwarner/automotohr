<?php 
exit; 
defined('BASEPATH') OR exit('No direct script access allowed');

use \PayPal\Api\ExecutePayment;
use \PayPal\Api\PaymentExecution; // required
use \PayPal\Api\Capture;
use \PayPal\Api\Payer; // required
use \PayPal\Api\Amount; // required
use \PayPal\Api\Transaction; // required
use \PayPal\Api\Payment; // required
use \PayPal\Api\PayerInfo;
use \PayPal\Api\Details; // required
use \PayPal\Api\Item; // required
use \PayPal\Api\ShippingAddress;
use \PayPal\Api\ItemList; // required
use \PayPal\Exception;
use \PayPal\Api\CreditCard; // required
use PayPal\Api\CreditCardToken; // required
use \PayPal\Api\FundingInstrument; // required
use \PayPal\Exception\PayPalConnectionException;
use \PayPal\Api\Plan;
use \PayPal\Api\PaymentDefinition;
use \PayPal\Api\Currency;
use \PayPal\Api\ChargeModel;
use \PayPal\Api\MerchantPreferences;
use \PayPal\Api\PatchRequest;
use \PayPal\Api\Patch;
use \PayPal\Api\Agreement;
use \PayPal\Api\Address;
use PayPal\Api\RedirectUrls; // required

class Cron_payments extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('cron_payments_model');
        $this->load->model('manage_admin/admin_invoices_model');
        $test_email = FROM_EMAIL_NOTIFICATIONS;
        mail($test_email, 'AutomtoHr Debug - Cron_payments Constructor Called', 'Email Generated On : ' . date('Y-m-d H:i:s'));
        mail($test_email, 'AutomtoHr Debug - Payment Helper Loaded', 'Email Generated On : ' . date('Y-m-d H:i:s'));
    }
/*
    public function index($verification_key = null) {
        exit;
        $test = '/usr/local/bin/php -q /'.DOC_ROOT.'cron.php /cron_payments/index/kg9bMWfIuhP0jCwfQ51x9g5HMtPvZr3qMVYgYJdN3w1MhWpi0l4Hx4sB2QHbIEqqVejAWAF0qmY2oOmUf1oSPC5cXqUKASbl9MEM';
        $test_email = FROM_EMAIL_NOTIFICATIONS;
        mail($test_email, 'AutomtoHr Debug - Payments Cron Running', 'Email Generated On : ' . date('Y-m-d H:i:s'));

        if ($verification_key == 'kg9bMWfIuhP0jCwfQ51x9g5HMtPvZr3qMVYgYJdN3w1MhWpi0l4Hx4sB2QHbIEqqVejAWAF0qmY2oOmUf1oSPC5cXqUKASbl9MEM') {
            $payment_db = $this->cron_payments_model->get_single_record_payment_processing();
            mail($test_email, 'AutomtoHr Debug - Key Verified', 'Email Generated On : ' . date('Y-m-d H:i:s'));

            if (!empty($payment_db)) {
                mail($test_email, 'AutomtoHr Debug - Payment Record Found', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                $invoice_sid = $payment_db['admin_invoice_sid'];
                $payment_sid = $payment_db['sid'];
                $invoice_detail = $this->admin_invoices_model->Get_admin_invoice($invoice_sid, false);

                if (!empty($invoice_detail)) {
                    mail($test_email, 'AutomtoHr Debug - Invoice Found', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                    $company_sid = $invoice_detail['company_sid'];
                    $default_cc = get_credit_card($company_sid, true);
                    $cc = get_credit_card($company_sid, false);
                    $company_cc = array();
                    
                    if (empty($default_cc)) {
                        $company_cc = $cc;
                    } else {
                        $company_cc = $default_cc;
                    }

                    $is_dicounted_invoice = $invoice_detail['is_discounted'];
                    $amount_to_be_charged = 0;

                    if (intval($is_dicounted_invoice) == 1) {
                        $amount_to_be_charged = $invoice_detail['total_after_discount'];
                    } else {
                        $amount_to_be_charged = $invoice_detail['value'];
                    }

                    if (!empty($company_cc)) {
                        mail($test_email, 'AutomtoHr Debug - CC Found', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                        $cc_id = $company_cc['id'];
                        //$payment = process_payment_using_credit_card('CARD-2DC802384M690860AK7NYZRA', 'USD', 1, 'Test Payment');

                        if (is_object($payment)) {
                            //echo 'Payment is Object <br/>';
                            mail($test_email, 'AutomtoHr Debug - Payment Is Object', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                            $payment_state = $payment->getState();

                            if (strtolower($payment_state) == 'approved') {
                                mail($test_email, 'AutomtoHr Debug - Payment State is Approved', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                                $transactions = $payment->getTransactions();
                                $relatedResources = $transactions[0]->getRelatedResources();
                                $sale = $relatedResources[0]->getSale();
                                $saleId = $sale->getId();
                                $this->cron_payments_model->update_payment_status($payment_sid, $payment_state, $saleId);
                                $this->admin_invoices_model->Update_admin_invoice_payment_status($invoice_sid, 'paid');
                                activate_invoice_features($company_sid, $invoice_sid);
                                $this->send_email_on_payment_success($company_sid, $invoice_detail, $amount_to_be_charged);
                            } else {
                                $this->send_email_on_payment_failure($company_sid, $invoice_detail, $amount_to_be_charged);
                            }
                        } else {
                            mail($test_email, 'AutomtoHr Debug - Payment Is Not Object', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                            $this->send_email_on_payment_failure($company_sid, $invoice_detail, $amount_to_be_charged);
                        }
                    } else {
                        mail($test_email, 'AutomtoHr Debug - Credit Card Not Found', 'Email Generated On : ' . date('Y-m-d H:i:s'));
                        // CC Not Found
                        $company_details = $this->cron_payments_model->get_company_information($company_sid);
                        $admin_details = $this->cron_payments_model->get_company_information(intval($company_sid) + 1);

                        if (!empty($company_details) && !empty($admin_details)) {
                            $company_details = $company_details[0];
                            $admin_details = $admin_details[0];
                            //Email to Steven
                            $subject = STORE_NAME.' - Invalid Credit Card Information!';
                            $message_body = '';

                            $message_body .= '<p>' . 'Dear Admin,' . '</p>';
                            $message_body .= '<p>' . 'Automatic Payment Scheduled to be processed on ' . date('m-d-Y') . ' has failed!' . '</p>';
                            $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
                            $message_body .= '<p>' . 'Company: ' . ucwords($company_details['CompanyName']) . '</p>';
                            $message_body .= '<p>' . 'Invoice Number: ' . $invoice_detail['invoice_number'] . '</p>';
                            $message_body .= '<p>' . 'Invoice Value: $ ' . number_format($amount_to_be_charged, 2) . '</p>';
                            $message_body .= '<p>' . 'There is no credit card information saved in the system.' . '</p>';
                            $message_body .= '<p>' . STORE_NAME . '</p>';
                            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
                            //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, TO_EMAIL_STEVEN, $subject, $message_body, FROM_STORE_NAME);
                            //Email to Company Admin
                            $message_body = '';
                            $message_body .= '<p>' . 'Dear ' . ucwords($admin_details['first_name'] . ' ' . $admin_details['last_name']) . '</p>';
                            $message_body .= '<p>' . 'Automatic Payment Scheduled to be processed on ' . date('m-d-Y') . ' has failed!' . '</p>';
                            $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
                            $message_body .= '<p>' . 'Company: ' . ucwords($company_details['CompanyName']) . '</p>';
                            $message_body .= '<p>' . 'Invoice Number: ' . $invoice_detail['invoice_number'] . '</p>';
                            $message_body .= '<p>' . 'Invoice Value: $ ' . number_format($amount_to_be_charged, 2) . '</p>';
                            $message_body .= '<p>' . 'There is no credit card information saved in the system.' . '</p>';
                            $message_body .= '<p>' . 'Please check details and do the needful.' . '</p>';
                            $message_body .= '<p>' . STORE_NAME . '</p>';
                            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
                            //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $admin_details['email'], $subject, $message_body, FROM_STORE_NAME);
                        }
                    }
                }
            }
        }
        //}
    }

    private function send_email_on_payment_success($company_sid, $invoice_detail, $amount_to_be_charged) {
        exit;
        $company_details = $this->cron_payments_model->get_company_information($company_sid);
        $admin_details = $this->cron_payments_model->get_company_information(intval($company_sid) + 1);

        if (!empty($company_details) && !empty($admin_details)) {
            $company_details = $company_details[0];
            $admin_details = $admin_details[0];
            //Email to Steven
            $subject = STORE_NAME.' - Automatic Payment Successful!';
            $message_body = '';
            $message_body .= '<p>' . 'Dear Admin,' . '</p>';
            $message_body .= '<p>' . 'Automatic Payment Scheduled to be processed on ' . date('m-d-Y') . ' has been successfully processed!' . '</p>';
            $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
            $message_body .= '<p>' . 'Company: ' . ucwords($company_details['CompanyName']) . '</p>';
            $message_body .= '<p>' . 'Invoice Number: ' . $invoice_detail['invoice_number'] . '</p>';
            $message_body .= '<p>' . 'Invoice Value: $ ' . number_format($amount_to_be_charged, 2) . '</p>';
            $message_body .= '<p>' . STORE_NAME . '</p>';
            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';

            $message_body = '';
            $message_body .= '<p>' . 'Dear ' . ucwords($admin_details['first_name'] . ' ' . $admin_details['last_name']) . '</p>';
            $message_body .= '<p>' . 'Automatic Payment Scheduled to be processed on ' . date('m-d-Y') . ' has been successfully processed!' . '</p>';
            $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
            $message_body .= '<p>' . 'Company: ' . ucwords($company_details['CompanyName']) . '</p>';
            $message_body .= '<p>' . 'Invoice Number: ' . $invoice_detail['invoice_number'] . '</p>';
            $message_body .= '<p>' . 'Invoice Value: $ ' . number_format($amount_to_be_charged, 2) . '</p>';
            $message_body .= '<p>' . 'Please check details and do the needful.' . '</p>';
            $message_body .= '<p>' . STORE_NAME . '</p>';
            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
            //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $admin_details['email'], $subject, $message_body, FROM_STORE_NAME);
        }
    }

    private function send_email_on_payment_failure($company_sid, $invoice_detail, $amount_to_be_charged) {
        exit;
        $company_details = $this->cron_payments_model->get_company_information($company_sid);
        $admin_details = $this->cron_payments_model->get_company_information(intval($company_sid) + 1);

        if (!empty($company_details) && !empty($admin_details)) {
            $company_details = $company_details[0];
            $admin_details = $admin_details[0];

            $subject = STORE_NAME.' - Automatic Payment Failed!';
            $message_body = '';
            $message_body .= '<p>' . 'Dear Admin,' . '</p>';
            $message_body .= '<p>' . 'Automatic Payment Scheduled to be processed on ' . date('m-d-Y') . ' has failed!' . '</p>';
            $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
            $message_body .= '<p>' . 'Company: ' . ucwords($company_details['CompanyName']) . '</p>';
            $message_body .= '<p>' . 'Invoice Number: ' . $invoice_detail['invoice_number'] . '</p>';
            $message_body .= '<p>' . 'Invoice Value: $ ' . number_format($amount_to_be_charged, 2) . '</p>';
            $message_body .= '<p>' . 'Please check details and do the needful.' . '</p>';
            $message_body .= '<p>' . STORE_NAME . '</p>';
            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
            //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, TO_EMAIL_STEVEN, $subject, $message_body, FROM_STORE_NAME);
            //Email to Company Admin

            $message_body = '';
            $message_body .= '<p>' . 'Dear ' . ucwords($admin_details['first_name'] . ' ' . $admin_details['last_name']) . '</p>';
            $message_body .= '<p>' . 'Automatic Payment Scheduled to be processed on ' . date('m-d-Y') . ' has failed!' . '</p>';
            $message_body .= '<p>' . 'Payment Details are as Follows: ' . '</p>';
            $message_body .= '<p>' . 'Company: ' . ucwords($company_details['CompanyName']) . '</p>';
            $message_body .= '<p>' . 'Invoice Number: ' . $invoice_detail['invoice_number'] . '</p>';
            $message_body .= '<p>' . 'Invoice Value: $ ' . number_format($amount_to_be_charged, 2) . '</p>';
            $message_body .= '<p>' . 'Please check details and do the needful.' . '</p>';
            $message_body .= '<p>' . STORE_NAME . '</p>';
            $message_body .= '<p>' . '**This is an automated email please do not reply.**' . '</p>';
            //log_and_sendEmail(FROM_EMAIL_NOTIFICATIONS, $admin_details['email'], $subject, $message_body, FROM_STORE_NAME);
        }
    }
 * *
 */
}
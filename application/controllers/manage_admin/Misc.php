<?php defined('BASEPATH') or exit('No direct script access allowed');

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
use \PayPal\Exception\PayPalConnectionException; // required
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

ini_set('max_execution_time', 300);

class Misc extends Admin_Controller
{
    private $apiContext;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('manage_admin/admin_invoices_model');
        $this->load->model('manage_admin/receipts_model');
        $this->load->model('ext_model');
        $auth_details = $this->ext_model->fetch_details(THEME_AUTH);
        $ClientID = $auth_details['id'];
        $ClientSecret = $auth_details['pass'];

        if ($_SERVER['HTTP_HOST'] == 'automotohr.local') {
            require_once FCPATH . 'ext/autoload.php';
        } else {
            require_once '/' . DOC_ROOT . 'ext/autoload.php';
        }

        $this->apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                $ClientID,
                $ClientSecret
            )
        );

        if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'www.applybuz.com') {
            // it is sandbox, by default it is sandbox therefore no need to set it
        } else { // set paypal mode to LIVE
            $this->apiContext->setConfig(
                array(
                    'mode' => 'live' // OR sandbox
                )
            );
        }
    }

    /**
     * Utility function to pretty print API error data
     * @param string $errorJson
     * @return string
     */
    function parseApiError($errorJson)
    {
        $msg = '';
        $data = json_decode($errorJson, true);

        if (isset($data['name']) && isset($data['message'])) {
            $msg .= "<b>" . $data['name'] . " : </b>" . $data['message'] . "<br/>";
        }

        if (isset($data['details'])) {
            foreach ($data['details'] as $detail) {
                $msg .= "<br><b>" . $detail['field'] . " : </b>" . $detail['issue'];
            }
        }

        if ($msg == '') {
            $msg = $errorJson;
        }
        return $msg;
    }

    /**
     * Save a credit card with paypal
     *
     * This helps you avoid the hassle of securely storing credit
     * card information on your site. PayPal provides a credit card
     * id that you can use for charging future payments.
     *
     * @param array $params credit card parameters
     * @return mixed
     */
    function saveCard($params, $apiContext, $company_sid)
    {
        $card = new CreditCard();
        $card->setType($params['cc_type']);
        $card->setNumber($params['cc_card_no']);
        $card->setExpireMonth($params['cc_expire_month']);
        $card->setExpireYear($params['cc_expire_year']);

        if (!empty($params['cc_ccv'])) {
            $card->setCvv2($params['cc_ccv']);
        }

        $logArray = [];
        $logArray['ccid'] = substr($params['cc_card_no'], -4);
        $logArray['request_json'] = json_encode($card);

        $card->setMerchantId("AHR_$company_sid");
        try {
            $response = $card->create($apiContext);
            $logArray['response_json'] = json_encode($response);
            $this->saveLog($logArray);
            return $card;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $error_code = $ex->getCode(); // Prints the Error Code
            $message = $this->parseApiError($ex->getData());
            $messageType = "error";
            $error_flag = true;
            $card = array(
                "error_status" => "error",
                "error_code" => $error_code,
                "error_message" => $message  // Filtering by MerchantId set during CreateCreditCard.
            );
            $logArray['response_json'] = json_encode($card);
            $this->saveLog($logArray);
            return $card;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            $logArray['response_json'] = 'error';
            $this->saveLog($logArray);
            return $ex;
        }
    }

    /**
     *
     * @param string $cardId credit card id obtained from
     * a previous create API call.
     * @return mixed
     */
    function getCreditCard($cardId)
    {
        return CreditCard::get($cardId, $this->apiContext);
    }

    /**
     * Create a payment using a previously obtained
     * cc id. The corresponding credit
     * card is used as the funding instrument.
     *
     * @param string $ccId cc id
     * @param string $total Payment amount with 2 decimal points
     * @param string $currency 3 letter ISO code for currency
     * @param string $paymentDesc
     * @return mixed
     */
    function makePaymentUsingCC($ccId, $total, $currency, $paymentDesc, $apiContext)
    {
        $ccToken = new CreditCardToken();
        $ccToken->setCreditCardId($ccId);

        $fi = new FundingInstrument();
        $fi->setCreditCardToken($ccToken);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($fi));

        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);
        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($paymentDesc);

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        // set log array
        $logArray = [];
        $logArray['ccid'] = $ccId;
        $logArray['ccToken'] = $ccToken;
        $logArray['paymentDesc'] = $paymentDesc;
        $logArray['request_json'] = json_encode($payment);

        try {
            $response = $payment->create($apiContext);
            $logArray['response_json'] = json_encode($response);
            $this->saveLog($logArray);
            return $payment;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $error_code = $ex->getCode(); // Prints the Error Code
            $message = $this->parseApiError($ex->getData());
            $messageType = "error";
            $error_flag = true;
            $payment = array(
                "error_status" => "error",
                "error_code" => $error_code,
                "error_message" => $message  // Filtering by MerchantId set during CreateCreditCard.
            );
            $logArray['response_json'] = json_encode($payment);
            $this->saveLog($logArray);
            return $payment;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            $logArray['response_json'] = 'error';
            $this->saveLog($logArray);
            return $ex;
        }
    }

    /**
     * Create a payment using a Direct Credit Card Details.
     * API used: /v1/payments/payment
     */
    function makeDirectPayment($params, $invoice_total, $currency, $payment_description, $company_sid, $apiContext)
    {
        $card = new CreditCard();
        $card->setType($params['cc_type']);
        $card->setNumber($params['cc_card_no']);
        $card->setExpireMonth($params['cc_expire_month']);
        $card->setExpireYear($params['cc_expire_year']);

        if (!empty($params['cc_ccv'])) {
            $card->setCvv2($params['cc_ccv']);
        }

        if (!empty($params['first_name'])) {
            $card->setFirstName($params['first_name']);
        }

        if (!empty($params['last_name'])) {
            $card->setLastName($params['last_name']);
        }

        //$card->setMerchantId("AHR_$company_sid");
        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For direct credit card payments, set the CreditCard field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // For direct credit card payments, set payment method
        // to 'credit_card' and add an array of funding instruments.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName('Invoice Amount')
            ->setDescription($payment_description)
            ->setCurrency($currency)
            ->setQuantity(1)
            ->setPrice($invoice_total);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new Details();
        $details->setSubtotal($invoice_total);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency($currency)
            ->setTotal($invoice_total)
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it.
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription($payment_description);

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        // set log array
        $logArray = [];
        $logArray['ccid'] = null;
        $logArray['ccToken'] = null;
        $logArray['paymentDesc'] = $payment_description;
        $logArray['request_json'] = json_encode($payment);

        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            $response = $payment->create($apiContext);
            $logArray['response_json'] = json_encode($response);
            $this->saveLog($logArray);
            return $payment;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $error_code = $ex->getCode(); // Prints the Error Code
            $message = $this->parseApiError($ex->getData());
            $messageType = "error";
            $error_flag = true;
            $payment = array(
                "error_status" => "error",
                "error_code" => $error_code,
                "error_message" => $message  // Filtering by MerchantId set during CreateCreditCard.
            );
            $logArray['response_json'] = json_encode($payment);
            $this->saveLog($logArray);
            return $payment;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            $logArray['response_json'] = 'error';
            $this->saveLog($logArray);
            return $ex;
        }
    }

    /**
     *
     * @param string $cardId credit card id obtained from
     * a previous create API call.
     */
    function deleteCreditCard($cardId)
    {
        $card = CreditCard::get($cardId, $this->apiContext);
        $card->delete($this->apiContext);
    }

    function listAllCards($merchant_id)
    {
        $params = array(
            "sort_by" => "create_time",
            "sort_order" => "desc",
            "merchant_id" => $merchant_id  // Filtering by MerchantId set during CreateCreditCard.
        );

        $cards = CreditCard::all($params, $this->apiContext);
        return $cards;
    }

    /**
     * Completes the payment once buyer approval has been
     * obtained. Used only when the payment method is 'paypal'
     *
     * @param string $paymentId id of a previously created
     *        payment that has its payment method set to 'paypal'
     *        and has been approved by the buyer.
     *
     * @param string $payerId PayerId as returned by PayPal post
     *        buyer approval.
     * @return mixed
     */
    function executePayment($paymentId, $payerId)
    {
        $payment = getPaymentDetails($paymentId);
        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($payerId);
        $payment = $payment->execute($paymentExecution, getApiContext());
        return $payment;
    }

    /**
     * Retrieves the payment information based on PaymentID from Paypal APIs
     *
     * @param $paymentId
     *
     * @return Payment
     */
    function getPaymentDetails($paymentId)
    {
        $payment = Payment::get($paymentId, getApiContext());
        return $payment;
    }

    /**
     * Determine the baseurl of the current script.
     * Used for determining the absolute url of return and
     * cancel urls.
     * @return string
     */
    function getBaseUrl()
    {
        $protocol = STORE_PROTOCOL;
        if ($_SERVER['SERVER_PORT'] == 443 || (!empty($_SERVER['HTTPS']) && strtolower($_SERVER['HTTPS']) == 'on')) {
            $protocol = STORE_PROTOCOL_SSL;
        }

        $host = $_SERVER['HTTP_HOST'];
        $request = $_SERVER['PHP_SELF'];
        return dirname($protocol . $host . $request);
    }

    function process_payment_admin_invoice($invoice_sid = 0)
    {
        if ($invoice_sid > 0) {
            $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);

            if (!empty($invoice_data)) {
                $company_sid = $invoice_data['company_sid'];
                $credit_cards = $this->ext_model->get_all_company_cards($company_sid, 1);
                $company_admin_user = db_get_first_admin_user($company_sid);
                $admin_user_id = $this->ion_auth->user()->row()->id;

                $this->load->model('notification_emails_model');
                $company_billing_notification_status = $this->notification_emails_model->get_notifications_status($company_sid, 'billing_invoice_notifications');

                $billing_notification_status = 0;
                if (!empty($company_billing_notification_status)) {
                    $billing_notification_status = $company_billing_notification_status['billing_invoice_notifications'];
                }

                $company_billing_contacts = array();
                if ($billing_notification_status == 1) {
                    $company_billing_contacts = getNotificationContacts($company_sid, 'billing_invoice', 'billing_invoice_notifications');
                }

                if ($_POST) {
                    $prev_saved_cc = $this->input->post('prev_saved_cc');

                    if ($prev_saved_cc == 0) {
                        $this->form_validation->set_rules('cc_number', 'Credit Card Number', 'required');
                        $this->form_validation->set_rules('cc_type', 'Credit Card Type', 'required');
                        $this->form_validation->set_rules('cc_expiration_month', 'Credit Card Expiration Month', 'required');
                        $this->form_validation->set_rules('cc_expiration_year', 'Credit Card Expiration Year', 'required');
                    }
                }

                $this->form_validation->set_rules('invoice_amount', 'Invoice Amount', 'required');
                $this->form_validation->set_rules('company_sid', 'Company Sid', 'required');
                $this->form_validation->set_rules('invoice_sid', 'Invoice Sid', 'required');
                $payment = array();

                if ($this->form_validation->run() === FALSE) {
                    $this->data['invoice'] = $invoice_data;
                    $this->data['user_cc'] = $credit_cards;
                    $this->data['admin_user_id'] = $admin_user_id;

                    $this->data['company_info'] = get_company_details($invoice_data['company_sid']);


                    $this->render('manage_admin/invoice/process_payment_admin_invoices', 'admin_master');
                } else {
                    $prev_saved_cc = $this->input->post('prev_saved_cc');
                    $invoice_sid = $this->input->post('invoice_sid');
                    $invoice_amount = $this->input->post('invoice_amount');
                    $company_sid = $this->input->post('company_sid');
                    $employer_sid = $this->ion_auth->user()->row()->id;

                    if (!empty($company_admin_user)) {
                        $employer_sid = $company_admin_user['sid'];
                    }

                    if (intval($prev_saved_cc > 0)) { //Process using Previously saved Card
                        $saved_card_details = db_get_cc_detail($prev_saved_cc, $company_sid);
                        $creditCardToken = trim($saved_card_details['id']);
                        $payment = $this->makePaymentUsingCC($creditCardToken, $invoice_amount, 'USD', 'Payment Against Invoice # ' . $invoice_data['invoice_number'], $this->apiContext);
                    } else { //Get Card Info
                        $cc_number = $this->input->post('cc_number');
                        $cc_type = $this->input->post('cc_type');
                        $cc_expiration_month = $this->input->post('cc_expiration_month');
                        $cc_expiration_year = $this->input->post('cc_expiration_year');
                        $cc_ccv = $this->input->post('cc_ccv');
                        $cc_save_for_future = intval($this->input->post('cc_save_for_future'));

                        if ($cc_save_for_future == 1) { //save card for future use
                            //array params cc_type, cc_card_no, cc_expire_month, cc_expire_year, cc_ccv
                            $card_params = array();
                            $card_params['cc_type'] = $cc_type;
                            $card_params['cc_card_no'] = $cc_number;
                            $card_params['cc_expire_month'] = $cc_expiration_month;
                            $card_params['cc_expire_year'] = $cc_expiration_year;
                            $card_params['cc_ccv'] = $cc_ccv;
                            $card = $this->saveCard($card_params, $this->apiContext, $company_sid);
                            db_save_credit_card($company_sid, $employer_sid, $card->getId(), $card->getNumber(), $card->getType(), $this->input->post('cc_expiration_month'), $this->input->post('cc_expiration_year'), $card->getMerchantId(), $card->getState());
                            $creditCardToken = $card->getId(); //Process Payment using Recently saved Card
                            $payment = $this->makePaymentUsingCC($creditCardToken, $invoice_amount, 'USD', 'Payment Against Invoice # ' . $invoice_data['invoice_number'], $this->apiContext);
                        } else { //Directrly Process Credit Card for Payment.
                            //Card Params cc_type,cc_card_no,cc_expire_month,cc_expire_year,cc_ccv,first_name,last_name
                            $card_params = array();
                            $card_params['cc_type'] = $cc_type;
                            $card_params['cc_card_no'] = $cc_number;
                            $card_params['cc_expire_month'] = $cc_expiration_month;
                            $card_params['cc_expire_year'] = $cc_expiration_year;
                            $card_params['cc_ccv'] = $cc_ccv;
                            $card_params['first_name'] = $company_admin_user['first_name'];
                            $card_params['last_name'] = $company_admin_user['last_name'];
                            $payment = $this->makeDirectPayment($card_params, $invoice_amount, 'USD', 'Payment Against Invoice # ' . $invoice_data['invoice_number'], $company_sid, $this->apiContext);
                        }
                    }

                    if (is_array($payment)) {
                        if (isset($payment['error_message'])) {
                            $this->session->set_flashdata('message', $payment['error_message']);
                        } else {
                            $this->session->set_flashdata('message', 'Payment could not be processed due to unknown error!');
                        }
                        redirect('manage_admin/misc/process_payment_admin_invoice/' . $invoice_sid, 'refresh');
                    }

                    $payment_state = strtolower($payment->getState());

                    if ($payment_state == 'approved') {
                        $payer = $payment->getPayer();
                        $fi = $payer->getFundingInstruments();
                        $cc_token = $fi[0]->getCreditCardToken();

                        //
                        $last4 = $cc_token->getLast4();
                        $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                        $cc_type = $cc_token->getType();
                        // $last4 = substr($cc_number, -4);
                        // $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                        // $cc_type = strtoupper($cc_type);
                        @mail('mubashar.ahmed@egenienext.com', "CC last 4 from admin", "Last 4 digits {$last4}");

                        $data_to_update = array();
                        $data_to_update['payment_status'] = 'paid';
                        $data_to_update['payment_date'] = date('Y-m-d H:i:s');
                        $data_to_update['payment_method'] = 'credit-card';
                        $data_to_update['payment_processed_by'] = $employer_sid;
                        $data_to_update['credit_card_number'] = $cc_number;
                        $data_to_update['credit_card_type'] = $cc_type;

                        $this->admin_invoices_model->update_admin_invoice($invoice_sid, $data_to_update);

                        //$this->admin_invoices_model->Update_admin_invoice_payment_status($invoice_sid, 'paid'); //Mark Invoice as Paid
                        //$this->admin_invoices_model->Update_admin_invoice_payment_method($invoice_sid, 'credit-card'); //Update Payment Method
                        //$this->admin_invoices_model->Update_payment_processed_by($invoice_sid, $employer_sid); //Update Payment Processed By

                        $this->receipts_model->generate_new_receipt($company_sid, $invoice_sid, $invoice_amount, 'Paypal', $admin_user_id, 'super_admin', 'admin_invoice'); //Generate Receipt - Start
                        //Generate Receipt - End
                        //Get Sale ID
                        $transactions = $payment->getTransactions();
                        $relatedResources = $transactions[0]->getRelatedResources();
                        $sale = $relatedResources[0]->getSale();
                        $saleId = $sale->getId();
                        //Mark Automatic Invoice as processed to prevent double payment.
                        $this->admin_invoices_model->Mark_automatic_invoice_as_processed_on_manual_payment($invoice_sid, $payment_state, $saleId);
                        //Activate Features
                        /*
                          $invoice_items = $this->admin_invoices_model->Get_admin_invoice_items($invoice_sid);
                          if(!empty($invoice_items)){
                          foreach($invoice_items as $item){
                          activate_features_after_payment($company_sid, $item['item_sid']);
                          }
                          }
                         */
                        activate_invoice_features($company_sid, $invoice_sid);
                        //Send Notification Email to Client
                        $replacement_array = array();
                        $replacement_array['invoice_number'] = $invoice_data['invoice_number'];
                        $replacement_array['company_admin'] = ucwords($company_admin_user['first_name'] . ' ' . $company_admin_user['last_name']);
                        $replacement_array['payment_method'] = 'Credit Card';
                        $replacement_array['payment_date'] = convert_date_to_frontend_format(date('Y/m/d h:i:s'));
                        $replacement_array['invoice'] = generate_invoice_html($invoice_sid);

                        if (!empty($company_billing_contacts)) {
                            foreach ($company_billing_contacts as $company_billing_contact) {
                                $email_address = $company_billing_contact['email'];
                                $replacement_array['company_admin'] = $company_billing_contact['contact_name'];
                                log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $email_address, $replacement_array);
                            }
                        } else {
                            //log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $company_admin_user['email'], $replacement_array);
                        }
                        mail('mubashar.ahmed@egenienext.com', 'Payment Invoice Notification billing contacts: ' . date('Y-m-d H:i:s'), print_r($company_billing_contacts, true));
                        mail('mubashar.ahmed@egenienext.com', 'Payment Invoice Notification company data: ' . date('Y-m-d H:i:s'), print_r($replacement_array, true));
                        //log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, TO_EMAIL_STEVEN, $replacement_array);
                        //log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, TO_EMAIL_ALEX, $replacement_array);
                        //Send Emails Through System Notifications Email - Start
                        $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                        if (!empty($system_notification_emails)) {
                            foreach ($system_notification_emails as $system_notification_email) {
                                log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $system_notification_email['email'], $replacement_array);
                            }
                        }
                        //Send Emails Through System Notifications Email - End
                        $this->session->set_flashdata('message', 'Payment Successfully Processed!');
                    } else {
                        $this->session->set_flashdata('message', 'Could not process Payment!');
                    }
                    redirect('manage_admin/companies/manage_company/' . $company_sid, 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
                redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
            }
        } else {
            $this->session->set_flashdata('message', '<strong>Error: </strong> Invoice Not Found!');
            redirect('manage_admin/invoice/list_admin_invoices', 'refresh');
        }
    }

    function demo_payment()
    {
        $card = new CreditCard();
        $card->setType("visa")
            ->setNumber("4669424246660779")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Ali")
            ->setLastName("Hassan");

        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($fi));
        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency('USD');
        $amount->setTotal('1');
        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription('custom order');

        $payment = new Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));
        $payment->create($this->apiContext);
        echo 'Payment State: ' . $payment->getState();
        echo "<pre>";
        print_r($payment);
        exit;
    }

    public function cc_management($company_sid = 0)
    {
        // ** Check Security Permissions Checks - Start ** //
        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name); // Param2: Redirect URL, Param3: Function Name
        // ** Check Security Permissions Checks - End ** //

        if ($company_sid == 0) {
            $this->session->set_flashdata('message', '<b>Error:</b> Company not found!');
            redirect('manage_admin/companies', "refresh");
        }

        if (!isset($_POST['perform_action'])) {
            $this->form_validation->set_rules('name_on_card', 'Name on Card', 'required|trim');
            $this->form_validation->set_rules('number', 'Credit Card Number', 'required|trim');
            $this->form_validation->set_rules('type', 'Credit Card Type', 'required|trim');
            $this->form_validation->set_rules('expire_month', 'Expiration Month', 'required|trim');
            $this->form_validation->set_rules('expire_year', 'Expiration Year', 'required|trim');
        } else if (isset($_POST['perform_action']) && $_POST['perform_action'] == 'send_update_cc_request_email') {
            $this->form_validation->set_rules('email_address', 'Email Address', 'required|trim');
        } else {
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');
        }

        if ($this->form_validation->run() == false) {
            $emp_cards = $this->ext_model->get_all_company_cards($company_sid);
            $this->data['cards'] = $emp_cards;
            $company_data = $this->admin_invoices_model->Get_company_information($company_sid);
            $CompanyName = $company_data[0]['CompanyName'];
            $this->data['page_title'] = 'AutomotoHr - ' .  ucwords($CompanyName);
            $this->data['company_sid'] = $company_sid;
            $this->data['company_email'] = $company_data[0]['email'];
            $this->data['company_name'] = $CompanyName;


            $this->render('manage_admin/company/cc_management');
        } else {
            $perform_action = $this->input->post('perform_action');

            switch ($perform_action) {
                case 'save_card':
                    $formpost = $_POST;
                    $this->load->model('manage_admin/company_model');
                    $this->load->model('ext_model');

                    $employerDetail = $this->company_model->get_details($company_sid + 1, 'employer');
                    $first_name = NULL;
                    $last_name = NULL;

                    if (!empty($employerDetail)) {
                        $first_name = $employerDetail[0]['first_name'];
                        $last_name = $employerDetail[0]['last_name'];
                    }

                    $name_on_card = $_POST['name_on_card'];

                    $name_parts = explode(' ', $name_on_card);

                    if (!empty($name_parts)) {
                        $first_name = isset($name_parts[0]) ? $name_parts[0] : $first_name;
                        $last_name = isset($name_parts[1]) ? $last_name[1] : $last_name;
                    }

                    $carddata = array();
                    $carddata['number'] = $_POST['number'];
                    $carddata['type'] = $_POST['type'];
                    $carddata['expire_month'] = $_POST['expire_month'];
                    $carddata['expire_year'] = $_POST['expire_year'];

                    if ($first_name != NULL) {
                        $carddata['first_name'] = $first_name;
                    }

                    if ($last_name != NULL) {
                        $carddata['last_name'] = $last_name;
                    }

                    $card = $this->cc_management_saveCard($carddata, $this->apiContext, $company_sid);

                    if (is_array($card)) {
                        if (isset($card['error_message'])) {
                            $this->session->set_flashdata('message', $card['error_message']);
                        } else {
                            $this->session->set_flashdata('message', 'Card could not be saved due to unknown error!');
                        }
                        redirect('manage_admin/misc/cc_management/' . $company_sid, "refresh");
                    }

                    $card_state = trim($card->getState());

                    if (strtolower($card_state) == 'ok') { // card is fine
                        $creditCardToken = trim($card->getId());
                        $carddetails = array();
                        $carddetails = array(
                            'id' => $creditCardToken,
                            'number' => trim($card->getNumber()),
                            'type' => trim($card->getType()),
                            'expire_month' => $_POST['expire_month'],
                            'expire_year' => $_POST['expire_year'],
                            'name_on_card' => $_POST['name_on_card'],
                            'merchant_id' => trim($card->getMerchantId()),
                            'state' => $card_state
                        );

                        if (isset($_POST['is_default'])) { //make this card default
                            $this->ext_model->reset_all_cards($company_sid);
                            $carddetails['is_default'] = 1;
                        }
                        //serialize extra data of address
                        $custom_data['address_1'] = $formpost['address_1'];
                        $custom_data['address_2'] = $formpost['address_2'];
                        $custom_data['city'] = $formpost['city'];
                        $custom_data['state'] = $formpost['state'];
                        $custom_data['zipcode'] = $formpost['zipcode'];
                        $custom_data['country'] = $formpost['country'];
                        $custom_data['phone_number'] = $formpost['phone_number'];
                        $carddetails['address_details'] = serialize($custom_data);
                        $this->ext_model->cc_future_store($carddetails, $company_sid, $company_sid + 1);
                        $this->session->set_flashdata('message', 'Success, Your card has successfully saved!');
                        redirect('manage_admin/misc/cc_management/' . $company_sid, "refresh");
                    } else { //redirect
                        $this->session->set_flashdata('message', 'Error, Please try again!');
                        redirect('manage_admin/misc/cc_management/' . $company_sid, "refresh");
                    }

                    break;
                case 'send_update_cc_request_email':
                    $company_sid = $this->input->post('company_sid');
                    $email_address = $this->input->post('email_address');
                    $contact_name = $this->input->post('contact_name');
                    $company_name = $this->input->post('company_name');
                    $email_template = $this->input->post('email_template');
                    $card_no = $this->input->post('card_no');
                    $credit_card = db_get_cc_detail($card_no); //{{contact_name}}

                    if ($email_template == 'UPDATE_CREDIT_CARD_REQUEST') {
                        $replacement_array = array();
                        $replacement_array['contact_name'] = ucwords($contact_name);
                        $replacement_array['company_name'] = '<strong>' . ucwords(strtolower($company_name)) . '</strong>';
                        $replacement_array['card_number'] = $credit_card['number'];
                        $replacement_array['name_on_card'] = $credit_card['name_on_card'];
                        $replacement_array['expiration_month'] = $credit_card['expire_month'];
                        $replacement_array['expiration_year'] = $credit_card['expire_year'];
                        $replacement_array['cc_management_link'] = anchor('cc_management', 'Credit Card Management');
                        log_and_send_templated_email(UPDATE_CREDIT_CARD_REQUEST, $email_address, $replacement_array);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Credit Card Update Request Successfully Sent!');
                    } else {
                        $replacement_array = array();
                        $replacement_array['billing_contact_name'] = ucwords($contact_name);
                        $replacement_array['company_name'] = ucwords($company_name);
                        $replacement_array['card_number'] = $credit_card['number'];
                        $replacement_array['name_on_card'] = $credit_card['name_on_card'];
                        $replacement_array['expiration_month'] = $credit_card['expire_month'];
                        $replacement_array['expiration_year'] = $credit_card['expire_year'];
                        log_and_send_templated_email(CREDIT_CARD_EXPIRATION_NOTIFICATION, $email_address, $replacement_array);
                        $this->session->set_flashdata('message', '<strong>Success:</strong> Credit Card Expiration Notification Successfully Sent!');
                    }

                    redirect('manage_admin/misc/cc_management/' . $company_sid, "refresh");
                    break;
                case 'activate_card':
                    $card_sid = $this->input->post('card_sid');

                    $this->ext_model->update_card_active_status($card_sid, 1);

                    $this->session->set_flashdata('message', '<strong>Success: </strong> Card Successfully Activated!');

                    redirect('manage_admin/misc/cc_management/' . $company_sid, 'refresh');
                    break;
                case 'deactivate_card':
                    $card_sid = $this->input->post('card_sid');

                    $this->ext_model->update_card_active_status($card_sid, 0);

                    $this->session->set_flashdata('message', '<strong>Success: </strong> Card Successfully Deactivated!');

                    redirect('manage_admin/misc/cc_management/' . $company_sid, 'refresh');
                    break;
            }
        }
    }

    function cc_management_saveCard($params, $apiContext, $company_sid)
    {
        $card = new CreditCard();
        $card->setType($params['type']);
        $card->setNumber($params['number']);
        $card->setExpireMonth($params['expire_month']);
        $card->setExpireYear($params['expire_year']);

        if (!empty($params['first_name'])) {
            $card->setFirstName($params['first_name']);
        }

        if (!empty($params['last_name'])) {
            $card->setLastName($params['last_name']);
        }

        $card->setMerchantId("AHR_$company_sid");

        try {
            $card->create($apiContext);
            return $card;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $error_code = $ex->getCode(); // Prints the Error Code
            $message = $this->parseApiError($ex->getData());
            $messageType = "error";
            $error_flag = true;
            $card = array(
                "error_status" => "error",
                "error_code" => $error_code,
                "error_message" => $message  // Filtering by MerchantId set during CreateCreditCard.
            );
            return $card;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            $card = array(
                "error_status" => "error",
                "error_message" => $ex  // Filtering by MerchantId set during CreateCreditCard.
            );
            return $card;
        }
    }

    public function edit_card($card_sid = 0)
    {
        if ($card_sid != 0) {
            $this->data['card'] = db_get_cc_detail($card_sid);
            $this->data['card']['address_details'] = unserialize($this->data['card']['address_details']);
            $this->form_validation->set_rules('name_on_card', 'Name on Card', 'required|trim');
            $this->form_validation->set_rules('expire_month', 'Expiration Month', 'required|trim');
            $this->form_validation->set_rules('expire_year', 'Expiration Year', 'required|trim');

            if ($this->form_validation->run() == false) {
                $this->render('manage_admin/company/cc_edit');
            } else {
                //Set Default
                $is_default = $this->input->post('is_default');
                $company_sid = $this->input->post('company_sid');
                $number = $this->input->post('number');

                if (isset($_POST['update_card']) && $_POST['update_card'] == 'update_card') {
                    $this->load->model('ext_model');
                    $formpost = $_POST;
                    $card = $this->updateCreditCard($this->data['card']['id'], $_POST['expire_month'], $_POST['expire_year']);

                    if (is_array($card)) {
                        if (isset($card['error_message'])) {
                            $this->session->set_flashdata('message', $card['error_message']);
                        } else {
                            $this->session->set_flashdata('message', 'Card could not be saved due to unknown error!');
                        }
                        redirect('manage_admin/misc/edit_card/' . $card_sid, 'refresh');
                    }

                    $card_state = trim($card->getState());

                    if (strtolower($card_state) == 'ok') { // card is fine
                        $creditCardToken = trim($card->getId());
                        $carddetails = array();
                        $carddetails = array(
                            'expire_month' => $_POST['expire_month'],
                            'expire_year' => $_POST['expire_year'],
                            'name_on_card' => $_POST['name_on_card'],
                            'merchant_id' => trim($card->getMerchantId()),
                            'state' => $card_state
                        );

                        //serialize extra data of address
                        $custom_data['address_1'] = $formpost['address_1'];
                        $custom_data['address_2'] = $formpost['address_2'];
                        $custom_data['city'] = $formpost['city'];
                        $custom_data['state'] = $formpost['state'];
                        $custom_data['zipcode'] = $formpost['zipcode'];
                        $custom_data['country'] = $formpost['country'];
                        $custom_data['phone_number'] = $formpost['phone_number'];
                        $carddetails["address_details"] = serialize($custom_data);

                        if (!is_null($is_default) && $is_default == 1) {
                            $this->ext_model->reset_all_cards($company_sid);
                            $carddetails['is_default'] = 1;
                        }

                        $this->ext_model->update_card($card_sid, $carddetails);

                        $this->session->set_flashdata('message', 'Success, Your card has successfully updated!');
                        redirect('manage_admin/misc/cc_management/' . $this->data['card']['company_sid'], "refresh");
                    } else {
                        $this->session->set_flashdata('message', 'Error, Please try again!');
                        redirect('manage_admin/misc/cc_management/' . $this->data['card']['company_sid'], "refresh");
                    }
                }
            }
        } else {
            redirect('manage_admin/companies', "refresh");
        }
    }

    function updateCreditCard($cardId, $month, $year)
    {
        $card = $this->getCreditCard($cardId);
        $pathOperation = new Patch();
        $pathOperation->setOp("replace")
            ->setPath('/expire_month')
            ->setValue($month);

        $pathOperation2 = new Patch();
        $pathOperation2->setOp("replace")
            ->setPath('/expire_year')
            ->setValue($year);

        $pathRequest = new \PayPal\Api\PatchRequest();
        $pathRequest->addPatch($pathOperation)
            ->addPatch($pathOperation2);

        try {
            $card = $card->update($pathRequest, $this->apiContext);
            return $card;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $error_code = $ex->getCode(); // Prints the Error Code
            $message = $this->parseApiError($ex->getData());
            $messageType = "error";
            $error_flag = true;
            $card = array(
                "error_status" => "error",
                "error_code" => $error_code,
                "error_message" => $message  // Filtering by MerchantId set during CreateCreditCard.
            );
            return $card;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            $card = array(
                "error_status" => "error",
                "error_message" => $ex  // Filtering by MerchantId set during CreateCreditCard.
            );
            return $card;
        }
    }

    /**
     * Create a payment using a Direct Credit Card Details.
     * API used: /v1/payments/payment
     */
    function makeDirectPayment_muba()
    {
        // A resource representing a credit card that can be used to fund a payment.
        echo "procees direct Payment<br><br><br>";
        $card = new CreditCard();
        $card->setType('visa');
        $card->setNumber('4669424246660779'); //4669424246660779
        $card->setExpireMonth('03');
        $card->setExpireYear('2019');
        $card->setCvv2('123');
        $card->setFirstName('AHR');
        $card->setLastName('222');

        //hello world
        //$card->setMerchantId("AHR_$company_sid");
        // ### FundingInstrument
        // A resource representing a Payer's funding instrument.
        // For direct credit card payments, set the CreditCard field on this object.
        $fi = new FundingInstrument();
        $fi->setCreditCard($card);

        // ### Payer
        // A resource representing a Payer that funds a payment
        // For direct credit card payments, set payment method
        // to 'credit_card' and add an array of funding instruments.
        $payer = new Payer();
        $payer->setPaymentMethod("credit_card")
            ->setFundingInstruments(array($fi));

        // ### Itemized information
        // (Optional) Lets you specify item wise
        // information
        $item1 = new Item();
        $item1->setName('Invoice Amount')
            ->setDescription('Testing Only')
            ->setCurrency('USD')
            ->setQuantity(1)
            ->setPrice(1);

        $itemList = new ItemList();
        $itemList->setItems(array($item1));

        // ### Additional payment details
        // Use this optional field to set additional
        // payment information such as tax, shipping
        // charges etc.
        $details = new Details();
        $details->setSubtotal(1);

        // ### Amount
        // Lets you specify a payment amount.
        // You can also specify additional details
        // such as shipping, tax.
        $amount = new Amount();
        $amount->setCurrency('USD')
            ->setTotal(1)
            ->setDetails($details);

        // ### Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. 
        $transaction = new Transaction();
        $transaction->setAmount($amount)
            ->setItemList($itemList)
            ->setDescription('Test Direct Payment');

        // ### Payment
        // A Payment Resource; create one using
        // the above types and intent set to sale 'sale'

        $payment = new Payment();
        $payment->setIntent("sale")
            ->setPayer($payer)
            ->setTransactions(array($transaction));

        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            $payment->create($this->apiContext);
            echo "<pre>";
            print_r($payment);
            exit;
        } catch (PayPal\Exception\PayPalConnectionException $ex) {
            $error_code = $ex->getCode(); // Prints the Error Code
            $message = $this->parseApiError($ex->getData());
            $messageType = "error";
            $error_flag = true;
            $payment = array(
                "error_status" => "error",
                "error_code" => $error_code,
                "error_message" => $message  // Filtering by MerchantId set during CreateCreditCard.
            );
            echo "<pre> ERROR: 1 <br>";
            print_r($payment);
        } catch (Exception $ex) {
            echo "<pre> ERROR: 2 <br>";
            print_r($ex);
        }
    }

    public function my_ajax_responder()
    {
        if ($_POST) {
            if (isset($_POST['perform_action']) && $_POST['perform_action'] != '') {
                $perform_action = strtoupper($_POST['perform_action']);

                switch ($perform_action) {
                    case 'MAKE_DEFAULT_CARD':
                        $card_id = $_POST['card_id'];
                        $table = 'emp_cards';
                        //making one card default
                        $dataToUpdate = array(
                            'is_default' => 1
                        );
                        $where = array('sid' => $card_id);
                        update_from_table($table, $where, $dataToUpdate);
                        echo 'success';
                        break;
                    case 'DELETE_CARD':
                        $card_id = $_POST['card_id'];
                        $cc_id = $this->db->select('id')->where('sid', $card_id)->get('emp_cards')->row_array();
                        $this->ext_model->delete_credit_card($card_id);
                        //                            $this->deleteCreditCard($cc_id['id']); // temporarly disabled
                        echo 'success';
                        break;
                    case 'MAKE_UNDEFAULT_CARD':
                        $card_id = $_POST['card_id'];
                        $table = 'emp_cards';
                        $dataToUpdate = array(
                            'is_default' => 0
                        );
                        $where = array('sid' => $card_id);
                        update_from_table($table, $where, $dataToUpdate);
                        echo 'success';
                        break;
                }
            }
        }
    }

    /**
     * Save paypal calls
     */
    private function saveLog(array $insertArray)
    {
        $insertArray['created_at'] = getSystemDate();
        //
        $this->db->insert('paypal_log', $insertArray);
    }



    //
    public function bank_ac_management($company_sid = 0)
    {

        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);

        if ($company_sid == 0) {
            $this->session->set_flashdata('message', '<b>Error:</b> Company not found!');
            redirect('manage_admin/companies', "refresh");
        }

        $this->form_validation->set_rules('account_title', 'Account Title', 'required|trim');
        $this->form_validation->set_rules('account_type', 'Account Type', 'required|trim');
        $this->form_validation->set_rules('account_number', 'Account Number', 'required|trim');
        $this->form_validation->set_rules('routing_number', 'Routing Number', 'required|trim');

        if ($this->form_validation->run() == false) {
            $bankAccount = $this->ext_model->get_company_bank_account($company_sid);
            $this->data['bankAccount'] = $bankAccount;
            $this->data['company_sid'] = $company_sid;

            $this->render('manage_admin/company/company_bank_account_form');
        } else {

            $updateData = [];
            $updateData['bank_name'] = $this->input->post('bank_name');
            $updateData['account_title'] = $this->input->post('account_title');
            $updateData['account_type'] = $this->input->post('account_type');
            $updateData['account_number'] = $this->input->post('account_number');
            $updateData['routing_number'] = $this->input->post('routing_number');
            $updateData['company_sid'] = $this->input->post('company_sid');
            $updateData['sid'] = $this->input->post('sid');
            $this->ext_model->update_company_bank_account($updateData);

            $this->session->set_flashdata('message', '<strong>Success: </strong> Bank Account Successfully Updated!');

           redirect('manage_admin/misc/bank_ac_management/' . $updateData['company_sid'], "refresh");

        }
    }


    //
    public function federal_tax_information($company_sid = 0)
    {

        $redirect_url = 'manage_admin/companies';
        $function_name = 'edit_company';
        $admin_id = $this->ion_auth->user()->row()->id;
        $security_details = db_get_admin_access_level_details($admin_id);
        $this->data['security_details'] = $security_details;
        check_access_permissions($security_details, $redirect_url, $function_name);

        if ($company_sid == 0) {
            $this->session->set_flashdata('message', '<b>Error:</b> Company not found!');
            redirect('manage_admin/companies', "refresh");
        }

        $this->form_validation->set_rules('ssn', 'Federal EIN', 'required|trim');
        $this->form_validation->set_rules('tax_payer_type', 'Tax Payer Type', 'required|trim');
        $this->form_validation->set_rules('filing_form', 'Federal Filling Form', 'required|trim');
        $this->form_validation->set_rules('legal_name', 'Legal Entitlty Name', 'required|trim');

        if ($this->form_validation->run() == false) {
            $taxInfo = $this->ext_model->get_company_federal_tax_informarion($company_sid);
            $this->data['taxInfo'] = $taxInfo;
            $this->data['company_sid'] = $company_sid;

            $this->render('manage_admin/company/company_federal_information_form');
        } else {

            $updateData = [];
            $updateData['ssn'] = $this->input->post('ssn');
            $updateData['tax_payer_type'] = $this->input->post('tax_payer_type');
            $updateData['filing_form'] = $this->input->post('filing_form');
            $updateData['legal_name'] = $this->input->post('legal_name');
            $updateData['company_sid'] = $this->input->post('company_sid');
            $updateData['sid'] = $this->input->post('sid');

            $this->ext_model->update_company_federal_tax_information($updateData);

            $this->session->set_flashdata('message', '<strong>Success: </strong> Federal tax information Successfully Updated!');

            redirect('manage_admin/misc/federal_tax_information/' . $updateData['company_sid'], "refresh");

        }
    }




    
}

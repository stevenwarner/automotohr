<?php defined('BASEPATH') || exit('No direct script access allowed');

class Invoice_new extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_new_model');
        $this->load->model('manage_admin/admin_invoices_model');
        $this->load->model('manage_admin/receipts_model');
    }

    public function view_invoice($invoice_sid)
    {
        if ($invoice_sid > 0) {

            $invoice_data = $this->invoice_new_model->Get_admin_invoice($invoice_sid);
            $invoice_html = '';
          
            $company_sid = $invoice_data['company_sid'];

            if (!empty($invoice_data)) {
                if ($invoice_data['company_sid'] == $company_sid) {
                    $invoice_html = generate_invoice_html($invoice_sid);
                } else {
                    $this->session->set_flashdata('message', '<b>Error:</b> No Such Invoice Exists!');
                  //  redirect('invoice_new/view_invoice', 'refresh');
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No Invoice Found!');
            }

            $credit_cards = $this->invoice_new_model->get_all_company_cards($company_sid, 1);

            $data['invoice'] = $invoice_data;
            $data['user_cc'] = $credit_cards;

            $data['invoicehtml'] = $invoice_html;
            $data['title'] = 'View Invoice # ' . $invoice_data['invoice_number'];
            $data['invoice_sid'] = $invoice_data['sid'];
            $data['payment_status'] = $invoice_data['payment_status'];
            $this->load->view('invoice',$data);
        } 
             
        
    }



    //
    function process_payment_admin_invoice($invoice_sid = 0) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'application_tracking'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            //$company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $invoice_data = $this->invoice_new_model->Get_admin_invoice($invoice_sid);
            $company_sid = $invoice_data['company_sid'];
            $credit_cards = $this->invoice_new_model->get_all_company_cards($company_sid, 1);
            $company_admin_user = db_get_first_admin_user($company_sid);

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
                $data['invoice'] = $invoice_data;
                $data['user_cc'] = $credit_cards;
                $data['title'] = 'Process Payment';
                //$data['admin_user_id'] = $admin_user_id;
             //   $this->load->view('main/header', $data);
               // $this->load->view('manage_employer/process_payment_admin_invoice');
             //   $this->load->view('main/footer');
            } else {
                $prev_saved_cc = $this->input->post('prev_saved_cc');
                $invoice_sid = $this->input->post('invoice_sid');
                $invoice_amount = $this->input->post('invoice_amount');
                $company_sid = $this->input->post('company_sid');

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
                        $card_params = array();
                        $card_params['cc_type'] = $cc_type;
                        $card_params['cc_card_no'] = $cc_number;
                        $card_params['cc_expire_month'] = $cc_expiration_month;
                        $card_params['cc_expire_year'] = $cc_expiration_year;
                        $card_params['cc_ccv'] = $cc_ccv;
                        $card = $this->saveCard($card_params, $this->apiContext, $company_sid);
                        db_save_credit_card($company_sid, $employer_sid, $card->getId(), $card->getNumber(), $card->getType(), $card->getExpireMonth(), $card->getExpireYear(), $card->getMerchantId(), $card->getState());
                        //Process Payment using Recently saved Card
                        $creditCardToken = $card->getId();
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
                        print_r($card_params);
                        die();
                        $payment = $this->makeDirectPayment($card_params, $invoice_amount, 'USD', 'Payment Against Invoice # ' . $invoice_data['invoice_number'], $company_sid, $this->apiContext);
                    }
                }

                if (is_array($payment)) {
                    
                    if (isset($payment['error_message'])) {
                        $this->session->set_flashdata('message', $payment['error_message']);
                    } else {
                        $this->session->set_flashdata('message', 'Payment could not be processed due to unknown error!');
                    }
                    
                    redirect('misc/process_payment_admin_invoice/' . $invoice_sid, 'refresh');
                }

                $payment_state = strtolower($payment->getState());

                if ($payment_state == 'approved') {
                    $payer = $payment->getPayer();
                    $fi = $payer->getFundingInstruments();
                    $cc_token = $fi[0]->getCreditCardToken();
                    //
                    if (false) {
                        $last4 = $cc_token->getLast4();
                        $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                        $cc_type = $cc_token->getType();
                    } else {
                        $last4 = substr($cc_number, -4);
                        $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                        $cc_type = strtoupper($cc_type);
                    }

                    $data_to_update = array();
                    $data_to_update['payment_status'] = 'paid';
                    $data_to_update['payment_date'] = date('Y-m-d H:i:s');
                    $data_to_update['payment_method'] = 'credit-card';
                    $data_to_update['payment_processed_by'] = $employer_sid;
                    $data_to_update['credit_card_number'] = $cc_number;
                    $data_to_update['credit_card_type'] = $cc_type;
                    $this->admin_invoices_model->update_admin_invoice($invoice_sid, $data_to_update);
                    //Generate Receipt - Start
                    $this->receipts_model->generate_new_receipt($company_sid, $invoice_sid, $invoice_amount, 'Paypal', $employer_sid, 'employer_portal', 'admin_invoice');
                    //Generate Receipt - End
                    //Get Sale ID
                    $transactions = $payment->getTransactions();
                    $relatedResources = $transactions[0]->getRelatedResources();
                    $sale = $relatedResources[0]->getSale();
                    $saleId = $sale->getId();
                    //Mark Automatic Invoice as processed to prevent double payment.
                    $this->admin_invoices_model->Mark_automatic_invoice_as_processed_on_manual_payment($invoice_sid, $payment_state, $saleId);
                    activate_invoice_features($company_sid, $invoice_sid);

                    //Send Notification Email to Client
                    $replacement_array = array();
                    $replacement_array['invoice_number'] = $invoice_data['invoice_number'];
                    $replacement_array['company_admin'] = ucwords($company_admin_user['first_name'] . ' ' . $company_admin_user['last_name']);
                    $replacement_array['payment_method'] = 'Credit Card';
                    $replacement_array['payment_date'] = convert_date_to_frontend_format(date('Y/m/d h:i:s'));
                    $replacement_array['invoice'] = generate_invoice_html($invoice_sid);
                    $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                    if (!empty($system_notification_emails)) {
                        foreach ($system_notification_emails as $system_notification_email) {
                            log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $system_notification_email['email'], $replacement_array);
                        }
                    }
                    
                    $this->session->set_flashdata('message', 'Payment Successfully Processed!');
                } else {
                    $this->session->set_flashdata('message', 'Could not process Payment!');
                }
                redirect('settings/list_packages_addons_invoices', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
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
     */
    function makePaymentUsingCC($ccId, $total, $currency, $paymentDesc, $apiContext) {
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

        try {
            $payment->create($apiContext);s
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
            return $payment;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            return $ex;
        }
    }


    function saveCard_muba() {
        $card = new CreditCard();
        $card->setType("visa")
            ->setNumber("4417119669820331")
            ->setExpireMonth("11")
            ->setExpireYear("2019")
            ->setCvv2("012")
            ->setFirstName("Dev")
            ->setLastName("222");

        $card->setMerchantId("AHR_Hassan");
        try {
            $card->create($this->apiContext);
            echo "<pre>";
            print_r($card);
            exit;
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
            echo "<pre>";
            print_r($card);
            exit;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            echo "<pre>";
            print_r($ex);
            exit;
        }
    }


        /**
     * Create a payment using a Direct Credit Card Details.
     * API used: /v1/payments/payment
     */
    function makeDirectPayment($params, $invoice_total, $currency, $payment_description, $company_sid, $apiContext) {
        // A resource representing a credit card that can be used to fund a payment.
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

        // ### Create Payment
        // Create a payment by calling the payment->create() method
        // with a valid ApiContext (See bootstrap.php for more on `ApiContext`)
        // The return object contains the state.
        try {
            $payment->create($apiContext);
            return $payment;
        } catch (Exception $ex) {
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            return $ex;
        }
    }


}

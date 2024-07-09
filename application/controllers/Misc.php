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
use \PayPal\Exception\PayPalConnectionException;

ini_set('max_execution_time', 300);

class Misc extends CI_Controller
{
    private $apiContext;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('ext_model');
        $this->load->model('manage_admin/admin_invoices_model');
        $this->load->model('manage_admin/receipts_model');
        $this->load->model('manage_admin/marketing_agencies_model');
        $auth_details = $this->ext_model->fetch_details(THEME_AUTH);
        $ClientID = $auth_details['id'];
        $ClientSecret = $auth_details['pass'];

        //
        require_once FCPATH . '/ext/autoload.php';

        // if ($_SERVER['HTTP_HOST'] == 'localhost') {
        //     require_once FCPATH . '/ext/autoload.php';
        // } else if ($_SERVER['HTTP_HOST'] == 'www.applybuz.com' || $_SERVER['HTTP_HOST'] == 'applybuz.com') {
        //     require_once '/home/applybuz/public_html/ext/autoload.php';
        // } else {
        //     require_once '/' . DOC_ROOT . 'ext/autoload.php';
        // }

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

    function cc_apply_main()
    {
        $data = $_POST;
        //echo json_encode($data['product']);
        $products_details = array();
        $product = $data['product'];
        $cc_future_payment = 'off';
        $has_coupon = false;
        $error_flag = false;
        $coupon_array = array();
        $response = array();
        $ordered_products = array();
        $serialized_items_info = array();
        $coupon_error = '';
        $order_amount = 0;
        $order_final_total = 0;
        $total_discount = 0.00;
        $order_currency = 'USD';
        $order_description = '';
        $proucts_sid = array();
        $card_error = 'no_error';
        $userdata = $this->session->userdata('logged_in');
        $company_sid = $userdata["company_detail"]["sid"];
        $employer_sid = $userdata["employer_detail"]["sid"];
        $fb_api_product_flag = 'false';

        if (isset($data['cc_future_payment'])) {
            $cc_future_payment = $data['cc_future_payment'];
        }

        if (isset($data['coupon_code'])) {
            $has_coupon = true;
            $coupon_code = $data['coupon_code'];
            $coupon_type = $data['coupon_type'];
            $coupon_discount = $data['coupon_discount'];
        }

        if (!empty($product)) {
            foreach ($product as $key => $value) {
                $product_id = $value['id'];
                $products_details[$key] = db_get_products_details($product_id);
                $products_details[$key]['qty'] = $value['qty'];
                $products_details[$key]['mid'] = $value['mid'];
                $products_details[$key]['no_of_days'] = $value['no_of_days'];
            }
        }

        //For Commission Invoice
        $id_to_quantity = array();
        $id_to_rooftops = array();
        $product_sids = array();
        $product_quantity = 0;

        if (!empty($products_details)) {
            foreach ($products_details as $key => $value) { // step 1 - check if the ordered product is still active or not

                if ($value['active'] == '0') { // the product is offline
                    $error_flag = true;
                    $response[] = $value['mid'];
                }
                //product_price, product_total, company_sid
                $no_of_days = $value['no_of_days'];
                $product_qty = $value['number_of_postings'] * $value['qty'];
                $daily = $value['daily'];

                if ($daily > 0) {
                    $expiry_days = $no_of_days;
                    $product_total = $value['price'] * $value['qty'] * $no_of_days;
                    $product_quantity = $value['qty'] * $no_of_days;
                } else {
                    $expiry_days = $value['expiry_days'];
                    $product_total = $value['price'] * $value['qty'];
                    $product_quantity = $value['qty'];
                }

                $cost_price = $this->ext_model->get_product_cost_price($value['sid']);
                $ordered_products[] = array(
                    'product_sid' => $value['sid'],
                    'product_qty' => $product_qty,
                    'product_remaining_qty' => $product_qty,
                    'order_qty' => $value['qty'],
                    'product_price' => $value['price'],
                    'cost_price' => $cost_price,
                    'product_total' => $product_total,
                    'company_sid' => $company_sid
                );

                $serialized_items_info['custom_text'][] = '';
                $serialized_items_info['item_qty'][] = $product_qty;
                $serialized_items_info['item_price'][] = $product_total;
                $serialized_items_info['products'][] = $value['sid'];
                $serialized_items_info['item_remaining_qty'][] = $product_qty;
                $serialized_items_info['no_of_days'][] = $expiry_days;
                $serialized_items_info['flag'][] = 'no_edit';
                $serialized_items_info['cost_price'][] = $cost_price;
                $serialized_items_info['total_cost'][] = $cost_price * $value['qty'];
                $order_amount = $order_amount + $product_total;
                $order_description .= 'Product: ' . $value['qty'] . ' * ' . $value['name'] . ', ';
                $proucts_sid[] = $value['sid'];

                if ($value['sid'] == 26) {
                    $fb_api_product_flag = 'true';
                    $fb_api_expiry_days = $expiry_days * $value['qty'];
                }

                $id_to_quantity[$value['sid']] = $product_quantity; //For Commission Invoice
                $id_to_rooftops[$value['sid']] = 1;
                $product_sids[] = $value['sid'];
            }
        }

        if ($has_coupon) { // check if coupon code is applied
            $coupon_data = db_get_coupon_content($coupon_code);

            if (empty($coupon_data)) { // Coupon code does not exists or it is not active
                $error_flag = true;
                $coupon_error = 'coupon_error';
            } else {
                $coupon_data = $coupon_data[0];
                $discount = $coupon_data['discount'];
                $type = $coupon_data['type'];
                $maximum_uses = $coupon_data['maximum_uses'];
                $start_date = $coupon_data['start_date'];
                $end_date = $coupon_data['end_date'];

                if ($start_date != null) { // check whether coupon is started
                    $current_date_time = date('Y-m-d H:i:s');

                    if ($start_date > $current_date_time) { // Coupon code is not started yet.
                        $error_flag = true;
                        $coupon_error = 'coupon_error';
                    }
                }

                if ($end_date != null) { // check whether coupon has expired
                    $current_date_time = date('Y-m-d H:i:s');

                    if ($current_date_time > $end_date) { // coupon code has expired
                        $error_flag = true;
                        $coupon_error = 'coupon_error';
                    }
                }

                if ($maximum_uses == null || $maximum_uses == 0) { // need to figure out the maxium uses information
                    //it is umlimited, no need to perform any checks - APPLY CHECKS PLEASE
                }

                if ($type == 'fixed') {
                    $total_discount = $discount;
                } else {
                    $total_discount = round((($order_amount * $discount) / 100), 2);
                }

                $coupon_array = array(
                    'coupon_code' => $coupon_code,
                    'coupon_discount' => $discount,
                    'coupon_type' => $type
                );

                $order_description .= 'Coupon Code: ' . $coupon_code . ', total_discount: ' . $total_discount . ', ';
            }
        }

        $order_description = substr($order_description, 0, -2);

        if (!empty($coupon_error)) {
            $this->session->unset_userdata('coupon_data');
        }

        //print_r($this->apiContext);
        // array - 0 reserved for error flag or success flag
        // array - 1 reserved for Products error flag
        // array - 2 reserved for coupon code error flag
        // array - 3 reserved for paypal error flag
        // array - 4 reserved for produts array

        if ($error_flag) {
            $array[0] = "error";

            if (empty($response)) {
                $array[1] = 'no_error';
            } else {
                $array[1] = implode(",", $response);
            }

            $array[2] = $coupon_error;
            $array[3] = $card_error;
            $array[4] = $data;
            echo json_encode($array);
            exit();
        } else {
            if (isset($data['process_credit_card']) && intval($data['process_credit_card'] == 1)) {
                // there is no error found. now we can process the order and save the details
                //cardCARD-57C588728W287145BK26IVNI
                if (isset($data['cc_id']) && !empty($data['cc_id'])) { // make payment from saved cc
                    $mycard = db_get_cc_detail($data['cc_id'], $company_sid);

                    if (empty($mycard)) { // card not found, generate error
                        $error_flag = true;
                        $card_error = 'Error: Please select valid card for payment!';
                    } else { // process payment
                        $creditCardToken = trim($mycard['id']);
                    }
                } else { // make payment with new cc
                    $card = $this->savecard($data, $this->apiContext, $company_sid);
                    // ali hassan check area
                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($card);
                        exit;
                    } else {
                        $creditCardToken = trim($card->getId());

                        if ($cc_future_payment != 'off') { // Save the credit card to vault and save its unique id to DB
                            $carddata = array();
                            $carddata = array(
                                'id' => $card->getId(),
                                'number' => $card->getNumber(),
                                'type' => $card->getType(),
                                'expire_month' => $card->getExpireMonth(),
                                'expire_year' => $card->getExpireYear(),
                                'merchant_id' => $card->getMerchantId(),
                                'state' => $card->getState()
                            );

                            $this->ext_model->cc_future_store($carddata, $company_sid, $employer_sid);
                        }
                    }
                }

                if (!empty($creditCardToken)) {
                    $order_final_total = $order_amount - $total_discount;
                    $from = FROM_EMAIL_ACCOUNTS;
                    $to = TO_EMAIL_DEV;
                    $subject = "Main Cart Order";

                    $body = 'employer sid: ' . $employer_sid . "<br>"
                        . 'customer name: ' . $userdata["employer_detail"]['username'] . "<br>"
                        . 'order total: ' . $order_final_total . "<br>"
                        . 'total discount: ' . $total_discount . "<br>";

                    sendMail($from, $to, $subject, $body);
                    //sending email to DEV ends
                    $payment = $this->makePaymentUsingCC($creditCardToken, $order_final_total, $order_currency, 'AHR_Payments', $this->apiContext);

                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($payment);
                        exit;
                    } else {
                        $payment_id = trim($payment->getId());
                        $payment_state = trim($payment->getState());
                        $purchased_date = date('Y-m-d H:i:s');

                        if ($payment_state == 'approved') { // payment was successful, add to db and show success message
                            if ($fb_api_product_flag == 'true') {
                                $this->load->model('manage_admin/company_model');
                                $select = 'expiry_date';
                                $fb_api_data = $this->company_model->get_company_facebook_api_detail($company_sid, $select);
                                $dataToUpdate['purchase_date'] = date('Y-m-d H:i:s');

                                if ($fb_api_data['expiry_date'] != NULL) {
                                    $dataToUpdate['expiry_date'] = date('Y-m-d H:i:s', strtotime($fb_api_data['expiry_date'] . " + " . $fb_api_expiry_days . " days"));
                                } else {
                                    $dataToUpdate['expiry_date'] = date('Y-m-d H:i:s', strtotime("+ " . $fb_api_expiry_days . " days"));
                                }

                                $this->company_model->updateFacebookStatus($company_sid, $dataToUpdate);
                            }

                            $payer = $payment->getPayer();
                            $fi = $payer->getFundingInstruments();
                            $cc_token = $fi[0]->getCreditCardToken();

                            $last4 = $cc_token->getLast4();
                            @mail('mubashar.ahmed@egenienext.com', "CC last 4", "Last 4 digits {$last4}");
                            $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                            $cc_type = $cc_token->getType();

                            $orders_data = array();
                            $orders_data = array(
                                'order_status' => 'paid',
                                'employer_sid' => $employer_sid,
                                'purchased_date' => $purchased_date,
                                'company_sid' => $company_sid,
                                'total' => $order_final_total,
                                'payment_method' => 'Paypal',
                                'verification_response' => $payment_id
                            );

                            $invoice_data = array();
                            $invoice_data = array(
                                'user_sid' => $employer_sid,
                                'company_sid' => $company_sid,
                                'date' => $purchased_date,
                                'payment_method' => 'Paypal',
                                'total_discount' => $total_discount,
                                'sub_total' => $order_amount,
                                'total' => $order_final_total,
                                'serialized_items_info' => serialize($serialized_items_info),
                                'status' => 'Paid',
                                'payment_date' => date('Y-m-d H:i:s'),
                                'verification_response' => $payment_id,
                                'product_sid' => implode(',', $proucts_sid),
                                'credit_card_number' => $cc_number,
                                'credit_card_type' => $cc_type
                            );

                            if (!empty($coupon_array)) {
                                $orders_data = array_merge($orders_data, $coupon_array); // array merge
                                $invoice_data = array_merge($invoice_data, $coupon_array);
                            }

                            $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                            foreach ($ordered_products as $ordered_product) { // insert products details in DB
                                $this->ext_model->cc_add_product($order_id, $ordered_product);
                            }

                            $invoice_id = $this->ext_model->cc_add_invoice($invoice_data); // insert info at invoices table
                            $this->ext_model->empty_cart($company_sid); // empty cart and coupon info
                            //Fetching email template
                            //getting invoice selected product details
                            $products = "";
                            $this->load->model('manage_admin/invoice_model');
                            $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                            //Store Data for Product Usage Track
                            $purchased_products = $_POST['product'];
                            if (!empty($purchased_products)) {
                                foreach ($purchased_products as $product) {
                                    $product_sid = $product['id'];
                                    $quantity = $product['qty'];
                                    $name = $product['name'];
                                    $days = $product['no_of_days'];
                                    $price = $product['price'];

                                    $product_info = db_get_products_details($product_sid);

                                    if (isset($product_info['number_of_postings']) && $product_info['number_of_postings'] > 0) {
                                        $quantity = $quantity * $product_info['number_of_postings'];
                                    }

                                    for ($iCount = 1; $iCount <= $quantity; $iCount++) {
                                        $data_to_insert = array();
                                        $data_to_insert['company_sid'] = $company_sid;
                                        $data_to_insert['employer_sid'] = $employer_sid;
                                        $data_to_insert['invoice_sid'] = $invoice_id;
                                        $data_to_insert['product_sid'] = $product_sid;
                                        $data_to_insert['date_purchased'] = date('Y-m-d H:i:s');
                                        $data_to_insert['quantity_purchased'] = 1;
                                        $data_to_insert['product_name'] = $name;
                                        $data_to_insert['price'] = $price;
                                        $this->ext_model->insert_invoice_track_initial_record($data_to_insert);
                                    }
                                }
                            }

                            //Create Commission Invoice
                            $commission_invoice_sid = $this->admin_invoices_model->Save_commission_invoice($employer_sid, $company_sid, $product_sids, $id_to_rooftops, $id_to_quantity, 'manual', 'employer_portal');
                            //Update Commission Invoice Sid in Admin Invoices Table
                            $secondary_invoice = 0;

                            if (isset($commission_invoice_sid['secondary'])) {
                                $secondary_invoice = $commission_invoice_sid['secondary'];
                            }

                            $this->admin_invoices_model->update_commission_invoice_sid($invoice_id, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);
                            //Update Admin Invoice Sid in Commission Invoices Table
                            $this->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $invoice_id, $secondary_invoice);
                            //Update Discount in Commission Invoice Table
                            if ($total_discount > $order_amount) {
                                $total_discount = $order_amount;
                            }

                            $discount_percentage = ($total_discount / $order_amount) * 100;
                            $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['primary'], $discount_percentage, $total_discount, $order_final_total);
                            //Apply Discount On Commission
                            $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['primary']);
                            //Re Calculate Commission
                            $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

                            if (isset($commission_invoice_sid['secondary'])) { //Update Discount in Commission Invoice Table
                                if ($total_discount > $order_amount) {
                                    $total_discount = $order_amount;
                                }

                                $discount_percentage = ($total_discount / $order_amount) * 100;
                                $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['secondary'], $discount_percentage, $total_discount, $order_final_total);
                                //Apply Discount On Commission
                                $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['secondary']);
                                //Re Calculate Commission
                                $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['secondary']);
                            }

                            //Generate Receipt - Start
                            $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                            //Generate Receipt - End
                            $custom_data = unserialize($invoiceData["serialized_items_info"]);
                            $productsArray = explode(',', $invoiceData["product_sid"]);

                            for ($i = 0; $i < count($custom_data['products']); $i++) {
                                $custom_product_id = $custom_data['products'][$i];

                                if (strpos($custom_product_id, 'custom') === false) {
                                    $products_name = db_get_product_name($custom_product_id);
                                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                                } else {
                                    $products_name = $custom_data["custom_text"][$i];
                                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                                }
                            }
                            //Send Email Through Notifications Module - Start
                            $replacement_array = array();
                            $replacement_array['site_url'] = base_url();
                            $replacement_array['date'] = month_date_year(date('Y-m-d'));
                            $replacement_array['firstname'] = $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'];
                            $replacement_array['invoice_id'] = $invoice_id;
                            $replacement_array['product_list'] = $products;
                            $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
                            $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
                            $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

                            if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                                $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
                            } else {
                                $replacement_array['special_discount'] = '$' . '0.00';
                            }

                            if (!empty($invoiceData["invoice_description"])) {
                                $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
                            } else {
                                $replacement_array['invoice_description'] = 'No Description';
                            }

                            $company_sid = $invoiceData['company_sid'];
                            send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);
                            //Send Email Through Notifications Module - End
                            $this->session->set_flashdata('message', '<b>Success:</b> You have successfully purchased the product(s)');
                            //redirect('market_place', 'refresh');
                        }
                    }
                }
            } elseif (isset($data['is_free_checkout']) && intval($data['is_free_checkout']) == 1) {
                $payment_id = 11;
                $payment_state = 'approved';
                $purchased_date = date('Y-m-d H:i:s');

                if ($order_final_total > 0) {
                    // do nothing
                } else {
                    $order_final_total = 0;
                }

                if ($fb_api_product_flag == 'true') {
                    $this->load->model('manage_admin/company_model');
                    $select = 'expiry_date';
                    $fb_api_data = $this->company_model->get_company_facebook_api_detail($company_sid, $select);
                    $dataToUpdate['purchase_date'] = date('Y-m-d H:i:s');

                    if ($fb_api_data['expiry_date'] != NULL) {
                        $dataToUpdate['expiry_date'] = date('Y-m-d H:i:s', strtotime($fb_api_data['expiry_date'] . " + " . $fb_api_expiry_days . " days"));
                    } else {
                        $dataToUpdate['expiry_date'] = date('Y-m-d H:i:s', strtotime("+ " . $fb_api_expiry_days . " days"));
                    }

                    $this->company_model->updateFacebookStatus($company_sid, $dataToUpdate);
                }

                $orders_data = array();
                $orders_data = array(
                    'order_status' => 'paid',
                    'employer_sid' => $employer_sid,
                    'purchased_date' => $purchased_date,
                    'company_sid' => $company_sid,
                    'total' => $order_final_total,
                    'payment_method' => 'Free_checkout',
                    'verification_response' => $payment_id
                );

                $invoice_data = array();
                $invoice_data = array(
                    'user_sid' => $employer_sid,
                    'company_sid' => $company_sid,
                    'date' => $purchased_date,
                    'payment_method' => 'Free_checkout',
                    'total_discount' => $total_discount,
                    'sub_total' => $order_amount,
                    'total' => $order_final_total,
                    'serialized_items_info' => serialize($serialized_items_info),
                    'status' => 'Paid',
                    'payment_date' => date('Y-m-d H:i:s'),
                    'verification_response' => $payment_id,
                    'product_sid' => implode(',', $proucts_sid)
                );

                if (!empty($coupon_array)) {
                    $orders_data = array_merge($orders_data, $coupon_array); // array merge
                    $invoice_data = array_merge($invoice_data, $coupon_array);
                }

                $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                foreach ($ordered_products as $ordered_product) { // insert products details in DB
                    $this->ext_model->cc_add_product($order_id, $ordered_product);
                }

                $invoice_id = $this->ext_model->cc_add_invoice($invoice_data); // insert info at invoices table
                // empty cart and coupon info
                $this->ext_model->empty_cart($company_sid);
                //Fetching email template
                //getting invoice selected product details
                $products = "";
                $this->load->model('manage_admin/invoice_model');
                $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                //Generate Receipt - Start
                $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                //Generate Receipt - End
                $custom_data = unserialize($invoiceData["serialized_items_info"]);
                $productsArray = explode(',', $invoiceData["product_sid"]);

                for ($i = 0; $i < count($custom_data['products']); $i++) {
                    $custom_product_id = $custom_data['products'][$i];

                    if (strpos($custom_product_id, 'custom') === false) {
                        $products_name = db_get_product_name($custom_product_id);
                        $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                    } else {
                        $products_name = $custom_data["custom_text"][$i];
                        $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                    }
                }

                //Send Email Through Notifications Module - Start
                $replacement_array = array();
                $replacement_array['site_url'] = base_url();
                $replacement_array['date'] = month_date_year(date('Y-m-d'));
                $replacement_array['firstname'] = $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'];
                $replacement_array['invoice_id'] = $invoice_id;
                $replacement_array['product_list'] = $products;
                $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
                $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
                $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

                if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                    $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
                } else {
                    $replacement_array['special_discount'] = '$' . '0.00';
                }

                if (!empty($invoiceData["invoice_description"])) {
                    $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
                } else {
                    $replacement_array['invoice_description'] = 'No Description';
                }

                $company_sid = $invoiceData['company_sid'];
                send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);
                //Send Email Through Notifications Module - End

                $this->session->set_flashdata('message', '<b>Success:</b> You have successfully purchased the product(s)');
                //redirect('market_place', 'refresh');
            }

            if ($error_flag) {
                $array[0] = "error";
                $array[1] = 'no_error';
                $array[2] = $coupon_error;
                $array[3] = $card_error;
                echo json_encode($array);
                exit();
            } else {
                $array[0] = "success";
                $array[4] = $serialized_items_info;
                echo json_encode($array);
                exit();
            }
        }
    }

    function cc_apply_muba()
    {
        echo 'INnn<pre>';
        // getId(), getPayer(), getCart(), getTransactions(), getState(), getCreateTime()
        $creditCardToken = 'CARD-5VV12290X07720356K3BNG7Y';
        //$payment = makePaymentUsingCC($creditCardToken, '9', 'USD', 'custom order');

        $ccToken = new CreditCardToken();
        $ccToken->setCreditCardId($creditCardToken);

        $fi = new FundingInstrument();
        $fi->setCreditCardToken($ccToken);

        $payer = new Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($fi));
        // Specify the payment amount.
        $amount = new Amount();
        $amount->setCurrency('USD');
        $amount->setTotal('15');
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
        //getId(), getPayer(), getCart(), getTransactions(), getState(), getCreateTime()   
        echo $payment->$payment->getState();
    }

    function saveCard_muba()
    {
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
    function makeDirectPayment_muba()
    {
        // A resource representing a credit card that can be used to fund a payment.
        echo "procees direct Payment<br><br><br>";
        $card = new CreditCard();
        $card->setType('visa');
        $card->setNumber('4669424246660779'); //4669424246660779
        $card->setExpireMonth('03');
        $card->setExpireYear('2020');
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

        $card->setMerchantId("AHR_$company_sid");

        $logArray = [];
        $logArray['ccid'] = substr($params['cc_card_no'], -4);
        $logArray['request_json'] = json_encode($card);
        
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
     */
    function getCreditCard($cardId)
    {
        try {
            $card = CreditCard::get($cardId, $this->apiContext);
            return $card;
        } catch (Exception $ex) {
            return $ex;
            exit(1);
        }
    }

    /**
     *
     * @param string $cardId credit card id obtained from
     * a previous create API call.
     */
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
     * Create a payment using a previously obtained
     * cc id. The corresponding credit
     * card is used as the funding instrument.
     *
     * @param string $ccId cc id
     * @param string $total Payment amount with 2 decimal points
     * @param string $currency 3 letter ISO code for currency
     * @param string $paymentDesc
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
            // NOTE: PLEASE DO NOT USE RESULTPRINTER CLASS IN YOUR ORIGINAL CODE. FOR SAMPLE ONLY
            $logArray['response_json'] = 'error';
            $this->saveLog($logArray);
            return $ex;
        }
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
     */
    function executePayment($paymentId, $payerId)
    {
        $payment = getPaymentDetails($paymentId);
        $paymentExecution = new PaymentExecution();
        $paymentExecution->setPayerId($payerId);
        $payment = $payment->execute($paymentExecution, $this->apiContext);
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

    function cc_apply_mini()
    { //echo 'I AM IN MISC 1297 <pre>';
        $data = $_POST;
        $products_details = array();
        $product = $data['product'];
        $cc_future_payment = 'off';
        $has_coupon = false;
        $error_flag = false;
        $coupon_array = array();
        $response = array();
        $ordered_products = array();
        $serialized_items_info = array();
        $job_data = array();
        $coupon_error = '';
        $order_amount = 0;
        $order_final_total = 0;
        $total_discount = 0.00;
        $order_currency = 'USD';
        $order_description = '';
        $proucts_sid = array();
        $card_error = 'no_error';
        $userdata = $this->session->userdata('logged_in');
        $company_sid = $userdata['company_detail']['sid'];
        $employer_sid = $userdata['employer_detail']['sid'];
        $job_sid = $data['job_sid'];
        $purchased_date = date('Y-m-d H:i:s');

        if (isset($data['cc_future_payment'])) {
            $cc_future_payment = $data['cc_future_payment'];
        }

        if (isset($data['coupon_code'])) {
            $has_coupon = true;
            $coupon_code = $data['coupon_code'];
            $coupon_type = $data['coupon_type'];
            $coupon_discount = $data['coupon_discount'];
        }

        foreach ($product as $key => $value) {
            $product_id = $value['id'];
            $products_details[$key] = db_get_products_details($product_id);
            $products_details[$key]['no_of_days'] = $value['no_of_days'];
            //$products_details[$key]['qty']                            = 1;
        }

        //For Commission Invoice
        $id_to_quantity = array();
        $id_to_rooftops = array();
        $product_sids = array();
        $product_quantity = 0;

        if (!empty($products_details)) {
            foreach ($products_details as $key => $value) { // step 1 - check if the ordered product is still active or not

                if ($value['active'] == '0') { // the product is offline
                    $error_flag = true;
                    $response[] = $value['mid'];
                }
                //product_price, product_total, company_sid
                $product_qty = $value['number_of_postings'];
                $no_of_days = $value['no_of_days'];

                $daily = $value['daily'];

                if ($daily > 0) {
                    $expiry_days = $no_of_days;
                    $product_total = $value['price'] * $no_of_days;

                    //For Commission Invoice
                    $product_quantity = $no_of_days;
                } else {
                    $expiry_days = $value['expiry_days'];
                    $product_total = $value['price'];

                    //For Commission Invoice
                    $product_quantity = 1;
                }

                $cost_price = $this->ext_model->get_product_cost_price($value['sid']);

                $ordered_products[] = array(
                    'product_sid'                       => $value['sid'],
                    'product_qty'                       => $product_qty,
                    'product_remaining_qty'             => $product_qty,
                    'order_qty'                         => '1',
                    'product_price'                     => $value['price'],
                    'cost_price'                        => $cost_price,
                    'product_total'                     => $product_total,
                    'company_sid'                       => $company_sid
                );

                $serialized_items_info['custom_text'][]                         = '';
                $serialized_items_info['item_qty'][]                            = $product_qty;
                $serialized_items_info['item_price'][]                          = $product_total;
                $serialized_items_info['products'][]                            = $value['sid'];
                $serialized_items_info['item_remaining_qty'][]                  = $product_qty - 1;
                $serialized_items_info['no_of_days'][]                          = $expiry_days;
                $serialized_items_info['flag'][]                                = 'no_edit';
                $serialized_items_info['cost_price'][]                          = $cost_price;
                $serialized_items_info['total_cost'][]                          = isset($value['qty']) ? $cost_price * $value['qty'] : $cost_price;
                $order_amount                                                   = $order_amount + $product_total;
                $order_description                                              .= 'Product: 1 * ' . $value['name'] . ', ';
                $proucts_sid[]                                                  = $value['sid'];
                //For Commission Invoice
                $id_to_quantity[$value['sid']]                                  = $product_quantity;
                $id_to_rooftops[$value['sid']]                                  = 1;
                $product_sids[]                                                 = $value['sid'];
            }
        }

        //        echo json_encode($products_details);
        //        exit;
        if ($has_coupon) { // check if coupon code is applied
            $coupon_data = db_get_coupon_content($coupon_code);

            if (empty($coupon_data)) { // Coupon code does not exists or it is not active
                $error_flag = true;
                $coupon_error = 'coupon_error';
            } else {
                $coupon_data = $coupon_data[0];
                $discount = $coupon_data['discount'];
                $type = $coupon_data['type'];
                $maximum_uses = $coupon_data['maximum_uses'];
                $start_date = $coupon_data['start_date'];
                $end_date = $coupon_data['end_date'];

                if ($start_date != null) { // check whether coupon is started
                    $current_date_time = date('Y-m-d H:i:s');
                    if ($start_date > $current_date_time) { // Coupon code is not started yet.
                        $error_flag = true;
                        $coupon_error = 'coupon_error';
                    }
                }

                if ($end_date != null) { // check whether coupon has expired
                    $current_date_time = date('Y-m-d H:i:s');
                    if ($current_date_time > $end_date) { // coupon code has expired
                        $error_flag = true;
                        $coupon_error = 'coupon_error';
                    }
                }

                // need to figure out the maxium uses information
                if ($maximum_uses == null || $maximum_uses == 0) {
                    //it is umlimited, no need to perform any checks - APPLY CHECKS PLEASE
                }

                if ($type == 'fixed') {
                    $total_discount = $discount;
                } else {
                    $total_discount = round((($order_amount * $discount) / 100), 2);
                }
                // All well than assign the coupon and apply discount
                $coupon_array = array(
                    'coupon_code' => $coupon_code,
                    'coupon_discount' => $discount,
                    'coupon_type' => $type
                );
                $order_description .= 'Coupon Code: ' . $coupon_code . ', total_discount: ' . $total_discount . ', ';
            }
        }

        $order_description = substr($order_description, 0, -2);
        //print_r($this->apiContext);
        // array - 0 reserved for error flag or success flag
        // array - 1 reserved for Products error flag
        // array - 2 reserved for coupon code error flag
        // array - 3 reserved for paypal error flag
        // array - 4 reserved for produts array
        //exit;
        if ($error_flag) {
            $array[0] = "error";

            if (empty($response)) {
                $array[1] = 'no_error';
            } else {
                $array[1] = implode(",", $response);
            }

            $array[2] = $coupon_error;
            $array[3] = $card_error;
            $array[4] = $data;
            echo json_encode($array);
            exit();
        } else {
            $payment_id = 0;
            $payment_state = '';
            $purchased_date = date('Y-m-d H:i:s');

            if (isset($data['process_credit_card']) && intval($data['process_credit_card'] == 1)) {
                // there is no error found. now we can process the order and save the details
                //cardCARD-57C588728W287145BK26IVNI
                if (isset($data['cc_id']) && !empty($data['cc_id'])) { // make payment from saved cc
                    $mycard = db_get_cc_detail($data['cc_id'], $company_sid);

                    if (empty($mycard)) { // card not found, generate error
                        $error_flag = true;
                        $card_error = 'Error: Please select valid card for payment!';
                    } else { // process payment
                        $creditCardToken = trim($mycard['id']);
                    }
                } else { // make payment with new cc
                    $card = $this->savecard($data, $this->apiContext, $company_sid);

                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($card);
                        exit;
                    } else {
                        $creditCardToken = trim($card->getId());

                        if ($cc_future_payment != 'off') { // Save the credit card to vault and save its unique id to DB
                            $carddata = array();
                            $carddata = array(
                                'id'                            => $card->getId(),
                                'number'                        => $card->getNumber(),
                                'type'                          => $card->getType(),
                                'expire_month'                  => $card->getExpireMonth(),
                                'expire_year'                   => $card->getExpireYear(),
                                'merchant_id'                   => $card->getMerchantId(),
                                'state'                         => $card->getState()
                            );
                            $this->ext_model->cc_future_store($carddata, $company_sid, $employer_sid);
                        }
                    }
                }

                if (!empty($creditCardToken)) {
                    $order_final_total = $order_amount - $total_discount;
                    // getId(), getPayer(), getCart(), getTransactions(), getState(), getCreateTime()
                    //sending email to DEV starts
                    $from = FROM_EMAIL_ACCOUNTS;
                    $to = TO_EMAIL_DEV;
                    $subject = "Inner Cart Order";
                    $body = 'employer sid: ' . $employer_sid . "<br>"
                        . 'customer name: ' . $userdata["employer_detail"]['username'] . "<br>"
                        . 'order total: ' . $order_final_total . "<br>"
                        . 'total discount: ' . $total_discount . "<br>";
                    sendMail($from, $to, $subject, $body, STORE_NAME);
                    //sending email to DEV ends
                    $payment = $this->makePaymentUsingCC($creditCardToken, $order_final_total, $order_currency, 'AHR_Payments', $this->apiContext);

                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($payment);
                        exit;
                    } else {
                        //$payment_id = 11;
                        //$payment_state = 'approved';
                        //$purchased_date = date('Y-m-d H:i:s');
                        $payment_id = trim($payment->getId());
                        $payment_state = trim($payment->getState());
                        $purchased_date = date('Y-m-d H:i:s');
                    }
                }
                if ($payment_state == 'approved') { // payment was successful, add to db and show success message
                    $payer = $payment->getPayer();
                    $fi = $payer->getFundingInstruments();
                    $cc_token = $fi[0]->getCreditCardToken();

                    $last4 = $cc_token->getLast4();
                    $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                    $cc_type = $cc_token->getType();

                    $orders_data = array();
                    $orders_data = array(
                        'order_status'                      => 'paid',
                        'employer_sid'                      => $employer_sid,
                        'purchased_date'                    => $purchased_date,
                        'company_sid'                       => $company_sid,
                        'total'                             => $order_final_total,
                        'payment_method'                    => 'Paypal',
                        'verification_response'             => $payment_id
                    );
                    $invoice_data = array();
                    $invoice_data = array(
                        'user_sid'                          => $employer_sid,
                        'company_sid'                       => $company_sid,
                        'date'                              => $purchased_date,
                        'payment_method'                    => 'Paypal',
                        'total_discount'                    => $total_discount,
                        'sub_total'                         => $order_amount,
                        'total'                             => $order_final_total,
                        'serialized_items_info'             => serialize($serialized_items_info),
                        'status'                            => 'Paid',
                        'payment_date'                      => date('Y-m-d H:i:s'),
                        'verification_response'             => $payment_id,
                        'product_sid'                       => implode(',', $proucts_sid),
                        'credit_card_number'                => $cc_number,
                        'credit_card_type'                  => $cc_type
                    );

                    if (!empty($coupon_array)) {
                        $orders_data                                            = array_merge($orders_data, $coupon_array); // array merge
                        $invoice_data                                           = array_merge($invoice_data, $coupon_array);
                    }

                    $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                    foreach ($ordered_products as $ordered_product) { // insert products details in DB
                        $this->ext_model->cc_add_product($order_id, $ordered_product);
                    }

                    foreach ($products_details as $key => $value) { // insert data to feeds
                        $purchased_date = date('Y-m-d H:i:s');
                        $daily = $value['daily'];

                        if ($daily > 0) {
                            $expiry_days = $value['no_of_days'];
                            $budget = $value['price'] * $expiry_days;
                        } else {
                            $expiry_days = $value['expiry_days'];
                            $budget = $value['price'];
                        }

                        $job_data = array(
                            'job_sid' => $job_sid,
                            'employer_sid' => $employer_sid,
                            'purchased_date' => $purchased_date,
                            'product_sid' => $value['sid'],
                            //'expiry_date' => date('Y-m-d H:i:s', strtotime("+" . $expiry_days . " days")), //New Scenario to Set the Expiry Date from Super Admin Upon Activation instead store Number of Days
                            'budget' => $budget,
                            'no_of_days' => $expiry_days,
                            'company_sid' => $company_sid
                        );
                        $this->ext_model->insertJobFeed($job_data); //hassan working area
                    }
                    // insert info at invoices table
                    $invoice_id = $this->ext_model->cc_add_invoice($invoice_data);
                    //Store Data for Product Usage Track
                    $purchased_products = $_POST['product'];

                    foreach ($purchased_products as $product) {
                        $product_sid = $product['id'];
                        $quantity = isset($product['qty']) ? $product['qty'] : 1;
                        $name = isset($product['name']) ? $product['name'] : 'Product id ' . $product['id'] . ' Applied from Mini cart.';
                        $days = $product['no_of_days'];
                        $price = $product['price'];
                        $product_info = db_get_products_details($product_sid);

                        if (isset($product_info['number_of_postings']) && $product_info['number_of_postings'] > 0) {
                            $quantity = $quantity * $product_info['number_of_postings'];
                        }

                        for ($iCount = 1; $iCount <= $quantity; $iCount++) {
                            $data_to_insert = array();
                            $data_to_insert['company_sid'] = $company_sid;
                            $data_to_insert['employer_sid'] = $employer_sid;
                            $data_to_insert['invoice_sid'] = $invoice_id;
                            $data_to_insert['product_sid'] = $product_sid;
                            $data_to_insert['date_purchased'] = date('Y-m-d H:i:s');
                            $data_to_insert['quantity_purchased'] = 1;
                            $data_to_insert['product_name'] = $name;
                            $data_to_insert['price'] = $price;
                            $this->ext_model->insert_invoice_track_initial_record($data_to_insert);
                            //New Job Products Tracking
                            $this->load->model('dashboard_model');
                            $this->dashboard_model->mark_product_as_used($product_sid, $company_sid, $employer_sid, $job_sid);
                        }
                    }

                    //getting invoice selected product details
                    $products = "";
                    $this->load->model('manage_admin/invoice_model');
                    $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                    //Create Commission Invoice
                    $commission_invoice_sid = $this->admin_invoices_model->Save_commission_invoice($employer_sid, $company_sid, $product_sids, $id_to_rooftops, $id_to_quantity, 'manual', 'employer_portal');
                    $secondary_invoice = 0;

                    if (isset($commission_invoice_sid['secondary'])) {
                        $secondary_invoice = $commission_invoice_sid['secondary'];
                    }

                    //Update Commission Invoice Sid in Admin Invoices Table
                    $this->admin_invoices_model->update_commission_invoice_sid($invoice_id, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);
                    //Update Admin Invoice Sid in Commission Invoices Table
                    $this->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $invoice_id, $secondary_invoice);
                    //Update Discount in Commission Invoice Table
                    if ($total_discount > $order_amount) {
                        $total_discount = $order_amount;
                    }

                    $discount_percentage = ($total_discount / $order_amount) * 100;
                    $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['primary'], $discount_percentage, $total_discount, $order_final_total);
                    //Apply Discount On Commission
                    $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['primary']);
                    //Re Calculate Commission
                    $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

                    if (isset($commission_invoice_sid['secondary'])) {
                        if ($total_discount > $order_amount) {
                            $total_discount = $order_amount;
                        }

                        $discount_percentage = ($total_discount / $order_amount) * 100;
                        $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['secondary'], $discount_percentage, $total_discount, $order_final_total);
                        //Apply Discount On Commission
                        $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['secondary']);
                        //Re Calculate Commission
                        $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['secondary']);
                    }

                    //Generate Receipt - Start
                    $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                    //Generate Receipt - End
                    $custom_data = unserialize($invoiceData["serialized_items_info"]);
                    $productsArray = explode(',', $invoiceData["product_sid"]);

                    for ($i = 0; $i < count($custom_data['products']); $i++) {
                        $custom_product_id = $custom_data['products'][$i];

                        if (strpos($custom_product_id, 'custom') === false) {
                            $products_name = db_get_product_name($custom_product_id);
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        } else {
                            $products_name = $custom_data["custom_text"][$i];
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        }
                    }

                    //Send Email Through Notifications Module - Start
                    $replacement_array = array();
                    $replacement_array['site_url'] = base_url();
                    $replacement_array['date'] = month_date_year(date('Y-m-d'));
                    $replacement_array['firstname'] = $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'];
                    $replacement_array['invoice_id'] = $invoice_id;
                    $replacement_array['product_list'] = $products;
                    $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
                    $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
                    $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

                    if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                        $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
                    } else {
                        $replacement_array['special_discount'] = '$' . '0.00';
                    }

                    if (!empty($invoiceData["invoice_description"])) {
                        $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
                    } else {
                        $replacement_array['invoice_description'] = 'No Description';
                    }

                    $company_sid = $invoiceData['company_sid'];
                    send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);
                    //Send Email Through Notifications Module - End
                    // empty cart and coupon info
                    $this->session->set_flashdata('message', '<b>Success:</b> You have successfully purchased the Job Board(s) and Job(s) are pending approval from Administrator');
                    //redirect('market_place', 'refresh');
                }
            } elseif (isset($data['is_free_checkout_mini']) && intval($data['is_free_checkout_mini']) == 1) {
                $payment_id = 11;
                $payment_state = 'approved';
                $purchased_date = date('Y-m-d H:i:s');

                $orders_data = array();
                $orders_data = array(
                    'order_status' => 'paid',
                    'employer_sid' => $employer_sid,
                    'purchased_date' => $purchased_date,
                    'company_sid' => $company_sid,
                    'total' => $order_final_total,
                    'payment_method' => 'Free_checkout',
                    'verification_response' => $payment_id
                );

                $invoice_data = array();
                $invoice_data = array(
                    'user_sid' => $employer_sid,
                    'company_sid' => $company_sid,
                    'date' => $purchased_date,
                    'payment_method' => 'Free_checkout',
                    'total_discount' => $total_discount,
                    'sub_total' => $order_amount,
                    'total' => $order_final_total,
                    'serialized_items_info' => serialize($serialized_items_info),
                    'status' => 'Paid',
                    'payment_date' => date('Y-m-d H:i:s'),
                    'verification_response' => $payment_id,
                    'product_sid' => implode(',', $proucts_sid)
                );

                if (!empty($coupon_array)) {
                    $orders_data = array_merge($orders_data, $coupon_array); // array merge
                    $invoice_data = array_merge($invoice_data, $coupon_array);
                }

                $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                foreach ($ordered_products as $ordered_product) { // insert products details in DB
                    $this->ext_model->cc_add_product($order_id, $ordered_product);
                }

                foreach ($products_details as $key => $value) { // insert data to feeds
                    $purchased_date = date('Y-m-d H:i:s');
                    $daily = $value['daily'];

                    if ($daily > 0) {
                        $expiry_days = $value['no_of_days'];
                        $budget = $value['price'] * $expiry_days;
                    } else {
                        $expiry_days = $value['expiry_days'];
                        $budget = $value['price'];
                    }

                    $job_data = array(
                        'job_sid' => $job_sid,
                        'employer_sid' => $employer_sid,
                        'purchased_date' => $purchased_date,
                        'product_sid' => $value['sid'],
                        //'expiry_date' => date('Y-m-d H:i:s', strtotime("+" . $expiry_days . " days")), 
                        'budget' => $budget,
                        'no_of_days' => $expiry_days,
                        'company_sid' => $company_sid
                    );

                    $this->ext_model->insertJobFeed($job_data);
                }

                $invoice_id = $this->ext_model->cc_add_invoice($invoice_data); // insert info at invoices table
                //sending invoice email
                //Fetching email template
                //getting invoice selected product details
                $products = "";
                $this->load->model('manage_admin/invoice_model');
                $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                //Generate Receipt - Start
                $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                //Generate Receipt - End
                $custom_data = unserialize($invoiceData["serialized_items_info"]);
                $productsArray = explode(',', $invoiceData["product_sid"]);

                for ($i = 0; $i < count($custom_data['products']); $i++) {
                    $custom_product_id = $custom_data['products'][$i];

                    if (strpos($custom_product_id, 'custom') === false) {
                        $products_name = db_get_product_name($custom_product_id);
                        $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                    } else {
                        $products_name = $custom_data["custom_text"][$i];
                        $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                    }
                }
                //Send Email Through Notifications Module - Start
                $replacement_array = array();
                $replacement_array['site_url'] = base_url();
                $replacement_array['date'] = month_date_year(date('Y-m-d'));
                $replacement_array['firstname'] = $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'];
                $replacement_array['invoice_id'] = $invoice_id;
                $replacement_array['product_list'] = $products;
                $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
                $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
                $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

                if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                    $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
                } else {
                    $replacement_array['special_discount'] = '$' . '0.00';
                }

                if (!empty($invoiceData["invoice_description"])) {
                    $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
                } else {
                    $replacement_array['invoice_description'] = 'No Description';
                }

                $company_sid = $invoiceData['company_sid'];
                send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);
                //Send Email Through Notifications Module - End
                // empty cart and coupon info
                $this->session->set_flashdata('message', '<b>Success:</b> You have successfully purchased the Job Board(s) and Job(s) are pending approval from Administrator');
                //redirect('market_place', 'refresh');
            }

            if ($error_flag) {
                $array[0] = "error";
                $array[1] = 'no_error';
                $array[2] = $coupon_error;
                $array[3] = $card_error;
                echo json_encode($array);
                exit();
            } else {
                $array[0] = "success";
                $array[4] = $serialized_items_info;
                echo json_encode($array);
                exit();
            }
        }
    }

    function account_package_payment()
    {
        $data = $_POST;
        $products_details = array();
        $cc_future_payment = 'off';
        $has_coupon = false;
        $error_flag = false;
        $coupon_array = array();
        $response = array();
        $ordered_products = array();
        $serialized_items_info = array();
        $coupon_error = '';
        $order_amount = 0;
        $order_final_total = 0;
        $special_discount = 0;
        $total_discount = 0.00;
        $order_currency = 'USD';
        $order_description = '';
        $proucts_sid = array();
        $card_error = 'no_error';

        if (isset($data['employer_sid']) && !empty($data['employer_sid'])) {
            $userdata = get_employer_company_data_by_employer_id($data['employer_sid']);
            $company_sid = $userdata["company_detail"]["sid"];
            $employer_sid = $userdata["employer_detail"]["sid"];

            if (isset($data['cc_future_payment'])) {
                $cc_future_payment = $data['cc_future_payment'];
            }

            if (isset($data['coupon_code'])) {
                $has_coupon = true;
                $coupon_code = $data['coupon_code'];
                $coupon_type = $data['coupon_type'];
                $coupon_discount = $data['coupon_discount'];
            }

            if (isset($data['product_id'])) {
                $product_id = $data['product_id'];
                $products_details = db_get_products_details($product_id);
                $products_details['qty'] = 1;
                $products_details['no_of_days'] = $products_details['expiry_days'];

                //Special discount functionality starts
                if ($userdata["company_detail"]['discount_amount'] > 0) {
                    if ($userdata["company_detail"]['discount_type'] == 'fixed') {
                        $special_discount = $userdata["company_detail"]['discount_amount'];
                    } else {
                        $special_discount = round((($products_details['price'] * $userdata["company_detail"]['discount_amount']) / 100), 2);
                    }
                }
                //Special discount functionality ends
            }

            if (!empty($products_details)) {
                // step 1 - check if the ordered product is still active or not
                //product_price, product_total, company_sid
                $no_of_days = $products_details['no_of_days'];
                $product_qty = $products_details['number_of_postings'] * $products_details['qty'];
                $daily = $products_details['daily'];

                if ($daily > 0) {
                    $expiry_days = $no_of_days;
                    $product_total = $products_details['price'] * $products_details['qty'] * $no_of_days;
                } else {
                    $expiry_days = $products_details['expiry_days'];
                    $product_total = $products_details['price'] * $products_details['qty'];
                }

                $cost_price = $this->ext_model->get_product_cost_price($products_details['sid']);

                $ordered_products[] = array(
                    'product_sid' => $products_details['sid'],
                    'product_qty' => $product_qty,
                    'product_remaining_qty' => $product_qty,
                    'order_qty' => $products_details['qty'],
                    'product_price' => $products_details['price'],
                    'cost_price' => $cost_price,
                    'product_total' => $product_total,
                    'company_sid' => $company_sid
                );

                $serialized_items_info['custom_text'][] = '';
                $serialized_items_info['item_qty'][] = $product_qty;
                $serialized_items_info['item_price'][] = $product_total;
                $serialized_items_info['products'][] = $products_details['sid'];
                $serialized_items_info['item_remaining_qty'][] = $product_qty - 1;
                $serialized_items_info['no_of_days'][] = $expiry_days;
                $serialized_items_info['flag'][] = 'no_edit';
                $serialized_items_info['special_discount'] = $special_discount;
                $order_amount = $order_amount + $product_total;
                $order_description .= 'Product: ' . $products_details['qty'] . ' * ' . $products_details['name'] . ', ';
                $proucts_sid[] = $products_details['sid'];
            }

            if ($has_coupon) { // check if coupon code is applied
                $coupon_data = db_get_coupon_content($coupon_code);

                if (empty($coupon_data)) { // Coupon code does not exists or it is not active
                    $error_flag = true;
                    $coupon_error = 'coupon_error';
                } else {
                    $coupon_data = $coupon_data[0];
                    $discount = $coupon_data['discount'];
                    $type = $coupon_data['type'];
                    $maximum_uses = $coupon_data['maximum_uses'];
                    $start_date = $coupon_data['start_date'];
                    $end_date = $coupon_data['end_date'];

                    if ($start_date != null) { // check whether coupon is started
                        $current_date_time = date('Y-m-d H:i:s');

                        if ($start_date > $current_date_time) { // Coupon code is not started yet.
                            $error_flag = true;
                            $coupon_error = 'coupon_error';
                        }
                    }

                    if ($end_date != null) { // check whether coupon has expired
                        $current_date_time = date('Y-m-d H:i:s');

                        if ($current_date_time > $end_date) { // coupon code has expired
                            $error_flag = true;
                            $coupon_error = 'coupon_error';
                        }
                    }

                    // need to figure out the maxium uses information
                    if ($maximum_uses == null || $maximum_uses == 0) {
                        //it is umlimited, no need to perform any checks - APPLY CHECKS PLEASE
                    }
                    if ($type == 'fixed') {
                        $total_discount = $discount;
                    } else {
                        $total_discount = round((($order_amount * $discount) / 100), 2);
                    }
                    // All well than assign the coupon and apply discount
                    $coupon_array = array(
                        'coupon_code' => $coupon_code,
                        'coupon_discount' => $discount,
                        'coupon_type' => $type
                    );
                    $order_description .= 'Coupon Code: ' . $coupon_code . ', total_discount: ' . $total_discount . ', ';
                }
            }

            $order_description = substr($order_description, 0, -2);

            if (!empty($coupon_error)) {
                $this->session->unset_userdata('coupon_data');
            }

            if ($error_flag) {
                $array[0] = "error";

                if (empty($response)) {
                    $array[1] = 'no_error';
                } else {
                    $array[1] = implode(",", $response);
                }

                $array[2] = $coupon_error;
                $array[3] = $card_error;
                $array[4] = $data;
                echo json_encode($array);
                exit();
            } else {
                // there is no error found. now we can process the order and save the details
                //cardCARD-57C588728W287145BK26IVNI
                if (isset($data['cc_id']) && !empty($data['cc_id'])) { // make payment from saved cc
                    $mycard = db_get_cc_detail($data['cc_id'], $company_sid);

                    if (empty($mycard)) { // card not found, generate error
                        $error_flag = true;
                        $card_error = 'Error: Please select valid card for payment!';
                    } else { // process payment
                        $creditCardToken = trim($mycard['id']);
                    }
                } else { // make payment with new cc
                    $card = $this->savecard($data, $this->apiContext, $company_sid);

                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($card);
                        exit;
                    } else {
                        $creditCardToken = trim($card->getId());

                        if ($cc_future_payment != 'off') { // Save the credit card to vault and save its unique id to DB
                            $carddata = array();
                            $carddata = array(
                                'id' => $card->getId(),
                                'number' => $card->getNumber(),
                                'type' => $card->getType(),
                                'expire_month' => $card->getExpireMonth(),
                                'expire_year' => $card->getExpireYear(),
                                'merchant_id' => $card->getMerchantId(),
                                'state' => $card->getState()
                            );
                            $this->ext_model->cc_future_store($carddata, $company_sid, $employer_sid);
                        }
                    }
                }

                if (!empty($creditCardToken)) {
                    $order_final_total = round($order_amount - $total_discount - $special_discount, 2);
                    $from = FROM_EMAIL_ACCOUNTS;
                    $to = TO_EMAIL_DEV;
                    $subject = "Main Cart Order";
                    $body = 'employer sid: ' . $employer_sid . "<br>"
                        . 'customer name: ' . $userdata["employer_detail"]['username'] . "<br>"
                        . 'order total: ' . $order_final_total . "<br>"
                        . 'total discount: ' . $total_discount . "<br>"
                        . 'special discount: ' . $special_discount . "<br>";

                    sendMail($from, $to, $subject, $body);
                    //sending email to DEV ends
                    $payment = $this->makePaymentUsingCC($creditCardToken, $order_final_total, $order_currency, 'AHR_Payments', $this->apiContext);

                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($payment);
                        exit;
                    } else {
                        $payment_id = trim($payment->getId());
                        $payment_state = trim($payment->getState());
                        $purchased_date = date('Y-m-d H:i:s');

                        if ($payment_state == 'approved') { // payment was successful, add to db and show success message  
                            //Extending Expiry date of account according to product days STARTS
                            $dataToUpdate['expiry_date'] = date('Y-m-d H:i:s', strtotime("+" . $products_details['no_of_days'] . " days"));
                            //activacting that company after payment
                            $dataToUpdate['active'] = 1;
                            $this->load->model('dashboard_model');
                            $this->dashboard_model->update_user($company_sid, $dataToUpdate);
                            //portal Activate after expiry update
                            $this->db->query("UPDATE `portal_employer` SET `status` = '1' WHERE `user_sid` = '" . $company_sid . "'");
                            //Check if the PRODUCT contain enterprise theme activation too?
                            $enterpriseProductArray = explode(',', ENTERPRISE_THEME_PACKAGE);

                            if (in_array($product_id, $enterpriseProductArray)) {
                                $dataToUpdateThemes['purchased'] = 1;
                                $this->load->model('appearance_model');
                                $this->appearance_model->updateTheme($company_sid, $dataToUpdateThemes);
                            } else {
                                $this->load->model('manage_admin/company_model');
                                $select = 'theme_status';
                                $theme_status = $this->company_model->get_company_theme_detail($company_sid, $select);

                                if ($theme_status) {
                                    $this->company_model->updateToActiveOtherTheme($company_sid, array('theme_status' => 1));
                                    $dataToUpdateThemes['theme_status'] = 0;
                                }

                                $dataToUpdateThemes['purchased'] = 0;
                                $this->company_model->updateEnterpriseTheme($company_sid, $dataToUpdateThemes);
                            }
                            //Extending Expiry date of account according to product days ENDS
                            $orders_data = array();
                            $orders_data = array(
                                'order_status' => 'paid',
                                'employer_sid' => $employer_sid,
                                'purchased_date' => $purchased_date,
                                'company_sid' => $company_sid,
                                'total' => $order_final_total,
                                'payment_method' => 'Paypal',
                                'verification_response' => $payment_id
                            );
                            $invoice_data = array();
                            $invoice_data = array(
                                'user_sid' => $employer_sid,
                                'company_sid' => $company_sid,
                                'date' => $purchased_date,
                                'payment_method' => 'Paypal',
                                'total_discount' => $total_discount,
                                'sub_total' => $order_amount,
                                'total' => $order_final_total,
                                'serialized_items_info' => serialize($serialized_items_info),
                                'status' => 'Paid',
                                'payment_date' => date('Y-m-d H:i:s'),
                                'verification_response' => $payment_id,
                                'product_sid' => implode(',', $proucts_sid)
                            );

                            if (!empty($coupon_array)) {
                                $orders_data = array_merge($orders_data, $coupon_array); // array merge
                                $invoice_data = array_merge($invoice_data, $coupon_array);
                            }

                            $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                            foreach ($ordered_products as $ordered_product) { // insert products details in DB
                                $this->ext_model->cc_add_product($order_id, $ordered_product);
                            }

                            $invoice_id = $this->ext_model->cc_add_invoice($invoice_data); // insert info at invoices table
                            $this->ext_model->empty_cart($company_sid); // empty cart and coupon info
                            //Fetching email template
                            //getting invoice selected product details
                            $products = "";
                            $this->load->model('manage_admin/invoice_model');
                            $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                            //Generate Receipt - Start
                            $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                            //Generate Receipt - End
                            $custom_data = unserialize($invoiceData["serialized_items_info"]);
                            $productsArray = explode(',', $invoiceData["product_sid"]);

                            for ($i = 0; $i < count($custom_data['products']); $i++) {
                                $custom_product_id = $custom_data['products'][$i];

                                if (strpos($custom_product_id, 'custom') === false) {
                                    $products_name = db_get_product_name($custom_product_id);
                                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                                } else {
                                    $products_name = $custom_data["custom_text"][$i];
                                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                                }
                            }

                            $emailTemplateData = get_email_template(INVOICE_NOTIFICATION);
                            $emailTemplateBody = $emailTemplateData['text'];

                            if (!empty($emailTemplateBody)) {
                                $emailTemplateBody = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{firstname}}', $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{invoice_id}}', $invoice_id, $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{product_list}}', $products, $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{invoice_subtotal}}', '$' . $invoiceData["sub_total"], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{discount}}', '$' . $invoiceData["total_discount"], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{invoice_total}}', '$' . $invoiceData["total"], $emailTemplateBody);

                                if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                                    $emailTemplateBody = str_replace('{{special_discount}}', '$' . $custom_data["special_discount"], $emailTemplateBody);
                                } else {
                                    $emailTemplateBody = str_replace('{{special_discount}}', '$' . '0.00', $emailTemplateBody);
                                }

                                if ($invoiceData["invoice_description"] != '') {
                                    $emailTemplateBody = str_replace('{{invoice_description}}', $invoiceData["invoice_description"], $emailTemplateBody);
                                } else {
                                    $emailTemplateBody = str_replace('{{invoice_description}}', 'No Description', $emailTemplateBody);
                                }
                            }

                            $from = $emailTemplateData['from_email'];
                            $to = $userdata["company_detail"]['email'];
                            $subject = $emailTemplateData['subject'];
                            $from_name = $emailTemplateData['from_name'];

                            $body = EMAIL_HEADER
                                . $emailTemplateBody
                                . EMAIL_FOOTER;

                            sendMail($from, $to, $subject, $body, $from_name);
                            sendMail($from, TO_EMAIL_DEV, $subject, $body, $from_name);
                            $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    sendMail($from, $system_notification_email['email'], $subject, $body, $from_name);
                                }
                            }

                            $emailLog['subject'] = $subject;
                            $emailLog['email'] = $to;
                            $emailLog['message'] = $body;
                            $emailLog['date'] = date('Y-m-d H:i:s');
                            $emailLog['admin'] = 'admin';
                            $emailLog['status'] = 'Delivered';
                            save_email_log_common($emailLog);
                            $this->session->set_flashdata('message', '<b>Success:</b> You have successfully purchased the product(s)');
                            //redirect('market_place', 'refresh');
                        }
                    }
                }

                if ($error_flag) {
                    $array[0] = "error";
                    $array[1] = 'no_error';
                    $array[2] = $coupon_error;
                    $array[3] = $card_error;
                    echo json_encode($array);
                    exit();
                } else {
                    $array[0] = "success 123";
                    $array[4] = $serialized_items_info;
                    echo json_encode($array);
                    exit();
                }
            }
        }
    }

    function enterprise_theme_payment()
    {
        $data = $_POST;
        $products_details = array();
        $cc_future_payment = 'off';
        $has_coupon = false;
        $error_flag = false;
        $coupon_array = array();
        $response = array();
        $ordered_products = array();
        $serialized_items_info = array();
        $coupon_error = '';
        $order_amount = 0;
        $order_final_total = 0;
        $total_discount = 0.00;
        $order_currency = 'USD';
        $order_description = '';
        $proucts_sid = array();
        $card_error = 'no_error';

        if (isset($data['employer_sid']) && !empty($data['employer_sid'])) {
            $userdata = get_employer_company_data_by_employer_id($data['employer_sid']);
            $company_sid = $userdata["company_detail"]["sid"];
            $employer_sid = $userdata["employer_detail"]["sid"];

            if (isset($data['cc_future_payment'])) {
                $cc_future_payment = $data['cc_future_payment'];
            }

            if (isset($data['coupon_code'])) {
                $has_coupon = true;
                $coupon_code = $data['coupon_code'];
                $coupon_type = $data['coupon_type'];
                $coupon_discount = $data['coupon_discount'];
            }

            if (isset($data['product_id'])) {
                $product_id = $data['product_id'];
                $products_details = db_get_products_details($product_id);
                $company_expiry_days = get_company_expiry_days($userdata["company_detail"]['expiry_date']);
                $products_details['expiry_days'] = $company_expiry_days;
                $products_details['price'] = $company_expiry_days * $products_details['price'];
                $products_details['qty'] = 1;
                $products_details['no_of_days'] = $products_details['expiry_days'];
            }

            if (!empty($products_details)) {
                // step 1 - check if the ordered product is still active or not
                //product_price, product_total, company_sid
                $no_of_days = $products_details['no_of_days'];
                $product_qty = $products_details['number_of_postings'] * $products_details['qty'];
                $daily = $products_details['daily'];

                if ($daily > 0) {
                    $expiry_days = $no_of_days;
                    $product_total = $products_details['price'] * $products_details['qty'] * $no_of_days;
                } else {
                    $expiry_days = $products_details['expiry_days'];
                    $product_total = $products_details['price'] * $products_details['qty'];
                }

                $cost_price = $this->ext_model->get_product_cost_price($products_details['sid']);
                $ordered_products[] = array('product_sid' => $products_details['sid'], 'product_qty' => $product_qty, 'product_remaining_qty' => $product_qty, 'order_qty' => $products_details['qty'], 'product_price' => $products_details['price'], 'cost_price' => $cost_price, 'product_total' => $product_total, 'company_sid' => $company_sid);

                $serialized_items_info['custom_text'][] = '';
                $serialized_items_info['item_qty'][] = $product_qty;
                $serialized_items_info['item_price'][] = $product_total;
                $serialized_items_info['products'][] = $products_details['sid'];
                $serialized_items_info['item_remaining_qty'][] = $product_qty - 1;
                $serialized_items_info['no_of_days'][] = $expiry_days;
                $serialized_items_info['flag'][] = 'no_edit';
                $order_amount = $order_amount + $product_total;
                $order_description .= 'Product: ' . $products_details['qty'] . ' * ' . $products_details['name'] . ', ';
                $proucts_sid[] = $products_details['sid'];
            }

            if ($has_coupon) { // check if coupon code is applied
                $coupon_data = db_get_coupon_content($coupon_code);

                if (empty($coupon_data)) { // Coupon code does not exists or it is not active
                    $error_flag = true;
                    $coupon_error = 'coupon_error';
                } else {
                    $coupon_data = $coupon_data[0];
                    $discount = $coupon_data['discount'];
                    $type = $coupon_data['type'];
                    $maximum_uses = $coupon_data['maximum_uses'];
                    $start_date = $coupon_data['start_date'];
                    $end_date = $coupon_data['end_date'];

                    if ($start_date != null) { // check whether coupon is started
                        $current_date_time = date('Y-m-d H:i:s');

                        if ($start_date > $current_date_time) { // Coupon code is not started yet.
                            $error_flag = true;
                            $coupon_error = 'coupon_error';
                        }
                    }

                    if ($end_date != null) { // check whether coupon has expired
                        $current_date_time = date('Y-m-d H:i:s');
                        if ($current_date_time > $end_date) { // coupon code has expired
                            $error_flag = true;
                            $coupon_error = 'coupon_error';
                        }
                    }

                    if ($maximum_uses == null || $maximum_uses == 0) { // need to figure out the maxium uses information
                        //it is umlimited, no need to perform any checks - APPLY CHECKS PLEASE
                    }

                    if ($type == 'fixed') {
                        $total_discount = $discount;
                    } else {
                        $total_discount = round((($order_amount * $discount) / 100), 2);
                    }
                    // All well than assign the coupon and apply discount
                    $coupon_array = array(
                        'coupon_code' => $coupon_code,
                        'coupon_discount' => $discount,
                        'coupon_type' => $type
                    );
                    $order_description .= 'Coupon Code: ' . $coupon_code . ', total_discount: ' . $total_discount . ', ';
                }
            }

            $order_description = substr($order_description, 0, -2);

            if (!empty($coupon_error)) {
                $this->session->unset_userdata('coupon_data');
            }

            if ($error_flag) {
                $array[0] = "error";

                if (empty($response)) {
                    $array[1] = 'no_error';
                } else {
                    $array[1] = implode(",", $response);
                }

                $array[2] = $coupon_error;
                $array[3] = $card_error;
                $array[4] = $data;
                echo json_encode($array);
                exit();
            } else {
                // there is no error found. now we can process the order and save the details
                //cardCARD-57C588728W287145BK26IVNI
                if (isset($data['cc_id']) && !empty($data['cc_id'])) { // make payment from saved cc
                    $mycard = db_get_cc_detail($data['cc_id'], $company_sid);

                    if (empty($mycard)) { // card not found, generate error
                        $error_flag = true;
                        $card_error = 'Error: Please select valid card for payment!';
                    } else { // process payment
                        $creditCardToken = trim($mycard['id']);
                    }
                } else { // make payment with new cc
                    $card = $this->savecard($data, $this->apiContext, $company_sid);

                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($card);
                        exit;
                    } else {
                        $creditCardToken = trim($card->getId());

                        if ($cc_future_payment != 'off') { // Save the credit card to vault and save its unique id to DB
                            $carddata = array();
                            $carddata = array(
                                'id' => $card->getId(),
                                'number' => $card->getNumber(),
                                'type' => $card->getType(),
                                'expire_month' => $card->getExpireMonth(),
                                'expire_year' => $card->getExpireYear(),
                                'merchant_id' => $card->getMerchantId(),
                                'state' => $card->getState()
                            );
                            $this->ext_model->cc_future_store($carddata, $company_sid, $employer_sid);
                        }
                    }
                }

                if (!empty($creditCardToken)) {
                    $order_final_total = $order_amount - $total_discount;

                    $from = FROM_EMAIL_ACCOUNTS;
                    $to = TO_EMAIL_DEV;
                    $subject = "Main Cart Order";
                    $body = 'employer sid: ' . $employer_sid . "<br>"
                        . 'customer name: ' . $userdata["employer_detail"]['username'] . "<br>"
                        . 'order total: ' . $order_final_total . "<br>"
                        . 'total discount: ' . $total_discount . "<br>";

                    sendMail($from, $to, $subject, $body);

                    //sending email to DEV ends
                    $payment = $this->makePaymentUsingCC($creditCardToken, $order_final_total, $order_currency, 'AHR_Payments', $this->apiContext);
                    if ($error_flag) { // check if there was error while card save
                        $card_error = 'Error: There was some error while processing payment!';
                        echo json_encode($payment);
                        exit;
                    } else {
                        $payment_id = trim($payment->getId());
                        $payment_state = trim($payment->getState());
                        $purchased_date = date('Y-m-d H:i:s');

                        if ($payment_state == 'approved') { // payment was successful, add to db and show success message  
                            //Activating theme-4 against company STARTS
                            $dataToUpdate['purchased'] = 1;
                            $this->load->model('manage_admin/company_model');
                            $this->company_model->updateEnterpriseTheme($company_sid, $dataToUpdate);
                            //Activating theme-4 against company ENDS
                            //Other data to save invoice
                            $orders_data = array();
                            $orders_data = array(
                                'order_status' => 'paid',
                                'employer_sid' => $employer_sid,
                                'purchased_date' => $purchased_date,
                                'company_sid' => $company_sid,
                                'total' => $order_final_total,
                                'payment_method' => 'Paypal',
                                'verification_response' => $payment_id
                            );
                            $invoice_data = array();
                            $invoice_data = array(
                                'user_sid' => $employer_sid,
                                'company_sid' => $company_sid,
                                'date' => $purchased_date,
                                'payment_method' => 'Paypal',
                                'total_discount' => $total_discount,
                                'sub_total' => $order_amount,
                                'total' => $order_final_total,
                                'serialized_items_info' => serialize($serialized_items_info),
                                'status' => 'Paid',
                                'payment_date' => date('Y-m-d H:i:s'),
                                'verification_response' => $payment_id,
                                'product_sid' => implode(',', $proucts_sid)
                            );

                            if (!empty($coupon_array)) {
                                $orders_data = array_merge($orders_data, $coupon_array); // array merge
                                $invoice_data = array_merge($invoice_data, $coupon_array);
                            }

                            $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                            foreach ($ordered_products as $ordered_product) { // insert products details in DB
                                $this->ext_model->cc_add_product($order_id, $ordered_product);
                            }
                            // insert info at invoices table
                            $invoice_id = $this->ext_model->cc_add_invoice($invoice_data);

                            $this->ext_model->empty_cart($company_sid); // empty cart and coupon info
                            //Fetching email template
                            //getting invoice selected product details
                            $products = "";
                            $this->load->model('manage_admin/invoice_model');
                            $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                            //Generate Receipt - Start
                            $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                            //Generate Receipt - End
                            $custom_data = unserialize($invoiceData["serialized_items_info"]);
                            $productsArray = explode(',', $invoiceData["product_sid"]);

                            for ($i = 0; $i < count($custom_data['products']); $i++) {
                                $custom_product_id = $custom_data['products'][$i];

                                if (strpos($custom_product_id, 'custom') === false) {
                                    $products_name = db_get_product_name($custom_product_id);
                                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                                } else {
                                    $products_name = $custom_data["custom_text"][$i];
                                    $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                                }
                            }

                            $emailTemplateData = get_email_template(INVOICE_NOTIFICATION);
                            $emailTemplateBody = $emailTemplateData['text'];

                            if (!empty($emailTemplateBody)) {
                                $emailTemplateBody = str_replace('{{site_url}}', base_url(), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{date}}', month_date_year(date('Y-m-d')), $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{firstname}}', $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{invoice_id}}', $invoice_id, $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{product_list}}', $products, $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{invoice_subtotal}}', '$' . $invoiceData["sub_total"], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{discount}}', '$' . $invoiceData["total_discount"], $emailTemplateBody);
                                $emailTemplateBody = str_replace('{{invoice_total}}', '$' . $invoiceData["total"], $emailTemplateBody);

                                if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                                    $emailTemplateBody = str_replace('{{special_discount}}', '$' . $custom_data["special_discount"], $emailTemplateBody);
                                } else {
                                    $emailTemplateBody = str_replace('{{special_discount}}', '$' . '0.00', $emailTemplateBody);
                                }

                                if ($invoiceData["invoice_description"] != '') {
                                    $emailTemplateBody = str_replace('{{invoice_description}}', $invoiceData["invoice_description"], $emailTemplateBody);
                                } else {
                                    $emailTemplateBody = str_replace('{{invoice_description}}', 'No Description', $emailTemplateBody);
                                }
                            }

                            $from = $emailTemplateData['from_email'];
                            $to = $userdata["company_detail"]['email'];
                            $subject = $emailTemplateData['subject'];
                            $from_name = $emailTemplateData['from_name'];

                            $body = EMAIL_HEADER
                                . $emailTemplateBody
                                . EMAIL_FOOTER;

                            sendMail($from, $to, $subject, $body, $from_name);
                            sendMail($from, TO_EMAIL_DEV, $subject, $body, $from_name);
                            $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    sendMail($from, $system_notification_email['email'], $subject, $body, $from_name);
                                }
                            }
                            //Send Emails Through System Notifications Email - End
                            //saving email log
                            $emailLog['subject'] = $subject;
                            $emailLog['email'] = $to;
                            $emailLog['message'] = $body;
                            $emailLog['date'] = date('Y-m-d H:i:s');
                            $emailLog['admin'] = 'admin';
                            $emailLog['status'] = 'Delivered';
                            save_email_log_common($emailLog);
                            $this->session->set_flashdata('message', '<b>Success:</b> You have successfully purchased the product(s)');
                            //redirect('market_place', 'refresh');
                        }
                    }
                }

                if ($error_flag) {
                    $array[0] = "error";
                    $array[1] = 'no_error';
                    $array[2] = $coupon_error;
                    $array[3] = $card_error;
                    echo json_encode($array);
                    exit();
                } else {
                    $array[0] = "success";
                    $array[4] = $serialized_items_info;
                    echo json_encode($array);
                    exit();
                }
            }
        }
    }

    function process_payment_admin_invoice($invoice_sid = 0)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'dashboard', 'application_tracking'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
            //$company_sid                                                        = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);
            $company_sid = $invoice_data['company_sid'];
            $credit_cards = $this->ext_model->get_all_company_cards($company_sid, 1);
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
                //
                if ($this->input->post('redirect_url')) {
                    return redirect($this->input->post('redirect_url'), 'refresh');
                }
                $data['invoice'] = $invoice_data;
                $data['user_cc'] = $credit_cards;
                $data['title'] = 'Process Payment';
                //$data['admin_user_id'] = $admin_user_id;
                $this->load->view('main/header', $data);
                $this->load->view('manage_employer/process_payment_admin_invoice');
                $this->load->view('main/footer');
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
                        $payment = $this->makeDirectPayment($card_params, $invoice_amount, 'USD', 'Payment Against Invoice # ' . $invoice_data['invoice_number'], $company_sid, $this->apiContext);
                    }
                }

                if (is_array($payment)) {

                    if (isset($payment['error_message'])) {
                        $this->session->set_flashdata('message', $payment['error_message']);
                    } else {
                        $this->session->set_flashdata('message', 'Payment could not be processed due to unknown error!');
                    }
                    //
                    if ($this->input->post('redirect_url')) {
                        return redirect($this->input->post('redirect_url'), 'refresh');
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
                //
                if ($this->input->post('redirect_url')) {
                    return redirect($this->input->post('redirect_url'), 'refresh');
                }
                redirect('settings/list_packages_addons_invoices', 'refresh');
            }
        } else {
            redirect('login', "refresh");
        }
    }

    public function cc_management()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'cc_management');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = "Credit Card Management";


            if (isset($_POST['save_card']) && $_POST['save_card'] == 'save_card') {
                $formpost = $_POST;
                $contractor_first_name = $data['session']['employer_detail']['first_name'];
                $contractor_last_name = $data['session']['employer_detail']['last_name'];
                $carddata = array();
                $carddata['number'] = $_POST['number'];
                $carddata['type'] = $_POST['type'];
                $carddata['expire_month'] = $_POST['expire_month'];
                $carddata['expire_year'] = $_POST['expire_year'];
                $carddata['contractor_first_name'] = $contractor_first_name;
                $carddata['$contractor_last_name'] = $contractor_last_name;
                $card = $this->cc_management_saveCard($carddata, $this->apiContext, $company_sid); // save card to vault

                if (is_array($card)) {
                    if (isset($card['error_message'])) {
                        $this->session->set_flashdata('message', $card['error_message']);
                    } else {
                        $this->session->set_flashdata('message', 'Card could not be saved due to unknown error!');
                    }

                    redirect('cc_management', 'refresh');
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
                        'merchant_id' => trim($card->getMerchantId()),
                        'state' => $card_state,
                        'name_on_card' => $_POST['name_on_card']
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
                    $carddetails["address_details"] = serialize($custom_data);


                    $emailTemplateBody =  'Dear Steven, <br><br>';
                    $emailTemplateBody = $emailTemplateBody . " I wanted to inform you that " . $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'] . " has recently added a new credit card to our company's account under the name " . $data['session']['company_detail']['CompanyName'] . " . The card was added on " . formatDateToDB(date('Y-m-d H:i:s'), DB_DATE_WITH_TIME, DATE_WITH_TIME) . ". <br>";
                    $emailTemplateBody = $emailTemplateBody . " I am writing to provide you with the details of the new card so that you can keep track of any transactions that may occur.<br>";
                    $emailTemplateBody = $emailTemplateBody . " Here is the card information you need to know: <br>";
                    $emailTemplateBody = $emailTemplateBody . " Card Number: " . $carddetails['number'] . " <br>";
                    $emailTemplateBody = $emailTemplateBody . " Best regards, <br>  AutomotoHR Team";


                    $from = FROM_EMAIL_NOTIFICATIONS; //$emailTemplateData['from_email'];
                    $to = TO_EMAIL_STEVEN;
                    $subject = 'New Card Is Added'; //$emailTemplateData['subject'];
                    $from_name = ucwords(STORE_DOMAIN); //$emailTemplateData['from_name'];

                    $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;

                    //
                    sendMail($from, $to, $subject, $body, $from_name);
                    sendMail($from, DEV_EMAIL_PM, $subject, $body, $from_name);

                    $this->ext_model->cc_future_store($carddetails, $company_sid, $employer_sid);
                    $this->session->set_flashdata('success', 'Success, Your card has successfully saved!');
                    redirect('cc_management', "refresh");
                } else {
                    $this->session->set_flashdata('error', 'Error, Please try again!');
                    redirect('cc_management', "refresh");
                }
            }
            //getiing all cards of the company
            $data['cards'] = $this->ext_model->get_all_company_cards($company_sid, 1);
            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/cc_management');
            $this->load->view('main/footer');
            //$this->load->view('manage_employer/cc_management', $data);
        } else {
            $data['snapshot']['class'] = $this->router->fetch_class();
            $data['snapshot']['method'] = $this->router->fetch_method();
            $data['snapshot']['url'] = current_url();
            $this->session->set_userdata('snapshot', $data);
            redirect('login', "refresh");
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
                "error_message" => $message
            );  // Filtering by MerchantId set during CreateCreditCard.

            return $card;
        } catch (Exception $ex) {
            $messageType = "error";
            $error_flag = true;
            $card = array(
                "error_status" => "error",
                "error_message" => $ex
            );  // Filtering by MerchantId set during CreateCreditCard.

            return $card;
        }
    }

    public function my_ajax_responder()
    {
        if ($this->session->userdata('logged_in')) {
            if ($_POST) {
                if (isset($_POST['perform_action']) && $_POST['perform_action'] != '') {
                    $perform_action = strtoupper($_POST['perform_action']);

                    switch ($perform_action) {
                        case 'MAKE_DEFAULT_CARD':
                            $data['session'] = $this->session->userdata('logged_in');
                            $company_sid = $data['session']['company_detail']['sid'];
                            $card_id = $_POST['card_id'];
                            //making all the cards not-default
                            $table = 'emp_cards';
                            $where = array('company_sid' => $company_sid);
                            $dataToUpdate = array('is_default' => 0);
                            update_from_table($table, $where, $dataToUpdate);
                            //making one card default
                            $dataToUpdate = array('is_default' => 1);
                            $where = array('sid' => $card_id);
                            update_from_table($table, $where, $dataToUpdate);
                            echo 'success';
                            break;
                        case 'DELETE_CARD':
                            $card_id = $_POST['card_id'];
                            $cc_id = $this->db->select('id')->where('sid', $card_id)->get('emp_cards')->row_array();
                            $this->ext_model->delete_credit_card($card_id);
                            $this->deleteCreditCard($cc_id['id']);
                            echo 'success';
                            break;
                    }
                }
            }
        }
    }

    public function edit_card($card_sid = 0)
    {
        if ($card_sid != 0) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $data['title'] = "Edit Credit Card";
            $data['card'] = db_get_cc_detail($card_sid); //getiing cards detail
            $data['card']['address_details'] = unserialize($data['card']['address_details']);

            if (isset($_POST['update_card']) && $_POST['update_card'] == 'update_card') {
                $this->load->model('ext_model');
                $formpost = $_POST;
                $card = $this->updateCreditCard($data['card']['id'], $_POST['expire_month'], $_POST['expire_year']); //updating card

                if (is_array($card)) {
                    if (isset($card['error_message'])) {
                        $this->session->set_flashdata('message', $card['error_message']);
                    } else {
                        $this->session->set_flashdata('message', 'Card could not be saved due to unknown error!');
                    }

                    redirect('edit_card/' . $card_sid, 'refresh');
                }

                $card_state = trim($card->getState());

                if (strtolower($card_state) == 'ok') { // card is fine
                    $creditCardToken = trim($card->getId());
                    $carddetails = array();
                    $carddetails = array(
                        'name_on_card' => $_POST['name_on_card'],
                        'expire_month' => $_POST['expire_month'],
                        'expire_year' => $_POST['expire_year'],
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

                    $this->ext_model->update_card($card_sid, $carddetails);

                    $emailTemplateBody =  'Dear Steven, <br><br>';
                    $emailTemplateBody = $emailTemplateBody . " I am pleased to inform you that our company's account under the name " . $data['session']['company_detail']['CompanyName'] . " has been recently updated by " . $data['session']['employer_detail']['first_name'] . ' ' . $data['session']['employer_detail']['last_name'] . ".";
                    $emailTemplateBody = $emailTemplateBody . " Under an existing credit card " . $data['card']['number'] . " . The change was made on " . formatDateToDB(date('Y-m-d H:i:s'), DB_DATE_WITH_TIME, DATE_WITH_TIME) . ".<br>";
                    $emailTemplateBody = $emailTemplateBody . " Best regards, <br>";
                    $emailTemplateBody = $emailTemplateBody . " AutomotoHR Team ";


                    $from = FROM_EMAIL_NOTIFICATIONS; //$emailTemplateData['from_email'];
                    $to = TO_EMAIL_STEVEN;
                    $subject = 'Existing Card Is Updated'; //$emailTemplateData['subject'];
                    $from_name = ucwords(STORE_DOMAIN); //$emailTemplateData['from_name'];

                    $body = EMAIL_HEADER
                        . $emailTemplateBody
                        . EMAIL_FOOTER;

                    //
                    sendMail($from, $to, $subject, $body, $from_name);
                    sendMail($from, DEV_EMAIL_PM, $subject, $body, $from_name);

                    $this->session->set_flashdata('success', 'Success, Your card has successfully updated!');
                    redirect('cc_management', "refresh");
                } else {
                    $this->session->set_flashdata('error', 'Error, Please try again!');
                    redirect('cc_management', "refresh");
                }
            }

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/cc_edit');
            $this->load->view('main/footer');
        } else {
            redirect('manage_admin/companies', "refresh");
        }
    }

    public function process_payment_pending_invoices()
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'pending_invoices');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];

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
            $this->form_validation->set_rules('invoice_sid[]', 'Invoice Sid', 'required');

            if ($this->form_validation->run() == false) {
            } else {
                $company_admin_user = db_get_first_admin_user($company_sid);
                $prev_saved_cc = $this->input->post('prev_saved_cc');
                $invoice_sid = $this->input->post('invoice_sid');
                $invoice_amount = $this->input->post('invoice_amount');
                $company_sid = $this->input->post('company_sid');

                if (intval($prev_saved_cc > 0)) { //Process using Previously saved Card
                    $saved_card_details = db_get_cc_detail($prev_saved_cc, $company_sid);
                    $creditCardToken = trim($saved_card_details['id']);
                    $payment = $this->makePaymentUsingCC($creditCardToken, $invoice_amount, 'USD', 'Against Invoice sids ( ' . implode(', ', $invoice_sid) . ' ) ', $this->apiContext);
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
                        $creditCardToken = $card->getId();
                        $payment = $this->makePaymentUsingCC($creditCardToken, $invoice_amount, 'USD', 'Against Invoice sids ( ' . implode(', ', $invoice_sid) . ' ) ', $this->apiContext);
                    } else { //Directrly Process Credit Card for Payment.
                        $card_params = array();
                        $card_params['cc_type'] = $cc_type;
                        $card_params['cc_card_no'] = $cc_number;
                        $card_params['cc_expire_month'] = $cc_expiration_month;
                        $card_params['cc_expire_year'] = $cc_expiration_year;
                        $card_params['cc_ccv'] = $cc_ccv;
                        $card_params['first_name'] = $company_admin_user['first_name'];
                        $card_params['last_name'] = $company_admin_user['last_name'];
                        $payment = $this->makeDirectPayment($card_params, $invoice_amount, 'USD', 'Against Invoice sids ( ' . implode(', ', $invoice_sid) . ' ) ', $company_sid, $this->apiContext);
                    }
                }


                if (is_array($payment)) {
                    if (isset($payment['error_message'])) {
                        $this->session->set_flashdata('message', $payment['error_message']);
                    } else {
                        $this->session->set_flashdata('message', 'Payment could not be processed due to unknown error!');
                    }

                    redirect('settings/pending_invoices', 'refresh');
                } else if (is_object($payment)) {
                    $payment_state = strtolower($payment->getState());

                    if ($payment_state == 'approved') {
                        $payer = $payment->getPayer();
                        $fi = $payer->getFundingInstruments();
                        $cc_token = $fi[0]->getCreditCardToken();
                        $last4 = $cc_token->getLast4();
                        $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                        $cc_type = $cc_token->getType();
                        $data_to_update = array();
                        $data_to_update['payment_status'] = 'paid';
                        $data_to_update['payment_date'] = date('Y-m-d H:i:s');
                        $data_to_update['payment_method'] = 'credit-card';
                        $data_to_update['payment_processed_by'] = $employer_sid;
                        $data_to_update['credit_card_number'] = $cc_number;
                        $data_to_update['credit_card_type'] = $cc_type;
                        $invoices = $this->ext_model->get_admin_invoices($invoice_sid);

                        foreach ($invoices as $invoice) {
                            $this->admin_invoices_model->update_admin_invoice($invoice['sid'], $data_to_update);
                            $invoice_amount = $invoice['total_after_discount'];
                            $this->receipts_model->generate_new_receipt($company_sid, $invoice['sid'], $invoice_amount, 'Paypal', $employer_sid, 'employer_portal', 'admin_invoice');
                            $transactions = $payment->getTransactions();
                            $relatedResources = $transactions[0]->getRelatedResources();
                            $sale = $relatedResources[0]->getSale();
                            $saleId = $sale->getId();
                            $this->admin_invoices_model->Mark_automatic_invoice_as_processed_on_manual_payment($invoice['sid'], $payment_state, $saleId);
                            activate_invoice_features($company_sid, $invoice['sid']);
                            $replacement_array = array();
                            $replacement_array['invoice_number'] = $invoice['invoice_number'];
                            $replacement_array['company_admin'] = ucwords($company_admin_user['first_name'] . ' ' . $company_admin_user['last_name']);
                            $replacement_array['payment_method'] = 'Credit Card';
                            $replacement_array['payment_date'] = convert_date_to_frontend_format(date('Y/m/d h:i:s'));
                            $replacement_array['invoice'] = generate_invoice_html($invoice['sid']);
                            $system_notification_emails = get_system_notification_emails('billing_and_invoice_emails');

                            if (!empty($system_notification_emails)) {
                                foreach ($system_notification_emails as $system_notification_email) {
                                    log_and_send_templated_email(ADMIN_INVOICE_PAYMENT_NOTIFICATION, $system_notification_email['email'], $replacement_array);
                                }
                            }

                            $this->session->set_flashdata('message', 'Payment Successfully Processed!');
                        }
                    } else {
                        $this->session->set_flashdata('message', 'Could not process Payment!');
                    }

                    redirect('settings/pending_invoices', 'refresh');
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function pay_per_job()
    { //echo 'I AM IN MISC 1297 <pre>';
        $data = $_POST;
        $userdata = $this->session->userdata('logged_in');
        $company_sid = $userdata['company_detail']['sid'];
        $employer_sid = $userdata['employer_detail']['sid'];
        $first_name = $userdata['employer_detail']['first_name'];
        $last_name = $userdata['employer_detail']['last_name'];
        $product_id = $data['ppj_id'];

        $cc_future_payment = 'off';
        $has_coupon = false;
        $error_flag = false;
        $coupon_array = array();
        $response = array();
        $ordered_products = array();
        $serialized_items_info = array();
        $coupon_error = '';
        $order_amount = 0;
        $order_final_total = 0;
        $total_discount = 0.00;
        $order_currency = 'USD';
        $order_description = '';
        $proucts_sid = array();
        $card_error = 'no_error';
        $job_sid = 0;
        $purchased_date = date('Y-m-d H:i:s');
        $card_params = array();
        //For Commission Invoice
        $id_to_quantity = array();
        $id_to_rooftops = array();
        $product_sids = array();
        $product_quantity = 0;
        $products = '';

        if ($product_id > 0) {
            $products_details = db_get_products_details($product_id);
            $products_details['qty'] = 1;
            $products_details['no_of_days'] = $products_details['expiry_days'];
        }

        if (!empty($products_details)) { // step 1 - check if the ordered product is still active or not
            $no_of_days = $products_details['no_of_days'];
            $product_qty = $products_details['number_of_postings'] * $products_details['qty'];
            $active = $products_details['active'];

            if ($active == '0') { // the product is offline
                $error_flag = true;
                $response[] = 'Product is no longer available!';
            }

            $daily = $products_details['daily'];

            if ($daily > 0) {
                $expiry_days = $no_of_days;
                $product_total = $products_details['price'] * $products_details['qty'] * $no_of_days;
                $product_quantity = $no_of_days; //For Commission Invoice
            } else {
                $expiry_days = $products_details['expiry_days'];
                $product_total = $products_details['price'] * $products_details['qty'];
                $product_quantity = 1; //For Commission Invoice
            }

            $cost_price = $this->ext_model->get_product_cost_price($products_details['sid']);

            $ordered_products[] = array(
                'product_sid'                           => $products_details['sid'],
                'product_qty'                           => $product_qty,
                'product_remaining_qty'                 => $product_qty,
                'order_qty'                             => $products_details['qty'],
                'product_price'                         => $products_details['price'],
                'cost_price'                            => $cost_price,
                'product_total'                         => $product_total,
                'company_sid'                           => $company_sid
            );

            $serialized_items_info['custom_text'][]                             = '';
            $serialized_items_info['item_qty'][]                                = $product_qty;
            $serialized_items_info['item_price'][]                              = $product_total;
            $serialized_items_info['products'][]                                = $products_details['sid'];
            $serialized_items_info['item_remaining_qty'][]                      = $product_qty;
            $serialized_items_info['no_of_days'][]                              = $expiry_days;
            $serialized_items_info['flag'][]                                    = 'no_edit';
            $serialized_items_info['cost_price'][]                              = $cost_price;
            $serialized_items_info['total_cost'][]                              = isset($products_details['qty']) ? $cost_price * $products_details['qty'] : $cost_price;
            $order_amount                                                       = $order_amount + $product_total;
            $order_description                                                  .= 'Product: 1 * ' . $products_details['name'];
            $proucts_sid[]                                                      = $products_details['sid'];
            //For Commission Invoice
            $id_to_quantity[$products_details['sid']]                           = $product_quantity;
            $id_to_rooftops[$products_details['sid']]                           = 1;
            $product_sids[]                                                     = $products_details['sid'];
        }

        $order_final_total                                                      = $order_amount;

        if (isset($data['cc_future_payment'])) {
            $cc_future_payment = $data['cc_future_payment'];
        }

        if (isset($data['coupon_code'])) {
            $has_coupon = true;
            $coupon_code = $data['coupon_code'];
            $coupon_type = $data['coupon_type'];
            $coupon_discount = $data['coupon_discount'];
        }

        if ($has_coupon) { // check if coupon code is applied
            $coupon_data = db_get_coupon_content($coupon_code);

            if (empty($coupon_data)) { // Coupon code does not exists or it is not active
                $error_flag = true;
                $coupon_error = 'coupon_error';
            } else {
                $coupon_data = $coupon_data[0];
                $discount = $coupon_data['discount'];
                $type = $coupon_data['type'];
                $maximum_uses = $coupon_data['maximum_uses'];
                $start_date = $coupon_data['start_date'];
                $end_date = $coupon_data['end_date'];

                if ($start_date != null) { // check whether coupon is started
                    $current_date_time = date('Y-m-d H:i:s');
                    if ($start_date > $current_date_time) { // Coupon code is not started yet.
                        $error_flag = true;
                        $coupon_error = 'coupon_error';
                    }
                }

                if ($end_date != null) { // check whether coupon has expired
                    $current_date_time = date('Y-m-d H:i:s');

                    if ($current_date_time > $end_date) { // coupon code has expired
                        $error_flag = true;
                        $coupon_error = 'coupon_error';
                    }
                }

                // need to figure out the maxium uses information
                if ($maximum_uses == null || $maximum_uses == 0) {
                    //it is umlimited, no need to perform any checks - APPLY CHECKS PLEASE
                }

                if ($type == 'fixed') {
                    $total_discount = $discount;
                } else {
                    $total_discount = round((($order_amount * $discount) / 100), 2);
                }

                $coupon_array = array(
                    'coupon_code' => $coupon_code,
                    'coupon_discount' => $discount,
                    'coupon_type' => $type
                );
                $order_description .= 'Coupon Code: ' . $coupon_code . ', total_discount: ' . $total_discount . ', ';
                $order_final_total = $order_amount - $total_discount;
            }
        }

        // array - 0 reserved for error flag or success flag
        // array - 1 reserved for Products error flag
        // array - 2 reserved for coupon code error flag
        // array - 3 reserved for paypal error flag
        // array - 4 reserved for produts array
        //exit;
        if ($error_flag) {
            $array[0] = "error";

            if (empty($response)) {
                $array[1] = 'no_error';
            } else {
                $array[1] = implode(",", $response);
            }

            $array[2] = $coupon_error;
            $array[3] = $card_error;
            $array[4] = $data;
            echo json_encode($array);
            exit();
        } else {
            if ($data['payment_type'] == 'new') { //make direct payment
                $card_params['cc_type'] = $data['cc_type'];
                $card_params['cc_card_no'] = $data['cc_card_no'];
                $card_params['cc_expire_month'] = $data['cc_expire_month'];
                $card_params['cc_expire_year'] = $data['cc_expire_year'];
                $card_params['cc_ccv'] = $data['cc_ccv'];
                $card_params['first_name'] = $first_name;
                $card_params['last_name'] = $last_name;
                $payment = $this->makeDirectPayment($card_params, $product_total, 'USD', $order_description, $company_sid, $this->apiContext);
            } else { // make payment using stored card
                $mycard = db_get_cc_detail($data['payment_type'], $company_sid);
                $creditCardToken = trim($mycard['id']);
                $payment = $this->makePaymentUsingCC($creditCardToken, $product_total, 'USD', $order_description, $this->apiContext);
            }

            if (is_object($payment)) {
                $payment_state = strtolower($payment->getState());

                if ($payment_state == 'approved') { // payment was successful, add to db and show success message
                    $payment_id = trim($payment->getId());
                    $payment_state = trim($payment->getState());
                    $purchased_date = date('Y-m-d H:i:s');
                    $payer = $payment->getPayer();
                    $fi = $payer->getFundingInstruments();
                    $cc_token = $fi[0]->getCreditCardToken();

                    if (is_object($cc_token)) {
                        $last4 = $cc_token->getLast4();
                        $cc_number = str_pad($last4, '16', 'X', STR_PAD_LEFT);
                        $cc_type = $cc_token->getType();
                    } else {
                        $last4 = '';
                        $cc_number = '';
                        $cc_type = '';
                    }

                    $orders_data = array();
                    $orders_data = array(
                        'order_status'                      => 'paid',
                        'employer_sid'                      => $employer_sid,
                        'purchased_date'                    => $purchased_date,
                        'company_sid'                       => $company_sid,
                        'total'                             => $order_final_total,
                        'payment_method'                    => 'Paypal',
                        'verification_response'             => $payment_id
                    );
                    $invoice_data = array();
                    $invoice_data = array(
                        'user_sid'                          => $employer_sid,
                        'company_sid'                       => $company_sid,
                        'date'                              => $purchased_date,
                        'payment_method'                    => 'Paypal',
                        'total_discount'                    => $total_discount,
                        'sub_total'                         => $order_amount,
                        'total'                             => $order_final_total,
                        'serialized_items_info'             => serialize($serialized_items_info),
                        'status'                            => 'Paid',
                        'payment_date'                      => $purchased_date,
                        'verification_response'             => $payment_id,
                        'product_sid'                       => implode(',', $proucts_sid),
                        'credit_card_number'                => $cc_number,
                        'credit_card_type'                  => $cc_type
                    );

                    if (!empty($coupon_array)) {
                        $orders_data                                            = array_merge($orders_data, $coupon_array); // array merge
                        $invoice_data                                           = array_merge($invoice_data, $coupon_array);
                    }

                    $order_id = $this->ext_model->cc_add_order($orders_data); // insert query and get order id

                    foreach ($ordered_products as $ordered_product) { // insert products details in DB
                        $this->ext_model->cc_add_product($order_id, $ordered_product);
                    }

                    $invoice_id = $this->ext_model->cc_add_invoice($invoice_data);
                    $commission_invoice_sid = $this->admin_invoices_model->Save_commission_invoice($employer_sid, $company_sid, $product_sids, $id_to_rooftops, $id_to_quantity, 'manual', 'employer_portal');
                    $secondary_invoice = 0;

                    if (isset($commission_invoice_sid['secondary'])) {
                        $secondary_invoice = $commission_invoice_sid['secondary'];
                    }

                    $this->admin_invoices_model->update_commission_invoice_sid($invoice_id, $commission_invoice_sid['primary'], 'admin_invoice', $secondary_invoice);
                    $this->admin_invoices_model->update_invoice_sid_in_commission_invoice($commission_invoice_sid['primary'], $invoice_id, $secondary_invoice);
                    //Update Discount in Commission Invoice Table
                    if ($total_discount > $order_amount) {
                        $total_discount = $order_amount;
                    }

                    $discount_percentage = ($total_discount / $order_amount) * 100;
                    $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['primary'], $discount_percentage, $total_discount, $order_final_total);
                    $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['primary']);
                    $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['primary']);

                    if (isset($commission_invoice_sid['secondary'])) {
                        if ($total_discount > $order_amount) {
                            $total_discount = $order_amount;
                        }

                        $discount_percentage = ($total_discount / $order_amount) * 100;
                        $this->admin_invoices_model->Update_commission_invoice_discount($commission_invoice_sid['secondary'], $discount_percentage, $total_discount, $order_final_total);
                        $this->admin_invoices_model->apply_discount_on_commission($commission_invoice_sid['secondary']);
                        $this->marketing_agencies_model->recalculate_commission($commission_invoice_sid['secondary']);
                    }

                    $this->load->model('manage_admin/invoice_model');
                    $invoiceData = $this->invoice_model->get_invoice_detail($invoice_id);
                    $this->receipts_model->generate_new_receipt($company_sid, $invoice_id, $invoiceData['total'], $invoiceData['payment_method'], $employer_sid, 'employer_portal', 'market_place');
                    $custom_data = unserialize($invoiceData["serialized_items_info"]);
                    $productsArray = explode(',', $invoiceData["product_sid"]);

                    for ($i = 0; $i < count($custom_data['products']); $i++) {
                        $custom_product_id = $custom_data['products'][$i];

                        if (strpos($custom_product_id, 'custom') === false) {
                            $products_name = db_get_product_name($custom_product_id);
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        } else {
                            $products_name = $custom_data["custom_text"][$i];
                            $products .= $products_name . " = $" . $custom_data['item_price'][$i] . "<br><br>";
                        }
                    }

                    $replacement_array = array();
                    $replacement_array['site_url'] = base_url();
                    $replacement_array['date'] = month_date_year(date('Y-m-d'));
                    $replacement_array['firstname'] = $userdata["employer_detail"]['first_name'] . ' ' . $userdata["employer_detail"]['last_name'];
                    $replacement_array['invoice_id'] = $invoice_id;
                    $replacement_array['product_list'] = $products;
                    $replacement_array['invoice_subtotal'] = '$' . $invoiceData["sub_total"];
                    $replacement_array['discount'] = '$' . $invoiceData["total_discount"];
                    $replacement_array['invoice_total'] = '$' . $invoiceData["total"];

                    if (isset($custom_data["special_discount"]) && floatval($custom_data["special_discount"]) > 0) {
                        $replacement_array['special_discount'] = '$' . $custom_data["special_discount"];
                    } else {
                        $replacement_array['special_discount'] = '$' . '0.00';
                    }

                    if (!empty($invoiceData["invoice_description"])) {
                        $replacement_array['invoice_description'] = $invoiceData["invoice_description"];
                    } else {
                        $replacement_array['invoice_description'] = 'No Description';
                    }

                    $company_sid = $invoiceData['company_sid'];
                    send_email_through_notifications($company_sid, 'billing_invoice', INVOICE_NOTIFICATION, $replacement_array);
                }
            }

            if ($error_flag) {
                $array[0] = "error";
                $array[1] = 'no_error';
                $array[2] = $coupon_error;
                $array[3] = $card_error;
                echo json_encode($array);
                exit();
            } else {
                $array[0] = "success";
                $array[4] = $serialized_items_info;
                echo json_encode($array);
                exit();
            }
        }
    }

    function process_payment_admin_public_invoice($invoice_sid)
    {
        //
        $company_sid = $this->input->post('company_sid', true);

        $invoice_data = $this->admin_invoices_model->Get_admin_invoice($invoice_sid);
        $credit_cards = $this->ext_model->get_all_company_cards($company_sid, 1);
        $company_admin_user = db_get_first_admin_user($company_sid);

        $employer_sid = $company_admin_user['sid'];

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
            //
            return redirect($this->input->post('redirect_url'), 'refresh');
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
                    $payment = $this->makeDirectPayment($card_params, $invoice_amount, 'USD', 'Payment Against Invoice # ' . $invoice_data['invoice_number'], $company_sid, $this->apiContext);
                }
            }

            if (is_array($payment)) {

                if (isset($payment['error_message'])) {
                    $this->session->set_flashdata('message', $payment['error_message']);
                } else {
                    $this->session->set_flashdata('message', 'Payment could not be processed due to unknown error!');
                }
                //
                if ($this->input->post('redirect_url')) {
                    return redirect($this->input->post('redirect_url'), 'refresh');
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
            //
            return redirect($this->input->post('redirect_url'), 'refresh');
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
}

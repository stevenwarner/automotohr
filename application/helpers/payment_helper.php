<?php defined('BASEPATH') OR exit('No direct script access allowed');

if(!function_exists('process_payment_using_credit_card')){
    function process_payment_using_credit_card($credit_card_id, $currency, $total, $transaction_description ){
        $client_id = 'AfC_1jCxix1w4_58cNwRSyj0V0dX9LFlid5ZNoBmpOfNhrKGg-CM0JcaLSjL8ohQi0JOcnuW8hi1_7kw';
        $client_secret = 'EC-jOVM_hCGbDMTY5hcCgdS9dP5XEg49jV8nmwe98qC_ilNtYlgbBPT8LSA5LGXAKV_WzxDXOOEgakNW';

        switch(base_url()){
            case 'http://localhost/automotoCI/':
                require_once '/ext/autoload.php';
                break;
            default:
                require_once '/'.DOC_ROOT.'www/ext/autoload.php';
                break;
        }

        $myOAuthTokenCredentials = new \PayPal\Auth\OAuthTokenCredential($client_id, $client_secret);
        $api_context = new \PayPal\Rest\ApiContext($myOAuthTokenCredentials);

        switch(base_url()){
            case 'http://localhost/automotoCI/':
                $configuration = array();
                $configuration['mode'] = 'sandbox';
                $api_context->setConfig($configuration);
                break;
            default:
                $configuration = array();
                $configuration['mode'] = 'sandbox';
                $api_context->setConfig($configuration);
                break;
        }

        $ccToken = new \PayPal\Api\CreditCardToken();
        $ccToken->setCreditCardId($credit_card_id);

        $fi = new \PayPal\Api\FundingInstrument();
        $fi->setCreditCardToken($ccToken);

        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("credit_card");
        $payer->setFundingInstruments(array($fi));

        // Specify the payment amount.
        $amount = new \PayPal\Api\Amount();
        $amount->setCurrency($currency);
        $amount->setTotal($total);
        // ###Transaction
        // A transaction defines the contract of a
        // payment - what is the payment for and who
        // is fulfilling it. Transaction is created with
        // a `Payee` and `Amount` types
        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription($transaction_description);

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale");
        $payment->setPayer($payer);
        $payment->setTransactions(array($transaction));

        try {
            $payment->create($api_context);
            return $payment;
        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            $CI = & get_instance();

            $message = $CI->parseApiError($ex->getData());

            $messageType = "error";
            $error_flag = true;
            return $message;
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            $messageType = "error";
            $error_flag = true;
            return $message;
        }



    }
}
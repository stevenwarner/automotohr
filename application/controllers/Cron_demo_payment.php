<?php defined('BASEPATH') OR exit('No direct script access allowed');
use PayPal\Api\Amount;
use PayPal\Api\CreditCard;
use PayPal\Api\CreditCardToken;
use PayPal\Api\Details;
use PayPal\Api\FundingInstrument;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\Transaction;

class Cron_demo_payment extends CI_Controller{
    public function __construct(){
        parent::__construct();
    }

    public function index($verification_key = null) {
        $test = '/usr/local/bin/php -q /'.DOC_ROOT.'cron.php /cron_payments/index/kg9bMWfIuhP0jCwfQ51x9g5HMtPvZr3qMVYgYJdN3w1MhWpi0l4Hx4sB2QHbIEqqVejAWAF0qmY2oOmUf1oSPC5cXqUKASbl9MEM';
        if ($verification_key == 'kg9bMWfIuhP0jCwfQ51x9g5HMtPvZr3qMVYgYJdN3w1MhWpi0l4Hx4sB2QHbIEqqVejAWAF0qmY2oOmUf1oSPC5cXqUKASbl9MEM') {
            $result = $this->makePaymentUsingCC('CARD-2DC802384M690860AK7NYZRA', 'USD', 1, 'Demo Payment');
            sendMail(REPLY_TO, FROM_EMAIL_NOTIFICATIONS, 'Payment data', print_r( $result, true ), 'techical support', REPLY_TO);                                            
        }
    }



    function makePaymentUsingCC($ccId, $total, $currency, $paymentDesc){
        if ($_SERVER['HTTP_HOST'] == 'localhost') {
            require_once '/ext/autoload.php';
        } else {
            require_once '/'.DOC_ROOT.'www/ext/autoload.php';
        }

        $apiContext = new \PayPal\Rest\ApiContext(
            new \PayPal\Auth\OAuthTokenCredential(
                'AfC_1jCxix1w4_58cNwRSyj0V0dX9LFlid5ZNoBmpOfNhrKGg-CM0JcaLSjL8ohQi0JOcnuW8hi1_7kw', 'EC-jOVM_hCGbDMTY5hcCgdS9dP5XEg49jV8nmwe98qC_ilNtYlgbBPT8LSA5LGXAKV_WzxDXOOEgakNW'
            )
        );

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
            $payment->create($apiContext);
            return $payment;

        } catch (\PayPal\Exception\PayPalConnectionException $ex) {
            return $ex;
        } catch (Exception $ex) {
            $message = $ex->getMessage();
            return $message;
        }
    }
}
<?php defined('BASEPATH') or exit('No direct script access allowed');

class Cron_invoices extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/recurring_payments_model');
    }

    public function index($verification_key = null)
    {
        $datetime = date('Y-m-d H:i:s', strtotime('now'));
        //
        mail(
            OFFSITE_DEV_EMAIL, 
            'Automatic Invoice Generated Cron Hit' . $datetime, 
            "The invoice cron function is trigger at " . $datetime
        );
        
        
        if ($verification_key == 'dwwbtPzuoHI9d5TEIKBKDGWwNoGEUlRuSidW8wQ4zSUHIl9gBxRx18Z3Dqk4HV7ZNCbu2ZfkjFVLHWINnY5uzMkUfIiINdZ19NJj') {
            //
            $today = new DateTime();
            //
            $recurring_payments = $this->recurring_payments_model->get_all_recurring_payment_records('active');
            $current_day = $today->format('d');

            if (!empty($recurring_payments)) {

                foreach ($recurring_payments as $recurring_payment) {
                    $payment_day = $recurring_payment['payment_day'];

                    if (intval($current_day) == intval($payment_day)) {
                        generate_invoice_for_cron_processing($recurring_payment['sid'], 1);
                    }
                }
            }
        }
    }
}

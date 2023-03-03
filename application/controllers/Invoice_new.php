<?php defined('BASEPATH') || exit('No direct script access allowed');

class Invoice_new extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_new_model');

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

}

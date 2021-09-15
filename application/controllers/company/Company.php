<?php defined('BASEPATH') || exit('No direct script access allowed');

class Company extends CI_Controller
{
    //
    private $data;
    //
    private $pages;
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("Payroll_model", "pm");
        //
        $this->data = [];
        $this->data['logged_in_view'] = true;
        //
        $this->pages = [
            'header' => 'main/header',
            'footer' => 'main/footer'
        ];
    }

    /**
     * Set Company Bank Accounts
     * 
     * @method CheckLogin
     * @method Assets
     */
    function BankAccount(){
        //
        CheckLogin($this->data);
        //
        $this->data['Assets'] = $this->Assets('bank_account');
        //
        $this->data['title'] = 'Company Bank Account';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view($this->pages['header'], $this->data)
        ->view('payroll/includes/bank_account')
        ->view($this->pages['footer']);
    }
    
    /**
     * Set Company Taxes
     * 
     * @method CheckLogin
     * @method Assets
     */
    function Taxes(){
        //
        CheckLogin($this->data);
        //
        $this->data['Assets'] = $this->Assets('taxes');
        //
        $this->data['title'] = 'Company Taxes';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view($this->pages['header'], $this->data)
        ->view('payroll/includes/taxes')
        ->view($this->pages['footer']);
    }

    /**
     * Generate Assets for the page
     * 
     * @param String $page
     * @return Array
     */
    private function Assets($page){
        //
        $Assets = [];
        //
        $Assets['common'] = [
            '<link href="'.(base_url('assets/css/SystemModel'.(MINIFIED).'.css?v='.(MINIFIED == '' ? time() : '1.0').'')).'" rel="stylesheet">',
            '<script src="'.(base_url('assets/js/SystemModal'.(MINIFIED).'.js?v='.(MINIFIED == '' ? time() : '1.0').'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['bank_account'] = [
            '<script src="'.(base_url('assets/payroll/bank_account'.(MINIFIED).'.js?v='.(MINIFIED == '' ? time() : '1.0').'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['taxes'] = [
            '<script src="'.(base_url('assets/payroll/tax'.(MINIFIED).'.js?v='.(MINIFIED == '' ? time() : '1.0').'')).'" type="text/javascript"></script>'
        ];
        //
        return array_merge($Assets['common'],$Assets[$page]);
    }

}
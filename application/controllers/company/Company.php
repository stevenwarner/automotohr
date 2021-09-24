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
        $this->load->library('user_agent');
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
     * Set Company Locations
     * 
     * @method CheckLogin
     * @method Assets
     */
    function Locations(){
        //
        CheckLogin($this->data);
        //
        $this->data['Assets'] = $this->Assets('locations');
        //
        $this->data['title'] = 'Company Locations';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view($this->pages['header'], $this->data)
        ->view('payroll/includes/locations')
        ->view($this->pages['footer']);
    }

    /**
     * Set Company Pay Periods
     * 
     * @method CheckLogin
     * @method Assets
     */
    function PayPeriods(){
        //
        CheckLogin($this->data);
        //
        $this->data['Assets'] = $this->Assets('pay_period');
        //
        $this->data['title'] = 'Company Pay Periods';
        $this->data['load_view'] = 0;
        //
        $this->load
        ->view($this->pages['header'], $this->data)
        ->view('payroll/includes/pay_period')
        ->view($this->pages['footer']);
    }
    
    /**
     * Add Emloyee To Payroll
     * 
     * @method CheckLogin
     * @method Assets
     */
    function AddEmployee($employeeId){
        //
        CheckLogin($this->data);
        //
        $this->load->model('single/Employee_model', 'em');
        //
        $this->data['section'] = $this->input->get('section') ? $this->input->get('section') : 'basic_information';
        //
        $this->data['Employee'] = $this->em->GetEmployeeDetails(
            $employeeId, [
                'sid',
                'first_name',
                'last_name',
                'on_payroll',
                'access_level',
                'profile_picture',
                'access_level_plus',
                'pay_plan_flag',
            ]
        );
        // Check if employee is on payroll
        $this->data['isEmployeeOnPayroll'] = $this->data['Employee']['on_payroll'];
        //
        if(!$this->data['isEmployeeOnPayroll']){
            $this->data['section'] = 'basic_information';
        }
        //
        $this->data['Assets'] = $this->Assets('add_employee')[$this->data['section']];
        //
        $this->data['title'] = 'Add Employee To Payroll';
        $this->data['load_view'] = 0;
        $this->data['employeeId'] = $employeeId;
        //
        $this->load
        ->view($this->pages['header'], $this->data)
        ->view('payroll/includes/add_employee')
        ->view($this->pages['footer']);
    }


    /**
     * 
     */
    function GetJobDetailPage($jobId){
        //
        echo $this->load->view("payroll/job_detail_view", ['jobId' => $jobId], true);
    }

    /**
     * Generate Assets for the page
     * 
     * @param String $page
     * @return Array
     */
    private function Assets($page){
        //
        $version = MINIFIED == '' ? time() : '1.0';
        //
        $Assets = [];
        //
        $Assets['common'] = [
            '<link href="'.(base_url('assets/css/SystemModel'.(MINIFIED).'.css?v='.($version).'')).'" rel="stylesheet">',
            '<script src="'.(base_url('assets/js/SystemModal'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['bank_account'] = [
            '<script src="'.(base_url('assets/payroll/bank_account'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['taxes'] = [
            '<script src="'.(base_url('assets/payroll/tax'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['locations'] = [
            '<script src="'.(base_url('assets/payroll/locations'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['pay_period'] = [
            '<script src="'.(base_url('assets/payroll/pay_period'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
        ];
        //
        $Assets['add_employee'] = [
            'basic_information' => [
                '<script src="'.(base_url('assets/payroll/basic_information'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
            ],
            'bank_accounts' => [
                '<script src="'.(base_url('assets/payroll/bank_accounts'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>'
            ],
            'jobs' => [
                '<script src="'.(base_url('assets/payroll/jobs'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>',
                '<script src="'.(base_url('assets/payroll/compensations'.(MINIFIED).'.js?v='.($version).'')).'" type="text/javascript"></script>',
            ]
        ];
        //
        return array_merge($Assets['common'],$Assets[$page]);
    }

}
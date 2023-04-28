<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }

    public function test()
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.gusto-demo.com/v1/companies/7756341741024534/pay_periods?start_date=2023-02-28',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer msPKA8AjhCKIObfna046RGcI4HdcgcM28zdGIZUbAmg'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        echo $response;

        die;
    }

    public function fix_merge()
    {
        $this->tm->get_merge_employee();
    }

    public function addEmployee () {
        $employeeId = '17133';
        $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
        $this->gusto_payroll_model->onboardEmployeeOnGusto($employeeId);
    }



    // Enable Rehired Employees


    // public function enableRehiredemployees()
    // {

    //     $employeesData = $this->tm->getRehiredemployees();

    //     if (!empty($employeesData)) {
    //         foreach ($employeesData as $employeeRow) {
    //             $this->tm->updateEmployee($employeeRow['sid']);
    //         }
    //     }
    //     echo "Done";
    // }
}

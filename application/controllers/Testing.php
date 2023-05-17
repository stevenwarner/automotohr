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

    /**
     * 
     */
    public function redirectToComply(int $employeeId = 0)
    {
        // check if we need to read from session
        if ($employeeId === 0) {
            $employeeId = $this->session->userdata('logged_in')['employer_detail']['sid'];
        }
        // if employee is not found
        if ($employeeId == 0) {
            return redirect('/dashboard');
        }
        // generate link
        $complyLink = getComplyNetLink(0, $employeeId);
        //
        if (!$complyLink) {
            return redirect('/dashboard');
        }
        redirect($complyLink);
    }

    public function missingEmployee () {
        $jsonString = '[
            {
                "oldEmployeeId": "28197",
                "oldCompanyId": "28144",
                "newEmployeeId": "52989",
                "newCompanyId": "16439"
            },
            {
                "oldEmployeeId": "51221",
                "oldCompanyId": "50063",
                "newEmployeeId": "52675",
                "newCompanyId": "50060"
            },
            {
                "oldEmployeeId": "51250",
                "oldCompanyId": "50063",
                "newEmployeeId": "52648",
                "newCompanyId": "50060"
            },
            {
                "oldEmployeeId": "50852",
                "oldCompanyId": "50060",
                "newEmployeeId": "52637",
                "newCompanyId": "50063"
            },
            {
                "oldEmployeeId": "51675",
                "oldCompanyId": "16463",
                "newEmployeeId": "52459",
                "newCompanyId": "32484"
            },
            {
                "oldEmployeeId": "17306",
                "oldCompanyId": "16404",
                "newEmployeeId": "51747",
                "newCompanyId": "16418"
            },
            {
                "oldEmployeeId": "18464",
                "oldCompanyId": "16420",
                "newEmployeeId": "50348",
                "newCompanyId": "32484"
            },
            {
                "oldEmployeeId": "24672",
                "oldCompanyId": "16465",
                "newEmployeeId": "50074",
                "newCompanyId": "16463"
            },
            {
                "oldEmployeeId": "35303",
                "oldCompanyId": "16463",
                "newEmployeeId": "50073",
                "newCompanyId": "16465"
            }
        ]';
        //
        $employees = json_decode($jsonString,true);
        //
        $this->load->model('2022/Complynet_model', 'complynet_model');
        //
        foreach ($employees as $employee) {
            $this->complynet_model->manageEmployee($employee);
        }
           
        // //
        // $this->complynet_model->fixUnhandleEmployees();
        die("I am here");
    }

}

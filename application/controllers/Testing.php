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
                "oldEmployeeId": "23965",
                "oldCompanyId": "16457",
                "newEmployeeId": "52644",
                "newCompanyId": "27849"
            },
            {
                "oldEmployeeId": "50852",
                "oldCompanyId": "50060",
                "newEmployeeId": "52637",
                "newCompanyId": "50063"
            },
            {
                "oldEmployeeId": "48076",
                "oldCompanyId": "16431",
                "newEmployeeId": "52628",
                "newCompanyId": "16481"
            },
            {
                "oldEmployeeId": "28477",
                "oldCompanyId": "27849",
                "newEmployeeId": "52619",
                "newCompanyId": "16408"
            },
            {
                "oldEmployeeId": "48179",
                "oldCompanyId": "48063",
                "newEmployeeId": "52486",
                "newCompanyId": "16451"
            },
            {
                "oldEmployeeId": "51675",
                "oldCompanyId": "16463",
                "newEmployeeId": "52459",
                "newCompanyId": "32484"
            },
            {
                "oldEmployeeId": "52447",
                "oldCompanyId": "32055",
                "newEmployeeId": "52450",
                "newCompanyId": "32051"
            },
            {
                "oldEmployeeId": "52277",
                "oldCompanyId": "32055",
                "newEmployeeId": "52449",
                "newCompanyId": "32051"
            },
            {
                "oldEmployeeId": "27729",
                "oldCompanyId": "16408",
                "newEmployeeId": "52442",
                "newCompanyId": "27849"
            },
            {
                "oldEmployeeId": "18534",
                "oldCompanyId": "16420",
                "newEmployeeId": "52124",
                "newCompanyId": "16471"
            },
            {
                "oldEmployeeId": "35604",
                "oldCompanyId": "16431",
                "newEmployeeId": "51881",
                "newCompanyId": "16408"
            },
            {
                "oldEmployeeId": "49160",
                "oldCompanyId": "16431",
                "newEmployeeId": "51834",
                "newCompanyId": "16408"
            },
            {
                "oldEmployeeId": "17306",
                "oldCompanyId": "16404",
                "newEmployeeId": "51747",
                "newCompanyId": "16418"
            },
            {
                "oldEmployeeId": "50347",
                "oldCompanyId": "16374",
                "newEmployeeId": "50382",
                "newCompanyId": "16420"
            },
            {
                "oldEmployeeId": "18464",
                "oldCompanyId": "16420",
                "newEmployeeId": "50348",
                "newCompanyId": "32484"
            },
            {
                "oldEmployeeId": "50301",
                "oldCompanyId": "16374",
                "newEmployeeId": "50346",
                "newCompanyId": "16420"
            },
            {
                "oldEmployeeId": "50274",
                "oldCompanyId": "16374",
                "newEmployeeId": "50295",
                "newCompanyId": "16420"
            },
            {
                "oldEmployeeId": "50273",
                "oldCompanyId": "16374",
                "newEmployeeId": "50294",
                "newCompanyId": "16420"
            },
            {
                "oldEmployeeId": "50270",
                "oldCompanyId": "16374",
                "newEmployeeId": "50291",
                "newCompanyId": "16420"
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
            },
            {
                "oldEmployeeId": "31509",
                "oldCompanyId": "16433",
                "newEmployeeId": "31511",
                "newCompanyId": "16429"
            },
            {
                "oldEmployeeId": "26749",
                "oldCompanyId": "16374",
                "newEmployeeId": "28596",
                "newCompanyId": "28588"
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

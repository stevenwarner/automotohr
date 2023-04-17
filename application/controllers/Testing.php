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
    {/*
        $this->load->model('users_model');

        //
        $companies = $this->db
            ->select('sid')
            ->where('parent_sid', 0)
            ->get('users')
            ->result_array();
        
        //
        foreach ($companies as $company) {
            $this->users_model->fixOnboardingAddress($company['sid']);
        }
        */





        $applicant = [
            'onboarding' => [
                'tag' => 'Applicant Onboarding',
                'name' => 'onboarding',
                'url' => '/hcm/v2/applicant.onboard',
                'body' => [
                    "applicantOnboarding" => [
                        "onboardingTemplateCode" => [
                            "code" => "{{onboardingTemplateCode}}"
                        ],
                        "onboardingStatus" => [
                            "statusCode" => [
                                "code" => "inprogress"
                            ]
                        ]
                    ],
                    "applicantPersonalProfile" => [
                        "birthName" => [
                            "givenName" => "{{firstName}}",
                            "familyName" => "{{lastName}}"
                        ],
                        "governmentIDs" => [

                            "id" => "",
                            "nameCode" => [
                                "code" => "SSN"
                            ],
                            "statusCode" => [
                                "code" => "AppliedFor"
                            ]

                        ]
                    ],
                    "applicantWorkerProfile" => [
                        "hireDate" => "{{timestamp}}"
                    ],
                    "applicantPayrollProfile" => [
                        "payrollGroupCode" => "{{companyCode}}"
                    ],
                    "applicantTaxProfile" => []
                ]
            ]
        ];
        //
        $this->load->model('2022/Adp_model');

        $this->Adp_model->onboardApplicantToAdp($applicant);
        
    }




    public function fix_merge () {
        $this->tm->get_merge_employee();
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

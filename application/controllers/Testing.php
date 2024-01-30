<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->model('hr_documents_management_model');
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

    /**
     * 
     */
    public function test()
    {
        // load payroll model
        $this->load->model("v1/Payroll/Copy_model", "copy_model");
        //
        $this->copy_model->copyCompanyEarningTypes(56885, 21);
    }

    /**
     * 
     */
    public function calculateWages()
    {
        // load payroll model
        $this->load->model("v1/Payroll/Wage_model", "wage_model");
        //
        $this->wage_model->calculateEmployeeWage(
            15753, 
            "2024-01-01",
            "2024-01-05"
        );
    }

    public function w4ToGusto()
    {
        // load payroll model
        $this->load->model("v1/Payroll/W4_payroll_model", "w4_payroll_model");

        // $this->w4_payroll_model->syncW4s(21);
        // $this->w4_payroll_model->syncStateW4(21);
        // $this->w4_payroll_model->syncDDI(21);
    }

    public function syncEmployeesToStore()
    {
        // get the employees
        $employees = $this->db
            ->select("gusto_uuid")
            ->get("gusto_companies_employees")
            ->result_array();
        //
        if (!$employees) {
            exit("no employees found");
        }
        // load payroll model
        $this->load->model("v1/Employee_payroll_model", "employee_payroll_model");
        foreach ($employees as $v0) {

            // handle employee sync
            $this
                ->employee_payroll_model
                ->syncEmployeeFromGustoToStore(
                    $v0["gusto_uuid"],
                    false
                );
        }
        exit(count($employees) . " employees are synced!");
    }

    public function syncEmployeeHomeAddress () {
        $employees = $this->db
            ->select("employee_sid, gusto_uuid")
            ->where('gusto_home_address_uuid', NULL)
            ->or_where('gusto_home_address_uuid', '')
            ->get("gusto_companies_employees")
            ->result_array();
        //
        if (!$employees) {
            exit("no employees found");
        }  
        //
        $this->load->model("v1/Payroll_model", "payroll_model");
        //
        foreach ($employees as $employee) {
            $employeeId = $employee['employee_sid'];
            $employeeInfo = $this->payroll_model->getEmployeeHomeAddress($employeeId);

            //
            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $this->load->model('hr_documents_management_model');
                // check and get state forms
                $companyStateForms = $this->hr_documents_management_model
                    ->getCompanyStateForms(
                        17100,
                        $employeeId,
                        "employee"
                    );
                   
                //
                if ($companyStateForms['completed']) {
                    foreach ($companyStateForms['completed'] as $form) {
                        //
                        _e($form,true);
                        if ($form['title'] == "2020 W-4MN, Minnesota Employee Withholding Allowance/Exemption Certificate") {
                            if (!$employeeInfo['Location_Address']) {
                                $employeeInfo['Location_Address'] = $form['form_data']['street_1'];
                            }
                            //
                            if (!$employeeInfo['Location_City']) {
                                $employeeInfo['Location_City'] = $form['form_data']['city'];
                            }
                            //
                            if (!$employeeInfo['state_code']) {
                                $employeeInfo['state_code'] = getStateColumnById($form['form_data']['state']);
                            }
                            //
                            if (!$employeeInfo['Location_ZipCode']) {
                                $employeeInfo['Location_ZipCode'] = $form['form_data']['zip_code'];
                            }
                        }
                    }
                }   
                //
                if ($companyStateForms['']) {
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $w4FormInfo['home_address'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $w4FormInfo['city'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        if ($w4FormInfo['state']) {
                            //
                            $stateCode = $this->db
                                ->select("state_code")
                                ->where('LOWER(state_name)', strtolower($w4FormInfo['state']))
                                ->get("states")
                                ->row_array()['state_code'];
                            //    
                            $employeeInfo['state_code'] = $stateCode;
                        }
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $w4FormInfo['zip'];
                    }
                }
                
            }

            //
            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $this->load->model('form_wi9_model');
                $w4FormInfo = $this->form_wi9_model->fetch_form("w4", "employee", $employeeId);
                //
                if ($w4FormInfo) {
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $w4FormInfo['home_address'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $w4FormInfo['city'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        if ($w4FormInfo['state']) {
                            //
                            $stateCode = $this->db
                                ->select("state_code")
                                ->where('LOWER(state_name)', strtolower($w4FormInfo['state']))
                                ->get("states")
                                ->row_array()['state_code'];
                            //    
                            $employeeInfo['state_code'] = $stateCode;
                        }
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $w4FormInfo['zip'];
                    }
                }
                
            }

            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $this->load->model('form_wi9_model');
                $i9FormInfo = $this->form_wi9_model->fetch_form("i9", "employee", $employeeId);
                //
                if ($i9FormInfo) {
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $i9FormInfo['section1_address'].' '.$i9FormInfo['section1_apt_number'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $i9FormInfo['section1_city_town'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        $employeeInfo['state_code'] = $i9FormInfo['section1_state'];
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $i9FormInfo['section1_zip_code'];
                    }
                }
            }

            if (
                !$employeeInfo['Location_Address'] ||
                !$employeeInfo['Location_City'] ||
                !$employeeInfo['state_code'] ||
                !$employeeInfo['Location_ZipCode']
            ) {
                //
                $employeeFullInfo = $this->db
                    ->select("full_employment_application")
                    ->where("sid", $employeeId)
                    ->get("users")
                    ->row_array()['full_employment_application'];
                //
                if ($employeeFullInfo) {
                    $fullEmploymentApplication = unserialize($employeeFullInfo);
                    if (!$employeeInfo['Location_Address']) {
                        $employeeInfo['Location_Address'] = $fullEmploymentApplication['TextBoxAddressStreetFormer1'];
                    }
                    //
                    if (!$employeeInfo['Location_City']) {
                        $employeeInfo['Location_City'] = $fullEmploymentApplication['TextBoxAddressCityFormer1'];
                    }
                    //
                    if (!$employeeInfo['state_code']) {
                        $employeeInfo['state_code'] = $fullEmploymentApplication['DropDownListAddressStateFormer1'];
                    }
                    //
                    if (!$employeeInfo['Location_ZipCode']) {
                        $employeeInfo['Location_ZipCode'] = $fullEmploymentApplication['TextBoxAddressZIPFormer1'];
                    }
                }    
                
            }

            _e($employeeInfo,true);
            if (
                $employeeInfo['Location_Address'] &&
                $employeeInfo['Location_City'] &&
                $employeeInfo['state_code'] &&
                $employeeInfo['Location_ZipCode']
            ) {
                
                // sync employee address
                $this->payroll_model->createEmployeeHomeAddress($employeeId, [
                    'street_1' => $employeeInfo['Location_Address'],
                    'street_2' => $employeeInfo['Location_Address_2'],
                    'city' => $employeeInfo['Location_City'],
                    'state' => strtoupper($employeeInfo['state_code']),
                    'zip' => $employeeInfo['Location_ZipCode'],
                ]);
            }
        }
        _e("script process complete",true,true);  
    }
}

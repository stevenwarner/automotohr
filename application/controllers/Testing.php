<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");

    }

    function text($id)
    {
        //
        $returnArray = [];
        $returnArray['Count'] = [];
        $returnArray['Records'] = [];
        //
        $records = $this->cpp->GetRecords($id);
        //
        if(!empty($records)){
            return $returnArray;
        }
        //
        foreach($records as $record){
            //
            $returnArray['Count']++;
        }
    }

    //
    function sendEmailNotifications($id){
        //
        $record = $this->ccp->GetReviewByIdByReviewers($id)[0];
        //
        $hf = message_header_footer($record['company_sid'], $record['CompanyName']);
        //
        if(empty($record['Reviewees'])){
            return;
        }
        //
        $template = get_email_template(REVIEW_ADDED);

        foreach($record['Reviewees'] as $row){
            //
            $replaceArray = [];
            $replaceArray['{{first_name}}'] = ucwords($row[0]['reviewer_first_name']);
            $replaceArray['{{last_name}}'] = ucwords($row[0]['reviewer_last_name']);
            $replaceArray['{{review_title}}'] = $record['review_title'];
            
            $replaceArray['{{table}}'] = $this->load->view('table', ['records' => $row, 'id' => $record['sid']], true);
            //
            $body = $hf['header'].str_replace(array_keys($replaceArray), $replaceArray, $template['text']).$hf['footer'];

            log_and_sendEmail(
                FROM_EMAIL_NOTIFICATIONS,
                $row[0]['reviewer_email'],
                $template['subject'],
                $body,
                $record['CompanyName']
            );
        }
    }


    //
    public function SyncPayrollEmployees(){
        //
        $this->load->helper('payroll');
        $this->load->model('payroll_model', 'pm');
        //
        $companyId = 15708;
        // Get company details
        $company = $this->pm->GetCompany($companyId, [
            'access_token',
            'refresh_token',
            'gusto_company_uid'
        ]);
        //
        $response = GetCompanyEmployees($company);
        //
        if(!$response){
            die('No employees found');
        }
        //
        foreach($response as $employee){
            //
            if(empty($employee['email'])){
                continue;
            }
            // Check if employee exists
            $payrollEmployee = $this->db->where('payroll_employee_uuid', $employee['uuid'])->get('payroll_employees')->row_array();
            //
            if(!$payrollEmployee){
                // Insert the employee
                $this->db->insert('users', [
                    'parent_sid' => $companyId,
                    'first_name' => $employee['first_name'],
                    'middle_name' => $employee['middle_initial'],
                    'last_name' => $employee['last_name'],
                    'email' => $employee['email'],
                    'dob' => $employee['date_of_birth'],
                    'PhoneNumber' => $employee['phone']
                ]);
                //
                $payrollEmployee['company_sid'] = $companyId;
                $payrollEmployee['employee_sid'] = $this->db->insert_id();
                $payrollEmployee['payroll_employee_id'] = $employee['id'];
                $payrollEmployee['payroll_employee_uuid'] = $employee['uuid'];
                $payrollEmployee['onboard_completed'] = 1;
                $payrollEmployee['personal_profile'] = 1;
                $payrollEmployee['home_address'] = 1;
                $payrollEmployee['compensation'] = 1;
                $payrollEmployee['federal_tax'] = 1;
                $payrollEmployee['state_tax'] = 1;
                $payrollEmployee['payment_method'] = 1;
                $payrollEmployee['version'] = $employee['version'];
                $payrollEmployee['created_at'] = date('Y-m-d H:i:s', strtotime('now'));
                $payrollEmployee['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
                // Insert the payroll employee
                $this->db->insert('payroll_employees', $payrollEmployee);
                //
                $payrollEmployee['sid'] = $this->db->insert_id();
            }
            // Home address
            if(!$this->db->where('employee_sid', $payrollEmployee['employee_sid'])->count_all_results('payroll_employee_address')){
                $this->db->insert(
                    'payroll_employee_address', [
                        'employee_sid' => $payrollEmployee['employee_sid'],
                        'version' => $employee['home_address']['version'],
                        'street_1' => $employee['home_address']['street_1'],
                        'street_2' => $employee['home_address']['street_2'],
                        'city' => $employee['home_address']['city'],
                        'state' => $employee['home_address']['state'],
                        'zip' => $employee['home_address']['zip'],
                        'country' => $employee['home_address']['country']
                    ]
                );
            }
            // Jobs
            if(!$this->db->where('employee_sid', $payrollEmployee['employee_sid'])->count_all_results('payroll_employee_jobs')){
                $this->db->insert(
                    'payroll_employee_jobs', [
                        'employee_sid' => $payrollEmployee['employee_sid'],
                        'payroll_uid' => $employee['jobs'][0]['uuid'],
                        'version' => $employee['jobs'][0]['version'],
                        'payroll_job_id' => $employee['jobs'][0]['id'],
                        'hire_date' => $employee['jobs'][0]['hire_date'],
                        'title' => $employee['jobs'][0]['title'],
                        'rate' => $employee['jobs'][0]['rate'],
                        'payment_unit' => $employee['jobs'][0]['payment_unit'],
                        'current_compensation_id' => $employee['jobs'][0]['current_compensation_id'],
                        'payroll_location_id' => $employee['jobs'][0]['location_id']
                    ]
                );
                //
                $jobsId = $this->db->insert_id();
                //
                foreach($employee['jobs'][0]['compensations'] as $compensation){
                    //
                    $this->db->insert(
                        'payroll_employee_job_compensations', [
                            'payroll_job_sid' => $jobsId,
                            'rate' => $compensation['rate'],
                            'version' => $compensation['version'],
                            'payment_unit' => $compensation['payment_unit'],
                            'flsa_status' => $compensation['flsa_status'],
                            'effective_date' => $compensation['effective_date'],
                            'payroll_id' => $compensation['id'],
                            'version' => $compensation['version']
                        ]
                    );
                }
            }
            // Federal tax
            if(!$this->db->where('employee_sid', $payrollEmployee['employee_sid'])->count_all_results('payroll_employee_federal_tax')){
                //
                $federalTax = GetEmployeeFederalTax($payrollEmployee['payroll_employee_uuid'], $company);
                //
                $this->db->insert(
                    'payroll_employee_federal_tax', [
                        'employee_sid' => $payrollEmployee['employee_sid'],
                        'company_sid' => $companyId,
                        'filing_status' => $federalTax['filing_status'],
                        'multiple_jobs' => $federalTax['two_jobs'],
                        'dependent' => $federalTax['dependents_amount'],
                        'other_income' => $federalTax['other_income'],
                        'deductions' => $federalTax['deductions'],
                        'extra_withholding' => $federalTax['extra_withholding'],
                        'w4_data_type' => $federalTax['w4_data_type'],
                        'version' => $federalTax['version']
                    ]
                );
            }
            // State tax
            if(!$this->db->where('employee_sid', $payrollEmployee['employee_sid'])->count_all_results('payroll_employee_state_tax')){
                //
                $stateTax = GetEmployeeStateTax($payrollEmployee['payroll_employee_uuid'], $company);
                //
                $this->db->insert(
                    'payroll_employee_state_tax', [
                        'employee_sid' => $payrollEmployee['employee_sid'],
                        'company_sid' => $companyId,
                        'state' => $stateTax[0]['state'],
                        'state_json' => json_encode($stateTax[0])
                    ]
                );
            }
            // Payment method
            if(!$this->db->where('employee_sid', $payrollEmployee['employee_sid'])->count_all_results('payroll_employee_payment_method')){
                //
                $paymentMethod = GetEmployeePaymentMethod($payrollEmployee['payroll_employee_uuid'], $company);
                //
                $this->db->insert(
                    'payroll_employee_payment_method', [
                        'employee_sid' => $payrollEmployee['employee_sid'],
                        'company_sid' => $companyId,
                        'payment_method' => $paymentMethod['type'],
                        'split_method' => $paymentMethod['split_by'],
                        'version' => $paymentMethod['version'],
                        'splits' => json_encode($paymentMethod['splits'])
                    ]
                );
            }
            //
            usleep(100);
        }
        //
        echo "All done";
        exit(0);
    }

}
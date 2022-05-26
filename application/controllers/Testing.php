<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");
        $this->load->model("test_model", "tm");

    }

    //
    function sendTestEmail(){
        //
       
        $templets = [48, 3,36,38,300,359,7,4,305,306,307,308,309,311, 312, 313, 314, 315, 316, 317, 318, 319, 320, 321, 322, 324, 325, 326, 327, 328, 330, 332, 333, 334, 335, 344, 100, 5, 10, 336, 337, 338, 339, 340, 341, 342, 343, 345, 349, 351, 352, 353, 357, 354, 347, 346, 348, 356, 35, 361, 355, 377, 378, 379, 380, 416, 381, 383, 387, 41, 420, 421, 417, 18, 362, 371, 382, 385, 388, 389, 390, 391, 415, 400, 401, 402, 403, 394, 392, 393, 414, 412, 413];

        $replacement_array = array();
        $replacement_array['applicant_name'] = "Jone Doe Applicant";
        $replacement_array['job_title'] = "Developer";
        $replacement_array['approval_status'] = "Hired one";
        $replacement_array['conditions'] = "new reason";

        $replacement_array['employer_name'] = ucwords("Aleem Shaukat");
        $user_email = "abc@def.com";
        $replacement_array['approving_authority'] = "Butt Sahib";

        $on_boarding_link = '<a target="_blank" style="'.VIDEO_INTERVIEW_EMAIL_BTN_STYLE.'" href="javascript:;">HR Compliance And Onboarding</a>';
        $replacement_array['on_boarding_link'] = $on_boarding_link;
        $replacement_array['company_name'] = ucwords("Pepsi co");
        $replacement_array['event_title'] = "Aftar Party";
        $replacement_array['duration'] = "1 hour";
        $replacement_array['participant_name'] = "Bla bla bla";
        $replacement_array['start_time'] = "6:36 PM";


        $replacement_array['user-name'] = ucwords($replacement_array['applicant_name']);
        $replacement_array['employee_name'] = "Butt Sahib";
        $replacement_array['company-name'] = ucwords("Pepsi co");
        $replacement_array['applicant-name'] = "Jone Doe Applicant";
        $replacement_array['job-title'] = "Developer";
        $replacement_array['subject'] = 'PTO Changed By Team Lead';
        $replacement_array['pto_details'] = "body";
        $replacement_array['name'] = "Usana Boalt";
        $replacement_array['firstname'] = "Usana";
        $replacement_array['lastname'] = "Boalt";
        $replacement_array['first_name'] = "Usana";
        $replacement_array['last_name'] = "Boalt";
        $replacement_array['referred_to'] = "Referred to Me";
        $replacement_array['username'] = "Username_usanbolt";
        $replacement_array['contact-name'] = "Mubashir";
        $replacement_array['company_admin'] = "Mustafa";
        $replacement_array['employee-name'] = "Mujtaba";
        $replacement_array['to-name'] = "Mahmood";
        $replacement_array['billing_contact_name'] = "Mahmood Alam";
        $replacement_array['client_name'] = "MM Alam";
        $replacement_array['administrator'] = "MM Alam PAF";
        $replacement_array['assigned_to_name'] = "Developer php";
        $replacement_array['affiliate_name'] = "affiliate Name php";
        $replacement_array['approver_first_name'] = "Usana";
        $replacement_array['approver_last_name'] = "Boalt";
        $replacement_array['requester_first_name'] = "Usana";
        $replacement_array['requester_last_name'] = "Boalt";
        $replacement_array['user_first_name'] = "Usana";
        $replacement_array['user_last_name'] = "Boalt";
        $replacement_array['creator-name'] = "Bajwa GNR";

        $replacement_array['url'] = '<a href="' . base_url() . '" style="'.DEF_EMAIL_BTN_STYLE_PRIMARY.'" target="_blank">Screening Questionnaires</a>';

                        

        $message_hf = (message_header_footer(15708, $replacement_array['company_name'])); 

        foreach ($templets as $key => $id) {
            log_and_send_templated_email($id, $user_email, $replacement_array, $message_hf);
        }

        // 
        //
        die("email send");    
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

    function get_invoices($company_sid){
        //
        $productIds =array();
        $productArray = array();
        $productIDsInArray = array();
        //
        $products = $this->db->get_where('products', array('product_type' => "background-checks"))->result_array();
        //
        foreach ($products as $key => $product) {
            $productIds[$key] = $product['sid'];
        }
        //
        $this->db->select('*');
        $this->db->where('company_sid', $company_sid);
        $this->db->where('status', 'Paid');
        $record_obj = $this->db->get('invoices'); //Getting all invoices against the company which are paid
        $orders = $record_obj->result_array();
        $record_obj->free_result();
        //
        foreach ($orders as $order) {
            $dataArray = unserialize($order['serialized_items_info']);
            //
            foreach ($dataArray['products'] as $key => $product) {
                if (in_array($product, $productIds)) {
                    if (in_array($product, $productIDsInArray)) {
                        //
                        $productArray[$product]['remaining_qty'] = $productArray[$product]['remaining_qty'] + $dataArray['item_remaining_qty'][$key];
                    } else {
                        //
                        array_push($productIDsInArray, $product);
                        $productArray[$product]['product_sid'] = $product;
                        $productArray[$product]['remaining_qty'] = $dataArray['item_remaining_qty'][$key];
                        //
                        if (isset($dataArray['no_of_days']))
                            $productArray[$product]['no_of_days'] = $dataArray['no_of_days'][$key];   
                    }    
                }    
            }    
        }
        //
        if ($product_type == NULL) {
            $products = $this->db->get('products')->result_array();
        } else
            $products = $this->db->get_where('products', array('product_type' => $product_type))->result_array();
        //
        foreach ($productArray as $key => $pro) {
            foreach ($products as $myKey => $product) {
                if ($pro['product_sid'] == $product['sid']) {
                    $pro['product_image'] = $product['product_image'];
                    $pro['name'] = $product['name'];
                    $productArray[$key] = $pro;
                }
            }
        }
        //
        echo "<pre>";
        print_r($productIDsInArray);
        print_r($productArray);
        die();
    }

    function get_fix_rehired (){
        //
        $this->db->select('sid,first_name,last_name,active,terminated_status,general_status');
        $this->db->where('general_status', "rehired");
        $this->db->where('active', '0');
        $record_obj = $this->db->get('users'); //Getting all rehired
        $employees = $record_obj->result_array();
        $record_obj->free_result();
        //
        foreach ($employees as $employee) {
            $data_to_update = array();   
            $data_to_update["active"] = 1; 
            $data_to_update["terminated_status "] = 0; 
            //
            $this->db->where('sid', $employee["sid"]);
            $this->db->update('users',$data_to_update);  
        }
        
        //
        echo "<pre>";
        print_r($employees);
        die();
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

    function myApproverScript () {
        $approvers = $this->db->get('portal_document_assign_flow')->result_array();
        $encode = json_encode($approvers);
        _e($encode);
        $decode = json_decode($encode);
        _e($decode,true,true);
    }


    function test2(){
        $this->db->from('doc')->get();
    }

}
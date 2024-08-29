<?php defined('BASEPATH') || exit('No direct script access allowed');


class Ledger extends Public_controller
{

    /**
     * for js
     */
    private $js;
    /**
     * for css
     */
    private $css;
    /**
     * wether to create minified files or not
     */
    private $blockMinifyFilesCreation;
    /**
     * main entry point to controller
     */
    public function __construct()
    {
        parent::__construct();
        // $this->load->helper('path');
        // $this->load->library('parse_csv');
        $this->load->model(
            "v1/Payroll/Ledger_model",
            "ledger_model"
        );
        //
        // set path to CSS file
        $this->css = 'public/v1/css/payroll/';
        // set path to JS file
        $this->js = 'public/v1/js/payroll/';
        //
        $this->blockMinifyFilesCreation = false;
    }

    public function import()
    {
        if ($this->session->userdata('logged_in')) {
            //
            $data = [];
            // check and set user session
            $data['session'] = checkUserSession();
            $data['title'] = "Import Payroll CSV File";
            // set
            $data['loggedInPerson'] = $data['session']['employer_detail'];
            $data['companyId'] = $data['session']['company_detail']['sid'];
            $data['employerId'] = $data['session']['employer_detail']['sid'];
            $data['level'] = 0;
            $data["sanitizedView"] = true;
            // get the security details
            $data['security_details'] = db_get_access_level_details(
                $data['session']['employer_detail']['sid'],
                null,
                $data['session']
            );
            //
            $data['employerData'] = $this->ledger_model->getEmployerDetail($data['employerId']);
            //
            // add css
            $data['appCSS'] = bundleCSS(
                [
                    'v1/plugins/alertifyjs/css/alertify.min',
                    'mFileUploader/index'
                ],
                $this->css,
                'import_payroll_ledger',
                $this->blockMinifyFilesCreation
            );
            //
            $data['appJs'] = bundleJs(
                [
                    'lodash/loadash.min',
                    'v1/plugins/alertifyjs/alertify.min',
                    'mFileUploader/index',
                    'v1/payroll/js/ledger'
                ],
                $this->js,
                'import_payroll_ledger',
                $this->blockMinifyFilesCreation
            );

            $this->load->view('main/header', $data);
            $this->load->view('payroll/ledger/important_ledger');
            $this->load->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }

    /**
     * Handle all ajax requests
     * Created on: 16-08-2019
     *
     * @accepts POST
     *
     * @uses resp
     *
     * @return JSON
     */
    function handler()
    {
        // check and set user session
        $data['session'] = checkUserSession();
        // set
        $data['loggedInPerson'] = $data['session']['employer_detail'];
        $data['companyId'] = $data['session']['company_detail']['sid'];
        $data['employerId'] = $data['session']['employer_detail']['sid'];
        // Set default response array
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request made.';
        // Check for a valid AJAX request
        if (!$this->input->is_ajax_request()) exit(0);
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        switch ($formpost['action']) {
            case 'add_payrolls':
                set_time_limit(0);
                // Default array

                $failCount = $insertCount = $existCount = 0;
                $updatedRows = [];
                $failRows = [];
                //

                if ($formpost['payrolls']) {
                    foreach ($formpost['payrolls'] as $key => $payroll) {
                        //
                        $employeeId = $this->ledger_model->checkEmployeeExistInCompany($payroll, $data['companyId']);

                        //
                        if ($employeeId == 0 && ($payroll['first_name'] || $payroll['last_name'])) {
                            $failCount++;
                            $failRows[] = $key;
                        } else {
                            $starDate = formatDateToDB($payroll['start_period'], SITE_DATE, DB_DATE);
                            $endDate = formatDateToDB($payroll['end_period'], SITE_DATE, DB_DATE);
                            $transactionDate = formatDateToDB($payroll['transaction_date'], SITE_DATE, DB_DATE);
                            $currentDate = getSystemDate();
                            //

                            //
                            if ($employeeId != 0) {
                                // employee
                                if (
                                    !$this->db
                                        ->where([
                                            'start_date' => $starDate,
                                            'end_date' => $endDate,
                                            'transaction_date' => $transactionDate,
                                            'debit_amount' => $payroll['debit'],
                                            'credit_amount' => $payroll['credit'],
                                            'company_sid' => $data['companyId'],
                                            'employee_sid' => $employeeId
                                        ])
                                        ->count_all_results('payrolls.payroll_ledger')
                                ) {
                                    $dataToInsert = [];
                                    $dataToInsert['company_sid'] = $data['companyId'];
                                    $dataToInsert['employee_sid'] = $employeeId;
                                    $dataToInsert['debit_amount'] = $payroll['debit'];
                                    $dataToInsert['credit_amount'] = $payroll['credit'];
                                    $dataToInsert['gross_pay'] = $payroll['gross_pay'];
                                    $dataToInsert['net_pay'] = $payroll['net_pay'];
                                    $dataToInsert['taxes'] = $payroll['taxes'];
                                    $dataToInsert['start_date'] = $starDate;
                                    $dataToInsert['end_date'] = $endDate;
                                    $dataToInsert['transaction_date'] = $transactionDate;
                                    $dataToInsert['description'] = $payroll['description'];
                                    $dataToInsert['created_at'] = $currentDate;
                                    $dataToInsert['updated_at'] = $currentDate;

                                    $dataToInsert['account_name'] = $payroll['account_name'];
                                    $dataToInsert['account_number'] = $payroll['account_number'];
                                    $dataToInsert['reference_number'] = $payroll['reference_number'];
                                    $dataToInsert['general_entry_number'] = $payroll['general_entry_number'];

                                    if (!empty($payroll['extra'])) {
                                        $dataToInsert['extra'] = json_encode($payroll['extra']);
                                    }

                                    //
                                    $this->ledger_model->insertLedgerInfo($dataToInsert);
                                    //
                                    $insertCount++;
                                } else {
                                    $updatedRows[] = $key;
                                    $existCount++;
                                }
                                //   
                            } else {
                                // company
                                if (
                                    !$this->db
                                        ->where([
                                            'start_date' => $starDate,
                                            'end_date' => $endDate,
                                            'transaction_date' => $transactionDate,
                                            'debit_amount' => $payroll['debit'],
                                            'credit_amount' => $payroll['credit'],
                                            'company_sid' => $data['companyId'],
                                            'employee_sid' => null
                                        ])
                                        ->count_all_results('payrolls.payroll_ledger')
                                ) {
                                    $dataToInsert = [];
                                    $dataToInsert['company_sid'] = $data['companyId'];
                                    $dataToInsert['debit_amount'] = $payroll['debit'];
                                    $dataToInsert['credit_amount'] = $payroll['credit'];
                                    $dataToInsert['gross_pay'] = $payroll['gross_pay'];
                                    $dataToInsert['net_pay'] = $payroll['net_pay'];
                                    $dataToInsert['taxes'] = $payroll['taxes'];
                                    $dataToInsert['start_date'] = $starDate;
                                    $dataToInsert['end_date'] = $endDate;
                                    $dataToInsert['transaction_date'] = $transactionDate;
                                    $dataToInsert['description'] = $payroll['description'];
                                    $dataToInsert['created_at'] = $currentDate;
                                    $dataToInsert['updated_at'] = $currentDate;
                                    $dataToInsert['account_name'] = $payroll['account_name'];
                                    $dataToInsert['account_number'] = $payroll['account_number'];
                                    $dataToInsert['reference_number'] = $payroll['reference_number'];
                                    $dataToInsert['general_entry_number'] = $payroll['general_entry_number'];

                                    if (!empty($payroll['extra'])) {
                                        $dataToInsert['extra'] = json_encode($payroll['extra']);
                                    }
                                    //
                                    $this->ledger_model->insertLedgerInfo($dataToInsert);
                                    //
                                    $insertCount++;
                                } else {
                                    $updatedRows[] = $key;
                                    $existCount++;
                                }
                            }
                        }
                    }
                }
                //
                $resp['Status'] = TRUE;
                $resp['Response'] = 'Proceed.';
                $resp['Inserted'] = $insertCount;
                $resp['Existed'] = $existCount;
                $resp['Failed'] = $failCount;
                $resp['duplicateRows'] = $updatedRows;
                $resp['FailedRows'] = $failRows;
                //
                $this->resp($resp);
                break;
        }
        //
        $this->resp($resp);
    }

    /**
     * Send JSON response
     *
     * @param $responseArray Array
     *
     * @return JSON
     */
    function resp($responseArray)
    {
        header('Content-type: application/json');
        echo json_encode($responseArray);
        exit(0);
    }

    //
    public function DownloadTemplate()
    {

        header('Content-Type: text/csv; charset=utf-8');
        header("Content-Disposition: attachment; filename=ledger_sample.csv");
        $output = fopen('php://output', 'w');

        fputcsv($output, array('Employee ID', 'Employee Number', 'Employee SSN', 'Employee Email', 'Employee Phone Number', 'Debit', 'Credit', 'Taxes', 'Description', 'Start Period', 'End Period', 'Transaction Date', 'Account Name', 'Account Number', 'Reference Number', 'Journal Entry Number', 'First Name', 'Last name', 'Department', 'Job Title', 'Gross Pay', 'Net Pay'));

        fclose($output);
        exit;
    }




    //
    public function ledger($start_date = 'all', $end_date = 'all', $employee = 'all', $department = 'all', $jobtitles = 'all', $dateSelection = 'transaction', $page_number = 1)
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'my_settings', 'reports');
            $company_sid = $data['session']['company_detail']['sid'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $data['title'] = 'Advanced Hr Reports - Employees Termination Reports';
            //

            $data["allemployees"] = $this->ledger_model->getCompanyEmployeesOnly(
                $company_sid
            );
            $data["company_sid"] = $company_sid;


            $start_date = urldecode($start_date);
            $end_date = urldecode($end_date);

            $filterEmployees = explode(',', urldecode($employee));
            $filterJobTitles = explode(',', urldecode($jobtitles));
            $filterDepartment = explode(',', urldecode($department));

            //
            if (!empty($start_date) && $start_date != 'all') {
                $start_date_applied = empty($start_date) ? null : DateTime::createFromFormat('m-d-Y', $start_date)->format('Y-m-d');
            } else {
                $start_date_applied = date('Y-m-d 00:00:00');
            }

            if (!empty($end_date) && $end_date != 'all') {
                $end_date_applied = empty($end_date) ? null : DateTime::createFromFormat('m-d-Y', $end_date)->format('Y-m-d');
            } else {
                $end_date_applied = date('Y-m-d');
            }
            //
            $between = '';
            //
            if ($start_date_applied != NULL && $end_date_applied != NULL) {
                if ($dateSelection == 'transaction') {
                    $between = "payroll_ledger.transaction_date >= '" . $start_date_applied . "' and payroll_ledger.transaction_date <=  '" . $end_date_applied . "'";
                } else {
                    $between = "payroll_ledger.start_date = '" . $start_date_applied . "' and payroll_ledger.end_date = '" . $end_date_applied . "'";
                }
            }

            //
            $data["flag"] = true;
            $data['ledgerCount'] = sizeof($this->ledger_model->getEmployeesLedger($company_sid, $between, $filterEmployees, $filterJobTitles, $filterDepartment, null, null));
            /** pagination * */
            $this->load->library('pagination');
            $records_per_page =  PAGINATION_RECORDS_PER_PAGE;
            $my_offset = 0;
            //
            if ($page_number > 1) {
                $my_offset = ($page_number - 1) * $records_per_page;
            }
            //
            $baseUrl = base_url('payrolls/ledger') . '/' . urlencode($start_date) . '/' . urlencode($end_date) . '/' . urlencode($employee) . '/' . urldecode($department) . '/' . urldecode($jobtitles) . '/' . $dateSelection;
            //
            $uri_segment = 9;
            $config = array();
            $config["base_url"] = $baseUrl;
            $config["total_rows"] = $data['ledgerCount'];
            $config["per_page"] = $records_per_page;
            $config["uri_segment"] = $uri_segment;
            $choice = $config["total_rows"] / $config["per_page"];
            $config["num_links"] = ceil($choice);
            $config["use_page_numbers"] = true;
            $config['full_tag_open'] = '<nav class="hr-pagination"><ul>';
            $config['full_tag_close'] = '</ul></nav><!--pagination-->';
            $config['first_link'] = '&laquo; First';
            $config['first_tag_open'] = '<li class="prev page">';
            $config['first_tag_close'] = '</li>';
            $config['last_link'] = 'Last &raquo;';
            $config['last_tag_open'] = '<li class="next page">';
            $config['last_tag_close'] = '</li>';
            $config['next_link'] = '<i class="fa fa-angle-right"></i>';
            $config['next_tag_open'] = '<li class="next page">';
            $config['next_tag_close'] = '</li>';
            $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
            $config['prev_tag_open'] = '<li class="prev page">';
            $config['prev_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li class="page">';
            $config['num_tag_close'] = '</li>';

            $this->pagination->initialize($config);
            $data['page_links'] = $this->pagination->create_links();
            $total_records = $data['terminatedEmployeesCount'];

            $data['current_page'] = $page_number;
            $data['from_records'] = $my_offset == 0 ? 1 : $my_offset;
            $data['to_records'] = $total_records < $records_per_page ? $total_records : $my_offset + $records_per_page;


            $data['employeesLedger'] = $this->ledger_model->getEmployeesLedger($company_sid, $between, $filterEmployees, $filterJobTitles, $filterDepartment, $records_per_page, $my_offset);


            //
            if (sizeof($this->input->post(NULL, TRUE))) {

                $additionalHeader = [];

                $additionalHeader['type'] = 'Type';

                if ($this->input->post('employee_sid')) {
                    $additionalHeader['employee_id'] = 'Employee ID';
                }
                if ($this->input->post('first_name')) {
                    $additionalHeader['first_name'] = "First Name";
                }
                if ($this->input->post('middle_name')) {
                    $additionalHeader['middle_name'] = "Middle Name";
                }
                if ($this->input->post('last_name')) {
                    $additionalHeader['last_name'] = "Last Name";
                }
                if ($this->input->post('job_title')) {
                    $additionalHeader['job_title'] = "Job Title";
                }
                if ($this->input->post('department')) {
                    $additionalHeader['department'] = "Department";
                }
                if ($this->input->post('team')) {
                    $additionalHeader['team'] = "Team";
                }

                if ($this->input->post('employee_number')) {
                    $additionalHeader['employee_number'] = "Employee Number";
                }
                if ($this->input->post('ssn')) {
                    $additionalHeader['ssn'] = "SSN";
                }

                if ($this->input->post('email')) {
                    $additionalHeader['email'] = "Email";
                }

                if ($this->input->post('phone_number')) {
                    $additionalHeader['phone_number'] = "Phone Number";
                }
                //

                if ($this->input->post('debit_amount')) {
                    $additionalHeader['debit_amount'] = 'Debit Amount';
                }
                if ($this->input->post('credit_amount')) {
                    $additionalHeader['credit_amount'] = 'Credit Amount';
                }
                if ($this->input->post('gross_pay')) {
                    $additionalHeader['gross_pay'] = 'Gross Pay';
                }
                if ($this->input->post('net_pay')) {
                    $additionalHeader['net_pay'] = 'Net Pay';
                }
                if ($this->input->post('taxes')) {
                    $additionalHeader['taxes'] = 'Taxes';
                }
                if ($this->input->post('description')) {
                    $additionalHeader['description'] = 'Description';
                }

                if ($this->input->post('transaction_date')) {
                    $additionalHeader['transaction_date'] = 'Transaction Date';
                }
                if ($this->input->post('start_date')) {
                    $additionalHeader['start_date'] = 'Start Period';
                }
                if ($this->input->post('end_date')) {
                    $additionalHeader['end_date'] = 'End Period';
                }
                if ($this->input->post('created_at')) {
                    $additionalHeader['created_at'] = 'Imported At';
                }
                if ($this->input->post('account_name')) {
                    $additionalHeader['account_name'] = 'Account Name';
                }
                if ($this->input->post('account_number')) {
                    $additionalHeader['account_number'] = 'Account Number';
                }
                if ($this->input->post('general_entry_number')) {
                    $additionalHeader['general_entry_number'] = 'Journal Entry Number';
                }
                if ($this->input->post('reference_number')) {

                    $additionalHeader['reference_number'] = 'Reference Number';
                }


                //
                if ($this->input->post('extra')) {

                    $header = [];
                    foreach ($data['employeesLedger'] as $key1 => $row) {
                        foreach (json_decode($row['extra'], true)[0] as $key2 => $value) {
                            if (!in_array($key2, $header)) {
                                $header[] = $key2;
                            }
                            //
                            $data['employeesLedger'][$key1]['extra_1'][$key2] = $value;
                        }
                    }

                    if (!empty($header)) {
                        foreach ($header as  $hdRow) {

                            $headerkey = 'extra_' . preg_replace('/\s+/', '', $hdRow);

                            $additionalHeader[$headerkey] = $hdRow;
                        }
                    }
                }


                header('Content-Type: text/csv; charset=utf-8');
                header("Content-Disposition: attachment; filename=employees_ledger_report_" . (date('Y_m_d_H_i_s', strtotime('now'))) . ".csv");
                $output = fopen('php://output', 'w');

                fputcsv($output, array($data['session']['company_detail']['CompanyName'], '', '', ''));

                fputcsv($output, array(
                    "Exported By",
                    $data['session']['employer_detail']['first_name'] . " " . $data['session']['employer_detail']['last_name']
                ));
                fputcsv($output, array(
                    "Export Date",
                    date('m/d/Y H:i:s ', strtotime('now')) . STORE_DEFAULT_TIMEZONE_ABBR
                ));
                fputcsv($output, array("",));

                fputcsv($output, $additionalHeader);

                if (!empty($data['employeesLedger'])) {
                    foreach ($data['employeesLedger'] as $ledgerRow) {

                        $input = array();

                        $teamDepartment = [];

                        $input['Type'] = $ledgerRow['employee_sid'] == null ? "Company" : "Employee";

                        if ($additionalHeader['employee_id']) {
                            $input['employee_sid'] = $ledgerRow['employee_sid'];
                        }
                        if ($additionalHeader['first_name']) {
                            $input['first_name'] = $ledgerRow['first_name'];
                        }
                        if ($additionalHeader['middle_name']) {
                            $input['middle_name'] = $ledgerRow['middle_name'];
                        }
                        if ($additionalHeader['last_name']) {
                            $input['last_name'] = $ledgerRow['last_name'];
                        }
                        if ($additionalHeader['job_title']) {
                            $input['job_title'] = $ledgerRow['job_title'];
                        }
                        if ($additionalHeader['department'] || $additionalHeader['team']) {
                            $teamDepartment = $ledgerRow['employee_sid'] != null ? getEmployeeDepartmentAndTeams($ledgerRow['employee_sid']) : '';
                        }
                        if ($additionalHeader['department']) {

                            $department = array_column($teamDepartment['departments'], 'name');

                            $departments = $department[0];
                            $input['department'] = $departments;
                        }
                        if ($additionalHeader['team']) {
                            $teams = !empty($teamDepartment['teams']) ? $teamDepartment['teams'][0]['name'] : '';
                            $input['team'] = $teams;
                        }


                        if ($additionalHeader['employee_number']) {
                            $input['employee_number'] = $ledgerRow['employee_number'];
                        }

                        if ($additionalHeader['ssn']) {
                            $input['ssn'] = $ledgerRow['ssn'];
                        }

                        if ($additionalHeader['email']) {
                            $input['email'] = $ledgerRow['email'];
                        }

                        if ($additionalHeader['phone_number']) {
                            $input['phone_number'] = $ledgerRow['PhoneNumber'];
                        }


                        if ($additionalHeader['debit_amount']) {
                            $input['debit_amount'] = $ledgerRow['debit_amount'] ? $ledgerRow['debit_amount'] : '0';
                        }
                        if ($additionalHeader['credit_amount']) {
                            $input['credit_amount'] = $ledgerRow['credit_amount'] ? $ledgerRow['credit_amount'] : '0';
                        }
                        if ($additionalHeader['gross_pay']) {
                            $input['gross_pay'] = $ledgerRow['gross_pay'] ? $ledgerRow['gross_pay'] : '0';
                        }
                        if ($additionalHeader['net_pay']) {
                            $input['net_pay'] = $ledgerRow['net_pay'] ? $ledgerRow['net_pay'] : '0';
                        }
                        if ($additionalHeader['taxes']) {
                            $input['taxes'] = $ledgerRow['taxes'] ? $ledgerRow['taxes'] : '0';
                        }
                        if ($additionalHeader['description']) {
                            $input['description'] = preg_replace('/[^A-Za-z0-9\-]/', '', $ledgerRow['description']);
                        }
                        if ($additionalHeader['transaction_date']) {
                            $input['transaction_date'] = formatDateToDB($ledgerRow['transaction_date'], DB_DATE, DATE);
                        }
                        if ($additionalHeader['start_date']) {
                            $input['start_date'] = formatDateToDB($ledgerRow['start_date'], DB_DATE, DATE);
                        }
                        if ($additionalHeader['end_date']) {
                            $input['end_date'] = formatDateToDB($ledgerRow['end_date'], DB_DATE, DATE);
                        }
                        if ($additionalHeader['imported_at']) {
                            $input['imported_at'] = formatDateToDB($ledgerRow['created_at'], DB_DATE_WITH_TIME, DATE_WITH_TIME);
                        }
                        if ($additionalHeader['account_name']) {
                            $input['account_name'] = $ledgerRow['account_name'];
                        }
                        if ($additionalHeader['account_number']) {
                            $input['account_number'] = $ledgerRow['account_number'];
                        }
                        if ($additionalHeader['general_entry_number']) {
                            $input['general_entry_number'] = $ledgerRow['general_entry_number'];
                        }
                        if ($additionalHeader['reference_number']) {
                            $input['reference_number'] = $ledgerRow['reference_number'];
                        }

                        //                        
                        if (!empty($header)) {
                            if (!empty($ledgerRow['extra_1'])) {
                                foreach ($ledgerRow['extra_1'] as $key => $val) {
                                    $headerkey = 'extra_' . preg_replace('/\s+/', '', $key);
                                    $input[$headerkey] = $val;
                                }
                            }
                        }

                        fputcsv($output, $input);
                    }
                }

                fclose($output);
                exit;
            }

            //
            $this->load
                ->view('main/header', $data)
                ->view('v1/payroll/ledger')
                ->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }
}

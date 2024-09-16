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
        $this->blockMinifyFilesCreation = true;
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
            $data['security_details'] = $data['securityDetails'] = db_get_access_level_details(
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
    public function handler()
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
                                        $dataToInsert['extra'] = ($payroll['extra']);
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
    private function resp($responseArray)
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

        fputcsv($output, array(
            'Employee ID',
            'Employee Number',
            'Employee SSN',
            'Employee Email',
            'Employee Phone Number',
            'Debit',
            'Credit',
            'Taxes',
            'Description',
            'Start Period',
            'End Period',
            'Transaction Date',
            'Account Name',
            'Account Number',
            'Reference Number',
            'Journal Entry Number',
            'First Name',
            'Last Name',
            'Gross Pay',
            'Net Pay'
        ));

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
            if ($start_date_applied != null && $end_date_applied != null) {
                if ($dateSelection == 'transaction') {
                    $between = "payroll_ledger.transaction_date >= '" . $start_date_applied . "' and payroll_ledger.transaction_date <=  '" . $end_date_applied . "'";
                } else {
                    $between = "payroll_ledger.start_date = '" . $start_date_applied . "' and payroll_ledger.end_date = '" . $end_date_applied . "'";
                }
            }

            //
            $data["flag"] = true;
            $data['ledgerCount'] = $this->ledger_model->getEmployeesLedger($company_sid, $between, $filterEmployees, $filterJobTitles, $filterDepartment, null);
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


            $data["columns"] = $this->setColumns();
            $data['employeesLedger'] = $this->ledger_model->getEmployeesLedger($company_sid, $between, $filterEmployees, $filterJobTitles, $filterDepartment, $records_per_page, $my_offset);

            $records = $this->setExtraColumnsWithTotal($data["employeesLedger"]);
            $data["employeesLedger"] = $records["employeesLedger"];
            $data["extraColumns"] = $records["extraColumns"];
            $data["totalArray"] = $records["totalArray"];
            //
            $this->handleExport($data);
            //
            $this->load
                ->view('main/header', $data)
                ->view('v1/payroll/ledger')
                ->view('main/footer');
        } else {
            redirect('login', "refresh");
        }
    }


    //
    public function getledgerBreakdown($sId, $isRegular, $isRegularEmployee, $isExternal)
    {
        return SendResponse(
            200,
            [
                'view' => $this->load->view('payroll/ledger/ledger_breakdown', [
                    'data' => $this->ledger_model->getledgerBreakdown($sId, $isRegular, $isRegularEmployee, $isExternal)
                ], true)
            ]
        );
    }

    /**
     * handles export process
     */
    private function handleExport($data)
    {
        $post = $this->input->post(null, true);
        //
        if (!$post) {
            return false;
        }
        //
        if (!$post["columns"]) {
            return false;
        }

        // set columns
        $columns = array_keys($post["columns"]);
        //
        $tmp = ["Employee/Company"];
        //
        foreach ($columns as $v0) {
            if ($v0 === "employee_ssn") {
                $tmp[] = "Employee SSN";
            } else {
                $tmp[] = SlugToString($v0);
            }
        }
        // set headers
        $headers = $tmp;
        $rows = [];
        // iterate through ledger
        foreach ($data["employeesLedger"] as $v0) {
            // tmp data holder
            $tmp = [];
            // add the company or employee name
            $tmp[] =
                $v0['employee_sid']
                ? remakeEmployeeName($v0, true, true)
                : $data["session"]["company_detail"]["CompanyName"];
            // extra
            $extraColumns = [];
            // iterate through export columns
            foreach ($columns as $v1) {
                // check if index is extra
                if (!array_key_exists($v1, $v0)) {
                    $extraColumns[] = $v1;
                    //
                    $value = $v0["extra_column"][stringToSlug($v1, "")];
                } else {
                    // get the index
                    $value = $v0[$v1];
                }
                //
                if (isDateTime($value)) {
                    $tmp[] = formatDateToDB(
                        $value,
                        false,
                        SITE_DATE
                    );
                } elseif (in_array($v1, [
                    "debit_amount",
                    "credit_amount",
                    "net_pay",
                    "gross_pay",
                    "taxes"
                ])) {
                    //
                    $tmp[] = _a($value ? $value : 0);
                } else {
                    $tmp[] = $value;
                }
            }
            //
            $rows[] = $tmp;
        }

        // here goes the code
        $filename = "payroll_ledger_export_at_" . date("Y_m_d_H_i_s") . ".csv";

        // Set headers to force download the CSV file
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment;filename=' . $filename);
        $output = fopen('php://output', 'w');
        fputcsv($output, $headers);
        foreach ($rows as $row) {
            fputcsv($output, $row);
        }

        // Close the output stream
        fclose($output);
        exit;
    }

    /**
     * handles export process
     */
    private function setColumns()
    {
        return [
            ["slug" => "employee_id", "value" => "Employee Id", "selected" => false],
            ["slug" => "employee_number", "value" => "Employee Number", "selected" => false],
            ["slug" => "employee_ssn", "value" => "Employee SSN", "selected" => false],
            ["slug" => "employee_email", "value" => "Employee Email", "selected" => false],
            ["slug" => "employee_phone_number", "value" => "Employee Phone Number", "selected" => false],
            ["slug" => "debit_amount", "value" => "Debit Amount", "selected" => true],
            ["slug" => "credit_amount", "value" => "Credit Amount", "selected" => true],
            ["slug" => "gross_pay", "selected" => true],
            ["slug" => "net_pay", "selected" => true],
            ["slug" => "taxes", "selected" => true],
            ["slug" => "period_start_date", "value" => "Period Start Date", "selected" => false],
            ["slug" => "period_end_date", "value" => "Period End Date", "selected" => false],
            ["slug" => "transaction_date", "selected" => true],
            ["slug" => "account_name", "selected" => false],
            ["slug" => "account_number", "selected" => false],
            ["slug" => "reference_number", "selected" => false],
            ["slug" => "journal_entry_number", "selected" => false],
            ["slug" => "first_name", "selected" => false],
            ["slug" => "last_name", "selected" => false],
            ["slug" => "department", "selected" => false],
            ["slug" => "team", "selected" => false],
            ["slug" => "job_title", "selected" => false],
            ["slug" => "imported_at", "selected" => false],
            ["slug" => "description", "selected" => false],
        ];
    }


    /**
     * handles export process
     */
    private function setExtraColumnsWithTotal(array $employeesLedger)
    {
        //
        $returnArray =
            [
                "extraColumns" => [],
                "totalArray" => [
                    "debit_amount" => 0,
                    "credit_amount" => 0,
                    "gross_pay" => 0,
                    "net_pay" => 0,
                    "taxes" => 0,
                ]
            ];
        //
        if (!$employeesLedger) {
            return $returnArray;
        }
        //
        $extraColumns = [];
        foreach ($employeesLedger as $k0 => $v0) {
            //
            $returnArray["totalArray"]["debit_amount"] += ($v0["debit_amount"] ? $v0["debit_amount"] : 0);
            $returnArray["totalArray"]["credit_amount"] += ($v0["credit_amount"] ? $v0["credit_amount"] : 0);
            $returnArray["totalArray"]["gross_pay"] += ($v0["gross_pay"] ? $v0["gross_pay"] : 0);
            $returnArray["totalArray"]["net_pay"] += ($v0["net_pay"] ? $v0["net_pay"] : 0);
            $returnArray["totalArray"]["taxes"] += ($v0["taxes"] ? $v0["taxes"] : 0);
            //
            $extraColumnArray = json_decode(
                $v0["extra"],
                true
            );
            //
            $employeesLedger[$k0]["extra_column"] = [];

            if ($extraColumnArray) {
                foreach ($extraColumnArray as $k1 => $v1) {
                    foreach ($v1 as $k2 => $v2) {
                        $extraColumns[stringToSlug($k2, "")] = ucwords($k2);

                        $employeesLedger[$k0]["extra_column"][stringToSlug($k2, "")] = $v2;
                    }
                }
            }
        }
        $returnArray["extraColumns"] = array_values($extraColumns);
        $returnArray["employeesLedger"] = $employeesLedger;

        return $returnArray;
    }
}

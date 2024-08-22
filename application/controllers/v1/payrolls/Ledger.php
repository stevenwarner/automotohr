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

                // _e($formpost['payrolls'],true,true);
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

        fputcsv($output, array('First Name', 'Last Name', 'Email', 'Primary Number', 'Employee ID', 'Employee Number', 'Social Security Number', 'Debit', 'Credit', 'Start Period', 'End Period', 'Transaction Date', 'Job Title/Position', 'Department', 'Gross Pay', 'Net Pay', 'Taxes', 'Description', 'Account Name', 'Account Number', 'Reference Number', 'General Entry Number'));

        fclose($output);
        exit;
    }
}

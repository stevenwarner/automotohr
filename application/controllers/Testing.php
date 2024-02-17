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
        $results = $this->db->select("sid")
            ->get("portal_job_title_templates")
            ->result_array();

        foreach ($results as $v) {
            $this->db->where("sid", $v["sid"])
                ->update("portal_job_title_templates", [
                    "color_code" => generateRandomColor()
                ]);
        }
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

    public function syncEmployeeHomeAddress()
    {
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
                        _e($form, true);
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
                        $employeeInfo['Location_Address'] = $i9FormInfo['section1_address'] . ' ' . $i9FormInfo['section1_apt_number'];
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

            _e($employeeInfo, true);
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
        _e("script process complete", true, true);
    }

    // Time off 
    public function timeOffHistoryMove($employeeSid)
    {

        $results = $this->db->select("*")
            ->where("new_employee_sid", $employeeSid)
            ->order_by('sid', 'Desc')
            ->limit(1)
            ->get("employees_transfer_log")
            ->result_array();

        if (empty($results)) {
            echo "employee transfer log not found";
        } else {

            //
            $adminId = getCompanyAdminSid($results[0]['to_company_sid']);
            $previousEmployeeSid = $results[0]['previous_employee_sid'];
            //
            $this->load->model('timeoff_model');

            $employeeRequests = $this->timeoff_model
                ->getEmployeeRequestsPrevious(
                    $previousEmployeeSid
                );

            //
            if ($employeeRequests) {
                foreach ($employeeRequests as $request) {
                    //Get Ploicy Title
                    $policyData = $this->timeoff_model
                        ->getPreviousPlicyTitle(
                            $request['company_sid'],
                            $request['timeoff_policy_sid']
                        );    

                    // Get Policy Id
                    $newPolicyId = $this->timeoff_model
                        ->getPreviousPlicySid(
                            $results[0]['to_company_sid'],
                            $policyData['title']
                        );
                        
                    if (empty($newPolicyId)) {
                        $policyDetails =
                                $this->timeoff_model->getPolicyDetailsById($request['timeoff_policy_sid']);
                            //
                            unset($policyDetails['sid']);
                            //
                            $policyDetails['company_sid'] = $results[0]['to_company_sid'];
                            $policyDetails['creator_sid'] = $adminId;
                            $policyDetails['type_sid'] = $this->timeoff_model
                                ->checkAndAddType(
                                    $policyDetails['type_sid'],
                                    $results[0]['to_company_sid']
                                );
                            $policyDetails['is_entitled_employee'] = 1;
                            $policyDetails['assigned_employees'] = $employeeSid;

                            // insert the policy
                            $this->db->insert('timeoff_policies', $policyDetails);
                            $newPolicyId['sid'] = $this->db->insert_id();
                    }

                    //
                    $requestId = $request['sid'];
                    $approvedBy = $request['approved_by'];
                    //
                    unset($request['sid']);
                    //
                    $request['company_sid'] = $results[0]['to_company_sid'];
                    $request['employee_sid'] = $results[0]['new_employee_sid'];
                    $request['timeoff_policy_sid'] = $newPolicyId['sid'];
                    $request['creator_sid'] = $request['creator_sid'] ==  $results[0]['new_employee_sid'] ? $results[0]['new_employee_sid'] : $adminId;
                    $request['approved_by'] = $request['approved_by'] ? $adminId : $request['approved_by'];
                    //
                    $whereArray = [
                        'employee_sid' => $results[0]['new_employee_sid'],
                        'timeoff_policy_sid' => $newPolicyId['sid'],
                        'request_from_date' => $request['request_from_date'],
                        'request_to_date' => $request['request_to_date'],
                        'status' => $request['status']
                    ];
                    //
                    if (!$this->db->where($whereArray)->count_all_results('timeoff_requests')) {
                        //
                        $this->db->insert(
                            'timeoff_requests',
                            $request
                        );
                        //
                        if ($approvedBy) {
                            //
                            $newRequestId = $this->db->insert_id();
                            //
                            $comment = $this->timeoff_model
                                ->getRequestApproverComment(
                                    $requestId,
                                    $approvedBy
                                );
                            // Insert the time off timeline
                            $insertTimeline = array();
                            $insertTimeline['request_sid'] = $newRequestId;
                            $insertTimeline['employee_sid'] = $adminId;
                            $insertTimeline['action'] = 'update';
                            $insertTimeline['note'] = json_encode([
                                'status' => $request['status'],
                                'canApprove' => 1,
                                'details' => [
                                    'startDate' => $request['request_from_date'],
                                    'endDate' => $request['request_to_date'],
                                    'time' => $request['requested_time'],
                                    'policyId' => $newPolicyId['sid'],
                                    'policyTitle' => $this->db
                                        ->select('title')
                                        ->where('sid', $newPolicyId['sid'])
                                        ->get('timeoff_policies')->row_array()['title'],
                                ],
                                'comment' => $comment
                            ]);
                            $insertTimeline['created_at'] = getSystemDate();
                            $insertTimeline['updated_at'] = getSystemDate();
                            $insertTimeline['is_moved'] = 0;
                            $insertTimeline['comment'] = $comment;
                            //
                            $this->db->insert('timeoff_request_timeline', $insertTimeline);
                        }
                    }
                }
            }

            echo "Done";
        }
    }

    public function fixDocument ($companyId) {
        $companyDocuments = $this->db
            ->select(
                'sid, document_sid, document_type, offer_letter_type'
            )
            ->where('company_sid', $companyId)
            ->where('document_sid <>', 0)
            ->get('documents_assigned')
            ->result_array();
        //
        if ($companyDocuments) {
            //
            $this->load->model('manage_admin/copy_employees_model');
            //
            $updatedDocumentCount = 0;
            $updatedDocument = [];
            //
            foreach ($companyDocuments as $document) {
                if ($document['document_type'] == 'offer_letter') {
                    $newDocumentID = $this->copy_employees_model->getAssignedOfferLetterId($companyId, $document);
                } else {
                    $newDocumentID = $this->copy_employees_model->getAssignedDocumentId($companyId, $document);   
                }
                //
                if ($document['document_sid'] != $newDocumentID) {
                    //
                    $dataToUpdate = [];
                    $dataToUpdate['document_sid'] = $newDocumentID;
                    $this->db->where('sid', $document['sid']);
                    $this->db->update('documents_assigned', $dataToUpdate);
                    //
                    $updatedDocumentCount++;
                    $updatedDocument[] = $document;
                }
                
            }
        } 
        _e($updatedDocumentCount." document(s) updated.",true);  
        _e($updatedDocument,true,true); 
    }

    public function deleteDocument(){
        $documents = Array
        (
            Array
                (
                    "sid" => 575785,
                    "document_sid" => 8817
                )
        
            ,Array
                (
                    "sid" => 575786,
                    "document_sid" => 8818
                )
        
            ,Array
                (
                    "sid" => 575787,
                    "document_sid" => 8819
                )
        
            ,Array
                (
                    "sid" => 575788,
                    "document_sid" => 8820
                )
        
            ,Array
                (
                    "sid" => 575789,
                    "document_sid" => 8821
                )
        
            ,Array
                (
                    "sid" => 575790,
                    "document_sid" => 8816
                )
        
            ,Array
                (
                    "sid" => 575791,
                    "document_sid" => 8804
                )
        
            ,Array
                (
                    "sid" => 575792,
                    "document_sid" => 8793
                )
        
            ,Array
                (
                    "sid" => 575793,
                    "document_sid" => 8794
                )
        
            ,Array
                (
                    "sid" => 575794,
                    "document_sid" => 8795
                )
        
            ,Array
                (
                    "sid" => 575795,
                    "document_sid" => 8796
                )
        
            ,Array
                (
                    "sid" => 575796,
                    "document_sid" => 8797
                )
        
            ,Array
                (
                    "sid" => 575797,
                    "document_sid" => 8798
                )
        
            ,Array
                (
                    "sid" => 575798,
                    "document_sid" => 8799
                )
        
            ,Array
                (
                    "sid" => 575799,
                    "document_sid" => 8801
                )
        
            ,Array
                (
                    "sid" => 575800,
                    "document_sid" => 8802
                )
        
            ,Array
                (
                    "sid" => 575801,
                    "document_sid" => 8803
                )
        
            ,Array
                (
                    "sid" => 575802,
                    "document_sid" => 8792
                )
        
            ,Array
                (
                    "sid" => 575803,
                    "document_sid" => 8805
                )
        
            ,Array
                (
                    "sid" => 575804,
                    "document_sid" => 8806
                )
        
            ,Array
                (
                    "sid" => 575805,
                    "document_sid" => 8807
                )
        
            ,Array
                (
                    "sid" => 575806,
                    "document_sid" => 8808
                )
        
            ,Array
                (
                    "sid" => 575807,
                    "document_sid" => 8809
                )
        
            ,Array
                (
                    "sid" => 575808,
                    "document_sid" => 8810
                )
        
            ,Array
                (
                    "sid" => 575809,
                    "document_sid" => 8811
                )
        
            ,Array
                (
                    "sid" => 575810,
                    "document_sid" => 8812
                )
        
            ,Array
                (
                    "sid" => 575811,
                    "document_sid" => 8813
                )
        
            ,Array
                (
                    "sid" => 575812,
                    "document_sid" => 8814
                )
        
            ,Array
                (
                    "sid" => 575813,
                    "document_sid" => 8815
                )
        
            ,Array
                (
                    "sid" => 575814,
                    "document_sid" => 10555
                )
        
            ,Array
                (
                    "sid" => 594355,
                    "document_sid" => 8799
                )
        
            ,Array
                (
                    "sid" => 594356,
                    "document_sid" => 8809
                )
        
            ,Array
                (
                    "sid" => 594357,
                    "document_sid" => 8819
                )
        
            ,Array
                (
                    "sid" => 594358,
                    "document_sid" => 8797
                )
        
            ,Array
                (
                    "sid" => 594359,
                    "document_sid" => 8802
                )
        
            ,Array
                (
                    "sid" => 594360,
                    "document_sid" => 10555
                )
        
            ,Array
                (
                    "sid" => 594361,
                    "document_sid" => 8817
                )
        
            ,Array
                (
                    "sid" => 594362,
                    "document_sid" => 8818
                )
        
            ,Array
                (
                    "sid" => 594363,
                    "document_sid" => 8820
                )
        
            ,Array
                (
                    "sid" => 594364,
                    "document_sid" => 8821
                )
        
            ,Array
                (
                    "sid" => 594365,
                    "document_sid" => 8816
                )
        
            ,Array
                (
                    "sid" => 594366,
                    "document_sid" => 8804
                )
        
            ,Array
                (
                    "sid" => 594367,
                    "document_sid" => 8794
                )
        
            ,Array
                (
                    "sid" => 594368,
                    "document_sid" => 8795
                )
        
            ,Array
                (
                    "sid" => 594369,
                    "document_sid" => 8796
                )
        
            ,Array
                (
                    "sid" => 594370,
                    "document_sid" => 8798
                )
        
            ,Array
                (
                    "sid" => 594371,
                    "document_sid" => 8801
                )
        
            ,Array
                (
                    "sid" => 594372,
                    "document_sid" => 8792
                )
        
            ,Array
                (
                    "sid" => 594373,
                    "document_sid" => 8805
                )
        
            ,Array
                (
                    "sid" => 594374,
                    "document_sid" => 8806
                )
        
            ,Array
                (
                    "sid" => 594375,
                    "document_sid" => 8807
                )
        
            ,Array
                (
                    "sid" => 594376,
                    "document_sid" => 8808
                )
        
            ,Array
                (
                    "sid" => 594377,
                    "document_sid" => 8811
                )
        
            ,Array
                (
                    "sid" => 594378,
                    "document_sid" => 8812
                )
        
            ,Array
                (
                    "sid" => 594379,
                    "document_sid" => 8813
                )
        
            ,Array
                (
                    "sid" => 594380,
                    "document_sid" => 8814
                )
        
            ,Array
                (
                    "sid" => 594381,
                    "document_sid" => 8815
                )
        
            ,Array
                (
                    "sid" => 594382,
                    "document_sid" => 8810
                )
        
            ,Array
                (
                    "sid" => 594383,
                    "document_sid" => 8793
                )
        
            ,Array
                (
                    "sid" => 604720,
                    "document_sid" => 10555
                )
        
            ,Array
                (
                    "sid" => 618275,
                    "document_sid" => 9489
                )
        
            ,Array
                (
                    "sid" => 618276,
                    "document_sid" => 9490
                )
        
            ,Array
                (
                    "sid" => 618277,
                    "document_sid" => 9492
                )
        
            ,Array
                (
                    "sid" => 618278,
                    "document_sid" => 9493
                )
        
            ,Array
                (
                    "sid" => 618279,
                    "document_sid" => 9488
                )
        
            ,Array
                (
                    "sid" => 618280,
                    "document_sid" => 9476
                )
        
            ,Array
                (
                    "sid" => 618281,
                    "document_sid" => 9466
                )
        
            ,Array
                (
                    "sid" => 618282,
                    "document_sid" => 9467
                )
        
            ,Array
                (
                    "sid" => 618283,
                    "document_sid" => 9468
                )
        
            ,Array
                (
                    "sid" => 618284,
                    "document_sid" => 9470
                )
        
            ,Array
                (
                    "sid" => 618285,
                    "document_sid" => 9473
                )
        
            ,Array
                (
                    "sid" => 618286,
                    "document_sid" => 9464
                )
        
            ,Array
                (
                    "sid" => 618287,
                    "document_sid" => 9477
                )
        
            ,Array
                (
                    "sid" => 618288,
                    "document_sid" => 9478
                )
        
            ,Array
                (
                    "sid" => 618289,
                    "document_sid" => 9479
                )
        
            ,Array
                (
                    "sid" => 618290,
                    "document_sid" => 9480
                )
        
            ,Array
                (
                    "sid" => 618291,
                    "document_sid" => 9483
                )
        
            ,Array
                (
                    "sid" => 618292,
                    "document_sid" => 9484
                )
        
            ,Array
                (
                    "sid" => 618293,
                    "document_sid" => 9485
                )
        
            ,Array
                (
                    "sid" => 618294,
                    "document_sid" => 9486
                )
        
            ,Array
                (
                    "sid" => 618295,
                    "document_sid" => 9487
                )
        
            ,Array
                (
                    "sid" => 618296,
                    "document_sid" => 9465
                )
        
            ,Array
                (
                    "sid" => 618297,
                    "document_sid" => 9469
                )
        
            ,Array
                (
                    "sid" => 618298,
                    "document_sid" => 9471
                )
        
            ,Array
                (
                    "sid" => 618299,
                    "document_sid" => 9474
                )
        
            ,Array
                (
                    "sid" => 618300,
                    "document_sid" => 9481
                )
        
            ,Array
                (
                    "sid" => 618301,
                    "document_sid" => 9482
                )
        
            ,Array
                (
                    "sid" => 618302,
                    "document_sid" => 10715
                )
        
            ,Array
                (
                    "sid" => 618303,
                    "document_sid" => 9491
                )
        
        );;
        //
        foreach ($documents as $doc) {
            $dataToUpdate = [];
            $dataToUpdate['document_sid'] = $doc['document_sid'];
            $this->db->where('sid', $doc['sid']);
            $this->db->update('documents_management', $dataToUpdate);
        }
        echo count($documents);
        _e($documents,true);
    }
}

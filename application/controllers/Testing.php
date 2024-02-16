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

    public function fixDocument ($company_sid, $user_sid) {
        $assigned_documents = $this->hr_documents_management_model->get_assigned_documents($company_sid, 'employee', $user_sid, 0, 1, 0, 0);
        //
        $payroll_sids = $this->hr_documents_management_model->get_payroll_documents_sids();
        $documents_management_sids = $payroll_sids['documents_management_sids'];
        $documents_assigned_sids = $payroll_sids['documents_assigned_sids'];
        //        
        foreach ($assigned_documents as $key => $assigned_document) {
            //
            if (in_array($assigned_document['document_sid'], $documents_management_sids)) {
                $assigned_document['pay_roll_catgory'] = 1;
            } else if (in_array($assigned_document['sid'], $documents_assigned_sids)) {
                $assigned_document['pay_roll_catgory'] = 1;
            } else {
                $assigned_document['pay_roll_catgory'] = 0;
            }
            //
            if ($assigned_document['document_sid'] == 0) {
                if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                    if ($assigned_document['pay_roll_catgory'] == 0) {
                        $assigned_sids[] = $assigned_document['document_sid'];
                        $no_action_required_sids[] = $assigned_document['document_sid'];
                        $no_action_required_documents[] = $assigned_document;
                        unset($assigned_documents[$key]);
                    } else if ($assigned_document['pay_roll_catgory'] == 1) {
                        $no_action_required_payroll_documents[] = $assigned_document;
                        unset($assigned_documents[$key]);
                    }
                }
            } else {
                //
                $assigned_document['archive'] = $assigned_document['archive'] == 1 || $assigned_document['company_archive'] == 1 ? 1 : 0;
                //
                if ($assigned_document['user_consent'] == 1) {
                    $assigned_document['archive'] = 0;
                }
                //
                if ($assigned_document['archive'] == 0) {
                    //
                    //check is this approver document
                    $is_approval_document = $this->hr_documents_management_model->check_if_approval_document($user_type, $user_sid, $assigned_document['document_sid']);
                    //
                    if (!empty($is_approval_document)) {
                        $assigned_documents[$key]["approver_document"] = 1;
                        $assigned_documents[$key]["approver_managers"] = implode(",", array_column($is_approval_document, "assigner_sid"));
                    } else {
                        $assigned_documents[$key]["approver_document"] = 0;
                    }
                    //
                    //check Document Previous History
                    $previous_history = $this->hr_documents_management_model->check_if_document_has_history($user_type, $user_sid, $assigned_document['sid']);
                    //
                    if (!empty($previous_history)) {
                        array_push($history_doc_sids, $assigned_document['sid']);
                    }
                    //
                    $is_magic_tag_exist = 0;
                    $is_document_completed = 0;
                    $is_document_authorized = 0;
                    $authorized_sign_status = 0;

                    if (!empty($assigned_document['document_description']) && ($assigned_document['document_type'] == 'generated' || $assigned_document['document_type'] == 'hybrid_document')) {
                        $document_body = $assigned_document['document_description'];
                        //$magic_codes = array('{{signature}}', '{{signature_print_name}}', '{{inital}}', '{{sign_date}}', '{{short_text}}', '{{text}}', '{{text_area}}', '{{checkbox}}', 'select');
                        $magic_codes = array('{{signature}}', '{{inital}}');

                        if (str_replace($magic_codes, '', $document_body) != $document_body) {
                            $is_magic_tag_exist = 1;
                        }

                        if (str_replace('{{authorized_signature}}', '', $document_body) != $document_body) {

                            $assign_on = date("Y-m-d", strtotime($assigned_document['assigned_date']));
                            $compare_date = date("Y-m-d", strtotime('2020-03-04'));

                            // if (!empty($assigned_document['form_input_data'] || $assign_on >= $compare_date )) {
                            if ($assign_on >= $compare_date || !empty($assigned_document['form_input_data'])) {
                                $is_document_authorized = 1;
                            }

                            // if ($assigned_document['user_consent'] == 1 && !empty($assigned_document['authorized_signature'])) {
                            if (!empty($assigned_document['authorized_signature'])) {
                                $authorized_sign_status = 1;
                            } else {
                                $authorized_sign_status = 0;
                            }

                            $assign_managers = $this->hr_documents_management_model->get_document_authorized_managers($company_sid, $assigned_document["sid"]);
                            $assigned_documents[$key]["assign_managers"] = implode(",", array_column($assign_managers, "assigned_to_sid"));
                        }
                    }

                    $assigned_documents[$key]['is_document_authorized'] = $assigned_document['is_document_authorized'] = $is_document_authorized;
                    $assigned_documents[$key]['authorized_sign_status'] = $assigned_document['authorized_sign_status'] = $authorized_sign_status;

                    if ($assigned_document['document_type'] != 'offer_letter') {
                        if ($assigned_document['document_type'] == 'uploaded') {
                            if (strpos($assigned_document['document_s3_name'], '&') !== false) {
                                $assigned_documents[$key]['document_s3_name'] = modify_AWS_file_name($assigned_document['sid'], $assigned_document['document_s3_name'], "document_s3_name");
                            }

                            if (strpos($assigned_document['uploaded_file'], '&') !== false) {
                                $assigned_documents[$key]['uploaded_file'] = modify_AWS_file_name($assigned_document['sid'], $assigned_document['uploaded_file'], "uploaded_file");
                            }
                        }
                        //
                        if ($assigned_document['status'] == 1) {
                            if ($assigned_document['acknowledgment_required'] || $assigned_document['download_required'] || $assigned_document['signature_required'] || $is_magic_tag_exist) {

                                // if ($is_document_authorized) {
                                //     if ($assigned_document['user_consent'] == 1 && !empty($assigned_document['authorized_signature'])) {
                                //         $is_document_completed = 1;
                                //     } else {
                                //         $is_document_completed = 0;
                                //     }
                                // } else 

                                if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['download_required'] == 1) {
                                    if ($is_magic_tag_exist == 1) {
                                        if ($assigned_document['uploaded'] == 1) {
                                            $is_document_completed = 1;
                                        } else {
                                            $is_document_completed = 0;
                                        }
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['acknowledged'] == 1 && $assigned_document['downloaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['download_required'] == 1 && $assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['acknowledgment_required'] == 1) {
                                    if ($assigned_document['acknowledged'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['download_required'] == 1) {
                                    if ($assigned_document['downloaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($assigned_document['signature_required'] == 1) {
                                    if ($assigned_document['uploaded'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                } else if ($is_magic_tag_exist == 1) {
                                    if ($assigned_document['user_consent'] == 1) {
                                        $is_document_completed = 1;
                                    } else {
                                        $is_document_completed = 0;
                                    }
                                }

                                if ($is_document_completed > 0) {
                                    if ($assigned_document['pay_roll_catgory'] == 0) {

                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        $signed_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                        $signed_document_sids[] = $assigned_document['document_sid'];
                                        $completed_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                } else {
                                    if ($assigned_document['pay_roll_catgory'] == 1) {
                                        $uncompleted_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }

                                    $assigned_sids[] = $assigned_document['document_sid'];
                                }
                            } else {
                                if ($is_document_authorized == 1) {
                                    //
                                    if ($authorized_sign_status == 1) {
                                        if ($assigned_document['pay_roll_catgory'] == 0) {
                                            $signed_document_sids[] = $assigned_document['document_sid'];
                                            $signed_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                            $signed_document_sids[] = $assigned_document['document_sid'];
                                            $completed_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    } else {
                                        if ($assigned_document['pay_roll_catgory'] == 1) {
                                            $uncompleted_payroll_documents[] = $assigned_document;
                                            unset($assigned_documents[$key]);
                                        }
                                    }
                                    //
                                    $assigned_sids[] = $assigned_document['document_sid'];
                                    //
                                } else if ($assigned_document['pay_roll_catgory'] == 0) {
                                    if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                                        $assigned_sids[] = $assigned_document['document_sid'];
                                        $no_action_required_sids[] = $assigned_document['document_sid'];
                                        $no_action_required_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                } else if ($assigned_document['pay_roll_catgory'] == 1) {
                                    if ($assigned_document['status'] == 1 && $assigned_document['archive'] == 0) {
                                        $no_action_required_payroll_documents[] = $assigned_document;
                                        unset($assigned_documents[$key]);
                                    }
                                }
                            }
                        } else {
                            $revoked_sids[] = $assigned_document['document_sid'];
                        }
                    }
                } else if ($assigned_document['archive'] == 1) {
                    unset($assigned_documents[$key]);
                }
            }
        }

        
        _e($assigned_sids,true); 
        _e($revoked_sids,true); 
        _e($signed_document_sids,true); 
    }
}

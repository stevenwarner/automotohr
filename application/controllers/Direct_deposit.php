<?php defined('BASEPATH') or exit('No direct script access allowed');

class Direct_deposit extends Public_Controller
{
    //
    private $layout;
    //
    public function __construct()
    {
        parent::__construct();

        $this->load->model('direct_deposit_model');
        $this->load->model('dashboard_model');
        $this->load->model('application_tracking_system_model');

        //
        $this->layout = true;
    }

    public function index($type = NULL, $sid = NULL, $jobs_listing = NULL)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all
            //getting userdata from DB
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $company_sid = $data["session"]["company_detail"]["sid"];
            $company_name = $data['session']['company_detail']['CompanyName'];

            //
            if ($type == 'employee') {
                $exits = $this->direct_deposit_model->checkDDI($type, $sid, $company_sid);
                //
                if ($exits == 0) {
                    //$type = 'applicant';
                    // not understand this logic
                }
            }

            if ($sid == NULL && $type == NULL) {
                $sid = $employer_sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data['title'] = 'Direct Deposit Information';
                $reload_location = 'direct_deposit';
                $type = 'employee';
                $data["return_title_heading"] = "My Profile";
                $data["return_title_heading_link"] = base_url('my_profile');
                $cancel_url = 'my_profile/';
                $data['cancel_url'] = $cancel_url;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($employer_sid, 'employee');
                $load_view = check_blue_panel_status(false, 'self');
                $data["employee"] = $data['session']['employer_detail'];
            } else if ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_occupational_license_info');  // Param2: Redirect URL, Param3: Function Name
                $data = employee_right_nav($sid);
                $data['security_details'] = $security_details;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data['title'] = 'Direct Deposit Information';
                $reload_location = 'direct_deposit/employee/' . $sid;
                $data["return_title_heading"] = "Employee Profile";
                $data["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
                $cancel_url = 'employee_profile/' . $sid;
                // getting applicant ratings - getting average rating of applicant
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'employee');
                $data['cancel_url'] = $cancel_url;
                $load_view = check_blue_panel_status(false, $type);
                $data['employer'] = $this->application_tracking_system_model->get_company_detail($sid);

                //
                $employee_number = $this->direct_deposit_model->get_user_extra_info('employee', $sid, $company_sid);
                $data['data'] = $this->direct_deposit_model->getDDI('employee', $sid, $company_sid);
                $data['dd_user_type'] = 'employee';
                $data['dd_user_sid'] = $sid;
                $data['company_id'] = $company_sid;
                $data['company_name'] = $company_name;
                $data['employee_number'] = $employee_number;
                $users_sign_info = get_e_signature($company_sid, $sid, 'employee');
                $data['users_sign_info'] = $users_sign_info;
                $data['cn'] = $this->direct_deposit_model->getUserData($sid, 'employee');
                $data['send_email_notification'] = 'yes';
                //

            } else if ($type == 'applicant') {
                if ($data['session']['employer_detail']['access_level'] != "Hiring Manager") {
                    check_access_permissions($security_details, 'application_tracking', 'direct_deposit_info');  // Param2: Redirect URL, Param3: Function Name
                }

                $data = applicant_right_nav($sid);
                $data['security_details'] = $security_details;
                $left_navigation = 'manage_employer/application_tracking_system/profile_right_menu_applicant';
                $data['title'] = 'Applicant Direct Deposit Information';
                $reload_location = 'direct_deposit/applicant/' . $sid . '/' . $jobs_listing;
                $cancel_url = 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data["return_title_heading"] = "Applicant Profile";
                $data["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid . '/' . $jobs_listing;
                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'applicant'); //getting average rating of applicant
                $data['cancel_url'] = $cancel_url;
                $load_view = check_blue_panel_status(false, $type);

                $applicant_info = $this->dashboard_model->get_applicants_details($sid);
                // $data['applicant_info'] = $applicant_info;
                //getting Company accurate backgroud check
                $data['company_background_check'] = checkCompanyAccurateCheck($company_sid);

                //Outsourced HR Compliance and Onboarding check
                $data['kpa_onboarding_check'] = checkCompanyKpaOnboardingCheck($company_sid);
                $data_employer = array(
                    'sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number'],
                    'profile_picture' => $applicant_info['pictures'],
                    'user_type' => 'Applicant'
                );

                $data['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($applicant_info['sid'], 'applicant'); //getting average rating of applicant
                $data['employer'] = $data_employer;

                //
                $employee_number = $this->direct_deposit_model->get_user_extra_info('applicant', $sid, $company_sid);
                $data['data'] = $this->direct_deposit_model->getDDI('applicant', $sid, $company_sid);
                $data['dd_user_type'] = 'applicant';
                $data['dd_user_sid'] = $sid;
                $data['company_id'] = $company_sid;
                $data['company_name'] = $company_name;
                $data['employee_number'] = $employee_number;
                $users_sign_info = get_e_signature($company_sid, $sid, 'applicant');
                $data['users_sign_info'] = $users_sign_info;
                $data['cn'] = $this->direct_deposit_model->getUserData($sid, 'applicant');
                $data['send_email_notification'] = 'no';
                //

            }

            $page = 'index';
            if ($this->layout) {
                $direct_deposit_information = $this->direct_deposit_model->getDDI($type, $sid, $company_sid);
                $data['ddi'] = $direct_deposit_information;
                $page = 'index_new';
            } else {
                $direct_deposit_information = $this->direct_deposit_model->get_direct_deposit_details($type, $sid, $company_sid);
                $data['direct_deposit_information'] = $direct_deposit_information;
            }
            $data['left_navigation'] = $left_navigation;

            // $data['users_type'] = $type;
            // $data['users_sid'] = $sid;
            // $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;
            $data['job_list_sid'] = $jobs_listing;
            $data['load_view'] = $load_view;

            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim');

            if ($this->form_validation->run() == false) {
                //load views
                $this->load->view('main/header', $data);
                $this->load->view('direct_deposit/' . $page . '');
                $this->load->view('main/footer');
            } else {
                $perform_action = $this->input->post('perform_action');
                switch ($perform_action) {
                    case 'update_bank_details':

                        $users_sid = $this->input->post('users_sid');
                        $users_type = $this->input->post('users_type');
                        $company_sid = $this->input->post('company_sid');
                        $employer_sid = $this->input->post('employer_sid');
                        $account_title = $this->input->post('account_title');
                        $routing_transaction_number = $this->input->post('routing_transaction_number');
                        $account_number = $this->input->post('account_number');
                        $financial_institution_name = $this->input->post('financial_institution_name');
                        $account_type = $this->input->post('account_type');

                        $data_to_save = array();
                        $data_to_save['users_sid'] = $users_sid;
                        $data_to_save['users_type'] = $users_type;
                        $data_to_save['company_sid'] = $company_sid;
                        $data_to_save['account_title'] = $account_title;
                        $data_to_save['routing_transaction_number'] = $routing_transaction_number;
                        $data_to_save['account_number'] = $account_number;
                        $data_to_save['financial_institution_name'] = $financial_institution_name;
                        $data_to_save['account_type'] = $account_type;

                        $this->direct_deposit_model->save_direct_deposit_details($users_type, $users_sid, $data_to_save);

                        $this->session->set_flashdata('message', '<strong>Success</strong> Direct Deposit Details Updated!');

                        redirect($reload_location, 'refresh');
                        break;
                }
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }


    //
    function add()
    {
        $session = $this->session->userdata('logged_in');
        //
        if (!$session) {
            echo 'failed';
            exit(0);
        }
        //getting userdata from DB
        $employerId = $session["employer_detail"]["sid"];
        $companyId = $session["company_detail"]["sid"];
        //
        $post = $this->input->post(NULL);
        //
        $accountStatus = $this->direct_deposit_model->getDDStatus($post['user_sid'], $post['user_type']);
        //
        $ins = [];
        $ins['company_sid'] = $companyId;
        $ins['users_type'] = $post['user_type'];
        $ins['users_sid'] = $post['user_sid'];
        $ins['account_title'] = $post['title'];
        $ins['routing_transaction_number'] = $post['routingNumber'];
        $ins['account_number'] = $post['accountNumber'];
        $ins['financial_institution_name'] = $post['bankName'];
        $ins['account_type'] = $post['accountType'];
        $ins['account_percentage'] = $post['accountPercentage'];
        // $ins['account_status'] = $post['accountStatus'] == 0 ? 'normal' : (  $post['accountStatus'] == 1 ? 'primary' : 'secondary');
        $ins['account_status'] = $accountStatus;
        // Upload file
        $ins['voided_cheque'] = $_FILES['file']['name']; //upload_file_to_aws('file', $companyId, str_replace(' ', '_', $_FILES['file']['name']), $employerId, AWS_S3_BUCKET_NAME)
        //
        $inserted = $this->direct_deposit_model->insertDDA($ins);
        //
        echo $inserted ? $inserted : 'failed';
    }

    //
    function edit()
    {
        $session = $this->session->userdata('logged_in');
        //
        if (!$session) {
            echo 'failed';
            exit(0);
        }
        //getting userdata from DB
        $employerId = $session["employer_detail"]["sid"];
        $companyId = $session["company_detail"]["sid"];
        //
        $post = $this->input->post(NULL);
        //
        $ins = [];
        $ins['account_title'] = $post['title'];
        $ins['routing_transaction_number'] = $post['routingNumber'];
        $ins['account_number'] = $post['accountNumber'];
        $ins['financial_institution_name'] = $post['bankName'];
        $ins['account_type'] = $post['accountType'];
        $ins['account_percentage'] = $post['accountPercentage'];
        // $ins['account_status'] = $post['accountStatus'] == 0 ? 'normal' : (  $post['accountStatus'] == 1 ? 'primary' : 'secondary');
        // Upload file
        if (count($_FILES)) $ins['voided_cheque'] = $_FILES['file']['name']; //upload_file_to_aws('file', $companyId, str_replace(' ', '_', $_FILES['file']['name']), $employerId, AWS_S3_BUCKET_NAME)
        //
        $inserted = $this->direct_deposit_model->updateDDA($ins, $post['sid']);
        //
        echo 'success';
    }

    //
    public function update_primary($sid)
    {
        $this->direct_deposit_model->updateDD($sid);
        echo 'success';
    }


    //
    function pd($userType, $userSid, $companySid, $type)
    {
        //
        $data = [];
        $data['session'] = $this->session->userdata('logged_in');
        $data['users_type'] = $userType;
        $data['users_sid'] = $userSid;
        $data['type'] = $type;
        $employee_number = $this->direct_deposit_model->get_user_extra_info($userType, $userSid, $companySid);
        $data['employee_number'] = $employee_number;
        $data['data'] = $this->direct_deposit_model->getDDI($userType, $userSid, $companySid);
        //
        $data['data'][0]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][0]['voided_cheque']));
        if (isset($data['data'][1])) $data['data'][1]['voided_cheque_64'] = 'data:image/' . (getFileExtension($data['data'][0]['voided_cheque'])) . ';base64,' . base64_encode(getFileData(AWS_S3_BUCKET_URL . $data['data'][1]['voided_cheque']));

        $data[$userType] = $data['cn'] = $this->direct_deposit_model->getUserData($userSid, $userType);
        //
        $this->load->view('direct_deposit/pd', $data);
    }

    public function ajax_handler()
    {
        $session = $this->session->userdata('logged_in');
        //
        if (empty($session)) {
            $company_name = getCompanyName($_POST['user_sid'], $_POST['user_type']);
            $session["employer_detail"]["sid"] = $_POST['user_sid'];
            $session['company_detail']['CompanyName'] = $company_name;
        }
        //
        if ($session) {

            $updated_by                 = $session["employer_detail"]["sid"];
            $record_sid                 = $_POST['record_sid'];
            $company_sid                = $_POST['company_sid'];
            $user_type                  = $_POST['user_type'];
            $user_sid                   = $_POST['user_sid'];
            $instructions               = $_POST['instructions'];
            $account_title              = $_POST['account_title'];
            $financial_institution_name = $_POST['financial_institution_name'];
            $account_type               = $_POST['account_type'];
            $routing_transaction_number = $_POST['routing_transaction_number'];
            $account_number             = $_POST['account_number'];
            $deposit_type               = $_POST['deposit_type'];
            $account_percentage         = $_POST['account_percentage'];
            $drawn_signature            = $_POST['drawn_signature'];
            $employee_number            = $_POST['employee_number'];
            $print_name                 = $_POST['print_name'];
            $consent_date               = $_POST['consent_date'];
            $send_email_notification    = $_POST['send_email_notification'];

            $cheque_img                 = '';

            if (!empty($_FILES) && isset($_FILES['upload_img']) && $_FILES['upload_img']['size'] > 0) {
                $update_incident_img = upload_file_to_aws('upload_img', $company_sid, 'upload_img', '', AWS_S3_BUCKET_NAME);
                $cheque_img             = $update_incident_img;
            }
            //
            if ($this->input->post('user_type', true) == 'employee') {
                //
                $this->load->model('2022/User_model', 'em');
                //
                $this->em->handleGeneralDocumentChange(
                    'directDeposit',
                    $this->input->post(null, true),
                    $cheque_img,
                    $this->input->post('user_sid', true),
                    $this->session->userdata('logged_in')['employer_detail']['sid']
                );

                // for payroll
                if (isEmployeeOnPayroll($this->input->post('user_sid', true))) {
                    // run payroll set up
                    // for now just sign the document
                    $this->load->model('v1/Documents_management_model', 'documents_management_model');
                    //
                    $this->documents_management_model->checkAndSignDocument(
                        'direct_deposit',
                        $user_sid
                    );
                    $this->load->model('v1/Payroll_model', 'payroll_model');
                    //
                    $this
                        ->payroll_model
                        ->syncEmployeePaymentMethod(
                            $user_sid
                        );
                }
            }

            $this->direct_deposit_model->set_user_extra_info($user_type, $user_sid, $company_sid, $employee_number);

            if ($record_sid != 0) {

                // fetch old record and save it into history table start
                $update_by_sid = $session['employer_detail']['sid'];
                // $old_record = $this->direct_deposit_model->getDDI($user_type, $user_sid, $company_sid);
                $old_record = $this->direct_deposit_model->getPreviousDDI($user_type, $user_sid, $company_sid, $record_sid);
                $old_bank_record = $old_record[0];
                unset($old_bank_record['sid']);
                $old_bank_record['bank_account_details_sid'] = $record_sid;
                $old_bank_record['update_by_sid'] = $update_by_sid;
                $this->direct_deposit_model->insert_bank_detail_history($old_bank_record);
                // fetch old record and save it into history table end

                $data_to_update = array();
                $data_to_update['company_sid'] = $company_sid;
                $data_to_update['users_type'] = $user_type;
                $data_to_update['users_sid'] = $user_sid;
                $data_to_update['instructions'] = $instructions;
                $data_to_update['account_title'] = $account_title;
                $data_to_update['routing_transaction_number'] = $routing_transaction_number;
                $data_to_update['account_number'] = $account_number;
                $data_to_update['financial_institution_name'] = $financial_institution_name;
                $data_to_update['account_type'] = $account_type;

                if ($cheque_img != '') {
                    $data_to_update['voided_cheque'] = $cheque_img;
                }

                $data_to_update['deposit_type'] = $deposit_type;
                $data_to_update['account_percentage'] = $account_percentage;
                $data_to_update['user_signature'] = $drawn_signature;
                $data_to_update['employee_number'] = $employee_number;
                $data_to_update['print_name'] = $print_name;
                $data_to_update['consent_date'] = date("Y-m-d", strtotime($consent_date));
                $data_to_update['is_consent'] = 1;
                $data_to_update['updated_by'] = $updated_by;

                $this->direct_deposit_model->updateDDA($data_to_update, $record_sid);

                $this->checkAndUpdateBankDetail(
                    $user_sid,
                    $old_bank_record,
                    $data_to_update
                );
            } else {
                $data_to_insert = array();
                $data_to_insert['company_sid'] = $company_sid;
                $data_to_insert['users_type'] = $user_type;
                $data_to_insert['users_sid'] = $user_sid;
                $data_to_insert['instructions'] = $instructions;
                $data_to_insert['account_title'] = $account_title;
                $data_to_insert['routing_transaction_number'] = $routing_transaction_number;
                $data_to_insert['account_number'] = $account_number;
                $data_to_insert['financial_institution_name'] = $financial_institution_name;
                $data_to_insert['account_type'] = $account_type;
                $data_to_insert['voided_cheque'] = $cheque_img;
                $data_to_insert['deposit_type'] = $deposit_type;
                $data_to_insert['account_percentage'] = $account_percentage;
                $data_to_insert['user_signature'] = $drawn_signature;
                $data_to_insert['employee_number'] = $employee_number;
                $data_to_insert['print_name'] = $print_name;
                $data_to_insert['consent_date'] = date("Y-m-d", strtotime($consent_date));
                $data_to_insert['is_consent'] = 1;
                $data_to_insert['updated_by'] = $updated_by;

                $this->direct_deposit_model->insertDDA($data_to_insert);
            }
            //
            checkAndUpdateDD($user_sid, $user_type, $company_sid, 'direct_deposit');

            //
            $cpArray = [];
            $cpArray['company_sid'] = $company_sid;
            $cpArray['user_sid'] = $user_sid;
            $cpArray['user_type'] = $user_type;
            $cpArray['document_sid'] = 0;
            $cpArray['document_type'] = 'direct_deposit_information';
            //
            checkAndInsertCompletedDocument($cpArray);

            $userData = $this->direct_deposit_model->getUserData($user_sid, $user_type);
            if ($send_email_notification == 'yes') {
                // Send document completion alert
                broadcastAlert(
                    DOCUMENT_NOTIFICATION_TEMPLATE,
                    'general_information_status',
                    'direct_deposit_information',
                    $company_sid,
                    $session['company_detail']['CompanyName'],
                    $userData['first_name'],
                    $userData['last_name'],
                    $user_sid,
                    [],
                    $user_type

                );
            }

            echo 'success';
        }
    }

    private function checkAndUpdateBankDetail(
        $employeeId,
        $previousDetail,
        $newDetail
    ) {

        // New employee profile data
        $newBankData = [];
        $newBankData['account_type'] = $dataToInsert['account_type'];
        $newBankData['account_number'] = $dataToInsert['account_number'];
        $newBankData['routing_transaction_number'] = $dataToInsert['routing_transaction_number'];
        $newBankData['financial_institution_name'] = $dataToInsert['financial_institution_name'];
        //
        // Old employee profile data
        $oldBankData = [];
        $oldBankData['account_type'] = $previousDetail['account_type'];
        $oldBankData['account_number'] = $previousDetail['account_number'];
        $oldBankData['routing_transaction_number'] = $previousDetail['routing_transaction_number'];
        $oldBankData['financial_institution_name'] = $previousDetail['financial_institution_name'];
        //
        $profileDifference = $this->findDifference($oldBankData, $newBankData);
        //
        if ($profileDifference['profile_changed'] == 1) {
            // want to discuss with mubashir
            // $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
            // $this->gusto_payroll_model->updateGustoEmployeInfo($employeeId, 'bank_detail');
        }
    }

    /**
     * 
     */
    function findDifference($previous_data, $form_data)
    {
        // 
        $profile_changed = 0;
        //
        $dt = [];
        //
        if (!empty($previous_data)) {
            foreach ($previous_data as $key => $data) {
                //
                if (!isset($form_data[$key])) {
                    continue;
                }
                //   
                if ((isset($form_data[$key])) && strip_tags($data) != strip_tags($form_data[$key])) {
                    //
                    $dt[$key] = [
                        'old' => $data,
                        'new' => $form_data[$key]
                    ];
                    //
                    $profile_changed = 1;
                }
            }
        }
        //

        return ['profile_changed' => $profile_changed, 'data' => $dt];
    }

    public function get_e_signature()
    {

        $user_sid                   = $_POST['user_sid'];
        $user_type                  = $_POST['user_type'];
        $company_sid                = $_POST['company_sid'];

        $signature = get_e_signature($company_sid, $user_sid, $user_type);

        $return_data = array();

        if (!empty($signature)) {
            $return_data['signature_bas64_image'] = $signature['signature_bas64_image'];
            echo json_encode($return_data);
        } else {
            echo false;
        }
    }
}

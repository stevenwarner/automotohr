<?php defined('BASEPATH') or exit('No direct script access allowed');

class Copy_employees extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->library('ion_auth');
        $this->load->model('manage_admin/copy_employees_model');
        $this->load->model('manage_admin/merge_employees_model');
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
    }


    /**
     * Handles copy applicants
     * Created on: 23-10-2019
     *
     * @uses db_get_admin_access_level_details
     * @uses check_access_permissions
     *
     * @return VOID
     */
    public function index_old()
    {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_employees');

        $page_title = 'Copy Employees To Another Company';
        $corporate_groups = $this->copy_employees_model->get_all_corporate_groups();
        $this->data['page_title'] = $page_title;
        $this->data['corporate_groups'] = $corporate_groups;

        $this->render('manage_admin/company/copy_employees', 'admin_master');
    }

    public function index()
    {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_employees');

        $page_title = 'Copy Employees To Another Company';
        $companies = $this->copy_employees_model->get_all_companies();
        $this->data['page_title'] = $page_title;
        $this->data['companies'] = $companies;

        $this->render('manage_admin/company/copy_employees', 'admin_master');
    }

    public function get_corporate_companies($corporate_sid)
    {
        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_employees');

        $corporate_companies = $this->copy_employees_model->get_corporate_companies_by_id($corporate_sid);

        if (!empty($corporate_companies)) {
            foreach ($corporate_companies as $key => $corporate_company) {
                $company_sid = $corporate_company['company_sid'];
                $company_name = $this->copy_employees_model->get_company_name_by_id($company_sid);
                if (!empty($company_name)) {
                    $corporate_companies[$key]['company_name'] = $company_name;
                }
            }
        }

        echo json_encode($corporate_companies);
    }

    public function get_companies_employees($company_sid, $employee_type, $page, $to_company_sid, $employee_sortby, $employee_sort_orderby, $employee_keyword = '')
    {

        $this->data['security_details'] = $security_details = db_get_admin_access_level_details($this->ion_auth->user()->row()->id);
        check_access_permissions($security_details, 'manage_admin', 'copy_employees');

        if (!$this->input->is_ajax_request() || $this->input->method(true) !== 'GET') {
            exit(0);
        }
        //
        $employee_keyword = str_replace('--', ' ', $employee_keyword);

        $resp = array();
        $resp['status'] = FALSE;
        $resp['response'] = 'Invalid request';

        if (!isset($company_sid) || !isset($employee_type) || !isset($page)) {
            $resp['response'] = 'Indexes are missing from request';
            echo json_encode($resp);
        }

        $company_employees = $this->copy_employees_model->get_company_employee($company_sid, $employee_type, $page, 50, $employee_sortby, $employee_sort_orderby, $employee_keyword);
        $employees_count = $this->copy_employees_model->get_employee_count($company_sid, $employee_type, $employee_keyword);

        if (empty($company_employees)) {
            $company_name = $this->copy_employees_model->get_company_name_by_id($company_sid);
            $resp['response'] = 'No desire employees found in this <b>' . $company_name . '</b>.';
            echo json_encode($resp);
        } else {
            $resp['status'] = TRUE;
            $resp['response'] = 'Proceed';
            if ($page == 1) {
                $resp['limit'] = 50;
                $resp['records'] = $company_employees;
                $resp['totalPages'] = ceil($employees_count / $resp['limit']);
                $resp['totalRecords'] = $employees_count;
            } else {
                $resp['records'] = $company_employees;
            }

            echo json_encode($resp);
        }
    }

    public function copy_companies_employees()
    {
        check_access_permissions(db_get_admin_access_level_details($this->ion_auth->user()->row()->id), 'manage_admin', 'copy_applicants');

        if (!$this->input->is_ajax_request() || $this->input->method(true) !== 'POST' || !sizeof($this->input->post())) {
            exit(0);
        }

        $resp = array();
        $resp['status'] = FALSE;
        $resp['response'] = 'Invalid request';
        //
        $formpost = $this->input->post(NULL, TRUE);
        //
        if (!isset($formpost['employee_sid']) || !isset($formpost['employee_name']) || !isset($formpost['from_company']) || !isset($formpost['to_company'])) {
            $resp['response'] = 'Indexes are missing from request';
            echo json_encode($resp);
        } else {
            $employee_sid = $formpost['employee_sid'];
            $to_company = $formpost['to_company'];
            $from_company = $formpost['from_company'];
            $user_type = 'employee';
            $transferredNote = $formpost['transferred_note'];
            //
            $employee = $this->copy_employees_model->fetch_employee_by_sid($employee_sid);
            $company_name = $this->copy_employees_model->get_company_name_by_id($to_company);

            $employee_name = $employee['first_name'] . ' ' . $employee['last_name'];

            if (empty($employee)) {
                $resp['response'] = 'No employee found.';
                echo json_encode($resp);
            }
            // set array
            $passArray = [
                'oldEmployeeId' => $employee_sid,
                'oldCompanyId' => $from_company,
                'newEmployeeId' => 0,
                'newCompanyId' => $to_company
            ];

            $date = date('Y-m-d H:i:s', strtotime('now'));

            if ($employee["email"] && $this->copy_employees_model->check_employee_exist($employee['email'], $to_company)) {
                $primary_employee_sid = $this->copy_employees_model->get_employee_sid($employee['email'], $to_company);
                $secondary_employee_sid = $this->copy_employees_model->get_employee_sid($employee['email'], $from_company);
                //
                $passArray['newEmployeeId'] = $primary_employee_sid;

                $secondary_employee_email    = $employee['email'];

                $adminId = getCompanyAdminSid($to_company);

                //
                //Update Primary Employee Profile
                $secondary_employee_data = $this->merge_employees_model->update_company_employee($primary_employee_sid, $secondary_employee_sid);

                // now move all other information

                // 1) Employee emergency contacts
                $emergency_contacts = $this->merge_employees_model->update_employee_emergency_contacts($primary_employee_sid, $secondary_employee_sid);

                // 2) Employee equipment information
                $equipment_information = $this->merge_employees_model->update_employee_equipment_information($primary_employee_sid, $secondary_employee_sid);

                // 3) Employee dependant information
                $dependant_information = $this->merge_employees_model->update_employee_dependant_information($primary_employee_sid, $secondary_employee_sid, $adminId);

                // 4) Employee license information
                $license_information = $this->merge_employees_model->update_employee_license_information($primary_employee_sid, $secondary_employee_sid);

                // 5) Employee background check
                $this->merge_employees_model->update_employee_background_check($primary_employee_sid, $secondary_employee_sid);

                // 6) Employee portal misc notes
                $this->merge_employees_model->update_employee_misc_notes($primary_employee_sid, $secondary_employee_sid);

                // 7) Employee private_message
                $this->merge_employees_model->update_employee_private_message($primary_employee_sid, $secondary_employee_email);

                // 8) Employee portal rating
                $this->merge_employees_model->update_employee_rating($primary_employee_sid, $secondary_employee_sid);

                // 9) Employee calendar events
                $this->merge_employees_model->update_employee_schedule_event($primary_employee_sid, $secondary_employee_sid);

                // 10) Employee portal attachments
                $this->merge_employees_model->update_employee_attachments($primary_employee_sid, $secondary_employee_sid);

                // 11) Employee reference checks
                $this->merge_employees_model->update_employee_reference_checks($primary_employee_sid, $secondary_employee_sid);

                // 12) Employee Onboarding Configuration
                $this->merge_employees_model->update_onboarding_configuration($primary_employee_sid, $secondary_employee_sid);

                // 13) Employee Documents
                $documents = $this->merge_employees_model->update_documents_new($primary_employee_sid, $secondary_employee_sid, $to_company);

                // 14) Employee Direct Deposit Information
                $bank_details = $this->merge_employees_model->update_employee_direct_deposit_information($primary_employee_sid, $secondary_employee_sid);

                // 15) Employee E-Signature Data
                $e_signature_data = $this->merge_employees_model->update_employee_e_signature($primary_employee_sid, $secondary_employee_sid);

                // 16) Employee EEOC Form
                $eeoc = $this->merge_employees_model->update_employee_eeoc_form($primary_employee_sid, $secondary_employee_sid, $adminId);
                //
                $merge_secondary_employee_data = [
                    'user_profile' => $secondary_employee_data,
                    'emergency_contacts' => $emergency_contacts,
                    'equipment_information' => $equipment_information,
                    'dependant_information' => $dependant_information,
                    'license_information' => $license_information,
                    'direct_deposit_information' => $bank_details,
                    'e_signature' => "",
                    'eeoc' => $eeoc,
                    'group' => "",
                    'documents' => count($documents)
                ];
                //
                $this->merge_employees_model->save_merge_secondary_employee_info($merge_secondary_employee_data, $primary_employee_sid, $secondary_employee_sid);
                //
                $logRecord =
                    $this->db
                    ->select('sid')
                    ->where([
                        'from_company_sid' => $from_company,
                        'to_company_sid' => $to_company,
                        'previous_employee_sid' => $secondary_employee_sid,
                        'new_employee_sid' => $primary_employee_sid
                    ])
                    ->get('employees_transfer_log')
                    ->row_array();
                //
                if ($logRecord) {
                    //
                    $this->db
                        ->where('sid', $logRecord['sid'])
                        ->update('employees_transfer_log', [
                            'employee_copy_date' => getSystemDate()
                        ]);
                } else {
                    //
                    $insert_employee_log = array();
                    $insert_employee_log['from_company_sid'] = $from_company;
                    $insert_employee_log['previous_employee_sid'] = $secondary_employee_sid;
                    $insert_employee_log['to_company_sid'] = $to_company;
                    $insert_employee_log['new_employee_sid'] = $primary_employee_sid;
                    $insert_employee_log['last_update'] = $insert_employee_log['employee_copy_date'] = getSystemDate();

                    $this->db->insert('employees_transfer_log', $insert_employee_log);
                }

                // Deactivate employee and add post fix username
                $this->db
                    ->where('sid', $secondary_employee_sid)
                    ->update('users', [
                        'active' => 0,
                        'username' => $secondary_employee_data['username'] . '_' . time()
                    ]);
                // Update the transfer date
                $transferDate = getSystemDate(DB_DATE);
                $this->db->where('sid', $primary_employee_sid)->update('users', ['transfer_date' => $transferDate]);
                $this->db->where('sid', $secondary_employee_sid)->update('users', ['transfer_date' => $transferDate]);

                // Add transferred status to moved employee
                $ins = [];
                $ins['status_change_date'] = date('Y-m-d', strtotime('now'));
                $ins['details'] = $transferredNote . '<br/>' . 'Employee is moved from "' . (getUserColumnById($from_company, 'CompanyName')) . '".';
                $ins['employee_status'] = 9;
                $ins['employee_sid'] = $primary_employee_sid;
                $ins['changed_by'] = 0;
                $ins['ip_address'] = getUserIP();
                $ins['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $ins['termination_reason'] = 0;
                $ins['termination_date'] = null;

                $this->copy_employees_model->add_terminate_user_table($ins);

                // Add transferred status to primary employee
                $ins = [];
                $ins['status_change_date'] = date('Y-m-d', strtotime('now'));
                $ins['details'] = 'Employee is moved to "' . (getUserColumnById($to_company, 'CompanyName')) . '".';
                $ins['employee_status'] = 9;
                $ins['employee_sid'] = $secondary_employee_sid;
                $ins['changed_by'] = 0;
                $ins['ip_address'] = getUserIP();
                $ins['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $ins['termination_reason'] = 0;
                $ins['termination_date'] = null;

                $this->copy_employees_model->add_terminate_user_table($ins);

                //
                $array['status'] = "success";
                $array['message'] = "Success! Employee is successfully merged!";
                $this->session->set_flashdata('message', '<b>Success:</b> Employee Merged Successfully!');
                //
                //  transfer employee timeoff request
                if ($formpost['timeoff'] == 1 && is_array($formpost['policyObj'])) {

                    $managePolicies = $formpost['policyObj'];

                    $this->transferEmployeeTimeOff($secondary_employee_sid, $primary_employee_sid, $from_company, $to_company, $managePolicies);
                }
                // load complynet model
                $this->load->model('2022/Complynet_model', 'complynet_model');

                // check if department / team exists
                // only then transfer the employee
                // as in case of merge the profile
                // could have the department/team
                // or job title
                $this
                    ->complynet_model
                    ->checkAndTransferEmployee(
                        $passArray
                    );

                //
                //   $this->complynet_model->manageEmployee($passArray);
                //
                $this->copy_employees_model->copyEmployeeLMSCourses($passArray);

                echo json_encode($resp);
            } else {

                $user_to_insert = array();


                $adminId = getCompanyAdminSid($to_company);



                foreach ($employee as $key => $value) {
                    if ($key == 'username') {
                        if ($this->copy_employees_model->check_employee_username_exist($employee['username'])) {

                            // update old username
                            $oldUserUpdateData['username'] = substr($employee['username'] . '_' . time(), 0, 254);
                            $oldUserUpdateData['active'] = 0;

                            if ($this->copy_employees_model->update_user_olddata($employee['sid'], $oldUserUpdateData) != $oldUserUpdateData['username']) {
                                $oldUserUpdateData['username'] = substr($employee['username'] . '_' . $employee['parent_sid'] . '_' . generateRandomString(5), 0, 254);
                                $oldUserUpdateData['active'] = 0;
                            }

                            $user_to_insert[$key] = $value;
                        }
                    } else {
                        $user_to_insert[$key] = $value;
                    }
                }

                $user_to_insert['parent_sid'] = $to_company;
                unset($user_to_insert['sid']);
                //
                $new_employee_sid = $this->copy_employees_model->copy_user_to_other_company($user_to_insert);
                $passArray['newEmployeeId'] = $new_employee_sid;
                $e_signature = $this->copy_employees_model->get_employee_e_signature($from_company, $employee_sid, $user_type);

                if (!empty($e_signature)) {
                    $insert_e_signature = array();

                    foreach ($e_signature as $key => $value) {
                        $insert_e_signature[$key] = $value;
                    }

                    $insert_e_signature['company_sid'] = $to_company;
                    $insert_e_signature['user_sid'] = $new_employee_sid;
                    unset($insert_e_signature['sid']);

                    $this->copy_employees_model->copy_new_employee_e_signature($insert_e_signature);
                }

                $specific_videos = $this->copy_employees_model->get_employee_specific_video($user_type, $employee_sid);

                if (!empty($specific_videos)) {
                    foreach ($specific_videos as $key => $specific_video) {
                        $insert_specific_video = array();

                        foreach ($specific_video as $key => $value) {
                            $insert_specific_video[$key] = $value;
                        }

                        $insert_specific_video['user_sid'] = $new_employee_sid;
                        unset($insert_specific_video['sid']);

                        $this->copy_employees_model->copy_new_employee_video($insert_specific_video);
                    }
                }

                $specific_training_sessions = $this->copy_employees_model->get_employee_specific_training_sessions($user_type, $employee_sid);

                if (!empty($specific_training_sessions)) {
                    foreach ($specific_training_sessions as $key => $training_session) {
                        $insert_training_session = array();

                        foreach ($training_session as $key => $value) {
                            $insert_training_session[$key] = $value;
                        }

                        $insert_training_session['user_sid'] = $new_employee_sid;
                        unset($insert_training_session['sid']);

                        $this->copy_employees_model->copy_new_employee_training_session($insert_training_session);
                    }
                }

                $occupational_license = $this->copy_employees_model->get_license_details($user_type, $employee_sid, 'occupational');

                if (!empty($occupational_license)) {
                    $insert_occupational_license = array();

                    foreach ($occupational_license as $key => $value) {
                        $insert_occupational_license[$key] = $value;
                    }

                    $insert_occupational_license['users_sid'] = $new_employee_sid;
                    unset($insert_occupational_license['sid']);

                    $this->copy_employees_model->copy_new_employee_license($insert_occupational_license);
                }

                $drivers_license = $this->copy_employees_model->get_license_details($user_type, $employee_sid, 'drivers');

                if (!empty($drivers_license)) {
                    $insert_drivers_license = array();

                    foreach ($drivers_license as $key => $value) {
                        $insert_drivers_license[$key] = $value;
                    }

                    $insert_drivers_license['users_sid'] = $new_employee_sid;
                    unset($insert_drivers_license['sid']);

                    $this->copy_employees_model->copy_new_employee_license($insert_drivers_license);
                }

                $dependents = $this->copy_employees_model->get_dependant_information($user_type, $employee_sid);

                if (!empty($dependents)) {
                    foreach ($dependents as $key => $dependent) {
                        $insert_dependent = array();

                        foreach ($dependent as $key => $value) {
                            $insert_dependent[$key] = $value;
                        }

                        $insert_dependent['users_sid'] = $new_employee_sid;
                        $insert_dependent['company_sid'] = $to_company;
                        unset($insert_dependent['sid']);

                        $this->copy_employees_model->copy_new_employee_dependent($insert_dependent);
                    }
                }

                $emergency_contacts = $this->copy_employees_model->get_employee_emergency_contacts($user_type, $employee_sid);

                if (!empty($emergency_contacts)) {
                    foreach ($emergency_contacts as $key => $emergency_contact) {
                        $insert_emergency_contact = array();

                        foreach ($emergency_contact as $key => $value) {
                            $insert_emergency_contact[$key] = $value;
                        }

                        $insert_emergency_contact['users_sid'] = $new_employee_sid;
                        unset($insert_emergency_contact['sid']);

                        $this->copy_employees_model->copy_new_employee_emergency_contacts($insert_emergency_contact);
                    }
                }

                $bank_details = $this->copy_employees_model->get_bank_detail($user_type, $employee_sid);

                if (!empty($bank_details)) {
                    $insert_bank_details = array();

                    foreach ($bank_details as $key => $value) {
                        $insert_bank_details[$key] = $value;
                    }

                    $insert_bank_details['users_sid'] = $new_employee_sid;
                    $insert_bank_details['company_sid'] = $to_company;
                    unset($insert_bank_details['sid']);

                    $this->copy_employees_model->copy_new_employee_bank_detail($insert_bank_details);
                }

                $equipments = $this->copy_employees_model->get_equipment_info($user_type, $employee_sid);

                if (!empty($equipments)) {
                    foreach ($equipments as $key => $equipment) {
                        $insert_equipment = array();

                        foreach ($equipment as $key => $value) {
                            $insert_equipment[$key] = $value;
                        }

                        $insert_equipment['users_sid'] = $new_employee_sid;
                        unset($insert_equipment['sid']);

                        $this->copy_employees_model->copy_new_employee_equipment($insert_equipment);
                    }
                }

                $assigned_documents = $this->copy_employees_model->get_assigned_documents($from_company, $user_type, $employee_sid, 0);

                if (!empty($assigned_documents)) {
                    foreach ($assigned_documents as $key => $assigned_document) {
                        //
                        $insert_assigned_document = array();
                        //
                        foreach ($assigned_document as $key => $value) {
                            $insert_assigned_document[$key] = $value;
                        }
                        //
                        $documentID = $this->copy_employees_model->getAssignedDocumentId($to_company, $assigned_document);
                        //
                        $insert_assigned_document['company_sid'] = $to_company;
                        $insert_assigned_document['user_sid'] = $new_employee_sid;
                        $insert_assigned_document['document_sid'] = $documentID;
                        //
                        unset($insert_assigned_document['sid']);
                        unset($insert_assigned_document['acknowledgment_required']);
                        unset($insert_assigned_document['download_required']);
                        unset($insert_assigned_document['signature_required']);

                        if (empty($insert_assigned_document['archive'])) {
                            $insert_assigned_document['archive'] = 0;
                        }


                        $insert_assigned_document['assigned_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_assign_document($insert_assigned_document);
                    }
                }

                $assigned_offer_letters = $this->copy_employees_model->get_assigned_offers($from_company, $user_type, $employee_sid, 0);

                if (!empty($assigned_offer_letters)) {
                    foreach ($assigned_offer_letters as $key => $offer_letter) {
                        $insert_offer_letter = array();
                        //
                        foreach ($offer_letter as $key => $value) {
                            $insert_offer_letter[$key] = $value;
                        }
                        //
                        $offerLetterID = $this->copy_employees_model->getAssignedOfferLetterId($to_company, $offer_letter);
                        //
                        $insert_offer_letter['company_sid'] = $to_company;
                        $insert_offer_letter['user_sid'] = $new_employee_sid;
                        $insert_offer_letter['document_sid'] = $offerLetterID;
                        //
                        unset($insert_offer_letter['sid']);
                        unset($insert_offer_letter['acknowledgment_required']);
                        unset($insert_offer_letter['download_required']);
                        unset($insert_offer_letter['signature_required']);

                        $insert_offer_letter['assigned_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_offer_letter($insert_offer_letter);
                    }
                }

                $eev_w4 = $this->copy_employees_model->is_exist_in_eev_document('w4', $from_company, $employee_sid);

                if (!empty($eev_w4)) {

                    $insert_eev_w4 = array();

                    foreach ($eev_w4 as $key => $value) {
                        $insert_eev_w4[$key] = $value;
                    }

                    $insert_eev_w4['company_sid'] = $to_company;
                    $insert_eev_w4['employee_sid'] = $new_employee_sid;
                    unset($insert_eev_w4['sid']);
                    $insert_eev_w4['uploaded_by_sid'] = $adminId;

                    $this->copy_employees_model->copy_new_employee_eev_form($insert_eev_w4);
                } else {
                    $w4_form = $this->copy_employees_model->fetch_form_for_front_end('w4', 'employee', $employee_sid);

                    if (!empty($w4_form)) {

                        $insert_w4_form = array();

                        foreach ($w4_form as $key => $value) {
                            $insert_w4_form[$key] = $value;
                        }

                        $insert_w4_form['company_sid'] = $to_company;
                        $insert_w4_form['employer_sid'] = $new_employee_sid;
                        unset($insert_w4_form['sid']);

                        $insert_w4_form['last_assign_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_w4_form($insert_w4_form);
                    }
                }

                $eev_w9 = $this->copy_employees_model->is_exist_in_eev_document('w9', $from_company, $employee_sid);

                if (!empty($eev_w9)) {
                    $insert_eev_w9 = array();

                    foreach ($eev_w9 as $key => $value) {
                        $insert_eev_w9[$key] = $value;
                    }

                    $insert_eev_w9['company_sid'] = $to_company;
                    $insert_eev_w9['employee_sid'] = $new_employee_sid;
                    unset($insert_eev_w9['sid']);

                    $insert_eev_w9['uploaded_by_sid'] = $adminId;

                    $this->copy_employees_model->copy_new_employee_eev_form($insert_eev_w9);
                } else {
                    $w9_form = $this->copy_employees_model->fetch_form_for_front_end('w9', 'employee', $employee_sid);

                    if (!empty($w9_form)) {

                        $insert_w9_form = array();

                        foreach ($w9_form as $key => $value) {
                            $insert_w9_form[$key] = $value;
                        }

                        $insert_w9_form['company_sid'] = $to_company;
                        $insert_w9_form['user_sid'] = $new_employee_sid;
                        unset($insert_w9_form['sid']);

                        $insert_w9_form['last_assign_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_w9_form($insert_w9_form);
                    }
                }

                $eev_i9 = $this->copy_employees_model->is_exist_in_eev_document('i9', $from_company, $employee_sid);

                if (!empty($eev_i9)) {
                    $insert_eev_i9 = array();

                    foreach ($eev_i9 as $key => $value) {
                        $insert_eev_i9[$key] = $value;
                    }

                    $insert_eev_i9['company_sid'] = $to_company;
                    $insert_eev_i9['employee_sid'] = $new_employee_sid;
                    unset($insert_eev_i9['sid']);
                    $insert_eev_w9['uploaded_by_sid'] = $adminId;
                    $this->copy_employees_model->copy_new_employee_eev_form($insert_eev_i9);
                } else {
                    $i9_form = $this->copy_employees_model->fetch_form_for_front_end('i9', 'employee', $employee_sid);
                    if (!empty($i9_form)) {

                        $insert_i9_form = array();

                        foreach ($i9_form as $key => $value) {
                            $insert_i9_form[$key] = $value;
                        }

                        $insert_i9_form['company_sid'] = $to_company;
                        $insert_i9_form['user_sid'] = $new_employee_sid;
                        unset($insert_i9_form['sid']);

                        $insert_i9_form['last_assign_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_i9_form($insert_i9_form);
                    }
                }

                $extra_attached_documents = $this->copy_employees_model->get_all_extra_attached_document($employee_sid, $user_type);

                if (!empty($extra_attached_documents)) {
                    foreach ($extra_attached_documents as $key => $extra_attached_document) {
                        $insert_extra_document = array();

                        foreach ($extra_attached_document as $key => $value) {
                            $insert_extra_document[$key] = $value;
                        }

                        $insert_extra_document['employer_sid'] = $to_company;
                        $insert_extra_document['applicant_job_sid'] = $new_employee_sid;
                        unset($insert_extra_document['sid']);

                        $this->copy_employees_model->copy_new_employee_extra_attachment($insert_extra_document);
                    }
                }

                $documents_history = $this->copy_employees_model->get_all_documents_history($employee_sid, $user_type);

                if (!empty($documents_history)) {
                    foreach ($documents_history as $key => $doc_history) {

                        $insert_doc_history = array();

                        foreach ($doc_history as $key => $value) {
                            $insert_doc_history[$key] = $value;
                        }

                        $insert_doc_history['company_sid'] = $to_company;
                        $insert_doc_history['user_sid'] = $new_employee_sid;
                        unset($insert_doc_history['sid']);

                        $insert_doc_history['assigned_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_documents_history($insert_doc_history);
                    }
                }

                $w4_history = $this->copy_employees_model->get_w4_history($employee_sid, $user_type);

                if (!empty($w4_history)) {
                    foreach ($w4_history as $key => $history) {
                        $insert_w4_history = array();

                        foreach ($history as $key => $value) {
                            $insert_w4_history[$key] = $value;
                        }

                        $insert_w4_history['company_sid'] = $to_company;
                        $insert_w4_history['employer_sid'] = $new_employee_sid;
                        unset($insert_w4_history['sid']);

                        $insert_w4_history['last_assign_by'] = $adminId;
                        $this->copy_employees_model->copy_new_employee_w4_history($insert_w4_history);
                    }
                }

                $w9_history = $this->copy_employees_model->get_w9_history($employee_sid, $user_type);

                if (!empty($w9_history)) {
                    foreach ($w9_history as $key => $history) {
                        $insert_w9_history = array();

                        foreach ($history as $key => $value) {
                            $insert_w9_history[$key] = $value;
                        }

                        $insert_w9_history['company_sid'] = $to_company;
                        $insert_w9_history['user_sid'] = $new_employee_sid;
                        unset($insert_w9_history['sid']);
                        $insert_w9_history['last_assign_by'] = $adminId;
                        $this->copy_employees_model->copy_new_employee_w9_history($insert_w9_history);
                    }
                }

                $i9_history = $this->copy_employees_model->get_i9_history($employee_sid, $user_type);

                if (!empty($i9_history)) {
                    foreach ($i9_history as $key => $history) {
                        $insert_i9_history = array();

                        foreach ($history as $key => $value) {
                            $insert_i9_history[$key] = $value;
                        }

                        $insert_i9_history['company_sid'] = $to_company;
                        $insert_i9_history['user_sid'] = $new_employee_sid;
                        unset($insert_i9_history['sid']);
                        $insert_i9_history['last_assign_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_i9_history($insert_i9_history);
                    }
                }

                $resume_history = $this->copy_employees_model->get_resume_history($employee_sid, $user_type);

                if (!empty($resume_history)) {
                    foreach ($resume_history as $key => $history) {
                        $insert_resume_history = array();

                        foreach ($history as $key => $value) {
                            $insert_resume_history[$key] = $value;
                        }

                        $insert_resume_history['company_sid'] = $to_company;
                        $insert_resume_history['user_sid'] = $new_employee_sid;
                        unset($insert_resume_history['sid']);

                        $insert_resume_history['requested_by'] = $adminId;

                        $this->copy_employees_model->copy_new_employee_request_log($insert_resume_history);
                    }
                }

                $insert_employee_log = array();
                $insert_employee_log['from_company_sid'] = $from_company;
                $insert_employee_log['previous_employee_sid'] = $employee_sid;
                $insert_employee_log['to_company_sid'] = $to_company;
                $insert_employee_log['new_employee_sid'] = $new_employee_sid;
                $insert_employee_log['employee_copy_date'] = $date;

                $this->copy_employees_model->maintain_employee_log_data($insert_employee_log);

                //
                $insert_employee_change_status = array();
                $insert_employee_change_status['status_change_date'] = date('Y-m-d', strtotime('now'));
                $insert_employee_change_status['details'] = $transferredNote . '<br/>' . 'Employee is moved from "' . (getUserColumnById($from_company, 'CompanyName')) . '".';;
                $insert_employee_change_status['employee_status'] = 9;
                $insert_employee_change_status['employee_sid'] = $new_employee_sid;
                $insert_employee_change_status['changed_by'] = 0;
                $insert_employee_change_status['ip_address'] = getUserIP();
                $insert_employee_change_status['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $insert_employee_change_status['termination_reason'] = 0;
                $insert_employee_change_status['termination_date'] = null;

                $this->copy_employees_model->add_terminate_user_table($insert_employee_change_status);

                // Add transferred status to primary employee
                $ins = [];
                $ins['status_change_date'] = date('Y-m-d', strtotime('now'));
                $ins['details'] = 'Employee is moved to "' . (getUserColumnById($to_company, 'CompanyName')) . '".';
                $ins['employee_status'] = 9;
                $ins['employee_sid'] = $employee_sid;
                $ins['changed_by'] = 0;
                $ins['ip_address'] = getUserIP();
                $ins['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                $ins['termination_reason'] = 0;
                $ins['termination_date'] = null;

                $this->copy_employees_model->add_terminate_user_table($ins);

                // here its come...

                // Update the transfer date
                $transferDate = getSystemDate(DB_DATE);
                $this->db->where('sid', $employee_sid)->update('users', ['transfer_date' => $transferDate]);
                $this->db->where('sid', $new_employee_sid)->update('users', ['transfer_date' => $transferDate]);

                //  transfer employee timeoff request
                if ($formpost['timeoff'] == 1 && is_array($formpost['policyObj'])) {
                    $this->transferEmployeeTimeOff($employee_sid, $new_employee_sid, $from_company, $to_company, $formpost['policyObj']);
                }

                $resp['status'] = TRUE;
                $resp['response'] = 'Employee <b>' . $employee_name . '</b> successfully copied in company <b>' . $company_name . '</b>';
                // load complynet model
                $this->load->model('2022/Complynet_model', 'complynet_model');

                // mark the employee as pending transfer
                // only if the employee is on ComplyNet
                $this
                    ->complynet_model
                    ->checkAndMarkEmployeeAsTransferLater(
                        $passArray
                    );

                //
                //    $this->complynet_model->manageEmployee($passArray);
                //
                $this->copy_employees_model->copyEmployeeLMSCourses($passArray);
                //
                echo json_encode($resp);
            }
        }
    }

    private function transferEmployeeTimeOff(
        int $sourceEmployeeId,
        int $destinationEmployeeId,
        int $sourceCompanyId,
        int $destinationCompanyId,
        array $managePolicies = []
    ): bool {
        // load time off model
        $this->load->model('timeoff_model');
        // get employee policies
        $employeePolicies = $this->timeoff_model
            ->getEmployeePoliciesById(
                $sourceCompanyId,
                $sourceEmployeeId,
                true
            );
        //
        if (!$employeePolicies) {
            return false;
        }
        //
        $adminId = getCompanyAdminSid($destinationCompanyId);
        //
        foreach ($employeePolicies as $policy) {
            // new policy id
            $newPolicyId = $managePolicies[$policy['PolicyId']] ?? 0;
            //
            if ($newPolicyId === 0) {
                // todo: needs to figure out
                continue;
            } elseif ($newPolicyId == -1) {
                // needs to create a new policy on
                // destination company
                // get the policy
                $policyDetails =
                    $this->timeoff_model->getPolicyDetailsById($policy['PolicyId']);
                //
                unset($policyDetails['sid']);
                //
                $policyDetails['company_sid'] = $destinationCompanyId;
                $policyDetails['creator_sid'] = $adminId;
                $policyDetails['type_sid'] = $this->timeoff_model
                    ->checkAndAddType(
                        $policyDetails['type_sid'],
                        $destinationCompanyId
                    );
                $policyDetails['is_entitled_employee'] = 1;
                $policyDetails['assigned_employees'] = $destinationEmployeeId;

                // insert the policy
                $this->db->insert('timeoff_policies', $policyDetails);
                $newPolicyId = $this->db->insert_id();
            }
            // flow will start here
            // get the selected policy
            $policyDetails = $this->timeoff_model->getPolicyDetailsById($newPolicyId, ['is_entitled_employee', 'assigned_employees']);
            $oldPolicy = $this->timeoff_model->getSinglePolicyById($newPolicyId);
            // is added history flag
            $isAddedHistory = "no";
            // add this person to existing persons list
            if ($policyDetails['is_entitled_employee'] == 1) {
                //
                if ($policyDetails['assigned_employees'] != 'all') {
                    $list = [];
                    //
                    if ($policyDetails['assigned_employees'] != 0 && $policyDetails['assigned_employees']) {
                        $list = explode(',', $policyDetails['assigned_employees']);
                    }
                    //
                    $list[] = $destinationEmployeeId;
                    $list = array_unique($list);
                    $list = implode(',', $list);

                    // update the list
                    $this->db
                        ->where('sid', $newPolicyId)
                        ->update('timeoff_policies', [
                            'assigned_employees' => $list
                        ]);
                    //
                    $isAddedHistory = "yes";
                }
            } else if ($policyDetails['is_entitled_employee'] == 0) {
                if ($policyDetails['assigned_employees'] == 0 || $policyDetails['assigned_employees'] == 'all') {
                    $this->db
                        ->where('sid', $newPolicyId)
                        ->update('timeoff_policies', [
                            'is_entitled_employee' => 1,
                            'assigned_employees' => $destinationEmployeeId,
                        ]);
                    //
                    $isAddedHistory = "yes";
                }
            }
            // 
            if ($isAddedHistory == "yes") {
                // Lets save who created the policy
                $in = [];
                $in['policy_sid'] = $newPolicyId;
                $in['employee_sid'] = $adminId;
                $in['action'] = 'update';
                $in['is_transfer'] = 1;
                $in['note'] = json_encode($oldPolicy);
                //
                $this->timeoff_model->insertPolicyHistory($in);
            }

            // get employee requests against specific policy
            $employeeRequests = $this->timeoff_model
                ->getEmployeeRequests(
                    $sourceEmployeeId,
                    $policy['PolicyId']
                );
            //
            if ($employeeRequests) {
                foreach ($employeeRequests as $request) {
                    //
                    $requestId = $request['sid'];
                    $approvedBy = $request['approved_by'];
                    //
                    unset($request['sid']);
                    //
                    $request['company_sid'] = $destinationCompanyId;
                    $request['employee_sid'] = $destinationEmployeeId;
                    $request['timeoff_policy_sid'] = $newPolicyId;
                    $request['creator_sid'] = $request['creator_sid'] == $sourceEmployeeId ? $destinationEmployeeId : $adminId;
                    $request['approved_by'] = $request['approved_by'] ? $adminId : $request['approved_by'];
                    //
                    $whereArray = [
                        'employee_sid' => $destinationEmployeeId,
                        'timeoff_policy_sid' => $newPolicyId,
                        'request_from_date' => $request['request_from_date'],
                        'request_to_date' => $request['request_to_date'],
                        'status' => $request['status'],
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
                                    'policyId' => $newPolicyId,
                                    'policyTitle' => $this->db
                                        ->select('title')
                                        ->where('sid', $newPolicyId)
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


            // get employee balances against specific policy
            $employeeBalances = $this->timeoff_model
                ->getEmployeeBalances(
                    $sourceEmployeeId,
                    $policy['PolicyId']
                );
            //
            if ($employeeBalances) {
                foreach ($employeeBalances as $balance) {
                    //
                    unset($balance['sid']);
                    //
                    $whereArray = [
                        'user_sid' => $destinationEmployeeId,
                        'policy_sid' => $newPolicyId,
                        'effective_at' => $balance['effective_at'],
                        'is_added' => $balance['is_added'],
                        'added_time' => $balance['added_time']
                    ];
                    //
                    if (!$this->db->where($whereArray)->count_all_results('timeoff_balances')) {
                        //
                        $balance['user_sid'] = $destinationEmployeeId;
                        $balance['policy_sid'] = $newPolicyId;
                        //
                        $this->db
                            ->insert(
                                'timeoff_balances',
                                $balance
                            );
                    }
                }
            }
        }
        // get incoming employee
        return true;
    }

    public function example($employee_sid)
    {
        $to_company = 001;
        $from_company = 704;
        $user_type = 'employee';
        $new_employee_sid = '98989898';

        $employee = $this->copy_employees_model->fetch_employee_by_sid($employee_sid);

        if (empty($employee)) {
            $resp['response'] = 'No employee found.';
            echo json_encode($resp);
        }

        $date = date('Y-m-d H:i:s', strtotime('now'));

        if ($this->copy_employees_model->check_employee_exist($employee['email'], $to_company)) {
            $resp['status'] = FALSE;
            $resp['response'] = 'Employee <b>' . $employee_name . '</b> already exist in company <b>' . $company_name . '</b>';
            echo json_encode($resp);
        }

        $user_to_insert = array();

        foreach ($employee as $key => $value) {
            if ($key == 'username') {
                echo $key;
                if ($this->copy_employees_model->check_employee_username_exist($employee['username'])) {
                    $user_to_insert[$key] = $value . '_' . generateRandomString(5);
                }
            } else {
                $user_to_insert[$key] = $value;
            }
        }

        $user_to_insert['parent_sid'] = $to_company;
        unset($user_to_insert['sid']);

        $new_employee_sid = $this->copy_employees_model->copy_user_to_other_company($user_to_insert);

        $e_signature = $this->copy_employees_model->get_employee_e_signature($from_company, $employee_sid, $user_type);

        if (!empty($e_signature)) {
            $insert_e_signature = array();

            foreach ($e_signature as $key => $value) {
                $insert_e_signature[$key] = $value;
            }

            $insert_e_signature['company_sid'] = $to_company;
            $insert_e_signature['user_sid'] = $new_employee_sid;
            unset($insert_e_signature['sid']);

            $this->copy_employees_model->copy_new_employee_e_signature($insert_e_signature);
        }

        $specific_videos = $this->copy_employees_model->get_employee_specific_video($user_type, $employee_sid);

        if (!empty($specific_videos)) {
            foreach ($specific_videos as $key => $specific_video) {
                $insert_specific_video = array();

                foreach ($specific_video as $key => $value) {
                    $insert_specific_video[$key] = $value;
                }

                $insert_specific_video['user_sid'] = $new_employee_sid;
                unset($insert_specific_video['sid']);

                $this->copy_employees_model->copy_new_employee_video($insert_specific_video);
            }
        }

        $specific_training_sessions = $this->copy_employees_model->get_employee_specific_training_sessions($user_type, $employee_sid);

        if (!empty($specific_training_sessions)) {
            foreach ($specific_training_sessions as $key => $training_session) {
                $insert_training_session = array();

                foreach ($training_session as $key => $value) {
                    $insert_training_session[$key] = $value;
                }

                $insert_training_session['user_sid'] = $new_employee_sid;
                unset($insert_training_session['sid']);

                $this->copy_employees_model->copy_new_employee_training_session($insert_training_session);
            }
        }

        $occupational_license = $this->copy_employees_model->get_license_details($user_type, $employee_sid, 'occupational');

        if (!empty($occupational_license)) {
            $insert_occupational_license = array();

            foreach ($occupational_license as $key => $value) {
                $insert_occupational_license[$key] = $value;
            }

            $insert_occupational_license['users_sid'] = $new_employee_sid;
            unset($insert_occupational_license['sid']);

            $this->copy_employees_model->copy_new_employee_license($insert_occupational_license);
        }

        $drivers_license = $this->copy_employees_model->get_license_details($user_type, $employee_sid, 'drivers');

        if (!empty($drivers_license)) {
            $insert_drivers_license = array();

            foreach ($drivers_license as $key => $value) {
                $insert_drivers_license[$key] = $value;
            }

            $insert_drivers_license['users_sid'] = $new_employee_sid;
            unset($insert_drivers_license['sid']);

            $this->copy_employees_model->copy_new_employee_license($insert_drivers_license);
        }

        $dependents = $this->copy_employees_model->get_dependant_information($user_type, $employee_sid);

        if (!empty($dependents)) {
            foreach ($dependents as $key => $dependent) {
                $insert_dependent = array();

                foreach ($dependent as $key => $value) {
                    $insert_dependent[$key] = $value;
                }

                $insert_dependent['users_sid'] = $new_employee_sid;
                $insert_dependent['company_sid'] = $to_company;
                unset($insert_dependent['sid']);

                $this->copy_employees_model->copy_new_employee_dependent($insert_dependent);
            }
        }

        $emergency_contacts = $this->copy_employees_model->get_employee_emergency_contacts($user_type, $employee_sid);

        if (!empty($emergency_contacts)) {
            foreach ($emergency_contacts as $key => $emergency_contact) {
                $insert_emergency_contact = array();

                foreach ($emergency_contact as $key => $value) {
                    $insert_emergency_contact[$key] = $value;
                }

                $insert_emergency_contact['users_sid'] = $new_employee_sid;
                unset($insert_emergency_contact['sid']);

                $this->copy_employees_model->copy_new_employee_emergency_contacts($insert_emergency_contact);
            }
        }

        $bank_details = $this->copy_employees_model->get_bank_detail($user_type, $employee_sid);

        if (!empty($bank_details)) {
            $insert_bank_details = array();

            foreach ($bank_details as $key => $value) {
                $insert_bank_details[$key] = $value;
            }

            $insert_bank_details['users_sid'] = $new_employee_sid;
            $insert_bank_details['company_sid'] = $to_company;
            unset($insert_bank_details['sid']);

            $this->copy_employees_model->copy_new_employee_bank_detail($insert_bank_details);
        }

        $equipments = $this->copy_employees_model->get_equipment_info($user_type, $employee_sid);

        if (!empty($equipments)) {
            foreach ($equipments as $key => $equipment) {
                $insert_equipment = array();

                foreach ($equipment as $key => $value) {
                    $insert_equipment[$key] = $value;
                }

                $insert_equipment['users_sid'] = $new_employee_sid;
                unset($insert_equipment['sid']);

                $this->copy_employees_model->copy_new_employee_equipment($insert_equipment);
            }
        }

        $assigned_documents = $this->copy_employees_model->get_assigned_documents($from_company, $user_type, $employee_sid, 0);

        if (!empty($assigned_documents)) {
            foreach ($assigned_documents as $key => $assigned_document) {
                $insert_assigned_document = array();
                //
                foreach ($assigned_document as $key => $value) {
                    $insert_assigned_document[$key] = $value;
                }
                //
                $documentID = $this->copy_employees_model->getAssignedDocumentId($to_company, $assigned_document);
                //
                $insert_assigned_document['company_sid'] = $to_company;
                $insert_assigned_document['user_sid'] = $new_employee_sid;
                $insert_assigned_document['document_sid'] = $documentID;
                //
                unset($insert_assigned_document['sid']);
                unset($insert_assigned_document['acknowledgment_required']);
                unset($insert_assigned_document['download_required']);
                unset($insert_assigned_document['signature_required']);

                if (empty($insert_assigned_document['archive'])) {
                    $insert_assigned_document['archive'] = 0;
                }

                $this->copy_employees_model->copy_new_employee_assign_document($insert_assigned_document);
            }
        }

        $assigned_offer_letters = $this->copy_employees_model->get_assigned_offers($from_company, $user_type, $employee_sid, 0);

        if (!empty($assigned_offer_letters)) {
            foreach ($assigned_offer_letters as $key => $offer_letter) {
                $insert_offer_letter = array();
                //
                foreach ($offer_letter as $key => $value) {
                    $insert_offer_letter[$key] = $value;
                }
                //
                $offerLetterID = $this->copy_employees_model->getAssignedOfferLetterId($to_company, $offer_letter);
                //
                $insert_offer_letter['company_sid'] = $to_company;
                $insert_offer_letter['user_sid'] = $new_employee_sid;
                $insert_offer_letter['document_sid'] = $offerLetterID;
                //
                unset($insert_offer_letter['sid']);
                unset($insert_offer_letter['acknowledgment_required']);
                unset($insert_offer_letter['download_required']);
                unset($insert_offer_letter['signature_required']);

                $this->copy_employees_model->copy_new_employee_offer_letter($insert_offer_letter);
            }
        }

        $eev_w4 = $this->copy_employees_model->is_exist_in_eev_document('w4', $from_company, $employee_sid);

        if (!empty($eev_w4)) {

            $insert_eev_w4 = array();

            foreach ($eev_w4 as $key => $value) {
                $insert_eev_w4[$key] = $value;
            }

            $insert_eev_w4['company_sid'] = $to_company;
            $insert_eev_w4['employee_sid'] = $new_employee_sid;
            unset($insert_eev_w4['sid']);

            $this->copy_employees_model->copy_new_employee_eev_form($insert_eev_w4);
        } else {
            $w4_form = $this->copy_employees_model->fetch_form_for_front_end('w4', 'employee', $employee_sid);

            if (!empty($w4_form)) {

                $insert_w4_form = array();

                foreach ($w4_form as $key => $value) {
                    $insert_w4_form[$key] = $value;
                }

                $insert_w4_form['company_sid'] = $to_company;
                $insert_w4_form['employer_sid'] = $new_employee_sid;
                unset($insert_w4_form['sid']);

                $this->copy_employees_model->copy_new_employee_w4_form($insert_w4_form);
            }
        }

        $eev_w9 = $this->copy_employees_model->is_exist_in_eev_document('w9', $from_company, $employee_sid);

        if (!empty($eev_w9)) {
            $insert_eev_w9 = array();

            foreach ($eev_w9 as $key => $value) {
                $insert_eev_w9[$key] = $value;
            }

            $insert_eev_w9['company_sid'] = $to_company;
            $insert_eev_w9['employee_sid'] = $new_employee_sid;
            unset($insert_eev_w9['sid']);

            $this->copy_employees_model->copy_new_employee_eev_form($insert_eev_w9);
        } else {
            $w9_form = $this->copy_employees_model->fetch_form_for_front_end('w9', 'employee', $employee_sid);

            if (!empty($w9_form)) {

                $insert_w9_form = array();

                foreach ($w9_form as $key => $value) {
                    $insert_w9_form[$key] = $value;
                }

                $insert_w9_form['company_sid'] = $to_company;
                $insert_w9_form['user_sid'] = $new_employee_sid;
                unset($insert_w9_form['sid']);

                $this->copy_employees_model->copy_new_employee_w9_form($insert_w9_form);
            }
        }

        $eev_i9 = $this->copy_employees_model->is_exist_in_eev_document('i9', $from_company, $employee_sid);

        if (!empty($eev_i9)) {
            $insert_eev_i9 = array();

            foreach ($eev_i9 as $key => $value) {
                $insert_eev_i9[$key] = $value;
            }

            $insert_eev_i9['company_sid'] = $to_company;
            $insert_eev_i9['employee_sid'] = $new_employee_sid;
            unset($insert_eev_i9['sid']);

            $this->copy_employees_model->copy_new_employee_eev_form($insert_eev_i9);
        } else {
            $i9_form = $this->copy_employees_model->fetch_form_for_front_end('i9', 'employee', $employee_sid);
            if (!empty($i9_form)) {

                $insert_i9_form = array();

                foreach ($i9_form as $key => $value) {
                    $insert_i9_form[$key] = $value;
                }

                $insert_i9_form['company_sid'] = $to_company;
                $insert_i9_form['user_sid'] = $new_employee_sid;
                unset($insert_i9_form['sid']);

                $this->copy_employees_model->copy_new_employee_i9_form($insert_i9_form);
            }
        }

        $extra_attached_documents = $this->copy_employees_model->get_all_extra_attached_document($employee_sid, $user_type);

        if (!empty($extra_attached_documents)) {
            foreach ($extra_attached_documents as $key => $extra_attached_document) {
                $insert_extra_document = array();

                foreach ($extra_attached_document as $key => $value) {
                    $insert_extra_document[$key] = $value;
                }

                $insert_extra_document['employer_sid'] = $to_company;
                $insert_extra_document['applicant_job_sid'] = $new_employee_sid;
                unset($insert_extra_document['sid']);

                $this->copy_employees_model->copy_new_employee_extra_attachment($insert_extra_document);
            }
        }

        $documents_history = $this->copy_employees_model->get_all_documents_history($employee_sid, $user_type);

        if (!empty($documents_history)) {
            foreach ($documents_history as $key => $doc_history) {

                $insert_doc_history = array();

                foreach ($doc_history as $key => $value) {
                    $insert_doc_history[$key] = $value;
                }

                $insert_doc_history['company_sid'] = $to_company;
                $insert_doc_history['user_sid'] = $new_employee_sid;
                unset($insert_doc_history['sid']);

                $this->copy_employees_model->copy_new_employee_documents_history($insert_doc_history);
            }
        }

        $w4_history = $this->copy_employees_model->get_w4_history($employee_sid, $user_type);

        if (!empty($w4_history)) {
            foreach ($w4_history as $key => $history) {
                $insert_w4_history = array();

                foreach ($history as $key => $value) {
                    $insert_w4_history[$key] = $value;
                }

                $insert_w4_history['company_sid'] = $to_company;
                $insert_w4_history['employer_sid'] = $new_employee_sid;
                unset($insert_w4_history['sid']);

                $this->copy_employees_model->copy_new_employee_w4_history($insert_w4_history);
            }
        }

        $w9_history = $this->copy_employees_model->get_w9_history($employee_sid, $user_type);

        if (!empty($w9_history)) {
            foreach ($w9_history as $key => $history) {
                $insert_w9_history = array();

                foreach ($history as $key => $value) {
                    $insert_w9_history[$key] = $value;
                }

                $insert_w9_history['company_sid'] = $to_company;
                $insert_w9_history['user_sid'] = $new_employee_sid;
                unset($insert_w9_history['sid']);

                $this->copy_employees_model->copy_new_employee_w9_history($insert_w9_history);
            }
        }

        $i9_history = $this->copy_employees_model->get_i9_history($employee_sid, $user_type);

        if (!empty($i9_history)) {
            foreach ($i9_history as $key => $history) {
                $insert_i9_history = array();

                foreach ($history as $key => $value) {
                    $insert_i9_history[$key] = $value;
                }

                $insert_i9_history['company_sid'] = $to_company;
                $insert_i9_history['user_sid'] = $new_employee_sid;
                unset($insert_i9_history['sid']);

                $this->copy_employees_model->copy_new_employee_i9_history($insert_i9_history);
            }
        }

        $resume_history = $this->copy_employees_model->get_resume_history($employee_sid, $user_type);

        if (!empty($resume_history)) {
            foreach ($resume_history as $key => $history) {
                $insert_resume_history = array();

                foreach ($history as $key => $value) {
                    $insert_resume_history[$key] = $value;
                }

                $insert_resume_history['company_sid'] = $to_company;
                $insert_resume_history['user_sid'] = $new_employee_sid;
                unset($insert_resume_history['sid']);

                $this->copy_employees_model->copy_new_employee_request_log($insert_resume_history);
            }
        }

        $insert_employee_log = array();
        $insert_employee_log['from_company_sid'] = $from_company;
        $insert_employee_log['previous_employee_sid'] = $employee_sid;
        $insert_employee_log['to_company_sid'] = $to_company;
        $insert_employee_log['new_employee_sid'] = $new_employee_sid;

        $this->copy_employees_model->maintain_employee_log_data($insert_employee_log);


        echo 'employee copy successfully';
        die('stop');
    }

    /**
     *
     */
    public function getCompaniesPolicies(int $fromCompanyId, int $toCompanyId): array
    {
        // load timeoff model
        $this->load->model('timeoff_model');
        //
        $policies = [];
        //
        $employeeIds = $this->input->post('employeeIds', true);

        // get source company policies by employee ids
        foreach ($employeeIds as $employeeId) {

            $sourceCompanyPolicies = $this->timeoff_model
                ->getEmployeePoliciesById(
                    $fromCompanyId,
                    $employeeId,
                    true
                );
            //
            if ($sourceCompanyPolicies) {
                //
                foreach ($sourceCompanyPolicies as $policy) {
                    //
                    if ($sourceCompanyPolicies['Reason']) {
                        continue;
                    }
                    //
                    if (!$policies[$policy['PolicyId']]) {
                        //
                        $policies[$policy['PolicyId']] = [
                            'sid' => $policy['PolicyId'],
                            'title' => $policy['Title'],
                            'is_paid' => $policy['CategoryType'],
                            'is_archived' => $policy['IsArchived'],
                            'is_entitled_employee' => $policy['IsEntitledEmployee'],
                            'assigned_employees' => $policy['AssignedEmployees'],
                            'requests_count' => 0
                        ];
                    }
                    // get requests count
                    $requestsCount = $this->timeoff_model
                        ->getEmployeeRequestCountAgainstPolicy(
                            $policy['PolicyId'],
                            $employeeId
                        );
                    //
                    $policies[$policy['PolicyId']]['requests_count'] += $requestsCount;
                }
            }
        }
        //
        if ($policies) {
            foreach ($policies as $index => $value) {
                //
                if ($value['is_archived'] == 1 && $value['requests_count'] == 0) {
                    unset($policies[$index]);
                }
            }
        }
        //
        $toCompanyPolicies = [];
        //
        if ($policies) {
            //
            $toCompanyPolicies = $this->copy_employees_model->getAllCompanyPolicies($toCompanyId);
        }
        //
        return SendResponse(
            200,
            [
                'fromCompanyPolicies' => array_values($policies),
                'toCompanyPolicies' => $toCompanyPolicies,
            ]
        );
    }
}

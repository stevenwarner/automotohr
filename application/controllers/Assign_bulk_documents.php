<?php defined('BASEPATH') or exit('No direct script access allowed');
//die('s');
class Assign_bulk_documents extends Public_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('assign_bulk_documents_model');
        $this->load->model('hr_documents_management_model');
    }

    /**
     *
     *
     */
    function index()
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $data['security_details'] = $security_details = db_get_access_level_details($security_sid);
        check_access_permissions($security_details, 'my_settings', 'bulk_resume'); // Param2: Redirect URL, Param3: Function Name
        $data['company_sid']  = $data['session']['company_detail']['sid'];

        $data['active_categories'] = $this->assign_bulk_documents_model->get_all_documents_category($data['company_sid']);
        $data['company_name'] = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['title'] = 'Assign Bulk Documents to Applicant(s) / Employee(s)';

        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/assign_bulk_documents');
        $this->load->view('main/footer');
    }

    /**
     * Fetch employees
     * Created on: 09-08-2019
     *
     * @accepts GET
     *
     * @return JSON
     */
    function fetch_employees()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('assign_bulk_documents', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $companyId = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'assign_bulk_documents', 'index'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        // fetch company employers
        $employees = $this->assign_bulk_documents_model->fetchEmployeesByCompanyId($companyId);
        if (!$employees) {
            $return_array['Response'] = 'No employees found.';
            $this->response($return_array);
        }
        //
        $return_array['Data'] = $employees;
        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Proceed..';
        $this->response($return_array);
    }


    /**
     * Fetch applicants
     * Created on: 09-08-2019
     *
     * @accepts GET
     *
     * @return JSON
     */
    function fetch_applicants_all()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('assign_bulk_documents', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $companyId = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'assign_bulk_documents', 'index'); // Param2: Redirect URL, Param3: Function Name
        //
        $return_array['Redirect'] = FALSE;
        // fetch company employers
        $applicants = $this->assign_bulk_documents_model->fetcApplicantsByCompanyId($companyId);
        if (!$applicants) {
            $return_array['Response'] = 'No applicants found.';
            $this->response($return_array);
        }
        //
        $return_array['Data'] = $applicants;
        $return_array['Status'] = TRUE;
        $return_array['Response'] = 'Proceed..';
        $this->response($return_array);
    }

    /**
     * Get applicants
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function fetch_applicants($query)
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('assign_bulk_documents', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'GET' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //
        $query = urldecode($query);
        //
        $data['session'] = $this->session->userdata('logged_in');
        $companyId = $data['session']['company_detail']['sid'];
        check_access_permissions(db_get_access_level_details($data['session']['employer_detail']['sid']), 'assign_bulk_documents', 'index'); // Param2: Redirect URL, Param3: Function Name
        //
        $this->response($this->assign_bulk_documents_model->fetchApplicantByQuery($companyId, $query));
    }



    /**
     * Get applicants
     *
     * accepts GET
     *
     * @return JSON
     *
     */
    function upload_assign_document()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('assign_bulk_documents', 'referesh');
        // set return array
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        // check if request method is not GET
        // user is not signed in
        if ($this->input->server('REQUEST_METHOD') != 'POST' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //

        $data['session'] = $this->session->userdata('logged_in');
        $companyId = $data['session']['company_detail']['sid'];
        $employerId = $data['session']['employer_detail']['sid'];
        //
        $file = $_FILES['file'];
        $formpost = $this->input->post(NULL, TRUE);
        if (!sizeof($file)) $this->response($return_array);
        if ($file['error'] != 0) $this->response($return_array);
        //
        $userId = $formpost['id'];
        $userType = $formpost['type'];
        $document_title = $file['name'];
        $document_description = '';

        $gen_document_title = substr($document_title, 0, strrpos($document_title, '.'));
        $gen_document_title = ucwords((preg_replace('/[^A-Za-z0-9\-]/', ' ', $gen_document_title)));

        //
        if ($_SERVER['HTTP_HOST'] == 'localhost') $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
        else $uploaded_document_s3_name = upload_file_to_aws('file', $companyId, str_replace(' ', '_', $document_title), $employerId, AWS_S3_BUCKET_NAME);
        // $uploaded_document_s3_name = upload_file_to_aws('file', $companyId, str_replace(' ', '_', $document_title), $employerId, AWS_S3_BUCKET_NAME);
        //
        $uploaded_document_original_name = $document_title;
        //
        $file_info = pathinfo($uploaded_document_original_name);
        //
        $data_to_insert = array();
        $data_to_insert['status'] = 1;
        $data_to_insert['user_sid'] = $userId;
        $data_to_insert['user_type'] = $userType;
        $data_to_insert['company_sid'] = $companyId;
        $data_to_insert['assigned_by'] = $employerId;
        $data_to_insert['document_sid'] = 0;
        $data_to_insert['user_consent'] = 1;

        if (isset($_POST['signed_date']) && $_POST['signed_date'] != '') {
            $data_to_insert['signature_timestamp'] = DateTime::createFromFormat('m/d/Y', $_POST['signed_date'])->format('Y-m-d') . ' 00:00:00';
        }

        $data_to_insert['document_type'] = 'uploaded';
        $data_to_insert['assigned_date'] = date('Y-m-d H:i:s');
        $data_to_insert['document_title'] = $gen_document_title;
        $data_to_insert['document_description'] = $document_description;
        //
        if (isset($file_info['extension'])) {
            $data_to_insert['document_extension'] = $file_info['extension'];
        }
        //
        if ($uploaded_document_s3_name != 'error') {
            $data_to_insert['uploaded'] = 1;
            $data_to_insert['uploaded_file'] = $uploaded_document_s3_name;
            $data_to_insert['uploaded_date'] = date('Y-m-d H:i:s');
            $data_to_insert['document_s3_name'] = $uploaded_document_s3_name;
            $data_to_insert['document_original_name'] = $uploaded_document_original_name;
        } else {
            $return_array['Response'] = 'Error';
            $this->response($return_array);
        }

        if (isset($_POST['is_offer_letter'])) {
            $user_info = '';

            if ($userType == 'applicant') {
                $user_info = $this->assign_bulk_documents_model->get_applicant_information($companyId, $userId);
            } else if ($userType == 'employee') {
                $user_info = $this->assign_bulk_documents_model->get_employee_information($companyId, $userId);
            }

            $offer_letter_name = $gen_document_title;

            $data_to_insert['document_title']       = $offer_letter_name;
            $data_to_insert['document_type']        = 'offer_letter';
            $data_to_insert['offer_letter_type']    = 'uploaded';

            $already_assigned = $this->assign_bulk_documents_model->check_applicant_offer_letter_exist($companyId, $userType, $userId, 'offer_letter');

            if (!empty($already_assigned)) {
                foreach ($already_assigned as $key => $previous_offer_letter) {
                    $previous_assigned_sid = $previous_offer_letter['sid'];
                    $already_moved = $this->assign_bulk_documents_model->check_offer_letter_moved($previous_assigned_sid, 'offer_letter');

                    if ($already_moved == 'no') {
                        $previous_offer_letter['doc_sid'] = $previous_assigned_sid;
                        unset($previous_offer_letter['sid']);
                        $this->assign_bulk_documents_model->insert_documents_assignment_record_history($previous_offer_letter);
                    }
                }
            }

            $this->assign_bulk_documents_model->disable_all_previous_letter($companyId, $userType, $userId, 'offer_letter');
        } else {

            if (!isset($_POST['categories'])) {
                if (isset($_POST['visible_to_payroll'])) {
                    $data_to_insert['visible_to_payroll'] = 1;
                } else {
                    $data_to_insert['visible_to_payroll'] = 0;
                }
            } else if (!in_array(27, $_POST['categories'])) {
                if (isset($_POST['visible_to_payroll'])) {
                    $data_to_insert['visible_to_payroll'] = 1;
                } else {
                    $data_to_insert['visible_to_payroll'] = 0;
                }
            }
        }
        // Confidential document
        $data_to_insert['is_confidential'] = $this->input->post('settings_is_confidential', true) == 'on' ? 1 : 0;
        //
        $insert_id = $this->assign_bulk_documents_model->insertDocumentsAssignmentRecord($data_to_insert);
        $this->assign_bulk_documents_model->add_update_categories_2_documents($insert_id, $this->input->post('categories'), "documents_assigned");

        $return_array['Status'] = true;
        $return_array['Response'] = 'Proceed';
        $this->response($return_array);
    }


    //
    function send_notification_email()
    {
        $resp = array();
        $resp['Status'] = FALSE;
        $resp['Response'] = 'Invalid request.';
        //
        if (!$this->session->userdata('logged_in')) $this->response($resp);
        //
        if (!isset($_POST['employeeId']) || empty($_POST['employeeId'])) $this->response($resp);
        //
        $this->load->model('Hr_documents_management_model', 'HRDMM');
        if ($this->HRDMM->isActiveUser($_POST['employeeId'])) {
            // Fetch employee information
            $user_info = $this->assign_bulk_documents_model->get_employee_information(
                $this->session->userdata('logged_in')['company_detail']['sid'],
                $_POST['employeeId']
            );
            //
            $replacement_array = array();
            $replacement_array['contact-name'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
            $replacement_array['username'] = ucwords($user_info['first_name'] . ' ' . $user_info['last_name']);
            $replacement_array['company_name'] = ucwords($this->session->userdata('logged_in')['company_detail']['CompanyName']);
            $replacement_array['firstname'] = $user_info['first_name'];
            $replacement_array['lastname'] = $user_info['last_name'];
            $replacement_array['first_name'] = $user_info['first_name'];
            $replacement_array['last_name'] = $user_info['last_name'];
            $replacement_array['baseurl'] = base_url();
            $replacement_array['url'] = base_url('hr_documents_management/my_documents');
            if ($this->hr_documents_management_model->doSendEmail($_POST['employeeId'], "employee", "HREMS1")) {
                log_and_send_templated_email(HR_DOCUMENTS_NOTIFICATION_EMS, $user_info['email'], $replacement_array);
            }
        }
        //
        $resp['Status'] = TRUE;
        $resp['Response'] = 'HR document email has ben sent.';
        //
        $this->response($resp);
    }

    /**
     * Send back json
     *
     * @param $array Array
     */
    private function response($array)
    {
        header('Content-Type: application/json');
        echo json_encode($array);
        exit(0);
    }


    //
    function SecureDocumentsListing()
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');


        //document_title

        $data['session'] = $this->session->userdata('logged_in');
        $data['company_sid']  = $data['session']['company_detail']['sid'];

        $data['company_name'] = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['title'] = 'Upload Secure Documents';

        $documentTitle = '';
        if (isset($_POST['document_title']) && $_POST['document_title'] != '') {
            $documentTitle = $_POST['document_title'];
        }


        $data['secure_documents'] = $this->assign_bulk_documents_model->getSecureDocuments($data['company_sid'], $documentTitle);

        $data['documentTitle'] = $documentTitle;
        //_e($data['secure_documents'],true);

        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/company_secure_documents_listing');
        $this->load->view('main/footer');
    }


    //
    function AddSecureDocument()
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $data['company_sid']  = $data['session']['company_detail']['sid'];

        $data['company_name'] = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['title'] = 'Add Secure Document';
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/add_secure_document');
        $this->load->view('main/footer');
    }


    function UploadSecureDocument()
    {
        // check if ajax request is not set
        if (!$this->input->is_ajax_request()) redirect('assign_bulk_documents', 'referesh');
        $return_array = array('Status' => FALSE, 'Response' => 'Invalid request', 'Redirect' => TRUE);
        if ($this->input->server('REQUEST_METHOD') != 'POST' || !$this->session->userdata('logged_in')) $this->response($return_array);
        //

        $data['session'] = $this->session->userdata('logged_in');
        $companyId = $data['session']['company_detail']['sid'];
        $employerId = $data['session']['employer_detail']['sid'];
        //
        $file = $_FILES['file'];
        $formpost = $this->input->post(NULL, TRUE);
        if (!sizeof($file)) $this->response($return_array);
        if ($file['error'] != 0) $this->response($return_array);
        //
        $document_title = $file['name'];

        $gen_document_title = substr($document_title, 0, strrpos($document_title, '.'));
        $gen_document_title = ucwords((preg_replace('/[^A-Za-z0-9\-]/', ' ', $gen_document_title)));

        //
        if ($_SERVER['HTTP_HOST'] == 'localhost') $uploaded_document_s3_name = '0057-test_latest_uploaded_document-58-Yo2.pdf';
        else $uploaded_document_s3_name = upload_file_to_aws('file', $companyId, str_replace(' ', '_', $document_title), $employerId, AWS_S3_BUCKET_NAME);
        //
        $data_to_insert = array();
        $data_to_insert['document_title'] = $gen_document_title;
        //
        if ($uploaded_document_s3_name != 'error') {
            $data_to_insert['document_s3_name'] = $uploaded_document_s3_name;
        } else {
            $return_array['Response'] = 'Error';
            $this->response($return_array);
        }
        //
        if ($formpost['document_sid']!= '') {
            $data_to_insert['updated_at'] = date('Y-m-d H:i:s');
            $this->assign_bulk_documents_model->updateSecureDocument($formpost['document_sid'],$data_to_insert);
        } else {
            $data_to_insert['created_by'] = $employerId;
            $data_to_insert['company_sid'] = $companyId;
            $data_to_insert['created_at'] = date('Y-m-d H:i:s');
            $this->assign_bulk_documents_model->insertSecureDocument($data_to_insert);
        }

        $return_array['Status'] = true;
        $return_array['Response'] = 'Proceed';
        $this->response($return_array);
    }


    function EditSecureDocument($document_sid)
    {
        if (!$this->session->userdata('logged_in')) redirect('login', 'refresh');

        $data['session'] = $this->session->userdata('logged_in');
        $data['company_sid']  = $data['session']['company_detail']['sid'];

        $data['company_name'] = strtolower(clean($data['session']['company_detail']['CompanyName']));
        $data['employer_sid'] = $data['session']['employer_detail']['sid'];
        $data['title'] = 'Add Secure Document';
        $data['document_sid'] = $document_sid;
        $this->load->view('main/header', $data);
        $this->load->view('manage_employer/edit_secure_document');
        $this->load->view('main/footer');
    }
}

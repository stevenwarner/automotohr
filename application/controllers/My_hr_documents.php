<?php defined('BASEPATH') OR exit('No direct script access allowed');

class My_hr_documents extends Public_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('my_hr_document_model');
//        $this->load->model('application_tracking_model');
        $this->load->model('application_tracking_system_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($type = NULL, $sid = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data_function['security_details'] = $security_details;
            $data_function['session'] = $data['session'];
            $company_id = $data_function['session']['company_detail']['sid'];
            $employer_access_level = $data_function['session']['employer_detail']['access_level'];

            if ($type == 'employee') {
                check_access_permissions($security_details, 'employee_management', 'employee_hr_documents'); // First Param: security array, 2nd param: redirect url, 3rd param: function name           
                $data_function = employee_right_nav($sid);
                $employer_id = $sid;
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_employee_new';
                $data_function['title'] = 'Employee Received HR Documents';
                $reload_location = 'my_hr_documents/employee/' . $sid;
                $data_function["return_title_heading"] = "Employee Profile";
                $data_function["return_title_heading_link"] = base_url() . 'employee_profile/' . $sid;
            }

            if ($type == 'applicant') {
                check_access_permissions($security_details, 'employee_management', 'employee_hr_documents'); // First Param: security array, 2nd param: redirect url, 3rd param: function name
                $data_function = applicant_right_nav($sid);
                $employer_id = $sid;
                $left_navigation = 'manage_employer/profile_right_menu_applicant';
                $data_function['title'] = 'Applicant Received HR Documents';
                $reload_location = 'my_hr_documents/applicant/' . $sid;
                $data_function["return_title_heading"] = "Applicant Profile";
                $data_function["return_title_heading_link"] = base_url() . 'applicant_profile/' . $sid;
            }

            if ($sid == NULL && $type == NULL) {
                $employer_id = $data_function['session']['employer_detail']['sid'];
                $left_navigation = 'manage_employer/employee_management/profile_right_menu_personal';
                $data_function['title'] = 'Received HR Documents';
                $reload_location = 'my_hr_documents';
                $type = 'employee';
                $sid = $employer_id;
                $data_function['return_title_heading'] = 'My Profile';
                $data_function['return_title_heading_link'] = base_url() . 'my_profile/';
            }

            $this->load->model('dashboard_model');

            if ($type == 'employee') {
                $data_function["employer"] = $this->dashboard_model->get_company_detail($employer_id);
            }

            if ($type == 'applicant') {
                $applicant_info = $this->dashboard_model->get_applicants_details($sid);

                $data_employer = array('sid' => $applicant_info['sid'],
                    'first_name' => $applicant_info['first_name'],
                    'last_name' => $applicant_info['last_name'],
                    'email' => $applicant_info['email'],
                    'Location_Address' => $applicant_info['address'],
                    'Location_City' => $applicant_info['city'],
                    'Location_Country' => $applicant_info['country'],
                    'Location_State' => $applicant_info['state'],
                    'Location_ZipCode' => $applicant_info['zipcode'],
                    'PhoneNumber' => $applicant_info['phone_number']
                );

                $data_function['employer'] = $data_employer;
            }

            $data_function['employer_access_level'] = $employer_access_level;
            $full_access = false;

            if ($employer_access_level == 'Admin') {
                $full_access = true;
            }

            if ($type == NULL) {
                $this->session->set_flashdata('message', '<b>Error:</b> No Contact found!');
                redirect('dashboard', 'refresh');
            }

            $data_function['full_access'] = $full_access;
            $data_function['left_navigation'] = $left_navigation;
            $documents = $this->my_hr_document_model->getAllReceivedDocuments('document', $sid);

            foreach($documents as $key => $document) {
                $ack_dwn_upl =  $this->my_hr_document_model->check_document_ack_dwn_upl($sid, $document['document_sid']);
                $archive_status = $this->my_hr_document_model->check_document_archive_status($document['document_sid']);
                $document['ack_dwn_upl'] = $ack_dwn_upl;
                $document['archived'] = $archive_status;
                $documents[$key] = $document;
            }

            $data_function['documents'] = $documents;
            $data_function['offerLetters'] = $this->my_hr_document_model->getAllReceivedOfferLetters('offerletter', $sid);
            // getting applicant ratings - getting average rating of applicant
            $data_function['applicant_average_rating'] = $this->application_tracking_system_model->getApplicantAverageRating($sid, 'employee');
            $data_function['employee'] = $data['session']['employer_detail'];
            $this->load->view('main/header', $data_function);
            $this->load->view('manage_employer/my-hr-document-list');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function document_tasks() {
        $data['session'] = $this->session->userdata('logged_in');
        $security_sid = $data['session']['employer_detail']['sid'];
        $security_details = db_get_access_level_details($security_sid);
        $data['security_details'] = $security_details;

        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            //echo $formpost['type'];
            if ($formpost['type'] == 'acknowledge') {
                $updatedData['acknowledged'] = $formpost['value'];
                $updatedData['acknowledged_date'] = date('Y-m-d H:i:s');
            } else if ($formpost['type'] == 'download') {
                $updatedData['downloaded'] = $formpost['value'];
                $updatedData['downloaded_date'] = date('Y-m-d H:i:s');
            } else if ($formpost['type'] == 'mark as complete') {
                $updatedData['uploaded'] = $formpost['value'];
                $updatedData['uploaded_date'] = date('Y-m-d H:i:s');
            }

            $document_id = $formpost['sid'];
            $this->my_hr_document_model->updateUserDocument($document_id, $updatedData);
            $check = $this->my_hr_document_model->removeVerificationKey($document_id);
            
            if ($check == "true") {
                $data = array('verification_key' => NULL);
                $this->my_hr_document_model->updateUserDocument($document_id, $data);
            }
        }
    }

    public function upload_response_file() { //uploading file 3rd step ==>> starts
        $current_url = $this->input->post('url');
        
        if ($this->input->post()) {
            if (isset($_FILES['uploadedFile']) && $_FILES['uploadedFile']['name'] != '') {
                $file = explode(".", $_FILES['uploadedFile']['name']);
                $parts = preg_split('~.(?=[^.]*$)~', $_FILES['uploadedFile']['name']);
                $updatedData['uploaded_file'] = $document = $parts[0] . '(uploaded:' . date('m-d-Y H:i:s') . ').' . end($file);
                
                if($_FILES['uploadedFile']['size'] == 0) {
                    $this->session->set_flashdata('message', '<b>Warning:</b> File is empty! Please try again.');
                    redirect($current_url);
                }
                
                $aws = new AwsSdk();
                $aws->putToBucket($document, $_FILES['uploadedFile']['tmp_name'], AWS_S3_BUCKET_NAME);
                $document_id = $this->input->post('document_user_sid');
                $updatedData['uploaded'] = 1;
                $updatedData['uploaded_date'] = date('Y-m-d H:i:s');
                $this->my_hr_document_model->updateUserDocument($document_id, $updatedData);
                $check = $this->my_hr_document_model->removeVerificationKey($document_id);
                
                if ($check == "true") {
                    $data = array('verification_key' => NULL);
                    $this->my_hr_document_model->updateUserDocument($document_id, $data);
                }

                if ($this->input->post('verification_key') != null) {
                    $this->session->set_flashdata('message', '<b>Success:</b> File uploaded successfully!');
                    redirect(base_url('received_documents') . "/" . $this->input->post('verification_key'));
                } else {
                    $this->session->set_flashdata('message', '<b>Success:</b> File uploaded successfully!');
                    redirect($current_url);
                }
            } else {
                $this->session->set_flashdata('message', '<b>Error:</b> No file selected to upload, Please try again!');
                redirect($current_url);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error:</b> Cannot upload file, Please try again!');
            redirect($current_url);
        }
        //uploading file 3rd step ==>> ends
    }

    function textToWordFile() {
        $offerLetterId = $this->uri->segment(3);
        $offerLetterObj = $this->my_hr_document_model->get_user_document_detail($offerLetterId);
        
        if ($offerLetterObj->num_rows() > 0) {
            $offerLetterData = $offerLetterObj->result_array();
            $offerLetterData = $offerLetterData[0];
            $fileName = str_replace(' ', '-', $offerLetterData['offer_letter_name'] . '.doc');
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment;Filename=$fileName");

            echo "<html>";
            echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
            echo "<body>";
            echo $offerLetterData['offer_letter_body'];
            echo "</body>";
            echo "</html>";
        }
    }

    function textToWordUploadedFile() {
        $offerLetterId = $this->uri->segment(3);
        $offerLetterObj = $this->my_hr_document_model->get_user_document_detail($offerLetterId);
        
        if ($offerLetterObj->num_rows() > 0) {
            $offerLetterData = $offerLetterObj->result_array();
            $offerLetterData = $offerLetterData[0];
            $fileName = $offerLetterData['uploaded_file'];
            $path = AWS_S3_BUCKET_URL . $fileName;
            header('Content-Type: application/octet-stream');
            header("Content-Disposition: attachment;Filename=$fileName");
            readfile($path);
        }
    }
}
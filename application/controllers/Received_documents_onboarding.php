<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Received_documents_onboarding extends CI_Controller
{

    private $security_details;

    public function __construct()
    {
        parent::__construct();
        // Your own constructor code
        $this->load->model('received_documents_model');
        $this->load->model('my_hr_document_model');
        $this->load->model('dashboard_model');
    }

    public function index($verificationKey = NULL)
    {

        $data['title'] = "Register";


        if ($verificationKey != NULL) {
            if (strpos($verificationKey, '_csvImport') !== false) {
                $userDataObject = $this->received_documents_model->get_user_detail_from_users($verificationKey);
            } else {
                $userDataObject = $this->received_documents_model->get_user_detail_from_hr_document($verificationKey);
            }
            if ($userDataObject->num_rows() > 0) {
                $res = $userDataObject->result_array();
                if (strpos($verificationKey, '_csvImport') !== false) {
                    $employer_sid = $res[0]['sid'];
                } else {
                    $employer_sid = $res[0]['receiver_sid'];
                }
                $data['employerDetail'] = $this->dashboard_model->getEmployerDetail($employer_sid);
                $data['companyDetail'] = $this->dashboard_model->getCompanyDetail($data['employerDetail']['parent_sid']);

                if ($data['employerDetail']['username'] == "" && $data['employerDetail']['password'] == "") {
                    $this->form_validation->set_rules('username', 'Username', 'trim|xss_clean|required|is_unique[users.username]');
                    $this->form_validation->set_rules('password', 'Password', 'required|xss_clean|min_length[6]');
                    $this->form_validation->set_rules('c_password', 'Confirm password', 'required|matches[password]|xss_clean|min_length[6]');

                    if ($this->form_validation->run() === FALSE) {
                        $this->load->view('employee_onboarding/employee_signup', $data);
                    } else {
                        $formpost = $this->input->post(NULL, TRUE);
                        $updateData['password'] = do_hash($formpost['password'], 'md5');
                        $updateData['username'] = $formpost['username'];
                        $updateData['active'] = 1;
                        $this->dashboard_model->update_user($employer_sid, $updateData);
                        if (strpos($verificationKey, '_csvImport') !== false) {
                            //Removing user verification_key
                            $updatedData = array('verification_key' => NULL);
                            $this->dashboard_model->update_user($employer_sid, $updatedData);
                            clear_loggin_session();
                            redirect(base_url('login'));
                        } else {
                            redirect(base_url('received_offer_letter') . '/' . $verificationKey);
                        }
                    }
                } else {
                    redirect(base_url('received_offer_letter') . '/' . $verificationKey);
                }
            } else {
                $this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired!');
                $this->load->view('employee_onboarding/error_file', $data);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>Verification key missing, Please use a Verification key to access this link!');
            $this->load->view('employee_onboarding/error_file', $data);
        }
    }

    public function received_offer_letter($verificationKey = NULL)
    {

        $data['title'] = "Offer Letter Panel";
        if ($verificationKey != NULL) {
            $userDataObject = $this->received_documents_model->get_user_detail($verificationKey);
            if ($userDataObject->num_rows() > 0) {
                $data['verificationKey'] = $verificationKey;
                $res = $userDataObject->result_array();
                $employer_sid = $res[0]['receiver_sid'];
                $data['employerDetail'] = $this->dashboard_model->getEmployerDetail($employer_sid);
                $data['companyDetail'] = $this->dashboard_model->getCompanyDetail($data['employerDetail']['parent_sid']);
                $data['offerLetters'] = $this->my_hr_document_model->getAllReceivedOfferLetters('offerletter', $employer_sid);
                $this->load->view('employee_onboarding/my-hr-offer-letter', $data);
            } else {
                $this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired!');
                $this->load->view('employee_onboarding/error_file', $data);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>Verification key missing, Please use a Verification key to access this link!');
            $this->load->view('employee_onboarding/error_file', $data);
        }
    }

    public function onboard_eligibility_verification($verificationKey = NULL)
    {

        $data['title'] = "Eligibility Verification Panel";
        if ($verificationKey != NULL) {
            $userDataObject = $this->received_documents_model->get_user_detail($verificationKey);
            if ($userDataObject->num_rows() > 0) {
                $data['verificationKey'] = $verificationKey;
                $res = $userDataObject->result_array();
                $employer_sid = $res[0]['receiver_sid'];
                $data['employerDetail'] = $this->dashboard_model->getEmployerDetail($employer_sid);
                $data['companyDetail'] = $this->dashboard_model->getCompanyDetail($data['employerDetail']['parent_sid']);
                $data["employer"] = $this->dashboard_model->get_company_detail($employer_sid);
                $full_employment_application = $data["employer"]['full_employment_application'];

                if (isset($_POST['action']) && $_POST['action'] == 'true') {
                    $data["formpost"] = $_POST;
                } else {
                    $data["formpost"] = unserialize($full_employment_application);
                }

                $this->form_validation->set_rules('first_name', 'First Name', 'trim|xss_clean|required');
                $this->form_validation->set_rules('last_name', 'Last Name', 'trim|xss_clean|required');

                if ($this->form_validation->run() === FALSE) {
                    $this->load->view('employee_onboarding/eligibility_form', $data);
                } else {
                    $formpost = $this->input->post(NULL, TRUE);
                    $full_employment_application = array();

                    foreach ($formpost as $key => $value) {
                        if ($key != 'action' && $key != 'first_name' && $key != 'last_name' && $key != 'sid') { // exclude these values from array
                            $full_employment_application[$key] = $value;
                        }
                    }

                    $id = $formpost['sid'];
                    $data = array('first_name' => $formpost['first_name'],
                        'last_name' => $formpost['last_name'],
                        'full_employment_application' => serialize($full_employment_application)
                    );
                    $this->dashboard_model->update_user($id, $data);
                    $this->session->set_flashdata('message', '<b>Success:</b> Full employment form updated successfully');
                    redirect(base_url('onboard_received_document') . '/' . $verificationKey);
                }
            } else {
                $this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired!');
                $this->load->view('employee_onboarding/error_file', $data);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>Verification key missing, Please use a Verification key to access this link!');
            $this->load->view('employee_onboarding/error_file', $data);
        }
    }

    public function onboard_received_document($verificationKey = NULL)
    {

        $data['title'] = "Offer Letter and HR Documents Panel";
        if ($verificationKey != NULL) {
            $userDataObject = $this->received_documents_model->get_user_detail($verificationKey);
            if ($userDataObject->num_rows() > 0) {
                $data['verificationKey'] = $verificationKey;
                $res = $userDataObject->result_array();
                $employer_sid = $res[0]['receiver_sid'];
                $data['employerDetail'] = $this->dashboard_model->getEmployerDetail($employer_sid);
                $data['companyDetail'] = $this->dashboard_model->getCompanyDetail($data['employerDetail']['parent_sid']);
                $documents = $this->my_hr_document_model->getAllReceivedDocuments('document', $employer_sid);

                foreach ($documents as $key => $document) {
                    $ack_dwn_upl = $this->my_hr_document_model->check_document_ack_dwn_upl($employer_sid, $document['document_sid']);

                    $archive_status = $this->my_hr_document_model->check_document_archive_status($document['document_sid']);

                    $document['actionCheck'] = $this->my_hr_document_model->removeVerificationKey($document['user_document_sid']);

                    $document['ack_dwn_upl'] = $ack_dwn_upl;
                    $document['archived'] = $archive_status;

                    $documents[$key] = $document;
                }

                $data['documents'] = $documents;
                $this->load->view('employee_onboarding/my-hr-document', $data);
            } else {
                $this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired!');
                $this->load->view('employee_onboarding/error_file', $data);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>Verification key missing, Please use a Verification key to access this link!');
            $this->load->view('employee_onboarding/error_file', $data);
        }
    }

    public function document_tasks()
    {
        if ($this->input->post()) {
            $formpost = $this->input->post(NULL, TRUE);
            echo $formpost['type'];
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
//            $check = $this->my_hr_document_model->removeVerificationKey($document_id);
//            if ($check == "true") {
//                $data = array('verification_key' => NULL);
//                $this->my_hr_document_model->updateUserDocument($document_id, $data);
//            }
        }
    }

}

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Received_documents extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('received_documents_model');
        $this->load->model('my_hr_document_model');
    }
    
    public function index($verificationKey = NULL) {

        if($this->session->userdata('logged_in')['company_detail']['ems_status']){
            redirect(base_url('my_documents'),'refresh');
        }
        $data['title']                                                          = "Offer Letter and HR Documents Panel";
        if ($verificationKey != NULL) {
            $userDataObject                                                     = $this->received_documents_model->get_user_detail($verificationKey);
            if ($userDataObject->num_rows() > 0) {
                    $data['verificationKey']                                    = $verificationKey;
                    $res                                                        = $userDataObject->result_array();
                    $employer_sid                                               = $res[0]['receiver_sid'];
                    $this->load->model('dashboard_model');
                    $data['employerDetail']                                     = $this->dashboard_model->getEmployerDetail($employer_sid);
                    $data['companyDetail']                                      = $this->dashboard_model->getCompanyDetail($data['employerDetail']['parent_sid']);
                    $data['documents']                                          = $this->my_hr_document_model->getAllReceivedDocuments('document', $employer_sid);

                    foreach ($data['documents'] as $key => $document) {
                        $document['actionCheck']                                = $this->my_hr_document_model->removeVerificationKey($document['user_document_sid']);
                        $data['documents'][$key]                                = $document;
                    }

                    $data['offerLetters']                                       = $this->my_hr_document_model->getAllReceivedOfferLetters('offerletter', $employer_sid);
                    $this->load->view('employee_onboarding/my-hr-document_offer_letter', $data);
            } else {
                $this->session->set_flashdata('message', '<b>Notification: </b>The link has Expired!');
                $this->load->view('employee_onboarding/error_file', $data);
            }
        } else {
            $this->session->set_flashdata('message', '<b>Error: </b>Verification key missing, Please use a Verification key to access this link!');
            $this->load->view('employee_onboarding/error_file', $data);
        }
    }
}
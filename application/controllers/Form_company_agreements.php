<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Form_company_agreements extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index($verification_key = null)
    {
        $this->load->model('manage_admin/documents_model');
        $data = array();

        if ($verification_key != null) {

            $document_record = $this->documents_model->get_document_record('uploaded_document', $verification_key);

            if (!empty($document_record)) {

                $this->form_validation->set_rules('company_sid', 'company sid', 'required|xss_clean|trim');

                if ($this->form_validation->run() == false) {


                } else {
                    $company_sid = $this->input->post('company_sid');
                    $document_name = $this->input->post('document_name');
                    $file_name = upload_file_to_aws('client_document', $company_sid, $document_name, 'by_client_' . date('Ymd'));

                    $dataToSave = array();
                    $dataToSave['client_upload_date'] = date('Y-m-d H:i:s');
                    $dataToSave['client_aws_filename'] = $file_name;

                    $this->documents_model->update_document_record('uploaded_document', $verification_key, $dataToSave, 'signed');

                    $this->session->set_flashdata('message', '"We Appreciate Your Business"');

                    redirect('thank_you', 'refresh');
                }

                $data['page_title'] = 'Company Agreements';
                $data['document_record'] = $document_record;
                $this->load->view('form_company_agreements/index', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }

    }

    public function market_agency_documents($verification_key = null)
    {
        $this->load->model('manage_admin/marketing_agency_documents_model');
        $data = array();

        if ($verification_key != null) {

            $document_record = $this->marketing_agency_documents_model->get_document_record('uploaded_document', $verification_key);

            if (!empty($document_record)) {

                $this->form_validation->set_rules('marketing_agency_sid', 'company sid', 'required|xss_clean|trim');

                if ($this->form_validation->run() == false) {


                } else {
                    $marketing_agency_sid = $this->input->post('marketing_agency_sid');
                    $document_name = $this->input->post('document_name');
                    $file_name = upload_file_to_aws('client_document', $marketing_agency_sid, $document_name, 'by_client_' . date('Ymd'));
//                    $file_name = '0003-test-by_admin_20190124-M7i.docx';

                    $dataToSave = array();
                    $dataToSave['client_upload_date'] = date('Y-m-d H:i:s');
                    $dataToSave['client_aws_filename'] = $file_name;

                    $this->marketing_agency_documents_model->update_document_record('uploaded_document', $verification_key, $dataToSave, 'signed');

                    $this->session->set_flashdata('message', '"We Appreciate Your Business"');

                    redirect('thank_you', 'refresh');
                }

                $data['page_title'] = 'Market Agency Documents';
                $data['document_record'] = $document_record;
                $this->load->view('form_company_agreements/market_agency_documents', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }

    }

}

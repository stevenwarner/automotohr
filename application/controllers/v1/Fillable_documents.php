<?php

use function GuzzleHttp\Psr7\str;

defined('BASEPATH') or exit('No direct script access allowed');

class Fillable_documents extends Public_Controller
{

    public function __construct()
    {

        parent::__construct();
        $this->load->model('v1/fillable_documents_model');
        $this->load->library('pagination');
    }

    public function index()
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            check_access_permissions($security_details, 'appearance', 'document_management_portal'); // no need to check in this Module as Dashboard will be available to all

            $company_sid = $data['session']['company_detail']['sid'];
            $company_name = $data['session']['company_detail']['CompanyName'];
            $employer_sid = $data['session']['employer_detail']['sid'];
            $employer_email = $data['session']['employer_detail']['email'];
            $employer_first_name = $data['session']['employer_detail']['first_name'];
            $employer_last_name = $data['session']['employer_detail']['last_name'];


            $data['title'] = 'Document Management';
            $data['company_sid'] = $company_sid;
            $data['company_name'] = $company_name;
            $data['employer_sid'] = $employer_sid;
            $data['employer_email'] = $employer_email;
            $data['employer_first_name'] = $employer_first_name;
            $data['employer_last_name'] = $employer_last_name;
            $this->form_validation->set_rules('perform_action', 'perform_action', 'required|trim|xss_clean');

            if ($this->form_validation->run() == false) {
                //
                $this->setFillableDocumentsSlug($company_sid);
            }
        } else {
            redirect('login', 'refresh');
        }
    }


    //
    public function setFillableDocumentsSlug($companySid)
    {
        //
        foreach (FILLABLE_DOCUMENTS as $fillableSlug) {
            $slugcount = $this->fillable_documents_model->getFillableDocumentsSlug($companySid, $fillableSlug);
            if ($slugcount < 1) {
                $insertData['fillable_documents_slug'] = $fillableSlug;
                $insertData['company_sid'] = $companySid;
                $insertData['employer_sid'] = 0;
                $insertData['document_type'] = 'generated';
                $insertData['document_title'] = str_replace('-', ' ', $fillableSlug);
                $this->fillable_documents_model->setFillableDocumentsSlug($insertData);
            }
        }
        echo "Done";
    }


    //
    public function previeFillable($cocumentSlug)
    {
        //
        $previewDoc = 'hr_' . str_replace('-', '_', $cocumentSlug);
        $this->load->view('v1/fillable_documents/' . $previewDoc);
    }

    //
    public function PrintPrevieFillable($cocumentSlug, $documentId, $original, $printDownload)
    {
        //
        $data['formInputData'] = '';
        if ($original == 'submited') {
            $document = $this->fillable_documents_model->get_assigned_document($documentId);
            $data['document'] = $document;
            $data['formInputData'] = json_decode(unserialize($document['form_input_data']), true);
        }

        if($cocumentSlug='employee-performance-evaluation'){
            $data['sectionsdata'] = employeePerformanceDocSectionsData($documentId);
        }
        

        $previewDoc = 'print_' . str_replace('-', '_', $cocumentSlug);
        $data['printDownload'] = $printDownload;
        $data['documentName'] = str_replace('-', '_', $cocumentSlug);

        $this->load->view('v1/fillable_documents/' . $previewDoc, $data);
    }


    public function previeFillableSubmited($cocumentSlug, $documentId)
    {
        //
        $document = $this->fillable_documents_model->get_assigned_document($documentId);
        $data['document'] = $document;
        $data['formInputData'] = json_decode(unserialize($document['form_input_data']), true);

        if($cocumentSlug='employee-performance-evaluation'){
            $data['sectionsdata'] = employeePerformanceDocSectionsData($documentId);
        }

        $previewDoc = 'hr_' . str_replace('-', '_', $cocumentSlug);

        $this->load->view('v1/fillable_documents/' . $previewDoc, $data);
    }


    public function previeFillableAjax($cocumentSlug)
    {
        //
        $previewDoc = 'hr_' . str_replace('-', '_', $cocumentSlug);
        echo  $this->load->view('v1/fillable_documents/' . $previewDoc, '[]', true);
    }


    //
    public function downloadFillable($cocumentSlug, $documentId, $original, $printDownload)
    {
        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');
            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];
            $data['title'] = 'Documents Assignment';
            $data['company_sid'] = $company_sid;
            $data['employer_sid'] = $employer_sid;

            //
            $data['formInputData'] = '';
            $document = $this->fillable_documents_model->get_assigned_document($documentId);
            $data['document'] = $document;
            $data['formInputData'] = json_decode(unserialize($document['form_input_data']), true);

            //
            $previewDoc = 'print_' . str_replace('-', '_', $cocumentSlug);
            $data['printDownload'] = $printDownload;
            $data['documentName'] = str_replace('-', '_', $cocumentSlug);

            //
            $this->load->view('v1/fillable_documents/' . $previewDoc, $data);

            $this->load->model('hr_documents_management_model');
            $this->fillable_documents_model->update_download_status($documentId);
        } else {
            redirect('login', 'refresh');
        }
    }
}

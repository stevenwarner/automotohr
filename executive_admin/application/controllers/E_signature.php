<?php defined('BASEPATH') or exit('No direct script access allowed');

class E_signature extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('e_signature_model');
    }

    public function index()
    {

        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['session'] = $this->session->userdata('executive_loggedin');
            $data['exadminId'] = $data['executive_user']['sid'];
            $executive_user_sid = $data['session']['executive_user']['sid'];

            $data['title'] = 'E-Signature Management';
            $this->form_validation->set_rules('perform_action', 'preform_action', 'required|trim');
            $e_signature_data = $this->e_signature_model->get_e_signature($executive_user_sid);

            if (!empty($e_signature_data)) {
                $data['consent'] = $e_signature_data['user_consent'];
            } else {
                $data['consent'] = 0;
            }

            $data['e_signature_data'] = $e_signature_data;
      
            if ($this->form_validation->run() == false) {
                $this->load->view('main/header', $data);
                $this->load->view('e_signature/index');
                $this->load->view('main/footer');
            } else {
                $form_post = $this->input->post();
                $insert_signature = $this->e_signature_model->set_e_signature($form_post);

                if ($insert_signature != 'error') {
                    $this->session->set_flashdata('message', '<strong>Success</strong> E-Signature Updated!');
                    redirect('e_signature', 'refresh');
                } else {
                    $this->session->set_flashdata('message', '<strong>Error</strong> E-Signature Not Updated!');
                    redirect('e_signature', 'refresh');
                }
            }
        } else {
            redirect('login', "refresh");
        }
    }

    function get_signature($user_sid, $company_sid, $user_type)
    {
        $signature = get_e_signature($company_sid, $user_sid, $user_type);

        $return_data = array();
        if (!empty($signature)) {
            $return_data['signature_sid'] = $signature['sid'];
            $return_data['company_sid'] = $signature['company_sid'];
            $return_data['signature'] = $signature['signature'];
            $return_data['signature_timestamp'] = date('m-d-Y', strtotime(str_replace('-', '/', $signature['signature_timestamp'])));
            $return_data['signature_bas64_image'] = $signature['signature_bas64_image'];
            $return_data['init_signature_bas64_image'] = $signature['init_signature_bas64_image'];
            $return_data['active_signature'] = $signature['active_signature'];
            $return_data['ip_address'] = $signature['ip_address'];
            $return_data['user_agent'] = $signature['user_agent'];
            $return_data['user_name'] = $signature['first_name'] . ' ' . $signature['last_name'];

            echo json_encode($return_data);
            exit(0);
        } else {
            echo false;
        }
    }

    function get_preparer_signature($document_sid, $user_sid, $company_sid, $user_type)
    {
        $signature = get_preparer_e_signature($document_sid, $company_sid, $user_sid, $user_type);

        $return_data = array();
        if (!empty($signature['section1_preparer_signature'] && isset($signature['section1_preparer_signature']))) {
            $return_data['section1_preparer_signature'] = $signature['section1_preparer_signature'];
            echo json_encode($return_data);
        } else {
            echo false;
        }
    }

    function get_agent_signature($marketing_agency_sid, $recode_sid, $user_type, $url)
    {

        $signature = get_agent_e_signature($marketing_agency_sid, $recode_sid, $user_type, $url);

        if (!empty($signature['signature_bas64_image'])) {

            $return_data['signature_bas64_image'] = $signature['signature_bas64_image'];
            $return_data['signature_timestamp'] = $signature['signature_timestamp'];
            echo json_encode($return_data);
        } else {

            echo false;
        }
    }

    function ajax_e_signature()
    {
        $form_post = $this->input->post();
        $insert_signature = set_e_signature($form_post);
    }

    function prepare_e_signature()
    {
        $form_post = $this->input->post();
        $company_sid = $form_post['company_sid'];
        $user_type = $form_post['user_type'];
        $user_sid = $form_post['user_sid'];
        $document_sid = $form_post['form_i9_sid'];
        $signature = get_preparer_e_signature($document_sid, $company_sid, $user_sid, $user_type);

        if (empty($signature['section1_preparer_signature'])) {
            $insert_signature = set_prepare_e_signature($form_post);
        }
    }

    function ajax_agent_e_signature()
    {
        $form_post = $this->input->post();
        $insert_signature = set_agent_e_signature($form_post);
    }

    function regenerate_e_signature($user_sid)
    {
        $data_to_update = array();
        $data_to_update['status'] = 0;
        $this->e_signature_model->regenerate_e_signature($user_sid, $data_to_update);
        echo true;
    }
}

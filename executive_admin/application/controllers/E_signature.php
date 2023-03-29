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

                $this->e_signature_model->apply_e_signature($data['exadminId']);

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


    //
    function regenerate_e_signature($user_sid)
    {
        $data_to_update = array();
        $data_to_update['status'] = 0;
        $this->e_signature_model->regenerate_e_signature($user_sid, $data_to_update);
        echo true;
    }

    //
    function apply_e_signature()
    {
        $form_post = $this->input->post();
        $executive_sid =  $form_post['executive_sid'];
        $this->e_signature_model->apply_e_signature($executive_sid);
        echo true;
    }
}

<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Complynet extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('Users_model');
    }

    public function index($cid = NULL) {
        if ($this->session->userdata('executive_loggedin')) {
            $data                                                               = $this->session->userdata('executive_loggedin');
            $data['page_title']                                                 = 'ComplyNet';
            $data['cid']                                                        = $cid;
            if($cid == NULL){
                $this->session->set_flashdata('message', 'Error: Company Missing');
                redirect(base_url('dashboard'), "refresh");
            }
            $company_details = $this->Users_model->get_company_details($cid);
            if(empty($company_details)){
                $this->session->set_flashdata('message', 'Company not found');
                redirect(base_url('dashboard'), "refresh");
            } else {

                if(!$company_details[0]['complynet_status']){
                    $this->session->set_flashdata('message', 'Error: Not Accessible');
                    redirect(base_url('dashboard'), "refresh");
                }
            }
            $data['company_details'] = $company_details[0];
            $this->load->view('main/header', $data);
            $this->load->view('complynet/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}
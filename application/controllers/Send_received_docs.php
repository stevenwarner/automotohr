<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Send_received_docs extends Public_Controller {
    private $security_details;
    public function __construct() {
        parent::__construct();
        $this->load->model('send_received_docs_model');
        $session_details                                                        = $this->session->userdata('logged_in');
        $sid                                                                    = $session_details['employer_detail']['sid'];
        $this->security_details                                                 = db_get_access_level_details($sid);
    }

    public function index() {
        if ($this->session->has_userdata('logged_in')) {
            $security_details                                                   = $this->security_details;
            $data['security_details']                                           = $security_details; 
            $data['title']                                                      = "Orders History";
            $data['session']                                                    = $this->session->userdata('logged_in');
            $company_sid                                                        = $data["session"]["company_detail"]["sid"];
            $employer_sid                                                       = $data["session"]["employer_detail"]["sid"];
            $data['documents']                                                  = $this->send_received_docs_model->get_documents($company_sid);

            $this->load->view('main/header', $data);
            $this->load->view('manage_employer/order_history');
            $this->load->view('main/footer');
        }//if end for session check success
        else {
            redirect(base_url('login'), "refresh");
        }//else end for session check fail
    }

}

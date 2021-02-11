<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Refer_an_affiliate extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('refer_an_affiliate_model');
    }

    public function index($affiliate_user_sid = null) {
        if ($this->session->userdata('affiliate_loggedin')) {
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $data['user_id'] = $affiliate_data['affiliate_users']['sid'];
            $data['title'] = 'Refer An Affiliate';
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            
            if ($affiliate_user_sid != null) {
                $affiliate_user_info = $this->refer_an_affiliate_model->get_reffer_affiliate_user($affiliate_user_sid);

                if (!empty($affiliate_user_info)) {
                    $data['affiliate_user_info'] = $affiliate_user_info;
                }
            }
            
            $firstName = array('field' => 'first_name',
                'label' => 'First Name',
                'rules' => 'xss_clean|trim|required');

            $lastName = array('field' => 'last_name',
                'label' => 'Last Name',
                'rules' => 'xss_clean|trim|required');

            $emailAddress = array('field' => 'email',
                'label' => 'Email',
                'rules' => 'xss_clean|trim|valid_email|required');
            
            $countries = $this->refer_an_affiliate_model->get_all_countries();
            $data['countries'] = $countries;
            $config[] = $firstName;
            $config[] = $lastName;
            $config[] = $emailAddress;
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('refer_an_affiliate/index');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);

                if ($formpost['perform_action'] == 'add_new_affiliate_user') {
                    $already_referred = $this->refer_an_affiliate_model->check_reffer_affiliater($formpost['email']);

                    if (sizeof($already_referred) > 0) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Provided Email!');
                        redirect('refer-an-affiliate', 'refresh');
                    }
                    
                    $insert_data = array();
                    $insert_data['first_name'] = $formpost['first_name'];
                    $insert_data['last_name'] = $formpost['last_name'];
                    $insert_data['email'] = $formpost['email'];
                    $insert_data['company'] = $formpost['company'];
                    $insert_data['street'] = $formpost['street'];
                    $insert_data['city'] = $formpost['city'];
                    $insert_data['state'] = $formpost['state_province'];
                    $insert_data['zip_code'] = $formpost['zip_postal_code'];
                    $insert_data['country'] = $formpost['country'];
                    $insert_data['website'] = $formpost['website'];
                    $insert_data['request_date'] = date('Y-m-d H:i:s');
                    $insert_data['contact_number'] = $formpost['contact_number'];
                    $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $insert_data['is_reffered'] = 1;
                    $insert_data['refferred_by_sid'] = $formpost['referred_by'];
                    $insert_data['ip_address'] = $_SERVER['REMOTE_ADDR'];
                    $this->refer_an_affiliate_model->insert_refer_form($insert_data);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>User Referred Successfully!');
                    redirect('refer-an-affiliate', 'refresh');
                } elseif ($formpost['perform_action'] == 'edit_affiliate_user') {
                    $affiliate_user_sid = $formpost['affiliate_user_sid'];
                    $already_referred = $this->refer_an_affiliate_model->check_reffer_affiliater_before_update_record($affiliate_user_sid, $formpost['email']);

                    if (sizeof($already_referred) > 0) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Provided Email!');
                        redirect('view-referral-affiliates', 'refresh');
                    }

                    $update_affiliate_user_data = array();
                    $update_affiliate_user_data['first_name'] = $formpost['first_name'];
                    $update_affiliate_user_data['last_name'] = $formpost['last_name'];
                    $update_affiliate_user_data['email'] = $formpost['email'];
                    $update_affiliate_user_data['company'] = $formpost['company'];
                    $update_affiliate_user_data['street'] = $formpost['street'];
                    $update_affiliate_user_data['city'] = $formpost['city'];
                    $update_affiliate_user_data['state'] = $formpost['state_province'];
                    $update_affiliate_user_data['zip_code'] = $formpost['zip_postal_code'];
                    $update_affiliate_user_data['country'] = $formpost['country'];
                    $update_affiliate_user_data['website'] = $formpost['website'];
                    $update_affiliate_user_data['contact_number'] = $formpost['contact_number'];
                    $this->refer_an_affiliate_model->update_affiliate_user_record($affiliate_user_sid, $update_affiliate_user_data);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>User Update Successfully!');
                    redirect('view-referral-affiliates', 'refresh');
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        }
    }

    public function edit() {
        $view_data = array();
        $affiliate_data = $this->session->userdata('affiliate_loggedin');
        $data['session'] = $affiliate_data;
        $affiliate_detail                                                    = $data['session']['affiliate_users'];
        $security_sid                                                       = $affiliate_detail['sid'];
        $security_details                                                   = db_get_access_level_details($security_sid);
        $data['security_details']                                           = $security_details;
        $view_data['title'] = 'Refer Potential Clients: Edit';
        $this->load->view('main/header', $view_data);
        $this->load->view('refer_potential_clients/index');
        $this->load->view('main/footer');
    }

    public function affiliate_listing() {
        if ($this->session->userdata('affiliate_loggedin')) {
            $data = array();
            $data['title'] = 'Referred Affiliate Management';
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $user_id = $affiliate_data['affiliate_users']['sid'];
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;


            $referred = $this->refer_an_affiliate_model->get_all_referel_affiliate($user_id);
            $data['referred'] = $referred;
            $data['count'] = count($referred);
            $this->load->view('main/header', $data);
            $this->load->view('refer_an_affiliate/affiliate_listing');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }
}
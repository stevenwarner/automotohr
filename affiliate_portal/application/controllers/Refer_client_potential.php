<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Refer_client_potential extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('refer_potential_clients_model');
    }
    
    public function index($reffer_user_sid = null) {
        if ($this->session->userdata('affiliate_loggedin')) {
            $affiliate_data = $this->session->userdata('affiliate_loggedin');
            $data['name'] = $affiliate_data['affiliate_users']['full_name'];
            $data['user_id'] = $affiliate_data['affiliate_users']['sid'];
            $data['title'] = 'Refer Potential Clients';
            $data['session'] = $affiliate_data;
            $affiliate_detail                                                    = $data['session']['affiliate_users'];
            $security_sid                                                       = $affiliate_detail['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details;
            if ($reffer_user_sid != null) {
                $reffer_user_info = $this->refer_potential_clients_model->get_reffer_potential_user($reffer_user_sid);
                
                if (!empty($reffer_user_info)) {
                    $data['reffer_user_info'] = $reffer_user_info;
                } 
            }
            
            $firstName = array( 'field' => 'first_name',
                                'label' => 'First Name',
                                'rules' => 'xss_clean|trim|required');
            
            $lastName = array(  'field' => 'last_name',
                                'label' => 'Last Name',
                                'rules' => 'xss_clean|trim|required');
            
            $emailAddress = array(  'field' => 'email',
                                    'label' => 'Email',
                                    'rules' => 'xss_clean|trim|valid_email|required');

            $data_countries                     = db_get_active_countries();

            foreach ($data_countries as $value) {
                $data_states[$value['sid']]     = db_get_active_states($value['sid']);
            }

            $data['active_countries']     = $data_countries;
            $data['active_states']        = $data_states;
            
            $config[] = $firstName;
            $config[] = $lastName;
            $config[] = $emailAddress;
            $this->form_validation->set_error_delimiters('<label class="error">', '</label>');
            $this->form_validation->set_rules($config);
            
            if ($this->form_validation->run() == FALSE) {
                $this->load->view('main/header', $data);
                $this->load->view('refer_potential_clients/index');
                $this->load->view('main/footer');
            } else {
                $formpost = $this->input->post(NULL, TRUE);
                
                if ($formpost['perform_action'] == 'add_new_reffer_user') {
                    $already_referred = $this->refer_potential_clients_model->check_refer_affiliater($formpost['email']);
                
                    if (sizeof($already_referred) > 0) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Provided Email!');
                        redirect('refer-potential-clients', 'refresh');
                    }
                    
                    $insert_data = array();
                    $insert_data['first_name'] = $formpost['first_name'];
                    $insert_data['last_name'] = $formpost['last_name'];
                    $insert_data['email'] = $formpost['email'];
                    $insert_data['job_role'] = $formpost['job_role'];
                    $insert_data['company_name'] = $formpost['company_name'];
                    $insert_data['company_size'] = $formpost['company_size'];
                    $insert_data['phone_number'] = $formpost['contact_number'];
                    $insert_data['street'] = $formpost['street'];
                    $insert_data['city'] = $formpost['city'];
                    $insert_data['state'] = $formpost['state_province'];
                    $insert_data['zip_code'] = $formpost['zip_postal_code'];
                    foreach ($data_countries as $country) {
                        if ($formpost['country'] == $country['sid']) {
                            $insert_data['country'] = $country['country_name'];
                        }
                    }
                    $insert_data['client_message'] = $formpost['client_message'];
                    $insert_data['newsletter_subscribe'] = $formpost['newsletter_subscribe'];
                    $insert_data['date_requested'] = date('Y-m-d H:i:s');
                    $insert_data['is_reffered'] = 1;
                    $insert_data['refferred_by_sid'] = $formpost['referred_by'];
                    $insert_data['ip_address'] = $_SERVER['REMOTE_ADDR'];
                    $insert_data['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
                    $this->refer_potential_clients_model->insert_refer_form($insert_data);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>User Referred Successfully!');
                    redirect('refer-potential-clients', 'refresh');
                } elseif ($formpost['perform_action'] == 'edit_reffer_user') {
                    $reffer_user_sid = $formpost['reffer_user_sid'];
                    $already_referred = $this->refer_potential_clients_model->check_reffer_potential_before_update_record($reffer_user_sid, $formpost['email']);
                    
                    if (sizeof($already_referred) > 0) {
                        $this->session->set_flashdata('message', '<strong>Error: </strong>Already Used This Provided Email!');
                        redirect('view-referral-clients', 'refresh');
                    }
                   
                    $update_reffer_user_data = array();
                    $update_reffer_user_data['first_name'] = $formpost['first_name'];
                    $update_reffer_user_data['last_name'] = $formpost['last_name'];
                    $update_reffer_user_data['email'] = $formpost['email'];
                    $update_reffer_user_data['job_role'] = $formpost['job_role'];
                    $update_reffer_user_data['company_name'] = $formpost['company_name'];
                    $update_reffer_user_data['company_size'] = $formpost['company_size'];
                    $update_reffer_user_data['phone_number'] = $formpost['contact_number'];
                    $update_reffer_user_data['street'] = $formpost['street'];
                    $update_reffer_user_data['city'] = $formpost['city'];
                    $update_reffer_user_data['state'] = $formpost['state_province'];
                    $update_reffer_user_data['zip_code'] = $formpost['zip_postal_code'];
                    foreach ($data_countries as $country) {
                        if ($formpost['country'] == $country['sid']) {
                            $update_reffer_user_data['country'] = $country['country_name'];
                        }
                    }
                    $update_reffer_user_data['client_message'] = $formpost['client_message'];
                    $update_reffer_user_data['newsletter_subscribe'] = $formpost['newsletter_subscribe'];
                    $this->refer_potential_clients_model->update_reffer_potential_user_record($reffer_user_sid, $update_reffer_user_data);
                    $this->session->set_flashdata('message', '<strong>Success: </strong>User Update Successfully!');
                    redirect('view-referral-clients', 'refresh');
                }
            }
        } else {
            redirect(base_url('login'), 'refresh');
        } 
    }
    
    public function getnewstates($country_sid) {
        $states = db_get_active_states($country_sid);
        $html = '<option value="">Select State</option>';
        foreach ($states as $key => $state) {
            $name = $state['state_name'];
            $html .= '<option value="' . $name . '">' . $name . '</option>';
        }
      
        echo json_encode($html);
    }
}
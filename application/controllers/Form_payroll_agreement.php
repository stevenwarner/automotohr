<?php defined('BASEPATH') or exit('No direct script access allowed');

class Form_payroll_agreement extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('manage_admin/documents_model');
    }

    public function index($verification_key = null, $pre_fill_flag = null)
    {
        $data = array();
        if ($verification_key != null) {

            $document_record = $this->documents_model->get_document_record('form_payroll_agreement', $verification_key);
            $agent_id = $document_record['company_sid'];
            $agent_record = $this->documents_model->get_agent_record($agent_id, 'form_payroll_agreement');

            if (!empty($document_record)) {
                $status =  $document_record['status'];
                $company_sid = $document_record['company_sid'];

                $ip_track = $this->documents_model->get_document_ip_tracking_record($company_sid, 'payroll_agreement');
                $data['ip_track'] = $ip_track;

                $this->form_validation->set_error_delimiters('<span class="error">', '</span>');
                $this->form_validation->set_message('required', '%s Required');

                if ($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') {
                    $this->form_validation->set_rules('company_sid', 'company_sid', 'xss_clean|trim');
                    $this->form_validation->set_rules('the_entity', 'Entity', 'xss_clean|trim');
                    $this->form_validation->set_rules('the_client', 'Client', 'xss_clean|trim');
                    $this->form_validation->set_rules('development_fee', 'Fee', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('monthly_fee', 'Fee', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('number_of_rooftops_locations', 'Rooftop Locations', 'xss_clean|trim|numeric');
                    $this->form_validation->set_rules('number_of_employees', 'Number of Employees', 'xss_clean|trim|numeric');

                    if ($this->input->post('payment_method') == 'trial_period') {
                        $this->form_validation->set_rules('trial_fee', 'Trial Fee', 'xss_clean|trim|numeric');
                        $this->form_validation->set_rules('recurring_payment_day', 'Recurring Trial Payment Day', 'xss_clean|trim|numeric');
                        $this->form_validation->set_rules('payment_method', 'Method', 'xss_clean|trim');
                        $this->form_validation->set_rules('trial_limit', 'Trial Limit', 'xss_clean|trim|numeric');
                        $this->form_validation->set_rules('number_of_rooftops_locations_trial', 'Rooftop Locations', 'xss_clean|trim|numeric');
                        $this->form_validation->set_rules('number_of_employees_trial', 'Number of Employees', 'xss_clean|trim|numeric');
                    }

                    $this->form_validation->set_rules('company_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_name', 'Name', 'xss_clean|trim');
                    $this->form_validation->set_rules('company_title', 'Title', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_date', 'Date', 'xss_clean|trim');
                    $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'xss_clean|trim');
                } else { 
                    $this->form_validation->set_rules('company_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_by', 'By', 'xss_clean|trim');
                    $this->form_validation->set_rules('client_name', 'Name', 'required|xss_clean|trim');
                    $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'required|xss_clean|trim');
                }



                ////
                
                $this->form_validation->set_rules('client_by', 'By', 'required|xss_clean|trim');
                $this->form_validation->set_rules('client_name', 'Name', 'required|xss_clean|trim');
                $this->form_validation->set_rules('client_title', 'Name', 'required|xss_clean|trim');

                $this->form_validation->set_rules('contract_term', 'Contract Term', 'required|xss_clean|trim');
                $this->form_validation->set_rules('term_number_of', 'Number of', 'required|xss_clean|trim');



                // Credit Card 
                $this->form_validation->set_rules('authorized_person_full_name', 'Name', 'xss_clean|trim');
                $this->form_validation->set_rules('recurring_amount', 'Recurring Amount', 'xss_clean|trim|numeric');
                $this->form_validation->set_rules('day_of_payment', 'Payment Day', 'xss_clean|trim');
                $this->form_validation->set_rules('authorization_on_behalf_of', 'Individual or Company', 'xss_clean|trim');
                $this->form_validation->set_rules('billing_address', 'Billing Address', 'xss_clean|trim');
                $this->form_validation->set_rules('billing_phone_number', 'Phone Number', 'xss_clean|trim');
                $this->form_validation->set_rules('billing_city', 'City', 'xss_clean|trim');
                $this->form_validation->set_rules('billing_state', 'State', 'xss_clean|trim');
                $this->form_validation->set_rules('billing_zip_code', 'Zip Code', 'xss_clean|trim');
                $this->form_validation->set_rules('billing_email_address', 'Email', 'xss_clean|trim|valid_email');
                $this->form_validation->set_rules('cc_type', '', 'xss_clean|trim');
                $this->form_validation->set_rules('cc_holder_name', 'Name', 'xss_clean|trim');
                $this->form_validation->set_rules('cc_number', 'Number', 'xss_clean|trim');
                $this->form_validation->set_rules('cc_expiration_month', 'Expiration Month', 'xss_clean|trim');
                $this->form_validation->set_rules('cc_expiration_year', 'Expiration Year', 'xss_clean|trim');
                $this->form_validation->set_rules('cc_front_image', 'Front Image', 'xss_clean|trim');
                $this->form_validation->set_rules('cc_back_image', 'Back Image', 'xss_clean|trim');
                $this->form_validation->set_rules('driving_license_front_image', 'Front Image', 'xss_clean|trim');
                $this->form_validation->set_rules('authorization_date', 'Date', 'xss_clean|trim');
                $this->form_validation->set_rules('additional_fee', 'Additional Fee', 'xss_clean|trim|numeric');
                $this->form_validation->set_rules('acknowledgement', 'Acknowledgement', 'xss_clean|trim');



                if (!$this->form_validation->run()) {
                } else {

                    //
                    $is_pre_fill = $this->input->post('is_pre_fill');
                    $clientName = $this->input->post('client_name');
                    //
                    $dataToSave = array();
                    $dataToSave['company_sid'] = $this->input->post('company_sid');
                    $dataToSave['acknowledgement'] = $this->input->post('acknowledgement');
                    $dataToSave['client_name'] = $clientName;
                    //
                    $dataToSave['client_by'] = $this->input->post('client_by');
                    $dataToSave['client_title'] = $this->input->post('client_title');
                    $dataToSave['client_date'] = date('Y-m-d H:i:s', strtotime(str_replace('-', '/', $this->input->post('client_date'))));
                    $dataToSave['client_signature'] = $this->input->post('client_signature');
                    //
                    if ($is_pre_fill == 1) {
                        $dataToSave['the_entity'] = $this->input->post('the_entity');
                        $dataToSave['the_client'] = $this->input->post('the_client');
                        $dataToSave['development_fee'] = $this->input->post('development_fee');

                        if ($this->input->post('payment_method') == 'monthly_subscription') {
                            $dataToSave['monthly_fee'] = $this->input->post('monthly_fee');
                            $dataToSave['is_trial_period'] = 0;
                            $dataToSave['number_of_rooftops_locations'] = $this->input->post('number_of_rooftops_locations');
                            $dataToSave['no_of_employees'] = $this->input->post('number_of_employees');
                        } else {
                            $dataToSave['monthly_fee'] = $this->input->post('trial_fee');
                            $dataToSave['recurring_payment_day'] = $this->input->post('recurring_payment_day');
                            $dataToSave['is_trial_period'] = 1;
                            $dataToSave['trial_limit'] = $this->input->post('trial_limit');
                            $dataToSave['number_of_rooftops_locations'] = $this->input->post('number_of_rooftops_locations_trial');
                            $dataToSave['no_of_employees'] = $this->input->post('number_of_employees_trial');
                        }
                        
                        //
                        if (!$dataToSave['number_of_rooftops_locations']) {
                            $dataToSave['number_of_rooftops_locations'] = $document_record['number_of_rooftops_locations'];
                        }
                    }    
                    //
                    $status = '';
                    //
                    if ($is_pre_fill == 1) {
                        $status = 'pre-filled';
                    } else {
                        $status = 'signed';
                        $dataToSave['client_ip'] = getUserIP();
                    }
                    // 
                    
                    $dataToSave['term_number_of'] = $this->input->post('term_number_of');
                    $dataToSave['contract_term'] = $this->input->post('contract_term');

                    $dataToSave['authorized_person_full_name'] = $this->input->post('authorized_person_full_name');
                    $dataToSave['recurring_amount'] = $this->input->post('recurring_amount');
                    $dataToSave['day_of_payment'] = $this->input->post('day_of_payment');
                    $dataToSave['authorization_on_behalf_of'] = $this->input->post('authorization_on_behalf_of');
                    $dataToSave['billing_address'] = $this->input->post('billing_address');
                    $dataToSave['billing_phone_number'] = $this->input->post('billing_phone_number');
                    $dataToSave['billing_city'] = $this->input->post('billing_city');
                    $dataToSave['billing_state'] = $this->input->post('billing_state');
                    $dataToSave['billing_zip_code'] = $this->input->post('billing_zip_code');
                    $dataToSave['billing_email_address'] = $this->input->post('billing_email_address');
                    $dataToSave['cc_type'] = $this->input->post('cc_type');
                    $dataToSave['cc_holder_name'] = $this->input->post('cc_holder_name');
                    $dataToSave['cc_number'] = $this->input->post('cc_number');
                    $dataToSave['cc_expiration_month'] = $this->input->post('cc_expiration_month');
                    $dataToSave['cc_expiration_year'] = $this->input->post('cc_expiration_year');

                    $this->documents_model->update_document_record('form_payroll_agreement', $verification_key, $dataToSave, $status);

                    if ($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') {
                        $this->session->set_flashdata('message', 'Form Successfully Pre-Filled.');

                        $this->documents_model->insert_document_ip_tracking_record($company_sid, 0, getUserIP(), 'payroll_agreement', 'pre_filled', $_SERVER['HTTP_USER_AGENT']);

                        redirect('manage_admin/documents/' . $company_sid, 'refresh');
                    } else {
                        $this->session->set_flashdata('message', '"We Appreciate Your Business"');

                        if ($this->session->userdata('logged_in')) {
                            $data['session'] = $this->session->userdata('logged_in');
                            $employer_sid = $data["session"]["employer_detail"]["sid"];
                        } else {
                            $employer_sid = -1;
                        }

                        $this->documents_model->insert_document_ip_tracking_record($company_sid, $employer_sid, getUserIP(), 'payroll_agreement', 'signed', $_SERVER['HTTP_USER_AGENT']);

                        redirect('thank_you', 'refresh');
                    }
                }
                //Check if is prefill by admin
                if ($pre_fill_flag != null && $pre_fill_flag == 'pre_fill') {
                    $data['is_pre_fill'] = 1;
                } else {
                    $data['is_pre_fill'] = 0;
                }

                if ($status == 'signed') {
                    $data['readonly'] = 1;
                } else {
                    $data['readonly'] = 0;
                }

                if (empty($agent_record)) {
                    $agent_record['sid'] = $agent_id;
                    $agent_record['full_name'] = 'Unknown';
                    $agent_record['email'] = 'Unknown';
                }

                $data['page_title'] = 'Payroll Agreement';
                $data['agent_record'] = $agent_record;
                $data['company_document'] = $document_record;
                $data['verification_key'] = $verification_key;
                //
                $this->load->view('form_payroll_agreement/index', $data);
            } else {
                redirect('login', 'refresh');
            }
        } else {
            redirect('login', 'refresh');
        }
    }
}

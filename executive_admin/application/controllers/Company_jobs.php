<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Company_Jobs extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->model('company_jobs_model');
        $this->load->model('Users_model');
    }

    public function index()
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');

            $data['executive_user_companies'] = $this->Users_model->get_executive_users_companies($data['executive_user']['sid']);

            $this->load->view('main/header', $data);
            $this->load->view('dashboard/dashboard_view');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function jobs($company_id)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');

            $executive_admin_sid = $data['executive_user']['sid'];

            $executive_admin_companies = $this->company_jobs_model->get_executive_admin_company_sids($executive_admin_sid);

            if (in_array($company_id, $executive_admin_companies)) {
                if ($company_id > 0 & $company_id != null) {
                    $jobs = $this->company_jobs_model->get_company_jobs($company_id, $executive_admin_sid);
                    $data['jobs'] = $jobs;
                    $company = $this->Users_model->get_company_details($company_id);
                    $data['company'] = $company[0];

                    $this->load->view('main/header', $data);
                    $this->load->view('dashboard/company_jobs');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', 'Company not found');
                    redirect(base_url('dashboard/manage_admin_companies'), "refresh");
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error :</strong> You dont have access to this company!');
                redirect('dashboard', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

    public function job_applicants($company_id)
    {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');

            $executive_admin_sid = $data['executive_user']['sid'];

            $executive_admin_companies = $this->company_jobs_model->get_executive_admin_company_sids($executive_admin_sid);

            if (in_array($company_id, $executive_admin_companies)) {

                if ($company_id > 0 & $company_id != null) {
                    $job_applicants = $this->company_jobs_model->get_company_job_applicants($company_id, $executive_admin_sid);
                    $data['job_applicants'] = $job_applicants;
                    $company = $this->Users_model->get_company_details($company_id);
                    $data['company'] = $company[0];

                    $this->load->view('main/header', $data);
                    $this->load->view('dashboard/company_jobs_applicants');
                    $this->load->view('main/footer');
                } else {
                    $this->session->set_flashdata('message', 'Company not found');
                    redirect(base_url('dashboard/manage_admin_companies'), "refresh");
                }
            } else {
                $this->session->set_flashdata('message', '<strong>Error :</strong> You dont have access to this company!');
                redirect('dashboard', 'refresh');
            }
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}

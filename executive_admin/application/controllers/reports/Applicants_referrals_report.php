<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Applicants_referrals_report extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('<p class="error_message"><i class="fa fa-exclamation-circle"></i>', '</p>');
        $this->load->library("pagination");
        $this->load->model('Reports_model');
    }

    public function index($company_sid) {
        if ($this->session->userdata('executive_loggedin')) {
            $data = $this->session->userdata('executive_loggedin');
            $data['title'] = 'Applicants Referrals Report';
            $data['company_sid'] = $company_sid;

            //**** working code ****//
            $references = $this->Reports_model->get_references($company_sid);
            $users = array();

            foreach ($references as $reference) {
                $ref = array();
                $user_sid = $reference['user_sid'];
                foreach ($references as $reference2) {
                    if ($reference2['user_sid'] == $user_sid) {
                        $ref[] = $reference2;
                    }
                }
                $users[$reference['user_name']] = $ref;
            }

            $data['users'] = $users;
            //**** working code ****//

            /** export sheet file * */
            if (isset($_POST['submit']) && $_POST['submit'] == 'Export') {
                if (isset($users) && sizeof($users) > 0) {

                    header('Content-Type: text/csv; charset=utf-8');
                    header('Content-Disposition: attachment; filename=data.csv');
                    $output = fopen('php://output', 'w');

                    foreach ($users as $user => $references) {
                        fputcsv($output, array($user, ucwords($references[0]['users_type'])));
                        fputcsv($output, array('Reference Title', 'Name', 'Type', 'Relation', 'Email', 'Department', 'Branch', 'Reference Organization'));

                        foreach ($references as $reference) {
                            $input = array();
                            $input['reference_title'] = $reference['reference_title'];
                            $input['reference_name'] = $reference['reference_name'];
                            $input['reference_type'] = $reference['reference_type'];
                            $input['reference_relation'] = $reference['reference_relation'];
                            $input['reference_email'] = $reference['reference_email'];
                            $input['department_name'] = $reference['department_name'];
                            $input['branch_name'] = $reference['branch_name'];
                            $input['organization_name'] = $reference['organization_name'];
                            fputcsv($output, $input);
                        }

                        fputcsv($output, array());
                        fputcsv($output, array());
                    }
                    fclose($output);
                    exit;
                } else {
                    $this->session->set_flashdata('message', 'No data found.');
                }
            }
            /** export sheet file * */
            $this->load->view('main/header', $data);
            $this->load->view('reports/applicants_referrals_report');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }

}

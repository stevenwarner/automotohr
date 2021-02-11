<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cssi extends Public_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->helper('cssi');
    }

    public function index()
    {

        if ($this->session->userdata('logged_in')) {
            $data['session'] = $this->session->userdata('logged_in');

            $security_sid = $data['session']['employer_detail']['sid'];
            $security_details = db_get_access_level_details($security_sid);
            $data['security_details'] = $security_details;
            //check_access_permissions($security_details, 'appearance', 'customize_appearance'); // no need to check in this Module as Dashboard will be available to all


            $company_sid = $data["session"]["company_detail"]["sid"];
            $employer_sid = $data["session"]["employer_detail"]["sid"];



            echo '<pre>';



            $access_token = cssi_get_access_token('taptest', 'taptest');
            //$access_token = cssi_get_access_token(FROM_EMAIL_DEV, 'dev@automotohr');

            $request_response = cssi_create_background_check_saved_credit_card($access_token, 'Johhy', 'Dept', '100654321', '09/06/1970', 2);


            //$request_response = cssi_get_user_account_information($access_token);




            print_r($request_response);
            exit;






            //load views
            $this->load->view('main/header', $data);
            $this->load->view('cssi/index');
            $this->load->view('main/footer');
        } else {
            redirect(base_url('login'), "refresh");
        }
    }



}

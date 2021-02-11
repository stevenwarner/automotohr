<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cron_test extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('dashboard_model');
    }

    public function index()
    {
        //$users = $this->dashboard_model->get_company_detail(31);



        $dummy_email = 'j.taylor.title@gmail.com';
        mail($dummy_email, 'Cron Test ' . date('Y-m-d H:i:s'), 'Cron Working');


    }


}

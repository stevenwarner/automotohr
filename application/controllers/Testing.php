<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
        $this->load->library('complynet');
    }

    public function complynet()
    {
        $companies = $this
        ->complynet
        ->setMode('fake')
        ->authenticate()
        ->getCompanies();

        _e($companies, true, true);
    }

}

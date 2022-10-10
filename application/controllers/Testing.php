<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("test_model", "tm");
    }

    public function complynet() {
        $this->load->library('complynet');
        echo $this->complynet->getCompanies();
    }
}

<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "pmm");
        // REVIEW_EXPIRING

    }


    

    

}
	
   

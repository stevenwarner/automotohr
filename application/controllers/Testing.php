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


 // Reset Default Categories is default status
    public function reset_default_categories(){
       
        $this->db->where('company_sid', 0);
        $this->db->or_where('default_category_sid <>', 0);
        $this->db->update('documents_category_management', array('is_default'=>1));

    }


}
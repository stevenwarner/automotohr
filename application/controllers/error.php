<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Error extends CI_Controller {
 
    function Error()
    {
        parent::Controller();
    }
 
    function index()
    {
        $this->load->view('error');
    }
}
<?php
ini_set('display_errors', true);
defined('BASEPATH') OR exit('No direct script access allowed');

class Cronsendapplicantstoremark extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('newmod_model');
    }

    public function index()
    {


        //$start_datetime = date("Y-m-d H:i:s", strtotime("-1 months"));
        $start_datetime = '2019-12-02 00:00:00';
        //$end_datetime = date("Y-m-d H:i:s");

		$end_datetime = '2019-12-30 23:59:59';

        $this->newmod_model->sendApplicantsToRemarket($start_datetime,$end_datetime);
    
    }


}
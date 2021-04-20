<?php defined('BASEPATH') or exit('No direct script access allowed');


class Cron_common extends CI_Controller{

    function __construct(){
        parent::__construct();
        $this->load->model('common_model');
    }

    function tos(){
        //
        $this->common_model->startR();
        $this->common_model->endR();
        //
    }

}
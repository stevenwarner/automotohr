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

    public function complynet()
    {
        $this->load->library('complynet');
        echo $this->complynet->getCompanies();
    }


    public function rollbacktest()
    {

       $this->db->trans_begin();
       $this->db->trans_strict(FALSE);

        $query1 = $this->insertest1();
        $query2 = $this->insertest2();

        if ($query1['status'] == 'ok' && $query2['status'] == 'ok') {
            $this->db->trans_commit();
        } else {
            $this->db->trans_rollback();
        }
    }



    public function insertest1()
    {

        $data_to_insert = array();
        $data_to_insert['name'] = 'sdsdfnv';
        $data_to_insert['status'] = 1;
        $inserted_id = $this->tm->addtesting1($data_to_insert);
        if ($inserted_id > 0) {
            return array('status' => 'ok', 'insertedid' => $inserted_id);
        } else {
            return array('status' => 'error', 'insertedid' => 0);
        }
    }

    public function insertest2()
    {
        $data_to_insert = array();
        $data_to_insert['newname'] = 'sdsdfnv ops';
        $inserted_id = $this->tm->addtestig2($data_to_insert);
        if ($inserted_id > 0) {
            return array('status' => 'ok', 'insertedid' => $inserted_id);
        } else {
            return array('status' => 'error', 'insertedid' => 0);
        }
    }
}

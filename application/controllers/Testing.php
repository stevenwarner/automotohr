<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");

    }

    function text($id)
    {
        //
        $returnArray = [];
        $returnArray['Count'] = [];
        $returnArray['Records'] = [];
        //
        $records = $this->cpp->GetRecords($id);
        //
        if(!empty($records)){
            return $returnArray;
        }
        //
        foreach($records as $record){
            //
            $returnArray['Count']++;
        }
    }

}
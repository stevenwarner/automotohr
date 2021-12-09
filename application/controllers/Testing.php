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

    function getDataTest(){
        $a = $this
            ->db
            ->select('sid, stage, is_disabled, is_ems_module')
            ->where('module_slug', 'employers')
            ->limit(1)
            ->get('modules');
        //
        $b = $a->row_array();
        if (isset($b))
        {
            echo 'value => '.$b['stage'];
            echo 'value => '.$b['is_disabled'];
            echo 'value => '.$b['is_ems_module'];
        }
        $a->free_result();

        echo "<pre>";
        print_r($b);
        echo "------------<br>";
        print_r($a);
        echo "============<br>";
        die();
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

    //
    function sendEmailNotifications($id){
        //
        $record = $this->ccp->GetReviewByIdByReviewers($id)[0];
        //
        $hf = message_header_footer($record['company_sid'], $record['CompanyName']);
        //
        if(empty($record['Reviewees'])){
            return;
        }
        //
        $template = get_email_template(REVIEW_ADDED);

        foreach($record['Reviewees'] as $row){
            //
            $replaceArray = [];
            $replaceArray['{{first_name}}'] = ucwords($row[0]['reviewer_first_name']);
            $replaceArray['{{last_name}}'] = ucwords($row[0]['reviewer_last_name']);
            $replaceArray['{{review_title}}'] = $record['review_title'];
            
            $replaceArray['{{table}}'] = $this->load->view('table', ['records' => $row, 'id' => $record['sid']], true);
            //
            $body = $hf['header'].str_replace(array_keys($replaceArray), $replaceArray, $template['text']).$hf['footer'];

            log_and_sendEmail(
                FROM_EMAIL_NOTIFICATIONS,
                $row[0]['reviewer_email'],
                $template['subject'],
                $body,
                $record['CompanyName']
            );
        }
    }

}
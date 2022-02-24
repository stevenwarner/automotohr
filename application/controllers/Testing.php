<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        $this->basePath = ROOTPATH;
        $this->i = 0;
        // Call the model
        $this->load->model("performance_management_model", "ccp");
        $this->fielsarray = array();
        $this->foldersarray = array();

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

    

    function searchfile($path = ''){
        // if($this->i ==5 2){ return; }
        $this->i++;
        $arrFiles = scandir($path ?  $path : $this->basePath, 1);
        foreach($arrFiles as $file){
            if(strpos($file, '.') === false){
                $newPath = $this->basePath.($path ? str_replace($this->basePath, '' ,$path).'/' : '').$file;
                 // folder
                $this->searchfile($newPath);
                $folder_search = 'bak';
                 if(preg_match("/{$folder_search}/i", $newPath)) {
                    array_push($this->foldersarray,$newPath);
                  }

             } else {
                    $search = '.bak';
                    if(preg_match("/{$search}/i", $file)) {
                        $backfiles= $path."/". $file;
                         array_push($this->fielsarray,$backfiles);
                      }
           
            }
         }
    

    }

 function getbackfiles(){
     $this->searchfile();
     echo "Folders <br>";
     print_r($this->foldersarray);
     echo "<br><br> Files";
     print_r($this->fielsarray);
 }

}
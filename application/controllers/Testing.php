<?php defined('BASEPATH') || exit('No direct script access allowed');

class Testing extends CI_Controller
{
    //
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("performance_management_model", "ccp");
        $this->load->model("test_model", "tm");

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

    function change_assign_datetime()
    {
        
        $records = $this->tm->getEEOCRecords();
        //
        foreach ($records as $row) {
            if (empty($row['assigned_at'])) {
                $this->tm->updateEEOCTime($row['sid'], $row['last_sent_at']);
            }
        }

        die("Process completed");
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


public function domaincheck(){

    $file = APPPATH.'../data.csv';
    
    if (($open = fopen($file, "r")) !== FALSE) 
      {
        $i=1;
     while (($data = fgetcsv($open, ",")) !== FALSE) 
        {        
              
      foreach ($data as $dat_row){
          if(!empty($dat_row)){
            
           if(!preg_match('#^default._#', $dat_row) && !preg_match('#^_#', $dat_row) && !preg_match('#^www#', $dat_row) && !preg_match('/\//', $dat_row)&& !preg_match('/\+/', $dat_row) && !preg_match('/\:/', $dat_row) ){
           
            $url = $dat_row.'.automotohr.com';  
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HEADER, true);    // we want headers
            curl_setopt($ch, CURLOPT_NOBODY, true);    // we don't need body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
            curl_setopt($ch, CURLOPT_TIMEOUT,10);
            $output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
   
         //   echo 'HTTP code: ' . $httpcode ."<br>";
          
         if($httpcode=='302'){
                 echo "Domain is Active   ".$url."<br>";
           }else{
                echo "Domain is InActive   ".$url."<br>";
             }
    
         if($i>50){die('');}
            $i++;      
      //echo $dat_row."<br>";
             }
          }
      }


    }
      fclose($open);
  }

}


}
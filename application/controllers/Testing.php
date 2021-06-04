<?php defined('BASEPATH') || exit('No direct script access allowed');

ini_set('memory_limit', -1);
set_time_limit(0);

class Testing extends CI_Controller{
    //
    public function __construct(){
        parent::__construct();
        //
        // $this->load->model('test_model', 'tm');
   }
	
    //
    function mover($date, $index){
        //
        $url = '';
        $folder = '';
        //
        switch($index){
            case "facebook":
                $url = 'https://www.automotohr.com/Facebook_feed/jobXmlCallback';
                $folder = '/home/automotohr/applicant/Facebook/';
                break;
            case "autocareers":
                $url = 'https://www.automotohr.com/Auto_careers/index';
                $folder = '/home/automotohr/applicant/autocareers/';
                break;
            case "indeed":
                $url = 'https://www.automotohr.com/indeed_feed/indeedPostUrl';
                $folder = '/home/automotohr/applicant/indeed/';
                break;
            case "zip":
                $url = 'https://www.automotohr.com/Zip_recruiter_organic/zipPostUrl';
                $folder = '/home/automotohr/applicant/zipRecruter/';
                break;
        }
        //
        $path = $folder."*_{$date}_*.json";
        //
        $files = glob($path, GLOB_BRACE);
        //
        foreach ($files as $file) {
            $fileData = file_get_contents($file);
            //
            $curl = curl_init();

            curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $fileData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Cookie: ci_session=0b21abda66d70e809f46b6b6ea3bc6a9af0aa57b'
            ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            echo $response;
            //
            usleep(300);

        }
        echo "All done";
        exit(0);
    }


    //
    function eeoc_fixer(){
        //
        $eeoc_forms = 
        $this->db
        ->select('sid, portal_applicant_jobs_list_sid, is_latest')
        ->where('users_type', '')
        ->get('portal_eeo_form')
        ->result_array();

        //
        $s = $f = 0;
        $fa = [];
        //
        if(!empty($eeoc_forms)){
            foreach($eeoc_forms as $form){
                // Get applicant id
                $applicantId = $this->db
                ->select('portal_job_applications_sid')
                ->where('sid', $form['portal_applicant_jobs_list_sid'])
                ->get('portal_applicant_jobs_list')
                ->row_array()['portal_job_applications_sid'];
                //
                if(empty($applicantId)){
                    $f++;
                    $fa[] = $form;
                    continue;
                }
                //
                $s++;
                //
                $type = 'applicant';
                //
                if(
                    $this->db
                    ->where('applicant_sid', $applicantId)
                    ->count_all_results('users')
                ){
                    $type = 'employee';
                }
                //
                if($form['is_latest'] == 0){
                    $type = 'applicant';
                }
                //
                $this->db->where('sid', $form['sid'])
                ->update('portal_eeo_form', ['users_type' => $type]);
            }
        }

        //
        _e('Success: '. $s, true);
        _e('Failed: '. $f, true);
        _e($fa, true);
    }
}

<?php defined('BASEPATH') || exit('No direct script access allowed');

ini_set('memory_limit', -1);
set_time_limit(0);

class Testing extends CI_Controller{
    //
    public function __construct(){
        parent::__construct();
        //
        $this->load->model('test_model', 'tm');
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
}

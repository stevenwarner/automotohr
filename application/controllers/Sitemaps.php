<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sitemaps extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Sitemap_model', 'sitemap_model');
    }


    /**
     * 
     */
    function CreateCompanySitemaps(){
        //
        $xmlArray = [];
        //
        $CompaniesWithJobs = $this->sitemap_model->GetCompaniesWithJobs();
        //
        foreach($CompaniesWithJobs as $job){
            // Check for approved job
            if($job['has_job_approval_rights'] && $job['approval_status'] != 'approved'){
                continue;
            }
            //
            if(!isset($xmlArray[$job['user_sid']])){
                $xmlArray[$job['user_sid']] = [
                    'XML_NAME' => 'sitemap_'.preg_replace('/[^a-zA-Z]/','_',(str_replace('.automotohr.com', '', $job['sub_domain']))).'.xml',
                    'XML' => []
                ];
            }
            //

            // Reset title
            $job['Title'] = job_title_uri($job, false, true);
            //
            $row = "<url>";
            $row .= "\r\n\t<loc>https://".( $job['sub_domain'] )."/job_details/".(stringToSlug($job['Title']))."-".($job['sid'])."</loc>\r\n";
            $row .= "</url>\r\n";
            //
            $xmlArray[$job['user_sid']]['XML'][] = $row;
        }

        //
        foreach($xmlArray as $xml){
            //
            $sitePath = 'https://www.automotohr.com/sitemaps/';
            //
            $path = APPPATH.'../sitemaps/';
            //
            if(!is_dir($path)){
                mkdir($path, DIR_WRITE_MODE, true);
            }
            //
            $file = $path.$xml['XML_NAME'];
            //
            $fileContent = "<?xml version='1.0' encoding='UTF-8'?>\r\n";
            $fileContent .= '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\r\n";
            $fileContent .= implode("\r\n", $xml['XML']);
            $fileContent .= '</urlset>';
            //
            $handler = fopen($file, 'w');
            fwrite($handler, $fileContent);
            fclose($handler);
            // Send it to Google
            $submit_to_google = getFileData("https://www.google.com/ping?sitemap={$sitePath}${xml['XML_NAME']}");
            mail(TO_EMAIL_DEV, 'Google Hire Cron Job For Sub domains at '.date('Y-m-d H:i:s').'', print_r($xml, true));
            //
            usleep(800);
            
            // echo $submit_to_google;
            echo $xml['XML_NAME'].'<br/>';
        }
    }
   
}
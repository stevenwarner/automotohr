<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Career_feed extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
    }

    public function index() {
        $sid = $this->isActiveFeed();
        $jobData = $this->all_feed_model->get_all_company_jobs_career_builder();
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies($sid);

        $rows='';
        if (count($jobData) > 0) {
           // $productData = $this->all_feed_model->get_product_data($jobData[0]['product_sid']);
            foreach ($jobData as $job) {
               
                if (in_array($job['user_sid'], $activeCompaniesArray)) {
                
                    $company_id = $job['user_sid'];
                    $companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                    $companydata = $this->all_feed_model->get_company_name_and_job_approval($company_id);
                    $companyName = $companydata['CompanyName'];
                    $has_job_approval_rights = $companydata['has_job_approval_rights'];

                    if($has_job_approval_rights ==  1) {
                        $approval_right_status = $job['approval_status'];

                        if($approval_right_status != 'approved') {
                            continue;
                        }
                    }

                     $jobDesc ="Job Description:".'<br /><br />'.strip_tags($job['JobDescription']);
                     
                    if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                        $jobDesc .= '<br><br>Job Requirements:<br><br>' . strip_tags($job['JobRequirements'], '<br><br>');
                    } else {
                        $jobDesc = strip_tags($job['JobDescription'], '<br>');
                    }

                    if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                        $country = db_get_country_name($job['Location_Country']);
                    } else {
                        $country['country_code'] = "US";
                    }

                    if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                        $state = db_get_state_name($job['Location_State']);
                    } else {
                        $state['state_name'] = "";
                    }

                    if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                        $city = $job['Location_City'];
                    } else {
                        $city = "";
                    }

                    if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                        $zipcode = $job['Location_ZipCode'];
                    } else {
                        $zipcode = "";
                    }

                    $JobCategorys = $job['JobCategory'];

                    if ($JobCategorys != null) {
                        $cat_id = explode(',', $JobCategorys);
                        $job_category_array = array();
                        foreach ($cat_id as $id) {
                            $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                            $job_category_array[] = $job_cat_name[0]['value'];
                        }
                        $job_category = implode(', ', $job_category_array);
                    }

                    $rows.="
                    <job>
                        <title><![CDATA[" . db_get_job_title($company_id, $job['Title'], false) . "]]></title>
                        <date><![CDATA[" . date_with_time($job['activation_date']) . " PST]]></date>
                        <job_id><![CDATA[" . $job['sid'] . "]]></job_id>
                        <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $job['sid'] . "]]></url>
                        <city><![CDATA[" . $city . "]]></city>
                        <state><![CDATA[" . $state['state_name'] . "]]></state>
                        <country><![CDATA[" . $country['country_code'] . "]]></country>
                        <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                        <category><![CDATA[" . $job_category . "]]></category>
                        <employer_name><![CDATA[" . $companyName . "]]></employer_name>
                        <writeup><![CDATA[" . $jobDesc . "]]></writeup>
                    </job>";
                }
            }
        }
        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        $det = '';
        $det .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        // echo '<xml>';
        $det .=  "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";
        $det .= trim($rows);
        $det .=  '</source>
        ';
        echo trim($det);
        exit;
    }

    private function isActiveFeed(){
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('career_builder_paid');
        if(!$validSlug){
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}

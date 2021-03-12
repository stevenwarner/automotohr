<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Career_feed_organic extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index() {

        $sid = $this->isActiveFeed();
        $rows = '';
        $purchasedJobs = $this->all_feed_model->get_all_company_jobs_career_builder();
        $i = 0;
        $featuredArray[$i] = "";

mail(TO_EMAIL_DEV, 'Feed XML - Career: ' . date('Y-m-d H:i:s'), 'Pinged');

        foreach ($purchasedJobs as $purchased) {
            $featuredArray[$i] = $purchased['sid'];
            $i++;
        }

        $jobData = $this->all_feed_model->get_all_company_jobs_indeed_organic($featuredArray);
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies($sid);


        foreach ($jobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                $companydata = $this->all_feed_model->get_company_name_and_job_approval($company_id);
                if(empty($companyPortal)) continue;
                $companyName = $companydata['CompanyName'];
                $has_job_approval_rights = $companydata['has_job_approval_rights'];

                if($has_job_approval_rights ==  1) {
                    $approval_right_status = $job['approval_status'];

                    if($approval_right_status != 'approved') {
                        continue;
                    }
                }

                $uid = $job['sid'];
                $publish_date = $job['activation_date'];
                $feed_data = $this->all_feed_model->fetch_uid_from_job_sid($uid);

                if(!empty($feed_data)){
                    $uid = $feed_data['uid'];
                    $publish_date = $feed_data['publish_date'];
                }

                // if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                //     $jobDesc = strip_tags($job['JobDescription'], '<br>') . '
                //
                //     '.'<br><br>Job Requirements:<br>' . strip_tags($job['JobRequirements'], '<br>');
                // } else {
                //     $jobDesc = strip_tags($job['JobDescription'], '<br>');
                // }

                $jobDesc = '<br><br>Job Description:<br><br>'.$job['JobDescription'];
                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                        $jobDesc .= '<br><br>Job Requirements:<br><br>'.strip_tags($job['JobRequirements'], '<br><br>');
                    } else {
                        $jobDesc ='<br><br>Job Description:<br><br>'.strip_tags($job['JobDescription'],'<br>');
                    }
               
                $jobDesc = str_replace(['</p>','<br>','<br />','</li>','</strong>'], "\n", $jobDesc);
                $jobDesc = strip_tags($jobDesc, '<br>');

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

                $rows .=  "
                    <job>
                    <title><![CDATA[" . db_get_job_title($company_id, $job['Title'], false). "]]></title>
                    <date><![CDATA[" . date_with_time($publish_date) . " PST]]></date>
                    <job_id><![CDATA[" . $uid . "]]></job_id>
                    <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></url>
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
        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');

        $det = '';
        $det .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        $det .=  "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";
        $det .=  trim($rows);
        $det .=  '</source>';
        echo trim($det);
        @mail('mubashir.saleemi123@gmail.com', 'Career Feed - HIT on ' . date('Y-m-d H:i:s') . '', count($rows));
        exit;
    }

    private function isActiveFeed(){
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('career_builder_organic');
        if(!$validSlug){
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}
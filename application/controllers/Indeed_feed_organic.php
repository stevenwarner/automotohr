<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '50M');
class Indeed_feed_organic extends CI_Controller {

    private $debug_email = TO_EMAIL_DEV;

    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }
    /**
 * 
 */
private function addLastRead($sid){
    $this->db
    ->where('sid', $sid)
    ->set([
        'last_read' => date('Y-m-d H:i:s', strtotime('now')),
        'referral' => !empty($_SERVER['HTTP_REFERER']) ?  $_SERVER['HTTP_REFERER'] : ''
    ])->update('job_feeds_management');
    //
    $this->db
    ->insert('job_feeds_management_history', [
        'feed_id' => $sid,
        'referral' => !empty($_SERVER['HTTP_REFERER']) ?  $_SERVER['HTTP_REFERER'] : '',
        'created_at' => date('Y-m-d H:i:s', strtotime('now'))
    ]);
}

    public function index() {
        $sid = $this->isActiveFeed();
        $this->addLastRead(3);
        $purchasedJobs = $this->all_feed_model->get_all_company_jobs_indeed();
        $i = 0;
        $featuredArray[$i] = "";

        foreach ($purchasedJobs as $purchased) {
            $featuredArray[$i] = $purchased['sid'];
            $i++;
        }

        $jobData = $this->all_feed_model->get_all_company_jobs_indeed_organic($featuredArray);
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies($sid);

        $rows = '';

        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        $rows .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

        // echo '<xml>';
        $rows .=  "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";
        $xmlFeed = 0;
        $listedJobArray = array();

        foreach ($jobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->all_feed_model->get_portal_detail($company_id);

                if(empty($companyPortal)) {
                    continue;
                }

                $companydata = $this->all_feed_model->get_company_name_and_job_approval($company_id);
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

                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDesc ='<br><br>Job Description:<br><br>'.strip_tags($job['JobDescription'], '<br><br>').'<br><br>Job Requirements:<br><br>' . strip_tags($job['JobRequirements'], '<br>');
                } else {
                    $jobDesc = strip_tags($job['JobDescription'], '<br><br>');
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

                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary = $job['Salary'];
                } else {
                    $salary = "";
                }

                if (isset($job['SalaryType']) && $job['SalaryType'] != NULL) {
                    if ($job['SalaryType'] == 'per_hour') {
                        $jobType = "Per Hour";
                    } elseif ($job['SalaryType'] == 'per_week') {
                        $jobType = "Per Week";
                    } elseif ($job['SalaryType'] == 'per_month') {
                        $jobType = "Per Month   ";
                    } elseif ($job['SalaryType'] == 'per_year') {
                        $jobType = "Per Year";
                    } else {
                        $jobType = "";
                    }
                } else {
                    $jobType = "";
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

                $xmlFeed++;
                $listedJobArray[] = array(
                    'JobTitle' => $job['Title'],
                    'CompanySid' => $company_id,
                    'PublishDate' => $publish_date,
                    'JobUID' => $uid,
                    'CompanySubDomain' => $companyPortal['sub_domain']
                );

                $rows .=  "
                    <job>
                    <title><![CDATA[" . db_get_job_title($company_id, $job['Title'], false) . "]]></title>
                    <date><![CDATA[" . date_with_time($publish_date) . " PST]]></date>
                    <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                    <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></url>
                    <company><![CDATA[" . $companyName . "]]></company>
                    <city><![CDATA[" . $city . "]]></city>
                    <state><![CDATA[" . $state['state_name'] . "]]></state>
                    <country><![CDATA[" . $country['country_code'] . "]]></country>
                    <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                    <salary><![CDATA[" . $salary . "]]></salary>
                    <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                    <category><![CDATA[" . $job_category . "]]></category>
                    <description><![CDATA[" . $jobDesc . "]]></description>
                    <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid) . "&indeed-apply-jobid=" . $uid . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "/indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required&indeed-apply-allow-apply-on-indeed=1&indeed-apply-questions=".urlencode(base_url('jobs/'.$job['sid'].'/questionnaire.json'))."]]></indeed-apply-data>
                    </job>";
            }
        }


        $rows .=  '</source>
        ';
        echo trim($rows);
        mail(TO_EMAIL_DEV, 'Indeed hit XML: ' . date('Y-m-d H:i:s'), print_r($listedJobArray, true));

        @mail('mubashir.saleemi123@gmail.com', 'Indeed Feed - HIT on ' . date('Y-m-d H:i:s') . '', count($listedJobArray));
        exit;
    }

    private function isActiveFeed(){
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('indeed_organic');
        if(!$validSlug){
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }
}

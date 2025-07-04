<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('memory_limit', '50M');
class Juju_feed extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index() {
        $jobData = $this->all_feed_model->get_all_company_jobs();
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies();

        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        echo '<positionfeed
            xmlns="http://www.juju.com/add-jobs/positionfeed-namespace/"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.juju.com/add-jobs/positionfeed-namespace/ http://www.juju.com/add-jobs/positionfeed.xsd"
            version="2006-04">
            <source>' . STORE_NAME . '</source>
            <sourceurl>' . STORE_FULL_URL_SSL . '</sourceurl>
            <feeddate></feeddate>';

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
                
                $uid = $job['sid'];
                $publish_date = $job['activation_date'];
                $feed_data = $this->all_feed_model->fetch_uid_from_job_sid($uid);
                
                if(!empty($feed_data)){
                    $uid = $feed_data['uid'];
                    $publish_date = $feed_data['publish_date'];
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

            echo "
            <job id='" . $uid . "'>
            <employer><![CDATA[" . $companyName . "]]></employer>
            <title><![CDATA[" . db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code']) . "]]></title>
            <description><![CDATA[" . strip_tags($job['JobDescription'], '<br>') . "]]></description>
            <postingdate><![CDATA[" . date_with_time($publish_date) . "]]></postingdate>
            <joburl><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></joburl>
            <location>
            <city><![CDATA[" . $city . "]]></city>
            <state><![CDATA[" . $state['state_name'] . "]]></state>
            <nation><![CDATA[" . $country['country_code'] . "]]></nation>
            </location>    
            <salary><![CDATA[" . $salary . "]]></salary>
            <type><![CDATA[" . $jobType . "]]></type>
            <category><![CDATA[" . $job_category . "]]></category>
            </job>";
            }
        }
        echo '</positionfeed>';
        exit;
    }
}
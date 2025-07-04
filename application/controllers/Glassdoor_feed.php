<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Glassdoor_feed extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
    }

    public function index(){
        $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
        $activeCompaniesArray = $this->all_feed_model->get_all_active_companies();

        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        echo '<jobs>';

        $i = 0;
        $featuredArray[$i] = "";
        foreach ($featuredJobs as $featuredId) {
            $featuredArray[$i] = $featuredId['jobId'];
            $i++;
        }
        
        $organicJobData = $this->all_feed_model->get_all_organic_jobs($featuredArray);
        $featured = 0;
        $expiryDate = "";


        foreach($organicJobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                //$companyName = $this->all_feed_model->get_company_name_by_id($company_id);
                $companyDetail = $this->all_feed_model->get_company_detail($company_id);
                $companyName = $companyDetail['CompanyName'];
                $has_job_approval_rights = $companyDetail['has_job_approval_rights'];
                $companyLogo = $companyDetail['Logo'];
                $companyContactName = $companyDetail['ContactName'];
                $companyUserName = strtolower(str_replace(" ", "", $companyName));
                $uid = $job['sid'];
                $publish_date = $job['activation_date'];
                
                if($has_job_approval_rights ==  1) {
                    $approval_right_status = $job['approval_status'];
                    
                    if($approval_right_status != 'approved') {
                        continue;
                    }
                }
                
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
                    //$job_category = str_replace('"', "'", strip_tags($job_category, '<br>'));
                    //$job_category = htmlspecialchars($job_category);
                    $job_category = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$job_category));
                }
                
                $jobDescription = str_replace('"', "'", strip_tags($job['JobDescription'], '<br>'));
                $jobDescription = htmlspecialchars($jobDescription);
                $jobDescription = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$jobDescription));

                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobRequirements = str_replace('"', "'", strip_tags($job['JobRequirements'], '<br>'));
                    $jobRequirements = htmlspecialchars($jobRequirements);
                    $jobRequirements = strip_tags(preg_replace("/&(?!#?[a-z0-9]+;)/", "&amp;",$jobRequirements));
                } else {
                    $jobRequirements = "";
                }
                
                if (!empty($companyDetail['YouTubeVideo'])) {
                    $companyYoutube = "https://www.youtube.com/watch?v=" . $companyDetail['YouTubeVideo'];
                } else {
                    $companyYoutube = $companyDetail['YouTubeVideo'];
                }
                
                if (!empty($job['YouTube_Video'])) {
                    $job['YouTube_Video'] = "https://www.youtube.com/watch?v=" . $job['YouTube_Video'];
                }

                $job_url = STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . '/job_details/' . $uid;

                echo '<job>';
                echo '<guid isPermaLink="false"><![CDATA[' . $uid .  ']]></guid>';
                echo '<title><![CDATA[' . $job['Title'] . ']]></title>';
                echo '<description><![CDATA[' . $jobDescription . ']]></description> ';
                echo '<url><![CDATA[' . $job_url . ']]></url>';
                echo '<salary><![CDATA[' . $salary . ']]></salary>';
                echo '<employer><![CDATA[' . $companyName . ']]></employer>';
                echo '<city><![CDATA[' . $city . ']]></city>';
                echo '<state><![CDATA[' . $state['state_name'] . ']]></state>';
                echo '<country><![CDATA[' . $country['country_code'] . ']]></country>';
                echo '<zipcode><![CDATA[' . $zipcode . ']]></zipcode>';
                echo '<category>' . $job_category . '</category>';
                echo '<post_date>' . $publish_date . '</post_date>';
                echo '<expiration_date>' . $expiryDate . '</expiration_date>';
                echo '</job>';
            }
        }
        echo '</jobs>';
    }
}
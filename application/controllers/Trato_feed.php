<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Trato_feed extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($generate_file = 0) {
        $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
//        echo '<pre>';
//        print_r($featuredJobs);
        //$jobData = $this->all_feed_model->get_all_company_jobs();
        //$activeCompaniesArray = $this->all_feed_model->get_all_active_companies();
        $jobData = $this->all_feed_model->get_all_active_company_jobs();
        
//        echo $this->db->last_query();
//        print_r($jobData);
//        exit;
        $xml_data = '';
        $xml_data .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        $xml_data .= '<xml>';
        if (!empty($featuredJobs)) {
           // if (in_array($job['user_sid'], $activeCompaniesArray)) {
                foreach ($featuredJobs as $featuredId) {
                    //if ($featuredId['jobId'] == $job['sid']) {
                        $sid = $featuredId['jobId'];
                        $job = $this->all_feed_model->get_job_details($sid);
                        $company_id = $job['user_sid'];
                        //$companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                        //$companyName = $this->all_feed_model->get_company_name_by_id($company_id);
                        //$companyDetail = $this->all_feed_model->get_company_detail($company_id);
                        $companyName = $job['CompanyName'];
                        $has_job_approval_rights = $job['has_job_approval_rights'];
                        $companyLogo = $job['Logo'];
                        $companyContactName = $job['ContactName'];
                        $companyYoutube = $job['YouTubeVideo'];
                        $companyUserName = strtolower(str_replace(" ", "", $companyName));
                        
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
                            if($job['Location_Country'] == 38 || $job['Location_Country'] == '38') {
                                $country['country_code'] = 'CA';
                            }
                            
                            if($job['Location_Country'] == 227 || $job['Location_Country'] == '227') {
                                $country['country_code'] = 'US';
                            }
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

                        $featured = 1;
                        
                        if (isset($featuredId['expiry_date'])) {
                            $expiryDate = my_date_format($featuredId['expiry_date']) . " PST";
                        }

                        $jobDescription = str_replace('"', "'", strip_tags($job['JobDescription'], '<br>'));

                        if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                            $jobRequirements = str_replace('"', "'", strip_tags($job['JobRequirements'], '<br>'));
                        } else {
                            $jobRequirements = "";
                        }

                        if (!empty($job['YouTubeVideo'])) {
                            $companyYoutube = "https://www.youtube.com/watch?v=" . $job['YouTubeVideo'];
                        } else {
                            $companyYoutube = $job['YouTubeVideo'];
                        }
                        
                        if (!empty($job['YouTube_Video'])) {
                            $job['YouTube_Video'] = "https://www.youtube.com/watch?v=" . $job['YouTube_Video'];
                        }
                        
                    if($job['job_title_location'] == 1) {
                        $portal_job_title = $job['Title'] . '  - ' . ucfirst($city) . ', ' . $state['state_name'] . ', ' . $country['country_code'];
                    } else {
                        $portal_job_title = $job['Title'];
                    }
                    

                    $xml_data .= "<job>
                    <title><![CDATA[" . $portal_job_title . "]]></title>
                    <publish_date><![CDATA[" . date_with_time($publish_date) . " PST]]></publish_date>
                    <expiry_date><![CDATA[" . $expiryDate . "]]></expiry_date>
                    <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                    <url><![CDATA[" . STORE_PROTOCOL_SSL . $job['sub_domain'] . "/job_details/" . $uid . "]]></url>
                    <company><![CDATA[" . $companyName . "]]></company>
                    <username><![CDATA[" . $companyUserName . "]]></username>
                    <city><![CDATA[" . $city . "]]></city>
                    <state><![CDATA[" . $state['state_name'] . "]]></state>
                    <country><![CDATA[" . $country['country_code'] . "]]></country>
                    <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                    <salary><![CDATA[" . $salary . "]]></salary>
                    <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                    <AHR_featured><![CDATA[" . $featured . "]]></AHR_featured>
                    <AHR_priority><![CDATA[1]]></AHR_priority>
                    <category><![CDATA[" . $job_category . "]]></category>
                    <description><![CDATA[" . $jobDescription . "]]></description>
                    <jobRequirements><![CDATA[" . $jobRequirements . "]]></jobRequirements>
                    <youtube_video><![CDATA[" . $job['YouTube_Video'] . "]]></youtube_video>
                    <company_logo><![CDATA[" . AWS_S3_BUCKET_URL . $companyLogo . "]]></company_logo>
                    <apply_url><![CDATA[" . STORE_PROTOCOL_SSL . $job['sub_domain'] . "/job_details/" . $uid . "]]></apply_url>
                    <company_url><![CDATA[" . STORE_PROTOCOL_SSL . $job['sub_domain'] . "]]></company_url>
                    <contact_name><![CDATA[" . $companyContactName . "]]></contact_name>
                    <company_youtube_video><![CDATA[" . $companyYoutube . "]]></company_youtube_video>
                    </job>";
                    //} // this is the check
                }
           // } // active company check
        }

        $i = 0;
        $featuredArray[$i] = "";
        
        foreach ($featuredJobs as $featuredId) {
            $featuredArray[$i] = $featuredId['jobId'];
            $i++;
        }
        
        $organicJobData = $this->all_feed_model->get_all_active_company_organic_jobs($featuredArray);
        $featured = 1;
        $expiryDate = "";

        foreach ($organicJobData as $job) {
           // if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                //$companyPortal = $this->all_feed_model->get_portal_detail($company_id);
                //$companyName = $this->all_feed_model->get_company_name_by_id($company_id);
                //$companyDetail = $this->all_feed_model->get_company_detail($company_id);
                $companyName = $job['CompanyName'];
                $has_job_approval_rights = $job['has_job_approval_rights'];
                $companyLogo = $job['Logo'];
                $companyContactName = $job['ContactName'];
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
                    if($job['Location_Country'] == 38 || $job['Location_Country'] == '38') {
                        $country['country_code'] = 'CA';
                    }

                    if($job['Location_Country'] == 227 || $job['Location_Country'] == '227') {
                        $country['country_code'] = 'US';
                    }
                } else {
                    $country['country_code'] = "";
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
                
                $jobDescription = str_replace('"', "'", strip_tags($job['JobDescription'], '<br>'));

                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobRequirements = str_replace('"', "'", strip_tags($job['JobRequirements'], '<br>'));
                } else {
                    $jobRequirements = "";
                }
                
                if (!empty($job['YouTubeVideo'])) {
                    $companyYoutube = "https://www.youtube.com/watch?v=" . $job['YouTubeVideo'];
                } else {
                    $companyYoutube = $job['YouTubeVideo'];
                }
                
                if (!empty($job['YouTube_Video'])) {
                    $job['YouTube_Video'] = "https://www.youtube.com/watch?v=" . $job['YouTube_Video'];
                }
                
                if($job['job_title_location'] == 1) {
                    $portal_job_title = $job['Title'] . '  - ' . ucfirst($city) . ', ' . $state['state_name'] . ', ' . $country['country_code'];
                } else {
                    $portal_job_title = $job['Title'];
                }
                    
                $xml_data .= "
                    <job>
                    <title><![CDATA[" . $portal_job_title . "]]></title>
                    <publish_date><![CDATA[" . date_with_time($publish_date) . " PST]]></publish_date>
                    <expiry_date><![CDATA[" . $expiryDate . "]]></expiry_date>
                    <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                    <url><![CDATA[" . STORE_PROTOCOL_SSL . $job['sub_domain'] . "/job_details/" . $uid . "]]></url>
                    <company><![CDATA[" . $companyName . "]]></company>
                    <username><![CDATA[" . $companyUserName . "]]></username>
                    <city><![CDATA[" . $city . "]]></city>
                    <state><![CDATA[" . $state['state_name'] . "]]></state>
                    <country><![CDATA[" . $country['country_code'] . "]]></country>
                    <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                    <salary><![CDATA[" . $salary . "]]></salary>
                    <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                    <AHR_featured><![CDATA[" . $featured . "]]></AHR_featured>
                    <AHR_priority><![CDATA[" . $featured . "]]></AHR_priority>
                    <category><![CDATA[" . $job_category . "]]></category>
                    <description><![CDATA[" . $jobDescription . "]]></description>
                    <jobRequirements><![CDATA[" . $jobRequirements . "]]></jobRequirements>
                    <youtube_video><![CDATA[" . $job['YouTube_Video'] . "]]></youtube_video>
                    <company_logo><![CDATA[" . AWS_S3_BUCKET_URL . $companyLogo . "]]></company_logo>
                    <apply_url><![CDATA[" . STORE_PROTOCOL_SSL . $job['sub_domain'] . "/job_details/" . $uid . "]]></apply_url>
                    <company_url><![CDATA[" . STORE_PROTOCOL_SSL . $job['sub_domain'] . "]]></company_url>
                    <contact_name><![CDATA[" . $companyContactName . "]]></contact_name>
                    <company_youtube_video><![CDATA[" . $companyYoutube . "]]></company_youtube_video>
                    </job>";
           // } //active company 
        }
        
        $xml_data .= '</xml>';

        if($generate_file == 1) {
            file_put_contents('ams_feed.xml', $xml_data);
        } else {
            header('Content-type: text/xml');
            header('Pragma: public');
            header('Cache-control: private');
            header('Expires: -1');
            echo $xml_data;
        }
        exit;
    }
}
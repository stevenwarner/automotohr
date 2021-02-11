<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Ams_feed extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    public function index($generate_file = 0) {
        $feed_data                                                              = $this->all_feed_model->fetch_uid_from_job_sid(5120);
        $featuredJobs                                                           = $this->all_feed_model->get_all_company_jobs_ams();
        $jobData                                                                = $this->all_feed_model->get_all_company_jobs();
        $activeCompaniesArray                                                   = $this->all_feed_model->get_all_active_companies();
        $paid_feed                                                              = array();
        $organic_today                                                          = array();
        $organic_week                                                           = array();
        $organic_month                                                          = array();
        
        foreach ($jobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                foreach ($featuredJobs as $featuredId) {
                    if ($featuredId['jobId'] == $job['sid']) {
                        $company_id                                             = $job['user_sid'];
                        $companyPortal                                          = $this->all_feed_model->get_portal_detail($company_id);
                        
                        if(empty($companyPortal)) {
                            continue;
                        }
                        
                        $companyDetail                                          = $this->all_feed_model->get_company_detail($company_id);
                        $companyName                                            = $companyDetail['CompanyName'];
                        $has_job_approval_rights                                = $companyDetail['has_job_approval_rights'];
                        $companyLogo                                            = $companyDetail['Logo'];
                        $companyContactName                                     = $companyDetail['ContactName'];
                        $companyYoutube                                         = $companyDetail['YouTubeVideo'];
                        $companyUserName                                        = strtolower(str_replace(' ', '', $companyName));
                        
                        if($has_job_approval_rights ==  1) {
                            $approval_right_status                              = $job['approval_status'];

                            if($approval_right_status != 'approved') {
                                continue;
                            }
                        }
                        
                        $uid                                                    = $job['sid'];
                        $publish_date                                           = $job['activation_date'];
                        $feed_data                                              = $this->all_feed_model->fetch_uid_from_job_sid($uid);

                        if(!empty($feed_data)){
                            $uid                                                = $feed_data['uid'];
                            $publish_date                                       = $feed_data['publish_date'];
                        }

                        if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                            $country                                            = db_get_country_name($job['Location_Country']);
                        } else {
                            $country['country_code']                            = 'US';
                        }

                        if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                            $state                                              = db_get_state_name($job['Location_State']);
                        } else {
                            $state['state_name']                                = '';
                        }

                        if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                            $city                                               = $job['Location_City'];
                        } else {
                            $city                                               = '';
                        }
                        
                        if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                            $zipcode                                            = $job['Location_ZipCode'];
                        } else {
                            $zipcode                                            = '';
                        }
                        
                        if (isset($job['Salary']) && $job['Salary'] != NULL) {
                            $salary                                             = $job['Salary'];
                        } else {
                            $salary                                             = '';
                        }
                        
                        if (isset($job['SalaryType']) && $job['SalaryType'] != NULL) {
                            if ($job['SalaryType'] == 'per_hour') {
                                $jobType                                        = 'Per Hour';
                            } elseif ($job['SalaryType'] == 'per_week') {
                                $jobType                                        = 'Per Week';
                            } elseif ($job['SalaryType'] == 'per_month') {
                                $jobType                                        = 'Per Month';
                            } elseif ($job['SalaryType'] == 'per_year') {
                                $jobType                                        = 'Per Year';
                            }
                        } else {
                            $jobType = '';
                        }
                        
                        $JobCategorys                                           = $job['JobCategory'];
                        
                        if ($JobCategorys != null) {
                            $cat_id                                             = explode(',', $JobCategorys);
                            $job_category_array                                 = array();
                            
                            foreach ($cat_id as $id) {
                                $job_cat_name                                   = $this->all_feed_model->get_job_category_name_by_id($id);
                        
                                if(!empty($job_cat_name)) {
                                    $job_category_array[]                       = $job_cat_name[0]['value'];
                                }
                            }
                            
                            $job_category                                       = implode(', ', $job_category_array);
                        }

                        $featured                                               = 1;
                        
                        if (isset($featuredId['expiry_date'])) {
                            $expiryDate                                         = my_date_format($featuredId['expiry_date']) . ' PST';
                        }

                        $jobDescription                                         = str_replace('"', "'", strip_tags($job['JobDescription'], '<br>'));

                        if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                            $jobRequirements                                    = str_replace('"', "'", strip_tags($job['JobRequirements'], '<br>'));
                        } else {
                            $jobRequirements                                    = '';
                        }

                        if (!empty($companyDetail['YouTubeVideo'])) {
                            $companyYoutube                                     = "https://www.youtube.com/watch?v=" . $companyDetail['YouTubeVideo'];
                        } else {
                            $companyYoutube                                     = $companyDetail['YouTubeVideo'];
                        }
                        
                        if (!empty($job['YouTube_Video'])) {
                            $job['YouTube_Video']                               = "https://www.youtube.com/watch?v=" . $job['YouTube_Video'];
                        }
                        
                        $paid_feed[] = array('title' => db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code']),
                                            'publish_date' => date_with_time($publish_date) . ' PST',
                                            'expiry_date' => $expiryDate,
                                            'referencenumber' => $uid,
                                            'url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                                            'company' => $companyName,
                                            'username' => $companyUserName,
                                            'city' => $city,
                                            'state' => $state['state_name'],
                                            'country' => $country['country_code'],
                                            'postalcode' => $zipcode,
                                            'salary' => $salary,
                                            'jobtype' => $jobType,
                                            'AHR_featured' => $featured,
                                            'AHR_priority' => 1,
                                            'category' => $job_category,
                                            'description' => $jobDescription,
                                            'jobRequirements' => $jobRequirements,
                                            'youtube_video' => $job['YouTube_Video'],
                                            'company_logo' => AWS_S3_BUCKET_URL . $companyLogo,
                                            'apply_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                                            'company_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'],
                                            'contact_name' => $companyContactName,
                                            'company_youtube_video' => $companyYoutube);
                    }
                } // foreach loop end
            } 
        }

        $i                                                                      = 0;
        $featuredArray[$i]                                                      = '';
        
        foreach ($featuredJobs as $featuredId) {
            $featuredArray[$i]                                                  = $featuredId['jobId'];
            $i++;
        }
        
        $organicJobData                                                         = $this->all_feed_model->get_all_organic_jobs($featuredArray);
        $featured                                                               = 1;
        $expiryDate                                                             = '';
        $today_start                                                            = date('Y-m-d 00:00:01');
        $today_end                                                              = date('Y-m-d 23:59:59');
        $week_start                                                             = date('Y-m-d 00:00:01', strtotime('-7 days'));

        foreach ($organicJobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id                                                     = $job['user_sid'];
                $companyPortal                                                  = $this->all_feed_model->get_portal_detail($company_id);
                
                if(empty($companyPortal)) {
                    continue;
                }
                        
                $companyDetail                                                  = $this->all_feed_model->get_company_detail($company_id);
                $companyName                                                    = $companyDetail['CompanyName'];
                $has_job_approval_rights                                        = $companyDetail['has_job_approval_rights'];
                $companyLogo                                                    = $companyDetail['Logo'];
                $companyContactName                                             = $companyDetail['ContactName'];
                $companyUserName                                                = strtolower(str_replace(' ', '', $companyName));
                $uid                                                            = $job['sid'];
                $publish_date                                                   = $job['activation_date'];
                
                if($has_job_approval_rights ==  1) {
                    $approval_right_status                                      = $job['approval_status'];
                    
                    if($approval_right_status != 'approved') {
                        continue;
                    }
                }
                
                $feed_data                                                      = $this->all_feed_model->fetch_uid_from_job_sid($uid);
                
                if(!empty($feed_data)){
                    $uid                                                        = $feed_data['uid'];
                    $publish_date                                               = $feed_data['publish_date'];
                }

                if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                    $country                                                    = db_get_country_name($job['Location_Country']);
                } else {
                    $country['country_code']                                    = 'US';
                }

                if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                    $state                                                      = db_get_state_name($job['Location_State']);
                } else {
                    $state['state_name']                                        = '';
                }

                if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                    $city                                                       = $job['Location_City'];
                } else {
                    $city                                                       = '';
                }
                
                if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                    $zipcode                                                    = $job['Location_ZipCode'];
                } else {
                    $zipcode                                                    = '';
                }
                
                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary                                                     = $job['Salary'];
                } else {
                    $salary                                                     = '';
                }
                
                if (isset($job['SalaryType']) && $job['SalaryType'] != NULL) {
                    if ($job['SalaryType'] == 'per_hour') {
                        $jobType                                                = 'Per Hour';
                    } elseif ($job['SalaryType'] == 'per_week') {
                        $jobType                                                = 'Per Week';
                    } elseif ($job['SalaryType'] == 'per_month') {
                        $jobType                                                = 'Per Month';
                    } elseif ($job['SalaryType'] == 'per_year') {
                        $jobType                                                = 'Per Year';
                    } else {
                        $jobType                                                = '';
                    }
                } else {
                    $jobType                                                    = '';
                }
                
                $JobCategorys                                                   = $job['JobCategory'];
                
                if ($JobCategorys != null) {
                    $cat_id                                                     = explode(',', $JobCategorys);
                    $job_category_array                                         = array();
                    
                    foreach ($cat_id as $id) {
                        $job_cat_name                                           = $this->all_feed_model->get_job_category_name_by_id($id);
                        
                        if(!empty($job_cat_name)) {
                            $job_category_array[]                               = $job_cat_name[0]['value'];
                        }
                    }
                    
                    $job_category                                               = implode(', ', $job_category_array);
                }
                
                $jobDescription                                                 = str_replace('"', "'", strip_tags($job['JobDescription'], '<br>'));

                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobRequirements                                            = str_replace('"', "'", strip_tags($job['JobRequirements'], '<br>'));
                } else {
                    $jobRequirements                                            = '';
                }
                
                if (!empty($companyDetail['YouTubeVideo'])) {
                    $companyYoutube                                             = "https://www.youtube.com/watch?v=" . $companyDetail['YouTubeVideo'];
                } else {
                    $companyYoutube                                             = $companyDetail['YouTubeVideo'];
                }
                
                if (!empty($job['YouTube_Video'])) {
                    $job['YouTube_Video']                                       = "https://www.youtube.com/watch?v=" . $job['YouTube_Video'];
                }
                
                $job = array(   'title' => db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code']),
                                'publish_date' => date_with_time($publish_date) . ' PST',
                                'expiry_date' => $expiryDate,
                                'referencenumber' => $uid,
                                'url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                                'company' => $companyName,
                                'username' => $companyUserName,
                                'city' => $city,
                                'state' => $state['state_name'],
                                'country' => $country['country_code'],
                                'postalcode' => $zipcode,
                                'salary' => $salary,
                                'jobtype' => $jobType,
                                'AHR_featured' => $featured,
                                'AHR_priority' => 1,
                                'category' => $job_category,
                                'description' => $jobDescription,
                                'jobRequirements' => $jobRequirements,
                                'youtube_video' => $job['YouTube_Video'],
                                'company_logo' => AWS_S3_BUCKET_URL . $companyLogo,
                                'apply_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                                'company_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'],
                                'contact_name' => $companyContactName,
                                'company_youtube_video' => $companyYoutube);
                
                if ($today_end >= $publish_date && $today_start <= $publish_date) {
                    $organic_today[] = $job;
                } else {
                    if($today_end >= $publish_date && $week_start <= $publish_date) {
                        $organic_week[] = $job;
                    } else {
                        $organic_month[] = $job;
                    }
                }
            }
        }
        
        $xml_data                                                               = '';
        $xml_data                                                               .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        $xml_data                                                               .= '<xml>';
        
        if(!empty($paid_feed)) {  // paid feed
            foreach($paid_feed as $om) {
                $xml_data .= "<job>
                    <title><![CDATA[" . $om['title'] . "]]></title>
                    <publish_date><![CDATA[" . $om['publish_date'] . " PST]]></publish_date>
                    <expiry_date><![CDATA[" . $om['expiry_date'] . "]]></expiry_date>
                    <referencenumber><![CDATA[" . $om['referencenumber'] . "]]></referencenumber>
                    <url><![CDATA[" . $om['url'] . "]]></url>
                    <company><![CDATA[" . $om['company'] . "]]></company>
                    <username><![CDATA[" . $om['username'] . "]]></username>
                    <city><![CDATA[" . $om['city'] . "]]></city>
                    <state><![CDATA[" . $om['state'] . "]]></state>
                    <country><![CDATA[" . $om['country'] . "]]></country>
                    <postalcode><![CDATA[" . $om['postalcode'] . "]]></postalcode>
                    <salary><![CDATA[" . $om['salary'] . "]]></salary>
                    <jobtype><![CDATA[" . $om['jobtype'] . "]]></jobtype>
                    <AHR_featured><![CDATA[" . $om['AHR_featured'] . "]]></AHR_featured>
                    <AHR_priority><![CDATA[1]]></AHR_priority>
                    <category><![CDATA[" . $om['category'] . "]]></category>
                    <description><![CDATA[" . $om['description'] . "]]></description>
                    <jobRequirements><![CDATA[" . $om['jobRequirements'] . "]]></jobRequirements>
                    <youtube_video><![CDATA[" . $om['youtube_video'] . "]]></youtube_video>
                    <company_logo><![CDATA[" . $om['company_logo'] . "]]></company_logo>
                    <apply_url><![CDATA[" . $om['apply_url'] . "]]></apply_url>
                    <company_url><![CDATA[" . $om['company_url'] . "]]></company_url>
                    <contact_name><![CDATA[" . $om['contact_name'] . "]]></contact_name>
                    <company_youtube_video><![CDATA[" . $om['company_youtube_video'] . "]]></company_youtube_video>
                    </job>";
            }
        }
        
        if(!empty($organic_month)) { //organic month
            foreach($organic_month as $om) {
                $xml_data .= "<job>
                    <title><![CDATA[" . $om['title'] . "]]></title>
                    <publish_date><![CDATA[" . $om['publish_date'] . " PST]]></publish_date>
                    <expiry_date><![CDATA[" . $om['expiry_date'] . "]]></expiry_date>
                    <referencenumber><![CDATA[" . $om['referencenumber'] . "]]></referencenumber>
                    <url><![CDATA[" . $om['url'] . "]]></url>
                    <company><![CDATA[" . $om['company'] . "]]></company>
                    <username><![CDATA[" . $om['username'] . "]]></username>
                    <city><![CDATA[" . $om['city'] . "]]></city>
                    <state><![CDATA[" . $om['state'] . "]]></state>
                    <country><![CDATA[" . $om['country'] . "]]></country>
                    <postalcode><![CDATA[" . $om['postalcode'] . "]]></postalcode>
                    <salary><![CDATA[" . $om['salary'] . "]]></salary>
                    <jobtype><![CDATA[" . $om['jobtype'] . "]]></jobtype>
                    <AHR_featured><![CDATA[" . $om['AHR_featured'] . "]]></AHR_featured>
                    <AHR_priority><![CDATA[1]]></AHR_priority>
                    <category><![CDATA[" . $om['category'] . "]]></category>
                    <description><![CDATA[" . $om['description'] . "]]></description>
                    <jobRequirements><![CDATA[" . $om['jobRequirements'] . "]]></jobRequirements>
                    <youtube_video><![CDATA[" . $om['youtube_video'] . "]]></youtube_video>
                    <company_logo><![CDATA[" . $om['company_logo'] . "]]></company_logo>
                    <apply_url><![CDATA[" . $om['apply_url'] . "]]></apply_url>
                    <company_url><![CDATA[" . $om['company_url'] . "]]></company_url>
                    <contact_name><![CDATA[" . $om['contact_name'] . "]]></contact_name>
                    <company_youtube_video><![CDATA[" . $om['company_youtube_video'] . "]]></company_youtube_video>
                    </job>";
            }
        }
        
        if(!empty($organic_week)) { // organic week
            foreach($organic_week as $om) {
                $xml_data .= "<job>
                    <title><![CDATA[" . $om['title'] . "]]></title>
                    <publish_date><![CDATA[" . $om['publish_date'] . " PST]]></publish_date>
                    <expiry_date><![CDATA[" . $om['expiry_date'] . "]]></expiry_date>
                    <referencenumber><![CDATA[" . $om['referencenumber'] . "]]></referencenumber>
                    <url><![CDATA[" . $om['url'] . "]]></url>
                    <company><![CDATA[" . $om['company'] . "]]></company>
                    <username><![CDATA[" . $om['username'] . "]]></username>
                    <city><![CDATA[" . $om['city'] . "]]></city>
                    <state><![CDATA[" . $om['state'] . "]]></state>
                    <country><![CDATA[" . $om['country'] . "]]></country>
                    <postalcode><![CDATA[" . $om['postalcode'] . "]]></postalcode>
                    <salary><![CDATA[" . $om['salary'] . "]]></salary>
                    <jobtype><![CDATA[" . $om['jobtype'] . "]]></jobtype>
                    <AHR_featured><![CDATA[" . $om['AHR_featured'] . "]]></AHR_featured>
                    <AHR_priority><![CDATA[1]]></AHR_priority>
                    <category><![CDATA[" . $om['category'] . "]]></category>
                    <description><![CDATA[" . $om['description'] . "]]></description>
                    <jobRequirements><![CDATA[" . $om['jobRequirements'] . "]]></jobRequirements>
                    <youtube_video><![CDATA[" . $om['youtube_video'] . "]]></youtube_video>
                    <company_logo><![CDATA[" . $om['company_logo'] . "]]></company_logo>
                    <apply_url><![CDATA[" . $om['apply_url'] . "]]></apply_url>
                    <company_url><![CDATA[" . $om['company_url'] . "]]></company_url>
                    <contact_name><![CDATA[" . $om['contact_name'] . "]]></contact_name>
                    <company_youtube_video><![CDATA[" . $om['company_youtube_video'] . "]]></company_youtube_video>
                    </job>";
            }
        }
        
        if(!empty($organic_today)) { // organic today
            foreach($organic_today as $om) {
                $xml_data .= "<job>
                    <title><![CDATA[" . $om['title'] . "]]></title>
                    <publish_date><![CDATA[" . $om['publish_date'] . " PST]]></publish_date>
                    <expiry_date><![CDATA[" . $om['expiry_date'] . "]]></expiry_date>
                    <referencenumber><![CDATA[" . $om['referencenumber'] . "]]></referencenumber>
                    <url><![CDATA[" . $om['url'] . "]]></url>
                    <company><![CDATA[" . $om['company'] . "]]></company>
                    <username><![CDATA[" . $om['username'] . "]]></username>
                    <city><![CDATA[" . $om['city'] . "]]></city>
                    <state><![CDATA[" . $om['state'] . "]]></state>
                    <country><![CDATA[" . $om['country'] . "]]></country>
                    <postalcode><![CDATA[" . $om['postalcode'] . "]]></postalcode>
                    <salary><![CDATA[" . $om['salary'] . "]]></salary>
                    <jobtype><![CDATA[" . $om['jobtype'] . "]]></jobtype>
                    <AHR_featured><![CDATA[" . $om['AHR_featured'] . "]]></AHR_featured>
                    <AHR_priority><![CDATA[1]]></AHR_priority>
                    <category><![CDATA[" . $om['category'] . "]]></category>
                    <description><![CDATA[" . $om['description'] . "]]></description>
                    <jobRequirements><![CDATA[" . $om['jobRequirements'] . "]]></jobRequirements>
                    <youtube_video><![CDATA[" . $om['youtube_video'] . "]]></youtube_video>
                    <company_logo><![CDATA[" . $om['company_logo'] . "]]></company_logo>
                    <apply_url><![CDATA[" . $om['apply_url'] . "]]></apply_url>
                    <company_url><![CDATA[" . $om['company_url'] . "]]></company_url>
                    <contact_name><![CDATA[" . $om['contact_name'] . "]]></contact_name>
                    <company_youtube_video><![CDATA[" . $om['company_youtube_video'] . "]]></company_youtube_video>
                    </job>";
            }
        }
        
        $xml_data                                                               .= '</xml>';

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
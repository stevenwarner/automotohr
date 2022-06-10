<?php

// use function GuzzleHttp\json_decode;

defined('BASEPATH') or exit('No direct script access allowed');

class Facebook_feed extends CI_Controller
{

    private $clientId = '2211285445561045';
    private $clientSecret = '8ee3e946d117a7a6c2c0cd5527335c82';
    private $hiringManagerId = "1923839514417430";
    private $accessToken;
    private $accessTokenType;
    private $feedId;
    private $jobApplicationId;

    // Job Feed Ids:[310277717149233,2925045904484375,1364424370612380]

    private $table = 'facebook_jobs_status';

    public function __construct()
    {
        parent::__construct();
        $this->load->model('facebook_feed_model');
        $this->load->model('all_feed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');

        //
        $this->accessToken = '2211285445561045|CDxZYxcSQcx6mJFHiH1RRHbtyOk';
        $this->accessTokenType = 'bearer';
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

    public function index($generate_file = 0)
    {
        $sid = $this->isActiveFeed();
        $this->addLastRead(17);
        $featuredJobs                                                           = $this->all_feed_model->get_all_company_jobs_ams();
        $jobData                                                                = $this->all_feed_model->get_all_company_jobs();
        $activeCompaniesArray                                                   = $this->all_feed_model->get_all_active_companies($sid);
        $paid_feed                                                              = array();
        $organic_today                                                          = array();
        $organic_week                                                           = array();
        $organic_month                                                          = array();
        //
        $companyAddresses = $this->all_feed_model->GetCompanyAddresses($activeCompaniesArray);
        //
        foreach ($jobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                foreach ($featuredJobs as $featuredId) {
                    if ($featuredId['jobId'] == $job['sid']) {
                        $company_id                                             = $job['user_sid'];
                        $companyPortal                                          = $this->all_feed_model->get_portal_detail($company_id);

                        if (empty($companyPortal)) {
                            continue;
                        }

                        $companyDetail                                          = $this->all_feed_model->get_company_detail($company_id);
                        $companyName                                            = $companyDetail['CompanyName'];
                        $has_job_approval_rights                                = $companyDetail['has_job_approval_rights'];
                        $companyLogo                                            = $companyDetail['Logo'];
                        $companyContactName                                     = $companyDetail['ContactName'];
                        $companyYoutube                                         = $companyDetail['YouTubeVideo'];
                        $companyUserName                                        = strtolower(str_replace(' ', '', $companyName));

                        if ($has_job_approval_rights ==  1) {
                            $approval_right_status                              = $job['approval_status'];

                            if ($approval_right_status != 'approved') {
                                continue;
                            }
                        }

                        $uid                                                    = $job['sid'];
                        $publish_date                                           = $job['activation_date'];
                        $feed_data                                              = $this->all_feed_model->fetch_uid_from_job_sid($uid);

                        if (!empty($feed_data)) {
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

                                if (!empty($job_cat_name)) {
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

                        $paid_feed[] = array(
                            'title' => $job['Title'],
                            'publish_date' => date_with_time($publish_date) . ' PST',
                            'publish_date_orginal' => $publish_date,
                            'odate' => $publish_date,
                            'expiry_date' => $expiryDate,
                            'jid' => $job['sid'],
                            'cid' => $job['user_sid'],
                            'company_address' => empty($companyAddresses[$company_id]) ? $job['Location_Address'] : $companyAddresses[$company_id],
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
                            'company_id' => $company_id,
                            'description' => $jobDescription,
                            'jobRequirements' => $jobRequirements,
                            'youtube_video' => $job['YouTube_Video'],
                            'company_logo' => AWS_S3_BUCKET_URL . $companyLogo,
                            'apply_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                            'company_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'],
                            'contact_name' => $companyContactName,
                            'company_youtube_video' => $companyYoutube
                        );
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

                if (empty($companyPortal)) {
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

                if ($has_job_approval_rights ==  1) {
                    $approval_right_status                                      = $job['approval_status'];

                    if ($approval_right_status != 'approved') {
                        continue;
                    }
                }

                $feed_data                                                      = $this->all_feed_model->fetch_uid_from_job_sid($uid);

                if (!empty($feed_data)) {
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

                        if (!empty($job_cat_name)) {
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

                $paid_feed[] = array(
                    'title' => $job['Title'],
                    'publish_date' => date_with_time($publish_date) . ' PST',
                    'publish_date_orginal' => $publish_date,
                    'expiry_date' => $expiryDate,
                    'referencenumber' => $uid,
                    'jid' => $job['sid'],
                    'url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                    'company' => $companyName,
                    'username' => $companyUserName,
                    'company_address' => empty($companyAddresses[$company_id]) ? '' : $companyAddresses[$company_id],
                    'odate' => $publish_date,
                    'city' => $city,
                    'state' => $state['state_name'],
                    'country' => $country['country_code'],
                    'postalcode' => $zipcode,
                    'salary' => $salary,
                    'jobtype' => $jobType,
                    'AHR_featured' => $featured,
                    'AHR_priority' => 1,
                    'category' => $job_category,
                    'company_id' => $company_id,
                    'description' => $jobDescription,
                    'jobRequirements' => $jobRequirements,
                    'youtube_video' => $job['YouTube_Video'],
                    'company_logo' => AWS_S3_BUCKET_URL . $companyLogo,
                    'apply_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid,
                    'company_url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'],
                    'contact_name' => $companyContactName,
                    'company_youtube_video' => $companyYoutube
                );

                if ($today_end >= $publish_date && $today_start <= $publish_date) {
                    $organic_today[] = $job;
                } else {
                    if ($today_end >= $publish_date && $week_start <= $publish_date) {
                        $organic_week[] = $job;
                    } else {
                        $organic_month[] = $job;
                    }
                }
            }
        }

        $xml_data                                                               = '';
        $xml_data                                                               .= "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
        $xml_data                                                               .= '<source>';
        $xml_data                                                               .= '<publisher-name>AutomotoHR.com</publisher-name>
                                                                                    <publisher-url>' . (STORE_FULL_URL) . '</publisher-url>
                                                                                    <last-build-date>' . date('Y-m-d H:i:s') . '</last-build-date>';

        if (!empty($paid_feed)) {  // paid feed
            $columns = array_column($paid_feed, 'odate');
            array_multisort($columns, SORT_DESC, $paid_feed);

            if($generate_file == 1) {
                echo json_encode($paid_feed);
                exit(0);
            }
            foreach ($paid_feed as $om) {
                $salary = $om['salary'];
                $salary = str_replace(" - ", "/", $salary);
                $salary = str_replace("-", "/", $salary);
                $salary = str_replace(" to ", "/", $salary);
                $salary = str_replace("to", "/", $salary);
                $salary = str_replace(" To ", "/", $salary);
                $salary = str_replace("To", "/", $salary);
                $salary = str_replace(" / ", "/", $salary);
                $salary = str_replace("/", "/", $salary);
                $min_salary = $salary;
                $max_salary = '';
                $salary = explode('/', $salary);
                if (count($salary) == 2) {
                    $min_salary = trim(preg_replace('/[^0-9.]/', '', $salary[0]));
                    $max_salary = trim(preg_replace('/[^0-9.]/', '', $salary[1]));
                } else{
                    $min_salary = trim(preg_replace('/[^0-9.]/', '', $min_salary));
                }
                $xml_data .= "<job>

                    <!-- basic info -->
                    <title><![CDATA[" . $om['title'] . "]]></title>
                    <date><![CDATA[" . $om['publish_date'] . "]]></date>
                    <id><![CDATA[" . $om['jid'] . "]]></id>
                    <description><![CDATA[" . $om['description'] . "]]></description>
                    <job-type><![CDATA[" . $om['jobtype'] . "]]></job-type>

                    <!-- company info -->
                    <company-name><![CDATA[" . $om['company'] . "]]></company-name>
                    <company-id><![CDATA[" . $om['company_id'] . "]]></company-id>
                    <company-full-address><![CDATA[" . $om['company_address'] . "]]></company-full-address>
                    <company-url><![CDATA[" . ($om['company_url']) . "]]></company-url>

                    <!-- location -->
                    <city><![CDATA[" . $om['city'] . "]]></city>
                    <region><![CDATA[" . $om['state'] . "]]></region>
                    <country><![CDATA[" . $om['country'] . "]]></country>
                    <postal-code><![CDATA[" . $om['postalcode'] . "]]></postal-code>

                    <!-- salary -->

                    <salary-min><![CDATA[" . $min_salary . "]]></salary-min>
                    <salary-max><![CDATA[" . $max_salary . "]]></salary-max>
                    <salary-currency><![CDATA[USD]]></salary-currency>

                    <!-- integration configuration -->

                    <facebook-apply-data>
                        <application-callback-url>
                        <![CDATA[" . STORE_PROTOCOL_SSL . "www." . STORE_DOMAIN . "/Facebook_feed/jobXmlCallback]]>
                        </application-callback-url>
                        <form-config>
                            <email-field>
                                <optional><![CDATA[false]]></optional>
                            </email-field>
                            <phone-number-field>
                                <optional><![CDATA[true]]></optional>
                            </phone-number-field>
                            <work-experience-field>
                                <optional><![CDATA[true]]></optional>
                            </work-experience-field>
                        </form-config>
                    </facebook-apply-data>

                    </job>";
            }
        }

        $xml_data                                                               .= '</source>';
        //
        header('Content-type: text/xml');
        header('Pragma: public');
        header('Cache-control: private');
        header('Expires: -1');
        echo $xml_data;
        //
        @mail('mubashir.saleemi123@gmail.com', 'Facebook Feed - HIT on ' . date('Y-m-d H:i:s') . '', count($paid_feed));
        exit;
    }

    /**
     * 
     */
    private function addReport($source, $email, $type = 'add'){
        if($type == 'add'){
            $this->db
            ->insert('daily_job_counter', [
                'source' => $source,
                'email' => $email,
                'created_at' => date('Y-m-d H:i:s', strtotime('now')),
                'already_exists' => 0
            ]);
        } else{
            $this->db
            ->where('email', $email)
            ->where('created_at', date('Y-m-d H:i:s', strtotime('now')))
            ->update('daily_job_counter', [
                'already_exists' => 1
            ]);
        }
    }

    public function jobXmlCallback()
    {

        // $dt = file_get_contents(APPPATH.'../assets/fba/20200612004053.json');
        $dt = file_get_contents('php://input');
        $this->addLastRead(18);
        //
        @mail('mubashir.saleemi123@gmail.com', 'Facebook - Applicant Recieve - ' . date('Y-m-d H:i:s') . '', print_r($dt, true));
        //
        $folder = APPPATH.'../../applicant/Facebook';
        //
        if(!is_dir($folder)) mkdir($folder, 0777, true);
        // 
        $categories_file = fopen($folder.'/Facebook_Applicant_Recieve_' . date('Y_m_d_H_i_s') . '.json', 'w');
        //
        fwrite($categories_file, file_get_contents('php://input'));
        //
        fclose($categories_file);
        //
        if (!$dt) exit(0);
        $f = fopen(APPPATH . '../assets/fba/' . time() . '.json', 'w');
        fwrite($f, $dt);
        fclose($f);
        $dt = json_decode($dt, true, 2, JSON_BIGINT_AS_STRING);
        //
        $this->jobApplicationId = $dt['job_application_id'];
        $jobId = $dt['job_external_id'];
        // $jobId = 24859;
        // Lets save the incoming request
        $facebookApplicantId =
            $this->facebook_feed_model->saveIncomingApplicant([
                'job_sid' => $jobId,
                'job_application_id' => $dt['job_application_id'],
                'job_type' => $dt['type'],
                'original_resume_url' => (isset($dt['resume_url']) ? $dt['resume_url'] : '')
            ]);
        // exit(0);
        // Check if job exists
        $jobDetails = $this->facebook_feed_model->getJobDetails($jobId);
        //
        if (!sizeof($jobDetails)) {
            _e('Job not found!');
            exit(0);
        }
        //
        $companyId = $jobDetails['user_sid'];
        $companyName = $jobDetails['CompanyName'];
        $jobTitle = $jobDetails['Title'];
        // //
        $this->makeCall(
            'https://graph.facebook.com/v7.0/{{jobApplicationId}}?fields=name,email,city_name,phone_number&access_token={{accessToken}}',
            array(
                CURLOPT_CUSTOMREQUEST => "GET"
            )
        );
        // Lets update the resume field
        $this->facebook_feed_model->updateIncomingApplicant(
            [
                'extra_info' => json_encode($this->curl),
                'original_application_id' => $this->curl['id']
            ],
            $facebookApplicantId
        );
        //
        $applicantInfo = $this->curl;
        $t = explode(' ', $applicantInfo['name']);
        $firstName = trim($t[0]);
        $lastName = trim($t[1]);
        //
        unset($t);
        //
        $this->addReport('AutoCareers', trim($applicantInfo['email']));
        //
        if(!isset($applicantInfo['email'])){
            echo "Email not found";
            exit(0);
        }
        // Check if applicant exists in company
        //  - If No then add it
        //  - else get applicant id
        // Check if applicant already applied for job
        //  - If Yes then send already applied notification
        //  - else apply proccess
        // Check if applicant already applied
        $isApplied = $this->facebook_feed_model->alreadyApplied(
            $companyId,
            $jobId,
            trim($applicantInfo['email']),
            $firstName,
            $lastName,
            $applicantInfo['phone_number']
        );
        // If the candiatehas already applied
        if ($isApplied['hasApplied'] == 1) {
            $this->addReport('Facebook', trim($applicantInfo['email']), 'update');
            $replacement_array = array();
            $replacement_array['company_name'] = $companyName;
            $replacement_array['job_title'] = $jobTitle;
            $replacement_array['original_job_title'] = $jobTitle;
            $replacement_array['applicant_name'] = $applicantInfo['name'];
            // $replacement_array['resume_link'] = $resume_anchor;
            $replacement_array['applicant_profile_link'] = base_url('applicant_profile/' . $isApplied['portalJobApplicationsId']);
            // log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, 'mubashir.saleemi123@gmail.com', $replacement_array, message_header_footer($companyId, $companyName), 0);
            log_and_send_templated_email(INDEED_ALREADY_APPLIED_NOTIFICATION, $applicantInfo['email'], $replacement_array, message_header_footer($companyId, $companyName), 0);
        } else {
            // Excute when applicant hasn't applied

            if (isset($dt['resume_url']) && !empty($dt['resume_url'])) {
                // Lets upload resume
                $resume = $dt['resume_url'];
                $file_name = 'resume_facebook_job_' . clean($firstName) . '_' . clean($lastName) . '_' . date('YmdHis') . '.pdf';
                $file_location = sys_get_temp_dir();
                $file_path = $file_location . '/' . $file_name;
                @file_put_contents($file_path,  getFileData($resume));
                // uncomment on production
                $aws = new AwsSdk();
                $aws->putToBucket($file_name, $file_path, AWS_S3_BUCKET_NAME);  //uploading file to AWS
                $resume = $file_name;
                unlink($file_path);
            } else $resume = '';

            //
            $resume_anchor = '';
            if (!empty($resume)) {
                $resume_url = AWS_S3_BUCKET_URL . urlencode($resume);
                $resume_anchor = '<a style="' . DEF_EMAIL_BTN_STYLE_PRIMARY . '" href="' . $resume_url . '" target="_blank">Download Resume</a>';
            }


            // Get status
            $status = $this->facebook_feed_model->get_default_status_sid_and_text($companyId);

            // Create insert array
            $in = [];
            $in['portal_job_applications_sid'] = $isApplied['portalJobApplicationsId'];
            $in['job_sid'] = $jobId;
            $in['company_sid'] = $companyId;
            $in['date_applied'] = date('Y-m-d H:i:s', strtotime('now'));
            $in['status'] = $status['status_name'];
            $in['status_sid'] = $status['status_sid'];
            $in['applicant_source'] = 'https://www.facebook.com/jobs';
            $in['main_referral'] = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'https://www.facebook.com/jobs';
            $in['applicant_type'] = 'Applicant';
            $in['eeo_form'] = null;
            $in['resume'] = $resume;
            $in['last_update'] = date('Y-m-d', strtotime('now'));
            //
            $jobs_list_result = $this->facebook_feed_model->add_applicant_job_details($in);
            //
            $portal_applicant_jobs_list_sid = $jobs_list_result[0];
            $job_added_successfully = $jobs_list_result[1];

            if (!isset($resume) || empty($resume) || $resume == '') {
                sendResumeEmailToApplicant([
                    'company_sid' => $companyId,
                    'company_name' => $companyName,
                    'job_list_sid' => $jobId,
                    'user_sid' => $isApplied['portalJobApplicationsId'],
                    'user_type' => 'applicant',
                    'requested_job_sid' => $jobId,
                    'requested_job_type' => 'job'
                ], false);
            }

            // Email sent to applicant
            $acknowledgement_email_data['company_name'] = $companyName;
            $acknowledgement_email_data['sid'] = $isApplied['portalJobApplicationsId'];
            $acknowledgement_email_data['job_sid'] = $jobId;
            $acknowledgement_email_data['job_title'] = $jobTitle;
            $acknowledgement_email_data['employer_sid'] = $companyId;
            $acknowledgement_email_data['first_name'] = $firstName;
            $acknowledgement_email_data['last_name'] = $lastName;
            $acknowledgement_email_data['email'] = $applicantInfo['email'];
            $acknowledgement_email_data['phone_number'] = $applicantInfo['phone_number'];
            $acknowledgement_email_data['date_applied'] = $in['date_applied'];
            common_indeed_acknowledgement_email($acknowledgement_email_data);
            //
            // send email to 'new applicant notification' users *** START *** ////////
            $message_hf = message_header_footer_domain($companyId, $companyName);
            //
            $replacement_array = array();
            $replacement_array['site_url'] = base_url();
            $replacement_array['date'] = month_date_year(date('Y-m-d'));
            $replacement_array['job_title'] = $jobTitle;
            $replacement_array['phone_number'] = $applicantInfo['phone_number'];
            $replacement_array['original_job_title'] = $jobTitle;
            $replacement_array['company_name'] = $companyName;
            $replacement_array['city'] = isset($applicantInfo['city_name']) ? $applicantInfo['city_name'] : '';
            $profile_anchor = '<a href="' . base_url('applicant_profile/' . $isApplied['portalJobApplicationsId'] . '/' . $portal_applicant_jobs_list_sid) . '" style="' . DEF_EMAIL_BTN_STYLE_DANGER . '">View Profile</a>';
            // Get employers for nootification
            $notifications_status = get_notifications_status($companyId);
            //$company_primary_admin_info = get_primary_administrator_information($company_sid);
            $applicant_notifications_status = 0;

            if (!empty($notifications_status)) {
                $applicant_notifications_status = $notifications_status['new_applicant_notifications'];
            } else {
                //mail($this->debug_email, STORE_NAME . ' Apply Now Debug - No Status Record Found', $my_debug_message);
            }

            $applicant_notification_contacts = array();

            // Send notification to employers
            if ($applicant_notifications_status == 1) { // New Applicants Notifications Enabled
                $applicant_notification_contacts = get_notification_email_contacts($companyId, 'new_applicant', $jobId);

                if (!empty($applicant_notification_contacts)) {
                    foreach ($applicant_notification_contacts as $contact) {
                        $replacement_array['firstname'] = $firstName;
                        $replacement_array['lastname'] = $lastName;
                        $replacement_array['email'] = $applicantInfo['email'];
                        $replacement_array['company_name'] = $companyName;
                        $replacement_array['resume_link'] = $resume_anchor;
                        $replacement_array['applicant_profile_link']   = $profile_anchor;
                        log_and_send_templated_notification_email(APPLY_ON_JOB_EMAIL_ID, $contact['email'], $replacement_array, $message_hf, $companyId, $jobId, 'new_applicant_notification');
                    }
                }
            }
            // Screening questionaire
            $questionnaire_sid = $jobDetails['questionnaire_sid'];

            if ($questionnaire_sid > 0) {
                $questionnaire_status = $this->all_feed_model->check_screening_questionnaires($questionnaire_sid);

                if ($questionnaire_status == 'found') {
                    $email_template_information = $this->all_feed_model->get_email_template_data(SCREENING_QUESTIONNAIRE_FOR_JOB);
                    $screening_questionnaire_key = $this->all_feed_model->generate_questionnaire_key($portal_applicant_jobs_list_sid);

                    if (empty($email_template_information)) {
                        $email_template_information = array(
                            'subject' => '{{company_name}} - Screening Questionnaire for {{job_title}}',
                            'text' => '<p>Dear {{applicant_name}},</p>
                                                                <p>You have successfully applied for the job: "{{job_title}}" and your job application is in our system. </p>
                                                                <p><strong>Please complete the Job Screening Questionnaire by clicking on the link below. We are excited to learn more about you. </strong></p>
                                                                <p>{{url}}</p>
                                                                <p>Thank you, again, for your interest in {{company_name}}</p>',
                            'from_name' => '{{company_name}}'
                        );
                    }

                    $emailTemplateBody = $email_template_information['text'];
                    $emailTemplateSubject = $email_template_information['subject'];
                    $emailTemplateFromName = $email_template_information['from_name'];
                    $replacement_array = array();
                    $replacement_array['company_name'] = $companyName;

                    if ($jobTitle != '') {
                        $replacement_array['job_title'] = $jobTitle;
                    } else {
                        $replacement_array['job_title'] = $jobTitle;
                    }

                    $replacement_array['applicant_name'] = $firstName . '&nbsp;' . $lastName;
                    $replacement_array['url'] = '<a style="background-color: #d62828; font-size:16px; font-weight: bold; font-family:sans-serif; text-decoration: none; line-height:40px; padding: 0 15px; color: #fff; border-radius: 5px; text-align: center; display:inline-block" href="' . base_url() . 'Job_screening_questionnaire/' . $screening_questionnaire_key . '" target="_blank">Screening Questionnaire</a>';

                    if (!empty($replacement_array)) {
                        foreach ($replacement_array as $key => $value) {
                            $emailTemplateBody = str_replace('{{' . $key . '}}', $value, $emailTemplateBody);
                            $emailTemplateSubject = str_replace('{{' . $key . '}}', $value, $emailTemplateSubject);
                            $emailTemplateFromName = str_replace('{{' . $key . '}}', $value, $emailTemplateFromName);
                        }
                    }

                    $message_data = array();
                    $message_data['to_id'] = $applicantInfo['email'];
                    $message_data['from_type'] = 'employer';
                    $message_data['to_type'] = 'admin';
                    $message_data['job_id'] = $isApplied['portalJobApplicationsId'];
                    $message_data['users_type'] = 'applicant';
                    $message_data['subject'] = $emailTemplateSubject;
                    $message_data['message'] = $emailTemplateBody;
                    $message_data['date'] = date('Y-m-d H:i:s', strtotime('now'));
                    $message_data['from_id'] = REPLY_TO;
                    $message_data['contact_name'] = $firstName . '&nbsp;' . $lastName;
                    $message_data['identity_key'] = generateRandomString(48);
                    $secret_key = $message_data['identity_key'] . "__";
                    $autoemailbody = $message_hf['header']
                        . $emailTemplateBody
                        . $message_hf['footer']
                        . '<div style="width:100%; float:left; background-color:#000; color:#000; box-sizing:border-box;">message_id:'
                        . $secret_key . '</div>';

                    //sendMail(REPLY_TO, $email, $emailTemplateSubject, $autoemailbody, $company_name, REPLY_TO);
                    // sendMail(REPLY_TO, $this->debug_email, $emailTemplateSubject.' ZiP', $autoemailbody, $companyName, REPLY_TO);
                    $sent_to_pm = common_save_message($message_data, NULL);
                    $this->all_feed_model->update_questionnaire_status($portal_applicant_jobs_list_sid);
                }
            }
        }
    }


    public function jobScreeningQuestionnaire()
    {
    }

    //
    function test()
    {
        // $this->getAccessToken();
        // $this->postJobs();
    }


    //
    private function getAccessToken()
    {
        //
        $this->makeCall(
            "https://graph.facebook.com/oauth/access_token?client_id={{clientId}}&client_secret={{clientSecret}}&grant_type=client_credentials",
            array(CURLOPT_CUSTOMREQUEST => "GET")
        );
        //
        $this->accessToken = $this->curl['access_token'];
        $this->accessTokenType = $this->curl['access_type'];

        return $this->curl;
    }

    //
    private function postJobs()
    {
        // $this->getAccessToken();
        //
        $this->makeCall(
            "https://graph.facebook.com/v7.0/{{hiringManagerId}}/job_feeds?access_token={{accessToken}}",
            array(
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => array('feed_url' => 'https://www.automotohr.com/facebook_feed', 'syncing_frequency' => 'DAILY')
            )
        );
        //
        $this->feedId = $this->curl['id'];
    }

    //
    private function checkFeedStatus()
    {
        // $this->getAccessToken();
        //
        $this->makeCall(
            "https://graph.facebook.com/v7.0/{{feedId}}/jobs?access_token={{accessToken}}&fields=job_status,external_id,platform_review_status,id",
            array(CURLOPT_CUSTOMREQUEST => "GET")
        );
        //
    }


    //
    private function makeCall(
        $url,
        $headers = array()
    ) {
        //
        $url = trim($url);
        //
        $this->parseURL($url);
        //
        $curl = curl_init();
        //
        $options = $headers;
        $options[CURLOPT_URL] = $url;
        $options[CURLOPT_RETURNTRANSFER] = true;
        $options[CURLOPT_ENCODING] = "";
        $options[CURLOPT_MAXREDIRS] = 10;
        $options[CURLOPT_TIMEOUT] = 0;
        $options[CURLOPT_FOLLOWLOCATION] = true;
        $options[CURLOPT_SSL_VERIFYPEER] = FALSE;
        $options[CURLOPT_SSL_VERIFYHOST] = FALSE;
        $options[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1;
        //
        curl_setopt_array(
            $curl,
            $options
        );
        //
        $this->curl = json_decode(curl_exec($curl), true);
        //
        curl_close($curl);
    }


    //
    private function parseURL(
        &$url
    ) {
        $url = str_replace(
            [
                '{{clientId}}',
                '{{clientSecret}}',
                '{{accessToken}}',
                '{{hiringManagerId}}',
                '{{feedId}}',
                '{{jobApplicationId}}'
            ],
            [
                $this->clientId,
                $this->clientSecret,
                $this->accessToken,
                $this->hiringManagerId,
                $this->feedId,
                $this->jobApplicationId
            ],
            $url
        );
    }


    private function isActiveFeed()
    {
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('facebook_feed');
        if (!$validSlug) {
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }
        return $validSlug;
    }


    /**
     * 
     */
    function jobsStatus(){
        //
        $jobs = [];
        $feedIds = ["310277717149233","2925045904484375","1364424370612380"];
        //
        foreach($feedIds as  $feedId){
            //
            $url = 'https://graph.facebook.com/v7.0/'.($feedId).'/jobs?access_token=2211285445561045%7CCDxZYxcSQcx6mJFHiH1RRHbtyOk&fields=job_status,external_id,platform_review_status,id,wage,review_rejection_reasons,errors&limit=5000';
            //
            $this->makeCall(
                $url,
                array(CURLOPT_CUSTOMREQUEST => "GET")
            );
            //
            $jobs = array_merge($jobs, $this->curl['data']);
            //
            usleep(200);
        }
        //
        $ids = [];
        $inserted = 0;
        $updated = 0;
        $total = 0;
        //
        $alreadyExists = [];
        //
        foreach($jobs as $job){
            //
            if(isset($alreadyExists[$job['external_id']])){
                continue;
            }
            //
            $alreadyExists[$job['external_id']] = true;
            $total++;
            //
            $t = [];
            $t['job_id'] = $job['external_id'];
            $t['external_id'] = $job['id'];
            $t['job_status'] = $job['job_status'];
            $t['status'] = $job['platform_review_status'];
            $t['is_deleted'] = 0;
            $t['reason'] = isset($job['review_rejection_reasons']) ? implode('<br />', $job['review_rejection_reasons']) : '';
            if($job['job_status'] == 'DRAFT'){
                $t['reason'] = isset($job['errors']) ? implode('<br />', $job['errors']) : '';
            }
            $t['updated_at'] = date('Y-m-d H:i:s', strtotime('now'));
            //
            $id = $this->checkAndInsert($t, $t['job_id'], $inserted, $updated);
            //
            $ids[] = $id;
        }
        
        if($ids){
            //
            $this->db
            ->where_not_in(
                'job_id', $ids
            )
            ->update('facebook_jobs_status', [
                'is_deleted' => 1
            ]);
        }

        //
        _e('-------------------');
        _e("Total = ".$total);
        _e("Updated = ".$updated);
        _e("Inserted = ".$inserted);
        _e('-------------------');
    }


    /**
     * 
     */
    private function checkAndInsert($a,$id, &$i, &$u){
        if(
            $this->db->where('job_id', $id)
            ->count_all_results($this->table)
        ){
            $this->db
            ->where('job_id', $id)
            ->set($a)
            ->update($this->table);
            $u++;
        } else{
            $i++;
            echo (int) $this->db->insert($this->table, $a);
        }
        //
        return $a['job_id'];
    }


    /**
     * 
     */
    function putBackApplicants($date){
        //
        $file_path = APPPATH.'../../applicant/Facebook/';
        //
        $files = scandir($file_path, 1);
        //
        foreach($files as $file){
            //
            if(!preg_match("/$date/", $file)){
                continue;
            }
            //
            $content = file_get_contents($file_path.$file);
            //
            $curl = curl_init();
            //
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://www.automotohr.com/Facebook_feed/jobXmlCallback',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => $content,
                CURLOPT_HTTPHEADER => array(
                  'Content-Type: application/json',
                ),
            ));
            //
            curl_exec($curl);
            //
            curl_close($curl);
            //
            sleep(1);
        }
        //
        die('All Done');
    }
}

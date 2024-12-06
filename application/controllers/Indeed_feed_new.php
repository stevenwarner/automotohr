<?php defined('BASEPATH') or exit('No direct script access allowed');
ini_set('memory_limit', '50M');
class Indeed_feed_new extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('all_feed_model');
        $this->load->model('indeed_model');
        require_once(APPPATH . 'libraries/aws/aws.php');
    }

    /**
     * 
     */
    private function addLastRead($sid)
    {
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

    public function index($type = 'old')
    {
        $sid = $this->isActiveFeed();
        switch ($type) {
            case 'new':
                $this->newIndex();
                return;
                break;
            case 'jobsForQueue':
                $this->jobsForQueue();
                return;
                break;
        }
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

        // $rows .= '<xml>';
        $rows .= "<source>
        <publisher>" . STORE_NAME . "</publisher>
        <publisherurl><![CDATA[" . STORE_FULL_URL_SSL . "]]></publisherurl>
        <lastBuildDate>" . date('D, d M Y h:i:s') . " PST</lastBuildDate>";
        $xmlFeed = 0;
        $listedJobArray = array();

        foreach ($jobData as $job) {
            if (in_array($job['user_sid'], $activeCompaniesArray)) {
                $company_id = $job['user_sid'];
                $companyPortal = $this->all_feed_model->get_portal_detail($company_id);

                if (empty($companyPortal)) {
                    continue;
                }

                $companydata = $this->all_feed_model->get_company_name_and_job_approval($company_id);
                $companyName = $companydata['CompanyName'];
                $has_job_approval_rights = $companydata['has_job_approval_rights'];

                if ($has_job_approval_rights ==  1) {
                    $approval_right_status = $job['approval_status'];

                    if ($approval_right_status != 'approved') {
                        continue;
                    }
                }

                $uid = $job['sid'];
                $publish_date = $job['activation_date'];
                $feed_data = $this->all_feed_model->fetch_uid_from_job_sid($uid);

                if (!empty($feed_data)) {
                    $uid = $feed_data['uid'];
                    $publish_date = $feed_data['publish_date'];
                }

                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDesc = '<br><br>Job Description:<br><br>' . (StripFeedTags($job['JobDescription'])) . '<br><br>Job Requirements:<br><br>' . (StripFeedTags($job['JobRequirements']));
                } else {
                    $jobDesc = StripFeedTags($job['JobDescription']);
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


                $jobType = "";
                if (isset($job['JobType']) && $job['JobType'] != NULL) {
                    $job['JobType'] = trim($job['JobType']);
                    if ($job['JobType'] == 'Full Time') {
                        $jobType = "Full Time";
                    } elseif ($job['JobType'] == 'Part Time') {
                        $jobType = "Part Time";
                    } elseif ($job['JobType'] == 'Seasonal') {
                        $jobType = "Seasonal";
                    }
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

                //
                $jobQuestionnaireUrl = "";
                //
                if ($job["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($job["user_sid"])) {
                    //
                    if ($this->indeed_model->saveQuestionIntoFile($job['sid'], $job['user_sid'], true)) {
                        //
                        $jobQuestionnaireUrl = "&indeed-apply-questions=";
                        $jobQuestionnaireUrl .= urlencode(
                            STORE_FULL_URL_SSL . "indeed/$uid/jobQuestions.json"
                        );
                    }
                }

                $rows .=  "
                    <job>
                    <title><![CDATA[" . $job['Title'] . "]]></title>
                    <date><![CDATA[" . date_with_time($publish_date) . " PST]]></date>
                    <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                    <requisitionid><![CDATA[" . $job['sid'] . "]]></requisitionid>
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
                    <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid) . "&indeed-apply-jobid=" . $uid . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($company_id, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required{$jobQuestionnaireUrl}]]></indeed-apply-data>
                    </job>";
            }
        }


        $rows .=  '</source>';
        echo trim($rows);
        mail(TO_EMAIL_DEV, 'Indeed hit XML: ' . date('Y-m-d H:i:s'), print_r($listedJobArray, true));
        exit;
    }

    /**
     * Indeed Organic/Paid Jobs
     *
     *  @return VOID
     */
    private function newIndex()
    {
        $sid = $this->isActiveFeed();
        $this->addLastRead(7);
        //
        $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
        // Get Indeed Paid Job Ids
        $indeedPaidJobIds = $this->indeed_model->getIndeedPaidJobIds();
        $indeedPaidJobs = [];
        if (sizeof($indeedPaidJobIds['Ids'])) {
            // Get Indeed Paid Jobs
            $jobIds = $indeedPaidJobIds['Ids'];
            $budget = $indeedPaidJobIds['Budget'];
            $indeedPaidJobs = $this->indeed_model->getIndeedPaidJobs();
        } else $budget = $jobIds = array();
        // Get Indeed Organic Jobs
        $indeedOrganicJobs = $this->indeed_model->getIndeedOrganicJobs($featuredJobs);
        // Get Active companies
        $activeCompanies = $this->indeed_model->getAllActiveCompanies($sid);
        //
        $rows = '';
        //
        $infoArray = array();
        $infoArray['Skipped']['Paid'] = array();
        $infoArray['Skipped']['Organic'] = array();
        $infoArray['Listed']['Paid'] = array();
        $infoArray['Listed']['Organic'] = array();
        //
        $totalJobsForFeed = 0;

        // Loop through Organic Jobs
        if (sizeof($indeedPaidJobs)) {
            foreach ($indeedPaidJobs as $job) {
                // Check for active company
                if (!in_array($job['user_sid'], $activeCompanies)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company In-active');
                    continue;
                }
                //
                $companySid = $job['user_sid'];
                // Check if company details exists
                $companyPortal = $this->indeed_model->getPortalDetail($companySid);
                //
                if (empty($companyPortal)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company details not found');
                    continue;
                }
                //
                $companyData = $this->indeed_model->getCompanyNameAndJobApproval($companySid);
                $companyName = $companyData['CompanyName'];
                $hasJobApprovalRights = $companyData['has_job_approval_rights'];
                // Check for approval rights
                if ($hasJobApprovalRights ==  1) {
                    $approvalRightStatus = $job['approval_status'];
                    //
                    if ($approvalRightStatus != 'approved') {
                        $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Job not approved');
                        continue;
                    }
                }
                //
                $contactName = $companyData['full_name'];
                $contactPhone = $companyData['phone_number'];
                $contactEmail = $companyData['email'];
                // Check for company indeed details
                $indeedDetails = $this->indeed_model->GetCompanyIndeedDetails($job['user_sid'], $job['sid']);
                //
                if (!empty($indeedDetails['Name'])) {
                    $contactName = $indeedDetails['Name'];
                }
                if (!empty($indeedDetails['Phone'])) {
                    $contactPhone = $indeedDetails['Phone'];
                }
                if (!empty($indeedDetails['Email'])) {
                    $contactEmail = $indeedDetails['Email'];
                }

                //
                $uid = $job['sid'];
                $publishDate = $job['activation_date'];
                $feedData = $this->indeed_model->fetchUidFromJobSid($uid);
                //
                if (sizeof($feedData)) {
                    $uid = $feedData['uid'];
                    $publishDate = $feedData['publish_date'];
                }
                //
                $jobDesc = StripFeedTags($job['JobDescription']);
                $country['country_code'] = "US";
                $state['state_name'] = "";
                $city = "";
                $zipcode = "";
                $salary = "";
                $jobType = "";
                //
                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDesc .= '<br><br>Job Requirements:<br>' . StripFeedTags($job['JobRequirements']);
                }
                //
                if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                    $country = db_get_country_name($job['Location_Country']);
                }
                //
                if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                    $state = db_get_state_name($job['Location_State']);
                }
                //
                if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                    $city = $job['Location_City'];
                }
                //
                if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                    $zipcode = $job['Location_ZipCode'];
                }
                //
                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary = $job['Salary'];
                }
                //


                if (isset($job['JobType']) && $job['JobType'] != NULL) {
                    $job['JobType'] = trim($job['JobType']);
                    if ($job['JobType'] == 'Full Time') {
                        $jobType = "Full Time";
                    } elseif ($job['JobType'] == 'Part Time') {
                        $jobType = "Part Time";
                    } elseif ($job['JobType'] == 'Seasonal') {
                        $jobType = "Seasonal";
                    }
                }


                //
                $JobCategorys = $job['JobCategory'];
                //
                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);
                    $job_category_array = array();
                    //
                    foreach ($cat_id as $id) {
                        $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                        $job_category_array[] = $job_cat_name[0]['value'];
                    }

                    $job_category = implode(', ', $job_category_array);
                }
                //
                $infoArray['Listed']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid']);
                //
                $salary = remakeSalary($salary, $job['SalaryType']);
                //
                $isSponsored = in_array($job['sid'], $jobIds) ? "yes" : "no";
                $hasBudget = in_array($job['sid'], $jobIds) ? $budget[$job['sid']] : "0";
                //
                $jobQuestionnaireUrl = "";
                //
                if ($job["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($job["user_sid"])) {
                    //
                    $this->indeed_model->saveQuestionIntoFile($job['sid'], $job['user_sid'], true);
                    //
                    $jobQuestionnaireUrl = "&indeed-apply-questions=";
                    $jobQuestionnaireUrl .= urlencode(
                        STORE_FULL_URL_SSL . "indeed/$uid/jobQuestions.json"
                    );
                }
                //
                $rows .= "
                    <job>
                        <title><![CDATA[" . $job['Title'] . "]]></title>
                        <sponsored><![CDATA[" . ($isSponsored) . "]]></sponsored>
                        <budget><![CDATA[" . ($hasBudget) . "]]></budget>
                        <date><![CDATA[" . (DateTime::createFromFormat('Y-m-d H:i:s', $publishDate)->format('D, d M Y H:i:s')) . " PST]]></date>
                        <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                        <requisitionid><![CDATA[" . $job['sid'] . "]]></requisitionid>
                        <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></url>
                        <company><![CDATA[" . $companyName . "]]></company>
                        <sourcename><![CDATA[" . $companyName . "]]></sourcename>
                        <city><![CDATA[" . $city . "]]></city>
                        <state><![CDATA[" . $state['state_name'] . "]]></state>
                        <country><![CDATA[" . $country['country_code'] . "]]></country>
                        <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                        <salary><![CDATA[" . $salary . "]]></salary>
                        <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                        <category><![CDATA[" . $job_category . "]]></category>
                        <description><![CDATA[" . $jobDesc . "]]></description>
                        <metadata><![CDATA[]]></metadata>
                        <email><![CDATA[" . $contactEmail . "]]></email>
                        <phonenumber><![CDATA[" . $contactPhone . "]]></phonenumber>
                        <contact><![CDATA[" . $contactName . "]]></contact>
                        <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid) . "&indeed-apply-jobid=" . $uid . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($companySid, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required{$jobQuestionnaireUrl}]]></indeed-apply-data>
                    </job>";

                $totalJobsForFeed++;
            }
        }

        // Loop through Organic Jobs
        if (sizeof($indeedOrganicJobs)) {
            foreach ($indeedOrganicJobs as $job) {
                if (in_array($job['sid'], $jobIds)) {
                    continue;
                }
                // Check for active company
                if (!in_array($job['user_sid'], $activeCompanies)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company In-active');
                    continue;
                }
                //
                $companySid = $job['user_sid'];
                // Check if company details exists
                $companyPortal = $this->indeed_model->getPortalDetail($companySid);
                //
                if (empty($companyPortal)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company details not found');
                    continue;
                }
                //
                $companyData = $this->indeed_model->getCompanyNameAndJobApproval($companySid);
                $companyName = $companyData['CompanyName'];
                $hasJobApprovalRights = $companyData['has_job_approval_rights'];
                // Check for approval rights
                if ($hasJobApprovalRights ==  1) {
                    $approvalRightStatus = $job['approval_status'];
                    //
                    if ($approvalRightStatus != 'approved') {
                        $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Job not approved');
                        continue;
                    }
                }
                //
                $contactName = $companyData['full_name'];
                $contactPhone = $companyData['phone_number'];
                $contactEmail = $companyData['email'];
                // Check for company indeed details
                $indeedDetails = $this->indeed_model->GetCompanyIndeedDetails($job['user_sid'], $job['sid']);
                //
                if (!empty($indeedDetails['Name'])) {
                    $contactName = $indeedDetails['Name'];
                }
                if (!empty($indeedDetails['Phone'])) {
                    $contactPhone = $indeedDetails['Phone'];
                }
                if (!empty($indeedDetails['Email'])) {
                    $contactEmail = $indeedDetails['Email'];
                }
                //
                $uid = $job['sid'];
                $publishDate = $job['activation_date'];
                $feedData = $this->indeed_model->fetchUidFromJobSid($uid);
                //
                if (sizeof($feedData)) {
                    $uid = $feedData['uid'];
                    $publishDate = $feedData['publish_date'];
                }
                //
                $jobDesc = StripFeedTags($job['JobDescription']);
                $country['country_code'] = "US";
                $state['state_name'] = "";
                $city = "";
                $zipcode = "";
                $salary = "";
                $jobType = "";


                //
                if (isset($job['JobRequirements']) && $job['JobRequirements'] != NULL) {
                    $jobDesc .= '<br><br>Job Requirements:<br>' . StripFeedTags($job['JobRequirements']);
                }
                //
                if (isset($job['Location_Country']) && $job['Location_Country'] != NULL) {
                    $country = db_get_country_name($job['Location_Country']);
                }
                //
                if (isset($job['Location_State']) && $job['Location_State'] != NULL) {
                    $state = db_get_state_name($job['Location_State']);
                }
                //
                if (isset($job['Location_City']) && $job['Location_City'] != NULL) {
                    $city = $job['Location_City'];
                }
                //
                if (isset($job['Location_ZipCode']) && $job['Location_ZipCode'] != NULL) {
                    $zipcode = $job['Location_ZipCode'];
                }
                //
                if (isset($job['Salary']) && $job['Salary'] != NULL) {
                    $salary = $job['Salary'];
                }

                //
                if (isset($job['JobType']) && $job['JobType'] != NULL) {
                    $job['JobType'] = trim($job['JobType']);
                    if ($job['JobType'] == 'Full Time') {
                        $jobType = "Full Time";
                    } elseif ($job['JobType'] == 'Part Time') {
                        $jobType = "Part Time";
                    } elseif ($job['JobType'] == 'Seasonal') {
                        $jobType = "Seasonal";
                    }
                }


                //
                $JobCategorys = $job['JobCategory'];
                //
                if ($JobCategorys != null) {
                    $cat_id = explode(',', $JobCategorys);
                    $job_category_array = array();
                    //
                    foreach ($cat_id as $id) {
                        $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                        $job_category_array[] = $job_cat_name[0]['value'];
                    }

                    $job_category = implode(', ', $job_category_array);
                }
                //
                $infoArray['Listed']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid']);
                //
                $salary = remakeSalary($salary, $job['SalaryType']);
                //
                $isSponsored = in_array($job['sid'], $jobIds) ? "yes" : "no";
                $hasBudget = in_array($job['sid'], $jobIds) ? $budget[$job['sid']] : "0";
                //
                $jobQuestionnaireUrl = "";
                //
                if ($job["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($job["user_sid"])) {
                    //
                    if ($this->indeed_model->saveQuestionIntoFile($job['sid'], $job['user_sid'], true)) {
                        $jobQuestionnaireUrl = "&indeed-apply-questions=";
                        $jobQuestionnaireUrl .= urlencode(
                            STORE_FULL_URL_SSL . "indeed/$uid/jobQuestions.json"
                        );
                    }
                }
                //
                $rows .= "
                    <job>
                        <title><![CDATA[" . $job['Title'] . "]]></title>
                        <sponsored><![CDATA[" . ($isSponsored) . "]]></sponsored>
                        <budget><![CDATA[" . ($hasBudget) . "]]></budget>
                        <date><![CDATA[" . (DateTime::createFromFormat('Y-m-d H:i:s', $publishDate)->format('D, d M Y H:i:s')) . " PST]]></date>
                        <referencenumber><![CDATA[" . $uid . "]]></referencenumber>
                        <requisitionid><![CDATA[" . $job['sid'] . "]]></requisitionid>
                        <url><![CDATA[" . STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid . "]]></url>
                        <company><![CDATA[" . $companyName . "]]></company>
                        <sourcename><![CDATA[" . $companyName . "]]></sourcename>
                        <city><![CDATA[" . $city . "]]></city>
                        <state><![CDATA[" . $state['state_name'] . "]]></state>
                        <country><![CDATA[" . $country['country_code'] . "]]></country>
                        <postalcode><![CDATA[" . $zipcode . "]]></postalcode>
                        <salary><![CDATA[" . $salary . "]]></salary>
                        <jobtype><![CDATA[" . $jobType . "]]></jobtype>
                        <category><![CDATA[" . $job_category . "]]></category>
                        <description><![CDATA[" . $jobDesc . "]]></description>
                        <metadata><![CDATA[]]></metadata>
                        <email><![CDATA[" . $contactEmail . "]]></email>
                        <phonenumber><![CDATA[" . $contactPhone . "]]></phonenumber>
                        <contact><![CDATA[" . $contactName . "]]></contact>
                        <indeed-apply-data><![CDATA[indeed-apply-joburl=" . urlencode(STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uid) . "&indeed-apply-jobid=" . $uid . "&indeed-apply-jobtitle=" . urlencode(db_get_job_title($companySid, $job['Title'], $city, $state['state_name'], $country['country_code'])) . "&indeed-apply-jobcompanyname=" . urlencode($companyName) . "&indeed-apply-joblocation=" . urlencode($city . "," . $state['state_name'] . "," . $country['country_code']) . "&indeed-apply-apitoken=56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781&indeed-apply-posturl=" . urlencode(STORE_FULL_URL_SSL . "indeed_feed/indeedPostUrl") . "&indeed-apply-phone=required{$jobQuestionnaireUrl}]]></indeed-apply-data>
                    </job>";

                $totalJobsForFeed++;
            }
        }

        // Post data to browser
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
        $det .=  trim($rows);
        $det .=  '</source>';
        echo trim($det);
        mail(TO_EMAIL_DEV, 'New Indeed hit XML: ' . date('Y-m-d H:i:s'), print_r($infoArray, true));
        exit;
    }

    private function isActiveFeed()
    {
        $this->load->model('all_job_feed_model');
        $validSlug = $this->all_job_feed_model->check_for_slug('indeed_new');
        if (!$validSlug) {
            echo '<h1>404. Feed Not Found!</h1>';
            die();
        }

        return $validSlug;
    }

    public function test()
    {
        $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
        // Get Indeed Organic Jobs
        $indeedOrganicJobs = $this->indeed_model->getIndeedOrganicJobs($featuredJobs);

        // Loop through Organic Jobs
        if (sizeof($indeedOrganicJobs)) {
            foreach ($indeedOrganicJobs as $job) {

                if ($job["user_sid"] != 46819) {
                    continue;
                }
                $this->indeed_model->addJobToQueue(
                    $job["sid"],
                    $job["user_sid"],
                    $job["approval_status"]
                );
            }
        }
    }


    /**
     * Indeed Organic/Paid Jobs
     *
     *  @return VOID
     */
    private function jobsForQueue()
    {
        //
        $featuredJobs = $this->all_feed_model->get_all_company_jobs_ams();
        // Get Indeed Paid Job Ids
        $indeedPaidJobIds = $this->indeed_model->getIndeedPaidJobIds();
        $indeedPaidJobs = [];
        if (sizeof($indeedPaidJobIds['Ids'])) {
            // Get Indeed Paid Jobs
            $jobIds = $indeedPaidJobIds['Ids'];
            $budget = $indeedPaidJobIds['Budget'];
            $indeedPaidJobs = $this->indeed_model->getIndeedPaidJobs();
        } else $budget = $jobIds = array();
        // Get Indeed Organic Jobs
        $indeedOrganicJobs = $this->indeed_model->getIndeedOrganicJobs($featuredJobs);
        // Get Active companies
        $activeCompanies = $this->indeed_model->getAllActiveCompanies($sid);
        //
        $rows = '';
        //
        $infoArray = array();
        $infoArray['Skipped']['Paid'] = array();
        $infoArray['Skipped']['Organic'] = array();
        $infoArray['Listed']['Paid'] = array();
        $infoArray['Listed']['Organic'] = array();
        //
        $totalJobsForFeed = 0;

        $jobsForQueues = [];

        // Loop through Organic Jobs
        if (sizeof($indeedPaidJobs)) {
            foreach ($indeedPaidJobs as $job) {
                // Check for active company
                if (!in_array($job['user_sid'], $activeCompanies)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company In-active');
                    continue;
                }
                //
                $companySid = $job['user_sid'];
                // Check if company details exists
                $companyPortal = $this->indeed_model->getPortalDetail($companySid);
                //
                if (empty($companyPortal)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company details not found');
                    continue;
                }
                //
                $companyData = $this->indeed_model->getCompanyNameAndJobApproval($companySid);
                $companyName = $companyData['CompanyName'];
                $hasJobApprovalRights = $companyData['has_job_approval_rights'];
                // Check for approval rights
                if ($hasJobApprovalRights ==  1) {
                    $approvalRightStatus = $job['approval_status'];
                    //
                    if ($approvalRightStatus != 'approved') {
                        $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Job not approved');
                        continue;
                    }
                }
                $jobsForQueues[] = ["job_sid" => $job["sid"], "company_sid" => $companySid];
            }
        }

        // Loop through Organic Jobs
        if (sizeof($indeedOrganicJobs)) {
            foreach ($indeedOrganicJobs as $job) {
                if (in_array($job['sid'], $jobIds)) {
                    continue;
                }
                // Check for active company
                if (!in_array($job['user_sid'], $activeCompanies)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company In-active');
                    continue;
                }
                //
                $companySid = $job['user_sid'];
                // Check if company details exists
                $companyPortal = $this->indeed_model->getPortalDetail($companySid);
                //
                if (empty($companyPortal)) {
                    $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Company details not found');
                    continue;
                }
                //
                $companyData = $this->indeed_model->getCompanyNameAndJobApproval($companySid);
                $companyName = $companyData['CompanyName'];
                $hasJobApprovalRights = $companyData['has_job_approval_rights'];
                // Check for approval rights
                if ($hasJobApprovalRights ==  1) {
                    $approvalRightStatus = $job['approval_status'];
                    //
                    if ($approvalRightStatus != 'approved') {
                        $infoArray['Skipped']['Paid'] = array('jobSid' => $job['sid'], 'companySid' => $job['user_sid'], 'Cause' => 'Job not approved');
                        continue;
                    }
                }
                $jobsForQueues[] = ["job_sid" => $job["sid"], "company_sid" => $companySid];
            }
        }
        _e(count($jobsForQueues));
        exit;
    }
}

if (!function_exists('remakeSalary')) {
    function remakeSalary($salary, $jobType)
    {
        $salary = trim(str_replace([',', 'k', 'K'], ['', '000', '000'], $salary));
        $jobType = strtolower($jobType);
        //
        if (preg_match('/year|yearly/', $jobType)) $jobType = 'per year';
        else if (preg_match('/month|monthly/', $jobType)) $jobType = 'per month';
        else if (preg_match('/week|weekly/', $jobType)) $jobType = 'per week';
        else if (preg_match('/hour|hourly/', $jobType)) $jobType = 'per hour';
        else $jobType = 'per year';
        //
        if ($salary == '') return $salary;
        //
        if (strpos($salary, '$') === FALSE)
            $salary = preg_replace('/(?<![^ ])(?=[^ ])(?![^0-9])/', '$', $salary);
        //
        $salary = $salary . ' ' . $jobType;
        //
        return $salary;
    }
}

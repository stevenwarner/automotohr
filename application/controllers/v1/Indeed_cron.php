<?php defined('BASEPATH') || exit('No direct script access allowed');


class Indeed_cron extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Cron job to push the jobs to Indeed
     *
     * @param string $verificationToken
     */
    public function jobSync()
    {
        // load the model

        $this->load->model("Indeed_model", "indeed_model");
        // check if we can moved forward
        if ($this->indeed_model->checkQueue()) {
            exit(0);
        }
        $this->load->model("all_feed_model");
        // get the jobs
        $jobsInQueue = $this->indeed_model->getQueueJobs(10, false);

        // when there is no jobs in queue
        if (!$jobsInQueue) {
            echo "No job found.";
            exit(0);
        }


        //$this->indeed_model->markIsprocessing($v['sid']);

        // set the default portal data
        $companiesPortalData = [];
        // set default company indeed information
        $companyIndeedContactDetails = [];
        //
        $jobs = '';

        // load library
        $this->load->library(
            "Indeed_lib",
            "",
            "indeed_lib"
        );


        $createMultipleJobs = true;
        // loop through the jobs


        $bulkPostJobIds = [];

        foreach ($jobsInQueue as $v) {


            // set the job id
            $jobId = $v["job_sid"];
            // get the job details
            $jobDetails = $this->indeed_model
                ->getJobDetailsForIndeed($jobId);

            // delete the job from queue when no job is found
            // or company is not paid or inactive
            if (!$jobDetails) {
                $this->indeed_model
                    ->deleteJobToQueue($jobId);
                continue;
            }
            // set company id
            $companyId = $jobDetails["user_sid"];
            // check company portal data in cache
            // get the portal data
            $companyPortal = $companiesPortalData[$companyId] ??
                $this->indeed_model->getPortalDetail($companyId);
            // update the cache
            $companiesPortalData[$companyId] = $companyPortal;
            // also delete the job if company is not found.
            if (!$companyPortal) {
                $this->indeed_model
                    ->deleteJobToQueue($jobId);
                continue;
            }


            //
            $contactName = clean($jobDetails['contact_name']);
            $contactEmail = clean($jobDetails['contact_email']);
            // Check for company indeed details
            $indeedDetails = $companyIndeedContactDetails[$companyId] ??
                $this->indeed_model->GetCompanyIndeedDetails($companyId, $jobId);
            // update cache
            $companyIndeedContactDetails[$companyId] = $indeedDetails;

            // update the contact details
            if (clean($indeedDetails['Name'])) {
                $contactName = clean($indeedDetails['Name']);
            }
            if (clean($indeedDetails['Phone'])) {
                $contactPhone = clean($indeedDetails['Phone']);
            }
            if ($indeedDetails['Email']) {
                $contactEmail = $indeedDetails['Email'];
            }

            if ($contactEmail == '' || $contactEmail == null) {
                $contactEmail = "test@automotohr.com";
            }


            // handle the job UUID
            $uuid = $jobId;
            $publishDate = $jobDetails['activation_date'];
            $feedData = $this->indeed_model->fetchUidFromJobSid($jobId);
            //
            if ($feedData) {
                $uuid = $feedData['uid'];
                $publishDate = $feedData['publish_date'];
            }



            //Expired Jobs
            if ($v['is_expired'] == 1) {
                $sourcedPostingId = $this->indeed_model->getSourcedPostingId($uuid);

                $sourcedPostingId = '{ sourcedPostingId: "' . $sourcedPostingId . '" }';
                if ($sourcedPostingId != '') {
                    $this->expiredJobs($sourcedPostingId, $uuid);
                }
                continue;
            }




            // convert publish date
            $publishDate = formatDateToDB(
                $publishDate,
                DB_DATE_WITH_TIME,
                "Y-m-d\TH:i\Z"
            );

            $jobDesc = clean(StripFeedTags(nl2br($jobDetails['JobDescription'])));
            $country['country_code'] = "US";
            $state['state_name'] = "";
            $city = "";
            $zipcode = "";
            $salary = "";
            //
            if (isset($jobDetails['JobRequirements']) && $jobDetails['JobRequirements'] != null) {
                $jobDesc .= '<br><br>Job Requirements:<br>' . clean(StripFeedTags($jobDetails['JobRequirements']));
            }
            //
            if (isset($jobDetails['Location_Country']) && $jobDetails['Location_Country'] != null) {
                $country = db_get_country_name($jobDetails['Location_Country']);
            }
            //
            if (isset($jobDetails['Location_State']) && $jobDetails['Location_State'] != null) {
                $state = db_get_state_name($jobDetails['Location_State']);
            }
            //
            if (isset($jobDetails['Location_City']) && $jobDetails['Location_City'] != null) {
                $city = clean($jobDetails['Location_City']);
            }
            //
            if (isset($jobDetails['Location_ZipCode']) && $jobDetails['Location_ZipCode'] != null) {
                $zipcode = $jobDetails['Location_ZipCode'];
            }
            //
            if (isset($jobDetails['Salary']) && $jobDetails['Salary'] != null) {
                $salary = $jobDetails['Salary'];
            }


            //
            $salary = remakeSalary($salary, $jobDetails['SalaryType']);
            //
            $jobQuestionnaireUrl = "";
            //
            if ($jobDetails["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($companyId)) {
                //
                if ($this->indeed_model->saveQuestionIntoFile($jobId, $companyId, true)) {
                    $jobQuestionnaireUrl = STORE_FULL_URL_SSL_FOR_INDEED . "indeed/$uuid/jobQuestions.json";
                }
            }


            //
            $companyName = '';

            if ($jobDetails["CompanyName"] != '' || $jobDetails["CompanyName"] != null) {

                $companyName = clean($jobDetails["CompanyName"]);
            } else {
                $companyName = getCompanyNameBySid($companyId);
            }

            $replaceArray = [
                '\title' => clean($jobDetails["Title"]),
                '\description' => clean(html_escape(
                    preg_replace("/\s+/", "", nl2br($jobDesc))
                )),
                '\country' => $country["country_code"],
                '\cityRegionPostal' => $city . ", " . $state["state_code"] . " " . $zipcode,
                '\currency' => 'USD',
                '\minimumMinor' => $salary["salary"],
                '\maximumMinor' => $salary["salary"],
                '\period' => $salary["type"],
                '\companyName' => $companyName,
                '\sourceName' => $companyName,
                '\sourceType' => 'Employer',
                '\contactName' => $contactName,
                '\contactEmail' => $contactEmail,
                '\jobPostingId' => $uuid,
                '\datePublished' => $publishDate,
                '\url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uuid,
                '\apiToken' => '56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781',
                '\postUrl' => STORE_DOMAIN_FOR_INDEED . "/indeed_feed/indeedPostUrl",
                '\applyQuestions' => $jobQuestionnaireUrl ? 'applyQuestions: "' . $jobQuestionnaireUrl . '"' : "",
                '\resumeRequired' => "NO",
                '\phoneRequired' => "YES",
                '\contactPhone' => $contactPhone,

            ];


            // create single job
            if ($createMultipleJobs == false) {
                $jobs = $this->createJobBody($replaceArray);
                //
                $this->setcreateJobCall($jobs, $uuid);
                continue;
            } else {
                $jobs .= $this->createJobBody($replaceArray);
                //  bulkPostJobIds
                array_push($bulkPostJobIds, $uuid);
            }
        }

        // set the call For MultiJob 
        if ($createMultipleJobs == true) {
            $this->setcreateJobCall($jobs, $bulkPostJobIds);
        }

        echo "Done";
    }



    //
    private function setcreateJobCall($jobs, $jobSid)
    {

        $query = <<<'GRAPHQL'
        mutation {
            createSourcedJobPostings(
                input: {
                    jobPostings: [
                        \jobs
                    ]
                }
            ) {
            results {
                jobPosting {
                    sourcedPostingId
                }
            }
            }
        }
        GRAPHQL;

        //
        $query = str_replace("\jobs", $jobs, $query);

        $response = $this->indeed_lib->jobSyncApi($query);

        //
        if (!empty($response['error'])) {
            $postingData['errors'] = json_encode($response['error']);
            if (is_array($jobSid)) {
                $postingData['job_id'] = $jobSid[0];
            } else {
                $postingData['job_id'] = $jobSid;
            }

            $this->indeed_model->saveIndeedJobPostingResponse($postingData);
        }

        if (!empty($response['resultArray']['data']['createSourcedJobPostings']['results'])) {

            $i = 0;

            foreach ($response['resultArray']['data']['createSourcedJobPostings']['results'] as $resultdata) {

                if (!empty($resultdata)) {

                    foreach ($resultdata as $rowJobPosting) {

                        $postingData['sourced_posting_Id'] = $rowJobPosting['sourcedPostingId'];
                        $postingData['errors'] = '';
                        if (is_array($jobSid)) {
                            $postingData['job_id'] = $jobSid[$i];
                        } else {
                            $postingData['job_id'] = $jobSid;
                        }

                        $this->indeed_model->saveIndeedJobPostingResponse($postingData);
                        $i++;
                    }
                }
            }
        }
    }

    //
    private function createJobBody($replaceArray)
    {

        $singleJob = <<<'GRAPHQL'
        {
            body: {
                title: "\title"
                description: "\description"
                location: {
                    country: "\country"
                    cityRegionPostal: "\cityRegionPostal"
                }
                benefits: []
                salary: {
                    currency: "\currency"
                    minimumMinor: \minimumMinor
                    maximumMinor: \maximumMinor
                    period: "\period"
                }
            }
            metadata: {
                jobSource: {
                    companyName: "\companyName"
                    sourceName: "\sourceName"
                    sourceType: "\sourceType"
                }
                contacts: {
                    contactType: "contact"
                    contactInfo: {
                        contactName: "\contactName"
                        contactEmail: "\contactEmail"
                    }
                }
                jobPostingId: "\jobPostingId"
                datePublished: "\datePublished"
                url: "\url"
            }
            applyMethod: {
                indeedApply: {
                    apiToken: "\apiToken"
                    postUrl: "\postUrl"
                    \applyQuestions
                    phoneRequired: YES
                }
            }
        }
        GRAPHQL;

        $jobs = str_replace(
            array_keys($replaceArray),
            $replaceArray,
            $singleJob
        );

        return  $jobs;
    }


    //
    private function expiredJobs($expiredJobsUid, $jobSid)
    {

        $expireJobquery = <<<'GRAPHQL'
        mutation {
        jobsIngest {
            expireSourcedJobsBySourcedPostingId(
                input: {
                    jobs: [
                         \jobsExpired
                    ]
                }
            ) {
            results {
                    trackingKey
            }
            }
        }
        }
        GRAPHQL;

        $expiredJobquery = str_replace("\jobsExpired", rtrim($expiredJobsUid, ','), $expireJobquery);

        $response = $this->indeed_lib->jobSyncApi($expiredJobquery);

        if (!empty($response['error'])) {
            $postingData['errors'] = json_encode($response['error']);
            $this->indeed_model->updateIndeedJobPostingResponse($postingData, $jobSid);
        }

        if (!empty($response['resultArray']['data']['jobsIngest']['expireSourcedJobsBySourcedPostingId'])) {

            foreach ($response['resultArray']['data']['jobsIngest']['expireSourcedJobsBySourcedPostingId'] as $resultdata) {

                if (!empty($resultdata)) {

                    foreach ($resultdata as $rowJobPosting) {

                        $postingData['tracking_Key'] = $rowJobPosting['trackingKey'];
                        $postingData['updated_at'] = date('Y-m-d H:i:s');

                        $this->indeed_model->updateIndeedJobPostingResponse($postingData, $jobSid);
                    }
                }
            }
        }
    }
}


//
if (!function_exists('remakeSalary')) {
    function remakeSalary($salary, $jobType)
    {
        $salary = trim(str_replace([',', 'k', 'K'], ['', '000', '000'], $salary));
        $jobType = strtolower($jobType);
        //
        if (preg_match('/year|yearly/', $jobType)) $jobType = 'YEAR';
        else if (preg_match('/month|monthly/', $jobType)) $jobType = 'MONTH';
        else if (preg_match('/week|weekly/', $jobType)) $jobType = 'WEEK';
        else if (preg_match('/hour|hourly/', $jobType)) $jobType = 'HOUR';
        else $jobType = 'YEAR';
        //
        if ($salary == '') return $salary;
        //
        if (strpos($salary, '$') === false)
            $salary = preg_replace('/(?<![^ ])(?=[^ ])(?![^0-9])/', '', $salary);
        //
        return ["salary" => $salary, "type" => $jobType];
    }
}

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
        $jobsInQueue = $this->indeed_model->getQueueJobs(1);
        // when there is no jobs in queue
        if (!$jobsInQueue) {
            echo "No job found.";
            exit(0);
        }
        // set the default portal data
        $companiesPortalData = [];
        // set default company indeed information
        $companyIndeedContactDetails = [];
        //
        $jobs = '';
        // loop through the jobs
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
            $contactName = $jobDetails['contact_name'];
            $contactPhone = $jobDetails['contact_phone'];
            $contactEmail = $jobDetails['contact_email'];
            // Check for company indeed details
            $indeedDetails = $companyIndeedContactDetails[$companyId] ??
                $this->indeed_model->GetCompanyIndeedDetails($companyId, $jobId);
            // update cache
            $companyIndeedContactDetails[$companyId] = $indeedDetails;

            // update the contact details
            if ($indeedDetails['Name']) {
                $contactName = $indeedDetails['Name'];
            }
            if ($indeedDetails['Phone']) {
                $contactPhone = $indeedDetails['Phone'];
            }
            if ($indeedDetails['Email']) {
                $contactEmail = $indeedDetails['Email'];
            }
            $contactEmail = "test@automotohr.com";

            // handle the job UUID
            $uuid = $jobId;
            $publishDate = $jobDetails['activation_date'];
            $feedData = $this->indeed_model->fetchUidFromJobSid($jobId);
            //
            if ($feedData) {
                $uuid = $feedData['uid'];
                $publishDate = $feedData['publish_date'];
            }

            // convert publish date
            $publishDate = formatDateToDB(
                $publishDate,
                DB_DATE_WITH_TIME,
                "Y-m-d\TH:i\Z"
            );

            $jobDesc = StripFeedTags(nl2br($jobDetails['JobDescription']));
            $country['country_code'] = "US";
            $state['state_name'] = "";
            $city = "";
            $zipcode = "";
            $salary = "";
            $jobType = "";


            //
            if (isset($jobDetails['JobRequirements']) && $jobDetails['JobRequirements'] != null) {
                $jobDesc .= '<br><br>Job Requirements:<br>' . StripFeedTags($jobDetails['JobRequirements']);
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
                $city = $jobDetails['Location_City'];
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
            if (
                isset($jobDetails['JobType']) && $jobDetails['JobType'] != null
            ) {
                $jobDetails['JobType'] = trim($jobDetails['JobType']);
                if (
                    $jobDetails['JobType'] == 'Full Time'
                ) {
                    $jobType = "Full Time";
                } elseif ($jobDetails['JobType'] == 'Part Time') {
                    $jobType = "Part Time";
                } elseif ($jobDetails['JobType'] == 'Seasonal') {
                    $jobType = "Seasonal";
                }
            }


            //
            $JobCategorys = $jobDetails['JobCategory'];
            //
            if ($JobCategorys != null) {
                $cat_id = explode(
                    ',',
                    $JobCategorys
                );
                $job_category_array = array();
                //
                foreach ($cat_id as $id) {
                    $job_cat_name = $this->all_feed_model->get_job_category_name_by_id($id);
                    $job_category_array[] = $job_cat_name[0]['value'];
                }

                $job_category = implode(', ', $job_category_array);
            }

            //
            $salary = remakeSalary($salary, $jobDetails['SalaryType']);
            //
            $jobQuestionnaireUrl = "";
            //
            if ($jobDetails["questionnaire_sid"] || $this->indeed_model->hasEEOCEnabled($companyId)) {
                //
                if ($this->indeed_model->saveQuestionIntoFile($jobId, $companyId, true)) {
                    $jobQuestionnaireUrl = STORE_FULL_URL_SSL . "indeed/$uuid/jobQuestions.json";
                }
            }

            $replaceArray = [
                '\title' => $jobDetails["Title"],
                '\description' => html_escape(
                    preg_replace("/\s+/", "", nl2br($jobDesc))
                ),
                '\country' => $country["country_code"],
                '\cityRegionPostal' => $city . ", " . $state["state_code"] . " " . $zipcode,
                '\currency' => 'USD',
                '\minimumMinor' => $salary["salary"],
                '\maximumMinor' => $salary["salary"],
                '\period' => $salary["type"],
                '\companyName' => $jobDetails["CompanyName"],
                '\sourceName' => $jobDetails["CompanyName"],
                '\sourceType' => 'Employer',
                '\contactName' => $contactName,
                '\contactEmail' => $contactEmail,
                '\jobPostingId' => $uuid,
                '\datePublished' => $publishDate,
                '\url' => STORE_PROTOCOL_SSL . $companyPortal['sub_domain'] . "/job_details/" . $uuid,
                '\apiToken' => '56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781',
                '\postUrl' => STORE_FULL_URL . "/indeed_feed/indeedPostUrl",
                '\applyQuestions' => $jobQuestionnaireUrl ? 'applyQuestions: "' . $jobQuestionnaireUrl . '"' : "",
                '\resumeRequired' => "NO",
                '\phoneRequired' => "YES",
            ];

            // create a job
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

            // replace the variables
            $jobs .= str_replace(
                array_keys($replaceArray),
                $replaceArray,
                $singleJob
            );
        }

        // set the call
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
        // replace variables
        $query = str_replace("\jobs", $jobs, $query);


        _e($query, true, true);

        // load library
        $this->load->library(
            "Indeed_lib",
            "",
            "indeed_lib"
        );
        //
        $response = $this->indeed_lib->jobSyncApi($query);

        if ($response["errors"]) {
            // revert the effected jobs
        }

        _e($response, true);
    }
}

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

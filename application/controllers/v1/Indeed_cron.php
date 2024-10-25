<?php defined('BASEPATH') || exit('No direct script access allowed');
/**
 * Indeed Cron job
 *
 * @version 1.0
 */

class Indeed_cron extends CI_Controller
{
    /**
     * holds the jobs count
     * @var int
     */
    private $numberOfJobsForQueue = 20;

    /**
     * holds the jobs
     * @var array
     */
    private $jobs = [];

    /**
     * holds the current job
     * @var array
     */
    private $job = [];

    /**
     * holds the API token
     * @var string
     */
    private $apiToken = "56010deedbac7ff45f152641f2a5ec8c819b17dea29f503a3ffa137ae3f71781";

    /**
     * holds the job body
     * @var string
     */
    private $jobBody = "";

    /**
     * holds the job ids
     * @var array
     */
    private $jobIds = [];

    /**
     * holds the expired job body
     * @var string
     */
    private $expiredJobBody = "";

    /**
     * holds the expired jobs
     * @var array
     */
    private $expiredJobs = [];

    /**
     * holds the expired job ids
     * @var array
     */
    private $expiredJobIds = [];

    /**
     * holds the company portal data
     * @var array
     */
    private $portalData = [];

    /**
     * holds the company indeed contact details
     * @var array
     */
    private $indeedContactDetails = [];

    /**
     * Main entry point
     */
    public function __construct()
    {
        parent::__construct();
        // load the indeed model
        $this
            ->load
            ->model(
                "Indeed_model",
            first
                "indeed_model"
            );
        // load the all feed model
        $this
            ->load
            ->model(
                "all_feed_model"
            );
        // load indeed library
        $this->load->library(
            "Indeed_lib",
            "",
            "indeed_lib"
        );
    }

    /**
     * Cron job to push the jobs to Indeed
     *
     * @param string $verificationToken
     */
    public function processJobSync()
    {
        // load the queued jobs
        $this->loadJobs();
        // load portal data
        $this
            ->loadPortalData(
                array_column(
                    $this->jobs,
                    "user_sid"
                )
            );
        // iterate through jobs
        foreach ($this->jobs as $job) {
            $this->jobBody = "";
            // set the job
            $this->job = $job;
            // set the default errors
            $this->job["errors"] = [];
            // set the uuid and publish date
            $this->setJobUUIdAndPublishDate();
            // check if job is expired
            if ($job["is_expired"]) {
                $this->expiredJobs[] = $this->job;
                continue;
            }
            // generate the add/edit job body
            $this->generateJobBody();
            //
            if ($this->job["errors"]) {
                $this
                    ->indeed_model
                    ->saveErrors(
                        $this->job["sid"],
                        $this->job["errors"]
                    );
                    continue;
            }
            // create/update jobs on Indeed
            $this->sendJobsToIndeed($this->job["sid"]);

            usleep(200);
        }
        // delete jobs from Indeed
        $this->deleteJobsFromIndeed();

        //
        exit("All done");
    }


    /************************************************************************* */
    /* Private Events  */
    /************************************************************************* */


    /**
     * load the jobs
     */
    private function loadJobs()
    {
        // get the jobs
        $this->jobs = $this
            ->indeed_model
            ->getJobQueueForActiveJobs(
                $this->numberOfJobsForQueue
            );
        // when there is no jobs in queue
        if (!$this->jobs) {
            echo "No jobs found.";
            exit(0);
        }
    }


    /**
     * set the portal details
     *
     * @param array $companyIds
     */
    private function loadPortalData(
        array $companyIds
    ) {
        $this->portalData = $this
            ->indeed_model
            ->getPortalData(
                $companyIds
            );
    }

    /**
     * set the job uuid and
     * publish date
     */
    private function setJobUUIdAndPublishDate()
    {
        // set the job uuid and publish date
        $this->job["uuid"] = $this->job['job_sid'];
        $this->job["publishDate"] = $this->job['activation_date'];
    }

    /**
     * generate the job body
     *
     * @method setJobToExpireAndDeleteIt
     * @method setJobDataArray
     * @return bool
     */
    private function generateJobBody(): bool
    {
        // check if portal data
        if (!$this->portalData[$this->job["user_sid"]]) {
            // add the job to expire array
            // and delete it from queue
            $this->expiredJobs[] = $this->job;
            //
            return false;
        }
        // load indeed contact details
        $this->loadIndeedContactDetails();
        // set data array
        $this->setJobDataArray();
        //
        $this->setIndeedContactDetails();
        //
        $this->convertDataToJobToGQL();
        // set the job
        $this->jobIds[] =
            [
                "sid" => $this->job["sid"],
                "job_sid" => $this->job["job_sid"],
                "uuid" => $this->job["uuid"],
            ];
        //
        return true;
    }

    /**
     * load the Indeed contact details
     *
     * @return bool
     */
    private function loadIndeedContactDetails()
    {
        // check if already loaded for company
        if (isset($this->indeedContactDetails[$this->job["user_sid"]])) {
            return true;
        }
        // update cache
        $this->indeedContactDetails[$this->job["user_sid"]] =
            $this
            ->indeed_model
            ->GetCompanyIndeedDetails(
                $this->job["user_sid"],
                $this->job["job_sid"]
            );
    }

    /**
     * get the data shell
     */
    private function setJobDataArray()
    {
        // get the salary
        $salaryArray = $this->getSalary();
        // get the employer details
        $employerDetails = $this->portalData[$this->job["user_sid"]];
        //
        $this->job["data"] = [
            '\title' => $this->job["Title"],
            '\description' => $this->getDescription(),
            '\jobTypes' => $this->getJobType(),
            '\country' => "US",
            '\cityRegionPostal' => $this->getRegionPostal(),
            '\currency' => 'USD',
            '\minimumMinor' => $salaryArray["min"],
            '\maximumMinor' => $salaryArray["max"],
            '\period' => $salaryArray["period"],
            '\companyName' => $employerDetails["CompanyName"],
            '\sourceName' => $employerDetails["CompanyName"],
            '\jobRequisitionId' => $this->job["user_sid"],
            '\sourceType' => 'Employer',
            '\contactName' => "",
            '\contactEmail' => "",
            '\contactPhone' => "",
            '\jobPostingId' => $this->job["uuid"],
            '\datePublished' => $this->job["publishDate"] ? convertTimeZone(
                $this->job["publishDate"],
                DB_DATE_WITH_TIME,
                "PST",
                "UTC",
                true,
                "Y-m-d\TH:i\Z"
            ) : "",
            '\url' => $this->getJobURL(),
            '\apiToken' => $this->apiToken,
            '\postUrl' => $this->getPostURL(),
            '\applyQuestions' => $this->getApplyQuestionnaire(),
            '\resumeRequired' => "YES",
            '\phoneRequired' => "YES",
        ];

        if (!$employerDetails["is_paid"]) {
            $this->job["data"]["\sourceName"] = "automotohr-sandbox";
        }
    }

    /**
     * set the Indeed contact details
     *
     * @return bool
     */
    private function setIndeedContactDetails()
    {
        // update job data array
        $indeedContactDetails = $this->indeedContactDetails[$this->job["user_sid"]];
        // update the contact details
        if (($indeedContactDetails['Name'])) {
            $this->job["data"]["\contactName"] =
                ($indeedContactDetails['Name']);
        }
        if (($indeedContactDetails['Phone'])) {
            $this->job["data"]["\contactPhone"] =
                ($indeedContactDetails['Phone']);
        }
        if ($indeedContactDetails['Email']) {
            $this->job["data"]["\contactEmail"] =
                $indeedContactDetails['Email'];
        }

        // check and set errors
        if (!$this->job["data"]["\contactEmail"]) {
            $this->job["errors"]["contact_email"] = "Contact email is missing";
        }
    }

    /**
     * get the description
     * 
     * @return string
     */
    private function getDescription(): string
    {
        $jd = StripFeedTags(nl2br(
            $this->job['JobDescription']
        ));
        //
        if ($this->job['JobRequirements']) {
            $jd .= '<br><br>Job Requirements:<br>' . StripFeedTags(
                $this->job['JobRequirements']
            );
        }
        // remove line breaks
        $jd = str_replace(
            [
                "\r",
                "\n",
                "\f",
                "\t",
                "\ooo",
                "\xhh",
                "\$",
                "\v",
                "\0",
            ],
            "",
            $jd
        );
        //
        return addslashes($jd);
    }

    /**
     * get the regional
     * 
     * @return string
     */
    private function getRegionPostal(): string
    {
        // set default
        $regional = "";
        // check and set city
        if ($this->job["Location_City"]) {
            $regional .= $this->job['Location_City'] . ", ";
        }
        // check and set state
        if ($this->job["Location_State"]) {
            $regional .= db_get_state_name(
                $this->job['Location_State']
            )["state_code"] . " ";
        }
        // check and set postal code
        if ($this->job["Location_ZipCode"]) {
            $regional .= $this->job['Location_ZipCode'];
        }
        //
        return rtrim(
            rtrim(
                $regional
            ),
            ","
        );
    }

    /**
     * get the salary
     *
     * @return array
     */
    private function getSalary(): array
    {
        // set default
        $salaryArray = [
            "salary" => 0,
            "min" => 0,
            "max" => 0,
            "period" => "",
        ];
        //
        if ($this->job["Salary"]) {
            //
            $salaryArray = setTheSalary(
                $this->job["Salary"],
                $this->job['SalaryType']
            );
        }
        //
        if ($salaryArray["min"] == "0") {
            $this->job["errors"]["salary"] = "Salary is either missing or misformed.";
        }
        //
        return $salaryArray;
    }

    /**
     * get the post URL
     *
     * @return string
     */
    private function getPostURL(): string
    {
        return STORE_FULL_URL_SSL . "webhook/indeed/applicant";
    }

    /**
     * get the post URL
     *
     * @return string
     */
    private function getJobURL(): string
    {
        return STORE_PROTOCOL_SSL
            . $this->portalData[$this->job["user_sid"]]["sub_domain"]
            . "/job_details/"
            . $this->job["uuid"];
    }

    /**
     * get the questionnaire URL
     *
     * @return string
     */
    private function getApplyQuestionnaire(): string
    {
        // set the url to empty
        $jobQuestionnaireUrl = "";
        // check if questionnaire is linked
        // or EEO is enabled.
        if (
            $this->job["questionnaire_sid"] ||
            $this
            ->indeed_model
            ->hasEEOCEnabled(
                $this->job["user_sid"]
            )
        ) {
            // create a file from teh questionnaire
            // and EEO
            if (
                $this
                ->indeed_model
                ->saveQuestionIntoFile(
                    $this->job['job_sid'],
                    $this->job['user_sid'],
                    true
                )
            ) {
                $url =
                    STORE_FULL_URL_SSL . "indeed/{$this->job["uuid"]}/jobQuestions.json";
                $jobQuestionnaireUrl =  'applyQuestions: "' . ($url) . '"';
            }
        }
        return $jobQuestionnaireUrl;
    }

    /**
     * Converts the job data array to GraphQL
     */
    private function convertDataToJobToGQL()
    {
        if ($this->job["errors"]) {
            return false;
        }
        //
        $this->jobBody .= trim(
            str_replace(
                array_keys(
                    $this->job["data"]
                ),
                $this->job["data"],
                $this->getJobBody()
            )
        );
    }

    /**
     * get the GraphQL body of a job
     */
    private function getJobBody()
    {
        return <<<'GRAPHQL'
        {
            body: {
                title: "\title"
                description: "\description"
                descriptionFormatting: RICH_FORMATTING
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
                jobRequisitionId: "\jobRequisitionId"
                datePublished: "\datePublished"
                taxonomyClassification: {
                    jobTypes: [\jobTypes]
                }
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
    }

    /**
     * make the call to Indeed
     */
    private function sendJobsToIndeed($jobId)
    {
        // revert if there is no body
        if (!$this->jobBody) {
            return false;
        }
        // get the multi job Indeed query
        $queryForIndeed = $this->getJobsBodyForIndeed();
        // make the call to Indeed
        $response = $this
            ->indeed_lib
            ->jobSyncApi(
                $queryForIndeed
            );
        // check for errors
        if ($response["error"]) {
            return $this
                ->indeed_model
                ->updateJobsQueue(
                    array_column( // get all the job queue ids
                    $jobId,
                        // $this->jobIds,
                        "sid"
                    ),
                    [
                        "has_errors" => 1,
                        "log_sid" => $response["logId"],
                    ]
                );
        }
        // set the track and update queue
        return $this
            ->handleSuccessEvent(
                $response["resultArray"]["data"]["createSourcedJobPostings"]["results"],
                $response["logId"]
            );
    }

    private function deleteJobsFromIndeed()
    {
        // check if there are expired
        // jobs to process
        if (!$this->expiredJobs) {
            return false;
        }
        //
        $expireBody = "";
        // iterate
        foreach ($this->expiredJobs as $v0) {
            if ($indeedPostingId = $this
                ->indeed_model
                ->checkAndGetJobPostingId(
                    $v0["uuid"],
                    $v0["job_sid"],
                    $v0["sid"]
                )
            ) {
                //
                $this->expiredJobIds[] = [
                    "sid" => $v0["sid"],
                    "job_sid" => $v0["job_sid"],
                    "uuid" => $v0["uuid"],
                    "indeed_posting_id" => $indeedPostingId
                ];
                //
                $expireBody .= '{ sourcedPostingId: "' . ($indeedPostingId) . '" }, ';
            } else {
                $this->indeed_model->removeJobFromQueue($v0["sid"]);
            }
        }
        //
        $expireBody = rtrim(
            $expireBody,
            ", "
        );
        // generate mutation body
        $generatedBody = $this->generateExpireMutation($expireBody);
        // make the call to Indeed
        $response = $this
            ->indeed_lib
            ->expireJobByPostingIds(
                $generatedBody
            );
        // check for errors
        if ($response["error"]) {
            return $this
                ->indeed_model
                ->updateJobsQueue(
                    array_column( // get all the job queue ids
                        $this->expiredJobIds,
                        "sid"
                    ),
                    [
                        "has_errors" => 1,
                        "log_sid" => $response["logId"],
                    ]
                );
        }
        $this->handleExpireSuccessEvent(
            $response["resultArray"]["data"]["jobsIngest"]["expireSourcedJobsBySourcedPostingId"]["results"],
            $response["logId"]
        );
    }

    /**
     * generate the jobs body for Indeed
     *
     * @return string
     */
    private function getJobsBodyForIndeed(): string
    {
        $multiJobBody = <<<'GRAPHQL'
        mutation {
            createSourcedJobPostings(
                input: {
                    jobPostings: [\jobs]
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
        // create multi jpb body
        $multiJobBody = str_replace(
            "\jobs",
            $this->jobBody,
            $multiJobBody
        );
        // convert it to http body
        return '{"query":' . json_encode($multiJobBody) . '}';
    }

    /**
     * generate the jobs body for Indeed
     *
     * @return string
     */
    private function generateExpireMutation(
        string $expiredJobBody
    ): string {
        $expireMutation = <<<'GRAPHQL'
        mutation {
            jobsIngest {
                    expireSourcedJobsBySourcedPostingId(
                        input: {
                            jobs: [\jobPostingIds]
                        }
                    ) {
                        results {
                            trackingKey
                        }
                    }
                }
            }
        GRAPHQL;
        // create multi jpb body
        $expireMutation = str_replace(
            "\jobPostingIds",
            $expiredJobBody,
            $expireMutation
        );
        // convert it to http body
        return '{"query":' . json_encode($expireMutation) . '}';
    }

    /**
     * handles the update track
     * and queue process
     *
     * @param array $jobPostingArray
     * @param int   $logId
     */
    private function handleSuccessEvent(
        array $jobPostingArray,
        int $logId
    ) {
        // iterate the results
        foreach ($jobPostingArray as $k0 => $v0) {
            // add the tracking
            $this
                ->indeed_model
                ->checkAndAddIndeedJobPosting(
                    $this->jobIds[$k0]["uuid"], // the job sid
                    $v0["jobPosting"]["sourcedPostingId"] // indeed job posting id
                );
            $this
                ->indeed_model
                ->updateJobsQueue(
                    [
                        $this->jobIds[$k0]["sid"]
                    ],
                    [
                        "has_errors" => null,
                        "is_processed" => 1,
                        "is_processing" => 0,
                        "processed_at" => getSystemDate(),
                        "log_sid" => $logId,
                    ]
                );
        }
        //
        return true;
    }

    /**
     * handles the update track
     * and queue process
     *
     * @param array $jobPostingArray
     * @param int   $logId
     */
    private function handleExpireSuccessEvent(
        array $jobPostingArray,
        int $logId
    ) {
        // iterate the results
        foreach ($jobPostingArray as $k0 => $v0) {
            // add the tracking
            $this
                ->indeed_model
                ->checkAndAddIndeedTrackingKey(
                    $this->expiredJobIds[$k0]["uuid"], // the job sid
                    $this->expiredJobIds[$k0]["indeed_posting_id"], // the job sid
                    $v0["trackingKey"] // indeed job posting id
                );
            $this
                ->indeed_model
                ->updateJobsQueue(
                    [
                        $this->expiredJobIds[$k0]["sid"]
                    ],
                    [
                        "has_errors" => null,
                        "is_processed" => 1,
                        "is_processing" => 0,
                        "processed_at" => getSystemDate(),
                        "log_sid" => $logId,
                    ]
                );
        }
    }

    /**
     * get the job type
     *
     * @return array
     */
    private function getJobType(): string
    {
        return $this->job["JobType"] !== "Full Time" ? '"part-time"' : '"full-time"';
    }
}

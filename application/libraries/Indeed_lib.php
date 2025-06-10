<?php defined('BASEPATH') || exit(0);

/**
 * Indeed library
 *
 * @author AutomotoHR Dev Team
 */
class Indeed_lib
{
    /**
     * Holds the CI instance
     * @var reference ci
     */
    private $ci;


    // call upon load
    public function __construct()
    {
        // get CI instance
        $this->ci = &get_instance();
    }

    /**
     * Generates the access token
     */
    public function generateAccessToken()
    {
        // get the client id and secret
        extract(
            $this->getClientIdAndSecret()
        );
        // set options
        $options = [];
        // post
        $options[CURLOPT_POSTFIELDS] = http_build_query([
            "grant_type" => "client_credentials",
            "scope" => "employer_access",
            "client_id" => $clientId,
            "client_secret" => $clientSecret,
            // "employer" => "1ee142ff6861db372f0735513e44bd5d",
        ]);
        // headers
        $options[CURLOPT_HTTPHEADER] = [
            "Content-Type: application/x-www-form-urlencoded",
            "Accept: application/json",
        ];
        // make the call to generate access
        // and refresh tokens
        $response = $this->makeCall(
            "https://apis.indeed.com/oauth/v2/tokens",
            "POST",
            $options
        );
        //
        if (!$response["resultArray"]["access_token"]) {
            return [
                "error" => $response["errors"]
            ];
        }
        // update token to the creds file
        return $this->updateIndeedAccessToken(
            $response["resultArray"]["access_token"]
        );
    }

    /**
     * Send disposition call
     *
     * @param string $atsId
     * @param string $status
     * @return array
     */
    public function sendDispositionCall(
        string $atsId,
        string $status
    ) {
        //
        $dateTime =
            getSystemDateInUTC("Y-m-d\TH:i:s.") . substr(getSystemDateInUTC("u"), 0, 3) . 'Z';
        //
        $requestWrap = '{"query":"mutation {\\n\\tpartnerDisposition {\\n\\t\\tsend(\\n\\t\\t\\tinput: {\\n\\t\\t\\t\\tdispositions: [\\n\\t\\t\\t\\t\\t{\\n\\t\\t\\t\\t\\t\\tdispositionStatus: ' . ($status) . '\\n\\t\\t\\t\\t\\t\\trawDispositionStatus: \\"' . ($status) . '\\"\\n\\t\\t\\t\\t\\t\\trawDispositionDetails: \\"\\"\\n\\t\\t\\t\\t\\t\\tidentifiedBy: {\\n\\t\\t\\t\\t\\t\\t\\tindeedApplyID: \\"' . ($atsId) . '\\"\\n\\t\\t\\t\\t\\t\\t}\\n\\t\\t\\t\\t\\t\\tatsName: \\"AutomotoHR\\"\\n\\t\\t\\t\\t\\t\\tstatusChangeDateTime: \\"' . ($dateTime) . '\\"\\n\\t\\t\\t\\t\\t}\\n\\t\\t\\t\\t],\\n\\t\\t\\t}\\n\\t\\t) {\\n\\t\\t\\tnumberGoodDispositions\\n\\t\\t\\tfailedDispositions {\\n\\t\\t\\t\\tidentifiedBy {\\n\\t\\t\\t\\t\\tindeedApplyID\\n\\t\\t\\t\\t}\\n\\t\\t\\t\\trationale\\n\\t\\t\\t}\\n\\t\\t}\\n\\t}\\n}\\n","variables":{}}';

        // get the access token
        $accessToken = $this->getAccessToken();

        // when no access token found
        if (!$accessToken) {
            return ["error" => "Failed to retrieve access token."];
        }
        // set options
        $options = [];
        // post
        $options[CURLOPT_POSTFIELDS] = $requestWrap;
        // headers
        $options[CURLOPT_HTTPHEADER] = [
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer {$accessToken}"
        ];
        // make the call
        $response = $this->makeCall(
            "https://apis.indeed.com/graphql",
            "POST",
            $options
        );
        // when error occurred
        if ($response["errors"]) {
            // check for auth error
            if ($response["resultArray"]["errors"][0]["extensions"]["code"] == "UNAUTHENTICATED") {
                // regenerate the token
                $this->generateAccessToken();
                // recall
                return $this->sendDispositionCall(
                    $atsId,
                    $status
                );
            }
            return ["error" => $response["errors"]];
        }
        // check for errors
        if ($response["resultArray"]["data"]["partnerDisposition"]["send"]["failedDispositions"]) {
            return ["error" => "Failed to update status on Indeed."];
        }
        //
        return ["success" => "Status updated on Indeed."];
    }

    /**
     * get indeed disposition status list
     *
     * @return array
     */
    public function getIndeedDispositionStatusList(): array
    {
        // set the array
        $dispositionStatusArray = [];
        //
        $dispositionStatusArray["NEW"] = [
            "status" => "NEW",
            "slug" => "NEW",
            "description" => "Required. ATS received a new job application.",
            "atsStatus" => [
                "New",
                "Applied",
                "Received",
                "New Candidate"
            ],
        ];
        $dispositionStatusArray["REVIEW"] = [
            "status" => "REVIEW",
            "slug" => "REVIEW",
            "description" => "Job application is under review.",
            "atsStatus" => [
                "In review",
                "Review",
                "Reviewing",
            ],
        ];
        $dispositionStatusArray["LIKED"] = [
            "status" => "LIKED",
            "slug" => "LIKED",
            "description" => "The recruiter liked, favorited, or shortlisted the job application.",
            "atsStatus" => [
                "Liked",
                "Shortlist",
                "Highlight",
                "Favorited",
            ],
        ];
        $dispositionStatusArray["CONTACTED"] = [
            "status" => "CONTACTED",
            "slug" => "CONTACTED",
            "description" => "The candidate was contacted through phone or email. This status does not include rejections.",
            "atsStatus" => [
                "Contacted",
                "Outreach",
            ],
        ];
        $dispositionStatusArray["SCREEN"] = [
            "status" => "SCREEN",
            "slug" => "SCREEN",
            "description" => "Pre-hire screening process. This could span multiple steps, such as phone screening or other screening method.",
            "atsStatus" => [
                "Phone screen",
            ],
        ];
        $dispositionStatusArray["ASSESS_QUALIFICATIONS"] = [
            "status" => "ASSESS QUALIFICATIONS",
            "slug" => "ASSESS_QUALIFICATIONS",
            "description" => "Pre-hire assessment process. Can include multiple steps, such as skills tests, take home assignments, and other methods.",
            "atsStatus" => [
                "Assessment",
                "Project",
                "Take home",
            ],
        ];
        $dispositionStatusArray["INTERVIEW"] = [
            "status" => "INTERVIEW",
            "slug" => "INTERVIEW",
            "description" => "Candidate is interviewing. Can span multiple interviews.",
            "atsStatus" => [
                "Interview",
                "First round interview",
                "Interview phase",
            ],
        ];
        $dispositionStatusArray["OFFER_MADE"] = [
            "status" => "OFFER MADE",
            "slug" => "OFFER_MADE",
            "description" => "Candidate received a job offer.",
            "atsStatus" => [
                "Offer",
                "Offering",
                "Offer initiated",
            ],
        ];
        $dispositionStatusArray["BACKGROUND_CHECK"] = [
            "status" => "BACKGROUND CHECK",
            "slug" => "BACKGROUND_CHECK",
            "description" => "A background check is in progress.",
            "atsStatus" => [
                "Background",
            ],
        ];
        $dispositionStatusArray["VERIFY_ELIGIBILITY"] = [
            "status" => "VERIFY ELIGIBILITY",
            "slug" => "VERIFY_ELIGIBILITY",
            "description" => "Pre-hire eligibility process. This could span multiple steps, including license verification, drug test, or other method.",
            "atsStatus" => [
                "References",
                "Drug screen",
                "License verification",
            ],
        ];
        $dispositionStatusArray["HIRED"] = [
            "status" => "HIRED",
            "slug" => "HIRED",
            "description" => "Required. The candidate accepted an offer of employment.",
            "atsStatus" => [
                "Hired",
                "Placed",
            ],
        ];
        $dispositionStatusArray["NOT_SELECTED"] = [
            "status" => "NOT SELECTED",
            "slug" => "NOT_SELECTED",
            "description" => "Candidate was not selected for this job.",
            "atsStatus" => [
                "Rejected",
                "Release",
                "Unsuitable",
                "Do not pursue",
            ],
        ];
        $dispositionStatusArray["OFFER_DECLINED"] = [
            "status" => "OFFER DECLINED",
            "slug" => "OFFER_DECLINED",
            "description" => "Candidate declined the job offer.",
            "atsStatus" => [
                "Declined",
            ],
        ];
        $dispositionStatusArray["WITHDRAWN"] = [
            "status" => "WITHDRAWN",
            "slug" => "WITHDRAWN",
            "description" => "The candidate withdrew the application.",
            "atsStatus" => [
                "Candidate withdrew",
            ],
        ];
        $dispositionStatusArray["INCOMPLETE"] = [
            "status" => "INCOMPLETE",
            "slug" => "INCOMPLETE",
            "description" => "Job application is incomplete.",
            "atsStatus" => [
                "Incomplete",
                "Unfinished",
            ],
        ];
        $dispositionStatusArray["UNABLE_TO_MAP"] = [
            "status" => "UNABLE TO MAP",
            "slug" => "UNABLE_TO_MAP",
            "description" => "Use this status only when you cannot find a corresponding status.	",
            "atsStatus" => [],
        ];
        // return the array
        return $dispositionStatusArray;
    }

    /**
     * get the client id and secret
     */
    private function getClientIdAndSecret()
    {
        // load the ENV file
        $indeedCredentials = getCreds(
            "AHR"
        )->INDEED;
        //
        return [
            "clientId" => $indeedCredentials->CLIENT_ID,
            "clientSecret" => $indeedCredentials->CLIENT_SECRET,
        ];
    }

    /**
     * make call to the server
     *
     * @param string $url
     * @param string $method
     * @param array $post
     * @param array $headers
     * @return array
     */
    private function makeCall(
        string $url,
        string $method = "GET",
        array $customOptions = []
    ) {
        // set return array
        $returnArray = [
            "info" => [],
            "errors" => [],
            "result" => '',
            "resultArray" => [],
        ];
        // set curl default options
        $options =
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
            ] + $customOptions;
        //
        $curl = curl_init();
        //
        curl_setopt_array(
            $curl,
            $options
        );
        // set log array
        $log = [
            "request_json" => json_encode([
                "url" => $url,
                "method" => $method,
                "options" => $options,
            ]),
            "response_errors" => [],
            "response_info" => [],
            "response_json" => [],
            "response_code" => 0,
        ];
        //
        $returnArray["result"] = curl_exec($curl);
        $returnArray["info"] = curl_getinfo($curl);
        $returnArray["errors"] = curl_error($curl);
        //
        $log["response_info"] = json_encode($returnArray["info"]);
        $log["response_errors"] = json_encode($returnArray["errors"]);
        $log["response_code"] = $returnArray["info"]["http_code"];
        $log["response_json"] = $returnArray["result"] ? $returnArray["result"] : json_encode([]);
        // save the log an get the generated id
        $returnArray["logId"] = $this->saveLog($log);
        //
        $returnArray["resultArray"] = json_decode(
            $returnArray["result"],
            true
        );
        //
        if ($returnArray["info"]["http_code"] !== 200) {
            $returnArray["errors"] = "Error occurred with status code: " . $returnArray["info"]["http_code"];
        } else {
            if ($returnArray["resultArray"]["errors"]) {
                $returnArray["errors"] = $returnArray["resultArray"]["errors"];
            }
        }
        //
        return $returnArray;
    }

    /**
     * update Indeed access token
     *
     * @param string $accessToken
     * @return string
     */
    private function updateIndeedAccessToken(string $accessToken)
    {
        // set the path
        $path = ROOTPATH . '../credentials/indeed_api.json';
        // open the file
        $handler = fopen($path, "r");
        // get the contents
        $fileContentArray = json_decode(
            fread($handler, filesize($path)),
            true
        );
        // close the file
        fclose($handler);
        // check if there is an index
        $fileContentArray["ACCESS_TOKEN"] = $accessToken;
        // open the file in write mode
        $handler = fopen($path, "w");
        // write the new data
        fwrite($handler, json_encode($fileContentArray));
        // close the file
        fclose($handler);
        //
        return $accessToken;
    }

    /**
     * get the access token
     *
     * @return string
     */
    private function getAccessToken(): string
    {
        // get the access token from credentails
        $accessToken = getProtectedFile("indeed_api")->ACCESS_TOKEN;
        // when no access token found
        // generate a new one
        if (!$accessToken) {
            return $this->generateAccessToken();
        }
        //
        return $accessToken;
    }

    /**
     * save the log
     *
     * @param array $log
     * @return int
     */
    private function saveLog(array $log): int
    {
        //
        $log["created_at"] = !function_exists("getSystemDate") ? date("Y-m-d H:i:s") : getSystemDate();
        //
        $this->ci->db
            ->insert(
                "automotohr_logs.indeed_api_logs",
                $log
            );
        // get the inserted id
        return $this->ci->db->insert_id();
    }

    /**
     * send the jobs to Indeed
     *
     * @param string $queryForIndeed
     */
    public function jobSyncApi(
        $queryForIndeed
    ) {
        // get the access token
        $accessToken = $this->getAccessToken();
        // when no access token found
        if (!$accessToken) {
            return ["error" => "Failed to retrieve access token."];
        }

        // set options
        $options = [];
        // post
        $options[CURLOPT_POSTFIELDS] = $queryForIndeed;
        // headers
        $options[CURLOPT_HTTPHEADER] = [
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer {$accessToken}"
        ];
        // make the call
        $response = $this->makeCall(
            "https://apis.indeed.com/graphql",
            "POST",
            $options
        );

        // when error occurred
        if ($response["errors"]) {
            // check for auth error
            if ($response["resultArray"]["errors"][0]["extensions"]["code"] == "UNAUTHENTICATED") {
                // regenerate the token
                $this->generateAccessToken();
                // recall
                return $this->jobSyncApi($queryForIndeed);
            }
            return ["error" => $response["errors"], "logId" => $response["logId"]];
        }
        // check for errors
        if ($response["resultArray"]["data"]["partnerDisposition"]["send"]["failedDispositions"]) {
            return ["error" => "Failed to Create Job on Indeed.", "logId" => $response["logId"]];
        }
        //
        return $response;
    }

    /**
     * delete jobs from Indeed
     *
     * @param string $queryForIndeed
     */
    public function expireJobByPostingIds(
        $queryForIndeed
    ) {
        // get the access token
        $accessToken = $this->getAccessToken();
        // when no access token found
        if (!$accessToken) {
            return ["error" => "Failed to retrieve access token."];
        }
        // set options
        $options = [];
        // post
        $options[CURLOPT_POSTFIELDS] = $queryForIndeed;
        // headers
        $options[CURLOPT_HTTPHEADER] = [
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer {$accessToken}"
        ];
        // make the call
        $response = $this->makeCall(
            "https://apis.indeed.com/graphql",
            "POST",
            $options
        );
        // when error occurred
        if ($response["errors"]) {
            // check for auth error
            if ($response["resultArray"]["errors"][0]["extensions"]["code"] == "UNAUTHENTICATED") {
                // regenerate the token
                $this->generateAccessToken();
                // recall
                return $this->expireJobByPostingIds($queryForIndeed);
            }
            return ["error" => $response["errors"], "logId" => $response["logId"]];
        }
        if ($response["resultArray"]["errors"]) {
            return [
                "errors" => $response["resultArray"]["errors"],
                "logId" => $response["logId"]
            ];
        }
        //
        return $response;
    }

    /**
     * 
     */
    public function generateIndeedApplyButton($jobId, int $counter = 0)
    {
        die("Sadas");
        $mutation = <<<'GRAPHQL'
        mutation {
            applyUrl {
                createApplyUrlForEmployers(
                    input: {
                        jobId: { sourceJobPostingId: "\jobId" }
                        jobUrl: "\jobUrl"
                        continueUrl: "\continueUrl"
                        exitUrl: "\xitUrl"
                    }
                ) {
                    applyUrl
                }
            }
        }
        GRAPHQL;
        // set replace array
        $replaceArray = [
            "\jobId" => $jobId,
            "\jobUrl" => current_url(),
            "\continueUrl" => current_url(),
            "\xitUrl" => base_url("jobs")
        ];
        // replace the indexes
        $mutationBody = str_replace(
            array_keys(
                $replaceArray
            ),
            $replaceArray,
            $mutation
        );

        $queryForIndeed = '{"query":' . json_encode($mutationBody) . '}';
        //
        $accessToken = $this->getAccessToken();
        // when no access token found
        if (!$accessToken) {
            return ["error" => "Failed to retrieve access token."];
        }
        // set options
        $options = [];
        // post
        $options[CURLOPT_POSTFIELDS] = $queryForIndeed;
        // headers
        $options[CURLOPT_HTTPHEADER] = [
            "Content-Type: application/json",
            "Accept: application/json",
            "Authorization: Bearer {$accessToken}"
        ];
        // make the call
        $response = $this->makeCall(
            "https://apis.indeed.com/graphql",
            "POST",
            $options
        );
        // when error occurred
        if ($response["errors"]) {
            // check for auth error
            if ($response["resultArray"]["errors"][0]["extensions"]["code"] == "UNAUTHENTICATED") {

                if ($counter == 0) {
                    // regenerate the token
                    $this->generateAccessToken();
                    // recall
                    return $this->generateIndeedApplyButton($jobId, 1);
                }
                return ["error" => "The system failed to generate access token.", "logId" => $response["logId"]];
            }
            return ["error" => $response["errors"], "logId" => $response["logId"]];
        }
        _e($response, true, true);

        _e($mutationBody, true, true);
    }
}

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
        // create the request
        $request = '
        {
            dispositionStatus: ' . ($status) . ',
            rawDispositionStatus: ' . (ucfirst($status)) . ',
            rawDispositionDetails: "",
            identifiedBy: [
                indeedApplyID: ' . ($atsId) . ',
            ],
            atsName: "AutomotoHR",
            statusChangeDateTime: ' . (getSystemDateInUTC("c")) . ',
        }
        ';
        //
        $requestWrap = 'mutation {
            partnerDisposition {
                send(input: {
                dispositions: [' . (json_encode($request)) . '],
                }) {
                numberGoodDispositions
                failedDispositions {
                    identifiedBy {
                    indeedApplyID
                    }
                    rationale
                }
                }
            }
        };';

        // get the acccess token
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
        _e($options, true, true);
        // make the call
        $response = $this->makeCall(
            "https://apis.indeed.com/graphql",
            "POST",
            $options
        );
        _e($response, true, true);
        // when error occured
        if ($response["errors"]) {
            return ["error" => $response["errors"]];
        }

        _e($response, true, true);
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
        //
        $returnArray["result"] = curl_exec($curl);
        $returnArray["info"] = curl_getinfo($curl);
        $returnArray["errors"] = curl_error($curl);
        //
        $returnArray["resultArray"] = json_decode(
            $returnArray["result"],
            true
        );
        //
        if ($returnArray["info"]["http_code"] !== 200) {
            $returnArray["errors"] = "Error occurred with status code: " . $returnArray["info"]["http_code"];
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
        $path = ROOTPATH . '../creds.json';
        // open the file
        $handler = fopen($path, "r");
        // get the contents
        $fileContentArray = json_decode(
            fread($handler, filesize($path)),
            true
        );
        // close the file
        fclose($handler);
        // check if teher is an index
        if (array_key_exists("ACCESS_TOKEN", $fileContentArray["AHR"]["INDEED"])) {
            $fileContentArray["AHR"]["INDEED"]["ACCESS_TOKEN"] = $accessToken;
        }
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
        $accessToken = getCreds("AHR")->INDEED->ACCESS_TOKEN;
        // when no access token found
        // generate a new one
        if (!$accessToken) {
            return $this->generateAccessToken();
        }
        //
        return $accessToken;
    }
}

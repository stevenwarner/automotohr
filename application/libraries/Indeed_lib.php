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

    /**
     * holds the url
     * @var string
     */
    private $url;

    // call upon load
    public function __construct()
    {
        // get CI instance
        $this->ci = &get_instance();
        // set the url
        $this->url = "https://apis.indeed.com/";
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
        // make the call to generate access
        // and refresh tokens
        $response = $this->makeCall(
            "oauth/v2/tokens",
            "POST",
            [
                "grant_type" => "client_credentials",
                "scope" => "employer_access",
                "client_id" => $clientId,
                "client_secret" => $clientSecret,
            ],
            [
                "Content-Type: application/x-www-form-urlencoded",
                "Accept: application/json",
            ]
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
        array $post = [],
        array $headers = []
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
                CURLOPT_URL => $this->url . $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => $method,
                CURLOPT_HTTPHEADER => $headers
            ];
        //
        if ($method === "POST") {
            $options[CURLOPT_POSTFIELDS] = http_build_query($post);
        }
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
}

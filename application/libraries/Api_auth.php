<?php defined('BASEPATH') || exit(0);

/**
 * API authentication
 * 
 * Logs the user into API server
 */
class Api_auth
{

    /**
     * Holds the CI instance
     * @var reference ci
     */
    private $ci;

    /**
     * Holds the user id
     * @var int userId
     */
    private $userId;

    /**
     * Holds the companyId
     * @var int companyId
     */
    private $companyId = 0;

    /**
     * Login to API server
     */
    public function checkAndLogin(int $companyId, int $userId)
    {
        // get CI instance
        $this->ci = &get_instance();
        //
        $this->companyId = $companyId;
        $this->userId = $userId;
        // check the user id
        if ($this->userId == 0) {
            return ['errors' => ['Required Id not found.']];
        }
        // check and expire token
        $this->checkAndGenerateApiTokenCode();
        //
        return ['success' => 'Successfully authenticated.'];
    }


    /**
     * 
     */
    private function checkAndGenerateApiTokenCode()
    {
        // set default result
        $result = $this->ci->db
            ->select('sid, client_id, client_secret, expires_in, updated_at, iat, exp')
            ->where([
                'user_sid' => $this->userId,
                'company_sid' => $this->companyId
            ])->get('api_credentials')->row_array();
        // check if the token is already generated
        if (!$result) {
            // need to add
            $ins = [];
            $ins['user_sid'] = $this->userId;
            $ins['company_sid'] = $this->companyId;
            $ins['created_at'] = $ins['updated_at'] = getSystemDate();
            //
            $this->ci->db->insert('api_credentials', $ins);
            // set default result
            $result = $this->ci->db
                ->select('sid, client_id, client_secret, expires_in, iat, exp')
                ->where([
                    'user_sid' => $this->userId,
                    'company_sid' => $this->companyId
                ])->get('api_credentials')->row_array();
        }
        //
        $currentTimeStamp = strtotime("now");
        //
        if ($currentTimeStamp >= $result["exp"]) {
            // flush the old access token
            $this->ci->db
                ->where([
                    'user_sid' => $this->userId,
                    'company_sid' => $this->companyId
                ])->update('api_credentials', ["access_token" => ""]);
            // generate a new access token
            $this->loginToAPIServer($result);
        }
    }

    /**
     * 
     */
    private function loginToAPIServer(array $dataArray)
    {
        //
        $curl = curl_init(getAPIUrl('login'));
        //
        curl_setopt_array($curl, [
            CURLOPT_POST => TRUE,
            CURLOPT_POSTFIELDS => json_encode([
                'client_id' => $dataArray['client_id'],
                'client_secret' => $dataArray['client_secret']
            ]),
            CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
            CURLOPT_RETURNTRANSFER => 1
        ]);
        //
        $result = json_decode(curl_exec($curl), true);
        //
        curl_close($curl);
        ///
        return $result;
    }
}

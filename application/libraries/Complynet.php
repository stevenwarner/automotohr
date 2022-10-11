<?php

/**
 * 
 */

class ComplyNet
{

    private $token;
    private $response;
    private $complynetUser;
    private $mode;
    private $CI;


    private $dateWithTime;


    public function __construct()
    {
        //
        $this->CI = &get_instance();
        //
        $this->CI->load->model('2022/Complynet_model', 'complynet_model');
        //
        $this->complynetModel = $this->CI->complynet_model;
        //
        $this->token = null;
        //
        $this->response = [];
        //
        $this->dateWithTime = date('Y-m-d H:i:s', strtotime('now'));
        //
        $this->date = date('Y-m-d', strtotime('now'));
        //
        $this->complynetUser = getCreds("AHR")->COMPLY_NET;
    }

    /**
     * Get access_token
     *
     * 
     * @return Array
     */
    public function authenticate()
    {
        //
        if ($this->mode == 'fake') {
            //
            return $this;
        }
        // Check db for active token
        $record = $this->complynetModel->getActiveToken(
            ['token'],
            [
                'expires >= ' => $this->dateWithTime
            ]
        );

        // Case of no record found
        if (!$record) {
            // generate new token
            $record =
                $this->curlCall(
                    $this->complynetUser->AUTH_URL,
                    [
                        "grant_type" => $this->complynetUser->GRANT_TYPE,
                        "username" => $this->complynetUser->USERNAME,
                        "password" => $this->complynetUser->PASSWORD
                    ],
                    "POST",
                    ['Content-Type: application/x-www-form-urlencoded']
                );

            // error
            if (isset($record['error'])) {
                //
            }
            // success
            $this->complynetModel
                ->insertData('complynet_access_token', [
                    "token" => $record["access_token"],
                    "token_type" => $record["token_type"],
                    "expires_in" => $record["expires_in"],
                    "issued" => DateTime::createFromFormat('D, d M Y H:i:s \G\M\T', $record[".issued"])->format('Y-m-d H:m:s'),
                    "expires" => DateTime::createFromFormat('D, d M Y H:i:s \G\M\T', $record[".expires"])->format('Y-m-d H:m:s')
                ]);
            //
            $record["token"] =  $record["access_token"];
        }
        //
        $this->token = $record['token'];
        //
        return $this;
    }

    /**
     * 
     */
    private function curlCall(
        $url,
        $postFields = [],
        $method = 'POST',
        $headerArray = ['Content-Type: application/json']
    ) {
        //
        if (preg_match('/api/i', $url)) {
            $headerArray[] = 'Authorization: Bearer ' . $this->token;
        }
        //
        $curl = curl_init();
        //
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => $headerArray
        ));
        //
        if (!empty($postFields)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($postFields));
        }
        //
        $response = curl_exec($curl);
        //
        $responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        //
        curl_close($curl);
        //
        if ($responseCode != 200) {
            //
            $responseObj = json_decode($response);
            //
            return [
                "error" => [
                    'code' => $responseCode,
                    'message' => $responseObj->error ?? $responseObj->Message
                ]
            ];
        }

        //
        return json_decode($response, true);
    }


    /**
     * 
     */
    public function setMode($mode)
    {
        //
        $this->mode = $mode;
        //
        return $this;
    }

    /**
     * Get all companies from complynet
     * 
     * @method fakeCompanyData
     * 
     * @return Array
     */
    public function getCompanies()
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeCompanyData();
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'Company',
            [],
            'GET',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }


    // Fake data functions
    /**
     * Fake company generator
     * 
     * @return array
     */
    private function fakeCompanyData()
    {
        return [[
            "Id" => "1F9F9677-2CE0-43B3-A418-0815334B706B",
            "Name" => " ComplyNet"
        ], [
            "Id" => "739FBDEA-135B-4FFD-803B-884A441F6C86",
            "Name" => " My Dealer Group "
        ], [
            "Id" => "739FBDEA-135B-4FFD-803B-884A441F6C86",
            "Name" => " AutomotoHR "
        ]];
    }

    /**
     * Get all location against company from complynet
     * 
     * @param $company_ID String
     * 
     * @return Array
     */
    function getLocations($company_ID)
    {
    }

    /**
     * Get all department against location from complynet
     * 
     * @param $location_ID String
     * 
     * @return Array
     */
    function getDepartments($location_ID)
    {
    }

    /**https://www.youtube.com/shorts/HblXD3bH6dA
     * Update department
     * 
     * @param $Id String
     * @param $ParentId String
     * @param $Name String
     * @param $IsActive String
     * 
     * @return Array
     */
    function updateDepartments($Id, $ParentId, $Name, $IsActive)
    {
    }

    /**
     * Create department
     * 
     * @param $department_name String
     * 
     * @return Array
     */
    function createDepartment($department_name)
    {
    }

    /**
     * Delete department
     * 
     * @param $department_Id String
     * 
     * @return Array
     */
    function deleteDepartment($department_Id)
    {
    }

    /**
     * Get all job roles against department from complynet
     * 
     * @param $department_Id String
     * 
     * @return Array
     */
    function getJobRole($department_Id)
    {
    }

    /**
     * Update job role
     * 
     * @param $Id String
     * @param $ParentId String
     * @param $Name String
     * @param $IsActive String
     * 
     * @return Array
     */
    function updateJobRole($Id, $ParentId, $Name, $IsActive)
    {
    }

    /**
     * Create job role
     * 
     * @param $name String
     * 
     * @return Array
     */
    function createJobRole($Name)
    {
    }

    /**
     * Delete job role
     * 
     * @param $Id String
     * 
     * @return Array
     */
    function deleteJobRole($Id)
    {
    }

    /**
     * Create User
     * 
     * @param $firstName String
     * @param $lastName String
     * @param $userName String
     * @param $email String
     * @param $password String
     * @param $companyId String
     * @param $locationId String
     * @param $departmentId String
     * @param $jobRoleId String
     * @param $PhoneNumber String
     * @param $TwoFactor String
     * 
     * @return Array
     */
    function createUser(
        $firstName,
        $lastName,
        $userName,
        $email,
        $password,
        $companyId,
        $locationId,
        $departmentId,
        $jobRoleId,
        $PhoneNumber,
        $TwoFactor = TRUE
    ) {
    }

    /**
     * Update User
     * 
     * @param $firstName String
     * @param $lastName String
     * @param $userName String
     * @param $email String
     * @param $password String
     * @param $companyId String
     * @param $locationId String
     * @param $departmentId String
     * @param $jobRoleId String
     * @param $PhoneNumber String
     * @param $TwoFactor String
     * 
     * @return Array
     */
    function updateUser(
        $firstName,
        $lastName,
        $userName,
        $email,
        $password,
        $companyId,
        $locationId,
        $departmentId,
        $jobRoleId,
        $PhoneNumber,
        $TwoFactor = TRUE
    ) {
    }

    /**
     * Get User
     * 
     * @param $userName String
     * 
     * @return Array
     */
    function getUser($userName)
    {
    }

    /**
     * Disable User
     * 
     * @param $userName String
     * 
     * @return Array
     */
    function disableUser($userName)
    {
    }

    private function resp($response, $http_status)
    {
        //
        $result = array(
            "status" => '',
            "response" => ''
        );
        //
        $response = json_decode($response, true);
        //
        if ($http_status == 200) {
            $result["status"] = "success";
            $result["response"] = $response;
        } else {
            $result["status"] = "error";
            $result["response"] = $response["Message"];
        }
        //
        return $result;
    }
}

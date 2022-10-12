<?php

/**
* Name:  ComplyNet
*
* Version: 1.0
*
* Author: AutoMotoHR
*
* Added Awesomeness: Mubashir Ahmad, Aleem Shaukat
*
* Location: USA
*
* Created:  10.10.2022
*
* Description:  The library is used for link system with complynet.
*
* Requirements: PHP7 or above
*
*/

class ComplyNet
{

    /**
     *
     * CI intance
     * 
     **/
    private $CI;

    /**
     *
     * @var string
     * 
     * For toggle between live and fake
     * 
     * option: live | fake
     * 
     **/
    private $mode;

    /**
     *
     * @var string
     * 
     * Complynet access_token
     * 
     **/
    private $token;

    /**
     *
     * @var array
     * 
     * For return result
     * 
     **/
    private $response;

    /**
     *
     * @var dateTime
     * 
     * For camparing
     * 
     **/
    private $dateWithTime;

    /**
     *
     * @var associative array
     * 
     * For handling camplynet credential
     * 
     **/
    private $complynetUser;

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
                    "issued" => DateTime::createFromFormat('D, d M Y H:i:s \G\M\T', $record[".issued"])->format('Y-m-d H:i:s'),
                    "expires" => DateTime::createFromFormat('D, d M Y H:i:s \G\M\T', $record[".expires"])->format('Y-m-d H:i:s')
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
            return $this->fakeCompanyResponse();
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

    /**
     * Get all location against company from complynet
     * 
     * @param $company_ID String
     * 
     * @return Array
     */
    public function getLocations($companyId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeLocationResponse();
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'Location?companyId='.$companyId,
            [],
            'GET',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Get all department against location from complynet
     * 
     * @param $location_ID String
     * 
     * @return Array
     */
    public function getDepartments($locationId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeDepartmentResponse("get");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'Department?LocationId='.$locationId,
            [],
            'GET',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Create department
     * 
     * @param $department_name String
     * 
     * @return Array
     */
    public function createDepartment($departmentName, $locationId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeDepartmentResponse("create");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'Department',
            [
                "Name" => $departmentName,
                "ParentId" => $locationId

            ],
            'POST',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Update department
     * 
     * @param $Id String
     * @param $ParentId String
     * @param $Name String
     * @param $IsActive String
     * 
     * @return Array
     */
    public function updateDepartments($departmentId, $locationId, $departmentName, $isActive)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeDepartmentResponse("update");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'Department',
            [
                "Id" => $departmentId,
                "ParentId" => $locationId,
                "Name" => $departmentName,
                "IsActive" => $isActive
            ],
            'PUT',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Delete department
     * 
     * @param $department_Id String
     * 
     * @return Array
     */
    public function deleteDepartment($departmentId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeDepartmentResponse("delete");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'Department',
            [
                "Id" => $departmentId,
            ],
            'DELETE',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Get all job roles against department from complynet
     * 
     * @param $department_Id String
     * 
     * @return Array
     */
    public function getJobRole($departmentId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeJobRoleResponse("get");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'JobRole?DepartmentId='.$departmentId,
            [],
            'GET',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Create job role
     * 
     * @param $name String
     * 
     * @return Array
     */
    public function createJobRole($jobRoleName, $departmentId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeJobRoleResponse("create");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'JobRole',
            [
                "Name" => $jobRoleName,
                "ParentId" => $departmentId
            ],
            'POST',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
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
    public function updateJobRole($jobRoleId, $departmentId, $jobRoleName, $isActive)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeJobRoleResponse("update");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'JobRole',
            [
                "Id" => $jobRoleId,
                "ParentId" => $departmentId,
                "Name" => $jobRoleName,
                "IsActive" => $isActive
            ],
            'PUT',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Delete job role
     * 
     * @param $Id String
     * 
     * @return Array
     */
    public function deleteJobRole($jobRoleId)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeJobRoleResponse("delete");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'JobRole',
            [
                "Id" => $jobRoleId,
            ],
            'DELETE',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Get User
     * 
     * @param $userName String
     * 
     * @return Array
     */
    public function getUser($userName)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeUserResponse("get");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'User?username='.$userName,
            [],
            'GET',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
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
    public function createUser($user) {
        //
        if ($this->mode == 'fake') {
            return $this->fakeUserResponse("create");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'User',
            $user,
            'POST',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
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
    public function updateUser($user) {
        //
        if ($this->mode == 'fake') {
            return $this->fakeUserResponse("update");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'User',
            $user,
            'PUT',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * Disable User
     * 
     * @param $userName String
     * 
     * @return Array
     */
    public function disableUser($userName)
    {
        //
        if ($this->mode == 'fake') {
            return $this->fakeUserResponse("delete");
        }
        //
        $result = $this->curlCall(
            $this->complynetUser->API_URL . 'User',
            [
                "userName" => $userName,
            ],
            'DELETE',
            [
                'Content-Type: application/json'
            ]
        );
        //
        return $result;
    }

    /**
     * 
     * Set Library Enviorment
     * 
     */
    public function setMode($mode)
    {
        //
        $this->mode = $mode;
        //
        return $this;
    }

    // Library Private Functions

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
     * Fake company generator
     * 
     * @return array
     */
    private function fakeCompanyResponse()
    {
        return [[
            "Id" => "739FBDEA-2CE0-43B3-A418-0815334B706B",
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
     * Fake company location generator
     * 
     * @return array
     */
    private function fakeLocationResponse()
    {
        return [[
            "Id" => "1F9F9677-2CE0-43B3-A418-0815334B706B",
            "Name" => " Human Resource"
        ], [
            "Id" => "1F9F9677-135B-4FFD-803B-884A441F6C86",
            "Name" => " Business Development "
        ], [
            "Id" => "1F9F9677-135B-4FFD-803B-884A441F6C87",
            "Name" => " Development "
        ]];
    }

    /**
     * Fake company departments generator
     * 
     * @return array
     */
    private function fakeDepartmentResponse($type)
    {
        $response = array();
        //
        if ($type == "create") {
            $response["Id"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["ParentId"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["Name"] = " Service Provider";
            $response["IsActive"] = TRUE;
        } else if ($type == "update") {
            $response["Id"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["Name"] = " Service Provider";
            $response["IsActive"] = TRUE;
        } else if ($type == "delete") {
            $response["Id"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["Name"] = " Service Provider";
            $response["IsActive"] = FALSE;
        } else if ($type == "get") {
            $response = [[
                "Id" => "1F9F9677-2CE0-43B3-A418-0815334B706BD",
                "Name" => " Backend Development"
            ], [
                "Id" => "1F9F9677-135B-4FFD-803B-0815334B706FD",
                "Name" => "Frontend Development "
            ], [
                "Id" => "1F9F9677-135B-4FFD-803B-884A441F6SQA",
                "Name" => " Software Quality Assurance "
            ], [
                "Id" => "1F9F9677-135B-4FFD-803B-884A441F6SEO",
                "Name" => " Search Engine Optimization "
            ]];
        }
        //
        return $response;
    }

    /**
     * Fake company departments generator
     * 
     * @return array
     */
    private function fakeJobRoleResponse($type)
    {
        $response = array();
        //
        if ($type == "create") {
            $response["Id"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["ParentId"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["Name"] = " General Manager";
            $response["IsActive"] = TRUE;
        } else if ($type == "update") {
            $response["Id"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["Name"] = " General Manager";
            $response["IsActive"] = TRUE;
        } else if ($type == "delete") {
            $response["Id"] = "1F9F9677-2CE0-43B3-A418-0815334B706B";
            $response["Name"] = " General Manager";
            $response["IsActive"] = FALSE;
        } else if ($type == "get") {
             $response = [[
                "Id" => "1F9F9677-2CE0-43B3-A418-0815334B706BD",
                "Name" => " F&I Manager"
            ], [
                "Id" => "1F9F9677-135B-4FFD-803B-0815334B706FD",
                "Name" => "General Manager "
            ], [
                "Id" => "1F9F9677-135B-4FFD-803B-884A441F6SQA",
                "Name" => " Hiring Manager "
            ], [
                "Id" => "1F9F9677-135B-4FFD-803B-884A441F6SEO",
                "Name" => "Employee "
            ]];
        }
        //
        return $response;
    }

    /**
     * Fake company departments generator
     * 
     * @return array
     */
    private function fakeUserResponse($type)
    {
        $response = array();
        //
        if ($type == "get") {
            $response["firstName"] = "ComplyNet";
            $response["lastName"] = "User";
            $response["userName"] = "requests@ComplyNet.com";
            $response["email"] = "requests@ComplyNet.com";
            $response["companyId"] = "E4A89DDA-12BB-4341-844A-BBE400451274";
            $response["locationId"] = "8AB20AFF-C1AE-4F08-AB1C-160ABD4FEA2F";
            $response["departmentId"] = "55A3BBA9-CE0F-4E1C-9587-9E3709CF2F25";
            $response["jobRoleId"] = "FE96FEBA-DE91-4DA1-A809-499351D001F7";
            $response["PhoneNumber"] = "5555555555";
            $response["TwoFactor"] = TRUE;

        } else {
            $response = "N/A";
        }
        //
        return $response;
    }
}

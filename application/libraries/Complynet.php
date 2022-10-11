<?php
//
class ComplyNet {

    /**
     * Get access_token
     *
     * 
     * @return Array
     */
    private function Authentication(){
        $CI =& get_instance();
        //
        $dateTime = date('Y-m-d h:m:s');
        //
        $CI->db->select('token');
        $CI->db->group_start();
        $CI->db->where("issued <=", $dateTime);
        $CI->db->where("expires >=", $dateTime);
        $CI->db->group_end();
        $result = $CI->db->get('complynet_access_token')->row_array();
        //
        if (!empty($result)) {
            return $result["token"];
        } else {
            //
            $credentials = getCreds("AHR")->ComplyNet;
            //
            $curl = curl_init();
            //
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.complynet.com/Token',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => 'grant_type=password&username='.$credentials->username.'&password='.$credentials->password,
                CURLOPT_HTTPHEADER => array(
                    'Content-Type: application/x-www-form-urlencoded'
                ),
            ));
            //
            $response = curl_exec($curl);
            $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            //
            curl_close($curl);
            //
            $response = $this->resp($response, $http_status);
            //
            if ($response["Status"] == "success") {
                
                $CI->db->insert("complynet_access_token", array(
                    "token" => $response["Data"]["access_token"],
                    "token_type" => $response["Data"]["token_type"],
                    "expires_in" => $response["Data"]["expires_in"],
                    "issued" => DateTime::createFromFormat('D, d M Y H:i:s \G\M\T', $response["Data"][".issued"])->format('Y-m-d h:m:s'),
                    "expires" => DateTime::createFromFormat('D, d M Y H:i:s \G\M\T', $response["Data"][".expires"])->format('Y-m-d h:m:s')
                ));
                //
                return $response["Data"]["access_token"];
            } else {
                return $response;
            }
        }
        
    }

    /**
     * Get all companies from complynet
     * 
     * 
     * @return Array
     */
    public function getCompanies(){
        $access_token = $this->Authentication();

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.ComplyNet.com/api/Company',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'GET',
          CURLOPT_HTTPHEADER => array(
            'ContentType: application/json',
            'Authorization: Bearer '.$access_token
          ),
        ));

        $response = curl_exec($curl);
        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);
        $result = $this->resp($response, $http_status);

        return $result;


    }

    /**
     * Get all location against company from complynet
     * 
     * @param $company_ID String
     * 
     * @return Array
     */
    function getLocations($company_ID){
        
    }

    /**
     * Get all department against location from complynet
     * 
     * @param $location_ID String
     * 
     * @return Array
     */
    function getDepartments($location_ID){
        
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
    function updateDepartments($Id, $ParentId, $Name, $IsActive){ 

    }

    /**
     * Create department
     * 
     * @param $department_name String
     * 
     * @return Array
     */
    function createDepartment($department_name){

    }

    /**
     * Delete department
     * 
     * @param $department_Id String
     * 
     * @return Array
     */
    function deleteDepartment($department_Id) {

    }

    /**
     * Get all job roles against department from complynet
     * 
     * @param $department_Id String
     * 
     * @return Array
     */
    function getJobRole ($department_Id) {

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
    function updateJobRole ($Id, $ParentId, $Name, $IsActive) {

    }

    /**
     * Create job role
     * 
     * @param $name String
     * 
     * @return Array
     */
    function createJobRole($Name){

    }

    /**
     * Delete job role
     * 
     * @param $Id String
     * 
     * @return Array
     */
    function deleteJobRole($Id) {

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
    ){


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
    ){


    }

    /**
     * Get User
     * 
     * @param $userName String
     * 
     * @return Array
     */
    function getUser($userName){


    }

    /**
     * Disable User
     * 
     * @param $userName String
     * 
     * @return Array
     */
    function disableUser($userName){


    }

    private function resp ($response, $http_status) {
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
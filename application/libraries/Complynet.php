<?php

class ComplyNet {
    /**
     * Get access_token
     *
     * 
     * @return Array
     */
    private function Authentication(){
        //
        $credentials = getCreds("AHR")->ComplyNet;
        // _e($credentials,true,true);
        //
        $post = array(
            "grant_type" => "password",
            "credentials" => $credentials->username,
            "password" => $credentials->password
        );
        //
        $curl = curl_init();
        //
        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.ComplyNet.com/Token',
          CURLOPT_POSTFIELDS => $post,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        echo $response."<br>";
        return "aSQzebtQBu8MXTK0peu_MYKmLxPPYVQf8_RJcku80GrxZOg6IWTQGbWl1CEPEnlWaU97FsghIi4QDW7c95LEd8aZVijtOFxvNUAXd0Z3CE6cCOuYa_28o8whp9xKvgaLz_noiOocL3x8YxkOz-LXC3IcakDu9c2urr5IuKWfx9E2KbgORUHBZm1MzNkFAITptm_CehWf8ko2uhyW97i_MVGOSpoF2eJLddiEnBmqwrOdnV8kghsRgcho-wXBZtFKRk2NbFQRu-Ffb6ZFzldeWs1vWh8ZAPyZCV0N199poKR-FwpZLOu7cu9jdI-YB0vapDCQ0ckEw3pdAL_TD7CX0dizuRercpt8n_XbMZKbJyvjAy3og5LIl1fxSb1ukIW5mA7I7QRitL4t4wxgy2VJcz7qO-hWLKTfPuJAu2K94cgW1FytUntzI3mTN5U5ZCvwhq0te5bgrJhLxxA6L7LozMHI90tLW8h7-MqtqT3LKj8mAsaC8xxRtVC0ORgtIoQH0f2IYWLOdTZMhFUI2fQNKMdPDcXNmIuRH7n-JuKIS2eBHh6x6Vo5E3frvJGPz4ZK";
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
          CURLOPT_FAILONERROR => true,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$access_token
          ),
        ));

        $response = curl_exec($curl);

        $http_status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $curl_errno = curl_errno($curl);

        echo $curl_errno." curl_errno<br>";
        echo $http_status." http_status<br>";

        switch ($http_status) {
            case 401:
                echo "401 Unauthorized Request</br>";
                echo curl_error($curl);
                break;

            case 405:
                echo "405 Method Not Allowed</br>";
                echo curl_error($curl);
                break;
                
            case 404:
                echo "404 Url Not Found</br>";
                echo curl_error($curl);
                break; 

            case 411:
                echo "411 Length Required</br>";
                echo curl_error($curl);
                break;           
        }
        echo $response;

        // return "Pepsico";
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

}
<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * ComplyNet Library
 *
 * @version 1.0
 */

class Complynet_lib
{
    /**
     * Credentials
     * @var object
     */
    private $credentials;

    /**
     * Access Token
     * @var string
     */
    private $accessToken;

    /**
     * Constructor
     */
    public function __construct()
    {
        //
        $this->CI = &get_instance();
        //
        $this->CI->load->model('2022/complynet_model');
        $this->CI->load->helper('common');
        //
        $this->setCredentials();
    }

    /**
     * Get companies from ComplyNet
     */
    public function getComplyNetCompanies()
    {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'Company',
            'GET'
        );
    }

    /**
     * Get locations from ComplyNet
     *
     * @param string $companyId
     * @return array
     */
    public function getComplyNetCompanyLocations(
        $companyId
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'Location?companyId=' . $companyId,
            'GET'
        );
    }

    /**
     * Get departments from ComplyNet
     *
     * @param string $locationId
     * @return array
     */
    public function getComplyNetDepartments(
        $locationId
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'Department?LocationId=' . $locationId,
            'GET'
        );
    }

    /**
     * Insert department to ComplyNet
     *
     * @param array $ins
     * @return array
     */
    public function addDepartmentToComplyNet(
        array $ins
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'Department',
            'POST',
            $ins
        );
    }

    /**
     * Get job role from department
     *
     * @param string $departmentId
     * @return array
     */
    public function getJobRolesByDepartmentId(
        string $departmentId
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'JobRole?DepartmentId=' . $departmentId,
            'GET'
        );
    }

    /**
     * Get job role from department
     *
     * @param array $ins
     * @return array
     */
    public function addJobRole(
        array $ins
    ) {
        return 0;
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        $response =  $this->execute(
            'JobRole',
            'POST',
            $ins
        );
        //
        return $response['Id'] ? $response['Id'] : 0;
    }

    /**
     * Get job role from department
     *
     * @param string $email
     * @return array
     */
    public function getEmployeeByEmail(
        string $email
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'User?username=' . (strtolower($email)),
            'GET'
        );
    }

    /**
     * Get job role from department
     *
     * @param array $ins
     * @return array
     */
    public function addEmployee(
        array $ins
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'User',
            'POST',
            $ins
        );
    }

    /**
     * Update user
     *
     * @param array $upd
     * @return array
     */
    public function updateUser(
        array $upd
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'User',
            'PUT',
            $upd
        );
    }

    /**
     * Update Alt id
     *
     * @param string $id
     * @param string $alt_id
     * @return array
     */
    public function updateAltId(
        string $id,
        string $alt_id
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            "UserAltId?id={$id}&altid=AHR{$alt_id}",
            'PUT'
        );
    }

    /**
     * get company employees
     *
     * @param string $companyId
     * @return array
     */
    public function getCompanyEmployees(
        string $companyId
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            "CompanyUsers?CompanyId={$companyId}",
            'GET'
        );
    }

    /**
     * Change user status
     *
     * @param array $upd
     * @return array
     */
    public function changeEmployeeStatusByEmail(
        array $upd
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'User',
            'DELETE',
            $upd
        );
    }

    /**
     * Get user hash
     *
     * @param string $email
     * @return array
     */
    public function getUserHash(
        string $email
    ) {
        // Check and set token
        $this->checkAndSetAccessToken();
        //
        return $this->execute(
            'aUser?username=' . (strtolower($email)),
            'GET'
        );
    }


    /**
     * Check and set the access token
     */
    private function checkAndSetAccessToken()
    {
        //
        $token = $this->CI->complynet_model->checkAndGetToken();
        //
        if (!$token) {
            // Generate new one
            $token = $this->generateNewToken();
        }
        //
        if (is_array($token)) {
            exit('Error: ' . $token['error']);
        }
        //
        $this->accessToken = $token;
    }

    /**
     * Fetch the access token
     * @return string|array
     */
    private function generateNewToken()
    {
        $curl = curl_init();
        $lists = array(
            CURLOPT_URL => $this->credentials->AUTH_URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'grant_type=' . ($this->credentials->GRANT_TYPE) . '&username=' . ($this->credentials->USERNAME) . '&password=' . ($this->credentials->PASSWORD) . '',
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/x-www-form-urlencoded'
            )
        );

        curl_setopt_array($curl, $lists);
        //
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        // Save the request and response to database
        $this->saveCall([
            'request_method' => $lists[CURLOPT_CUSTOMREQUEST],
            'request_url' => $this->credentials->AUTH_URL,
            'request_body' => json_encode([
                'headers' => $lists[CURLOPT_HTTPHEADER],
                'body' => $lists[CURLOPT_POSTFIELDS]
            ]),
            'response_body' => $response,
            'response_code' => $info['http_code'],
            'response_headers' => json_encode($info)
        ]);
        //
        //
        $response = json_decode($response, true);
        curl_close($curl);
        //
        if (isset($response['error'])) {
            return $response;
        }
        //
        return $this->saveAccessToken($response);
    }

    /**
     * Calls out to ComplyNet
     *
     * @param string $url
     * @param string $method
     * @param array  $options
     * @param bool  $execute Optional
     * @return array
     */
    private function execute(
        string $url,
        string $method = "GET",
        array $postFields = [],
        bool $execute = true,
        int $iteration = 1
    ) {
        //
        $curl = curl_init();
        //
        $lists = [
            CURLOPT_URL => $this->credentials->API_URL . $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_SSL_VERIFYPEER => FALSE,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . $this->accessToken
            ),
        ];
        //
        if ($method == 'POST' || $method == 'PUT' || $method == 'DELETE') {
            $lists[CURLOPT_POSTFIELDS] = json_encode($postFields);
            $lists[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        }
        //
        curl_setopt_array($curl, $lists);
        //
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        // Save the request and response to database
        $this->saveCall([
            'request_method' => $lists[CURLOPT_CUSTOMREQUEST],
            'request_url' => $lists[CURLOPT_URL],
            'request_body' => json_encode([
                'headers' => $lists[CURLOPT_HTTPHEADER],
                'body' => $lists[CURLOPT_POSTFIELDS]
            ]),
            'response_body' => $response,
            'response_code' => $info['http_code'],
            'response_headers' => json_encode($info)
        ]);
        //
        $response = json_decode($response, true);
        //
        curl_close($curl);
        //
        $execute = $iteration == 2 ? false : true;
        $iteration++;
        //
        // if (is_array($response) && !$response && $execute)  {
        //     return $this->execute($url, $method, $postFields, $execute, $iteration); 
        // } else if (!is_array($response) && empty($response) && $execute) {
        //     return $this->execute($url, $method, $postFields, $execute, $iteration);
        // } else if ($info['http_code'] == 500 && $execute) {
        //     return $this->execute($url, $method, $postFields, $execute, $iteration);
        // }   
        //
        if ($info['http_code'] == 500) {
            $response['error'] = '500 internal server occurred on ComplyNet.';
        }
        //
        return $response;
    }


    /**
     * Access token entry
     * @param array $response
     * @return array
     */
    private function saveAccessToken($response)
    {
        $ins = [];
        $ins['access_token'] = $response['access_token'] ?? '';
        $ins['token_type'] = $response['token_type'] ?? '';
        // $ins['issued'] = formatDateToDB($response['.issued'], 'D, d M Y H:i:s e', DB_DATE_WITH_TIME);
        // $ins['expires'] = formatDateToDB($response['.expires'], 'D, d M Y H:i:s e', DB_DATE_WITH_TIME);
        // $ins['expires_in'] = $response['expires_in'];
        // $ins['userName'] = $response['userName'];
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->CI->db->insert('complynet_access_token', $ins);
        //
        return $ins['access_token'];
    }

    /**
     * Set the credentials
     */
    private function setCredentials()
    {
        $this->credentials = getCreds('AHR')->COMPLYNET;
    }

    /**
     * saves request and response
     *
     * @param array $ins
     */
    private function saveCall($ins)
    {
        //
        $ins['created_at'] = getSystemDate();
        //
        $this->CI->db->insert('complynet_calls', $ins);
    }
}

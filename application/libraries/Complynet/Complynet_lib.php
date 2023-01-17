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

        curl_setopt_array($curl, array(
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
            ),
        ));
        //
        $response = json_decode(curl_exec($curl), true);
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
     * @return array
     */
    private function execute(
        string $url,
        string $method = "GET",
        array $postFields = []
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
        if ($method == 'POST') {
            $lists[CURLOPT_POSTFIELDS] = json_encode($postFields);
            $lists[CURLOPT_HTTPHEADER][] = 'Content-Type: application/json';
        }
        //
        curl_setopt_array($curl, $lists);
        //
        $response = json_decode(curl_exec($curl), true);
        //
        curl_close($curl);
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
        $ins['access_token'] = $response['access_token'];
        $ins['token_type'] = $response['token_type'];
        $ins['issued'] = formatDateToDB($response['.issued'], 'D, d M Y H:i:s e', DB_DATE_WITH_TIME);
        $ins['expires'] = formatDateToDB($response['.expires'], 'D, d M Y H:i:s e', DB_DATE_WITH_TIME);
        $ins['expires_in'] = $response['expires_in'];
        $ins['userName'] = $response['userName'];
        $ins['created_at'] = $ins['updated_at'] = getSystemDate();
        //
        $this->CI->db->insert('complynet_access_token', $ins);
        //
        return $ins['token'];
    }

    /**
     * Set the credentials
     */
    private function setCredentials()
    {
        $this->credentials = getCreds('AHR')->COMPLYNET;
    }
}

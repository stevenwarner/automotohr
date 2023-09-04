<?php defined('BASEPATH') or exit('No direct script access allowed');
/**
 * Document management
 * Session base API's
 * 
 * @author  AutomotoHR
 * @link    www.automotohr.com
 * @author  Mubashar Ahmed <mubashar@automotohr.com>
 * @version 1.0
 */
class Documents_management extends Public_Controller
{
    /**
     * main entry point
     * inherit all properties of parent
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
        // load model
        $this->load->model('v1/documents_management_model', 'documents_management_model');
        // check for login and generate errors
        checkAndGetSession(
            'employee'
        );
    }

    /**
     * assigns w4 form
     * 
     * @param int    $userId
     * @param string $userType
     * @return array
     */
    public function assignW4Form(int $userId, string $userType): array
    {
        //
        $response = $this
            ->documents_management_model
            ->assignW4Form($userId, $userType);

        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }

    /**
     * revoke w4 form
     * 
     * @param int    $userId
     * @param string $userType
     * @return array
     */
    public function revokeW4Form(int $userId, string $userType): array
    {
        //
        $response = $this
            ->documents_management_model
            ->revokeW4Form($userId, $userType);

        return SendResponse(
            $response['errors'] ? 400 : 200,
            $response
        );
    }
}

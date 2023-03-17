<?php defined('BASEPATH') || exit('No direct script access allowed');

class Gusto_payroll extends CI_Controller
{
    /**
     * Main entry point to controller
     */
    public function __construct()
    {
        parent::__construct();
        // Call the model
        $this->load->model("gusto/Gusto_payroll_model", "gusto_payroll_model");
    }

    /**
     * Add admin
     *
     * @param int $companyId
     */
    public function addAdmin(int $companyId)
    {
        //
        $post = $this->input->post(null, true);
        //
        $admin = $this->db
            ->select('sid, gusto_uuid')
            ->where('company_sid', $companyId)
            ->where('email_address', $post['emailAddress'])
            ->get('payroll_company_admin')
            ->row_array();
        // already exists
        if ($admin) {
            //
            if ($admin['gusto_uuid']) {
                return SendResponse(200, ['error' => 'Admin already exists.']);
            }
            // fetch all admins
            $gustoAdmins = $this->gusto_payroll_model->fetchAllAdmins($companyId);
            //
            $this->db
                ->where('sid', $admin['sid'])
                ->update('payroll_company_admin', [
                    'gusto_uuid' => $gustoAdmins[$admin['email_address']]
                ]);
            //
            return SendResponse(200, ['error' => 'Admin already exists.']);
        }
        // add a new one
        $response = $this->gusto_payroll_model->moveAdminToGusto([
            'first_name' => $post['firstName'],
            'last_name' => $post['lastName'],
            'email' => $post['emailAddress']
        ], $companyId);
        //
        return SendResponse(
            200,
            [
                'success' => 'You have successfully added an admin.'
            ]
        );
    }

    /**
     * Get admins
     *
     * @param int $companyId
     */
    public function getAdmins(int $companyId)
    {
        // fetch all admins
        $gustoAdmins = $this->gusto_payroll_model->fetchAllAdmins($companyId);
        // get all admins
        $admins = $this->db
            ->select('sid, gusto_uuid, first_name, last_name, email_address, created_at')
            ->where('company_sid', $companyId)
            ->order_by('sid', 'desc')
            ->get('payroll_company_admin')
            ->result_array();
        //
        if ($admins) {
            //
            foreach ($admins as $key => $admin) {
                //
                if (!$admin['gusto_uuid']) {
                    //
                    if (!isset($gustoAdmins[$admin['email_address']])) {
                        // move to Gusto first
                        $response = $this->gusto_payroll_model->moveAdminToGusto([
                            'first_name' => $admin['first_name'],
                            'last_name' => $admin['last_name'],
                            'email' => $admin['email_address']
                        ], $companyId);
                        //
                        $admins[$key]['gusto_uuid'] = $response['uuid'];
                    } else {
                        //
                        $this->db
                            ->where('company_sid', $companyId)
                            ->where('email_address', $admin['email_address'])
                            ->update('payroll_company_admin', [
                                'gusto_uuid' => $gustoAdmins[$admin['email_address']]['uuid']
                            ]);
                        //
                        $admins[$key]['gusto_uuid'] = $gustoAdmins[$admin['email_address']]['uuid'];
                    }
                }
            }
        }
        //
        return SendResponse(
            200,
            [
                'view' => $this->load->view('gusto/admins/view', ['admins' => $admins], true)
            ]
        );
    }
}

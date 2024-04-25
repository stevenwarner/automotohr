<?php defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Employee shifts
 * @author AutomotoHR Dev Team
 * @link   www.automotohr.com
 * @version 1.0
 * @package Shifts
 */
class Payroll_sync_company_cron extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * send out email notification to employees
     * with upcoming shifts
     */
    public function syncCompanyInfoWithGusto()
    {
        // load payroll model
        $this->load->model("v1/Payroll_model", "payroll_model");
        //
        $pendingRequest = $this->payroll_model->getPendingSyncCompanyRequest();
        
        //
        if ($pendingRequest) {
            $this->payroll_model->syncCompanyWithGusto(
                $pendingRequest['company_sid']
            );
        }
        //
    }
}

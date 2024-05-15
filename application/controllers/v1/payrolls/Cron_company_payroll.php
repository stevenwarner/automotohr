<?php defined('BASEPATH') || exit('No direct script access allowed');

/**
 * Cron company payroll
 * 
 * @author  AutomotoHR Dev Team
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Cron_company_payroll extends CI_Controller
{

    /**
     * holds the job
     * @var array
     */
    private $job;

    /**
     * main entry point to controller
     */
    public function __construct()
    {
        // inherit
        parent::__construct();
    }

    /**
     * check and run job from queue
     */
    public function queue()
    {
        // get the company from the queue
        $job = $this
            ->db
            ->where("is_processed", 0)
            ->limit(1)
            ->order_by("sid", "ASC")
            ->get("payrolls.gusto_sync_queue")
            ->row_array();
        //
        if (!$job) {
            exit("No job found!");
        }
        //
        $this->runJob($job);
    }

    /**
     * executes the job
     *
     * @param array $job
     */
    private function runJob(array $job)
    {
        //
        $this->job = $job;
        //
        $this->runCompanyEvents();
        //
        $this->runEmployeeEvents();
        //
        $this->markJobComplete();
    }

    /**
     * company events
     */
    private function runCompanyEvents()
    {
        // load the company model
        // Call the model
        $this->load->model(
            "v1/Payroll/Company_payroll_model",
            "company_payroll_model"
        );
        //
        $this
            ->company_payroll_model
            ->setCompanyDetails(
                $this->job["company_sid"]
            );

        // sync admins
        $this->updateStage("admins");
        $this
            ->company_payroll_model
            ->syncAdmins();

        // sync address
        $this->updateStage("company_address");
        $this
            ->company_payroll_model
            ->syncCompanyAddress();

        // sync earning types
        $this->updateStage("earning_types");
        $this
            ->company_payroll_model
            ->syncEarningTypes();

        // sync payment config
        $this->updateStage("payment_config");
        $this
            ->company_payroll_model
            ->syncPaymentConfig();

        // sync federal tax
        $this->updateStage("federal_tax");
        $this
            ->company_payroll_model
            ->syncFederalTax();
    }

    /**
     * employee events
     */
    private function runEmployeeEvents()
    {
        // load the company model
        // Call the model
        $this->load->model(
            "v1/Payroll/Employee_payroll_model",
            "employee_payroll_model"
        );
        //
        $this
            ->employee_payroll_model
            ->setCompanyDetails(
                $this->job["company_sid"]
            );

        // sync employees
        $this->updateStage("employee");
        // get the company employees
        $this
            ->employee_payroll_model
            ->syncEmployees();


        _e("sdas");
    }

    /**
     * update the stage
     *
     * @param string $stage
     */
    private function updateStage(
        string $stage
    ) {
        $this
            ->db
            ->where("sid", $this->job["sid"])
            ->update(
                "payrolls.gusto_sync_queue",
                [
                    "stage" => $stage,
                    "updated_at" => getSystemDate()
                ]
            );
    }

    /**
     * mark the job completed
     */
    private function markJobComplete()
    {
        $this
            ->db
            ->where("sid", $this->job["sid"])
            ->update(
                "payrolls.gusto_sync_queue",
                [
                    "is_processed" => 1
                ]
            );
    }
}

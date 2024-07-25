<?php defined('BASEPATH') || exit('No direct script access allowed');
// load the payroll model
loadUpModel('payroll/Payrolls_model', 'Payroll_model');
/**
 * Copy payroll model
 * 
 * @author  AutomotoHR <www.automotohr.com>
 * @link    www.automotohr.com
 * @version 1.0
 * @package Payroll
 */
class Copy_model extends Payroll_model
{
    /**
     * Inherit the parent class methods on call
     */
    public function __construct()
    {
        // call the parent constructor
        parent::__construct();
    }

    /**
     * 
     */
    public function copyCompanyEarningTypes(int $sourceCompanyId, int $destinationCompanyId): array
    {
        // get the earning types from source company
        $earningTypes = $this->getCompanyEarningTypes($sourceCompanyId);
        //
        if (!$earningTypes) {
            return ["error" => ["No earning types found."]];
        }
        // load the payroll helper
        $this->load->helper('v1/payroll' . ($this->db->where([
            "company_sid" => $destinationCompanyId,
            "stage" => "production"
        ])->count_all_results("gusto_companies_mode") ? "_production" : "") . '_helper');

        foreach ($earningTypes as $v0) {
            // set where
            $where = [
                "company_sid" => $destinationCompanyId,
                "name" => $v0["name"]
            ];
            // when found
            if ($this->db->where($where)->count_all_results("gusto_companies_earning_types")) {
                // update
                $this->db
                    ->where($where)
                    ->update("gusto_companies_earning_types", [
                        "fields_json" => $v0["fields_json"],
                        "updated_at" => getSystemDate()
                    ]);
            } else {
                // only add if it's not an default
                if ($v0["is_default"] == 0) {
                    $this->addCompanyEarningType(
                        $destinationCompanyId,
                        $v0
                    );
                }
            }
        }
        //
        return ["msg" => "Copy process completed."];
    }
}

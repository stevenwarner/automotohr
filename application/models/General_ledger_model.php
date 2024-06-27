<?php 
class General_ledger_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function getPayrollInfo($company_sids = NULL, $between = '') {
        $this->db
            ->select('
                sid,
                start_date,
                end_date,
                check_date,
                totals
            ');
        //
        $records = $this->db
            ->get('payrolls.regular_payrolls')
            ->result_array();
        //    
        _e($records, true, true);
    }
}
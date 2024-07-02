<?php 
class General_ledger_model extends CI_Model {
    function __construct() {
        parent::__construct();
    }

    public function getCompanyPayrolls($companyId = NULL, $between = '', $process = true) {
        //
        $this->db->select('sid, totals, processed_date, off_cycle_reason');
        $this->db->where('processed', 1);
        $this->db->where('company_sid', $companyId);
        //
        if ($between != '' && $between != NULL) {
            $this->db->where($between);
        }
        //  
        $this->db->order_by("sid", "desc");
        $companyPayroll = $this->db->get('payrolls.regular_payrolls')->result_array();
        //
        $response = [];
        //
        if ($companyPayroll) {
            if ($process) {
                foreach ($companyPayroll as $key => $payroll) {
                    $response[$key]['sid'] = $payroll['sid'];
                    $response[$key]['date'] = formatDateToDB($payroll['processed_date'], DB_DATE_WITH_TIME, DATE);
                    $response[$key]['debit'] = json_decode($payroll['totals'], true)['company_debit'];
                    $response[$key]['type'] = 'Payroll - (<b>'.$payroll['off_cycle_reason'].'</b>)';
                }
            } else {
                $response = $companyPayroll;
            }
            
        }
        //
        return $response;
    }

    public function getPayroll($sid) {
        //
        $this->db->select('totals');
        $this->db->where('sid', $sid);
        //
        $payroll = $this->db->get('payrolls.regular_payrolls')->row_array();
        //
        return json_decode($payroll['totals'], true);
    }
}
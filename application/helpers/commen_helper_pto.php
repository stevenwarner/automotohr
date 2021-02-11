/**
 * Generated email template for PTO request
 * Created on: 28-08-2019
 *
 * @param $companyId   Integer
 * @param $companyName String
 * @param $_this       Instance
 *
 * @return String
 */
if(!function_exists('getPtoRequestEmailTemplate')){
    function getPtoRequestEmailTemplate(
        $companyId,
        $companyName,
        $_this
    ){
        $template = $_this->pto_model->getEmailTemplate($companyId);
        if(!$template) return false;
        $tmp = message_header_footer($companyId, ucwords($companyName));
        $rows = '';
        $rows .= $tmp['header'];
        $rows .= $template['message_body'];
        $rows .= $tmp['footer'];

        $template['message_body'] = $rows;
        return $template;
    }
}

/**
 * Get days for leap years
 *
 * @param $startYear String
 * 'YYYY'
 * @param $endYear String
 * 'YYYY'
 *
 * @return Integer
 */
if(!function_exists('getLeapYears')){
    function getLeapYears($startYear, $endYear) {
        // Set default leap years count
        $leapYears = 0;
        // Create dates array
        $yearsToCheck = range($startYear, $endYear);
        // Loop through years
        foreach ($yearsToCheck as $year) {
            // Calculate leap years count
            $leapYears = $leapYears + (int) date('L', strtotime("$year-01-01"));
        }
        return $leapYears;
    }
}

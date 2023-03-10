<?php defined('BASEPATH') || exit('No direct script access allowed');

/******************************** ADP ********************************/
if (!function_exists('checkADPStatus')) {
    /**
     * Check the ADP status against company
     *
     * @param int $companyId
     * @return int
     */
    function checkADPStatus(int $companyId)
    {
        // get CI instance
        $CI = &get_instance();
        // get the status
        return $CI->db->where([
            'company_sid' => $companyId,
            'status' => 1
        ])->count_all_results('adp_companies');
    }
}

if (!function_exists('checkEmployeeAdpStatus')) {
    /**
     * Check the ADP status against employee
     *
     * @param int $employeeId
     * @return int
     */
    function checkEmployeeAdpStatus(int $employeeId)
    {
        // get CI instance
        $CI = &get_instance();
        // get the status
        return $CI->db
            ->where('employee_sid', $employeeId)
            ->count_all_results('adp_employees');
    }
}

if (!function_exists('generateRequestBody')) {
    /**
     * Generates ADp request body
     *
     * @param array  $field
     * @param string $associateOID
     * @param any    $value
     * @return array
     */
    function generateRequestBody(array $field, string $associateOID, $value)
    {
        //
        $requestBody = [];
        $requestBody['events'] = [];
        $requestBody['events'][0] = [
            'data' => [
                'eventContext' => [
                    'worker' => [
                        'associateOID' => $associateOID
                    ]
                ],
                'transform' => [
                    'worker' => []
                ]
            ]
        ];
        // for gender
        if ($field['name'] == 'genderCode') {
            $requestBody['events'][0]['data']['transform']['worker']['person'] = [];
            $requestBody['events'][0]['data']['transform']['worker']['person']['genderCode'] = [
                'codeValue' => strtoupper(substr($value, 0, 1))
            ];
        }
        //
        return $requestBody;
    }
}


if (!function_exists('getRaceCode')) {
    /**
     * Get race codes
     * Either race codes array is returned or options as text
     *
     * @param mixed $selectedRaceCode Optional
     * @param bool $options Optional
     * @return array|string
     */
    function getRaceCode($selectedRaceCode = null, bool $options = true)
    {
        //
        $raceCodes = [
            1 => "white",
            2 => "black or african american",
            3 => "hispanic or latino",
            4 => "asian",
            5 => "american indian or alaska native",
            6 => "native hawaiian or other pacific islander",
            9 => "two or more races"
        ];
        //
        if (!$options) {
            //
            if (gettype($selectedRaceCode) == 'string') {
                $raceCodes = array_flip($raceCodes);
            }
            //
            return $selectedRaceCode != null ? $raceCodes[strtolower($selectedRaceCode)] : $raceCodes;
        }
        //
        $html = '';
        //
        foreach ($raceCodes as $raceCodeIndex => $raceCode) {
            $html .= '<option value="' . ($raceCodeIndex) . '" ' . ($selectedRaceCode == $raceCodeIndex ? 'selected' : '') . '>' . ($raceCode) . '</option>';
        }
        //
        return $html;
    }
}

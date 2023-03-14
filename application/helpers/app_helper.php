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
     * Generates ADP request body
     *
     * @method 
     * @param array  $field
     * @param string $associateOID
     * @param any    $value
     * @param array  $extraValueArray
     * @return array
     */
    function generateRequestBody(array $field, string $associateOID, $value, $extraValueArray)
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

        // Rehire Date
        if ($field['name'] == 'rehireDate') {
            $requestBody['events'][0]['data']['transform']['effectiveDateTime'] = $value;
            $requestBody['events'][0]['data']['transform']['worker']['workerDates'] =  [];
            $requestBody['events'][0]['data']['transform']['worker']['workerDates']['rehireDate'] =  $value;
            $requestBody['events'][0]['data']['transform']['worker']['workerStatus']['reasonCode']['codeValue'] = 'Active';
        }

        // legal Address
        if (
            $field['name'] == 'lineOne'
            || $field['name'] == 'cityName'
            || $field['name'] == 'codeValue'
            || $field['name'] == 'countryCode'
            || $field['name'] == 'postalCode'
        ) {
            // get state code and name
            $stateData = getStateColumnsById($extraValueArray['Location_State']);
            // generate request body
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress'] =  [];
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['lineOne'] =  $extraValueArray['Location_Addresss'];
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['lineTwo'] =  '';
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['lineThree'] =  '';
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['lineFour'] =  '';
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['lineFive'] =  '';
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['cityName'] =  $extraValueArray['Location_City'];
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['countrySubdivisionLevel1']['subdivisionType'] =  'State';
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['countrySubdivisionLevel1']['codeValue'] = $stateData['state_code'];
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['countrySubdivisionLevel1']['longName'] = $stateData['state_name'];
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['countryCode'] =  'US';
            $requestBody['events'][0]['data']['transform']['worker']['legalAddress']['postalCode'] =  $extraValueArray['Location_ZipCode'];
        }

        // SSN
        if ($field['name'] == 'ssn') {
            $requestBody['events'][0]['data']['transform']['worker']['person'] =  [];
            $requestBody['events'][0]['data']['transform']['worker']['person']['governmentID']['idValue'] =  $value;
            $requestBody['events'][0]['data']['transform']['worker']['person']['governmentID']['nameCode']['codeValue'] =  'SSN';
            $requestBody['events'][0]['data']['transform']['worker']['person']['governmentID']['countryCode'] =  'US';
        }

        // Phone Number
        if ($field['name'] == 'phone_number') {
            $requestBody['events'][0]['data']['transform']['worker']['communication'] =  [];
            $requestBody['events'][0]['data']['transform']['worker']['communication']['landlines']['nameCode']['codeValue'] =  'Home Phone';
            $requestBody['events'][0]['data']['transform']['worker']['communication']['landlines']['nameCode']['shortName'] =  'Home Phone';
            $requestBody['events'][0]['data']['transform']['worker']['communication']['landlines']['countryDialing'] = '1';
            $requestBody['events'][0]['data']['transform']['worker']['communication']['landlines']['areaDialing'] = $extraValueArray['areaDialing'];
            $requestBody['events'][0]['data']['transform']['worker']['communication']['landlines']['dialNumber'] = $extraValueArray['dialNumber'];
            $requestBody['events'][0]['data']['transform']['worker']['communication']['landlines']['formattedNumber'] = $extraValueArray['formattedNumber'];
        }
        //Email
        if ($field['name'] == 'email') {
            $requestBody['events'][0]['data']['transform']['worker']['communication'] =  [];
            $requestBody['events'][0]['data']['transform']['worker']['communication']['emails']['nameCode']['codeValue'] =  'E-mail';
            $requestBody['events'][0]['data']['transform']['worker']['communication']['emails']['nameCode']['shortName'] =  'E-mail';
            $requestBody['events'][0]['data']['transform']['worker']['communication']['emails']['emailUri'] =  $value;
            $requestBody['events'][0]['data']['transform']['worker']['communication']['emails']['itemID'] =  "139470108012_1";
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

if (!function_exists('getMaritalCode')) {
    /**
     * Get marital codes
     * Either marital codes array is returned or options as text
     *
     * @param mixed $selectedMaritalCode Optional
     * @param bool $options Optional
     * @return array|string
     */
    function getMaritalCode($selectedMaritalCode = null, bool $options = true)
    {
        //
        $selectedMaritalCode = trim($selectedMaritalCode);
        //
        $maritalCodes = [
            // 'A' => "Annulled",
            'D' => "Divorced",
            // 'I' => "Interlocutory",
            'L' => "Legally Separated",
            'M' => "Married",
            'P' => "Polygamous",
            'S' => "Single",
            // 'T' => "Domestic Partner",
            'W' => "Widowed"
        ];
        //
        if (!$options) {
            //
            if (strlen($selectedMaritalCode) != 1) {
                $maritalCodes = array_flip($maritalCodes);
            }
            //
            return $selectedMaritalCode != null ? $maritalCodes[ucwords($selectedMaritalCode)] : $maritalCodes;
        }
        //
        $html = '';
        //
        foreach ($maritalCodes as $raceCode) {
            $html .= '<option value="' . ($raceCode) . '" ' . ($selectedMaritalCode == $raceCode ? 'selected' : '') . '>' . ($raceCode) . '</option>';
        }
        //
        return $html;
    }
}

if (!function_exists('getStateColumnsById')) {
    /**
     * 
     */
    function getStateColumnsById($state_sid)
    {
        //
        $CI = &get_instance();
        return $CI->db
            ->select('state_name,state_code')
            ->where('sid', $state_sid)
            ->from('states')
            ->get()->row_array();
    }
}

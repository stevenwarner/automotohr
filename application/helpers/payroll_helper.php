<?php

use Aws\HashingStream;

if (!function_exists('PayrollAuth')) {
    function PayrollAuth($company)
    {
        //
        return MakeCall(
            PayrollURL('Me'),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . ''
                )
            ]
        );
    }
}

if (!function_exists('RefreshToken')) {
    function RefreshToken($request)
    {
        //
        $body = [];
        $body['client_id'] = GUSTO_CLIENT_ID;
        $body['client_secret'] = GUSTO_CLIENT_SECRET;
        $body['redirect_uri'] = GUSTO_CLIENT_REDIRECT_URL;
        $body['refresh_token'] = $request['refresh_token'];
        $body['grant_type'] = 'refresh_token';
        //
        return MakeCall(
            PayrollURL('RefreshToken', ''),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($body),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token ' . ($request['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
    }
}

//
if (!function_exists('createPartnerCompany')) {
    function createPartnerCompany($request)
    {
        //
        return MakeCall(
            PayrollURL('CreatePartnerCompany'),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token ' . (GUSTO_KEY_TEST) . '',
                    'Content-Type: application/json'
                )
            ]
        );
    }
}



if (!function_exists('DeleteCompanyEmployee')) {
    function DeleteCompanyEmployee($employee_id, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('DeleteEmployee', $employee_id),
            [
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteCompanyEmployee($employee_id, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('AddCompanyLocation')) {
    function AddCompanyLocation($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('AddCompanyLocation', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddCompanyLocation($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('Payrolls')) {
    function Payrolls($company)
    {
        //
        $response =  MakeCall(
            PayrollURL('Payrolls', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return Payrolls($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('AddBankAccountToPayroll')) {
    function AddBankAccountToPayroll($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('AddBankAccountToPayroll', $company['employeeId']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddBankAccountToPayroll($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('DeleteBankAccountToPayroll')) {
    function DeleteBankAccountToPayroll($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('DeleteBankAccountToPayroll', $request['employee_id'], $request['bank_account_id']),
            [
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteBankAccountToPayroll($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('AddCompanyBankAccountToPayroll')) {
    function AddCompanyBankAccountToPayroll($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('AddCompanyBankAccountToPayroll', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddCompanyBankAccountToPayroll($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('GetSinglePayroll')) {
    function GetSinglePayroll($query, $company, $step)
    {
        //
        $url = PayrollURL('GetSinglePayroll', $company['gusto_company_uid'], $company['payroll_id'], $step);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetSinglePayroll($query, $company, $step);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
if (!function_exists('GetCompanyEmployees')) {
    function GetCompanyEmployees($company)
    {
        //
        $url = PayrollURL('GetCompanyEmployees', $company['gusto_company_uid']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetCompanyEmployees($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
if (!function_exists('GetCompany')) {
    function GetCompany($company)
    {
        //
        $url = PayrollURL('GetCompany', $company['gusto_company_uid']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetCompany($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
if (!function_exists('UpdatePayrollById')) {
    function UpdatePayrollById($request, $company)
    {
        //
        $url = PayrollURL('UpdatePayrollById', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdatePayrollById($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('CalculatePayroll')) {
    function CalculatePayroll($company)
    {
        //
        $url = PayrollURL('CalculatePayroll', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CalculatePayroll($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('CancelPayrollById')) {
    function CancelPayrollById($company)
    {
        //
        $url = PayrollURL('CancelPayrollById', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CancelPayrollById($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

//
if (!function_exists('SubmitPayrollById')) {
    function SubmitPayrollById($company)
    {
        //
        $url = PayrollURL('SubmitPayrollById', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return SubmitPayrollById($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}


// Employee related calls
if (!function_exists('AddEmployeeToCompany')) {
    function AddEmployeeToCompany($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('AddEmployeeToCompany', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddEmployeeToCompany($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateEmployeeAddress')) {
    function UpdateEmployeeAddress($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeAddress', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeeAddress($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('CreateEmployeeAddress')) {
    function CreateEmployeeAddress($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('CreateEmployeeAddress', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CreateEmployeeAddress($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('CreateEmployeeJob')) {
    function CreateEmployeeJob($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('CreateEmployeeJob', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CreateEmployeeJob($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateEmployeeJob')) {
    function UpdateEmployeeJob($request, $jobId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeJob', $jobId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeeJob($request, $jobId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('DeleteOnboardingEmployee')) {
    function DeleteOnboardingEmployee($employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('DeleteOnboardingEmployee', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteOnboardingEmployee($employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('DeleteEmployeeBankAccount')) {
    function DeleteEmployeeBankAccount($employeeId, $bankId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('DeleteEmployeeBankAccount', $employeeId, $bankId),
            [
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteEmployeeBankAccount($employeeId, $bankId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateEmployeeOnGusto')) {
    function UpdateEmployeeOnGusto($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeOnGusto', $company['employee_uuid']),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeeOnGusto($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateJobCompensation')) {
    function UpdateJobCompensation($request, $compensationId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateJobCompensation', $compensationId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateJobCompensation($request, $compensationId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('GetJob')) {
    function GetJob($jobId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetJob', $jobId),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetJob($jobId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('GetEmployeeFederalTax')) {
    function GetEmployeeFederalTax($employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetEmployeeFederalTax', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetEmployeeFederalTax($employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('GetEmployeeStateTax')) {
    function GetEmployeeStateTax($employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetEmployeeStateTax', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetEmployeeStateTax($employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('GetEmployeePaymentMethod')) {
    function GetEmployeePaymentMethod($employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetEmployeePaymentMethod', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetEmployeePaymentMethod($employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('GetEmployeeBankDetails')) {
    function GetEmployeeBankDetails($employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetEmployeeBankDetails', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetEmployeeBankDetails($employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateEmployeeFederalTax')) {
    function UpdateEmployeeFederalTax($request, $employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeFederalTax', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeeFederalTax($request, $employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateEmployeeStateTax')) {
    function UpdateEmployeeStateTax($request, $employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeStateTax', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeeStateTax($request, $employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('UpdateEmployeePaymentMethod')) {
    function UpdateEmployeePaymentMethod($request, $employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeePaymentMethod', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeePaymentMethod($request, $employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('MarkEmployeeAsOnboarded')) {
    function MarkEmployeeAsOnboarded($employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('MarkEmployeeAsOnboarded', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return MarkEmployeeAsOnboarded($employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('AddEmployeeBandAccount')) {
    function AddEmployeeBandAccount($request, $employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('AddEmployeeBandAccount', $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddEmployeeBandAccount($request, $employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if (!function_exists('GetEmployeePayStubs')) {
    function GetEmployeePayStubs($payrollUUID, $employeeUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetEmployeePayStubs', $payrollUUID, $employeeUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetEmployeePayStubs($payrollUUID, $employeeUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}



// As of 12/09/2021

if (!function_exists('GetCompanyStatus')) {
    function GetCompanyStatus($company)
    {
        //
        $response =  MakeCall(
            PayrollURL('GetCompanyStatus', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetCompanyStatus($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('CreateCompanyFlowLink')) {
    function CreateCompanyFlowLink($company, $isSignatory = false)
    {
        //
        $url = PayrollURL('GetCompanyFlows', $company['gusto_company_uid']);
        //
        $request = array();
        //
        $request['flow_type'] = "select_industry,payroll_schedule,federal_tax_setup,state_setup,add_bank_info,verify_bank_info" . ($isSignatory ? ',sign_all_forms' : '');
        $request['entity_type'] = "Company";
        $request['entity_uuid'] = $company['gusto_company_uid'];
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CreateCompanyFlowLink($company, $force);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('PayPeriods')) {
    function PayPeriods($company, $startDate, $force = false)
    {
        //

        $url = PayrollURL('PayPeriods', $company['gusto_company_uid'], $startDate);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            $force
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return PayPeriods($company, $startDate, $force);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            return $response;
        }
    }
}

if (!function_exists('GetCompletedProcessedPayrolls')) {
    function GetCompletedProcessedPayrolls($query, $company, $headers = [])
    {
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('GetCompletedProcessedPayrolls', $company['gusto_company_uid']);
        $url = PayrollURL('GetCompletedProcessedPayrolls', $company['gusto_company_uid']) . '?processing_statuses=processed&payroll_types=regular&include=totals,payroll_status_meta';
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetCompletedProcessedPayrolls($query, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

if (!function_exists('UpdatePayrollForDemo')) {
    function UpdatePayrollForDemo($company, $employees, $version, $startDate, $endDate)
    {
        //
        $url = PayrollURL('UpdatePayrollForDemo', $company['gusto_company_uid'], $startDate, $endDate);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(['employee_compensations' => $employees, 'version' => $version]),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            false
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdatePayrollForDemo($company, $employees, $version, $startDate, $endDate);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}


if (!function_exists('GetProcessedPayrolls')) {
    function GetProcessedPayrolls($query, $company, $force = false)
    {
        //
        $url = PayrollURL('GetProcessedPayrolls', $company['gusto_company_uid'], $query);
        //
        if (!$force) {
            $tr = CacheHolder($url);
            if ($tr) {
                // return $tr;
            }
        }
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            $force
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetProcessedPayrolls($query, $company, $force);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
//
if (!function_exists('CreateAdmin')) {
    function CreateAdmin($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('CreateAdmin', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CreateAdmin($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('AcceptServiceTerms')) {
    function AcceptServiceTerms($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('AcceptServiceTerms', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AcceptServiceTerms($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('UpdatePaymentConfig')) {
    function UpdatePaymentConfig($request, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdatePaymentConfig', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdatePaymentConfig($request, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('GetPaymentConfig')) {
    function GetPaymentConfig($company)
    {
        //
        // $company['gusto_company_uid'] = 'de7afa2e-9a5f-4f6c-b063-1b4803ac9d1c';
        // $company['access_token'] = 'QbliCE6If1NIgcaTgU82-BHxLDhDKhe5AgIckc8YJ6s';
        // $company['refresh_token'] = 'xW_xWBT55T92HKXwQeDc8AP9NQcBbRVyESmZzIQSKL0';
        //
        $response =  MakeCall(
            PayrollURL('GetPaymentConfig', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetPaymentConfig($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


// Internal use functions

if (!function_exists('PayrollURL')) {
    function PayrollURL($index, $key = 0, $key1 = 0, $step = null)
    {
        //
        $urls = [];
        $urls['Me'] = 'v1/me';
        $urls['CreatePartnerCompany'] = 'v1/partner_managed_companies';
        $urls['RefreshToken'] = 'oauth/token?' . ($key);
        // $urls['PayPeriods'] = 'v1/companies/' . ($key) . '/pay_periods?start_date=' . $key1;
        $urls['PayPeriods'] = 'v1/companies/' . ($key) . '/pay_periods';
        $urls['Payrolls'] = 'v1/companies/' . ($key) . '/pay_schedules';
        $urls['AddBankAccountToPayroll'] = 'v1/employees/' . ($key) . '/bank_accounts';
        $urls['DeleteBankAccountToPayroll'] = 'v1/employees/' . ($key) . '/bank_accounts/' . $key1;
        $urls['AddCompanyBankAccountToPayroll'] = 'v1/companies/' . ($key) . '/bank_accounts';
        $urls['GetSinglePayroll'] = 'v1/companies/' . ($key) . '/payrolls/' . ($key1) . '?show_calculation=false&include=benefits,deductions,taxes';
        if ($step == 3 || $step == 4) {
            $urls['GetSinglePayroll'] = 'v1/companies/' . ($key) . '/payrolls/' . ($key1) . '?show_calculation=true&include=benefits,deductions,taxes';
        }
        $urls['GetCompanyEmployees'] = 'v1/companies/' . ($key) . '/employees';
        $urls['UpdatePayrollById'] = 'v1/companies/' . ($key) . '/payrolls/' . ($key1);
        $urls['CalculatePayroll'] = 'v1/companies/' . ($key) . '/payrolls/' . ($key1) . '/calculate';
        $urls['CancelPayrollById'] = 'v1/companies/' . ($key) . '/payrolls/' . ($key1) . '/cancel';
        $urls['SubmitPayrollById'] = 'v1/companies/' . ($key) . '/payrolls/' . ($key1) . '/submit';
        $urls['GetCompany'] = 'v1/companies/' . ($key);
        $urls['AddCompanyLocation'] = 'v1/companies/' . ($key) . '/locations';
        $urls['GetCompanyFlows'] = 'v1/companies/' . ($key) . '/flows';
        $urls['GetCompanyStatus'] = 'v1/companies/' . ($key) . '/onboarding_status';
        $urls['DeleteEmployee'] = 'v1/employees/' . ($key);
        // As of 12/09/2021
        $urls['GetUnProcessedPayrolls'] = 'v1/companies/' . ($key) . '/payrolls' . ($key1);
        $urls['GetProcessedPayrolls'] = 'v1/companies/' . ($key) . '/payrolls' . ($key1);

        // Employee routes
        $urls['AddEmployeeToCompany'] = 'v1/companies/' . ($key) . '/employees';
        $urls['UpdateEmployeeAddress'] = 'v1/employees/' . ($key) . '/home_address';
        $urls['CreateEmployeeAddress'] = 'v1/employees/' . ($key) . '/jobs';
        $urls['CreateEmployeeJob'] = 'v1/employees/' . ($key) . '/jobs';
        $urls['UpdateEmployeeJob'] = 'v1/jobs/' . ($key);
        $urls['DeleteOnboardingEmployee'] = 'v1/employees/' . ($key);
        $urls['UpdateEmployeeOnGusto'] = 'v1/employees/' . ($key);
        $urls['UpdateJobCompensation'] = 'v1/compensations/' . ($key);
        $urls['GetJob'] = 'v1/jobs/' . ($key);
        $urls['GetEmployeeFederalTax'] = 'v1/employees/' . ($key) . '/federal_taxes';
        $urls['GetEmployeeStateTax'] = 'v1/employees/' . ($key) . '/state_taxes';
        $urls['UpdateEmployeeFederalTax'] = 'v1/employees/' . ($key) . '/federal_taxes';
        $urls['UpdateEmployeeStateTax'] = 'v1/employees/' . ($key) . '/state_taxes';
        $urls['GetEmployeePaymentMethod'] = 'v1/employees/' . ($key) . '/payment_method';
        $urls['GetEmployeeBankDetails'] = 'v1/employees/' . ($key) . '/bank_accounts';
        $urls['AddEmployeeBandAccount'] = 'v1/employees/' . ($key) . '/bank_accounts';
        $urls['DeleteEmployeeBankAccount'] = 'v1/employees/' . ($key) . '/bank_accounts/' . ($key1);
        $urls['UpdateEmployeePaymentMethod'] = 'v1/employees/' . ($key) . '/payment_method';
        $urls['MarkEmployeeAsOnboarded'] = 'v1/employees/' . ($key) . '/finish_onboarding';
        $urls['GetEmployeeOnboardedStatus'] = 'v1/employees/' . ($key) . '/onboarding_status';
        $urls['GetEmployeeForms'] = 'v1/employees/' . ($key) . '/forms';
        $urls['GetEmployeeFormContent'] = 'v1/employees/' . ($key) . '/forms/' . ($key1) . '/pdf';
        $urls['SignEmployeeForm'] = 'v1/employees/' . ($key) . '/forms/' . ($key1) . '/sign';
        //
        $urls['GetEmployeePayStubs'] = 'v1/payrolls/' . ($key) . '/employees/' . ($key1) . '/pay_stub';


        $urls['UpdatePayrollForDemo'] = 'v1/companies/' . ($key) . '/payrolls/' . $key1 . '/' . $step;
        $urls['CreateAdmin'] = 'v1/companies/' . ($key) . '/admins';
        $urls['AcceptServiceTerms'] = 'v1/partner_managed_companies/' . ($key) . '/accept_terms_of_service';
        $urls['GetPaymentConfig'] = 'v1/companies/' . ($key) . '/payment_configs';
        $urls['UpdatePaymentConfig'] = 'v1/companies/' . ($key) . '/payment_configs';
        $urls['UpdatePayrollForDemo'] = 'v1/companies/' . ($key) . '/payrolls/' . $key1 . '/' . $step;

        // As of 2023

        // Admin calls
        $urls['addAdminToGusto'] = 'v1/companies/' . ($key) . '/admins';
        $urls['getAdminsFromGusto'] = 'v1/companies/' . ($key) . '/admins';
        // Signatory calls
        $urls['getSignatoriesFromGusto'] = 'v1/companies/' . ($key) . '/signatories';
        $urls['addSignatoryToGusto'] = 'v1/companies/' . ($key) . '/signatories';
        $urls['deleteSignatoryToGusto'] = 'v1/companies/' . ($key1) . '/signatories/' . $key;
        $urls['updateSignatoryToGusto'] = 'v1/companies/' . ($key1) . '/signatories/' . $key;
        // Sync
        $urls['getCompanyLocations'] = 'v1/companies/' . ($key) . '/locations';
        $urls['getCompanyFederalTax'] = 'v1/companies/' . ($key) . '/federal_tax_details';
        $urls['getCompanyIndustry'] = 'v1/companies/' . ($key) . '/industry_selection';
        $urls['getCompanyTaxLiabilities'] = 'v1/companies/' . ($key) . '/external_payrolls/tax_liabilities';
        $urls['getCompanyPaymentConfig'] = 'v1/companies/' . ($key) . '/payment_configs';
        $urls['GetCompletedProcessedPayrolls'] = 'v1/companies/' . ($key) . '/payrolls';

        $urls['getCompanyPayrolls'] = 'v1/companies/' . ($key) . '/payrolls';
        $urls['getPayrollReceipt'] = 'v1/payrolls/' . ($key) . '/receipt';

        // Employee urls
        $urls['getEmployeeJobsFromGusto'] = 'v1/employees/' . ($key) . '/jobs';
        $urls['createEmployeeJobOnGusto'] = 'v1/employees/' . ($key) . '/jobs';
        $urls['updateEmployeeJobOnGusto'] = 'v1/jobs/' . ($key) . '';
        $urls['updateEmployeeHomeAddressOnGusto'] = 'v1/employees/' . ($key) . '/home_address';
        $urls['getEmployeeHomeAddressFromGusto'] = 'v1/employees/' . ($key) . '/home_address';
        $urls['updateEmployeeJobCompensation'] = 'v1/compensations/' . ($key) . '';
        $urls['getEmployeeFederalTaxFromGusto'] = 'v1/employees/' . ($key) . '/federal_taxes';
        $urls['updateEmployeeFederalTaxOnGusto'] = 'v1/employees/' . ($key) . '/federal_taxes';
        $urls['getEmployeePaymentMethodFromGusto'] = 'v1/employees/' . ($key) . '/payment_method';
        $urls['updateEmployeePaymentMethodOnGusto'] = 'v1/employees/' . ($key) . '/payment_method';
        $urls['addEmployeeBankAccountToGusto'] = 'v1/employees/' . ($key) . '/bank_accounts';
        $urls['deleteEmployeeBankAccountToGusto'] = 'v1/employees/' . ($key) . '/bank_accounts';
        $urls['getEmployeeBankAccountToGusto'] = 'v1/employees/' . ($key) . '/bank_accounts';
        $urls['getEmployeeOnboardStatusFromGusto'] = 'v1/employees/' . ($key) . '/onboarding_status';
        $urls['finishEmployeeOnboardOnGusto'] = 'v1/employees/' . ($key) . '/onboarding_status';
        // Company URLs
        $urls['getCompanyOnboardStatusFromGusto'] = 'v1/companies/' . ($key) . '/onboarding_status';
        $urls['finishCompanyOnboardOnGusto'] = 'v1/companies/' . ($key) . '/finish_onboarding';
        $urls['approveCompanyOnboardOnGusto'] = 'v1/companies/' . ($key) . '/approve';
        $urls['getCompanyBankAccountsFromGusto'] = 'v1/companies/' . ($key) . '/bank_accounts';
        $urls['getTestDeposits'] = 'v1/companies/' . ($key) . '/bank_accounts/' . ($key1) . '/send_test_deposits';
        $urls['approveCompanyOnGusto'] = 'v1/companies/' . ($key) . '/approve';
        //
        $urls['getPayrollBlockers'] = 'v1/companies/' . ($key) . '/payrolls/blockers';
        $urls['createCustomEarningTypeOnGusto'] = 'v1/companies/' . ($key) . '/earning_types';

        return (GUSTO_MODE === 'test' ? GUSTO_URL_TEST : GUSTO_URL) . $urls[$index];
    }
}

if (!function_exists('MakeCall')) {
    function MakeCall($url, $options = [], $force = false)
    {
        $curl = curl_init();
        //
        $options =
            [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1
            ] + $options;
        //
        curl_setopt_array(
            $curl,
            $options
        );
        //
        $response = curl_exec($curl);
        //
        $info = curl_getinfo($curl);
        //
        curl_close($curl);
        // Save the request and response to database
        saveCall([
            'request_method' => $options[CURLOPT_CUSTOMREQUEST],
            'request_url' => $url,
            'request_body' => json_encode([
                'headers' => $options[CURLOPT_HTTPHEADER],
                'body' => $options[CURLOPT_POSTFIELDS]
            ]),
            'response_body' => $response,
            'response_code' => $info['http_code'],
            'response_headers' => json_encode($info)
        ]);
        // network issue
        if ($info['http_code'] == '0') {
            return [
                'errors' => [
                    [
                        'message' => 'Network issue: failed to reach payroll provider.'
                    ]
                ]
            ];
        }
        // Check for aut error
        if ($info['http_code'] == 401) {
            return [
                'errors' => [
                    'auth' => [
                        $info['http_code']
                    ]
                ]
            ];
        }
        //
        if ($info['content_type'] === 'application/pdf') {
            //
            $filename = 'employees/pay_stub/' . time() . '_' . (random_key(10)) . '_employee_pay_stub' . '.pdf';
            //
            $_this = &get_instance();
            //
            $_this->load->library('aws_lib');
            //
            $options = [
                'Bucket' => AWS_S3_BUCKET_NAME,
                'Key' => $filename,
                'Body' => $response,
                'ACL' => 'public-read',
                'ContentType' => $info['content_type']
            ];
            //
            $_this->aws_lib->put_object($options);
            //
            return [
                's3_file_name' => $filename,
                's3_file_url' => AWS_S3_BUCKET_URL . $filename
            ];
        }
        //
        // Convert to Associated Array and keep the long big ints
        $response = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        //
        return $response;
    }
}

if (!function_exists('saveCall')) {
    /**
     * saves request and response
     *
     * @param array $ins
     */
    function saveCall($ins)
    {
        //
        $CI = &get_instance();
        //
        $ins['created_at'] = getSystemDate();
        //
        $CI->db->insert('payroll_calls', $ins);
    }
}

if (!function_exists('MakeErrorArray')) {
    function MakeErrorArray($errorObj)
    {
        //
        $errorArray = [];
        //
        foreach ($errorObj as $index => $value) {
            //
            if (is_array($value)) {
                //
                foreach ($value as $index2 => $value2) {
                    //
                    if ($value2['message']) {
                        $errorArray = array_merge($errorArray, [$value2['message']]);
                    } else {
                        $errorArray = array_merge($errorArray, $value2);
                    }
                }
            } else {
                //
                if ($errorObj['common']) {
                    $errorArray = array_merge($errorArray, $errorObj['common']);
                } else {
                    //
                    $errorArray = array_merge($errorArray, $value);
                }
            }
        }
        //
        return $errorArray;
    }
}

if (!function_exists('UpdateToken')) {
    /**
     * Updates new generated token into
     * the DB
     * 
     * @param array $token
     * @param array $where
     * @param array $company
     */
    function UpdateToken($token, $where, $company)
    {
        //
        $_this = &get_instance();
        //
        $_this->load->model('Payroll_model', 'pm');
        //
        $_this->pm->UpdateToken([
            'access_token' => $token['access_token'],
            'refresh_token' => $token['refresh_token'],
            'old_access_token' => $company['access_token'],
            'old_refresh_token' => $company['refresh_token']
        ], $where);
    }
}

if (!function_exists('CacheHolder')) {
    /**
     * Cache the response of API call
     * 
     * @param string  $url
     * @param array   $data
     * @param boolean $force
     * 
     * @return
     */
    function CacheHolder($url, $data = [], $force = false)
    {
        //
        $_this = &get_instance();

        if ($_this->session->userdata($url) && !$force) {
            // return $_this->session->userdata($url);
        }
        if (!empty($data)) {
            $_this->session->set_userdata($url, $data);
        }
        return false;
    }
}


if (!function_exists('EmployeePayrollOnboardStatus')) {
    /**
     * Get the employee onboard status
     * 
     * @param array $o
     * @return
     */
    function EmployeePayrollOnboardStatus($o)
    {
        //
        $ra['details'] = [
            'personal_profile' => 1,
            'compensation' => 1,
            'home_address' => 1,
            'federal_tax' => 1,
            'state_tax' => 1,
            'payment_method' => 1,
            'employee_form_signing' => 1
        ];
        $ra['status'] = 'completed';
        if ($o['onboard_completed'] == 1) {
            return $ra;
        }
        //
        $ra = [];
        //
        $ra['status'] = 'pending';
        //
        $ra['details'] = [
            'personal_profile' => 0,
            'compensation' => 0,
            'home_address' => 0,
            'federal_tax' => 0,
            'state_tax' => 0,
            'payment_method' => 0,
            'employee_form_signing' => 0
        ];
        //
        $count = 0;
        //
        $ra['details']['personal_profile'] = $o['personal_profile'];
        $ra['details']['compensation'] = $o['compensation'];
        $ra['details']['home_address'] = $o['home_address'];
        $ra['details']['federal_tax'] = $o['federal_tax'];
        $ra['details']['state_tax'] = $o['state_tax'];
        $ra['details']['payment_method'] = $o['payment_method'];
        $ra['details']['employee_form_signing'] = $o['employee_form_signing'];
        //
        if ($ra['details']['personal_profile']) {
            $count++;
        }
        if ($ra['details']['compensation']) {
            $count++;
        }
        if ($ra['details']['home_address']) {
            $count++;
        }
        if ($ra['details']['federal_tax']) {
            $count++;
        }
        if ($ra['details']['state_tax']) {
            $count++;
        }
        if ($ra['details']['payment_method']) {
            $count++;
        }
        if ($ra['details']['employee_form_signing']) {
            $count++;
        }
        //
        if ($count == 7) {
            $ra['status'] = 'completed';
        }
        //
        return $ra;
    }
}

if (!function_exists('getEmployeeOnboardCheckForPayroll')) {
    /**
     * Check if employee qualifies for onboard
     *
     * @param array $employeeArray
     * @param bool  $returnBool
     * @return array|int
     */
    function getEmployeeOnboardCheckForPayroll(array $employeeArray, bool $returnBool = false)
    {
        //
        $returnArray = [];
        //
        if (GetVal($employeeArray['first_name']) == 'Not Specified') {
            $returnArray[] = 'First name is required.';
        }
        //
        if (GetVal($employeeArray['last_name']) == 'Not Specified') {
            $returnArray[] = 'Last name is required.';
        }
        //
        // if (GetVal($employeeArray['middle_name']) == 'Not Specified') {
        //     $returnArray[] = 'Middle initial is required.';
        // }
        //
        if (GetVal($employeeArray['dob']) == 'Not Specified' || $employeeArray['dob'] == '000-00-00') {
            $returnArray[] = 'Date of birth is required.';
        }
        //
        if (GetVal($employeeArray['ssn']) == 'Not Specified') {
            $returnArray[] = 'Social Security Number (SSN) is required.';
        }
        //
        if (GetVal($employeeArray['email']) == 'Not Specified') {
            $returnArray[] = 'Email is required.';
        }
        //
        return $returnBool ? count($returnArray) : $returnArray;
    }
}



// As of 2023

// Admin calls
if (!function_exists('addAdminToGusto')) {
    /**
     * Add admin on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $request
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function addAdminToGusto($request, $company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('addAdminToGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return addAdminToGusto($request, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}
if (!function_exists('getAdminsFromGusto')) {
    /**
     * Get admin on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getAdminsFromGusto($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getAdminsFromGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getAdminsFromGusto($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

// Signatories calls
if (!function_exists('getSignatoriesFromGusto')) {
    /**
     * Get signatories on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getSignatoriesFromGusto($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getSignatoriesFromGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getSignatoriesFromGusto($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}
if (!function_exists('addSignatoryToGusto')) {
    /**
     * Add signatory on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $request
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function addSignatoryToGusto($request, $company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('addSignatoryToGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return addSignatoryToGusto($request, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}
if (!function_exists('updateSignatoryToGusto')) {
    /**
     * Update signatory on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $request
     * @param array $signatoryId
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function updateSignatoryToGusto($request, $signatoryId, $company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('updateSignatoryToGusto', $signatoryId, $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateSignatoryToGusto($request, $signatoryId, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('deleteSignatoryToGusto')) {
    /**
     * Delete signatory on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param string $request
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function deleteSignatoryToGusto($signatoryUUID, $company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('deleteSignatoryToGusto', $signatoryUUID, $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return deleteSignatoryToGusto($signatoryUUID, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}


/**
 * Generates a one-dimensional array of errors
 *
 * @param array $errors
 * @return array
 */
function makeGustoErrorArray($errors)
{
    //
    $errorArray = [];
    //
    foreach ($errors as $error) {
        //
        if (isset($error['errors']) && is_array($error['errors'])) {
            $errorArray = array_merge($errorArray, makeGustoErrorArray($error['errors']));
        } else {
            $errorArray[] = $error['message'];
        }
    }
    //
    return $errorArray;
}


// -------------------------------------------------------
// Company calls
if (!function_exists('getCompanyLocations')) {
    /**
     * Get admin on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getCompanyLocations($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json',
            'X-Gusto-API-Version: 2024-03-01'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getCompanyLocations', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyLocations($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}
if (!function_exists('getCompanyFederalTax')) {
    /**
     * Get company federal tax on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getCompanyFederalTax($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getCompanyFederalTax', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyFederalTax($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('getCompanyIndustry')) {
    /**
     * Get company industry on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getCompanyIndustry($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getCompanyIndustry', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyIndustry($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('getCompanyTaxLiabilities')) {
    /**
     * Get company tax liabilities on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getCompanyTaxLiabilities($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getCompanyTaxLiabilities', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyTaxLiabilities($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('getCompanyPaymentConfig')) {
    /**
     * Get company payment configs on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getCompanyPaymentConfig($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getCompanyPaymentConfig', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyPaymentConfig($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('getCompanyPayrolls')) {
    /**
     * Get company payment configs on Gusto
     *
     * @method MakeCall
     * @method PayrollURL
     * @method RefreshToken
     * @method UpdateToken
     *
     * @param array $company
     * @param array $headers Optional
     * @return array
     */
    function getCompanyPayrolls($company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Content-Type: application/json'
        ];
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('getCompanyPayrolls', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyPayrolls($company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            //
            return $response;
        }
    }
}

if (!function_exists('GetUnProcessedPayrolls')) {
    function GetUnProcessedPayrolls($query, $company, $headers = [])
    {
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('GetUnProcessedPayrolls', $company['gusto_company_uid'], $query);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetUnProcessedPayrolls($query, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

if (!function_exists('getProcessedPayrollsReceipt')) {
    function getProcessedPayrollsReceipt($payRollId, $company, $headers = [])
    {
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('getPayrollReceipt', $payRollId);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getProcessedPayrollsReceipt($payRollId, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}


if (!function_exists('getPayrollsEmployeesCompensations')) {
    function getPayrollsEmployeesCompensations($payRollId, $company, $headers = [])
    {
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('GetSinglePayroll', $company['gusto_company_uid'], $payRollId);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getPayrollsEmployeesCompensations($payRollId, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

// Employee related calls
if (!function_exists('createAnEmployeeOnGusto')) {
    function createAnEmployeeOnGusto($request, $company, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'Accept: application/json',
            'Content-Type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('AddEmployeeToCompany', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createAnEmployeeOnGusto($request, $company, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


// Employee related calls
if (!function_exists('updateAnEmployeeOnGusto')) {
    function updateAnEmployeeOnGusto($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeOnGusto', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateAnEmployeeOnGusto($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('updateAnEmployeeAddressOnGusto')) {
    function updateAnEmployeeAddressOnGusto($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeAddress', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateAnEmployeeAddressOnGusto($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


// Employee related calls
if (!function_exists('createEmployeeJobDetail')) {
    function createEmployeeJobDetail($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('CreateEmployeeJob', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createEmployeeJobDetail($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('createEmployeeBankDetail')) {
    function createEmployeeBankDetail($request, $company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('AddEmployeeBandAccount', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createEmployeeBankDetail($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('getOnboardingEmployeePaymentMethod')) {
    function getOnboardingEmployeePaymentMethod($company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $response =  MakeCall(
            PayrollURL('GetEmployeePaymentMethod', $payroll_employee_uuid),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getOnboardingEmployeePaymentMethod($company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('updateOnboardingEmployeePaymentMethod')) {
    function updateOnboardingEmployeePaymentMethod($request, $company, $payroll_employee_uuid, $headers = [])
    {
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('UpdateEmployeePaymentMethod', $payroll_employee_uuid);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateOnboardingEmployeePaymentMethod($request, $company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('getEmployeeOnbordingStatus')) {
    function getEmployeeOnbordingStatus($company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('GetEmployeeOnboardedStatus', $payroll_employee_uuid);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeeOnbordingStatus($company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('getEmployeePayrollsDocuments')) {
    function getEmployeePayrollsDocuments($company, $payroll_employee_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('GetEmployeeForms', $payroll_employee_uuid);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeePayrollsDocuments($company, $payroll_employee_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('getEmployeeFormContent')) {
    function getEmployeeFormContent($company, $payroll_employee_uuid, $form_uuid, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('GetEmployeeFormContent', $payroll_employee_uuid, $form_uuid);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeeFormContent($company, $payroll_employee_uuid, $form_uuid, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

// Employee related calls
if (!function_exists('signPayrollEmployeeForm')) {
    function signPayrollEmployeeForm($company, $payroll_employee_uuid, $form_uuid, $request, $headers = [])
    {
        //
        $callHeaders = [
            'Authorization: Bearer ' . ($company['access_token']) . '',
            'accept: application/json',
            'content-type: application/json'
        ];
        //
        $callHeaders = array_merge($callHeaders, $headers);
        //
        $url = PayrollURL('SignEmployeeForm', $payroll_employee_uuid, $form_uuid);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => $callHeaders
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return signPayrollEmployeeForm($company, $payroll_employee_uuid, $form_uuid, $request, $headers);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


if (!function_exists('parseGustoErrors')) {
    /**
     * Parse gusto errors
     * 
     * @param array $errors
     */
    function parseGustoErrors(array $errors)
    {
        //
        $errorArray = [];
        //
        foreach ($errors as $error) {
            $errorArray[] = $error['message'];
        }
        //
        return $errorArray;
    }
}

if (!function_exists('hasGustoErrors')) {
    /**
     * Parse Gusto errors
     *
     * Convert Gusto errors to AutomotoHR errors
     * for handling errors
     *
     * @version 1.0
     *
     * @param mixed $response
     * @return array
     */
    function hasGustoErrors($response)
    {
        // set errors array
        $errors = [
            'errors' => []
        ];
        // if it's a single error
        if (isset($response['message'])) {
            $errors['errors'][] = $response['message'];
        } elseif (isset($response['errors']['invalid_grant'])) {
            $errors['errors'] = array_merge($errors['errors'], $response['errors']['invalid_grant']);
        } elseif (isset($response['errors'])) {
            foreach ($response['errors'] as $err) {
                //
                if (isset($err[0])) {
                    foreach ($err as $err0) {
                        $errors['errors'][] = $err0['message'];
                    }
                } else {

                    //
                    $errors['errors'][] = $err['message'];
                }
            }
        }

        //
        return $errors['errors'] ? $errors : [];
    }
}

if (!function_exists('updateOnboardEmployeeProfile')) {
    /**
     * Update request for Gusto
     * Update onboard employee profile on Gusto.
     * 
     * @version 1.0
     */
    function updateOnboardEmployeeProfile($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeOnGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateOnboardEmployeeProfile($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('getEmployeeJobsFromGusto')) {
    /**
     * Get the jobs
     * 
     * Get the employee all jobs with all compensations
     * from Gusto
     * 
     * @version 1.0
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function getEmployeeJobsFromGusto($employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('getEmployeeJobsFromGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeeJobsFromGusto($employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('createEmployeeJobOnGusto')) {
    /**
     * Create job
     * 
     * Create employee job on Gusto. Gusto will create
     * a default compensation
     * 
     * @version 1.0
     * @param array  $request
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function createEmployeeJobOnGusto($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('createEmployeeJobOnGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createEmployeeJobOnGusto($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


if (!function_exists('updateEmployeeJobOnGusto')) {
    /**
     * Update job
     * 
     * Update employee job on Gusto. Gusto will create
     * a default compensation
     * 
     * @version 1.0
     * @param array  $request
     * @param string $jobUUID
     * @param array  $company
     * @return array
     */
    function updateEmployeeJobOnGusto($request, $jobUUID, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('updateEmployeeJobOnGusto', $jobUUID),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateEmployeeJobOnGusto($request, $jobUUID, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}


if (!function_exists('updateEmployeeHomeAddressOnGusto')) {
    /**
     * Update employee home address
     * 
     * Update employee home address on Gusto.
     * 
     * @version 1.0
     * @param array  $request
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function updateEmployeeHomeAddressOnGusto($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('updateEmployeeHomeAddressOnGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateEmployeeHomeAddressOnGusto($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('getEmployeeHomeAddressFromGusto')) {
    /**
     * Get employee home address
     * 
     * Get employee home address on Gusto.
     * 
     * @version 1.0
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function getEmployeeHomeAddressFromGusto($employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('getEmployeeHomeAddressFromGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeeHomeAddressFromGusto($employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('updateEmployeeJobCompensation')) {
    /**
     * Update employee compensation
     * 
     * Get employee compensation on Gusto.
     * 
     * @version 1.0
     * @param array  $request
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function updateEmployeeJobCompensation($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('updateEmployeeJobCompensation', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateEmployeeJobCompensation($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('getEmployeeFederalTaxFromGusto')) {
    /**
     * Get employee federal tax
     * 
     * Get employee federal tax on Gusto.
     * 
     * @version 1.0
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function getEmployeeFederalTaxFromGusto($employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('getEmployeeFederalTaxFromGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeeFederalTaxFromGusto($employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('updateEmployeeFederalTaxOnGusto')) {
    /**
     * Update employee federal tax
     * 
     * Get employee federal tax on Gusto.
     * 
     * @version 1.0
     * @param array  $request
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function updateEmployeeFederalTaxOnGusto($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('updateEmployeeFederalTaxOnGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateEmployeeFederalTaxOnGusto($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('getEmployeePaymentMethodFromGusto')) {
    /**
     * Get employee payment method
     * 
     * Get employee payment method on Gusto.
     * 
     * @version 1.0
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function getEmployeePaymentMethodFromGusto($employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('getEmployeePaymentMethodFromGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeePaymentMethodFromGusto($employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('updateEmployeePaymentMethodOnGusto')) {
    /**
     * Update employee payment method
     * 
     * Update employee payment method on Gusto.
     * 
     * @version 1.0
     * @param array  $request
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function updateEmployeePaymentMethodOnGusto($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('updateEmployeePaymentMethodOnGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return updateEmployeePaymentMethodOnGusto($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('addEmployeeBankAccountToGusto')) {
    /**
     * Add employee bank account
     * 
     * Add employee bank account on Gusto.
     * 
     * @version 1.0
     * @param array  $request
     * @param string $employeeId
     * @param array  $company
     * @return array
     */
    function addEmployeeBankAccountToGusto($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('addEmployeeBankAccountToGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return addEmployeeBankAccountToGusto($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('getCompanyOnboardStatusFromGusto')) {
    /**
     * Add employee bank account
     * 
     * Add employee bank account on Gusto.
     * 
     * @version 1.0
     * @param array  $company
     * @return array
     */
    function getCompanyOnboardStatusFromGusto($company)
    {
        //
        $response =  MakeCall(
            PayrollURL('getCompanyOnboardStatusFromGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyOnboardStatusFromGusto($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
if (!function_exists('finishCompanyOnboardOnGusto')) {
    /**
     * Add employee bank account
     * 
     * Add employee bank account on Gusto.
     * 
     * @version 1.0
     * @param array  $company
     * @return array
     */
    function finishCompanyOnboardOnGusto($company)
    {
        //
        $response =  MakeCall(
            PayrollURL('finishCompanyOnboardOnGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return finishCompanyOnboardOnGusto($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
if (!function_exists('approveCompanyOnboardOnGusto')) {
    /**
     * Add employee bank account
     * 
     * Add employee bank account on Gusto.
     * 
     * @version 1.0
     * @param array  $company
     * @return array
     */
    function approveCompanyOnboardOnGusto($company)
    {
        //
        $response =  MakeCall(
            PayrollURL('approveCompanyOnboardOnGusto', $company['gusto_company_uid']),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return approveCompanyOnboardOnGusto($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('getEmployeeOnboardStatusFromGusto')) {
    /**
     * Add employee bank account
     * 
     * Add employee bank account on Gusto.
     * 
     * @version 1.0
     * @param array  $company
     * @return array
     */
    function getEmployeeOnboardStatusFromGusto($employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('getEmployeeOnboardStatusFromGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getEmployeeOnboardStatusFromGusto($employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

if (!function_exists('finishEmployeeOnboardOnGusto')) {
    /**
     * Add employee bank account
     * 
     * Add employee bank account on Gusto.
     * 
     * @version 1.0
     * @param array  $company
     * @return array
     */
    function finishEmployeeOnboardOnGusto($request, $employeeId, $company)
    {
        //
        $response =  MakeCall(
            PayrollURL('finishEmployeeOnboardOnGusto', $employeeId),
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Accept: application/json',
                    'Content-Type: application/json',
                    'X-Gusto-API-Version: 2024-03-01'
                )
            ]
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, [
                    'gusto_company_uid' => $company['gusto_company_uid']
                ], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return finishEmployeeOnboardOnGusto($request, $employeeId, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if (!function_exists('payRollBlockers')) {
    function payRollBlockers($company)
    {
        //

        $url = PayrollURL('getPayrollBlockers', $company['gusto_company_uid']);
        //
        $response =  MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            false
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return payRollBlockers($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            CacheHolder($url, $response);
            return $response;
        }
    }
}

//
if (!function_exists('getTestDeposits')) {
    function getTestDeposits(string $bankAccountUid, $company)
    {
        //
        $url = PayrollURL('getTestDeposits', $company['gusto_company_uid'], $bankAccountUid);
        //
        $response = MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            false
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getTestDeposits($bankAccountUid, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            return $response;
        }
    }
}



//
if (!function_exists('getCompanyBankAccountsFromGusto')) {
    function getCompanyBankAccountsFromGusto($company)
    {
        //
        $url = PayrollURL('getCompanyBankAccountsFromGusto', $company['gusto_company_uid']);
        //
        $response = MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            false
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return getCompanyBankAccountsFromGusto($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            return $response;
        }
    }
}


//
if (!function_exists('approveCompanyOnGusto')) {
    function approveCompanyOnGusto($company)
    {
        //
        $url = PayrollURL('approveCompanyOnGusto', $company['gusto_company_uid']);
        //
        $response = MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            false
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return approveCompanyOnGusto($company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            return $response;
        }
    }
}


//
if (!function_exists('createCustomEarningTypeOnGusto')) {
    function createCustomEarningTypeOnGusto($customEarningType, $company)
    {
        //
        $url = PayrollURL('createCustomEarningTypeOnGusto', $company['gusto_company_uid']);
        //
        $response = MakeCall(
            $url,
            [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode(['name' => $customEarningType]),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . ($company['access_token']) . '',
                    'Content-Type: application/json'
                )
            ],
            false
        );
        //
        if (isset($response['errors']['auth'])) {
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if (isset($tokenResponse['access_token'])) {
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return createCustomEarningTypeOnGusto($customEarningType, $company);
            } else {
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else {
            return $response;
        }
    }
}

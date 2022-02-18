<?php

if(!function_exists('PayrollAuth')){
    function PayrollAuth($company){
        //
        return MakeCall(
            PayrollURL('Me'),[
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).''
                )
            ] 
        );
    }
}

if(!function_exists('RefreshToken')){
    function RefreshToken($request){
        //
        $key = 'client_id='.(GUSTO_CLIENT_ID).'&';
        $key .= 'client_secret='.(GUSTO_CLIENT_SECRET).'&';
        $key .= 'redirect_uri='.(GUSTO_CLIENT_REDIRECT_URL).'&';
        $key .= 'refresh_token='.($request['refresh_token']).'&';
        $key .= 'grant_type=refresh_token';
        //
        return MakeCall(
            PayrollURL('RefreshToken', $key), [
                CURLOPT_CUSTOMREQUEST => 'POST'
            ]
        );
    }
}

//
if(!function_exists('CreatePartnerCompany')){
    function CreatePartnerCompany($request){
        //
        return MakeCall(
            PayrollURL('CreatePartnerCompany'),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Token '.(GUSTO_KEY_TEST).'',
                    'Content-Type: application/json'
                )
            ] 
        );
    }
}



if(!function_exists('DeleteCompanyEmployee')){
    function DeleteCompanyEmployee($employee_id, $company){
        //
        $response =  MakeCall(
            PayrollURL('DeleteEmployee', $employee_id),[
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteCompanyEmployee($employee_id, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if(!function_exists('AddCompanyLocation')){
    function AddCompanyLocation($request, $company){
        //
        $response =  MakeCall(
            PayrollURL('AddCompanyLocation', $company['gusto_company_uid']),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddCompanyLocation($request, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}

//
if(!function_exists('Payrolls')){
    function Payrolls($company){
        //
        $response =  MakeCall(
            PayrollURL('Payrolls', $company['gusto_company_uid']),[
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return Payrolls($company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('AddBankAccountToPayroll')){
    function AddBankAccountToPayroll($request, $company){
        //
        $response =  MakeCall(
            PayrollURL('AddBankAccountToPayroll', $company['employeeId']),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddBankAccountToPayroll($request, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('DeleteBankAccountToPayroll')){
    function DeleteBankAccountToPayroll($request, $company){
        //
        $response =  MakeCall(
            PayrollURL('DeleteBankAccountToPayroll', $request['employee_id'], $request['bank_account_id']),[
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteBankAccountToPayroll($request, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('AddCompanyBankAccountToPayroll')){
    function AddCompanyBankAccountToPayroll($request, $company){
        //
        $response =  MakeCall(
            PayrollURL('AddCompanyBankAccountToPayroll', $company['gusto_company_uid']),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddCompanyBankAccountToPayroll($request, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('GetSinglePayroll')){
    function GetSinglePayroll($query, $company, $step){
        //
        $url = PayrollURL('GetSinglePayroll', $company['gusto_company_uid'], $company['payroll_id'], $step);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetSinglePayroll($query, $company, $step);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
if(!function_exists('GetCompanyEmployees')){
    function GetCompanyEmployees($company){
        //
        $url = PayrollURL('GetCompanyEmployees', $company['gusto_company_uid']);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetCompanyEmployees($company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
if(!function_exists('GetCompany')){
    function GetCompany($company){
        //
        $url = PayrollURL('GetCompany', $company['gusto_company_uid']);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetCompany($company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

//
if(!function_exists('UpdatePayrollById')){
    function UpdatePayrollById($request, $company){
        //
        $url = PayrollURL('UpdatePayrollById', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdatePayrollById($request, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('CalculatePayroll')){
    function CalculatePayroll($company){
        //
        $url = PayrollURL('CalculatePayroll', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CalculatePayroll($company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('CancelPayrollById')){
    function CancelPayrollById($company){
        //
        $url = PayrollURL('CancelPayrollById', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CancelPayrollById($company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}

//
if(!function_exists('SubmitPayrollById')){
    function SubmitPayrollById($company){
        //
        $url = PayrollURL('SubmitPayrollById', $company['gusto_company_uid'], $company['payroll_id']);
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return SubmitPayrollById($company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            //
            return $response;
        }
    }
}


// Employee related calls
if(!function_exists('AddEmployeeToCompany')){
    function AddEmployeeToCompany($request, $company){
        //
        $response =  MakeCall(
            PayrollURL('AddEmployeeToCompany', $company['gusto_company_uid']),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return AddEmployeeToCompany($request, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if(!function_exists('UpdateEmployeeAddress')){
    function UpdateEmployeeAddress($request, $employeeId, $company){
        //
        $response =  MakeCall(
            PayrollURL('UpdateEmployeeAddress', $employeeId),[
                CURLOPT_CUSTOMREQUEST => 'PUT',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return UpdateEmployeeAddress($request, $employeeId, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if(!function_exists('CreateEmployeeAddress')){
    function CreateEmployeeAddress($request, $employeeId, $company){
        //
        $response =  MakeCall(
            PayrollURL('CreateEmployeeAddress', $employeeId),[
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return CreateEmployeeAddress($request, $employeeId, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}
//
if(!function_exists('DeleteOnboardingEmployee')){
    function DeleteOnboardingEmployee($employeeId, $company){
        //
        $response =  MakeCall(
            PayrollURL('DeleteOnboardingEmployee', $employeeId),[
                CURLOPT_CUSTOMREQUEST => "DELETE",
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ] 
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return DeleteOnboardingEmployee($employeeId, $company);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        }
        //
        return $response;
    }
}



// As of 12/09/2021

if(!function_exists('GetCompanyStatus')){
    function GetCompanyStatus($company){
        //
        $response =  MakeCall(
            PayrollURL('GetCompanyStatus', $company['gusto_company_uid']),[
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
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
            if(isset($tokenResponse['access_token'])){
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

if(!function_exists('CreateCompanyFlowLink')){
    function CreateCompanyFlowLink($company, $force = false){
        //
        $url = PayrollURL('GetCompanyFlows', $company['gusto_company_uid']);
        //
        if(!$force){
            //
            $response = CacheHolder($url);
            //
            if($response){
                return $response;
            }
        }
        //
        $request = array();
        //
        $request['flow_type'] = "company_onboarding";
        $request['entity_type'] = "Company";
        $request['entity_uuid'] = $company['gusto_company_uid'];
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => json_encode($request),
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
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
            if(isset($tokenResponse['access_token'])){
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

if(!function_exists('PayPeriods')){
    function PayPeriods($company, $startDate, $force = false){
        //

        $url = PayrollURL('PayPeriods', $company['gusto_company_uid'], $startDate);
        //
        if(!$force){
            $tr = CacheHolder($url);
            if($tr){
                return $tr;
            }
        }
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
            ],
            $force
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return PayPeriods($company, $startDate, $force);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            CacheHolder($url, $response);
            return $response;
        }
    }
}

if(!function_exists('GetUnProcessedPayrolls')){
    function GetUnProcessedPayrolls($query, $company, $force = false){
        //
        $url = PayrollURL('GetUnProcessedPayrolls', $company['gusto_company_uid'], $query);
        //
        if(!$force){
            $tr = CacheHolder($url);
            if($tr){
                return $tr;
            }
        }
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
                ],
                $force
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetUnProcessedPayrolls($query, $company, $force);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}

if(!function_exists('GetUnProcessedPayrolls')){
    function GetProcessedPayrolls($query, $company, $force = false){
        //
        $url = PayrollURL('GetProcessedPayrolls', $company['gusto_company_uid'], $query);
        //
        if(!$force){
            $tr = CacheHolder($url);
            if($tr){
                // return $tr;
            }
        }
        //
        $response =  MakeCall(
            $url, [
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer '.($company['access_token']).'',
                    'Content-Type: application/json'
                )
                ],
                $force
        );
        //
        if(isset($response['errors']['auth'])){
            // Lets Refresh the token
            $tokenResponse = RefreshToken([
                'access_token' => $company['access_token'],
                'refresh_token' => $company['refresh_token']
            ]);
            //
            if(isset($tokenResponse['access_token'])){
                //
                UpdateToken($tokenResponse, ['gusto_company_uid' => $company['gusto_company_uid']], $company);
                //
                $company['access_token'] = $tokenResponse['access_token'];
                $company['refresh_token'] = $tokenResponse['refresh_token'];
                //
                return GetProcessedPayrolls($query, $company, $force);
            } else{
                return ['errors' => ['invalid_grant' => [$tokenResponse['error_description']]]];
            }
        } else{
            CacheHolder($url, $response);
            //
            return $response;
        }
    }
}


// Internal use functions

if(!function_exists('PayrollURL')){
    function PayrollURL($index, $key = 0, $key1 = 0, $step = null){
        //
        $urls = [];
        $urls['Me'] = 'v1/me';
        $urls['CreatePartnerCompany'] = 'v1/partner_managed_companies';
        $urls['RefreshToken'] = 'oauth/token?'.($key);
        $urls['PayPeriods'] = 'v1/companies/'.($key).'/pay_periods?start_date='.$key1;
        $urls['Payrolls'] = 'v1/companies/'.($key).'/pay_schedules';
        $urls['AddBankAccountToPayroll'] = 'v1/employees/'.($key).'/bank_accounts';
        $urls['DeleteBankAccountToPayroll'] = 'v1/employees/'.($key).'/bank_accounts/'.$key1;
        $urls['AddCompanyBankAccountToPayroll'] = 'v1/companies/'.($key).'/bank_accounts';
        $urls['GetSinglePayroll'] = 'v1/companies/'.($key).'/payrolls/'.($key1).'?show_calculation=false&include=benefits,deductions,taxes';
        // if ($step == 3) {
        //     $urls['GetSinglePayroll'] = 'v1/companies/'.($key).'/payrolls/'.($key1).'?show_calculation=true&include=benefits,deductions,taxes';
        // } else {
            //     $urls['GetSinglePayroll'] = 'v1/companies/'.($key).'/payrolls/'.($key1).'?show_calculation=false&include=benefits,deductions,taxes';
            // }
        $urls['GetCompanyEmployees'] = 'v1/companies/'.($key).'/employees';
        $urls['UpdatePayrollById'] = 'v1/companies/'.($key).'/payrolls/'.($key1);
        $urls['CalculatePayroll'] = 'v1/companies/'.($key).'/payrolls/'.($key1).'/calculate';
        $urls['CancelPayrollById'] = 'v1/companies/'.($key).'/payrolls/'.($key1).'/cancel';
        $urls['SubmitPayrollById'] = 'v1/companies/'.($key).'/payrolls/'.($key1).'/submit';
        $urls['GetCompany'] = 'v1/companies/'.($key);
        $urls['AddCompanyLocation'] = 'v1/companies/'.($key).'/locations';
        $urls['GetCompanyFlows'] = 'v1/companies/'.($key).'/flows';
        $urls['GetCompanyStatus'] = 'v1/companies/'.($key).'/onboarding_status';
        $urls['DeleteEmployee'] = 'v1/employees/'.($key);
        // As of 12/09/2021
        $urls['GetUnProcessedPayrolls'] = 'v1/companies/'.($key).'/payrolls'.($key1);
        $urls['GetProcessedPayrolls'] = 'v1/companies/'.($key).'/payrolls'.($key1);
        
        // Employee routes
        $urls['AddEmployeeToCompany'] = 'v1/companies/'.($key).'/employees';
        $urls['UpdateEmployeeAddress'] = 'v1/employees/'.($key).'/home_address';
        $urls['CreateEmployeeAddress'] = 'v1/employees/'.($key).'/jobs';
        $urls['DeleteOnboardingEmployee'] = 'v1/employees/'.($key);
        //
        return (GUSTO_MODE === 'test' ? GUSTO_URL_TEST : GUSTO_URL).$urls[$index];
    }
}

if(!function_exists('MakeCall')){
    function MakeCall($url, $options = [], $force = false){
        //
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
        // Check for aut error
        if($info['http_code'] == 401){
            return [
                'errors' => [
                    'auth' => [
                        $info['http_code']
                    ] 
                ]
            ];   
        }
        // Convert to Associated Array and keep the long big ints
        $response = json_decode($response, true, 512, JSON_BIGINT_AS_STRING);
        //
        return $response; 
    }
}

if(!function_exists('MakeErrorArray')){
    function MakeErrorArray($errorObj){
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

if(!function_exists('UpdateToken')){
    /**
     * Updates new generated token into
     * the DB
     * 
     * @param array $token
     * @param array $where
     * @param array $company
     */
    function UpdateToken($token, $where, $company){
        //
        $_this =& get_instance();
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

if(!function_exists('CacheHolder')){
    /**
     * Cache the response of API call
     * 
     * @param string  $url
     * @param array   $data
     * @param boolean $force
     * 
     * @return
     */
    function CacheHolder($url, $data = [], $force = false){
        //
        // $_this =&get_instance();
        //
        // if($_this->session->userdata($url) && !$force){
        //     // return $_this->session->userdata($url);
        // }
        // if(!empty($data)){
        //     $_this->session->set_userdata($url, $data);
        // }
    }
}


if(!function_exists('EmployeePayrollOnboardStatus')){
    /**
     * Get the employee onboard status
     * 
     * @param array $o
     * @return
     */
    function EmployeePayrollOnboardStatus($o){
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
            'payment_method' => 0
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
        //
        if($ra['details']['personal_profile']){ $count++; }
        if($ra['details']['compensation']){ $count++; }
        if($ra['details']['home_address']){ $count++; }
        if($ra['details']['federal_tax']){ $count++; }
        if($ra['details']['state_tax']){ $count++; }
        if($ra['details']['payment_method']){ $count++; }
        //
        if($count == 6){
            $ra['status'] = 'completed';
        }
        //
        return $ra;
    }
}
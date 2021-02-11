<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Expirations_manager extends Public_Controller {
    public function __construct() {
        parent::__construct();
        //Load Models
        $this->load->model('dashboard_model');
        $this->load->model('expiries_manager_model');
    }

    public function index($export_flag = NULL) {
        if ($this->session->userdata('logged_in')) {
            $data['session']                                                    = $this->session->userdata('logged_in');
            $security_sid                                                       = $data['session']['employer_detail']['sid'];
            $security_details                                                   = db_get_access_level_details($security_sid);
            $data['security_details']                                           = $security_details; 
            check_access_permissions($security_details, 'my_settings', 'expiries_manager'); // Param2: Redirect URL, Param3: Function Name
                       
            $data['title']                                                      = "Expirations Manager";
            $company_id                                                         = $data['session']['company_detail']['sid'];

            //getting userdata from DB
            $employer_id                                                        = $data["session"]["employer_detail"]["sid"];
            $data['employerData']                                               = $this->dashboard_model->getEmployerDetail($employer_id);

            //getting userdata from DB
            $company_id                                                         = $data["session"]["company_detail"]["sid"];
            $data['companyData']                                                = $this->dashboard_model->getCompanyDetail($company_id);
            $data['companyData']['locationDetail']                              = db_get_state_name($data['companyData']['Location_State']);

            //Get All Users of the Company
            $users = $this->expiries_manager_model->GetAllUsers($company_id);

            //Get Licenses Information
            $licenses = array();

            foreach($users as $user){
                $userLicenses = $this->expiries_manager_model->GetAllLicenses($user['sid']);
                foreach($userLicenses as $userLicense)
                {
                    $licenses[] = $userLicense;
                }
            }

            //Attach User Detail to License & Unserialize License Information
            foreach($licenses as $key => $license){
                //Attach User Detail
                $user_details = $this->expiries_manager_model->GetUserDetails($license['users_sid']);
                $licenses[$key]['user_details'] = $user_details[0];

                // Reset the serialize length
                // Added on: 27-06-2019
                $license['license_details'] = @preg_replace_callback('!s:(\d+):"(.*?)";!', function($x){
                    return "s:".strlen($x[2]).":\"".$x[2]."\";";
                }, $license['license_details']);

                //Unserialize License Information
                $licenseDetails = @unserialize($license['license_details']);
                $licenses[$key]['license_details'] = $licenseDetails;
            }

            $expired = array();
            $expiringInOneDay = array();
            $expiringInSevenDays = array();
            $expiringInThirtyDays = array();

            $currentDate = strtotime(date('Y-m-d H:i:s'));
            $TodayPlusOneDay = date_add_day(date('Y-m-d H:i:s'), 1);
            $TodayPlusSevenDays = date_add_days(date('Y-m-d H:i:s'), 7);
            $TodayPlusThirtyDays = date_add_days(date('Y-m-d H:i:s'), 30);

            foreach($licenses as $key => $license){
                $licenseDetails = $license['license_details'];
                //
                $expiry = formatDateForDb($licenseDetails['license_expiration_date']);
                //Expired
                if(!empty($expiry) && $expiry != '0000-00-00' && hasExpired($expiry, $currentDate)){
                    $expired[] = $license;
                }

                //Expiring in One Day
                /*
                if(show_expiry_date_alert($currentDate, $TodayPlusOneDay, $expiry)){
                    $expiringInOneDay[] = $license;
                }
                */

                /*
                //Expiring in 7 Days
                if(show_expiry_date_alert($currentDate, $TodayPlusSevenDays, $expiry)){
                    $expiringInSevenDays[] = $license;
                }
                */

                //Expiring in 30 Days

                if(show_expiry_date_alert($currentDate, $TodayPlusThirtyDays, $expiry)){
                    $expiringInThirtyDays[] = $license;
                }


            }

            $data['licenses'] = $licenses;
            $data['expired'] = $expired;
            //$data['expiringInOneDay'] = $expiringInOneDay;
            //$data['expiringInSevenDays'] = $expiringInSevenDays;
            $data['expiringInThirtyDays'] = $expiringInThirtyDays;

            $rows = '';
            if($export_flag == 'export'){
                foreach($expired as $item){
                    if($item['license_type'] == 'drivers'){
                        $type = "Drivers License";
                    }
                    else if($item['license_type'] == 'occupational') {
                        $type = "Occupational License";
                    }
                    $name = ucwords($item['user_details']['first_name']) . ' ' . ucwords($item['user_details']['last_name']);
                    $exp_date = ucwords($item['license_details']['license_expiration_date']);
                    $rows .= $type . ',' . $name . ',' . $exp_date . PHP_EOL ;
                }
                $rows .= PHP_EOL;
                $rows .= 'Expiring In Thirty Days' . PHP_EOL;
                foreach($expiringInThirtyDays as $item){
                    if($item['license_type'] == 'drivers'){
                        $type = "Drivers License";
                    }
                    else if($item['license_type'] == 'occupational') {
                        $type = "Occupational License";
                    }
                    $name = ucwords($item['user_details']['first_name']) . ' ' . ucwords($item['user_details']['last_name']);
                    $exp_date = ucwords($item['license_details']['license_expiration_date']);
                    $rows .= $type . ',' . $name . ',' . $exp_date . PHP_EOL;
                }
                $header_row = 'License Type	,User Full Name	,Expiration Date';
                $file_content = '';
                $file_content .= $header_row . ',' . PHP_EOL;
                $file_content .= $rows;
                $file_size = 0;

                if (function_exists('mb_strlen')) {
                    $file_size = mb_strlen($file_content, '8bit');
                } else {
                    $file_size = strlen($file_content);
                }

                header('Pragma: public');     // required
                header('Expires: 0');         // no cache
                header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                header('Cache-Control: private', false);
                header('Content-Type: text/csv');  // Add the mime type from Code igniter.
                header('Content-Disposition: attachment; filename="expired_applicants_' . date('Y_m_d-H:i:s') . '.csv"');  // Add the file name
                header('Content-Transfer-Encoding: binary');
                header('Content-Length: ' . $file_size); // provide file size
                header('Connection: close');
                echo $header_row . ',' . PHP_EOL;
                echo $rows;
                die();
            }

            $this->load->view('main/header', $data);
            $this->load->view('expiries_manager/index', $data);
            $this->load->view('main/footer', $data);
        } else {
            redirect('login', "refresh");
        }
    }
}
